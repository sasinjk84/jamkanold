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

$subject = '마이페이지 단골매장';

$insertKey = 'mycustsect';

// 백업 / 복구
if ( $type=="store" OR $type=="restore" ) {
	$MSG = adminDesingBackup ( $type, $insertKey, $body, $subject, '', '', $leftmenu );
	$MSG .= adminDesingBackup ( $type, 'design_mycustsect', 'U', $subject, '', '', '', 'tblshopinfo', 'design_mycustsect' );
	$onload="<script>alert(\"".$MSG."\");</script>";
}

if($type=="update" && strlen($body)>0) {

	$sql = "SELECT COUNT(*) as cnt FROM tbldesignnewpage WHERE type='mycustsect' ";
	$result=mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
	if($row->cnt==0) {
		$sql = "INSERT tbldesignnewpage SET ";
		$sql.= "type		= 'mycustsect', ";
		$sql.= "subject		= '마이페이지 단골매장 화면', ";
		$sql.= "leftmenu	= '".$leftmenu."', ";
		$sql.= "body		= '".$body."' ";
		mysql_query($sql,get_db_conn());
	} else {
		$sql = "UPDATE tbldesignnewpage SET ";
		$sql.= "leftmenu	= '".$leftmenu."', ";
		$sql.= "body		= '".$body."' ";
		$sql.= "WHERE type='mycustsect' ";
		mysql_query($sql,get_db_conn());
	}
	mysql_free_result($result);

	$sql = "UPDATE tblshopinfo SET design_mycustsect='U' ";
	mysql_query($sql,get_db_conn());
	DeleteCache("tblshopinfo.cache");
	$onload="<script>alert(\"마이페이지 단골매장 화면 디자인 수정이 완료되었습니다.\");</script>";
} else if($type=="delete") {
	$sql = "DELETE FROM tbldesignnewpage WHERE type='mycustsect' ";
	mysql_query($sql,get_db_conn());

	$sql = "UPDATE tblshopinfo SET design_mycustsect='001' ";
	mysql_query($sql,get_db_conn());
	DeleteCache("tblshopinfo.cache");
	$onload="<script>alert(\"마이페이지 단골매장 화면 디자인 삭제가 완료되었습니다.\");</script>";
} else if($type=="clear") {
	$intitle="";
	$body="";
	$sql = "SELECT body FROM tbldesigndefault WHERE type='mycustsect' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$body=$row->body;
	}
	mysql_free_result($result);
}

if($type!="clear") {
	$body="";
	$intitle="";
	$sql = "SELECT leftmenu,body FROM tbldesignnewpage WHERE type='mycustsect' ";
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
			alert("마이페이지 단골매장 화면 디자인 내용을 입력하세요.");
			document.form1.body.focus();
			return;
		}
		document.form1.type.value=type;
		document.form1.submit();
	} else if(type=="delete") {
		if(confirm("마이페이지 단골매장 화면 디자인을 삭제하시겠습니까?")) {
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
			alert("회원가입 입력폼 디자인 내용을 입력하세요.");
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
	window.open("http://www.getmall.co.kr/macro/pages/mycustsect_macro.html","mycustsect_macro","height=800,width=680,scrollbars=no");
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
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">현재위치 : 디자인관리 &gt; 개별디자인-페이지 본문 &gt; <span class="2depth_select">단골매장 화면 꾸미기</span></td>
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
					<TD><IMG SRC="images/design_eachmycustsect_title.gif" ALT=""></TD>
					</tr><tr>
					<TD width="100%" background="images/title_bg.gif" height=21></TD>
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
					<TD width="100%" class="notice_blue">마이페이지 단골매장 화면 디자인을 자유롭게 디자인 하실 수 있습니다.</TD>
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
					<TD><IMG SRC="images/design_eachmycustsect_stitl.gif"></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">
						&nbsp;&nbsp;<a href="javascript:macroview();"><img src="images/btn_macroview.gif" border="0" align="absmiddle" alt="" /></a>&nbsp;&nbsp;<a href="http://www.getmall.co.kr/macro/data/mycustsect_design.zip"><img src="images/btn_defaultcode.gif" border="0" align="absmiddle" alt="" />
					</TD>
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
					<TD COLSPAN=3 width="100%" valign="top" class=menual_bg style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><p class="LIPoint"><B><span class="font_orange">단골매장 화면 매크로명령어</span></B>(해당 매크로명령어는 다른 페이지 디자인 작업시 사용이 불가능함)</p></td>
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
							<B>마이페이지 메뉴관련 매크로 정의</B>
							</td>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[MENU_MYHOME]</td>
							<td class=td_con1 style="padding-left:5;">
							마이페이지 홈 <FONT class=font_blue>(예:&lt;a href=[MENU_MYHOME]>마이페이지 홈&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[MENU_MYORDER]</td>
							<td class=td_con1 style="padding-left:5;">
							주문내역 <FONT class=font_blue>(예:&lt;a href=[MENU_MYORDER]>주문내역&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[MENU_MYPERSONAL]</td>
							<td class=td_con1 style="padding-left:5;">
							1:1고객게시판 <FONT class=font_blue>(예:&lt;a href=[MENU_MYPERSONAL]>1:1고객게시판&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[MENU_MYWISH]</td>
							<td class=td_con1 style="padding-left:5;">
							위시리스트 <FONT class=font_blue>(예:&lt;a href=[MENU_MYWISH]>위시리스트&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[MENU_MYRESERVE]</td>
							<td class=td_con1 style="padding-left:5;">
							적립금 <FONT class=font_blue>(예:&lt;a href=[MENU_MYRESERVE]>적립금&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[MENU_MYCOUPON]</td>
							<td class=td_con1 style="padding-left:5;">
							쿠폰 <FONT class=font_blue>(예:&lt;a href=[MENU_MYCOUPON]>쿠폰&lt;/a>)</font>
							</td>
						</tr>
						<? if($_shopdata->recom_url_ok == "Y" || $_shopdata->sns_ok == "Y"){ ?>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[MENU_PROMOTE]</td>
							<td class=td_con1 style="padding-left:5;">
							홍보내역 <FONT class=font_blue>(예:&lt;a href=[MENU_PROMOTE]>홍보내역&lt;/a>)</font>
							</td>
						</tr>
						<? } ?>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[MENU_GONGGU]</td>
							<td class=td_con1 style="padding-left:5;">
							공동구매 <FONT class=font_blue>(예:&lt;a href=[MENU_GONGGU]>공동구매&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[MENU_MYCUSTSECT]</td>
							<td class=td_con1 style="padding-left:5;">
							단골매장 <FONT class=font_blue>(예:&lt;a href=[MENU_MYCUSTSECT]>단골매장&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[MENU_MYINFO]</td>
							<td class=td_con1 style="padding-left:5;">
							회원정보수정 <FONT class=font_blue>(예:&lt;a href=[MENU_MYINFO]>회원정보수정&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[MENU_MYOUT]</td>
							<td class=td_con1 style="padding-left:5;">
							회원탈퇴 <FONT class=font_blue>(예:&lt;a href=[MENU_MYOUT]>회원탈퇴&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr><td colspan=2 height=10></td></tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[CHECKALL]</td>
							<td class=td_con1 style="padding-left:5;">
							단골매장 전체선택 버튼 <FONT class=font_blue>(예:&lt;a href=[CHECKALL]>전체선택&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[CHECKDELETE]</td>
							<td class=td_con1 style="padding-left:5;">
							선택 단골매장 삭제 버튼 <FONT class=font_blue>(예:&lt;a href=[CHECKDELETE]>선택 단골매장 삭제&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[CHECKMAILYES]</td>
							<td class=td_con1 style="padding-left:5;">
							선택 단골매장 메일수신 버튼 <FONT class=font_blue>(예:&lt;a href=[CHECKMAILYES]>선택 단골매장 메일수신&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[CHECKMAILNO]</td>
							<td class=td_con1 style="padding-left:5;">
							선택 단골매장 메일수신 거부 버튼 <FONT class=font_blue>(예:&lt;a href=[CHECKMAILNO]>선택 단골매장 메일수신 거부&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell width=180 align=right style="padding-right:15" nowrap>[IFMINI]<br>[IFELSEMINI]<br>[IFENDMINI]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							단골매장이 있을 경우와 없을 경우
							<pre style="line-height:15px">
<FONT class=font_blue>   <B>[IFMINI]</B>
      단골매장이 <FONT COLOR="red"><B>있을</B></FONT> 경우의 내용
   <B>[IFELSEMINI]</B>
      단골매장이 <FONT COLOR="red"><B>없을</B></FONT> 경우의 내용
   <B>[IFENDMINI]</B></font></pre>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell width=180 align=right style="padding-right:15" nowrap>[FORMINI]<br>[FORENDMINI]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							[FORMINI] 단골매장 하나에 대한 내용 [FORENDMINI]
							<pre style="line-height:15px">
<FONT class=font_blue>   [IFMINI]
       <B>[FORMINI]</B>단골매장 하나에 대한 내용 기술<B>[FORENDMINI]</B>
   [IFELSEMINI]
       등록된 단골매장이 없습니다.
   [IFENDMINI]</font></pre>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[MINI_CHECKBOX]</td>
							<td class=td_con1 style="padding-left:5;">
							해당 단골매장 선택박스
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[MINI_LINK]</td>
							<td class=td_con1 style="padding-left:5;">
							해당 단골매장 링크 <FONT class=font_blue>(예:&lt;a href=[MINI_LINK]>[MINI_LOGOIMG]&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[MINI_LOGOIMG]</td>
							<td class=td_con1 style="padding-left:5;">
							해당 단골매장 로고 이미지
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[MINI_MAIL]</td>
							<td class=td_con1 style="padding-left:5;">
							해당 단골매장 메일 수신여부 (수신 또는 거부)
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell width=180 align=right style="padding-right:15" nowrap>[IFMAILOK]<br>[IFELSEMAILOK]<br>[IFENDMAILOK]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							메일 수신여부가 "수신"일 경우와 "거부"일 경우 "수신/거부" 버튼 표시
							<pre style="line-height:15px">
<FONT class=font_blue>   <B>[IFMAILOK]</B>
      메일 수신여부가 <FONT COLOR="red"><B>수신</B></FONT>일 경우 <FONT COLOR="red"><B>거부</B></FONT>버튼 내용
   <B>[IFELSEMAILOK]</B>
      메일 수신여부가 <FONT COLOR="red"><B>거부</B></FONT>일 경우 <FONT COLOR="red"><B>수신</B></FONT>버튼 내용
   <B>[IFENDMAILOK]</B></font></pre>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell width=180 align=right style="padding-right:15" nowrap>[MAILOK]</td>
							<td class=td_con1 width=100% style="padding-left:5;">
							메일 수신/거부 버튼
							<pre style="line-height:15px">
<FONT class=font_blue>   <B>[IFMAILOK]</B>
	  &lt;a hred=[MAILOK]>거부&lt;/a>
   <B>[IFELSEMAILOK]</B>
	  &lt;a hred=[MAILOK]>수신&lt;/a>
   <B>[IFENDMAILOK]</B></font></pre>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[MINI_NAME]</td>
							<td class=td_con1 style="padding-left:5;">
							단골매장 이름
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[MINIHOT?]</td>
							<td class=td_con1 style="padding-left:5;">
							해당 단골매장의 추천상품
									<br><img width=10 height=0>
									<FONT class=font_orange>? : 상품 표시 개수 (1~6)</FONT>
									<br>
									<FONT class=font_blue>예) [MINIHOT3]</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[PAGE]</td>
							<td class=td_con1 style="padding-left:5;">
							페이지 표시
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
	f.mode.value = 'mycustsect';
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