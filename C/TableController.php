<?php
include "C/Utilisateur.php";

class TableController
{
    public function afficheStructure($paramGet, $paramPost)
    {
        if (ifConnexion()) {
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