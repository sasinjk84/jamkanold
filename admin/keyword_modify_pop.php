<?php
/**
 * Created by PhpStorm.
 * User: x2chi-objet
 * Date: 2014-10-23
 * Time: 오전 9:09
 */
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/ext/func.php");
include_once($Dir."lib/class/pages.php");
INCLUDE ("access.php");

if($_POST['act'] == 'modify'){	

	$updateSQL = "UPDATE tblkwgroup SET kwgroup='".$_POST['kwgroup']."' WHERE kg_idx='".$_POST['kg_idx']."' ";
	if(false === mysql_query($updateSQL,get_db_conn())){
		_alert('DB 질의 오류','-1');
	}else{
		_alert('수정되었습니다.',$_SERVER['PHP_SELF']);
	}
	exit;

}else if($_POST['act'] == 'delete' && _isInt($_POST['kg_idx'])){

	$sql = "DELETE FROM tblkwgroup WHERE kg_idx='".$_POST['kg_idx']."'";
	if(false === mysql_query($sql,get_db_conn())){
		_alert('삭제','-1');
	}else{
		$sql2 = "DELETE FROM tblkeyword WHERE kg_idx = '".$_POST['kg_idx']."' ";
		mysql_query($sql2,get_db_conn());
		_alert('삭제되었습니다.',$_SERVER['PHP_SELF']);
	}
	exit;

}else{
	$sql = "select * from tblkwgroup order by kg_idx asc";
	if(false === $res = mysql_query($sql,get_db_conn())){
		_alert('DB 질의 오류',0);
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
<link rel="stylesheet" href="<?=$Dir?>css/ui-lightness/jquery-ui-1.10.4.custom.min.css">
	<script src="<?=$Dir?>js/jquery-1.10.2.js"></script>
<script src="<?=$Dir?>js/jquery-ui-1.10.4.custom.min.js"></script>

<script language="javascript" type="text/jscript">
function delKeygroup(idx){
	if(confirm('삭제 하시겠습니까? 하위 키워드도 모두 삭제됩니다.')){
		document.keygroupForm.act.value = 'delete';
		document.keygroupForm.kg_idx.value = idx;			
		document.keygroupForm.submit();
	}
}

function modifyKeygroup(idx){
	document.keygroupForm.act.value = 'modify';
	document.keygroupForm.kwgroup.value = document.keygroupForm["kwgroup_"+idx].value;	
	document.keygroupForm.kg_idx.value = idx;
	document.keygroupForm.submit();
}
</script>
<link rel="stylesheet" href="style.css">
</head>
<body>
	<h4 style="text-align:center">검색 키워드 관리</h4>

	<form name="keygroupForm" action="<?=$_SERVER['PHP_SELF']?>" method="post" style="margin:0px;padding:0px;">
	<input type="hidden" name="act" value="modify">
	<input type="hidden" name="kg_idx" value="" />
	<input type="hidden" name="kwgroup" value="" />
	<table border="0" cellpadding="0" cellspacing="0" width="96%" align="center" class="tableBase">
		<colgroup>
			<col width="120">
			<col width="">
			<col width="70">
		</colgroup>
		<tr>
			<th class="firstTh">키워드 분류</th>
			<th>수정</th>
			<th>삭제</th>
		</tr>
		<?
		if(count($items) < 1){ ?>
		<tr>
			<td colspan="3" style="text-align:center; padding:5px 0px">등록된 정보가 없습니다.</td>
		</tr>
		<? 
		}else{
			foreach($items as $item){ 
		?>	
		<tr style="text-align:center">
			<td><input type="text" name="kwgroup_<?=$item['kg_idx']?>" value="<?=$item['kwgroup']?>"></td>
			<td><button type="button" onClick="modifyKeygroup('<?=$item['kg_idx']?>');">수정</button></td>
			<td><button type="button" onClick="delKeygroup('<?=$item['kg_idx']?>');">삭제</button></td>
		</tr>
		<?	
			}
		} 
		?>
	</table>
	</form>

	<div style="margin:10px 0px;text-align:center;"><a href="javascript:window.close();"><img src="/images/common/bigview_btnclose.gif" border="0" alt="" /></a></div>

</body>
</html>
