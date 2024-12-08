<?php
// Inizializza una variabile per il messaggio di conferma
$thank_you_message = "";
$error_captcha = "";


// Verifica se il modulo è stato inviato
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recupera i dati dal form
    $name = htmlspecialchars($_POST['name']);
    $guests = intval($_POST['guests']);
    $preferences = htmlspecialchars($_POST['preferences']);
    $recaptchaResponse = $_POST['g-recaptcha-response'];

    // Chiave segreta di Google reCAPTCHA
    $secretKey = "LA_TUA_SECRET_KEY";

    // Verifica del reCAPTCHA tramite richiesta a Google
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$recaptchaResponse");
    $responseKeys = json_decode($response, true);

    if (!$responseKeys["success"]) {
        $error_captcha = "Per favore, compila il Captcha";
    } else {
        // Percorso del file in cui salvare i dati
        $file = 'rsvp_data.txt';

        // Formatta i dati da salvare
        $data = "Nome: $name\nAccompagnatori: $guests\nPreferenze: $preferences\n---\n";

        // Salva i dati nel file
        file_put_contents($file, $data, FILE_APPEND);

        // Mostra un messaggio di conferma
        $thank_you_message = "Grazie per aver confermato la tua partecipazione!";
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matrimonio - Luigi e Viola</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
</head>
<body>
    <!-- Header -->
    <header>
        <h1>Il Nostro Grande Giorno</h1>
        <p>(15 Giugno 2024)?</p>
    </header>

    <!-- Navbar -->
    <div class="navbar">
        <a href="#dove-e-quando">Cerimonia e Ricevimento</a>
        <a href="#iban">Un piccolo pensiero</a>
        <a href="#conferma">Conferma presenza</a>
    </div>

    <!-- Contenitore principale -->
    <div class="container">
        <!-- Sezione 1: Dove e Quando -->
        <section id="dove-e-quando">
            <h2>Dove e Quando</h2>
            <div class="card">
                <img src="images/chiesa.jpg" alt="Chiesa di Santa Maria">
                <p><strong>Cerimonia:</strong> Chiesa di San Bartolomeo, Barberino in Val di Pesa</p>
                <p><a href="https://maps.app.goo.gl/9m4LhybW2kobiQSR9" target="_blank">Apri in Google Maps</a></p>
            </div>

            <div class="card">
                <img src="images/ricevimento.jpg" alt="Chiesa di Santa Maria">
                <p><strong>Ricevimento:</strong> Villa dei Fiori, Via Lago 456, Como</p>
                <p><a href="https://goo.gl/maps/example" target="_blank">Apri in Google Maps</a></p>
            </div>
        </section>

        <!-- Sezione 2: IBAN -->
        <section id="iban">
            <h2>Un Piccolo Pensiero</h2>
            <p class="iban-container">La vostra presenza è il regalo più grande. Ma se desiderate contribuire al nostro futuro, potete usare questo IBAN.</p>
            <div class="iban-container">
                <button class="iban-button" onclick="toggleIban()">Mostra IBAN</button>
                <div id="iban-content" class="iban-hidden">IT60X0542811101000000123456</div>
            </div>
        </section>

        <!-- Sezione 3: Conferma partecipazione -->
        <section id="conferma">
            <h2>Conferma la tua presenza</h2>
            <?php if ($thank_you_message): ?>
                <div class="thank-you">
                    <p><?php echo $thank_you_message; ?></p>
                </div>
            <?php endif; ?>
            <?php if ($error_captcha) : ?>
                <div class="error-captcha">
                    <p><?php echo $error_captcha; ?></p>
                </div>
            <?php endif; ?>

            <form action="" method="post">
                <!-- Campo Nome -->
                <label for="name">Nome e Cognome</label>
                <input type="text" id="name" name="name" placeholder="Inserisci il tuo nome e cognome" required>
                
                <!-- Numero di ospiti -->
                <label for="guests">Numero di accompagnatori</label>
                <input type="number" id="guests" name="guests" placeholder="Inserisci il numero di accompagnatori" min="0" required>
                
                <!-- Preferenze alimentari -->
                <label for="preferences">Preferenze alimentari (opzionale)</label>
                <input type="text" id="preferences" name="preferences" placeholder="Inserisci eventuali preferenze alimentari">
                
                <!-- Captcha -->
                <div class="g-recaptcha" data-sitekey="LA_TUA_SITE_KEY"></div>

                <!-- Pulsante di invio -->
                <button type="submit">Invia la tua conferma</button>
                <script src="https://www.google.com/recaptcha/api.js" async defer></script>
            </form>
        </section>
    </div>

    <!-- Footer -->
    <footer>
        <p>Con amore, Luigi e Viola</p>
    </footer>
</body>
</html>