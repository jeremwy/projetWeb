<?php
class User
{
    public $nom;
    public $prenom;
    public $motDePasse;
    public $inscription;

    public function __construct($nom = NULL, $prenom = NULL, $motDePasse = NULL)
    {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->motDePasse = $motDePasse;
        $this->inscription = time();
    }
}
?>