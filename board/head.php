<?
if(substr(getenv("SCRIPT_NAME"),-9)=="/head.php"){
	header("HTTP/1.0 404 Not Found");
	exit;
}

$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");
include_once($Dir."lib/class/pages.php");

include ("file.inc.php");
include ("lib.inc.php");
include ($Dir.TempletDir."board/".$setup[board_skin]."/color.php");

$dir=$Dir.TempletDir."board/".$setup[board_skin];
$imgdir=$Dir.BoardDir."images/skin/".$setup[board_skin];

$table_colcnt=6;
if ( $setup[board_skin] == "L04" ){$table_colcnt=7;}

if($setup[datedisplay]=="N") {
	$hide_date_start="<!--";
	$hide_date_end="-->";
	$table_colcnt=$table_colcnt-1;

	if($setup[hitdisplay]=="N" || ($setup[hitdisplay]=="M" && strlen($member[id])<=0 && strlen($member[authidkey])<=0 && $member[admin]!="SU")) {
		$hide_hit_start="<!--";
		$hide_hit_end="-->";
		$table_colcnt=$table_colcnt-1;
	}
} else {
	if($setup[hitdisplay]=="N" || ($setup[hitdisplay]=="M" && strlen($member[id])<=0 && strlen($member[authidkey])<=0 && $member[admin]!="SU")) {
		$hide_hit_start="<!--";
		$hide_hit_end="-->";
		$table_colcnt=$table_colcnt-1;
	}
}

//���� IP
$avoid_ip = split(",",$setup[avoid_ip]);
for($i=0;$i<count($avoid_ip);$i++) {
	if (getenv("REMOTE_ADDR") == trim($avoid_ip[$i])) {
		echo "<html><head><title></title></head><body onload=\"alert('�������� IP�� �ش� �Խ��� ������ ���ѵǾ����ϴ�.\\n\\n���θ� ��ڿ��� �����Ͻñ� �ٶ��ϴ�.');history.go(-1)\"></body></html>";exit;
	}
}


//�۾��� ���� üũ
if($member[grant_write]!="Y") {
	if($setup[grant_write]=="A") {	// ������
		$hide_write_start="<!--";
		$hide_write_end="-->";
	} else if($setup[grant_write]=="Y") {
		if(strlen($setup[group_code])==4) {
			$hide_write_start="<!--";
			$hide_write_end="-->";
		} else {
			$hide_write_start="<!--";
			$hide_write_end="-->";
		}
	}
}


if($setup[use_hide_button]=="Y" && $member[admin]!="SU") {
	$hide_write_start="<!--";
	$hide_write_end="-->";
	$reply_start="<!--";
	$reply_end="-->";
	$hide_delete_start="<!--";
	$hide_delete_end="-->";
}

if($setup[use_reply]=="N" OR $setup[grant_reply]=="A") {
	$reply_start="<!--";
	$reply_end="-->";
}

if($setup[use_lock]=="N") {
	$hide_secret_start="<!--";
	$hide_secret_end="-->";
}

if($setup[reply_sms]=="N" || $member[admin]== "SU") {
	$hide_replysms_start="<!--";
	$hide_replysms_end="-->";
}

if($setup[btype]!="L") {
	$reply_start="<!--";
	$reply_end="-->";
}



// ���Ӹ�
$subCateSQL = "SELECT `subCategory` FROM `tblboardadmin` WHERE `board` = '".$board."' ;";
$subCateRes = mysql_query($subCateSQL,get_db_conn());
$subCateRow = mysql_fetch_assoc ($subCateRes);
$subCategoryArray = explode(",",$subCateRow[subCategory]);

$subCategoryList = "";
$subCategoryList_start="<!--";
$subCategoryList_end="-->";

$subCategoryView = "";

if( $num > 0 ) {
	$boardSQL = "SELECT `subCategory`,`vote` FROM `tblboard` WHERE board='".$board."' AND num = ".$num;
	$boardResult = mysql_query($boardSQL,get_db_conn());
	$boardRow = mysql_fetch_assoc ($boardResult);
	if( strlen($boardRow['subCategory']) > 0 ) $subCategoryView = "[".$boardRow['subCategory']."]&nbsp;";
}

if( count($subCategoryArray) > 0 AND strlen($subCategoryArray[0]) > 0 ) {

	if( $boardRow['subCategory'] AND $num > 0 ) $selSubCategory = $boardRow['subCategory'];
	if( $_GET['subCategory'] ) $selSubCategory = $_GET['subCategory'];
	$subCategoryList .= "<select name='subCategory'><option value=".">--- ���� ---</option>";
	foreach ($subCategoryArray as $V) {
		$V = trim($V);
		$sel = ( $selSubCategory == $V )?"selected":"";
		$subCategoryList .= "<option value=\"".$V."\" ".$sel.">".$V."</option>";
	}
	$subCategoryList .= "</select>";

	$subCategoryList_start="";
	$subCategoryList_end="";

}


// �� ���� vote
$voteLink = "alert('ȸ�� �α����� �ϼž� ���� ��õ�Ͻ� �� �ֽ��ϴ�.');";
if( strlen($member[id]) > 0 ) {
	$voteLink = "if(confirm('�� ���� ��õ �մϱ�?')){location.href='/board/board.php?pagetype=view&num=".$num."&board=".$board."&block=".$block."&gotopage=".$gotopage."&search=".$search."&s_check=".$s_check."&voteup=up'};";
}
//$voteButton = "<img src='' alt='�� ��õ' onclick=\"".$voteLink."\" style=\"cursor:pointer;\"> ".$boardRow['vote'];
$voteButton = "<img src=\"".$imgdir."/butt-vote.gif\" alt='��õ�ϱ�' onclick=\"".$voteLink."\" style=\"cursor:pointer;\">";

if( $_GET[voteup] == 'up' ) {
	$voteSQL = "UPDATE `tblboard` SET `vote`=`vote`+1 WHERE board='".$board."' AND num = ".$num;
	mysql_query($voteSQL,get_db_conn());
	header("Location:http://".$_SERVER['SERVER_NAME']."/board/board.php?pagetype=view&num=".$num."&board=".$board."&block=".$block."&gotopage=".$gotopage."&search=".$search."&s_check=".$s_check."");
}

//print_r($setup);


// �� �ۼ��� ���̵�
if( strlen($num) > 0 ) {
	$useridSQL = "SELECT userid FROM tblboard WHERE num = ".$num;
	$useridResult = mysql_query( $useridSQL, get_db_conn());
	$useridRow = mysql_fetch_assoc( $useridResult );
	$boardUserid = $useridRow[userid];
}
?>