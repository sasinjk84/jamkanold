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

function WriteEngine($engine, $file) {
	$filename = DirPath.DataDir."shopimages/etc/".$file;
	$success = false;

	if($fp=fopen($filename, "w")) {
		fputs($fp, serialize($engine));
		fclose($fp);
		$success=true;
	}
	return $success;
}

function ReadEngine($file) {
	$filename = DirPath.DataDir."shopimages/etc/".$file;

	if(file_exists($filename)==true) {
		if($fp=@fopen($filename, "r")) {
			$szdata=fread($fp, filesize($filename));
			fclose($fp);
			$engine=unserialize($szdata);
		}
	}
	return $engine;
}

$type=$_POST["type"];
$engine=$_POST["engine"];

if($type=="update") {
	$success = WriteEngine(&$engine, "engineinfo.db");

	if($success) {
		$onload="<script>alert('���������� ���� �ƽ��ϴ�.');</script>";
	} else {
		$onload="<script>alert('����ġ ���� ������ ���ؼ� ������� �� �߽��ϴ�.');history.go(-1);</script>";
	}
}

$engineval = ReadEngine("engineinfo.db");
?>

<? INCLUDE "header.php"; ?>
<script>try {parent.topframe.ChangeMenuImg(7);}catch(e){}</script>
<script type="text/javascript" src="lib.js.php"></script>
<script type="text/javascript" src="calendar.js.php"></script>
<script language="JavaScript">

function CheckForm(type) {
	if(confirm("���ݺ������� ���� ������ �����ϰڽ��ϱ�?"))
	{
		document.form1.type.value=type;
		document.form1.submit();
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
			<? include ("menu_market.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ���������� &gt; <span class="2depth_select">���ݺ������� ����</span></td>
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
			<tr><td height="8"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/market_enginepage_title.gif" border="0"></TD>
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
					<TD width="100%" class="notice_blue">���ݺ� ���� ��ü�� ������ ��ǰ ���� �������� �����մϴ�.</TD>
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
					<TD><IMG SRC="images/market_enginepage_stitle1.gif" border="0"></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height=3></td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/distribute_01.gif"></TD>
					<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
					<TD><IMG SRC="images/distribute_03.gif"></TD>
				</TR>
				<TR>
					<TD background="images/distribute_04.gif"></TD>
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
					<TD width="100%" class="notice_blue">���ݺ� ���� ��ü�� ������ �������� ������ �ּ���.</TD>
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
			<tr><td height=3></td></tr>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<col width=20></col>
				<col width=30></col>
				<col width=150></col>
				<col width=></col>
				<col width=80></col>
				<TR>
					<TD colspan=5 background="images/table_top_line.gif"></TD>
				</TR>
				<TR align="center">
					<TD class="table_cell">No</TD>
					<TD class="table_cell1">���</TD>
					<TD class="table_cell1">���ݺ� ��ü��</TD>
					<TD class="table_cell1">���ݺ� ������ �ּ�</TD>
					<TD class="table_cell1">�̸�����</TD>
				</TR>
				<TR>
					<TD colspan=5 background="images/table_con_line.gif"></TD>
				</TR>
<?
				$colspan=5;
				
				$engine_unique = array("omi","naver","naversub","nawayo","yahoo","danawae","danawap","enuri","mymargin","bestbuyer","yavis","shopbinder","linkprice","plusmall","gaenawa");
				$engine_data = array(
				"����"						=> "http://".$shopurl."shopping/omi_ufo.php",
				"���̹�(��ü)"				=> "http://".$shopurl."shopping/naver.php",
				"���̹�(���)"				=> "http://".$shopurl."shopping/naver_sub.php",
				"���Ϳ�"					=> "http://".$shopurl."shopping/nawayo.php",
				"����"						=> "http://".$shopurl."shopping/yahoo.php",
				"�ٳ���-����,����"		=> "http://".$shopurl."shopping/danawa_elec.php",
				"�ٳ���-PC"					=> "http://".$shopurl."shopping/danawa_pc.php",
				"������"					=> "http://".$shopurl."shopping/enuri.php",
				"���̸���"					=> "http://".$shopurl."shopping/mymargin.php",
				"����Ʈ���̾�"				=> "http://".$shopurl."shopping/bestbuyer.php",
				"�ߺ�"					=> "http://".$shopurl."shopping/yavis.php",
				"�����δ�"					=> "http://".$shopurl."shopping/shopbinder.php",
				"��ũ�����̽�"				=> "http://".$shopurl."shopping/linkprice.php",
				"�÷�����"					=> "http://".$shopurl."shopping/plusmall.php",
				"������(�ְ�)"				=> "http://".$shopurl."shopping/gaenawa.php"
				);


				$cnt=0;
				while(list($key, $value) = each($engine_data)) {
					echo "<tr align=\"center\">\n";
					echo "	<td class=\"td_con2\">".($cnt+1)."</td>\n";
					echo "	<td class=\"td_con1\"><input type=\"checkbox\" name=\"engine[".$engine_unique[$cnt]."]\" value=\"checked\" ".$engineval[$engine_unique[$cnt]]."></td>\n";
					echo "	<td class=\"td_con1\">".$key."</td>\n";
					echo "	<td class=\"td_con1\" align=\"left\" style=\"".(strlen($engineval[$engine_unique[$cnt]])>0?"color:#00A0D5;font-weight:bold;":"")."\">&nbsp;&nbsp;".$value."</td>\n";
					echo "	<td class=\"td_con1\">&nbsp;".(strlen($engineval[$engine_unique[$cnt]])>0?"<a href=\"".$value."\" target=\"_blank\" style=\"".(strlen($engineval[$engine_unique[$cnt]])>0?"color:#00A0D5;font-weight:bold;":"")."\">[�̸�����]</a>":"")."</td>\n";
					echo "</tr>\n";
					echo "<TR>\n";
					echo "	<TD colspan=".$colspan." background=\"images/table_con_line.gif\"></TD>\n";
					echo "</TR>\n";
					$cnt++;
				}
?>
				<TR>
					<TD background="images/table_top_line.gif" colspan="5"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height="20"></td>
			</tr>
			<tr>
				<td align=center><a href="javascript:CheckForm('update');"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a></td>
			</tr>
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
					<TD background="images/manual_left1.gif"></TD>
					<TD COLSPAN=3 width="100%" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;" class="menual_bg">
					<table cellpadding="0" cellspacing="0" width="100%">
					<col width=20></col>
					<col width=></col>
					<tr>
						<td align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><span class="font_dotline">���ݺ������� ����</span></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- ��뿡 üũ�� ���ݺ� ��ü �������� ����� �����մϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- �Ϻ� ���ݺ� �������� ��� �̸����� ����Ÿ�� �Ϻ� ����� �ȵǴ� �κ��� �ֽ��ϴ�. ���� �̿���� �����մϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- ���ݺ� ���񽺴� �ش� ��ü�� �߰��� ����� �ؾ߸� ���󼭺񽺰� �̷����ϴ�.</td>
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