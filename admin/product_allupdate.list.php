<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "pr-4";
$MenuCode = "nomenu";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

//����Ʈ ����
$setup[page_num] = 10;
//$setup[list_num] = 10;
if(preg_match('/^[0-9]+$/',$_REQUEST['list_num'])) $setup['list_num'] = $_REQUEST['list_num'];
else $setup['list_num'] = 10;

if($setup['list_num'] < 5) $setup['list_num'] = 10;
else if($setup['list_num'] >100) $setup['list_num'] = 100;

$sort=$_REQUEST["sort"];
$block=$_REQUEST["block"];
$gotopage=$_REQUEST["gotopage"];

if ($block != "") {
	$nowblock = $block;
	$curpage  = $block * $setup[page_num] + $gotopage;
} else {
	$nowblock = 0;
}

if (($gotopage == "") || ($gotopage == 0)) {
	$gotopage = 1;
}
////////////////////////

$mode=$_POST["mode"];
$code=$_POST["code"];
$keyword=$_POST["keyword"];
$searchtype=$_POST["searchtype"];
if(strlen($searchtype)==0) $searchtype=0;

$aproductcode=(array)$_POST["aproductcode"];

$aassembleproduct=(array)$_POST["aassembleproduct"];
$aassembleuse=(array)$_POST["aassembleuse"];

$aproductname=(array)$_POST["aproductname"];

$aproductname2=(array)$_POST["aproductname2"];
$aproduction=(array)$_POST["aproduction"];
$aproduction2=(array)$_POST["aproduction2"];
$aconsumerprice=(array)$_POST["aconsumerprice"];
$aconsumerprice2=(array)$_POST["aconsumerprice2"];
$abuyprice=(array)$_POST["abuyprice"];
$abuyprice2=(array)$_POST["abuyprice2"];
$asellprice=(array)$_POST["asellprice"];
$asellprice2=(array)$_POST["asellprice2"];
$areserve=(array)$_POST["areserve"];
$areserve2=(array)$_POST["areserve2"];
$areservetype=(array)$_POST["areservetype"];
$areservetype2=(array)$_POST["areservetype2"];
$aquantity=(array)$_POST["aquantity"];
$aquantity2=(array)$_POST["aquantity2"];
$adisplay=(array)$_POST["adisplay"];
$adisplay2=(array)$_POST["adisplay2"];

$productdisprice=(array)$_POST["productdisprice"];
$productdisprice2=(array)$_POST["productdisprice2"];

$dicker_text=(array)$_POST["dicker_text"];
$dicker_text2=(array)$_POST["dicker_text2"];

$etcapply_coupon=(array)$_POST["etcapply_coupon"];
$etcapply_coupon2=(array)$_POST["etcapply_coupon2"];
$etcapply_reserve=(array)$_POST["etcapply_reserve"];
$etcapply_reserve2=(array)$_POST["etcapply_reserve2"];
$etcapply_gift=(array)$_POST["etcapply_gift"];
$etcapply_gift2=(array)$_POST["etcapply_gift2"];
$etcapply_return=(array)$_POST["etcapply_return"];
$etcapply_return2=(array)$_POST["etcapply_return2"];




################################��ǰ�� ȸ��������######################################
$prcode=$_POST["prcode"];
$group_code=(array)$_POST["group_code"];
$discount=(array)$_POST["discount"];
$discount_type=(array)$_POST["discount_type"];

if($mode=="discountprd"){
	for($i=0;$i<count($group_code[$_POST["cnt"]]);$i++) {
		$discountval = 0;
		if($discount[$_POST["cnt"]][$i] > 0){
			if($discount_type[$_POST["cnt"]][$i] != '100'){
				$discountval = intval($discount[$_POST["cnt"]][$i]);
			}else if($discount_type[$_POST["cnt"]][$i] == '100' && intval($discount[$_POST["cnt"]][$i]) < 100){
				$discountval = floatval($discount[$_POST["cnt"]][$i]/100);
			}
		}
		
		$sql = "insert into tblmemberdiscount (group_code,productcode,discount) values ('".$group_code[$_POST["cnt"]][$i]."','".$prcode."','".$discountval."') ON DUPLICATE KEY UPDATE discount = values(discount)";
		mysql_query($sql,get_db_conn());				
	}	
	$onload="<script>alert('��ǰ�� ȸ�������� �����Ͽ����ϴ�.');</script>";
}
################################��ǰ�� ȸ��������######################################




if ($mode=="update" && count($aproductcode)>0) {


	$movecount=0;
	$update_ymd = date("YmdH");
	$update_ymd2 = date("is");
	$displist=array();
	for($i=0;$i<count($aproductcode);$i++) {
		if (strlen($aproductcode[$i])>0 && ($aproductname[$i]!=$aproductname2[$i] || $aproduction[$i]!=$aproduction2[$i] || $aconsumerprice[$i]!=$aconsumerprice2[$i] || $abuyprice[$i]!=$abuyprice2[$i] || $asellprice[$i]!=$asellprice2[$i] || $areserve[$i]!=$areserve2[$i] || $areservetype[$i]!=$areservetype2[$i] || $aquantity[$i]!=$aquantity2[$i] || $adisplay[$i]!=$adisplay2[$i] || $productdisprice[$i]!=$productdisprice2[$i] || $dicker_text[$i]!=$dicker_text2[$i] || $etcapply_coupon[$i]!=$etcapply_coupon2[$i] || $etcapply_reserve[$i]!=$etcapply_reserve2[$i] || $etcapply_gift[$i]!=$etcapply_gift2[$i] || $etcapply_return[$i]!=$etcapply_return2[$i]) && strlen($asellprice[$i])>0 && strlen($areserve[$i])>0 && strlen($aproductname[$i])>0) {
			if (ereg("([0-9]{".strlen($asellprice[$i])."})",$asellprice[$i]) && ereg("([0-9.]{".strlen($areserve[$i])."})",$areserve[$i])) {   #�������� �˻�
				if (strlen($aquantity[$i])==0) $quantity="NULL";
				else if (ereg("([0-9]{".strlen($aquantity[$i])."})",$aquantity[$i]))
				$quantity = $aquantity[$i];
				if (strlen($abuyprice[$i])==0) $abuyprice[$i]="0";
				if (strlen($areserve[$i])==0) {
					$areserve[$i]=0;
				} else {
					$areserve[$i]=$areserve[$i]*1;
				}
				if($areservetype[$i]!="Y") {
					$areservetype[$i]="N";
				}

				$productname = ereg_replace("\\\\'","''",$aproductname[$i]);
				$production = ereg_replace("\\\\'","''",$aproduction[$i]);



				$sql = "UPDATE tblproduct SET ";
				$sql.= "productname			= '".$productname."', ";
				$sql.= "sellprice					= ".$asellprice[$i].", ";
				$sql.= "consumerprice			= ".$aconsumerprice[$i].", ";
				$sql.= "buyprice					= ".$abuyprice[$i].", ";
				$sql.= "reserve					= '".$areserve[$i]."', ";
				$sql.= "reservetype				= '".$areservetype[$i]."', ";
				$sql.= "production				= '".$production."', ";
				if(preg_match('/^[0-9]+$/',$productdisprice[$i])) $sql.= "productdisprice		= ".$productdisprice[$i].", ";
				$sql.= "etcapply_coupon		= '".$etcapply_coupon[$i]."', ";
				$sql.= "etcapply_reserve		= '".$etcapply_reserve[$i]."', ";
				$sql.= "etcapply_gift			= '".$etcapply_gift[$i]."', ";
				$sql.= "etcapply_return		= '".$etcapply_return[$i]."', ";
				//$sql.= "etctype					= '".$etctype."', ";
				$sql.= "quantity					= ".$quantity.", ";
				$sql.= "display						= '".$adisplay[$i]."' ";
				$sql.= "WHERE productcode='".$aproductcode[$i]."' ";

				if(!mysql_query($sql,get_db_conn())) echo mysql_error();
				if($dicker_text[$i]!=$dicker_text2[$i]) {
						$etctype_ori    = "DICKER=".$dicker_text2[$i]."";
						$etctype_new = "DICKER=".$dicker_text[$i]."";
						if(strlen($dicker_text2[$i])>0 ){
								if(strlen($dicker_text[$i])<1 ){
									$sqldicker = "UPDATE tblproduct SET etctype = REPLACE(etctype,'".$etctype_ori."','' ) WHERE productcode='".$aproductcode[$i]."' ";
								}else{
									$sqldicker = "UPDATE tblproduct SET etctype = REPLACE(etctype,'".$etctype_ori."','".$etctype_new."' ) WHERE productcode='".$aproductcode[$i]."' ";
								}
						}else{
							$sqldicker = "UPDATE tblproduct SET etctype =  CONCAT(etctype, '".$etctype_new."')  WHERE productcode='".$aproductcode[$i]."' ";
						}
						mysql_query($sqldicker,get_db_conn());
				}

				if($asellprice[$i]!=$asellprice2[$i] && $aassembleuse[$i]!="Y") {
					if(strlen($aassembleproduct[$i])>0) {
						$sql = "SELECT productcode, assemble_pridx FROM tblassembleproduct ";
						$sql.= "WHERE productcode IN ('".str_replace(",","','",$aassembleproduct[$i])."') ";
						$result = mysql_query($sql,get_db_conn());
						while($row = @mysql_fetch_object($result)) {
							$sql = "SELECT SUM(sellprice) as sumprice FROM tblproduct ";
							$sql.= "WHERE pridx IN ('".str_replace("","','",$row->assemble_pridx)."') ";
							$sql.= "AND display ='Y' ";
							$sql.= "AND assembleuse!='Y' ";
							$result2 = mysql_query($sql,get_db_conn());
							if($row2 = @mysql_fetch_object($result2)) {
								$sql = "UPDATE tblproduct SET sellprice='".$row2->sumprice."' ";
								$sql.= "WHERE productcode = '".$row->productcode."' ";
								$sql.= "AND assembleuse='Y' ";
								mysql_query($sql,get_db_conn());
							}
							mysql_free_result($result2);
						}
					}
				}

				if($adisplay[$i]!=$adisplay2[$i]) {
					$displist[]=$aproductcode[$i];
				}

				$movecount++;

				$update_date = $update_ymd.$update_ymd2;
				$log_content = "## ��ǰ�ϰ����� ## - ��ǰ�ڵ�: ".$aproductcode[$i]." ����: ".$asellprice[$i]." �Һ��ڰ� : ".$aconsumerprice[$i]."  ���԰� : ".$abuyprice." ����: ".$adisplay[$i]." ����: $quantity ������ : ".$areserve[$i];
				ShopManagerLog($_ShopInfo->getId(),$connect_ip,$log_content,$update_date);
				$update_ymd2++;
			}
		}
	}

	//���� ������Ʈ �迭 Ȯ�� �� ������ü ��ǰ�� ������Ʈ
	$prcodelist="";
	for($i=0;$i<count($displist);$i++) {
		if($i>0) $prcodelist.=",";
		$prcodelist.=$displist[$i];
	}
	if(strlen($prcodelist)>0) {
		$prcodelist = ereg_replace(",","','",$prcodelist);

		$arrvender=array();
		$sql = "SELECT vender FROM tblproduct WHERE productcode IN ('".$prcodelist."') AND vender>0 ";
		$sql.= "GROUP BY vender ";
		$p_result=mysql_query($sql,get_db_conn());
		while($p_row=mysql_fetch_object($p_result)) {
			$arrvender[]=$p_row->vender;
		}
		mysql_free_result($p_result);

		for($yy=0;$yy<count($arrvender);$yy++) {
			//�̴ϼ� ��ǰ�� ������Ʈ (������ ��ǰ��)
			$sql = "SELECT COUNT(*) as prdt_allcnt, COUNT(IF(display='Y',1,NULL)) as prdt_cnt FROM tblproduct ";
			$sql.= "WHERE vender='".$arrvender[$yy]."' ";
			$result=mysql_query($sql,get_db_conn());
			$row=mysql_fetch_object($result);
			$prdt_allcnt=(int)$row->prdt_allcnt;
			$prdt_cnt=(int)$row->prdt_cnt;
			mysql_free_result($result);

			setVenderCountUpdate($prdt_allcnt, $prdt_cnt, $arrvender[$yy]);
		}
	}

	if ($movecount!=0) {
		$onload="<script>alert('".$movecount." ���� ��ǰ������ �����Ǿ����ϴ�.');</script>";
	}
}

$sql = "SELECT vendercnt FROM tblshopcount ";
$result=mysql_query($sql,get_db_conn());
$row=mysql_fetch_object($result);
$vendercnt=$row->vendercnt;
mysql_free_result($result);

if($vendercnt>0){
	$venderlist=array();
	$sql = "SELECT vender,id,com_name,delflag FROM tblvenderinfo ORDER BY id ASC ";
	$result=mysql_query($sql,get_db_conn());
	while($row=mysql_fetch_object($result)) {
		$venderlist[$row->vender]=$row;
	}
	mysql_free_result($result);
}

$imagepath=$Dir.DataDir."shopimages/product/";
?>

<? INCLUDE "header.php"; ?>
<style>td {line-height:18pt;}</style>
<script type="text/javascript" src="lib.js.php"></script>
<script language="javascript" type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
<script> var $j = jQuery.noConflict();</script>

<script>var LH = new LH_create();</script>
<script for=window event=onload>LH.exec();</script>
<script>//LH.add("parent_resizeIframe('ListFrame')");</script>

<script language="JavaScript">
<? if($vendercnt>0){?>
function viewVenderInfo(vender) {
	window.open("about:blank","vender_infopop","width=100,height=100,scrollbars=yes");
	document.vForm.vender.value=vender;
	document.vForm.target="vender_infopop";
	document.vForm.submit();
}
<?}?>

function CheckForm() {
	try {
		if (typeof(document.form1["aproductcode[]"])!="object") {
			alert("������ ��ǰ�� �������� �ʽ��ϴ�.");
			return;
		}

		var i=0;
		while(true) {
			if(document.getElementById("areserve"+i) && document.getElementById("areservetype"+i)) {
				if (document.getElementById("areserve"+i).value.length>0) {
					if(document.getElementById("areservetype"+i).value=="Y") {
						if(isDigitSpecial(document.getElementById("areserve"+i).value,".")) {
							alert("�������� ���ڿ� Ư������ �Ҽ���\(.\)���θ� �Է��ϼ���.");
							document.getElementById("areserve"+i).focus();
							return;
						}

						if(getSplitCount(document.getElementById("areserve"+i).value,".")>2) {
							alert("������ �Ҽ���\(.\)�� �ѹ��� ��밡���մϴ�.");
							document.getElementById("areserve"+i).focus();
							return;
						}

						if(getPointCount(document.getElementById("areserve"+i).value,".",2)==true) {
							alert("�������� �Ҽ��� ���� ��°�ڸ������� �Է� �����մϴ�.");
							document.getElementById("areserve"+i).focus();
							return;
						}

						if(Number(document.getElementById("areserve"+i).value)>100 || Number(document.getElementById("areserve"+i).value)<0) {
							alert("�������� 0 ���� ũ�� 100 ���� ���� ���� �Է��� �ּ���.");
							document.getElementById("areserve"+i).focus();
							return;
						}
					} else {
						if(isDigitSpecial(document.getElementById("areserve"+i).value,"")) {
							alert("�������� ���ڷθ� �Է��ϼ���.");
							document.getElementById("areserve"+i).focus();
							return;
						}
					}
				}
				i++;
			} else {
				break;
			}
		}
	} catch (e) {
		return;
	}
	if(confirm("��ǰ������ ���� �Ͻðڽ��ϱ�?")) {
		document.form1.mode.value="update";
		document.form1.submit();
	}
}

function ProductMouseOver(Obj) {
	obj = event.srcElement;
	WinObj=document.getElementById(Obj);
	obj._tid = setTimeout("ProductViewImage(WinObj)",200);
}
function ProductViewImage(WinObj) {
	WinObj.style.display = "";

	if(!WinObj.height)
		WinObj.height = WinObj.offsetTop;

	WinObjPY = WinObj.offsetParent.offsetHeight;
	WinObjST = WinObj.height-WinObj.offsetParent.scrollTop;
	WinObjSY = WinObjST+WinObj.offsetHeight;

	if(WinObjPY < WinObjSY)
		WinObj.style.top = WinObj.offsetParent.scrollTop-WinObj.offsetHeight+WinObjPY;
	else if(WinObjST < 0)
		WinObj.style.top = WinObj.offsetParent.scrollTop;
	else
		WinObj.style.top = WinObj.height;
}
function ProductMouseOut(Obj) {
	obj = event.srcElement;
	WinObj = document.getElementById(Obj);
	WinObj.style.display = "none";
	clearTimeout(obj._tid);
}

function GoPage(block,gotopage,sort) {
	document.form1.mode.value = "";
	document.form1.sort.value = sort;
	document.form1.block.value = block;
	document.form1.gotopage.value = gotopage;
	document.form1.submit();
}

function GoSort(sort) {
	document.form1.mode.value = "";
	document.form1.sort.value = sort;
	document.form1.block.value = "";
	document.form1.gotopage.value = "";
	document.form1.submit();
}

function chkFieldMaxLenFunc(thisId,reserveTypeID) {
	if(document.getElementById(reserveTypeID)) {
		if (document.getElementById(reserveTypeID).value=="Y") { max=5; addtext="/Ư������(�Ҽ���)";} else { max=6; }

		if(document.getElementById(thisId)) {
			if (document.getElementById(thisId).value.bytes() > max) {
				alert("�Է��� �� �ִ� ��� ������ �ʰ��Ǿ����ϴ�.\n\n" + "����"+addtext+" " + max + "�� �̳��� �Է��� �����մϴ�.");
				document.getElementById(thisId).value = document.getElementById(thisId).value.cut(max);
				document.getElementById(thisId).focus();
			}
		}
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

function DivDefaultReset()
{
	if(!self.id)
	{
		self.id = self.name;
		parent.document.getElementById(self.id).style.height = parent.document.getElementById(self.id).height;
	}
}
DivDefaultReset();


//ȸ������������
var oldPrcode = '';
var oldPbg = '';
function DivMemberDiscount(prcode,pbg) {
	if( oldPrcode != prcode ) {
		if( oldPrcode !='' ) {
			oldPrcode.style.display = 'none';
			oldPbg.style.background = '#ffffff';
		}
		prcode.style.display = 'block';
		pbg.style.background = '#ffcc00';
		oldPrcode = prcode;
		oldPbg = pbg;
	} else {
		prcode.style.display = 'none';
		pbg.style.background = '#ffffff';
		oldPrcode = '';
		oldPbg = '';
	}
}




//��ǰ�� ȸ�������� �ڵ����
function autoCal(gubun,val,obj,sellprice){
	if(gubun=="1"){
		document.getElementById(obj).value = eval(sellprice * (val/100)).toFixed(0);
	}else{
		document.getElementById(obj).value = eval((val/sellprice) *100).toFixed(2);
	}
}


//��ǰ�� ȸ�������� ����
function DiscountPrd(mode,prcode,cnt) {
	document.form1.cnt.value=cnt;
	document.form1.mode.value=mode;
	document.form1.prcode.value=prcode;
	document.form1.submit();
}

</script>

<table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" bgcolor="#FFFFFF">
<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
<input type=hidden name=mode>
<input type=hidden name=prcode>
<input type=hidden name=cnt>
<input type=hidden name=code value="<?=$code?>">
<input type=hidden name=searchtype value="<?=$searchtype?>">
<input type=hidden name=keyword value="<?=$keyword?>">
<input type=hidden name=sort value="<?=$sort?>">
<input type=hidden name=block value="<?=$block?>">
<input type=hidden name=gotopage value="<?=$gotopage?>">
<tr>
	<td width="100%" bgcolor="#FFFFFF"><IMG SRC="images/product_mainlist_text.gif" border="0"></td>
</tr>
<tr>
	<td width="100%" height="100%" valign="top" style="BORDER:#FF8730 2px solid;padding-left:5px;padding-right:5px;">
	<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
	<tr>
		<td width="100%" style="padding-top:2pt; padding-bottom:2pt; height:30px;" height="30">
		<div style="width:300px; height:1px; font-size:0px; line-height:1px;"></div>
		<div style="float:left; width:69%"><B><span class="font_orange">* ���Ĺ�� :</span></B> <A HREF="javascript:GoSort('date');"><B>������</B></a> | <A HREF="javascript:GoSort('price');"><B>���ݼ�</B></a> | <A HREF="javascript:GoSort('productname');"><B>��ǰ���</B></a> | <A HREF="javascript:GoSort('date');"><B>����ϼ�</B></a> | <A HREF="javascript:GoSort('production');"><B>�������</B></a>
		</div>
		<div style="float:right; width:30%; text-align:right">
		��°��� :
		<select name="list_num" onchange="GoPage('',1,'')">
			<option value="10" <? if($setup['list_num'] == 10){ ?> selected="selected" <? } ?>>10</option>
			<option value="20" <? if($setup['list_num'] == 20){ ?> selected="selected" <? } ?>>20</option>
			<option value="30" <? if($setup['list_num'] == 30){ ?> selected="selected" <? } ?>>30</option>
			<option value="50" <? if($setup['list_num'] == 50){ ?> selected="selected" <? } ?>>50</option>
			<option value="70" <? if($setup['list_num'] == 75){ ?> selected="selected" <? } ?>>75</option>
			<option value="100" <? if($setup['list_num'] == 100){ ?> selected="selected" <? } ?>>100</option>
		</select>
		</div>
		</td>
	</tr>
	<tr>
		<td width="100%" valign="top">
		<DIV style="width:100%;height:100%;overflow-x:scroll;">
		<script language="javascript" type="text/javascript">
		$j(function(){
			$j('select[name=allChange_coupon]').change(function(){ if($j.trim($j(this).val()) != '') $j('select[name^=etcapply_coupon]>option[value='+$j(this).val()+']').attr('selected',true);});
			$j('select[name=allChange_reserve]').change(function(){ if($j.trim($j(this).val()) != '') $j('select[name^=etcapply_reserve]>option[value='+$j(this).val()+']').attr('selected',true);});
			$j('select[name=allChange_gift]').change(function(){ if($j.trim($j(this).val()) != '') $j('select[name^=etcapply_gift]>option[value='+$j(this).val()+']').attr('selected',true);});
			$j('select[name=allChange_return]').change(function(){ if($j.trim($j(this).val()) != '') $j('select[name^=etcapply_return]>option[value='+$j(this).val()+']').attr('selected',true);});
			$j('select[name=allChange_adisplay]').change(function(){ if($j.trim($j(this).val()) != '') $j('select[name^=adisplay]>option[value='+$j(this).val()+']').attr('selected',true);});
			});
		</script>
		<table cellpadding="0" cellspacing="0" width="1600" border="0">
		<tr>
			<td width="100%">
			<script language="javascript" type="text/javascript">						
			function checkGroupReserveVal(gid){
				gdiscount = $('#discount'+gid);
				gdiscounttype = $('#discounttype'+gid);
				
				var numpattern=/^[0-9]+$/;
				if(gdiscount){
					gdisval = gdiscount.val();
					if($.trim(gdisval).length > 0 && !numpattern.test(gdisval)){
						alert('���ڸ� �Է����ּ���');
						gdisval = gdisval.replace(/[^0-9]/g,'');
						gdiscount.val(gdisval);
					}
					gdisval = parseInt(gdisval);
					if(gdiscounttype.val()== '100' && gdisval >= 100){
						alert('%�� �ִ� 100���� �Է� �����մϴ�.');
						gdiscount.val(0);
						gdiscount.focus();
					}
				}
			}
			</script>
			<TABLE width="100%" cellSpacing="0" cellPadding="0" border="0">
<?
			$colspan=17;
			if($vendercnt>0) $colspan++;
?>
			<col width=40></col>
			<?if($vendercnt>0){?>
			<col width=60></col>
			<?}?>
			<col width=50></col>
			<col width=250></col>
			<col width=80></col>
			<col width=55></col>
			<col width=55></col>
			<col width=55></col>
			<col width=55></col>
			<col width=100></col>
			<col width=90></col>
			<col width=40></col>
			<col width=100></col>
			<col width=50></col>
			<col width=60></col>
			<col width=60></col>
			<col width=50></col>
			<col width=50></col>
			<TR>
				<TD background="images/table_top_line.gif" colspan="<?=$colspan?>"></TD>
			</TR>
			<TR align="center">
				<TD class="table_cell3" style="font-size:11px;"><b>No</b></TD>
				<?if($vendercnt>0){?>
				<TD class="table_cell1" style="font-size:11px;">������ü</TD>
				<?}?>
				<TD class="table_cell1" style="font-size:11px;" colspan="2">��ǰ��</TD>
				<TD class="table_cell1" style="font-size:11px;">������</TD>
				<TD class="table_cell1" style="font-size:11px;">���߰�</TD>
				<TD class="table_cell1" style="font-size:11px;">���԰�</TD>
				<TD class="table_cell1" style="font-size:11px;">�ǸŰ�</TD>
				<TD class="table_cell1" style="font-size:11px;">���Ű�</TD>
				<TD class="table_cell1" style="font-size:11px;">�ǸŰ���<br>��ü����</TD>
				<TD class="table_cell1" style="font-size:11px;">������(��)</TD>
				<TD class="table_cell1" style="font-size:11px;">����</TD>
				<TD class="table_cell1" style="font-size:11px;"> �������� ����Ұ�<select name="allChange_coupon" style="font-size:8pt;width:100%;"><option value="">-</option><option value="Y">Y</option><option value="N">N</option></select></TD>
				<TD class="table_cell1" style="font-size:11px;">����������Ұ�<select name="allChange_reserve" style="font-size:8pt;width:100%;"><option value="">-</option><option value="Y">Y</option><option value="N">N</option></select></TD>
				<TD class="table_cell1" style="font-size:11px;">���Ż���ǰ����Ұ�<select name="allChange_gift" style="font-size:8pt;width:100%;"><option value="">-</option><option value="Y">Y</option><option value="N">N</option></select></TD>
				<TD class="table_cell1" style="font-size:11px;">��ȯ��ȯ�� �Ұ�<select name="allChange_return" style="font-size:8pt;width:100%;"><option value="">-</option><option value="Y">Y</option><option value="N">N</option></select></TD>
				<TD class="table_cell1" style="font-size:11px;">����<select name="allChange_adisplay" style="font-size:8pt;width:100%;"><option value="">-</option><option value="Y">Y</option><option value="N">N</option></select></TD>
				<TD class="table_cell1" style="font-size:11px;">����</TD>

			</TR>
<?
	if (($searchtype=="0" && strlen($code)==12) || ($searchtype=="1" && strlen($keyword)>2)) {
		$page_numberic_type=1;
		if ($searchtype=="0" && strlen($code)==12) {
			$qry = "AND productcode LIKE '".$code."%' ";
		} else {
			$qry = "AND productname LIKE '%".$keyword."%' ";
		}
		$sql0 = "SELECT COUNT(*) as t_count FROM tblproduct WHERE 1=1 ";
		$sql0.= $qry;
		$result = mysql_query($sql0,get_db_conn());
		$row = mysql_fetch_object($result);
		mysql_free_result($result);
		$t_count = $row->t_count;
		$pagecount = (($t_count - 1) / $setup[list_num]) + 1;

		$sql = "SELECT option_price,productcode,productname,production,sellprice,consumerprice,productdisprice,etctype,etcapply_coupon,etcapply_reserve,etcapply_gift,etcapply_return,";
		$sql.= "buyprice,quantity,reserve,reservetype,addcode,display,vender,tinyimage,assembleuse,assembleproduct ";
		$sql.= "FROM tblproduct WHERE 1=1 ";
		$sql.= $qry." ";
		if ($sort=="price")				$sql.= "ORDER BY sellprice ";
		else if ($sort=="production")	$sql.= "ORDER BY production ";
		else if ($sort=="productname")	$sql.= "ORDER BY productname ";
		else if ($sort=="date")			$sql.= "ORDER BY regdate ";
		else							$sql.= "ORDER BY date DESC ";
		$sql.= "LIMIT " . ($setup[list_num] * ($gotopage - 1)) . ", " . $setup[list_num];
		$result = mysql_query($sql,get_db_conn());
		$cnt=0;
		while($row=mysql_fetch_object($result)) {
			$number = ($t_count-($setup[list_num] * ($gotopage-1))-$cnt);
?>
				<input type="hidden" name="aproductcode[]" value="<?=$row->productcode?>">
				<input type="hidden" name="aassembleproduct[]" value="<?=$row->assembleproduct?>">
				<input type="hidden" name="aassembleuse[]" value="<?=$row->assembleuse?>">
				<tr>
					<TD colspan="<?=$colspan?>" background="images/table_con_line.gif"></TD>
				</tr>
				<tr id="p<?=$row->productcode?>">
					<td align="center" style="font-size:8pt;padding:2"><?=$number?></td>
<?
				if($vendercnt>0) {
					echo "	<td class=\"td_con1\" align=\"center\" style=\"font-size:8pt\"><B>".(strlen($venderlist[$row->vender]->vender)>0?"<a href=\"javascript:viewVenderInfo(".$row->vender.")\">".$venderlist[$row->vender]->id."</a>":"-")."</B></td>\n";
				}
				echo "	<TD class=\"td_con1\">";
				if (strlen($row->tinyimage)>0 && file_exists($imagepath.$row->tinyimage)==true){
					echo "<img src='".$imagepath.$row->tinyimage."' height=40 width=40 border=1 onMouseOver=\"ProductMouseOver('primage".$cnt."')\" onMouseOut=\"ProductMouseOut('primage".$cnt."');\">";
				} else {
					echo "<img src=images/space01.gif onMouseOver=\"ProductMouseOver('primage".$cnt."')\" onMouseOut=\"ProductMouseOut('primage".$cnt."');\">";
				}
				echo "<div id=\"primage".$cnt."\" style=\"position:absolute; z-index:100; display:none;\"><table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"170\">\n";
				echo "		<tr bgcolor=\"#FFFFFF\">\n";
				if (strlen($row->tinyimage)>0 && file_exists($imagepath.$row->tinyimage)==true){
					echo "		<td align=\"center\" width=\"100%\" height=\"150\" style=\"border:#000000 solid 1px;\"><img src=\"".$imagepath.$row->tinyimage."\" border=\"0\"></td>\n";
				} else {
					echo "		<td align=\"center\" width=\"100%\" height=\"150\" style=\"border:#000000 solid 1px;\"><img src=\"".$Dir."images/product_noimg.gif\" border=\"0\"></td>\n";
				}
				echo "		</tr>\n";
				echo "		</table>\n";
				echo "		</div>\n";
				echo "	</td>\n";


				// Ư���ɼǰ��� üũ�Ѵ�.
				$dicker = $dicker_text="";
				if (strlen($row->etctype)>0) {
					$etctemp = explode("",$row->etctype);
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

						/*switch ($etctemp[$i]) {
							case "BANKONLY": $bankonly = "Y";break;
							case "SETQUOTA": $setquota = "Y";break;
						}*/
					}
				}

?>
					<td class="td_con1"><input type=text name="aproductname[]" maxlength=250 value="<?=str_replace("\"","&quot",$row->productname) ?>" style="font-size:8pt;width:100%;" onKeyDown="chkFieldMaxLen(250)" class="input"></td>
					<td class="td_con1"><input type=text name="aproduction[]" maxlength=20 value="<?=str_replace("\"","&quot",$row->production) ?>" style="font-size:8pt;width:100%;" class="input"></td>
					<td class="td_con1"><input type=text name="aconsumerprice[]" maxlength=8 value="<?=$row->consumerprice?>" style="font-size:8pt;width:100%;text-align:right" class="input"></td>
					<td class="td_con1"><input type=text name="abuyprice[]" maxlength=8 value="<?=$row->buyprice?>" style="font-size:8pt;width:100%;text-align:right" class="input"></td>

					<? if($row->assembleuse=="Y") { ?>
					<td class="td_con1" align="right" style="font-size:8pt;"><input type=hidden name="asellprice[]" value="<?=$row->sellprice?>"><?=$row->sellprice?></td>
					<? } else { ?>
					<td class="td_con1"><input type=text name="asellprice[]" maxlength=8 value="<?=$row->sellprice?>" style="font-size:8pt;width:100%;text-align:right" class="input"></td>
					<? } ?>

					<td class="td_con1"><input type=text name="productdisprice[]" maxlength=8 value="<?=$row->productdisprice?>" style="font-size:8pt;width:100%;text-align:right" class="input"></td>
					<td class="td_con1"><input type=text name="dicker_text[]" value="<?=$dicker_text?>" style="font-size:8pt;width:100%;text-align:right" class="input"></td>




					<td class="td_con1"><input type=text name="areserve[]" size=6 maxlength=6 value="<?=$row->reserve?>" style="font-size:8pt;text-align:right" class="input" id="areserve<?=$cnt?>" onKeyUP="chkFieldMaxLenFunc(this.id,'areservetype<?=$cnt?>');"><select name="areservetype[]" style="width:36px;font-size:8pt;margin-left:1px;" id="areservetype<?=$cnt?>" onchange="chkFieldMaxLenFunc('areserve<?=$cnt?>',this.id);"><option value="N"<?=($row->reservetype!="Y"?" selected":"")?>>��<option value="Y"<?=($row->reservetype!="Y"?"":" selected")?>>%</select></td>
					<td class="td_con1"><input type=text name="aquantity[]" maxlength=3 value="<?=$row->quantity?>" style="font-size:8pt;width:100%;text-align:right" class="input"></td>

					<td class="td_con1"><select name="etcapply_coupon[]" style="font-size:8pt;width:100%;"><option value="Y" <? if ($row->etcapply_coupon=="Y") echo "selected" ?>>Y<option value="N" <? if ($row->etcapply_coupon=="N") echo "selected" ?>>N</select></td>
					<td class="td_con1"><select name="etcapply_reserve[]" style="font-size:8pt;width:100%;"><option value="Y" <? if ($row->etcapply_reserve=="Y") echo "selected" ?>>Y<option value="N" <? if ($row->etcapply_reserve=="N") echo "selected" ?>>N</select></td>
					<td class="td_con1"><select name="etcapply_gift[]" style="font-size:8pt;width:100%;"><option value="Y" <? if ($row->etcapply_gift=="Y") echo "selected" ?>>Y<option value="N" <? if ($row->etcapply_gift=="N") echo "selected" ?>>N</select></td>
					<td class="td_con1"><select name="etcapply_return[]" style="font-size:8pt;width:100%;"><option value="Y" <? if ($row->etcapply_return=="Y") echo "selected" ?>>Y<option value="N" <? if ($row->etcapply_return=="N") echo "selected" ?>>N</select></td>

					<td class="td_con1"><select name="adisplay[]" style="font-size:8pt;width:100%;"><option value="Y" <? if ($row->display=="Y") echo "selected" ?>>Y<option value="N" <? if ($row->display=="N") echo "selected" ?>>N</select></td>
					<!--ȸ��������-->
					<td class="td_con1" align="center"><a href="javascript:DivMemberDiscount(m<?=$row->productcode?>,p<?=$row->productcode?>)">����</a>
						<div style="position:relative;"  style="display:none">
							<div style="position:absolute;left:-300px; top:-75px; display:none" id="m<?=$row->productcode?>">
								<table cellspacing="1" cellpadding="0" border="0" style="BORDER:#FF8730 1px solid;padding-left:5px;padding-right:5px;width:300px;background:#ffffff;">
									<?
									$dSql = "SELECT g.group_name,mg.* FROM tblmembergroup g left join tblmemberdiscount mg on (mg.group_code = g.group_code and mg.productcode='".$row->productcode."' )";									
									$gdiscounts = array();
									if(false !== $dres = mysql_query($dSql,get_db_conn())){
										if(mysql_num_rows($dres)){
											while($gditem = mysql_fetch_assoc($dres)) array_push($gdiscounts,$gditem);
										}
									}
									
									if(_array($gdiscounts)){
										foreach($gdiscounts as $gditem){											
											$gdistype = '100';
											$gdiscountval = '';
											if($gditem['discount'] > 0){
												if($gditem['discount'] < 1){
													$gdiscountval = $gditem['discount']*100;
													$gdistype = '100';
												}else{
													$gdiscountval = intval($gditem['discount']);
													$gdistype = '1';
												}
											}
									?>
									<tr>
										<TD ><img src="images/icon_point5.gif" width="8" height="11" border="0">
											<?=$gditem['group_name']?>
											<input type="hidden" name="group_code[<?=$cnt?>][]" value="<?=$gditem['group_code']?>">
										</TD>
										<TD >
											<input name="discount[<?=$cnt?>][]" id="discount<?=$gditem['group_code']?><?=$row->productcode?>" size="10" type="text" class="input" value="<?=(int)$gdiscountval?>" onkeyup="javascript:checkGroupReserveVal('<?=$gditem['group_code']?><?=$row->productcode?>')"style="width:50px; text-align:right; padding-right:5px;">
											<select name="discount_type[<?=$cnt?>][]" id="discounttype<?=$gditem['group_code']?><?=$row->productcode?>" onchange="javascript:checkGroupReserveVal('<?=$gditem['group_code']?><?=$row->productcode?>')">
												<option value="100" <? if($gdistype == '100') echo 'selected="selected"'; ?>>%</option>
												<option value="1" <? if($gdistype == '1') echo 'selected="selected"'; ?>>��</option>
											</select> ����
										</TD>
									</tr>
									<?
										}// end foreach									

									?>
									<tr>
										<td colspan="2" align="center" width="100%">
											<a href="javascript:DiscountPrd('discountprd','<?=$row->productcode?>',<?=$cnt?>);"><img src="images/btn_infoedit.gif" align=absmiddle width="162" height="38" border="0" vspace="5"></a>
										</td>
									</tr>
									<? } ?>
								</table>
							</div>
						</div>				
					
					</td>
					<!--ȸ��������-->
				</tr>

				<input type="hidden" name="aproductname2[]" value="<?=str_replace("\"","&quot",$row->productname)?>">
				<input type="hidden" name="aproduction2[]" value="<?=str_replace("\"","&quot",$row->production)?>">
				<input type="hidden" name="aconsumerprice2[]" value="<?=$row->consumerprice?>">
				<input type="hidden" name="abuyprice2[]" value="<?=$row->buyprice?>">
				<input type="hidden" name="asellprice2[]" value="<?=$row->sellprice?>">
				<input type="hidden" name="areserve2[]" value="<?=$row->reserve?>">
				<input type="hidden" name="areservetype2[]" value="<?=($row->reservetype!="Y"?"N":"Y")?>">
				<input type="hidden" name="aquantity2[]" value="<?=$row->quantity?>">
				<input type="hidden" name="adisplay2[]" value="<?=$row->display?>">

				<input type="hidden" name="productdisprice2[]" value="<?=$row->productdisprice?>">
				<input type="hidden" name="dicker_text2[]" value="<?=$dicker_text?>">

				<input type="hidden" name="etcapply_coupon2[]" value="<?=$row->etcapply_coupon?>">
				<input type="hidden" name="etcapply_reserve2[]" value="<?=$row->etcapply_reserve?>">
				<input type="hidden" name="etcapply_gift2[]" value="<?=$row->etcapply_gift?>">
				<input type="hidden" name="etcapply_return2[]" value="<?=$row->etcapply_return?>">

<?
			$cnt++;
		}
		mysql_free_result($result);
		if ($cnt==0) {
			$page_numberic_type="";
			echo "<tr><TD colspan=\"".$colspan."\" background=\"images/table_con_line.gif\"></TD></tr><tr><td class=lineleft colspan=".$colspan." align=center>�˻��� ��ǰ�� �������� �ʽ��ϴ�.</td></tr>";
		}
	} else {
		echo "<tr><TD colspan=\"".$colspan."\" background=\"images/table_con_line.gif\"></TD></tr><tr><td class=linebottomleft colspan=".$colspan." align=center>��ǰī�װ��� �����ϰų� �˻��� ���ּ���.</td></tr>";
	}
?>
			<TR>
				<TD background="images/table_top_line.gif" colspan="<?=$colspan?>"></TD>
			</TR>
			</TABLE>
			</td>
		</tr>
<?
	if($page_numberic_type) {
		$total_block = intval($pagecount / $setup[page_num]);

		if (($pagecount % $setup[page_num]) > 0) {
			$total_block = $total_block + 1;
		}

		$total_block = $total_block - 1;

		if (ceil($t_count/$setup[list_num]) > 0) {
			// ����	x�� ����ϴ� �κ�-����
			$a_first_block = "";
			if ($nowblock > 0) {
				$a_first_block .= "<a href=\"javascript:GoPage(0,1,'".$sort."');\" onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='ù ������';return true\"><IMG src=\"images/icon_first.gif\" border=0 align=\"absmiddle\"></a>&nbsp;&nbsp;";

				$prev_page_exists = true;
			}

			$a_prev_page = "";
			if ($nowblock > 0) {
				$a_prev_page .= "<a href=\"javascript:GoPage(".($nowblock-1).",".($setup[page_num]*($block-1)+$setup[page_num]).",'".$sort."');\" onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup[page_num]." ������';return true\">[prev]</a>&nbsp;&nbsp;";

				$a_prev_page = $a_first_block.$a_prev_page;
			}

			// �Ϲ� �������� ������ ǥ�úκ�-����

			if (intval($total_block) <> intval($nowblock)) {
				$print_page = "";
				for ($gopage = 1; $gopage <= $setup[page_num]; $gopage++) {
					if ((intval($nowblock*$setup[page_num]) + $gopage) == intval($gotopage)) {
						$print_page .= "<span class=font_orange2><B>[".(intval($nowblock*$setup[page_num]) + $gopage)."]</B></span> ";
					} else {
						$print_page .= "<a href=\"javascript:GoPage(".$nowblock.",".(intval($nowblock*$setup[page_num]) + $gopage).",'".$sort."');\" onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='������ : ".(intval($nowblock*$setup[page_num]) + $gopage)."';return true\">[".(intval($nowblock*$setup[page_num]) + $gopage)."]</a> ";
					}
				}
			} else {
				if (($pagecount % $setup[page_num]) == 0) {
					$lastpage = $setup[page_num];
				} else {
					$lastpage = $pagecount % $setup[page_num];
				}

				for ($gopage = 1; $gopage <= $lastpage; $gopage++) {
					if (intval($nowblock*$setup[page_num]) + $gopage == intval($gotopage)) {
						$print_page .= "<span class=font_orange2><B>[".(intval($nowblock*$setup[page_num]) + $gopage)."]</B></span> ";
					} else {
						$print_page .= "<a href=\"javascript:GoPage(".$nowblock.",".(intval($nowblock*$setup[page_num]) + $gopage).",'".$sort."');\" onMouseOver=\"window.status='������ : ".(intval($nowblock*$setup[page_num]) + $gopage)."';return true\">[".(intval($nowblock*$setup[page_num]) + $gopage)."]</a> ";
					}
				}
			}		// ������ �������� ǥ�úκ�-��


			$a_last_block = "";
			if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
				$last_block = ceil($t_count/($setup[list_num]*$setup[page_num])) - 1;
				$last_gotopage = ceil($t_count/$setup[list_num]);

				$a_last_block .= "&nbsp;&nbsp;<a href=\"javascript:GoPage(".$last_block.",".$last_gotopage.",'".$sort."');\" onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='������ ������';return true\"><IMG src=\"images/icon_last.gif\" border=0 align=\"absmiddle\" width=\"17\" height=\"14\"></a>";

				$next_page_exists = true;
			}

			// ���� 10�� ó���κ�...

			$a_next_page = "";
			if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
				$a_next_page .= "&nbsp;&nbsp;<a href=\"javascript:GoPage(".($nowblock+1).",".($setup[page_num]*($nowblock+1)+1).",'".$sort."');\" onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup[page_num]." ������';return true\">[next]</a>";

				$a_next_page = $a_next_page.$a_last_block;
			}
		} else {
			$print_page = "<B>[1]</B>";
		}
		echo "<tr>\n";
		echo "	<td width=\"100%\" height=\"50\" background=\"images/blueline_bg.gif\" align=\"center\">\n";
		echo "	".$a_div_prev_page.$a_prev_page.$print_page.$a_next_page.$a_div_next_page."\n";
		echo "	</td>\n";
		echo "</tr>\n";
	}
?>
		<tr>
			<td style="padding:10px;BORDER-top:#eeeeee 2px solid;" align="center"><a href="javascript:CheckForm();"><img src="images/botteon_save.gif" border="0"></a></td>
		</tr>
		</table>
		</td>
	</tr>
	</form>

	<?if($vendercnt>0){?>
	<form name=vForm action="vender_infopop.php" method=post>
	<input type=hidden name=vender>
	</form>
	<?}?>

	</table>
	</td>
</tr>
</table>
<?=$onload?>


<script type="text/javascript">
<!--
	parent.autoResize('ListFrame');
//-->
</script>

</body>
</html>