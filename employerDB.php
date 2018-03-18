<?php
/*
	employerWrite 에서 넘어오는 데이터를 받아서
	employer DB에 저장시켜주는 페이지
*/
session_start();
extract($_GET);
extract($_POST);
extract($_SERVER);

date_default_timezone_set("Asia/Seoul");
include_once('dbConnect.php');

$er_writer = $_SESSION['id'];
$er_radio = $_POST['er_radio'];
$er_title = $_POST['er_title'];
$er_writeDay = DATE("Y-m-d", time());
$er_count = 1;
$er_sido = $_POST['er_sido'];
$er_gugun = $_POST['er_gugun'];
$er_dong = $_POST['er_dong'];
$er_detail = $_POST['er_detail'];
$er_jobs1 = $_POST['er_jobs1'];
$er_jobs2 = $_POST['er_jobs2'];
$er_jobs3 = $_POST['er_jobs3'];
$er_money =  $_POST['er_money'];
$er_content = $_POST['er_content'];

if($er_radio=="day") { // 하루일 때
	$er_day = $_POST['er_day'];
	$er_startTime = $_POST['er_startTime'];
	$er_finishTime = $_POST['er_finishTime'];

	// 내용이 없을 경우에는 경고가 뜨고 다시 되돌아 간다.
	if($er_title=="" || $er_sido=="" || $er_gugun=="" || $er_dong=="" || $er_detail=="" || $er_jobs1=="" || $er_jobs2=="" || $er_jobs3==""  || $er_money=="" || $er_content=="" || $er_day=="" || $er_startTime=="" || $er_finishTime=="") {
		echo"<script>
		alert('모든 내용을 입력해주세요.');
		history.go(-1);
		</script>";

		die;
	}

	// 데이터를 테이블에 저장
	$query = "insert into employer (er_writer, er_radio, er_title, er_writeDay, er_count, er_sido, er_gugun, er_dong, er_detail, er_jobs1, er_jobs2, er_jobs3, er_money, er_content) values ('$er_writer', '$er_radio', '$er_title', '$er_writeDay', '$er_count', '$er_sido', '$er_gugun', '$er_dong', '$er_detail', '$er_jobs1', '$er_jobs2', '$er_jobs3', '$er_money', '$er_content')"; 
	$result = mysql_query($query); 

	$er_no = mysql_insert_id();
	
	$query = "insert into employer_day values ('$er_no', '$er_day', '$er_startTime', '$er_finishTime')"; 
	$result = mysql_query($query); 

	echo"<script>
		alert('게시물 작성이 완료되었습니다.');
		location.href='employerBoard.php?sido=".$er_sido."&gugun=".$er_gugun."&dong=".$er_dong."';
		</script>";
	
}

else { // 단기일 때
	$er_startDay = $_POST['er_startDay'];
	$er_finishDay = $_POST['er_finishDay'];
	$er_monTime="";$er_tueTime="";$er_wedTime="";$er_thuTime="";$er_friTime="";$er_satTime="";$er_sunTime="";
	$er_box = $_POST['er_box']; 

	if($er_box == null) {
			echo"<script>
		alert('기간을 다시 확인하세요.');
		history.go(-1);
		</script>";

		die;
	}

	for($i=0; $i<count($er_box); $i++) { // 요일 중 선택된 요일이 있는데 내용이 입력이 안되어있는 경우 경고창
		switch($er_box[$i]) {
			case "mon":
				$er_monTime = $_POST['er_monTime'];
				if($er_monTime == "") {
					echo"<script>
					alert('모든 내용을 입력해주세요.');
					history.go(-1);
					</script>";

					die;
				}
				break;
			case "tue":
				$er_tueTime = $_POST['er_tueTime'];
				if($er_tueTime == "") {
					echo"<script>
					alert('모든 내용을 입력해주세요.');
					history.go(-1);
					</script>";

					die;
				}
				break;
			case "wed":
				$er_wedTime = $_POST['er_wedTime'];
				if($er_wedTime == "") {
					echo"<script>
					alert('모든 내용을 입력해주세요.');
					history.go(-1);
					</script>";

					die;
				}
				break;
			case "thu":
				$er_thuTime = $_POST['er_thuTime'];
				if($er_thuTime == "") {
					echo"<script>
					alert('모든 내용을 입력해주세요.');
					history.go(-1);
					</script>";

					die;
				}
				break;
			case "fri":
				$er_friTime = $_POST['er_friTime'];
				if($er_friTime == "") {
					echo"<script>
					alert('모든 내용을 입력해주세요.');
					history.go(-1);
					</script>";

					die;
				}
				break;
			case "sat":
				$er_satTime = $_POST['er_satTime'];
				if($er_satTime == "") {
					echo"<script>
					alert('모든 내용을 입력해주세요.');
					history.go(-1);
					</script>";

					die;
				}
				break;
			case "sun":
				$er_sunTime = $_POST['er_sunTime'];
				if($er_sunTime == "") {
					echo"<script>
					alert('모든 내용을 입력해주세요.');
					history.go(-1);
					</script>";

					die;
				}
				break;
		}
	}
	
	if($er_title=="" || $er_sido=="" || $er_gugun=="" || $er_dong=="" || $er_detail=="" || $er_jobs1=="" || $er_jobs2=="" || $er_jobs3==""  || $er_money=="" || $er_content=="" || $er_startDay=="" || $er_finishDay=="") {
		echo"<script>
		alert('모든 내용을 입력해주세요.');
		history.go(-1);
		</script>";

		die;
	}

	// 데이터를 테이블에 저장
	$query = "insert into employer (er_writer, er_radio, er_title, er_writeDay, er_count, er_sido, er_gugun, er_dong, er_detail, er_jobs1, er_jobs2, er_jobs3, er_money, er_content) values ('$er_writer', '$er_radio', '$er_title', '$er_writeDay', '$er_count', '$er_sido', '$er_gugun', '$er_dong', '$er_detail', '$er_jobs1', '$er_jobs2', '$er_jobs3', '$er_money', '$er_content')"; 
	$result = mysql_query($query); 

	$er_no = mysql_insert_id();
	
	$query = "insert into employer_days values ('$er_no', '$er_startDay', '$er_finishDay', '$er_monTime', '$er_tueTime', '$er_wedTime', '$er_thuTime', '$er_friTime', '$er_satTime', '$er_sunTime')"; 
	$result = mysql_query($query); 

	//게시물이 작성되면 자신이 입력한 시/도, 구/군, 동/면/읍 이 지역 값으로 설정되어 게시판 목록으로 돌아간다. 
	echo"<script>
		alert('게시물 작성이 완료되었습니다.');
		location.href='employerBoard.php?sido=".$er_sido."&gugun=".$er_gugun."&dong=".$er_dong."';
		</script>";
		
}

mysql_close($connect);
?>