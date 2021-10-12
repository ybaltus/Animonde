<?php


namespace App\Entity;


class Contact
{
    /**
     * id d'un contact
     *
     * Auto increment
     */
    private int $id;

    /**
     * Message laissé par l'utilisateur
     */
    private string $message;

    /**
     * Utilisateur
     */
    private User|int $user;

    /**
     * L'objet du contact
     */
    private string $subject = "info";

    /**
     * Animal ciblé
     */
    private Animal|int $animal;

    public function getId(): int
    {
        return $this->id;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function getUser(): User|int
    {
        return $this->user;
    }

    public function setUser(User|int $user): void
    {
        $this->user = $user;
    }

    public function getAnimal(): Animal|int
    {
        return $this->animal;
    }

    public function setAnimal(Animal|int $animal): void
    {
        $this->animal = $animal;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    /**
     * Hydrate
     * @param array $contact
     */
    public function hydrate(array $contact): void
    {
        foreach ($contact as $key => $value) {
            $method = "set" . ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }
}
