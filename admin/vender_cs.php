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






$csRowType = array("10"=>"�±�ȯ���","11"=>"��ǰ���� �±�ȯ���","12"=>"������߼�","13"=>"���񽺹߼�","21"=>"��ǰ����","31"=>"�������ǻ���");



// �˻�

$WHERE = "";

// ���� �˻�
switch ( $_POST['srchING'] ) {
	case "all" : // ��ü
		$WHERE .= " ";
		break;
	case "end" : // ó���Ϸ�
		$WHERE .= " AND `completeRegDate` > 0";
		break;
	case "ing" : // ��ó��
		$WHERE .= " AND `completeRegDate` = 0";
		break;
	default : // �⺻ - ��ó��
		$WHERE .= " AND `completeRegDate` = 0";
		break;
}

// �ֹ��ڵ� �˻�
if( $_POST['srchOrder'] ) {
	$_POST['srchOrder'] = str_replace(" ","",$_POST['srchOrder']);
	$WHERE .= " AND `order` = '".$_POST['srchOrder']."'";
}


// ��ǰ�ڵ� �˻�
if( $_POST['srchProduct'] ) {
	$_POST['srchProduct'] = str_replace(" ","",$_POST['srchProduct']);
	$WHERE .= " AND `product` = '".$_POST['srchProduct']."'";
}


// ��ǰ�ڵ� �˻�
if( $_POST['srchType'] ) {
	$WHERE .= " AND `type` = '".$_POST['srchType']."'";
}


// ��üó���Ϸ� �� ����
if( $_POST['venderEnd'] ) {
	$WHERE .= " AND `venderRegDate` = 0 ";
}

// ��ǰ �� ����
if( $_POST['excludeType21'] ) {
	$WHERE .= " AND `type` != '21' ";
}

// ��Ⱓ(3����) ��ó���� ����
if( $_POST['old'] ) {
	$WHERE .= " AND `adminRegDate` > '".date("YmdHis",time()-2592000)."'";
}

// ���� �˻�
if( $_POST['srchVender'] ) {
	$WHERE .= " AND `vender` = '".$_POST['srchVender']."'";
}

$ORDER_BY .= " ORDER BY `idx` DESC";



//����Ʈ ����
$setup[page_num] = 10;
$setup[list_num] = 50;

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

$sql = "SELECT * FROM `tbl_csManager` WHERE 1 ".$WHERE;
$result = mysql_query($sql,get_db_conn());
$t_count = mysql_num_rows($result);
mysql_free_result($result);
$pagecount = (($t_count - 1) / $setup[list_num]) + 1;





unset($venderlist);
$sql = "SELECT vender,id,com_name,delflag FROM tblvenderinfo ";
$result=mysql_query($sql,get_db_conn());
while($row=mysql_fetch_object($result)) {
	$venderlist[$row->vender]=$row;
}
mysql_free_result($result);

?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function searchForm() {
	document.sForm.submit();
}


function viewVenderInfo(vender) {
	window.open("about:blank","vender_infopop","width=100,height=100,scrollbars=yes");
	document.vForm.vender.value=vender;
	document.vForm.target="vender_infopop";
	document.vForm.submit();
}

function ViewCounsel(date) {
	window.open("about:blank","vendercounsel_pop","width=600,height=450,scrollbars=yes");
	document.form3.date.value=date;
	document.form3.submit();
}

function GoPage(block,gotopage) {
	document.pageForm.block.value = block;
	document.pageForm.gotopage.value = gotopage;
	document.pageForm.submit();
}


function OrderDetailView(ordercode) {
	document.detailform.ordercode.value = ordercode;
	window.open("","orderdetail","scrollbars=yes,width=700,height=600");
	document.detailform.submit();
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
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �������� &gt; ������ü ����  &gt; <span class="2depth_select">������ü CS ����</span></td>
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
					<TD><IMG SRC="images/vender_cs_title.gif" ALT="������ü CS ����"></TD>
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
					<TD width="100%" class="notice_blue">���θ� ����� ������ü���� ��ǰ�� ���� CS ������ �Ͻ� �� �ֽ��ϴ�.</TD>
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
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/vender_cs_stitle1.gif" ALT="������ü CS ���� ���"></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td>









				<table border=0 cellpadding=0 cellspacing=0 width=100%>
				<form name=sForm action="<?=$_SERVER[PHP_SELF]?>" method=post>
				<input type=hidden name=code value="<?=$code?>">
				<tr>
					<td valign=top bgcolor=D4D4D4 style=padding:1>
					<table border=0 cellpadding=0 cellspacing=0 width=100%>
					<tr>
						<td valign=top bgcolor=F0F0F0 style=padding:10>

							<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
							<tr>
								<td>
									&nbsp;<U>����</U>&nbsp;
									<select name="srchING">
										<option value="all" <?=( $_POST['srchING']=="all" )?"selected":""?>>��ü</option>
										<option value="ing" <?=( $_POST['srchING']=="ing" OR $_POST['srchING']=="" )?"selected":""?>>��ó��</option>
										<option value="end" <?=( $_POST['srchING']=="end" )?"selected":""?>>ó���Ϸ�</option>
									</select>

									&nbsp;<U>�ֹ��ڵ�</U>&nbsp;
									<input type="text" name="srchOrder" value="<?=$_POST['srchOrder']?>" style="width:150px;">

									&nbsp;<U>��ǰ�ڵ�</U>&nbsp;
									<input type="text" name="srchProduct" value="<?=$_POST['srchProduct']?>" style="width:150px;">

									&nbsp;<U>��������</U>&nbsp;
									<select class="select" name="srchType">
										<option value="" <?=( $_POST['srchType']=="" )?"selected":""?>>��ü</option>
										<option value="10" <?=( $_POST['srchType']=="10" )?"selected":""?>>�±�ȯ���</option>
										<option value="11" <?=( $_POST['srchType']=="11" )?"selected":""?>>��ǰ���� �±�ȯ���</option>
										<option value="12" <?=( $_POST['srchType']=="12" )?"selected":""?>>������߼�</option>
										<option value="13" <?=( $_POST['srchType']=="13" )?"selected":""?>>���񽺹߼�</option>
										<option value="21" <?=( $_POST['srchType']=="21" )?"selected":""?>>��ǰ����</option>
										<option value="31" <?=( $_POST['srchType']=="31" )?"checked":""?>>�������ǻ���</option>
										<option value="39" <?=( $_POST['srchType']=="39" )?"checked":""?>>��Ÿ</option>
									</select>


									&nbsp;<U>����</U>&nbsp;
									<select class="select" name="srchVender">
										<option value="" <?=( $_POST['srchVender']=="" )?"selected":""?>>��ü</option>
										<?
											foreach ( $venderlist as $var ) {
												$sel = ( $_POST['srchVender']==$var->vender )?"selected":"";
												echo "<option value='".$var->vender."' ".$sel.">".$var->com_name."(".$var->id.")</option>";
											}
										?>
									</select>

									<A HREF="javascript:searchForm()"><img src=images/btn_inquery03.gif border=0 align=absmiddle alt="AND �˻�"></A>
								</td>
							</tr>
							<tr><td height=5></td></tr>
							<tr>
								<td>
								&nbsp;
								<input type="checkbox" name="venderEnd" value="1" <?=($_POST['venderEnd'])?"checked":""?>> ��üó���Ϸ� �� ����
								<input type="checkbox" name="excludeType21" value="1" <?=($_POST['excludeType21'])?"checked":""?>> ��ǰ �� ����
								<input type="checkbox" name="old" value="1" <?=($_POST['old'])?"checked":""?>> ��Ⱓ(3����) ��ó���� ����
								</td>
							</tr>
							</table>



						</td>
					</tr>
					</table>
					</td>
				</tr>

				</form>
				</table>












				<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
					<tr><td height=20></td></tr>
					<tr>
						<td align="right" style="font-size:11px;">
							<img width="13" height="13" src="images/icon_8a.gif" border="0"/>�� �ֹ��� : <B><?=number_format($t_count)?></B>��&nbsp;
							<img width="13" height="13" src="images/icon_8a.gif" border="0"/>���� <B><?=$gotopage?>/<?=ceil($t_count/$setup[list_num])?></B> ������ &nbsp;&nbsp;
							<a href="http://www.getmall.co.kr/data/cs_manual.zip"><img src="images/btn_csmanual.gif" border="0" align="absmiddle" alt="CS���� �Ŵ���" /></a>
						</td>
					</tr>
					<tr><td height="5"></td></tr>
					<tr><td height="1" bgcolor="#cccccc"></td></tr>
				</table>

				<table border=0 cellpadding=0 cellspacing=1 width=100% bgcolor=E7E7E7 style="table-layout:fixed">
				<form name=form2 action="<?=$_SERVER[PHP_SELF]?>" method=post>
				<col width=100></col>
				<col width=50></col>
				<col width=150></col>
				<col width=120></col>
				<col width=120></col>
				<col></col>
				<col width=100></col>
				<col width=100></col>
				<col width=100></col>
				<tr height=32 align=center bgcolor=F5F5F5>
					<input type=hidden name=chkordercode>
					<td><B>����</B></td>
					<td><B>����</B></td>
					<td><B>�ֹ���ȣ</B></td>
					<td><B>����</B></td>
					<td><B>��������</B></td>
					<td><B>����</B></td>
					<td><B>�����</B></td>
					<td><B>��üó����</B></td>
					<td><B>ó���Ϸ���</B></td>
				</tr>

				<?
					$colspan=8;
					$sql = "SELECT * FROM `tbl_csManager` WHERE 1 ".$WHERE.$ORDER_BY." LIMIT ".($setup[list_num] * ($gotopage - 1)) . ", " . $setup[list_num];
					$result=mysql_query($sql,get_db_conn());
					$i=0;
					while($row=mysql_fetch_assoc($result)) {


						switch ( substr($row['type'],0,1) ) {
							case 1 : $csOrderType = "<font color='blue'>���</font>"; break;
							case 2 : $csOrderType = "<font color='red'>��ǰ</font>"; break;
							case 3 : $csOrderType = "��Ÿ"; break;
						}
				?>
				<tr bgcolor="#FFFFFF" onmouseover="this.style.background='#FEFBD1';" onmouseout="this.style.background='#FFFFFF'">
					<td align=center><?=$venderlist[$row['vender']]->com_name?>(<?=$venderlist[$row['vender']]->id?>)</td>
					<td align=center><?=$csOrderType?></td>
					<td align=center style="padding:3;line-height:11pt"><a href="javascript:OrderDetailView('<?=$row['order']?>')"><?=$row['order']?></a></td>
					<td align=center style="padding:3;line-height:11pt">
						<?
							if(substr($row['order'],-1)=="X") { //��ȸ��
								echo $row['member']." (��ȸ��)";;
							} else { // ȸ��
								$memberSQL = "SELECT `id`,`name` FROM `tblmember` WHERE `id`='".$row['member']."' LIMIT 1 ";
								$memberResult=mysql_query($memberSQL,get_db_conn());
								$memberRow=mysql_fetch_assoc ($memberResult);
								echo $memberRow['name']." (".$memberRow['id'].")";
							}
						?>
					</td>
					<td align=center style="padding:3;line-height:11pt">
						<?=$csRowType[$row['type']]?>
					</td>
					<td style="padding:7px; line-height:11pt" title="<?=$row['adminMemo']?>">
						<a href="vender_cs_view.php?code=<?=$row['idx']?>"><?=($row['customer'] == 1)?"<font color=red>[�����]</font> ":""?><?=$row['title']?><?=($row['delivery']=="vender")?"<font color=blue>(��ü���)</font>":""?></a>
					</td>
					<td align=center style="padding:3;line-height:11pt" title="<?=$row['adminRegDate']?>">
						<?=substr($row['adminRegDate'],0,10)?>
					</td>
					<td align=center style="padding:3;line-height:11pt" title="<?=$row['venderRegDate']?>">
						<?=($row['venderRegDate'] > 0)?substr($row['venderRegDate'],0,10):"-"?>
					</td>
					<td align=center style="padding:3;line-height:11pt" title="<?=$row['completeRegDate']?>">
						<?=($row['completeRegDate'] > 0)?substr($row['completeRegDate'],0,10):"-"?>
					</td>
				</tr>
				<?
						$i++;
					}



				mysql_free_result($result);
				$cnt=$i;
				if($i==0) {
					echo "<tr height=28 bgcolor=#FFFFFF><td colspan=".$colspan." align=center>��ȸ�� ������ �����ϴ�.</td></tr>\n";
				} else if($i>0) {
					$total_block = intval($pagecount / $setup[page_num]);
					if (($pagecount % $setup[page_num]) > 0) {
						$total_block = $total_block + 1;
					}
					$total_block = $total_block - 1;
					if (ceil($t_count/$setup[list_num]) > 0) {
						// ����	x�� ����ϴ� �κ�-����
						$a_first_block = "";
						if ($nowblock > 0) {
							$a_first_block .= "<a href='javascript:GoPage(0,1);' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='ù ������';return true\"><img src=".$Dir."images/minishop/btn_miniprev_end.gif border=0 align=absmiddle></a> ";
							$prev_page_exists = true;
						}
						$a_prev_page = "";
						if ($nowblock > 0) {
							$a_prev_page .= "<a href='javascript:GoPage(".($nowblock-1).",".($setup[page_num]*($block-1)+$setup[page_num]).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup[page_num]." ������';return true\"><img src=".$Dir."images/minishop/btn_miniprev.gif border=0 align=absmiddle></a> ";

							$a_prev_page = $a_first_block.$a_prev_page;
						}
						if (intval($total_block) <> intval($nowblock)) {
							$print_page = "";
							for ($gopage = 1; $gopage <= $setup[page_num]; $gopage++) {
								if ((intval($nowblock*$setup[page_num]) + $gopage) == intval($gotopage)) {
									$print_page .= "<FONT color=red><B>".(intval($nowblock*$setup[page_num]) + $gopage)."</B></font> ";
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
									$print_page .= "<FONT color=red><B>".(intval($nowblock*$setup[page_num]) + $gopage)."</B></FONT> ";
								} else {
									$print_page .= "<a href='javascript:GoPage(".$nowblock.",".(intval($nowblock*$setup[page_num]) + $gopage).");' onMouseOver=\"window.status='������ : ".(intval($nowblock*$setup[page_num]) + $gopage)."';return true\">[".(intval($nowblock*$setup[page_num]) + $gopage)."]</a> ";
								}
							}
						}
						$a_last_block = "";
						if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
							$last_block = ceil($t_count/($setup[list_num]*$setup[page_num])) - 1;
							$last_gotopage = ceil($t_count/$setup[list_num]);
							$a_last_block .= " <a href='javascript:GoPage(".$last_block.",".$last_gotopage.");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='������ ������';return true\"><img src=".$Dir."images/minishop/btn_mininext_end.gif border=0 align=absmiddle></a>";
							$next_page_exists = true;
						}
						$a_next_page = "";
						if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
							$a_next_page .= " <a href='javascript:GoPage(".($nowblock+1).",".($setup[page_num]*($nowblock+1)+1).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup[page_num]." ������';return true\"><img src=".$Dir."images/minishop/btn_mininext.gif border=0 align=absmiddle></a>";
							$a_next_page = $a_next_page.$a_last_block;
						}
					} else {
						$print_page = "<B>1</B>";
					}
					$pageing=$a_div_prev_page.$a_prev_page.$print_page.$a_next_page.$a_div_next_page;
				}
?>
				<input type=hidden name=tot value="<?=$cnt?>">
				</form>

				<form name=detailform method="post" action="order_detail.php" target="orderdetail">
				<input type=hidden name=ordercode>
				</form>


				</table>

				<table border=0 cellpadding=0 cellspacing=0 width=100%>
				<tr>
					<td align=center style="padding-top:10"><?=$pageing?></td>
				</tr>
				</table>









				</td>
			</tr>
			<tr><td height=2></td></tr>
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
					<TD COLSPAN=3 width="100%" valign="top" class="menual_bg" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><span class="font_dotline">������ü ���Խ��� ����</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- ���Խ����� ����� �����簣�� 1:1�Խ��� �Դϴ�.</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- ������ ���̵� Ȯ�� [����]Ŭ���� �亯ó�� �� �� �ֽ��ϴ�.</td>
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

<form name=vForm action="vender_infopop.php" method=post>
<input type=hidden name=vender>
</form>

<form name=pageForm action="<?=$_SERVER[PHP_SELF]?>" method=post>
<input type=hidden name=scheck value="<?=$scheck?>">
<input type=hidden name=search value="<?=$search?>">
<input type=hidden name=block value="<?=$block?>">
<input type=hidden name=gotopage value="<?=$gotopage?>">
</form>

<form name=form3 action="vender_counsel_pop.php" method=post target="vendercounsel_pop">
<input type=hidden name=date>
</form>

</table>

<?=$onload?>

<? INCLUDE "copyright.php"; ?>