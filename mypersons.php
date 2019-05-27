<?php
    require_once('classes/PersonSearch.php');
?>

<!doctype html>
<html>
    <head>
        <title>SDG - Moje osoby</title>
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
        <section id="search-result">
            <?php
                $personSearch = new PersonSearch();

                $connection = new mysqli("localhost", "root", '', "sdg");
                $connection->set_charset("utf8");

                if ($personSearch->Load($connection, null, $_SESSION["id"], null, null, null, null, null, null, null, null))
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