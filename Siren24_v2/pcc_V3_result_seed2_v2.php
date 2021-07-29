<?
/**************************************************************************************************************************
* Program Name  : 본인확인 결과 Sample Page
* File Name     : pcc_V3_result_seed_v2
* Comment       : 
* History       : Version 1.0
**************************************************************************************************************************/
?>
<?
	/************************************************************************************/
	
 	/************************************************************************************/

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

    [본인확인 결과 수신 Sample-PHP] <br> <br>
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

	if($result == "Y"){
?>
	<!--
	<form name="reqCBAre" method="post" action="/front/member_join2.php" >
		<input type="hidden" name="req_name" value="<? echo "$name" ?>">
		<input type="hidden" name="req_sex" value="<? echo "$sex" ?>">
		<input type="hidden" name="req_birYMD" value="<? echo "$birYMD" ?>">
		<input type="hidden" name="req_di" value="<? echo "$di" ?>">
		<input type="hidden" name="req_result" value="<? echo "$result" ?>">
		<input type="hidden" name="req_cellNo" value="<? echo "$cellNo" ?>">
		<input type="hidden" name="addVar" value="<? echo "$addVar" ?>">
	</form>
	<script>document.reqCBAre.submit();</script>
	-->
	<script>
		window_name =opener.window.name;
		opener.sirenResult('<? echo $name ?>','<? echo $cellNo ?>','<? echo $sex ?>','<? echo $birYMD ?>','<? echo $ci1 ?>');
		self.close();
	</script>

<? }else{ ?>
	<script language=javascript>
		alert("인증실패되었습니다.");
		location.replace("/front/member_join2.php");
	</script>
<?
		exit;
	}

	/*
?>
<html>
    <head>
        <title>SCI평가정보 본인확인서비스  테스트</title>
        <meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
		<meta name="robots" content="noindex,nofollow" />
        <style>
            <!--
            body,p,ol,ul,td
            {
                font-family: 굴림;
                font-size: 12px;
            }

            a:link { size:9px;color:#000000;text-decoration: none; line-height: 12px}
            a:visited { size:9px;color:#555555;text-decoration: none; line-height: 12px}
            a:hover { color:#ff9900;text-decoration: none; line-height: 12px}

            .style1 {
                color: #6b902a;
                font-weight: bold;
            }
            .style2 {
                color: #666666
            }
            .style3 {
                color: #3b5d00;
                font-weight: bold;
            }
            -->
        </style>
    </head>
	<body>
            [복호화 후 수신값] <br>
            <br>
            <table cellpadding="1" cellspacing="1" border="1">
				<tr>
					<td align="center" colspan="2">14세이상 및 신원보증인 결과</td>
				</tr>
				<tr>
                    <td align="left">성명</td>
                    <td align="left"><? echo $name ?></td>
                </tr>
				<tr>
                    <td align="left">성별</td>
                    <td align="left"><? echo $sex ?></td>
                </tr>
				<tr>
                    <td align="left">생년월일</td>
                    <td align="left"><? echo $birYMD ?></td>
                </tr>
				<tr>
                    <td align="left">내외국인 구분값(1:내국인, 2:외국인)</td>
                    <td align="left"><? echo $fgnGbn ?></td>
                </tr>				
				<tr>
                    <td align="left">중복가입자정보</td>
                    <td align="left"><? echo $di ?></td>
                </tr>
				<tr>
                    <td align="left">연계정보1</td>
                    <td align="left"><? echo $ci1 ?></td>
                </tr>
				<tr>
                    <td align="left">연계정보2</td>
                    <td align="left"><? echo $ci2 ?></td>
                </tr>
				<tr>
                    <td align="left">연계정보버전</td>
                    <td align="left"><? echo $civersion ?></td>
                </tr>
                <tr>
                    <td align="left">요청번호</td>
                    <td align="left"><? echo $reqNum ?></td>
                </tr>
				<tr>
                    <td align="left">인증성공여부</td>
                    <td align="left"><? echo $result ?></td>
                </tr>
				<tr>
                    <td align="left">인증수단</td>
                    <td align="left"><? echo $certGb ?></td>
                </tr>
				<tr>
                    <td align="left">핸드폰번호</td>
                    <td align="left"><? echo $cellNo ?></td>
                </tr>
				<tr>
                    <td align="left">이동통신사</td>
                    <td align="left"><? echo $cellCorp ?></td>
                </tr>
                <tr>
                    <td align="left">요청시간</td>
                    <td align="left"><? echo $certDate ?></td>
                </tr>				
				<tr>
                    <td align="left">추가파라미터</td>
                    <td align="left"><? echo $addVar ?>&nbsp;</td>
                </tr>
				
            </table>              
            <br>
            <br>
            <a href="http://beta.jamkan.com/Siren24_v2/pcc_V3_input_seed_v2.php">[Back]</a>
</body>
</html>
*/ ?>