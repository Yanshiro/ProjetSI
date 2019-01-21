<?php
include "C/UtilisateurController.php";

class TableController
{
    private $Utilisateur;

    public function __construct()
    {
        $this->Utilisateur = new UtilisateurController();
    }

    public function afficheStructure($paramGet, $paramPost)
    {
        if ($this->Utilisateur->ifConnexion()) {
            $table = new Table();
            $structure = $table->getColumns($paramGet["table"]);
            require("V/structureTable.php");
        }
    }

    public function addData($paramGet, $paramPost)
    {
        var_dump($paramGet);
    }

}