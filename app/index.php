<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);
//自分のDBNAMEに変更するのを忘れない
define('DB_DATABASE', 'book_borrowing_app');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'root');
define('PDO_DSN', 'mysql:host=mysql;dbname=' . DB_DATABASE);

try {
    // connect
    $db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // book_borrowing_appよりデータ取得
    $sql_query = 
    "SELECT
        title,
        CASE
        WHEN borrowable IS NULL THEN 1
        ELSE borrowable
        END AS borrowable
    FROM (
        SELECT *
        FROM borrowing_histories AS m
        WHERE NOT EXISTS (
            SELECT id
            FROM borrowing_histories AS s
            WHERE m.book_id = s.book_id
            AND m.date < s.date
        ) )AS bh
    RIGHT JOIN books 
    ON bh.book_id = books.id";
    $stmt = $db->query($sql_query);
    $book_status = $stmt->fetchALL(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo $e->getMessage();
    exit;
}
?>

<?php
//貸出し返却のためのDB操作
try {
  if(isset($_GET["user_id"])){
    $db->beginTransaction();
    // ここにborrow_historiesのinsertを書く

    $db->commit();
  }else{
    $db->beginTransaction();
    // ここにborrow_historiesのupdateを書く
  
    $db->commit();
  }

}catch(PDOException $e){
    $db->rollback();
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
            foreach ($book_status as $book){
                echo $book['title'];
                //ステータスが貸出し可能かどうか
                if($book['borrowable'] == 1){ 
                ?>
            <form action = "index.php" method = "get">
                <input type = "text" name = "user_id" ><br/>
                <!--  $book['title'];はidをとることができるものに置き換える -->
                <input type = "hidden" name = "book_id" value = "<?= $book['title']; ?>">
                <input type = "submit" value = "借りる">
            </form>
            <?php  }elseif($book['borrowable'] == 0){ ?>
            <form action = "index.php" method = "get">
                <!--  $book['title'];はidをとることができるものに置き換える -->
                <input type = "hidden" name = "book_id" value = "<?= $book['title']; ?>">
                <input type = "submit" value = "返却">
            </form>
            <?php  } 
            } ?>
        </div>
    </body>
</html>