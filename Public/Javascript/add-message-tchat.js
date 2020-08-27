let sendMessageButton = document.getElementById('send-message-button');
let contentMessage = document.getElementById('content-message');

check_input_message();

contentMessage.addEventListener('input', check_input_message);

function check_input_message() {
 if ( contentMessage.value.length > 0 && contentMessage.value.length < 256) {
  sendMessageButton.disabled = false;
 } else {
  sendMessageButton.disabled = true;
 }
}

function add_message_tchat() {
  if (contentMessage.value.length > 0 && contentMessage.value.length < 256) {
    axios.post('Controller/Admin/add_message_tchat.php', {
      id_sended: sendMessageButton.name,
      message: contentMessage.value,
      id_check: id_check[id_check.length - 1]
    })
    .then(function (response) {
      if (response.data.success !== false) {
        if (response.data.failure) {
          contentMessage.value = "";
        } else if (response.data.success) {
          add_notification(sendMessageButton.name, 2, 0);
          contentMessage.value = "";
        } else {
          contentMessage.value = "";
        }
      } else {
        contentMessage.value = "";  
      }
    })
    .catch(function (error) {
      contentMessage.value = "";
      console.log(error);
    });
  } else {
    contentMessage.value = "";
  }
}