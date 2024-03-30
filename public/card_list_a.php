<?php   // card_list_a.php

require_once( __DIR__ . '/../libs/init_auth.php');
require_once( __DIR__ . '/../libs/UserCardsA.php');

//
$list = UserCardsA::list($dbh);

var_dump($list);
