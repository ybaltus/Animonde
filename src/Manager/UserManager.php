<?php
namespace App\Manager;
use App\Entity\User;
use App\Manager\ManagerInterface;
use Vendor\database\Database;
use Vendor\database\Manager;

class UserManager extends Manager implements ManagerInterface
{
    /**
     * Récupérer un utilisateur avec son email
     */
    public function findOne(mixed$entity): User|bool
    {
        $statement = "SELECT * FROM user WHERE email = :email AND password = :password LIMIT 1";
        $prepare = $this->db?->prepare($statement);
        $prepare?->bindValue(":email", $entity->getEmail());
        $prepare?->bindValue(":password", $entity->getPassword());
        $prepare?->execute();
        $prepare->setFetchMode(\PDO::FETCH_CLASS, User::class);
        return $prepare->fetch();
//        return $prepare?->fetch(\PDO::FETCH_OBJ);
    }

    /**
     * Récupérer un utilisateur avec son id
     */
    public function findOneById(int $id): User|bool
    {
        $statement = "SELECT * FROM user WHERE id = :id LIMIT 1";
        $prepare = $this->db?->prepare($statement);
        $prepare?->bindValue(":id", $id);
        $prepare?->execute();
        $prepare?->setFetchMode(\PDO::FETCH_CLASS, User::class);
        return $prepare?->fetch();
    }

    /**
     * Tous les utilisateurs
     */
    public function findAll(): array
    {
        $query = $this->db?->query("SELECT * FROM user");
        return $query?->fetchAll(\PDO::FETCH_CLASS, "App\Entity\User");
    }

    /**
     * Ajouter un utilisateur
     */
    public function add(mixed $entity): void
    {
        $statement = "INSERT INTO user (last_name, first_name, email, password, address, zip_code, tel, role) 
                        VALUES (:lastname, :firstname, :email, :password, :address, :zipcode, :tel, :role)";

        $prepare = $this->db?->prepare($statement);
        $prepare?->bindValue(":lastname", $entity->getLastName());
        $prepare?->bindValue(":firstname", $entity->getFirstName());
        $prepare?->bindValue(":email", $entity->getEmail());
        $prepare?->bindValue(":password", $entity->getPassword());
        $prepare?->bindValue(":address", $entity->getAddress());
        $prepare?->bindValue(":zipcode", $entity->getZipCode());
        $prepare?->bindValue(":tel", $entity->getTel());
        $prepare?->bindValue(":role", $entity->getRole());
        $prepare?->execute();
    }

    /**
     * Éditer un utilisateur
     */
    public function edit(mixed $entity): void
    {
        $statement = "UPDATE user SET 
                last_name = :lastname,
                first_name = :firstname,
                email = :email,
                password = :password,
                address = :address,
                zip_code = :zipcode,
                tel = :tel,
                role = :role
                WHERE id = :id";
        $prepare = $this->db->prepare($statement);
        $prepare?->bindValue(":id", $entity->getId());
        $prepare?->bindValue(":lastname", $entity->getLastName());
        $prepare?->bindValue(":firstname", $entity->getFirstName());
        $prepare?->bindValue(":email", $entity->getEmail());
        $prepare?->bindValue(":password", $entity->getPassword());
        $prepare?->bindValue(":address", $entity->getAddress());
        $prepare?->bindValue(":zipcode", $entity->getZipCode());
        $prepare?->bindValue(":tel", $entity->getTel());
        $prepare?->bindValue(":role", $entity->getRole());
        $prepare?->execute();
    }

    /**
     * Supprimer un utilisateur avec l'id
     */
    public function delete(mixed $entity): void
    {
        $statement = "DELETE FROM user WHERE id = :id";
        $prepare = $this->db?->prepare($statement);
        $prepare?->bindValue(":id", $entity);
        $prepare?->execute();
    }

}