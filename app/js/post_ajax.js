    let xhr = new XMLHttpRequest();
    let user_add_button = document.getElementById('user_add');

    
    xhr.onreadystatechange = () =>  {
    
        if((xhr.status == 200) && (xhr.readyState == 4)){
            //var json ='{"title":"桃太郎","book_id":"2","history_id":"3","user_id":"2","borrow_date":"2018-08-22 05:44:50","can_borrow":"0"}';      
            console.log(xhr.responseText);
            var json = JSON.parse(xhr.responseText);
            console.log(json);
            console.log(json[0].title);
        }
    }
/*
    user_add_button.addEventListener('click', () => {
        xhr.open('post', 'http://localhost:8080/server/ajax.php',true);
        xhr.setRequestHeader('content-type','application/x-www-form-urlencoded;charset=UTF-8');
        xhr.send('name=akira');
    },false);
*/
    function createUser() {
        xhr.open('post', 'http://localhost:8080/server/ajax.php',true);
        xhr.setRequestHeader('content-type','application/x-www-form-urlencoded;charset=UTF-8');
        xhr.send('name='+document.user_info.book_name.value);
        console.log("登録しました");
    }
