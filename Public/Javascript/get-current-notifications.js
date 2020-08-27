let notificationsDiv = document.getElementById('notifications-div');

setInterval(get_curent_notifications, 5000);

function get_curent_notifications() {
    axios.post('Controller/Admin/get_current_notifications.php', {})
      .then(function (response) {
        if (response.data.response !== false) {
          if (response.data.response) {
            let result = response.data.response;
            for (i = 0; i < result.length; i++) {
            let contentNotificationDiv = document.createElement('div');
            contentNotificationDiv.setAttribute('class', 'alert alert-primary col-sm-12 col-md-12 col-lg-12 col-xl-12');
            let notificationDate = sqlToJsDate(result[0][1]);
            contentNotificationDiv.innerHTML = unescape(result[0][0]) + ' le ' + notificationDate;
            notificationsDiv.insertBefore(contentNotificationDiv, notificationsDiv.childNodes[0]);
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
    