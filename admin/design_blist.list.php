<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### 페이지 접근권한 check ###############
$PageCode = "de-2";
$MenuCode = "nomenu";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$templet_list=array(0=>"L001");

$mode=$_POST["mode"];
$code=$_POST["code"];
$design=$_POST["design"];

if((int)$code>0) {
	$sql = "SELECT * FROM tblproductbrand WHERE bridx='".$code."' ";
	$result=mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
	mysql_free_result($result);
	if(!$row) {
		$onload="<script>alert(\"브랜드 선택이 잘못 되었습니다.\");</script>";
	} else {
		if(strlen($row->list_type)==5) {
			$design_default = "LU";
		} else {
			$design_default = $row->list_type;
		}
	}
} else {
	$child_all="Y";
}

if(strlen($onload)==0 && $mode == "update") {
	if($child_all=="Y") {
		$qry = "";
		if(ereg("^[ㄱ-ㅎ]", $code)) {
			if($code == "ㄱ") $qry.= "WHERE (brandname >= 'ㄱ' AND brandname < 'ㄴ') OR (brandname >= '가' AND brandname < '나') ";
			if($code == "ㄴ") $qry.= "WHERE (brandname >= 'ㄴ' AND brandname < 'ㄷ') OR (brandname >= '나' AND brandname < '다') ";
			if($code == "ㄷ") $qry.= "WHERE (brandname >= 'ㄷ' AND brandname < 'ㄹ') OR (brandname >= '다' AND brandname < '라') ";
			if($code == "ㄹ") $qry.= "WHERE (brandname >= 'ㄹ' AND brandname < 'ㅁ') OR (brandname >= '라' AND brandname < '마') ";
			if($code == "ㅁ") $qry.= "WHERE (brandname >= 'ㅁ' AND brandname < 'ㅂ') OR (brandname >= '마' AND brandname < '바') ";
			if($code == "ㅂ") $qry.= "WHERE (brandname >= 'ㅂ' AND brandname < 'ㅅ') OR (brandname >= '바' AND brandname < '사') ";
			if($code == "ㅅ") $qry.= "WHERE (brandname >= 'ㅅ' AND brandname < 'ㅇ') OR (brandname >= '사' AND brandname < '아') ";
			if($code == "ㅇ") $qry.= "WHERE (brandname >= 'ㅇ' AND brandname < 'ㅈ') OR (brandname >= '아' AND brandname < '자') ";
			if($code == "ㅈ") $qry.= "WHERE (brandname >= 'ㅈ' AND brandname < 'ㅊ') OR (brandname >= '자' AND brandname < '차') ";
			if($code == "ㅊ") $qry.= "WHERE (brandname >= 'ㅊ' AND brandname < 'ㅋ') OR (brandname >= '차' AND brandname < '카') ";
			if($code == "ㅋ") $qry.= "WHERE (brandname >= 'ㅋ' AND brandname < 'ㅌ') OR (brandname >= '카' AND brandname < '타') ";
			if($code == "ㅌ") $qry.= "WHERE (brandname >= 'ㅌ' AND brandname < 'ㅍ') OR (brandname >= '타' AND brandname < '파') ";
			if($code == "ㅍ") $qry.= "WHERE (brandname >= 'ㅍ' AND brandname < 'ㅎ') OR (brandname >= '파' AND brandname < '하') ";
			if($code == "ㅎ") $qry.= "WHERE (brandname >= 'ㅎ' AND brandname < 'ㅏ') OR (brandname >= '하' AND brandname < '??') ";
		} else if($code == "기타") {
			$qry.= "WHERE (brandname < 'ㄱ' OR brandname >= 'ㅏ') AND (brandname < '가' OR brandname >= '??') AND (brandname < 'a' OR brandname >= '{') AND (brandname < 'A' OR brandname >= '[') ";
		} else if(ereg("^[A-Z]", $code)) {
			$qry.= "WHERE brandname LIKE '".$code."%' OR brandname LIKE '".strtolower($code)."%' ";
		} else if($code == "전체") {
			$qry.= "WHERE 1=1 ";
		}
		$design_default = "";
	} else {
		$qry.= "WHERE bridx='".$code."' ";
		$design_default = $design;
	}

	if(strlen($qry)==0) {
		$onload="<script>alert(\"브랜드 선택이 올바르지 않습니다.\");</script>";
	} else {
		$sql = "UPDATE tblproductbrand SET list_type = '".$design."' ";
		$sql.= $qry;
		$update = mysql_query($sql,get_db_conn());
		$onload="<script>alert(\"브랜드 화면 템플릿 변경이 완료되었습니다.\");</script>";
	}
}
?>

<? INCLUDE "header.php"; ?>
<style>td {line-height:18pt;}</style>
<script type="text/javascript" src="lib.js.php"></script>
<script>var LH = new LH_create();</script>
<script for=window event=onload>LH.exec();</script>
<script>LH.add("parent_resizeIframe('MainPrdtFrame')");</script>
<SCRIPT LANGUAGE="JavaScript">
<!--
function CheckForm() {
	ischk=false;
	if(typeof(document.form1.design.length)!="undefined") {
		for(i=0;i<document.form1.design.length;i++) {
			if(document.form1.design[i].checked==true) {
				ischk=true;
				break;
			}
		}
	} else {
		if(document.form1.design.checked==true) {
			ischk=true;
		}
	}
	if(!ischk) {
		alert("디자인 템플릿을 선택하세요.");
		return;
	}
	if(confirm("브랜드별 화면 템플릿을 변경하시겠습니까?")) {
		document.form1.mode.value="update";
		document.form1.submit();
	}
}

function ChangeDesign(tmp) {
	if(typeof(document.form1["design"][tmp])=="object") {
		document.form1["design"][tmp].checked=true;
		parent.design_preview(document.form1["design"][tmp].value);
	} else {
		document.form1["design"].checked=true;
		parent.design_preview(document.form1["design"].value);
	}
}

function changeMouseOver(img) {
	 img.style.border='1 dotted #999999';
}
function changeMouseOut(img,dot) {
	 img.style.border="1 "+dot;
}

<?
echo "parent.document.all[\"preview_img\"].style.display=\"none\";";
if(strlen($design_default)>0) {
	echo "parent.document.all[\"preview_img\"].src=\"images/sample/brand".$design_default.".gif\";\n";
	echo "parent.document.all[\"preview_img\"].style.display=\"\";";
}
?>
//-->
</SCRIPT>

<table cellpadding="0" cellspacing="0" width="100%">
<form name=form1 method=post action="<?=$_SERVER[PHP_SELF]?>">
<input type=hidden name=mode>
<input type=hidden name=code value="<?=$code?>">
<tr>
	<td>
	<table cellpadding="0" cellspacing="0" width="764">
	<tr>
		<td width="100%" bgcolor="#ededed" style="padding:4pt;">
		<table cellpadding="0" cellspacing="0" width="100%" bgcolor="white">
		<tr>
			<td width="100%">
			<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
			<TR>
				<TD align=center width="100%" height="30" background="images/blueline_bg.gif"><b><font color="#555555">템플릿 적용하기</font></b></TD>
			</TR>
			<TR>
				<TD width="100%" background="images/table_con_line.gif"></TD>
			</TR>
			<TR>
				<TD width="100%" style="padding:10pt;">
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td align="center">
					<table cellpadding="0" cellspacing="0" border="0">
<?
		for($i=0;$i<count($templet_list);$i++) {
			if($i==0) echo "<tr>\n";
			if($i>0 && $i%3==0) echo "</tr>\n<tr>\n";
			if($i%3==0) {
				echo "<td width=\"246\" align=center>";
			} else {
				echo "<td width=\"246\" align=center>";
			}
			echo "<img src=\"images/sample/brand".$templet_list[$i].".gif\" width=\"150\" height=\"160\" border=\"0\" class=\"imgline1\" onMouseOver='changeMouseOver(this);' onMouseOut=\"changeMouseOut(this,'dotted #FFFFFF');\" style='cursor:hand;' onclick='ChangeDesign(".$i.");'>";
			echo "<br><input type=radio id=\"idx_design".$i."\" name=design value=\"".$templet_list[$i]."\" ";
			if($design_default==$templet_list[$i]) echo "checked";
			echo " onclick=\"parent.design_preview('".$templet_list[$i]."')\">";
			echo "</td>\n";
		}
		if($i%3!=0) {
			//echo "<td width=\"246\" align=center>&nbsp;</td></tr>\n";
		}
?>
					</table>
					</td>
				</tr>
				<!--
				<tr>
					<td width="100%" height="25"><hr size="1" noshade color="#EBEBEB"></td>
				</tr>
				-->
				</table>
				</TD>
			</TR>
			</TABLE>
			</td>
		</tr>
		</table>
		</td>
	</tr>
	</table>
	</TD>
</tr>
<tr>
	<td height=20></td>
</tr>
<tr>
	<td align="center" width="764"><a href="javascript:CheckForm();"><img src="images/botteon_save.gif" border="0"></a></td>
</tr>
<tr>
	<td height=10></td>
</tr>
</form>
</table>
<?=$onload?>

</body>
</html>