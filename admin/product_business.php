<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "pr-1";
$MenuCode = "product";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

//����Ʈ ����
$setup[page_num] = 10;
$setup[list_num] = 10;

$block=$_REQUEST["block"];
$gotopage=$_REQUEST["gotopage"];

if ($block != "") {
	$nowblock = $block;
	$curpage  = $block * $setup[page_num] + $gotopage;
} else {
	$nowblock = 0;
}

if (($gotopage == "") || ($gotopage == 0)) {
	$gotopage = 1;
}

$type=$_POST["type"];
$mode=$_POST["mode"];

$companycode=$_POST["companycode"];
$up_companyname=$_POST["up_companyname"];
$up_companycharge=$_POST["up_companycharge"];
$up_companychargeposition=$_POST["up_companychargeposition"];
$up_companytel=$_POST["up_companytel"];
$up_companyhp=$_POST["up_companyhp"];
$up_companyemail=$_POST["up_companyemail"];
$up_companynum=$_POST["up_companynum"];
$up_companytype=$_POST["up_companytype"];
$up_companybiz=$_POST["up_companybiz"];
$up_companyitem=$_POST["up_companyitem"];
$up_companyowner=$_POST["up_companyowner"];
$up_companyfax=$_POST["up_companyfax"];
$up_companypost1=$_POST["up_companypost1"];
$up_companypost2=$_POST["up_companypost2"];
$up_companypost=$_POST["up_companypost"];
$up_companyaddr=$_POST["up_companyaddr"];
$up_companyurl=$_POST["up_companyurl"];
$up_companybank=$_POST["up_companybank"];
$up_companybanknum=$_POST["up_companybanknum"];
$up_companymemo=$_POST["up_companymemo"];

$up_companyview1=$_POST["up_companyview1"];
$up_companyview2=$_POST["up_companyview2"];
$up_companyview3=$_POST["up_companyview3"];

$up_companyviewval = $up_companyname;
if($up_companyview1!="Y") { 
	$up_companyview1 = "N";
} else {
	$up_companyviewval.=", ".$up_companycharge." ".$up_companychargeposition;
}

if($up_companyview2!="Y") { 
	$up_companyview2 = "N";
} else {
	$up_companyviewval.=", ".$up_companytel;
}

if($up_companyview3!="Y") { 
	$up_companyview3 = "N";
} else {
	$up_companyviewval.=", ".$up_companyhp;
}

$up_companyview = $up_companyview1.$up_companyview2.$up_companyview3;

if($up_companyview=="NNN") {
	$up_companyview="";
}

if(strlen($up_companyname)>0 && $type=="insert") {
	$sql = "INSERT tblproductbisiness SET ";
	$sql.= "companyname				= '".$up_companyname."', ";
	$sql.= "companycharge			= '".$up_companycharge."', ";
	$sql.= "companychargeposition	= '".$up_companychargeposition."', ";
	$sql.= "companytel				= '".$up_companytel."', ";
	$sql.= "companyhp				= '".$up_companyhp."', ";
	$sql.= "companyfax				= '".$up_companyfax."', ";
	$sql.= "companyemail			= '".$up_companyemail."', ";
	$sql.= "companynum				= '".$up_companynum."', ";
	$sql.= "companytype				= '".$up_companytype."', ";
	$sql.= "companybiz				= '".$up_companybiz."', ";
	$sql.= "companyitem				= '".$up_companyitem."', ";
	$sql.= "companyowner			= '".$up_companyowner."', ";
	$sql.= "companypost				= '".$up_companypost."', ";
	$sql.= "companyaddr				= '".$up_companyaddr."', ";
	$sql.= "companyurl				= '".str_replace("http://", "", $up_companyurl)."', ";
	$sql.= "companybank				= '".$up_companybank."', ";
	$sql.= "companybanknum			= '".$up_companybanknum."', ";
	$sql.= "companymemo				= '".$up_companymemo."', ";
	$sql.= "companyview				= '".$up_companyview."', ";
	$sql.= "companyviewval			= '".$up_companyviewval."' ";
	mysql_query($sql,get_db_conn());
	$onload="<script>alert('�ŷ���ü ����� �Ϸ�Ǿ����ϴ�.');</script>\n";
} else if (strlen($companycode)>0 && $type=="modify") {
	if ($mode=="result") {
		$sql = "UPDATE tblproductbisiness SET ";
		$sql.= "companyname				= '".$up_companyname."', ";
		$sql.= "companycharge			= '".$up_companycharge."', ";
		$sql.= "companychargeposition	= '".$up_companychargeposition."', ";
		$sql.= "companytel				= '".$up_companytel."', ";
		$sql.= "companyhp				= '".$up_companyhp."', ";
		$sql.= "companyfax				= '".$up_companyfax."', ";
		$sql.= "companyemail			= '".$up_companyemail."', ";
		$sql.= "companynum				= '".$up_companynum."', ";
		$sql.= "companytype				= '".$up_companytype."', ";
		$sql.= "companybiz				= '".$up_companybiz."', ";
		$sql.= "companyitem				= '".$up_companyitem."', ";
		$sql.= "companyowner			= '".$up_companyowner."', ";
		$sql.= "companypost				= '".$up_companypost."', ";
		$sql.= "companyaddr				= '".$up_companyaddr."', ";
		$sql.= "companyurl				= '".str_replace("http://", "", $up_companyurl)."', ";
		$sql.= "companybank				= '".$up_companybank."', ";
		$sql.= "companybanknum			= '".$up_companybanknum."', ";
		$sql.= "companymemo				= '".$up_companymemo."', ";
		$sql.= "companyview				= '".$up_companyview."', ";
		$sql.= "companyviewval			= '".$up_companyviewval."' ";
		$sql.= "WHERE companycode = '".$companycode."' ";
		mysql_query($sql,get_db_conn());
		$onload="<script>alert('�ŷ���ü ������ �Ϸ�Ǿ����ϴ�.');</script>\n";
		unset($type);
		unset($mode);
		unset($companycode);
	} else {
		$sql = "SELECT * FROM tblproductbisiness WHERE companycode = '".$companycode."' ";
		$result = mysql_query($sql,get_db_conn());
		$row = mysql_fetch_object($result);
		mysql_free_result($result);
		if ($row) {
			$companycode = $row->companycode;
			$companyname = $row->companyname;
			$companynum = $row->companynum;
			$companyowner = $row->companyowner;
			$companypost = $row->companypost;
			$companypost1 = @substr($row->companypost,0,3);
			$companypost2 = @substr($row->companypost,3);
			$companyaddr = $row->companyaddr;
			$companybiz = $row->companybiz;
			$companyitem = $row->companyitem;
			$companytype = $row->companytype;
			$companycharge = $row->companycharge;
			$companychargeposition = $row->companychargeposition;
			$companyemail = $row->companyemail;
			$companytel = $row->companytel;
			$companyhp = $row->companyhp;
			$companyfax = $row->companyfax;
			$companybank = $row->companybank;
			$companybanknum = $row->companybanknum;
			$companyurl = $row->companyurl;
			$companymemo = $row->companymemo;
			$companyview = $row->companyview;
			
			$companyview_checked = array();
			if(strlen($companyview)>0) {
				for($i=0; $i<strlen($companyview); $i++) {
					if(substr($companyview,$i,1)=="Y") {
						$companyview_checked[$i] = "checked";
					}
				}
			}
		} else {
			$onload="<script>alert('�����Ϸ��� �ŷ���ü�� �������� �ʽ��ϴ�.');<script>";
			unset($type);
			unset($companycode);
		}
	}
} else if (strlen($companycode)>0 && $type=="delete") {
	$sql = "DELETE FROM tblproductbisiness WHERE companycode = '".$companycode."' ";
	if(mysql_query($sql,get_db_conn())) {
		$sql = "UPDATE tblproduct SET bisinesscode='' WHERE bisinesscode = '".$companycode."' ";
		mysql_query($sql,get_db_conn());
		$onload="<script> alert('�ŷ���ü ������ �Ϸ�Ǿ����ϴ�.');</script>\n";
	}
	unset($type);
	unset($companycode);
} 

if (strlen($type)==0) $type="insert";
?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function CheckForm(type) {
	if(document.form1.up_companyname.value.length==0) {
		document.form1.up_companyname.focus();
		alert("��ȣ(ȸ���)�� �Է��ϼ���");
		return;
	}
	if(document.form1.up_companycharge.value.length==0) {
		document.form1.up_companycharge.focus();
		alert("����� ������ �Է��ϼ���");
		return;
	}
	if(document.form1.up_companytel.value.length==0) {
		document.form1.up_companytel.focus();
		alert("��ȭ��ȣ�� �Է��ϼ���");
		return;
	}
	if(document.form1.up_companyhp.value.length==0) {
		document.form1.up_companyhp.focus();
		alert("�޴�����ȣ�� �Է��ϼ���");
		return;
	}
	if(document.form1.up_companyemail.value.length==0) {
		document.form1.up_companyemail.focus();
		alert("�̸����� �Է��ϼ���");
		return;
	}
	if(type=="modify") {
		if(!confirm("������ ��� ��ǰ�� �ŷ���ü ������ �����ϰ� �����˴ϴ�.\n\n�ŷ���ü ������ ���� �����Ͻðڽ��ϱ�?")) {
			return;
		}
		document.form1.mode.value="result";
	}
	if(type=="insert") {
		if(!confirm("�ش� �ŷ���ü ������ ����Ͻðڽ��ϱ�?")) {
			return;
		}
		document.form1.mode.value="result";
	}
	document.form1.type.value=type;
	document.form1.submit();
}
function ContentSend(type,companycode) {
	if(type=="delete") {
		if(!confirm("������ ��� ��ǰ�� �ŷ���ü ������ �����ϰ� �����˴ϴ�.\n\n�ŷ���ü ������ ���� �����Ͻðڽ��ϱ�?")) return;
	}
	document.form1.type.value=type;
	document.form1.companycode.value=companycode;
	document.form1.submit();
}
function GoPage(block,gotopage) {
	document.form2.block.value = block;
	document.form2.gotopage.value = gotopage;
	document.form2.submit();
}
function f_addr_search(form,post,addr,gbn) {
	window.open("../front/addr_search.php?form="+form+"&post="+post+"&addr="+addr+"&gbn="+gbn,"f_post","resizable=yes,scrollbars=yes,x=100,y=200,width=370,height=250");		
}
function BusinessSMS(number) {
	document.smsform.number.value=number;
	window.open("about:blank","sendsmspop","width=220,height=350,scrollbars=no");
	document.smsform.submit();
}
function BusinessMail(mail){
	document.mailform.rmail.value=mail;
	document.mailform.submit();
}
</script>
<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script type="text/javascript">
	<!--
	function addr_search_for_daumapi(post,addr1,addr2) {
		new daum.Postcode({
			oncomplete: function(data) {
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
	//-->
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
			<? include ("menu_product.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ��ǰ���� &gt;ī�װ�/��ǰ���� &gt; <span class="2depth_select">��ǰ �ŷ�ó ����</span></td>
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







			<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post enctype="multipart/form-data">
			<input type=hidden name=type>
			<input type=hidden name=mode>
			<input type=hidden name=companycode value="<?=$companycode?>">
			<input type=hidden name=block value="<?=$block?>">
			<input type=hidden name=gotopage value="<?=$gotopage?>">
			<tr><td height="8"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/product_business_title.gif" border="0"></TD>
					</tr><tr>
					<TD width="100%" background="images/title_bg.gif" height=21></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height="3"></td></tr>
			<tr>
				<td style="padding-bottom:3pt;">
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/distribute_01.gif"></TD>
					<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
					<TD><IMG SRC="images/distribute_03.gif"></TD>
				</TR>
				<TR>
					<TD background="images/distribute_04.gif"></TD>
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
					<TD width="100%" class="notice_blue">��ǰ �ŷ�ó�� ���/����/������ ������ �� �ֽ��ϴ�.</TD>
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
			<tr><td height="20"></td></tr>
			<tr>
				<td>																
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/product_business_stitle01.gif" border="0"></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=3></td></tr>
			<tr>
				<td>
<?
				$colspan=9;
?>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<col width=></col>
				<col width=80></col>
				<col width=60></col>
				<col width=40></col>
				<col width=50></col>
				<col width=28></col>
				<col width=28></col>
				<col width=50></col>
				<col width=50></col>
				<TR>
					<TD colspan=<?=$colspan?> background="images/table_top_line.gif"></TD>
				</TR>
				<TR align=center>
					<TD class="table_cell">��ü��</TD>
					<TD class="table_cell1">�����</TD>
					<TD class="table_cell1">����</TD>
					<TD class="table_cell1">����ó</TD>
					<TD class="table_cell1">����</TD>
					<TD class="table_cell1">�ּ�</TD>
					<TD class="table_cell1">�޸�</TD>
					<TD class="table_cell1">����</TD>
					<TD class="table_cell1">����</TD>
				</TR>
				<TR>
					<TD colspan="<?=$colspan?>" background="images/table_con_line.gif"></TD>
				</TR>
<?
				$sql = "SELECT COUNT(*) as t_count FROM tblproductbisiness ";
				$result = mysql_query($sql,get_db_conn());
				$row = mysql_fetch_object($result);
				$t_count = $row->t_count;
				mysql_free_result($result);
				$pagecount = (($t_count - 1) / $setup[list_num]) + 1;

				$sql = "SELECT * FROM tblproductbisiness ORDER BY companycode DESC ";
				$sql.= "LIMIT " . ($setup[list_num] * ($gotopage - 1)) . ", " . $setup[list_num];
				$result = mysql_query($sql,get_db_conn());
				$cnt=0;
				while($row=mysql_fetch_object($result)) {
					if(strlen($row->companyhp)>0) {
						$row->companyhp=ereg_replace("-","",$row->companyhp);
					}

					echo "<TR align=center>\n";
					echo "	<TD class=\"td_con2\">".$row->companyname."&nbsp;</TD>\n";
					echo "	<TD class=\"td_con1\">".$row->companycharge." ".$row->companychargeposition."&nbsp;</TD>\n";
					echo "	<TD class=\"td_con1\">".$row->companytype."&nbsp;</TD>\n";
					echo "	<TD class=\"td_con1\"><a href=\"javascript:alert('   ��ȭ��ȣ    : ".addslashes($row->companytel)."      \\n   �޴�����ȣ : ".addslashes($row->companyhp)."         \\n   �ѽ���ȣ    : ".addslashes($row->companyfax)."      ');\"><img src=\"images/member_tel.gif\" border=\"0\"></a>".(strlen($row->companyhp)>0?"<img width=2 height=0><a href=\"javascript:BusinessSMS('".addslashes($row->companyhp)."');\"><img src=\"images/member_mobile.gif\" border=\"0\"></a>":"")."</TD>\n";
					echo "	<TD class=\"td_con1\"><a href=\"javascript:BusinessMail('".addslashes($row->companyemail)."');\"><img src=\"images/icon_mail.gif\" border=\"0\"></a></TD>\n";
					echo "	<TD class=\"td_con1\"><a href=\"javascript:alert('   ����� �ּ� : ".addslashes(substr($row->companypost,0,3)."-".substr($row->companypost,3)." ".$row->companyaddr)."      ');\"><img src=\"images/addr_home.gif\" border=\"0\"></a></TD>\n";
					echo "	<TD class=\"td_con1\">".(strlen($row->companymemo)>0?"<img src=\"images/ordtll_icnmemo.gif\" border=\"0\" alt=\"".htmlspecialchars($row->companymemo)."\">":"&nbsp;")."</TD>\n";
					echo "	<TD class=\"td_con1\"><a href=\"javascript:ContentSend('modify','".$row->companycode."');\"><img src=\"images/btn_edit.gif\" border=\"0\"></a></TD>\n";
					echo "	<TD class=\"td_con1\"><a href=\"javascript:ContentSend('delete','".$row->companycode."');\"><img src=\"images/btn_del.gif\" border=\"0\"></a></TD>\n";
					echo "</TR>\n";
					echo "<TR>\n";
					echo	"	<TD colspan=".$colspan." background=\"images/table_con_line.gif\"></TD>\n";
					echo "</TR>\n";
					$cnt++;
				}
				mysql_free_result($result);

				if ($cnt==0) {
					echo "<tr><td class=td_con2 colspan=".$colspan." align=center>�˻��� ������ �������� �ʽ��ϴ�.</td></tr>";
				}
?>
				<TR>
					<TD colspan=<?=$colspan?> background="images/table_top_line.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height=10></td>
			</tr>
			<tr>
				<td>
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td align=center class="font_size">
<?
					$total_block = intval($pagecount / $setup[page_num]);

					if (($pagecount % $setup[page_num]) > 0) {
						$total_block = $total_block + 1;
					}

					$total_block = $total_block - 1;

					if (ceil($t_count/$setup[list_num]) > 0) {
						// ����	x�� ����ϴ� �κ�-����
						$a_first_block = "";
						if ($nowblock > 0) {
							$a_first_block .= "<a href='javascript:GoPage(0,1);' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='ù ������';return true\"><IMG src=\"images/icon_first.gif\" border=0 align=\"absmiddle\"></a>&nbsp;&nbsp;";

							$prev_page_exists = true;
						}

						$a_prev_page = "";
						if ($nowblock > 0) {
							$a_prev_page .= "<a href='javascript:GoPage(".($nowblock-1).",".($setup[page_num]*($block-1)+$setup[page_num]).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup[page_num]." ������';return true\">[prev]</a>&nbsp;&nbsp;";

							$a_prev_page = $a_first_block.$a_prev_page;
						}

						// �Ϲ� �������� ������ ǥ�úκ�-����

						if (intval($total_block) <> intval($nowblock)) {
							$print_page = "";
							for ($gopage = 1; $gopage <= $setup[page_num]; $gopage++) {
								if ((intval($nowblock*$setup[page_num]) + $gopage) == intval($gotopage)) {
									$print_page .= "<span class=font_orange2><B>[".(intval($nowblock*$setup[page_num]) + $gopage)."]</B></span> ";
								} else {
									$print_page .= "<a href='javascript:GoPage(".$nowblock.",".(intval($nowblock*$setup[page_num]) + $gopage).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='������ : ".(intval($nowblock*$setup[page_num]) + $gopage)."';return true\">[".(intval($nowblock*$setup[page_num]) + $gopage)."]</a> ";
								}
							}
						} else {
							if (($pagecount % $setup[page_num]) == 0) {
								$lastpage = $setup[page_num];
							} else {
								$lastpage = $pagecount % $setup[page_num];
							}

							for ($gopage = 1; $gopage <= $lastpage; $gopage++) {
								if (intval($nowblock*$setup[page_num]) + $gopage == intval($gotopage)) {
									$print_page .= "<span class=font_orange2><B>[".(intval($nowblock*$setup[page_num]) + $gopage)."]</B></span> ";
								} else {
									$print_page .= "<a href='javascript:GoPage(".$nowblock.",".(intval($nowblock*$setup[page_num]) + $gopage).");' onMouseOver=\"window.status='������ : ".(intval($nowblock*$setup[page_num]) + $gopage)."';return true\">[".(intval($nowblock*$setup[page_num]) + $gopage)."]</a> ";
								}
							}
						}		// ������ �������� ǥ�úκ�-��


						$a_last_block = "";
						if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
							$last_block = ceil($t_count/($setup[list_num]*$setup[page_num])) - 1;
							$last_gotopage = ceil($t_count/$setup[list_num]);

							$a_last_block .= "&nbsp;&nbsp;<a href='javascript:GoPage(".$last_block.",".$last_gotopage.");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='������ ������';return true\"><IMG src=\"images/icon_last.gif\" border=0 align=\"absmiddle\" width=\"17\" height=\"14\"></a>";

							$next_page_exists = true;
						}

						// ���� 10�� ó���κ�...

						$a_next_page = "";
						if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
							$a_next_page .= "&nbsp;&nbsp;<a href='javascript:GoPage(".($nowblock+1).",".($setup[page_num]*($nowblock+1)+1).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup[page_num]." ������';return true\">[next]</a>";

							$a_next_page = $a_next_page.$a_last_block;
						}
					} else {
						$print_page = "<B>[1]</B>";
					}
?>
					<?=$a_div_prev_page.$a_prev_page.$print_page.$a_next_page.$a_div_next_page?>
					</td>
				</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td height="30"></td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/product_business_stitle02.gif" border="0"></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=3></td></tr>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<col width=140></col>
				<col width=210></col>
				<col width=140></col>
				<col width=></col>
				<TR>
					<TD colspan=4 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" border="0"><font color="#FF4C00">��ȣ(ȸ���)</TD>
					<TD class="td_con1"><INPUT style="WIDTH: 100%" name=up_companyname class="input" value="<?=$companyname?>" maxlength="30" onKeyDown="chkFieldMaxLen(30)"></TD>
					<TD class="table_cell" style="border-left:#E3E3E3 1px solid;"><img src="images/icon_point2.gif" border="0"><font color="#FF4C00">����� ����</font></TD>
					<TD class="td_con1"><INPUT style="WIDTH: 50%" name=up_companycharge class="input" value="<?=$companycharge?>" onKeyDown="chkFieldMaxLen(20)" maxlength="20"> ���� : <INPUT style="WIDTH: 30%" name=up_companychargeposition class="input" value="<?=$companychargeposition?>" maxlength="20" onKeyDown="chkFieldMaxLen(20)"></TD>
				</TR>
				<TR>
					<TD colspan="4" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" border="0"><font color="#FF4C00">��ȭ��ȣ</font></TD>
					<TD class="td_con1"><INPUT style="WIDTH: 100%" name=up_companytel class="input" value="<?=$companytel?>" maxlength="20" onKeyDown="chkFieldMaxLen(20)"></TD>
					<TD class="table_cell" style="border-left:#E3E3E3 1px solid;"><img src="images/icon_point2.gif" border="0"><font color="#FF4C00">�޴�����ȣ</font></TD>
					<TD class="td_con1"><INPUT style="WIDTH: 100%" name=up_companyhp class="input" value="<?=$companyhp?>" maxlength="20" onKeyDown="chkFieldMaxLen(20)"></TD>
				</TR>
				<TR>
					<TD colspan=4 background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" border="0"><font color="#FF4C00">�̸���</font></TD>
					<TD class="td_con1" colspan="3"><INPUT style="WIDTH: 100%" name=up_companyemail class="input" value="<?=$companyemail?>" maxlength="70" onKeyDown="chkFieldMaxLen(70)"></TD>
				</TR>
				<TR>
					<TD colspan=4 background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" border="0">����ڵ�Ϲ�ȣ</TD>
					<TD class="td_con1"><INPUT style="WIDTH: 100%" name=up_companynum class="input" value="<?=$companynum?>" maxlength="20" onKeyDown="chkFieldMaxLen(20)"></TD>
					<TD class="table_cell" style="border-left:#E3E3E3 1px solid;"><img src="images/icon_point2.gif" border="0">��ü����</TD>
					<TD class="td_con1"><INPUT style="WIDTH: 100%" name=up_companytype class="input" value="<?=$companytype?>" maxlength="20" onKeyDown="chkFieldMaxLen(20)"></TD>
				</TR>
				<TR>
					<TD colspan=4 background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" border="0">����� ����</TD>
					<TD class="td_con1"><INPUT style="WIDTH: 100%" name=up_companybiz class="input" value="<?=$companybiz?>" maxlength="20" onKeyDown="chkFieldMaxLen(20)"></TD>
					<TD class="table_cell" style="border-left:#E3E3E3 1px solid;"><img src="images/icon_point2.gif" border="0">����� ����</TD>
					<TD class="td_con1"><INPUT style="WIDTH: 100%" name=up_companyitem class="input" value="<?=$companyitem?>" maxlength="20" onKeyDown="chkFieldMaxLen(20)"></TD>
				</TR>
				<TR>
					<TD colspan=4 background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" border="0">��ǥ�� ����</TD>
					<TD class="td_con1"><INPUT style="WIDTH: 100%" name=up_companyowner class="input" value="<?=$companyowner?>" maxlength="20" onKeyDown="chkFieldMaxLen(20)"></TD>
					<TD class="table_cell" style="border-left:#E3E3E3 1px solid;"><img src="images/icon_point2.gif" border="0">�ѽ���ȣ</TD>
					<TD class="td_con1"><INPUT style="WIDTH: 100%" name=up_companyfax class="input" value="<?=$companyfax?>" maxlength="20" onKeyDown="chkFieldMaxLen(20)"></TD>
				</TR>
				<TR>
					<TD colspan=4 background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<td class="table_cell"><img src="images/icon_point2.gif" border="0">����� �ּ�</td>
					<td colspan="3" bgcolor="#FFFFFF" class="td_con1">
					<table width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td width="80" nowrap><!--input type=text name="up_companypost1" value="<?=$companypost1?>" size="3" maxlength="3" class="input" onKeyDown="chkFieldMaxLen(20)"> - <input type=text name="up_companypost2" value="<?=$companypost2?>" size="3" maxlength="3" class="input" onKeyDown="chkFieldMaxLen(20)"--><input type=text name="up_companypost" value="<?=$companypost?>" size="7" maxlength="7" class="input" onKeyDown="chkFieldMaxLen(20)"></td>
						<td width="100%"><A href="javascript:addr_search_for_daumapi('up_companypost','up_companyaddr','');" onfocus="this.blur();" style="selector-dummy: true" class="board_list hideFocus"><img src="images/icon_addr.gif" border="0"></A></td>
					</tr>
					<tr>
						<td colspan="2"><input style="WIDTH: 100%" type=text name="up_companyaddr" value="<?=$companyaddr?>" maxlength="150" class="input" onKeyDown="chkFieldMaxLen(150)"></td>
					</tr>
					</table>
					</td>
				</tr>
				<TR>
					<TD colspan=4 background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" border="0">Ȩ������</TD>
					<TD class="td_con1" colspan="3">
					<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
					<col width="40"></col>
					<col width=""></col>
					<tr>
						<td>http://&nbsp;</td>
						<td><INPUT style="WIDTH: 100%" name=up_companyurl class="input" value="<?=$companyurl?>" maxlength="70" onKeyDown="chkFieldMaxLen(70)"></TD>
					</tr>
					</table>
					</td>
				</TR>
				<TR>
					<TD colspan=4 background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" border="0">�ŷ�����</TD>
					<TD class="td_con1"><INPUT style="WIDTH: 100%" name=up_companybank class="input" value="<?=$companybank?>" maxlength="20" onKeyDown="chkFieldMaxLen(20)"></TD>
					<TD class="table_cell" style="border-left:#E3E3E3 1px solid;"><img src="images/icon_point2.gif" border="0">���¹�ȣ</TD>
					<TD class="td_con1"><INPUT style="WIDTH: 100%" name=up_companybanknum class="input" value="<?=$companybanknum?>" maxlength="20" onKeyDown="chkFieldMaxLen(20)"></TD>
				</TR>
				<TR>
					<TD colspan=4 background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" border="0">�޸�</TD>
					<TD class="td_con1" colspan="3"><TEXTAREA style="WIDTH: 100%; HEIGHT: 100px" name=up_companymemo class="textarea"><?=htmlspecialchars($companymemo)?></TEXTAREA></TD>
				</TR>
				<TR>
					<TD colspan=4 background="images/table_top_line.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td><b>�����׸� : </b><span class="font_orange" style="font-size:11px;letter-spacing:-0.5pt;"><b><input type=checkbox checked disabled>��ü��(�⺻)&nbsp;
			<input type=checkbox name=up_companyview1 id="idx_view1" value="Y" <?=$companyview_checked[0]?>><label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_view1">����ڼ���,����</label>&nbsp;
			<input type=checkbox name=up_companyview2 id="idx_view2" value="Y" <?=$companyview_checked[1]?>><label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_view2">��ȭ��ȣ</label>&nbsp;
			<input type=checkbox name=up_companyview3 id="idx_view3" value="Y" <?=$companyview_checked[2]?>><label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_view3">�޴�����ȣ</label>
			</b></span></td></tr>
			<tr>
				<td><span class="font_orange" style="font-size:11px;letter-spacing:-0.5pt;">* �����׸��̶� ��ǰ ���/���� �Ǵ� �ֹ��� ���� �ٿ�ε��� ��� ����Ǵ� �ŷ�ó �����Դϴ�.</span></td>
			</tr>
			<tr>
				<td align=center><a href="javascript:CheckForm('<?=$type?>');"><img src="images/botteon_save.gif" border="0"></a></td>
			</tr>
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
					<td background="images/manual_bg.gif"><IMG SRC="images/manual_top2.gif" WIDTH=18 height="45" ALT=""></td>
				</TR>
				<TR>
					<TD background="images/manual_left1.gif"></TD>
					<TD COLSPAN=3 width="100%" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;" class="menual_bg">
					<table cellpadding="0" cellspacing="0" width="100%">
					<col width=20></col>
					<col width=></col>
					<tr>
						<td align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><span class="font_dotline">��ǰ �ŷ�ó ����</span></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- ��ǰ ���/������ �ŷ�ó ���� �׸��� ��µ˴ϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- ��ǰ �ŷ�ó ����/������ ��� ��ǰ �ŷ�ó���� ���� �ݿ��˴ϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- �����׸��� ��ǰ ���/������ ��� �ŷ�ó ���� �׸� ����Ǵ� �����Դϴ�.</td>
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
			</form>

			<form name=form2 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=type>
			<input type=hidden name=block value="<?=$block?>">
			<input type=hidden name=gotopage value="<?=$gotopage?>">
			</form>
			<form name=smsform action="sendsms.php" method=post target="sendsmspop">
			<input type=hidden name=number>
			</form>
			<form name=mailform action="member_mailsend.php" method=post>
			<input type=hidden name=rmail>
			</form>
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
<?=$onload?>
<? INCLUDE "copyright.php"; ?>