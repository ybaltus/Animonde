<?php

namespace App\Manager;

use App\Entity\Animal;
use App\Manager\ManagerInterface;
use Vendor\database\Database;
use Vendor\database\Manager;

class AnimalManager extends Manager implements ManagerInterface
{
    private string $table = "animal";

    /**
     * Récupérer un animal par son id
     */
    public function findOne(mixed $entity): Animal|bool
    {
        $statement = "SELECT * FROM $this->table WHERE id = :id LIMIT 1";
        $prepare = $this->db?->prepare($statement);
        $prepare?->bindValue(":id", $entity);
        $prepare?->execute();
        $prepare?->setFetchMode(\PDO::FETCH_CLASS, Animal::class);
        return $prepare?->fetch();
    }

    /**
     * Récupérer un animal par son slug
     */
    public function findOneBySlug(string $nameSlug): Animal|bool
    {
        $statement = "SELECT * FROM $this->table WHERE nameSlug = :nameSlug LIMIT 1";
        $prepare = $this->db?->prepare($statement);
        $prepare?->bindValue(":nameSlug", $nameSlug);
        $prepare?->execute();
        $prepare?->setFetchMode(\PDO::FETCH_CLASS, Animal::class);
        return $prepare?->fetch();
    }

    /**
     * Récupérer les 10 derniers animaux
     */
    public function findLastTen(): array
    {
        $sql = $this->db?->query("SELECT * FROM $this->table WHERE available = 1 order by created_at LIMIT 10");
        return $sql?->fetchAll(\PDO::FETCH_CLASS, Animal::class);
    }

    /**
     * Récupérer tous les animaux
     */
    public function findAll(): array
    {
        $sql = $this->db?->query("SELECT * FROM $this->table");
        return $sql?->fetchAll(\PDO::FETCH_CLASS, Animal::class);
    }

    /**
     * Récupérer tous les animaux disponibles
     */
    public function findAllAvailable(): array
    {
        $sql = $this->db?->query("SELECT * FROM $this->table WHERE available = 1");
        return $sql?->fetchAll(\PDO::FETCH_CLASS, Animal::class);
    }

    /**?
     * Récupérer tous les animaux indisponibles
     */
    public function findAllNotAvailable(): array
    {
        $sql = $this->db?->query("SELECT * FROM $this->table WHERE available = 0");
        return $sql?->fetchAll(\PDO::FETCH_CLASS, Animal::class);
    }

    /**
     * Ajouter un animal
     */
    public function add(mixed $entity): Animal|bool
    {
        $statement = "INSERT INTO $this->table (name, nameSlug, race_id, weight, size, image, available) VALUE (:name, :nameSlug, :race, :weight, :size, :image, :available)";
        $prepare = $this->db?->prepare($statement);
        $prepare?->bindValue(":name", $entity->getName());
        $prepare?->bindValue(":nameSlug", $entity->getNameSlug());
        $prepare?->bindValue(":race", $entity->getRace());
        $prepare?->bindValue(":weight", $entity->GetWeight());
        $prepare?->bindValue(":size", $entity->getSize());
        $prepare?->bindValue(":image", $entity->getImage());
        $prepare?->bindValue(":available", $entity->getAvailable());
        $prepare?->execute();
        return $prepare?->fetch();
    }

    /**
     * Éditer un animal
     */
    public function edit(mixed $entity): void
    {
        $statement = "UPDATE $this->table SET name=:name, race_id=:race, weight=:weight, size=:size, available=:available WHERE id=:id";
        $prepare = $this->db?->prepare($statement);
        $prepare?->bindValue(":id", $entity->getId());
        $prepare?->bindValue(":name", $entity->getName());
        $prepare?->bindValue(":race", $entity->getRace());
        $prepare?->bindValue(":weight", $entity->GetWeight());
        $prepare?->bindValue(":size", $entity->getSize());
        $prepare?->bindValue(":available", $entity->getAvailable());
        $prepare?->execute();
    }

    /**
     * Supprimer un animal
     */
    public function delete(mixed $entity): void
    {
        $statement = "DELETE FROM $this->table WHERE id = :id";
        $prepare = $this->db?->prepare($statement);
        $prepare?->bindValue(":id", $entity);
        $prepare?->execute();
    }
}
