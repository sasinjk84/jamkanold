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
$up_companyname=$_POST["up_companyname"];
$up_companynum=$_POST["up_companynum"];
$up_companyowner=$_POST["up_companyowner"];
$up_companypost1=$_POST["up_companypost1"];
$up_companypost2=$_POST["up_companypost2"];
$up_companyaddr=$_POST["up_companyaddr"];
$up_companybiz=$_POST["up_companybiz"];
$up_companyitem=$_POST["up_companyitem"];
$up_reportnum=$_POST["up_reportnum"];

$up_shopname=$_POST["up_shopname"];
$up_info_email=$_POST["up_info_email"];
$up_info_tel=$_POST["up_info_tel"];
$up_info_addr=$_POST["up_info_addr"];
$up_privercyname=$_POST["up_privercyname"];
$up_privercyemail=$_POST["up_privercyemail"];

$up_companypost = $up_companypost1.$up_companypost2;

if ($type == "up") {
	########################### TEST ���θ� Ȯ�� ##########################
	DemoShopCheck("������������� �׽�Ʈ�� �Ұ��� �մϴ�.", $_SERVER[PHP_SELF]);
	#######################################################################

	$sql = "UPDATE tblshopinfo SET ";
	$sql.= "shopname		= '".$up_shopname."', ";
	$sql.= "companyname		= '".$up_companyname."', ";
	$sql.= "companynum		= '".$up_companynum."', ";
	$sql.= "companypost		= '".$up_companypost."', ";
	$sql.= "companyaddr		= '".$up_companyaddr."', ";
	$sql.= "companybiz		= '".$up_companybiz."', ";
	$sql.= "companyitem		= '".$up_companyitem."', ";
	$sql.= "companyowner	= '".$up_companyowner."', ";
	$sql.= "reportnum		= '".$up_reportnum."', ";
	$sql.= "privercyname	= '".$up_privercyname."', ";
	$sql.= "privercyemail	= '".$up_privercyemail."', ";
	$sql.= "info_email		= '".$up_info_email."', ";
	$sql.= "info_tel		= '".$up_info_tel."', ";
	$sql.= "info_addr		= '".$up_info_addr."' ";
	$result = mysql_query($sql,get_db_conn());

	DeleteCache("tblshopinfo.cache");
	$onload = "<script> alert('���� ������ �Ϸ�Ǿ����ϴ�.'); </script>";
}

$sql = "SELECT * FROM tblshopinfo ";
$result = mysql_query($sql,get_db_conn());
if ($row=mysql_fetch_object($result)) {
	$shopname = $row->shopname;
	$companyname = $row->companyname;
	$companynum = $row->companynum;
	$companyowner = $row->companyowner;
	$companypost = $row->companypost;
	$companyaddr = $row->companyaddr;
	$companybiz = $row->companybiz;
	$companyitem = $row->companyitem;
	$reportnum = $row->reportnum;
	$info_email = $row->info_email;
	$info_tel  = $row->info_tel;
	$info_addr = $row->info_addr;
	$privercyname = $row->privercyname;
	$privercyemail = $row->privercyemail;
}
mysql_free_result($result);

?>

<? INCLUDE ("header.php"); ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">

function f_addr_search(form,post,addr,gbn) {
	window.open("<?=$Dir.FrontDir?>addr_search.php?form="+form+"&post="+post+"&addr="+addr+"&gbn="+gbn,"f_post","resizable=yes,scrollbars=yes,x=100,y=200,width=370,height=250");		
}

function CheckForm() {
	var form = document.form1;
	if (!form.up_companyname.value) {
		form.up_companyname.focus();
		alert("��ȣ(ȸ���)�� �Է��ϼ���.");
		return;
	}
	if(CheckLength(form.up_companyname)>30) {
		form.company_name.focus();
		alert("��ȣ(ȸ���)�� �ѱ�15�� ����30�� ���� �Է� �����մϴ�");
		return;
	}
	if (!form.up_companynum.value) {
		form.up_companynum.focus();
		alert("����ڵ�Ϲ�ȣ�� �Է��ϼ���.");
		return;
	}

	var bizno;
	var bb;
	bizno = form.up_companynum.value;
	bizno = bizno.replace("-","");
	bb = chkBizNo(bizno);
	if (!bb) {
		alert("�������� ���� ����ڵ�Ϲ�ȣ �Դϴ�.\n����ڵ�Ϲ�ȣ�� �ٽ� �Է��ϼ���.");
		form.up_companynum.value = "";
		form.up_companynum.focus();
		return;
	}

	if (!form.up_companyowner.value) {
		form.up_companyowner.focus();
		alert("��ǥ�� ������ �Է��ϼ���.");
		return;
	}
	if(CheckLength(form.up_companyowner)>12) {
		form.up_companyowner.focus();
		alert("��ǥ�� ������ �ѱ� 6���ڱ��� �����մϴ�");
		return;
	}
	if (!form.up_companypost1.value) {
		form.up_companypost1.focus();
		alert("�����ȣ�� �Է��ϼ���.");
		return;
	}
	if (!form.up_companyaddr.value) {
		form.up_companyaddr.focus();
		alert("����� �ּҸ� �Է��ϼ���.");
		return;
	}
	if(CheckLength(form.up_companybiz)>30) {
		form.up_companybiz.focus();
		alert("����� ���´� �ѱ� 15�ڱ��� �Է� �����մϴ�");
		return;
	}
	if(CheckLength(form.up_companyitem)>30) {
		form.up_companyitem.focus();
		alert("����� ������ �ѱ� 15�ڱ��� �Է� �����մϴ�");
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
		<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed" background="images/con_bg.gif">
		<col width=198></col>
		<col width=10></col>
		<col width=></col>
		<tr>
			<td  valign="top" background="images/leftmenu_bg.gif">
			<? include ("menu_shop.php"); ?>
			</td>

			<td></td>
			<td valign="top">





<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �������� &gt; ���� �⺻���� ���� &gt; <span class="2depth_select">���� �⺻���� ����</span></td>
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
					<td><img src="images/shop_basicinfo.gif" border="0"></td>
				</tr>
				<tr>
					<td width="100%" background="images/title_bg.gif" height="21"></td>
				</tr>
				</table>
				</td>
			</tr>
			<tr><td height="20"></td></tr>
			<tr>
				<td>
				<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td><img src="images/shop_basicinfo_stitle1.gif" border="0"></td>
					<td width="100%" background="images/shop_basicinfo_stitle_bg.gif"></td>
					<td><img src="images/shop_basicinfo_stitle_end.gif" border="0"></td>
				</tr>
				</table>
				</td>
			</tr>
			<tr><td height="3"></td></tr>
			<tr>
				<td>
				<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td><img src="images/distribute_01.gif"></td>
					<td COLSPAN="2" background="images/distribute_02.gif"></td>
					<td><img src="images/distribute_03.gif"></td>
				</tr>
				<tr>
					<td background="images/distribute_04.gif"></td>
					<td class="notice_blue"><img src="images/distribute_img.gif" border="0"></td>
					<td width="100%" class="notice_blue">���θ� <b>ȸ��Ұ�/�ϴ�/�̿�ȳ�/������ȣ</b> ��� ��µ����� ��Ȯ�� �Է��ؾ� �մϴ�.</span></b></td>
					<td background="images/distribute_07.gif"></td>
				</tr>
				<tr>
					<td><img src="images/distribute_08.gif"></td>
					<td COLSPAN="2" background="images/distribute_09.gif"></td>
					<td><img src="images/distribute_10.gif"></td>
				</tr>
				</table>
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
					<td class="table_cell"><img src="images/icon_point2.gif" border="0">��ȣ (ȸ���)</td>
					<td class="td_con1"><input type="text" name="up_companyname" value="<?=$companyname?>" size="60" maxlength="30" onKeyDown="chkFieldMaxLen(30)" class="input"></td>
				</tr>
				<tr>
					<td height="1" colspan="2" bgcolor="#EDEDED"></td>
				</tr>
				<tr>
					<td class="table_cell"><img src="images/icon_point2.gif" border="0">����ڵ�Ϲ�ȣ</td>
					<td class="td_con1"><input type="text" name="up_companynum" value="<?=$companynum?>" size="20" maxlength="20" class="input"></td>
				</tr>
				<tr>
					<td height="1" colspan="2" bgcolor="#EDEDED"></td>
				</tr>
				<tr>
					<td class="table_cell"><img src="images/icon_point2.gif" border="0">��ǥ�� ����</td>
					<td class="td_con1"><input type="text" name="up_companyowner" value="<?=$companyowner?>" size="20" maxlength="12" onKeyDown="chkFieldMaxLen(12)" class="input"></td>
				</tr>
				<tr>
					<td height="1" colspan="2" bgcolor="#EDEDED"></td>
				</tr>
				<tr>
					<td class="table_cell"><img src="images/icon_point2.gif" border="0">����� �ּ�</td>
					<td colspan="3" bgcolor="#FFFFFF" class="td_con1">
					<table width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td width="80" nowrap><input type=text name="up_companypost1" id="up_companypost1" value="<?=$companypost?>" size="5" maxlength="5" class="input" style="width:50px;"></td>
						<td width="100%"><A href="javascript:addr_search_for_daumapi('up_companypost1','up_companyaddr','');" onfocus="this.blur();" style="selector-dummy: true" class="board_list hideFocus"><img src="images/icon_addr.gif" border="0"></A></td>
					</tr>
					<tr>
						<td colspan="2"><input type=text name="up_companyaddr" id="up_companyaddr" value="<?=$companyaddr?>" size="60" maxlength="150" onKeyDown="chkFieldMaxLen(150)" class="input"></td>
					</tr>
					</table>
					</td>
				</tr>
				<tr>
					<td height="1" colspan="2" bgcolor="#EDEDED"></td>
				</tr>
				<tr>
					<td class="table_cell"><img src="images/icon_point2.gif" border="0">����� ����</td>
					<td class="td_con1"><input type="text" name="up_companybiz" value="<?=$companybiz?>" size="60" maxlength="30" onKeyDown="chkFieldMaxLen(30)" class="input"></td>
				</tr>
				<tr>
					<td height="1" colspan="2" bgcolor="#EDEDED"></td>
				</tr>
				<tr>
					<td class="table_cell"><img src="images/icon_point2.gif" border="0">����� ����</td>
					<td class="td_con1"><input type=text name="up_companyitem" value="<?=$companyitem?>" size="60" maxlength="30" onKeyDown="chkFieldMaxLen(30)" class="input"></td>
				</tr>
				<tr>
					<td height="1" colspan="2" bgcolor="#EDEDED"></td>
				</tr>
				<tr>
					<td class="table_cell"><img src="images/icon_point2.gif" border="0">����ǸŽŰ��ȣ</td>
					<td class="td_con1"><input type=text name="up_reportnum" value="<?=$reportnum?>" size="20" maxlength="20" class="input"></td>
				</tr>
				<tr>
					<td height="1" colspan="2" bgcolor="#B9B9B9"></td>
				</tr>
				</table>
				</td>
			</tr>
			<tr><td height="50"></td></tr>
			<tr>
				<td>
				<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td><img src="images/shop_basicinfo_stitle2.gif" border="0"></td>
					<td width="100%" background="images/shop_basicinfo_stitle_bg.gif"></td>
					<td><img src="images/shop_basicinfo_stitle_end.gif" border="0"></td>
				</tr>
				</table>
				</td>
			</tr>
			<tr><td height="3"></td></tr>
			<tr>
				<td>
				<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td><img src="images/distribute_01.gif"></td>
					<td COLSPAN="2" background="images/distribute_02.gif"></td>
					<td><img src="images/distribute_03.gif"></td>
				</tr>
				<tr>
					<td background="images/distribute_04.gif"></td>
					<td class="notice_blue"><img src="images/distribute_img.gif" ></td>
					<td width="100%" class="notice_blue">���θ� ������ �������� ���� ������ ǥ��˴ϴ�. <b>��Ȯ�� �Է��� �ּ���!</b></td>
					<td background="images/distribute_07.gif"></td>
				</tr>
				<tr>
					<td><img src="images/distribute_08.gif"></td>
					<td COLSPAN="2" background="images/distribute_09.gif"></td>
					<td><img src="images/distribute_10.gif"></td>
				</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td height="3"></td>
			</tr>
			<tr>
				<td>
				<table width="100%" cellpadding="0" cellspacing="0">
				<col width="140"></col>
				<col></col>
				<tr>
					<td height="2" colspan="2" bgcolor="#808080"></td>
				</tr>
				<tr>
					<td class="table_cell"><img src="images/icon_point2.gif" border="0">������</td>
					<td class="td_con1"><input type=text name="up_shopname" value="<?=$shopname?>" size="60" maxlength="50" onKeyDown="chkFieldMaxLen(50)" class="input"></td>
				</tr>
				<tr>
					<td height="1" colspan="2" bgcolor="#EDEDED"></td>
				</tr>
				<tr>
					<td class="table_cell"><img src="images/icon_point2.gif" border="0">���θ� ��� �̸���</td>
					<td class="td_con1"><input type=text name="up_info_email" value="<?=$info_email?>" size="60" maxlength="50" onKeyDown="chkFieldMaxLen(50)" class="input"></td>
				</tr>
				<tr>
					<td height="1" colspan="2" bgcolor="#EDEDED"></td>
				</tr>
				<tr>
					<td class="table_cell"><img src="images/icon_point2.gif" border="0">����� ��ȭ��ȣ</td>
					<td class="td_con1"><input type=text name="up_info_tel" value="<?=$info_tel?>" size="60" maxlength="100" onKeyDown="chkFieldMaxLen(100)" class="input"> <span class="font_blue">* ������ �Է½� �޸�(,)�� �Է��ϼ���.</span></td>
				</tr>
				<tr>
					<td height="1" colspan="2" bgcolor="#EDEDED"></td>
				</tr>
				<tr>
					<td class="table_cell"><img src="images/icon_point2.gif" border="0">�ּ� �� �ȳ�</td>
					<td class="td_con1"><input type=text name="up_info_addr" value="<?=$info_addr?>" size="60" maxlength="150" onKeyDown="chkFieldMaxLen(150)" class="input"></td>
				</tr>
				<tr>
					<td height="1" colspan="2" bgcolor="#EDEDED"></td>
				</tr>
				<tr>
					<td class="table_cell"><img src="images/icon_point2.gif" border="0">�������� ����� �̸�</td>
					<td class="td_con1"><input type="text" name="up_privercyname" value="<?=$privercyname?>" size="20" maxlength="10" onKeyDown="chkFieldMaxLen(10)" class="input"></td>
				</tr>
				<tr>
					<td height="1" colspan="2" bgcolor="#EDEDED"></td>
				</tr>
				<tr>
					<td class="table_cell"><img src="images/icon_point2.gif" border="0">�������� ����� �̸���</td>
					<td class="td_con1"><input type="text" name="up_privercyemail" value="<?=$privercyemail?>" size="60" maxlength="50" onKeyDown="chkFieldMaxLen(50)" class="input"></td>
				</tr>
				<tr>
					<td height="1" colspan="2" bgcolor="#B9B9B9"></td>
				</tr>
				</table>
				</td>
			</tr>
			<tr><td height="10"></td></tr>
			<tr>
				<td align="center"><a href="javascript:CheckForm();"><img src="images/botteon_save.gif" border="0"></a></td>
			</tr>
			<tr><td height="20"></td></tr>
			</form>
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
						<td class="menual_con"><img src="images/icon_8.gif" border="0" align="absmiddle"><span class="font_dotline">�ϴ� ǥ�� ����</span></td>
					</tr>
					<tr>
						<td style="padding-left:13px;" class="menual_con">
						��ȣ��:ABC COMPANY &nbsp;��ǥ:000 &nbsp;����ڵ�Ϲ�ȣ:000-00-00000 &nbsp;����ǸŹ�ȣ:0000ȣ<br>
						����������:000-000 &nbsp;00�� 00�� 00�� 000-0���� 00���� 000ȣ &nbsp;������:00-000-000, 00-000-000<br>E-MAIL:0000@000.000 &nbsp;[��������å����:000] &nbsp;[���] &nbsp;[����������ȣ��å]<br>Copiright �� ABC COMPANY All Rights Reserved.
						</td>
					</tr>
					<tr><td height="20"></td></tr>
					<tr>
						<td class="menual_con"><img src="images/icon_8.gif" border="0" align="absmiddle"><span class="font_dotline">�ϴ� ������ ����</span></td>
					</tr>
					<tr>
						<td style="padding-left:13px;" class="menual_con">
						<span class="font_blue"><a href="javascript:parent.topframe.GoMenu(2,'design_bottom.php');">�����ΰ��� > ���ø� - ���� �� ī�װ� > ���θ� �ϴ� ���ø�</a></span> ���� �̸� ������ ��ġ�� Ÿ���� ������ �� �ֽ��ϴ�.<br>
						<span class="font_blue"><a href="javascript:parent.topframe.GoMenu(2,'design_eachbottom.php');">�����ΰ��� > ���������� - ���� �� ���ϴ� > �ϴ�ȭ�� �ٹ̱�</a></span> ���� ���� �������� �� �� �ֽ��ϴ�.
						</td>
					</tr>
					<tr><td height="20"></td></tr>
					</table>
					</td>
					<td background="images/manual_right1.gif"></td>
				</tr>
				<tr>
					<td><img src="images/manual_left2.gif" border="0"></td>
					<td background="images/manual_down.gif"></td>
					<td><img src="images/manual_right2.gif" border="0"></td>
				</tr>
				</table>
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

<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script type="text/javascript">
function addr_search_for_daumapi(post,addr1,addr2) {
	new daum.Postcode({
		oncomplete:function(data) {
			// �˾����� �˻���� �׸��� Ŭ�������� ������ �ڵ带 �ۼ��ϴ� �κ�.

			// �� �ּ��� ���� ��Ģ�� ���� �ּҸ� �����Ѵ�.
			// �������� ������ ���� ���� ��쿣 ����('')���� �����Ƿ�, �̸� �����Ͽ� �б� �Ѵ�.
			var fullAddr = ''; // ���� �ּ� ����
			var extraAddr = ''; // ������ �ּ� ����

			// ����ڰ� ������ �ּ� Ÿ�Կ� ���� �ش� �ּ� ���� �����´�.
			if (data.userSelectedType === 'R') { // ����ڰ� ���θ� �ּҸ� �������� ���
				fullAddr = data.roadAddress;

			} else { // ����ڰ� ���� �ּҸ� �������� ���(J)
				fullAddr = data.jibunAddress;
			}

			// ����ڰ� ������ �ּҰ� ���θ� Ÿ���϶� �����Ѵ�.
			if(data.userSelectedType === 'R'){
				//���������� ���� ��� �߰��Ѵ�.
				if(data.bname !== ''){
					extraAddr += data.bname;
				}
				// �ǹ����� ���� ��� �߰��Ѵ�.
				if(data.buildingName !== ''){
					extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
				}
				// �������ּ��� ������ ���� ���ʿ� ��ȣ�� �߰��Ͽ� ���� �ּҸ� �����.
				fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
			}

			// �����ȣ�� �ּ� ������ �ش� �ʵ忡 �ִ´�.
			document.getElementById(post).value = data.zonecode; //5�ڸ� �������ȣ ���
			document.getElementById(addr1).value = fullAddr;

			// Ŀ���� ���ּ� �ʵ�� �̵��Ѵ�.
			if (addr2 != "") {
				document.getElementById(addr2).focus();
			}
		}
	}).open();
}
</script>

<?=$onload?>

<? INCLUDE ("copyright.php"); ?>