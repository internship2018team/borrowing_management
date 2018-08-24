<?php
require_once(dirname(__FILE__) ."/../server/config.php");
require_once(dirname(__FILE__) ."/../server/management.php");


$book = new \MyApp\Borrow_book();

//貸出し処理後にリロード挟む
if(isset($_POST["user_id"])){
    $book->BookBorrow($_POST["book_id"],$_POST["user_id"]);
    header('Location:http://localhost:8080/borrowing_management/app/index.php');
}elseif(isset($_POST["history_id"])){
    $book->BookReturn($_POST["history_id"]);
    header('Location:http://localhost:8080/borrowing_management/app/index.php');
}



$book_status = $book->getLatestBooks();
$book_status = json_decode(json_encode($book_status), true);
// var_dump($book_status);
$user_status = $book->getUser();
// var_dump($user_status);
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
            <!-- booklistの表示 -->
            <?php foreach ($book_status as $book) : ?>
                <?php echo $book['title']; ?>
                <!-- ステータスが貸出し可能かどうか -->
                <!-- 貸し出し可能の場合 -->
                <?php if($book['can_borrow']) : ?>
                <form action="index.php" method="POST">
                    <!-- <input type="text" name="user_id" ><br/> -->
                    <select name="user_id">
                        <?php foreach ($user_status as $user) : ?>
                        <option value=<?php echo $user['id']; ?>><?php echo $user['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="hidden" name="book_id" value="<?= $book['book_id']; ?>">
                    <input type="submit" value="借りる">
                </form>
                <!-- 貸し出し不可の場合 -->
                <?php else : ?>
                <form action="index.php" method="POST">
                    <input type="hidden" name="history_id" value="<?= $book['history_id']; ?>">
                    <?php $user_id = $book['user_id']; ?>
                    <?php foreach ($user_status as $user) : ?>
                        <?php if($user['id'] == $user_id) : ?>
                            <?php $limit_date = new Datetime($book['borrow_date']); ?>
                            <?php $limitdate = $limit_date->modify('+2 weeks'); ?>
                            <?php echo "・借りてる人-> " . $user['name']; ?><br/>
                            <?php echo "・返却期限-> " . $limit_date->format("Y/m/d(D) H:i:s"); ?>
                        <?php endif;?>
                    <?php endforeach; ?>
                    <input type="submit" value="返却">
                </form>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </body>
</html>