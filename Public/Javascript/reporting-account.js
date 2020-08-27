let reportingButton = document.getElementById('reporting-button');


function reporting_account() {
    axios.post('Controller/Admin/reporting_account.php', {
        id_reported: reportingButton.name,
        id_check: id_check[id_check.length - 1]
    })
    .then(function (response) {
      if (response.data.response !== false) {
        if (response.data.response) {
            document.location.href="http://localhost:8080/Dog-s-Matcha/online.php";
        }
      }
    })
      .catch(function (error) {
        console.log(error);
      });
}