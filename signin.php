<!doctype html>
<html>
    <head>
        <title>SDG - Logowanie</title>
        <meta charset="utf-8">
    </head>
    <body>
        <?php
            $errors = [];

            if (isset($_SESSION["userName"]))
                header("location: index.php");

            if (isset($_POST["userName"]))
            {
                if (empty($_POST["userName"]) || empty($_POST["password"]))
                    array_push($errors, "Wszystkie pola muszą być wypełnione.");
                else
                {
                    $connection = new mysqli("localhost", "root", '', "sdg");
                    $connection->set_charset("utf8");

                    if ($connection->connect_error)
                            array_push($errors, "Połączenie z bazą danych nie udało się.");
                    else
                    {
                        $query = "SELECT * FROM users WHERE name = '".$_POST['userName']."'
                                AND password = '".$_POST['password']."'";
                        $result = $connection->query($query);
                        $connection->close();

                        if (!$result)
                            array_push($errors, "Wystąpił nieoczekiwany błąd.");
                        else if ($result->num_rows === 1)
                        {
                            $row = $result->fetch_assoc();
            
                            $_SESSION["id"] = $row["id"];
                            $_SESSION["userName"] = $row["name"];
                            $_SESSION["password"] = $row["password"];
                            $_SESSION["email"] = $row["email"];
                            $_SESSION["dateOfRegistration"] = $row["date_of_registration"];
            
                            header("location: index.php");
                        }
                        else
                            array_push($errors, "Nieprawidłowa nazwa lub hasło.");
                    }
                }
            }
        ?>

        <form method="post">
            <?php
                foreach ($errors as $error)
                    echo $error.'<br>';
            ?>

            <input type="text" name="userName" placeholder="Nazwa użytkownika"
            value="<?php if (isset($_POST["userName"])) echo $_POST["userName"] ?>"><br>

            <input type="password" name="password" placeholder="Hasło"
            value="<?php if (isset($_POST["password"])) echo $_POST["password"] ?>"><br>
            
            <button type="submit">Wyślij</button>
        </form>
    </body>
</html>