<?php
header("Content-type: text/html; charset=euc-kr");
/**
 * Created by PhpStorm.
 * User: x2chi-objet
 * Date: 2014-11-13
 * Time: ���� 3:54
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
	_alert('DB ���� ����','-1');
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
			_alert('������ DB ������ �߻��߽��ϴ�.','-1');
		}else{
			_alert('���� �Ǿ����ϴ�.',$_SERVER['PHP_SELF'].'?pridx='.$_POST['pridx']);
		}
	}else{		
		
		if(_empty($optionGrade)){
			_alert('�ɼ� ����� ���޵��� �ʾҽ��ϴ�.','-1');
			exit;
		}else if(_empty($optionName)){
			_alert('�ɼǸ��� ���� ���� �ʾҽ��ϴ�.','-1');
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
					_alert('�ĺ� ��ȣ�� ���� ���� �ʾҽ��ϴ�.','-1');
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
				_alert('DB ó���� ������ �߻� �߽��ϴ�.','-1');
				exit;
			}
			_alert('���� ó�� �Ǿ����ϴ�.',$_SERVER['PHP_SELF'].'?pridx='.$pridx);
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
				alert('�ּ� �� ��� ����');
				return ;
			}
			if(price < minval) return false;
		}
		
		if(maxval){
			maxval = parseInt(maxval);
			if(isNaN(maxval) || maxval <1){
				alert('�ִ� �� ��� ����');
				return ;
			}
			if(minval && minval > maxval){
				alert('�ִ� �� ��� �� �ּ� �񱳴�� ���� ����');
				return ;
			}
			if(price > maxval) return false;;
		}
		return true;
	}
	
	function rentOptInsert(name){
		var f = document.getElementById(name);		
		if(!f){
			alert('���α׷� ����');
			return;		
		}
		if(f.pridx.value.length == 0) {
			alert("��ǰ�ڵ尡 ���� �Ǿ����ϴ�.");
			return;
		}
		if(f.optionName.value.length == 0) {
			f.optionName.focus();
			alert("�ɼǸ��� �Է��ϼ���.");
			return;
		}
		if(f.optionCount.value == 0) {
			f.optionCount.focus();
			alert("���� 0�� �̻��� �Է��ϼ���.");
			return;
		}
		
		var normalPrice = parseInt(f.nomalPrice.value);
		
		if(!checkPriceval(normalPrice,0)){
			f.nomalPrice.focus();
			alert("�Ϲݰ�(24�ð�)�� 0�� �̻��� �Է��ϼ���.");
			return;
		}
		
		<? if($pricetype == 'time'){ ?>
		var halfPrice = parseInt(f.halfPrice.value);
		var timePrice = parseInt(f.timePrice.value);
		if(!checkPriceval(halfPrice,0,normalPrice)){
			f.halfPrice.focus();
			alert("12�ð� ������ 0�� �̻� 24�ð� ���� ���ϸ� �Է��ϼ���.");
			return;
		}
		if(!checkPriceval(timePrice,0,halfPrice)){
			f.timePrice.focus();
			alert("�߰�1�ð��� ������ 0�� 12�ð� ���� ���ϸ� �Է��ϼ���.");
			return;
		}		
		
		<? } ?>		
		f.method = "POST";
		f.submit();
	}
	
	function rentOptDelete(idx){
		var f = document.optDeliForm;
		if(confirm("���� �����ʹ� ������ �Ұ��� �մϴ�.\r\n���� ����Ͻðڽ��ϱ�?")){
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
�뿩��ǰ �ɼ�<? _pr($categoryRentInfo); ?>
<table border="0" cellpadding="0" cellspacing="0" width="97%" align="center" class="tableBase">
	<colgroup>
		<col width="240">
		<col width="100">
		<col width="">
		<col width="100">
		<col width="130">
	</colgroup>
	<tr>
		<th class="firstTh">�ɼǸ�</th>
		<th>���</th>
		<th>���� ( <? switch($pricetype){
						case 'time': echo '�ð����� ���'; break;
						case 'day': echo '�Ϸ�(24�ð�)���� ���'; break;
						case 'checkout': echo '������(����2��~����11��) ���'; break;
						default: echo '����'; break;
										} ?>)</th>
		<th>���</th>
		<th>���</th>
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
					<td>�Ϲݰ�(24�ð�)</td>
					<td>:</td>
					<td><input type="text" name="nomalPrice" value="0" class="input" />��</td>
				</tr>
			<? if($pricetype == 'time'){ ?>
				<tr>
					<td>12�ð�</td>
					<td>:</td>
					<td><input type="text" name="halfPrice" value="0" class="input" />��</td>
				</tr>
				<tr>
					<td>�߰�1�ð�</td>
					<td>:</td>
					<td><input type="text" name="timePrice" value="0" class="input" />��</td>
				</tr>
				<? if($haveseason == '1'){ ?>
					<tr>
						<td>������ ����</td>
						<td>:</td>
						<td><input type="text" name="busySeason" value="0" class="input" />%</td>
					</tr>
					<tr>
						<td>�ؼ����� ����</td>
						<td>:</td>
						<td><input type="text" name="semiBusySeason" value="0" class="input" />%</td>
					</tr>
				<? } ?>
					<tr>
						<td>�ָ� ����</td>
						<td>:</td>
						<td><input type="text" name="holidaySeason" value="0" class="input" />%</td>
					</tr>
			<? }else{ ?>
				<? if($haveseason == '1'){ ?>
					<tr>
						<td>������ �߰���</td>
						<td>:</td>
						<td><input type="text" name="busySeason" value="0" class="input" />��</td>
					</tr>
					<tr>
						<td>�ؼ����� �߰���</td>
						<td>:</td>
						<td><input type="text" name="semiBusySeason" value="0" class="input" />��</td>
					</tr>
				<? } ?>
					<tr>
						<td>�ָ� �߰���</td>
						<td>:</td>
						<td><input type="text" name="holidaySeason" value="0" class="input" />��</td>
					</tr>
			<?	}?>
			
			</table>
		</td>
		<td align="center"><input type="text" name="optionCount" value="0" style="width:60px;text-align:center;" class="input" />��</td>
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
						<td>�Ϲݰ�(24�ð�)</td>
						<td>:</td>
						<td><input type="text" name="nomalPrice" value="<?=$listROW['nomalPrice']?>" class="input" />��</td>
					</tr>
					<? if($pricetype == 'time'){ ?>
					<tr>
						<td>12�ð�</td>
						<td>:</td>
						<td><input type="text" name="halfPrice" value="<?=$listROW['halfPrice']?>" class="input" />��</td>
					</tr>
					<tr>
						<td>�߰�1�ð�</td>
						<td>:</td>
						<td><input type="text" name="timePrice" value="<?=$listROW['timePrice']?>" class="input" />��</td>
					</tr>
					<? if($haveseason == '1'){ ?>
						<tr>
							<td>������ ����</td>
							<td>:</td>
							<td><input type="text" name="busySeason" value="<?=$listROW['busySeason']?>" class="input" />%</td>
						</tr>
						<tr>
							<td>�ؼ����� ����</td>
							<td>:</td>
							<td><input type="text" name="semiBusySeason" value="<?=$listROW['semiBusySeason']?>" class="input" />%</td>
						</tr>
					<? } ?>
						<tr>
							<td>�ָ� ����</td>
							<td>:</td>
							<td><input type="text" name="holidaySeason" value="<?=$listROW['holidaySeason']?>" class="input" />%</td>
						</tr>
				<? }else{ ?>
					<? if($haveseason == '1'){ ?>
					<tr>
						<td>������ �߰���</td>
						<td>:</td>
						<td><input type="text" name="busySeason" value="<?=$listROW['busySeason']?>" class="input" />��</td>
					</tr>
					<tr>
						<td>�ؼ����� �߰���</td>
						<td>:</td>
						<td><input type="text" name="semiBusySeason" value="<?=$listROW['semiBusySeason']?>" class="input" />��</td>
					</tr>
					<? } ?>
					<tr>
						<td>�ָ� �߰���</td>
						<td>:</td>
						<td><input type="text" name="holidaySeason" value="<?=$listROW['holidaySeason']?>" class="input" />��</td>
					</tr>
				<?	}?>
				</table>
			</td>
			<td align="center"><input type="text" name="optionCount" value="<?=$listROW['productCount']?>" style="width:60px;text-align:center;" class="input" />��</td>
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