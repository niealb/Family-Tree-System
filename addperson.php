<!doctype html>
<html>
    <head>
        <title>SDG - Dodaj osobę</title>
        <meta charset="utf-8">
    </head>
    <body>
        <?php
            $errors = [];
            $messages = [];

            // Gdy użytkownik nie jest zalogowany...
            if (!isset($_SESSION["userName"]))
                header("location: signin.php");

            // Gdy wysłano formularz...
            if (isset($_POST["firstName"]))
            {
                if (empty($_POST["firstName"]) || empty($_POST["surname"]) || empty($_POST["dateOfBirth"])
                    || empty($_POST["sex"]) || empty($_POST["placeOfBirth"]))
                    array_push($errors, "Wszystkie obowiązkowe pola muszą być wypełnione.");
                else
                {
                    if (strlen($_POST["firstName"]) > 20 || strlen($_POST["secondName"]) > 20)
                        array_push($errors, "Imiona powinny mieć do 20 znaków.");

                    if (strlen($_POST["surname"]) > 30)
                        array_push($errors, "Nazwisko powinno mieć do 30 znaków.");

                    if (strlen($_POST["placeOfBirth"]) > 30)
                        array_push($errors, "Miejsce urodzenia powinno mieć do 30 znaków.");

                    if (date("Y-m-d") < $_POST["dateOfBirth"] || (isset($_POST["dateOfDeath"])
                        && date("Y-m-d") < $_POST["dateOfDeath"]))
                        array_push($errors, "Daty muszą być datami dzisiejszymi lub wcześniejszymi.");

                    /* ##### Narazie zakładamy, że użytkownik nie modyfikuje skryptu HTML. #####
                    if (!($_POST["sex"] == "male" || $_POST["sex"] == "female"))
                        array_push($errors, "Nieprawidłowa płeć."); */

                    // Gdy nie wykryto błędów...
                    if (sizeof($errors) === 0)
                    {
                        $connection = new mysqli("localhost", "root", '', "sdg");
                        $connection->set_charset("utf8");

                        if ($connection->connect_error)
                            array_push($errors, "Połączenie z bazą danych nie udało się.");
                        else
                        {
                            // ***********************************
                            // ***** KONSTRUOWANIE ZAPYTANIA *****
                            // ***********************************

                            $query = "insert into persons (owner_id, first_name, surname, place_of_birth, date_of_birth, sex,
                                    second_name, date_of_death, biographical_note)
                                    values (".$_SESSION["id"].", '".$_POST["firstName"]."', '".$_POST["surname"]."',
                                    '".$_POST["placeOfBirth"]."', '".$_POST["dateOfBirth"]."'";

                            // ##### Lepiej zrobić w formie zapytania... #####
                            if ($_POST["sex"] === "male")
                                $query .= ", 2";
                            else
                                $query .= ", 1";

                            // Drugie imię...
                            if (empty($_POST["secondName"]))
                                $query .= ", null";
                            else
                                $query .= ", '".$_POST["secondName"]."'";

                            // Data śmierci...
                            if (empty($_POST["dateOfDeath"]))
                                $query .= ", null";
                            else
                                $query .= ", '".$_POST["dateOfDeath"]."'";

                            // Notka biograficzna...
                            if (empty($_POST["biographicalNote"]))
                                $query .= ", null";
                            else
                                $query .= ", '".$_POST["biographicalNote"]."'";

                            $query .= ")";

                            // ***********************************

                            $result = $connection->query($query);

                            $connection->close();

                            if (!$result)
                                array_push($errors, "Wystąpił nieoczekiwany błąd.");
                            else
                                array_push($messages, "Osoba została dodana.");
                        }
                    }
                }
            }
        ?>

        <form method="post">
            <?php
                foreach ($errors as $error)
                    echo $error.'<br>';

                foreach ($messages as $message)
                    echo $message.'<br>';
            ?>

            <input type="text" name="firstName" placeholder="Imię"
            value="<?php if (!empty($_POST["firstName"])) echo $_POST["firstName"] ?>"><br>

            <input type="text" name="secondName" placeholder="Drugie imię (opcjonalne)"
            value="<?php if (!empty($_POST["secondName"])) echo $_POST["secondName"] ?>"><br>
            
            <input type="text" name="surname" placeholder="Nazwisko"
            value="<?php if (!empty($_POST["surname"])) echo $_POST["surname"] ?>"><br>

            Płeć: <input type="radio" name="sex" value="male"
                <?php if (!empty($_POST["sex"]) && $_POST["sex"] === "male") echo 'checked' ?>> Mężczyzna
                <input type="radio" name="sex" value="female"
                <?php if (!empty($_POST["sex"]) && $_POST["sex"] === "female") echo 'checked' ?>> Kobieta<br>

            <input type="text" name="placeOfBirth" placeholder="Miejsce urodzenia"
            value="<?php if (!empty($_POST["placeOfBirth"])) echo $_POST["placeOfBirth"] ?>"><br>

            Data urodzenia: <input type="date" name="dateOfBirth"
                            value="<?php if (!empty($_POST["dateOfBirth"])) echo $_POST["dateOfBirth"] ?>"><br>

            Data śmierci (opcjonalne): <input type="date" name="dateOfDeath"
                                        value="<?php if (!empty($_POST["dateOfDeath"])) echo $_POST["dateOfDeath"] ?>"><br>

            <textarea name="biographicalNote" rows="5" cols="50" placeholder="Notka biograficzna (opcjonalne)"><?php if (!empty($_POST["biographicalNote"])) echo $_POST["biographicalNote"]; ?></textarea><br>

            <button type="submit">Wyślij</button>
        </form>
    </body>
</html>