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
     * @return mixed
     */
    public function findOne($entity);

    /**
     * Récupérer toutes les entrées d'une entité
     * @return mixed
     */
    public function findAll();

    /**
     * Ajouter une nouvelle entité
     */
    public function add($entity);

    /**
     * Éditer une entité à partir de son id ou de son nameSlug
     * @return mixed
     */
    public function edit($entity);

    /**
     * Supprimer une entité à partir de son id ou de son nameSlug
     * @return mixed
     */
    public function delete($entity);

}
