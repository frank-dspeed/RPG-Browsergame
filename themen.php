<?php
include("funktionen.php");
?>

<html>

<head>
    <title>Themen</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="rpgstyle.css" />
</head>

<div class="Zurückbutton">
    <a href="/rpg.php"><img src="Bilder/Zurückbutton.png" /></a>
</div>
<div class="WaffenContainer">
    <p class="Überschrift">Themenuebersicht</p>
    <div class="Waffenliste">
        <p><?php $newClass->ThemenAnzeigen($connection) ?></p>
    </div>
</div>

</html>