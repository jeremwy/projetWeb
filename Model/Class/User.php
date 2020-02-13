<?php
class User
{
    private $nom;
    private $prenom;
    private $id; //l'identifiant sert uniquement à référencer l'utilisateur dans les parties. Les clerfs primaires sont le nom et le prénom.
    private $motDePasse;
    private $inscription;
    private $role;

    /*   
     Les rôles seront :
        - 1 ChefPompier
        - 2 ChefMedecin
        - 3 ChefPolicier
    */

    public function __construct($nom = NULL, $prenom = NULL, $motDePasse = NULL, $inscription = NULL, $role = NULL)
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
        $this->role=$role;
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

    public function getRole()
    {
        return $this->role;
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

    public function setRole($role)
    {
        //$role = chiffre entre 1 et 3
        if($role >= 1 && $role <=3)
            $this->role = $role;
    }

    
    // Méthodes placements personnage et véhicule A COMPLETER AVEC x et y DANS LA GRILLE (PARTIE IMAGE)
    public function placerVehicule($x, $y)
    {
        switch($role)
        {
            case 1 :
                break;
            case 2 :
                break;
            case 3 :
                break;
            default : 
                break;
        }
    }

    public function placerPersonnage($x, $y)
    {
        switch($role)
        {
            case 1 :
                break;
            case 2 :
                break;
            case 3 :
                break;
            default : 
                break;
        }
    }
}
?>