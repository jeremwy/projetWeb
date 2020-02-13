<?php
/*
    Cette classe permet de faire des vues qui vont rediriger l'utilisateur vers une url au bout de X secondes
*/
class RedirectView extends View
{
    private $time;
    private $url;

    public function __construct($viewName, $url, $time = 0, $dReponse = [])
    {
        $this->time = $time;
        $this->url = $url;
        parent::__construct($viewName, $dReponse);
    }

    public function rend()
    {
        header('Refresh: ' . $this->time . '; URL=' . $this->url);
        parent::rend();
    }
}
?>