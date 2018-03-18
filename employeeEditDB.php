<?php
/*
	employeeEdit 에서 수정된 데이터를 DB에 다시 저장하는 페이지
*/
header("Content-Type: text/html; charset= UTF-8");
session_start();

extract($_REQUEST);
extract($_GET);
extract($_POST);
extract($_SERVER);
date_default_timezone_set("Asia/Seoul");

include_once('dbConnect.php');

$query = "select * from employee where ee_no='$_POST[ee_no]'"; 
$result = mysql_query($query, $connect);
$data = mysql_fetch_array($result);

$ee_no = $_POST['ee_no'];
$ee_writeDay = date("Y-m-d", time());
$ee_title = $_POST['ee_title'];
$ee_sido = $_POST['sido'];
$ee_gugun = $_POST['gugun'];
$ee_dong = $_POST['dong'];
$ee_jobs1 = $_POST['jobs1'];
$ee_career = $_POST['ee_career'];
$ee_content = $_POST['ee_content'];

if($data['ee_radio'] == "day") { // 하루일 경우
	$ee_day = $_POST['ee_day'];
	$ee_startTime = $_POST['ee_startTime'];
	$ee_finishTime = $_POST['ee_finishTime'];

	if($ee_title=="" || $ee_sido=="" || $ee_gugun=="" || $ee_dong=="" || $ee_jobs1=="" || $ee_content=="" || $ee_day=="" || $ee_startTime=="" || $ee_finishTime=="") {
		echo"<script>
		alert('모든 내용을 입력해주세요.');
		history.go(-1);
		</script>";

		die;
	}

	// 입력된 데이터 다시 DB에 업데이트
	$query = "update employee set ee_title='$ee_title', ee_writeDay='$ee_writeDay', ee_sido='$ee_sido', ee_gugun='$ee_gugun', ee_dong='$ee_dong', ee_jobs1='$ee_jobs1', ee_career='$ee_career', ee_content='$ee_content' WHERE ee_no='$ee_no'";
	$result = mysql_query($query, $connect);

	// 하루일 경우에 해당하는 DB에 데이터 업데이트
	$que = "update employee_day set ee_day='$ee_day', ee_startTime='$ee_startTime', ee_finishTime='$ee_finishTime' WHERE ee_no='$ee_no'";
	$res = mysql_query($que, $connect);

	echo"<script>
	alert('게시물 수정이 완료되었습니다.');
	location.href='employeeRead.php?ee_no=$ee_no';
	</script>";
}

else if($data['ee_radio'] == "days") { // 단기일 경우
	$ee_startDay = $_POST['ee_startDay'];
	$ee_finishDay = $_POST['ee_finishDay'];

	$ee_monTime="";
	$ee_tueTime="";
	$ee_wedTime="";
	$ee_thuTime="";
	$ee_friTime="";
	$ee_satTime="";
	$ee_sunTime="";

	$ee_box = $_POST['ee_box']; 
	
	// 아무것도 체크되지 않았을 경우
	if($ee_box == null) {
			echo"<script>
		alert('기간을 다시 확인하세요.');
		history.go(-1);
		</script>";

		die;
	}

	// 선택하고 내용 입력 안했을 경우
	for($i = 0; $i < count($ee_box); $i++) {
		switch($ee_box[$i]) {
			case "mon":
				$ee_monTime = $_POST['ee_monTime'];
				if($ee_monTime == "") {
					echo"<script>
					alert('모든 내용을 입력해주세요.');
					history.go(-1);
					</script>";
					die;
				}
				break;
			case "tue":
				$ee_tueTime = $_POST['ee_tueTime'];
				if($ee_tueTime == "") {
					echo"<script>
					alert('모든 내용을 입력해주세요.');
					history.go(-1);
					</script>";
					die;
				}
				break;
			case "wed":
				$ee_wedTime = $_POST['ee_wedTime'];
				if($ee_wedTime == "") {
					echo"<script>
					alert('모든 내용을 입력해주세요.');
					history.go(-1);
					</script>";
					die;
				}
				break;
			case "thu":
				$ee_thuTime = $_POST['ee_thuTime'];
				if($ee_thuTime == "") {
					echo"<script>
					alert('모든 내용을 입력해주세요.');
					history.go(-1);
					</script>";
					die;
				}
				break;
			case "fri":
				$ee_friTime = $_POST['ee_friTime'];
				if($ee_friTime == "") {
					echo"<script>
					alert('모든 내용을 입력해주세요.');
					history.go(-1);
					</script>";
					die;
				}
				break;
			case "sat":
				$ee_satTime = $_POST['ee_satTime'];
				if($ee_satTime == "") {
					echo"<script>
					alert('모든 내용을 입력해주세요.');
					history.go(-1);
					</script>";
					die;
				}
				break;
			case "sun":
				$ee_sunTime = $_POST['ee_sunTime'];
				if($ee_sunTime == "") {
					echo"<script>
					alert('모든 내용을 입력해주세요.');
					history.go(-1);
					</script>";
					die;
				}
				break;
		}

		if($ee_title=="" || $ee_sido=="" || $ee_gugun=="" || $ee_dong=="" || $ee_jobs1=="" || $ee_content=="" || $ee_startDay=="" || $ee_finishDay=="") {
			echo"<script>
			alert('모든 내용을 입력해주세요.');
			history.go(-1);
			</script>";

			die;
		}

	}
	
	// 입력된 데이터 다시 DB에 업데이트
	$query = "update employee set ee_title='$ee_title', ee_writeDay='$ee_writeDay', ee_sido='$ee_sido', ee_gugun='$ee_gugun', ee_dong='$ee_dong', ee_jobs1='$ee_jobs1', ee_career='$ee_career', ee_content='$ee_content' WHERE ee_no='$ee_no';";

	$result = mysql_query($query, $connect);

	// 수정 이전의 데이터를 모두 삭제 (특성상 전의 데이터가 삭제되지 않으면 전의 데이터와 겹칠 수 있음)
	$que1 = "delete from employee_days WHERE ee_no='$ee_no';";
	$res1 = mysql_query($que1, $connect);

	// 단기일 경우에 해당하는 DB에 데이터 업데이트
	$que2 = "insert into employee_days values ('$ee_no', '$ee_startDay', '$ee_finishDay', '$ee_monTime', '$ee_tueTime', '$ee_wedTime', '$ee_thuTime', '$ee_friTime', '$ee_satTime', '$ee_sunTime');";
	$res2 = mysql_query($que2, $connect);

	echo"<script>
		alert('게시물 작성이 완료되었습니다.');
		location.href='employeeRead.php?ee_no=$ee_no';
		</script>";
}

?>