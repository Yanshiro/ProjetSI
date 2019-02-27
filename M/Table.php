<?php 

class Table
{
    private $bdd;
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
        if ($this->getColumnByName($table, $col)) {
            $table = htmlspecialchars($table);
            $col = htmlspecialchars($col);
            $req = $this->bdd->prepare("ALTER TABLE " . $table . " DROP COLUMN " . $col);
            $req->execute();
        }
    }

    public function addColonne($TableInfoTable)
    {
        $colonne = htmlspecialchars($TableInfoTable["Column"]);
        $type = htmlspecialchars($TableInfoTable["Type"]);
        $champsNullable = htmlspecialchars($TableInfoTable["Nullable"]);

        if (strtoupper($champsNullable) != "NOT NULL")
            $champsNullable = "NULL";

        if (!isset($TableInfoTable["Default"]))
            $default = "";
        else
            $default = " DEFAULT  '" . htmlspecialchars($TableInfoTable["Default"]) . "'";

        $table = htmlspecialchars($TableInfoTable["table"]);
        if ($this->existTable($table) != false) {
            $req = $this->bdd->prepare("ALTER TABLE " . $table . " ADD " . $colonne . " " . $type . " " . $champsNullable . " " . $default);
            $req->execute();
            return $this->getColumnByName($table, $colonne);
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

    public function deleteData($table, $id)
    {
        if ($this->existTable($table)) {
            $req = $this->bdd->prepare("DELETE FROM " . $table . " where id = ? ");
            $req->execute(array($id));
        } else {
            throw new Exception("la table" . $table . "existe pas");
        }
    }
}