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
//INCLUDE ("access.php");

if(!$_REQUEST['vender']){
	_alert('입점업체 식별 코드가 전달되지 않았습니다.',0);
	exit;
}

extract($_REQUEST);
if($_REQUEST['act'] == 'add'){	
	$sql = "select count(*) from vender_season_range where vender='".$_REQUEST['vender']."' and pridx='".$pridx."' and ((start between '".$seasonStartDate."' and '".$seasonEndDate."' and  end between '".$seasonStartDate."' and '".$seasonEndDate."') ||  (start <= '".$seasonStartDate."' and end >= '".$seasonEndDate."'))";
	
	if(false === $res = mysql_query($sql,get_db_conn())){
		_alert('DB 질의 오류','-1');
		exit;
	}
	if(mysql_result($res,0,0) > 0){
		_alert('중복되는 기간이 있습니다.','-1');
		exit;
		
	}	
	$insertSQL = "INSERT `vender_season_range` SET `vender`='".$vender."', pridx='".$pridx."', `type` = '".$seasonType."', `start` = '".$seasonStartDate."', `end` = '".$seasonEndDate."' ";
	if(false === mysql_query($insertSQL,get_db_conn())){
		_alert('DB 질의 오류','-1');
	}else{
		_alert('등록되었습니다.',$_SERVER['PHP_SELF'].'?vender='.$_REQUEST['vender'].'&pridx='.$_REQUEST['pridx']);
	}
	exit;
}else if($_POST['act'] == 'delete' && _isInt($_POST['delidx'])){
	$sql = "delete from vender_season_range where idx='".$_POST['delidx']."' limit 1";
	if(false === mysql_query($sql,get_db_conn())){
		_alert('삭제','-1');
	}else{
		_alert('삭제되었습니다.',$_SERVER['PHP_SELF'].'?vender='.$_REQUEST['vender'].'&pridx='.$_REQUEST['pridx']);
	}
	exit;
}else{
	$sql = "select * from vender_season_range where vender='".$_REQUEST['vender']."' and pridx='".$_REQUEST['pridx']."' order by start desc";
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
<script type="text/javascript">
	$(function(){
		$("#seasonStartDate").datepicker({
			yearRange: 'c-0:c+2',
			dateFormat:'yymmdd',
			monthNamesShort:['01','02','03','04','05','06','07','08','09','10','11','12']
		});
		$("#seasonEndDate").datepicker({
			yearRange: 'c-0:c+2',
			dateFormat:'yymmdd',
			monthNamesShort:['01','02','03','04','05','06','07','08','09','10','11','12']
		});
	});
</script>
	<script language="javascript" type="text/jscript">
	function delDate(idx){
		if(confirm('삭제 하시겠습니까?')){
			document.seasonInsertForm.act.value = 'delete';
			document.seasonInsertForm.delidx.value = idx;			
			document.seasonInsertForm.submit();
		}
	}
	
	function checkRange(){		
		if(document.seasonInsertForm.seasonStartDate.value == ''){
			alert('시작일을 입력하세요');
		}else if(document.seasonInsertForm.seasonEndDate.value == ''){
			alert('종료일을 입력하세요');
		}else if(document.seasonInsertForm.seasonStartDate.value > document.seasonInsertForm.seasonEndDate.value){
			alert('종료일은 시작일 보다 빠를 수 없습니다.');
		}else{
			return true;
		}
		return false;
	}
	</script>
	<link rel="stylesheet" href="style.css">
</head>
<body>

	<h2 style="background:url('../admin/images/member_mailallsend_imgbg.gif');"><img src="../admin/images/season_popt.gif" alt="성수기/준성수기 등록" /></h2>

	<TABLE WIDTH="96%" align="center" BORDER=0 CELLPADDING=0 CELLSPACING=0>
		<TR>
			<TD><IMG SRC="../admin/images/product_season_stitle3.gif" ALT="성수기/준성수기 등록" /></TD>
		</TR>
	</TABLE>
	<TABLE WIDTH="96%" align="center" BORDER=0 CELLPADDING=0 CELLSPACING=0>
		<TR>
			<TD><IMG SRC="../admin/images/distribute_01.gif"></TD>
			<TD COLSPAN=2 background="../admin/images/distribute_02.gif"></TD>
			<TD><IMG SRC="../admin/images/distribute_03.gif"></TD>
		</TR>
		<TR>
			<TD background="../admin/images/distribute_04.gif"></TD>
			<TD class="notice_blue"><IMG SRC="../admin/images/distribute_img.gif" ></TD>
			<TD width="100%" class="notice_blue">
				1) 성수기/준성수기 미지정 구간은 비수기로 적용됩니다(표시내용 없음).<br />
				2) 성수기와 주말(공휴일)요금이 겹칠 경우 주말(공휴일)요금이 우선됩니다.<br />
				※ 요금적용 순서 : 주말(공휴일)요금 > 성수기 > 준성수기 > 비수기<br>
				<span style="color:red">※ 변경 가격 정보를 하위카테고리에 일괄 적용 하기 위해서는 카테고리 편집창에서 성수기사용 항목에서 <span style="font-weight:bold">하부카테고리일괄 적용</span>을 선택 하신후 해당카테고리 정보를 저장 하셔야 반영 됩니다.</span>
			</TD>
			<TD background="../admin/images/distribute_07.gif"></TD>
		</TR>
		<TR>
			<TD><IMG SRC="../admin/images/distribute_08.gif"></TD>
			<TD COLSPAN=2 background="../admin/images/distribute_09.gif"></TD>
			<TD><IMG SRC="../admin/images/distribute_10.gif"></TD>
		</TR>
		<tr><td height="5"></td></tr>
	</TABLE>

	<form name="seasonInsertForm" action="<?=$_SERVER['PHP_SELF']?>" method="post" style="margin:0px;padding:0px;" onSubmit="javascript:return checkRange();">
	<input type="hidden" name="act" value="add">
	<input type="hidden" name="vender" value="<?=$_REQUEST['vender']?>">
	<input type="hidden" name="pridx" value="<?=$_REQUEST['pridx']?>" >
	<input type="hidden" name="delidx" value="" />
	<table border="0" cellpadding="0" cellspacing="0" width="96%" align="center" style="margin-bottom:15px; border-bottom:1px solid #ccc; border-top:1px solid #ccc">
		<tr>
			<td style="width:120px;" class="table_cell"><img width="8" height="11" src="../admin/images/icon_point2.gif" border="0"/>등록</td>
			<td class="td_con1">
				<select name="seasonType">
					<option value="busy">성수기</option>
					<option value="semi">준성수기</option>
				</select>
			</td>
			<td style="width:140px;" class="table_cell"><img width="8" height="11" src="../admin/images/icon_point2.gif" border="0"/> 시작일~종료일</td>
			<td class="td_con1">
				<input type="text" name="seasonStartDate" id="seasonStartDate" value="<?=date("Ymd")?>" style="width:60px;" class="input" readonly>
				<span id="seasonStartDateCal" style="position:absolute;display:none;border:1px solid #d9d9d9;padding:3px;background-color: #FFFFFF;z-index:1000;"></span>
				~
				<input type="text" name="seasonEndDate" id="seasonEndDate" value="<?=date("Ymd")?>" style="width:60px;" class="input"  readonly>
			
				<input type="submit" value="입력" />
			</td>
		</tr>
		<tr><td background="../admin/images/table_top_line.gif" colSpan="4" /></td></tr>
	</table>
	</form>


	<table border="0" cellpadding="0" cellspacing="0" width="96%" align="center" class="tableBase">
		<colgroup>
			<col width="120">
			<col width="">
			<col width="70">
		</colgroup>
		<tr>
			<th class="firstTh">타입</th>
			<th>기간</th>
			<th>삭제</th>
		</tr>
		<?
		if(count($items) < 1){ ?>
		<tr>
			<td colspan="3" style="text-align:center; padding:5px 0px">이 상품에 대한 성수기 설정이 없습니다. <br> <span class="notice_blue" style="color:red">이 경우 판매자께서 설정한 전체 성수기설정 값이나 총관리자 설정값을 따릅니다.<br> 이 상품에 성수기를 설정하시거나 전체 설정값을 변경하려면 업체정보 설정>업체정보 관리에서 변경하세요. </span></td>
		</tr>
	<? }else{
			foreach($items as $item){ ?>	
		<tr style="text-align:center">
			<td><?=$item['type']=='busy'?"성수기":"준성수기"?></td>
			<td><?=$item['start']." ~ ".$item['end']?></td>
			<td><input type="image" src="../admin/images/btn_del.gif" onClick="delDate('<?=$item['idx']?>');" /></td>
		</tr>
	<?		}
		} ?>
	</table>

	<div style="margin:10px 0px;text-align:center;"><a href="javascript:window.close();"><img src="/images/common/bigview_btnclose.gif" border="0" alt="" /></a></div>

</body>
</html>
