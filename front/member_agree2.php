<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

if(strlen($_ShopInfo->getMemid())>0) {
	header("Location:mypage_usermodify.php");
	exit;
}


$leftmenu="Y";
$sql="SELECT body,leftmenu FROM ".$designnewpageTables." WHERE type='joinagree'";
$result=mysql_query($sql,get_db_conn());
if($row=mysql_fetch_object($result)) {
	$body=$row->body;
	$body=str_replace("[DIR]",$Dir,$body);
	$leftmenu=$row->leftmenu;
	$newdesign="Y";
}
mysql_free_result($result);

?>

<HTML>
<HEAD>
<TITLE><?=$_data->shoptitle?> - 회원가입</TITLE>
<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">

<META name="description" content="<?=(strlen($_data->shopdescription)>0?$_data->shopdescription:$_data->shoptitle)?>">
<META name="keywords" content="<?=$_data->shopkeyword?>">
<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
<?include($Dir."lib/style.php")?>
<link href="/css/b2b_style.css" rel="stylesheet" type="text/css" />
<SCRIPT LANGUAGE="JavaScript">
<!--
function CheckForm() {
	if(!document.form1.agree || document.form1.agree.checked==false) {
		alert("회원약관에 동의하셔야 회원가입이 가능합니다.");
		if(document.form1.agree) {
			document.form1.agree.focus();
		}
		return;
	} else if(!document.form1.agreep || document.form1.agreep.checked==false) {
		alert("개인보호취급방침에 동의하셔야 회원가입이 가능합니다.");
		if(document.form1.agreep) {
			document.form1.agreep.focus();
		}
		return;
	} else if(confirm("회원가입을 정말 하겠습니까?")) {
		document.form1.submit();
	} else {
		return;
	}
}

// 다음 체크박스로 포커스 이동
function fn_PassNextCheck()
{
  location.href = '#link1';
  return;
}

//-->
</SCRIPT>
</HEAD>

<body<?=(substr($_data->layoutdata["MOUSEKEY"],0,1)=="Y"?" oncontextmenu=\"return false;\"":"")?><?=(substr($_data->layoutdata["MOUSEKEY"],1,1)=="Y"?" ondragstart=\"return false;\" onselectstart=\"return false;\"":"")?> leftmargin="0" marginwidth="0" topmargin="0" marginheight="0"><?=(substr($_data->layoutdata["MOUSEKEY"],2,1)=="Y"?"<meta http-equiv=\"ImageToolbar\" content=\"No\">":"")?>

<? include ($Dir.MainDir.$_data->menu_type.".php") ?>

<table border=0 cellpadding=0 cellspacing=0 width=100%>
<form name=form1 action="member_join2.php" method=post>
<?
if ($leftmenu!="N") {
	echo "<tr>\n";
	if ($_data->title_type=="Y" && file_exists($Dir.DataDir."design/memberjoin_title.gif")) {
		echo "<td><img src=\"".$Dir.DataDir."design/memberjoin_title.gif\" border=\"0\" alt=\"회원가입\"></td>\n";
	} else {
		echo "<td>\n";
		echo "<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>\n";
		echo "<TR>\n";
		echo "	<TD><IMG SRC=".$Dir."images/".$_data->icon_type."/memberjoin_title_head.gif ALT=></TD>\n";
		echo "	<TD width=100% valign=top background=".$Dir."images/".$_data->icon_type."/memberjoin_title_bg.gif></TD>\n";
		echo "	<TD width=40><IMG SRC=".$Dir."images/".$_data->icon_type."/memberjoin_title_tail.gif ALT=></TD>\n";
		echo "</TR>\n";
		echo "</TABLE>\n";
		echo "</td>\n";
	}
	echo "</tr>\n";
}

$sql="SELECT agreement,privercy FROM tbldesign ";
$result=mysql_query($sql,get_db_conn());
$row=mysql_fetch_object($result);
$agreement=$row->agreement;
$privercy_exp=@explode("=", $row->privercy);
$privercy=$privercy_exp[1];
mysql_free_result($result);

if(strlen($agreement)==0) {
	$buffer="";
	$fp=fopen($Dir.AdminDir."agreement.txt","r");
	if($fp) {
		while (!feof($fp)) {
			$buffer.= fgets($fp, 1024);
		}
	}
	fclose($fp);
	$agreement=$buffer;
}

$pattern=array("(\[SHOP\])","(\[COMPANY\])");
$replace=array($_data->shopname, $_data->companyname);
$agreement = preg_replace($pattern,$replace,$agreement);

if(strlen($privercy)==0) {
	$buffer="";
	$fp=fopen($Dir.AdminDir."privercy2.txt","r");
	if($fp) {
		while (!feof($fp)) {
			$buffer.= fgets($fp, 1024);
		}
	}
	fclose($fp);
	$privercy=$buffer;
}

$pattern=array("(\[SHOP\])","(\[NAME\])","(\[EMAIL\])","(\[TEL\])");
$replace=array($_data->shopname,$_data->privercyname,"<a href=\"mailto:".$_data->privercyemail."\">".$_data->privercyemail."</a>",$_data->info_tel);
$privercy = preg_replace($pattern,$replace,$privercy);

?>
<tr>
	<td align=center>

<div id="join_b2b_form01">
    <img src="/images/nmain/b2bjoin/join_b2b01_title.jpg" />
    <div style="width:920px;">
        <img src="/images/nmain/b2bjoin/join01_title01.jpg" />
        <div class="join_box">
            <?=$agreement?>
        </div>
    </div>
	<p class="chkbox"><span class="join_text"><INPUT id="idx_agree" type="checkbox" name="agree" style="border:none;" onclick='fn_PassNextCheck();'><span class="join_text2">위의 회원약관에 동의합니다.</span></span></p>

	<a name="link1"></a>
	<div style="width:920px;">
    	<img src="/images/nmain/b2bjoin/join01_title02.jpg" />
        <div class="join_box">
            <?=$privercy?>
        </div>
    </div>
	<p class="chkbox"><span class="join_text"><INPUT id="idx_agreep" type="checkbox" name="agreep" style="border:none;"><span class="join_text2">위의 개인정보취급방침에 동의합니다.</span></span></p>
	<p style="width:920px;clear:both;"></p>
	<div id="btn_join">
        <a href="javascript:CheckForm()"><img src="/images/nmain/b2bjoin/join01_ok.jpg" /></a>
        <a href="/"><img src="/images/nmain/b2bjoin/join01_dis.jpg" /></a>
    </div>
</div>



	</td>
</tr>
</table>

</form>

<? include ($Dir."lib/bottom.php") ?>

</BODY>
</HTML>