<?php

include "M/Table.php";

class TableController
{
    private $table;

    public function __construct()
    {
        $this->table = new Table();
    }

    /**
     * Affiche la structure de la table
     */
    public function afficheStructure($paramGet, $paramPost)
    {
        if (isset($paramGet["table"]) && !empty($paramGet["table"])) {
            $structure = $this->table->getColumns($paramGet["table"]);
            echo json_encode($structure);
        } else
        throw new Exception("Table non existant");
    }

    /***
     * Affiche toutes les tables de la base
     */
    public function getAllTable()
    {
        $tables = $this->table->getAllTable();
        echo json_encode($tables);
        die();
    }

    /**
     * Ajoute une table
     */
    public function addTable($paramGet, $paramPost)
    {
        if (isset($paramPost["table"]) && !empty($paramPost['table'])) {
            $nameTable = htmlspecialchars($paramPost["table"]);
            echo json_encode($this->table->createTable($nameTable));
        } else {
            throw new Exception("Erreur le nom de la table est inconnu");
        }
    }

    public function deleteTable($paramGet, $paramPost)
    {
        if (isset($paramPost["table"]) && !empty($paramPost["table"])) {
            $this->table->deleteTable($paramPost["table"]);
        } else {
            throw new Exception("Nom de la table pas définie");
        }
    }

    public function addColonneInTable($paramGet, $paramPost)
    {
        if (isset($paramPost["table"]) && !empty($paramPost["table"])) {
            if (
                isset($paramPost["Column"]) && !empty($paramPost["Column"])
                && isset($paramPost["Type"]) && !empty($paramPost["Type"])
            ) {
                $resulte = $this->table->addColonne($paramPost);
                echo json_encode($resulte);
            } else {
                throw new Exception("Erreur, des information sont inconnu pour la création d'une colonne");
            }
        } else {
            throw new Exception("Erreur, table inconnue");
        }
    }

    /**
     * Suprimer un champs d'une table
     */
    public function suprimerColonne($paramGet, $paramPost)
    {
        if (
            isset($paramPost["table"]) && !empty($paramPost["table"])
            && isset($paramPost["Column"]) && !empty($paramPost["Column"])
        ) {
            $this->table->suprimerChamps($paramPost["table"], $paramPost["Column"]);
            echo "true";
        } else {
            throw new Exception("Erreur, il manque des informations pour supprimer une colonne d'une table");
        }
    }

    public function getDonnees($paramGet, $paramPost)
    {
        if (isset($paramGet["table"]) && !empty($paramGet["table"])) {
            echo json_encode($this->table->getDataTable($paramGet["table"]));
        } else {
            throw new Exception("Table inconnue");
        }
    }

    public function deletData($paramGet, $paramPost)
    {
        if (
            isset($paramPost["table"]) && !empty($paramPost["table"])
            && isset($paramPost["id"]) && !empty($paramPost["id"])
        ) {
            $this->table->deleteData($paramPost["table"], $paramPost["id"]);
        } else {
            throw new Exception("Erreur, information manquante");
        }
    }
}