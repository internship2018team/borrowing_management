    let xhr = new XMLHttpRequest();
    let user_add_button = document.getElementById('user_registration');
    let test_button = document.getElementById('test');
    let create = document.getElementById('user_hyoji');
    let del = document.getElementById('del');

    xhr.onreadystatechange = () =>  {
        if((xhr.status == 200) && (xhr.readyState == 4)){
            console.log(xhr.responseText);
            var json = JSON.parse(xhr.responseText);
            console.log(json);
            var arraybook = [];
            for(var i=0; i < Object.keys(json).length; i++){
                var tmp = json[i].title
                arraybook.push(tmp);
            }
            createTag(arraybook);
        }
    }

    //post部分
    function registrationUser() {
        xhr.open('post', 'http://localhost:8080/server/ajax.php',true);
        xhr.setRequestHeader('content-type','application/x-www-form-urlencoded;charset=UTF-8');
        xhr.send('name='+document.user_info.user_name.value);
    }

    function registrationBook(){
        xhr.open('post', 'http://localhost:8080/server/ajax.php',true);
        xhr.setRequestHeader('content-type','application/x-www-form-urlencoded;charset=UTF-8');
        xhr.send('title='+document.book_info.book_name.value);
    }

    test_button.addEventListener('click', () => {
        xhr.open('post', 'http://localhost:8080/server/ajax.php',true);
        xhr.setRequestHeader('content-type','application/x-www-form-urlencoded;charset=UTF-8');
        xhr.send('push=on')
        console.log("登録しました");
    },false);



    //表示内容部分
    function createTag(books){
        while (create.firstChild) create.removeChild(create.firstChild);
        for(var i=0; i < books.length; i++){
           var pk = document.createElement('p');
           pk.textContent = books[i];
           create.appendChild(pk);
        }
    }

    del.addEventListener('click',  () => {
        while (create.firstChild) create.removeChild(create.firstChild);
    },false);