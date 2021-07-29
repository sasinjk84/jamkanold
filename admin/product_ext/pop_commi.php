<?php
$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/ext/func.php");
include_once($Dir."lib/class/pages.php");
INCLUDE ("../access.php");

if(!preg_match('/^[0-9]{12}$/',$_REQUEST['code'])){
	_alert('카테고리 식별 코드가 전달되지 않았습니다.',0);
	exit;
}

extract($_REQUEST);

if($_REQUEST['act'] == 'update'){	
	if(!_isInt($_REQUEST['main']) || $_REQUEST['main'] > 100){
		_alert('위탁 수수료는 100 이내 숫자는 숫자만 입력가능합니다.','-1');
		exit;
	}
	if(!_isInt($_REQUEST['self'])  || $_REQUEST['self'] > 100){
		_alert('셀프 수수료는 100 이내 숫자는 숫자만 입력가능합니다.','-1');
		exit;
	}
	$sql = "insert into code_rent set code='".$_REQUEST['code']."',commission_self='".$_REQUEST['self']."',commission_main='".$_REQUEST['main']."' ON DUPLICATE KEY UPDATE commission_self='".$_REQUEST['self']."',commission_main='".$_REQUEST['main']."'";
		
	if(false === mysql_query($sql,get_db_conn())){
		_alert('DB 질의 오류','-1');
		exit;
	}else{ ?>
	<script language="javascript" type="text/javascript">
	window.opener.location.reload();
	window.close();
	</script>
<?
	exit;
	}
}

$sql = "select * from code_rent where code='".$_REQUEST['code']."' limit 1";	
if(false === $res = mysql_query($sql,get_db_conn())){
	_alert('DB 질의 오류',0);
	exit;
}
$item = mysql_fetch_assoc($res);
?>

<html>
<head>		
<link rel="stylesheet" href="style.css">
<script language="javascript" type="text/javascript" src="/js/jquery-1.10.2.min.js"></script>
<script language="javascript" type="text/javascript">
$(function(){	
	$('#percentForm').on('submit',function(e){
		if($(this).find('input[name=act]').val() != 'delete'){
			var s = parseInt($('#selfcommi').val());
			var m = parseInt($('#maincommi').val());			
			var check = false;
			
			if(isNaN(s) || s < 1 || s> 100){
				alert('셀프 수수료를 올바르게 입력해주세요.');
				$('#selfcommi').focus();
			}else if(isNaN(m) || m < 1|| m>100){
				alert('위탁 수수료를 올바르게 입력하세요.');
				$('#maincommi').focus();
			}else{
				check = true;
			}	
		}else{
			check = true;
		}
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
	<h2 style="background:url('/admin/images/member_mailallsend_imgbg.gif'); font-size:15px; color:white; padding:5px 0px 5px 5px;">수수료 관리</h2>	
	<form name="percentForm" id="percentForm" method="post" action="<?=$_SERVER['PHP_SELF']?>">
	<input type="hidden" name="act" value="update" />
	<input type="hidden" name="code" value="<?=$_REQUEST['code']?>">
	<table cellpadding="0" cellspacing="0" class="infoListTbl" style="margin-top:7px; border-bottom:0px; width:100%">
		<tr>
			<th style="width:100px;">셀프 수수료</th>
			<td  style="padding:5px;" class="norbl"><input type="text" name="self" id="selfcommi" value="<?=$item['commission_self']?>" style="width:40px;" />%</td>
		</tr>
		<tr>
			<th style="width:100px;">위탁 수수료</th>
			<td  style="padding:5px;"  class="norbl"><input type="text" name="main" id="maincommi" value="<?=$item['commission_main']?>" style="width:40px;" />%</td>
		</tr>			
	</table>
	<div style="text-align:center">
		<input type="submit" value="저장" style="margin-right:10px;">
		<input type="button" value="닫기" onClick="javascript:closeWin();">
	</div>
	</form>	
</body>
</html>
