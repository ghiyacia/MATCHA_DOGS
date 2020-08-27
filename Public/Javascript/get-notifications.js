let notificationsNumber = document.getElementById('notifications-number');

setInterval(get_notifications, 5000);

function get_notifications() {
    axios.post('Controller/Admin/get_notifications.php', {})
      .then(function (response) {
        if (response.data.response !== false) {
          if (response.data.response == 0) {
            notificationsNumber.style.display = 'none';
          } else {
            notificationsNumber.innerHTML = response.data.response;
            notificationsNumber.style.display = 'initial';
          }
        }
      })
      .catch(function (error) {
        console.log(error);
      });
}