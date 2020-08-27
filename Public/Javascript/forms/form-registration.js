let lastname = document.getElementById('lastname');
let firstname = document.getElementById('firstname');
let email = document.getElementById('email');
let username = document.getElementById('username');
let password = document.getElementById('password');
let confirmPassword = document.getElementById('password_confirm');
let signupButton = document.getElementById('registration_button');

let lastnameRegex = /^[^!@#$%^&*(),.;?\":{}\[\]|<>0-9\t]{1,40}$/;
let firstnameRegex = /^[^!@#$%^&*(),.;?\":{}\[\]|<>0-9\t]{1,40}$/;
let emailRegex = /^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/i;
let usernameRegex = /^.{1,50}$/;
let passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*(),.;?\":{}\[\]|<>]).{6,50}$/;

checkAllValues();

function checkAllValues(){
    if  (lastnameRegex.test(lastname.value) && firstnameRegex.test(firstname.value) && emailRegex.test(email.value) && usernameRegex.test(username.value) && passwordRegex.test(password.value) && passwordRegex.test(confirmPassword.value)) {
        signupButton.disabled = false;
    } else {
        signupButton.disabled = true;
    }
}

lastname.addEventListener('input', checkAllValues);
firstname.addEventListener('input', checkAllValues);
email.addEventListener('input', checkAllValues);
username.addEventListener('input', checkAllValues);
password.addEventListener('input', checkAllValues);
confirmPassword.addEventListener('input', checkAllValues);