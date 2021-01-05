<?php

session_start();

require_once('connect.php');
$db = new mysqli($host, $db_user, $db_password, $db_name);

$query="SELECT zdjecie_dokumentu FROM dokumenty WHERE substr(Name, -4)='.pdf'";

for($i = 0 ; $i < count($_SESSION['rzad']) ; $i++) {
    if (isset($_POST[$_SESSION['rzad'][$i]])) {
        $ktore = $_SESSION['rzad'][$i];
        $sql2 = @$db->query("SELECT zdjecie_dokumentu FROM dokumenty WHERE id=$ktore");


        while ($data = $sql2->fetch_assoc()) {
            if (substr($data['zdjecie_dokumentu'], -4) == ".pdf") {

                echo "siema";
                $filename = "images/testpdf.pdf";
                header("Content-type: application/pdf");

                header("Content-Length: " . filesize($filename));

                readfile($filename);
            }
            else
            {
                echo "<img src='images/" . $data['zdjecie_dokumentu'] . "' >";
            }
        }


    }
}
?>
