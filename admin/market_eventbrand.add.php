<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### 페이지 접근권한 check ###############
$PageCode = "ma-2";
$MenuCode = "market";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$imagepath=$Dir.DataDir."shopimages/etc/";

$mode=$_POST["mode"];
$code=$_POST["code"];
$title_type=$_POST["title_type"];
$title_body=$_POST["title_body"];

if(strlen($code)==0) {
	$code=""; $title_type=""; $title_body=""; $child_all="";
	$disabled="disabled";
} else {
	if((int)$code>0) {
		$sql = "SELECT * FROM tblproductbrand WHERE bridx='".$code."' ";
		$result=mysql_query($sql,get_db_conn());
		$row=mysql_fetch_object($result);
		mysql_free_result($result);
		if($row) {
			if(strlen($mode)==0) {
				$title_type=$row->title_type;
				$title_body=$row->title_body;
			}
		} else {
			$title_type=""; $title_body=""; $child_all="";
			$disabled="disabled";
			$code="";
			$onload="<script>alert(\"브랜드 선택이 잘못 되었습니다.\");</script>";
		}
	} else {
		$child_all="Y";
		if(strlen($mode)==0) {
			$title_type="";
			$title_body="";
		}
	}
}

if(strlen($onload)==0 && $mode=="modify" && strlen($code)>0) {
	if($child_all=="Y") {
		$sql = "SELECT bridx as code FROM tblproductbrand ";
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
		}
		
		if(strlen($qry)>0) {
			$result=mysql_query($sql.$qry,get_db_conn());
			unset($arrcode);
			while($row=mysql_fetch_object($result)) {
				$arrcode[]=$row->code;
				if($title_type!="image") {
					@unlink($imagepath."BRD".$row->code.".gif");
				}
			}
			mysql_free_result($result);
		}
	} else {
		$arrcode[]=$code;
		if($title_type!="image") {
			@unlink($imagepath."BRD".$code.".gif");
		}
	}
	
	if(count($arrcode)==0) {
		$onload="<script>alert(\"적용할 브랜드가 존재하지 않습니다.\");</script>";
	} else {
		if($title_type=="image") {
			$upfile=$_FILES["upfileimage"];
			if($upfile[size] < 153600) {
				if (strlen($upfile[name])>0 && file_exists($upfile[tmp_name])) {
					$ext = strtolower(substr($upfile[name],strlen($upfile[name])-3,3));
					if($ext=="gif" || $ext=="jpg"){
						$imagenameorg="BRD".$arrcode[0].".gif";
						move_uploaded_file($upfile[tmp_name],$imagepath.$imagenameorg);
						chmod($imagepath.$imagenameorg,0666);
						for($i=1;$i<count($arrcode);$i++) {
							$imagename="BRD".$arrcode[$i].".gif";
							copy($imagepath.$imagenameorg, $imagepath.$imagename);
						}
					} else {
						$onload="<script>alert(\"이미지 등록은 gif, jpg 파일만 등록 가능합니다.\\n\\n확인 후 다시 등록하시기 바랍니다.\");</script>";
					}
				} else {
					$onload="<script>alert(\"이미지 선택이 안되었거나 잘못된 이미지 파일입니다.\\n\\n파일 확인 후 다시 등록하시기 바랍니다.\");</script>";
				}
			} else {
				$onload="<script>alert(\"이미지 등록은 최대 150KB 까지 등록이 가능합니다.\\n\\n이미지 용량을 줄여서 다시 등록하시기 바랍니다.\");</script>";
			}
			if(strlen($onload)==0) {
				$sql = "UPDATE tblproductbrand SET title_type='".$title_type."',title_body=NULL ";
				if($child_all=="Y") {
					$sql.= $qry;
					$onload="<script>alert(\"$code 브랜드 일괄 상단/이벤트 수정이 완료되었습니다.\");</script>";
					$title_type="";
					$title_body="";
				} else {
					$sql.= "WHERE bridx='".$code."' ";
					$onload="<script>alert(\"브랜드별 상단/이벤트 수정이 완료되었습니다.\");</script>";
				}
				$update = mysql_query($sql,get_db_conn());
			}
		} else if($title_type=="html") {
			if(strlen($onload)==0) {
				$sql = "UPDATE tblproductbrand SET title_type='".$title_type."',title_body='".$title_body."' ";
				if($child_all=="Y") {
					$sql.= $qry;
					$onload="<script>alert(\"$code 브랜드 일괄 상단/이벤트 수정이 완료되었습니다.\");</script>";
					$title_type="";
					$title_body="";
				} else {
					$sql.= "WHERE bridx='".$code."' ";
					$onload="<script>alert(\"브랜드별 상단/이벤트 수정이 완료되었습니다.\");</script>";
				}
				$update = mysql_query($sql,get_db_conn());
			}
		} else {
			if(strlen($onload)==0) {
				$sql = "UPDATE tblproductbrand SET title_type='".$title_type."',title_body='".$title_body."' ";
				if($child_all=="Y") {
					$sql.= $qry;
					$onload="<script>alert(\"$code 브랜드 일괄 상단/이벤트 수정이 완료되었습니다.\");</script>";
				} else {
					$sql.= "WHERE bridx='".$code."' ";
					$onload="<script>alert(\"브랜드별 상단/이벤트 수정이 완료되었습니다.\");</script>";
				}
				$update = mysql_query($sql,get_db_conn());
				$title_type="";$title_body="";
			}
		}
	}
}
?>

<? INCLUDE "header.php"; ?>
<script type="text/javascript" src="lib.js.php"></script>
<script>var LH = new LH_create();</script>
<script for=window event=onload>LH.exec();</script>
<script>LH.add("parent_resizeIframe('ListFrame')");</script>
<SCRIPT LANGUAGE="JavaScript">
<!--
var shop="layer1";
var ArrLayer = new Array ("layer1","layer2","layer3");
function ViewLayer(gbn){
	if(document.all){
		for(i=0;i<ArrLayer.length;i++) {
			if (ArrLayer[i] == gbn)
				document.all[ArrLayer[i]].style.display="";
			else
				document.all[ArrLayer[i]].style.display="none";
		}
	} else if(document.getElementById){
		for(i=0;i<ArrLayer.length;i++) {
			if (ArrLayer[i] == gbn)
				document.getElementByld[ArrLayer[i]].style.display="";
			else
				document.getElementByld[ArrLayer[i]].style.display="none";
		}
	} else if(document.layers){
		for(i=0;i<ArrLayer.length;i++) {
			if (ArrLayer[i] == gbn)
				document.layers[ArrLayer[i]].display="";
			else
				document.layers[ArrLayer[i]].display="none";
		}
	}
	shop=gbn;
	parent_resizeIframe('ListFrame');
}

function Save() {
	if(!document.form1.code.value) {
		alert("상품 브랜드를 선택하세요.");
		return;
	}
	if(document.form1.title_type[1].checked==true) {
		if(document.form1.upfileimage.value.length==0) {
			alert("등록할 이미지를 선택하세요.");
			document.form1.upfileimage.focus();
			return;
		}
	} else if(document.form1.title_type[2].checked==true) {
		if(document.form1.title_body.value.length==0) {
			alert("편집내용을 입력하세요.");
			document.form1.title_body.focus();
			return;
		}
	}
	if(confirm("수정하시겠습니까?")) {
		document.form1.mode.value="modify";
		document.form1.submit();
	}
}

function TitleDelete() {
<?
	if($child_all == "Y") {
		echo "msg=\"".$code." 브랜드 일괄 상단 디자인을 삭제하시겠습니까?\\n\\n삭제하시더라도 상품 브랜드는 삭제되지 않습니다.\";";
	} else {
		echo "msg=\"해당 카테고리의 상단 디자인을 삭제하시겠습니까?\\n\\n삭제하시더라도 상품 브랜드는 삭제되지 않습니다.\";";
	}
?>
	if(confirm(msg)) {
		document.form1.mode.value="delete";
		document.form1.submit();
	}
}
//-->
</SCRIPT>
<table cellpadding="0" cellspacing="0" width="100%">
<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post enctype="multipart/form-data">
<input type=hidden name=mode>
<input type=hidden name=code value="<?=$code?>">
<tr>
	<td width="473">
	<table cellpadding="0" cellspacing="0" width="100%" height="100%">
	<tr>
		<td width="100%" bgcolor="white"><IMG SRC="images/product_mainlist_text4.gif" border="0"></td>
	</tr>
	<tr>
		<td  HEIGHT=3></td>
	</tr>
	<tr>
		<td width="100%" bgcolor="eeeeee" HEIGHT=2 ALT=""></td>
	</tr>
	<tr>
		<td width="100%" height="100%" valign="top" style="border-bottom-width:2px; border-bottom-color:eeeeee; border-bottom-style:solid;">
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td width="100%" style="padding-bottom:2pt;">
			<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
			<col width=150></col>
			<col width=></col>
			<TR>
				<TD colspan=2 background="images/table_top_line.gif"></TD>
			</TR>
			<TR>
				<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">편집타입 선택</TD>
				<TD class="td_con1">
				<INPUT id=idx_title_type1 onclick="ViewLayer('layer1')" type=radio value="" <?if(strlen($title_type)==0)echo"checked";?> name=title_type <?=$disabled?>><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand; TEXT-DECORATION: none" onmouseout="style.textDecoration='none'" for=idx_title_type1>없음</LABEL> 
				<INPUT id=idx_title_type2 onclick="ViewLayer('layer2')" type=radio value=image <?if($title_type=="image")echo"checked";?> name=title_type <?=$disabled?>><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand; TEXT-DECORATION: none" onmouseout="style.textDecoration='none'" for=idx_title_type2>이미지</LABEL> 
				<INPUT id=idx_title_type3 onclick="ViewLayer('layer3')" type=radio value=html <?if($title_type=="html")echo"checked";?> name=title_type <?=$disabled?>><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand; TEXT-DECORATION: none" onmouseout="style.textDecoration='none'" for=idx_title_type3>HTML편집</LABEL>
				</TD>
				<TD>
			</TR>
			<TR>
				<TD colspan="2" background="images/table_con_line.gif"></TD>
			</TR>
			<TR>
				<TD colspan=2>
				<div id=layer1 style="margin-left:0;display:hide; display:<?=(strlen($title_type)==0?"block":"none")?> ;border-style:solid; border-width:0; border-color:black;background:#FFFFFF;padding:0;">

				</div>
				<div id=layer2 style="margin-left:0;display:hide; display:<?=($title_type=="image"?"block":"none")?> ;border-style:solid; border-width:0; border-color:black;background:#FFFFFF;padding:0;">
				<table border=0 cellpadding=0 cellspacing=0 width=100%>
				<col width=150></col>
				<col width=></col>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">이미지 파일</TD>
					<TD class="td_con1"><INPUT type=file size=38 name=upfileimage style="width:100%" class="input" <?=$disabled?>><br><span class="font_orange" style="font-size:11px;letter-spacing:-0.5pt;">* 이미지는 150KB 이하의 GIF, JPG만 가능</span></TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD colspan=2 style="padding:5" align="center">
					<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
					<tr>
						<td align="center">
<?
					if((int)$code>0 && $title_type=="image") {
						echo "<img src=\"".$imagepath."BRD".$code.".gif\" border=0 width=100% align=absmiddle>";
					} else {
						echo "<img src=\"images/code_eventnoimg.gif\" border=0 align=absmiddle>";
					}
?>
						</td>
					</tr>
					</table>
					</TD>
				</TR>
				</TABLE>				
				</div>
				<div id=layer3 style="margin-left:0;display:hide; display:<?=($title_type=="html"?"block":"none")?> ;border-style:solid; border-width:0; border-color:black;background:#FFFFFF;padding:0;">
				<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
				<TR>
					<TD colspan="2"><TEXTAREA style="WIDTH:100%" name=title_body rows=8 wrap=off cols="86" class="textarea" <?=$disabled?>><?=$title_body?></TEXTAREA></TD>
				</TR>
				</TABLE>
				</div>
				</TD>
			</TR>
			<TR>
				<TD colspan="2" background="images/table_con_line.gif"></TD>
			</TR>
			<TR>
				<TD colspan=2 background="images/table_top_line.gif"></TD>
			</TR>
			</TABLE>
			</td>
		</tr>
		<tr>
			<td align=center style="padding-top:2pt; padding-bottom:2pt;" height="22">
<?
			if($disabled == "disabled") {
				echo "<img src=\"images/btn_edit1.gif\" width=\"113\" height=\"38\" border=\"0\" hspace=\"0\" vspace=\"4\">";
			} else {
				echo "<a href=\"javascript:Save();\"><img src=\"images/btn_edit2.gif\" width=\"113\" height=\"38\" border=\"0\" hspace=\"0\" vspace=\"4\"></a>";
			}
?>
			</td>
		</tr>
		</table>
		</td>
	</tr>
	</table>
	</td>
</tr>
</form>
</table>
<?=$onload?>