<?php

namespace App\Manager;

use App\Entity\AnimalRace;
use App\Manager\ManagerInterface;
use Vendor\database\Database;
use Vendor\database\Manager;

class AnimalRaceManager extends Manager implements ManagerInterface {

    private string $table = "animalRace";

    /**
     * Récupérer une race d'animal grâce à son id
     */
    public function findOne($entity): mixed
    {
        $statement = "SELECT * FROM $this->table WHERE id = :id LIMIT 1";
        $prepare = $this->db->prepare($statement);
        $prepare->bindValue(":id", $entity->getId());
        $prepare->execute();
        return $prepare->fetch(\PDO::FETCH_CLASS, AnimalRace::class);
    }

    /**
     * Récupérer une race par son slug
     */
    public function findOneBySlug(string $nameSlug): mixed
    {
        $statement = "SELECT * FROM $this->table WHERE nameSlug = :nameSlug LIMIT 1";
        $prepare = $this->db->prepare($statement);
        $prepare->bindValue(":nameSlug", $nameSlug);
        $prepare->execute();
        $prepare->setFetchMode(\PDO::FETCH_CLASS, AnimalRace::class);
        return $prepare->fetch();
    }

    /**
     * Récupérer toutes les races
     */
    public function findAll()
    {
        $query = $this->db->query("SELECT * FROM $this->table");
        return $query->fetchAll(\PDO::FETCH_CLASS, AnimalRace::class);
    }

    /**
     * Ajoute une race de pokemon
     */
    public function add($entity): void
    {
        $statement = "INSERT INTO $this->table (name, nameslug) 
                        VALUES (:name, :nameslug)";

        $prepare = $this->db->prepare($statement);
        $prepare->bindValue(":name", $entity->getName());
        $prepare->bindValue(":nameslug", $entity->getNameSlug());
        $prepare->execute();
    }

    /**
     * Modifier une race
     */
    public  function edit($entity): void
    {$statement = "UPDATE $this->table 
                    SET name = :name 
                    nameslug = :nameslug 
                    WHERE id = :id LIMIT 1";

        $prepare = $this->db->prepare($statement);
        $prepare->bindValue(":name", $entity->getName());
        $prepare->bindValue(":nameslug", $entity->getNameSlug());
        $prepare->bindValue(":id", $entity->getId());
        $prepare->execute();
    }

    /**
     * Supprimer une race
     */
    public function delete($entity): void
    {
        $statement = "DELETE FROM $this->table WHERE id = :id";
        $prepare = $this->db->prepare($statement);
        $prepare->bindValue(":id", $entity->getId());
        $prepare->execute();
    }

}