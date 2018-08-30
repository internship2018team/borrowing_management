<?php
require_once(dirname(__FILE__) ."/../server/config.php");
require_once(dirname(__FILE__) ."/../server/management.php");

date_default_timezone_set('Asia/Tokyo');
$bookApp = new \MyApp\Borrow_book();

//貸出し処理後にリロード挟む
if (isset($_POST["borrow_book_id"], $_POST["user_id"]) && !empty($_POST["user_id"])) {
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
    // 本のタイトル、book_id、貸出し状況を取得
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
        <script src="js/display_script.js"></script>
        <style>
            .demo-layout-waterfall .mdl-layout__header-row .mdl-navigation__link:last-of-type  {
            padding-right: 0;
        }
        </style>
    </head>
    <body>
        <!-- Uses a header that contracts as the page scrolls down. -->
        <div class="demo-layout-waterfall mdl-layout mdl-js-layout">
        <header class="mdl-layout__header mdl-layout__header--waterfall">
            <!-- Top row, always visible -->
            <div class="mdl-layout__header-row">
            <!-- Title -->
            <span class="mdl-layout-title">貸出しページ</span>
            <div class="mdl-layout-spacer"></div>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--expandable
                        mdl-textfield--floating-label mdl-textfield--align-right">
                <label class="mdl-button mdl-js-button mdl-button--icon"
                    for="waterfall-exp">
                <i class="material-icons">search</i>
                </label>
                <div class="mdl-textfield__expandable-holder">
                    <form action="index.php" method="POST">
                        <input type="text" name="search_query" autocomplete="off" class="mdl-textfield__input" id="waterfall-exp">
                    </form>
                </div>
                <div>
                    <?php if(isset($_POST["search_query"])): ?>
                        <?php echo "『" . $_POST["search_query"] . "』 " . count($books) . "件"?>
                    <?php endif;?>
                </div>
            </div>
            </div>
            <!-- Bottom row, not visible on scroll -->
            <!-- <div class="mdl-layout__header-row">
            <div class="mdl-layout-spacer"></div>
            <nav class="mdl-navigation">
            </nav>
            </div> -->
        </header>
        <div class="mdl-layout__drawer">
            <span class="mdl-layout-title">本の貸出管理アプリ</span>
            <nav class="mdl-navigation">
            <a class="mdl-navigation__link" href="./index.php">貸出しページ</a>
            <a class="mdl-navigation__link" href="./admin.php">管理者ページ</a>
            </nav>
        </div>
            <main class="mdl-layout__content">
                <div class="page-content">
                    <div class="booklist">
                        <div id="display_switching">
                            <label class="mdl-switch mdl-js-switch" for="is_borrowable">
                                <input type="checkbox" id="is_borrowable" class="mdl-switch__input" checked>
                                <span class="mdl-switch__label">貸出し可能</span>
                            </label>
                            <label class="mdl-switch mdl-js-switch" for="isnot_borrowable">
                                <input type="checkbox" id="isnot_borrowable" class="mdl-switch__input" checked>
                                <span class="mdl-switch__label">貸出し中</span>
                            </label>
                        </div>
                        <table class="mdl-data-table">
                            <thead>
                                <tr>
                                    <th class="mdl-data-table__cell--non-numeric">タイトル</th>
                                    <th class="mdl-data-table__cell--non-numeric">借りている人</th>
                                    <th class="mdl-data-table__cell--non-numeric">返却期限</th>
                                    <th class="mdl-data-table__cell--non-numeric">貸出し/返却</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- booklistの表示 -->
                                <?php foreach ($books as $book) : ?>
                                    <?php if($book['can_borrow']) : ?>
                                    <!-- 貸出し可能の場合 -->
                                        <tr class='can_borrow'>
                                            <td class="mdl-data-table__cell--non-numeric title"><?php echo $book['title']; ?></td>
                                            <form action="index.php" method="POST">
                                                <td class="mdl-data-table__cell--non-numeric">
                                                    <select name="user_id" class="mdl-textfield__input">
                                                        <!-- 名前が表示されていると見辛い -->
                                                        <option value=<?php echo NULL; ?>>-</option>
                                                        <?php foreach ($users as $user) : ?>
                                                            <option value=<?php echo $user['id']; ?>><?php echo $user['name']; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>                                    
                                                </td>
                                                <td class="mdl-data-table__cell--non-numeric">-</td>
                                                <td class="mdl-data-table__cell--non-numeric">
                                                    <input type="hidden" name="borrow_book_id" value=<?= $book['book_id']; ?>>
                                                    <input type="submit" value="貸出し" class="mdl-button mdl-js-button borrow_button">
                                                </td>
                                            </form>                                
                                        </tr>
                                    <?php else : ?>
                                    <!-- 貸出し中の場合 -->
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
                </div>
            </main>
        </div>
    </body>
</html>