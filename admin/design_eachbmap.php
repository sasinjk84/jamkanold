<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### 페이지 접근권한 check ###############
$PageCode = "de-5";
$MenuCode = "design";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$type=$_POST["type"];
$body=$_POST["body"];
$intitle=$_POST["intitle"];

if($intitle=="Y") {
	$leftmenu="Y";
} else {
	$leftmenu="N";
}


$subject = '브랜드맵 화면';

$insertKey = 'bmap';

// 백업 / 복구
if ( $type=="store" OR $type=="restore" ) {
	$MSG = adminDesingBackup ( $type, $insertKey, $body, $subject, '', '', $leftmenu );
	$MSG .= adminDesingBackup ( $type, 'design_bmap', 'U', $subject, '', '', '', 'tblshopinfo', 'design_bmap' );
	$onload="<script>alert(\"".$MSG."\");</script>";
}

if($type=="update" && strlen($body)>0) {

	$sql = "SELECT COUNT(*) as cnt FROM tbldesignnewpage WHERE type='bmap' ";
	$result=mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
	if($row->cnt==0) {
		$sql = "INSERT tbldesignnewpage SET ";
		$sql.= "type		= 'bmap', ";
		$sql.= "subject		= '브랜드맵 화면', ";
		$sql.= "leftmenu	= '".$leftmenu."', ";
		$sql.= "body		= '".$body."' ";
		mysql_query($sql,get_db_conn());
	} else {
		$sql = "UPDATE tbldesignnewpage SET ";
		$sql.= "leftmenu	= '".$leftmenu."', ";
		$sql.= "body		= '".$body."' ";
		$sql.= "WHERE type='bmap' ";
		mysql_query($sql,get_db_conn());
	}
	mysql_free_result($result);

	$sql = "UPDATE tblshopinfo SET design_bmap='U' ";
	mysql_query($sql,get_db_conn());
	DeleteCache("tblshopinfo.cache");
	$onload="<script>alert(\"브랜드맵 화면 디자인 수정이 완료되었습니다.\");</script>";
} else if($type=="delete") {
	$sql = "DELETE FROM tbldesignnewpage WHERE type='bmap' ";
	mysql_query($sql,get_db_conn());

	$sql = "UPDATE tblshopinfo SET design_bmap='001' ";
	mysql_query($sql,get_db_conn());
	DeleteCache("tblshopinfo.cache");
	$onload="<script>alert(\"브랜드맵 화면 디자인 삭제가 완료되었습니다.\");</script>";
} else if($type=="clear") {
	$intitle="";
	$body="";
	$sql = "SELECT body FROM tbldesigndefault WHERE type='bmap' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$body=$row->body;
	}
	mysql_free_result($result);
}

if($type!="clear") {
	$body="";
	$intitle="";
	$sql = "SELECT leftmenu,body FROM tbldesignnewpage WHERE type='bmap' ";
	$result = mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$body=$row->body;
		$intitle=$row->leftmenu;
	} else {
		$intitle="Y";
	}
	mysql_free_result($result);
}

?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<SCRIPT LANGUAGE="JavaScript">
<!--
function CheckForm(type) {
	if(type=="update") {
		if(document.form1.body.value.length==0) {
			alert("브랜드맵 화면 디자인 내용을 입력하세요.");
			document.form1.body.focus();
			return;
		}
		document.form1.type.value=type;
		document.form1.submit();
	} else if(type=="delete") {
		if(confirm("브랜드맵 화면 디자인을 삭제하시겠습니까?")) {
			document.form1.type.value=type;
			document.form1.submit();
		}
	} else if(type=="clear") {
		alert("기본값 복원 후 확인버튼을 누르셔야 최종적으로 저장됩니다.");
		document.form1.type.value=type;
		document.form1.submit();
	}

	// 백업
	if(type=="store") {
		if(confirm("<?=$subject?> 디자인을 백업하시겠습니까?\n\n적용하지 않으셨다면 \"적용하기\"로 적용 하신후 백업하시기 바랍니다.\n기존 저장된 백업소스를 대체합니다.")) {
			document.form1.type.value=type;
			document.form1.submit();
			return;
		}
	}
	// 복구
	if(type=="restore") {
		if(confirm("<?=$subject?> 디자인을 백업복구 하시겠습니까?\n\n복구 하게 되면 바로 디자인 적용 됩니다.")) {
			document.form1.type.value=type;
			document.form1.submit();
			return;
		}
	}

	// 미리보기
	if(type=="preview") {
		if(document.form1.body.value.length==0) {
			alert("브랜드맵 화면 디자인 내용을 입력하세요.");
			document.form1.body.focus();
			return;
		}
		document.form1.type.value='<?=$insertKey?>';
		document.form1.target="preview";
		document.form1.action="designPreview.php";
		document.form1.submit();
		document.form1.target="";
		document.form1.action="<?=$_SERVER[PHP_SELF]?>";
	}
}

//매크로 보기(팝업)
function macroview(){
	window.open("http://www.getmall.co.kr/macro/pages/brandmap_macro.html","brandmap_macro","height=800,width=680,scrollbars=no");
}

//-->
</SCRIPT>
<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
<tr>
	<td valign="top">
	<table cellpadding="0" cellspacing="0" width=100% style="table-layout:fixed">
	<tr>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed"  background="images/con_bg.gif">
		<col width=198></col>
		<col width=10></col>
		<col width=></col>
		<tr>
			<td valign="top"  background="images/leftmenu_bg.gif">
			<? include ("menu_design.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">현재위치 : 디자인관리 &gt; 개별디자인-페이지 본문 &gt; <span class="2depth_select">브랜드맵 화면 꾸미기</span></td>
			</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width="16"><img src="images/con_t_01.gif" width="16" height="16" border="0"></td>
		<td background="images/con_t_01_bg.gif"></td>
		<td width="16"><img src="images/con_t_02.gif" width="16" height="16" border="0"></td>
	</tr>
	<tr>
		<td width="16" background="images/con_t_04_bg1.gif"></td>
		<td bgcolor="#ffffff" style="padding:10px">

			<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
			<tr><td height="8"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/design_eachbmap_title.gif" border="0"></TD>
					</tr><tr>
					<TD width="100%" background="images/title_bg.gif" height="21"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height="3"></td></tr>
			<tr>
				<td style="padding-bottom:3pt;">
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/distribute_01.gif"></TD>
					<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
					<TD><IMG SRC="images/distribute_03.gif"></TD>
				</TR>
				<TR>
					<TD background="images/distribute_04.gif"></TD>
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
					<TD width="100%" class="notice_blue">브랜드맵 화면 디자인을 자유롭게 디자인 하실 수 있습니다.</TD>
					<TD background="images/distribute_07.gif"></TD>
				</TR>
				<TR>
					<TD><IMG SRC="images/distribute_08.gif"></TD>
					<TD COLSPAN=2 background="images/distribute_09.gif"></TD>
					<TD><IMG SRC="images/distribute_10.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height="20"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/design_eachbmap_stitle1.gif" border="0"></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">
						&nbsp;&nbsp;<a href="javascript:macroview();"><img src="images/btn_macroview.gif" border="0" align="absmiddle" alt="" /></a>&nbsp;&nbsp;<a href="http://www.getmall.co.kr/macro/data/brandmap_design.zip"><img src="images/btn_defaultcode.gif" border="0" align="absmiddle" alt="" /></TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height="3"></td></tr>
			<tr>
				<td style="padding-bottom:3pt;">
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/distribute_01.gif"></TD>
					<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
					<TD><IMG SRC="images/distribute_03.gif"></TD>
				</TR>
				<TR>
					<TD background="images/distribute_04.gif"></TD>
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
					<TD width="100%" class="notice_blue">1) 매뉴얼의 <b>매크로명령어</b>를 참조하여 디자인 하세요.</span><br>2) [기본값복원]+[적용하기], [삭제하기]하면 기본템플릿으로 변경(개별디자인 소스 삭제)됨 -> 템플릿 메뉴에서 원하는 템플릿 선택.<br>3) 기본값 복원이나 삭제하기 없이도 템플릿 선택하면 개별디자인은 해제됩니다.(개별디자인 소스는 보관됨)</TD>
					<TD background="images/distribute_07.gif"></TD>
				</TR>
				<TR>
					<TD><IMG SRC="images/distribute_08.gif"></TD>
					<TD COLSPAN=2 background="images/distribute_09.gif"></TD>
					<TD><IMG SRC="images/distribute_10.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height="3"></td></tr>
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=type>
			<tr>
				<td style="padding-top:2px;"><textarea name=body style="WIDTH: 100%; HEIGHT: 300px" class="textarea" ><?=htmlspecialchars($body)?></textarea><br><input type=checkbox name=intitle value="Y" <?if($intitle=="Y")echo"checked";?> style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;"> <b><span style="letter-spacing:-0.5pt;"><span class="font_orange">기본 타이틀 이미지 유지 - 타이틀 이하 부분부터 디자인 변경</span>(미체크시 기존 타이틀 이미지 없어짐으로 직접 편집하여 사용)</b></span></td>
			</tr>
			<tr><td height=10></td></tr>
			<tr>
				<td align="center"><a href="javascript:CheckForm('update');"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:CheckForm('clear');"><img src="images/botteon_bok.gif" width="124" height="38" border="0"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:CheckForm('delete');"><img src="images/botteon_del.gif" width="113" height="38" border="0" hspace="0"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:CheckForm('preview');"><img src="images/botteon_prev.gif" width="113" height="38" border="0" hspace="2"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:CheckForm('store');"><img src="images/botteon_store.gif" border="0" hspace="2" alt="백업하기"></a>&nbsp;&nbsp;&nbsp;<a href="javascript:CheckForm('restore');"><img src="images/botteon_restore.gif" border="0" hspace="2" alt="백업복원하기"></a></td>
			</tr>
			</form>
			<tr><td height=20></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/manual_top1.gif" WIDTH=15 HEIGHT=45 ALT=""></TD>
					<TD><IMG SRC="images/manual_title.gif" WIDTH=113 HEIGHT=45 ALT=""></TD>
					<TD width="100%" background="images/manual_bg.gif"></TD>
					<TD background="images/manual_bg.gif"></TD>
					<TD><IMG SRC="images/manual_top2.gif" WIDTH=18 HEIGHT=45 ALT=""></TD>
				</TR>
				<TR>
					<TD background="images/manual_left1.gif"></TD>
					<TD COLSPAN=3 width="100%" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;"  class="menual_bg">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><p class="LIPoint"><B><span class="font_orange">브랜드맵 화면 매크로명령어</span></B>(해당 매크로명령어는 다른 페이지 디자인 작업시 사용이 불가능함)</p></td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><p>&nbsp;</p></td>
						<td >
						<table border=0 cellpadding=0 cellspacing=0 width=100%>
						<col width=150></col>
						<col width=></col>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell colspan=2 align=center>
							<B>브랜드맵 매크로 정의</B>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell width=180 align=right style="padding-right:15" nowrap>[IFBRANDMAP]<br>[IFENDBRANDMAP]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							브랜드맵 검색 결과 있을 경우에만 출력
							<pre style="line-height:15px">
<FONT class=font_blue>   <B>[IFBRANDMAP]</B>
      브랜드맵 검색 결과 <FONT COLOR="red"><B>있을</B></FONT> 경우의 내용
   <B>[IFENDBRANDMAP]</B></pre>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[SEARCHBAR]</td>
							<td class=td_con1 style="padding-left:5;">
							검색 결과바(한/영에 맞춰 자동 출력)
							<pre style="line-height:15px">
<FONT class=font_blue>   [IFBRANDMAP]
      <B>[SEARCHBAR]</B>
   [IFENDBRANDMAP]</pre>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[SEARCHBRAND]</td>
							<td class=td_con1 style="padding-left:5;">
							검색 결과(한/영에 맞춰 자동 출력)
							<pre style="line-height:15px">
<FONT class=font_blue>   [IFBRANDMAP]
      [SEARCHBAR]
	  <B>[SEARCHBRAND]</B>
   [IFENDBRANDMAP]</pre>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ENGSEARCHBAR]</td>
							<td class=td_con1 style="padding-left:5;">
							영어 검색바
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ENGBRAND]</td>
							<td class=td_con1 style="padding-left:5;">
							영어 브랜드 맵
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[KORSEARCHBAR]</td>
							<td class=td_con1 style="padding-left:5;">
							한글 검색바
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[KORBRAND]</td>
							<td class=td_con1 style="padding-left:5;">
							한글 브랜드 맵
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right bgcolor=#E9A74E style="padding-right:15">브랜드맵 스타일 정의</td>
							<td class=td_con1 bgcolor=#FEEEE2 style="padding-left:5;word-break:break-all;">
							<img width=10 height=0>
							<FONT class=font_orange>#engbaridx - 영어 검색바 스타일 정의</FONT>
							<br><img width=10 height=0>
							<FONT class=font_blue>예) #engbaridx {font-family:tahoma,verdana,Arial;font-size:8pt;color:#888888;font-weight:bold;text-decoration:none;}</FONT>
							<br><img width=0 height=7><br><img width=10 height=0>
							<FONT class=font_orange>#korbaridx - 한글 검색바 스타일 정의</FONT>
							<br><img width=10 height=0>
							<FONT class=font_blue>예) #korbaridx {font-family:돋움,굴림,Arial;font-size:9pt;color:#888888;font-weight:bold;text-decoration:none;}</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>#engtitleidx - 영어 브랜드맵 타이틀 스타일 정의</FONT>
							<br><img width=10 height=0>
							<FONT class=font_blue>예) #engtitleidx {font-family:tahoma,verdana,Arial;FONT-WEIGHT:bold;FONT-SIZE:40px;color:#3E75D2;}</FONT>
							<br><img width=0 height=7><br><img width=10 height=0>
							<FONT class=font_orange>#kortitleidx - 한글 브랜드맵 타이틀 스타일 정의</FONT>
							<br><img width=10 height=0>
							<FONT class=font_blue>예) #kortitleidx {font-family:돋움,굴림,Arial;FONT-WEIGHT:bold;FONT-SIZE:30px;color:#3E75D2;}</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>#engbrandidx - 영어 브랜드맵 목록 스타일 정의</FONT>
							<br><img width=10 height=0>
							<FONT class=font_blue>예) #engbrandidx {font-family:tahoma,verdana,Arial;FONT-SIZE:12px;color:#3E75D2;}</FONT>
							<br><img width=0 height=7><br><img width=10 height=0>
							<FONT class=font_orange>#korbrandidx - 한글 브랜드맵 목록 스타일 정의</FONT>
							<br><img width=10 height=0>
							<FONT class=font_blue>예) #korbrandidx {font-family:돋움,굴림,Arial;FONT-SIZE:12px;color:#3E75D2;}</FONT>
<br>
<B>[사용 예]</B> - 내용 본문에 아래와 같이 정의하시면 됩니다.<br>
<br>
<FONT class=font_blue>&lt;style><br>
#engtitleidx {font-family:tahoma,verdana,Arial;FONT-WEIGHT:bold;FONT-SIZE:40px;color:#3E75D2;}<br>
#kortitleidx {font-family:돋움,굴림,Arial;FONT-WEIGHT:bold;FONT-SIZE:30px;color:#3E75D2;}<br>
#engbaridx {font-family:tahoma,verdana,Arial;font-size:8pt;color:#888888;font-weight:bold;text-decoration:none;}<br>
#korbaridx {font-family:돋움,굴림,Arial;font-size:9pt;color:#888888;font-weight:bold;text-decoration:none;}<br>
#engbrandidx {font-family:tahoma,verdana,Arial;FONT-SIZE:12px;color:#3E75D2;}<br>
#korbrandidx {font-family:돋움,굴림,Arial;FONT-SIZE:12px;color:#3E75D2;}<br>
&lt;/style></FONT><br>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						</table>
						</td>
					</tr>
					<tr>
						<td width="20" colspan="2"><p>&nbsp;</p></td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><p class="LIPoint">나모,드림위버등의 에디터로 작성시 이미지경로등 작업내용이 틀려질 수 있으니 주의하세요!</p></td>
					</tr>
					</table>
					</TD>
					<TD background="images/manual_right1.gif"></TD>
				</TR>
				<TR>
					<TD><IMG SRC="images/manual_left2.gif" WIDTH=15 HEIGHT=8 ALT=""></TD>
					<TD COLSPAN=3 background="images/manual_down.gif"></TD>
					<TD><IMG SRC="images/manual_right2.gif" WIDTH=18 HEIGHT=8 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height="50"></td></tr>
			</table>

</td>
        <td width="16" background="images/con_t_02_bg.gif"></td>
    </tr>
    <tr>
        <td width="16"><img src="images/con_t_04.gif" width="16" height="16" border="0"></td>
        <td background="images/con_t_04_bg.gif"></td>
        <td width="16"><img src="images/con_t_03.gif" width="16" height="16" border="0"></td>
    </tr>
    <tr><td height="20"></td></tr>
</table>


			</td>
		</tr>
		</table>
		</td>
	</tr>
	</table>
	</td>
</tr>
</table>
<script>
function prevPage(){
	if(document.form1.body.value.length==0) {
		alert("페이지 내용을 입력하세요.");
		document.form1.body.focus();
		return;
	}

	f = document.prevForm;
	f.mode.value = 'brandmap';
	f.code.value = document.form1.body.value;
	f.submit();
}
</script>

<form name="prevForm" method="post" action="design_prev_post.php" target="_blank">
	<input type="hidden" name="code">
	<input type="hidden" name="mode">
</form>

<?=$onload?>

<? INCLUDE "copyright.php"; ?>