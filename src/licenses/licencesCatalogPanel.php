<?php
session_start();

if ((!isset($_SESSION['zalogowany'])) || ($_SESSION['zalogowany'] == false)) {
    header('Location: ../index.php');
}

require_once('../connect.php');
$db = new mysqli($host, $db_user, $db_password, $db_name);
$select_query = "SELECT * FROM licencje";

if (isset($_POST['napis']) && isset($_POST['search-by'])) {
    $napis = $_POST['napis'];
    $search_by = $_POST['search-by'];

    if ($search_by == 'search-by-inwentarzowy') {
        if (!is_numeric($napis) && strlen($napis) > 0) {
            $_SESSION['e_inwentarzowy'] = "Numer inwentarzowy zawiera tylko liczby ";
            $select_query = "SELECT * FROM licencje WHERE 1 = 0";
        }
    }
    if ($search_by == 'search-by-seryjny') {
        if (!preg_match('/^[a-zA-Z0-9\-\/]+/', $napis)) {
            $_SESSION['e_nr_seryjny'] = "Numer seryjny zawiera litery, cyfry lub znaki specjalne: - /";
        }
    }
}
if (isset($_POST['submit']) && !isset($_POST['search-by']) && strlen($_POST['napis']) > 0 && isset($_POST['napis'])) {

    $_SESSION['e_wyszukiwanie'] = "Nie wybrano po czym wyszukiwać ";
    $select_query = "SELECT * FROM licencje WHERE 1 = 0";

}


if (isset($_POST['submit']) && isset($_POST['search-by']) && !isset($_SESSION['e_wyszukiwanie'])) {

    $napis = $db->real_escape_string($_POST['napis']);
    $napis = intval($napis);
    $search_by = $_POST['search-by'];

    if ($search_by == 'search-by-inwentarzowy') {
        echo "<script>console.log('$select_query');</script>";
        echo "<script>console.log('$search_by');</script>";
        $select_query = "SELECT * FROM licencje WHERE nr_inwentarzowy=$napis";

    } elseif ($search_by == 'search-by-nr-seryjny') {

        $select_query = "SELECT * FROM licencje WHERE klucz_seryjny=$napis";
    }

}
if (isset($_POST['submit']) && !isset($_POST['search-by']) && strlen($_POST['napis']) == 0 && isset($_POST['napis'])) {


    $select_query = "SELECT * FROM licencje";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Katalog licencji</title>
    <link href="../../css/panel.css" type="text/css" rel="stylesheet">
    <link href="../../css/invoices_panel.css" type="text/css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" rel="stylesheet">
</head>
<body>

<h1>Podsystem licencji</h1>

<form method="post" action="licencesCatalogPanel.php">
    <div class="search-bar">
        <input type="text" placeholder="szukana fraza..." name="napis">
        <button type="submit" name="submit"><i class="fa fa-search"></i></button>
    </div>
    <div class="search-by-selection">
        <p>Szukaj po:</p>

        <input type='radio' name='search-by' value='search-by-inwentarzowy' id="search-by-inwentarzowy"/>
        <label for="search-by-inwentarzowy">nr inwentarzowy</label>

        <input type='radio' name='search-by' value='search-by-nr-seryjny' id="search-by-nr-seryjny"/>
        <label for="search-by-nr-seryjny">nr seryjny</label>
    </div>

</form>

<div class="panel-section">

    <div class="title-section">
        <button class="transparent-button" onclick="window.location.href = 'addLicence.php';">Dodaj licencję <i
                    class="fas fa-plus"></i></button>
    </div>
    <ol class="invoice-list">
        <?php
        if (isset($search_by)) {
            $search_by = $_POST['search-by'];
        }
        $_SESSION['rzad'] = array();
        $select_query = mysqli_query($db, $select_query);

        if ($select_query->num_rows > 0) {
            while ($data = $select_query->fetch_assoc()) {
                array_push($_SESSION['rzad'], $data['id']);

                echo "<li>" .
                    "<p> id: " . $data['id'] . "</p>" .
                    "<p> nr inwentarzowy: " . $data['nr_inwentarzowy'] . "</p>" .
                    "<p> nazwa: " . $data['nazwa'] . "</p>" .
                    "<p> opis: " . $data['opis'] . "</p>" .
                    "<p> klucz seryjny: " . $data['klucz_seryjny'] . "</p>" .
                    "<p> data zakupu: " . date('d-m-Y', strtotime($data['data_zakupu'])) . "</p>" .
                    "<p> nr faktury: " . $data['id_faktury'] . "</p>" .
                    "<p> wsparcie do: " . $data['wsparcie_do'] . "</p>" .
                    "<p> notatki: " . $data['notatki'] . "</p>" .
                    "<p> id właściciela: " . $data['id_wlasciciela'] . "</p>";
                echo "<div class='update-section'>";
                echo "<a class='update-button delete-button' href='deleteLicence.php?id=" . $data['id'] . "'>Usuń</a>";
                echo "<a class='update-button' href='editLicence.php?id=" . $data['id'] . "'>Edytuj</a>";
                echo "</div>";
            }
        } elseif (!isset($_SESSION['e_wyszukiwanie']) && isset($search_by) && $search_by == 'search-by-inwentarzowy') {
            echo "Brak wyszukiwań";
        } elseif (!isset($_SESSION['e_nr_seryjny']) && isset($search_by) && $search_by == 'search-by-nr-seryjny') {
            echo "Brak wyszukiwań";
        }


        if (isset($_SESSION['e_inwentarzowy'])) {
            echo '<div class="error">' . $_SESSION['e_inwentarzowy'] . '</div>';
            unset($_SESSION['e_inwentarzowy']);
        }

        if (isset($_SESSION['e_nr_seryjny'])) {
            echo '<div class="error">' . $_SESSION['e_nr_seryjny'] . '</div>';
            unset($_SESSION['e_nr_seryjny']);
        }

        if (isset($_SESSION['e_wyszukiwanie'])) {
            echo '<div class="error">' . $_SESSION['e_wyszukiwanie'] . '</div>';
            unset($_SESSION['e_wyszukiwanie']);
        }
        ?>
    </ol>

</div>


<a href="../panel.php"> Wróć do panelu </a>

</body>
</html>