<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once '../lib/class/coupon.php';
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "ma-3";
$MenuCode = "market";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$popup=$_REQUEST["popup"];

$gubun=$_REQUEST["gubun"];

$memberID=$_REQUEST["memberID"];

$groupCode=$_REQUEST["grp"];


/*
$CurrentTime = time();
$date_start=$_POST["date_start"];
$date_end=$_POST["date_end"];
$date_start=$date_start?$date_start:date("Y-m-d",$CurrentTime);
$date_end=$date_end?$date_end:date("Y-m-d",$CurrentTime);

$type=$_POST["type"];
$productcode=$_POST["productcode"];
$coupon_name=$_POST["coupon_name"];
$time=$_POST["time"];
$peorid=$_POST["peorid"];
$sale_type=$_POST["sale_type"];
$sale2=$_POST["sale2"];
$sale_money=$_POST["sale_money"];
$amount_floor=$_POST["amount_floor"];
$mini_price=$_POST["mini_price"];
$bank_only=$_POST["bank_only"];
$order_limit=$_POST["order_limit"];
$use_con_type1=$_POST["use_con_type1"];
$issue_type=$_POST["issue_type"];
$detail_auto=$_POST["detail_auto"];
$issue_tot_no=$_POST["issue_tot_no"];
$repeat_id=$_POST["repeat_id"];
$repeat_ok=$_POST["repeat_ok"];
$description=$_POST["description"];
$use_point=$_POST["use_point"];
$etcapply_gift=$_POST["etcapply_gift"];
$couponimg=$_FILES["couponimg"];

$imagepath=$Dir.DataDir."shopimages/etc/";
if ($type=="insert") {
	$coupon_code=substr(ceil(date("sHi").date("ds")/10*8)."000",0,8);
	if($couponimg[size] < 153600) {
		if (strlen($couponimg[name])>0 && file_exists($couponimg[tmp_name])) {
			$ext = strtolower(substr($couponimg[name],strlen($couponimg[name])-3,3));
			if ($ext=="gif") {
				$imagename = "COUPON".$coupon_code.".gif";
				move_uploaded_file($couponimg[tmp_name],$imagepath.$imagename);
				chmod($imagepath.$imagename,0666);
			} else {
				echo "<script>alert('���� �̹��� ������ GIF ���ϸ� ��� �����մϴ�.');history.go(-1);</script>";
			}
		}
	} else {
		echo "<script>alert('���� �̹��� ���� �뷮�� �ʰ��Ǿ����ϴ�.\\n\\nGIF ���� 150KB ���Ϸ� �÷��ֽñ� �ٶ��ϴ�.');history.go(-1);</script>";
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

	if($etcapply_gift!="A") $etcapply_gift="";

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
	$sql.= "order_limit		= '".$order_limit."', ";
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
	$sql.= "etcapply_gift	= '".$etcapply_gift."', ";
	$sql.= "display			= '".($issue_type!="N"?"Y":"N")."', ";
	$sql.= "date			= '".date("YmdHis")."' ";

	mysql_query($sql,get_db_conn());

	if($issue_type!="N") $url = "market_couponlist.php";
	else $url = "market_couponsupply.php";

	echo "<body onload=\"location.href='$url';\"></body>";
	exit;
}*/

$coupon = new coupon();
if($_POST['type'] == 'insert'){
	$_POST['coupon_code'] = $coupon->_genCouponcode();
	$imgresult = $coupon->_couponImg($result['coupon_code'],$_FILES["couponimg"]);
	if($imgresult == 'notgif'){
		echo "<script>alert('���� �̹��� ������ GIF ���ϸ� ��� �����մϴ�.');history.go(-1);</script>";
		exit;
	}else if($imgresult == 'sizeover'){
		echo "<script>alert('���� �̹��� ���� �뷮�� �ʰ��Ǿ����ϴ�.\\n\\nGIF ���� 150KB ���Ϸ� �÷��ֽñ� �ٶ��ϴ�.');history.go(-1);</script>";
		exit;
	}
	$result = $coupon->_new($_POST);

	if($result['result']){

		//if( $popup == "OK" ) {
			switch( $gubun ){
				case "MEMBER" :
					$url = "market_couponsupply.php?popup=".$popup."&memberID=".$memberID."&gubun=".$gubun."&coupon_code=".$result['coupon_code'];
					break;
				case "GROUP" :
					$url = "member_groupnew_couponAddPop.php?popup=".$popup."&grp=".$groupCode;
					break;
				default :
					$url=($result['issue_type']!="N")? "market_couponlist.php" : "market_couponsupply.php" ;
				break;
			}
		//}
		echo "<body onload=\"location.href='$url';\"></body>";
		exit;
	}
}

include "header.php"; ?>
<script type="text/javascript" src="lib.js.php"></script>
<script type="text/javascript" src="calendar.js.php"></script>
<script type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
<script language="javascript" type="text/javascript">
$j = jQuery.noConflict();
</script>
<script language="JavaScript">
function CheckForm(form) {
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

	if(form.detail_auto[0].checked==true && form.issue_type[1].checked!=true) {
		content+="* ��ǰ �������� �ڵ����� : ������\n\n";
	} else if(form.issue_type[1].checked!=true) {
		content+="* ��ǰ �������� �ڵ����� : �������\n\n";
	}

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
	if((form.issue_type[0].checked==true || form.issue_type[2].checked==true) && form.checknum[1].checked==true){
		alert("��� �߱޽�,ȸ�� ���Խ� ���� ������ ��� ���� �������� ������ �����ϴ�.");
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
		}else if(form.issue_tot_no.value<=0) {
			alert("���� ���� �ż��� �Է��ϼ���.");
			form.issue_tot_no.focus();
			return;
		}
		content+="* ���� ������ : "+form.issue_tot_no.value+"��\n\n";
	} else {
		content+="* ���� ������ : ������\n\n";
	}

	if(form.repeat_id[0].checked==true){
		 content +="* �ߺ� �ٿ�ε� : ����\n\n";
	} else {
		 content +="* �ߺ� �ٿ�ε� : �Ұ���\n\n";
	}


	/*
	if(form.repeat_ok[0].checked==true){
		 content +="* ���� ��� �� �ڵ� ��߱� : ���\n\n";
	} else {
		 content +="* ���� ��� �� �ڵ� ��߱� : ������\n\n";
	}
	*/

	//content+="* �����ǰ�� : "+form.productname.value+"\n\n";
	if(form.etcapply_gift.checked==true) {
		content+="* ����ǰ���ܿ��� : �� ������ ����� ��� ����ǰ�� �������� ����\n\n";
	}


	if(form.issue_type[0].checked==true) tempmsg ="��� �߱޿� ����";
	else if(form.issue_type[1].checked==true) tempmsg ="���� Ŭ���� �߱�";
	else if(form.issue_type[2].checked==true) tempmsg ="ȸ�� ���Խ� �߱�";
	content+="* �߱����� : "+tempmsg+"\n\n";
	//content+="* ���ѻ��� : ����������ð� ���û�� "+form.use_point[form.use_point.selectedIndex].text+"\n\n";
	if(form.use_point[1].checked==true) content +="* ��޺� ����: ȸ��������ΰ� ���� ���� �Ұ�\n\n";

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
function nonum(temp){
	if(temp==1){
		document.form1.issue_tot_no.value="";
		document.form1.issue_tot_no.disabled=true;
		document.form1.issue_tot_no.style.background='#F0F0F0';
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

function toggleDownType(bool){
	if(bool){
		$j('.downOnly').css('display','');
	}else{
		$j('.downOnly').css('display','none');
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


</script>

<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
	<tr>
		<td valign="top">
			<table cellpadding="0" cellspacing="0" width=100% style="table-layout:fixed">
				<tr>
					<td>
						<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed"  background="images/con_bg.gif">
							<?
								if( $popup != "OK" ) {
							?>
							<col width=198></col>
							<col width=10></col>
							<?
								}
							?>
							<col width=></col>
							<tr>
								<?
									if( $popup != "OK" ) {
								?>
								<td valign="top"  background="images/leftmenu_bg.gif"><? include ("menu_market.php"); ?></td>
								<td></td>
								<?
									}
								?>
								<td valign="top">
									<table cellpadding="0" cellspacing="0" width="100%">
										<?
											if( $popup != "OK" ) {
										?>
										<tr>
											<td height="29" colspan="3">
												<table cellpadding="0" cellspacing="0" width="100%">
													<tr>
														<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ���������� &gt; �������� ���� ���� &gt; <span class="2depth_select">���ο� ���� �����ϱ�</span></td>
													</tr>
												</table>
											</td>
										</tr>
										<?
											}
										?>
										<tr>
											<td width="16"><img src="images/con_t_01.gif" width="16" height="16" border="0"></td>
											<td background="images/con_t_01_bg.gif"></td>
											<td width="16"><img src="images/con_t_02.gif" width="16" height="16" border="0"></td>
										</tr>
										<tr>
											<td width="16" background="images/con_t_04_bg1.gif"></td>
											<td bgcolor="#ffffff" style="padding:10px">
												<a href="javascript:document.location.reload()">���ΰ�ħ</a>
												<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post enctype="multipart/form-data">
													<input type=hidden name=popup value="<?=$popup?>">
													<input type=hidden name=memberID value="<?=$memberID?>">
													<input type=hidden name=gubun value="<?=$gubun?>">
													<input type=hidden name=type>
													<input type=hidden name=productcode value="ALL">
													<div style="padding-bottom:21px; background:url(images/title_bg.gif) repeat-x bottom left; margin-top:8px; margin-bottom:3px;"> <IMG SRC="images/market_couponnew_title.gif" ALT=""> </div>
													<div style="padding-top:3px; margin-bottom:20px; padding-left:25px;" class="notice_blue">ȸ���鿡�� �����Ӱ� �������� ���񽺸� ������ �� �ֽ��ϴ�.
													<br><span class="font_orange">�������� ��� �������� ��ü���� ���ο� ���� ����� �δ��մϴ�.(���θ� ��翡�� ������ ��ǰ�� ���� �� �������αݾ��� ����ݾ׿��� �������� ����)</span></div>
													<style type="text/css">
													.cinputTbl{ border-top:1px solid #ccc;  border-bottom:1px solid #ccc; margin-bottom:15px;}
													.cinputTbl th{ background:#f8f8f8 url(images/icon_point2.gif) no-repeat 15px 50%; padding:3px 0px 3px 25px; border-bottom:1px solid #efefef; border-right:1px solid #efefef; text-align:left; font-size:12px;}
													.cinputTbl td{ border-bottom:1px solid #efefef; padding:3px; empty-cells:show}
													</style>
													<div><IMG SRC="images/market_couponnew_stitle1.gif" WIDTH="192" HEIGHT=31 ALT="">
														<p class="notice_blue" style="padding-left:25px;">���� ����� �� �ֹ��ǿ� ���ؼ� �Ѱ��� ������ ����� �����մϴ�.</p>
													</div>
													<table class="cinputTbl" width="100%" border="0"  cellpadding="0" cellspacing="0">
														<tr>
															<th style="width:160px">���� �̸�</th>
															<td>
																<INPUT maxLength=100 size=70 name=coupon_name class="input">
																<br>
																<span class="font_orange"><b>��)�� ������10% ���������̺�Ʈ~</b></span></td>
														</tr>
														<tr>
															<th>���� ����</th>
															<td>
																<INPUT maxLength=200 size=91 name=description style=width:99% class="input">
																<span class="font_orange"> * �Է��� ���������� �����̹��� ��ܿ� ��µ˴ϴ�.</span></td>
														</tr>
														<tr>
															<th>��ȿ�Ⱓ</th>
															<td>
																<div>
																	<INPUT type=radio value=D name=time>
																	�Ⱓ���� :
																	<INPUT onfocus=this.blur(); onclick=Calendar(this) size=10 name=date_start value="<?=$date_start?>" class="input_selected">
																	����
																	<INPUT  onfocus=this.blur(); onclick=Calendar(this) size=10 name=date_end value="<?=$date_end?>" class="input_selected">
																	���� ��밡��<span class="font_orange">(��ȿ�Ⱓ �������� 23��59��59�� ����)</span> </div>
																<div>
																	<INPUT type=radio CHECKED value=P name=time>
																	���� ��
																	<INPUT onkeyup=strnumkeyup(this); style="PADDING-RIGHT: 3px; TEXT-ALIGN: right" maxLength=3 size=4 name=peorid class="input">
																	�� ���� ��밡��<span class="font_orange">(��ȿ�Ⱓ �������� 23��59��59�� ����)</span> </div>
															</td>
														</tr>
														<tr>
															<th>�������� ����</th>
															<td>
																<SELECT style="WIDTH: 100px" name=sale_type class="select">
																	<OPTION value="-" selected>���� ����</OPTION>
																	<OPTION value="+">���� ���� (����� ����)</OPTION>
																</SELECT>
																<span class="font_orange"> * ���������� ���Ž� ��� ���εǸ�, ���������� ���Ž� �߰� �������� ���޵˴ϴ�.</span> </td>
														</tr>
														<tr>
															<th>�ݾ�/������ ����</th>
															<td>
																<SELECT style="WIDTH: 100px" onchange=changerate(options.value); name=sale2 class="select">
																	<OPTION value="��" selected>�ݾ�</OPTION>
																	<OPTION value="%">����(����)��</OPTION>
																</SELECT>
																��
																<INPUT onkeyup=strnumkeyup(this); style="PADDING-RIGHT: 5px; TEXT-ALIGN: right" maxLength=10 size=10 name=sale_money class="input">
																<INPUT class="input_hide1" readOnly size=1 value=�� name=rate>
															</td>
														</tr>
														<tr>
															<th>�ݾ�����</th>
															<td>
																<SELECT disabled name=amount_floor class="select">
																	<?
																		$arfloor = array(1=>"�Ͽ�����, ��)12344 �� 12340","�ʿ�����, ��)12344 �� 12300","�������, ��)12344 �� 12000","õ������, ��)12344 �� 10000");
																		$arcnt = count($arfloor);
																		for($i=1;$i<$arcnt;$i++){
																			$sel = ($amount_floor==$i)?" selected":'';
																	?>
																	<option value="<?=$i?>" <?=$sel?>>
																	<?=$arfloor[$i]?>
																	</option>
																	<?											} ?>
																</SELECT>
															</td>
														</tr>
														<tr>
															<th>���� ���� �ݾ�</th>
															<td>
																<INPUT onclick=nomoney(1) type=radio CHECKED name=checksale>
																���� ����  &nbsp;
																<INPUT onclick=nomoney(0) type=radio name=checksale>
																<INPUT onkeyup=strnumkeyup(this); disabled maxLength=10 size=10 name=mini_price class="input_disabled">
																�� �̻� �ֹ��� ����
																<SCRIPT>nomoney(1);</SCRIPT>
															</td>
														</tr>
														<tr>
															<th>������밡�� �������</th>
															<td>
																<INPUT type=radio CHECKED value=N name=bank_only>
																���� ����  &nbsp;
																<INPUT type=radio value=Y name=bank_only>
																<B>���� ����</B>�� ����(�ǽð� ������ü ����) </td>
														</tr>
														<tr>
															<th>���� �߱�����</th>
															<td>
																<INPUT onclick="toggleDownType(false)" type=radio CHECKED value=N name=issue_type>
																��ڹ߱�&nbsp;&nbsp;&nbsp;&nbsp;<span class="font_orange">* ������� �� [������ ���� ��� �߱�](�����߱޴�⸮��Ʈ) ���� ��ڰ� Ư��ȸ������ �߱�.</span><BR>
																<INPUT onclick="toggleDownType(true)" type=radio value=Y name=issue_type>
																ȸ������ �ٿ�ε�&nbsp;&nbsp;&nbsp;&nbsp;<span class="font_orange">* �α��� �� ȸ���� ���� �ٿ�ε� Ŭ���� �߱� </span><BR>
																<INPUT onclick="toggleDownType(false)" type=radio value=M name=issue_type>
																ȸ�� ���Խ� �ڵ��߱�</td>
														</tr>
														<!--
														<tr>
															<th>�߱޴��ȸ�����</th>
															<td>&nbsp;   </td>
														</tr> -->
														<tr class="downOnly" style="display:none">
															<th>���� ���� ��</th>
															<td class="td_con1">
																<INPUT onclick=nonum(1) type=radio CHECKED name=checknum>
																������ &nbsp;
																<INPUT onclick=nonum(0) type=radio name=checknum>
																<INPUT onkeyup=strnumkeyup(this); disabled maxLength=10 size=10 name=issue_tot_no class="input">
																�� ����
																<SCRIPT>nonum(1);</SCRIPT>
															</td>
														</tr>
														<tr class="downOnly" style="display:none">
															<th>���� �ڵ����� ����</th>
															<td> ��ǰ �������� �󼼼��� ��ܿ� ������ �ڵ�
																<SELECT name=detail_auto class="select">
																	<OPTION value=Y selected>������</OPTION>
																	<OPTION value=N>�������</OPTION>
																</SELECT>
																<IMG height=5 width=0><BR>
																<span class="font_orange"> * ȸ���� ���� ������ Ŭ�������μ� �߱޹��� �� �ִ� �����Դϴ�.</span> </td>
														</tr>
														<tr class="downOnly" style="display:none">
															<th>�� �ٿ�ε� </th>
															<td>
																<INPUT type=radio value=N name=repeat_id checked>
																�Ұ��� &nbsp;
																<INPUT type=radio value=Y name=repeat_id>
																���� </td>
														</tr>
														<?
															if( false ) {
														?>
														<tr class="downOnly" style="display:none">
															<th>���� ��� �� �ڵ� ��߱�</th>
															<td>
																<INPUT type=radio value=Y name=repeat_ok>
																���  &nbsp;
																<INPUT type=radio value=N name=repeat_ok checked>
																������ </td>
														</tr>
														<?
															}
														?>
														<tr>
															<th style="width:160px;">�����ǰ �Ǵ�<br />ī�װ� ����</th>
															<td>
																<input type=radio name=codegbn value="A" checked onclick="ChangeCodegbn('A')">
																��ü��ǰ&nbsp;&nbsp;
																<input type=radio name=codegbn value="N" onclick="ChangeCodegbn('N')">
																�Ϻ� ī�װ�/��ǰ
																<div id=layer_codelist style="display:none; width:680px;">
																	<select name=codelist size=10 style="WIDTH:470px; float:left" class="select">
																		<option value="" style="BACKGROUND-COLOR: #ffff00">------------------------- ���� ��ǰ���� �����ϼ���. -------------------------</option>
																	</select>
																	<div style="width:200px;"> <a href="javascript:ChoiceProduct();"><img src="images/btn_add1.gif" hspace="2"></a> &nbsp; <a href="javascript:CodeDelete();"><img src="images/btn_del.gif" hspace="2"></a> </div>
																</div>
															</td>
														</tr>
														<tr id="layer1" style="display:none;">
															<th style="width:">���� �������</th>
															<td>
																<INPUT type=checkbox CHECKED value=Y name=use_con_type1>
																�ٸ� ��ǰ�� �Բ� ���Žÿ���, �ش� ������ ����մϴ�.<BR>
																<INPUT type=checkbox value=N name=use_con_type2>
																���õ� ī�װ�(��ǰ)�� �����ϰ� �����մϴ�. </td>
														</tr>
													</table>

													<div><IMG SRC="images/market_couponnew_stitle2.gif" WIDTH="192" HEIGHT=31 ALT="���� �ΰ����� �Է�"></div>
													<table  cellSpacing=0 cellPadding=0 width="100%" border=0  class="cinputTbl">
														<tr>
															<th style="width:160px;">ȸ����޺� ����<br />(����/����)�� ����<br /> ���� ����</th>
															<td><input type="radio" name="use_point" value="Y" checked="checked" />��������&nbsp;&nbsp;<input type="radio" name="use_point" value="A" />������ ����</td>
														</tr>
														<tr>
															<th>����ǰ �� <br />�������뿩��</th>
															<td>
																<input type=checkbox name=etcapply_gift value=A> �� ������ ����� ��� ����ǰ�� �������� �ʽ��ϴ�. </td>
														</tr>

														<tr>
															<th>�ϳ��� �ֹ���<br /> �ߺ���밡�� ����</th>
															<td>
																<INPUT type=radio value=Y name=order_limit CHECKED>�ߺ� ���Ұ�
																<INPUT type=radio value=N name=order_limit>���Ѿ��� �ߺ���밡��  &nbsp;
															</td>
														</tr>
														<tr>
															<th>���� �̹��� ����</th>
															<td>
																<INPUT type=radio CHECKED name=useimg>
																�⺻ �̹��� ���<br>
																<IMG src="images/sample/market_couponsampleimg.gif" width="352" height="122" style="margin-bottom:5px;"><br />
																<INPUT type=radio name=useimg>
																�������� �̹��� ���<span class="font_orange">(*GIF ���� 150KB ���Ϸ� �÷��ֽð�, ���� ������� 350*150 �Դϴ�.)</span><br />
																<INPUT type=file size=65 name=couponimg class="input">
															</td>
														</tr>
													</table>
												</form>
												<form name=form2 action="coupon_productchoice.php" method=post target=coupon_product>
												</form>
												<div style=" margin-top:10px; margin-bottom:25px; text-align:center"><a href="javascript:CheckForm(document.form1);"><img src="images/btn_cupon.gif" width="139" height="38" border="0"></a></div>
												<div style="margin-bottom:50px;">
													<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
														<tr>
															<td><IMG SRC="images/manual_top1.gif" WIDTH=15 height="45" ALT=""></td>
															<td><IMG SRC="images/manual_title.gif" WIDTH=113 height="45" ALT=""></td>
															<td width="100%" background="images/manual_bg.gif" height="35"></td>
															<td background="images/manual_bg.gif"></td>
															<td background="images/manual_bg.gif"><IMG SRC="images/manual_top2.gif" WIDTH=18 height="45" ALT=""></td>
														</tr>
														<tr>
															<td background="images/manual_left1.gif"></td>
															<td COLSPAN=3 width="100%" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;" class="menual_bg">
																<table cellpadding="0" cellspacing="0" width="100%">
																	<col width=20></col>
																	<col width=></col>
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
																		<td>�߻��� ������ �α��� �� ���������� �������� Ȯ�� �� �� �ֽ��ϴ�.</td>
																	</tr>
																</table>
															</td>
															<td background="images/manual_right1.gif"></td>
														</tr>
														<tr>
															<td><IMG SRC="images/manual_left2.gif" WIDTH=15 HEIGHT=8 ALT=""></td>
															<td COLSPAN=3 background="images/manual_down.gif"></td>
															<td><IMG SRC="images/manual_right2.gif" WIDTH=18 HEIGHT=8 ALT=""></td>
														</tr>
													</TABLE>
												</div>
											</td>
											<td width="16" background="images/con_t_02_bg.gif"></td>
										</tr>
										<tr>
											<td width="16"><img src="images/con_t_04.gif" width="16" height="16" border="0"></td>
											<td background="images/con_t_04_bg.gif"></td>
											<td width="16"><img src="images/con_t_03.gif" width="16" height="16" border="0"></td>
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
</table>
<?=$onload?>

<?
	if( $popup != "OK" ) {
		INCLUDE "copyright.php";
	}
?>