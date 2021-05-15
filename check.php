<?php
//check.php  
include('/connection.php');
if (isset($_POST['username'])) {
    $username  = $_POST["username"];
    $sql = ("SELECT * FROM visiteur WHERE login  = '$username'");
    $sth1 = $conn->prepare($sql);
    $sth1->execute();
    $row = $sth1->fetchAll();
    if ($row != null) {
        echo '<span class="text-danger">Identifiant est pris</span>';
    } else {
        echo '<span class="text-success">Identifiant valable</span>';
    }
}

if (isset($_POST['email'])) {
    $email  = $_POST["email"];
    $sql2 = ("SELECT * FROM visiteur WHERE email  =?");
    $sth2 = $conn->prepare($sql2);
    $sth2->bindParam(1, $email);
    $sth2->execute();
    $row2 = $sth2->fetchAll();
    if ($row2 != null) {
        echo '<span class="text-danger">Email est pris</span>';
    } else {
        echo '<span class="text-success">Email valable</span>';
    }
}