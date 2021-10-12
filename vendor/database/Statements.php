<?php
namespace Vendor\database;

class Statements
{
    /**
     * Créer la table user
     */
    public function getUserStatements(): array
    {
        return [
            'CREATE TABLE user(
            id   INT AUTO_INCREMENT,
            last_name   VARCHAR(100) NOT NULL,
            first_name  VARCHAR(100) NOT NULL,
            email   VARCHAR(100) NOT NULL UNIQUE,
            password   VARCHAR(16) NOT NULL,
            address   VARCHAR(255) NOT NULL,
            zip_code   VARCHAR(10) NOT NULL,
            tel   VARCHAR(25) NOT NULL,
            role   VARCHAR(100) NOT NULL,
            create_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY(id)
                 );'
        ];
    }

    /**
     * Créer les tables animal et animalRace
     */
    public function getAnimalStatements(): array
    {
        return [
            'CREATE TABLE animalRace(
            id   INT AUTO_INCREMENT,
            name   VARCHAR(100) NOT NULL,
            nameSlug  VARCHAR(100) NOT NULL UNIQUE,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY(id)
            );',
            'CREATE TABLE animal(
            id   INT AUTO_INCREMENT,
            name   VARCHAR(100) NOT NULL,
            nameSlug  VARCHAR(100) NOT NULL UNIQUE,
            race_id INT NOT NULL,
            weight   FLOAT NOT NULL,
            size   FLOAT NOT NULL,
            image   VARCHAR(255) NOT NULL,
            available BOOL NOT NULL DEFAULT TRUE,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY(id),
            CONSTRAINT fk_animal_race 
                FOREIGN KEY(race_id) 
                REFERENCES animalRace(id) 
                ON DELETE CASCADE
            );'];
    }

    /**
     * Créer la table contact
     */
    public function getContactsStatements(): array
    {
        return [
            'CREATE TABLE contact (
            id   INT AUTO_INCREMENT,
            subject VARCHAR(100) NOT NULL,
            message TEXT NOT NULL,
            user_id INT NOT NULL,
            animal_id INT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY(id),
            CONSTRAINT fk_contact_user
                FOREIGN KEY(user_id)
                REFERENCES user(id)
                ON DELETE CASCADE,
            CONSTRAINT fk_contact_animal
                FOREIGN KEY(animal_id)
                REFERENCES animal(id)
                ON DELETE CASCADE
            );'
        ];
    }
}