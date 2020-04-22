<?php

class Victime
{
    private $id;
    private $partie;
    private $nom;
    private $prenom;
    private $etat;          //peut prendre l'une des valeurs suivantes : 0 (sauf), 1 (légèrement blessé), 2 (grvae) (à modifier)
    private $blessures;     //tableau des blessures ou chaîne de caractères ??
    private $vie;           //la vie ne sera pas affichée telle quelle aux joueurs, elle permet d'implémenter le fait que les victimes "meurent" petit à petit et plus ou moins vite en fonction de leur état


    public function __construct($id = null, $partie = null, $nom = null, $prenom = null, $etat = 0, $blessures = array(), $vie = 10000)
    {
        $this->id = $id;
        $this->partie = $partie;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->etat = $etat;
        $this->blessures = $blessures;
        $this->vie = $vie;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }


    public function getPartie()
    {
        return $this->partie;
    }

    public function setPartie($partie)
    {
        $this->partie = $partie;

        return $this;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom()
    {
        return $this->prenom;
    }

    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEtat()
    {
        return $this->etat;
    }

    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    public function getBlessures()
    {
        return $this->blessures;
    }

    /*
        Retourne le tableau des blessures sous le forme d'un chaîne de caractères.
    */
    public function getBlessuresString()
    {
        return implode(", ", $this->blessures);
    }

    public function setBlessures($blessures)
    {
        $this->blessures = $blessures;

        return $this;
    }

    public function getVie()
    {
        return $this->vie;
    }

    public function setVie($vie)
    {
        $this->vie = $vie;

        return $this;
    }
}