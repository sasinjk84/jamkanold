<!-- �����Ϳ� ���� ȣ�� -->
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
<!-- # �����Ϳ� ���� ȣ�� -->
<script type="text/javascript" src="calendar.js.php"></script>
<script language="JavaScript">
function checkBulkForm(form){
	if($.trim(form.senderName.value) == ''){
		alert('�߼��� �̸��� �Է��ϼ���.');
		form.senderName.focus();
		return false;
	}
	if($.trim(form.senderEmail.value) == ''){
		alert('�߼��� �̸����� �Է��ϼ���.');
		form.senderEmail.focus();
		return false;
	}
	if($.trim(form.subject.value) == ''){
		alert('���������� �Է��ϼ���.');
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
			alert("���� ��ȿ�Ⱓ ������ �߸��Ǿ����ϴ�.\n\n�ٽ� Ȯ���Ͻñ� �ٶ��ϴ�.");
			form.date_start.focus();
			return false;
		}
		content+="* ���� ��ȿ�Ⱓ : "+form.date_start.value+" ~ "+form.date_end.value+" ����\n\n";
	}else{
		if (form.peorid.value.length==0) {
			alert("���� ���Ⱓ�� �Է��ϼ���.");
			form.peorid.focus();
			return false;
		} else if (!IsNumeric(document.bulkMailForm.peorid.value)) {
			alert("���� ���Ⱓ�� ���ڸ� �Է� �����մϴ�.");
			form.peorid.focus();
			return false;
		}
	}
	if (form.sale_money.value.length==0) {
		alert("���� ���� �ݾ�/���η��� �Է��ϼ���.");
		form.sale_money.focus();
		return false;
	} else if (!IsNumeric(form.sale_money.value)) {
		alert("���� ���� �ݾ�/���η��� ���ڸ� �Է� �����մϴ�.(�Ҽ��� �Է� �ȵ�)");
		form.sale_money.focus();
		return false;
	}
	if(form.sale2.selectedIndex==1 && form.sale_money.value>=100){
		alert("���� ���η��� 100���� �۾ƾ� �մϴ�.");
		form.sale_money.focus();
		return false;
	}	

	if(form.productcode.value.length==18 && form.checksale[1].checked==true && form.use_con_type2.checked!=true) {
		alert("������ �ѻ�ǰ�� ����ɰ�� ���űݾ׿� ������ �����ϴ�.");
		nomoney(1);
	}
	if(form.checksale[1].checked==true){
		if(form.mini_price.value.length==0){
			alert("���� ���� �ݾ��� �Է��ϼ���.");
			document.bulkMailForm.mini_price.focus();
			return false;
		}else if(!IsNumeric(form.mini_price.value)){
			alert("���� ���� �ݾ��� ���ڸ� �Է� �����մϴ�.");
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
					<td><IMG src="/admin/images/market_bulkmail_title.gif"  ALT="��뷮���� �߼۰���"></td>
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
				<div>�ܿ����� : <?=number_format($bulkmail->ableCount)?> ��<br />
					<span style="color:red"> * �߼� ��� ���� �ܿ����� ���� Ŭ ��� �ܿ����� �ʰ� �п� ���ؼ��� ������ �߼۵��� �ʽ��ϴ�.</span>
				</div>
				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="formTbl">
					<tr>
						<th colspan="2">������ ���</th>
						<td>�̸� : <input type="text" name="senderName" value="<?=$senderName?>" style="margin-right:10px;" />
							�̸��� �ּ� : <input type="text" name="senderEmail" value="<?=$senderEmail?>" />
						</td>
					</tr>
					<tr>
						<th rowspan="2" style="width:80px;">�޴»��</th>
						<th style="width:80px;">��� ����</th>
						<td>
							<input type="radio" name="targetType" value="member" onclick="javascript:chgTarget(this)" checked="checked" />ȸ��
							<input type="radio" name="targetType" value="csv" onclick="javascript:chgTarget(this)" />����(CSV) ���� ���
							<input type="radio" name="targetType" value="input" onclick="javascript:chgTarget(this)" />�����ּ��Է�
						</td>
					</tr>
					<tr>
						<th>����</th>
						<td>
						<div id="memberSet" style="width:100%;">
							<table border="0" cellpadding="0" cellspacing="0"  class="formTbl">
								<tr>
									<td><input type="radio" name="memberType" value="membergroup" onclick="javascript:refMemberType(this);" checked="checked" />ȸ���׷� ����
										<select name="membergroup" id="membergroup_sel">
											<option value="all">��ü�׷�</option>
											<? for($i=0;$i<count($memberGroupLists);$i++){ ?>
											<option value="<?=$memberGroupLists[$i]['group_code']?>"><?=$memberGroupLists[$i]['group_name']?></option>
											<? } ?>
										</select>
									</td>
									<td><input type="button" value="ȸ���׷����" onclick="javascript:goMenu('3','/admin/member_groupnew.php')" /></td>
								</tr>								
								<tr>
									<td><input type="radio" name="memberType" value="mailgroup" onclick="javascript:refMemberType(this);"/>�߼۱׷� ����
										<select name="mailgroup" id="mailgroup_sel">
											<option value="">�׷켱��</option>
											<? for($i=0;$i<count($mailGroupLists);$i++){ ?>
											<option value="<?=$mailGroupLists[$i]['gidx']?>"><?=$mailGroupLists[$i]['gname']?></option>
											<? } ?>
										</select>
									</td>
									<td><a href="bulkmail.php?act=group"><input type="button" value="�߼۱׷����" /></a></td>
								</tr>	
								<tr>
									<td colspan="2">���Űź� ���� ���� ( <input type="checkbox" name="addreject" value="0" />�������� ���� / <input type="checkbox" name="addreject" value="1" /> ����)
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
								$filestr = '<br>'.date("Y/m/d h:i:s",filemtime($csvfile)).' �� ������ ������ �ֽ��ϴ�.<br> ������ ������� ������ ������ ���� ���Ϸ� �߼۵˴ϴ�.';
							}
							echo $filestr;
							?>
						</div>
						<div id="inputSet" style="width:100%; display:none"> 
						<table border="0" cellpadding="0" cellspacing="0"  class="formTbl">
							<caption>�Է��� �߰� �� �����ø� ��� �˴ϴ�.
							<tr>
								<th>Email</th>
								<th>�̸�</th>
								<th>����</th>
							</tr>
							<tr>							
								<td><input type="text" name="new_email" id="new_email" value="" /></td>
								<td><input type="text" name="new_name" id="new_name" value="" /></td>
								<td><input type="button" name="inputAdd" onclick="javascript:addInputRow(this)" value="�߰�" /></td>
							</tr>
							</table>
						</div>
						</td>
					</tr>
					<tr>
						<th colspan="2">���ϱ���</th>
						<td>
							<select name="mailType">
								<option value="��������">��������</option>
								<option value="��ǰȫ��">��ǰȫ��</option>
								<option value="��Ÿ">��Ÿ</option>
								<option value="��ް���">��ް���</option>
							</select>
						</td>
					</tr>
					<tr>
						<th colspan="2">��������</th>
						<td><input type="text" name="subject" value="" style="width:98%" /><br />* ���� �޴� ���� �̸��� ��� �� ��� �̸� �κп� [$name] �� ������ �˴ϴ�.<br />ex: [$name] �� �ȳ��ϼ���.</td>
					</tr>
					
					<tr>
						<th colspan="2">�����������</th>
						<td><input type="radio" name="setCoupon" value="0" checked="checked" onclick="javascript:toggleCoupon(this)" />�̻��&nbsp;<input type="radio" name="setCoupon" value="1" onclick="javascript:toggleCoupon(this)" />���				
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
						<th colspan="2">SMS �ȳ����</th>
						<td>&nbsp;</td>
					</tr> -->					
				</table>
				<div id="couponFormDiv" style="display:none">
				<input type="hidden" name="coupon_name" value="[�뷮���Ϲ߼ۿ�����]<?=date('Y-m-d H')?>" />
				<input type="hidden" name="productcode" value="ALL" />
				<input type="hidden" name="issue_type" value="O" />
				<input type="hidden" name="detail_auto" value="N" />
				<input type="hidden" name="use_point" value="Y" />
				<input type="hidden" name="use_con_type1" value="Y">
				<input type="hidden" name="description" value="�뷮 ���� �߼۽� ������ ����" />
				<span style="color:red">** ������ ���� �Ͻ� ��� �������� [$couponcode] �� �����ž� �ش� �κп� ���� �ڵ尡 ǥ�� �˴ϴ�.</span>
				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="formTbl">
					<caption> ���� ����</caption>
					<tr>					
						<th rowspan="2" style="width:80px;">��ȿ�Ⱓ</th>
						<td>
							<input type="radio" value="D" name="time">�Ⱓ���� : <input onfocus="this.blur();" onclick="Calendar(this)" size="12" name="date_start" value="<?=date('Y-m-d')?>" class="input_selected"> ���� <input  onfocus="this.blur();" onclick="Calendar(this)" size="12" name="date_end" value="<?=date('Y-m-d',strtotime('+1 week'))?>" class="input_selected"> ���� ��밡��<span class="font_orange">(��ȿ�Ⱓ �������� 23��59��59�� ����)</span> </td>
						</tr>
						<tr>
							<td><input type="radio" CHECKED value="P" name="time">���� �� <input onkeyup="strnumkeyup(this);" style="PADDING-RIGHT: 3px; TEXT-ALIGN: right" maxLength=3 size=4 name=peorid class="input">�� ���� ��밡��<span class="font_orange">(��ȿ�Ⱓ �������� 23��59��59�� ����)</span> </td>
					</tr>
					<tr>
						<th>�������� ����</th>
						<td>
							<SELECT style="WIDTH: 100px" name="sale_type" class="select">
								<OPTION value="-" selected>���� ����</OPTION> 
								<OPTION value="+">���� ����</OPTION>
							</SELECT>
						<span class="font_orange"> * ���������� ���Ž� ��� ���εǸ�, ���������� ���Ž� �߰� �������� ���޵˴ϴ�.</span>
						</td>
					</tr>	
					<tr>
						<th>�ݾ�/������ ����</th>
						<td>
							<SELECT style="WIDTH: 100px" onchange=changerate(options.value); name=sale2 class="select">
								<OPTION value=�� selected>�ݾ�</OPTION> <OPTION value=%>����(����)��</OPTION>
							</SELECT>
							�� 
							<input onkeyup="strnumkeyup(this);" style="PADDING-RIGHT: 5px; TEXT-ALIGN: right" maxLength=10 size=10 name=sale_money class="input"> <input class="input_hide1" readOnly size=1 value=�� name=rate></td>
					</tr>			
					<tr>
						<th>�ݾ�����</th>
						<td class="td_con1">
						<SELECT disabled name=amount_floor class="select">
<? 
					$arfloor = array(1=>"�Ͽ�����, ��)12344 �� 12340","�ʿ�����, ��)12344 �� 12300","�������, ��)12344 �� 12000","õ������, ��)12344 �� 10000");
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
						<th>���� ���� �ݾ�</th>
						<td>
							<input onclick=nomoney(1) type="radio" CHECKED name=checksale>���� ����  &nbsp;
							<input onclick=nomoney(0) type="radio" name=checksale><input onkeyup=strnumkeyup(this); disabled maxLength=10 size=10 name=mini_price class="input_disabled">�� �̻� �ֹ��� ����		<SCRIPT>nomoney(1);</SCRIPT>
						</td>
					</tr>				
					<tr>
						<th>������밡�� �������</th>
						<td>
							<input type="radio" CHECKED value=N name=bank_only>���� ����  &nbsp;
							<input type="radio" value=Y name=bank_only><B>���� ����</B>�� ����(�ǽð� ������ü ����)
						</td>
					</tr>	
					<tr>
						<th>���� ���� ��ǰ��</th>
						<td><input style="width:400px" onclick="alert('������ [�����ϱ�]��ư�� Ŭ���Ͻø� �˴ϴ�.')" readOnly size="64" value="��ü��ǰ" name="productname" class="input"><a href="javascript:ChoiceProduct();"><img src="images/btn_select2.gif" width="76" height="28" border="0" hspace="2"></a></td>
					</tr>					
				</table>
				</div>
				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="formTbl">
					<tr>
						<td colspan="2">
						<span style="color:orange">* ���뿡 [$name] �� �����ø� �ش� �κ��� �޴� ����� �̸����� ��ȯ �˴ϴ�.</span>
						<textarea name="mailContents" style="width:98%; height:380px;" lang="ej-editor1"></textarea>
						</td>
					</tr>
					<tr>
						<th>���Űźξȳ�</th>
						<td><textarea name="rejectMsg" style="width:98%; height:50px;">�� ������ ������Ÿ��� �� ���ñ����� �ǰ��Ͽ� ���ŵ����Ͻ� ȸ������ �߼۵Ǿ����ϴ�.
���� ������ ��ġ �����ø� <?=$bulkmail->shopurl?> �� �α����� �������������� ������ �������ּ���.
</textarea>
						</td>
					</tr>
				</table>
				<div style="width:100%; text-align:center; padding-top:10px;">
				<input type="button" value="�ҹ� ���Թ��� �ȳ�" onclick="javascript:sapmguide();" style="margin-right:5px;" /><input type="button" value="�̸�����" onclick="javascript:preview()" style="margin-right:5px;" /><input type="submit" value="�߼�" />
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
	alert('���� ���� ���Ž� � ����Ʈ ��å �� Ư���� ���� �ټ� ���̰� ������ �ֽ��ϴ�.');
	var sf = $('#bulkMailform');
	var tf = $('#previewForm');
	
	$(tf).find('input[name=subject]').val($(sf).find('input[name=subject]').val()); // ����
	$(tf).find('input[name=sender]').val($(sf).find('input[name=senderName]').val()+'('+$(sf).find('input[name=senderEmail]').val()+')'); // ����
	$(tf).find('input[name=contents]').val($(sf).find('textarea[name=mailContents]').val()); // ����
	$(tf).find('input[name=rejectMsg]').val($(sf).find('textarea[name=rejectMsg]').val()); // ����

	var targetType = $(sf).find('input:radio[name=targetType]:checked').val();
	if(targetType == 'member'){		
		if($(sf).find('input:radio[name=memberType]:checked').val() == 'membergroup'){
			$(tf).find('input[name=receiver]').val($('#membergroup_sel option:selected').html());
		}else{
			$(tf).find('input[name=receiver]').val($('#membergroup_sel option:selected').html());
		}				
	}else if(targetType=='csv'){
		$(tf).find('input[name=receiver]').val('CSV���ε���');
	}else if(targetType =='input'){
		$(tf).find('input[name=receiver]').val('���� �Է� �߼� ���');	
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
		alert('���� �ּҰ� �ùٸ��� �ʽ��ϴ�.');
		$('#new_email').focus();
	}else{
		str = '<tr><td><input type="text" name="email[]" value="'+email+'" style="border:0px" readonly /></td><td><input type="text" name="name[]"  value="'+name+'" style="border:0px" readonly /></td><td><input type="button"  onclick="javascript:removeInputRow(this)" value="����" /></td></tr>';
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
