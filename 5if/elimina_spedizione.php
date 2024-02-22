<?php
session_start();
require "libreria.php";
require "credenziali.php";

$errors = [];

if (isset($_SESSION["UTENTE"])) {
    echo '<link rel="stylesheet" type="text/css" href="style_elimina.css">';

    echo "<div class='container'>
            <h2 style='text-align: center;'>Benvenuto nell'eliminazione delle spedizioni " . $_SESSION["UTENTE"] . "</h2>";

    echo "<footer>
            <button onclick='redirectToPage(\"spedizioni.php\")'>Visualizza le Spedizioni</button>
          </footer><br>";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Se è stato premuto il bottone "Elimina"
        if (isset($_POST['delete_shipment'])) {
            try {
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $id_elimina = $_POST['spedizione_eliminare'];

                // Prepara e esegui la query per eliminare la spedizione selezionata
                $stmt = $conn->prepare("DELETE FROM spedizioni WHERE id = ?");
                $stmt->execute([$id_elimina]);

                echo "<div style='text-align: center; color: green;'>Spedizione eliminata con successo!</div>"; 

            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            } finally {
                $conn = null;
            }
        }
    }

    // HTML form per selezionare la spedizione da eliminare
    echo "<div class='add-container'>
            <form method='POST'>
                <label>Seleziona ID della spedizione da eliminare:</label>
                <select name='spedizione_eliminare'>
                    <option></option>";

                    try {
                        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        $query_spedizioni = 'SELECT id FROM spedizioni ORDER BY id ASC;';
                        $statement_spedizioni = $conn->prepare($query_spedizioni);
                        $statement_spedizioni->execute();
                        $id_spedizioni = $statement_spedizioni->fetchAll();

                        foreach ($id_spedizioni as $spedizione) {
                            echo "<option value='{$spedizione['id']}'>{$spedizione['id']}</option>";
                        }
                        
                    } catch (PDOException $e) {
                        echo "Connection failed: " . $e->getMessage();
                    }

    echo "      </select><br><br>
                <button type='submit' name='delete_shipment'>Elimina</button>
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
