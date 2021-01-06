<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Company Manager</title>
    <link href="style.css" type="text/css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Arbutus&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=ABeeZee&display=swap" rel="stylesheet">
</head>
<body>
<main>

    <form action="wyloguj.php" method="post">
        <button type="submit">Wyloguj się</button>
    </form>
    <div class="panel">
        <button onclick="window.location.href = 'addUserPanel.php';"> stwórz użytkownika</button>
        <button onclick="window.location.href = 'invoices/salesInvoicePanel.php';"> katalog faktur sprzedaży</button>
        <button> katalog faktur zakupu</button>
        <button onclick="window.location.href = 'documentsSystem.php';"> podsystem dokumentów</button>
        <button onclick="window.location.href = 'devicesCatalogPanel.php';"> podsystem katalogów sprzętu</button>
        <button> podsystem licecji</button>
    </div>

</main>
</body>
</html>
