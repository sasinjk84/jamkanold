<?
if(substr(getenv("SCRIPT_NAME"),-19)=="/passwd_confirm.php"){
	header("HTTP/1.0 404 Not Found");
	exit;
}

include ("head.php");

if (($exec != "admin") && ($exec != "delete") && ($exec != "modify") && ($exec != "secret"))	{
	$errmsg="잘못된 경로로 접근하셨습니다.";
	echo "<html><head><title></title></head><body onload=\"alert('".$errmsg."');history.go(-1);\"></body></html>";exit;
}

if($exec == "admin") {
	if($member[admin]=="SU") {
		header("Location:board.php?pagetype=list&board=$board&s_check=$s_check&search=$search&block=$block&gotopage=$gotopage");
		exit;
	}
	if($error=="1") $error_meaage="<FONT COLOR=\"red\">※ 게시판 관리자 비밀번호 입력이 잘못되었습니다.</FONT><br><br>";
	$info_msg="<FONT COLOR=\"blue\">본 게시판의 관리자 비밀번호를 입력하세요.<br>3회 이상 비밀번호 오류시 쇼핑몰 운영자에게 통보되며<br>해당 게시판 접근이 차단될 수 있습니다.</FONT>";
	$html_url="board.php?pagetype=admin_login";
	$file=$dir."/admin_login.php";
} else {
	$qry  = "SELECT * FROM tblboard WHERE board='".$board."' AND num = '".$num."' ";
	$result1 = mysql_query($qry, get_db_conn());
	$ok_result = mysql_num_rows($result1);

	if ((!$ok_result) || ($ok_result == -1)) {
		if ($exec == "delete") {
			$strMessage = "삭제할";
		} else if ($exec == "modify") {
			$strMessage = "수정할";
		} else {
			$strMessage = "해당";
		}
		$errmsg="잘못된 경로로 접근하셨습니다.";
		echo "<html><head><title></title></head><body onload=\"alert('".$strMessage." 게시글이 없습니다.\\n\\n다시 확인하시기 바랍니다.');history.go(-1);\"></body></html>";exit;
	} else {
		$row1 = mysql_fetch_array($result1);
	}
	mysql_free_result($result1);

	if ($exec == "delete") {
		if ($member[admin]=="SU") {
			header("Location:board.php?pagetype=delete&board=$board&num=$num&s_check=$s_check&search=$search&block=$block&gotopage=$gotopage");
			exit;
		}
		$html_url = "board.php?pagetype=delete";
	} else if ($exec == "modify") {
		if ( ( $member[admin]=="SU" ) OR ( $member[id]!="" AND $boardUserid == $member[id] ) ) {
			header("Location:board.php?pagetype=write&exec=$exec&board=$board&num=$num&s_check=$s_check&search=$search&block=$block&gotopage=$gotopage");
			exit;
		}
		$html_url = "board.php?pagetype=write";
	} else if ($exec == "secret") {
		unset($isSecret);
		if ($setup[use_lock]!="N" && $row1[is_secret]=="1") {
			$cname=$board."_".$row1[thread]."_".$num."S";
			$isSecret = isCookieVal($_COOKIE["board_thread_numS"],$cname);
		}
		if( $member[id]!="" AND $boardUserid == $member[id] ) {
			$cookiearray=getBoardCookieArray($_COOKIE["board_thread_numS"]);
			$cookiearray[$cname]="OK";
			setBoardCookieArray("board_thread_numS",$cookiearray,1800,"/".RootPath.BoardDir,"");
			$isSecret = true;
		}
		if ($isSecret || $member[admin]=="SU") {
			header("Location:board.php?pagetype=view&board=$board&num=$num&s_check=$s_check&search=$search&block=$block&gotopage=$gotopage");
			exit;
		}
		$html_url = "board.php?pagetype=view&view=1&board=$board&num=$num&s_check=$s_check&search=$search&block=$block&gotopage=$gotopage";
		$info_msg="<img src=".$imgdir."/lock.gif border=0> 잠금기능을 사용중인 게시물입니다.<br>관리자비밀번호나 게시자의 비밀번호를 입력하세요.";
	}
	if($error=="1") $error_meaage="<FONT COLOR=\"red\">※ 비밀번호 입력이 잘못되었습니다.</FONT><br><br>";

	$file=$dir."/passwd_confirm.php";
}

include ("top.php");
?>

<script>
function check_submit() {
	if(!pwForm.up_passwd.value) {
		alert("비밀번호를 입력하여 주세요");
		pwForm.up_passwd.focus();
		return false;
	}
	return true;
}
</script>

<form method=post action="<?=$html_url?>" onsubmit="return check_submit();" name=pwForm>
<input type=hidden name=num value=<?=$num?>>
<input type=hidden name=board value=<?=$board?>>
<input type=hidden name=s_check value=<?=$s_check?>>
<input type=hidden name=search value=<?=$search?>>
<input type=hidden name=block value=<?=$block?>>
<input type=hidden name=gotopage value=<?=$gotopage?>>
<input type=hidden name=exec value=<?=$exec?>>

<?
	include $file;
?>

</form>

<?
	include ("bottom.php");
?>