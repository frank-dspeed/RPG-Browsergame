<?php
include("funktionen.php");
?>

<html>
    <head>
        <title>Themen</title>
        <meta charset="utf-8" />
        <link rel="stylesheet" type="text/css" href="rpgstyle.css" />
    </head>

    <a href="/rpg.php"><img src="Bilder/Zurückbutton.png" width="100" height="100" /></a>

    <div class="ThemenContainer">
    <p><?php $newClass->ThemenAnzeigen($connection)?></p>
   </div>

</html>