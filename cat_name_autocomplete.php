<?php
//connect with the database
include 'config.php';
   
    
    
//get search term
$searchTerm =  trim($_GET['term']);
    
$return_arr=array();
$query = $db->query("SELECT * FROM `categories_table` WHERE `cat_name` LIKE   '%".$searchTerm."%' ORDER BY `cat_name` ASC");
    while ($row = $query->fetch_assoc()) {
      // $data[] = $row['cat_name'];
	
	$data[] = array (
            'label' => $row['cat_name'].'   CAT'.str_pad($row['cat_id'], 3, "0", STR_PAD_LEFT),
		     'value' => $row['cat_id'],
        );
            
    }
    
    //return json data
    echo json_encode($data);
/*
//CREATE QUERY TO DB AND PUT RECEIVED DATA INTO ASSOCIATIVE ARRAY
if (isset($_REQUEST['query'])) {
    $query = $_REQUEST['query'];
    $sql = mysql_query ("SELECT * FROM `categories_table` WHERE `cat_name` LIKE  '%{$query}%' ORDER BY `cat_name` ASC");
	$array = array();
    while ($row = mysql_fetch_array($sql)) {
        $array[] = array (
            'label' => $row['cat_name'].', '.$row['cat_id'],
            'value' => $row['cat_id'],
        );
    }
    //RETURN JSON ARRAY
    echo json_encode ($array);
}
*/
?>