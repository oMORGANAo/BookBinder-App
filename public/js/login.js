const emailInput = document.querySelector('input[type="email"]');
const passwordInput = document.querySelector('input[type="password"]');
const loginButton = document.querySelector('button[type="submit"]');

function isValidEmail(email) {
    const emailPattern = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;
    return emailPattern.test(email);
}

function updateLoginButtonState() {
    const isEmailValid = isValidEmail(emailInput.value);
    const isPasswordNotEmpty = passwordInput.value.trim() !== '';
    loginButton.disabled = !isEmailValid || !isPasswordNotEmpty;
}

emailInput.addEventListener('input', updateLoginButtonState);
passwordInput.addEventListener('input', updateLoginButtonState);

updateLoginButtonState();