    let xhr = new XMLHttpRequest();
    let user_add_button = document.getElementById('user_registration');
    let test_button = document.getElementById('test');
    let test_button2 = document.getElementById('test2');
    let book_hyoji = document.getElementById('book_hyoji');
    let create = document.getElementById('user_hyoji');
    let del = document.getElementById('del');
    
    // 処理用変数
    var syori_state = "init";
    var json;
    var tmp,tmp2;
    var arraybook = [];
    var arrayuser = [];


    xhr.onreadystatechange = () =>  {
        if((xhr.status == 200) && (xhr.readyState == 4)){
            json = JSON.parse(xhr.responseText);
            console.log(json);
            if(syori_state == "user"){
                arrayuser = [];
                for(var i=0; i < Object.keys(json).length; i++){
                    tmp2 = json[i].name;
                    arrayuser.push(tmp2);
                }
                createTag2(arrayuser);
            }else if(syori_state == "book"){
                arraybook = [];
                for(var i=0; i < Object.keys(json).length; i++){
                    tmp = json[i].title;
                    arraybook.push(tmp);
                }
                createTag(arraybook);
            }else{
                arraybook = [];
                arrayuser = [];
                for(var i=0; i < Object.keys(json).length; i++){
                    tmp = json[i].title;
                    tmp2 = json[i].name;
                    arraybook.push(tmp);
                    arrayuser.push(tmp2);
                }
                createTag(arraybook);
                createTag2(arrayuser);
            }
            // 入力内容等の中身を削除する関数呼び出し予定
        }
    }



    //post部分
    function registrationUser() {
        xhr.open('post', 'http://localhost:8080/server/ajax.php',true);
        xhr.setRequestHeader('content-type','application/x-www-form-urlencoded;charset=UTF-8');
        xhr.send('name='+document.user_info.user_name.value);
        syori_state = "user";
    }

    function registrationBook(){
        xhr.open('post', 'http://localhost:8080/server/ajax.php',true);
        xhr.setRequestHeader('content-type','application/x-www-form-urlencoded;charset=UTF-8');
        xhr.send('title='+document.book_info.book_name.value);
        syori_state = "book";
    }
    // 表示テスト用
    /* 
    test_button.addEventListener('click', () => {
        xhr.open('post', 'http://localhost:8080/server/ajax.php',true);
        xhr.setRequestHeader('content-type','application/x-www-form-urlencoded;charset=UTF-8');
        xhr.send('push=on');
        syori_state = "book";
        console.log("登録しました");
    },false);

    test_button2.addEventListener('click', () => {
        xhr.open('post', 'http://localhost:8080/server/ajax.php',true);
        xhr.setRequestHeader('content-type','application/x-www-form-urlencoded;charset=UTF-8');
        xhr.send('push2=on');
        console.log("登録しました");
        syori_state = "user";
    },false);
    */

    {
        xhr.open('post', 'http://localhost:8080/server/ajax.php',true);
        xhr.setRequestHeader('content-type','application/x-www-form-urlencoded;charset=UTF-8');
        xhr.send('push3=on');
        console.log("表示しました");
    }



    //表示内容部分
    function createTag(books){
        while (create.firstChild) create.removeChild(create.firstChild);
        for(var i=0; i < books.length; i++){
           var pk = document.createElement('div');
           pk.textContent = books[i];
           create.appendChild(pk);
        }
    }
    function createTag2(users){
        while (book_hyoji.firstChild) book_hyoji.removeChild(book_hyoji.firstChild);
        for(var i=0; i < users.length; i++){
           var pk2 = document.createElement('div');
           pk2.textContent = users[i];
           book_hyoji.appendChild(pk2);
        }
    }

    /*
    del.addEventListener('click',  () => {
        while (create.firstChild) create.removeChild(create.firstChild);
    },false);
    */