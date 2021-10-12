<?php

namespace App\Entity;


class User
{
    /**
     * Auto increment
     */
    private int $id;
    /**
     * Lastname
     */
    private string $last_name;
    /**
     * Firstname
     */
    private string $first_name;
    /**
     * Email
     */
    private string $email;
    /**
     * Password
     */
    private string $password;
    /**
     * Address
     */
    private string $address;
    /**
     * ZipCode
     */
    private string $zip_code;
    /**
     * Tel
     */
    private string $tel;

    /**
     * User role
     */
    private string $role = "ROLE_USER";

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getLastName(): string
    {
        return $this->last_name;
    }

    public function setLastName(string $lastName): void
    {
        $this->last_name = $lastName;
    }

    public function getFirstName(): string
    {
        return $this->first_name;
    }

    public function setFirstName(string $firstName): void
    {
        $this->first_name = $firstName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    public function getZipCode(): string
    {
        return $this->zip_code;
    }

    public function setZipCode(string $zipCode): void
    {
        $this->zip_code = $zipCode;
    }

    public function getTel(): string
    {
        return $this->tel;
    }

    public function setTel(string $tel): void
    {
        $this->tel = $tel;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    /**
     * Hydrate
     */
    public function hydrate (array $user): void
    {
        foreach ($user as $key => $value) {
            $method = "set". ucfirst($key);
            if($method !== null && method_exists($this, $method)){
                $this->$method($value);
            }
        }
    }
}