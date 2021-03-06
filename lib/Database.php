<?php

/**
 * Connection a la base de donnée
 */

class Database
{

	private $db_pass;
	private $db_user;
	private $connSql;
	private $bdd;
	private $db_name;

	function __construct($fileConfigJson = "config.json")
	{
		$fileConfig = file_get_contents($fileConfigJson);
		$config = json_decode($fileConfig);

		$this->db_user = $config->user;
		$this->db_pass = $config->password;
		$this->connSql = $config->sql;
		$this->db_name = $config->db_name;
	}

	public function getBbName()
	{
		return $this->db_name;
	}

	private function getPDO()
	{
		if (empty($this->bdd)) {
			if (!empty($this->db_pass)) { {
					$bdd = new PDO($this->connSql, '' . $this->db_user . '', '' . $this->db_pass, array(
						PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
					));
				}
			} else {
				$bdd = new PDO($this->connSql, '' . $this->db_user . '', '', null);
			}
			$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->bdd = $bdd;
		}
		return $this->bdd;
	}


	public function query($stament, $fetch = 0)
	{
		$res = $this->getPDO()->query($stament);
		if ($fetch != 0) {
			$data = $res->fetch(PDO::FETCH_OBJ);
			return $data;
		} else {
			return $res;
		}
	}

	public function afficher($stament, $option, $one = false)
	{
		$req = $this->getPDO()->prepare($stament);
		$req->execute(array($option));
		if ($one) {
			$data = $req->fetch();
		} else {
			$data = $req->fetchAll();
		}
		return $data;
	}


	public function prepare($stament)
	{
		//fonction pour suprimer ou inserer

		//ex insertion : $req = $bdd->prepare('INSERT INTO admin(Nom, Prenom, age, Adresse_mail, Nom_utilisateur, MDP) 
		//VALUES(?, ?, ?, ?, ?, ?)');
		//$req->execute(array($nom, $prenom, $age, $mail, $admin, $MDP));

		//ex delete 
		//$int = $bdd->prepare("DELETE FROM admin WHERE Nom_utilisateur= ?");
		//$int->execute(array($sup));

		//ex update
		//$SELECT1 = $bdd->prepare("UPDATE affiche SET une = 0 WHERE id = ?");
		//$SELECT1->execute(array($SELECT['id']));

		return $req = $this->getPDO()->prepare($stament);
	}


	public function update($table, $valeur_a_modif, $new_valeur, $ou)
	{
		$select = $this->getPDO()->prepare("UPDATE $table SET $valeur_a_modif = ? WHERE id = ?");
		$select->execute(array($new_valeur, $ou));
	}

	public function insert($table, $champs, $nbvaleur)
	{
		return $select = $this->getPDO()->prepare('INSERT INTO ' . $table . '(' . $champs . ') VALUES(' . $nbvaleur . ')');
	}

	public function delete($table, $id)
	{
		$sup = $this->getPDO()->prepare("DELETE FROM $table WHERE id = ?");
		$sup->execute(array($id));
	}
}