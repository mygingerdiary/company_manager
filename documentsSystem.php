<?php

session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Company Manager</title>
    <link href="style_inside.css" type="text/css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        table, td, th, tr {
            border: 1px solid black;
            border-collapse: collapse;
            margin-left: auto;
            margin-right: auto;
            margin-top: auto;
            text-align: center;
        }

    </style>

</head>
<body>
<h1>Dokumenty</h1>
<button id="addDoc" onclick="window.location.href='addDocument.php'">Dodaj dokument</button>
<form class="search" method="post" action="documentsSystem.php">
    <input type="text" name="napis" placeholder="Wyszukaj...">
    <button type="submit" name="submit"><i class="fa fa-search"></i></button>

</form>

<table style="width:1100px">
    <tr>
        <th>Id</th>
        <th>Data</th>
        <th>Liczba stron</th>
        <th>Notatki</th>
        <th>Skany</th>
    </tr>
    <?php
    $db = mysqli_connect("localhost", "root", "", "company_manager2");

    if(!isset($_POST['submit'])) {
        $_SESSION['rzad'] = array();
        $result = "SELECT * FROM dokumenty";
        $result = mysqli_query($db, $result);

        if ($result->num_rows > 0) {
            while ($data = $result->fetch_assoc()) {
                array_push($_SESSION['rzad'], $data['id']);
                echo "<tr><th>" . $data['id'] . "</th><th>" . date('d-m-Y', strtotime($data['data'])) . "</th><th>" . $data['l_stron'] . "</th><th>" . $data['notatki'] . "</th><th>" . '<form method="post" action="showScan.php"><input type="submit" name="' . $data['id'] . '" value="otwórz skan"></form>'. "</th><th>" . '<button type="submit" name="delete" value="usuń">' . "</td></tr>";
            }
        }
    }


    if (isset($_POST['submit'])) {
        $napis = $db->real_escape_string($_POST['napis']);
        //echo intval($napis);
        $idd = intval($napis);
        if (is_int($idd) && $idd>0) {
            $sql = "SELECT * FROM dokumenty WHERE id=$idd";
            mysqli_query($db, $sql);
            $result2 = mysqli_query($db, $sql);
            if ($result2->num_rows > 0) {
                while ($row = $result2->fetch_assoc()) {
                    echo "<tr><th>" . $row['id'] . "</th><th>" . date('d-m-Y', strtotime($row['data'])) . "</th><th>" . $row['l_stron'] . "</th><th>" . $row['notatki'] . "</th><th>" . '<form method="post" action="showScan.php"><input type="submit" name="' . $row['id'] . '" value="otwórz skan"></form>' . "</td></tr>";
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
    if(isset($_SESSION['e_search']))
    {
        echo"</table>";
        echo '<div class="error2">'.$_SESSION['e_search'].'</div>';
        unset($_SESSION['e_search']);
    }
    ?>

</table>


</body>
</html>
