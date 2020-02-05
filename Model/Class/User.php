<?php
class User
{
    private $nom;
    private $prenom;
    private $motDePasse;
    private $inscription;

    public function __construct($nom = NULL, $prenom = NULL, $motDePasse = NULL, $inscription = NULL)
    {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->motDePasse = $motDePasse;
        if($inscription === NULL)
        {
            $this->inscription = time();
        }
        else
        {
            $this->inscription = $inscription;
        }
        
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function getPrenom()
    {
        return $this->prenom;
    }

    public function getMotDePasse()
    {
        return $this->motDePasse;
    }

    public function getInscription()
    {
        return $this->inscription;
    }

    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    public function setMotDePasse($motDePasse)
    {
        $this->motDePasse = $motDePasse;
    }

    public function setInscription($inscription)
    {
        $this->inscription = $inscription;
    }
}
?>