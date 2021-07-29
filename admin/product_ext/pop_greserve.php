<?php
$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/ext/func.php");
include_once($Dir."lib/class/pages.php");
INCLUDE ("../access.php");

if(!preg_match('/^([0-9]{12}|[0-9]{18})$/',$_REQUEST['code'])){
	_alert('ī�װ� �ĺ� �ڵ尡 ���޵��� �ʾҽ��ϴ�.',0);
	exit;
}

extract($_REQUEST);
if($_REQUEST['act'] == 'update'){	

	foreach($_REQUEST['gdiscount'] as $groupcode=>$discount){
		if(!_isInt($discount,true) || $_REQUEST['main'] > 100){
			_alert('�������� 100 �̳� ���ڴ� ���ڸ� �Է°����մϴ�.','-1');
			exit;
		}	
		$sql = "insert into reserve_chgrequest (group_code,productcode,reserve) values ('".$groupcode."','".$_REQUEST['code']."','".($discount/100)."') ON DUPLICATE KEY UPDATE reserve=values(reserve)";

		if(false === mysql_query($sql,get_db_conn())){
			_alert('DB ���� ����','-1');
			exit;
		}
	}
	?>
	<script language="javascript" type="text/javascript">
	window.opener.location.reload();
	window.close();
	</script>
<?
}
?>

<html>
<head>		
<link rel="stylesheet" href="style.css">
<script language="javascript" type="text/javascript" src="/js/jquery-1.10.2.min.js"></script>
<script language="javascript" type="text/javascript">
$(function(){	
	$('#percentForm').on('submit',function(e){		
		var check = true;
		$(this).find('input[name^=gdiscount]').each(function(idx,el){
			var s = parseInt($(el).val());
			if(isNaN(s) || s < 0 || s> 100){
				alert('�������� �ùٸ��� �Է����ּ���.');
				$(el).focus();
				check = false;
				return false;
			}
		});
		if(!check) e.preventDefault();
	});
});

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
	<h2 style="background:url('/admin/images/member_mailallsend_imgbg.gif'); font-size:15px; color:white; padding:5px 0px 5px 5px;">������ ����</h2>	
	<form name="percentForm" id="percentForm" method="post" action="<?=$_SERVER['PHP_SELF']?>">
	<input type="hidden" name="act" value="update" />
	<input type="hidden" name="code" value="<?=$_REQUEST['code']?>">	
	<table cellpadding="0" cellspacing="0" class="infoListTbl" style="margin-top:7px; border-bottom:0px; width:100%">
	<? 
		if(strlen($code) > 12) $cinfo = getGroupReserves(substr($code,0,12));	
		$groupdiscount = getGroupReserves($code);	
		if(_array($groupdiscount)){
				foreach($groupdiscount as $discount){	?>
		
		<tr>
			<th style="width:100px;"><?=$discount['group_name']?></th>
			<td  style="padding:5px;" class="norbl"><input type="text" name="gdiscount[<?=$discount['group_code']?>]" value="<?=$discount['reserve']*100?>" style="width:40px;" />% <? if(!_empty($cinfo[$discount['group_code']])) echo '('.($cinfo[$discount['group_code']]['reserve']*100).'%)';?></td>
		</tr>
	<?			}
		} ?>
	</table>
	<div style="text-align:center">
		<input type="submit" value="����" style="margin-right:10px;">
		<input type="button" value="�ݱ�" onClick="javascript:closeWin();">
	</div>
	</form>	
</body>
</html>
