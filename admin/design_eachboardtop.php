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
$board=$_POST["board"];
$body=$_POST["body"];
$added=$_POST["added"];


if($added=="Y") {
	$leftmenu="Y";
} else {
	$leftmenu="N";
}


$insertKey = "board";

$subject = '�Խ��� ���ȭ�� ������';

// ��� / ����
if ( $type=="store" OR $type=="restore" ) {
	$MSG = adminDesingBackup ( $type, $insertKey, $body, $subject, '', $board, $leftmenu );
	$onload="<script>alert(\"".$MSG."\");</script>";
}


if($type=="update" && strlen($body)>0 && strlen($board)>0) {

	$sql = "SELECT MAX(code) as maxcode FROM tbldesignnewpage WHERE type='board' ";
	$result=mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
	mysql_free_result($result);
	if(strlen($row->maxcode)==0 || $row->maxcode==NULL) {
		$maxcode="001";
	} else {
		$maxcode=(int)$row->maxcode+1;
		$maxcode=substr("00".$maxcode,-3);
	}


	$sql = "SELECT COUNT(*) as cnt FROM tbldesignnewpage ";
	$sql.= "WHERE type='board' AND filename='".$board."' ";
	$result=mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
	if($row->cnt==0) {
		$sql = "INSERT tbldesignnewpage SET ";
		$sql.= "type		= 'board', ";
		$sql.= "subject		= '�Խ��� ���ȭ�� ������', ";
		$sql.= "filename	= '".$board."', ";
		$sql.= "leftmenu	= '".$leftmenu."', ";
		$sql.= "body		= '".$body."', ";
		$sql.= "code		= '".$maxcode."' ";
		mysql_query($sql,get_db_conn());
	} else {
		$sql = "UPDATE tbldesignnewpage SET ";
		$sql.= "leftmenu	= '".$leftmenu."', ";
		$sql.= "body		= '".$body."' ";
		$sql.= "WHERE type='board' AND filename='".$board."' ";
		mysql_query($sql,get_db_conn());
	}
	mysql_free_result($result);
	$onload="<script>alert(\"�ش� �Խ��� ���ȭ�� ������ ������ �Ϸ�Ǿ����ϴ�.\");</script>";
} else if($type=="delete" && strlen($board)>0) {
	$sql = "DELETE FROM tbldesignnewpage WHERE type='board' AND filename='".$board."' ";
	mysql_query($sql,get_db_conn());
	$onload="<script>alert(\"�ش� �Խ��� ���ȭ�� ������ ������ �Ϸ�Ǿ����ϴ�.\");</script>";
} else if($type=="clear" && strlen($board)>0) {
	$body="";
	$sql = "SELECT body FROM tbldesigndefault WHERE type='board' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$body=$row->body;
	}
	mysql_free_result($result);
}

if($type!="clear") {
	$body="";
	if(strlen($board)>0) {
		$sql = "SELECT leftmenu,body FROM tbldesignnewpage ";
		$sql.= "WHERE type='board' AND filename='".$board."' ";
		$result = mysql_query($sql,get_db_conn());
		if($row=mysql_fetch_object($result)) {
			$added=$row->leftmenu;
			$body=$row->body;
		}
		mysql_free_result($result);
	}
	if(strlen($added)==0) $added="Y";
}

?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<SCRIPT LANGUAGE="JavaScript">
<!--
function CheckForm(type) {
	if(type=="update") {
		if(document.form1.blist.value.length==0) {
			alert("�Խ����� �����ϼ���.");
			document.form1.blist.focus();
			return;
		}
		if(document.form1.body.value.length==0) {
			alert("������ ������ �Է��ϼ���.");
			document.form1.body.focus();
			return;
		}
		document.form1.type.value=type;
		document.form1.board.value=document.form1.blist.value;
		document.form1.submit();
	} else if(type=="delete") {
		if(document.form1.blist.value.length==0) {
			alert("�Խ����� �����ϼ���.");
			document.form1.blist.focus();
			return;
		}
		if(confirm("�������� �����Ͻðڽ��ϱ�?")) {
			document.form1.type.value=type;
			document.form1.board.value=document.form1.blist.value;
			document.form1.submit();
		}
	} else if(type=="clear") {
		if(document.form1.blist.value.length==0) {
			alert("�Խ����� �����ϼ���.");
			document.form1.blist.focus();
			return;
		}
		alert("�⺻�� ���� �� [�����ϱ�]�� Ŭ���ϼ���. Ŭ�� �� �������� ����˴ϴ�.");
		document.form1.type.value=type;
		document.form1.board.value=document.form1.blist.value;
		document.form1.submit();
	}


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

	// �̸�����
	if(type=="preview") {
		if(document.form1.blist.value.length==0) {
			alert("�Խ����� �����ϼ���.");
			document.form1.blist.focus();
			return;
		}
		document.form1.type.value='<?=$insertKey?>';
		document.form1.target="preview";
		document.form1.action="designPreview.php";
		document.form1.submit();
		document.form1.target="";
		document.form1.action="<?=$_SERVER[PHP_SELF]?>";
	}

}

function change_page(val) {
	document.form1.type.value="change";
	document.form1.board.value=val;
	document.form1.submit();
}

//��ũ�� ����(�˾�)
function macroview(){
	window.open("http://www.getmall.co.kr/macro/pages/boardtop_macro.html","boardtop_macro","height=800,width=680,scrollbars=no");
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
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �����ΰ��� &gt; ����������-������ ���� &gt; <span class="2depth_select">�Խ��� ��� ȭ�� �ٹ̱�</span></td>
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
					<TD><IMG SRC="images/design_eachboardtop_title.gif" ALT=""></TD>
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
					<TD width="100%" class="notice_blue">�Խ��� ��� ȭ�� �������� �����Ӱ� ������ �Ͻ� �� �ֽ��ϴ�.</TD>
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
					<TD><IMG SRC="images/design_eachboardtop_stitle1.gif" WIDTH="250" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">
						&nbsp;&nbsp;<a href="javascript:macroview();"><img src="images/btn_macroview.gif" border="0" align="absmiddle" alt="" /></a>&nbsp;&nbsp;<a href="http://www.getmall.co.kr/macro/data/boardtop_design.zip"><img src="images/btn_defaultcode.gif" border="0" align="absmiddle" alt="" />
					</TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
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
					<TD width="100%" class="notice_blue">1) �Ŵ����� <b>��ũ�θ�ɾ�</b>�� �����Ͽ� ������ �ϼ���. - �Խ��� Ÿ��Ʋ�� ��ܰ˻� ������ ������ �����Դϴ�.<br>2) [�⺻������]+[�����ϱ�], [�����ϱ�]�ϸ� �⺻���� �����Ǵ� ���������� ����˴ϴ�.(��ܺκ��� ���ø��� ����)<br>3) �Խ��Ǹ���Ʈ ���ø� ���� : <a href="javascript:parent.topframe.GoMenu(7,'community_list.php');"><span class="font_blue">Ŀ�´�Ƽ > Ŀ�´�Ƽ ���� > ����� �Խ��� ����</font></span></TD>
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
			<tr><td height="3"></td></tr>
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=type>
			<input type=hidden name=board value="<?=$board?>">
			<tr>
				<td style="padding-top:3pt;">
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0 style="table-layout:fixed">
				<col width=139></col>
				<col width=></col>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�ش� �Խ��� ����</TD>
					<TD class="td_con1"><select name=blist onchange="change_page(options.value)" style="width:330" class="select">
						<option value="">�Խ����� �����ϼ���.</option>
<?
			$sql = "SELECT board,board_name FROM tblboardadmin ";
			$sql.= "ORDER BY date DESC ";
			$result=mysql_query($sql,get_db_conn());
			$i=0;
			unset($arr_board);
			while($row=mysql_fetch_object($result)) {
				$i++;
				echo "<option value=\"".$row->board."\" ";
				if($board==$row->board) echo "selected";
				echo ">".$i.".".$row->board_name."</option>\n";
				$arr_board[]=$row;
			}
			mysql_free_result($result);
?>
						</select></TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD colspan="2"><textarea name=body style="WIDTH: 100%; HEIGHT: 300px" class="textarea"><?=htmlspecialchars($body)?></textarea></TD>
				</TR>
				<TR>
					<TD colspan="2" height="24"><input type=checkbox name=added value="Y" <?if($added=="Y")echo"checked";?> style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;"> <b><span class="font_orange">�����ϱ� üũ</span>(üũ�ؾ߸� �������� ����˴ϴ�. ��üũ�� �ҽ��� �����ǰ� ������ ���� �ʽ��ϴ�.)</b></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=10></td></tr>
			<tr>
				<td align="center"><a href="javascript:CheckForm('update');"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:CheckForm('clear');"><img src="images/botteon_bok.gif" width="124" height="38" border="0" hspace="2"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:CheckForm('delete');"><img src="images/botteon_del.gif" width="113" height="38" border="0" hspace="0"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:CheckForm('preview');"><img src="images/botteon_prev.gif" width="113" height="38" border="0" hspace="2"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:CheckForm('store');"><img src="images/botteon_store.gif" border="0" hspace="2" alt="����ϱ�"></a>&nbsp;&nbsp;&nbsp;<a href="javascript:CheckForm('restore');"><img src="images/botteon_restore.gif" border="0" hspace="2" alt="��������ϱ�"></a></td>
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
					<TD COLSPAN=3 width="100%" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;"  class="menual_bg">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><p class="LIPoint"><B><span class="font_orange">�Խ��� ��� ��ũ�θ�ɾ�</span></B>(�ش� ��ũ�θ�ɾ�� �ٸ� ������ ������ �۾��� ����� �Ұ�����)</p></td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><p>&nbsp;</p></td>
						<td >
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
						<col width=150></col>
						<col width=></col>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<TR>
							<TD class="table_cell" align=right style="padding-right:15">[BOARDGROUP]</TD>
							<TD class="td_con1" style="padding-left:5px;">�Խ��� ��� (SELECT �ڽ�)</TD>
						</TR>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<TR>
							<TD class="table_cell" align=right style="padding-right:15px;">[BOARDNAME]</TD>
							<TD class="td_con1" style="padding-left:5px;">�Խ��� ����</TD>
						</TR>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						</TABLE>
						</td>
					</tr>
					<tr>
						<td width="20" colspan="2"><p>&nbsp;</p></td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><p>&nbsp;<B>�Խ��� URL����Ʈ</B></p></td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><p>&nbsp;</p></td>
						<td >
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
						<col width=150></col>
						<col width=></col>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
<?
		for($i=0;$i<count($arr_board);$i++) {
			if($i == count($arr_board)-1) {
?>
						<tr>
							<TD class="table_cell" style="padding-right:15px;" align=right><?=$arr_board[$i]->board_name?></td>
							<TD class="td_con1" style="padding-left:5px;">&lt;a href="/<?=RootPath.BoardDir?>board.php?board=<?=$arr_board[$i]->board?>"><?=$arr_board[$i]->board_name?>&lt;/a></td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
<?
			} else {
?>
						<tr>
							<TD class="table_cell" style="padding-right:15px;" align=right><?=$arr_board[$i]->board_name?></td>
							<TD class="td_con1" style="padding-left:10px;">&lt;a href="/<?=RootPath.BoardDir?>board.php?board=<?=$arr_board[$i]->board?>"><?=$arr_board[$i]->board_name?>&lt;/a></td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
<?
			}
		}
		if(count($arr_board)==0) {
			echo "<tr><TD colspan=\"2\" align=\"center\" class=\"td_con1\" style=\"padding-left:10px;\"><B>��ϵ� Ŀ�´�Ƽ �������� �������� �ʽ��ϴ�.</B></td></tr>\n";
			echo "<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>";
		}
?>
						</TABLE>
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
</table>
<script>
function prevPage(){
	if(document.form1.body.value.length==0) {
		alert("������ ������ �Է��ϼ���.");
		document.form1.body.focus();
		return;
	}

	f = document.prevForm;
	f.mode.value = 'board';
	f.code.value = document.form1.body.value;
	f.submit();
}
</script>

<form name="prevForm" method="post" action="design_prev_post.php" target="_blank">
	<input type="hidden" name="code">
	<input type="hidden" name="mode">
</form>


<?=$onload?>

<? INCLUDE "copyright.php"; ?>