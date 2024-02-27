<?php
session_start();
require "libreria.php";
require "credenziali.php";

$errors = [];

if (isset($_SESSION["UTENTE"])) {
    echo ' <link rel="stylesheet" type="text/css" href="style_aggiungi.css">';

    echo "<div class='container'>
            <h2 style='text-align: center;'>Benvenuto nella modifica delle categorie, {$_SESSION["UTENTE"]}!</h2>";

    echo "<footer>
            <button onclick='redirectToPage(\"categorie.php\")'>Visualizza le Categorie</button>
          </footer><br>";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
     
        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            if (isset($_POST['fill_category'])) {
                // Riempire i campi con i dati della categoria selezionata
                $category_id = $_POST['category_id'];
                $stmt = $conn->prepare("SELECT * FROM categorie WHERE id = ?");
                $stmt->execute([$category_id]);
                $category = $stmt->fetch(PDO::FETCH_ASSOC);

                // Genera la sezione HTML per la modifica della categoria
                echo "<div class='add-container'>
                        <h3 style='text-align: center;'>Modifica Categoria</h3>
                        <form method='POST'>
                            <input type='hidden' name='category_id' value='{$category['id']}'>
                            <label for='nome'>Nome:</label>
                            <input type='text' name='nome' value='{$category['nome']}'><br>
                            <button type='submit' name='update_category'>Aggiorna Categoria</button>
                        </form>
                    </div>";
            }

            if (isset($_POST['update_category'])) {
                // Logica per aggiornare la categoria
                // Prendi i dati dal modulo
                $category_id = $_POST['category_id'];
                $nome = $_POST['nome'];

                // Preparazione query per l'aggiornamento dei dati
                $stmt = $conn->prepare("UPDATE categorie SET nome = ? WHERE id = ?");
                $stmt->execute([$nome, $category_id]);

                echo "<div style='text-align: center; color: green;'>Operazione completata con successo!</div>";
            }


        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        } finally {
            $conn = null;
        }
    }


    echo "<div class='add-container'>
            <form method='POST'>
                <label>Seleziona ID della categoria da modificare:</label>
                <select name='category_id'>
                    <option></option>";
                    // Opzioni per selezionare la categoria
                    try {
                        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        $query_categories = 'SELECT id FROM categorie ORDER BY id ASC;';
                        $stmt = $conn->prepare($query_categories);
                        $stmt->execute();
                        $categories = $stmt->fetchAll();

                        foreach ($categories as $category) {
                            echo "<option value='{$category['id']}'>{$category['id']}</option>";
                        }
                        
                    } catch (PDOException $e) {
                        echo "Connection failed: " . $e->getMessage();
                    }

    echo "      </select><br><br>
                <button type='submit' name='fill_category'>Riempi</button>
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
