<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "de-5";
$MenuCode = "design";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$type=$_POST["type"];
$body=$_POST["body"];
$code=$_POST["code"];

if(strlen($code)==0) {
	$code="1";
}

$imagepath = $Dir.DataDir."shopimages/etc/";
$btimage_name="btbackground.gif";
$btimage_backup_name="btbackground_backup.gif";


if($code=="1") {
	$ptype="bttoolsetc";
	$pmsg="�⺻���μ���";

	$up_bottomtools_width=(int)$_POST["up_bottomtools_width"];
	$up_bottomtools_widthmain=(int)$_POST["up_bottomtools_widthmain"];
	$up_bottomtools_height=(int)$_POST["up_bottomtools_height"];
	$up_bottomtools_heightclose=(int)$_POST["up_bottomtools_heightclose"];

	if($up_bottomtools_width>0 || $up_bottomtools_height>0) {
		$up_bottomtools_width_type=($_POST["up_bottomtools_width_type"]=="%"?$_POST["up_bottomtools_width_type"]:"");

		$up_bottomtoolsbgtype=$_POST["up_bottomtoolsbgtype"];
		$up_bgcolor=$_POST["up_bgcolor"];
		$up_bgclear=$_POST["up_bgclear"];

		$up_bgimage = $_FILES['up_bgimage']['tmp_name'];
		$up_bgimage_type = $_FILES['up_bgimage']['type'];
		$up_bgimage_name = $_FILES['up_bgimage']['name'];
		$up_bgimage_size = $_FILES['up_bgimage']['size'];
		$up_bgimage_old = $_POST["up_bgimage_old"];

		$up_bgimagelocat=$_POST["up_bgimagelocat"];
		$up_bgimagerepet=$_POST["up_bgimagerepet"];

		if($up_bottomtoolsbgtype == "I") {
			if(strlen($up_bgimage)>0) {
				if (strlen($up_bgimage_name)>0 && strtolower(substr($up_bgimage_name,strlen($up_bgimage_name)-3,3))=="gif" && $up_bgimage_size<=153600) {
					move_uploaded_file($up_bgimage,$imagepath.$btimage_name);
					chmod($imagepath.$btimage_name,0664);
				} else {
					if (strlen($up_bgimage_name)>0) {
						$msg="�ø��� �̹����� 150KB ������ gif���ϸ� �˴ϴ�.";
					}
				}
			} else {
				if(strlen($up_bgimage_old)==0) {
					$msg="��� �̹��� ������ ���õ��� �ʾҽ��ϴ�.";
				}
			}
		} else if($up_bottomtoolsbgtype == "B" && strlen($up_bgcolor)==0){
			$msg="��� ������ ���õ��� �ʾҽ��ϴ�.";
			@unlink($imagepath.$btimage_name);
		} else {
			@unlink($imagepath.$btimage_name);
		}

		$followetc_str="";
		if ($up_bottomtools_width>0){
			$followetc_str[] = "BTWIDTH=".$up_bottomtools_width.$up_bottomtools_width_type;
		}
		if ($up_bottomtools_widthmain>0){
			$followetc_str[] = "BTWIDTHM=".$up_bottomtools_widthmain;
		}
		if ($up_bottomtools_height>0){
			$followetc_str[] = "BTHEIGHT=".$up_bottomtools_height;
		}
		if ($up_bottomtools_heightclose>0){
			$followetc_str[] = "BTHEIGHTC=".$up_bottomtools_heightclose;
		}
		if(preg_match("/^(N|B|I){1}/", $up_bottomtoolsbgtype)) {
			if($up_bottomtoolsbgtype == "B" && strlen($up_bgcolor)>0) {
				$followetc_str[]= "BTBGTYPE=".$up_bottomtoolsbgtype;
				$followetc_str[]= "BTBGCLEAR=".$up_bgclear;
				$followetc_str[]= "BTBGCOLOR=#".$up_bgcolor;
			} else if($up_bottomtoolsbgtype == "I" && strlen($msg)==0) {
				$followetc_str[]= "BTBGTYPE=".$up_bottomtoolsbgtype;
				$followetc_str[]= "BTBGIMAGELOCAT=".$up_bgimagelocat;
				$followetc_str[]= "BTBGIMAGEREPET=".$up_bgimagerepet;
			} else {
				$followetc_str[]= "BTBGTYPE=N";
			}
		}

		if(count($followetc_str)>0) {
			$body=implode("",$followetc_str);
		} else {
			$body="";
		}
	}
} else if($code=="2") {
	$ptype="bttools";
	$pmsg="�⺻����ȭ��";
} else if($code=="3") {
	$ptype="bttoolstdy";
	$pmsg="�ֱ� �� ��ǰ ����";
} else if($code=="4") {
	$ptype="bttoolswlt";
	$pmsg="WishList ����";
} else if($code=="5") {
	$ptype="bttoolsbkt";
	$pmsg="��ٱ��� ����";
} else if($code=="6") {
	$ptype="bttoolsmbr";
	$pmsg="ȸ������ ����";
}




$subject =$pmsg;

$insertKey = "bottomTools";

// ��� / ����
if ( $type=="store" OR $type=="restore" ) {
	if( $type=="store" ) {
		$orgFile = $btimage_name;
		$copyFile = $btimage_backup_name;
	}
	if( $type=="restore" ) {
		$orgFile = $btimage_backup_name;
		$copyFile = $btimage_name;
	}
	copy( $imagepath.$orgFile , $imagepath.$copyFile );
	$MSG = adminDesingBackup ( $type, $ptype, $body, $subject );
	$onload="<script>alert(\"".$MSG."\");</script>";
}












if($type=="update" && (strlen($body)>0 || $code=="1") && preg_match("/^(1|2|3|4|5|6){1}/", $code)) {

	if(strlen($body)>0) {
		$sql = "SELECT COUNT(*) as cnt FROM tbldesignnewpage WHERE type='".$ptype."' ";
		$result=mysql_query($sql,get_db_conn());
		$row=mysql_fetch_object($result);
		if($row->cnt==0) {
			$sql = "INSERT tbldesignnewpage SET ";
			$sql.= "type		= '".$ptype."', ";
			$sql.= "subject		= '".$pmsg."', ";
			$sql.= "body		= '".$body."' ";
			mysql_query($sql,get_db_conn());
		} else {
			$sql = "UPDATE tbldesignnewpage SET ";
			$sql.= "body		= '".$body."' ";
			$sql.= "WHERE type='".$ptype."' ";
			mysql_query($sql,get_db_conn());
		}
		mysql_free_result($result);
		$onload="<script type=\"text/javascript\">alert(\"".$pmsg." ������ ������ �Ϸ�Ǿ����ϴ�. ".$msg."\");</script>";
	}
} else if($type=="delete" && preg_match("/^(1|2|3|4|5|6){1}/", $code)) {
	if($code=="1") {
		$ptype="bttoolsetc";
		$pmsg="�⺻����ȭ��";
		@unlink($imagepath.$btimage_name);
	} else if($code=="2") {
		$ptype="bttools";
		$pmsg="�⺻����ȭ��";
	} else if($code=="3") {
		$ptype="bttoolstdy";
		$pmsg="�ֱ� �� ��ǰ ����";
	} else if($code=="4") {
		$ptype="bttoolswlt";
		$pmsg="WishList ����";
	} else if($code=="5") {
		$ptype="bttoolsbkt";
		$pmsg="��ٱ��� ����";
	} else if($code=="6") {
		$ptype="bttoolsmbr";
		$pmsg="ȸ������ ����";
	}

	$sql = "DELETE FROM tbldesignnewpage WHERE type='".$ptype."' ";
	mysql_query($sql,get_db_conn());
	$onload="<script type=\"text/javascript\">alert(\"".$pmsg." ������ ������ �Ϸ�Ǿ����ϴ�.\");</script>";
} else if($type=="clear" && preg_match("/^(2|3|4|5|6){1}/", $code)) {
	$body="";
	if($code=="1") {
		$ptype="bttoolsetc";
	} else if($code=="2") {
		$ptype="bttools";
	} else if($code=="3") {
		$ptype="bttoolstdy";
	} else if($code=="4") {
		$ptype="bttoolswlt";
	} else if($code=="5") {
		$ptype="bttoolsbkt";
	} else if($code=="6") {
		$ptype="bttoolsmbr";
	}
	if(strlen($ptype)>0) {
		$sql = "SELECT body FROM tbldesigndefault WHERE type='".$ptype."' ";
		$result=mysql_query($sql,get_db_conn());
		if($row=mysql_fetch_object($result)) {
			$body=$row->body;
		}
		mysql_free_result($result);
	}
}

if($type!="clear" && preg_match("/^(1|2|3|4|5|6){1}/", $code)) {
	if($code=="1") {
		$ptype="bttoolsetc";
	} else if($code=="2") {
		$ptype="bttools";
	} else if($code=="3") {
		$ptype="bttoolstdy";
	} else if($code=="4") {
		$ptype="bttoolswlt";
	} else if($code=="5") {
		$ptype="bttoolsbkt";
	} else if($code=="6") {
		$ptype="bttoolsmbr";
	}

	if(strlen($ptype)>0) {
		$body="";
		$sql = "SELECT body FROM tbldesignnewpage WHERE type='".$ptype."' ";
		$result = mysql_query($sql,get_db_conn());
		if($row=mysql_fetch_object($result)) {
			$body=$row->body;
		}
		mysql_free_result($result);
	}
}

?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script type="text/javascript">
<!--
function CheckForm(type) {


	// ���
	if(type=="store") {
		if(confirm("<?=$subject?> �������� ����Ͻðڽ��ϱ�?\n\n�������� �����̴ٸ� \"�����ϱ�\"�� ���� �Ͻ��� ����Ͻñ� �ٶ��ϴ�.\n���� ����� ����ҽ��� ��ü�մϴ�.")) {
			document.form1.type.value=type;
			document.form1.submit();
			return;
		}
	}
	// ����
	if(type=="restore") {
		if(confirm("<?=$subject?> �������� ������� �Ͻðڽ��ϱ�?\n\n���� �ϰ� �Ǹ� �ٷ� ������ ���� �˴ϴ�.")) {
			document.form1.type.value=type;
			document.form1.submit();
			return;
		}
	}





	form = document.form1;
<? if($code=="1") { ?>
	if(form.up_bottomtools_width.value.length==0 || !isDigit(form.up_bottomtools_width.value) || Number(form.up_bottomtools_width.value)<=0) {
		alert("�ϴ� ���θ޴� ��ü ����(Width)�� 0���� ū ���ڷθ� �Է��� �ּ���.");
		form.up_bottomtools_width.focus();
		return;
	}
	if(Number(form.up_bottomtools_width.value)>100 && form.up_bottomtools_width_type.value.length>0) {
		alert("�ϴ� ���θ޴� ��ü ����(Width) �ۼ�Ʈ(%)�� 100���� ���� ���ڷθ� �Է��� �ּ���.");
		form.up_bottomtools_width.focus();
		return;
	}
	if(form.up_bottomtools_height.value.length==0 || !isDigit(form.up_bottomtools_height.value) || Number(form.up_bottomtools_height.value)<=0) {
		alert("�ϴ� ���θ޴� ��ü ����(Height)-������ 0���� ū ���ڷθ� �Է��� �ּ���.");
		form.up_bottomtools_height.focus();
		return;
	}
	if(form.up_bottomtools_heightclose.value.length>0 && (!isDigit(form.up_bottomtools_heightclose.value) || Number(form.up_bottomtools_heightclose.value)<=0)) {
		alert("�ϴ� ���θ޴� ��ü ����(Height)-������ 0���� ū ���ڷθ� �Է��� �ּ���.");
		form.up_bottomtools_heightclose.focus();
		return;
	}
	if(form.up_bottomtools_widthmain.value.length==0 || !isDigit(form.up_bottomtools_widthmain.value) || Number(form.up_bottomtools_widthmain.value)<=0) {
		alert("�ϴ� ���θ޴� ��ü ����(Width)�� 0���� ū ���ڷθ� �Է��� �ּ���.");
		form.up_bottomtools_widthmain.focus();
		return;
	}
	if(form.up_bottomtoolsbgtype[1].checked && form.up_bgcolor.value.length==0) {
		alert("�ϴ� ���θ޴� ��ü ��� ������ ������ �ּ���.");
		form.up_bgcolor.focus();
		return;
	}
	if(form.up_bgimage_old.value.length==0 && form.up_bottomtoolsbgtype[2].checked && form.up_bgimage.value.length==0) {
		alert("�ϴ� ���θ޴� ��ü ��� �̹����� �Է��� �ּ���.");
		form.up_bgimage.focus();
		return;
	}
<? } ?>
	if(type=="update") {
		<? if($code!="1") { ?>
		if(form.body.value.length==0) {
			alert("������ ������ �Է��ϼ���.");
			form.body.focus();
			return;
		}
		<? } ?>
		form.type.value=type;
		form.submit();
	} else if(type=="delete") {
		if(confirm("�������� �����Ͻðڽ��ϱ�?")) {
			form.type.value=type;
			form.submit();
		}
	} else if(type=="clear") {
		alert("�⺻�� ���� �� [�����ϱ�]�� Ŭ���ϼ���. Ŭ�� �� �������� ����˴ϴ�.");
		form.type.value=type;
		form.submit();
	}

	// �̸�����
	if(type=="preview") {
		<? if($code!="1") { ?>
		if(form.body.value.length==0) {
			alert("������ ������ �Է��ϼ���.");
			form.body.focus();
			return;
		}
		<? } ?>
		form.type.value='<?=$insertKey?>';
		form.target="preview";
		form.action="designPreview.php";
		form.submit();
		form.target="";
		form.action="<?=$_SERVER[PHP_SELF]?>";
	}


}

function change_page(val) {
	document.form2.type.value="change";
	document.form2.submit();
}

function isDigit(str) {
	for(i=0; i<str.length; i++) {
		var ch = str.substr(i,1).toUpperCase();
		if((ch < "0") || (ch > "9")) {
			return false;
		}
	}
	return true;
}

function selcolor(obj){
	fontcolor = obj.value.substring(1);
	var newcolor = showModalDialog("color.php?color="+fontcolor, "oldcolor", "resizable: no; help: no; status: no; scroll: no;");
	if(newcolor){
		obj.value=newcolor;
	}
}

function ResetClear() {
	form = document.form1;
	form.up_bottomtools_width.value="100";
	form.up_bottomtools_width_type.value="%";
	form.up_bottomtools_height.value="238";
	form.up_bottomtools_heightclose.value="29";
	form.up_bottomtools_widthmain.value="900";
	form.up_bottomtoolsbgtype[2].checked=true;
	bottomtoolsbgtype_change(form,"I");
	form.up_bgimagelocat.value="A";
	form.up_bgimagerepet[0].checked=true;
	if(form.up_bgimage_old.value.length==0 && form.up_bgimage.value.length==0) {
		alert("�ϴ� ���θ޴� ��ü ��� �̹����� ���� �Է��� �ּ���.");
		form.up_bgimage.focus();
	}
}

function bottomtoolsbgtype_change(thisForm,thisValue) {
	if(document.getElementById("idx_bgcolor")) {
		bgcolor_obj = document.getElementById("idx_bgcolor");
	}
	if(document.getElementById("idx_bgimage")) {
		bgimage_obj = document.getElementById("idx_bgimage");
	}

	if(thisValue == "N") {
		thisForm.up_bgcolor.disabled=true;
		thisForm.up_bgclear[0].disabled=true;
		thisForm.up_bgclear[1].disabled=true;
		thisForm.up_bgimage.disabled=true;
		thisForm.up_bgimagelocat.disabled=true;
		thisForm.up_bgimagerepet[0].disabled=true;
		thisForm.up_bgimagerepet[1].disabled=true;
		thisForm.up_bgimagerepet[2].disabled=true;
		thisForm.up_bgimagerepet[3].disabled=true;
		bgcolor_obj.style.backgroundColor="#EAE9E4";
		bgimage_obj.style.backgroundColor="#EAE9E4";
	} else {
		if(thisValue == "B") {
			thisForm.up_bgcolor.disabled=false;
			thisForm.up_bgclear[0].disabled=false;
			thisForm.up_bgclear[1].disabled=false;
			thisForm.up_bgimage.disabled=true;
			thisForm.up_bgimagelocat.disabled=true;
			thisForm.up_bgimagerepet[0].disabled=true;
			thisForm.up_bgimagerepet[1].disabled=true;
			thisForm.up_bgimagerepet[2].disabled=true;
			thisForm.up_bgimagerepet[3].disabled=true;
			bgcolor_obj.style.backgroundColor="#0099CC";
			bgimage_obj.style.backgroundColor="#EAE9E4";
		} else {
			thisForm.up_bgcolor.disabled=true;
			thisForm.up_bgclear[0].disabled=true;
			thisForm.up_bgclear[1].disabled=true;
			thisForm.up_bgimage.disabled=false;
			thisForm.up_bgimagelocat.disabled=false;
			thisForm.up_bgimagerepet[0].disabled=false;
			thisForm.up_bgimagerepet[1].disabled=false;
			thisForm.up_bgimagerepet[2].disabled=false;
			thisForm.up_bgimagerepet[3].disabled=false;
			bgcolor_obj.style.backgroundColor="#EAE9E4";
			bgimage_obj.style.backgroundColor="#0099CC";
		}
	}
}
//-->
</SCRIPT>
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
			<? include ("menu_design.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �����ΰ��� &gt; ����������-������ ���� &gt; <span class="2depth_select">�ϴ� ���θ޴� ȭ�� �ٹ̱�</span></td>
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
					<TD><IMG SRC="images/design_eachbottomtools_title.gif" ALT=""></TD>
					</tr>
<tr>
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
					<TD width="100%" class="notice_blue"><p>�ϴ� ���θ޴� ȭ�� �������� �����Ӱ� ������ �Ͻ� �� �ֽ��ϴ�.</p></TD>
					<TD background="images/distribute_07.gif"></TD>
				</TR>
				<TR>
					<TD background="images/distribute_04.gif"></TD>
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
					<TD width="100%" class="notice_blue"><p><font color="blue"><strong>Follow �޴��� IE���� �Դϴ�.</strong></font></p></TD>
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
			<form name=form2 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=type>



			<tr>
				<td><IMG SRC="images/design_follow_stitle1.gif" border="0"></td>
			</tr>
			<tr>
				<td height="5"></td>
			</tr>
			<tr>
				<td>
					<table cellpadding="0" cellspacing="0" width="100%">
						<TR>
							<TD bgcolor="#B9B9B9" height="1"></TD>
						</TR>
						<tr>
							<td>
								<table cellpadding="0" cellspacing="0" width="100%">
								<col width="150"></col>
								<col width=""></col>
									<TR>
										<TD class="table_cell"><b><img src="images/icon_point2.gif" border="0"></b>���θ޴� ȭ�� ����</TD>
										<TD class="td_con1">					<select name=code onchange="change_page(options.value)" style="width:330" class="input">
					<option value="1" <?if($code=="1")echo"selected";?>>�⺻���μ���</option>
					<option value="2" <?if($code=="2")echo"selected";?>>����Bar ������</option>
					<option value="3" <?if($code=="3")echo"selected";?>>�ֱ� �� ��ǰ ����</option>
					<option value="4" <?if($code=="4")echo"selected";?>>WishList ����</option>
					<option value="5" <?if($code=="5")echo"selected";?>>��ٱ��� ����</option>
					<option value="6" <?if($code=="6")echo"selected";?>>ȸ������ ����</option>
					</select></TD>
									</TR>
								</table>
							</td>
						</tr>
						<TR>
							<TD bgcolor="#B9B9B9" height="1"></TD>
						</TR>
					</table>


				</td>
			</tr>
			<tr><td height="10"></td></tr>










			</form>
<?if($code=="1") { ?>
			<tr>
				<td height="30"></td>
			</tr>
<? } else { ?>
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
					<TD width="100%" class="notice_blue">1) �Ŵ����� <b>��ũ�θ�ɾ�</b>�� �����Ͽ� ������ �ϼ���.</span><br>2) [�⺻������]+[�����ϱ�], [�����ϱ�]�ϸ� �⺻���ø����� ����(���������� �ҽ� ����)�� -> ���ø� �޴����� ���ϴ� ���ø� ����.<br>3) �⺻�� �����̳� �����ϱ� ���̵� ���ø� �����ϸ� ������������ �����˴ϴ�.(���������� �ҽ��� ������)</TD>
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
				<td height="3"></td>
			</tr>
<? } ?>
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post enctype="multipart/form-data">
			<input type=hidden name=type>
			<input type=hidden name=code value="<?=$code?>">
			<tr>
				<td style="padding-top:3pt;">
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0 style="table-layout:fixed">

<?
	if($code=="1") {
		$body_exp = explode("",$body);
		if(strlen($body)<1) {
			$body="BTWIDTH=BTHEIGHT=BTBGTYPE=NBTBGCLEAR=NBTBGCOLOR=#FFFFFFBTBGIMAGELOCAT=ABTBGIMAGEREPET=A";
		}
		unset($followetcdata);
		if(strlen($body)>0) {
			$followetctemp=explode("",$body);
			$followetccnt=count($followetctemp);
			for ($followetci=0;$followetci<$followetccnt;$followetci++) {
				$followetctemp2=explode("=",$followetctemp[$followetci]);
				if(isset($followetctemp2[1])) {
					$followetcdata[$followetctemp2[0]]=$followetctemp2[1];
				} else {
					$followetcdata[$followetctemp2[0]]="";
				}
			}
		}

		if(substr($followetcdata["BTWIDTH"],-1)=="%") {
			$bottomtools_width=substr($followetcdata["BTWIDTH"],0,-1);
			$bottomtools_width_type=substr($followetcdata["BTWIDTH"],-1);
		} else {
			$bottomtools_width=$followetcdata["BTWIDTH"];
		}
		$bottomtools_widthmain=$followetcdata["BTWIDTHM"];

		$bottomtools_height=$followetcdata["BTHEIGHT"];
		$bottomtools_heightclose=$followetcdata["BTHEIGHTC"];

		$bottomtoolsbgtype = $followetcdata["BTBGTYPE"];
		$bottomtoolsbgtype_checked[$bottomtoolsbgtype] = "checked";

		if(strlen($followetcdata["BTBGCLEAR"])==0) {
			$followetcdata["BTBGCLEAR"]="N";
		}
		if(strlen($followetcdata["BTBGCOLOR"])==0) {
			$followetcdata["BTBGCOLOR"]="#FFFFFF";
		}
		if(strlen($followetcdata["BTBGIMAGELOCAT"])==0) {
			$followetcdata["BTBGIMAGELOCAT"] = "A";
		}
		if(strlen($followetcdata["BTBGIMAGEREPET"])==0) {
			$followetcdata["BTBGIMAGEREPET"] = "A";
		}

		$bgclear_checked[$followetcdata["BTBGCLEAR"]] = "checked";
		$bgcolor = substr($followetcdata["BTBGCOLOR"],1);

		$bgimagelocat_seleced[$followetcdata["BTBGIMAGELOCAT"]] = "selected";
		$bgimagerepet_checked[$followetcdata["BTBGIMAGEREPET"]] = "checked";
?>

				<tr>
					<td>
					<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
					<TR>
						<TD><IMG SRC="images/design_follow_stitle2.gif" border="0"></TD>
						<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
						<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" border="0"></TD>
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
						<TD width="100%" class="notice_blue">�ϴ� ���θ޴� ��ü ����(Width), ����(Height) ����� ���� �Ͻ� �� �ֽ��ϴ�.</TD>
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
					<td height=3></td>
				</tr>
				<tr>
					<td>
					<TABLE cellSpacing="0" cellPadding="0" width="100%" border="0">
					<TR>
						<TD bgcolor="#B9B9B9" height="1"></TD>
					</TR>
					<TR>
						<TD>
						<table cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td class="table_cell"><img src="images/design_follow_img1.gif" border="0" style="border:1px #C4C4C4 solid;"></td>
							<td class="td_con1" width="100%" style="padding:10px;"><b><span class="font_orange"><font color="#000000">�ϴ� ���θ޴� ��ü</font> ����(Width)&nbsp;<b>&nbsp;</b>: <input type=text name="up_bottomtools_width" value="<?=$bottomtools_width?>" size="6" maxlength="4" class="input"> <select name="up_bottomtools_width_type" class="input"><option value="">�ȼ�(px)</option><option value="%" <?if($bottomtools_width_type=="%")echo"selected";?>>�伾Ʈ(%)</option></select>
							<br><font color="#000000">�ϴ� ���θ޴� ��ü</font> ����(Height) - <font color="#0000FF">����</font> : <input type=text name="up_bottomtools_height" value="<?=$bottomtools_height?>" size="6" maxlength="3" class="input"> �ȼ�
							<br><font color="#000000">�ϴ� ���θ޴� ��ü</font> ����(Height) - <font color="#005500">����</font> : <input type=text name="up_bottomtools_heightclose" value="<?=$bottomtools_heightclose?>" size="6" maxlength="3" class="input"> �ȼ�<br><br>

							<font color="#0000FF">�ϴ� ���θ޴� ����</font> ����(Width) : <input type=text name="up_bottomtools_widthmain" value="<?=$bottomtools_widthmain?>" size="6" maxlength="4" class="input"> �ȼ�</span></span></b><br><br>
							<span class="space_top">* ������� 1���� ū ���ڸ� �Է°����մϴ�.<br>
							* �ۼ�Ʈ �Է½� �ִ밪�� 100 �Դϴ�.</span></td>
						</tr>
						</table>
						</TD>
					</TR>
					<TR>
						<TD bgcolor="#B9B9B9" height="1"></TD>
					</TR>
					</TABLE>
					</td>
				</tr>
				<tr>
					<td height="30"></td>
				</tr>







			<tr>
				<td><IMG SRC="images/design_follow_stitle3.gif" border="0"></td>
			</tr>
			<tr>
				<td height="5"></td>
			</tr>
			<tr>
				<td>
					<table cellpadding="0" cellspacing="0" width="100%">
						<TR>
							<TD bgcolor="#B9B9B9" height="1"></TD>
						</TR>
						<tr>
							<td>
								<table cellpadding="0" cellspacing="0" width="100%">
								<col width="150"></col>
								<col width=""></col>
									<TR>
										<TD class="table_cell"><b><img src="images/icon_point2.gif" border="0"></b>��ǰ ������ ��� ����</TD>
										<TD class="td_con1"><input type=radio name="up_bottomtoolsbgtype" value="N" id="idx_bottomtoolsbgtype1" onclick="bottomtoolsbgtype_change(this.form,this.value);" <?=$bottomtoolsbgtype_checked["N"]?>><label style="cursor:hand;" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_bottomtoolsbgtype1">��� ��� ����</label>
							<input type=radio name="up_bottomtoolsbgtype" value="B" id="idx_bottomtoolsbgtype2" onclick="bottomtoolsbgtype_change(this.form,this.value);" <?=$bottomtoolsbgtype_checked["B"]?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_bottomtoolsbgtype2">��� �������� ����</label>
							<input type=radio name="up_bottomtoolsbgtype" value="I" id="idx_bottomtoolsbgtype3" onclick="bottomtoolsbgtype_change(this.form,this.value);" <?=$bottomtoolsbgtype_checked["I"]?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_bottomtoolsbgtype3">��� �̹����� ����</label>
										</TD>
									</TR>
								</table>
							</td>
						</tr>
						<TR>
							<TD bgcolor="#B9B9B9" height="1"></TD>
						</TR>
					</table>


				</td>
			</tr>
			<tr><td height="10"></td></tr>




				<tr>
					<td>
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td id="idx_bgcolor" style="padding:6pt;background-Color:<?=($bottomtoolsbgtype=="B"?"#0099CC":"#EAE9E4")?>;">
						<table cellpadding="0" cellspacing="0" width="100%" bgcolor="#FFFFFF">
						<tr>
							<td>
							<table cellpadding="0" cellspacing="0" width="100%">
							<TR>
								<TD height="30" background="images/blueline_bg.gif" align="center"><b><font color="#333333">��� �������� ����</font></b></TD>
							</TR>
							<TR>
								<TD bgcolor="#EDEDED"></TD>
							</TR>
							<tr>
								<td>
								<table cellpadding="0" cellspacing="0" width="100%">
								<col width="150"></col>
								<col width=""></col>
								<TR>
									<TD class="table_cell"><b><img src="images/icon_point2.gif" border="0"></b>���� ����</TD>
									<TD class="td_con1" valign="bottom">
									<table cellpadding="0" cellspacing="0">
									<tr>
										<td style="padding-left:5px;">#</td>
										<td style="padding-left:3px;"><input type=text name="up_bgcolor" value="<?=$bgcolor?>" size="8" maxlength="6" class="input" <?=($bottomtoolsbgtype=="N" || $bottomtoolsbgtype=="I"?"disabled":"")?>></td>
										<td style="padding-left:5px;"><font color="<?=$bgcolor?>"><span style="font-size:20pt;">��</span></font></td>
										<td style="padding-left:5px;"><a href="javascript:selcolor(document.form1.up_bgcolor)"><IMG src="images/icon_color.gif" border="0" align="absmiddle"></a></td>
									</tr>
									</table>
									</td>
								</TR>
								<TR>
									<TD colspan="2" bgcolor="#EDEDED"></TD>
								</TR>
								<TR>
									<TD class="table_cell"><b><img src="images/icon_point2.gif" border="0"></b>����� ��뿩��</TD>
									<TD class="td_con1" valign="bottom"><input type=radio name="up_bgclear" value="N" id="idx_bgclear1" <?=$bgclear_checked["N"]?> <?=($bottomtoolsbgtype=="N" || $bottomtoolsbgtype=="I"?"disabled":"")?>><label style="cursor:hand;" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_bgclear1">����� ������</label>&nbsp;&nbsp;&nbsp;&nbsp;
									<input type=radio name="up_bgclear" value="Y" id="idx_bgclear2" <?=$bgclear_checked["Y"]?> <?=($bottomtoolsbgtype=="N" || $bottomtoolsbgtype=="I"?"disabled":"")?>><label style="cursor:hand;" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_bgclear2">����� �����</label></td>
								</TR>
								</table>
								</td>
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
				<tr>
					<td height="5"></td>
				</tr>
				<tr>
					<td>
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td id="idx_bgimage" style="padding:6pt;background-Color:<?=($bottomtoolsbgtype=="I"?"#0099CC":"#EAE9E4")?>;">
						<table cellpadding="0" cellspacing="0" width="100%" bgcolor="#FFFFFF">
						<tr>
							<td>
							<table cellpadding="0" cellspacing="0" width="100%">
							<TR>
								<TD height="30" background="images/blueline_bg.gif" align="center"><b><font color="#333333">��� �̹����� ����</font></b></TD>
							</TR>
							<TR>
								<TD bgcolor="#EDEDED"></TD>
							</TR>
							<tr>
								<td>
								<table cellpadding="0" cellspacing="0" width="100%">
								<col width="150"></col>
								<col width=""></col>
								<input type=hidden name="up_bgimage_old" value="<?=(file_exists($imagepath.$btimage_name)?"1":"")?>">
								<TR>
									<TD class="table_cell"><b><img src="images/icon_point2.gif" border="0"></b>��� �̹���</TD>
									<TD class="td_con1" style="padding-left:8px;"><input type=file name="up_bgimage" style="WIDTH: 98%" class="input" <?=($bottomtoolsbgtype=="N" || $bottomtoolsbgtype=="B"?"disabled":"")?>><br>
									* ��� ������ �̹����� ���� Ȯ���� <span class="font_orange">GIF(gif)</span> �� �����ϸ� �뷮�� <span class="font_orange">�ִ� 150KB</span> ���� �����մϴ�.
									<? if(file_exists($imagepath.$btimage_name)) { ?>
									<table cellpadding="0" cellspacing="0" width="98%" border="0" style="table-layout:fixed">
									<tr>
										<td height="5"></td>
									</tr>
									<tr>
										<td height="100" style="border:#00A0D5 1px solid;"><img src="<?=$imagepath.$btimage_name?>" border="0"></td>
									</tr>
									<tr>
										<td height="5"></td>
									</tr>
									</table>
									<? } ?>
									</TD>
								</TR>
								<TR>
									<TD colspan="2" bgcolor="#EDEDED"></TD>
								</TR>
								<TR>
									<TD class="table_cell"><b><img src="images/icon_point2.gif" border="0"></b>��� ��� ���� ��ġ</TD>
									<TD class="td_con1" style="padding-left:8px;"><select name="up_bgimagelocat" class="select" <?=($bottomtoolsbgtype=="N" || $bottomtoolsbgtype=="B"?"disabled":"")?>>
										<option value="A" <?=$bgimagelocat_seleced["A"]?>>���� - ���� </option>
										<option value="B" <?=$bgimagelocat_seleced["B"]?>>���� - �߾�</option>
										<option value="C" <?=$bgimagelocat_seleced["C"]?>>���� - ����</option>
										<option value="D" <?=$bgimagelocat_seleced["D"]?>>��� - ����</option>
										<option value="E" <?=$bgimagelocat_seleced["E"]?>>��� - �߾�</option>
										<option value="F" <?=$bgimagelocat_seleced["F"]?>>��� - ����</option>
										<option value="G" <?=$bgimagelocat_seleced["G"]?>>�ǾƷ� - ����</option>
										<option value="H" <?=$bgimagelocat_seleced["H"]?>>�ǾƷ� - �߾�</option>
										<option value="I" <?=$bgimagelocat_seleced["I"]?>>�ǾƷ� - ����</option>
										</select></TD>
								</TR>
								<TR>
									<TD colspan="2" bgcolor="#EDEDED"></TD>
								</TR>
								<TR>
									<TD class="table_cell"><b><img src="images/icon_point2.gif" border="0"></b>��� �ݺ� ����</TD>
									<TD class="td_con1"><input type=radio name="up_bgimagerepet" value="A" id="idx_bgimagerepet1" <?=$bgimagerepet_checked["A"]?> <?=($bottomtoolsbgtype=="N" || $bottomtoolsbgtype=="B"?"disabled":"")?>><label style="cursor:hand;" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_bgimagerepet1">��ü�ݺ�</label>&nbsp;&nbsp;&nbsp;&nbsp;
									<input type=radio name="up_bgimagerepet" value="B" id="idx_bgimagerepet2" <?=$bgimagerepet_checked["B"]?> <?=($bottomtoolsbgtype=="N" || $bottomtoolsbgtype=="B"?"disabled":"")?>><label style="cursor:hand;" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_bgimagerepet2">����ݺ�</label>&nbsp;&nbsp;&nbsp;&nbsp;
									<input type=radio name="up_bgimagerepet" value="C" id="idx_bgimagerepet3" <?=$bgimagerepet_checked["C"]?> <?=($bottomtoolsbgtype=="N" || $bottomtoolsbgtype=="B"?"disabled":"")?>><label style="cursor:hand;" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_bgimagerepet3">�����ݺ�</label>&nbsp;&nbsp;&nbsp;&nbsp;
									<input type=radio name="up_bgimagerepet" value="D" id="idx_bgimagerepet4" <?=$bgimagerepet_checked["D"]?> <?=($bottomtoolsbgtype=="N" || $bottomtoolsbgtype=="B"?"disabled":"")?>><label style="cursor:hand;" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_bgimagerepet4">�ݺ�����</label>
									</TD>
								</TR>
								</table>
								</td>
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
				<tr>
					<td height="30"></td>
				</tr>
<? } else { ?>
				<TR>
					<TD><TEXTAREA style="WIDTH: 100%; HEIGHT: 300px" name=body class="textarea"><?=htmlspecialchars($body)?></TEXTAREA></TD>
				</TR>
<?
}
?>
				</TABLE>
				</td>
			</tr>
			<tr><td height=10></td></tr>
			<tr>
				<td align="center"><a href="javascript:CheckForm('update');"><img src="images/botteon_save.gif" border="0"></a><?=($code=="1"?"&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"javascript:ResetClear('clear');\"><img src=\"images/botteon_bok.gif\" border=\"0\" hspace=\"2\"></a>":"&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"javascript:CheckForm('clear');\"><img src=\"images/botteon_bok.gif\" border=\"0\" hspace=\"2\"></a>")?>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:CheckForm('delete');"><img src="images/botteon_del.gif"border="0" hspace="0"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:CheckForm('preview');"><img src="images/botteon_prev.gif" width="113" height="38" border="0" hspace="2"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:CheckForm('store');"><img src="images/botteon_store.gif" border="0" hspace="2" alt="����ϱ�"></a>&nbsp;&nbsp;&nbsp;<a href="javascript:CheckForm('restore');"><img src="images/botteon_restore.gif" border="0" hspace="2" alt="��������ϱ�"></a></td>
			</tr>
			</form>
<? if($code=="1") { ?>
			<tr><td height=20></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/manual_top1.gif" WIDTH=15 HEIGHT=45 ALT=""></TD>
					<TD><IMG SRC="images/manual_title.gif" WIDTH=113 HEIGHT=45 ALT=""></TD>
					<TD width="100%" background="images/manual_bg.gif"></TD>
					<TD background="images/manual_bg.gif"></TD>
					<TD><IMG SRC="images/manual_top2.gif" WIDTH=18 HEIGHT=45 ALT=""></TD>
				</TR>
				<TR>
					<TD background="images/manual_left1.gif"></TD>
					<TD COLSPAN=3 width="100%" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;" class="menual_bg">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><span class="font_dotline">�ϴ� �����޴� ����</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">
						- �ϴ� �����޴� ��ü ����, ����(����,����) �� ���� ���� ����� ������ �� �ֽ��ϴ�.<br>
						- �ϴ� �����޴� ��� ������ �����ڵ� �� �̹��� �����Ͽ� ����� �� �ֽ��ϴ�.<br>
						<b>&nbsp;&nbsp;</b>�̹��� �뷮�� �ִ� 150KByte���� �����ϸ�, Ȯ���ڴ� gif�� �����մϴ�.<br>
						</td>
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
<? } else { ?>
			<tr><td height=20></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/manual_top1.gif" WIDTH=15 HEIGHT=45 ALT=""></TD>
					<TD><IMG SRC="images/manual_title.gif" WIDTH=113 HEIGHT=45 ALT=""></TD>
					<TD width="100%" background="images/manual_bg.gif"></TD>
					<TD background="images/manual_bg.gif"></TD>
					<TD><IMG SRC="images/manual_top2.gif" WIDTH=18 HEIGHT=45 ALT=""></TD>
				</TR>
				<TR>
					<TD background="images/manual_left1.gif"></TD>
					<TD COLSPAN=3 width="100%" valign="top"  style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;"  class="menual_bg">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><p class="LIPoint"><B><span class="font_orange">�ϴ� ���θ޴� ȭ�� ��ũ�θ�ɾ�</span></B>(�ش� ��ũ�θ�ɾ�� �ٸ� ������ ������ �۾��� ����� �Ұ�����)</p></td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><p>&nbsp;</p></td>
						<td >
						<table border=0 cellpadding=0 cellspacing=0 width=100%>
						<col width=150></col>
						<col width=></col>

						<?if($code=="2"){?>

						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[TODAYCHANGE_���� �� ��Ʈ_���� �� ��Ʈ]</td>
							<td class=td_con1 style="padding-left:5;word-break:break-all;">
							���ι� '�ֱ� �� ��ǰ' ��Ʈ
								<br><br><img width=10 height=0>
								<FONT class=font_orange>��??|???????|?|? : <b>���� �� ��Ʈ</b></FONT>
								<br><img width=23 height=0>
								<FONT class=font_orange>?? : ���� ũ��(��:12px)</FONT> - <FONT COLOR="red">���� ���ܽ� �⺻ px�� ���� �˴ϴ�.</FONT>
								<br><img width=23 height=0>
								<FONT class=font_orange>??????? : ���� ����</FONT> - <FONT COLOR="red">"#"����</FONT>
								<br><img width=23 height=0>
								<FONT class=font_orange>? : ���� �β���(Y/N)</FONT>
								<br><img width=23 height=0>
								<FONT class=font_orange>? : ���� �ؼ�(Y/N)</FONT><br>
								<br><img width=10 height=0>
								<FONT class=font_orange>��??|???????|?|? : <b>���� �� ��Ʈ</b></FONT>
								<br><img width=23 height=0>
								<FONT class=font_orange>?? : ���� ũ��(��:12px)</FONT> - <FONT COLOR="red">���� ���ܽ� �⺻ px�� ���� �˴ϴ�.</FONT>
								<br><img width=23 height=0>
								<FONT class=font_orange>??????? : ���� ����</FONT> - <FONT COLOR="red">"#"����</FONT>
								<br><img width=23 height=0>
								<FONT class=font_orange>? : ���� �β���(Y/N)</FONT>
								<br><img width=23 height=0>
								<FONT class=font_orange>? : ���� �ؼ�(Y/N)</FONT><br><br>

								<FONT class=font_blue>��) [TODAYCHANGE_12px|0000000|N|N_12px|FF0000|N|N]</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[WISHLISTCHANGE_���� �� ��Ʈ_���� �� ��Ʈ]</td>
							<td class=td_con1 style="padding-left:5;word-break:break-all;">
							���ι� 'Wishlist' ��Ʈ
								<br><br><img width=10 height=0>
								<FONT class=font_orange>��??|???????|?|? : <b>���� �� ��Ʈ</b></FONT>
								<br><img width=23 height=0>
								<FONT class=font_orange>?? : ���� ũ��(��:12px)</FONT> - <FONT COLOR="red">���� ���ܽ� �⺻ px�� ���� �˴ϴ�.</FONT>
								<br><img width=23 height=0>
								<FONT class=font_orange>??????? : ���� ����</FONT> - <FONT COLOR="red">"#"����</FONT>
								<br><img width=23 height=0>
								<FONT class=font_orange>? : ���� �β���(Y/N)</FONT>
								<br><img width=23 height=0>
								<FONT class=font_orange>? : ���� �ؼ�(Y/N)</FONT><br>
								<br><img width=10 height=0>
								<FONT class=font_orange>��??|???????|?|? : <b>���� �� ��Ʈ</b></FONT>
								<br><img width=23 height=0>
								<FONT class=font_orange>?? : ���� ũ��(��:12px)</FONT> - <FONT COLOR="red">���� ���ܽ� �⺻ px�� ���� �˴ϴ�.</FONT>
								<br><img width=23 height=0>
								<FONT class=font_orange>??????? : ���� ����</FONT> - <FONT COLOR="red">"#"����</FONT>
								<br><img width=23 height=0>
								<FONT class=font_orange>? : ���� �β���(Y/N)</FONT>
								<br><img width=23 height=0>
								<FONT class=font_orange>? : ���� �ؼ�(Y/N)</FONT><br><br>

								<FONT class=font_blue>��) [WISHLISTCHANGE_12px|0000000|N|N_12px|FF0000|N|N]</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BASKETCHANGE_���� �� ��Ʈ_���� �� ��Ʈ]</td>
							<td class=td_con1 style="padding-left:5;word-break:break-all;">
							���ι� '��ٱ���' ��Ʈ
								<br><br><img width=10 height=0>
								<FONT class=font_orange>��??|???????|?|? : <b>���� �� ��Ʈ</b></FONT>
								<br><img width=23 height=0>
								<FONT class=font_orange>?? : ���� ũ��(��:12px)</FONT> - <FONT COLOR="red">���� ���ܽ� �⺻ px�� ���� �˴ϴ�.</FONT>
								<br><img width=23 height=0>
								<FONT class=font_orange>??????? : ���� ����</FONT> - <FONT COLOR="red">"#"����</FONT>
								<br><img width=23 height=0>
								<FONT class=font_orange>? : ���� �β���(Y/N)</FONT>
								<br><img width=23 height=0>
								<FONT class=font_orange>? : ���� �ؼ�(Y/N)</FONT><br>
								<br><img width=10 height=0>
								<FONT class=font_orange>��??|???????|?|? : <b>���� �� ��Ʈ</b></FONT>
								<br><img width=23 height=0>
								<FONT class=font_orange>?? : ���� ũ��(��:12px)</FONT> - <FONT COLOR="red">���� ���ܽ� �⺻ px�� ���� �˴ϴ�.</FONT>
								<br><img width=23 height=0>
								<FONT class=font_orange>??????? : ���� ����</FONT> - <FONT COLOR="red">"#"����</FONT>
								<br><img width=23 height=0>
								<FONT class=font_orange>? : ���� �β���(Y/N)</FONT>
								<br><img width=23 height=0>
								<FONT class=font_orange>? : ���� �ؼ�(Y/N)</FONT><br><br>

								<FONT class=font_blue>��) [BASKETCHANGE_12px|0000000|N|N_12px|FF0000|N|N]</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[MEMBERCHANGE_���� �� ��Ʈ_���� �� ��Ʈ]</td>
							<td class=td_con1 style="padding-left:5;word-break:break-all;">
							���ι� 'ȸ������' ��Ʈ
								<br><br><img width=10 height=0>
								<FONT class=font_orange>��??|???????|?|? : <b>���� �� ��Ʈ</b></FONT>
								<br><img width=23 height=0>
								<FONT class=font_orange>?? : ���� ũ��(��:12px)</FONT> - <FONT COLOR="red">���� ���ܽ� �⺻ px�� ���� �˴ϴ�.</FONT>
								<br><img width=23 height=0>
								<FONT class=font_orange>??????? : ���� ����</FONT> - <FONT COLOR="red">"#"����</FONT>
								<br><img width=23 height=0>
								<FONT class=font_orange>? : ���� �β���(Y/N)</FONT>
								<br><img width=23 height=0>
								<FONT class=font_orange>? : ���� �ؼ�(Y/N)</FONT><br>
								<br><img width=10 height=0>
								<FONT class=font_orange>��??|???????|?|? : <b>���� �� ��Ʈ</b></FONT>
								<br><img width=23 height=0>
								<FONT class=font_orange>?? : ���� ũ��(��:12px)</FONT> - <FONT COLOR="red">���� ���ܽ� �⺻ px�� ���� �˴ϴ�.</FONT>
								<br><img width=23 height=0>
								<FONT class=font_orange>??????? : ���� ����</FONT> - <FONT COLOR="red">"#"����</FONT>
								<br><img width=23 height=0>
								<FONT class=font_orange>? : ���� �β���(Y/N)</FONT>
								<br><img width=23 height=0>
								<FONT class=font_orange>? : ���� �ؼ�(Y/N)</FONT><br><br>

								<FONT class=font_blue>��) [MEMBERCHANGE_12px|0000000|N|N_12px|FF0000|N|N]</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[TODAYCNT]</td>
							<td class=td_con1 style="padding-left:5;word-break:break-all;">
							���ι� '�ֱ� �� ��ǰ' ���� ���
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[WISHLISTCNT]</td>
							<td class=td_con1 style="padding-left:5;word-break:break-all;">
							���ι� 'Wishlist' ��ǰ ���� ���
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BASKETCNT]</td>
							<td class=td_con1 style="padding-left:5;word-break:break-all;">
							���ι� '��ٱ��� ��ǰ' ���� ���
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[OPENCLOSEIMG_�����̹������_�ݱ��̹������]</td>
							<td class=td_con1 style="padding-left:5;word-break:break-all;">
							���ι� ����/�ݱ� �̹��� ����<br>
							<FONT class=font_blue>��) &lt;img src=[OPENCLOSEIMG_../images/common/btopen.gif_../images/common/btclose.gif] border="0"></FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[OPENCLOSECHANGE]</td>
							<td class=td_con1 style="padding-left:5;word-break:break-all;">
							���ι� ����/�ݱ� <FONT class=font_blue>(��:&lt;a href=[OPENCLOSECHANGE]>���ι� ����/�ݱ� �̹���&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>

						<?} else if($code=="3") {?>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell width=180 align=right style="padding-right:15" nowrap>[IFTODAY]<br>[IFELSETODAY]<br>[IFENDTODAY]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							�ֱ� �� ��ǰ�� ���� ���� ���� ���
							<pre style="line-height:15px">
<FONT class=font_blue>   <B>[IFTODAY]</B>
      �ֱ� �� ��ǰ�� <FONT COLOR="red"><B>����</B></FONT> ����� ����
   <B>[IFELSETODAY]</B>
      �ֱ� �� ��ǰ�� <FONT COLOR="red"><B>����</B></FONT> ����� ����
   <B>[IFENDTODAY]</B></font></pre>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[TODAYPROLIST_?????]</td>
							<td class=td_con1 style="padding-left:5;word-break:break-all;">
							�ֱ� �� ��ǰ ���
								<br><img width=10 height=0>
								<FONT class=font_orange>? : ������ ��¿��� (Y/N)</FONT>
								<br><img width=10 height=0>
								<FONT class=font_orange>? : ��ǰ ������ ��¿��� (Y/N)</FONT>
								<br><img width=10 height=0>
								<FONT class=font_orange>? : ��ǰ �����ڵ� ��¿��� (Y/N)</FONT>
								<br><img width=10 height=0>
								<FONT class=font_orange>? : ��ǰ ������ ��¿��� (Y/N)</FONT>
								<br><img width=10 height=0>
								<FONT class=font_orange>? : ��ǰ ���߰��� ��¿��� (Y/N)</FONT>
								<br><FONT class=font_blue>��) [TODAYPROLIST_YYYYY]</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right bgcolor=#E9A74E style="padding-right:15">��ǰ ���� ��Ÿ�� ����</td>
							<td class=td_con1 bgcolor=#FEEEE2 style="padding-left:5;">
							<img width=10 height=0 style="visibility:hidden;">
							<FONT class=font_orange>#xtprname - ��ǰ�� ���� ��Ÿ�� ����(��Ʈ ������ �� �÷�)</FONT>
							<br><img width=100 height=0 style="visibility:hidden;">
							<FONT class=font_blue>��) #xtprname { color:#666666;font-size:12px; }</FONT>
							<br><img width=0 height=7 style="visibility:hidden;"><br><img width=10 height=0 style="visibility:hidden;">
							<FONT class=font_orange>#xtprsellprice - ��ǰ �ǸŰ��� TD ��Ÿ�� ���� (��Ʈ �� �� ��Ÿ��)</FONT>
							<br><img width=100 height=0 style="visibility:hidden;">
							<FONT class=font_blue>��) #xtprsellprice { color:#666666;font-size:12px; }</FONT>
							<br><img width=0 height=7 style="visibility:hidden;"><br><img width=10 height=0 style="visibility:hidden;">
							<FONT class=font_orange>#xtprreserve - ��ǰ ������ TD ��Ÿ�� ���� (��Ʈ �� �� ��Ÿ��)</FONT>
							<br><img width=100 height=0 style="visibility:hidden;">
							<FONT class=font_blue>��) #xtprreserve { color:#666666;font-size:12px; }</FONT>
							<br><img width=0 height=7 style="visibility:hidden;"><br><img width=10 height=0 style="visibility:hidden;">
							<FONT class=font_orange>#xtprconsumerprice - ��ǰ ���߰��� TD ��Ÿ�� ���� (��Ʈ �� �� ��Ÿ��)</FONT>
							<br><img width=100 height=0 style="visibility:hidden;">
							<FONT class=font_blue>��) #xtprconsumerprice { color:#666666;font-size:12px; }</FONT>
							<br><img width=0 height=7 style="visibility:hidden;"><br><img width=10 height=0 style="visibility:hidden;">
							<FONT class=font_orange>#xtprimage - ��ǰ �̹��� ��Ÿ�� ����</FONT>
							<br><img width=100 height=0 style="visibility:hidden;">
							<FONT class=font_blue>��) #xtprimage { border:1px #CCCCCC solid; }</FONT>
				<pre style="line-height:15px">
<B>[��� ��]</B> - ���� ������ �Ʒ��� ���� �����Ͻø� �˴ϴ�.

<FONT class=font_blue>&lt;style type="text/css">
  #xtprname {color:#666666;font-size:12px;}
  #xtprsellprice {color:#666666;font-size:12px;}
  #xtprreserve {color:#666666;font-size:12px;}
  #xtprconsumerprice {color:#666666;font-size:12px;}
  #xtprimage {border:1px #CCCCCC solid;}
&lt;/style></FONT></pre>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ALLSELECT]</td>
							<td class=td_con1 style="padding-left:5;word-break:break-all;">
							��ǰ ��ü���� <FONT class=font_blue>(��:&lt;a href=[ALLSELECT]>��ü����&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ALLSELECTOUT]</td>
							<td class=td_con1 style="padding-left:5;word-break:break-all;">
							��ǰ ��ü���� <FONT class=font_blue>(��:&lt;a href=[ALLSELECTOUT]>��ü����&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ALLOUT]</td>
							<td class=td_con1 style="padding-left:5;word-break:break-all;">
							��ǰ �ϰ����� <FONT class=font_blue>(��:&lt;a href=[ALLOUT]>�ϰ�����&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BASKETLINK]</td>
							<td class=td_con1 style="padding-left:5;word-break:break-all;">
							��ٱ��� �ٷΰ��� <FONT class=font_blue>(��:&lt;a href=[BASKETLINK]>��ٱ��� �ٷΰ���&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<?} else if($code=="4") {?>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell width=180 align=right style="padding-right:15" nowrap>[IFMEMBER]<br>[IFELSEMEMBER]<br>[IFENDMEMBER]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							ȸ�� �α���/�α׾ƿ� ������ ���
							<pre style="line-height:15px">
<FONT class=font_blue>   <B>[IFMEMBER]</B>
      ȸ�� <FONT COLOR="red"><B>�α���</B></FONT> ������ ����� ����
   <B>[IFELSEMEMBER]</B>
      ȸ�� <FONT COLOR="red"><B>�α׾ƿ�</B></FONT> ������ ����� ����
   <B>[IFENDMEMBER]</B></font></pre>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell width=180 align=right style="padding-right:15" nowrap>[IFWISHLIST]<br>[IFELSEWISHLIST]<br>[IFENDWISHLIST]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							Wishlist ��ǰ�� ���� ���� ���� ���
							<pre style="line-height:15px">
<FONT class=font_blue>   <B>[IFWISHLIST]</B>
      Wishlist ��ǰ�� <FONT COLOR="red"><B>����</B></FONT> ����� ����
   <B>[IFELSEWISHLIST]</B>
      Wishlist ��ǰ�� <FONT COLOR="red"><B>����</B></FONT> ����� ����
   <B>[IFENDWISHLIST]</B></font></pre>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[WISHLISTPROLIST_?????]</td>
							<td class=td_con1 style="padding-left:5;word-break:break-all;">
							Wishlist ��ǰ ���
								<br><img width=10 height=0>
								<FONT class=font_orange>? : ������ ��¿��� (Y/N)</FONT>
								<br><img width=10 height=0>
								<FONT class=font_orange>? : ��ǰ ������ ��¿��� (Y/N)</FONT>
								<br><img width=10 height=0>
								<FONT class=font_orange>? : ��ǰ �����ڵ� ��¿��� (Y/N)</FONT>
								<br><img width=10 height=0>
								<FONT class=font_orange>? : ��ǰ ������ ��¿��� (Y/N)</FONT>
								<br><img width=10 height=0>
								<FONT class=font_orange>? : �ɼǻ�ǰ ������ ��¿��� (Y/N)</FONT>
								<br><FONT class=font_blue>��) [WISHLISTPROLIST_YYYYY]</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right bgcolor=#E9A74E style="padding-right:15">��ǰ ���� ��Ÿ�� ����</td>
							<td class=td_con1 bgcolor=#FEEEE2 style="padding-left:5;word-break:break-all;">
							<img width=10 height=0 style="visibility:hidden;">
							<FONT class=font_orange>#xwprname - ��ǰ�� ���� ��Ÿ�� ����(��Ʈ ������ �� �÷�)</FONT>
							<br><img width=100 height=0 style="visibility:hidden;">
							<FONT class=font_blue>��) #xwprname { color:#666666;font-size:12px; }</FONT>
							<br><img width=0 height=7 style="visibility:hidden;"><br><img width=10 height=0 style="visibility:hidden;">
							<FONT class=font_orange>#xwprsellprice - ��ǰ �ǸŰ��� TD ��Ÿ�� ���� (��Ʈ �� �� ��Ÿ��)</FONT>
							<br><img width=100 height=0 style="visibility:hidden;">
							<FONT class=font_blue>��) #xwprsellprice { color:#666666;font-size:12px; }</FONT>
							<br><img width=0 height=7 style="visibility:hidden;"><br><img width=10 height=0 style="visibility:hidden;">
							<FONT class=font_orange>#xwprreserve - ��ǰ ������ TD ��Ÿ�� ���� (��Ʈ �� �� ��Ÿ��)</FONT>
							<br><img width=100 height=0 style="visibility:hidden;">
							<FONT class=font_blue>��) #xwprreserve { color:#666666;font-size:12px; }</FONT>
							<br><img width=0 height=7 style="visibility:hidden;"><br><img width=10 height=0 style="visibility:hidden;">
							<FONT class=font_orange>#xwprconsumerprice - ��ǰ ���߰��� TD ��Ÿ�� ���� (��Ʈ �� �� ��Ÿ��)</FONT>
							<br><img width=100 height=0 style="visibility:hidden;">
							<FONT class=font_blue>��) #xwprconsumerprice { color:#666666;font-size:12px; }</FONT>
							<br><img width=0 height=7 style="visibility:hidden;"><br><img width=10 height=0 style="visibility:hidden;">
							<FONT class=font_orange>#xwprimage - ��ǰ �̹��� ��Ÿ�� ����</FONT>
							<br><img width=100 height=0 style="visibility:hidden;">
							<FONT class=font_blue>��) #xwprimage { border:1px #CCCCCC solid; }</FONT>
				<pre style="line-height:15px">
<B>[��� ��]</B> - ���� ������ �Ʒ��� ���� �����Ͻø� �˴ϴ�.

<FONT class=font_blue>&lt;style type="text/css">
  #xwprname {color:#666666;font-size:12px;}
  #xwprsellprice {color:#666666;font-size:12px;}
  #xwprreserve {color:#666666;font-size:12px;}
  #xwprconsumerprice {color:#666666;font-size:12px;}
  #xwprimage {border:1px #CCCCCC solid;}
&lt;/style></FONT></pre>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ALLSELECT]</td>
							<td class=td_con1 style="padding-left:5;word-break:break-all;">
							��ǰ ��ü���� <FONT class=font_blue>(��:&lt;a href=[ALLSELECT]>��ü����&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ALLSELECTOUT]</td>
							<td class=td_con1 style="padding-left:5;word-break:break-all;">
							��ǰ ��ü���� <FONT class=font_blue>(��:&lt;a href=[ALLSELECTOUT]>��ü����&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ALLOUT]</td>
							<td class=td_con1 style="padding-left:5;word-break:break-all;">
							��ǰ �ϰ����� <FONT class=font_blue>(��:&lt;a href=[ALLOUT]>�ϰ�����&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[WISHLISTLINK]</td>
							<td class=td_con1 style="padding-left:5;word-break:break-all;">
							Wishlist �ٷΰ��� <FONT class=font_blue>(��:&lt;a href=[WISHLISTLINK]>Wishlist �ٷΰ���&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<?} else if($code=="5") {?>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell width=180 align=right style="padding-right:15" nowrap>[IFBASKET]<br>[IFELSEBASKET]<br>[IFENDBASKET]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							��ٱ��� ��ǰ�� ���� ���� ���� ���
							<pre style="line-height:15px">
<FONT class=font_blue>   <B>[IFBASKET]</B>
      ��ٱ��� ��ǰ�� <FONT COLOR="red"><B>����</B></FONT> ����� ����
   <B>[IFELSEBASKET]</B>
      ��ٱ��� ��ǰ�� <FONT COLOR="red"><B>����</B></FONT> ����� ����
   <B>[IFENDBASKET]</B></font></pre>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BASKETPROLIST_??????]</td>
							<td class=td_con1 style="padding-left:5;word-break:break-all;">
							��ٱ��� ��ǰ ���
								<br><img width=10 height=0>
								<FONT class=font_orange>? : ������ ��¿��� (Y/N)</FONT>
								<br><img width=10 height=0>
								<FONT class=font_orange>? : ��ǰ ������ ��¿��� (Y/N)</FONT>
								<br><img width=10 height=0>
								<FONT class=font_orange>? : ��ǰ �����ڵ� ��¿��� (Y/N)</FONT>
								<br><img width=10 height=0>
								<FONT class=font_orange>? : ��ǰ ������ ��¿��� (Y/N)</FONT>
								<br><img width=10 height=0>
								<FONT class=font_orange>? : ��ǰ ���� ��¿��� (Y/N)</FONT>
								<br><img width=10 height=0>
								<FONT class=font_orange>? : (�ɼ�,��Ű��,�ڵ�/����)��ǰ ������ ��¿��� (Y/N)</FONT>
								<br><FONT class=font_blue>��) [BASKETPROLIST_YYYYYY]</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right bgcolor=#E9A74E style="padding-right:15">��ǰ ���� ��Ÿ�� ����</td>
							<td class=td_con1 bgcolor=#FEEEE2 style="padding-left:5;word-break:break-all;">
							<img width=10 height=0 style="visibility:hidden;">
							<FONT class=font_orange>#xbprname - ��ǰ�� ���� ��Ÿ�� ����(��Ʈ ������ �� �÷�)</FONT>
							<br><img width=100 height=0 style="visibility:hidden;">
							<FONT class=font_blue>��) #xbprname { color:#666666;font-size:12px; }</FONT>
							<br><img width=0 height=7 style="visibility:hidden;"><br><img width=10 height=0 style="visibility:hidden;">
							<FONT class=font_orange>#xbprsellprice - ��ǰ �ǸŰ��� TD ��Ÿ�� ���� (��Ʈ �� �� ��Ÿ��)</FONT>
							<br><img width=100 height=0 style="visibility:hidden;">
							<FONT class=font_blue>��) #xbprsellprice { color:#666666;font-size:12px; }</FONT>
							<br><img width=0 height=7 style="visibility:hidden;"><br><img width=10 height=0 style="visibility:hidden;">
							<FONT class=font_orange>#xbprreserve - ��ǰ ������ TD ��Ÿ�� ���� (��Ʈ �� �� ��Ÿ��)</FONT>
							<br><img width=100 height=0 style="visibility:hidden;">
							<FONT class=font_blue>��) #xbprreserve { color:#666666;font-size:12px; }</FONT>
							<br><img width=0 height=7 style="visibility:hidden;"><br><img width=10 height=0 style="visibility:hidden;">
							<FONT class=font_orange>#xbprquantity - ��ǰ ���Լ��� TD ��Ÿ�� ���� (��Ʈ �� �� ��Ÿ��)</FONT>
							<br><img width=100 height=0 style="visibility:hidden;">
							<FONT class=font_blue>��) #xbprquantity { color:#666666;font-size:12px; }</FONT>
							<br><img width=0 height=7 style="visibility:hidden;"><br><img width=10 height=0 style="visibility:hidden;">
							<FONT class=font_orange>#xbprimage - ��ǰ �̹��� ��Ÿ�� ����</FONT>
							<br><img width=100 height=0 style="visibility:hidden;">
							<FONT class=font_blue>��) #xbprimage { border:1px #CCCCCC solid; }</FONT>
				<pre style="line-height:15px">
<B>[��� ��]</B> - ���� ������ �Ʒ��� ���� �����Ͻø� �˴ϴ�.

<FONT class=font_blue>&lt;style type="text/css">
  #xbprname {color:#666666;font-size:12px;}
  #xbprsellprice {color:#666666;font-size:12px;}
  #xbprreserve {color:#666666;font-size:12px;}
  #xbprquantity {color:#666666;font-size:12px;}
  #xbprimage {border:1px #CCCCCC solid;}
&lt;/style></FONT></pre>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ALLSELECT]</td>
							<td class=td_con1 style="padding-left:5;word-break:break-all;">
							��ǰ ��ü���� <FONT class=font_blue>(��:&lt;a href=[ALLSELECT]>��ü����&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ALLSELECTOUT]</td>
							<td class=td_con1 style="padding-left:5;word-break:break-all;">
							��ǰ ��ü���� <FONT class=font_blue>(��:&lt;a href=[ALLSELECTOUT]>��ü����&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ALLOUT]</td>
							<td class=td_con1 style="padding-left:5;word-break:break-all;">
							��ǰ �ϰ����� <FONT class=font_blue>(��:&lt;a href=[ALLOUT]>�ϰ�����&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BASKETLINK]</td>
							<td class=td_con1 style="padding-left:5;word-break:break-all;">
							��ٱ��� �ٷΰ��� <FONT class=font_blue>(��:&lt;a href=[BASKETLINK]>��ٱ��� �ٷΰ���&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<?} else if($code=="6") {?>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell width=180 align=right style="padding-right:15" nowrap>[IFMEMBER]<br>[IFELSEMEMBER]<br>[IFENDMEMBER]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							ȸ�� �α���/�α׾ƿ� ������ ���
							<pre style="line-height:15px">
<FONT class=font_blue>   <B>[IFMEMBER]</B>
      ȸ�� <FONT COLOR="red"><B>�α���</B></FONT> ������ ����� ����
   <B>[IFELSEMEMBER]</B>
      ȸ�� <FONT COLOR="red"><B>�α׾ƿ�</B></FONT> ������ ����� ����
   <B>[IFENDMEMBER]</B></font></pre>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell width=180 align=right style="padding-right:15" nowrap>[IFMEMNON]<br>[IFELSEMEMNON]<br>[IFENDMEMNON]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							ȸ�� ������ ���� ���� ���� ���
							<pre style="line-height:15px">
<FONT class=font_blue>   <B>[IFMEMNON]</B>
      ȸ�� ������ <FONT COLOR="red"><B>����</B></FONT> ����� ����
   <B>[IFELSEMEMNON]</B>
      ȸ�� ������ <FONT COLOR="red"><B>����</B></FONT> ����� ����
   <B>[IFENDMEMNON]</B></font></pre>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ID]</td>
							<td class=td_con1 style="padding-left:5;">
							ȸ�� ���̵�
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[GNAME]</td>
							<td class=td_con1 style="padding-left:5;">
							ȸ�����
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell width=180 align=right style="padding-right:15" nowrap>[IFGNAME]<br>[IFENDGNAME]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							ȸ������� ���� ���
							<pre style="line-height:15px">
<FONT class=font_blue>   <B>[IFGNAME]</B>
      ȸ������� <FONT COLOR="red"><B>����</B></FONT> ����� ����
   <B>[IFENDGNAME]</B></font></pre>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[TEL]</td>
							<td class=td_con1 style="padding-left:5;">
							��ȭ��ȣ
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[EMAIL]</td>
							<td class=td_con1 style="padding-left:5;">
							�̸��� �ּ�
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ADDR]</td>
							<td class=td_con1 style="padding-left:5;">
							���ּ�
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell width=180 align=right style="padding-right:15" nowrap>[IFNEWSMAIL]<br>[IFELSENEWSMAIL]<br>[IFENDNEWSMAIL]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							�̸��� ������ ���� �ƴ� ���
							<pre style="line-height:15px">
<FONT class=font_blue>   <B>[IFNEWSMAIL]</B>
      �̸��� <FONT COLOR="red"><B>����</B></FONT>�� ����� ����
   <B>[IFELSENEWSMAIL]</B>
      �̸��� <FONT COLOR="red"><B>�̼���</B></FONT>�� ����� ����
   <B>[IFENDNEWSMAIL]</B></font></pre>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell width=180 align=right style="padding-right:15" nowrap>[IFNEWSSMS]<br>[IFELSENEWSSMS]<br>[IFENDNEWSSMS]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							SMS ������ ���� �ƴ� ���
							<pre style="line-height:15px">
<FONT class=font_blue>   <B>[IFNEWSSMS]</B>
      SMS <FONT COLOR="red"><B>����</B></FONT>�� ����� ����
   <B>[IFELSENEWSSMS]</B>
      SMS <FONT COLOR="red"><B>�̼���</B></FONT>�� ����� ����
   <B>[IFENDNEWSSMS]</B></font></pre>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ORDERCNT]</td>
							<td class=td_con1 style="padding-left:5;">
							�ֱ� �ֹ����� �Ǽ�
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ORDERLINK]</td>
							<td class=td_con1 style="padding-left:5;">
							�ֹ����� �ٷΰ���
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[RESERVECNT]</td>
							<td class=td_con1 style="padding-left:5;">
							���� �����ݾ�
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[RESERVELINK]</td>
							<td class=td_con1 style="padding-left:5;">
							�����ݳ��� �ٷΰ���
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[COUPONCNT]</td>
							<td class=td_con1 style="padding-left:5;">
							���� ���� ������
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[COUPONLINK]</td>
							<td class=td_con1 style="padding-left:5;">
							�������� �ٷΰ���
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[PERSONALCNT]</td>
							<td class=td_con1 style="padding-left:5;">
							1:1 ���� �Ǽ�
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[PERSONALLINK]</td>
							<td class=td_con1 style="padding-left:5;">
							1:1���� �ٷΰ���
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<? } ?>
						</table>
						</td>
					</tr>
					<tr>
						<td width="20" colspan="2"><p>&nbsp;</p></td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><p class="LIPoint">����,�帲�������� �����ͷ� �ۼ��� �̹�����ε� �۾������� Ʋ���� �� ������ �����ϼ���!</p></td>
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
<?
}
?>
			<tr><td height="50"></td></tr>
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