<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "or-1";
$MenuCode = "order";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$curdate = date("YmdHi",mktime(date("H"),date("i")-10,0,date("m"),date("d"),date("Y")));//���� ���� �ð� term

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

$orderby=$_POST["orderby"];
if(strlen($orderby)==0) $orderby="ASC";

$type=$_POST["type"];
$ordercode=$_POST["ordercode"];

$CurrentTime = time();

$search_date=$_POST["search_date"];
$search_date=$search_date?$search_date:date("Y-m-d",$CurrentTime);
$search_date2=$search_date?str_replace("-","",$search_date."000000"):date("Ymd",$CurrentTime)."000000";

if($type=="restore" && strlen($ordercode)>=12) {	//����
	$sql = "SELECT * FROM tblorderinfotemp WHERE ordercode='".$ordercode."' ";
	$result=mysql_query($sql,get_db_conn());
	$data=mysql_fetch_object($result);
	mysql_free_result($result);
	if($data && strlen($data->del_gbn)==0 && substr($data->ordercode,0,12)<=$curdate) {
		$sql = "SELECT a.productcode,a.productname,a.opt1_name,a.opt2_name,a.quantity, ";
		$sql.= "b.option_quantity,b.option1,b.option2,a.package_idx,a.assemble_idx,a.assemble_info FROM tblorderproducttemp a, tblproduct b ";
		$sql.= "WHERE a.productcode=b.productcode AND a.ordercode='".$ordercode."' ";
		$result=mysql_query($sql,get_db_conn());
		$message="";
		while ($row=mysql_fetch_object($result)) {
			$tmpoptq="";
			if(strlen($artmpoptq[$row->productcode])>0)
				$optq=$artmpoptq[$row->productcode];
			else
				$optq=$row->option_quantity;

			if(strlen($optq)>51 && substr($row->opt1_name,0,5)!="[OPTG"){
				$tmpoptname1=explode(" : ",$row->opt1_name);
				$tmpoptname2=explode(" : ",$row->opt2_name);
				$tmpoption1=explode(",",$row->option1);
				$tmpoption2=explode(",",$row->option2);
				$cnt=1;
				$maxoptq = count($tmpoption1);
				while ($tmpoption1[$cnt]!=$tmpoptname1[1] && $cnt<$maxoptq) {
					$cnt++;
				}
				$opt_no1=$cnt;
				$cnt=1;
				$maxoptq2 = count($tmpoption2);
				while ($tmpoption2[$cnt]!=$tmpoptname2[1] && $cnt<$maxoptq2) {
					$cnt++;
				}
				$opt_no2=$cnt;
				$optioncnt = explode(",",substr($optq,1));
				if($optioncnt[($opt_no2-1)*10+($opt_no1-1)]!="") $optioncnt[($opt_no2-1)*10+($opt_no1-1)]+=$row->quantity;
				for($j=0;$j<5;$j++){
					for($i=0;$i<10;$i++){
						$tmpoptq.=",".$optioncnt[$j*10+$i];
					}
				}
				if(strlen($tmpoptq)>0 && $tmpoptq.","!=$optq){
					$artmpoptq[$row->productcode]=$tmpoptq;
					$tmpoptq=",option_quantity='".$tmpoptq.",'";
				}else{
					$tmpoptq="";
					$message .="[".$row->productname." - ".$row->opt1_name.$row->opt2_name."]\\n";
				}
			}
			$sql = "UPDATE tblproduct SET quantity=quantity+".$row->quantity.$tmpoptq." ";
			$sql.= "WHERE productcode='".$row->productcode."'";
			mysql_query($sql,get_db_conn());

			if(str_replace("","",str_replace(":","",str_replace("=","",$row->assemble_info)))) {
				$assemble_infoall_exp = explode("=",$row->assemble_info);

				if($row->package_idx>0 && strlen(str_replace("","",str_replace(":","",$assemble_infoall_exp[0])))>0) {
					$package_info_exp = explode(":",$assemble_infoall_exp[0]);
					if(strlen($package_info_exp[0])>0) {
						$package_productcode_exp = explode("",$package_info_exp[0]);
						for($k=0; $k<count($package_productcode_exp); $k++) {
							$sql2 = "UPDATE tblproduct SET ";
							$sql2.= "quantity		= quantity+".$row->quantity." ";
							$sql2.= "WHERE productcode='".$package_productcode_exp[$k]."' ";
							mysql_query($sql2,get_db_conn());
						}
					}
				}

				if($row->assemble_idx>0 && strlen(str_replace("","",str_replace(":","",$assemble_infoall_exp[1])))>0) {
					$assemble_info_exp = explode(":",$assemble_infoall_exp[1]);
					if(strlen($assemble_info_exp[0])>0) {
						$assemble_productcode_exp = explode("",$assemble_info_exp[0]);
						for($k=0; $k<count($assemble_productcode_exp); $k++) {
							$sql2 = "UPDATE tblproduct SET ";
							$sql2.= "quantity		= quantity+".$row->quantity." ";
							$sql2.= "WHERE productcode='".$assemble_productcode_exp[$k]."' ";
							mysql_query($sql2,get_db_conn());
						}
					}
				}
			}
		}
		mysql_free_result($result);

		$sql = "SELECT productcode FROM tblorderproducttemp ";
		$sql.= "WHERE ordercode='".$ordercode."' AND productcode LIKE 'COU%' ";
		$result=mysql_query($sql,get_db_conn());
		$rowcou=mysql_fetch_object($result);
		mysql_free_result($result);
		if($rowcou) {
			$coupon_code=substr($rowcou->productcode,3,-1);
			mysql_query("UPDATE tblcouponissue SET used='N' WHERE id='".$data->id."' AND coupon_code='".$coupon_code."'",get_db_conn());
		}
		if($data->reserve>0) {
			mysql_query("UPDATE tblmember SET reserve=reserve+".abs($data->reserve)." WHERE id='".$data->id."'",get_db_conn());
			$reserve_restore_sql = "INSERT tblreserve SET id = '".$data->id."', reserve = '".abs($data->reserve)."' , reserve_yn = 'Y', content='������ �������μ��� ���࿡ ���� ����', orderdata = '".$ordercode."=".$data->price."', date='".date('YmdHis')."' ";
			@mysql_query($reserve_restore_sql,get_db_conn());
		}
		mysql_query("UPDATE tblorderinfotemp SET del_gbn='R' WHERE ordercode='".$ordercode."'",get_db_conn());

		$onload="<script>alert(\"�ش� �ֹ��õ� �ֹ����� ����/������/���� ���� �����Ͽ����ϴ�.\");</script>";
	} else {
		$onload="<script>alert(\"������ �ֹ��õ� �ֹ����� �������� �ʽ��ϴ�.\");</script>";
	}
}
?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script type="text/javascript" src="calendar.js.php"></script>
<script language="JavaScript">

function OrderDetailView(ordercode) {
	document.detailform.ordercode.value = ordercode;
	window.open("","ordertempdetail","scrollbars=yes,width=700,height=600");
	document.detailform.submit();
}

function GoPage(block,gotopage) {
	document.idxform.block.value = block;
	document.idxform.gotopage.value = gotopage;
	document.idxform.submit();
}

function GoOrderby(orderby) {
	document.idxform.block.value = "";
	document.idxform.gotopage.value = "";
	document.idxform.orderby.value = orderby;
	document.idxform.submit();
}

function MemberView(id){
	parent.topframe.ChangeMenuImg(4);
	document.member_form.search.value=id;
	document.member_form.submit();
}

function OrderRestore(ordercode) {
	if(confirm("�ش� �ֹ��õ� �ֹ����� ����/������/���� ���� ���� �Ͻðڽ��ϱ�?")) {
		document.idxform.type.value="restore";
		document.idxform.ordercode.value=ordercode;
		document.idxform.submit();
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
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" width="16" height="12" border="0" valign=absmiddle>������ġ : �ֹ�/���� &gt; �ֹ���ȸ �� ��۰��� &gt; <span class="2depth_select">�����õ� �ֹ��� ����</span></td>
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
					<TD><IMG SRC="images/order_tempinfo_title.gif" border="0"></TD>
					</tr><tr>
					<TD width="100%" background="images/title_bg.gif" height=21></TD>
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
					<TD width="100%" class="notice_blue"><p>���θ������� �����õ� �ǿ� ���� ��Ȳ �� ������ �Ͻ� �� �ֽ��ϴ�.</p></TD>
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
					<TD><IMG SRC="images/order_list_stitle1.gif" WIDTH="187" HEIGHT=31 ALT=""></TD>
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
			<input type=hidden name=type>
			<tr>
				<td>
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td width="100%" bgcolor="#ededed" style="padding:4pt;">
					<table cellpadding="0" cellspacing="0" width="100%" bgcolor="white">
					<tr>
						<td width="100%">
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
						<TR>
							<TD class="table_cell" width="139"><img src="images/icon_point5.gif" width="8" height="11" border="0">��¥ ����</TD>
							<TD class="td_con1" >
							<table cellpadding="0" cellspacing="0" width="200">
							<tr>
								<td width="281"><input type=text name=search_date value="<?=$search_date?>" size=15 onfocus="this.blur();" OnClick="Calendar(this)" class="select_selected"></td>
								<td width="281"><a href="javascript:document.form1.submit();"><img src="images/btn_search2.gif" width="50" height="25" border="0" hspace="0"></a></td>
							</tr>
							</table>
							</TD>
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
			</form>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<form name=form2 action="<?=$_SERVER[PHP_SELF]?>" method=post>
<?
		$arpm=array("B"=>"������","V"=>"������ü","O"=>"�������","Q"=>"�������(�Ÿź�ȣ)","C"=>"�ſ�ī��",/*"P"=>"�ſ�ī��(�Ÿź�ȣ)",*/"M"=>"�ڵ���");

		$qry.= "WHERE ordercode LIKE '".substr($search_date2,0,8)."%' ";
		$qry.= "AND (pay_data='�ſ�ī�� ������' OR pay_data='�ǽð� ������ü ������' OR pay_data='') ";

		$sql = "SELECT COUNT(*) as t_count, SUM(price) as t_price FROM tblorderinfotemp ".$qry;
		$result = mysql_query($sql,get_db_conn());
		$row = mysql_fetch_object($result);
		$t_count = (int)$row->t_count;
		$t_price = (int)$row->t_price;
		mysql_free_result($result);
		$pagecount = (($t_count - 1) / $setup[list_num]) + 1;

		$sql = "SELECT * FROM tblorderinfotemp ".$qry." ";
		$sql.= "ORDER BY ordercode ".$orderby." ";
		$sql.= "LIMIT " . ($setup[list_num] * ($gotopage - 1)) . ", " . $setup[list_num];
		#echo $sql; exit;
		$result = mysql_query($sql,get_db_conn());
?>
			<tr>
				<td style="padding-bottom:3pt;">
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td ><p align="left"><img src="images/icon_8a.gif" width="13" height="13" border="0"><B>���� :
					<?if($orderby=="DESC"){?>
					<A HREF="javascript:GoOrderby('ASC');"><B><FONT class=font_orange>�ֹ����ڼ���</s></B></A>
					<?}else{?>
					<A HREF="javascript:GoOrderby('DESC');"><B><FONT class=font_orange>�ֹ����ڼ���</FONT></B></A>
					<?}?>
					</B></td>
					<td ><p align="right"><img src="images/icon_8a.gif" width="13" height="13" border="0">���� <b>1/0</b> ������</td>
				</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD background="images/table_top_line.gif" colspan="7">
					</TD>
				</TR>
				<TR>
					<TD class="table_cell"><p align="center">No</TD>
					<TD class="table_cell1"><p align="center">�ֹ��õ� ����</TD>
					<TD class="table_cell1"><p align="center">�ֹ��ڸ�</TD>
					<TD class="table_cell1"><p align="center">ID/�ֹ��õ���ȣ</TD>
					<TD class="table_cell1"><p align="center">�������</TD>
					<TD class="table_cell1"><p align="center">����</TD>
					<TD class="table_cell1"><p align="center">����/������/����</TD>
				</TR>
				<TR>
					<TD colspan="7"  background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></TD>
				</TR>
<?
		$cnt=0;
		while($row=mysql_fetch_object($result)) {
			$number = ($t_count-($setup[list_num] * ($gotopage-1))-$cnt);
			$cnt++;
			$ordercode=$row->ordercode;
			$name=$row->sender_name;
			if(substr($row->ordercode,20)=="X") {	//��ȸ��
				$strid = substr($row->id,1,6);
			} else {	//ȸ��
				$strid = "<A HREF=\"javascript:MemberView('".$row->id."');\"><FONT COLOR=\"blue\">".$row->id."</FONT></A>";
			}
			$date = substr($row->ordercode,0,4)."/".substr($row->ordercode,4,2)."/".substr($row->ordercode,6,2)." (".substr($row->ordercode,8,2).":".substr($row->ordercode,10,2).")";

			echo "<tr>\n";
			echo "	<TD class=\"td_con2\"><p align=\"center\"><A HREF=\"javascript:OrderDetailView('".$row->ordercode."');\">".$number."</A></td>\n";
			echo "	<TD class=\"td_con1\"><p align=\"center\">".$date."</td>\n";
			echo "	<TD class=\"td_con1\"><p align=\"center\">".$name."</p></td>\n";
			echo "	<TD class=\"td_con1\"><p align=\"center\"><span class=\"font_orange\"><b>".$strid."</b></span></td>\n";
			echo "	<TD class=\"td_con1\"><p align=\"center\"><b>".$arpm[substr($row->paymethod,0,1)]." ";
			if(preg_match("/^(B){1}/", $row->paymethod)) {	//������
				if (strlen($row->bank_date)==9 && substr($row->bank_date,8,1)=="X") echo "<font color=005000> [ȯ��]</font>";
				else if (strlen($row->bank_date)>0) echo " <font color=004000>[�ԱݿϷ�]</font>";
			} else if(preg_match("/^(V|M){1}/", $row->paymethod)) {	//������ü/�ڵ���
				if ($row->pay_flag=="0000") echo " <font color=0000a0>[�����Ϸ�]</font>";
				else echo " <font color=#757575>[��������]</font>";
			} else if(preg_match("/^(O|Q){1}/", $row->paymethod)) {	//�������
				if (strcmp($row->pay_flag,"0000")!=0) echo " <font color=#757575>[�ֹ�����]</font>";
				else if ($row->pay_flag=="0000" && $row->pay_admin_proc=="C") echo "<font color=005000> [��ҿϷ�]</font>";
				else if ($row->pay_flag=="0000" && strlen($row->bank_date)==0) echo "<font color=red> [���Ա�]</font>";
				else if ($row->pay_flag=="0000" && strlen($row->bank_date)>0) echo "<font color=0000a0> [�ԱݿϷ�]</font>";
			} else {
				if (strcmp($row->pay_flag,"0000")!=0) echo " <font color=#757575>[ī�����]</font>";
				else if ($row->pay_flag=="0000" && $row->pay_admin_proc=="N") echo "<font color=red> [ī�����]</font>";
				else if ($row->pay_flag=="0000" && $row->pay_admin_proc=="Y") echo "<font color=0000a0> [�����Ϸ�]</font>";
				else if ($row->pay_flag=="0000" && $row->pay_admin_proc=="C") echo "<font color=005000> [��ҿϷ�]</font>";
			}
			echo "	</b></td>\n";
			echo "	<TD class=\"td_con1\"><p align=\"center\"><b>".number_format($row->price)."&nbsp;</b></p></TD>\n";
			if(strlen($row->del_gbn)==0 && substr($row->ordercode,0,12)<=$curdate) {	//����
				echo "	<TD class=\"table_cell1\"><p align=\"center\"><A HREF=\"javascript:OrderRestore('".$row->ordercode."');\" style=\"color:blue\"><img src=\"images/orderrestore_go.gif\" width=\"74\" height=\"25\" border=\"0\"></A></td>\n";
			} else if(strlen($row->del_gbn)!=0) {	//�����Ϸ�
				echo "	<TD class=\"table_cell1\"><p align=\"center\"><img src=\"images/orderrestore_ok.gif\" width=\"74\" height=\"25\" border=\"0\"></td>\n";
			} else {
				echo "	<TD class=\"table_cell1\"><p align=\"center\">--</td>\n";
			}
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<TD colspan=\"7\"  background=\"images/table_con_line.gif\"><img src=\"images/table_con_line.gif\" width=\"4\" height=\"1\" border=\"0\"></TD>\n";
			echo "</tr>\n";
		}
		mysql_free_result($result);

		if ($cnt==0) {
			echo "<tr><td class=\"td_con2\" colspan=\"7\" align=\"center\">�˻��� �ֹ������� �����ϴ�.</td></tr>";
		}
?>
				<TR>
					<TD background="images/table_top_line.gif" colspan="7"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td><p>&nbsp;</p></td>
			</tr>
			<tr>
				<td align="center">
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
			</form>
			<form name=detailform method="post" action="order_tempdetail.php" target="ordertempdetail">
			<input type=hidden name=ordercode>
			</form>

			<form name=idxform action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=type>
			<input type=hidden name=ordercode>
			<input type=hidden name=block value="<?=$block?>">
			<input type=hidden name=gotopage value="<?=$gotopage?>">
			<input type=hidden name=orderby value="<?=$orderby?>">
			<input type=hidden name=search_date value="<?=$search_date?>">
			</form>

			<form name=member_form action="member_list.php" method=post>
			<input type=hidden name=search>
			</form>

			<form name=mailform action="member_mailsend.php" method=post>
			<input type=hidden name=rmail>
			</form>

			<tr>
				<td height=20></td>
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
					<TD background="images/manual_left1.gif"><IMG SRC="images/manual_left1.gif" WIDTH=15 HEIGHT="5" ALT=""></TD>
					<TD COLSPAN=3 width="100%" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;" class="menual_bg">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><span class="font_dotline">������ ��ǰ�� �ֹ���ȸ</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top" style="letter-spacing:-0.5pt;"><p>- �����õ� ����̶� �����ڰ� �ֹ����� �ۼ��ϰ� ���� �����ܰ�� �Ѿ�� ��<br>
						<b>&nbsp;&nbsp;</b>���� ����, ��Ʈ��ũ ���, ������ PC ���, ��Ÿ ����ġ ���� ������ ���� ���� �����Ϸ���� ���� �ֹ������� ��Ȳ�Դϴ�.</p></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top" style="letter-spacing:-0.5pt;"><p>- �����õ� ��Ͽ� ��ϵ� �ֹ����� ����� <span class="font_orange"><b>1�ð� ��</b></span>�� <span class="font_orange"><b>[�ڵ�]</b></span>���� �ش��ǰ���� ����Ǿ��� ����/������/������ ���󺹱��� �˴ϴ�.<span class="font_orange"><b>(����)</b></span></p></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top" style="letter-spacing:-0.5pt;"><p>- �����õ� ��Ͽ� ��ϵ� �ֹ����� <span class="font_orange"><b>10�� �� [����]</b></span>���� ������ �� ������, ���� �������� �ֹ��� �� �����Ƿ� �������� �ʽ��ϴ�.</p></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top" style="letter-spacing:-0.5pt;"><p>- ���� ������ �ش� �ֹ��� ����Ǿ��� ����/������/������ ���� �����˴ϴ�.</p></td>
					</tr>
					<tr>
						<td colspan="2" height="20"></td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><span class="font_dotline">�����õ� ��Ͽ� ��ϵǴ� ���</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top" style="letter-spacing:-0.5pt;"><p>- �� ���� ������(ISP������ ������ �Է� ������ �ð��� �ټ� ������ �� ����) : ���� ���� �Ϸ�� �����õ� ��Ͽ��� �ڵ� �����˴ϴ�.</p></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top" style="letter-spacing:-0.5pt;"><p>- �� ���Ű��� �ֹ����� �ۼ��ϰ�, ���� �����Ϸ� ���� ���� �������� ������ �����ϴ� ���</p></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top" style="letter-spacing:-0.5pt;"><p>- �� ���Ű��� ���� �Ǵ� PC�� ������ ����, ��Ʈ��ũ�� ���, ��Ÿ �ٸ� �������� ������ ���� ���� �����Ϸ� ���� ���</p></td>
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