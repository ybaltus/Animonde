<?php

namespace App\Manager;

use App\Entity\Contact;
use App\Manager\ManagerInterface;
use Vendor\database\Database;
use Vendor\database\Manager;

class ContactManager extends Manager implements ManagerInterface
{
    /**
     * Récupérer un contact avec son id
     */
    public function findOne(mixed $entity): Contact|bool
    {
        $statement = "SELECT * FROM contact WHERE id = :id LIMIT 1";
        $prepare = $this->db?->prepare($statement);
        $prepare?->bindValue(":id", $entity->getId());
        $prepare?->execute();
        return $prepare?->fetch(\PDO::FETCH_CLASS, Contact::class);
    }

    /**
     * Récupérer tous les contacts
     */
    public function findAll(): array
    {
        $query = $this->db?->query("SELECT * FROM contact order by created_at");
        return $query?->fetchAll(\PDO::FETCH_CLASS, Contact::class);
    }

    /**
     * Ajouter un contact
     */
    public function add(mixed $entity): void
    {
        $statement = "INSERT INTO contact (subject, message, user_id, animal_id) 
                        VALUES (:subject, :message, :user, :animal)";

        $prepare = $this->db?->prepare($statement);
        $prepare?->bindValue(":subject", $entity->getSubject());
        $prepare?->bindValue(":message", $entity->getMessage());
        $prepare?->bindValue(":user", $entity->getUser());
        $prepare?->bindValue(":animal", $entity->getAnimal());
        $prepare?->execute();
    }

    /**
     * Éditer un contact grâce à l'id
     */
    public function edit(mixed $entity): void
    {
        $statement = "UPDATE contact SET 
                message = :message
                WHERE id=:id LIMIT 1";
        $prepare = $this->db?->prepare($statement);
        $prepare?->bindValue(":message", $entity->getMessage());
        $prepare?->bindValue(":id", $entity->getId());
        $prepare?->execute();
    }

    /**
     * Supprimer un contact grâce à l'id
     */
    public function delete($entity): void
    {
        $statement = "DELETE FROM contact WHERE id = :id";
        $prepare = $this->db?->prepare($statement);
        $prepare?->bindValue(":id", $entity->getId());
        $prepare?->execute();
    }

}
