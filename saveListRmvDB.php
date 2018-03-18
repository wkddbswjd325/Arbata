<?php
/*
	대타 보관함 삭제 소스
	saveList 에서
	삭제를 누르면 이 페이지로 넘어와 DB에서 데이터를 제거하는 방식
*/
session_start();
extract($_GET);
extract($_POST);
extract($_SERVER);

date_default_timezone_set("Asia/Seoul");
include_once('dbConnect.php');

// 삭제할 게시물을 선택하지 않았을 경우
if(!isset($_POST['saveList1']) && !isset($_POST['saveList2'])) {
	echo"<script>
		alert('삭제할 게시물을 선택해 주세요.');
		location.href='saveList.php';
		</script>";
		die;
}

// 보관함에 담긴 '대타를 부탁해' 게시물을 삭제할 경우
else if(isset($_POST['saveList1']) && !isset($_POST['saveList2'])){
	
	$saveList1 = $_POST['saveList1'];


	for($i=0; $i<count($saveList1); $i++) {
		$s_no = $saveList1[$i];
		$sql = "DELETE FROM savelist WHERE s_no=$s_no and s_chk='0'"; 
		mysql_query($sql);	
     }

	echo"<script>
		alert('선택한 게시물을 삭제하였습니다.');
		location.href='saveList.php?value=1';
		</script>";
}

// 보관함에 담긴 '나에게 부탁해' 게시물을 삭제할 경우
else if(!isset($_POST['saveList1']) && isset($_POST['saveList2'])){
	
	$saveList2 = $_POST['saveList2'];


	for($i=0; $i<count($saveList2); $i++) {
		$s_no = $saveList2[$i];
		$sql = "DELETE FROM savelist WHERE s_no=$s_no and s_chk='1'"; 
		mysql_query($sql);	
     }

	echo"<script>
		alert('선택한 게시물을 삭제하였습니다.');
		location.href='saveList.php?value=2';
		</script>";
}

mysql_close($connect);
?>