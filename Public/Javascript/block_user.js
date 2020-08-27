let blockButton = document.getElementById('block_button');

blockButton.addEventListener('click', function () {
    axios.post('Controller/Admin/block_user.php', {
        id_blocked: blockButton.name,
        id_check: id_check[id_check.length - 1]
      })
      .then(function (response) {
        if (response.data.success !== false) {
          if (response.data.success) {
              document.location.href="http://localhost:8080/Dog-s-Matcha/online.php";
          }
        }
      })
      .catch(function (error) {
        console.log(error);
      });
});