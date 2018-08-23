<?php
require_once(__DIR__ . '/config.php');


try {
    // connect
    $db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // book_borrowing_appよりデータ取得
    // 本のタイトル、id、historyのid、貸し出し状況をget
    $sql_query = 
    "SELECT
        title,
        books.id AS book_id,
        bh.id AS history_id,
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

    // usersテーブルの情報を取得
    $sql_query = "SELECT * FROM users";
    $stmt = $db->query($sql_query);
    $users = $stmt->fetchALL(PDO::FETCH_ASSOC);

    // foreach ($users as $user) {
    //     var_dump($user);
    // }

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
    $stmt = $db->prepare("INSERT INTO borrowing_histories (book_id, user_id, borrowable) VALUES (:book_id, :user_id, :borrowable)");
    $stmt->execute([
        ':book_id' => $_GET["book_id"],
        ':user_id' => $_GET["user_id"],
        ':borrowable' => 0]);
    $db->commit();
  }else{
    $db->beginTransaction();
    // ここにborrow_historiesのupdateを書く
    $stmt = $db->prepare("UPDATE borrowing_histories SET borrowable = 1 WHERE id = :history_id");
    $stmt->execute([':history_id' => $_GET["history_id"]]);
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
                <input type = "hidden" name = "book_id" value = "<?= $book['book_id']; ?>">
                <input type = "submit" value = "借りる">
            </form>
            <?php  }elseif($book['borrowable'] == 0){ ?>
            <form action = "index.php" method = "get">
                <!--  $book['title'];はidをとることができるものに置き換える -->
                <!-- borrowing_historiesのidの方が更新処理にはいい -->
                <input type = "hidden" name = "history_id" value = "<?= $book['history_id']; ?>">
                <input type = "submit" value = "返却">
            </form>
            <?php  } 
            } ?>
        </div>
    </body>
</html>