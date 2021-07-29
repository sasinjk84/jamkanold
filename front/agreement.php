<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");


if( $preview===true ) {

	$sql = "SELECT * FROM `tbldesignnewpage_prev` WHERE `type` = 'agreement' LIMIT 1;";
	$result = mysql_query($sql,get_db_conn());

	if($row=mysql_fetch_object($result)) {
		$agreement = $row->body;
	}

} else {

	$sql="SELECT agreement FROM tbldesign ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$agreement=$row->agreement;
	}

}
mysql_free_result($result);

if(strlen($agreement)==0) {
	$fp=fopen($Dir.AdminDir."agreement.txt","r");
	if($fp) {
		while (!feof($fp)) {
			$buffer.= fgets($fp, 1024);
		}
	}
	fclose($fp);
	$agreement=$buffer;
	$agreement="<table border=0 cellpadding=0 cellspacing=0 width=100%><tr><td  style=\"padding:10\">".$agreement."</td></tr></table>";
}

$pattern=array("(\[SHOP\])","(\[COMPANY\])");
$replace=array($_data->shopname, $_data->companyname);
$agreement = preg_replace($pattern,$replace,$agreement);

?>

<HTML>
	<HEAD>
		<TITLE><?=$_data->shoptitle?> - 이용약관</TITLE>
		<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">
		<META http-equiv="X-UA-Compatible" content="IE=Edge" />

		<META name="description" content="<?=(strlen($_data->shopdescription)>0?$_data->shopdescription:$_data->shoptitle)?>">
		<META name="keywords" content="<?=$_data->shopkeyword?>">
		<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
		<?include($Dir."lib/style.php")?>
	</HEAD>

	<body<?=(substr($_data->layoutdata["MOUSEKEY"],0,1)=="Y"?" oncontextmenu=\"return false;\"":"")?><?=(substr($_data->layoutdata["MOUSEKEY"],1,1)=="Y"?" ondragstart=\"return false;\" onselectstart=\"return false;\"":"")?> leftmargin="0" marginwidth="0" topmargin="0" marginheight="0"><?=(substr($_data->layoutdata["MOUSEKEY"],2,1)=="Y"?"<meta http-equiv=\"ImageToolbar\" content=\"No\">":"")?>

		<? include ($Dir.MainDir.$_data->menu_type.".php") ?>

		<!-- 이용약관 페이지 상단 메뉴 -->
		<div class="currentTitle">
			<div class="titleimage">이용약관</div>
			<!--<div class="current"><img src="/data/design/img/sub/icon_home.gif" border="0" alt="" /> 홈 &gt; <SPAN class="nowCurrent">이용약관</span></div>-->
		</div>
		<!-- 이용약관 페이지 상단 메뉴 -->

		<div style="clear:both;height:6px;background:url('/data/design/img/main/top_boxline.gif') no-repeat;font-size:0px;"></div>
		<div style="padding:20px 30px;background:#ffffff url('/data/design/img/main/bg_boxline.gif') repeat-y;overflow:hidden;">
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<?
				echo "<tr>\n";
				if ($_data->title_type=="Y" && file_exists($Dir.DataDir."design/agreement_title.gif")) {
					echo "<td><img src=\"".$Dir.DataDir."design/agreement_title.gif\" border=\"0\" alt=\"이용약관\"></td>\n";
				} else {
					echo "<td>\n";
					/*
					echo "<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>\n";
					echo "<TR>\n";
					echo "	<TD><IMG SRC=".$Dir."images/".$_data->icon_type."/agreement_title_head.gif></TD>\n";
					echo "	<TD width=100% valign=top background=".$Dir."images/".$_data->icon_type."/agreement_title_bg.gif></TD>\n";
					echo "	<TD width=40><IMG SRC=".$Dir."images/".$_data->icon_type."/agreement_title_tail.gif ALT=></TD>\n";
					echo "</TR>\n";
					echo "</TABLE>\n";
					*/
					echo "</td>\n";
				}
				echo "</tr>\n";
				?>
				<tr>
					<td style="padding-bottom:20px;"><?=$agreement?></td>
				</tr>
			</table>
		</div>
		<div style="height:6px;background:url('/data/design/img/main/bot_boxline.gif') no-repeat;font-size:0px;"></div>
		<? include ($Dir."lib/bottom.php") ?>

	</BODY>
</HTML>