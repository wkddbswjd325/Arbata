<?php
/*
	매칭 정보를 저장하는 페이지
	matchingBoard 에서 데이터를 받아온다.
*/
	session_start();

	date_default_timezone_set("Asia/Seoul");
	include_once('dbConnect.php');

	$a_no = $_GET['a_no'];
	$mat_date = DATE("Y-m-d");
	$mat_time = DATE("h:i:s");

	$sql = "select * from apply where a_no='$a_no'"; 
	$result = mysql_query($sql); 
	$data = mysql_fetch_array($result); 

	$a_applier = $data['a_applier'];
	$a_writer = $data['a_writer'];
	$a_post_no = $data['a_post_no'];
	$a_chk = $data['a_chk'];

	//matching DB 테이블에 중복된 레코드가 있는지 확인!
	$sql = "select count(*) from matching where a_post_no='$a_post_no' and a_chk='$a_chk'"; 
	$check = mysql_fetch_array(mysql_query($sql));
	if($check['count(*)'] != 0) {
		echo "<script>alert('이미 매칭이 완료된 게시물입니다.');location.href='myPostList.php';</script>";
		die;
	}

	$mat_no = mysql_insert_id();
	
	$sql = "insert into matching values ('$mat_no', '$a_no', '$a_applier', '$a_writer', '$a_post_no', '$a_chk', '$mat_date', '$mat_time')"; 
	mysql_query($sql);

	$mat_no = mysql_insert_id();

	if($a_chk == 0) {
		$sql = "update employer set e_stmt=1 where er_no='$a_post_no'"; 
		mysql_query($sql); 
	}

	else if($a_chk == 1) {
		$sql = "update employee set e_stmt=1 where ee_no='$a_post_no'"; 
		mysql_query($sql); 
	}

	echo"<script>
		alert('매칭이 완료되었습니다.');
		location.href='matchingResult.php?mat_no=".$mat_no."';
		</script>";

	
mysql_close($connect);
?>