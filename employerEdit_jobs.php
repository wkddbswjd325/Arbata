<?php
/*
	employerEdit 에서 업종을 수정하기 위함
	employee와 다르게 업종 분류 1,2,3 가 있기 때문에 
	따로 페이지를 만들었다.
*/
include_once('dbConnect.php');

echo "<select name='jobs1' id='jobs1' onChange='jobs1Change();' style='border:0px;font-size:12px;'>
	<option value=''>분류1</option>";

	$sql = "select distinct(jobs1) from jobs";
	$result = mysql_query($sql);

	while($row = mysql_fetch_array($result)) {
			echo "<option value='".$row['jobs1']."'>".$row['jobs1']."</option>"; 
	}

echo "</select>
<select name='jobs2' id='jobs2' onChange='jobs2Change();' style='border:0px;font-size:12px;'>
	<option value=''>분류2</option>
</select>
<select name='jobs3' id='jobs3' style='border:0px;font-size:12px;'>
	<option value=''>분류3</option>
</select>";
	
mysql_close($connect);
?>