<?php
session_start();
require "libreria.php";
require "credenziali.php";



if (isset($_SESSION["UTENTE"])) {
    echo '<link rel="stylesheet" type="text/css" href="style_elimina.css">';

    echo "<div class='container'>
            <h2 style='text-align: center;'>Benvenuto nell'eliminazione dello scaffale, {$_SESSION["UTENTE"]}!</h2>";

    echo "<footer>
            <button onclick='redirectToPage(\"scaffale.php\")'>Visualizza gli Scaffali</button>
          </footer><br>";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            if (isset($_POST['delete_shelf'])) {

                $id_scaffale = $_POST['id_scaffale'];

                // Preparazione e esecuzione della query per eliminare lo scaffale
                $stmt = $conn->prepare("DELETE FROM scaffali WHERE id = ?");
                $stmt->execute([$id_scaffale]);

                echo "<div style='text-align: center; color: green;'>Scaffale eliminato con successo!</div>";
            }

        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        } finally {
            $conn = null;
        }
    }

    // HTML form per selezionare lo scaffale da eliminare
    echo "<div class='add-container'>
            <h3 style='text-align: center;'>Elimina Scaffale</h3>
            <form method='POST'>
                <label for='id_scaffale'>Seleziona lo scaffale da eliminare:</label>
                <select name='id_scaffale'>
                    <option></option>";
                    
                    try {
                        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        $query_scaffali = 'SELECT id FROM scaffali ORDER BY id ASC;';
                        $stmt = $conn->prepare($query_scaffali);
                        $stmt->execute();
                        $scaffali = $stmt->fetchAll();
                     
                        foreach ($scaffali as $scaffale) {
                            echo "<option value='{$scaffale['id']}'>{$scaffale['id']}</option>";
                        }
                        
                    } catch (PDOException $e) {
                        echo "Connection failed: " . $e->getMessage();
                    }

    echo "      </select><br><br>
                <button type='submit' name='delete_shelf'>Elimina Scaffale</button>
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
