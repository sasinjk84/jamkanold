<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");
include_once($Dir."lib/ext/func.php");
include_once($Dir."lib/ext/order_func.php");
include_once($Dir."lib/ext/coupon_func.php");


if(strlen($_ShopInfo->getMemid())==0) {
	exit;
}

if(true !== checkGroupUseCoupon($groupname)) _alert($groupname.' ȸ�� ����� ���� ����� �Ұ����մϴ�.','0');


if( $_REQUEST['offlinecoupon'] == "popup" ) {
	$onloadOfflinecouponAuthPop = " onload=\"offlinecoupon_auth();\"";
}


//���� ������ ���� ���
if($_REQUEST['mode']=="coupon" && strlen($_REQUEST['coupon_code'])==8){
	$onload = '';
	$sql = "SELECT * FROM tblcouponinfo ";
	$sql.= "WHERE coupon_code = '".$_REQUEST['coupon_code']."'";

	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		if($row->issue_tot_no>0 && $row->issue_tot_no<$row->issue_no+1) {
			$onload="<script>alert(\"��� ������ �߱޵Ǿ����ϴ�.\");</script>";
		} else {
			$date=date("YmdHis");
			if($row->date_start>0) {
				$date_start=$row->date_start;
				$date_end=$row->date_end;
			} else {
				$date_start = substr($date,0,10);
				$date_end = date("Ymd",mktime(0,0,0,substr($date,4,2),substr($date,6,2)+abs($row->date_start),substr($date,0,4)))."23";
			}
			$sql = "INSERT tblcouponissue SET ";
			$sql.= "coupon_code	= '".$_REQUEST['coupon_code']."', ";
			$sql.= "id			= '".$_ShopInfo->getMemid()."', ";
			$sql.= "date_start	= '".$date_start."', ";
			$sql.= "date_end	= '".$date_end."', ";
			$sql.= "date		= '".$date."' ";
			//echo $sql;
			mysql_query($sql,get_db_conn());
			if(!mysql_errno()) {
				$sql = "UPDATE tblcouponinfo SET issue_no = issue_no+1 ";
				$sql.= "WHERE coupon_code = '".$_REQUEST['coupon_code']."'";
				mysql_query($sql,get_db_conn());

				$onload="<script>alert(\"�ش� ���� �߱��� �Ϸ�Ǿ����ϴ�.\\n\\n��ǰ �ֹ��� �ش� ������ ����Ͻ� �� �ֽ��ϴ�.\");</script>";
			} else {
				if($row->repeat_id=="Y") {	//������ ��߱��� �����ϴٸ�,,,,
					$sql = "UPDATE tblcouponissue SET ";
					if($row->date_start<=0) {
						$sql.= "date_start	= '".$date_start."', ";
						$sql.= "date_end	= '".$date_end."', ";
					}
					$sql.= "used		= 'N' ";
					$sql.= "WHERE coupon_code='".$_REQUEST['coupon_code']."' ";
					$sql.= "AND id='".$_ShopInfo->getMemid()."' ";
					//echo $sql;
					mysql_query($sql,get_db_conn());
					$onload="<script>alert(\"�ش� ���� �߱��� �Ϸ�Ǿ����ϴ�.\\n\\n��ǰ �ֹ��� �ش� ������ ����Ͻ� �� �ֽ��ϴ�.\");</script>";
				} else {
					$onload="<script>alert(\"�̹� ������ �߱޹����̽��ϴ�.\\n\\n�ش� ������ ��߱��� �Ұ����մϴ�.\");</script>";
				}
			}
		}
	}
	mysql_free_result($result);

	if(_empty($onload)){
		echo $onload;
	}
	_alert('','/front/couponpop.php?otype='.$$_EQUEST['otype']);
	exit;
}


$productitems = array();
if($_REQUEST['otype'] == 'scheduled'){	
	include_once $Dir.'scheduled_delivery/config.php';
	$basket = getScheduledBasket();
	if(!_empty($basket['msg']) && $basket['msg'] != 'success'){
		_alert($basket['msg'],'0');
		exit;
	}
	foreach($basket['items'] as $idx=>$pd){
		if($pd['cateAuth']['coupon'] != 'Y') continue;
		$basket['items'][$idx]['realprice'] = $pd['sumprice'];
		$productitems[$idx] = &$basket['items'][$idx];
	}
	$basketItems['sumprice'] = $basket['info']['sumprice'];
}else{
	$basketItems = getBasketByArray();
	//echo "<div style=\" height:500px; overflow:scroll;  border:2px solid #ff0000 ;  text-align:left;\">";
	//_pr($basketItems);
	//echo "</div>";
	
	
	foreach($basketItems['vender'] as $vd=>$val){
		foreach($val['products'] as $idx=>$pd){
			if($pd['cateAuth']['coupon'] != 'Y') continue;
			//if(!_array($productitems[$pd['productcode']])) $productitems[$pd['productcode']] = array();
			$productitems[] = &$basketItems['vender'][$vd]['products'][$idx];
		}
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
<title>�������� ��ȸ �� ����</title>

<style>
	table, th, td, caption, div, input, select, textarea{FONT-FAMILY:����,verdana; font-size: 12px;color: #666666;line-height:16px;}
</style>

<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
<script type="text/javascript" src="<?=$Dir?>js/jquery-1.7.2.min.js"></script>

<script type="text/javascript">
<!--
var $j = jQuery.noConflict();

//window.moveTo(10,10);
window.resizeTo(750,600);

var orgSumPrice = parseInt("<?=$basketItems['sumprice']?>");
var totalpay = orgSumPrice;

var coupondata  = '<?=$coupon_json?>';
var coupon_limit = '<?=$_data->coupon_limit_ok?>';

var giftprice		= 0;
var discount		= 0;
var reserve		= 0;
var arrobj			= [];
var bank_only	= "N"; //���� ���� ������ ������ ���õ� ��� ������ ���� �� ������·θ� �����ؾ� �Ѵ�.
var giftUnUsed	= false; //����ǰ �Ұ� ���� ��� ����
var GroupDisUnUsed = false; // ȸ��������� �Ұ� ���� ��� ����

$j(document).ready(function(){

	// �ߺ�������� ����
	$j('.unlimitcouponselect').change(function(){
		calprice();
	});

	// ���ϻ������ ����
	$j('.limitcouponselect').change(function(){
		$this = $j(this);
		$j('.limitcouponselect').each(function(idx,el){
			if($j.trim($j($this).val()) != ''){
				if($j($this).attr('seq') != $j(el).attr('seq')){
					if($j(el).val() ==  $j($this).val()){
						alert('���� ���� ���� ��� �׸��� �ʱ�ȭ �˴ϴ�.');
						$j(el).val('');
					}
				}
			}
		})
		calprice();
	});

	// �ʱ�ȭ ����
	$j(".reset").click(function(){
		document.frm.reset();
		arrobj = [];
		//////////////////////
		$j("#total_discount_txt").html('0');
		$j("#total_payprice").html(number_format(orgSumPrice));
		basketTemp( 'default' );// ��ۺ� �ʱ�ȭ
	});

	$j('#total_sumprice').html(number_format(totalpay));
	$j('#total_payprice').html(number_format(totalpay));
});

// ���ó��
function calprice(){
	// �ʱ�ȭ
	discount = 0;
	reserve =0;
	arrobj = [];
	giftUnUsed = false;
	GroupDisUnUsed = false;
	var deli_price = $j('#default_deli_sumprice_org').val();
	var unUsedGiftcouponList='';
	var unUsedGroupDisCouponList='';

	var basketTempList = ''; // ��ۺ� �� ��� ����Ʈ


	$j("#moreMsg").html(""); //����ǰ �Ұ� ���� �޼���
	var etcapply_gift_temp = ''; // ����ǰ �Ұ� ���� �޼��� ������������Ʈ �ߺ� üũ

	$j("#moreMsg1").html(""); // ȸ��������� �Ұ� ���� �޼���
	var use_point_temp = ''; // ȸ��������� �Ұ� ���� �޼��� ������������Ʈ �ߺ� üũ

	// �������� ����Ʈ
	$j('.unlimitcouponselect option:selected, .limitcouponselect option:selected').each(function(idx,el){
		if($j.trim($j(el).val()) != ''){
			var tmp = dr = dc = 0;
			var seq = $j(el).parent().attr('seq');
			var oripay = parseInt($j("#step3_"+seq+"_price").val()); // ��ǰ ���� ����
			var saletype = parseInt($j(el).attr('sale_type')); // ����/���� Ÿ��
			var salemoney = parseInt($j(el).attr('sale_money')); // ����/���� �ݾ�/%
			var amount_floor = parseInt($j(el).attr('amount_floor')); // �ݾ����� 1:�Ͽ�/2:10��/3:���

			/*
				saletype
				1 : + % : ���� %
				2 : - % :  ���� %
				3 : + �� : ���� ��
				4 : - �� :  ���� ��
			*/

			if(saletype < 3 && salemoney >= 100){
				alert('���� ���� �Դϴ� �����ڿ��� ���� �ϼ���.');
				return false;
			}
			if(saletype < 3){
				// % ����
				po = 0;
				if( !isNaN(amount_floor) && amount_floor > 0 && amount_floor < 4) po += amount_floor;
				tmp = Math.floor(oripay*(salemoney/ 100) / Math.pow(10,po))*Math.pow(10,po);
			}else {
				// �ݾ�
				tmp = salemoney;
			}
			if(saletype%2 == 1){
				dr = tmp; // ����
			}else{
				dc = tmp; // ����
			}

			$j(el).data('dr',dr);
			$j(el).data('dc',dc);
			
			discount += dc; // ������
			reserve += dr; // ������


			//����ǰ �Ұ� ����
			if($j(el).attr('etcapply_gift') == "A"){
				if ( etcapply_gift_temp != $j(el).val() ) {
					etcapply_gift_temp = $j(el).val();
					unUsedGiftcouponList += "["+$j(el).val()+"] ";
				}
				$j("#moreMsg").html("<br><font color='red'>"+unUsedGiftcouponList+" �������� ����ǰ�� ���� �� �����ϴ�.</font>");
				giftUnUsed = true;
			}

			// ȸ��������� �Ұ� ����
			if( $j(el).attr('use_point') == 'A' ) {
				if ( use_point_temp != $j(el).val() ) {
					use_point_temp = $j(el).val();
					unUsedGroupDisCouponList += "["+$j(el).val()+"] ";
				}
				$j("#moreMsg1").html("<br><font color='blue'>"+unUsedGroupDisCouponList+" �������� ������� ������ ���� �� �����ϴ�.</font>");
				GroupDisUnUsed = true;
			}

			$j(el).attr('product',$j("#step3_"+seq+"_product").val()); //��ǰ�ڵ�
			$j(el).attr('opt1',$j("#step3_"+seq+"_product").attr('opt1')); //��ǰ �ɼ� 1 �ε��� �ڵ�
			$j(el).attr('opt2',$j("#step3_"+seq+"_product").attr('opt2')); //��ǰ �ɼ� 2 �ε��� �ڵ�
			$j(el).attr('optidxs',$j("#step3_"+seq+"_product").attr('optidxs')); //��ǰ �ɼ�s �ε��� �ڵ�
			arrobj.push($j(el));

			basketTempList += $j("#step3_"+seq+"_product").val()+"_"+$j("#step3_"+seq+"_product").attr('opt1')+"_"+$j("#step3_"+seq+"_product").attr('opt2')+'_'+$j(el).attr('optidxs')+"|"+dc+"-";
		}
	});

	// ��ۺ� �� ���
	basketTemp( basketTempList );

	// ������ ������ ���� Ŭ ���
	if ( orgSumPrice < discount ) discount = orgSumPrice;

	$j("#basketTempList").val(basketTempList);

	$j("#total_discount_txt").html(number_format(discount));
	$j("#total_reserve_txt").html(number_format(reserve));
	$j("#total_payprice").html(number_format(orgSumPrice - discount));
}


// ���� ���� �ϱ�
function checkCoupon(){
	var couponlist = ""; // ���� ����Ʈ
	var dcpricelist = ""; // ���ξ� ����Ʈ
	var drpricelist = ""; // ������ ����Ʈ
	var couponproduct = ""; // ������� ��ǰ ����Ʈ (�����ڵ�_��ǰ�ڵ�_�ɼ�1idx_�ɼ�2idx)
	var couponBankOnly = ""; // if (���� ���� ������ ������ ���õ� ��� ) Y else N


	$j(arrobj).each(function(idx,el){
		couponlist += "|"+$j(el).val();
		dcpricelist += "|"+$j(el).data('dc');
		drpricelist += "|"+$j(el).data('dr');
		couponproduct += "|"+$j(el).val()+'_'+$j(el).attr('product')+'_'+$j(el).attr('opt1')+'_'+$j(el).attr('opt2')+'_'+$j(el).attr('optidxs');
		if($j(el).attr('bank_only') == "Y") couponBankOnly = "Y";

	});


	opener.document.getElementById("couponlist").value = couponlist;
	opener.document.getElementById("dcpricelist").value = dcpricelist;
	opener.document.getElementById("drpricelist").value = drpricelist;
	opener.document.getElementById("couponproduct").value = couponproduct;
	opener.document.getElementById("couponBankOnly").value = couponBankOnly;

	if(opener.document.getElementById("possible_gift_price_used")) opener.document.getElementById("possible_gift_price_used").value = ( giftUnUsed ) ? "N" : "Y"; // ����ǰ �Ұ� �������
	if(opener.document.getElementById("possible_group_dis_used")) opener.document.getElementById("possible_group_dis_used").value = ( GroupDisUnUsed ) ? "N" : "Y";  // ȸ����� ���� �ߺ� �Ұ� �������
	if(opener.document.getElementById("deliprice")) opener.document.getElementById("deliprice").value = $j('#basketTempReturn').val(); // ��ۺ�

	opener.document.getElementById("coupon_price").value = discount; // ������
	opener.document.getElementById("coupon_reserve").value = reserve; // ������

	if(opener.document.getElementById("basketTempList")) opener.document.getElementById("basketTempList").value = $j("#basketTempList").val(); // ���� ����


	opener.solvPrice();

	window.close();
}

// ���
function cancelCoupon(){
	opener.resetCoupon();
	window.close();
}

// ���� �ٿ�ε�
function issue_coupon(coupon_code,productcode){
	document.couponissueform.mode.value="coupon";
	document.couponissueform.coupon_code.value=coupon_code;
	document.couponissueform.submit();
}



// �������� ���� ���
function offlinecoupon_auth () {
	window.open('/front/offlinecoupon_auth.php?reloadchk=no','OffLineCoupon','width=300,height=200');
}


// ���� ���� ���� ��ۺ� ����
// ex ) basketTemp( '002001000000000003_0_0|5000-002002000000000002_2_3|5000' );
//	��ǰ�ڵ�_�ɼ�1�ε���_�ɼ�2�ε���|���ΰ���-��ǰ�ڵ�_�ɼ�1�ε���_�ɼ�2�ε���|���ΰ���
// "-" : ��ǰ ����Ʈ ���� , "|" : ��ǰŰ|���ΰ��� ����
function basketTemp( code ) {
	if( code == 'default' ) {
		var result = <?=!_empty($basketItems['deli_price'])?$basketItems['deli_price']:0?>;
		$j('#basketTempReturn').val(result);
		$j('#total_deli_price').html(number_format(parseInt(result)));
	} else {
		$j.post(
			"basket.temp.php",
			{ code:code },
			function(result){
				$j('#basketTempReturn').val(result);
				$j('#total_deli_price').html(number_format(parseInt(result)));
				//alert("�� ��۷� : "+result);
				//return result;
			}
		);
	}
}


//-->
</script>

<style>
	.tline {border-right:1px solid #e6e6e6;}
	#total_discount_txt {color:#ff6600;}
	#total_payprice{color:#ff6600; font-size:18px; font-family:verdana; letter-spacing:-0.5pt;}
</style>
</head>

<body topmargin="0" leftmargin="0" <?=$onloadOfflinecouponAuthPop?>>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td background="/images/common/order/T01/coupon_skin_bg.gif"><img src="/images/common/order/T01/coupon_skin_title.gif"></td>
	</tr>
</table>
<form name="frm" action="">
<table border="0" cellpadding="0" cellspacing="0" width="94%" align="center">
	<tr><td><img src="/images/common/order/T01/coupon_sstitle02.gif"></td></tr>
	<tr>
		<td style="font-size:11px; letter-spacing:-1px; padding-bottom:15px;">
			���밡���� �ش������� �����ϰ� �����ϱ⸦ �����ø� ������ �����Ͽ� ��ǰ���Ű� �����մϴ�.<br />
			<!-- <span style="color:#ff6600">�ϳ��� ��ǰ�� �ϳ��� ������ ��밡���մϴ�.</span><br /> //-->
			<span style="color:#0066ff;">�ٿ�ε� ������ �ٿ�ε� Ŭ�� �� ����Ͻ� �� �ֽ��ϴ�.<br />[�ߺ�]������ �� ��ǰ �̻� ��� ������ �����Դϴ�.</span><br />
			<span style="color:#ff6600">�������� ������ [<a href="javascript:offlinecoupon_auth();" style="font-weight:bold; color:#ff6600;">����</a>] Ŭ�� �Ͽ� ����� ����Ͻ� �� �ֽ��ϴ�.</span>
		</td>
	</tr>
	<tr><td height="1" bgcolor="#e6e6e6"></td></tr>
	<tr>
		<td>
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<col width="160"></col>
				<col width="15"></col>
				<col width="50"></col>
				<col width="70"></col>
				<col width="70"></col>
				<col width="140"></col>
				<col width=""></col>

				<tr height="40" align="center" style="background-color:#f5f5f5; letter-spacing:-0.5px; font-weight:bold;">
					<td colspan="2" class="tline">��ǰ��/���û���</td>
					<td class="tline">����</td>
					<td class="tline">�Ǹűݾ�</td>
					<td class="tline">��������</td>
					<!--<td class="tline">�հ�</td>-->
					<td class="tline">��������</td>
					<td>�ߺ�����</td>
				</tr>
				<tr><td height="1" bgcolor="#e6e6e6" colspan="7"></td></tr>


<?
	$sumprice =$p_cnt = $reserveprice =0;
	$sumprice -= $usereserve;
	//_pr($productitems);

	$chkcouponcode = array();
	$ablecoupons = array();
	$mycoupons = array();

	foreach($productitems as $idx=>$product){
		//_pr($product);
		$coupons = array();
		$coupons = getMyCouponList($product['productcode']);

		//_pr($coupons);

		$p_cnt = $idx+1;


		$limitcoupons = array();
		$unlimitcoupons = array();

		if( $product['cateAuth']['coupon'] == "Y") {
			if(_array($coupons)){

				foreach($coupons as $coupon){

					//echo $coupon['etcapply_gift'].", ";
					if(!in_array($coupon['coupon_code'],$chkcouponcode)) {
						array_push($chkcouponcode,$coupon['coupon_code']);
						array_push($mycoupons,$coupon); // ���밡�� ���� ����Ʈ
					}

					if($coupon['use_con_type2'] != "N"){
						if($coupon['mini_price'] > 0 && ($coupon['mini_price'] > $product['realprice'] || $product['realprice'] < 100)) continue;
						$coupon['etcapply_gift'] = ($coupon['etcapply_gift'] == "A" && $product['cateAuth']['gift'] == "Y")?'A':'';
					}
					//echo "[".$product['cateAuth']['gift']."], "."(".$coupon['etcapply_gift']."), ";

					if($coupon['vender'] > 0 && $product['vender']  != $coupon['vender']) continue;

					if($coupon['order_limit']=="N") {
						array_push($unlimitcoupons,$coupon);
					} else {
						array_push($limitcoupons,$coupon);
					}
				}
				unset($coupons);
			}
		}
?>
				<tr>
					<td align="center" width="60" height="60" style="padding:5px 0px;"><!--��ǰ�̹���--><img src="<?=$product['tinyimage']['src']?>" width="50" /></td>
					<td width="100" class="tline">
						<!--��ǰ��/�ɼ�-->
						<?=strip_tags($product['productname'])?>
						<font style="font-size:11px; color:#888888;"><?=$product['optvalue']?></font>
						<?=$product['package_str']?>
					</td>
					<td align="center" class="tline"><!--����--><?=$product['quantity']?>��</td>
					<td align="center" class="tline"><!--�ǸŰ���--><b><?=number_format($product['sellprice'])?>��</b></td>
					<td align="center" class="tline" style="font-size:11px;">���� <?=number_format(count($limitcoupons))?>��<br />�ߺ� <?=number_format(count($unlimitcoupons))?>��</td>
					<!--<td align="center" class="tline">-->
						<!--�հ�-->
						<!--<font color="#ff6600"><b><?=number_format($product['realprice'])?>��</b></font>-->
						<input type="hidden" name="step3_<?=$p_cnt?>_price" id="step3_<?=$p_cnt?>_price" value="<?=$product['realprice']?>"/>
						<input type="hidden" name="step3_<?=$p_cnt?>_product" id="step3_<?=$p_cnt?>_product" opt1="<?=$product['opt1_idx']?>" opt2="<?=$product['opt2_idx']?>" optidxs="<?=$product['optidxs']?>" value="<?=$product['productcode']?>"/>
					<!--</td>-->
					<td align="center" class="tline">
						<select name="lim_coupon_<?=$p_cnt?>" class="limitcouponselect" seq="<?=$p_cnt?>">
							<option value="">���ϻ������</option>
							<?
								if(_array($limitcoupons)){
									foreach($limitcoupons as $coupon){
							?>
							<option value="<?=$coupon['coupon_code']?>" sale_type="<?=$coupon['sale_type']?>" sale_money="<?=$coupon['sale_money']?>" amount_floor="<?=$coupon['amount_floor']?>"  discount="" etcapply_gift="<?=$coupon['etcapply_gift']?>" bank_only="<?=$coupon['bank_only']?>" order_limit="<?=$coupon['order_limit']?>" use_point="<?=$coupon['use_point']?>">[<?=$coupon['coupon_code']?>]<?=number_format(intval($coupon['sale_money'])).(($coupon['sale_type']<'3')?'%':'��').((intval($coupon['sale_type'])%2 == 1)?'����':'����')?></option>
							<?
									}
								}
							?>
						</select>
					</td>
					<td align="center">
						<select name="lim_coupon_<?=$p_cnt?>" class="unlimitcouponselect" seq="<?=$p_cnt?>">
							<option value="">�ߺ��������</option>
							<?
								if(_array($unlimitcoupons)){
									foreach($unlimitcoupons as $coupon){
							?>
							<option value="<?=$coupon['coupon_code']?>" sale_type="<?=$coupon['sale_type']?>" sale_money="<?=$coupon['sale_money']?>" amount_floor="<?=$coupon['amount_floor']?>" discount="" etcapply_gift="<?=$coupon['etcapply_gift']?>" bank_only="<?=$coupon['bank_only']?>" order_limit="<?=$coupon['order_limit']?>" use_point="<?=$coupon['use_point']?>">[<?=$coupon['coupon_code']?>]<?=number_format(intval($coupon['sale_money'])).(($coupon['sale_type']<'3')?'%':'��').((intval($coupon['sale_type'])%2 == 1)?'����':'����')?></option>
							<?
									}
								}
							?>
						</select>
					</td>
				</tr>
				<tr><td height="1" bgcolor="#e6e6e6" colspan="7"></td></tr>
				<?
					$downcoupons = ableCouponOnProduct($product['productcode'],0,true);
					$newcode = array();


					for($qq=count($downcoupons) -1;$qq >=0;$qq--){
						if(in_array($downcoupons[$qq]['coupon_code'],$chkcouponcode)) unset($downcoupons[$qq]);
						else array_push($newcode,$downcoupons[$qq]['coupon_code']);
					}

					$chkcouponcode = array_merge($chkcouponcode,$newcode);
					$ablecoupons = array_merge($ablecoupons,$downcoupons);
					unset($downcoupons,$newcode);
	}
?>
			</table>
		</td>
	</tr>
	<tr>
		<td align="right" style="padding:10px 22px;">
			<table border="0" cellpadding="0" cellspacing="0">
				<input type="hidden" name="step3_orgprice" id="step3_orgprice" value="<?=$sumprice?>"/>
				<input type="hidden" name="step3_discount" id="step3_discount" value="0"/>
				<input type="hidden" name="total_discount" value="0" />
				<tr>
					<td colspan="5" style="color:#999;">
						�������� �ݾ� : <span id="total_reserve_txt" style=" font-weight:bold">0</span>��
						/
						��ۺ� : <b><span id="total_deli_price"><?=number_format($basketItems['deli_price'])?></span></b>��
						/
						��ǰ�ݾ� �հ� : <b><span id="total_sumprice"><?=number_format($sumprice)?></span></b>��
						/
						<span style="color:#222;"><b>���� �������� :</span> <span id="total_discount_txt">0</span><span style="color:#222;">��</span></b>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="1" bgcolor="#e6e6e6"></td></tr>
	<tr>
		<td align="right">
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td align="right" style="font-size:11px; color:#999; padding-top:10px;">
						���������� ��ǰ ��ۿϷ� �� �����Ǵ� �����Դϴ�.<span id="moreMsg"></span><span id="moreMsg1"></span>
					</td>
				</tr>
				<tr>
					<td style="color:#222; padding:10px 0px; font-weight:bold;">�������� �� �� ���� �ݾ� : <span id="total_payprice"><?=number_format($sumprice)?></span>��</font>(��ۺ� ����)</td>
				</tr>
			</table>
			<!-- �������� ���ε� ���� -->
			<input type="hidden" name="basketTempReturn" id="basketTempReturn" value="<?=$basketItems['deli_price']?>">
			<input type="hidden" name="default_deli_sumprice_org" id="default_deli_sumprice_org" value="<?=$basketItems['deli_price']?>">
		</td>
	</tr>
	<tr><td height="1" bgcolor="#e6e6e6"></td></tr>
	<tr>
		<td align="center" style="padding-top:15px;">
			<table border="0" cellpadding="0" cellspacing="0">

				<tr>
					<td><a href="#btn_sc" onClick="checkCoupon();return false;"><img src="/images/common/order/T01/btn_send.gif" alt="�����ϱ�" border="0" /></a></td>
					<td><img src="/images/common/order/T01/btn_reset.gif" alt="�ʱ�ȭ" style="cursor:hand" class="reset"></td>
					<td><a href="#btn_sc" onClick="cancelCoupon();return false;"><img src="/images/common/order/T01/btn_cancel.gif" border="0" alt="����ϱ�" /></a></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<?
//$mycoupons = getMyCouponList();

//_pr($mycoupons);

if(_array($ablecoupons) || _array($mycoupons)){ ?>
<style>
	.couponList {border:1px solid #e6e6e6; margin:0px 5px; border-left:hidden; border-right:hidden;}
	.couponList caption {overflow:hidden; margin-left:-9999px; width:1px; height:0px; font-size:0px; line-height:0px;}
	.couponList th {border-bottom:1px solid #e6e6e6; border-bottom:1px solid #e6e6e6; height:40px; font-size:12px; background-color:#f5f5f5; letter-spacing:-0.5px;}
	.couponList td {padding:3px 0px; font-size:11px;border-bottom:1px solid #e6e6e6;}
	.couponList td.endsell {border-bottom:0}
</style>

<table border="0" cellpadding="0" cellspacing="0" align="center" width="94%">
	<tr><td height="40"></td></tr>
	<tr><td><img src="/images/common/order/T01/coupon_sstitle03.gif"></td></tr>
	<tr>
		<td style="font-size:11px; letter-spacing:-1px; padding-bottom:4px;">
			<span style="color:#0066ff;">[�ߺ�]������ �� ��ǰ �̻� ��� ������ �����Դϴ�.</span>
		</td>
	</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" class="couponList" align="center" width="94%">
	<caption>���밡�� ���� ���</caption>
	<col width="75"></col>
	<col width="100"></col>
	<col width="90"></col>
	<col width=""></col>
	<col width="90"></col>
	<col width="90"></col>
	<tr>
		<th class="tline">�����ڵ�</th>
		<th class="tline">������</th>
		<th class="tline">����/����</th>
		<th class="tline">������/ī�װ�</th>
		<th class="tline">�������</th>
		<th>��ȿ�Ⱓ</th>
	</tr>
<?	if(_array($mycoupons)){
		foreach($mycoupons as $idx=>$coupon){
			$range = ($coupon['date_start']>0)?substr($coupon['date_start'],0,4)."/".substr($coupon['date_start'],4,2)."/".substr($coupon['date_start'],6,2)." ~ ".substr($coupon['date_end'],0,4)."/".substr($coupon['date_end'],4,2)."/".substr($coupon['date_end'],6,2):date("Y/m/d")." ~ ".date("Y/m/d",mktime(0,0,0,date("m"),date("d")+abs($coupon['date_start']),date("Y")));
			$coupon_desc = number_format($coupon['sale_money']).($coupon['sale_type']<=2?"%":"��").($coupon['sale_type']%2==0?"����":"����");
			$limit = (_isInt($coupon['mini_price']))?number_format($coupon['mini_price']).'�� �̻�<br />���Ž�':'&nbsp;';

			$productList = usableProductOnCoupon($coupon['productcode']);


			$target = '		������ : ';
			if($coupon['vender'] > 0) $target .= '[������ : '.$coupon['venderid'].' ����]';
			if($coupon['use_con_type2']=="N") $target .='['.$productList.'] ����';
			else $target .= $productList;

			if($coupon['order_limit']=='N') {
				$coupon_order_limit_img = "order_unlimit.gif";
				$coupon_order_limit_alt = "�ߺ���������";
				$target .= "<img src=\"/images/common/order/".$coupon_order_limit_img."\" alt=\"".$coupon_order_limit_alt."\">";
			}

			/*
			else {
				$coupon_order_limit_img = "order_limit.gif";
				$coupon_order_limit_alt = "������������";
			}
			*/



			$addclass = ($idx == count($mycoupons)-1)?' class="endsell"':'';
?>
	<tr>
		<td class="tline" align="center" <?=$addclass?>><? if(strlen($coupon['coupon_code'])>0){ ?><b><?=$coupon['coupon_code']?></b><?}else{?>&nbsp;<?}?></td>
		<td class="tline" style="padding-left:6px;" <?=$addclass?>><?=$coupon['coupon_name']?></td>
		<td class="tline" align="center" <?=$addclass?>><?=$coupon_desc?></td>
		<td class="tline" style="padding-left:6px;" <?=$addclass?>><?=$target?></td>
		<td class="tline" style="padding-left:6px;" <?=$addclass?>><?=$limit?></td>
		<td align="center" <?=$addclass?>><?=$range?></td>
	</tr>
<?		}
	}
?>

<?	if(_array($ablecoupons)){
		foreach($ablecoupons as $idx=>$coupon){
			$range = ($coupon['date_start']>0)?substr($coupon['date_start'],0,4)."/".substr($coupon['date_start'],4,2)."/".substr($coupon['date_start'],6,2)." ~ ".substr($coupon['date_end'],0,4)."/".substr($coupon['date_end'],4,2)."/".substr($coupon['date_end'],6,2):date("Y/m/d")." ~ ".date("Y/m/d",mktime(0,0,0,date("m"),date("d")+abs($coupon['date_start']),date("Y")));
			$coupon_desc = number_format($coupon['sale_money']).($coupon['sale_type']<=2?"%":"��").($coupon['sale_type']%2==0?"����":"����");
			$limit = (_isInt($coupon['mini_price']))?number_format($coupon['mini_price']).'�� �̻�<br />���Ž�':'&nbsp;';

			$productList = usableProductOnCoupon($coupon['productcode']);
			$target = '		������ : ';
			if($coupon['vender'] > 0) $target .= '[������ : '.$coupon['venderid'].' ����]';
			if($coupon['use_con_type2']=="N") $target.'['.$productList.'] ����';
			else $target .= $productList;

			if($coupon['order_limit']=='N') {
				$coupon_order_limit_img = "order_unlimit.gif";
				$coupon_order_limit_alt = "�ߺ���������";
				$target .= "<img src=\"/images/common/order/".$coupon_order_limit_img."\" alt=\"".$coupon_order_limit_alt."\">";
			}

			/*
			else {
				$coupon_order_limit_img = "order_limit.gif";
				$coupon_order_limit_alt = "������������";
			}
			*/

			$addclass = ($idx == count($mycoupons)-1)?' class="endsell"':'';
?>
	<tr>
		<td class="tline" align="center" <?=$addclass?>><? if(strlen($coupon['coupon_code'])>0){ ?><b><?=$coupon['coupon_code']?></b><?}else{?>&nbsp;<?}?></td>
		<td class="tline" style="padding-left:6px;"><?=$coupon['coupon_name']?></td>
		<td class="tline" align="center" <?=$addclass?>><?=$coupon_desc?><br /><a href="javascript:issue_coupon('<?=$coupon['coupon_code']?>')"><img src="/images/common/icon_download.gif" border="0" alt="�����ٿ�" /></a></td>
		<td class="tline" style="padding-left:6px;"><?=$target?></td>
		<td class="tline" style="padding-left:6px;"><?=$limit?></td>
		<td align="center" <?=$addclass?>><?=$range?></td>
	</tr>
<?		}
	}
?>
</table>
<div style="margin:0 auto; margin-top:20px; text-align:center;"><a href="javascript:window.close();"><img src="/images/common/bigview_btnclose.gif" border="0" alt="" /></a></div>
<br />

<? } ?>
</form>
<form name=couponissueform method=get action="<?=$_SERVER[PHP_SELF]?>">
<input type=hidden name=mode value="">
<input type=hidden name=coupon_code value="">
</form>

<input type="hidden" name="basketTempList" id="basketTempList" value="">

<script language="javascript" type="text/javascript">
//orgSumPrice = parseInt('<?=$sumprice?>');
</script>
</body>
</html>