<?php

use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Vendor\dotenv\DotEnv;

//On démarre une nouvelle session
session_start();

//Autoload
define("ROOT", dirname(__DIR__));
require_once ROOT.'/vendor/autoload.php';

// Initialiser les variables du fichier .env
$dotEnv = new DotEnv(ROOT. '/.env');
$dotEnv->load();

// Créer la requête
$request = Request::createFromGlobals();

// Ajout du routeur
require ROOT.'/config/router.php';

$context = new RequestContext();
$context->fromRequest($request);

// Configuration de Twig
$loader = new FilesystemLoader(ROOT.'/templates');
$template = new Environment($loader, ['cache' => false]);
$template->addGlobal('session', $_SESSION);

// Matcher les routes définies dans le routeur
$matcher = new UrlMatcher($routes, $context);
extract($matcher->match($request->getPathInfo()));
$_controller($request, $template);

