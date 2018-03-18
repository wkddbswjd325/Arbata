<?php
/* 
	지역 변경을 위한 페이지
*/
include_once('dbConnect.php');

// sido를 받아온다.
$sido = $_POST['sido'];

// sido를 받아온 후 선택을 하면 그에 따른 gugun을 받아옴
$gugun = $_POST['gugun'];

if(!isset($gugun)) {
	$sql = "select distinct(gugun) from area where sido='$sido' order by gugun";
	$result = mysql_query($sql);
	
	echo "<option value=''>구/군</option>";
	while($row = mysql_fetch_array($result)) {
		echo "<option value='".$row['gugun']."'>".$row['gugun']."</option>"; 
	}

} else {
	echo "<option value=''>동/면/읍</option>";
	$all = $_POST['all'];
	if($all == "ok")
		echo "<option value='전체'>전체</option>";

	$sql = "select distinct(dong) from area where sido='$sido' and gugun='$gugun' order by dong";
	$result = mysql_query($sql);

	while($row = mysql_fetch_array($result)) {
		echo "<option value='".$row['dong']."'>".$row['dong']."</option>"; 
	}

}

mysql_close($connect);
?>