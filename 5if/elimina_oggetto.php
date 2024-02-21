<?php
session_start();
require "libreria.php";
require "credenziali.php";

$errors = [];

if (isset($_SESSION["UTENTE"])) {
    echo '<link rel="stylesheet" type="text/css" href="style_elimina.css">';

    echo "<div class='container'>
            <h2 style='text-align: center;'>Benvenuto nell'eliminazione " . $_SESSION["UTENTE"] . "</h2>";

    echo "<footer>
            <button onclick='redirectToPage(\"protetta.php\")'>Visualizza gli Oggetti</button>
          </footer><br>";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Se è stato premuto il bottone "Elimina"
        if (isset($_POST['delete_object'])) {
            try {
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $id_elimina = $_POST['oggetto_eliminare'];

                // Prepara e esegui la query per eliminare l'oggetto selezionato
                $stmt = $conn->prepare("DELETE FROM oggetti WHERE id = ?");
                $stmt->execute([$id_elimina]);

                echo "<div style='text-align: center; color: green;'>Oggetto eliminato con successo!</div>"; 

            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            } finally {
                $conn = null;
            }
        }
    }

    // HTML form per selezionare l'oggetto da eliminare
    echo "<div class='add-container'>
 
            <form method='POST'>
                <label>Seleziona ID dell'oggetto da eliminare:</label>
                <select name='oggetto_eliminare'>
                    <option></option>";

                    try {
                        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        $queryvenditori = 'SELECT id FROM oggetti ORDER BY id ASC;';
                        $statementforn = $conn->prepare($queryvenditori);
                        $statementforn->execute();
                        $id_elimina = $statementforn->fetchAll();

                        foreach ($id_elimina as $elimina) {
                            echo "<option value='{$elimina['id']}'>{$elimina['id']}</option>";
                        }
                        
                    } catch (PDOException $e) {
                        echo "Connection failed: " . $e->getMessage();
                    }

    echo "      </select><br><br>
                <button type='submit' name='delete_object'>Elimina</button>
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
