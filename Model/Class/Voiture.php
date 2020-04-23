<?php

class Voiture
{
    private $id;
    private $partie;
    private $fonction;
    private $x;
    private $y;
    private $z;

    public function __construct($id = null, $partie = null, $fonction = null, $x = null, $y = null, $z = null)
    {
        $this->id = $id;
        $this->partie = $partie;
        $this->fonction = $fonction;
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;
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

    public function getFonction()
    {
        return $this->fonction;
    }

    public function setFonction($fonction)
    {
        $this->fonction = $fonction;
    }

    public function getX()
    {
        return $this->x;
    }

    public function setX($x)
    {
        $this->x = $x;
    }

    public function getY()
    {
        return $this->y;
    }

    public function setY($y)
    {
        $this->y = $y;
    }

    public function getZ()
    {
        return $this->z;
    }

    public function setZ($z)
    {
        $this->z = $z;
    }
}