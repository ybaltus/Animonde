<?php


namespace App\Controller;


use App\Entity\User;
use App\Manager\UserManager;
use App\Utils\ControllerHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class UserController
{
    /**
     * ControllerHandler
     */
    private ControllerHandler $controllerHandler;

    public function __construct() {
        $this->controllerHandler = new ControllerHandler();
    }

    /**
     * Page de profil d'un utilisateur
     */
    #[Route('/profil', name: 'profil')]
    public function profil(Request $request, Environment $template): void
    {
        // Si l'utilisateur n'est pas connecté on redirige vers la page de connexion
        $this->controllerHandler?->redirectToRouteIsNotIsset('user', 'connexion');

        // Supprimer la variable modification_ok de la session
        $this->controllerHandler?->unsetSessionVariable('modification_ok');

        // Rendu de la page de profil
        echo $template->render('user/profil.html.twig', []);
    }

    /**
     * Éditer un profil utilisateur
     */
    #[Route('/editer-profil/{id}', name: 'editerProfil')]
    public function edit(Request $request, Environment $template): void
    {
        // Si l'utilisateur n'est pas connecté on redirige vers la page de connexion
        $this->controllerHandler?->redirectToRouteIsNotIsset('user', 'connexion');

        $id = $request->query->get('id');

        if($id){
            $userManager = new UserManager();
            $user = $userManager->findOneById($id);

            if ($request->isMethod('POST')) {
                $manager = new UserManager();
                $user->hydrate($request->request->all());
                $manager->edit($user);

                // Message de création
                $_SESSION['modification_ok'] = "Votre compte à été modifié avec succès !";

                // Modification de la variable user dans la session
                $_SESSION['user'] = [
                    'id' => $user->getId(),
                    'lastname' => $user->getLastName(),
                    'firstname' => $user->getFirstName(),
                    'email' => $user->getEmail(),
                    'password' => $user->getPassword(),
                    'tel' => $user->getTel(),
                    'address' => $user->getAddress(),
                    'zipcode' => $user->getZipCode()
                ];
                // Redirection vers la page de profil
                header("Location:/profil");
            }

            //Rendu de la page d'édition de profil
            echo $template->render('user/edit.html.twig', [
                'user' => $user
            ]);
        }else{
            // Si l'utilisateur n'existe pas on redirige vers la page de connexion
            header("Location:/connexion");
        }
    }

    /**
     * Supprimer un compte
     */
    #[Route('/supprimer-profil/{id}', name: 'supprimerProfil')]
    public function delete(Request $request, Environment $template): void
    {
        $id = $request->query->get('id');
        if($id){
            $userManager = new UserManager();
            $user = $userManager->findOneById($id);
            $userManager->delete($user);

            // Supprimer la variable user de la session
            unset($_SESSION['user']);

            // Message de création
            $_SESSION['Suppression_ok'] = "Votre compte à été supprimé avec succès !";

            // Redirection vers la page de profil
            header("Location:/inscription");
        }

        // Rendu de la page de profil
        echo $template->render('user/profil.html.twig', []);
    }


}