<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "ma-3";
$MenuCode = "market";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

//����Ʈ ����
$setup[page_num] = 10;
$setup[list_num] = 20;

$block=$_REQUEST["block"];
$gotopage=$_REQUEST["gotopage"];
$search=$_POST["search"];

if ($block != "") {
	$nowblock = $block;
	$curpage  = $block * $setup[page_num] + $gotopage;
} else {
	$nowblock = 0;
}

if (($gotopage == "") || ($gotopage == 0)) {
	$gotopage = 1;
}

////////////////////////

$type=$_POST["type"];
$coupon_code=$_POST["coupon_code"];
$uid=$_POST["uid"];
$cp_display=$_POST["cp_display"];

$imagepath=$Dir.DataDir."shopimages/etc/";

if($type=="change" && strlen($coupon_code)>0) {	
	$sql = "UPDATE tblpapercoupon SET display='".$cp_display."' WHERE coupon_code = '".$coupon_code."' ";
	mysql_query($sql,get_db_conn());
	if(!mysql_errno()) $onload="<script>alert('�ش� ������ ���°� ����Ǿ����ϴ�.');</script>";
	unset($coupon_code);
}else if($type=="delete" && strlen($coupon_code)>0) {	//��������
	$sql = "DELETE FROM tblpapercoupon WHERE coupon_code = '".$coupon_code."' ";
	mysql_query($sql,get_db_conn());
	$sql = "DELETE FROM tblpapercoupon_code WHERE coupon_code = '".$coupon_code."' ";
	mysql_query($sql,get_db_conn());

	if(file_exists($imagepath."PAPER_CP".$coupon_code.".gif")) {
		unlink($imagepath."PAPER_CP".$coupon_code.".gif");
	}

	if(!mysql_errno()) $onload="<script>alert('�ش� ������ ��� ������ ���� �����Ǿ����ϴ�.');</script>";
	unset($coupon_code);
}
?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function GoPage(block,gotopage) {
	document.form1.type.value = "";
	document.form1.coupon_code.value = "";
	document.form1.uid.value = "";
	document.form1.block.value = block;
	document.form1.gotopage.value = gotopage;
	document.form1.submit();
}

function id_search() {
	document.form1.type.value='';
	document.form1.uid.value='';
	document.form1.submit();
}

function search_default() {
	document.form1.type.value='';
	document.form1.uid.value='';
	document.form1.search.value='';
	document.form1.submit();
}

function CouponStop(code,val) {
	if(val == "C"){
		if(!confirm("���� ȸ������ �߱޵� ������ ����� �����մϴ�.\n\n�ش� ���� �߱��� �����Ͻðڽ��ϱ�?")) {
			return false;
		}
	}
	document.form1.coupon_code.value=code;
	document.form1.cp_display.value=val;
	document.form1.type.value="change";
	document.form1.submit();
}

function CouponDelete(code) {
	if(confirm("���� �߱޵� ������������ ��� �����˴ϴ�.\n\n�ش� ������ ���� �����Ͻðڽ��ϱ�?")) {
		document.form1.coupon_code.value=code;
		document.form1.type.value="delete";
		document.form1.submit();
	}
}

function CouponView(code) {
	document.form1.coupon_code.value=code;
	document.form1.type.value="view";
	document.form1.action="market_papercoupon_add.php";
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
			<? include ("menu_market.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ���������� &gt; �������� ���� ���� &gt; <span class="2depth_select">���������� �������</span></td>
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
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=type>
			<input type=hidden name=coupon_code value="<?=$coupon_code?>">
			<input type=hidden name=uid>
			<input type=hidden name=block value="<?=$block?>">
			<input type=hidden name=gotopage value="<?=$gotopage?>">
			<input type=hidden name=cp_display value="">
			<tr><td height="8"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/market_couponlist_title.gif" ALT=""></TD>
					</tr><tr>
					<TD width="100%" background="images/title_bg.gif" height="21"></TD>
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
					<TD width="100%" class="notice_blue">���� �������� ���������� ������ Ȯ���� �� �ִ� �޴� �Դϴ�.</TD>
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
					<TD><IMG SRC="images/market_couponlist_stitle1.gif" WIDTH="192" HEIGHT=31 ALT=""></TD>
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
				<col width=30></col>
				<col width=75></col>
				<col width=></col>
				<col width=130></col>
				<col width=65></col>
				<col width=105></col>
				<col width=80></col>
				<col width=55></col>
				<TR>
					<TD colspan=8 background="images/table_top_line.gif"></TD>
				</TR>
				<TR align=center>
					<TD class="table_cell">No</TD>
					<TD class="table_cell1">�ڵ�</TD>
					<TD class="table_cell1">������</TD>
					<TD class="table_cell1">����������ȣ</TD>
					<TD class="table_cell1">�����ݾ�</TD>
					<TD class="table_cell1">��ȿ�Ⱓ</TD>
					<TD class="table_cell1">��������</TD>
					<TD class="table_cell1"><b><font color="red">��������</font></b></TD>
				</TR>
				<TR>
					<TD colspan="8" background="images/table_con_line.gif"></TD>
				</TR>
<?
				$sql = "SELECT COUNT(*) as t_count FROM tblpapercoupon ";
				$result = mysql_query($sql,get_db_conn());
				$row = mysql_fetch_object($result);
				mysql_free_result($result);
				$t_count = $row->t_count;
				$pagecount = (($t_count - 1) / $setup[list_num]) + 1;

				$sql = "SELECT A.*, ";
				$sql.= "(SELECT coupon_number FROM tblpapercoupon_code B WHERE A.coupon_code = B.coupon_code ) coupon_number ";
				$sql.= "FROM tblpapercoupon A ";
				$sql.= "ORDER BY date DESC LIMIT " . ($setup[list_num] * ($gotopage - 1)) . ", " . $setup[list_num];
				$result = mysql_query($sql,get_db_conn());
				$cnt=0;
				while($row=mysql_fetch_object($result)) {
					$number = ($t_count-($setup[list_num] * ($gotopage-1))-$cnt);
					$cnt++;

					if($coupon_code==$row->coupon_code) {
						$coupon_name=$row->coupon_name;
					}

					if($row->sale_type == "A") $dan="��";
					else $dan = "%";
					$sale = "����";
					if($row->date_start>0) {
						$date = substr($row->date_start,2,2).".".substr($row->date_start,4,2).".".substr($row->date_start,6,2)."~".substr($row->date_end,2,2).".".substr($row->date_end,4,2).".".substr($row->date_end,6,2);
					} else {
						$date = abs($row->date_start)."�ϵ���";
					}

					$coupon_number = substr($row->coupon_number,0,4)."-".substr($row->coupon_number,4,4)."-".substr($row->coupon_number,8,4)."-".substr($row->coupon_number,12,4);

					echo "<TR align=center>\n";
					echo "	<TD class=\"td_con2\">".$number."</TD>\n";
					echo "	<TD class=\"td_con1\"><A HREF=\"javascript:CouponView('".$row->coupon_code."');\"><B>".$row->coupon_code."</B></A></TD>\n";
					echo "	<TD align=left class=\"td_con1\">".$row->coupon_name."</TD>\n";
					echo "	<TD class=\"td_con1\">".$coupon_number."</TD>\n";
					echo "	<TD class=\"td_con1\"><span class=\"".($sale=="����"?"font_orange":"font_blue")."\"><b><NOBR>".number_format($row->sale_money).$dan." ".$sale."<NOBR></b></span></TD>\n";
					echo "	<TD class=\"td_con1\"><NOBR>".$date."</NOBR></TD>\n";
					echo "	<TD class=\"td_con1\">\n";
					echo "		<select name=\"display\" onchange=\"CouponStop('".$row->coupon_code."',this.value)\">\n";
					echo "		<option value=\"Y\" ".(($row->display =="Y")?"selected":"").">�߱ް���</option>\n";
					echo "		<option value=\"C\" ".(($row->display =="C")?"selected":"").">�߱�����</option>\n";
					echo "		<option value=\"N\" ".(($row->display =="N")?"selected":"").">������</option>\n";
					echo "		</select>\n";
					echo "</TD>\n";
					echo "	<TD class=\"td_con1\"><a href=\"javascript:CouponDelete('".$row->coupon_code."');\"><img src=\"images/btn_del7.gif\" border=\"0\"></a></TD>\n";
					echo "</TR>\n";
					echo "<TR>\n";
					echo "	<TD colspan=\"8\" background=\"images/table_con_line.gif\"></TD>\n";
					echo "</TR>\n";
				}
				mysql_free_result($result);
				if ($cnt==0) {
					echo "<tr><td class=td_con2 colspan=8 align=center>�߱��� ���������� �����ϴ�.</td></tr>";
				}
?>
				<TR>
					<TD colspan=8 background="images/table_top_line.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=10></td></tr>
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
			<tr><td align="right"><a href="market_papercoupon_add.php"><img src="images/btn_cupon.gif" width="139" height="38" border="0"></a></td></tr>
			<tr><td height="30"></td></tr>
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
						<td><span class="font_dotline">�߱޵� ���� ��������</span></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- �����ڵ� Ŭ���� �ش� ������ ���� �ڼ��� ������ Ȯ���� �� �ֽ��ϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- [��ȸ] ��ư Ŭ���� �ش� ������ �߱޹��� ȸ���� Ȯ���� �� �ֽ��ϴ�.<br>
						<b>&nbsp;&nbsp;</b>�߱޹��� ȸ���������� [��߱�] ��ư Ŭ���� �ش� ������ ��߱� �˴ϴ�.<br>
						<b>&nbsp;&nbsp;</b>�߱޹��� ȸ���������� [����] ��ư Ŭ���� �ش� ������ ���� �˴ϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- [�߱�����] ��ư Ŭ���� �ش� ���� �߱��� �����մϴ�. ��, <span class="font_blue">�߱����� ���� �̹� �߱޵� ������ ��� �����մϴ�.</span></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- [��������] ��ư Ŭ���� �ش� ���� �߱��� �����ϸ� ���� <span class="font_orange">�������� ���� �̹� �߱޵� ������ �Բ� �����˴ϴ�.</span></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- <span class="font_orange">��ȿ�Ⱓ�� ���� ������ ��� [��������]�� ���� ����</span>�� ���ֽñ� �ٶ��ϴ�.</td>
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

			<form name=cform action="coupon_view.php" method=post target=couponview>
			<input type=hidden name=coupon_code>
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