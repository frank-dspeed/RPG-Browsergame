<?php
include("funktionen.php");	
if(!isset($_SESSION["Spieler"]))
{header('location: rpglogin.php');}
?>

<html>
    <head>
        <title>Marktplatz</title>
        <meta charset="utf-8" />
        <link rel="stylesheet" type="text/css" href="rpgstyle.css" />
    </head>

    <a href="/rpg.php"><img src="Bilder/Zurückbutton.png" width="100" height="100" /></a>

    <div class="Überschrift">
        <p>Marktplatz</p>
    </div>

    <div class="SpielerInfoContainer">
        <div class="Spieler">
            <span><?php  echo $_SESSION["Spieler"];?></span>
        </div>
        <div class="Spielerbilder">
            <img class="Spielerbildrahmen" src="/Bilder/Rahmen.png" width="50" height="50" />
            <img class="Spielerbild" src="<?php echo $newClass->SpielerLesen($connection,"spielerbildpfad",$_SESSION["Spieler"]) ?>" width="50" height="50">
        </div>
        <div class="lvl">
            lvl
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
                <?php echo $newClass->SpielerLesen($connection,"geld",$_SESSION["Spieler"]) ?>
            </p>
        </div>
        <img class="Geldbild" src="/Bilder/Geld.png" width="20" height="20" />
        <div class="form">
            <form action="/rpg.php" method="POST">
                <input type="submit" name="action" value="Ausloggen" />
            </form>
        </div>
    </div>

    <div class="WaffenContainer">
        <p>Waffenschmied</p>
        <div class="Waffenliste">
            <p><?php $newClass->AlleWaffenLesen($connection)?></p>
        </div>
    </div>

    <div class="RuestungsContainer">
        <p>Rüstungsschmied</p>
        <div class="Ruestungsliste">
            <p><?php $newClass->AlleRüstungenLesen($connection)?></p>
        </div>
    </div>
	
	    <div class="HeilstubeContainer">
        <p>Heilstube</p>
        <div class="Heiltrankliste">
            <p><?php $newClass->AlleTraenkeLesen($connection)?></p>
        </div>
    </div>
</html>
