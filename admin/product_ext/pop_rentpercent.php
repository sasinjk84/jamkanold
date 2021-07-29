<?php
$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/ext/func.php");
include_once($Dir."lib/class/pages.php");
INCLUDE ("../access.php");

if(!preg_match('/^[0-9]{12}$/',$_REQUEST['code'])){
	_alert('ī�װ� �ĺ� �ڵ尡 ���޵��� �ʾҽ��ϴ�.',0);
	exit;
}

extract($_REQUEST);

if($_REQUEST['type'] == 'discount'){
	$table = 'rent_longdiscount';	
}else if($_REQUEST['type'] == 'refund'){
	$table = 'rent_refund';
}else{
	_alert('�߸��� ȣ�� �Դϴ�.','0');
		exit;
}
if($_REQUEST['act'] == 'add'){	
	if(!_isInt($_REQUEST['day'])){
		_alert('���ڴ� ���ڸ� �Է°����մϴ�.','-1');
		exit;
	}
	if(!_isInt($_REQUEST['percent'])){
		_alert('% ��ġ�� ���ڸ� �Է°����մϴ�.','-1');
		exit;
	}
	
	if($_REQUEST['percent'] > 0 && $_REQUEST['percent'] <= 100){	
		$sql = "insert into ".$table." set code='".$_REQUEST['code']."',day='".$_REQUEST['day']."',percent='".$_REQUEST['percent']."' ON DUPLICATE KEY UPDATE percent='".$_REQUEST['percent']."'";
		
		if(false === mysql_query($sql,get_db_conn())){
			_alert('DB ���� ����','-1');
			exit;
		}
		_alert('',$_SERVER['PHP_SELF']."?type=".$_REQUEST['type']."&code=".$_REQUEST['code']);
	}else{
		_alert('% ��ġ�� 1~100������ �Է°����մϴ�.','-1');
	}
	
}else if($_REQUEST['act'] == 'delete'){	
	if(!_isInt($_REQUEST['targetday'])){
		_alert('���ڴ� ���ڸ� �Է°����մϴ�.','-1');
		exit;
	}	
	$sql = "delete from ".$table." where code='".$_REQUEST['code']."' and day='".$_REQUEST['targetday']."' order by day asc";	
	if(false === mysql_query($sql,get_db_conn())){
		_alert('DB ���� ����','-1');
		exit;
	}
	_alert('',$_SERVER['PHP_SELF']."?type=".$_REQUEST['type']."&code=".$_REQUEST['code']);
}else{	
	$sql = "select * from ".$table." where code='".$_REQUEST['code']."' order by day asc";	

	if(false === $res = mysql_query($sql,get_db_conn())){
		_alert('DB ���� ����',0);
		exit;
	}
	$items = array();
	if(mysql_num_rows($res)){
		while($item = mysql_fetch_assoc($res)){
			array_push($items,$item);
		}
	}
}
?>

<html>
<head>		
<link rel="stylesheet" href="style.css">
<script language="javascript" type="text/javascript" src="/js/jquery-1.10.2.min.js"></script>
<script language="javascript" type="text/javascript">
$(function(){
	$(document).on('click','#itemDiv>div>img',function(e){
		rmvPercent($(this).attr('day'));
	});
	
	$('#percentForm').on('submit',function(e){
		if($(this).find('input[name=act]').val() != 'delete'){
			var d = parseInt($('#addDay').val());
			var p = parseInt($('#addPercent').val());			
			var check = false;
			
			if(isNaN(d) || d < 1){
				alert('�Ⱓ/���ڸ� �ùٸ��� �Է��ϼ���.');
				$('#addDay').focus();
			}else if(isNaN(p) || p < 1|| p>100){
				alert('% ��ġ�� �ùٸ��� �Է��ϼ���.');
				$('#addPercent').focus();
			}else{
				check = true;
			}	
		}else{
			check = true;
		}
		if(!check) e.preventDefault();
	});
});
function rmvPercent(day){
	var f = document.percentForm;	
	f.targetday.value = day;
	f.act.value = 'delete';
	f.submit();
}

function closeWin(){
	window.opener.location.reload();
	window.close();
}

</script>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
</head>
<body style="padding:0px; margin:0px;">
	<style type="text/css">
	#itemDiv div{  margin-right:3px; width:32%; padding:5px; background:#f4f4f4; font-size:12px; clear:both; display:inline-block}
	#itemDiv div img{cursor:pointer}
	
	 .infoListTbl{border:1px solid #CDDDE0; }
	 .infoListTbl th{ font-weight:bold; background:#efefef; border-right:1px solid #CDDDE0; border-bottom:1px solid #CDDDE0; font-size:11px;}
	 .infoListTbl td{  background:#fff; border-right:1px solid #CDDDE0; border-bottom:1px solid #CDDDE0; font-size:11px;}
	 .infoListTbl .norbl{border-right:0px;}
	 .infoListTbl .nobbl{border-bottom:0px;}
	</style>		
	<h2 style="background:url('/admin/images/member_mailallsend_imgbg.gif'); font-size:15px; color:white; padding:5px 0px 5px 5px;">
	<? 	if($_REQUEST['type'] == 'discount') echo '������� ����';
		else echo 'ȯ�� ������ ����';
		?>
	</h2>	
	<form name="percentForm" id="percentForm" method="post" action="<?=$_SERVER['PHP_SELF']?>">
	<input type="hidden" name="type" value="<?=$_REQUEST['type']?>">
	<input type="hidden" name="act" value="add" />
	<input type="hidden" name="code" value="<?=$_REQUEST['code']?>">
	<input type="hidden" name="targetday" value="" />
	<table cellpadding="0" cellspacing="0" class="infoListTbl" style="margin-top:7px; border-bottom:0px;">
		<tr>
			<th style="width:80px;"><?=($_REQUEST['type'] == 'discount')?'�Ⱓ':'�����'?></th>
			<td class="norbl" style="padding:5px;"><input type="text" name="day" id="addDay" value="" style="width:30px;" /><?=($_REQUEST['type'] == 'discount')?'���̻�':'����'?></td>
			<th style="width:80px;"><?=($_REQUEST['type'] == 'discount')?'������':'������'?></th>
			<td style="padding:5px;"><input type="text" name="percent" id="addPercent" value="" style="width:30px;" />%</td>
			<td style="padding:0px;"><input type="submit" name="addBtn" value="�߰�"  style="height:30px;" /></td>
		</tr>					
	</table>
	</form>
	<div style="width:100%; padding:3px 0px; clear:both" id="itemDiv">
	<? 
	if($_REQUEST['type'] == 'discount'){ 
		$ldiscinfo = rentLongDiscount(pick($code));
		if(_array($ldiscinfo)){
			foreach($ldiscinfo as $day=>$percent){ ?>
				<div><span style="float:left"><?=$day?> ���̻� <?=$percent?>%</span><img src="/admin/images/btn_del.gif" alt="����" align="right" day="<?=$day?>" /></div>
	<?		}
		}
	}else{
		$refundinfo = rentRefundCommission(pick($_REQUEST['code']));				
		if(_array($refundinfo)){
			foreach($refundinfo as $day=>$percent){ ?>
				<div><span style="float:left"><?=$day?> ���� <?=$percent?>%</span><img src="/admin/images/btn_del.gif" alt="����" align="right" day="<?=$day?>" /></div>
	<?		}
		}

	} ?>
	</div>
	<div style="margin:10px 0px;text-align:center;"><a href="javascript:closeWin();"><img src="/images/common/bigview_btnclose.gif" border="0" alt="" /></a></div>

</body>
</html>
