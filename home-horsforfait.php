<?php
session_start();
error_reporting(0);
include('/functions.php');
date_default_timezone_set('Europe/Paris');
$user = $_SESSION['USER'];
if (isset($_GET['usr'])) {
	$usr = $_GET['usr'];
	$query1 = "SELECT * FROM fichefrais F JOIN visiteur V ON F.idVisiteur = V.id WHERE V.login = '$usr' AND F.idEtat = 'CR'";
} else {
	$query1 = "SELECT * FROM fichefrais F JOIN visiteur V ON F.idVisiteur = V.id WHERE V.login = '$user' AND F.idEtat = 'CR'";
}
$query = "SELECT * FROM visiteur WHERE login = '$user'";
$sth = $conn->prepare($query);
$sth->execute();
$result = $sth->fetchAll();

$sth1 = $conn->prepare($query1);
$sth1->execute();
$result1 = $sth1->fetchAll();

$query2 = "SELECT login FROM visiteur WHERE comptable = 0";
$sth2 = $conn->prepare($query2);
$sth2->execute();
$result2 = $sth2->fetchAll();

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
									<h4><?php echo $user['nom'] ." ". $user['prenom']; ?></h4>
									<h4><?php echo $user['ville'] ?></h4>
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
							<?php } ?>
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
						<li class="menu-item-normal"><a href="home.php"><i class="bi bi-pencil-square"></i>Saisie FicheFrais</a></li>
						<li class="menu-item-normal"><a href="home-ligne.php"><i class="bi bi-pencil-square"></i>Saisie ligne Frais</a></li>
						<li class="menu-item-active"><a href="home-horsforfait.php"><i class="bi bi-pencil-square"></i>Saisie Frais Hors Forfait</a></li>
						<li class="menu-item-normal"><a href="home-validation.php"><i class="bi bi-file-earmark-check"></i>Validation Fiches</a></li>
						<li class="menu-item-normal"><a href="consulterfrais.php"><i class="bi bi-file-earmark-spreadsheet-fill"></i>Consulter Frais</a></li>
						<li class="menu-item-normal"><a href="stats.php"><i class="bi bi-file-earmark-bar-graph-fill"></i>Statistiques</a></li>
						<li class="menu-item-normal"><a href="users.php"><i class="bi bi-people-fill"></i>Utilisateurs</a></li>
					</ul>
				</div>
				<div class="col-md-8">
					<div class="card mb-3">
						<div class="card-body">
							<div class="row">
								<div class="titre">
									<h1>SAISIE DE FRAIS HORS FORFAIT</h1>
									<?php
									if ($_GET) {
										echo $_GET['feedback'];
									} ?>
								</div>
								<form class='input-form' method="POST" action="horsforfait.php">
									<div class="group">
										<?php if ($comptable == 1) { ?>
											<label>Visiteur : </label>
											<select name="usr" onchange="location = this.value;">
												<?php if (isset($usr)) { ?>
													<option value="<?= $usr ?>"> <?= $usr ?> </option>
												<?php } ?>
												<?php foreach ($result2 as $user) { ?>
													<option value="home-horsforfait.php?usr=<?= $user['login'] ?>"> <?= $user['login'] ?> </option>
												<?php } ?>
											</select>
										<?php } ?>
									</div>
									<div class='group'>
										<label>Mois :</label>
										<select name="mois">
											<option disabled selected value> -- Mois -- </option>
											<?php
											if (empty($result1)) { ?>
												<option disabled> Ce Visiteur n'a pas de fiche </option>
												<?php } else {
												foreach ($result1 as $row1 => $month) {
													switch ($month['mois']) {
														case 1:
															$moislib = 'JANVIER';
															break;
														case 2:
															$moislib = 'FEVRIER';
															break;
														case 3:
															$moislib = 'MARS';
															break;
														case 4:
															$moislib = 'AVRIL';
															break;
														case 5:
															$moislib = 'MAI';
															break;
														case 6:
															$moislib = 'JUIN';
															break;
														case 7:
															$moislib = 'JUILLET';
															break;
														case 8:
															$moislib = 'AOÛT';
															break;
														case 9:
															$moislib = 'SEPTEMBRE';
															break;
														case 10:
															$moislib = 'OCTOBRE';
															break;
														case 11:
															$moislib = 'NOVEMBRE';
															break;
														case 12:
															$moislib = 'DECEMBRE';
															break;
													} ?>
													<option value="<?php echo $month['mois'] ?>"> <?php echo $moislib ?> </option>
											<?php }
											} ?>
										</select>
									</div>
									<div class='group'>
										<label>Libelle :</label>
										<input class="inputs" type="text" name="libelle">
									</div>
									<div class='group'>
										<label>Date :</label>
										<input value="<?php echo $today; ?>" class="inputs" type="date" name="date">
									</div>
									<div class='group'>
										<label>Montant :</label>
										<input class="inputs" type="text" name="mtn">
									</div>
									<input class='valider' name="login" type="submit" value="Valider">
								</form>
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