<?php
/**
 * Created by PhpStorm.
 * User: x2chi-objet
 * Date: 2014-11-26
 * Time: ���� 2:57
 */

//////////////////////
// ��
/////////////////////

// ���� ��¥
if(_empty($vdate)) $vdate = date('Ymd');

$curY = date("Y");
$curW = date("W");

$selY = substr($vdate,0,4);
$selW = substr($vdate,4,2);

$days = weekDays2($vdate);

$startDate = strtotime($days['sDate']);

if($days['weeks'] > 52 ) {
	$selY = $days['years'] = $days['years']+1;
	$selW = $days['weeks'] = 1;
}
if($days['weeks'] == 0 ) {
	$selY = $days['years'] = $days['years']-1;
	$selW = $days['weeks'] = 52;
}
$prv = date('Ymd',strtotime('-1 week',strtotime($days['sDate'])));//$days['years'].($days['weeks']-1);
$nxt = date('Ymd',strtotime('+1 week',strtotime($days['sDate'])));//$days['years'].($days['weeks']+1);

// ��Ż �ֹ� ����Ʈ
$bookingProductList = bookingProductList('W',$vdate);
// ��ǰ �������� ������
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
	<td><img src="/admin/images/product_rental_stitle2.gif" alt="�ְ� ����/��Ż ��Ȳ" /></td>
</tr>
<tr>
	<td>
		<table border="0" cellpadding="0" cellspacing="0" width="100%" height="24" class="tb_orderbytype" style="margin-bottom:2px;">
			<tr>
				<td style="width:300px;">				
				<form name="setDateForm" method="get" action="<?=$_SERVER['PHP_SELF']?>">
				<input type="hidden" name="datet" value="<?=$_SESSION['datet']?>" />
				<input type="text" id="datepicker" style="width:100px;" name="vdate" value="<?=date('Ymd')?>"></form></td>
				<td style="padding-left:10px; text-align:center"><a href="javascript:changeList('<?=$prv?>')" style="margin-right:10px; font-size:18px; font-weight:bold">&lt;</a> <strong class="font_orange" style="font-size:16px;"><?=$days['sDate']?>~<?=$days['eDate']?></strong> <a href="javascript:changeList('<?=$nxt?>')" style="margin-left:10px; font-size:18px; font-weight:bold">&gt;</a><a href="javascript:changeList('<?=$curY.$curM.$curD?>')" style="border:1px solid #dddddd; display:inline-block; padding:2px; background:#efefef; margin-left:4px;">�̹���</a>
					[<a href="javascript:document.location.reload();">���ΰ�ħ</a>]</td>
				<td style="padding-right:10px;text-align:right;font-size:0px; width:300px;"><a href="javascript:changeListType('D')" style="margin-right:3px;"><img src="/admin/images/counter_tab_day_off.gif" border="0" alt="����" /></a> &nbsp; <img src="/admin/images/counter_tab_week_on.gif" border="0" alt="�ְ�" style="margin-right:3px;"/> &nbsp; <a href="javascript:changeListType('M')" style="margin-right:3px;"><img width="74" height="20" src="/admin/images/counter_tab_month_off.gif" border="0" alt="����" /></a></td>
			</tr>
		</table>

		
		<table border="0" cellspacing="0" cellpadding="0" width="100%" class="tableBase" style="margin-bottom:40px;">
			<tr>
				<th class="firstTh">��ǰ��/��������</th>
				<th>�ɼ�</th>
				<?
				// ����
				for( $dd = 0 ; $dd < 7 ; $dd++) {
					$sDate = strtotime( "+".$dd." day", $startDate );
					$dayOfWeek = date("w",$sDate); //����
					// ���� �� �޹��� �÷� �޹���>�Ͽ���>����� (�޹���:(�Ͽ���:�����))
					$dayOfWeekColor = (strlen($dayOffs[0])==0?($dayOfWeek==0?"#FF8888":($dayOfWeek==6?"8888FF":"")):"#FF6600");
					echo "<th><span style='color:".$dayOfWeekColor.";'>". date("Y-m-d", $sDate) ."</span></th>";
				}
				?>
			</tr>
		
		<? //��ǰ ����Ʈ
			if(!_array($productOptionList)){ ?>
			<tr>
				<td colspan="9" style="text-align:center"> ���� ���� </td>
			</tr>
		<?	}else{
				foreach($productOptionList as $pridx => $product ){ 
					$pfirst = true;
				?>
			<tr>
				<td class="firstTd" width="25%" align="left" rowspan="<?=$product['rowscnt']?>"><?=$product['productname']?></td>
				<? 	foreach($product['option'] as $optidx => $option ){// �ɼ� ����Ʈ
						if(!$pfirst) echo '<tr>';
						else $pfirst = false;
						$scfirst = true;
				?>
				<td align="left" width="10%" style="padding-left:10px;" rowspan="<?=$option['itemcnt']?>"><?=$option['optname']?></td>
				<?		foreach($option['items'] as $sidx=>$schduled){
							if(!$pfirst){								
								if(!$scfirst) echo '<tr>';
								else $scfirst = false;				
							} // end if
							$class = 'resevST_'.$schduled['status'];
							$divid = 'bookingInfo_'.$schduled['ordercode'].$schduled['optidx'];
							$disphtml = false;
							for( $dd = 0 ; $dd < 7 ; $dd++){
								$stamp = date('Y-m-d',strtotime( "+".$dd." day", $startDate ));								
								if($stamp > substr($schduled['end'],0,10) || $stamp < substr($schduled['start'],0,10)){ ?>
									<td align="center">&nbsp;</td>	
							<?	}else{ 
									?>
									<td align="center" class="<?=$class?> scheduledItem" layerid="<?=$divid?>" style="cursor:pointer;" onclick="changeStatus('<?=$schduled['ordercode']?>', '<?=$schduled['basketidx']?>')"><?=rentProduct::_bookingStatus($schduled['status'])?>[<?=$schduled['sender_name']?>]<? if(!$disphtml){ $disphtml = true; echo infoareaHtml($schduled,$divid);}?></td>	
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
		// �ֹ�����Ʈ
//		include "product_rental.booking.list.php";
		?>



	</td>
</tr>

<tr><td height="20"></td></tr>
<tr>
	<td height="20"></td>
</tr>
<tr><td height="50"></td></tr>
</table>