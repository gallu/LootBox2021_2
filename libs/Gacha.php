<?php   // ~/LootBox2021/libs/Gacha.php

class Gacha {
    //
    public static function draw($card_list, $num) {
        // 「確率の合計」を把握する
        $probability_total = 0;
        foreach($card_list  as  $k => $v) {
            $probability_total += $v['probability'];
        }
//var_dump($probability_total);

        // 指定枚数、がちゃを引く
        $cards = [];
        for($j = 0; $j < $num; ++$j) {
            // 乱数を作る
            $i = random_int(0, $probability_total - 1);
    //var_dump($i);

            // 判定
            $probability = 0;
            foreach($card_list  as  $k => $v) {
                $probability += $v['probability'];
                if ($i < $probability) {
                    $cards[] = $v;
                    break;
                }
            }
        }

        //
        return $cards;
    }

}