<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Entity\AnimalRace;
use App\Entity\User;
use App\Manager\AnimalManager;
use App\Manager\AnimalRaceManager;
use App\Manager\UserManager;
use App\Utils\ControllerHandler;
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
     * ControllerHandler
     */
    private ControllerHandler $controllerHandler;

    public function __construct() {
        $this->controllerHandler = new ControllerHandler();
    }

    /**
     * Accueil administration
     */
    public function index(Request $request, Environment $template): void {
        // Si l'utilisateur n'est pas connecté on redirige vers la page de connexion
        $this->controllerHandler?->redirectToRouteIsNotIsset('user', 'connexion');

        // Si l'utilisateur n'est pas un administrateur
        if(isset($_SESSION['user']) && $_SESSION['user']['role'] !== 'ROLE_ADMIN'){
            // Redirection vers la page de profil
            header("Location:/connexion");
        }

        // Supprimer la variable addAnimal de la session
        $this->controllerHandler?->unsetSessionVariable('addAnimal');

        // Supprimer la variable editAnimal de la session
        $this->controllerHandler?->unsetSessionVariable('editAnimal');

        // Supprimer la variable inscription de la session
        $this->controllerHandler?->unsetSessionVariable('addUser');

        // Récupérer les animaux
        $animalManager = new AnimalManager();
        $animals = $animalManager->findAll();
        // Récupérer les races
        $raceManager = new AnimalRaceManager();
        $racesEntities = $raceManager->findAll();
        $races = [];
        foreach ($racesEntities as $race) {
            $races[$race->getId()] = $race;
        }
        // Récupérer les utilisateurs
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

            // Vérifier si l'animal existe
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

        // Récupérer les races
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
        // Supprimer la variable animal_exist de la session
        $this->controllerHandler?->unsetSessionVariable('animal_exist');

        // Récupérer l'id de l'animal
        $id = $request->query->get('id');

        // S'il n'y a pas d'id
        if(!$id) {
            // Redirection vers la liste des animaux
            header("Location:/admin");
        }

        // Récupérer l'animal
        $manager = new AnimalManager();
        $animal = $manager->findOne($id);

        // Si l'animal n'existe pas
        if(!$animal){
            // Redirection vers la liste des animaux
            header("Location:/admin");
        }

        // Si le formulaire est soumis
        if ($request->isMethod('POST')) {
            $datas = $request->request->all();
            $animal->hydrate($datas);
            $manager->edit($animal);
            $_SESSION['editAnimal'] = "L'animal ". $animal->getName()." a bien été édité!";

            // Redirection vers la liste des animaux
            header("Location:/admin");
        }

        // Récupérer les races
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
    public function deleteAnimal(Request $request): void{
        // Récupérer l'id de l'animal
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
        $this->controllerHandler?->redirectToRouteIsNotIsset('user', 'connexion');

        // Si l'utilisateur n'est pas un administrateur
        if(isset($_SESSION['user']) && $_SESSION['user']['role'] !== 'ROLE_ADMIN'){
            // Redirection vers la page de profil
            header("Location:/connexion");
        }

        // Supprimer la variable none_user de la session
        $this->controllerHandler?->unsetSessionVariable('none_user');

        // Supprimer la variable suppression de la session
        $this->controllerHandler?->unsetSessionVariable('Suppression_ok');

        // Si le formulaire est soumis
        if ($request->isMethod('POST')) {
            $user = new User();
            $manager = new UserManager();
            $user?->hydrate($request->request->all());
            $manager?->add($user);

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
    public function editUser(Request $request, Environment $template): void {
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