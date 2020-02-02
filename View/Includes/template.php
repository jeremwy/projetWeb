<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <?php
      if(isset($dResponse["style"]))
      {
        foreach($dResponse["style"] as $url)
        {
          echo '<link rel="stylesheet" href="' . $url . '">';
        }
      }
      if(isset($dResponse["font"]))
      {
        foreach($dResponse["font"] as $url)
        {
          echo '<link rel="stylesheet" href="' . $url . '">';
        }
      }
    ?>
    <title><?php echo $dResponse["title"] ?></title>
  </head>
  <body>
    <div id="main">
      <?php
          include_once($dResponse["viewFileName"]);
      ?>
    </div>
    
    <?php
      if(isset($dResponse["js"]))
      {
        foreach($dResponse["js"] as $url)
        {
          echo '<script type="text/javascript" src="' . $url . '"></script>';
        }
      }
    ?>
  </body>
</html>