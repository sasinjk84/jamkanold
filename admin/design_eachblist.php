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

if(strlen($seachIdx)==0) {
	$seachIdx = "전체";
}
?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function SearchSubmit(seachIdxval) {
	form = document.form1;
	form.mode.value="";
	form.seachIdx.value = seachIdxval;
	form.submit();
}

function design_preview(design) {
	document.all["preview_img"].src="images/sample/brand"+design+".gif";
}

function CodeProcessFun(brandselectedIndex,brandcode) {
	if(brandselectedIndex>-1) {
		document.form2.mode.value="";
		document.form2.code.value=brandcode;
		document.form2.target="MainPrdtFrame";
		document.form2.action="design_eachblist.list.php";
		document.form2.submit();
	}
}
</script>
<STYLE type=text/css>
	#menuBar {}
	#contentDiv {WIDTH: 300;HEIGHT: 250;}
</STYLE>

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
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">현재위치 : 디자인관리 &gt; 개별디자인-페이지 본문 &gt; <span class="2depth_select">상품브랜드 화면 꾸미기</span></td>
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









			<table cellpadding="0" cellspacing="0" width="100%">
			<tr><td height="8"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/design_productbrand_title.gif" border="0"></TD>
					</tr>
<tr>
<TD width="100%" background="images/title_bg.gif" height="21"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height="3"></td>
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
					<TD width="100%" class="notice_blue">상품 브랜드별 화면 디자인을 자유롭게 디자인 하실 수 있습니다.</TD>
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
					<TD><IMG SRC="images/design_productbrand_stitle.gif" border="0"></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=mode>
			<input type=hidden name=seachIdx value="">
			<tr>
				<td style="padding-top:3pt;">
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<col width="400"></col>
				<col width="30"></col>
				<col></col>
				<TR>
					<TD background="images/table_top_line.gif" colspan="3"></TD>
				</TR>
				<TR>
					<TD class="table_cell" align="center"><b>전체 브랜드</b></td>
					<TD class="table_cell1" align="center">&nbsp;</TD>
					<TD class="table_cell1" align="center" background="images/blueline_bg.gif"><span class="font_blue">현재 상품 브랜드별 템플릿</span></TD>
				</TR>
				<TR>
					<TD colspan="3" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD valign="top" style="padding:3pt;">
					<table border=0 cellpadding=0 cellspacing=0 width="100%">
					<tr>
						<td style="padding:5px;padding-left:2px;padding-right:2px;">
						<table border=0 cellpadding=0 cellspacing=0 width="100%">
						<tr align="center">
							<td><b><a href="javascript:SearchSubmit('A');"><span id="A">A</span></a></b></td>
							<td><b><a href="javascript:SearchSubmit('B');"><span id="B">B</span></a></b></td>
							<td><b><a href="javascript:SearchSubmit('C');"><span id="C">C</span></a></b></td>
							<td><b><a href="javascript:SearchSubmit('D');"><span id="D">D</span></a></b></td>
							<td><b><a href="javascript:SearchSubmit('E');"><span id="E">E</span></a></b></td>
							<td><b><a href="javascript:SearchSubmit('F');"><span id="F">F</span></a></b></td>
							<td><b><a href="javascript:SearchSubmit('G');"><span id="G">G</span></a></b></td>
							<td><b><a href="javascript:SearchSubmit('H');"><span id="H">H</span></a></b></td>
							<td><b><a href="javascript:SearchSubmit('I');"><span id="I">I</span></a></b></td>
							<td><b><a href="javascript:SearchSubmit('J');"><span id="J">J</span></a></b></td>
							<td><b><a href="javascript:SearchSubmit('K');"><span id="K">K</span></a></b></td>
							<td><b><a href="javascript:SearchSubmit('L');"><span id="L">L</span></a></b></td>
							<td><b><a href="javascript:SearchSubmit('M');"><span id="M">M</span></a></b></td>
							<td><b><a href="javascript:SearchSubmit('N');"><span id="N">N</span></a></b></td>
							<td><b><a href="javascript:SearchSubmit('O');"><span id="O">O</span></a></b></td>
							<td><b><a href="javascript:SearchSubmit('P');"><span id="P">P</span></a></b></td>
							<td><b><a href="javascript:SearchSubmit('Q');"><span id="Q">Q</span></a></b></td>
							<td><b><a href="javascript:SearchSubmit('R');"><span id="R">R</span></a></b></td>
							<td><b><a href="javascript:SearchSubmit('S');"><span id="S">S</span></a></b></td>
							<td><b><a href="javascript:SearchSubmit('T');"><span id="T">T</span></a></b></td>
							<td><b><a href="javascript:SearchSubmit('U');"><span id="U">U</span></a></b></td>
							<td><b><a href="javascript:SearchSubmit('V');"><span id="V">V</span></a></b></td>
							<td><b><a href="javascript:SearchSubmit('W');"><span id="W">W</span></a></b></td>
							<td><b><a href="javascript:SearchSubmit('X');"><span id="X">X</span></a></b></td>
							<td><b><a href="javascript:SearchSubmit('Y');"><span id="Y">Y</span></a></b></td>
							<td><b><a href="javascript:SearchSubmit('Z');"><span id="Z">Z</span></a></b></td>
						</TR>
						</table>
						</td>
						<td width="40" align="center" nowrap><b><a href="javascript:SearchSubmit('전체');"><span id="전체">전체</span></a></b></td>
					</tr>
					<tr>
						<!-- 상품브랜드 목록 -->
						<td width="100%"><select name="up_brandlist" size="16" style="width:100%;" onchange="CodeProcessFun(this.selectedIndex,this.value);">
					<?
						$sql = "SELECT * FROM tblproductbrand ";
						if(ereg("^[ㄱ-ㅎ]", $seachIdx)) {
							if($seachIdx == "ㄱ") $sql.= "WHERE (brandname >= 'ㄱ' AND brandname < 'ㄴ') OR (brandname >= '가' AND brandname < '나') ";
							if($seachIdx == "ㄴ") $sql.= "WHERE (brandname >= 'ㄴ' AND brandname < 'ㄷ') OR (brandname >= '나' AND brandname < '다') ";
							if($seachIdx == "ㄷ") $sql.= "WHERE (brandname >= 'ㄷ' AND brandname < 'ㄹ') OR (brandname >= '다' AND brandname < '라') ";
							if($seachIdx == "ㄹ") $sql.= "WHERE (brandname >= 'ㄹ' AND brandname < 'ㅁ') OR (brandname >= '라' AND brandname < '마') ";
							if($seachIdx == "ㅁ") $sql.= "WHERE (brandname >= 'ㅁ' AND brandname < 'ㅂ') OR (brandname >= '마' AND brandname < '바') ";
							if($seachIdx == "ㅂ") $sql.= "WHERE (brandname >= 'ㅂ' AND brandname < 'ㅅ') OR (brandname >= '바' AND brandname < '사') ";
							if($seachIdx == "ㅅ") $sql.= "WHERE (brandname >= 'ㅅ' AND brandname < 'ㅇ') OR (brandname >= '사' AND brandname < '아') ";
							if($seachIdx == "ㅇ") $sql.= "WHERE (brandname >= 'ㅇ' AND brandname < 'ㅈ') OR (brandname >= '아' AND brandname < '자') ";
							if($seachIdx == "ㅈ") $sql.= "WHERE (brandname >= 'ㅈ' AND brandname < 'ㅊ') OR (brandname >= '자' AND brandname < '차') ";
							if($seachIdx == "ㅊ") $sql.= "WHERE (brandname >= 'ㅊ' AND brandname < 'ㅋ') OR (brandname >= '차' AND brandname < '카') ";
							if($seachIdx == "ㅋ") $sql.= "WHERE (brandname >= 'ㅋ' AND brandname < 'ㅌ') OR (brandname >= '카' AND brandname < '타') ";
							if($seachIdx == "ㅌ") $sql.= "WHERE (brandname >= 'ㅌ' AND brandname < 'ㅍ') OR (brandname >= '타' AND brandname < '파') ";
							if($seachIdx == "ㅍ") $sql.= "WHERE (brandname >= 'ㅍ' AND brandname < 'ㅎ') OR (brandname >= '파' AND brandname < '하') ";
							if($seachIdx == "ㅎ") $sql.= "WHERE (brandname >= 'ㅎ' AND brandname < 'ㅏ') OR (brandname >= '하' AND brandname < '��') ";
							$sql.= "ORDER BY brandname ";
						} else if($seachIdx == "기타") {
							$sql.= "WHERE (brandname < 'ㄱ' OR brandname >= 'ㅏ') AND (brandname < '가' OR brandname >= '��') AND (brandname < 'a' OR brandname >= '{') AND (brandname < 'A' OR brandname >= '[') ";
							$sql.= "ORDER BY brandname ";
						} else if(ereg("^[A-Z]", $seachIdx)) {
							$sql.= "WHERE brandname LIKE '".$seachIdx."%' OR brandname LIKE '".strtolower($seachIdx)."%' ";	
							$sql.= "ORDER BY brandname ";
						} else {
							$sql.= "ORDER BY brandname ";
						}
						$result=mysql_query($sql,get_db_conn());
						while($row=mysql_fetch_object($result)) {
							$brandopt .= "<option value=\"".$row->bridx."\">".$row->brandname."</option>\n";
						}

						if(strlen($brandopt)>0 && $seachIdx == "전체") {
							$brandopt = "<option value=\"".$seachIdx."\">------------ ".$seachIdx." 브랜드 일괄 개별디자인 ------------</option>\n".$brandopt;
						}
						echo $brandopt;
					?>
						</select></td>
						<td width="40" align="center" nowrap valign="top">
						<table border=0 cellpadding=0 cellspacing=0 width="100%">
						<tr><td align="center"><b><a href="javascript:SearchSubmit('ㄱ');"><span id="ㄱ">ㄱ</span></a></b></td></tr>
						<tr><td align="center"><b><a href="javascript:SearchSubmit('ㄴ');"><span id="ㄴ">ㄴ</span></a></b></td></tr>
						<tr><td align="center"><b><a href="javascript:SearchSubmit('ㄷ');"><span id="ㄷ">ㄷ</span></a></b></td></tr>
						<tr><td align="center"><b><a href="javascript:SearchSubmit('ㄹ');"><span id="ㄹ">ㄹ</span></a></b></td></tr>
						<tr><td align="center"><b><a href="javascript:SearchSubmit('ㅁ');"><span id="ㅁ">ㅁ</span></a></b></td></tr>
						<tr><td align="center"><b><a href="javascript:SearchSubmit('ㅂ');"><span id="ㅂ">ㅂ</span></a></b></td></tr>
						<tr><td align="center"><b><a href="javascript:SearchSubmit('ㅅ');"><span id="ㅅ">ㅅ</span></a></b></td></tr>
						<tr><td align="center"><b><a href="javascript:SearchSubmit('ㅇ');"><span id="ㅇ">ㅇ</span></a></b></td></tr>
						<tr><td align="center"><b><a href="javascript:SearchSubmit('ㅈ');"><span id="ㅈ">ㅈ</span></a></b></td></tr>
						<tr><td align="center"><b><a href="javascript:SearchSubmit('ㅊ');"><span id="ㅊ">ㅊ</span></a></b></td></tr>
						<tr><td align="center"><b><a href="javascript:SearchSubmit('ㅋ');"><span id="ㅋ">ㅋ</span></a></b></td></tr>
						<tr><td align="center"><b><a href="javascript:SearchSubmit('ㅌ');"><span id="ㅌ">ㅌ</span></a></b></td></tr>
						<tr><td align="center"><b><a href="javascript:SearchSubmit('ㅍ');"><span id="ㅍ">ㅍ</span></a></b></td></tr>
						<tr><td align="center"><b><a href="javascript:SearchSubmit('ㅎ');"><span id="ㅎ">ㅎ</span></a></b></td></tr>
						<tr><td align="center"><b><a href="javascript:SearchSubmit('기타');"><span id="기타">기타</span></a></b></td></tr>
						</table>
						</td>
					</tr>
					</table>
					</TD>
					<TD class="td_con1" align="center"><img src="images/btn_next1.gif" border="0" hspace="5"></TD>
					<TD class="td_con1" align="center" style="padding:5pt;">&nbsp;<img id="preview_img" width="200" height="214" style="display:none" border="0" vspace="0" class="imgline"><br><p align="left"><b>&quot;모든 브랜드 일괄 개별디자인&quot; </b>을 적용할 경우 개별 디자인 사용중인 브랜드를 제외한 템플릿을 사용하는 모든 브랜드가 개별디자인으로 일괄 변경됩니다.</TD>
				</TR>
				<TR>
					<TD background="images/table_top_line.gif" colspan="3"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height="20"></td>
			</tr>
			<tr>
				<td><IFRAME name="MainPrdtFrame" src="design_eachblist.list.php" width=100% height=350 frameborder=0 align=TOP scrolling="no" marginheight="0" marginwidth="0"></IFRAME></td>
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
					<TD COLSPAN=3 width="100%" valign="top" class=menual_bg style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;"  class="menual_bg">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><p class="LIPoint"><B><span class="font_orange">상품브랜드 화면 매크로명령어</span></B>(해당 매크로명령어는 다른 페이지 디자인 작업시 사용이 불가능함)</p></td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><p>&nbsp;</p></td>
						<td >
						<table border=0 cellpadding=0 cellspacing=0 width=100%>
						<col width=150></col>
						<col width=></col>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BRANDNAME]</td>
							<td class=td_con1 style="padding-left:5;">
							현재 브랜드/카테고리명
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BRANDNAVI??????_??????]</td>
							<td class=td_con1 style="padding-left:5;">
							브랜드 네비게이션 
									<br><img width=10 height=0>
									<FONT class=font_orange>앞?????? : 홈 또는 현재 브랜드 색상</FONT> - <FONT COLOR="red">"#"제외</FONT>
									<br><img width=10 height=0>
									<FONT class=font_orange>뒤?????? : 현재 브랜드 또는 현재 브랜드가 속한 카테고리 색상</FONT> - <FONT COLOR="red">"#"제외</FONT>
									<br>
									<FONT class=font_blue>예) [BRANDNAVI] or [BRANDNAVI000000_FF0000]</FONT>
							</td>
						</tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[CLIPCOPY]</td>
							<td class=td_con1 style="padding-left:5;">
							현재주소 복사 버튼 <FONT class=font_blue>(예:&lt;a href=[CLIPCOPY]>주소복사&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BRANDEVENT]</td>
							<td class=td_con1 style="padding-left:5;">
							브랜드별 이벤트 이미지/html
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[BRANDGROUP]</td>
							<td class=td_con1 style="padding-left:5;">
							상품 브랜드 카테고리 그룹
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right bgcolor=#E9A74E style="padding-right:15">상품 브랜드 카테고리 그룹 관련 스타일 정의</td>
							<td class=td_con1 bgcolor=#FEEEE2 style="padding-left:5;">
										<img width=10 height=0>
										<FONT class=font_orange>#group1_td - 상위카테고리 TD 스타일 정의 (사이즈 및 백그라운드컬러)</FONT>
										<br><img width=100 height=0>
										<FONT class=font_blue>예) #group1_td { background-color:#E6E6E6;width:25%; }</FONT>
										<br><img width=0 height=7><br><img width=10 height=0>
										<FONT class=font_orange>#group2_td - 하위카테고리 TD 스타일 정의 (사이즈 및 백그라운드컬러)</FONT>
										<br><img width=100 height=0>
										<FONT class=font_blue>예) #group2_td { background-color:#EFEFEF; }</FONT>
										<br><img width=0 height=7><br><img width=10 height=0>
										<FONT class=font_orange>#group_line - 상위그룹과 상위그룹 사이의 가로라인 셀 스타일 정의</FONT>
										<br><img width=100 height=0>
										<FONT class=font_blue>예) #group_line { background-color:#FFFFFF;height:1px; }</FONT>
				<pre style="line-height:15px">
<B>[사용 예]</B> - 내용 본문에 아래와 같이 정의하시면 됩니다.

<FONT class=font_blue>&lt;style>
  #group1_td { background-color:#E6E6E6;width:25%; }
  #group2_td { background-color:#EFEFEF; }
  #group_line { background-color:#FFFFFF;height:1px; }
&lt;/style></FONT></pre>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[TOTAL]</td>
							<td class=td_con1 style="padding-left:5;">
							총 상품수 <FONT class=font_blue>(예:총 [TOTAL]건)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[SORTPRODUCTUP]</td>
							<td class=td_con1 style="padding-left:5;">
							제조사 ㄱㄴㄷ순 정렬  <FONT class=font_blue>(예:&lt;a href=[SORTPRODUCTUP]>제조사순▲&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[SORTPRODUCTDN]</td>
							<td class=td_con1 style="padding-left:5;">
							제조사 ㄷㄴㄱ순 정렬 <FONT class=font_blue>(예:&lt;a href=[SORTPRODUCTDN]>제조사순▼&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[SORTNAMEUP]</td>
							<td class=td_con1 style="padding-left:5;">
							상품명 ㄱㄴㄷ순 정렬 <FONT class=font_blue>(예:&lt;a href=[SORTNAMEUP]>상품명순▲&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[SORTNAMEDN]</td>
							<td class=td_con1 style="padding-left:5;">
							상품명 ㄷㄴㄱ순 정렬 <FONT class=font_blue>(예:&lt;a href=[SORTNAMEDN]>상품명순▼&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[SORTPRICEUP]</td>
							<td class=td_con1 style="padding-left:5;">
							낮은 상품가격순 <FONT class=font_blue>(예:&lt;a href=[SORTPRICEUP]>가격순▲&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[SORTPRICEDN]</td>
							<td class=td_con1 style="padding-left:5;">
							높은 상품가격순 <FONT class=font_blue>(예:&lt;a href=[SORTPRICEDN]>가격순▼&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[SORTRESERVEUP]</td>
							<td class=td_con1 style="padding-left:5;">
							낮은 적립금순 <FONT class=font_blue>(예:&lt;a href=[SORTRESERVEUP]>적립금순▲&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[SORTRESERVEDN]</td>
							<td class=td_con1 style="padding-left:5;">
							높은 적립금순 <FONT class=font_blue>(예:&lt;a href=[SORTRESERVEDN]>적립금순▼&lt;/a>)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>

						<tr>
							<td class=table_cell align=right style="padding-right:15">[ONNEW]</td>
							<td class=td_con1 style="padding-left:5;">
								신규등록 상품순 선택 표시 <FONT class=font_blue>(class="sortOn", /lib/style.php 파일에서 css 정의)</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ONBEST]</td>
							<td class=td_con1 style="padding-left:5;">
								인기상품순 선택 표시 <FONT class=font_blue>(class="sortOn", /lib/style.php 파일에서 css 정의)</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ONPRICEUP]</td>
							<td class=td_con1 style="padding-left:5;">
								낮은 가격순 선택 표시 <FONT class=font_blue>(class="sortOn", /lib/style.php 파일에서 css 정의)</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ONPRICEDN]</td>
							<td class=td_con1 style="padding-left:5;">
								높은 가격순 선택 표시 <FONT class=font_blue>(class="sortOn", /lib/style.php 파일에서 css 정의)</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[ONRESERVEDN]</td>
							<td class=td_con1 style="padding-left:5;">
								적립금순 선택 표시 <FONT class=font_blue>(class="sortOn", /lib/style.php 파일에서 css 정의)</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[LISTSELECT]</td>
							<td class=td_con1 style="padding-left:5;">
								상품출력갯수 선택
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
						<tr>
							<td class=table_cell align=right style="padding-right:15">[PRLIST1??]</td>
							<td class=td_con1 style="padding-left:5;">
							상품목록 - 이미지A형
										<br><img width=10 height=0>
										<FONT class=font_orange>? : 라인별 상품갯수(1~8)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : 몇라인으로 진열을 할건지 숫자입력(1-8)</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[PRLIST2??]</td>
							<td class=td_con1 style="padding-left:5;">
							상품목록 - 이미지B형
										<br><img width=10 height=0>
										<FONT class=font_orange>? : 라인별 상품갯수(1~8)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : 몇라인으로 진열을 할건지 숫자입력(1-8)</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[PRLIST????????_??]</td>
							<td class=td_con1 style="padding-left:5;">
							상품목록 - 이미지A형/이미지B형
										<br><img width=10 height=0>
										<FONT class=font_orange>? : 위에 제공된 상품목록 형태 (1:이미지A형, 2:이미지B형)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : 라인별 상품갯수(1~8)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : 몇라인으로 진열을 할건지 숫자입력(1-8)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : 상품 사이의 세로라인 표시여부(Y/N/L)</FONT> (L은 상품에 맞추어 길게 표시됨)
										<br><img width=10 height=0>
										<FONT class=font_orange>? : 상품 사이의 가로라인 표시여부(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : 상품 시중가격 표시여부(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : 상품 적립금 표시여부(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : 상품 태그 표시갯수(0-9) : 0일 경우 표시안함</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>_?? : 상품사이(위아래) 간격 최대 99픽셀 (미입력시 5픽셀)</FONT>
										<br>
										<FONT class=font_blue>예) [PRLIST142NNYN2_10], [PRLIST222LYYY2_5]</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[PRLIST3??]</td>
							<td class=td_con1 style="padding-left:5;">
							상품목록 - 리스트형
										<br><img width=10 height=0>
										<FONT class=font_orange>?? : 상품목록 진열갯수 (01~20)</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[PRLIST3???????]</td>
							<td class=td_con1 style="padding-left:5;">
							상품목록 - 리스트형
										<br><img width=10 height=0>
										<FONT class=font_orange>?? : 상품 진열갯수 (01~20)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : 상품 이미지 표시여부 (Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : 상품 제조사 표시여부 (Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : 상품 시중가격 표시여부(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : 상품 적립금 표시여부(Y/N)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : 상품 태그 표시갯수(0-9) : 0일 경우 표시안함</FONT>
										<br>
										<FONT class=font_blue>예) [PRLIST304YYYY4]</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[PRLIST4??_??]</td>
							<td class=td_con1 style="padding-left:5;">
							상품목록 - 공동구매형
										<br><img width=10 height=0>
										<FONT class=font_orange>? : 라인별 상품갯수(2~4)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>? : 몇라인으로 진열을 할건지 숫자입력(1~8)</FONT>
										<br><img width=10 height=0>
										<FONT class=font_orange>_?? : 상품사이(위아래) 간격 최대 99픽셀 (미입력시 5픽셀)</FONT>
										<br>
										<FONT class=font_blue>예) [PRLIST423_5]</FONT>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right bgcolor=#E9A74E style="padding-right:15">상품목록 스타일 정의</td>
							<td class=td_con1 bgcolor=#FEEEE2 style="padding-left:5;">
										<img width=15 height=0><FONT class=font_orange>#prlist_colline - 이미지/리스트형의 가로라인 셀 스타일 정의</FONT>
										<br><img width=100 height=0>
										<FONT class=font_blue>예) #prlist_colline { background-color:#f4f4f4;height:1px; }</FONT>
										<br><img width=0 height=7><br><img width=10 height=0>
										<FONT class=font_orange>#prlist_colline - 이미지/리스트형의 가로라인 셀 스타일 정의</FONT>
										<br><img width=100 height=0>
										<FONT class=font_blue>예) #prlist_rowline { background-color:#f4f4f4;width:1px; }</FONT>
							<pre style="line-height:15px">
<B>[사용 예]</B> - 내용 본문에 아래와 같이 정의하시면 됩니다.
<FONT class=font_blue>&lt;style>
  #prlist_colline { background-color:#f4f4f4;height:1px; }
  #prlist_rowline { background-color:#f4f4f4;width:1px; }
&lt;/style></FONT></pre>
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
<form name=form2 action="" method=post>
<input type=hidden name=mode>
<input type=hidden name=code>
</form>
</table>
<script language="javascript">
<!--
<?
	if(strlen($seachIdx)>0) {
		echo "document.getElementById(\"$seachIdx\").style.color=\"#FF4C00\";";
	} else {
		echo "document.getElementById(\"TTL\").style.color=\"#FF4C00\";";
	}
?>
//-->
</script>
<?=$onload?>

<? INCLUDE "copyright.php"; ?>