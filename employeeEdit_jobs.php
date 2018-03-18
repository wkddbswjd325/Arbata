<?php
/*
	employeeEdit 에서 업종을 수정하기 위함
	employee의 특성상 업종은 분류 하나만 선택
*/
include_once('dbConnect.php');

echo "<select name='jobs1' id='jobs1' onChange='jobs1Change();' style='border:0px;font-size:12px;'>
	<option value=''>분류</option>";

$sql = "select distinct(jobs1) from jobs";
$result = mysql_query($sql);

while($row = mysql_fetch_array($result)) {
		echo "<option value='".$row['jobs1']."'>".$row['jobs1']."</option>"; 
}

echo "</select>";

mysql_close($connect);
?>