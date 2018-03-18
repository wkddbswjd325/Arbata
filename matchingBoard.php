<?php
/*
	자신이 올린 게시물의 지원 신청 현황을 보여주는 페이지 
	myPostList 에서 글 번호를 받아와서 해당 글의 지원 신청 현황을 보여준다.
*/
include_once('dbConnect.php');
date_default_timezone_set("Asia/Seoul");
$year = DATE("Y");
?>
<html>
<head>
<title>매칭 게시판</title>
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
<link rel="stylesheet" href="msgStyle.css" type="text/css" />
</head>
<body>
<br>
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
	$a_writer = $_SESSION['id'];
	$a_post_no = $_GET['a_post_no'];
	$a_chk = $_GET['a_chk'];

	$sql = "select * from apply where a_writer='$a_writer' and a_post_no='$a_post_no' and a_chk='$a_chk' order by a_no";
	$result = mysql_query($sql);
	$rows = mysql_num_rows($result);

	if($rows==0) {
		echo "<br><table bgcolor='#f6f6f6' width='100%'><tr align='center'><td><br>현재 신청자가 없습니다.<br><br></td></tr></table><br>";
	}
	else {
?>

<div align="right" style='padding-right:5px;font-weight:bold;'>총 <font style="color:#f1404b"><?php echo $rows;?></font>명이 신청했습니다</div>
<span style="line-height:5%"><br></span>

<!--매칭 내용들-->
<ul id='msgUl'>
	<?php
	while($row = mysql_fetch_array($result)) {
		$m_sql = "select * from member where mem_id='$row[a_applier]'";
		$m_result = mysql_query($m_sql);
		$m_row = mysql_fetch_array($m_result);
		$sql2 = "select * from image where mem_id='$m_row[mem_id]'";
		$res = mysql_query($sql2);
		$data = mysql_fetch_array($res);
		echo "<li id='msgLi'>";
		echo "<div>";
		echo "<span style='float:left;'><img src='http://14.63.196.104".$data['img_path']."' width='40px' height='40px' id='myPhoto'></span>";
		echo "<span id='matchingTitle'>&nbsp;<a href='applyRead.php?a_no=".$row['a_no']."'>".$row['a_title']."</a></span>";
		echo "<span style='float:right;'><input type='button' id='matchingRequest' value='매칭' onclick='var result = confirm(\"매칭 하시겠습니까?\");
			  if(result) 
				 location.href=\"matchingDB.php?a_no=".$row['a_no']."\";'/></span>";
		echo "</div>";
		echo "<span id='noticeDate'>&nbsp;&nbsp;".$m_row['mem_name']."</span><!--이름";
		$tmp = explode('-', $m_row['mem_birth']);
		$mem_age= $year-$tmp[0]+1;
		echo "--><span id='noticeDate'>(".$mem_age.")</span>&nbsp;|&nbsp;<!--나이";
		echo "--><span span id='noticeDate'>".$row['a_date']."</span><!--매칭 신청 날짜-->";
		echo "</span>";
		echo "</li>";
	}
	?>
</ul>
<?php
	}

	//employer 게시판 신청일 때
	if($a_chk==0) {
	?>
	<div align="right" style='padding-right:5px;'><a href="employerRead.php?er_no=<?php echo $a_post_no;?>"><u>해당 게시물 보기</u></a></div>
	<?php
	} //employee 게시판 신청일 때 
	else if($a_chk==1) {
	?>
	<div align="right" style='padding-right:5px;'><a href="employeeRead.php?ee_no=<?php echo $a_post_no;?>"><u>해당 게시물 보기</u></a></div>
<?php
	}
}
mysql_close($connect);
?>
</body>
</html>