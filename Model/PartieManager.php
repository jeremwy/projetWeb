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

    /*
        Setter
    */
    public function setPartie($partie)
    {
        $this->partie = $partie;
    }

    /*
        Permet de récupérer la partie d'id $partieId depuis la base de données
    */
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

    /*
        Permet de récupérer la nom d'une partie d'id $partieId depuis la base de données
    */
    public function getPartieNom($partieId)
    {
        $partie = $this->getPartie($partieId);
        return $partie->getNom();
    }

    /*
        Retourne les parties qui ne sont pas encore lancées et qui penvent rejointes
    */
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

    /*
        Indique si l'identifiant est déjà utilisé
    */
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
    /*
        Indique si la partie est lancée
    */
    public function isPartieEnCours($id)
    {
        $stmt = $this->db->prepare("SELECT *
                                    FROM partie
                                    WHERE id=:id");
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Partie');
        $partie = $stmt->fetch();
        return $partie->isEnCours() == true;
    }

    /*
        Permet de sauvegarder la partie stockée en attribut
    */
    public function savePartie()
    {
        $stmt = $this->db->prepare("INSERT INTO partie VALUES(:id, :nom, :date, :maitre, :chefPompier, :chefPolicier, :chefMedecin, :horloge, :enCours)");
        $stmt->bindValue(":id", $this->partie->getId());
        $stmt->bindValue(":nom", $this->partie->getNom());
        $stmt->bindValue(":date", $this->partie->getDateString());
        $stmt->bindValue(":maitre", $this->partie->getMaitre());
        $stmt->bindValue(":chefPompier", $this->partie->getChefPompier());
        $stmt->bindValue(":chefPolicier", $this->partie->getChefPolicier());
        $stmt->bindValue(":chefMedecin", $this->partie->getChefMedecin());
        $stmt->bindValue(":horloge", $this->partie->getHorloge());
        $stmt->bindValue(":enCours", $this->partie->isEnCours());
        
        return $stmt->execute();
    }

    /*
        Permet de mettre à jour la partie stockée en attribut
    */
    public function updatePartie($id)
    {
        $stmt = $this->db->prepare("UPDATE partie 
                                    SET 
                                        nom = :nom,
                                        date = :date,
                                        maitre = :maitre,
                                        chefPompier = :chefPompier,
                                        chefPolicier = :chefPolicier,
                                        chefMedecin = :chefMedecin,
                                        horloge = :horloge,
                                        enCours = :enCours
                                    WHERE id = :id");
        $stmt->bindValue(":id", $this->partie->getId());
        $stmt->bindValue(":nom", $this->partie->getNom());
        $stmt->bindValue(":date", $this->partie->getDate());
        $stmt->bindValue(":maitre", $this->partie->getMaitre());
        $stmt->bindValue(":chefPompier", $this->partie->getChefPompier());
        $stmt->bindValue(":chefPolicier", $this->partie->getChefPolicier());
        $stmt->bindValue(":chefMedecin", $this->partie->getChefMedecin());
        $stmt->bindValue(":horloge", $this->partie->getHorloge());
        $stmt->bindValue(":enCours", $this->partie->isEnCours());
        
        return $stmt->execute();
    }

    /*
        Retourne l'id de la partie dans lequel est l'utilisateur. Retourne false sinon.
    */
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
    
    /*
        Retourne le maître d'une partie
    */
    public function getMaitre($partieId)
    {
        $stmt = $this->db->prepare("SELECT maitre
                                    FROM partie
                                    WHERE id=:partieId");
        $stmt->bindValue(":partieId", $partieId);        
        $stmt->execute();

        return $stmt->fetch()[0];
    }

    /*
        Permet de supprimer une partie d'id $partieId
    */
    public function supprimerPartie($partieId)
    {
        $stmt = $this->db->prepare("DELETE FROM partie
                                    WHERE id=:partieId");
        $stmt->bindValue(":partieId", $partieId);        
        $stmt->execute();

        //suppression du chat de la partie
        $chatManager = new ChatManager();
        $chatManager->clearMessage($partieId);
    }

    /*
        Ajoute un rôle pour un certain utilisateur d'une partie
    */
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

    /*
        Lance la partie
    */
    public function lancerPartie()
    {
        $stmt = $this->db->prepare("UPDATE partie
                                    SET enCours=1
                                    WHERE id=:partieId");
        $stmt->bindValue(":partieId", $this->partie->getId());
        return $stmt->execute();
    }

    /*
        Permet d'ajouter n seconde(s) à l'horloge d'une partie dans la base de donnée
    */
    public function ajoutHorloge($n)
    {
        $partieId = $_SESSION["partie"]->getId();
        $this->partie = $this->getPartie($partieId);
        $this->partie->ajoutHorloge($n);
        $this->updatePartie($partieId);
    }

    public function getHorloge()
    {
        $partieId = $_SESSION["partie"]->getId();

        $stmt = $this->db->prepare("SELECT horloge FROM partie where id = :id");
        $stmt->bindValue(":id", $partieId);
        $stmt->execute();
        return $stmt->fetch()[0];
    }
}
?>