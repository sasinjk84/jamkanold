<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "sh-3";
$MenuCode = "shop";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$type=$_POST["type"];
$up_val=$_POST["up_val"];
$up_product_filter=$_POST["up_product_filter"];
$up_review_type=$_POST["up_review_type"];
$up_reviewlist=$_POST["up_reviewlist"];
$up_leftreview=$_POST["up_leftreview"];
$up_review_memtype=$_POST["up_review_memtype"];
$up_review_date=$_POST["up_review_date"];
$up_review_filter=$_POST["up_review_filter"];
$up_reviewrow=$_POST["up_reviewrow"];


if ($type=="up") {

	// ##### �������͸� �� rowsu ���
	if (strpos($up_review_filter,"#")!==false) {
		echo "<script> alert ('���͸��ܾ ��#���� �Է��Ͻ� �� �����ϴ�.');location.href='".$_SERVER[PHP_SELF]."';</script>\n";
		exit;
	}
	$filter=$up_product_filter."#".$up_review_filter."REVIEWROW".$up_reviewrow;

	$etctype=$up_val;
	if ($up_reviewlist!="0"){
		$etctype.= "REVIEWLIST=".$up_reviewlist.""; // ������÷��� ���
	}
	if ($up_leftreview!="N"){
		$etctype.= "REVIEW=".$up_leftreview.""; // ����ı�޴�
	}
	if ($up_review_date=="N"){
		$etctype.= "REVIEWDATE=".$up_review_date.""; // �����ϳ�¥ ǥ�þ��� 
	}

	$sql = "UPDATE tblshopinfo SET ";
	$sql.= "review_type		= '".$up_review_type."', ";
	$sql.= "review_memtype	= '".$up_review_memtype."', ";
	$sql.= "etctype			= '".$etctype."', ";
	$sql.= "filter			= '".$filter."' ";
	$update = mysql_query($sql,get_db_conn());
	DeleteCache("tblshopinfo.cache");
	$onload="<script>alert('�� ��ǰ���� ������ �Ϸ�Ǿ����ϴ�.');</script>";
}

$sql = "SELECT review_type,review_memtype,etctype,filter FROM tblshopinfo ";
$result = mysql_query($sql,get_db_conn());
$row=mysql_fetch_object($result);
mysql_free_result($result);

$review_type=$row->review_type;
$review_memtype=$row->review_memtype;
if (strlen($row->etctype)>0) {
	$etctemp = explode("",$row->etctype);
	$cnt = count($etctemp);
	$etcvalue="";
	for ($i=0;$i<$cnt;$i++) {
		if (substr($etctemp[$i],0,11)=="REVIEWLIST=") $reviewlist=substr($etctemp[$i],11);	#��ǰ���� ���÷��̹��
		else if (substr($etctemp[$i],0,7)=="REVIEW=") $leftreview=substr($etctemp[$i],7);	#��ǰ���� ���ʸ޴� 
		else if (substr($etctemp[$i],0,11)=="REVIEWDATE=") $review_date=substr($etctemp[$i],11);	#��ǰ���� ��ϳ�¥ ǥ�ÿ���
		else if(strlen($etctemp[$i])>0) $etcvalue.=$etctemp[$i]."";
	}
}
if(strlen($reviewlist)==0) $reviewlist="N";
if(strlen($leftreview)==0) $leftreview="N";
if(strlen($review_date)==0) $review_date="Y";
$tmp_filter=explode("#",$row->filter);
$product_filter=$tmp_filter[0];
$filter_array=explode("REVIEWROW",$tmp_filter[1]);
$review_filter=$filter_array[0];
$reviewrow=$filter_array[1];

${"check_review_type".$review_type} = "checked";
${"check_reviewlist".$reviewlist} = "checked";
${"check_review_memtype".$review_memtype} = "checked";
${"check_review_date".$review_date} = "checked";
${"check_leftreview".$leftreview} = "checked";
?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function CheckForm() {
	document.form1.type.value="up";
	document.form1.submit();
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
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �������� &gt; ���θ� � ���� &gt; <span class="2depth_select">��ǰ����(�ı�) ����</span></td>
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
				<td height="8"></td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/shop_review_title.gif"  ALT=""></TD>
					</tr>
<tr>
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
					<TD background="images/distribute_04.gif"></TD>
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
					<TD width="100%" class="notice_blue"><p>��ǰ����ı��� ��뿩��, �Խù��, �ۼ������� ������ �� �ֽ��ϴ�.</p></TD>
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
				<td height="20"></td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/shop_review_stitle1.gif" WIDTH="187" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height=3></td>
			</tr>
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
					<TD width="100%" class="notice_blue">1) ������ ������ ��� : <a href="javascript:parent.topframe.GoMenu(4,'product_review.php');"><b>��ǰ���� > ����ǰ/����/��Ÿ���� > ��ǰ ���� ����</b></a>���� ���� �� �� �ֽ��ϴ�.<br>2) ���信 ���� �亯/����/���� ������ �Ҽ� �ֽ��ϴ�.</span></b></TD>
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
				<td height=3></td>
			</tr>
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=type>
			<input type=hidden name=up_val value="<?=$etcvalue?>">
			<input type=hidden name=up_product_filter value="<?=$product_filter?>">
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">��ǰ���� ��뿩��</TD>
					<TD class="td_con1">
						<input type=radio id="idx_review_type2" name=up_review_type value="Y" <?=$check_review_typeY?>> <label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_review_type2>���</label>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=radio id="idx_review_type1" name=up_review_type value="N" <?=$check_review_typeN?>> <label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_review_type1>������</label>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=radio id="idx_review_type3" name=up_review_type value="A" <?=$check_review_typeA?>> <label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_review_type3>������ ���� �� ���</label>
					</TD>
				</TR>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height="30">&nbsp;</td>
			</tr>
			<tr>
				<td style="padding-bottom:3px;">
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/shop_review_stitle2.gif" WIDTH="187" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height=3></td>
			</tr>
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
					<TD width="100%" class="notice_blue">1) ��ǰ�󼼺��� ������ ���� �Ǵ� �˾����� ���÷��̸� �� �� �ֽ��ϴ�.<br>
					2) <a href="javascript:parent.topframe.GoMenu(2,'design_eachreviewpopup.php');"><b>�����ΰ��� > ����������-������ ���� > ��ǰ���� ����â �ٹ̱�</b></a>���� �˾����� ���������� �����մϴ�.</span></b></TD>
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
				<td height=3></td>
			</tr>
			<tr>
				<td>
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td bgcolor="#EDEDED" style="padding:4pt;">
					<table cellpadding="0" cellspacing="0" width="100%" bgcolor="white">
					<tr>
						<td>
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
						<TR>
							<TD height="35" background="images/blueline_bg.gif"><p align="center"><input type=radio id="idx_reviewlist1" name=up_reviewlist value="Y" checked<?//=$check_reviewlistY?>> <label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_reviewlist1><span class="font_blue"><b>��ǰ�������� ������ ���(����)</span></label></TD>
							<TD height="35" background="images/blueline_bg.gif"><p align="center"><input type=radio id="idx_reviewlist2" name=up_reviewlist value="N" <?//=$check_reviewlistN?>> <label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_reviewlist2><span class="font_blue"><b>�˾����� ���</b></span></label></TD>
						</TR>
						<TR>
							<TD colspan="2" background="images/table_con_line.gif"></TD>
						</TR>
						<TR>
							<TD align=center style="padding-top:10pt; padding-bottom:10pt;"><img src="images/review_img001.gif" border="0" class="imgline"></TD>
							<TD align=center class="td_con1"><img src="images/review_img002.gif" border="0" class="imgline"></TD>
						</TR>
						</TABLE>
						</td>
					</tr>
					</table>
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
				<span class="font_orange"><b>* ȸ������ ���ý� ȸ���� ���並 �ۼ��� �� �ֽ��ϴ�.</b></span><br>
				
				</td>
			</tr>






			<tr>
				<td height="10"></td>
			</tr>
			<tr>
				<td><IMG SRC="images/shop_review_stitle3.gif" border="0"></td>
			</tr>
			<tr>
				<td height="5"></td>
			</tr>
			<tr>
				<td>
					<table cellpadding="0" cellspacing="0" width="100%">
						<TR>
							<TD bgcolor="#B9B9B9" height="1"></TD>
						</TR>
						<tr>
							<td>
								<table cellpadding="0" cellspacing="0" width="100%">
								<col width="150"></col>
								<col width=""></col>
									<TR>
										<TD class="table_cell"><b><img src="images/icon_point2.gif" border="0"></b>ȸ���� ���� ���뿩��</TD>
										<TD class="td_con1"><input type=radio id="idx_review_memtype2" name=up_review_memtype value="Y" <?=$check_review_memtypeY?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_review_memtype2>ȸ�� ����</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=radio id="idx_review_memtype1" name=up_review_memtype value="N" <?=$check_review_memtypeN?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_review_memtype1>������ �ۼ�(ȸ��+��ȸ��)</label>
										</TD>
									</TR>
								</table>
							</td>
						</tr>
						<TR>
							<TD bgcolor="#B9B9B9" height="1"></TD>
						</TR>
					</table>


				</td>
			</tr>
			<tr><td height="30"></td></tr>













			<tr>
				<td height="30"></td>
			</tr>
			<tr>
				<td><IMG SRC="images/shop_review_stitle4.gif" border="0"></td>
			</tr>
			<tr>
				<td height="5"></td>
			</tr>
			<tr>
				<td>
					<table cellpadding="0" cellspacing="0" width="100%">
						<TR>
							<TD bgcolor="#B9B9B9" height="1"></TD>
						</TR>
						<tr>
							<td>
								<table cellpadding="0" cellspacing="0" width="100%">
								<col width="150"></col>
								<col width=""></col>
									<TR>
										<TD class="table_cell"><b><img src="images/icon_point2.gif" border="0"></b>���� ��ϳ�¥ ǥ�ÿ���</TD>
										<TD class="td_con1"><input type=radio id="idx_review_date2" name=up_review_date value="Y" <?=$check_review_dateY?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_review_date2>���� ��� ��¥ ǥ��</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=radio id="idx_review_date1" name=up_review_date value="N" <?=$check_review_dateN?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_review_date1>�����ϳ�¥ ��ǥ��</label>
										</TD>
									</TR>
								</table>
							</td>
						</tr>
						<TR>
							<TD bgcolor="#B9B9B9" height="1"></TD>
						</TR>
					</table>


				</td>
			</tr>
			<tr><td height="30"></td></tr>






			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/shop_review_stitle5.gif" WIDTH="187" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height=3></td>
			</tr>
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
					<TD width="100%" class="notice_blue">���� �ۼ��� ����� �� ���� �ܾ�(�޸�","�� �����Ͽ� �Է�)</span></b></TD>
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
				<td height=3></td>
			</tr>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif" width="153"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">���͸� �ܾ� �Է�</TD>
					<TD class="td_con1"><input type=text name=up_review_filter value="<?=$review_filter?>" size=100% class="input"></TD>
				</TR>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height="30"></td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/shop_review_stitle6.gif" WIDTH="187" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height=3></td>
			</tr>
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
					<TD width="100%" class="notice_blue">1) ��ü���� ��� : ���θ����ʸ޴��� <b>[����ı� ����]</b>, ��ǰ�������� ������ <b>[��ü����]</b> �޴��� �����˴ϴ�.<br>2) ��ü���⿡���� �ֱ� 100���� ����ı⸦ �����ݴϴ�.</TD>
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
				<td height=3></td>
			</tr>
			<tr>
				<td height=10>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">��뿩�� ����</TD>
					<TD class="td_con1">
					<TABLE cellSpacing=0 cellPadding=0 border=0>
					<TR>
						<TD><input type=radio id="idx_leftreview1" name=up_leftreview value="Y" <?=$check_leftreviewY?>> <label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_leftreview1><b>[����ı� ����]</b>, <b>[��ü����]</b> ���</label>(�� �������� ǥ�� �� : <select name=up_reviewrow class="select" style=width:100px>
<?
	for ($i=8 ; $i <= 20 ; $i++ ) {
		echo "<option value='".$i."' ";
		if ($i==$reviewrow) echo " selected";
		echo ">".$i."</option>\n";
	}
?>
							</select>)</TD>
					</TR>
					<TR>
						<TD><input type=radio id="idx_leftreview2" name=up_leftreview value="N" <?=$check_leftreviewN?>> <label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_leftreview2>�̻��</label></TD>
					</TR>
					</TABLE>
					</TD>
				</TR>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height=10></td>
			</tr>
			<tr>
				<td align="center"><a href="javascript:CheckForm();"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a></td>
			</tr>
			</form>
			<tr>
				<td height=20></td>
			</tr>
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
						<td><span class="font_dotline">��ϵ� ������� �޴�</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td class="space_top"><a href="javascript:parent.topframe.GoMenu(4,'product_review.php');"><span class="font_blue">��ǰ���� > ����ǰ/����/��Ÿ���� > ��ǰ ���� ����</span></a></td>
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
			<tr>
				<td height="50"></td>
			</tr>
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