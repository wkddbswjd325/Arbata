<?php
/*
	employerWrite에서 입력한 데이터의 내용을 볼 수 있음
	employerBoard에서 글 번호를 받아와 불러오는 페이지
*/
include_once('dbConnect.php');
include('menuBar.php');
?>
<html lang="ko">
<head>
<meta charset="UTF-8">
<!-- 모바일 화면의 크기가 고정되게 하는 소스, 가로 스크롤 안생긴다. -->
<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0,minimum-scale=1.0, width=device-width" />

<!-- 아이폰 바탕화면 아이콘 추가 -->
<link rel="apple-touch-icon" href="image/apple.jpg" />
  
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script> 
<?php
$query = "select * from employer where er_no='$_GET[er_no]'"; 
$result = mysql_query($query, $connect);
$data = mysql_fetch_array($result);

$check = mysql_num_rows($result); 
if($check==0) 
	echo "<script>alert('삭제된 게시물입니다.');location.href=history.back();</script>";

$query = "select img_path from image where mem_id='$data[er_writer]'"; 
$result = mysql_query($query, $connect);
$row = mysql_fetch_array($result);

if(!isset($_SESSION['id'])){ $id = "";}
else{ $id = $_SESSION['id'];}

if($data['er_radio'] == "day") {
	$que = "select * from employer_day where er_no='$_GET[er_no]'";
	$res = mysql_query($que, $connect);
	$row1 = mysql_fetch_array($res);
}
else if($data['er_radio'] == "days") {
	$que = "select * from employer_days where er_no='$_GET[er_no]'";
	$res = mysql_query($que, $connect);
	$row2 = mysql_fetch_array($res);
}

// 지도 관련 소스

$address = $data['er_sido'].$data['er_gugun'].$data['er_dong']; // 시도/구군/동 까지의 주소
$address_detail = $data['er_detail']; // 상세주소

if(ini_get('allow_url_fopen')) {
	$xml = simpleXML_load_file("http://maps.google.com/maps/api/geocode/xml?address=".urlencode($address).urlencode($address_detail)."&language=ko&sensor=false");
}
else { 
	$ch = curl_init($url); 
	curl_setopt($ch, CURLOPT_HEADER, false); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	$xml_raw = curl_exec($ch);
	$xml = simplexml_load_string($xml_raw); 
}

// 구글 지도가 인식하지 못하는 주소의 경우 결과를 받아올 수 없을 때 임의의 위도와 경도를 입력, 지도를 표시하지 않게 함
if( count($xml->result) == "0") {
	echo "";
	$lat = 1000;
	$lng = 1000;
}
else { // 구글 지도가 인식하는 경우, 해당 주소에 따른 위도와 경도를 받아온다.
$lat = $xml->result->geometry->location->lat;
$lng = $xml->result->geometry->location->lng;
}


?> 

<script type="text/javascript"> 
    function initialize() { 
        var mapLocation = new google.maps.LatLng('<?php echo $lat; ?>', '<?php echo $lng; ?>'); // 지도에서 가운데로 위치할 위도와 경도 
        var markLocation = new google.maps.LatLng('<?php echo $lat; ?>', '<?php echo $lng; ?>'); // 마커가 위치할 위도와 경도 
        
        var mapOptions = { 
            center: mapLocation, // 지도에서 가운데로 위치할 위도와 경도(변수) 
            zoom: 17, // 지도를 띄웠을 때의 줌 크기 
            mapTypeId: google.maps.MapTypeId.ROADMAP 
        }; 
      
        var map = new google.maps.Map(document.getElementById("map"), mapOptions); // div의 id과 값이 같아야 함.
        
        var marker; 
        marker = new google.maps.Marker({ 
            position: markLocation, // 마커가 위치할 위도와 경도(변수) 
            map: map, 
            title: '<?php echo $address_detail; ?>' // 마커에 마우스 포인트를 갖다댔을 때 뜨는 타이틀 
        }); 
        
        var content = "작성자가 위치를 정확히 입력하지 않았을 경우, 위치가 다르게 나타날 수 있습니다."; // 말풍선 안에 들어갈 내용 
        
        // 마커를 클릭했을 때의 이벤트
        var infowindow = new google.maps.InfoWindow({ content: content}); 
        
        google.maps.event.addListener(marker, "click", function() { 
            infowindow.open(map,marker); 
        }); 
    } 
    google.maps.event.addDomListener(window, 'load', initialize); 
// ----- 지도 ------

window.addEventListener("load", function() {
	setTimeout(loaded, 100);
	}, false);

function loaded() {
	window.scrollTo(0,1);
	}
</script>
<link rel="stylesheet" href="inputStyle.css" type="text/css"/>
<link rel="stylesheet" href="boardStyle.css" type="text/css"/>
<style type="text/css">
  p {	color:#252c41;font-size:12pt;	}
</style>
<title>대타를 부탁해! 상세보기</title>
</head>
<body>
<form name="employerRead" method="POST" action="employerDB.php">
<div id="boardDiv">
<div id="readTitle" align="center">
		<?php
		if($data['er_radio'] == "day") {
			echo "하루 대타";
		}
		else if($data['er_radio'] == "days") {
			echo "단기 대타";
		}
		?>
		<p class="title"><b><?php echo $data['er_title'];?></b></p>
</div>

<?php
$sql = "update employer set er_count=er_count+1 WHERE er_no='$_GET[er_no]'"; 
$res = mysql_query($sql);
?>

<?php 
if($data['er_writer'] == $id) { ?>
	<img class="writer_img" src="http://14.63.196.104/<?php echo $row['img_path'];?>" onClick="location.href='myPage.php?rcvId=<?php echo $data['er_writer']; ?>'"/>
<?php
} 
else{ ?>
	<img class="writer_img" src="http://14.63.196.104/<?php echo $row['img_path'];?>" onClick="location.href='memPage.php?rcvId=<?php echo $data['er_writer']; ?>'"/>
<?php
}
?>

<div id="write_info">작성자 

<?php 
if($data['er_writer'] == $id) { 
	echo "<b><a href='myPage.php?rcvId=".$data['er_writer']."'>";
	echo $data['er_writer']."</a></b><br>";}

else{ 
	echo "<b><a href='memPage.php?rcvId=".$data['er_writer']."'>";
	echo $data['er_writer']."</a></b>";
?>
<img src="image/msg.png" width="18px" valign="absmiddle" onclick="location.href='message.php?msg_sendId=<?php echo $data['er_writer'];?>'"><br>
<?php } ?>

작성일 <b><?php echo $data['er_writeDay'];?></b>&nbsp;|&nbsp;조회수&nbsp;<b><?php echo $data['er_count'];?></b>
</div>

<div id="er_info">
<span id="er_info_title">업종</span>
<span id="er_info_content"><?php echo $data['er_jobs3'];?></span>
</div>

<div id="er_info">
<span id="er_info_title" style="vertical-align:top;">장소</span>
<span id="er_info_content"><?php echo $data['er_sido']." ".$data['er_gugun']." ".$data['er_dong']."<br>".$data['er_detail'];?></span>
</div>

<!-- 지도 -->
<div id="map" style="width:100%;height:30%;"></div>
<?php
// 구글 지도가 인식하지 못하는 주소의 경우 결과를 받아올 수 없을 때 임의의 위도와 경도를 입력, 지도를 표시하지 않게 함
if( count($xml->result) == "0") {
	echo "";
	$lat = 127;
	$lng = 55;
?>
<div id="er_info">
<span id="er_info_title">* 구글 지도가 주소를 인식하지 못하는 경우, 지도가 나타나지 않을 수 있습니다.</span>
</div>
<?php
}
?>
<div id="er_info">
<span id="er_info_title">시급</span>
<span id="er_info_content"><?php echo number_format($data['er_money'])?>원</span>
</div>

<div id="er_info">
<span id="er_info_title">기간</span>
<span id="er_info_content">
<?php
		if($data['er_radio'] == "day") {
			echo $row1['er_day'];
		}
		else if($data['er_radio'] == "days") {
			echo $row2['er_startDay']." ~ ".$row2['er_finishDay'];
		}
?>
</span>
</div>

<div id="er_info">
<span id="er_info_title" style="vertical-align:top;">시간</span>
<span id="er_info_content">
<?php
		// 하루일 경우에는 시간을 표시하기에 오전/오후를 자세히 구성
		if($data['er_radio'] == "day") {
			$start_hour = substr($row1['er_startTime'], 0, 2);
			$start_minute = substr($row1['er_startTime'], 3, 2);
			$fin_hour = substr($row1['er_finishTime'], 0, 2);
			$fin_minute = substr($row1['er_finishTime'], 3, 2);

			if ($start_hour == 00) echo "오전 12시";
			else if($start_hour <= 12) echo "오전 ".$start_hour."시";
			else echo "오후 ".($start_hour-12)."시";

			if ($start_minute != "00") {
				echo $start_minute + "분";
			}
			echo " ~ ";

			if ($fin_hour == 00) echo "오전 12시";
			else if($fin_hour <= 12) echo "오전 ".$fin_hour."시";
			else echo "오후 ".($fin_hour-12)."시";
	
			if ($fin_minute != "00") {
				echo $fin_minute + "분";
			}
		}

		// 단기일 경우에는 요일별로 시간 표시
		else if($data['er_radio'] == "days") {
			$d = " ";
			for($i = 3; $i <= 9; $i++) {
				if($row2[$i] != null) {
					if($i == 3) $d = "월";
					else if($i == 4) $d = "화";
					else if($i == 5) $d = "수";
					else if($i == 6) $d = "목";
					else if($i == 7) $d = "금";
					else if($i == 8) $d = "토";
					else $d = "일";
	
					echo $d."요일 : ".$row2[$i]."<br>";
				}	
			}
		}
?>
</span>
</div>

<div id="er_info">
<span id="er_content">
<?php echo nl2br($data['er_content']);?>
</span>
</div>

<div id="er_info" align="center">
<?php
echo"<script>
	function del() {
		var msg='삭제하시겠습니까?';
		var result = confirm(msg);
		if(result == true) {
			location.href='employerDelDB.php?er_no=$data[er_no]';
		}
	}
</script>";
		//matching 여부 확인
		$sql = "select e_stmt from employer where er_no='$_GET[er_no]'"; 
		$check = mysql_fetch_array(mysql_query($sql));
		if($check['e_stmt'] == 1) {
			echo "이미 매칭이 완료된 게시물입니다.";
		}
		else {
			if(!isset($_SESSION['id'])){ // 회원 아닐 경우
				echo "회원만 매칭 신청이 가능합니다.";
			}
			else if($data['er_writer'] == $_SESSION['id']) { // 자신의 게시물일 경우
			?> 
			<!--수정 필요!!!!!-->
			<input type="button" value="수정" name="readEdit" id="readEdit" onclick="location.href='employerEdit.php?er_no=<?php echo $data['er_no'];?>'">
			<input type="button" value="삭제" name="readDel" id="readDel" onclick="del();">
			<?php
			}
			else if(isset($_SESSION['id'])){ // 남의 게시물일 경우
			?>
			<input type="button" value="담기" style="width:45%" name="save" id="save" onclick="location.href='saveListDB.php?e_no=<?php echo $data['er_no'];?>&&s_chk=0'">
			<input type="button" value="매칭 신청" style="width:45%" name="applyMatching" id="applyMatching" onclick="location.href='applyWrite_er.php?er_no=<?php echo $_GET['er_no'];?>'">
		<?php
			}
		}
		?>
</div>
</div>
</form>
</body>
</html>
<?php
mysql_close($connect);
?>