<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

//����Ȯ�� ��� ����
//_pr($_POST);

$req_result		= $_POST['req_result']; //�Ǹ����� ó�����(Y:����, N:�ź�)
$req_name		= $_POST['req_name']; //�Ǹ����� �̸�
$req_sex			= $_POST['req_sex']; //�Ǹ����� ����(M:����,W:����)
$req_birYMD	= $_POST['req_birYMD']; //�Ǹ����� �������
$req_cellNo		= $_POST['req_cellNo']; //�Ǹ����� �޴��� ��ȣ


/* ����Ȯ�� ���� */
/**************************************************************************************/
/* - ����� ��ȣȭ�� ���� IV ���� Random�ϰ� ������.(�ݵ�� �ʿ���!!)				*/
/* - input�ڽ� reqNum�� value����  echo $CurTime.$RandNo  ���·� ����		*/
/**************************************************************************************/
$CurTime = date(YmdHis);  //���� �ð� ���ϱ�

//6�ڸ� ������ ����
$RandNo = rand(100000, 999999);

$srvid = "SRNN001";
$srvNo = "001002";
//$reqNum = $CurTime.$RandNo;
$reqNum="0000000000000000"; //���� �ȵǼ� ���������� ó��(result ���� �����ϰ� ���� �ʿ�)
$certDate = $CurTime;
$certGb = "H";
$addVar = "";
$retUrl = "32http://beta.jamkan.com/Siren24_v2/pcc_V3_popup_seed2_v2.php";
$exVar = "0000000000000000"; // Ȯ���ӽ� �ʵ��Դϴ�. �������� ������..

//02. ��ȣȭ �Ķ���� ����
$reqInfo = $srvid . "^" . $srvNo . "^" . $reqNum . "^" . $certDate . "^" . $certGb . "^" . $addVar . "^" . $exVar;

$key = "3ECA075F0D94C1E583DC5A0968FD6F97";

syslog(LOG_NOTICE, $key);
//03. ����Ȯ�� ��û���� 1����ȣȭ
//2014.02.07 KISA �ǰ����
//�� ���� ��, �ҹ� �õ� ������ ���Ͽ� �Ʒ� ���Ͽ� �ش��ϴ� ���ڿ��� ���	
if(preg_match('~[^0-9a-zA-Z+/=^]~', $reqInfo, $matches)){
	echo "�Է� �� Ȯ���� �ʿ��մϴ�.(req)"; exit;
}

//��ȣȭ��� ��ġ�� ������ SciSecuX ������ �ִ� ������ ��θ� �������ּ���.
$enc_reqInfo = exec("/home/rental/public_html/Siren24_v2/SciSecuX SEED 1 2 $reqInfo $key");	//(ex: /home/name1/php_v2/SciSecuX)

//04. ��û���� ������������ ����
$hmac_str = exec("/home/rental/public_html/Siren24_v2/SciSecuX HMAC 1 2 $enc_reqInfo $key");

//05. ��û���� 2����ȣȭ
//������ ���� ��Ģ : "��û���� 1�� ��ȣȭ^������������^�Ϻ�ȭ Ȯ�� ����"
$enc_reqInfo = $enc_reqInfo. "^" .$hmac_str. "^" ."0000000000000000";
$enc_reqInfo = exec("/home/rental/public_html/Siren24_v2/SciSecuX SEED 1 2 $enc_reqInfo $key");

$enc_reqInfo = $enc_reqInfo. "^" .$srvid. "^" ."00000000";
$enc_reqInfo = exec("/home/rental/public_html/Siren24_v2/SciSecuX SEED 1 1 $enc_reqInfo $key");
/* ����Ȯ�� ���� */

$bizno1=trim($_POST["bizno1"]);
$bizno2=trim($_POST["bizno2"]);
$bizno3=trim($_POST["bizno3"]);
$bizno = $bizno1."-".$bizno2."-".$bizno3;

$rsql = "SELECT id FROM tblmember WHERE bizno='".$bizno."'";
$result2 = mysql_query($rsql,get_db_conn());
$num = mysql_num_rows($result2);
mysql_free_result($result2);
if ($num>0) {
	echo "<script>alert('����ڵ�Ϲ�ȣ�� �ߺ��Ǿ����ϴ�.');location.href='businessLicense_check.php';</script>";
	exit;
}
?>
<HTML>
<HEAD>
<TITLE><?=$_data->shoptitle?>����� ȸ�� ����</TITLE>
<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">
<META name="description" content="<?=(strlen($_data->shopdescription)>0?$_data->shopdescription:$_data->shoptitle)?>">
<META name="keywords" content="<?=$_data->shopkeyword?>">
<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
<?include($Dir."lib/style.php")?>
<? /*<link rel="stylesheet" type="text/css" href="/css/b2b_style.css" />*/ ?>
<link rel="stylesheet" type="text/css" href="/css/common.css" />
<link rel="stylesheet" type="text/css" href="/css/jamkan.css" />

<SCRIPT LANGUAGE="JavaScript">
<!--
function CheckFormData(data) {
	var numstr = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	var thischar;
	var count = 0;
	data = data.toUpperCase( data )

	for ( var i=0; i < data.length; i++ ) {
		thischar = data.substring(i, i+1 );
		if ( numstr.indexOf( thischar ) != -1 )
			count++;
	}
	if ( count == data.length )
		return(true);
	else
		return(false);
}

function CheckForm() {
	form=document.form1;

	if(form.id.value.length==0) {
		alert("���̵� �Է��ϼ���."); form.id.focus(); return;
	}
	if(form.id.value.length<4 || form.id.value.length>12) {
		alert("���̵�� 4�� �̻� 12�� ���Ϸ� �Է��ϼž� �մϴ�."); form.id.focus(); return;
	}
	if (CheckFormData(form.id.value)==false) {
		alert("ID�� ����, ���ڸ� �����Ͽ� 4~12�� �̳��� ����� �����մϴ�."); form.id.focus(); return;
	}
	if(form.passwd1.value.length==0) {
		alert("��й�ȣ�� �Է��ϼ���."); form.passwd1.focus(); return;
	}
	if(form.passwd1.value!=form.passwd2.value) {
		alert("��й�ȣ�� ��ġ���� �ʽ��ϴ�."); form.passwd2.focus(); return;
	}

	if(form.result.value.length==0) {
		alert("���������� �����ϼ���."); form.mobile.focus(); return;
	}

	var naverchk=document.form1.email.value;
	if(naverchk.indexOf("naver") != -1){
		//���̹��� ���� ���ʿ�
	}else{
		/*
		if(document.form1.cert_value.value != "000"){
			alert("�̸����� ������ �ּ���.");
			return;
		}
		*/
	}

	if(form.mobile.value.length==0) {
		alert("�޴���ȭ�� �Է��ϼ���."); form.mobile.focus(); return;
	}

	if(form.email.value.length==0) {
		alert("�̸����� �Է��ϼ���."); form.email.focus(); return;
	}
	if(!IsMailCheck(form.email.value)) {
		alert("�̸��� ������ �����ʽ��ϴ�.\n\nȮ���Ͻ� �� �ٽ� �Է��ϼ���."); form.email.focus(); return;
	}

	if(form.idChk.value=="0") {
		alert("���̵� �ߺ� üũ�� �ϼž� �մϴ�!");
		idcheck();
		return;
	}

	/*
	if(form.mailChk.value=="0") {
		alert("�̸��� �ߺ� üũ�� �ϼž� �մϴ�!");
		mailcheck();
		return;
	}
	*/

<?if($_data->ssl_type=="Y" && strlen($_data->ssl_domain)>0 && strlen($_data->ssl_port)>0 && $_data->ssl_pagelist["MJOIN"]=="Y") {?>
		form.action='https://<?=$_data->ssl_domain?><?=($_data->ssl_port!="443"?":".$_data->ssl_port:"")?>/<?=RootPath.SecureDir?>member_join.php';
<?}?>
	if(confirm("ȸ�������� �ϰڽ��ϱ�?"))
		form.submit();
	else
		return;
}


function idcheck() {
	form1.idChk.value="0";
	window.open("<?=$Dir.FrontDir?>iddup.php?id="+document.form1.id.value,"","height=260,width=282");
}

function mailcheck() {
	if(!IsMailCheck(form1.email.value)) {
		alert("�̸��� ������ �����ʽ��ϴ�.\n\nȮ���Ͻ� �� �ٽ� �Է��ϼ���.");
		form1.email.focus();
		return;
	}
	form1.mailChk.value="0";
	window.open("<?=$Dir.FrontDir?>mailcheck.php?email="+document.form1.email.value,"","height=150,width=200");
}
//-->
</SCRIPT>
</HEAD>
<body<?=(substr($_data->layoutdata["MOUSEKEY"],0,1)=="Y"?" oncontextmenu=\"return false;\"":"")?><?=(substr($_data->layoutdata["MOUSEKEY"],1,1)=="Y"?" ondragstart=\"return false;\" onselectstart=\"return false;\"":"")?> leftmargin="0" marginwidth="0" topmargin="0" marginheight="0"><?=(substr($_data->layoutdata["MOUSEKEY"],2,1)=="Y"?"<meta http-equiv=\"ImageToolbar\" content=\"No\">":"")?>
<? include ($Dir.MainDir.$_data->menu_type.".php") ?>

<!-- ����� ȸ������ �� start -->
<form name=form1 action="member_join.php" method="post" enctype="multipart/form-data">
<input type=hidden name="type" value="biz_insert">
<input type=hidden name="loginType" value="biz">
<input type=hidden name="idChk" value="0">
<input type=hidden name="mailChk" value="0">
<?if($_data->ssl_type=="Y" && strlen($_data->ssl_domain)>0 && strlen($_data->ssl_port)>0 && $_data->ssl_pagelist["MJOIN"]=="Y") {?>
<input type=hidden name=shopurl value="<?=getenv("HTTP_HOST")?>">
<?}?>
<input type=hidden name="member_gubun" value="company">
<input type=hidden name="bizno1" value="<?=$_POST['bizno1']?>">
<input type=hidden name="bizno2" value="<?=$_POST['bizno2']?>">
<input type=hidden name="bizno3" value="<?=$_POST['bizno3']?>">
<input type=hidden name="name" value="<?=$_POST['companyName']?>">

<div class="currentTitle">
	<h1 class="titleimage">����� ȸ������</h1>
</div>
<p class="noticeWrap"><span class="red">(��)�� �ʼ��Է� �׸��Դϴ�.</span></p>
<div class="joinCompanyWrap">
	<table border="0" cellpadding="0" cellspacing="0" width="100%"  class="basicTable_line2">
		<colgroup>
			<col width="150" align="right"></col>
			<col width="" style="padding-left:5px;"></col>
			<col width="115"></col>
			<col width="" align="right"></col>
		</colgroup>
		<tr>
			<th>��ȣ��</th>
			<td><?=$_POST['companyName']?></td>
			<th>����ڵ�Ϲ�ȣ</th>
			<td><?=$_POST['bizno1']."-".$_POST['bizno2']."-".$_POST['bizno3']?></td>
		</tr>
		<!--�����/��ü ���� -->
		<tr>
			<th align="left"><span class="red">��</span>�����/��ü ����</th>
			<td colspan="3" class="groupSelect">
				<input type="radio" id="groupSelect01" name="biz_gubun" value="corp" class="radio" checked />
				<label onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for="groupSelect01">����/��ü �����</label>

				<input type="radio" id="groupSelect02" name="biz_gubun" value="indi" class="radio" />
				<label onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for="groupSelect02">���λ����</label>

				<input type="radio" id="groupSelect03" name="biz_gubun" value="simp" class="radio" />
				<label onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for="groupSelect03">���̻����</label>

				<input type="radio" id="groupSelect04" name="biz_gubun" value="social" class="radio" />
				<label onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for="groupSelect04">��ȸ���� ��ü</label>
			</td>
		</tr>

		<!-- ���̵� -->
		<tr>
			<th><span class="red">��</span>���̵�</th>
			<td colspan="3" class="id">
				<INPUT type="text" name="id" value="<?=$id?>" maxLength="50" class="input" style="width:275px" /><A href="javascript:idcheck();"class="btn_gray"><span>���̵� �ߺ�üũ</span></a>
				<p style="color:#F02800;padding:10px 0px">*���� �ҹ���, ���� ���� 6~50��(-, _��밡��)</p>
			</td>
		</tr>

		<!-- ��й�ȣ/��й�ȣ Ȯ�� -->
		<tr>
			<th><span class="red">��</span>��й�ȣ</th>
			<td><INPUT type="password" name="passwd1" value="<?=$passwd1?>" maxLength="20" style="WIDTH:170px;" class="input" /></td>
			<th><span class="red">��</span>��й�ȣȮ��</th>
			<td><INPUT type="password" name="passwd2" value="<?=$passwd2?>" maxLength="20" style="WIDTH:170px; " class="input" /></td>
		</tr>

		<!-- �޴��� -->
		<tr>
			<th><span class="red">��</span>�޴���</th>
			<td colspan="3">
				<input type="text" maxlength="15" name="mobile" id="mobile" value="<?=$req_cellNo?>" style="WIDTH:275px;border: #F02800 1px solid;margin-right:5px;" class="input" onclick="openPCCWindow()" readonly /><a href="javascript:;" onclick="openPCCWindow()" class="btn_red"><span>��������</span></a>

				<input type="hidden" name="result" id="result" />
			</td>
		</tr>

		<!-- �̸��� -->
		<tr>
			<th><span class="red">��</span>�̸���</th>
			<td colspan="3">
				<input type="text" name="email" id="email" maxlength="100" style="WIDTH:275px" class="input" onkeyup="email_check('email');" autocomplete="off" />

				<div class="mainForm1LinkBtn2" id='email_cert' style="display:none">
					<a href="javascript:cert_key_open();" onclick="ga('send', 'event', '��ưŬ��', 'ȸ������ ��������', 'ȸ������ ������');" class="btn_red"><span>�����ϱ�</span></a>
				</div>

				<!--<span style="padding-left:5px;color:#F02800">*�����ּ� ����� ������ �ʿ��մϴ�.</span>-->

				<div id="msg_email" style="display:none;margin-top:10px"></div>
				<input type="hidden" name="email_enabled" id="email_enabled" />
				<input type="hidden" name="cert_value" id="cert_value" />

				<div class="mainForm1LinkBtn3" id='email_cert2' style="display:none;margin-top:4px">
					<input type="text" name="cret_num" id="cret_num" placeholder="�̸��� ������ȣ�� �Է��ϼ���." class="input" style="width:275px;border:1px solid #F02800;box-sizing:border-box;" autocomplete="off" />
					<a href="javascript:cert_key_ok();" class="btn_red"><span>����</span></a><a href="javascript:cert_key_go();" onclick="ga('send', 'event', '��ưŬ��', 'ȸ������ �������� ��߼�', 'ȸ������ ������');" class="btn_gray"><span>��߼�</span></a>
					<div style="margin-top:10px">
						���� ������ ���� �̸��� ������ �ִ� 5�� ���� ������ �� �ֽ��ϴ�.<br />
						���������� 5�� �Ŀ��� �������� ���� ��� ���� ó��, �뷮 �ʰ�, �޽��� ���� ���� ���� Ȯ���� �ּ���.
					</div>
				</div>

				<script>
					$j('#email').blur(function() {
						email_check('email');
						/*
						if($j('#email_enabled').val() == '000' && $j('#cert_value').val() != '000' ){
							if($j("#email_cert2").css("display") == "none"){
								$j('#email_cert').fadeIn();
								$j('#email_cert').css('display','inline-block');
								$j('#id').css('background', '');
							}
						}
						*/
					});

					/*
					$j('#email').focus(function() {
						$j('#email_cert').css({"display":"none"});
					});
					*/
				</script>

				<? /*
				<a href="javascript:mailcheck();" class="btn_gray"><span>���� ����</span></a>
				<p style="color:#F02800;padding:10px 0px">*Naver ����(aaa@naver.com) �� �ٸ� �����ּҷ� �����Ͽ� ���� �����մϴ�.</p>
				*/ ?>
			</td>
		</tr>

		<!-- ����ڵ���� ÷�� -->
		<tr>
			<th class="thLast"><span style="padding-left:12px">����ڵ����</span></th>
			<td colspan="3" class="tdLast">
				<INPUT type="file" name="bizcheck" />
				<p style="color:#F02800;padding:10px 0px">*�̹��� �� PDF(gif, jpg, png, pdf) ���ϸ� ÷�ΰ� �����մϴ�.</p>
			</td>
		</tr>
	</table>
</div>
<div class="btnWrap">
	<a href="javascript:CheckForm();" class="btn_grayB"><span>ȸ������ �Ϸ�</span></a>
	<a href="javascript:history.go(-1);" class="btn_lineB"><span>�ٽ��ۼ�</span></a>
</div>
<!-- ����� ȸ������ �� end -->

</form>

<?=$onload?>

<script>
	var ck_path = "../";
</script>
<script src="/js/ajax_form.js"></script>

<!-- ����Ȯ�� ���� -->
<script language=javascript>
<!--
	var CBA_window; 

	function openPCCWindow(){ 
		window.name = "JOINWindow";
		var CBA_window = window.open('', 'PCCWindow', 'width=430, height=560, resizable=1, scrollbars=no, status=0, titlebar=0, toolbar=0, left=300, top=200' );

		if(CBA_window == null){ 
			 alert(" �� ������ XP SP2 �Ǵ� ���ͳ� �ͽ��÷η� 7 ������� ��쿡�� \n    ȭ�� ��ܿ� �ִ� �˾� ���� �˸����� Ŭ���Ͽ� �˾��� ����� �ֽñ� �ٶ��ϴ�. \n\n�� MSN,����,���� �˾� ���� ���ٰ� ��ġ�� ��� �˾������ ���ֽñ� �ٶ��ϴ�.");
		}

		document.reqCBAForm.action = 'https://pcc.siren24.com/pcc_V3/jsp/pcc_V3_j10_v2.jsp';
		document.reqCBAForm.target = 'PCCWindow';
		document.reqCBAForm.submit();
		//return true;
	}

	function sirenResult(name,mobile,sex,birth,result){
		$j("#mobile").val(mobile);
		$j("#result").val(result);
	}

//-->
</script>
<!-- ����Ȯ�� ���� -->

<!-- ����Ȯ�μ��� ��û form --------------------------->
<form name="reqCBAForm" method="post" action = "" onsubmit="return openPCCWindow()">
	<input type="hidden" name="reqInfo"     value = "<? echo "$enc_reqInfo" ?>">
	<input type="hidden" name="retUrl"      value = "<? echo "$retUrl" ?>">
	<input type="hidden" name="verSion"		value = "2"><!--��� ��������-->
</form>
<!--End ����Ȯ�μ��� ��û form ----------------------->

<script>
	//�̸��� �ߺ�üũ ó�� �ѹ� ����
	$j(function(){
		setTimeout(function(){
			email_check('email');
		},10);
	});
</script>

<? include ($Dir."lib/bottom.php") ?>