<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

/*
if(strlen($_ShopInfo->getMemid())==0) {
	echo "<html><head><title></title></head><body onload=\"alert('ȸ�� ���̵� �������� �ʽ��ϴ�.');location.href='".$Dir.FrontDir."login.php';\"></body></html>";exit;
	exit;
}
*/

$ordercode=$_POST["ordercode"];
$type=$_POST["type"];

$memid = (strlen($_ShopInfo->getMemid())==0)? $ordercode:$_ShopInfo->getMemid();

$up_companyname=$_POST["up_companyname"];
$up_companynum=$_POST["up_companynum"];
$up_companyowner=$_POST["up_companyowner"];
$up_companypost1=$_POST["up_companypost1"];
$up_companypost2=$_POST["up_companypost2"];
$up_companyaddr1=$_POST["up_companyaddr1"];
$up_companyaddr2=$_POST["up_companyaddr2"];
$up_companybiz=$_POST["up_companybiz"];
$up_companyitem=$_POST["up_companyitem"];
$up_c_name=$_POST["up_c_name"];
$up_c_email=$_POST["up_c_email"];
$up_c_cell=$_POST["up_c_cell"];

if ($type == "up" || $type == "mod") {
	if($type == "up"){
		$sql = "INSERT tblmemcompany SET ";
		$sql.= "memid		= '".$memid."', ";
	}else if($type == "mod"){
		$sql = "UPDATE tblmemcompany SET ";
	}
	$sql.= "companyname		= '".$up_companyname."', ";
	$sql.= "companynum		= '".$up_companynum."', ";
	$sql.= "companytnum		= '".$up_companytnum."', ";
	$sql.= "companypost		= '".$up_companypost1.$up_companypost2."', ";
	$sql.= "companyaddr		= '".$up_companyaddr1."||".$up_companyaddr2."', ";
	$sql.= "companybiz		= '".$up_companybiz."', ";
	$sql.= "companyitem		= '".$up_companyitem."', ";
	$sql.= "companyowner	= '".$up_companyowner."', ";
	$sql.= "c_name			= '".$up_c_name."', ";
	$sql.= "c_email			= '".$up_c_email."', ";
	$sql.= "c_cell			= '".$up_c_cell."', ";
	$sql.= "regidate		= ".time()." ";
	if($type == "mod"){
		$sql.= "WHERE memid = '".$memid."' ";
	}
	mysql_query($sql,get_db_conn());
}

$sql = "SELECT * FROM tblmemcompany WHERE memid ='".$memid."' ";
$result = mysql_query($sql,get_db_conn());
if ($row=mysql_fetch_object($result)) {
	$companyname = $row->companyname;
	$companynum = $row->companynum;
	$companyowner = $row->companyowner;
	$companypost = $row->companypost;
	$companyaddr = explode("||",$row->companyaddr);
	$companyaddr1 = $companyaddr[0];
	$companyaddr2 = $companyaddr[1];
	$companybiz = $row->companybiz;
	$companyitem = $row->companyitem;
	$c_name = $row->c_name;
	$c_email = $row->c_email;
	$c_cell = $row->c_cell;
	$type="mod";
}
mysql_free_result($result);

?>
<html>
<head>
<title>���ݰ�꼭 �����Է�</title>
<meta http-equiv="CONTENT-TYPE" content="text/html;charset=EUC-KR">
<script type="text/javascript" src="../lib/lib.js.php"></script>
<style>
td {font-family:����;color:333333;font-size:9pt;}
tr {font-family:����;color:333333;font-size:9pt;}
BODY,TD,SELECT,DIV,form,TEXTAREA,center,option,pre,blockquote {font-family:����;color:333333;font-size:9pt;}

A:link    {color:333333;text-decoration:none;}
A:visited {color:333333;text-decoration:none;}
A:active  {color:333333;text-decoration:none;}
A:hover  {color:#CC0000;text-decoration:none;}

.input {border:1px solid #d9d9d9; background:#f5f5f5; font-size:12px; font-family:����; height:22px; line-height:20px; padding-left:5px;}
</style>
<script language="JavaScript">

function f_addr_search(form,post,addr,gbn) {
	window.open("<?=$Dir.FrontDir?>addr_search.php?form="+form+"&post="+post+"&addr="+addr+"&gbn="+gbn,"f_post","resizable=yes,scrollbars=yes,x=100,y=200,width=370,height=250");
}

function CheckForm() {
	var form = document.form1;
	if (!form.up_companyname.value) {
		form.up_companyname.focus();
		alert("��ȣ(ȸ���)�� �Է��ϼ���.");
		return;
	}
	if(CheckLength(form.up_companyname)>30) {
		form.company_name.focus();
		alert("��ȣ(ȸ���)�� �ѱ�15�� ����30�� ���� �Է� �����մϴ�");
		return;
	}
	if (!form.up_companynum.value) {
		form.up_companynum.focus();
		alert("����ڵ�Ϲ�ȣ�� �Է��ϼ���.");
		return;
	}

	var bizno;
	var bb;
	bizno = form.up_companynum.value;
	bizno = bizno.replace("-","");
	bb = chkBizNo(bizno);
	if (!bb) {
		alert("�������� ���� ����ڵ�Ϲ�ȣ �Դϴ�.\n����ڵ�Ϲ�ȣ�� �ٽ� �Է��ϼ���.");
		form.up_companynum.value = "";
		form.up_companynum.focus();
		return;
	}

	if (!form.up_companyowner.value) {
		form.up_companyowner.focus();
		alert("��ǥ�� ������ �Է��ϼ���.");
		return;
	}
	if(CheckLength(form.up_companyowner)>12) {
		form.up_companyowner.focus();
		alert("��ǥ�� ������ �ѱ� 6���ڱ��� �����մϴ�");
		return;
	}
	if (!form.up_companypost1.value || !form.up_companypost2.value) {
		form.up_companypost1.focus();
		alert("�����ȣ�� �Է��ϼ���.");
		return;
	}
	if (!form.up_companyaddr1.value) {
		form.up_companyaddr1.focus();
		alert("����� �ּҸ� �Է��ϼ���.");
		return;
	}
	if (!form.up_companyaddr2.value) {
		form.up_companyaddr2.focus();
		alert("����� ������ �ּҸ� �Է��ϼ���.");
		return;
	}
	if(CheckLength(form.up_companybiz)>30) {
		form.up_companybiz.focus();
		alert("����� ���´� �ѱ� 15�ڱ��� �Է� �����մϴ�");
		return;
	}
	if(CheckLength(form.up_companyitem)>30) {
		form.up_companyitem.focus();
		alert("����� ������ �ѱ� 15�ڱ��� �Է� �����մϴ�");
		return;
	}
	if (!form.up_c_name.value) {
		form.up_c_name.focus();
		alert("����� �̸��� �Է��ϼ���.");
		return;
	}
	if (!form.up_c_email.value) {
		form.up_c_email.focus();
		alert("����� �̸����� �Է��ϼ���.");
		return;
	}
	if (!IsMailCheck(form.up_c_email.value)) {
		form.up_c_email.focus();
		alert("�̸��� ������ �ƴմϴ�.");
		return;
	}
	if (!form.up_c_cell.value) {
		form.up_c_cell.focus();
		alert("����� ����ó�� �Է��ϼ���.");
		return;
	}

	form.type.value="<?=($type)? 'mod':'up'?>";
	form.submit();
}
function billRequest(ordercode){
	var form = document.frmbill;
	form.submit();
}
</script>
</head>

<body topmargin=0 leftmargin=0 rightmargin=0 marginheight=0 marginwidth=0>
<center>
<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
<input type=hidden name=type>
<input type=hidden name="ordercode" value="<?=$ordercode?>">
<input type=hidden name="memid" value="<?=$memid?>">
<table border=0 cellpadding=0 cellspacing=0 width=100%>
<tr>
	<td align=center style="padding:10,10,10,10">
	<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
	<tr><td align=center height=30 bgcolor=#454545><FONT COLOR="#FFFFFF"><B>���޹޴��� �����Է�</B></FONT></td></tr>
	<tr><td height=10></td></tr>
	<tr>
		<td align=center>
		<table border=0 cellpadding=5 cellspacing=1 width=100% bgcolor=#E7E7E7 style="table-layout:fixed">
		<col width=110></col>
		<col width=></col>
				<tr>
			<td align=center bgcolor=#F5F5F5 style="padding:7,10">����ڵ�Ϲ�ȣ</td>
			<td bgcolor=#ffffff style="padding:7,10"><input type="text" name="up_companynum" style="WIDTH:150px;" class="input" value="<?=$companynum?>"> &nbsp;<span style="color:#aaaaaa; font-size:11px;">("-" �����ϰ� �Է��� �ּ���.)</span></td>
		</tr>
		<tr>
			<td align=center bgcolor=#F5F5F5 style="padding:7,10">��ȣ(���θ�)</td>
			<td bgcolor=#ffffff style="padding:7,10"><input type="text" name="up_companyname" style="WIDTH:150px;" class="input" value="<?=$companyname?>"></td>
		</tr>
		<tr>
			<td align=center bgcolor=#F5F5F5 style="padding:7,10">����(��ǥ��)</td>
			<td bgcolor=#ffffff style="padding:7,10"><input type="text" name="up_companyowner" style="WIDTH:150px;" class="input" value="<?=$companyowner?>"></td>
		</tr>
		<tr>
			<td align=center bgcolor=#F5F5F5 style="padding:7,10">�ּ�</td>
			<td bgcolor=#ffffff style="padding:7,10">
				<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td><INPUT type=text name="up_companypost1" value="<?=substr($companypost,0,3)?>" readOnly style="WIDTH:40px;" class="input" /> - <INPUT type=text name="up_companypost2" value="<?=substr($companypost,3,3)?>" readOnly style="WIDTH:40px;" class="input" /><a href="javascript:f_addr_search('form1','up_companypost','up_companyaddr1',2);"><img src="<?=$Dir?>images/common/mbjoin/001/memberjoin_skin1_btn2.gif" border="0" align="absmiddle" hspace="3"></a></td>
					</tr>
					<tr>
						<td><INPUT type=text name="up_companyaddr1" value="<?=$companyaddr1?>" maxLength="100" readOnly class="input" style="WIDTH:80%;" /></td>
					</tr>
					<tr>
						<td><INPUT type=text name="up_companyaddr2" value="<?=$companyaddr2?>" maxLength="100" class="input" style="WIDTH:80%;" /></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td align=center bgcolor=#F5F5F5 style="padding:7,10">����</td>
			<td bgcolor=#ffffff style="padding:7,10"><input type="text" name="up_companybiz" class="input" style="WIDTH:150px;" value="<?=$companybiz?>" /></td>
		</tr>
		<tr>
			<td align=center bgcolor=#F5F5F5 style="padding:7,10">����</td>
			<td bgcolor=#ffffff style="padding:7,10"><input type="text" name="up_companyitem" class="input" style="WIDTH:150px;" value="<?=$companyitem?>" /></td>
		</tr>
		<tr>
			<td align=center bgcolor=#F5F5F5 style="padding:7,10">����� �̸�</td>
			<td bgcolor=#ffffff style="padding:7,10"><input type="text" name="up_c_name" class="input" style="WIDTH:150px;" value="<?=($c_name=="")? $_ShopInfo->getMemname():$c_name ?>" /></td>
		</tr>
		<tr>
			<td align=center bgcolor=#F5F5F5 style="padding:7,10">����� �̸���</td>
			<td bgcolor=#ffffff style="padding:7,10"><input type="text" name="up_c_email" class="input" style="WIDTH:80%;" value="<?=($c_email=="")? $_ShopInfo->getMememail():$c_email?>" /></td>
		</tr>
		<tr>
			<td align=center bgcolor=#F5F5F5 style="padding:7,10">����� ����ó</td>
			<td bgcolor=#ffffff style="padding:7,10"><input type="text" name="up_c_cell" class="input" style="WIDTH:150px;" value="<?=$c_cell?>" /></td>
		</tr>
		</table>
		</td>
	</tr>
	<tr><td height=10></td></tr>
	<tr>
		<td align=center>
			<img src="/images/common/<?=($type=="mod")? "orderdetailpop_edit.gif":"orderdetailpop_insert.gif"?>" onclick="CheckForm()" style="cursor:pointer;">
			<?if($type=="mod" && $ordercode != ""){?><img src="/images/common/mypage_reqbill.gif" alt="���ݰ�꼭��û" onclick="billRequest()" style="cursor:pointer;" /><?}?>
			<img src="../images/common/orderdetailpop_close.gif" border="0" onclick="javascript:window.close();" style="cursor:pointer;" /></A>
		</td>
	</tr>
	</table>
	</td>
</tr>
</table>
</form>
<form name=frmbill action="orderbillsend.php" method=post target="hiddenFrame">
<input type=hidden name="ordercode" value="<?=$ordercode?>">
<input type=hidden name="member" value="<?=(strlen($_ShopInfo->getMemid())==0)? "guest":$_ShopInfo->getMemid()?>">
<input type=hidden name="popup" value="popup">
</form>
<IFRAME id="hiddenFrame" name="hiddenFrame" style="width:0;height:0; position:absolute; visibility:hidden"></IFRAME>
</body>
</html>
