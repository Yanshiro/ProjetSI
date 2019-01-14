<?php
//3faf7ed52fa83d583fc670a96bcf92da270d0767 
session_start();
include "lib/Database.php";

if (!isset($_POST["controller"]) && empty($_POST['controller']) && !isset($_GET["controller"]) && empty($_GET["controller"])) {
	$_POST['controller'] = "Utilisateur";
	$_POST['f'] = "verifConnection";
}

if (isset($_POST["controller"]) && !empty($_POST['controller'])) {
	if (file_exists("C/" . $_POST["controller"] . ".php")) {
		require "C/" . $_POST["controller"] . ".php";
		if (function_exists($_POST["f"])) {
			$_POST["f"]($_GET, $_POST);
		} else {
			echo "probleme fonction";
		}
	} else {
		echo "probleme controller";
	}
}

if (isset($_GET["controller"]) && !empty($_GET["controller"])) {
	if (file_exists("C/" . $_GET["controller"] . ".php")) {
		require "C/" . $_GET["controller"] . ".php";
		if (function_exists($_GET["f"])) {
			$_GET["f"]($_GET, $_POST);
		} else {
			echo "probleme fonction";
		}
	} else {
		echo "probleme controller";
	}
}

if (isset($_SESSION['err']) && !empty($_SESSION['err'])) {
	echo ($_SESSION['err']);
	$_SESSION['err'] = null;
	die();
}

var_dump($_POST);
var_dump($_GET);