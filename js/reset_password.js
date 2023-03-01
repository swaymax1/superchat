
let submitSendEmail = document.getElementById("submit_send_email");
let submitResetPassword = document.getElementById("submit_reset_password");


if (submitSendEmail) {
    submitSendEmail.addEventListener("click", sendEmail);
}

if (submitResetPassword) {
    submitResetPassword.addEventListener("click", resetPassword);
}

async function resetPassword(event) {
    event.preventDefault();

    let password = document.getElementById("password").value;
    let passwordConfirm = document.getElementById("password_confirm").value;
    let formMessage = document.getElementById("form-message");

    if (password.length < 8) {
        formMessage.innerText = 'Password must be at least 8 characters long';
        return;
    }

    if (password !== passwordConfirm) {
        formMessage.innerText = 'Passwords do not match';
        return;
    }

    try {
        const response = await fetch('reset_password.php', {
            method: 'POST',
            body: `password=${encodeURIComponent(password)}`,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
        });
        const data = await response.json();
        if (data.success) {
            window.location.href = 'login.php';
        }
        else {
            formMessage.innerText = data.message;
        }
    }
    catch (error) {
        formMessage.innerText = "An error occured";
    }
}

async function sendEmail(event) {
    event.preventDefault();

    let email = document.getElementById("email").value;
    let formMessage = document.getElementById("form-message");

    if (email == '') {
        formMessage.innerText = "Enter your email address";
        return;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        formMessage.innerText = 'Email address is invalid';
        return;
    }

    try {
        const response = await fetch("send_reset_password.php", {
            method: "POST",
            body: `email=${encodeURIComponent(email)}`,
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
        });
        const data = await response.json();
        if (data.success) {
            document.getElementById("email_send_message").innerText =
                `An email was send to ${email}, please follow the instruction to reset the password`;
            document.getElementById("reset_password").classList.add("hidden");
            document.getElementById("email_sent").classList.remove("hidden");
        } else {
            formMessage.innerText = data.message;
        }
    }
    catch (error) {
        formMessage.innerText = "An error occured";
    }
}