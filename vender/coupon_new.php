<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");
INCLUDE ("access.php");

$CurrentTime = time();
$date_start=$_POST["date_start"];
$date_end=$_POST["date_end"];
$date_start=$date_start?$date_start:date("Y-m-d",$CurrentTime);
$date_end=$date_end?$date_end:date("Y-m-d",$CurrentTime);

################## �ǵ��� ����!! #############
$sale_type="-";
$bank_only="N";
$use_point="Y";
$use_con_type1="Y";
$detail_auto="Y";
##############################################


$type=$_POST["type"];
$productcode=$_POST["productcode"];
$coupon_name=$_POST["coupon_name"];
$time=$_POST["time"];
$peorid=$_POST["peorid"];
$sale2=$_POST["sale2"];
$sale_money=$_POST["sale_money"];
$amount_floor=$_POST["amount_floor"];
$mini_price=$_POST["mini_price"];
$use_con_type2=$_POST["use_con_type2"];
$issue_type=$_POST["issue_type"];
$issue_tot_no=$_POST["issue_tot_no"];
$repeat_id=$_POST["repeat_id"];
$description=$_POST["description"];
$couponimg=$_FILES["couponimg"];

$imagepath=$Dir.DataDir."shopimages/etc/";
if ($type=="insert") {
	$coupon_code=substr(ceil(date("sHi").date("ds")/10*8)."000",0,8);
	if($couponimg[size] < 102400) {
		if (strlen($couponimg[name])>0 && file_exists($couponimg[tmp_name])) {
			$ext = strtolower(substr($couponimg[name],strlen($couponimg[name])-3,3));
			if ($ext=="gif") {
				$imagename = "COUPON".$coupon_code.".gif";
				move_uploaded_file($couponimg[tmp_name],$imagepath.$imagename);
				chmod($imagepath.$imagename,0664);
			} else {
				echo "<html></head><body onload=\"alert('���� �̹��� ������ GIF ���ϸ� ��� �����մϴ�.')\"></body></html>";exit;
			}
		}
	} else {
		echo "<html></head><body onload=\"alert('���� �̹��� ���� �뷮�� �ʰ��Ǿ����ϴ�.\\n\\nGIF ���� 100KB ���Ϸ� �÷��ֽñ� �ٶ��ϴ�.')\"></body></html>";exit;
	}

	if(strlen($mini_price)==0) $mini_price=0; 
	if(strlen($use_con_type1)==0 || $productcode=="ALL") $use_con_type1="N"; 
	if(strlen($use_con_type2)==0 || $productcode=="ALL") $use_con_type2="Y"; 
	if(strlen($repeat_id)==0) $repeat_id="N";
	if(strlen($issue_tot_no)==0) $issue_tot_no=0; 
	if(strlen($sale_money)==0) $sale_money=0; 
	if($sale_type=="+" && $sale2=="%") $realsale=1;
	else if($sale_type=="-" && $sale2=="%") $realsale=2;
	else if($sale_type=="+" && $sale2=="��") $realsale=3;
	else if($sale_type=="-" && $sale2=="��") $realsale=4;
	if ($time=="D") {
		$date_start = str_replace("-","",$date_start)."00";
		$date_end = str_replace("-","",$date_end)."23";
	} else {
		$date_start = "-".$peorid;
		$date_end = "";
	}

	$sql = "INSERT tblcouponinfo SET ";
	$sql.= "coupon_code		= '".$coupon_code."', ";
	$sql.= "coupon_name		= '".$coupon_name."', ";
	$sql.= "date_start		= '".$date_start."', ";
	$sql.= "date_end		= '".$date_end."', ";
	$sql.= "sale_type		= '".$realsale."', ";
	$sql.= "sale_money		= ".$sale_money.", ";
	$sql.= "amount_floor	= '".$amount_floor."', ";
	$sql.= "mini_price		= ".$mini_price.", ";
	$sql.= "bank_only		= '".$bank_only."', ";
	$sql.= "productcode		= '".$productcode."', ";
	$sql.= "use_con_type1	= '".$use_con_type1."', ";
	$sql.= "use_con_type2	= '".$use_con_type2."', ";
	$sql.= "issue_type		= '".$issue_type."', ";
	$sql.= "detail_auto		= '".$detail_auto."', ";
	$sql.= "issue_tot_no	= ".$issue_tot_no.", ";
	$sql.= "repeat_id		= '".$repeat_id."', ";
	$sql.= "description		= '".$description."', ";
	$sql.= "use_point		= '".$use_point."', ";
	$sql.= "member			= '".($issue_type!="N"?"ALL":"")."', ";
	$sql.= "display			= '".($issue_type!="N"?"Y":"N")."', ";
	$sql.= "date			= '".date("YmdHis")."', ";
	$sql.= "vender			= '".$_VenderInfo->getVidx()."' ";
	if(mysql_query($sql,get_db_conn())) {
		if($issue_type!="N") $url = "coupon_list.php";
		else $url = "coupon_supply.php";
		echo "<html></head><body onload=\"alert('���� ������ �Ϸ�Ǿ����ϴ�.');parent.location.href='".$url."'\"></body></html>";exit;
	} else {
		echo "<html></head><body onload=\"alert('��û�Ͻ� �۾��� ������ �߻��Ͽ����ϴ�.')\"></body></html>";exit;
	}
	exit;
}

?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script type="text/javascript" src="calendar.js.php"></script>
<script language="JavaScript">
function CheckForm() {
	form=document.form1;
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
	content+="* ���� �ݾ�/���η� : "+form.sale_money.value+form.sale2.options[form.sale2.selectedIndex].value+"\n\n";

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
	content+="* �����ǰ�� : "+form.productname.value+"\n\n";

	if(form.description.value.length==0) {
		alert("���� ������ �Է��ϼ���.");
		form.description.focus();
		return;
	}
	if(CheckLength(form.description)>100) {
		alert("�Է��� �� �ִ� ��� ������ �ʰ��Ǿ����ϴ�.\n\n" + "�ѱ� 50�� �̳� Ȥ�� ����/����/��ȣ 100�� �̳��� �Է��� �����մϴ�.");
		form.description.focus();
		return;
	}
	if(form.issue_type[0].checked==true && form.checknum[1].checked==true){
		alert("���� �߱޽� ���� ������ ��� ���� �������� ������ �����ϴ�.");
		nonum(1);
	}
	if(form.checknum[1].checked==true){
		if(form.issue_tot_no.value.length==0){
			alert("���� ������� �Է��ϼ���.");
			form.issue_tot_no.focus();
			return;
		}else if(!IsNumeric(form.issue_tot_no.value)){
			alert("���� ������� ���ڸ� �Է� �����մϴ�.(�Ҽ��� �Է� �ȵ�)");
			form.issue_tot_no.focus();
			return;
		}
		content+="* ���� ������ : "+form.issue_tot_no.value+"��\n\n";
	} else {
		content+="* ���� ������ : ������\n\n";
	}

	if(form.issue_type[0].checked==true) tempmsg ="���� �߱޿� ����";
	else if(form.issue_type[1].checked==true) tempmsg ="���� Ŭ���� �߱�";
	content+="* �߱����� : "+tempmsg+"\n\n";
	if(form.useimg[0].checked==true){
		form.couponimg.value="";
		content+="* �����̹��� : �⺻�̹���\n\n";
	} else if(form.useimg[1].checked==true && form.couponimg.value.length==0){
		alert("���� �̹����� ����ϼ���.");
		form.couponimg.focus();
		return;
	} else {
		content+="* �����̹��� : ���� �̹��� ���\n\n";
	}
	content+="--------------------------------------------";
	if(confirm(content)){
		form.type.value="insert";
		form.target="processFrame";
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
		document.form1.mini_price.style.background='silver';
		document.form1.checksale[0].checked=true;
	} else {
		document.form1.mini_price.value="0";
		document.form1.mini_price.disabled=false;
		document.form1.mini_price.style.background='white';
		document.form1.checksale[1].checked=true;
	}
}
function nonum(temp){  
	if(temp==1){
		document.form1.issue_tot_no.value="";
		document.form1.issue_tot_no.disabled=true;
		document.form1.issue_tot_no.style.background='silver';
		document.form1.checknum[0].checked=true;
	} else {
		document.form1.issue_tot_no.value="0";
		document.form1.issue_tot_no.disabled=false;
		document.form1.issue_tot_no.style.background='white';
		document.form1.checknum[1].checked=true;
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
	owin=window.open("coupon_productchoice.php","coupon_product","width=245,height=140,scrollbars=no");
	owin.focus();
}
</script>
<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
<col width=190></col>
<col width=20></col>
<col width=></col>
<col width=20></col>
<tr>
	<td width=190 valign=top nowrap background="images/minishop_leftbg.gif"><? include ("menu.php"); ?></td>
	<td width=20 nowrap></td>
	<td valign=top style="padding-top:20px">

	<table width="100%"  border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>
		<table width="100%"  border="0" cellpadding="0" cellspacing="0" >
		<tr>
			<td>
				<table border=0 cellpadding=0 cellspacing=0 width=100% >
				<tr>
					<td><img src="images/coupon_new_title.gif"></td>
				</tr>
				<tr>
					<td height=5 background="images/minishop_titlebg.gif">
				</tr>
				</table>
			</td>
		</tr>
		<tr><td height=10></td></tr>
		<tr>
			<td>
				<table border=0 cellpadding=0 cellspacing=0 width=100% >
				<tr>
					<td colspan=3 >


						<table cellpadding="10" cellspacing="1" width="100%" bgcolor="#EFEFF2">
							<tr>
								<td  bgcolor="#F5F5F9" style="padding:20px">
									<table border=0 cellpadding=0 cellspacing=0 width=100%>
									    <tr>
											<td class="notice_gray" height="20"><img src="images/icon_dot02.gif" border=0 hspace="4"><span class="notice_blue">�������� �� ���ο� ���� ����� ����ݾ׿��� �����˴ϴ�.</span></td>
										</tr>
										<!--
										<tr>
											<td class="notice_gray" height="20"><img src="images/icon_dot02.gif" border=0 hspace="4">���� ����� �� �ֹ��ǿ� ���ؼ� �Ѱ��� ������ ����� �����մϴ�.</td>
										</tr>
										-->
										<tr>
											<td class="notice_gray" height="20"><img src="images/icon_dot02.gif" border=0 hspace="4">���� ����� �ݾ�/������ �ΰ��� ������� ����� ���ֽ��ϴ�.</td>
										</tr>
										<tr>
											<td class="notice_gray" height="20"><img src="images/icon_dot02.gif" border=0 hspace="4">���� ������  ���������� �������� Ȯ�� �� �� �ֽ��ϴ�.</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>



					</td>
				</tr>
				</table>
				</td>
			</tr>

			<!-- ó���� ���� ��ġ ���� -->
			<tr><td height=40></td></tr>
			<tr>
				<td>
				


				


				<table border=0 cellpadding=0 cellspacing=0 width=100%>

				<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post enctype="multipart/form-data">
				<input type=hidden name=type>
				<input type=hidden name=productcode value="ALL">

				<tr>
					<td><img src="images/coupon_new_stitle01.gif" border=0 align=absmiddle alt="�������� �⺻����"></td>
				</tr>
				<tr><td height=5></td></tr>
				<tr><td height=1 bgcolor=#cccccc></td></tr>
				</table>

				<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
				<col width=140></col>
				<col width=></col>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>���� �̸�</B></td>
					<td style=padding:7,10>
					<input type=text name=coupon_name size=50 maxlength=100 class="input"> <span class="notice_blue">��) ������ 10% ��������</span>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>��ȿ�Ⱓ</B></td>
					<td style=padding:7,10>
					<table border=0 cellpadding=0 cellspacing=0 width=100%>
					<tr>
						<td style="padding-top:5;padding-bottom:3">
						<input type=radio name=time value="D">�����Ⱓ : 
						<input class="input" type=text name=date_start value="<?=$date_start?>" size=10 onfocus="this.blur();" OnClick="Calendar(this)" style="background:#efefef"> ���� <input class="input" type=text name=date_end value="<?=$date_end?>" size=10 onfocus="this.blur();" OnClick="Calendar(this)" style="background:#efefef"> ���� ��밡�� <span class="notice_blue">(�Ⱓ �������� 23��59��59�� ����)</span>
						</td>
					</tr>
					<tr>
						<td style="padding-top:5;padding-bottom:5">
						<input type=radio name=time value="P" checked>���� �� <input type=text name=peorid size=4 maxlength=3 style="text-align:right;padding-right:3" onkeyup="strnumkeyup(this);" class="input">�� ���� ��밡�� <span class="notice_blue">(��ȿ�Ⱓ �������� 23��59��59�� ����)</span>
						</td>
					</tr>
					</table>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>�ݾ�/������ ����</B></td>
					<td style=padding:7,10>
					<table border=0 cellpadding=0 cellspacing=0 width=100%>
					<tr>
						<td width=50% style="padding-left:5">
						<select name=sale2 style="width:100" onchange="changerate(options.value);">
						<option value="��" selected>�ݾ�</option>
						<option value="%">������</option>
						</select>
						��
						<input type=text name=sale_money size=10 maxlength=10 style="text-align:right;padding-right:5px;" onkeyup="strnumkeyup(this);" class="input">
						<input type=text name=rate size=1 value="��" class=input_hide readonly>
						</td>
						<td align=right bgcolor=#F0F0F0 width=70 nowrap style="padding-right:10">�ݾ�����</td>
						<td width=50% style="padding-left:5">
						<select name=amount_floor disabled>
<? 
						$arfloor = array(1=>"�Ͽ�����, ��)12344 �� 12340","�ʿ�����, ��)12344 �� 12300","�������, ��)12344 �� 12000","õ������, ��)12344 �� 10000");
						$arcnt = count($arfloor);
						for($i=1;$i<$arcnt;$i++){
							echo "<option value=\"".$i."\"";
							if($amount_floor==$i) echo " selected";
							echo ">".$arfloor[$i]."</option>";
						}
?>
						</select>
						</td>
					</tr>
					</table>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>���� ���� �ݾ�</B></td>
					<td style=padding:7,10>
					<input type=radio name=checksale checked onClick="nomoney(1)">���� ����
					<img width=16 height=0>
					<input type=radio name=checksale onClick="nomoney(0)">
					<input type=text name=mini_price size=10 maxlength=10 style="text-align:right;padding-right:5px;" onkeyup="strnumkeyup(this);" class="input"> �� �̻� �ֹ��� ����
					<script>nomoney(1);</script>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				</table>

				<table border=0 cellpadding=0 cellspacing=0 width=100%>
				<tr><td height=60></td></tr>
				<tr>
					<td><img src="images/coupon_new_stitle02.gif" border=0 align=absmiddle alt="�������� �ΰ�����"></td>
				</tr>
				<tr><td height=5></td></tr>
				<tr><td height=1 bgcolor=#cccccc></td></tr>
				</table>

				<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
				<col width=140></col>
				<col width=></col>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>���� ���� ��ǰ��</B></td>
					<td style=padding:7,10>
					���� ���� ��ǰ�� : <input type=text name=productname size=50 readonly value="��ü��ǰ" onclick="alert('���� ���� ��ǰ���� ������ [�����ϱ�]��ư�� Ŭ���Ͻø� �˴ϴ�.')" class="input">
					<img src=images/btn_inquery02.gif border=0 align=absmiddle style="cursor:hand" onclick="ChoiceProduct();">
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td colspan=2>
					<div id=layer1 style="margin-left:0;display:hide; display:none;border-style:solid; border-width:0; border-color:black;background:#FFFFFF;padding:0;">
					<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
					<col width=140></col>
					<col width=></col>
					<tr>
						<td bgcolor=FBB99F background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>���� �������</B></td>
						<td bgcolor=FDEDE3 style=padding:7,10>
						<input type=checkbox name=use_con_type2 value="N"> ���õ� �з�(��ǰ)�� �����ϰ� �����մϴ�.
						</td>
					</tr>
					<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
					</table>
					</div>
					</td>
				</tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>���� �߱�����</B></td>
					<td style=padding:7,10>
					<input type=radio name=issue_type value="N" checked onclick="ViewLayer('layer2','none')"> ���� �߱޿� ���� <span class="notice_blue">��[������ ���� ���� �߱�]���� ������  ������, �߱��Ͻø� �˴ϴ�.</span>
					<br>
					<input type=radio name=issue_type value="Y" onclick="ViewLayer('layer2','block')"> ���� Ŭ���� �߱� <span class="notice_blue">��ȸ���� ������ ���� �ٿ�޾� �߱޵˴ϴ�.</span>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td colspan=2>
					<div id=layer2 style="margin-left:0;display:hide; display:none;border-style:solid; border-width:0; border-color:black;background:#FFFFFF;padding:0;">
					<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
					<col width=140></col>
					<col width=></col>
					<tr>
						<td bgcolor=FBF8E6 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>���� ������</B></td>
						<td bgcolor=FEFDF5 style=padding:7,10>
						<input type=radio name=checknum checked onClick="nonum(1)">������
						<img width=10 height=0>
						<input type=radio name=checknum onClick="nonum(0)"><input type=text name=issue_tot_no size=10 maxlength=10 style="text-align:right;padding-right:5px;" onkeyup="strnumkeyup(this);">�� ����
						<script>nonum(1);</script>
						</td>
					</tr>
					<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
					<tr>
						<td bgcolor=FBF8E6 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>������ ��߱�</B></td>
						<td bgcolor=FEFDF5 style=padding:7,10>
						<input type=radio name=repeat_id value="Y">����
						<img width=22 height=0>
						<input type=radio name=repeat_id checked value="N">�Ұ���
						</td>
					</tr>
					<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
					</table>
					</div>
					</td>
				</tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>���� ����</B></td>
					<td style=padding:7,10>
					<input type=text name=description size=80 maxlength=200  class="input">
					<br>
					<span class="notice_blue">�������� ���� ������ ������ �Է��ϼ���. �ش� ������ �����̹��� ��ܿ� ����˴ϴ�.</span>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>���� �̹��� ����</B></td>
					<td style=padding:7,10>
					<input type=radio name=useimg checked>�⺻ �̹��� ���
					<br><img width=0 height=3><br>
					<img width=15 height=0>
					<img src="images/market_couponsampleimg.gif">
					<br><br>
					<input type=radio name=useimg>�������� �̹��� ��� <span class="notice_blue">(GIF ���� 100KB ���Ϸ� �÷��ֽð�, ���� ������� 350*150 �Դϴ�.)</span>
					<br><img width=0 height=3><br>
					<img width=15 height=0>
					<input type=file name=couponimg size=50 class=button >
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				</table>

				<table border=0 cellpadding=0 cellspacing=0 width=100%>
				<tr><td height=20></td></tr>
				<tr>
					<td align=center>
					<img src=images/btn_regist01.gif border=0 style="cursor:hand" onclick="CheckForm();">
					</td>
				</tr>
				</form>
				</table>

				</td>
			</tr>
			<!-- ó���� ���� ��ġ �� -->

			</table>
			</td>
		</tr>
		</table>
		</td>
	</tr>
	</table>

	</td>
</tr>
</table>

<iframe name="processFrame" src="about:blank" width="0" height="0" scrolling=no frameborder=no></iframe>

<?=$onload?>

<? INCLUDE "copyright.php"; ?>