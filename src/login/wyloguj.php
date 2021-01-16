<?php

session_start();

session_unset();

$_SESSION['zalogowany'] = false;

header('Location: ../index.php');
