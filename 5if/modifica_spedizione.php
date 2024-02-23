<?php
session_start();
require "libreria.php";
require "credenziali.php";

$errors = [];

if (isset($_SESSION["UTENTE"])) {
    echo ' <link rel="stylesheet" type="text/css" href="style_aggiungi.css">';

    echo "<div class='container'>
            <h2 style='text-align: center;'>Benvenuto nelle spedizioni, {$_SESSION["UTENTE"]}!</h2>";

    echo "<footer>
            <button onclick='redirectToPage(\"protetta.php\")'>Visualizza le Spedizioni</button>
          </footer><br>";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Connessione al database
        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            if (isset($_POST['fill_shipment'])) {
                // Riempire i campi con i dati della spedizione selezionata
                $shipment_id = $_POST['shipment_id'];
                $stmt = $conn->prepare("SELECT * FROM spedizioni WHERE id = ?");
                echo $shipment_id;
                $stmt->execute([$shipment_id]);
                $shipment = $stmt->fetch(PDO::FETCH_ASSOC);
            }

            if (isset($_POST['update_shipment'])) {
                // Logica per aggiornare la spedizione
                // Prendi i dati dal modulo
                $shipment_id = $_POST['shipment_id'];
                $partenza = $_POST['partenza'];
                $arrivo = $_POST['arrivo'];
                $rif_ogg = $_POST['rif_ogg'];

                // Preparazione query per l'aggiornamento dei dati
                $stmt = $conn->prepare("UPDATE spedizioni SET partenza = ?, arrivo = ?, rif_ogg = ? WHERE id = ?");
                $stmt->execute([$partenza, $arrivo, $rif_ogg, $shipment_id]);
                
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
                <label>Seleziona ID della spedizione da modificare:</label>
                <select name='shipment_id'>
                    <option></option>";
                    // Opzioni per selezionare la spedizione
                    try {
                        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        $query_shipments = 'SELECT id FROM spedizioni ORDER BY id ASC;';
                        $stmt = $conn->prepare($query_shipments);
                        $stmt->execute();
                        $shipments = $stmt->fetchAll();

                        foreach ($shipments as $shipment) {
                            echo "<option value='{$shipment['id']}'>{$shipment['id']}</option>";
                        }
                        
                    } catch (PDOException $e) {
                        echo "Connection failed: " . $e->getMessage();
                    }

    echo "      </select><br><br>
                <button type='submit' name='fill_shipment'>Riempi</button>
            </form>
        </div>";
    
    
    echo "<div class='add-container'>
            <h3 style='text-align: center;'>Modifica Spedizione</h3>
            <form method='POST'>";

    if (!empty($shipment['partenza'])) {
        echo "
            <input type='hidden' name='shipment_id' value='{$shipment['id']}'>
            <label for='partenza'>Partenza:</label>
            <input type='text' name='partenza' value='{$shipment['partenza']}'><br>
            <label for='arrivo'>Arrivo:</label>
            <input type='text' name='arrivo' value='{$shipment['arrivo']}'><br>
            <label for='rif_ogg'>Riferimento Oggetto:</label>
            <input type='text' name='rif_ogg' value='{$shipment['rif_ogg']}'><br>
        ";
    } else {
        echo "
            <label for='partenza'>Partenza:</label>
            <input type='text' name='partenza'><br>
            <label for='arrivo'>Arrivo:</label>
            <input type='text' name='arrivo'><br>
            <label for='rif_ogg'>Riferimento Oggetto:</label>
            <input type='text' name='rif_ogg'><br>
        ";
    }

    echo "<button type='submit' name='update_shipment'>Aggiorna Spedizione</button>
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
