<?php
require_once(dirname(__FILE__) ."/../server/config.php");
require_once(dirname(__FILE__) ."/../server/management.php");


$booklist = new \MyApp\Borrow_book();

/*
if(isset($_POST["user_id"])){
    $booklist->BookBorrow($_POST["book_id"],$_POST["user_id]");
}else{
}

*/


$book_status = $booklist->getLatestBooks();
$book_status = json_decode(json_encode($book_status), true);
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
            <form action = "index.php" method = "post">
                <input type = "text" name = "user_id" ><br/>
                <!--  $book['title'];はidをとることができるものに置き換える -->
                <input type = "hidden" name = "book_id" value = "<?= $book['book_id']; ?>">
                <input type = "submit" value = "借りる">
            </form>
            <?php  }elseif($book['borrowable'] == 0){ ?>
            <form action = "index.php" method = "post">
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