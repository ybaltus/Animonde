<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;

class Animal
{
    /**
     * Auto increment
     */
    private int $id ;

    /**
     * Name
     */
    private string $name;

    /**
     * Slug name
     */
    private ?string $nameSlug = null;

    /**
     * Race
     */
    private AnimalRace|int $race;

    /**
     * Weight
     */
    private float $weight;

    /**
     * Size
     */
    private float $size;

    /**
     * Picture
     */
    private string $image;

    /**
     * Available
     */
    private bool $available = true;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
        // COnfigurer le slug
        $slugger = new Slugify();
        $this->setNameSlug($slugger->slugify($name));
    }

    public function getNameSlug(): ?string
    {
        return $this->nameSlug;
    }

    public function setNameSlug(string $nameSlug): void
    {
        $this->nameSlug = $nameSlug;
    }

    public function getRace(): AnimalRace|int
    {
        return $this->race;
    }

    public function setRace(AnimalRace|int $race): void
    {
        $this->race = $race;
    }

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function setWeight(float $weight)
    {
        $this->weight = $weight;
    }

    public function getSize(): float
    {
        return $this->size;
    }

    public function setSize(float $size)
    {
        $this->size = $size;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage($image): void
    {
        $this->image = $image;
    }

    public function getAvailable(): bool
    {
        return $this->available;
    }

    public function setAvailable($available): void
    {
        $this->available = $available;
    }

    /**
     * Hydrate
     */
    public function hydrate(array $animal): void
    {
        foreach ($animal as $key => $value) {
            $method = "set" . ucfirst($key);

            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }
}
