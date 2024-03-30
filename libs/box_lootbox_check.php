<?php   // box_lootbox_check.php

// BOXがちゃIDの把握
$box_lootbox_id = intval($_GET['box_lootbox_id'] ?? 0);
if (0 >= $box_lootbox_id) {
    header('Location: ./top.php');
    exit;
}
