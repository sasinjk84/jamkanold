<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "pr-5";
$MenuCode = "product";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$imagepath=$Dir.DataDir."shopimages/etc/";

$setup[page_num] = 10;
$setup[list_num] = 15;

$sort=$_REQUEST["sort"];
$block=$_REQUEST["block"];
$gotopage=$_REQUEST["gotopage"];

if ($block != "") {
	$nowblock = $block;
	$curpage  = $block * $setup[page_num] + $gotopage;
} else {
	$nowblock = 0;
}

if (($gotopage == "") || ($gotopage == 0)) {
	$gotopage = 1;
}


$type=$_POST["type"];

//ȯ�漳��
$up_gift_type1=$_POST["up_gift_type1"];	//����ǰ ���� ��뿩��
$up_gift_type2=$_POST["up_gift_type2"];	//���߼��� ����ǰ ��뿩��
$up_gift_type3=$_POST["up_gift_type3"];	//�������
$up_gift_type4=$_POST["up_gift_type4"];	//���������������� ���� ���ɿ���
if(strlen($up_gift_type1)==0) $up_gift_type1="N";
if(strlen($up_gift_type2)==0) $up_gift_type2="N";
if(strlen($up_gift_type3)==0) $up_gift_type3="A";
if(strlen($up_gift_type4)==0) $up_gift_type4="N";

//����ǰ ���
$gift_name=$_POST["gift_name"];
$gift_startprice=$_POST["gift_startprice"];
$gift_endprice=$_POST["gift_endprice"];
$gift_quantity=$_POST["gift_quantity"];
$gift_limit=$_POST["gift_limit"];
$option1_title=$_POST["option1_title"];
$option1_value=$_POST["option1_value"];
$option2_title=$_POST["option2_title"];
$option2_value=$_POST["option2_value"];
$option3_title=$_POST["option3_title"];
$option3_value=$_POST["option3_value"];
$option4_title=$_POST["option4_title"];
$option4_value=$_POST["option4_value"];
$gift_image=$_FILES["gift_image"];

$gift_regdate=$_POST["gift_regdate"];

if ($type=="config") {
	$gift_type=$up_gift_type1."|".$up_gift_type2."|".$up_gift_type3."|".$up_gift_type4;
	$sql = "UPDATE tblshopinfo SET ";
	$sql.= "gift_type	= '".$gift_type."' ";
	mysql_query($sql,get_db_conn());
	DeleteCache("tblshopinfo.cache");
	$onload="<script>alert('�� ����ǰ ������ �Ϸ�Ǿ����ϴ�.');</script>";
} else if ($type=="insert" || $type=="edit") {
	$maxcnt=99999999;
	if ($gift_limit<0 || $gift_limit>99) {
		echo "<script> alert ('����ǰ �������� ���� �Է��� �߸��Ǿ����ϴ�.');location='".$_SERVER[PHP_SELF]."';</script>\n";
		exit;
	}
	$sql = "SELECT COUNT(*) as cnt FROM tblgiftinfo ";
	$result = mysql_query($sql,get_db_conn());
	$row = mysql_fetch_object($result);
	mysql_free_result($result);
	if ($row->cnt>=$maxcnt) {
		echo "<script> alert ('����ǰ ����� �ִ� 100�� �����Դϴ�.');location='".$_SERVER[PHP_SELF]."';</script>\n";
		exit;
	}
	$curdate=date("YmdHis");
	if (strlen($gift_endprice)==0) {
		$gift_endprice=16777215;
	}
	$sql_quantity=(strlen($gift_quantity)==0)?"NULL":$gift_quantity;
	$sql_limit=(strlen($gift_limit)==0)?0:$gift_limit;
	if (strlen($gift_image["name"])>0) {
		if ($gift_image["size"]>153600 || $gift_image["size"]==0) {
			echo "<script>alert ('��� ������ ����ǰ �̹����� �뷮�� 150KB���Ϸ� ���ѵ˴ϴ�.');location='".$_SERVER[PHP_SELF]."';</script>\n";
			exit;
		}
		$getsize=getimageSize($gift_image["tmp_name"]);
		$width=$getsize[0];
		$height=$getsize[1];
		$imgtype=$getsize[2];
		$size_limit=200;
		if ($imgtype==1 || $imgtype==2 || $imgtype==3) {
			if ($imgtype==1) $ext="gif";
			else if ($imgtype==2) $ext="jpg";
			else if ($imgtype==3) $ext="png";
			if ($width>$size_limit || $height>$size_limit) {
				if($imgtype==1)      $im = ImageCreateFromGif($gift_image["tmp_name"]);
				else if($imgtype==2) $im = ImageCreateFromJpeg($gift_image["tmp_name"]);
				else if($imgtype==3) $im = ImageCreateFromPng($gift_image["tmp_name"]);
				if ($width>=$height) {
					$small_width=$size_limit;
					$small_height=($height*$size_limit)/$width;
				} else if($width<$height) {
					$small_width=($width*$size_limit)/$height;
					$small_height=$size_limit;
				}
				if ($imgtype==1) {
					$im2=ImageCreate($small_width,$small_height); // GIF�ϰ��
					$white = ImageColorAllocate($im2, 255,255,255);
					imagefill($im2,1,1,$white);
					ImageCopyResized($im2,$im,0,0,0,0,$small_width,$small_height,$width,$height);
					imageGIF($im2,$gift_image["tmp_name"]);
				} else if ($imgtype==2) {
					$im2=ImageCreateTrueColor($small_width,$small_height); // JPG�ϰ��
					$white = ImageColorAllocate($im2, 255,255,255);
					imagefill($im2,1,1,$white);
					imagecopyresampled($im2,$im,0,0,0,0,$small_width,$small_height,$width,$height);
					imageJPEG($im2,$gift_image["tmp_name"],$quality);
				} else {
					$im2=ImageCreateTrueColor($small_width,$small_height); // PNG�ϰ��
					$white = ImageColorAllocate($im2, 255,255,255);
					imagefill($im2,1,1,$white);
					imagecopyresampled($im2,$im,0,0,0,0,$small_width,$small_height,$width,$height);
					imagePNG($im2,$gift_image["tmp_name"]);
				}
				ImageDestroy($im);
				ImageDestroy($im2);
			}
			$filename="gift_".$curdate.".".$ext;
			move_uploaded_file($gift_image["tmp_name"],$imagepath.$filename);
			chmod($imagepath.$filename,0666);
		} else {
			echo "<script> alert ('����ǰ �̹����� JPG, GIF, PNG ���ϸ� ��� �����մϴ�.');location='".$_SERVER[PHP_SELF]."';</script>\n";
			exit;
		}
	} else {
		$filename="";
	}
	$gift_option1=(strlen($option1_title)>0 && strlen($option1_value)>0)?str_replace(",","",$option1_title).",".trim($option1_value):"";
	$gift_option2=(strlen($option2_title)>0 && strlen($option2_value)>0)?str_replace(",","",$option2_title).",".trim($option2_value):"";
	$gift_option3=(strlen($option3_title)>0 && strlen($option3_value)>0)?str_replace(",","",$option3_title).",".trim($option3_value):"";
	$gift_option4=(strlen($option4_title)>0 && strlen($option4_value)>0)?str_replace(",","",$option4_title).",".trim($option4_value):"";

	$gift_option1 = str_replace(array(" :"," : ",": "),":",$gift_option1);
	$gift_option2 = str_replace(array(" :"," : ",": "),":",$gift_option2);
	$gift_option3 = str_replace(array(" :"," : ",": "),":",$gift_option3);
	$gift_option4 = str_replace(array(" :"," : ",": "),":",$gift_option4);

	if($type=="edit" ) {
		$sql = "UPDATE tblgiftinfo SET ";
		$sql.= "gift_startprice	= '".$gift_startprice."', ";
		$sql.= "gift_endprice	= '".$gift_endprice."', ";
		$sql.= "gift_quantity	= ".$sql_quantity.", ";
		$sql.= "gift_limit		= '".$sql_limit."', ";
		$sql.= "gift_name		= '".$gift_name."', ";
		if( $gift_image["size"] > 0 ) $sql.= "gift_image		= '".$filename."', ";
		$sql.= "gift_option1	= '".$gift_option1."', ";
		$sql.= "gift_option2	= '".$gift_option2."', ";
		$sql.= "gift_option3	= '".$gift_option3."', ";
		$sql.= "gift_option4	= '".$gift_option4."' ";
		$sql.= "WHERE gift_regdate = '".$gift_regdate."' ";
		mysql_query($sql,get_db_conn());
		$onload="<script> alert ('����ǰ ������ �Ϸ�Ǿ����ϴ�.'); </script>\n";
	}
	else {
		$sql = "INSERT tblgiftinfo SET ";
		$sql.= "gift_regdate	= '".$curdate."', ";
		$sql.= "gift_startprice	= '".$gift_startprice."', ";
		$sql.= "gift_endprice	= '".$gift_endprice."', ";
		$sql.= "gift_quantity	= ".$sql_quantity.", ";
		$sql.= "gift_limit		= '".$sql_limit."', ";
		$sql.= "gift_name		= '".$gift_name."', ";
		if( $gift_image["size"] > 0 ) $sql.= "gift_image		= '".$filename."', ";
		$sql.= "gift_option1	= '".$gift_option1."', ";
		$sql.= "gift_option2	= '".$gift_option2."', ";
		$sql.= "gift_option3	= '".$gift_option3."', ";
		$sql.= "gift_option4	= '".$gift_option4."' ";
		mysql_query($sql,get_db_conn());
		$onload="<script> alert ('����ǰ ����� �Ϸ�Ǿ����ϴ�.'); </script>\n";
	}
} else if ($type=="delete") {
	if (strlen($gift_regdate)>0) {
		$sql = "SELECT * FROM tblgiftinfo WHERE gift_regdate = '".$gift_regdate."' ";
		$result = mysql_query($sql,get_db_conn());
		if ($row = mysql_fetch_object($result)) {
			if ($row->gift_image) {
				unlink($imagepath.$row->gift_image);
			}
			$sql = "DELETE FROM tblgiftinfo WHERE gift_regdate = '".$gift_regdate."' ";
			mysql_query($sql,get_db_conn());
			$onload="<script>alert('�ش� ����ǰ ���� ������ �Ϸ�Ǿ����ϴ�.');</script>";
		}
		mysql_free_result($result);
	}
} else if ($type=="quantity") {
	if (strlen($gift_quantity)==0) {
		$gift_quantity = "NULL";
	}
	$sql = "UPDATE tblgiftinfo SET gift_quantity = '".$gift_quantity."' ";
	$sql.= "WHERE gift_regdate = '".$gift_regdate."' ";
	mysql_query($sql,get_db_conn());
	$onload="<script>alert('�����Ͻ� ����ǰ ������ ������ �Ϸ�Ǿ����ϴ�.');</script>";
}

$sql = "SELECT gift_type FROM tblshopinfo ";
$result = mysql_query($sql,get_db_conn());
$row = mysql_fetch_object($result);
mysql_free_result($result);
$gift_type = explode("|",$row->gift_type);
if(strlen($gift_type[0])==0) $gift_type[0]="N";
if(strlen($gift_type[1])==0) $gift_type[1]="N";
if(strlen($gift_type[2])==0) $gift_type[2]="A";
if(strlen($gift_type[3])==0) $gift_type[3]="N";

${"chk_gift1".$gift_type[0]} = "checked";
${"chk_gift2".$gift_type[1]} = "checked";
${"chk_gift3".$gift_type[2]} = "checked";
${"chk_gift4".$gift_type[3]} = "checked";

?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function CheckForm1() {
	document.form1.type.value="config";
	document.form1.submit();
}

function CheckForm2() {
	form=document.form2;
	if (form.gift_name.value.length==0) {
		alert ("����ǰ�� �̸��� �Է��ϼ���.");
		form.gift_name.focus();
		return;
	}
	if (isNaN(form.gift_startprice.value) || form.gift_startprice.value.length==0) {
		alert ("���Ű��ݹ����� ���ڷ� �Է��ϼ���.");
		form.gift_startprice.focus();
		return;
	}
	if (form.between.selectedIndex==0 && (isNaN(form.gift_endprice.value) || form.gift_endprice.value.length==0)) {
		alert ("���Ű��ݹ����� ���ڷ� �Է��ϼ���.");
		form.gift_endprice.focus();
		return;
	}
	if (parseInt(form.gift_startprice.value)<=0) {
		alert ("���Ź��� ���۰��� 1���̻� �Է��ϼž� �մϴ�..");
		form.gift_startprice.focus();
		return;
	}
	if (form.between.selectedIndex==0 && (parseInt(form.gift_startprice.value) >= parseInt(form.gift_endprice.value))) {
		alert ("���Ź��� ���۰��� ���Ź��� ���ᰡ ���� ũ�ų� ���� �� �����ϴ�.");
		form.gift_startprice.focus();
		return;
	}
	if (form.quantity_choice[1].checked==true && (isNaN(form.gift_quantity.value) || form.gift_quantity.value.length==0)) {
		alert ("�������� ���ڷ� �Է��ϼ���.");
		form.gift_quantity.focus();
		return;
	}
	if (form.quantity_choice[1].checked==true && parseInt(form.gift_quantity.value) > 60000) {
		alert ("�������� 60000�� �̻��� ���� ���������� �����ϼ���");
		form.gift_quantity.focus();
		return;
	}
	if (form.limit_choice[1].checked==true && (isNaN(form.gift_limit.value) || parseInt(form.gift_limit.value)<1)) {
		alert ("���������� 1���� 99������ ���ڷθ� �Է��� �ϼž� �մϴ�.");
		form.gift_limit.focus();
		return;
	}
	if (form.option1_title.value.length>0 && form.option2_value.length==0) {
		alert ("�ɼ������� �Է��Ͻ� ���� �ݵ�� �Ӽ��� �Է��ϼž� �մϴ�.");
		form.option2_value.focus();
		return;
	}
	if (form.option2_title.value.length>0 && form.option2_value.length==0) {
		alert ("�ɼ������� �Է��Ͻ� ���� �ݵ�� �Ӽ��� �Է��ϼž� �մϴ�.");
		form.option2_value.focus();
		return;
	}
	if (form.option3_title.value.length>0 && form.option3_value.length==0) {
		alert ("�ɼ������� �Է��Ͻ� ���� �ݵ�� �Ӽ��� �Է��ϼž� �մϴ�.");
		form.option3_value.focus();
		return;
	}
	if (form.option4_title.value.length>0 && form.option4_value.length==0) {
		alert ("�ɼ������� �Է��Ͻ� ���� �ݵ�� �Ӽ��� �Է��ϼž� �մϴ�.");
		form.option4_value.focus();
		return;
	}
	if(form.type.value!='edit') form.type.value="insert";
	form.submit();
}

function between_check() {
	form=document.form2;
	if (form.between.selectedIndex==1) {
		form.gift_endprice.style.background="#F0F0F0";
		form.gift_endprice.disabled=true;
	} else if (form.between.selectedIndex==0) {
		form.gift_endprice.style.background="white";
		form.gift_endprice.disabled=false;
	}
}

function quantity_change(tmp) {
	form=document.form2;
	if (tmp=="flag") {
		form.gift_quantity.style.background="#F0F0F0";
		form.gift_quantity.disabled=true;
	} else if (tmp=="nonflag") {
		form.gift_quantity.style.background="white";
		form.gift_quantity.disabled=false;
	}
}

function limit_change(tmp) {
	form=document.form2;
	if (tmp=="flag") {
		form.gift_limit.style.background="#F0F0F0";
		form.gift_limit.disabled=true;
	} else if (tmp=="nonflag") {
		form.gift_limit.style.background="white";
		form.gift_limit.disabled=false;
	}
}

function delgift(code) {
	if (confirm("�����Ͻ� ����ǰ�� �����Ͻðڽ��ϱ�?")) {
		document.form3.type.value="delete";
		document.form3.gift_regdate.value=code;
		document.form3.submit();
	}
}

function modgift(code) {
	document.form3.type.value="modify";
	document.form3.gift_regdate.value=code;
	document.form3.submit();
}

function amount_up(code,no) {
	if (isNaN(document.form5["gift_quantity"+no].value) || document.form5["gift_quantity"+no].value.length==0) {
		document.form5["gift_quantity"+no].focus();
		alert("�������� ���ڸ� �Է� �����մϴ�.");
		return;
	}
	if (confirm("����ǰ �������� �����Ͻðڽ��ϱ�?")) {
		document.form3.type.value="quantity";
		document.form3.gift_regdate.value=code;
		document.form3.gift_quantity.value=document.form5["gift_quantity"+no].value;
		document.form3.submit();
	}
}

function overTip(boxObj) {
	try {
		boxObj.style.visibility = "visible";
	} catch (e) {}
}
function outTip(boxObj) {
	try {
		boxObj.style.visibility = "hidden";
	} catch (e) {}
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
			<? include ("menu_product.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ���������� &gt; �̺�Ʈ/����ǰ ��� ���� &gt; <span class="2depth_select">����ǰ ���� ����</span></td>
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




			<table cellpadding="0" cellspacing="0" width="100%">
			<tr><td height="8"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/product_giftlist_title.gif" ALT=""></TD>
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
					<TD width="100%" class="notice_blue">��ǰ �ֹ��� ���ݴ뺰�� ������ ������ ���� ����ǰ�� �����մϴ�.</TD>
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
					<TD><IMG SRC="images/product_giftlist_stitle1.gif" border="0"></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height=3 style="padding-top:3px; padding-bottom:3px;">
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/distribute_01.gif"></TD>
					<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
					<TD><IMG SRC="images/distribute_03.gif"></TD>
				</TR>
				<TR>
					<TD background="images/distribute_04.gif"></TD>
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
					<TD width="100%" class="notice_blue">1) <b><span class="font_orange">[����ǰ ��������]</span></b>�� ���� �ֹ��� �������űݾ��� ����Ʈ�� ȯ��(1:1)�Ͽ� ����Ʈ ������ ����ǰ�� ������ ������ �� �ֽ��ϴ�.<br>
					2) �ֹ���ȸ���������� ���� ���ɿ��δ� �����Ϸ� ���������� ������ ������ ��� �ֹ���ȸ���������� ���ð����ϰ� �Ұ������� �����մϴ�.</TD>
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
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<col width=190></col>
				<col width=></col>
				<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
				<input type=hidden name=type>
				<input type=hidden name=sort value="<?=$sort?>">
				<input type=hidden name=block value="<?=$block?>">
				<input type=hidden name=gotopage value="<?=$gotopage?>">
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">����ǰ ���� ��뿩��</TD>
					<TD class="td_con1">
					<INPUT id=idx_gift_type11 type=radio name=up_gift_type1 value=N <?=$chk_gift1N?> style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none"><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_gift_type11>������</LABEL>&nbsp;&nbsp;
					<INPUT id=idx_gift_type12 type=radio name=up_gift_type1 value=M <?=$chk_gift1M?> style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none"><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_gift_type12>ȸ�� �����ڸ� ��밡��</LABEL>&nbsp;&nbsp;&nbsp;
					<INPUT id=idx_gift_type13 type=radio name=up_gift_type1 value=C <?=$chk_gift1C?> style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none"><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand; TEXT-DECORATION: none" onmouseout="style.textDecoration='none'" for=idx_gift_type13>��� ������ ��밡��</LABEL></TD>
				</TR>
				<!-- <TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR> -->
				<!-- <TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">����ǰ �������� ��뿩��</TD>
					<TD class="td_con1">
					<INPUT id=idx_gift_type22 type=radio name=up_gift_type2 value=Y <?=$chk_gift2Y?> style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none"><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_gift_type22>�����</LABEL>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<INPUT id=idx_gift_type21 type=radio name=up_gift_type2 value=N <?=$chk_gift2N?> style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none"><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_gift_type21>������</LABEL>
					</TD>
				</TR> -->
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">��������� ���� ����ǰ ����</TD>
					<TD class="td_con1">
					<INPUT id=idx_gift_type31 type=radio value=A name=up_gift_type3 <?=$chk_gift3A?> style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none"><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand; TEXT-DECORATION: none" onmouseout="style.textDecoration='none'" for=idx_gift_type31>��� ���� ����</LABEL><!-- &nbsp;&nbsp;
					<INPUT id=idx_gift_type32 type=radio value=B name=up_gift_type3 <?=$chk_gift3B?> style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none"><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand; TEXT-DECORATION: none" onmouseout="style.textDecoration='none'" for=idx_gift_type32>���� ������ ����</LABEL> -->
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<!-- <TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�ֹ���ȸ����������<br>&nbsp;&nbsp;���� ���ɿ���</TD>
					<TD class="td_con1"> --><!--
					<INPUT id=idx_gift_type42 type=radio value=Y name=up_gift_type4 <?=$chk_gift4Y?> style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none"><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand; TEXT-DECORATION: none" onmouseout="style.textDecoration='none'" for=idx_gift_type42>���ð���</LABEL>&nbsp;&nbsp; -->
					<!-- <INPUT id=idx_gift_type41 type=radio value=N name=up_gift_type4 <?=$chk_gift4N?> style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none"><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand; TEXT-DECORATION: none" onmouseout="style.textDecoration='none'" for=idx_gift_type41>���úҰ�</LABEL> -->
					<!-- </TD>
				</TR> -->
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=10></td></tr>
			<tr>
				<td align=center><a href="javascript:CheckForm1();"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a></td>
			</tr>
			</form>
			<tr><td height="30"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/product_giftlist_stitle2.gif" border="0"></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=3></td></tr>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<col width=190></col>
				<col width=></col>
				<form name=form2 action="<?=$_SERVER[PHP_SELF]?>" method=post enctype="multipart/form-data">
<?
$etype = '';

$ckd1 = $ckd3 = "checked";

if($_POST['type']=='modify') {
	$sql = "SELECT * FROM tblgiftinfo WHERE gift_regdate = '".$gift_regdate."' ";
	$result = mysql_query($sql,get_db_conn());
	$rows = mysql_fetch_array($result);
	mysql_free_result($result);

	$etype = "edit";
	if(!$rows['gift_endprice']) $between = 2;
	else $between = 1;

	if($rows['gift_quantity']) {
		$ckd1 = '';
		$ckd2 = 'checked';
	}
	if($rows['gift_limit']) {
		$ckd3 = '';
		$ckd4 = 'checked';
	}
	else $rows['gift_limit'] = '';

	for($kk=1;$kk<5;$kk++) {
		$tmps = explode(",",$rows['gift_option'.$kk]);
		${"OPT".$kk} = $tmps[0];
		$tmps[0] = null;
		${"OPV".$kk} = substr(join(",",$tmps),1);
	}


}
?>
				<input type=hidden name=type value="<?=$etype?>">
				<input type=hidden name=sort value="<?=$sort?>">
				<input type=hidden name=block value="<?=$block?>">
				<input type=hidden name=gotopage value="<?=$gotopage?>">

				<? if($etype=='edit') { ?>
				<input type=hidden name=gift_regdate value="<?=$gift_regdate?>">
				<? } ?>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">����ǰ��</TD>
					<TD class="td_con1"><INPUT style=width:100% onkeydown=chkFieldMaxLen(200) maxLength=200 size=90 name=gift_name class="input" value="<?=$rows['gift_name'];?>"></TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">����ǰ ���� ���� ���Ű���</TD>
					<TD class="td_con1">
					<INPUT maxLength=8 size=8 name=gift_startprice class="input" value="<?=$rows['gift_startprice'];?>">��
					<SELECT onchange=between_check() name=between class="select">
					<OPTION value=1>����(�̻�)</OPTION>
					<OPTION value=2>�̻� ��簡��</OPTION>
					</SELECT>
					 ~&nbsp;
					 <INPUT maxLength=8 size=8 name=gift_endprice class="input" value="<?=$rows['gift_endprice'];?>"> �� ����(�̸�)
					 <span class="font_orange">* �޸�(,) ������ ���ڸ� �Է��� �ּ���.</span>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">����ǰ ������</TD>
					<TD class="td_con1">
					<INPUT id=idx_quantity_choice1 onclick="quantity_change('flag')" type=radio CHECKED value=endless name=quantity_choice <?=$ckd1?>><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_quantity_choice1>���Ѿ���</LABEL>&nbsp;
					<INPUT id=idx_quantity_choice2 onclick="quantity_change('nonflag')" type=radio value=end name=quantity_choice <?=$ckd2?>><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand; TEXT-DECORATION: none" onmouseout="style.textDecoration='none'" for=idx_quantity_choice2>����</LABEL>
					<INPUT  disabled maxLength="10" size="10" name=gift_quantity class="input_disabled" value="<?=$rows['gift_quantity'];?>"> ��
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">����ǰ ��������</TD>
					<TD class="td_con1">
					<INPUT id=idx_limit_choice1 onclick="limit_change('flag')" type=radio CHECKED value=endless name=limit_choice <?=$ckd3?>><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_limit_choice1>���Ѿ���</LABEL>&nbsp;
					<INPUT id=idx_limit_choice2 onclick="limit_change('nonflag')" type=radio value=end name=limit_choice <?=$ckd4?>><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand; TEXT-DECORATION: none" onmouseout="style.textDecoration='none'" for=idx_limit_choice2>����</LABEL>
					<INPUT  disabled maxLength="10" size="10" name=gift_limit class="input_disabled" value="<?=$rows['gift_limit'];?>"> �� <span class="font_orange">* �� ����ǰ�� ���ð����� �ִ� ����</span>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">����ǰ �ɼ�1</TD>
					<TD class="td_con1">
					�Ӽ��� : <INPUT size=15 name=option1_title class="input" value="<?=$OPT1?>"> &nbsp;&nbsp;
					�Ӽ� : <INPUT size=53 name=option1_value class="input" value="<?=$OPV1?>">
					<br />
					<span class="font_orange">�ɼ� ������ ������� �Ӽ�:���� ���·� �Է� �Ͻñ� �ٶ��ϴ�.(ǰ���ϰ�� 0) ex)ȭ��Ʈ:3</span>
					</TD>
				</tr>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">����ǰ �ɼ�2</TD>
					<TD class="td_con1">
					�Ӽ��� : <INPUT size=15 name=option2_title class="input" value="<?=$OPT2?>"> &nbsp;&nbsp;
					�Ӽ� : <INPUT size=53 name=option2_value class="input" value="<?=$OPV2?>">
					</TD>
				</tr>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">����ǰ �ɼ�3</TD>
					<TD class="td_con1">
					�Ӽ��� : <INPUT size=15 name=option3_title class="input" value="<?=$OPT3?>"> &nbsp;&nbsp;
					�Ӽ� : <INPUT size=53 name=option3_value class="input" value="<?=$OPV3?>">
					</TD>
				</tr>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">����ǰ �ɼ�4</TD>
					<TD class="td_con1">
					�Ӽ��� : <INPUT size=15 name=option4_title class="input" value="<?=$OPT4?>"> &nbsp;&nbsp;
					�Ӽ� : <INPUT size=53 name=option4_value class="input" value="<?=$OPV4?>">
					</TD>
				</tr>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">����ǰ �̹���</TD>
					<TD class="td_con1">
					<?  if(strlen(trim($rows['gift_image'])) > 0){ ?>
					<img src="<?=$imagepath.$rows['gift_image']?>" border="0" />
					<? } ?>
					<INPUT type=file size=71 name=gift_image class="input"><br>
					<span class="font_orange">* �̹����� �ִ������ 200 X 200, �ִ�뷮 150KB ������ GIF, JPG, PNG �� ����</span></TD>
				</tr>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=10></td></tr>
			<tr>
				<td align=center><a href="javascript:CheckForm2();"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a></td>
			</tr>
			</form>
			<tr><td height="30"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/product_giftlist_stitle3.gif" WIDTH="230" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=3></td></tr>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<col width=90></col>
				<col width=></col>
				<col width=75></col>
				<col width=75></col>
				<col width=55></col>
				<col width=55></col>
				<form name=form5 action="<?=$_SERVER[PHP_SELF]?>">
				<TR>
					<TD background="images/table_top_line.gif" colspan="6"></TD>
				</TR>
				<TR align=center>
					<TD class="table_cell">�������</TD>
					<TD class="table_cell1">����ǰ��</TD>
					<TD class="table_cell1">���Ž��۰�</TD>
					<TD class="table_cell1">�������ᰡ</TD>
					<TD class="table_cell1">����</TD>
					<TD class="table_cell1">����</TD>
				</TR>
				<TR>
					<TD colspan="6" background="images/table_con_line.gif"></TD>
				</TR>
<?
				$sql = "SELECT COUNT(*) as t_count FROM tblgiftinfo ";
				$result = mysql_query($sql,get_db_conn());
				$row = mysql_fetch_object($result);
				mysql_free_result($result);
				$t_count = $row->t_count;
				$pagecount = (($t_count - 1) / $setup[list_num]) + 1;

				$sql = "SELECT * FROM tblgiftinfo ";
				$sql.= "ORDER BY gift_regdate DESC LIMIT " . ($setup[list_num] * ($gotopage - 1)) . ", " . $setup[list_num];
				$result = mysql_query($sql,get_db_conn());
				$i=0;
				while($row=mysql_fetch_object($result)) {
					$number = ($t_count-($setup[list_num] * ($gotopage-1))-$i);
					echo "<TR>\n";
					echo "	<TD align=center class=\"td_con2\">".substr($row->gift_regdate,0,4)."/".substr($row->gift_regdate,4,2)."/".substr($row->gift_regdate,6,2)."</TD>\n";
					echo "	<TD class=\"td_con1\" style=\"word-break:break-all;\">";
					if (strlen($row->gift_image)>0 && file_exists($imagepath.$row->gift_image)) {
						echo "  <span onMouseOver='overTip(bigimg".$i.")' onMouseOut='outTip(bigimg".$i.")'>$row->gift_name</span>\n";
						echo "	<div id=\"bigimg".$i."\" style=\"position:absolute; z-index:100; visibility:hidden;\">";
						echo "	<img name=bigimgs src=\"".$imagepath.$row->gift_image."\" width=100 height=100></div>\n";
					} else {
						echo $row->gift_name;
					}
					echo "	<TD align=center class=\"td_con1\"><b><span class=\"font_orange\">".number_format($row->gift_startprice)."��</span></b></TD>\n";
					echo "	<TD align=center class=\"td_con1\"><b><span class=\"font_blue\">".number_format($row->gift_endprice)."��</span></b></TD>\n";
					//echo "	<TD align=center class=\"td_con1\"><input type=text name=gift_quantity".$i." value=\"".$row->gift_quantity."\" size=5 maxlength=5 class=\"input\"> <A HREF=\"javascript:amount_up('".$row->gift_regdate."','".$i."');\"><img src='images/icon_edit2.gif' border=0 align=absmiddle></A></TD>\n";
					echo "	<TD align=center class=\"td_con1\"><A HREF=\"javascript:modgift('".$row->gift_regdate."');\"><img src='images/icon_edit2.gif' border=0 align=absmiddle></A></TD>\n";
					echo "	<TD align=center class=\"td_con1\"><A HREF=\"javascript:delgift('".$row->gift_regdate."')\"><img src=\"images/btn_del.gif\" width=\"50\" height=\"22\" border=\"0\"></A></TD>\n";
					echo "</TR>\n";
					echo "<TR>\n";
					echo "	<TD colspan=\"6\" background=\"images/table_con_line.gif\"></TD>\n";
					echo "</TR>\n";
					$i++;
				}
				mysql_free_result($result);

				if ($i==0) {
					echo "<tr><td class=td_con2 colspan=6 align=center>��ϵ� ����ǰ ������ �����ϴ�.</td></tr>\n";
				}
?>
				<TR>
					<TD background="images/table_top_line.gif" colspan="6"></TD>
				</TR>
				</form>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td></td>
			</tr>
			<tr><td height=10></td></tr>
<?
			$total_block = intval($pagecount / $setup[page_num]);

			if (($pagecount % $setup[page_num]) > 0) {
				$total_block = $total_block + 1;
			}

			$total_block = $total_block - 1;

			if (ceil($t_count/$setup[list_num]) > 0) {
				// ����	x�� ����ϴ� �κ�-����
				$a_first_block = "";
				if ($nowblock > 0) {
					$a_first_block .= "<a href='".$_SERVER[PHP_SELF]."?scheck=".$scheck."&search=".$search."&block=0&gotopage=1' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='ù ������';return true\"><IMG src=\"images/icon_first.gif\" border=0  align=\"absmiddle\" width=\"17\" height=\"14\"></a> ";

					$prev_page_exists = true;
				}

				$a_prev_page = "";
				if ($nowblock > 0) {
					$a_prev_page .= "<a href='".$_SERVER[PHP_SELF]."?scheck=".$scheck."&search=".$search."&block=".($nowblock-1)."&gotopage=".($setup[page_num]*($block-1)+$setup[page_num])."' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup[page_num]." ������';return true\">[���� ".$setup[page_num]."��]</a> ";

					$a_prev_page = $a_first_block.$a_prev_page;
				}

				// �Ϲ� �������� ������ ǥ�úκ�-����

				if (intval($total_block) <> intval($nowblock)) {
					$print_page = "";
					for ($gopage = 1; $gopage <= $setup[page_num]; $gopage++) {
						if ((intval($nowblock*$setup[page_num]) + $gopage) == intval($gotopage)) {
							$print_page .= "<B><span class=font_orange2>[".(intval($nowblock*$setup[page_num]) + $gopage)."]</span></B> ";
						} else {
							$print_page .= "<a href='".$_SERVER[PHP_SELF]."?scheck=".$scheck."&search=".$search."&block=".$nowblock."&gotopage=". (intval($nowblock*$setup[page_num]) + $gopage)."' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='������ : ".(intval($nowblock*$setup[page_num]) + $gopage)."';return true\">[".(intval($nowblock*$setup[page_num]) + $gopage)."]</a> ";
						}
					}
				} else {
					if (($pagecount % $setup[page_num]) == 0) {
						$lastpage = $setup[page_num];
					} else {
						$lastpage = $pagecount % $setup[page_num];
					}

					for ($gopage = 1; $gopage <= $lastpage; $gopage++) {
						if (intval($nowblock*$setup[page_num]) + $gopage == intval($gotopage)) {
							$print_page .= "<B><span class=font_orange2>[".(intval($nowblock*$setup[page_num]) + $gopage)."]</span></B> ";
						} else {
							$print_page .= "<a href='".$_SERVER[PHP_SELF]."?scheck=".$scheck."&search=".$search."&block=".$nowblock."&gotopage=".(intval($nowblock*$setup[page_num]) + $gopage)."' onMouseOver=\"window.status='������ : ".(intval($nowblock*$setup[page_num]) + $gopage)."';return true\">[".(intval($nowblock*$setup[page_num]) + $gopage)."]</a> ";
						}
					}
				}		// ������ �������� ǥ�úκ�-��


				$a_last_block = "";
				if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
					$last_block = ceil($t_count/($setup[list_num]*$setup[page_num])) - 1;
					$last_gotopage = ceil($t_count/$setup[list_num]);

					$a_last_block .= " <a href='".$_SERVER[PHP_SELF]."?scheck=".$scheck."&search=".$search."&block=".$last_block."&gotopage=".$last_gotopage."' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='������ ������';return true\"><IMG src=\"images/icon_last.gif\" border=0  align=\"absmiddle\" width=\"17\" height=\"14\"></a>";

					$next_page_exists = true;
				}

				// ���� 10�� ó���κ�...

				$a_next_page = "";
				if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
					$a_next_page .= " <a href='".$_SERVER[PHP_SELF]."?scheck=".$scheck."&search=".$search."&block=".($nowblock+1)."&gotopage=".($setup[page_num]*($nowblock+1)+1)."' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup[page_num]." ������';return true\">[���� ".$setup[page_num]."��]</a>";

					$a_next_page = $a_next_page.$a_last_block;
				}
			} else {
				$print_page = "<B><span class=font_orange2>[1]</span></B>";
			}
			echo "<tr>\n";
			echo "	<td colspan=\"6\" align=center style='font-size:11px;'>\n";
			echo "		".$a_div_prev_page.$a_prev_page.$print_page.$a_next_page.$a_div_next_page;
			echo "	</td>\n";
			echo "</tr>\n";
?>
			<tr>
				<td>&nbsp;</td>
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
					<TD background="images/manual_left1.gif"></td>
					<TD COLSPAN=3 width="100%" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;" class="menual_bg">
					<table cellpadding="0" cellspacing="0" width="100%">
					<col width=20></col>
					<col width=></col>
					<tr>
						<td align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><span class="font_dotline">����ǰ ���� ����</span></td>
					</tr>

					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- ����ǰ �������� �ֹ��� ��ҵǵ� �������� �ʽ��ϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- ������ ����ǰ�� �������� �����Ƿ� ������ ó���Ͻñ� �ٶ��ϴ�.</td>
					</tr>
						<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- ���� ������ ����ǰ ������ �ֹ���ȸ���������� Ȯ�� �����մϴ�.</td>
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
			<tr><td height="50"></td></tr>
			<form name=form3 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=type>
			<input type=hidden name=sort value="<?=$sort?>">
			<input type=hidden name=block value="<?=$block?>">
			<input type=hidden name=gotopage value="<?=$gotopage?>">
			<input type=hidden name=gift_regdate>
			<input type=hidden name=gift_quantity>
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
<? if($between) { ?>
<script>document.form2.between.value="<?=$between?>";between_check();
<? if($ckd2=='checked') { ?>
	quantity_change('nonflag');
<? } ?>
<? if($ckd4=='checked') { ?>
	limit_change('nonflag');
<? } ?>
</script>
<? } ?>
<? INCLUDE "copyright.php"; ?>