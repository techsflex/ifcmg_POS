<?php

include 'config.php';

$db = $conn;
$return_arr =array();
$query = $db->query("SELECT * FROM `categories_table` ORDER BY `cat_name` ASC");



?>