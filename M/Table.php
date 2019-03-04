<?php 

class Table
{
    private $bdd;
    private $SystemeJointure = "SystemeJointure";
    function __construct()
    {
        $this->bdd = new Database();
    }

    public function getAllTable()
    {
        $req = $this->bdd->prepare("select * from `table`");
        $req->execute();
        return $req->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Revoi la table si elle existe si non return false
     */
    public function existTable($nameTable)
    {
        $req = $this->bdd->prepare("select * from `table` where `Table` = ?");
        $req->execute(array($nameTable));
        return $req->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Creation d'une nouvelle table
     */
    public function createTable($nameTable)
    {
        $nameTable = htmlspecialchars($nameTable);
        if ($this->existTable($nameTable))
            throw new Exception("Erreur, la table '" . $nameTable . "' existe deja");
        $req = $this->bdd->prepare("INSERT INTO `table` (`table`, tableSysteme, idDroit) VALUES (?, ?, ?)");
        $req->execute(array($nameTable, 0, 1));
        $req = $this->bdd->prepare("CREATE TABLE " . $nameTable . "(id INT PRIMARY KEY NOT NULL AUTO_INCREMENT)");
        $req->execute();
        return $this->existTable($nameTable);
    }

    public function deleteTable($nameTable)
    {
        $nameTable = htmlspecialchars($nameTable);
        if ($this->existTable($nameTable) == false)
            throw new Exception("Erreur, la table n'existe pas");

        $req = $this->bdd->prepare("DROP TABLE " . $nameTable);
        $req->execute();
        $req = $this->bdd->prepare("DELETE FROM `table` WHERE `table` = ?");
        $req->execute(array($nameTable));
    }

    /**
     * Recuperation des columns d'une table
     */
    public function getColumns($tableName)
    {
        if ($this->existTable($tableName)) {
            $req = $this->bdd->prepare("SHOW COLUMNS FROM " . $tableName);
            $req->execute();
            return $req->fetchAll(PDO::FETCH_OBJ);
        }
        throw new Exception("Erreur,la table existe pas");
    }

    /**
     * Renvoie la colonne en fonction de la table et de la colonne
     */
    public function getColumnByName($tableName, $nameColumns)
    {
        if ($this->existTable($tableName)) {
            $req = $this->bdd->prepare("SHOW COLUMNS FROM " . $tableName . " where FIELD = ?");
            $req->execute(array($nameColumns));
            return $req->fetch(PDO::FETCH_OBJ);
        }
        throw new Exception("Erreur,la table existe pas");
    }

    /**
     * Supprimer un champs d'une table
     * @param $table : table ou on doit suprimmer la colonne
     * @param $col : colonne a suprimer 
     */
    public function suprimerChamps($table, $col)
    {
        $colonne = $this->getColumnByName($table, $col);
        if ($colonne) {
            $table = htmlspecialchars($table);
            $col = htmlspecialchars($col);
            $req = $this->bdd->prepare("ALTER TABLE " . $table . " DROP COLUMN " . $col);
            $req->execute();
            if ($colonne->Key = "MUL") {
                $this->suprimerJointure($table, $col);
            }
        }
    }

    public function suprimerJointure($table, $col)
    {
        $req = $this
            ->bdd
            ->prepare(
                "DELETE FROM " . $this->SystemeJointure . "  
            WHERE (Table1 = ? and Champ1 = ?) or(Table2 = ? and Champ2 = ?)"
            );
        $req->execute(array($table, $col, $table, $col));
    }

    /**
     * Ajouter une colonne à une table
     */
    public function addColonne($TableInfoTable)
    {
        $colonne = htmlspecialchars($TableInfoTable["Column"]);
        $type = htmlspecialchars($TableInfoTable["Type"]);
        $champsNullable = htmlspecialchars($TableInfoTable["Nullable"]);
        $TableJointure = htmlspecialchars($TableInfoTable["TableJointure"]);
        $ColonneJointure = htmlspecialchars($TableInfoTable["ColonneJointure"]);
        $isLien = false;
        if (strtoupper($champsNullable) != "NOT NULL")
            $champsNullable = "NULL";

        if (!isset($TableInfoTable["Default"]) || empty($TableInfoTable["Default"]))
            $default = "";
        else
            $default = " DEFAULT  '" . htmlspecialchars($TableInfoTable["Default"]) . "'";

        if ($type == "lien") {
            $isLien = true;
            $type = "int";
        }

        $table = htmlspecialchars($TableInfoTable["table"]);
        if ($this->existTable($table) != false) {
            $req = $this->bdd->prepare("ALTER TABLE " . $table . " ADD " . $colonne . " " . $type . " " . $champsNullable . " " . $default);
            $req->execute();
            if ($isLien) {
                $this->addJointure($table, $colonne, $TableJointure, $ColonneJointure);
            }
            return $this->getColumnByName($table, $colonne);
        } else {
            throw new Exception("Erreur table inconnue");
        }
    }

    /**
     * Ajoute une jointure dans la base de données
     */
    public function addJointure($table1, $champ1, $table2, $champ2)
    {
        if ($this->existTable($table1) != false && $this->existTable($table2) != false) {
            $req = $this
                ->bdd
                ->prepare(
                    "ALTER TABLE " . $table1 . " ADD FOREIGN KEY (" . $champ1 . ") 
                REFERENCES " . $table2 . " (" . $champ2 . ")"
                );
            $req->execute();
            $req = $this
                ->bdd
                ->prepare(
                    "INSERT INTO " . $this->SystemeJointure .  "(Table1, Champ1, Table2, Champ2) VALUE (?, ?, ?, ?)"
                );
            $req->execute(array($table1, $champ1, $table2, $champ2));
        } else {
            throw new Exception("Erreur table inconnue");
        }
    }

    /***
     * Recuperation des données d'une table
     */
    public function getDataTable($table)
    {
        if ($this->existTable($table)) {
            $req = $this->bdd->prepare("SELECT * FROM " . $table);
            $req->execute();
            return $req->fetchAll(PDO::FETCH_OBJ);
        }
        throw new Exception("Erreur table inconnue");
    }

    /**
     * Supression  d'une données
     */
    public function deleteData($table, $id)
    {
        if ($this->existTable($table)) {
            $req = $this->bdd->prepare("DELETE FROM " . $table . " where id = ? ");
            $req->execute(array($id));
        } else {
            throw new Exception("la table" . $table . "existe pas");
        }
    }

    /**
     * Recuperation de toute les jointure pas rapport a un champs
     */
    public function recuperationJointure($table, $colonne)
    {
        $req = $this->bdd->prepare(
            "SELECT * FROM " . $this->SystemeJointure .
                " WHERE(Table1 = ? and Champ1 = ?) or (Table2 = ? and Champ2 = ?)"
        );
        $resultat = $req->execute(array($table, $colonne, $table, $colonne));
        $resultat = $req->fetch(PDO::FETCH_OBJ);
        if (!empty($resultat)) {
            if ($resultat->Table1 != $table) {
                $table = $resultat->Table1;
                $colonne = $resultat->Champ1;
            } else {
                $table = $resultat->Table2;
                $colonne = $resultat->Champ2;
            }
            $req = $this->bdd->prepare("SELECT * FROM " . $table);
            $req->execute();
            return $req->fetchAll(PDO::FETCH_OBJ);
        } else {
            throw new Exception("Erreur, lien non trouvé");
        }
    }
}