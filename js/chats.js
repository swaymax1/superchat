const chatList = O("chats-list");
const addUserSubmit = O("add-user-submit");
const addUserField = O("add-user-field");
const errorMessage = O("add-user-error-message");
const user1 = O("username-div").value;


for (let chat of chatList.childNodes) {
    if (chat.className === 'chat') {
        chat.addEventListener("click", function () {
            window.location.href = `chat.php?chatId=${this.dataset.id}&user2=${this.dataset.user2}`;
        })
    }
}


addUserSubmit.addEventListener("click", addUser);


async function addUser() {

    const user2 = addUserField.value;
    const url = `./php/add_user.php?add_user=1&user1=${encodeURIComponent(user1)}&user2=${encodeURIComponent(user2)}`;
    if (user2 === '') return;
    try {
        const response = await fetch(url, {
            method: "GET",
        });
        const data = await response.json();
        if (!data.success) {
            errorMessage.innerText = data.message;
            return;
        }
        else {
            window.location.href = "./chat.php?id=" + data.id;
        }
    } catch (error) {
        errorMessage.textContent = error;
    }
}

function O(id) {
    return document.getElementById(id);
}

async function getUnseenCount(chatId) {
    const url = `./php/get_unread_messages.php?username=${encodeURIComponent(user1)}&chatId=${encodeURIComponent(chatId)}`;

    try {
        const response = await fetch(url, {
            method: "GET",
        });
        const data = await response.json();
        if (data.success) {
            return data.count;
        }
        else {
            return "";
        }
    }
    catch (error) {
        throw error;
    }
}

function updateUnseenCount() {
    const chats = document.getElementsByClassName('chat');
    for (let chat of chats) {
        const chatId = chat.dataset.id;
        getUnseenCount(chatId).then(count => {
            const unseenCountElement = document.getElementById(chatId + '-unseen');
            unseenCountElement.style = count > 0 ? 'visibility: visible' : 'visibility: hidden';
            unseenCountElement.innerText = count;
        });
    }
}

setInterval(updateUnseenCount, 2000);