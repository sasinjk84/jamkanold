<?
$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$sitecd=$_REQUEST["sitecd"];
$escrow=$_REQUEST["escrow"];
$paymethod=$_REQUEST["paymethod"];
$goodname=$_REQUEST["goodname"];
$price=$_REQUEST["price"];
$ordercode=$_REQUEST["ordercode"];
$hpunittype=$_REQUEST["hpunittype"];
$buyername=$_REQUEST["buyername"];
$buyermail=$_REQUEST["buyermail"];
$buyertel1=$_REQUEST["buyertel1"];
$buyertel2=$_REQUEST["buyertel2"];
$rpost=$_REQUEST["rpost"];
$raddr1=$_REQUEST["raddr1"];
$raddr2=$_REQUEST["raddr2"];
$quotafree=$_REQUEST["quotafree"];
$quotamonth=$_REQUEST["quotamonth"];
$quotaprice=$_REQUEST["quotaprice"];
$sitelogo=$_REQUEST["sitelogo"];
$sitelogoleft=$_REQUEST["sitelogoleft"];
$receivername=$_REQUEST["receivername"];
$receivertel=$_REQUEST["receivertel"];

#### PG ����Ÿ ���� ####
$_ShopInfo->getPgdata();
########################
switch($paymethod) {
	case "C":
		$pay_method="onlycard";
		$pgid_info=GetEscrowType($_data->card_id);
		break;
	case "O":
		$pay_method="onlyvbank";
		$pgid_info=GetEscrowType($_data->virtual_id);
		break;
	case "M":
		$pay_method="onlyhpp";
		$pgid_info=GetEscrowType($_data->mobile_id);
		break;
	case "V":
		$pay_method="onlydbank";
		$pgid_info=GetEscrowType($_data->trans_id);
		break;
	default :
		break;
}

$pg_type = trim($pgid_info["PG"]);
$sitekey = $pgid_info["KEY"];

if($escrow=="Y" || strlen($pay_method)==0) {
	echo "<html><head><title></title></head><body onload=\"alert('���������� �ùٸ��� �ʽ��ϴ�.');history.go(-1);\"></body></html>";exit;
}
if (empty($price) || $price==0) {
	echo "<html><head><title></title></head><body onload=\"alert('�����ݾ��� �����ϴ�.');history.go(-1);\"></body></html>";exit;
}
if (empty($sitecd)) {
	echo "<html><head><title></title></head><body onload=\"alert('�̴Ͻý� ����ID�� �����ϴ�.');history.go(-1);\"></body></html>";exit;
}
if (empty($sitekey)) {
	echo "<html><head><title></title></head><body onload=\"alert('�̴Ͻý� ����KEY�� �����ϴ�.');history.go(-1);\"></body></html>";exit;
}

$quotaopt="";
if($quotafree=="Y" && $quotaprice>=50000) {
	if($price >= $quotaprice) {
		for($i=3; $i<=$quotamonth; $i++) {
			$quotamontharr[] = $i;
		}
		if(count($quotamontharr)>0) {
			$quotaopt=":(ALL-".implode(":",$quotamontharr).")";
			$nointerest = "yes";
		} else {
			$nointerest = "no";
		}
	} else {
		$nointerest = "no";
	}
} else {
	$nointerest = "no";
}

$acceptmethod="";
if($hpunittype == "1" || $hpunittype == "2") {
	$acceptmethod.=":HPP(".$hpunittype.")";
}
?>

<html>
<head>
<title>����</title>
<link href="css/sample.css" rel="stylesheet" type="text/css">
<script language='javascript' src='http://plugin.inicis.com/pay40.js'></script>
<script language='javascript'>
StartSmartUpdate();

function pay(frm) {
	if(frm.clickcontrol.value == "enable") {
		if(document.INIpay == null || document.INIpay.object == null) { // �÷����� ��ġ���� üũ
			alert("\n�̴����� �÷����� 128�� ��ġ���� �ʾҽ��ϴ�. \n\n������ ������ ���Ͽ� �̴����� �÷����� 128�� ��ġ�� �ʿ��մϴ�. \n\n�ٽ� ��ġ�Ͻ÷��� Ctrl + F5Ű�� �����ðų� �޴��� [����/���ΰ�ħ]�� �����Ͽ� �ֽʽÿ�.");
			return;
		} else {
			/******
			 * �÷������� �����ϴ� ���� �����ɼ��� �̰����� ������ �� �ֽ��ϴ�.
			 * (�ڹٽ�ũ��Ʈ�� �̿��� ���� �ɼ�ó��)
			 */

			if(MakePayMessage(frm))
			{
				disable_click();
				frm.submit();
			}
			else
			{
				alert("������ ����ϼ̽��ϴ�.");
				parent.location.href="/front/basket.php";
				return;
			}
		}
	} else {
		return;
	}
}

document.onkeydown = CheckKeyPress;
document.onkeyup = CheckKeyPress;
function CheckKeyPress() {
	ekey = event.keyCode;

	try {
		if(ekey == 38 || ekey == 40 || ekey == 112 || ekey ==17 || ekey == 18 || ekey == 25 || ekey == 122 || ekey == 116) {
			event.keyCode = 0;
			return false;
		}
	} catch (e) {}
}

function enable_click() { document.form1.clickcontrol.value = "enable"; }
function disable_click() { document.form1.clickcontrol.value = "disable"; }
function focus_control() {  }

function PageResize() {
	if(document.all.table_body) {
		var oWidth = document.all.table_body.clientWidth + 10;
		var oHeight = document.all.table_body.clientHeight + 65;

		window.resizeTo(oWidth,oHeight);

		enable_click();
		pay(document.form1);
	}
}

</script>
</head>
<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 onLoad="PageResize();"
 onFocus="javascript:focus_control()">
<?@INCLUDE ("charge.inc.php");?>
<form name=form1 method=post action="charge_result.php">
<input type=hidden name=pay_mod value="<?=$escrow?>">
<input type=hidden name=gopaymethod value="<?=$pay_method?>">
<input type=hidden name=goodname value="<?=$goodname?>">
<input type=hidden name=price value="<?=$price?>">
<input type=hidden name=buyername value="<?=$buyername?>">
<input type=hidden name=buyeremail value="<?=$buyermail?>">
<input type=hidden name=buyertel value="<?=$buyertel1?>">
<input type=hidden name=parentemail value="<?=$buyermail?>">
<input type=hidden name=mid value="<?=$sitecd?>">
<input type=hidden name=currency value='WON'>
<input type=hidden name=oid value="<?=$ordercode?>">
<input type=hidden name=nointerest value="<?=$nointerest?>">
<input type=hidden name=quotabase value="����:�Ͻú�:3����:4����:5����:6����:7����:8����:9����:10����:11����:12����<?=$quotaopt?>">
<input type=hidden name=acceptmethod value="SKIN(ORIGINAL):no_receipt<?=$acceptmethod?>">
<input type=hidden name=ini_logoimage_url value="<?=$sitelogo?>">
<input type=hidden name=ini_menuarea_url value="<?=$sitelogoleft?>">
<input type=hidden name=recvname value="<?=$receivername?>">
<input type=hidden name=recvtel value="<?=$receivertel?>">
<input type=hidden name=recvpostnum value="<?=$rpost?>">
<input type=hidden name=recvaddr value="<?=$raddr1." ".$raddr2?>">
<input type=hidden name=quotainterest value="">
<input type=hidden name=paymethod value="">
<input type=hidden name=cardcode value="">
<input type=hidden name=cardquota value="">
<input type=hidden name=rbankcode value="">
<input type=hidden name=reqsign value="DONE">
<input type=hidden name=encrypted value="">
<input type=hidden name=sessionkey value="">
<input type=hidden name=uid value="">
<input type=hidden name=sid value="">
<input type=hidden name=version value=4000>
<input type=hidden name=clickcontrol value="">
<?@INCLUDE ("chargeform.inc.php");?>
</form>
</body>
</html>