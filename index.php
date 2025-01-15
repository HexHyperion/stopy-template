<!DOCTYPE html>
<html lang="PL">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Test</title>
    </head>
    <body>
        <h1>Test</h1>
        <form method="POST">
        <?php
            // Database connection details
            $login = 'root';
            $password = '';
            $db_name = 'su_php';
            $users_table = 'uzytkownicy';
            $items_table = 'produkty';
            $orders_table = 'zamowienia';

            // Establishing connection to the database
            $db = new mysqli('localhost', $login, $password, $db_name);
            if ($db->connect_error) {
                die('Connection failed: ' . $db->connect_error);
            } else {
                // SQL query to fetch order details along with user and item details
                $sql = "SELECT idZamowienia, imie, nazwisko, nazwa, cena, ilosc, komentarze 
                        FROM $users_table 
                        INNER JOIN $orders_table ON $users_table.idUzytkownika = $orders_table.idUzytkownika 
                        INNER JOIN $items_table ON $orders_table.idProduktu = $items_table.idProduktu 
                        ORDER BY idZamowienia";
                $result = $db -> query($sql);
                $result_array = $result -> fetch_all(MYSQLI_ASSOC);

                // Displaying the fetched data in a table
                echo '<table border=1>';
                echo '<tr>';
                foreach ($result_array[0] as $key => $value) {
                    echo '<th>' . $key . '</th>';
                }
                echo '</tr>';

                // Loop through each row of the result set
                foreach ($result_array as $row) {
                    // Check if the current row is being edited
                    if (isset($_POST['edit']) && $_POST['edit'] == $row['idZamowienia']) {
                        echo '<tr>';
                        echo '<form method="POST">';
                        echo '<input type="hidden" name="idZamowienia" value="' . $row['idZamowienia'] . '">';
                        echo '<td>' . $row['idZamowienia'] . '</td>';
                        echo '<td>' . $row['imie'] . '</td>';

                        // Dropdown for selecting user
                        echo '<td><select name="nazwisko">';
                        $sql = "SELECT idUzytkownika, nazwisko FROM $users_table";
                        $result = $db -> query($sql);
                        $result_array = $result -> fetch_all(MYSQLI_ASSOC);
                        foreach ($result_array as $user) {
                            $selected = $user['nazwisko'] == $row['nazwisko'] ? 'selected' : '';
                            echo '<option value="' . $user['idUzytkownika'] . '" ' . $selected . '>' . $user['nazwisko'] . '</option>';
                        }
                        echo '</select></td>';

                        // Dropdown for selecting item
                        echo '<td><select name="nazwa">';
                        $sql = "SELECT idProduktu, nazwa FROM $items_table";
                        $result = $db -> query($sql);
                        $result_array = $result -> fetch_all(MYSQLI_ASSOC);
                        foreach ($result_array as $item) {
                            $selected = $item['nazwa'] == $row['nazwa'] ? 'selected' : '';
                            echo '<option value="' . $item['idProduktu'] . '" ' . $selected . '>' . $item['nazwa'] . '</option>';
                        }
                        echo '</select></td>';

                        // Input fields for quantity and comments
                        echo '<td>' . $row['cena'] . '</td>';
                        echo '<td><input type="text" name="ilosc" value="' . $row['ilosc'] . '"></td>';
                        echo '<td><input type="text" name="komentarze" value="' . $row['komentarze'] . '"></td>';
                        echo '<td><input type="submit" name="update" value="Update"></td>';
                        echo '</form>';
                        echo '</tr>';
                    } else {
                        echo '<tr>';
                        foreach ($row as $key => $value) {
                            if ($key == 'idZamowienia') {
                                echo '<td><button type="submit" name="edit" value="' . $value . '">' . $value . '</button></td>';
                            } else {
                                echo '<td>' . $value . '</td>';
                            }
                        }
                        echo '<td><button type="submit" name="delete" value="' . $row['idZamowienia'] . '">&#128465;</button></td>';
                        echo '</tr>';
                    }
                }

                // Form for adding a new order
                echo '<tr>';
                echo '<form method="POST">';
                echo '<td>[auto]</td><td>[auto]</td>';
                echo '<td><select name="nazwisko">';
                $sql = "SELECT idUzytkownika, nazwisko FROM $users_table";
                $result = $db -> query($sql);
                $result_array = $result -> fetch_all(MYSQLI_ASSOC);
                foreach ($result_array as $row) {
                    echo '<option value="' . $row['idUzytkownika'] . '">' . $row['nazwisko'] . '</option>';
                }
                echo '</select></td>';
                echo '<td><select name="nazwa">';
                $sql = "SELECT idProduktu, nazwa FROM $items_table";
                $result = $db -> query($sql);
                $result_array = $result -> fetch_all(MYSQLI_ASSOC);
                foreach ($result_array as $row) {
                    echo '<option value="' . $row['idProduktu'] . '">' . $row['nazwa'] . '</option>';
                }
                echo '</select></td>';
                echo '<td>[auto]</td>';
                echo '<td><input type="text" name="ilosc" placeholder="ilosc"></td>';
                echo '<td><input type="text" name="komentarze" placeholder="komentarze"></td>';
                echo '<td><input type="submit" name="submit" value="submit"></td>';
                echo '</form>';
                echo '</tr>';
                echo '</table>';

                // Handling form submission for adding a new order
                if (isset($_POST['submit'])) {
                    $nazwisko = (int)$_POST['nazwisko'];
                    $nazwa = (int)$_POST['nazwa'];
                    $ilosc = (int)$_POST['ilosc'];
                    $komentarze = $_POST['komentarze'];
                    $sql = "INSERT INTO $orders_table (idUzytkownika, idProduktu, ilosc, komentarze) 
                            VALUES ($nazwisko, $nazwa, $ilosc, '$komentarze')";
                    $result = $db -> query($sql);
                    // if ($result) {
                    //     echo 'Dodano zamowienie';
                    // } else {
                    //     echo 'Blad dodawania zamowienia';
                    // }
                    $_POST = array();
                    echo '<script>window.location.href=window.location.href;</script>';
                }
            }
        ?>
        </form>
        <?php
            // Handling form submission for deleting an order
            if (isset($_POST['delete'])) {
                $idZamowienia = $_POST['delete'];
                $sql = "DELETE FROM $orders_table WHERE idZamowienia = $idZamowienia";
                $result = $db -> query($sql);
                // if ($result) {
                //     echo 'Usunieto zamowienie';
                // } else {
                //     echo 'Blad usuwania zamowienia';
                // }
                $_POST = array();
                echo '<script>window.location.href=window.location.href;</script>';
            }
        ?>
        <?php
            // Handling form submission for updating an order
            if (isset($_POST['update'])) {
                $idZamowienia = $_POST['idZamowienia'];
                $nazwisko = (int)$_POST['nazwisko'];
                $nazwa = (int)$_POST['nazwa'];
                $ilosc = (int)$_POST['ilosc'];
                $komentarze = $_POST['komentarze'];
                $sql = "UPDATE $orders_table 
                        SET idUzytkownika=$nazwisko, idProduktu=$nazwa, ilosc=$ilosc, komentarze='$komentarze' 
                        WHERE idZamowienia=$idZamowienia";
                $result = $db -> query($sql);
                // if ($result) {
                //     echo 'Zaktualizowano zamowienie';
                // } else {
                //     echo 'Blad aktualizacji zamowienia';
                // }
                echo '<script>window.location.href=window.location.href;</script>';
            }
        ?>
    </body>
</html>