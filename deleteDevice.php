<?php


session_start();

require_once('connect.php');
$db = new mysqli($host, $db_user, $db_password, $db_name);
$id = $_GET['id'];
$sql2 = @$db->query("DELETE FROM sprzety WHERE id=$id");
header('Location: devicesCatalogPanel.php');
