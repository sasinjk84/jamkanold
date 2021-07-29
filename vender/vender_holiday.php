<?php
/**
 * Created by PhpStorm.
 * User: x2chi-objet
 * Date: 2014-10-24
 * Time: 오후 2:49
 */
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/ext/func.php");
include_once($Dir."lib/class/pages.php");
//include_once($Dir."lib/class/rentproduct.php");
//include ("access.php");

$weekendtitles = rentProduct::_weekendVals();

if($_POST['act'] == 'add'){
	$indata = array();
	//$indata['code'] = preg_match('/^[0-9]{12}$/',$_POST['code'])?$_POST['code']:'000000000000';	
	
	if(_array($weekend)){
		
		foreach($weekendtitles as $weekendstr){			
			$sql = "select count(*) from vender_holiday_list where vender='".$vender."' and pridx='".$pridx."' and title='".$weekendstr."'";
			if(false === $res = mysql_query($sql,get_db_conn())){ _alert('DB 쿼리 오류','-1'); exit;}
			$excnt = mysql_result($res,0,0);
			
			$date = (in_array($weekendstr,$weekend))?'ok':'no';

			if(!$excnt) $sql = "insert into vender_holiday_list set vender='".$vender."',pridx='".$pridx."',title='".$weekendstr."',date='".$date."' ";
			//else $sql = "update vender_holiday_list set  date='".$date."' where vender='".$vender."' and pridx='".$pridx."' and date='".substr($date,0,4)."'";
			else $sql = "update vender_holiday_list set  date='".$date."' where vender='".$vender."' and pridx='".$pridx."' and title='".$weekendstr."'";
			if(false === $res = mysql_query($sql,get_db_conn())){ _alert('DB 갱신 쿼리 오류','-1');		exit;}
		}
		_alert('정상 처리 되었습니다.','/vender/vender_holiday.php?vender='.$vender.'&pridx='.$pridx);
		exit;
	}else{
		$chkyear = (!_empty($_POST['year']))?$_POST['year']:date('Y');
		if(!checkdate(intval($_POST['month']),intval($_POST['holidayDate']),intval($chkyear))){
			_alert('일자 값이 유효하지 않습니다.','-1');
			exit;
		}
		if(_empty($_POST['dayTitle'])){
			_alert('공휴일 명을 입력해주세요.','-1');
			exit;
		}else if(in_array($_POST['dayTitle'],$weekendtitles)){
			_alert('시스템 예약어로 사용 할수 없는 공휴일 명입니다..','-1');
			exit;
		}
		
		$indata['vender'] = $_POST['vender'];
		$indata['pridx'] = $_POST['pridx'];
		$indata['title'] = _escape($_POST['dayTitle'],false);
		if(!_empty($_POST['year'])) $indata['year'] = $_POST['year'];
		$indata['date'] = sprintf('%02d%02d',intval($_POST['month']),intval($_POST['holidayDate']));
		$insql = array();
		foreach($indata as $key=>$val){
			array_push($insql,$key."='".$val."'");
		}
		
		
		$sql = "select count(*) from vender_holiday_list where vender='".$indata['vender']."' pridx='".$pridx."' and and ".(isset($indata['year'])?" year='".$indata['year']."'":' year is null ')." and date='".$indata['date']."'";
		if(false !== $res = mysql_query($sql,get_db_conn())){
			if(mysql_result($res,0,0) > 0){
				_alert('이미 동일한 일자에 등록된 휴일 정보가 있습니다.','-1');
				exit;
			}
		}
		
		$sql = "insert into vender_holiday_list set ".implode(",",$insql);
		if(false === mysql_query($sql,get_db_conn())){
			_alert('DB 에러','-1');	
		}else{
			_alert('등록 되었습니다.',$_SERVER['PHP_SELF'].'?vender='.$_POST['vender'].'&pridx='.$_POST['pridx']);
		}
	}
	exit;
}else if($_POST['act'] == 'delete' && _isInt($_POST['delidx'])){
	$sql = "delete from vender_holiday_list where idx='".$_POST['delidx']."' limit 1";
	if(false === mysql_query($sql,get_db_conn())){
		_alert('DB 에러','-1');	
	}else{
		_alert('삭제 되었습니다.',$_SERVER['PHP_SELF'].'?vender='.$_POST['vender'].'&pridx='.$_POST['pridx']);
	}
	exit;
}

//$owhere = array();
//array_push($owhere," code='000000000000'");
//if(preg_match('/^[0-9]{12}$/',$_REQUEST['code'])) array_push($owhere," code='".$_REQUEST['vender']."'");
$sql = "select * from vender_holiday_list where vender='".$vender."' and pridx='".$pridx."'";
if(false === $res =  mysql_query($sql,get_db_conn())){
	_alert('DB 에러','-1');	
	exit;
}

$items = array();
$weekendconf = array();
if(mysql_num_rows($res)){
	while($item = mysql_fetch_assoc($res)){
		if(in_array($item['title'],$weekendtitles)){
			$weekendconf[$item['title']] = $item['date'];
		}else{
			array_push($items,$item);	
		}
	}
}

if(_array($weekendtitles) && !_array($weekendconf)){
	$sql = "select * from vender_holiday_list where vender='".$vender."' and pridx='".$pridx."' and title in ('".implode("','",$weekendtitles)."')";
	if(false !== $res =  mysql_query($sql,get_db_conn())){
		if(mysql_num_rows($res)){
			while($item = mysql_fetch_assoc($res)){
				$weekendconf[$item['title']] = $item['date'];
			}
		}
	}
}

?>
<html>
<head>
	<script type="text/javascript" src="<?=$Dir?>js/miniCalendar.js"></script>
	<script language="javascript" type="text/javascript">
	function delDate(idx){
		if(confirm('삭제 하시겠습니까?')){
			document.seasonInsertForm.act.value = 'delete';
			document.seasonInsertForm.delidx.value = idx;			
			document.seasonInsertForm.submit();
		}
	}
	
	
	function checkVal(){
		var f = document.seasonInsertForm;
		var testdate = parseInt(f.holidayDate);
		if(isNaN(testdate) || testdate < 1 || testdate > 31 ) alert('날짜 값이 올바르지 않습니다.');
		else return true;
		return false;	
	}
	</script>
	<link rel="stylesheet" href="style.css">
</head>
<body>

	<h2 style="background:url('../admin/images/member_mailallsend_imgbg.gif');"><img src="../admin/images/weekend_popt.gif" alt="성수기/준성수기 등록" /></h2>

	<TABLE WIDTH="96%" align="center" BORDER=0 CELLPADDING=0 CELLSPACING=0>
		<TR>
			<TD><IMG SRC="../admin/images/product_season_stitle4.gif" ALT="주말(공휴일) 등록" /></TD>
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
				1) 주말(공휴일)요금은 1일 단위로만 등록가능합니다.<br />
				2) 성수기와 주말(공휴일)요금이 겹칠 경우 주말(공휴일)요금이 우선됩니다.<br />
				※ 요금적용 순서 : 주말(공휴일)요금 > 성수기 > 준성수기 > 비수기
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

	<form name="seasonInsertForm" method="post" style="margin:0px;padding:0px;" onSubmit="javascript:return checkVal()">
	<input type="hidden" name="vender" value="<?=$_REQUEST['vender']?>" >
	<input type="hidden" name="pridx" value="<?=$_REQUEST['pridx']?>" >
	<input type="hidden" name="act" value="add">
	<input type="hidden" name="delidx" value="">
	<table border="0" cellpadding="0" cellspacing="0" width="96%" align="center" style="margin-bottom:0px;">
		<colgroup>
			<col width="140" />
			<col width="" />
		</colgroup>
		<tr><td background="../admin/images/table_top_line.gif" colSpan="2" /></td></tr>
		<tr>
			<td class="table_cell"><img width="8" height="11" src="../admin/images/icon_point2.gif" border="0"/> 날짜</td>
			<td class="td_con1">
				<select name="year">
					<option value="">매년반복</option>
					<? for($i=0;$i<20;$i++){ 
							$yearval = date('Y')+$i;
					?>
					<option value="<?=$yearval?>"><?=$yearval?></option>
					<? } ?>
				</select>
				년
				<select name="month">
				<? for($i=1;$i<=12;$i++){ ?>
					<option value="<?=$i?>"><?=$i?></option>					
				<? } ?>
				</select>월
				<input type="text" name="holidayDate" id="holidayDate" value="" class="input" style="width:30px;" maxlength="2">일
			</td>
		</tr>
		<tr><td background="../admin/images/table_con_line.gif" colSpan="2" style="height:1px" /></tr>
		<tr>
			<td class="table_cell"><img width="8" height="11" src="../admin/images/icon_point2.gif" border="0"/> 공휴일 명</td>
			<td class="td_con1"><input type="text" name="dayTitle" size="40" class="input" /> <input type="button" value="입력" onClick="submit();"></td>
		</tr>
		<tr><td background="../admin/images/table_top_line.gif" colSpan="2" /></td></tr>
	</table>
	</form>
	<form name="seasonInsertForm2" method="post" style="margin:0px;padding:0px;" onSubmit="javascript:return checkVal()">
	<input type="hidden" name="vender" value="<?=$_REQUEST['vender']?>" >
	<input type="hidden" name="pridx" value="<?=$_REQUEST['pridx']?>" >
	<input type="hidden" name="act" value="add">
	<input type="hidden" name="delidx" value="">
	<table border="0" cellpadding="0" cellspacing="0" width="96%" align="center" style="margin-bottom:15px;">
		<colgroup>
			<col width="140" />
			<col width="" />
		</colgroup>
		<tr><td background="../admin/images/table_top_line.gif" colSpan="2" style="height:1px" /></td></tr>
		<tr>
			<td class="table_cell"><img width="8" height="11" src="../admin/images/icon_point2.gif" border="0"/> 주말요금</td>
			<td class="td_con1"><input type="checkbox" name="weekend[]" value="sun" <?=$weekendconf['sun']=='ok'?'checked':''?> />일요일 <input type="checkbox" name="weekend[]" value="sat" <?=$weekendconf['sat'] == 'ok'?'checked':''?> />토요일 <input type="checkbox" name="weekend[]" value="fri" <?=$weekendconf['fri'] == 'ok'?'checked':''?> />금요일 <input type="button" value="적용" onClick="submit();"></td>
		</tr>
		<tr><td background="../admin/images/table_top_line.gif" colSpan="2" style="height:1px" /></td></tr>
	</table>
	</form>
	<table border="0" cellpadding="0" cellspacing="0" width="96%" align="center" class="tableBase">
		<tr>
			<th style="width:120px;" class="firstTh">날짜</th>
			<th>공휴일 명</th>
			<th style="width:60px;">삭제</th>
		</tr>
		<?
		if(count($items) < 1){ ?>
		<tr>
			<td colspan="3" style="text-align:center; padding:5px 0px;">이 상품에 대한 휴일 설정이 없습니다. <br> <span class="notice_blue" style="color:red">이 경우 판매자께서 설정한 전체 휴일설정 값이나 총관리자 설정값을 따릅니다.<br> 이 상품에 휴일을 설정하시거나 전체 설정값을 변경하려면 업체정보 설정>업체정보 관리에서 변경하세요. </span></td>
		</tr>
<?		}else{ 
			foreach($items as $item){
				if(in_array($item['title'],$weekendtitles)) continue;
				?>
		<tr style="text-align:center">
			<td  class="firstTd"><?=(_empty($item['year'])?'[매년]&nbsp;':$item['year'])?><?=substr($item['date'],0,2).'-'.substr($item['date'],2,2)?></td>
			<td><?=$item['title']?></td>
			<td><input type="image" src="../admin/images/btn_del.gif" onClick="delDate('<?=$item['idx']?>')" /></td>
		<? 	}
		} ?>			
		</tr>
	</table>

	<div style="margin:10px 0px;text-align:center;"><a href="javascript:window.close();"><img src="/images/common/bigview_btnclose.gif" border="0" alt="" /></a></div>

</body>
</html>