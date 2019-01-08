<?php 

class Utilisateur
{
	private $bdd;
	function __construct()
	{
		$this->bdd = new Database();
	}

	public function existUser($login, $mdp){
		$req = $this->bdd->prepare("SELECT * FROM user WHERE login = ? AND mdp = ?");
		$req->execute(array($login, $mdp));
		if($req->rowCount() == 1){
			return $req->fetch();
		}else{
			return false;
		}
	}
	
}


/**
* 
*/
class ClassName extends AnotherClass
{
	
	function __construct(argument)
	{
		# code...
	}
}