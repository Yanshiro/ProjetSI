<?php
include "C/Utilisateur.php";

function afficheStructure($paramGet, $paramPost)
{
    if (ifConnexion()) {
        $table = new Table();
        $structure = $table->getColumns($paramGet["table"]);
        require("V/structureTable.php");
    }
}

function addData($paramGet, $paramPost)
{
    var_dump($paramGet);
}