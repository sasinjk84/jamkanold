<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "ma-3";
$MenuCode = "market";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$CurrentTime = time();
$date_start=$_POST["date_start"];
$date_end=$_POST["date_end"];
$date_start=$date_start?$date_start:date("Y-m-d",$CurrentTime);
$date_end=$date_end?$date_end:date("Y-m-d",$CurrentTime);

$type=$_POST["type"];
$coupon_code = $_POST["coupon_code"];
$coupon_name=$_POST["coupon_name"];
$description=$_POST["description"];
$sale_type=$_POST["sale_type"];
$sale_money=$_POST["sale_money"];
$mini_price=$_POST["mini_price"];
$number_type=$_POST["number_type"];
$publish_limit=$_POST["publish_limit"];
$couponimg=$_FILES["couponimg"];

$block=$_POST["block"];
$gotopage=$_POST["gotopage"];

$imagepath=$Dir.DataDir."shopimages/etc/";
if ($type=="insert") {
	$coupon_code=substr(ceil(date("sHi").date("ds")/10*8)."000",0,8);

	if($couponimg[size] < 153600) {
		if (strlen($couponimg[name])>0 && file_exists($couponimg[tmp_name])) {
			$ext = strtolower(substr($couponimg[name],strlen($couponimg[name])-3,3));
			if ($ext=="gif") {
				$imagename = "PAPER_CP".$coupon_code.".gif";
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
	if(strlen($sale_money)==0) $sale_money=0; 
	$date_start = str_replace("-","",$date_start)."00";
	$date_end = str_replace("-","",$date_end)."23";

	$sql = "INSERT tblpapercoupon SET ";
	$sql.= "coupon_code		= '".$coupon_code."', ";
	$sql.= "coupon_name		= '".$coupon_name."', ";
	$sql.= "description		= '".$description."', ";
	$sql.= "date_start		= '".$date_start."', ";
	$sql.= "date_end		= '".$date_end."', ";
	$sql.= "sale_type		= '".$sale_type."', ";
	$sql.= "sale_money		= ".$sale_money.", ";
	$sql.= "mini_price		= ".$mini_price.", ";
	$sql.= "number_type		= '".$number_type."', ";
	$sql.= "publish_limit	= '".$publish_limit."', ";
	$sql.= "date			= '".date("YmdHis")."', ";
	$sql.= "display			= 'N' ";
	//echo $sql;
	mysql_query($sql,get_db_conn());

	if($number_type =="A"){
		makeCoupon_code($coupon_code);
	}

	echo "<body onload=\"location.href='market_papercoupon.php';\"></body>";
	exit;
}else if ($type =="update"){

	if($couponimg[size] < 153600) {
		if (strlen($couponimg[name])>0 && file_exists($couponimg[tmp_name])) {
			$ext = strtolower(substr($couponimg[name],strlen($couponimg[name])-3,3));
			if ($ext=="gif") {
				$imagename = "PAPER_CP".$coupon_code.".gif";
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
	if(strlen($sale_money)==0) $sale_money=0; 
	$date_start = str_replace("-","",$date_start)."00";
	$date_end = str_replace("-","",$date_end)."23";

	$sql = "UPDATE tblpapercoupon SET ";
	$sql.= "coupon_name		= '".$coupon_name."', ";
	$sql.= "description		= '".$description."', ";
	$sql.= "date_start		= '".$date_start."', ";
	$sql.= "date_end		= '".$date_end."', ";
	$sql.= "sale_type		= '".$sale_type."', ";
	$sql.= "sale_money		= ".$sale_money.", ";
	$sql.= "mini_price		= ".$mini_price.", ";
	$sql.= "number_type		= '".$number_type."', ";
	$sql.= "publish_limit	= '".$publish_limit."', ";
	$sql.= "date			= '".date("YmdHis")."' ";
	$sql.= "WHERE coupon_code = '".$coupon_code."'";
	//echo $sql;
	mysql_query($sql,get_db_conn());
	$type = "view";


}

if($type == "view"){
	
	$sql = "SELECT A.*, ";
	$sql.= "(SELECT coupon_number FROM tblpapercoupon_code B WHERE A.coupon_code = B.coupon_code ) coupon_number ";
	$sql.= "FROM tblpapercoupon A ";
	$sql.= "WHERE coupon_code='".$coupon_code."' ";
	$result = mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_array($result)) {
		$coupon_number=substr($row["coupon_number"],0,4)."-".substr($row["coupon_number"],4,4)."-".substr($row["coupon_number"],8,4)."-".substr($row["coupon_number"],12,4);
		$coupon_name=$row["coupon_name"];
		$description=$row["description"];
		$sale_type=$row["sale_type"];
		$sale_money=$row["sale_money"];
		$mini_price=$row["mini_price"];
		$number_type=$row["number_type"];
		$publish_limit=$row["publish_limit"];
		$display=$row["display"];
		$date_start = substr($row["date_start"],0,4)."-".substr($row["date_start"],4,2)."-".substr($row["date_start"],6,2);
		$date_end = substr($row["date_end"],0,4)."-".substr($row["date_end"],4,2)."-".substr($row["date_end"],6,2);
		$couponImg ="";
		if(file_exists($imagepath."PAPER_CP".$coupon_code.".gif")) {
			$couponImg = $imagepath."PAPER_CP".$coupon_code.".gif";
		}
		$disabled = ($display != "N")? "disabled":"";
	}
	
}

function makeCoupon_code($coupon_code){
	$arr_no=array("1","2","3","4","5","6","7","8","9","0");
	$arr_alphabet=array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");

	$str="";
	for ($i=0; $i<16; $i++){
		if (rand(0,1)==0)
			$str.=$arr_no[rand(0,(count($arr_no)-1))];
		else
			$str.=$arr_alphabet[rand(0,(count($arr_alphabet)-1))];
	}

	// �ش� ��ȣ�� DB �ִ� �ߺ���ȣ�ΰ� üũ 
	$query = "select count(idx) from tblpapercoupon_code where coupon_number='".$str."'";
	$result = mysql_query($query, get_db_conn());
	$col = mysql_fetch_row($result);

	if ($col[0]==0) {
		$query = "insert into tblpapercoupon_code VALUES ('', '".$coupon_code."', '".$str."' ,'N');"; 
		mysql_query($query,get_db_conn());
	}
}
?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script type="text/javascript" src="calendar.js.php"></script>
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

	date = "<?=date("Y-m-d");?>";
	if (form.date_start.value<date || form.date_end.value<date || form.date_start.value>form.date_end.value) {
		alert("���� ��ȿ�Ⱓ ������ �߸��Ǿ����ϴ�.\n\n�ٽ� Ȯ���Ͻñ� �ٶ��ϴ�.");
		form.date_start.focus();
		return;
	}
	content+="* ���� ��ȿ�Ⱓ : "+form.date_start.value+" ~ "+form.date_end.value+" ����\n\n";

	if (form.sale_money.value.length==0) {
		alert("���� ���� �ݾ�/���η��� �Է��ϼ���.");
		form.sale_money.focus();
		return;
	} else if (!IsNumeric(form.sale_money.value)) {
		alert("���� ���� �ݾ�/���η��� ���ڸ� �Է� �����մϴ�.(�Ҽ��� �Է� �ȵ�)");
		form.sale_money.focus();
		return;
	}
	if(form.sale_type.selectedIndex==1 && form.sale_money.value>=100){
		alert("���� ���η��� 100���� �۾ƾ� �մϴ�.");
		form.sale_money.focus();
		return;
	}
	content+="* ���� �ݾ�/���η� : "+form.sale_money.value+form.sale_type.options[form.sale_type.selectedIndex].txt+"\n\n";

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
/*
	if(form.checknum[1].checked==true){
		if(form.publish_limit.value.length==0){
			alert("���� ������� �Է��ϼ���.");
			form.publish_limit.focus();
			return;
		}else if(!IsNumeric(form.publish_limit.value)){
			alert("���� ������� ���ڸ� �Է� �����մϴ�.(�Ҽ��� �Է� �ȵ�)");
			form.publish_limit.focus();
			return;
		}else if(form.publish_limit.value<=0) {
			alert("���� ���� �ż��� �Է��ϼ���.");
			form.publish_limit.focus();
			return;
		}
		content+="* ���� ������ : "+form.publish_limit.value+"��\n\n";
	} else {
		content+="* ���� ������ : ������\n\n";
	}
*/

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
function updateForm(form) {
	if(confirm("�����Ͻðڽ��ϱ�?")){
		form.type.value="update";
		form.submit();
	}
}
function changerate(){  
	var rate =document.form1.sale_type.options[document.form1.sale_type.selectedIndex].txt;
	document.form1.rate.value=rate;
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
		document.form1.publish_limit.value="";
		document.form1.publish_limit.disabled=true;
		document.form1.publish_limit.style.background='#F0F0F0';
		document.form1.checknum[0].checked=true;
	} else {
		document.form1.publish_limit.value="0";
		document.form1.publish_limit.disabled=false;
		document.form1.publish_limit.style.background='white';
		document.form1.checknum[1].checked=true;
	}
}

function goList() {
	document.form1.type.value = "";
	document.form1.coupon_code.value = "";
	document.form1.method = "get";
	document.form1.action = "market_papercoupon.php";
	document.form1.submit();
}
</script>
<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
<tr>
	<td valign="top">
	<table cellpadding="0" cellspacing="0" width=100% style="table-layout:fixed">
	<tr>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed"  background="images/con_bg.gif">
		<col width=198></col>
		<col width=10></col>
		<col width=></col>
		<tr>
			<td valign="top"  background="images/leftmenu_bg.gif">
			<? include ("menu_market.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ���������� &gt; �������� ���� ���� &gt; <span class="2depth_select">������ ���� �����ϱ�</span></td>
			</tr>
			</table>
		</td>
	</tr>   
	<tr>
        <td width="16"><img src="images/con_t_01.gif" width="16" height="16" border="0"></td>
        <td background="images/con_t_01_bg.gif"></td>
        <td width="16"><img src="images/con_t_02.gif" width="16" height="16" border="0"></td>
    </tr>
    <tr>
        <td width="16" background="images/con_t_04_bg1.gif"></td>
        <td bgcolor="#ffffff" style="padding:10px">


			<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post enctype="multipart/form-data">
			<input type=hidden name=type>
			<input type=hidden name=coupon_code value="<?=$coupon_code?>">
			<input type=hidden name=block value="<?=$block?>">
			<input type=hidden name=gotopage value="<?=$gotopage?>">
			<tr><td height="8"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/market_papercoupon_title2.gif" ALT="������ ���� ���"></TD>
					</tr><tr>
					<TD width="100%" background="images/title_bg.gif" height="21"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height="3"></td></tr>
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
					<TD width="100%" class="notice_blue">ȸ���鿡�� �����Ӱ� �������� ���񽺸� ������ �� �ֽ��ϴ�.</TD>
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
			<tr><td height="20"></td></tr>
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
			<tr><td height=30><?=($disabled)?"<span style=\"color:red\"><b>* �������� ������ �����ϽǼ������ϴ�.</b></span>":""?></td></tr>
			<tr><td height=3></td></tr>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0 style="table-layout:fixed">
				<col width=160></col>
				<col width=></col>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">���� �̸�</TD>
					<TD class="td_con1"><INPUT maxLength=100 size=70 name=coupon_name class="input" value="<?=$coupon_name?>" <?=$disabled?>><br><span class="font_orange"><b>��)�� ������10% ���������̺�Ʈ~</b></span></TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">���� ����</TD>
					<TD class="td_con1"><INPUT maxLength=200 size=91 name=description style=width:99% class="input"  value="<?=$description?>" <?=$disabled?>> <br><span class="font_orange"> * �Է��� ���������� �����̹��� ��ܿ� ��µ˴ϴ�.</span></TD>
				</tr>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">��ȿ�Ⱓ</TD>
					<TD class="td_con1"><INPUT onfocus=this.blur(); onclick=Calendar(this) size=10 name=date_start value="<?=$date_start?>" class="input_selected" <?=$disabled?>> ���� <INPUT  onfocus=this.blur(); onclick=Calendar(this) size=10 name=date_end value="<?=$date_end?>" class="input_selected" <?=$disabled?>> ���� ��밡��<span class="font_orange">(��ȿ�Ⱓ: ������ 00�ú��� �������� 23��59��59�� ����)</span></TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�ݾ�/������ ����</TD>
					<TD class="td_con1">
					<SELECT style="WIDTH: 100px" onchange="changerate();" name=sale_type class="select" <?=$disabled?>>
					<OPTION value="A" txt="��" <?=($sale_type=="A")?"selected":""?>>�ݾ�</OPTION>
					<OPTION value="B" txt="%" <?=($sale_type=="B")?"selected":""?>>������</OPTION>
					</SELECT>
					�� 
					<INPUT onkeyup="strnumkeyup(this);" style="PADDING-RIGHT: 5px; TEXT-ALIGN: right" maxLength=10 size=10 name=sale_money class="input" value="<?=$sale_money?>" <?=$disabled?>> <INPUT class="input_hide1" readOnly size=1 value=<?=($sale_type=="B")?"%":"��"?> name=rate>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">���� ���� �ݾ�</TD>
					<TD class="td_con1">
					<INPUT onclick=nomoney(1) type=radio name=checksale <?=($mini_price=="0")?"checked":""?> <?=$disabled?>>���� ����  &nbsp;
					<INPUT onclick=nomoney(0) type=radio name=checksale <?=($mini_price>0)?"checked":""?> <?=$disabled?>><INPUT onkeyup=strnumkeyup(this); maxLength=10 size=10 name=mini_price class="input_disabled"  value="<?=$mini_price?>" <?=$disabled?>>�� �̻� �ֹ��� ����
					<?if($type!="view"){?><SCRIPT>nomoney(1);</SCRIPT><?}?>
					</TD>
				</tr>
				<INPUT type=hidden CHECKED name=number_type value="A" />
				<!-- <TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">������ȣ����</TD>
					<TD class="td_con1">
					<INPUT type=radio CHECKED name=number_type value="A">������ ��ȣ�� �ڵ����� &nbsp;
					<INPUT type=radio CHECKED name=number_type value="B">�ٸ���ȣ�� �ڵ����� <br>
					<span class="font_orange">����� ������ȣ�� ������ �Ұ��� �մϴ�.</span>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">���� ���� ��</TD>
					<TD class="td_con1">
					<INPUT onclick=nonum(1) type=radio name=checknum <?=($publish_limit=="0")?"checked":""?> <?=$disabled?>>������ &nbsp;
					<INPUT onclick=nonum(0) type=radio name=checknum <?=($publish_limit>0)?"checked":""?> <?=$disabled?>><INPUT onkeyup=strnumkeyup(this); disabled maxLength=10 size=10 name=publish_limit class="input" value="<?=$publish_limit?>" <?=$disabled?>>�� ����
					<?if($type!="view"){?><SCRIPT>nonum(<?=(publish_limit>0)?"0":"1"?>);</SCRIPT><?}?>
					</TD>
				</TR> -->
<?if($coupon_number){?>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�������� ��ȣ</TD>
					<TD class="td_con1"><span class="font_blue"><b><?=$coupon_number?></b></span></TD>
				</TR>
<?}?>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">���� �̹��� ����</TD>
					<TD class="td_con1">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td>
						<table cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td><INPUT type=radio name=useimg <?=($couponImg=="")?"checked":""?> <?=$disabled?>>�⺻ �̹��� ���<br></td>
						</tr>
						<tr>
							<td><IMG src="images/sample/market_couponsampleimg.gif" width="352" height="122"></td>
						</tr>
						</table>
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>
						<table cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td><IMG height=3 width=0><INPUT type=radio name=useimg <?=($couponImg!="")?"checked":""?> <?=$disabled?>>�������� �̹��� ���<span class="font_orange">(*GIF ���� 150KB ���Ϸ� �÷��ֽð�, ���� ������� 350*150 �Դϴ�.)</span>
							<?=($couponImg!="")?"<br><img src='".$couponImg."' width='350' />":""?></td>
						</tr>
						<tr>
							<td><INPUT type=file size=65 name=couponimg class="input" <?=$disabled?>></td>
						</tr>
						</table>
						</td>
					</tr>
					</table>
					</TD>
				</TR>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height="30" align="left"><input type="button" value="��Ϻ���" onclick="goList();"></td></tr>
			<tr>
				<td align=center><a href="javascript:<?=($type=="view")? "updateForm":"CheckForm"?>(document.form1);"><img src="images/btn_cupon.gif" width="139" height="38" border="0"></a></td>
			</tr>
			<tr>
				<td height="25"></td>
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
			<tr><td height="50"></td></tr>
			</form>
			</table>


					</td>
					<td width="16" background="images/con_t_02_bg.gif"></td>
				</tr>
				<tr>
					<td width="16"><img src="images/con_t_04.gif" width="16" height="16" border="0"></td>
					<td background="images/con_t_04_bg.gif"></td>
					<td width="16"><img src="images/con_t_03.gif" width="16" height="16" border="0"></td>
				</tr>
				<tr><td height="20"></td></tr>
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
<? INCLUDE "copyright.php"; ?>