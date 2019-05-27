<!doctype html>
<html>
    <head>
        <title>SDG - Osoba</title>
        <meta charset="utf-8">
    </head>
    <body>
        <?php
            $errors = [];

            if (!isset($_SESSION["userName"]))
                header("location: index.php");

            if (isset($_GET["id"]))
            {
                $connection = new mysqli("localhost", "root", '', "sdg");
                $connection->set_charset("utf8");

                if ($connection->connect_error)
                    array_push($errors, "Połączenie z bazą danych nie udało się.");
                else
                {
                    $query = "select persons.*, sexes.name as sex from persons join
                            sexes on persons.sex = sexes.id where persons.id=".$_GET["id"];
                    $result = $connection->query($query);
                    $connection->close();

                    if (!$result)
                        array_push($errors, "Wystąpił nieoczekiwany błąd.");
                    else if ($result->num_rows === 1)
                        $row = $result->fetch_assoc();
                    else
                        array_push($errors, "Nie znaleziono osoby.");
                }

                // Gdy nie zgłoszono żadnego błędu (znaleziono osobę)...
                if (sizeof($errors) === 0)
                {
        ?>

        ID: <?php echo $row["id"] ?><br>
        Imię: <?php echo $row["first_name"] ?><br>
        Drugie imię: <?php if (!empty($row["second_name"])) echo $row["second_name"]; else echo 'Brak' ?><br>
        Nazwisko: <?php echo $row["surname"] ?><br>
        Płeć: <?php echo $row["sex"] ?><br>
        Miejsce urodzenia: <?php echo $row["place_of_birth"] ?><br>
        Data urodzenia: <?php echo $row["date_of_birth"] ?><br>
        Data śmierci: <?php if (!empty($row["date_of_death"])) echo $row["date_of_death"]; else echo 'Brak' ?><br>
        Notka biograficzna: <?php if (!empty($row["biographical_note"]))
                                echo $row["biographical_note"]; else echo 'Brak' ?><br>

        <?php
                    // ... i gdy zalogowany użytkownik jest założycielem profilu tej osoby.
                    if ($_SESSION["id"] === $row["owner_id"])
                    {
        ?>

        <a href="deleteperson.php?id=<?php echo $row["id"] ?>">Usuń osobę</a>

        <?php
                    }
                }
                // Gdy nie znaleziono osoby.
                else
                {
        ?>

        Nie znaleziono osoby o podanym ID.

        <?php
                }
            }
            // Gdy nie przesłano formularza.
            else
            {
        ?>

        Podaj ID profilu osoby.

        <?php
            }
        ?>
    </body>
</html>