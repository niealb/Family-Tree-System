<!doctype html>
<html>
    <head>
        <title>SDG - Wylogowywanie</title>
        <meta charset="utf-8">
    </head>
    <body>
        <?php
            if (isset($_SESSION["userName"]))
            {
                $_SESSION = array();
        ?>

        Czekaj, za chwilę nastąpi przekierowane na stronę główną...

        <?php
            header("refresh: 3; url = index.php");
            }
            else
                header("location: index.php");
        ?>
    </body>
</html>