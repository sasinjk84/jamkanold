<?
if(substr(getenv("SCRIPT_NAME"),-16)=="/admin_login.php"){
	header("HTTP/1.0 404 Not Found");
	exit;
}

include ("head.php");

if($member[admin]=="SU") {
	header("Location:board.php?pagetype=list&board=$board&s_check=$s_check&search=$search&block=$block&gotopage=$gotopage");
	exit;
}

if (strlen($_POST["up_passwd"])==0) {
	$errmsg="�߸��� ��η� �����ϼ̽��ϴ�.";
	echo "<html><head><title></title></head><body onload=\"alert('".$errmsg."');location.replace('board.php?pagetype=list&board=$board&s_check=$s_check&search=$search&block=$block&gotopage=$gotopage');\"></body></html>";exit;
}

if ($setup[passwd]!=$_POST["up_passwd"]) {
	echo "<html><head><title></title></head><body onload=\"location.replace('board.php?pagetype=passwd_confirm&exec=admin&board=$board&s_check=$s_check&search=$search&block=$block&gotopage=$gotopage&error=1');\"></body></html>";exit;
} else { //��й�ȣ ��ġ
	$cadname=$board."_ADMIN";
	$cadnamrarray=getBoardCookieArray($_ShopInfo->getBoardadmin());
	$cadnamrarray[$cadname]="OK";
	$_ShopInfo->setBoardadmin(addslashes(serialize($cadnamrarray)));
	$_ShopInfo->Save();
}

header("Location:board.php?pagetype=list&board=$board&s_check=$s_check&search=$search&block=$block&gotopage=$gotopage");
exit;
?>