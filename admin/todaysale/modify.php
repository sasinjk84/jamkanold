<?
$gongtype = 'N';
include_once($Dir."lib/ext/product_func.php");

$sql = "SELECT recom_memreserve_type, sns_ok, sns_reserve_type ";
$sql.= "FROM tblshopinfo ";
$result=mysql_query($sql,get_db_conn());
if ($row=mysql_fetch_object($result)) {
	$recom_memreserve_type=$row->recom_memreserve_type;
	$sns_ok=$row->sns_ok;
	$sns_reserve_type=$row->sns_reserve_type;
	$arRecomType = explode("",$recom_memreserve_type);
	$arSnsType = explode("",$sns_reserve_type);
}

$userspec_cnt=5;
$maxfilesize="512000";
$imagepath=$Dir.DataDir."shopimages/product/";


if(!_empty($_REQUEST['productcode']) && preg_match('/^899[0-9]{15}$/',$_REQUEST['productcode'])){
	$sql = "select * from tblproduct p inner join todaysale using(pridx)  where p.productcode ='".$_REQUEST['productcode']."' limit 1";	
	
	if(false === $res = mysql_query($sql,get_db_conn())){
		_alert('��ǰ ���� ��ȣ�� �ùٸ��� �ʽ��ϴ�.','-1');
	}
	if(mysql_num_rows($res) <1) _alert('��ǰ ������ ã���� �����ϴ�.','-1');
	$productinfo = mysql_fetch_assoc($res);
	
	if(!_empty($productinfo['option_quantity'])) $searchtype=1;
	else if(ereg("^(\[OPTG)([0-9]{4})(\])$",$productinfo['option1'])) $searchtype=3;

	unset($specname,$specvalue,$specarray);
	if(!_empty($productinfo['userspec'])) {
		$userspec = "Y";
		$specarray= explode("=",$productinfo['userspec']);
		for($i=0; $i<$userspec_cnt; $i++) {
			$specarray_exp = explode("", $specarray[$i]);
			$specname[] = $specarray_exp[0];
			$specvalue[] = $specarray_exp[1];
		}
	} else {
		$userspec = "N";
	}

	// Ư���ɼǰ��� üũ�Ѵ�.
	$dicker = $dicker_text="";
	if (strlen($productinfo['etctype'])>0) {
		$etctemp = explode("",$productinfo['etctype']);
		$miniq = 1;          // �ּ��ֹ����� �⺻�� �ִ´�.
		$maxq = "";
		for ($i=0;$i<count($etctemp);$i++) {
			if ($etctemp[$i]=="BANKONLY")                    $bankonly="Y";        // ��������
			else if (substr($etctemp[$i],0,11)=="DELIINFONO=")     $deliinfono=substr($etctemp[$i],11);  // ���/��ȯ/ȯ������ ������� ����
			else if ($etctemp[$i]=="SETQUOTA")               $setquota="Y";        // �����ڻ�ǰ
			else if (substr($etctemp[$i],0,6)=="MINIQ=")     $miniq=substr($etctemp[$i],6);  // �ּ��ֹ�����
			else if (substr($etctemp[$i],0,5)=="MAXQ=")      $maxq=substr($etctemp[$i],5);  // �ִ��ֹ�����
			else if (substr($etctemp[$i],0,5)=="ICON=")      $iconvalue=substr($etctemp[$i],5);  // �ִ��ֹ�����
			else if (substr($etctemp[$i],0,9)=="FREEDELI=")  $freedeli=substr($etctemp[$i],9);  // �����ۻ�ǰ
			else if (substr($etctemp[$i],0,7)=="DICKER=") {  $dicker=Y; $dicker_text=str_replace("DICKER=","",$etctemp[$i]); }  // ���ݴ�ü����
		}
	}
	if(!_empty($iconvalue)) {
		for($i=0;$i<strlen($iconvalue);$i=$i+2) {
			$iconvalue2[substr($iconvalue,$i,2)]="Y";
		}
	}
	if($productinfo['brand']>0) {
		$sql = "SELECT brandname FROM tblproductbrand WHERE bridx = '".$productinfo['brand']."' ";
		$result = mysql_query($sql,get_db_conn());
		$_data2 = mysql_fetch_object($result);
		$productinfo['brandname'] = $_data2->brandname;
		mysql_free_result($result);
	}

	if($productinfo['group_check']=="Y") {
		$sql = "SELECT group_code FROM tblproductgroupcode WHERE productcode = '".$prcode."' ";
		$result = mysql_query($sql,get_db_conn());
		while($row = mysql_fetch_object($result)) {
			$group_code[$row->group_code] = "Y";
		}
		mysql_free_result($result);
	}
	if(preg_match('/(2[0-9]{3}-[0-9]{1,2}-[0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2})/',$productinfo['start'],$mat)){
		$productinfo['start_date'] = $mat[1];
		$productinfo['start_hour'] = $mat[2];
		$productinfo['start_minute'] = $mat[3];		
	}
	
	if(preg_match('/(2[0-9]{3}-[0-9]{1,2}-[0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2})/',$productinfo['end'],$mat)){
		$productinfo['end_date'] = $mat[1];
		$productinfo['end_hour'] = $mat[2];
		$productinfo['end_minute'] = $mat[3];		
	}
	
	
	if(preg_match("/^\[OPTG([0-9]{4})\]$/",$productinfo['option1'],$mat)){
		$optcode = $mat[1];		
		$productinfo['option1'] = $productinfo['option_price'] = "";
	}
	
}else{	
	$productinfo = array();
	$tmpobj = cateAuth('899');
	
	$productinfo['etcapply_coupon']		=	($tmpobj->coupon=="Y")?"N":'Y';
	$productinfo['etcapply_reserve']	=	($tmpobj->reserve=="Y")?"N":'Y';
	$productinfo['etcapply_gift']			=	($tmpobj->gift=="Y")?"N":'Y';
	$productinfo['etcapply_return']			=	($tmpobj->refund=="Y")?"N":'Y';
	
	$productinfo['start_date'] = date('Y-m-d');
	$productinfo['end_date'] = date('Y-m-d',strtotime('+1 day'));
}


include "header.php"; ?>
<style type="text/css">
@import url("/css/common.css");
</style>
<script language="javascript" type="text/javascript" src="lib.js.php"></script>
<SCRIPT LANGUAGE="JavaScript">
<!--
function PrdtDelete() {
	if (confirm("�ش� ��ǰ�� �����Ͻðڽ��ϱ�?")) {
		document.cForm.mode.value="delete";
		document.cForm.submit();
	}
}

function NewPrdtInsert(){
	document.cForm.prcode.value="";
	document.cForm.submit();
}

function IconMy(){
	window.open("","icon","height=343,width=440,toolbar=no,menubar=no,scrollbars=no,status=no");
	document.icon.submit();
}

function IconList(){
	alert("���� �غ��� �Դϴ�.");
	//window.open("","iconlist","height=343,width=440,toolbar=no,menubar=no,scrollbars=no,status=no");
	//document.iconlist.submit();
}

function DeletePrdtImg(temp){
	if(confirm('�ش� �̹����� �����Ͻðڽ��ϱ�?')){
		document.cForm.mode.value="delprdtimg";
		document.cForm.delprdtimg.value=temp-1;
		document.cForm.submit();
	}
}

function CheckChoiceIcon(no){
	num = document.form1.iconnum.value;
	iconnum=0;
	for(i=0;i<num;i++){
		if(document.form1.icon[i].checked==true) iconnum++;
	}
	if(iconnum>3){
		alert('�� ��ǰ�� 3������ �������� ����� �� �ֽ��ϴ�.');
		document.form1.icon[no].checked=false;
	}
}

function PrdtAutoImgMsg(){
	if(document.form1.imgcheck.checked==true) alert('��ǰ �߰�/���� �̹����� �� �̹������� �ڵ� �����˴ϴ�.\n\n������ �߰�/���� �̹����� �����˴ϴ�.');
}

var shop="layer0";
var ArrLayer = new Array ("layer0","layer1","layer2","layer3");
function ViewLayer(gbn){
	if(document.all){
		for(i=0;i<4;i++) {
			if (ArrLayer[i] == gbn)
				document.all[ArrLayer[i]].style.display="";
			else
				document.all[ArrLayer[i]].style.display="none";
		}
	} else if(document.getElementById){
		for(i=0;i<4;i++) {
			if (ArrLayer[i] == gbn)
				document.getElementByld[ArrLayer[i]].style.display="";
			else
				document.getElementByld[ArrLayer[i]].style.display="none";
		}
	} else if(document.layers){
		for(i=0;i<4;i++) {
			if (ArrLayer[i] == gbn)
				document.layers[ArrLayer[i]].display="";
			else
				document.layers[ArrLayer[i]].display="none";
		}
	}
	shop=gbn;
}

function ViewSnsLayer(display) {
	if(document.getElementById("sns_optionWrap"))
		document.getElementById("sns_optionWrap").style.display = display;
}

function SelectColor(){
	setcolor = document.form1.setcolor.value;
	var newcolor = showModalDialog("select_color.php?color="+setcolor, "oldcolor", "resizable: no; help: no; status: no; scroll: no;");
	if(newcolor){
		document.form1.setcolor.value=newcolor;
		document.all.ColorPreview.style.backgroundColor = '#' + newcolor;
	}
}

function optionhelp(){
	alert("���� �غ��� �Դϴ�.");
}

function DateFixAll(obj) {
	if (obj.checked==true) {
		document.form1.insertdate.value="Y";
		document.form1.insertdate1.checked=true;
		document.form1.insertdate2.checked=true;
		document.form1.insertdate3.checked=true;
	} else {
		document.form1.insertdate.value="";
		document.form1.insertdate1.checked=false;
		document.form1.insertdate2.checked=false;
		document.form1.insertdate3.checked=false;
	}
}

function change_filetype(obj) {
	if(obj.checked==true) {	//�̹��� ��ũ ���
		for(var jj=1;jj<=3;jj++) {
			idx=jj;
			if(idx==1) idx="";
			document.form1["userfile"+idx].style.display='none';
			document.form1["userfile"+idx+"_url"].style.display='';
			document.form1["userfile"+idx].disabled=true;
			document.form1["userfile"+idx+"_url"].disabled=false;
		}
	} else {				//÷������ ���
		for(var jj=1;jj<=3;jj++) {
			idx=jj;
			if(idx==1) idx="";
			document.form1["userfile"+idx].style.display='';
			document.form1["userfile"+idx+"_url"].style.display='none';
			document.form1["userfile"+idx].disabled=false;
			document.form1["userfile"+idx+"_url"].disabled=true;
		}
	}
}

function userspec_change(val) {
	if(document.getElementById("userspecidx")) {
		if(val == "Y") {
			document.getElementById("userspecidx").style.display ="";
		} else {
			document.getElementById("userspecidx").style.display ="none";
		}
	}
}

function GroupCode_Change(val) {
	if(document.getElementById("group_checkidx")) {
		if(val == "Y") {
			document.getElementById("group_checkidx").style.display ="";
		} else {
			document.getElementById("group_checkidx").style.display ="none";
		}
	}
}

function GroupCodeAll(checkval,checkcount) {
	for(var i=0; i<checkcount; i++) {
		if(document.getElementById("group_code_idx"+i)) {
			document.getElementById("group_code_idx"+i).checked = checkval;
		}
	}
}

/*################################### �±װ��� ���� #####################################*/
var IE = false ;
if (window.navigator.appName.indexOf("Explorer") !=-1) {
	IE = true;
}

function getXmlHttpRequest() {
	var xmlhttp = false
	if(window.XMLHttpRequest){//Mozila
		xmlhttp = new XMLHttpRequest()
	}else {//IE
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP")
	}
	return xmlhttp;
}
function loadData(path, successFunc, msg){
	var xmlhttp = getXmlHttpRequest();
	xmlhttp.open("GET",path,true);
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4) {
			if (xmlhttp.status == 200) {
				var data = xmlhttp.responseText;
				successFunc(data);
			}else{
				alert(msg);
			}
		}
	}
	xmlhttp.send(null);
	return false;
}


function loadProductTagList (prcode) {
	loadData("product_taglist.xml.php?prcode="+prcode, setProductTagList);
}
function setProductTagList(data) {
  	try {
  		var tagElem = document.getElementById("ProductTagList");
  		if(data=='') {
  			data = "�Ͻ������� �±� ������ �ҷ��� �� �����ϴ�.\n\n�±� ���� ����� ��� �Ŀ� �̿��� �ֽʽÿ�. \n\n��ǰ������  ���������� �����Ͻ�  �� �ֽ��ϴ�.";
  		}
  		tagElem.innerHTML = data;
		tagElem.style.height = "68";
		tagElem.style.overflowY = "auto";
  	}catch(e) {}
}

function BrandSelect() {
	window.open("product_brandselect.php","brandselect","height=400,width=420,scrollbars=no,resizable=no");
}

function FiledSelect(pagetype) {
	window.open("product_select.php?type="+pagetype,pagetype,"height=400,width=420,scrollbars=no,resizable=no");
}

/*################################### �±װ��� ��   #####################################*/


function deli_helpshow() {
	if(document.getElementById('deli_helpshow_idx')) {
		if(document.getElementById('deli_helpshow_idx').style.display=="none") {
			document.getElementById('deli_helpshow_idx').style.display="";
		} else {
			document.getElementById('deli_helpshow_idx').style.display="none";
		}
	}
}

function chkFieldMaxLenFunc(thisForm,reserveType) {
	if (reserveType=="Y") { max=5; addtext="/Ư������(�Ҽ���)";} else { max=6; }
	if (thisForm.reserve.value.bytes() > max) {
		alert("�Է��� �� �ִ� ��� ������ �ʰ��Ǿ����ϴ�.\n\n" + "����"+addtext+" " + max + "�� �̳��� �Է��� �����մϴ�.");
		thisForm.reserve.value = thisForm.reserve.value.cut(max);
		thisForm.reserve.focus();
	}
}

function getSplitCount(objValue,splitStr)
{
	var split_array = new Array();
	split_array = objValue.split(splitStr);
	return split_array.length;
}

function getPointCount(objValue,splitStr,falsecount)
{
	var split_array = new Array();
	split_array = objValue.split(splitStr);

	if(split_array.length!=2) {
		if(split_array.length==1) {
			return false;
		} else {
			return true;
		}
	} else {
		if(split_array[1].length>falsecount) {
			return true;
		} else {
			return false;
		}
	}
}

function isDigitSpecial(objValue,specialStr)
{
	if(specialStr.length>0) {
		var specialStr_code = parseInt(specialStr.charCodeAt(i));

		for(var i=0; i<objValue.length; i++) {
			var code = parseInt(objValue.charCodeAt(i));
			var ch = objValue.substr(i,1).toUpperCase();

			if((ch<"0" || ch>"9") && code!=specialStr_code) {
				return true;
				break;
			}
		}
	} else {
		for(var i=0; i<objValue.length; i++) {
			var ch = objValue.substr(i,1).toUpperCase();
			if(ch<"0" || ch>"9") {
				return true;
				break;
			}
		}
	}
}
//-->
</SCRIPT>
<script type="text/javascript" src="calendar.js.php"></script>
<!-- �����Ϳ� ���� ȣ�� -->
<script type="text/javascript" src="/gmeditor/js/jquery.js"></script>
<script type="text/javascript" src="/gmeditor/js/jquery.event.drag-2.0.min.js"></script>
<script type="text/javascript" src="/gmeditor/js/jquery.resizable.js"></script>
<script type="text/javascript" src="/gmeditor/js/ajax_upload.3.6.js"></script>
<script type="text/javascript" src="/gmeditor/js/ej.h2xhtml.js"></script>
<script type="text/javascript" src="/gmeditor/editor.js"></script>
<script type="text/javascript" src="/js/jquery.autocomplete.js"></script>
<link rel="stylesheet" type="text/css" href="/js/jquery.autocomplete.css" />
<script language="javascript" type="text/javascript">
$(document).ready(function() {
	ejEditor();
});
</script>
<style type="text/css">
@import url("/gmeditor/common.css");
.productRegFormTbl{border-top:2px solid #333}
.productRegFormTbl th{ text-align:left; padding-left:25px; background:#f8f8f8 url(/admin/images/icon_point5.gif) 10px 50% no-repeat; border-bottom:1px solid #efefef; border-left:1px solid #efefef}
.productRegFormTbl td{padding:5px; border-bottom:1px solid #efefef; border-left:1px solid #efefef}
.productRegFormTbl caption{ text-align:left}
</style>
<!-- # �����Ϳ� ���� ȣ�� -->

<div style="background:url(images/title_bg.gif) repeat-x left bottom; padding-bottom:25px;"><IMG SRC="images/todaysale_title02.gif" ALT="�����̼��� ��ǰ���" /></div>
<div style="padding:0px 0px 20px 20px;" class="notice_blue">�����̼��Ͽ� ��ǰ�� ����� �� �ֽ��ϴ�.</div>

<input type="button" onclick="javascript:document.location.reload();" value="���ΰ�ħ" />
<form name="form1" action="/admin/todaysale/product.process.php" method="post" enctype="multipart/form-data" style="padding:0px; margin:0px">
	<input type="hidden" name="mode">
	<input type="hidden" name="code" value="899">
	<input type="hidden" name="prcode" value="<?=$productinfo['productcode']?>">	
	<input type="hidden" name="option1">
	<input type="hidden" name="option2">
	<input type="hidden" name="option_price">
	<input type="hidden" name="insertdate">
	<table cellpadding="0" cellspacing="0" width="100%" class="productRegFormTbl">
		<caption style="padding-bottom:10px;">
			<IMG SRC="images/product_register_stitle1.gif" ALT="�⺻ ���� ���/����"><br />
			<div style="padding-top:5pt; padding-left:24px;"><img src="images/icon_point2.gif" alt="" /><span class="font_orange" style="font-weight:bold">�ʼ�ǥ�� �׸�</span></div>
		</caption>
		<tr>
			<th style="width:140px;"><span class="font_orange">��ǰ��</span></th>
			<td colspan="3"><input name="productname" value="<?=ereg_replace("\"","&quot",$productinfo['productname'])?>" size="80" maxlength="250" onKeyDown="chkFieldMaxLen(250)" class="input" style="width:100%"></td>
		</tr>
		<tr>
			<th>���Ұ�üũ</th>
			<td colspan="3">
				<input type="checkbox" id="idx_etcapply_coupon" name="etcapply_coupon" value="Y" <?=($productinfo['etcapply_coupon']=="Y")?"checked":"";?>>
				<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_etcapply_coupon">�������� ����Ұ�</label>
				&nbsp;&nbsp;&nbsp;
				<input type="checkbox" id="idx_etcapply_reserve" name="etcapply_reserve" value="Y" <?=($productinfo['etcapply_reserve']=="Y")?"checked":"";?>>
				<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_etcapply_reserve">����������Ұ�</label>
				&nbsp;&nbsp;&nbsp;
				<input type="checkbox" id="idx_etcapply_gift" name="etcapply_gift" value="Y" <?=($productinfo['etcapply_gift']=="Y")?"checked":"";?>>
				<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_etcapply_gift">���Ż���ǰ����Ұ�</label>				
				&nbsp;&nbsp;&nbsp;
				<input type=checkbox id="idx_etcapply_return" name=etcapply_return value="Y" <?=($productinfo['etcapply_return']=="Y")?"checked":"";?>>
				<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_etcapply_return>��ȯ��ȯ�� �Ұ�</label>
			</td>
		</tr>
		<tr>
			<th><span class="font_orange">�ǸŰ�</span></th>
			<td><input name="sellprice" value="<?=$productinfo['sellprice']?>" size="16" maxlength="10" class="input" style="width:150px;" />��
			<th style="width:140px;"><span class="font_orange">����</span></th>
			<td>
				<input name="consumerprice" value="<?=(intval($productinfo['consumerprice'])>0?intval($productinfo['consumerprice']):'')?>" size="16" maxlength="10" class="input" style="width:150px">��
			</td>
		</tr>			
		<tr>
			<th>������(��)</th>
			<td>
				<input name="reserve" value="<?=$productinfo['reserve']?>" size="16" maxlength="6" class="input" style="width:60%" onKeyUP="chkFieldMaxLenFunc(this.form,this.form.reservetype.value);">
				<select name="reservetype" class="select" onchange="chkFieldMaxLenFunc(this.form,this.value);">
					<option value="N"<?=($productinfo['reservetype']!="Y"?" selected":"")?>>������(��)</option>
					<option value="Y"<?=($productinfo['reservetype']!="Y"?"":" selected")?>>������(%)</option>
				</select>
				<br>
				<span class="font_orange" style="font-size:8pt;letter-spacing:-0.5pt">* �������� �Ҽ��� ��°�ڸ����� �Է� �����մϴ�.<br>
				* �������� ���� ���� �ݾ� �Ҽ��� �ڸ��� �ݿø�.</span> </td>
			<th>���Կ���</th>
			<td>
				<input name="buyprice" value="<?=$productinfo['buyprice']?>" size="16" maxlength="10" class="input" style="width:150px">
			</td>
		</tr>			
		<tr>
			<th>������</th>
			<td>
				<input name="production" value="<?=$productinfo['production']?>" size="23" maxlength="20" onKeyDown="chkFieldMaxLen(50)" class="input">
				<a href="javascript:FiledSelect('PR');"><img src="images/btn_select.gif" border="0" hspace="5" align="absmiddle"></a></td>
			<th>������</th>
			<td>
				<input name="madein" value="<?=$productinfo['madein']?>" size="23" maxlength="20" onKeyDown="chkFieldMaxLen(30)" class="input"><a href="javascript:FiledSelect('MA');"><img src="images/btn_select.gif" border="0" hspace="5" align="absmiddle"></a></td>
		</tr>			
		<tr>
			<th>�귣��</th>
			<td>
				<input type="text" name="brandname" value="<?=$productinfo['brandname']?>" size="23" maxlength="50" onKeyDown="chkFieldMaxLen(50)" class="input">
				<a href="javascript:BrandSelect();"><img src="images/btn_select.gif" border="0" hspace="5" align="absmiddle"></a><br>
				<span class="font_orange">* �귣�带 ���� �Է½ÿ��� ��ϵ˴ϴ�.</span></td>
			<th>�𵨸�</th>
			<td>
				<input name="model" value="<?=$productinfo['model']?>" size="23" maxlength="40" onKeyDown="chkFieldMaxLen(50)" class="input">
				<a href="javascript:FiledSelect('MO');"><img src="images/btn_select.gif" border="0" hspace="5" align="absmiddle"></a></td>
		</tr>
		<tr>
			<th><span class="font_orange">������</span></th>
			<td>
			<INPUT class="input_selected" style="text-align:center;width:120px;" onfocus="this.blur();" onclick="Calendar(this)" size="10" value="<?=$productinfo['start_date']?>" name="start_date"> 
			<SELECT name="start_hour" class="select">
<?			for($i=0;$i<=23;$i++){ 
				$sel = ($productinfo['start_hour'] == $i)?'selected':''; ?>
				<option value="<?=$i?>" <?=$sel?>><?=$i?>��</option>
<?			} ?>
			</SELECT>
			 
			<SELECT name="start_minute" class="select">
<?			for($i=0;$i<=59;$i++) { 
				$sel = (intval($productinfo['start_minute']) == $i)?'selected':''; ?>
				<option value="<?=$i?>" <?=$sel?>><?=sprintf('%02d',$i)?>��</option>
<?			} ?>
			</SELECT>
			</td>
			<th><span class="font_orange">������</span></th>
			<td>
			<INPUT class="input_selected" style="text-align:center;width:120px;" onfocus="this.blur();" onclick="Calendar(this)" size="10" value="<?=$productinfo['end_date']?>" name="end_date"> 
			<SELECT name="end_hour" class="select">
<?			for($i=0;$i<=23;$i++) { 
				$sel = ($productinfo['end_hour'] == $i)?'selected':''; ?>
				<option value="<?=$i?>" <?=$sel?>><?=$i?>��</option>
<?			} ?>
			</SELECT>
			 
			<SELECT name="end_minute" class="select">
<?			for($i=0;$i<=59;$i++) { 
				$sel = (intval($productinfo['end_minute']) == $i)?'selected':''; ?>
				<option value="<?=$i?>" <?=$sel?>><?=sprintf('%02d',$i)?>��</option>
<?			} ?>
			</SELECT>
			</TD>
		</tr>
		
		<?
			$usevender = getVenderUsed();
			if($usevender[OK] == "OK"){ ?>

			<!-- vender ���� �߰�::���θ��� ��쿡�� ������� �����ؾ��� -->
			<TR>
				<Th><img src="images/icon_point5.gif" width="8" height="11" border="0">������üID</Th>
				<TD class="td_con1" colspan="3">
					<?
						/*
					<input type="text" name="vender_name" id="vender_name" value="<? echo $vender_id ? $vender_id : "";?>" style="width:260px" class="input"> ��ü id�� �Է��Ͻø� �ش� ����� �ڵ� �ϼ����� ��Ÿ���ϴ�.<br />
					<span class="font_orange">(* ���鹮��(�����̽�) �� �Է��Ͻø� ��ü id ����� Ȯ�� �Ͻ� �� �ֽ��ϴ�. )</span>
					<!--input type="text" name="vender" id="vender" value="<? echo $vender ? $vender : 0;?>"-->
						*/
					?>
					<select name="vender" style="width:260px" class="input">
						<option value=''>����</option>";
						<?
							$venderResult = mysql_query( "SELECT `vender`,`com_name`,`id` FROM `tblvenderinfo` Order By `id` ASC; ",get_db_conn());
							while ( $venderRow = mysql_fetch_assoc ( $venderResult ) ) {
								$sel = ($venderRow['vender'] == $productinfo['vender'])?"selected":"";
								echo "<option value='".$venderRow['vender']."' ".$sel.">".$venderRow['id'].'('.$venderRow['com_name'].")</option>";
							}
						?>
					</select>
				</TD>
			</TR>
			<TR>
				<TD colspan="4" background="images/table_con_line.gif"></TD>
			</TR>
			<? } ?>
			
			
		<tr>
			<th>�����ڵ�</th>
			<td colspan="3">
				<input name="selfcode" value="<?=$productinfo['selfcode']?>" size="35" maxlength="20" onKeyDown="chkFieldMaxLen(20)" class="input" style="width:100%"><br />
				<span class="font_orange">* ���θ����� �ڵ����� �߱޵Ǵ� ��ǰ�ڵ�ʹ� ������ ��� �ʿ��� ��ü��ǰ�ڵ带 �Է��� �ּ���.<br>
				* �����ڵ� ���� ������ <a href="javascript:parent.parent.topframe.GoMenu(1,'shop_productshow.php');"><span class="font_blue">�������� > ���θ� ȯ�� ���� > ��ǰ ���� ��Ÿ ����</span></a> ������ �� �ֽ��ϴ�. </span>
			</td>
		</tr>
		<tr>
			<th><span class="font_orange">���Ѽ���</span></th>
			<td>
			<script language="javascript" type="text/javascript">
			function checkUnlimit(){
				if($('#quantity_unlimit').attr('checked')){					
					$('input[name=quantity]').attr('disabled','disabled');
				}else{
					$('input[name=quantity]').removeAttr('disabled');
				}
			}
			</script>			
			<input type="checkbox" name="quantity_unlimit" id="quantity_unlimit" value="1" <? if(!_isInt($productinfo['quantity'],true)){ ?> checked="checked" <? } ?>  onclick="checkUnlimit()" /><label for="quantity_unlimit">������</label>&nbsp;Or&nbsp;<input type="text" name="quantity" style="width:45px;" value="<?=$productinfo['quantity']?>" <? if(!_isInt($productinfo['quantity'],true)){?> disabled="disabled" <? } ?>  />��
			
			</td>
			<th>�Ǹż������⼳��</td>
			<td><input type="text" name="addquantity" value="<?=$productinfo['addquantity']?>" style="width:45px;" />(���Ǹ� ������ + �ؼ� ǥ��)</td>
		</tr>			
		<tr>
			<th>�ּұ��ż���</th>
			<td>
				<input type="text" name="miniq" value="<?=($miniq>0?$miniq:"1")?>" size="5" maxlength="5" class="input">
				�� �̻�</td>
			<th>�ִ뱸�ż���</th>
			<td>
				<input type="radio" id="idx_checkmaxq1" name="checkmaxq" value="A" <? if (strlen($maxq)==0 || $maxq=="?") echo "checked ";?> onclick="document.form1.maxq.disabled=true;document.form1.maxq.style.background='silver';">
				<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_checkmaxq1">������</label>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="radio" id="idx_checkmaxq2" name="checkmaxq" value="B" <? if ($maxq!="?" && $maxq>0) echo "checked"; ?> onclick="document.form1.maxq.disabled=false;document.form1.maxq.style.background='white';">
				<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_checkmaxq2">����</label>
				:
				<input name="maxq" size="5" maxlength="5" value="<?=$maxq?>" class="input">
				�� ���� 
				<script>
			if (document.form1.checkmaxq[0].checked==true) { document.form1.maxq.disabled=true;document.form1.maxq.style.background='silver'; }
			else if (document.form1.checkmaxq[1].checked==true) { document.form1.maxq.disabled=false;document.form1.maxq.style.background='white'; }
			</script> 
			</td>
		</tr>
		<tr>
			<th>������ۺ�</th>
			<td colspan="3">
				<div style="padding:3px 0px">
							<input type="radio" id="idx_deliprtype0" name="deli" value="H" <? if(!_isInt($productinfo['pridx']) || ($productinfo['deli_price']<=0 && $productinfo['deli']=="N")) echo "checked";?> onclick="document.form1.deli_price_value1.disabled=true;document.form1.deli_price_value1.style.background='silver';document.form1.deli_price_value2.disabled=true;document.form1.deli_price_value2.style.background='silver';">
							<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_deliprtype0">�⺻ ��ۺ� <b>����</b></label>
							&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" id="idx_deliprtype2" name="deli" value="F" <? if($productinfo['deli_price']<=0 && $productinfo['deli']=="F") echo "checked";?> onclick="document.form1.deli_price_value1.disabled=true;document.form1.deli_price_value1.style.background='silver';document.form1.deli_price_value2.disabled=true;document.form1.deli_price_value2.style.background='silver';">
							<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_deliprtype2">���� ��ۺ� <b><font color="#0000FF">����</font></b></label>
							&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" id="idx_deliprtype1" name="deli" value="G" <? if($productinfo['deli_price']<=0 && $productinfo['deli']=="G") echo "checked";?> onclick="document.form1.deli_price_value1.disabled=true;document.form1.deli_price_value1.style.background='silver';document.form1.deli_price_value2.disabled=true;document.form1.deli_price_value2.style.background='silver';">
							<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_deliprtype1">���� ��ۺ� <b><font color="#38A422">����</font></b></label>
				</div>
				<div style="padding:3px 0px">
							<input type="radio" id="idx_deliprtype3" name="deli" value="N" <? if($productinfo['deli_price']>0 && $productinfo['deli']=="N") echo "checked";?> onclick="document.form1.deli_price_value1.disabled=false;document.form1.deli_price_value1.style.background='';document.form1.deli_price_value2.disabled=true;document.form1.deli_price_value2.style.background='silver';">
							<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_deliprtype3">���� ��ۺ� <b><font color="#FF0000">����</font></b>
								<input type="text" name="deli_price_value1" value="<? if($productinfo['deli_price']>0 && $productinfo['deli']=="N") echo $productinfo['deli_price'];?>" size="6" maxlength="6" <? if($productinfo['deli_price']<=0 || $productinfo['deli']=="Y") echo "disabled style='background:silver'";?> class="input">
								��</label><br>
							<input type="radio" id="idx_deliprtype4" name="deli" value="Y" <? if($productinfo['deli_price']>0 && $productinfo['deli']=="Y") echo "checked";?> onclick="document.form1.deli_price_value2.disabled=false;document.form1.deli_price_value2.style.background='';document.form1.deli_price_value1.disabled=true;document.form1.deli_price_value1.style.background='silver';">
							<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_deliprtype4">���� ��ۺ� <b><font color="#FF0000">����</font></b>
								<input type="text" name="deli_price_value2" value="<?if($productinfo['deli_price']>0 && $productinfo['deli']=="Y") echo $productinfo['deli_price'];?>" size="6" maxlength="6" <?if($productinfo['deli_price']<=0 || $productinfo['deli']=="N") echo "disabled style='background:silver'";?> class="input">
								�� (���ż� ��� ���� ��ۺ� ���� : <FONT COLOR="#FF0000"><B>��ǰ���ż������� ��ۺ�</B></font>)</label>								
				</div>
			</td>
		</tr>			
		<tr>
			<th>��ǰ������</th>
			<td colspan="3">
				<div style="padding:3px 0px">
							<input type="radio" id="idx_group_check1" name="group_check" value="N" onclick="GroupCode_Change('N');" <?if($productinfo['group_check']!="Y") echo "checked";?>>
							<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_group_check1">��ǰ������ ������</label>
							&nbsp;&nbsp;<span class="font_orange">* ��ǰ������ �������� ��� ��� ��ȸ��, ȸ������ ����˴ϴ�.</span><br>
							<input type="radio" id="idx_group_check2" name="group_check" value="Y" onclick="GroupCode_Change('Y');" <?if($productinfo['group_check']=="Y") echo "checked";?>>
							<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_group_check2">��ǰ������ ����</label>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="font_orange">* ȸ������� <a href="javascript:parent.parent.topframe.GoMenu(3,'member_groupnew.php');"><span class="font_blue">ȸ������ > ȸ����� ���� > ȸ����� ���/����/����</span></a>���� �����ϼ���.</span>
				</div>
				
				<div style="padding:3px; background:#FFF7F0; display:block; border:2px #FF7100 solid; display:<?=($productinfo['group_check']!="Y")?'none':''?>" id="group_checkidx">
				<?
					$sqlgrp = "SELECT group_code,group_name FROM tblmembergroup ";
					$resultgrp = mysql_query($sqlgrp,get_db_conn());
					$grpcnt=0;
					while($rowgrp = mysql_fetch_object($resultgrp)){
				?>
						<div style="width:24%; position:relative; float:left; padding:3px;"><input type="checkbox" id="group_code_idx<?=$grpcnt?>" name="group_code[]" value="<?=$rowgrp->group_code?>" <?=(strlen($group_code[$rowgrp->group_code])>0?"checked":"")?> /><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="group_code_idx<?=$grpcnt++?>" /><?=$rowgrp->group_name?></label></div>
				<?	}
					mysql_free_result($resultgrp);					
					 ?>
					 <?	if($grpcnt<1) { ?>
					* ȸ������� �������� �ʽ��ϴ�.<br>* ȸ������� <a href="javascript:parent.parent.topframe.GoMenu(3,'member_groupnew.php');"><span class="font_blue">��ǰ���� > ī�װ�/��ǰ���� > ��ǰ �ŷ�ó ����</span></a>���� ����ϼ���.</span>
				<?	} else{ ?>
					<div style="clear:both; text-align:right"><input type="checkbox" id="group_codeall_idx" onclick="GroupCodeAll(this.checked,<?=$grpcnt?>);"> <label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="group_codeall_idx">�ϰ�����/����</label></div>
				<?	} ?>
				</div>
			
			</td>
		</tr>			
		<tr>
			<th>��ǰ�������</th>
			<td colspan="3">
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td> ��ǰ���� ���� :
							<select name="gosiTemplet" class="select">
								<option value="">���ø� ����Ʈ �ε���</option>
							</select>
						</td>
					</tr>
					<tr>
						<td> <span class="font_orange"> �� �׸�� �Ǵ� ���� �� �� �κ��̶� ������ ������� �ش� �׸��� ��ϵ��� �ʽ��ϴ�.<br>
							�� ��ǰ ���м����� ���� ������� ������ �⺻ ������ �� �κк� �������� �ʿ�� ������ �����մϴ�.<br>
							�� ������� ���� ����� ���� ��� ������ �ʱ�ȭ�Ǹ�, ��ǰ ���� ����� ����˴ϴ�. </span> </td>
					</tr>
					<tr>
						<td>
							<style type="text/css">
								.dtitleTd{ padding:0px 0px 0px 10px; background-color:#f5f5f5; }
								.daccTd{ padding:8px 0px 8px 10px; }
								.dbtnTd{ padding:10px 0px 10px 0px; }
								.dtitleInput{ width:96%; border:1px solid #ccc; font-family:����; letter-spacing:-1px; }
								.ditemTextarea{ width:98%; line-height:18px;}
							</style>
							<script language="javascript" type="text/javascript">
								function addGosiItem(el,itm){
									var str = '<tr><td colspan="3" height="1" bgcolor="#dddddd"></td></tr>';
										str += '<tr>';
										 str +=      '<td class="dtitleTd"><input type="hidden" name="didx[]" value="" /><input type="text" name="dtitle[]" value="'+((itm && itm.title)?itm.title:'')+'" class="dtitleInput" /></td>';
										 str +=      '<td width="60%"><textarea name="dcontent[]" class="ditemTextarea"></textarea></td>';
									if(itm && itm.desc){
										 str +=      '<td width="90" class="dbtnTd" rowspan="2"><img src="images/btn_info_delete.gif" class="ditemDelBtn" alt="�׸����" style="cursor:hand;" /><br><img src="images/btn_info_add.gif" class="ditemAddBtn" alt="�׸��߰�" style="cursor:hand;" /></td></tr>';
										 str += '<tr><td colspan="2" class="daccTd"><span class="font_orange">* '+itm.desc+'</span></td></tr>';
									}else{
										 str +=      '<td class="dbtnTd"><img src="images/btn_info_delete.gif" class="ditemDelBtn" alt="�׸����" style="cursor:hand;" /><br><img src="images/btn_info_add.gif" class="ditemAddBtn" alt="�׸��߰�" style="cursor:hand;" /></td></tr>';
									}

									if(el){
										 $(el).parent().parent().after(str);
									}else{
										 if($('#detailTable').find('tr').length <1){
											  $('#detailTable').append('<tbody>'+str+'</tbody>');
										 }else{
											  $('#detailTable').find('tr:last').after(str);
										 }
									}
									if($('#detailTable').css('display') == 'none') $('#detailTable').css('display','');
									
								}

								function removeGosiItem(el){
									$(el).parent().parent().remove();
									if($('#detailTable').find('tr').length <1){
										 $('#detailTable').css('display','none');
									}
								}

								$(function(){
									$.post('/lib/ext/getbyjson.php',{'act':'getProductGosiTitles'},
										 function(data){
											  if(data.err != 'ok'){
												   alert(data.err);
											  }else{
												   $('select[name="gosiTemplet"]').find('option').remove();
												   $('select[name="gosiTemplet"]').append('<option value="">== ��ǰ ���� ���� ==</option>');
												   $.each(data.items,function(idx,itm){
														$('select[name="gosiTemplet"]').append('<option value="'+itm.idx+'">'+itm.title+'</option>');
												   });
												   $('select[name="gosiTemplet"]').append('<option value="-1">���� �Է�</option>');
											  }
									},'json');

									$(document).on('change','select[name="gosiTemplet"]',function(){
										 var idx = $(this).val();
										 if(idx == '-1'){
											  addGosiItem(null,null);
										 }else{
											  $.post('/lib/ext/getbyjson.php',{'act':'getProductGosiItems','idx':idx},
												   function(data){
														if(data.err != 'ok'){
															 alert(data.err);
														}else{
															 $('#detailTable').find('tr').remove();
															 $.each(data.items,function(idx,itm){
																  addGosiItem(null,itm);
															 });
														}
												   },'json');
										 }
									});

									$(document).on('click','.ditemAddBtn',function(){
										 addGosiItem(this,null);
									});

									$(document).on('click','.ditemDelBtn',function(){
										 removeGosiItem(this);
									});

								});
							</script>
							<?
						 $detialItems = _getProductDetails($productinfo['pridx']);
						 ?>
							<table width="98%" border="0" cellpadding="0" cellspacing="0" id="detailTable" style="margin:0px 10px 0px 15px; display:<?=(count($detialItems)>0)?'':'none'?>; border-bottom:1px solid #dddddd">
								<? if(count($detialItems)>0){
											foreach($detialItems as $ditem){ ?>
								<tr>
									<td class="dtitleTd">
										<input type="hidden" name="didx[]" value="<?=$ditem['didx']?>" />
										<input type="text" name="dtitle[]" value="<?=$ditem['dtitle']?>" class="dtitleInput" />
									</td>
									<td width="65%">
										<textarea name="dcontent[]" class="ditemTextarea"><?=$ditem['dcontent']?>
</textarea>
									</td>
									<td width="90" class="dbtnTd"><img src="images/btn_info_delete.gif" class="ditemDelBtn" alt="�׸����" style="cursor:hand;" /><br>
										<img src="images/btn_info_add.gif" class="ditemAddBtn" alt="�׸��߰�" style="cursor:hand;" /></td>
								</tr>
								<tr>
									<td colspan="3" height="1" bgcolor="#dddddd"></td>
								</tr>
								<?	 } // end foreach
								} // end if
						?>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>			
		<tr>
			<th>����� ���� ����</th>
			<td colspan="3" style="padding:5px;">
				<TABLE cellSpacing="0" cellPadding="0" width="100%" border="0">						
					<tr>
						<td colspan="2">
							<input type="radio" id="idx_userspec1" name="userspec" onclick="userspec_change('N');" value="N" <?if($userspec!="Y") echo "checked";?>>
							<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_userspec1">����� ���� ���� ������</label>
							&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" id="idx_userspec0" name="userspec" onclick="userspec_change('Y');" value="Y" <?if($userspec=="Y") echo "checked";?>>
							<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_userspec0">����� ���� ���� �����</label>
						</td>
					</tr>
					<tr>
						<td height="5"></td>
					</tr>
					<tr id="userspecidx" <?=($userspec=="Y"?"":"style='display:none;'")?>>
						<td valign="top" bgcolor="#FFF7F0" style="border:2px #FF7100 solid;border-right:1px #FF7100 solide;">
							<TABLE cellSpacing="0" cellPadding="0" width="100%" border="0">
								<tr>
									<td height="7"></td>
								</tr>
								<tr>
									<td align="center" height="30"><b>��<img width="20" height="0">��<img width="20" height="0">��</b></td>
								</tr>
								<tr>
									<td height="3"></td>
								</tr>
								<tr>
									<td style="padding-left:5px;padding-right:5px;">
										<TABLE cellSpacing="0" cellPadding="0" width="100%" border="0">
											<tr>
												<td height="1" bgcolor="#DADADA"></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td height="5"></td>
								</tr>
								<tr>
									<td>
										<TABLE cellSpacing="0" cellPadding="0" width="100%" border="0">
											<col width="20"></col>												
											<col width=""></col>												
											<? for($i=0; $i<$userspec_cnt; $i++) {?>
											<tr>
												<td style="padding:5px;padding-bottom:0px;padding-left:7px;padding-right:2px;" align="center"><?=str_pad(($i+1), 2, "0", STR_PAD_LEFT);?></td>
												<td style="padding:5px;padding-bottom:0px;padding-left:0px;">
													<input name="specname"[] value="<?=htmlspecialchars($specname[$i])?>" size="30" maxlength="30" class="input" style="width:100%;">
												</td>
											</tr>
											<? }?>
										</table>
									</td>
								</tr>
								<tr>
									<td height="10"></td>
								</tr>
							</table>
						</td>
						<td valign="top" bgcolor="#F1FFEF" style="border:2px #57B54A solid;border-left:1px #57B54A solide;">
							<TABLE cellSpacing="0" cellPadding="0" width="100%" border="0">
								<tr>
									<td height="7"></td>
								</tr>
								<tr>
									<td align="center" height="30"><b>��<img width="20" height="0">��<img width="20" height="0">��<img width="20" height="0">��</b></td>
								</tr>
								<tr>
									<td height="3"></td>
								</tr>
								<tr>
									<td style="padding-left:5px;padding-right:5px;">
										<TABLE cellSpacing="0" cellPadding="0" width="100%" border="0">
											<tr>
												<td height="1" bgcolor="#DADADA"></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td height="5"></td>
								</tr>
								<?for($i=0; $i<$userspec_cnt; $i++) {?>
								<tr>
									<td style="padding:5px;padding-bottom:0px;">
										<input name="specvalue"[] value="<?=htmlspecialchars($specvalue[$i])?>" size="50" maxlength="100" class="input" style="width:100%;">
									</td>
								</tr>
								<?}?>
								<tr>
									<td height="10"></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th>�˻���</th>
			<td colspan="3">
				<input name="keyword" value="<? if (_isInt($productinfo['pridx'])) echo $productinfo['keyword']; ?>" size="80" maxlength="100" onKeyDown="chkFieldMaxLen(100)" class="input" style="width:100%">
			</td>
		</tr>			
		<? if(strlen($productinfo['productcode'])==18){?>			
		<tr>
			<th>�±� ����</th>
			<td colspan="3">
				<DIV id="ProductTagList" name="ProductTagList" style="padding:5px;width:600px;height:68px;word-spacing:7px;background:#fafafa"> �±׸� �ҷ����� �ֽ��ϴ�. </DIV>
			</td>
		</tr>
		<script>loadProductTagList('<?=$productinfo['productcode']?>');</script>
		<? } ?>			
		<tr>
			<td class="td_con_orange" colspan="4" style="border-top-width:1pt; border-top-color:rgb(255,153,51); border-top-style:solid;"><b><span class="font_orange">��ǰ�̹������</span></b><br>
				<font color="black">��ǰ ���� �̹��� ����� <B>[��ǰ���� �ΰ���� =&gt; ��ǰ �����̹��� ���]</B> ���� �Ͻ� �� �ֽ��ϴ�.</font> <br>
				<input type="checkbox" id="idx_use_imgurl" name="use_imgurl" value="Y" <?=($use_imgurl=="Y"?"checked":"")?> onclick="change_filetype(this)">
				<label style='cursor:hand;' onmouseover="style.textDecoration=''" onmouseout="style.textDecoration='none'" for="idx_use_imgurl"><span class="font_orange"><B>��ǰ�̹��� ÷�� ����� URL�� �Է��մϴ�.</B> (�� : http://www.abc.com/images/abcd.gif)</font></label>
			</td>
		</tr>			
		<tr>
			<th>�̹���</th>
			<td colspan="3">
				<input type="file" name="userfile" onchange="document.getElementById('size_checker').src=this.value;" style="WIDTH: 400px" class="input">
				<input type="text" name="userfile_url" value="<?=$userfile_url?>" style="WIDTH: 400px; display:none" class="input">
				<span class="font_orange">(�����̹��� : 550X550)</span>
				<input type="hidden" name="vimage" value="<?=$productinfo['maximage']?>">
			<?
		if (_isInt($productinfo['pridx'])) {
			if (!_empty($productinfo['maximage']) && file_exists($imagepath.$productinfo['maximage'])==true) {
				echo "<br><img src='".$imagepath.$productinfo['maximage']."' height=100 width=200 border=1 alt='URL : http://".$_ShopInfo->getShopurl().DataDir."product/".$productinfo['maximage']."'>";
				echo "&nbsp;<a href=\"JavaScript:DeletePrdtImg('1')\"><img src=\"images/icon_del1.gif\" align=bottom border=0></a>";
			} else {
				echo "<br><img src=\"images/space01.gif\">";
			}
		}
?>
				<br>
				<input type="hidden" id="idx_imgcheck1" name="imgcheck" value="Y"<?if (strlen($productinfo['minimage'])>0 || strlen($row->tinyimage)>0) echo "onclick=PrdtAutoImgMsg()"?>>
				<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_imgcheck1"><font color=#003399>�� �̹����� ��, �� �̹��� �ڵ�����(��, �� ���� ������� ����)</font></label>
			</td>
		</tr>
		
		<tr>
			<th>�� �̹���</th>
			<td colspan="3">
				<input type="file" name="userfile2" style="WIDTH: 400px" onchange="document.getElementById('size_checker2').src = this.value;" class="input">
				<input type="text" name="userfile2_url" value="<?=$userfile2_url?>" style="WIDTH: 400px; display:none" class="input">
				<span class="font_orange">(�����̹��� : 300X300)</font>
				<input type="hidden" name="vimage2" value="<?=$productinfo['minimage']?>">
				<?
		if (_isInt($productinfo['pridx'])) {
			if (strlen($productinfo['minimage'])>0 && file_exists($imagepath.$productinfo['minimage'])==true){
				echo "<br><img src='".$imagepath.$productinfo['minimage']."' height=80 width=150 border=1 alt='URL : http://".$_ShopInfo->getShopurl().DataDir."product/".$productinfo['minimage']."'>";
				echo "&nbsp;<a href=\"JavaScript:DeletePrdtImg('2')\"><img src=\"images/icon_del1.gif\" align=bottom border=0></a>";
			} else {
				echo "<br><img src=images/space01.gif>";
			}
		}
?>
			</td>
		</tr>			
		<tr>
			<th>�� �̹���</th>
			<td colspan="3" style="border-bottom-width:1pt; border-bottom-color:rgb(255,153,51); border-bottom-style:solid;">
				<input type="file" name="userfile3" style="WIDTH: 400px" onchange="document.getElementById('size_checker3').src = this.value;" class="input">
				<input type="text" name="userfile3_url" value="<?=$userfile3_url?>" style="WIDTH: 400px; display:none" class="input">
				<span class="font_orange">(�����̹��� : 130X130)</font>
				<input type="hidden" name="setcolor" value="<?=$setcolor?>">
				<input type="hidden" name="vimage3" value="<?=$productinfo['tinyimage']?>">
				<?
		if (_isInt($productinfo['pridx'])) {
			if (strlen($productinfo['tinyimage'])>0 && file_exists($imagepath.$productinfo['tinyimage'])==true){
				echo "<br><img src='".$imagepath.$productinfo['tinyimage']."' height=70 width=120 border=1 alt='URL : http://".$_ShopInfo->getShopurl().DataDir."product/".$productinfo['tinyimage']."'>";
				echo "&nbsp;<a href=\"JavaScript:DeletePrdtImg('3')\"><img src=\"images/icon_del1.gif\" align=bottom border=0></a>";
			} else {
				echo "<br><img src=images/space01.gif>";
			}
		}
?>				
				</td>
		</tr>
		<tr>
			<td class="td_con_orange" colspan="4">
				<table cellpadding="0" cellspacing="0" width="100%">
					<col width="160">
					
							</col>
					
					<col width=>
					
							</col>
					
					<col width="140">
					
							</col>
					
					<tr>
						<td><B><span class="font_orange">��ǰ �󼼳��� �Է�</span></B></td>
						<td>
							<? if($predit_type=="Y" && false){?>
							<input type="radio" id="idx_checkedit1" name="checkedit" checked onClick="JavaScript:htmlsetmode('wysiwyg',this)">
							<label style='cursor:hand;' onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for="idx_checkedit1">��������� �Է��ϱ�(����)</label>
							&nbsp;&nbsp;
							<input type="radio" id="idx_checkedit2" name="checkedit" onClick="JavaScript:htmlsetmode('textedit',this);">
							<label style='cursor:hand;' onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for="idx_checkedit2">���� HTML�� �Է��ϱ�</label>
							<? } ?>
							&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="checkbox" id="idx_localsave" name="localsave" value="Y" <?=($localsave=="Y"?"checked":"")?> onClick="alert('��ǰ �󼼳����� ��ũ�� Ÿ���� �̹����� �� ���θ��� ���� �� ��ũ�� �����ϴ� ����Դϴ�.')">
							<label style='cursor:hand;' onMouseOver="style.textDecoration='none'" onMouseOut="style.textDecoration='none'" for="idx_localsave"><span class="font_orange"><B>Ÿ���� �̹��� ���θ��� ����</B></span></label>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="4"><textarea wrap="off" style="WIDTH: 100%; HEIGHT: 300px" name="content" lang="ej-editor1"><?=htmlspecialchars($productinfo['content'])?></textarea></td>
		</tr>
		<tr>
			<td colspan="4"><img id="size_checker" style="display:none;"><img id="size_checker2" style="display:none;"><img id="size_checker3" style="display:none;"></td>
		</tr>		
	</table>
	<div style="text-align:center; padding:10px;">
	 <? if(_array($productinfo) && _isInt($productinfo['pridx'])){ ?>
			<a href="javascript:CheckForm('modify');"><B><img src="images/btn_infoedit.gif" align="absmiddle" width="162" height="38" border="0" vspace="5"></B></a> &nbsp; <a href="javascript:PrdtDelete();"><B><img src="images/btn_infodelete.gif" align="absmiddle" width="113" height="38" border="0" vspace="5"></B></a>					
			<a href="JavaScript:NewPrdtInsert()"  onMouseOver="window.status='�ű��Է�';return true;"><img src="images/product_newregicn.gif" align="absmiddle" border="0" width="142" height="38" vspace="5"></a>
	<? } else {?>					
			<a href="javascript:CheckForm('insert');"><img src="images/btn_new.gif" align="absmiddle" width="144" height="38" border="0" vspace="5"></a>
		<? }?>									
	</div>		
		
		
	
	<table cellpadding="0" cellspacing="0" width="100%" class="productRegFormTbl" style="margin-top:30px;">
		<caption style="padding-bottom:10px;">
			<IMG SRC="images/shop_basicinfo_stitle_bg.gif" ALT="�߰����� ���/����">
		</caption>				
		<tr>
			<th style="width:140px;">�ɼ� Ÿ�� ����</th>
			<td>
				<input type="radio" id="idx_searchtype0" name="searchtype" style="border:none" onclick="ViewLayer('layer0')" value="0" <? if($searchtype=="0") echo "checked";?>>
				<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_searchtype0">�ɼ����� ����</label>
				<img width="10" height="0">
				<input type="radio" id="idx_searchtype1" name="searchtype" style="border:none" onclick="ViewLayer('layer1');alert('�ɼ�1�� �ɼ�2�� �ִ� 10����\n�� �ɼǺ� ���������� �����ϰ� �˴ϴ�.\n������ ������ ���̻��� �ɼǵ��� �����˴ϴ�.');" value="1" <? if($searchtype=="1") echo "checked";?><?=($productinfo['assembleuse']=="Y"?" disabled":"")?>>
				<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_searchtype1">��ǰ �ɼ� + <font color=#FF0000>������</font></label>
				
				<a href="JavaScript:optionhelp()"><img src="images/product_optionhelp3.gif" align="absmiddle" border="0"></a> <img width="10" height="0">
				<input type="radio" id="idx_searchtype2" name="searchtype" style="border:none" onclick="ViewLayer('layer2')" value="2" <? if($searchtype=="2") echo "checked";?>>
				<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_searchtype2">��ǰ �ɼ� ������ ���</label>
				<? if((int)$_data->vender==0){?>
				<img width=10 height=0>
				<input type=radio id="idx_searchtype3" name=searchtype style="border:none" onclick="ViewLayer('layer3')" value="3" <?if($searchtype=="3") echo "checked";?><?=($_data->assembleuse=="Y"?" disabled":"")?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_searchtype3>�ɼǱ׷�</label>
				<? }?>
				
				<div id="layer0" style="margin-left:0;display:hide; display:block ;border-style:solid; border-width:0; border-color:black;background:#FFFFFF;padding:0;"></div>				
				
				<div id="layer1" style="margin-left:0;display:hide; display:none ;border-style:solid; border-width:0; border-color:black;background:#FFFFFF;padding:0;">
					<TABLE cellSpacing="0" cellPadding="0" width="100%" border="0" style="margin-top:10px; border-top:2px solid #000">
				<?
					$optionarray1=explode(",",$productinfo['option1']);
					$option_price=explode(",",$productinfo['option_price']);
					$optionarray2=explode(",",$productinfo['option2']);
					$option_quantity_array=explode(",",$productinfo['option_quantity']);
					$optnum1=count($optionarray1)-1;
					$optnum2=count($optionarray2)-1;
		
					$optionover="NO";
					if($optnum1>10){
						$optnum1=10;
						$optionover="YES";
					}
					if($optnum2>10){
						$optnum2=10;
						$optionover="YES";
					}
					if($optnum1>0 && strlen($productinfo['option_quantity'])==0) $optionover="YES";
					if($optnum2<=1) $optnum2=1;
				?>
						<tr>
							<th>��ǰ�ɼ� �Ӽ���</th>
							<td><b>�ɼ�1 �Ӽ���</b><B> :<FONT color=#ff6000> </B></FONT>
								<input name="option1_name" value="<? if (strlen($productinfo['option1'])>0) echo htmlspecialchars($optionarray1[0]); ?>" size="20" maxlength="20" class="input">
								&nbsp;&nbsp;&nbsp;&nbsp;<b>�ɼ�2 �Ӽ���</b><B> :<FONT color=#128c02> </B></FONT>
								<input name="option2_name" value="<? if (strlen($productinfo['option2'])>0) echo htmlspecialchars($optionarray2[0]); ?>" size="20" maxlength="20" class="input">
							</td>
						</tr>
						<tr>
							<td colspan="2" background="images/table_top_line.gif"></td>
						</tr>
						<tr>
							<td colspan="2" style="padding-top:3pt; padding-bottom:3pt;">
								1) �ɼǰ��� �Է½� �ǸŰ����� ���õǰ� �ɼǰ������� ���Ű� ����˴ϴ�.<br>
								2) �ǸŻ�ǰ ǰ���� ��� �ɼ� �������� ���� �ִ��� ��ǰ���Ŵ� ������� �ʽ��ϴ�.<br>
								&nbsp;<b>&nbsp;&nbsp;</b>�ɼ� ���������θ� ��ǰ ������ �� ��� �ǸŻ�ǰ �������� ���������� ������ �ּ���.<br>
								3) �ɼ� ������ ���Է½� �ɼ� �������� ������ ���°� �Ǹ� "0" �Է½� �ɼ� �������� ǰ�� ���°� �˴ϴ�.</td>								
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<TABLE cellSpacing="0" cellPadding="0" width="754px" bgColor="#ffffff" border="0">
									<tr>
										<td width="80px" bgColor="#F9F9F9">
											<TABLE cellSpacing="0" cellPadding="0" border="0">
												<tr  bgColor="#FF7100" height="2">
													<td noWrap width="2"></td>
													<td noWrap width="2"></td>
													<td width="100%"></td>
													<td noWrap width="2"></td>
													<td noWrap width="2"></td>
												</tr>
												<tr height="50">
													<td  bgColor="#FF7100" rowSpan="25"></td>
													<td rowSpan="25"></td>
													<td align="middle"><B>�ɼ�1 �Ӽ�</B></td>
													<td rowSpan="25"></td>
													<td  bgColor="#FF7100" rowSpan="25"></td>
												</tr>
												<tr bgColor=#dadada height="1"><td></td></tr>
												<tr height="1"><td></td></tr>
												<?
												for($i=1;$i<=10;$i++){
													if($i==6){ ?><tr height=5><td></td></tr> <? } ?>
													<tr height="7"><td></td></tr>
													<tr height="19"><td align="middle"><input type=text name="optname1" value="<?=trim(htmlspecialchars($optionarray1[$i]))?>" size="8" class="input" /></td></tr>
											<?	} ?>
												<tr height=2><td></td></tr>
												<tr height=2><td colspan=5  bgColor="#FF7100"></td></tr>
											</TABLE>
										</td>
										<td width="80px" bgColor="#F9F9F9">
											<TABLE cellSpacing="0" cellPadding="0" border="0">
												<tr bgColor="#0071C3" height="2">
													<td noWrap width="2"></td>
													<td noWrap width="2"></td>
													<td width="100%"></td>
													<td noWrap width="2"></td>
													<td noWrap width="2"></td>
												</tr>
												<tr height="50">
													<td bgColor="#0071C3" rowSpan="25"></td>
													<td rowSpan="25"></td>
													<td align="middle"><B>����</B></td>
													<td rowSpan="25"></td>
													<td bgColor="#0071C3" rowSpan="25"></td>
												</tr>
												<tr bgColor=#dadada height="1"><td></td></tr>
												<tr height="1"><td></td></tr>
												<?
												for($i=0;$i<10;$i++){
													if($i==5) { ?><tr height=5><td></td></tr> <? } ?>
													<tr height="7"><td></td></tr>
													<tr height="21"><td align="center"><input type="text" name="optprice" size="8" value="<?=$option_price[$i]?>" onkeyup="strnumkeyup(this)" class="input"></td></tr>
											<?	} ?>
												<tr height=2><td></td></tr>
												<tr height=2><td colspan=5 bgColor="#0071C3"></td></tr>
											</TABLE>
										</td>
										<td vAlign="top" width="585px" bgColor="#ffffff">
											<TABLE cellSpacing="0" cellPadding="0" border="0">
												<tr bgColor=#57B54A height="2">
													<td width="2" rowSpan="4"></td>
													<td width="2"></td>
													<td width="80"></td>
													<td width="80"></td>
													<td width="80"></td>
													<td width="80"></td>
													<td width="80"></td>
													<td width="80"></td>
													<td width="80"></td>
													<td width="80"></td>
													<td width="80"></td>
													<td width="80"></td>
													<td width="2"></td>
													<td width="2" rowSpan="4"></td>
												</tr>
												<tr bgColor=#f1ffef height="27">
													<td width="2" rowspan="2"></td>
													<td align="middle" colSpan="10" bgcolor="#F9F9F9"><b>�ɼ�2 �Ӽ�</b></td>
													<td width="2" rowspan="2"></td>
												</tr>
												<tr bgColor="#f1ffef" height="19">
										<?		for($i=1;$i<=10;$i++){ ?>
													<td align="middle" width="20%" bgcolor="#F9F9F9">
														<input type="text" name="optname2" value="<?=htmlspecialchars($optionarray2[$i])?>" size="8" class="input" /></td>
										<?		} ?>
												</tr>
												<tr bgColor="#F9F9F9" height="4"><td colSpan="12"></td></tr>
												<tr bgColor="#57B54A" height="2"><td colSpan="14"></td></tr>
												<tr height="6">
													<td colSpan="2" rowSpan="22"></td>
													<td colSpan="10"></td>
													<td colSpan="2" rowSpan="22"></td>
												</tr>
												<?
												for($i=0;$i<10;$i++){
													if($i!=0 && $i!=5){ ?> 
												<tr><td colspan=10 height=7></td></tr> 
												<?	}else if($i==5){?>
												<tr><td colspan="10" height="6"></td></tr>
												<tr><td colspan="10" height="1" bgcolor="#DADADA"></td></tr>
												<tr><td colspan="10" height="5"></td></tr>
												<? }?>
												<tr height="19">
												<?	for($j=0;$j<10;$j++){ ?>
													<td align="middle"><input type="text" name="optnumvalue[<?=$j?>][<?=$i?>]" value="<?=$option_quantity_array[$j*10+$i+1]?>" size="8" maxlength="3" onkeyup="strnumkeyup(this)" class="input"></td>
												<?	} ?>
												</tr>
											<?		
												} ?>
											</TABLE>
										</td>
									</tr>
								</TABLE>
							</td>
						</tr>
						<tr>
							<td colspan="2" height="5"></td>
						</tr>
						<tr>
							<td colspan="2" background="images/table_top_line.gif"></td>
						</tr>
					</table>
				</div>
				
				<div id="layer2" style="margin-left:0;display:hide; display:none ;border-style:solid; border-width:0; border-color:black;background:#FFFFFF;padding:0;">
					<TABLE cellSpacing="0" cellPadding="0" width="100%" border="0">
						<col width="160">
						
								</col>
						
						<col width=>
						
								</col>
						
						<tr>
							<td colspan="2" background="images/table_top_line.gif"></td>
						</tr>
						<tr>
							<th>�ɼ�1</th>
							<td>
						<? $option1 = $optname1="";
							if(_array($productinfo)){
								if (strlen($productinfo['option1'])>0) {
									$tok = strtok($productinfo['option1'],",");
									$optname1=$tok;
									$tok = strtok("");
									$option1=$tok;
								}
							} ?>
								<TABLE cellSpacing="0" cellPadding="0" border="0" width="100%">
									<col width="76"></col>									
									<col width=></col>											
									<tr>
										<td>1)�Ӽ���</td>
										<td style="PADDING-LEFT: 5px">
											<input name="toptname1" value="<? if (strlen($productinfo['option1'])>0) echo $optname1; ?>" size="50" maxlength="20" class="input">
										</td>
									</tr>
									<tr>
										<td>2)�Ӽ�</td>
										<td style="PADDING-LEFT: 5px">
											<input name="toption1" value="<? if (strlen($productinfo['option1'])>0) echo htmlspecialchars($option1); ?>" size="50" maxlength="230" class="input">
										</td>
									</tr>
									<tr>
										<td style="PADDING-LEFT: 3px" colSpan="2">* �ɼ��� �Ӽ������� ���� �Ǵ� ������ �Ǵ� �뷮 ���� �Է��ؼ� ����ϼ���.<br>
											* �Ӽ��� �Ӽ��� ���� ���γ����� �Է��մϴ�.<br>
											&nbsp;&nbsp;&nbsp;��)����,�Ķ�,��� �Ǵ� 95,100,105 �� ���� �ĸ�(,)�� �����Ͽ� ������� �Է��մϴ�.</td>
									</tr>
								</TABLE>
							</td>
						</tr>
						<tr>
							<td colspan="2" background="images/table_con_line.gif"></td>
						</tr>
						<tr>
							<th>�ɼ�1 ����</th>
							<td>
								<? if($gongtype=="N"){?>
								<input name="toption_price" value="<? if (_isInt($productinfo['pridx'])) echo $productinfo['option_price']; ?>" size="50" maxlength="250" class="input">
								&nbsp;<span class="font_orange"><b>��) 1000,2000,3000</b></span><br>
								* �ɼ�1 ���� �Է½� �ǸŰ����� ���õ˴ϴ�.<br>
								* �ɼ�1 ���� �Է½� �ǸŰ��� ��� ù��° ������ �ǸŰ������� ���˴ϴ�.<br>
								* ī�װ��� ��ǰ ��½� "�ǸŰ��� (�⺻��)"�� ǥ�� �˴ϴ�.<br>
								* �޼��� ������
								<?=($popup=="YES"?"":"<A HREF=\"javascript:parent.parent.topframe.GoMenu(1,'shop_mainproduct.php');\">")?>
								<span class="font_blue">�������� > ���θ� ȯ�漳�� > ��ǰ ���� ��Ÿ ����</span></A> ���� ���� ����.
								<? } else { ?>
								���� ������ ���������� ��� �ɼ�1 ������ �������� �ʽ��ϴ�.
								<input type="hidden" name="toption_price">
								<? } ?>
							</td>
						</tr>
						<tr>
							<td colspan="2" background="images/table_con_line.gif"></td>
						</tr>
						<tr>
							<th>�ɼ�2</th>
							<td>
					<? 		$option2=$optname2="";
							if(_array($productinfo)){
								if (strlen($productinfo['option2'])>0) {
									$tok = strtok($productinfo['option2'],",");
									$optname2=$tok;
									$tok = strtok("");
									$option2=$tok;
								}
							} ?>
								<TABLE cellSpacing="0" cellPadding="0" border="0" width="100%">
									<col width="76"></col>											
									<col width=></col>
									
									<tr>
										<td>1)�Ӽ���</td>
										<td style="PADDING-LEFT: 5px">
											<input name="toptname2" value="<? if (_isInt($productinfo['pridx']) && strlen($productinfo['option2'])>0) echo $optname2; ?>" size="50" maxlength="20" class="input">
										</td>
									</tr>
									<tr>
										<td>2)�Ӽ�</td>
										<td style="PADDING-LEFT: 5px">
											<input name="toption2" value="<? if (_isInt($productinfo['pridx']) && strlen($productinfo['option2'])>0) echo htmlspecialchars($option2); ?>" size="50" maxlength="230" class="input">
										</td>
									</tr>
									<tr>
										<td style="PADDING-LEFT: 3px" colSpan="2">* �ɼ�1 ��� ����� ������ "<B>�ɼ�1 ����</B>"���� �����մϴ�.</td>
									</tr>
								</TABLE>
							</td>
						</tr>
						<tr>
							<td colspan="2" background="images/table_top_line.gif"></td>
						</tr>
					</table>
				</div>
				
				<div id=layer3 style="margin-left:0;display:hide; display:none ;border-style:solid; border-width:0; border-color:black;background:#FFFFFF;padding:0;">
				<? if($gongtype=="N"){?>
					<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
						<col width=160></col>
						<col width=></col>
						<TR>
							<TD colspan=2 background="images/table_top_line.gif"></TD>
						</TR>
						<TR>
							<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">�ɼǱ׷� ����</TD>
							<TD class="td_con1">
								<select name=optiongroup style="width: 70%" class="select">
							<?
							$sqlopt = "SELECT option_code,description FROM tblproductoption ";
							$resultopt = mysql_query($sqlopt,get_db_conn());
							$optcnt=0;
							while($rowopt = mysql_fetch_object($resultopt)){
								if($optcnt++==0) echo "<option value=0>�ɼǱ׷��� �����ϼ���.";
								echo "<option value=\"".$rowopt->option_code."\"";
								if($optcode==$rowopt->option_code) echo " selected";
								echo ">".$rowopt->description."</option>";
							}
							mysql_free_result($resultopt);
							if($optcnt==0) echo "<option value=0>����Ͻ� �ɼǱ׷��� �����ϴ�.</option>";
							?>
					</select>
					<?if($popup!="YES"){?><A HREF="javascript:location='product_option.php';"><B><img src="images/btn_option.gif" width="105" height="18" border="0" hspace="2" align=absmiddle></B></A><?}?>
					<?if($optcnt==0) echo "<script>document.form1.optiongroup.disabled=true;</script>";?>
	
					<br>* (��ǰ����+�ɼ�) ���氡�� ���� �ɼǱ׷��� �̿��� �ּ���.
					<br>* �ɼǱ׷� ���� �ɼ�1�� �ɼ�2�� �ڵ� �����˴ϴ�.
					<br>* �ɼǱ׷� ���ý� �ش� �ɼǱ׷쿡 ��ϵ� ��ǰ�ɼ��� Ȯ���� �� �ֽ��ϴ�.
							</TD>
						</TR>
					</TABLE>
				<? }?>
				</div>
			</td>
		</tr>
		<tr>
			<th>������ �ٹ̱�</th>
			<td>
			<?
			$iconarray = array("01","02","03","04","05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28");
			$totaliconnum = 0;
			
			for($i=0;$i<count($iconarray);$i++) { ?>
				<div style="width:150px; float:left; padding:3px 0px;"><input type=checkbox name="icon" onclick="CheckChoiceIcon('<?=$totaliconnum++?>')" value="<?=$iconarray[$i]?>" <? if($iconvalue2[$iconarray[$i]]=="Y") echo "checked" ?> /><img src="<?=$Dir?>images/common/icon<?=$iconarray[$i]?>.gif" border=0 align=absmiddle></div>
		<?	} ?>
			</td>
		</tr>
		<tr>
			<th>��ǰ��������</th>
			<td>
				<input type="radio" id="idx_display1" name="display" value="Y" <? if (_isInt($productinfo['pridx'])) { if ($productinfo['display']=="Y") echo "checked"; } else echo "checked";  ?>>
				<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_display1">������</label>
				&nbsp;
				<input type="radio" id="idx_display2" name="display" value="N" <? if (_isInt($productinfo['pridx'])) { if ($productinfo['display']=="N") echo "checked"; } ?> onclick="JavaScript:alert('���� ȭ���� ��ǰ Ư¡�� �������� ����˴ϴ�.')">
				<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_display2">��������</label>
			</td>
		</tr>
		<tr>
			<th>��ǰ��Ÿ����</th>
			<td>
				<TABLE cellSpacing="0" cellPadding="0" width="100%" border="0">
					<tr>
						<td>
							<input type="checkbox" id="idx_bankonly1" name="bankonly" value="Y" <? if (_isInt($productinfo['pridx'])) { if ($bankonly=="Y") echo "checked";}?>>
							<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_bankonly1">���ݰ����� ����ϱ�</label>
							<span class="font_orange">(���� ��ǰ�� �Բ� ���Ž� ������ ���ݰ����θ� ����˴ϴ�.)</span></td>
						<td></td>
					</tr>
					<? if ($card_splittype=="O") { ?>
					<tr>
						<td>
							<input type="checkbox" id="idx_setquota1" name="setquota" value="Y" <? if (_isInt($productinfo['pridx'])) { if ($setquota=="Y") echo "checked";}?>>
							<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_setquota1">�����δ� ������</label>
							<span class="font_orange">(�����ݾ�/�������Һΰ����� <a  href="shop_payment.php"><b>�������ñ�ɼ���</b></a>�� ����)</span></td>
						<td></td>
					</tr>
					<? } ?>
					<? if ($gongtype=="N") { ?>
					<tr>
						<td style="PADDING-TOP: 5px">
							<input type="checkbox" id="idx_dicker1" name="dicker" value="Y" <? if (_isInt($productinfo['pridx'])) { if ($dicker=="Y") echo "checked";}?>>
							<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_dicker1"><b>�ǸŰ��� ��ü����</b></label>
							&nbsp;
							<input type="text" name="dicker_text" value="<?=$dicker_text?>" size="20" maxlength="20" onKeyDown="chkFieldMaxLen(20)" class="input">
							<span class="font_orange">* ��) �ǸŴ���ǰ, ��㹮��(000-000-000)</span></td>
						<td></td>
					</tr>
					<tr>
						<td colSpan="2"><!-- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;* <b>�ǸŰ��� ��ü����</b>�� ��ǰ �ǸŰ��� ��� ���ϴ� ������ ��½�Ű�� ����Դϴ�.<br> -->&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;* <b>�ǸŰ��� ��ü����</b> �Է°��� ���� ���� �ѱ� 10��, ���� 20�ڷ� ���ѵǾ� �ֽ��ϴ�.<br>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;* <b>�ǸŰ��� ��ü����</b> ���� �ֹ��� ������� �ʽ��ϴ�.</td>
					</tr>
					<? } ?>
					<tr>
						<td style="PADDING-TOP: 5px">
							<input type="checkbox" id="idx_deliinfono1" name="deliinfono" value="Y" <? if (_isInt($productinfo['pridx'])) { if ($deliinfono=="Y") echo "checked";}?>>
							<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_deliinfono1">���/��ȯ/ȯ������ �������</label>
							<font color="#AA0000">(��ǰ��ȭ�� �ϴܿ� ���/��ȯ/ȯ�������� ����ȵ�)</font></td>
						<td></td>
					</tr>
				</TABLE>
			</td>
		</tr>
<?	if($sns_ok == "Y"){ ?>
		<tr>
			<th>SNS ��뿩��</th>
			<td>
				<input type="radio" id="sns_state1" name="sns_state" value="Y" <? if (_isInt($productinfo['pridx'])) { if ($productinfo['sns_state']=="Y") echo "checked"; }  ?> onclick="ViewSnsLayer('block')" >
				<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="sns_state1">�����</label>
				&nbsp;
				<input type="radio" id="sns_state2" name="sns_state" value="N" <? if (_isInt($productinfo['pridx'])) { if ($productinfo['sns_state'] !="Y") echo "checked"; } else echo "checked"; ?> onclick="ViewSnsLayer('none')" >
				<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="sns_state2">������</label>
			</td>
		</tr>
<? 		if($arSnsType[0] =="B"){ ?>
		<tr id ="sns_optionWrap" style="display:none;">
			<td colspan="2">
				<TABLE cellSpacing="0" cellPadding="0" width="100%" border="0">
					<col width="140">
					
							</col>
					
					<col>
					
							</col>
					
					<col width="140">
					
							</col>
					
					<col>
					
							</col>
					
					<tr>
						<th style="width:140px;">��õ�� ������(��)</th>
						<td>
							<input name="sns_reserve1" value="<?=$productinfo['sns_reserve1']?>" size="10" maxlength="6" class="input" style="width:45%" onKeyUP="chkFieldMaxLenFunc(this.form,this.form.sns_reserve1_type.value);">
							<select name="sns_reserve1_type" class="select" onchange="chkFieldMaxLenFunc(this.form,this.value);">
								<option value="N"<?=($productinfo['sns_reserve1_type']!="Y"?" selected":"")?>>������(��)</option>
								<option value="Y"<?=($productinfo['sns_reserve1_type']!="Y"?"":" selected")?>>������(%)</option>
							</select>
						</td>
						<td style=" width:140px; border-left-width:1pt; border-color:rgb(227,227,227); border-top-style:none; border-right-style:none; border-bottom-style:none; border-left-style:solid;"><img src="images/icon_point5.gif" width="8" height="11" border="0">����õ�� ������(��)</td>
						<td>
							<input name="sns_reserve2" value="<?=$productinfo['sns_reserve2']?>" size="10" maxlength="6" class="input" style="width:45%" onKeyUP="chkFieldMaxLenFunc(this.form,this.form.sns_reserve2_type.value);">
							<select name="sns_reserve2_type" class="select" onchange="chkFieldMaxLenFunc(this.form,this.value);">
								<option value="N"<?=($productinfo['sns_reserve2_type']!="Y"?" selected":"")?>>������(��)</option>
								<option value="Y"<?=($productinfo['sns_reserve2_type']!="Y"?"":" selected")?>>������(%)</option>
							</select>
						</td>
					</tr>
				</table>
			</td>
		</tr>
<?		}
	}?>
		<tr>
			<th>�����ϱ� ��뿩��</th>
			<td>
				<input type="radio" id="present_state1" name="present_state" value="Y" <? if (_isInt($productinfo['pridx'])) { if ($productinfo['present_state']=="Y") echo "checked"; } ?>>
				<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="present_state1">�����</label>
				&nbsp;
				<input type="radio" id="present_state2" name="present_state" value="N" <? if (_isInt($productinfo['pridx'])) { if ($productinfo['present_state']!="Y") echo "checked"; } else echo "checked"; ?>>
				<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="present_state2">������</label>
			</td>
		</tr>
		<tr>
			<th>������ ��뿩��</th>
			<td>
				<input type="radio" id="pester_state1" name="pester_state" value="Y" <? if (_isInt($productinfo['pridx'])) { if ($productinfo['pester_state']=="Y") echo "checked"; } ?>>
				<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="pester_state1">�����</label>
				&nbsp;
				<input type="radio" id="pester_state2" name="pester_state" value="N" <? if (_isInt($productinfo['pridx'])) { if ($productinfo['pester_state']!="Y") echo "checked"; } else echo "checked";  ?>>
				<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="pester_state2">������</label>
			</td>
		</tr>
<? if($arRecomType[0] =="B" && $arRecomType[1] == "B"){ ?>
		<tr>
			<th>ù���Ž� ��õ�� ����</th>
			<td>
				<input name="first_reserve" value="<?=$productinfo['first_reserve']?>" size="10" maxlength="6" class="input" style="width:20%" onKeyUP="chkFieldMaxLenFunc(this.form,this.form.first_reserve_type.value);">
				<select name="first_reserve_type" class="select" onchange="chkFieldMaxLenFunc(this.form,this.value);">
					<option value="N"<?=($productinfo['first_reserve_type']!="Y"?" selected":"")?>>������(��)</option>
					<option value="Y"<?=($productinfo['first_reserve_type']!="Y"?"":" selected")?>>������(%)</option>
				</select>
				<span color="#ff0000"> *snsȫ���� ���� ù���ſ��� ����</span> </td>
		</tr>
<?	}?>		
	</table>
	<div style="text-align:center"><? if(_array($productinfo) && _isInt($productinfo['pridx'])) { ?>				
				<a href="javascript:CheckForm('modify');"><B><img src="images/btn_infoedit.gif" align="absmiddle" width="162" height="38" border="0" vspace="5"></B></a> &nbsp; <a href="javascript:PrdtDelete();"><B><img src="images/btn_infodelete.gif" align="absmiddle" width="113" height="38" border="0" vspace="5"></B></a>
				<a href="JavaScript:NewPrdtInsert()"  onMouseOver="window.status='�ű��Է�';return true;"><img src="images/product_newregicn.gif" align="absmiddle" border="0" width="142" height="38" vspace="5"></a>
				
				<? } else {?>
				<a href="javascript:CheckForm('insert');"><img src="images/btn_new.gif" align="absmiddle" width="144" height="38" border="0" vspace="5"></a>
				<? }?></div>
	<input type="hidden" name="iconnum" value='<?=$totaliconnum?>'>
	<input type="hidden" name="iconvalue">
	<input type="hidden" name="optnum1" value=<?=$optnum1?>>
	<input type="hidden" name="optnum2" value=<?=$optnum2?>>
</form>
<form name="cForm" action="<?=$_SERVER[PHP_SELF]?>" method="post">
	<input type="hidden" name="mode">
	<input type="hidden" name="popup" value="<?=$popup?>">
	<input type="hidden" name="code" value=<?=$code?>>
	<input type="hidden" name="prcode" value=<?=$prcode?>>
	<input type="hidden" name="delprdtimg">
	<input type="hidden" name="vimage" value="<? if (_isInt($productinfo['pridx'])) echo $productinfo['maximage']; ?>">
	<input type="hidden" name="vimage2" value="<? if (_isInt($productinfo['pridx'])) echo $productinfo['minimage']; ?>">
	<input type="hidden" name="vimage3" value="<? if (_isInt($productinfo['pridx'])) echo $productinfo['tinyimage']; ?>">
</form>
<form name="icon" action="product_iconmy.php" method="post" target="icon">
</form>
<form name="iconlist" action="product_iconlist.php" method="post" target="iconlist">
</form>
<?=$onload?>
<?
if (strlen($code)==12 && $predit_type=="Y") {
?>
<script language="Javascript1.2" src="htmlarea/editor.js"></script> 
<script language="JavaScript">
function htmlsetmode(mode,i){
	if(mode==document.form1.htmlmode.value) {
		return;
	} else {
		//i.checked=true;
		//editor_setmode('content',mode);
	}
	document.form1.htmlmode.value=mode;
}
//_editor_url = "htmlarea/";
//editor_generate('content');
</script>
<?
}
?>
<SCRIPT LANGUAGE="JavaScript">
<!--



// ��ǰ�� ȸ��������
function DiscountPrd(mode) {
	document.form1.mode.value=mode;
	document.form1.submit();
}



//��ǰ�� ȸ�������� �ڵ����
function autoCal(gubun,val,obj){
	if(gubun=="1"){
		document.getElementById(obj).value = eval(document.form1.sellprice.value * (val/100)).toFixed(0);
	}else{
		document.getElementById(obj).value = eval((val/document.form1.sellprice.value) *100).toFixed(2);
	}
}


function CheckForm(mode) {
	if (document.form1.productname.value.length==0) {
		alert("��ǰ���� �Է��ϼ���.");
		document.form1.productname.focus();
		return;
	}
	if (CheckLength(document.form1.productname)>300) {
		alert('�� �Է°����� ���̰� �ѱ� 150�ڱ����Դϴ�. �ٽ��ѹ� Ȯ���Ͻñ� �ٶ��ϴ�.');
		document.form1.productname.focus();
		return;
	}
	if (document.form1.consumerprice.value.length==0) {
		alert("���󰡸� �Է��ϼ���.");
		document.form1.consumerprice.focus();
		return;
	}
	if (isNaN(document.form1.consumerprice.value)) {
		alert("���󰡸� ���ڷθ� �Է��ϼ���.(�޸�����)");
		document.form1.consumerprice.focus();
		return;
	}
	
	if (document.form1.sellprice.value.length==0) {
		alert("�ǸŰ��� �Է��ϼ���.");
		document.form1.sellprice.focus();
		return;
	}
	if (isNaN(document.form1.sellprice.value)) {
		alert("�ǸŰ��� ���ڷθ� �Է��ϼ���.(�޸�����)");
		document.form1.sellprice.focus();
		return;
	}

	if (document.form1.reserve.value.length>0) {
		if(document.form1.reservetype.value=="Y") {
			if(isDigitSpecial(document.form1.reserve.value,".")) {
				alert("�������� ���ڿ� Ư������ �Ҽ���\(.\)���θ� �Է��ϼ���.");
				document.form1.reserve.focus();
				return;
			}

			if(getSplitCount(document.form1.reserve.value,".")>2) {
				alert("������ �Ҽ���\(.\)�� �ѹ��� ��밡���մϴ�.");
				document.form1.reserve.focus();
				return;
			}

			if(getPointCount(document.form1.reserve.value,".",2)==true) {
				alert("�������� �Ҽ��� ���� ��°�ڸ������� �Է� �����մϴ�.");
				document.form1.reserve.focus();
				return;
			}

			if(Number(document.form1.reserve.value)>100 || Number(document.form1.reserve.value)<0) {
				alert("�������� 0 ���� ũ�� 100 ���� ���� ���� �Է��� �ּ���.");
				document.form1.reserve.focus();
				return;
			}
		} else {
			if(isDigitSpecial(document.form1.reserve.value,"")) {
				alert("�������� ���ڷθ� �Է��ϼ���.");
				document.form1.reserve.focus();
				return;
			}
		}
	}


	if (!document.form1.quantity_unlimit.checked){
		if (document.form1.quantity.value.length==0) {
			alert("���Ѽ����� �Է��ϼ���.");
			document.form1.quantity.focus();
			return;
		} else if (isNaN(document.form1.quantity.value)) {
			alert("���Ѽ����� ���ڷθ� �Է��ϼ���.");
			document.form1.quantity.focus();
			return;
		}else if (parseInt(document.form1.quantity.value)<=0) {
			alert("���Ѽ����� 0���̻��̿��� �մϴ�.");
			document.form1.quantity.focus();
			return;
		}
	}
	miniq_obj=document.form1.miniq;
	maxq_obj=document.form1.maxq;
	if (miniq_obj.value.length>0) {
		if (isNaN(miniq_obj.value)) {
			alert ("�ּ��ֹ��ѵ��� ���ڷθ� �Է��� �ּ���.");
			miniq_obj.focus();
			return;
		}
	}
	if (document.form1.checkmaxq[1].checked==true) {
		if (maxq_obj.value.length==0) {
			alert ("�ִ��ֹ��ѵ��� ������ �Է��� �ּ���.");
			maxq_obj.focus();
			return;
		} else if (isNaN(maxq_obj.value)) {
			alert ("�ִ��ֹ��ѵ��� ������ ���ڷθ� �Է��� �ּ���.");
			maxq_obj.focus();
			return;
		}
	}
	if (miniq_obj.value.length>0 && document.form1.checkmaxq[1].checked==true && maxq_obj.value.length>0) {
		if (parseInt(miniq_obj.value) > parseInt(maxq_obj.value)) {
			alert ("�ּ��ֹ��ѵ��� �ִ��ֹ��ѵ� ���� �۾ƾ� �մϴ�.");
			miniq_obj.focus();
			return;
		}
	}
	if(document.form1.deli[3].checked==true || document.form1.deli[4].checked==true) {
		if(document.form1.deli[3].checked==true)
		{
			if (document.form1.deli_price_value1.value.length==0) {
				alert("������ۺ� �Է��ϼ���.");
				document.form1.deli_price_value1.focus();
				return;
			} else if (isNaN(document.form1.deli_price_value1.value)) {
				alert("������ۺ�� ���ڷθ� �Է��ϼ���.");
				document.form1.deli_price_value1.focus();
				return;
			} else if (parseInt(document.form1.deli_price_value1.value)<=0) {
				alert("������ۺ�� 0�� �̻� �Է��ϼž� �մϴ�.");
				document.form1.deli_price_value1.focus();
				return;
			}
		}
		else
		{
			if (document.form1.deli_price_value2.value.length==0) {
				alert("������ۺ� �Է��ϼ���.");
				document.form1.deli_price_value2.focus();
				return;
			} else if (isNaN(document.form1.deli_price_value2.value)) {
				alert("������ۺ�� ���ڷθ� �Է��ϼ���.");
				document.form1.deli_price_value2.focus();
				return;
			} else if (parseInt(document.form1.deli_price_value2.value)<=0) {
				alert("������ۺ�� 0�� �̻� �Է��ϼž� �մϴ�.");
				document.form1.deli_price_value2.focus();
				return;
			}
		}
	}

	searchtype=false;
	for(i=0;i<document.form1.searchtype.length;i++) {
		if(document.form1.searchtype[i].checked==true) {
			searchtype=true;
			shop="layer"+i;
			break;
		}
	}


	if(document.form1.sellprice.disabled==false) {
		if(shop=="layer0") {

		} else if(shop=="layer1"){
			optnum1=0;
			optnum2=0;

			//�ɼ�1 �׸�
			document.form1.option1.value="";
			for(i=0;i<10;i++){
				if(document.form1.optname1[i].value.length>0) {
					document.form1.option1.value+=document.form1.optname1[i].value+",";
					optnum1++;
				}
			}

			//�ɼ�1 ���� �˻� (�ɼ�1 �׸��� NULL�� �ƴϸ�)
			if((document.form1.option1.value.length!=0 && document.form1.option1_name.value.length==0)
			|| (document.form1.option1.value.length==0 && document.form1.option1_name.value.length!=0)){
				alert('�� �ɼǺ� �����Է°� [�ɼ�����]�� Ȯ�����ּ���!');
				if(document.form1.option1_name.value.length==0) {
					document.form1.option1_name.focus();
				} else {
					document.form1.optname1[0].focus();
				}
				return;
			}

			//�ɼ�2 �׸�
			document.form1.option2.value="";
			for(i=0;i<10;i++){
				if(document.form1.optname2[i].value.length>0) {
					document.form1.option2.value+=document.form1.optname2[i].value+",";
					optnum2++;
				}
			}

			//�ɼ�2 ���� �˻� (�ɼ�2 �׸��� NULL�� �ƴϸ�)
			if((document.form1.option2.value.length!=0 && document.form1.option2_name.value.length==0)
			|| (document.form1.option2.value.length==0 && document.form1.option2_name.value.length!=0)){
				alert('�� �ɼǺ� �����Է°� [�ɼ�����]�� Ȯ�����ּ���!');
				if(document.form1.option2_name.value.length==0) {
					document.form1.option2_name.focus();
				} else {
					document.form1.optname2[0].focus();
				}
				return;
			}

			//�ɼ�2�� �Է��ߴ��� �˻�
			if(document.form1.option1.value.length==0 && document.form1.option2.value.length>0) {
				alert('�ɼ�2�� �ɼ�1 �Է��� �Է°����մϴ�.');
				document.form1.option1_name.focus();
				return;
			}

			//�ɼ�1�� ���� ���� �˻�
			document.form1.option_price.value="";
			pricecnt=0;
			for(i=0;i<optnum1;i++){
				if(document.form1.optprice[i].value.length==0){
					pricecnt++;
				}else{
					document.form1.option_price.value+=document.form1.optprice[i].value+",";
				}
			}
			if(optnum1>0 && pricecnt!=0 && pricecnt!=optnum1){
				alert('�ɼǺ� ������ ��� �Է��ϰų� ��� �Է����� �ʾƾ� �մϴ�.');
				document.form1.optprice[0].focus();
				return;
			}

			if(document.form1.option_price.value.length!=0) temp=0;
			else temp=-1;
			temp2=document.form1.option_price.value;
			while(temp!=-1){
				temp=temp2.indexOf(",");
				if(temp!=-1) temp3=(temp2.substring(0,temp));
				else temp3=temp2;
				if(isNaN(temp3)){
					alert("�ɼ� ������ ���ڸ� �Է��� �ϼž� �մϴ�.");
					document.form1.option_price.focus();
					return;
				}
				temp2=temp2.substring(temp+1);
			}

			//������ �� ���ڰ˻�
			isquan=false;
			quanobj="";
			for(i=0;i<10;i++) {
				isgbn1=false;
				if(i<optnum1) isgbn1=true;

				for(j=0;j<10;j++) {
					isgbn2=false;
					if(optnum2>0) {
						if(j<optnum2 && isgbn1==true) isgbn2=true;
					} else {
						if(j==0 && isgbn1==true) isgbn2=true;
					}

					if(isgbn2==true) {
						if(isquan==false && document.form1["optnumvalue["+j+"]["+i+"]"].value.length==0) {
							isquan=true;
							quanobj=document.form1["optnumvalue["+j+"]["+i+"]"];
						}
					} else {
						if(document.form1["optnumvalue["+j+"]["+i+"]"].value.length>0) {
							alert("�Է��Ͻ� ������ �ɼ������� ������ �Ѿ����ϴ�. ("+(i+1)+" °�� "+(j+1)+" °ĭ)");
							document.form1["optnumvalue["+j+"]["+i+"]"]. focus();
							return;
						}
					}
				}
			}
			if(isquan==true) {
				if(!confirm("���� �Է��� �ȵ� �ɼ������� ������ �������� ��ϵ˴ϴ�.\n\n��� �Ͻðڽ��ϱ�?")) {
					quanobj.focus();
					return;
				}
			}

		} else if(shop=="layer2"){
			if (document.form1.toption_price.value.length!=0 && document.form1.toption1.value.length==0) {
				alert("Ư���ڵ庰������ �Է��ϸ� �ݵ�� Ư���ڵ��Է�1���� ������ �Է��ؾ� �մϴ�.");
				document.form1.toption1.focus();
				return;
			}
			if(document.form1.toption_price.value.length!=0) temp=0;
			else temp=-1;
			temp2=document.form1.toption_price.value;
			while(temp!=-1){
				temp=temp2.indexOf(",");
				if(temp!=-1) temp3=(temp2.substring(0,temp));
				else temp3=temp2;
				temp4=" "+temp3;
				if(isNaN(temp3) || temp4.indexOf('.')>0){
					alert("�ɼ� ������ ���ڸ� �Է��� �ϼž� �մϴ�.");
					document.form1.toption_price.focus();
					return;
				}
				temp2=temp2.substring(temp+1);
			}
			document.form1.option_price.value=document.form1.toption_price.value+",";
			document.form1.option1_name.value=document.form1.toptname1.value;
			document.form1.option1.value=document.form1.toption1.value+",";
			document.form1.option2_name.value=document.form1.toptname2.value;
			document.form1.option2.value=document.form1.toption2.value+",";
<?	if((int)$productinfo['vender']==0){?>
		} else if(shop=="layer3") {
			if(document.form1.optiongroup.selectedIndex==0) {
				alert("�ɼǱ׷��� �����ϼ���.");
				document.form1.optiongroup.focus();
				return;
			}
<? } ?>
		}
	}
<?
	if($sns_ok =="Y" && $arSnsType[0] =="B"){
?>
	if (document.form1.sns_reserve1.value.length>0) {
		if(document.form1.sns_reserve1_type.value=="Y") {
			if(isDigitSpecial(document.form1.reserve.value,".")) {
				alert("�������� ���ڿ� Ư������ �Ҽ���\(.\)���θ� �Է��ϼ���.");
				document.form1.sns_reserve1.focus();
				return;
			}

			if(getSplitCount(document.form1.sns_reserve1.value,".")>2) {
				alert("������ �Ҽ���\(.\)�� �ѹ��� ��밡���մϴ�.");
				document.form1.sns_reserve1.focus();
				return;
			}

			if(getPointCount(document.form1.sns_reserve1.value,".",2)==true) {
				alert("�������� �Ҽ��� ���� ��°�ڸ������� �Է� �����մϴ�.");
				document.form1.sns_reserve1.focus();
				return;
			}

			if(Number(document.form1.sns_reserve1.value)>100 || Number(document.form1.sns_reserve1.value)<0) {
				alert("�������� 0 ���� ũ�� 100 ���� ���� ���� �Է��� �ּ���.");
				document.form1.sns_reserve1.focus();
				return;
			}
		} else {
			if(isDigitSpecial(document.form1.sns_reserve1.value,"")) {
				alert("�������� ���ڷθ� �Է��ϼ���.");
				document.form1.sns_reserve1.focus();
				return;
			}
		}
	}
	if (document.form1.sns_reserve2.value.length>0) {
		if(document.form1.sns_reserve2_type.value=="Y") {
			if(isDigitSpecial(document.form1.sns_reserve2.value,".")) {
				alert("�������� ���ڿ� Ư������ �Ҽ���\(.\)���θ� �Է��ϼ���.");
				document.form1.sns_reserve2.focus();
				return;
			}

			if(getSplitCount(document.form1.sns_reserve2.value,".")>2) {
				alert("������ �Ҽ���\(.\)�� �ѹ��� ��밡���մϴ�.");
				document.form1.sns_reserve2.focus();
				return;
			}

			if(getPointCount(document.form1.sns_reserve2.value,".",2)==true) {
				alert("�������� �Ҽ��� ���� ��°�ڸ������� �Է� �����մϴ�.");
				document.form1.sns_reserve2.focus();
				return;
			}

			if(Number(document.form1.sns_reserve2.value)>100 || Number(document.form1.sns_reserve2.value)<0) {
				alert("�������� 0 ���� ũ�� 100 ���� ���� ���� �Է��� �ּ���.");
				document.form1.sns_reserve2.focus();
				return;
			}
		} else {
			if(isDigitSpecial(document.form1.sns_reserve2.value,"")) {
				alert("�������� ���ڷθ� �Է��ϼ���.");
				document.form1.sns_reserve2.focus();
				return;
			}
		}
	}
<?
}
if($arRecomType[0] =="B" && $arRecomType[1] == "B"){
?>

	if (document.form1.first_reserve.value.length>0) {
		if(document.form1.first_reserve_type.value=="Y") {
			if(isDigitSpecial(document.form1.reserve.value,".")) {
				alert("�������� ���ڿ� Ư������ �Ҽ���\(.\)���θ� �Է��ϼ���.");
				document.form1.first_reserve.focus();
				return;
			}

			if(getSplitCount(document.form1.first_reserve.value,".")>2) {
				alert("������ �Ҽ���\(.\)�� �ѹ��� ��밡���մϴ�.");
				document.form1.first_reserve.focus();
				return;
			}

			if(getPointCount(document.form1.first_reserve.value,".",2)==true) {
				alert("�������� �Ҽ��� ���� ��°�ڸ������� �Է� �����մϴ�.");
				document.form1.first_reserve.focus();
				return;
			}

			if(Number(document.form1.first_reserve.value)>100 || Number(document.form1.first_reserve.value)<0) {
				alert("�������� 0 ���� ũ�� 100 ���� ���� ���� �Է��� �ּ���.");
				document.form1.first_reserve.focus();
				return;
			}
		} else {
			if(isDigitSpecial(document.form1.first_reserve.value,"")) {
				alert("�������� ���ڷθ� �Է��ϼ���.");
				document.form1.first_reserve.focus();
				return;
			}
		}
	}
<?
}
?>
	if(document.form1.use_imgurl.checked!=true) {
		filesize = Number(document.form1.size_checker.fileSize) + Number(document.form1.size_checker2.fileSize) + Number(document.form1.size_checker3.fileSize) ;
		if(filesize><?=$maxfilesize?>) {
			alert('�ø��÷��� �ϴ� ���Ͽ뷮�� 500K�̻��Դϴ�.\n���Ͽ뷮�� üũ�Ͻ��Ŀ� �ٽ� �̹����� �÷��ּ���');
			return;
		}
	}
	tempcontent = document.form1.content.value;
	document.form1.iconvalue.value="";
	num = document.form1.iconnum.value;
	for(i=0;i<num;i++){
		if(document.form1.icon[i].checked==true) document.form1.iconvalue.value+=document.form1.icon[i].value;
	}	
	document.form1.mode.value=mode;
	document.form1.submit();
}

//-->
</SCRIPT>

<?
if($searchtype==2 || $optionover=="YES") {
	echo "<script>document.form1.searchtype[2].checked=true;\nViewLayer('layer2');</script>";
} else if($searchtype==1) {
	echo "<script>document.form1.searchtype[1].checked=true;\nViewLayer('layer1');</script>";
} else if($searchtype==3 && (int)$productinfo['vender']==0) {
	echo "<script>document.form1.searchtype[3].checked=true;\nViewLayer('layer3');</script>";
}
if ($productinfo['sns_state']=="Y") {
	echo "<script>ViewSnsLayer('block');</script>";
} ?>

