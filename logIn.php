<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Poppins:wght@400;500;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/dist/style.css">
    <link href="../dist/output.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Logare</title>
</head>
<body>
    <div class="flex font-sans">
        <!-- SIDEBAR -->
        <div class="topnav" id="myTopnav">
            <a href="/index.php">Harta</a>
            <a href="/adaugaMasuratoare.php">Adauga Masuratoare</a>
            <a href="/studii.php">Studii</a>
            <a href="/adaugaStudii.php">Adauga Studii</a>
            <a href="/formular.php">Devino Membru</a>
            <?php
            if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
                // Show the "Login" button when the user is not logged in
                echo '<a href="/logIn.php" style="float:right;">Login</a>';
            } else {
                // Show the "Logout" button when the user is logged in
                echo '<a href="logout.php" style="float:right;">Logout</a>';
            }
        ?>
        </div>
    </div>
    <div class="form-container">
        <form action="login.php" id="login-form" method="post">
            <div class="form-group">
                <label for="username">Utilizator:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Parola:</label>
                <input type="text" id="password" name="password" required>
            </div>
            <div class="form-group">
                <button type="submit">Autentificare</button>
            </div>
        </form>
        <div id="login-message"></div>
    </div>
    <script src="scriptLogin.js"></script> <!-- Asigurați-vă că aveți legătura corectă către fișierul JavaScript -->
</body>
</html>
