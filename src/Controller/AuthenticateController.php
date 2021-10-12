<?php


namespace App\Controller;

use App\Manager\UserManager;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use Twig\Environment;

class AuthenticateController
{
    /**
     * Page de connexion
     */
    public function signIn (Request $request, Environment $template): void {
        // Si l'utilisateur est déjà connecté, on redirige vers la page profil
        if(isset($_SESSION['user'])){
            // Redirection vers la page de profil
            header("Location:/profil");
        }

        // Si le formulaire est soumis
        if ($request->isMethod('POST')) {
            // Supprimer la variable inscription de la session
            if(isset($_SESSION['inscription'])){
                unset($_SESSION['inscription']);
            }

            // Supprimer la variable none_user de la session
            if(isset($_SESSION['none_user'])){
                unset($_SESSION['none_user']);
            }

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
                    'id' => $checkUser->id,
                    'lastname' => $checkUser->last_name,
                    'firstname' => $checkUser->first_name,
                    'email' => $checkUser->email,
                    'password' => $checkUser->password,
                    'tel' => $checkUser->tel,
                    'address' => $checkUser->address,
                    'zipcode' => $checkUser->zip_code,
                    'role' => $checkUser->role
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
        if(isset($_SESSION['none_user'])){
            unset($_SESSION['none_user']);
        }

        // Supprimer la variable suppression de la session
        if(isset($_SESSION['Suppression_ok'])){
            unset($_SESSION['Suppression_ok']);
        }


        // Supprimer la variable inscription de la session
        if(isset($_SESSION['inscription'])){
            unset($_SESSION['inscription']);
        }

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
        if(isset($_SESSION['user'])){
            unset($_SESSION['user']);
        }
        // Redirection vers la page de connexion
        header("Location:/connexion");
    }
}