<?php
/*
	쇼핑몰 사이트의 '장바구니' 기능처럼
	'대타를 부탁해' 게시판이나 '나에게 부탁해' 게시판에 올라온 게시물을 
	나중에 볼 수 있도록 담아놓을 수 있도록 한 페이지
*/
include_once('dbConnect.php');
?>
<html>
<head>
<title>보관함</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, width=device-width" />
<link rel="apple-touch-icon" href="image/apple.jpg" />

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
</script>

<link rel="stylesheet" href="inputStyle.css" type="text/css" />
<link rel="stylesheet" href="matchBoardStyle.css" type="text/css" />
</head>

<body>
<!--메뉴바 넣어주기 -->
<?php
	include('menuBar.php');

	if(!isset($_GET['value'])){
		$v = 0;
	}
	else{
		$v = $_GET['value'];
	}
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
		$s_id = $_SESSION['id'];
?>

<form name="saveListForm" method="POST" action="saveListRmvDB.php">
<table width="100%" cellspacing="0px" style="border-collapse:collapse;border:5px white solid;table-layout:fixed;">
<tr>
	<td width="40px"><input type="checkbox" id="allCheck" style="margin-top:-8px;margin-left:7px;"></td>
    <td>
		<select name="saveList" onchange="location.href=this.value" align="left" style="font-size:15px;">
			<option value="saveList.php?value=1" <?php if($v=="0"||$v=="1"){?>selected<?php }?>>대타를 부탁해!</option>
			<option value="saveList.php?value=2" <?php if($v=="2"){?>selected<?php }?>>나에게 부탁해!</option>
		</select>
	</td>
	<td width="80px">
		<input type="submit" id="remove" value="선택 삭제" style="width:80px;float:right;">
	</td>
</tr>
</table>

<?php
	// '대타를 부탁해' 게시물 보관 리스트
	if($v=="0"||$v=="1"){

		$sql = "select * from savelist where s_id='$s_id' and s_chk='0' order by s_no desc";
		$result = mysql_query($sql);
		$rows = mysql_num_rows($result);

		if($rows==0) {
			echo "<table bgcolor='#f6f6f6' width='100%'><tr align='center'><td><br>보관중인 게시물이 없습니다.<br><br></td></tr></table>";
		}

		else {
			while($row = mysql_fetch_array($result)) {
				$sql2 = "select * from employer where er_no='$row[e_no]'";
				$result2 = mysql_query($sql2);
				$rows2 = mysql_fetch_array($result2);

				if(!$rows2['er_no']) {
					echo "<div id='divBoard'>";
					echo "<input type='checkbox' style='margin-top:6px;' name='saveList1[]' value=".$row['s_no']." id=".$row['s_no'].">";
					echo "<span id='spanTitle'><strong>삭제된 게시물입니다.</strong></span><br></div>";
				}
				else {
					echo "<a href='employerRead.php?er_no=".$rows2['er_no']."'><div id='divBoard'>";
					echo "<input type='checkbox' name='saveList1[]' value=".$row['s_no']." id=".$row['s_no'].">";
					echo "<span id='spanTitle'><strong>".$rows2['er_title']. "</strong></span><br>";
					echo "<span id='spanApBody'>".number_format($rows2['er_money'])."원&nbsp;|&nbsp;".$rows2['er_sido']." ".$rows2['er_gugun']." ".$rows2['er_dong']."&nbsp;|&nbsp;".str_replace("-","/",substr($rows2['er_writeDay'], 5))."</span></div></a>";
				}
			}
		}
	}

	// '나에게 부탁해' 게시물 보관 리스트
	else if($v=="2"){

		$sql = "select * from savelist where s_id='$s_id' and s_chk='1' order by s_no desc";
		$result = mysql_query($sql);
		$rows = mysql_num_rows($result);

		if($rows==0) {
			echo "<table bgcolor='#f6f6f6' width='100%'><tr align='center'><td><br>보관중인 게시물이 없습니다.<br><br></td></tr></table>";
		}

		else {
			while($row = mysql_fetch_array($result)) {

				$sql2 = "select * from employee where ee_no='$row[e_no]'";
				$result2 = mysql_query($sql2);
				$rows2 = mysql_fetch_array($result2);
				
				if(!$rows2['ee_no']) {
					echo "<div id='divBoard'>";
					echo "<input type='checkbox' name='saveList1[]' style='margin-top:6px;' value=".$row['s_no']." id=".$row['s_no'].">";
					echo "<span id='spanTitle'><strong>삭제된 게시물입니다.</strong></span><br></div>";
				}
				else {
					echo "<a href='employeeRead.php?ee_no=".$rows2['ee_no']."'><div id='divBoard'>";
					echo "<input type='checkbox' name='saveList2[]' value=".$row['s_no']." id=".$row['s_no'].">";
					echo "<span id='spanTitle'><strong>".$rows2['ee_title']. "</strong></span><br>";
					echo "<span id='spanApBody'>".$rows2['ee_sido']." ".$rows2['ee_gugun']." ".$rows2['ee_dong'].
						"&nbsp;|&nbsp;".str_replace("-","/",substr($rows2['ee_writeDay'], 5))."</span></div></a>";
				}
			}
		}
	}
?>
</form>
<?php
}
mysql_close($connect);
?>

</body>
</html>