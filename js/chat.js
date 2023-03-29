
function O(id) {
    return document.getElementById(id);
}

let sending = false;

const messageForm = O('message_form');
const messageInput = O('message_field');
const messageSubmit = O('message_submit');
const messagesContainer = O('messages_container');
const creds = JSON.parse(O("credentials").innerText);
const email = creds['email'];
const username = creds['username'];
const chatId = creds['chatId'];
const user2 = creds['user2'];
let chatElements = [];
let messages = [];
let lastTimeStamp = 0;

getMessages();
setInterval(getMessages, 2000);


messageForm.addEventListener('submit', event => {
    event.preventDefault();

    const text = messageInput.value;
    if (text === '' || sending) return;
    const message = createMessage(text, username);
    chatElements.push(message);
    messagesContainer.appendChild(message);
    scroll();
    messageInput.value = '';
    uploadMessage(text);
});


function createMessage(text, sender) {
    const messageElement = document.createElement('span');
    messageElement.classList.add('message', sender === username ? 'mine' : 'theirs');
    messageElement.innerText = text;

    return messageElement;
}

function uploadMessage(message) {
    if (message === '') return;
    const info = {
        'message_upload': true,
        'chatId': chatId,
        'sender': username,
        'content': message,
    };
    sending = true;
    fetch('./php/chat.inc.php', {
        method: 'POST',
        body: JSON.stringify(info),
        headers: {
            'Content-Type': 'application/json',
        },
    }).then((response) => response.json()).then((data) => {
        lastTimeStamp = data.timestamp;
        sending = false;
    });

}


function getMessages() {
    if (sending) return;
    const params = `chatId=${chatId}&since=${lastTimeStamp}`;
    fetch("./php/chat.inc.php?" + params, {
        method: 'GET',
    })
        .then((response) => (response.json())
            .then((data) => {
                if (data.messages.length > 0) {
                    setMessages(data.messages);
                    scroll();
                    lastTimeStamp = data.messages[data.messages.length - 1]['timestamp'];
                }
            }));
}

function setMessages(messages) {
    for (let message of messages) {
        let element = createMessage(message['content'], message['sender_id']);
        chatElements.push(element);
    }
    messagesContainer.append(...chatElements);
}

function scroll() {
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

