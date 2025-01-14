<!DOCTYPE html>
<html lang="PL">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Test</title>
    </head>
    <body>
        <!-- 
        Done for now:
        - display all orders with user and item details
        - delete order
        To do:
        - add order
        - edit order
        -->

        <h1>Test</h1>
        <form method="POST">
        <?php
            // db connection
            $login = 'root';
            $password = '';
            $db_name = 'su_php';
            $users_table = 'uzytkownicy';
            $items_table = 'produkty';
            $orders_table = 'zamowienia';
            $db = new mysqli('localhost', $login, $password, $db_name);
            if ($db->connect_error) {
                die('Connection failed: ' . $db->connect_error);
            }
            else {
                $sql = "SELECT idZamowienia,imie,nazwisko,nazwa,cena,ilosc,komentarze FROM $users_table INNER JOIN $orders_table ON $users_table.idUzytkownika = $orders_table.idUzytkownika INNER JOIN $items_table ON $orders_table.idProduktu = $items_table.idProduktu ORDER BY idZamowienia";
                $result = $db->query($sql);
                $result_array = $result->fetch_all(MYSQLI_ASSOC);
                echo '<table border=1>';
                echo '<tr>';
                foreach ($result_array[0] as $key => $value) {
                    echo '<th>' . $key . '</th>';
                }
                foreach ($result_array as $row) {
                    echo '<tr>';
                    foreach ($row as $key => $value) {
                        echo '<td>' . $value . '</td>';
                    }
                    echo '<td><button type="submit" name="delete" value="' . $row['idZamowienia'] . '">&#128465;</button></td>';
                    echo '</tr>';
                }
                echo '</table>';
            }
        ?>
        </form>
        <?php
            if (isset($_POST['delete'])) {
                $idZamowienia = $_POST['delete'];
                $sql = "DELETE FROM $orders_table WHERE idZamowienia = $idZamowienia";
                $result = $db->query($sql);
                if ($result) {
                    echo 'Usunieto zamowienie';
                }
                else {
                    echo 'Blad usuwania zamowienia';
                }
            }
        ?>

        <!-- <form method="POST">
            <select name="nazwisko">
                <?php
                    $sql = "SELECT nazwisko FROM $users_table";
                    $result = $db->query($sql);
                    $result_array = $result->fetch_all(MYSQLI_ASSOC);
                    foreach ($result_array as $row) {
                        echo '<option value="' . $row['nazwisko'] . '">' . $row['nazwisko'] . '</option>';
                    }
                ?>
            </select>
            <select name="nazwa">
                <?php
                    $sql = "SELECT nazwa FROM $items_table";
                    $result = $db->query($sql);
                    $result_array = $result->fetch_all(MYSQLI_ASSOC);
                    foreach ($result_array as $row) {
                        echo '<option value="' . $row['nazwa'] . '">' . $row['nazwa'] . '</option>';
                    }
                ?>
            <input type="text" name="ilosc" placeholder="ilosc">
            <input type="text" name="komentarze" placeholder="komentarze">
            <input type="submit" name="submit" value="submit">
        </form>
        <?php
            if (isset($_POST['submit'])) {
                $idUzytkownika = $_POST['nazwisko'];
                $idProduktu = $_POST['nazwa'];
                $ilosc = $_POST['ilosc'];
                $komentarze = $_POST['komentarze'];
                $sql = "INSERT INTO $orders_table (idUzytkownika, idProduktu, ilosc, komentarze) VALUES ('$idUzytkownika', '$idProduktu', '$ilosc', '$komentarze')";
                $result = $db->query($sql);
                if ($result) {
                    echo 'Dodano zamowienie';
                }
                else {
                    echo 'Blad dodawania zamowienia';
                }
            }
        ?> -->
    </body>
</html>