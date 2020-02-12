<?php
class Partie
{
    private $id;
    private $maitre;
    private $enCours;

    public function __construct($id = null, $maitre = null, $enCours = 0)
    {
        $this->id = $id;
        $this->maitre = $maitre;
        $this->enCours = $enCours;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setMaitre($maitre)
    {
        $this->maitre = $maitre;
    }

    public function setEnCours($enCours)
    {
        $this->enCours = $enCours;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getMaitre()
    {
        return $this->maitre;
    }

    public function isEnCours()
    {
        return $this->isEnCours == 1;
    }

    //à complétrer avec les autres rôles
    public function getNbJoueur()
    {
        $nbJoueur = 0;
        if($this->maitre != null)
        {
            $nbJoueur++;
        }

        return $nbJoueur;
    }
}
?>