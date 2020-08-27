let usernameEmail = document.getElementById('username-email-connect');
let password = document.getElementById('password-connect');
let signinButton = document.getElementById('signin-button');

let usernameEmailRegex = /^.{1,50}$/;
let passwordRegex = /^.{6,50}$/;

checkAllValues();

function checkAllValues(){
    if  (usernameEmailRegex.test(usernameEmail.value) && passwordRegex.test(password.value)) {
        signinButton.disabled = false;
    } else {
        signinButton.disabled = true;
    }
}

usernameEmail.addEventListener('input', checkAllValues);
password.addEventListener('input', checkAllValues);