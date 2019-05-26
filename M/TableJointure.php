<?php

class TableJointure
{
    private $bdd;
    public static $SystemeJointure = "SystemeJointure";
    function __construct()
    {
        $this->bdd = new Database();
    }

    /**
     * Recuperation de toute les jointure pas rapport a un champs
     */
    public function recuperationJointure($table, $colonne)
    {
        $resultat = $this->infoJointure($table, $colonne);
        if (!empty($resultat)) {
            if ($resultat->Table1 != $table) {
                $table = $resultat->Table1;
                $colonne = $resultat->Champ1;
            } else {
                $table = $resultat->Table2;
                $colonne = $resultat->Champ2;
            }
            $req = $this->bdd->prepare("SELECT * FROM " . $table);
            $req->execute();
            return $req->fetchAll(PDO::FETCH_OBJ);
        } else {
            throw new Exception("Erreur, lien non trouvé");
        }
    }

    /**
     * Structure d'une jointure
     */
    public function infoJointure($table, $colonne)
    {
        $req = $this->bdd->prepare(
            "SELECT * FROM " . TableJointure::$SystemeJointure .
                " WHERE(Table1 = ? and Champ1 = ?) or (Table2 = ? and Champ2 = ?)"
        );
        $req->execute(array($table, $colonne, $table, $colonne));
        return $req->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Ajout des jointure dans la table jointure
     */
    public function addJointure($table1, $champ1, $table2, $champ2)
    {
        $req = $this
            ->bdd
            ->prepare(
                "INSERT INTO " . TableJointure::$SystemeJointure .  "(Table1, Champ1, Table2, Champ2) VALUE (?, ?, ?, ?)"
            );
        $req->execute(array($table1, $champ1, $table2, $champ2));
    }
}
