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
