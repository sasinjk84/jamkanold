<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

if( $preview===true ) {

	$sql = "SELECT * FROM `tbldesignnewpage_prev` WHERE `type` = 'useinfo' LIMIT 1;";
	$result = mysql_query($sql,get_db_conn());

	if($row=mysql_fetch_object($result)) {
		$useinfo = $row->body;
		$leftmenu = $row->leftmenu;
	}



} else {

	$sql = "SELECT useinfo FROM tbldesign ";
	$result = mysql_query($sql,get_db_conn());

	if($row=mysql_fetch_object($result)) {
		$useinfo=$row->useinfo;
		if (substr($useinfo,-2,1)=="") {
			$leftmenu = substr($useinfo,-1);
			$useinfo = substr($useinfo,0,-2);
		} else {
			$leftmenu = "Y";	//N:��� Ÿ��Ʋ �̹��� �⺻ ���, Y:��� Ÿ��Ʋ �̹��� ����ӿ� ����
		}

	}
	mysql_free_result($result);

	if (strpos(strtolower($useinfo),"table")!=false) $useinfo = "<pre>".$useinfo."</pre>";
	else $useinfo = ereg_replace("\n","<br>",$useinfo);

}

?>
<HTML>
	<HEAD>
		<TITLE><?=$_data->shoptitle?> - �̿�ȳ�</TITLE>
		<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">
		<META http-equiv="X-UA-Compatible" content="IE=Edge" />

		<META name="description" content="<?=(strlen($_data->shopdescription)>0?$_data->shopdescription:$_data->shoptitle)?>">
		<META name="keywords" content="<?=$_data->shopkeyword?>">
		<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
		<?include($Dir."lib/style.php")?>
	</HEAD>

	<body<?=(substr($_data->layoutdata["MOUSEKEY"],0,1)=="Y"?" oncontextmenu=\"return false;\"":"")?><?=(substr($_data->layoutdata["MOUSEKEY"],1,1)=="Y"?" ondragstart=\"return false;\" onselectstart=\"return false;\"":"")?> leftmargin="0" marginwidth="0" topmargin="0" marginheight="0"><?=(substr($_data->layoutdata["MOUSEKEY"],2,1)=="Y"?"<meta http-equiv=\"ImageToolbar\" content=\"No\">":"")?>

		<? include ($Dir.MainDir.$_data->menu_type.".php") ?>

		<!-- �̿�ȳ� ������ ��� �޴� -->
		<div class="currentTitle">
			<div class="titleimage">�̿�ȳ�</div>
			<!--<div class="current"><img src="/data/design/img/sub/icon_home.gif" border="0" alt="" /> Ȩ &gt; <SPAN class="nowCurrent">�̿�ȳ�</span></div>-->
		</div>
		<!-- �̿�ȳ� ������ ��� �޴� -->

		<div style="clear:both;height:6px;background:url('/data/design/img/main/top_boxline.gif') no-repeat;font-size:0px;"></div>
		<div style="padding:20px 30px;background:#ffffff url('/data/design/img/main/bg_boxline.gif') repeat-y;overflow:hidden;">
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<?
					if ($leftmenu!="N") {
						echo "<tr>\n";
						if ($_data->title_type=="Y" && file_exists($Dir.DataDir."design/useinfo_title.gif")) {
							echo "<td><img src=\"".$Dir.DataDir."design/useinfo_title.gif\" border=\"0\" alt=\"�̿�ȳ�\"></td>\n";
						} else {
							echo "<td>\n";
							/*
							echo "<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>\n";
							echo "<TR>\n";
							echo "	<TD><IMG SRC=".$Dir."images/".$_data->icon_type."/useinfo_title_head.gif ALT=></TD>\n";
							echo "	<TD width=100% valign=top background=".$Dir."images/".$_data->icon_type."/useinfo_title_bg.gif></TD>\n";
							echo "	<TD width=40><IMG SRC=".$Dir."images/".$_data->icon_type."/useinfo_title_tail.gif ALT=></TD>\n";
							echo "</TR>\n";
							echo "</TABLE>\n";
							*/
							echo "</td>\n";
						}
						echo "</tr>\n";
					}
				?>
				<tr>
					<td align="center">
				<?
					if(strlen($useinfo)>0) {
						echo $useinfo;
					} else {
						include($Dir.TempletDir."useinfo/useinfo".$_data->design_useinfo.".php");
					}
				?>
					</td>
				</tr>
			</table>
		</div>
		<div style="height:6px;background:url('/data/design/img/main/bot_boxline.gif') no-repeat;font-size:0px;"></div>
		<? include ($Dir."lib/bottom.php") ?>

	</BODY>
</HTML>