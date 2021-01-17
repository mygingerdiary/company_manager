<?php
session_start();

if ((!isset($_POST['login'])) || (!isset($_POST['haslo'])))
{
    header('Location: ../index.php');
    exit();
}

require_once('../connect.php');
$polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);

if ($polaczenie->connect_errno != 0)
{
    echo "ERROR: " . $polaczenie->connect_errno;
}
else
{
    $login = $_POST['login'];
    $haslo = $_POST['haslo'];

    $login = htmlentities($login, ENT_QUOTES, "UTF-8");

    if ($rezultat = @$polaczenie->query(
        sprintf("SELECT * FROM uzytkownicy WHERE BINARY login='%s'",
            mysqli_real_escape_string($polaczenie, $login))))
    {
        $ilu_userow = $rezultat->num_rows;
        if ($ilu_userow > 0)
        {
            $wiersz = $rezultat->fetch_assoc();

            if(password_verify($haslo, $wiersz['haslo']))
            {
                $_SESSION['id_roli'] = $wiersz['id_roli'];
                $_SESSION['id'] = $wiersz['id'];
                $_SESSION['login'] = $wiersz['login'];
                $_SESSION['imie'] = $wiersz['imie'];
                $_SESSION['nazwisko'] = $wiersz['nazwisko'];

                if ($_POST['rola'] == 1 && $_SESSION['id_roli'] == 1)
                {
                    $_SESSION['zalogowany'] = true;
                    $_SESSION['rola_uzytkownika'] = 'administrator';
                    header('Location: ../panel.php');
                    unset($_SESSION['blad']);
                    $rezultat->free_result();
                }
                elseif ($_POST['rola'] == 2 && $_SESSION['id_roli'] == 2)
                {
                    $_SESSION['zalogowany'] = true;
                    $_SESSION['rola_uzytkownika'] = 'pracownik';
                    header('Location: ../panel.php');
                    unset($_SESSION['blad']);
                    $rezultat->free_result();
                }
                elseif ($_POST['rola'] == 3 && $_SESSION['id_roli'] == 3)
                {
                    $_SESSION['zalogowany'] = true;
                    $_SESSION['rola_uzytkownika'] = 'auditor';
                    header('Location: ../panel.php');
                    unset($_SESSION['blad']);
                    $rezultat->free_result();
                }
                else
                {
                    $_SESSION['blad'] = '<span style="color:red">Nie posiadasz uprawnień!</span>';
                    header('Location: ../index.php');
                }
            }
            else
            {
                $_SESSION['blad'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';
                header('Location: ../index.php');
            }
        }
        else
        {
            $_SESSION['blad'] = '<span style="color:red">Nieprawidlowy login lub hasło!</span>';
            header('Location: ../index.php');
        }
    }

    $polaczenie->close();
}


