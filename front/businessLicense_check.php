<?
	$Dir="../";
	include_once($Dir."lib/init.php");
	include_once($Dir."lib/lib.php");
	include_once($Dir."lib/shopdata.php");

	if(strlen($_ShopInfo->getMemid())>0) {
		
		if($bizcheck=="ok"){
			$sql = "UPDATE tblmember SET authidkey='logout' WHERE id='".$_ShopInfo->getMemid()."' ";
			if(false !== mysql_query($sql,get_db_conn()) && $_ShopInfo->getTempkey() !=""){			
				// �α׾ƿ��� ��ٱ��� ����
				
				// 160202 �α׾ƿ� �� ȸ�� ���̵� ���� ��ٱ��� ��ǰ�� ����ó��.
				$where = "tempkey='{$_ShopInfo->getTempkey()}' AND (id IS NULL or id = '')";

				$delBasket = "DELETE FROM tblbasket WHERE ".$where;
				@mysql_query($delBasket,get_db_conn());
				$delBasket2 = "DELETE FROM tblbasket2 WHERE ".$where;
				@mysql_query($delBasket2,get_db_conn());
				$delBasket3 = "DELETE FROM tblbasket3 WHERE ".$where;
				@mysql_query($delBasket3,get_db_conn());
				$delBasket4 = "DELETE FROM tblbasket4 WHERE ".$where;
				@mysql_query($delBasket4,get_db_conn());
				
				// �α׾ƿ��� ��ٱ��� ���� ��	
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
<TITLE><?=$_data->shoptitle?>����� ȸ������</TITLE>
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
		alert("�������� ���� ����ڵ�Ϲ�ȣ �Դϴ�.\n����ڵ�Ϲ�ȣ�� �ٽ� �Է��ϼ���.");
		frm.bizno1.value = "";
		frm.bizno2.value = "";
		frm.bizno3.value = "";
		frm.bizno1.focus();
		$j("input[name=bizno1]").removeAttr("readonly");
		$j("input[name=bizno2]").removeAttr("readonly");
		$j("input[name=bizno3]").removeAttr("readonly");
		return;
	}else{
		alert("�����Ǿ����ϴ�.");
		frm.biznocheck.value = "Y";
		$j("input[name=bizno1]").attr("readonly",true);
		$j("input[name=bizno2]").attr("readonly",true);
		$j("input[name=bizno3]").attr("readonly",true);
	}
}


function checkForm() {
	var frm = document.form;
	
	if(frm.biznocheck.value=="N"){
		alert("����ڵ�Ϲ�ȣ ������ �ϼž� �մϴ�.");
		frm.bizno1.focus();
		return;
	}

	if(frm.companyName.value==""){
		alert("��ȣ���� �Է��ϼ���.");
		frm.companyName.focus();
		return;
	}

	if(frm.agree.checked==false){
		alert("�̿����� �����ϼž� �մϴ�.");
		frm.agree.focus();
		return;
	}

	if(frm.agreep.checked==false){
		alert("����������޹�ħ�� �����ϼž� �մϴ�.");
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



<!-- ����� ȸ������ start -->
<div class="currentTitle">
	<h1 class="titleimage">����� ȸ������</h1>
</div>

<form name="form" method="post" action="member_join2.php">
<div class="businessLicense_checkWrap">
	<div class="businessLicense_check">
		<h2>����ڹ�ȣ ��ȿ�� �˻� �� ���Կ��� Ȯ��</h2>
		<? /*
		<ul>
			<li style="line-height:23px;">������ ������ ���� ���, <a href="http://www.niceinfo.co.kr/main.nice" target="_blank">NICE��������</a> ������(02-3771-1011)�� ���� ��Ź �帳�ϴ�.<br>(����ڹ�ȣ�� ��Ȯ�� Ȯ�� �� ������ �� �õ� �ϰų�, NICE�������� �Ǵ� ����û���� ����� ����� ��û���ֽñ� �ٶ��ϴ�.)</li>
		</ul>
		*/ ?>

		<p class="text">����� ��Ϲ�ȣ ������ ���� ���� ���, �Ʒ� ������� Ȯ���Ͻ� �� �ֽ��ϴ�.</p>
		<ul>
			<li>- ����� ��Ϲ�ȣ ���� ���(�ٷΰ���)</li>
			<li>- FAX���� : 02-3447-0102�� ����ڵ���� 1�� �߼�(����ó ����)</li>
			<!--<li>- ���� ���� : <a href="http://www.niceinfo.co.kr/main.nice" target="_blank">NICE������(��)</a> �����ͷ� ���� �ٶ��ϴ�.(02-3771-1011)</li>-->
		</ul>
		
		
		<table border="0" cellpadding="0" cellspacing="0" width="100%" class="basicTable_line2">
			<colgroup>
				<col width="150" align="right">
				<col width="">
			</colgroup>
			<tbody>
			<tr>
				<th>����ڵ�Ϲ�ȣ</th>
				<td>
					<input type="text" name="bizno1" value="" maxlength="3" class="input" style="text-align:center" /> - 
					<input type="text" name="bizno2" value="" maxlength="2" class="input" style="text-align:center" /> - 
					<input type="text" name="bizno3" value="" maxlength="5" class="input" style="text-align:center" />
					<input type="hidden" name="biznocheck" value="N" />
					<a href="javascript:BusinessLicenseNoCheck();" class="btn_gray"><span>����ڵ�Ϲ�ȣ ����</span></a>
				</td>
			</tr>
			<tr>
				<th>��ȣ��</th>
				<td>
					<input type="text" name="companyName" value="" maxlength="20" style="WIDTH:253px;" class="input">
				</td>
			</tr>
		</table>
	</div>

	<div class="agreeZone">
		<p>
			<input id="idx_agree" type="checkbox" class="checkbox" name="agree" style="position:relative;top:2px;border:none;" />
			<label style="cursor: pointer; text-decoration: none;" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_agree">���� ȸ������� �����մϴ�.</label>
			<a href="javascript:viewPolicy();" class="btn">�̿��� ���� ></a>
		</p>
		<p>
			<input id="idx_agreep" type="checkbox" class="checkbox" name="agreep" style="position:relative;top:2px;border:none;"  />
			<label style="cursor:hand;" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_agreep">���� ����������޹�ħ�� �����մϴ�.</label>
			<a href="javascript:viewProtect();" class="btn">����������޹�ħ ���� ></a>
		</p>
	</div>

	<div class="btnWrap"><a href="javascript:checkForm()" class="btn_grayB"><span>ȸ������</span></a></div>
</div>
</form>


<!-- �̿��� ��ü���� -->
<div class="policyView" id="policyView" style='display: none;'>
	<div class='viewBox1'>
		<div class='viewCloseBtn'><a style='line-height: 120%; font-size: 30px; text-decoration: none;' href='javascript:hiddenPolicy();'>��</a></div>
		<h4>ȸ������ �������</h4>
		<div class="viewBox2"><?=$agreement?></div>
	</div>
</div>
<!-- ����������޹�ħ ��ü���� -->
<div class='policyView' id='ProtectView' style='display: none;'>
	<div class='viewBox1'>
		<div class='viewCloseBtn'><a style='line-height: 120%; font-size: 30px; text-decoration: none;' href='javascript:hiddenProtect();'>��</a></div>
		<h4>����������޹�ħ ����</h4>
		<div class='viewBox2'><?=$privercy?></div>
	</div>
</div>

<link rel="stylesheet" href="/css/jquery-ui/jquery-ui.min.css">
<script type="text/javascript" src="/js/jquery-ui.min.js"></script>
<script type="text/javascript">
<!--
/* �̿��� ���� */
function viewPolicy(){
	$j("#policyView").show();
}
/* �̿��� �ݱ� */
function hiddenPolicy(){
	$j("#policyView").hide();
}
/* ����������޹�ħ ���� */
function viewProtect(){
	$j("#ProtectView").show();
}
/* ����������޹�ħ �ݱ� */
function hiddenProtect(){
	$j("#ProtectView").hide();
}
</script>

<!-- ����� ȸ������ end -->






<? include ($Dir."lib/bottom.php") ?>
</BODY>
</HTML>