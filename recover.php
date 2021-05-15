<?php
session_start();
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
	<div id="recover">
		<?php if(isset($_GET['reset'])){
			if($_GET['reset'] == 'success'){
				echo "<p class='success'>Un mail contenant le lien de réinitialisation vous a été envoyé</p>";
			}
		} 
		if(isset($_GET['verifyemail'])){
			if($_GET['verifyemail'] == 'notvalid'){
				echo "<p class='error'>L'email rentré n'est pas valide</p>";
			}
		}
		?>
		<form class="login-form" method="POST" action="recover-pass.php">
			<div class="recover-heading">
				<img class="logorecover" src="images/logo.png">
				<h1>Password Reset</h1>
			</div>
			<input type="text" name="email" placeholder="Email" />
			<input name="submit-reset" type="submit" value="Send Email Verification"></a>
		</form>
	</div>
</body>