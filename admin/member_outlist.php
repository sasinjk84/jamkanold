<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "me-1";
$MenuCode = "member";
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
$outid=$_POST["outid"];

//sendmail($to, $subject, $body, $header)
if ($type=="delete" && strlen($outid)>0) {
	$sql = "SELECT email FROM tblmember WHERE id='".$outid."'";
	$result = mysql_query($sql,get_db_conn());
	if ($row = mysql_fetch_object($result)) {
		$email=$row->email;
	}
	mysql_free_result($result);

	$sql = "SELECT COUNT(*) as cnt FROM tblorderinfo WHERE id='".$outid."'";
	$result= mysql_query($sql,get_db_conn());
	$row = mysql_fetch_object($result);
	mysql_free_result($result);
	if ($row->cnt==0) {
		$sql = "DELETE FROM tblmember WHERE id = '".$outid."'";
		$state="Y";
	}else {
		$sql = "UPDATE tblmember SET ";
		$sql.= "passwd			= '', ";
		$sql.= "resno			= '', ";
		$sql.= "email			= '', ";
		$sql.= "news_yn			= 'N', ";
		$sql.= "age				= '', ";
		$sql.= "gender			= '', ";
		$sql.= "job				= '', ";
		$sql.= "birth			= '', ";
		$sql.= "home_post		= '', ";
		$sql.= "home_addr		= '', ";
		$sql.= "home_tel		= '', ";
		$sql.= "mobile			= '', ";
		$sql.= "office_post		= '', ";
		$sql.= "office_addr		= '', ";
		$sql.= "office_tel		= '', ";
		$sql.= "memo			= '', ";
		$sql.= "reserve			= 0, ";
		$sql.= "joinip			= '', ";
		$sql.= "ip				= '', ";
		$sql.= "authidkey		= '', ";
		$sql.= "group_code		= '', ";
		$sql.= "member_out		= 'Y', ";
		$sql.= "etcdata			= '' ";
		$sql.= "WHERE id = '".$outid."'";
		$state="V";
	}
	mysql_query($sql,get_db_conn());
	$sql = "DELETE FROM tblreserve WHERE id='".$outid."'";
	mysql_query($sql,get_db_conn());
	$sql = "DELETE FROM tblcouponissue WHERE id='".$outid."'";
	mysql_query($sql,get_db_conn());
	$sql = "DELETE FROM tblmemo WHERE id='".$outid."'";
	mysql_query($sql,get_db_conn());
	$sql = "DELETE FROM tblrecommendmanager WHERE rec_id='".$outid."'";
	mysql_query($sql,get_db_conn());
	$sql = "DELETE FROM tblrecomendlist WHERE id='".$outid."'";
	mysql_query($sql,get_db_conn());
	$sql = "DELETE FROM tblpersonal WHERE id='".$outid."'";
	mysql_query($sql,get_db_conn());
	$sql = "UPDATE tblmemberout SET state='".$state."' WHERE id='".$outid."'";
	mysql_query($sql,get_db_conn());

	$maildata = "[".$_shopdata->shopname."]���� ȸ�� Ż�� ó���� �ص�Ƚ��ϴ�.<br>";
	$maildata.= "�׵��� ���� ���θ��� �̿��� �ּż� �����մϴ�.<br>";
	$maildata.= $_shopdata->shopname." ���θ� ��� �ø�";
	sendmail($email,"[".$_shopdata->shopname."]ȸ��Ż�� ó���� �Ϸ�Ǿ����ϴ�.",$maildata,"From: ".$_shopdata->info_email."\r\nContent-Type: text/html; charset=euc-kr\r\n");

	$log_content = "## ȸ������ : ID:$outid ##";
	ShopManagerLog($_ShopInfo->getId(),$connect_ip,$log_content);

	$onload="<script>alert('".$outid."���� Ż��ó���� �Ϸ�Ǿ����ϴ�.');</script>\n";
} else if ($type=="cancel" && strlen($outid)>0) {
	mysql_query("DELETE FROM tblmemberout WHERE id = '".$outid."'",get_db_conn());
	$onload="<script>alert('".$outid." ȸ������ Ż�� ���ó�� �Ͽ����ϴ�.');</script>\n";
}

$CurrentTime = time();
$period[0] = date("Y-m-d",$CurrentTime);
$period[1] = date("Y-m-d",$CurrentTime-(60*60*24*3));
$period[2] = date("Y-m-d",$CurrentTime-(60*60*24*7));

$search_start=$_POST["search_start"];
$search_end=$_POST["search_end"];
$vperiod=(int)$_POST["vperiod"];
$search=$_POST["search"];

$search_start=$search_start?$search_start:$period[0];
$search_end=$search_end?$search_end:date("Y-m-d",$CurrentTime);
$search_s=$search_start?str_replace("-","",$search_start."000000"):str_replace("-","",$period[0]."000000");
$search_e=$search_end?str_replace("-","",$search_end."235959"):date("Ymd",$CurrentTime)."235959";
$s_check=$_POST["s_check"];
if(!$s_check) $s_check="id";

${"check_s_check".$s_check} = "checked";
${"check_vperiod".$vperiod} = "checked";

$tempstart = explode("-",$search_start);
$tempend = explode("-",$search_end);
$termday = (mktime(0,0,0,$tempend[1],$tempend[2],$tempend[0])-mktime(0,0,0,$tempstart[1],$tempstart[2],$tempstart[0]))/86400;
if ($termday>366) {
	echo "<script>alert('�˻��Ⱓ�� 1���� �ʰ��� �� �����ϴ�.');location='".$_SERVER[PHP_SELF]."';</script>";
	exit;
}

?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script type="text/javascript" src="calendar.js.php"></script>
<script language="JavaScript">
function CheckForm() {

}

function CheckSearch() {
	document.form1.submit();
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

function OutResult(id){
	if(confirm('�ش� ȸ���� Ż��ó���Ͻðڽ��ϱ�?')){
		document.form2.type.value="delete";
		document.form2.outid.value=id;
		document.form2.submit();
	}
}

function OutCancel(id) {
	if(confirm('�ش� ȸ���� Ż�� ����Ͻðڽ��ϱ�?')){
		 document.form2.type.value="cancel";
		 document.form2.outid.value=id;
		 document.form2.submit();
	}
}

function GoPage(block,gotopage) {
	document.form2.block.value = block;
	document.form2.gotopage.value = gotopage;
	document.form2.submit();
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
			<? include ("menu_member.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ȸ������ &gt; ȸ���������� &gt; <span class="2depth_select">Ż���û ����</span></td>
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
					<TD><IMG SRC="images/member_outlist_title.gif" ALT=""></TD>
					</tr>
<tr>
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
					<TD width="100%" class="notice_blue">���θ����� Ż���û�� ȸ���� ��ȸ �� Ż������� �� �ֽ��ϴ�.</TD>
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
			<tr><td height=20></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/member_outlist_stitle1.gif" WIDTH="192" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=3></td></tr>
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=type>
			<input type=hidden name=outid>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">�˻��Ⱓ ����</TD>
					<TD class="td_con1" ><input type=text name=search_start value="<?=$search_start?>" size=10 onfocus="this.blur();" OnClick="Calendar(this)" class="input_selected"> ~ <input type=text name=search_end value="<?=$search_end?>" size=10 onfocus="this.blur();" OnClick="Calendar(this)" class="input_selected">
					<input type=radio id=idx_vperiod0 name=vperiod value="0" checked style="BORDER-RIGHT: 0px; BORDER-TOP: 0px; BORDER-LEFT: 0px; BORDER-BOTTOM: 0px;" onclick="OnChangePeriod(this.value)" <?=$check_vperiod0?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_vperiod0>����</label>
					<input type=radio id=idx_vperiod1 name=vperiod value="1" style="BORDER-RIGHT: 0px; BORDER-TOP: 0px; BORDER-LEFT: 0px; BORDER-BOTTOM: 0px;" onclick="OnChangePeriod(this.value)" <?=$check_vperiod1?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_vperiod1>3��</label>
					<input type=radio id=idx_vperiod2 name=vperiod value="2" style="BORDER-RIGHT: 0px; BORDER-TOP: 0px; BORDER-LEFT: 0px; BORDER-BOTTOM: 0px;" onclick="OnChangePeriod(this.value)" <?=$check_vperiod2?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_vperiod2>1����</label>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">�˻��� �Է�</TD>
					<TD class="td_con1" ><input name=search size=47 value="<?=$search?>" class="input"> <select size=1 name=s_check class="select">
						<option value="id" <? if($s_check=="id") echo "selected"?>>ȸ�� ���̵�
						<option value="name" <? if($s_check=="name") echo "selected"?>>ȸ�� �̸�
						</select> <a href="javascript:CheckSearch();"><img src="images/btn_search3.gif" width="77" height="25" border="0" align=absmiddle></a></TD>
				</TR>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height="30"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/member_outlist_stitle2.gif" WIDTH="192" HEIGHT=31 ALT=""></TD>
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
				<TR>
					<TD background="images/table_top_line.gif" colspan="7" height=1></TD>
				</TR>
				<TR align=center>
					<TD class="table_cell">No</TD>
					<TD class="table_cell1">Ż������/IP</TD>
					<TD class="table_cell1">ȸ��ID</TD>
					<TD class="table_cell1">�̸�</TD>
					<TD class="table_cell1">��ȭ</TD>
					<TD class="table_cell1">�̸���</TD>
					<TD class="table_cell1">Ż�����</TD>
				</TR>
				<TR>
					<TD colspan="7" background="images/table_con_line.gif"></TD>
				</TR>
<?
		$colspan=7;
		$qry = "WHERE (date >= '".$search_s."' AND date <= '".$search_e."') ";
		if(strlen($search)>0) $qry.= "AND ".$s_check." LIKE '".$search."%' ";

		$sql = "SELECT COUNT(*) as t_count FROM tblmemberout ";
		$sql.= $qry;
		$result = mysql_query($sql,get_db_conn());
		$row = mysql_fetch_object($result);
		$t_count = $row->t_count;
		mysql_free_result($result);
		$pagecount = (($t_count - 1) / $setup[list_num]) + 1;

		$sql = "SELECT * FROM tblmemberout ";
		$sql.= $qry." ";
		$sql.= "ORDER BY date DESC ";
		$sql.= "LIMIT " . ($setup[list_num] * ($gotopage - 1)) . ", " . $setup[list_num];
		$result = mysql_query($sql,get_db_conn());
		$cnt=0;
		$message = array ("Y"=>"ȸ��ID <font color=#00209E><b>����</b></font> ����","V"=>"ȸ��ID <font color=#FF5D00><b>������</b></font> ����");
		while($row=mysql_fetch_object($result)) {
			$number = ($t_count-($setup[list_num] * ($gotopage-1))-$cnt);
			$str_date = substr($row->date,0,4)."/".substr($row->date,4,2)."/".substr($row->date,6,2)."<br><span class=\"font_orange\">(".$row->ip.")</span>";
			echo "<tr>\n";
			echo "	<TD align=center class=\"td_con2\">".$number."</td>\n";
			echo "	<TD align=center class=\"td_con1\">".$str_date."</td>\n";
			echo "	<TD align=center class=\"td_con1\"><b><span class=\"font_orange\">".$row->id."</span></b></TD>\n";
			echo "	<TD align=center class=\"td_con1\">".$row->name."</td>\n";
			echo "	<TD align=center class=\"td_con1\">".$row->tel."</td>\n";
			echo "	<TD align=center class=\"td_con1\">".$row->email."</td>\n";
			echo "	<TD align=center class=\"td_con1\">".($row->state=="N"?"<a href=\"javascript:OutResult('".$row->id."');\"><img src=\"images/icon_tal.gif\" width=\"54\" height=\"16\" border=\"0\"></a>&nbsp;<a href=\"javascript:OutCancel('".$row->id."');\"><img src=\"images/icon_canceltal.gif\" width=\"54\" height=\"16\" border=\"0\"></a>":$message[$row->state])."</td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<TD colspan=\"7\" background=\"images/table_con_line.gif\"></TD>\n";
			echo "</tr>\n";
			$cnt++;
		}
		mysql_free_result($result);

		if ($cnt==0) {
			echo "<tr><td class=\"td_con2\" colspan=".$colspan." align=center>ȸ�� Ż���û ������ �������� �ʽ��ϴ�.</td></tr>";
		}
?>
				<TR>
					<TD background="images/table_top_line.gif" colspan="7" height=1></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td HEIGHT=20></td></tr>
			<tr>
				<td>
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
		echo "	<td width=\"100%\" class=\"font_size\" colspan=".$colspan." align=center>\n";
		echo "		".$a_div_prev_page.$a_prev_page.$print_page.$a_next_page.$a_div_next_page;
		echo "	</td>\n";
		echo "</tr>\n";
?>
				</table>
				</td>
			</tr>
			</form>
			<form name=form2 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=type>
			<input type=hidden name=outid>
			<input type=hidden name=block value="<?=$block?>">
			<input type=hidden name=gotopage value="<?=$gotopage?>">
			<input type=hidden name=search_start value="<?=$search_start?>">
			<input type=hidden name=search_end value="<?=$search_end?>">
			<input type=hidden name=vperiod value="<?=$vperiod?>">
			<input type=hidden name=search value="<?=$search?>">
			<input type=hidden name=s_check value="<?=$s_check?>">
			</form>
			<tr>
				<td height="30"></td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/manual_top1.gif" WIDTH=15 height="45" ALT=""></TD>
					<TD><IMG SRC="images/manual_title.gif" WIDTH=113 height="45" ALT=""></TD>
					<TD width="100%" background="images/manual_bg.gif" height="35"></TD>
					<TD background="images/manual_bg.gif"></TD>
					<td background="images/manual_bg.gif"></td>
				</TR>
				<TR>
					<TD background="images/manual_left1.gif"></TD>
					<TD COLSPAN=3 width="100%" valign="top" class=menual_bg style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;" class="menual_bg" >
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><p><span class="font_dotline">ȸ��Ż���� ID</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top"><p>- ȸ��Ż��ó�� �� ���θ��� ��ϵ� ��� ������ �����Ǹ� Ż��ȸ������ �ȳ� ������ �߼۵˴ϴ�.<br>
						<b>&nbsp;&nbsp;</b>�簡���� �ٷ� �����մϴ�. ��, Ż��� ����� ID�� ����� �� �����ϴ�.</p></td>
					</tr>
					<tr>
						<td colspan="2" height="20"></td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><p><span class="font_dotline">ȸ��Ż��� �ֹ���������</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top"><p>- ȸ��Ż��ó���� <span class="font_orange"><b>[�ش�ID���� ����]</b></span>�� <span class="font_blue"><b>[�ش�ID������ ����]</b></span>�� ���е˴ϴ�<br>
						<b>&nbsp;&nbsp;</b><span class="font_orange"><b>[�ش�ID���� ����]</b></span>&nbsp;&nbsp;&nbsp;<b>&nbsp;</b>: <span style="letter-spacing:-0.5pt;">�ش�ID�� �ֹ����� <span class="font_orange">�������� ���� ���</span> ��� ������ �����Ǿ� <span class="font_orange">�α����� �Ұ����մϴ�</span>.</span><br>
						<b>&nbsp;&nbsp;</b><span class="font_blue"><b>[�ش�ID������ ����]</b></span> : <span style="letter-spacing:-0.5pt;">�ش�ID�� �ֹ����� <span class="font_blue">������ ���</span> ID�� �������� �ʽ��ϴ�. <span class="font_blue">�α��� �� �ֹ���ȸ, ȸ������ Ȯ���� �����մϴ�</span>.</span><br>
						</p></td>
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
<?=$onload?>

<? INCLUDE "copyright.php"; ?>