<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);

define('DB_DATABASE', 'book_borrowing_app');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'root');
define('PDO_DSN', 'mysql:host=mysql;dbname=' . DB_DATABASE);

try {
    // connect
    $db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // book_borrowing_appよりデータ取得
    // books table
    $stmt = $db->query("SELECT * FROM books");
    $books = $stmt->fetchALL(PDO::FETCH_ASSOC);
    // borrowing_histories table
    $sql_query = "SELECT * FROM borrowing_histories AS m WHERE NOT EXISTS (SELECT id FROM borrowing_histories AS s WHERE m.book_id = s.book_id AND m.date < s.date)";
    $stmt = $db->query($sql_query);
    // $stmt = $db->prepare("SELECT * FROM borrowing_histories");
    $b_histories = $stmt->fetchALL(PDO::FETCH_ASSOC);
    // users table
    // $stmt = $db->query("select * from users");
    // $users = $stmt->fetchALL(PDO::FETCH_ASSOC);

    foreach ($b_histories as $history) {
        var_dump($history);
        // echo $history['book_id'] . "\n";
    }

} catch (PDOException $e) {
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
            foreach ($books as $book){
                echo $book['title'];
                //ステータスが貸出し可能かどうか
                if($book[$value] == 0){ 
                ?>
            <form action = "management.php" method = "get">
                <input type = "text" name = "username" ><br/>
                <input type = "submit" value = "借りる">
            </form>
            <?php  }else if($books[$value] == 1){ ?>
            <form action = "management.php" method = "get">
                <input type = "submit" value ="返却">
            </form>
            <?php  } 
            } ?>
        </div>
    </body>
</html>