<!doctype html>
<html>
    <head>
        <title>SDG - Strona główna</title>
        <meta charset="utf-8">
    </head>
    <body>
        <?php
            if (isset($_SESSION["userName"]))
            {
        ?>

        <a href="signout.php">Wyloguj się</a><br>
        <a href="addperson.php">Dodaj osobę</a><br>
        <a href="searchperson.php">Szukaj osób</a><br>
        <a href="mypersons.php">Moje osoby</a>

        <?php
            }
            else
            {
        ?>

        <a href="signin.php">Zaloguj się</a><br>
        <a href="signup.php">Zarejestruj się</a>

        <?php
            }
        ?>
    </body>
</html>