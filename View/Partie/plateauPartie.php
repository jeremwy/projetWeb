<h1><?php echo $dReponse["title"] ?></h1>

<div id="plateau">
    <div id="carte" onload="init()"></div>
    
    <?php
        $partie = $_SESSION["partie"];
        $user = $_SESSION["user"];
        switch($user->getId()) {
            case ($partie->getMaitre()): include("PlateauIncludes/TableauBordMaitre.php");
        }
    ?>

</div>

<p id="partieId" class="hidden"><?php echo $dReponse["partieId"]; ?></p>

<aside id="asideChat">
    <?php include("View/Chat/chat.php"); ?>
</aside>