

function remove_historical() {
    axios.post('Controller/Admin/remove_historical.php', {})
    .then(function (response) {
      if (response.data.response !== false) {
        if (response.data.response) {
            document.location.href="http://localhost:8080/Dog-s-Matcha/online.php?page=historical";
        }
      }
    })
      .catch(function (error) {
        console.log(error);
      });
}