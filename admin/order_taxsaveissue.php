<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "or-3";
$MenuCode = "order";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$tax_cnum=$_shopdata->tax_cnum;
$tax_cname=$_shopdata->tax_cname;
$tax_cowner=$_shopdata->tax_cowner;
$tax_caddr=$_shopdata->tax_caddr;
$tax_ctel=$_shopdata->tax_ctel;
$tax_type=$_shopdata->tax_type;
$tax_rate=$_shopdata->tax_rate;
$tax_mid=$_shopdata->tax_mid;
$tax_tid=$_shopdata->tax_tid;

$tax_cnum1=substr($tax_cnum,0,3);
$tax_cnum2=substr($tax_cnum,3,2);
$tax_cnum3=substr($tax_cnum,5,5);

if(strlen($tax_cnum)==0) {
	echo "<html></head><body onload=\"alert('���ݿ����� ȯ�漳�� �� �̿��Ͻñ� �ٶ��ϴ�.');location.href='order_taxsaveabout.php';\"></body></html>";exit;
}

$mode=$_POST["mode"];
if($mode=="insert") {
	########################### TEST ���θ� Ȯ�� ##########################
	DemoShopCheck("������������� �׽�Ʈ�� �Ұ��� �մϴ�.", $_SERVER[PHP_SELF]);
	#######################################################################

	$up_name=$_POST["up_name"];
	$up_email=$_POST["up_email"];
	$up_productname=$_POST["up_productname"];
	$up_amt=(int)$_POST["up_amt"];
	$up_tr_code=$_POST["up_tr_code"];
	$up_gbn=$_POST["up_gbn"];

	$up_resno1=$_POST["up_resno1"];
	$up_resno2=$_POST["up_resno2"];

	$up_mobile1=$_POST["up_mobile1"];
	$up_mobile2=$_POST["up_mobile2"];
	$up_mobile3=$_POST["up_mobile3"];

	$up_comnum1=$_POST["up_comnum1"];
	$up_comnum2=$_POST["up_comnum2"];
	$up_comnum3=$_POST["up_comnum3"];

	if($tax_rate==10) {
		$up_amt1=$up_amt;
		$up_amt4=floor(($up_amt1/1.1)*0.1);
		$up_amt2=$up_amt1-$up_amt4;
		$up_amt3=0;
	} else {
		$up_amt1=$up_amt;
		$up_amt2=0;
		$up_amt3=0;
		$up_amt4=0;
	}

	if($up_tr_code=="0") {	//����
		if($up_gbn=="0") {
			$up_id_info=$up_resno1.$up_resno2;	//�ֹι�ȣ
		} else {
			$up_id_info=$up_mobile1.$up_mobile2.$up_mobile3;	//�ڵ�����ȣ
		}
	} else {	//�����
		$up_id_info=$up_comnum1.$up_comnum2.$up_comnum3;	//����ڹ�ȣ
	}

	$ordercode=unique_id();
	$tsdtime=substr($ordercode,0,14);
	$sql = "INSERT tbltaxsavelist SET ";
	$sql.= "ordercode		= '".$ordercode."', ";
	$sql.= "tsdtime			= '".$tsdtime."', ";
	$sql.= "tr_code			= '".$up_tr_code."', ";
	$sql.= "tax_no			= '".$tax_cnum."', ";
	$sql.= "id_info			= '".$up_id_info."', ";
	$sql.= "name			= '".$up_name."', ";
	$sql.= "email			= '".$up_email."', ";
	$sql.= "productname		= '".$up_productname."', ";
	$sql.= "amt1			= ".$up_amt1.", ";
	$sql.= "amt2			= ".$up_amt2.", ";
	$sql.= "amt3			= ".$up_amt3.", ";
	$sql.= "amt4			= ".$up_amt4.", ";
	$sql.= "type			= 'N' ";
	if(mysql_query($sql,get_db_conn())) {
		$onload="<script>alert('���ݿ����� �����߱� ��û�� �Ϸ�Ǿ����ϴ�.\\n\\n���ݿ����� �߱�/��ȸ���� ���������� �߱��Ͻø� ����û�� �Ű�˴ϴ�.');</script>";
	} else {
		$onload="<script>alert('���ݿ����� �߱޿�û�� �����Ͽ����ϴ�.');</script>";
	}
}

?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function CheckForm() {
	if(document.form1.up_name.value.length==0) {
		alert("�ֹ��ڸ��� �Է��ϼ���.");
		document.form1.up_name.focus();
		return;
	}
	if(document.form1.up_email.value.length==0) {
		alert("�ֹ��� �̸����� �Է��ϼ���.");
		document.form1.up_email.focus();
		return;
	}
	if(!IsMailCheck(document.form1.up_email.value)) {
		alert("�̸��� �Է��� �߸��Ǿ����ϴ�.");
		document.form1.up_email.focus();
		return;
	}
	if(document.form1.up_productname.value.length==0) {
		alert("�ֹ� ��ǰ���� �Է��ϼ���.");
		document.form1.up_productname.focus();
		return;
	}
	if(document.form1.up_amt.value.length==0) {
		alert("�ֹ� ��ǰ������ �Է��ϼ���.");
		document.form1.up_amt.focus();
		return;
	}
	if(!IsNumeric(document.form1.up_amt.value)) {
		alert("�ֹ� ��ǰ������ ���ڸ� �Է��ϼ���.");
		document.form1.up_amt.focus();
		return;
	}
	if(document.form1.up_amt.value<1) {
		alert("�ֹ� ��ǰ������ 1�� �̻� ����� �����մϴ�.");
		document.form1.up_amt.focus();
		return;
	}

	if(document.form1.up_tr_code[0].checked==true) {
		if(document.form1.up_gbn[0].checked==true) {
			if(document.form1.up_resno1.value.length==0 || document.form1.up_resno2.value.length==0 || document.form1.up_resno1.value.length!=6 || document.form1.up_resno2.value.length!=7) {
				alert("�ֹι�ȣ�� ��Ȯ�� �Է��ϼ���.");
				document.form1.up_resno1.focus();
				return;
			}
			if(!chkResNo(document.form1.up_resno1.value+"-"+document.form1.up_resno2.value)) {
				alert("�ֹι�ȣ �Է��� �߸��Ǿ����ϴ�.");
				document.form1.up_resno1.focus();
				return;
			}
		} else {
			mobile1=document.form1.up_mobile1;
			mobile2=document.form1.up_mobile2;
			mobile3=document.form1.up_mobile3;
			if(mobile1.value.length==0 || mobile2.value.length==0 || mobile3.value.length==0) {
				alert("�ڵ�����ȣ�� ��Ȯ�� �Է��ϼ���.");
				mobile1.focus();
				return;
			}
			if(!IsNumeric(mobile1.value)) {
				alert("�ڵ�����ȣ�� ���ڸ� �Է��ϼ���.");
				mobile1.focus();
				return;
			}
			if(!IsNumeric(mobile2.value)) {
				alert("�ڵ�����ȣ�� ���ڸ� �Է��ϼ���.");
				mobile2.focus();
				return;
			}
			if(!IsNumeric(mobile3.value)) {
				alert("�ڵ�����ȣ�� ���ڸ� �Է��ϼ���.");
				mobile3.focus();
				return;
			}
			if(mobile1.value=="010" || mobile1.value=="011" || mobile1.value=="016" || mobile1.value=="017" || mobile1.value=="018" || mobile1.value=="019") {
				if(mobile2.value.length<3 && mobile3.value.length<4) {
					alert("�ڵ�����ȣ�� ��Ȯ�� �Է��ϼ���.");
					mobile2.focus();
					return;
				}
			} else {
				alert("�ڵ�����ȣ�� ��Ȯ�� �Է��ϼ���.");
				mobile1.focus();
				return;
			}
		}
	} else {
		//����ڹ�ȣ üũ
		biz1=document.form1.up_comnum1.value;
		biz2=document.form1.up_comnum2.value;
		biz3=document.form1.up_comnum3.value;
		if(!chkBizNo(biz1+""+biz2+""+biz3)) {
			alert("����ڹ�ȣ �Է��� �߸��Ǿ����ϴ�.");
			document.form1.up_comnum1.focus();
			return;
		}
	}

	document.form1.mode.value="insert";
	document.form1.submit();
}

function ViewLayer(layer) {
	if(layer=="layer2") {
		document.all["layer1"].style.display="none";
		document.all["layer2"].style.display="";
		document.form1.up_gbn[2].checked=true;

		document.form1.up_comnum1.disabled=false;
		document.form1.up_comnum2.disabled=false;
		document.form1.up_comnum3.disabled=false;

		document.form1.up_resno1.disabled=true;
		document.form1.up_resno2.disabled=true;
		document.form1.up_mobile1.disabled=true;
		document.form1.up_mobile2.disabled=true;
		document.form1.up_mobile3.disabled=true;

		document.form1.up_resno1.value="";
		document.form1.up_resno2.value="";
		document.form1.up_mobile1.value="";
		document.form1.up_mobile2.value="";
		document.form1.up_mobile3.value="";

	} else {
		document.all["layer2"].style.display="none";
		document.all["layer1"].style.display="";
		document.form1.up_gbn[0].checked=true;

		document.form1.up_comnum1.disabled=true;
		document.form1.up_comnum2.disabled=true;
		document.form1.up_comnum3.disabled=true;

		document.form1.up_comnum1.value="";
		document.form1.up_comnum2.value="";
		document.form1.up_comnum3.value="";

		document.form1.up_mobile1.disabled=true;
		document.form1.up_mobile2.disabled=true;
		document.form1.up_mobile3.disabled=true;

		document.form1.up_resno1.disabled=false;
		document.form1.up_resno2.disabled=false;
	}
}
function change_gbn(gbn) {
	if(gbn==0) {
		document.form1.up_resno1.disabled=false;
		document.form1.up_resno2.disabled=false;
		document.form1.up_mobile1.disabled=true;
		document.form1.up_mobile2.disabled=true;
		document.form1.up_mobile3.disabled=true;
		document.form1.up_mobile1.value="";
		document.form1.up_mobile2.value="";
		document.form1.up_mobile3.value="";
	} else if(gbn==1) {
		document.form1.up_mobile1.disabled=false;
		document.form1.up_mobile2.disabled=false;
		document.form1.up_mobile3.disabled=false;
		document.form1.up_resno1.disabled=true;
		document.form1.up_resno2.disabled=true;
		document.form1.up_resno1.value="";
		document.form1.up_resno2.value="";
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
			<? include ("menu_order.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" width="16" height="12" border="0" valign=absmiddle>������ġ : �ֹ�/���� &gt; ���ݿ����� ���� &gt; <span class="2depth_select">���ݿ����� �����߱�</span></td>
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
			<tr>
				<td height="8">
				</td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/order_taxsaveissue_title.gif"  ALT=""></TD>
					</tr><tr>
					<TD width="100%" background="images/title_bg.gif" height="21"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height="3"></td>
			</tr>
			<tr>
				<td style="padding-bottom:3pt;">
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/distribute_01.gif"></TD>
					<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
					<TD><IMG SRC="images/distribute_03.gif"></TD>
				</TR>
				<TR>
					<TD background="images/distribute_04.gif"><IMG SRC="images/distribute_04.gif" ></TD>
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
					<TD width="100%" class="notice_blue"><p>���ݿ������� ���������� �߱޿�û�� �����մϴ�.</p></TD>
					<TD background="images/distribute_07.gif"><IMG SRC="images/distribute_07.gif" ></TD>
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
				<td height="20"></td>
			</tr>





		<tr>
			<td>
			<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
			<TR>
				<TD><IMG SRC="images/order_taxsavelist_stitle1.gif" WIDTH="192" HEIGHT=31 ALT=""></TD>
				<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
				<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
			</TR>
			</TABLE>
			</td>
		</tr>
		<tr>
			<td height=3></td>
		</tr>
		<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
		<input type=hidden name=mode>
		<tr>
			<td>
			<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
			<TR>
				<TD background="images/table_top_line.gif" width="150"><img src="images/table_top_line.gif"></TD>
				<TD background="images/table_top_line.gif"></TD>
			</TR>
			<TR>
				<TD class="table_cell" width="150"><img src="images/icon_point2.gif" width="8" height="11" border="0">�ֹ��ڸ�</TD>
				<TD class="td_con1"><input type=text name=up_name size=30 maxlength=20 class="input"></TD>
			</TR>
			<TR>
				<TD colspan="2" background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></TD>
			</TR>
			<TR>
				<TD class="table_cell" width="150"><img src="images/icon_point2.gif" width="8" height="11" border="0">�̸���</TD>
				<TD class="td_con1"><input type=text name=up_email size=30 maxlength=30 class="input"></TD>
			</TR>
			<TR>
				<TD colspan="2" background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></TD>
			</TR>
			<TR>
				<TD class="table_cell" width="150"><img src="images/icon_point2.gif" width="8" height="11" border="0">��ǰ��</TD>
				<TD class="td_con1"><input type=text name=up_productname size=30 maxlength=30 class="input"></TD>
			</TR>
			<TR>
				<TD colspan="2" background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></TD>
			</TR>
			<TR>
				<TD class="table_cell" width="150"><img src="images/icon_point2.gif" width="8" height="11" border="0">��ǰ����</TD>
				<TD class="td_con1"><input type=text name=up_amt size=12 maxlength=12 style="text-align:right" onkeyup="strnumkeyup(this)" class="input">�� &nbsp;<span class="font_orange">* �� ��ǰ����(�Ϲݰ��������� ���� �ΰ��� 10%�� ����Ͽ� �Ű�˴ϴ�.)</span></TD>
			</TR>
			<TR>
				<TD colspan="2" background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></TD>
			</TR>
			<TR>
				<TD class="table_cell" width="150" rowspan="2"><img src="images/icon_point2.gif" width="8" height="11" border="0">�߱�����</TD>
				<TD class="td_con1" style="border-bottom-width:1pt; border-bottom-color:rgb(237,237,237); border-bottom-style:solid;"><input type=radio id="idx_tr_code0" name=up_tr_code value="0" checked onclick="ViewLayer('layer1')"><label style='cursor:hand; TEXT-DECORATION: none' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_tr_code0>����</label>&nbsp;&nbsp;&nbsp;<input type=radio id="idx_tr_code1" name=up_tr_code value="1" onclick="ViewLayer('layer2')"><label style='cursor:hand; TEXT-DECORATION: none' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_tr_code1>�����</label></TD>
			</TR>
			<TR>
				<TD class="td_con1">
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td>
					<DIV id=layer1 style="BORDER-RIGHT: black 0px solid; PADDING-RIGHT: 0px; BORDER-TOP: black 0px solid; DISPLAY: block; PADDING-LEFT: 0px; BACKGROUND: #ffffff; PADDING-BOTTOM: 0px; MARGIN-LEFT: 0px; BORDER-LEFT: black 0px solid; PADDING-TOP: 0px; BORDER-BOTTOM: black 0px solid">
					<input type=radio id="idx_gbn0" name=up_gbn value="0" checked onclick="change_gbn(0)"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_gbn0>�ֹι�ȣ</label>&nbsp;&nbsp;
					<input type=text name=up_resno1 size=6 maxlength=6 onkeyup="strnumkeyup(this)" class="input"> - <input type=text name=up_resno2 size=7 maxlength=7 onkeyup="strnumkeyup(this)" class="input">
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input type=radio id="idx_gbn1" name=up_gbn value="1" onclick="change_gbn(1)"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_gbn1>�ڵ���</label>&nbsp;&nbsp;
					<input type=text name=up_mobile1 size=3 maxlength=3 disabled onkeyup="strnumkeyup(this)" class="input"> - <input type=text name=up_mobile2 size=4 maxlength=4 disabled onkeyup="strnumkeyup(this)" class="input"> - <input type=text name=up_mobile3 size=4 maxlength=4 disabled onkeyup="strnumkeyup(this)" class="input">
					</DIV>
					<div id=layer2 style="BORDER-RIGHT: black 0px solid; PADDING-RIGHT: 0px; BORDER-TOP: black 0px solid; DISPLAY: none; PADDING-LEFT: 0px; BACKGROUND: #ffffff; PADDING-BOTTOM: 0px; MARGIN-LEFT: 0px; BORDER-LEFT: black 0px solid; PADDING-TOP: 0px; BORDER-BOTTOM: black 0px solid">
					<input type=radio id="idx_gbn2" name=up_gbn value="2" style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_gbn2>����ڹ�ȣ</label>&nbsp;&nbsp;
					<input type=text name=up_comnum1 size=3 maxlength=3 disabled onkeyup="strnumkeyup(this)" class="input"> - <input type=text name=up_comnum2 size=2 maxlength=2 disabled onkeyup="strnumkeyup(this)" class="input"> - <input type=text name=up_comnum3 size=5 maxlength=5 disabled onkeyup="strnumkeyup(this)" class="input">
					</div>
					</td>
				</tr>
				</table>
				</TD>
			</TR>
			<TR>
				<TD background="images/table_top_line.gif" width="150"><img src="images/table_top_line.gif"></TD>
				<TD background="images/table_top_line.gif"></TD>
			</TR>
			</TABLE>
			</td>
		</tr>
		<tr>
			<td height=10></td>
		</tr>
		<tr>
			<td align="center"><p><a href="javascript:CheckForm();"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a></p></td>
		</tr>
		</form>
		<tr>
			<td height="20"></td>
		</tr>
		<tr>
			<td>
			<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
			<TR>
				<TD><IMG SRC="images/manual_top1.gif" WIDTH=15 height="45" ALT=""></TD>
				<TD><IMG SRC="images/manual_title.gif" WIDTH=113 height="45" ALT=""></TD>
				<TD width="100%" background="images/manual_bg.gif" height="35"></TD>
				<TD background="images/manual_bg.gif"></TD>
				<td background="images/manual_bg.gif"><IMG SRC="images/manual_top2.gif" WIDTH=18 height="45" ALT=""></TD>
			</TR>
			<TR>
				<TD background="images/manual_left1.gif"><IMG SRC="images/manual_left1.gif" WIDTH=15 HEIGHT="5" ALT=""></TD>
				<TD COLSPAN=3 width="100%" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;">
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td width="701"><span class="font_dotline">���ݿ����� �����߱�</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td width="701" class="space_top" style="letter-spacing:-0.5pt;"><p>- �ֹ����� ���� ������ �Ա��� �ƴ� ��쳪 ��Ÿ �������� �ݾ׿� ���ؼ��� ���ݿ����� �߱��� �����մϴ�.</p></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td width="701" class="space_top" style="letter-spacing:-0.5pt;"><p>- ���ݿ����� �߱޽� ����û�� �뺸�Ǳ� ������ ��Ȯ�� �ڷḦ �Է��ؾ� �մϴ�.</p></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td width="701" class="space_top" style="letter-spacing:-0.5pt;"><p>- �����߱��� �ϸ� �߱޿�û���� ó�������� �߱� �� <a href="javascript:parent.topframe.GoMenu(5,'order_taxsavelist.php');"><span class="font_blue">�ֹ�/���� > ���ݿ����� ���� > ���ݿ����� �߱�/��ȸ</span></a> ���� ���� �߱��� �ϼž� �մϴ�.</p></td>
					</tr>
				</table>
				</TD>
				<TD background="images/manual_right1.gif"><IMG SRC="images/manual_right1.gif" WIDTH=18 HEIGHT="2" ALT=""></TD>
			</TR>
			<TR>
				<TD><IMG SRC="images/manual_left2.gif" WIDTH=15 HEIGHT=8 ALT=""></TD>
				<TD COLSPAN=3 background="images/manual_down.gif"><IMG SRC="images/manual_down.gif" WIDTH="4" HEIGHT=8 ALT=""></TD>
				<TD><IMG SRC="images/manual_right2.gif" WIDTH=18 HEIGHT=8 ALT=""></TD>
			</TR>
			</TABLE>
			</td>
		</tr>
		<tr>
			<td height="50"></td>
		</tr>
		</table>
		</td>
	</tr>
	</table>
	</td>
</tr>
</table>
<?=$onload?>

<? INCLUDE "copyright.php"; ?>