<?php
/*
	employerEdit 에서 수정된 데이터를 DB에 다시 저장하는 페이지
*/
header("Content-Type: text/html; charset= UTF-8");
session_start();

extract($_REQUEST);
extract($_GET);
extract($_POST);
extract($_SERVER);
date_default_timezone_set("Asia/Seoul");

include_once('dbConnect.php');

$query = "select * from employer where er_no='$_POST[er_no]'"; 
$result = mysql_query($query, $connect);
$data = mysql_fetch_array($result);

$er_no = $_POST['er_no'];
$er_writeDay = date("Y-m-d", time());
$er_title = $_POST['er_title'];
$er_sido = $_POST['sido'];
$er_gugun = $_POST['gugun'];
$er_dong = $_POST['dong'];
$er_detail = $_POST['er_detail'];
$er_jobs1 = $_POST['jobs1'];
$er_jobs2 = $_POST['jobs2'];
$er_jobs3 = $_POST['jobs3'];
$er_money = $_POST['er_money'];
$er_content = $_POST['er_content'];

if($data['er_radio'] == "day") { // 하루일 경우
	$er_day = $_POST['er_day'];
	$er_startTime = $_POST['er_startTime'];
	$er_finishTime = $_POST['er_finishTime'];

	// 내용 모두 입력안하면 경고
	if($er_title=="" || $er_sido=="" || $er_gugun=="" || $er_dong=="" || $er_detail=="" || $er_jobs1=="" || $er_jobs2=="" || $er_jobs3==""  || $er_money=="" || $er_content=="" || $er_day=="" || $er_startTime=="" || $er_finishTime=="") {
		echo"<script>
		alert('모든 내용을 입력해주세요.');
		history.go(-1);
		</script>";

		die;
	}
	
	// 입력된 데이터 다시 DB에 업데이트
	$query = "update employer set er_title='$er_title', er_writeDay='$er_writeDay', er_sido='$er_sido', er_gugun='$er_gugun', er_dong='$er_dong', er_detail='$er_detail', er_jobs1='$er_jobs1', er_jobs2='$er_jobs2', er_jobs3='$er_jobs3', er_money='$er_money', er_content='$er_content' WHERE er_no='$er_no'";
	$result = mysql_query($query, $connect);

	// 하루일 경우에 해당하는 DB에 데이터 업데이트
	$que = "update employer_day set er_day='$er_day', er_startTime='$er_startTime', er_finishTime='$er_finishTime' WHERE er_no='$er_no'";
	$res = mysql_query($que, $connect);

	echo"<script>
	alert('게시물 수정이 완료되었습니다.');
	location.href='employerRead.php?er_no=$er_no';
	</script>";
}

else if($data['er_radio'] == "days") { // 단기일 경우
	$er_startDay = $_POST['er_startDay'];
	$er_finishDay = $_POST['er_finishDay'];

	$er_monTime="";
	$er_tueTime="";
	$er_wedTime="";
	$er_thuTime="";
	$er_friTime="";
	$er_satTime="";
	$er_sunTime="";

	$er_box = $_POST['er_box']; 

	// 아무것도 체크되지 않았을 경우
	if($er_box == null) {
			echo"<script>
		alert('기간을 다시 확인하세요.');
		history.go(-1);
		</script>";

		die;
	}

	// 선택하고 내용 입력 안했을 경우
	for($i = 0; $i < count($er_box); $i++) {
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

		if($er_title=="" || $er_sido=="" || $er_gugun=="" || $er_dong=="" || $er_detail=="" || $er_jobs1=="" || $er_jobs2=="" || $er_jobs3==""  || $er_money=="" || $er_content=="" || $er_startDay=="" || $er_finishDay=="") {
			echo"<script>
			alert('모든 내용을 입력해주세요.');
			history.go(-1);
			</script>";

			die;
		}

	}
	
	// 입력된 데이터 다시 DB에 업데이트
	$query = "update employer set er_title='$er_title', er_writeDay='$er_writeDay', er_sido='$er_sido', er_gugun='$er_gugun', er_dong='$er_dong', er_detail='$er_detail', er_jobs1='$er_jobs1', er_jobs2='$er_jobs2', er_jobs3='$er_jobs3', er_money='$er_money', er_content='$er_content' WHERE er_no='$er_no';";

	$result = mysql_query($query, $connect);

	// 수정 이전의 데이터를 모두 삭제 (특성상 전의 데이터가 삭제되지 않으면 전의 데이터와 겹칠 수 있음)
	$que1 = "delete from employer_days WHERE er_no='$er_no';";
	$res1 = mysql_query($que1, $connect);
	
	// 단기일 경우에 해당하는 DB에 데이터 업데이트
	$que2 = "insert into employer_days values ('$er_no', '$er_startDay', '$er_finishDay', '$er_monTime', '$er_tueTime', '$er_wedTime', '$er_thuTime', '$er_friTime', '$er_satTime', '$er_sunTime');";
	$res2 = mysql_query($que2, $connect);

	echo"<script>
		alert('게시물 작성이 완료되었습니다.');
		location.href='employerRead.php?er_no=$er_no';
		</script>";
}

?>