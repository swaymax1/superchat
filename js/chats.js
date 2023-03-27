const chatList = O("chats-list");
const addUserSubmit = O("add-user-submit");
const addUserField = O("add-user-field");
const errorMessage = O("error-message");
const user1 = O("username-div").value;
const logout = O("logout");


for (let chat of chatList.childNodes) {
    if (chat.className === 'chat') {
        chat.addEventListener("click", function () {
            window.location.href = `chat.php?chatId=${this.dataset.id}&user2=${this.dataset.user2}`;
        })
    }
}


addUserSubmit.addEventListener("click", addUser);
logout.addEventListener("click", () => {
    fetch("./php/logout.php").then((_) => {
        window.location.href = 'login.php';
    });
});
setInterval(updateUnseenCount, 2000);

async function addUser() {

    const user2 = addUserField.value;
    if (user2 === '') return;
    const url = `./php/add_user.php?add_user=1&user1=${encodeURIComponent(user1)}&user2=${encodeURIComponent(user2)}`;
    try {
        const response = await fetch(url, {
            method: "GET",
        });
        const data = await response.json();
        if (data.success) {
            window.location.href = `chat.php?chatId=${data.id}&user2=${user2}`;
        }
        else {
            errorMessage.innerText = data.message;
        }
    } catch (error) {
        errorMessage.innerText = "something went wrong";
    }
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

function O(id) {
    return document.getElementById(id);
}

