<?php
/*
	로그인 확인하는 페이지
	아이디와 패스워드가 일치하는지 확인
*/
session_start();

include_once('dbConnect.php');

if(!isset($_POST['mem_id']) || !isset($_POST['mem_pwd'])) exit;
$mem_id = $_POST['mem_id'];
$mem_pwd = $_POST['mem_pwd'];

$query = "select count(*) from member where mem_id='$mem_id' and mem_pwd='$mem_pwd'"; 
$result = mysql_query($query); 
$data = mysql_fetch_array($result); 

if($data[0] == 0) {
	echo "<script>alert('아이디 또는 패스워드가 잘못되었습니다.');history.go(-1);</script>";
	exit;
}

$_SESSION['id'] = $mem_id;
$_SESSION['passwd'] = $mem_pwd;

mysql_close($connect);
?>
<meta http-equiv='refresh' content='0;url=index.php'>
