<html>
    <head>
        <title>SDG - Rejestracja</title>
        <meta charset="utf-8">
    </head>
    <body>
        <?php
            $errors = [];
            $messages = [];

            if (isset($_SESSION["userName"]))
                header("location: index.php");

            if (isset($_POST["userName"]))
            {
                if (empty($_POST["email"]) || empty($_POST["userName"]) || empty($_POST["password"])
                    || empty($_POST["passwordRepeat"]))
                    array_push($errors, "Wszystkie pola muszą być wypełnione.");
                else
                {
                    // Gdy wszystkie pola są wypełnione to sprawdzamy ich poprawność.

                    if (strlen($_POST["userName"]) < 4 || strlen($_POST["userName"]) > 20)
                        array_push($errors, "Nazwa użytkownika powinna mieć od 4 do 20 znaków.");

                    if (strlen($_POST["password"]) < 4 || strlen($_POST["password"]) > 20)
                        array_push($errors, "Hasło powinna mieć od 4 do 20 znaków.");

                    if ($_POST["password"] != $_POST["passwordRepeat"])
                        array_push($errors, "Hasła różnią się.");

                    if (sizeof($errors) === 0)
                    {
                        // Gdy nie znaleziono żadnych błędów to łączymy się z bazą danych.

                        $connection = new mysqli("localhost", "root", '', "sdg");
                        $connection->set_charset("utf8");

                        if ($connection->connect_error)
                            array_push($errors, "Połączenie z bazą danych nie udało się.");
                        else
                        {
                            /* Gdy połączyliśmy się z bazą danych to sprawdzamy czy istnieje w bazie użytkownik
                            o przesłanej nazwie lub adresie. */

                            $query = "SELECT id FROM users WHERE name='".$_POST["userName"]."' OR email='".$_POST["email"]."'";

                            $result = $connection->query($query);

                            if (!$result)
                                array_push($errors, "Wystąpił nieoczekiwany błąd.");
                            else if ($result->num_rows !== 0)
                                array_push($errors, "Użytkownik o podanej nazwie lub adresie już istnieje.");
                            else
                            {
                                /* Gdy nie ma użytkownika o przesłanej nazwie lub adresie to dodajemy
                                takiego do bazy danych. */

                                $query = "INSERT INTO users (rank_id, name, password, email, date_of_registration)
                                        VALUES(2, '".$_POST["userName"]."', '".$_POST["password"]."',
                                        '".$_POST["email"]."', sysdate())";

                                $result = $connection->query($query);

                                $connection->close();

                                if (!$result)
                                    array_push($errors, "Wystąpił nieoczekiwany błąd.");
                                else
                                    array_push($messages, "Rejestracja powiodła się!");
                            }
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

            <input type="text" name="userName" placeholder="Nazwa użytkownika"
            value="<?php if (isset($_POST["userName"])) echo $_POST["userName"] ?>"><br>

            <input type="password" name="password" placeholder="Hasło"
            value="<?php if (isset($_POST["password"])) echo $_POST["password"] ?>"><br>

            <input type="password" name="passwordRepeat" placeholder="Powtórz hasło"
            value="<?php if (isset($_POST["passwordRepeat"])) echo $_POST["passwordRepeat"] ?>"><br>

            <input type="email" name="email" placeholder="E-mail"
            value="<?php if (isset($_POST["email"])) echo $_POST["email"] ?>"><br>
            
            <button type="submit">Wyślij</button>
        </form>
    </body>
</html>