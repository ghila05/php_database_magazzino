<?php
session_start();
require "libreria.php";
require "credenziali.php";

$errors = [];

if (isset($_SESSION["UTENTE"])) {
    echo ' <link rel="stylesheet" type="text/css" href="style_aggiungi.css">';

    echo "<div class='container'>
            <h2 style='text-align: center;'>Benvenuto nella pagina di aggiunta categorie, " . $_SESSION["UTENTE"] . "</h2>";

    echo "<footer>
            <button onclick='redirectToPage(\"categorie.php\")'>Visualizza le categorie</button>
          </footer><br>";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validazione dei dati
        if (empty($_POST['nome_categoria'])) {
            $errors[] = "Il campo Nome Categoria è richiesto";
        }

        if (empty($errors)) {
            try {
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                if (isset($_POST['add_category'])) {
                    $nome_categoria = $_POST['nome_categoria'];

                    // Preparazione query
                    $stmt = $conn->prepare("INSERT INTO categorie (nome) VALUES (?)");
                    $stmt->execute([$nome_categoria]);
                }

                echo "<div style='text-align: center; color: green;'>Categoria aggiunta con successo!</div>";

            } catch (PDOException $e) {
                echo "Connessione fallita: " . $e->getMessage();
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

    // HTML form per l'aggiunta di una categoria
    echo "<div class='add-container'>
            <h3 style='text-align: center;'>Aggiungi Categoria</h3>
            <form method='POST'>
                <label for='nome_categoria'>Nome Categoria:</label>
                <input type='text' name='nome_categoria'><br>
                <button type='submit' name='add_category'>Aggiungi categoria</button>
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
