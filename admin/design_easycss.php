<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "de-7";
$MenuCode = "design";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$array_text[0][idx]="family";
$array_text[0][name]="�۲�";
$array_text[0][value]="NanumGothic=�������,����=����,����=����,����=����,�ü�=�ü�,����=����,verdana=verdana,Arial=Arial,Wingdings=Wingdings,Tahoma=Tahoma,System=System,Arial Black=Arial Black,Arial Narrow=Arial Narrow,Comic Sans MS=Comic Sans MS,Courier New=Courier New,Georgia=Georgia";
$array_text[1][idx]="size";
$array_text[1][name]="ũ��";
$array_text[1][value]="7pt=7,8pt=8,9pt=9,10pt=10,11pt=11,12pt=12,13pt=13,14pt=14,15pt=15,16pt=16";
$array_text[2][idx]="weight";
$array_text[2][name]="�β�";
$array_text[2][value]="normal=����,bold=�β���";
$array_text[3][idx]="decoration";
$array_text[3][name]="�ɼ�";
$array_text[3][value]="=����,underline=����,line-through=�����";
$array_text[4][idx]="color";
$array_text[4][name]="����";
$array_text[4][value]="";

$array_menu[0]=array("��ǰī�װ�","Ŀ�´�Ƽ","�����");
$array_menu[1]=array("�ű�/�α�/��õ ��ǰ��","�ű�/�α�/��õ ����","Ư����ǰ��","Ư����ǰ ����","���ΰ�������","����������","������ǥ","���ΰԽ���","���ν��߰���","����������","�����±�","����������","���������ڵ�");
$array_menu[2]=array("�з��׷� ���ī�װ���","�з��׷� ����ī�װ���","�з��׷� ����ī�װ���","��ǰ��","��ǰ����","������","������","���߰���","������","�±�","Ư�̻���","��ǰ���Ĺ��","���� ��ǰ���Ĺ��","��ǰ��� ������ ����","���������� ����","�����ڵ�");

$css="";

$type=$_POST["type"];
if($type=="update") {
	$up_family=(array)$_POST["up_family"];
	$up_size=(array)$_POST["up_size"];
	$up_weight=(array)$_POST["up_weight"];
	$up_decoration=(array)$_POST["up_decoration"];
	$up_color=(array)$_POST["up_color"];

	$k=0;
	for($i=0;$i<count($array_menu[$k]);$i++) {
		$up_color[$k][$i]=str_replace(",","",$up_color[$k][$i]);
		$up_color[$k][$i]=str_replace("","",$up_color[$k][$i]);
		$css.=$up_family[$k][$i].",";
		$css.=$up_size[$k][$i].",";
		$css.=$up_weight[$k][$i].",";
		$css.=$up_decoration[$k][$i].",";
		$css.=$up_color[$k][$i].",";
	}
	$css=substr($css,0,-1)."";

	$k=1;
	for($i=0;$i<count($array_menu[$k]);$i++) {
		$up_color[$k][$i]=str_replace(",","",$up_color[$k][$i]);
		$up_color[$k][$i]=str_replace("","",$up_color[$k][$i]);
		$css.=$up_family[$k][$i].",";
		$css.=$up_size[$k][$i].",";
		$css.=$up_weight[$k][$i].",";
		$css.=$up_decoration[$k][$i].",";
		$css.=$up_color[$k][$i].",";
	}
	$css=substr($css,0,-1)."";

	$k=2;
	for($i=0;$i<count($array_menu[$k]);$i++) {
		$up_color[$k][$i]=str_replace(",","",$up_color[$k][$i]);
		$up_color[$k][$i]=str_replace("","",$up_color[$k][$i]);
		$css.=$up_family[$k][$i].",";
		$css.=$up_size[$k][$i].",";
		$css.=$up_weight[$k][$i].",";
		$css.=$up_decoration[$k][$i].",";
		$css.=$up_color[$k][$i].",";
	}
	$css=substr($css,0,-1);

	$sql = "UPDATE tblshopinfo SET css='".$css."' ";
	mysql_query($sql,get_db_conn());
	DeleteCache("tblshopinfo.cache");

	$_shopdata->css=$css;

	$onload="<script>alert(\"���θ� �ؽ�Ʈ �Ӽ� ������ �Ϸ�Ǿ����ϴ�.\");</script>";
} else if($type=="clear") {
	$sql = "UPDATE tblshopinfo SET css='' ";
	mysql_query($sql,get_db_conn());
	DeleteCache("tblshopinfo.cache");

	$_shopdata->css="";

	$onload="<script>alert(\"���θ� �ؽ�Ʈ �Ӽ��� �⺻������ �����Ǿ����ϴ�.\");</script>";
}

if(strlen($_shopdata->css)==0) {
	$sql = "SELECT * FROM tbltempletinfo WHERE icon_type='".$_shopdata->icon_type."' ";
	$result=mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
	
	$_shopdata->css=$row->default_css;
	mysql_free_result($result);
}


if(strlen($_shopdata->css)==0) {
	for($i=0;$i<count($array_menu[0]);$i++) {
		$_shopdata->css.="����,";
		$_shopdata->css.="9pt,";
		$_shopdata->css.="normal,";
		$_shopdata->css.=",";
		$_shopdata->css.=",";
	}
	$_shopdata->css=substr($_shopdata->css,0,-1)."";
	for($i=0;$i<count($array_menu[1]);$i++) {
		$_shopdata->css.="����,";
		$_shopdata->css.="9pt,";
		$_shopdata->css.="normal,";
		$_shopdata->css.=",";
		$_shopdata->css.=",";
	}
	$_shopdata->css=substr($_shopdata->css,0,-1)."";
	for($i=0;$i<count($array_menu[2]);$i++) {
		$_shopdata->css.="����,";
		$_shopdata->css.="9pt,";
		$_shopdata->css.="normal,";
		$_shopdata->css.=",";
		$_shopdata->css.=",";
	}
	$_shopdata->css=substr($_shopdata->css,0,-1);
}
$array_val=explode("",$_shopdata->css);

?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function CheckForm(type) {
	if(type=="clear") {
		if(!confirm("�⺻ �Ӽ������� �����Ͻðڽ��ϱ�?")) {
			return;
		}
	}
	document.form1.type.value=type;
	document.form1.submit();
}

function selcolor(obj){
	fontcolor = obj.value.substring(1);
	var newcolor = showModalDialog("color.php?color="+fontcolor, "oldcolor", "resizable: no; help: no; status: no; scroll: no;");
	if(newcolor){
		obj.value=newcolor;
	}
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
			<? include ("menu_design.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �����ΰ��� &gt; Easy ������ ����  &gt; <span class="2depth_select">Easy �ؽ�Ʈ �Ӽ� ����</span></td>
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
							<TD><IMG SRC="images/design_easycss_title.gif" ALT=""></TD>
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
							<TD width="100%" class="notice_blue"><p>����������, ��ǰī�װ�, �˻�ȭ�鿡�� �������� �ؽ�Ʈ���� �Ӽ��� �����ϰ� �����Ͻ� �� �ֽ��ϴ�.</p></TD>
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
							<TD><IMG SRC="images/design_easycss_stitle1.gif" WIDTH="210" HEIGHT=31 ALT=""></TD>
							<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
							<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
						</TR>
					</TABLE>
				</td>
			</tr>
			<tr>
				<td height=3></td>
			</tr>
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post enctype="multipart/form-data">
			<input type=hidden name=type>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD colspan=6 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="153"><p align="center">�޴���</p></TD>
					<TD class="table_cell1"><p align="center">�۲�</p></TD>
					<TD class="table_cell1"><p align="center">ũ��</p></TD>
					<TD class="table_cell1"><p align="center">����</p></TD>
					<TD class="table_cell1"><p align="center">����ó��</p></TD>
					<TD class="table_cell1"><p align="center">����</p></TD>
				</TR>
				<TR>
					<TD colspan="6" background="images/table_con_line.gif"></TD>
				</TR>
<?
			$z=0;
			$k=0;
			$m=0;
			$value=explode(",",$array_val[$z]);
			for($i=0;$i<count($array_menu[$z]);$i++) {
				echo "<TR>\n";
				echo "	<TD class=\"table_cell\" width=\"153\"><img src=\"images/icon_point2.gif\" width=\"8\" height=\"11\" border=\"0\"><FONT color=\"#3d3d3d\">".$array_menu[$z][$i]."</font></td>\n";
				for($j=0;$j<count($array_text);$j++) {
					echo "	<TD class=\"td_con1\" align=center>";
					if($array_text[$j][idx]=="color") {
						//$array_text[$j][name]." : ";
						echo "<table cellpadding=\"0\" cellspacing=\"0\" width=\"140\" align=\"center\">\n";
						echo "<tr>\n";
						echo "	<td>#</td>\n";
						echo "	<td width=\"34\"><input type=text name=\"up_".$array_text[$j][idx]."[".$z."][]\" value=\"".$value[$k]."\" size=8 maxlength=6 class=\"input\"></td>\n";
						echo "	<td width=\"34\"><font color=\"".$value[$k]."\"><span style=\"font-size:20pt;\">��</span></font></td>\n";
						echo "	<td>&nbsp;<a href=\"javascript:selcolor(document.form1['up_".$array_text[$j][idx]."[".$z."][]'][".$m."])\"><IMG src=\"images/icon_color.gif\" border=0 width=\"55\" height=\"18\"></a></td>\n";
						echo "</tr>\n";
						echo "</table>\n";
						$m++;
					} else {
						//echo "&nbsp;".$array_text[$j][name]." : ";
						echo "<select name=\"up_".$array_text[$j][idx]."[".$z."][]\" class=\"select\">\n";
						$tmparr=explode(",",$array_text[$j][value]);
						for($y=0;$y<count($tmparr);$y++) {
							$tmp=explode("=",$tmparr[$y]);
							echo "<option value=\"".$tmp[0]."\" ";
							if($value[$k]==$tmp[0]) echo " selected";
							echo ">".$tmp[1]."</option>\n";
						}
						echo "</select>\n";
					}
					$k++;
					echo "	</td>\n";
				}
				echo "</TR>\n";
				echo "<TR>";
				echo "	<TD colspan=\"6\" align=center background=\"images/table_con_line.gif\"><img src=\"images/table_con_line.gif\" width=\"4\" height=\"1\" border=\"0\"></TD>\n";
				echo "</TR>\n";
			}
?>
					<TR>
						<TD colspan=6 background="images/table_top_line.gif"></TD>
					</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height="30"></td></tr>
			<tr>
				<td>
					<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
						<TR>
							<TD><IMG SRC="images/design_easycss_stitle2.gif" border="0"></TD>
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
						<TR>
							<TD colspan=6 background="images/table_top_line.gif"></TD>
						</TR>
						<TR align=center>
							<TD class="table_cell" width="153">�޴���</TD>
							<TD class="table_cell1">�۲�</TD>
							<TD class="table_cell1">ũ��</TD>
							<TD class="table_cell1">����</TD>
							<TD class="table_cell1">����ó��</TD>
							<TD class="table_cell1">����</TD>
						</TR>
						<TR>
							<TD colspan="6" background="images/table_con_line.gif"></TD>
						</TR>
<?
			$z=1;
			$k=0;
			$m=0;
			$value=explode(",",$array_val[$z]);
			for($i=0;$i<count($array_menu[$z]);$i++) {
				echo "<tr>\n";
				echo "	<TD class=\"table_cell\" width=\"153\"><img src=\"images/icon_point2.gif\" width=\"8\" height=\"11\" border=\"0\"><FONT color=\"#3d3d3d\">".$array_menu[$z][$i]."</font></td>\n";
				for($j=0;$j<count($array_text);$j++) {
					echo "	<TD class=\"td_con1\" align=center>";
					if($array_text[$j][idx]=="color") {
						//echo "&nbsp;".$array_text[$j][name]." : ";
						echo "<table cellpadding=\"0\" cellspacing=\"0\" width=\"140\" align=\"center\">\n";
						echo "<tr>\n";
						echo "	<td>#</td>\n";
						echo "	<td width=\"34\"><input type=text name=\"up_".$array_text[$j][idx]."[".$z."][]\" value=\"".$value[$k]."\" size=8 maxlength=6 class=\"input\"></td>\n";
						echo "	<td width=\"34\"><font color=\"".$value[$k]."\"><span style=\"font-size:20pt;\">��</span></font></td>\n";
						echo "	<td>&nbsp;<a href=\"javascript:selcolor(document.form1['up_".$array_text[$j][idx]."[".$z."][]'][".$m."])\"><IMG src=\"images/icon_color.gif\" border=0 width=\"55\" height=\"18\"></a></td>\n";
						echo "</tr>\n";
						echo "</table>\n";
						$m++;
					} else {
						//echo "&nbsp;".$array_text[$j][name]." : ";
						echo "<select name=\"up_".$array_text[$j][idx]."[".$z."][]\" class=\"select\">\n";
						$tmparr=explode(",",$array_text[$j][value]);
						for($y=0;$y<count($tmparr);$y++) {
							$tmp=explode("=",$tmparr[$y]);
							echo "<option value=\"".$tmp[0]."\" ";
							if($value[$k]==$tmp[0]) echo " selected";
							echo ">".$tmp[1]."</option>\n";
						}
						echo "</select>\n";
					}
					echo "	</td>\n";
					$k++;
				}
				echo "</tr>\n";
				echo "<TR>";
				echo "	<TD colspan=\"6\" align=center background=\"images/table_con_line.gif\"><img src=\"images/table_con_line.gif\" width=\"4\" height=\"1\" border=\"0\"></TD>\n";
				echo "</TR>\n";
			}
?>
				<TR>
					<TD colspan=6 background="images/table_top_line.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height="30"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/design_easycss_stitle3.gif" WIDTH="210" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=3></td></tr>
			<tr>
				<td align="center">
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD colspan=6 background="images/table_top_line.gif"></TD>
				</TR>
				<TR align=center>
					<TD class="table_cell" width="153">�޴���</TD>
					<TD class="table_cell1">�۲�</TD>
					<TD class="table_cell1">ũ��</TD>
					<TD class="table_cell1">����</TD>
					<TD class="table_cell1">����ó��</TD>
					<TD class="table_cell1">����</TD>
				</TR>
				<TR>
					<TD colspan="6" background="images/table_con_line.gif"></TD>
				</TR>
<?
			$z=2;
			$k=0;
			$m=0;
			$value=explode(",",$array_val[$z]);
			for($i=0;$i<count($array_menu[$z]);$i++) {
				echo "<tr>\n";
				echo "	<TD class=\"table_cell\" width=\"153\"><img src=\"images/icon_point2.gif\" width=\"8\" height=\"11\" border=\"0\"><FONT color=\"#3d3d3d\">".$array_menu[$z][$i]."</font></td>\n";
				for($j=0;$j<count($array_text);$j++) {
					echo "	<TD class=\"td_con1\" align=center>";
					if($array_text[$j][idx]=="color") {
						//echo "&nbsp;".$array_text[$j][name]." : ";
						echo "<table cellpadding=\"0\" cellspacing=\"0\" width=\"140\" align=\"center\">\n";
						echo "<tr>\n";
						echo "	<td>#</td>\n";
						echo "	<td width=\"34\"><input type=text name=\"up_".$array_text[$j][idx]."[".$z."][]\" value=\"".$value[$k]."\" size=8 maxlength=6 class=\"input\"></td>\n";
						echo "	<td width=\"34\"><font color=\"".$value[$k]."\"><span style=\"font-size:20pt;\">��</span></font></td>\n";
						echo "	<td>&nbsp;<a href=\"javascript:selcolor(document.form1['up_".$array_text[$j][idx]."[".$z."][]'][".$m."])\"><IMG src=\"images/icon_color.gif\" border=0 width=\"55\" height=\"18\"></a></td>\n";
						echo "</tr>\n";
						echo "</table>\n";
						$m++;
					} else {
						//echo "&nbsp;".$array_text[$j][name]." : ";
						echo "<select name=\"up_".$array_text[$j][idx]."[".$z."][]\" class=\"select\">\n";
						$tmparr=explode(",",$array_text[$j][value]);
						for($y=0;$y<count($tmparr);$y++) {
							$tmp=explode("=",$tmparr[$y]);
							echo "<option value=\"".$tmp[0]."\" ";
							if($value[$k]==$tmp[0]) echo " selected";
							echo ">".$tmp[1]."</option>\n";
						}
						echo "</select>\n";
					}
					$k++;
					echo "	</td>\n";
				}
				echo "</tr>\n";
				echo "<TR>";
				echo "	<TD colspan=\"6\" align=center background=\"images/table_con_line.gif\"><img src=\"images/table_con_line.gif\" width=\"4\" height=\"1\" border=\"0\"></TD>\n";
				echo "</TR>\n";
			}
?>
				<TR>
					<TD colspan=6 background="images/table_top_line.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height=10></td>
			</tr>
			<tr>
				<td align="center"><a href="javascript:CheckForm('update');"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:CheckForm('clear');"><img src="images/botteon_bok.gif" width="124" height="38" border="0" hspace="2"></a></td>
			</tr>
			</form>
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
					<TD COLSPAN=3 width="100%" valign="top" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;"  class="menual_bg">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><p>�������ſ� ��Ŵ� ������ ������� �ʽ��ϴ�.</p></td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><p>���ΰԽ����� [���κ��� ���� ������]�� ��쿡�� ���θ��� �ݿ��˴ϴ�.</p></td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><p>���������ν� ��ũ�θ�ɾ�� �ҷ����� �Ϲ� �Խ����� �ؽ�Ʈ �Ӽ��� �ǹ��մϴ�.</p></td>
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
<?=$onload?>

<? INCLUDE "copyright.php"; ?>