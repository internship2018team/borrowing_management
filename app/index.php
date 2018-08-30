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
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
        <script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>
    </head>
    <body>
        <!-- ここに追加！ -->
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
            <input type="checkbox" id="is_borrowable" class="mdl-checkbox__input" checked>
            <label for="is_borrowable">貸し出し可能</label><br>
            <input type="checkbox" id="isnot_borrowable" class="mdl-checkbox__input" checked>
            <label for="isnot_borrowable">貸し出し中</label>
            <table class="mdl-data-table mdl-js-data-table">
                <thead>
                    <tr>
                        <th class="mdl-data-table__cell--non-numeric">タイトル</th>
                        <th class="mdl-data-table__cell--non-numeric">借りている人</th>
                        <th class="mdl-data-table__cell--non-numeric">返却期限</th>
                        <th class="mdl-data-table__cell--non-numeric">貸し出しor返却</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- booklistの表示 -->
                    <?php foreach ($books as $book) : ?>
                        <?php if($book['can_borrow']) : ?>
                        <!-- 貸し出し可能の場合 -->
                            <tr class='can_borrow'>
                                <td class="mdl-data-table__cell--non-numeric title"><?php echo $book['title']; ?></td>
                                <form action="index.php" method="POST">
                                    <td class="mdl-data-table__cell--non-numeric">
                                        <select name="user_id" class="mdl-textfield__input">
                                            <!-- 名前が表示されていると見辛い -->
                                            <?php foreach ($users as $user) : ?>
                                                <option value=<?php echo $user['id']; ?>><?php echo $user['name']; ?></option>
                                            <?php endforeach; ?>
                                        </select>                                    
                                    </td>
                                    <td class="mdl-data-table__cell--non-numeric">-</td>
                                    <td class="mdl-data-table__cell--non-numeric">
                                        <input type="hidden" name="borrow_book_id" value=<?= $book['book_id']; ?>>
                                        <input type="submit" value="貸し出し" class="mdl-button mdl-js-button mdl-button--colored">
                                    </td>
                                </form>                                
                            </tr>
                        <?php else : ?>
                        <!-- 貸し出し中の場合 -->
                            <tr class="cannot_borrow">
                                <td class="mdl-data-table__cell--non-numeric title"><?php echo $book['title']; ?></td>
                                <?php $key = array_search($book['user_id'], array_column($users, 'id'))?>
                                <?php if ($key === false) :?>
                                <!-- データベースにUserが存在しないときは'No Name'で表記 -->
                                    <td class="mdl-data-table__cell--non-numeric">No Name</td>
                                <?php else:?>
                                    <td class="mdl-data-table__cell--non-numeric"><?php echo $users[$key]['name']; ?></td>
                                <?php endif;?>
                                <?php $limit_date = new Datetime($book['borrow_date']); ?>
                                <!-- 返却期限は借りた日から2週間後 -->
                                <?php $limit_date->modify('+2 weeks'); ?>
                                <?php $today = new Datetime(date("Y/m/d"))?>
                                <?php if ($limit_date < $today) : ?>
                                <!-- 返却期限を過ぎた -->
                                    <td class="mdl-data-table__cell--non-numeric" bgcolor='#FF6257'><?php echo $limit_date->modify('+9 hours')->format("Y/m/d(D)"); ?></td>
                                <?php elseif ($limit_date < $today->modify('+1 days')) : ?>
                                <!-- 返却期限まで1日を切った -->
                                    <td class="mdl-data-table__cell--non-numeric" bgcolor='#FCFF6D'><?php echo $limit_date->modify('+9 hours')->format("Y/m/d(D)"); ?></td>
                                <?php else :?>
                                <!-- 返却期限まで余裕がある -->
                                    <td class="mdl-data-table__cell--non-numeric"><?php echo $limit_date->modify('+9 hours')->format("Y/m/d(D)"); ?></td>
                                <?php endif;?>    
                                <form action="index.php" method="POST">
                                    <input type="hidden" name="return_book_id" value=<?= $book['book_id']; ?>>
                                    <td class="mdl-data-table__cell--non-numeric"><input type="submit" value="返却" class="mdl-button mdl-js-button"></td>
                                </form>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </body>
</html>