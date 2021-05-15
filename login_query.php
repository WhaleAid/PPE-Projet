<?php
include('functions.php');

session_start();
$errmsg_arr = array();
$errflag = false;
// configuration
$dbhost = "localhost";
$dbname = "gsb_frais";
$dbuser = "root";
$dbpass = "root";
// database connection
$conn = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbuser,$dbpass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// query
if(isset($_POST['identifiant'])) {
	$user = $_POST['identifiant'];
	$password = $_POST['pwd'];

	$sql = "SELECT * FROM visiteur WHERE login= ?";
	$result = $conn->prepare($sql);
	$result->bindParam(1, $user);
	$result->execute();

	$rows = $result->fetch();

	if(password_verify($password, $rows['mdp'])) {
		$_SESSION['USER'] = $user;
		header("location: home.php");
	}else{
		$errmsg_arr[] = 'Identifiant et mot de passe non trouvés';
		$errflag = true;
	}
if($errflag) {
	$_SESSION['ERRMSG_ARR'] = $errmsg_arr;
	session_write_close();
	header("location: login.php");
	exit();
	}
}
?>