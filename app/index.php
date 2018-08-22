<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);

$bookname = array ("harapeko","sannbiki","urashima","kakaka");
$books = array("harapeko" => 0,"sannbiki" => 0,"urashima" => 1,"kakaka" => 1);


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
            foreach ($bookname as $value){
                echo $value;
                //ステータスが貸出し可能かどうか
                if($books[$value] == 0){ 
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