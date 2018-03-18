<?php
/*
	서로 매칭된 사용자끼리 서로에 대한 평가를 남기게 되는데
	평가의 상세내용을 읽어볼 수 있는 페이지
*/
include('menuBar.php');
include('dbConnect.php');
?>
<html>
<head>
<title>평가 내용 보기</title>
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
$query = "select * from review where r_no=$_GET[r_no]"; 
$result = mysql_query($query, $connect);
$data = mysql_fetch_array($result);

$query = "select img_path from image where mem_id='$data[r_writeId]'"; 
$result = mysql_query($query, $connect);
$img = mysql_fetch_array($result);

?>
<form name="ReviewRead">
<div id="boardDiv">
<span id="reviewRadioNotice">
<br>
<?php 
if($data['r_radio'] == 'arbata')
	echo "이 글은 고용인이 알바타에게 남긴 후기입니다.";
else
	echo "이 글은 알바타가 고용인에게 남긴 후기입니다.";
?>
</span>
<br><br>
<img class="writer_img" src="http://14.63.196.104/<?php echo $img['img_path'];?>" onClick="location.href='memPage.php?rcvId=<?php echo $data['r_writeId']; ?>'"/>
<div id="write_info" align="left" style="padding-bottom:10px">작성자 <b><a href="memPage.php?rcvId=<?php echo $data['r_writeId']; ?>"><?php echo $data['r_writeId'];?></a></b><br>
작성일 <b><?php echo $data['r_date']." ".$data['r_time'];?></b></div>

<div id="er_info">
<span id="er_content"> <!--스타일을 위해 이름-->
<?php echo nl2br($data['r_content']);?>
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