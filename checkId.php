<?php
/*
	중복 아이디 체크 페이지
*/
include_once('dbConnect.php');

$id = $_POST['id'];

// 현재 DB에 같은 아이디가 있는지 개수확인
$query = "select count(*) from member where mem_id='$id'"; 
$result = mysql_query($query); 
$data = mysql_fetch_array($result); 

if ( $data[0] > 0 ) $dup_id = 1; else $dup_id = 0; 

echo $dup_id;

mysql_close($connect);
?>