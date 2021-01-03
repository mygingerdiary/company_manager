<?php
session_start();

$id = $_GET['id'];

require_once('../connect.php');
mysqli_report(MYSQLI_REPORT_STRICT);
$conn = new mysqli($host, $db_user, $db_password, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nr_faktury = $_POST['nr_faktury'];
    $netto = $_POST['netto'];
    $vat = $_POST['vat'];
    $brutto = $_POST['brutto'];
    $waluta = $_POST['waluta'];
    $waluta_id = get_vault_id($conn, $waluta);
    $nazwa_kontrahenta = $_POST['nazwa_kontrahenta'];
    $vat_kontrahenta = $_POST['vat_kontrahenta'];
    $kontrahent_id = add_contractor_if_not_existing($conn, $nazwa_kontrahenta, $vat_kontrahenta);

    $sql = "UPDATE faktury 
            SET nr_faktury = '$nr_faktury',
            netto = $netto,
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
        echo "Error deleting record";
    }
}

function add_contractor_if_not_existing($conn, $nazwa_kontrahenta, $vat_kontrahenta)
{
    $sample = $conn->query("SELECT id FROM kontrahenci WHERE vat_id = $vat_kontrahenta");
    if (mysqli_num_rows($sample) == 0) {
        $conn->query("INSERT INTO kontrahenci (nazwa, vat_id) VALUES ('$nazwa_kontrahenta', $vat_kontrahenta)");
    }

    $result = $conn->query("SELECT id FROM kontrahenci WHERE vat_id = $vat_kontrahenta");
    $row = $result->fetch_array()[0];
    return $row['id'];
}

function get_vault_id($conn, $waluta)
{
    $result = $conn->query("SELECT id FROM waluty WHERE nazwa = '$waluta'");
    $row = $result->fetch_array()[0];
    return $row['id'];
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
        <?php
        echo "<p>" . $id . "</p>";
        ?>
        <input type="text" placeholder="nr faktury..." name="nr_faktury" required>
        <input type="text" placeholder="kwota netto..." name="netto" required>
        <input type="text" placeholder="vat..." name="vat" required>
        <input type="text" placeholder="kwota brutto..." name="brutto" required>
        <input list="waluta" name="waluta" required>
        <datalist id="waluta">
            <?php

            $waluty_select = 'SELECT * FROM waluty';
            $waluty_result = mysqli_query($conn, $waluty_select);

            if ($waluty_result->num_rows > 0) {
                while ($data = $waluty_result->fetch_assoc()) {
                    echo "<option value='" . $data['nazwa'] . "'>\n";
                }
            }
            ?>
        </datalist>
        <input type="text" placeholder="nazwa kontrahenta..." name="nazwa_kontrahenta" required>
        <input type="text" placeholder="vat kontrahenta..." name="vat_kontrahenta" required>
        <button type="submit">Update</button>
    </form>
</div>

</body>
