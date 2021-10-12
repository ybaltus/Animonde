<?php


namespace App\Controller;

use App\Manager\UserManager;
use App\Utils\ControllerHandler;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use Twig\Environment;

class AuthenticateController
{
    /**
     * ControllerHandler
     */
    private ControllerHandler $controllerHandler;

    public function __construct() {
        $this->controllerHandler = new ControllerHandler();
    }

    /**
     * Page de connexion
     */
    public function signIn(Request $request, Environment $template): void
    {
        // Si l'utilisateur est déjà connecté, on redirige vers la page profil
        $this->controllerHandler->redirectToRouteIsIsset('user','profil');

        // Supprimer la variable inscription de la session
        $this->controllerHandler->unsetSessionVariable('inscription');

        // Supprimer la variable none_user de la session
        $this->controllerHandler->unsetSessionVariable('none_user');

        // Si le formulaire est soumis
        if ($request->isMethod('POST'))
        {
            // Récupérer l'utilisateur s'il existe en base de données
            $user = new User();
            $manager = new UserManager();
            $datas = $request?->request->all();
            $user->setEmail($datas['email']);
            $user->setPassword($datas['password']);
            $checkUser = $manager->findOne($user);
            if($checkUser) {
                // Création de la variable user dans la session
                $_SESSION['user'] = [
                    'id' => $checkUser->getId(),
                    'lastname' => $checkUser->getLastName(),
                    'firstname' => $checkUser->getFirstName(),
                    'email' => $checkUser->getEmail(),
                    'password' => $checkUser->getPassword(),
                    'tel' => $checkUser->getTel(),
                    'address' => $checkUser->getAddress(),
                    'zipcode' => $checkUser->getZipCode(),
                    'role' => $checkUser->getRole()
                ];

                // Redirection vers la page de profil
                header("Location:/profil");
            } else{
                $_SESSION['none_user'] = "Les identifiants sont incorrects.";
                header("Location:/connexion");
            }
        }

        echo $template->render('authenticate/sign_in.html.twig', []);
    }

    /**
     * Page d'inscription
     */
    public function signUp (Request $request, Environment $template): void {
        // Supprimer la variable none_user de la session
        $this->controllerHandler?->unsetSessionVariable('none_user');

        // Supprimer la variable suppression de la session
        $this->controllerHandler?->unsetSessionVariable('Suppression_ok');

        // Supprimer la variable inscription de la session
        $this->controllerHandler?->unsetSessionVariable('inscription');

        // Si le formulaire est soumis
        if ($request->isMethod('POST')) {
            $user = new User();
            $manager = new UserManager();
            $user->hydrate($request->request->all());
            $manager->add($user);

            // Message de création
            $_SESSION['inscription'] = "Votre compte à été créé avec succès !";

            // Redirection vers la page de connexion
            header("Location:/connexion");
        }

        // Rendu de la page d'inscription
        echo $template->render('authenticate/sign_up.html.twig', [
        ]);
    }

    /**
     * Se déconnecter
     */
    public function signOut(Request $request, Environment$template): void {
        // Supprimer la variable user dans la session
        $this->controllerHandler->unsetSessionVariable('user');

        // Redirection vers la page de connexion
        header("Location:/connexion");
    }
}