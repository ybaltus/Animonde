<?php
namespace App\Controller;

use App\Entity\Contact;
use App\Entity\User;
use App\Manager\AnimalManager;
use App\Manager\ContactManager;
use App\Manager\PokemonTypeManager;
use App\Manager\AnimalRaceManager;
use App\Manager\PokemonRarityManager;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Animal;
use Twig\Environment;
use Vendor\database\Manager;

class AnimalController
{
    /**
     * Page de la liste des animaux
     */
    public function index (Request $request, Environment$template) {
        $manager = new AnimalManager();
        $animals = $manager->findAll();
        echo $template->render('animal/index.html.twig', [
            'animals' => $animals
        ]);
    }

    /**
     * Page d'un animal
     */
    public function show(Request $request, Environment $template) {
        $id =$request->query->get('id');

        // S'il n'y a pas d'id on redirige vers la liste des animaux
        if (!$id){
            // Redirection vers la liste des animaux
            header("Location:/animaux");
        }

        if(!isset($_SESSION['user'])){
            // Redirection vers la liste des animaux
            header("Location:/animaux");
        }

        if(isset($_SESSION['contact_ok'])){
            // On supprime la variable session
            unset($_SESSION['contact_ok']);
        }

        $manager = new AnimalManager();
        $animal = $manager->findOne($id);
        $managerRace = new AnimalRaceManager();
        $races = $managerRace->findAll();

        // Si le formulaire est soumis
        if ($request->isMethod('POST')) {
            $contact = new Contact();
            $manager = new ContactManager();
            $userId= $_SESSION['user']['id'];
            $contact->setUser($userId);
            $contact->setAnimal($id);
            $contact->hydrate($request->request->all());
            $manager->add($contact);

            // On créer un message dans la session
            $_SESSION['contact_ok'] = "Votre message a bien été envoyé!";

            //On redirige vers la page de contact
            header("location:/voir-un-animal?id=$id");
        }

        echo $template->render('animal/show.html.twig', [
            'animal' => $animal,
            'races' => $races
        ]);

    }
}