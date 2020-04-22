<?php
require_once("Manager.php");
require_once("Class/Victime.php");

class VictimeManager extends Manager
{
    /*
        Ajoute une victime à la base de données.
    */
    public function addVictime($victime)
    {
        $stmt = $this->db->prepare("INSERT INTO victime VALUES (:id, :partie, :nom, :prenom, :etat, :blessures, :vie)");
        $stmt->bindValue(':id', null);  //comme l'identifiant est un auto_increment, il faut mettre null
        $stmt->bindValue(':partie', $victime->getPartie());
        $stmt->bindValue(':nom', $victime->getNom());
        $stmt->bindValue(':prenom', $victime->getPrenom());
        $stmt->bindValue(':etat', $victime->getEtat());
        $stmt->bindValue(':blessures', $victime->getBlessuresString());
        $stmt->bindValue(':vie', $victime->getVie());
        $stmt->execute();
    }

    /*
        Ajoute plusieurs victimes à la base de données
    */
    public function addVictimes($victimes)
    {
        $stmt = $this->db->prepare("INSERT INTO victime VALUES (:id, :partie, :nom, :prenom, :etat, :blessures, :vie)");
        foreach($victimes as $victime)
        {
            $stmt->bindValue(':id', null);  //comme l'identifiant est un auto_increment, il faut mettre null
            $stmt->bindValue(':partie', $victime->getPartie());
            $stmt->bindValue(':nom', $victime->getNom());
            $stmt->bindValue(':prenom', $victime->getPrenom());
            $stmt->bindValue(':etat', $victime->getEtat());
            $stmt->bindValue(':blessures', $victime->getBlessuresString());
            $stmt->bindValue(':vie', $victime->getVie());
            $stmt->execute();
        }
    }

    public function supprimerVictimes($partieId)
    {
        $stmt = $this->db->prepare("DELETE FROM victime WHERE partie=:partieId");
        $stmt->bindValue(":partieId", $partieId);
        $stmt->execute();
    }

    public function getVictimes($partieId)
    {
        $stmt = $this->db->prepare("SELECT * FROM victime WHERE partie=:partieId");
        $stmt->bindValue(':partieId', $partieId);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Victime');
        return $stmt->fetchAll();
    }

    public function updateVictimesVie($victimes)
    {
        $stmt = $this->db->prepare("UPDATE victime SET vie=:vie WHERE id=:id");
        foreach($victimes as $victime)
        {
            $stmt->bindValue(':vie', $victime->getVie());
            $stmt->bindValue(':id', $victime->getId());
            $stmt->execute();
        }
    }
}