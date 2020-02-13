<?php
function generateString($length)
{
    $caracteres = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $nbCaracteres = strlen($caracteres);
    $id = "";
    for($i = 0; $i < $length; $i++)
    {
        $id .= $caracteres[rand(0, $nbCaracteres-1)];
    }
    return $id;
}
?>