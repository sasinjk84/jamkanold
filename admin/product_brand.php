<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "pr-1";
$MenuCode = "product";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$sql = "SELECT etctype FROM tblshopinfo ";
$result=mysql_query($sql,get_db_conn());
$etctype= "";
$branduse="";
$brandleft="";
$brandlefty="";
$brandleftl="";
$brandpro="";
$brandmap="";
$brandmapt="";
if($row=mysql_fetch_object($result)) {
	if (strlen($row->etctype)>0) {
		$etctemp = @explode("",$row->etctype);
		
		for($i=0; $i<count($etctemp); $i++) {
			if (strlen($etctemp[$i])>0) {
				if(strlen(substr($etctemp[$i],9,1))>0 && substr($etctemp[$i],0,9) == "BRANDUSE=") {
					$branduse=substr($etctemp[$i],9,1);
				} else if(strlen(substr($etctemp[$i],10,1))>0 && substr($etctemp[$i],0,10) == "BRANDLEFT=") {
					$brandleft=substr($etctemp[$i],10,1);
				} else if(strlen(substr($etctemp[$i],11,3))>0 && substr($etctemp[$i],0,11) == "BRANDLEFTY=") {
					$brandlefty=substr($etctemp[$i],11,3);
				} else if(strlen(substr($etctemp[$i],11,1))>0 && substr($etctemp[$i],0,11) == "BRANDLEFTL=") {
					$brandleftl=substr($etctemp[$i],11,1);
				} else if(strlen(substr($etctemp[$i],9,1))>0 && substr($etctemp[$i],0,9) == "BRANDPRO=") {
					$brandpro=substr($etctemp[$i],9,1);
				} else if(strlen(substr($etctemp[$i],9,1))>0 && substr($etctemp[$i],0,9) == "BRANDMAP=") {
					$brandmap=substr($etctemp[$i],9,1);
				} else if(strlen(substr($etctemp[$i],10,1))>0 && substr($etctemp[$i],0,10) == "BRANDMAPT=") {
					$brandmapt=substr($etctemp[$i],10,1);
				} else {
					$etctempvalue[] = $etctemp[$i];
				}
			} else {
				$etctempvalue[] = "";
			}
		}

		$etctype = @implode("",$etctempvalue);
	}
}
mysql_free_result($result);

$type=$_POST["type"];
$up_branduse=$_POST["up_branduse"];
$up_brandleft=$_POST["up_brandleft"];
$up_brandlefty=(int)$_POST["up_brandlefty"];
$up_brandleftl=$_POST["up_brandleftl"];
$up_brandpro=$_POST["up_brandpro"];
$up_brandmap=$_POST["up_brandmap"];
$up_brandmapt=$_POST["up_brandmapt"];

if($type=="up") {
	$branduse="N";
	$brandleft="N";
	$brandlefty="";
	$brandleftl="N";
	$brandpro="N";
	$brandmap="N";
	$brandmapt="N";
	if(strlen($up_branduse)>0 && $up_branduse=="Y") { 
		$etctype.="BRANDUSE=Y";
		$branduse="Y";
		if(strlen($up_brandleft)>0 && $up_brandleft=="Y") {
			$etctype.="BRANDLEFT=Y";
			$brandleft="Y";

			if($up_brandlefty>0) {
				$etctype.="BRANDLEFTY=".$up_brandlefty."";
				$brandlefty=$up_brandlefty;
			}
			if(strlen($up_brandleftl)>0 && ($up_brandleftl=="Y" || $up_brandleftl=="B" || $up_brandleftl=="A")) {
				$etctype.="BRANDLEFTL=".$up_brandleftl."";
				$brandleftl=$up_brandleftl;
			}
		}
		if(strlen($up_brandpro)>0 && $up_brandpro=="Y") {
			$etctype.="BRANDPRO=Y";
			$brandpro="Y";
		}
		if(strlen($up_brandmap)>0 && $up_brandmap=="Y") {
			$etctype.="BRANDMAP=Y";
			$brandmap="Y";

			if(strlen($up_brandmapt)>0 && $up_brandmapt=="Y") {
				$etctype.="BRANDMAPT=Y";
				$brandmapt="Y";
			}
		}
	}

	$sql="UPDATE tblshopinfo SET etctype='".$etctype."' ";
	mysql_query($sql,get_db_conn());
	DeleteCache("tblshopinfo.cache");
	$onload="<script>alert('�귣�� ���� ������ ������ �Ϸ�Ǿ����ϴ�.');</script>";
} else if($type=="save") {
	if($edittype == "insert") {
		if(strlen($up_brandname)>0) {
			$sql = "INSERT tblproductbrand SET ";
			$sql.= "brandname	= '".$up_brandname."' ";
			if(mysql_query($sql,get_db_conn())) {
				$onload="<script>alert('�귣�� ����� ���� �Ϸ�Ǿ����ϴ�.');</script>";
				DeleteCache("tblproductbrand.cache");
			} else {
				echo "<script>alert('���ϸ��� �����մϴ�. �ٸ� �귣����� �Է��� �ּ���.');history.go(-1);</script>";
				exit;
			}
		} else {
			echo "<script>alert('�߰��� �귣����� �Է��� �ּ���.');history.go(-1);</script>";
			exit;
		}
	} else if($edittype == "update") {
		if(strlen($up_brandname)>0 && (int)$up_brandlist>0) {
			$sql = "UPDATE tblproductbrand SET ";
			$sql.= "brandname	= '".$up_brandname."' ";
			$sql.= "WHERE bridx = '".$up_brandlist."' ";
			if(mysql_query($sql,get_db_conn())) {
				$onload="<script>alert('�귣�� ������ ���� �Ϸ�Ǿ����ϴ�.');</script>";
				DeleteCache("tblproductbrand.cache");
			} else {
				echo "<script>alert('���ϸ��� �����մϴ�. �ٸ� �귣����� �Է��� �ּ���.');history.go(-1);</script>";
				exit;
			}
		} else if((int)$up_brandlist<1) {
			echo "<script>alert('������ �귣�带 ������ �ּ���.');history.go(-1);</script>";
			exit;
		} else {
			echo "<script>alert('�߰��� �귣����� �Է��� �ּ���.');history.go(-1);</script>";
			exit;
		}
	} else if($edittype == "delete") {
		if((int)$up_brandlist>0) {
			$sql = "DELETE FROM tblproductbrand ";
			$sql.= "WHERE bridx = '".$up_brandlist."' ";
			if(mysql_query($sql,get_db_conn())) {
				$sql = "UPDATE FROM tblproduct ";
				$sql.= "brand = null ";
				$sql.= "WHERE brand = '".$up_brandlist."' ";
				mysql_query($sql,get_db_conn());
				$onload="<script>alert('�귣�� ������ ���� �Ϸ�Ǿ����ϴ�.');</script>";
				DeleteCache("tblproductbrand.cache");
			}
		} else {
			echo "<script>alert('������ �귣�带 ������ �ּ���.');history.go(-1);</script>";
			exit;
		}
	}
}

if($branduse != "Y") {
	$branddisabled="disabled";
	$brandleftdisabled="disabled";
	$brandmapdisabled="disabled";
} else if($brandleft != "Y") {
	$brandleftdisabled="disabled";
} else if($brandleft != "Y") {
	$brandmapdisabled="disabled";
}
?>
<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function CheckForm(typeval) {
	form = document.form1;
	var submit_val = "";
	
	if(typeval == "up") {
		var brandleftyval = document.form1.up_brandlefty.value;
		if(document.form1.up_brandlefty.disabled == false && (!brandleftyval || isNaN(brandleftyval) || parseInt(brandleftyval)<1 || brandleftyval != parseInt(brandleftyval))) {
			alert('�귣�� ��� ���̴� 0���� ū ���ڸ� �Է��� �ּ���.');
			form.up_brandname.focus();
			submit_val = "no";
		} else if(confirm("�귣�� ������ ������ �����ϰڽ��ϱ�?")){
			submit_val = "ok";
		} else {
			submit_val = "no";
		}
	} else if(typeval == "save") {
		if(form.edittype.value == "update" || form.edittype.value == "insert") {
			if(!form.up_brandname.value) {
				alert('�귣�� ���� �Է��� �ּ���.');
				form.up_brandname.focus();
				submit_val = "no";
			}

			for(var i=0; i<form.up_brandlist.options.length; i++) {
				if(form.up_brandname.value == form.up_brandlist.options[i].text) {
					alert('���� ���� �귣�� ���� �����մϴ�. �ٸ� �귣����� �Է� �� �ּ���.');
					form.up_brandname.focus();
					submit_val = "no";
					break;
				}
			}
		}
		
		if(!submit_val) {
			if(form.edittype.value == "update" && confirm("�ش� �귣�尡 �Էµ� ��ǰ�� �귣�嵵 ���� ����˴ϴ�.\n\n���õ� �귣����� ���� �����ϰڽ��ϱ�?")) {
				submit_val = "ok";
			} else if(form.edittype.value == "insert" && confirm("�űԷ� �귣�带 �߰��ϰڽ��ϱ�?")) {
				submit_val = "ok";
			} else if(form.edittype.value == "delete") {
				if(confirm("�ش� �귣�尡 �Էµ� ��ǰ�� �귣�嵵 ���� �����˴ϴ�.\n\n���õ� �귣�带 ���� �����ϰڽ��ϱ�?")) {
					submit_val = "ok";
				} else {
					edittype_select("insert");
				}
			}
		}
	}
	
	if(submit_val == "ok") {
		form.type.value=typeval;
		form.submit();
	}
}

function brandleft_change(form) {
	if(form.up_branduse[0].checked == true && form.up_brandleft[0].checked == true) {
		form.up_brandlefty.disabled = false;
		form.up_brandleftl[0].disabled = false;
		form.up_brandleftl[1].disabled = false;
		form.up_brandleftl[2].disabled = false;
	} else {
		form.up_brandlefty.disabled = true;
		form.up_brandleftl[0].disabled = true;
		form.up_brandleftl[1].disabled = true;
		form.up_brandleftl[2].disabled = true;
	}
}

function brandmap_change(form) {
	if(form.up_branduse[0].checked == true && form.up_brandmap[0].checked == true) {
		form.up_brandmapt[0].disabled = false;
		form.up_brandmapt[1].disabled = false;
	} else {
		form.up_brandmapt[0].disabled = true;
		form.up_brandmapt[1].disabled = true;
	}
}

function branduse_change(form) {
	if(form.up_branduse[0].checked == true) {
		form.up_brandleft[0].disabled = false;
		form.up_brandpro[0].disabled = false;
		form.up_brandmap[0].disabled = false;
		form.up_brandleft[1].disabled = false;
		form.up_brandpro[1].disabled = false;
		form.up_brandmap[1].disabled = false;
	} else {
		form.up_brandleft[0].disabled = true;
		form.up_brandpro[0].disabled = true;
		form.up_brandmap[0].disabled = true;
		form.up_brandleft[1].disabled = true;
		form.up_brandpro[1].disabled = true;
		form.up_brandmap[1].disabled = true;
	}
	brandleft_change(form);
	brandmap_change(form);
}

function edittype_select(edittypeval) {
	form = document.form1;
	if((edittypeval == "update" || edittypeval == "delete") && form.up_brandlist.selectedIndex<0) {
		alert('������ �귣�带 ������ �ּ���.');
		form.up_brandlist.focus();
	} else {
	
		form.edittype.value="";

		if(edittypeval == "update") {
			document.getElementById("update").style.backgroundColor = "#FF4C00";
			document.getElementById("insert").style.backgroundColor = "#FFFFFF";
			document.getElementById("delete").style.backgroundColor = "#FFFFFF";
			form.edittype.value = "update";
			form.up_brandname.value = form.up_brandlist.options[form.up_brandlist.selectedIndex].text;
		} else if(edittypeval == "insert") {
			document.getElementById("update").style.backgroundColor = "#FFFFFF";
			document.getElementById("insert").style.backgroundColor = "#FF4C00";
			document.getElementById("delete").style.backgroundColor = "#FFFFFF";
			form.edittype.value = "insert";
			form.up_brandname.value = "";
		} else if(edittypeval == "delete") {
			document.getElementById("update").style.backgroundColor = "#FFFFFF";
			document.getElementById("insert").style.backgroundColor = "#FFFFFF";
			document.getElementById("delete").style.backgroundColor = "#FF4C00";
			form.edittype.value = "delete";
			CheckForm('save');
		}
	}
}

function brandlist_change() {
	form = document.form1;
	if(form.edittype.value == "update") {
		form.up_brandname.value = form.up_brandlist.options[form.up_brandlist.selectedIndex].text;
	}
}

function defaultreset() {
	branduse_change(document.form1);
	if(document.form1.edittype.value == "update") {
		edittype_select("update");
	} else {
		edittype_select("insert");
	}
}

function SearchSubmit(seachIdxval) {
	form = document.form1;
	form.type.value="";
	form.edittype.value="";
	form.seachIdx.value = seachIdxval;
	form.submit();
}
</script>
<STYLE type=text/css>
	#menuBar {}
	#contentDiv {WIDTH: 200;HEIGHT: 315;}
</STYLE>
<body onload="defaultreset();">
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
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ��ǰ���� &gt;ī�װ�/��ǰ���� &gt; <span class="2depth_select">��ǰ �귣�� ����</span>/td>
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
			<tr><td height="8"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/product_brand_title.gif" border="0"></TD>
					</tr><tr>
					<TD width="100%" background="images/title_bg.gif" height=21></TD>
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
					<TD width="100%" class="notice_blue">�귣�� �߰�, ����, ������ �����ϸ� �귣�� ���� �������� ��� ������ �� �� �ֽ��ϴ�.</TD>
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
					<TD><IMG SRC="images/product_brand_stitle01.gif" border="0"></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height="3"></td></tr>
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=type>
			<input type=hidden name=edittype value="insert">
			<input type=hidden name=seachIdx value="<?=$seachIdx?>">
			<tr>
				<td>
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td bgcolor="#ededed" style="padding:4pt;">
					<table cellpadding="0" cellspacing="0" width="100%" bgcolor="white">
					<tr>
						<td>
						<TABLE cellSpacing=0 cellPadding="0" width="100%" border=0>
						<TR>
							<TD>
							<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
							<col width="170"></col>
							<col width="200"></col>
							<col></col>
							<TR>
								<TD style="padding:15px;" bgcolor="#F8F8F8" colspan="3" align="center" class="td_con1"><span class="font_orange"><input type=radio id="idx_branduse1" name=up_branduse value="Y"<?=($branduse=="Y"?" checked":"")?> onclick="branduse_change(this.form);"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_branduse1><b>�귣�� ������ �����</b></label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input type=radio id="idx_branduse2" name=up_branduse value="N"<?=($branduse!="Y"?" checked":"")?> onclick="branduse_change(this.form);"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_branduse2><b>�귣�� ������ ������</b></label></span></TD>
							</TR>
							<TR>
								<TD colspan="3" bgcolor="#EDEDED" height="1"></TD>
							</TR>
							<TR>
								<TD style="padding:7px;" bgcolor="#F8F8F8" class="td_con1" align="center" rowspan="2"><b>�����޴� �귣�� ���</b></TD>
								<TD style="padding:5px;" class="td_con1"><input type=radio id="idx_brandleft1" name=up_brandleft value="Y"<?=($brandleft=="Y"?" checked":"")?> <?=$branddisabled?> onclick="branduse_change(this.form);"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_brandleft1>�����޴� �귣�� ��� �����</label></TD>
								<TD style="padding:5px;"><input type=radio id="idx_brandleft2" name=up_brandleft value="N"<?=($brandleft!="Y"?" checked":"")?> <?=$branddisabled?> onclick="branduse_change(this.form);"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_brandleft2>�����޴� �귣�� ��� ������</label></TD>
							</TR>
							<TR>
								<TD style="padding:5px;padding-left:24px;padding-top:0px;" colspan="3" class="td_con1">
								<TABLE width="80%"cellSpacing=0 cellPadding=0 border=0 style="border:1px #EDEDED solid;">
								<col width="140"></col>
								<col></col>
								<tr>
									<TD bgcolor="#F8F8F8" style="padding-left:5px;padding-right:5px;">�귣�� ��� ����</TD>
									<td style="padding-left:5px;padding-right:5px;border-left:1px #EDEDED solid;">&nbsp;<input type=text name="up_brandlefty" value="<?=$brandlefty?>" size="3" maxlength="3" style="width:40;" class="input" <?=$brandleftdisabled?>> �ȼ� <span class="font_orange" style="font-size:11px;letter-spacing:-0.5pt;">�� 0���� ū ���ڸ� �����մϴ�.</span></td>
								</tr>
								<TR>
									<TD colspan="2" bgcolor="#EDEDED" height="1"></TD>
								</TR>
								<tr>
									<TD bgcolor="#F8F8F8" style="padding-left:5px;padding-right:5px;">�귣�� ��� ���</TD>
									<td style="padding-left:5px;padding-right:5px;border-left:1px #EDEDED solid;">
									<input type=radio name="up_brandleftl" id="idx_brandleftl1" value="Y"<?=($brandleftl=="Y"?" checked":"")?> <?=$brandleftdisabled?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_brandleftl1">���� ������ + �귣�� �������������� ���</label><br>
									<input type=radio name="up_brandleftl" id="idx_brandleftl2" value="B"<?=($brandleftl=="B"?" checked":"")?> <?=$brandleftdisabled?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_brandleftl2">�귣�� ���������������� ���</label><br>
									<input type=radio name="up_brandleftl" id="idx_brandleftl3" value="A"<?=($brandleftl=="A"?" checked":"")?> <?=$brandleftdisabled?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_brandleftl3">��� ���������� ���</label>
									</td>
								</tr>
								</table>
								<span class="font_orange" style="font-size:11px;letter-spacing:-0.5pt;">�� �귣�� ������������ '�귣�� ��ǰ ��� ������' �� '�귣��� ������' �Դϴ�.</span>
								</TD>
							</TR>
							<TR>
								<TD colspan="3" bgcolor="#EDEDED" height="1"></TD>
							</TR>
							<TR>
								<TD style="padding:7px;" bgcolor="#F8F8F8" class="td_con1" align="center"><b>�귣�� ��ǰ ��� ������</b></TD>
								<TD style="padding:5px;" class="td_con1"><input type=radio id="idx_brandpro1" name=up_brandpro value="Y"<?=($brandpro=="Y"?" checked":"")?> <?=$branddisabled?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_brandpro1>�귣�� ��ǰ ��� ������ �����</label></TD>
								<TD style="padding:5px;"><input type=radio id="idx_brandpro2" name=up_brandpro value="N"<?=($brandpro!="Y"?" checked":"")?> <?=$branddisabled?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_brandpro2>�귣�� ��ǰ ��� ������ ������</label></TD>
							</TR>
							<TR>
								<TD colspan="3" bgcolor="#EDEDED" height="1"></TD>
							</TR>
							<TR>
								<TD style="padding:7px;" bgcolor="#F8F8F8" class="td_con1" align="center" rowspan="2"><b>�귣�� �� ������</b></TD>
								<TD style="padding:5px;" class="td_con1"><input type=radio id="idx_brandmap1" name=up_brandmap value="Y"<?=($brandmap=="Y"?" checked":"")?> <?=$branddisabled?> onclick="branduse_change(this.form);"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_brandmap1>�귣�� �� ������ �����</label></TD>
								<TD style="padding:5px;"><input type=radio id="idx_brandmap2" name=up_brandmap value="N"<?=($brandmap!="Y"?" checked":"")?> <?=$branddisabled?> onclick="branduse_change(this.form);"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_brandmap2>�귣�� �� ������ ������</label></TD>
							</TR>
							<TR>
								<TD style="padding:5px;padding-left:24px;padding-top:0px;" colspan="2" class="td_con1">
								<TABLE width="80%"cellSpacing=0 cellPadding=0 border=0 style="border:1px #EDEDED solid;">
								<col width="140"></col>
								<col></col>
								<tr>
									<TD bgcolor="#F8F8F8" style="padding-left:5px;padding-right:5px;">�귣��� �켱 ���� ����</TD>
									<td style="padding-left:5px;padding-right:5px;border-left:1px #EDEDED solid;"><input type=radio name="up_brandmapt" id="idx_brandmapa" value="N"<?=($brandmapt!="Y"?" checked":"")?> <?=$brandmapdisabled?> onclick="branduse_change(this.form);"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_brandmapa>����켱</label>&nbsp;&nbsp;&nbsp;&nbsp;
									<input type=radio name="up_brandmapt" id="idx_brandmapb" value="Y"<?=($brandmapt=="Y"?" checked":"")?> <?=$brandmapdisabled?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_brandmapb>�ѱۿ켱</label></td>
								</tr>
								</table>
								</TD>
							</TR>
							</TABLE>
							</TD>
						</TR>
						</TABLE>
						</td>
					</tr>
					</table>
					</td>
				</tr>
				<tr><td height=10></td></tr>
				<tr>
					<td colspan="2" align="center"><a href="javascript:CheckForm('up');"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a></td>
				</tr>
				</table>
				</td>
			</tr>
			<tr><td height="20"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/product_brand_stitle02.gif" border="0"></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height="3"></td></tr>
			<tr>
				<td>
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td bgcolor="#ededed" style="padding:4pt;">
					<table cellpadding="0" cellspacing="0" width="100%" bgcolor="#FFFFFF">
					<tr>
						<td>
						<table cellpadding="0" cellspacing="0" width="100%" bgcolor="#FFFFFF">
						<tr>
							<td style="padding:1px;" bgcolor="#EDEDED" valign="top">
							<table cellpadding="0" cellspacing="0" width="100%" bgcolor="#FFFFFF">
							<col width="190"></col>
							<col></col>
							<tr>
								<td align="center" bgcolor="#F8F8F8"><b>��ǰ �귣�� ���</b></td>
								<td style="padding:5px;" class="td_con1">
								<table border=0 cellpadding=0 cellspacing=0 width="100%">
								<tr>
									<td style="padding:5px;padding-left:2px;padding-right:2px;letter-spacing:1.5pt;"><b><a href="javascript:SearchSubmit('A');"><span id="A">A</span></a> 
									<a href="javascript:SearchSubmit('B');"><span id="B">B</span></a> 
									<a href="javascript:SearchSubmit('C');"><span id="C">C</span></a> 
									<a href="javascript:SearchSubmit('D');"><span id="D">D</span></a> 
									<a href="javascript:SearchSubmit('E');"><span id="E">E</span></a> 
									<a href="javascript:SearchSubmit('F');"><span id="F">F</span></a> 
									<a href="javascript:SearchSubmit('G');"><span id="G">G</span></a> 
									<a href="javascript:SearchSubmit('H');"><span id="H">H</span></a> 
									<a href="javascript:SearchSubmit('I');"><span id="I">I</span></a> 
									<a href="javascript:SearchSubmit('J');"><span id="J">J</span></a> 
									<a href="javascript:SearchSubmit('K');"><span id="K">K</span></a> 
									<a href="javascript:SearchSubmit('L');"><span id="L">L</span></a> 
									<a href="javascript:SearchSubmit('M');"><span id="M">M</span></a> 
									<a href="javascript:SearchSubmit('N');"><span id="N">N</span></a> 
									<a href="javascript:SearchSubmit('O');"><span id="O">O</span></a> 
									<a href="javascript:SearchSubmit('P');"><span id="P">P</span></a> 
									<a href="javascript:SearchSubmit('Q');"><span id="Q">Q</span></a> 
									<a href="javascript:SearchSubmit('R');"><span id="R">R</span></a> 
									<a href="javascript:SearchSubmit('S');"><span id="S">S</span></a> 
									<a href="javascript:SearchSubmit('T');"><span id="T">T</span></a> 
									<a href="javascript:SearchSubmit('U');"><span id="U">U</span></a> 
									<a href="javascript:SearchSubmit('V');"><span id="V">V</span></a> 
									<a href="javascript:SearchSubmit('W');"><span id="W">W</span></a> 
									<a href="javascript:SearchSubmit('X');"><span id="X">X</span></a> 
									<a href="javascript:SearchSubmit('Y');"><span id="Y">Y</span></a> 
									<a href="javascript:SearchSubmit('Z');"><span id="Z">Z</span></a></b></td>
									<td width="50" align="center" nowrap><b><a href="javascript:SearchSubmit('��ü');"><span id="��ü">��ü</span></a></b></td>
								</tr>
								<tr>
									<td>
									<select name="up_brandlist" size="20" style="width:100%;" onchange="brandlist_change();">
<?
$sql = "SELECT * FROM tblproductbrand ";

if(ereg("^[A-Z]", $seachIdx)) {
	$sql.= "WHERE brandname LIKE '".$seachIdx."%' OR brandname LIKE '".strtolower($seachIdx)."%' ";	
	$sql.= "ORDER BY brandname ";
} else if(ereg("^[��-��]", $seachIdx)) {
	if($seachIdx == "��") $sql.= "WHERE (brandname >= '��' AND brandname < '��') OR (brandname >= '��' AND brandname < '��') ";
	if($seachIdx == "��") $sql.= "WHERE (brandname >= '��' AND brandname < '��') OR (brandname >= '��' AND brandname < '��') ";
	if($seachIdx == "��") $sql.= "WHERE (brandname >= '��' AND brandname < '��') OR (brandname >= '��' AND brandname < '��') ";
	if($seachIdx == "��") $sql.= "WHERE (brandname >= '��' AND brandname < '��') OR (brandname >= '��' AND brandname < '��') ";
	if($seachIdx == "��") $sql.= "WHERE (brandname >= '��' AND brandname < '��') OR (brandname >= '��' AND brandname < '��') ";
	if($seachIdx == "��") $sql.= "WHERE (brandname >= '��' AND brandname < '��') OR (brandname >= '��' AND brandname < '��') ";
	if($seachIdx == "��") $sql.= "WHERE (brandname >= '��' AND brandname < '��') OR (brandname >= '��' AND brandname < '��') ";
	if($seachIdx == "��") $sql.= "WHERE (brandname >= '��' AND brandname < '��') OR (brandname >= '��' AND brandname < '��') ";
	if($seachIdx == "��") $sql.= "WHERE (brandname >= '��' AND brandname < '��') OR (brandname >= '��' AND brandname < '��') ";
	if($seachIdx == "��") $sql.= "WHERE (brandname >= '��' AND brandname < '��') OR (brandname >= '��' AND brandname < 'ī') ";
	if($seachIdx == "��") $sql.= "WHERE (brandname >= '��' AND brandname < '��') OR (brandname >= 'ī' AND brandname < 'Ÿ') ";
	if($seachIdx == "��") $sql.= "WHERE (brandname >= '��' AND brandname < '��') OR (brandname >= 'Ÿ' AND brandname < '��') ";
	if($seachIdx == "��") $sql.= "WHERE (brandname >= '��' AND brandname < '��') OR (brandname >= '��' AND brandname < '��') ";
	if($seachIdx == "��") $sql.= "WHERE (brandname >= '��' AND brandname < '��') OR (brandname >= '��' AND brandname < 'ɡ') ";
	$sql.= "ORDER BY brandname ";
} else if($seachIdx == "��Ÿ") {
	$sql.= "WHERE (brandname < '��' OR brandname >= '��') AND (brandname < '��' OR brandname >= 'ɡ') AND (brandname < 'a' OR brandname >= '{') AND (brandname < 'A' OR brandname >= '[') ";
	$sql.= "ORDER BY brandname ";
} else {
	$sql.= "ORDER BY brandname ";
}

$result=mysql_query($sql,get_db_conn());
while($row=mysql_fetch_object($result)) {
	echo "<option value=\"".$row->bridx."\">".$row->brandname."</option>";
}
?>
									</select></td>
									<td width="50" align="center" nowrap style="line-height:21px;" valign="top"><b><a href="javascript:SearchSubmit('��');"><span id="��">��</span></a><br>
									<a href="javascript:SearchSubmit('��');"><span id="��">��</span></a><br>
									<a href="javascript:SearchSubmit('��');"><span id="��">��</span></a><br>
									<a href="javascript:SearchSubmit('��');"><span id="��">��</span></a><br>
									<a href="javascript:SearchSubmit('��');"><span id="��">��</span></a><br>
									<a href="javascript:SearchSubmit('��');"><span id="��">��</span></a><br>
									<a href="javascript:SearchSubmit('��');"><span id="��">��</span></a><br>
									<a href="javascript:SearchSubmit('��');"><span id="��">��</span></a><br>
									<a href="javascript:SearchSubmit('��');"><span id="��">��</span></a><br>
									<a href="javascript:SearchSubmit('��');"><span id="��">��</span></a><br>
									<a href="javascript:SearchSubmit('��');"><span id="��">��</span></a><br>
									<a href="javascript:SearchSubmit('��');"><span id="��">��</span></a><br>
									<a href="javascript:SearchSubmit('��');"><span id="��">��</span></a><br>
									<a href="javascript:SearchSubmit('��');"><span id="��">��</span></a><br>
									<a href="javascript:SearchSubmit('��Ÿ');"><span id="��Ÿ">��Ÿ</span></a></b></td>
								</tr>
								</table>
								</td>
							</tr>
							<TR>
								<TD colspan="2" bgcolor="#EDEDED" height="1"></TD>
							</TR>
							<tr>
								<td align="center" bgcolor="#F8F8F8"><b>���� ��� ����</b></td>
								<td class="td_con1" align="center">
								<table cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
								<tr>
									<td id="insert" style="background-color:#FF4C00;padding:5px;"><div style="padding:5px;background-color:'#FFFFFF';"><img src="images/btn_add2.gif" border="0" style="cursor:hand;" onclick="edittype_select('insert');"></div></td>
									<td style="padding-left:20px;padding-right:20px;">
									<table cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
									<tr>
										<td id="update" style="padding:5px;"><div style="padding:5px;background-color:'#FFFFFF';"><img src="images/btn_edit.gif" border="0" style="cursor:hand;" onclick="edittype_select('update');"></div></td>
									</tr>
									</table>
									</td>
									<td id="delete" style="padding:5px;"><div style="padding:5px;background-color:'#FFFFFF';"><img src="images/btn_del.gif" border="0" style="cursor:hand;" onclick="edittype_select('delete');"></div></td>
								</tr>
								</table>
								</td>
							</tr>
							<TR>
								<TD colspan="2" bgcolor="#EDEDED" height="1"></TD>
							</TR>
							<tr>
								<td align="center" bgcolor="#F8F8F8"><b>��ǰ �귣���</b></td>
								<td style="padding:5px;" class="td_con1"><input type=text name="up_brandname" value="" size="50" maxlength="50" onKeyDown="chkFieldMaxLen(50)" class="input"><a href="javascript:CheckForm('save');"><img src="images/btn_save.gif" border="0" hspace="5" align="absmiddle"></a></td>
							</td>
							</table>
							</td>
						</tr>
						</table>
						</td>
					</tr>
					</table>
					</td>
				</tr>
				</form>
				<tr><td height=20></td></tr>
				<tr>
					<td colspan="2">
					<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
					<TR>
						<TD><IMG SRC="images/manual_top1.gif" WIDTH=15 HEIGHT=45></TD>
						<TD><IMG SRC="images/manual_title.gif" WIDTH=113 HEIGHT=45></TD>
						<TD width="100%" background="images/manual_bg.gif"></TD>
						<TD background="images/manual_bg.gif"></TD>
						<TD><IMG SRC="images/manual_top2.gif" WIDTH=18 HEIGHT=45></TD>
					</TR>
					<TR>
						<TD background="images/manual_left1.gif"></TD>
						<TD COLSPAN=3 width="100%" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;" class="menual_bg">
						<table cellpadding="0" cellspacing="0" width="100%">
						<col width=20></col>
						<col width=></col>
						<tr>
							<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
							<td width="100%"><span class="font_dotline">�귣�� ������ ����</span></td>
						</tr>
						<tr>
							<td width="20" align="right">&nbsp;</td>
							<td width="100%" class="space_top" style="letter-spacing:-0.5pt;">- �귣�� ������ ��������� �����ÿ��� ������ ���� ������ ��뿩�θ� ������ �� �ֽ��ϴ�.</td>
						</tr>
						<tr>
							<td height="20" colspan="2"></td>
						</tr>
						<tr>
							<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
							<td width="100%"><span class="font_dotline">��ǰ �귣�� ����</span></td>
						</tr>
						<tr>
							<td width="20" align="right">&nbsp;</td>
							<td width="100%" class="space_top" style="letter-spacing:-0.5pt;">- ���� ��忡 ���� �귣�带 ���/����/������ �����մϴ�.</td>
						</tr>
						<tr>
							<td width="20" align="right">&nbsp;</td>
							<td width="100%" class="space_top" style="letter-spacing:-0.5pt;">- <font color="#FF4C00">���� ��忡 ���� ����� ������ �ش� �귣�尡 �Էµ� ��ǰ���� �����ϰ� ����˴ϴ�.</font></td>
						</tr>
						<tr>
							<td width="20" align="right">&nbsp;</td>
							<td width="100%" class="space_top" style="letter-spacing:-0.5pt;">- ��ϵ� �귣��� ��ǰ���/������ �귣�带 ������ �� �ֽ��ϴ�.</td>
						</tr>
						<tr>
							<td width="20" align="right">&nbsp;</td>
							<td width="100%" class="space_top" style="letter-spacing:-0.5pt;">- ��ǰ���/������ �����Է��� �귣��� �귣�� ��Ͽ� �ڵ� ��ϵ˴ϴ�.</td>
						</tr>
						</table>
						</TD>
						<TD background="images/manual_right1.gif"></TD>
					</TR>
					<TR>
						<TD><IMG SRC="images/manual_left2.gif" WIDTH=15 HEIGHT=8></TD>
						<TD COLSPAN=3 background="images/manual_down.gif"></TD>
						<TD><IMG SRC="images/manual_right2.gif" WIDTH=18 HEIGHT=8></TD>
					</TR>
					</TABLE>
					</td>
				</tr>
				<tr>
					<td height="50" colspan="2"></td>
				</tr>
				</table>
				</td>
			</tr>
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
<script language="javascript">
<!--
<?
	if(strlen($seachIdx)>0) {
		echo "document.getElementById(\"$seachIdx\").style.color=\"#FF4C00\";";
	} else {
		echo "document.getElementById(\"��ü\").style.color=\"#FF4C00\";";
	}
?>
//-->
</script>
<?=$onload?>
<? INCLUDE "copyright.php"; ?>