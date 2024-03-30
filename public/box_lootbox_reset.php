<?php  // box_lootbox_reset.php
// https://dev2.m-fr.net/アカウント名/LootBox/box_lootbox_reset.php
require_once( __DIR__ . '/../libs/init_auth.php');

// BOXがちゃIDの把握
require_once( __DIR__ . '/../libs/box_lootbox_check.php');
//var_dump($box_lootbox_id);

// BOXがちゃに含まれるカードの取得
$sql = 'SELECT * FROM box_lootbox_decks_detail WHERE box_lootbox_deck_id=:box_lootbox_deck_id';
$pre = $dbh->prepare($sql);
//　値をバインド
$pre->bindValue(':box_lootbox_deck_id', $box_lootbox_id);
// SQLを実行
$r = $pre->execute();
// 値を取得
$deck_details = $pre->fetchAll(PDO::FETCH_ASSOC);
//var_dump($deck_details);

//
$cards = [];
foreach($deck_details as $d) {
    $cards[] = $d['card_id'];
}
// シャッフル
shuffle($cards);
//var_dump($cards);

//
//$json_string = json_encode($cards);
//var_dump($json_string);
//
$serialize_stirng = serialize($cards);
//var_dump($serialize_stirng);

// データを upsert(update + insert)
$sql = 'INSERT INTO box_lootbox_users(box_lootbox_deck_id, user_id, remaining_cards)
            VALUES(:box_lootbox_deck_id, :user_id, :remaining_cards)
              ON DUPLICATE KEY UPDATE
            remaining_cards = :remaining_cards_update
            ;';
$pre = $dbh->prepare($sql);
//　値をバインド
$pre->bindValue(':box_lootbox_deck_id', $box_lootbox_id);
$pre->bindValue(':user_id', $_SESSION['user']['auth']['user_id']);
$pre->bindValue(':remaining_cards', $serialize_stirng);
$pre->bindValue(':remaining_cards_update', $serialize_stirng);
// SQLを実行
$r = $pre->execute();
var_dump($r);










