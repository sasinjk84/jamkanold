<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");
INCLUDE ("access.php");

####################### 페이지 접근권한 check ###############
$PageCode = "de-4";
$MenuCode = "design";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

function movePage($url) {
	 echo"<meta http-equiv=\"refresh\" content=\"0; url=$url\">";
	 exit;
}

$mode = $_POST['mode'];
$code = ($_POST['code']);
$prev_page = "../prev_view";

switch($mode) {
	case "intro" :	case "sendmail" : case "useinfo" :case "newpage" : 
		$fp = fopen("{$prev_page}/tmp/{$mode}.htm","w");
		$code = stripslashes($code);		
		$code=str_replace("[DIR]",$Dir,$code);
		$code=str_replace("../","/",$code);
		fwrite($fp,$code);
		fclose($fp);

		movePage("{$prev_page}/tmp/{$mode}.htm");
	break;

	case "agreement" :
		$fp = fopen("{$prev_page}/tmp/{$mode}.htm","w");
		$code = stripslashes($code);
		$pattern=array("(\[SHOP\])","(\[COMPANY\])");
		$replace=array($_data->shopname, $_data->companyname);
		$code = preg_replace($pattern,$replace,$code);
		fwrite($fp,$code);
		fclose($fp);
		movePage("{$prev_page}/tmp/{$mode}.htm");
	break;

	case "top" : case "menu" : case "main" : case "bottom" : case "logform" :  case "prlist" : case "prdetail" : case "tag" : 
	case "section" : case "search" : case "basket" : case "primageview" : case "notice" : case "info" : case "formmail" :
	case "board" : case "joinagree" : case "mbjoin" : case "mbmodify" : case "iddup" : case "findpw" : case "login" : 
	case "mbout" : case "mypage" : case "orderlist" :  case "wishlist" : case "mycoupon" : case "myreserve" :
	case "myperson" : case "mycustsect" : case "surveylist" : case "surveyview" : case "review" : case "reviewall" :
	case "rss" : case "blist" : case "brandmap" : case "commu" :
		$body_arr = array("top"=>"topmenu","menu"=>"leftmenu","main"=>"mainpage","primageview"=>"primgview","notice"=>"noticelist","info"=>"infolist");

		if(!$body_arr[$mode]) $body_arr[$mode] = $mode;

		$sql = "SELECT COUNT(*) as cnt FROM tbldesignnewpage_prev ";
		$sql.= "WHERE type='{$body_arr[$mode]}'";

		$result=mysql_query($sql,get_db_conn());
		$row=mysql_fetch_object($result);

		if($row->cnt==0) {
			$sql = "INSERT tbldesignnewpage_prev SET ";
			$sql.= "type		= '{$body_arr[$mode]}', ";
			$sql.= "body		= '".$code."'";
			mysql_query($sql,get_db_conn());

		} else {
			$sql = "UPDATE tbldesignnewpage_prev SET ";
			$sql.= "body		= '".$code."' ";
			$sql.= "WHERE type='{$body_arr[$mode]}'";
			mysql_query($sql,get_db_conn());
		}
		mysql_free_result($result);
		
		if($_POST['brandcode']) movePage("{$prev_page}/{$mode}.php?brandcode={$_POST['brandcode']}");
		else movePage("{$prev_page}/{$mode}.php");
	break;
}

?>