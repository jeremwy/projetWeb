<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <?php
      foreach($dResponse["style"] as $url)
      {
        echo '<link rel="stylesheet" href="' . $url . '">';
      }
      foreach($dResponse["font"] as $url)
      {
        echo '<link rel="stylesheet" href="' . $url . '">';
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
  </body>
</html>