<?php
session_start();
require "libreria.php";
require "credenziali.php";

$errors = [];

if (isset($_SESSION["UTENTE"])) {
    echo "<html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 0;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    background-color: #f8f9fa;
                }

                .container {
                    width: 400px;
                    padding: 20px;
                    background-color: #ffffff;
                    border-radius: 8px;
                    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 20px;
                }

                th, td {
                    border: 1px solid #dee2e6;
                    text-align: left;
                    padding: 8px;
                }

                th {
                    background-color: #343a40;
                    color: #ffffff;
                }

                form {
                    margin-bottom: 20px;
                }

                footer {
                    margin-top: 20px;
                    text-align: center;
                }

                button {
                    padding: 10px 20px;
                    cursor: pointer;
                    background-color: #28a745;
                    color: #ffffff;
                    border: none;
                    border-radius: 4px;
                }

                .add-container {
                    margin-bottom: 20px;
                }
                
                .add-container label {
                    font-weight: bold;
                    margin-bottom: 5px;
                    display: block;
                }
                
                .add-container input[type='text'] {
                    padding: 8px;
                    margin-bottom: 10px;
                    width: 100%;
                    box-sizing: border-box;
                }

                .add-container button[type='submit'] {
                    width: 100%;
                }

                .error {
                    color: #dc3545;
                    margin-top: 5px;
                }
            </style>
        </head>
        <body>";

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
