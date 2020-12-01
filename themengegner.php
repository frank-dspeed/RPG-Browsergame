<?php
include("funktionen.php");
?>

<html>

<head>
    <title>ThemenGegner</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="rpgstyle.css" />
</head>

<div class="Zurückbutton">
    <a href="/themen.php"><img src="Bilder/Zurückbutton.png" /></a>
</div>
<div class="WaffenContainer">
    <p class="Überschrift">Gegneruebersicht</p>
    <div class="Waffenliste">
        <p><?php $newClass->ThemenGegnerAnzeigen($connection, $_POST["themenname"]) ?></p>
    </div>
</div>

</html>