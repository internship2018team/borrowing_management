<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>管理者ページ</title>
    <script src="js/modal.js"></script>
    <link rel="stylesheet" href="css/adminbase.css">
</head>
<body>
    <h1>管理者ページ</h1>
    <div id="book_registration_open">本の新規登録</div>
    <div id="book_registration_modal" class="hidden">
        <form id ="book_registration" name="book_info" method="post" return false;>
            <p>本の名前 :<input type="text" name="book_name"></p>
            <p>　　著者 :<input type="text" name="book_auther"></p>
            <p>ジャンル :<input type="text" name="book_genre"></p>
            <input type="button" value="登録" onclick="registrationBook()">
        </form>
        <div class="close">
            Close
        </div>
    </div>
    <div id="user_registration_open">ユーザー登録</div>
    <div id="user_registration_modal" class="hidden">
        <form id ="user_registration" name="user_info" method="post" return false;>
            <p>　　 氏名 :<input type="text" name="user_name"></p>
            <p>chatwork :<input type="text" name="user_auther"></p>
            <input type="button" value="登録" onclick="registrationUser()">
        </form>
        <div class="close">
            Close
        </div>
    </div>
    <!--
    <button id="test">testuser</button>
    <button id="test2">testbook</button>


    <button id="del">del</button>
      -->
    <div id="mask" class="hidden"></div>
    <div class = "block">
            <div class="flex_column">
                <div>ユーザー一覧</div>
                <div id="user_hyoji"></div>
            </div>
            <div class="flex_column">
                <div>book一覧</div>
                <div id="book_hyoji"></div>
            </div>
    </div>
    <script src="js/post_ajax.js"></script>
</body>
</html>