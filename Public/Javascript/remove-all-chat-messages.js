


function remove_all_chat_messages() {
  axios.post('Controller/Admin/remove_all_chat_messages.php', {
    id_sended: like.name,
    id_check: id_check[id_check.length - 1]
  })
  .then(function (response) {
    if (response.data.success !== false) {
      if (response.data.response) {
        document.location.href="http://localhost:8080/Dog-s-Matcha/online.php?page=show_matched_profil&id=" + like.name;
      }
    }
  })
  .catch(function (error) {
    console.log(error);
  });
}