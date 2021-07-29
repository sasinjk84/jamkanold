<script language="JavaScript">
<!--
<?
	$maxq='-1';
	$limquantity = '-1';
	if(isset($_pdata)){
		if(strlen($_pdata->etctype)>0) {
			$etctemp = explode("",$_pdata->etctype);
			for ($i=0;$i<count($etctemp);$i++) {				
				if(substr($etctemp[$i],0,5)=="MAXQ=")      $maxq=substr($etctemp[$i],5);
			}
		}

		if(strlen($_pdata->quantity) && $_pdata->quantity>0) $limquantity =  $_pdata->quantity;
	}				
?>
	var maxq = <?=$maxq?>;
	var limquantity = <?=$limquantity?>;
	function _orderNaverCheckout() {
		var optMust = true;
		var goodsOption = '';
		var goodsQuan = '';

		if(document.form1.quantity.value.length==0 || document.form1.quantity.value==0) {
			alert("주문수량을 입력하세요.");
			document.form1.quantity.focus();
			return;
		}
		if(!IsNumeric(document.form1.quantity.value)) {
			alert("주문수량은 숫자만 입력하세요.");
			document.form1.quantity.focus();
			return;
		}
		if(miniq>1 && document.form1.quantity.value<=1) {
			alert("해당 상품의 구매수량은 "+miniq+"개 이상 주문이 가능합니다.");
			document.form1.quantity.focus();
			return;
		}
		
		if(maxq>0 && document.form1.quantity.value > maxq){
			alert("해당 상품의 구매수량은 "+maxq+"개 이하로 주문이 가능합니다.");
			document.form1.quantity.focus();
			return;
		}
		
		if(limquantity>0 && document.form1.quantity.value > limquantity){
			alert("해당 상품의 재고가 "+limquantity+"개 입니다.");
			document.form1.quantity.focus();
			return;
		}

		//무제한 옵션 사용 시 체크
		<?
			if($optClass->optUse) {
		?>
			jQuery('input[name="optMustCnt[]"]').each(function(index, item) {
				if(jQuery(item).val()<=0) {
					alert('필수 옵션을 선택해주세요.');
					optMust = false;
					return false;
				}
			});

			if(!optMust) {
				return;
			}

			jQuery('input[name="opt_comidx[]"]').each(function(index, item) {
				if(index>0) {
					goodsOption += ",";
				}
				goodsOption += jQuery(item).val();
			});

			jQuery('input[name="opt_quantity[]"]').each(function(index, item) {
				if(index>0) {
					goodsQuan += ",";
				}
				goodsQuan += jQuery(item).val();
			});
		<?
			} else {
		?>
			goodsQuan = document.form1.quantity.value;
		<?
			}
		?>
		
		
		var param = "";
		param += "?mode=order&type=product";
		param += "&goodsId=<?=$_pdata->productcode?>";		
		param += "&goodsCount=" + goodsQuan;		
		param += "&goodsOption=" + goodsOption;
		location.href = "/_NaverCheckout/sync.php" + param;
	}

	function _wishlistNaverCheckout(opentype) {
		var isGoodsImage = 1;
		var isGoodsThumbImage = 1;
		var goodsImage = "<?=$_pdata->maximage?>";
		var goodsThumbImage = "<?=$_pdata->tinyimage?>";

		if (!goodsImage) {
			isGoodsImage = 0;
			goodsImage = "";
		}
		if (!goodsThumbImage) {
			isGoodsThumbImage = 0;
			goodsThumbImage = "";
		}
		/*
		var param = "";
		param += "?goodsId=<?=$_pdata->productcode?>";
		param += "&goodsName=<?=$_pdata->productname?>";
		param += "&goodsPrice=<?=$_pdata->sellprice?>";
		param += "&isGoodsImage=" + isGoodsImage;
		param += "&goodsImage=" + goodsImage;
		param += "&isGoodsThumbImage=" + isGoodsThumbImage;
		param += "&goodsThumbImage=" + goodsThumbImage;
*/
		//alert(param);

		//window.open("/_NaverCheckout/wishlist.php" + param, "_wishlistNaverCheckout", "width=397, height=304, scrollbars=yes");
		var param = "";
		param += "?mode=wish&type=product";
		param += "&goodsId=<?=$_pdata->productcode?>";
		//location.href = "/_NaverCheckout/sync.php" + param;		
		if(typeof opentype != 'undefined' && opentype =='mobile'){
			location.href = "/_NaverCheckout/sync.php" + param;
		}else{
			window.open("/_NaverCheckout/sync.php" + param, "_wishlistNaverCheckout", "width=397, height=304, scrollbars=yes");
		}
	}
	
	function _mwishlistNaverCheckout(){
		_wishlistNaverCheckout('mobile');
	}
	
	
	function _cartNaverCheckout() {
		var param = "";
		param += "?mode=order&type=cart";
		param += "&cartId=<?=$_ShopInfo->getTempkey()?>";
		param += "&id=<?=$_ShopInfo->getMemid()?>";
		location.href = "/_NaverCheckout/sync.php" + param;
	}	
	//-->
</script>