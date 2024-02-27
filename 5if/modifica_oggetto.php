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

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



            if (isset($_POST['fill_object'])) {
                // riempire i campi con i dati dell'oggetto selezionato
                $oggetto_id = $_POST['oggetto_eliminare'];
                $stmt = $conn->prepare("SELECT * FROM oggetti WHERE id = ?");
                $stmt->execute([$oggetto_id]);
                $oggetto = $stmt->fetch(PDO::FETCH_ASSOC);
            }

            if (isset($_POST['add_object'])) {
                // Logica per aggiungere o modificare l'oggetto
                // Prendi i dati dal modulo
                $oggetto_id = $_POST['oggetto_id'];
                $nome = $_POST['nome'];
                $altezza = $_POST['altezza'];
                $larghezza = $_POST['larghezza'];
                $peso = $_POST['peso'];
                $rif_scaffale = $_POST['rif_scaffale'];
                $fornitore = $_POST['fornitore'];
                $prezzo = $_POST['prezzo'];

                $stmt = $conn->prepare("UPDATE oggetti SET nome = ?, altezza = ?, larghezza = ?, peso = ?, rif_scaffale = ?, Fornitori = ?, Prezzo = ? WHERE id = ?");
                $stmt->execute([$nome, $altezza, $larghezza, $peso, $rif_scaffale, $fornitore, $prezzo, $oggetto_id]);
                
            }

            echo "<div style='text-align: center; color: green;'>Operazione completata con successo!</div>";

        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        } finally {
            $conn = null;
        }
    }


    echo "<div class='add-container'>
            <form method='POST'>
                <label>Seleziona ID dell'oggetto da modificare:</label>
                <select name='oggetto_eliminare'>
                    <option></option>";
                    // Opzioni per selezionare l'oggetto
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
                <button type='submit' name='fill_object'>Riempi</button>
            </form>
        </div>";
    
    
    echo "<div class='add-container'>
            <h3 style='text-align: center;'>Aggiungi o Modifica Oggetto</h3>
            <form method='POST'>";

     
                if (!empty($oggetto)) {
                    echo "
                        <input type='hidden' name='oggetto_id' value='{$oggetto['id']}'>
                        <label for='nome'>Nome:</label>
                        <input type='text' name='nome' value='{$oggetto['nome']}'><br>
                        <label for='altezza'>Altezza:</label>
                        <input type='text' name='altezza' value='{$oggetto['altezza']}'><br>
                        <label for='larghezza'>Larghezza:</label>
                        <input type='text' name='larghezza' value='{$oggetto['larghezza']}'><br>
                        <label for='peso'>Peso:</label>
                        <input type='text' name='peso' value='{$oggetto['peso']}'><br>
                        <label for='rif_scaffale'>Riferimento Scaffale:</label>
                        <input type='text' name='rif_scaffale' value='{$oggetto['rif_scaffale']}'><br>
                        <label for='fornitore'>Fornitore:</label>
                        <input type='text' name='fornitore' value='{$oggetto['Fornitori']}'><br>
                        <label for='prezzo'>Prezzo:</label>
                        <input type='text' name='prezzo' value='{$oggetto['Prezzo']}'><br>
                    ";
                } else {
             
                    echo "
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
                    ";
                }

                echo "<button type='submit' name='add_object'>Modifica oggetto</button>
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
