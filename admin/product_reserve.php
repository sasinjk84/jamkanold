<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "pr-4";
$MenuCode = "product";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$mode=$_POST["mode"];
$code=$_POST["code"];
$money=$_POST["money"];
$gbn=$_POST["gbn"];
$reserve=$_POST["reserve"];
$reservetype=$_POST["reservetype"];
$danwi=$_POST["danwi"];
$cut=$_POST["cut"];

if(strlen($code)==12) {
	$sql = "SELECT type FROM tblproductcode WHERE codeA='".substr($code,0,3)."' ";
	$sql.= "AND codeB='".substr($code,3,3)."' ";
	$sql.= "AND codeC='".substr($code,6,3)."' AND codeD='".substr($code,9,3)."' ";
	$result=mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
	mysql_free_result($result);
	if(!$row) {
		$code="";
	}
	$type = $row->type;
} else {
	$code="";
}

if ($mode=="update" && (strlen(trim($money))>0 || strlen(trim($reserve))>0) && strlen($code)==12) {
	if(ereg("X",$type)) {
		$likecode=$code;
	} else {
		$likecode=substr($code,0,3);
		if(substr($code,3,3)!="000") {
			$likecode.=substr($code,3,3);
			if(substr($code,6,3)!="000") {
				$likecode.=substr($code,6,3);
				if(substr($code,9,3)!="000") {
					$likecode.=substr($code,9,3);
				}
			}
		}
	}

	$won=array(1=>0.1,10=>0.01,100=>0.001,1000=>0.0001);

	if ($gbn=="W") {
		$reserve=$reserve*1;
		if($reservetype!="Y") {
			$reservetype="N";
		}
		$sql = "UPDATE tblproduct SET ";
		$sql.= "reserve			= '".$money."', ";
		$sql.= "reservetype		= '".$reservetype."' ";
		$sql.= "WHERE productcode LIKE '".$likecode."%' ";
		mysql_query($sql,get_db_conn());

		$log_content = "## ������ �ϰ����� ## - �ڵ� $likecode - ���� ������ : $money";
		ShopManagerLog($_ShopInfo->getId(),$connect_ip,$log_content);
		$onload="<script>alert('������ �ϰ� ������ �Ϸ�Ǿ����ϴ�.');</script>";
	} else if ($gbn=="P") {
		if ($cut=="round") {
			$sql = "UPDATE tblproduct SET ";
			$sql.= "reserve=ROUND(sellprice*($reserve/100)*$won[$danwi]+$won[$danwi])*($danwi*10) ";
			$sql.= "WHERE productcode LIKE '".$likecode."%' ";
		} else if ($cut=="ceil") {
			$sql = "UPDATE tblproduct SET ";
			$sql.= "reserve=CEILING(sellprice*($reserve/100)*$won[$danwi])*($danwi*10) ";
			$sql.= "WHERE productcode LIKE '".$likecode."%' ";
		} else if ($cut=="floor") {
			$sql = "UPDATE tblproduct SET ";
			$sql.= "reserve=FLOOR(sellprice*($reserve/100)*$won[$danwi])*($danwi*10) ";
			$sql.= "WHERE productcode LIKE '".$likecode."%' ";
		}
		$result = mysql_query($sql,get_db_conn());

		$log_content = "## ������ �ϰ����� ## - �ڵ� $likecode - ���� ������ : $reserve% - $danwi������ $cut";
		ShopManagerLog($_ShopInfo->getId(),$connect_ip,$log_content);
		$onload="<script>alert('�������� �Ǹ� �ݾ��� $reserve%�� �����Ͽ����ϴ�.');</script>";
	}
}

?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script type="text/javascript" src="codeinit.js.php"></script>
<script language="JavaScript">
var code="<?=$code?>";
function CodeProcessFun(_code) {
	if(_code=="out" || _code.length==0 || _code=="000000000000") {
		document.all["code_top"].style.background="#dddddd";
		selcode="";
		seltype="";

		if(_code!="out") {
			BodyInit('');
		} else {
			_code="";
		}
	} else {
		document.all["code_top"].style.background="#ffffff";
		BodyInit(_code);
	}
}

var allopen=false;
function AllOpen() {
	display="show";
	open1="open";
	if(allopen) {
		display="none";
		open1="close";
		allopen=false;
	} else {
		allopen=true;
	}
	for(i=0;i<all_list.length;i++) {
		if(display=="none" && all_list[i].codeA==selcode.substring(0,3)) {
			all_list[i].selected=true;
			selcode=all_list[i].codeA+all_list[i].codeB+all_list[i].codeC+all_list[i].codeD;
			seltype=all_list[i].type;
		}
		all_list[i].display=display;
		all_list[i].open=open1;
		for(ii=0;ii<all_list[i].ArrCodeB.length;ii++) {
			if(display=="none") {
				all_list[i].ArrCodeB[ii].selected=false;
			}
			all_list[i].ArrCodeB[ii].display=display;
			all_list[i].ArrCodeB[ii].open=open1;
			for(iii=0;iii<all_list[i].ArrCodeB[ii].ArrCodeC.length;iii++) {
				if(display=="none") {
					all_list[i].ArrCodeB[ii].ArrCodeC[iii].selected=false;
				}
				all_list[i].ArrCodeB[ii].ArrCodeC[iii].display=display;
				all_list[i].ArrCodeB[ii].ArrCodeC[iii].open=open1;
				for(iiii=0;iiii<all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD.length;iiii++) {
					if(display=="none") {
						all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].selected=false;
					}
					all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].display=display;
					all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].open=open1;
				}
			}
		}
	}
	BodyInit('');
}

function CheckForm() {
	if(selcode.length!=12 || selcode=="000000000000") {
		alert ("�����Ͻ� ī�װ��� �����ϼ���.");
		return;
	}
	
	if(document.form1.gbn[0].checked==true) {
		if(document.form1.reservetype.value=="Y") {
			if(document.form1.money.value.length==0){
				alert('�ϰ� �������� �ݾ��� �Է��ϼ���');
				document.form1.money.focus();
				return;
			}
			if(isDigitSpecial(document.form1.money.value,".")) {
				alert("�������� ���ڿ� Ư������ �Ҽ���\(.\)���θ� �Է��ϼ���.");
				document.form1.money.focus();
				return;
			}
			
			if(getSplitCount(document.form1.money.value,".")>2) {
				alert("������ �Ҽ���\(.\)�� �ѹ��� ��밡���մϴ�.");
				document.form1.money.focus();
				return;
			}

			if(getPointCount(document.form1.money.value,".",2)==true) {
				alert("�������� �Ҽ��� ���� ��°�ڸ������� �Է� �����մϴ�.");
				document.form1.money.focus();
				return;
			}

			if(Number(document.form1.money.value)>100 || Number(document.form1.money.value)<0) {
				alert("�������� 0 ���� ũ�� 100 ���� ���� ���� �Է��� �ּ���.");
				document.form1.money.focus();
				return;
			}
		} else {
			if(document.form1.money.value.length==0){
				alert('�ϰ� �������� �ݾ��� �Է��ϼ���');
				document.form1.money.focus();
				return;
			}
			if(isDigitSpecial(document.form1.money.value,"")) {
				alert("�������� ���ڷθ� �Է��ϼ���.");
				document.form1.money.focus();
				return;
			}
		}
	}

	if (!confirm("�ش� ī�װ��� �������� �����Ͻðڽ��ϱ�?")) {
		return;
	}
	document.form1.mode.value="update";
	document.form1.code.value=selcode;
	document.form1.submit();
}

function GoAllUpdate(){
	if(selcode.length!=12 || selcode=="000000000000") {
		alert('���� ī�װ��� �����ϼ���');
		return;
	}
	document.form2.code.value=selcode;
	document.form2.submit();
}

function chkFieldMaxLenFunc(thisForm,reserveType) {
	if (reserveType=="Y") { max=5; addtext="/Ư������(�Ҽ���)";} else { max=6; }
	if (thisForm.money.value.bytes() > max) {
		alert("�Է��� �� �ִ� ��� ������ �ʰ��Ǿ����ϴ�.\n\n" + "����"+addtext+" " + max + "�� �̳��� �Է��� �����մϴ�.");
		thisForm.money.value = thisForm.money.value.cut(max);
		thisForm.money.focus();
	}
}

function getSplitCount(objValue,splitStr)
{
	var split_array = new Array();
	split_array = objValue.split(splitStr);
	return split_array.length;
}

function getPointCount(objValue,splitStr,falsecount)
{
	var split_array = new Array();
	split_array = objValue.split(splitStr);
	
	if(split_array.length!=2) {
		if(split_array.length==1) {
			return false;
		} else {
			return true;
		}
	} else {
		if(split_array[1].length>falsecount) {
			return true;
		} else {
			return false;
		}
	}
}

function isDigitSpecial(objValue,specialStr)
{
	if(specialStr.length>0) {
		var specialStr_code = parseInt(specialStr.charCodeAt(i));

		for(var i=0; i<objValue.length; i++) {
			var code = parseInt(objValue.charCodeAt(i));
			var ch = objValue.substr(i,1).toUpperCase();
			
			if((ch<"0" || ch>"9") && code!=specialStr_code) {
				return true;
				break;
			}
		}
	} else {
		for(var i=0; i<objValue.length; i++) {
			var ch = objValue.substr(i,1).toUpperCase();
			if(ch<"0" || ch>"9") {
				return true;
				break;
			}
		}
	}
}
</script>

<STYLE type=text/css>
	#menuBar {}
	#contentDiv {WIDTH: 200;HEIGHT: 315;}
</STYLE>
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
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ��ǰ���� &gt; ��ǰ �ϰ����� &gt; <span class="2depth_select">������ �ϰ�����</span></td>
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
					<TD><IMG SRC="images/product_reserve_title.gif" ALT=""></TD>
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
					<TD width="100%" class="notice_blue">���θ��� ��ϵ� ��� ��ǰ�� �������� �ϰ� ������ �� �ֽ��ϴ�.</TD>
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
				<td height=20></td>
			</tr>
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=mode>
			<input type=hidden name=code>
			<tr>
				<td>
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td valign="top">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="232" height="100%" valign="top" background="images/category_boxbg.gif">
						<table cellpadding="0" cellspacing="0" width="242">
						<tr>
							<td bgcolor="white"><IMG SRC="images/product_totoacategory_title.gif" WIDTH=85 HEIGHT=24 ALT=""></td>
						</tr>
						<tr>
							<td><IMG SRC="images/category_box1.gif" border="0"></td>
						</tr>
						<tr>
							<td bgcolor="#0F8FCB" style="padding:2;padding-left:4">
							<button title="��ü Ʈ��Ȯ��" id="btn_treeall" class="btn" onmouseover="if(this.className=='btn'){this.className='btnOver'}" onmouseout="if(this.className=='btnOver'){this.className='btn'}" unselectable="on" onclick="AllOpen();"><IMG SRC="images/category_btn1.gif" WIDTH=22 HEIGHT=23 border="0"></button>
							</td>
						</tr>
						<tr>
							<td bgcolor="#0F8FCB" style="padding-top:4pt; padding-bottom:6pt;"></td>
						</tr>
						<tr>
							<td><IMG SRC="images/category_box2.gif" border="0"></td>
						</tr>
						<tr>
							<td width="100%" height="100%" align=center valign=top style="padding-left:5px;padding-right:5px;">
							<DIV class=MsgrScroller id=contentDiv style="width=99%;height:350;OVERFLOW-x: auto; OVERFLOW-y: auto;" oncontextmenu="return false" style="overflow-x:hidden;overflow-y:hidden;" ondragstart="return false" onselectstart="return false" oncontextmenu="return false">
							<DIV id=bodyList>
							<table border=0 cellpadding=0 cellspacing=0 width="100%" height="100%" bgcolor=FFFFFF>
							<tr>
								<td height=18><IMG SRC="images/directory_root.gif" border=0 align=absmiddle> <span id="code_top" style="cursor:default;" onmouseover="this.className='link_over'" onmouseout="this.className='link_out'" onclick="ChangeSelect('out');">�ֻ��� ī�װ�</span></td>
							</tr>
							<tr>
								<!-- ��ǰī�װ� ��� -->
								<td id="code_list" nowrap valign=top></td>
								<!-- ��ǰī�װ� ��� �� -->
							</tr>
							</table>
							</DIV>
							</DIV>
							</td>
						</tr>
						</table>
						</td>
					</tr>
					<tr>
						<td><IMG SRC="images/category_boxdown.gif" border="0"></td>
					</tr>
					</table>
					</td>
					<td width="15"><img src="images/btn_next1.gif" width="25" height="25" border="0" hspace="5"><br></td>
					<td width="100%" valign="top" height="100%">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td>
						<table cellpadding="0" cellspacing="0" width="100%" height="100%">
						<tr>
							<td width="100%" bgcolor="#FFFFFF"><IMG SRC="images/product_mainlist_text3.gif" border="0"></td>
						</tr>
						<tr>
							<td width="100%" height="100%" valign="top" style="BORDER-bottom:#eeeeee 2px solid;BORDER-top:#eeeeee 2px solid;">
							<table cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td width="100%" style="padding-top:2pt; padding-bottom:2pt;"><input type=radio name=gbn value="W" checked style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none">������(��)�� �ϰ� <input type=text name=money size=10 maxlength=6 style="text-align:right" class="input" onKeyUP="chkFieldMaxLenFunc(this.form,this.form.reservetype.value);"><select name="reservetype" style="font-size:8pt;margin-left:1px;" onchange="chkFieldMaxLenFunc(this.form,this.value);"><option value="N" selected>��</option><option value="Y">��</option></select>���� �����մϴ�.</td>
							</tr>
							<tr>
								<td width="100%" style="padding-top:2pt; padding-bottom:2pt;" height="22"><input type=radio name=gbn value="P" style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none">�������� �ǸŰ����� <select name=reserve class="select">
									<?for($i=0.1;$i<=0.9;$i+=0.1) echo "<option value='$i'>$i</option>\n";  ?>
									<?for($i=1;$i<=100;$i++) echo "<option value='$i'>$i</option>\n";  ?>
									</select> %�� <select name=danwi class="select">
										<option value="1">1</option>
										<option value="10">10</option>
										<option value="100">100</option>
										<option value="1000">1000</option>
									</select>�� ������ <select name=cut class="select">
									<option value="floor">����</option>
									<option value="round">�ݿø�</option>
									<option value="ceil">�ø�</option>
								</select>�Ͽ� �����մϴ�.</td>
							</tr>
							<tr>
								<td width="100%">&nbsp;</td>
							</tr>
							<tr>
								<td width="100%" style="padding-top:5pt; padding-bottom:5pt;">
								<table cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td width="100%" class="font_orange" style="padding-top:5pt; padding-bottom:5pt; border-width:1pt; border-color:rgb(0,153,204); border-style:solid;">&nbsp;&nbsp;* �ڵ�/���� ��ǰ�� �ǸŰ����� �⺻ ���� ��ǰ �ǸŰ����� ���� �����Դϴ�.<br>
									&nbsp;&nbsp;* ��ǰ ������ ���濡���� �޸�(,)�� ������ ���ڸ� �Է��ϼ���.<br>
									&nbsp;&nbsp;* ��ǰ ������ ���濡���� ���� �� �Ҽ���(.)�� �Է��ϼ���.<br>
									&nbsp;&nbsp;* ��ǰ �������� �Ҽ��� ��°�ڸ����� �Է��� �����մϴ�.<br>
									&nbsp;&nbsp;* ��ǰ �������� ���� ���� �ݾ� �Ҽ��� ���� �ڸ��� �ݿø� ó���Ǿ� ���޵˴ϴ�.<br>
										&nbsp;&nbsp;* ���õ� ī�װ��� ��� ���� ī�װ��� ��ǰ ������(��)�� ����˴ϴ�.<br>
										&nbsp;&nbsp;* ����� ������(��)�� �������� �����Ƿ� ������ ó���Ͻñ� �ٶ��ϴ�.<br>
										&nbsp;&nbsp;* <b>[�ϰ� ������ ����]</b> ��ư�� ������ �ش� ī�װ��� ��ǰ������ ���� �� �ֽ��ϴ�.</td>
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
					<tr>
						<td height=10></td>
					</tr>
					<tr>
						<td align=center><a href="javascript:CheckForm();"><img src="images/botteon_save.gif" border="0" hspace="0" vspace="4"></a>&nbsp;&nbsp;<a href="javascript:GoAllUpdate();"><img src="images/btn_allpoint.gif" border="0" hspace="2" vspace="4"></a></td>
					</tr>
					</table>
					</td>
				</tr>
				</table>
				</td>
			</tr>
			</form>
			<form name=form2 action="product_allupdate.php" method=post>
			<input type=hidden name=code>
			<tr>
				<td height="30"></td>
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
						<td><span class="font_dotline">������ �ϰ�����</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top"><p>- �ڵ�/���� ��ǰ�� �ǸŰ����� �⺻ ���� ��ǰ �ǸŰ����� ���� �����Դϴ�.</p></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- ���� ��ǰ ������ ������ [��ǰ ���/����/����] �޴����� ó���Ͻñ� �ٶ��ϴ�.<br>
						<b>&nbsp;&nbsp;</b><a href="javascript:parent.topframe.GoMenu(4,'product_register.php');"><span class="font_blue">��ǰ���� > ī�װ�/��ǰ���� > ��ǰ ���/����/����</span></a></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- ���õ� ī�װ��� ��� ����ī�װ��� ��ϵ� ��� ��ǰ�� �������� �ϰ�����ǹǷ� ī�װ� ���ÿ� �����ϼ���.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- ����� �������� �������� �����Ƿ� ������ ó���Ͻñ� �ٶ��ϴ�.</td>
					</tr>
					<tr>
						<td colspan="2" height="20"></td>
					</tr>
					<tr>
						<td align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><span class="font_dotline">������ �ϰ����� ���</span></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">�� �ϰ� ������ ���ϴ� ī�װ��� �����մϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">�� ���� �����ݾ��� �Է��Ͽ� �����ϴ� ��İ� ��ǰ �ǸŰ��ݿ� ���� ������ �����ϴ� �ΰ��� ��� �� �ϳ��� �����ϼ���.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">�� ���ϴ� ������ ������ �Է��Ͻ� �� �����ϱ� ��ư�� ��������.</td>
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
<?
$sql = "SELECT * FROM tblproductcode WHERE type!='T' AND type!='TX' AND type!='TM' AND type!='TMX' ";
$sql.= "ORDER BY sequence DESC ";
include ("codeinit.php");
?>

<?=$onload?>

<? INCLUDE "copyright.php"; ?>