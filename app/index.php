<?php
require_once(dirname(__FILE__) ."/../server/config.php");
require_once(dirname(__FILE__) ."/../server/management.php");

date_default_timezone_set('Asia/Tokyo');
$bookApp = new \MyApp\Borrow_book();

//貸出し処理後にリロード挟む
if (isset($_POST["borrow_book_id"], $_POST["user_id"])) {
    $bookApp->borrowBook($_POST["borrow_book_id"],$_POST["user_id"]);
    header(HEADER);
} elseif (isset($_POST["return_book_id"])) {
    $bookApp->returnBook($_POST["return_book_id"]);
    header(HEADER);
}
if (isset($_POST["search_query"])) {
    // 検索
    $books = $bookApp->searchBook($_POST["search_query"]);
} else {
    // 本のタイトル、book_id、貸し出し状況を取得
    $books = $bookApp->fetchLatestBooks();
}
sort($books);
$books = json_decode(json_encode($books), true);
$users = $bookApp->loadUsers();
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
        <a href="http://localhost:8080/app/index.php"><h1 class="page_name">BOOK LIST</h1></a>
        <div id="search_text">
            <form action="index.php" method="POST">
                <input type="text" name="search_query" placeholder="検索">
            </form>
            <?php if(isset($_POST["search_query"])): ?>
                <?php echo "検索結果 : 『" . $_POST["search_query"] . "』 " . count($books) . "件"?>
            <?php endif;?>
        </div>
        <div class="booklist">
            <input type="checkbox" id="is_borrowable" checked>貸し出し可能
            <input type="checkbox" id="isnot_borrowable" checked>貸し出し不可
            <table>
                <thead><tr><th>タイトル</th><th>借りている人</th><th>返却期限</th><th>貸し出しor返却</th></tr></thead>
                <tbody>
                    <!-- booklistの表示 -->
                    <?php foreach ($books as $book) : ?>
                        <!-- ステータスが貸出し可能かどうか -->
                        <!-- 貸し出し可能の場合 -->
                        <?php if($book['can_borrow'] || $book['can_borrow'] == NULL) : ?>
                        <tr class='can_borrow'>
                            <td class="title"><?php echo $book['title']; ?></td>
                            <td>-</td>
                            <td>-</td>
                            <td>
                            <form action="index.php" method="POST">
                                <!-- <input type="text" name="user_id" ><br/> -->
                                <select name="user_id">
                                    <?php foreach ($users as $user) : ?>
                                    <option value=<?php echo $user['id']; ?>><?php echo $user['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="hidden" name="borrow_book_id" value=<?= $book['book_id']; ?>>
                                <input type="submit" value="借りる">
                            </form>
                            </td>
                        </tr>
                        <!-- 貸し出し中の場合 -->
                        <?php else : ?>
                        <tr class="cannot_borrow">
                            <td class="title"><?php echo $book['title']; ?></td>
                            <form action="index.php" method="POST">
                                <input type="hidden" name="return_book_id" value=<?= $book['book_id']; ?>>
                                <?php $user_id = $book['user_id']; ?>
                                <?php foreach ($users as $user) : ?>
                                    <?php if ($user['id'] == $user_id) : ?>
                                        <?php $limit_date = new Datetime($book['borrow_date']); ?>
                                        <!-- 返却期限は２週間後 -->
                                        <?php $limit_date = $limit_date->modify('+2 weeks'); ?>
                                        <td><?php echo $user['name']; ?></td>
                                        <?php if ($limit_date > new Datetime(date("Y/m/d"))) : ?>
                                            <!-- JSTで表記 -->
                                            <td><?php echo $limit_date->modify('+9 hours')->format("Y/m/d(D)"); ?></td>
                                        <?php else :?>
                                            <td><font color='red'><?php echo $limit_date->modify('+9 hours')->format("Y/m/d(D)"); ?></font></td>
                                        <?php endif;?>
                                    <?php endif;?>
                                <?php endforeach; ?>
                                <td><input type="submit" value="返却"></td>
                            </form>
                        </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>                
            </table>
        </div>
    </body>
</html>