<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

if(strlen($_ShopInfo->getMemid())==0) {
	Header("Location:".$Dir.FrontDir."login.php?chUrl=".getUrl());
	exit;
}

//����Ʈ ����
$setup[page_num] = 10;
$setup[list_num] = 10;

$block=$_REQUEST["block"];
$gotopage=$_REQUEST["gotopage"];

if ($block != "") {
	$nowblock = $block;
	$curpage  = $block * $setup[page_num] + $gotopage;
} else {
	$nowblock = 0;
}

if (($gotopage == "") || ($gotopage == 0)) {
	$gotopage = 1;
}

$mode=$_POST["mode"];
$venders=$_POST["venders"];
if($mode=="delete" && strlen($venders)>0) {
	$venders=substr($venders,0,-1);
	$venderlist=ereg_replace(',','\',\'',$venders);
	$sql = "DELETE FROM tblregiststore WHERE id='".$_ShopInfo->getMemid()."' AND vender IN ('".$venderlist."') ";
	if(mysql_query($sql,get_db_conn())) {
		$sql = "UPDATE tblvenderstorecount SET cust_cnt=cust_cnt-1 WHERE vender IN ('".$venderlist."') ";
		mysql_query($sql,get_db_conn());
	}
	header("Location:".$_SERVER[PHP_SELF]."?block=".$block."&gotopage=".$gotopage); exit;
} else if($mode=="agree" && strlen($venders)>0 && ($type=="Y" || $type=="N")) {
	$venders=substr($venders,0,-1);
	$venderlist=ereg_replace(',','\',\'',$venders);
	$sql = "UPDATE tblregiststore SET email_yn='".$type."' WHERE id='".$_ShopInfo->getMemid()."' AND vender IN ('".$venderlist."') ";
	mysql_query($sql,get_db_conn());
	header("Location:".$_SERVER[PHP_SELF]."?block=".$block."&gotopage=".$gotopage); exit;
}

?>

<HTML>
<HEAD>
<TITLE><?=$_data->shoptitle?> - ���� ���θ�</TITLE>
<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">
<META http-equiv="X-UA-Compatible" content="IE=Edge" />

<META name="description" content="<?=(strlen($_data->shopdescription)>0?$_data->shopdescription:$_data->shoptitle)?>">
<META name="keywords" content="<?=$_data->shopkeyword?>">
<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
<?include($Dir."lib/style.php")?>
<SCRIPT LANGUAGE="JavaScript">
<!--
function miniMailAgree(gbn,vender) {
	if(gbn=="add") {
		if(confirm("������ �����Ͻðڽ��ϱ�?")) {
			document.form2.venders.value=vender+",";
			document.form2.mode.value="agree";
			document.form2.type.value="Y";
			document.form2.submit();
		}
	} else if(gbn=="del") {
		if(confirm("������ �ź��Ͻðڽ��ϱ�?")) {
			document.form2.venders.value=vender+",";
			document.form2.mode.value="agree";
			document.form2.type.value="N";
			document.form2.submit();
		}
	}
}
function addAgreeMailAll() {
	document.form2.venders.value="";
	for(i=1;i<document.form1.sels.length;i++) {
		if(document.form1.sels[i].checked==true) {
			document.form2.venders.value+=document.form1.sels[i].value+",";
		}
	}
	if(document.form2.venders.value.length==0) {
		alert("�����Ͻ� �̴ϼ��� �����ϴ�.");
		return;
	}
	if(confirm("�����Ͻ� �̴ϼ��� ������ �����Ͻðڽ��ϱ�?")) {
		document.form2.mode.value="agree";
		document.form2.type.value="Y";
		document.form2.submit();
	}
}
function delAgreeMailAll() {
	document.form2.venders.value="";
	for(i=1;i<document.form1.sels.length;i++) {
		if(document.form1.sels[i].checked==true) {
			document.form2.venders.value+=document.form1.sels[i].value+",";
		}
	}
	if(document.form2.venders.value.length==0) {
		alert("�����Ͻ� �̴ϼ��� �����ϴ�.");
		return;
	}
	if(confirm("�����Ͻ� �̴ϼ��� ������ ���� �ź��Ͻðڽ��ϱ�?")) {
		document.form2.mode.value="agree";
		document.form2.type.value="N";
		document.form2.submit();
	}
}

var chkval=false;
function CheckAll(){
	if(chkval==false) chkval=true;
	else if(chkval==true) chkval=false;
	cnt=document.form1.tot.value;
	for(i=1;i<=cnt;i++){
		document.form1.sels[i].checked=chkval;
	}
}

function goDeleteMinishop() {
	document.form2.venders.value="";
	for(i=1;i<document.form1.sels.length;i++) {
		if(document.form1.sels[i].checked==true) {
			document.form2.venders.value+=document.form1.sels[i].value+",";
		}
	}
	if(document.form2.venders.value.length==0) {
		alert("�����Ͻ� �̴ϼ��� �����ϴ�.");
		return;
	}
	if(confirm("�����Ͻ� �̴ϼ��� �����Ͻðڽ��ϱ�?")) {
		document.form2.mode.value="delete";
		document.form2.submit();
	}
}

function GoPage(block,gotopage) {
	document.idxform.block.value=block;
	document.idxform.gotopage.value=gotopage;
	document.idxform.submit();
}
//-->
</SCRIPT>
</HEAD>

<body<?=(substr($_data->layoutdata["MOUSEKEY"],0,1)=="Y"?" oncontextmenu=\"return false;\"":"")?><?=(substr($_data->layoutdata["MOUSEKEY"],1,1)=="Y"?" ondragstart=\"return false;\" onselectstart=\"return false;\"":"")?> leftmargin="0" marginwidth="0" topmargin="0" marginheight="0"><?=(substr($_data->layoutdata["MOUSEKEY"],2,1)=="Y"?"<meta http-equiv=\"ImageToolbar\" content=\"No\">":"")?>

<?
	include ($Dir.MainDir.$_data->menu_type.".php");
	include_once("./mypage_groupinfo.php");
?>

<!-- ����������-�ܰ���� ��� �޴� -->
<div class="currentTitle">
	<div class="titleimage">���� ���θ�</div>
	<!--<div class="current"><img src="/data/design/img/sub/icon_home.gif" border="0" alt="" /> Ȩ &gt; ���������� &gt; <SPAN class="nowCurrent">���� ���θ�</span></div>-->
</div>
<!-- ����������-�ܰ���� ��� �޴� -->


	<table border=0 cellpadding=0 cellspacing=0 width=100%>
<?
$leftmenu="Y";
if($_data->design_mycustsect=="U") {
	$sql="SELECT body,leftmenu FROM ".$designnewpageTables." WHERE type='mycustsect'";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$body=$row->body;
		$body=str_replace("[DIR]",$Dir,$body);
		$leftmenu=$row->leftmenu;
		$newdesign="Y";
	}
	mysql_free_result($result);
}

if ($leftmenu!="N") {
	echo "<tr>\n";
	if ($_data->title_type=="Y" && file_exists($Dir.DataDir."design/mycustsect_title.gif")) {
		echo "<td><img src=\"".$Dir.DataDir."design/mycustsect_title.gif\" border=\"0\" alt=\"���� ���θ�\"></td>\n";
	} else {
		echo "<td>\n";
		/*
		echo "<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>\n";
		echo "<TR>\n";
		echo "	<TD><IMG SRC=".$Dir."images/".$_data->icon_type."/mycustsect_title_head.gif ALT=></TD>\n";
		echo "	<TD width=100% valign=top background=".$Dir."images/".$_data->icon_type."/mycustsect_title_bg.gif></TD>\n";
		echo "	<TD width=40><IMG SRC=".$Dir."images/".$_data->icon_type."/mycustsect_title_tail.gif ALT=></TD>\n";
		echo "</TR>\n";
		echo "</TABLE>\n";
		*/
		echo "</td>\n";
	}
	echo "</tr>\n";
}

echo "<form name=form1 method=post action=\"".$_SERVER[PHP_SELF]."\">\n";
echo "<input type=hidden name=sels>\n";
echo "<tr>\n";
echo "	<td align=center>\n";
include ($Dir.TempletDir."mycustsect/mycustsect".$_data->design_mycustsect.".php");
echo "	</td>\n";
echo "</tr>\n";
echo "<input type=hidden name=tot value=\"".$cnt."\">\n";
echo "</form>\n";
?>

<form name=idxform method=get action="<?=$_SERVER[PHP_SELF]?>">
<input type=hidden name=block>
<input type=hidden name=gotopage>
</form>

<form name=form2 method=post action="<?=$_SERVER[PHP_SELF]?>">
<input type=hidden name=mode>
<input type=hidden name=type>
<input type=hidden name=venders>
<input type=hidden name=block value="<?=$block?>">
<input type=hidden name=gotopage value="<?=$gotopage?>">
</form>

	</table>


<? include ($Dir."lib/bottom.php") ?>
<?=$onload?>
</BODY>
</HTML>