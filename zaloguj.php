<?php
session_start();

if ((!isset($_POST['login'])) || (!isset($_POST['haslo'])))
{
    header('Location: index.php');
    exit();
}

$polaczenie = @new mysqli("localhost:8889", "root", "root","company_manager");

if ($polaczenie->connect_errno != 0)
{
    echo "ERROR: " . $polaczenie->connect_errno;
}
else
{
    $login = $_POST['login'];
    $haslo = $_POST['haslo'];

    $login = htmlentities($login, ENT_QUOTES, "UTF-8");
    $haslo = htmlentities($haslo, ENT_QUOTES, "UTF-8");


    if ($rezultat = @$polaczenie->query(
        sprintf("SELECT * FROM uzytkownicy WHERE BINARY login='%s' AND BINARY haslo='%s'",
            mysqli_real_escape_string($polaczenie, $login),
            mysqli_real_escape_string($polaczenie, $haslo))))
    {
        $ilu_userow = $rezultat->num_rows;
        if ($ilu_userow > 0)
        {
            $_SESSION['zalogowany'] = true;

            $wiersz = $rezultat->fetch_assoc();
            $_SESSION['id'] = $wiersz['id'];
            $_SESSION['login'] = $wiersz['login'];
            $_SESSION['imie'] = $wiersz['imie'];
            $_SESSION['nazwisko'] = $wiersz['nazwisko'];

            unset($_SESSION['blad']);
            $rezultat->free_result();
            header('Location: panel.php');
        }
        else
        {
            $_SESSION['blad'] = '<span style="color:red">Nieprawidlowy login lub hasło!</span>';
            header('Location: index.php');
        }
    }

    $polaczenie->close();
}


