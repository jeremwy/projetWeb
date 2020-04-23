<?php
require_once("Manager.php");
require_once("Class/Voiture.php");

class VoitureManager extends Manager
{
    private $voiture;

    public function __construct($voiture = null)
    {
        parent::__construct();
        $this->voiture = $voiture;
    }

    /*
        Permet de sauvegarder la voiture contenue dans l'attribut
    */
    public function save()
    {
        $stmt = $this->db->prepare("INSERT INTO voiture VALUES (:id, :partie, :fonction, :x, :y, :z)");
        $stmt->bindValue(":id", null);  //variable en auto_increment il faut mettre nulle et le sgbd s'occupe de mettre la bonne valeur
        $stmt->bindValue(":partie", $this->voiture->getPartie());
        $stmt->bindValue(":fonction", $this->voiture->getFonction());
        $stmt->bindValue(":x", $this->voiture->getX());
        $stmt->bindValue(":y", $this->voiture->getY());
        $stmt->bindValue(":z", $this->voiture->getZ());
        return $stmt->execute();
    }
}