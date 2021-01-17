<?php

session_start();

if ((!isset($_SESSION['zalogowany'])) || ($_SESSION['zalogowany'] == false)) {
    header('Location: ../index.php');
}


require_once('../connect.php');
$conn = new mysqli($host, $db_user, $db_password, $db_name);

if ($conn->connect_errno != 0) {
    echo "ERROR: " . $conn->connect_errno;
} else {
    $uzytkownicy=$conn->query("SELECT count(*) as c FROM uzytkownicy")->fetch_object()->c;
    $wszystkiefaktury=$conn->query("SELECT count(*) as c FROM faktury")->fetch_object()->c;
    $fakturysprzedazy=$conn->query("SELECT count(*) as c FROM faktury WHERE rodzaj=1")->fetch_object()->c;
    $fakturyzakupu=$conn->query("SELECT count(*) as c FROM faktury WHERE rodzaj=2")->fetch_object()->c;
    $kwotazakupy=$conn->query("SELECT sum(netto_pln) as c FROM faktury WHERE rodzaj=2")->fetch_object()->c;
    $kwotasprzedaz=$conn->query("SELECT sum(netto_pln) as c FROM faktury WHERE rodzaj=1")->fetch_object()->c;
    if(!$kwotazakupy)
    {
        $kwotazakupy=0;
    }
    if(!$kwotasprzedaz)
    {
        $kwotasprzedaz=0;
    }
    $minsprzedaz=$conn->query("SELECT min(netto_pln) as c FROM faktury WHERE rodzaj=1")->fetch_object()->c;
    $maxsprzedaz=$conn->query("SELECT max(netto_pln) as c FROM faktury WHERE rodzaj=1")->fetch_object()->c;
    if(!$minsprzedaz)
    {
        $minsprzedaz=0;
    }
    if(!$maxsprzedaz)
    {
        $maxsprzedaz=0;
    }
    $licencje=$conn->query("SELECT count(*) as c FROM licencje")->fetch_object()->c;
    $sprzety=$conn->query("SELECT count(*) as c FROM sprzety")->fetch_object()->c;
    $conn->close();


}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Company Manager</title>
    <link href="../../css/panel.css" type="text/css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Arbutus&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=ABeeZee&display=swap" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" rel="stylesheet">
</head>
<body>

<div class="panel-section">
    <div class="title-section">
        <p>Statystyki</p>
        <hr>
    </div>
    <div class="stats">
        <p>Ilość użytkowników w systemie: <?php
            echo $uzytkownicy;
            ?></p>
        <p>Ilość wszystkich faktur w systemie: <?php
            echo $wszystkiefaktury;
            ?></p>
        <p>Ilość faktur zakupu w systemie: <?php
            echo $fakturyzakupu;
            ?></p>
        <p>Kwota za zakupy: <?php
            echo $kwotazakupy . " NETTO PLN";
            ?></p>
        <p>Ilość faktur sprzedaży w systemie: <?php
            echo $fakturysprzedazy;
            ?></p>
        <p>Kwota ze sprzedaży: <?php
            echo $kwotasprzedaz . " NETTO PLN";
            ?></p>
        <p>Najmniejsza wartość ze sprzedaży:
            <?php
            echo $minsprzedaz . " NETTO PLN";
            ?></p>
        <p>Największa wartość ze sprzedaży: <?php
            echo $maxsprzedaz . " NETTO PLN";
            ?></p>
        <p>Ilość licencji w systemie: <?php
            echo $licencje;
            ?></p>
        <p>Ilość sprzętów w systemie: <?php
            echo $sprzety;
            ?></p>

    </div>
</div>

<a href="../panel.php" class="go-back-link"> Wróć do panelu </a>
</body>
</html>

