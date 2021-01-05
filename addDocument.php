<?php

session_start();

require_once('connect.php');
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
    $ok=true;
    $image = $_FILES['image']['name'];
    $image_text = mysqli_real_escape_string($db, $_POST['notatki']);
    $target = "images/".basename($image);
    $data = $_POST['data'];
    $l_stron = $_POST['l_stron'];
    $sql = "INSERT INTO dokumenty VALUES (NULL,'$data','$l_stron','$image_text', '$image')";
    if(substr($image, -4) == ".pdf" || substr($image, -4) == ".jpg")
    { mysqli_query($db, $sql);
    header('Location: documentsSystem.php');}
    else
    {

        $_SESSION['e_plik']= "Dozowolone są tylko pliki w formacie jpg lub pdf";
    }

}





?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Company Manager</title>
    <link href="style_inside.css" type="text/css" rel="stylesheet">
</head>
<body>
<div id="dodaj_dokument" >
    <h1> Dodawanie nowego dokumentu </h1>
    <form method="POST" action="addDocument.php" enctype="multipart/form-data">
        <input type="file" name="image" required>
        <?php
        if(isset($_SESSION['e_plik']))
        {
            echo '<div class="error2">'.$_SESSION['e_plik'].'</div>';
            unset($_SESSION['e_plik']);
        }
        ?>
        <input type="date" name="data" placeholder="data" required>
        <input type="number" name="l_stron" placeholder="liczba stron" min="1" step="1" required>
        <textarea rows="4" cols="30" name="notatki" placeholder="...">
</textarea>
        <button type="submit" name="upload">Dodaj</button>
    </form>
   <!---
    <img src="showimages.php?id=3">
    -->
    <a href="documentsSystem.php">Cofnij</a>
    <br>
    <a href="panel.php">Wróc do panelu</a>

</div>
<!--
<?php

$sql = "select zdjecie_dokumentu from dokumenty where id=11";
$result = mysqli_query($db,$sql);
$row = mysqli_fetch_array($result);

$image_src2 = $row['zdjecie_dokumentu'];

?>
-->

</body>
</html>