<?php
session_start();

if ((!isset($_SESSION['zalogowany'])) || ($_SESSION['zalogowany'] == false)) {
    header('Location: ../index.php');
}

if (!isset($_SESSION['rola_uzytkownika']) || $_SESSION['rola_uzytkownika'] == 'auditor') {
    header('Location: ../notAllowed.php');
    exit();
}

$id = $_GET['id'];

const REMEMBER_IWENT = 'remember_iwent';
const REMEMBER_NAZWA = 'remember_nazwa';
const REMEMBER_OPIS = 'remember_opis';
const REMEMBER_KLUCZ = 'remember_klucz';
const REMEMBER_DATA = 'remember_data';
const REMEMBER_NR_FAKTURY = 'remember_nr_faktury';
const REMEMBER_GWARANCJA = 'remember_gwarancja';
const REMEMBER_NETTO = 'remember_netto';
const REMEMBER_NOTATKI = 'remember_notatki';
const REMEMBER_WLASCICIEL = 'remember_wlasciciel';


if (isset($_POST['nr_inwent'])) {
    //udana walidacja
    $ok = true;

    //NUMER INWENTARZOWY
    $nr_inwent = $_POST['nr_inwent'];

    //długość
    if (strlen($nr_inwent) == 0) {
        $ok = false;
        $_SESSION['e_nr_inwent'] = "Wprowadź numer inwentarzowy";
    }
    if (!preg_match('/^[a-zA-Z0-9\-\/]+/', $nr_inwent)) {
        $ok = false;
        $_SESSION['e_nr_inwent'] = "Numer inwentarzowy zawiera litery, cyfry lub znaki specjalne: - /";
    }

    //NAZWA
    $nazwa = $_POST['nazwa'];
    if (strlen($nazwa) == 0) {
        $ok = false;
        $_SESSION['e_nazwa'] = "Wprowadź nazwę";
    }

    //NR SERYJNY
    $nr_seryjny = $_POST['nr_seryjny'];
    if (strlen($nr_seryjny) == 0) {
        $ok = false;
        $_SESSION['e_nr_seryjny'] = "Wprowadź numer seryjny";
    }
    if (!preg_match('/^[a-zA-Z0-9\-\/]+/', $nr_seryjny)) {
        $ok = false;
        $_SESSION['e_nr_seryjny'] = "Numer seryjny zawiera litery, cyfry lub znaki specjalne: - /";
    }

    $date_now = date("Y-m-d");

    //DATA ZAKUPU
    $data_zakupu = $_POST['data_zakupu'];
    if (preg_match("/^[0-9]{4}/(0[1-9]|1[0-2])/(0[1-9]|[1-2][0-9]|3[0-1])$/", $data_zakupu)) {
        $ok = false;
        $_SESSION['e_data_zakupu'] = "Wprowadzona wartość musi być datą";
    }
    if ($data_zakupu > $date_now) {
        $ok = false;
        $_SESSION['e_data_zakupu'] = "Data zakupu nie może być większa niż dziś";
    }

    //DATA GWARANCJI
    $data_gwarancji = $_POST['data_gwarancji'];
    if (preg_match("/^[0-9]{4}/(0[1-9]|1[0-2])/(0[1-9]|[1-2][0-9]|3[0-1])$/", $data_gwarancji)) {
        $ok = false;
        $_SESSION['e_data_gwarancji'] = "Wprowadzona wartość musi być datą";
    }

    //WARTOSC NETTO
    $wartosc_netto = $_POST['wartosc_netto'];
    if ($wartosc_netto < 0) {
        $ok = false;
        $_SESSION['e_wartosc_netto'] = "Wprowadzona wartość musi być dodatnia";
    }
    if (!is_numeric($wartosc_netto)) {
        $ok = false;
        $_SESSION['e_wartosc_netto'] = "Wprowadzona wartość musi być liczbą";
    }

    $opis = $_POST['opis'];
    $nr_faktury = $_POST['faktury'];
    $notatki = $_POST['notatki'];
    $uzytkownik = $_POST['uzytkownicy'];


    //zapamietaj wprowadzone dane
    $_SESSION['fr_nr_inwent'] = $nr_inwent;
    $_SESSION['fr_nazwa'] = $nazwa;
    $_SESSION['fr_opis'] = $opis;
    $_SESSION['fr_nr_seryjny'] = $nr_seryjny;
    $_SESSION['fr_data_zakupu'] = $data_zakupu;
    $_SESSION['fr_nr_faktury'] = $nr_faktury;
    $_SESSION['fr_data_gwarancji'] = $data_gwarancji;
    $_SESSION['fr_wartosc_netto'] = $wartosc_netto;
    $_SESSION['fr_notatki'] = $notatki;
    $_SESSION['fr_uzytkownicy'] = $uzytkownik;
}

//BAZA
mysqli_report(MYSQLI_REPORT_STRICT);
require_once('../connect.php');

try {
    $conn = new mysqli($host, $db_user, $db_password, $db_name);

    if ($conn->connect_errno != 0) {
        echo "ERROR: " . $conn->connect_errno;
    } else {
        $results_u = $conn->query('SELECT * FROM uzytkownicy');

        $results_f = $conn->query('SELECT * FROM faktury');

        if ($ok == true) {
            if ($conn->query("UPDATE sprzety SET nr_inwentarzowy='$nr_inwent',nazwa= '$nazwa',opis= '$opis',nr_seryjny='$nr_seryjny',data_zakupu= '$data_zakupu',nr_faktury= '$nr_faktury',data_gwarancji= '$data_gwarancji',netto_pln= '$wartosc_netto',notatki= '$notatki',id_wlasciciela= '$uzytkownik')")) {
                header("Location: addDevice.php");
            } else {
                throw new Exception($conn->error);
            }
        }
    }


    $conn->close();
} catch (Exception $e) {
    echo '<span style="color: red;"> Błąd serwera. Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie </span>';
    echo '<br />Informacja developerska: ' . $e;
}

$db = new mysqli($host, $db_user, $db_password, $db_name);
$docs_select = "SELECT * FROM sprzety WHERE id=$id";
$docs_result = mysqli_query($db, $docs_select);

if ($docs_result->num_rows > 0) {
    while ($data = $docs_result->fetch_assoc()) {
        $_SESSION[REMEMBER_IWENT] = $data['nr_inwentarzowy'];
        $_SESSION[REMEMBER_NAZWA] = $data['nazwa'];
        $_SESSION[REMEMBER_OPIS] = $data['opis'];
        $_SESSION[REMEMBER_KLUCZ] = $data['nr_seryjny'];
        $_SESSION[REMEMBER_DATA] = $data['data_zakupu'];
        $_SESSION[REMEMBER_NR_FAKTURY] = $data['nr_faktury'];
        $_SESSION[REMEMBER_GWARANCJA] = $data['gwarancja_do'];
        $_SESSION[REMEMBER_NETTO] = $data['netto_pln'];
        $_SESSION[REMEMBER_NOTATKI] = $data['notatki'];
        $_SESSION[REMEMBER_WLASCICIEL] = $data['id_wlasciciela'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dodaj sprzet</title>
    <link href="../../css/panel.css" type="text/css" rel="stylesheet">
    <link href="../../css/add_panel.css" type="text/css" rel="stylesheet">
</head>
<body>
<div id="panel" class="panel-section">
    <div class="title-section">
        <h1> Edycja sprzętu </h1>
        <hr>
    </div>

    <div class="input-section">

        <form method="post">

            <p>
                Numer inwentarzowy
                <label>
                    <input type="text" name="nr_inwent" value= <?php
                    if (isset($_SESSION[REMEMBER_IWENT])) {
                        echo $_SESSION[REMEMBER_IWENT];
                        unset($_SESSION[REMEMBER_IWENT]);
                    }
                    ?>>
                </label>
            </p>

            <?php
            if (isset($_SESSION['e_nr_inwent'])) {
                echo '<div class="error">' . $_SESSION['e_nr_inwent'] . '</div>';
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
            if (isset($_SESSION['e_nazwa'])) {
                echo '<div class="error">' . $_SESSION['e_nazwa'] . '</div>';
                unset($_SESSION['e_nazwa']);
            }
            ?>

            <p>
                Opis:
                <br/>
                <textarea rows="4" cols="50" maxlength="1000" name="opis"><?php
                    if (isset($_SESSION[REMEMBER_OPIS])) {
                        echo $_SESSION[REMEMBER_OPIS];
                        unset($_SESSION[REMEMBER_OPIS]);
                    }
                    ?></textarea>
                <br/>
                *pole może pozostać puste
            </p>

            <p>
                Numer seryjny:
                <input type="text" name="nr_seryjny" value= <?php
                if (isset($_SESSION[REMEMBER_KLUCZ])) {
                    echo $_SESSION[REMEMBER_KLUCZ];
                    unset($_SESSION[REMEMBER_KLUCZ]);
                }
                ?>>
            </p>

            <?php
            if (isset($_SESSION['e_nr_seryjny'])) {
                echo '<div class="error">' . $_SESSION['e_nr_seryjny'] . '</div>';
                unset($_SESSION['e_nr_seryjny']);
            }
            ?>

            <p>
                Data zakupu:
                <input type="date" name="data_zakupu" value= <?php
                if (isset($_SESSION[REMEMBER_DATA])) {
                    echo $_SESSION[REMEMBER_DATA];
                    unset($_SESSION[REMEMBER_DATA]);
                }
                ?>>
            </p>

            <?php
            if (isset($_SESSION['e_data_zakupu'])) {
                echo '<div class="error">' . $_SESSION['e_data_zakupu'] . '</div>';
                unset($_SESSION['e_data_zakupu']);
            }
            ?>

            <p>
                <label for="faktury">Numer faktury:</label>
                <select name="faktury" id="faktury">
                    <?php
                    if ($results_f->num_rows > 0) {
                        while ($data = $results_f->fetch_assoc()) {
                            echo "<option value=" . $data['id'] . " ";
                            if (isset($_SESSION[REMEMBER_NR_FAKTURY]) && $_SESSION[REMEMBER_NR_FAKTURY] == $data['id']) {
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
                Gwarancja do:
                <input type="date" name="data_gwarancji" value="<?php
                if (isset($_SESSION[REMEMBER_GWARANCJA])) {
                    echo $_SESSION[REMEMBER_GWARANCJA];
                    unset($_SESSION[REMEMBER_GWARANCJA]);
                }
                ?>">
            </p>

            <?php
            if (isset($_SESSION['e_data_gwarancji'])) {
                echo '<div class="error">' . $_SESSION['e_data_gwarancji'] . '</div>';
                unset($_SESSION['e_data_gwarancji']);
            }
            ?>

            <p>
                Wartość netto:
                <input type="number" step="0.01" name="wartosc_netto" value="<?php
                if (isset($_SESSION[REMEMBER_NETTO])) {
                    echo $_SESSION[REMEMBER_NETTO];
                    unset($_SESSION[REMEMBER_NETTO]);
                }
                ?>">
            </p>

            <?php
            if (isset($_SESSION['e_wartosc_netto'])) {
                echo '<div class="error">' . $_SESSION['e_wartosc_netto'] . '</div>';
                unset($_SESSION['e_wartosc_netto']);
            }
            ?>

            <p>
                <label for="uzytkownicy">Na czyim stanie:</label>
                <select name="uzytkownicy" id="uzytkownicy">
                    <?php
                    if ($results_u->num_rows > 0) {
                        while ($data = $results_u->fetch_assoc()) {
                            echo "<option value=" . $data['id'] . " ";
                            if (isset($_SESSION[REMEMBER_WLASCICIEL]) && $_SESSION[REMEMBER_WLASCICIEL] == $data['id']) {
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
                <br/>
                <textarea rows="4" cols="50" maxlength="1000" name="notatki"><?php
           if (isset($_SESSION[REMEMBER_NOTATKI])) {
               echo $_SESSION[REMEMBER_NOTATKI];
               unset($_SESSION[REMEMBER_NOTATKI]);
           }
           ?> </textarea>
                <br/>
                *pole może pozostać puste
            </p>

            <input class="transparent-button" type="submit" value="Aktualizuj">

        </form>

    </div>

</div>


<a href="../panel.php" class="go-back-link"> Wróć do panelu </a>

<a href="devicesCatalogPanel.php" class="go-back-link"> Cofnij </a>

</body>
</html>