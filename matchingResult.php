<?php
/*
	자신과 상대방과의 매칭 결과를 보여주는 페이지
	상단에는 자신이 지원했던 게시물이나 자신이 올렸던 게시물의 내용이 나오고
	그 밑에는 상대방의 명함과 자신의 명함을 볼 수 있다.
	상대방에 대한 후기를 쓰는 review 페이지로 넘어갈 수 있으며
	자신이 작성한 게시물 혹은 상대방의 지원서,
	자신이 지원한 게시물 혹은 자신의 지원서를 확인할 수 있으며 상대방의 정보를 볼 수 있다.
*/
include_once('dbConnect.php');
include_once('menuBar.php');
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
<link rel="stylesheet" href="cardStyle.css" type="text/css"/>
<title>매칭 완료 페이지</title>
</head>
<!--로그인이 되지 않았을 때 -->
<?php
	if(!isset($_SESSION['id']) || !isset($_SESSION['passwd'])) {
?>
<body>
<table width="100%" cellpadding="5" cellspacing="0">
	<tr>
		<td>로그인이 필요한 기능 입니다.</td>
	</tr>	
</table>
<!--로그인이 되어있을 때 -->
<?php
}
else {
	date_default_timezone_set("Asia/Seoul");
	$mat_no = $_GET['mat_no'];
	$id = $_SESSION['id'];

	$query = "select * from matching where mat_no=$mat_no";
	$result= mysql_query($query, $connect);
	$data = mysql_fetch_array($result);

	$query = "select * from member natural join image where mem_id='$data[a_applier]'"; 
	$result = mysql_query($query, $connect);
	$applier = mysql_fetch_array($result);

	$query = "select * from member natural join image where mem_id = '$data[a_writer]'"; 
	$result = mysql_query($query, $connect);
	$writer = mysql_fetch_array($result);
	
	$query = "select * from apply where a_no='$data[a_no]'";
	$result= mysql_query($query, $connect);
	$a_data = mysql_fetch_array($result);
	
	echo "<div id='infomation_title'>대타 정보</div>";

	//employer(대타를 부탁해! 게시판 매칭)
	if($data['a_chk'] == 0) {
		$query = "select * from employer where er_no='$data[a_post_no]'";
		$result = mysql_query($query, $connect);
		$er = mysql_fetch_array($result);

		//기간별 select 하루/단기
		if($er['er_radio'] == "day") {
			$query = "select * from employer_day where er_no='$er[er_no]'";
			$result = mysql_query($query, $connect);
			$er_day = mysql_fetch_array($result);
		}
		else if($er['er_radio'] == "days") {
			$query = "select * from employer_days where er_no='$er[er_no]'";
			$result = mysql_query($query, $connect);
			$er_days = mysql_fetch_array($result);	
		}
		
		echo "<div id='infomation_content'>";
		echo "<font color=grey><b>업종 &nbsp;</b></font><font size=3> ".$er['er_jobs3']."<br></font>";
		echo "<font color=grey><b>시급 &nbsp;</b></font><font size=3> ".$er['er_money']."원<br></font>";
		echo "<font color=grey><b>기간 &nbsp;</b></font><font size=3> ";
		if($er['er_radio'] == "day") {
			echo $er_day['er_day'];
		}
		else if($er['er_radio'] == "days") {
			echo $er_days['er_startDay']." ~ ".$er_days['er_finishDay'];
		}
		echo "<br></font>";

		echo "<span style='color: gray;display:inline-block;'><b>시간 &nbsp;</b></span>";
		echo "<span style='display:inline-block;vertical-align:top;margin-left:5px;'><font size=3>";
		if($er['er_radio'] == "day") {
			$start_hour = substr($er_day['er_startTime'], 0, 2);
			$start_minute = substr($er_day['er_startTime'], 3, 2);
			$fin_hour = substr($er_day['er_finishTime'], 0, 2);
			$fin_minute = substr($er_day['er_finishTime'], 3, 2);

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
		else if($er['er_radio'] == "days") {
			echo "";
			$d = " ";
			for($i = 3; $i <= 9; $i++) {
				if($er_days[$i] != null) {
					if($i == 3) $d = "월";
					else if($i == 4) $d = "화";
					else if($i == 5) $d = "수";
					else if($i == 6) $d = "목";
					else if($i == 7) $d = "금";
					else if($i == 8) $d = "토";
					else $d = "일";
	
					echo $d."요일 : ".$er_days[$i]."<br>";
				}
			}
		}
		echo "</span></div></font>";
	}


	//employee(나에게 부탁해! 게시판 매칭)
	if($data['a_chk'] == 1) {
		
		echo "<div id='infomation_content'>";
		echo "<font color=gray><b>장소 &nbsp;</b></font><font size=3> ".$a_data['a_area']."<br></font>";
		echo "<font color=gray><b>시급 &nbsp;</b></font><font size=3> ".number_format($a_data['a_etc'])."원<br></font>";
		echo "<font color=gray><b>세부사항 &nbsp;</b></font><font size=3> ".$a_data['a_content']."<br></font>";
		echo "</div>";
	}

?>
<hr color="#dddfe6" height="1px"><br>
<form name="matchingResult">
<!-- applier 명함(지원자의 정보) -->
<div id="cardDiv" onClick="location.href='applyRead.php?a_no=<?php echo $data['a_no']; ?>'">
	<?php
	if(!isset($applier['mem_id'])) {
		echo "<div id='warning'>";
		echo $data['a_applier']." 님이 알바타 사이트를 탈퇴하셨습니다.<br>다른 사람과 매칭을 원하신다면 게시판에 다시 글을 올려주세요.";
		echo "</div>";		
	}
	else {
	?>
		<div>
			<span><img class="writer_img" src="http://14.63.196.104<?php echo $applier['img_path'];?>"/></span>
			<span id="spanRole">
			<?php
				if($data['a_chk'] == 0) {
					echo"<input type=image src='image/arbata.png' style='width:130px; height:35px;' onClick='return false;'>";
				}
				else {
					echo"<input type=image src='image/master.png' style='width:130px; height:40px;' onClick='return false;'>";
				}
			?>
			</span>
		</div>
		<div id="write_info"><b><?php echo $applier['mem_name'];?>
		</b>
		<font style="font-size:13px;">
		<?php
			$year = DATE("Y");
			$tmp = explode('-', $applier['mem_birth']);
			$mem_age= $year-$tmp[0]+1; 
			echo $mem_age."세 / ";

			if($applier['mem_sex'] == "m") {
				echo "남";
			}
			else {
				echo "여";
			}
		?></font></div>
		<div id="write_info2">
		<b>TEL | </b><?php echo $applier['mem_phone'];?><br>
		<b>Email | </b><?php echo $applier['mem_email']; ?><br>
		<?php
		if($data['a_chk'] == 0) { //employerRead에 지원했으면
			echo "<b>주소 | </b>"; // 자신의 주소 표시 (세부주소 제외)
			echo $applier['mem_sido']." ".$applier['mem_gugun']." ".$applier['mem_dong']."<br>";
			echo "<b>경력 | </b>";
			echo $a_data['a_etc']; // 자신의 경력 표시
		}
		else { //employeeRead에 지원했으면
			echo "<b>시급 | </b>"; // 알바 시급 표시
			echo number_format($a_data['a_etc'])."원<br>";
			echo "<b>장소 | </b>";
			echo $a_data['a_area']; // 알바할 장소를 표시
		}
	}
	?>
	</div>
</div>

<?php
if(isset($applier['mem_id'])) {
	if($applier['mem_id']==$id){ // 지원자가 자신일 경우
		echo "";
	}
	else{ // 지원자가 자신이 아닐 경우
		$role = "";
		if($data['a_chk'] == 0) { // 지원자가 지원한 것이 employerRead 이면
			$role = "arbata"; // 지원자는 알바타
		}
		else { // 지원자가 지원한 것이 employeeRead 이면
			$role = "master"; // 지원자는 마스터
		}
		echo "<input type='button' id='reviewBtn' value='후기작성' onclick=location.href='review.php?recvId=".$data['a_applier']."&role=".$role."'>"; // 지원자가 자신이 아니므로 지원자에 후기작성
		echo "<br>";
	}
}
?>

<br><br>

<!-- 작성자 명함 -->
<?php
	if($data['a_chk'] == 0) $url="employerRead.php?er_no=".$data['a_post_no'];
	else if($data['a_chk'] == 1) $url="employeeRead.php?ee_no=".$data['a_post_no'];
?>
<div id="cardDiv" onClick="location.href='<?php echo $url;?>'">
	<?php
	if(!isset($writer['mem_id'])) {
		echo "<div id='warning'>";
		echo $data['a_applier']." 님이 알바타 사이트를 탈퇴하셨습니다.<br>다른 사람과 매칭을 원하신다면 다른 글에 매칭신청을 해주세요.";
		echo "</div>";
	}
	else {
	?>
		<div>
		<span><img class="writer_img" src="http://14.63.196.104<?php echo $writer['img_path'];?>"/></span>
		<span id="spanRole">
		<?php
			if($data['a_chk'] == 0) {
			echo"<input type=image src='image/master.png' style='width:130px; height:40px;' onClick='return false;'>";
		}
		else {
			echo"<input type=image src='image/arbata.png' style='width:130px; height:35px;' onClick='return false;'>";
		}
		?>
		</span>
		</div>
		<div id="write_info"><b><?php echo $writer['mem_name'];?>
		</b>
		<font style="font-size:13px;">
		<?php
			$year = DATE("Y");
			$tmp = explode('-', $writer['mem_birth']);
			$mem_age= $year-$tmp[0]+1; 
			echo $mem_age."세 / ";

			if($writer['mem_sex'] == "m") {
				echo "남";
			}
			else {
				echo "여";
			}
		?></font></div>
		<div id="write_info2">
		<b>TEL | </b><?php echo $writer['mem_phone'];?><br>
		<b>Email | </b><?php echo $writer['mem_email']; ?><br>
		<?php
		if($data['a_chk'] == 0) { //employerBoard에 작성했으면
			$query = "select * from employer where er_no='$a_data[a_post_no]'";
			$result= mysql_query($query, $connect);
			$post_data = mysql_fetch_array($result);
			
			echo "<b>시급 | </b>"; 
			echo number_format($post_data['er_money'])."원<br>";
			echo "<b>장소 | </b>".$post_data['er_sido']." ".$post_data['er_gugun']." ".$post_data['er_dong']." ".$post_data['er_detail']."<br>";
		}
		else { //employeeBoard에 작성했으면
			$query = "select * from employee where ee_no='$a_data[a_post_no]'";
			$result= mysql_query($query, $connect);
			$post_data = mysql_fetch_array($result);
			
			echo "<b>주소 | </b>";
			echo $post_data['ee_sido']." ".$post_data['ee_gugun']." ".$post_data['ee_dong']."<br>";
			echo "<b>경력 | </b>"; 
			echo $post_data['ee_career'];
		}
	}
	?>
	</div>
</div>
<?php
if(isset($writer['mem_id'])) {
	if($writer['mem_id']==$id){ // 자신이 작성자일 때
		echo "";
	}
	else{ // 자신이 지원자일 때
		$role = "";
		if($data['a_chk'] == 0) { // 지원자가 지원한 것이 employerRead 이면
			$role = "master"; // 작성자는 master
		}
		else { // 지원자가 지원한 것이 employeeRead 이면
			$role = "arbata"; // 작성자는 Arbata
		}
		echo "<input type='button' id='reviewBtn' value='후기작성' onclick=location.href='review.php?recvId=".$data['a_writer']."&role=".$role."'><br>"; // 작성자가 자신이 아니므로 작성자에 후기작성
	}
}

if(isset($writer['mem_id']) && isset($applier['mem_id']) ) { // 작성자와 지원자가 존재할 경우
	if($data['a_chk'] == 0){ //employer 매칭일 경우
		echo "<br><span style='float:left; margin-left:20px'>";
		echo "<u><a href='applyRead.php?a_no=".$data['a_no']."'>지원서 상세보기</a></u>";
		echo "</span>";

		echo "<span style='margin-left:10px'>";
		echo "<u><a href='employerRead.php?er_no=".$data['a_post_no']."'>게시글 상세보기</a></u>";

		echo "</span><br>";
	}

	else if($data['a_chk'] == 1){	//employee 매칭일 경우
		echo "<br><span style='float:left; margin-left:20px'>";
		echo "<u><a href='applyRead.php?a_no=".$data['a_no']."'>지원서 상세보기</a></u>";
		echo "</span>";

		echo "<span style=' margin-left:10px'>";
		echo "<u><a href='employeeRead.php?ee_no=".$data['a_post_no']."'>게시글 상세보기 </a></u>";

		echo "</span><br>";
	}
}
?>
<br><hr color="#dddfe6" height="1px">
<div style="margin-left:15px;margin-bottom:15px;margin-top:15px"><font color="gray">
* 저희 사이트는 매칭 이후의 문제에 대해서는 책임지거나 관여하지 않습니다.
</font></div>
</form>
</body>
</html>
<?php
}
mysql_close($connect);
?>