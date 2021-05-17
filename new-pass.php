<?php
session_start();
error_reporting(0);
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
        <?php
        $selector = $_POST['selector'];
        $validator = $_POST['validator'];

        if (isset($_GET['newpwd'])) {
            if ($_GET['newpwd'] == 'empty') {
                echo "<p class='text-danger'>Mot de passe non renseigné</p>";
                $selector = $_GET['selector'];
                $validator = $_GET['validator'];
            } elseif ($_GET['newpwd'] == 'pwdnotsame') {
                echo "<p class='text-danger'>Mots de passe renseignés sont different</p>";
                $selector = $_GET['selector'];
                $validator = $_GET['validator'];
            }



            if (empty($selector) && empty($validator)) {
                echo "Votre demande n'est pas valide";
            } else {
                if (ctype_xdigit($selector) !== false && ctype_xdigit($validator) !== false) {
        ?>

                    <form action="resetPassVerif.php" method="post">
                        <input type="hidden" name="selector" value="<?= $selector ?>">
                        <input type="hidden" name="validator" value="<?= $validator ?>">
                        <input type="password" name="password" placeholder="Nouveau mot de passe">
                        <input type="password" name="password2" placeholder="Confirmer mot de passe">
                        <button type="submit" name="passResetSubmit">Réinitialiser Votre Mot De Passe</button>
                    </form>

        <?php
                }
            }
        } ?>
        <form class="login-form" method="POST" action="recover-pass.php">
            <div class="recover-heading">
                <img class="logorecover" src="images/logo.png">
                <h1>Password Reset</h1>
            </div>
            <input type="password" name="password" placeholder="Mot de passe" />
            <input type="password" name="password" placeholder="Mot de passe" />
            <input name="pwdChange" type="submit" value="Valider"></a>
        </form>
    </div>
</body>