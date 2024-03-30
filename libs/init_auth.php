<?php   // init_auth.php
//
require_once( __DIR__ . '/init.php');

// 認可されていなければNG
if (false === isset($_SESSION['user']['auth'])) {
    header('Location: ./index.php');
    exit;
}

//
require_once( __DIR__ . '/dbh.php');
