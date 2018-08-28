<?php
require_once(dirname(__FILE__) ."/../server/config.php");
require_once(dirname(__FILE__) ."/../server/management.php");


$book = new \MyApp\Borrow_book();

if(isset($_POST['name'])){
    //ajax送信でPOSTされたデータを受け取る
    // 登録データ取得予定
    $user_name = $_POST['name'];
    $book->addUser($user_name);
    //登録後に更新された一覧を取得して返す
    $user_lists = $book->getUser();
    //ヘッダーの設定
    header('Content-type:application/json; charset=utf8');
    //第二引数はUnicodeにエンコードしないため
    echo json_encode($user_lists, JSON_UNESCAPED_UNICODE);
}

if(isset($_POST['title'])){
    //ajax送信でPOSTされたデータを受け取る
    // 登録データ取得予定
    $book_name = $_POST['title'];
    $book->addBook($book_name);
    
    $book_status = $book->getBooks();
    header('Content-type:application/json; charset=utf8');

    //第二引数はUnicodeにエンコードしないため
    echo json_encode($book_status, JSON_UNESCAPED_UNICODE);
}

if(isset($_POST['push'])){
    $booklists = $book->getBooks();
    header('Content-type:application/json; charset=utf8');
    echo json_encode($booklists, JSON_UNESCAPED_UNICODE);
}

if(isset($_POST['push2'])){
    $user_lists = $book->getUser();
    header('Content-type:application/json; charset=utf8');
    echo json_encode($user_lists, JSON_UNESCAPED_UNICODE);
}

if(isset($_POST['push3'])){
    $booklists = $book->getBooks();
    $user_lists = $book->getUser();
    $sum = array_merge($booklists,$user_lists);
    header('Content-type:application/json; charset=utf8');
    echo json_encode($sum, JSON_UNESCAPED_UNICODE);
}

if(isset($_POST['del_user_id'])){
    $user_id = $_POST['del_user_id'];
    $book->deleteUser($user_id);
    $user_lists = $book->getUser();
    header('Content-type:application/json; charset=utf8');
    echo json_encode($user_lists, JSON_UNESCAPED_UNICODE);
}

if(isset($_POST['del_book_id'])){
    $book_id = $_POST['del_book_id'];
    $book->deleteBook($book_id);
    $booklists = $book->getBooks();
    header('Content-type:application/json; charset=utf8');
    echo json_encode($booklists, JSON_UNESCAPED_UNICODE);
}