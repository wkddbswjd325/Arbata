<?php
/*
	자신이 아닌 다른 사람의 정보를 볼 때 나타나는 페이지
	게시물이나 매칭페이지를 통해 들어올 수 있다.

	전화번호, 이메일등의 정보를 제외한 개인정보를 볼 수 있고
	해당 사람에 대한 평가 및 별점을 확인 할 수 있다.
*/
include('menuBar.php');
include_once('dbConnect.php');
?>
<html lang="ko">
<head>
<meta charset="UTF-8">
<!-- 모바일 화면의 크기가 고정되게 하는 소스, 가로 스크롤 안생긴다. -->
<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0,
minimum-scale=1.0, width=device-width" />
<link rel="stylesheet" href="inputStyle.css" type="text/css"/>

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
<style type="text/css">
p {	color:#252c41;	}
</style>
<title>멤버 정보 페이지</title>
</head>
<?php

date_default_timezone_set("Asia/Seoul");
$rcvId = $_GET['rcvId'];

$check_query = "select count(*) from member where mem_id='$rcvId'"; 
$check_result = mysql_query($check_query); 
$check_data = mysql_fetch_array($check_result); 

if ( $check_data[0] == 0 ){
	echo "<script>
			alert('".$rcvId."회원님이 알바타를 탈퇴하셨습니다.');
			history.go(-1);
			</script>";
}
else{
	
$query = "select * from member where mem_id='$rcvId'"; 
$result = mysql_query($query, $connect);
$row = mysql_fetch_array($result);

$que = "select * from image where mem_id='$rcvId'";
$res = mysql_query($que, $connect);
$data = mysql_fetch_array($res);

?>
<body bgcolor="#f4f5f9">
<form name="myPage">
 <table width="100%" bgcolor="#f4f5f9" align="center" cellpadding="5" cellspacing="5">
	<tr>
		<td colspan="2"><font style="font-size:14pt;color:#252c41;">
		<strong>
		<?php
			echo $row['mem_id'];
		?></strong></font>
		<img src="image/msg.png" width="23px" onclick="location.href='message.php?msg_sendId=<?php echo $row['mem_id'];?>'">
		</td>
	</tr>
	<tr>
		<td align="center" colspan="2">
			<img src="http://14.63.196.104<?php echo $data['img_path'];?>" width="130px" height="130px" id="myPhoto"><br><br>
		</td>
	</tr>
	<tr>
		<td width="80px"><p><img src="image/id.png" width="20px" valign="absmiddle">
		&nbsp;이름</p></td>
		<td valign="absmiddle" bgcolor="#ffffff" style="border-radius:7px;">
			<p><?php echo $row['mem_name'];?></p>
		</td>
	</tr>
	<tr>
		<td><p><img src="image/sex.png" width="20px" valign="absmiddle">
		&nbsp;나이</p></td>
		<td valign="absmiddle" bgcolor="#ffffff" style="border-radius:7px;">
			<p><?php $year = DATE("Y");
					 $tmp = explode('-', $row['mem_birth']);
					 $mem_age= $year-$tmp[0]+1; 
					 echo $mem_age."세 (";

					 if($row['mem_sex']=='f'){echo "女)";}
					 else{echo "男)";}
				?>
			</p>
		</td>
	</tr>
	<tr>
		<td><p><img src="image/home.png" width="20px" valign="absmiddle">
		&nbsp;주소</p></td>
		<td valign="absmiddle" bgcolor="#ffffff" style="border-radius:7px;">
		<p><?php echo $row['mem_sido']." ".$row['mem_gugun']." ".$row['mem_dong'];?></p></td>
	</tr>
 </table>
 <br>


<?php
$id = $row['mem_id'];

$sql = "select * from review where r_recvID='$id'"; 
$result = mysql_query($sql); 
$n = mysql_num_rows($result); 

if($n==0) {
	echo "<center>후기가 없습니다.</center>";
}

else {
	$sql = "select avg(r_star) from review where r_recvID='$id' order by r_date desc"; 
	$res = mysql_query($sql); 
	$row = mysql_fetch_array($res);

	$star = round($row['avg(r_star)'], 1); 
	?>

	<div id="AvgStarResult"><center>
	<?php
	$count = 0;
	for($i=1; $i<=$star; $i++) {
		echo "<font style='color:#f1404b;'>★</font>";
		$count++;
	}
	if($star != $count) {
		echo "<font style='color:#f1404b;'>☆</font>";
	}
	?>
	</center></div>
	<div id="AvgStar"><b>
		<font style="font-size:12pt;color:#252c41;">평균 별점</font> 
		<font style="font-size:15pt;color:#f1404b;"><?php echo $star;?></font>
		<font style="font-size:12pt;color:#252c41;">점</font></b>
	</div>
	<br><br>
	<?php
	while($rows = mysql_fetch_array($result)) {
		echo "<a href='reviewRead.php?r_no=".$rows['r_no']."'><div id='reviewBoard'>";
		if($rows['r_radio'] == 'arbata')
			echo "<span id='reviewRadio'><font style='font-size:23px;'>A</font>rbata</span>";
		else
			echo "<span id='reviewRadio'><font style='font-size:23px;'>M</font>aster</span>";
		echo "<span id='reviewTitle'><strong>".$rows['r_content']."</strong></span><br>";
		echo "<span id='reviewBody'>".$rows['r_writeId']."&nbsp;|&nbsp;".$rows['r_date']."</span></div></a>";
	}
	echo "<hr color='white'>";
}
?>

</body>
</html>
<?php
	mysql_close($connect); 
}
?>
