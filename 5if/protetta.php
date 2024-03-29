<?php
session_start();
require "libreria.php"; // per funzioni che verranno eseguite dal server e che possono servire 
require "credenziali.php"; //per tenere le credenziali di connessione al database

if (isset($_SESSION["UTENTE"])) {

    // css per presentazione più belina
    echo '<link rel="stylesheet" type="text/css" href="style_protetta.css">';

       

    echo "Benvenuto negli oggetti " . $_SESSION["UTENTE"];

    echo "<footer>
        <button onclick='redirectToPage(\"scaffale.php\")'>Visualizza gli Scaffali</button>
        <button onclick='redirectToPage(\"spedizioni.php\")'>Visualizza le spedizioni</button>
        <button onclick='redirectToPage(\"categorie.php\")'>Visualizza le categorie</button>
      </footer><br>";

    //connessione per la stampa della tabella principale se la pagina non è ricaricata
    try {
        //$user = "nicola";
        //$passwd = "1234";
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Controlla se la pagina è stata ricaricata
        if (isset($_POST['ricaricaPagina'])) {
            
            $_SESSION['ricaricaPagina'] = true;
            // Ricarica la pagina
            echo "<script>window.location.reload();</script>";
        }

        // Query che stampa tutti gli oggetti (query principale)
        $sql = 'SELECT * FROM oggetti';



        // Verifica se l'utente ha selezionato un filtro per il prezzo
        if (isset($_POST['my_html_select_box']) && isset($_POST['select_fornitori'])) {
            $filtroPrezzo = $_POST['my_html_select_box'];
            $filtroFornitori = $_POST['select_fornitori'];
        
            // Aggiungi la condizione WHERE per il filtro dei fornitori
            $sql .= " WHERE Fornitori LIKE '%" . $filtroFornitori . "%'";
        
            // Modifica la query in base alla selezione dell'utente
            if ($filtroPrezzo === 'Prezzo: Crescente') {
                $sql .= ' ORDER BY prezzo ASC';
            } elseif ($filtroPrezzo === 'Prezzo: Decrescente') {
                $sql .= ' ORDER BY prezzo DESC';
            }
        }

        $statement = $conn->query($sql);

        if ($statement->rowCount() > 0) {
            // Tabella HTML
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

            // Form per il filtro
            
            echo"<form method='POST'>";
            //echo "<form method='POST'>
             echo"<label for='my_html_select_box'>FILTRA PER PREZZO: </label>    
                    <select name='my_html_select_box'>
                        <option>Prezzo: Crescente</option>
                        <option>Prezzo: Decrescente</option>
                    </select>
                    <button type='submit'>Filtra</button><br><br>";


            try{  // riempie la select dei venditori 

                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                


                $queryvenditori = 'SELECT DISTINCT Fornitori FROM oggetti';
                $statementforn = $conn->prepare($queryvenditori);
                $statementforn->execute();
                $fornitori = $statementforn->fetchAll();

                echo'<label>FILTRA PER FORNITORE: </label>';
                echo '<select name="select_fornitori">';
                echo' <option> </option>';
                foreach ($fornitori as $fornitore) {
                    echo'  <option>' . $fornitore['Fornitori'] . '</option>';
                }
                echo '</select>';
                
                echo"</form>"; //fine form filtri fornitori e prezzi
                
            } catch (PDOException $e) {echo "Connection failed: " . $e->getMessage();}


            echo"<button onclick='redirectToPage(\"aggiungi_oggetto.php\")'>Aggiungi oggetto</button><br><br>";
            echo"<button onclick='redirectToPage(\"elimina_oggetto.php\")'>Elimina oggetto</button><br><br>";
            echo"<button onclick='redirectToPage(\"modifica_oggetto.php\")'>Modifica oggetto</button>";
            


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
