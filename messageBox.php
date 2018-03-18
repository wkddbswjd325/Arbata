<?php
/*
	메세지 보관함을 보여주는 페이지
	수신/발신을 나눠서 보여준다.
*/
include_once('dbConnect.php');
?>
<html>
<head>
<title>쪽지 보관함</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, width=device-width" />
<link rel="apple-touch-icon" href="image/apple.jpg" />

<!-- Tell the browser where to find the manifest for your web app -->
<link rel="manifest" href="manifest.json" />

<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.9.0/jquery.js"></script>
<script type="text/JavaScript">
window.addEventListener("load", function() {
	setTimeout(loaded, 100);
}, false);

function loaded(){
	window.scrollTo(0, 1);
}


//전체 체크//
$(function(){
//전체선택 체크박스 클릭
	$("#allCheck").click(function(){
		//만약 전체 선택 체크박스가 체크된상태일경우
		if($("#allCheck").prop("checked")) {
			//해당화면에 전체 checkbox들을 체크해준다
			$("input[type=checkbox]").prop("checked",true);
		// 전체선택 체크박스가 해제된 경우
		} else {
			//해당화면에 모든 checkbox들의 체크를해제시킨다.
			$("input[type=checkbox]").prop("checked",false);
		}
	})
})

//추가...

var didScroll;
var lastScrollTop = 0;
var delta = 5;  // 동작의 구현이 시작되는 위치 
var navbarHeight = $('header').outerHeight();  // 영향을 받을 요소를 선택

// 스크롤했다는 것을 알림
$(window).scroll(function(event) {
	didScroll = true;
});

setInterval(function() {
	if(didScroll) {
		hasScrolled();
		didScroll = false;
	}
}, 250);

function hasScrolled() {
	var st = $(this).scrollTop(); //현재 스크롤위치 저장
	if(Math.abs(lastScrollTop - st) <= delta) //설정한 delta값보다 더 스크롤 되었는지 확인
		return;	
	if(st > lastScrollTop && st > navbarHeight) { //현재위치가 헤더보다
		$('header').removeClass('nav-down').addClass('nav-up');
	} else {
		if( st + $(window).height() < $(document).height() ) {
			$('header').removeClass('nav-up').addClass('nav-down');
		}
	}
	lastScrollTop = st; //현재 스크롤위치 지정
}

</script>

<link rel="stylesheet" href="inputStyle.css" type="text/css" />
<link rel="stylesheet" href="matchBoardStyle.css" type="text/css" />
</head>
<body>
<!--메뉴바 넣어주기 -->
<?php
include('menuBar.php');
?>
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
?>
<?php
	if(!isset($_GET['value'])){
		$v = 0;
	}
	else{
		$v = $_GET['value'];
	}
	
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
?>
<form name="messageBoxForm" method="POST" action="messageRmvDB.php">
<table width="100%" cellspacing="0px" style="border-collapse:collapse;border:5px white solid;table-layout:fixed;">
<tr>
	<td width="40px"><input type="checkbox" id="allCheck" style="margin-top:-10px;margin-left:6px;"></td>
	<td>
		<select name="msg_box" onchange="location.href=this.value" align="left" style="font-size:15px;">
			<option value="messageBox.php?value=1" <?php if($v=="0"||$v=="1"){?>selected<?php }?>>수신쪽지함</option>
			<option value="messageBox.php?value=2" <?php if($v=="2"){?>selected<?php }?>>발신쪽지함</option>
		</select>
	</td>
	<td width="80px">
		<input type="submit" id="remove" value="선택 삭제" style="width:80px;float:right;">
	</td>
</tr>
</table>

<!--메시지들 -->
<?php
	//받은 메세지함
	if($v=="0"||$v=="1"){
		$count = "select count(*) from message where msg_rcvId='$_SESSION[id]' and msg_chk='0' ";
		$res = mysql_query($count);
		$rows1 = mysql_fetch_array($res); 
		$total_count = $rows1['count(*)'];

		$total_page = ceil( $total_count / $list ); 

		if ($b_end_page > $total_page) {
			$b_end_page = $total_page;}

		$limit = ($page - 1) * $list;
		
		$sql = "select msg_no, msg_date, msg_time, msg_title, msg_no, msg_sendId 
				from message 
				where msg_rcvId='$_SESSION[id]' and msg_chk='0' 
				order by msg_no desc, msg_time desc limit $limit,$list";
		$result = mysql_query($sql);
		$rows = mysql_num_rows($result); 
		
		if($block ==1) {
			echo " ";
		}

		if($rows==0) {
			echo "<table bgcolor='#f6f6f6' width='100%'><tr align='center'><td><br>쪽지가 존재하지 않습니다.<br><br></td></tr></table>";
			echo "<br>";
		}
		else {
			while($row = mysql_fetch_array($result)) {
				echo "<a href='rcvMessageRead.php?msg_chk=0&msg_no=".$row['msg_no']."'><div id='divBoard'>";
				echo "<input type='checkbox' id=".$row['msg_no']." name='msg_rcvList[]' value=".$row['msg_no'].">";
				echo "<span id='spanTitle'>".$row['msg_title']. "</span><br>";
				echo "<span id='spanApBody'>".$row['msg_sendId']."</span><span style='float:right;' id='spanBody'>".$row['msg_date']."(".$row['msg_time'].")</span></div></a>";
			}
			echo "<div align='center' style='margin-top:15px;'>";
			if($block ==1) {
				echo " ";
			}
			else{
				echo "<a href='messageBox.php?msg_chk=0&value=1&page=".($b_start_page-1)."'>&nbsp;◀&nbsp;</a>";}

			for($j = $b_start_page; $j <=$b_end_page; $j++){
				if($page == $j){
					echo "<font color=#f1404b>&nbsp;".$j."&nbsp;</font>";
				}
				else{
					echo "<a href='messageBox.php?msg_chk=0&value=1&page=".$j."'>&nbsp;".$j."&nbsp;</a>";
				}          
			}
			$total_block = ceil($total_page/$b_page_list);
			if($block >= $total_block){
				echo " ";
			}
			else{
				echo "<a href='messageBox.php?msg_chk=0&value=1&page=".($b_end_page+1)."'>&nbsp;▶&nbsp;</a>";
			}
		}
		echo "</div>";
		echo "<div align='right' style='margin-right:5px;'><input type='button' id='rcv' value='쪽지 보내기' style='width:95px;' onclick=\"location.href='message.php'\"></div>";
			
	}
	//보낸 메세지함
	else if($v=="2"){
		$count = "select count(*) from message where msg_sendId='$_SESSION[id]' and msg_chk='1'";
		$res = mysql_fetch_array(mysql_query($count));
		$total_count = $res['count(*)'];

		$total_page = ceil( $total_count / $list ); 

		if ($b_end_page > $total_page) {
			$b_end_page = $total_page;}

		$limit = ($page - 1) * $list;

		$sql = "select msg_no, msg_date, msg_time, msg_title, msg_no, msg_rcvId, msg_sendId 
				from message 
				where msg_sendId='$_SESSION[id]' and msg_chk='1' 
				order by msg_no desc, msg_time desc limit $limit,$list";

		$result = mysql_query($sql);
		$rows = mysql_num_rows($result); 

		if($block ==1) {
			echo " ";
		}

		if($rows==0) {
			echo "<table bgcolor='#f6f6f6' width='100%'><tr align='center'><td><br>쪽지가 존재하지 않습니다.<br><br></td></tr></table>";
			echo "<br>";
		}
		else {
		while($row = mysql_fetch_array($result)) {
				echo "<a href='rcvMessageRead.php?msg_chk=1&msg_no=".$row['msg_no']."'><div id='divBoard'>";
				echo "<input type='checkbox' id=".$row['msg_no']." name='msg_sendList[]' value=".$row['msg_no'].">";
				echo "<span id='spanTitle'>".$row['msg_title']."</span><br>";
				echo "<span id='spanApBody'>".$row['msg_rcvId']."</span><span style='float:right;' id='spanBody'>".$row['msg_date']."(".$row['msg_time'].")</span></div></a>";
			}
		echo "<div align='center'; style='margin-top:15px;'>";
		if($block ==1) {
				echo " ";
			}
		else{
			echo "<a href='messageBox.php?msg_chk=1&value=2&page=".($b_start_page-1)."'>&nbsp;◀&nbsp;</a>";}

		for($j = $b_start_page; $j <=$b_end_page; $j++){
			if($page == $j){
				echo "<font color=#f1404b>&nbsp;".$j."&nbsp;</font>";
			}
			else{
				echo "<a href='messageBox.php?msg_chk=1&value=2&page=".$j."'>&nbsp;".$j."&nbsp;</a>";
			}          
		}
		$total_block = ceil($total_page/$b_page_list);
		if($block >= $total_block){
			echo " ";
		}
		else{
			echo "<a href='messageBox.php?msg_chk=1&value=2&page=".($b_end_page+1)."'>&nbsp;▶&nbsp;</a>";
			}
		}
		echo "</div>";
		echo "<div align='right' style='margin-right:5px;'><input type='button' id='rcv' value='쪽지 보내기' style='width:95px;'onclick=\"location.href='message.php'\"></div>";
	}
?>
</form>
</div>
<?php
}
mysql_close($connect);
?>
</body>
</html>