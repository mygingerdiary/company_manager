<?php
session_start();

if ((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany'] == true)) {
    header('Location: panel.php');
    exit(); //opuszczamy plik, nie wykonujemy dalszej czesci kodu
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Company Manager</title>
    <link href="style.css" type="text/css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Arbutus&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=ABeeZee&display=swap" rel="stylesheet">
</head>
<body>

<div id="login_panel">

    <h2 class="naglowek">Logowanie</h2>

    <form action="zaloguj.php" method="post" class="pola_logowania">
        <input type="text" placeholder="login..." name="login" required>
        <input type="password" placeholder="hasło..." name="haslo" required>

        <div>
            <input type='radio' name='radio' value='1' id="admin"/>
            <label for="admin">administrator</label>
            <input type='radio' name='radio' value='2' id="pracownik"/>
            <label for="pracownik">pracownik</label>
            <input type='radio' name='radio' value='3' id="auditor"/>
            <label for="auditor">auditor</label>
        </div>

        <button type="submit">Zaloguj się</button>

        <?php
        if (isset($_SESSION['blad'])) {
            echo "</br>" . $_SESSION['blad'];
            unset($_SESSION['blad']);
        }
        ?>
    </form>

</div>

<p> Company Manager 2020 &copy; </p>

</body>
</html>
