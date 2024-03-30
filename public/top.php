<?php   // top.php
// https://dev2.m-fr.net/アカウント名/LootBox/top.php
require_once( __DIR__ . '/../libs/init_auth.php');

/* ユーザコイン数の把握 */
// プリペアドステートメント
$sql = 'SELECT * FROM users WHERE user_id = :user_id;';
$pre = $dbh->prepare($sql);
//　値をバインド
$pre->bindValue(':user_id', $_SESSION['user']['auth']['user_id']);
// SQLを実行
$r = $pre->execute();
// 値を取得
$user = $pre->fetch(PDO::FETCH_ASSOC);
$coin = $user['coin'];
//var_dump($coin);

?>
<h1>がちゃ　ログイン後TopPage</h1>

<a href="./card_list_a.php">所持カード一覧(a)</a><br>
<a href="./card_list_b.php">所持カード一覧(b)</a><br>

<hr>
所持コイン: <?php echo h(strval($coin)); ?> 枚 <br>

<ul>
  <li><a href="./draw_loot_box.php?lootbox_id=12">がちゃ（無料）</a>
  <li><a href="./draw_loot_box.php?lootbox_id=18">がちゃ（有料）</a>
  <li><a href="./draw_loot_box.php?lootbox_id=13">１１連がちゃ（無料）</a>
  <li>１１連がちゃ（有料）
  <li><a href="./box_loot_box.php?box_lootbox_id=1">Boxがちゃを引く</a>
  <li>デイリーがちゃ
</ul>
