<?php   // ~/LootBox2021/libs/UserCardsA.php

class UserCardsA {
    //
    public static function list($dbh) {
        // プリペアドステートメント
        $sql = 'SELECT * FROM user_cards_a WHERE user_id = :user_id ORDER BY card_id;';
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
        /* user_cards_aにinsertする */
        // プリペアドステートメント
        $sql = 'INSERT INTO user_cards_a(card_id, user_id, created_at)
                       VALUES(:card_id, :user_id, :created_at);';
        $pre = $dbh->prepare($sql);
//var_dump($pre);

        // 値をバインド
//var_dump( $card['card_id'], $_SESSION['user']['auth']['user_id'] );
        $pre->bindValue(':card_id', $card['card_id']);
        $pre->bindValue(':user_id', $_SESSION['user']['auth']['user_id']);
        $pre->bindValue(':created_at', date('Y-m-d H:i:s'));

        // SQLを実行
        $r = $pre->execute();
//var_dump($r);
    }
}












