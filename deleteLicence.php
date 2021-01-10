<?php


session_start();

require_once('connect.php');
$db = mysqli_connect("localhost", "root", "", "company_manager3");
$id = $_GET['id'];
$sql2 = @$db->query("DELETE FROM licencje WHERE id=$id");
header('Location: licencesCatalogPanel.php');
