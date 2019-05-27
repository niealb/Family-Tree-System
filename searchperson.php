<?php
    require_once('classes/PersonSearch.php');
?>

<!doctype html>
<html>
    <head>
        <title>SDG - Szukaj osób</title>
        <meta charset="utf-8">

        <style>
            #search-result
            {
                width: 500px;
                height: 200px;
                background-color: grey;
                overflow: auto;
            }
        </style>
    </head>
    <body>
        <form method="post">
            <input type="text" name="firstName" placeholder="Imię"><br>
            <input type="text" name="secondName" placeholder="Drugie imię"><br>
            <input type="text" name="surname" placeholder="Nazwisko"><br>
            <input type="text" name="placeOfBirth" placeholder="Miejsce urodzenia"><br>
            <input type="submit" value="Szukaj">
        </form>

        <section id="search-result">
            <?php
                $personSearch = new PersonSearch();

                $connection = new mysqli("localhost", "root", '', "sdg");
                $connection->set_charset("utf8");

                if (isset($_POST['firstName']))
                    $success = $personSearch->Load($connection, null, null, $_POST['firstName'], $_POST['secondName'], $_POST['surname'], null, $_POST['placeOfBirth'], null, null, null);
                else
                    $success = $personSearch->Load($connection, null, null, null, null, null, null, null, null, null, null);

                if ($success)
                    foreach ($personSearch->GetTable() as $row)
                        echo '<a href="person.php?id='.$row['id'].'">'.$row['first_name'].' '.$row['second_name'].' '.$row['surname'].'</a>, '.$row['place_of_birth'].'<br>';
                else
                    echo $personSearch->GetError();
            ?>
        </section>
    </body>
</html>

<?php
    $connection->close();
?>