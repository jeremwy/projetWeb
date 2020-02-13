<?php
require_once("Manager.php");
require_once("Class/Partie.php");
class PartieMAnager extends Manager
{
    private $partie;

    public function __construct($partie = null)
    {
        parent::__construct();
        $this->partie = $partie;
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
        $stmt = $this->db->prepare("INSERT INTO partie VALUES(:id, :maitre, 0)");
        $stmt->bindValue(":id", $this->partie->getId());
        $stmt->bindValue(":maitre", $this->partie->getMaitre());
        
        return $stmt->execute();
    }
}
?>