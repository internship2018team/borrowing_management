<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>管理者ページ</title>
    <script src="js/modal.js"></script>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div id="book_registration_open">
        本の新規登録
    </div>
    
    <div id="book_registration_modal" class="hidden">
        <form action="">
            <p>本の名前 :<input type="text" name="book_name"></p>
            <p>　　著者 :<input type="text" name="book_auther"></p>
            <p>ジャンル :<input type="text" name="book_auther"></p>
            <input type="submit" value="登録">
        </form>
        <div class="close">
            Close
        </div>
    </div>
 
    <div id="user_registration_open">
        ユーザー登録
    </div>
    <div id="user_registration_modal" class="hidden">
        <form name="user_info">
            <p>氏名 :<input type="text" name="book_name"></p>
            <p>チャットワークid :<input type="text" name="book_auther"></p>
            <input type="submit" value="登録" onclick="checkText()">
        </form>
        <div class="close">
            Close
        </div>
    </div>
    <button id="test">test</button>
    <div id="mask" class="hidden"></div>
    <script src="js/post_ajax.js"></script>
</body>
</html>