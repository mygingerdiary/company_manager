<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Company Manager</title>
    <link href="style_inside.css" type="text/css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Arbutus&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=ABeeZee&display=swap" rel="stylesheet">
</head>
<body>
<div id="rejestracja">
    <h1> Dodawanie nowego użytkownika systemu </h1>
    <form action="createUser.php" method="post">
        <input type="text" name="imie" placeholder="Imię">
        <input type="text" name="nazwisko" placeholder="Nazwisko">
        <input type="text" name="login" placeholder="Login">
        <input type="text" name="haslo" placeholder="Hasło">
        <div id="typeOfUser">
            <input type='radio' name='radio' value='1' id="admin"/>
            <label for="admin">administrator</label>
            <input type='radio' name='radio' value='2' id="pracownik"/>
            <label for="pracownik">pracownik</label>
            <input type='radio' name='radio' value='3' id="auditor"/>
            <label for="auditor">auditor</label>
        </div>
        <button type="submit">Dodaj</button>
    </form>
</div>
</body>
</html>
