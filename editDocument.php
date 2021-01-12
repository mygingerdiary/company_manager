<?php

session_start();
const REMEMBER_DATA = 'remember_data';
const REMEMBER_L_STRON = 'remember_l_stron';
const REMEMBER_NOTATKI = 'remember_notatki';
const REMEMBER_IMG = 'remember_imh';



require_once('connect.php');
$db = new mysqli($host, $db_user, $db_password, $db_name);
for($i = 0 ; $i < count($_SESSION['rzad']) ; $i++) {
    if (isset($_POST[$_SESSION['rzad'][$i]])) {
        $ktore = $_SESSION['rzad'][$i];
        $_SESSION["myvar"] = $ktore;

    }
}

$docs_select = "SELECT * FROM dokumenty WHERE id=$ktore";
$docs_result = mysqli_query($db, $docs_select);

if ($docs_result->num_rows > 0) {
    while ($data = $docs_result->fetch_assoc()) {
        $_SESSION[REMEMBER_DATA] = $data['data'];
        $_SESSION[REMEMBER_L_STRON] = $data['l_stron'];
        $_SESSION[REMEMBER_NOTATKI] = $data['notatki'];
        $_SESSION[REMEMBER_IMG] = $data['zdjecie_dokumentu'];
    }
}
//echo "<script>console.log('$$ktore');</script>";

if (isset($_POST['upload'])) {

    $ok=true;
    $image = $_FILES['image']['name'];
    $image_text = mysqli_real_escape_string($db, $_POST['notatki']);
    $target = "images/".basename($image);
    $data = $_POST['data'];
    $l_stron = $_POST['l_stron'];
    $ktore=$_SESSION["myvar"];
    $zdjecie=$_SESSION[REMEMBER_IMG];

    if($_FILES["image"]["error"] == 4) {
        $sql = "UPDATE dokumenty SET data='$data',l_stron='$l_stron',notatki='$image_text' WHERE id=$ktore";
        if (mysqli_query($db, $sql)) {
            header('Location: documentsSystem.php');
        }}

    if($_FILES["image"]["error"] != 4) {
        $sql = "UPDATE dokumenty SET data='$data',l_stron='$l_stron',notatki='$image_text',zdjecie_dokumentu='$image' WHERE id=$ktore";
        if (substr($image, -4) == ".pdf" || substr($image, -4) == ".jpg" ) {
            if (mysqli_query($db, $sql)) {
                header('Location: documentsSystem.php');
            }
        } else {

            $_SESSION['e_plik'] = "Dozowolone są tylko pliki w formacie jpg lub pdf";
        }
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
    <h1> Edycja dokumentu </h1>
    <form method="POST" action="editDocument.php" enctype="multipart/form-data">
        <input type="file" name="image">
        <?php
        if(isset($_SESSION['e_plik']))
        {
            echo '<div class="error2">'.$_SESSION['e_plik'].'</div>';
            unset($_SESSION['e_plik']);
        }
        ?>
        <input type="date" name="data" placeholder="data" value= <?php
        if (isset($_SESSION[REMEMBER_DATA])) {
            echo $_SESSION[REMEMBER_DATA];
            unset($_SESSION[REMEMBER_DATA]);
        }
        ?> required>

        <input type="number" name="l_stron" placeholder="liczba stron" min="1" step="1" value= <?php
        if (isset($_SESSION[REMEMBER_L_STRON])) {
            echo $_SESSION[REMEMBER_L_STRON];
            unset($_SESSION[REMEMBER_L_STRON]);
        }
        ?> required>

        <textarea rows="4" cols="30" name="notatki" placeholder="...">
            <?php
            if (isset($_SESSION[REMEMBER_NOTATKI])) {
                echo htmlspecialchars($_SESSION[REMEMBER_NOTATKI]);
                unset($_SESSION[REMEMBER_NOTATKI]);
            }
            ?>
</textarea>
        <button type="submit" name="upload">Zaktualizuj</button>
    </form>
    <!---
     <img src="showimages.php?id=3">
     -->
    <a href="documentsSystem.php">Cofnij</a>
    <br>
    <a href="panel.php">Wróc do panelu</a>

</div>

<form method="POST">
    <input type="hidden" name="word" value="<?php $ktore; ?>" />
</form>
<!--
<?php

$sql = "select zdjecie_dokumentu from dokumenty where id=11";
$result = mysqli_query($db,$sql);
$row = mysqli_fetch_array($result);

$image_src2 = $row['zdjecie_dokumentu'];

?>
-->

</body>

