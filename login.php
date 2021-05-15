<?php
session_start();
error_reporting(0);
$feedback = '';
?>
<?php
if (
	isset($_SESSION['ERRMSG_ARR']) &&
	is_array($_SESSION['ERRMSG_ARR']) &&
	count($_SESSION['ERRMSG_ARR']) > 0
) {
	echo '<ul style="padding:0; color:red;">';
	foreach ($_SESSION['ERRMSG_ARR'] as $msg) {
		echo '<li>', $msg, '</li>';
	}
	echo '</ul>';
	unset($_SESSION['ERRMSG_ARR']);
}
?>

<head>
	<link rel="stylesheet" href="log-in.css">
</head>

<body>
	<?php
	if ($_GET) {
		echo $_GET['feedback'];
	}
	?>
	<form class="login-form" method="POST" action="login_query.php">
		<div class="form-section">
			<div class="section-header-login">
				<img class="logo-login" src="images/logo.png">
				<h1>Connexion</h1>
			</div>
			<div class="group">
				<input type="text" name="identifiant" />
				<span class="highlight"></span>
				<span class="bar-login"></span>
				<label class="login-label">Identifiant</label>
			</div>
			<div class="group">
				<input type="password" name="pwd" />
				<span class="highlight"></span>
				<span class="bar-login barpass-login"></span>
				<label class="login-label">Mot de Passe</label>
				<div>
					<input class='showpassword' id='showpassword' type="checkbox" onclick="showpass()"><label id='labelshowpass' for="showpassword"><span class="fa fa-fw fa-eye"></span></label>
				</div>
			</div>
			<input name="login" type="submit" value="Valider"></a>
			<div class="flex">
				<a href="signup.php" class="login-forgot-pass">Créer Un Compte</a><br><br>
				<a href="recover.php" class="login-forgot-pass">Mot de passe oublié</a>
			</div>
		</div>
	</form>
</body>