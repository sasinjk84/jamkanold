<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="8"></td>
	</tr>
	<tr>
		<td>
			<table width="100%" border=0 cellpadding=0 cellspacing=0>
				<tr>
					<td><IMG SRC="images/market_bulkmail_title.gif"  ALT="��뷮���� �߼۰���"></td>
				</tr>
				<tr>
					<td width="100%" background="images/title_bg.gif" height="21"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="3"></td>
	</tr>
	<tr>
		<td style="padding-bottom:3pt;"> </td>
	</tr>
	<tr>
		<td>
			<form name="groupListForm" id="groupListForm" method="post" action="/admin/bulkmail/process.php">
			<input type="hidden" name="act" value="deleteGroups" />
			<input type="hidden" name="dgidx" value="" />
			<table border="0" cellpadding="0" cellspacing="0" class="formTbl" style="width:100%">
				<caption style="text-align:left">
					<input type="button" value="�׷��߰�" style="margin-right:5px" class="btn_groupadd" />
					<input type="button" value="�׷����" style="margin-right:5px" class="btn_groupdelete" />
				</caption>
				<tr>
					<th style="width:40px"><input type="checkbox" name="selAll" value="" /></th>
					<th style="width:150px;">�׷��</th>
					<!-- <th>ȸ����</th> -->
					<th style="width:300px;">����</th>
					<th>������</th>
					<th style="width:80px">����</th>
				</tr>
				<?
				$groupList = $bulkmail->_groupList();
				if(count($groupList) < 1){ ?>
				<tr>
					<td colspan="5" style="text-align:center">��ϵ� �׷��� �����ϴ�.</td>
				</tr>
			<?  }else{
					foreach($groupList as $group){ 
						$detail = unserialize($group['detail']);
						$detailstr = array();
						if(!empty($detail['member_group'])) $detailstr[] = 'ȸ�� �׷� : '.$detail['member_group_name'];
						if(!empty($detail['skey']) && !empty($detail['sval'])) $detailstr[] = '�˻� ���� : '.(($detail['skey'] != 'id')?($detail['skey'] == 'name')?'�̸�':'�̸���':'ID').' > '.$detail['sval'];		
						if(!empty($detail['gender'])) $detailstr[] = '�� �� : '.(($detail['gender'] == '1')?'����':'����');
						if(!empty($detail['reserve'][1])){
							if(!empty($detail['reserve'][0])) $detailstr[] = '���������� : '.number_format($detail['reserve'][0]).' ~ '.number_format($detail['reserve'][1]);
							else $detailstr[] = '���������� : '.number_format($detail['reserve'][1]).' ����';
						}else if(!empty($detail['reserve'][0])) $detailstr[] = '���������� : '.number_format($detail['reserve'][0]).' �̻�';
						
						if(!empty($detail['age'][1])){
							if(!empty($detail['age'][0])) $detailstr[] = '�� �� : '.number_format($detail['age'][0]).' ~ '.number_format($detail['age'][1]);
							else $detailstr[] = '�� �� : '.number_format($detail['age'][1]).' ����';
						}else if(!empty($detail['age'][0])) $detailstr[] = '�� �� : '.number_format($detail['age'][0]).' �̻�';
						
						if(!empty($detail['date'][1])){
							if(!empty($detail['date'][0])) $detailstr[] = '������ : '.number_format($detail['date'][0]).' ~ '.number_format($detail['date'][1]);
							else $detailstr[] = '������ : '.number_format($detail['date'][1]).' ����';
						}else if(!empty($detail['date'][0])) $detailstr[] = '������ : '.number_format($detail['date'][0]).' ����';
						
						if(!empty($detail['news_yn'])) $detailstr[] = '���� ���� : '.(($detail['news_yn'] == 'Y')?'���':'�ź�');
						if(!empty($detail['married_yn'])) $detailstr[] = '��ȥ ���� : '.(($detail['married_yn'] == 'Y')?'��ȥ':'��ȥ');
						
						if(!empty($detail['home_tel'])) $detailstr[] = '����ȭ : '.$detail['home_tel'];
						if(!empty($detail['mobile'])) $detailstr[] = '�ڵ��� : '.$detail['mobile'];
						
						if(count($detailstr) < 1) $detailstr[] = '��ü ȸ��';
				?>
				<tr>
					<td><input type="checkbox" name="selGroup[]" value="<?=$group['gidx']?>" /></td>
					<td><?=$group['gname']?></td>
					<!-- <td><?=$group['gmembers']?></td> -->
					<td><?=$group['memo']?></td>
					<td><?=implode('<br>',$detailstr)?>	</td>
					<td style="width:80px;"><input type="button" value="����" onclick="javascript:editGroup('<?=$group['gidx']?>');" /><input type="button" value="����" onclick="javascript:deleteGroup('<?=$group['gidx']?>');" /></td>
				</tr>
			<?	}
				}
				?>
			</table>
			</form>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>
			<table width="100%" border=0 cellpadding=0 cellspacing=0>
				<tr>
					<td><IMG SRC="images/manual_top1.gif" width=15 height="45" ALT=""></td>
					<td><IMG SRC="images/manual_title.gif" width=113 height="45" ALT=""></td>
					<td width="100%" background="images/manual_bg.gif" height="35"></td>
					<td background="images/manual_bg.gif"></td>
					<td background="images/manual_bg.gif"><IMG SRC="images/manual_top2.gif" width=18 height="45" ALT=""></td>
				</tr>
				<tr>
					<td background="images/manual_left1.gif"></td>
					<td COLSPAN=3 width="100%" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;" class="menual_bg"> </td>
					<td background="images/manual_right1.gif"></td>
				</tr>
				<tr>
					<td><IMG SRC="images/manual_left2.gif" width=15 HEIGHT=8 ALT=""></td>
					<td COLSPAN=3 background="images/manual_down.gif"></td>
					<td><IMG SRC="images/manual_right2.gif" width=18 HEIGHT=8 ALT=""></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="50"></td>
	</tr>
</table>
<script language="javascript" type="text/javascript">
function deleteGroup(target){
	if(target == 'sel'){		
		if($('input:checkbox[name^=selGroup]:checked').length < 1){
			alert('���õ� �׷��� �����ϴ�.');
		}else{
			$('#groupListForm>input:hidden[name=act]').val('deletegroups');
			$('#groupListForm>input:hidden[name=dgidx]').val('');
		}
		if(confirm('���� �����Ͻðڽ��ϱ�?')){
			$('#groupListForm').submit();
		}
	}else if(parseInt(target) > 0){
		$('#groupListForm>input:hidden[name=act]').val('deletegroup');
		$('#groupListForm>input:hidden[name=dgidx]').val('target');
		$('input:checkbox[name^=selGroup]').each(function(index, el) {
			$(el).removeAttr('checked');
		});
		if(confirm('���� �����Ͻðڽ��ϱ�?')){
			$('#groupListForm').submit();
		}
	}else{
		alert('�߸��� ȣ�� �Դϴ�.');
	}
}


function editGroup(gidx){
	document.location.href='/admin/bulkmail.php?act=group&md=edit&gidx='+gidx;	
}
$(function(){
	$('.btn_groupadd').click(function(e) {
		document.location.href="/admin/bulkmail.php?act=group&md=edit";
	});
	
	$('.btn_groupdelete').click(function(e) {
		if($('input:checkbox[name^=selGroup]:checked').length < 1){
			alert('���õ� �׷��� �����ϴ�.');
		}else{
			
		}
	});
	
});
</script>