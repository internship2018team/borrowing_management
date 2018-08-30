    let xhr = new XMLHttpRequest();
    let indicateuser = document.getElementById('user_hyoji');
    let indicatebook = document.getElementById('book_hyoji');
    const post_url = "http://localhost:8080/server/ajax.php";

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
                createTagUser(arrayuser);
            }else if(syori_state == "book"){
                arraybook = [];
                for(var i=0; i < Object.keys(json).length; i++){
                    arraybook.push(json[i]);
                }
                createTagBook(arraybook);
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
                createTagBook(arraybook);
                createTagUser(arrayuser);
            }
        }
    }

    //post部分
    function registerUser() {
        confilmProcessing();
        xhr.open('post', post_url,true);
        xhr.setRequestHeader('content-type','application/x-www-form-urlencoded;charset=UTF-8');
        xhr.send('name='+document.user_info.user_name.value);
        syori_state = "user";
        clearText();

    }

    function registerBook(){
        confilmProcessing();
        xhr.open('post', post_url,true);
        xhr.setRequestHeader('content-type','application/x-www-form-urlencoded;charset=UTF-8');
        xhr.send('title='+document.book_info.book_name.value);
        syori_state = "book";
        clearText();
    }

    function deleteUser(del_id){
        confilmProcessing();
        xhr.open('post', post_url,true);
        xhr.setRequestHeader('content-type','application/x-www-form-urlencoded;charset=UTF-8');
        xhr.send('del_user_id='+del_id);
        syori_state = "user";
    }

    function deleteBook(del_id){
        confilmProcessing();
        xhr.open('post',post_url,true);
        xhr.setRequestHeader('content-type','application/x-www-form-urlencoded;charset=UTF-8');
        xhr.send('del_book_id='+del_id);
        syori_state = "book";
    }

    {
        xhr.open('post', post_url,true);
        xhr.setRequestHeader('content-type','application/x-www-form-urlencoded;charset=UTF-8');
        xhr.send('push3=on');
        console.log("表示しました");
    }



    //表示内容部分
    function createTagBook(books){
        while (indicatebook.firstChild) indicatebook.removeChild(indicatebook.firstChild);
        for(var i=0; i < books.length; i++) {
            var createtr = document.createElement('tr');
            createtr.innerHTML = "<td class='mdl-data-table__cell--non-numeric'>" + books[i].title + 
            "</td><td class='mdl-button mdl-js-button mdl-button--colored' onclick='deleteBook("+ books[i].id +")'>削除</td>"
            indicatebook.appendChild(createtr);
        }
    }

    function createTagUser(users){
        while (indicateuser.firstChild) indicateuser.removeChild(indicateuser.firstChild);
        for(var i=0; i < users.length; i++){
            var createtr = document.createElement('tr');
            createtr.innerHTML = "<td class='mdl-data-table__cell--non-numeric'>" + users[i].name + 
            "</td><td class='mdl-button mdl-js-button mdl-button--colored' onclick='deleteUser("+ users[i].id +")'>削除</td>"
            indicateuser.appendChild(createtr);
        }
    }

    //実行確認ダイアログ
    function confilmProcessing(){
        ret = confirm("実行します。本当によろしいですか?");
        if (ret == false){
            preventDefault();
        }
    }

    function clearText(){
        //document.getElementsByTagName("form")[0].reset();
        //document.getElementsByTagName("form")[1].reset();
        document.getElementsByClassName('close')[0].click();
    }