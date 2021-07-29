/**
 * Created by x2chi-objet on 2014-10-14.
 */
// 출고현황정보
function bookingSchedulePop ( pridx ,isadmin) {
	if(isadmin) admstr = '&isadm=1';
	else admstr = '';
	var opt = '';
	
	$j('input[name=rentOptions]').each(function(idx,el){
		var qty = parseInt($j(el).val());
		var opidx= $j(el).attr('idxcode');
		if(!isNaN(qty) && qty >= 1){
			opt += opidx+'|'+qty+',';			
		}
	});
	if(opt.length < 1 ){
		alert('수량을 선택 해주세요.');
	}else{		
		window.open("/front/bookingSchedulePop.php?pridx="+pridx+admstr+'&opt='+opt,"bookingSchedulePop","width=710,height=750,scrollbars=yes");
	}
}


// 정비 입고
function bookingRepair ( pridx ) {
	window.open("/admin/bookingScheduleRepairPop.php?pridx="+pridx,"bookingScheduleRepairPop","width=1000,height=600");
}


// 상품 - 출고지 연동
function bookingProductConnPop ( pridx ) {
	window.open("/admin/bookingProductConnPop.php?pridx="+pridx,"bookingProductConnPop","width=1000,height=600");
}


// 시즌적용가격 달력
function bookingPriceCalendalPop (code,vender,pridx) {
	window.open("/front/bookingPriceCalendal.php?code="+code+"&vender="+vender+"&pridx="+pridx,"bookingPriceCalendalPop","width=1000,height=600");
}


// 관리자 상품 정보 옵션관리자
function rentProdOptManager ( pridx ) {
	window.open("/admin/rentProductOptionManager.php?pridx="+pridx,"rentProductOptionManager","width=1000,height=600,scrollbars=yes,resize=yes");
	//window.open("/admin/product_register.add.rentOption.php","rentProductOptionManager","width=1000,height=600");
}



// 상품상세 - 미리 계산하기
function priceCalc( f ){
	document.getElementById('priceCalcPrint').innerHTML = "<img src='/images/ajax-loader.gif'>";
	try {
		var priceCalculator;
		priceCalculator = new XMLHttpRequest();

		var optionValue = "";
		var rentOptionsLength = parseInt(f.rentOptions.length);
		
		if( isNaN( rentOptionsLength ) ) {
			var rentOptionsValue = f.rentOptions.value;
			var rentOptionsIdx = f.rentOptions.getAttribute('idxcode');
			if(!IsNumeric(rentOptionsValue)) {
				document.getElementById('priceCalcPrint').innerHTML = "주문수량은 숫자만 입력하세요.";
				f.rentOptions.focus();
				return;
			}
			if( parseInt(rentOptionsValue) > 0 ) optionValue += "|" + rentOptionsIdx + ":" + parseInt(rentOptionsValue);
		} else {
			for ( i = 0 ; i < rentOptionsLength ; i++ ){
				var rentOptionsValue = f.rentOptions[i].value;
				var rentOptionsIdx = f.rentOptions[i].getAttribute('idxcode');
				if(!IsNumeric(rentOptionsValue)) {
					document.getElementById('priceCalcPrint').innerHTML = "주문수량은 숫자만 입력하세요.";
					f.rentOptions[i].focus();
					return;
				}
				if( parseInt(rentOptionsValue) > 0 ) optionValue += "|" + rentOptionsIdx + ":" + parseInt(rentOptionsValue);
			}
		}
		f.rentOptionList.value = optionValue;

		if( optionValue.length == 0 ) {
			document.getElementById('priceCalcPrint').innerHTML = "옵션을 선택하세요!";
			return;
		}
		
		var sdate = f.p_bookingStartDate.value || f.qp_bookingStartDate;
		if($j('#startTime').length) sdate += ' '+$j('#startTime').val();		
		var edate = f.p_bookingEndDate.value || f.qp_bookingEndDate;
		if($j('#endTime').length) edate += ' '+$j('#endTime').val();
		var pridx = f.pridx.value;
		var vender = f.vender.value;
		
		priceCalculator.onreadystatechange=function() {
			document.getElementById('priceCalcPrint').innerHTML = ( priceCalculator.readyState == 4 && priceCalculator.status == 200 ? priceCalculator.responseText : '' );
		};
		priceCalculator.open('POST', '/front/priceCalculator.php', true);
		priceCalculator.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		priceCalculator.send( "opt=" + optionValue + "&sdate=" + sdate + "&edate=" + edate + "&pridx=" + pridx + "&vender=" + vender  );
	} catch ( e ) {}
}


// 상품상세 - 미리 계산하기
function priceCalc2(f,retid){	
	var retel = (retid && $j('#'+retid))?$j('#'+retid):($j('#priceCalcPrint')?$j('#priceCalcPrint'):null);	
	var periodtext = (retid && $j('#'+retid))?$j('#'+retid):($j('#periodPrint')?$j('#periodPrint'):null);	
	var optionValue='';
	var optidx = '';
	var qtytext = '';
	

//	try{		

		if($j('.rentOptionSelect').length < 1){
			$j(retel).html('선택가능한 옵션이 없습니다.');
			return;
		}
		var optionValue = '';
		
		$j('.rentOptionSelect').each(function(idx,el){
			var qty = $j(el).val();	
			if(!IsNumeric(qty)){
				$j(retel).html('옵션 수량은 숫자만 입력하세요');
				$j(el).focus();
				return false;
			}
			if( parseInt(qty) > 0 ) optionValue += "|" + $j(el).attr('idxcode')+":" + parseInt(qty);

			optidx = $j(el).attr('idxcode');
		});

		qtytext = $j('#productCnt_'+optidx);

		$j('input[name=rentOptionList]').val(optionValue);

		if($j.trim(optionValue).length < 3){
			$j(retel).html('옵션수량을 입력하세요.');
			return;
		}
		
		if($j('#pricetype').val()!="long"){
			var now = new Date();
			var nowDay = now.getFullYear()+"-"+("0"+(now.getMonth()+1)).slice(-2)+"-"+("0"+now.getDate()).slice(-2);
			var nowTime = now.getHours();

			if($j('#p_bookingStartDate').val() !="" && $j('#p_bookingStartDate').val()==nowDay && $j('#startTime').val()<=nowTime){
				alert("현재시간보다 빠른 시간은 선택할 수 없습니다.");
				$j(retel).html('날짜선택 오류');
				return false;
			}

			if($j('#p_bookingStartDate').val() !="" && $j('#p_bookingStartDate').val()==$j('#p_bookingEndDate').val() && $j('#endTime').val()!="" && $j('#startTime').val()>=$j('#endTime').val()){
				alert("대여일과 반납일이 같은 경우 반납시간이 대여시간보다 빠를 수는 없습니다.");
				$j(retel).html('날짜선택 오류');
				return false;
			}
		}

		var sdate = $j('#p_bookingStartDate').val() || f.qp_bookingStartDate;
		if($j('#startTime').length) sdate += ' '+$j('#startTime').val();		
		var edate = $j('#p_bookingEndDate').val() || f.qp_bookingEndDate;
		if($j('#endTime').length) edate += ' '+$j('#endTime').val();
		var pridx = $j('#pridx').val();
		var vender = $j('#vender').val();
		var disc_per = $j('#disc_per').val();
		var ismember = $j('#ismember').val();
		var reurl = $j('#reurl').val();
		
		if(($j('#pricetype').val()!="long" && $j('#p_bookingStartDate').val() !="" && $j('#startTime').val()!="" && $j('#p_bookingEndDate').val() !="" && $j('#endTime').val()!="") || $j('#pricetype').val()=="long"){
			
			$j(retel).html("<img src='/images/ajax-loader.gif'>");

			$j.post('/ajaxback/rent.php',{'act':'solvPrice','opt':optionValue,'sdate':sdate,'edate':edate,'pridx':pridx,'vender':vender,'reserveconv':$j('#reserveconv').val()},
				function(data){
					if(data.err == 'ok'){
						var qtyhtml = "";
						var html2 ="";
						var html = "<table width=80% border=0 cellpadding=0 cellspacing=0>";

						if($j('#pricetype').val()!="long"){
							/*
							html += "<tr>";
							html += "<td>기간</td>";
							html += "<td></td>";
							html += "<td align=right>";
							if($j('#pricetype').val()=="time"){//시간제인 경우 기간표시 변경
								html += parseInt(data.diff.day)*24 + parseInt(data.diff.hour)+' 시간';
							}else{
								html += (parseInt(data.diff.day) > 0)?data.diff.day+' 일':'';
								html += (parseInt(data.diff.hour) > 0)?data.diff.hour+' 시간':'';
							}
							html += " ("+data.rangetxt[0]+" ~ "+data.rangetxt[1]+")";
							html += "</td>";
							html += "</tr>";
							*/

							if($j('#pricetype').val()=="time"){//시간제인 경우 기간표시 변경
								html2 = parseInt(data.diff.day)*24 + parseInt(data.diff.hour)+' 시간';
							}else{
								html2 += (parseInt(data.diff.day) > 0)?data.diff.day+' 일':'';
								html2 += (parseInt(data.diff.hour) > 0)?data.diff.hour+' 시간':'';
							}
						}

						html += "<tr>";
						html += "<td>합계금액</td>";
						html += "<td></td>";
						html += "<td align=right>";
						html += number_format(data.totalprice)+"원";
						html += "</td>";
						html += "</tr>";
						
						if(data.discprice!=0){
							html += "<tr>";
							html += "<td>장기렌탈할인</td>";
							html += "<td></td>";
							html += "<td align=right>";
							html += "<font style=\"color:#568EF5;\">"+number_format(data.discprice)+"</font>원";
							html += "</td>";
							html += "</tr>";
						}

						if(data.longrentmsg>0){
							html += "<tr>";
							html += "<td>장기렌탈추가("+data.longrentmsg+"%)</td>";
							html += "<td></td>";
							html += "<td align=right>";
							html += "<font style=\"color:#ec2f36;\">"+number_format(data.longrent)+"</font>원";
							html += "</td>";
							html += "</tr>";
						}
						
						if(data.addprice>0){
							html += "<tr>";
							html += "<td>주말/성수기요금</td>";
							html += "<td></td>";
							html += "<td align=right>";
							html += "<font style=\"color:#ec2f36;\">"+number_format(data.addprice)+"</font>원";
							html += "</td>";
							html += "</tr>";
						}

						html += "<tr>";
						html += "<td style=\"font-size:20px;font-weight:bold\">주문금액</td>";
						html += "<td></td>";
						html += "<td style=\"font-family:tahoma,돋움;\" align=right>";
						html += '<strong style="font-size:20px;">'+number_format(data.pricetxt)+'<span style="font-size:13px;">원</span></strong>';
						html += "</td>";
						html += "</tr>";
						
						
						if(ismember=="N"){
							data.reserv = "<a href=\"/front/login.php?reurl="+reurl+"\" style=\"color:#568EF5;text-decoration:underline\">로그인</a><font style=\"color:#568EF5;\">을 하세요.</font>";
						}else{
							if(disc_per>0){
								data.reserv = "<font style=\"color:#568EF5;\">"+number_format(data.discountprice) + "</font>원";
							}else{
								data.reserv = "<font style=\"color:#568EF5;\">"+number_format(data.reserv) + "</font>원";
							}
						}
						//if(data.reserv>0 || ismember=="N"){
							html += "<tr>";
							html += "<td><font style=\"color:#568EF5;\">적립/할인</font></td>";
							html += "<td></td>";
							html += "<td align=right>";
							html += number_format(data.reserv);
							html += "</td>";
							html += "</tr>";
						//}
						html += "</table>";
	/*
						var html = (parseInt(data.diff.day) > 0)?data.diff.day+' 일':'';
						html += (parseInt(data.diff.hour) > 0)?data.diff.hour+' 시간':'';
						html += ' ('+data.rangetxt[0]+" ~ "+data.rangetxt[1]+")<br />";
						html += '<strong style="font-size:20px;">'+data.pricetxt+'<span style="font-size:13px;">원</span></strong>';	
						if($j.trim(data.discountmsg).length) html+= data.discountmsg;
	*/					

						if($j('#pricetype').val()!="long"){
										
							$j('.rentOptionSelect').each(function(idx,el){
								optidx = $j(el).attr('idxcode');
								if(data.opt_cnt[optidx]==0){
									//alert("선택된 항목이 품절되었습니다.");
									$j(retel).html("품절된 상품입니다.");
									option_delete(optidx);
								}else{
									$j('#productCnt_'+optidx).html(data.opt_cnt[optidx]+"개 남음");
									$j('#restCnt_'+optidx).val(data.opt_cnt[optidx]);
								}
							});


						}
						$j(periodtext).html(html2);
						$j(retel).html(html);
					}else{
						$j(retel).html(data.err);
					}
				},'json');			
		}
//	} catch(e){
//		alert(e.message);
//	}
}



/** 찜하기 카테고리 관리 */
/* 카테고리관리 보이기*/
function wishCateViewOnOff ( t ) {
	t.style.display = ( t.style.display == 'none' ) ? 'block' : 'none';
}

/* 카테고리 추가*/
function wishCateInsert(f) {
	if( f.cateTitle.value.length == 0 ) {
		alert("카테고리명을 입력하세요!");
		f.cateTitle.focus();
		return false;
	}
	f.mode.value = "cateInsert";
	f.method = "POST";
	f.submit();
}

/* 카테고리 수정*/
function wishCateModify(f) {
	if( f.cateTitle.value.length == 0 ) {
		alert("카테고리명을 입력하세요!");
		f.cateTitle.focus();
		return false;
	}
	f.mode.value = "cateModify";
	f.method = "POST";
	f.submit();
}

/* 카테고리 삭제*/
function wishCateDelete(f,k) {
	if( confirm('폴더 삭제시 폴더 내에 저장된 상품들도 함께 삭제됩니다\r\n폴더를 삭제하시겠습니까?') ) {
		f.delCateIdx.value = k;
		f.mode.value = "cateDelete";
		f.method = "POST";
		f.submit();
	}
}

/* 카테고리 이동*/
function wishCateMove (f) {
	var issel=false;
	for (var i=0;i<f.elements.length;i++) {
		var e = f.elements[i];
		if(e.type.toUpperCase()=="CHECKBOX" && e.name=="sels[]") {
			if(e.checked==true) {
				issel=true;
				break;
			}
		}
	}
	if(!issel) {
		alert("이동할 상품을 선택하세요.");
		return;
	}
	f.mode.value="cateMove";
	f.submit();
}

/* 카테고리 복사*/
function wishCateCopy (f) {
	var issel=false;
	for (var i=0;i<f.elements.length;i++) {
		var e = f.elements[i];
		if(e.type.toUpperCase()=="CHECKBOX" && e.name=="sels[]") {
			if(e.checked==true) {
				issel=true;
				break;
			}
		}
	}
	if(!issel) {
		alert("복사할 상품을 선택하세요.");
		return;
	}
	f.mode.value="cateCopy";
	f.submit();
}

/* 장바구니폴더 추가*/
function basketFolderInsert(f) {
	if( f.newFoldername.value.length == 0 ) {
		alert("카테고리명을 입력하세요!");
		f.newFoldername.focus();
		return false;
	}

	$j('#basketForm>input[name=act]').val('insertFolder');
	$j('#basketForm>input[name=newFolder]').val(f.newFoldername.value);
	$j('#basketForm').submit();

}
/* 장바구니 폴더명 수정*/
function basketFolderModify(f) {
	if( f.newFoldername.value.length == 0 ) {
		alert("폴더명을 입력하세요!");
		f.newFoldername.focus();
		return false;
	}
	f.submit();
}
/* 장바구니 폴더 이동*/
function basketCateMove (f) {
	var issel=false;
	for (var i=0;i<document.basketForm.elements.length;i++) {
		var e = document.basketForm.elements[i];
		if(e.type.toUpperCase()=="CHECKBOX" && e.name=="basket_select_item[]") {
			if(e.checked==true) {
				issel=true;
				break;
			}
		}
	}
	if(!issel) {
		alert("이동할 상품을 선택하세요.");
		return;
	}

	$j('#basketForm>input[name=act]').val('moveFolder');
	$j('#basketForm>input[name=moveFolder]').val(f.targetFolder.value);
	$j('#basketForm').submit();
}

/* 장바구니 폴더 삭제*/
function basketCateDelete(f,k) {
	if( confirm('폴더 삭제시 폴더 내에 저장된 상품들도 함께 삭제됩니다\r\n폴더를 삭제하시겠습니까?') ) {
		f.bfidx.value = k;
		f.act.value = "delFolder";
		f.method = "POST";
		f.submit();
	}
}


/* 위시리스트에서 장바구니로 이동 */
function wishToBasketMove ( mode ) {

	var f= document.form1;

	switch ( mode ) {
		case 'pester' : // 조르기
			f.mode.value="wishToPesterBasketMove";
			break;
		case 'present' : // 선물하기
			f.mode.value="wishToPresentBasketMove";
			break;
		default :
			f.mode.value="wishToBasketMove";
			break;
	}

	var issel=false;
	for (var i=0;i<f.elements.length;i++) {
		var e = f.elements[i];
		if(e.type.toUpperCase()=="CHECKBOX" && e.name=="sels[]") {
			if(e.checked==true) {
				issel=true;
				break;
			}
		}
	}
	if(!issel) {
		alert("이동할 상품을 선택하세요.");
		f.mode.value="";
		return;
	}

	f.submit();
}






// 주문 상태 변경
function bookingStatusChange ( idx, value ) {
	$('#loading_'+idx).css("display","block");
	$('#loading_'+idx).html("<img src='/images/ajax-loader.gif'>");
	$.ajax({
		url: "product_rental.booking.stateCHG.php",
		data: { idx: idx, value: value }
	}).done(function(msg) {
		if( msg == "OK" ) {
			$('#loading_'+idx).html("변경완료!");
		} else {
			$('#loading_'+idx).html("변경실패!");
		}
		setTimeout( function() {
			$('#loading_'+idx).fadeOut(800);
		}, 1000);
	});
}



function checkRentRange(el){
	/*
	p_bookingStartDate
	startTime
	
	p_bookingEndDate
	endTime
	alert('ddd');
	alert($j(el).attr('id'));*/
}


function quickZoom(productcode){
//	if($j('#zoomDiv').length <1) $j(document).append('<div id="zoomDiv" style="display:none" class="content"></div>');
	$j('#zoomDiv').bPopup({
          content:'ajaximg', //'ajax', 'iframe' or 'image'
//			content:'image', //'ajax', 'iframe' or 'image'
         contentContainer:'.zoomContent',
         loadUrl:'/data/getmaximg.php?productcode='+productcode
		//   loadUrl:'/data/shopimages/product/'+productcode+'.jpg'
     });
//	alert('테스트중');
}