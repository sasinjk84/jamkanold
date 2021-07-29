<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "vd-1";
$MenuCode = "vender";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$CurrentTime = time();
$period[0] = date("Y-m-d",$CurrentTime);
$period[1] = date("Y-m-d",$CurrentTime-(60*60*24*7));
$period[2] = date("Y-m-d",$CurrentTime-(60*60*24*14));
$period[3] = date("Y-m-d",mktime(0,0,0,date("m")-1,date("d"),date("Y")));

$orderby=$_POST["orderby"];
if(strlen($orderby)==0) $orderby="DESC";

$vender=$_POST["vender"];
$paystate=$_POST["paystate"];
$deli_gbn=$_POST["deli_gbn"];
$s_check=$_POST["s_check"];
$search=$_POST["search"];
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
	echo "<script>alert('�˻��Ⱓ�� 1���� �ʰ��� �� �����ϴ�.');location='".$_SERVER[PHP_SELF]."';</script>";
	exit;
}

$qry.= "WHERE a.ordercode=b.ordercode ";
if(strlen($vender)>0) {
	$qry.= "AND b.vender='".$vender."' ";
} else {
	$qry.= "AND b.vender>0 ";
}
if(substr($search_s,0,8)==substr($search_e,0,8)) {
	$qry.= "AND a.ordercode LIKE '".substr($search_s,0,8)."%' ";
} else {
	$qry.= "AND a.ordercode>='".$search_s."' AND a.ordercode <='".$search_e."' ";
}
$qry.= "AND NOT (b.productcode LIKE 'COU%' OR b.productcode LIKE '999999%') ";
if(strlen($deli_gbn)>0)	$qry.= "AND b.deli_gbn='".$deli_gbn."' ";
if($paystate=="Y") {		//�Ա�
	$qry.= "AND ((MID(a.paymethod,1,1) IN ('B','O','Q') AND LENGTH(a.bank_date)=14) OR (MID(a.paymethod,1,1) IN ('C','P','M','V') AND a.pay_admin_proc!='C' AND a.pay_flag='0000')) ";
} else if($paystate=="B") {	//���Ա�
	$qry.= "AND ((MID(a.paymethod,1,1) IN ('B','O','Q') AND (a.bank_date IS NULL OR a.bank_date='')) OR (MID(a.paymethod,1,1) IN ('C','P','M','V') AND a.pay_flag!='0000' AND a.pay_admin_proc='C')) ";
} else if($paystate=="C") {	//ȯ��
	$qry.= "AND ((MID(a.paymethod,1,1) IN ('B','O','Q') AND LENGTH(a.bank_date)=9) OR (MID(a.paymethod,1,1) IN ('C','P','M','V') AND a.pay_flag='0000' AND a.pay_admin_proc='C')) ";
}
if(strlen($search)>0) {
	if($s_check=="cd") $qry.= "AND a.ordercode='".$search."' ";
	else if($s_check=="pn") $qry.= "AND b.productname LIKE '".$search."%' ";
	else if($s_check=="mn") $qry.= "AND a.sender_name='".$search."' ";
	else if($s_check=="mi") $qry.= "AND a.id='".$search."' ";
	else if($s_check=="cn") $qry.= "AND a.id='".$search."X' ";
}

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

$t_count=0;
$sql = "SELECT COUNT(DISTINCT(a.ordercode)) as t_count FROM tblorderinfo a, tblorderproduct b ".$qry." ";
$sql.= "GROUP BY a.ordercode,b.vender ";
$result = mysql_query($sql,get_db_conn());
while($row = mysql_fetch_object($result)) {
	$t_count+=$row->t_count;
}
mysql_free_result($result);
$pagecount = (($t_count - 1) / $setup[list_num]) + 1;

$venderlist=array();
$sql = "SELECT vender,id,com_name,delflag, passwd FROM tblvenderinfo ORDER BY id ASC ";
$result=mysql_query($sql,get_db_conn());
while($row=mysql_fetch_object($result)) {
	$venderlist[$row->vender]=$row;
}
mysql_free_result($result);
?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script type="text/javascript" src="calendar.js.php"></script>
<script language="JavaScript">
function OnChangePeriod(val) {
	var pForm = document.sForm;
	var period = new Array(7);
	period[0] = "<?=$period[0]?>";
	period[1] = "<?=$period[1]?>";
	period[2] = "<?=$period[2]?>";
	period[3] = "<?=$period[3]?>";

	pForm.search_start.value = period[val];
	pForm.search_end.value = period[0];
}

function searchForm() {
	document.sForm.submit();
}

function OrderDetailView(ordercode,vender) {
	document.detailform.ordercode.value = ordercode;
	document.detailform.vender.value = vender;
	window.open("","vorderdetail","scrollbars=yes,width=800,height=600");
	document.detailform.submit();
}

function searchSender(name) {
	document.sForm.s_check.value="mn";
	document.sForm.search.value=name;
	document.sForm.submit();
}

function searchId(id) {
	document.sForm.s_check.value="mi";
	document.sForm.search.value=id;
	document.sForm.submit();
}

function GoPage(block,gotopage) {
	document.pageForm.block.value=block;
	document.pageForm.gotopage.value=gotopage;
	document.pageForm.submit();
}

function GoOrderby(orderby) {
	document.pageForm.block.value = "";
	document.pageForm.gotopage.value = "";
	document.pageForm.orderby.value = orderby;
	document.pageForm.submit();
}

function viewVenderInfo(vender) {
	window.open("about:blank","vender_infopop","width=100,height=100,scrollbars=yes");
	document.vForm.vender.value=vender;
	document.vForm.target="vender_infopop";
	document.vForm.submit();
}

function loginVender(vender, pd) {

	window.open("","loginVender","");

	document.lForm.id.value=vender;
	document.lForm.passwd.value=pd;
	document.lForm.action="/vender/loginproc.php";
	document.lForm.target="loginVender";
	document.lForm.submit();
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
			<? include ("menu_vender.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �������� &gt; �ֹ�/���� ���� &gt; <span class="2depth_select">������ü �ֹ���ȸ</span></td>
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
					<TD><IMG SRC="images/vender_orderlisttitle.gif" ALT=""></TD>
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
					<TD background="images/distribute_04.gif"></TD>
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
					<TD width="100%" class="notice_blue">�ش� ������ü�� ���ں� ��� �ֹ���Ȳ �� �ֹ������� Ȯ��/ó���Ͻ� �� �ֽ��ϴ�.</TD>
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
			<form name=sForm action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=code value="<?=$code?>">
			<tr>
				<td>
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td  bgcolor="#ededed" style="padding:4pt;">
					<table cellpadding="0" cellspacing="0" width="100%" bgcolor="white">
					<tr>
						<td width="100%">
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
						<TR>
							<TD class="table_cell" width="139"><img src="images/icon_point5.gif" width="8" height="11" border="0">�Ⱓ ����</TD>
							<TD class="td_con1" >
								<input type=text name=search_start value="<?=$search_start?>" size=13 onfocus="this.blur();" OnClick="Calendar(this)" class="input_selected"> ~ <input type=text name=search_end value="<?=$search_end?>" size=13 onfocus="this.blur();" OnClick="Calendar(this)" class="input_selected">
								<img src="images/btn_today01.gif" border="0" align="absmiddle" style="cursor:hand" onclick="OnChangePeriod(0)">
								<img src="images/btn_day07.gif" border="0" align="absmiddle" style="cursor:hand" onclick="OnChangePeriod(1)">
								<img src="images/btn_day14.gif" border="0" align="absmiddle" style="cursor:hand" onclick="OnChangePeriod(2)">
								<img src="images/btn_day30.gif" border="0" align="absmiddle" style="cursor:hand" onclick="OnChangePeriod(3)">
							</TD>
						</TR>
						<TR>
							<TD colspan="2" background="images/table_con_line.gif"></TD>
						</TR>
						<TR>
							<TD class="table_cell" width="139"><img src="images/icon_point5.gif" width="8" height="11" border="0">��������</TD>
							<TD class="td_con1" ><select name="paystate" class="select">
<?
								$arps=array("\"\":��ü����","Y:�Ա�","B:���Ա�","C:ȯ��");
								for($i=0;$i<count($arps);$i++) {
									$tmp=split(":",$arps[$i]);
									echo "<option value=\"".$tmp[0]."\" ";
									if($tmp[0]==$paystate) echo "selected";
									echo ">".$tmp[1]."</option>\n";
								}
?>
							</select></TD>
						</TR>
						<TR>
							<TD colspan="2" background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></TD>
						</TR>
						<TR>
							<TD class="table_cell" width="139"><img src="images/icon_point5.gif" width="8" height="11" border="0">ó���ܰ�</TD>
							<TD class="td_con1" ><select name="deli_gbn" class="select">
<?
							$ardg=array("\"\":��ü����","S:�߼��غ�","Y:���","N:��ó��","C:�ֹ����","R:�ݼ�","D:��ҿ�û","E:ȯ�Ҵ��","H:���(���꺸��)");
							for($i=0;$i<count($ardg);$i++) {
								$tmp=split(":",$ardg[$i]);
								echo "<option value=\"".$tmp[0]."\" ";
								if($tmp[0]==$deli_gbn) echo "selected";
								echo ">".$tmp[1]."</option>\n";
							}
?>
							</select></TD>
						</TR>
						<TR>
							<TD colspan="2" background="images/table_con_line.gif"></TD>
						</TR>
						<tr>
							<TD class="table_cell" width="139"><img src="images/icon_point5.gif" width="8" height="11" border="0">������ü</TD>
							<TD class="td_con1" ><select name=vender class="select">
							<option value=""> ��� ������ü</option>
<?
							$tmplist=$venderlist;
							while(list($key,$val)=each($tmplist)) {
								if($val->delflag=="N") {
									echo "<option value=\"".$val->vender."\"";
									if($vender==$val->vender) echo " selected";
									echo ">".$val->id." - ".$val->com_name."</option>\n";
								}
							}
?>
							</select></TD>
						</tr>
						<TR>
							<TD class="table_cell" width="139"><img src="images/icon_point5.gif" width="8" height="11" border="0">�˻���</TD>
							<TD class="td_con1" ><select name="s_check" class="select">
								<option value="cd" <?if($s_check=="cd")echo"selected";?>>�ֹ��ڵ�</option>
								<option value="pn" <?if($s_check=="pn")echo"selected";?>>��ǰ��</option>
								<option value="mn" <?if($s_check=="mn")echo"selected";?>>�����ڼ���</option>
								<option value="mi" <?if($s_check=="mi")echo"selected";?>>����ȸ��ID</option>
								<option value="cn" <?if($s_check=="cn")echo"selected";?>>��ȸ���ֹ���ȣ</option>
								</select>
								<input type=text name=search value="<?=$search?>" style="width:183" class="input"></TD>
						</TR>
						<TR>
							<TD colspan="2" background="images/table_con_line.gif"></TD>
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
				<td style="padding-top:10pt;" align="center"><a href="javascript:searchForm();"><img src="images/botteon_search.gif" width="113" height="38" border="0"></a></td>
			</tr>
			</form>
			<tr>
				<td height="40"></td>
			</tr>
			<tr>
				<td style="padding-bottom:3pt;">
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td width="372" align="left"><img src="images/icon_8a.gif" width="13" height="13" border="0"><B>����:
					<?if($orderby=="DESC"){?>
					<A  href="javascript:GoOrderby('ASC');"><FONT class=font_orange>�ֹ����ڼ���</FONT></B></A>
					<?}else{?>
					<A  href="javascript:GoOrderby('DESC');"><FONT class=font_orange>�ֹ����ڼ���</FONT></B></A>
					<?}?>
					</td>
					<td  align="right"><img src="images/icon_8a.gif" width="13" height="13" border="0">�� : <B><?=number_format($t_count)?></B>��&nbsp;&nbsp;<img src="images/icon_8a.gif" width="13" height="13" border="0">���� <b><?=$gotopage?>/<?=ceil($t_count/$setup[list_num])?></b> ������</td>
				</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td>
				<TABLE border="0" cellSpacing=0 cellPadding=0 width="100%" style="table-layout:fixed">
				<col width=120></col>
				<col width=140></col>
				<col width=80></col>
				<col width=></col>
				<col width=50></col>
				<col width=100></col>
				<col width=100></col>
				<col width=100></col>
				<TR>
					<TD background="images/table_top_line.gif" width="761" colspan="8"></TD>
				</TR>
				<TR height="32">
					<TD class="table_cell5" align="center">�ֹ�����</TD>
					<TD class="table_cell6" align="center">�ֹ��� ����</TD>
					<TD class="table_cell6" align="center">������ü</TD>
					<TD class="table_cell6" align="center">��ǰ��</TD>
					<TD class="table_cell6" align="center">����</TD>
					<TD class="table_cell6" align="center">�Ǹűݾ�</TD>
					<TD class="table_cell6" align="center">ó���ܰ�</TD>
					<TD class="table_cell6" align="center">��������</TD>
				</TR>
				<TR>
					<TD colspan="8" background="images/table_con_line.gif"></TD>
				</TR>
<?
		$colspan=8;
		if($t_count>0) {
			$sql = "SELECT a.ordercode,a.id,a.paymethod,a.pay_data,a.bank_date,a.pay_flag,a.pay_auth_no, ";
			$sql.= "a.pay_admin_proc,a.escrow_result,a.sender_name,a.del_gbn, b.vender ";
			$sql.= "FROM tblorderinfo a, tblorderproduct b ".$qry." ";
			$sql.= "GROUP BY a.ordercode, b.vender ORDER BY a.ordercode ".$orderby." ";
			$sql.= "LIMIT " . ($setup[list_num] * ($gotopage - 1)) . ", " . $setup[list_num];
			$result=mysql_query($sql,get_db_conn());
			$i=0;
			$thisordcd="";
			$thiscolor="#FFFFFF";
			while($row=mysql_fetch_object($result)) {
				$number = ($t_count-($setup[list_num] * ($gotopage-1))-$i);
				$date = substr($row->ordercode,0,4)."/".substr($row->ordercode,4,2)."/".substr($row->ordercode,6,2)." (".substr($row->ordercode,8,2).":".substr($row->ordercode,10,2).")";
				$name=$row->sender_name;
				unset($stridX);
				unset($stridM);
				if(substr($row->ordercode,20)=="X") {	//��ȸ��
					$stridX = substr($row->id,1,6);
				} else {	//ȸ��
					$stridM = "<A HREF=\"javascript:searchId('".$row->id."');\"><FONT COLOR=\"#0099BF\">".$row->id."</FONT></A>";
				}
				if($thisordcd!=$row->ordercode) {
					$thisordcd=$row->ordercode;
					if($thiscolor=="#FFFFFF") {
						$thiscolor="#FEF8ED";
					} else {
						$thiscolor="#FFFFFF";
					}
				}
				echo "<tr bgcolor=".$thiscolor." onmouseover=\"this.style.background='#FEFBD1'\" onmouseout=\"this.style.background='".$thiscolor."'\">\n";
				echo "	<td class=\"td_con5\" align=center style=\"padding:3; line-height:11pt\"><A HREF=\"javascript:OrderDetailView('".$row->ordercode."',".$row->vender.")\">".$date."</A></td>\n";
				echo "	<td class=\"td_con6\" style=\"padding:3; line-height:11pt\">\n";
				echo "	�ֹ��� : <A HREF=\"javascript:searchSender('".$name."');\"><FONT COLOR=\"#0099BF\">".$name."</font></A>";
				if(strlen($stridX)>0) {
					echo "<br> �ֹ���ȣ : ".$stridX;
				} else if(strlen($stridM)>0) {
					echo "<br> ���̵� : ".$stridM;
				}
				echo "	</td>\n";
				echo "	<td class=\"td_con6\" align=center>".(strlen($venderlist[$row->vender]->vender)>0?"<B><a href=\"javascript:viewVenderInfo(".$row->vender.")\">".$venderlist[$row->vender]->id."</a></B>":"-")."<br/><a href=\"javascript:loginVender('".$venderlist[$row->vender]->id."','".$venderlist[$row->vender]->passwd."');\"><span style='padding:3px 10px'><img src=\"images/icon_venderlogin.gif\" alt=\"������\" /></span></a></td>\n";
				echo "	<td style=\"height:100%; BORDER-LEFT:#E3E3E3 1pt solid;\" colspan=4>\n";
				echo "	<table border=0 cellpadding=0 cellspacing=0 width=100% height=100% style=\"table-layout:fixed\">\n";
				echo "	<col width=></col>\n";
				echo "	<col width=1></col>\n";
				echo "	<col width=49></col>\n";
				echo "	<col width=1></col>\n";
				echo "	<col width=99></col>\n";
				echo "	<col width=1></col>\n";
				echo "	<col width=99></col>\n";
				$sql = "SELECT * FROM tblorderproduct WHERE vender='".$row->vender."' AND ordercode='".$row->ordercode."' ";
				$sql.= "AND ordercode='".$row->ordercode."' ";
				$sql.= "AND NOT (productcode LIKE 'COU%' OR productcode LIKE '999999%') ";
				if(strlen($deli_gbn)>0)	$sql.= "AND deli_gbn='".$deli_gbn."' ";
				if(strlen($search)>0 && $s_check=="pn") {
					$sql.= "AND productname LIKE '".$search."%' ";
				}
				$result2=mysql_query($sql,get_db_conn());
				$jj=0;
				while($row2=mysql_fetch_object($result2)) {
					if($jj>0) echo "<tr><td colspan=7 height=1 bgcolor=#E7E7E7></tr>";
					echo "<tr>\n";
					echo "	<td style=\"padding:3; line-height:11pt\"><a href=\"/front/productdetail.php?productcode=".$row2->productcode."\" target=\"_blank\">".$row2->productname."</a></td>\n";
					echo "	<td bgcolor=#E7E7E7></td>\n";
					echo "	<td align=center>".$row2->quantity."</td>\n";
					echo "	<td bgcolor=#E7E7E7></td>\n";
					echo "	<td align=right style=\"padding:3\">".number_format($row2->price*$row2->quantity)."&nbsp;</td>\n";
					echo "	<td bgcolor=#E7E7E7></td>\n";
					echo "	<td align=center style=\"padding:3\">";
					switch($row2->deli_gbn) {
						case 'S': echo "�߼��غ�";  break;
						case 'X': echo "��ۿ�û";  break;
						case 'Y': echo "���";  break;
						case 'D': echo "<font color=#0099BF>��ҿ�û</font>";  break;
						case 'N': echo "��ó��";  break;
						case 'E': echo "<font color=#FF4C00>ȯ�Ҵ��</font>";  break;
						case 'C': echo "<font color=#FF4C00>�ֹ����</font>";  break;
						case 'R': echo "�ݼ�";  break;
						case 'H': echo "���(<font color=#FF4C00>���꺸��</font>)";  break;
					}
					if($row2->deli_gbn=="D" && strlen($row2->deli_date)==14) echo " (���)";
					echo "	</td>\n";
					echo "</tr>\n";
					$jj++;
				}
				mysql_free_result($result2);
				echo "	</table>\n";
				echo "	</td>\n";
				echo "	<td class=\"td_con6\" align=center style=\"font-size:8pt; padding:3; line-height:12pt\">";
				if(preg_match("/^(B){1}/", $row->paymethod)) {	//������
					echo "������<br>";
					if (strlen($row->bank_date)==9 && substr($row->bank_date,8,1)=="X") echo "<font color=005000>[ȯ��]</font>";
					else if (strlen($row->bank_date)>0) {
						echo "<font color=004000>[�ԱݿϷ�]</font>";
					} else {
						echo "[�Աݴ��]";
					}
				} else if(preg_match("/^(V){1}/", $row->paymethod)) {	//������ü
					echo "������ü<br>";
					if (strcmp($row->pay_flag,"0000")!=0) echo "<font color=#757575>[��������]</font>";
					else if ($row->pay_flag=="0000" && $row->pay_admin_proc=="C") echo "<font color=005000>[ȯ��]</font>";
					else if ($row->pay_flag=="0000") {
						echo "<font color=0000a0>[�����Ϸ�]</font>";
					}
				} else if(preg_match("/^(M){1}/", $row->paymethod)) {	//�ڵ���
					echo "�ڵ���<br>";
					if (strcmp($row->pay_flag,"0000")!=0) echo "<font color=#757575>[��������]</font>";
					else if ($row->pay_flag=="0000" && $row->pay_admin_proc=="C") echo "<font color=005000>[��ҿϷ�]</font>";
					else if ($row->pay_flag=="0000") {
						echo "<font color=0000a0>[�����Ϸ�]</font>";
					}
				} else if(preg_match("/^(O|Q){1}/", $row->paymethod)) {	//�������
					echo "�������<br>";
					if (strcmp($row->pay_flag,"0000")!=0) echo "<font color=#757575>[�ֹ�����]</font>";
					else if ($row->pay_flag=="0000" && $row->pay_admin_proc=="C") echo "<font color=005000>[ȯ��]</font>";
					else if ($row->pay_flag=="0000" && strlen($row->bank_date)==0) echo "<font color=#FF4C00>[���Ա�]</font>";
					else if ($row->pay_flag=="0000" && strlen($row->bank_date)>0) {
						echo "<font color=0000a0>[�ԱݿϷ�]</font>";
					}
				} else {
					echo "�ſ�ī��<br>";
					if (strcmp($row->pay_flag,"0000")!=0) echo "<font color=#757575>[ī�����]</font>";
					else if ($row->pay_flag=="0000" && $row->pay_admin_proc=="N") echo "<font color=#FF4C00>[ī�����]</font>";
					else if ($row->pay_flag=="0000" && $row->pay_admin_proc=="Y") {
						echo "<font color=0000a0>[�����Ϸ�]</font>";
					}
					else if ($row->pay_flag=="0000" && $row->pay_admin_proc=="C") echo "<font color=005000>[��ҿϷ�]</font>";
				}
				echo "	</td>\n";
				echo "</tr>\n";
				echo "<tr>\n";
				echo "	<TD height=1 background=\"images/table_con_line.gif\" colspan=\"".$colspan."\"></TD>\n";
				echo "</tr>\n";
				$i++;
			}
			mysql_free_result($result);
			$cnt=$i;

			if($i>0) {
				$total_block = intval($pagecount / $setup[page_num]);
				if (($pagecount % $setup[page_num]) > 0) {
					$total_block = $total_block + 1;
				}
				$total_block = $total_block - 1;
				if (ceil($t_count/$setup[list_num]) > 0) {
					// ����	x�� ����ϴ� �κ�-����
					$a_first_block = "";
					if ($nowblock > 0) {
						$a_first_block .= "<a href='javascript:GoPage(0,1);' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='ù ������';return true\"><img src=/images/minishop/btn_miniprev_end.gif border=0 align=absmiddle></a> ";
						$prev_page_exists = true;
					}
					$a_prev_page = "";
					if ($nowblock > 0) {
						$a_prev_page .= "<a href='javascript:GoPage(".($nowblock-1).",".($setup[page_num]*($block-1)+$setup[page_num]).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup[page_num]." ������';return true\"><img src=/images/minishop/btn_miniprev.gif border=0 align=absmiddle></a> ";

						$a_prev_page = $a_first_block.$a_prev_page;
					}
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
					}
					$a_last_block = "";
					if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
						$last_block = ceil($t_count/($setup[list_num]*$setup[page_num])) - 1;
						$last_gotopage = ceil($t_count/$setup[list_num]);
						$a_last_block .= " <a href='javascript:GoPage(".$last_block.",".$last_gotopage.");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='������ ������';return true\"><img src=/images/minishop/btn_mininext_end.gif border=0 align=absmiddle></a>";
						$next_page_exists = true;
					}
					$a_next_page = "";
					if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
						$a_next_page .= " <a href='javascript:GoPage(".($nowblock+1).",".($setup[page_num]*($nowblock+1)+1).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup[page_num]." ������';return true\"><img src=/images/minishop/btn_mininext.gif border=0 align=absmiddle></a>";
						$a_next_page = $a_next_page.$a_last_block;
					}
				} else {
					$print_page = "<B>[1]</B>";
				}
				$pageing=$a_div_prev_page.$a_prev_page.$print_page.$a_next_page.$a_div_next_page;
			}
		} else {
			echo "<tr height=28 bgcolor=#FFFFFF><td colspan=".$colspan." align=center>��ȸ�� ������ �����ϴ�.</td></tr>\n";
		}
?>
				<TR>
					<TD background="images/table_top_line.gif" colspan="<?=$colspan?>"></TD>
				</TR>
				</table>
				<table border=0 cellpadding=0 cellspacing=0 width=100%>
				<tr>
					<td align=center style="padding-top:10"><?=$pageing?></td>
				</tr>
				</table>
				</td>
			</tr>
			<form name=detailform method="post" action="vender_orderdetail.php" target="vorderdetail">
			<input type=hidden name=ordercode>
			<input type=hidden name=vender>
			</form>

			<form name=pageForm method=post action="<?=$_SERVER[PHP_SELF]?>">
			<input type=hidden name=vender value="<?=$vender?>">
			<input type=hidden name=search_start value="<?=$search_start?>">
			<input type=hidden name=search_end value="<?=$search_end?>">
			<input type=hidden name=s_check value="<?=$s_check?>">
			<input type=hidden name=search value="<?=$search?>">
			<input type=hidden name=paystate value="<?=$paystate?>">
			<input type=hidden name=deli_gbn value="<?=$deli_gbn?>">
			<input type=hidden name=orderby value="<?=$orderby?>">
			<input type=hidden name=block>
			<input type=hidden name=gotopage>
			</form>

			<form name=vForm action="vender_infopop.php" method=post>
			<input type=hidden name=vender>
			</form>

			<? /* �α��� ���� �߰� jdy */?>
			<form name=lForm method=post>
			<input type=hidden name="id">
			<input type=hidden name="passwd">
			<input type=hidden name="admin_chk" value="1">
			</form>
			<? /* �α��� ���� �߰� jdy */?>

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
					<TD background="images/manual_left1.gif"></TD>
					<TD COLSPAN=3 width="100%" valign="top" class="menual_bg" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><span class="font_dotline">������ü �ֹ���ȸ</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- ������ü �ֹ����� ��ȸ������ �Դϴ�.</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- �Ⱓ/��������/������ ���̵� �˻�������� �ֹ������� ��ȸ�մϴ�.</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- �ֹ����� : �ֹ��� ������ Ȯ���� �� �ֽ��ϴ�.</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- �ֹ���/���̵� : ����ȸ�� �ֹ�����Ʈ ��µ˴ϴ�.</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- ������ü���̵�: ������ü ������ Ȯ���� �� �ֽ��ϴ�.</td>
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
