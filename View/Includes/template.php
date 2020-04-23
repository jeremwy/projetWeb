<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <?php
      $i = 0; //permet de donner un id spécifique pour chaque feuille de style. Attention : pour concerver la logique du bouton de changement de thème, la première feuille CSS doit être la fueille qui gère les couleurs (voir code js).
      if(isset($dReponse["style"]))
      {
        
        foreach($dReponse["style"] as $url)
        {
          echo '<link id="css-stylesheet-'.  $i .'" rel="stylesheet" href="' . $url . '">';
          $i++;
        }
      }
      if(isset($dReponse["font"]))
      {
        foreach($dReponse["font"] as $url)
        {
          echo '<link id="css-stylesheet-'.  $i .'" rel="stylesheet" href="' . $url . '">';
          $i++;
        }
      }
    ?>
    <title><?php echo $dReponse["title"] ?></title>
  </head>
  <body>
    <?php
      include_once("header.php");
    ?>
    <div id="main">
      <?php
          include_once($dReponse["viewFileName"]);
      ?>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="<?php echo SITE_ROOT; ?>switch_theme.js"></script>
    <?php
      if(isset($dReponse["js"]))
      {
        foreach($dReponse["js"] as $url)
        {
          echo '<script src="' . SITE_ROOT .$url . '"></script>';
        }
      }
    ?>
    
  </body>
</html>