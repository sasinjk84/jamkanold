<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

if(strlen($_ShopInfo->getId())==0){
	echo "<script>alert('�������� ��η� �����Ͻñ� �ٶ��ϴ�.');window.close();</script>";
	exit;
}

$vender=$_POST["vender"];
$date=$_POST["date"];

if($vender==NULL || strlen($date)!=8) {
	echo "<script>alert('�߸��� �����Դϴ�.');window.close();</script>";
	exit;
}

if($date>date("Ymd")) {
	echo "<html></head><body onload=\"alert('��¥�� �߸��Ǿ����ϴ�.');window.close();\"></body></html>";exit;
}

$tmpdate=substr($date,0,4)."/".substr($date,4,2)."/".substr($date,6,2);

$sql = "SELECT * FROM tblvenderinfo WHERE vender='".$vender."' AND delflag='N' ";
$result=mysql_query($sql,get_db_conn());
if(!$vdata=mysql_fetch_object($result)) {
	echo "<html><head></head><body onload=\"alert('�ش� ������ü�� �������� �ʽ��ϴ�.');window.close();\"></body></html>";exit;
}
mysql_free_result($result);

$str_account="";
if(strlen($vdata->bank_account)>0) {
	$tmpaccount=explode("=",$vdata->bank_account);
	$str_account=$tmpaccount[0]." ".$tmpaccount[1]." (������ : ".$tmpaccount[2].")";
}

$sql = "SELECT * FROM tblvenderaccount WHERE vender='".$vender."' AND date='".$date."' ";
$result=mysql_query($sql,get_db_conn());
if($adata=mysql_fetch_object($result)) {
	$type="update";
} else {
	$type="insert";
}
mysql_free_result($result);

$mode=$_POST["mode"];
if($mode=="insert" && $type=="insert") {
	$price=$_POST["price"];
	$bank_account=$_POST["bank_account"];
	$memo=$_POST["memo"];

	$sql = "INSERT tblvenderaccount SET ";
	$sql.= "vender		= '".$vender."', ";
	$sql.= "date		= '".$date."', ";
	$sql.= "price		= '".$price."', ";
	$sql.= "bank_account= '".$bank_account."', ";
	$sql.= "memo		= '".$memo."' ";
	mysql_query($sql,get_db_conn());
	echo "<html><head></head><body onload=\"alert('".$tmpdate." ���곻���� ��ϵǾ����ϴ�.');opener.formSubmit();window.close();\"></body></html>";exit;
} else if($mode=="update" && $type=="update") {
	$price=$_POST["price"];
	$bank_account=$_POST["bank_account"];
	$memo=$_POST["memo"];
	$sql = "UPDATE tblvenderaccount SET ";
	if($adata->confirm=="N") {
		$sql.= "price		= '".$price."', ";
		$sql.= "bank_account= '".$bank_account."', ";
	}
	$sql.= "memo		= '".$memo."' ";
	$sql.= "WHERE vender='".$vender."' AND date='".$date."' ";
	mysql_query($sql,get_db_conn());
	echo "<html><head></head><body onload=\"alert('".$tmpdate." ���곻�� ������ �Ϸ�Ǿ����ϴ�.');opener.formSubmit();window.close();\"></body></html>";exit;
} else if($mode=="delete" && $type=="update") {
	$sql = "DELETE FROM tblvenderaccount ";
	$sql.= "WHERE vender='".$vender."' AND date='".$date."' ";
	mysql_query($sql,get_db_conn());
	echo "<html><head></head><body onload=\"alert('".$tmpdate." ���곻���� �����Ǿ����ϴ�.');opener.formSubmit();window.close();\"></body></html>";exit;
}

?>

<html>
<head>
<title>������ ������</title>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-KR">
<script type="text/javascript" src="lib.js.php"></script>
<link rel="stylesheet" href="style.css">
<script language=Javascript>
function PageResize() {
	var oWidth = document.all.table_body.clientWidth + 10;
	var oHeight = document.all.table_body.clientHeight + 65;

	window.resizeTo(oWidth,oHeight);
}
<?if($type=="update"){?>
function formUpdate() {
	if(typeof(document.form1.price)=="object") {
		if(document.form1.price.value.length==0) {
			alert("����ݾ��� �Է��ϼ���.");
			document.form1.price.focus();
			return;
		}
	}
	if(typeof(document.form1.bank_account)=="object") {
		if(document.form1.bank_account.value.length==0) {
			alert("�Ա� ���¸� �Է��ϼ���.");
			document.form1.bank_account.focus();
			return;
		}
	}
	if(confirm("���곻�� ������ �����Ͻðڽ��ϱ�?")) {
		document.form1.mode.value="update";
		document.form1.submit();
	}
}

function formDelete() {
	if(confirm("���� �����Ͻðڽ��ϱ�?")) {
		document.form1.mode.value="delete";
		document.form1.submit();
	}
}
<?}else{?>
function formWrite() {
	if(document.form1.price.value.length==0) {
		alert("����ݾ��� �Է��ϼ���.");
		document.form1.price.focus();
		return;
	}
	if(document.form1.bank_account.value.length==0) {
		alert("�Ա� ���¸� �Է��ϼ���.");
		document.form1.bank_account.focus();
		return;
	}
	if(confirm("���� ó���� �Ͻðڽ��ϱ�?")) {
		document.form1.mode.value="insert";
		document.form1.submit();
	}
}
<?}?>
</script>
</head>
<body marginwidth=0 marginheight=0 leftmargin=0 topmargin=0 style="overflow-x:hidden;overflow-y:hidden;" onLoad="PageResize();">
<center>
<table border=0 cellpadding=0 cellspacing=0 width=400 style="table-layout:fixed;" id=table_body>
<tr>
	<td width=100%>
	<table border=0 cellpadding=3 cellspacing=0 width=100% style="table-layout:fixed;">
	<tr>
		<td bgcolor="#F9799A" style="padding-left:15"><FONT COLOR="#ffffff"><B><?=$tmpdate?>
		<?
		if($type=="update") echo " ���곻��";
		if($type=="insert") echo " ����ó��";
		?>
		</B></FONT></td>
	</tr>
	</table>

	<table border=0 cellpadding=0 cellspacing=0 width=100%>
	<tr><td height=10></td></tr>
	<form name=form1 method=post action="<?=$_SERVER[PHP_SELF]?>">
	<input type=hidden name=mode>
	<input type=hidden name=date value="<?=$date?>">
	<input type=hidden name=vender value="<?=$vender?>">
	<tr>
		<td align=center>
		<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
		<col width=100></col>
		<col width=></col>
		<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
		<tr>
			<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:2>
			&nbsp;������ü
			</td>
			<td style=padding:7,10>
			<B><?=$vdata->id?></B> - <?=$vdata->com_name?>
			</td>
		</tr>
		<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
		<tr>
			<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:2>
			&nbsp;��������
			</td>
			<td style=padding:7,10>
			<B><?=$tmpdate?></B>
			</td>
		</tr>
		<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
		<tr>
			<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:2>
			&nbsp;����ݾ�
			</td>
			<td style=padding:7,10>
			<?if($type=="update"){?>
				<?if($adata->confirm=="Y"){?>
				<B><?=number_format($adata->price)?>��</B>
				<?}else{?>
				<input type=text name=price value="<?=$adata->price?>" size=10 maxlength=10 onkeyup="strnumkeyup(this)">��
				<?}?>
			<?}else{?>
				<input type=text name=price size=10 maxlength=10 onkeyup="strnumkeyup(this)">��
			<?}?>
			</td>
		</tr>
		<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
		<?if($type=="update"){?>
		<tr>
			<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:2>
			&nbsp;�Ա�Ȯ��
			</td>
			<td style=padding:7,10>
			<B>
			<?=($adata->confirm=="Y"?"������ü �Ա� Ȯ��":"<font color=red>������ü �Ա� ��Ȯ��</font>")?>
			</B>
			</td>
		</tr>
		<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
		<?}?>
		<tr>
			<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:2>
			&nbsp;�Աݰ���
			</td>
			<td style=padding:7,10>
			<?if($type=="update"){?>
				<?if($adata->confirm=="Y"){?>
				<B><?=$adata->bank_account?></B>
				<?}else{?>
				<input type=text name=bank_account value="<?=$adata->bank_account?>" size=45 maxlength=100>
				<?}?>
			<?}else{?>
				<input type=text name=bank_account value="<?=$str_account?>" size=45 maxlength=100>
			<?}?>
			</td>
		</tr>
		<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
		<tr>
			<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:2>
			&nbsp;����޸�
			</td>
			<td style=padding:7,10>
			<textarea name=memo style="width:100%;height:80"><?=$adata->memo?></textarea>
			</td>
		</tr>
		<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
		<tr><td height=10></td></tr>
		<tr>
			<td colspan=2 align=center>
			<A HREF="javascript:window.close()"><img src=images/btn_close03.gif border=0></A>
			<?if($type=="update"){?>
			&nbsp;<A HREF="javascript:formUpdate()"><img src=images/btn_modify03.gif border=0></A>
			&nbsp;<A HREF="javascript:formDelete()"><img src=images/btn_delete.gif border=0></A>
			<?} else {?>
			&nbsp;<A HREF="javascript:formWrite()"><img src=images/btn_confirm03.gif border=0></A>
			<?}?>
			</td>
		</tr>
		</table>
		</td>
	</tr>
	</form>
	<tr><td height=10></td></tr>
	</table>
	</td>
</tr>

</table>
</center>
</body>
</html>