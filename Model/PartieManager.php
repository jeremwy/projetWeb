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
}
?>