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
    <title>Messagerie - GlucoNet</title>
    <link rel="stylesheet" href="res/style.css">
    <link href='res/logo_site.png' rel='icon'>
</head>
<body>

    <?php include 'nav_bar.php'; ?>

    <div class="messagerie-container">
        <!-- Liste des contacts -->
        <div class="contacts-list" id="contactsList">
            <!-- Les contacts seront chargés ici par JS -->
            <div style="padding:15px; text-align:center; color:#888;">Chargement...</div>
        </div>

        <!-- Zone de discussion -->
        <div class="chat-area">
            <div class="chat-header" id="chatHeader">Sélectionnez un contact</div>
            
            <div class="messages-container" id="messagesContainer">
                <!-- Les messages seront chargés ici -->
            </div>

            <div class="input-area">
                <textarea id="messageInput" placeholder="Écrivez votre message..."></textarea>
                <button onclick="sendMessage()">Envoyer</button>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        let currentContactId = null;
        const currentUser = <?php echo json_encode($_SESSION['user_id']); ?>;

        // Charger les contacts au démarrage
        document.addEventListener('DOMContentLoaded', loadContacts);

        function loadContacts() {
            fetch('backend/traitement_messagerie.php?action=get_contacts')
                .then(response => response.json())
                .then(data => {
                    const list = document.getElementById('contactsList');
                    list.innerHTML = '';
                    if (data.length === 0) {
                        list.innerHTML = '<div style="padding:15px;">Aucun contact trouvé.</div>';
                        return;
                    }
                    data.forEach(contact => {
                        const div = document.createElement('div');
                        div.className = 'contact-item';
                        div.innerHTML = `<div class="contact-name">${contact.prenom} ${contact.nom}</div>`;
                        div.onclick = () => selectContact(contact.id, contact.prenom + ' ' + contact.nom, div);
                        list.appendChild(div);
                    });
                })
                .catch(err => console.error('Erreur:', err));
        }

        function selectContact(id, name, element) {
            currentContactId = id;
            document.getElementById('chatHeader').innerText = 'Discussion avec ' + name;
            
            // Gestion de la classe active
            document.querySelectorAll('.contact-item').forEach(el => el.classList.remove('active'));
            if(element) element.classList.add('active');

            loadMessages();
        }

        function loadMessages() {
            if (!currentContactId) return;

            fetch(`backend/traitement_messagerie.php?action=get_messages&contact_id=${currentContactId}`)
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('messagesContainer');
                    container.innerHTML = ''; // On vide (simple, pourrait être optimisé)
                    
                    data.forEach(msg => {
                        const div = document.createElement('div');
                        div.className = `message ${msg.emetteur_type}`; // 'moi' ou 'autre'
                        div.innerHTML = `
                            ${msg.contenu.replace(/\n/g, '<br>')}
                            <div class="message-time">${new Date(msg.date_heure).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</div>
                        `;
                        container.appendChild(div);
                    });
                    
                    // Scroll to bottom
                    container.scrollTop = container.scrollHeight;
                });
        }

        function sendMessage() {
            if (!currentContactId) {
                alert("Veuillez sélectionner un contact");
                return;
            }
            const input = document.getElementById('messageInput');
            const contenu = input.value.trim();
            
            if (!contenu) return;

            fetch('backend/traitement_messagerie.php?action=send_message', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    destinataire_id: currentContactId,
                    contenu: contenu
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    input.value = '';
                    loadMessages(); // Recharger pour voir son message
                } else {
                    alert("Erreur lors de l'envoi");
                }
            });
        }

        // Auto refresh toutes les 5 secondes
        setInterval(() => {
            if (currentContactId) loadMessages();
        }, 5000);

    </script>
</body>
</html>