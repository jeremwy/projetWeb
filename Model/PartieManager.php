<?php
require_once("Manager.php");
require_once("ChatManager.php");
require_once("Class/Partie.php");

class PartieManager extends Manager
{
    private $partie;

    public function __construct($partie = null)
    {
        parent::__construct();
        $this->partie = $partie;
    }

    public function getPartie($partieId)
    {
        $stmt = $this->db->prepare("SELECT *
                                    FROM partie
                                    WHERE id=:partieId");
        $stmt->bindValue(":partieId", $partieId);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Partie');
        $partie = $stmt->fetch();
        return $partie;
    }

    //retourne les parties qui ne sont pas encore lancées et qui penvent rejointes
    public function getParties()
    {
        $stmt = $this->db->prepare("SELECT *
                            FROM partie
                            WHERE enCours = 0");
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Partie');
        $parties = $stmt->fetchAll();
        return $parties;
    }

    public function setPartie($partie)
    {
        $this->partie = $partie;
    }

    //indique si l'identifiant est déjà utilisé
    public function isIdUsed($id)
    {
        $stmt = $this->db->prepare("SELECT *
                                    FROM partie
                                    WHERE id=:id");
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        $result = $stmt->fetch();
        return !($result == false);
    }

    public function savePartie()
    {
        $stmt = $this->db->prepare("INSERT INTO partie VALUES(:id, :maitre, NULL, NULL, NULL, 0)");
        $stmt->bindValue(":id", $this->partie->getId());
        $stmt->bindValue(":maitre", $this->partie->getMaitre());
        
        return $stmt->execute();
    }

    //retourne l'id de la partie dans lequel est l'utilisateur. Retourne false sinon.
    public function getUserPartie($userId)
    {
        $n = 4;
        $stmt = $this->db->prepare("SELECT *
                                    FROM partie
                                    WHERE maitre=:userId1 OR chefPompier=:userId2 OR chefPolicier=:userId3 OR chefMedecin=:userId4"); //après il faut rajouter "OR :userId=pompier OR :userId=police ...".
        for($i = 1; $i <= $n; $i++)
            $stmt->bindValue(":userId" . $i, $userId);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Partie');
        return $stmt->fetch();
    }
    
    public function getMaitre($partieId)
    {
        $stmt = $this->db->prepare("SELECT maitre
                                    FROM partie
                                    WHERE id=:partieId");
        $stmt->bindValue(":partieId", $partieId);        
        $stmt->execute();

        return $stmt->fetch()[0];
    }

    public function supprimerPartie($maitreId)
    {
        $stmt = $this->db->prepare("DELETE FROM partie
                                    WHERE maitre=:maitreId");
        $stmt->bindValue(":maitreId", $maitreId);        
        $stmt->execute();

        //suppression du chat de la partie
        $chatManager = new ChatManager();
        $chatManager->clearMessage($_SESSION["partie"]->getId());
    }

    public function addRole($role, $user, $partieId)
    {
        
        $stmt = $this->db->prepare("UPDATE partie
                                    SET " . $role . "=:user
                                    WHERE id=:partieId AND " . $role . " IS NULL");     //"IS NULL" va permetrre de tester directement si le rôle est libre
        $stmt->bindValue(":user", $user);
        $stmt->bindValue(":partieId", $partieId);
        $stmt->execute();
        return $count = $stmt->rowCount();
    }

    public function lancerPartie()
    {
        $stmt = $this->db->prepare("UPDATE partie
                                    SET enCours=1
                                    WHERE id=:partieId");
        $stmt->bindValue(":partieId", $_SESSION["partie"]->getId());
        return $stmt->execute();
    }
}
?>