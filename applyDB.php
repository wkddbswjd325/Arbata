<?php
/*
	applyWrite_er 또는 ee에서 받아온 값을 DB에 저장하는 페이지
*/
	session_start();
	extract($_GET);
	extract($_POST);
	extract($_SERVER);

	date_default_timezone_set("Asia/Seoul");
	include_once('dbConnect.php');

	$a_date = DATE("Y-m-d");
	$a_time = DATE("h:i:s");
	
	$a_applier = $_SESSION['id'];
	$a_chk = $_POST['apply_chk'];


	if($a_chk == 0){
		$a_writer = $_POST['er_writer'];
		$a_title = $_POST['apply_title'];
		$a_etc = $_POST['apply_etc'];
		$a_content = $_POST['apply_content'];
		$a_post_no = $_POST['er_no'];
		$a_no = mysql_insert_id();

		//apply DB에 같은 아이디로 신청한적 있는지 체크!
		$sql = "select count(*) from apply where a_post_no='$a_post_no' and a_chk='$a_chk' and a_applier='$a_applier'"; 
		$check = mysql_fetch_array(mysql_query($sql));
		if($check['count(*)'] != 0) {
			echo "<script>alert('이미 신청을 한 게시물입니다.');location.href='employerRead.php?er_no=".$a_post_no."';</script>";
			die;
		}

		//아이디 존재여부 체크
		$sql1 = "select count(*) from member where mem_id='$a_writer'"; 
		$result = mysql_query($sql1); 
		$data = mysql_fetch_array($result); 

		if ( $data[0] > 0 ) $dup_id = 1; 
		else $dup_id = 0; 

		if($dup_id==0){ //아이디 존재하지 않을 경우
			echo"<script>
				alert('존재하지 않는 게시자입니다.');
				location.href=history.go(-1);
				</script>";
		}
		else{ //아이디 존재할 경우
			$sql2 = "insert into apply values('a_no', '$a_applier', '$a_writer', '$a_post_no', '$a_title', '$a_etc', '$a_content', '$a_date', '$a_time', '$a_chk', '')"; 
			mysql_query($sql2);	
		
			echo"<script>
				alert('매칭신청이 완료되었습니다.');
				location.href='employerRead.php?er_no=".$a_post_no."';
				</script>";
		}
	}

	else{
		$a_writer = $_POST['ee_writer'];
		$a_title = $_POST['apply_title'];
		$a_etc = $_POST['apply_etc'];
		$a_content = $_POST['apply_content'];
		$a_area = $_POST['ap_sido']." ".$_POST['ap_gugun']." ".$_POST['ap_dong']." ".$_POST['apply_area'];
		$a_post_no = $_POST['ee_no'];
		$a_no = mysql_insert_id();

		//아이디 존재여부 체크
		$sql1 = "select count(*) from member where mem_id='$a_writer'"; 
		$result = mysql_query($sql1); 
		$data = mysql_fetch_array($result); 

		if ( $data[0] > 0 ) $dup_id = 1; 
		else $dup_id = 0; 

		if($dup_id==0){ //아이디 존재하지 않을 경우
			echo"<script>
				alert('존재하지 않는 게시자입니다.');
				location.href=history.go(-1);
				</script>";
		}
		else{ //아이디 존재할 경우
			$sql2 = "insert into apply values('a_no', '$a_applier', '$a_writer', '$a_post_no', '$a_title', '$a_etc', '$a_content', '$a_date', '$a_time', '$a_chk', '$a_area')"; 
			mysql_query($sql2);	
		
			echo"<script>
				alert('매칭신청이 완료되었습니다.');
				location.href='employeeRead.php?ee_no=".$a_post_no."';
				</script>";
		}

	}
mysql_close($connect);
?>