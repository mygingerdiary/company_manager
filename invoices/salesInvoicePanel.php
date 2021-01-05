<?php
session_start();

require_once('../connect.php');
mysqli_report(MYSQLI_REPORT_STRICT);
$conn = new mysqli($host, $db_user, $db_password, $db_name);

function get_documents_in_year($conn, $year)
{
    $result = array();
    $select_documents_from_year_query = "SELECT * FROM dokumenty WHERE YEAR(data) = $year";
    $documents_from_year = mysqli_query($conn, $select_documents_from_year_query);

    if ($documents_from_year->num_rows > 0) {
        while ($data = $documents_from_year->fetch_assoc()) {
            array_push($result, $data['id']);
        }
    }

    return $result;
}

function get_documents_in_month($conn, $month)
{
    $result = array();
    $select_documents_from_year_query = "SELECT * FROM dokumenty WHERE MONTH (data) = $month";
    $documents_from_year = mysqli_query($conn, $select_documents_from_year_query);

    if ($documents_from_year->num_rows > 0) {
        while ($data = $documents_from_year->fetch_assoc()) {
            array_push($result, $data['id']);
        }
    }

    return $result;
}

function get_documents_in_month_and_year($conn, $month, $year)
{
    $result = array();
    $select_documents_from_year_query = "SELECT * FROM dokumenty WHERE MONTH (data) = $month AND YEAR(data) = $year";
    $documents_from_year = mysqli_query($conn, $select_documents_from_year_query);

    if ($documents_from_year->num_rows > 0) {
        while ($data = $documents_from_year->fetch_assoc()) {
            array_push($result, $data['id']);
        }
    }

    return $result;
}

function get_ids_string($id_array)
{
    $result = "(";
    $i = 0;
    foreach ($id_array as &$value) {
        $result .= $value;
        if ($i != count($id_array) - 1) {
            $result .= ", ";
        }
        $i++;
    }
    $result .= ")";

    return $result;
}

$invoice_type = 1;

$search_phrase = $_POST['search-phrase'];
$search_by = $_POST['search-by'];
$select_query = "SELECT * FROM faktury";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $month = $_POST['month'];
    $year = $_POST['year'];

    if ($month == '' && $year == '') {
        $documents_in_year = range(1, 10000);
        $document_ids = get_ids_string($documents_in_year);
    } elseif ($month == '' && $year != '') {
        $documents_in_year = get_documents_in_year($conn, $year);
        $document_ids = get_ids_string($documents_in_year);
    } elseif ($month != '' && $year == '') {
        $documents_in_month = get_documents_in_month($conn, $month);
        $document_ids = get_ids_string($documents_in_month);
    } else {
        $documents_in_month_and_year = get_documents_in_month_and_year($conn, $month, $year);
        $document_ids = get_ids_string($documents_in_month_and_year);
    }

    $select_query = "SELECT * FROM faktury WHERE id_dokumentu IN $document_ids";

    if (isset($_POST['rodzaj'])) {
        $invoice_type = $_POST['rodzaj'];
        $_SESSION['rodzaj'] = $invoice_type;
    } elseif (isset($_SESSION['rodzaj'])) {
        $invoice_type = $_SESSION['rodzaj'];
    }

    if ($search_by == 'search-by-id') {

        $select_query = "SELECT * FROM faktury WHERE id=$search_phrase AND rodzaj=$invoice_type AND id_dokumentu IN $document_ids";

    } elseif ($search_by == 'search-by-nr-faktury') {

        $select_query = "SELECT * FROM faktury WHERE nr_faktury='$search_phrase' AND rodzaj=$invoice_type AND id_dokumentu IN $document_ids";

    } elseif ($search_by == 'search-by-vat-kontrahent') {

        $select_kontrahent = "SELECT id FROM kontrahenci WHERE vat_id=$search_phrase";
        $kontrahent_result = mysqli_query($conn, $select_kontrahent);
        $kontrahent_id = $kontrahent_result->fetch_array()[0];

        $select_query = "SELECT * FROM faktury WHERE kontrahent_id=$kontrahent_id AND rodzaj=$invoice_type AND id_dokumentu IN $document_ids";

    } elseif ($search_by == 'search-by-nazwa-kontrahenta') {
        $select_kontrahent = "SELECT id FROM kontrahenci WHERE nazwa='$search_phrase'";
        $kontrahent_result = mysqli_query($conn, $select_kontrahent);
        $kontrahent_id = $kontrahent_result->fetch_array()[0];

        $select_query = "SELECT * FROM faktury WHERE kontrahent_id=$kontrahent_id AND rodzaj=$invoice_type AND id_dokumentu IN $document_ids";
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

    <form name="invoice_type" method="post">
        <div>
            <input type='radio' name='rodzaj' value='1' id="sell_invoice" onclick="autoSubmit()" <?php
            if ($invoice_type == 1) {
                echo "checked";
            }
            ?>/>
            <label for="sell_invoice">Faktura sprzedaży</label>

            <input type='radio' name='rodzaj' value='2' id="buy_invoice" onclick="autoSubmit()"<?php
            if ($invoice_type == 2) {
                echo "checked";
            }
            ?>/>
            <label for="buy_invoice">Faktura zakupu</label>
        </div>
    </form>

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
        <select class="form-control" name="month">
            <option value="" disabled selected>Wybierz miesiąc</option>
            <option value="01">Styczeń</option>
            <option value="02">Luty</option>
            <option value="03">Marzec</option>
            <option value="04">Kwiecień</option>
            <option value="05">Maj</option>
            <option value="06">Czerwiec</option>
            <option value="07">Lipiec</option>
            <option value="08">Sierpień</option>
            <option value="09">Wrzesień</option>
            <option value="10">Październik</option>
            <option value="11">Listopad</option>
            <option value="12">Grudizeń</option>

        </select>
        <select class="form-control" name="year">
            <option value="" disabled selected>Wybierz rok</option>
            <?php
            foreach (range(2000, (int)date("Y")) as $yearr) {
                echo "<option value='" . $yearr . "'>" . $yearr . "</option>";
            }
            ?>
        </select>

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

                    if ($data['rodzaj'] == $invoice_type) {
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
                                array_push($_SESSION['rzad'], $dokument_data['id']);
                                echo "<p>Skan faktury: " . $dokument_data['id'] . ", " . $dokument_data['data'] . ", " . $dokument_data['l_stron'] . ", " . $dokument_data['notatki'] . ", " . '<form method="post" action="../showSingleScan.php?id=' . $dokument_id . '"><input type="submit" name="' . $dokument_data['id'] . '" value="otwórz skan"></form>' . "</p>";
                            }
                        }

                        echo "<a href='updateInvoice.php?id=" . $data['id'] . "'>Update</a>";
                        echo "<a href='deleteInvoice.php?id=" . $data['id'] . "'>Usuń</a>";

                        echo "</li>" . "\n";
                    }
                }
            }

        } catch (Exception $e) {
            echo '<span style="color: red;"> Błąd serwera. Przepraszamy za niedogodności </span>';
            echo '<br />Informacja developerska: ' . $e;
        }
        ?>
    </ul>

    <a href="../panel.php"> Wróć do panelu </a>
</div>

<script>
    function autoSubmit() {
        let formObject = document.forms['invoice_type'];
        formObject.submit();
    }
</script>
</body>
