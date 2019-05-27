<?php
    class PersonSearch
    {
        protected $table
                  , $error;

        function GetTable()
        {
            return $this->table;
        }

        function GetError()
        {
            return $this->error;
        }

        function Load(&$connection, $id, $ownerId, $firstName, $secondName, $surname,
                            $sex, $placeOfBirth, $dateOfBirth, $dateOfDeath, $biographicalNote)
        {
            $condition = " where 1";

            if (!empty($id))
                $condition .= " and id = $id";

            if (!empty($ownerId))
                $condition .= " and owner_id = $ownerId";

            if (!empty($firstName))
                $condition .= " and first_name = '$firstName'";

            if (!empty($secondName))
                $condition .= " and second_name = '$secondName'";

            if (!empty($surname))
                $condition .= " and surname = '$surname'";

            if (!empty($sex))
                $condition .= " and sex = $sex";

            if (!empty($placeOfBirth))
                $condition .= " and place_of_birth = '$placeOfBirth'";

            if (!empty($dateOfBirth))
                $condition .= " and date_of_birth = '$dateOfBirth'";

            if (!empty($dateOfDeath))
                $condition .= " and date_of_death = '$dateOfDeath'";

            if (!empty($biographicalNote))
                $condition .= " and biographicalNote = '$biographicalNote'";

            $query = "select * from persons$condition";
            $result = $connection->query($query);

            if (!$result)
            {
                $this->error = "Coś poszło nie tak.";
                return false;
            }

            if ($result->num_rows == 0)
            {
                $this->error = "Nie znaleziono takiej osoby.";
                return false;
            }

            $this->table = mysqli_fetch_all($result, MYSQLI_ASSOC);

            return true;
        }
    }
?>