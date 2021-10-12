<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Entity\AnimalRace;
use App\Entity\User;
use App\Manager\AnimalManager;
use App\Manager\AnimalRaceManager;
use App\Manager\UserManager;
use Cocur\Slugify\Slugify;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;

class AdminController
{
    /**
     * Liste des races par default
     */
    private static array $races = [
        1 => 'chien',
        2 => 'chat'
    ];

    /**
     * Accueil administration
     */
    public function index(Request $request, Environment $template): void {
        // Supprimer la variable addAnimal de la session
        if(isset($_SESSION['addAnimal'])){
            unset($_SESSION['addAnimal']);
        }

        // Supprimer la variable editAnimal de la session
        if(isset($_SESSION['editAnimal'])){
            unset($_SESSION['editAnimal']);
        }

        // Si l'utilisateur n'est pas connecté
        if(!isset($_SESSION['user'])){
            // Redirection vers la page de profil
            header("Location:/connexion");
        }

        // Si l'utilisateur n'est pas un administrateur
        if(isset($_SESSION['user']) && $_SESSION['user']['role'] !== 'ROLE_ADMIN'){
            // Redirection vers la page de profil
            header("Location:/connexion");
        }

        // Supprimer la variable inscription de la session
        if(isset($_SESSION['addUser'])){
            unset($_SESSION['addUser']);
        }

        // Animals
        $animalManager = new AnimalManager();
        $animals = $animalManager->findAllAvailable();
        // Races
        $raceManager = new AnimalRaceManager();
        $racesEntities = $raceManager->findAll();
        $races = [];
        foreach ($racesEntities as $race) {
            $races[$race->getId()] = $race;
        }
        // Users
        $userManager = new UserManager();
        $users = $userManager->findAll();

        // Rendu de la page
        echo $template->render('admin/index.html.twig', [
            'animals' => $animals,
            'races' => $races,
            'users' => $users
        ]);
    }

    /**
     * Ajouter un animal
     */
    public function addAnimal(Request $request, Environment$template): void{
        // Si le formulaire est soumis
        if ($request->isMethod('POST')) {
            $datas = $request->request->all();
            $animal = new Animal();
            $manager = new AnimalManager();
            $slugger = new Slugify();

            $checkAnimal = $manager->findOneBySlug($slugger->slugify($datas['name']));

            if(!$checkAnimal) {
                $raceManager = new AnimalRaceManager();
                $animalRace = $raceManager->findOneBySlug(self::$races[$datas['race']]);
                $animal->setRace($animalRace->getId());
                $animal->setImage(intval($datas['race']) == 1 ? 'chien500x500.jpg': 'chat500x500.jpg');
                $animal->hydrate($request->request->all());
                $manager->add($animal);

                $_SESSION['addAnimal'] = "L'animal ". $animal->getName()." à bien été ajouté!";
                // Redirection vers la liste des animaux
                header("Location:/admin");
            }else{
                $_SESSION['animal_exist'] = "Cette animal existe déjà.";
                // Redirection vers la page de profil
                header("Location:/ajouter-un-animal");
            }
        }

        $managerRace = new AnimalRaceManager();
        $races = $managerRace->findAll();

        echo $template->render('admin/animal/add.html.twig', [
            'races' => $races,
        ]);
    }

    /**
     * Éditer un animal
     */
    public function editAnimal(Request $request, Environment $template): void{
        // Récupérer l'id de l'animal
        $id = $request->query->get('id');

        // S'il n'y a pas d'id
        if(!$id) {
            // Redirection vers la liste des animaux
            header("Location:/admin");
        }

        $manager = new AnimalManager();
        $animal = $manager->findOne($id);

        // Si l'animal n'existe pas
        if(!$animal){
            // Redirection vers la liste des animaux
            header("Location:/admin");
        }

        // Si le formulaire est soumis
        if ($request->isMethod('POST')) {
            $animal->hydrate($request->request->all());
            $manager->edit($animal);
            $_SESSION['editAnimal'] = "L'animal ". $animal->getName()." a bien été édité!";

            // Redirection vers la liste des animaux
            header("Location:/admin");
        }

        $managerRace = new AnimalRaceManager();
        $races = $managerRace->findAll();

        echo $template->render('admin/animal/edit.html.twig', [
            'animal' => $animal,
            'races' => $races
        ]);
    }

    /**
     * Supprimer un animal
     */
    public function deleteAnimal(Request $request){
        $id =$request->query->get('id');
        if ($id){
            $manager = new AnimalManager();
            $manager->delete($id);
            $_SESSION['editAnimal'] = "L'animal a bien été supprimé!";
        }
        // Redirection vers la liste des animaux
        header("Location:/admin");
    }

    /**
     * Ajouter un utilisateur
     */
    public function addUser (Request $request, Environment $template): void {
        // Si l'utilisateur n'est pas connecté
        if(!isset($_SESSION['user'])){
            // Redirection vers la page de profil
            header("Location:/connexion");
        }

        // Si l'utilisateur n'est pas un administrateur
        if(isset($_SESSION['user']) && $_SESSION['user']['role'] !== 'ROLE_ADMIN'){
            // Redirection vers la page de profil
            header("Location:/connexion");
        }

        // Supprimer la variable none_user de la session
        if(isset($_SESSION['none_user'])){
            unset($_SESSION['none_user']);
        }

        // Supprimer la variable suppression de la session
        if(isset($_SESSION['Suppression_ok'])){
            unset($_SESSION['Suppression_ok']);
        }

        // Si le formulaire est soumis
        if ($request->isMethod('POST')) {
            $user = new User();
            $manager = new UserManager();
            $user->hydrate($request->request->all());
            $manager->add($user);

            // Message de création
            $_SESSION['addUser'] = "Le compte à été créé avec succès !";

            // Redirection vers la page de connexion
            header("Location:/admin");
        }

        // Rendu de la page d'inscription
        echo $template->render('admin/user/add.html.twig', [
        ]);
    }

    /**
     * Éditer un utilisateur
     */
    public function editUser(Request $request, Environment $template): void{
        // Récupérer l'id de l'utilisateur
        $id = $request->query->get('id');

        // S'il n'y a pas d'id
        if(!$id) {
            // Redirection vers la page d'administration
            header("Location:/admin");
        }

        $manager = new UserManager();
        $user = $manager->findOneById($id);

        // Si l'animal n'existe pas
        if(!$user){
            // Redirection vers la page d'administration
            header("Location:/admin");
        }

        // Si le formulaire est soumis
        if ($request->isMethod('POST')) {
            $user->hydrate($request->request->all());
            $manager->edit($user);
            $_SESSION['editAnimal'] = "L'utilisateur a bien été édité!";

            // Redirection vers la liste des animaux
            header("Location:/admin");
        }

        echo $template->render('admin/user/edit.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * Supprimer un utilisateur
     */
    public function deleteUser(Request $request){
        $id =$request->query->get('id');
        if ($id){
            $manager = new UserManager();
            $manager->delete($id);
            $_SESSION['editAnimal'] = "L'utilisateur a bien été supprimé!";
        }
        // Redirection vers l'administration
        header("Location:/admin");
    }
}