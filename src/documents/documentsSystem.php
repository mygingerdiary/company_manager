<?php

session_start();

if ((!isset($_SESSION['zalogowany'])) || ($_SESSION['zalogowany'] == false)) {
    header('Location: ../index.php');
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
<button id="addDoc" class="transparent-button" onclick="window.location.href='addDocument.php'">Dodaj dokument <i
            class="fas fa-plus"></i></button>

<hr>

<form class="search" method="post" action="documentsSystem.php">
    <input type="number" name="napis" placeholder="Wyszukaj..." min="1" step="1">
    <button type="submit" name="submit"><i class="fa fa-search"></i></button>
    <div class="date-selection">

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
            foreach (range(1950, (int)date("Y")) as $yearr) {
                echo "<option value='" . $yearr . "'>" . $yearr . "</option>";
            }

            ?>
        </select>
    </div>

</form>
<a href="../panel.php">Wróc do panelu</a>

<table class="docs-table">
    <tr>
        <th>Id</th>
        <th>Data</th>
        <th>Liczba stron</th>
        <th>Notatki</th>
        <th>Skany</th>
        <th>Edytuj</th>
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
                echo "<tr><td>" . $data['id'] . "</td><td>" . date('d-m-Y', strtotime($data['data'])) . "</td><td>" . $data['l_stron'] . "</td><td>" . $data['notatki'] . "</td><td>" . '<form method="post" action="showScan.php"><input class="transparent-button" type="submit" name="' . $data['id'] . '" value="otwórz skan"></form>' . "</td><td>" . '<form method="post" action="deleteDocument.php"><input class="update-button delete-button" type="submit" name="' . $data['id'] . '" value="Usuń"></form>' . '<form method="post" action="editDocument.php"><input class="update-button" type="submit" name="' . $data['id'] . '" value="Edytuj"></form>' . "</td></tr>";
            }
        }
    }


    if (isset($_POST['submit'])) {
        $napis = $db->real_escape_string($_POST['napis']);
        if (isset($_POST['month'])) {
            $month = $db->real_escape_string($_POST['month']);
        }

        if (isset($_POST['year'])) {
            $year = $db->real_escape_string($_POST['year']);
        }

        //echo intval($napis);
        $idd = intval($napis);
        //dziala
        if ($idd > 0 && is_int($idd) && empty($year) && empty($month)) {

            $sql = "SELECT * FROM dokumenty WHERE id=$idd";
            mysqli_query($db, $sql);
            $result2 = mysqli_query($db, $sql);
            if ($result2->num_rows > 0) {
                while ($row = $result2->fetch_assoc()) {
                    echo "<tr><td>" . $row['id'] . "</td><td>" . date('d-m-Y', strtotime($row['data'])) . "</td><td>" . $row['l_stron'] . "</td><td>" . $row['notatki'] . "</td><td>" . '<form method="post" action="showScan.php"><input type="submit" name="' . $row['id'] . '" value="otwórz skan"></form>' . "</td></tr>";
                }

            } else {
                echo "</table>";
                echo "Brak wyszukiwań";
            }
        }


        //dziala
        if (!empty($year) && empty($month) && $idd == NULL) {
            echo("<script>console.log('PHPY: " . $year . "');</script>");
            $sql2 = "SELECT * FROM dokumenty WHERE year(data) = $year";
            mysqli_query($db, $sql2);
            $result3 = mysqli_query($db, $sql2);
            if ($result3->num_rows > 0) {
                while ($row = $result3->fetch_assoc()) {
                    echo "<tr><td>" . $row['id'] . "</td><td>" . date('d-m-Y', strtotime($row['data'])) . "</td><td>" . $row['l_stron'] . "</td><td>" . $row['notatki'] . "</td><td>" . '<form method="post" action="showScan.php"><input type="submit" name="' . $row['id'] . '" value="otwórz skan"></form>' . "</td></tr>";
                }

            } else {
                echo "</table>";
                echo "Brak wyszukiwań";
            }

        }//else {
        //     $_SESSION['e_search'] = "Wyszukiwanie możliwe tylko po id";
        // }
//dziala
        if (!empty($year) && !empty($month) && $idd == NULL) {
            echo("<script>console.log('PHPM: " . $month . "');</script>");
            echo("<script>console.log('PHPY: " . $year . "');</script>");
            $sql2 = "SELECT * FROM dokumenty WHERE year(data) = $year AND month(data)=$month";
            mysqli_query($db, $sql2);
            $result3 = mysqli_query($db, $sql2);
            if ($result3->num_rows > 0) {
                while ($row = $result3->fetch_assoc()) {
                    echo "<tr><td>" . $row['id'] . "</td><td>" . date('d-m-Y', strtotime($row['data'])) . "</td><td>" . $row['l_stron'] . "</td><td>" . $row['notatki'] . "</td><td>" . '<form method="post" action="showScan.php"><input type="submit" name="' . $row['id'] . '" value="otwórz skan"></form>' . "</td></tr>";
                }

            } else {
                echo "</table>";
                echo "Brak wyszukiwań";
            }

        }
        //dziala
        if (empty($year) && $idd == NULL && !empty($month)) {
            $month = $_POST['month'];
            echo("<script>console.log('PHPM: " . $month . "');</script>");
            $sql3 = "SELECT * FROM dokumenty WHERE month(data) = $month";
            mysqli_query($db, $sql3);
            $result4 = mysqli_query($db, $sql3);
            if ($result4->num_rows > 0) {
                while ($row2 = $result4->fetch_assoc()) {
                    echo "<tr><td>" . $row2['id'] . "</td><td>" . date('d-m-Y', strtotime($row2['data'])) . "</td><td>" . $row2['l_stron'] . "</td><td>" . $row2['notatki'] . "</td><td>" . '<form method="post" action="showScan.php"><input type="submit" name="' . $row2['id'] . '" value="otwórz skan"></form>' . "</td></tr>";
                }

            } else {
                echo "</table>";
                echo "Brak wyszukiwań";
            }

        }
        //dziala
        if (!empty($year) && !empty($month) && $idd > 0 && is_int($idd)) {

            $sql2 = "SELECT * FROM dokumenty WHERE year(data) = $year AND month(data)=$month AND id=$idd";
            mysqli_query($db, $sql2);
            $result3 = mysqli_query($db, $sql2);
            if ($result3->num_rows > 0) {
                while ($roww = $result3->fetch_assoc()) {
                    echo "<tr><td>" . $roww['id'] . "</td><td>" . date('d-m-Y', strtotime($roww['data'])) . "</td><td>" . $roww['l_stron'] . "</td><td>" . $roww['notatki'] . "</td><td>" . '<form method="post" action="showScan.php"><input type="submit" name="' . $roww['id'] . '" value="otwórz skan"></form>' . "</td></tr>";
                }

            } else {
                echo "</table>";
                echo "Brak wyszukiwań";
            }

        }
        if (empty($year) && !empty($month) && $idd > 0 && is_int($idd)) {

            $sql2 = "SELECT * FROM dokumenty WHERE month(data)=$month AND id=$idd";
            mysqli_query($db, $sql2);
            $result3 = mysqli_query($db, $sql2);
            if ($result3->num_rows > 0) {
                while ($roww = $result3->fetch_assoc()) {
                    echo "<tr><td>" . $roww['id'] . "</td><td>" . date('d-m-Y', strtotime($roww['data'])) . "</td><td>" . $roww['l_stron'] . "</td><td>" . $roww['notatki'] . "</td><td>" . '<form method="post" action="showScan.php"><input type="submit" name="' . $roww['id'] . '" value="otwórz skan"></form>' . "</td></tr>";
                }

            } else {
                echo "</table>";
                echo "Brak wyszukiwań";
            }

        }
        if (!empty($year) && empty($month) && $idd > 0 && is_int($idd)) {
            $sql2 = "SELECT * FROM dokumenty WHERE year(data)=$year AND id=$idd";
            mysqli_query($db, $sql2);
            $result3 = mysqli_query($db, $sql2);
            if ($result3->num_rows > 0) {
                while ($roww = $result3->fetch_assoc()) {
                    echo "<tr><td>" . $roww['id'] . "</td><td>" . date('d-m-Y', strtotime($roww['data'])) . "</td><td>" . $roww['l_stron'] . "</td><td>" . $roww['notatki'] . "</td><td>" . '<form method="post" action="showScan.php"><input type="submit" name="' . $roww['id'] . '" value="otwórz skan"></form>' . "</td></tr>";
                }

            } else {
                echo "</table>";
                echo "Brak wyszukiwań";
            }

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