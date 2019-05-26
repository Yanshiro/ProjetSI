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

    /**
     * Chargement des clef etranger
     */
    public function chargeStructureEtDataEtrangere($paramGet, $paramPost)
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

    /**
     * Supression d'une table
     */
    public function deleteTable($paramGet, $paramPost)
    {
        if (isset($paramPost["table"]) && !empty($paramPost["table"])) {
            $this->table->deleteTable($paramPost["table"]);
        } else {
            throw new Exception("Nom de la table pas définie");
        }
    }

    /**
     * Ajouter une colonne dans une table
     */
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

    /**
     * Recuperation des donnees
     */
    public function getDonnees($paramGet, $paramPost)
    {
        if (isset($paramGet["table"]) && !empty($paramGet["table"])) {
            echo json_encode($this->table->getDataTable($paramGet["table"]));
        } else {
            throw new Exception("Table inconnue");
        }
    }

    /**
     * Supprimer une table
     */
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

    /***
     * Recuperation des valeur de jointure sur une table
     */
    public function chargeValueEtranger($paramGet, $paramPost)
    {
        $e = [];
        if (
            isset($paramPost["table"]) && !empty($paramPost["table"])
            && isset($paramPost["colonnes"]) && !empty($paramPost["colonnes"])
        ) {
            $col = json_decode($paramPost["colonnes"]);

            $cmp = 0;
            foreach ($col as $colonne) {
                $e[$cmp] = new stdClass();
                $e[$cmp]->colonne = $this->table->chargeStructureJointure($paramPost["table"], $colonne->Field);
                $e[$cmp]->value =  $this->table->recuperationJointure($paramPost["table"], $colonne->Field);
                $cmp++;
            }
        }
        echo json_encode($e);
    }

    /***
     * Ajout des données dans la ba(se de données
     */
    public function addData($get, $post)
    {
        $listeData = json_decode($post["data"]);
        $table = $listeData->table;
        $listeData = $listeData->data;

        if (!empty($table) && isset($table)) {
            $structure = $this->table->getColumns($table);
            foreach ($listeData as $data) {
                foreach ($structure as $key => $value) {
                    $v = $value->Field;
                    if ((empty($data->$v) || !isset($data->$v)) && $value->Null == "NO" && $value->Field != "id") {
                        throw new Exception("Champs non existant " . $key . ". dans la table " . $post["table"]);
                    }
                    if (preg_match("/[ int]/", $value->Type) && $value->Field != "id") {
                        if (is_numeric($data->$v)) {
                            $data->$v = date('d/M/Y', $data->$v);
                        } else {
                            throw new Exception("Erreur le Type doit être un " . $value->Type . " pour la colonne " . $value->Field);
                        }
                    }
                    if ("date" == $value->Type) {
                        $data->$v = floatval($data->$v);
                    }
                }
                if (isset($data->id)) {
                    unset($data->id);
                }
                $this->table->addDataInTable($table, $data);
            }
            echo json_encode($this->table->getDataTable($table));
        } else {
            throw new Exception(" Erreur d'ajout des données, table inconnue");
        }
    }

    /**
     * Update d'une donnees existante
     */
    public function updateData($get, $post)
    {
        $table = $post["table"];
        $data = json_decode($post["data"]);
        if (isset($data->id)) {
            $id = $data->id;
            unset($data->id);
            if (isset($data) && !empty($data) && isset($table) && !empty($data)) {
                $this->table->updateData($table, $data, $id);
                echo json_encode($this->table->getDataTable($table));
            }
        } else {
            throw new Exception("Id de l'objet inconnue");
        }
    }
}