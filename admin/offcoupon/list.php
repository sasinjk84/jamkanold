<script language="javascript" type="text/javascript">
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

function id_search() {
	document.listForm.type.value='';
	document.listForm.uid.value='';
	document.listForm.submit();
}


function CouponDelete(code) {
	if(confirm("���� ȸ������ �߱޵� �������� ��� �����˴ϴ�.\n\n�ش� ������ ���� �����Ͻðڽ��ϱ�?")) {
		document.listForm.coupon_code.value=code;
		document.listForm.act.value="delete";
		document.listForm.action = '/admin/offcoupon/process.php';
		document.listForm.submit();
	}
}

function CouponExcel(code){
	document.excelDownloadF.coupon_code.value = code;
	document.excelDownloadF.submit();
}

$(function(){
	$(document).on('click','.newOffCouponBtn',function(){
		document.location.href = '?mode=new';
	});
});
</script>
<div style="width:100%; background:url(images/title_bg.gif) left bottom repeat-x; margin-top:8px; margin-bottom:3px; padding-bottom:21px;"> <img src="images/market_offlinecoupon_title.gif" alt="�������� ���� ����"> </div>
<span class="notice_blue" style=" display:block; padding-left:22px;">���� �������� ���������� ������ Ȯ���� �� �ִ� �޴� �Դϴ�.</span>
<div style="width:100%; margin:20px 0px 5px 0px;"><img src="images/market_couponlist_stitle1.gif" width="192" height="31" alt="�߱޵� ���� ����" style="margin-bottom:3px;"><input type="image" src="images/btn_newcoupon.gif" class="newOffCouponBtn" style="float:left"></div>
	<table cellspacing="0" cellpadding="0" width="100%" border="0" style="table-layout:fixed">
		<col width="50" />
		<col width="75" />
		<col width="" />
		<col width="100" />
		<col width="150" />
		<col width="70" />
		<col width="70" />
		<col width="80" />
		<col width="90" />
		<tr>
			<td colspan="9" background="images/table_top_line.gif"></td>
		</tr>
		<tr align="center">
			<td class="table_cell">No</td>
			<td class="table_cell1">�����ڵ�</td>
			<td class="table_cell1">������</td>
			<td class="table_cell1">����/����</td>
			<td class="table_cell1">��ȿ�Ⱓ</td>
			<td class="table_cell1">�ѹ����</td>
			<td class="table_cell1">��������</td>
			<td class="table_cell1">ExcelDown</td>
			<td class="table_cell1"><span style="color:red; font-weight:bold">��������</span></td>
		</tr>
		<tr>
			<td colspan="9" background="images/table_con_line.gif"></td>
		</tr>
		<? if($result['total'] < 1){ ?>
		<tr>
			<td class='td_con2' colspan='9' align='center'>�߱��� ���������� �����ϴ�.</td>
		</tr>
		<? }else{ 			
				foreach($result['items'] as $item){ ?>
		<tr align='center'>
			<td class="td_con2"><?=$item['vno']?></td>
			<td class="td_con1"><a href="javascript:CouponView('<?=$item['coupon_code']?>');" style="font-weight:bold"><?=$item['coupon_code']?></a></td>
			<td align='left' class="td_con1"><?=$item['coupon_name']?></td>
			<td class="td_con1"><span class="<?=(($item['sale']=="����")?"font_orange":"font_blue")?>" style="font-weight:bold"><NOBR><?=number_format($item['sale_money']).$item['dan']." ".$item['sale']?><NOBR></span></td>
			<td class="td_con1"><NOBR><?=$item['range']?></NOBR></td>
			<td class="td_con1"><?=number_format($item['issue_tot_no'])?></td>
			<td class="td_con1"><a href="javascript:CouponIssue('<?=$item['coupon_code']?>');"><img src="images/btn_search2.gif" border="0"></a></td>
			<td class="td_con1">
				<? if($item['issue_type'] == 'P'){ ?>
				<a href="javascript:CouponExcel('<?=$item['coupon_code']?>');"><img src="images/btn_excel.gif" height="20" border="0"></a>
				<? }else{ ?>
				--
				<? } ?>
			</td>
			<td class="td_con1"><a href="javascript:CouponDelete('<?=$item['coupon_code']?>');"><img src="images/btn_del7.gif" border="0"></a></td>
		</tr>
		<tr>
			<td colspan="9" background="images/table_con_line.gif"></td>
		</tr>
		<?		}		
			}?>
		<tr>
			<td colspan="9" background="images/table_top_line.gif"></td>
		</tr>
	</table>
</div>
<div style="text-align:center; margin-top:10px; margin-bottom:30px;" class="font_size">
<?			$pages = new pages(array('total_page'=>$result['total_page'],'page'=>$result['page'],'pageblocks'=>10,'links'=>"javascript:GoPage('%u')"));
	echo $pages->_solv()->_result('fulltext'); ?>
</div>
<form name="listForm" action="<?=$_SERVER['PHP_SELF']?>" method="POST">
		<input type=hidden name="type" value="list" />
		<input type="hidden" name="act" value="" />
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
			<td class="table_cell1" style="width:135px;">������</td>
			<td class="table_cell1" style="width:135px;">��ȿ�Ⱓ</td>
			<td class="table_cell1" style="width:100px">��뿩��</td>			
		</tr>
		<tr>
			<td colspan="5" background="images/table_con_line.gif"></td>
		</tr>
		<? if($issues['total'] < 1){ ?>
		<tr>
			<td class="td_con2" colspan="6" align="center">�������� ������ �����ϴ�.</td>
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
		echo $pages->_attr(array('total_page'=>$issues['total_page'],'page'=>$issues['page'],'pageblocks'=>10,'links'=>"javascript:GoPage2(''%u')"))->_solv()->_result('fulltext'); ?>
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

<form name="excelDownloadF" action="/admin/offcoupon/process.php">
	<input type="hidden" name="act" value="exceldown">
	<input type=hidden name="coupon_code">
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
					<td class="space_top">- [��ȸ] ��ư Ŭ���� �ش� ������ �������� ȸ���� Ȯ���� �� �ֽ��ϴ�.<br>
						<b>&nbsp;&nbsp;</b>�߱޹��� ȸ���������� [����] ��ư Ŭ���� �ش� ������ ���� �˴ϴ�.</td>
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