document.addEventListener('DOMContentLoaded', () => {
    const contactsList = document.getElementById('contacts-list');
    const messagesContainer = document.getElementById('messages-container');
    const chatHeader = document.getElementById('chat-header');
    const messageForm = document.getElementById('message-form');
    const messageInput = document.getElementById('message-input');

    let currentContactId = null;

    fetchContacts();

    function fetchContacts() {
        fetch('backend/traitement_messagerie.php?action=get_contacts')
            .then(response => response.json())
            .then(data => {
                contactsList.innerHTML = '';

                if (data.status === 'success' && data.data.length > 0) {
                    data.data.forEach(contact => {
                        const div = document.createElement('div');
                        div.className = 'contact-item';
                        div.textContent = `${contact.prenom} ${contact.nom}`;
                        div.dataset.id = contact.id;

                        div.addEventListener('click', () => selectContact(contact));
                        contactsList.appendChild(div);
                    });
                } else {
                    contactsList.innerHTML = '<div class="no-data">Aucun contact trouvé</div>';
                }
            })
            .catch(err => console.error(err));
    }

    function selectContact(contact) {
        currentContactId = contact.id;

        document.querySelectorAll('.contact-item').forEach(el => el.classList.remove('active'));
        const activeEl = document.querySelector(`.contact-item[data-id="${contact.id}"]`);
        if (activeEl) activeEl.classList.add('active');

        chatHeader.innerHTML = `<strong>${contact.prenom} ${contact.nom}</strong>`;

        messageForm.style.display = 'flex';

        fetchMessages();
    }

    function fetchMessages() {
        if (!currentContactId) return;

        fetch(`backend/traitement_messagerie.php?action=get_messages&contact_id=${currentContactId}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    displayMessages(data.data);
                }
            })
            .catch(err => console.error(err));
    }

    function displayMessages(messages) {
        messagesContainer.innerHTML = '';

        messages.forEach(msg => {
            const bubble = document.createElement('div');
            bubble.className = `message-bubble ${msg.type}`;

            const content = document.createElement('div');
            content.className = 'content';
            content.textContent = msg.contenu;

            const time = document.createElement('div');
            time.className = 'time';
            const date = new Date(msg.date_heure);
            time.textContent = date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

            bubble.appendChild(content);
            bubble.appendChild(time);
            messagesContainer.appendChild(bubble);
        });

        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    messageForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const content = messageInput.value.trim();

        if (!content || !currentContactId) return;

        fetch('backend/traitement_messagerie.php?action=send_message', {
            method: 'POST',
            body: JSON.stringify({
                destinataire_id: currentContactId,
                contenu: content
            }),
            headers: { 'Content-Type': 'application/json' }
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    messageInput.value = '';
                    fetchMessages();
                }
            });
    });

    setInterval(fetchMessages, 5000);
});
