<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <?php
      if(isset($dReponse["style"]))
      {
        foreach($dReponse["style"] as $url)
        {
          echo '<link rel="stylesheet" href="' . $url . '">';
        }
      }
      if(isset($dReponse["font"]))
      {
        foreach($dReponse["font"] as $url)
        {
          echo '<link rel="stylesheet" href="' . $url . '">';
        }
      }
    ?>
    <title><?php echo $dReponse["title"] ?></title>
  </head>
  <body>
    <div id="main">
      <?php
          include_once($dReponse["viewFileName"]);
      ?>
    </div>
    
    <?php
      if(isset($dReponse["js"]))
      {
        foreach($dReponse["js"] as $url)
        {
          echo '<script type="text/javascript" src="' . SITE_ROOT . 'src/js/' .$url . '"></script>';
        }
      }
    ?>
  </body>
</html>