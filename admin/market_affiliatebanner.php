<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "ma-1";
$MenuCode = "market";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$maxcnt=5;

$type=$_POST["type"];
$mode=$_POST["mode"];
$num=$_POST["num"];
$used=$_POST["used"];
$banner_type=$_POST["banner_type"];
$banner_target=$_POST["banner_target"];
$banner_url=$_POST["banner_url"];
$title=$_POST["title"];
$banner_html=$_POST["banner_html"];
if(strlen($used)==0) $used="N";

$imagepath=$Dir.DataDir."shopimages/banner/";
$filename=date("YmdHis").".gif";

if($type=="insert") {
	$sql = "SELECT COUNT(*) as cnt FROM tblaffiliatebanner ";
	$result = mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
	mysql_free_result($result);
	if($row->cnt<$maxcnt) {
		if($banner_type=="Y") {	//�̹��� ��Ϲ��
			$banner_image=$_FILES["banner_image"];
			if ($banner_image["size"]>153600) {
				echo "<script>alert ('��� �̹��� �뷮�� 150KB�� ���� �� �����ϴ�.');location.href='".$_SERVER[PHP_SELF]."';</script>\n";
				exit;
			}

			if (strlen($banner_image[name])>0 && $banner_image["size"]>0 && (strtolower(substr($banner_image[name],strlen($banner_image[name])-3,3))=="gif" || strtolower(substr($banner_image[name],strlen($banner_image[name])-3,3))=="jpg")) {
				$banner_image[name]=$filename;
				move_uploaded_file($banner_image[tmp_name],$imagepath.$banner_image[name]);
				chmod($imagepath.$banner_image[name],0664);
			}

			$content="Y=".$banner_target."=".$banner_url."=".$banner_image[name];
		} else {				//html �������
			$content="N=".$banner_html;
		}
		$sql = "INSERT tblaffiliatebanner SET ";
		$sql.= "used		= 'N', ";
		$sql.= "reg_date	= '".date("YmdHis")."', ";
		$sql.= "title		= '".$title."', ";
		$sql.= "content		= '".$content."' ";
		mysql_query($sql,get_db_conn());
		$onload="<script>alert('��� ����� �Ϸ�Ǿ����ϴ�.');</script>";
		unset($type); unset($used); unset($banner_type); unset($banner_target); unset($banner_url);
		unset($banner_html); unset($title); unset($content);
	} else {
		$onload="<script>alert('��� ����� �ִ� ".$maxcnt."�� ���� ��� �����մϴ�.');</script>";
	}
} else if (($type=="modify_result" || $type=="modify") && strlen($num)>0) {
	$sql = "SELECT * FROM tblaffiliatebanner WHERE num = '".$num."' ";
	$result = mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		mysql_free_result($result);
		$tempcontent=explode("=",$row->content);
		$temptype=$tempcontent[0];

		if($type=="modify") {
			$used=$row->used;
			$title=$row->title;
			$tempcontent=explode("=",$row->content);
			$banner_type=$tempcontent[0];
			if($banner_type=="Y") {
				$banner_target=$tempcontent[1];
				$banner_url=$tempcontent[2];
				$banner_image=$tempcontent[3];
				$banner_html="";
			} else if($banner_type=="N") {
				$banner_html=$tempcontent[1];
				$banner_target="";
				$banner_url="";
				$banner_image="";
			}
		} else if($type=="modify_result") {
			if($temptype=="Y") {
				$old_image=$tempcontent[3];
			} else if($temptype=="N") {
				$old_image="";
			}
			if($banner_type=="Y") {	//�̹��� ��Ϲ��
				$banner_image=$_FILES["banner_image"];
				if ($banner_image["size"]>100000) {
					echo "<script>alert ('��� �̹��� �뷮�� 150KB�� ���� �� �����ϴ�.');location.href='".$_SERVER[PHP_SELF]."';</script>\n";
					exit;
				}

				if (strlen($banner_image[name])>0 && $banner_image["size"]>0 && (strtolower(substr($banner_image[name],strlen($banner_image[name])-3,3))=="gif" || strtolower(substr($banner_image[name],strlen($banner_image[name])-3,3))=="jpg")) {
					$banner_image[name]=$filename;
					move_uploaded_file($banner_image[tmp_name],$imagepath.$banner_image[name]);
					chmod($imagepath.$banner_image[name],0664);
				}
				if(strlen($banner_image[name])>0 && $banner_image["size"]>0) {
					$content="Y=".$banner_target."=".$banner_url."=".$banner_image[name];
					if(strlen($old_image)>0) {
						if(file_exists($imagepath.$old_image)) {
							unlink($imagepath.$old_image);
						}
					}
				} else {
					$content="Y=".$banner_target."=".$banner_url."=".$old_image;
				}
			} else {				//html �������
				$content="N=".$banner_html;
				if(strlen($old_image)>0) {
					if(file_exists($imagepath.$old_image)) {
						unlink($imagepath.$old_image);
					}
				}
			}
			$sql = "UPDATE tblaffiliatebanner SET ";
			$sql.= "used		= '".$used."', ";
			$sql.= "title		= '".$title."', ";
			$sql.= "content		= '".$content."' ";
			$sql.= "WHERE num = '".$num."' ";
			mysql_query($sql,get_db_conn());
			$onload="<script>alert('��� ������ �Ϸ�Ǿ����ϴ�.');</script>";
			unset($type); unset($used); unset($banner_type); unset($banner_target); unset($banner_url);
			unset($banner_html); unset($num); unset($title); unset($content);
		}
	} else {
		mysql_free_result($result);
		$onload="<script>alert('�����Ϸ��� ��� ������ �������� �ʽ��ϴ�.');</script>";
	}
} else if ($type=="delete" && strlen($num)>0) {
	$sql = "SELECT * FROM tblaffiliatebanner WHERE num = '".$num."' ";
	$result = mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$tempcontent=explode("=",$row->content);
		$temptype=$tempcontent[0];
		if($temptype=="Y") {
			$old_image=$tempcontent[3];
		} else if($temptype=="N") {
			$old_image="";
		}
		if(strlen($old_image)>0) {
			if(file_exists($imagepath.$old_image)) {
				unlink($imagepath.$old_image);
			}
		}

		$sql = "DELETE FROM tblaffiliatebanner WHERE num = '".$num."' ";
		mysql_query($sql,get_db_conn());
		$onload="<script>alert('�ش� ��ʸ� �����Ͽ����ϴ�.');</script>";
		unset($type); unset($used); unset($banner_type); unset($banner_target); unset($banner_url);
		unset($banner_html); unset($num); unset($title); unset($content);
	}
	mysql_free_result($result);
}

if(strlen($type)==0) $type="insert";
$type_name="images/botteon_save.gif";
if($type=="modify" || $type=="modify_result") $type_name="images/btn_edit2.gif";

if($type=="insert") $used_disabled="disabled";
?>

<? INCLUDE "header.php"; ?>
<script>try {parent.topframe.ChangeMenuImg(7);}catch(e){}</script>
<script type="text/javascript" src="lib.js.php"></script>
<script type="text/javascript" src="calendar.js.php"></script>
<script language="JavaScript">

function CheckForm(type) {
	if(document.form1.title.value.length==0) {
		alert("��� ������ �Է��ϼ���.");
		document.form1.title.focus();
		return;
	}
	temptype="";
	for(i=0;i<document.form1.banner_type.length;i++) {
		if(document.form1.banner_type[i].checked==true) {
			temptype=document.form1.banner_type[i].value;
			break;
		}
	}
	if(temptype.length==0 || (temptype!="Y" && temptype!="N")) {
		alert("��� ��� ���¸� �����ϼ���.");
		document.form1.banner_type[0].focus();
		return;
	}
	if(temptype=="Y") {
		if(document.form1.banner_image.value.length==0) {
			if(type=="modify" || type=="modify_result") {
				if(document.form1.tempbannerimg.value.length==0) {
					alert("��� �̹����� ����ϼ���.");
					document.form1.banner_image.focus();
					return;
				}
			} else {
				alert("��� �̹����� ����ϼ���.");
				document.form1.banner_image.focus();
				return;
			}
		}
		if(document.form1.banner_url.length==0) {
			alert("��� ����URL�� �Է��ϼ���.");
			document.form1.banner_url.focus();
			return;
		}
	} else if(temptype=="N") {
		if(document.form1.banner_html.length==0) {
			alert("��� ������ �Է��ϼ���.");
			document.form1.banner_html.focus();
			return;
		}
	}
	if(type=="modify" || type=="modify_result") {
		if(!confirm("�ش� ��ʸ� �����Ͻðڽ��ϱ�?")) {
			return;
		}
		document.form1.type.value="modify_result";
	} else {
		document.form1.type.value="insert";
	}
	document.form1.submit();
}

function ModeSend(type,num) {
	if(type=="delete") {
		if(!confirm("�ش� ��ʸ� �����Ͻðڽ��ϱ�?")) {
			return;
		}
	}
	document.form1.type.value=type;
	document.form1.num.value=num;
	document.form1.submit();
}


function ChangeType(type){
	if(type=="Y") {
		document.form1.banner_image.disabled=false;
		document.form1.banner_url.disabled=false;
		document.form1.banner_target.disabled=false;
		document.form1.banner_html.disabled=true;
	} else if(type=="N") {
		document.form1.banner_image.disabled=true;
		document.form1.banner_url.disabled=true;
		document.form1.banner_target.disabled=true;
		document.form1.banner_html.disabled=false;
	}
}

function BannerImageMouseOver() {
	obj = event.srcElement;
	WinObj=eval("document.all.bannerimg");
	obj._tid = setTimeout("BannerImageView(WinObj)",200);
}
function BannerImageView(WinObj) {
	WinObj.style.visibility = "visible";
}
function BannerImageMouseOut() {
	obj = event.srcElement;
	WinObj=eval("document.all.bannerimg");
	WinObj.style.visibility = "hidden";
	clearTimeout(obj._tid);
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
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ���������� &gt; <span class="2depth_select">Affiliate ��ʰ���</span></td>
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
			<input type=hidden name=num value="<?=$num?>">
			<input type=hidden name=htmlmode value='wysiwyg'>
			<tr><td height="8"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/market_affiliate_title.gif" border="0"></TD>
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
					<TD width="100%" class="notice_blue">�α��� �������� ��ϵ� ��ʸ� �����Ͻ� �� �ֽ��ϴ�.</TD>
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
			<tr><td height=20></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/market_Affiliate_stitle1.gif" border="0"></TD>
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
				<col width=30></col>
				<col width=55></col>
				<col width=></col>
				<col width=65></col>
				<col width=80></col>
				<col width=60></col>
				<col width=60></col>
				<TR>
					<TD colspan=7 background="images/table_top_line.gif"></TD>
				</TR>
				<TR align="center">
					<TD class="table_cell">No</TD>
					<TD class="table_cell1">��뿩��</TD>
					<TD class="table_cell1">�������</TD>
					<TD class="table_cell1">���Ÿ��</TD>
					<TD class="table_cell1">�����</TD>
					<TD class="table_cell1">����</TD>
					<TD class="table_cell1">����</TD>
				</TR>
				<TR>
					<TD colspan=7 background="images/table_con_line.gif"></TD>
				</TR>
<?
				$colspan=7;
				$sql = "SELECT num, used, reg_date, title, content FROM tblaffiliatebanner ORDER BY num DESC ";
				$result = mysql_query($sql,get_db_conn());
				$cnt=0;
				while($row=mysql_fetch_object($result)) {
					$cnt++;
					$reg_date = substr($row->reg_date,0,4).".".substr($row->reg_date,4,2).".".substr($row->reg_date,6,2);
					if($row->used=="Y")	$used_name = "�����";
					else if($row->used=="N")	$used_name = "������";
					$temptype=substr($row->content,0,1);
					if($temptype=="Y") $typename="�̹�����";
					else if($temptype=="N") $typename="HTML������";
					echo "<tr align=\"center\">\n";
					echo "	<td class=\"td_con2\">".$cnt."</td>\n";
					echo "	<td class=\"td_con1\">".$used_name."</td>\n";
					echo "	<td class=\"td_con1\" align=\"left\">&nbsp;&nbsp;".$row->title."</td>\n";
					echo "	<td class=\"td_con1\">".$typename."</td>\n";
					echo "	<td class=\"td_con1\">".$reg_date."</td>\n";
					echo "	<td class=\"td_con1\"><a href=\"javascript:ModeSend('modify','".$row->num."');\"><img src=\"images/btn_edit.gif\" width=\"50\" height=\"22\" border=\"0\"></a></td>\n";
					echo "	<td class=\"td_con1\"><a href=\"javascript:ModeSend('delete','".$row->num."');\"><img src=\"images/btn_del.gif\" width=\"50\" height=\"22\" border=\"0\"></a></td>\n";
					echo "</tr>\n";
					echo "<TR>\n";
					echo "	<TD colspan=".$colspan." background=\"images/table_con_line.gif\"></TD>\n";
					echo "</TR>\n";
				}
				mysql_free_result($result);
				if ($cnt==0) {
					echo "<tr><td class=td_con2 colspan=".$colspan." align=center>��ϵ� ��ʰ� �����ϴ�.</td></tr>";
				}
?>
				<TR>
					<TD background="images/table_top_line.gif" colspan="7"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height="40"></td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/market_Affiliate_stitle2.gif" border="0"></TD>
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
				<col width=140></col>
				<col width=></col>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell">&nbsp;&nbsp;<img src="images/icon_point2.gif" width="8" height="11" border="0">��뿩��</TD>
					<TD class="td_con1" ><input type=checkbox id="idx_used" name=used value="Y" <?if($used=="Y")echo"checked";?> <?=$used_disabled?>> <label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_used>�����</label><br>* ��������� �Ǿ� �ִ� ��쿡�� ǥ�õ˴ϴ�. (�α��� �������� ǥ�õ�)<br><span class="font_orange">* ����� ����� ����Ŀ� ������ �� �ֽ��ϴ�.&nbsp;</span></TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell">&nbsp;&nbsp;<img src="images/icon_point2.gif" width="8" height="11" border="0">��� ����</TD>
					<TD class="td_con1" ><INPUT style="WIDTH:100%" name=title value="<?=$title?>" class="input"><br><span class="font_orange">��������Ͽ����� ����մϴ�. ������ �Է��� �ּ���.</span></TD>
				</tr>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				</table>
				</td>
			</tr>
			<tr><td height="10"></td></tr>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD colspan="2" width="760" class="table_cell"><input type=radio id="idx_bannertypeY" name="banner_type" value="Y" onclick="ChangeType('Y')"<?=($banner_type=="Y"?" checked":"")?>> <label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_bannertypeY><span class="font_orange">�̹����� ��ʵ��</span></label></td>
				</tr>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD colspan="2">
					<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
					<col width=140></col>
					<col width=></col>
					<TR>
						<TD bgcolor="white" style="padding-left:16pt;"><img src="images/icon_point2.gif" width="8" height="11" border="0">�������� �̹��� ����</TD>
						<TD class="td_con1">
						<table cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td><input type=file name="banner_image" style="width:300" disabled class="input">
<?
							if ($banner_type=="Y") {
								if(strlen($banner_image)>0 && file_exists($imagepath.$banner_image)==true) {
									echo "<input type=hidden name=tempbannerimg value=\"".$banner_image."\">\n";
									echo "<A style=\"cursor:hand;\" onMouseOver=\"BannerImageMouseOver()\" onMouseOut=\"BannerImageMouseOut();\"><B>[�̹��� Ȯ��]</B></A>";	
									echo "<div id=bannerimg style=\"position:absolute; z-index:100; left:500px; top:200px; visibility:hidden;\">\n";
									echo "<table border=0 cellpadding=0 cellspacing=0>\n";
									echo "<tr><td style=\"border:1px #000000 solid\"><img src=\"".$imagepath.$banner_image."\" border=0></td></tr>\n";
									echo "</table>\n";
									echo "</div>";
								}
							}
?>
							<span class="font_orange" style="font-size:11px;letter-spacing:-0.5pt;">* �̹����� 150KB ������ GIF, JPG�� ����</span></td>
						</tr>
						</table>
						</TD>
					</TR>
					<TR>
						<TD colspan="2" background="images/table_con_line.gif"></TD>
					</TR>
					<TR>
						<TD bgcolor="white" style="padding-left:16pt;"><img src="images/icon_point2.gif" width="8" height="11" border="0">���� URL �Է�</TD>
						<TD class="td_con1"><input type=text name=banner_url value="<?=$banner_url?>" size=50 disabled class="input"> <select name=banner_target disabled class="select">
						<option value="_blank"<?if($banner_target=="_blank")echo" selected";?>>_blank</option>
						<option value="_top"<?if($banner_target=="_top")echo" selected";?>>_top</option>
						<option value="_parent"<?if($banner_target=="_parent")echo" selected";?>>_parent</option>
						<option value="_self"<?if($banner_target=="_self")echo" selected";?>>_self</option>
						</select>
						</TD>
					</TR>
					</TABLE>
					</TD>
				</tr>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD colspan="2" class="table_cell"><input type=radio id="idx_bannertypeN" name="banner_type" value="N" onclick="ChangeType('N')"<?=($banner_type=="N"?" checked":"")?>> <label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_bannertypeN><span class="font_orange">HTML�� �������</span></label></td>
				</tr>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD colspan="2">
					<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
					<col width=140></col>
					<col width=></col>
					<TR>
						<TD bgcolor="white" style="padding-left:16pt;"><img src="images/icon_point2.gif" width="8" height="11" border="0">��� �����Է�</TD>
						<TD class="td_con1"><TEXTAREA name=banner_html style="width:100%;height:255" class="textarea" disabled><?=$banner_html?></textarea></TD>
					</tr>
					</table>
				</tr>
				<TR>
					<TD background="images/table_top_line.gif" colspan="2"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td HEIGHT=10></td></tr>
			<tr>
				<td align=center><a href="javascript:CheckForm('<?=$type?>');"><img src="<?=$type_name?>" width="113" height="38" border="0"></a></td>
			</tr>
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
					<TD background="images/manual_left1.gif"></TD>
					<TD COLSPAN=3 width="100%" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;" class="menual_bg">
					<table cellpadding="0" cellspacing="0" width="100%">
					<col width=20></col>
					<col width=></col>
					<tr>
						<td align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><span class="font_dotline">Affiliate ��ʰ���</span></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- Affiliate ��ʴ� �ִ� 5�������� ��� �����մϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- Affiliate ��� ��뿩�δ� �����ÿ��� ������ �� �ֽ��ϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- ��ϵ� Affiliate ��ʴ� �α��� ������ ������ �����ϰ� 1���� �����Ͽ� ��½�ŵ�ϴ�.<br>
						<b>&nbsp;&nbsp;</b>�α��� ������ ���ø� ������ ������ <a href="javascript:parent.topframe.GoMenu(2,'design_login.php');"><span class="font_blue">�����ΰ��� > ���ø�-������ ���� > �α��� ���� ȭ�� ���ø�</span></a><br>
						<b>&nbsp;&nbsp;</b>�α��� ������ ���� ������ ������ <a href="javascript:parent.topframe.GoMenu(2,'design_eachlogin.php');"><span class="font_blue">�����ΰ��� > ����������-������ ���� > �α��� ȭ�� �ٹ̱�</span></a></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- Affiliate ��ʿ� ������ ����ڷ�� �������� �ʽ��ϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- ������� �ʴ� ��ʴ� �ǵ��� ���� �ϼ���.</td>
					</tr>
					</table>
					</td>
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

			<form name=form2 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=mode value="update">
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

<script>
ChangeType("<?=$banner_type?>");
//editor_generate('content');
</script>

<?=$onload?>

<? INCLUDE "copyright.php"; ?>