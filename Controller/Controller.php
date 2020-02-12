<?php
class Controller
{
    protected function isConnected()
    {
        if(isset($_SESSION["user"]))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}
?>