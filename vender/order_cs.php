<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");
INCLUDE ("access.php");


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

$ORDER_BY .= " ORDER BY `idx` DESC";



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

function OrderDetailView(ordercode) {
	document.detailform.ordercode.value = ordercode;
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

function CheckAll(){
   chkval=document.form2.allcheck.checked;
   cnt=document.form2.tot.value;
   for(i=1;i<=cnt;i++){
      document.form2.chkordercode[i].checked=chkval;
   }
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

function AddressPrint() {
	document.sForm.action="order_address_excel.php";
	document.sForm.target="processFrame";
	document.sForm.submit();
	document.sForm.action="";
	document.sForm.target="";
}

function OrderExcel() {
	document.sForm.action="order_excel.php";
	document.sForm.target="processFrame";
	document.sForm.submit();
	document.sForm.target="";
	document.sForm.action="";
}

function OrderCheckExcel() {
	document.checkexcelform.ordercodes.value="";
	for(i=1;i<document.form2.chkordercode.length;i++) {
		if(document.form2.chkordercode[i].checked==true) {
			document.checkexcelform.ordercodes.value+=document.form2.chkordercode[i].value.substring(0)+",";
		}
	}
	if(document.checkexcelform.ordercodes.value.length==0) {
		alert("�����Ͻ� �ֹ����� �����ϴ�.");
		return;
	}
	document.checkexcelform.action="order_excel.php";
	document.checkexcelform.target="processFrame";
	document.checkexcelform.submit();
	document.checkexcelform.target="";
}

</script>
<table border=0 cellpadding=0 cellspacing=0 width=100% height="100%" style="table-layout:fixed">
<col width=190></col>
<col width=20></col>
<col width=></col>
<col width=20></col>
<tr>
	<td width=190 valign=top nowrap background="images/minishop_leftbg.gif"><? include ("menu.php"); ?></td>
	<td width=20 nowrap></td>
	<td valign=top style="padding-top:20px">

	<table width="100%"  border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>
		<table width="100%"  border="0" cellpadding="0" cellspacing="0" >
		<tr>
			<td>
				<table border=0 cellpadding=0 cellspacing=0 width=100% >
				<tr>
					<td><img src="images/order_cs_title.gif" alt="��ǰ CS ����"></td>
				</tr>
				<tr>
					<td height=5 background="images/minishop_titlebg.gif">
				</tr>
				</table>
			</td>
		</tr>
		<tr><td height=10></td></tr>
		<tr>
			<td>
				<table border=0 cellpadding=0 cellspacing=0 width=100% >
				<tr>
					<td colspan=3 >


						<table cellpadding="10" cellspacing="1" width="100%" bgcolor="#EFEFF2">
							<tr>
								<td  bgcolor="#F5F5F9" style="padding:20px">
									<table border=0 cellpadding=0 cellspacing=0 width=100%>
										<tr>
											<td class="notice_gray" height="20"><img src="images/icon_dot02.gif" border=0 hspace="4">�����翡�� ����� ��ǰ�� ���ؼ��� ��ǰ/��ȯó���� Ȯ���� �� �ֽ��ϴ�.</td>
										</tr>
										<tr>
											<td class="notice_gray" height="20"><img src="images/icon_dot02.gif" border=0 hspace="4">�ֹ���ȣ Ŭ���� �ֹ���ǰ�� ���� �������� Ȯ���� �� �ֽ��ϴ�.</td>
										</tr>
										<tr>
											<td class="notice_gray" height="20"><img src="images/icon_dot02.gif" border=0 hspace="4">���� Ŭ���� ��ǰ/��ȯ ������ ���� �������� Ȯ���� �� �ֽ��ϴ�.</td>
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

			<!-- ó���� ���� ��ġ ���� -->
			<tr><td height=40></td></tr>
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
				<col width=130></col>
				<col width=200></col>
				<col width=></col>
				<tr><td colspan=3 height=20></td></tr>
				<tr>
					<td align=right valign=bottom colspan=3>
					�� �ֹ��� : <B><?=number_format($t_count)?></B>��, &nbsp;&nbsp;
					���� <B><?=$gotopage?>/<?=ceil($t_count/$setup[list_num])?></B> ������
					</td>
				</tr>
				<tr><td colspan=3 height=1 bgcolor=#cccccc></td></tr>
				</table>

				<table border=0 cellpadding=0 cellspacing=1 width=100% bgcolor=E7E7E7 style="table-layout:fixed">
				<form name=form2 action="<?=$_SERVER[PHP_SELF]?>" method=post>
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
					$sql = "SELECT * FROM `tbl_csManager` WHERE `vender`=".$_VenderInfo->vidx.$WHERE.$ORDER_BY." LIMIT ".($setup[list_num] * ($gotopage - 1)) . ", " . $setup[list_num];
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
					<td style="padding:3;line-height:11pt" title="<?=$row['adminMemo']?>">
						<a href="order_cs_view.php?code=<?=$row['idx']?>"><?=($row['customer'] == 1)?"<font color=red>[�����]</font> ":""?><?=$row['title']?><?=($row['delivery']=="vender")?"<font color=blue>(��ü���)</font>":""?></a>
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

				<form name=detailform method="post" action="order_detail.php" target="vorderdetail">
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
			<!-- ó���� ���� ��ġ �� -->

			</table>
			</td>
		</tr>
		</table>
		</td>
	</tr>
	</table>

	</td>
</tr>

<form name=pageForm method=post action="<?=$_SERVER[PHP_SELF]?>">
<input type=hidden name=srchING value="<?=$srchING?>">
<input type=hidden name=srchOrder value="<?=$srchOrder?>">
<input type=hidden name=srchProduct value="<?=$srchProduct?>">
<input type=hidden name=srchType value="<?=$srchType?>">
<input type=hidden name=paystate value="<?=$paystate?>">
<input type=hidden name=deli_gbn value="<?=$deli_gbn?>">
<input type=hidden name=orderby value="<?=$orderby?>">
<input type=hidden name=block>
<input type=hidden name=gotopage>
</form>

</table>

<iframe name="processFrame" src="about:blank" width="0" height="0" scrolling=no frameborder=no></iframe>

<?=$onload?>

<? INCLUDE "copyright.php"; ?>