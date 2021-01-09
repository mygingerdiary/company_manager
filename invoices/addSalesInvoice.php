<?php
session_start();

const ERROR_NR_FAKTURY = 'error_nr_faktury';
const ERROR_NETTO = 'error_netto';
const ERROR_VAT = 'error_vat';
const ERROR_BRUTTO = 'error_brutto';
const ERROR_WALUTA = 'error_waluta';
const ERROR_NAZWA_KONTRAHENTA = 'error_nazwa_kontrahenta';
const ERROR_VAT_KONTRAHENTA = 'error_vat_kontrahenta';

const REMEMBER_NR_FAKTURY = 'remember_nr_faktury';
const REMEMBER_NETTO = 'remember_netto';
const REMEMBER_VAT = 'remember_vat';
const REMEMBER_BRUTTO = 'remember_brutto';
const REMEMBER_WALUTA = 'remember_waluta';
const REMEMBER_NAZWA_KONTRAHENTA = 'remember_nazwa_kontrahenta';
const REMEMBER_VAT_KONTRAHENTA = 'remember_vat_kontrahenta';

const ADDED_INVOICE_ID = 'added_invoice_id';

require_once('../connect.php');
mysqli_report(MYSQLI_REPORT_STRICT);
$conn = new mysqli($host, $db_user, $db_password, $db_name);

function validate_invoice_number($nr_faktury)
{
    if (!is_numeric($nr_faktury)) {
        $_SESSION[ERROR_NR_FAKTURY] = "Nieprawidłowy format numeru faktury";
        return false;
    }
    return true;
}

function validate_netto($netto)
{
    if (!is_numeric($netto)) {
        $_SESSION[ERROR_NETTO] = "Kwota netto nie jest liczbą";
        return false;
    }
    return true;
}

function validate_vat($vat)
{
    if (!is_numeric($vat) || $vat < 1 || $vat > 100) {
        $_SESSION[ERROR_VAT] = "Vat musi być wartością 1-100";
        return false;
    }

    return true;
}

function validate_brutto($brutto)
{
    if (!is_numeric($brutto)) {
        $_SESSION[ERROR_BRUTTO] = "Kwota brutto nie jest liczbą";
        return false;
    }

    return true;
}

function validate_waluta($waluta)
{
    if (!ctype_alpha($waluta)) {
        $_SESSION[ERROR_WALUTA] = "Waluta musi być tekstem nie zawierającym cyfr";
        return false;
    } else if (strlen($waluta) < 2) {
        $_SESSION[ERROR_WALUTA] = "Waluta zawierać conajmniej 2 znaki";
        return false;
    }

    return true;
}

function validate_nazwa_kontrahenta($nazwa_kontrahenta)
{
    if (strlen($nazwa_kontrahenta) < 3) {
        $_SESSION[ERROR_NAZWA_KONTRAHENTA] = "Nazwa kontrahenta musi mieć długość conajmniej 3 znaków";
        return false;
    }
    return true;
}

function validate_vat_kontrahenta($vat_kontrahenta)
{
    if (!is_numeric($vat_kontrahenta)) {
        $_SESSION[ERROR_VAT_KONTRAHENTA] = "Vat musi zawierać same cyfry";
        return false;
    }

    return true;
}

if ($conn->connect_errno != 0) {
    echo "ERROR: " . $conn->connect_errno;
} else {
    $waluty_select = 'SELECT * FROM waluty';
    $waluty_result = mysqli_query($conn, $waluty_select);
}

if ($conn->connect_errno == 0 && isset($_POST['nr_faktury'])) {
    $valid = true;

    $nr_faktury = $_POST['nr_faktury'];
    $netto = $_POST['netto'];
    $vat = $_POST['vat'];
    $brutto = $_POST['brutto'];
    $waluta = $_POST['waluta'];
    $nazwa_kontrahenta = $_POST['nazwa_kontrahenta'];
    $vat_kontrahenta = $_POST['vat_kontrahenta'];
    $rodzaj = $_POST['rodzaj'];


    if (validate_invoice_number($nr_faktury) &&
        validate_netto($netto) &&
        validate_vat($vat) &&
        validate_brutto($brutto) &&
        validate_waluta($waluta) &&
        validate_nazwa_kontrahenta($nazwa_kontrahenta) &&
        validate_vat_kontrahenta($vat_kontrahenta)) {
        $valid = true;
    } else {
        $valid = false;
    }

    // remember data
    $_SESSION[REMEMBER_NR_FAKTURY] = $nr_faktury;
    $_SESSION[REMEMBER_NETTO] = $netto;
    $_SESSION[REMEMBER_VAT] = $vat;
    $_SESSION[REMEMBER_BRUTTO] = $brutto;
    $_SESSION[REMEMBER_WALUTA] = $waluta;
    $_SESSION[REMEMBER_NAZWA_KONTRAHENTA] = $nazwa_kontrahenta;
    $_SESSION[REMEMBER_VAT_KONTRAHENTA] = $vat_kontrahenta;

    try {
        if (!check_if_vault_exists($conn, $waluta)) {
            $valid = false;
        }

        if ($valid) {
            $kontrahent_id = add_contractor_if_not_existing($conn, $nazwa_kontrahenta, $vat_kontrahenta);
            $waluta_id = get_vault_id($conn, $waluta);

            echo("<script>console.log('PHP: " . $kontrahent_id . "');</script>");

            if ($conn->query("INSERT INTO faktury " .
                "(nr_faktury, netto, vat, brutto, waluta, kontrahent_id, id_dokumentu, rodzaj) " .
                "VALUES('$nr_faktury', $netto, $vat, $brutto, $waluta_id, $kontrahent_id, NULL, $rodzaj);")) {

                $id_select_query = "SELECT id FROM faktury WHERE nr_faktury=$nr_faktury AND kontrahent_id=$kontrahent_id LIMIT 1";
                $id_result = mysqli_query($conn, $id_select_query);
                if ($id_result->num_rows > 0) {
                    while ($data = $id_result->fetch_assoc()) {
                        $_SESSION[ADDED_INVOICE_ID] = $data['id'];
                    }
                }
                header("Location: chooseDocument.php");
            } else {
                throw new Exception($conn->error);
            }
        }

        $conn->close();
    } catch (Exception $e) {
        echo '<span style="color: red;"> Błąd serwera. Przepraszamy za niedogodności </span>';
        echo '<br />Informacja developerska: ' . $e;
    }
}

function check_if_vault_exists($conn, $waluta)
{
    $result = $conn->query("SELECT id FROM waluty WHERE nazwa = '$waluta'");
    if (mysqli_num_rows($result) == 0) {
        $_SESSION[ERROR_WALUTA] = "Taka waluta nie istnieje";
        return false;
    }

    return true;
}

function get_vault_id($conn, $waluta)
{
    $result = $conn->query("SELECT id FROM waluty WHERE nazwa = '$waluta'");
    if ($result->num_rows > 0) {
        while ($data = $result->fetch_assoc()) {
            return $data['id'];
        }
    }
}

function add_contractor_if_not_existing($conn, $nazwa_kontrahenta, $vat_kontrahenta)
{
    $sample = $conn->query("SELECT id FROM kontrahenci WHERE vat_id = $vat_kontrahenta");
    if (mysqli_num_rows($sample) == 0) {
        $conn->query("INSERT INTO kontrahenci (nazwa, vat_id) VALUES ('$nazwa_kontrahenta', $vat_kontrahenta)");
    }

    $result = $conn->query("SELECT id FROM kontrahenci WHERE vat_id = $vat_kontrahenta");
    if ($result->num_rows > 0) {
        while ($data = $result->fetch_assoc()) {
            return $data['id'];
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Company Manager</title>
    <link href="../style_inside.css" type="text/css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Arbutus&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=ABeeZee&display=swap" rel="stylesheet">
</head>
<body>
<div class="add-invoice-panel">
    <h2>Dodaj fakturę</h2>
    <form method="post">
        <p>
            Numer faktury:
            <input type="text" name="nr_faktury" placeholder="nr faktury" value="<?php
            if (isset($_SESSION[REMEMBER_NR_FAKTURY])) {
                echo $_SESSION[REMEMBER_NR_FAKTURY];
                unset($_SESSION[REMEMBER_NR_FAKTURY]);
            }
            ?>">
        </p>
        <?php
        if (isset($_SESSION[ERROR_NR_FAKTURY])) {
            echo '<div class="error">' . $_SESSION[ERROR_NR_FAKTURY] . '</div>';
            unset($_SESSION[ERROR_NR_FAKTURY]);
        }
        ?>

        <p>
            Kwota netto:
            <input type="text" name="netto" placeholder="netto" value="<?php
            if (isset($_SESSION[REMEMBER_NETTO])) {
                echo $_SESSION[REMEMBER_NETTO];
                unset($_SESSION[REMEMBER_NETTO]);
            }
            ?>">
        </p>
        <?php
        if (isset($_SESSION[ERROR_NETTO])) {
            echo '<div class="error">' . $_SESSION[ERROR_NETTO] . '</div>';
            unset($_SESSION[ERROR_NETTO]);
        }
        ?>

        <p>
            Vat:
            <input type="text" name="vat" placeholder="vat" value="<?php
            if (isset($_SESSION[REMEMBER_VAT])) {
                echo $_SESSION[REMEMBER_VAT];
                unset($_SESSION[REMEMBER_VAT]);
            }
            ?>">
        </p>
        <?php
        if (isset($_SESSION[ERROR_VAT])) {
            echo '<div class="error">' . $_SESSION[ERROR_VAT] . '</div>';
            unset($_SESSION[ERROR_VAT]);
        }
        ?>

        <p>
            Kwota brutto:
            <input type="text" name="brutto" placeholder="brutto" value="<?php
            if (isset($_SESSION[REMEMBER_BRUTTO])) {
                echo $_SESSION[REMEMBER_BRUTTO];
                unset($_SESSION[REMEMBER_BRUTTO]);
            }
            ?>">
        </p>
        <?php
        if (isset($_SESSION[ERROR_BRUTTO])) {
            echo '<div class="error">' . $_SESSION[ERROR_BRUTTO] . '</div>';
            unset($_SESSION[ERROR_BRUTTO]);
        }
        ?>

        <p>
            Waluta:
            <input list="waluta" name="waluta" value="<?php
            if (isset($_SESSION[REMEMBER_WALUTA])) {
                echo $_SESSION[REMEMBER_WALUTA];
                unset($_SESSION[REMEMBER_WALUTA]);
            }
            ?>" required>
            <datalist id="waluta">
                <?php

                if ($waluty_result->num_rows > 0) {
                    while ($data = $waluty_result->fetch_assoc()) {
                        echo "<option value='" . $data['nazwa'] . "'>\n";
                    }
                }
                ?>
            </datalist>
        </p>
        <?php
        if (isset($_SESSION[ERROR_WALUTA])) {
            echo '<div class="error">' . $_SESSION[ERROR_WALUTA] . '</div>';
            unset($_SESSION[ERROR_WALUTA]);
        }
        ?>

        <p>
            Nazwa kontrahenta:
            <input type="text" name="nazwa_kontrahenta" placeholder="nazwa_kontrahenta" value="<?php
            if (isset($_SESSION[REMEMBER_NAZWA_KONTRAHENTA])) {
                echo $_SESSION[REMEMBER_NAZWA_KONTRAHENTA];
                unset($_SESSION[REMEMBER_NAZWA_KONTRAHENTA]);
            }
            ?>">
        </p>
        <?php
        if (isset($_SESSION[ERROR_NAZWA_KONTRAHENTA])) {
            echo '<div class="error">' . $_SESSION[ERROR_NAZWA_KONTRAHENTA] . '</div>';
            unset($_SESSION[ERROR_NAZWA_KONTRAHENTA]);
        }
        ?>

        <p>
            Vat kontrahenta:
            <input type="text" name="vat_kontrahenta" placeholder="vat_kontrahenta" value="<?php
            if (isset($_SESSION[REMEMBER_VAT_KONTRAHENTA])) {
                echo $_SESSION[REMEMBER_VAT_KONTRAHENTA];
                unset($_SESSION[REMEMBER_VAT_KONTRAHENTA]);
            }
            ?>">
        </p>
        <?php
        if (isset($_SESSION[ERROR_VAT_KONTRAHENTA])) {
            echo '<div class="error">' . $_SESSION[ERROR_VAT_KONTRAHENTA] . '</div>';
            unset($_SESSION[ERROR_VAT_KONTRAHENTA]);
        }
        ?>

        <div>
            <input type='radio' name='rodzaj' value='1' id="sell_invoice" checked/>
            <label for="sell_invoice">Faktura sprzedaży</label>

            <input type='radio' name='rodzaj' value='2' id="buy_invoice"/>
            <label for="buy_invoice">Faktura zakupu</label>
        </div>

        <button type="submit">Dodaj</button>
    </form>

    <a href="salesInvoicePanel.php"> Wróć do panelu </a>
</div>

</body>
</html>
