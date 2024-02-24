<?php
session_start();
require "libreria.php";
require "credenziali.php";

$errors = [];

if (isset($_SESSION["UTENTE"])) {
    echo '<link rel="stylesheet" type="text/css" href="style_elimina.css">';

    echo "<div class='container'>
            <h2 style='text-align: center;'>Benvenuto nella pagina di eliminazione categoria, " . $_SESSION["UTENTE"] . "</h2>";

    echo "<footer>
            <button onclick='redirectToPage(\"categorie.php\")'>Visualizza le categorie</button>
          </footer><br>";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Se è stato premuto il bottone "Elimina"
        if (isset($_POST['delete_category'])) {
            try {
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $id_elimina = $_POST['categoria_eliminare'];

                // Prepara e esegui la query per eliminare la categoria selezionata
                $stmt = $conn->prepare("DELETE FROM categorie WHERE id = ?");
                $stmt->execute([$id_elimina]);

                echo "<div style='text-align: center; color: green;'>Categoria eliminata con successo!</div>"; 

            } catch (PDOException $e) {
                echo "Connessione fallita: " . $e->getMessage();
            } finally {
                $conn = null;
            }
        }
    }

    // HTML form per selezionare la categoria da eliminare
    echo "<div class='add-container'>
 
            <form method='POST'>
                <label>Seleziona la categoria da eliminare:</label>
                <select name='categoria_eliminare'>
                    <option></option>";

                    try {
                        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        $querycategorie = 'SELECT id, nome FROM categorie ORDER BY id ASC;';
                        $statementcat = $conn->prepare($querycategorie);
                        $statementcat->execute();
                        $categorie_elimina = $statementcat->fetchAll();

                        foreach ($categorie_elimina as $categoria) {
                            echo "<option value='{$categoria['id']}'>{$categoria['nome']}</option>";
                        }
                        
                    } catch (PDOException $e) {
                        echo "Connessione fallita: " . $e->getMessage();
                    }

    echo "      </select><br><br>
                <button type='submit' name='delete_category'>Elimina</button>
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
