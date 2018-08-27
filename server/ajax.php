<?php
require_once(dirname(__FILE__) ."/../server/config.php");
require_once(dirname(__FILE__) ."/../server/management.php");


$book = new \MyApp\Borrow_book();

if(isset($_POST['name'])){
    //ajax送信でPOSTされたデータを受け取る
    // 登録データ取得予定
    $user_name = $_POST['name'];
    $book->addUser($user_name);
    //受け取ったデータを判別してユーザーor本の登録をする

    //登録後に更新された一覧を取得して返す
    $book_status = $book->getLatestBooks();
    //ヘッダーの設定
    header('Content-type:application/json; charset=utf8');
    //「$return_array」をjson_encodeして出力
    //第二引数はUnicodeにエンコードしないため
    //echo json_encode($book_status, JSON_UNESCAPED_UNICODE);
    echo json_encode($user_name, JSON_UNESCAPED_UNICODE);
}