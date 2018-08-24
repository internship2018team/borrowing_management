    let xhr = new XMLHttpRequest();
    let test_button = document.getElementById('test');
    xhr.onreadystatechange = () =>  {
    
        if((xhr.status == 200) && (xhr.readyState == 4)){
            console.log(xhr.responseText);
        }
    }

    test_button.addEventListener('click', () => {
        xhr.open('post', 'http://localhost:8080/server/ajax.php',true);
        xhr.setRequestHeader('content-type','application/x-www-form-urlencoded;charset=UTF-8');
        xhr.send('name=taro&age=30');
    },false);

    function checkText() {
        alert(document.user_info.book_name.value);
    }
