<?php
namespace App\Manager;

/**
 * Interface EntityInterface
 *
 * Tout les managers/repositories d'une entité doivent implémenter cette interface
 */
interface ManagerInterface {
    /**
     * Récupérer une entité à partir d'un attribut unique d'une entité
     */
    public function findOne(mixed $entity);

    /**
     * Récupérer toutes les entrées d'une entité
     */
    public function findAll();

    /**
     * Ajouter une nouvelle entité
     */
    public function add(mixed $entity);

    /**
     * Éditer une entité à partir d'un paramètre
     */
    public function edit(mixed $entity);

    /**
     * Supprimer une entité à partir d'un paramètre
     */
    public function delete(mixed $entity);

}
