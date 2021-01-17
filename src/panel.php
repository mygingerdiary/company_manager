<?php

session_start();

if ((!isset($_SESSION['zalogowany'])) || ($_SESSION['zalogowany'] == false)) {
    header('Location: index.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Company Manager</title>
    <link href="../css/panel.css" type="text/css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Arbutus&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=ABeeZee&display=swap" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" rel="stylesheet">
</head>
<body>
<main>

    <form action="login/wyloguj.php" method="post">
        <button class="transparent-button" type="submit">Wyloguj się</button>
    </form>
    <div class="panel-section">

        <div class="title-section">
            <p>PODSYSTEMY</p>
            <hr>
        </div>

        <div class="button-section">

            <?php
            if (isset($_SESSION['rola_uzytkownika']) && $_SESSION['rola_uzytkownika'] == 'administrator') {
                echo '<button class="panel-button" onclick="window.location.href = \'add_user/addUserPanel.php\';">';
                echo '<span>stwórz użytkownika</span>';
                echo '<i class="fa fa-angle-right"></i>';
                echo '</button>';
            }
            ?>

            <button class="panel-button" onclick="window.location.href = 'invoices/salesInvoicePanel.php';">
                <span>podsystem katalogów faktur</span>
                <i class="fa fa-angle-right"></i>
            </button>
            <button class="panel-button" onclick="window.location.href = 'documents/documentsSystem.php';">
                <span>podsystem dokumentów</span>
                <i class="fa fa-angle-right"></i>
            </button>
            <button class="panel-button" onclick="window.location.href = 'devices/devicesCatalogPanel.php';">
                <span>podsystem katalogów sprzętu</span>
                <i class="fa fa-angle-right"></i>
            </button>
            <button class="panel-button" onclick="window.location.href = 'licenses/licencesCatalogPanel.php';">
                <span>podsystem licecji</span>
                <i class="fa fa-angle-right"></i>
            </button>

        </div>
    </div>

</main>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/2.1.3/TweenMax.min.js"></script>
<script src="../js/panel_animations.js"></script>
</body>
</html>
