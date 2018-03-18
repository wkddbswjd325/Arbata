<?php
/*
	applyWrite_ee 또는 _er 를 통해 지원한 지원서를
	해당 게시글을 올린 사용자가 지원서를 볼 수 있게 만든 페이지
*/
include_once('dbConnect.php');
?>
<html>
<head>
<title>지원서 읽기</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, width=device-width" />
<link rel="apple-touch-icon" href="image/apple.jpg" />
<script type="text/javascript">
window.addEventListener("load", function() {
	setTimeout(loaded, 100);
}, false);

function  loaded() {
	window.scrollTo(0, 1);
}
</script>
<link rel="stylesheet" href="inputStyle.css" type="text/css" />
<link rel="stylesheet" href="boardStyle.css" type="text/css" />
</head>
<body>
<div align="center">
<!--메뉴바 넣어주기 -->
<?php
include('menuBar.php');
?>
</div>
<!--로그인이 되지 않았을 때 -->
<?php
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
$query = "select * from apply where a_no='$_GET[a_no]'"; 
$result = mysql_query($query, $connect);
$row = mysql_fetch_array($result);

$query = "select img_path from image where mem_id='$row[a_applier]'"; 
$result = mysql_query($query, $connect);
$pic = mysql_fetch_array($result);
?>

<form name="ApplyReadForm" method="POST" action="matchingDB.php?a_no=<?php echo $row['a_no'];?>">
<div id="boardDiv">
<div id="readTitle" align="center">
	<p class="title" style="margin-bottom:5px;"><b><?php echo $row['a_title'];?></b></p>
</div>

<?php 

// 지원자가 자신이라면.. 사진을 눌렀을 때 내 정보로
if($row['a_applier'] == $_SESSION['id']) { ?>
	<img class="writer_img" src="http://14.63.196.104/<?php echo $pic['img_path'];?>" onClick="location.href='myPage.php?rcvId=<?php echo $row['a_applier']; ?>'"/>
<?php
} 

// 지원자가 남이라면.. 사진을 눌렀을 때 남의 정보로
else{ ?>
	<img class="writer_img" src="http://14.63.196.104/<?php echo $pic['img_path'];?>" onClick="location.href='memPage.php?rcvId=<?php echo $row['a_applier']; ?>'"/>
<?php
}
?>

<div id="write_info">지원자 

<?php 

// 지원자가 자신이라면.. 아이디를 눌렀을 때 내 정보로
if($row['a_applier'] == $_SESSION['id']) { 
	echo "<b><a href='myPage.php?rcvId=".$row['a_applier']."'>";
	echo $row['a_applier']."</a></b><br>";}


// 지원자가 남이라면.. 아이디를 눌렀을 때 남의 정보로, 메세지를 누르면 메세지 보내는 페이지로
else{ 
	echo "<b><a href='memPage.php?rcvId=".$row['a_applier']."'>";
	echo $row['a_applier']."</a></b>";
?>
<img src="image/msg.png" width="18px" valign="absmiddle" onclick="location.href='message.php?msg_sendId=<?php echo $row['a_applier'];?>'"><br>
<?php } ?>

지원일 <b><?php echo $row['a_date']." ".$row['a_time'];?></b>
</div>

<div id="er_info">
<?php
	if($row['a_chk'] == 0){ // employerRead에 지원
		echo "<span id='er_info_title' style='vertical-align:top;'>경력</span>";
		echo "&nbsp;<span id='er_info_content'>".nl2br($row['a_etc'])."</span></div>";
	}
	else if($row['a_chk'] == 1){ //employeeRead에 지원
		echo "<span id='er_info_title'>시급</span>";
		echo "&nbsp;<span id='er_info_content'>". number_format($row['a_etc'])."원</span></div>";
		echo "<div id='er_info'><span id='er_info_title'>장소</span>";
		echo "<span id='er_info_content'>".$row['a_area']."</span></div>";
	}
?>

<div id="er_info">
<span id="er_content">
<?php echo nl2br($row['a_content']);?>
</span>
</div>
<div align="center">
<input type="submit" value="매칭" name="matchingButton" id="readEdit" style="width:20%;font-size:17px;padding: 5px 0px;">
</div>
</div>
</form>

<?php
}
mysql_close($connect);
?>
</body>
</html>