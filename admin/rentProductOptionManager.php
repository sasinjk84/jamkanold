<?php
header("Content-type: text/html; charset=euc-kr");
/**
 * Created by PhpStorm.
 * User: x2chi-objet
 * Date: 2014-11-13
 * Time: 오후 3:54
 */
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/ext/func.php");
include_once($Dir."lib/class/pages.php");
include ("access.php");


$goodStatusArray = rentProduct::_status();
$cinfo = "select c.useseason,c.pricetype from code_rent c left join tblproduct p on c.code = substr(p.productcode,1,12) where p.pridx='".$pridx."' limit 1";
if(false === $cres = mysql_query($cinfo,get_db_conn())){
	_alert('DB 질의 오류','-1');
	exit;	
}

if(mysql_num_rows($cres)){
	$haveseason = mysql_result($cres,0,0);
	$pricetype = mysql_result($cres,0,1);
}
if($haveseason != '1') $busySeason = $semiBusySeason = 0;

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	extract($_POST);
	if($act == 'delete'){
		$sql = "delete from rent_product_option where idx='".$_POST['idx']."' and pridx='".$_POST['pridx']."' limit 1";
		if(false === mysql_query($sql,get_db_conn())){
			_alert('삭제중 DB 오류가 발생했습니다.','-1');
		}else{
			_alert('삭제 되었습니다.',$_SERVER['PHP_SELF'].'?pridx='.$_POST['pridx']);
		}
	}else{		
		
		if(_empty($optionGrade)){
			_alert('옵션 등급이 전달되지 않았습니다.','-1');
			exit;
		}else if(_empty($optionName)){
			_alert('옵션명이 전달 되지 않았습니다.','-1');
			exit;
		}
		
		$sql = "`pridx` = '".$pridx."',
					`grade` = '".$optionGrade."',
					`optionName` = '".$optionName."',
					`nomalPrice` = '".$nomalPrice."',";
		if($pricetype == 'time'){	
			$sql .= "
				`halfPrice` = '".$halfPrice."',
				`timePrice` = '".$timePrice."',";
		}else{
			$sql .= "
				`halfPrice` = NULL,
				`timePrice` = NULL,";
		}
		$sql .= "
				`busySeason` = '".$busySeason."',
				`semiBusySeason` = '".$semiBusySeason."',
				`holidaySeason` = '".$holidaySeason."',
				`productCount` = '".$optionCount."'";
	
		switch($mode){
			case "update":
				if(!_isInt($idx)){
					_alert('식별 번호가 전달 되지 않았습니다.','-1');
					exit;
				}
				$sql = "UPDATE `rent_product_option` SET ".$sql." WHERE `idx` = '".$idx."'";
				break;		
			case "insert": 
				$sql = "INSERT `rent_product_option` SET ".$sql;
				break;
			default:
				break;		
		}
		if(!_empty($sql)){
			if(false === mysql_query($sql,get_db_conn())){
				_alert('DB 처리중 오류가 발생 했습니다.','-1');
				exit;
			}
			_alert('정상 처리 되었습니다.',$_SERVER['PHP_SELF'].'?pridx='.$pridx);
			exit;
		}
	}
}
?>

<script type="text/javascript">
	function checkPriceval(price,minval,maxval){
		price = parseInt(price);
		if(isNaN(minval) || minval <0) return false;

		if(minval){
			minval = parseInt(minval);
			if(isNaN(minval) || minval <1){
				alert('최소 비교 대상값 오류');
				return ;
			}
			if(price < minval) return false;
		}
		
		if(maxval){
			maxval = parseInt(maxval);
			if(isNaN(maxval) || maxval <1){
				alert('최대 비교 대상값 오류');
				return ;
			}
			if(minval && minval > maxval){
				alert('최대 비교 대상값 이 최소 비교대상값 보다 작음');
				return ;
			}
			if(price > maxval) return false;;
		}
		return true;
	}
	
	function rentOptInsert(name){
		var f = document.getElementById(name);		
		if(!f){
			alert('프로그램 오류');
			return;		
		}
		if(f.pridx.value.length == 0) {
			alert("상품코드가 누락 되었습니다.");
			return;
		}
		if(f.optionName.value.length == 0) {
			f.optionName.focus();
			alert("옵션명을 입력하세요.");
			return;
		}
		if(f.optionCount.value == 0) {
			f.optionCount.focus();
			alert("재고는 0개 이상을 입력하세요.");
			return;
		}
		
		var normalPrice = parseInt(f.nomalPrice.value);
		
		if(!checkPriceval(normalPrice,0)){
			f.nomalPrice.focus();
			alert("일반가(24시간)는 0원 이상을 입력하세요.");
			return;
		}
		
		<? if($pricetype == 'time'){ ?>
		var halfPrice = parseInt(f.halfPrice.value);
		var timePrice = parseInt(f.timePrice.value);
		if(!checkPriceval(halfPrice,0,normalPrice)){
			f.halfPrice.focus();
			alert("12시간 가격은 0원 이상 24시간 가격 이하를 입력하세요.");
			return;
		}
		if(!checkPriceval(timePrice,0,halfPrice)){
			f.timePrice.focus();
			alert("추가1시간당 가격은 0원 12시간 가격 이하를 입력하세요.");
			return;
		}		
		
		<? } ?>		
		f.method = "POST";
		f.submit();
	}
	
	function rentOptDelete(idx){
		var f = document.optDeliForm;
		if(confirm("삭제 데이터는 복구가 불가능 합니다.\r\n정말 삭게하시겠습니까?")){
			f.idx.value = idx;
			f.submit();
		}

	}
</script>

<link rel="stylesheet" href="style.css">
<form name="optDeliForm" method="post" action="<?=$_SERVER['PHP_SELF']?>">
<input type="hidden" name="pridx" value="<?=$pridx?>" />
<input type="hidden" name="act" value="delete" />
<input type="hidden" name="idx" value="" />
</form>
대여상품 옵션<? _pr($categoryRentInfo); ?>
<table border="0" cellpadding="0" cellspacing="0" width="97%" align="center" class="tableBase">
	<colgroup>
		<col width="240">
		<col width="100">
		<col width="">
		<col width="100">
		<col width="130">
	</colgroup>
	<tr>
		<th class="firstTh">옵션명</th>
		<th>등급</th>
		<th>가격 ( <? switch($pricetype){
						case 'time': echo '시간단위 요금'; break;
						case 'day': echo '하루(24시간)단위 요금'; break;
						case 'checkout': echo '숙박제(오후2시~오전11시) 요금'; break;
						default: echo '오류'; break;
										} ?>)</th>
		<th>재고량</th>
		<th>비고</th>
	</tr>

	<form name="insert" id="insert">
	<tr>
		<td class="firstTd" align="center"><input type="text" name="optionName" style="width:90%;" class="input" /></td>
		<td align="center">
			<select name="optionGrade">
				<?
				foreach ($goodStatusArray as $k=>$v) {
					echo "<option value='".$k."'>".$v."</option>";
				}
				?>
			</select>
		</td>
		<td align="center">
			<table cellpadding="0" cellspacing="0" class="tableBaseNoBorder">
				<colgroup>
					<col width="110">
					<col width="10">
					<col width="">
				</colgroup>
				<tr>
					<td>일반가(24시간)</td>
					<td>:</td>
					<td><input type="text" name="nomalPrice" value="0" class="input" />원</td>
				</tr>
			<? if($pricetype == 'time'){ ?>
				<tr>
					<td>12시간</td>
					<td>:</td>
					<td><input type="text" name="halfPrice" value="0" class="input" />원</td>
				</tr>
				<tr>
					<td>추가1시간</td>
					<td>:</td>
					<td><input type="text" name="timePrice" value="0" class="input" />원</td>
				</tr>
				<? if($haveseason == '1'){ ?>
					<tr>
						<td>성수기 할증</td>
						<td>:</td>
						<td><input type="text" name="busySeason" value="0" class="input" />%</td>
					</tr>
					<tr>
						<td>준성수기 할증</td>
						<td>:</td>
						<td><input type="text" name="semiBusySeason" value="0" class="input" />%</td>
					</tr>
				<? } ?>
					<tr>
						<td>주말 할증</td>
						<td>:</td>
						<td><input type="text" name="holidaySeason" value="0" class="input" />%</td>
					</tr>
			<? }else{ ?>
				<? if($haveseason == '1'){ ?>
					<tr>
						<td>성수기 추가액</td>
						<td>:</td>
						<td><input type="text" name="busySeason" value="0" class="input" />원</td>
					</tr>
					<tr>
						<td>준성수기 추가액</td>
						<td>:</td>
						<td><input type="text" name="semiBusySeason" value="0" class="input" />원</td>
					</tr>
				<? } ?>
					<tr>
						<td>주말 추가액</td>
						<td>:</td>
						<td><input type="text" name="holidaySeason" value="0" class="input" />원</td>
					</tr>
			<?	}?>
			
			</table>
		</td>
		<td align="center"><input type="text" name="optionCount" value="0" style="width:60px;text-align:center;" class="input" />개</td>
		<td align="center"><a href="javascript:rentOptInsert('insert');"><img src="images/btn_badd2.gif" /></a></td>
	</tr>
		<input type="hidden" name="mode" value="insert">
		<input type="hidden" name="pridx" value="<?=$pridx?>">
	</form>

	<?
	$listSQL = "SELECT * FROM rent_product_option WHERE pridx = ".$pridx." ORDER BY idx DESC ";
	$listRES = mysql_query( $listSQL, get_db_conn());
	while ( $listROW = mysql_fetch_assoc($listRES) ) {
	?>
	<form name="modify_<?=$listROW['idx']?>"  id="modify_<?=$listROW['idx']?>">
		<tr>
			<td class="firstTd" align="center"><input type="text" name="optionName" value="<?=$listROW['optionName']?>" style="width:90%;" class="input" /></td>
			<td align="center">
				<select name="optionGrade">
					<?
					foreach ($goodStatusArray as $k=>$v) {
						$sel = ( $listROW['grade'] == $k ? 'selected' : '' );
						echo "<option value='".$k."' ".$sel.">".$v."</option>";
					}
					?>
				</select>
			</td>
			<td align="center">
				<table cellpadding="0" cellspacing="0" class="tableBaseNoBorder">
					<colgroup>
						<col width="110">
						<col width="10">
						<col width="">
					</colgroup>
					<tr>
						<td>일반가(24시간)</td>
						<td>:</td>
						<td><input type="text" name="nomalPrice" value="<?=$listROW['nomalPrice']?>" class="input" />원</td>
					</tr>
					<? if($pricetype == 'time'){ ?>
					<tr>
						<td>12시간</td>
						<td>:</td>
						<td><input type="text" name="halfPrice" value="<?=$listROW['halfPrice']?>" class="input" />원</td>
					</tr>
					<tr>
						<td>추가1시간</td>
						<td>:</td>
						<td><input type="text" name="timePrice" value="<?=$listROW['timePrice']?>" class="input" />원</td>
					</tr>
					<? if($haveseason == '1'){ ?>
						<tr>
							<td>성수기 할증</td>
							<td>:</td>
							<td><input type="text" name="busySeason" value="<?=$listROW['busySeason']?>" class="input" />%</td>
						</tr>
						<tr>
							<td>준성수기 할증</td>
							<td>:</td>
							<td><input type="text" name="semiBusySeason" value="<?=$listROW['semiBusySeason']?>" class="input" />%</td>
						</tr>
					<? } ?>
						<tr>
							<td>주말 할증</td>
							<td>:</td>
							<td><input type="text" name="holidaySeason" value="<?=$listROW['holidaySeason']?>" class="input" />%</td>
						</tr>
				<? }else{ ?>
					<? if($haveseason == '1'){ ?>
					<tr>
						<td>성수기 추가액</td>
						<td>:</td>
						<td><input type="text" name="busySeason" value="<?=$listROW['busySeason']?>" class="input" />원</td>
					</tr>
					<tr>
						<td>준성수기 추가액</td>
						<td>:</td>
						<td><input type="text" name="semiBusySeason" value="<?=$listROW['semiBusySeason']?>" class="input" />원</td>
					</tr>
					<? } ?>
					<tr>
						<td>주말 추가액</td>
						<td>:</td>
						<td><input type="text" name="holidaySeason" value="<?=$listROW['holidaySeason']?>" class="input" />원</td>
					</tr>
				<?	}?>
				</table>
			</td>
			<td align="center"><input type="text" name="optionCount" value="<?=$listROW['productCount']?>" style="width:60px;text-align:center;" class="input" />개</td>
			<td align="center"><a href="javascript:rentOptInsert('modify_<?=$listROW['idx']?>');"><img src="images/btn_edit.gif" ></a> <a href="javascript:rentOptDelete('<?=$listROW['idx']?>');"><img src="images/btn_del.gif" /></a></td>
		</tr>
		<input type="hidden" name="mode" value="update">
		<input type="hidden" name="idx" value="<?=$listROW['idx']?>">
		<input type="hidden" name="pridx" value="<?=$pridx?>">
		
		
	</form>
	<?
	}
	?>

</table>