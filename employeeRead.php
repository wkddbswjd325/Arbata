<?php
/*
	employeeWrite에서 입력한 데이터의 내용을 볼 수 있음
	employeeBoard에서 글 번호를 받아와 불러오는 페이지
*/
include_once('dbConnect.php');
include('menuBar.php');
?>
<html lang="ko">
<head>
<meta charset="UTF-8">
<!-- 모바일 화면의 크기가 고정되게 하는 소스, 가로 스크롤 안생긴다. -->
<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0,minimum-scale=1.0, width=device-width" />

<!-- 아이폰 바탕화면 아이콘 추가 -->
<link rel="apple-touch-icon" href="image/apple.jpg" />
  
<!-- 주소창 숨기기 -->
<script type="text/javascript">
window.addEventListener("load", function() {
	setTimeout(loaded, 100);
	}, false);
function loaded() {
	window.scrollTo(0,1);
	}
</script>
<link rel="stylesheet" href="inputStyle.css" type="text/css"/>
<link rel="stylesheet" href="boardStyle.css" type="text/css"/>
<style type="text/css">
  p {	color:#252c41;font-size:12pt;	}
</style>
<title>나에게 부탁해! 상세보기</title>
</head>
<body>
<?php

$query = "select * from employee where ee_no='$_GET[ee_no]'"; 
$result = mysql_query($query, $connect);
$data = mysql_fetch_array($result);

$check = mysql_num_rows($result); 
if($check==0) 
	echo "<script>alert('삭제된 게시물입니다.');location.href=history.back();</script>";

$query = "select img_path from image where mem_id='$data[ee_writer]'"; 
$result = mysql_query($query, $connect);
$row = mysql_fetch_array($result);

if(!isset($_SESSION['id'])){ $id = "";}
else{ $id = $_SESSION['id'];}

if($data['ee_radio'] == "day") {
	$que = "select * from employee_day where ee_no='$_GET[ee_no]'";
	$res = mysql_query($que, $connect);
	$row1 = mysql_fetch_array($res);
}
else if($data['ee_radio'] == "days") {
	$que = "select * from employee_days where ee_no='$_GET[ee_no]'";
	$res = mysql_query($que, $connect);
	$row2 = mysql_fetch_array($res);

}

?>
<form name="employeeRead" method="POST" action="employeeDB.php">
<div id="boardDiv">
<div id="readTitle" align="center">
		<?php
		if($data['ee_radio'] == "day") {
			echo "하루 대타 가능";
		}
		else if($data['ee_radio'] == "days") {
			echo "단기 대타 가능";
		}
		?>
		<p class="title"><b><?php echo $data['ee_title'];?></b></p>
</div>

<?php
$sql = "update employee set ee_count=ee_count+1 WHERE ee_no='$_GET[ee_no]'"; 
$res = mysql_query($sql);
?>

<?php 
if($data['ee_writer'] == $id) { ?>
	<img class="writer_img" src="http://14.63.196.104/<?php echo $row['img_path'];?>" onClick="location.href='myPage.php?rcvId=<?php echo $data['ee_writer']; ?>'"/>
<?php
} 
else{ ?>
	<img class="writer_img" src="http://14.63.196.104/<?php echo $row['img_path'];?>" onClick="location.href='memPage.php?rcvId=<?php echo $data['ee_writer']; ?>'"/>
<?php
}
?>

<div id="write_info">작성자

<?php 
if($data['ee_writer'] == $id) { 
	echo "<b><a href='myPage.php?rcvId=".$data['ee_writer']."'>";
	echo $data['ee_writer']."</a></b><br>";}

else{ 
	echo "<b><a href='memPage.php?rcvId=".$data['ee_writer']."'>";
	echo $data['ee_writer']."</a></b>";
?>
<img src="image/msg.png" width="18px" valign="absmiddle" onclick="location.href='message.php?msg_sendId=<?php echo $data['ee_writer'];?>'"><br>
<?php } ?>

작성일 <b><?php echo $data['ee_writeDay'];?></b> | 조회수 <b><?php echo $data['ee_count'];?></b>
</div>

<div id="ee_info">
<span id="ee_info_title" style="vertical-align:top;">사는 지역</span>
<span id="ee_info_content"><?php echo $data['ee_sido']." ".$data['ee_gugun']." ".$data['ee_dong'];?></span>
</div>

<div id="ee_info">
<span id="ee_info_title" style="vertical-align:top;">관심 업종</span>
<span id="ee_info_content"><?php echo $data['ee_jobs1'] ?></span>
</div>

<div id="ee_info">
<span id="ee_info_title" style="vertical-align:top;">가능 기간</span>
<span id="ee_info_content">
<?php
		if($data['ee_radio'] == "day") {
			echo $row1['ee_day'];
		}
		else if($data['ee_radio'] == "days") {
			echo $row2['ee_startDay']." ~ ".$row2['ee_finishDay'];
		}
?>
</span>
</div>

<div id="ee_info">
<span id="ee_info_title" style="vertical-align:top;">가능 시간</span>
<span id="ee_info_content">
<?php
	// 하루일 경우에는 시간을 표시하기에 오전/오후를 자세히 구성
	if($data['ee_radio'] == "day") {
		$start_hour = substr($row1['ee_startTime'], 0, 2);
		$start_minute = substr($row1['ee_startTime'], 3, 2);
		$fin_hour = substr($row1['ee_finishTime'], 0, 2);
		$fin_minute = substr($row1['ee_finishTime'], 3, 2);

		if ($start_hour == 00) echo "오전 12시";
		else if($start_hour <= 12) echo "오전 ".$start_hour."시";
		else echo "오후 ".($start_hour-12)."시";

		if ($start_minute != "00") {
			echo $start_minute + "분";
		}
		echo " ~ ";
		
		if ($fin_hour == 00) echo "오전 12시";
		else if($fin_hour <= 12) echo "오전 ".$fin_hour."시";
		else echo "오후 ".($fin_hour-12)."시";

		if ($fin_minute != "00") {
			echo $fin_minute + "분";
		}
	}
	
	// 단기일 경우에는 요일별로 시간 표시
	else if($data['ee_radio'] == "days") {
		$d = " ";
		for($i = 3; $i <= 9; $i++) {
			if($row2[$i] != null) {
				if($i == 3) $d = "월";
				else if($i == 3) $d = "월";
				else if($i == 4) $d = "화";
				else if($i == 5) $d = "수";
				else if($i == 6) $d = "목";
				else if($i == 7) $d = "금";
				else if($i == 8) $d = "토";
				else $d = "일";

				echo $d."요일 : ".$row2[$i]."<br>";
			}	
		}
	}
?>
</span>
</div>

<div id="ee_info">
<span id="ee_info_title" style="vertical-align:top;">경력</span>
<span id="ee_info_content"><?php echo nl2br($data['ee_career']);?></span>
</div>

<div id="ee_info">
<span id="ee_content">
<?php echo nl2br($data['ee_content']);?>
</span>
</div>

<div id="ee_info" align="center">
<?php
echo"<script>
	function del() {
		var msg='삭제하시겠습니까?';
		var result = confirm(msg);
		if(result == true) {
			location.href='employeeDelDB.php?ee_no=$data[ee_no]';
		}
		else {
			return false;
		}
	}
</script>";
		//matching 여부 확인
		$sql = "select e_stmt from employee where ee_no='$_GET[ee_no]'"; 
		$check = mysql_fetch_array(mysql_query($sql));
		if($check['e_stmt'] == 1) { // 이미 매칭한 게시물일 경우 경고
			echo "이미 매칭이 완료된 게시물입니다.";
		}
		else {
			if(!isset($_SESSION['id'])){
				echo "회원만 매칭 신청이 가능합니다.";
			}
			else if($data['ee_writer'] == $_SESSION['id']) { // 자신의 게시물일 경우
			?> 
			<input type="button" value="수정" name="readEdit" id="readEdit" onclick="location.href='employeeEdit.php?ee_no=<?php echo $data['ee_no']?>'">
			<input type="button" value="삭제" name="readDel" id="readDel" onclick="del();">
			<?php
			}
			else if(isset($_SESSION['id'])){ // 남의 게시물일 경우
			?>
			<input type="button" value="담기" style="width:45%" name="save" id="save" onclick="location.href='saveListDB.php?e_no=<?php echo $data['ee_no'];?>&&s_chk=1'">
			<input type="button" style="width:45%;" value="매칭 신청" name="applyMatching" id="applyMatching" onclick="location.href='applyWrite_ee.php?ee_no=<?php echo $data['ee_no']?>'">
		<?php
			}
		}
		?>
</div>

</div>
</form>
</body>
</html>
<?php
mysql_close($connect);
?>
