<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);
//自分のDBNAMEに変更するのを忘れない
define('DB_DATABASE', 'book_management');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'root');
define('PDO_DSN', 'mysql:host=mysql;dbname=' . DB_DATABASE);