function quickOrder(productcode,chkquantity){
	if(chkquantity == '-1'){
		alert('재고가 없습니다.');
	}else{
		PrdtQuickCls.quickFun(productcode,'3');
	}
	return false;
}

function quickCart(productcode,chkquantity){
	if(chkquantity == '-1'){
		alert('재고가 없습니다.');
	}else{
		PrdtQuickCls.quickFun(productcode,'2');
	}
	return false;
}

function quickFavorite(productcode,chkquantity){
	if(chkquantity == '-1'){
		alert('재고가 없습니다.');
	}else{
		PrdtQuickCls.quickFun(productcode,'1');
	}
	return false;
}


function overItem(el){
	$j(el).addClass('over');
}

function leaveItem(el){
	$j(el).removeClass('over');
}