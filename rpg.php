<?php
include("funktionen.php");
?>

<html>
    <head>
        <title>RPG</title>
        <meta charset="utf-8" />
        <link rel="stylesheet" type="text/css" href="rpgstyle.css" />
    </head>
    <div class="Menü">
        <div class="SpielerInfoContainer">
            <div class="Spieler">
			    <?php $newClass->AdminEinblenden($connection,$_SESSION["Spieler"]);?><br>
                <span><?php  echo $_SESSION["Spieler"];?></span>
            </div>
            <div class="Spielerbilder">
                <img class="Spielerbildrahmen" src="/Bilder/Rahmen.png" width="50" height="50" />
                <img class="Spielerbild" src="<?php echo $newClass->SpielerLesen($connection,"spielerbildpfad",$_SESSION["Spieler"]) ?>" width="50" height="50">
            </div>
            <div class="Level">
                <div class="Bildupload">
                    <form id="inputform" action="./funktionen.php" method="POST" enctype="multipart/form-data">
                        <!-- 3,5 mb maximal dateigröße -->
                        <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
                        <input id="inputfile" type="file" name="bildhochladen" /><br />
                        <input id="subbtn" type="submit" value="Bild hochladen" />
                        <input id="loeschen" type="submit" name="bildloeschen" value="Bild entfernen" />
                    </form>
                </div>
                Level
                <?php echo $newClass->SpielerLesen($connection,"lvl",$_SESSION["Spieler"]) ?>
            </div>
            <div class="Erfahrung">
                Erfahrung
                <?php echo $newClass->SpielerLesen($connection,"erfahrung",$_SESSION["Spieler"])?> von
                <?php $newClass->MAXErfahrung($connection,$_SESSION["Spieler"])?>
            </div>
            <div class="Leben">
                <img class="Ruestungsbild" src="<?php $newClass->BildLesen($connection, "ruestungsbildpfad","ruestung","ruestungsid", $_SESSION["Spieler"]);?>" width="50" height="50">
                <div class="Ruestungsname"><?php $newClass->BildLesen($connection, "ruestungsname","ruestung","ruestungsid", $_SESSION["Spieler"]);?></div>
                <p>
                    Leben
                    <?php echo $newClass->SpielerLesen($connection,"leben",$_SESSION["Spieler"]) ?>
					von 
					<?php echo $newClass->SpielerLesen($connection,"maxleben",$_SESSION["Spieler"]) ?>
                </p>
            </div>
            <div class="Waffe">
                <img class="Waffenbild" src="<?php $newClass->BildLesen($connection, "waffenbildpfad","waffen","waffenid", $_SESSION["Spieler"]);?>" width="50" height="50">
                <div class="Waffenname"><?php $newClass->BildLesen($connection, "waffenname","waffen","waffenid", $_SESSION["Spieler"]);?></div>
                <p>
                    Angriff
                    <?php echo $newClass->SpielerLesen($connection,"angriff",$_SESSION["Spieler"]) ?>
                </p>
            </div>
            <div class="Geld">
                <p>
                    Geld
                    <?php echo $newClass->SpielerLesen($connection,"geld",$_SESSION["Spieler"])?> <img class="Geldbild" src="/Bilder/Geld.png" width="20" height="20" />
                </p>
            </div>
            <div class="form">
                <form action="/rpg.php" method="POST">
                    <input type="submit" name="action" value="Ausloggen" />
                </form>
            </div>
        </div>

        <div class="SpielerlisteContainer">
            <p>Spielerübersicht</p>
            <div class="Spielerliste">
                <p><?php $newClass->AlleSpielerLesen($connection)?></p>
            </div>
        </div>

        <div class="MarktplatzContainer">
            <p>Marktplatz</p>
            <a href="/marktplatz.php"><img src="Bilder/Marktplatzbutton.png" width="100" height="50" /></a>
        </div>

        <div class="KampfContainer">
            <p>Kampf</p><br><br>
            <p>PVE</p><br>
            <a href="/themen.php"><img src="Bilder/PVE.png" width="100" height="50" /></a><br><br>
            <p>PVP</p><br>
            <a href="/spielergegner.php"><img src="Bilder/PVP.png" width="100" height="50" /></a>
        </div>
    </div>
</html>
