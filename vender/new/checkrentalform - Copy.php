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
	// �뿩 ����� ���� ����Ʈ	
	$value = array("display"=>1,'vender'=>($_venderdata->vender)); // ���� �� ǥ��
	$localList = rentLocalList( $value );
	if(!isset($localList[$rentProduct['location']])) $rentProduct['location']= 0;
	?>
	<select name="location" style="float:left">
			<option value="0">--������ ����--</option>
		<?
			// �뿩 ����� ���� ����Ʈ	
			$value = array("display"=>1,'vender'=>($_venderdata->vender)); // ���� �� ǥ��
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
	<TD style="background:#f5f5f5 url(images/line01.gif) no-repeat right; padding:9px"><font color="FF4800">*</font>��ǰ���й� ���� ����</TD>
	<TD colspan="3" style="padding-left:5px;">
		
		<div style="margin-bottom:5px;">
			<input type="radio" name="istrust" value="1" <?=($rentProduct['istrust']!='-1' && $rentProduct['istrust']!='0')?'checked':''?>  onclick="javascript:toggleTrust()"  />�������� (������  <?=number_format($commi['self'])?>%)
			<? if($rentProduct['istrust']=='0'){ ?>
			<input type="radio" name="istrust" value="0" style="margin-left:8px;" <?=($rentProduct['istrust']=='0')?'checked':''?>  onclick="javascript:toggleTrust()" />��Ź���� (������  <?=number_format($commi['main'])?>%)
			<? }else{ ?>
			<input type="radio" name="istrust" value="-1" style="margin-left:8px;" <?=($rentProduct['istrust']=='-1')?'checked':''?> onclick="javascript:toggleTrust()" />��Ź���� ��û (������  <?=number_format($commi['main'])?>%)
			<? } ?>
		</div>
		<div id="goodsTypeLocalDiv" class="rentalItemArea">	
			<table border="0" cellpadding="0" cellspacing="0"  class="tableBaseSe" style="border-top:1px solid #ededed;">
				<tr>
					<th style="width:150px;"><img width="8" height="11" src="images/icon_point2.gif" border="0"/>��ǰ Ÿ��</th>
					<td style="text-align:left;padding-left:10px;">
						<input type=radio id="itemType1" name="itemType" value="product" checked="checked"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=itemType1>��ǰ</label> &nbsp;
						<input type=radio id="itemType2" name="itemType" value="location" ><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=itemType2>���</label> &nbsp;
					</td>
					<? if(!_empty($categoryRentInfo['pricetype'])){ ?>
					<th style="width:120px;"><img width="8" height="11" src="images/icon_point2.gif" border="0"/>���ݱ���</th>
					<td style="text-align:left;padding:0px 10px;">
						<? switch($categoryRentInfo['pricetype']){
								case 'time': echo '�ð����� ���'; break;
								case 'day': echo '$�Ϸ�(24�ð�)���� ���'; break;
								case 'checkout': echo '������(����2��~����11��) ���'; break;
								default: echo '����'; break;
						} ?>
					</td>
					<? } ?>									
					<th style="width:120px;"><img width="8" height="11" src="images/icon_point2.gif" border="0"/>��������</th>
					<td style="text-align:left;padding:0px 10px;">
						<? echo ($categoryRentInfo['useseason'] == '1')?'������ ���':'������';?>
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
	<TD style="background:#f5f5f5 url(images/line01.gif) no-repeat right; padding:9px"><font color="FF4800">*</font>������</TD>
	<td colspan="3" style="padding-left:5px;">
	<?
	// �뿩 ����� ���� ����Ʈ	
	$value = array("display"=>1,'vender'=>($_venderdata->vender)); // ���� �� ǥ��
	$localList = rentLocalList( $value );
	if(!isset($localList[$rentProduct['location']])) $rentProduct['location']= 0;
	?>
	<div id="localListDiv">	
		<select name="location" style="float:left">
			<option value="0">--������ ����--</option>
		<?
			// �뿩 ����� ���� ����Ʈ	
			$value = array("display"=>1,'vender'=>($_venderdata->vender)); // ���� �� ǥ��
			$localList = rentLocalList( $value );
			if(!isset($localList[$rentProduct['location']])) $rentProduct['location']= 0;
			
			foreach ( $localList as $k=>$v ) { 
				$sel = ($rentProduct['location'] == $v['location'])?'checked="checked"':'';
			?>
			<option value="<?=$v['location']?>" <?=$sel?>><?=$v['title']?></option>
		<? } ?>
		</select>			
	</div>
	<input type="button" value="����� ����" onclick="javascript:openLocalWin();" style="margin-left:5px;" /></h6>
	<br />
	<span style="color:#ec2f36; font-weight:bold">�뿩��ǰ �ɼ��� ��ǰ ���� ���� ������ ������ ���ؼ� ó�� �����մϴ�.</span>																																		
		
	</TD>
</TR>
<TR class="rentalItemArea">
	<td height=1 colspan=4 bgcolor=E7E7E7></td>
</TR>
<TR class="rentalItemArea">
	<TD bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9">������̵���</TD>
	<TD class="td_con1" colspan="3">
		<input type="radio" name="rentdispId" value="self" <?=($rentProduct['rentdispId'] != 'main')?'checked':''?> />����
		<input type="radio" name="rentdispId" value="main" <?=($rentProduct['rentdispId'] == 'main')?'checked':''?> />�����
	</TD>
</TR>
<TR class="rentalItemArea">
	<td height=1 colspan=4 bgcolor=E7E7E7></td>
</TR>
<TR class="rentalItemArea">
	<TD bgcolor="F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y;background-position:right;padding:9">�̴�Ȩ����</TD>
	<TD class="td_con1" colspan="3">
	<input type="radio" name="rentdispminihome" value="self" <?=($rentProduct['rentdispminihome'] != 'main')?'checked':''?> />��
	<input type="radio" name="rentdispminihome" value="main" <?=($rentProduct['rentdispminihome'] == 'main')?'checked':''?> />�ƴϿ�
	</TD>
</TR>
<TR class="rentalItemArea">
	<td height=1 colspan=4 bgcolor=E7E7E7></td>
</TR>
<?
		$ret['cont'] = ob_get_clean();
		$ret['checkbox'] = '<input type=radio id="goodsType1" name="goodsType" value="1" checked="checked" onclick="toggleGoodsType(\'1\');"><label style=\'cursor:hand;\' for=goodsType1>�ǸŻ�ǰ</label> &nbsp;	<input type=radio id="goodsType2" name="goodsType" value="2"  onclick="toggleGoodsType(\'2\'); "><label style="cursor:hand;" for=goodsType2>�뿩��ǰ</label><a href="javascript:document.location.reload()">';	
	}else{
		$ret['cont'] = '<input type=radio id="goodsType1" name="goodsType" value="1" checked="checked" onclick="toggleGoodsType(\'1\');"><label style=\'cursor:hand;\' for=goodsType1>�ǸŻ�ǰ</label> ';
		$ret['checkbox'] = '';
	}
}
// php  5.2.0 �̻��� �߰�
$phpVer = str_replace(".","",phpversion());
if( $phpVer >= 520 ) array_walk($ret,'_encode');

echo json_encode($ret);
exit;

?>