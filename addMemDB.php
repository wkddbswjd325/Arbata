<?php
/*
	회원가입 후 회원 정보를 DB에 저장하는 페이지
*/
header("Content-Type: text/html; charset= UTF-8 ");
session_start();

date_default_timezone_set("Asia/Seoul");

include_once('dbConnect.php');

$mem_id = $_POST['mem_id'];
$mem_pwd = $_POST['mem_pwd'];
$mem_name = mysql_real_escape_string($_POST['mem_name']);
$mem_sex = $_POST['mem_sex'];
$mem_birth = $_POST['mem_birth'];
$mem_phone = $_POST['mem_phone'];
$mem_sido = $_POST['sido'];
$mem_gugun = $_POST['gugun'];
$mem_dong = $_POST['dong'];
$mem_email = $_POST['mem_email'];

// 내용이 입력되지 않았을 때 경고
if ( $mem_id == "" || $mem_pwd == "" || $mem_name == "" || $mem_sex == ""  || $mem_birth == "" || $mem_phone == "" || $mem_sido == "" || $mem_gugun == "" || $mem_dong == "" || $mem_email == "" ) {
	echo"<script>
			alert('모든 내용을 입력하세요.');
			history.go(-1);
			</script>";
}

// 파일 업로드 한다면
if(isset($_FILES['upload'])) {
	if($_FILES['upload']['error'] > 0) {
		switch($_FILES['upload']['error']) {
			case 1:
				echo"<script>
			alert('업로드 최대 용량 초과');
			history.go(-1);
			</script>";
			case 2:
				echo"<script>
			alert('업로드 최대 용량 초과');
			history.go(-1);
			</script>";
			case 3:
				echo"<script>
			alert('파일의 일부만 업로드 됨');
			history.go(-1);
			</script>";
			case 6:
				echo"<script>
			alert('사용가능한 임시폴더가 없음');
			history.go(-1);
			</script>";
			case 7:
				echo"<script>
			alert('디스크에 저장할수 없음');
			history.go(-1);
			</script>";
			case 8:
				echo"<script>
			alert('파일 업로드가 중지됨');
			history.go(-1);
			</script>";
			
		}
	}

	//파일 용량 초과
	if($_FILES['upload']['size'] > 1000000) {
		echo"<script>
			alert('업로드 최대 용량이 초과되었습니다.');
			history.go(-1)
			</script>";
	}
	
	// 임시 폴더
	$tmpname = $_FILES['upload']['tmp_name'];

	if($tmpname) {
		$filename = strtolower($_FILES['upload']['name']);
		
		$img_size = getimagesize($_FILES['upload']['tmp_name']);
		$file_size = $_FILES['upload']['size'];

		$file_ext_arr = explode(".", $filename);
		$file_name = $file_ext_arr[0];
		$ext = end($file_ext_arr);
		$ext = strtolower($ext);

		// 파일이 사진이라면
		if($ext == "jpg" || $ext == "jpeg" || $ext == "png" || $ext == "gif") {
			$folder = date("Y-m-d-h-i-s");

			//임시폴더에서 지정한 폴더로 이동
			move_uploaded_file($tmpname, $_SERVER['DOCUMENT_ROOT']."/arbata/files/".$folder.".".$ext);
			
			$way = "/arbata/files/".$folder.".".$ext;
			
			$query = "insert into image (mem_id, img_id, img_path, img_title, img_width, img_height, img_size) values ('$mem_id','','$way','$filename','$img_size[0]', '$img_size[1]','$file_size')";

			$result = mysql_query($query, $connect);
		}
	}

	// 파일이 없다면 성별에 따른 기본 이미지 저장
	else {
		if($mem_sex == "m") {
			$folder = "male.png";
		}
		else if($mem_sex == "f") {
			$folder = "female.png";
		}
			$way = "/arbata/files/".$folder;

			$query = "insert into image (mem_id, img_id, img_path, img_title) values ('$mem_id','','$way','$folder')";

			$result = mysql_query($query, $connect);
	}
	
	$sql="insert into member (mem_id, mem_pwd, mem_name, mem_sex, mem_birth, mem_phone, mem_sido, mem_gugun, mem_dong, mem_email) ";
	$sql.="values ('$mem_id', '$mem_pwd', '$mem_name', '$mem_sex', '$mem_birth', '$mem_phone', '$mem_sido', '$mem_gugun', '$mem_dong', '$mem_email');";

	$res=mysql_query($sql, $connect);
	$tot_row = mysql_affected_rows();

	if($tot_row > 0) {
		echo"<script>
		alert('회원가입이 완료되었습니다.');
		location.href='loginPage.php';
		</script>";
	}
	
	mysql_close($connect);
}
?>