<?php
session_start(); // Démarre la session

// Clé publique hCaptcha (Site key)
$siteKey = 'f364e7f6-264a-4dc6-a74e-7672927867de'; // Remplace 'TON_SITE_KEY' par ta clé publique de hCaptcha

// Clé secrète hCaptcha (Secret key)
$secretKey = 'ES_d682ea8723ef40cd9a83771332007bb8'; // Ta clé secrète de hCaptcha

// Vérifie si le formulaire est soumis et si hCaptcha est validé
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['h-captcha-response']) && !empty($_POST['h-captcha-response'])) {
        // Vérifier la réponse hCaptcha
        $response = $_POST['h-captcha-response'];
        $remoteIp = $_SERVER['REMOTE_ADDR'];

        // Appel à l'API hCaptcha pour vérifier la réponse
        $verifyResponse = file_get_contents("https://hcaptcha.com/siteverify?secret=$secretKey&response=$response&remoteip=$remoteIp");
        $responseKeys = json_decode($verifyResponse);

        if ($responseKeys->success) {
            // Si le CAPTCHA est validé, autorise le téléchargement du fichier
            $filePath = './QuickPadvBeta.zip'; // Spécifie le chemin du fichier à télécharger
            if (file_exists($filePath)) {
                // Nettoie les éventuels contenus précédemment envoyés pour éviter des erreurs
                ob_clean();
                flush();

                // Envoie les en-têtes HTTP pour le téléchargement
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="QuickPadvBeta.zip"');
                readfile($filePath); // Envoie le fichier au navigateur
                exit();
            } else {
                echo "Le fichier n'existe pas.";
            }
        } else {
            echo "Erreur de CAPTCHA. Veuillez réessayer.";
        }
    } else {
        echo "Veuillez valider le CAPTCHA.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Download QuickPad</title>
    <style>
        body {
            background-color: rgb(0, 0, 0);
            color: #ffffff;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            text-align: center;
            background-color: rgb(0, 0, 0);
            padding: 30px;
            border: 10px solid white;
            width: 80%;
            max-width: 500px;
        }
        h1 {
            font-size: 30px;
            margin-bottom: 20px;
        }
        button {
            background-color: rgb(0, 0, 0);
            color: white;
            border: 5px solid white;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 20px;
        }
        button:hover {
            background-color: rgb(255, 255, 255);
            color: black;
        }
    </style>

    <!-- Ajoute le script hCaptcha -->
    <script src="https://hcaptcha.com/1/api.js" async defer></script>
</head>
<body>

<div class="container">
    <h1>Télécharger QuickPad</h1>
    <p>Veuillez résoudre le CAPTCHA pour télécharger le fichier.</p>

    <!-- Formulaire avec hCaptcha -->
    <form method="POST">
        <div class="h-captcha" data-sitekey="<?php echo $siteKey; ?>"></div> <!-- hCaptcha ici -->
        <br>
        <button type="submit">Télécharger</button>
    </form>
</div>

</body>
</html>
