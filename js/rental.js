/**
 * Created by x2chi-objet on 2014-10-14.
 */
// �����Ȳ����
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
		alert('������ ���� ���ּ���.');
	}else{		
		window.open("/front/bookingSchedulePop.php?pridx="+pridx+admstr+'&opt='+opt,"bookingSchedulePop","width=710,height=750,scrollbars=yes");
	}
}


// ���� �԰�
function bookingRepair ( pridx ) {
	window.open("/admin/bookingScheduleRepairPop.php?pridx="+pridx,"bookingScheduleRepairPop","width=1000,height=600");
}


// ��ǰ - ����� ����
function bookingProductConnPop ( pridx ) {
	window.open("/admin/bookingProductConnPop.php?pridx="+pridx,"bookingProductConnPop","width=1000,height=600");
}


// �������밡�� �޷�
function bookingPriceCalendalPop (code,vender,pridx) {
	window.open("/front/bookingPriceCalendal.php?code="+code+"&vender="+vender+"&pridx="+pridx,"bookingPriceCalendalPop","width=1000,height=600");
}


// ������ ��ǰ ���� �ɼǰ�����
function rentProdOptManager ( pridx ) {
	window.open("/admin/rentProductOptionManager.php?pridx="+pridx,"rentProductOptionManager","width=1000,height=600,scrollbars=yes,resize=yes");
	//window.open("/admin/product_register.add.rentOption.php","rentProductOptionManager","width=1000,height=600");
}



// ��ǰ�� - �̸� ����ϱ�
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
				document.getElementById('priceCalcPrint').innerHTML = "�ֹ������� ���ڸ� �Է��ϼ���.";
				f.rentOptions.focus();
				return;
			}
			if( parseInt(rentOptionsValue) > 0 ) optionValue += "|" + rentOptionsIdx + ":" + parseInt(rentOptionsValue);
		} else {
			for ( i = 0 ; i < rentOptionsLength ; i++ ){
				var rentOptionsValue = f.rentOptions[i].value;
				var rentOptionsIdx = f.rentOptions[i].getAttribute('idxcode');
				if(!IsNumeric(rentOptionsValue)) {
					document.getElementById('priceCalcPrint').innerHTML = "�ֹ������� ���ڸ� �Է��ϼ���.";
					f.rentOptions[i].focus();
					return;
				}
				if( parseInt(rentOptionsValue) > 0 ) optionValue += "|" + rentOptionsIdx + ":" + parseInt(rentOptionsValue);
			}
		}
		f.rentOptionList.value = optionValue;

		if( optionValue.length == 0 ) {
			document.getElementById('priceCalcPrint').innerHTML = "�ɼ��� �����ϼ���!";
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


// ��ǰ�� - �̸� ����ϱ�
function priceCalc2(f,retid){	
	var retel = (retid && $j('#'+retid))?$j('#'+retid):($j('#priceCalcPrint')?$j('#priceCalcPrint'):null);	
	var periodtext = (retid && $j('#'+retid))?$j('#'+retid):($j('#periodPrint')?$j('#periodPrint'):null);	
	var optionValue='';
	var optidx = '';
	var qtytext = '';
	

//	try{		

		if($j('.rentOptionSelect').length < 1){
			$j(retel).html('���ð����� �ɼ��� �����ϴ�.');
			return;
		}
		var optionValue = '';
		
		$j('.rentOptionSelect').each(function(idx,el){
			var qty = $j(el).val();	
			if(!IsNumeric(qty)){
				$j(retel).html('�ɼ� ������ ���ڸ� �Է��ϼ���');
				$j(el).focus();
				return false;
			}
			if( parseInt(qty) > 0 ) optionValue += "|" + $j(el).attr('idxcode')+":" + parseInt(qty);

			optidx = $j(el).attr('idxcode');
		});

		qtytext = $j('#productCnt_'+optidx);

		$j('input[name=rentOptionList]').val(optionValue);

		if($j.trim(optionValue).length < 3){
			$j(retel).html('�ɼǼ����� �Է��ϼ���.');
			return;
		}
		
		if($j('#pricetype').val()!="long"){
			var now = new Date();
			var nowDay = now.getFullYear()+"-"+("0"+(now.getMonth()+1)).slice(-2)+"-"+("0"+now.getDate()).slice(-2);
			var nowTime = now.getHours();

			if($j('#p_bookingStartDate').val() !="" && $j('#p_bookingStartDate').val()==nowDay && $j('#startTime').val()<=nowTime){
				alert("����ð����� ���� �ð��� ������ �� �����ϴ�.");
				$j(retel).html('��¥���� ����');
				return false;
			}

			if($j('#p_bookingStartDate').val() !="" && $j('#p_bookingStartDate').val()==$j('#p_bookingEndDate').val() && $j('#endTime').val()!="" && $j('#startTime').val()>=$j('#endTime').val()){
				alert("�뿩�ϰ� �ݳ����� ���� ��� �ݳ��ð��� �뿩�ð����� ���� ���� �����ϴ�.");
				$j(retel).html('��¥���� ����');
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
							html += "<td>�Ⱓ</td>";
							html += "<td></td>";
							html += "<td align=right>";
							if($j('#pricetype').val()=="time"){//�ð����� ��� �Ⱓǥ�� ����
								html += parseInt(data.diff.day)*24 + parseInt(data.diff.hour)+' �ð�';
							}else{
								html += (parseInt(data.diff.day) > 0)?data.diff.day+' ��':'';
								html += (parseInt(data.diff.hour) > 0)?data.diff.hour+' �ð�':'';
							}
							html += " ("+data.rangetxt[0]+" ~ "+data.rangetxt[1]+")";
							html += "</td>";
							html += "</tr>";
							*/

							if($j('#pricetype').val()=="time"){//�ð����� ��� �Ⱓǥ�� ����
								html2 = parseInt(data.diff.day)*24 + parseInt(data.diff.hour)+' �ð�';
							}else{
								html2 += (parseInt(data.diff.day) > 0)?data.diff.day+' ��':'';
								html2 += (parseInt(data.diff.hour) > 0)?data.diff.hour+' �ð�':'';
							}
						}

						html += "<tr>";
						html += "<td>�հ�ݾ�</td>";
						html += "<td></td>";
						html += "<td align=right>";
						html += number_format(data.totalprice)+"��";
						html += "</td>";
						html += "</tr>";
						
						if(data.discprice!=0){
							html += "<tr>";
							html += "<td>��ⷻŻ����</td>";
							html += "<td></td>";
							html += "<td align=right>";
							html += "<font style=\"color:#568EF5;\">"+number_format(data.discprice)+"</font>��";
							html += "</td>";
							html += "</tr>";
						}

						if(data.longrentmsg>0){
							html += "<tr>";
							html += "<td>��ⷻŻ�߰�("+data.longrentmsg+"%)</td>";
							html += "<td></td>";
							html += "<td align=right>";
							html += "<font style=\"color:#ec2f36;\">"+number_format(data.longrent)+"</font>��";
							html += "</td>";
							html += "</tr>";
						}
						
						if(data.addprice>0){
							html += "<tr>";
							html += "<td>�ָ�/��������</td>";
							html += "<td></td>";
							html += "<td align=right>";
							html += "<font style=\"color:#ec2f36;\">"+number_format(data.addprice)+"</font>��";
							html += "</td>";
							html += "</tr>";
						}

						html += "<tr>";
						html += "<td style=\"font-size:20px;font-weight:bold\">�ֹ��ݾ�</td>";
						html += "<td></td>";
						html += "<td style=\"font-family:tahoma,����;\" align=right>";
						html += '<strong style="font-size:20px;">'+number_format(data.pricetxt)+'<span style="font-size:13px;">��</span></strong>';
						html += "</td>";
						html += "</tr>";
						
						
						if(ismember=="N"){
							data.reserv = "<a href=\"/front/login.php?reurl="+reurl+"\" style=\"color:#568EF5;text-decoration:underline\">�α���</a><font style=\"color:#568EF5;\">�� �ϼ���.</font>";
						}else{
							if(disc_per>0){
								data.reserv = "<font style=\"color:#568EF5;\">"+number_format(data.discountprice) + "</font>��";
							}else{
								data.reserv = "<font style=\"color:#568EF5;\">"+number_format(data.reserv) + "</font>��";
							}
						}
						//if(data.reserv>0 || ismember=="N"){
							html += "<tr>";
							html += "<td><font style=\"color:#568EF5;\">����/����</font></td>";
							html += "<td></td>";
							html += "<td align=right>";
							html += number_format(data.reserv);
							html += "</td>";
							html += "</tr>";
						//}
						html += "</table>";
	/*
						var html = (parseInt(data.diff.day) > 0)?data.diff.day+' ��':'';
						html += (parseInt(data.diff.hour) > 0)?data.diff.hour+' �ð�':'';
						html += ' ('+data.rangetxt[0]+" ~ "+data.rangetxt[1]+")<br />";
						html += '<strong style="font-size:20px;">'+data.pricetxt+'<span style="font-size:13px;">��</span></strong>';	
						if($j.trim(data.discountmsg).length) html+= data.discountmsg;
	*/					

						if($j('#pricetype').val()!="long"){
										
							$j('.rentOptionSelect').each(function(idx,el){
								optidx = $j(el).attr('idxcode');
								if(data.opt_cnt[optidx]==0){
									//alert("���õ� �׸��� ǰ���Ǿ����ϴ�.");
									$j(retel).html("ǰ���� ��ǰ�Դϴ�.");
									option_delete(optidx);
								}else{
									$j('#productCnt_'+optidx).html(data.opt_cnt[optidx]+"�� ����");
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



/** ���ϱ� ī�װ� ���� */
/* ī�װ����� ���̱�*/
function wishCateViewOnOff ( t ) {
	t.style.display = ( t.style.display == 'none' ) ? 'block' : 'none';
}

/* ī�װ� �߰�*/
function wishCateInsert(f) {
	if( f.cateTitle.value.length == 0 ) {
		alert("ī�װ����� �Է��ϼ���!");
		f.cateTitle.focus();
		return false;
	}
	f.mode.value = "cateInsert";
	f.method = "POST";
	f.submit();
}

/* ī�װ� ����*/
function wishCateModify(f) {
	if( f.cateTitle.value.length == 0 ) {
		alert("ī�װ����� �Է��ϼ���!");
		f.cateTitle.focus();
		return false;
	}
	f.mode.value = "cateModify";
	f.method = "POST";
	f.submit();
}

/* ī�װ� ����*/
function wishCateDelete(f,k) {
	if( confirm('���� ������ ���� ���� ����� ��ǰ�鵵 �Բ� �����˴ϴ�\r\n������ �����Ͻðڽ��ϱ�?') ) {
		f.delCateIdx.value = k;
		f.mode.value = "cateDelete";
		f.method = "POST";
		f.submit();
	}
}

/* ī�װ� �̵�*/
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
		alert("�̵��� ��ǰ�� �����ϼ���.");
		return;
	}
	f.mode.value="cateMove";
	f.submit();
}

/* ī�װ� ����*/
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
		alert("������ ��ǰ�� �����ϼ���.");
		return;
	}
	f.mode.value="cateCopy";
	f.submit();
}

/* ��ٱ������� �߰�*/
function basketFolderInsert(f) {
	if( f.newFoldername.value.length == 0 ) {
		alert("ī�װ����� �Է��ϼ���!");
		f.newFoldername.focus();
		return false;
	}

	$j('#basketForm>input[name=act]').val('insertFolder');
	$j('#basketForm>input[name=newFolder]').val(f.newFoldername.value);
	$j('#basketForm').submit();

}
/* ��ٱ��� ������ ����*/
function basketFolderModify(f) {
	if( f.newFoldername.value.length == 0 ) {
		alert("�������� �Է��ϼ���!");
		f.newFoldername.focus();
		return false;
	}
	f.submit();
}
/* ��ٱ��� ���� �̵�*/
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
		alert("�̵��� ��ǰ�� �����ϼ���.");
		return;
	}

	$j('#basketForm>input[name=act]').val('moveFolder');
	$j('#basketForm>input[name=moveFolder]').val(f.targetFolder.value);
	$j('#basketForm').submit();
}

/* ��ٱ��� ���� ����*/
function basketCateDelete(f,k) {
	if( confirm('���� ������ ���� ���� ����� ��ǰ�鵵 �Բ� �����˴ϴ�\r\n������ �����Ͻðڽ��ϱ�?') ) {
		f.bfidx.value = k;
		f.act.value = "delFolder";
		f.method = "POST";
		f.submit();
	}
}


/* ���ø���Ʈ���� ��ٱ��Ϸ� �̵� */
function wishToBasketMove ( mode ) {

	var f= document.form1;

	switch ( mode ) {
		case 'pester' : // ������
			f.mode.value="wishToPesterBasketMove";
			break;
		case 'present' : // �����ϱ�
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
		alert("�̵��� ��ǰ�� �����ϼ���.");
		f.mode.value="";
		return;
	}

	f.submit();
}






// �ֹ� ���� ����
function bookingStatusChange ( idx, value ) {
	$('#loading_'+idx).css("display","block");
	$('#loading_'+idx).html("<img src='/images/ajax-loader.gif'>");
	$.ajax({
		url: "product_rental.booking.stateCHG.php",
		data: { idx: idx, value: value }
	}).done(function(msg) {
		if( msg == "OK" ) {
			$('#loading_'+idx).html("����Ϸ�!");
		} else {
			$('#loading_'+idx).html("�������!");
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
//	alert('�׽�Ʈ��');
}