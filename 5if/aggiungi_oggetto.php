<?php
session_start();
require "libreria.php";
require "credenziali.php";

$errors = [];

if (isset($_SESSION["UTENTE"])) {
    echo ' <link rel="stylesheet" type="text/css" href="style_aggiungi.css">';

    echo "<div class='container'>
            <h2 style='text-align: center;'>Benvenuto negli oggetti " . $_SESSION["UTENTE"] . "</h2>";

    echo "<footer>
            <button onclick='redirectToPage(\"protetta.php\")'>Visualizza gli Oggetti</button>
          </footer><br>";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validazione dei dati
        if (empty($_POST['nome'])) {
            $errors[] = "Il campo Nome è richiesto";
        }
        if (!is_numeric($_POST['altezza'])) {
            $errors[] = "Il campo Altezza deve essere un numero";
        }
        if (!is_numeric($_POST['larghezza'])) {
            $errors[] = "Il campo Larghezza deve essere un numero";
        }
        if (!is_numeric($_POST['peso'])) {
            $errors[] = "Il campo Peso deve essere un numero";
        }
        if (empty($_POST['rif_scaffale'])) {
            $errors[] = "Il campo Riferimento Scaffale è richiesto";
        }
        if (!is_numeric($_POST['prezzo'])) {
            $errors[] = "Il campo Prezzo deve essere un numero";
        }

        if (empty($errors)) {
            try {
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                if (isset($_POST['add_object'])) {
                    $nome = $_POST['nome'];
                    $altezza = $_POST['altezza'];
                    $larghezza = $_POST['larghezza'];
                    $peso = $_POST['peso'];
                    $rif_scaffale = $_POST['rif_scaffale'];
                    $fornitore = $_POST['fornitore'];
                    $prezzo = $_POST['prezzo'];

                    // Preparazione query
                    $stmt = $conn->prepare("INSERT INTO oggetti (nome, altezza, larghezza, peso, rif_scaffale, Fornitori, Prezzo) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$nome, $altezza, $larghezza, $peso, $rif_scaffale, $fornitore, $prezzo]);
                }

                echo "<div style='text-align: center; color: green;'>Oggetto aggiunto con successo!</div>";

            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            } finally {
                $conn = null;
            }
        }
    }

    // Mostra gli errori
    if (!empty($errors)) {
        echo "<div class='error'>";
        foreach ($errors as $error) {
            echo "<p>$error</p>";
        }
        echo "</div>";
    }

    // HTML form per l'aggiunta di un oggetto
    echo "<div class='add-container'>
            <h3 style='text-align: center;'>Aggiungi Oggetto</h3>
            <form method='POST'>
                <label for='nome'>Nome:</label>
                <input type='text' name='nome'><br>
                <label for='altezza'>Altezza:</label>
                <input type='text' name='altezza'><br>
                <label for='larghezza'>Larghezza:</label>
                <input type='text' name='larghezza'><br>
                <label for='peso'>Peso:</label>
                <input type='text' name='peso'><br>
                <label for='rif_scaffale'>Riferimento Scaffale:</label>
                <input type='text' name='rif_scaffale'><br>
                <label for='fornitore'>Fornitore:</label>
                <input type='text' name='fornitore'><br>
                <label for='prezzo'>Prezzo:</label>
                <input type='text' name='prezzo'><br>
                <button type='submit' name='add_object'>Aggiungi oggetto</button>
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
