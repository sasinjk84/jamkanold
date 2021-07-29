<?
if(!eregi(getenv("HTTP_HOST"),getenv("HTTP_REFERER"))) {
	header("HTTP/1.0 404 Not Found");
	exit;
} else {
?> 
function ACodeSendIt(f,obj) {
	if(obj.value.length>0) {
		f.codeB_name.value = "";
		f.codeC_name.value = "";
		f.codeD_name.value = "";
		f.codeA_name.value = obj.text;
		f.category_view.value = f.codeA_name.value;

		if(obj.ctype=="X") {
			f.code.value = obj.value+"000000000";
		} else {
			f.code.value = obj.value;
		}

		burl = "product_register.ctgr.php?code=" + obj.value;
		curl = "product_register.ctgr.php";
		durl = "product_register.ctgr.php";
		BCodeCtgr.location.href = burl;
		CCodeCtgr.location.href = curl;
		DCodeCtgr.location.href = durl;
		
		checkRental(obj.value);
		refGroupDiscount(obj.value);
	}
}

function sectSendIt(f,obj,x) {
	if(obj.value.length>0) {		
		if(x == 2) {
			f.codeC_name.value = "";
			f.codeD_name.value = "";
			if($j(obj).attr('ctype')=="X") {
				f.code.value = obj.value+"000000";
			} else {
				f.code.value = obj.value;
			}
			durl = "product_register.ctgr.php";
			f.codeB_name.value = obj.text;
			f.category_view.value = f.codeA_name.value + " > " + f.codeB_name.value;
			url = "product_register.ctgr.php?code="+obj.value;
			parent.CCodeCtgr.location.href = url;
			parent.DCodeCtgr.location.href = durl;			
		} else if(x == 3) {
			f.codeD_name.value = "";
			f.codeC_name.value = obj.text;
			if($j(obj).attr('ctype')=="X") {
				f.code.value = obj.value+"000";
			} else {
				f.code.value = obj.value;
			}
			f.category_view.value = f.codeA_name.value + " > " + f.codeB_name.value + " > " + f.codeC_name.value;
			url = "product_register.ctgr.php?code="+obj.value;
			parent.DCodeCtgr.location.href = url;
		} else if(x == 4) {
			f.code.value = obj.value;
			f.codeD_name.value = obj.text;
			f.category_view.value = f.codeA_name.value + " > " + f.codeB_name.value + " > " + f.codeC_name.value + " > " + f.codeD_name.value;
		}
	
		parent.checkRental(obj.value);
		parent.refGroupDiscount(obj.value);
		parent.addKeyword(obj.value);
	}
}

function checkRental(code){
	$j('#sellTypeSelDiv').html();
	$j('.rentalItemArea1').not('.notdistory').remove();	
	$j('.rentalItemArea2').not('.notdistory').remove();	
	$j('.rentalItemArea3').not('.notdistory').remove();	
	$j('.rentalItemArea4').not('.notdistory').remove();	
	$j('.rentalItemArea5').not('.notdistory').remove();	
	$j('.rentalItemArea6').not('.notdistory').remove();
	$j('.rentalItemArea7').not('.notdistory').remove();
	$j('.rentalItemArea8').not('.notdistory').remove();
	$j('.rentalItemArea9').not('.notdistory').remove();
	$j('.rentalItemArea10').not('.notdistory').remove();
	
	$j.post('/vender/new/checkrentalform.php',{'code':code,'act':'solvform'},function(data){
		if(data.checkbox){	
			$j('#sellTypeSelDiv').html(data.checkbox);
			
			if(!$j('.rentalItemArea1').is(':visible')){
				$j('.productItemArea').css('display','none');
				$j('.rentalItemArea1').css('display','');
				$j('.rentalItemArea2').css('display','');
				$j('.rentalItemArea3').css('display','');
				$j('.rentalItemArea4').css('display','');
				$j('.rentalItemArea5').css('display','');
				$j('.rentalItemArea6').css('display','');
				$j('.rentalItemArea7').css('display','');
				$j('.rentalItemArea10').css('display','');
			}
		}
		if(data.cont){
			$j('#placeOfRental').after(data.cont);		
			$j('.rentalItemArea').css('display','none');
		}
		
	},'json');
}


function refGroupDiscount(code){
	$j.post('/vender/new/checkrentalform.php',{'code':code,'act':'groupDiscount'},function(data){
		if(data.err != 'ok'){
			alert(data.err);		
		}else{
			$j(data.items).each(function(idx,val){			
				if($j('#discount_'+val.group_code)) $j('#discount_'+val.group_code).html(val.txt);
			});
			if(data.reseller_reserve && $j('#categoryReserv')) $j('#categoryReserv').html(data.reseller_reserve);
		}
	},'json');
}


function loadLocalList(){
	$j('#localListDiv').html('목록 갱신중');	
	
	$j.post('/vender/new/checkrentalform.php',{'act':'locallist'},function(data){
		if(data.cont){			
			$j('#localListDiv').html(data.cont);
		}else{
			$j('#localListDiv').html('동기화 오류');
		}		
	},'json');

}


var alerttrust = 0;

function toggleTrust(){
	var trust = $j('input[name=istrust]:checked').val();
	if(trust == '-1' && alerttrust == 0){
		alert('본사에서 승인여부와 상품보관 창고를 연락 드립니다.');
		alerttrust = 1;
	}
}
<?
}
?>