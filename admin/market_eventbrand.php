<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "ma-2";
$MenuCode = "market";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

if(strlen($seachIdx)==0) {
	$seachIdx = "A";
}
?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
<!--
function SearchSubmit(seachIdxval) {
	form = document.form1;
	form.mode.value="";
	form.seachIdx.value = seachIdxval;
	form.submit();
}

function CodeProcessFun(brandselectedIndex,brandcode) {
	if(brandselectedIndex>-1) {
		document.form2.mode.value="";
		document.form2.code.value=brandcode;
		document.form2.target="ListFrame";
		document.form2.action="market_eventbrand.add.php";
		document.form2.submit();
	}
}
//-->
</script>

<STYLE type=text/css>
	#menuBar {}
	#contentDiv {WIDTH: 200;HEIGHT: 320;}
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
			<? include ("menu_market.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ���������� &gt; �̺�Ʈ/����ǰ ��� ���� &gt; <span class="2depth_select">�귣�庰 �̺�Ʈ ����</span></td>
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
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=mode value="">
			<input type=hidden name=seachIdx value="">
			<tr><td height="8"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/market_eventbrand_title.gif" border="0"></TD>
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
					<TD width="100%" class="notice_blue">�� �귣�庰 ������ ��ܿ� �̹��� �Ǵ� Html ������ ���� �̺�Ʈ�� ���� �Ͻ� �� �ֽ��ϴ�.</TD>
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
				<table cellpadding="0" cellspacing="0" width="760">
				<tr>
					<td width="242" valign="top" height="100%">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="232" height="100%" valign="top">
						<table cellpadding="0" cellspacing="0" width="242">
						<tr>
							<td bgcolor="white"><IMG SRC="images/product_totoabrand_title.gif" border="0"></td>
						</tr>
						<tr>
							<TD valign="top">
							<table border=0 cellpadding=0 cellspacing=0 width="100%">
							<tr>
								<td style="padding-bottom:3pt;">
								<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
								<TR>
									<TD><IMG SRC="images/distribute_01.gif"></TD>
									<TD background="images/distribute_02.gif"></TD>
									<TD><IMG SRC="images/distribute_03.gif"></TD>
								</TR>
								<TR>
									<TD background="images/distribute_04.gif"></TD>
									<TD width="100%" class="notice_blue">
									<table border=0 cellpadding=0 cellspacing=0 width="100%">
									<tr>
										<td colspan="2" style="padding:5px;padding-left:2px;padding-right:2px;">
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
										</tr>
										<tr align="center">
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
									</tr>
									<tr>
										<!-- ��ǰī�װ� ��� -->
										<td width="100%"><select name="up_brandlist" size="20" style="width:100%;" onchange="CodeProcessFun(this.selectedIndex,this.value);">
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
								}

								$result=mysql_query($sql,get_db_conn());
								while($row=mysql_fetch_object($result)) {
									$brandopt .= "<option value=\"".$row->bridx."\">".$row->brandname."</option>\n";
								}

								if(strlen($brandopt)>0) {
									$brandopt = "<option value=\"".$seachIdx."\">---- ".$seachIdx." �귣�� �ϰ� ���� ----</option>\n".$brandopt;
								}
								echo $brandopt;
							?>
										</select></td>
										<td width="30" align="center" nowrap style="line-height:21px;" valign="top">
										<table border=0 cellpadding=0 cellspacing=0 width="100%">
										<tr><td align="center" style="font-size:14px;line-height:21px;"><b><a href="javascript:SearchSubmit('��');"><span id="��">��</span></a></b></td></tr>
										<tr><td align="center" style="font-size:14px;line-height:21px;"><b><a href="javascript:SearchSubmit('��');"><span id="��">��</span></a></b></td></tr>
										<tr><td align="center" style="font-size:14px;line-height:21px;"><b><a href="javascript:SearchSubmit('��');"><span id="��">��</span></a></b></td></tr>
										<tr><td align="center" style="font-size:14px;line-height:21px;"><b><a href="javascript:SearchSubmit('��');"><span id="��">��</span></a></b></td></tr>
										<tr><td align="center" style="font-size:14px;line-height:21px;"><b><a href="javascript:SearchSubmit('��');"><span id="��">��</span></a></b></td></tr>
										<tr><td align="center" style="font-size:14px;line-height:21px;"><b><a href="javascript:SearchSubmit('��');"><span id="��">��</span></a></b></td></tr>
										<tr><td align="center" style="font-size:14px;line-height:21px;"><b><a href="javascript:SearchSubmit('��');"><span id="��">��</span></a></b></td></tr>
										<tr><td align="center" style="font-size:14px;line-height:21px;"><b><a href="javascript:SearchSubmit('��');"><span id="��">��</span></a></b></td></tr>
										<tr><td align="center" style="font-size:14px;line-height:21px;"><b><a href="javascript:SearchSubmit('��');"><span id="��">��</span></a></b></td></tr>
										<tr><td align="center" style="font-size:14px;line-height:21px;"><b><a href="javascript:SearchSubmit('��');"><span id="��">��</span></a></b></td></tr>
										<tr><td align="center" style="font-size:14px;line-height:21px;"><b><a href="javascript:SearchSubmit('��');"><span id="��">��</span></a></b></td></tr>
										<tr><td align="center" style="font-size:14px;line-height:21px;"><b><a href="javascript:SearchSubmit('��');"><span id="��">��</span></a></b></td></tr>
										<tr><td align="center" style="font-size:14px;line-height:21px;"><b><a href="javascript:SearchSubmit('��');"><span id="��">��</span></a></b></td></tr>
										<tr><td align="center" style="font-size:14px;line-height:21px;"><b><a href="javascript:SearchSubmit('��');"><span id="��">��</span></a></b></td></tr>
										<tr><td align="center" style="line-height:21px;"><b><a href="javascript:SearchSubmit('��Ÿ');"><span id="��Ÿ">��Ÿ</span></a></b></td></tr>
										</table>
										</td>
									</tr>
									</table>
									</TD>
									<TD background="images/distribute_07.gif"></TD>
								</TR>
								<TR>
									<TD><IMG SRC="images/distribute_08.gif"></TD>
									<TD background="images/distribute_09.gif"></TD>
									<TD><IMG SRC="images/distribute_10.gif"></TD>
								</TR>
								</TABLE>
								</td>
							</tr>
							</table>
							</TD>
						</tr>
						</table>
						</td>
					</tr>
					</table>
					</td>
					<td width="15"><img src="images/btn_next1.gif" width="25" height="25" border="0" hspace="5"><br></td>
					<td width="100%" valign="top" height="100%"><IFRAME name="ListFrame" src="market_eventbrand.add.php" width=100% height=300 frameborder=0 align=TOP scrolling="no" marginheight="0" marginwidth="0"></IFRAME></td>
				</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td height="20"></td>
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
						<td><span class="font_dotline">�귣�庰 �̺�Ʈ ����</span></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- �̹��� �Ǵ� Html ������ �Ͻø� �� �귣�� ����� �پ��ϰ� �ٹ̽� �� �ֽ��ϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- �귣�庰 �̺�Ʈ�� "��ǰ �귣�� ���ø�" ���ÿ��� ��µ˴ϴ�.<br>
						<b>&nbsp;&nbsp;</b><a href="javascript:parent.topframe.GoMenu(2,'design_blist.php');"><span class="font_blue">������ ���� > ���ø�-���� �� ī�װ� > ��ǰ �귣�� ���ø�</span></a></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- ���� ������ ���� "��ǰ �귣�� ȭ�� �ٹ̱�"���� �ش� ��ũ�θ� �̿��Ͻø� ����� �����մϴ�.<br>
						<b>&nbsp;&nbsp;</b><a href="javascript:parent.topframe.GoMenu(2,'design_eachblist.php');"><span class="font_blue">������ ���� > ����������-������ ���� > ��ǰ�귣�� ȭ�� �ٹ̱�</span></a></td>
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
			</form>
			<form name=form2 action="" method=post>
			<input type=hidden name=mode>
			<input type=hidden name=code>
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