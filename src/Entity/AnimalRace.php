<?php

namespace App\Entity;

class AnimalRace
{
    /**
     * Auto increment
     */
    private int $id;

    /**
     * Name
     */
    private string $name;

    /**
     * Slug name
     */
    private string $nameSlug;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getNameSlug(): string
    {
        return $this->nameSlug;
    }

    public function setNameSlug(string $nameSlug): void
    {
        $this->nameSlug = $nameSlug;
    }

    /**
     * Hydrate
     */
    public function hydrate(array $animalRace): void
    {
        foreach ($animalRace as $key => $value) {
            $method = "set" . ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }
}
