<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "sh-1";
$MenuCode = "shop";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$type=$_POST["type"];
$up_shoptitle=$_POST["up_shoptitle"];
$up_shopkeyword=$_POST["up_shopkeyword"];
$up_shopdescription=$_POST["up_shopdescription"];

if ($type=="up") {
	$sql = "UPDATE tblshopinfo SET ";
	$sql.= "shoptitle		= '".$up_shoptitle."', ";
	$sql.= "shopkeyword		= '".$up_shopkeyword."', ";
	$sql.= "shopdescription	= '".$up_shopdescription."' ";
	$result = mysql_query($sql,get_db_conn());
	DeleteCache("tblshopinfo.cache");
	$onload = "<script> alert('���� ������ �Ϸ�Ǿ����ϴ�.'); </script>";
}

$sql = "SELECT shoptitle, shopkeyword, shopdescription ";
$sql.= "FROM tblshopinfo ";
$result = mysql_query($sql,get_db_conn());
if ($row=mysql_fetch_object($result)) {
	$shoptitle = $row->shoptitle;
	$shopkeyword = $row->shopkeyword;
	$shopdescription = $row->shopdescription;
}
mysql_free_result($result);
?>

<? INCLUDE ("header.php"); ?>

<script type="text/javascript" src="lib.js.php"></script>
<script>
function CheckForm(){
	var form = document.form1;
	if(CheckLength(form.up_shoptitle)>100){
		alert("Ÿ��Ʋ���� �ѱ�50��, ����100�� ���� �Է� �����մϴ�.\n\n�ٽ� Ȯ���Ͻñ� �ٶ��ϴ�.");
		form.up_shoptitle.focus();
		return;
	}
	if(CheckLength(form.up_shopkeyword)>100){
		alert("Ű����� �ѱ�50��, ����100�� ���� �Է� �����մϴ�.\n\n�ٽ� Ȯ���Ͻñ� �ٶ��ϴ�.");
		form.up_shopkeyword.focus();
		return;
	}
	if(CheckLength(form.up_shopdescription)>100){
		alert("������ �ѱ�50��, ����100�� ���� �Է� �����մϴ�.\n\n�ٽ� Ȯ���Ͻñ� �ٶ��ϴ�.");
		form.up_shopdescription.focus();
		return;
	}
	form.type.value="up";
	form.submit();
}
</script>

<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
<tr>
	<td valign="top">
	<table cellpadding="0" cellspacing="0" width=100% style="table-layout:fixed">
	<tr>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed"  background="images/con_bg.gif">
		<col width=198></col>
		<col width=10></col>
		<col width=></col>
		<tr>
			<td valign="top"  background="images/leftmenu_bg.gif">
			<? include ("menu_shop.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �������� &gt; ���� �⺻���� ���� &gt; <span class="2depth_select">������ Ÿ��Ʋ/Ű����</span></td>
			</tr>
			</table>
		</td>
	</tr>   
	<tr>
        <td width="16"><img src="images/con_t_01.gif" width="16" height="16" border="0"></td>
        <td background="images/con_t_01_bg.gif"></td>
        <td width="16"><img src="images/con_t_02.gif" width="16" height="16" border="0"></td>
    </tr>
    <tr>
        <td width="16" background="images/con_t_04_bg1.gif"></td>
        <td bgcolor="#ffffff" style="padding:10px">





			<table width="100%" cellpadding="0" cellspacing="0">
			<tr><td height="8"></td></tr>
			<tr>
				<td>
				<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td><img src="images/shop_keyword_title.gif" border="0"></td>
				</tr>
				<tr>
					<td width="100%" background="images/title_bg.gif" height="21"></td>
				</tr>
				</TABLE>
				</td>
			</tr>
			<tr><td height="20"></td></tr>
			<tr>
				<td>
				<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td><img src="images/shop_keyword_stitle1.gif" border="0"></td>
					<td width="100%" background="images/shop_basicinfo_stitle_bg.gif"></td>
					<td><img src="images/shop_basicinfo_stitle_end.gif" border="0"></td>
				</tr>
				</TABLE>
				</td>
			</tr>
			<tr><td height="3"></td></tr>
			<tr>
				<td>
				<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td><img src="images/distribute_01.gif" border="0"></td>
					<td COLSPAN="2" background="images/distribute_02.gif"></td>
					<td><img src="images/distribute_03.gif" border="0"></td>
				</tr>
				<tr>
					<td background="images/distribute_04.gif"></td>
					<td class="notice_blue"><img src="images/distribute_img.gif" border="0"></td>
					<td width="100%" class="notice_blue">���θ� ��ܿ� ǥ�õǴ� Ÿ��Ʋ�� �����Դϴ�.</b></td>
					<td background="images/distribute_07.gif"></td>
				</tr>
				<tr>
					<td><img src="images/distribute_08.gif" border="0"></td>
					<td COLSPAN="2" background="images/distribute_09.gif"></td>
					<td><img src="images/distribute_10.gif" border="0"></td>
				</tr>
				</TABLE>
				</td>
			</tr>
			<tr><td height="3"></td></tr>
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=type>
			<tr>
				<td>
				<table width="100%" cellpadding="0" cellspacing="0">
				<col width="140"></col>
				<col></col>
				<tr>
					<td height="2" colspan="2" bgcolor="#808080"></td>
				</tr>
				<tr>
					<td class="table_cell"><img src="images/icon_point2.gif" border="0">Ÿ��Ʋ�� �Է�</td>
					<td class="td_con1"><input name="up_shoptitle" value="<?=$shoptitle?>" size="80" maxlength="100" onKeyDown="chkFieldMaxLen(100)" class="input_selected"><br><span class="font_gray7">* �������� ������ ���� ��� Ÿ��Ʋ�ٿ� ǥ���� ������ �Է��մϴ�.<br>* �� ������ ���ã�� ��Ͻ��� Ÿ��Ʋ�� �˴ϴ�.<br>* �ִ� <b><span class="notice_orange1">100��(�ѱ�50��)</span></b> ����(��������) </span>.&nbsp;<br><img src="images/shop_keyword_img1.gif" border="0"></td>
				</tr>
				<tr>
					<td height="1" colspan="2" bgcolor="#E3E3E3"></td>
				</tr>
				</TABLE>
				</td>
			</tr>
			<tr><td height="50"></td></tr>
			<tr>
				<td>
				<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td><img src="images/shop_keyword_stitle2.gif" border="0"></td>
					<td width="100%" background="images/shop_basicinfo_stitle_bg.gif"></td>
					<td><img src="images/shop_basicinfo_stitle_end.gif" border="0"></td>
				</tr>
				</TABLE>
				</td>
			</tr>
			<tr><td height="3"></td></tr>
			<tr>
				<td>
				<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td><img src="images/distribute_01.gif" border="0"></td>
					<td COLSPAN="2" background="images/distribute_02.gif"></td>
					<td><img src="images/distribute_03.gif" border="0"></td>
				</tr>
				<tr>
					<td background="images/distribute_04.gif"></td>
					<td class="notice_blue"><img src="images/distribute_img.gif" border="0"></td>
					<td width="100%" class="notice_blue">1) �˻��� ��Ÿ�ױ׸� �����մϴ�.<br>2) <b>�˻����� ����Ʈ�� ���å(������å)</b>�� ���� <b>���θ��� Ű���峪 ����</b>�� <b>��ϵ��� ���� �� �ֽ��ϴ�.</b></td>
					<td background="images/distribute_07.gif"></td>
				</tr>
				<tr>
					<td><img src="images/distribute_08.gif" border="0"></td>
					<td COLSPAN="2" background="images/distribute_09.gif"></td>
					<td><img src="images/distribute_10.gif" border="0"></td>
				</tr>
				</TABLE>
				</td>
			</tr>
			<tr><td height="3"></td></tr>
			<tr>
				<td>
				<table width="100%" cellpadding="0" cellspacing="0">
				<col width="140"></col>
				<col></col>
				<tr>
					<td height="2" colspan="2" bgcolor="#808080"></td>
				</tr>
				<tr>
					<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">Ű���� �Է�</td>
					<td class="td_con1"><input type=text name="up_shopkeyword" value="<?=$shopkeyword?>" size="80" maxlength="100" onKeyDown="chkFieldMaxLen(100)" class="input"><br><span class="font_gray7">* ���� �˻����� ����Ʈ���� �����ϴ� Keyword ��Ÿ�±׿� �� �˻�� �Է��ϼ���.<br>* ���θ��� ���� ������ �˻�� �޸�(,)�� �����ڷ� �Է��ϼ���.</span></td>
				</tr>
				<tr>
					<td height="1" colspan="2" bgcolor="#E3E3E3"></td>
				</tr>
				<tr>
					<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">���� �Է�</td>
					<td class="td_con1"><input type=text name="up_shopdescription" value="<?=$shopdescription?>" size="80" maxlength="100" onKeyDown="chkFieldMaxLen(100)" class="input"><br><span class="font_gray7">* Description ��Ÿ�±׿� �� ������ �Է��ϼ���.<br>* ���� �˻����� ����Ʈ���� ������ ������ ���� ���˴ϴ�. ���θ� ������ ������ �Է��ϼ���.</span></td>
				</tr>
				<tr>
					<td height="1" colspan="2" bgcolor="#E3E3E3"></td>
				</tr>
				</TABLE>
				</td>
			</tr>
			<tr><td height="10"></td></tr>
			<tr>
				<td align="center"><a href="javascript:CheckForm();"><img src="images/botteon_save.gif" border="0"></a></td>
			</tr>
			</form>
			<tr><td height="20"></td></tr>
			<tr>
				<td>
				<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td><img src="images/manual_top1.gif" border="0"></td>
					<td width="100%" background="images/manual_bg.gif"><img src="images/manual_title.gif" border="0"></td>
					<td><img src="images/manual_top2.gif" border="0"></td>
				</tr>
				<tr>
					<td background="images/manual_left1.gif"></td>
					<td style="padding-top:5px;" class="menual_bg">
					<table width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td class="menual_con"><img src="images/icon_8.gif" border="0" align="absmiddle"><span class="font_dotline">Ÿ��Ʋ�� ��Ÿ�±� ��� ��</span></td>
					</tr>
					<tr>
						<td style="padding-left:13px;"  class="menual_con">&lt;HEAD&gt;<br>&lt;TITLE&gt;�������� Ÿ��Ʋ��&lt;/TITLE&gt;<br>&lt;meta http-equiv=&quot;CONTENT-TYPE&quot; content=&quot;text/html; charset=EUC-KR&quot;&gt;<br>&lt;meta name=&quot;description&quot; content=��meta Description(���θ� ������) ��µǴ� ��&quot;&gt;<br>&lt;meta name=&quot;keywords&quot; content=��meta Keyword(�˻� Ű����) ��µǴ� ��&quot;&gt;<br>&lt;/HEAD&gt;</td>
					</tr>
					<tr><td height="20"></td></tr>
					<tr>
						<td  class="menual_con"><img src="images/icon_8.gif" border="0" align="absmiddle"><span class="font_dotline">�������� �ҽ� ���� ���</span></td>
					</tr>
					<tr>
						<td  class="menual_con_orange" style="padding-left:13px;" ><b>�� ������ Ÿ��Ʋ �޴� &gt; ���� &gt; �ҽ�<br>�� �������� ���� &gt; ����κп� ������ ���콺 &gt;�ҽ�����(��ܸ޴� ����Ÿ���� �������� ����� ���)</b></td>
					</tr>
					</table>
					</td>
					<td background="images/manual_right1.gif"></td>
				</tr>
				<tr>
					<td><img src="images/manual_left2.gif" border="0"></td>
					<td background="images/manual_down.gif"></td>
					<td><img src="images/manual_right2.gif" border="0"></td>
				</tr>
				</TABLE>
				</td>
			</tr>
			<tr><td height="50"></td></tr>
			</table>


</td>
        <td width="16" background="images/con_t_02_bg.gif"></td>
    </tr>
    <tr>
        <td width="16"><img src="images/con_t_04.gif" width="16" height="16" border="0"></td>
        <td background="images/con_t_04_bg.gif"></td>
        <td width="16"><img src="images/con_t_03.gif" width="16" height="16" border="0"></td>
    </tr>
    <tr><td height="20"></td></tr>
</table>


			</td>
		</tr>
		</table>
		</td>
	</tr>
	</table>
	</td>
</tr>
</table>
<?= $onload ?>
<? INCLUDE ("copyright.php"); ?>