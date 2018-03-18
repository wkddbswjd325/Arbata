<?php
/*
	공지사항 페이지
*/
extract($_GET);
extract($_POST);
extract($_SERVER);

include_once('dbConnect.php');
include('menuBar.php');
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8;" />
<!-- 모바일 화면의 크기가 고정되게 하는 소스, 가로 스크롤 안생긴다. -->
<meta name="viewport" content="user-scalable=1, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, width=device-width, target-densitydpi=medium-dpi"  />

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
<title>공지사항</title>
<link rel="stylesheet" href="inputStyle.css" type="text/css" />
<link rel="stylesheet" href="boardStyle.css" type="text/css" />
<link rel="stylesheet" href="msgStyle.css" type="text/css" />
</head>
<?php
//현재 페이지 쪽
if(!isset($_GET['page']))
	$page = 1;
else
	$page = $_GET['page'];

//한 페이지에 몇 개의 글
$list = 8;

//한 블럭당 몇개 링크
$b_page_list = 3;
//현재 블럭
$block = ceil($page/$b_page_list);

//현재 블럭에서 시작페이지 번호
$b_start_page = ( ($block - 1) * $b_page_list ) + 1; 
//현재 블럭에서 마지막 페이지 번호
$b_end_page = $b_start_page + $b_page_list - 1; 

$count = "select count(*) from notice";

$res = mysql_fetch_array(mysql_query($count));
$total_count = $res['count(*)'];

$total_page = ceil( $total_count / $list ); 

if ($b_end_page > $total_page) {
	$b_end_page = $total_page;
}

$limit = ($page - 1) * $list;
$sql = "select * from notice order by n_no desc limit $limit,$list ";
$result = mysql_query($sql);
?>
<body>
<br>
<b style='margin-left:10px;'><font color="#252c41">공지사항</font></b>
<br>
<span style="line-height:5%"><br><br></span>
<?php
$rows = mysql_num_rows($result); 
if($rows==0) {
	echo "<table bgcolor='#f6f6f6' width='100%'><tr align='center'><td><br>현재 올라온 공지사항이 없습니다.<br><br></td></tr></table>";
}

else {
	echo "<ul id='msgUl'>";
	while($row = mysql_fetch_array($result)) {
		echo "<a href='notice.php?n_no=".$row['n_no']."'><li id='msgLi'>";
		echo "<div id='noticeT'>".$row['n_title']. "<br>";
		echo "<span id='noticeDate'>".$row['n_writeDate']."</span>";
		echo "</div>";
		echo "</li></a>";
	}
	echo "</ul>";
}
?>

<!-- 페이지 계산 -->
<br>
<div align="center">
<font style="font-size:20px;color:#252c41">
<?php 
if($block == 1) {
	echo " ";
}
else {
	echo "<a href='noticeBoard.php?page=".($b_start_page-1)."'>&nbsp;◀&nbsp;</a>";
}

for($j = $b_start_page; $j <= $b_end_page; $j++)
{
	if($page == $j)
	{
		echo "<font color=#f1404b>&nbsp;".$j."&nbsp;</font>";
	}
	else{
		echo "<a href='noticeBoard.php?page=".$j."'>&nbsp;".$j."&nbsp;</a>";
	}
}

$total_block = ceil($total_page/$b_page_list);

if($block >= $total_block){
	echo " ";
}
else {  
	echo "<a href='noticeBoard.php?page=".($b_end_page+1)."'>&nbsp;▶&nbsp;</a>";
}
?>
</font>
</div>
</body>
</html>
<?php
	mysql_close($connect);
?>