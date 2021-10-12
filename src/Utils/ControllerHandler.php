<?php

namespace App\Utils;

class ControllerHandler
{
    /**
     * Redirection en fonction de la session et de la page.
     * Si la variable existe dans la session on redirige
     */
    public function redirectToRouteIsIsset(string $variableSession, string $page): void
    {
        if(isset($_SESSION[$variableSession])){
            match($page) {
                'profil' => header("Location:/profil"),
                default => header("Location:/connexion")
            };
        }
    }

    /**
     * Redirection en fonction de la session et de la page
     * Si la variable n'existe pas dans la session on redirige
     */
    public function redirectToRouteIsNotIsset(string $variableSession, string $page): void
    {
        if(!isset($_SESSION[$variableSession])){
            match($page) {
                'profil' => header("Location:/profil"),
                default => header("Location:/connexion")
            };
        }
    }

    /**
     * Supprimer une variable de session
     */
    public function unsetSessionVariable(string $variable): void
    {
        if(isset($_SESSION[$variable])){
            unset($_SESSION[$variable]);
        }
    }
}