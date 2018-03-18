<?php
/*
	'대타를 부탁해' 게시판
	알바생들이라고 볼 수 있으며, 알바를 대타해 줄 사람을 구하는 알바생들이 글을 작성하는 공간

	페이지당 보이는 글의 수는 10개이며, 한 블럭당 페이지 3개
	지역을 선택하여 볼 수 있음
	
	디폴트 지역은 로그인 안했을 경우엔 전국,
	했을 경우에는 자신의 지역으로 설정된다.

	최신순, 대타시 지급할 시급순, 글 작성자의 별점순으로 나열할 수 있다.

	검색기능도 구현되어있다.
*/
include_once('dbConnect.php');
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
<title>대타를 부탁해! 게시판</title>
<link rel="stylesheet" href="inputStyle.css" type="text/css" />
<link rel="stylesheet" href="boardStyle.css" type="text/css" />
</head>
<body>
<?php
include('menuBar.php');

if(isset($_GET['sido']) && $_GET['sido'] == "전국") {
	unset($_GET['sido']);
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

//조건에 맞게 select될 총 게시물 수 구하기 => 페이지를 위해 필요
if(!isset($_GET['sido'])) { //전국
	if(!isset($_GET['er_search'])) //검색X
		$count = "select count(*) from employer where e_stmt=0";
	else //검색 O
		$count = "select count(*) from employer where e_stmt=0 and (er_title like '%$_GET[er_search]%' or er_jobs3 like '%$_GET[er_search]%')";
}
else { // 지역선택 되어있을 때
	if($_GET['dong']=="전체") { // 동/면/읍 이 전체일 때
		if(!isset($_GET['er_search'])) //검색X
			$count = "select count(*) from employer where e_stmt=0 and er_sido='$_GET[sido]' and er_gugun='$_GET[gugun]'";
		else //검색O
			$count = "select count(*) from employer where e_stmt=0 and (er_title like '%$_GET[er_search]%' or er_jobs3 like '%$_GET[er_search]%') and er_sido='$_GET[sido]' and er_gugun='$_GET[gugun]'";
	}
	else { // 시/도, 구/군, 동/면/읍 까지 모두 선택되었을 때
		if(!isset($_GET['er_search'])) // 검색X
			$count = "select count(*) from employer where e_stmt=0 and er_sido='$_GET[sido]' and er_gugun='$_GET[gugun]' and er_dong='$_GET[dong]'";
		else // 검색O
			$count = "select count(*) from employer where e_stmt=0 and (er_title like '%$_GET[er_search]%' or er_jobs3 like '%$_GET[er_search]%') and er_sido='$_GET[sido]' and er_gugun='$_GET[gugun]' and er_dong='$_GET[dong]'";
	}
}
$res = mysql_fetch_array(mysql_query($count));
$total_count = $res['count(*)'];

//총 페이지
$total_page = ceil( $total_count / $list ); 

if ($b_end_page > $total_page) {
	$b_end_page = $total_page;
}

$limit = ($page - 1) * $list;

if(!isset($_GET['order']))
	$order=1; //최신순
else
	$order=$_GET['order']; //최신순or시급순or별점순

if($order==1) { //최신순 = 1
	if(!isset($_GET['sido'])) { // 전국
		if(!isset($_GET['er_search'])) // 검색X
			$sql = "select * from employer where e_stmt=0 order by er_no desc limit $limit,$list ";
		else // 검색O
			$sql = "select * from employer where e_stmt=0 and (er_title like '%$_GET[er_search]%' or er_jobs3 like '%$_GET[er_search]%') order by er_no desc limit $limit,$list ";
	}
	else { // 지역선택 되어있을 때
		if($_GET['dong']=="전체") { // 동/면/읍 이 전체일 때
			if(!isset($_GET['er_search'])) // 검색X
				$sql = "select * from employer where e_stmt=0 and er_sido='$_GET[sido]' and er_gugun='$_GET[gugun]' order by er_no desc limit $limit,$list ";
			else // 검색O
				$sql = "select * from employer where e_stmt=0 and (er_title like '%$_GET[er_search]%' or er_jobs3 like '%$_GET[er_search]%') and er_sido='$_GET[sido]' and er_gugun='$_GET[gugun]' order by er_no desc limit $limit,$list ";
		}
		else { // 시/도, 구/군, 동/면/읍 까지 모두 선택되었을 때
			if(!isset($_GET['er_search'])) // 검색X
				$sql = "select * from employer where e_stmt=0 and er_sido='$_GET[sido]' and er_gugun='$_GET[gugun]' and er_dong='$_GET[dong]' order by er_no desc limit $limit,$list ";
			else // 검색O
				$sql = "select * from employer where e_stmt=0 and (er_title like '%$_GET[er_search]%' or er_jobs3 like '%$_GET[er_search]%') and er_sido='$_GET[sido]' and er_gugun='$_GET[gugun]' and er_dong='$_GET[dong]' order by er_no desc limit $limit,$list ";
		}
	}
}
else if($order==2) { //시급순 = 2
	if(!isset($_GET['sido'])) { // 전국
		if(!isset($_GET['er_search'])) // 검색X
			$sql = "select * from employer where e_stmt=0 order by er_money desc limit $limit,$list ";
		else // 검색O
			$sql = "select * from employer where e_stmt=0 and (er_title like '%$_GET[er_search]%' or er_jobs3 like '%$_GET[er_search]%') order by er_money desc limit $limit,$list ";
	}
	else { // 지역선택 되어있을 때
		if($_GET['dong']=="전체") { // 동/면/읍 이 전체일 때
			if(!isset($_GET['er_search'])) // 검색X
				$sql = "select * from employer where e_stmt=0 and er_sido='$_GET[sido]' and er_gugun='$_GET[gugun]' order by er_money desc limit $limit,$list ";
			else // 검색O
				$sql = "select * from employer where e_stmt=0 and (er_title like '%$_GET[er_search]%' or er_jobs3 like '%$_GET[er_search]%') and er_sido='$_GET[sido]' and er_gugun='$_GET[gugun]' order by er_money desc limit $limit,$list ";
		}
		else { // 시/도, 구/군, 동/면/읍 까지 모두 선택되었을 때
			if(!isset($_GET['er_search'])) // 검색X
				$sql = "select * from employer where e_stmt=0 and er_sido='$_GET[sido]' and er_gugun='$_GET[gugun]' and er_dong='$_GET[dong]' order by er_money desc limit $limit,$list ";
			else // 검색O
				$sql = "select * from employer where e_stmt=0 and (er_title like '%$_GET[er_search]%' or er_jobs3 like '%$_GET[er_search]%') and er_sido='$_GET[sido]' and er_gugun='$_GET[gugun]' and er_dong='$_GET[dong]' order by er_money desc limit $limit,$list ";
		}
	}
}
else if($order==3) { //별점순 = 3
	if(!isset($_GET['sido'])) { // 전국
		if(!isset($_GET['er_search'])) // 검색X
			$sql = "select er_no, er_jobs3, er_title, er_money, er_sido, er_gugun, er_dong, er_writeDay, avg(r_star) from employer left join review on er_writer=r_recvId where e_stmt=0  group by er_no order by avg(r_star) desc, er_no desc limit $limit,$list ";
		else // 검색O
			$sql = "select er_no, er_jobs3, er_title, er_money, er_sido, er_gugun, er_dong, er_writeDay, avg(r_star) from employer left join review on er_writer=r_recvId where e_stmt=0 and (er_title like '%$_GET[er_search]%' or er_jobs3 like '%$_GET[er_search]%') group by er_no order by avg(r_star) desc, er_no desc limit $limit,$list ";
	}
	else { // 지역선택 되어있을 때
		if($_GET['dong']=="전체") { // 동/면/읍 이 전체일 때
			if(!isset($_GET['er_search'])) // 검색X
				$sql = "select er_no, er_jobs3, er_title, er_money, er_sido, er_gugun, er_dong, er_writeDay, avg(r_star) from employer left join review on er_writer=r_recvId where e_stmt=0 and er_sido='$_GET[sido]' and er_gugun='$_GET[gugun]' group by er_no order by avg(r_star) desc, er_no desc limit $limit,$list ";
			else // 검색O
				$sql = "select er_no, er_jobs3, er_title, er_money, er_sido, er_gugun, er_dong, er_writeDay, avg(r_star) from employer left join review on er_writer=r_recvId where e_stmt=0 and (er_title like '%$_GET[er_search]%' or er_jobs3 like '%$_GET[er_search]%') and er_sido='$_GET[sido]' and er_gugun='$_GET[gugun]' group by er_no order by avg(r_star) desc, er_no desc limit $limit,$list ";
		}
		else { // 시/도, 구/군, 동/면/읍 까지 모두 선택되었을 때
			if(!isset($_GET['er_search'])) // 검색X
				$sql = "select er_no, er_jobs3, er_title, er_money, er_sido, er_gugun, er_dong, er_writeDay, avg(r_star) from employer left join review on er_writer=r_recvId where e_stmt=0 and er_sido='$_GET[sido]' and er_gugun='$_GET[gugun]' and er_dong='$_GET[dong]' group by er_no order by avg(r_star) desc, er_no desc limit $limit,$list ";
			else // 검색O
				$sql = "select er_no, er_jobs3, er_title, er_money, er_sido, er_gugun, er_dong, er_writeDay, avg(r_star) from employer left join review on er_writer=r_recvId where e_stmt=0 and (er_title like '%$_GET[er_search]%' or er_jobs3 like '%$_GET[er_search]%') and er_sido='$_GET[sido]' and er_gugun='$_GET[gugun]' and er_dong='$_GET[dong]' group by er_no order by avg(r_star) desc, er_no desc limit $limit,$list ";
		}
	}
}
		
$result = mysql_query($sql);

?>	
<div style="margin:15px 5px 10px 5px;">
<form name='employer_Board' action='employerBoard.php' method="get" style="margin:0px;display:inline;">
	<?php
		//선택된 지역이 get방식으로 계속 쫓아다니게 한다
		if(isset($_GET['sido'])) {
			echo "<input type=hidden name='sido' value='".$_GET['sido']."'>";
			echo "<input type=hidden name='gugun' value='".$_GET['gugun']."'>";
			echo "<input type=hidden name='dong' value='".$_GET['dong']."'>";
		}
	?>
	<table width="100%" cellspacing="2px">
		<tr>
		<td><input name="er_search" type="text" placeholder="검색" style="font-size:18px;min-width:200px;width:100%;min-height:20px;"></td>
		<td width="24px"><input type="image" src="image/search.png" style="min-width:24px;max-width:24px;vertical-align:middle;"></td>
		<td width="75px" align="center"><img src="image/write_img.png" style="min-width:70px;max-width:70px;vertical-align:middle;" onclick="location.href='employerWrite.php'"></td>
		</tr>
	</table>	
	</form>
</div>

<div style="margin:0px 5px 0px 5px;">
<table width="100%" cellspacing="2px">
<tr>
	<td align="left">
		<select name="order" style="font-size:15px;" onchange="location.href=this.value">
		<?php
		//지역이 선택되지 않았을 경우(전국), 정렬 순서를 바꿀때 정렬 순서를 넘겨준다 
		if(!isset($_GET['sido'])) { 
			if(!isset($_GET['er_search'])) { // 검색X
				echo "<option value='employerBoard.php?order=1'"; if($order=="1") echo "selected"; echo ">최신순</option>";
				echo "<option value='employerBoard.php?order=2'"; if($order=="2") echo "selected"; echo ">시급순</option>";
				echo "<option value='employerBoard.php?order=3'"; if($order=="3") echo "selected"; echo ">별점순</option>";
			} else	{ // 검색O
				echo "<option value='employerBoard.php?order=1&er_search=".$_GET['er_search']."'"; if($order=="1") echo "selected"; echo ">최신순</option>";
				echo "<option value='employerBoard.php?order=2&er_search=".$_GET['er_search']."'"; if($order=="2") echo "selected"; echo ">시급순</option>";
				echo "<option value='employerBoard.php?order=3&er_search=".$_GET['er_search']."'"; if($order=="3") echo "selected"; echo ">별점순</option>";
			}
		} else { //지역이 선택되었을 경우, 정렬 순서를 바꿀때 정렬 순서와 함께 선택되어 있는 지역의 정보를 같이 넘겨준다
			if(!isset($_GET['er_search'])) { // 검색X
				echo "<option value='employerBoard.php?order=1&sido=".$_GET['sido']."&gugun=".$_GET['gugun']."&dong=".$_GET['dong']."'";
				if($order=="1") echo "selected"; echo ">최신순</option>";	
				echo "<option value='employerBoard.php?order=2&sido=".$_GET['sido']."&gugun=".$_GET['gugun']."&dong=".$_GET['dong']."'";
				if($order=="2") echo "selected"; echo ">시급순</option>";
				echo "<option value='employerBoard.php?order=3&sido=".$_GET['sido']."&gugun=".$_GET['gugun']."&dong=".$_GET['dong']."'";
				if($order=="3") echo "selected"; echo ">별점순</option>";
			} else { // 검색O
				echo "<option value='employerBoard.php?order=1&er_search=".$_GET['er_search']."&sido=".$_GET['sido']."&gugun=".$_GET['gugun']."&dong=".$_GET['dong']."'";
				if($order=="1") echo "selected"; echo ">최신순</option>";	
				echo "<option value='employerBoard.php?order=2&er_search=".$_GET['er_search']."&sido=".$_GET['sido']."&gugun=".$_GET['gugun']."&dong=".$_GET['dong']."'";
				if($order=="2") echo "selected"; echo ">시급순</option>";
				echo "<option value='employerBoard.php?order=3&er_search=".$_GET['er_search']."&sido=".$_GET['sido']."&gugun=".$_GET['gugun']."&dong=".$_GET['dong']."'";
				if($order=="3") echo "selected"; echo ">별점순</option>";
			}
		}
		?>	
		</select>
	</td>
	<td align="right" width="auto">
		<font style="font-size:15px">
		<?php 
			if(!isset($_GET['sido'])) 
				echo "전국";
			else
				echo $_GET['sido']." ".$_GET['gugun']." ".$_GET['dong'];
		?> 
		</font>
	</td>
	<td align="right" width="80px">
		<!-- 지역변경 후에 다시 돌아와야 할 게시판의 정보를 get방식으로 넣어준다 -->
		<input type="button" id="selectLocal" value="지역 변경" onclick="location.href='area.php?which=employer'">
	</td>
</tr>
</table>
</div>
<?php
$rows = mysql_num_rows($result); 
//select된 row의 수가 0개 일때
if($rows==0) {
	echo "<table bgcolor='#f6f6f6' width='100%'><tr align='center'><td><br>현재 올라온 대타가 없습니다.<br><br></td></tr></table>";
}

else {
	while($row = mysql_fetch_array($result)) {
		echo "<a href='employerRead.php?er_no=".$row['er_no']."'><div id='divBoard'>";
		echo "<span id='spanTitle'>[".$row['er_jobs3']."] ".$row['er_title']. "</span><br>";
		echo "<span id='spanMoney'>".number_format($row['er_money'])."원</span>&nbsp;|&nbsp;<span id='spanBody'>".$row['er_sido']." ".$row['er_gugun']." ".$row['er_dong']."</span>&nbsp;|&nbsp;<span id='spanBody'>".$row['er_writeDay']."</span>";
		echo "</div></a>";
	}
}
?>
<br>
<div align="center">
<font style="font-size:20px;color:#252c41">
<?php 
//현재 첫번째 블락에 위치한다면 왼쪽 화살표 안보이게 => 블락당 페이지가 3개이므로 page=1,2,3쪽일 경우 
if($block ==1) {
	echo " ";
}
//첫번째 블락이 아니라면, 왼쪽 화살표 출력(조건에 따라 get방식으로 따라가는 parameter의 차이 존재)
else {
	if(!isset($_GET['sido'])) { // 전국일 때
		if(!isset($_GET['er_search'])) // 검색X
			echo "<a href='employerBoard.php?page=".($b_start_page-1)."&order=".$order."'>&nbsp;◀&nbsp;</a>";
		else // 검색O
			echo "<a href='employerBoard.php?er_search=".$_GET['er_search']."&page=".($b_start_page-1)."&order=".$order."'>&nbsp;◀&nbsp;</a>";
	}
	else { // 지역O
		if(!isset($_GET['er_search'])) // 검색X
			echo "<a href='employerBoard.php?sido=".$_GET['sido']."&gugun=".$_GET['gugun']."&dong=".$_GET['dong']."&page=".($b_start_page-1)."&order=".$order."'>&nbsp;◀&nbsp;</a>";
		else // 검색O
			echo "<a href='employerBoard.php?er_search=".$_GET['er_search']."&sido=".$_GET['sido']."&gugun=".$_GET['gugun']."&dong=".$_GET['dong']."&page=".($b_start_page-1)."&order=".$order."'>&nbsp;◀&nbsp;</a>";
	}
}

//해당 블락의 쪽수 출력(select한 개수에 맞게)
for($j = $b_start_page; $j <=$b_end_page; $j++)
{
	//현재 페이지와 출력하는 쪽수가 같을 때 - 색 변경
	if($page == $j)
	{
		echo "<font color=#f1404b>&nbsp;".$j."&nbsp;</font>";
	}
	else{ //현재 페이지가 아닌 쪽수
		if(!isset($_GET['sido'])) { //전국
			if(!isset($_GET['er_search'])) //검색X
				echo "<a href='employerBoard.php?page=".$j."&order=".$order."'>&nbsp;".$j."&nbsp;</a>";
			else // 검색O
				echo "<a href='employerBoard.php?er_search=".$_GET['er_search']."&page=".$j."&order=".$order."'>&nbsp;".$j."&nbsp;</a>";
		}
		else { // 지역O
			if(!isset($_GET['er_search'])) //검색X
				echo "<a href='employerBoard.php?sido=".$_GET['sido']."&gugun=".$_GET['gugun']."&dong=".$_GET['dong']."&page=".$j."&order=".$order."'>&nbsp;".$j."&nbsp;</a>";
			else //검색 O
				echo "<a href='employerBoard.php?er_search=".$_GET['er_search']."&sido=".$_GET['sido']."&gugun=".$_GET['gugun']."&dong=".$_GET['dong']."&page=".$j."&order=".$order."'>&nbsp;".$j."&nbsp;</a>";
		}
	}             

}
//select한 결과가 총 몇 block인지
$total_block = ceil($total_page/$b_page_list);

//현재 마지막 블럭에 위치한다면, 오른쪽 화살표 X
if($block >= $total_block){
	echo " ";
}
else {  //마지막 블락이 아니라면, 오른쪽 화살표 출력(조건에 따라 get방식으로 따라가는 parameter의 차이 존재)
	if(!isset($_GET['sido'])) { // 전국
		if(!isset($_GET['er_search'])) // 검색X
			echo "<a href='employerBoard.php?page=".($b_end_page+1)."&order=".$order."'>&nbsp;▶&nbsp;</a>";
		else // 검색O
			echo "<a href='employerBoard.php?er_search=".$_GET['er_search']."&page=".($b_end_page+1)."&order=".$order."'>&nbsp;▶&nbsp;</a>";
	}
	else { // 지역O
		if(!isset($_GET['er_search'])) // 검색X
			echo "<a href='employerBoard.php?sido=".$_GET['sido']."&gugun=".$_GET['gugun']."&dong=".$_GET['dong']."&page=".($b_end_page+1)."&order=".$order."'>&nbsp;▶&nbsp;</a>";
		else // 검색O
			echo "<a href='employerBoard.php?er_search=".$_GET['er_search']."&sido=".$_GET['sido']."&gugun=".$_GET['gugun']."&dong=".$_GET['dong']."&page=".($b_end_page+1)."&order=".$order."'>&nbsp;▶&nbsp;</a>";
	}
}
?>
</font>
</div>
</body>
</html>
<?php
	mysql_close($connect);
?>