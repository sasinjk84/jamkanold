<?php
/**
 * Created by PhpStorm.
 * User: x2chi-objet
 * Date: 2014-11-26
 * Time: 오후 2:57
 */

//////////////////////
// 월
/////////////////////

// 오늘 날짜
if(strlen($vdate) == 8) {
	$thisM = substr($vdate,0,6)."01";
} else if(strlen($vdate) == 6) {
	$thisM = $vdate.'01';
	$vdate = date('Ymd');
} else {
	$thisM = date('Ym').'01';
}

$selT =  (empty($thisM) ? time() :strtotime( $thisM) );

$prv = date("Ym",strtotime("-1 month",$selT));
$nxt = date("Ym",strtotime("+1 month",$selT));
$selY = date("Y",$selT);
$selM = date("m",$selT);
$curY = date("Y");
$curM = date("m");
$monthDays = date("t",$selT);

// 렌탈 주문 리스트
$bookingProductList = bookingProductList('M',$selY.$selM);
// 상품 기준으로 재조합
$productOptionList = array();
if(_array($bookingProductList)){
	foreach($bookingProductList as $v) {
		if(!isset($productOptionList[$v['pridx']])) $productOptionList[$v['pridx']]['productname'] = $v['productname'];

		if(!isset($productOptionList[$v['pridx']]['option'][$v['optidx']])){
			$productOptionList[$v['pridx']]['option'][$v['optidx']]['optname'] = $v['optionName'];
			$productOptionList[$v['pridx']]['option'][$v['optidx']]['items'] = array();
		}
		$productOptionList[$v['pridx']]['option'][$v['optidx']]['items'][] = $v;			
	}
	foreach($productOptionList as $pridx=>$prinfo){
		$productOptionList[$pridx]['rowscnt'] = 0;
		foreach($prinfo['option'] as $opidx=>$optinfo){
			$productOptionList[$pridx]['option'][$opidx]['itemcnt'] = count($optinfo['items']);
			$productOptionList[$pridx]['rowscnt'] += $productOptionList[$pridx]['option'][$opidx]['itemcnt'];
		}
	}
}
?>
<table cellpadding="0" cellspacing="0" width="100%">
<tr>
	<td><img src="/admin/images/product_rental_stitle1.gif" alt="월간 예약/렌탈 현황" /></td>
</tr>
<tr>
	<td>		
		<table border="0" cellpadding="0" cellspacing="0" width="100%" height="24" class="tb_orderbytype" style="margin-bottom:2px;">
			<tr>
				<td style="width:300px;">
					<form name="setDateForm" method="get" action="<?=$_SERVER['PHP_SELF']?>">
						<input type="hidden" name="datet" value="<?=$_SESSION['datet']?>" />
						<input type="text" id="datepicker" style="width:100px;" name="vdate" value="<?=date('Ymd')?>">
					</form>
				</td>
				<td style="padding-left:10px; text-align:center"><a href="javascript:changeList('<?=$prv?>')" style="margin-right:10px; font-size:18px; font-weight:bold">&lt;</a> <strong class="font_orange" style="font-size:16px;"><?=date('Y.m',$selT)?></strong> <a href="javascript:changeList('<?=$nxt?>')" style="margin-left:10px; font-size:18px; font-weight:bold">&gt;</a><a href="javascript:changeList('<?=$curY.$curM.$curD?>')" style="border:1px solid #dddddd; display:inline-block; padding:2px; background:#efefef; margin-left:4px;">이번달</a>
					[<a href="javascript:document.location.reload();">새로고침</a>]</td>
				<td style="padding-right:10px;text-align:right;font-size:0px; width:300px;"><a href="javascript:changeListType('D')"  style="margin-right:3px;"><img src="/admin/images/counter_tab_day_off.gif" border="0" alt="일일" /></a>&nbsp; <a href="javascript:changeListType('W')" style="margin-right:3px;"><img src="/admin/images/counter_tab_week_off.gif" border="0" alt="주간" /></a> &nbsp; <img width="74" height="20" src="/admin/images/counter_tab_month_on.gif" border="0" alt="월간" /></td>
			</tr>
		</table>
		
		<div id="viewInfo" style="display:none;position:absolute;top:100px;left:100px;width:600;height:0;padding:10px;background:#ffffff;border:2px solid #999999;z-index:999;"></div>

		<table border="0" cellspacing="0" cellpadding="0" width="100%" class="tableBase" style="margin-bottom:40px;">
			<tr>
				<th class="firstTh">상품명/예약일자</th>
				<th>옵션</th>
				<?
				// 일자
				for( $dd = 1 ; $dd <= $monthDays ; $dd++) {
					$dayOfWeek = date("w",strtotime($selY."-".$selM."-".$dd)); //요일
					// 요일 및 휴무일 컬러 휴무일>일요일>토요일 (휴무일:(일요일:토요일))
					$dayOfWeekColor = (strlen($dayOffs[0])==0?($dayOfWeek==0?"#FF8888":($dayOfWeek==6?"8888FF":"")):"#FF6600");
					echo "<th><span style='color:".$dayOfWeekColor.";'>". str_pad($dd, 2, "0", STR_PAD_LEFT) ."</span></th>";
				}
				?>
			</tr>
			<? //상품 리스트
			if(!_array($productOptionList)){ ?>
			<tr>
				<td colspan="<?=$monthDays+2?>" style="text-align:center"> 예약 없음 </td>
			</tr>
		<?	}else{
				foreach($productOptionList as $pridx => $product ){ 
					$pfirst = true;
				?>
			<tr>
				<td class="firstTd" width="15%" align="left" rowspan="<?=$product['rowscnt']?>"><?=$product['productname']?></td>
				<? 	foreach($product['option'] as $optidx => $option ){// 옵션 리스트
						if(!$pfirst) echo '<tr>';
						else $pfirst = false;
						$scfirst = true;
				?>
				<td align="left" width="5%" style="padding-left:10px;" rowspan="<?=$option['itemcnt']?>"><?=$option['optname']?></td>
				<?		foreach($option['items'] as $sidx=>$schduled){
							if(!$pfirst){
								if(!$scfirst) echo '<tr>';
								else $scfirst = false;
							} // end if
							// colspan 계산
							$startY = substr($schduled['start'],0,4);
							$startM = substr($schduled['start'],5,2);
							$startD = substr($schduled['start'],8,2);
							$endD   = substr($schduled['end'],8,2);
							$remainWidth = 80;

							if ($startD > $endD) {
								$endD = date('t',$startY.$startM);
							}
							$col = ($endD - $startD) + 1;
							$colwidth= $col * ($remainWidth/$monthDays); //합쳐진 셀 크기 구하기

							$class = 'resevST_'.$schduled['status'];
							$divid = 'bookingInfo_'.$schduled['ordercode'].$schduled['optidx'];
							$disphtml = false;
							for( $dd = 1 ; $dd <= $monthDays ; $dd++) {
								if ($dd > $startD && $dd <= $endD) continue;
								$stamp = date('Y-m-d',strtotime( "+".($dd-1)." day",$selT));
								if($stamp > substr($schduled['end'],0,10) || $stamp < substr($schduled['start'],0,10)){?>
									<td style="width:<?=($remainWidth/$monthDays)?>%;" align="center">&nbsp;</td>
							<?	}else{ 
									?>
									<td align="center" colspan="<?=$col?>" style="width:<?=$colwidth?>%;cursor:pointer;" class="<?=$class?> scheduledItem" layerid="<?=$divid?>" onclick="changeStatus('<?=$schduled['ordercode']?>', '<?=$schduled['basketidx']?>')"><p style="width:40px;overflow:hidden;white-space:nowrap;"><?=rentProduct::_bookingStatus($schduled['status'])?>[<?=$schduled['sender_name']?>]</p><? if(!$disphtml){ $disphtml = true; echo infoareaHtml($schduled,$divid);}?></td>
							<?  } ?>
				
				<?			} // end for ?>
			</tr>
				<?		}// end sc foreach
					}// end opt foreach
				}// end  product foreach
			}
			?>
		</table>

		<?
		// 주문리스트
	//	include "product_rental.booking.list.php";
		?>

	</td>
</tr>
<tr><td height="50"></td></tr>
</table>