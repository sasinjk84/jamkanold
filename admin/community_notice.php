<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "co-1";
$MenuCode = "community";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$mode=$_POST["mode"];
$mode2=$_POST["mode2"];
$board=$_POST["board"];
$num=$_POST["num"];
$notice=$_POST["notice"];

$name=$_POST["name"];
$passwd=$_POST["passwd"];
$title=$_POST["title"];
$use_html=(int)$_POST["use_html"];
$content=$_POST["content"];

function getContent($str) {
	$str = str_replace("<","&lt",$str);
	$str = str_replace(">","&gt",$str);
	return $str;
}


if($mode=="onenotice_modify" && strlen($board)>0) {	//���� �������� ����
	$sql = "UPDATE tblboardadmin SET notice='".$notice."' WHERE board='".$board."' ";
	mysql_query($sql,get_db_conn());
	$onload="<script>alert(\"���� �������� ������ �Ϸ�Ǿ����ϴ�.\");</script>";
	$mode="";
} else if($mode=="onenotice_delete" && strlen($board)>0) {	//���� �������� ����
	$sql = "UPDATE tblboardadmin SET notice=NULL WHERE board='".$board."' ";
	mysql_query($sql,get_db_conn());
	$onload="<script>alert(\"���� ���������� �����Ǿ����ϴ�.\");</script>";
	$mode="";
} else if($mode=="insert" && $mode2=="result" && strlen($board)>0) {	//�������� ���
	$sql = "SELECT thread_no, max_num FROM tblboardadmin WHERE board='".$board."' ";
	$result=mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
	mysql_fetch_object($result);
	$thread=(int)$row->thread_no-1;
	$next_no=(int)$row->max_num;
	if(!$thread) {
		$sql = "SELECT MIN(thread) as thread FROM tblboard WHERE board='".$board."' ";
		$result = mysql_query($sql,get_db_conn());
		$row = mysql_fetch_array($result);
		mysql_free_result($result);
		if (!$row->thread) {
			$thread = 1000000000;
		} else {
			$thread = $row->thread-1;
		}
	}

	$title=getTitle($title);
	
	/** ������ ���� ���� ó�� �߰� �κ� */
	if(preg_match_all('/\/data\/editor_temp\/([a-zA-Z0-9\.]+)/',$content,$edimg)){
		foreach($edimg[1] as $timg){
			@rename($_SERVER['DOCUMENT_ROOT'].'/data/editor_temp/'.$timg,$_SERVER['DOCUMENT_ROOT'].'/data/editor/'.$timg);
		}
		$content = str_replace('/data/editor_temp/','/data/editor/',$content);
	}
	/** #������ ���� ���� ó�� �߰� �κ� */
	
	if(!$use_html) $content=getContent($content);
	$data->name=$name;
	$data->passwd=$passwd;
	$data->title=$title;
	$data->use_html=$use_html;
	$data->content=$data->content;

	$sql = "INSERT tblboard SET ";
	$sql.= "board			= '".$board."', ";
	$sql.= "num				= '', ";
	$sql.= "thread			= '".$thread."', ";
	$sql.= "pos				= '0', ";
	$sql.= "depth			= '0', ";
	$sql.= "prev_no			= '0', ";
	$sql.= "next_no			= '".$next_no."', ";
	$sql.= "name			= '".$name."', ";
	$sql.= "passwd			= '".$passwd."', ";
	$sql.= "email			= '', ";
	$sql.= "is_secret		= '0', ";
	$sql.= "use_html		= '".$use_html."', ";
	$sql.= "title			= '".$title."', ";
	$sql.= "filename		= '', ";
	$sql.= "writetime		= '".time()."', ";
	$sql.= "ip				= '".getenv("REMOTE_ADDR")."', ";
	$sql.= "access			= '0', ";
	$sql.= "total_comment	= '0', ";
	$sql.= "content			= '".$content."', ";
	$sql.= "notice			= '1', ";
	$sql.= "deleted			= '0' ";
	$insert=mysql_query($sql,get_db_conn());

	if($insert) {
		$qry = "SELECT LAST_INSERT_ID() ";
		$res = mysql_fetch_row(mysql_query($qry,get_db_conn()));
		$thisNum = $res[0];

		// ===== �������̺��� �Խñۼ� update =====
		$sql3 = "UPDATE tblboardadmin SET total_article = total_article + 1, ";
		$sql3.= "thread_no = '".$thread."', max_num = '".$thisNum."' WHERE board='".$board."' ";
		$update = mysql_query($sql3, get_db_conn());

		if ($next_no) {
			$qry9 = "SELECT thread FROM tblboard WHERE board='".$board."' AND num = '".$next_no."' ";
			$res9 = mysql_query($qry9,get_db_conn());
			$next_thread = mysql_fetch_row($res9);
			@mysql_free_result($res9);
			mysql_query("UPDATE tblboard SET prev_no = '".$thisNum."' WHERE thread = '".$next_thread[0]."'",get_db_conn());

			mysql_query("UPDATE tblboard SET prev_no = '".$thisNum."' WHERE board='".$board."' AND num = '".$next_no."'",get_db_conn());
		}
		$onload="<script>alert(\"�������� ����� �Ϸ�Ǿ����ϴ�.\");</script>";
		unset($data);
	} else {
		$onload="<script>alert(\"�������� ����� ������ �߻��Ͽ����ϴ�.\");</script>";
	}
	$mode=""; $mode2="";
} else if($mode=="modify" && strlen($board)>0 && strlen($num)>0) {	//�������� ����
	$sql = "SELECT * FROM tblboard WHERE board='".$board."' AND num='".$num."' AND notice='1' ";
	$result=mysql_query($sql,get_db_conn());
	$data=mysql_fetch_object($result);
	mysql_free_result($result);
	if(!$data) {
		$onload="<script>alert(\"�ش� ���������� �������� �ʽ��ϴ�.\");</script>";
		$mode=""; $num="";
	} else {
		if($mode2=="result") {
			$title=getTitle($title);
			
			/** ������ ���� ���� ó�� �߰� �κ� */
			if(preg_match_all('/\/data\/editor\/([a-zA-Z0-9\.]+)/',$data->content,$edtimg)){
				if(!preg_match_all('/\/data\/editor\/([a-zA-Z0-9\.]+)/',$content,$edimg)) $edimg[1] = array();			
				foreach($edtimg[1] as $cimg){
					if(!in_array($cimg,$edimg[1])) @unlink($_SERVER['DOCUMENT_ROOT'].'/data/editor/'.$cimg);
				}
			}
			
			if(preg_match_all('/\/data\/editor_temp\/([a-zA-Z0-9\.]+)/',$content,$edimg)){
				foreach($edimg[1] as $timg){
					@rename($_SERVER['DOCUMENT_ROOT'].'/data/editor_temp/'.$timg,$_SERVER['DOCUMENT_ROOT'].'/data/editor/'.$timg);
				}
				$content = str_replace('/data/editor_temp/','/data/editor/',$content);		
			}
			/** #������ ���� ���� ó�� �߰� �κ� */
			
			if(!$use_html) $content=getContent($content);
			$data->name=$name;
			$data->passwd=$passwd;
			$data->title=$title;
			$data->use_html=$use_html;
			$data->content=$data->content;

			$sql = "UPDATE tblboard SET ";
			$sql.= "name		= '".$name."', ";
			$sql.= "passwd		= '".$passwd."', ";
			$sql.= "title		= '".$title."', ";
			$sql.= "use_html	= '".$use_html."', ";
			$sql.= "content		= '".$content."' ";
			$sql.= "WHERE board='".$board."' AND num='".$num."' AND notice='1' ";
			$update=mysql_query($sql,get_db_conn());
			if($update) {
				$onload="<script>alert(\"�������� ������ �Ϸ�Ǿ����ϴ�.\");</script>";
				$mode=""; $mode2=""; $num=""; unset($data);
			} else {
				$onload="<script>alert(\"�������� ������ ������ �߻��Ͽ����ϴ�.\");</script>";
				$mode2="";
			}				
		}
	}
} else if($mode=="delete" && strlen($board)>0 && strlen($num)>0) {	//�������� ����
	$sql = "SELECT * FROM tblboard WHERE board='".$board."' AND num='".$num."' AND notice='1' ";
	$result=mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
	mysql_free_result($result);
	if(!$row) {
		$onload="<script>alert(\"�ش� ���������� �������� �ʽ��ϴ�.\");</script>";
	} else {
		unset($isUpdate);
		if($row->pos!=0) {
			$sql = "DELETE FROM tblboard WHERE board='".$board."' AND num='".$num."' ";
			$isUpdate=true;
		} else {
			$sql2 = "SELECT COUNT(*) FROM tblboard ";
			$sql2.= "WHERE board='".$board."' AND thread = '".$row->thread."' ";
			$result2 = mysql_query($sql2,get_db_conn());
			$deleteTotal = mysql_result($result2,0,0);
			mysql_free_result($result2);

			if ($deleteTotal == 1) {
				$sql = "DELETE FROM tblboard WHERE board='".$board."' AND num='".$num."' ";
				$isUpdate = true;
			} else {
				$delMsg = "��� �Ǵ� �ۼ��ڿ� ���� �����Ǿ����ϴ�.";
				$sql  = "UPDATE tblboard SET ";
				$sql .= "prev_no = 0, ";
				$sql .= "next_no = 0, ";
				$sql .= "passwd = 'deleted', ";
				$sql .= "email = '', ";
				$sql .= "is_secret = '0', ";
				$sql .= "use_html = '0', ";
				$sql .= "title = '".$delMsg."', ";
				$sql .= "use_related = '0', ";
				$sql .= "total_comment = 0, ";
				$sql .= "content = '".$delMsg."', ";
				$sql .= "notice = '0', ";
				$sql .= "deleted = '1' ";
				$sql .= "WHERE board='".$board."' AND num = '".$num."' ";
			}
		}
		$delete=mysql_query($sql,get_db_conn());
		if($delete) {
			/** ������ ���� ���� ó�� �߰� �κ� */
			if(preg_match_all('/\/data\/editor\/([a-zA-Z0-9\.]+)/',$row->content,$edtimg)){		
				foreach($edtimg[1] as $timg){
					@unlink($_SERVER['DOCUMENT_ROOT'].'/data/editor/'.$timg);
				}
			}
			/** #������ ���� ���� ó�� �߰� �κ� */
			
			if($row->prev_no) mysql_query("UPDATE tblboard SET next_no='".$row->next_no."' WHERE board='".$board."' AND next_no='".$row->num."'",get_db_conn()); // �������� ������ ���ڸ� �޲�;;;
			if($row->next_no) mysql_query("UPDATE tblboard SET prev_no='".$row->prev_no."' WHERE board='".$board."' AND prev_no='".$row->num."'",get_db_conn()); // �������� ������ ���ڸ� �޲�;;;

			if($row->total_comment>0) {
				$sql = "DELETE FROM tblboardcomment WHERE board='".$board."' AND parent='".$num."' ";
				mysql_query($sql,get_db_conn());
			}

			// ===== �������̺��� �Խñۼ� update =====
			unset($in_max_qry);
			unset($in_total_qry);
			if ($row->pos == 0) {
				if ($row->prev_no == 0) {
					$in_max_qry = "max_num = '".$row->next_no."' ";
				}
			}
			if ($isUpdate) {
				$in_total_qry = "total_article = total_article - 1 ";
			}

			$sql3 = "UPDATE tblboardadmin SET ";
			if ($in_max_qry) $sql3.= $in_max_qry;
			if ($in_max_qry && $in_total_qry) $sql3.= ",".$in_total_qry;
			else if (!$in_max_qry && $in_total_qry) $sql3.= $in_total_qry;
			$sql3.= "WHERE board='".$board."' ";

			if ($in_max_qry || $in_total_qry) $update = mysql_query($sql3,get_db_conn());

			$onload="<script>alert(\"���������� �����Ͽ����ϴ�.\");</script>";
		} else {
			$onload="<script>alert(\"�������� ������ ������ �߻��Ͽ����ϴ�.\");</script>";
		}
	}
	$mode=""; $num="";
}

if(strlen($board)==0) $board="qna";
if(strlen($mode)==0) $mode="insert";

if($mode=="modify") $mode_name="&nbsp;�� &nbsp; ��&nbsp;";
else $mode_name="&nbsp;�� &nbsp; ��&nbsp;";
?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function CheckForm(form) {
	if(form.name.value.length==0) {
		alert("�������� �۾��� �̸��� �Է��ϼ���.");
		form.name.focus();
		return;
	}
	if(form.passwd.value.length==0) {
		alert("�������� ���� ��й�ȣ�� �Է��ϼ���.");
		form.passwd.focus();
		return;
	}
	if(form.title.value.length==0) {
		alert("�������� ������ �Է��ϼ���.");
		form.title.focus();
		return;
	}
	if(form.content.value.length==0) {
		alert("�������� ������ �Է��ϼ���.");
		form.content.focus();
		return;
	}
	form.mode2.value="result";
	form.submit();
}

function OneNoticeModify() {
	document.form1.mode.value="onenotice_modify";
	document.form1.submit();
}

function OneNoticeDelete() {
	if(confirm("���� ���������� �����Ͻðڽ��ϱ�?")) {
		document.form1.mode.value="onenotice_delete";
		document.form1.submit();
	}
}

function NoticeModify(num) {
	document.form1.mode.value="modify";
	document.form1.num.value=num;
	document.form1.submit();
}

function NoticeDelete(num) {
	if(confirm("�ش� ���������� �����Ͻðڽ��ϱ�?")) {
		document.form1.mode.value="delete";
		document.form1.num.value=num;
		document.form1.submit();
	}
}
</script>

<!-- ������ ���� �߰� -->
<script type="text/javascript" src="/gmeditor/js/jquery.js"></script>
<script type="text/javascript" src="/gmeditor/js/jquery.event.drag-2.0.min.js"></script>
<script type="text/javascript" src="/gmeditor/js/jquery.resizable.js"></script>
<script type="text/javascript" src="/gmeditor/js/ajax_upload.3.6.js"></script>
<script type="text/javascript" src="/gmeditor/js/ej.h2xhtml.js"></script>
<script type="text/javascript" src="/gmeditor/editor.js"></script> 
<style type="text/css">
  @import url("/gmeditor/common.css");
</style>
<script language="javascript" type="text/javascript">
$(document).ready(function() {
	ejEditor();
});
</script>
<!-- # ������ ���� �߰� -->

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
			<? include ("menu_community.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : Ŀ�´�Ƽ &gt; Ŀ�´�Ƽ ����  &gt; <span class="2depth_select">�Խ��� �������� ����</span></td>
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
					<TD><IMG SRC="images/community_notice_title.gif"  ALT=""></TD>
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
					<TD width="100%" class="notice_blue">�Խ��� ��ܿ� ��� ���������� ������ �� �ֽ��ϴ�.</TD>
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
					<TD><IMG SRC="images/community_notice_stitle1.gif"  ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height=3 style="padding-top:3pt; padding-bottom:3pt;">
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/distribute_01.gif"></TD>
					<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
					<TD><IMG SRC="images/distribute_03.gif"></TD>
				</TR>
				<TR>
					<TD background="images/distribute_04.gif"></TD>
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
					<TD width="100%" class="notice_blue">1) <b><span class="font_orange">���� ��������</span></b> : �Խ��� �ֻ�ܿ� ��ġ�ϸ�, ������ �˸��� ������ ������ ����� �� �ֽ��ϴ�.<br>2) <b><span class="font_orange">��������</span></b> : ���� �������� �Ʒ��ʿ� ��ġ�ϸ�, ������ �˸��� ���� ������ ����� �� �ֽ��ϴ�.</TD>
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
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<col width=140></col>
				<col width=></col>
				<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
				<input type=hidden name=mode>
				<input type=hidden name=num>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�Խ��� ���</TD>
					<TD class="td_con1">
					<SELECT onchange="this.form.mode.value='';this.form.submit();" name=board class="select">
<?
					$sql = "SELECT * FROM tblboardadmin ORDER BY date ASC ";
					$result=mysql_query($sql,get_db_conn());
					$cnt=0;
					while($row=mysql_fetch_object($result)) {
						$cnt++;
						if($board==$row->board) {
							echo "<option value=\"".$row->board."\" selected>".$row->board_name."</option>\n";
							$one_notice=$row->notice;
						} else {
							echo "<option value=\"".$row->board."\">".$row->board_name."</option>\n";
						}
					}
					mysql_free_result($result);
?>
					</SELECT>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">���� ��������</TD>
					<TD class="td_con1">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td><INPUT style="WIDTH: 100%" name=notice value="<?=$one_notice?>" class="input"> </td>
						<td width="106"><p align="right"><a href="javascript:OneNoticeModify();"><img src="images/btn_edit.gif" width="50" height="22" border="0" hspace="2"></a><a href="javascript:OneNoticeDelete();"><img src="images/btn_del.gif" width="50" height="22" border="0"></a></td>
					</tr>
					</table>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
<?
				$sql = "SELECT num,title FROM tblboard WHERE board='".$board."' AND notice='1' ORDER BY num ASC ";
				$result=mysql_query($sql,get_db_conn());
				$cnt=0;
				while($row=mysql_fetch_object($result)) {
					$cnt++;
					echo "<TR>\n";
					echo "	<TD class=\"table_cell\"><img src=\"images/icon_point2.gif\" width=\"8\" height=\"11\" border=\"0\">��������".$cnt."</TD>\n";
					echo "	<TD class=\"td_con1\">\n";
					echo "	<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
					echo "	<tr>\n";
					echo "		<td><INPUT type=text style=\"font-size:9pt; color:rgb(51,102,153); border-width:medium; border-style:none; width:100%;\" readOnly value=\"".$row->title."\"></td>\n";
					echo "		<td width=\"106\"><p align=\"right\"><a href=\"javascript:NoticeModify('".$row->num."');\"><img src=\"images/btn_edit.gif\" width=\"50\" height=\"22\" border=\"0\" hspace=\"2\"></a><a href=\"javascript:NoticeDelete('".$row->num."');\"><img src=\"images/btn_del.gif\" width=\"50\" height=\"22\" border=\"0\"></a></td>\n";
					echo "	</tr>\n";
					echo "	</table>\n";
					echo "	</TD>\n";
					echo "</TR>\n";
					echo "<TR>\n";
					echo "	<TD colspan=\"2\" background=\"images/table_con_line.gif\"></TD>\n";
					echo "</TR>\n";
				}
				mysql_free_result($result);
?>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				</form>
				</TABLE>
				</td>
			</tr>
			<tr><td height="30"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/community_notice_stitle2.gif" WIDTH="270" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
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
				<form name=form2 action="<?=$_SERVER[PHP_SELF]?>" method=post>
				<input type=hidden name=mode value="<?=$mode?>">
				<input type=hidden name=mode2>
				<input type="hidden" name="use_html" value="1" /> <!-- ������ ���� �ڵ� html ������� ó�� -->
				<input type=hidden name=board value="<?=$board?>">
				<input type=hidden name=num value="<?=$num?>">
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�ۼ��� �̸�</TD>
					<TD class="td_con1"><INPUT maxLength="28" name=name value="<?=$data->name?>"class="input" size="27"></TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">��й�ȣ</TD>
					<TD class="td_con1"><INPUT type=password maxLength="30" value="" name=passwd value="<?=$data->passwd?>" class="input" size="30"></TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>														
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�������� ����</TD>
					<TD class="td_con1">
					<INPUT style="WIDTH: 500px" name=title value="<?=$data->title?>" class="input">
					<? /*
					<INPUT type=checkbox value=1 name=use_html <? if($data->use_html=="1")echo"checked";?> id=idx_use_html BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none"> <LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_use_html><B>HTML����</B></LABEL> */ ?>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>													
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�������� ����</TD>
					<TD class="td_con1"><TEXTAREA style="WIDTH: 100%; HEIGHT: 250px" name="content" lang="ej-editor3" class="textarea"><?=$data->content?></TEXTAREA> </TD>
				</TR>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=6></td></tr>
			<tr>
				<td align=center><a href="javascript:CheckForm(document.form2);"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a></td>
			</tr>
			<tr><td height="30"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/manual_top1.gif" WIDTH=15 height="45" ALT=""></TD>
					<TD><IMG SRC="images/manual_title.gif" WIDTH=113 height="45" ALT=""></TD>
					<TD width="100%" background="images/manual_bg.gif" height="35"></TD>
					<TD background="images/manual_bg.gif">&nbsp;</TD>
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
						<td><span class="font_dotline">���� ��������</span></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- �Խ��� ��ܿ� ���ٷ� ������ ������ �˸��� �ִ� ���� ���� �����Դϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- �������׺��� ���ʿ� ��µǸ�, ����� �Խ��� ���� 1���� �����մϴ�.</td>
					</tr>
					<tr>
						<td colspan="2" height="20"></td>
					</tr>
					<tr>
						<td align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><span class="font_dotline">��������</span></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- �Խ��� ��ܿ� �������� ����� �� �ִ� ������� �Ϲ� �Խù��� �����ϰ� ���ϰ� ��� �����մϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- ���������� ��� �����¹������̸�, ��ϵ� ������� ��ܿ� ��µ˴ϴ�.</td>
					</tr>
					<tr>
						<td colspan="2" height="20"></td>
					</tr>
					<tr>
						<td align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><span class="font_dotline">��Ϲ��</span></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">�� ����� ���ϴ� �Խ��� �����մϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">�� ���� �������� �Ǵ� ���������� ���/����/���� �Ͻø� �˴ϴ�.</td>
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