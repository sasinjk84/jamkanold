<form name="pageForm" action="<?=$_SERVER['PHP_SELF']?>" method="get">
<input type="hidden" name="page" value="<?=$_REQUEST['page']?>">
</form>
<script language="javascript" type="text/javascript">
function GoPage(page){
	document.pageForm.page.value = page;
	document.pageForm.submit();
}
</script>
<div style="width:100%; margin-top:20px;"> <img src="images/market_attendance_stitle1.gif" width="192" height="31" alt="�⼮üũ �̺�Ʈ ���" style="margin-bottom:3px;">
	<table cellspacing="0" cellpadding="0" width="100%" border="0" class="formTbl">
		<thead>
			<tr>
				<th style="height:30px; width:100px;">No</th>
				<th style="width:120px;">����</th>
				<th>�̺�Ʈ��</th>
				<th style="width:220px;">�Ⱓ</th>				
				<th style="width:80px;">ȸ����</th>
				<th style="width:80px;">�⼮��</th>
				<th style="width:120px;">����</th>
				<th style="width:120px;">����</th>
			</tr>
		</thead>
		<tbody>
			<? if($result['total'] < 1){ ?>
			<tr>
				<td colspan='8' style="height:50px; text-align:center">��ϵ� �̺�Ʈ ������ �����ϴ�.</td>
			</tr>
			<? }else{
		foreach($result['items'] as $item){ ?>
			<tr>
				<td style="text-align:center"><?=$item['vno']?></td>
				<td style="text-align:center"><?=$item['statusmsg']?></td>
				<td align='left'><?=$item['title']?></td>
				<td style="text-align:center"><?=substr($item['stdate'],0,10)?>
					~
					<?=substr($item['enddate'],0,10)?></td>
				<td align='center'><?=$item['usercnt']?></td>
				<td align='center'><?=$item['totalcnt']?></td>
				<td style="text-align:center">
					<input type="button" value="�⼮" class="viewStampBtn" aidx="<?=$item['aidx']?>" />
					<input type="button" value="����" class="viewRewardBtn" aidx="<?=$item['aidx']?>" />
				</td>
				<td style="text-align:center">
					<input type="button" value="����" class="modifyBtn" aidx="<?=$item['aidx']?>" />
				</td>
			</tr>
			<?		}
	}?>
		</tbody>
	</table>
</div>
<? $pages = new pages(array('total_page'=>$result['total_page'],'page'=>$result['page'],'pageblocks'=>10,'links'=>"javascript:GoPage('%u')"));	?>
<div style="margin-top:10px; margin-bottom:30px; padding-bottom:10px;" class="font_size">	
	<div style="width:10%; float:left;"></div>
	<div style="width:79%; float:left; text-align:center"><?	echo $pages->_solv()->_result('fulltext'); ?></div>
	<div style="width:10%; float:right; text-align:right"><button class="modifyBtn">���</button></div>
</div>
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
		<td COLSPAN="3" width="100%" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;" class="menual_bg"></td>
		<td background="images/manual_right1.gif"></td>
	</tr>
	<tr>
		<td><img src="images/manual_left2.gif" width="15" height="8" alt=""></td>
		<td COLSPAN="3" background="images/manual_down.gif"></td>
		<td><img src="images/manual_right2.gif" width="18" height="8" alt=""></td>
	</tr>
</table>

<!-- #�޴��� --> 