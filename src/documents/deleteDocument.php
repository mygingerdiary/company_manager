<?php

session_start();

if ((!isset($_SESSION['zalogowany'])) || ($_SESSION['zalogowany'] == false)) {
    header('Location: ../index.php');
}

if (!isset($_SESSION['rola_uzytkownika']) || $_SESSION['rola_uzytkownika'] == 'auditor') {
    header('Location: ../notAllowed.php');
    exit();
}

require_once('../connect.php');
$db = new mysqli($host, $db_user, $db_password, $db_name);

if ($db->connect_errno != 0) {
    echo "ERROR: " . $db->connect_errno;
}
else {
    for ($i = 0; $i < count($_SESSION['rzad']); $i++) {
        if (isset($_POST[$_SESSION['rzad'][$i]])) {
            $ktore = $_SESSION['rzad'][$i];
            echo "<script>console.log('$ktore');</script>";
            $sql1=$db->query("UPDATE faktury SET id_dokumentu=NULL WHERE id_dokumentu=$ktore");
            $sql2 = $db->query("DELETE FROM dokumenty WHERE id=$ktore");
            header('Location: documentsSystem.php');
        }
    }
}
?>
