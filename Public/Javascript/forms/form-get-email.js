let getEmail = document.getElementById('get-email');
let sendButton = document.getElementById('send-button');

let emailRegex = /^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/i;

checkAllValues();

function checkAllValues(){
    if  (emailRegex.test(getEmail.value)) {
        sendButton.disabled = false;
    } else {
        sendButton.disabled = true;
    }
}

getEmail.addEventListener('input', checkAllValues);