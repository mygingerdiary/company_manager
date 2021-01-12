<?php
session_start();
$id = $_GET['id'];
const REMEMBER_IWENT= 'remember_iwent';
const REMEMBER_NAZWA = 'remember_nazwa';
const REMEMBER_OPIS = 'remember_opis';
const REMEMBER_KLUCZ = 'remember_klucz';
const REMEMBER_DATA= 'remember_data';
const REMEMBER_NR_FAKTURY = 'remember_faktura';
const REMEMBER_WSPARCIE = 'remember_wsparcie';
const REMEMBER_LICENCJA = 'remember_licencja';
const REMEMBER_NOTATKI = 'remember_notatki';
const REMEMBER_WLASCICIEL = 'remember_wlasciciel';
const REMEMBER_FAKTURA = 'remember_wlasciciel';


if(isset($_POST['nr_inwent']))
{
    //udana walidacja
    $ok = true;

    //NUMER INWENTARZOWY
    $nr_inwent = $_POST['nr_inwent'];

    //długość
    if( strlen($nr_inwent) == 0 )
    {
        $ok = false;
        $_SESSION['e_nr_inwent'] = "Wprowadź numer inwentarzowy";
    }
    if(!preg_match('/^[a-zA-Z0-9\-\/]+/', $nr_inwent))
    {
        $ok = false;
        $_SESSION['e_nr_inwent'] = "Numer inwentarzowy zawiera litery, cyfry lub znaki specjalne: - /";
    }

    //NAZWA
    $nazwa = $_POST['nazwa'];
    if( strlen($nazwa) == 0 )
    {
        $ok = false;
        $_SESSION['e_nazwa'] = "Wprowadź nazwę";
    }

    //NR SERYJNY
    $nr_seryjny = $_POST['nr_seryjny'];
    if( strlen($nr_seryjny) == 0 )
    {
        $ok = false;
        $_SESSION['e_nr_seryjny'] = "Wprowadź numer seryjny";
    }
    if(!preg_match('/^[a-zA-Z0-9\-\/]+/', $nr_seryjny))
    {
        $ok = false;
        $_SESSION['e_nr_seryjny'] = "Numer seryjny zawiera litery, cyfry lub znaki specjalne: - /";
    }

    $date_now = date("Y-m-d");

    //DATA ZAKUPU
    $data_zakupu = $_POST['data_zakupu'];
    list($yyyy, $mm, $dd) = explode('-', $data_zakupu);
    if (!checkdate($mm,$dd,$yyyy)) {
        $ok = false;
        $_SESSION['e_data_zakupu'] = "Wprowadzona wartość musi być datą";
    }

    if($data_zakupu > $date_now)
    {
        $ok = false;
        $_SESSION['e_data_zakupu'] = "Data zakupu nie może być większa niż dziś";
    }

    //DATA WSPARCIA
    $data_wsparcia= $_POST['data_wsparcia'];
    list($yyyy_dw, $mm_dw, $dd_dw) = explode('-', $data_wsparcia);
    if (!checkdate($mm,$dd,$yyyy)) {
        $ok = false;
        $_SESSION['e_data_wsparcia'] = "Wprowadzona wartość musi być datą";
    }
    if($data_wsparcia < $data_zakupu)
    {
        $ok = false;
        $_SESSION['e_data_wsparcia'] = "Data wsparcia nie może być wcześniejsza niż data zakupu";
    }

    //DATA WAZNOSCI
    $opcja = $_POST['waznosc_licencji'];

    if(!isset($opcja))
    {
        $ok = false;
        $_SESSION['e_data_waznosci'] = "Konieczne jest zaznaczenie opcji";
    }
    if($opcja == "bezterminowo")
    {
        $data_waznosci = "NULL";
    }
    else if($opcja == "data")
    {
        $data_waznosci = $_POST['data_waznosci'];
        list($yyyy, $mm, $dd) = explode('-', $data_waznosci);
        if (!checkdate($mm,$dd,$yyyy)) {
            $ok = false;
            $_SESSION['e_data_waznosci'] = "Wprowadzona wartość musi być datą";
        }
        if($data_waznosci < $data_zakupu)
        {
            $ok = false;
            $_SESSION['e_data_waznosci'] = "Data, do której licencja jest wspierana nie może być wcześniejsza niż data zakupu";
        }
        $data_waznosci = "'$data_waznosci'";
    }


    $opis = $_POST['opis'];
    $nr_faktury = $_POST['faktury'];
    if(!isset($nr_faktury))
    {
        $nr_faktury = "NULL";
    }
    else
    {
        $nr_faktury = "'$nr_faktury'";
    }

    $notatki = $_POST['notatki'];
    $uzytkownik = $_POST['uzytkownicy'];


    //zapamietaj wprowadzone dane
    $_SESSION['fr_nr_inwent'] = $nr_inwent;
    $_SESSION['fr_nazwa'] = $nazwa;
    $_SESSION['fr_opis'] = $opis;
    $_SESSION['fr_nr_seryjny'] = $nr_seryjny;
    $_SESSION['fr_data_zakupu'] = $data_zakupu;
    $_SESSION['fr_nr_faktury'] = $nr_faktury;
    $_SESSION['fr_data_wsparcia'] = $data_wsparcia;
    $_SESSION['fr_waznosc_opcja'] = $_POST['waznosc_licencji'];
    $_SESSION['fr_data_waznosci'] = $data_waznosci;
    $_SESSION['fr_notatki'] = $notatki;
    $_SESSION['fr_uzytkownicy'] = $uzytkownik;
}

//BAZA
mysqli_report(MYSQLI_REPORT_STRICT);

try
{
    $conn = new mysqli($host, $db_user, $db_password, $db_name);

    if ($conn->connect_errno != 0)
    {
        echo "ERROR: " . $conn->connect_errno;
    }
    else {
        $results_u = $conn->query('SELECT * FROM uzytkownicy');

        $results_f = $conn->query('SELECT * FROM faktury');

        if($ok == true)
        {
            if($conn->query("UPDATE licencje SET nr_inwentarzowy='$nr_inwent', nazwa='$nazwa',opis='$opis',klucz_seryjny='$nr_seryjny',data_zakupu='$data_zakupu',id_faktury=$nr_faktury,wsparcie_do='$data_wsparcia',licencja_do=$data_waznosci,notatki='$notatki', id_wlasciciela='$uzytkownik'  WHERE id=$id"))
            {
                header("Location: licencesCatalogPanel.php");
            }
            else
            {
                throw new Exception($conn->error);
            }

        }
    }


    $conn->close();
}
catch (Exception $e)
{
    echo '<span style="color: red;"> Błąd serwera. Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie </span>';
    echo '<span style="color: red;">'. $nr_inwent . $nazwa . $opis . $nr_seryjny . $data_zakupu . $nr_faktury . $data_wsparcia . $data_waznosci . $notatki . $uzytkownik . '</span>';
    echo '<br />Informacja developerska: '.$e;
}

$db = mysqli_connect("localhost", "root", "", "company_manager3");
$docs_select = "SELECT * FROM licencje WHERE id=$id";
$docs_result = mysqli_query($db, $docs_select);

if ($docs_result->num_rows > 0) {
    while ($data = $docs_result->fetch_assoc()) {
        $_SESSION[REMEMBER_IWENT] = $data['nr_inwentarzowy'];
        $_SESSION[REMEMBER_NAZWA] = $data['nazwa'];
        $_SESSION[REMEMBER_OPIS] = $data['opis'];
        $_SESSION[REMEMBER_KLUCZ] = $data['klucz_seryjny'];
        $_SESSION[REMEMBER_DATA] = $data['data_zakupu'];
        $_SESSION[REMEMBER_NR_FAKTURY] = $data['id_faktury'];
        $_SESSION[REMEMBER_WSPARCIE] = $data['wsparcie_do'];
        $_SESSION[REMEMBER_LICENCJA] = $data['licencja_do'];
        $_SESSION[REMEMBER_NOTATKI] = $data['notatki'];
        $_SESSION[REMEMBER_WLASCICIEL] = $data['id_wlasciciela'];


    }
}
if($_SESSION[REMEMBER_LICENCJA] == NULL)
{
    $_SESSION['fr_waznosc_opcja'] = "bezterminowo";
}
else
{
    $_SESSION['fr_waznosc_opcja'] = "data";
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edytuj licencje</title>
    <link href="style_inside.css" type="text/css" rel="stylesheet">
</head>
<body>

<script type="text/javascript">
    window.onload = checkVisibility;

    function checkVisibility()
    {
        if(document.getElementById("data").checked)
        {
            document.getElementById("data_waznosci").style = "visibility: visible;";
        }
        else if (document.getElementById("bezterminowo").checked)
        {
            document.getElementById("data_waznosci").style= "visibility: hidden;";
        }
    }

</script>

<div id="panel">

    <h1> Edytowanie licencje </h1>

    <form method="post">

        <p>
            Numer inwentarzowy
            <label>
                <input type="text" name="nr_inwent" value= <?php
                if (isset($_SESSION[REMEMBER_IWENT])) {
                    echo $_SESSION[REMEMBER_IWENT];
                    unset($_SESSION[REMEMBER_IWENT]);
                }
                ?> >
                </label>
        </p>

        <?php
        if(isset($_SESSION['e_nr_inwent']))
        {
            echo '<div class="error">'.$_SESSION['e_nr_inwent'].'</div>';
            unset($_SESSION['e_nr_inwent']);
        }
        ?>

        <p>
            Nazwa:
            <label>
                <input type="text" name="nazwa" value= <?php
                if (isset($_SESSION[REMEMBER_NAZWA])) {
                    echo $_SESSION[REMEMBER_NAZWA];
                    unset($_SESSION[REMEMBER_NAZWA]);
                }
                ?>
                >
            </label>
        </p>

        <?php
        if(isset($_SESSION['e_nazwa']))
        {
            echo '<div class="error">'.$_SESSION['e_nazwa'].'</div>';
            unset($_SESSION['e_nazwa']);
        }
        ?>

        <p>
            Opis:
            <br />
            <textarea rows="4" cols="50" maxlength="1000" name="opis"><?php
                if(isset($_SESSION[REMEMBER_OPIS]))
                {
                    echo $_SESSION[REMEMBER_OPIS];
                    unset($_SESSION[REMEMBER_OPIS]);
                }
                ?></textarea>
            <br />
            *pole może pozostać puste
        </p>

        <p>
            Klucz seryjny:
            <input type="text" name="nr_seryjny" value= <?php
            if (isset($_SESSION[REMEMBER_KLUCZ])) {
                echo $_SESSION[REMEMBER_KLUCZ];
                unset($_SESSION[REMEMBER_KLUCZ]);
            }
            ?> >
        </p>

        <?php
        if(isset($_SESSION['e_nr_seryjny']))
        {
            echo '<div class="error">'.$_SESSION['e_nr_seryjny'].'</div>';
            unset($_SESSION['e_nr_seryjny']);
        }
        ?>

        <p>
            Data zakupu:
            <input type="date" name="data_zakupu"  value= <?php
            if (isset($_SESSION[REMEMBER_DATA])) {
                echo $_SESSION[REMEMBER_DATA];
                unset($_SESSION[REMEMBER_DATA]);
            }
            ?> >
        </p>

        <?php
        if(isset($_SESSION['e_data_zakupu']))
        {
            echo '<div class="error">'.$_SESSION['e_data_zakupu'].'</div>';
            unset($_SESSION['e_data_zakupu']);
        }
        ?>

        <p>
            <label for="faktury">Numer faktury:</label>
            <select name="faktury" id="faktury">
                <?php
                if ($results_f->num_rows > 0)
                {
                    while ($data = $results_f->fetch_assoc())
                    {
                        echo "<option value=" . $data['id'] . " ";
                        if(isset($_SESSION[REMEMBER_NR_FAKTURY]) && $_SESSION[REMEMBER_NR_FAKTURY] == $data['id'])
                        {
                            echo "selected";

                            unset($_SESSION[REMEMBER_NR_FAKTURY]);
                        }
                        echo ">" . $data['nr_faktury'] . "</option>/n";
                    }
                }
                ?>
            </select>
        </p>

        <p>
            Wsparcie techniczne do:
            <input type="date" name="data_wsparcia" value= <?php
            if (isset($_SESSION[REMEMBER_WSPARCIE])) {
                echo $_SESSION[REMEMBER_WSPARCIE];
                unset($_SESSION[REMEMBER_WSPARCIE]);
            }
            ?> >
        </p>

        <?php
        if(isset($_SESSION['e_data_wsparcia']))
        {
            echo '<div class="error">'.$_SESSION['e_data_wsparcia'].'</div>';
            unset($_SESSION['e_data_wsparcia']);
        }
        ?>

        <p id="waznosc_licencji">
            Licencja ważna do:
            <input type="radio" id="bezterminowo" name="waznosc_licencji" value="bezterminowo" id="bezterminowo" onclick="checkVisibility()"
                <?php
                if(isset($_SESSION['fr_waznosc_opcja']) && $_SESSION['fr_waznosc_opcja'] == "bezterminowo")
                {
                    echo "checked";
                    unset($_SESSION['fr_waznosc_opcja']);
                }
                ?>
            >
            <label for="bezterminowo">bezterminowo</label>
            <input type="radio" id="data" name="waznosc_licencji" value="data" id="data" onclick="checkVisibility()"
                <?php
                if(isset($_SESSION['fr_waznosc_opcja']) && $_SESSION['fr_waznosc_opcja'] == "data")
                {
                    echo "checked";
                    unset($_SESSION['fr_waznosc_opcja']);
                }
                ?>
            >
            <label for="data">do konkretnej daty</label>
            <input type="date" name="data_waznosci" id="data_waznosci" value="<?php
            if(isset($_SESSION[REMEMBER_LICENCJA]))
            {
                echo $_SESSION[REMEMBER_LICENCJA];
                unset($_SESSION[REMEMBER_LICENCJA]);
            }
            ?>">
        </>

        <?php
        if(isset($_SESSION['e_data_waznosci']))
        {
            echo '<div class="error">'.$_SESSION['e_data_waznosci'].'</div>';
            unset($_SESSION['e_data_waznosci']);
        }
        ?>

        <p>
            <label for="uzytkownicy">Na czyim stanie:</label>
            <select name="uzytkownicy" id="uzytkownicy">
                <?php
                if ($results_u->num_rows > 0)
                {
                    while ($data = $results_u->fetch_assoc())
                    {
                        echo "<option value=" . $data['id'] . " ";
                        if(isset($_SESSION[REMEMBER_WLASCICIEL]) && $_SESSION[REMEMBER_WLASCICIEL] == $data['id'])
                        {
                            echo "selected";
                            unset($_SESSION[REMEMBER_WLASCICIEL]);
                        }
                        echo ">" . $data['imie'] . " " . $data['nazwisko'] . "</option>/n";
                    }
                }
                ?>
            </select>
        </p>

        <p>
            Notatki:
            <br />
            <textarea rows="4" cols="50" maxlength="1000" name="notatki">
           <?php
                if (isset($_SESSION[REMEMBER_NOTATKI])) {
                    echo $_SESSION[REMEMBER_NOTATKI];
                    unset($_SESSION[REMEMBER_NOTATKI]);
                }
                ?> </textarea>
            <br />
            *pole może pozostać puste
        </p>

        <input type="submit" value="Dodaj licencję">

        <a href="panel.php"> Wróć do panelu </a>

        <a href="licencesCatalogPanel.php"> Cofnij </a>

    </form>

</div>

</body>
</html>