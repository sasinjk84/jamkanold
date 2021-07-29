<!-- 카테고리 목록 상단 신상및 베트스 상품 등 -->
<link rel="stylesheet" type="text/css" href="/css/newUI.css" />
<style type="text/css">
#__ID__{ clear:both; padding-bottom:1px; height:230px;}

#__ID__ .productWrapper{ width:226px; height:320px;margin-right:6px; border:2px solid #f5f6fa; background:#000;}
#__ID__ .productWrapper.over{border:2px solid #ff0000;}


#__ID__ .productWrapper .infoArea{ position:absolute; top:260px; width:228px; height:120px;
	/* Fallback for web browsers that doesn't support RGBa */
    background: rgb(0, 0, 0);
    /* RGBa with 0.6 opacity */
    background: rgba(0, 0, 0, 0.7);
    /* For IE 5.5 - 7*/
    filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000);
    /* For IE 8*/
    -ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000)"; 
}

#__ID__ .productWrapper .rentIcon,#__ID__ .productWrapper .sellIcon{ top:0px;}

#__ID__ .productWrapper.over .infoArea{top:135px;}

#__ID__ .productWrapper .infoArea .itemname{ height:40px; color:#e1e1e1; word-break:break-all}
/* #__ID__ .productWrapper .infoArea .itemname span{ margin:6px;}*/
#__ID__ .productWrapper .infoArea .itemprice .mainconprice{ color:#e1e1e1}

#__ID__ div.endItem{margin:30px 8px 30px 0px;}

</style>
<script language="javascript" type="text/javascript">

$j(function(){
	$j('#__ID__').find('.productWrapper').on('mouseover',function(e){ overItem(this)});
	$j('#__ID__').find('.productWrapper').on('mouseout',function(e){ leaveItem(this)});
});

</script>
<div id="__ID__">
<!-- items -->
	<div class="productWrapper product.listfinal" style="cursor:pointer">
		<a href="product.linkurl">
		product.saleTypeIcon
		<div class="productImg" style="width:228px;height:228px;overflow:hidden;"><img src="product.minimgsrc" style="width:100%;height:100%;"></div>
		<div class="infoArea">
			<div class="itemname">product.name
				<span class="reviewArea">
					<span class="reviewMark">product.reviewmark</span>
					<span class="reviewCount">product.reviewcount</span>
				</span>
			</div>
			<div class="itemprice">
				product.ifcprice
				<div class="discountRate">product.discountRate</div>
				product.endcprice
				<div class="priceTxtArea">
					<?php if($consumerprice == $sellprice){?>
					product.ifcprice
					<span class="customerprice">product.cprice_txt</span><br />
					product.endcprice
					<span class="mainprprice" style="clear:both">product.price_txt</span>
					<?php } else if ($consumerprice != $sellprice) {?>
					product.endcprice
					<span class="mainprprice" style="clear:both">product.price_txt</span>
					<?php }?>
				</div>
			</div>
		</div>
		</a>
		<dl class="quickView">
			<dd class="qpreview" onClick="PrdtQuickCls.quickView('product.productcode');"><span class="qtext"></span></dd>
<!--			<dd class="qzoom" onClick="quickZoom('product.productcode');"><span class="qtext"></span></dd>-->
			<dd class="qfavorite" onClick="quickFavorite('product.productcode','product.chkquantity');"><span class="qtext"></span></dd>
			<dd class="qcart" onClick="quickCart('product.productcode','product.chkquantity');"><span class="qtext"></span></dd>
<!--			<dd class="qorder product.ClassType" onClick="quickOrder('product.productcode','product.chkquantity');"><span class="qtext"></span></dd>-->
		</dl>
	</div>
<!-- /items -->
</div>