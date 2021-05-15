<?php
$dbhost = "localhost";
$dbname = "gsb_frais";
$dbuser = "root";
$dbpass = "root";
$conn = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);

$month = date('m');

$sql = "UPDATE fichefrais SET idEtat = 'CL' WHERE mois < $month AND idEtat = 'CR'";
$conn->exec($sql);
?>