<?php

session_start();

if ((!isset($_SESSION['zalogowany'])) || ($_SESSION['zalogowany'] == false)) {
    header('Location: ../index.php');
}

$id = $_GET['id'];

require_once('../connect.php');
$db = new mysqli($host, $db_user, $db_password, $db_name);

$query = "SELECT zdjecie_dokumentu FROM dokumenty WHERE substr(Name, -4)='.pdf'";

$sql2 = @$db->query("SELECT zdjecie_dokumentu FROM dokumenty WHERE id=$id");

while ($data = $sql2->fetch_assoc()) {
    if (substr($data['zdjecie_dokumentu'], -4) == ".pdf") {

        $filename = "../images/testpdf.pdf";
        header("Content-type: application/pdf");

        header("Content-Length: " . filesize($filename));

        readfile($filename);
    } else {
        echo "<img src='../images/" . $data['zdjecie_dokumentu'] . "' >";
    }

}
?>