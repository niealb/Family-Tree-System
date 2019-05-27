<!doctype html>
<html>
    <head>
        <title>SDG - Usuń osobę</title>
        <meta charset="utf-8">
    </head>
    <body>
        <?php
            $errors = [];

            if (!isset($_SESSION["userName"]))
                header("location: index.php");

            $connection = new mysqli("localhost", "root", '', "sdg");
            $connection->set_charset("utf8");

            if ($connection->connect_error)
                array_push($errors, "Połączenie z bazą danych nie udało się.");
            else
            {
                $query = "select owner_id from persons where id=".$_GET["id"];
                $result = $connection->query($query);

                if (!$result)
                    array_push($errors, "Wystąpił nieoczekiwany błąd.");
                else if ($result->num_rows === 1)
                    $row = $result->fetch_assoc();
                else
                    array_push($errors, "Nie znaleziono osoby.");

                if ($row["owner_id"] !== $_SESSION["id"])
                    array_push($errors, "Nie możesz tego zrobić.");
                else
                {
                    $query = "delete from persons where id=".$_GET["id"];
                    $result = $connection->query($query);
                    $connection->close();

                    if (!$result)
                        array_push($errors, "Operacja nie powiodła się!");
                }
            }

            if (sizeof($errors) === 0)
            {
        ?>

        Operacja powiodła się. Za chwilę nastąpi przekierowanie na stronę główną.

        <?php
                header("refresh: 3; url = index.php");
            }
            else
                foreach ($errors as $error)
                    echo $error.'<br>';
        ?>
    </body>
</html>