<?php

session_start();

require_once('connect.php');
$db = new mysqli($host, $db_user, $db_password, $db_name);

for($i = 0 ; $i < count($_SESSION['rzad']) ; $i++) {
    if (isset($_POST[$_SESSION['rzad'][$i]])) {
        $ktore = $_SESSION['rzad'][$i];
        $sql2 = @$db->query("DELETE FROM dokumenty WHERE id=$ktore");
        header('Location: documentsSystem.php');
    }
}
?>
