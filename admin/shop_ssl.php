<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

########################### TEST ���θ� Ȯ�� ##########################
DemoShopCheck("������������� �׽�Ʈ�� �Ұ��� �մϴ�.", "history.go(-1)");
#######################################################################

####################### ������ ���ٱ��� check ###############
$PageCode = "sh-2";
$MenuCode = "shop";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$ssl_type=$_shopdata->ssl_type;
$ssl_domain=$_shopdata->ssl_domain;
$ssl_port=$_shopdata->ssl_port;
$ssl_page=$_shopdata->ssl_page;

$type=$_POST["type"];
if($type=="up") {
	$ssl_type=$_POST["ssl_type"];
	$ssl_port=$_POST["ssl_port"];
	$ssl_domain=$_POST["ssl_domain"];

	$ssl_page_admin=$_POST["ssl_page_admin"];
	$ssl_page_plogn=$_POST["ssl_page_plogn"];
	$ssl_page_vlogn=$_POST["ssl_page_vlogn"];
	$ssl_page_login=$_POST["ssl_page_login"];
	$ssl_page_mjoin=$_POST["ssl_page_mjoin"];
	$ssl_page_medit=$_POST["ssl_page_medit"];
	$ssl_page_mlost=$_POST["ssl_page_mlost"];
	$ssl_page_adult=$_POST["ssl_page_adult"];

	if($ssl_type=="Y") {
		$ssl_page="";
		if($ssl_page_admin=="Y") $ssl_page.="ADMIN=Y|";
		if($ssl_page_plogn=="Y") $ssl_page.="PLOGN=Y|";
		if($ssl_page_vlogn=="Y") $ssl_page.="VLOGN=Y|";
		if($ssl_page_login=="Y") $ssl_page.="LOGIN=Y|";
		if($ssl_page_mjoin=="Y") $ssl_page.="MJOIN=Y|";
		if($ssl_page_medit=="Y") $ssl_page.="MEDIT=Y|";
		if($ssl_page_mlost=="Y") $ssl_page.="MLOST=Y|";
		if($ssl_page_adult=="Y") $ssl_page.="ADULT=Y|";

		if(strlen($ssl_page)>0) $ssl_page=substr($ssl_page,0,-1);
	} else {
		$ssl_port="";
		$ssl_domain="";
		$ssl_page="";
	}
	$sql = "UPDATE tblshopinfo SET ";
	$sql.= "ssl_type	= '".$ssl_type."', ";
	$sql.= "ssl_domain	= '".$ssl_domain."', ";
	$sql.= "ssl_port	= '".$ssl_port."', ";
	$sql.= "ssl_page	= '".$ssl_page."' ";
	mysql_query($sql,get_db_conn());
	DeleteCache("tblshopinfo.cache");
	$onload="<script>alert('SSL(���ȼ���) ������ �Ϸ�Ǿ����ϴ�.');</script>\n";
}


$temp=explode("|",$ssl_page);
$cnt=count($temp);
for ($i=0;$i<$cnt;$i++) {
	if (substr($temp[$i],0,6)=="ADMIN=")		$ssl_check_admin=substr($temp[$i],6);
	else if (substr($temp[$i],0,6)=="PLOGN=")	$ssl_check_plogn=substr($temp[$i],6);
	else if (substr($temp[$i],0,6)=="VLOGN=")	$ssl_check_vlogn=substr($temp[$i],6);
	else if (substr($temp[$i],0,6)=="LOGIN=")	$ssl_check_login=substr($temp[$i],6);
	else if (substr($temp[$i],0,6)=="MJOIN=")	$ssl_check_mjoin=substr($temp[$i],6);
	else if (substr($temp[$i],0,6)=="MEDIT=")	$ssl_check_medit=substr($temp[$i],6);
	else if (substr($temp[$i],0,6)=="MLOST=")	$ssl_check_mlost=substr($temp[$i],6);
	else if (substr($temp[$i],0,6)=="ADULT=")	$ssl_check_adult=substr($temp[$i],6);
}

?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script>
function CheckForm(){
	if(document.form1.ssl_type[0].checked==true) {
		if(document.form1.ssl_port.value.length==0) {
			alert("SSL ��Ʈ�� �Է��ϼ���.");
			document.form1.ssl_port.focus();
			return;
		} else {
			if(!IsNumeric(document.form1.ssl_port.value)) {
				alert("SSL ��Ʈ�� ���ڸ� �Է��ϼ���.");
				document.form1.ssl_port.focus();
				return;
			}
		}
		if(document.form1.ssl_domain.value.length==0) {
			alert("���ȼ��� �������� ��Ȯ�� �Է��ϼ���.");
			document.form1.ssl_domain.focus();
			return;
		}
	}
	if(confirm("SSL(���ȼ���) ������ �����Ͻðڽ��ϱ�?")) {
		document.form1.type.value="up";
		document.form1.submit();
	}
}

function CheckType(type) {
	if(type=="Y") {
		document.form1.ssl_port.style.background="";
		document.form1.ssl_domain.style.background="";
		document.form1.ssl_port.disabled=false;
		document.form1.ssl_domain.disabled=false;
		for(i=0;i<document.form1.elements.length;i++) {
			temp=document.form1.elements[i];
			if(temp.name.substring(0,9)=="ssl_page_") {
				temp.disabled=false;
			}
		}
	} else {
		document.form1.ssl_port.style.background="#f0f0f0";
		document.form1.ssl_domain.style.background="#f0f0f0";
		document.form1.ssl_port.disabled=true;
		document.form1.ssl_domain.disabled=true;
		for(i=0;i<document.form1.elements.length;i++) {
			temp=document.form1.elements[i];
			if(temp.name.substring(0,9)=="ssl_page_") {
				temp.disabled=true;
			}
		}
	}
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
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �������� &gt; ���θ� ȯ�� ���� &gt; <span class="2depth_select">SSL(���ȼ���) ��� ����</span></td>
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








			<table cellpadding="0" cellspacing="0" width="100%">
			<tr><td height="8"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/shop_ssl_title.gif"  ALT=""></TD>
					</tr>
<tr>
<TD width="100%" background="images/title_bg.gif" height="21"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=20></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/distribute_01.gif"></TD>
					<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
					<TD><IMG SRC="images/distribute_03.gif"></TD>
				</TR>
				<TR>
					<TD background="images/distribute_04.gif"></TD>
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
					<TD width="100%" class="notice_blue">1) SSL(���ȼ���) ��� ������ �߿� ������ ��ȣȭ�� ���� �����ϰ� ���۽�ų �� �ֽ��ϴ�.<br>2) ������������ ó���� ��� ��ȣȭ/��ȣȭ ó���� ���� �Ϲ����Ӻ��� �ӵ��� ���������� ���Ȼ� ���������� ���������� �����մϴ�.</TD>
					<TD background="images/distribute_07.gif"></TD>
				</TR>
				<TR>
					<TD><IMG SRC="images/distribute_08.gif"></TD>
					<TD COLSPAN=2 background="images/distribute_09.gif"></TD>
					<TD><IMG SRC="images/distribute_10.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height=10></td>
			</tr>
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=type>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<col width=140></col>
				<col width=></col>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0"><B>SSL ���� ����</B></TD>
					<TD class="td_con1">
					<input type=radio name=ssl_type value="Y" onclick="CheckType('Y')" <?=($ssl_type=="Y"?"checked":"")?>>������(SSL ��Ʈ : <input type=text name=ssl_port value="<?=$ssl_port?>" size=5 maxlength=5 class="input_selected" onkeyup="strnumkeyup(this)">)
					<img width=20 height=0>
					<input type=radio name=ssl_type value="N" onclick="CheckType('N')" <?=($ssl_type!="Y"?"checked":"")?>>�������
					<br>
					<span class=font_orange>�� SSL ������ �������� �����ο� SSL ���� ������ �ؾ߸� ������ �����մϴ�.</span>
					<br>
					<span class=font_orange>�� SSL ������ ���� �۵� ���� ������ "������"���� �����ϴ��� �۵��� ���� �ʽ��ϴ�.</span>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0"><B>SSL ���� ������</B></TD>
					<TD class="td_con1">
					https://<input type=text name=ssl_domain value="<?=$ssl_domain?>" size=25 class="input_selected">/<?=RootPath.SecureDir?>ó��������
					<br>
					<span class=font_orange>�� SSL ���� �������� ���Ⱦ�ü���� SSL ����Ű �߱޽� �Է��� �������� �Է��� �ּ���.</span>
					<br>
					<span class=font_orange>�� SSL ����Ű �߱޽� �Է��� �����ο� www. �Է� ���θ� ��Ȯ�� Ȯ�� �� �� �Է��� �ּ���.</span>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0"><B>SSL ���� ������</B></TD>
					<TD class="td_con1">
					<input type=checkbox name=ssl_page_admin value="Y" <?=($ssl_check_admin=="Y"?"checked":"")?>> ������ �α��� �������� ��������(SSL) �����Ͽ� ����
					<br>
					<input type=checkbox name=ssl_page_plogn value="Y" <?=($ssl_check_plogn=="Y"?"checked":"")?>> ��Ʈ�ʻ� �������� �α��� �������� SSL �����Ͽ� ����
					<br>
					<input type=checkbox name=ssl_page_vlogn value="Y" <?=($ssl_check_vlogn=="Y"?"checked":"")?>> ������ �̴ϼ����� �α��� �������� SSL �����Ͽ� ����
					<br>
					<input type=checkbox name=ssl_page_adult value="Y" <?=($ssl_check_adult=="Y"?"checked":"")?>> �������� �������� SSL �����Ͽ� ����
					<br>
					<input type=checkbox name=ssl_page_login value="Y" <?=($ssl_check_login=="Y"?"checked":"")?>> ȸ�� �α��� �������� SSL �����Ͽ� ����
					<br>
					<input type=checkbox name=ssl_page_mjoin value="Y" <?=($ssl_check_mjoin=="Y"?"checked":"")?>> ȸ������ �������� SSL�����Ͽ� ����
					<br>
					<input type=checkbox name=ssl_page_medit value="Y" <?=($ssl_check_medit=="Y"?"checked":"")?>> ȸ���������� �������� SSL�����Ͽ� ����
					<br>
					<input type=checkbox name=ssl_page_mlost value="Y" <?=($ssl_check_mlost=="Y"?"checked":"")?>> ID/��й�ȣ ã�� �������� SSL�����Ͽ� ����
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=10></td></tr>
			<tr>
				<td align="center"><a href="javascript:CheckForm();"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a></td>
			</tr>
			</form>
			<tr><td height=20></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/manual_top1.gif" WIDTH=15 HEIGHT=45 ALT=""></TD>
					<TD><IMG SRC="images/manual_title.gif" WIDTH=113 HEIGHT=45 ALT=""></TD>
					<TD width="100%" background="images/manual_bg.gif"></TD>
					<TD background="images/manual_bg.gif"></TD>
					<TD><IMG SRC="images/manual_top2.gif" WIDTH=18 HEIGHT=45 ALT=""></TD>
				</TR>
				<TR>
					<TD background="images/manual_left1.gif"></TD>
					<TD COLSPAN=3 width="100%" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;" class="menual_bg">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><span class="font_dotline">SSL(���ȼ���) ��� ���� ���</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">
						- ���Ⱦ�ü���� SSL Ű �߱� (���� ����� ���θ� ���������� �߱�)<br>
						- ȣ���� ���� �����ڿ��� ���θ� �����ο� SSL ���� ���� ��û<br>
						- ���� ���� �Ϸ� �� ���� ���� ȯ�漳���� ����<br>
						</td>
					</tr>
					</table>
					</TD>
					<TD background="images/manual_right1.gif"></TD>
				</TR>
				<TR>
					<TD><IMG SRC="images/manual_left2.gif" WIDTH=15 HEIGHT=8 ALT=""></TD>
					<TD COLSPAN=3 background="images/manual_down.gif"></TD>
					<TD><IMG SRC="images/manual_right2.gif" WIDTH=18 HEIGHT=8 ALT=""></TD>
				</TR>
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

<script>CheckType('<?=$ssl_type?>');</script>

<?=$onload?>

<? INCLUDE "copyright.php"; ?>