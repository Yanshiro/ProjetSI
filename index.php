<?php
//3faf7ed52fa83d583fc670a96bcf92da270d0767 
session_start();
include "lib/Database.php";

if (!isset($_POST["controller"]) && empty($_POST['controller']) && !isset($_GET["controller"]) && empty($_GET["controller"])) {
	$_POST['controller'] = "UtilisateurController";
	$_POST['f'] = "verifConnection";
}

if (isset($_POST["controller"]) && !empty($_POST['controller'])) {
	orientationController($_POST, $_GET, $_POST);

}

if (isset($_GET["controller"]) && !empty($_GET["controller"])) {
	orientationController($_GET, $_GET, $_POST);
}

function orientationController($param, $get, $post)
{
	if (isset($param["controller"]) && !empty($param['controller'])) {
		if (file_exists("C/" . $param["controller"] . ".php")) {
			require "C/" . $param["controller"] . ".php";
			if (class_exists($param['controller'])) {
				$classe = new $param['controller']();
				if (method_exists($classe, $param['f'])) {
					$classe->{$param['f']}($get, $post);
				} else {
					echo "erreur methode inconnu " . $param['f'];
				}
			} else {
				echo "erreur classe du controller inconnue" . $param['controller'];
			}
		}
	}

	if (isset($_SESSION['err']) && !empty($_SESSION['err'])) {
		echo ($_SESSION['err']);
		$_SESSION['err'] = null;
		die();
	}
}

var_dump($_POST);
var_dump($_GET);