


function remove_notifications() {
    axios.post('Controller/Admin/remove_notifications.php', {})
    .then(function (response) {
      if (response.data.response !== false) {
        if (response.data.response) {
            document.location.href="http://localhost:8080/Dog-s-Matcha/online.php?page=notifications";
        }
      }
    })
      .catch(function (error) {
        console.log(error);
      });
}