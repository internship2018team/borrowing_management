<?php
require_once(__DIR__ . '/config.php');
require_once(__DIR__ . '/../sever/management.php');

try {
    // bbApp は book_borrowing_app の略 
    $bbApp = new \MyApp\Management;

    //貸出し返却のためのDB操作
    if(isset($_POST["user_id"])){
        // 貸し出し処理
        $bbApp->borrow_book();
    }elseif(isset($_POST["history_id"])){
        // 返却処理
        $bbApp->return_book();
    }

    // book_borrowing_appよりデータ取得
    // 本のタイトル、id、historyのid、貸し出し状況をget
    $book_status = $bbApp->get_latest_book_status();
    // usersテーブルの情報を取得
    $users = $bbApp->get_all_users();
} catch (\PDOException $e) {
    echo $e->getMessage();
    exit;
}



?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>本の貸出し管理</title>
    </head>
    <body>
        <h1>booklist</h1>
        <div class="booklist">
            <?php //　リストの表示予定 
            foreach ($book_status as $book):
                echo $book['title'];
                //ステータスが貸出し可能かどうか
                if($book['borrowable']){ 
                ?>
            <form action = "index.php" method = "POST">
                <!-- user_idを取得して貸し出し処理 -->
                <input type = "text" name = "user_id" ><br/>
                <input type = "hidden" name = "book_id" value = "<?= $book['book_id']; ?>">
                <input type = "submit" value = "借りる">
            </form>
            <?php } elseif($book['borrowable'] == 0){ ?>
            <form action = "index.php" method = "POST">
                <!-- history_idを取得して返却処理 -->
                <input type = "hidden" name = "history_id" value = "<?= $book['history_id']; ?>">
                <input type = "submit" value = "返却">
            </form>
            <?php }
            endforeach; ?>
        </div>
    </body>
</html>