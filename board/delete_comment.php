<?
if(substr(getenv("SCRIPT_NAME"),-19)=="/delete_comment.php"){
	header("HTTP/1.0 404 Not Found");
	exit;
}

include "head.php";

//_pr($member);

if ($setup[use_comment] != "Y") {
	$errmsg="�ش� �Խ����� ��� ����� �������� �ʽ��ϴ�.";
	echo "<html><head><title></title></head><body onload=\"alert('".$errmsg."');history.go(-1);\"></body></html>";exit;
}

if ($member[grant_comment]!="Y") {
	$errmsg="�̿������ �����ϴ�.";
	echo "<html><head><title></title></head><body onload=\"alert('".$errmsg."');history.go(-1);\"></body></html>";exit;
}

$qry  = "SELECT * FROM tblboardcomment WHERE board='".$board."' AND parent='".$num."' AND num='".$c_num."' ";
$result1 = mysql_query($qry,get_db_conn());
$ok_result = mysql_num_rows($result1);

if ((!$ok_result) || ($ok_result == -1)) {
	$errmsg="������ ����� �����ϴ�.\\n\\n�ٽ� Ȯ���Ͻñ� �ٶ��ϴ�.";
	echo "<html><head><title></title></head><body onload=\"alert('".$errmsg."');location.replace('board.php?pagetype=view&board=$board&num=$num&s_check=$s_check&search=$search&block=$block&gotopage=$gotopage');\"></body></html>";exit;
} else {
	$row1 = mysql_fetch_array($result1);
}


if ($_POST["mode"] == "delete") {
	if($member[admin]!="SU" AND $member['id'] != $row1['id'] ) {
		if (strlen($_POST["up_passwd"])==0) {
			$errmsg="�߸��� ��η� �����ϼ̽��ϴ�.";
			echo "<html><head><title></title></head><body onload=\"alert('".$errmsg."');history.go(-1);\"></body></html>";exit;
		}

		if (($row1[passwd]!=$_POST["up_passwd"]) && ($setup[passwd]!=$_POST["up_passwd"])) {
			$errmsg="��й�ȣ�� ��ġ���� �ʽ��ϴ�.\\n\\n�ٽ� Ȯ�� �Ͻʽÿ�.";
			echo "<html><head><title></title></head><body onload=\"alert('".$errmsg."');location.replace('board.php?pagetype=view&board=$board&num=$num&s_check=$s_check&search=$search&block=$block&gotopage=$gotopage');\"></body></html>";exit;
		}
	}
	$del_sql = "DELETE FROM tblboardcomment WHERE board='".$board."' AND parent='".$num."' AND num = '".$_POST["c_num"]."'";
	$delete = mysql_query($del_sql,get_db_conn());

	if ($delete) {
		@mysql_query("UPDATE tblboard SET total_comment = total_comment - 1 WHERE board='".$board."' AND num='".$num."'",get_db_conn());

		// ������ ��۵� ����..
		$del_admin_sql = "DELETE FROM tblboardcomment_admin WHERE board='".$board."' AND board_no='".$num."' AND comm_no = '".$_POST["c_num"]."'";
		mysql_query($del_admin_sql,get_db_conn());

	}

	header("Location:board.php?pagetype=view&board=$board&num=$num&s_check=$s_check&search=$search&block=$block&gotopage=$gotopage");
} else {
	$info_msg="��� �Է½� ����� ��й�ȣ�� �Է��ϼ���.";
	if($member[admin]=="SU" OR $member['id'] == $row1['id']) {
		$admin_hide_start = "���� �����Ͻðڽ��ϱ�?<!--";
		$admin_hide_end = "-->";
		$info_msg="";
	}
}


@include ("top.php");
?>

<script>
function check_submit() {
	try {
		if (!pwForm.up_passwd.value) {
			alert("��й�ȣ�� �Է��Ͽ� �ּ���");
			pwForm.up_passwd.focus();
			return false;
		}
	} catch (e) {}
}
</script>

<form method=post action="<?=$PHP_SELF?>" onsubmit="return check_submit();" name=pwForm>
<input type=hidden name=pagetype value="delete_comment">
<input type=hidden name=num value=<?=$num?>>
<input type=hidden name=board value=<?=$board?>>
<input type=hidden name=s_check value=<?=$s_check?>>
<input type=hidden name=search value=<?=$search?>>
<input type=hidden name=block value=<?=$block?>>
<input type=hidden name=gotopage value=<?=$gotopage?>>
<input type=hidden name=c_num value=<?=$c_num?>>
<input type=hidden name=mode value="delete">
<?
	include ($dir."/passwd_confirm.php");
?>

</form>

<?
	@include ("bottom.php");
?>

