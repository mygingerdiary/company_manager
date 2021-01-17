<?php
session_start();
if ((!isset($_SESSION['zalogowany'])) || ($_SESSION['zalogowany'] == false)) {
    header('Location: ../index.php');
}

if (!isset($_SESSION['rola_uzytkownika']) || $_SESSION['rola_uzytkownika'] == 'auditor') {
    header('Location: ../notAllowed.php');
}


const ADDED_INVOICE_ID = 'added_invoice_id';

function update_invoice_document($conn, $document_id)
{
    if (isset($_SESSION[ADDED_INVOICE_ID])) {
        $invoice_id = $_SESSION[ADDED_INVOICE_ID];

        $update_query = "UPDATE faktury SET id_dokumentu=$document_id WHERE id=$invoice_id";
        if (mysqli_query($conn, $update_query)) {
            mysqli_close($conn);
            header('Location: salesInvoicePanel.php');
            exit;
        } else {
            echo "Error updating record";
        }
    }

}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once('../connect.php');
    mysqli_report(MYSQLI_REPORT_STRICT);
    $conn = new mysqli($host, $db_user, $db_password, $db_name);

    $id_dokumentu = $_POST['id_dokumentu'];
    update_invoice_document($conn, $id_dokumentu);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Company Manager</title>
    <link href="../../css/panel.css" type="text/css" rel="stylesheet">
    <link href="../../css/invoices_panel.css" type="text/css" rel="stylesheet">
    <link href="../../css/table.css" type="text/css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" rel="stylesheet">

</head>
<body>
<h1>Dokumenty</h1>
<button id="addDoc" class="transparent-button" onclick="window.location.href='../documents/addDocument.php'">Dodaj
    dokument <i
            class="fas fa-plus"></i></button>
<form class="search" method="post" action="documentsSystem.php">
    <input type="text" name="napis" placeholder="Wyszukaj...">
    <button type="submit" name="submit"><i class="fa fa-search"></i></button>

</form>

<table class="docs-table">
    <tr>
        <th>Id</th>
        <th>Data</th>
        <th>Liczba stron</th>
        <th>Notatki</th>
        <th>Skany</th>
        <th>Potwierdź</th>
    </tr>
    <?php
    require_once('../connect.php');
    $db = new mysqli($host, $db_user, $db_password, $db_name);

    if (!isset($_POST['submit'])) {
        $_SESSION['rzad'] = array();
        $result = "SELECT * FROM dokumenty";
        $result = mysqli_query($db, $result);

        if ($result->num_rows > 0) {
            while ($data = $result->fetch_assoc()) {
                array_push($_SESSION['rzad'], $data['id']);
                echo "<tr><td>" . $data['id'] . "</td><td>" . date('d-m-Y', strtotime($data['data'])) . "</td><td>" . $data['l_stron'] . "</td><td>" . $data['notatki'] . "</td><td>" . '<form method="post" action="../documents/showScan.php" target="_blank"><input class="transparent-button" type="submit" name="' . $data['id'] . '" value="otwórz skan"></form>' . "</td><td>" . '<form method="post"><button class="update-button" type="submit" name="id_dokumentu" value=' . $data['id'] . '>wybierz</button></form>' . "</td></tr>";
            }
        }
    }


    if (isset($_POST['submit'])) {
        $napis = $db->real_escape_string($_POST['napis']);
        //echo intval($napis);
        $idd = intval($napis);
        if (is_int($idd) && $idd > 0) {
            $sql = "SELECT * FROM dokumenty WHERE id=$idd";
            mysqli_query($db, $sql);
            $result2 = mysqli_query($db, $sql);
            if ($result2->num_rows > 0) {
                while ($row = $result2->fetch_assoc()) {
                    echo "<tr><td>" . $row['id'] . "</td><td>" . date('d-m-Y', strtotime($row['data'])) . "</td><td>" . $row['l_stron'] . "</td><td>" . $row['notatki'] . "</td><td>" . '<form method="post" action="../documents/showScan.php"><input class="transparent-button" type="submit" name="' . $row['id'] . '" value="otwórz skan"></form>' . "</td></tr>";
                }
            } else {
                echo "</table>";
                echo "Brak wyszukiwań";
            }
        } else {
            $_SESSION['e_search'] = "Wyszukiwanie możliwe tylko po id";
        }
    }
    ?>
    <?php
    if (isset($_SESSION['e_search'])) {
        echo "</table>";
        echo '<div class="error2">' . $_SESSION['e_search'] . '</div>';
        unset($_SESSION['e_search']);
    }
    ?>

</table>


</body>
</html>
