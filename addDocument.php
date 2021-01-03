<?php

session_start();

$db = mysqli_connect("localhost", "root", "admin", "company_manager");
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
    $image = $_FILES['image']['name'];
    $image_text = mysqli_real_escape_string($db, $_POST['notatki']);
    $target = "images/".basename($image);
    $data = $_POST['data'];
    $l_stron = $_POST['l_stron'];
    $sql = "INSERT INTO dokumenty VALUES (NULL,'$data','$l_stron','$image_text', '$image')";
    mysqli_query($db, $sql);
    header('Location: documentsSystem.php');
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
        <input type="date" name="data" placeholder="data" required>
        <input type="text" name="l_stron" placeholder="liczba stron" required>
        <textarea rows="4" cols="30" name="notatki" placeholder="...">
</textarea>
        <button type="submit" name="upload">Dodaj</button>
    </form>
   <!---
    <img src="showimages.php?id=3">
    -->
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