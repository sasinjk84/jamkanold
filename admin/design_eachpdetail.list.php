<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### 페이지 접근권한 check ###############
$PageCode = "de-5";
$MenuCode = "nomenu";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$mode=$_POST["mode"];
$code=$_POST["code"];
$detail_type=$_POST["detail_type"];

if(strlen($code)==0) $code="ALL";

$body=$_POST["body"];
$added=$_POST["added"];


if($added=="Y") {
	$leftmenu="Y";
} else {
	$leftmenu="N";
}

$subject = '상품상세 화면';

$insertKey = "prdetail";

// 백업 / 복구
if ( $mode=="store" OR $mode=="restore" ) {
	$MSG = adminDesingBackup ( $mode, $insertKey, $body, $subject, $code, '', $leftmenu );
	$onload="<script>alert(\"".$MSG."\");</script>";
}



if($mode=="update" && strlen($body)>0) {

	$sql = "SELECT COUNT(*) as cnt FROM tbldesignnewpage WHERE type='prdetail' AND code='".$code."' ";
	$result=mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
	if($row->cnt==0) {
		$sql = "INSERT tbldesignnewpage SET ";
		$sql.= "type		= 'prdetail', ";
		$sql.= "subject		= '상품상세 화면', ";
		$sql.= "leftmenu	= '".$leftmenu."', ";
		$sql.= "body		= '".$body."', ";
		$sql.= "code		= '".$code."' ";
		mysql_query($sql,get_db_conn());
	} else {
		$sql = "UPDATE tbldesignnewpage SET ";
		$sql.= "leftmenu	= '".$leftmenu."', ";
		$sql.= "body		= '".$body."' ";
		$sql.= "WHERE type='prdetail' AND code='".$code."' ";
		mysql_query($sql,get_db_conn());
	}
	mysql_free_result($result);

	$sql="";
	if($leftmenu=="Y") {	//카테고리화면 개별디자인 적용
		$sql = "UPDATE tblproductcode SET detail_type=CONCAT(SUBSTRING(detail_type,1,5),'U') ";
	} else if($leftmenu=="N") {	//카테고리화면 템플릿 적용
		$sql = "UPDATE tblproductcode SET detail_type=SUBSTRING(detail_type,1,5) ";
	}
	if(strlen($sql)>0) {
		$csql = $sql;
		$sql.= "WHERE 1=1 ";
		$sql.= "AND type not in('S','SX','SM','SMX') ";
		$sql.= "AND codeA <> '999' ";
		if(strlen($code)==12) {
			$codeA=substr($code,0,3);
			$codeB=substr($code,3,3);
			$codeC=substr($code,6,3);
			$codeD=substr($code,9,3);
			$sql.= "AND codeA='".$codeA."' AND codeB='".$codeB."' ";
			$sql.= "AND codeC='".$codeC."' AND codeD='".$codeD."' ";
			$msg="해당 상품상세 화면 디자인이 ";
			if($leftmenu=="Y") {
				$script_p="parent.ModifyCodeDesign('".$code."','".substr($detail_type,0,5)."U"."','0');";
			} else if($leftmenu=="N") {
				$script_p="parent.ModifyCodeDesign('".$code."','".substr($detail_type,0,5)."','0');";
			}
		} else {
			$msg="모든 상품상세 화면 디자인이 ";
		}
		mysql_query($sql,get_db_conn());


		if(strlen($code)==12) {
			// 하위 카테고리중 부모 디자인 변경 따르는 부분에 대한 처리
			$cwhere = array();
			$cdep = false;
			for($i=0;$i<3;$i++){
				$key = 	'code'.chr(65+$i);
				if(${$key} == '000'){
					array_push($cwhere,$key."!='000'");
					break;
				}else array_push($cwhere,$key."='".${$key}."'");
			}
			array_push($cwhere,"dsameparent='1'");
			$csql .= " where ".implode(' and ',$cwhere);
			@mysql_query($csql,get_db_conn());
		}



		if($leftmenu=="Y") {
			$msg.="개별디자인으로 변경되었습니다.";
		} else if($leftmenu=="N") {
			$msg.="기본으로 제공되는 템플릿 디자인으로 변경되었습니다.";
		}
	}
	if(strlen($code)==12) {
		$onload="<script>".$script_p." alert(\"".$msg."\");</script>";
	} else {
		echo "<script>alert(\"".$msg."\");parent.location.reload();</script>";
		exit;
	}
} else if($mode=="delete") {
	$sql = "DELETE FROM tbldesignnewpage WHERE type='prdetail' AND code='".$code."' ";
	mysql_query($sql,get_db_conn());


	$csql = $sql = "UPDATE tblproductcode SET detail_type=SUBSTRING(detail_type,1,5) ";
	$sql.= "WHERE 1=1 ";
	if(strlen($code)==12) {
		$codeA=substr($code,0,3);
		$codeB=substr($code,3,3);
		$codeC=substr($code,6,3);
		$codeD=substr($code,9,3);
		$sql.= "AND codeA='".$codeA."' AND codeB='".$codeB."' ";
		$sql.= "AND codeC='".$codeC."' AND codeD='".$codeD."' ";
		$msg="해당 상품상세 화면 디자인이 기본으로 제공되는 템플릿 디자인으로 변경되었습니다.";
		$script_p="parent.ModifyCodeDesign('".$code."','".substr($detail_type,0,5)."','0');";
	} else {
		$msg="모든 카테고리페이지 개별디자인이 삭제되었습니다.\\n해당 카테고리의 개별디자인은 삭제되지 않았습니다.";
	}
	mysql_query($sql,get_db_conn());

	if(strlen($code)==12) {
		// 하위 카테고리중 부모 디자인 변경 따르는 부분에 대한 처리
		$cwhere = array();
		$cdep = false;
		for($i=0;$i<3;$i++){
			$key = 	'code'.chr(65+$i);
			if(${$key} == '000'){
				array_push($cwhere,$key."!='000'");
				break;
			}else array_push($cwhere,$key."='".${$key}."'");
		}
		array_push($cwhere,"dsameparent='1'");
		$csql .= " where ".implode(' and ',$cwhere);
		@mysql_query($csql,get_db_conn());
	}

	if(strlen($code)==12) {
		$onload="<script>".$script_p." alert(\"".$msg."\");</script>";
	} else {
		echo "<script>alert(\"".$msg."\");parent.location.reload();</script>";
		exit;
	}
} else if($mode=="clear") {
	$body="";
	$sql = "SELECT body FROM tbldesigndefault WHERE type='prdetail' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$body=$row->body;
	}
	mysql_free_result($result);
}

if($mode!="clear") {
	$body="";
	$added="";
	$sql = "SELECT leftmenu,body FROM tbldesignnewpage WHERE type='prdetail' AND code='".$code."' ";
	$result = mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$body=$row->body;
		$added=$row->leftmenu;
	} else {
		$added="Y";
	}
	mysql_free_result($result);
}

?>
<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script>var LH = new LH_create();</script>
<script for=window event=onload>LH.exec();</script>
<script>LH.add("parent_resizeIframe('MainPrdtFrame')");</script>
<SCRIPT LANGUAGE="JavaScript">
<!--
function CheckForm(mode) {
	if(mode=="update") {
		if(document.form1.body.value.length==0) {
			alert("상품상세 화면 디자인 내용을 입력하세요.");
			document.form1.body.focus();
			return;
		}
		if(document.form1.code.value.length==12) {
			msg="해당 ";
		} else {
			msg="모든 ";
		}
		if(document.form1.added.checked==true) {
			if(!confirm(msg+"상품상세 화면 디자인을 개별디자인으로 변경하시겠습니까?")) {
				return;
			}
		} else {
			if(!confirm(msg+"상품상세 화면 디자인을 기본으로 제공되는 템플릿 디자인으로 변경하시겠습니까?\n\n입력하신 개별디자인 소스는 저장됩니다.")) {
				return;
			}
		}
		document.form1.mode.value=mode;
		document.form1.submit();
	} else if(mode=="delete") {
		if(confirm("상품상세 화면 개별디자인을 삭제하시겠습니까?")) {
			document.form1.mode.value=mode;
			document.form1.submit();
		}
	} else if(mode=="clear") {
		alert("기본값 복원 후 [적용하기]를 클릭하세요. 클릭 후 페이지에 적용됩니다.");
		document.form1.mode.value=mode;
		document.form1.submit();
	}

	// 백업
	if(mode=="store") {
		if(confirm("<?=$subject?> 디자인을 백업하시겠습니까?\n\n적용하지 않으셨다면 \"적용하기\"로 적용 하신후 백업하시기 바랍니다.\n기존 저장된 백업소스를 대체합니다.")) {
			document.form1.mode.value=mode;
			document.form1.submit();
		}
	}
	// 복구
	if(mode=="restore") {
		if(confirm("<?=$subject?> 디자인을 백업복구 하시겠습니까?\n\n복구 하게 되면 바로 디자인 적용 됩니다.")) {
			document.form1.mode.value=mode;
			document.form1.submit();
		}
	}

	// 미리보기
	if(mode=="preview") {
		if(document.form1.body.value.length==0) {
			alert("상품 카테고리 디자인 내용을 입력하세요.");
			document.form1.body.focus();
			return;
		}
		if(document.form1.code.value.length==12) {
		} else {
			//alert("미리보기는 모든카테고리 적용에서는 확인 할수 없습니다.\n카테고리를 선택해 주세요!");
			//return;
		}

		document.form1.mode.value='<?=$insertKey?>';
		document.form1.target="preview";
		document.form1.action="designPreview.php";
		document.form1.submit();
		document.form1.target="";
		document.form1.action="<?=$_SERVER[PHP_SELF]?>";
	}

}

//매크로 보기(팝업)
function macroview(){
	window.open("http://www.getmall.co.kr/macro/pages/prdetail_macro.html","top_macro","height=800,width=680,scrollbars=no");
}
//-->
</SCRIPT>
<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
<tr>
	<td>
	<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
	<TR>
		<TD><IMG SRC="images/design_productdt_stitle1.gif" WIDTH="240" HEIGHT=31 ALT=""></TD>
		<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;&nbsp;<a href="javascript:macroview();"><img src="images/btn_macroview.gif" border="0" align="absmiddle" alt="" /></a>&nbsp;&nbsp;<a href="http://www.getmall.co.kr/macro/data/prdetail_design.zip"><img src="images/btn_defaultcode.gif" border="0" align="absmiddle" alt="" /></a></TD>
		<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
	</TR>
	</TABLE>
	</td>
</tr>
<tr>
	<td height=3></td>
</tr>
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
		<TD width="100%" class="notice_blue">
			1) 매뉴얼의 <b>매크로명령어</b>를 참조하여 디자인 하세요.</span><br />
			2) <span class="font_orange" style="font-size:11px;"><u>상품상세 화면 매크로 관련 파일</u> : <b> /front/productdetail.php, /front/productdetail_text.php, /templet/product/detail_U.php</b> (파일 수정시 기존 파일은 반드시 백업하시기 바랍니다.)</span><br />
			3) [기본값복원]+[적용하기], [삭제하기]하면 기본템플릿으로 변경(개별디자인 소스 삭제)됨 -> 템플릿 메뉴에서 원하는 템플릿 선택.<br />
			4) 기본값 복원이나 삭제하기 없이도 템플릿 선택하면 개별디자인은 해제됩니다.(개별디자인 소스는 보관됨)
		</TD>
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
<tr>
	<td height="20"></td>
</tr>
<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
<input type=hidden name=mode>
<input type=hidden name=code value="<?=$code?>">
<input type=hidden name=detail_type value="<?=$detail_type?>">
<tr>
	<td><textarea name=body style="WIDTH: 100%; HEIGHT: 300px" class="textarea"><?=htmlspecialchars($body)?></textarea><br><input type=checkbox name=added value="Y" <?if($added=="Y")echo"checked";?>> <b><span class="font_orange">적용하기 체크</span>(체크해야만 디자인이 적용됩니다. 미체크시 소스만 보관되고 적용은 되지 않습니다.)</b></td>
</tr>
<tr>
	<td><p>&nbsp;</p></td>
</tr>
<tr>
	<td align="center"><a href="javascript:CheckForm('update');"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:CheckForm('clear');"><img src="images/botteon_bok.gif" width="124" height="38" border="0" hspace="2"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:CheckForm('delete');"><img src="images/botteon_del.gif" width="113" height="38" border="0" hspace="0"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:CheckForm('preview');"><img src="images/botteon_prev.gif" width="113" height="38" border="0" hspace="2"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:CheckForm('store');"><img src="images/botteon_store.gif" border="0" hspace="2" alt="백업하기"></a>&nbsp;&nbsp;&nbsp;<a href="javascript:CheckForm('restore');"><img src="images/botteon_restore.gif" border="0" hspace="2" alt="백업복원하기"></a></td>
</tr>
</form>
<tr>
	<td height=20></td>
</tr>
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
		<TD COLSPAN=3 width="100%" valign="top" class="menual_bg" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;">
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
			<td><p class="LIPoint"><B><span class="font_orange">상품상세 화면 매크로명령어</span></B>(해당 매크로명령어는 다른 페이지 디자인 작업시 사용이 불가능함)</p></td>
		</tr>
		<tr>
			<td width="20" align="right" valign="top"></td>
			<td>
			<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
			<col width=150></col>
			<col width=></col>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[CODENAME]</td>
				<td class=td_con1 style="padding-left:5;">
				현재 카테고리명
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[CODENAVI??????_??????]</td>
				<td class=td_con1 style="padding-left:5;">
				카테고리 네비게이션
						<br><img width=10 height=0>
						<FONT class=font_orange>앞?????? : 현재 카테고리가 속한 카테고리 색상</FONT> - <FONT COLOR="red">"#"제외</FONT>
						<br><img width=10 height=0>
						<FONT class=font_orange>뒤?????? : 현재 카테고리의 색상</FONT> - <FONT COLOR="red">"#"제외</FONT>
						<br>
						<FONT class=font_blue>예) [CODENAVI] or [CODENAVI000000_FF0000]</FONT>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[CLIPCOPY]</td>
				<td class=td_con1 style="padding-left:5;">
				현재주소 복사 버튼 <FONT class=font_blue>(예:&lt;a href=[CLIPCOPY]>주소복사&lt;/a>)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[IFVENDER]<br>[IFENDVENDER]</td>
				<td class=td_con1 style="padding-left:5;">
				입점업체 상품일 경우 (입점업체 상품일 경우에만 내용 출력)
		<pre style="line-height:15px">
<font class=font_blue><B>[IFVENDER]</B>
  내용
<B>[IFENDVENDER]</B></font></pre>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[VENDER_IMAGE]</td>
				<td class=td_con1 style="padding-left:5;">
					입점업체 이미지 - [IFVENDER] [IFENDVENDER] 내용에 사용
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[VENDER_NAME]</td>
				<td class=td_con1 style="padding-left:5;">
					입점업체명 <FONT class=font_blue>(예 : 공급업체 : [VENDER_NAME])</font> - [IFVENDER] [IFENDVENDER] 내용에 사용
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[VENDER_OWNER]</td>
				<td class=td_con1 style="padding-left:5;">
					입점업체 대표자명 <FONT class=font_blue>(예 : 대표자 : [VENDER_OWNER])</font> - [IFVENDER] [IFENDVENDER] 내용에 사용
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[VENDER_MINISHOP]</td>
				<td class=td_con1 style="padding-left:5;">
					업체 미니샵 링크 <FONT class=font_blue>(예 : &lt;a href=[VENDER_MINISHOP]>[VENDER_NAME]&lt;/a>)</font> - [IFVENDER] [IFENDVENDER] 내용에 사용
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[VENDER_PRDTCNT]</td>
				<td class=td_con1 style="padding-left:5;">
					전체상품수 <FONT class=font_blue>(예 : 전체상품수 : &lt;B>[VENDER_PRDTCNT]&lt;/B>개)</font> - [IFVENDER] [IFENDVENDER] 내용에 사용
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[VENDER_REGIST]</td>
				<td class=td_con1 style="padding-left:5;">
					단골매장등록 버튼 <FONT class=font_blue>(예 : &lt;a href=[VENDER_REGIST]>단골매장등록&lt;/a>)</font> - [IFVENDER] [IFENDVENDER] 내용에 사용
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>

			<tr>
				<td class=table_cell align=right style="padding-right:15">[VENDERPRODUCT]</td>
				<td class=td_con1 style="padding-left:5;">
					입점업체 등록 기타상품 (등록일 기준 최근 3개 상품 출력)
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>

			<tr>
				<td class=table_cell align=right style="padding-right:15"><B>[STARTFORM]</B></td>
				<td class=td_con1 style="padding-left:5;">
					폼의 시작 (수량변경, 장바구니/바로구매등을 하기 위해서는 꼭 넣어주어야함) - 폼의 끝나는 부분에서는 <B>[ENDFORM]</B> 입력
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[PRNAME]</td>
				<td class=td_con1 style="padding-left:5;">
					상품명
				</td>
			</tr>
			<!--
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[COUPON1]</td>
				<td class=td_con1 style="padding-left:5;">
				상품쿠폰이미지 + 쿠폰설명 표시
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[COUPON2]</td>
				<td class=td_con1 style="padding-left:5;">
				상품쿠폰이미지만 표시
				</td>
			</tr>
			-->
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[PRMSG]</td>
				<td class=td_con1 style="padding-left:5;">
					상품 홍보문구
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[PREV]</td>
				<td class=td_con1 style="padding-left:5;">
				이전상품 <FONT class=font_blue>(예:&lt;a href=[PREV]>이전상품&lt;/a>)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[NEXT]</td>
				<td class=td_con1 style="padding-left:5;">
				다음상품 <FONT class=font_blue>(예:&lt;a href=[NEXT]>다음상품&lt;/a>)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[PRIMAGE]</td>
				<td class=td_con1 style="padding-left:5;">
				상품이미지
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[GONGTABLE]</td>
				<td class=td_con1 style="padding-left:5;">
				공동구매 가격변동표 (시작가, 현재가)
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[GONGINFO]</td>
				<td class=td_con1 style="padding-left:5;">
				공동구매 상품정보 표시 (가격변동표 및 시중가격,현재가격,구매수량,상품옵션 등)
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[PRINFO]</td>
				<td class=td_con1 style="padding-left:5;">
				상품정보 - 상품스팩 노출 설정된 순서대로
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[SELLPRICE]</td>
				<td class=td_con1 style="padding-left:5;">
				판매가격
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[GONGPRICE]</td>
				<td class=td_con1 style="padding-left:5;">
				공동구매 현재가
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[DOLLAR]</td>
				<td class=td_con1 style="padding-left:5;">
				해외화폐
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[PRODUCTION]</td>
				<td class=td_con1 style="padding-left:5;">
				제조사
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[MADEIN]</td>
				<td class=td_con1 style="padding-left:5;">
				원산지 <FONT class=font_blue>(예:원산지 : [MADEIN])</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[BRAND]</td>
				<td class=td_con1 style="padding-left:5;">
				브랜드 <FONT class=font_blue>(예:브랜드 : [BRAND])</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[BRANDLINK]</td>
				<td class=td_con1 style="padding-left:5;">
				브랜드 목록 <FONT class=font_blue>(예:&lt;a href=[BRANDLINK]>바로가기&lt;/a>)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[MODEL]</td>
				<td class=td_con1 style="padding-left:5;">
				모델명 <FONT class=font_blue>(예:모델명 : [MODEL])</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[GIFT_PRICE]</td>
				<td class=td_con1 style="padding-left:5;">
				사은품 정책-구매가격(레이어)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[OPENDATE]</td>
				<td class=td_con1 style="padding-left:5;">
				출시일 <FONT class=font_blue>(예:출시일 : [OPENDATE])</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[SELFCODE]</td>
				<td class=td_con1 style="padding-left:5;">
				진열코드 <FONT class=font_blue>(예:진열코드 : [SELFCODE])</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[ADDCODE]</td>
				<td class=td_con1 style="padding-left:5;">
				상품 특이사항 <FONT class=font_blue>(예:특이사항 : [ADDCODE])</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[DELIPRICE]</td>
				<td class=td_con1 style="padding-left:5;">
				배송비 <FONT class=font_blue>(예:배송비 : [DELIPRICE])</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell width=180 align=right style="padding-right:15" nowrap>[IFUSPEC?]<br>[IFENDUSPEC?]</td>
				<td class=td_con1 width=100% style="padding-left:5;">
				사용자 정의 스펙 항목 이름 또는 값이 있을 경우에만 출력
				<br><img width=10 height=0>
				<FONT class=font_orange>? : 사용자 정의 스펙 번호 1,2,3,4,5</FONT> - 번호는 항상 쌍으로 이뤄져야 됩니다.
				<pre style="line-height:15px">
<FONT class=font_blue>   <B>[IFUSPEC1]</B>
     사용자 정의 스펙 항목 1 이름 또는 값이 <FONT COLOR="red"><B>있을</B></FONT> 경우의 내용
   <B>[IFENDUSPEC1]</B>
               ㆍ
               ㆍ
               ㆍ
<FONT class=font_blue>   <B>[IFUSPEC5]</B>
     사용자 정의 스펙 항목 5 이름 또는 값이 <FONT COLOR="red"><B>있을</B></FONT> 경우의 내용
   <B>[IFENDUSPEC5]</B></pre>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[USPECNAME?]<br>[USPECVALUE?]</td>
				<td class=td_con1 style="padding-left:5;">
				사용자 정의 스펙
						<br><img width=10 height=0>
						<FONT class=font_orange>? : 사용자 정의 스펙 번호 1,2,3,4,5</FONT>
<pre style="line-height:15px">
<FONT class=font_blue>   [IFUSPEC1]
     <B>[USPECNAME1] : [USPECVALUE1]</B>
   [IFENDUSPEC1]
               ㆍ
               ㆍ
               ㆍ
<FONT class=font_blue>   [IFUSPEC5]
     <B>[USPECNAME5] : [USPECVALUE5]</B>
   [IFENDUSPEC5]</pre>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[CONSUMPRICE]</td>
				<td class=td_con1 style="padding-left:5;">
				시중가격 <FONT class=font_blue>(예:시중가격 : [CONSUMPRICE]원)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[RESERVE]</td>
				<td class=td_con1 style="padding-left:5;">적립금 <FONT class=font_blue>(예:적립금 : [RESERVE]원)</font></td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class="table_cell" align="right" style="padding-right:15px;">[ABLE_COUPON_POP]</td>
				<td class=td_con1 style="padding-left:5;">쿠폰목록 링크(팝업) <FONT class=font_blue>(예:&lt;a href=[ABLE_COUPON_POP]>적용가능 전체쿠폰&lt;/a&gt;)</font></td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[QUANTITY]</td>
				<td class=td_con1 style="padding-left:5;">
				수량입력박스 <FONT class=font_blue>(예:[QUANTITY]개)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[QUANTITY_UP]</td>
				<td class=td_con1 style="padding-left:5;">
				수량증가 함수 <FONT class=font_blue>(예:&lt;a href=[QUANTITY_UP]>증가&lt;/a>)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[QUANTITY_DN]</td>
				<td class=td_con1 style="padding-left:5;">
				수량감소 함수 <FONT class=font_blue>(예:&lt;a href=[QUANTITY_DN]>감소&lt;/a>)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell bgcolor=#F0F0F0>
				<pre style="line-height:15px">
[IFOPTION]
    [IFOPTION1]
        [OPTION1]
    [IFENDOPTION1]
    [IFOPTION2]
        [OPTION2]
    [IFENDOPTION2]
[IFENDOPTION]</pre>
				</td>
				<td class=td_con1 style="padding-left:5;">
					상품옵션 처리 매크로 정의
					<pre style="line-height:15px">
  [IFOPTION]		- 옵션이 있을 경우
         [IFOPTION1]		- 옵션1이 있을 경우
               [OPTION1]	- 첫번째 옵션 내용 <FONT COLOR="red">(예 : &lt;div align=center>[OPTION1]&lt;/div>)</FONT>
         [IFENDOPTION1]	- 옵션1 끝
         [IFOPTION2]		- 옵션2가 있을 경우
               [OPTION2]	- 두번째 옵션 내용 <FONT COLOR="red">(예 : &lt;div align=center>[OPTION2]&lt;/div>)</FONT>
         [IFENDOPTION2]	- 옵션2 끝
  [IFENDOPTION]		- 옵션 전체 끝</pre>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[IFPACKAGE]<br>[IFENDPACKAGE]</td>
				<td class=td_con1 style="padding-left:5;">
				패키지일 경우 (패키지가 선택된 경우에만 내용 출력)
		<pre style="line-height:15px">
<font class=font_blue><B>[IFPACKAGE]</B>
  내용
<B>[IFENDPACKAGE]</B></font></pre>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class="table_cell" align="right" style="padding-right:15px;">[SNSBUTTON]</td>
				<td class="td_con1" style="padding-left:5px;">
					SNS 상품홍보 및 이메일, SMS
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15px;">[IFPESTER]<br>[PESTER]<br>[IFENDPESTER]</td>
				<td class=td_con1 style="padding-left:5px;">
					조르기
					<pre style="line-height:15px">
  [IFPESTER]	- 조르기 기능 있을경우
	&lt;a href=[PESTER]>조르기(텍스트 or 이미지) &lt;/a>
  [IFENDPESTER]	- 조르기 끝
					</pre>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[IFPRESENT]<br>[PRESENT]<br>[IFENDPRESENT]</td>
				<td class=td_con1 style="padding-left:5;">
				선물하기
					<pre style="line-height:15px">
  [IFPRESENT]	- 선물하기 기능 있을경우
	&lt;a href=[PRESENT]>선물하기(텍스트 or 이미지) &lt;/a>
  [IFENDPRESENT]	- 선물하기 끝
					</pre>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[PACKAGESELECT]</td>
				<td class=td_con1 style="padding-left:5;">
				패키지선택 <FONT class=font_blue>(예:패키지선택 : [PACKAGESELECT])</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[BASKETIN]</td>
				<td class=td_con1 style="padding-left:5;">
				장바구니 담기 <FONT class=font_blue>(예:&lt;a href=[BASKETIN]>장바구니 담기&lt;/a>)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[BARO]</td>
				<td class=td_con1 style="padding-left:5;">
				바로구매 <FONT class=font_blue>(예:&lt;a href=[BARO]>바로구매&lt/a>)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[WISHIN]</td>
				<td class=td_con1 style="padding-left:5;">
				WishList담기 <FONT class=font_blue>(예:&lt;a href=[WISHIN]>위시리스트 담기&lt;/a>)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15"><B>[ENDFORM]</B></td>
				<td class=td_con1 style="padding-left:5;">
				폼의 끝 (수량변경, 장바구니/바로구매등을 하기 위해서는 꼭 넣어주어야함)
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>

			<tr>
				<td class="table_cell" align="right" style="padding-right:15px;"><B>[PRODUCTDETAIL_EVENT]</B></td>
				<td class=td_con1 style="padding-left:5;">
					상품상세 공통이벤트 관리 <FONT class=font_blue>(프로모션 > 이벤트/사은품 기능 설정 > 상품상세 공통이벤트 관리에서 등록가능)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>

			<tr><td colspan=2 height=5></td></tr>

			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[PACKAGETABLE]</td>
				<td class=td_con1 style="padding-left:5;">
				패키지 정보 출력
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[ASSEMBLETABLE]</td>
				<td class=td_con1 style="padding-left:5;">
				코디/조립 상품 선택
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[COUPON_LIST]</td>
				<td class=td_con1 style="padding-left:5;">
				쿠폰 목록
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[TAGLIST]</td>
				<td class=td_con1 style="padding-left:5;">
				태그목록
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[TAGREGINPUT_입력폼 스타일]</td>
				<td class=td_con1 style="padding-left:5;">
				태그입력폼 <FONT class=font_blue>(예:[TAGREGINPUT_width:160px;height:22;])</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[TAGREGOK]</td>
				<td class=td_con1 style="padding-left:5;">
				태그달기 버튼 <FONT class=font_blue>(예:&lt;a href=[TAGREGOK]>태그달기&lt;/a>)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[COLLECTION]</td>
				<td class=td_con1 style="padding-left:5;">
				관련상품 (타이틀 미포함) : 상품관리=>관련상품관리에서 설정된 방식 사용
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[DELIINFO]</td>
				<td class=td_con1 style="padding-left:5;">
				배송/교환/환불정보
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[DETAIL]</td>
				<td class=td_con1 style="padding-left:5;">
				상품상세정보
				</td>
			</tr>

			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[PRODUCTINFOGOSI]</td>
				<td class=td_con1 style="padding-left:5;">
					상품정보고시 <FONT class=font_blue>(정보 테이블의 스타일 변경은 /css/common.css 파일에서 productInfoGosi 클래스의 설정 변경)</font>
				</td>
			</tr>


			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr><td colspan=2 height=5></td></tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15"><B>[REVIEW_STARTFORM]</B></td>
				<td class=td_con1 style="padding-left:5;">
				리뷰가 시작되는 위치에 꼭 입력해야함 (리뷰 폼이 위치함)
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[REVIEWALL]</td>
				<td class=td_con1 style="padding-left:5;">
				전체리뷰보기 버튼 <FONT class=font_blue>(예:&lt;a href=[REVIEWALL]>전체리뷰보기&lt;/a>)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[REVIEW_WRITE]</td>
				<td class=td_con1 style="padding-left:5;">
				리뷰 작성폼 보기 버튼 <FONT class=font_blue>(예:&lt;a href=[REVIEW_WRITE]>리뷰쓰기&lt;/a>)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[REVIEW_TOTAL]</td>
				<td class=td_con1 style="padding-left:5;">
				등록된 리뷰 총 갯수 <FONT class=font_blue>(예:[REVIEW_TOTAL]개의 리뷰가 등록되었습니다.)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<!--
			<tr>
				<td class=table_cell align=right style="padding-right:15">[REVIEW_AVERAGE??????_??????]</td>
				<td class=td_con1 style="padding-left:5;">
				리뷰 고객평가 평균 <FONT class=font_blue>예) 평균평점 : [REVIEW_AVERAGE] or [REVIEW_AVERAGEcacaca_000000]</font> <FONT class=font_orange>앞??????</font> : 별5개 기본 색상, <FONT class=font_orange>뒤??????</font> : 평균 별 색상 - "#"제외한 6자리 색상
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			-->
			<tr>
				<td class=table_cell align=right style="padding-right:15">[REVIEW_HIDE_START]</td>
				<td class=td_con1 style="padding-left:5;">
				리뷰 쓰기폼을 리뷰쓰기 버튼 클릭시에만 보이려면 사용하세요.
				<br>마지막 부분에 [REVIEW_HIDE_END] 꼭 입력해야합니다.
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>

			<tr>
				<td class=table_cell align=right style="padding-right:15">[REVIEW_WRITE_FORM]</td>
				<td class=td_con1 style="padding-left:5;">
					리뷰 작성 입력폼 (/front/prreview.php 에서 수정가능합니다.)
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			
			<!--
			<tr>
				<td class=table_cell align=right style="padding-right:15">[REVIEW_SHOW_START]</td>
				<td class=td_con1 style="padding-left:5;">
				리뷰 쓰기폼을 항상 보이려면 사용하세요. 마지막 부분에 [REVIEW_SHOW_END] 꼭 입력해야합니다.
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[REVIEW_NAME_입력폼 스타일]</td>
				<td class=td_con1 style="padding-left:5;">
				이름 입력폼
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[REVIEW_AREA_쓰기폼 스타일]</td>
				<td class=td_con1 style="padding-left:5;">
				리뷰 쓰기폼 <FONT class=font_blue>(예:[REVIEW_AREA_width:95%;height:40;])</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[REVIEW_MARKS??????]</td>
				<td class=td_con1 style="padding-left:5;">
				평균평점
				<br><img width=10 height=0>
				<FONT class=font_orange>?????? : 별 색상 ("#"제외)</FONT>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[REVIEW_MARK1]</td>
				<td class=td_con1 style="padding-left:5;">
				평점 (별 1개 선택박스) <FONT class=font_blue>예) [REVIEW_MARK1]★</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[REVIEW_MARK2]</td>
				<td class=td_con1 style="padding-left:5;">
				평점 (별 2개 선택박스) <FONT class=font_blue>예) [REVIEW_MARK2]★★</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[REVIEW_MARK3]</td>
				<td class=td_con1 style="padding-left:5;">
				평점 (별 3개 선택박스) <FONT class=font_blue>예) [REVIEW_MARK3]★★★</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[REVIEW_MARK4]</td>
				<td class=td_con1 style="padding-left:5;">
				평점 (별 4개 선택박스) <FONT class=font_blue>예) [REVIEW_MARK4]★★★★</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[REVIEW_MARK5]</td>
				<td class=td_con1 style="padding-left:5;">
				평점 (별 5개 선택박스) <FONT class=font_blue>예) [REVIEW_MARK5]★★★★★</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			-->
			<tr>
				<td class=table_cell align=right style="padding-right:15">[BTN_REVIEW_WRITE]</td>
				<td class=td_con1 style="padding-left:5;">
					리뷰쓰기 버튼 <FONT class=font_blue>(예:&lt;a href=[BTN_REVIEW_WRITE]>리뷰쓰기&lt;/a>)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[REVIEW_HIDE_END]</td>
				<td class=td_con1 style="padding-left:5;">
				리뷰 쓰기폼을 리뷰쓰기 버튼 클릭시에만 보이게 하는 [REVIEW_HIDE_START] 사용시에 마지막 선언
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<!--
			<tr>
				<td class=table_cell align=right style="padding-right:15">[REVIEW_SHOW_END]</td>
				<td class=td_con1 style="padding-left:5;">
				리뷰 쓰기폼을 항상 보이게 하는 [REVIEW_SHOW_START] 사용시에 마지막 선언
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			-->
			<tr>
				<td class=table_cell align=right style="padding-right:15"><B>[REVIEW_ENDFORM]</B></td>
				<td class=td_con1 style="padding-left:5;">
				리뷰가 끝나는 위치에 꼭 입력햐야함
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[REVIEW_LIST]</td>
				<td class=td_con1 style="padding-left:5;">
				리뷰 리스트
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr><td colspan=2 height=5></td></tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[QNA_ALL]</td>
				<td class=td_con1 style="padding-left:5;">
				상품Q&A 게시판 가기 <FONT class=font_blue>(예:&lt;a href=[QNA_ALL]>상품Q&A게시판가기&lt;/a>)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[QNA_WRITE]</td>
				<td class=td_con1 style="padding-left:5;">
				상품Q&A 게시판 쓰기버튼 <FONT class=font_blue>(예:&lt;a href=[QNA_WRITE]>상품Q&A게시판 글쓰기&lt;/a>)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[QNA_LIST]</td>
				<td class=td_con1 style="padding-left:5;">
				상품Q&A 리스트
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[QNA_TOTAL]</td>
				<td class=td_con1 style="padding-left:5;">
				상품Q&A 총 갯수
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[SNSCOMMENT]</td>
				<td class=td_con1 style="padding-left:5;">
				SNS홍보
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[GONGGUCOMMENT]</td>
				<td class=td_con1 style="padding-left:5;">
				공동구매신청
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
			<td><p class="LIPoint">나모,드림위버등의 에디터로 작성시 이미지경로등 작업내용이 틀려질 수 있으니 주의하세요!</p></td>
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
<tr>
	<td height="50"></td>
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
	f.mode.value = 'prdetail';
	f.code.value = document.form1.body.value;
	f.submit();
}
</script>
<script type="text/javascript">
<!--
	parent.autoResize('MainPrdtFrame');
//-->
</script>

<form name="prevForm" method="post" action="design_prev_post.php" target="_blank">
	<input type="hidden" name="code">
	<input type="hidden" name="mode">
</form>

<?=$onload?>

</body>
</html>