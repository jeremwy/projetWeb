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
    <?php
      include_once("header.php");
    ?>
    <div id="main">
      <?php
          include_once($dReponse["viewFileName"]);
      ?>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <?php
      if(isset($dReponse["js"]))
      {
        foreach($dReponse["js"] as $url)
        {
          echo '<script src="' . SITE_ROOT . 'src/js/' .$url . '"></script>';
        }
      }
    ?>
    
  </body>
</html>