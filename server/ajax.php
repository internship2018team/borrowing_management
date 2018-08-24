<?php
require_once(dirname(__FILE__) ."/../server/config.php");
require_once(dirname(__FILE__) ."/../server/management.php");


$book = new \MyApp\Borrow_book();

if(isset($_POST['name'])){
    //ajax送信でPOSTされたデータを受け取る
    /* 登録データ取得予定
    $post_data_1 = $_POST['name'];
    $post_data_2 = $_POST['age'];
    */
    //受け取ったデータを判別してユーザーor本の登録をする

    //登録後に更新された一覧を取得して返す
    $book_status = $book->getLatestBooks();
    //ヘッダーの設定
    header('Content-type:application/json; charset=utf8');
    //「$return_array」をjson_encodeして出力
    //第二引数はUnicodeにエンコードしないため
    echo json_encode($book_status, JSON_UNESCAPED_UNICODE);
}