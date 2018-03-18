<?php
/*
	editInfo 페이지에서 주소 변경할 때 필요한 페이지
	주소 변경 누르면 이 페이지로 넘어와서 값 전달
*/
include_once('dbConnect.php');

echo "<select name='sido' id='sido' onChange='sidoChange();' style='border:0px'>
		<option value=''>시/도</option>";

$sql = "select distinct(sido) from area order by priority";
$result = mysql_query($sql);

while($row = mysql_fetch_array($result)) {
		echo "<option value='".$row['sido']."'>".$row['sido']."</option>"; 
}

echo "</select>
<select name='gugun' id='gugun' onChange='gugunChange()' style='border:0px' >
	<option value=''>구/군</option>
</select>
<select name='dong' id='dong' style='border:0px'>
	<option value=''>동/면/읍</option>
</select>";
	
mysql_close($connect);
?>


		