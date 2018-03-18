<?php
/*
	'대타를 부탁해'와 '나에게 부탁해' 게시판에 자신이 작성한 게시물을 볼 수 있고
	자신이 매칭 신청한 게시물들을 모아볼 수 있다.
*/
include_once('dbConnect.php');
?>
<html>
<head>
<title>나의 매칭현황</title>
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
	$list = 10;

	//한 블럭당 몇개 링크
	$b_page_list = 3;
	//현재 블럭
	$block = ceil($page/$b_page_list);

	//현재 블럭에서 시작페이지 번호
	$b_start_page = ( ($block - 1) * $b_page_list ) + 1; 
	//현재 블럭에서 마지막 페이지 번호
	$b_end_page = $b_start_page + $b_page_list - 1;
?>
<form name="myPostListForm" method="POST" action="myPostListRmvDB.php">
<table width="100%" cellspacing="0px" style="border-collapse:collapse;border:5px white solid;table-layout:fixed;">
<tr>
	<td align="left">
		<select name="msg_box"  onchange="location.href=this.value" style="font-size:15px;">
			<option value="myPostList.php?value=1" <?php if($v=="0"||$v=="1"){?>selected<?php }?>>내가 올린 대타를 부탁해!</option>
			<option value="myPostList.php?value=2" <?php if($v=="2"){?>selected<?php }?>>내가 올린 나에게 부탁해!</option>
			<option value="myPostList.php?value=3" <?php if($v=="3"){?>selected<?php }?>>내가 매칭 신청한 게시물</option>
		</select>
	</td>
	<td width="90px">
		<input type="submit" id="remove" value="선택 삭제" style="width:80px;float:right;">
	</td>
</tr>
</table>
<span style='line-height:5%'></span>

<!-- 게시물들 -->
<?php

	//'대타를 부탁해!'에 올린 게시물
	if($v=="0"||$v=="1"){
		$count = "select count(*) from employer where er_writer='$_SESSION[id]' ";
		$res = mysql_query($count);
		$rows1 = mysql_fetch_array($res); 
		$total_count = $rows1['count(*)'];

		$total_page = ceil( $total_count / $list ); 

		if ($b_end_page > $total_page) {
			$b_end_page = $total_page;}

		$limit = ($page - 1) * $list;
		
		$sql = "select er_writeDay, er_title, er_no, er_writer, er_money, er_sido, er_gugun, er_dong, e_stmt
				from  employer
				where er_writer='$_SESSION[id]' 
				order by er_writeDay desc limit $limit,$list";
		$result = mysql_query($sql);
		
		if($block==1) {
			echo " ";
		}

		if($total_count==0) {
			echo "<table bgcolor='#f6f6f6' width='100%'><tr align='center'><td><br>게시물이 존재하지 않습니다.<br><br></td></tr></table>";		
		}
		else {
			$rows = mysql_num_rows($result);
			while($row = mysql_fetch_array($result)) {
				if($row['e_stmt'] == 0){	//진행중일 경우
					echo "<a href='matchingBoard.php?a_post_no=".$row['er_no']."&a_chk=0'><div id='divBoard'>";
					echo "<input type='checkbox' onClick='alert(\"진행중 삭제를 요청하면 매칭신청이 취소됩니다.\");' 
					id=".$row['er_no']." name='erPostList[]' value=".$row['er_no'].">";
					echo "<div>";
					echo "<span id='spanApTitle'>".$row['er_title']."</span>";
					echo "<span style='float:right; margin-right:5; margin-top:6;'><input type=button id='stmt0Btn' value='진행'/></span><br>";
					echo "</div>";
					echo "<span id='spanApBody'>".$row['er_sido']." ".$row['er_gugun']." ".$row['er_dong']."&nbsp;|&nbsp;".str_replace("-","/",substr($row['er_writeDay'], 5))."</span></div></a>";
				}
				else{	//성공했을 경우
					$sql_m = "select mat_no from matching where a_post_no='$row[er_no]' and a_chk='0'";
					$result_m = mysql_query($sql_m);
					$row_m = mysql_fetch_array($result_m);

					echo "<a href='matchingResult.php?mat_no=".$row_m['mat_no']."'><div id='divBoard'>";
					echo "<input type='checkbox' onClick='this.checked=false;alert(\"매칭에 성공한 게시물은 삭제가 불가능 합니다.(매칭성공 한달이 지나면 자동 삭제됩니다.)\");'>";
					echo "<div>";
					echo "<span id='spanApTitle'>".$row['er_title']. "</span>";
					echo "<span style='float:right; margin-right:5; margin-top:6;'><input type=button id='stmt1Btn' value='성공'/></span><br>";
					echo "</div>";
					echo "<span id='spanApBody'>".$row['er_sido']." ".$row['er_gugun']." ".$row['er_dong']."&nbsp;|&nbsp;".str_replace("-","/",substr($row['er_writeDay'], 5))."</span></div></a>";
				}
			}
			echo "<br>";
			echo "<div align='center'>";
			if($block ==1) {
				echo " ";
			}
			else{
				echo "<a href='myPostList.php?value=1&page=".($b_start_page-1)."'>&nbsp;◀&nbsp;</a>";}

			for($j = $b_start_page; $j <=$b_end_page; $j++){
				if($page == $j){
					echo "<font color=#f1404b>&nbsp;".$j."&nbsp;</font>";
				}
				else{
					echo "<a href='myPostList.php?value=1&page=".$j."'>&nbsp;".$j."&nbsp;</a>";
				}          
			}
			$total_block = ceil($total_page/$b_page_list);
			if($block >= $total_block){
				echo " ";
			}
			else{
				echo "<a href='myPostList.php?value=1&page=".($b_end_page+1)."'>&nbsp;▶&nbsp;</a>";}
		}
			
	}

	//'나에게 부탁해!'에 올린 게시물
	else if($v=="2"){
		$count = "select count(*) from employee where ee_writer='$_SESSION[id]' ";
		$res = mysql_query($count);
		$rows1 = mysql_fetch_array($res); 
		$total_count = $rows1['count(*)'];

		$total_page = ceil( $total_count / $list ); 

		if ($b_end_page > $total_page) {
			$b_end_page = $total_page;}

		$limit = ($page - 1) * $list;
		
		$sql =  "select ee_writeDay, ee_title, ee_no, ee_writer, ee_sido, ee_gugun, ee_dong, e_stmt
				from  employee
				where ee_writer='$_SESSION[id]' 
				order by ee_writeDay desc limit $limit,$list";
		$result = mysql_query($sql);

		if($block == 1) {
			echo " ";
		}

		if($total_count==0) {
			echo "<table bgcolor='#f6f6f6' width='100%'><tr align='center'><td><br>게시물이 존재하지 않습니다.<br><br></td></tr></table>";
		}
		else {
			$rows = mysql_num_rows($result);
			while($row = mysql_fetch_array($result)) {	
				if($row['e_stmt'] == 0){	
					echo "<a href='matchingBoard.php?a_post_no=".$row['ee_no']."&a_chk=1'><div id='divBoard'>";
					echo "<input type='checkbox' onClick='alert(\"진행중 삭제를 요청하면 매칭신청이 취소됩니다.\");'
					id=".$row['ee_no']." name='eePostList[]' value=".$row['ee_no'].">";
					echo "<div>";
					echo "<span id='spanApTitle'>".$row['ee_title']."</span>";
					echo "<span style='float:right; margin-right:5; margin-top:6;'><input type=button id='stmt0Btn' value='진행'/></span><br>";
					echo "</div>";
					echo "<span style='margin-left:35px;' id='spanBody'>".$row['ee_sido']." ".$row['ee_gugun']." ".$row['ee_dong']."&nbsp;|&nbsp;".str_replace("-","/",substr($row['ee_writeDay'], 5))."</span></div></a>";
				}
				else{
					$sql_m = "select mat_no from matching where a_post_no='$row[ee_no]' and a_chk='1'";
					$result_m = mysql_query($sql_m);
					$row_m = mysql_fetch_array($result_m);

					echo "<a href='matchingResult.php?mat_no=".$row_m['mat_no']."'><div id='divBoard'>";
					echo "<input type='checkbox' onClick='this.checked=false;alert(\"매칭에 성공한 게시물은 삭제가 불가능 합니다.(매칭성공 한달이 지나면 자동 삭제됩니다.)\");'>";
					echo "<div>";
					echo "<span id='spanApTitle'>".$row['ee_title']. "</span>";
					echo "<span style='float:right; margin-right:5; margin-top:6;'><input type=button id='stmt1Btn' value='성공'/></span><br>";
					echo "</div>";
					echo "<span style='margin-left:35px;' id='spanBody'>".$row['ee_sido']." ".$row['ee_gugun']." ".$row['ee_dong']."&nbsp;|&nbsp;".str_replace("-","/",substr($row['ee_writeDay'], 5))."</span></div></a>";
				}
			}
			echo "<br>";
			echo "<div align='center'>";
		if($block ==1) {
				echo " ";
			}
		else{
			echo "<a href='myPostList.php?value=2&page=".($b_start_page-1)."'>&nbsp;◀&nbsp;</a>";}

		for($j = $b_start_page; $j <=$b_end_page; $j++){
			if($page == $j){
				echo "<font color=#f1404b>&nbsp;".$j."&nbsp;</font>";
			}
			else{
				echo "<a href='myPostList.php?value=2&page=".$j."'>&nbsp;".$j."&nbsp;</a>";
			}          
		}
		$total_block = ceil($total_page/$b_page_list);
		if($block >= $total_block){
			echo " ";
		}
		else{
			echo "<a href='myPostList.php?value=2&page=".($b_end_page+1)."'>&nbsp;▶&nbsp;</a>";}
		}
	}

	//내가 매칭 신청한 게시물
	else if($v=="3"){
		$count = "select count(*) from apply where a_applier='$_SESSION[id]'";
		$res = mysql_query($count);
		$rows1 = mysql_fetch_array($res); 
	
		$total_count = $rows1['count(*)'];

		$total_page = ceil( $total_count / $list ); 

		if ($b_end_page > $total_page) {
			$b_end_page = $total_page;}

		$limit = ($page - 1) * $list;

		if($block == 1) {
			echo " ";
		}
		
		$sql =  "select a_post_no, a_chk
				from  apply
				where a_applier='$_SESSION[id]'
				order by a_date desc, a_time desc limit $limit,$list";
		$result = mysql_query($sql);

		if($total_count==0) {
			echo "<table bgcolor='#f6f6f6' width='100%'><tr align='center'><td><br>게시물이 존재하지 않습니다.<br><br></td></tr></table>";
		}
		else{
			while($post_chk = mysql_fetch_array($result)) {

				if($post_chk['a_chk'] == 0){
					$sql2 =  "select er_writeDay, er_title, er_no, er_writer, er_money, er_sido, er_gugun, er_dong, e_stmt
								from  employer
								where er_no='$post_chk[a_post_no]'"; 
					$result2 = mysql_query($sql2);
					$rows2 = mysql_num_rows($result2);
					$row2 = mysql_fetch_array($result2);

					if($row2['er_no']==""){
						$delete_sql = "DELETE FROM apply WHERE a_post_no=$post_chk[a_post_no] and a_chk='0'"; 
						mysql_query($delete_sql);
					}
					else{
						if($row2['e_stmt']=="0"){
							echo "<div id='divBoard'>";
							echo "<input type='checkbox' onClick='alert(\"진행중 삭제를 요청하면 매칭신청이 취소됩니다.\");'
							id=".$row2['er_no']." name='erApplyList[]' value=".$row2['er_no'].">";
							echo "<a href='employerRead.php?er_no=".$row2['er_no']."'><div>";
							echo "<span id='spanApTitle'>".$row2['er_title']."</span>";
							echo "<span style='float:right; margin-right:5; margin-top:6;'><input type=button id='stmt0Btn' value='진행'/></span><br>";
							echo "<span id='spanApBody'>".$row2['er_sido']." ".$row2['er_gugun']." ".$row2['er_dong']."&nbsp;|&nbsp;".str_replace("-","/",substr($row2['er_writeDay'], 5))."</span></div></a></div>";
						}
						else{
							$sql3 = "select a_applier, mat_no
								from  matching
								where a_post_no='$row2[er_no]' and a_chk='0'";
							$result3 = mysql_query($sql3);
							$row3 = mysql_fetch_array($result3);
							if($row3['a_applier'] == $_SESSION['id']){
								echo "<div id='divBoard'>";
								echo "<input type='checkbox' onClick='this.checked=false;alert(\"매칭에 성공한 게시물은 삭제가 불가능 합니다.(매칭성공 한달이 지나면 자동 삭제됩니다.)\");'>";
								echo "<a href='matchingResult.php?mat_no=".$row3['mat_no']."'><div>";
								echo "<span id='spanApTitle'>".$row2['er_title']."</span>";
								echo "<span style='float:right; margin-right:5; margin-top:6;'><input type=button id='stmt1Btn' value='성공'/></span><br>";
							}
							else{
								echo "<div id='divBoard'>";
								echo "<input type='checkbox' id=".$row2['er_no']." name='erApplyList[]' value=".$row2['er_no'].">";
								echo "<a href='#' onclick='alert(\"이미 다른 신청자와 매칭이 완료된 게시물 입니다.\")'><div>";
								echo "<span id='spanApTitle'>".$row2['er_title']."</span>";
								echo "<span style='float:right; margin-right:5; margin-top:6;'><input type=button id='stmt2Btn' value='실패'/></span><br>";
							}
							echo "<span id='spanApBody'>".$row2['er_sido']." ".$row2['er_gugun']." ".$row2['er_dong']."&nbsp;|&nbsp;".str_replace("-","/",substr($row2['er_writeDay'], 5))."</span></div></a>";
							echo "</div>";
						}
					}
				}
				else if($post_chk['a_chk'] == 1){
					$sql2 = "select ee_writeDay, ee_title, ee_no, ee_writer, ee_sido, ee_gugun, ee_dong, e_stmt
								from  employee
								where ee_no='$post_chk[a_post_no]'";
					$result2 = mysql_query($sql2);
					$rows2 = mysql_num_rows($result2);
					$row2 = mysql_fetch_array($result2);

					if($row2['ee_no']==""){
						$delete_sql = "DELETE FROM apply WHERE a_post_no=$post_chk[a_post_no] and post_chk='1'"; 
						mysql_query($delete_sql);
					}
					else{
						if($row2['e_stmt']=="0"){
							echo "<div id='divBoard'>";
							echo "<input type='checkbox' onClick='alert(\"진행중 삭제를 요청하면 매칭신청이 취소됩니다.\")'
							id=".$row2['ee_no']." name='eeApplyList[]' value=".$row2['ee_no'].">";
							echo "<a href='employeeRead.php?ee_no=".$row2['ee_no']."'><div>";
							echo "<span id='spanApTitle'>".$row2['ee_title']. "</span>";
							echo "<span style='float:right; margin-right:5; margin-top:6;'><input type=button id='stmt0Btn' value='진행'/></span><br>";
							echo "<span id='spanApBody'>".$row2['ee_sido']." ".$row2['ee_gugun']." ".$row2['ee_dong']."&nbsp;|&nbsp;".str_replace("-","/",substr($row2['ee_writeDay'], 5))."</span></div></a></div>";

						}
						else{
							$sql3 = "select a_applier, mat_no
								from  matching
								where a_post_no='$row2[ee_no]' and a_chk='1'";
							$result3 = mysql_query($sql3);
							$row3 = mysql_fetch_array($result3);

							if($row3['a_applier'] == $_SESSION['id']){
								$sql_a = "select a_no from apply where a_post_no='$row2[ee_no]' and a_chk='1'";
								$result_a = mysql_query($sql_a);
								$row_a = mysql_fetch_array($result_a);
								$sql_m = "select mat_no from matching where a_no='$row_a[a_no]'";
								$result_m = mysql_query($sql_m);
								$row_m = mysql_fetch_array($result_m);

								echo "<div id='divBoard'>";
								echo "<input type='checkbox' onClick='this.checked=false;alert(\"매칭에 성공한 게시물은 삭제가 불가능 합니다.(매칭성공 한달이 지나면 자동 삭제됩니다.)\");'>";
								echo "<a href='matchingResult.php?mat_no=".$row3['mat_no']."'><div>";
								echo "<span id='spanApTitle'>".$row2['ee_title']."</span>";
								echo "<span style='float:right; margin-right:5; margin-top:6;'><input type=button id='stmt1Btn' value='성공'/></span><br>";
							}
							else{
								echo "<div id='divBoard'>";
								echo "<input type='checkbox' id=".$row2['ee_no']." name='eeApplyList[]' value=".$row2['ee_no'].">";
								echo "<a href='#' onclick='alert(\"이미 다른 신청자와 매칭이 완료된 게시물 입니다.\")'><div>";
								echo "<span id='spanApTitle'>".$row2['ee_title']."</span>";
								echo "<span style='float:right; margin-right:5; margin-top:6;'><input type=button id='stmt2Btn' value='실패'/></span><br>";
							}
							echo "<span id='spanApBody'>".$row2['ee_sido']." ".$row2['ee_gugun']." ".$row2['ee_dong']."&nbsp;|&nbsp;".str_replace("-","/",substr($row2['ee_writeDay'], 5))."</span></div></a>";
							echo "</div>";
						}
					}
				}
			}//while끝
		} //else끝
		echo "<br>";
		echo "<div align='center'>";
		if($block ==1) {
				echo " ";
		}
		else{
			echo "<a href='myPostList.php?value=3&page=".($b_start_page-1)."'>&nbsp;◀&nbsp;</a>";
		}

		for($j = $b_start_page; $j <=$b_end_page; $j++){
			if($page == $j){
				echo "<font color=#f1404b>&nbsp;".$j."&nbsp;</font>";
			}
			else{
				echo "<a href='myPostList.php?value=3&page=".$j."'>&nbsp;".$j."&nbsp;</a>";
			}          
		}

		$total_block = ceil($total_page/$b_page_list);

		if($block >= $total_block){
			echo " ";
		}
		else{
			echo "<a href='myPostList.php?value=3&page=".($b_end_page+1)."'>&nbsp;▶&nbsp;</a>";
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