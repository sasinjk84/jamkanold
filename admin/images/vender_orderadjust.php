<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");
include_once($Dir."lib/admin_more.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "vd-3";
$MenuCode = "vender";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

//�߰�� ���� ��ȸ
$shop_more_info = getShopMoreInfo();
$shop_relay = $shop_more_info['relay'];
//�߰�� ���� ��ȸ


$CurrentTime = time();
/*
$period[0] = date("Y-m-d",$CurrentTime);
$period[1] = date("Y-m-d",$CurrentTime-(60*60*24*7));
$period[2] = date("Y-m-d",$CurrentTime-(60*60*24*14));
$period[3] = date("Y-m-d",mktime(0,0,0,date("m")-1,date("d"),date("Y")));
*/
$today = date("Y-m-d",$CurrentTime);

$orderby=$_POST["orderby"];
if($orderby!="deli_date" && $orderby!="ordercode") $orderby="deli_date";

$vender=$_REQUEST["vender"];
$s_check=$_POST["s_check"];
$search_date=$_REQUEST["search_date"];

$after_chk = "";
if ($search_date>$today) {
	$after_chk = "1";
}
/*
$search_end=$_POST["search_end"];
$vperiod=(int)$_POST["vperiod"];

$search_start=$search_start?$search_start:$period[1];
$search_end=$search_end?$search_end:date("Y-m-d",$CurrentTime);
$search_s=$search_start?str_replace("-","",$search_start."000000"):str_replace("-","",$period[1]."000000");
$search_e=$search_end?str_replace("-","",$search_end."235959"):date("Ymd",$CurrentTime)."235959";
*/

$search_date = $search_date?$search_date:$today;
$search_d=$search_date?str_replace("-","",$search_date):str_replace("-","",$today);

$after_date = date("Y-m-d", strtotime($search_date." -1 day"));
$next_date = date("Y-m-d", strtotime($search_date." 1 day"));

//${"check_vperiod".$vperiod} = "checked";

$tempstart = explode("-",$search_date);
$tempend = explode("-",$today);
$termday = (mktime(0,0,0,$tempend[1],$tempend[2],$tempend[0])-mktime(0,0,0,$tempstart[1],$tempstart[2],$tempstart[0]))/86400;
if ($termday>366) {
	echo "<script>alert('�˻��Ⱓ�� 1���� �ʰ��� �� �����ϴ�.');location='".$_SERVER[PHP_SELF]."';</script>";
	exit;
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
$sumprice=0;
$sumreserve=0;
$sumdeliprice=0;
$sumcouprice=0;
/*
$sql = "SELECT COUNT(DISTINCT(a.ordercode)) as t_count, (select rate from tblvenderinfo where vender = b.vender) as rate,";
$sql.= "SUM(IF((b.productcode!='99999999990X' AND NOT (b.productcode LIKE 'COU%')), b.price*b.quantity,NULL)) as sumprice, ";
$sql.= "SUM(IF(b.productcode LIKE 'COU%', b.price,NULL)) as sumcouprice, ";
$sql.= "SUM(b.reserve*b.quantity) as sumreserve, SUM(IF(b.productcode='99999999990X', b.price,NULL)) as sumdeliprice ";
$sql.= "FROM tblorderinfo a, tblorderproduct b ".$qry." GROUP BY a.ordercode,b.vender ";
*/

$qty = "";
$h_sql = "";
$b_sql = "";

if(strlen($vender)>0) {
	
	$qry = getVenderOrderAdjustList($vender, $search_date, $s_check);
	
	//1���� ��ȸ
	$sql = "SELECT COUNT(DISTINCT(ordercode)) as t_count,
	SUM(IF((productcode!='99999999990X' AND NOT (productcode LIKE 'COU%')), price,NULL)) as sumprice,
	SUM(cou_price) as sumcouprice, 
	SUM(reserve) as sumreserve, SUM(deli_price) as sumdeliprice, sum(adjust) as sumadjust,
	SUM(rate_price) as sum_rate_price,
	SUM(surtax) as sum_surtax

	FROM `order_adjust_detail` ".$qry;
echo '---------------------';
}else{

	//������ �������� ��ü ��ȸ
	$venders = getVenderToTodayOrderAccount($search_date);
	
	$h_sql = "select * from (";
	$b_sql = ") t ";
	$sql = "";

	$end_i = count($venders)-1;
	$i=0;
	while (isset($venders[$i])) {


		$t_vender = $venders[$i];
		$sql .= "SELECT COUNT(DISTINCT(ordercode)) as t_count,
		SUM(IF((productcode!='99999999990X' AND NOT (productcode LIKE 'COU%')), price,NULL)) as sumprice,
		SUM(cou_price) as sumcouprice, 
		SUM(reserve) as sumreserve, SUM(deli_price) as sumdeliprice, sum(adjust) as sumadjust,
		SUM(rate_price) as sum_rate_price,
		SUM(surtax) as sum_surtax

		FROM `order_adjust_detail` ";
		$sql .= getVenderOrderAdjustList($t_vender, $search_date, $s_check);
		
		
		if ($i<$end_i) {
			$sql .= "
				union
			";
		}

		$i++;

	}

	$sql = $h_sql.$sql.$b_sql;

}
//echo $sql;

$result = mysql_query($sql,get_db_conn());
while($row = mysql_fetch_object($result)) {
	$t_count+=$row->t_count;
	$sumprice+=(int)$row->sumprice;
	$sumreserve+=(int)$row->sumreserve;
	$sumdeliprice+=(int)$row->sumdeliprice;
	$sumcouprice+=(int)$row->sumcouprice;	
	/*
	$sum+=(int)(($row->sumprice+$row->sumdeliprice-$row->sumreserve-$row->sumcouprice)*(100-$row->rate)/100);
	*/
	$sumadjust +=(int)$row->sumadjust;

	$sum_rate_price +=(int)$row->sum_rate_price;
	$sum_surtax +=(int)$row->sum_surtax;
}
mysql_free_result($result);
$pagecount = (($t_count - 1) / $setup[list_num]) + 1;

$venderlist=array();
$sql = "SELECT vender,id,com_name,delflag FROM tblvenderinfo ORDER BY id ASC ";
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

function venderexcelForm() {
	document.sForm.action="vender_order_excel.php";
	document.sForm.submit();
}

function goCalendar(vender, year, month) {

	location.href="vender_calendar.php?vender="+vender+"&year="+year+"&month="+month;

}


function detailView_toVender(vender, date) {
	owin=window.open("about:blank","calendar_detailview","scrollbars=no,width=400,height=300");
	owin.focus();
	document.dForm.vender.value=vender;
	document.dForm.date.value=date;
	document.dForm.target="calendar_detailview";
	document.dForm.action="vender_calendar.detail.php";
	document.dForm.submit();
}

function adjustModify(ordercode, vender) {
	
	window.open("about:blank","adjustModify","scrollbars=yes,resizable=yes, width=900,height=500");

	document.aForm.ordercode.value=ordercode;
	document.aForm.vender.value=vender;
	document.aForm.action="vender_order_adjust_modify.php";
	document.aForm.target="adjustModify";
	document.aForm.submit();


}

function dayMove(n) {

	if (n==1) {
		document.sForm.search_date.value="<?= $next_date ?>";
	}else{
		document.sForm.search_date.value="<?= $after_date ?>";
	}
	
	searchForm()

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
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �������� &gt; �ֹ�/���� ���� &gt; <span class="2depth_select">������ü �������</span></td>
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
					<TD><IMG SRC="images/vender_orderadjust_title.gif" ALT=""></TD>
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
					<TD width="100%" class="notice_blue">������ü�� ��� �ֹ��ǿ� ���� ���� ���� �ֹ������� Ȯ���� �� �ֽ��ϴ�.</TD>
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
					<td bgcolor="#ededed" style="padding:4pt;">
					<table cellpadding="0" cellspacing="0" width="100%" bgcolor="white">
					<tr>
						<td width="100%">
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
						<TR>
							<TD class="table_cell" width="139"><img src="images/icon_point5.gif" width="8" height="11" border="0">������</TD>
							<TD class="td_con1" >
								<input type=text name=search_date value="<?= $search_date?>" size=13 onfocus="this.blur();" OnClick="Calendar(this)" class="input_selected">
								<!--
								~ <input type=text name=search_end value="<?=$search_end?>" size=13 onfocus="this.blur();" OnClick="Calendar(this)" class="input_selected"> 
								<img src=images/btn_today01.gif border=0 align=absmiddle style="cursor:hand" onclick="OnChangePeriod(0)">
								<img src=images/btn_day07.gif border=0 align=absmiddle style="cursor:hand" onclick="OnChangePeriod(1)">
								<img src=images/btn_day14.gif border=0 align=absmiddle style="cursor:hand" onclick="OnChangePeriod(2)">
								<img src=images/btn_day30.gif border=0 align=absmiddle style="cursor:hand" onclick="OnChangePeriod(3)">
								-->
								<a href="javascript:dayMove();"><img src="images/btn_prev_day.gif" border="0" align="absmiddle" alt="������" /></a> <a href="javascript:dayMove(1);"><img src="images/btn_next_day.gif" border="0" align="absmiddle" alt="������" /></a>
							</TD>
						</TR>
						<TR>
							<TD colspan="2" background="images/table_con_line.gif"></TD>
						</TR>
						<TR>
							<TD class="table_cell" width="139"><img src="images/icon_point5.gif" width="8" height="11" border="0">������ü</TD>
							<TD class="td_con1" ><select name="vender" class="select">
							<option value="">�������� �������� ��� ������ü</option>
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
						</TR>
						<TR>
							<TD colspan="2" background="images/table_con_line.gif"></TD>
						</TR>
						<!--
						<TR>
							<TD class="table_cell" width="139"><img src="images/icon_point5.gif" width="8" height="11" border="0">�˻���</TD>
							<TD class="td_con1" ><select name=s_check style="width:94px" class="select">
							<option value="cd" <?if($s_check=="cd")echo"selected";?>>�ֹ��ڵ�</option>
							<option value="mn" <?if($s_check=="mn")echo"selected";?>>�����ڼ���</option>
							<option value="mi" <?if($s_check=="mi")echo"selected";?>>����ȸ��ID</option>
							<option value="cn" <?if($s_check=="cn")echo"selected";?>>��ȸ���ֹ���ȣ</option>
							</select>
							<input type=text name=search value="<?=$search?>" style="width:183" class="input"></TD>
						</TR>
						-->
						<TR>
							<TD class="table_cell" width="139"><img src="images/icon_point5.gif" width="8" height="11" border="0">�������</TD>
							<TD class="td_con1" ><select name=s_check style="width:94px" class="select">
								<option value="" <?if($s_check=="")echo"selected";?>>��ü</option>
								<option value="1" <?if($s_check=="1")echo"selected";?>>��������</option>
								<option value="2" <?if($s_check=="2")echo"selected";?>>���޿Ϸ�</option>
								<option value="3" <?if($s_check=="3")echo"selected";?>>ó���Ϸ�</option>
							</select>
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
			<tr>
				<td style="padding-top:15pt;" align="center"><a href="javascript:venderexcelForm();"><img src="images/btn_excel1.gif" width="127" height="38" border="0" hspace="1"></a><a href="javascript:searchForm();"><img src="images/botteon_search.gif" width="113" height="38" border="0"></a></td>
			</tr>
			</form>
			<tr><td height="30"></td></tr>
			<tr>
				<td style="padding-bottom:3pt;">
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td width="372" align="left"><img src="images/icon_8a.gif" width="13" height="13" border="0"><B>�Ⱓ �� �հ�</B></td>
					<td width="372"></td>
				</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td>
				<?
					$t_col = 6;
					if ($sum_surtax>0) {
						$t_col = 7;
					}
				?>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0 style="table-layout:fixed">
				<col width=></col>
				<col width=></col>
				<? if ($sum_surtax>0) { ?>
				<col width=></col>
				<? } ?>
				<col width=></col>
				<col width=></col>
				<col width=></col>
				<col width=></col>
				<TR>
					<TD background="images/table_top_line.gif" colspan="<?= $t_col ?>"></TD>
				</TR>
				<TR>
					<TD class="table_cell" align="center">�� ��ǰ �Ǹž�</TD>
					<TD class="table_cell1" align="center">�� ������</TD>
					<? if ($sum_surtax>0) { ?>
					<TD class="table_cell1" align="center">�������� �ΰ���</TD>
					<? } ?>
					<TD class="table_cell1" align="center">�� ��۷�</TD>
					<TD class="table_cell1" align="center">�� ���� ������</TD>
					<TD class="table_cell1" align="center">�� ���� ���ξ�</TD>
					<!--
					<TD class="table_cell1" align="center">�� �ݾ�</TD>
					-->
					<TD class="table_cell1" align="center">�� ���� �ݾ�</TD>
				</TR>
				<TR>
					<TD colspan="6" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="td_con2" align="center"><B><?=number_format($sumprice)?>��</B></TD>
					<TD class="td_con1" align="center"><B><?=number_format($sum_rate_price)?>��
					</B></TD>
					<? if ($sum_surtax>0) { ?>
					<TD class="td_con1" align="center"><B><?=number_format($sum_surtax)?>��
					<? } ?>
					<TD class="td_con1" align="center"><B><?=($sumdeliprice>0?"+":"").number_format($sumdeliprice)?>��</B></TD>
					<TD class="td_con1" align="center"><B><?=($sumreserve>0?"-":"").number_format($sumreserve)?>��</B></TD>
					<TD class="td_con1" align="center"><B><?=number_format($sumcouprice)?>��</B></TD>
					<!--
					<TD class="td_con1" align="center"><B><?=number_format($sumprice+$sumdeliprice-($sumreserve-$sumcouprice))?>��</B></TD>
					-->
					<TD class="td_con1" align="center"><B><?=number_format($sumadjust)?>��</B></TD>
				</TR>
				<TR>
					<TD colspan="<?= $t_col ?>" background="images/table_con_line.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr><tr>
				<td height=20></td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<!--
				<TR>
					<TD><IMG SRC="images/manual_top1.gif" WIDTH=15 height="45" ALT=""></TD>
					<TD><IMG SRC="images/manual_title.gif" WIDTH=113 height="45" ALT=""></TD>
					<TD width="100%" background="images/manual_bg.gif" height="35"></TD>
					<TD background="images/manual_bg.gif"></TD>
					<td background="images/manual_bg.gif"><IMG SRC="images/manual_top2.gif" WIDTH=18 height="45" ALT=""></td>
				</TR>
				-->
				<TR>
					<TD colspan="5" background="images/manual_bg.gif"></TD>
				</TR>
				<TR>
					<TD background="images/manual_left1.gif"></TD>
					<TD COLSPAN=3 width="100%" valign="top" class="menual_bg" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td height="10"></td>
					</tr>
					<tr>
						<td class="menual_con"><img src="images/icon_8.gif" border="0" align="absmiddle"><span class="font_dotline">������� �ȳ�</span></td>
					</tr>
					<tr>
						<td  class="space_top"><span class="font_orange" style="padding-left:13px">��ü�� ���������(��������Ⱓ)�� �ش��ϴ�  ��ۿϷ� ���� �ǿ� ���� ����ݾ����� �����մϴ�.</span><br/>
						<span style="padding-left:13px">����ݾ׻��� ����Ÿ�� ���� �ŷ��� ��ۿϷ� �� ���� �����Ͽ� ��������� ���� �ջ�ó���ϸ�, �ֹ����, ȯ�� ���� �߻� �� ������ ���� ���� ��� �ڵ����� ����Ǹ� ������ ���� ���� ���� ����ݾ׿��� �����ϰ� �����մϴ�.<br/>
						<span style="padding-left:13px">������ȸ���� ��������Ⱓ ��(����� ����)�� ��� ������´� �����������ߡ����� ǥ��˴ϴ�.<br/>
						<span style="padding-left:13px"><b>��) A���� �������� 15���̰� ����������� 1��~10�� ���</b><br/>
						<span style="padding-left:13px"><b>��ڰ� 10�ϳ�</b> ����ݾ� ��ȸ ��  �����������ߡ����� ǥ�õǰ�, 11�� 00�� ���� ��ȸ �� ����ý��ۿ��� �ݾ׻����� �Ϸ�Ǹ� ������°� ���������ߡ����� ǥ�õ˴ϴ�.

						</td>
					</tr>
					<tr><td height="5"></td></tr>
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
				<td height="40"></td>
			</tr>
			<tr>
				<td style="padding-bottom:3pt;">
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td width="372" align="left"><img src="images/icon_8a.gif" width="13" height="13" border="0">
					<B>���Ĺ��:
					<select name=orderby onchange="GoOrderby(this.options[this.selectedIndex].value)" class="select">
					<option value="deli_date" <?if($orderby=="deli_date")echo"selected";?>>���԰�����</option>
					<option value="ordercode" <?if($orderby=="ordercode")echo"selected";?>>�ֹ��ڵ�</option>
					</select>
					</td>
					<td  align="right"><img src="images/icon_8a.gif" width="13" height="13" border="0">�� �ֹ��� : <B><?=number_format($t_count)?></B>��&nbsp;&nbsp;<img src="images/icon_8a.gif" width="13" height="13" border="0">���� <b><?=$gotopage?>/<?=ceil($t_count/$setup[list_num])?></b> ������&nbsp;<a href="javascript:goCalendar('<?= $vender ?>','<?$tempend[0] ?>','<?= $tempend[1] ?>');"><img src="images/btn_calendar.gif" align="absmiddle" border="0" alt="���� Ķ���� ����" /></a></td>
				</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td width="100%">
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<col width=70></col> <!-- ������ü -->
				<col width=135></col> <!-- �����/�ֹ��ڵ� -->
				<col width=110></col> <!-- �ֹ��� -->
				<col width=></col> <!-- ��ǰ�� -->

				<col width=55></col> <!-- �� ��۷� -->
				<col width=60></col> <!-- �������� -->
				<col width=70></col> <!-- �����ݾ� -->
				<col width=100></col> <!-- ����ݾ� -->
				<col width=100></col> <!-- ������� -->
				<TR>
					<TD background="images/table_top_line.gif" colspan="13"></TD>
				</TR>
				<TR height="32">
					<TD class="table_cell5" align="center">������ü</TD>
					<TD class="table_cell6" align="center">���԰�����/�ֹ��ڵ�</TD>
					<TD class="table_cell6" align="center">�ֹ�����</TD>
					<TD align="center" colspan="5">
						<table border=0 cellpadding=0 cellspacing=0 width='100%'>
							<col width=></col>
							<col width=30></col>
							<col width=66></col>
							<col width=106></col>
							<col width=61></col>
							<tr height="32">
							<TD class="table_cell6" align="center">��ǰ��</TD>
							<TD class="table_cell6" align="center">����</TD>
							<TD class="table_cell6" align="center">�Ǹűݾ�</TD>
							<TD class="table_cell6" align="center">������ -> �ݾ� 
							<? if($shop_relay=="1") {?>
							<br/><span style="font-size:11px">(�������� �ΰ���)
							<? } ?>
							</TD>
							<TD class="table_cell6" align="center">������</TD>
							</tr>
						</table>
					</td>
					<TD class="table_cell6" align="center">��۷�</TD>
					<TD class="table_cell6" align="center">��������</TD>
					<TD class="table_cell6" align="center">�����ݾ�</TD>
					<TD class="table_cell6" align="center">����ݾ�</TD>
					<TD class="table_cell6" align="center">�������</TD>
				</TR>
				<TR>
					<TD colspan="13" background="images/table_con_line.gif"></TD>
				</TR>
<?
		if($t_count>0) {
			/*
			$sql ="SELECT SUM(IF((b.productcode!='99999999990X' AND NOT (b.productcode LIKE 'COU%')), b.price*b.quantity,NULL)) as sumprice, (select round(rate, 2) from tblvenderinfo where vender = b.vender) as rate, ";
			$sql.= "SUM(b.reserve*b.quantity) as sumreserve, ";
			$sql.= "SUM(IF(b.productcode='99999999990X', b.price,NULL)) as sumdeliprice, ";
			$sql.= "SUM(IF(b.productcode LIKE 'COU%', b.price,NULL)) as sumcouprice, ";
			$sql.= "a.ordercode,a.deli_date, b.vender FROM tblorderinfo a, tblorderproduct b ".$qry." ";
			$sql.="GROUP BY a.ordercode,b.vender ORDER BY a.".$orderby." DESC ";
			$sql.="LIMIT " . ($setup[list_num] * ($gotopage - 1)) . ", " . $setup[list_num];
			*/

			if(strlen($vender)>0) {

				$sql ="SELECT SUM(IF((productcode!='99999999990X' AND NOT (productcode LIKE 'COU%')), price,NULL)) as sumprice, ";
				$sql.= "SUM(reserve) as sumreserve, ";
				$sql.= "SUM(deli_price) as sumdeliprice, ";
				$sql.= "SUM(cou_price) as sumcouprice, ";
				$sql.= "ordercode, deli_date, vender, sum(adjust) as sumadjust FROM `order_adjust_detail` ".$qry." ";
				$sql.="GROUP BY ordercode, vender ";

			}else{

				//������ �������� ��ü ��ȸ
				$sql = "";

				$i=0;
				while (isset($venders[$i])) {

					$t_vender = $venders[$i];

					$sql.="SELECT SUM(IF((productcode!='99999999990X' AND NOT (productcode LIKE 'COU%')), price,NULL)) as sumprice, ";
					$sql.= "SUM(reserve) as sumreserve, ";
					$sql.= "SUM(deli_price) as sumdeliprice, ";
					$sql.= "SUM(cou_price) as sumcouprice, ";
					$sql.= "ordercode, deli_date, vender, sum(adjust) as sumadjust ";
					$sql.= " FROM `order_adjust_detail` o ";					
					$sql .= getVenderOrderAdjustList($t_vender, $search_date, $s_check);
					$sql.="GROUP BY ordercode, vender ";
					
					if ($i<$end_i) {
						$sql .= "
							union
						";
					}

					$i++;
				}

				$sql = $h_sql.$sql.$b_sql;

			}

			$sql .= " ORDER BY vender, ".$orderby." DESC ";

			$result=mysql_query($sql,get_db_conn());
			
			$vender_rowspan= array();
			$now_vender = "";
			while($row=mysql_fetch_object($result)) {
				
				if ($now_vender != $row->vender) {
					$now_vender = $row->vender;
					$vender_rowspan[$now_vender]=1;
				}else{
					$vender_rowspan[$now_vender]++;
				}
			}
			$result=mysql_query($sql,get_db_conn());

			$i=0;

			$thisordcd="";
			$thiscolor="#FFFFFF";			
			
			$dd=0;
			$now_vender = "";
			$vender_adjust = 0;


			$colspan=13;

			while($row=mysql_fetch_object($result)) {

				$number = ($t_count-($setup[list_num] * ($gotopage-1))-$i);
				$date = substr($row->deli_date,0,4)."/".substr($row->deli_date,4,2)."/".substr($row->deli_date,6,2)." (".substr($row->deli_date,8,2).":".substr($row->deli_date,10,2).")";
				$orderdate = substr($row->ordercode,0,4)."/".substr($row->ordercode,4,2)."/".substr($row->ordercode,6,2)." (".substr($row->ordercode,8,2).":".substr($row->ordercode,10,2).")";
				
				if($thisordcd!=$row->ordercode) {
					$thisordcd=$row->ordercode;
					if($thiscolor=="#FFFFFF") {
						$thiscolor="#FEF8ED";
					} else {
						$thiscolor="#FFFFFF";
					}
				}

				echo "<tr bgcolor=".$thiscolor." onmouseover=\"this.style.background='#FEFBD1'\" onmouseout=\"this.style.background='".$thiscolor."'\">\n";
				
				$row_span = 0;
				if ((int) $now_vender != (int) $row->vender) {
					$row_span=($vender_rowspan[$row->vender] * 2)+1;

					echo "	<td rowspan=\"".$row_span."\"  class=\"td_con6\" align=center style=\"font-size:8pt\">".(strlen($venderlist[$row->vender]->vender)>0?"<B><a href=\"javascript:viewVenderInfo(".$row->vender.")\">".$venderlist[$row->vender]->id."</a></B>":"-")."</td>\n";

					$vender_adjust = 0;
					$dd=1;
					
					$now_vender=$row->vender;
				}else{

					//$vender_adjust = $vender_adjust + $row->sumadjust;
					$dd++;
				}


				echo "	<td class=\"td_con5\" align=center style=\"font-size:8pt;line-height:12pt\"><A HREF=\"javascript:OrderDetailView('".$row->ordercode."',".$row->vender.")\">".$date."<br>".$row->ordercode."</A></td>\n";
				echo "	<td class=\"td_con6\" align=center style=\"font-size:8pt;line-height:12pt\">".$orderdate."</td>";
				echo "	<td class=\"td_con6\" colspan=\"5\">\n";
				
				echo "	<table border=0 cellpadding=0 cellspacing=0 width='100%'>\n";
				echo "	<col width=></col>\n";
				echo "	<col width=1></col>\n";
				echo "	<col width=30></col>\n";
				echo "	<col width=1></col>\n";
				echo "	<col width=60></col>\n";
				echo "	<col width=1></col>\n";
				echo "	<col width=100></col>\n";
				echo "	<col width=1></col>\n";
				echo "	<col width=55></col>\n";

				$sql = "SELECT o.*,
						a.account_rule, a.rate, a.cost, a.status,
						a.relay, a.rate_price, a.surtax
						FROM tblorderproduct o right join order_adjust_detail a
						on o.ordercode=a.ordercode and o.productcode=a.productcode
						WHERE o.vender='".$row->vender."' AND o.ordercode='".$row->ordercode."' ";
				$sql.=  getVenderOrderAdjustListGoods($row->vender, $search_date);
				$sql.= "AND NOT (o.productcode LIKE 'COU%' OR o.productcode LIKE '999999%') ";

				$status_chk=0;

				$result2=mysql_query($sql,get_db_conn());
				$jj=0;
				while($row2=mysql_fetch_object($result2)) {

					
					$a_rule = $row2->account_rule;
					$rate = $row2->rate;
					$cost = $row2->cost;

					$relay = $row2->relay;
					$rate_price = $row2->rate_price;
					$surtax = $row2->surtax;

					$rate_val = 0;


					if ($a_rule =='1') {
						$rate_val = $row2->price*$row2->quantity - $cost." ��";
					}else{
						$rate_val = $rate." % ->".$rate_price." ��";
					}

					if ($relay == "1") {
						$rate_val .= "<br/>";
						$rate_val .= "(".$surtax."��)";
					}

					$s_value ="";

					if ($row2->status != 1) {
						$status_chk++;
					}


					if($jj>0) echo "<tr><td colspan=9 height=1 bgcolor=#E7E7E7></tr>";
					echo "<tr>\n";
					echo "	<td style=\"font-size:8pt;padding:3;line-height:11pt\"><a href=\"/front/productdetail.php?productcode=".$row2->productcode."\" target=\"_blank\">".$row2->productname."</a></td>\n";
					echo "	<td bgcolor=#E7E7E7></td>\n";
					echo "	<td align=center style=\"font-size:8pt\">".$row2->quantity."</td>\n";
					echo "	<td bgcolor=#E7E7E7></td>\n";
					echo "	<td align=right style=\"font-size:8pt;padding:3\">".number_format($row2->price*$row2->quantity)."&nbsp;</td>\n";
					echo "	<td bgcolor=#E7E7E7></td>\n";
					echo "	<td align=right style=\"font-size:8pt;padding:3\">".$rate_val."&nbsp;</td>\n";
					echo "	<td bgcolor=#E7E7E7></td>\n";
					echo "	<td align=right style=\"font-size:8pt;padding:3\">".($row2->reserve>0?"-":"").number_format($row2->reserve*$row2->quantity)."&nbsp;</td>\n";
					echo "</tr>\n";
					$jj++;
				}
				mysql_free_result($result2);

				$adjust_btn = "";
				$status_value = "";
				if ($status_chk==0) {
					//$adjust_btn = "<br/><a href=\"javascript:adjustModify('".$row->ordercode."','".$row->vender."');\"><span style='color:ffffff;background-color:1F497D;padding:3px 5px;margin-top:5px;'>���������̷�</span></a>";
					$adjust_btn = "<br/><a href=\"javascript:adjustModify('".$row->ordercode."','".$row->vender."');\"><img src=\"images/btn_history.gif\" border=\"0\" alt=\"���������̷�\" /></a>";
					
					//���ó�¥���� ���ĸ� ó������
					if ($after_chk=="1") {
						$status_value = "����������";
					}else{
						$status_value = "��������";
					}

					$vender_adjust = $vender_adjust + $row->sumadjust;

				}else{
					$status_value = "����ó��";
					$adjust_btn = "<br/><a href=\"javascript:adjustModify('".$row->ordercode."','".$row->vender."');\"><img src=\"images/btn_history.gif\" border=\"0\" alt=\"���������̷�\" /></a>";
				}

				echo "	</table>\n";
				echo "	</td>\n";
				echo "	<td class=\"td_con6\" align=right style=\"font-size:8pt;padding:3\">".($row->sumdeliprice>0?"+":"").number_format($row->sumdeliprice)."&nbsp;</td>\n";
				echo "	<td class=\"td_con6\" align=right style=\"font-size:8pt;padding:3\">".number_format($row->sumcouprice)."&nbsp;</td>\n";
				echo "	<td class=\"td_con6\" align=right style=\"font-size:8pt;padding:3\"><B>".number_format($row->sumprice+$row->sumdeliprice-($row->sumreserve-$row->sumcouprice))."</B>&nbsp;</td>\n";
				echo "	<td class=\"td_con6\" align=right style=\"font-size:8pt;padding:3\"><B>".number_format($row->sumadjust)."</B>&nbsp;</td>\n";
				
				echo "	<td class=\"td_con6\" align=center style=\"font-size:8pt;padding:3;\"><B>".$status_value.$adjust_btn."</B></td>\n";
			
				echo "</tr>\n";
				echo "<tr>\n";
				echo "	<TD height=1 background=\"images/table_con_line.gif\" colspan=\"".($colspan-3)."\"></TD>\n";
				echo "</tr>\n";
				

				if ($dd==$vender_rowspan[$row->vender]) {
		
						$sql = "select * from order_account_new where vender=".$row->vender." and date='".$search_d."'";
						$result2=mysql_query($sql,get_db_conn());
						$row2=mysql_fetch_object($result2);

						if ($row2->confirm=="Y") {
							$s_value = "ó���Ϸ�<br/><A HREF=\"javascript:detailView_toVender('".$row->vender."',".$search_d.")\">".substr($row2->reg_date, 0, 10)."</a>";
						}else if ($row2->confirm=="N") {
							$s_value = "���޿Ϸ�<br/><A HREF=\"javascript:detailView_toVender('".$row->vender."',".$search_d.")\">".substr($row2->reg_date, 0, 10)."</a>";
						}else{
							$s_value = "������<br/><A HREF=\"javascript:detailView_toVender('".$row->vender."',".$search_d.")\"><img src=\"images/btn_calculate.gif\" alt=\"�Ϸ�ó��\" /></a>";
						}
						
						//���ó�¥���� ���ĸ� ó������
						if ($after_chk=="1") {
							$s_value = "<span style='color:red;'>����������</span>";
						}
						
						mysql_free_result($result2);
		
					echo "<tr>";
					echo "	<TD height=2 background=\"images/table_con_line.gif\" colspan=\"12\"></TD>";
					echo "</tr>";
		
					echo "<tr  style='height:50px'>";
					echo "	<td class=\"td_con6\" align=right style=\"font-size:8pt;padding:3\" colspan=\"12\">";

					if ($vender_adjust != 0) {

						$year = substr($search_date, 0, 4);
						$month = substr($search_date, 5, 2);
						$day = substr($search_date, 8, 2);
					
						$adjust_array = getVenderOrderAdjust($row->vender, $year, $month, $day);
						$adjust = $adjust_array['adjust'];
						$ad_start = $adjust_array['start_date'];
						$ad_end = $adjust_array['end_date'];
						
						$ad_start = substr($ad_start, 0, 4)."-".substr($ad_start, 4, 2)."-".substr($ad_start, 6, 2);
						$ad_end = substr($ad_end, 0, 4)."-".substr($ad_end, 4, 2)."-".substr($ad_end, 6, 2);


						echo "<B>���꿹���� (".$ad_start." ~ ".$ad_end.") �հ� : ".number_format($vender_adjust)."</B>&nbsp;";
					}

					echo "</td>\n";
					echo "	<td class=\"td_con6\" align=center style=\"font-size:8pt;padding:3\"><B>".$s_value."</B>&nbsp;</td>\n";
					echo "</tr>";
					echo "<tr>";
					echo "	<TD height=2 background=\"images/table_con_line.gif\" colspan=\"13\"></TD>";
					echo "</tr>";
				}


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
			echo "<tr height=28 bgcolor=#FFFFFF><td colspan=13 align=center>��ȸ�� ������ �����ϴ�.</td></tr>\n";
		}
?>
				<TR>
					<TD background="images/table_top_line.gif" colspan="13"></TD>
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
			
			<input type=hidden name=search_date value="<?=$search_date?>">
			<input type=hidden name=search_start value="<?=$search_start?>">
			<input type=hidden name=search_end value="<?=$search_end?>">
			<input type=hidden name=s_check value="<?=$s_check?>">
			<input type=hidden name=search value="<?=$search?>">
			<input type=hidden name=orderby value="<?=$orderby?>">
			<input type=hidden name=block>
			<input type=hidden name=gotopage>
			</form>

			<form name=vForm action="vender_infopop.php" method=post>
			<input type=hidden name=vender>
			</form>
			
			<form name=dForm method=post>
			<input type=hidden name=vender>
			<input type=hidden name=date>
			</form>

			<form name=aForm method=post>
			<input type=hidden name=ordercode>
			<input type=hidden name=search_date value="<?= $search_date ?>">
			<input type=hidden name=vender>
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
					<TD background="images/manual_left1.gif"></TD>
					<TD COLSPAN=3 width="100%" valign="top" class="menual_bg" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td class="menual_con"><img src="images/icon_8.gif" border="0" align="absmiddle"><span class="font_dotline">������ü �������</span></td>
					</tr>
					<tr>
						<td  class="space_top"><span style="padding-left:13px">- ������ü�� �ֹ��ǿ� ���� ���곻���� Ȯ���� �� �ֽ��ϴ�.</td>
					</tr>
					<tr>
						<td  class="space_top"><span style="padding-left:13px">- ���Ĺ�� : �ֹ��ڵ�/���԰�����  ������ �� �ֽ��ϴ�.</td>
					</tr>
					<tr><td height="20"></td></tr>

					<tr>
						<td class="menual_con"><img src="images/icon_8.gif" border="0" align="absmiddle"><span class="font_dotline">�������</span></td>
					</tr>
					<tr>
						<td  class="space_top"><span style="padding-left:13px">- ����ݾ� : ������ ��������Ⱓ ������ ������ü ��ۿϷ��ǰ�� �� ���⿡�� �Ǹż�����, ���������� ������, ���������� ���� ��۷Ḧ ���� �ݾ��� ������ �� �����ݾ�<br/>
						<span style="padding-left:13px">- ��������� : �ŷ��� ���� �� ����ݾ��� �����Ǵ� �Ⱓ<br/>
						<span style="padding-left:13px">- ����� : ����������� ������ ��¥(������)<br/>
						<span style="padding-left:13px">- ������ : ����������� ����ݾ��� ������ü���� ����(�Ա�)�ϴ� ��¥<br/>
						<span style="padding-left:13px">- ������ȸ�� : ����ݾ��� ��ȸ�ϴ� ��¥
						</td>
					</tr>
					<tr><td height="20"></td></tr>

					<tr>
						<td class="menual_con"><img src="images/icon_8.gif" border="0" align="absmiddle"><span class="font_dotline">������� ��) </span></td>
					</tr>
					<tr>
						<td  class="space_top">
						<span style="padding-left:13px">* A��ü�� �������� �ſ�10�� 1ȸ �̰�, ������� �����Ϸ� ���� 5�� ������ ���<br/>
						<span style="padding-left:13px">- ��������� : ������ 6�� ~ �̹���5��<br/>
						<span style="padding-left:13px">- ����� : �Ŵ� 5��
						</td>
					</tr>
					<tr><td height="20"></td></tr>
					<tr>
						<td class="menual_con"><img src="images/icon_8.gif" border="0" align="absmiddle"><span class="font_dotline">�������� ������� ��) </span></td>
					</tr>
					<tr>
						<td  class="space_top">
						<span style="padding-left:13px">* B��ü�� �������� �ſ� 5��, 10��, 15��, 20��, 25��, 30��  6ȸ �̰�, ������� �����Ϸ� ���� 5�� ������ ���<br/>
						<span style="padding-left:13px">- ��������� : ������ 26��~������ ����(5�� ����), �̹��� 1��~�̹��� 5��(10�� ����), 6��~10��(15�� ����), 11��~15��(20�� ����), 16��~20��(25�� ����), 21��~ 25��(30�� ����)<br/>
						<span style="padding-left:13px">- ����� : �Ŵ� ����, 5��, 10��, 15��, 20��, 25��
						</td>
					</tr>
					<tr><td height="20"></td></tr>
					<tr>
						<td class="menual_con"><img src="images/icon_8.gif" border="0" align="absmiddle"><span class="font_dotline">�������</span></td>
					</tr>
					<tr>
						<td style="padding-left:13px;" class="menual_con">
						<b>1. ������ ���� �</b>
							<div class="font_blue" style="padding-left:13px">
							1)��ǰ�Ǹ� �߰��ü�� �ƴ� �� ��� ����ݾ�=��ǰ�Ǹűݾ�-������ݾ�+��ۺ�-������-��������/���� <br/>
							2)��ǰ�Ǹ� �߰���ü�� �� ��� ����ݾ�=��ǰ�Ǹűݾ�-������ݾ�-�������� �ΰ���+��ۺ�-������-��������/����
							</div>
						<b>2. ��ǰ���ް� ���� �</b>
							<div class="font_blue" style="padding-left:13px">
							1) ��ǰ�Ǹ� �߰��ü�� �ƴ� �� �� ��� ����ݾ�= �ǸŻ�ǰ ��ü ���ް���+��ۺ�-������-��������/���� <br/>
							2) ��ǰ�Ǹ� �߰��ü�� �� �� ��� ����ݾ�=�ǸŻ�ǰ ��ü ���ް���-(��ǰ�Ǹűݾ�-��ǰ���ް���)*0.1+��ۺ�-������-��������/���� <br/>
								<span style="padding-left:13px">* (��ǰ�Ǹűݾ�-��ǰ���ް���)*0.1�� �������� �ΰ����Դϴ�.</span>
							</div>
							* ������ �ݾ� = �Ǹűݾ�x�������� <br/>
							* ������ �� ������ ��� ���� ��ü�� �δ��ϴ°��� ��Ī���� �մϴ�. <br/>
							* ȸ����޺� ���� �� ��Ÿ ������ �Ǹſ��(����)�� �δ��ϴ°��� ��Ģ���� �մϴ�. <br/>
							* ��۷��� ��� ������ ������ �����å�� �����ϴ�.
						</td>
					</tr>
					<tr><td height="20"></td></tr>
					<tr>
						<td class="menual_con"><img src="images/icon_8.gif" border="0" align="absmiddle"><span class="font_dotline">���ݰ�꼭 ó��</span></td>
					</tr>
					<tr>
						<td style="padding-left:13px;" class="menual_con">
						* ��ǰ�Ǹ� �߰��ü�� �ƴ� ��� ��ü ����ݾ� ���� ���Լ��ݰ�꼭�� ������κ��� ���� <br/>
						* ��ǰ�Ǹ� �߰��ü�� ��� ��ǰ�Ǹż����ῡ ���� �ΰ����� ���� �� �����ϰ� �Ǹż����ῡ ���� ���⼼�ݰ�꼭�� �����翡�� �߼�, ������� ��ü �Ǹűݾ׿� ���� �����ڿ��� ���ݰ�꼭 �߼�<br/>
						* ��ǰ�Ǹ��߰���ü�� ��ϴ� ���� ���� �� �����(�鼼) ��ǰ�� ������� ��� ��ǰ�Ǹż������� �ΰ����� ���� �� ����ó���˴ϴ�.<br/>
                          <span style="padding-left:13px">- �Ǹ��߰����� �Ϲݰ����������� �߰� �������� �ΰ��� �����ǹ��� �ֽ��ϴ�.(*���� ��ü���� ���ǻ��״� ���� �������� ���ǹٶ��ϴ�.)
						</td>
					</tr>
					<tr><td height="20"></td></tr>
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