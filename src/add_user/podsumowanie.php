<?php

session_start();

if ((!isset($_SESSION['zalogowany'])) || ($_SESSION['zalogowany'] == false)) {
    header('Location: ../index.php');
}

if (!isset($_SESSION['rola_uzytkownika']) || $_SESSION['rola_uzytkownika'] != 'administrator') {
    header('Location: ../notAllowed.php');
}

if( !isset($_SESSION['rejestracja']) )
{
    header('Location: ../index.php');
    exit();
}
else
{
    unset($_SESSION['rejestracja']);
}

//usuwamy zmienne z błędami rejestracji
if (isset($_SESSION['e_imie'])) unset($_SESSION['e_imie']);
if (isset($_SESSION['e_nazwisko'])) unset($_SESSION['e_nazwisko']);
if (isset($_SESSION['e_login'])) unset($_SESSION['e_login']);
if (isset($_SESSION['e_haslo'])) unset($_SESSION['e_haslo']);
if (isset($_SESSION['e_rola'])) unset($_SESSION['e_rola']);

//usuwamy zapisane wartości z formularza
if (isset($_SESSION['fr_imie'])) unset($_SESSION['fr_imie']);
if (isset($_SESSION['fr_nazwisko'])) unset($_SESSION['fr_nazwisko']);
if (isset($_SESSION['fr_login'])) unset($_SESSION['fr_login']);
if (isset($_SESSION['fr_haslo1'])) unset($_SESSION['fr_haslo1']);
if (isset($_SESSION['fr_haslo2'])) unset($_SESSION['fr_haslo2']);
if (isset($_SESSION['fr_rola'])) unset($_SESSION['fr_rola']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Panel logowania</title>

    <link href="../../css/panel.css" type="text/css" rel="stylesheet">
</head>
<body>

<div id="podsumowanie" class="panel-section">
    <div class="title-section">
        <p>Udało się dodać nowego użytkownika</p>
        <hr>
    </div>

    <div class="button-section">

    <p id="podsumowanie_header"> Oto jego dane:</p>

    <?php
    echo "<p>Imię: " . $_SESSION['r_imie'] . "</p>";

    echo "<p>Nazwisko: " . $_SESSION['r_nazwisko'] . "</p>";

    echo "<p>Login: " . $_SESSION['r_login'] . "</p>";

    echo "<div class='password-section'>";
    echo '<p> Hasło: <span id="haslo">' . $_SESSION['r_haslo'] . '</span></p>';
    echo '<button class="transparent-button show-password-button" id="pokaz_haslo"> Zobacz hasło </button>';
    echo "</div>";

    if ($_SESSION['r_rola'] == 1) {
        echo "<p> Rola: administrator </p>";
    } else if ($_SESSION['r_rola'] == 2) {
        echo "<p> Rola: pracownik </p>";
    } else if ($_SESSION['r_rola'] == 3) {
        echo "<p> Rola: auditor </p>";
    }

    ?>

    </div>

</div>

<a href="../panel.php" class="go-back-link"> Wróć do panelu </a>


<script type="text/javascript">
    document.getElementById("pokaz_haslo").addEventListener("click", show_password);
    visible = false;

    function show_password() {
        if (visible == false) {
            document.getElementById("haslo").style.visibility = "visible";
            visible = true;
            document.getElementById("pokaz_haslo").innerHTML = "Ukryj hasło";
        } else if (visible == true) {
            document.getElementById("haslo").style.visibility = "hidden";
            visible = false;
            document.getElementById("pokaz_haslo").innerHTML = "Zobacz hasło";
        }
    }

</script>

</body>
</html>