<?php
include('/functions.php');
session_start();
$errmsg_arr = array();
$errflag = false;
date_default_timezone_set('CET');
$feedback = '';

if (isset($_POST['login_visiteur'])) {
  $logVis = $_POST['login_visteur'];
  $sqlid = "SELECT * FROM visiteur WHERE login= ?";
  $resultid = $conn->prepare($sqlid);
  $resultid->bindParam(1, $logVis);
  $resultid->execute();
  $result = $resultid->fetchColumn();
  if ($result > 0) {
    echo '<span class="text-danger">Not Exist</span>';
  } else {
    echo '<span class="text-success">Exist</span>';
  }
}

if (isset($_POST['register'])) {
  if (
    $_POST['mdp'] && $_POST['email'] && $_POST['identifiant'] && $_FILES['portrait']
    && !empty($_POST['mdp']) && !empty($_POST['email']) && !empty($_POST['identifiant']) && !empty($_FILES['portrait'])
  ) {
    $url = "https://www.google.com/recaptcha/api/siteverify";
    $data = [
      'secret' => "6LfhzeQZAAAAAECKzVt5rseMWTcO8lovrpQ0b3Mi",
      'response' => $_POST['token']
    ];
    $options = array(
      'http' => array(
        'header' => "content-type: application/x-www-form-urlencoded\r\n",
        'method' => 'POST',
        'content' => http_build_query($data)
      )
    );
    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);
    $res = json_decode($response, true);
    if ($res['success'] == true) {
      $target_dir = "images/profil/";
      $prenom = $_POST['prenom'];
      $nom = $_POST['nom'];
      if (!empty($_POST['identifiant'])) {
        $identifiant = htmlspecialchars($_POST['identifiant']);
      } else {
        $error = 1;
        $feedback = "<p class='error'>Champ identifiant non renseigné</p>";
      }
      if (htmlspecialchars($_POST['email']) !== '' && preg_match("/^[^@\t\r\n#\/]{1,32}@[^@ \t\r\n#\/]{1,32}.[^@ \t\r\n]{1,5}$/", $_POST['email']) === 1) {
        $email = htmlspecialchars($_POST['email']);
      } else {
        $error = 1;
        $feedback = '<p>Champ email non renseigné</p>';
      }
      $mdp = $_POST['mdp'];
      $adresse = $_POST['adresse'];
      $cp = $_POST['cp'];
      $ville = htmlspecialchars($_POST['ville']);
      $dateEmbauche = date("Y-m-d");
      $chars = '0123456789abcdefghijklmnopqrstuvwxyz';
      $id = substr(str_shuffle($chars), 0, 3);
      // Hashing
      $pass = password_hash($mdp, PASSWORD_DEFAULT);
      if (isset($_FILES['portrait'])) {
        $tempname = $_FILES["portrait"]["tmp_name"];
        $filename = $_FILES["portrait"]["name"];
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $nomDest = $identifiant . "." . $extension;
        if (move_uploaded_file($tempname, $target_dir . $nomDest)) {;
        } else {
          $error = 1;
          $feedback =  "<p class='error'>Erreur de telechargement de votre image, veuillez selectionner une autre.</p>";
        }
      } else {
        $error = 1;
        $feedback = '<p>Champ email non renseigné</p>';
      }
      if ($error == 0) {
        register([$id, $nom, $prenom, $identifiant, $email, $pass, $adresse, $cp, $ville, $dateEmbauche, $nomDest]);
        $feedback = "<p class='success'>Inscription effectuée</p>";
        header("location: login.php?feedback=" . $feedback);
      } elseif ($error == 1) {
        $feedback = "<p class='error'>Champ important non renseigné</p>";
        header("location: signup.php?feedback=" . $feedback);
      }
      // set the PDO error mode to exception
    } else {
      print_r($res);
      echo '<h1 style="padding:0; color:red; font-weight : bold">Erreur Robot Detécté</h1>';
    }
  }
}
