<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "ma-4";
$MenuCode = "market";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$sql = "SELECT id, authkey, return_tel FROM tblsmsinfo ";
$result=mysql_query($sql,get_db_conn());
if($row=mysql_fetch_object($result)){
	$sms_id=$row->id;
	$sms_authkey=$row->authkey;
}
mysql_free_result($result);

if(strlen($sms_id)==0 || strlen($sms_authkey)==0) {
	echo "<html></head><body onload=\"alert('SMS �⺻ȯ�� �������� SMS ���̵� �� ����Ű�� �Է��Ͻñ� �ٶ��ϴ�.');location.href='market_smsconfig.php';\"></body></html>";exit;
}

$today = date("Ymd");

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
$year=$_POST["year"];
$month=$_POST["month"];
$day=$_POST["day"];
$status=$_POST["status"];
if(strlen($status)==0) $status="A";

if(empty($date))	$date = $today;
if(empty($year))	$year = substr($date,0,4);
if(empty($month))	$month = substr($date,4,2);
if(empty($day))		$day = substr($date,6,2);

$t_count=0;
$smslistdata=array();

#########################################################
#														#
#			SMS������ ��� ��ƾ �߰� (�Ϸ�)				#
#														#
#########################################################
$query="block=".$block."&gotopage=".$gotopage."&type=".$type."&year=".$year."&month=".$month."&day=".$day."&status=".$status;
$resdata=getSmssendlist($sms_id,$sms_authkey,$query);
if(substr($resdata,0,2)=="OK") {
	$tempdata=explode("=",$resdata);
	$t_count=$tempdata[1];
	$smslistdata=unserialize($tempdata[2]);
} else if(substr($resdata,0,2)=="NO") {
	$tempdata=explode("=",$resdata);
	$onload="<script>alert('".$tempdata[1]."');</script>";
} else {
	$onload="<script>alert('SMS ������ ����� �Ұ����մϴ�.\\n\\n��� �� �̿��Ͻñ� �ٶ��ϴ�.');</script>";
}


$pagecount = (($t_count - 1) / $setup[list_num]) + 1;

?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function sendsms(msg,tel){
	tmre = /RN_/g
	sqre = /sQ_/g
	dqre = /dQ_/g
	msg = msg.replace(tmre,"\r\n");
	msg = msg.replace(sqre,"'");
	msg = msg.replace(dqre,'"');
	window.open("about:blank","sms","width=200,height=200");
	document.smsform.number.value=tel;
	document.smsform.message.value=msg;
	document.smsform.type.value="sendfail";
	document.smsform.submit();
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
			<? include ("menu_market.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ���������� &gt; SMS �߼�/����  &gt; <span class="2depth_select">SMS �߼۳��� ����</span></td>
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
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<tr><td height="8"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/market_smssendlist_title.gif" ALT=""></TD>
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
					<TD width="100%" class="notice_blue">SMS �߼ۿ� ���� �󼼳����� Ȯ���� �� �ֽ��ϴ�. <span style="color:#f63; font-weight:bold;">[�߼۽��а��� ������ 1�� �ڵ����� ������ �˴ϴ�.]</span></TD>
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
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td width="750" bgcolor="#0099CC" style="padding:6pt;">
					<table cellpadding="0" cellspacing="0" width="100%" bgcolor="white">
					<tr>
						<td width="100%">
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
						<TR>
							<TD width="760" height="35" background="images/blueline_bg.gif" align="center"><b><font color="#0099CC">SMS �߼۳��� ��ȸ�ϱ�</font></b></TD>
						</TR>
						<TR>
							<TD width="726">
							<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
							<TR>
								<TD colspan="2" background="images/table_con_line.gif"></TD>
							</TR>
							<TR>
								<TD class="table_cell" width="138"><img src="images/icon_point2.gif" width="8" height="11" border="0">��ȸ�Ⱓ</TD>
								<TD class="td_con1" width="597">
<?
								$maxyear=date("Y");
								echo "<select size=1 name=year class=\"select\">\n";
								for ($i = 2006;$i <= $maxyear; $i++) {
									if($i == $year)  echo "<option value=\"".$i."\" selected>".$i."</option>\n";
									else echo "<option value=\"".$i."\">".$i."</option>\n";
								}
								echo "</select>�� ";
								echo "<select size=1 name=month class=\"select\">\n";
								for ($i = 1;$i <= 12; $i++) {
									if($i == $month)  echo "<option value=\"".$i."\" selected>".$i."</option>\n";
									else echo "<option value=\"".$i."\">".$i."</option>\n";
								}
								echo "</select>�� ";
								echo "<select size=1 name=day class=\"select\">\n";
								echo "<option value=\"ALL\"";
								if($day=="ALL") echo " selected";
								echo ">��ü</option>\n";
								for ($i = 1;$i <= 31; $i++) {
									if ($i == $day)  echo "<option value=\"".$i."\" selected>".$i."</option>\n";
									else echo "<option value=\"".$i."\">".$i."</option>\n";
								}
								echo "</select>��";
?>
								</TD>
							</TR>
							<TR>
								<TD colspan="2" background="images/table_con_line.gif"></TD>
							</TR>
							<tr>
								<TD class="table_cell" width="138"><img src="images/icon_point2.gif" width="8" height="11" border="0">ó������</TD>
								<TD class="td_con1" width="597">
<?
								echo "<select size=1 name=status class=\"select\">\n";
								$arstatus = array ("��ü����","�߼ۿϷ�","�߼۽���","�߼ۿ���");
								$statusvalue = array ("A","Y","N","M");
								for($i=0;$i<4;$i++){
									echo "<option value=\"".$statusvalue[$i]."\"";
									if($status==$statusvalue[$i]) echo " selected";
									echo ">".$arstatus[$i]."</option>\n";
								}
								echo "</select>\n";
?>
								<a href="javascript:document.form1.submit();"><img src="images/btn_search2.gif" width="50" height="25" border="0" align=absmiddle></a>
								</TD>
							</tr>
							</TABLE>
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
				<td height="30">&nbsp;</td>
			</tr>
			<tr>
				<td>
				<table cellpadding="0" cellspacing="0" width="100%">

				<?$colspan=5; ?>

				<tr>
					<td width="803">
					<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
					<TR>
						<TD><IMG SRC="images/market_smssendlist_stitle1.gif" WIDTH="192" HEIGHT=31 ALT=""></TD>
						<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
						<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
					</TR>
					</TABLE>
					</td>
				</tr>
				<tr>
					<td width="803" height=3 style="padding-bottom:3pt;" align="right"><img src="images/icon_8a.gif" width="13" height="13" border="0">�� SMS �߼۰Ǽ� : <B><?= $t_count ?></B>�� <img src="images/icon_8a.gif" width="13" height="13" border="0">���� <b><?=$gotopage?>/<?=ceil($t_count/$setup[list_num])?></b> ������</td>
				</tr>
				<tr>
					<td width="803">
					<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
					<TR>
						<TD background="images/table_top_line.gif" colspan=5></TD>
					</TR>
					<TR>
						<TD class="table_cell" width="20" align="center">No</TD>
						<TD class="table_cell1" align="center">���ۿϷ�ð�</TD>
						<TD class="table_cell1" align="center">���Ź�ȣ</TD>
						<TD class="table_cell1" width="40%" align="center">�޼���</TD>
						<TD class="table_cell1" align="center">ó������</TD>
					</TR>
					<TR>
						<TD colspan="5" background="images/table_con_line.gif"></TD>
					</TR>
<?
					$cnt=0;
					if ($t_count>0) {
						$rsltmsg=array("1"=>"TIMEOUT","A"=>"�޴��� ȣ ó����","B"=>"��������","C"=>"Power Off",
									"D"=>"�޼��� ���� ���� �ʰ�","2"=>"�߸��� ��ȭ��ȣ","a"=>"�Ͻ� ���� ����",
									"b"=>"��Ÿ �ܸ��� ����","c"=>"���� ����","d"=>"��Ÿ","e"=>"����� SMC ���� ����",
									"f"=>"IB ��ü ���� ����","g"=>"SMS ���� �Ұ� �ܸ���","h"=>"�޴��� ȣ �Ұ� ����",
									"i"=>"SMC ��ڰ� �޽��� ����","j"=>"����� ���� �޽��� Que Full");

						$patten =array("(\r)","(')","(\")");
						$replace=array("RN_","sQ_","dQ_");
						$cnt=0;
						for($i=0;$i<count($smslistdata);$i++) {
							$row=$smslistdata[$i];
							$number = ($t_count-($setup[list_num] * ($gotopage-1))-$cnt);
							$date = substr($row->tran_date,0,16);
							$date = substr($date,0,4)."/".substr($date,5,2)."/".substr($date,8,2)." (".substr($date,11,2).":".substr($date,14,2).")";
							echo "<TR>\n";
							echo "	<TD class=\"td_con2\" width=\"34\" align=\"center\">".$number."</TD>\n";
							echo "	<TD class=\"td_con1\" align=\"center\">".$date."</TD>\n";
							echo "	<TD class=\"td_con1\" align=\"center\"><B>".$row->tran_phone."</B></TD>\n";
							echo "	<TD class=\"td_con1\" width=\"40%\" align=\"center\">&nbsp;<a href=\"JavaScript:sendsms('".preg_replace($patten,$replace,$row->tran_msg)."','".$row->tran_phone."');\">".$row->tran_etc1."</a></TD>\n";
							echo "	<TD class=\"td_con1\" align=\"center\"><B>";
							if($row->tran_rslt=="0") echo "�߼� �Ϸ�";
							else if($row->tran_rslt=="") echo "<font color=#0072BC>�߼� ����</font>";
							else echo "<a href=\"JavaScript:alert('".$rsltmsg[$row->tran_rslt]."')\"><font color=#FF0000><u>�߼� ����</u></font></a>";
							echo "	</B></TD>\n";
							echo "</TR>\n";
							echo "<TR>\n";
							echo "	<TD colspan=".$colspan." background=\"images/table_con_line.gif\"></TD>\n";
							echo "</TR>\n";
							$cnt++;
						}
					}

					if ($cnt==0) {
						echo "<tr><td class=td_con2 colspan=".$colspan." align=center>���ǿ� �´� �߼۳����� �������� �ʽ��ϴ�.</td></tr>";
					}
?>
					<TR>
						<TD background="images/table_top_line.gif" colspan=5></TD>
					</TR>
					</TABLE>
					</td>
				</tr>
				<tr>
					<td width="803" height=10></td>
				</tr>
				<tr>
					<td>
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="100%" class="font_size" align="center">
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
				</table>
				</td>
			</tr>
			<tr>
				<td height="25">&nbsp;</td>
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
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td width="701"><span class="font_dotline">SMS �߼۳��� ����</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td width="701" class="space_top">- SMS �߼۳����� ���۽����� �� ��Ʈ��ũ ���¿� ���� ������ �ð��� �����ɼ� �ֽ��ϴ�.</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td width="701" class="space_top">- SMS �߼� ���е� �ǿ� ���ؼ��� 2�� �� �ϰ������� ������ �ص帳�ϴ�.</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td width="701" class="space_top">- SMS �߼� ���е� ���� [�߼۽���]�� �����ø� �߼� ������ ����� Ȯ���� �� �ֽ��ϴ�.</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td width="701" class="space_top">- SMS �߼� ���е� ���� �������� ���Ͻø� ���аǿ� ���ؼ� �������� �����մϴ�.</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td width="701" class="space_top">- "���ۿϷ�ð�"�� ��Ż�(SKT, KTF, LGT)���� �˷��� �ð����μ� ���ڸ޼����� �޴����� ������ �ð��Դϴ�.</td>
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

	<form name=form2 action="<?=$_SERVER[PHP_SELF]?>" method=post>
	<input type=hidden name=type>
	<input type=hidden name=block>
	<input type=hidden name=gotopage>
	<input type=hidden name=year value="<?=$year?>">
	<input type=hidden name=month value="<?=$month?>">
	<input type=hidden name=day value="<?=$day?>">
	<input type=hidden name=status value="<?=$status?>">
	</form>

	<form name=smsform method=post action="sendsms.php" target=sms>
	<input type=hidden name=type>
	<input type=hidden name=number>
	<input type=hidden name=message>
	</form>

	</table>
	</td>
</tr>
</table>

<?=$onload?>

<? INCLUDE "copyright.php"; ?>