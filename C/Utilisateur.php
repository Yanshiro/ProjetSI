<?php
include "M/Utilisateur.php";

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
    if (isset($paramPost["mdp"]) && isset($paramPost["mdp"]) && !empty($paramPost["mdp"]) && !empty($paramPost["login"])) {
        $login = $paramPost["login"];
        $mdp = sha1($paramPost["mdp"]);
        $m = new Utilisateur();
        $existe = $m->existUser($login, $mdp);
        if ($existe) {
            $_SESSION['Auth'] = $existe;
            echo true;
        } else {
            $_SESSION['err'] = "Problême de connexion";
            header('Location: index.php');
            die();
        }
    }
}

function deconnexion()
{
    $_SESSION['Auth'] = null;
    session_destroy();
    header("Location: index.php");
}