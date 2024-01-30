function validateInput(input, regex, errorId) {
    const errorDiv = document.getElementById(errorId);
    if (!regex.test(input.value)) {
        errorDiv.style.display = 'block';
    } else {
        errorDiv.style.display = 'none';
    }
    validateForm();
}

function validatePassword(input, errorId) {
    const errorDiv = document.getElementById(errorId);
    const passwordValidationPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&_]).{8,}$/;
    if (!passwordValidationPattern.test(input.value)) {
        errorDiv.style.display = 'block';
    } else {
        errorDiv.style.display = 'none';
    }
    validateForm();
}

function validatePasswordMatch() {
    const password1 = document.getElementById('registration_form_plainPassword_first').value;
    const password2 = document.getElementById('registration_form_plainPassword_second').value;
    const errorDiv = document.getElementById('password2Error');
    if (password1 !== password2) {
        errorDiv.style.display = 'block';
    } else {
        errorDiv.style.display = 'none';
    }
    validateForm();
}

function validateForm() {
    const form = document.getElementById('registrationForm');
    const submitButton = document.getElementById('submitButton');
    const errorMessages = document.getElementsByClassName('error-message-client');
    const inputs = form.getElementsByTagName('input');
    const isEmptyFieldExist = Array.from(inputs).some(input => input.value === '');
    const isErrorExist = Array.from(errorMessages).some(message => message.style.display !== 'none');
    submitButton.disabled = isErrorExist || isEmptyFieldExist;
}