<?
	header("Content-type: text/html; charset=euc-kr");
	header ("Cache-Control : no-cache");
	header ("Cache-Control : post-check=0 pre-check=0");
	header ("Pragma:no-cache");

	//$enc_retInfo =  $_REQUEST["retInfo"];
	//$param = "?retInfo=$enc_retInfo";

	$iv = "0000000000000000";

	// 파라메터로 받은 요청결과
	$enc_retInfo = $_REQUEST["retInfo"];

	//암호화 키 셋팅
	$key = "3ECA075F0D94C1E583DC5A0968FD6F97";

	//2014.02.07 KISA 권고사항 : 위 변조 및, 불법 시도 차단을 위하여 아래 패턴에 해당하는 문자열만 허용
	if( preg_match('~[^0-9a-zA-Z+/=^]~', $iv, $matches) || 
		preg_match('~[^0-9a-zA-Z+/=^]~', $enc_retInfo, $matches)){
			echo "입력 값 확인이 필요합니다.(res-1)"; exit;
	}

	/*
?>

	[본인확인 결과 수신 Sample-PHP] <br><br>
	[복호화 하기전 수신값] <br><br>

	retInfo : <? echo $enc_retInfo ?> <br />

<?
	*/

	//02. 1차 복호화 
	//암호화모듈 설치시 생성된 SciSecuX 파일이 있는 리눅스 경로를 설정해주세요.
	$dec_retInfo = exec("/home/rental/public_html/Siren24_v2/SciSecuX SEED 2 2 $iv $enc_retInfo $key"); //(ex: /home/name1/php_v2/SciSecuX)

	/*
	PHP 버전에 따라 기존에 사용하던 split 함수가 작동을 안 할때가 있습니다.
	그럴땐 explode 함수로 변경 후 \\^ 스플릿대신에 ^로 구분을 지어주시면 정상처리가 가능합니다.
	*/
	$totInfo = split("\\^", $dec_retInfo);
	$encPara  = $totInfo[0];			//본인확인1차암호화값
	$encMsg   = $totInfo[1];		//암호화된 통합 파라미터의 위변조검증값

	//03. HMAC 확인
	$hmac_str = exec("/home/rental/public_html/Siren24_v2/SciSecuX HMAC 1 2 $encPara $key");

	if($hmac_str != $encMsg){
?>
		<script language="javascript">
			alert("비정상적인 접근입니다!!");
		</script>
		<a href="http://beta.jamkan.com/Siren24_v2/pcc_V3_input_seed_v2.php">[Back]</a>
<?
		exit;
	}

	//04. 2차 복호화
	$decPara = exec("/home/rental/public_html/Siren24_v2/SciSecuX SEED 2 2 $iv $encPara $key");

	/*
	PHP 버전에 따라 기존에 사용하던 split 함수가 작동을 안 할때가 있습니다.
	그럴땐 explode 함수로 변경 후 \\^ 스플릿대신에 ^로 구분을 지어주시면 정상처리가 가능합니다.
	*/
	//05. 데이터 추출
	$split_dec_retInfo = split("\\^", $decPara);

	print_r($split_dec_retInfo);
	//exit;

	$name		= $split_dec_retInfo[0];		//성명
	$birYMD		= $split_dec_retInfo[1];		//생년월일
	$sex			= $split_dec_retInfo[2];		//성별
	$fgnGbn		= $split_dec_retInfo[3];		//내외국인 구분값
	$di				= $split_dec_retInfo[4];		//DI
	$ci1			= $split_dec_retInfo[5];		//CI1
	$ci2			= $split_dec_retInfo[6];		//CI2	
	$civersion	= $split_dec_retInfo[7];		//CI Version
	$reqNum		= "0000000000000000";		//$split_dec_retInfo[8];		//요청번호
	$result		= $split_dec_retInfo[9];		//본인확인 결과 (Y/N)
	$certGb		= $split_dec_retInfo[10];		//인증수단
	$cellNo		= $split_dec_retInfo[11];		//핸드폰 번호
	$cellCorp		= $split_dec_retInfo[12];		//이동통신사
	$certDate	= $split_dec_retInfo[13];		//검증시간
	$addVar		= $split_dec_retInfo[14];	//추가 파라메터

	//예약 필드
	$ext1		= $split_dec_retInfo[15];
	$ext2		= $split_dec_retInfo[16];
	$ext3		= $split_dec_retInfo[17];
	$ext4		= $split_dec_retInfo[18];
	$ext5		= $split_dec_retInfo[19];

	//$name=iconv("UTF-8","EUC-KR",$name);
?>

<script>
	window_name=opener.window.name;
	opener.sirenResult('<? echo $name ?>','<? echo $cellNo ?>','<? echo $sex ?>','<? echo $birYMD ?>','<? echo $result ?>');
	self.close();
</script>

<? /*
<html>
<head>
<script language="JavaScript">
function end(){
	window.opener.location.href = 'http://beta.jamkan.com/Siren24_v2/pcc_V3_result_seed_v2.php' + '<?=$param?>';
	self.close();
}
</script>

</head>
<body onload="javascript:end()">
</body>
</html>
*/ ?>