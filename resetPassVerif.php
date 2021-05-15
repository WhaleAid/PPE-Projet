<?php

if (isset($_POST['passResetSubmit'])) {

    $selector = $_POST['selector'];
    $validator = $_POST['validator'];
    $pass = $_POST['password'];
    $pass2 = $_POST['password2'];

    if (empty($pass) || empty($pass2)) {
        header("Location: newpass.php?newpwd=empty&selector=" . $selector . "&validator=" . $validator);
        exit();
    } elseif ($pass != $pass2) {
        header("Location: newpass.php?newpwd=pwdnotsame&selector=" . $selector . "&validator=" . $validator);
    }

    $currentDate = date("U");

    require 'connection.php';

    $sql = ("SELECT * FROM pwdReset WHERE pwdResetSelector =? AND pwdResetExpires >= $currentDate");
    if (!$sth1 = $conn->prepare($sql)) {
        echo "Erreur";
        exit();
    } else {
        $sth1->bindParam(1, $selector);
        $sth1->execute();
        $row = $sth1->fetchAll();

        if (empty($row)) {
            echo "Votre demande de réinitialisation a été refusé veuillez réessayé";
            exit();
        } else {
            $tokenBin = hex2bin($validator);
            $tokenCheck = password_verify($tokenBin, $row["pwdResetToken"]);

            if ($tokenCheck === false) {
                echo "Votre demande de réinitialisation a été refusé veuillez réessayé";
            } elseif ($tokenCheck === true) {

                $tokenEmail = $row['pwdResetEmail'];

                $sql2 = ("SELECT * FROM visiteur WHERE email =?");
                if (!$sth2 = $conn->prepare($sql2)) {
                    echo "Erreur";
                    exit();
                } else {
                    $sth2->bindParam(1, $tokenEmail);
                    $sth2->execute();
                    $row2 = $sth2->fetchAll();
                    if ($rows2 != 0) {
                        echo "erreur";
                    } else {
                        $sql3 = ("UPDATE visiteur SET mdp=? WHERE email =?");
                        if (!$sth3 = $conn->prepare($sql2)) {
                            echo "Erreur";
                            exit();
                        } else {
                            $password = password_hash($pass, PASSWORD_DEFAULT);
                            $sth3->bindParam(1, $password);
                            $sth3->bindParam(2, $tokenEmail);
                            $sth3->execute();
                            $row3 = $sth3->fetchAll();

                            $sql4 = ("DELETE FROM pwdReset WHERE pwdResetEmail =?");
                            if (!$sth1 = $conn->prepare($sql4)) {
                                echo "Erreur";
                                exit();
                            } else {
                                $sth4->bindParam(1, $tokenEmail);
                                $sth4->execute();
                                $row4 = $sth4->fetchAll();
                                header("Location: login.php?pwdchange=success");
                            }
                        }
                    }
                }
            }
        }
    }
} else {
    header("Location: login.php");
}
