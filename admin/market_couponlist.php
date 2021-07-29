<?
$Dir="../";

include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

include ("access.php");
include_once $Dir.'lib/class/coupon.php';
include_once $Dir.'lib/class/pages.php';
####################### ������ ���ٱ��� check ###############
$PageCode = "ma-3";
$MenuCode = "market";
if (!$_usersession->isAllowedTask($PageCode)) {
	include ("AccessDeny.inc.php");
	exit;
}

#########################################################

$coupon = new coupon();
$imagepath=$Dir.DataDir."shopimages/etc/";

switch($_POST['type']){
	case 'stop':
		$result = $coupon->_stop($_POST['coupon_code']);		
		if($result['result']) $onload="<script>alert('�ش� ������ ���ؼ� �߱����� ó���� �Ϸ�Ǿ����ϴ�.\\n\\n���� �߱޵� ������ ��밡���մϴ�.');</script>";
		break;
	case 'delete':
		$result = $coupon->_delete($_POST['coupon_code']);
		print_r($result);
		if($result['result']) $onload="<script>alert('�ش� ������ ��� ������ ���� �����Ǿ����ϴ�.');</script>";
		break;
	case 'issueagain':
		$result = $coupon->_issueRe($_POST['coupon_code'],$_POST['uid']);
		if($result['result']) $onload="<script>alert('".$uid." ȸ���Բ� �ش� ������ ��߱� �Ǿ����ϴ�.');</script>";
		break;
	case 'issuedelete':
		$result = $coupon->_issueDelete($_POST['coupon_code'],$_POST['uid']);
		if($result['result']) $onload="<script>alert('".$uid." ȸ���Կ��� �߱޵� ������ �����Ǿ����ϴ�.');</script>";
		break;
}

$_REQUEST['issue_type'] = '!OP';
$result = $coupon->_couponList($_REQUEST);
$issues = $coupon->_issueList(array('coupon_code'=>$_REQUEST['coupon_code'],'page'=>$_REQUEST['issue_page'],'search'=>$_REQUEST['issue_search']));
?>
<? include "header.php"; ?>
<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function CouponView(code) {
	window.open("about:blank","couponview","width=650,height=650,scrollbars=no");
	document.cform.coupon_code.value=code;
	document.cform.submit();
}

function CouponIssue(code){
	document.listForm.coupon_code.value=code;
	document.listForm.issue_page.value="1";	
	document.listForm.submit();
}

function CouponStop(code) {
	if(confirm("���� ȸ������ �߱޵� ������ ����� �����մϴ�.\n\n�ش� ���� �߱��� �����Ͻðڽ��ϱ�?")) {
		document.listForm.coupon_code.value=code;
		document.listForm.method="post";
		document.listForm.type.value="stop";
		document.listForm.submit();
	}
}

function CouponDelete(code) {
	if(confirm("���� ȸ������ �߱޵� �������� ��� �����˴ϴ�.\n\n�ش� ������ ���� �����Ͻðڽ��ϱ�?")) {
		document.listForm.coupon_code.value=code;
		document.listForm.type.value="delete";
		document.listForm.submit();
	}
}

function IssueCouponAgain(code,uid) {
	if(confirm(uid+" ȸ���Կ��� ������ ��߱� �Ͻðڽ��ϱ�?")) {
		document.listForm.coupon_code.value=code;
		document.listForm.uid.value=uid;
		document.listForm.type.value="issueagain";
		document.listForm.submit();
	}
}

function IssueCouponDelete(code,uid) {
	if(confirm(uid+" ȸ���Կ��� �߱��� ������ �����Ͻðڽ��ϱ�?")) {
		document.listForm.coupon_code.value=code;
		document.listForm.uid.value=uid;
		document.listForm.type.value="issuedelete";
		document.listForm.submit();
	}
}

function GoPage(page) {
	document.listForm.type.value = "";
	document.listForm.coupon_code.value = "";
	document.listForm.uid.value = "";
	document.listForm.page.value = page;
	document.listForm.issue_page.value="";
	//document.listForm.issue_search.value="";
	document.listForm.submit();
}

function GoPage2(page) {
	document.listForm.type.value = "";
	document.listForm.uid.value = "";
	document.listForm.issue_page.value = page;
	document.listForm.submit();
}

function id_search() {
	document.listForm.type.value='';
	document.listForm.uid.value='';
	document.listForm.submit();
}

function search_default() {
	document.listForm.type.value='';
	document.listForm.uid.value='';
	document.listForm.search.value='';
	document.listForm.submit();
}

</script>
<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed"  background="images/con_bg.gif">	
	<tr>
		<td valign="top" style="width:198px; background:url(images/leftmenu_bg.gif)">	
			<? include ("menu_market.php"); ?>	
		</td>
		<td style="width:10px;"></td>
		<td valign="top">
			<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td height="29" colspan="3" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ���������� &gt; �������� ���� ���� &gt; <span class="2depth_select">�߱޵� ���� ��������</span></td>
				</tr>
				<tr>
					<td width="16"><img src="images/con_t_01.gif" width="16" height="16" border="0"></td>
					<td background="images/con_t_01_bg.gif"></td>
					<td width="16"><img src="images/con_t_02.gif" width="16" height="16" border="0"></td>
				</tr>
				<tr>
					<td width="16" background="images/con_t_04_bg1.gif"></td>
					<td bgcolor="#ffffff" style="padding:10px">
						<div style="width:100%; background:url(images/title_bg.gif) left bottom repeat-x; margin-top:8px; margin-bottom:3px; padding-bottom:21px;"> <img src="images/market_couponlist_title.gif" alt="�߱޵� ���� ��������"> </div>
						<span class="notice_blue" style=" display:block; padding-left:22px;">���� �������� ���������� ������ Ȯ���� �� �ִ� �޴� �Դϴ�.</span>
						<div style="width:100%; margin-top:20px;"> <img src="images/market_couponlist_stitle1.gif" width="192" height="31" alt="�߱޵� ���� ����" style="margin-bottom:3px;">
							<table cellspacing="0" cellpadding="0" width="100%" border="0" style="table-layout:fixed">
								<col width="50" />
								<col width="75" />
								<col width="" />
								<col width="100" />
								<col width="105" />
								<col width="70" />
								<col width="80" />
								<col width="90" />
								<tr>
									<td colspan="8" background="images/table_top_line.gif"></td>
								</tr>
								<tr align="center">
									<td class="table_cell">No</td>
									<td class="table_cell1">�����ڵ�</td>
									<td class="table_cell1">������</td>
									<td class="table_cell1">����/����</td>
									<td class="table_cell1">��ȿ�Ⱓ</td>
									<td class="table_cell1">�߱޳���</td>
									<td class="table_cell1">�߱�����</td>
									<td class="table_cell1"><span style="color:red; font-weight:bold">��������</span></td>
								</tr>
								<tr>
									<td colspan="8" background="images/table_con_line.gif"></td>
								</tr>
								<? if($result['total'] < 1){ ?>
								<tr>
									<td class='td_con2' colspan='8' align='center'>�߱��� ���������� �����ϴ�.</td>
								</tr>
								<? }else{ 
										foreach($result['items'] as $item){ ?>
								<tr align='center'>
									<td class="td_con2"><?=$item['vno']?></td>
									<td class="td_con1"><a href="javascript:CouponView('<?=$item['coupon_code']?>');" style="font-weight:bold"><?=$item['coupon_code']?></a></td>
									<td align='left' class="td_con1"><?=$item['coupon_name']?></td>
									<td class="td_con1"><span class="<?=(($item['sale']=="����")?"font_orange":"font_blue")?>" style="font-weight:bold"><NOBR><?=number_format($item['sale_money']).$item['dan']." ".$item['sale']?><NOBR></span></td>
									<td class="td_con1"><NOBR><?=$item['date']?></NOBR></td>
									<td class="td_con1"><a href="javascript:CouponIssue('<?=$item['coupon_code']?>');"><img src="images/btn_search2.gif" border="0"></a></td>
									<td class="td_con1">
										<? if($item['issue_type']!="D"){ ?>
										<a href="javascript:CouponStop('<?=$item['coupon_code']?>');"><img src="images/btn_stop.gif" border="0"></a>
										<? }else{  echo '&nbsp;';} ?>
									</td>
									<td class="td_con1"><a href="javascript:CouponDelete('<?=$item['coupon_code']?>');"><img src="images/btn_del7.gif" border="0"></a></td>
								</tr>
								<tr>
									<td colspan="8" background="images/table_con_line.gif"></td>
								</tr>
								<?		}		
									}?>
								<tr>
									<td colspan="8" background="images/table_top_line.gif"></td>
								</tr>
							</table>
						</div>
						<div style="text-align:center; margin-top:10px; margin-bottom:30px;" class="font_size">
					<?			$pages = new pages(array('total_page'=>$result['total_page'],'page'=>$result['page'],'pageblocks'=>10,'links'=>"javascript:GoPage('%u')"));
							echo $pages->_solv()->_result('fulltext'); ?>
						</div>
						<form name="listForm" action="<?=$_SERVER['PHP_SELF']?>" method="POST">
								<input type=hidden name="type" value="list" />
								<input type=hidden name="coupon_code" value="<?=$_REQUEST['coupon_code']?>" />
								<input type=hidden name="uid" value="" />
								<input type=hidden name="page" value="<?=$_REQUEST['page']?>">
								<input type=hidden name="issue_page" value="<?=$_REQUEST['issue_page']?>">
						
						<? // ���� ����Ʈ �� ��û�� ���
											if(!is_null($issues) && is_array($issues) && count($issues) >0){ ?>
						<div><img src="images/market_couponlist_stitle2.gif" width="192" height="31" alt="�߱޹��� ȸ�� ����"></div>
						<div style="text-align:right; padding-bottom:3px"> <img src="images/icon_cuponname.gif" width="44" height="16" border="0" align=absmiddle><span class="font_orange" style="font-weight:bold">
							<?=$issues['coupon_name']?>
							</span>&nbsp; <img src="images/icon_cupon_bal.gif" width="35" height="16" border="0" align=absmiddle><span style="font-weight:bold">
							<?=number_format($issues['issuetotal'])?>
							</span>�� <img src="images/icon_cupon_use.gif" width="35" height="16" border="0" align=absmiddle>
							<?=number_format($issues['usenum'])?>
							�� </div>
						<div>
							<table cellspacing="0" cellpadding="0" width="100%" border="0">
								<tr>
									<td colspan="6" background="images/table_top_line.gif"></td>
								</tr>
								<tr align=center>
									<td class="table_cell" style="width:30px;">No</td>
									<td class="table_cell1">���̵�</td>
									<td class="table_cell1" style="width:135px;">�߱���</td>
									<td class="table_cell1" style="width:135px;">��ȿ�Ⱓ</td>
									<td class="table_cell1" style="width:100px">��뿩��</td>
									<td class="table_cell1" style="width:100px; font-weight:bold">���</td>
								</tr>
								<tr>
									<td colspan="6" background="images/table_con_line.gif"></td>
								</tr>
								<? if($issues['total'] < 1){ ?>
								<tr>
									<td class="td_con2" colspan="6" align="center">ȸ������ �߱޵� ���������� �����ϴ�.</td>
								</tr>
								<? }else{
													foreach($issues['items'] as $item){ 
														$regdate = substr($item['date'],0,4)."/".substr($item['date'],4,2)."/".substr($item['date'],6,2);
														$date = substr($item['date_start'],0,4).".".substr($item['date_start'],4,2).".".substr($item['date_start'],6,2)." ~ ".substr($item['date_end'],0,4).".".substr($item['date_end'],4,2).".".substr($item['date_end'],6,2);
											?>
								<tr align=center>
									<td class="td_con2">
										<?=$item['vno']?>
									</td>
									<td class="td_con1">
										<?=$item['id']?>
									</td>
									<td class="td_con1">
										<?=$regdate?>
									</td>
									<td class="td_con1">
										<?=$date?>
									</td>
									<td class="td_con1">
										<?=(($item['used'] == 'Y')?'<span class="font_blue">�����</span>':'<span class="font_orange">�̻��</span>')?>
									</td>
									<td class="td_con1"><a href="javascript:<?=(($item['used'] == 'Y')?'IssueCouponAgain':'IssueCouponDelete')?>('<?=$item['coupon_code']?>','<?=$item['id']?>');"><img src="images/<?=(($item['used'] == 'Y')?'btn_again.gif':'btn_del7.gif')?>" border="0"></a></td>
								</tr>
								<tr>
									<td colspan="6" background="images/table_con_line.gif"></td>
								</tr>
								<? } 
												}?>
								<tr>
									<td colspan="8" background="images/table_top_line.gif"></td>
								</tr>
							</table>
							<?	if($issues['total'] >0){ //����¡ ?>
							<div style="text-align:center; margin-top:10px; margin-bottom:30px;" class="font_size">
							<?
								echo $pages->_attr(array('total_page'=>$issues['total_page'],'page'=>$issues['page'],'pageblocks'=>10,'links'=>"javascript:GoPage2('%u')"))->_solv()->_result('fulltext'); ?>
							</div>
							<?												} // ����¡ �� ?>
							
								<div style="border:1px solid #DBDBDB; width:100%; text-align:center; padding:5px 0px;">
									<div style="width:895px; height:1px; clear:both; font-size:1px; overflow:hidden"></div>
									���̵� �˻�
									<input class="input" size="30" name="issue_search" value="<?=$_REQUEST['issue_search']?>" style="margin-left:5px; margin-right:5px" />
									<a href="javascript:id_search();"><img src="images/icon_search.gif" alt="�˻�" align="absmiddle" border="0"></a><a href="javascript:search_default();"><img src="images/icon_search_clear.gif" align="absmiddle" border="0" width="68" height="25" hspace="2" alt="�˻��ʱ�ȭ"></a> </div>							
						</div>
						<? } // ���� �߱� ���� ��?>
						</form>
						<form name=cform action="coupon_view.php" method=post target=couponview>
							<input type=hidden name=coupon_code>
						</form>
						
						<!-- �޴��� -->
						<table width="100%" BORDER="0" CELLPADDING="0" CELLSPACING="0">
							<tr>
								<td><img src="images/manual_top1.gif" width="15" height="45" alt=""></td>
								<td><img src="images/manual_title.gif" width="113" height="45" alt=""></td>
								<td width="100%" background="images/manual_bg.gif" height="35"></td>
								<td background="images/manual_bg.gif"></td>
								<td background="images/manual_bg.gif"><img src="images/manual_top2.gif" width="18" height="45" alt=""></td>
							</tr>
							<tr>
								<td background="images/manual_left1.gif"></td>
								<td COLSPAN="3" width="100%" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;" class="menual_bg">
									<table cellpadding="0" cellspacing="0" width="100%">										
										<tr>
											<td align="right" valign="top" style="width:20px;"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
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
								</td>
								<td background="images/manual_right1.gif"></td>
							</tr>
							<tr>
								<td><img src="images/manual_left2.gif" width="15" height="8" alt=""></td>
								<td COLSPAN="3" background="images/manual_down.gif"></td>
								<td><img src="images/manual_right2.gif" width="18" height="8" alt=""></td>
							</tr>
						</table>
						
						<!-- #�޴��� --> 
					</td>
					<td width="16" background="images/con_t_02_bg.gif"></td>
				</tr>
				<tr>
					<td width="16"><img src="images/con_t_04.gif" width="16" height="16" border="0"></td>
					<td background="images/con_t_04_bg.gif"></td>
					<td width="16"><img src="images/con_t_03.gif" width="16" height="16" border="0"></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<?=$onload?>
<? include "copyright.php"; ?>