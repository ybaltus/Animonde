<?php
const ROOT = __DIR__;

require_once ROOT.'/../vendor/autoload.php';

use App\Utils\AnimalRaceHandler;
use Vendor\database\Database;
use Vendor\database\Statements;
use Vendor\dotenv\DotEnv;

// Initialiser les variables du fichier .env
$dotEnv = new DotEnv(ROOT. '/../.env');
$dotEnv->load();

// Création / Connexion à la base de donnée
$db = (new Database())->getDb();

// Exécuter SQL statements
try {
    // Récupérer les statements
    $statementsManager = new Statements();
    $userStatements = $statementsManager->getUserStatements();
    $animalStatements = $statementsManager->getAnimalStatements();
    $contactStatements = $statementsManager->getContactsStatements();

    $statements = [$userStatements, $animalStatements, $contactStatements];
    foreach ($statements as $statementPerType) {
        // Ajout des tables
        foreach ($statementPerType as $statement) {
            $prepare = $db?->prepare($statement);
            $prepare?->execute();
        }
    }

    // Insérer les race par défaut
    $raceHandler = new AnimalRaceHandler();
    $raceStatement = $db?->prepare($raceHandler->getAnimalRaceStatement());
    $db?->beginTransaction();
    foreach ($raceHandler::$listRace as $race){
        $raceStatement->execute($race);
    }
    $db?->commit();


} catch (\Throwable $e) {
    die($e->getMessage());
}

