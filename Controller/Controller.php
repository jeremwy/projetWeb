<?php
class Controller
{
    protected static function isConnected()
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
//test 4
?>