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

$maxcnt=10;
$eventpopup = array("U","001","002","003","004");

$type=$_POST["type"];
$num=$_POST["num"];
$start_date=$_POST["start_date"];
$end_date=$_POST["end_date"];
$design=$_POST["design"];
$x_to=$_POST["x_to"];
$y_to=$_POST["y_to"];
$x_size=$_POST["x_size"];
$y_size=$_POST["y_size"];
$scroll_yn=$_POST["scroll_yn"];
$frame_type=$_POST["frame_type"];
$cookietime=$_POST["cookietime"];
$title=$_POST["title"];
$content=$_POST["content"];

$in_start = ereg_replace("-","",$start_date);
$in_end = ereg_replace("-","",$end_date);
if($type=="insert") {
	$sql = "SELECT COUNT(*) as cnt, COUNT(IF(frame_type='2',1,NULL)) as cnt2 FROM tbleventpopup ";
	$result = mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
	mysql_free_result($result);
	$contentSql = "SELECT content FROM tbleventpopup WHERE num = '".$num."' ";
	$contentResult = mysql_query($contentSql, get_db_conn());
	$contentNums = mysql_num_rows($contentResult);
	$contentRow = mysql_fetch_object($contentResult);
	
	if($contentNums > 0){
		$content = editorImsgeUrlSolv($content,$contentRow->content);
		mysql_free_result($contentResult);
	}
	if($row->cnt<$maxcnt) {
		if($frame_type==2 && $row->cnt2>=1) {
			$onload="<script>alert('���̾� Ÿ���� �˾�â�� 1���� ��� �����մϴ�.');</script>";
		} else {
			$sql = "INSERT tbleventpopup SET ";
			$sql.= "start_date	= '".$in_start."', ";
			$sql.= "end_date	= '".$in_end."', ";
			$sql.= "reg_date	= '".date("YmdHis")."', ";
			$sql.= "design		= '".$design."', ";
			$sql.= "x_size		= '".$x_size."', ";
			$sql.= "y_size		= '".$y_size."', ";
			$sql.= "x_to		= '".$x_to."', ";
			$sql.= "y_to		= '".$y_to."', ";
			$sql.= "scroll_yn	= '".$scroll_yn."', ";
			$sql.= "frame_type	= '".$frame_type."', ";
			$sql.= "cookietime	= '".$cookietime."', ";
			$sql.= "title		= '".$title."', ";
			$sql.= "content		= '".$content."' ";
			mysql_query($sql,get_db_conn());
			$onload="<script>alert('�˾�â ����� �Ϸ�Ǿ����ϴ�.');</script>";
			unset($type);
			unset($start_date); unset($end_date); unset($design); unset($x_size); unset($y_size); unset($x_to);
			unset($y_to); unset($scroll_yn); unset($frame_type); unset($cookietime); unset($title); unset($content);
		}
	} else {
		$onload="<script>alert('�˾�â ����� �ִ� ".$maxcnt."�� ���� ��� �����մϴ�.');</script>";
	}
} else if (($type=="modify_result" || $type=="modify") && strlen($num)>0) {
	$sql = "SELECT * FROM tbleventpopup WHERE num = '".$num."' ";
	$result = mysql_query($sql,get_db_conn());

	if($row=mysql_fetch_object($result)) {
		mysql_free_result($result);
		if($type=="modify") {
			$start_date=substr($row->start_date,0,4)."-".substr($row->start_date,4,2)."-".substr($row->start_date,6,2);
			$end_date=substr($row->end_date,0,4)."-".substr($row->end_date,4,2)."-".substr($row->end_date,6,2);
			$design=$row->design;
			$x_size=$row->x_size;
			$y_size=$row->y_size;
			$x_to=$row->x_to;
			$y_to=$row->y_to;
			$scroll_yn=$row->scroll_yn;
			$frame_type=$row->frame_type;
			$cookietime=$row->cookietime;
			$title=$row->title;
			$content=$row->content;
		} else if($type=="modify_result") {
			$sql = "SELECT COUNT(*) as cnt, COUNT(IF(frame_type='2',1,NULL)) as cnt2 FROM tbleventpopup ";
			$result = mysql_query($sql,get_db_conn());
			$crow=mysql_fetch_object($result);
			mysql_free_result($result);

				$contentSql = "SELECT content FROM tbleventpopup WHERE num = '".$num."' ";
				$contentResult = mysql_query($contentSql, get_db_conn());
				$contentNums = mysql_num_rows($contentResult);
				$contentRow = mysql_fetch_object($contentResult);
				
				if($contentNums > 0){
					$content = editorImsgeUrlSolv($content,$contentRow->content);
					mysql_free_result($contentResult);
				}
			if($row->frame_type!="2" && $frame_type==2 && $crow->cnt2>=1) {
				$onload="<script>alert('���̾� Ÿ���� �˾�â�� 1���� ��� �����մϴ�.');</script>";
			} else {
				$sql = "UPDATE tbleventpopup SET ";
				$sql.= "start_date	= '".$in_start."', ";
				$sql.= "end_date	= '".$in_end."', ";
				$sql.= "design		= '".$design."', ";
				$sql.= "x_size		= '".$x_size."', ";
				$sql.= "y_size		= '".$y_size."', ";
				$sql.= "x_to		= '".$x_to."', ";
				$sql.= "y_to		= '".$y_to."', ";
				$sql.= "scroll_yn	= '".$scroll_yn."', ";
				$sql.= "frame_type	= '".$frame_type."', ";
				$sql.= "cookietime	= '".$cookietime."', ";
				$sql.= "title		= '".$title."', ";
				$sql.= "content		= '".$content."' ";
				$sql.= "WHERE num = '".$num."' ";
				mysql_query($sql,get_db_conn());
				$onload="<script>alert('�˾�â ������ �Ϸ�Ǿ����ϴ�.');</script>";
				unset($type); unset($num);
				unset($start_date); unset($end_date); unset($design); unset($x_size); unset($y_size); unset($x_to);
				unset($y_to); unset($scroll_yn); unset($frame_type); unset($cookietime); unset($title); unset($content);
			}
		}
	} else {
		mysql_free_result($result);
		$onload="<script>alert('�����Ϸ��� �˾�â ������ �������� �ʽ��ϴ�.');</script>";
	}
} else if ($type=="delete" && strlen($num)>0) {
	$sql = "SELECT * FROM tbleventpopup WHERE num = '".$num."' ";
	$result = mysql_query($sql,get_db_conn());
	$rows=mysql_num_rows($result);
	mysql_free_result($result);

	if($rows>0) {
		$sql = "DELETE FROM tbleventpopup WHERE num = '".$num."' ";
		mysql_query($sql,get_db_conn());
		$onload="<script>alert('�ش� �˾�â�� �����Ͽ����ϴ�.');</script>";
		unset($type); unset($num);
		unset($start_date); unset($end_date); unset($design); unset($x_size); unset($y_size); unset($x_to);
		unset($y_to); unset($scroll_yn); unset($frame_type); unset($cookietime); unset($title); unset($content);
	}
}

if(strlen($start_date)==0) $start_date=date("Y-m-d");
if(strlen($end_date)==0) $end_date=date("Y-m-d");

if(strlen($type)==0) $type="insert";
$type_name="images/botteon_save.gif";
if($type=="modify" || $type=="modify_result") $type_name="images/btn_edit2.gif";
?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script type="text/javascript" src="calendar.js.php"></script>

<!-- �����Ϳ� ���� ȣ�� -->
<script type="text/javascript" src="/gmeditor/js/jquery.js"></script>
<script type="text/javascript" src="/gmeditor/js/jquery.event.drag-2.0.min.js"></script>
<script type="text/javascript" src="/gmeditor/js/jquery.resizable.js"></script>
<script type="text/javascript" src="/gmeditor/js/ajax_upload.3.6.js"></script>
<script type="text/javascript" src="/gmeditor/js/ej.h2xhtml.js"></script>
<script type="text/javascript" src="/gmeditor/editor.js"></script>
<script type="text/javascript" src="/js/jquery.autocomplete.js"></script>
<link rel="stylesheet" type="text/css" href="/js/jquery.autocomplete.css" />
<script language="javascript" type="text/javascript">
$(document).ready(function() {
	ejEditor();
});
</script>
<style type="text/css">
@import url("/gmeditor/common.css");
.productRegFormTbl{border-top:2px solid #333}
.productRegFormTbl th{ text-align:left; padding-left:25px; background:#f8f8f8 url(/admin/images/icon_point5.gif) 10px 50% no-repeat; border-bottom:1px solid #efefef; border-left:1px solid #efefef}
.productRegFormTbl td{padding-left:5px; border-bottom:1px solid #efefef; border-left:1px solid #efefef}
.productRegFormTbl caption{ text-align:left}
</style>
<!-- # �����Ϳ� ���� ȣ�� -->

<script language="JavaScript">

var eventpopupcnt = <?=count($eventpopup)?>;
function ChangeEditer(mode,obj){
	if (mode==form1.htmlmode.value) {
		return;
	} else {
		obj.checked=true;
		editor_setmode('content',mode);
	}
	form1.htmlmode.value=mode;
}

function CheckForm(type) {
	if(document.form1.x_to.value.length==0 || document.form1.y_to.value.length==0) {
		alert("�˾�â ��ġ ������ �ϼ���.");
		document.form1.x_to.focus();
		return;
	}
	if(!IsNumeric(document.form1.x_to.value)) {
		alert("�˾�â ��ġ �������� ���ڸ� �Է� �����մϴ�.");
		document.form1.x_to.focus();
		return;
	}
	if(!IsNumeric(document.form1.y_to.value)) {
		alert("�˾�â ��ġ �������� ���ڸ� �Է� �����մϴ�.");
		document.form1.y_to.focus();
		return;
	}
	if(document.form1.x_size.value.length==0 || document.form1.y_size.value.length==0) {
		alert("�˾�â ũ�� ������ �ϼ���.");
		document.form1.x_size.focus();
		return;
	}
	if(!IsNumeric(document.form1.x_size.value)) {
		alert("�˾�â ũ�� �������� ���ڸ� �Է� �����մϴ�.");
		document.form1.x_size.focus();
		return;
	}
	if(!IsNumeric(document.form1.y_size.value)) {
		alert("�˾�â ũ�� �������� ���ڸ� �Է� �����մϴ�.");
		document.form1.y_size.focus();
		return;
	}
	frame_type=false;
	for(i=0;i<document.form1.frame_type.length;i++) {
		if(document.form1.frame_type[i].checked==true) {
			frame_type=true;
			break;
		}
	}
	if(!frame_type) {
		alert("�˾�â ������ �����ϼ���.");
		document.form1.frame_type[0].focus();
		return;
	}
	if(document.form1.scroll_yn[0].checked==false && document.form1.scroll_yn[1].checked==false) {
		alert("��ũ�ѹ� ������ �ϼ���.");
		document.form1.scroll_yn[0].focus();
		return;
	}
	if(document.form1.cookietime[0].checked==false && document.form1.cookietime[1].checked==false && document.form1.cookietime[2].checked==false) {
		alert("�˾�â ��ǥ�� �Ⱓ�� �����ϼ���.");
		document.form1.cookietime[0].focus();
		return;
	}
	if(document.form1.title.value.length==0) {
		alert("�˾�â ������ �Է��ϼ���.");
		document.form1.title.focus();
		return;
	}

	design=false;
	for(i=eventpopupcnt;i<document.form1.design.length;i++) {
		if(document.form1.design[i].checked==true) {
			design=true;
			break;
		}
	}
	if(!design) {
		alert("�˾�â ���ø��� �����ϼ���.");
		return;
	}

	if(document.form1.content.value.length==0) {
		alert("�˾�â ������ �Է��ϼ���.");
		document.form1.content.focus();
		return;
	}
	if(type=="modify" || type=="modify_result") {
		if(!confirm("�ش� �˾�â�� �����Ͻðڽ��ϱ�?")) {
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
		if(!confirm("�ش� �˾�â�� �����Ͻðڽ��ϱ�?")) {
			return;
		}
	}
	document.form1.type.value=type;
	document.form1.num.value=num;
	document.form1.submit();
}

function ChangeDesign(tmp) {
	tmp=tmp + eventpopupcnt;
	document.form1["design"][tmp].checked=true;
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
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ���������� &gt; �̺�Ʈ/����ǰ ��� ���� &gt; <span class="2depth_select">�˾� �̺�Ʈ ����</span></td>
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
			<input type=hidden name=type>
			<input type=hidden name=num value="<?=$num?>">
			<input type=hidden name=htmlmode value='wysiwyg'>
			<tr><td height="8"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/market_eventpopup_title.gif" ALT=""></TD>
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
					<TD width="100%" class="notice_blue">�̺�Ʈ, ��ް����� ���������� �˾�â�� ���� ������ �̺�Ʈ ������ �˸� �� �ֽ��ϴ�.</TD>
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
					<TD><IMG SRC="images/market_eventpopup_stitle1.gif" WIDTH="187" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=3></td></tr>
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
					<TD width="100%" class="notice_blue">1) &quot;�ʱ�ȭ&quot; ��ư Ŭ���� ���޻縦 ���� �湮 �����ڰ� &quot;0&quot;���� �ʱ�ȭ �˴ϴ�.<br>2) &quot;�ֹ���ȸ&quot; ��ư Ŭ���� ���޻縦 ���Ͽ� �湮�� ���� �ֹ���ȸ�� �Ͻ� �� �ֽ��ϴ�.</TD>
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
				<col width=25></col>
				<col width=></col>
				<col width=75></col>
				<col width=75></col>
				<col width=70></col>
				<col width=70></col>
				<col width=60></col>
				<col width=60></col>
				<TR>
					<TD colspan=8 background="images/table_top_line.gif"></TD>
				</TR>
				<TR align=center>
					<TD class="table_cell">No</TD>
					<TD class="table_cell1">�̺�Ʈ ����â ��� ����</TD>
					<TD class="table_cell1">������</TD>
					<TD class="table_cell1">������</TD>
					<TD class="table_cell1">�˾�âŸ��</TD>
					<TD class="table_cell1">�����</TD>
					<TD class="table_cell1">����</TD>
					<TD class="table_cell1">����</TD>
				</TR>
				<TR>
					<TD colspan="8" background="images/table_con_line.gif"></TD>
				</TR>
<?
				$colspan=8;
				$sql = "SELECT num, start_date, end_date, reg_date, frame_type, title FROM tbleventpopup ";
				$sql.= "ORDER BY num DESC ";
				$result = mysql_query($sql,get_db_conn());
				$cnt=0;
				while($row=mysql_fetch_object($result)) {
					$cnt++;
					$date1 = substr($row->start_date,0,4).".".substr($row->start_date,4,2).".".substr($row->start_date,6,2);
					$date2 = substr($row->end_date,0,4).".".substr($row->end_date,4,2).".".substr($row->end_date,6,2);
					$reg_date = substr($row->reg_date,0,4).".".substr($row->reg_date,4,2).".".substr($row->reg_date,6,2);
					if($row->frame_type==0) $frame_type_name = "<img src=\"images/icon_type3.gif\" width=\"63\" height=\"16\" border=\"0\">";
					else if($row->frame_type==1)	$frame_type_name = "<img src=\"images/icon_type2.gif\" width=\"63\" height=\"16\" border=\"0\">";
					else if($row->frame_type==2)	$frame_type_name = "<img src=\"images/icon_type1.gif\" width=\"63\" height=\"16\" border=\"0\">";
					echo "<TR align=center>\n";
					echo "	<TD class=\"td_con2\">".$cnt."</TD>\n";
					echo "	<TD align=left class=\"td_con1\">".$row->title."</TD>\n";
					echo "	<TD class=\"td_con1\">".$date1."</TD>\n";
					echo "	<TD class=\"td_con1\">".$date2."</TD>\n";
					echo "	<TD class=\"td_con1\">".$frame_type_name."</TD>\n";
					echo "	<TD class=\"td_con1\">".$reg_date."</TD>\n";
					echo "	<TD class=\"td_con1\"><a href=\"javascript:ModeSend('modify','".$row->num."');\"><img src=\"images/btn_edit.gif\" width=\"50\" height=\"22\" border=\"0\"></a></TD>\n";
					echo "	<TD class=\"td_con1\"><a href=\"javascript:ModeSend('delete','".$row->num."');\"><img src=\"images/btn_del.gif\" width=\"50\" height=\"22\" border=\"0\"></a></TD>\n";
					echo "</TR>\n";
					echo "<TR>\n";
					echo "	<TD colspan=".$colspan." background=\"images/table_con_line.gif\"></TD>\n";
					echo "</TR>\n";
				}
				mysql_free_result($result);
				if ($cnt==0) {
					echo "<TR><TD class=td_con2 colspan=".$colspan." align=center>��ϵ� �˾�â�� �����ϴ�.</TD></TR>";
				}
?>
				<TR>
					<TD colspan=8 background="images/table_top_line.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=40></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/market_eventpopup_stitle2.gif" WIDTH="187" HEIGHT=31 ALT=""></TD>
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
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">���� �Ⱓ</TD>
					<TD class="td_con1"><INPUT style="TEXT-ALIGN: center" onfocus=this.blur(); onclick=Calendar(this) size=15 name=start_date value="<?=$start_date?>" class="select_selected">����  <INPUT style="TEXT-ALIGN: center" onfocus=this.blur(); onclick=Calendar(this) size=15 name=end_date value="<?=$end_date?>" class="select_selected">����&nbsp;&nbsp;<span class="font_orange">���ش� �Ⱓ ������ �˾�â�� ��ϴ�.</span></TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�˾�â ��ġ ����</TD>
					<TD class="td_con1">���ʿ��� <INPUT onkeyup="return strnumkeyup(this);" style="PADDING-LEFT: 5px" size=5 name=x_to value="<?=$x_to?>" class="input">�ȼ� �̵� ��, ���ʿ��� <INPUT onkeyup="return strnumkeyup(this);" style="PADDING-LEFT: 5px" size=5 name=y_to value="<?=$y_to?>" class="input">�ȼ� �Ʒ��� �̵��մϴ�.</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�˾�â ũ�� ����</TD>
					<TD class="td_con1">
					����: <INPUT onkeyup="return strnumkeyup(this);" style="PADDING-LEFT: 5px" size=5 name=x_size value="<?=$x_size?>" class="input">�ȼ�,  &nbsp;
					����: <INPUT onkeyup="return strnumkeyup(this);" style="PADDING-LEFT: 5px" size=5 name=y_size value="<?=$y_size?>" class="input">�ȼ�</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0"><b>�˾�â ���� ����</b></TD>
					<TD class="td_con1">
					<INPUT id=idx_frame_type1 type=radio value=0 <?if($frame_type==0)echo"checked";?> name=frame_type><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_frame_type1>��������</LABEL>&nbsp;
					<INPUT id=idx_frame_type2 type=radio value=1 <?if($frame_type==1)echo"checked";?> name=frame_type><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand; TEXT-DECORATION: none" onmouseout="style.textDecoration='none'" for=idx_frame_type2>��������</LABEL>&nbsp;
					<INPUT id=idx_frame_type3 type=radio value=2 <?if($frame_type==2)echo"checked";?> name=frame_type><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand; TEXT-DECORATION: none" onmouseout="style.textDecoration='none'" for=idx_frame_type3><B><span class="font_orange">���̾� Ÿ��</B></LABEL></span>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">��ũ�ѹ� ����</TD>
					<TD class="td_con1">
					<INPUT id=idx_scroll_yn1 type=radio value=Y name=scroll_yn <?if($scroll_yn=="Y")echo"checked";?>><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand; TEXT-DECORATION: none" onmouseout="style.textDecoration='none'" for=idx_scroll_yn1>��ũ���� �����</LABEL> &nbsp;&nbsp;
					<INPUT id=idx_scroll_yn2 type=radio value=N name=scroll_yn <?if($scroll_yn=="N")echo"checked";?>><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand; TEXT-DECORATION: none" onmouseout="style.textDecoration='none'" for=idx_scroll_yn2>��ũ�ѹ� ������� ����</LABEL><BR><span class="font_orange">����ũ���� ������� �ʴ� ���, �˾�â ũ�⺸�� ������ ������ ���� ���� ���� �� �ֽ��ϴ�.</span>
					</TD>
				</tr>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�˾�â ��ǥ�� �Ⱓ</TD>
					<TD class="td_con1">
					<INPUT id=idx_cookietime1 type=radio value=1 name=cookietime <?if($cookietime=="1")echo"checked";?>><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_cookietime1>�Ϸ絿�� ������ ����</LABEL>&nbsp;&nbsp;
					<INPUT id=idx_cookietime2 type=radio value=2 name=cookietime <?if($cookietime=="2")echo"checked";?>><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand; TEXT-DECORATION: none" onmouseout="style.textDecoration='none'" for=idx_cookietime2>�ٽ� ���� ����</LABEL>&nbsp;&nbsp;
					<INPUT id=idx_cookietime3 type=radio value=0 name=cookietime <?if($cookietime=="0")echo"checked";?>><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_cookietime3>�˾�â ������ �����</LABEL>
					</TD>
				</tr>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�˾�â ����</TD>
					<TD class="td_con1"><INPUT style="WIDTH: 100%" name=title value="<?=$title?>" class="input"></TD>
				</tr>
				<tr>
					<TD colspan="2">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="100%" bgcolor="#ededed" style="padding:4pt;">
						<table cellpadding="0" cellspacing="0" width="100%" bgcolor="white">
						<tr>
							<td width="100%">
							<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
								<TR>
									<TD align=center height="30" background="images/blueline_bg.gif"><b><font color="#555555">���ø� ����</font></b></TD>
								</TR>
								<TR>
									<TD width="100%" background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></TD>
								</TR>
								<TR>
									<TD width="100%" style="padding:0pt;">
									<TABLE cellSpacing=0 cellPadding="5" width="100%" border=0>
									<TR>
										<TD width="24" height="160" align=right valign="middle"><img src="images/btn_back.gif" width="31" height="31" border="0" onMouseover='moveright()' onMouseout='clearTimeout(righttime)' style="cursor:hand;"></TD>
										<TD  height="160">
										<table width="100%" cellspacing="0" cellpadding="0" border="0">
										<tr height=230>
											<td id=temp style="visibility:hidden;position:absolute;top:0;left:0">
<?
											echo "<script>";
											$jj=0;
											$menucontents = "";
											$menucontents .= "<table border=0 cellpadding=0 cellspacing=0><tr>";
											for($i=0;$i<count($eventpopup);$i++) {
												echo "thisSel = 'dotted #FFFFFF';";
												$menucontents .= "<td width=173 align=center><input type=radio name='design' value='".$eventpopup[$i]."' ";
												if($design==$eventpopup[$i]) $menucontents .= " checked";
												$menucontents .= "><br><img src='images/sample/event".$eventpopup[$i].".gif' border=0 width=150 height=200 style='border-width:1pt; border-color:#FFFFFF; border-style:solid;' hspace=5 onMouseOver='changeMouseOver(this);' onMouseOut='changeMouseOut(this,thisSel);' style='cursor:hand;' onclick='ChangeDesign(".$i.");'></td>";
												$jj++;
											}
											$menucontents .= "</tr></table>";
											echo "</script>";
?>

											<script language="JavaScript1.2">
											<!--
											function changeMouseOver(img) {
												 img.style.border='1 dotted #999999';
											}
											function changeMouseOut(img,dot) {
												 img.style.border="1 "+dot;
											}

											var menuwidth=650
											var menuheight=230
											var scrollspeed=10
											var menucontents="<nobr><?=$menucontents?></nobr>";

											var iedom=document.all||document.getElementById
											if (iedom)
												document.write(menucontents)
											var actualwidth=''
											var cross_scroll, ns_scroll
											var loadedyes=0
											function fillup(){
												if (iedom){
													cross_scroll=document.getElementById? document.getElementById("test2") : document.all.test2
													cross_scroll.innerHTML=menucontents
													actualwidth=document.all? cross_scroll.offsetWidth : document.getElementById("temp").offsetWidth
												}
												else if (document.layers){
													ns_scroll=document.ns_scrollmenu.document.ns_scrollmenu2
													ns_scroll.document.write(menucontents)
													ns_scroll.document.close()
													actualwidth=ns_scroll.document.width
												}
												loadedyes=1
											}
											window.onload=fillup

											function moveleft(){
												if (loadedyes){
													if (iedom&&parseInt(cross_scroll.style.left)>(menuwidth-actualwidth)){
														cross_scroll.style.left=parseInt(cross_scroll.style.left)-scrollspeed
													}
													else if (document.layers&&ns_scroll.left>(menuwidth-actualwidth))
														ns_scroll.left-=scrollspeed
												}
												lefttime=setTimeout("moveleft()",50)
											}

											function moveright(){
												if (loadedyes){
													if (iedom&&parseInt(cross_scroll.style.left)<0)
														cross_scroll.style.left=parseInt(cross_scroll.style.left)+scrollspeed
													else if (document.layers&&ns_scroll.left<0)
														ns_scroll.left+=scrollspeed
												}
												righttime=setTimeout("moveright()",50)
											}

											if (iedom||document.layers){
												with (document){
													write('<td valign=top>')
													if (iedom){
														write('<div style="position:relative;width:'+menuwidth+';">');
														write('<div style="position:absolute;width:'+menuwidth+';height:'+menuheight+';overflow:hidden;">');
														write('<div id="test2" style="position:absolute;left:0">');
														write('</div></div></div>');
													}
													else if (document.layers){
														write('<ilayer width='+menuwidth+' height='+menuheight+' name="ns_scrollmenu">')
														write('<layer name="ns_scrollmenu2" left=0 top=0></layer></ilayer>')
													}
													write('</td>')
												}
											}
											//-->
											</script>
											</td>
										</tr>
										</table>
										</td>
										<TD width="27" height="160"><img src="images/btn_next.gif" width="31" height="31" border="0" onMouseover='moveleft()' onMouseout='clearTimeout(lefttime)' style="cursor:hand;"></TD>
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
					</table>
					</TD>
				</tr>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD colspan="2">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="100%"><TEXTAREA style="DISPLAY: yes; WIDTH: 100%" name=content rows="17" wrap=off lang="ej-editor1" class="textarea"><?=$content?></TEXTAREA></td>
					</tr>
					</table>
					</TD>
				</tr>
				</TABLE>
				</td>
			</tr>
			<tr><td height=10></td></tr>
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
						<td><b>�˾�â ��밡�̵�</b></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">
						- �˾�â�� �ִ� 10�� ���� ��� �����մϴ�.
						<br>- �˾�â ������ &quot;���̾� 	Ÿ��&quot; �˾�â�� 1���� ��� �����մϴ�.
						<br>- �˾�â ũ��� 340*400�� �����մϴ�. �̺��� ũ�ų� ���� ��� ������ ���ø��� ��Ȯ�� ���� ���� �� �ֽ��ϴ�.
						<br>- �������� (�帲����, ������������ ��)�� �ۼ� �� �����ֱ�� �Ҷ��� �̹��� ��ο� �����Ͻñ� �ٶ��ϴ�.
						<br>- ���񿡴� ������ HTML�ڵ带 ������� ������.
						<br>- �˾�â ��ġ�� ���� �˾�â�� ���� ��� â ��ġ�� ��ġ�� �ʵ��� ��ġ�� ���� �����Ͻñ� �ٶ��ϴ�.<br>
						</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">&nbsp;</td>
					</tr>
					<tr>
						<td align="right"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><span class="font_orange"><b>�˾�â �ϴ� �ݱ� �κ� �Է���</b></span></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
						<TR>
							<TD class="table_cell" style="padding-right:15px; border-top-width:1pt; border-top-color:silver; border-top-style:solid;" noWrap align=right width=150 bgColor=#f0f0f0 height="27">[CHECK]</TD>
							<TD class="td_con1" style="padding-left:5px; border-top-width:1pt; border-top-color:silver; border-top-style:solid;" width="100%">üũ�ڽ��� ǥ���ϴ� �±��Դϴ�.</TD>
						</TR>
						<TR>
							<TD class="table_cell" style="padding-right:15px; border-top-width:1pt; border-bottom-width:1pt; border-top-color:rgb(222,222,222); border-bottom-color:silver; border-top-style:solid; border-bottom-style:solid;" noWrap align=right width=150 bgColor=#f0f0f0 height="27">[CLOSE]</TD>
							<TD class="td_con1" style="padding-left:5px; border-top-width:1pt; border-bottom-width:1pt; border-top-color:rgb(222,222,222); border-bottom-color:silver; border-top-style:solid; border-bottom-style:solid;" width="100%">�˾�â�� �ݴ� �±��Դϴ�. ��) â �ݱ� &lt;a href=[CLOSE]&gt;[�ݱ�]&lt;/a&gt;</TD>
						</TR>
						</TABLE>
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