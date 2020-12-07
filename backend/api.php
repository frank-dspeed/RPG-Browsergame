<?php
if(file_exists('../logindata.php')) {
	include("logindata.php");
} else {
   $host = $_ENV['DB_HOST'];
   $user = $_ENV['DB_USER'];
   $password = $_ENV['DB_PASSWORD'];
   $db = $_ENV['DB_NAME'];
}
   $connection = new mysqli($host,$user,$password,$db);
   if($connection)

session_start();

// ======== Flow ====== //
// has session?
// use db
// do request

$newClass = new DBAktionen();

if (!isset($_SESSION["Spieler"])) {
	// Einloggen---------------------------------------------------------------------------------------------
	if (isset($_POST["action"]) && $_POST["action"] == "Einloggen") {
		$player = $_POST["bname"];
		$passwort = $_POST["pw"];
		$login = $connection->prepare("SELECT benutzername, passwort FROM konto WHERE benutzername = ?");
		$login->bind_param("s", $player);
		$login->execute();
		$result = $login->get_result();
		$row = mysqli_fetch_row($result);

		if ($player == $row[0] && password_verify($passwort, $row[1])) {
			//echo "Benutzer eingeloggt";
			$_SESSION["Spieler"] = $player;
			$newClass->Spielersperren($connection, 0, $player);
		} else {
			header('location: index.php');
		}

		$login->close();
	}
}

// Registrieren---------------------------------------------------------------------------------------------

if (isset($_POST["action"]) && $_POST["action"] == "Registrieren") {
	$passworthash = password_hash($_POST["pw"], PASSWORD_DEFAULT);
	$select = $connection->prepare("SELECT benutzername FROM konto WHERE benutzername = ?");
	$select->bind_param("s", $_POST["bname"]);
	$select->execute();
	$result = $select->get_result();
	if ($result->num_rows == 0) {
		// Konto anlegen
		$insert = $connection->prepare("INSERT INTO konto (benutzername, passwort) VALUES (?, ?)");
		$insert->bind_param("ss", $_POST["bname"], $passworthash);
		$insert->execute();
		$insert->close();
		//Spieler anlegen
		$insertspieler = $connection->prepare("INSERT INTO spieler (spielername, lvl, erfahrung, 
		geld,leben,maxleben, angriff, waffenid, ruestungsid, spielerbildpfad, rechte,gesperrt) 
		VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
		$insertspieler->bind_param("siiiiiiiissi", $player, $lvl, $erfahrung, $geld, $leben, $maxleben, $angriff, $leer, $leer, $pfad, $rechte, $sperre);
		$player = $_POST["bname"];
		$lvl = 1;
		$erfahrung = 0;
		$geld = 0;
		$leben = 3;
		$maxleben = 3;
		$angriff = 1;
		$leer = 0;
		$pfad = "/Spieleravatare/Default.png";
		$rechte = "Spieler";
		$sperre = 0;
		$insertspieler->execute();
		$insertspieler->close();
		$_SESSION["Spieler"] = $_POST["bname"];
		//header('location: index.php');
	} else {
		//Userausgabe Benutzer bereits vorhanden 
		echo "Benutzer bereits vorhanden";
	};
}

if (!isset($_SESSION["Spieler"])) {
	header('location: index.php');
	die();
}

// Ausloggen ---------------------------------------------------------------------------------------------

if (isset($_POST["action"]) && $_POST["action"] == "Ausloggen") {
	session_destroy();
	header('location: index.php');
	die();
}


//Bild hochladen
if (isset($_FILES["bildhochladen"]) && $_FILES["bildhochladen"]["size"] > 0) {
	$uploaddir = './Spieleravatare/';
	$filename = $_SESSION["Spieler"];
	$fileextension = ".png";
	move_uploaded_file($_FILES["bildhochladen"]['tmp_name'], $uploaddir . $filename . $fileextension);

	$newClass->Bildaendern($connection, "/Spieleravatare/" . $filename . $fileextension, $_SESSION["Spieler"]);
	header('location: rpg.php');
}

//Bild löschen  ---------------------------------------------------------------------------------------------
if (isset($_POST["bildloeschen"])) {
	$file = "./Spieleravatare/" . $_SESSION["Spieler"] . ".png";
	if (file_exists($file)) {
		unlink($file);
	}
	$newClass->Bildaendern($connection, "/Spieleravatare/Default.png", $_SESSION["Spieler"]);
	header('location: rpg.php');
}

//Redirekt wenn kein Bild ausgewählt  ---------------------------------------------------------------------------------------------
if (isset($_FILES["bildhochladen"]) && $_FILES["bildhochladen"]["size"] == 0) {
	header('location: rpg.php');
}

//Waffe kaufen  ---------------------------------------------------------------------------------------------
if (isset($_POST["waffenid"])) {
	$newClass->Kaufen($connection, "waffen", "waffenid", $_POST["waffenid"]);
}

//Rüstung kaufen  ---------------------------------------------------------------------------------------------
if (isset($_POST["ruestungsid"])) {
	$newClass->Kaufen($connection, "ruestung", "ruestungsid", $_POST["ruestungsid"]);
}

//Trank kaufen
if (isset($_POST["trankid"])) {
	$newClass->TränkeKaufen($connection, $_POST["trankid"]);
}

//Spieler und Gegner sperren PvE
if (isset($_POST["spielersperren"]) && isset($_POST["gegnersperren"])) {
	$spieler = (json_decode($_POST["spielersperren"], true));
	$gegner = (json_decode($_POST["gegnersperren"], true));
	$spielername = json_encode($spieler[0]["spielername"]);
	$spielername = str_replace('"', "", $spielername);
	$gegnerid =  json_encode($gegner[0]["gegnerid"]);
	$sperre = 1;
	//Spieler und Gegner sperren
	$newClass->Spielersperren($connection, $sperre, $spielername);
	$newClass->Gegnersperren($connection, $sperre, $gegnerid);
}

//Spieler und SpielerGegner sperren PVP
if (isset($_POST["spielersperren"]) && isset($_POST["spielergegnersperren"])) {
	$spieler = (json_decode($_POST["spielersperren"], true));
	$spielergegner  = (json_decode($_POST["spielergegnersperren"], true));
	$spielername = json_encode($spieler[0]["spielername"]);
	$spielergegnername = json_encode($spielergegner[0]["spielername"]);
	$spielername = str_replace('"', "", $spielername);
	$spielergegnername = str_replace('"', "", $spielergegnername);
	$sperre = 1;
	//Beide Spieler sperren
	$newClass->Spielersperren($connection, $sperre, $spielername);
	$newClass->Spielersperren($connection, $sperre, $spielergegnername);
}

//Spieler und SpielerGegnerdaten nach dem Kampf annhemen PVP
if (isset($_POST["spielerdaten"]) && isset($_POST["spielergegnerdaten"])) {
	$spieler = (json_decode($_POST["spielerdaten"], true));
	$lvl = json_encode($spieler[0]["lvl"]);
	$erfahrung = json_encode($spieler[0]["erfahrung"]);
	$leben = json_encode($spieler[0]["leben"]);
	$geld = json_encode($spieler[0]["geld"]);
	$spielername = json_encode($spieler[0]["spielername"]);
	$spielername = str_replace('"', "", $spielername);
	$spielerangriff = json_encode($spieler[0]["angriff"]);
	$spielermaxleben = json_encode($spieler[0]["maxleben"]);
	$sperre = 0;
	$newClass->SpielerStatsSchreiben($connection, $lvl, $erfahrung, $geld, $leben, $spielerangriff, $spielermaxleben, $spielername);

	$spielergegner = (json_decode($_POST["spielergegnerdaten"], true));
	$lvl = json_encode($spielergegner[0]["lvl"]);
	$erfahrung = json_encode($spielergegner[0]["erfahrung"]);
	$leben = json_encode($spielergegner[0]["leben"]);
	$geld = json_encode($spielergegner[0]["geld"]);
	$spielergegnername = json_encode($spielergegner[0]["spielername"]);
	$spielergegnername = str_replace('"', "", $spielergegnername);
	$spielergegnerangriff = json_encode($spielergegner[0]["angriff"]);
	$spielergegnermaxleben = json_encode($spielergegner[0]["maxleben"]);
	$sperre = 0;
	$newClass->SpielerStatsSchreiben($connection, $lvl, $erfahrung, $geld, $leben, $spielergegnerangriff, $spielergegnermaxleben, $spielergegnername);

	// Nach Kampf entsperren
	$sperre = 0;
	$newClass->Spielersperren($connection, $sperre, $spielername);
	$newClass->Spielersperren($connection, $sperre, $spielergegnername);
}


//Spieler und Gegnerdaten nach Kampf annehmen PVE
if (isset($_POST["spielerdaten"]) && isset($_POST["gegnerdaten"])) {
	//Spieler
	$spieler = (json_decode($_POST["spielerdaten"], true));
	$lvl = json_encode($spieler[0]["lvl"]);
	$erfahrung = json_encode($spieler[0]["erfahrung"]);
	$leben = json_encode($spieler[0]["leben"]);
	$geld = json_encode($spieler[0]["geld"]);
	$spielername = json_encode($spieler[0]["spielername"]);
	$spielername = str_replace('"', "", $spielername);
	$spielerangriff = json_encode($spieler[0]["angriff"]);
	$spielermaxleben = json_encode($spieler[0]["maxleben"]);
	$sperre = 0;
	$newClass->SpielerStatsSchreiben($connection, $lvl, $erfahrung, $geld, $leben, $spielerangriff, $spielermaxleben, $spielername);

	//Gegner
	$gegner = (json_decode($_POST["gegnerdaten"], true));
	$gegnerid =  json_encode($gegner[0]["gegnerid"]);
	$gegnerleben = json_encode($gegner[0]["leben"]);
	$gegnergeld =  json_encode($gegner[0]["geld"]);
	//if($gegnerleben > 0)
	{
		$newClass->GegnerStatsSchreiben($connection, $gegnerleben, $gegnergeld, $gegnerid);
	}
	// else {$newClass->Gegnerloeschen($connection,$gegnerid);}

	//Spieler und Gegner entsperren
	$newClass->Spielersperren($connection, $sperre, $spielername);
	$newClass->Gegnersperren($connection, $sperre, $gegnerid);
}

// admin.php ----------------------------------------------------------------------------------------------------

// Thema
if (isset($_POST["themaspeichern"])) {
	$insert = $connection->prepare("INSERT INTO themen (themenname, themenbildpfad) VALUES (?,?)");
	$insert->bind_param("ss", $thema, $themenbildpfad);
	$thema = $_POST["thema"];
	$themenbildpfad = "/Themenbilder/" . $thema . date("Ymd") . time() . ".png";
	$insert->execute();
	$insert->close();
	$uploaddir = './Themenbilder/';
	$filename = $thema . date("Ymd") . time();
	$fileextension = ".png";
	move_uploaded_file($_FILES["themabildhochladen"]['tmp_name'], $uploaddir . $filename . $fileextension);
}
//Gegner
if (isset($_POST["gegnerhochladen"])) {
	$insert = $connection->prepare("INSERT INTO gegner (gegnername, lvl,leben,angriff,geld,gegnerbildpfad,thema,gesperrt) VALUES (?,?,?,?,?,?,?,?)");
	$insert->bind_param("siiiissi", $gegnername, $lvl, $leben, $angriff, $geld, $pfad, $thema, $sperre);
	$gegnername = $_POST["gegnername"];
	$lvl = $_POST["lvl"];
	$leben = $_POST["leben"];
	$angriff = $_POST["angriff"];
	$geld = $_POST["geld"];
	$pfad = "/Gegneravatare/" . $gegnername . "" . date("Ymd") . time() . ".png";
	$thema = $_POST["thema"];
	$sperre = 0;
	$insert->execute();
	$insert->close();
	$uploaddir = './Gegneravatare/';
	$filename = $gegnername . date("Ymd") . time();
	$fileextension = ".png";
	move_uploaded_file($_FILES["gegnerbildhochladen"]['tmp_name'], $uploaddir . $filename . $fileextension);
}
// Waffen
if (isset($_POST["waffenhochladen"])) {
	$insert = $connection->prepare("INSERT INTO waffen (waffenname, waffenwert,geldwert,waffenbildpfad) VALUES (?,?,?,?)");
	$insert->bind_param("siis", $waffenname, $waffenwert, $geldwert, $pfad);
	$waffenname = $_POST["waffenname"];
	$waffenwert = $_POST["waffenwert"];
	$geldwert = $_POST["waffengeldwert"];
	$pfad = "/Waffenbilder/" . $waffenname . "" . date("Ymd") . time() . ".png";
	$insert->execute();
	$insert->close();
	$uploaddir = './Waffenbilder/';
	$filename = $waffenname . date("Ymd") . time();
	$fileextension = ".png";
	move_uploaded_file($_FILES["waffenbildhochladen"]['tmp_name'], $uploaddir . $filename . $fileextension);
}
// Rüstung
if (isset($_POST["ruestunghochladen"])) {
	$insert = $connection->prepare("INSERT INTO ruestung (ruestungsname, ruestungswert,geldwert,ruestungsbildpfad) VALUES (?,?,?,?)");
	$insert->bind_param("siis", $ruestungsname, $ruestungswert, $geldwert, $pfad);
	$ruestungsname = $_POST["ruestungsname"];
	$ruestungswert = $_POST["ruestungswert"];
	$geldwert = $_POST["ruestungsgeldwert"];
	$pfad = "/Ruestungsbilder/" . $ruestungsname . "" . date("Ymd") . time() . ".png";
	$insert->execute();
	$insert->close();
	$uploaddir = './Ruestungsbilder/';
	$filename = $ruestungsname . date("Ymd") . time();
	$fileextension = ".png";
	move_uploaded_file($_FILES["ruestungsbildhochladen"]['tmp_name'], $uploaddir . $filename . $fileextension);
}
// Tränke
if (isset($_POST["trankhochladen"])) {
	$insert = $connection->prepare("INSERT INTO traenke (trankname, trankwert,trankwertpermanent,geldwert,trankbildpfad) VALUES (?,?,?,?,?)");
	$insert->bind_param("siiis", $trankname, $trankwert, $trankwertpermanent, $geldwert, $pfad);
	$trankname = $_POST["trankname"];
	$trankwert = $_POST["trankwert"];
	$trankwertpermanent = $_POST["trankwertpermanent"];
	$geldwert = $_POST["trankgeldwert"];
	$pfad = "/Traenkebilder/" . $trankname . "" . date("Ymd") . time() . ".png";
	$insert->execute();
	$insert->close();
	$uploaddir = './Traenkebilder/';
	$filename = $trankname . date("Ymd") . time();
	$fileextension = ".png";
	move_uploaded_file($_FILES["trankbildhochladen"]['tmp_name'], $uploaddir . $filename . $fileextension);
}

class DBAktionen {

	// Admin Link einblenden
	function AdminEinblenden($connection, $player)	{
		$admin = $this->SpielerLesen($connection, "Rechte", $_SESSION["Spieler"]);
		if ($admin == "Admin") {
            //echo '<a href="admin.php"><button>Admin</button></a>';
            echo json_encode($return_arr);
		}
    }
    


	// JSON String des Spielers zurückgeben
	function JSONStringSpieler($connection, $player) {
		$return_arr = array();
		$select = $connection->prepare("SELECT * FROM spieler WHERE spielername = ?");
		$select->bind_param("s", $player);
		$select->execute();
		$result = $select->get_result();

		while ($row = $result->fetch_array()) {
			$row_array['spielername'] = $row['spielername'];
			$row_array['lvl'] = $row['lvl'];
			$row_array['erfahrung'] = $row['erfahrung'];
			$row_array['geld'] = $row['geld'];
			$row_array['leben'] = $row['leben'];
			$row_array['maxleben'] = $row['maxleben'];
			$row_array['angriff'] = $row['angriff'];
			$row_array['waffenwert'] = $this->SpielerWaffenStatsLesen($connection, "waffenwert", $row['waffenid']);
			$row_array['waffenbildpfad'] = $this->SpielerWaffenStatsLesen($connection, "waffenbildpfad", $row['waffenid']);
			$row_array['ruestungswert'] = $this->SpielerRuestungsStatsLesen($connection, "ruestungswert", $row['ruestungsid']);
			$row_array['ruestungsbildpfad'] = $this->SpielerRuestungsStatsLesen($connection, "ruestungsbildpfad", $row['ruestungsid']);
			$row_array['spielerbildpfad'] = $row['spielerbildpfad'];
			array_push($return_arr, $row_array);
		}
		echo json_encode($return_arr);
	}

	// JSON String des Gegners zurückgeben
	function JSONStringGegner($connection, $id)	{
		$return_arr = array();
		$select = $connection->prepare("SELECT * FROM gegner WHERE gegnerid = ?");
		$select->bind_param("i", $id);
		$select->execute();
		$result = $select->get_result();

		while ($row = $result->fetch_array()) {
			$row_array['gegnerid'] = $row['gegnerid'];
			$row_array['gegnername'] = $row['gegnername'];
			$row_array['lvl'] = $row['lvl'];
			$row_array['leben'] = $row['leben'];
			$row_array['angriff'] = $row['angriff'];
			$row_array['geld'] = $row['geld'];
			$row_array['gegnerbildpfad'] = $row['gegnerbildpfad'];
			array_push($return_arr, $row_array);
		}
		echo json_encode($return_arr);
	}

	// Spieler sperren
	function Spielersperren($connection, $sperre, $spielername)	{
		$update = $connection->prepare("UPDATE spieler SET gesperrt=? WHERE spielername=?");
		$update->bind_param("is", $sperre, $spielername);
		$update->execute();
		$update->close();
	}
	// Gegner sperren
	function Gegnersperren($connection, $sperre, $id) {
		$update = $connection->prepare("UPDATE gegner SET gesperrt = ? WHERE gegnerid=?");
		$update->bind_param("ii", $sperre, $id);
		$update->execute();
		$update->close();
	}

	// Zurückgegebene Spieler Kampfstats in die DB schreiben
	function SpielerStatsSchreiben($connection, $lvl, $erfahrung, $geld, $leben, $spielerangriff, $spielermaxleben, $spielername)
	{
		$update = $connection->prepare("UPDATE spieler SET lvl=?,erfahrung=?,geld=?,leben=?,angriff=?,maxleben=? WHERE spielername=?");
		$update->bind_param("iiiiiis", $lvl, $erfahrung, $geld, $leben, $spielerangriff, $spielermaxleben, $spielername);
		$update->execute();
		$update->close();
	}
	// Zurückgegebene Gegner Kampfstats in die DB schreiben
	function GegnerStatsSchreiben($connection, $leben, $geld, $id)
	{
		$update = $connection->prepare("UPDATE gegner SET leben=?,geld=? WHERE gegnerid =?");
		$update->bind_param("iii", $leben, $geld, $id);
		$update->execute();
		$update->close();
	}

	// Gegner löschen
	function Gegnerloeschen($connection, $id)
	{
		$delete = $connection->prepare("DELETE from gegner WHERE gegnerid =?");
		$delete->bind_param("i", $id);
		$delete->execute();
		$delete->close();
	}



	// Spieler---------------------------------------------------------------------------------------------
	function SpielerLesen($connection, $var, $player)
	{
		$select = $connection->prepare("SELECT " . $var . " FROM spieler WHERE spielername = ?");
		$select->bind_param("s", $player);
		$select->execute();
		$result = $select->get_result();
		$row = $result->fetch_assoc();
		return $row["" . $var . ""];
	}
	function SpielerWaffenStatsLesen($connection, $feld, $id)
	{
		$select = $connection->prepare("SELECT " . $feld . " FROM waffen WHERE waffenid = ?");
		$select->bind_param("i", $id);
		$select->execute();
		$result = $select->get_result();
		$row = $result->fetch_assoc();
		return $row["" . $feld . ""];
	}
	function SpielerRuestungsStatsLesen($connection, $feld, $id)
	{
		$select = $connection->prepare("SELECT " . $feld . " FROM ruestung WHERE ruestungsid = ?");
		$select->bind_param("i", $id);
		$select->execute();
		$result = $select->get_result();
		$row = $result->fetch_assoc();
		return $row["" . $feld . ""];
	}
	function MAXErfahrung($connection, $player)
	{
		$select = $connection->prepare("SELECT lvl FROM spieler WHERE spielername = ?");
		$select->bind_param("s", $player);
		$select->execute();
		$result = $select->get_result();
		$row = $result->fetch_assoc();
		echo (($row["lvl"]) * 100);
	}

	function BildLesen($connection, $field, $tabellen, $id, $spielername)
	{
		$select = $connection->prepare("SELECT " . $field . " FROM spieler," . $tabellen . "   
		WHERE spielername = ?
		AND spieler." . $id . " = " . $tabellen . "." . $id . "");
		$select->bind_param("s", $spielername);
		$select->execute();
		$result = $select->get_result();
		if ($result->num_rows == 1) {
			$row = $result->fetch_assoc();
			$idtemp = $row['' . $field . ''];
			echo $idtemp;
		} else {
			if ($field == "waffenbildpfad")
				echo "/Waffenbilder/Waffe_Default.png";
			if ($field == "ruestungsbildpfad")
				echo "/Ruestungsbilder/Ruestung_Default.png";
			if ($field == "waffenname")
				echo "Keine Waffe angelegt";
			if ($field == "ruestungsname")
				echo "Keine Rüstung angelegt";
		}
	}
	function Bildaendern($connection, $pfad, $player)
	{
		$update = $connection->prepare("UPDATE spieler SET spielerbildpfad= ? WHERE spielername= ?");
		$update->bind_param("ss", $pfad, $player);
		$update->execute();
	}

	//Spielerübersicht  ---------------------------------------------------------------------------------------------
	function AlleSpielerLesen($connection)
	{
		$select = $connection->prepare("SELECT spielerbildpfad, spielername, lvl FROM spieler ORDER BY lvl DESC,spielername ASC");
		$select->execute();
		$result = $select->get_result();
		while ($row = $result->fetch_array()) {
			echo ("<img src=" . ($row['spielerbildpfad']) . "> &nbsp
			" . $row['spielername'] . "&nbsp lvl: &nbsp" . $row['lvl'] . "<br>");
		}
	}
	//SpielerKampf PVP ---------------------------------------------------------------------------------------------
	function AlleSpielerKampf($connection)
	{
		$lvl = $this->SpielerLesen($connection, "lvl", $_SESSION["Spieler"]);
		$eigenerSpieler = $_SESSION["Spieler"];
		$sperre = 0;
		$leben = 0;
		$select = $connection->prepare("SELECT spielerbildpfad, spielername, lvl FROM spieler WHERE gesperrt = ? AND lvl >=? AND spielername !=? AND leben >? ORDER BY lvl");
		$select->bind_param("iisi", $sperre, $lvl, $eigenerSpieler, $leben);
		$select->execute();
		$result = $select->get_result();
		while ($row = $result->fetch_array()) {
			echo ("
			    <div class=\"GegenstandEinzeln\">
			    <img src=" . ($row['spielerbildpfad']) . "" . " width=\"100\" height=\"100\">			
				<p>" . $row['spielername'] . "</p><p>Level : " . $row["lvl"] . "</p>
				<form action=\"/pvp.php\" method=\"POST\">
				<input type=\"hidden\" name=\"spielergegner\" value=\"" . $row['spielername'] . "\" />
				<input class=\"Kampfimg\" type=\"image\"src=\"/Bilder/Kampf.png\" width=\"80\" height=\"80\">
				</form>
				</div>");
		}
	}




	//Waffen und Rüstung Kaufen  ---------------------------------------------------------------------------------------------
	function Kaufen($connection, $tabelle, $typid, $id)
	{
		$geld = $this->SpielerLesen($connection, "geld", $_SESSION["Spieler"]);
		$select = $connection->prepare("SELECT geldwert FROM " . $tabelle . " WHERE " . $typid . " =?");
		$select->bind_param("i", $id);
		$select->execute();
		$result = $select->get_result();
		$row = $result->fetch_assoc();
		$kosten = $row["geldwert"];

		if ($geld >= $kosten) {
			$neuesguthaben = $geld - $kosten;
			$update = $connection->prepare("UPDATE spieler SET " . $typid . "=?, geld=? WHERE spielername=?");
			$update->bind_param("iis", $id, $neuesguthaben, $_SESSION["Spieler"]);
			$update->execute();
		}
	}

	//Tränke kaufen
	function TränkeKaufen($connection, $id)
	{
		$geld = $this->SpielerLesen($connection, "geld", $_SESSION["Spieler"]);
		$select = $connection->prepare("SELECT geldwert,trankwert,trankwertpermanent FROM traenke WHERE trankid =?");
		$select->bind_param("i", $id);
		$select->execute();
		$result = $select->get_result();
		$row = $result->fetch_assoc();
		$kosten = $row["geldwert"];
		$trankwert = $row["trankwert"];
		$trankwertpermanent = $row["trankwertpermanent"];

		$leben = $this->SpielerLesen($connection, "leben", $_SESSION["Spieler"]);
		$maxleben = $this->SpielerLesen($connection, "maxleben", $_SESSION["Spieler"]);

		if ($geld >= $kosten) {
			$leben += $trankwert;
			if ($leben > $maxleben) {
				$leben = $maxleben;
			}
			$neuesguthaben = $geld - $kosten;
			$update = $connection->prepare("UPDATE spieler SET leben=?,maxleben=maxleben+?, geld=? WHERE spielername=?");
			$update->bind_param("iiis", $leben, $trankwertpermanent, $neuesguthaben, $_SESSION["Spieler"]);
			$update->execute();
		}
	}
	//WaffenContainer  ---------------------------------------------------------------------------------------------
	function AlleWaffenLesen($connection)
	{
		$select = $connection->prepare("SELECT waffenbildpfad, waffenid, waffenname, waffenwert, geldwert FROM waffen WHERE waffenid !=0");
		$select->execute();
		$result = $select->get_result();
		while ($row = $result->fetch_array()) {
			echo ("
			<div class=\"GegenstandEinzeln\">
			<img src=" . ($row['waffenbildpfad']) . "" . " width=\"100\" height=\"100\"> &nbsp
			<p>
			" . $row['waffenname'] . "</p>&nbsp <p>Angriff: &nbsp" . $row['waffenwert'] . "</p>
			 &nbsp <p>Kostet: &nbsp " . $row['geldwert'] . "<img src=\"Bilder/Geld.png\" width=\"30\" height=\"30\"></p>
			 <form action=\"marktplatz.php\" method=\"POST\">
			 <input type=\"hidden\" name=\"waffenid\" value=\"" . $row['waffenid'] . "\" />
			 <input type=\"image\" src=\"/Bilder/Geldsack.png\" width=\"60\" height=\"60\">
			 </form>
			 </div><br><br>");
		}
	}
	//RüstungsContainer  ---------------------------------------------------------------------------------------------
	function AlleRüstungenLesen($connection)
	{
		$select = $connection->prepare("SELECT ruestungsbildpfad, ruestungsid, ruestungsname, ruestungswert, geldwert FROM ruestung WHERE ruestungsid !=0");
		$select->execute();
		$result = $select->get_result();
		while ($row = $result->fetch_array()) {
			echo ("
			<div class=\"GegenstandEinzeln\">
			<img src=" . ($row['ruestungsbildpfad']) . "" . " width=\"100\" height=\"100\"> &nbsp
			<p>
			 " . $row['ruestungsname'] . "</p>&nbsp <p>Verteidigung: &nbsp" . $row['ruestungswert'] . "</p>
			 &nbsp <p>Kostet: &nbsp " . $row['geldwert'] . "<img src=\"Bilder/Geld.png\" width=\"30\" height=\"30\"></p>
			 <form action=\"marktplatz.php\" method=\"POST\">
			 <input type=\"hidden\" name=\"ruestungsid\" value=\"" . $row['ruestungsid'] . "\" />
			 <input type=\"image\" src=\"/Bilder/Geldsack.png\" width=\"60\" height=\"60\">
			 </form>
			 </div><br><br>");
		}
	}

	//HeilstubenContainer  ---------------------------------------------------------------------------------------------
	function AlleTraenkeLesen($connection)
	{
		$select = $connection->prepare("SELECT trankbildpfad, trankid, trankname, trankwert,trankwertpermanent, geldwert FROM traenke");
		$select->execute();
		$result = $select->get_result();
		while ($row = $result->fetch_array()) {
			if ($row['trankwert'] > 0) {
				$typ = "Heilung";
				$value = $row['trankwert'];
			}
			if ($row['trankwertpermanent'] > 0) {
				$typ = "Permanent";
				$value = $row['trankwertpermanent'];
			}

			echo ("
			<div class=\"GegenstandEinzeln\">
			<img src=" . ($row['trankbildpfad']) . "" . " width=\"100\" height=\"100\">&nbsp
			<p>
			" . $row['trankname'] . "</p>
			 <p>" . $typ . " &nbsp" . $value . "</p>
			 <p>Kostet: &nbsp " . $row['geldwert'] . "<img src=\"Bilder/Geld.png\" width=\"30\" height=\"30\"></p>
			 <form action=\"marktplatz.php\" method=\"POST\">
			 <input type=\"hidden\" name=\"trankid\" value=\"" . $row['trankid'] . "\" />
			 <input type=\"image\" src=\"/Bilder/Geldsack.png\" width=\"60\" height=\"60\">
			 </form>
			 </div><br><br>");
		}
	}

	// admin.php -----------------------------------------------------------------------------------------------
	// Themen auswahl auslesen----------------------------------------------------------------------------------
	function Themenlesen($connection)
	{
		$select = $connection->prepare("SELECT id, themenname from themen");
		$select->execute();
		$result = $select->get_result();
		while ($row = $result->fetch_array()) {
			echo '<option value="' . $row["themenname"] . '">' . $row["themenname"] . '</option>';
		}
	}

	// themen.php
	// Themen anzeigen bei denen Gegner zugewiesen sind
	function ThemenAnzeigen($connection)
	{
		$select = $connection->prepare("SELECT DISTINCT themenname, themenbildpfad from themen INNER JOIN gegner ON  gegner.thema = themen.themenname");
		$select->execute();
		$result = $select->get_result();
		while ($row = $result->fetch_array()) {
			echo ('
			<div class=GegenstandEinzeln>
			<img src=' . $row["themenbildpfad"] . ' width=100 height=100>
			<p>' . $row["themenname"] . '</p>
			<p>Anzahl Gegner</p>
			<p>' . $this->ThemenGegnerAnzahl($connection, $row["themenname"]) . '</p>
			<form action="themengegner.php" method="POST">
			<input type=hidden name="themenname" value="' . $row["themenname"] . '" />
			<input type=image src="/Bilder/Schild.png" width=80 height=80>
			</form>
		    </div>');
		}
	}
	function ThemenGegnerAnzahl($connection, $themenname)
	{
		$sperre = 0;
		$select = $connection->prepare("SELECT COUNT(gegnername)AS anzahl FROM gegner WHERE thema=? AND gesperrt=?");
		$select->bind_param("si", $themenname, $sperre);
		$select->execute();
		$result = $select->get_result();
		$row = $result->fetch_assoc();
		return $row["anzahl"];
	}
	function ThemenGegnerAnzeigen($connection, $themenname)
	{
		$sperre = 0;
		$select2 = $connection->prepare("SELECT gegnername,gegnerid, lvl FROM gegner WHERE thema=? AND leben >0 AND gesperrt=?");
		$select2->bind_param("si", $themenname, $sperre);
		$select2->execute();
		$result2 = $select2->get_result();
		while ($row2 = $result2->fetch_array()) {
			echo
				"
				<div class=GegenstandEinzeln>
				<p>" . $row2["gegnername"] . "</p><p>Level : " . $row2["lvl"] . "</p>
				<form action=\"/pve.php\" method=\"POST\">
				   <input type=\"hidden\" name=\"gegnerid\" value=\"" . $row2['gegnerid'] . "\" />
				   <input class=\"Kampfimg\" type=\"image\"src=\"/Bilder/Kampf.png\" width=\"60\" height=\"60\">
				   </form></p>
				   </div>";
		}
	}
}
