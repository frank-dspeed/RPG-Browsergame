<?php
include("funktionen.php");
?>

<html>
    <head>
        <title>Spielergegner</title>
        <meta charset="utf-8" />
        <link rel="stylesheet" type="text/css" href="rpgstyle.css" />
    </head>
    <a href="/rpg.php"><img src="Bilder/Zurückbutton.png" width="100" height="100" /></a>

<div class="SpielerlisteContainer">
            <p>Spielerübersicht</p>
            <div class="Spielerliste">
                <p><?php $newClass->AlleSpielerKampf($connection)?></p>
            </div>
        </div>