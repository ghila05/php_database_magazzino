<?php
session_start();
require "libreria.php";
require "credenziali.php";

$errors = [];

if (isset($_SESSION["UTENTE"])) {
    echo ' <link rel="stylesheet" type="text/css" href="style_aggiungi.css">';

    echo "<div class='container'>
            <h2 style='text-align: center;'>Benvenuto nella gestione degli scaffali, {$_SESSION["UTENTE"]}!</h2>";

    echo "<footer>
            <button onclick='redirectToPage(\"scaffale.php\")'>Visualizza gli Scaffali</button>
          </footer><br>";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Connessione al database
        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            if (isset($_POST['add_shelf'])) {
                // Prendi i dati dal modulo
                $nome_scaffale = $_POST['nome_scaffale'];
                $capacita = $_POST['capacita'];

                // Preparazione query per l'inserimento dei dati
                $stmt = $conn->prepare("INSERT INTO scaffali (nome_scaffale, capacita) VALUES (?, ?)");
                $stmt->execute([$nome_scaffale, $capacita]);

                echo "<div style='text-align: center; color: green;'>Scaffale aggiunto con successo!</div>";
            }

        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        } finally {
            $conn = null;
        }
    }

    // HTML form per l'aggiunta di uno scaffale
    echo "<div class='add-container'>
            <h3 style='text-align: center;'>Aggiungi Scaffale</h3>
            <form method='POST'>
                <label for='nome_scaffale'>Nome Scaffale:</label>
                <input type='text' name='nome_scaffale'><br>
                <label for='capacita'>Capacità:</label>
                <input type='text' name='capacita'><br>
                <button type='submit' name='add_shelf'>Aggiungi Scaffale</button>
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
