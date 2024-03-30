<?php   // box_loot_box.php
// https://dev2.m-fr.net/アカウント名/LootBox/box_loot_box.php
require_once( __DIR__ . '/../libs/init_auth.php');

// BOXがちゃIDの把握
require_once( __DIR__ . '/../libs/box_lootbox_check.php');

// BOXがちゃ情報の取得
$sql = 'SELECT * FROM box_lootbox_decks WHERE box_lootbox_deck_id=:box_lootbox_deck_id';
$pre = $dbh->prepare($sql);
//　値をバインド
$pre->bindValue(':box_lootbox_deck_id', $box_lootbox_id);
// SQLを実行
$r = $pre->execute();
// 値を取得
$decks = $pre->fetch(PDO::FETCH_ASSOC);
//var_dump($decks);
if (false === $decks) {
    header('Location: ./top.php');
    exit;
}

// BOXがちゃに含まれるカード枚数の取得
$sql = 'SELECT count(*) as count FROM box_lootbox_decks_detail WHERE box_lootbox_deck_id=:box_lootbox_deck_id';
$pre = $dbh->prepare($sql);
//　値をバインド
$pre->bindValue(':box_lootbox_deck_id', $box_lootbox_id);
// SQLを実行
$r = $pre->execute();
// 値を取得
$deck_details = $pre->fetch(PDO::FETCH_ASSOC);
//var_dump($deck_details);

// ログインユーザの現在のBOXがちゃの状況を取得
$sql = 'SELECT * FROM box_lootbox_users WHERE box_lootbox_deck_id=:box_lootbox_deck_id AND user_id = :user_id';
$pre = $dbh->prepare($sql);
//　値をバインド
$pre->bindValue(':box_lootbox_deck_id', $box_lootbox_id);
$pre->bindValue(':user_id', $_SESSION['user']['auth']['user_id']);
// SQLを実行
$r = $pre->execute();
// 値を取得
$remaining = $pre->fetch(PDO::FETCH_ASSOC);
//var_dump($remaining['remaining_cards']);
if (false === $remaining) {
    $remaining_count = 0;
} else {
    $awk = unserialize($remaining['remaining_cards']);
//var_dump($awk);
    $remaining_count = count($awk);
}
?>

BOXがちゃ名:<?php echo h($decks['box_lootbox_name']); ?><br>
残枚数: <?php echo h($remaining_count); ?> / <?php echo $deck_details['count']; ?> <br>
<hr>
・<a href="./box_lootbox_draw.php?box_lootbox_id=<?php echo rawurlencode($box_lootbox_id); ?>">BOXがちゃを引く</a><br>
・<a href="./box_lootbox_reset.php?box_lootbox_id=<?php echo rawurlencode($box_lootbox_id); ?>">BOXがちゃをリセットする</a><br>







