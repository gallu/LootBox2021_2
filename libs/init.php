<?php   // init.php
//
ob_start();
session_start();

//
function h($s) {
    return htmlspecialchars($s, ENT_QUOTES);
}

