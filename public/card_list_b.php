<?php   // card_list_b.php

require_once( __DIR__ . '/../libs/init_auth.php');
require_once( __DIR__ . '/../libs/UserCardsB.php');

//
$list = UserCardsB::list($dbh);

var_dump($list);
