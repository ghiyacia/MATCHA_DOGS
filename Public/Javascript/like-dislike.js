let like = document.getElementById('like');
let likes_number = document.getElementById('likes_number');
let id_check = window.location.href.split('=');


function like_dislike() {
    axios.post('Controller/Admin/like_dislike.php', {
        id_liked: like.name,
        id_check: id_check[id_check.length - 1]
      })
      .then(function (response) {
        if (response.data.success !== false) {
          if (response.data.success === 1) {
            likes_number.innerHTML = response.data.total_likes;
            like.src = 'Public/Images/love_black.png';
            like.onmouseout = function() { this.src= 'Public/Images/love_black.png'; } ;
          } else if (response.data.success === 2) {
            likes_number.innerHTML = response.data.total_likes;
            like.onmouseout = this.src = 'Public/Images/love.png';
            like.src = 'Public/Images/love.png';
            add_notification(like.name, 0, 0);
          } else if (response.data.success === 3) {
            likes_number.innerHTML = response.data.total_likes;
            like.onmouseout = this.src = 'Public/Images/love.png';
            like.src = 'Public/Images/love.png';
            add_notification(like.name, 3, 0);
          } else if (response.data.success === 4) {
            likes_number.innerHTML = response.data.total_likes;
            like.src = 'Public/Images/love_black.png';
            like.onmouseout = function() { this.src= 'Public/Images/love_black.png'; } ;
            add_notification(like.name, 4, 0);
          }
        }
      })
      .catch(function (error) {
        console.log(error);
      });
}