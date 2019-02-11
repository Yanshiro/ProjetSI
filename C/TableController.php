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
        try {
            if (isset($paramGet["table"]) && !empty($paramGet["table"])) {
                $structure = $this->table->getColumns($paramGet["table"]);
                echo json_encode($structure);
            } else
                throw new Exception("Table non existant");
        } catch (Exception $e) {
            echo $e->getMessage();
            header("La syntaxe de la requête est erronée.", true, 400);
        }
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
        try {
            if (isset($paramPost["table"]) && !empty($paramPost['table'])) {
                $nameTable = htmlspecialchars($paramPost["table"]);
                echo json_encode($this->table->createTable($nameTable));
            } else {
                throw new Exception("Erreur le nom de la table est inconnu");
            }
        } catch (Exception $e) {

            echo $e->getMessage();
            header("La syntaxe de la requête est erronée.", true, 400);
        }
    }

    public function deleteTable($paramGet, $paramPost)
    {
        try {
            if (isset($paramPost["table"]) && !empty($paramPost["table"])) {
                $this->table->deleteTable($paramPost["table"]);
            } else {
                throw new Exception("Nom de la table pas définie");
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            header("La syntaxe de la requête est erronée.", true, 400);
        }
    }

    /**
     * Ajouter des données dans une table
     */
    public function addDataTable($paramGet, $paramPost)
    {
        var_dump($paramGet);
    }

}