<?php
session_start();


$db = new mysqli($host, $db_user, $db_password, $db_name);
$select_query = "SELECT * FROM sprzety";

if (isset($_POST['napis'])  && isset($_POST['search-by'])) {
    $napis = $_POST['napis'];
    $search_by = $_POST['search-by'];

    if ($search_by == 'search-by-inwentarzowy') {
        if (!is_numeric($napis) && strlen($napis) > 0) {
            $_SESSION['e_inwentarzowy'] = "Numer inwentarzowy zawiera tylko liczby ";
            $select_query ="SELECT * FROM sprzety WHERE 1 = 0";
        }
    }
    if ($search_by == 'search-by-seryjny') {
        if(!preg_match('/^[a-zA-Z0-9\-\/]+/', $napis))
        {
            $_SESSION['e_nr_seryjny'] = "Numer seryjny zawiera litery, cyfry lub znaki specjalne: - /";
        }
    }
}
if (isset($_POST['submit']) && !isset($_POST['search-by']) && strlen($_POST['napis']) > 0 && isset($_POST['napis']) ) {

    $_SESSION['e_wyszukiwanie'] = "Nie wybrano po czym wyszukiwać ";
    $select_query ="SELECT * FROM sprzety WHERE 1 = 0";

    }


if (isset($_POST['submit']) && isset($_POST['search-by']) && !isset($_SESSION['e_wyszukiwanie'] )) {

    $napis = $db->real_escape_string($_POST['napis']);
    $napis=intval($napis);
    $search_by = $_POST['search-by'];

    if ($search_by == 'search-by-inwentarzowy') {
        echo "<script>console.log('$select_query');</script>";
        echo "<script>console.log('$search_by');</script>";
        $select_query = "SELECT * FROM sprzety WHERE nr_inwentarzowy=$napis";

    } elseif ($search_by == 'search-by-nr-seryjny') {

        $select_query = "SELECT * FROM sprzety WHERE nr_seryjny=$napis";
    }

}
if (isset($_POST['submit']) && !isset($_POST['search-by']) && strlen($_POST['napis']) == 0 && isset($_POST['napis'])) {


        $select_query = "SELECT * FROM sprzety";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Katalog sprzętu</title>
    <link href="style_inside.css" type="text/css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <button onclick="window.location.href = 'addDevice.php';">Dodaj sprzęt</button>

    <a href="panel.php"> Wróć do panelu </a>

    <form method="post" action="devicesCatalogPanel.php">
        <input type="text" placeholder="szukana fraza..." name="napis">
        <button type="submit" name="submit" ><i class="fa fa-search"></i></button>
        <p>Szukaj po:</p>
        <div>
            <input type='radio' name='search-by' value='search-by-inwentarzowy' id="search-by-inwentarzowy"/>
            <label for="search">nr inwentarzowy</label>

            <input type='radio' name='search-by' value='search-by-nr-seryjny' id="search-by-nr-seryjny"/>
            <label for="search-by-nr-seryjny">nr seryjny</label>
        </div>

    </form>

    <?php
    if(isset($search_by)){
    $search_by = $_POST['search-by'];}
    $_SESSION['rzad'] = array();
    $select_query= mysqli_query($db, $select_query);

    if ($select_query->num_rows > 0) {
        while ($data = $select_query->fetch_assoc()) {
            array_push($_SESSION['rzad'], $data['id']);

            echo "<li>" .
                "<p>" . $data['id'] . "</p>" .
                "<p> nr inwentarzowy: " . $data['nr_inwentarzowy'] ."</p>" .
                "<p> nazwa: " . $data['nazwa'] . "</p>" .
                "<p> opis: " . $data['opis'] . "</p>" .
                "<p> nr seryjny: " . $data['nr_seryjny'] . "</p>" .
                "<p> data zakupu: " . date('d-m-Y', strtotime($data['data_zakupu']))  . "</p>" .
                "<p> nr faktury: " . $data['nr_faktury'] . "</p>" .
                "<p> gwarancja do: " . $data['gwarancja_do'] . "</p>" .
                "<p> kwota netto: " . $data['netto_pl'] . "</p>" .
                "<p> notatki: " . $data['notatki'] . "</p>" .
                "<p> id właściciela: " . $data['id_wlasciciela'] . "</p>" ;
            echo "<a href='deleteDevice.php?id=" . $data['id'] . "'>Usuń</a>";
            echo "<a href='editDevice.php?id=" . $data['id'] . "'>Edytuj</a>";
       }
    }


    elseif(!isset($_SESSION['e_wyszukiwanie']) &&  isset($search_by) && $search_by == 'search-by-inwentarzowy'){
        echo "Brak wyszukiwań";
    }
    elseif(!isset($_SESSION['e_nr_seryjny']) &&  isset($search_by)&& $search_by == 'search-by-nr-seryjny'){
        echo "Brak wyszukiwań";
    }


    if(isset($_SESSION['e_inwentarzowy']))
    {
        echo '<div class="error">'.$_SESSION['e_inwentarzowy'].'</div>';
        unset($_SESSION['e_inwentarzowy']);
    }

    if(isset($_SESSION['e_nr_seryjny']))
    {
        echo '<div class="error">'.$_SESSION['e_nr_seryjny'].'</div>';
        unset($_SESSION['e_nr_seryjny']);
    }

    if(isset($_SESSION['e_wyszukiwanie']))
    {
        echo '<div class="error">'.$_SESSION['e_wyszukiwanie'].'</div>';
        unset($_SESSION['e_wyszukiwanie']);
    }
    ?>





</body>
</html>