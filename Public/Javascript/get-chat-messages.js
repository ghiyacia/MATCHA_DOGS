let divMessagesChat = document.getElementById('messages');
let matchedPhoto = document.getElementById('matched-photo');
let userPhoto = document.getElementById('user-photo');


setInterval(get_chat_messages, 500);

function get_chat_messages() {
    axios.post('Controller/Admin/get_chat_messages.php', {
      id_sended: sendMessageButton.name,
      id_check: id_check[id_check.length - 1]
    })
    .then(function (response) {
      if (response.data.success !== false) {
        if (response.data.response != 0) {

          let result = response.data.response;
            for(i = 0; i < result.length; i++) {
              if (result[0][1] === contentMessage.name) {

                let contentMessageDiv = document.createElement('div');
                contentMessageDiv.setAttribute('class', 'tchat');

                let imageMessageDiv = document.createElement('img');
                imageMessageDiv.setAttribute('src', userPhoto.href);
                imageMessageDiv.setAttribute('alt', 'Avatar');
                imageMessageDiv.setAttribute('style', 'height: 60px;');

                let paragraphMessageDiv = document.createElement('p');
                paragraphMessageDiv.innerHTML = result[0][3];


                let spanMessageDiv = document.createElement('span');
                spanMessageDiv.setAttribute('class', 'time-right');
                spanMessageDiv.innerHTML = 'le ' + sqlToJsDate(result[0][4]);


                contentMessageDiv.appendChild(imageMessageDiv);
                contentMessageDiv.appendChild(paragraphMessageDiv);
                contentMessageDiv.appendChild(spanMessageDiv);

                divMessagesChat.insertBefore(contentMessageDiv, divMessagesChat.childNodes[divMessagesChat.childNodes.length - 1]);

              } else {

                let contentMessageDiv = document.createElement('div');
                contentMessageDiv.setAttribute('class', 'tchat darker');

                let imageMessageDiv = document.createElement('img');
                imageMessageDiv.setAttribute('src', matchedPhoto.src);
                imageMessageDiv.setAttribute('alt', 'Avatar');
                imageMessageDiv.setAttribute('class', 'right');
                imageMessageDiv.setAttribute('style', 'height: 60px;');

                let paragraphMessageDiv = document.createElement('p');
                paragraphMessageDiv.innerHTML = result[0][3];


                let spanMessageDiv = document.createElement('span');
                spanMessageDiv.setAttribute('class', 'time-left');
                spanMessageDiv.innerHTML = 'le ' + sqlToJsDate(result[0][4]);

                contentMessageDiv.appendChild(imageMessageDiv);
                contentMessageDiv.appendChild(paragraphMessageDiv);
                contentMessageDiv.appendChild(spanMessageDiv);

                divMessagesChat.insertBefore(contentMessageDiv, divMessagesChat.childNodes[divMessagesChat.childNodes.length - 1]);
              }
            }
          }
        }
      })
      .catch(function (error) {
        console.log(error);
      });
}

function sqlToJsDate(sqlDate){
  //sqlDate in SQL DATETIME format ("yyyy-mm-dd hh:mm:ss.ms")
  let sqlDateArr1 = sqlDate.split("-");
  
  
  //format of sqlDateArr1[] = ['yyyy','mm','dd hh:mm:ms']
  let year = sqlDateArr1[0];
  let month = sqlDateArr1[1];

  let sqlDateArr2 = sqlDateArr1[2].split(" ");

  //format of sqlDateArr2[] = ['dd', 'hh:mm:ss.ms']
  let day = sqlDateArr2[0];
  
  let sqlDateArr3 = sqlDateArr2[1].split(":");
  
  //format of sqlDateArr3[] = ['hh','mm','ss.ms']
  let hour = sqlDateArr3[0];
  let minute = sqlDateArr3[1];

  let finalFormat = day + '/' + month + '/' + year + ' à ' + hour + ':' + minute + '.';

  return finalFormat;
}