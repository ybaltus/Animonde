<?php
namespace App\Utils;

class AnimalRaceHandler
{
    public static array $listRace = [
        ['Chien', 'chien'],
        ['Chat', 'chat']
    ];

    /**
     * Insérer une race
     */
    public function getAnimalRaceStatement(): string
    {
        return
            'INSERT INTO animalRace (name, nameSlug) 
            VALUES (?,?)'
        ;
    }
}