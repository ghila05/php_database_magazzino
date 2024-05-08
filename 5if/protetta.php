<?php
session_start();

// Inclusione delle librerie necessarie
require "libreria.php"; // Funzioni eseguite dal server
require "credenziali.php"; // Credenziali di connessione al database

// Controllo dell'autenticazione dell'utente
if (isset($_SESSION["UTENTE"])) {

    // Inclusione del foglio di stile per la presentazione
    echo '<!DOCTYPE html>
            <html lang="it">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Benvenuto</title>
                <link rel="stylesheet" type="text/css" href="style_scaffale.css">
            </head>
            <body>';

    // Messaggio di benvenuto
    echo "<h1>Benvenuto su questa piattaforma, {$_SESSION["UTENTE"]}!</h1>";

    // Pulsanti di navigazione
    echo "
            <button onclick='redirectToPage(\"scaffale.php\")'>Visualizza gli Scaffali</button>
            <button onclick='redirectToPage(\"spedizioni.php\")'>Visualizza le Spedizioni</button>
            <button onclick='redirectToPage(\"categorie.php\")'>Visualizza le Categorie</button>
          <br><br>";

    try {
        // Connessione al database
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Controllo se la pagina è stata ricaricata
        if (isset($_POST['ricaricaPagina'])) {
            $_SESSION['ricaricaPagina'] = true;
            // Ricaricamento della pagina
            echo "<script>window.location.reload();</script>";
        }

        // Costruzione della query principale per la selezione degli oggetti
        $sql = 'SELECT * FROM oggetti';

        // Controllo dei filtri applicati dall'utente
        if (isset($_POST['my_html_select_box']) && isset($_POST['select_fornitori'])) {
            $filtroPrezzo = $_POST['my_html_select_box'];
            $filtroFornitori = $_POST['select_fornitori'];
        
            // Aggiunta della clausola WHERE per il filtro dei fornitori
            $sql .= " WHERE Fornitori LIKE '%" . $filtroFornitori . "%'";
        
            // Modifica della query in base alla selezione dell'utente
            if ($filtroPrezzo === 'Prezzo: Crescente') {
                $sql .= ' ORDER BY prezzo ASC';
            } elseif ($filtroPrezzo === 'Prezzo: Decrescente') {
                $sql .= ' ORDER BY prezzo DESC';
            }
        }

        $statement = $conn->query($sql);

        if ($statement->rowCount() > 0) {
            // Tabella HTML per la visualizzazione degli oggetti
            echo "<table>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Altezza</th>
                        <th>Larghezza</th>
                        <th>Peso</th>
                        <th>Rif. Scaffale</th>
                        <th>Fornitore</th>
                        <th>Prezzo</th>
                    </tr>";

            // Iterazione sui risultati della query
            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['nome']}</td>
                        <td>{$row['altezza']}</td>
                        <td>{$row['larghezza']}</td>
                        <td>{$row['peso']}</td>
                        <td>{$row['rif_scaffale']}</td>
                        <td>{$row['Fornitori']}</td>
                        <td>{$row['Prezzo']}</td>
                    </tr> ";
            }

            echo "</table>";

            // Form per l'applicazione dei filtri
            echo "<form method='POST'>
                    <label for='my_html_select_box'>Filtra per prezzo:</label>    
                    <select name='my_html_select_box'>
                        <option>Prezzo: Crescente</option>
                        <option>Prezzo: Decrescente</option>
                    </select>
                    <label>Filtra per fornitore:</label>
                    <select name='select_fornitori'>
                        <option></option>";
            // Riempimento della select con i fornitori
            $queryvenditori = 'SELECT DISTINCT Fornitori FROM oggetti';
            $statementforn = $conn->prepare($queryvenditori);
            $statementforn->execute();
            $fornitori = $statementforn->fetchAll();
            foreach ($fornitori as $fornitore) {
                echo "<option>{$fornitore['Fornitori']}</option>";
            }
            echo "</select>
                    <button type='submit'>Filtra</button>
                  </form>";

            // Pulsanti per aggiungere, eliminare e modificare oggetti
            echo "<button onclick='redirectToPage(\"aggiungi_oggetto.php\")'>Aggiungi oggetto</button><br><br>";
            echo "<button onclick='redirectToPage(\"elimina_oggetto.php\")'>Elimina oggetto</button><br><br>";
            echo "<button onclick='redirectToPage(\"modifica_oggetto.php\")'>Modifica oggetto</button>";

        } else {
            // Messaggio se la query non produce risultati
            echo "Nessun risultato trovato";
        }
    } catch (PDOException $e) {
        echo "Errore di connessione al database: " . $e->getMessage();
    } finally {
        // Chiusura della connessione
        $conn = null;
    }

    echo "</body>
        </html>";
} else {
    // Messaggio se l'accesso non è consentito
    echo "Accesso non consentito";
}
?>

<script>
    // Funzione per il reindirizzamento alle pagine
    function redirectToPage(page) {
        window.location.href = page;
    }
</script>
