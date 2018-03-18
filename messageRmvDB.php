<?php
/*
	메세지 보관함의 메세지들을 삭제하는 페이지
*/
session_start();

date_default_timezone_set("Asia/Seoul");
include_once('dbConnect.php');

// 삭제할 메세지가 없을 경우(선택하지 않았을 경우)
if(!isset($_POST['msg_rcvList']) && !isset($_POST['msg_sendList'])) {
	echo"<script>
		alert('삭제할 쪽지를 선택해 주세요.');
		location.href='messageBox.php';
		</script>";
		die;
}

// 수신 메세지를 선택하여 삭제하는 경우
else if(isset($_POST['msg_rcvList']) && !isset($_POST['msg_sendList'])){
	 
	 $msg_rcvList = $_POST['msg_rcvList'];

	 for($i=0; $i<count($msg_rcvList); $i++) {
		$msg_no = $msg_rcvList[$i];
		$sql = "DELETE FROM message WHERE msg_no=$msg_no and msg_chk='0'"; 
		mysql_query($sql);	
     }

	 echo"<script>
		alert('선택한 쪽지를 삭제하였습니다.');
		location.href='messageBox.php?value=0';
		</script>";
}

// 발신 메세지를 선택하여 삭제하는 경우
else if(!isset($_POST['msg_rcvList']) && isset($_POST['msg_sendList'])){
	
	$msg_sendList = $_POST['msg_sendList'];

	for($i=0; $i<count($msg_sendList); $i++) {
		$msg_no = $msg_sendList[$i];
		$sql = "DELETE FROM message WHERE msg_no=$msg_no and msg_chk='1'"; 
		mysql_query($sql);	
     }
	echo"<script>
		alert('선택한 쪽지를 삭제하였습니다.');
		location.href='messageBox.php?value=2';
		</script>";
}
mysql_close($connect);
?>