<?php
session_start();
error_reporting(0);
include('/functions.php');
date_default_timezone_set('Europe/Paris');
$PremierJour = strtotime('first day of next month');
$nbJourRest = ($PremierJour - time()) / (24 * 3600);
if ($_SESSION['USER'] != null) {
    $user = $_SESSION['USER'];
    $tothf = 0;
    $etp = 0;
    $etape = 0;
    $kilo = 0;
    $km = 0;
    $nuite = 0;
    $nui = 0;
    $repas = 0;
    $rep = 0;
    $query = "SELECT * FROM visiteur WHERE login = '$user'";
    $sth = $conn->prepare($query);
    $sth->execute();
    $result = $sth->fetchAll();

    $sql = "SELECT * FROM visiteur WHERE comptable NOT LIKE 1 ";
    $sthsql = $conn->prepare($sql);
    $sthsql->execute();
    $resultusr = $sthsql->fetchAll();

    if (isset($_POST['valide'])) {
        $id = $_POST['id'];
        $mois = $_POST['mois'];
        $total = $_POST['mtnval'];
        $datemodif = $_POST['datemodif'];

        ValiderFiche([$id, $mois, $total, $datemodif]);
    }

    if (isset($_POST['rembourser'])) {
        $id = $_POST['id'];
        $mois = $_POST['mois'];
        RembourserFiche([$id, $mois]);
    }

    if (isset($_POST['cloturer'])) {
        $id = $_POST['id'];
        $mois = $_POST['mois'];
        CloturerFiche([$id, $mois]);
    }

    if (isset($_POST['ouvrir'])) {
        $id = $_POST['id'];
        $mois = $_POST['mois'];
        OuvrirFiche([$id, $mois]);
    }

    if (isset($_GET)) {
        if (isset($_GET['userpick'])) {
            $usercons = $_GET['usr'];
            $mois = $_GET['mois'];
        }
    }

    if (isset($_GET['type']) && $_GET['type'] == 'delete') {
        $id = $_GET['id'];
        $mois = $_GET['mois'];
        $idFrais = $_GET['idfrais'];
        $usercons = $_GET['usr'];
        $mois = $_GET['mois'];
        deleteFrais([$id, $mois, $idFrais]);
    }

    if (isset($_GET['type']) && $_GET['type'] == 'deletehf') {
        $id = $_GET['id'];
        $mois = $_GET['mois'];
        $idFrais = $_GET['idfrais'];
        $usercons = $_GET['usr'];
        $mois = $_GET['mois'];
        deleteFraishf([$id, $mois, $idFrais]);
    }

    $sqlid = "SELECT * FROM visiteur WHERE login= ?";
    $resultid = $conn->prepare($sqlid);
    $resultid->bindParam(1, $usercons);
    $resultid->execute();
    $rowid = $resultid->fetch();
    $id = $rowid['id'];

    $query1 = "SELECT idFraisForfait,libelle,quantite, montant, quantite*montant FROM `lignefraisforfait` JOIN fraisforfait on idFraisForfait = fraisforfait.id JOIN visiteur v ON v.id = lignefraisforfait.idVisiteur where login=? AND mois=?";
    $sth1 = $conn->prepare($query1);
    $sth1->bindParam(1, $usercons);
    $sth1->bindParam(2, $mois);
    $sth1->execute();
    $result1 = $sth1->fetchAll();

    $query3 = "SELECT * FROM fraisforfait";
    $sth3 = $conn->prepare($query3);
    $sth3->execute();
    $result3 = $sth3->fetchAll();

    foreach ($result3 as $frai => $frais) {
        switch ($frais['id']) {
            case 'ETP':
                $etape = $frais['montant'];
                break;
            case 'KM':
                $km = $frais['montant'];
                break;
            case 'NUI':
                $nuite = $frais['montant'];
                break;
            case 'REP':
                $repas = $frais['montant'];
                break;
        }
    }

    $sql3 = "SELECT * FROM fichefrais JOIN visiteur v ON v.id = fichefrais.idVisiteur JOIN etat ON idEtat = etat.id WHERE login= ? AND mois= ?";
    $result3 = $conn->prepare($sql3);
    $result3->bindParam(1, $usercons);
    $result3->bindParam(2, $mois);
    $result3->execute();
    $etats = $result3->fetchAll();
    foreach ($etats as $etat) {
        $etatfiche = $etat['libelle'];
        $etatid = $etat['id'];
    }


    $sql2 = "SELECT id, date, libelle, montant FROM `lignefraishorsforfait` where idVisiteur='$id' AND mois=?";
    $resulthf = $conn->prepare($sql2);
    $resulthf->bindParam(1, $mois);
    $resulthf->execute();
    $rowhf = $resulthf->fetchAll();


    $query2 = "SELECT * FROM visiteur V JOIN  lignefraisforfait LF ON LF.idVisiteur = V.id WHERE V.login = '$usercons' AND mois = '$mois'";
    $sth2 = $conn->prepare($query2);
    $sth2->execute();
    $result2 = $sth2->fetchAll();
    foreach ($result2 as $users => $userligne) {
        switch ($userligne['idFraisForfait']) {
            case 'ETP':
                $etp = $userligne['quantite'];
                break;
            case 'KM':
                $kilo = $userligne['quantite'];
                break;
            case 'NUI':
                $nui = $userligne['quantite'];
                break;
            case 'REP':
                $rep = $userligne['quantite'];
                break;
        }
    }

    $query4 = "SELECT * FROM visiteur V JOIN  lignefraishorsforfait LF ON LF.idVisiteur = V.id WHERE V.login = '$usercons' AND mois = '$mois'";
    $sth4 = $conn->prepare($query4);
    $sth4->execute();
    $result4 = $sth4->fetchAll();
    foreach ($result4 as $hf) {
        $tothf = $tothf + $hf['montant'];
    }

    $total_frais = ($etp * $etape + $kilo * $km + $nuite * $nui + $repas * $rep) + $tothf;

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
                                foreach ($result as $row => $user) {
                                    $comptablecheck = $user['comptable'];
                                ?>
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
                                        <?php } else { ?>
                                            <p>Comptable</p>
                                        <?php } ?>
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
                            <li class="menu-item-normal"><a href="home-horsforfait.php"><i class="bi bi-pencil-square"></i>Saisie Frais Hors Forfait</a></li>
                            <li class="menu-item-active"><a href="home-validation.php"><i class="bi bi-file-earmark-check"></i>Validation Fiches</a></li>
                            <li class="menu-item-normal"><a href="consulterfrais.php"><i class="bi bi-file-earmark-spreadsheet-fill"></i>Consulter Frais</a></li>
                        </ul>
                    </div>
                    <?php if ($comptablecheck == 1) { ?>
                        <div class="col-md-8">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="row">
                                        <?php
                                        if ($_GET) {
                                            echo $_GET['feedback'];
                                        } ?>
                                        <div class="titre">
                                            <div class="userpick">
                                                <div>
                                                    <h1>VALIDATION FICHES</h1>
                                                </div>
                                                <form method="get" action="">
                                                    <div class="userform">
                                                        <label>Visiteur :</label>
                                                        <select name="usr">
                                                            <?php if ($usercons != null) { ?>
                                                                <option value="<?= $usercons ?>"><?= $usercons ?></option>
                                                            <?php } ?>
                                                            <?php foreach ($resultusr as $usr) { ?>
                                                                <option value="<?= $usr['login'] ?>"><?= $usr['login'] ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <label>Mois :</label>
                                                        <select name="mois">
                                                            <?php
                                                            switch ($mois) {
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
                                                            }
                                                            if ($mois != null) { ?>
                                                                <option value="<?= $mois ?>" selected> <?= $moislib ?> </option>
                                                            <?php } else { ?>
                                                                <option disabled selected value> -- Mois -- </option>
                                                            <?php } ?>
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
                                                        <input type="submit" name="userpick">
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="row">
                                        <?php if ($result1 != null) { ?>
                                            <h1>FRAIS FORFAIT DU MOIS DE <?= $moislib ?></h1>
                                            <table id="frais-forfait">
                                                <thead>
                                                    <tr class="w3-light-blue">
                                                        <th>Frais Forfait</th>
                                                        <th>Quantite</th>
                                                        <th>Prix Unitaire</th>
                                                        <th>Montant</th>
                                                        <th>Supprimer</th>
                                                    </tr>
                                                </thead>
                                                <?php
                                                foreach ($result1 as $result => $fraisfin) {
                                                    echo "<tr><td class = 'libelle'>" . $fraisfin['libelle'] . "</td><td>" . $fraisfin['quantite'] . "</td><td>" . $fraisfin['montant'] . "</td><td>" . $fraisfin['quantite*montant'] .
                                                        "</td>";
                                                    if ($etatid == 'CR') {
                                                        echo "<td><a class='del' href='home-validation.php?type=delete&idfrais=" . $fraisfin['idFraisForfait'] . "&id=" . $id . "&mois=" . $mois . "&usr=" . $usercons . "'><i class='bi bi-trash-fill'></i></a></td>";
                                                    } else {
                                                        echo "<td>Fiche " . $etatfiche . "</td>";
                                                    }
                                                    echo "</tr>";
                                                }
                                                ?>
                                            </table>
                                        <?php } else { ?>
                                            <h3>Ce Visiteur n'as pas de fiche dans ce mois</h3>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <?php if ($rowhf != null) { ?>
                                        <h1>FRAIS HORS FORFAIT DU MOIS DE <? $moislib ?></h1>
                                        <table id='frais-hors-forfait'>
                                            <thead>
                                                <tr class="w3-light-blue">
                                                    <th>Date</th>
                                                    <th>Libelle</th>
                                                    <th>Montant</th>
                                                    <th>Supprimer</th>
                                                </tr>
                                            </thead>
                                            <?php
                                            foreach ($rowhf as $resulthf => $fraishffin) {
                                                echo "<tr><td>" . $fraishffin['date'] . "</td><td>" . $fraishffin['libelle'] . "</td><td>" . $fraishffin['montant'] . "</td>";
                                                if ($etatid == 'CR') {
                                                    echo "<td><a class='del' href='home-validation.php?type=deletehf&idfrais=" . $fraishffin['id'] . "&id=" . $id . "&mois=" . $mois .  "&usr=" . $usercons . "'><i class='bi bi-trash-fill'></i></a></td></td></tr>";
                                                } else {
                                                    echo "<td>Fiche Clôturée</td>";
                                                }
                                            }
                                            ?>
                                        </table>
                                    <?php } else { ?>
                                        <h3>Ce Visiteur n'as pas de fiche hors forfait pour ce mois</h3>
                                    <?php }
                                    if ($result1 != null) {
                                    ?>
                                        <h1>RECAPITULATIF</h1>
                                        <table id='frais-recap'>
                                            <thead>
                                                <tr class="w3-light-blue">
                                                    <th>Date</th>
                                                    <th>Montant Total</th>
                                                    <th>Etat de Fiche</th>
                                                    <th>Valider</th>
                                                </tr>
                                            </thead>
                                            <tr>
                                                <form method="post" action="">
                                                    <input type="hidden" name="id" value="<?= $id ?>">
                                                    <input type="hidden" name="mois" value="<?= $mois ?>">
                                                    <td><input value="<?php echo $today; ?>" type="date" name="datemodif"></td>
                                                    <?php if ($etatid != 'VA' && $etatid != 'RB') { ?>
                                                        <td><input value='<?= $total_frais ?>' type="text" name="mtnval"></td>
                                                    <?php } else {
                                                        echo "<td>" . $total_frais . "</td>";
                                                    } ?>
                                                    <td><?= $etatfiche ?></td>
                                                    <?php if ($etatid != 'VA' && $etatid != 'RB') { ?>
                                                        <td><input type="submit" name="valide" value="Valider" onclick="return confirm('Cette operation est irréversible')"></td>
                                                    <?php } else { ?>
                                                        <td>Fiche déjà <?= $etatfiche ?></td>
                                                    <?php } ?>
                                                </form>
                                            </tr>
                                        </table>
                                    <?php } ?>
                                    <div class="bottom-section">
                                        <?php if ($etatid == 'VA') { ?>
                                            <div class="btns">
                                                <form method="post" action="">
                                                    <input type="hidden" name="id" value="<?= $id ?>">
                                                    <input type="hidden" name="mois" value="<?= $mois ?>">
                                                    <input type="submit" name="rembourser" value="Remboursé" onclick="return confirm('Cette operation est irréversible')">
                                                </form>
                                            </div>
                                        <?php }
                                        if ($etatid == 'CR') {
                                        ?>
                                            <div class="btns">
                                                <form method="post" action="">
                                                    <input type="hidden" name="id" value="<?= $id ?>">
                                                    <input type="hidden" name="mois" value="<?= $mois ?>">
                                                    <input type="submit" name="cloturer" value="Clôturer">
                                                    <p><i class="bi bi-exclamation-triangle-fill"></i>Il reste <?= $nbJourRest ?> jours avant la clôturation automatique</p>
                                                </form>
                                            </div>
                                        <?php }
                                        if ($etatid == 'CL') {
                                        ?>
                                            <div class="btns">
                                                <form method="post" action="">
                                                    <input type="hidden" name="id" value="<?= $id ?>">
                                                    <input type="hidden" name="mois" value="<?= $mois ?>">
                                                    <input type="submit" name="ouvrir" value="Ouvrir Saisie">
                                                </form>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <!-- <form class='input-form' method="POST" action="fichefrais.php">
                                <div class='group'>

                                </div>
                                <div class='group'>
                                    <label>MontantValide :</label>
                                    <input value='<?= $total_frais ?>' class="inputs" type="text" name="mtnval" readonly>
                                </div>
                                <div class='group'>
                                    <label>Date de modification :</label>
                                    <input value="<?php echo $today; ?>" class="inputs" type="date" name="datemodif">
                                </div>
                                <div class='group'>
                                    <label>Etat de la fiche :</label>
                                    <select name="etat" required>
                                        <option disabled selected value> -- Etat -- </option>
                                        <option value="CR">Fiche créée, saisie en cours</option>
                                        <option value="CL">Saisie clôturée</option>
                                        <option value="RB">Remboursé</option>
                                        <option value="VA">Validé et mise en paiement</option>
                                    </select>
                                </div>
                                <input class='valider' name="login" type="submit" value="Valider">
                            </form> -->
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
<?php } else { ?>
    <div class="locksection">
        <div class="card mb-3">
            <div class="card-body">
                <h1><i class="bi bi-exclamation-triangle-fill"></i>Cet espace est reservé au comptable</h1>
                <img class="lock" src="images/lock.png">
            </div>
        </div>
    </div>
<?php } ?>
<?php } else { ?>
    <h3>Veuillez Vous Connecter <a href="login.php">ici</a></h3>
<?php } ?>

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