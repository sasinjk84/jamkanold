<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

########################### TEST ���θ� Ȯ�� ##########################
DemoShopCheck("������������� ������ �Ұ��� �մϴ�.", "history.go(-1)");
#######################################################################

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
$tax_scd=$_shopdata->tax_scd;

$tax_cnum1=substr($tax_cnum,0,3);
$tax_cnum2=substr($tax_cnum,3,2);
$tax_cnum3=substr($tax_cnum,5,5);

if(strlen($tax_cnum)==0) {
	echo "<html></head><body onload=\"alert('���ݿ����� ȯ�漳�� �� �̿��Ͻñ� �ٶ��ϴ�.');location.href='order_taxsaveabout.php';\"></body></html>";exit;
}


//����Ʈ ����
$setup[page_num] = 10;
$setup[list_num] = 20;

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

$CurrentTime = time();
$period[0] = date("Y-m-d",$CurrentTime);
$period[1] = date("Y-m-d",$CurrentTime-(60*60*24*3));
$period[2] = date("Y-m-d",$CurrentTime-(60*60*24*7));

$search_start=$_POST["search_start"];
$search_end=$_POST["search_end"];
$vperiod=(int)$_POST["vperiod"];

$search_start=$search_start?$search_start:$period[0];
$search_end=$search_end?$search_end:date("Y-m-d",$CurrentTime);
$search_s=$search_start?str_replace("-","",$search_start."000000"):str_replace("-","",$period[0]."000000");
$search_e=$search_end?str_replace("-","",$search_end."235959"):date("Ymd",$CurrentTime)."235959";

${"check_vperiod".$vperiod} = "checked";

$tempstart = explode("-",$search_start);
$tempend = explode("-",$search_end);
$termday = (mktime(0,0,0,$tempend[1],$tempend[2],$tempend[0])-mktime(0,0,0,$tempstart[1],$tempstart[2],$tempstart[0]))/86400;
if ($termday>366) {
	echo "<html></head><body onload=\"alert('�˻��Ⱓ�� 1���� �ʰ��� �� �����ϴ�.');location='".$_SERVER[PHP_SELF]."';\"></body></html>";exit;
}

$mode=$_POST["mode"];
$flag=$_POST["flag"];
$ordercode=$_POST["ordercode"];

if($mode=="OK" && preg_match("/^(C|Y)$/",$flag)) {
	include ($Dir."lib/taxsave.inc.new.php");
	if(strlen($msg)>0) $onload="<script>alert('".$msg."');</script>";
}

$type=$_POST["type"];

?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script type="text/javascript" src="calendar.js.php"></script>
<script language="JavaScript">
function CheckForm() {
	document.form1.mode.value="";
	document.form1.flag.value="";
	document.form1.ordercode.value="";
	document.form1.block.value="";
	document.form1.gotopage.value="";
	document.form1.submit();
}

function process(flag,ordercode) {
	msg="";
	if(flag=="Y") msg="�ش� ��û�ǿ� ���ؼ� ���ݿ��ֽ� �߱��� �Ͻðڽ��ϱ�?";
	else if(flag=="C") msg="�ش� �߱޵� ���ݿ������� ����Ͻðڽ��ϱ�?";
	if(confirm(msg)) {
		document.form1.mode.value="OK";
		document.form1.flag.value=flag;
		document.form1.ordercode.value=ordercode;
		document.form1.submit();
	}
}

function taxsaveview(ordercode) {
	window.open("order_taxsaveviewpop.php?ordercode="+ordercode,"kcptaxsaveview","scrollbars=no,width=700,height=600");
}

function OrderDetailView(ordercode) {
	document.detailform.ordercode.value = ordercode;
	window.open("","orderdetail","scrollbars=yes,width=700,height=600");
	document.detailform.submit();
}

function TaxsaveExcel() {
	document.form1.action="order_taxsave_excel.php";
	document.form1.submit();
	document.form1.action="";
}


function OnChangePeriod(val) {
	var pForm = document.form1;
	var period = new Array(7);
	period[0] = "<?=$period[0]?>";
	period[1] = "<?=$period[1]?>";
	period[2] = "<?=$period[2]?>";

	pForm.search_start.value = period[val];
	pForm.search_end.value = period[0];
}

function GoPage(block,gotopage) {
	document.idxform.block.value = block;
	document.idxform.gotopage.value = gotopage;
	document.idxform.submit();
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
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" width="16" height="12" border="0" valign=absmiddle>������ġ : �ֹ�/���� &gt; ���ݿ����� ���� &gt; <span class="2depth_select">���ݿ����� �߱�/��ȸ</span></td>
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
					<TD><IMG SRC="images/order_taxsavelist_title.gif"  ALT=""></TD>
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
					<TD width="100%" class="notice_blue"><p>���ݿ����� �߱޽�û ��ȸ �� �߱޳��� Ȯ���� �����մϴ�.</p></TD>
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
					<TD><IMG SRC="images/order_taxsavelist_stitle.gif" border="0"></TD>
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
			<input type=hidden name=flag>
			<input type=hidden name=ordercode>
			<input type=hidden name=block value="<?=$block?>">
			<input type=hidden name=gotopage value="<?=$gotopage?>">
			<tr>
				<td>
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td bgcolor="#0099CC" style="padding:6pt;">
					<table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="white">
					<tr>
						<td width="100%">
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
						<TR>
							<TD class="table_cell" width="150"><img src="images/icon_point5.gif" width="8" height="11" border="0">�Ⱓ ����</TD>
							<TD class="td_con1"><input type=text name=search_start value="<?=$search_start?>" size=15 onfocus="this.blur();" OnClick="Calendar(this)" class="input_selected"> ~ <input type=text name=search_end value="<?=$search_end?>" size=15 onfocus="this.blur();" OnClick="Calendar(this)" class="input_selected"> <input type=radio id=idx_vperiod0 name=vperiod value="0" checked style="BORDER-RIGHT: 0px; BORDER-TOP: 0px; BORDER-LEFT: 0px; BORDER-BOTTOM: 0px" onclick="OnChangePeriod(this.value)" <?=$check_vperiod0?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_vperiod0>����</label> <input type=radio id=idx_vperiod1 name=vperiod value="1" style="BORDER-RIGHT: 0px; BORDER-TOP: 0px; BORDER-LEFT: 0px; BORDER-BOTTOM: 0px" onclick="OnChangePeriod(this.value)" <?=$check_vperiod1?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_vperiod1>3��</label> <input type=radio id=idx_vperiod2 name=vperiod value="2" style="BORDER-RIGHT: 0px; BORDER-TOP: 0px; BORDER-LEFT: 0px; BORDER-BOTTOM: 0px" onclick="OnChangePeriod(this.value)" <?=$check_vperiod2?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_vperiod2>1����</label></TD>
						</TR>
						<TR>
							<TD colspan="2" background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></TD>
						</TR>
						<TR>
							<TD class="table_cell" width="150"><img src="images/icon_point5.gif" width="8" height="11" border="0">ó���ܰ�</TD>
							<TD class="td_con1"><input type=radio id="idx_type0" name=type value="" <?if(strlen($type)==0)echo"checked";?> style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_type0>��ü����</label> <input type=radio id="idx_type1" name=type value="N" <?if($type=="N")echo "checked";?> style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_type1>�߱޿�û</label> <input type=radio id="idx_type2" name=type value="Y" <?if($type=="Y")echo "checked";?> style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_type2>�߱޿Ϸ�</label> <input type=radio id="idx_type3" name=type value="C" <?if($type=="C")echo "checked";?> style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_type3>��ҿϷ�</label></TD>
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
				<td height=10></td>
			</tr>
			<tr>
				<td align="center"><p align="center"><a href="javascript:CheckForm();"><img src="images/botteon_search.gif" width="113" height="38" border="0"></a>&nbsp;<a href="javascript:TaxsaveExcel();"><img src="images/btn_excel1.gif" width="127" height="38" border="0" hspace="1"></a></td>
			</tr>
			<tr>
				<td height=20></td>
			</tr>
			<tr>
				<td align="center">
				<table cellpadding="0" cellspacing="0" width="100%">
<?
				if(substr($search_s,0,8)==substr($search_e,0,8)) {
					$qry.= "WHERE tsdtime LIKE '".substr($search_s,0,8)."%' ";
				} else {
					$qry.= "WHERE tsdtime>='".$search_s."' AND tsdtime <='".$search_e."' ";
				}
				if(strlen($type)>0)	$qry.= "AND type='".$type."' ";

				$sql = "SELECT COUNT(*) as t_count, SUM(amt1) as t_price FROM tbltaxsavelist ".$qry;
				$result = mysql_query($sql,get_db_conn());
				$row = mysql_fetch_object($result);
				$t_count = (int)$row->t_count;
				$t_price = (int)$row->t_price;
				mysql_free_result($result);
				$pagecount = (($t_count - 1) / $setup[list_num]) + 1;

				$sql = "SELECT * FROM tbltaxsavelist ".$qry." ";
				$sql.= "ORDER BY tsdtime DESC ";
				$sql.= "LIMIT " . ($setup[list_num] * ($gotopage - 1)) . ", " . $setup[list_num];
				$result = mysql_query($sql,get_db_conn());
?>
				<tr>
					<td width="100%">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="372"><p align="left">&nbsp;</td>
						<td width="372"><p align="right"><img src="images/icon_8a.gif" width="13" height="13" border="0">�� �Ǽ� : <B><?=number_format($t_count)?></B>��&nbsp;&nbsp;<img src="images/icon_8a.gif" width="13" height="13" border="0">�հ�ݾ� : <B><?=number_format($t_price)?></B>��&nbsp; <img src="images/icon_8a.gif" width="13" height="13" border="0">���� <b><?=$gotopage?>/<?=ceil($t_count/$setup[list_num])?></b> ������</td>
					</tr>
					</table>
					</td>
				</tr>
				<tr>
					<td height=3></td>
				</tr>
				<tr>
					<td width="100%">
					<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
					<TR>
						<TD background="images/table_top_line.gif" width="761" colspan="8"></TD>
					</TR>
					<TR>
						<TD class="table_cell"><p align="center">No</TD>
						<TD class="table_cell1"><p align="center">ó������</TD>
						<TD class="table_cell1"><p align="center">�ֹ�����</TD>
						<TD class="table_cell1"><p align="center">�ֹ���</TD>
						<TD class="table_cell1"><p align="center">�ݾ�</TD>
						<TD class="table_cell1"><p align="center">ó��</TD>
						<TD class="table_cell1"><p align="center">����</TD>
						<TD class="table_cell1"><p align="center">���</TD>
					</TR>
					<TR>
						<TD colspan="8" width="760" background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></TD>
					</TR>
<?
					unset($arrtax);
					unset($arrorder);
					unset($ordercode);
					$cnt=0;
					while($row=mysql_fetch_object($result)) {
						$number = ($t_count-($setup[list_num] * ($gotopage-1))-$cnt);
						$arrtax[$cnt]=$row;
						$arrtax[$cnt]->number=$number;
						$ordercode.=",'".$row->ordercode."'";
						$cnt++;
					}
					mysql_free_result($result);

					if ($cnt==0) {
						echo "<tr><td class=\"td_con2\" colspan=\"8\" align=\"center\">�˻��� ������ �����ϴ�.</td></tr>";
					} else {
						$ordercode=substr($ordercode,1);
						$sql = "SELECT ordercode, sender_name, bank_date, deli_gbn FROM tblorderinfo ";
						$sql.= "WHERE ordercode IN (".$ordercode.") ";
						$result=mysql_query($sql,get_db_conn());
						while($row=mysql_fetch_object($result)) {
							$arrorder[$row->ordercode]=$row;
						}
						mysql_free_result($result);

						for($i=0;$i<count($arrtax);$i++) {
							$tsdtime=$arrtax[$i]->tsdtime;
							$tsdtime=substr($tsdtime,0,4)."/".substr($tsdtime,4,2)."/".substr($tsdtime,6,2)." (".substr($tsdtime,8,2).":".substr($tsdtime,10,2).")";
							$orderdate=$arrtax[$i]->ordercode;
							$orderdate=substr($orderdate,0,4)."/".substr($orderdate,4,2)."/".substr($orderdate,6,2)." (".substr($orderdate,8,2).":".substr($orderdate,10,2).")";

							echo "<tr>\n";
							echo "	<TD class=\"td_con2\"><p align=\"center\">";
							if(strlen($arrorder[$arrtax[$i]->ordercode]->deli_gbn)==0) echo $arrtax[$i]->number;
							else {
								echo "<A HREF=\"javascript:OrderDetailView('".$arrtax[$i]->ordercode."')\"><U>".$arrtax[$i]->number."</U></A>";
							}
							echo "	</td>\n";
							echo "	<TD class=\"td_con1\"><p align=\"center\">";
							if($arrtax[$i]->type=="Y" || $arrtax[$i]->type=="C") {
								echo "<A HREF=\"javascript:taxsaveview('".$arrtax[$i]->ordercode."')\"><U>".$tsdtime."</U></A>";
							} else {
								echo $tsdtime;
							}
							echo "	</td>\n";
							echo "	<TD class=\"td_con1\"><p align=\"center\">".$orderdate."</td>\n";
							echo "	<TD class=\"td_con1\"><p align=\"center\">".$arrtax[$i]->name."</p></td>\n";
							echo "	<TD class=\"td_con1\" style=\"padding-right:10px;\"><p align=\"center\"><span class=\"font_orange\"><b>".number_format($arrtax[$i]->amt1)."��</b></span></TD>\n";
							echo "	<TD class=\"td_con1\"><p align=\"center\">";
							if(strlen($arrorder[$arrtax[$i]->ordercode]->deli_gbn)==0) {
								echo "�����߱�";
							} else {
								if(strlen($arrorder[$arrtax[$i]->ordercode]->bank_date)==14) echo "<font color=red>�Ա�</font>";
								else if (strlen($arrorder[$arrtax[$i]->ordercode]->bank_date)==9 && substr($arrorder[$arrtax[$i]->ordercode]->bank_date,8,1)=="X") echo "ȯ��";
								else echo "���Ա�";
								echo "/";
								if($arrorder[$arrtax[$i]->ordercode]->deli_gbn=="Y") echo "���";
								else if($arrorder[$arrtax[$i]->ordercode]->deli_gbn=="S") echo "�߼��غ�";
								else if($arrorder[$arrtax[$i]->ordercode]->deli_gbn=="C") echo "���";
								else if($arrorder[$arrtax[$i]->ordercode]->deli_gbn=="R") echo "�ݼ�";
								else echo "�̹��";
							}
							echo "	</td>\n";
							echo "	<TD class=\"td_con1\"><p align=\"center\">[";
							if(strlen($arrtax[$i]->error_msg)>0) echo "<a href=\"javascript:alert('���� ���� : ".$arrtax[$i]->error_msg."')\"><font color=red>";
							if($arrtax[$i]->type=="Y") echo "�߱޿Ϸ�";
							else if($arrtax[$i]->type=="C") echo "��ҿϷ�";
							else echo "�߱޿�û";
							if(strlen($arrtax[$i]->error_msg)>0) echo "</font></a>";
							echo "]	</p></td>";
							echo "	<TD class=\"td_con1\"><p align=\"center\">";
							if($arrtax[$i]->type=="Y") echo "<a href=\"javascript:process('C','".$arrtax[$i]->ordercode."')\"><img src=\"images/icon_cupon_cancel.gif\" border=0 align=absmiddle></a>";	//���
							else if($arrtax[$i]->type=="N") echo "<a href=\"javascript:process('Y','".$arrtax[$i]->ordercode."')\"><img src=\"images/icon_cupon_bal.gif\" border=0 align=absmiddle></a>";	//�߱�
							else echo "-"; 
							echo "	</p></td>\n";
							echo "</tr>\n";
							echo "<tr>\n";
							echo "	<TD colspan=\"8\" width=\"760\" background=\"images/table_con_line.gif\"><img src=\"images/table_con_line.gif\" width=\"4\" height=\"1\" border=\"0\"></TD>\n";
							echo "</tr>\n";
						}
					}
					echo "<tr><td colspan=8 height=1 bgcolor=".LineColor."></td></tr>\n";
?>
					<TR>
						<TD background="images/table_top_line.gif" width="761" colspan="8"></TD>
					</TR>
					</TABLE>
					</td>
				</tr>
				<tr>
					<td height=10></td>
				</tr>
				<tr>
					<td width="100%">
					<table cellpadding="0" cellspacing="0" width="100%">
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
					echo "<tr>\n";
					echo "	<td width=\"100%\" class=\"font_size\"><p align=\"center\">\n";
					echo "		".$a_div_prev_page.$a_prev_page.$print_page.$a_next_page.$a_div_next_page;
					echo "	</td>\n";
					echo "</tr>\n";
?>
					</table>
					</td>
				</tr>
				</table>
				</td>
			</tr>
			</form>
			<form name=idxform action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=block value="<?=$block?>">
			<input type=hidden name=gotopage value="<?=$gotopage?>">
			<input type=hidden name=search_start value="<?=$search_start?>">
			<input type=hidden name=search_end value="<?=$search_end?>">
			<input type=hidden name=vperiod value="<?=$vperiod?>">
			<input type=hidden name=type value="<?=$type?>">
			</form>

			<form name=detailform method="post" action="order_detail.php" target="orderdetail">
			<input type=hidden name=ordercode>
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
						<td width="701"><span class="font_dotline">���ݿ����� �߱�/��ȸ</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td width="701" class="space_top" style="letter-spacing:-0.5pt;"><p>- <b>�ڵ��߱�</b>���� ������ ��� �Ա�Ȯ�ν� �ڵ��߱� �Ǹ�, �ֹ����(��������)�� �ڵ���ҵ˴ϴ�.</p></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td width="701" class="space_top" style="letter-spacing:-0.5pt;"><p>- <b>�����߱�</b>���� ������ ��� [�߱�], [���]��ư�� ���� �����ֽø� �˴ϴ�.<br>
						<b>&nbsp;&nbsp;</b>ó�����°� �Աݻ����϶� �߱��� ���ֽð�, �ֹ���ҽÿ� ��ҹ�ư���� ���ó�� �Ͻø� �˴ϴ�.</p></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td width="701" class="space_top" style="letter-spacing:-0.5pt;"><p>- �߱޹�� ������ <a href="javascript:parent.topframe.GoMenu(5,'order_taxsaveconfig.php');"><span class="font_blue">�ֹ�/���� > ���ݿ����� ���� > ���ݿ����� ȯ�漳��</span></a> ���� ���� �����մϴ�.</p></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td width="701" class="space_top" style="letter-spacing:-0.5pt;"><p>- ó�����ڸ� Ŭ���Ͻø� �߱޵� ���³����� Ȯ���Ͻ� �� ������, <b>�߱� �� 1�� �Ŀ� Ȯ���� ����</b>�մϴ�.</p></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td width="701" class="space_top" style="letter-spacing:-0.5pt;"><p>- �߱�/��Ұ� �ݿ����� ���� ��� ���°� ���������� ������ �ش� ���� Ŭ���� ������ �� �� �ֽ��ϴ�.</p></td>
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
	</td>
</tr>
</table>
<?=$onload?>

<? INCLUDE "copyright.php"; ?>