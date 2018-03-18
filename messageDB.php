<?php
/*
	메세지를 보낼 때 사용되는 페이지 (DB에 데이터 넣기)
*/
session_start();

date_default_timezone_set("Asia/Seoul");
include_once('dbConnect.php');

	$msg_date = DATE("Y-m-d");
	$msg_time = DATE("h:i:s");


	$msg_sendId = $_SESSION['id'];
	$msg_rcvId = $_POST['msg_rcvId'];
	$msg_title = $_POST['msg_title'];
	$msg_content = $_POST['msg_content'];
	$msg_no = mysql_insert_id();
	
	//아이디 존재여부 체크
	$sql1 = "select count(*) from member where mem_id='$msg_rcvId'"; 
	$result = mysql_query($sql1); 
	$data = mysql_fetch_array($result); 

	if ( $data[0] > 0 ) $dup_id = 1; 
	else $dup_id = 0; 

	if($dup_id==0){ //아이디 존재하지 않을 경우
		echo"<script>
			alert('존재하지 않는 아이디입니다.');
			location.href='message.php';
			</script>";
	}

	// 자기 자신에게 쪽지를 보낼 경우
	if($msg_rcvId == $_SESSION['id']) {
		echo"<script>
			alert('자기 자신에게는 쪽지 전송이 불가합니다');
			location.href='message.php';
			</script>";
	}

	else{ //아이디 존재할 경우
		$sql2 = "insert into message values('$msg_no', '$msg_title', '$msg_content', '$msg_date', '$msg_time', '$msg_rcvId', '$msg_sendId', '0')"; 
		$sql3 = "insert into message values('$msg_no', '$msg_title', '$msg_content', '$msg_date', '$msg_time', '$msg_rcvId', '$msg_sendId', '1')";
		mysql_query($sql2);	
		mysql_query($sql3);
	
		echo"<script>
			alert('쪽지전송을 성공하였습니다.');
			location.href='messageBox.php';
			</script>";
	}
mysql_close($connect);
?>