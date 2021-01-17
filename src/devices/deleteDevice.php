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
$id = $_GET['id'];
$sql2 = @$db->query("DELETE FROM sprzety WHERE id=$id");
header('Location: devicesCatalogPanel.php');
