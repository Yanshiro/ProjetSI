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
        $req->execute();
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
}