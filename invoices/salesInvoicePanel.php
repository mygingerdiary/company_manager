<?php
session_start();

require_once('../connect.php');
mysqli_report(MYSQLI_REPORT_STRICT);
$conn = new mysqli($host, $db_user, $db_password, $db_name);

$search_phrase = $_POST['search-phrase'];
$search_by = $_POST['search-by'];

$select_query = "SELECT * FROM faktury";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($search_by == 'search-by-id') {

        $select_query = "SELECT * FROM faktury WHERE id=$search_phrase";

    } elseif ($search_by == 'search-by-nr-faktury') {

        $select_query = "SELECT * FROM faktury WHERE nr_faktury='$search_phrase'";

    } elseif ($search_by == 'search-by-vat-kontrahent') {

        $select_kontrahent = "SELECT id FROM kontrahenci WHERE vat_id=$search_phrase";
        $kontrahent_result = mysqli_query($conn, $select_kontrahent);
        $kontrahent_id = $kontrahent_result->fetch_array()[0];

        $select_query = "SELECT * FROM faktury WHERE kontrahent_id=$kontrahent_id";

    } elseif ($search_by == 'search-by-nazwa-kontrahenta') {
        $select_kontrahent = "SELECT id FROM kontrahenci WHERE nazwa='$search_phrase'";
        $kontrahent_result = mysqli_query($conn, $select_kontrahent);
        $kontrahent_id = $kontrahent_result->fetch_array()[0];

        $select_query = "SELECT * FROM faktury WHERE kontrahent_id=$kontrahent_id";
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
<div class="panel">
    <button onclick="window.location.href = 'addSalesInvoice.php';">Dodaj nową fakturę</button>

    <form method="post">
        <input type="text" placeholder="szukana fraza..." name="search-phrase">
        <p>Szukaj po:</p>
        <div>
            <input type='radio' name='search-by' value='search-by-id' id="search-by-id"/>
            <label for="search">id</label>

            <input type='radio' name='search-by' value='search-by-nr-faktury' id="search-by-nr-faktury"/>
            <label for="search-by-nr-faktury">nr faktury</label>

            <input type='radio' name='search-by' value='search-by-vat-kontrahent' id="search-by-vat-kontrahent"/>
            <label for="search-by-vat-kontrahent">vat kontrahenta</label>

            <input type='radio' name='search-by' value='search-by-nazwa-kontrahenta' id="search-by-nazwa-kontrahenta"/>
            <label for="search-by-nazwa-kontrahent">nazwa kontrahenta</label>
        </div>
        <button type="submit">Szukaj</button>
    </form>
    <ul class="sales-invoice-list">
        <?php

        try {
            $result = mysqli_query($conn, $select_query);

            if ($result->num_rows > 0) {
                while ($data = $result->fetch_assoc()) {
                    $waluta_id = $data['waluta'];
                    $select_waluta = "SELECT nazwa FROM waluty WHERE id=$waluta_id";
                    $waluta_result = mysqli_query($conn, $select_waluta);
                    $waluta_name = $waluta_result->fetch_array()[0];

                    $kontrahent_id = $data['kontrahent_id'];
                    $select_kontrahent_nazwa = "SELECT nazwa FROM kontrahenci WHERE id=$kontrahent_id";
                    $select_kontrahent_vat = "SELECT vat_id FROM kontrahenci WHERE id=$kontrahent_id";
                    $kontrakent_result_nazwa = mysqli_query($conn, $select_kontrahent_nazwa);
                    $kontrakent_result_vat = mysqli_query($conn, $select_kontrahent_vat);
                    $kontrahent_name = $kontrakent_result_nazwa->fetch_array()[0];
                    $kontrahent_vat = $kontrakent_result_vat->fetch_array()[0];

                    $dokument_id = $data['id_dokumentu'];
                    $select_dokument = "SELECT * FROM dokumenty WHERE id=$dokument_id";
                    $dokument_result = mysqli_query($conn, $select_dokument);

                    echo "<li>" .
                        "<p>" . $data['id'] . "</p>" .
                        "<p> nr faktury: " . $data['nr_faktury'] . "</p>" .
                        "<p> kwota netto: " . $data['netto'] . "</p>" .
                        "<p> vat: " . $data['vat'] . "</p>" .
                        "<p> kwota brutto: " . $data['brutto'] . "</p>" .
                        "<p> waluta: " . $waluta_name . "</p>" .
                        "<p> Nazwa kontrahenta: " . $kontrahent_name . "</p>" .
                        "<p> Vat kontrahenta: " . $kontrahent_vat . "</p>";

                    if ($dokument_result->num_rows > 0) {
                        while ($dokument_data = $dokument_result->fetch_assoc()) {
                            $_SESSION['rzad'] = array();
                            array_push($_SESSION['rzad'], $dokument_data['id']);
                            echo "<p>Skan faktury: " . $dokument_data['id'] . ", " . $dokument_data['data'] . ", " . $dokument_data['l_stron'] . ", " . $dokument_data['notatki'] . ", " . '<form method="post" action="../showScan.php"><input type="submit" name="' . $dokument_data['id'] . '" value="otwórz skan"></form>' . "</p>";
                        }
                    }

                    echo "<a href='updateInvoice.php?id=" . $data['id'] . "'>Update</a>";
                    echo "<a href='deleteInvoice.php?id=" . $data['id'] . "'>Usuń</a>";

                    echo "</li>" . "\n";
                }
            }

        } catch (Exception $e) {
            echo '<span style="color: red;"> Błąd serwera. Przepraszamy za niedogodności </span>';
            echo '<br />Informacja developerska: ' . $e;
        }
        ?>
    </ul>
</div>
</body>
