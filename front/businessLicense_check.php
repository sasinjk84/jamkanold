<?
	$Dir="../";
	include_once($Dir."lib/init.php");
	include_once($Dir."lib/lib.php");
	include_once($Dir."lib/shopdata.php");

	if(strlen($_ShopInfo->getMemid())>0) {
		
		if($bizcheck=="ok"){
			$sql = "UPDATE tblmember SET authidkey='logout' WHERE id='".$_ShopInfo->getMemid()."' ";
			if(false !== mysql_query($sql,get_db_conn()) && $_ShopInfo->getTempkey() !=""){			
				// 로그아웃시 장바구니 비우기
				
				// 160202 로그아웃 시 회원 아이디가 없는 장바구니 상품만 삭제처리.
				$where = "tempkey='{$_ShopInfo->getTempkey()}' AND (id IS NULL or id = '')";

				$delBasket = "DELETE FROM tblbasket WHERE ".$where;
				@mysql_query($delBasket,get_db_conn());
				$delBasket2 = "DELETE FROM tblbasket2 WHERE ".$where;
				@mysql_query($delBasket2,get_db_conn());
				$delBasket3 = "DELETE FROM tblbasket3 WHERE ".$where;
				@mysql_query($delBasket3,get_db_conn());
				$delBasket4 = "DELETE FROM tblbasket4 WHERE ".$where;
				@mysql_query($delBasket4,get_db_conn());
				
				// 로그아웃시 장바구니 비우기 끝	
			}

			$_ShopInfo->SetMemNULL();
			//$_ShopInfo->setTempkey(0);
			//$_ShopInfo->setTempkey($_data->ETCTYPE["BASKETTIME"]);
			$_ShopInfo->Save();
		}else{
			header("Location:mypage_usermodify.php");
			if( $preview===false ) exit;
		}
	}

	$leftmenu="Y";
	$sql="SELECT body,leftmenu FROM ".$designnewpageTables." WHERE type='joinagree'";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$body=$row->body;
		$body=str_replace("[DIR]",$Dir,$body);
		$leftmenu=$row->leftmenu;
		$newdesign="Y";
	}
	mysql_free_result($result);
?>

<HTML>
<HEAD>
<TITLE><?=$_data->shoptitle?>사업자 회원가입</TITLE>
<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">
<META http-equiv="X-UA-Compatible" content="IE=Edge" />
<META name="description" content="<?=(strlen($_data->shopdescription)>0?$_data->shopdescription:$_data->shoptitle)?>">
<META name="keywords" content="<?=$_data->shopkeyword?>">
<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
<script type="text/javascript" src="/js/jquery-1.8.3.min.js"></script>
<script language="javascript" type="text/javascript">
$j = jQuery.noConflict();
</script>

<script language="JavaScript">
<!--
function BusinessLicenseNoCheck() {
	var frm = document.form;
	var bizno;
	var bb;
	bizno = frm.bizno1.value+frm.bizno2.value+frm.bizno3.value;
	bb = chkBizNo(bizno);
	if (!bb) {
		alert("인증되지 않은 사업자등록번호 입니다.\n사업자등록번호를 다시 입력하세요.");
		frm.bizno1.value = "";
		frm.bizno2.value = "";
		frm.bizno3.value = "";
		frm.bizno1.focus();
		$j("input[name=bizno1]").removeAttr("readonly");
		$j("input[name=bizno2]").removeAttr("readonly");
		$j("input[name=bizno3]").removeAttr("readonly");
		return;
	}else{
		alert("인증되었습니다.");
		frm.biznocheck.value = "Y";
		$j("input[name=bizno1]").attr("readonly",true);
		$j("input[name=bizno2]").attr("readonly",true);
		$j("input[name=bizno3]").attr("readonly",true);
	}
}


function checkForm() {
	var frm = document.form;
	
	if(frm.biznocheck.value=="N"){
		alert("사업자등록번호 인증을 하셔야 합니다.");
		frm.bizno1.focus();
		return;
	}

	if(frm.companyName.value==""){
		alert("상호명을 입력하세요.");
		frm.companyName.focus();
		return;
	}

	if(frm.agree.checked==false){
		alert("이용약관에 동의하셔야 합니다.");
		frm.agree.focus();
		return;
	}

	if(frm.agreep.checked==false){
		alert("개인정보취급방침에 동의하셔야 합니다.");
		frm.agreep.focus();
		return;
	}

	frm.submit();
}
//-->
</script>
<?include($Dir."lib/style.php")?>
</HEAD>
<body<?=(substr($_data->layoutdata["MOUSEKEY"],0,1)=="Y"?" oncontextmenu=\"return false;\"":"")?><?=(substr($_data->layoutdata["MOUSEKEY"],1,1)=="Y"?" ondragstart=\"return false;\" onselectstart=\"return false;\"":"")?> leftmargin="0" marginwidth="0" topmargin="0" marginheight="0"><?=(substr($_data->layoutdata["MOUSEKEY"],2,1)=="Y"?"<meta http-equiv=\"ImageToolbar\" content=\"No\">":"")?>
<? include ($Dir.MainDir.$_data->menu_type.".php") ?>



<!-- 사업자 회원가입 start -->
<div class="currentTitle">
	<h1 class="titleimage">사업자 회원가입</h1>
</div>

<form name="form" method="post" action="member_join2.php">
<div class="businessLicense_checkWrap">
	<div class="businessLicense_check">
		<h2>사업자번호 유효성 검사 및 가입여부 확인</h2>
		<? /*
		<ul>
			<li style="line-height:23px;">인증에 문제가 있을 경우, <a href="http://www.niceinfo.co.kr/main.nice" target="_blank">NICE평가정보㈜</a> 고객센터(02-3771-1011)로 문의 부탁 드립니다.<br>(사업자번호를 정확히 확인 후 인증을 재 시도 하거나, NICE평가정보㈜ 또는 국세청으로 사업자 등록을 신청해주시기 바랍니다.)</li>
		</ul>
		*/ ?>

		<p class="text">사업자 등록번호 인증이 되지 않을 경우, 아래 방법으로 확인하실 수 있습니다.</p>
		<ul>
			<li>- 사업자 등록번호 직접 등록(바로가기)</li>
			<li>- FAX접수 : 02-3447-0102로 사업자등록증 1부 발송(연락처 기재)</li>
			<!--<li>- 관련 문의 : <a href="http://www.niceinfo.co.kr/main.nice" target="_blank">NICE평가정보(주)</a> 고객센터로 문의 바랍니다.(02-3771-1011)</li>-->
		</ul>
		
		
		<table border="0" cellpadding="0" cellspacing="0" width="100%" class="basicTable_line2">
			<colgroup>
				<col width="150" align="right">
				<col width="">
			</colgroup>
			<tbody>
			<tr>
				<th>사업자등록번호</th>
				<td>
					<input type="text" name="bizno1" value="" maxlength="3" class="input" style="text-align:center" /> - 
					<input type="text" name="bizno2" value="" maxlength="2" class="input" style="text-align:center" /> - 
					<input type="text" name="bizno3" value="" maxlength="5" class="input" style="text-align:center" />
					<input type="hidden" name="biznocheck" value="N" />
					<a href="javascript:BusinessLicenseNoCheck();" class="btn_gray"><span>사업자등록번호 인증</span></a>
				</td>
			</tr>
			<tr>
				<th>상호명</th>
				<td>
					<input type="text" name="companyName" value="" maxlength="20" style="WIDTH:253px;" class="input">
				</td>
			</tr>
		</table>
	</div>

	<div class="agreeZone">
		<p>
			<input id="idx_agree" type="checkbox" class="checkbox" name="agree" style="position:relative;top:2px;border:none;" />
			<label style="cursor: pointer; text-decoration: none;" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_agree">위의 회원약관에 동의합니다.</label>
			<a href="javascript:viewPolicy();" class="btn">이용약관 보기 ></a>
		</p>
		<p>
			<input id="idx_agreep" type="checkbox" class="checkbox" name="agreep" style="position:relative;top:2px;border:none;"  />
			<label style="cursor:hand;" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_agreep">위의 개인정보취급방침에 동의합니다.</label>
			<a href="javascript:viewProtect();" class="btn">개인정보취급방침 보기 ></a>
		</p>
	</div>

	<div class="btnWrap"><a href="javascript:checkForm()" class="btn_grayB"><span>회원가입</span></a></div>
</div>
</form>


<!-- 이용약관 전체보기 -->
<div class="policyView" id="policyView" style='display: none;'>
	<div class='viewBox1'>
		<div class='viewCloseBtn'><a style='line-height: 120%; font-size: 30px; text-decoration: none;' href='javascript:hiddenPolicy();'>×</a></div>
		<h4>회원가입 약관보기</h4>
		<div class="viewBox2"><?=$agreement?></div>
	</div>
</div>
<!-- 개인정보취급방침 전체보기 -->
<div class='policyView' id='ProtectView' style='display: none;'>
	<div class='viewBox1'>
		<div class='viewCloseBtn'><a style='line-height: 120%; font-size: 30px; text-decoration: none;' href='javascript:hiddenProtect();'>×</a></div>
		<h4>개인정보취급방침 보기</h4>
		<div class='viewBox2'><?=$privercy?></div>
	</div>
</div>

<link rel="stylesheet" href="/css/jquery-ui/jquery-ui.min.css">
<script type="text/javascript" src="/js/jquery-ui.min.js"></script>
<script type="text/javascript">
<!--
/* 이용약관 열기 */
function viewPolicy(){
	$j("#policyView").show();
}
/* 이용약관 닫기 */
function hiddenPolicy(){
	$j("#policyView").hide();
}
/* 개인정보취급방침 열기 */
function viewProtect(){
	$j("#ProtectView").show();
}
/* 개인정보취급방침 닫기 */
function hiddenProtect(){
	$j("#ProtectView").hide();
}
</script>

<!-- 사업자 회원가입 end -->






<? include ($Dir."lib/bottom.php") ?>
</BODY>
</HTML>