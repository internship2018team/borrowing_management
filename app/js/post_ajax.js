    let xhr = new XMLHttpRequest();
    let indicateuser = document.getElementById('user_hyoji');
    let indicatebook = document.getElementById('book_hyoji');

    // 処理用変数
    var syori_state = "init";
    var json;
    var arraybook = [];
    var arrayuser = [];


    xhr.onreadystatechange = () =>  {
        if((xhr.status == 200) && (xhr.readyState == 4)){
            json = JSON.parse(xhr.responseText);
            console.log(json);
            if(syori_state == "user"){
                arrayuser = [];
                for(var i=0; i < Object.keys(json).length; i++){
                    arrayuser.push(json[i]);
                }
                createTag2(arrayuser);
            }else if(syori_state == "book"){
                arraybook = [];
                for(var i=0; i < Object.keys(json).length; i++){
                    arraybook.push(json[i]);
                }
                createTag(arraybook);
            }else{
                arraybook = [];
                arrayuser = [];
                for(var i=0; i < Object.keys(json).length; i++){
                    if(json[i].title == null){
                        arrayuser.push(json[i]);
                    }else{
                        arraybook.push(json[i]);
                    }
                }
                createTag(arraybook);
                createTag2(arrayuser);
            }
            // 入力内容等の中身を削除する関数呼び出し予定
        }
    }

    //post部分
    function registerUser() {
        xhr.open('post', 'http://localhost:8080/server/ajax.php',true);
        xhr.setRequestHeader('content-type','application/x-www-form-urlencoded;charset=UTF-8');
        xhr.send('name='+document.user_info.user_name.value);
        syori_state = "user";
    }

    function registerBook(){
        xhr.open('post', 'http://localhost:8080/server/ajax.php',true);
        xhr.setRequestHeader('content-type','application/x-www-form-urlencoded;charset=UTF-8');
        xhr.send('title='+document.book_info.book_name.value);
        syori_state = "book";
    }

    function deleteUser(del_id){
        xhr.open('post', 'http://localhost:8080/server/ajax.php',true);
        xhr.setRequestHeader('content-type','application/x-www-form-urlencoded;charset=UTF-8');
        xhr.send('del_user_id='+del_id);
        syori_state = "user";
    }

    function deleteBook(del_id){
        xhr.open('post', 'http://localhost:8080/server/ajax.php',true);
        xhr.setRequestHeader('content-type','application/x-www-form-urlencoded;charset=UTF-8');
        xhr.send('del_book_id='+del_id);
        syori_state = "book";
    }

    {
        xhr.open('post', 'http://localhost:8080/server/ajax.php',true);
        xhr.setRequestHeader('content-type','application/x-www-form-urlencoded;charset=UTF-8');
        xhr.send('push3=on');
        console.log("表示しました");
    }



    //表示内容部分
    function createTag(books){
        while (indicatebook.firstChild) indicatebook.removeChild(indicatebook.firstChild);
        //indicatebook.innerHTML = "<tr><th>book一覧</th></tr>";
        for(var i=0; i <Object.keys(books).length; i++) {
            var createtr = document.createElement('tr');
            createtr.innerHTML = "<td>" + books[i].title + "</td><td><button onclick='deleteBook("+ books[i].id +")'>削除</button></td>"
            indicatebook.appendChild(createtr);
        }
    }
    function createTag2(users){
        while (indicateuser.firstChild) indicateuser.removeChild(indicateuser.firstChild);
        //indicateuser.innerHTML = "<tr><th>ユーザー一覧</th></tr>";
        for(var i=0; i < users.length; i++){
            var createtr = document.createElement('tr');
            createtr.innerHTML = "<td>" + users[i].name + "</td><td><button onclick='deleteUser("+ users[i].id +")'>削除</button></td>"
            indicateuser.appendChild(createtr);
        }
    }