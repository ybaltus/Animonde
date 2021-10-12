<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Manager\AnimalManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class HomeController
{
    /**
     * Page d'accueil
     */
    #[Route('/', name: 'home')]
    public function index(Request $request, Environment $template)
    {
        $manager = new AnimalManager();
        $animals = $manager?->findLastTen();
        echo $template->render('home/index.html.twig', [
            'animals' => $animals
        ]);
    }
}
