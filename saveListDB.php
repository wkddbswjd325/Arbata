<?php
/*
	employerRead.php 나 employeeRead.php 에서
	'담기' 버튼을 누르면 넘어오는 페이지

	보관함에 게시물을 담는 페이지이다.
*/
header("Content-Type: text/html; charset= UTF-8 ");
session_start();

extract($_REQUEST);
extract($_GET);
extract($_POST);
extract($_SERVER);
date_default_timezone_set("Asia/Seoul");

include_once('dbConnect.php');

$s_no = mysql_insert_id();
$s_id = $_SESSION['id'];
$e_no = $_GET['e_no'];
$s_chk = $_GET['s_chk'];
$s_date = DATE("Y-m-d");
$s_time = DATE("h:i:s");

//employerRead을 보관할 때
if ( $s_chk == 0 ) { 
	
	$sql = "select * from employer where er_no='$e_no'"; 
	$res = mysql_query($sql); 
	$row = mysql_fetch_array($res);

	$sql1 = "select count(*) from savelist where s_id='$s_id' and e_no='$e_no' and s_chk=0"; //이미 저장한 게시물인지 확인
	$result = mysql_query($sql1); 
	$data = mysql_fetch_array($result); 

	$sql_c = "select count(*) from savelist where s_id='$s_id' and s_chk=0"; //보관함에 담긴 개수가 몇개인지 확인
	$result_c = mysql_query($sql_c); 
	$data_c = mysql_fetch_array($result_c);

	if ( $data[0] > 0 ) { //중복된 게시물을 담을 경우
		echo"<script>alert('이미 보관함에 담긴 게시물입니다.');location.href='employerRead.php?er_no=".$e_no."';</script>";
	}
	else if( $data_c[0] == 10 || $data_c[0] > 10){ //11개 이상 담으려고 할 경우
		echo"<script>alert('보관 가능한 게시물의 개수를 초과하였습니다.');location.href='employerRead.php?er_no=".$e_no."';</script>";
	}
	else{ 
		$sql2 = "insert into savelist values('$s_no', '$s_id', '$s_date', '$s_time', '$s_chk', '$e_no')"; 
		mysql_query($sql2);
		echo"<script>alert('대타 보관함에 저장하였습니다.(최대 10개 보관가능)'); location.href='employerRead.php?er_no=".$e_no."'; </script>";
	}
}

//employeeRead를 보관할 떄
else if( $s_chk == 1 ){
	
	$sql = "select * from employee where ee_no='$e_no'"; 
	$res = mysql_query($sql); 
	$row = mysql_fetch_array($res);

	$sql1 = "select count(*) from savelist where s_id='$s_id' and e_no='$e_no' and s_chk=1"; //이미 저장한 게시물인지 확인
	$result = mysql_query($sql1); 
	$data = mysql_fetch_array($result); 

	$sql_c = "select count(*) from savelist where s_id='$s_id' and s_chk=1"; //보관함에 담긴 개수가 몇개인지 확인
	$result_c = mysql_query($sql_c); 
	$data_c = mysql_fetch_array($result_c);

	if ( $data[0] > 0 ) {
		echo"<script>alert('이미 보관함에 담긴 게시물입니다.');location.href='employeeRead.php?ee_no=".$e_no."';</script>";
	}
	else if( $data_c[0] == 10 || $data_c[0] > 10){
		echo"<script>alert('보관 가능한 게시물의 개수를 초과하였습니다.');location.href='employeeRead.php?ee_no=".$e_no."';</script>";
	}
	else{ 
		$sql2 = "insert into savelist values('$s_no', '$s_id', '$s_date', '$s_time', '$s_chk', '$e_no')"; 
		mysql_query($sql2);
		echo"<script>alert('대타 보관함에 저장하였습니다.(최대 10개 보관가능)'); location.href='employeeRead.php?ee_no=".$e_no."'; </script>";
	}
}

mysql_close($connect);

?>