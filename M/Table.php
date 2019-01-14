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
     * Recuperation des columns d'une table
     */
    public function getColumns($tableName)
    {
        $req = $this->bdd->prepare("SHOW COLUMNS FROM " . $tableName);
        $req->execute();
        return $req->fetchAll(PDO::FETCH_OBJ);
    }
}