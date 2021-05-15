<?php
session_start();
$user = $_SESSION['USER'];
$errmsg_arr = array();
$errflag = false;
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
	$libelle = $_POST['libelle'];
    $date = $_POST['date'];  
    $montant = $_POST['mtn'];
	// set the PDO error mode to exception
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "INSERT INTO lignefraishorsforfait (idVisiteur, mois, libelle, date, montant) VALUES ('$id', '$mois', '$libelle', '$date', '$montant');";
  if($conn->exec($sql)){
	$feedback = '<h4 style="color : green; margin : auto">Insertion prise en compte</h4>';
  } else {
	$feedback = '<h4 style="color : red; margin : auto">Prolème d\'insertion</h4>';
  }
  header('location: home-horsforfait.php?feedback=' . $feedback);
