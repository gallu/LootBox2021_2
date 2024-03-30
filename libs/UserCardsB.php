<?php   // ~/LootBox2021/libs/UserCardsB.php

class UserCardsB {
    //
    public static function list($dbh) {
        // プリペアドステートメント
        $sql = 'SELECT * FROM user_cards_b WHERE user_id = :user_id ORDER BY card_id;';
        $pre = $dbh->prepare($sql);        
        // 値をバインド
        $pre->bindValue(':user_id', $_SESSION['user']['auth']['user_id']);
        // SQLを実行
        $r = $pre->execute();
        
        // データを取得
        $list = $pre->fetchAll(PDO::FETCH_ASSOC);
        return $list;
    }

    //
    public static function add($dbh, $card) {
        /* user_cards_b に upsert する */
        // プリペアドステートメント
        $sql = 'INSERT INTO user_cards_b(card_id, user_id, num, created_at, updated_at)
                       VALUES(:card_id, :user_id, :num, :created_at, :updated_at)
                   ON DUPLICATE KEY UPDATE
                       num = num + 1, updated_at = :updated_at_update
                ;';
        $pre = $dbh->prepare($sql);
//var_dump($pre);
        //
        $now = date('Y-m-d H:i:s');
        // 値のバインド
        $pre->bindValue(':card_id', $card['card_id']);
        $pre->bindValue(':user_id', $_SESSION['user']['auth']['user_id']);
        $pre->bindValue(':num', 1);
        $pre->bindValue(':created_at', $now);
        $pre->bindValue(':updated_at', $now);
        $pre->bindValue(':updated_at_update', $now);
        // SQLを実行
        $r = $pre->execute();
//var_dump($r);
    }
}