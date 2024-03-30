<?php   // draw_loot_box.php
require_once( __DIR__ . '/../libs/init_auth.php');
require_once( __DIR__ . '/../libs/Gacha.php');
require_once( __DIR__ . '/../libs/UserCardsA.php');
require_once( __DIR__ . '/../libs/UserCardsB.php');

/* がちゃデッキを特定する */
// がちゃIDをURLから取得する
$lootbox_id = intval($_GET['lootbox_id'] ?? 0);
var_dump($lootbox_id);
// validate
if (0 >= $lootbox_id) {
    //
    echo 'ねぇよ';
    exit;
}
/* DBから情報を取得する */
try {
    // プリペアドステートメント
    $sql = 'SELECT * FROM lootbox_decks WHERE lootbox_deck_id = :lootbox_id;';
    $pre = $dbh->prepare($sql);
    //　値をバインド
    $pre->bindValue(':lootbox_id', $lootbox_id, \PDO::PARAM_INT);
    // SQLを実行
    $r = $pre->execute();
    // 値を取得
    $lootbox_deck = $pre->fetch(PDO::FETCH_ASSOC);
    if (false === $lootbox_deck) {
        // XXX
        echo 'なかったよ？';
        exit;
    }

    // がちゃ詳細を取得
    // プリペアドステートメント
    $sql = 'SELECT * FROM lootbox_decks_detail
　　　　　　　    WHERE lootbox_deck_id = :lootbox_id
             ORDER BY probability DESC;';
    $pre = $dbh->prepare($sql);
    //　値をバインド
    $pre->bindValue(':lootbox_id', $lootbox_id, \PDO::PARAM_INT);
    // SQLを実行
    $r = $pre->execute();
    // 値を取得
    $lootbox_deck_details = $pre->fetchAll(PDO::FETCH_ASSOC);
} catch(Throwable $e) {
    // XXXX
    echo $e->getMessage();
    exit;
}
//var_dump($lootbox_deck, $lootbox_deck_details);

// がちゃを引く
$cards = Gacha::draw($lootbox_deck_details, $lootbox_deck['draw_num']);

try {
    // トランザクション開始
    $dbh->beginTransaction();

    // 有償がちゃの確認
    if ('' !== $lootbox_deck['cost']) {
echo "有償だよ!! \n";
        //
        $deck_cost = intval($lootbox_deck['cost']);

        // XXX　ユーザコインの確認
        // プリペアドステートメント
        $sql = 'SELECT * FROM users WHERE user_id = :user_id  FOR UPDATE ;';
        $pre = $dbh->prepare($sql);
        //　値をバインド
        $pre->bindValue(':user_id', $_SESSION['user']['auth']['user_id']);
        // SQLを実行
        $r = $pre->execute();
        // 値を取得
        $user = $pre->fetch(PDO::FETCH_ASSOC);
        $coin = $user['coin'];
var_dump($coin);

        // お金、足りてる？
        if ($deck_cost > $coin) {
            throw new \Exception('お金足りない...');
        }
        
        //　ユーザコインの減額
        // プリペアドステートメント
        $sql = 'UPDATE users SET coin = coin - :deck_cost WHERE user_id = :user_id;';
        $pre = $dbh->prepare($sql);
        //　値をバインド
        $pre->bindValue(':user_id', $_SESSION['user']['auth']['user_id']);
        $pre->bindValue(':deck_cost', $deck_cost);
        // SQLを実行
        $r = $pre->execute();
    }

    // ログインユーザにカードを所有させる
    foreach($cards as $card) {
        UserCardsA::add($dbh, $card);
        UserCardsB::add($dbh, $card);
    }

    // トランザクション終了
    $dbh->commit();

} catch(Throwable $e) {
    // トランザクション異常終了
    $dbh->rollBack();
    // XXXX
    echo $e->getMessage();
    exit;
}

// 完了画面に遷移する
var_dump($cards);







