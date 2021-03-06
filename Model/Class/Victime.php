<?php

class Victime
{
    private $id;
    private $partie;
    private $nom;
    private $prenom;
    private $etat;          //peut prendre l'une des valeurs suivantes : 0 (sauf), 1 (légé), 2 (moyen), 3(grave) (à modifier)
    private $blessures;     //tableau des blessures ou chaîne de caractères ??
    private $vie;           //la vie ne sera pas affichée telle quelle aux joueurs, elle permet d'implémenter le fait que les victimes "meurent" petit à petit et plus ou moins vite en fonction de leur état

    private $etatsString = array("sauf", "légé", "moyen", "grave"); //permet de définir l'état en chaîne de caractères selon l'indice :0 (sauf), 1 (légé), 2 (moyen), 3(grave) (à modifier)

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
    }


    public function getPartie()
    {
        return $this->partie;
    }

    public function setPartie($partie)
    {
        $this->partie = $partie;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    public function getPrenom()
    {
        return $this->prenom;
    }

    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    public function getEtat()
    {
        return $this->etat;
    }

    public function getEtatString()
    {
        return $this->etatsString[$this->etat];
    }

    public function setEtat($etat)
    {
        $this->etat = $etat;
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
    }

    public function getVie()
    {
        return $this->vie;
    }

    public function setVie($vie)
    {
        $this->vie = $vie;
    }

     /*
        Permet d'appeler la fonction "get_object_vars" depuis l'extérieur de la classe.
        Ainsi, on pourra savoir quels sont les attributs de la classe (noms et valeurs) sans se soucier du "scope".
        (Utilisée pour la classe XML Partie)
    */
    public function getVars()
    {
        return get_object_vars($this);
    }
}