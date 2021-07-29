<?php
/**
 * Created by PhpStorm.
 * User: x2chi-objet
 * Date: 2014-10-22
 * Time: 오전 9:48
 */
$rentProduct = rentProduct($_data->pridx);
if( is_array($rentProduct) ){
	//_pr($rentProduct);
	//$goodsTypeSel['2'] = "checked";
	$itemTypeSel[$rentProduct['itemType']] = "checked";
	$localSel[$rentProduct['location']] = "checked";
} else {
	//$goodsTypeSel['1'] = "checked";
	$itemTypeSel['product'] = "checked";
	$localSel['0'] = "checked";
}
$goodsTypeSel[$_data->rental] = "checked";
?>
<TR>
	<TD colspan="4" background="images/table_con_line.gif"></TD>
</TR>
<TR>
	<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">상품 구분</TD>
	<TD class="td_con1" colspan="3">
		<div style="height:24px;">
			<input type=radio id="goodsType1" name="goodsType" value="1" <?=$goodsTypeSel['1']?> onclick="goodsTypeLocalDiv.style.display = 'none';"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=goodsType1>판매상품</label> &nbsp;
			<input type=radio id="goodsType2" name="goodsType" value="2" <?=$goodsTypeSel['2']?> onclick="goodsTypeLocalDiv.style.display = (this.checked ? 'block': 'none' ); parent_resizeIframe('AddFrame'); "><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=goodsType2>대여상품</label>
		</div>

		<div id="goodsTypeLocalDiv" style="display:<?=($goodsTypeSel['2']=="checked"?"block":"none")?>;width:100%;border:2px solid #444444;margin:5px 2px 5px 7px;padding:15px;">
			<table border="0" cellpadding="0" cellspacing="0"  class="tableBaseSe" style="border-top:1px solid #ededed;">
				<tr>
					<th style="width:150px;"><img width="8" height="11" src="images/icon_point2.gif" border="0"/>상품 타입</th>
					<td style="text-align:left;padding-left:10px;">
						<input type=radio id="itemType1" name="itemType" value="product" <?=$itemTypeSel['product']?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=itemType1>상품</label> &nbsp;
						<input type=radio id="itemType2" name="itemType" value="location" <?=$itemTypeSel['location']?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=itemType2>장소</label> &nbsp;
					</td>
					<th style="width:120px;"><img width="8" height="11" src="images/icon_point2.gif" border="0"/>과금 형태</th>
					<td class="lastTd" style="text-align:left;padding-left:10px;">
						<input type=radio id="pricetime1" name="pricetime" value="1" <? if($rentProduct['pricetime'] == '1') echo 'checked="checked"';?> ><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=pricetime1>1시간단위</label> &nbsp;
						<input type=radio id="pricetime2" name="pricetime" value="24" <? if($rentProduct['pricetime'] != '1') echo 'checked="checked"';?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=pricetime2>하루단위</label> &nbsp;
					</td>
				</tr>
			</table>
			
			<div style="margin:10px 0px;overflow:hidden;">
				<h6 style="float:left;padding-top:5px;font-size:13px;font-weight:700;letter-spacing:-1px;">* 상품 출고지 및 대여 장소 선택</h6>
				<div style="float:right;">
					<input type="button" value="예약/렌탈 현황보기" onclick="bookingSchedulePop(<?=$_data->pridx?>);">
					<input type="button" value="정비입고" onclick="bookingRepair(<?=$_data->pridx?>);">
					<? /* <input type="button" value="출고지연동" onclick="bookingProductConnPop(<?=$_data->pridx?>);"> */ ?>
				</div>
				<div style="clear:both;"></div>
			</div>


			<?
			// 대여 출고지 정보 리스트
			$value = array("display"=>1); // 노출 만 표시
			$localList = rentLocalList( $value );
			?>
			<!-- 리스트 --->
			<table border="0" cellpadding="0" cellspacing="0" width="100%" class="tableBase">
				<colgroup>
					<col width="60">
					<col width="60">
					<col width="100">
					<col width="">
					<col width="160">
					<col width="">
					<col width="50">
				</colgroup>
				<tr align="center">
					<th class="firstTh">지역코드</th>
					<th>타입</th>
					<th>소유입점사</th>
					<th>지명</th>
					<th>지도</th>
					<th>주소</th>
					<th>연동</th>
				</tr>
				<tr>
					<td colspan="6" align="center" class="firstTd">출고지 정보 없음</td>
					<td align="center"><input type="radio" value="0" name="location" <?=$localSel[0]?> /></td>
				</tr>
				<? foreach ( $localList as $k=>$v ) { ?>
				<tr>
					<td class="firstTd" align="center"><?=$v['location']?></td>
					<td align="center"><?=$rentLocationType[$v['type']]?></td>
					<td align="center"><?=($v['vender']>0 ? $venderList[$v['vender']]['com_name'] : "본사"); ?></td>
					<td style="padding-left:10px;"><?=$v['title']?></td>
					<td style="padding-left:10px;"><?=$v['ypos']?>*<?=$v['xpos']?></td>
					<td style="padding-left:10px;">(<?=$v['zip']?>) <?=$v['address']?></td>
					<td align="center"><input type="radio" value="<?=$v['location']?>" name="location" <?=$localSel[$v['location']]?> /></td>
				</tr>
				<? } ?>
			</table>

			<?
			if ( retnOptionUseCnt($_data->pridx) == 0 ) {
				echo "<span style=\"color:#ec2f36;\"><strong>옵션을 최소 1개 이상 입력해 주세요!</strong></span>";
			}

			?>
			<div style="margin-top:10px;"><input type="button" value="대여상품옵션" onclick="rentProdOptManager(<?=$_data->pridx?>);"></div>
		</div>


	</TD>
</TR>
