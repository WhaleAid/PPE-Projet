<?php
session_start();
$user = $_SESSION['USER'];
$errmsg_arr = array();
$errflag = false;
$insert = 1;
$exists = 0;
// configuration
$dbhost = "localhost";
$dbname = "gsb_frais";
$dbuser = "root";
$dbpass = "root";
$conn = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);

// public function getFraisId(){
// 	$requetePrepare = pdoGsb::$monPdo->prepare(
// 		'SELECT fraisforfait.id as idfrais'
// 		'From fraisforfait ORDER BY fraisforfait.id'
// 	);
// 	$requetePrepare->execute()
// 	return $requestPrep->fetchAll();
// }

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
		foreach ($result4 as $util) {
			$id = $util['id'];
		}
	} else {
		$id = $usrconcerne['id'];
	}
}


$sql2 = "SELECT * FROM fraisforfait";
$result2 = $conn->prepare($sql2);
$result2->execute();
$rows2 = $result2->fetchAll();

$query2 = "SELECT DISTINCT mois FROM lignefraisforfait f JOIN visiteur v ON f.idVisiteur = v.id WHERE login = '$user'";
$sth2 = $conn->prepare($query2);
$sth2->execute();
$result2 = $sth2->fetchAll();
foreach ($result2 as $month) {
	$moisFiche[] = $month['mois'];
}

$mois = $_POST['mois'];
$etape = $_POST['etape'];
$km = $_POST['km'];
$nuite = $_POST['nuite'];
$repas = $_POST['repas'];
// set the PDO error mode to exception

$query1 = "SELECT DISTINCT idEtat FROM fichefrais f JOIN visiteur v ON f.idVisiteur = v.id JOIN lignefraisforfait l ON l.idVisiteur = v.id WHERE login = '$user' AND mois ='$mois'";
$sth1 = $conn->prepare($query1);
$sth1->execute();
$result1 = $sth1->fetchAll();

foreach ($result1 as $etat) {
	if ($etat['idEtat'] != 'CR') {
		$insert = 0;
	}
}
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if ($insert == 1) {
	foreach ($moisFiche as $monthfiche) {
		if ($monthfiche == $mois) {
			$exists = 1;
		}
	}
	if ($exists == 0) {
		$sql = "INSERT INTO lignefraisforfait (idVisiteur, mois, idFraisForfait, quantite) VALUES ('$id', '$mois', 'ETP', '$etape');
  		INSERT INTO lignefraisforfait (idVisiteur, mois, idFraisForfait, quantite) VALUES ('$id', '$mois', 'KM', '$km');
  		INSERT INTO lignefraisforfait (idVisiteur, mois, idFraisForfait, quantite) VALUES ('$id', '$mois', 'NUI', '$nuite');
  		INSERT INTO lignefraisforfait (idVisiteur, mois, idFraisForfait, quantite) VALUES ('$id', '$mois', 'REP', '$repas');";
		$feedback = '<h4 style="color : green; margin : auto">Insertion prise en compte</h4>';
		$conn->exec($sql);
	} else {
		$sql = "UPDATE lignefraisforfait SET quantite = $etape WHERE idVisiteur = '$id' AND mois = $mois AND idFraisForfait = 'ETP';
		UPDATE lignefraisforfait SET quantite = $km WHERE idVisiteur = '$id' AND mois = $mois AND idFraisForfait = 'KM';
		UPDATE lignefraisforfait SET quantite = $nuite WHERE idVisiteur = '$id' AND mois = $mois AND idFraisForfait = 'NUI';
		UPDATE lignefraisforfait SET quantite = $repas WHERE idVisiteur = '$id' AND mois = $mois AND idFraisForfait = 'REP';";
		$feedback = '<h4 style="color : green; margin : auto">Modifications prises en compte</h4>';
		$conn->exec($sql);
	}
} else {
	$feedback = '<h4 style="color : red; margin : auto">Cette fiche n\'est plus modifiable</h4>';
}
header('location: home-ligne.php?feedback=' . $feedback);
exit();
