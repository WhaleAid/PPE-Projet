<?php
session_start();
include('/functions.php');
//error_reporting(0);
date_default_timezone_set('Europe/Paris');
$user = $_SESSION['USER'];
if ($_GET) {
	if ($_GET['user']) {
		$usr = $_GET['user'];
	}
}
$query = "SELECT * FROM visiteur WHERE login = '$user'";
$sth = $conn->prepare($query);
$sth->execute();
$result = $sth->fetchAll();

$query1 = "SELECT login FROM visiteur WHERE comptable = 0";
$sth1 = $conn->prepare($query1);
$sth1->execute();
$result1 = $sth1->fetchAll();

$month = date('m');
$day = date('d');
$year = date('Y');

$today = $year . '-' . $month . '-' . $day;
?>
<!DOCTYPE html>
<html>

<head>
	<title>PROFIL</title>
	<link href='home.css' rel='stylesheet' type='text/css'>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
	<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/angular_material/0.8.3/angular-material.min.css">
</head>

<body>
	<div class="container">
		<div class="main-body">
			<div class="headrow gutters-sm">
				<div class="col-md-4 mb-3">
					<div class="card">
						<div class="card-body header-card">
							<?php
							foreach ($result as $row => $user) { ?>
								<div class='rounded-circle-right'>
									<div class='param' onclick="moreinfo()">
										<i class="bi bi-gear-fill"></i>
									</div>
									<img id='pfp' src="./images/profil/<?php echo $user['portrait'] ?>" alt="Admin" class="rounded-circle" width="150">
								</div>
								<div id='usr-info' class="user-info card">
									<img src="./images/profil/<?php echo $user['portrait'] ?>" alt="Admin" class="rounded-circle" width="150">
									<h4><?php echo $_SESSION['USER']; ?></h4>
									<?php if ($user['comptable'] == 0) { ?>
										<p>Visiteur</p>
									<?php $comptable = 0;
									} else { ?>
										<p>Comptable</p>
									<?php
										$comptable = 1;
									} ?>
									<p class="email"><?php echo $user['email'] ?></p>
									<button class="deco"><a href="logout.php">Déconnexion</a></button>
								</div>
								<?php if (is_null($user['portrait'])) { ?>
									<img src="images/pfp.png" alt="Admin" class="rounded-circle" width="150">
									<?php} else {?>
									<img src="/PPE/images/profil/<?php echo $user['portrait'] ?>" alt="Admin" class="rounded-circle rounded-circle-right" width="150">
							<?php
								}
							} ?>
							<div class="rounded-circle-left">
								<img src="images/logo.png" alt="Admin" width="150">
								<h3>GSB FRAIS</h3>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class='body-container'>
				<div class="main-menu card">
					<ul class="menu">
						<li class="menu-item-active"><a href="home.php"><i class="bi bi-pencil-square"></i>Saisie FicheFrais</a></li>
						<li class="menu-item-normal"><a href="home-ligne.php"><i class="bi bi-pencil-square"></i>Saisie ligne Frais</a></li>
						<li class="menu-item-normal"><a href="home-horsforfait.php"><i class="bi bi-pencil-square"></i>Saisie Frais Hors Forfait</a></li>
						<li class="menu-item-normal"><a href="home-validation.php"><i class="bi bi-file-earmark-check"></i>Validation Fiches</a></li>
						<li class="menu-item-normal"><a href="consulterfrais.php"><i class="bi bi-file-earmark-spreadsheet-fill"></i>Consulter Frais</a></li>
					</ul>
				</div>
				<div class="col-md-8">
					<div class="card mb-3">
						<div class="card-body">
							<div class="row">
								<div class="titre">
									<h1>SAISIE DE FRAIS</h1>
									<?php
									if ($_GET) {
										echo $_GET['feedback'];
									} ?>
								</div>
								<form class='input-form' method="POST" action="fichefrais.php">
									<div class="group">
										<?php if ($comptable == 1) { ?>
											<label>Visiteur : </label>
											<select name="usr">
												<?php foreach ($result1 as $user) { ?>
													<option value="<?= $user['login'] ?>"> <?= $user['login'] ?> </option>
												<?php } ?>
											</select>
										<?php } ?>
									</div>
									<div class='group'>
										<label>Mois :</label>
										<select name="mois" required>
											<option disabled selected value> -- Mois -- </option>
											<option value="1">Janvier</option>
											<option value="2">Férvier</option>
											<option value="3">Mars</option>
											<option value="4">Avril</option>
											<option value="5">Mai</option>
											<option value="6">Juin</option>
											<option value="7">Juillet</option>
											<option value="8">Août</option>
											<option value="9">Septembre</option>
											<option value="10">Octobre</option>
											<option value="11">Novembre</option>
											<option value="12">Décembre</option>
										</select>
									</div>
									<div class='group'>
										<label>Nombres de justificatifs :</label>
										<input class="inputs" type="text" name="nbjust">
									</div>
									<div class='group'>
										<label>Date de modification :</label>
										<input value="<?php echo $today; ?>" class="inputs" type="date" name="datemodif">
									</div>
									<input class='valider' name="login" type="submit" value="Valider">
								</form>
								<!-- <h3 class="radio-title" style="text-decoration: underline;">Situation</h3>
							<label class="radio-label">Enregistré</label><input class="inputs" type="radio" name="situation" checked>
							<label class="radio-label">Remboursé</label><input class="inputs" type="radio" name="situation">
							<label class="radio-label">Validé</label><input class="inputs" type="radio" name="situation"> -->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>
	</div>
</body>
<script>
	$(function() {
		$('li').css('cursor', 'pointer')

			.click(function() {
				window.location = $('a', this).attr('href');
				return false;
			});
	});

	function moreinfo() {
		var x = document.getElementById("usr-info");
		if (x.style.display && x.style.display !== "none") {
			x.style.display = "none";
		} else {
			x.style.display = "block";
		}
	}
</script>

</html>