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
$msg = "";

/*
if(isset($_POST['upload']))
{
    $imageName=mysqli_real_escape_string($db,$_FILES["image"]["name"]);
    $imageData=mysqli_real_escape_string($db,base64_encode(file_get_contents($_FILES["image"]["tmp_name"])));
    $imageType=mysqli_real_escape_String($db,$_FILES["image"]["type"]);
    $image = 'data:image/' . $imageType . ';base64,' . $imageData;

    if(substr($imageType,0,5) =="image") //)if its image it will be image
    {
        $image_text = mysqli_real_escape_string($db, $_POST['notatki']);
        $data = $_POST['data'];
        $l_stron = $_POST['l_stron'];
        $sql = "INSERT INTO dokumenty VALUES (NULL,'$data','$l_stron','$image_text', '$imageData')";
        mysqli_query($db, $sql);
        header('Location: documentsSystem.php');

    }
    else
    {
        echo "not working";
    }

}*/
if (isset($_POST['upload'])) {
    $ok = true;
    $image = $_FILES['image']['name'];
    $image_text = mysqli_real_escape_string($db, $_POST['notatki']);
    $target = "images/" . basename($image);
    $data = $_POST['data'];
    $l_stron = $_POST['l_stron'];
    $_SESSION['fr_data'] = $data;
    $_SESSION['fr_l_stron'] = $l_stron;
    $_SESSION['fr_notatki'] = $image_text;

    $sql = "INSERT INTO dokumenty VALUES (NULL,'$data','$l_stron','$image_text', '$image')";
    if (substr($image, -4) == ".pdf" || substr($image, -4) == ".jpg") {
        mysqli_query($db, $sql);
        header('Location: documentsSystem.php');
    } else {

        $_SESSION['e_plik'] = "Dozowolone są tylko pliki w formacie jpg lub pdf";
    }

}


?>
<script>var today = new Date();

    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0');
    var yyyy = today.getFullYear();

    today = yyyy + '-' + mm + '-' + dd;</script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Company Manager</title>
    <link href="../../css/panel.css" type="text/css" rel="stylesheet">
    <link href="../../css/add_panel.css" type="text/css" rel="stylesheet">
</head>
<body>

<div id="dodaj_dokument" class="panel-section">
    <div class="title-section">
        <p> Dodawanie nowego dokumentu </p>
        <hr>
    </div>
    <div class="input-section-docs">
        <form method="POST" action="addDocument.php" enctype="multipart/form-data">
            <input type="file" name="image" required>
            <?php
            if (isset($_SESSION['e_plik'])) {
                echo '<div class="error">' . $_SESSION['e_plik'] . '</div>';
                unset($_SESSION['e_plik']);
            }
            ?>
            <input type="date" name="data" placeholder="data" max="<?php echo date("Y-m-d"); ?>" required
                   value="<?php
                   if (isset($_SESSION['fr_data'])) {
                       echo $_SESSION['fr_data'];
                       unset($_SESSION['fr_data']);
                   }
                   ?>">
            <input type="number" name="l_stron" placeholder="liczba stron" min="1" step="1" required
                   value="<?php
                   if (isset($_SESSION['fr_l_stron'])) {
                       echo $_SESSION['fr_l_stron'];
                       unset($_SESSION['fr_l_stron']);
                   }
                   ?>">
            <textarea rows="4" cols="30" name="notatki" placeholder="..."><?php
            if (isset($_SESSION['fr_notatki'])) {
                echo $_SESSION['fr_notatki'];
                unset($_SESSION['fr_notatki']);
            }
            ?>
</textarea>
            <button class="transparent-button" type="submit" name="upload">Dodaj</button>
        </form>
    </div>

    <!---
     <img src="showimages.php?id=3">
     -->

</div>

<a href="../panel.php" class="go-back-link">Wróc do panelu</a>

<a href="documentsSystem.php" class="go-back-link">Cofnij</a>

<!--
<?php

$sql = "select zdjecie_dokumentu from dokumenty where id=11";
$result = mysqli_query($db, $sql);
$row = mysqli_fetch_array($result);

$image_src2 = $row['zdjecie_dokumentu'];

?>
-->

</body>
</html>