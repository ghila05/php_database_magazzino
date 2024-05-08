<?php
session_start();
require "libreria.php"; // Funzioni eseguite dal server
require "credenziali.php"; // Credenziali di connessione al database
if (isset($_SESSION["UTENTE"])) {
    echo '<link rel="stylesheet" type="text/css" href="style_scaffale.css">';

    echo "Benvenuto nella visualizzazione delle categorie, " . $_SESSION["UTENTE"];

    echo "<footer>
        <button onclick='redirectToPage(\"protetta.php\")'>Visualizza gli Oggetti</button>
      </footer><br>";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = 'SELECT * FROM categorie';

        $statement = $conn->query($sql);

        if ($statement->rowCount() > 0) {
            echo "<table>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                    </tr>";

            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['nome']}</td>
                    </tr>";
            }

            echo "</table>";

            echo"<button onclick='redirectToPage(\"aggiungi_categoria.php\")'>Aggiungi categoria</button><br><br>";
            echo"<button onclick='redirectToPage(\"elimina_categoria.php\")'>Elimina categoria</button><br><br>";
            echo"<button onclick='redirectToPage(\"modifica_categoria.php\")'>Modifica categoria</button><br><br>";


        } else {
            echo "Nessun risultato trovato";
        }
    } catch (PDOException $e) {
        echo "Connessione fallita: " . $e->getMessage();
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
