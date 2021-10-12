<?php

use App\Controller\AdminController;
use App\Controller\AuthenticateController;
use App\Controller\ContactController;
use App\Controller\HomeController;
use App\Controller\AnimalController;
use App\Controller\UserController;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Liste des routes globales
 */
$routes = new RouteCollection();
// Acceuil
$routes?->add("home", new Route("/", ['_controller'=>[new HomeController, "index"]]));
// Profil
$routes->add("profil", new Route("/profil", ['_controller'=>[new UserController(), "profil"]]));
// Éditer un profil
$routes->add("editerProfil", new Route("/editer-profil", ['_controller'=>[new UserController(), "edit"]]));
// Supprimer un profil
$routes->add("supprimerProfil", new Route("/supprimer-profil", ['_controller'=>[new UserController(), "delete"]]));
// Liste des animaux
$routes->add("animals", new Route("/animaux", ['_controller'=>[new AnimalController(), "index"]]));
// Détail d'un animal
$routes->add("showAnimal", new Route("/voir-un-animal", ['_controller'=>[new AnimalController(), "show"]]));
/**
 * Routes d'authentification
 */
// Inscription
$routes->add("inscription", new Route("/inscription", ['_controller'=>[new AuthenticateController, "signUp"]]));
// Connexion
$routes?->add("connexion", new Route("/connexion", ['_controller'=>[new AuthenticateController(), "signIn"]]));
// Deconnexion
$routes->add("deconnexion", new Route("/deconnexion", ['_controller'=>[new AuthenticateController(), "signOut"]]));

/**
 * Routes pour l'administration
 */
// Administration
$routes->add("admin", new Route("/admin", ['_controller'=>[new AdminController(), "index"]]));
// Ajouter un animal
$routes->add("addAnimal", new Route("/ajouter-un-animal", ['_controller'=>[new AdminController(), "addAnimal"]]));
// Éditer un animal
$routes->add("editAnimal", new Route("/editer-un-animal", ['_controller'=>[new AdminController(), "editAnimal"]]));
// Supprimer un animal
$routes->add("deleteAnimal", new Route("/supprimer-un-animal", ['_controller'=>[new AdminController(), "deleteAnimal"]]));
// Ajouter un utilisateur
$routes->add("addUser", new Route("/ajouter-un-utilisateur", ['_controller'=>[new AdminController(), "addUser"]]));
// Ajouter un utilisateur
$routes->add("editUser", new Route("/editer-un-utilisateur", ['_controller'=>[new AdminController(), "editUser"]]));
// Ajouter un utilisateur
$routes->add("deleteUser", new Route("/supprimer-un-utilisateur", ['_controller'=>[new AdminController(), "deleteUser"]]));

