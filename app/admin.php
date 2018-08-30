<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>管理者ページ</title>
    <script src="js/modal.js"></script>
    <link rel="stylesheet" href="https://storage.googleapis.com/code.getmdl.io/1.0.0/material.indigo-pink.min.css">
    <script src="https://storage.googleapis.com/code.getmdl.io/1.0.0/material.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="css/adminbase.css">
</head>
<body>
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
  <header class="mdl-layout__header">
    <div class="mdl-layout__header-row">
      <span class="mdl-layout-title">管理者ページ</span>
    </div>
    <div class="mdl-layout__tab-bar mdl-js-ripple-effect">
      <div id="book_registration_open" class="mdl-layout__tab mdl-button mdl-js-button mdl-js-ripple-effect">本の新規登録</div>
      <div id="user_registration_open" class="mdl-layout__tab mdl-button mdl-js-button mdl-js-ripple-effect">ユーザー登録</div>
    </div>
  </header>
  <div class="mdl-layout__drawer">
    <span class="mdl-layout-title">本の貸出管理アプリ</span>
    <nav class="mdl-navigation">
      <a class="mdl-navigation__link" href="./index.php">貸出しページ</a>
      <a class="mdl-navigation__link" href="./admin.php">管理者ページ</a>
    </nav>
  </div>
    <div id="book_registration_modal" class="hidden">
        <form id ="book_registration" name="book_info" method="post" return false;>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input type="text" name="book_name" id="3"class="mdl-textfield__input">
                <label class="mdl-textfield__label" for="3">本のタイトル...</label>
            </div>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input type="text" name="book_auther" id="4"class="mdl-textfield__input">
                <label class="mdl-textfield__label" for="4">著者...</label>
            </div>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input type="text" name="book_genre" id="5"class="mdl-textfield__input">
                <label class="mdl-textfield__label" for="5">ジャンル...</label>
            </div>
            <input type="button" value="登録" onclick="registerBook()">
        </form>
        <div class="close mdl-button mdl-js-button mdl-js-ripple-effect">
            Close
        </div>
    </div>
    <div id="user_registration_modal" class="hidden">
        <form id ="user_registration" name="user_info" action="#" method="post" return false;>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input type="text" name="user_name" id="1" class="mdl-textfield__input">
                <label class="mdl-textfield__label" for="1">氏名...</label>
            </div>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input type="text" name="user_chat" id="2" class="mdl-textfield__input">
                <label class="mdl-textfield__label" for="2">chatwork_id</label>
            </div>
            <input type="button" value="登録" onclick="registerUser()">
        </form>
        <div class="close mdl-button mdl-js-button mdl-js-ripple-effect">
            Close
        </div>
    </div>
    <div id="mask" class="hidden"></div>
    <div class = "block">
        <div class="article_column">
            <div>book一覧</div>
            <table id="book_hyoji" class="flex_column mdl-data-table mdl-js-data-table mdl-data-table--selectable mdl-shadow--2dp"></table>
        </div>
        <div class="article_column">
            <div>ユーザ一覧</div>
            <table id="user_hyoji" class="flex_column mdl-data-table mdl-js-data-table mdl-data-table--selectable mdl-shadow--2dp"></table>
        </div>
    </div>
</div>
    <script src="js/post_ajax.js"></script>
</body>
</html>