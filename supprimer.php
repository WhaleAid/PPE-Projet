<?php
session_start();
$user = $_SESSION['USER'];
$errmsg_arr = array();
$errflag = false;
$dbhost = "localhost";
$dbname = "gsb_frais";
$dbuser = "root";
$dbpass = "root";
$conn = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);

$sql = "DELETE FROM lignefraishorsfrais WHERE login= ?";
	$result = $conn->prepare($sqlid);
	$result ->bindParam(1, $user);
	$result->execute();

header('Location: consulterfrais.php');
exit;
?>