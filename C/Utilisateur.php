<?php

function verifConnection()
{
    if (isset($_SESSION['Auth']) && !empty($_SESSION['Auth'])) {
        $Vol = new Vol();
        $vols = $Vol->selectVol();
        $avions = $Vol->getListeAvion();
        require "V/map.php";
    } else {
        require "V/connexion.php";
    }
}


function connexion($paramGet, $paramPost)
{
    $login = $paramPost["login"];
    $mdp = $paramPost["mdp"];
    $m = new Utilisateur();
    $existe = $m->existUser($login, $mdp);
    if ($existe) {
        $_SESSION['Auth'] = $existe;
        echo true;
    } else {
        echo "false";
    }
}

function deconnexion()
{
    $_SESSION['Auth'] = null;
    session_destroy();
    header("Location: index.php");
}