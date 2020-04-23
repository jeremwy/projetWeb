<?php
require_once("Model/Class/Victime.php");

/*
    Classe permettant de générer les victimes d'une partie (nom, prénom, blessures ...).
    Elle évite de surcharger le code de la classe PartieController.php
*/
class GenerateurVictimes
{
    /*
        Ces tableaux constituent une "base de données" qui permet de générer les vicitimes.
    */
    private $nomsTab = array("Martin", "Bernard", "Legrand", "Chevalier", "Lecomte");
    private $prenomsTab = array("Thierry", "Manon", "Christian", "Sofia", "Nathalie", "Marion", "Jules");
    /*
        Ce tableau permet d'avoir dans le même tableau les blessures et leut état de gravité ce qui est beaucoup plus simple à lire.
        Il permet d'éviter d'avoir deux tableaux : un pour les blessures et un pour l'état quelles entraînent.
        Par contre pour sélectionner aléatoirement les blessures, il faudra extraire les clefs (voir méthode générerListe).
    */
    private $blessuresTab = array(
                                "Petit saignement" => 1,
                                "Saignement moyen" => 2,
                                "Saignement important" => 3,
                                "Brûlures légères" => 2,
                                "Brûlures graves" => 3,
                                "Traumatisme psychologique" => 0
                            );

    /*
        Génère une liste de $n victime(s) pour la partie d'id $partieId
    */
    public function genererListe($partieId, $n)
    {
        //d'abord on génère $n identités différentes puis on crée les victimes
        $victimes = array();
        $identites = array();
        $noms = array();
        $prenoms = array();

        //générations des identités
        for($i = 0; $i < $n; $i++)
        {
            do{
                $nom = $this->nomsTab[random_int(0, count($this->nomsTab) - 1)];
                $prenom = $this->prenomsTab[random_int(0, count($this->prenomsTab) - 1)];
            }while(in_array($nom . " "  .$prenom, $identites));

            array_push($identites, $nom . " "  .$prenom);
            array_push($noms, $nom);
            array_push($prenoms, $prenom);
        }

        //création et ajout des victimes à la liste des victimes:

        /*
            Contient les clefs du tableau $blessuresTab.
            Plus bas, on génère un entier aléatoire $k pour ajouter une blessure à la victime.
            On ne peut accéder au tableau $blessuresTab que par une clef (= nom blessure) il faut donc d'abord récupérer la clefs d'indice $k.
            La fonction php array_keys renvoie un tableau contenant les clefs (ici, dans $nomsBlessures) d'un tableau associtatif, on récupère alors la clefs d'indice $k
            pour récupérer la blessure dans le tableau $blessuresTab.
        */
        $nomsBlessures = array_keys($this->blessuresTab);
        for($i = 0; $i < $n; $i++)
        {
            $isSauf = random_int(0, 1);     //permet de définir si la personne est sauve
            if($isSauf > 0.95)              //si $isSauf est supérieur à 0.95 (5% de chance) alors la personne est sauve
                array_push($victimes, new Victime(null, $partieId, $noms[$i], $prenoms[$i]));   //voir constructeur classe Victime
            else                            //sinon elle a au moins une blessure
            {
                $nbBlessures = random_int(1, 4);    //indique le nombre de blessures de la victime (entre 1 et 4 inclus)
                $blessures = array();               //contient toutes les blessures de la victime
                $etat = 0;                          //etat de la victime
                for($j = 0; $j < $nbBlessures; $j++) 
                {
                    do
                    {
                        $k = random_int(0, count($this->blessuresTab) - 1); //on génère un entier $k aléatoire
                        $blessure = $nomsBlessures[$k];                     //on récupère la blessure d'indice $k
                    }while(in_array($blessure, $blessures));                //tant que la blessure trouvée est déjà dans le tbaleau des blessures de la victime
                    array_push($blessures, $blessure);                      //on ajoute la nouvelle blessure ua tableau des blessures

                    if($etat < $this->blessuresTab[$blessure])      //si la nouvelle blessure met la victime dans un état plus grave
                        $etat = $this->blessuresTab[$blessure];     //alors on met à jour cet état
                }
                array_push($victimes, new Victime(null, $partieId, $noms[$i], $prenoms[$i], $etat, $blessures));    //on ajoute la victime à la liste des victimes              
            }
        }
        return $victimes;
    }
}