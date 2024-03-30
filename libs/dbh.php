<?php   // dbh.php
//
$dsn = 'mysql:host=localhost;dbname=lootbox_2021;charset=utf8mb4';
$user = 'lootbox_user';
$pass = 'loot_pass';
$options = [
    \PDO::ATTR_EMULATE_PREPARES => false,  // 静的プレースホルダにする
    \PDO::MYSQL_ATTR_MULTI_STATEMENTS => false, // 複文を禁止する
    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, // エラー時に例外を投げる
];
//
try {
    $dbh = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    echo "Error\n";
    echo $e->getMessage(); // 本来はlogとかに出力する
    exit;
}
//var_dump($dbh);
