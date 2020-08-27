let password = document.getElementById('password');
let passwordConfirm = document.getElementById('password-confirm');
let editButton = document.getElementById('edit-button');

let passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*(),.;?\":{}\[\]|<>]).{6,50}$/;

checkAllValues();

function checkAllValues(){
    if  (passwordRegex.test(password.value) && passwordRegex.test(passwordConfirm.value)) {
        editButton.disabled = false;
    } else {
        editButton.disabled = true;
    }
}

password.addEventListener('input', checkAllValues);
passwordConfirm.addEventListener('input', checkAllValues);