<?php
session_start();
require "libreria.php";
require "credenziali.php";

$errors = [];

if (isset($_SESSION["UTENTE"])) {
    echo ' <link rel="stylesheet" type="text/css" href="style_aggiungi.css">';

    echo "<div class='container'>
            <h2 style='text-align: center;'>Benvenuto nelle spedizioni " . $_SESSION["UTENTE"] . "</h2>";

    echo "<footer>
            <button onclick='redirectToPage(\"spedizioni.php\")'>Visualizza le Spedizioni</button>
          </footer><br>";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if (empty($_POST['partenza'])) {
            $errors[] = "Il campo Partenza è richiesto";
        }
        if (empty($_POST['arrivo'])) {
            $errors[] = "Il campo Arrivo è richiesto";
        }
        if (empty($_POST['rif_ogg'])) {
            $errors[] = "Il campo Riferimento Oggetto è richiesto";
        }
        
        if (empty($errors)) {
            try {
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                if (isset($_POST['add_shipment'])) {
                    $partenza = $_POST['partenza'];
                    $arrivo = $_POST['arrivo'];
                    $rif_ogg = $_POST['rif_ogg'];

                    // Preparazione query
                    $stmt = $conn->prepare("INSERT INTO spedizioni (partenza, arrivo, rif_ogg) VALUES (?, ?, ?)");
                    $stmt->execute([$partenza, $arrivo, $rif_ogg]);
                }

                echo "<div style='text-align: center; color: green;'>Spedizione aggiunta con successo!</div>";

            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            } finally {
                $conn = null;
            }
        }
    }

  // eventuali errori
    if (!empty($errors)) {
        echo "<div class='error'>";
        foreach ($errors as $error) {
            echo "<p>$error</p>";
        }
        echo "</div>";
    }

    // HTML form per l'aggiunta di una spedizione
    echo "<div class='add-container'>
            <h3 style='text-align: center;'>Aggiungi Spedizione</h3>
            <form method='POST'>
                <label for='partenza'>Partenza:</label>
                <input type='text' name='partenza'><br>
                <label for='arrivo'>Arrivo:</label>
                <input type='text' name='arrivo'><br>
                <label for='rif_ogg'>Riferimento Oggetto:</label>
                <input type='text' name='rif_ogg'><br>
                <button type='submit' name='add_shipment'>Aggiungi spedizione</button>
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
