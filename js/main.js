
let loginSubmit = document.getElementById("login-submit");
let registerSubmit = document.getElementById("register-submit");
let form = document.getElementById("form");


if (loginSubmit) {
    loginSubmit.addEventListener("click", login);
}

if (registerSubmit) {
    registerSubmit.addEventListener("click", register);
}


async function login(event) {
    event.preventDefault();
    let username = document.getElementById("username").value;
    let password = document.getElementById("password").value;
    let formMessage = document.getElementById('form-message');

    if (username == '') {
        formMessage.innerText = "Enter your username";
        return;
    }
    if (password == '') {
        formMessage.innerText = "Enter your password";
        return;
    }

    form.submit();

}


async function register(event) {
    event.preventDefault();

    let formMessage = document.getElementById('form-message');
    let input = {};
    document.querySelectorAll('.form__input').forEach((item) => {
        input[item.getAttribute('name')] = item.value;
    });


    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(input.email)) {
        formMessage.innerText = 'Email address is invalid';
        return;
    }

    if (input.username.length < 4) {
        formMessage.innerText = 'Username too short';
        return;
    }

    if (/[^a-zA-Z0-9]/.test(input.username)) {
        formMessage.innerText = 'Username must contain only letters and numbers';
        return;
    }

    if (input.password.length < 8) {
        formMessage.innerText = 'Password must be at least 8 characters long';
        return;
    }

    if (input.password !== input['confirm-password']) {
        formMessage.innerText = 'Passwords do not match';
        return;
    }

    form.submit();
    
}
