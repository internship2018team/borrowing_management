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
$users = $bookApp->loadAllUsers();
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
                        <?php if($book['can_borrow'] || $book['can_borrow'] == NULL) : ?>
                        <!-- 貸し出し可能の場合 -->
                            <tr class='can_borrow'>
                                <td class="title"><?php echo $book['title']; ?></td>
                                <td>-</td>
                                <td>-</td>
                                <td>
                                    <form action="index.php" method="POST">
                                        <select name="user_id">
                                            <?php foreach ($users as $user) : ?>
                                                <option value=<?php echo $user['id']; ?>><?php echo $user['name']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <input type="hidden" name="borrow_book_id" value=<?= $book['book_id']; ?>>
                                        <input type="submit" value="貸し出し">
                                    </form>
                                </td>
                            </tr>
                        <?php else : ?>
                        <!-- 貸し出し中の場合 -->
                            <tr class="cannot_borrow">
                                <td class="title"><?php echo $book['title']; ?></td>
                                <?php $key = array_search($book['user_id'], array_column($users, 'id'))?>
                                <?php if ($key === false) :?>
                                <!-- データベースにUserが存在しないときは'No Name'で表記 -->
                                    <td>No Name</td>
                                <?php else:?>
                                    <td><?php echo $users[$key]['name']; ?></td>
                                <?php endif;?>
                                <?php $limit_date = new Datetime($book['borrow_date']); ?>
                                <!-- 返却期限は借りた日から2週間後 -->
                                <?php $limit_date->modify('+2 weeks'); ?>
                                <?php $today = new Datetime(date("Y/m/d"))?>
                                <?php if ($limit_date < $today) : ?>
                                <!-- 返却期限を過ぎた -->
                                    <td bgcolor='#FF6257'><?php echo $limit_date->modify('+9 hours')->format("Y/m/d(D)"); ?></td>
                                <?php elseif ($limit_date < $today->modify('+1 days')) : ?>
                                <!-- 返却期限まで1日を切った -->
                                    <td bgcolor='#FCFF6D'><?php echo $limit_date->modify('+9 hours')->format("Y/m/d(D)"); ?></td>
                                <?php else :?>
                                <!-- 返却期限まで余裕がある -->
                                    <td><?php echo $limit_date->modify('+9 hours')->format("Y/m/d(D)"); ?></td>
                                <?php endif;?>    
                                <form action="index.php" method="POST">
                                    <input type="hidden" name="return_book_id" value=<?= $book['book_id']; ?>>
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