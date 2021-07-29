<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");
INCLUDE ("access.php");
include_once($Dir."lib/admin_more.php");

$get_vender=$_GET["vender"];


$tm_idx=$_POST["tm_idx"];
$mode=$_POST["mode"];
$approve=$_POST["approve"];
$refuse_reason=$_POST["refuse_reason"];

//위탁관리 업체등록 승인
if($mode=="insert"){
	$sql = "UPDATE tbltrustmanage SET ";
	$sql.= "approve	= '".$approve."' ";
	if($approve=="R"){
		$sql.= ",refuse_reason	= '".$refuse_reason."' ";
	}
	$sql.= "WHERE tm_idx='".$tm_idx."' ";
	if($update = mysql_query($sql,get_db_conn())){
		if($approve=="Y"){ //승인
			$message = "위탁관리 업체로 등록을 승인했습니다.";
		}else if($approve=="C"){ //취소
			$message = "위탁관리 업체 등록을 취소했습니다.";
		}else{
			$message = "위탁관리 업체로 등록을 거절했습니다.";
		}

		echo "<script>alert(\"".$message."\");opener.document.form3.action='vender_infomodify.php';opener.document.form3.submit();window.close();</script>";
		exit;
	}
}


//위탁관리 업체정보수정
if($mode=="modify"){

	$product_commi = "";
	for($i=1;$i<=4;$i++){
		if($_POST["code".$i]!=$i){
			$product_commi .= $_POST["code".$i].":".$_POST["commi".$i]."//";
		}
	}

	$sql = "UPDATE tbltrustmanage SET ";
	$sql.= "store_post		= '".$store_post."', ";
	$sql.= "store_addr		= '".$store_addr."', ";
	$sql.= "store_sido		= '".$store_sido."', ";
	$sql.= "store_sigungu	= '".$store_sigungu."', ";
	$sql.= "store_area		= '".$store_area."', ";
	$sql.= "product_commi		= '".$product_commi."', ";
	$sql.= "homepage	= '".$homepage."', ";
	$sql.= "release_time	= '".$release_time."', ";
	$sql.= "p_name		= '".$p_name."', ";
	$sql.= "p_mobile			= '".$p_mobile."', ";
	$sql.= "p_email		= '".$p_email."', ";
	$sql.= "comp_info		= '".$comp_info."' ";
	$sql.= "WHERE tm_idx='".$tm_idx."'";
	if($update = mysql_query($sql,get_db_conn())){
		echo "<script>alert(\"수정되었습니다.\");opener.document.form3.action='vender_infomodify.php';opener.document.form3.submit();window.close();</script>";
		exit;
	}
}



$sql = "SELECT * FROM tbltrustmanage ";
$sql.= "WHERE vender='".$get_vender."' ";
$result=mysql_query($sql,get_db_conn());
$data=mysql_fetch_object($result);

$arrPr_commi = explode("//",$data->product_commi);
for($i=0;$i<sizeof($arrPr_commi);$i++){
	$arrCommi[$i] = explode(":",$arrPr_commi[$i]);

	$sql_ = "SELECT code_name FROM tblproductcode ";
	$sql_.= "WHERE codeA='".$arrCommi[$i][0]."' ";
	$sql_.= "AND codeB='000' AND codeC='000' ";
	$sql_.= "AND codeD='000' AND type LIKE 'L%'";
	$cRes=mysql_query($sql_,get_db_conn());
	while($cRow=mysql_fetch_object($cRes)) {
		if($i==0){
			$mainCodeNm = $cRow->code_name;
			$mainCommi = $arrCommi[$i][1]."%";
		}else{
			$codeNm .= $cRow->code_name.",";
		}
		
		if($arrCommi[$i][1]){
			$pr_commi .= "<li><span class='sub1'>".$cRow->code_name."</span> <span class='sub2 grayBox'>".$arrCommi[$i][1]."%</span></li>";
		}
	}
	mysql_free_result($cRow);
}
?>

<html>
<head>
<title>관리자 페이지</title>
<!--META content="IE=5" http-equiv="X-UA-Compatible"-->
<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="index,nofollow">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<!--[if lt lE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js">
</script>
<![endif]-->
<link rel="stylesheet" href="/admin/style.css">
<link rel="stylesheet" href="../vender/style.css" type="text/css">
<style>
.sub1 {width:100px;font-weight:bold}
.sub2 {width:60px;text-align:center;}
.mainForm li {border-bottom:1px solid #eeeeee}
.smallBtn {width:80px; margin-bottom:10px; padding:5px;}
.cancelForm textarea {width:600px;height:80px}
</style>
<script type="text/javascript" src="lib.js.php"></script>
<link href="/js/jquery-ui-1.11.4/jquery-ui.css" rel="stylesheet">
<script src="/js/jquery-ui-1.11.4/external/jquery/jquery.js"></script>
<script src="/js/jquery-ui-1.11.4/jquery-ui.js"></script>
<script type="text/javascript" src="../js/jquery.validate.min.js"></script>
<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>

<script type="text/javascript">
function addr_search_for_daumapi(post,addr1,addr2) {
	new daum.Postcode({
		oncomplete: function(data) {
			// 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

			// 각 주소의 노출 규칙에 따라 주소를 조합한다.
			// 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
			var fullAddr = ''; // 최종 주소 변수
			var extraAddr = ''; // 조합형 주소 변수

			// 사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
			if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
				fullAddr = data.roadAddress;

			} else { // 사용자가 지번 주소를 선택했을 경우(J)
				fullAddr = data.jibunAddress;
			}

			// 사용자가 선택한 주소가 도로명 타입일때 조합한다.
			if(data.userSelectedType === 'R'){
				//법정동명이 있을 경우 추가한다.
				if(data.bname !== ''){
					extraAddr += data.bname;
				}
				// 건물명이 있을 경우 추가한다.
				if(data.buildingName !== ''){
					extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
				}
				// 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
				fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
			}

			// 우편번호와 주소 정보를 해당 필드에 넣는다.
			document.getElementById(post).value = data.zonecode; //5자리 새우편번호 사용
			document.getElementById(addr1).value = fullAddr;

			document.getElementById("sido").value = data.sido; //도/시 이름 
			document.getElementById("sigungu").value = data.sigungu; //


			// 커서를 상세주소 필드로 이동한다.
			if (addr2 != "") {
				document.getElementById(addr2).focus();
			}
		}
	}).open();
}

$(document).ready(function () {    

	$('#trust_modifyBtn').click(function(){ 

		if($('#store_area').val()==""){
			alert("물품관리 창고 규모를 입력하세요.");
			return false;
		}

		if (isNaN($('#store_area').val())){
			alert("물품관리 창고 규모는 숫자만 입력하실 수 있습니다.");
			return false;
		}

		if($('#code1').val()==""){
			alert("물품 종류를 선택하세요.");
			return false;
		}

		if($('#commi1').val()==""){
			alert("물품 수수료를 입력하세요.");
			return false;
		}

		if (isNaN($('#commi1').val()) || isNaN($('#commi2').val()) || isNaN($('#commi3').val()) || isNaN($('#commi4').val())){
			alert("물품 수수료는 숫자만 입력하실 수 있습니다.");
			return false;
		}

		if($('#code1').val()==$('#code2').val() || $('#code1').val()==$('#code3').val() || $('#code1').val()==$('#code4').val() || $('#code2').val()==$('#code3').val() || $('#code2').val()==$('#code4').val() || $('#code3').val()==$('#code4').val()){
			alert("수수료 설정시 물품종류를 다르게 설정해주세요.  ");
			return;
		}else if($('#code2').val()!="2" && $('#commi2').val()==""){
			alert("수수료를 입력해주세요.  ");
			$('#commi2').focus();
			return;
		}else if($('#code3').val()!="3" && $('#commi3').val()==""){
			alert("수수료를 입력해주세요.  ");
			$('#commi3').focus();
			return;
		}else if($('#code4').val()!="4" && $('#commi4').val()==""){
			alert("수수료를 입력해주세요.  ");
			$('#commi4').focus();
			return;
		}

		$('input[name=mode]').val('modify');
		$('#appForm').submit();   //유효성 검사를 통과시 전송


	});

	$('#trust_cancelBtn').click(function(){ 
		if(confirm("위탁관리업체 등록취소하시겠습니까?")){
			$('input[name=approve]').val('C');
			$('input[name=mode]').val('insert');
			$('#appForm').submit();
		}
	});

	$('#trust_applyBtn').click(function(){ 
		$('input[name=approve]').val('Y');
		$('input[name=mode]').val('insert');
		$('#appForm').submit();
	});

	$('#refuse').click(function(){ 
		$('.cancelForm').show();
	});
	$('#cancelBack').click(function(){ 
		$('.cancelForm').hide();
	});

	$('#cancelSubmit').click(function(){
		if($('#refuse_reason').val()==""){
			alert("거절사유를 입력해주세요");
			$('#refuse_reason').focus();
			return false;
		}else{
			if(confirm("위탁관리 등록업체 신청을 거절하시겠습니까?")){
				$('input[name=approve]').val('R');
				$('input[name=mode]').val('insert');
				$('#appForm').submit();
			}
		}
	});

});
</script>
</head>

<body topmargin=0 leftmargin=0  marginheight=0 marginwidth=0>

	
<section>
	<article class="applyForm">
		<h4 class="txt">위탁관리 업체등록 신청서</h4>
		<form name="appForm" id="appForm" method="post" action="<?=$PHP_SELF?>">
		<input type="hidden" name="mode">
		<input type="hidden" name="tm_idx" value="<?=$data->tm_idx?>">
		<input type="hidden" name="approve">
		<fieldset>
			<legend>위탁관리 업체등록 신청하기</legend>
			<ul class="mainForm">
				<li>
					<span class="sub">물품관리 창고 위치</span>
					<span class="unit">
						<button type="button" id="addrSearch" onClick="addr_search_for_daumapi('store_post','store_addr','');">주소검색</button><input type="text" name="store_post" id="store_post" class="addrBox1" value="<?=$data->store_post?>"><br>
						<input type="text" name="store_addr" id="store_addr" class="inputBox" value="<?=$data->store_addr?>">
						<input type="hidden" name="sido" id="sido" class="inputBox" value="<?=$data->store_sido?>">
						<input type="hidden" name="sigungu" id="sigungu" class="inputBox" value="<?=$data->store_sigungu?>">
					</span>
				</li>	
				<li>
					<span class="sub">물품관리 창고 규모(평)</span>
					<span class="unit"><input type="text" name="store_area" id="store_area" class="inputBox" placeholder="평" style="text-align:right" value="<?=$data->store_area?>"></span>
				</li>
				<li>
					<span class="sub">보유창고 등기계약서</span>
					<span class="unit">
						<a href="../vender/trust_download.php?dir=/data/trust_store/&file_name=<?=$data->store_register?>"><?=$data->store_register?></a>
					</span>
				</li>
				<li>
					<span class="sub">
						위탁관리 물품 종류 및 수수료 설정
					</span>
					<span class="unit">
						<ul class="comi">
							<?//=$pr_commi?>
							<?
							for($i=1;$i<=4;$i++){
								if($i==1) $title = "MAIN";
								else $title = "SUB";

								$arrCommi[$i-1] = explode(":",$arrPr_commi[$i-1]);

							?>
							<li>
								<span class="title"><?=$title?></span>
								<select name="code<?=$i?>" id="code<?=$i?>">
									<option value="<?=$i?>">카테고리선택</option>
								<?
								$sql = "SELECT codeA,codeB,codeC,codeD,code_name,type FROM tblproductcode ";
								$sql.= "WHERE codeB='000' AND codeC='000' ";
								$sql.= "AND codeD='000' AND type LIKE 'L%' ORDER BY sequence DESC ";
								$result=mysql_query($sql,get_db_conn());
								while($row=mysql_fetch_object($result)) {
									$ctype=substr($row->type,-1);
									if($ctype!="X") $ctype="";
									echo "<option value=\"".$row->codeA."\" ctype='".$ctype."'";
									if($row->codeA==substr($arrCommi[$i-1][0],0,3)) echo " selected";
									echo ">".$row->code_name."";
									if($ctype=="X") echo " (단일분류)";
									echo "</option>\n";
								}
								mysql_free_result($result);
								?>
								</select>
								<input type="text" name="commi<?=$i?>" id="commi<?=$i?>" value="<?=$arrCommi[$i-1][1]?>"> %
							</li>
							<?
							} 
							?>
						</ul>
					</span>
				</li>
				<li>
					<span class="sub">운영 중인 온라인 쇼핑몰</span>
					<span class="unit"><input type="text" class="inputBox" name="homepage" value="<?=$data->homepage?>" placeholder="없을 경우 미기재"></span>
				</li>
				<li>
					<span class="sub">물품 출고 가능 시간</span>
					<span class="unit"><input type="text" class="inputBox" name="release_time" value="<?=$data->release_time?>" placeholder="ex)08:00~20:00"></span>
				</li>
				<li>
					<span class="sub">관리 담당자 성명</span>
					<span class="unit"><input type="text" class="inputBox" name="p_name" value="<?=$data->p_name?>"></span>
				</li>
				<li>
					<span class="sub">관리 담당자 연락처</span>
					<span class="unit"><input type="text" class="inputBox" name="p_mobile" value="<?=$data->p_mobile?>"></span>
				</li>
				<li>
					<span class="sub">관리 담당자 이메일</span>
					<span class="unit"><input type="text" class="inputBox" name="p_email" value="<?=$data->p_email?>"></span>
				</li>
				<li>
					<span class="sub">업체소개글</span>
					<span class="unit2">
						<textarea name="comp_info" id="comp_info" placeholder="위탁관리를 신청하는 업체로 공개되는 글입니다. 주요 장점 및 이력 등을 기재합니다."><?=$data->comp_info?></textarea><br>
						<font class="red">※ 위탁관리 업체등록 심사에는 최소 일주일이 소요됩니다. 등록 완료 시 등록하신 연락처로 안내드립니다.</font>
					</span>
				</li>
			</ul>
			<div class="applyBtn">
				<?
				if($data->approve=="Y"){
				?>
				<button type="button" class="okBtn" id="trust_modifyBtn">정보수정하기</button>
				<button type="button" class="noBtn" id="trust_cancelBtn">등록취소하기</button>
				<?
				}else{
				?>
				<button type="submit" class="okBtn" id="trust_applyBtn">위탁관리 업체등록 승인하기</button>
				<button type="button" class="noBtn" id="refuse">위탁관리 업체등록 거절하기</button>
				<?
				}
				?>
			</div>

			<div class="cancelForm">
				<span class="sub">거절사유 
				<textarea name="refuse_reason" id="refuse_reason"></textarea></span>
				<div class="applyBtn">
					<button type="button" class="smallBtn" id="cancelSubmit">거절</button>
					<button type="button" class="smallBtn" id="cancelBack">취소</button>
				</div>
			</div>
			<div class="clear"></div>
		</fieldset>
		</form>
	</article>

</section>

</body>
</html>