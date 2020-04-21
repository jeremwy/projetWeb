<?php
class Partie
{
    /*
        Deux parties peuvent avoir le même nom mais pas le même identifiant.
        Un identifiant est de la forme: nom-clef_unique. La clef unique est générée au moement de la sauvegarde dans la base de données avec
        la fonction php unidid().
    */
    private $id;
    private $nom;
    private $date;
    private $maitre;
    private $chefPompier;
    private $chefPolicier;
    private $chefMedecin;
    private $horloge;
    private $enCours;

    public function __construct($id = null, $nom = null, $maitre = null, $chefPompier = null, $chefPolicier = null, $chefMedecin = null, $horloge = 0, $enCours = 0)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->date = new DateTime();
        $this->maitre = $maitre;
        $this->chefPompier = $chefPompier;
        $this->chefPolicier = $chefPolicier;
        $this->chefMedecin = $chefMedecin;
        $this->horloge = $horloge;
        $this->enCours = $enCours;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    public function setDate($date)
    {
        $this->date = $date;
    }

    public function setMaitre($maitre)
    {
        $this->maitre = $maitre;
    }

    public function setChefPompier($chefPompier)
    {
        
        $this->chefPompier = $chefPompier;
    }

    public function setChefMedecin($chefMedecin)
    {
        $this->chefMedecin = $chefMedecin;
    }

    public function setChefPolicier($chefPolicier)
    {
        $this->chefPolicier = $chefPolicier;
    }

    public function setHorloge($horloge)
    {
        $this->horloge = $horloge;
    }

    /*
        Permet d'ajouter n seconde(s) à l'horloge
    */
    public function ajoutHorloge($n)
    {
        $this->horloge = $this->horloge + $n;
    }

    public function setEnCours($enCours)
    {
        $this->enCours = $enCours;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function getDate()
    {
        return $this->date;
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

    public function getHorloge()
    {
        return $this->horloge;
    }

    public function isEnCours()
    {
        return $this->enCours;
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

    public function getRoles()
    {
        $roles = [
            "maitre" => $this->maitre,
            "chefMedecin" => $this->chefMedecin,
            "chefPolicier" => $this->chefPolicier,
            "chefPompier" => $this->chefPompier
        ];
        return $roles;
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
?>