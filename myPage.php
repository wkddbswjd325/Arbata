<?php
/*
	자신의 정보를 볼 수 있는 페이지
	(이름, 나이, 전화번호, 주소, 자신에 대한 평점 등)
	이 페이지에서 editInfo.php로 이동할 수 있고
	matchingBoard.php 와 messageBox.php, saveList.php 로도 이동할 수 있다.
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
<title>마이 페이지</title>
</head>
<?php
//로그인 안됐을 경우..
if(!isset($_SESSION['id']) || !isset($_SESSION['passwd'])) {
?>
<table width="100%" cellpadding="5" cellspacing="0">
	<tr>
		<td>로그인이 필요한 기능 입니다.</td>
	</tr>	
</table>

<!--로그인이 되어있을 때 -->
<?php
}
else {
$query = "select * from member where mem_id='$_SESSION[id]'"; 
$result = mysql_query($query, $connect);
$row = mysql_fetch_array($result);

$que = "select * from image where mem_id='$_SESSION[id]'";
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
			echo $row['mem_name'];
		?></strong>님의 정보</font>
		<img src="image/setting.png" style="width:20px;" align="right" onclick="location.href='editInfo.php'"></td>
	</tr>
	<tr>
		<td align="center" colspan="2">
			<img src="http://14.63.196.104<?php echo $data['img_path'];?>" width="130px" height="130px" id="myPhoto"><br><br>
		</td>
	</tr>
	<tr>
		<td width="85px"><p><img src="image/id.png" width="20px" valign="absmiddle">
		&nbsp;아이디</p></td>
		<td valign="absmiddle" bgcolor="#ffffff" style="border-radius:7px;">
		<p><?php echo($_SESSION['id']);?></p></td>
	</tr>
	<tr>
		<td><p><img src="image/tel.png" width="20px" valign="absmiddle">
		&nbsp;번호</p></td>
		<td valign="absmiddle" bgcolor="#ffffff" style="border-radius:7px;">
		<p><?php echo $row['mem_phone'];?></p></td>
	</tr>
	<tr>
		<td><p><img src="image/home.png" width="20px" valign="absmiddle">
		&nbsp;주소</p></td>
		<td valign="absmiddle" bgcolor="#ffffff" style="border-radius:7px;">
		<p><?php echo $row['mem_sido']." ".$row['mem_gugun']." ".$row['mem_dong'];?></p></td>
	</tr>
 </table>

 <table width="100%" align="center" cellpadding="10" rules="all" style="border:2px solid white;">
	<tr>
		<td width="30%" align="center" onclick="location.href='saveList.php'" style="border:2px solid white;">
			<img src="image/cart.png" style="width:19px;" align="center" >&nbsp;<font style="color:#252c41;font-size:11pt;">보관함</font>
		</td>
		<td width="30%" align="center" onclick="location.href='messageBox.php'" style="border:2px solid white;">
			<img src="image/msg.png" style="width:21px;" align="center" style="vertical-align:absmiddle;">&nbsp;<font style="color:#252c41;font-size:11pt;">쪽지함</font>
		</td>
		<td width="30%" align="center" onclick="location.href='myPostList.php'" style="border:2px solid white;">
			<img src="image/matching.png" style="width:25px;" align="center">&nbsp;<font style="color:#252c41;font-size:11pt;">매칭현황</font>
		</td>
	</tr>
 </table>

<br>

<?php
$id = $_SESSION['id'];

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
