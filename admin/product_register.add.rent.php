<?php
/**
 * Created by PhpStorm.
 * User: x2chi-objet
 * Date: 2014-10-22
 * Time: ���� 9:48
 */
$rentProduct = rentProduct($_data->pridx);
if(_array($rentProduct) ){
	//_pr($rentProduct);
	//$goodsTypeSel['2'] = "checked";
	$itemTypeSel[$rentProduct['itemType']] = "checked";
	$localSel[$rentProduct['location']] = "checked";
} else {
	//$goodsTypeSel['1'] = "checked";
	$itemTypeSel['product'] = "checked";
	$localSel['0'] = "checked";
}

if($_data->rental == '2') $goodsTypeSel[$_data->rental] = "checked";
else $goodsTypeSel[1] = "checked";
?>
<TR>
	<TD colspan="4" style="height:1px" background="images/table_con_line.gif"></TD>
</TR>
<TR>
	<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">��ǰ ����</TD>
	<TD class="td_con1" colspan="3" style="padding:0px;">
	<script language="javascript" type="text/javascript">
	function toggleGoodsType(val){
		if(val == '2'){ // ��Ż ��ǰ
			goodsTypeLocalDiv.style.display = 'block';
		}else{
			goodsTypeLocalDiv.style.display = 'none';
		}
	}
	</script>
		<div style="height:24px;">
		<? if(_isInt($_data->pridx)){ ?>
			<input type="hidden" name="goodsType" value="<?=$_data->rental?>" />
			<? echo ($_data->rental=='2')?'�뿩��ǰ':'�ǸŻ�ǰ'; ?><a href="javascript:document.location.reload()">[Refresh]</a>
		<?	}else{ ?>
			<input type=radio id="goodsType1" name="goodsType" value="1" <?=$goodsTypeSel['1']?> onclick="toggleGoodsType('1');"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=goodsType1>�ǸŻ�ǰ</label> &nbsp;
			<input type=radio id="goodsType2" name="goodsType" value="2" <?=$goodsTypeSel['2']?> onclick="toggleGoodsType('2'); "><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=goodsType2>�뿩��ǰ</label><a href="javascript:document.location.reload()">[Refresh]</a>
		<?	} ?>
		</div>

		<div id="goodsTypeLocalDiv" style="display:<?=($goodsTypeSel['2']=="checked"?"block":"none")?>;border:2px solid #444444;margin:5px 2px 5px 7px;padding:15px;">
			<table border="0" cellpadding="0" cellspacing="0"  class="tableBaseSe" style="border-top:1px solid #ededed;">
				<tr>
					<th style="width:150px;"><img width="8" height="11" src="images/icon_point2.gif" border="0"/>��ǰ Ÿ��</th>
					<td style="text-align:left;padding-left:10px;">
						<input type=radio id="itemType1" name="itemType" value="product" <?=$itemTypeSel['product']?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=itemType1>��ǰ</label> &nbsp;
						<input type=radio id="itemType2" name="itemType" value="location" <?=$itemTypeSel['location']?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=itemType2>���</label> &nbsp;
					</td>
				
				<? if(!_empty($_data->pricetime)){ ?>
					<th style="width:120px;"><img width="8" height="11" src="images/icon_point2.gif" border="0"/>���ݱ���</th>
					<td style="text-align:left;padding-left:10px;">
						<? switch($_data->pricetime){
								case '1': echo '�ð����� ���'; break;
								case '24': echo '�Ϸ�(24�ð�)���� ���'; break;
								case 'checkout': echo '������(����2��~����11��) ���'; break;
								default: echo '����'; break;
						} ?>
					</td>
				<? } ?>
				</tr>
			</table>
			
			<div style="margin:10px 0px;overflow:hidden;">
				<h6 style="float:left;padding-top:5px;font-size:13px;font-weight:700;letter-spacing:-1px;">* ��ǰ ����� �� �뿩 ��� ����</h6>
				<div style="float:right;">
					<input type="button" value="����/��Ż ��Ȳ����" onclick="bookingSchedulePop(<?=$_data->pridx?>);">
					<input type="button" value="�����԰�" onclick="bookingRepair(<?=$_data->pridx?>);">
					<? /* <input type="button" value="���������" onclick="bookingProductConnPop(<?=$_data->pridx?>);"> */ ?>
				</div>				
			</div>
			<?
			// �뿩 ����� ���� ����Ʈ	
			$value = array("display"=>1,'vender'=>(($_data->istrust == '1')?$_data->vender:0)); // ���� �� ǥ��
			$localList = rentLocalList( $value );
			?>
			<!-- ����Ʈ --->			
			<table border="0" cellpadding="0" cellspacing="0" class="tableBase" style="clear:both">
				<tr align="center">
					<th style="width:60px;" class="firstTh">�����ڵ�</th>
					<th style="width:60px;">Ÿ��</th>
<!-- 					<th>����������</th> -->
					<th style="width:200px;">����</th>
<!--					<th>����</th> -->
					<th style="width:700px">�ּ�</th>
					<th style="width:40px;">����</th>
				</tr>
				<tr>
					<td colspan="4" align="center" class="firstTd">����� ���� ����</td>
					<td align="center"><input type="radio" value="0" name="location" <?=$localSel[0]?> /></td>
				</tr>
				<? foreach ( $localList as $k=>$v ) { ?>
				<tr>
					<td class="firstTd" align="center"><?=$v['location']?></td>
					<td align="center"><?=$rentLocationType[$v['type']]?></td>
<!--					<td align="center"><?=($v['vender']>0 ? $venderList[$v['vender']]['com_name'] : "����"); ?></td> -->
					<td style="padding-left:10px;"><?=$v['title']?></td>
<!--					<td style="padding-left:10px;"><?=$v['ypos']?>*<?=$v['xpos']?></td> -->
					<td style="padding-left:10px;">(<?=$v['zip']?>) <?=$v['address']?></td>
					<td align="center"><input type="radio" value="<?=$v['location']?>" name="location" <?=$localSel[$v['location']]?> /></td>
				</tr>
				<? } ?>
			</table>			
			<?
			if ( retnOptionUseCnt($_data->pridx) == 0 ) {
				echo "<span style=\"color:#ec2f36;\"><strong>�ɼ��� �ּ� 1�� �̻� �Է��� �ּ���!</strong></span>";
			}

			?>
			<div style="margin-top:10px;"><input type="button" value="�뿩��ǰ�ɼ�" onclick="rentProdOptManager(<?=$_data->pridx?>);"></div>
		</div>


	</TD>
</TR>
