<?php
session_start();

$id = $_GET['id'];

const ERROR_NR_FAKTURY = 'error_nr_faktury';
const ERROR_NETTO = 'error_netto';
const ERROR_NETTO_PLN = 'error_netto_pln';
const ERROR_VAT = 'error_vat';
const ERROR_BRUTTO = 'error_brutto';
const ERROR_WALUTA = 'error_waluta';
const ERROR_NAZWA_KONTRAHENTA = 'error_nazwa_kontrahenta';
const ERROR_VAT_KONTRAHENTA = 'error_vat_kontrahenta';

const REMEMBER_NR_FAKTURY = 'remember_nr_faktury';
const REMEMBER_NETTO = 'remember_netto';
const REMEMBER_NETTO_PLN = 'remember_netto_pln';
const REMEMBER_VAT = 'remember_vat';
const REMEMBER_BRUTTO = 'remember_brutto';
const REMEMBER_WALUTA = 'remember_waluta';
const REMEMBER_NAZWA_KONTRAHENTA = 'remember_nazwa_kontrahenta';
const REMEMBER_VAT_KONTRAHENTA = 'remember_vat_kontrahenta';


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

function validate_netto_pln($netto_pln)
{
    if (!is_numeric($netto_pln)) {
        $_SESSION[ERROR_NETTO_PLN] = "Kwota netto nie jest liczbą";
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

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($conn->errno != 0) {
    echo "ERROR: " . $conn->errno;
} else {
    $waluty_select = 'SELECT * FROM waluty';
    $waluty_result = mysqli_query($conn, $waluty_select);

    $invoices_select = "SELECT * FROM faktury WHERE id=$id";
    $invoices_result = mysqli_query($conn, $invoices_select);

    if ($invoices_result->num_rows > 0) {
        while ($data = $invoices_result->fetch_assoc()) {
            $_SESSION[REMEMBER_NR_FAKTURY] = $data['nr_faktury'];
            $_SESSION[REMEMBER_NETTO] = $data['netto'];
            $_SESSION[REMEMBER_NETTO_PLN] = $data['netto_pln'];
            $_SESSION[REMEMBER_VAT] = $data['vat'];
            $_SESSION[REMEMBER_BRUTTO] = $data['brutto'];

            $sql_waluta_id = $data['waluta'];
            $sql_waluta_select = "SELECT * FROM waluty WHERE id=$sql_waluta_id";
            $sql_waluta_result = mysqli_query($conn, $sql_waluta_select);
            if ($sql_waluta_result->num_rows > 0) {
                while ($waluta_data = $sql_waluta_result->fetch_assoc()) {
                    $sql_waluta = $waluta_data['nazwa'];
                }
            }
            $_SESSION[REMEMBER_WALUTA] = $sql_waluta;

            $sql_kontrahent_id = $data['kontrahent_id'];
            $sql_kontrahent_select = "SELECT * FROM kontrahenci WHERE id=$sql_kontrahent_id";
            $sql_kontrahent_result = mysqli_query($conn, $sql_kontrahent_select);
            if ($sql_kontrahent_result->num_rows > 0) {
                while ($sql_kontrahent_data = $sql_kontrahent_result->fetch_assoc()) {
                    $sql_nazwa_kontrahenta = $sql_kontrahent_data['nazwa'];
                    $sql_vat_kontrahenta = $sql_kontrahent_data['vat_id'];
                }
            }
            $_SESSION[REMEMBER_NAZWA_KONTRAHENTA] = $sql_nazwa_kontrahenta;
            $_SESSION[REMEMBER_VAT_KONTRAHENTA] = $sql_vat_kontrahenta;
        }
    }

}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $valid = true;

    $nr_faktury = $_POST['nr_faktury'];
    $netto = $_POST['netto'];
    $netto_pln = $_POST['netto_pln'];
    $vat = $_POST['vat'];
    $brutto = $_POST['brutto'];
    $waluta = $_POST['waluta'];
    $nazwa_kontrahenta = $_POST['nazwa_kontrahenta'];
    $vat_kontrahenta = $_POST['vat_kontrahenta'];

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
    $_SESSION[REMEMBER_NETTO_PLN] = $netto_pln;
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
            $waluta_id = get_vault_id($conn, $waluta);
            $kontrahent_id = add_contractor_if_not_existing($conn, $nazwa_kontrahenta, $vat_kontrahenta);

            $sql = "UPDATE faktury 
            SET nr_faktury = '$nr_faktury',
            netto = $netto,
            netto_pln = $netto_pln,
            vat = $vat,
            brutto = $brutto,
            waluta = $waluta_id,
            kontrahent_id = $kontrahent_id 
            WHERE id = $id";

            if (mysqli_query($conn, $sql)) {
                mysqli_close($conn);
                header('Location: salesInvoicePanel.php');
                exit;
            } else {
                echo "Error updating record";
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
<div class="edit-invoice-panel">
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
            Kwota netto w PLN:
            <input type="text" name="netto_pln" placeholder="netto_pln" value="<?php
            if (isset($_SESSION[REMEMBER_NETTO_PLN])) {
                echo $_SESSION[REMEMBER_NETTO_PLN];
                unset($_SESSION[REMEMBER_NETTO_PLN]);
            }
            ?>">
        </p>
        <?php
        if (isset($_SESSION[ERROR_NETTO_PLN])) {
            echo '<div class="error">' . $_SESSION[ERROR_NETTO_PLN] . '</div>';
            unset($_SESSION[ERROR_NETTO_PLN]);
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
            <select name="waluta" value="<?php
            if (isset($_SESSION[REMEMBER_WALUTA])) {
                echo $_SESSION[REMEMBER_WALUTA];
                unset($_SESSION[REMEMBER_WALUTA]);
            }
            ?>" required>
                <?php
                if ($waluty_result->num_rows > 0) {
                    while ($data = $waluty_result->fetch_assoc()) {
                        echo "<option value='" . $data['nazwa'] . "'>" . $data['nazwa'] . "</option>";
                    }
                }
                ?>
            </select>

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
        <button type="submit">Aktualizuj</button>
    </form>
</div>

</body>
