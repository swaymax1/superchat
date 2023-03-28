
function O(id) {
    return document.getElementById(id);
}

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
scroll();


messageForm.addEventListener('submit', event => {
    event.preventDefault();

    const text = messageInput.value;
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

async function uploadMessage(message) {
    const info = {
        'message_upload': true,
        'chatId': chatId,
        'sender': username,
        'content': message,
    };
    lastTimeStamp = Date.now() / 1000;

    try {
        fetch('./php/chat.inc.php.', {
            method: 'POST',
            body: JSON.stringify(info),
            headers: {
                'Content-Type': 'application/json',
            },
        });

    } catch (error) {
        throw error;
    }
}


async function getMessages() {
    const params = `chatId=${chatId}&since=${lastTimeStamp}`;
    try {
        let response = await fetch("./php/chat.inc.php?" + params, {
            method: 'GET',
        });
        let data = await response.json();
        if (data.messages.length > 0) {
            setMessages(data.messages);
            let lastMessage = data.messages[data.messages.length - 1];
            lastTimeStamp = new Date(lastMessage['created_at']).getTime() / 1000;
        }
    } catch (error) {
        throw error;
    }
}

function getMessages() {
    const params = `chatId=${chatId}&since=${lastTimeStamp}`;
    fetch("./php/chat.inc.php?" + params, {
        method: 'GET',
    })
        .then((response) => (response.json())
            .then((data) => {
                if (data.messages.length > 0) {
                    setMessages(data.messages);
                    let lastMessage = data.messages[data.messages.length - 1];
                    lastTimeStamp = new Date(lastMessage['created_at']).getTime() / 1000;
                }
            }));
}

function setMessages(messages) {
    for (let message of messages) {
        let element = createMessage(message['content'], message['sender_id']);
        const shouldScroll = messagesContainer.scrollTop == messagesContainer.scrollHeight;
        chatElements.push(element);
        if (shouldScroll) scroll();
    }
    messagesContainer.append(...chatElements);
}

function scroll() {
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}
