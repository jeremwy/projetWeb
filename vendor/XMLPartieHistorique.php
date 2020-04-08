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
        $this->fichier_xml_path = "src/xml/" . $partie->getId() . "_historique.xml";
        $this->load();
        $this->save();
    }

    private function load()
    {
        $fichierXML = fopen($this->fichier_xml_path, "r");
        if(filesize($this->fichier_xml_path) > 0)
            $this->fichier_xml = simplexml_load_file($this->fichier_xml_path);
        else
            $this->creerFichierXml();
        fclose($fichierXML);
    }

    private function creerFichierXml()
    {
        $historiqueXML = new SimpleXMLElement("<historique></historique>");
        $partieXML = $historiqueXML->addChild("partie");

        $attributs = $this->partie->getVars();

        foreach($attributs as $nom => $valeur)
        {
            $partieXML->addChild($nom, $valeur);
        }

        $this->fichier_xml = $historiqueXML;
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
        //on écrase le fichier
        $fichierXML = fopen($this->fichier_xml_path, "w");
        fwrite($fichierXML, $dom->saveXML());
        fclose($fichierXML);
    }
}
?>