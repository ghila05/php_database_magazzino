<?php
session_start();
require "libreria.php"; // Funzioni eseguite dal server
require "credenziali.php"; // Credenziali di connessione al database

if (isset($_SESSION["UTENTE"])) {
    echo '<link rel="stylesheet" type="text/css" href="style_scaffale.css">';

    echo "Benvenuto negli scaffali " . $_SESSION["UTENTE"];

    echo "<footer>
        <button onclick='redirectToPage(\"protetta.php\")'>Visualizza gli Oggetti</button>

      </footer><br>";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Controlla se la pagina è stata ricaricata
        if (isset($_POST['ricaricaPagina'])) {
            // Imposta la variabile di sessione
            $_SESSION['ricaricaPagina'] = true;
            // Ricarica la pagina
            echo "<script>window.location.reload();</script>";
        }

        // Query che stampa tutti gli scaffali o ordina per ID
        $sql = 'SELECT * FROM scaffali';

        // Verifica se l'utente ha selezionato un filtro
        if (isset($_POST['my_html_select_box'])) {
            $filtro = $_POST['my_html_select_box'];

            // Modifica la query in base alla selezione dell'utente
            if ($filtro === 'Id: Crescente') {
                $sql .= ' ORDER BY id ASC';
            } elseif ($filtro === 'Id: Decrescente') {
                $sql .= ' ORDER BY id DESC';
            }
        }

        $statement = $conn->query($sql);

        if ($statement->rowCount() > 0) {
            // Tabella HTML
            echo "<table>
                    <tr>
                        <th>Categoria</th>
                        <th>ID</th>
                    </tr>";

            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>{$row['categoria']}</td>
                        <td>{$row['id']}</td>
                    </tr> ";
            }

            echo "</table>";

            // Form per il filtro
            echo "<form method='POST'>
                    <label for='my_html_select_box'>FILTRA PER:</label>    
                    <select name='my_html_select_box'>
                        <option>Id: Crescente</option>
                        <option>Id: Decrescente</option>
                    </select>
                    <button type='submit'>Filtra</button>
                </form>";


                echo"<button onclick='redirectToPage(\"aggiungi_scaffale.php\")'>Aggiungi scaffale</button><br><br>";
                echo"<button onclick='redirectToPage(\"elimina_scaffale.php\")'>Elimina scaffale</button><br><br>";
                echo"<button onclick='redirectToPage(\"modifica_scaffale.php\")'>Modifica scaffale</button><br><br>";
        } else {
            // Messaggio se la query non ha prodotto risultati
            echo "Nessun risultato trovato";
        }
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    } finally {
        // Chiudi la connessione in ogni caso
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
