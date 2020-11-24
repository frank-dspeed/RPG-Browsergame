<?php
include("funktionen.php");
?>
<html>
    <head>
        <title>PVE</title>
        <meta charset="utf-8" />
        <link rel="stylesheet" type="text/css" href="rpgstyle.css" />
        <script type="text/javascript" src="pvekampf.js"></script> 
    </head>

    <body onload="DatenLaden();">

    <div class="RahmenContainer">
    <div class="SpielerRahmenLinks">
    <div class="SpielerBildRahmenLinks">  
    <img id="spielerbild" class="SpielerBildLinks" src="Spieleravatare/Ritter.png" alt="Bild links">
    <div id="RuestungRahmenLinks">
      <img id="spielerruestungsbild"class="RuestungsBilder" src="/Ruestungsbilder/Ruestung_Default.png">
      <p id="spielerruestungswert" class="ruestungswert1">20</p>
    </div>
    <div id="LebenRahmenMitte">
    <img class="LebensBild" src="/Bilder/Grau.png">
    <div class="lebentext1">
    <p id="spielerleben"class="leben1">10</p>
    <p class="von">&nbspvon&nbsp</p>
    <p id="spielermaxleben" class="maxleben1">100</p>
    </div>
    </div>
    <div id="AngriffRahmenLinks">
    <img id="spielerwaffenbild" class="AngriffBild" src="/Waffenbilder/Waffe_Default.png">
    <p id="spielerangriff" class="angriffswert1">10</p>
    </div>
    </div>
    <p id="spielername"class="spielername1">Spieler1</p>
    </div>
<div class="SpielerRahmenRechts">
    <div class="SpielerBildRahmenRechts">  
    <img id="gegnerbild" class="SpielerBildRechts" src="Gegneravatare/Ork.png" alt="Bild rechts">
    <div id="LebenRahmenRechts">
    <img class="LebensBild" src="/Bilder/Grau.png">
    <div class="lebentext2">
    <p id="gegnerleben" class="leben2">5</p>
</div>
    </div>
    <div id="AngriffRahmenGegnerLinks">
    <img id="test" class="AngriffBild" src="/Waffenbilder/Waffe_Default.png">
    <p id="gegnerschaden" class="angriffswert2">5</p>
    </div>
    </div>
    <p id="gegnername" class="spielername2">Gegner</p>
    </div>
</div>

<div  id="Kampfergebnisse" class="KampfergebnisseContainer">
<p id="kampfgewinner"></p>
<p id="kampfgeld"></p>
<p id="kampferfahrung"></p>
<p id="kampfgeldverlust"></p>
<p id="lvlup"></p>
</div>
<button id="kampfstartenbutton" type="button" class="KampfStarten" onclick="KampfStarten(SpielerDatenLaden(),GegnerDatenLaden())">Kampf Starten</button><br><br>
<a href="/themen.php"><img src="Bilder/Zurückbutton.png" width="100" height="100" /></a>

<script type="text/javascript">
//X
function DatenLaden()
{

//json_encode($str, JSON_UNESCAPED_SLASHES)
const spielerdaten = <?php json_encode($newClass->JSONStringSpieler($connection,$_SESSION["Spieler"]),JSON_UNESCAPED_SLASHES);?>;
const gegnerdaten =  <?php json_encode($newClass->JSONStringGegner($connection,$_POST["gegnerid"]),JSON_UNESCAPED_SLASHES);?>;

//Spieler Daten in Frontend laden
let spielerruestungswert =  spielerdaten[0]["ruestungswert"];
let spielerruestungsbildpfad = spielerdaten[0]["ruestungsbildpfad"];
let spielerleben = spielerdaten[0]["leben"];
let spielermaxleben = spielerdaten[0]["maxleben"];
let spielerschaden = spielerdaten[0]["angriff"]+spielerdaten[0]["waffenwert"];
let spielerwaffenbild = spielerdaten[0]["waffenbildpfad"];
let spielerbild = spielerdaten[0]["spielerbildpfad"];
let spielername = spielerdaten[0]["spielername"];

let uispielerruestungswert =  document.getElementById('spielerruestungswert');
let uispielerruestungsbild = document.getElementById('spielerruestungsbild');
let uispielerleben = document.getElementById('spielerleben');
let uispielermaxleben = document.getElementById('spielermaxleben');
let uispielerangriff = document.getElementById('spielerangriff');
let uispielerwaffenbild = document.getElementById('spielerwaffenbild');
let uispielerbild = document.getElementById('spielerbild');
let uispielername = document.getElementById('spielername');

uispielerruestungswert.innerText = spielerruestungswert;
uispielerruestungsbild.src = spielerruestungsbildpfad;
uispielerleben.innerText = spielerleben;
uispielermaxleben.innerText = spielermaxleben;
uispielerangriff.innerText = spielerschaden;
uispielerwaffenbild.src = spielerwaffenbild;
uispielerbild.src = spielerbild;
uispielername.innerText = spielername;

//Gegner Daten in Frontend laden

let gegnerleben = gegnerdaten[0]["leben"];
let gegnerschaden = gegnerdaten[0]["angriff"];
let gegnername = gegnerdaten[0]["gegnername"];
let gegnerbild = gegnerdaten[0]["gegnerbildpfad"];

let uigegnerleben = document.getElementById('gegnerleben');
let uigegnerschaden = document.getElementById('gegnerschaden');
let uigegnername = document.getElementById('gegnername');
let uigegnerbild = document.getElementById('gegnerbild');

uigegnerleben.innerText = gegnerleben;
uigegnerschaden.innerText = gegnerschaden;
uigegnername.innerText = gegnername;
uigegnerbild.src = gegnerbild;

}
//X
function SpielerDatenLaden()
{
  const spielerdaten = <?php json_encode($newClass->JSONStringSpieler($connection,$_SESSION["Spieler"]),JSON_UNESCAPED_SLASHES);?>;
  let spielerlvl = spielerdaten[0]["lvl"];
  let spielererfahrung = spielerdaten[0]["erfahrung"];
  let spielerruestungswert =  spielerdaten[0]["ruestungswert"];
  let spielerruestungsbildpfad = spielerdaten[0]["ruestungsbildpfad"];
  let spielerleben = spielerdaten[0]["leben"];
  let uispielerruestungswert =  document.getElementById('spielerruestungswert');
  let uispielerleben = document.getElementById('spielerleben');
  let spielermaxleben = spielerdaten[0]["maxleben"];
  let spielerschaden = spielerdaten[0]["angriff"]+spielerdaten[0]["waffenwert"];
  let spielerangriffswert = spielerdaten[0]["angriff"];
  let spielerwaffenbild = spielerdaten[0]["waffenbildpfad"];
  let spielerbild = spielerdaten[0]["spielerbildpfad"];
  let spielername = spielerdaten[0]["spielername"];
  let spielergeld = spielerdaten[0]["geld"];
  let Spieler = new Avatar(spielername,spielerlvl,spielerschaden,spielerruestungswert,spielerleben,uispielerruestungswert
  ,uispielerleben,"links",spielergeld,spielererfahrung,spielerangriffswert,spielermaxleben);
  return Spieler;
}

 //X 
function GegnerDatenLaden()
{
  const gegnerdaten =  <?php json_encode($newClass->JSONStringGegner($connection,$_POST["gegnerid"]),JSON_UNESCAPED_SLASHES);?>;
  let gegnerlvl = gegnerdaten[0]["lvl"];
  let gegnerleben = gegnerdaten[0]["leben"];
  let uigegnerleben = document.getElementById('gegnerleben');
  let gegnerschaden = gegnerdaten[0]["angriff"];
  let gegnername = gegnerdaten[0]["gegnername"];
  let gegnerbild = gegnerdaten[0]["gegnerbildpfad"];
  let gegnergeld = gegnerdaten[0]["geld"];
  let Gegner = new Avatar(gegnername,gegnerlvl,gegnerschaden,0,gegnerleben,uigegnerleben,uigegnerleben,
  "rechts",gegnergeld,GegnerErfahrungBerechnen(gegnerlvl,gegnerleben,gegnerschaden));
  return Gegner;
}

//X
function VersendenVorbereiten(Spieler,Gegner)
{
  const spielerdaten = <?php json_encode($newClass->JSONStringSpieler($connection,$_SESSION["Spieler"]),JSON_UNESCAPED_SLASHES);?>;
  const gegnerdaten =  <?php json_encode($newClass->JSONStringGegner($connection,$_POST["gegnerid"]),JSON_UNESCAPED_SLASHES);?>;

  spielerdaten[0]["leben"] = Spieler.leben;
  spielerdaten[0]["erfahrung"] = Spieler.erfahrung;
  spielerdaten[0]["geld"] = Spieler.geld;
  spielerdaten[0]["lvl"] = Spieler.lvl;
  spielerdaten[0]["angriff"] = Spieler.angriffswert;
  spielerdaten[0]["maxleben"] = Spieler.maxleben;

  gegnerdaten[0]["leben"] = Gegner.leben;
  gegnerdaten[0]["geld"] = Gegner.geld;

  const spielerdatennachkampf =JSON.stringify(spielerdaten);
  const gegnerdatennachkampf = JSON.stringify(gegnerdaten);

  KampfergebnisseSenden(spielerdatennachkampf,gegnerdatennachkampf);
}


// Spieler und Gegner für den Kampf sperren
function SpielerSperren() 
{
  const spielerdaten = <?php json_encode($newClass->JSONStringSpieler($connection,$_SESSION["Spieler"]),JSON_UNESCAPED_SLASHES);?>;
  const gegnerdaten =  <?php json_encode($newClass->JSONStringGegner($connection,$_POST["gegnerid"]),JSON_UNESCAPED_SLASHES);?>;
  const spielersperre =JSON.stringify(spielerdaten);
  const gegnersperre = JSON.stringify(gegnerdaten);

  var xhttp = new XMLHttpRequest();
  xhttp.open("POST", "pve.php", true);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhttp.send("spielersperren="+spielersperre+"&gegnersperren="+gegnersperre+"");
}


//Für die Rückgabe nach dem Kampf  
function KampfergebnisseSenden(spielerdatennachkampf,gegnerdatennachkampf) {
  var xhttp = new XMLHttpRequest();
  xhttp.open("POST", "pve.php", true);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhttp.send("spielerdaten="+spielerdatennachkampf+"&gegnerdaten="+gegnerdatennachkampf+"");
 // window.location.href = '/themen.php';
}
</script>


</html>