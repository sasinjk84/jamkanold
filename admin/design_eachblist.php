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

if(strlen($seachIdx)==0) {
	$seachIdx = "��ü";
}
?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function SearchSubmit(seachIdxval) {
	form = document.form1;
	form.mode.value="";
	form.seachIdx.value = seachIdxval;
	form.submit();
}

function design_preview(design) {
	document.all["preview_img"].src="images/sample/brand"+design+".gif";
}

function CodeProcessFun(brandselectedIndex,brandcode) {
	if(brandselectedIndex>-1) {
		document.form2.mode.value="";
		document.form2.code.value=brandcode;
		document.form2.target="MainPrdtFrame";
		document.form2.action="design_eachblist.list.php";
		document.form2.submit();
	}
}
</script>
<STYLE type=text/css>
	#menuBar {}
	#contentDiv {WIDTH: 300;HEIGHT: 250;}
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
			<? include ("menu_design.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �����ΰ��� &gt; ����������-������ ���� &gt; <span class="2depth_select">��ǰ�귣�� ȭ�� �ٹ̱�</span></td>
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
					<TD><IMG SRC="images/design_productbrand_title.gif" border="0"></TD>
					</tr>
<tr>
<TD width="100%" background="images/title_bg.gif" height="21"></TD>
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
					<TD width="100%" class="notice_blue">��ǰ �귣�庰 ȭ�� �������� �����Ӱ� ������ �Ͻ� �� �ֽ��ϴ�.</TD>
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
					<TD><IMG SRC="images/design_productbrand_stitle.gif" border="0"></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=mode>
			<input type=hidden name=seachIdx value="">
			<tr>
				<td style="padding-top:3pt;">
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<col width="400"></col>
				<col width="30"></col>
				<col></col>
				<TR>
					<TD background="images/table_top_line.gif" colspan="3"></TD>
				</TR>
				<TR>
					<TD class="table_cell" align="center"><b>��ü �귣��</b></td>
					<TD class="table_cell1" align="center">&nbsp;</TD>
					<TD class="table_cell1" align="center" background="images/blueline_bg.gif"><span class="font_blue">���� ��ǰ �귣�庰 ���ø�</span></TD>
				</TR>
				<TR>
					<TD colspan="3" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD valign="top" style="padding:3pt;">
					<table border=0 cellpadding=0 cellspacing=0 width="100%">
					<tr>
						<td style="padding:5px;padding-left:2px;padding-right:2px;">
						<table border=0 cellpadding=0 cellspacing=0 width="100%">
						<tr align="center">
							<td><b><a href="javascript:SearchSubmit('A');"><span id="A">A</span></a></b></td>
							<td><b><a href="javascript:SearchSubmit('B');"><span id="B">B</span></a></b></td>
							<td><b><a href="javascript:SearchSubmit('C');"><span id="C">C</span></a></b></td>
							<td><b><a href="javascript:SearchSubmit('D');"><span id="D">D</span></a></b></td>
							<td><b><a href="javascript:SearchSubmit('E');"><span id="E">E</span></a></b></td>
							<td><b><a href="javascript:SearchSubmit('F');"><span id="F">F</span></a></b></td>
							<td><b><a href="javascript:SearchSubmit('G');"><span id="G">G</span></a></b></td>
							<td><b><a href="javascript:SearchSubmit('H');"><span id="H">H</span></a></b></td>
							<td><b><a href="javascript:SearchSubmit('I');"><span id="I">I</span></a></b></td>
							<td><b><a href="javascript:SearchSubmit('J');"><span id="J">J</span></a></b></td>
							<td><b><a href="javascript:SearchSubmit('K');"><span id="K">K</span></a></b></td>
							<td><b><a href="javascript:SearchSubmit('L');"><span id="L">L</span></a></b></td>
							<td><b><a href="javascript:SearchSubmit('M');"><span id="M">M</span></a></b></td>
							<td><b><a href="javascript:SearchSubmit('N');"><span id="N">N</span></a></b></td>
							<td><b><a href="javascript:SearchSubmit('O');"><span id="O">O</span></a></b></td>
							<td><b><a href="javascript:SearchSubmit('P');"><span id="P">P</span></a></b></td>
							<td><b><a href="javascript:SearchSubmit('Q');"><span id="Q">Q</span></a></b></td>
							<td><b><a href="javascript:SearchSubmit('R');"><span id="R">R</span></a></b></td>
							<td><b><a href="javascript:SearchSubmit('S');"><span id="S">S</span></a></b></td>
							<td><b><a href="javascript:SearchSubmit('T');"><span id="T">T</span></a></b></td>
							<td><b><a href="javascript:SearchSubmit('U');"><span id="U">U</span></a></b></td>
							<td><b><a href="javascript:SearchSubmit('V');"><span id="V">V</span></a></b></td>
							<td><b><a href="javascript:SearchSubmit('W');"><span id="W">W</span></a></b></td>
							<td><b><a href="javascript:SearchSubmit('X');"><span id="X">X</span></a></b></td>
							<td><b><a href="javascript:SearchSubmit('Y');"><span id="Y">Y</span></a></b></td>
							<td><b><a href="javascript:SearchSubmit('Z');"><span id="Z">Z</span></a></b></td>
						</TR>
						</table>
						</td>
						<td width="40" align="center" nowrap><b><a href="javascript:SearchSubmit('��ü');"><span id="��ü">��ü</span></a></b></td>
					</tr>
					<tr>
						<!-- ��ǰ�귣�� ��� -->
						<td width="100%"><select name="up_brandlist" size="16" style="width:100%;" onchange="CodeProcessFun(this.selectedIndex,this.value);">
					<?
						$sql = "SELECT * FROM tblproductbrand ";
						if(ereg("^[��-��]", $seachIdx)) {
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
						} else if(ereg("^[A-Z]", $seachIdx)) {
							$sql.= "WHERE brandname LIKE '".$seachIdx."%' OR brandname LIKE '".strtolower($seachIdx)."%' ";	
							$sql.= "ORDER BY brandname ";
						} else {
							$sql.= "ORDER BY brandname ";
						}
						$result=mysql_query($sql,get_db_conn());
						while($row=mysql_fetch_object($result)) {
							$brandopt .= "<option value=\"".$row->bridx."\">".$row->brandname."</option>\n";
						}

						if(strlen($brandopt)>0 && $seachIdx == "��ü") {
							$brandopt = "<option value=\"".$seachIdx."\">------------ ".$seachIdx." �귣�� �ϰ� ���������� ------------</option>\n".$brandopt;
						}
						echo $brandopt;
					?>
						</select></td>
						<td width="40" align="center" nowrap valign="top">
						<table border=0 cellpadding=0 cellspacing=0 width="100%">
						<tr><td align="center"><b><a href="javascript:SearchSubmit('��');"><span id="��">��</span></a></b></td></tr>
						<tr><td align="center"><b><a href="javascript:SearchSubmit('��');"><span id="��">��</span></a></b></td></tr>
						<tr><td align="center"><b><a href="javascript:SearchSubmit('��');"><span id="��">��</span></a></b></td></tr>
						<tr><td align="center"><b><a href="javascript:SearchSubmit('��');"><span id="��">��</span></a></b></td></tr>
						<tr><td align="center"><b><a href="javascript:SearchSubmit('��');"><span id="��">��</span></a></b></td></tr>
						<tr><td align="center"><b><a href="javascript:SearchSubmit('��');"><span id="��">��</span></a></b></td></tr>
						<tr><td align="center"><b><a href="javascript:SearchSubmit('��');"><span id="��">��</span></a></b></td></tr>
						<tr><td align="center"><b><a href="javascript:SearchSubmit('��');"><span id="��">��</span></a></b></td></tr>
						<tr><td align="center"><b><a href="javascript:SearchSubmit('��');"><span id="��">��</span></a></b></td></tr>
						<tr><td align="center"><b><a href="javascript:SearchSubmit('��');"><span id="��">��</span></a></b></td></tr>
						<tr><td align="center"><b><a href="javascript:SearchSubmit('��');"><span id="��">��</span></a></b></td></tr>
						<tr><td align="center"><b><a href="javascript:SearchSubmit('��');"><span id="��">��</span></a></b></td></tr>
						<tr><td align="center"><b><a href="javascript:SearchSubmit('��');"><span id="��">��</span></a></b></td></tr>
						<tr><td align="center"><b><a href="javascript:SearchSubmit('��');"><span id="��">��</span></a></b></td></tr>
						<tr><td align="center"><b><a href="javascript:SearchSubmit('��Ÿ');"><span id="��Ÿ">��Ÿ</span></a></b></td></tr>
						</table>
						</td>
					</tr>
					</table>
					</TD>
					<TD class="td_con1" align="center"><img src="images/btn_next1.gif" border="0" hspace="5"></TD>
					<TD class="td_con1" align="center" style="padding:5pt;">&nbsp;<img id="preview_img" width="200" height="214" style="display:none" border="0" vspace="0" class="imgline"><br><p align="left"><b>&quot;��� �귣�� �ϰ� ����������&quot; </b>�� ������ ��� ���� ������ ������� �귣�带 ������ ���ø��� ����ϴ� ��� �귣�尡 �������������� �ϰ� ����˴ϴ�.</TD>
				</TR>
				<TR>
					<TD background="images/table_top_line.gif" colspan="3"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height="20"></td>
			</tr>
			<tr>
				<td><IFRAME name="MainPrdtFrame" src="design_eachblist.list.php" width=100% height=350 frameborder=0 align=TOP scrolling="no" marginheight="0" marginwidth="0"></IFRAME></td>
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
					<TD COLSPAN=3 width="100%" valign="top" class=menual_bg style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;"  class="menual_bg">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><p class="LIPoint"><B><span class="font_orange">��ǰ�귣�� ȭ�� ��ũ�θ�ɾ�</span></B>(�ش� ��ũ�θ�ɾ�� �ٸ� ������ ������ �۾��� ����� �Ұ�����)</p></td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><p>&nbsp;</p></td>
						<td >
						<table border=0 cellpadding=0 cellspacing=0 width=100%>
						<col width=150></col>
						<col width=></col>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BRANDNAME]</td>
							<td class=td_con1 style="padding-left:5;">
							���� �귣��/ī�װ���
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BRANDNAVI??????_??????]</td>
							<td class=td_con1 style="padding-left:5;">
							�귣�� �׺���̼� 
									<br><img width=10 height=0>
									<FONT class=font_orange>��?????? : Ȩ �Ǵ� ���� �귣�� ����</FONT> - <FONT COLOR="red">"#"����</FONT>
									<br><img width=10 height=0>
									<FONT class=font_orange>��?????? : ���� �귣�� �Ǵ� ���� �귣�尡 ���� ī�װ� ����</FONT> - <FONT COLOR="red">"#"����</FONT>
									<br>
									<FONT class=font_blue>��) [BRANDNAVI] or [BRANDNAVI000000_FF0000]</FONT>
							</td>
						</tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[CLIPCOPY]</td>
							<td class=td_con1 style="padding-left:5;">
							�����ּ� ���� ��ư <FONT class=font_blue>(��:&lt;a href=[CLIPCOPY]>�ּҺ���&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BRANDEVENT]</td>
							<td class=td_con1 style="padding-left:5;">
							�귣�庰 �̺�Ʈ �̹���/html
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BRANDGROUP]</td>
							<td class=td_con1 style="padding-left:5;">
							��ǰ �귣�� ī�װ� �׷�
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right bgcolor=#E9A74E style="padding-right:15">��ǰ �귣�� ī�װ� �׷� ���� ��Ÿ�� ����</td>
							<td class=td_con1 bgcolor=#FEEEE2 style="padding-left:5;">
										<img width=10 height=0>
										<FONT class=font_orange>#group1_td - ����ī�װ� TD ��Ÿ�� ���� (������ �� ��׶����÷�)</FONT>
										<br><img width=100 height=0>
										<FONT class=font_blue>��) #group1_td { background-color:#E6E6E6;width:25%; }</FONT>
										<br><img width=0 height=7><br><img width=10 height=0>
										<FONT class=font_orange>#group2_td - ����ī�װ� TD ��Ÿ�� ���� (������ �� ��׶����÷�)</FONT>
										<br><img width=100 height=0>
										<FONT class=font_blue>��) #group2_td { background-color:#EFEFEF; }</FONT>
										<br><img width=0 height=7><br><img width=10 height=0>
										<FONT class=font_orange>#group_line - �����׷�� �����׷� ������ ���ζ��� �� ��Ÿ�� ����</FONT>
										<br><img width=100 height=0>
										<FONT class=font_blue>��) #group_line { background-color:#FFFFFF;height:1px; }</FONT>
				<pre style="line-height:15px">
<B>[��� ��]</B> - ���� ������ �Ʒ��� ���� �����Ͻø� �˴ϴ�.

<FONT class=font_blue>&lt;style>
  #group1_td { background-color:#E6E6E6;width:25%; }
  #group2_td { background-color:#EFEFEF; }
  #group_line { background-color:#FFFFFF;height:1px; }
&lt;/style></FONT></pre>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[TOTAL]</td>
							<td class=td_con1 style="padding-left:5;">
							�� ��ǰ�� <FONT class=font_blue>(��:�� [TOTAL]��)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[SORTPRODUCTUP]</td>
							<td class=td_con1 style="padding-left:5;">
							������ �������� ����  <FONT class=font_blue>(��:&lt;a href=[SORTPRODUCTUP]>���������&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[SORTPRODUCTDN]</td>
							<td class=td_con1 style="padding-left:5;">
							������ �������� ���� <FONT class=font_blue>(��:&lt;a href=[SORTPRODUCTDN]>���������&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[SORTNAMEUP]</td>
							<td class=td_con1 style="padding-left:5;">
							��ǰ�� �������� ���� <FONT class=font_blue>(��:&lt;a href=[SORTNAMEUP]>��ǰ�����&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[SORTNAMEDN]</td>
							<td class=td_con1 style="padding-left:5;">
							��ǰ�� �������� ���� <FONT class=font_blue>(��:&lt;a href=[SORTNAMEDN]>��ǰ�����&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[SORTPRICEUP]</td>
							<td class=td_con1 style="padding-left:5;">
							���� ��ǰ���ݼ� <FONT class=font_blue>(��:&lt;a href=[SORTPRICEUP]>���ݼ���&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[SORTPRICEDN]</td>
							<td class=td_con1 style="padding-left:5;">
							���� ��ǰ���ݼ� <FONT class=font_blue>(��:&lt;a href=[SORTPRICEDN]>���ݼ���&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[SORTRESERVEUP]</td>
							<td class=td_con1 style="padding-left:5;">
							���� �����ݼ� <FONT class=font_blue>(��:&lt;a href=[SORTRESERVEUP]>�����ݼ���&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[SORTRESERVEDN]</td>
							<td class=td_con1 style="padding-left:5;">
							���� �����ݼ� <FONT class=font_blue>(��:&lt;a href=[SORTRESERVEDN]>�����ݼ���&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>

						<tr>
							<td class=table_cell align=right style="padding-right:15">[ONNEW]</td>
							<td class=td_con1 style="padding-left:5;">
								�űԵ�� ��ǰ�� ���� ǥ�� <FONT class=font_blue>(class="sortOn", /lib/style.php ���Ͽ��� css ����)</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ONBEST]</td>
							<td class=td_con1 style="padding-left:5;">
								�α��ǰ�� ���� ǥ�� <FONT class=font_blue>(class="sortOn", /lib/style.php ���Ͽ��� css ����)</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ONPRICEUP]</td>
							<td class=td_con1 style="padding-left:5;">
								���� ���ݼ� ���� ǥ�� <FONT class=font_blue>(class="sortOn", /lib/style.php ���Ͽ��� css ����)</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ONPRICEDN]</td>
							<td class=td_con1 style="padding-left:5;">
								���� ���ݼ� ���� ǥ�� <FONT class=font_blue>(class="sortOn", /lib/style.php ���Ͽ��� css ����)</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ONRESERVEDN]</td>
							<td class=td_con1 style="padding-left:5;">
								�����ݼ� ���� ǥ�� <FONT class=font_blue>(class="sortOn", /lib/style.php ���Ͽ��� css ����)</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[LISTSELECT]</td>
							<td class=td_con1 style="padding-left:5;">
								��ǰ��°��� ����
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>

						<tr>
							<td class=table_cell align=right style="padding-right:15">[PAGE]</td>
							<td class=td_con1 style="padding-left:5;">
							������ ǥ��
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[PRLIST1??]</td>
							<td class=td_con1 style="padding-left:5;">
							��ǰ��� - �̹���A��
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ���κ� ��ǰ����(1~8)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��������� ������ �Ұ��� �����Է�(1-8)</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[PRLIST2??]</td>
							<td class=td_con1 style="padding-left:5;">
							��ǰ��� - �̹���B��
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ���κ� ��ǰ����(1~8)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��������� ������ �Ұ��� �����Է�(1-8)</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[PRLIST????????_??]</td>
							<td class=td_con1 style="padding-left:5;">
							��ǰ��� - �̹���A��/�̹���B��
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ���� ������ ��ǰ��� ���� (1:�̹���A��, 2:�̹���B��)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ���κ� ��ǰ����(1~8)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��������� ������ �Ұ��� �����Է�(1-8)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��ǰ ������ ���ζ��� ǥ�ÿ���(Y/N/L)</FONT> (L�� ��ǰ�� ���߾� ��� ǥ�õ�)
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��ǰ ������ ���ζ��� ǥ�ÿ���(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��ǰ ���߰��� ǥ�ÿ���(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��ǰ ������ ǥ�ÿ���(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��ǰ �±� ǥ�ð���(0-9) : 0�� ��� ǥ�þ���</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>_?? : ��ǰ����(���Ʒ�) ���� �ִ� 99�ȼ� (���Է½� 5�ȼ�)</FONT>
										<br>
										<FONT class=font_blue>��) [PRLIST142NNYN2_10], [PRLIST222LYYY2_5]</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[PRLIST3??]</td>
							<td class=td_con1 style="padding-left:5;">
							��ǰ��� - ����Ʈ��
										<br><img width=10 height=0>
										<FONT class=font_orange>?? : ��ǰ��� �������� (01~20)</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[PRLIST3???????]</td>
							<td class=td_con1 style="padding-left:5;">
							��ǰ��� - ����Ʈ��
										<br><img width=10 height=0>
										<FONT class=font_orange>?? : ��ǰ �������� (01~20)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��ǰ �̹��� ǥ�ÿ��� (Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��ǰ ������ ǥ�ÿ��� (Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��ǰ ���߰��� ǥ�ÿ���(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��ǰ ������ ǥ�ÿ���(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��ǰ �±� ǥ�ð���(0-9) : 0�� ��� ǥ�þ���</FONT>
										<br>
										<FONT class=font_blue>��) [PRLIST304YYYY4]</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[PRLIST4??_??]</td>
							<td class=td_con1 style="padding-left:5;">
							��ǰ��� - ����������
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ���κ� ��ǰ����(2~4)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : ��������� ������ �Ұ��� �����Է�(1~8)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>_?? : ��ǰ����(���Ʒ�) ���� �ִ� 99�ȼ� (���Է½� 5�ȼ�)</FONT>
										<br>
										<FONT class=font_blue>��) [PRLIST423_5]</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right bgcolor=#E9A74E style="padding-right:15">��ǰ��� ��Ÿ�� ����</td>
							<td class=td_con1 bgcolor=#FEEEE2 style="padding-left:5;">
										<img width=15 height=0><FONT class=font_orange>#prlist_colline - �̹���/����Ʈ���� ���ζ��� �� ��Ÿ�� ����</FONT>
										<br><img width=100 height=0>
										<FONT class=font_blue>��) #prlist_colline { background-color:#f4f4f4;height:1px; }</FONT>
										<br><img width=0 height=7><br><img width=10 height=0>
										<FONT class=font_orange>#prlist_colline - �̹���/����Ʈ���� ���ζ��� �� ��Ÿ�� ����</FONT>
										<br><img width=100 height=0>
										<FONT class=font_blue>��) #prlist_rowline { background-color:#f4f4f4;width:1px; }</FONT>
							<pre style="line-height:15px">
<B>[��� ��]</B> - ���� ������ �Ʒ��� ���� �����Ͻø� �˴ϴ�.
<FONT class=font_blue>&lt;style>
  #prlist_colline { background-color:#f4f4f4;height:1px; }
  #prlist_rowline { background-color:#f4f4f4;width:1px; }
&lt;/style></FONT></pre>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
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
<form name=form2 action="" method=post>
<input type=hidden name=mode>
<input type=hidden name=code>
</form>
</table>
<script language="javascript">
<!--
<?
	if(strlen($seachIdx)>0) {
		echo "document.getElementById(\"$seachIdx\").style.color=\"#FF4C00\";";
	} else {
		echo "document.getElementById(\"TTL\").style.color=\"#FF4C00\";";
	}
?>
//-->
</script>
<?=$onload?>

<? INCLUDE "copyright.php"; ?>