<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messagerie - Gluconet</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="res/style.css">
</head>

<body>

    <!-- Barre de navigation -->
    <?php include 'nav_bar.php'; ?>

    <div class="main-container">
        <div class="messaging-layout">

            <aside class="contacts-sidebar">
                <h3>Vos Contacts</h3>
                <div id="contacts-list" class="contacts-list">
                    <div class="loading-spinner">Chargement...</div>
                </div>
            </aside>

            <section class="chat-area" id="chat-area">
                <div class="chat-header" id="chat-header">
                    <span class="placeholder-text">Sélectionnez un contact pour discuter</span>
                </div>

                <div class="messages-container" id="messages-container">
                </div>

                <form id="message-form" class="message-input-area" style="display: none;">
                    <input type="text" id="message-input" placeholder="Votre message..." autocomplete="off">
                    <button type="submit" class="send-btn">
                        <span class="material-symbols-outlined">send</span>
                    </button>
                </form>
            </section>

        </div>
    </div>

    <script src="res/messagerie.js"></script>
</body>

</html>