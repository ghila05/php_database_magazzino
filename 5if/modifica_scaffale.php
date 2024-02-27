<?php
session_start();
require "libreria.php";
require "credenziali.php";

$errors = [];

if (isset($_SESSION["UTENTE"])) {
    echo ' <link rel="stylesheet" type="text/css" href="style_aggiungi.css">';

    echo "<div class='container'>
            <h2 style='text-align: center;'>Benvenuto nella modifica degli scaffali, {$_SESSION["UTENTE"]}!</h2>";

    echo "<footer>
            <button onclick='redirectToPage(\"scaffale.php\")'>Visualizza gli Scaffali</button>
          </footer><br>";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            if (isset($_POST['fill_shelf'])) {
                // Riempire i campi con i dati dello scaffale selezionato
                $shelf_id = $_POST['shelf_id'];
                $stmt = $conn->prepare("SELECT * FROM scaffali WHERE id = ?");
                $stmt->execute([$shelf_id]);
                $shelf = $stmt->fetch(PDO::FETCH_ASSOC);

                // Genera la sezione HTML per la modifica dello scaffale
                echo "<div class='add-container'>
                        <h3 style='text-align: center;'>Modifica Scaffale</h3>
                        <form method='POST'>
                            <input type='hidden' name='shelf_id' value='{$shelf['id']}'>
                            <label for='categoria'>Categoria:</label>
                            <input type='text' name='categoria' value='{$shelf['categoria']}'><br>
                            <button type='submit' name='update_shelf'>Aggiorna Scaffale</button>
                        </form>
                    </div>";
            }

            if (isset($_POST['update_shelf'])) {
                // Logica per aggiornare lo scaffale
                // Prendi i dati dal modulo
                $shelf_id = $_POST['shelf_id'];
                $categoria = $_POST['categoria'];

                // Preparazione query per l'aggiornamento dei dati
                $stmt = $conn->prepare("UPDATE scaffali SET categoria = ? WHERE id = ?");
                $stmt->execute([$categoria, $shelf_id]);

                echo "<div style='text-align: center; color: green;'>Operazione completata con successo!</div>";
            }


        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        } finally {
            $conn = null;
        }
    }

    // HTML form per selezionare lo scaffale da modificare
    echo "<div class='add-container'>
            <form method='POST'>
                <label>Seleziona ID dello scaffale da modificare:</label>
                <select name='shelf_id'>
                    <option></option>";
                    // Opzioni per selezionare lo scaffale
                    try {
                        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        $query_shelves = 'SELECT id FROM scaffali ORDER BY id ASC;';
                        $stmt = $conn->prepare($query_shelves);
                        $stmt->execute();
                        $shelves = $stmt->fetchAll();

                        foreach ($shelves as $shelf) {
                            echo "<option value='{$shelf['id']}'>{$shelf['id']}</option>";
                        }
                        
                    } catch (PDOException $e) {
                        echo "Connection failed: " . $e->getMessage();
                    }

    echo "      </select><br><br>
                <button type='submit' name='fill_shelf'>Riempi</button>
            </form>
        </div>";

    echo "</div></body>
        </html>";
} else {
    echo "Accesso non consentito";
}
?>

<script>
    function redirectToPage(page) {
        window.location.href = page;
    }
</script>
