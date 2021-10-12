<?php

const ROOT = __DIR__;

require_once ROOT.'/../vendor/autoload.php';
use Vendor\database\Database;
use App\Utils\UserAdminHandler;
use Vendor\dotenv\DotEnv;

// Initialiser les variables du fichier .env
$dotEnv = new DotEnv(ROOT. '/../.env');
$dotEnv->load();

// Connexion à la base de donnée
$db = (new Database())->getDb();

// Exécuter SQL statements
try {
    // Récupérer le statement
    $userAdminHandler = new UserAdminHandler();
    $adminStatement = $userAdminHandler->getAdminStatement();

    // Insérer en base de données
    $prepare = $db?->prepare($adminStatement);
    $prepare?->execute(UserAdminHandler::$adminInfo);

} catch (\Throwable $e) {
    die($e->getMessage());
}
