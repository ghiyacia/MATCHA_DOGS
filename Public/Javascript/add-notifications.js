
function add_notification(id_notificated, notification_number, status) {
    axios.post('Controller/Admin/add_notifiations.php', {
        id_notificated: id_notificated,
        notification_number: notification_number,
        status: status
      })
      .then(function (response) {
      })
      .catch(function (error) {
        console.log(error);
      });
}