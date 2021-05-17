<?php
session_start();
error_reporting(0);
include('/functions.php');
$user = $_SESSION['USER'];
$errmsg_arr = array();
$errflag = false;
if ($_SESSION['USER']) {
    if (isset($_GET['date'])) {
        $date = $_GET['date'];
    } elseif (isset($_GET['mois'])) {
        $date = $_GET['mois'];
    } else {
        $date = 1;
    }

    if (isset($_GET['type']) && $_GET['type'] == 'delete') {
        $id = $_GET['id'];
        $mois = $_GET['mois'];
        $idFrais = $_GET['idfrais'];
        deleteFrais([$id, $mois, $idFrais]);
    }

    if (isset($_GET['type']) && $_GET['type'] == 'deletehf') {
        $id = $_GET['id'];
        $mois = $_GET['mois'];
        $idFrais = $_GET['idfrais'];
        deleteFraishf([$id, $mois, $idFrais]);
    }

    switch ($date) {
        case 1:
            $month = 'JANVIER';
            break;
        case 2:
            $month = 'FEVRIER';
            break;
        case 3:
            $month = 'MARS';
            break;
        case 4:
            $month = 'AVRIL';
            break;
        case 5:
            $month = 'MAI';
            break;
        case 6:
            $month = 'JUIN';
            break;
        case 7:
            $month = 'JUILLET';
            break;
        case 8:
            $month = 'AOÛT';
            break;
        case 9:
            $month = 'SEPTEMBRE';
            break;
        case 10:
            $month = 'OCTOBRE';
            break;
        case 11:
            $month = 'NOVEMBRE';
            break;
        case 12:
            $month = 'DECEMBRE';
            break;
    }

    $sqlid = "SELECT * FROM visiteur WHERE login= ?";
    $resultid = $conn->prepare($sqlid);
    $resultid->bindParam(1, $user);
    $resultid->execute();
    $rowid = $resultid->fetch();
    $id = $rowid['id'];



    $sql1 = "SELECT idFraisForfait,libelle,quantite, montant, quantite*montant FROM `lignefraisforfait` JOIN fraisforfait on idFraisForfait = fraisforfait.id where idVisiteur=? AND mois=?";
    $result = $conn->prepare($sql1);
    $result->bindParam(1, $id);
    $result->bindParam(2, $date);
    $result->execute();
    $rows = $result->fetchAll();

    $sql2 = "SELECT id, date, libelle, montant FROM `lignefraishorsforfait` where idVisiteur='$id' AND mois=?";
    $resulthf = $conn->prepare($sql2);
    $resulthf->bindParam(1, $date);
    $resulthf->execute();
    $rowhf = $resulthf->fetchAll();

    $query = "SELECT * FROM visiteur WHERE login = '$user'";
    $sth = $conn->prepare($query);
    $sth->execute();
    $users = $sth->fetchAll();

    foreach ($users as $row => $user) {
        $comptablecheck = $user['comptable'];
    }

    $sql3 = "SELECT * FROM fichefrais JOIN etat ON idEtat = etat.id WHERE idVisiteur= ? AND mois= ?";
    $result3 = $conn->prepare($sql3);
    $result3->bindParam(1, $id);
    $result3->bindParam(2, $date);
    $result3->execute();
    $etats = $result3->fetchAll();
    foreach ($etats as $etat) {
        $etatfiche = $etat['libelle'];
        $etatid = $etat['id'];
    }



?>
    <!DOCTYPE html>
    <html>

    <head>
        <title>PROFIL</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href='home.css' rel='stylesheet' type='text/css'>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
        <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/angular_material/0.8.3/angular-material.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.0.0/jspdf.umd.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.6/jspdf.plugin.autotable.min.js"></script>
    </head>

    <body>
        <div class="container">
            <div class="main-body">
                <div class="headrow gutters-sm">
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body header-card">
                                <?php
                                foreach ($users as $row => $user) { ?>
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
                                <?php
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
                            <li class="menu-item-normal"><a href="home.php"><i class="bi bi-pencil-square"></i>Saisie FicheFrais</a></li>
                            <li class="menu-item-normal"><a href="home-ligne.php"><i class="bi bi-pencil-square"></i>Saisie ligne Frais</a></li>
                            <li class="menu-item-normal"><a href="home-horsforfait.php"><i class="bi bi-pencil-square"></i>Saisie Frais Hors Forfait</a></li>
                            <li class="menu-item-normal"><a href="home-validation.php"><i class="bi bi-file-earmark-check"></i>Validation Fiches</a></li>
                            <li class="menu-item-active"><a href="consulterfrais.php"><i class="bi bi-file-earmark-spreadsheet-fill"></i>Consulter Frais</a></li>
                            <li class="menu-item-normal"><a href="stats.php"><i class="bi bi-file-earmark-bar-graph-fill"></i>Statistiques</a></li>
                            <li class="menu-item-normal"><a href="users.php"><i class="bi bi-people-fill"></i>Utilisateurs</a></li>
                        </ul>
                    </div>
                    <?php if ($comptablecheck == 0) { ?>
                        <div class='consult'>
                            <div class="">
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="row">
                                            <select class="month" name="date" onchange="location = this.value;">
                                                <option disabled selected value> -- Mois -- </option>
                                                <option value="consulterfrais.php?date=1">Janvier</option>
                                                <option value="consulterfrais.php?date=2">Février</option>
                                                <option value="consulterfrais.php?date=3">Mars</option>
                                                <option value="consulterfrais.php?date=4">Avril</option>
                                                <option value="consulterfrais.php?date=5">Mai</option>
                                                <option value="consulterfrais.php?date=6">Juin</option>
                                                <option value="consulterfrais.php?date=7">Juillet</option>
                                                <option value="consulterfrais.php?date=8">Août</option>
                                                <option value="consulterfrais.php?date=9">Septembre</option>
                                                <option value="consulterfrais.php?date=10">Octobre</option>
                                                <option value="consulterfrais.php?date=11">Novembre</option>
                                                <option value="consulterfrais.php?date=12">Décembre</option>
                                            </select>
                                            <?php if ($rows == null && $rowhr == null) { ?>
                                                <h3>Vous ne possédez pas une fiche pour le mois de <?= $month ?></h3>
                                            <?php } else { ?>
                                                <div class="etat">
                                                    <h3>Etat de la fiche : <?= $etatfiche ?></h3>
                                                </div>


                                                <?php if ($rows != null) { ?>
                                                    <div class="titre">
                                                        <h2>CONSULTATION DE FRAIS FORFAIT DU MOIS DE <?= $month ?></h2>
                                                        <?php foreach ($rows as $fraisfin) { ?>
                                                            <input id="mois" type="hidden" value="<?= $month ?>">
                                                            <input id="etat" type="hidden" value="<?= $etatfiche ?>">
                                                            <input id="libelle" type='hidden' value='<?= $fraisfin['libelle'] ?>'>
                                                            <input id="quantite" type='hidden' value='<?= $fraisfin['quantite'] ?>'>
                                                            <input id="montant" type='hidden' value='<?= $fraisfin['montant'] ?>'>
                                                            <input id="total" type='hidden' value='<?= $fraisfin['quantite*montant'] ?>'>
                                                        <?php }
                                                        foreach ($rowhf as $resulthf => $fraishffin) { ?>
                                                            <input id="date" type='hidden' value='<?= $fraishffin['date'] ?>'>
                                                            <input id="libellehf" type='hidden' value='<?= $fraishffin['libelle'] ?>'>
                                                            <input id="montanthf" type='hidden' value='<?= $fraishffin['montant'] ?>'>
                                                        <?php }
                                                        ?>
                                                        <button onclick="createPDF()" class="pdf"><i class="bi bi-cloud-arrow-down"></i> Download PDF</button>
                                                        </form>
                                                    </div>
                                                    <table id="frais-forfait">
                                                        <thead>
                                                            <tr class="w3-light-blue">
                                                                <th>Frais Forfait</th>
                                                                <th>Quantite</th>
                                                                <th>Prix Unitaire</th>
                                                                <th>Total</th>
                                                                <th>Supprimer</th>
                                                            </tr>
                                                        </thead>
                                                        <?php
                                                        foreach ($rows as $result => $fraisfin) {
                                                            echo "<tr><td class = 'libelle'>" . $fraisfin['libelle'] . "</td><td>" . $fraisfin['quantite'] . "</td><td>" . $fraisfin['montant'] . "</td><td>" . $fraisfin['quantite*montant'] .
                                                                "</td>";
                                                            if ($etatid == 'CR') {
                                                                echo "<td><a class='del' href='consulterfrais.php?type=delete&idfrais=" . $fraisfin['idFraisForfait'] . "&id=" . $id . "&mois=" . $date . "'><i class='bi bi-trash-fill'></i></a></td>";
                                                            } else {
                                                                echo "<td>Fiche " . $etatfiche . "</td>";
                                                            }
                                                            echo "</tr>";
                                                        }
                                                        ?>
                                                    </table>
                                                <?php } else { ?>
                                                    <h3>Vous n'avez pas renseignez de frais pour le mois de <?= $month ?></h3>
                                                <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="titre">
                                                <?php if ($rowhf != null) { ?>
                                                    <h2>CONSULTATION DE FRAIS HORS FORFAIT</h2>
                                            </div>
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
                                                            echo "<td><a class='del' href='consulterfrais.php?type=deletehf&idfrais=" . $fraishffin['id'] . "&id=" . $id . "&mois=" . $date . "'><i class='bi bi-trash-fill'></i></a></td>";
                                                        } else {
                                                            echo "<td>Fiche " . $etatfiche . "</td>";
                                                        }
                                                        echo "</tr>";
                                                    }
                                                } else { ?>
                                                <h3>Vous n'avez pas renseignez de frais hors forfait pour le mois de <?= $month ?></h3>
                                            <?php } ?>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
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

        function createPDF() {
            var doc = new jsPDF()
            doc.autoTable({ html: '#frais-forfait' })
            doc.autoTable({ html: '#frais-hors-forfait' })
            doc.text(150, 10,'Mois de :' + document.getElementById("mois").value);
            doc.text(10, 10, 'Etat de Fiche : ' + document.getElementById("etat").value);
            
            // doc.text(10, 10, document.getElementById("libelle").value);
            // doc.text(10, 10, document.getElementById("quantite").value);
            // doc.text(10, 10, document.getElementById("montant").value);
            // doc.text(10, 10, document.getElementById("total").value);
            // doc.text(10, 10, document.getElementById("libellehf").value);
            // doc.text(10, 10, document.getElementById("montanthf").value);

            doc.save("output.pdf");
        }
    </script>
<?php } else { ?>
    <div class="locksection">
        <div class="card mb-3">
            <div class="card-body">
                <h1><i class="bi bi-exclamation-triangle-fill"></i>Cet espace est reservé au visiteurs</h1>
                <img class="lock" src="images/lock.png">
            </div>
        </div>
    </div>
<?php }
                } else { ?>
<h3>Veuillez Vous Connecter <a href="login.php">ici</a></h3>
<?php } ?>

    </html>