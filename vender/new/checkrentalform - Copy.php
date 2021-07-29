<?
$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/ext/func.php");
include_once($Dir."lib/ext/rent.php");
include_once($Dir."lib/venderlib.php");
include_once("../access.php");
/*
_pr($_venderdata);
_pr($_VenderInfo);*/
ob_start();
if($_REQUEST['act'] == 'locallist'){
	// 대여 출고지 정보 리스트	
	$value = array("display"=>1,'vender'=>($_venderdata->vender)); // 노출 만 표시
	$localList = rentLocalList( $value );
	if(!isset($localList[$rentProduct['location']])) $rentProduct['location']= 0;
	?>
	<select name="location" style="float:left">
			<option value="0">--소재지 선택--</option>
		<?
			// 대여 출고지 정보 리스트	
			$value = array("display"=>1,'vender'=>($_venderdata->vender)); // 노출 만 표시
			$localList = rentLocalList( $value );
			if(!isset($localList[$rentProduct['location']])) $rentProduct['location']= 0;
			
			foreach ( $localList as $k=>$v ) { 
				$sel = ($rentProduct['location'] == $v['location'])?'checked="checked"':'';
			?>
			<option value="<?=$v['location']?>" <?=$sel?>><?=$v['title']?></option>
		<? } ?>
		</select>
<?
	$ret['cont'] = ob_get_clean();
}else{
	$code = str_pad($_REQUEST['code'],12,'0');
	$categoryRentInfo = categoryRentInfo($code);					
	if(_array($categoryRentInfo)){	
		$goodsTypeSel[1] = "checked";
		$commi = rentCommitionByCategory($code,$_venderdata->vender);
	
?>
<TR class="rentalItemArea">
	<TD style="background:#f5f5f5 url(images/line01.gif) no-repeat right; padding:9px"><font color="FF4800">*</font>상품구분및 관리 형태</TD>
	<TD colspan="3" style="padding-left:5px;">
		
		<div style="margin-bottom:5px;">
			<input type="radio" name="istrust" value="1" <?=($rentProduct['istrust']!='-1' && $rentProduct['istrust']!='0')?'checked':''?>  onclick="javascript:toggleTrust()"  />셀프관리 (수수료  <?=number_format($commi['self'])?>%)
			<? if($rentProduct['istrust']=='0'){ ?>
			<input type="radio" name="istrust" value="0" style="margin-left:8px;" <?=($rentProduct['istrust']=='0')?'checked':''?>  onclick="javascript:toggleTrust()" />위탁관리 (수수료  <?=number_format($commi['main'])?>%)
			<? }else{ ?>
			<input type="radio" name="istrust" value="-1" style="margin-left:8px;" <?=($rentProduct['istrust']=='-1')?'checked':''?> onclick="javascript:toggleTrust()" />위탁관리 요청 (수수료  <?=number_format($commi['main'])?>%)
			<? } ?>
		</div>
		<div id="goodsTypeLocalDiv" class="rentalItemArea">	
			<table border="0" cellpadding="0" cellspacing="0"  class="tableBaseSe" style="border-top:1px solid #ededed;">
				<tr>
					<th style="width:150px;"><img width="8" height="11" src="images/icon_point2.gif" border="0"/>상품 타입</th>
					<td style="text-align:left;padding-left:10px;">
						<input type=radio id="itemType1" name="itemType" value="product" checked="checked"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=itemType1>상품</label> &nbsp;
						<input type=radio id="itemType2" name="itemType" value="location" ><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=itemType2>장소</label> &nbsp;
					</td>
					<? if(!_empty($categoryRentInfo['pricetype'])){ ?>
					<th style="width:120px;"><img width="8" height="11" src="images/icon_point2.gif" border="0"/>가격구분</th>
					<td style="text-align:left;padding:0px 10px;">
						<? switch($categoryRentInfo['pricetype']){
								case 'time': echo '시간단위 요금'; break;
								case 'day': echo '$하루(24시간)단위 요금'; break;
								case 'checkout': echo '숙박제(오후2시~오전11시) 요금'; break;
								default: echo '오류'; break;
						} ?>
					</td>
					<? } ?>									
					<th style="width:120px;"><img width="8" height="11" src="images/icon_point2.gif" border="0"/>성수기사용</th>
					<td style="text-align:left;padding:0px 10px;">
						<? echo ($categoryRentInfo['useseason'] == '1')?'성수기 사용':'사용안함';?>
					</td>																
				</tr>
			</table>
		</div>
	</TD>
</TR>
<TR class="rentalItemArea">
	<td height=1 colspan=4 bgcolor=E7E7E7></td>
</TR>

<TR class="rentalItemArea">
	<TD style="background:#f5f5f5 url(images/line01.gif) no-repeat right; padding:9px"><font color="FF4800">*</font>소재지</TD>
	<td colspan="3" style="padding-left:5px;">
	<?
	// 대여 출고지 정보 리스트	
	$value = array("display"=>1,'vender'=>($_venderdata->vender)); // 노출 만 표시
	$localList = rentLocalList( $value );
	if(!isset($localList[$rentProduct['location']])) $rentProduct['location']= 0;
	?>
	<div id="localListDiv">	
		<select name="location" style="float:left">
			<option value="0">--소재지 선택--</option>
		<?
			// 대여 출고지 정보 리스트	
			$value = array("display"=>1,'vender'=>($_venderdata->vender)); // 노출 만 표시
			$localList = rentLocalList( $value );
			if(!isset($localList[$rentProduct['location']])) $rentProduct['location']= 0;
			
			foreach ( $localList as $k=>$v ) { 
				$sel = ($rentProduct['location'] == $v['location'])?'checked="checked"':'';
			?>
			<option value="<?=$v['location']?>" <?=$sel?>><?=$v['title']?></option>
		<? } ?>
		</select>			
	</div>
	<input type="button" value="출고지 관리" onclick="javascript:openLocalWin();" style="margin-left:5px;" /></h6>
	<br />
	<span style="color:#ec2f36; font-weight:bold">대여상품 옵션은 상품 정보 최초 생성후 수정을 통해서 처리 가능합니다.</span>																																		
		
	</TD>
</TR>
<TR class="rentalItemArea">
	<td height=1 colspan=4 bgcolor=E7E7E7></td>
</TR>
<TR class="rentalItemArea">
	<TD bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9">노출아이디선택</TD>
	<TD class="td_con1" colspan="3">
		<input type="radio" name="rentdispId" value="self" <?=($rentProduct['rentdispId'] != 'main')?'checked':''?> />본인
		<input type="radio" name="rentdispId" value="main" <?=($rentProduct['rentdispId'] == 'main')?'checked':''?> />잠깐본점
	</TD>
</TR>
<TR class="rentalItemArea">
	<td height=1 colspan=4 bgcolor=E7E7E7></td>
</TR>
<TR class="rentalItemArea">
	<TD bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9">미니홈노출</TD>
	<TD class="td_con1" colspan="3">
	<input type="radio" name="rentdispminihome" value="self" <?=($rentProduct['rentdispminihome'] != 'main')?'checked':''?> />예
	<input type="radio" name="rentdispminihome" value="main" <?=($rentProduct['rentdispminihome'] == 'main')?'checked':''?> />아니오
	</TD>
</TR>
<TR class="rentalItemArea">
	<td height=1 colspan=4 bgcolor=E7E7E7></td>
</TR>
<?
		$ret['cont'] = ob_get_clean();
		$ret['checkbox'] = '<input type=radio id="goodsType1" name="goodsType" value="1" checked="checked" onclick="toggleGoodsType(\'1\');"><label style=\'cursor:hand;\' for=goodsType1>판매상품</label> &nbsp;	<input type=radio id="goodsType2" name="goodsType" value="2"  onclick="toggleGoodsType(\'2\'); "><label style="cursor:hand;" for=goodsType2>대여상품</label><a href="javascript:document.location.reload()">';	
	}else{
		$ret['cont'] = '<input type=radio id="goodsType1" name="goodsType" value="1" checked="checked" onclick="toggleGoodsType(\'1\');"><label style=\'cursor:hand;\' for=goodsType1>판매상품</label> ';
		$ret['checkbox'] = '';
	}
}
// php  5.2.0 이상은 추가
$phpVer = str_replace(".","",phpversion());
if( $phpVer >= 520 ) array_walk($ret,'_encode');

echo json_encode($ret);
exit;

?>