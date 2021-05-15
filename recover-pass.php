<?php

if (isset($_POST["submit-reset"])) {

	$selector =  bin2hex(random_bytes(8));
	$token = random_bytes(32);

	$url = "127.0.0.1/PPE-Copy/new-pass.php?selector=" . $selector . "&validator=" . bin2hex($token);

	$expires = date("U") + 1800;

	require 'connection.php';
	if (htmlspecialchars($_POST['email']) !== '' && preg_match("/^[^@\t\r\n#\/]{1,32}@[^@ \t\r\n#\/]{1,32}.[^@ \t\r\n]{1,5}$/", $_POST['email']) === 1) {
		$userEmail = $_POST['email'];


		$sql = ("DELETE FROM pwdReset WHERE pwdResetEmail =?");
		$sth1 = $conn->prepare($sql);
		$sth1->bindParam(1, $userEmail);
		$sth1->execute();
		$row = $sth1->fetchAll();

		$sql1 = ("INSERT INTO pwdReset(pwdResetEmail, pwdResetSelector, pwdResetToken, pwdResetExpires) VALUES (?, ?, ?, ?)");
		$sth2 = $conn->prepare($sql1);
		$tokenHashed = password_hash($token, PASSWORD_DEFAULT);
		$sth2->bindParam(1, $userEmail);
		$sth2->bindParam(2, $selector);
		$sth2->bindParam(3, $tokenHashed);
		$sth2->bindParam(4, $expires);
		$sth2->execute();
		$row2 = $sth2->fetchAll();


		$to = $userEmail;
		$subject = 'Réinitialisation Mot De Passe';
		$message = '<p>Vous avez demandé une réinitialisation du mot de passe de votre compte EA. Dans le cas contraire, ignorez cet e-mail.
	Pour choisir un nouveau mot de passe et valider votre demande, cliquez sur le lien suivant :</br>';
		$message .= '<a href="' . $url . '">' . $url . '</a>';
		$message .= 'Si le lien ne fonctionne pas, copiez-le et collez-le directement dans la barre d\'adresse de votre navigateur.</p>';

		$headers = "From: GSB Entreprise <gsb.entreprisefrais@gmail.com>\r\n";
		$headers .= "Reply-To: gsb.entreprisefrais@gmail.com\r\n";
		$headers .= "Content-type: text/html\r\n";

		mail("khalqallahwalid@gmail.com", $subject, $message, $headers);

		header("Location: recover.php?reset=success");
	} else {
		header("Location: recover.php?verifyemail=notvalid");
	}
} else {
	header("Location: login.php");
}
