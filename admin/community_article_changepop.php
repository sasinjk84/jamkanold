<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

if(strlen($_ShopInfo->getId())==0){
	echo "<script>alert('�������� ��η� �����Ͻñ� �ٶ��ϴ�.');window.close();</script>";
	exit;
}

$file_icon_path = "images/board/file_icon";
$imgdir = "images/board";

$mode=$_POST["mode"];
$thread_list=$_POST["thread_list"];
$s_board=$_POST["s_board"];

$board=$_REQUEST["board"];
$cart=$_REQUEST["cart"];
$block=$_REQUEST["block"];
$gotopage=$_REQUEST["gotopage"];

if(strlen($mode)==0) {
	if (count($cart)<=0) {
		echo "<script>window.close();</script>";
		exit;
	}
?>

<html>
<head>
<meta http-equiv='Content-Type' content='text/html;charset=euc-kr'>
<title>SMS �ּ� ���/����</title>
<link rel="stylesheet" href="style.css" type="text/css">
<script type="text/javascript" src="/include/lib.js.php"></script>
<SCRIPT LANGUAGE="JavaScript">
<!--
var con = confirm("�Խù� �̵��� ���۰� �亯���� ���� �̵��˴ϴ�.\n\n���� ����� �Խñ� �̵��� �����Խñ۷� �����Ǿ� �̵��˴ϴ�.\n\n��� �Ͻðڽ��ϱ�?");
if (!con) {
	window.close();
}
//-->
</SCRIPT>
</head>

<body topmargin="0" leftmargin="0" bgcolor="#ffffff">
<table border=0 cellpadding=0 cellspacing=0 width=100%>
<tr>
	<td height=25 bgcolor=#f4f4f4 style="padding-left:20"><B>�Խù� �̵�</B></td>
</tr>
<TR HEIGHT=1 BGCOLOR=#164982><TD></TD></TR>
<TR BGCOLOR=#5DC2FF><TD HEIGHT=1></TD></TR>
<TR BGCOLOR=#EFEFEF><TD HEIGHT=3></TD></TR>
<tr>
	<td height=20></td>
</tr>
<tr>
	<td align=center><FONT COLOR="red"><B>�̵��� �Խ����� �����ϼ���.</B></FONT></td>
</tr>
<tr>
	<td height=10></td>
</tr>
<SCRIPT LANGUAGE="JavaScript">
<!--
function CheckForm() {
	form=document.form1;
	var chk_board = false;
	var s_board = "";
	for(var i=0;i<form.elements.length;i++) {
		if (form.elements[i].name == "s_board" && form.elements[i].checked == true) {
			chk_board = true;
			s_board = form.elements[i].value;
			break;
		}
	}

	if (chk_board == false) {
		alert("�Խ��� ������ �ϼ���.");
		return;
	}

	if (form.board.value == s_board) {
		alert("�ٸ� �Խ������θ� �̵� �����մϴ�.");
		return;
	}

	form.mode.value="ok";
	form.submit();
}
//-->
</SCRIPT>
<form name=form1 method=post action="<?=$PHP_SELF?>">
<input type=hidden name=mode>
<input type=hidden name=board value="<?=$board?>">
<input type=hidden name=block value="<?=$block?>">
<input type=hidden name=gotopage value="<?=$gotopage?>">
<?
$thread_list = "";
for($y=0;$y<count($cart);$y++) {
	if ($y > 0) $thread_list .= ",";
	unset($tmp_thread);
	$tmp_thread = split("",$cart[$y]);
	$thread_list .= $tmp_thread[1];
}
?>
<input type=hidden name="thread_list" value="<?=$thread_list?>">
<tr>
	<td>
	<table border=0 cellpadding=0 cellspacing=0 width=100%>
	<tr>
<?
	$sql = "SELECT * FROM tblboardadmin ORDER BY date ASC ";
	$result=mysql_query($sql,get_db_conn());
	$j=0;
	while($row=mysql_fetch_object($result)) {
		unset($check_board);
		if (strlen($board)>0 && $board==$row->board) {
			$check_board = "checked";
		}
		if ($j%2 == 0 && $j > 0) echo "</tr><tr>";
		echo "<td style='padding-left:30' width=50%><input type=radio name=s_board value=\"".$row->board."\" ".$check_board."> ".$row->board_name."</td>";
		$j++;
	}
?>
	</tr>
	</table>
	</td>
</tr>
<tr>
	<td height=10></td>
</tr>
<TR HEIGHT=1 BGCOLOR=#164982><TD></TD></TR>
<TR BGCOLOR=#5DC2FF><TD HEIGHT=1></TD></TR>
<TR BGCOLOR=#EFEFEF><TD HEIGHT=3></TD></TR>
<tr>
	<td align=center style="padding-top:10;"><A HREF="javascript:CheckForm()"><img src="<?=$imgdir?>/butt-ok.gif" border=0></A></td>
</tr>

</form>

</table>
</body>
</html>

<?
} else {
/*
	if (strlen($thread_list)==0) {
		echo "<script>alert('���õ� �Խù��� �����ϴ�.');window.close();</script>";
		exit;
	}
	if (strlen($s_board)==0) {
		echo "<script>alert('���õ� �Խ����� �����ϴ�.');window.close();</script>";
		exit;
	}

	$qry = "WHERE 1=1 ";

	$sql = "SELECT * FROM tblboardadmin ".$qry." AND board='".$s_board."' ";
	$result=mysql_query($sql,get_db_conn());
	$rows=(int)mysql_num_rows($result);
	mysql_free_result($result);
	if ($rows==0) {
		echo "<script>alert('���õ� �Խ����� �����ϴ�.');window.close();</script>";
		exit;
	}


	$sql = "UPDATE tblboard SET board='".$s_board."' ".$qry." AND thread IN (".$thread_list.") ";
	$update=mysql_query($sql,get_db_conn());
	if($update) {
		$sql = "SELECT board,num,thread,pos,depth,prev_no,next_no FROM tblboard ".$qry." ";
		$sql.= "AND thread IN (".$thread_list.") ";
		$result=mysql_query($sql,get_db_conn());
		while($row=mysql_fetch_object($result)) {
			if($s_board!=$row->board) {
				//�ڸ�Ʈ ������Ʈ
				$parent.=",".$row->num;

				//����/������ ������Ʈ
				if($row->pos==0 && $row->depth==0) {

				}

				//�������̺� total_article, max_num ������Ʈ
			}
		}
		mysql_free_result($result);

		$parent=substr($parent,1);
		if(strlen($parent)>0) {
			$sql = "UPDATE tblboardcomment SET board='".$s_board."' ".$qry." ";
			$sql.= "AND parent IN (".$parent.") ";
			mysql_query($sql,get_db_conn());
		}
	}
*/
	echo "<script>alert('�ش� �Խ������� �Խù� �̵��� �Ϸ�Ǿ����ϴ�.');window.close();</script>";
	exit;
}
?>