# Énoncé
Objectif: Créer un site pour une association d'animaux

Le site doit contenir:
- une page d'accueil présentant les derniers animaux arrivés à l'association (les 10 derniers ou ceux arrivés au cours des 30 derniers jours)
- une page présentant tous les animaux disponible (uniquement disponible)
- une page d'un animal présentant les informations de l'animal et un formulaire de contact pour obtenir des informations ou le réserver.
- une page admin permettant de gérer les animaux (en ajouter, mettre des animaux en réservés, modifier leur fiche)

L'admin doit donc pouvoir se connecter et ajouter d'autres admin.
L'utilisation de la session est donc incontournable.

Le site doit être construit en PHP orienté objet. (La mise en place d'un MVC est fortement conseillée et les framework ne sont pas autorisés)
PHP doit être utilisé dans sa dernière version (PHP8) on attend donc à avoir un code non pas annoté mais avec des attributs et bien typé.
Le code doit être commenté.
On attend l'utilisation d'un match et/ou d'un nullsafe
Le design n'est pas important mais on en attent un minimum.

# Stack technique
* [PHP 8](https://www.php.net/releases/8.0/en.php)
* [Composer](https://getcomposer.org/)
* [Twig](https://twig.symfony.com/)
* [Symfony/http-foundation](https://packagist.org/packages/symfony/http-foundation)
* [Symfony/routing](https://packagist.org/packages/symfony/routing)
* [Cocur/slugify](https://github.com/cocur/slugify)

# Pour initialiser le projet

#### 1- Génerer la base de données et les tables
```
Configurer vos identifiants de la base de données dans le fichier .env
Exécuter la commande: php bin/initialize_database.php
```
#### 2- Génerer un administrateur
```
Exécuter la commande: php bin/initialize_admin.php
login: admin@doe.fr, password: 1234
```
#### 3- Installer les packages composer et l'autoload
```
Exécuter la commande: composer install ou composer update
Exécuter la commande: composer dump-autoload
```
#### 4- Lancer le serveur php
```
Exécuter la commande: php -S localhost:8000 -t public
```
