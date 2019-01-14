<?php
include "M/Utilisateur.php";
include "M/Table.php";

function verifConnection()
{
    if (ifConnexion()) {
        require "V/index.php";
    }
}

function ifConnexion()
{
    if (isset($_SESSION['Auth']) && !empty($_SESSION['Auth'])) {
        if (!isset($_SESSION['tables']) || empty($_SESSION['tables'])) {
            $tables = new Table();
            $tables = $tables->getAllTable();
            $_SESSION['tables'] = $tables;
        }
        return true;
    }
    require "V/connexion.php";
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
        } else {
            $_SESSION['err'] = "Problême de connexion";
        }

        header('Location: index.php');
    }
}

function deconnexion()
{
    $_SESSION['Auth'] = null;
    session_destroy();
    header("Location: index.php");
}