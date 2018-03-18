<?php
/*
	employeeWrite 에서 넘어오는 데이터를 받아서
	employee DB에 저장시켜주는 페이지
*/
session_start();
extract($_GET);
extract($_POST);
extract($_SERVER);

date_default_timezone_set("Asia/Seoul");
include_once('dbConnect.php');

$ee_writer = $_SESSION['id'];
$ee_radio = $_POST['ee_radio'];
$ee_title = $_POST['ee_title'];
$ee_writeDay = DATE("Y-m-d", time());
$ee_count = 0;
$ee_sido = $_POST['ee_sido'];
$ee_gugun = $_POST['ee_gugun'];
$ee_dong = $_POST['ee_dong'];
$ee_jobs1 = $_POST['ee_jobs1'];
$ee_career =  $_POST['ee_career'];
$ee_content = $_POST['ee_content'];

if($ee_radio=="day") { // 하루일 때
	$ee_day = $_POST['ee_day'];
	$ee_startTime = $_POST['ee_startTime'];
	$ee_finishTime = $_POST['ee_finishTime'];
	
	//내용이 없을 경우에는 경고가 뜨고 다시 되돌아 간다.
	if($ee_title=="" || $ee_sido=="" || $ee_gugun=="" || $ee_dong=="" || $ee_jobs1=="" || $ee_content=="" || $ee_day=="" || $ee_startTime=="" || $ee_finishTime=="") {
		echo"<script>
		alert('모든 내용을 입력해주세요.');
		history.go(-1);
		</script>";

		die;
	}
	if(isset($ee_career)) { // 경력이 있을겨우에는 DB에 저장
		$query = "insert into employee (ee_writer, ee_radio, ee_title, ee_writeDay, ee_count, ee_sido, ee_gugun, ee_dong, ee_jobs1, ee_career, ee_content) values ('$ee_writer', '$ee_radio', '$ee_title', '$ee_writeDay', '$ee_count', '$ee_sido', '$ee_gugun', '$ee_dong', '$ee_jobs1', '$ee_career', '$ee_content')"; 
	}
	else { // 없을 경우엔 경력을 제외하고 저장
		$query = "insert into employee (ee_writer, ee_radio, ee_title, ee_writeDay, ee_count, ee_sido, ee_gugun, ee_dong, ee_jobs1, ee_content) values ('$ee_writer', '$ee_radio', '$ee_title', '$ee_writeDay', '$ee_count', '$ee_sido', '$ee_gugun', '$ee_dong', '$ee_jobs1', '$ee_content')"; 
	}
	$result = mysql_query($query); 

	$ee_no = mysql_insert_id();
	
	$query = "insert into employee_day values ('$ee_no', '$ee_day', '$ee_startTime', '$ee_finishTime')"; 
	$result = mysql_query($query); 

	echo"<script>
		alert('게시물 작성이 완료되었습니다.');
		location.href='employeeBoard.php?sido=".$ee_sido."&gugun=".$ee_gugun."&dong=".$ee_dong."';
		</script>";
	
}

else { // 단기일 때
	$ee_startDay = $_POST['ee_startDay'];
	$ee_finishDay = $_POST['ee_finishDay'];
	$ee_monTime="";$ee_tueTime="";$ee_wedTime="";$ee_thuTime="";$ee_friTime="";$ee_satTime="";$ee_sunTime="";
	$ee_box = $_POST['ee_box']; 

	if($ee_box == null) {
			echo"<script>
		alert('기간을 다시 확인하세요.');
		history.go(-1);
		</script>";

		die;
	}

	for($i=0; $i<count($ee_box); $i++) { // 요일 중 선택된 요일이 있는데 내용이 입력이 안되어있는 경우 경고창
		switch($ee_box[$i]) {
			case "mon":
				$ee_monTime = $_POST['ee_monTime'];
				if($ee_monTime == "") {
					echo"<script>
					alert('모든 내용을 입력해주세요.');
					location.href='employeeWrite.php';
					</script>";

					die;
				}
				break;
			case "tue":
				$ee_tueTime = $_POST['ee_tueTime'];
				if($ee_tueTime == "") {
					echo"<script>
					alert('모든 내용을 입력해주세요.');
					location.href='employeeWrite.php';
					</script>";

					die;
				}
				break;
			case "wed":
				$ee_wedTime = $_POST['ee_wedTime'];
				if($ee_wedTime == "") {
					echo"<script>
					alert('모든 내용을 입력해주세요.');
					location.href='employeeWrite.php';
					</script>";

					die;
				}
				break;
			case "thu":
				$ee_thuTime = $_POST['ee_thuTime'];
				if($ee_thuTime == "") {
					echo"<script>
					alert('모든 내용을 입력해주세요.');
					location.href='employeeWrite.php';
					</script>";

					die;
				}
				break;
			case "fri":
				$ee_friTime = $_POST['ee_friTime'];
				if($ee_friTime == "") {
					echo"<script>
					alert('모든 내용을 입력해주세요.');
					location.href='employeeWrite.php';
					</script>";

					die;
				}
				break;
			case "sat":
				$ee_satTime = $_POST['ee_satTime'];
				if($ee_satTime == "") {
					echo"<script>
					alert('모든 내용을 입력해주세요.');
					location.href='employeeWrite.php';
					</script>";

					die;
				}
				break;
			case "sun":
				$ee_sunTime = $_POST['ee_sunTime'];
				if($ee_sunTime == "") {
					echo"<script>
					alert('모든 내용을 입력해주세요.');
					location.href='employeeWrite.php';
					</script>";

					die;
				}
				break;
		}
	}
	
	if($ee_title=="" || $ee_sido=="" || $ee_gugun=="" || $ee_dong=="" || $ee_jobs1=="" || $ee_content=="" || $ee_startDay=="" || $ee_finishDay=="") {
		echo"<script>
		alert('모든 내용을 입력해주세요.');
		history.go(-1);
		</script>";

		die;
	}

	if(isset($ee_career)) { // 경력이 있을 경우에 DB에 저장
		$query = "insert into employee (ee_writer, ee_radio, ee_title, ee_writeDay, ee_count, ee_sido, ee_gugun, ee_dong, ee_jobs1, ee_career, ee_content) values ('$ee_writer', '$ee_radio', '$ee_title', '$ee_writeDay', '$ee_count', '$ee_sido', '$ee_gugun', '$ee_dong', '$ee_jobs1', '$ee_career', '$ee_content')"; 
	}
	else { // 없을 경우에 경력을 제외하고 저장
		$query = "insert into employee (ee_writer, ee_radio, ee_title, ee_writeDay, ee_count, ee_sido, ee_gugun, ee_dong, ee_jobs1, ee_content) values ('$ee_writer', '$ee_radio', '$ee_title', '$ee_writeDay', '$ee_count', '$ee_sido', '$ee_gugun', '$ee_dong', '$ee_jobs1', '$ee_content')"; 
	}

	$result = mysql_query($query); 

	$ee_no = mysql_insert_id();
	
	$query = "insert into employee_days values ('$ee_no', '$ee_startDay', '$ee_finishDay', '$ee_monTime', '$ee_tueTime', '$ee_wedTime', '$ee_thuTime', '$ee_friTime', '$ee_satTime', '$ee_sunTime')"; 
	$result = mysql_query($query); 

	//게시물이 작성되면 자신이 입력한 시/도, 구/군, 동/면/읍 이 지역 값으로 설정되어 게시판 목록으로 돌아간다. 
	echo"<script>
		alert('게시물 작성이 완료되었습니다.');
		location.href='employeeBoard.php?sido=".$ee_sido."&gugun=".$ee_gugun."&dong=".$ee_dong."';
		</script>";
		
}

mysql_close($connect);
?>