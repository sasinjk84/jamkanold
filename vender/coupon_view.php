<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");

$isaccesspass=true;
INCLUDE ("access.php");


$coupon_code=$_POST["coupon_code"];
$sql = "SELECT * FROM tblcouponinfo WHERE coupon_code = '".$coupon_code."' AND vender='".$_VenderInfo->getVidx()."' ";
$result = mysql_query($sql,get_db_conn());
if(!$row=mysql_fetch_object($result)) {
	echo "<script>alert('�ش� ���� ������ �������� �ʽ��ϴ�.');window.close();</script>";
	exit;
}
mysql_free_result($result);

$arissuetype =array("D"=>"���� ����","M"=>"ȸ�� ���Խ� �߱�","N"=>"���� �߱޿� ����","Y"=>"���� Ŭ���� �߱�");
if($row->date_start>0) {
	$date = substr($row->date_start,0,4).".".substr($row->date_start,4,2).".".substr($row->date_start,6,2)."[".substr($row->date_start,8,2).":00] ~ ".substr($row->date_end,0,4).".".substr($row->date_end,4,2).".".substr($row->date_end,6,2)."[".substr($row->date_end,8,2).":00]";
	$date2 = substr($row->date_start,4,2)."/".substr($row->date_start,6,2)." ~ ".substr($row->date_end,4,2)."/".substr($row->date_end,6,2);
} else {
	$date = abs($row->date_start)."�ϵ���";
	$date2 = date("m/d")." ~ ".date("m/d",mktime(0,0,0,date("m"),date("d")+abs($row->date_start),date("Y")));
}
if($row->sale_type<=2) {
	$dan="%";
} else {
	$dan="��";
}
if($row->sale_type%2==0) {
	$sale = "����";
} else {
	$sale = "����";
}
$prleng=strlen($row->productcode);
if($row->productcode=="ALL") {
	$product="��ü��ǰ";
} else {
	if($prleng==12) {
		$sql2 = "SELECT code_name as product FROM tblproductcode WHERE codeA='".substr($row->productcode,0,3)."' ";
		if(substr($row->productcode,3,3)!="000") {
			$sql2.= "AND (codeB='".substr($row->productcode,3,3)."' OR codeB='000') ";
			if(substr($row->productcode,6,3)!="000") {
				$sql2.= "AND (codeC='".substr($row->productcode,6,3)."' OR codeC='000') ";
				if(substr($row->productcode,9,3)!="000") {
					$sql2.= "AND (codeD='".substr($row->productcode,9,3)."' OR codeD='000') ";
				} else {
					$sql2.= "AND codeD='000' ";
				}
			} else {
				$sql2.= "AND codeC='000' ";
			}
		} else {
			$sql2.= "AND codeB='000' AND codeC='000' ";
		}
		$sql2.= "AND (type='L' or type='LX' or type='LM' or type='LMX') ";
		$sql2.= "ORDER BY codeA,codeB,codeC,codeD ASC ";
		$result2 = mysql_query($sql2,get_db_conn());
		$i=0;
		while($row2=mysql_fetch_object($result2)) {
			if($i>0) $product.= " > ";
			$product.= $row2->product;
			$i++;
		}
		mysql_free_result($result2);
	}
	if($prleng==18) {
		$sql2 = "SELECT productname as product FROM tblproduct WHERE productcode='".$row->productcode."' ";
		$result2 = mysql_query($sql2,get_db_conn());
		if($row2 = mysql_fetch_object($result2)) {
			$product.= " > ".$row2->product;
		}
		mysql_free_result($result2);
	}
	if($row->use_con_type2=="N") $product="[".$product."] ����";
}
if($row->member=="ALL") {
	$membermsg = "[��üȸ��]";
} else if($row->member!="") {
	$sql2 = "SELECT group_name FROM tblmembergroup WHERE group_code='".$row->member."' ";
	$result2 = mysql_query($sql2,get_db_conn());
	if($row2 = mysql_fetch_object($result2)) $membermsg = "[�׷�ȸ�� : ".$row2->group_name."]";
	else $membermsg = "[����ȸ��]";
	mysql_free_result($result2);
} else {
	$membermsg = "[����ȸ��]";
}
?>

<html>
<head>
<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">
<META http-equiv="X-UA-Compatible" content="IE=5" />
<title>�������� ����</title>
<link rel="stylesheet" href="style.css" type="text/css">
<SCRIPT LANGUAGE="JavaScript">
<!--
function PageResize() {
	var oWidth = document.all.table_body.clientWidth + 10;
	var oHeight = document.all.table_body.clientHeight + 55;

	window.resizeTo(oWidth,oHeight);
}
//-->
</SCRIPT>
</head>
<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 oncontextmenu="return false" style="overflow-x:hidden;overflow-y:hidden;" ondragstart="return false" onselectstart="return false" oncontextmenu="return false" onLoad="PageResize();">

<table border=0 cellpadding=0 cellspacing=0 width=650 style="table-layout:fixed;" id=table_body>
<tr>
	<td width=100% align=center>
	<table border=0 cellpadding=3 cellspacing=0 width=100% style="table-layout:fixed;">
	<tr>
		<td height=30 bgcolor="#F9799A" style="padding-left:15"><FONT COLOR="#ffffff"><B>���� ������</B></FONT></td>
	</tr>
	</table>

	<table width="95%" border="0" cellpadding="0" cellspacing="0" style="table-layout:fixed">
	<col width=150></col>
	<col width=></col>
	<tr><td colspan=2 height=20></td></tr>
	<tr>
		<td colspan=2><img src="images/icon_dot03.gif" border=0 align=absmiddle> <B>���� �⺻����</B></td>
	</tr>
	<tr><td colspan=2 height=1 bgcolor=red></td></tr>
	<tr>
		<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:6> �����ڵ�</td>
		<td style=padding:7,10><B><?=$row->coupon_code?></B></td>
	</tr>
	<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
	<tr>
		<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:6> �����̸�</td>
		<td style=padding:7,10><B><?=$row->coupon_name?></B></td>
	</tr>
	<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
	<tr>
		<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:6> ��ȿ�Ⱓ</td>
		<td style=padding:7,10><?=$date?></td>
	</tr>
	<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
	<tr>
		<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:6> ���� �Ӽ�</td>
		<td style=padding:7,10><?=number_format($row->sale_money).$dan.$sale?> ����</td>
	</tr>
	<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
	<tr>
		<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:6> ���� ��밡�� �����ݾ�</td>
		<td style=padding:7,10><?=$row->mini_price=="0"?"���� ����":number_format($row->mini_price)."�� �̻� ���Žÿ��� ��밡��"?></td>
	</tr>
	<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
	<tr><td colspan=2 height=20></td></tr>
	<tr>
		<td colspan=2><img src="images/icon_dot03.gif" border=0 align=absmiddle> <B>���� �ΰ�����</B></td>
	</tr>
	<tr><td colspan=2 height=1 bgcolor=red></td></tr>
	<tr>
		<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:6> ���� ��ǰ��</td>
		<td style=padding:7,10><?=$product?></td>
	</tr>
	<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
	<tr>
		<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:6> ���� �߱�����</td>
		<td style=padding:7,10><?=$arissuetype[$row->issue_type]." ".$membermsg?></td>
	</tr>
	<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
	<tr>
		<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:6> ���� ���� ���� ��</td>
		<td style=padding:7,10><?=$row->issue_tot_no=="0"?"������":number_format($row->issue_tot_no)."��"?></td>
	</tr>
	<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
	<tr>
		<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:6> ���� �̹���</td>
		<td style="padding:7,10;padding-bottom:3">
<?
		if(file_exists($Dir.DataDir."shopimages/etc/COUPON".$row->coupon_code.".gif")) {
			echo "<img src=\"".$Dir.DataDir."shopimages/etc/COUPON".$row->coupon_code.".gif\" align=absmiddle border=0>";
		} else {
?>
		<table border="0" cellpadding="0" cellspacing="0" width="352" style="table-layout:fixed;">
		<col width="5"></col>
		<col width=></col>
		<col width="5"></col>
		<tr>
			<td colspan="3"><IMG SRC="<?=$Dir?>images/common/coupon_table01.gif" border="0"></td>
		</tr>
		<tr>
			<td background="<?=$Dir?>images/common/coupon_table02.gif"><IMG SRC="<?=$Dir?>images/common/coupon_table02.gif" border="0"></td>
			<td width="100%" style="padding:3pt;" background="<?=$Dir?>images/common/coupon_bg.gif">
			<table align="center" cellpadding="0" cellspacing="0">
			<tr>
				<td style="padding-bottom:4pt;"><IMG SRC="<?=$Dir?>images/common/coupon_title<?=$row->sale_type?>.gif" border="0"></td>
			</tr>
			<tr>
				<td>
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td><font color="#585858" style="font-size:11px;letter-spacing:-0.5pt;">��ȿ�Ⱓ : <?=$date2?></font><?=($row->bank_only=="Y"?"<font color=\"#0000FF\">(���ݰ����� ����)</font>":"")?></td>
				</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td>
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td width="100%" align="right"><font color="#FF5000" style="font-family:sans-serif;font-size:48px;line-height:45px"><b><font color="#FF6600" face="����ü"><?=number_format($row->sale_money)?></font></b></td>
					<td><IMG SRC="<?=$Dir?>images/common/coupon_text<?=$row->sale_type?>.gif" border="0"></td>
				</tr>
				</table>
				</td>
			</tr>
			</table>
			</td>
			<td background="<?=$Dir?>images/common/coupon_table04.gif"><IMG SRC="<?=$Dir?>images/common/coupon_table04.gif" border="0"></td>
		</tr>
		<tr>
			<td colspan="3"><IMG SRC="<?=$Dir?>images/common/coupon_table03.gif" border="0"></td>
		</tr>
		</table>
<?
		}
?>
		</td>
	</tr>
	<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
	<tr>
		<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:6> ����� ������</td>
		<td style=padding:7,10><b><?=number_format($row->issue_no)?></b></font>�� (�� ���� �߱� ���� ��)</td>
	</tr>
	<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
	<tr><td colspan=2 height=10></td></tr>
	<tr>
		<td colspan=2 align=center>
		<A HREF="javascript:window.close()"><img src=images/btn_close03.gif border=0></A>
		</td>
	</tr>
	<tr><td colspan=2 height=20></td></tr>
	</table>

	<table border=0 cellpadding=2 cellspacing=0 width=100% style="table-layout:fixed;">
	<tr>
		<td bgcolor="#F9799A" height=5 align=center></td>
	</tr>
	</table>
	</td>
</tr>
</table>
</body>
</html>