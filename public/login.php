<?php   // login.php

ob_start();
session_start();
require_once( __DIR__ . '/../libs/dbh.php');

// formからのデータの取得
$id = $_POST['id'] ?? '';
$pass = $_POST['pw'] ?? '';

// validate
if ( ('' === $id)or('' === $pass) ) {
    //
    $_SESSION['flash']['id'] = $id;
    $_SESSION['flash']['error'] = true;
    
    //
    header('Location: ./index.php');
    exit;
}

//
var_dump($id, $pass);

try {
    /* usersテーブルからデータを取得 */
    // プリペアドステートメントを作成
    $sql = 'SELECT * FROM users WHERE user_id = :user_id;';
    $pre = $dbh->prepare($sql);
    var_dump($pre);
    // 値をバインド
    $pre->bindValue(':user_id', $id);
    // SQLを実行
    $r = $pre->execute();
    var_dump($r);
    // データを取得
    $user = $pre->fetch(PDO::FETCH_ASSOC);
    var_dump($user);
    if (false === $user) {
        echo "ユーザIDまたはパスワードが違います\n";
        exit;
    }
} catch(Throwable $e) {
    // XXXX
    echo $e->getMessage();
    exit;
}

// パスワードの比較
$r = password_verify($pass, $user['password']);
var_dump($r);
if (false === $r) {
    echo "ユーザIDまたはパスワードが違います\n";
    exit;
}

/* ここまできたら認証OK　*/

// 認可設定
session_regenerate_id(true); // セッション固定攻撃用の対策

unset($user['password']);
$_SESSION['user']['auth'] = $user;

// ログイン後TopPageに遷移
header('Location: ./top.php');






