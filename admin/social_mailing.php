<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "go-4";
$MenuCode = "gong";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

//����Ʈ ����
$setup[page_num] = 10;
$setup[list_num] = 15;

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

$mode = $_POST["mode"];
$up_idx = $_POST["seq"];
$up_state = $_POST["state"];

$s_column = $_POST["s_column"];
$keyword = $_POST["keyword"];
$sCondition ="";
if(strlen($s_column)>0 && strlen($keyword)>0){
	$sCondition = "AND ".$s_column." like '%".$keyword."%' ";
}


if($mode == "modify"){
	$sql="UPDATE tblsocial_mailing set state='".$up_state."' WHERE idx ='".$up_idx."'";
	mysql_query($sql, get_db_conn());
}else if($mode == "delete") {
	$sql="DELETE FROM tblsocial_mailing WHERE idx ='".$up_idx."'";
	mysql_query($sql, get_db_conn());
}


$sql = "SELECT COUNT(*) as t_count FROM tblsocial_mailing  WHERE  1=1 ".$sCondition;
$result = mysql_query($sql,get_db_conn());

$row = mysql_fetch_object($result);
$t_count = $row->t_count;
mysql_free_result($result);
$pagecount = (($t_count - 1) / $setup[list_num]) + 1;
//echo $t_count;

$sql = "SELECT * FROM tblsocial_mailing ";
$sql .="WHERE 1=1 ".$sCondition;
$sql .="ORDER BY regidate DESC ";
$sql .= "LIMIT " . ($setup[list_num] * ($gotopage - 1)) . ", " . $setup[list_num];
$result=mysql_query($sql,get_db_conn());
?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function GoPage(block,gotopage) {
	document.gongcmtFrm.seq.value= "";
	document.gongcmtFrm.mode.value= "";
	document.gongcmtFrm.block.value = block;
	document.gongcmtFrm.gotopage.value = gotopage;
	document.gongcmtFrm.submit();
}

function modifyState(obj, seq){
	for(i = 0 ;i < obj.length;i++){
		if(obj.options[i].selected == true){
			state_txt = obj.options[i].text;
			state = obj.options[i].value;
			break;
		}
	}
	if(confirm("["+state_txt+ "] ���·� �����Ͻðڽ��ϱ�?")){
		document.gongcmtFrm.seq.value= seq;
		document.gongcmtFrm.state.value= state;
		document.gongcmtFrm.mode.value= "modify";
		document.gongcmtFrm.submit();
	}
}

function deleteMailing(seq){
	if(confirm("�����Ͻðڽ��ϱ�?")){
		document.gongcmtFrm.seq.value= seq;
		document.gongcmtFrm.mode.value= "delete";
		document.gongcmtFrm.submit();
	}
}

function sendSms(){
	document.sendSmsFrm.target="sendSms";
	window.open("about:blank","sendSms","width=500,height=400,scrollbars=yes,status=no");
	document.sendSmsFrm.submit();
}

function sendMail(pcode){
	document.sendMailFrm.target="sendMail";
	window.open("about:blank","sendMail","width=760,height=600,scrollbars=yes,status=no");
	document.sendMailFrm.submit();
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
			<? include ("menu_gong.php"); ?>
			</td>

			<td></td>
			<td valign="top">

<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �Ҽȡ���� &gt; �Ҽȼ��� &gt; <span class="2depth_select">�������� ��������</span></td>
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
					<TD><IMG SRC="images/social_mailing_title.gif" ALT="�������� ��������"></TD>
					</tr><tr>
					<TD width="100%" background="images/title_bg.gif" height="21"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=20></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/social_mailing_stitle1.gif"  ALT="�������� �����ڸ���Ʈ"></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=3></td></tr>
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
					<TD width="100%" class="notice_blue">�������� �ҽ��� ������û�� ����� ������ �� �ֽ��ϴ�.</TD>
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
				<td width="100%" style="text-align:center">
				<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
				<input type=hidden name=mode>
				<input type=hidden name=block value="<?=$block?>">
				<input type=hidden name=gotopage value="<?=$gotopage?>">
				 <div style="border:1px solid #DBDBDB;width:100%;padding:10px 0;margin-top:10px;margin-bottom:10px;">
				 <select name="s_column">
				 <option value="email" <?=($s_column=="email")? "selected":""?>>�̸���</option>
				 <option value="mobile" <?=($s_column=="mobile")? "selected":""?>>�޴���</option>
				 </select>
				 <input type="text" name="keyword" value="<?=$keyword?>" style="vertical-align:middle;"> <input type="image" src="images/icon_search.gif" alt="�˻�" style="vertical-align:middle;"></div>
				 </form>
				</td>
			</tr>
			<tr><td height=20><input type="button" value="���� �ϰ��߼�" class="btnstyle"  onclick="sendMail()"> <input type="button" value="���� �ϰ��߼�" class="btnstyle"  onclick="sendSms()"></td></tr>
			<tr><td height=10></td></tr>
			<tr>
				<td>
				<form name="gongcmtFrm" method="post" action="<?=$_SERVER[PHP_SELF]?>">
				<input type=hidden name=mode value="">
				<input type=hidden name=seq value="">
				<input type=hidden name=state value="">
				<input type=hidden name=block value="<?=$block?>">
				<input type=hidden name=gotopage value="<?=$gotopage?>">
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0 style="table-layout:fixed">
				<col width=50></col>
				<col width=200></col>
				<col width=200></col>
				<col width=150></col>
				<col width=70></col>
				<col width=70></col>
				<TR>
					<TD colspan=6 background="images/table_top_line.gif"></TD>
				</TR>
				<TR align=center>
					<TD class="table_cell">��ȣ</TD>
					<TD class="table_cell1">�̸����ּ�</TD>
					<TD class="table_cell1">�޴�����ȣ</TD>
					<TD class="table_cell1">��û����</TD>
					<TD class="table_cell1">����</TD>
					<TD class="table_cell1">����</TD>
				</TR>
				<TR>
					<TD colspan="6" background="images/table_con_line.gif"></TD>
				</TR>
<?
$cnt=0;
while($row=mysql_fetch_object($result)) {
	$number = ($t_count-($setup[list_num] * ($gotopage-1))-$cnt);
	$email = $row->email;
	$mobile = $row->mobile;
	$tegidate = date("Y-m-d H:i:s", $row->regidate);
?>
				<TR align=center>
					<TD class="td_con"><?=$number?></TD>
					<TD class="td_con1"><?=$email?></TD>
					<TD class="td_con1"><?=$mobile?></TD>
					<TD class="td_con1"><?=$tegidate?></TD>
					<TD class="td_con1">
					 <select name="state<?=$row->idx?>" onchange="modifyState(this,'<?=$row->idx?>')" class="input">
					 <option value="Y" <?=($row->state =="Y")?"selected":""?>>���</option>
					 <option value="N" <?=($row->state =="N")?"selected":""?>>�̻��</option>
					 </select>
					</TD>
					<TD class="td_con1">
					 <input type="button" value="����" class="btnstyle" onclick="deleteMailing('<?=$row->idx?>');">
					</TD>
				</TR>
				<TR>
					<TD colspan="6" background="images/table_con_line.gif"></TD>
				</TR>
<?
	$cnt++;
}
?>
				</TABLE>
				</form>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
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
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/manual_top1.gif" WIDTH=15 height="45" ALT=""></TD>
					<TD><IMG SRC="images/manual_title.gif" WIDTH=113 height="45" ALT=""></TD>
					<TD width="100%" background="images/manual_bg.gif" height="35"></TD>
					<TD background="images/manual_bg.gif">&nbsp;</TD>
					<td background="images/manual_bg.gif"><IMG SRC="images/manual_top2.gif" WIDTH=18 height="45" ALT=""></td>
				</TR>
				<TR>
					<TD background="images/manual_left1.gif"></TD>
					<TD COLSPAN=3 width="100%" valign="top" class=menual_bg style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;">
					<table cellpadding="0" cellspacing="0" width="100%">
					<col width=20></col>
					<col width=></col>
					<tr>
						<td align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><span class="font_dotline">�������� ���� ����</span></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- �������� ��ǰ �����ϱ⿡�� ������û �� ������ ��µʴϴ�..</td>
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
			<form name=sendMailFrm action="social_product_mailsend.php" method=post>
			<input type=hidden name=pcode>
			</form>
			<form name=sendSmsFrm action="social_product_smssend.php" method=post>
			<input type=hidden name=pcode>
			</form>

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