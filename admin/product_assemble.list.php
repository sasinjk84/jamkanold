<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "pr-1";
$MenuCode = "nomenu";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################
$Scrolltype=$_REQUEST["Scrolltype"];
$code=$_POST["code"];
$prcode=$_POST["prcode"];
$sort=$_REQUEST["sort"];

if(strlen($prcode)>0) {
	$sql = "SELECT assembleuse FROM tblproduct ";
	$sql.= "WHERE productcode = '".$prcode."' ";
	$result=mysql_query($sql,get_db_conn());
	$row=@mysql_fetch_object($result);
	if($row->assembleuse!="Y") {
		$onload = "<script type=\"text/javascript\">alert(\"�ش� ��ǰ�� �ڵ�/���� ��ǰ�� �ƴմϴ�. Ȯ�� �� ����� �ּ���.\");</script>";
		echo "
		<form name=form1 action=\"".$_SERVER[PHP_SELF]."\" method=post>\n
		<input type=hidden name=mode value=\"\">
		<input type=hidden name=code value=\"".$code."\">
		<input type=hidden name=sort value=\"".$sort."\">
		<input type=hidden name=Scrolltype value=\"".$Scrolltype."\">
		<input type=hidden name=onload value=\"".htmlspecialchars($onload)."\">
		</form>\n
		<script type=\"text/javascript\">
		<!--
			document.form1.submit();
		//-->
		</script>
		";
		exit;
	}
}

$onload=$_POST["onload"];
$mode=$_POST["mode"];
$aname=$_POST["aname"];

$assemble_title_list=$_POST["assemble_title_list"];
$assemble_type_list=$_POST["assemble_type_list"];
$assemble_product_list=$_POST["assemble_product_list"];
$assemble_basic_list=$_POST["assemble_basic_list"];

if ($mode=="update") {
	if(strlen($assemble_title_list)) {
		$sql = "SELECT assemble_pridx FROM tblassembleproduct ";
		$sql.= "WHERE productcode = '".$prcode."' ";
		$result=mysql_query($sql,get_db_conn());
		$row=@mysql_fetch_object($result);

		if($row) {
			mysql_free_result($result);
			if(strlen($row->assemble_pridx)>0) {
				$assemble_oribasic_list = substr($row->assemble_pridx,1);
			} else {
				$assemble_oribasic_list = "";
			}
			$sql = "UPDATE tblassembleproduct SET ";
			$sql.= "assemble_type = '".$assemble_type_list."', ";
			$sql.= "assemble_title = '".$assemble_title_list."', ";
			$sql.= "assemble_list = '".$assemble_product_list."', ";
			$sql.= "assemble_pridx = '".$assemble_basic_list."' ";
			$sql.= "WHERE productcode = '".$prcode."' ";
			mysql_query($sql,get_db_conn());

			$sql = "SELECT SUM(sellprice) AS sellpricesum FROM tblproduct ";
			$sql.= "WHERE pridx IN ('".str_replace("","','",$assemble_basic_list)."') ";
			$sql.= "AND display ='Y' ";
			$sql.= "AND assembleuse!='Y' ";
			$result=mysql_query($sql,get_db_conn());
			$row=mysql_fetch_object($result);

			$sql = "UPDATE tblproduct SET ";
			$sql.= "sellprice=".(int)$row->sellpricesum." ";
			$sql.= "WHERE productcode='".$prcode."' ";
			$sql.= "AND assembleuse='Y' ";
			mysql_query($sql,get_db_conn());

			if(strlen($assemble_oribasic_list)>0) {
				$sql = "UPDATE tblproduct SET ";
				$sql.= "assembleproduct=REPLACE(assembleproduct,',".$prcode."','') ";
				$sql.= "WHERE pridx IN ('".str_replace("","','",$assemble_oribasic_list)."') ";
				$sql.= "AND assembleuse!='Y' ";
				mysql_query($sql,get_db_conn());
			}

			$sql = "UPDATE tblproduct SET ";
			$sql.= "assembleproduct=CONCAT(assembleproduct,',".$prcode."') ";
			$sql.= "WHERE pridx IN ('".str_replace("","','",$assemble_basic_list)."') ";
			$sql.= "AND assembleuse!='Y' ";
			mysql_query($sql,get_db_conn());
		} else {
			$sql = "INSERT tblassembleproduct SET ";
			$sql.= "productcode = '".$prcode."', ";
			$sql.= "assemble_type = '".$assemble_type_list."', ";
			$sql.= "assemble_title = '".$assemble_title_list."', ";
			$sql.= "assemble_list = '".$assemble_product_list."', ";
			$sql.= "assemble_pridx = '".$assemble_basic_list."' ";
			mysql_query($sql,get_db_conn());

			if(strlen($assemble_basic_list)>0) {
				$sql = "SELECT SUM(sellprice) AS sellpricesum FROM tblproduct ";
				$sql.= "WHERE pridx IN ('".str_replace("","','",$assemble_basic_list)."') ";
				$sql.= "AND display ='Y' ";
				$sql.= "AND assembleuse!='Y' ";
				$result=mysql_query($sql,get_db_conn());
				$row=mysql_fetch_object($result);

				$sql = "UPDATE tblproduct SET ";
				$sql.= "sellprice=".(int)$row->sellpricesum." ";
				$sql.= "WHERE productcode='".$prcode."' ";
				$sql.= "AND assembleuse='Y' ";
				mysql_query($sql,get_db_conn());

				$sql = "UPDATE tblproduct SET ";
				$sql.= "assembleproduct=CONCAT(assembleproduct,',".$prcode."') ";
				$sql.= "WHERE pridx IN ('".str_replace("","','",$assemble_basic_list)."') ";
				$sql.= "AND assembleuse!='Y' ";
				mysql_query($sql,get_db_conn());
			}
		}
	} else {
		$sql = "SELECT assemble_pridx FROM tblassembleproduct ";
		$sql.= "WHERE productcode = '".$prcode."' ";
		$result=mysql_query($sql,get_db_conn());
		$row=@mysql_fetch_object($result);

		if($row) {
			mysql_free_result($result);
			if(strlen($row->assemble_pridx)>0) {
				$assemble_oribasic_list = substr($row->assemble_pridx,1);
			} else {
				$assemble_oribasic_list = "";
			}
			$sql = "DELETE FROM tblassembleproduct ";
			$sql.= "WHERE productcode = '".$prcode."' ";
			mysql_query($sql,get_db_conn());

			$sql = "UPDATE tblproduct SET ";
			$sql.= "sellprice=0 ";
			$sql.= "WHERE productcode='".$prcode."' ";
			$sql.= "AND assembleuse='Y' ";
			mysql_query($sql,get_db_conn());

			if(strlen($assemble_oribasic_list)>0) {
				$sql = "UPDATE tblproduct SET ";
				$sql.= "assembleproduct=REPLACE(assembleproduct,',".$prcode."','') ";
				$sql.= "WHERE pridx IN ('".str_replace("","','",$assemble_oribasic_list)."') ";
				$sql.= "AND assembleuse!='Y' ";
				mysql_query($sql,get_db_conn());
			}
		}
	}

	$onload = "<script type=\"text/javascript\">alert(\"�ڵ�/���� ��ǰ�� ���� ��ǰ ó���� ���� �Ϸ� �ƽ��ϴ�.\");</script>";
	echo "
		<form name=form1 action=\"".$_SERVER[PHP_SELF]."\" method=post>\n
		<input type=hidden name=mode value=\"\">
		<input type=hidden name=code value=\"".$code."\">
		<input type=hidden name=aname value=\"".$aname."\">
		<input type=hidden name=prcode value=\"".$prcode."\">
		<input type=hidden name=sort value=\"".$sort."\">
		<input type=hidden name=Scrolltype value=\"".$Scrolltype."\">
		<input type=hidden name=onload value=\"".htmlspecialchars($onload)."\">
		</form>\n
		<script type=\"text/javascript\">
		<!--
			document.form1.submit();
		//-->
		</script>
	";
}

$imagepath=$Dir.DataDir."shopimages/product/";
?>

<? INCLUDE "header.php"; ?>
<style>td {line-height:18pt;}</style>
<script type="text/javascript" src="lib.js.php"></script>
<script>var LH = new LH_create();</script>
<script for=window event=onload>LH.exec();</script>
<script>LH.add("parent_resizeIframe('ListFrame')");</script>

<script language="JavaScript">
<!--
var ProductInfoStop="";

function GoSort(sort) {
	document.form1.mode.value = "";
	document.form1.aname.value="prcode";
	document.form1.sort.value = sort;
	document.form1.submit();
}

function SelectList(idx)
{
	if(ProductInfoStop)
		ProductInfoStop = "";
	else
	{
		if(idx != document.form1.prcode.value) {
			document.form1.mode.value = '';
			document.form1.prcode.value = idx;
			document.form1.aname.value="prcode";
			document.form1.submit();
		}
	}
}

function onMouseColor(argValue)
{
	if(document.form1.prcode.value != argValue)
		return true;
	else
		return false;
}

function ProductInfo(prcode) {
	ProductInfoStop = "1";
	code=prcode.substring(0,12);
	popup="YES";
	document.form_reg.code.value=code;
	document.form_reg.prcode.value=prcode;
	document.form_reg.popup.value=popup;
	if (popup=="YES") {
		document.form_reg.action="product_register.add.php";
		document.form_reg.target="register";
		window.open("about:blank","register","width=820,height=700,scrollbars=yes,status=no");
	} else {
		document.form_reg.action="product_register.php";
		document.form_reg.target="";
	}
	document.form_reg.submit();
}

function DivScrollActive(arg1,divscroll_id,ListTable_id,activetype)
{
	if(!self.id)
	{
		self.id = self.name;
		parent.document.getElementById(self.id).style.height = parent.document.getElementById(self.id).height;
	}

	if(document.getElementById(divscroll_id) && document.getElementById("ListTTableId") && document.getElementById(ListTable_id) && parent.document.getElementById(self.id))
	{
		if(!document.getElementById(divscroll_id).height)
			document.getElementById(divscroll_id).height=document.getElementById(divscroll_id).offsetHeight;

		if(arg1>0)
		{
			if(document.getElementById(ListTable_id).offsetHeight > document.getElementById(divscroll_id).offsetHeight)
			{
				document.getElementById(divscroll_id).style.height="100%";
				parent.document.getElementById(self.id).style.height=document.getElementById("ListTTableId").offsetHeight;
			}
		}
		else
		{
			var default_divwidth = document.getElementById("ListTTableId").offsetHeight-document.getElementById(divscroll_id).offsetHeight+document.getElementById(divscroll_id).height;
			document.getElementById(divscroll_id).style.height=document.getElementById(divscroll_id).height;
			parent.document.getElementById(self.id).style.height=default_divwidth;
		}
	}

	document.form1.Scrolltype.value = arg1;
}

function ProductMouseOver(Obj) {
	obj = event.srcElement;
	WinObj=document.getElementById(Obj);
	obj._tid = setTimeout("ProductViewImage(WinObj)",200);
}
function ProductViewImage(WinObj) {
	WinObj.style.display = "";

	if(!WinObj.height)
		WinObj.height = WinObj.offsetTop;

	WinObjPY = WinObj.offsetParent.offsetHeight;
	WinObjST = WinObj.height-WinObj.offsetParent.scrollTop;
	WinObjSY = WinObjST+WinObj.offsetHeight;

	if(WinObjPY < WinObjSY)
		WinObj.style.top = WinObj.offsetParent.scrollTop-WinObj.offsetHeight+WinObjPY;
	else if(WinObjST < 0)
		WinObj.style.top = WinObj.offsetParent.scrollTop;
	else
		WinObj.style.top = WinObj.height;
}
function ProductMouseOut(Obj) {
	obj = event.srcElement;
	WinObj = document.getElementById(Obj);
	WinObj.style.display = "none";
	clearTimeout(obj._tid);
}


function assembleFormSubmit() {
	form = document.form1;

	if(form.assemble_title.selectedIndex<0) {
		document.assembleForm.assemble_title_code.value = "";
		document.assembleForm.assemble_title_list.value = "";
		document.assembleForm.assemble_basic_code.value = "";
	} else {
		document.assembleForm.assemble_title_code.value = form.assemble_title.selectedIndex;
		assemble_array_num = form.assemble_title.options[form.assemble_title.selectedIndex].value.split('');
		document.assembleForm.assemble_title_list.value = assemble_array_num[2];
		document.assembleForm.assemble_basic_code.value = assemble_array_num[3];
	}
	document.assembleForm.submit();
}

function assembleuse_change() {
	form = document.form1;
	if(document.getElementById("assembleidx")) {
		if(form.assembleuse[0].checked) {
			document.getElementById("assembleidx").style.display="none";
		} else if(form.assembleuse[1].checked) {
			document.getElementById("assembleidx").style.display="";
		}
	}
}

function assemble_title_change(assembleFormtype) {
	form = document.form1;
	form.assemble_update_type.value="";
	form.assemble_title_name.value="";
	form.assemble_type.checked=true;
	form.assemble_modify_list.value="";
	form.assemble_basic_code.value="";
	if(document.getElementById("btnidx")){
		document.getElementById("btnidx").src = "images/btn_input.gif";
	}

	if(assembleFormtype=="Y") {
		assembleFormSubmit();
	}
}

function assemble_title_move(movetype) {
	form = document.form1;
	if(form) {
		if(form.assemble_title.selectedIndex<0) {
			alert("�̵��� ���� ��ǰ Ÿ��Ʋ�� ������ �ּ���.");
			form.assemble_title.focus();
		} else {
			var moveobj_value = form.assemble_title.options[form.assemble_title.selectedIndex].value;
			var moveobj_text = form.assemble_title.options[form.assemble_title.selectedIndex].text;

			if(movetype=="up") {
				if(form.assemble_title.selectedIndex>0 && form.assemble_title.options[form.assemble_title.selectedIndex-1]) {
					var movego_value = form.assemble_title.options[form.assemble_title.selectedIndex-1].value;
					var movego_text = form.assemble_title.options[form.assemble_title.selectedIndex-1].text;
					form.assemble_title.options[form.assemble_title.selectedIndex-1].value=moveobj_value;
					form.assemble_title.options[form.assemble_title.selectedIndex-1].text=moveobj_text;
					form.assemble_title.options[form.assemble_title.selectedIndex].value=movego_value;
					form.assemble_title.options[form.assemble_title.selectedIndex].text=movego_text;
					form.assemble_title.options[form.assemble_title.selectedIndex].selected=false;
					form.assemble_title.options[form.assemble_title.selectedIndex-1].selected=true;
				}
			} else {
				if(form.assemble_title.options[form.assemble_title.selectedIndex+1]) {
					var movego_value = form.assemble_title.options[form.assemble_title.selectedIndex+1].value;
					var movego_text = form.assemble_title.options[form.assemble_title.selectedIndex+1].text;
					form.assemble_title.options[form.assemble_title.selectedIndex+1].value=moveobj_value;
					form.assemble_title.options[form.assemble_title.selectedIndex+1].text=moveobj_text;
					form.assemble_title.options[form.assemble_title.selectedIndex].value=movego_value;
					form.assemble_title.options[form.assemble_title.selectedIndex].text=movego_text;
					form.assemble_title.options[form.assemble_title.selectedIndex].selected=false;
					form.assemble_title.options[form.assemble_title.selectedIndex+1].selected=true;
				}
			}
			assemble_title_change('');
		}
	}
}

function assemble_title_delete() {
	form = document.form1;
	if(form) {
		if(form.assemble_title.selectedIndex<0) {
			alert("������ ���� ��ǰ Ÿ��Ʋ�� ������ �ּ���.");
			form.assemble_title.focus();
		} else {
			if(confirm("���õ� ���� ��ǰ Ÿ��Ʋ�� ���� �ϰڽ��ϱ�?")) {
				form.assemble_title.options[form.assemble_title.selectedIndex]=null;
				assemble_title_change('Y');
			}
		}
	}
}

function assemble_title_modify() {
	form = document.form1;
	if(form) {
		if(form.assemble_title.selectedIndex<0) {
			alert("������ ���� ��ǰ Ÿ��Ʋ�� ������ �ּ���.");
			form.assemble_title.focus();
		}
		else {
			form.assemble_update_type.value="modify";
			assemble_array_num=form.assemble_title.options[form.assemble_title.selectedIndex].value.split('');

			form.assemble_type.checked=(assemble_array_num[0]=="Y"?true:false);
			form.assemble_title_name.value=assemble_array_num[1];
			form.assemble_modify_list.value=assemble_array_num[2];
			form.assemble_basic_code.value=assemble_array_num[3];

			if(document.getElementById("btnidx")){
				document.getElementById("btnidx").src = "images/btn_modify.gif";
			}
		}
	} else {
		alert("���� ��ǰ Ÿ��Ʋ �߰� �� ������ �߻��ƽ��ϴ�.");
		return;
	}
}

function assemble_title_update() {
	form = document.form1;
	if(form) {
		if(form.assemble_title_name.value.length>0) {
			if(form.assemble_update_type.value=="modify") {
				form.assemble_title.options[form.assemble_title.selectedIndex].value=(form.assemble_type.checked?"Y":"N")+""+form.assemble_title_name.value+""+form.assemble_modify_list.value+""+form.assemble_basic_code.value;
				form.assemble_title.options[form.assemble_title.selectedIndex].text=form.assemble_title_name.value+(form.assemble_type.checked?" : �ʼ�(O)":" : �ʼ�(X)");
			} else {
				form.assemble_title.options[form.assemble_title.length] = new Option(form.assemble_title_name.value+(form.assemble_type.checked?" : �ʼ�(O)":" : �ʼ�(X)"),(form.assemble_type.checked?"Y":"N")+""+form.assemble_title_name.value+""+"", false, false);
			}
			assemble_title_change('');
		} else {
			alert("���� ��ǰ Ÿ��Ʋ�� �Է��� �ּ���.");
			form.assemble_title_name.focus();
			return;
		}
	} else {
		alert("���� ��ǰ Ÿ��Ʋ �߰� �� ������ �߻��ƽ��ϴ�.");
		return;
	}
}

function assemble_list_update(assemblelistproduct,assemblebasicproduct) {
	form = document.form1;
	if(form.assemble_title.selectedIndex<0) {
		alert('���� ��ǰ Ÿ��Ʋ�� ���� �ȵ� �ֽ��ϴ�.');
		form.assemble_title.focus();
		return false;
	} else if(assemblebasicproduct.length>0 && assemble_basic_check(assemblebasicproduct)==true) {
		alert('�ڵ�/���� �⺻��ǰ ���ý� ������ �̹� ��ϵ� ���� �⺻��ǰ�� �ߺ� ������ �Ұ��� �մϴ�.');
		form.assemble_title.focus();
		return false;
	} else {
		form.assemble_update_type.value="modify";
		assemble_array_num=form.assemble_title.options[form.assemble_title.selectedIndex].value.split('');
		form.assemble_title.options[form.assemble_title.selectedIndex].value=(assemble_array_num[0]=="Y"?"Y":"N")+""+assemble_array_num[1]+""+assemblelistproduct+""+assemblebasicproduct;
		form.assemble_title.options[form.assemble_title.selectedIndex].text=assemble_array_num[1]+(assemble_array_num[0]=="Y"?" : �ʼ�(O)":" : �ʼ�(X)");
		assemble_title_change('');
		return true;
	}
}

function assemble_basic_check(assemblebasicproduct) {
	var selectIndexValue = form.assemble_title.selectedIndex;
	for(var i=0; i<form.assemble_title.options.length; i++) {
		if(selectIndexValue!=i) {
			assemble_array_num=form.assemble_title.options[i].value.split('');
			if(assemble_array_num[3]==assemblebasicproduct) {
				return true;
			}
		}
	}
}

function FormSubmit() {
	form = document.form1;
	form.assemble_type_list.value="";
	form.assemble_title_list.value="";
	form.assemble_product_list.value="";
	form.assemble_basic_list.value="";
	for(var i=0; i<form.assemble_title.length; i++) {
		assemble_array_num = "";
		assemble_array_num = form.assemble_title.options[i].value.split('');

		if (i==0) {
			form.assemble_type_list.value=assemble_array_num[0];
			form.assemble_title_list.value=assemble_array_num[1];
			if(assemble_array_num[2].length>0) {
				form.assemble_product_list.value=","+assemble_array_num[2];
			}
			form.assemble_basic_list.value=assemble_array_num[3];
		} else {
			form.assemble_type_list.value+=""+assemble_array_num[0];
			form.assemble_title_list.value+=""+assemble_array_num[1];
			if(assemble_array_num[2].length>0) {
				form.assemble_product_list.value+=","+assemble_array_num[2];
			} else {
				form.assemble_product_list.value+="";
			}
			form.assemble_basic_list.value+=""+assemble_array_num[3];
		}
	}

	if(confirm("���� ������ǰ������ �����ϰڽ��ϱ�?")) {
		document.form1.mode.value = "update";
		form.submit();
	}
}
//-->
</script>
<table id="ListTTableId" border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" bgcolor="#ffffff">
<tr>
	<td valign="top" width="100%" height="100%">
	<table cellpadding="0" cellspacing="0" width="100%">
	<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
	<input type=hidden name=mode>
	<input type=hidden name=code value="<?=$code?>">
	<input type=hidden name=aname>
	<input type=hidden name=prcode value="<?=$prcode?>">
	<input type=hidden name=sort value="<?=$sort?>">
	<input type=hidden name=Scrolltype value="<?=$Scrolltype?>">
	<input type="hidden" name="assemble_title_list">
	<input type="hidden" name="assemble_type_list">
	<input type="hidden" name="assemble_product_list">
	<input type="hidden" name="assemble_basic_list">
	<input type="hidden" name="assemble_update_type">
	<input type="hidden" name="assemble_modify_list" value="">
	<input type="hidden" name="assemble_basic_code" value="">
	<tr>
		<td width="100%">
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td width="100%" bgcolor="#FFFFFF"><IMG SRC="images/product_assemble_text1.gif" border="0"></td>
		</tr>
		<tr>
			<td width="100%" height="100%" valign="top" style="BORDER:#FF8730 2px solid;padding-left:5px;padding-right:5px;">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td valign="top">
				<TABLE border="0" cellSpacing="0" cellPadding="0" width="100%">
				<TR>
					<TD width="100%" background="images/blueline_bg.gif">
					<TABLE border="0" cellSpacing="0" cellPadding="0" width="100%">
					<col width="250"></col>
					<col width=""></col>
					<col width="200"></col>
					<tr height="30">
						<td><B><span class="font_orange">* ���Ĺ�� :</span></B> <A HREF="javascript:GoSort('date');">������</a> | <A HREF="javascript:GoSort('productname');">��ǰ���</a> | <A HREF="javascript:GoSort('price');">���ݼ�</a></td>
						<td align=center><b><span class="font_blue">ī�װ��� �ڵ�/���� ��ǰ���</span></b></td>
						<td align="right"><a href="javascript:DivScrollActive('1','divscroll0','List0TableId',1);"><span style="letter-spacing:-0.5pt;" class="font_orange"><b>��ü��ħ</b></span></a>&nbsp;&nbsp;<a href="javascript:DivScrollActive('0','divscroll0','List0TableId',1);"><b>��ħ�ݱ�</b></a></td>
					</tr>
					</table>
					</TD>
				</TR>
				<TR>
					<TD background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD width="100%">
					<DIV id="divscroll0" style="position:relative;width:100%;height:278px;bgcolor:#FFFFFF;overflow-x:hidden;overflow-y:auto;">
					<TABLE id="List0TableId" border="0" cellSpacing="0" cellPadding="0" width="100%">
<?
			$colspan=7;
?>
					<col width=50></col>
					<col width=50></col>
					<col width=></col>
					<col width=70></col>
					<col width=45></col>
					<col width=45></col>
					<col width=45></col>
					<TR align="center">
						<TD class="table_cell">No</TD>
						<TD class="table_cell1" colspan="2">��ǰ��</TD>
						<TD class="table_cell1">�ǸŰ���</TD>
						<TD class="table_cell1">����</TD>
						<TD class="table_cell1">����</TD>
						<TD class="table_cell1">����</TD>
					</TR>
<?
			if (strlen($code)==12) {
				$prcode_selected[$prcode] = " bgcolor=\"#EFEFEF\"";
				$prcode_link[$prcode] = "<a name=\"prcodelink\">";

				$sql = "SELECT option_price,productcode,productname,production,sellprice,consumerprice, ";
				$sql.= "buyprice,quantity,reserve,addcode,display,vender,tinyimage,reservetype,assembleuse ";
				$sql.= "FROM tblproduct ";
				$sql.= "WHERE assembleuse = 'Y' ";
				$sql.= "AND productcode LIKE '".$code."%' ";
				if ($sort=="price")				$sql.= "ORDER BY sellprice ";
				else if ($sort=="productname")	$sql.= "ORDER BY productname ";
				else							$sql.= "ORDER BY date DESC ";
				$result = mysql_query($sql,get_db_conn());
				$t_count = @mysql_num_rows($result);
				$cnt=0;
				while($row=mysql_fetch_object($result)) {
					$number = $t_count-$cnt;
					echo "<tr>\n";
					echo "	<TD colspan=\"".$colspan."\" background=\"images/table_con_line.gif\">".$prcode_link[$row->productcode]."</TD>\n";
					echo "</tr>\n";
					echo "<tr align=\"center\"".$prcode_selected[$row->productcode]." onclick=\"SelectList('".$row->productcode."')\" id=\"pidx_".$row->productcode."\" onmouseover=\"if(onMouseColor('".$row->productcode."'))this.style.backgroundColor='#F4F7FC';\" onmouseout=\"if(onMouseColor('".$row->productcode."'))this.style.backgroundColor='';\" style=\"cursor:hand;\">\n";
					echo "	<TD class=\"td_con2\">".$number."<br><img src=\"images/btn_select1a.gif\" border=\"0\"></td>\n";
					echo "	<TD class=\"td_con1\">";
					if (strlen($row->tinyimage)>0 && file_exists($imagepath.$row->tinyimage)==true){
						echo "<img src='".$imagepath.$row->tinyimage."' height=40 width=40 border=1 onMouseOver=\"ProductMouseOver('primage".$image_i."')\" onMouseOut=\"ProductMouseOut('primage".$image_i."');\">";
					} else {
						echo "<img src=images/space01.gif onMouseOver=\"ProductMouseOver('primage".$image_i."')\" onMouseOut=\"ProductMouseOut('primage".$image_i."');\">";
					}
					echo "<div id=\"primage".$image_i."\" style=\"position:absolute; z-index:100; display:none;\"><table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"170\">\n";
					echo "		<tr bgcolor=\"#FFFFFF\">\n";
					if (strlen($row->tinyimage)>0) {
						echo "		<td align=\"center\" width=\"100%\" height=\"150\" style=\"border:#000000 solid 1px;\"><img src=\"".$imagepath.$row->tinyimage."\" border=\"0\"></td>\n";
					} else {
						echo "		<td align=\"center\" width=\"100%\" height=\"150\" style=\"border:#000000 solid 1px;\"><img src=\"images/product_noimg.gif\" border=\"0\"></td>\n";
					}
					echo "		</tr>\n";
					echo "		</table>\n";
					echo "		</div>\n";
					echo "	</td>\n";
					echo "	<TD class=\"td_con1\" align=\"left\" style=\"word-break:break-all;\"><img src=\"images/producttype".($row->assembleuse=="Y"?"y":"n").".gif\" border=\"0\" align=\"absmiddle\" hspace=\"2\">".$row->productname.($row->addcode?"-".$row->addcode:"")."&nbsp;</td>\n";
					echo "	<TD align=right class=\"td_con1\"><img src=\"images/won_icon.gif\" border=\"0\" style=\"margin-right:2px;\"><span class=\"font_orange\">".number_format($row->sellprice)."</span><br><img src=\"images/reserve_icon.gif\" border=\"0\" style=\"margin-right:2px;\">".($row->reservetype!="Y"?number_format($row->reserve):$row->reserve."%")."</TD>\n";
					echo "	<TD class=\"td_con1\">";
					if (strlen($row->quantity)==0) echo "������";
					else if ($row->quantity<=0) echo "<span class=\"font_orange\"><b>ǰ��</b></span>";
					else echo $row->quantity;
					echo "	</TD>\n";
					echo "	<TD class=\"td_con1\">".($row->display=="Y"?"<font color=\"#0000FF\">�Ǹ���</font>":"<font color=\"#FF4C00\">������</font>")."</td>";
					echo "	<TD class=\"td_con1\"><img src=\"images/icon_newwin1.gif\" border=\"0\" style=\"cursor:hand;\" onclick=\"ProductInfo('".$row->productcode."');\"></td>\n";
					echo "</tr>\n";
					$cnt++;
					$image_i++;
				}
				mysql_free_result($result);
				if ($cnt==0) {
					echo "<tr><TD colspan=\"".$colspan."\" background=\"images/table_con_line.gif\"></td></tr><tr><td class=\"td_con2\" colspan=\"".$colspan."\" align=\"center\">�˻��� ��ǰ�� �������� �ʽ��ϴ�.</td></tr>";
				}
			} else {
				echo "<tr><TD colspan=\"".$colspan."\" background=\"images/table_con_line.gif\"></td></tr><tr><td class=\"td_con2\" colspan=\"".$colspan."\" align=\"center\">�˻��� ��ǰ�� �������� �ʽ��ϴ�.</td></tr>";
			}
?>
				<TR>
						<TD height="1" colspan="<?=$colspan?>" background="images/table_con_line.gif"></TD>
					</TR>
					</table>
					</div>
					</TD>
				</TR>
				<TR>
					<TD background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD background="images/blueline_bg.gif" style="padding-top:3pt; padding-bottom:3pt;">
					<table cellpadding="0" cellspacing="0" width="100%">
					<col width="50"></col>
					<col width=""></col>
					<col width="50"></col>
					<tr>
						<td><img width="0" height="0"></td>
						<td align="center"><img src="images/product_collectionlist_img.gif" border="0"></td>
						<td><img width="0" height="0"></td>
					</tr>
					<tr>
						<td><img width="0" height="0"></td>
						<td align="center"><b><span class="font_blue">���õ� ��ǰ�� ��ϵ� �ڵ�/���� ����</span></b><br>
						<span class="font_orange"><b>**** �ڵ�/���� ���� ��ǰ�� ���� ���� ��ư�� �����ž߸� ����˴ϴ�. ****</b></span></td>
						<td align="right"><img width="0" height="0"></td>
					</tr>
					</table>
					</TD>
				</TR>
				<TR>
					<TD background="images/table_top_line.gif"></TD>
				</TR>
				<tr>
					<td valign="top">
					<table border=0 cellpadding=0 cellspacing=0 width="100%" bgcolor=FFFFFF>
					<tr>
						<td colspan="3">
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0 id="assembleidx">
						<tr>
							<td style="BORDER-top:#E3E3E3 1pt solid;"><img width="0" height="0"></td>
						</tr>
						<tr>
							<td style="padding:5px;">
							<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
							<col width="200"></col>
							<col width=""></col>
							<tr>
								<td valign="top" bgcolor="#FFF7F0" style="border:2px #FF7100 solid;border-right:1px #FF7100 solide;">
								<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
								<tr>
									<td height="7"></td>
								</tr>
								<tr>
									<td align="center" height="30"><b>���� ��ǰ Ÿ��Ʋ ����</b></td>
								</tr>
								<tr>
									<td height="3"></td>
								</tr>
								<tr>
									<td style="padding-left:5px;padding-right:5px;"><TABLE cellSpacing=0 cellPadding=0 width="100%" border=0><tr><td height="1" bgcolor="#DADADA"></td></tr></table></td>
								</tr>
								<tr>
									<td height="5"></td>
								</tr>
								<tr>
									<td align="center" style="padding-left:5px;padding-right:5px;">
<?
	if(strlen($prcode)>0) {
?>
									<table cellpadding="0" cellspacing="0" width="100%">
									<tr>
										<td width="100%" rowspan="2">
										<select name="assemble_title" size="30" style="width:100%;" onchange="assemble_title_change('Y');" ondblclick="assemble_title_modify();">
<?
		$sql = "SELECT * FROM tblassembleproduct ";
		$sql.= "WHERE productcode = '".$prcode."' ";
		$result = mysql_query($sql,get_db_conn());
		while($row=mysql_fetch_object($result)) {
			$assemble_type_exp=explode("",$row->assemble_type);
			$assemble_title_exp=explode("",$row->assemble_title);
			$assemble_list_exp=explode("",$row->assemble_list);
			$assemble_pridx_exp=explode("",$row->assemble_pridx);

			for($i=1; $i<count($assemble_type_exp); $i++) {
				echo "<option value=\"".($assemble_type_exp[$i]=="Y"?"Y":"N")."".$assemble_title_exp[$i]."".substr($assemble_list_exp[$i],1)."".$assemble_pridx_exp[$i]."\">".$assemble_title_exp[$i]." : �ʼ�(".($assemble_type_exp[$i]=="Y"?"O":"X").")</option>\n";
			}
		}
?>
										</select>
										</td>
										<td style="padding-left:5px;" valign="top">
										<table cellpadding="0" cellspacing="0">
										<TR>
											<TD align=middle><A href="JavaScript:assemble_title_move('up');"><IMG src="images/code_up.gif" align=absMiddle border="0" vspace="3"></A></td>
										</tr>
										<TR>
											<TD align=middle><IMG src="images/code_sort.gif" border="0"></td>
										 </tr>
										<TR>
											<TD align=middle><A href="JavaScript:assemble_title_move('down');"><IMG src="images/code_down.gif" align=absMiddle border="0" vspace="3"></A></td>
										</tr>
										</table>
										</td>
									</tr>
									<tr>
										<td style="padding-left:5px;" valign="bottom">
										<table cellpadding="0" cellspacing="0" valign="bottom">
										<TR>
											<TD align=middle><a href="javascript:assemble_title_modify();"><IMG src="images/btn_edit.gif" align=absMiddle border=0 vspace="4"></a></td>
										</tr>
										<TR>
											<TD align=middle><a href="javascript:assemble_title_delete();"><IMG src="images/btn_del.gif" align=absMiddle border=0 vspace="4"></a></td>
										</tr>
										</table>
										</td>
									</tr>
									<tr>
										<td height="5"></td>
									</tr>
									<tr>
										<td colspan="2"><TABLE cellSpacing=0 cellPadding=0 width="100%" border=0><tr><td height="1" bgcolor="#DADADA"></td></tr></table></td>
									</tr>
									<tr>
										<td height="5"></td>
									</tr>
									<tr>
										<td><input type="text" name="assemble_title_name" maxlength="50" value="" onKeyDown="chkFieldMaxLen(50)" class="input" style="width:100%;" id="assemble_title_nameidx"></td>
										<td style="padding-left:5px;"><a href="javascript:assemble_title_update();"><img src="images/btn_input.gif" id="btnidx" border="0" align="absmiddle" vspace="2"></a></td>
									</tr>
									<tr>
										<td colspan="2"><input type="checkbox" name="assemble_type" id="assemble_typeidx" value="Y" checked> <label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=assemble_typeidx><span class="font_orange"><b>���� ��ǰ �ʼ� ����</b></span></label></td>
									</tr>
									</table>
<?
	} else {
		echo "<table cellpadding=\"0\" cellspacing=\"0\">\n<tr>\n<td height=\"520\">�ڵ�/���� ��ǰ�� ������ �ּ���.</td>\n</tr>\n</table>";
	}
?>
									</td>
								</tr>
								<tr>
									<td height="10"></td>
								</tr>
								</table>
								</td>
								<td height=100% valign="top" bgcolor="#F1FFEF" style="border:2px #57B54A solid;border-left:1px #57B54A solide;">
								<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0 height=100%>
								<tr>
									<td height="7"></td>
								</tr>
								<tr>
									<td align="center" height="30"><b>���� ��ǰ ��� ����</b></td>
								</tr>
								<tr>
									<td height="3"></td>
								</tr>
								<tr>
									<td style="padding-left:5px;padding-right:5px;"><TABLE cellSpacing=0 cellPadding=0 width="100%" border=0><tr><td height="1" bgcolor="#DADADA"></td></tr></table></td>
								</tr>
								<tr>
									<td style="padding:5px;padding-bottom:0px;" height=100%><IFRAME name="AssembleListFrame" src="product_assemble.list.detail.php" width=100% height=100% frameborder=0 align=TOP scrolling="no" marginheight="0" marginwidth="0"></IFRAME></td>
								</tr>
								<tr>
									<td height="5"></td>
								</tr>
								</table>
								</td>
							</tr>
							</table>
							</td>
						</tr>
						</table>
						</td>
					</TR>
					</table>
					</TD>
				</TR>
				<TR>
					<TD background="images/table_top_line.gif"></TD>
				</TR>

				<tr>
					<td align="center" style="padding:10px;"><span class="font_orange"><b>**** �ڵ�/���� ���� ��ǰ�� ���� ���� ��ư�� �����ž߸� ����˴ϴ�. ****</b></span><br><a href="javascript:FormSubmit();"><img src="images/botteon_save.gif" border="0"></a></td>
				</tr>
				<tr>
					<td height="5"></td>
				</tr>
				</TABLE>
				</td>
			</tr>
			</table>
			</td>
		</tr>
		</table>
		</td>
	</tr>
	</form>
	</table>
	</td>
</tr>
<form name=form_reg action="product_register.php" method=post>
<input type=hidden name=code>
<input type=hidden name=prcode>
<input type=hidden name=popup>
</form>
<form name=assembleForm action="product_assemble.list.detail.php" method=post target=AssembleListFrame>
<input type=hidden name="assemble_title_code">
<input type=hidden name="assemble_title_list">
<input type=hidden name="assemble_basic_code">
</form>
</table>
<?
$script_echo = "<script>";
if($Scrolltype>0)
	$script_echo .= "DivScrollActive('1','divscroll0','List0TableId',0);";
else
	$script_echo .= "DivScrollActive('0','divscroll0','List0TableId',0);";

if (strlen($aname)>0) {
	if(strlen($prcode)>0)
		$script_echo .= "location.hash='prcodelink';\n";
	else
		$script_echo .= "location.hash='cateprcode';\n";
}

$script_echo .= "</script>";
?>
<?=$script_echo?>
<?=stripslashes($onload)?>
</body>
</html>