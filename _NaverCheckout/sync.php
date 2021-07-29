<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
require_once dirname(__FILE__).'/naverCheckout.class.php';

//옵션 클래스 2016-09-26 Seul
include_once($Dir."lib/class/option.php");
$naverOptClass = new Option;

$obj = new naverCheckout($ismobile);

$obj->setItesmType($_REQUEST['type']);


$formitem = array();
if($_REQUEST['mode'] == 'order'){
	$obj->setItems($_REQUEST);
	$obj->solvDeli();
	
	$result = $obj->order();	
	if($obj->ismobile){
		$targeturl = 'https://' . $obj->sendHost . '/mobile/customer/order.nhn';		
	}else{
		$targeturl = 'https://' . $obj->sendHost . '/customer/order.nhn';
	}

	$formitem['ORDER_ID'] =$result['resId'];
	$formitem['SHOP_ID'] =$obj->shopId;
	$formitem['TOTAL_PRICE'] =$obj->totalPrice;
}else{	
	$obj->setItems($_REQUEST);
	$obj->solvDeli();
	$result = $obj->wish();
	if($obj->ismobile){
		$targeturl = 'https://'.$obj->checkoutHost.'/mobile/customer/wishList.nhn';
	}else{
		$targeturl = 'https://'.$obj->checkoutHost.'/customer/wishlistPopup.nhn';
	}
	
	$formitem['SHOP_ID'] =$obj->shopId;
	$formitem['ITEM_ID'] =$result['resId'];
}

//_pr($result);

?>
<html>
<HEAD>
<!-- 네이버 공통 상단 처리용 -->
<script language="javascript" type="text/javascript" src="http://wcs.naver.net/wcslog.js"></script>
<script type="text/javascript"> 
if(!wcs_add) var wcs_add = {}; 
wcs_add["wa"] = "<?=$obj->_getCommonId()?>";  
wcs.inflow();
</script>  
</HEAD>
<body>
<? if($result['Code'] == 200){ ?>
<form name="frm" method="get" action="<?=$targeturl?>">
<? foreach($formitem as $key=>$val){ ?>
<input type="hidden" name="<?=$key?>" value="<?=$val?>" />
<? } ?>
</form>
<script type="text/javascript"> 
 // 추가 정보 입력 
 // wcs_do 함수 호출 
 wcs_do(); 
</script> 
<script>
<?	if($obj->ismobile){ ?>

<?		}else{ ?>
	document.frm.target = "_top";
<? } ?>
	document.frm.submit();
</script>
<? }else{ 
	echo $result['msg'];
	} ?>
</body>
</html>