<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

$mode=$_POST["mode"];


if(!_empty($_ShopInfo->getMemid())){
	$sql = "SELECT * FROM tblmember WHERE id='".$_ShopInfo->getMemid()."' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$_mdata=$row;
		$sendUrl_id = $row->url_id;
		$sendId = $row->id;
		$sendName = $row->name;
		$sendEmail = $row->email;
	}
	mysql_free_result($result);
}
if($_data->recom_url_ok != "Y"){
	//echo "<html><head><title></title></head><body onload=\"alert('ȫ���������� �����Ǿ������ʽ��ϴ�.');window.close();\"></body></html>";exit;
	echo "<html><head><title></title></head><body onload=\"alert('ȫ���������� �����Ǿ������ʽ��ϴ�.');history.back(-1);\"></body></html>";exit;
}

if($mode=="send" && $sendUrl_id && $sendName) {
	$arEmails=explode(",", $_POST["in_email"]);
	$message=$_POST["in_message"];

	$mess2=$row->email."�� ������ ";
	for($i=0;$i<sizeof($arEmails);$i++) {
		SendUrlMail($_data->shopname, $_data->shopurl, $_data->design_mail, $message, $sendEmail, $arEmails[$i], $sendName, $sendUrl_id, $sendId, $_data->recom_memreserve);
	}
	echo "<html><head><title></title></head><body onload=\"alert('������ ���۵Ǿ����ϴ�.'); location.href='/front/member_urlhongbo.php'; \"></body></html>";exit;
}

$hongboUrl = "http://".$_data->shopurl."?token=".$sendUrl_id;
$hongboTle = sprintf("[%s]�� �����ϼ���.",$_data->shopname);

$sAddRecom = "";
if($_data->reserve_join >0){
	$sAddRecom = $_data->shopname."�� ��õ�Ͽ� ������ ������ ù �����Ҷ� ���� ������<span style=\"color:#CC0035\">".$_data->reserve_join."��</span>�� �帳�ϴ�.<br/>";
}
if($_data->recom_ok == "Y") {
	$arRecomType = explode("", $_data->recom_memreserve_type);

	if($arRecomType[0] == "A"){
		$sAddRecom.= "�Ұ� ���� ģ������ �ű�ȸ�����Խ� <span style=\"color:#CC0035\">".$_data->recom_memreserve."��</span>�� �������� ������ �� �ִ�ϴ�.</span>";
		$sAddRecom2 ="ȸ���Կ� URL�ּҷ� ������ ��� �ű�ȸ�����Խ� <span style=\"color:#CC0035\">".$_data->recom_memreserve."��</span>�� �������� �帳�ϴ�.";
	}else if($arRecomType[0] == "B"){
		$sAddRecom .= "��õ ���� ���ε鿡�Ե� ù ���� �� ���� ������<span style=\"color:#CC0035\">";
		$sAddRecom2 = "ȸ������ ���� ȫ��URL ��õ�� ���� ���ε��� ȸ�������Ҷ����� ȸ���Կ��� ������<span style=\"color:#CC0035\">";
		if($arRecomType[1] == "A"){
			if($arRecomType[2] == "N"){
				$sAddRecom .= $_data->recom_memreserve."��";
				$sAddRecom2 .= $_data->recom_memreserve."��</span>";
			}else if($arRecomType[2] == "Y"){
				$sAddRecom .= "���űݾ��� ".$_data->recom_memreserve."%��";
				$sAddRecom2 .= "���űݾ��� ".$_data->recom_memreserve."%</span>��";
			}
		}else if($arRecomType[1] == "B"){
			$sAddRecom .= "���űݾ׿� ����";
			$sAddRecom2 .= "���űݾ׿� ����</span>";
		}
		$sAddRecom .= "</span>�� �����ص帳�ϴ�.";
		$sAddRecom2 .="�� <br />�帳�ϴ�. ��õ���� ���ε� ù ���� �� ���� �߰��������� �����ص帳�ϴ�.";
	}
}








// SMS ȫ�� �߼�
if( $mode == "sms_urlhongbo" ) {
	$sql="SELECT * FROM tblsmsinfo ";
	$result=mysql_query($sql,get_db_conn());
	if($rowsms=mysql_fetch_object($result)) {
		$sms_id=$rowsms->id;
		$sms_authkey=$rowsms->authkey;

		$sender = $_POST["send1"].$_POST["send2"].$_POST["send3"];
		$cell = $_POST["cel1"].$_POST["cel2"].$_POST["cel3"];

		$msg_hongbo = "[".$_data->shopname."]������õ �ٷΰ��� : ".$hongboUrl;

		$etcmsg = "������õ URL";

		$use_mms = $rowsms->use_mms;

		$temp=SendSMS2($sms_id, $sms_authkey, $cell, "", $sender, 0, $msg_hongbo, $etcmsg, $use_mms);
		$resmsg=explode("[SMS]",$temp);
		echo "<html></head><body onload=\"alert('".$resmsg[1]."'); location.href='/front/member_urlhongbo.php'; \"></body></html>";
		exit;
	}
}


?>
<HTML>
<HEAD>
<TITLE><?=$_data->shoptitle?> - �ҹ�����</TITLE>
<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">

<META name="description" content="<?=(strlen($_data->shopdescription)>0?$_data->shopdescription:$_data->shoptitle)?>">
<META name="keywords" content="<?=$_data->shopkeyword?>">
<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
<?include($Dir."lib/style.php")?>
<SCRIPT LANGUAGE="JavaScript">
<!--
function ClipCopy(url) {
	var tmp;
	tmp = window.clipboardData.setData('Text', url);
	if(tmp) {
		alert('�ּҰ� ����Ǿ����ϴ�.');
	}
}


function CheckForm() {
	if(document.form1.in_email.value.length==0) {
		alert("�̸����� �Է��ϼ���.");
		document.form1.in_email.focus();
		return;
	}
	var email = document.form1.in_email.value;
	if(email.indexOf(",") >0){
		arEmail = email.split(",");
		for(i=0;i<arEmail.length;i++){
			if(!IsMailCheck(arEmail[i].trim())) {
				alert("�̸��� ������ �����ʽ��ϴ�.\n\nȮ���Ͻ� �� �ٽ� �Է��ϼ���.");
				document.form1.in_email.focus(); return;
			}
		}
	}else{
		if(!IsMailCheck(email.trim())) {
			alert("�̸��� ������ �����ʽ��ϴ�.\n\nȮ���Ͻ� �� �ٽ� �Է��ϼ���.");
			document.form1.in_email.focus(); return;
		}
	}
	if(document.form1.in_message.value.length==0) {
		alert("�������� �Է��ϼ���.");
		document.form1.in_message.focus();
		return;
	}
	document.form1.mode.value="send";
	document.form1.submit();
}

function goFaceBook()
{
	var href = "http://www.facebook.com/sharer.php?u=" + encodeURIComponent('<?=$hongboUrl?>') + "&t=" + encodeURIComponent('<?=$hongboTle?>');
	var a = window.open(href, 'Facebook', '');
	if (a) {
		a.focus();
	}
}

function goTwitter()
{
	var href = "http://twitter.com/share?text=" + encodeURIComponent('<?=$hongboTle?>') + " " + encodeURIComponent('<?=$hongboUrl ?>');
	var a = window.open(href, 'Twitter', '');
	if (a) {
		a.focus();
	}
}

function goMe2Day()
{
	var href = "http://me2day.net/posts/new?new_post[body]=" + encodeURIComponent('<?=$hongboTle?>') + " " + encodeURIComponent('<?=$hongboUrl ?>') + "&new_post[tags]=" + encodeURIComponent('<?=$_data->shopname?>');
	var a = window.open(href, 'Me2Day', '');
	if (a) {
		a.focus();
	}
}

function goCyworld(){
	var href = "http://csp.cyworld.com/bi/bi_recommend_pop.php?url=" + encodeURIComponent('<?=$hongboUrl ?>') + "&title_nobase64=" + encodeURIComponent('<?=$hongboTle?>') + "&thumbnail=" +  encodeURIComponent("http://<?=$_ShopInfo->getShopurl()?>images/winywill.jpg") + "&write=" + encodeURIComponent('http://<?=$_data->shopurl?>');
	var a = window.open(href, 'Cyworld', 'width=466, height=356');
	if (a) {
		a.focus();
	}
}

function goYozmDaum()
{
	var href = "http://yozm.daum.net/api/popup/prePost?sourceid=54&link=" + encodeURIComponent('<?=$hongboUrl ?>') + "&prefix=" + encodeURIComponent('<?=$_data->shopname ?> > <?=$hongboTle?>\'') + "&parameter=" + encodeURIComponent('<?=$hongboTle?>');
	var a = window.open(href, 'yozmSend', 'width=466, height=356');
	if (a) {
		a.focus();
	}
}

function nologin(){
	alert('���� ȫ��URL�� ȸ������ ����Դϴ�.\nȸ�� �α��� �� �̿��� �ּ���.');
	window.location='/front/login.php';
}

//window.resizeTo(730,765);
//-->
</SCRIPT>
</HEAD>

<body<?=(substr($_data->layoutdata["MOUSEKEY"],0,1)=="Y"?" oncontextmenu=\"return false;\"":"")?><?=(substr($_data->layoutdata["MOUSEKEY"],1,1)=="Y"?" ondragstart=\"return false;\" onselectstart=\"return false;\"":"")?> leftmargin="0" marginwidth="0" topmargin="0" marginheight="0"><?=(substr($_data->layoutdata["MOUSEKEY"],2,1)=="Y"?"<meta http-equiv=\"ImageToolbar\" content=\"No\">":"")?>

<?
	include ($Dir.MainDir.$_data->menu_type.".php");
?>

<table cellpadding="0" cellspacing="0" width="100%" align="center">
	<tr>
		<td>

			<div class="memberbenefit">
				<h2>MUST HAVE! ��������</h2>
				<div><img src="/images/003/benefit_top.jpg" alt="" /></div>
				<div class="benefitmenu">
					<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td><a href="/front/newpage.php?code=1">ȸ������</a></td>
							<td><a href="/front/newpage.php?code=2">��ǰ������</a></td>
							<td><a href="/front/couponlist.php">��������</a></td>
							<td><a href="/front/productgift.php">�����̿��</a></td>
							<td><a href="/front/attendance.php">�⼮üũ</a></td>
							<td class="nowon"><a href="/front/member_urlhongbo.php">ȫ������������</a></td>
							<td><a href="/board/board.php?board=storytalk">���丮��</a></td>
						</tr>
					</table>
				</div>

				<script language="javascript">
					function prgift(){
						window.open("/data/design/popup/productgift.php","offlinecoupon_pop","height=570,width=590,scrollbars=yes");
					}
				</script>
				<div class="urlhongbo">
					<h3>ȫ������������</h3>
					<h4>���� ȫ��URL�� �˸���</h4>
				</div>
			</div>

		</td>
	</tr>
	<!--
	<tr>
		<td colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td width="17" align="left"><IMG SRC="../images/design/pop_view_head.gif" WIDTH=17 HEIGHT=44 ALT=""></td>
					<td background="../images/design/pop_view_headbg.gif"><IMG SRC="../images/design/detail_pop_email_title.gif" WIDTH="164" HEIGHT=44 ALT=""></td>
					<td width="47" align="right"><a href="javascript:window.close();"><IMG SRC="../images/design/pop_view_exit.gif" WIDTH=47 HEIGHT=44 ALT=""></a></td>
				</tr>
			</table>
		</td>
	</tr>
	-->
	<tr>
		<td style="border:5px solid #f3f4f6; padding:40px 0px;">
			<table cellpadding="0" cellspacing="0" align="center" width="85%" border="0" style="margin:0 auto;">
				<tr>
					<td><IMG SRC="../images/design/detail_pop_email_text.gif" ALT=""></td>
				</tr>
				<tr><td height="20"></td></tr>
				<tr>
					<td class="table01_con"><?=$sAddRecom?></td>
				</tr>
				<tr><td height="30"></td></tr>
				<tr>
					<td><IMG SRC="../images/design/detail_pop_email_line.gif" WIDTH=100% HEIGHT=1 ALT=""></td>
				</tr>
				<tr><td height="20"></td></tr>
				<tr>
					<td>
						<table cellpadding="2" cellspacing="0" width="100%">
							<tr>
								<td width="120"><IMG SRC="../images/design/detail_pop_email_img01.gif" WIDTH=95 HEIGHT=95 ALT=""></td>
								<td class="table01_con">�Ʒ� ȸ������ ���� ȫ��<b>URL�ּ�</b>�κ��� Ŭ���Ͽ� ������ �ּ���!<br>����� ����<b>URL</b>�� ���� ������ ���θ� ȸ���� �� �� �ֵ��� <b><font color="#E6B044">���̽���, Ʈ����, īī����, īī�����丮, ī��, ��α� �� ������ ���ϰ� �޴���</font></b>������ ���θ��� ��õ���ּ���.</td>
							</tr>
							<tr>
								<td width="120"><IMG SRC="../images/design/detail_pop_email_img02.gif" WIDTH=95 HEIGHT=95 ALT=""></td>
								<td class="table01_con"><?=$sAddRecom2?><br />(ȸ������ <b>URL�ּ�</b>�� ���������������� Ȯ�� �����Ͻʴϴ�.)</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td height="20"></td></tr>

				<? if(_empty($_ShopInfo->getMemid())){ ?>
				<tr>
					<td><a href="javascript:nologin();"><img src="/images/design/urlhongbo_image.gif" border="0" alt="" /></a></td>
				</tr>

				<? }else{ ?>
				<tr>
					<td>
						<table cellpadding="0" cellspacing="1" width="100%" bgcolor="#ECECEC">
							<tr>
								<td bgcolor="#F3F3F3" class="table01_con" align="center" style="padding:10px 0px;">
									<b><font color="black"><?=$sendName?></font></b> ȸ������ ���� ȫ��URL�ּҴ� <b><span style="background-color:black;"><font color="white"><?=$hongboUrl?></font> </span></b>�Դϴ�.
									<div style="margin-top:5px;"><A HREF="javascript:ClipCopy('<?=$hongboUrl?>')"><IMG SRC="../images/design/detail_pop_email_btn01.gif" WIDTH=86 HEIGHT=27 ALT="" align="absmiddle" /></a></div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td height="30"></td></tr>

				<?
					$smsCount = smsCountValue ();
					if( $smsCount > 0 AND strlen($_ShopInfo->getMemid())>0 AND $_ShopInfo->getMemid()!="deleted" ){
				?>
				<tr>
					<td>
						<script type="text/javascript">
						<!--
							function sms_urlhongbo_send () {
								if(document.form2.send1.value.length==0) {
									alert("SMS �߽��� ��ȣ�� �Է��ϼ���.");
									document.form2.send1.focus();
									return;
								}
								if(document.form2.send2.value.length==0) {
									alert("SMS �߽��� ��ȣ�� �Է��ϼ���.");
									document.form2.send2.focus();
									return;
								}
								if(document.form2.send3.value.length==0) {
									alert("SMS �߽��� ��ȣ�� �Է��ϼ���.");
									document.form2.send3.focus();
									return;
								}
								if(document.form2.cel1.value.length==0) {
									alert("SMS ������ ��ȣ�� �Է��ϼ���.");
									document.form2.cel1.focus();
									return;
								}
								if(document.form2.cel2.value.length==0) {
									alert("SMS ������ ��ȣ�� �Է��ϼ���.");
									document.form2.cel2.focus();
									return;
								}
								if(document.form2.cel3.value.length==0) {
									alert("SMS ������ ��ȣ�� �Է��ϼ���.");
									document.form2.cel3.focus();
									return;
								}
								document.form2.submit();
							}
						//-->
						</script>
						<form name=form2 action="<?=$_SERVER[PHP_SELF]?>" method=post>
						<input type="hidden" name="mode" value="sms_urlhongbo">
						<table cellpadding="0" cellspacing="1" width="100%" bgcolor="#ECECEC">
							<caption style="font-size:15px; font-weight:bold; letter-spacing:-1px; color:#333333; text-align:left; padding:5px 10px;">SMS�� �Ұ��ϱ�</caption>
							<tr>
								<td bgcolor="#F3F3F3" class="table01_con" align="center" style="padding:10px 0px;">
									<b><font color="black">SMS �߽��� ��ȣ</font></b> :
									<input type="text" name="send1" size="5" maxlength="3" class="input">
									-
									<input type="text" name="send2" size="5" maxlength="4" class="input">
									-
									<input type="text" name="send3" size="5" maxlength="4" class="input">
								</td>
							</tr>
							<tr>
								<td bgcolor="#F3F3F3" class="table01_con" align="center" style="padding:10px 0px;">
									<b><font color="black">SMS ������ ��ȣ</font></b> :
									<input type="text" name="cel1" size="5" maxlength="3" class="input">
									-
									<input type="text" name="cel2" size="5" maxlength="4" class="input">
									-
									<input type="text" name="cel3" size="5" maxlength="4" class="input">
								</td>
							</tr>
							<tr>
								<td>
									<div style="margin:5px; text-align:center;"><A HREF="javascript:sms_urlhongbo_send();"><IMG SRC="../images/design/sms_urlhongbo_btn.gif" ALT="SMS �߼�" /></a></div>
								</td>
							</tr>
						</table>
						</form>
					</td>
				</tr>
				<tr><td height="30"></td></tr>
				<? } ?>

				<? if($_data->sns_ok == "Y"){ ?>
				<tr>
					<td style="padding-bottom:20px;">
						<table cellpadding="0" cellspacing="0" width="100%" style="border:1px solid #eeeeee;">
							<caption style="font-size:15px; font-weight:bold; letter-spacing:-1px; color:#333333; text-align:left; padding:5px 10px;">SNS(�Ҽ� ��Ʈ��ũ ����)�� �Ұ��ϱ�</caption>
							<tr>
								<td width="89" height="20"><IMG SRC="../images/design/detail_pop_email_text01.gif" WIDTH=89 HEIGHT=24 ALT=""></td>
								<td style="padding:15px 0px;">
										<a href="javascript:goTwitter();"><img src="../images/design/icon_twitter_on.gif" width="25" height="25" border="0"></a>
									<a href="javascript:goFaceBook();"><img src="../images/design/icon_facebook_on.gif" width="25" height="25" border="0" hspace="3"></a>
									<!--<a href="javascript:goMe2Day();"><img src="../images/design/icon_me2day_on.gif" width="25" height="25" border="0"></a>
									<a href="javascript:goCyworld();"><img src="../images/design/icon_cywold_on.gif" width="25" height="25" border="0" hspace="3"></a>-->
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<? } ?>
				<tr>
					<td>
						<div>
							<table cellpadding="0" cellspacing="0">
								<tr>
									<td width="89" height="35"><IMG SRC="../images/design/detail_pop_email_text02.gif" WIDTH=89 HEIGHT=24 ALT=""></td>
									<td><IMG SRC="../images/design/detail_pop_email_text03.gif" WIDTH=405 HEIGHT=24 ALT=""></td>
								</tr>
							</table>
						</div>
						<table cellpadding="0" width="100%" cellspacing="1" bgcolor="#ECECEC">
							<tr>
								<td bgcolor="#F3F3F3">
								<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
								<input type=hidden name=mode value="">
									<table cellpadding="0" cellspacing="10" border="0" width="100%">
										<tr>
											<td width="80"><IMG SRC="../images/design/detail_pop_email_text04.gif" WIDTH=49 HEIGHT=20 ALT=""></td>
											<td><input type="text" name="in_email" class="input" maxlength="30" size="67" style="width:98%;"></td>
											<td width="80"></td>
										</tr>
										<tr>
											<td width="80" valign="top"><IMG SRC="../images/design/detail_pop_email_text05.gif" WIDTH=49 HEIGHT=20 ALT=""></td>
											<td>
<textarea name="in_message" rows="5" class="textarea_gonggu" style="width:98%; padding:10px;">
<?=$sendName?>�Բ��� <?=$_data->shopname?>(<?=$hongboUrl?>)�� ��õ�ϼ̾��!!
</textarea>
<!--
<?//=$sendName?>�Բ��� ���ϲ� <?//=$_data->shopname?>�� ��õ�ϼ̽��ϴ�.
���� ������� <?//=$_data->shopname?>�� ������ ����������.
<?//=$hongboUrl?>
-->
											</td>
											<td width="80"><A HREF="javascript:CheckForm()"><img src="../images/design/detail_pop_email_btn02.gif" width="80" height="80" border="0"></a></td>
										</tr>
									</table>
								</form>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<?}?>

			</table>
		</td>
	</tr>

	<tr>
		<td style="padding-top:80px;">
			<div class="snshongboinfo">
				<h4>�� ��ǰ�� SNS ȫ�� ������ ����</h4>
				<div class="addpoint">
					��ǰ�� �Ҽȷ� ȫ���ϸ� �������� ��������!<br />
					<?=$sAddRecom2?>
				</div>
			</div>

			<div class="snschannel">
				<h4>ȫ������ SNSä��</h4>
				<p style="padding-bottom:40px;">
					- ���̽���, Ʈ����, īī����(���������), īī�����丮(���������)<br />
					- ���� SNSä�� ���� ȫ��URL�� ���� ��ǰ�� �ǸŵǸ� �����ݾ��� ���� %�� �ǸŽø��� ���������� ������ �帮��, ������ ���ο��Ե� �߰��������� ������ �帳�ϴ�.
				</p>

				<h4>��������</h4>
				<div style="padding:20px 0px;background:#f9f9f9;text-align:center;font-size:0px;"><img src="/images/common/snshongbo_image1.gif" alt="" /></div>
			</div>
		</td>
	</tr>
	<tr>
		<td style="padding-top:80px;">
			<div class="pointfaq">
				<h3>�����ݰ��� �����ϴ� ����</h3>
				<h4>SNSȫ�� �������̶�</h4>
				<p>���� SNSä�η� ��õ�� ��ǰ������ ���� ������ ��ǰ �����ϰų� ���� ȫ�� URL�� ���� ��ǰ�� ������ ��� �����Ǵ� ����Ʈ�Դϴ�.</p>

				<h4>SNS��ư�� �������� ������ ȫ���������� �־����� �ʳ���?</h4>
				<p>
					URL�����ϱ� ����� ���� ��õ�� �����մϴ�.<br />
					URL�� �����Ͻ� �� ����Ͻô� �޽����� ��α�, ī�� �� �湮�ڵ��� ���� �Խ��� ���� ���� ��ǰ�� ��õ�ϼŵ� �������� ������ �帱 �� �ֽ��ϴ�.
				</p>

				<h4>���� ������ ������ ��� Ȯ���ϳ���?</h4>
				<p>
					�α��� �� ��ܸ޴��� '���������� &gt; ������ �޴����� ������ �� ������ Ȯ���Ͻ� �� �ֽ��ϴ�. SNSȫ�������� �� �Ϲ� ��ǰ���Ÿ� ���� ����Ʈ ���� ��Ȳ �� ��ǰ�� �ۼ��� ���� ����Ʈ �������� �� ���� ������ �߻������� Ȯ���Ͻ� �� �ֽ��ϴ�.<br />
					- ��õ URL�� ���� ���� �� �Ǹŵ� �ֹ����� ��ҽ� �� �����Ǿ��� ����Ʈ�� �����˴ϴ�.
				</p>
			</div>
		</td>
	</tr>
	<tr><td height="40"></td></tr>
	<!--
	<tr>
		<td height="9" width="10"><img src="../images/design/pop_view_bottomleft.gif" width="17" height="16" border="0"></td>
		<td background="../images/design/pop_view_bottombg.gif" height="9" width="729"></td>
		<td height="9" width="11"><img src="../images/design/pop_view_bottomright.gif" width="17" height="16" border="0"></td>
	</tr>
	-->
</table>

<? include ($Dir."lib/bottom.php") ?>
</BODY>
</HTML>