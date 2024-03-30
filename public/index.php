<?php   // index.php
//  https://dev2.m-fr.net/アカウント名/LootBox/
//  https://dev2.m-fr.net/アカウント名/LootBox/index.php

//
require_once( __DIR__ . '/../libs/init.php');

//
$flash_session = $_SESSION['flash'] ?? [];
unset($_SESSION['flash']);
//var_dump($_SESSION);

?>
<?php
if (true === ($flash_session['error'] ?? false)) {
    echo "なんかエラーだったよ？<br>\n";
}
?>
<form action="./login.php" method="post">
ユーザID: <input name="id" value="<?php echo h($flash_session['id'] ?? ''); ?>"><br>
パスワード: <input name="pw" type="password"><br>
<br>
<button>ログインする</button>
</form>
