<script language="javascript" type="text/javascript">
function CheckForm(form) {
	if(form.running.value=="1"){
		alert('�ڵ� ������ �Դϴ�. ��ø� �� ��ٷ� �ּ���.');
		return;
	}
	if(form.coupon_name.value.length==0) {
		alert("���� �̸��� �Է��ϼ���.");
		form.coupon_name.focus();
		return;
	}
	if(CheckLength(form.coupon_name)>100) {
		alert("�Է��� �� �ִ� ��� ������ �ʰ��Ǿ����ϴ�.\n\n" + "�ѱ� 50�� �̳� Ȥ�� ����/����/��ȣ 100�� �̳��� �Է��� �����մϴ�.");
		form.coupon_name.focus();
		return;
	}
	content ="�Ʒ��� ������ Ȯ���Ͻð�, ����Ͻø� �˴ϴ�.\n\n"
			 +"--------------------------------------------\n\n"
			 +"* ���� �̸� : "+form.coupon_name.value+"\n\n";
	
	if (form.time[0].checked==true) {
		date = "<?=date("Y-m-d");?>";
		if (form.date_start.value<date || form.date_end.value<date || form.date_start.value>form.date_end.value) {
			alert("���� ��ȿ�Ⱓ ������ �߸��Ǿ����ϴ�.\n\n�ٽ� Ȯ���Ͻñ� �ٶ��ϴ�.");
			form.date_start.focus();
			return;
		}
		content+="* ���� ��ȿ�Ⱓ : "+form.date_start.value+" ~ "+form.date_end.value+" ����\n\n";
	} else {
		if (form.peorid.value.length==0) {
			alert("���� ���Ⱓ�� �Է��ϼ���.");
			form.peorid.focus();
			return;
		} else if (!IsNumeric(document.form1.peorid.value)) {
			alert("���� ���Ⱓ�� ���ڸ� �Է� �����մϴ�.");
			form.peorid.focus();
			return;
		}
		content+="* ���� ���Ⱓ : "+form.peorid.value+"�� ����\n\n";
	}
	if (form.sale_money.value.length==0) {
		alert("���� ���� �ݾ�/���η��� �Է��ϼ���.");
		form.sale_money.focus();
		return;
	} else if (!IsNumeric(form.sale_money.value)) {
		alert("���� ���� �ݾ�/���η��� ���ڸ� �Է� �����մϴ�.(�Ҽ��� �Է� �ȵ�)");
		form.sale_money.focus();
		return;
	}
	if(form.sale2.selectedIndex==1 && form.sale_money.value>=100){
		alert("���� ���η��� 100���� �۾ƾ� �մϴ�.");
		form.sale_money.focus();
		return;
	}
	content+="* �������� : "+form.sale_type.options[form.sale_type.selectedIndex].text+"\n\n";
	content+="* ���� �ݾ�/���η� : "+form.sale_money.value+form.sale2.options[form.sale2.selectedIndex].value+"\n\n";
	if(form.bank_only[0].checked==true) content+="* ���� ��밡�� ������� : ���� ����\n\n";
	else content+="* ���� ��밡�� ������� : ���� ������ ����(�ǽð� ������ü ����)\n\n";

	if(form.order_limit[0].checked==true) content+="* ���� �ֹ� �ߺ���� : ���� ����\n\n";
	else content+="* ���� �ֹ� �ߺ���� : ���Ұ�\n\n";

	document.form1.productcode.value="";
	if(document.form1.codegbn[0].checked==true) {
		document.form1.productcode.value="ALL";
	} else {
		cnt=document.form1.codelist.options.length - 1;
		if(cnt<=0) {
			alert("���� ���� ��ǰ���� �����ϼ���.");
			return;
		}
		for(i=1;i<=cnt;i++) {
			document.form1.productcode.value+=document.form1.codelist.options[i].value+",";
			/*
			if(i==1) {
				document.form1.productcode.value+=document.form1.codelist.options[i].value;
			} else {
				document.form1.productcode.value+=","+document.form1.codelist.options[i].value;
			}
			*/
		}
	}

	if(form.productcode.value.length==18 && form.checksale[1].checked==true && form.use_con_type2.checked!=true) {
		alert("������ �ѻ�ǰ�� ����ɰ�� ���űݾ׿� ������ �����ϴ�.");
		nomoney(1);
	}
	if(form.checksale[1].checked==true){
		if(form.mini_price.value.length==0){
			alert("���� ���� �ݾ��� �Է��ϼ���.");
			document.form1.mini_price.focus();
			return;
		}else if(!IsNumeric(form.mini_price.value)){
			alert("���� ���� �ݾ��� ���ڸ� �Է� �����մϴ�.");
			form.mini_price.focus();
			return;
		}
		content+="* ���� ���� �ݾ� : "+form.mini_price.value+"�� �̻� ���Ž�\n\n";
	} else {
		content+="* ���� ���� �ݾ� : ���Ѿ���\n\n";
	}
	//content+="* �����ǰ�� : "+form.productname.value+"\n\n";
	if(form.etcapply_gift.checked==true) {
		content+="* ����ǰ���ܿ��� : �� ������ ����� ��� ����ǰ�� �������� ����\n\n";
	}
	if(form.issue_tot_no.value.length==0){
		alert("���� ������� �Է��ϼ���.");
		form.issue_tot_no.focus();
		return;
	}else if(!IsNumeric(form.issue_tot_no.value)){
		alert("���� ������� ���ڸ� �Է� �����մϴ�.(�Ҽ��� �Է� �ȵ�)");
		form.issue_tot_no.focus();
		return;
	}else if(form.issue_tot_no.value<=0) {
		alert("���� ���� �ż��� �Է��ϼ���.");
		form.issue_tot_no.focus();
		return;
	}
	content+="* ���� ������ : "+form.issue_tot_no.value+"��\n\n";
	

	
	content+="* �߱����� : ������������������\n\n";
	content+="* ���ѻ��� : ����������ð� ���û�� "+form.use_point.options[form.use_point.selectedIndex].text+"\n\n";
	
	content+="--------------------------------------------";
	if(confirm(content)){
		alert('�߱޼��� ���� ��� �ð��� ���� �ҿ�ɼ� �ֽ��ϴ�. �������� �ݰų� ���ΰ�ġ�� ���ð� �Ϸ� �޽����� ���ö� ���� ��ٷ� �ּ���');
		form.running.value="1";
		form.type.value="insert";
		form.submit();
	}
}
function changerate(rate){  
	document.form1.rate.value=rate;
	if(rate=="%") {
		document.form1.amount_floor.disabled=false;
	} else {
		document.form1.amount_floor.disabled=true;
	}
}
function nomoney(temp){  
	if(temp==1){
		document.form1.mini_price.value="";
		document.form1.mini_price.disabled=true;
		document.form1.mini_price.style.background='#F0F0F0';
		document.form1.checksale[0].checked=true;
	} else {
		document.form1.mini_price.value="0";
		document.form1.mini_price.disabled=false;
		document.form1.mini_price.style.background='white';
		document.form1.checksale[1].checked=true;
	}
}


function ChoiceProduct(){
	window.open("about:blank","coupon_product","width=245,height=140,scrollbars=no");
	document.form2.submit();
}

function ChangeCodegbn(gbn) {
	if(gbn=="A") {
		if(document.all){
			document.all["layer_codelist"].style.display="none";
		} else if(document.getElementById){
			document.getElementByld["layer_codelist"].style.display="none";
		} else if(document.layers){
			document.layers["layer_codelist"].display="none";
		}
		ViewLayer('layer1','none');
	} else if(gbn=="N") {
		if(document.all){
			document.all["layer_codelist"].style.display="";
		} else if(document.getElementById){
			document.getElementByld["layer_codelist"].style.display="";
		} else if(document.layers){
			document.layers["layer_codelist"].display="";
		}
		ViewLayer('layer1','block');
	}
}

function CodeDelete() {
	codelist=document.form1.codelist;
	for(i=1;i<codelist.options.length;i++) {
		if(codelist.options[i].selected==true){
			codelist.options[i]=null;
			cnt=codelist.options.length - 1;
			codelist.options[0].text = "------------------------- ���� ��ǰ���� �����ϼ���. -------------------------";
			return;
		}
	}
	alert("������ ��ǰ���� �����ϼ���.");
	codelist.focus();
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


</script>

<form name=form1 action="/admin/offcoupon/process.php" method=post>
	<input type=hidden name=type>
	<input type="hidden" name="running" value="0" />
	<input type="hidden" name="act" value="new">
	<input type=hidden name=productcode value="ALL">
	<input type="hidden" name="issue_type" value="P">
	<input type="hidden" name="repeat_id" value="N">
	<input type="hidden" name="repeat_ok" value="N" />
	<input type="hidden" name="description" value="������������������">
	<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
		<tr>
			<td height="8"></td>
		</tr>
		<tr>
			<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
					<TR>
						<TD><IMG SRC="images/market_couponnew_title.gif" ALT=""></TD>
					</tr>
					<tr>
						<TD width="100%" background="images/title_bg.gif" height="21"></TD>
					</TR>
				</TABLE>
			</td>
		</tr>
		<tr>
			<td height="3"></td>
		</tr>
		<tr>
			<td style="padding-bottom:3pt;">
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
					<TR>
						<TD><IMG SRC="images/distribute_01.gif"></TD>
						<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
						<TD><IMG SRC="images/distribute_03.gif"></TD>
					</TR>
					<TR>
						<TD background="images/distribute_04.gif"></TD>
						<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
						<TD width="100%" class="notice_blue">���� ��ȣ�� ���� ���� ����� ���� ������ ���� �μ� ��ü ���� �̿��� �������� ������ �����մϴ�.</TD>
						<TD background="images/distribute_07.gif"></TD>
					</TR>
					<TR>
						<TD><IMG SRC="images/distribute_08.gif"></TD>
						<TD COLSPAN=2 background="images/distribute_09.gif"></TD>
						<TD><IMG SRC="images/distribute_10.gif"></TD>
					</TR>
				</TABLE>
			</td>
		</tr>
		<tr>
			<td height="20"></td>
		</tr>
		<tr>
			<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
					<TR>
						<TD><IMG SRC="images/market_couponnew_stitle1.gif" WIDTH="192" HEIGHT=31 ALT=""></TD>
						<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
						<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
					</TR>
				</TABLE>
			</td>
		</tr>
		<tr>
			<td height=3></td>
		</tr>
		<tr>
			<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
					<TR>
						<TD><IMG SRC="images/distribute_01.gif" WIDTH=7 HEIGHT=7 ALT=""></TD>
						<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
						<TD><IMG SRC="images/distribute_03.gif" WIDTH=8 HEIGHT=7 ALT=""></TD>
					</TR>
					<TR>
						<TD background="images/distribute_04.gif"></TD>
						<TD class="notice_blue"><IMG SRC="images/distribute_img.gif"></TD>
						<TD width="100%" class="notice_blue">���� ����� �� �ֹ��ǿ� ���ؼ� �Ѱ��� ������ ����� �����մϴ�.</TD>
						<TD background="images/distribute_07.gif"></TD>
					</TR>
					<TR>
						<TD><IMG SRC="images/distribute_08.gif" WIDTH=7 HEIGHT=8 ALT=""></TD>
						<TD COLSPAN=2 background="images/distribute_09.gif"></TD>
						<TD><IMG SRC="images/distribute_10.gif" WIDTH=8 HEIGHT=8 ALT=""></TD>
					</TR>
				</TABLE>
			</td>
		</tr>
		<tr>
			<td height=3></td>
		</tr>
		<tr>
			<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0 style="table-layout:fixed">
					<col width=160>
					
							</col>
					
					<col width=>
					
							</col>
					
					<TR>
						<TD colspan=2 background="images/table_top_line.gif"></TD>
					</TR>
					<TR>
						<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">���� �̸�</TD>
						<TD class="td_con1">
							<INPUT maxLength=100 size=70 name=coupon_name class="input">
							<br>
							<span class="font_orange"><b>��)�� ������10% ���������̺�Ʈ~</b></span></TD>
					</TR>
					<TR>
						<TD colspan="2" background="images/table_con_line.gif"></TD>
					</TR>
					<TR>
						<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">��ȿ�Ⱓ</TD>
						<TD class="td_con1">
							<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
								<TR>
									<TD >
										<INPUT type=radio value=D name=time>
										�Ⱓ���� :
										<INPUT onfocus=this.blur(); onclick=Calendar(this) size=12 name=date_start value="<?=date('Y-m-d')?>" class="input_selected">
										����
										<INPUT  onfocus=this.blur(); onclick=Calendar(this) size=12 name=date_end value="<?=date('Y-m-d',strtotime('+1 week'))?>" class="input_selected">
										���� ��밡��<span class="font_orange">(��ȿ�Ⱓ �������� 23��59��59�� ����)</span> </TD>
								</TR>
								<TR>
									<TD >
										<INPUT type=radio CHECKED value=P name=time>
										���� ��
										<INPUT onkeyup=strnumkeyup(this); style="PADDING-RIGHT: 3px; TEXT-ALIGN: right" maxLength=3 size=4 name=peorid class="input">
										�� ���� ��밡��<span class="font_orange">(��ȿ�Ⱓ �������� 23��59��59�� ����)</span> </TD>
								</TR>
							</TABLE>
						</TD>
					</TR>
					<TR>
						<TD colspan="2" background="images/table_con_line.gif"></TD>
					</TR>
					<TR>
						<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�������� ����</TD>
						<TD class="td_con1">
							<SELECT style="WIDTH: 100px" name=sale_type class="select">
								<OPTION value=- selected>���� ����</OPTION>
								<OPTION value=+>���� ����</OPTION>
							</SELECT>
							<span class="font_orange"> * ���������� ���Ž� ��� ���εǸ�, ���������� ���Ž� �߰� �������� ���޵˴ϴ�.</span> </TD>
					</TR>
					<TR>
						<TD colspan="2" background="images/table_con_line.gif"></TD>
					</TR>
					<TR>
						<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�ݾ�/������ ����</TD>
						<TD class="td_con1">
							<SELECT style="WIDTH: 100px" onchange=changerate(options.value); name=sale2 class="select">
								<OPTION value=�� selected>�ݾ�</OPTION>
								<OPTION value=%>����(����)��</OPTION>
							</SELECT>
							��
							<INPUT onkeyup=strnumkeyup(this); style="PADDING-RIGHT: 5px; TEXT-ALIGN: right" maxLength=10 size=10 name=sale_money class="input">
							<INPUT class="input_hide1" readOnly size=1 value=�� name=rate>
						</TD>
					</TR>
					<TR>
						<TD colspan="2" background="images/table_con_line.gif"></TD>
					</TR>
					<TR>
						<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�ݾ�����</TD>
						<TD class="td_con1">
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
						</TD>
					</TR>
					<TR>
						<TD colspan="2" background="images/table_con_line.gif"></TD>
					</TR>
					<tr>
						<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">���� ���� �ݾ�</TD>
						<TD class="td_con1">
							<INPUT onclick=nomoney(1) type=radio CHECKED name=checksale>
							���� ����  &nbsp;
							<INPUT onclick=nomoney(0) type=radio name=checksale>
							<INPUT onkeyup=strnumkeyup(this); disabled maxLength=10 size=10 name=mini_price class="input_disabled">
							�� �̻� �ֹ��� ���� 
							<SCRIPT>nomoney(1);</SCRIPT> 
						</TD>
					</tr>
					<TR>
						<TD colspan="2" background="images/table_con_line.gif"></TD>
					</TR>
					<tr>
						<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">������밡�� �������</TD>
						<TD class="td_con1">
							<INPUT type=radio CHECKED value=N name=bank_only>
							���� ����  &nbsp;
							<INPUT type=radio value=Y name=bank_only>
							<B>���� ����</B>�� ����(�ǽð� ������ü ����) </TD>
					</tr>
					<TR>
						<TD colspan=2 background="images/table_top_line.gif"></TD>
					</TR>
					<tr>
						<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">���� �ֹ� �ߺ� ���</TD>
						<TD class="td_con1">
							<INPUT type=radio CHECKED value=N name=order_limit>
							���� ����  &nbsp;
							<INPUT type=radio value=Y name=order_limit>
							���Ұ� 
					</tr>
					<TR>
						<TD colspan=2 background="images/table_top_line.gif"></TD>
					</TR>
					<tr>
						<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">����ǰ ���ܿ���</TD>
						<TD class="td_con1">
							<input type=checkbox name=etcapply_gift value=A>
							�� ������ ����� ��� ����ǰ�� �������� �ʽ��ϴ�. </TD>
					</tr>
					<TR>
						<TD colspan=2 background="images/table_top_line.gif"></TD>
					</TR>
				</TABLE>
			</td>
		</tr>
		<tr>
			<td height="30"></td>
		</tr>
		<tr>
			<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
					<TR>
						<TD><IMG SRC="images/market_couponnew_stitle2.gif" WIDTH="192" HEIGHT=31 ALT=""></TD>
						<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
						<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
					</TR>
				</TABLE>
			</td>
		</tr>
		<tr>
			<td height=3></td>
		</tr>
		<tr>
			<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0 style="table-layout:fixed">
					<col width=160>
					
							</col>
					
					<col width=>
					
							</col>
					
					<TR>
						<TD colspan=2 background="images/table_top_line.gif"></TD>
					</TR>
					<TR>
						<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">���� ���� ��ǰ�� ����</TD>
						<TD class="td_con1">
							<table cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td>
										<input type=radio name=codegbn value="A" checked onclick="ChangeCodegbn('A')">
										��ü��ǰ
										&nbsp;&nbsp;
										<input type=radio name=codegbn value="N" onclick="ChangeCodegbn('N')">
										�Ϻ� ī�װ�/��ǰ </td>
								</tr>
								<tr>
									<td height=5></td>
								</tr>
								<tr>
									<td id=layer_codelist style="display:none">
										<table border=0 cellpadding=0 cellspacing=0 width=100%>
											<tr>
												<td>
													<select name=codelist size=10 style="WIDTH:470px" class="select">
														<option value="" style="BACKGROUND-COLOR: #ffff00">------------------------- ���� ��ǰ���� �����ϼ���. -------------------------</option>
													</select>
												</td>
												<td valign=top nowrap> <a href="javascript:ChoiceProduct();"><img src="images/btn_add1.gif" hspace="2"></a> &nbsp; <a href="javascript:CodeDelete();"><img src="images/btn_del.gif" hspace="2"></a> </td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</TD>
					</TR>
					<TR>
						<TD colspan="2" background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></TD>
					</TR>
					<TR>
						<TD colspan="2">
							<div id=layer1 style="margin-left:0;display:hide; display:none;border-style:solid; border-width:0; border-color:black;background:#FFFFFF;padding:0;">
								<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0 style="table-layout:fixed">
									<col width=160>
									
											</col>
									
									<col width=>
									
											</col>
									
									<TR>
										<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">���� �������</TD>
										<TD class="td_con1">
											<INPUT type=checkbox CHECKED value=Y name=use_con_type1>
											�ٸ� ��ǰ�� �Բ� ���Žÿ���, �ش� ������ ����մϴ�.<BR>
											<INPUT type=checkbox value=N name=use_con_type2>
											���õ� ī�װ�(��ǰ)�� �����ϰ� �����մϴ�. </TD>
									</TR>
									<TR>
										<TD colspan="2" background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></TD>
									</TR>
									</TABLE>									
							</div>
						</td>
					</tr>
					<tr>
						<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">���� ���� ��</TD>
						<TD class="td_con1">
							<INPUT onkeyup="strnumkeyup(this);" maxLength="10" size="10" name="issue_tot_no" value="" class="input">
							�� </TD>
					</tr>
					<TR>
						<TD colspan="2" background="images/table_con_line.gif"></TD>
					</TR>
					<TR>
						<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">���� ���� ����&nbsp;����</TD>
						<TD class="td_con1"> ������ ���ȸ�� ����/���� ���� ����
							<SELECT name=use_point class="select">
								<OPTION value=Y selected>������</OPTION>
								<OPTION value=A>�������</OPTION>
							</SELECT>
						</TD>
					</TR>
					<TR>
						<TD colspan="2" background="images/table_con_line.gif"></TD>
					</TR>
				</TABLE>
			</td>
		</tr>
		<tr>
			<td height=10></td>
		</tr>
		<tr>
			<td align=center><a href="javascript:CheckForm(document.form1);"><img src="images/btn_cupon.gif" width="139" height="38" border="0"></a> <a href="javascript:document.location.replace='/admin/offlinecoupon.php';"><img src="images/btn_list.gif" border="0" alt="�������"></a></td>
		</tr>
		<tr>
			<td height="25">&nbsp;</td>
		</tr>
		<tr>
			<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
					<TR>
						<TD><IMG SRC="images/manual_top1.gif" WIDTH=15 height="45" ALT=""></TD>
						<TD><IMG SRC="images/manual_title.gif" WIDTH=113 height="45" ALT=""></TD>
						<TD width="100%" background="images/manual_bg.gif" height="35"></TD>
						<TD background="images/manual_bg.gif"></TD>
						<td background="images/manual_bg.gif"><IMG SRC="images/manual_top2.gif" WIDTH=18 height="45" ALT=""></td>
					</TR>
					<TR>
						<TD background="images/manual_left1.gif"></TD>
						<TD COLSPAN=3 width="100%" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;" class="menual_bg">
							<table cellpadding="0" cellspacing="0" width="100%">
								<col width=20>
								
										</col>
								
								<col width=>
								
										</col>
								
								<tr>
									<td align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
									<td>���� ����� �ѹ��� �ֹ��ǿ����� ����� �� �ֽ��ϴ�.</td>
								</tr>
								<tr>
									<td align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
									<td>������� ���� : �� ���������� ���Ž� ��� ���ε˴ϴ�.</td>
								</tr>
								<tr>
									<td align="right" valign="top">&nbsp;</td>
									<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;�� ���������� ���Ž� �߰� �������� ���޵˴ϴ�.</td>
								</tr>
								<tr>
									<td align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
									<td>������ǰ ���� :����ǰ,�Ϻ�ī�װ�,�Ϻλ�ǰ ���� ���� �˴ϴ�.</td>
								</tr>
								<tr>
									<td align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
									<td>��������� �α��� �� ���������� ������������ ���� ��ȣ ���� �� ���� Ȯ�� �� ��� �� �� �ֽ��ϴ�.</td>
								</tr>
							</table>
						</TD>
						<TD background="images/manual_right1.gif"></TD>
					</TR>
					<TR>
						<TD><IMG SRC="images/manual_left2.gif" WIDTH=15 HEIGHT=8 ALT=""></TD>
						<TD COLSPAN=3 background="images/manual_down.gif"></TD>
						<TD><IMG SRC="images/manual_right2.gif" WIDTH=18 HEIGHT=8 ALT=""></TD>
					</TR>
				</TABLE>
			</td>
		</tr>
		<tr>
			<td height="50"></td>
		</tr>
	</table>
</form>
<form name=form2 action="coupon_productchoice.php" method=post target=coupon_product>
</form>
