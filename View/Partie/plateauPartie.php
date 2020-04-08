<h1 id="partieId"><?php echo $dReponse["title"]; ?></h1>

<div id="plateau">
    <div id="carte"></div>
    
    <?php
        $partie = $_SESSION["partie"];
        $user = $_SESSION["user"];
        switch($user->getId()) {
            case ($partie->getMaitre()): include("PlateauIncludes/CommandesMaitre.php");
        }
    ?>

</div>

<aside id="asideChat">
    <?php include("View/Chat/chat.php"); ?>
</aside>