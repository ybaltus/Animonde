<?php
namespace App\Utils;

class UserAdminHandler
{
    public static array $adminInfo = [
        'last_name' => 'admin',
        'first_name' => 'admin',
        'email' => 'admin@doe.fr',
        'password' => '1234',
        'address' => 'Vers l\'infini et au delà',
        'zipcode' => 25200,
        'tel' => '0666666666',
        'role' => 'ROLE_ADMIN'
        ];

    /**
     * Créer un administrateur
     */
    public function getAdminStatement(): string
    {
        return
            'INSERT INTO user (last_name, first_name, email, password, address, zip_code, tel, role) 
            VALUES (:last_name,:first_name,:email,:password,:address,:zipcode,:tel,:role)'
        ;
    }
}