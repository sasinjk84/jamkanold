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
		<script language="javascript" type="text/javascript">
		function mailDetail(cpid){
			window.open('/admin/bulkmail/result_detail.php?cpid='+cpid, 'resultDetail', 'width=700,height=700,status=yes,resizable=yes,scrollbars=yes');
		}
		function goSearchPage(p){
			document.searchPageForm.page.value = p;
			document.searchPageForm.submit();
		}
		</script>
			<?			
			$result = json_decode($bulkmail->summary($_REQUEST));
			if(is_object($result)){
				$result = @json_decode(@json_encode($result),1);
			}
			if(!empty($result['errmsg'])){ ?>
			<div style="padding:10 0px; text-align:center; color:red">
			<?=$result['errmsg']?>
			</div>
		<?	}else{
				$linkstr = "javascript:goSearchPage('%u')";
				$pageSet = array('page'=>$result['page'],'total_page'=>$result['total_page'],'links'=>$linkstr,'pageblocks'=>$cond['page_num'],'style_pages'=>'%u', // �Ϲ� ������ 
					'style_page_sep'=>'&nbsp;.&nbsp;');
				$Opage = new pages($pageSet);
				$Opage->_solv();				
		?>
			<form name="searchPageForm" method="get" action="<?=$_SERVER['PHP_SELF']?>">
			<? foreach($_REQUEST as $key=>$val){ 
					if($key == 'page') continue;
					if(is_array($val)){
						for($i=0;$i<count($val);$i++){ ?>
				<input type="hidden" name="<?=$key?>[]" value="<?=$val[$i]?>" />			
			<?			}
					}else{ ?>	
				<input type="hidden" name="<?=$key?>" value="<?=$val?>" />
				
			<? 		}
				}
			 ?>
			<input type="hidden" name="page" value="1"  />
			</form>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="formTbl" style="margin-bottom:5px;">
				<tr>
					<th style="width:15%">�ѰǼ�</th>
					<td><?=@number_format($result['total'])?></td>
					<th style="width:15%">�� �߼� �Ǽ�</th>
					<td><?=@number_format($result['send_total'])?></td>					
				</tr>
				<tr>
					<th>������</th>
					<td><?=@round($result['success']/$result['send_total']*100,2)?>%</td>
					<th>������</th>
					<td><?=@round($result['fail']/$result['send_total']*100,2)?>%</td>					
				</tr>
			</table>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="formTbl">
				<tr>
					<th style="height:28px;">����</th>
					<th>�ۼ���</th>
					<th>�߼��Ͻ�</th>
					<th>�ۼ��Ͻ�</th>
					<th>�ѹ߼۷�(����/����)</th>
					<th>����</th>
					<th>Ŭ��</th>
					<th>����</th>
				</tr>
		<?		foreach($result['items'] as $item){ 
					foreach($item as $key=>$val) $item[$key] = (strlen(trim($val)))?urldecode($val):'&nbsp;';
					if($item['state'] == '7') $subject = '<a href="javascript:mailDetail(\''.$item['id'].'\')" style="color:blue">'.$item['campaign_title'].'</a>';
					else $subject = $item['campaign_title'];
		?>
				<tr>
					<td style=" padding-left:3px;"><?=$subject?></td>
					<td style="text-align:center"><?=$item['writer']?></td>
					<td style="text-align:center"><?=$item['send_time']?></td>
					<td style="text-align:center"><?=$item['regist_date']?></td>
					<td style="text-align:center"><?=@number_format($item['send_total'])?> (<span style="color:blue"><?=@number_format($item['success_total'])?></span>/<span style="color:red"><?=@number_format($item['fail_total'])?></span>)</td>
					<td style="text-align:center"><?=@number_format($item['open_total'])?> (<?=@number_format($item['open_total']*100/$item['send_total'],2,'.','')?>%)</td>
					<td style="text-align:center"><?=@number_format($item['click_total'])?> (<?=@number_format($item['click_total']*100/$item['send_total'],2,'.','')?>%)</td>
					<td style="text-align:center"><?=$bulkmail->_statemsg($item['state'])?></td>
				</tr>
		<?		}  // end foreach?>
			</table>
			<div style="text-align:center; margin-top:10px"><?=$Opage->_result('fulltext')?></div>
		<?	} ?>			
		</td>
	</tr>
	<tr>
		<td>
			
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" border=0 cellpadding=0 cellspacing=0 style="margin-top:10px;">
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
