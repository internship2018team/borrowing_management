<?php
require_once(dirname(__FILE__) ."/../server/config.php");
require_once(dirname(__FILE__) ."/../server/management.php");


$book = new \MyApp\Borrow_book();

//貸出し処理後にリロード挟む
if(isset($_POST["user_id"])){
    $book->BookBorrow($_POST["book_id"],$_POST["user_id"]);
    header(HEADER);
}elseif(isset($_POST["history_id"])){
    $book->BookReturn($_POST["history_id"]);
    header(HEADER);
}
if(isset($_POST["query"])){
    // 検索
    $book_status = $book->BookSearch($_POST["query"]);
}else {
    // 本のタイトル、book_id、貸し出し状況を取得
    $book_status = $book->getLatestBooks();
}
sort($book_status);
$book_status = json_decode(json_encode($book_status), true);
$user_status = $book->getUser();
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>本の貸出し管理</title>
        <link rel="stylesheet" href="css/styles.css">
    </head>
    <body>
        <h1 class="page_name">BOOK LIST</h1>
        <div id="search_text">
            <form action="index.php" method="POST">
                <input type="text" name="query" placeholder="検索">
            </form>
            <?php if(isset($_POST["query"])): ?>
                <?php echo "検索結果 : " . $_POST["query"] ?>
            <?php endif;?>
            <form>
                <input type="checkbox" name="state" checked>貸し出し可能
                <input type="checkbox" name="state" checked>貸し出し不可
            </form>
        </div>
        <div class="booklist">
            <table>
                <thead><tr><th>タイトル</th><th>借りている人</th><th>返却期限</th><th>貸し出しor返却</th></tr></thead>
                <tbody>
                    <!-- booklistの表示 -->
                    <?php foreach ($book_status as $book) : ?>
                    <tr>
                        <td id="title"><?php echo $book['title']; ?></td>
                        <!-- ステータスが貸出し可能かどうか -->
                        <!-- 貸し出し可能の場合 -->
                        <?php if($book['can_borrow']) : ?>
                        <td>-</td>
                        <td>-</td>
                        <td>
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
                        </td>
                        <!-- 貸し出し不可の場合 -->
                        <?php else : ?>
                        <form action="index.php" method="POST">
                            <input type="hidden" name="history_id" value="<?= $book['history_id']; ?>">
                            <?php $user_id = $book['user_id']; ?>
                            <?php foreach ($user_status as $user) : ?>
                                <?php if($user['id'] == $user_id) : ?>
                                    <?php $limit_date = new Datetime($book['borrow_date']); ?>
                                    <!-- 返却期限は２週間後 -->
                                    <?php $limit_date = $limit_date->modify('+2 weeks'); ?>
                                    <td><?php echo $user['name']; ?></td>
                                    <?php if($limit_date > new Datetime(date("Y/m/d"))) : ?>
                                        <td><?php echo $limit_date->format("Y/m/d(D)"); ?></td>
                                    <?php else:?>
                                        <td><font color='red'><?php echo $limit_date->format("Y/m/d(D)"); ?></font></td>
                                    <?php endif;?>
                                <?php endif;?>
                            <?php endforeach; ?>
                            <td><input type="submit" value="返却"></td>
                        </form>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>                
            </table>
        </div>
    </body>
</html>