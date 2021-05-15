<?php
error_reporting(0);
?>
<!DOCTYPE html>
<html>

<head>
	<script src="https://www.google.com/recaptcha/api.js?render=6LfhzeQZAAAAAFt_wrE8Bh9ozp3THRsyrs9b7o8l"></script>
</head>

<body>
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300&display=swap" rel="stylesheet">
	<link href='log-in.css' rel='stylesheet' type='text/css'>
	<?php
	if ($_GET) {
		echo $_GET['feedback'];
	}
	?>
	<form id="registerform" onsubmit="return validate()" class="login-form2" action="./register_query.php" method="POST" enctype='multipart/form-data'>
		<div class="form-section">
			<div class="section-header">
				<img class="logo" src="images/logo.png">
				<h1>INSCRIPTION</h1>
			</div>
			<div class="flex">
				<div class="group">
					<input type="text" name="nom" required>
					<span class="highlight"></span>
					<span class="bar"></span>
					<label>Nom</label>
				</div>
				<div class="group">
					<input type="text" name="prenom" required>
					<span class="highlight"></span>
					<span class="bar"></span>
					<label>Prenom</label>
				</div>
			</div>
			<div class="group">
				<input id='identifiant' type="text" name="identifiant" required>
				<span class="highlight"></span>
				<span class="bar"></span>
				<label>Identifiant</label>
				<span id="availability"></span>
			</div>
			<div class="group">
				<input id='email' type="email" name="email" required>
				<span class="highlight"></span>
				<span class="bar barlong"></span>
				<label>Email</label>
				<span id="availabilityemail"></span>
			</div>
			<div class="flex">
				<div class="group">
					<div class="flex">
						<div>
							<input type="password" name="mdp" id="pass" required>
							<span class="highlight"></span>
							<span class="bar barpass"></span>
							<label>Mot de Passe</label>
						</div>
						<div>
							<input class='showpassword' id='showpassword' type="checkbox" onclick="showpass()"><label id='labelshowpass' for="showpassword"><span class="fa fa-fw fa-eye"></span></label>
						</div>
					</div>
					<error id="output"></error>
				</div>
				<div class="group">
					<div class="flex">
						<div>
							<input type="password" name="mdp2" id="pass2" onkeyup="check(this)" required>
							<span class="highlight"></span>
							<span class="bar barpass"></span>
							<label>Confirmer</label>
						</div>
						<div>
							<input class='showpassword' id='showpassword' type="checkbox" onclick="showpass()">
							<label id='labelshowpass2' for="showpassword">
								<span class="fa fa-fw fa-eye"></span>
							</label>
						</div>
					</div>
				</div>
			</div>
			<div class="group">
				<input type="text" name="adresse" required>
				<span class="highlight"></span>
				<span class="bar barlong"></span>
				<label>Adresse</label>
			</div>
			<div class="flex">
				<div class="group">
					<input type="number" name="cp" max="99999" required>
					<span class="highlight"></span>
					<span class="bar"></span>
					<label>Code Postal</label>
				</div>
				<div class="group">
					<input type="text" name="ville" required>
					<span class="highlight"></span>
					<span class="bar"></span>
					<label>Ville</label>
				</div>
			</div>
			<div class="flex">
				<a href="login.php" class="login-forgot-pass">
					<div>Déjà membre? Connectez-vous</div>
				</a>
				<input id="register" name="register" type="submit" value="Valider"></a>
			</div>
		</div>
		<input type="hidden" id="token" name="token">
		<div class="image-section">
			<div class='imageupload'>
				<img id='imageoverlay' class='imageoverlay' onClick="triggerClick()" src="images/plus.png" />
				<img class='pfp' src="images/pfp.png" onClick="triggerClick()" id="profileDisplay">
				</span>
				<input type="file" name="portrait" onChange="displayImage(this)" id="profileImage" class="form-control" style="display: none;">
				<label for="portrait">Photo de profil</label>
				<!-- <img src="images/pfp.png" id="profil">
				<label >Photo de profil</label>
				<input type="file" name="portrait" /> -->
			</div>
		</div>
	</form>
	<script>
		grecaptcha.ready(function() {
			grecaptcha.execute('6LfhzeQZAAAAAFt_wrE8Bh9ozp3THRsyrs9b7o8l', {
				action: 'homepage'
			}).then(function(token) {
				console.log(token);
				document.getElementById("token").value = token;
				// pass the token to the backend script for verification
			});
		});

		$(".toggle-password").click(function() {

			$(this).toggleClass("fa-eye fa-eye-slash");
			var input = $($(this).attr("toggle"));
			if (input.attr("type") == "password") {
				input.attr("type", "text");
			} else {
				input.attr("type", "password");
			}
		});

		$('document').ready(function() {
			$('#identifiant').change(function() {
				var username = $(this).val();
				$.ajax({
					url: "check.php",
					method: "POST",
					data: {
						username: username
					},
					dataType: "text",
					success: function(html) {
						$('#availability').html(html);
						if (html == '<span class="text-danger">Identifiant est pris</span>') {
							$("#register").prop('disabled', true);
						} else {
							$("#register").prop('disabled', false);
						}
					},
				});
			});
		});

		$('document').ready(function() {
			$('#email').change(function() {
				var email = $(this).val();
				$.ajax({
					url: "check.php",
					method: "POST",
					data: {
						email: email
					},
					dataType: "text",
					success: function(html) {
						$('#availabilityemail').html(html);
						if (html == '<span class="text-danger">Email est pris</span>') {
							$("#register").prop('disabled', true);
						} else {
							$("#register").prop('disabled', false);
						}
					},
				});
			});
		});
	</script>
	<script src="/PPE/scripts.js"></script>
</body>

</html>