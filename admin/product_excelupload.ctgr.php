<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$code=$_REQUEST["code"];
$depth=$_REQUEST["depth"];

$codeA=substr($code,0,3);
$codeB=substr($code,3,3);
$codeC=substr($code,6,3);
$codeD=substr($code,9,3);
if(strlen($codeA)!=3) $codeA="";
if(strlen($codeB)!=3) $codeB="";
if(strlen($codeC)!=3) $codeC="";
if(strlen($codeD)!=3) $codeD="";
$code=$codeA.$codeB.$codeC.$codeD;
if($codeA=="000") {
	$code=$codeA=$codeB=$codeC=$codeD="";
}

$len=0;
if(strlen($codeA)>0) {
	$likecode=$codeA;
	if(strlen($codeB>0)) $likecode.=$codeB;
	if(strlen($codeC>0)) $likecode.=$codeC;
	if(strlen($codeD>0)) $likecode.=$codeD;

	$len=strlen($likecode);
}

$level=2;
if($len==3) $level=2;
else if($len==6) $level=3;
else if($len==9) $level=4;

$code_str="�з�����";
if($depth=="2") $code_str="�� �� ��";
else if($depth=="3") $code_str="�� �� ��";
else if($depth=="4") $code_str="�� �� ��";

?>

<html>
<head>
<title></title>

<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">

<script type="text/javascript" src="lib.js.php"></script>
<script language="javascript" type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
<script> var $j = jQuery.noConflict();</script>

<SCRIPT LANGUAGE="JavaScript">
<!--
function sectSendIt(f,obj,x) {
	if(obj.value.length>0) {
		if(x == 2) {
			if($j(obj).attr('ctype')=="X") {
				f.code.value = obj.value+"000000";
			} else {
				f.code.value = obj.value;
			}
			durl = "product_excelupload.ctgr.php?depth=4";
			url = "product_excelupload.ctgr.php?depth=3&code="+obj.value;
			parent.CCodeCtgr.location.href = url;
			parent.DCodeCtgr.location.href = durl;
		} else if(x == 3) {
			if($j(obj).attr('ctype')=="X") {
				f.code.value = obj.value+"000";
			} else {
				f.code.value = obj.value;
			}
			url = "product_excelupload.ctgr.php?depth=4&code="+obj.value;
			parent.DCodeCtgr.location.href = url;
		} else if(x == 4) {
			f.code.value = obj.value;
		}
	} else {
		if(x == 2) {
			f.code.value=f.code1.options[f.code1.selectedIndex].value;
			durl = "product_excelupload.ctgr.php?depth=4";
			url = "product_excelupload.ctgr.php?depth=3";
			parent.CCodeCtgr.location.href = url;
			parent.DCodeCtgr.location.href = durl;
		} else if(x == 3) {
			f.code.value=parent.BCodeCtgr.form1.code.options[parent.BCodeCtgr.form1.code.selectedIndex].value;
			url = "product_excelupload.ctgr.php?depth=4";
			parent.DCodeCtgr.location.href = url;
		} else if(x == 4) {
			f.code.value=parent.CCodeCtgr.form1.code.options[parent.CCodeCtgr.form1.code.selectedIndex].value;
		}
	}
}
//-->
</SCRIPT>
<link rel=stylesheet href="style.css" type=text/css>
</head>
<body leftmargin=0 topmargin=0>
<form name="form1" method="post" action="">
<table border="0" cellpadding="0" cellspacing=0>
<tr>
	<td><select name="code" style=width:143 onchange="sectSendIt(parent.form1,this.options[this.selectedIndex],<?=$level?>);">
	<option value="">---- <?=$code_str?> ----</option>
<?
	if(strlen($code)>=3) {
		$sql = "SELECT codeA,codeB,codeC,codeD,code_name,type FROM tblproductcode ";
		$sql.= "WHERE codeA='".$codeA."' ";
		if(strlen($codeB)==3) {
			$sql.= "AND codeB='".$codeB."' ";
			if(strlen($codeC)==3) {
				$sql.= "AND codeC='".$codeC."' ";
				if(strlen($codeD)==3) {
					$sql.= "AND codeD='".$codeD."' ";
				}
			}
		}
		if($len==3) $sql.= "AND codeB!='000' AND codeC='000' AND codeD='000' ";
		if($len==6) $sql.= "AND codeC!='000' AND codeD='000' ";
		if($len==9) $sql.= "AND codeD!='000' ";
		$sql.= "AND type LIKE 'L%' ORDER BY sequence DESC ";
		//echo $sql; exit;
		$result=mysql_query($sql,get_db_conn());
		while($row=mysql_fetch_object($result)) {
			$codeval=$row->codeA;
			if($len==3) $codeval.=$row->codeB;
			else if($len==6) $codeval.=$row->codeB.$row->codeC;
			else if($len==9) $codeval.=$row->codeB.$row->codeC.$row->codeD;
			$ctype=substr($row->type,-1);
			if($ctype!="X") $ctype="";
			echo "<option value=\"".$codeval."\" ctype='".$ctype."'>".$row->code_name.$ctype."";
			if($ctype=="X" && $len<9) {
				echo " (���Ϻз�)";
			}
			echo "</option>\n";
		}
		mysql_free_result($result);
	}
?>
	</select></td>
</tr>
</table>
</form>
</body>
</html>