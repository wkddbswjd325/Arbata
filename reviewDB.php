<?php
/*
	상대에 대한 평가를 DB에 저장하는 페이지
	review.php 에서 넘어온다
*/
header("Content-Type: text/html; charset= UTF-8 ");
session_start();

extract($_REQUEST);
extract($_GET);
extract($_POST);
extract($_SERVER);
date_default_timezone_set("Asia/Seoul");

include_once('dbConnect.php');

$r_no = mysql_insert_id();
$r_writeId = $_SESSION['id'];
$r_recvId = $_POST['r_recvId'];
$r_radio = $_POST['r_radio'];
$r_star = $_POST['star'];
$r_content = $_POST['r_content'];
$r_date = DATE("Y-m-d");
$r_time = DATE("h:i:s");

$sql = "insert into review values('$r_no', '$r_writeId', '$r_recvId', '$r_radio', '$r_star', '$r_content', '$r_date', '$r_time')"; 
mysql_query($sql);
echo"<script>alert('후기가 정상적으로 등록되었습니다.');location.href='myPostList.php'; </script>";

mysql_close($connect);
?>