function validateInput(input, regex, errorId) {
    const errorDiv = document.getElementById(errorId);
    if (!regex.test(input.value)) {
        errorDiv.style.display = 'block';
    } else {
        errorDiv.style.display = 'none';
    }
    validateForm();
}


function validateForm() {
    const form = document.getElementById('updateUserForm');
    const submitButton = document.getElementById('submitButton');
    const errorMessages = document.getElementsByClassName('error-message-client');
    const inputs = form.getElementsByTagName('input');
    const isEmptyFieldExist = Array.from(inputs).some(input => input.value === '' && input.id !== 'profile_form_profilePicture');
    const isErrorExist = Array.from(errorMessages).some(message => message.style.display !== 'none');
    submitButton.disabled = isErrorExist || isEmptyFieldExist;
}

// Call validateForm on page load
document.addEventListener('DOMContentLoaded', validateForm);