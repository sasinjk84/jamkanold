<!-- 에디터용 파일 호출 -->
<!-- <script type="text/javascript" src="/gmeditor/js/jquery.js"></script> -->
<script type="text/javascript" src="/gmeditor/js/jquery.event.drag-2.0.min.js"></script>
<script type="text/javascript" src="/gmeditor/js/jquery.resizable.js"></script>
<script type="text/javascript" src="/gmeditor/js/ajax_upload.3.6.js"></script>
<script type="text/javascript" src="/gmeditor/js/ej.h2xhtml.js"></script>
<script type="text/javascript" src="/gmeditor/editor.js"></script> 
<script language="javascript" type="text/javascript">
$(document).ready(function() {
	ejEditor();
});
</script>
<style type="text/css">
  @import url("/gmeditor/common.css");
</style>
<!-- # 에디터용 파일 호출 -->
<script type="text/javascript" src="calendar.js.php"></script>
<script language="JavaScript">
function checkBulkForm(form){
	if($.trim(form.senderName.value) == ''){
		alert('발송자 이름을 입력하세요.');
		form.senderName.focus();
		return false;
	}
	if($.trim(form.senderEmail.value) == ''){
		alert('발송자 이메일을 입력하세요.');
		form.senderEmail.focus();
		return false;
	}
	if($.trim(form.subject.value) == ''){
		alert('메일제목을 입력하세요.');
		form.subject.focus();
		return false;
	}
	if(form.setCoupon[1].checked){
		if(!CheckForm(form)) return false;
	}
	return true;
}

function CheckForm(form) {	
	if (form.time[0].checked==true) {
		date = "<?=date("Y-m-d");?>";
		if (form.date_start.value<date || form.date_end.value<date || form.date_start.value>form.date_end.value) {
			alert("쿠폰 유효기간 설정이 잘못되었습니다.\n\n다시 확인하시기 바랍니다.");
			form.date_start.focus();
			return false;
		}
		content+="* 쿠폰 유효기간 : "+form.date_start.value+" ~ "+form.date_end.value+" 까지\n\n";
	}else{
		if (form.peorid.value.length==0) {
			alert("쿠폰 사용기간을 입력하세요.");
			form.peorid.focus();
			return false;
		} else if (!IsNumeric(document.bulkMailForm.peorid.value)) {
			alert("쿠폰 사용기간은 숫자만 입력 가능합니다.");
			form.peorid.focus();
			return false;
		}
	}
	if (form.sale_money.value.length==0) {
		alert("쿠폰 할인 금액/할인률을 입력하세요.");
		form.sale_money.focus();
		return false;
	} else if (!IsNumeric(form.sale_money.value)) {
		alert("쿠폰 할인 금액/할인률은 숫자만 입력 가능합니다.(소숫점 입력 안됨)");
		form.sale_money.focus();
		return false;
	}
	if(form.sale2.selectedIndex==1 && form.sale_money.value>=100){
		alert("쿠폰 할인률은 100보다 작아야 합니다.");
		form.sale_money.focus();
		return false;
	}	

	if(form.productcode.value.length==18 && form.checksale[1].checked==true && form.use_con_type2.checked!=true) {
		alert("쿠폰이 한상품에 적용될경우 구매금액에 제한이 없습니다.");
		nomoney(1);
	}
	if(form.checksale[1].checked==true){
		if(form.mini_price.value.length==0){
			alert("쿠폰 결제 금액을 입력하세요.");
			document.bulkMailForm.mini_price.focus();
			return false;
		}else if(!IsNumeric(form.mini_price.value)){
			alert("쿠폰 결제 금액은 숫자만 입력 가능합니다.");
			form.mini_price.focus();
			return false;
		}
	}
	return true;
}
function changerate(rate){  
	document.bulkMailForm.rate.value=rate;
	if(rate=="%") {
		document.bulkMailForm.amount_floor.disabled=false;
	} else {
		document.bulkMailForm.amount_floor.disabled=true;
	}
}
function nomoney(temp){  
	if(temp==1){
		document.bulkMailForm.mini_price.value="";
		document.bulkMailForm.mini_price.disabled=true;
		document.bulkMailForm.mini_price.style.background='#F0F0F0';
		document.bulkMailForm.checksale[0].checked=true;
	} else {
		document.bulkMailForm.mini_price.value="0";
		document.bulkMailForm.mini_price.disabled=false;
		document.bulkMailForm.mini_price.style.background='white';
		document.bulkMailForm.checksale[1].checked=true;
	}
}
function nonum(temp){  
	if(temp==1){
		document.bulkMailForm.issue_tot_no.value="";
		document.bulkMailForm.issue_tot_no.disabled=true;
		document.bulkMailForm.issue_tot_no.style.background='#F0F0F0';
		document.form1.checknum[0].checked=true;
	} else {
		document.form1.issue_tot_no.value="0";
		document.bulkMailForm.issue_tot_no.disabled=false;
		document.bulkMailForm.issue_tot_no.style.background='white';
		document.bulkMailForm.checknum[1].checked=true;
	}
}
function ViewLayer(layer,display){
	if(document.all){
		document.all[layer].style.display=display;
	} else if(document.getElementById){
		document.getElementByld[layer].style.display=display;
	} else if(document.layers){
		document.layers[layer].display=display;
	}
}
function ChoiceProduct(){
	window.open("about:blank","coupon_product","width=245,height=140,scrollbars=no");
	document.form2.submit();
}

</script>
<form name=form2 action="coupon_productchoice.php" method=post target=coupon_product>
			</form>
<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="8"></td>
	</tr>
	<tr>
		<td>
			<table width="100%" border=0 cellpadding=0 cellspacing=0>
				<tr>
					<td><IMG src="/admin/images/market_bulkmail_title.gif"  ALT="대용량메일 발송관리"></td>
				</tr>
				<tr>
					<td width="100%" background="images/title_bg.gif" height="21"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="3"></td>
	</tr>
	<tr>
		<td style="padding-bottom:3pt;"> </td>
	</tr>
	<tr>
		<td>
		<?
		$sql = "SELECT shopname,info_email FROM tblshopinfo limit 1";
		$result = mysql_query($sql,get_db_conn());		
		if($result){
			$senderName = mysql_result($result,0,0);
			$senderEmail = mysql_result($result,0,1);
		}
		
		$sql = "select group_code,group_name from tblmembergroup";
		$result = mysql_query($sql,get_db_conn());
		$memberGroupLists = array();
		if($result){
			while($row = mysql_fetch_assoc($result)){
				array_push($memberGroupLists,$row);
			}
		}
		
		
		$sql = "select gidx,gname from bulkmail_group";
		$result = mysql_query($sql,get_db_conn());
		$mailGroupLists = array();
		if($result){
			while($row = mysql_fetch_assoc($result)){
				array_push($mailGroupLists,$row);
			}
		}
			
		?>	
			<form action="/admin/bulkmail/preview.php" id="previewForm" name="previewForm" target="mailPreview" method="post">
				<input type="hidden" name="contents" value="" />
				<input type="hidden" name="sender" value="" />
				<input type="hidden" name="receiver" value="" />
				<input type="hidden" name="subject" value="" />
				<input type="hidden" name="rejectMsg" value="" />
			</form>
						
			<form action="/admin/bulkmail/process.php" name="bulkMailForm" id="bulkMailform" method="post" enctype="multipart/form-data" onsubmit="javascript:return checkBulkForm(this);">
				<input type="hidden" name="act" value="send" />
				<input type="hidden" name="campaignType" value="1" />
				<input type="hidden" name="receiverName" value="[$name]"  style="width:400">
				<div>잔여수량 : <?=number_format($bulkmail->ableCount)?> 건<br />
					<span style="color:red"> * 발송 대상 수가 잔여수량 보다 클 경우 잔여수량 초과 분에 대해서는 메일이 발송되지 않습니다.</span>
				</div>
				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="formTbl">
					<tr>
						<th colspan="2">보내는 사람</th>
						<td>이름 : <input type="text" name="senderName" value="<?=$senderName?>" style="margin-right:10px;" />
							이메일 주소 : <input type="text" name="senderEmail" value="<?=$senderEmail?>" />
						</td>
					</tr>
					<tr>
						<th rowspan="2" style="width:80px;">받는사람</th>
						<th style="width:80px;">대상 구분</th>
						<td>
							<input type="radio" name="targetType" value="member" onclick="javascript:chgTarget(this)" checked="checked" />회원
							<input type="radio" name="targetType" value="csv" onclick="javascript:chgTarget(this)" />엑셀(CSV) 파일 등록
							<input type="radio" name="targetType" value="input" onclick="javascript:chgTarget(this)" />직접주소입력
						</td>
					</tr>
					<tr>
						<th>선택</th>
						<td>
						<div id="memberSet" style="width:100%;">
							<table border="0" cellpadding="0" cellspacing="0"  class="formTbl">
								<tr>
									<td><input type="radio" name="memberType" value="membergroup" onclick="javascript:refMemberType(this);" checked="checked" />회원그룹 선택
										<select name="membergroup" id="membergroup_sel">
											<option value="all">전체그룹</option>
											<? for($i=0;$i<count($memberGroupLists);$i++){ ?>
											<option value="<?=$memberGroupLists[$i]['group_code']?>"><?=$memberGroupLists[$i]['group_name']?></option>
											<? } ?>
										</select>
									</td>
									<td><input type="button" value="회원그룹관리" onclick="javascript:goMenu('3','/admin/member_groupnew.php')" /></td>
								</tr>								
								<tr>
									<td><input type="radio" name="memberType" value="mailgroup" onclick="javascript:refMemberType(this);"/>발송그룹 선택
										<select name="mailgroup" id="mailgroup_sel">
											<option value="">그룹선택</option>
											<? for($i=0;$i<count($mailGroupLists);$i++){ ?>
											<option value="<?=$mailGroupLists[$i]['gidx']?>"><?=$mailGroupLists[$i]['gname']?></option>
											<? } ?>
										</select>
									</td>
									<td><a href="bulkmail.php?act=group"><input type="button" value="발송그룹관리" /></a></td>
								</tr>	
								<tr>
									<td colspan="2">수신거부 포함 여부 ( <input type="checkbox" name="addreject" value="0" />포함하지 않음 / <input type="checkbox" name="addreject" value="1" /> 포함)
							</table>
						</div>
						<div id="csvSet" style="width:100%; display:none">
							<input type="file" name="csvFile" value="" />
							<?
							$filepath=$_SERVER['DOCUMENT_ROOT']."/data/groupmail/";
							$filename="bulkmail.csv";							
							$csvfile = $filepath.$filename;
							$filestr = '';
							if(file_exists($csvfile) && is_file($csvfile) && filesize($csvfile)){
								$filestr = '<br>'.date("Y/m/d h:i:s",filemtime($csvfile)).' 에 생성된 파일이 있습니다.<br> 파일을 등록하지 않으면 마지막 저장 파일로 발송됩니다.';
							}
							echo $filestr;
							?>
						</div>
						<div id="inputSet" style="width:100%; display:none"> 
						<table border="0" cellpadding="0" cellspacing="0"  class="formTbl">
							<caption>입력후 추가 를 누르시면 등록 됩니다.
							<tr>
								<th>Email</th>
								<th>이름</th>
								<th>관리</th>
							</tr>
							<tr>							
								<td><input type="text" name="new_email" id="new_email" value="" /></td>
								<td><input type="text" name="new_name" id="new_name" value="" /></td>
								<td><input type="button" name="inputAdd" onclick="javascript:addInputRow(this)" value="추가" /></td>
							</tr>
							</table>
						</div>
						</td>
					</tr>
					<tr>
						<th colspan="2">메일구분</th>
						<td>
							<select name="mailType">
								<option value="뉴스레터">뉴스레터</option>
								<option value="상품홍보">상품홍보</option>
								<option value="기타">기타</option>
								<option value="긴급공지">긴급공지</option>
							</select>
						</td>
					</tr>
					<tr>
						<th colspan="2">메일제목</th>
						<td><input type="text" name="subject" value="" style="width:98%" /><br />* 제목에 받는 분의 이름을 사용 할 경우 이름 부분에 [$name] 를 넣으면 됩니다.<br />ex: [$name] 님 안녕하세요.</td>
					</tr>
					
					<tr>
						<th colspan="2">할인쿠폰기능</th>
						<td><input type="radio" name="setCoupon" value="0" checked="checked" onclick="javascript:toggleCoupon(this)" />미사용&nbsp;<input type="radio" name="setCoupon" value="1" onclick="javascript:toggleCoupon(this)" />사용				
						<script language="javascript" type="text/javascript">
						function toggleCoupon(el){
							if($(el).val() == '1'){
								$('#couponFormDiv').css('display','');
							}else{
								$('#couponFormDiv').css('display','none');
							}
						}
						</script>
						</td>
					</tr>
					<!-- <tr>
						<th colspan="2">SMS 안내기능</th>
						<td>&nbsp;</td>
					</tr> -->					
				</table>
				<div id="couponFormDiv" style="display:none">
				<input type="hidden" name="coupon_name" value="[대량메일발송용쿠폰]<?=date('Y-m-d H')?>" />
				<input type="hidden" name="productcode" value="ALL" />
				<input type="hidden" name="issue_type" value="O" />
				<input type="hidden" name="detail_auto" value="N" />
				<input type="hidden" name="use_point" value="Y" />
				<input type="hidden" name="use_con_type1" value="Y">
				<input type="hidden" name="description" value="대량 메일 발송시 설정된 쿠폰" />
				<span style="color:red">** 쿠폰을 설정 하신 경우 컨텐츠에 [$couponcode] 를 넣으셔야 해당 부분에 쿠폰 코드가 표시 됩니다.</span>
				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="formTbl">
					<caption> 쿠폰 설정</caption>
					<tr>					
						<th rowspan="2" style="width:80px;">유효기간</th>
						<td>
							<input type="radio" value="D" name="time">기간설정 : <input onfocus="this.blur();" onclick="Calendar(this)" size="12" name="date_start" value="<?=date('Y-m-d')?>" class="input_selected"> 부터 <input  onfocus="this.blur();" onclick="Calendar(this)" size="12" name="date_end" value="<?=date('Y-m-d',strtotime('+1 week'))?>" class="input_selected"> 까지 사용가능<span class="font_orange">(유효기간 마지막일 23시59분59초 까지)</span> </td>
						</tr>
						<tr>
							<td><input type="radio" CHECKED value="P" name="time">발행 후 <input onkeyup="strnumkeyup(this);" style="PADDING-RIGHT: 3px; TEXT-ALIGN: right" maxLength=3 size=4 name=peorid class="input">일 동안 사용가능<span class="font_orange">(유효기간 마지막일 23시59분59초 까지)</span> </td>
					</tr>
					<tr>
						<th>쿠폰종류 선택</th>
						<td>
							<SELECT style="WIDTH: 100px" name="sale_type" class="select">
								<OPTION value="-" selected>할인 쿠폰</OPTION> 
								<OPTION value="+">적립 쿠폰</OPTION>
							</SELECT>
						<span class="font_orange"> * 할인쿠폰은 구매시 즉시 할인되며, 적립쿠폰은 구매시 추가 적립금이 지급됩니다.</span>
						</td>
					</tr>	
					<tr>
						<th>금액/할인율 선택</th>
						<td>
							<SELECT style="WIDTH: 100px" onchange=changerate(options.value); name=sale2 class="select">
								<OPTION value=원 selected>금액</OPTION> <OPTION value=%>할인(적립)율</OPTION>
							</SELECT>
							→ 
							<input onkeyup="strnumkeyup(this);" style="PADDING-RIGHT: 5px; TEXT-ALIGN: right" maxLength=10 size=10 name=sale_money class="input"> <input class="input_hide1" readOnly size=1 value=원 name=rate></td>
					</tr>			
					<tr>
						<th>금액절사</th>
						<td class="td_con1">
						<SELECT disabled name=amount_floor class="select">
<? 
					$arfloor = array(1=>"일원단위, 예)12344 → 12340","십원단위, 예)12344 → 12300","백원단위, 예)12344 → 12000","천원단위, 예)12344 → 10000");
					$arcnt = count($arfloor);
					for($i=1;$i<$arcnt;$i++){
						echo "<option value=\"".$i."\"";
						if($amount_floor==$i) echo " selected";
						echo ">".$arfloor[$i]."</option>";
					}
?>
					</SELECT>
						</td>
					</tr>	
					<tr>
						<th>쿠폰 결제 금액</th>
						<td>
							<input onclick=nomoney(1) type="radio" CHECKED name=checksale>제한 없음  &nbsp;
							<input onclick=nomoney(0) type="radio" name=checksale><input onkeyup=strnumkeyup(this); disabled maxLength=10 size=10 name=mini_price class="input_disabled">원 이상 주문시 가능		<SCRIPT>nomoney(1);</SCRIPT>
						</td>
					</tr>				
					<tr>
						<th>쿠폰사용가능 결제방법</th>
						<td>
							<input type="radio" CHECKED value=N name=bank_only>제한 없음  &nbsp;
							<input type="radio" value=Y name=bank_only><B>현금 결제</B>만 가능(실시간 계좌이체 포함)
						</td>
					</tr>	
					<tr>
						<th>쿠폰 적용 상품군</th>
						<td><input style="width:400px" onclick="alert('변경은 [선택하기]버튼을 클릭하시면 됩니다.')" readOnly size="64" value="전체상품" name="productname" class="input"><a href="javascript:ChoiceProduct();"><img src="images/btn_select2.gif" width="76" height="28" border="0" hspace="2"></a></td>
					</tr>					
				</table>
				</div>
				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="formTbl">
					<tr>
						<td colspan="2">
						<span style="color:orange">* 내용에 [$name] 를 넣으시면 해당 부분은 받는 사람의 이름으로 변환 됩니다.</span>
						<textarea name="mailContents" style="width:98%; height:380px;" lang="ej-editor1"></textarea>
						</td>
					</tr>
					<tr>
						<th>수신거부안내</th>
						<td><textarea name="rejectMsg" style="width:98%; height:50px;">본 메일은 정보통신망률 등 관련규정에 의거하여 수신동의하신 회원에게 발송되었습니다.
메일 수신을 원치 않으시면 <?=$bulkmail->shopurl?> 에 로그인후 마이페이지에서 설정을 변경해주세요.
</textarea>
						</td>
					</tr>
				</table>
				<div style="width:100%; text-align:center; padding-top:10px;">
				<input type="button" value="불법 스팸방지 안내" onclick="javascript:sapmguide();" style="margin-right:5px;" /><input type="button" value="미리보기" onclick="javascript:preview()" style="margin-right:5px;" /><input type="submit" value="발송" />
				</div>
			</form>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>
			<table width="100%" border=0 cellpadding=0 cellspacing=0>
				<tr>
					<td><IMG src="/admin/images/manual_top1.gif" width=15 height="45" ALT=""></td>
					<td><IMG src="/admin/images/manual_title.gif" width=113 height="45" ALT=""></td>
					<td width="100%" background="images/manual_bg.gif" height="35"></td>
					<td background="images/manual_bg.gif"></td>
					<td background="images/manual_bg.gif"><IMG src="/admin/images/manual_top2.gif" width=18 height="45" ALT=""></td>
				</tr>
				<tr>
					<td background="images/manual_left1.gif"></td>
					<td COLSPAN=3 width="100%" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;" class="menual_bg"> </td>
					<td background="images/manual_right1.gif"></td>
				</tr>
				<tr>
					<td><IMG src="/admin/images/manual_left2.gif" width=15 HEIGHT=8 ALT=""></td>
					<td COLSPAN=3 background="images/manual_down.gif"></td>
					<td><IMG src="/admin/images/manual_right2.gif" width=18 HEIGHT=8 ALT=""></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="50"></td>
	</tr>
</table>
<script language="javascript" type="text/javascript">
function preview(){
	alert('실제 메일 수신시 운영 사이트 정책 및 특성에 따라 다소 차이가 있을수 있습니다.');
	var sf = $('#bulkMailform');
	var tf = $('#previewForm');
	
	$(tf).find('input[name=subject]').val($(sf).find('input[name=subject]').val()); // 제목
	$(tf).find('input[name=sender]').val($(sf).find('input[name=senderName]').val()+'('+$(sf).find('input[name=senderEmail]').val()+')'); // 제목
	$(tf).find('input[name=contents]').val($(sf).find('textarea[name=mailContents]').val()); // 제목
	$(tf).find('input[name=rejectMsg]').val($(sf).find('textarea[name=rejectMsg]').val()); // 제목

	var targetType = $(sf).find('input:radio[name=targetType]:checked').val();
	if(targetType == 'member'){		
		if($(sf).find('input:radio[name=memberType]:checked').val() == 'membergroup'){
			$(tf).find('input[name=receiver]').val($('#membergroup_sel option:selected').html());
		}else{
			$(tf).find('input[name=receiver]').val($('#membergroup_sel option:selected').html());
		}				
	}else if(targetType=='csv'){
		$(tf).find('input[name=receiver]').val('CSV업로드목록');
	}else if(targetType =='input'){
		$(tf).find('input[name=receiver]').val('직접 입력 발송 목록');	
	}
	
	window.open("about:blank","mailPreview","width=800,height=700,scrollbars=no");
	$(tf).submit();
}

function sapmguide(){
	var guideWin = window.open("/admin/bulkmail/spamguide.php","spamguide","width=400,height=500");
}

function chgTarget(el){
	var trgarr = ['member','csv','input'];
	var tgname = $(el).val();
	for(i=0;i<trgarr.length;i++){
		name = trgarr[i];
		if(tgname == name){		
			$('#'+name+'Set').css('display','');
		}else{
			$('#'+name+'Set').css('display','none');
		}
	}
}

function refMemberType(el){
	var trgarr = ['membergroup','mailgroup'];
	var tgname = $(el).val();
	for(i=0;i<trgarr.length;i++){
		name = trgarr[i];
		if(tgname == name){
			$('#'+name+'_sel').removeAttr('disabled');
		}else{
			$('#'+name+'_sel').attr('disabled','disabled');			
		}
	}
}

function addInputRow(el){
	var email	= $('#new_email').val();
	var name	= $('#new_name').val();
	var str = '';
	if(email.match(/^(\w+)@(\w+)[.](\w+)$/ig) == null && email.match(/^(\w+)@(\w+)[.](\w+)[.](\w+)$/ig) == null){
		alert('메일 주소가 올바르지 않습니다.');
		$('#new_email').focus();
	}else{
		str = '<tr><td><input type="text" name="email[]" value="'+email+'" style="border:0px" readonly /></td><td><input type="text" name="name[]"  value="'+name+'" style="border:0px" readonly /></td><td><input type="button"  onclick="javascript:removeInputRow(this)" value="삭제" /></td></tr>';
		$('#inputSet>table').append(str);
		$('#new_email').val('');
		$('#new_name').val('');
	}
}

function removeInputRow(el){
	$(el).parent().parent().remove();
	
}

$(function(){
	$('input:radio[name="targetType"]:checked').each(function(index,el) {
		chgTarget(el);
	});
	
	$('input:radio[name="memberType"]:checked').each(function(index,el) {
		refMemberType(el)
	});
});
</script>
