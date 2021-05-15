<?php
session_start();
error_reporting(0);
$user = $_SESSION['USER'];
$errmsg_arr = array();
$errflag = false;
$insert = 1;
// configuration
$dbhost = "localhost";
$dbname = "gsb_frais";
$dbuser = "root";
$dbpass = "root";
$conn = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);

$sql1 = "SELECT * FROM visiteur WHERE login= ?";
$result = $conn->prepare($sql1);
$result->bindParam(1, $user);
$result->execute();
$rows = $result->fetchAll();
foreach ($rows as $usrconcerne) {
	if ($usrconcerne['comptable'] == 1) {
		$user = $_POST['usr'];
		$sql = "SELECT id FROM visiteur WHERE login= ?";
		$sth = $conn->prepare($sql);
		$sth->bindParam(1, $user);
		$sth->execute();
		$result4 = $sth->fetchAll();
		foreach($result4 as $util){
			$id = $util['id'];
		}
	} else {
		$id = $usrconcerne['id'];
	}
}

$mois = $_POST['mois'];
$nbrjust = $_POST['nbjust'];
$date = $_POST['datemodif'];
// set the PDO error mode to exception

$query1 = "SELECT idEtat FROM fichefrais f JOIN visiteur v ON f.idVisiteur = v.id WHERE login = '$user' AND mois ='$mois'";
$sth1 = $conn->prepare($query1);
$sth1->execute();
$result1 = $sth1->fetchAll();
$moisFiche=[];
$query2 = "SELECT mois FROM fichefrais f JOIN visiteur v ON f.idVisiteur = v.id WHERE login = '$user'";
$sth2 = $conn->prepare($query2);
$sth2->execute();
$result2 = $sth2->fetchAll();
foreach ($result2 as $month) {
	array_push($moisFiche, $month['mois']);
}

$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
foreach ($result1 as $etat) {
	if ($etat['idEtat'] != 'CR') {
		$insert = 0;
	}
}
$killswitch = 0;
foreach($moisFiche as $monthfiche){
	if($monthfiche == $mois){
		$killswitch = 1;
	}
}

if ($insert == 1) {
	if ($killswitch == 0) {
		$sql = "INSERT INTO fichefrais (idVisiteur, mois, nbJustificatifs, dateModif, idEtat) VALUES ('$id', '$mois', '$nbrjust', '$date', 'CR')";
		$feedback = '<h4 style="color : green; margin : auto">Insertion prise en compte</h4>';
	} else {
		$sql = "UPDATE fichefrais SET nbJustificatifs = '$nbrjust', dateModif = '$date' WHERE idVisiteur = '$id'";
		$feedback = '<h4 style="color : green; margin : auto">Modification prise en compte</h4>';
	}
	$conn->exec($sql);
	if ($conn) {
	} else {
		$feedback = '<h4 style="color : red; margin : auto">Erreur d\'insertion</h4>';
	}
} else {
	$feedback = '<h4 style="color : red; margin : auto">Cette fiche n\'est plus modifiable</h4>';
}
header('location: home.php?feedback=' . $feedback);
exit();
