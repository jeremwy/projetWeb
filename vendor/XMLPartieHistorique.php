<?php
require_once("Model/Class/Partie.php");

/*
    Classe permettant de lire, de mettre à jour et d'enregistrer l'historique des parties au format XML.
*/
class XMLPartieHistorique
{
    private $partie;
    private $fichier_xml;
    private $fichier_xml_path;

    public function __construct($partie)
    {
        $this->partie = $partie;
        $this->fichier_xml_path = FOLDER_ROOT . SEPARATOR . "src" . SEPARATOR . "xml" . SEPARATOR . $partie->getId() . "_historique.xml";
        $this->load();
    }

    /*
        Permet d'ouvir le fichier XML dont le chemin est $fichier_xml_path.
        Si aucun fichier n'est trouvé alors un nouveau et créé.
    */
    private function load()
    {
        try
        {
            $fichier = fopen($this->fichier_xml_path, "r"); //ouverture du fichier en lecture seul (on l'écrase par la suite). La tête de lecture est placée au début du fichier
            if(filesize($this->fichier_xml_path) > 0)   //si le fihcier n'est pas vide alors il faut charger le xml
                $this->fichier_xml = simplexml_load_file($this->fichier_xml_path);  //on charge dans la varible fichier_xml le contenu du fichier
            else
                $this->creerFichierXml();   //si le fichier est vide alors on doit écrire certaines lignes de base (voir méthode creerFichierXml())
            fclose($fichier);   //on ferme le fichier ouvert (libération mémoire)
        } catch(Exception $e)
        {
            echo "Une erreur est survenue : " . $e->getMessage();
        }
    }

    /*
        Permet d'écrire dans la fichier les lignes de base de tous les documents XML permettant de tracer l'historique d'un partie.
        Ces lignes de base sont les suivantes:
            -ajout d'un élément englobant : historique
            -ajout d'un élément enfant à l'élément historique: partie
            -ajout de tous les attributs de l'objet "partie" (pour lequel on souhaite créer un fichier historique) ainsi que de leur valeur à cette élément partie.
    */
    private function creerFichierXml()
    {
        $historiqueXML = new SimpleXMLElement("<historique></historique>"); //création de l'élément historique
        $partieXML = $historiqueXML->addChild("partie");    //ajout de l'élément enfant partie

        $attributs = $this->partie->getVars();  //getVars() permet de récupérer tous les attributs ainsi que leur valeur de l'objet partie

        foreach($attributs as $nom => $valeur)  //pour chaque attribut sauf l'attribut "enCours" (on s'en fiche car on se doute que la partie était en cours au moment où elle était jouée)
        {
            if($nom == "victimes")  //si l'on veut ajouter la tableau des victimes, il faut passer par la méthode ajouterVictimes
            {
                $victimesXML = $partieXML->addChild("victimes");
                $this->ajouterVictimes($victimesXML, $valeur);
            }
            elseif($nom != "enCours")
                $partieXML->addChild($nom, $valeur); //on l'ajoute avec sa valeur à l'élément parte
        }

        $this->fichier_xml = $historiqueXML;    //le contenu du fichier XML est sauvegarder dans la variable fichier_XML
        $this->save();
    }

    /*
        Ajoute un tableau de victimes de la partie au fichier XML
    */
    private function ajouterVictimes($victimesXML, $victimes)
    {
        echo "ajout des victimes";
        foreach($victimes as $victime)
        {
            $victimeXML = $victimesXML->addChild("victime");
            $attributs = $victime->getVars();
            foreach($attributs as $nom => $valeur)      //comme pour la partie on ajoute les attributs et leur valeur
            {
                if($nom == "blessures")     //si l'attribut en cours est "blessures" alors ajoute la valeur de retour de getBlessuresString()
                    $victimeXML->addChild($nom, $victime->getBlessuresString());
                elseif($nom == "etat")     //si l'attribut en cours est "etat" alors ajoute la valeur de retour de getEtatString()
                $victimeXML->addChild($nom, $victime->getEtatString());
                elseif($nom != "id" && $nom != "partie")    //pas besoin d'écrire les attributs id et identifiant de partie car ce sont des informations propres à la BD qui seront supprimés une fois la partie terminée      
                    $victimeXML->addChild($nom, $valeur);
            }
        }
    }

    public function ajoutEvenement($evenement, $description, $temps)
    {
        $evenementXML = $this->fichier_xml->addChild($evenement, $description);  //on ajoute un nouvel élément "evenement" et sa valeur est "description" à l'historique
        $evenementXML->addAttribute("temps", $temps);   //on ajoute un attribut à cette événement qui est le temps t (en secondes) auquel il s'st produit
        $this->save();  //on sauvegarde
    }

    private function save()
    {
        /*
            Les 4 lignes suivantes sont utilisées afin de concerver une bonne indentation du fichier XML.
            "SimpleXML" écrit tout sur la même ligne alors que passer par une "DOMDocument" permet un contrôle plus simple.
        */
        $dom = new DOMDocument("1.0");
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($this->fichier_xml->asXML());
        $fichierXML = fopen($this->fichier_xml_path, "w");  //on ouvre le fichier en écriture. La tête d'écriture est positionnée au début du fichier (il est écrasé).
        fwrite($fichierXML, $dom->saveXML());   //on écrit le contenu XML dans le fichier
        fclose($fichierXML);    //on le ferme
    }
}
?>