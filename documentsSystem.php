<?php

session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Company Manager</title>
    <link href="style_inside.css" type="text/css" rel="stylesheet">
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
<button onclick="window.location.href='addDocument.php'">Dodaj dokument</button>
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

    $_SESSION['rzad']=array();
    $result = "SELECT * FROM dokumenty";
    $result=mysqli_query($db, $result);

    if ($result->num_rows > 0) {
        while ($data = $result->fetch_assoc()) {
            array_push($_SESSION['rzad'],$data['id']);
                echo "<tr><th>" . $data['id'] . "</th><th>" . $data['data'] . "</th><th>" . $data['l_stron'] . "</th><th>" . $data['notatki'] . "</th><th>".'<form method="post" action="showScan.php"><input type="submit" name="'.$data['id'].'" id=value="otwórz skan"></form>'."</td></tr>";
            }
    }

    ?>
</table>


</body>
</html>
