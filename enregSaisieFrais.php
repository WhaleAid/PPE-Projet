<?php
session_start();
$dbhost = "localhost";
$dbname = "gsb_frais";
$dbuser = "root";
$dbpass = "root";
$conn = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = "INSERT INTO fichefrais (id, nom, prenom, login, email, mdp, adresse, cp, ville, dateEmbauche) VALUES ('$id', '$nom', '$prenom', '$identifiant', '$email', '$pass', '$adresse', '$cp', '$ville', curdate())";
  // use exec() because no results are returned
 $conn->exec($sql);
?>