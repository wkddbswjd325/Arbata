<?php
/*
	noticeBoard.php 에서 게시물 번호를 받아온다.
	공지사항을 상세하게 볼 수 있는 페이지
*/
include_once('dbConnect.php');
include('menuBar.php');
?>
<html>
<head>
<title>공지사항 상세보기</title>
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
<?php
$query = "select * from notice where n_no=$_GET[n_no]"; 
$result = mysql_query($query, $connect);
$data = mysql_fetch_array($result);
?>
<form name="noticeRead">
<div id="boardDiv">
<div id="readTitle" align="center">
		<p class="title" style="margin-bottom:5px;"><b><?php echo $data['n_title'];?></b></p>
</div>

<img class="writer_img" src="http://14.63.196.104/arbata/image/female.png"/>
<div id="write_info" align="left" style="padding-bottom:10px">작성자 <b>관리자</b><br>
작성일 <b><?php echo $data['n_writeDate'];?></b></div>

<div id="er_info">
<span id="er_content"> <!--스타일을 위해 이름-->
<?php echo nl2br($data['n_content']);?>
</span>
</div>

</div>
</form>
</div>
</body>
</html>
<?php
	mysql_close($connect);
?>