<?php
class User
{
    private $nom;
    private $prenom;
    private $id; //l'identifiant sert uniquement à référencer l'utilisateur dans les parties. Les clerfs primaires sont le nom et le prénom.
    private $motDePasse;
    private $inscription;

    public function __construct($nom = NULL, $prenom = NULL, $motDePasse = NULL, $inscription = NULL)
    {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->id = generateString(25);
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

    public function getId()
    {
        return $this->id;
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

    public function setId($id)
    {
        $this->id = $id;
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