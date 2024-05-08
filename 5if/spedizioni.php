<?php
session_start();
require "libreria.php"; // Funzioni eseguite dal server
require "credenziali.php"; // Credenziali di connessione al database
if (isset($_SESSION["UTENTE"])) {
    echo '<link rel="stylesheet" type="text/css" href="style_scaffale.css">';

    echo "Benvenuto nelle spedizioni " . $_SESSION["UTENTE"];

    echo "<footer>
        <button onclick='redirectToPage(\"protetta.php\")'>Visualizza gli Oggetti</button>

      </footer><br>";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

       
        if (isset($_POST['ricaricaPagina'])) {
           
            $_SESSION['ricaricaPagina'] = true;
         
            echo "<script>window.location.reload();</script>";
        }

      // Query base che stampa tutto senza ordine
        $sql = 'SELECT * FROM spedizioni';

        // Verifica se l'utente ha selezionato un filtro
        if (isset($_POST['my_html_select_box'])) {
            $filtro = $_POST['my_html_select_box'];

            // Modifica la query
            if ($filtro === 'Id: Crescente') {
                $sql .= ' ORDER BY id ASC';
            } elseif ($filtro === 'Id: Decrescente') {
                $sql .= ' ORDER BY id DESC';
            }
        }

        $statement = $conn->query($sql);

        if ($statement->rowCount() > 0) {
     
            echo "<table>
                    <tr>
                        <th>ID</th>
                        <th>Partenza</th>
                        <th>Arrivo</th>
                        <th>Riferimento Oggetto</th>
                    </tr>";

            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['partenza']}</td>
                        <td>{$row['arrivo']}</td>
                        <td>{$row['rif_ogg']}</td>
                    </tr> ";
            }

            echo "</table>";

            echo "<form method='POST'>
                    <label for='my_html_select_box'>FILTRA PER:</label>    
                    <select name='my_html_select_box'>
                        <option>Id: Crescente</option>
                        <option>Id: Decrescente</option>
                    </select>
                    <button type='submit'>Filtra</button>
                </form>";
                echo"<button onclick='redirectToPage(\"aggiungi_spedizione.php\")'>Aggiungi spedizione</button><br><br>"; 
                echo"<button onclick='redirectToPage(\"elimina_spedizione.php\")'>Elimina spedizione</button><br><br>"; 
                echo"<button onclick='redirectToPage(\"modifica_spedizione.php\")'>Modifica spedizione</button><br><br>"; 
        } else {
           
            echo "Nessun risultato trovato";
        }
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    } finally {

        $conn = null;
    }

    echo "</body>
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
