<?
$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/ext/func.php");
include_once($Dir."lib/venderlib.php");
include_once($Dir."lib/class/rentproduct.php");
INCLUDE ("../access.php");

$st = strtotime($_REQUEST['d']);
$st = time();
if(!_isInt($st)){
	$st = time();
//	_alert('잘못된 요청 입니다.','0');
}


//$sql = "SELECT op.*,op.quantity*op.price as sumprice,p.tinyimage,p.minimage, p.reservation,r.optidx,r.location,r.start,r.end,r.status as rentstatus,r.regDate, r.returnDate,o.receiver_name,o.receiver_tel1,o.receiver_tel2 FROM tblorderproduct op left join tblorderinfo o on o.ordercode=op.ordercode left join tblproduct p on (op.productcode = p.productcode) left join rent_schedule r on r.ordercode=op.ordercode and r.basketidx=op.basketidx WHERE ";
//static public $bookingStatus = array("NN"=>"가예약", "NC"=>"가예약취소", "BC"=>"미입금취소", "BR"=>"입금대기", "BO"=>"입금완료", "BI"=>"예약완료", "BE"=>"반납완료", "OT"=>"반납안됨", "RP"=>"정비" );
$param = array('start'=>date('Y-m-d',$st));
$param['vender'] = $_VenderInfo->getVidx();



switch($_REQUEST['t']){	
	case 'R':	
		$cstr = '대여';	
		/* $sql .= " r.status in ('BI','BO') and r.start >= '".date('Y-m-d',$st)."' and r.start < '".date('Y-m-d',strtotime('+1 day',$st))."'";*/
		$param['status'] = 'BO';
		break;
	case 'C':
		$cstr = '취소';	
	//	$sql .= " r.status in ('BC','NC') and ((r.start >= '".date('Y-m-d',$st)."' and r.start '".date('Y-m-d',strtotime('+1 day',$st))."') or (r.end >= '".date('Y-m-d',$st)."' and r.end < '".date('Y-m-d',strtotime('+1 day',$st))."'))";
		$param['status'] = 'BC';
		break;
	case 'O':
		$cstr = '반납';	
	//	$sql .= " r.status in ('BI','BO') and  r.end >= '".date('Y-m-d',$st)."' and r.end < '".date('Y-m-d',strtotime('+1 day',$st))."'";
		$param['status'] = 'BC';
		break;
	case 'B':
	default:
		$cstr = '예약';			
		$param['status'] = 'BI';
		break;
}
//$sql .= " and p.vender='".$_VenderInfo->getVidx()."'";

$items = array();
/*
if(false !== $cres = mysql_query($sql,get_db_conn())){	
	if(mysql_num_rows($cres)){
		while($crow = mysql_fetch_object($cres)){
			array_push($items,$crow);
		}
	}
}*/


$items = rentProduct::searchOrder($param);
?>
<style type="text/css">
.tblStyle_List{ border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:12px; margin-top:10px;}
.tblStyle_List caption{ text-align:left; background:#333; color:white; font-weight:bold; padding:5px 0px 5px 5px;}
.tblStyle_List thead th{ font-weight:normal; background:#efefef; border-right:1px solid #ccc; border-bottom:1px solid #ccc;height:28px;}
.tblStyle_List thead td{ font-weight:normal; background:#fff; border-right:1px solid #ccc; border-bottom:1px solid #ccc; padding:0px 5px;}
.tblStyle_List tbody th{ font-weight:normal; background:#efefef; border-right:1px solid #ccc; border-bottom:1px solid #ccc;height:28px;}
.tblStyle_List tbody td{ font-weight:normal; background:#fff; border-right:1px solid #ccc; border-bottom:1px solid #ccc; padding:0px 5px; height:28px; line-height:140%; text-align:center}
</style>
<script language="javascript" type="text/javascript">
function viewOrderList(date,type){
	var win = window.open('./rental/orderlist.php?d='+date+'&t='+type,'width=450,height=500');
}
</script>
<table class="tblStyle_List" cellpadding="0" cellspacing="0" style="width:100%">
<caption><?=date('Y-m-d',$st)?> :<?=$cstr?> 현황</caption>
	<thead>
		<tr>
			<th style="width:120px;">주문번호/일시</th>
			<th>상품정보</th>
			<th>기간</th>
			<th style="width:60px;">수량</th>
			<th style="width:80px;">금액</th>		
			<th style="width:220px;">예약자</th>
		</tr>
	</thead>
	<tbody>
<? if(!_array($items)){ ?>
		<tr>
			<td colspan="6">등록된 정보가 없습니다.</td>
		</tr>
<? }else{
foreach($items as $jj=>$item){		
/*
		$optvalue = "";
		if (ereg("^(\[OPTG)([0-9]{3})(\])$", $item->opt1_name)) {
			$optioncode = $item->opt1_name;
			$item->opt1_name = "";
			$sql = "SELECT opt_name FROM tblorderoption WHERE ordercode='" . $row->ordercode . "' AND productcode='" . $item->productcode . "' AND opt_idx='" . $optioncode . "' limit 1 ";
			$res = mysql_query($sql, get_db_conn());
			if ($res && mysql_num_rows($res)) {
				$optvalue = mysql_result($res, 0, 0);
			}
			mysql_free_result($res);
		}*/
?>
	<tr>
		<td>
		<? // echo $item->ordercode.'<br>';
			echo substr($item['ordercode'],0,4).'-'.substr($item['ordercode'],4,2).'-'.substr($item['ordercode'],6,2).' '.substr($item['ordercode'],8,2).':'.substr($item['ordercode'],10,2);
			echo '<br>'.$item['productcode'];
		?>
		</td>
		<td style="padding:10px; text-align:left">		
			<img src="<?=$Dir.((strlen($item['tinyimage'])>0 && file_exists($Dir.DataDir."shopimages/product/".$item['tinyimage']))?DataDir.'shopimages/product/'.urlencode($item['tinyimage']):"images/no_img.gif")?>" border="0" width="50" style="float:left;margin-right:5px;"/><?= $reservation ?><?= $item['productname'] ?>
		<?
		/*
		if (!_empty($optvalue)) echo "<br><img src=\"" . $Dir . "images/common/icn_option.gif\" border=0 align=absmiddle> " . $optvalue . "";
		echo '<br>'.$item['opt1_name'].(!_empty($item['opt2_name'])?' / '.$item['opt2_name']:'');
		*/
		if(!_empty($item['multiOpt']) && !_empty($item['optionName'])) echo $item['optionName'];
		?>	
		</td>
		<td style="text-align:center">
			<?				
			if(!_empty($item['start'])){
				$diff = datediff_rent($item['end'],$item['start']);
				echo substr($item['start'],0,-6).' ~ <br>'.date('Y-m-d H',strtotime($item['end'])+1);
				echo '<br>('.(($diff['day'] >0)?$diff['day'].'일':'').(($diff['hour'] >0)?$diff['hour'].'시간':'').')';
			}else echo 'Error';
			?>
		</td>
		<td><?=number_format($item['quantity'])?></td>
		<td style=" text-align:center"><? echo number_format($item['quantity']*$item['price']).'원'; ?></td>		
		<td>
		<?
			echo $item['receiver_name'].'<br>';
			echo $item['receiver_tel1'].'<br>';
			if(!_empty($item['receiver_tel2'])) echo $item['receiver_tel2'];
		?>
		</td>
	</tr>
<? }  //end foreach
}?>
	</tbody>
</table>
