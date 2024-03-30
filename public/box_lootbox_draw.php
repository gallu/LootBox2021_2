<?php   // box_lootbox_draw.php
require_once( __DIR__ . '/../libs/init_auth.php');
require_once( __DIR__ . '/../libs/UserCardsA.php');
require_once( __DIR__ . '/../libs/UserCardsB.php');

// BOXがちゃIDの把握
require_once( __DIR__ . '/../libs/box_lootbox_check.php');
//var_dump($box_lootbox_id);

try {
    // トランザクション開始
    $dbh->beginTransaction();

    // 「ユーザのBOXがちゃ状況用テーブル」から情報を取得
    $sql = 'SELECT * FROM box_lootbox_users
             WHERE box_lootbox_deck_id=:box_lootbox_deck_id AND user_id = :user_id
            FOR UPDATE;';
    $pre = $dbh->prepare($sql);
    //　値をバインド
    $pre->bindValue(':box_lootbox_deck_id', $box_lootbox_id);
    $pre->bindValue(':user_id', $_SESSION['user']['auth']['user_id']);
    // SQLを実行
    $r = $pre->execute();
    // 値を取得
    $remaining = $pre->fetch(PDO::FETCH_ASSOC);
    if (false === $remaining) {
        header('./top.php');
        exit;
    } else {
        $box_cards = unserialize($remaining['remaining_cards']);
        if ([] === $box_cards) {
            header('./top.php');
            exit;
        }
    }
var_dump($box_cards);

    // boxから1枚、カードを入手
    $card_id = array_shift($box_cards);
var_dump($card_id, $box_cards);
    // カードを所有させる
    UserCardsA::add($dbh, ['card_id' => $card_id]);
    UserCardsB::add($dbh, ['card_id' => $card_id]);

    // 「ユーザのBOXがちゃ状況用テーブル」に情報を書き込み
    $sql = 'UPDATE box_lootbox_users SET remaining_cards = :remaining_cards
             WHERE box_lootbox_deck_id=:box_lootbox_deck_id AND user_id = :user_id;';
    $pre = $dbh->prepare($sql);
    //　値をバインド
    $pre->bindValue(':box_lootbox_deck_id', $box_lootbox_id);
    $pre->bindValue(':user_id', $_SESSION['user']['auth']['user_id']);
    $pre->bindValue(':remaining_cards', serialize($box_cards));
    // SQLを実行
    $r = $pre->execute();

    // トランザクション終了
    $dbh->commit();
} catch(Throwable $e) {
    // トランザクション異常終了
    $dbh->rollBack();
    // XXXX
    echo $e->getMessage();
    exit;
}






