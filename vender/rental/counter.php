<?
$csql = "select so.* from rent_schedule so left join rent_product_option o on o.idx=so.optidx left join tblproduct p on p.pridx = o.pridx where (so.start between '".date('Y-m-d',strtotime('-2 day'))."' and '".date('Y-m-d',strtotime('+3 day'))."' or so.end between '".date('Y-m-d',strtotime('-2 day'))."' and '".date('Y-m-d',strtotime('+3 day'))."') and p.vender='".$_VenderInfo->getVidx()."'";
$chkCounts = array();
if(false !== $cres = mysql_query($csql,get_db_conn())){
	for($i=-2;$i<3;$i++){
		$key = date('Y-m-d',strtotime($i.' day'));
		$chkCounts[$key] = array('rent'=>0,'return'=>0,'cancel'=>'0');
	}
	if(mysql_num_rows($cres)){
		while($crow = mysql_fetch_assoc($cres)){
			$st =  substr($crow['start'],0,10);
			$end =  substr($crow['end'],0,10);
		//	"NN"=>"가예약", "NC"=>"가예약취소", "BC"=>"미입금취소", "BR"=>"입금대기", "BO"=>"입금완료", "BI"=>"예약완료", "BE"=>"반납완료", "OT"=>"반납안됨", "RP"=>"정비" );
			switch($crow['status']){
				case 'BI':
				case 'BO':
					if(isset($chkCounts[$st])) $chkCounts[$st]['rent'] +=1;
					if(isset($chkCounts[$end])) $chkCounts[$end]['return'] +=1;
					break;
				case 'BC':
				case 'NC':
					if(isset($chkCounts[$st])) $chkCounts[$st]['cancel'] +=1;
					else if(isset($chkCounts[$end])) $chkCounts[$end]['cancel'] +=1;
					break;					
			}
		}
	}
}
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
	var win = window.open('./rental/orderlist.php?d='+date+'&t='+type,'width=700,height=500');
}
</script>
<table class="tblStyle_List" cellpadding="0" cellspacing="0" style="width:100%">
<caption>날짜별:대여 반납 현황</caption>
	<thead>
		<tr>
			<th style="background:#ccc">&nbsp;</th>
			<th>그제</th>
			<th>어제</th>
			<th>오늘</th>
			<th>내일</th>
			<th>모래</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th>대여</th>
		<?
			for($i=-2;$i<3;$i++){
				$key = date('Y-m-d',strtotime($i.' day')); ?>
			<td><a href="javascript:viewOrderList('<?=$key?>','R')"><?=number_format($chkCounts[$key]['rent'])?></a></td>
		<?	} ?>
		</tr>
		<tr>
			<th>대여취소</th>
		<?
			for($i=-2;$i<3;$i++){
				$key = date('Y-m-d',strtotime($i.' day')); ?>
			<td><a href="javascript:viewOrderList('<?=$key?>','C')"><?=number_format($chkCounts[$key]['cancel'])?></a></td>
		<?	} ?>
		</tr>
		<tr>
			<th>반납</th>
		<?
			for($i=-2;$i<3;$i++){
				$key = date('Y-m-d',strtotime($i.' day')); ?>
			<td><a href="javascript:viewOrderList('<?=$key?>','O')"><?=number_format($chkCounts[$key]['return'])?></a></td>
		<?	} ?>
		</tr>
	</tbody>
</table>