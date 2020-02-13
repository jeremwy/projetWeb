<?php
class Partie
{
    private $id;
    private $maitre;
    private $chefPompier;
    private $chefPolicier;
    private $chefMedecin;
    private $enCours;

    public function __construct($id = null, $maitre = null, $chefPompier = null, $chefPolicier = null, $chefMedecin = null, $enCours = 0)
    {
        $this->id = $id;
        $this->maitre = $maitre;
        $this->chefPompier = $chefPompier;
        $this->chefPolicier = $chefPolicier;
        $this->chefMedecin = $chefMedecin;
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

    public function setChefPompier($chefPompier)
    {
        
        $this->chefPompier = $chefPompier;
        //? Est ce qu'on le fait comme ça ?
        $chefPompier->role = 1;
        //?
    }

    public function setChefMedecin($chefMedecin)
    {
        $this->chefMedecin = $chefMedecin;
        //? Est ce qu'on le fait comme ça ?
        $chefMedecin->role = 2;
        //?
    }

    public function setChefPolicier($chefPolicier)
    {
        $this->chefPolicier = $chefPolicier;
        //? Est ce qu'on le fait comme ça ?
        $chefPolicier->role = 3;
        //?
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

    public function getChefPompier()
    {
        return $this->chefPompier;
    }

    public function getChefPolicier()
    {
        return $this->chefPolicier;
    }

    public function getChefMedecin()
    {
        return $this->chefMedecin;
    }

    public function isEnCours()
    {
        return $this->isEnCours == 1;
    }


    public function getNbJoueur()
    {
        $nbJoueur = 0;
        if($this->maitre != null)
        {
            $nbJoueur++;
        }

        if($this->chefPompier != null)
        {
            $nbJoueur++;
        }

        if($this->chefMedecin != null)
        {
            $nbJoueur++;
        }

        if($this->chefPolicier != null)
        {
            $nbJoueur++;
        }

        return $nbJoueur;
    }
}
?>