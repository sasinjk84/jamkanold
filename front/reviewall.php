<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

if ($_data->ETCTYPE["REVIEW"]!="Y") {
	echo "<html><head><title></title></head><body onload=\"alert('����ǰ�� ���� �Խ����� �̿��� �� �����ϴ�.');location.href='".$Dir."main/main.php';\"></body></html>";exit;
}

$reviewlist=$_data->ETCTYPE["REVIEWLIST"];
$reviewdate=$_data->ETCTYPE["REVIEWDATE"];
if(strlen($reviewlist)==0) $reviewlist="N";

$tmp_filter=explode("#",$_data->filter);
$filter_array=explode("REVIEWROW",$tmp_filter[1]);
$reviewrow=(int)$filter_array[1];
if($reviewrow<8) $reviewrow=8;

$code=$_REQUEST["code"];


$codeA=(substr($code,0,3)!=""?substr($code,0,3):"000");
$codeB=(substr($code,3,3)!=""?substr($code,3,3):"000");
$codeC=(substr($code,6,3)!=""?substr($code,6,3):"000");
$codeD=(substr($code,9,3)!=""?substr($code,9,3):"000");

$sort=(int)$_POST["sort"];
$listnum=(int)$_POST["listnum"];
$reviewtype= !_empty($_POST['reviewtype'])?trim($_POST['reviewtype']):"";
if($sort>1) $sort=0;	//0:�ֱٵ�ϼ�, 1:����������
if($listnum<=0) $listnum=$reviewrow;

//����Ʈ ����
$setup[page_num] = 10;
$setup[list_num] = $listnum;

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

?>

<HTML>
<HEAD>
	<TITLE><?=$_data->shoptitle?> - �� ��ǰ��</TITLE>
	<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">
	<META http-equiv="X-UA-Compatible" content="IE=Edge" />

	<META name="description" content="<?=(strlen($_data->shopdescription)>0?$_data->shopdescription:$_data->shoptitle)?>">
	<META name="keywords" content="<?=$_data->shopkeyword?>">
	<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
	<script language="javascript" type="text/javascript" src="/js/jquery-1.10.2.min.js"></script>
	<script language="javascript" type="text/javascript">
	$j = jQuery.noConflict();
	</script>
	
	<?include($Dir."lib/style.php")?>
	<SCRIPT LANGUAGE="JavaScript">
		<!--
		function select_code(code) {
			document.form1.code.value=code;
			document.form1.submit();
		}
		
		function select_code_new(el){
			document.form1.code.value=$j(el).val();
			document.form1.submit();
		}

		function change_sort(val) {
			document.form2.sort.value=val;
			document.form2.block.value="";
			document.form2.gotopage.value="";
			document.form2.submit()
		}

		function change_listnum(val) {
			document.form2.listnum.value=val;
			document.form2.block.value="";
			document.form2.gotopage.value="";
			document.form2.submit()
		}

		function GoPage(block,gotopage) {
			document.form2.block.value = block;
			document.form2.gotopage.value = gotopage;
			document.form2.submit();
		}

		function CheckForm() {
			if(document.form1.prcode2.value.length==0) {
				alert("��ǰ ������ �ϼ���.");
				document.form1.prcode2.focus();
				return;
			}
			document.form1.code.value=document.form1.prcode2.value;
			document.form1.submit();
		}

		function view_review(cnt) {
			if(typeof(document.all.reviewspan)=="object" && typeof(document.all.reviewspan.length)!="undefined") {
				for(i=0;i<document.all.reviewspan.length;i++) {
					if(cnt==i) {
						if(document.all.reviewspan[i].style.display=="none") {
							document.all.reviewspan[i].style.display="block";
						} else {
							document.all.reviewspan[i].style.display="none";
						}
					} else {
						document.all.reviewspan[i].style.display="none";
					}
				}
			} else {
				if(document.all.reviewspan.style.display=="none") {
					document.all.reviewspan.style.display="block";
				} else {
					document.all.reviewspan.style.display="none";
				}
			}
		}

		function reviewSelect(type){
			var _form = document.form2;

			_form.reviewtype.value = type;
			_form.submit();

			return;
		}

		function review_open(prcode,num) {
			window.open("<?=$Dir.FrontDir?>review_popup.php?prcode="+prcode+"&num="+num,"","width=450,height=400,scrollbars=yes");
		}
		//-->
	</SCRIPT>
</HEAD>

<body<?=(substr($_data->layoutdata["MOUSEKEY"],0,1)=="Y"?" oncontextmenu=\"return false;\"":"")?><?=(substr($_data->layoutdata["MOUSEKEY"],1,1)=="Y"?" ondragstart=\"return false;\" onselectstart=\"return false;\"":"")?> leftmargin="0" marginwidth="0" topmargin="0" marginheight="0"><?=(substr($_data->layoutdata["MOUSEKEY"],2,1)=="Y"?"<meta http-equiv=\"ImageToolbar\" content=\"No\">":"")?>

<? include ($Dir.MainDir.$_data->menu_type.".php") ?>

<!-- �� ��ǰ�� ��ü���� ��� �޴� -->
<div class="currentTitle">
	<div class="titleimage">�� ��ǰ��</div>
	<!--<div class="current">Ȩ &gt; <SPAN class="nowCurrent">�� ��ǰ�� ��ü����</span></div>-->
</div>
<!-- �� ��ǰ�� ��ü���� ��� �޴� -->


<?
$colspan=6;
if($reviewdate!="N") $colspan=7;

$newdesign="";
$sql="SELECT body FROM ".$designnewpageTables." WHERE type='reviewall'";
$result=mysql_query($sql,get_db_conn());
if($row=mysql_fetch_object($result)) {
	$body=$row->body;
	$body=str_replace("[DIR]",$Dir,$body);
	$newdesign="Y";
} else {
	if(file_exists($Dir.TempletDir."review/review.php")) {
		$fp=fopen($Dir.TempletDir."review/review.php","r");
		if($fp) {
			while (!feof($fp)) {$buffer.= fgets($fp, 1024);}
		}
		fclose($fp);
		$body=$buffer;
	}
}
mysql_free_result($result);

if($num=strpos($body,"[CODEA_")) {
	$s_tmp=explode("_",substr($body,$num+1,strpos($body,"]",$num)-$num-1));
	$codeA_style=$s_tmp[1];
}
if($num=strpos($body,"[CODEB_")) {
	$s_tmp=explode("_",substr($body,$num+1,strpos($body,"]",$num)-$num-1));
	$codeB_style=$s_tmp[1];
}
if($num=strpos($body,"[CODEC_")) {
	$s_tmp=explode("_",substr($body,$num+1,strpos($body,"]",$num)-$num-1));
	$codeC_style=$s_tmp[1];
}
if($num=strpos($body,"[CODED_")) {
	$s_tmp=explode("_",substr($body,$num+1,strpos($body,"]",$num)-$num-1));
	$codeD_style=$s_tmp[1];
}
if($num=strpos($body,"[PRCODE_")) {
	$s_tmp=explode("_",substr($body,$num+1,strpos($body,"]",$num)-$num-1));
	$prcode_style=$s_tmp[1];
}

if(strlen($codeA_style)==0) $codeA_style="width:150px";
if(strlen($codeB_style)==0) $codeB_style="width:150px";
if(strlen($codeC_style)==0) $codeC_style="width:150px";
if(strlen($codeD_style)==0) $codeD_style="width:150px";
if(strlen($prcode_style)==0)$prcode_style="width:312px";

if ($_data->title_type=="Y" && file_exists($Dir.DataDir."design/reviewall_title.gif")) {
	$review_title="<img src=\"".$Dir.DataDir."design/reviewall_title.gif\" border=0 alt=\"�� ��ǰ��\">";
} else {
	/*
	$review_title.="<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>\n";
	$review_title.="<TR>\n";
	$review_title.="	<TD><IMG SRC=".$Dir."images/".$_data->icon_type."/reviewall_title_head.gif ALT=></TD>\n";
	$review_title.="	<TD width=100% valign=top background=".$Dir."images/".$_data->icon_type."/reviewall_title_bg.gif></TD>\n";
	$review_title.="	<TD width=40><IMG SRC=".$Dir."images/".$_data->icon_type."/reviewall_title_tail.gif ALT=></TD>\n";
	$review_title.="</TR>\n";
	$review_title.="</TABLE>\n";
	*/
}

$codeA_select="";
//$codeA_select.="<select name=codeA2 onchange=\"select_code(options.value)\" style=\"".$codeA_style."\">\n";
$codeA_select.="<select name=codeA2 onchange=\"select_code_new(this)\" style=\"".$codeA_style."\">\n";
$codeA_select.="<option value=\"\"> 1���з� ����</option>\n";
if(strlen($_ShopInfo->getMemid())==0) {
	$add_qry="AND group_code='' ";
} else {
	$add_qry ="AND (group_code='".$_ShopInfo->getMemgroup()."' OR group_code='ALL' ";
	$add_qry.="OR group_code='') ";
}
$is_codeB=true;
$sql = "SELECT codeA, codeB, codeC, codeD, type, code_name FROM tblproductcode ";
$sql.= "WHERE codeB='000' AND codeC='000' ";
$sql.= "AND codeD='000' AND (type='L' OR type='LX') ".$add_qry;
$result=mysql_query($sql,get_db_conn());
while($row=mysql_fetch_object($result)) {
	$selA="";
	if($codeA==$row->codeA) {
		$selA="selected";
		if($row->type=="LX") $is_codeB=false;
	}
	$codeA_select.="<option value=\"".$row->codeA.$row->codeB.$row->codeC.$row->codeD."\" ".$selA.">".$row->code_name."</option>\n";
}
mysql_free_result($result);
$codeA_select.="</select>\n";

$codeB_select="";
//$codeB_select.="<select name=codeB2 onchange=\"select_code(options.value)\" style=\"".$codeB_style."\">\n";
$codeB_select.="<select name=codeB2 onchange=\"select_code_new(this)\" style=\"".$codeB_style."\">\n";
$is_codeC=true;
if($is_codeB==false) {
	$codeB_select.="<option value=\"\">���Ϻз�</option>\n";
	$is_codeC=false;
} else {
	$codeB_select.="<option value=\"".$codeA."\"> 2���з� ����</option>\n";
	if(strlen($codeA)==3 && $codeA!="000") {
		$sql = "SELECT codeA, codeB, codeC, codeD, type, code_name FROM tblproductcode ";
		$sql.= "WHERE codeA='".$codeA."' ";
		$sql.= "AND codeB!='000' AND codeC='000' ";
		$sql.= "AND codeD='000' AND (type='LM' OR type='LMX') ".$add_qry;
		$result=mysql_query($sql,get_db_conn());
		while($row=mysql_fetch_object($result)) {
			$selB="";
			if($codeB==$row->codeB) {
				$selB="selected";
				if($row->type=="LMX") $is_codeC=false;
			}
			$codeB_select.="<option value=\"".$row->codeA.$row->codeB.$row->codeC.$row->codeD."\" ".$selB.">".$row->code_name."</option>\n";
		}
		mysql_free_result($result);
	}
}
$codeB_select.="</select>\n";

$codeC_select="";
$codeC_select.="<select name=codeC2 onchange=\"select_code_new(this)\" style=\"".$codeC_style."\">\n";
$is_codeD=true;
if($is_codeC==false) {
	$codeC_select.="<option value=\"\">���Ϻз�</option>\n";
	$is_codeD=false;
} else {
	$codeC_select.="<option value=\"".$codeA.$codeB."\"> 3���з� ����</option>\n";
	if(strlen($codeA)==3 && $codeA!="000" && strlen($codeB)==3 && $codeB!="000") {
		$sql = "SELECT codeA, codeB, codeC, codeD, type, code_name FROM tblproductcode ";
		$sql.= "WHERE codeA='".$codeA."' ";
		$sql.= "AND codeB='".$codeB."' AND codeC!='000' AND codeD='000' ";
		$sql.= "AND (type='LM' OR type='LMX') ".$add_qry;
		$result=mysql_query($sql,get_db_conn());
		while($row=mysql_fetch_object($result)) {
			$selC="";
			if($codeC==$row->codeC) {
				$selC="selected";
				if($row->type=="LMX") $is_codeD=false;
			}
			$codeC_select.="<option value=\"".$row->codeA.$row->codeB.$row->codeC.$row->codeD."\" ".$selC.">".$row->code_name."</option>\n";
		}
		mysql_free_result($result);
	}
}
$codeC_select.="</select>\n";

$codeD_select="";
$codeD_select.="<select name=codeD2 onchange=\"select_code_new(this)\" style=\"".$codeD_style."\">\n";
if($is_codeD==false) {
	$codeD_select.="<option value=\"\">���Ϻз�</option>\n";
} else {
	$codeD_select.="<option value=\"".$codeA.$codeB.$codeC."\"> 4���з� ����</option>\n";
	if(strlen($codeA)==3 && $codeA!="000" && strlen($codeB)==3 && $codeB!="000" && strlen($codeC)==3 && $codeC!="000") {
		$sql = "SELECT codeA, codeB, codeC, codeD, type, code_name FROM tblproductcode ";
		$sql.= "WHERE codeA='".$codeA."' ";
		$sql.= "AND codeB='".$codeB."' AND codeC='".$codeC."' AND codeD!='000' ";
		$sql.= "AND (type='LM' OR type='LMX') ".$add_qry;
		$result=mysql_query($sql,get_db_conn());
		while($row=mysql_fetch_object($result)) {
			$selD="";
			if($codeD==$row->codeD) {
				$selD="selected";
			}
			$codeD_select.="<option value=\"".$row->codeA.$row->codeB.$row->codeC.$row->codeD."\" ".$selD.">".$row->code_name."</option>\n";
		}
		mysql_free_result($result);
	}
}
$codeD_select.="</select>\n";

$prcode_select="";
$prcode_select.="<select name=prcode2 style=\"".$prcode_style."\">\n";
$prcode_select.="<option value=\"\"> ��ǰ ����</option>\n";
$product_likecode=$codeA.$codeB.$codeC.$codeD;
if($is_codeD==false || $codeD!="000") {
	$sql = "SELECT a.productcode, a.productname ";
	$sql.= "FROM tblproduct AS a ";
	$sql.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
	$sql.= "WHERE a.productcode LIKE '".$product_likecode."%' AND a.display!='N' ";
	$sql.= "AND (a.group_check='N' OR b.group_code='".$_ShopInfo->getMemgroup()."') ";
	$sql.= "ORDER BY a.date DESC ";

	$result=mysql_query($sql,get_db_conn());
	while($row=mysql_fetch_object($result)) {
		$selP="";
		if($code==$row->productcode) {
			$selP="selected";
		}
		$prcode_select.="<option value=\"".$row->productcode."\" ".$selP.">".$row->productname."</option>\n";
	}
	mysql_free_result($result);
}
$prcode_select.="</select>\n";

$searchok="CheckForm()";




$review_list="";
if($num=strpos($body,"[LIST_")) {
	$s_tmp=explode("_",substr($body,$num+1,strpos($body,"]",$num)-$num-1));
	$imgwidth=(int)$s_tmp[1];
}
if($imgwidth<10) $imgwidth=55;
/*
$addsql=	$sphotoreview=$sbestreview=$sbasicreview=$sallreview="";
if(strlen($reviewtype)>0){
	switch($reviewtype){
		case "photo":
			$sphotoreview = " selected";
			$addsql .= "AND img IS NOT NULL AND img != '' ";
		break;
		case "best":
			$sbestreview = " selected";
			$addsql .= "AND best = 'Y' ";
		break;
		case "basic":
			$sbasicreview = " selected";
			$addsql .= "AND img IS NULL OR img = '' ";
		break;
		case "all":
		default:
			$sallreview = " selected";
		break;
	}
}
*/



#��ǰ�� Ÿ�� ���� ��
$addsql = "";
$sphotoreview = $sbestreview = $sbasicreview = $sallreview = "tabOff";
//if(strlen($reviewtype)>0){
	switch($reviewtype){
		case "photo":
			$sphotoreview = "tabOn";
			$addsql .= "AND img IS NOT NULL AND img != '' ";
		break;
		case "best":
			$sbestreview = "tabOn";
			$addsql .= "AND best = 'Y' ";
		break;
		case "basic":
			$sbasicreview = "tabOn";
			$addsql .= "AND img IS NULL OR img = '' ";
		break;
		case "all":
		default:
			$sallreview = "tabOn";
		break;
	}
//}

$review_list.="<table border=0 cellpadding=0 cellspacing=0 width=100%>\n";
$review_list.="<tr>\n";
$review_list.="	<td style=\"font-size:11px;letter-spacing:-0.5pt;\">\n";
$review_list.="	<table border=0 cellpadding=0 cellspacing=0 width=100%>\n";
$review_list.="	<tr>\n";
$review_list.="		<td style=\"color:#FF4C00;font-size:11px;letter-spacing:-0.5pt;\">\n";


$review_list.="			<div class=\"button ".$sallreview."\"><a href=\"javascript:reviewSelect('all');\">��ü��ǰ��</a></div>";
$review_list.="			<div class=\"button ".$sbestreview."\"><a href=\"javascript:reviewSelect('best');\">����Ʈ��ǰ��</a></div>";
$review_list.="			<div class=\"button ".$sphotoreview."\"><a href=\"javascript:reviewSelect('photo');\">�����ǰ��</a></div>";
$review_list.="			<div class=\"button ".$sbasicreview."\"><a href=\"javascript:reviewSelect('basic');\">�Ϲݻ�ǰ��</a></div>";

/*
$review_list.="			<select name=\"reviewselect\" onchange=\"reviewSelect(this.value)\">\n";
$review_list.="				<option value=\"all\"$sallreview>��ü��ǰ��</option>\n";
$review_list.="				<option value=\"photo\"$sphotoreview>�����ǰ��</option>\n";
$review_list.="				<option value=\"basic\"$sbasicreview>�Ϲݻ�ǰ��</option>\n";
$review_list.="				<option value=\"best\"$sbestreview>����Ʈ��ǰ��</option>\n";
$review_list.="			</select>\n";
*/


$review_list.="		</td>\n";
$review_list.="		<td align=right>\n";
$review_list.="		<select name=sort onchange=\"change_sort(options.value)\" class=\"select\">\n";
$review_list.="		<option value=0 ";if($sort=="0")$review_list.="selected";$review_list.=">�ֱٵ�ϼ�</option>\n";
$review_list.="		<option value=1 ";if($sort=="1")$review_list.="selected";$review_list.=">����������</option>\n";
$review_list.="		</select>\n";
$review_list.="		&nbsp;\n";
$review_list.="		<select name=listnum onchange=\"change_listnum(options.value)\" class=\"select\">\n";
$review_list.="		<option value=10 ";if($listnum=="10")$review_list.="selected";$review_list.=">10���� ����</option>\n";
$review_list.="		<option value=20 ";if($listnum=="20")$review_list.="selected";$review_list.=">20���� ����</option>\n";
$review_list.="		<option value=30 ";if($listnum=="30")$review_list.="selected";$review_list.=">30���� ����</option>\n";
$review_list.="		<option value=40 ";if($listnum=="40")$review_list.="selected";$review_list.=">40���� ����</option>\n";
$review_list.="		</select>\n";
$review_list.="		</td>\n";
$review_list.="	</tr>\n";
$review_list.="	<tr><td height=5></td></tr>\n";
$review_list.="	</table>\n";
$review_list.="	</td>\n";
$review_list.="</tr>\n";
$review_list.="<tr><td height=3></td></tr>\n";
$review_list.="<tr>\n";
$review_list.="	<td>\n";
$review_list.="	<table border=0 cellpadding=0 cellspacing=0 width=100% style=\"table-layout:fixed\" class=\"orderlistTbl1\">\n";
$review_list.="	<col width=40></col>\n";
$review_list.="	<col width=80></col>\n";
$review_list.="	<col width=1></col>\n";
$review_list.="	<col width=></col>\n";
$review_list.="	<col width=80></col>\n";
if($reviewdate!="N") {
	$review_list.="<col width=80></col>\n";
}
$review_list.="	<col width=80></col>\n";
$review_list.="	<tr>\n";
$review_list.="		<th>��ȣ</th>\n";
$review_list.="		<th>�̹���</th>\n";
$review_list.="		<th></td>\n";
$review_list.="		<th>��ǰ��/����ı�</th>\n";
$review_list.="		<th>�ۼ���</th>\n";
if($reviewdate!="N") {
	$review_list.="	<th>�ۼ���</th>\n";
}
$review_list.="		<th>����</th>\n";
$review_list.="	</tr>\n";

$likecode="";
if(strlen($code)==18) $likecode=$code;
else {
	if($codeA!="000") $likecode.=$codeA;
	if($codeB!="000") $likecode.=$codeB;
	if($codeC!="000") $likecode.=$codeC;
	if($codeD!="000") $likecode.=$codeD;
}

//if(strlen($likecode)>=3) {
	$qry = "WHERE 1=1 ";
	if(strlen($likecode)>0) {
		$qry.= "AND a.productcode LIKE '".$likecode."%' ";
	}
	$qry.= "AND a.productcode=b.productcode ";
	if($_data->review_type=="A") $qry.= "AND a.display='Y' ";
	$qry.= "AND b.display='Y' ";
	
	
	$sql = "SELECT COUNT(*) as t_count ";
	$sql.= "FROM tblproductreview a, tblproduct b ";
	$sql.= "LEFT OUTER JOIN tblproductgroupcode c ON b.productcode=c.productcode ";
	$sql.= $qry;
	$sql.= $addsql;
	$sql.= "AND (b.group_check='N' OR c.group_code='".$_ShopInfo->getMemgroup()."') ";
	$result=mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
	$t_count = (int)$row->t_count;
	mysql_free_result($result);
	$pagecount = (($t_count - 1) / $setup[list_num]) + 1;

	$sql = "SELECT a.num,a.id,a.name,a.marks,a.date,a.content,a.img,b.productcode,b.productname,b.tinyimage,b.quantity,b.selfcode ";
	$sql.= "FROM tblproductreview a, tblproduct b ";
	$sql.= "LEFT OUTER JOIN tblproductgroupcode c ON b.productcode=c.productcode ";
	$sql.= $qry;
	$sql.= $addsql;
	$sql.= "AND (b.group_check='N' OR c.group_code='".$_ShopInfo->getMemgroup()."') ";
	if($sort==0) $sql.= "ORDER BY a.date DESC ";
	else if($sort==1) $sql.= "ORDER BY marks DESC ";
	$sql.= "LIMIT " . ($setup[list_num] * ($gotopage - 1)) . ", " . $setup[list_num];
	$result=mysql_query($sql,get_db_conn());
	$cnt=0;
	while($row=mysql_fetch_object($result)) {
		$number = ($t_count-($setup[list_num] * ($gotopage-1))-$cnt);

		$date=substr($row->date,0,4)."/".substr($row->date,4,2)."/".substr($row->date,6,2);
		$content=explode("=",$row->content);
		$review_list.="<tr id=\"A".$row->productcode."\" onmouseover=\"quickfun_show(this,'A".$row->productcode."','','row')\" onmouseout=\"quickfun_show(this,'A".$row->productcode."','none')\">\n";
		$review_list.="	<td align=center>".$number."</td>\n";
		$review_list.="	<td align=center>";
		if(strlen($row->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$row->tinyimage)) {
			$width = getimagesize($Dir.DataDir."shopimages/product/".$row->tinyimage);
			$review_list.="<img src=\"".$Dir.DataDir."shopimages/product/".$row->tinyimage."\" border=0 ";
			if ($width[0]>=$width[1] && $width[0]>$imgwidth) $review_list.=" width=$imgwidth ";
			else if ($width[0]<$width[1] && $width[1]>$imgwidth) $review_list.=" height=$imgwidth ";
			$review_list.="></td>";
		} else {
			$review_list.="<img src=\"".$Dir."images/no_img.gif\" border=0 width=$imgwidth></td>";
		}
		$review_list.="	<td style=\"position:relative;\"></td>";
		$review_list.="	<td>\n";
		$review_list.="	<table border=0 cellpadding=0 cellspacing=0 width=100%>\n";
		//$review_list.="	<tr><td style=\"padding-left:5\"><A HREF=\"".$Dir.FrontDir."productdetail.php?productcode=".$row->productcode."\" onmouseover=\"window.status='��ǰ����ȸ';return true;\" onmouseout=\"window.status='';return true;\"><FONT COLOR=\"#373737\"><B>".viewselfcode($row->productname,$row->selfcode)."</A> <A HREF=\"".$Dir.FrontDir."productdetail.php?productcode=".$row->productcode."\" onmouseover=\"window.status='��ǰ����ȸ';return true;\" onmouseout=\"window.status='';return true;\"><img src=\"".$Dir."images/common/review/btn_reviewprview.gif\" border=0 align=absmiddle></A></td></tr>\n";
		$linkpage="";
		if($reviewlist=="Y") {
			$linkpage="<A HREF=\"javascript:view_review(".$cnt.")\">";
		} else {
			$linkpage="<A HREF=\"javascript:review_open('".$row->productcode."',".$row->num.")\">";
		}
		$review_list.="	<tr><td style=\"padding-left:5\">".$linkpage."<FONT COLOR=\"#373737\"><B>".viewselfcode($row->productname,$row->selfcode)."</A> <A HREF=\"".$Dir.FrontDir."productdetail.php?productcode=".$row->productcode."\" onmouseover=\"window.status='��ǰ����ȸ';return true;\" onmouseout=\"window.status='';return true;\"><img src=\"".$Dir."images/common/review/btn_reviewprview.gif\" border=0 align=absmiddle></A></td></tr>\n";
		$review_list.="	<tr><td style=\"padding-left:5;padding-top:5\">";
		if($reviewlist=="Y") {
			$review_list.="<A HREF=\"javascript:view_review(".$cnt.")\">".titleCut(45,$content[0])."</A>";
		} else {
			$review_list.="<A HREF=\"javascript:review_open('".$row->productcode."',".$row->num.")\">".titleCut(45,$content[0])."</A>";
		}
		if(strlen($content[1])>0) $review_list.="<img src=\"".$Dir."images/common/review/review_replyicn.gif\" border=0 align=absmiddle>";
		$review_list.="	</td></tr>\n";
		$review_list.="	</table>\n";
		$review_list.="	</td>\n";
		$review_list.="	<td align=center>".$row->name."</td>";
		if($reviewdate!="N") {
			$review_list.="	<td align=center>".$date."</td>";
		}
		$review_list.="	<td align=center>";
		for($i=0;$i<$row->marks;$i++) $review_list.="<FONT color=#000000>��</FONT>";
		for($i=$row->marks;$i<5;$i++) {
			$review_list.="<FONT color=#DEDEDE>��</FONT>";
		}
		$review_list.="	</td>";
		$review_list.="</tr>\n";
		if($reviewlist=="Y") {
			$review_list.="<tr>\n";
			$review_list.="	<td></td>\n";
			$review_list.="	<td></td>\n";
			$review_list.="	<td></td>\n";
			$review_list.="	<td>\n";
			$review_list.="	<span id=reviewspan style=\"display:none; xcursor:hand\">\n";
			$review_list.="	<table cellpadding=0 cellspacing=0 border=0 width=100%>\n";
			$review_list.="	<tr><td><table><tr><td>";
			
			//��ǰ���̹����� �ִ°��
			if(!empty($row->img) && file_exists($Dir.DataDir."shopimages/productreview/".$row->img)){
				$review_list.="<img src=\"".$Dir.DataDir."shopimages/productreview/".$row->img."\" border=\"0\" width=\"260\" align='absmiddle'/></td><td>\n";
			}
			
			$review_list.= nl2br($content[0])."</td></tr></table></td></tr>\n";

			if(strlen($content[1])>0) {
				$review_list.="	<tr><td style=\"padding:5 5 5 10px\"><img src=\"".$Dir."images/common/review/review_replyicn2.gif\" align=absmiddle border=0> ".nl2br($content[1])."</td></tr>\n";
			}
			$review_list.="	</table>\n";
			$review_list.="	</span>\n";
			$review_list.="	</td>\n";
			$review_list.="	<td></td>\n";
			$review_list.="	<td></td>\n";
			if($reviewdate!="N") {
				$review_list.="	<td></td>\n";
			}
			$review_list.="</tr>\n";
		}
		$review_list.="\n";

		$cnt++;
	}
	mysql_free_result($result);

	if ($cnt==0) {
		$review_list.="<tr height=30><td class=lineleft colspan=".$colspan." align=center style=\"padding:30px;\">�˻��� ��ǰ���䰡 �����ϴ�.</td></tr>";
		$review_list.="\n";
	}

	$total_block = intval($pagecount / $setup[page_num]);

	if (($pagecount % $setup[page_num]) > 0) {
		$total_block = $total_block + 1;
	}

	$total_block = $total_block - 1;

	if (ceil($t_count/$setup[list_num]) > 0) {
		// ����	x�� ����ϴ� �κ�-����
		$a_first_block = "";
		if ($nowblock > 0) {
			$a_first_block .= "<a href='javascript:GoPage(0,1);' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='ù ������';return true\">[1...]</a>&nbsp;&nbsp;";

			$prev_page_exists = true;
		}

		$a_prev_page = "";
		if ($nowblock > 0) {
			$a_prev_page .= "<a href='javascript:GoPage(".($nowblock-1).",".($setup[page_num]*($block-1)+$setup[page_num]).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup[page_num]." ������';return true\">[prev]</a>&nbsp;&nbsp;";

			$a_prev_page = $a_first_block.$a_prev_page;
		}

		// �Ϲ� �������� ������ ǥ�úκ�-����

		if (intval($total_block) <> intval($nowblock)) {
			$print_page = "";
			for ($gopage = 1; $gopage <= $setup[page_num]; $gopage++) {
				if ((intval($nowblock*$setup[page_num]) + $gopage) == intval($gotopage)) {
					$print_page .= "<FONT color=red><B>".(intval($nowblock*$setup[page_num]) + $gopage)." ";
				} else {
					$print_page .= "<a href='javascript:GoPage(".$nowblock.",".(intval($nowblock*$setup[page_num]) + $gopage).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='������ : ".(intval($nowblock*$setup[page_num]) + $gopage)."';return true\">[".(intval($nowblock*$setup[page_num]) + $gopage)."]</a> ";
				}
			}
		} else {
			if (($pagecount % $setup[page_num]) == 0) {
				$lastpage = $setup[page_num];
			} else {
				$lastpage = $pagecount % $setup[page_num];
			}

			for ($gopage = 1; $gopage <= $lastpage; $gopage++) {
				if (intval($nowblock*$setup[page_num]) + $gopage == intval($gotopage)) {
					$print_page .= "<FONT color=red><B>".(intval($nowblock*$setup[page_num]) + $gopage)." ";
				} else {
					$print_page .= "<a href='javascript:GoPage(".$nowblock.",".(intval($nowblock*$setup[page_num]) + $gopage).");' onMouseOver=\"window.status='������ : ".(intval($nowblock*$setup[page_num]) + $gopage)."';return true\">[".(intval($nowblock*$setup[page_num]) + $gopage)."]</a> ";
				}
			}
		}		// ������ �������� ǥ�úκ�-��


		$a_last_block = "";
		if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
			$last_block = ceil($t_count/($setup[list_num]*$setup[page_num])) - 1;
			$last_gotopage = ceil($t_count/$setup[list_num]);

			$a_last_block .= "&nbsp;&nbsp;<a href='javascript:GoPage(".$last_block.",".$last_gotopage.");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='������ ������';return true\">[...".$last_gotopage."]</a>";

			$next_page_exists = true;
		}

		// ���� 10�� ó���κ�...

		$a_next_page = "";
		if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
			$a_next_page .= "&nbsp;&nbsp;<a href='javascript:GoPage(".($nowblock+1).",".($setup[page_num]*($nowblock+1)+1).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup[page_num]." ������';return true\">[next]</a>";

			$a_next_page = $a_next_page.$a_last_block;
		}
	} else {
		$print_page = "<B>1</B>";
	}

	$review_page="";
	$review_page.="<table border=0 cellpadding=0 cellspacing=0 width=100%>\n";
	$review_page.="<tr>\n";
	$review_page.="	<td align=center>\n";
	$review_page.="		".$a_div_prev_page.$a_prev_page.$print_page.$a_next_page.$a_div_next_page;
	$review_page.="	</td>\n";
	$review_page.="</tr>\n";
	$review_page.="</table>\n";
//} else {
//	$review_list.="<tr height=25><td class=lineleft colspan=".$colspan." align=center>�з� ������ �ϼ���.</td></tr>\n";
//	$review_list.="<tr><td colspan=\"".$colspan."\" height=1 bgcolor=#dddddd></td></tr>\n";
//}
$review_list.="	</table>\n";
$review_list.="	</td>\n";
$review_list.="</tr>\n";
$review_list.="</table>\n";

$reviewbody ="<table border=0 cellpadding=0 cellspacing=0 width=100%>\n";
$reviewbody.="<form name=form1 action=\"".$_SERVER[PHP_SELF]."\" method=post>\n";
$reviewbody.="<input type=hidden name=code value=\"".$code."\">\n";
$reviewbody.="<input type=hidden name=sort value=\"".$sort."\">\n";
$reviewbody.="<input type=hidden name=listnum value=\"".$listnum."\">\n";
$reviewbody.="<tr>\n";
$reviewbody.="	<td align=center>".$body."</td>\n";
$reviewbody.="</tr>\n";
$reviewbody.="</form>\n";
$reviewbody.="<form name=form2 method=post action=\"".$_SERVER[PHP_SELF]."\">\n";
$reviewbody.="<input type=hidden name=code value=\"".$code."\">\n";
$reviewbody.="<input type=hidden name=sort value=\"".$sort."\">\n";
$reviewbody.="<input type=hidden name=listnum value=\"".$listnum."\">\n";
$reviewbody.="<input type=hidden name=block value=\"".$block."\">\n";
$reviewbody.="<input type=hidden name=gotopage value=\"".$gotopage."\">\n";
$reviewbody.="<input type=hidden name=reviewtype value=\"".$reviewtype."\">\n";
$reviewbody.="</form>\n";
$reviewbody.="</table>\n";

$pattern=array("(\[DIR\])","(\[REVIEW_TITLE\])","(\[CODEA((\_){0,1})([0-9a-zA-Z\.\-\:\;\%\#\ ]){0,}\])","(\[CODEB((\_){0,1})([0-9a-zA-Z\.\-\:\;\%\#\ ]){0,}\])","(\[CODEC((\_){0,1})([0-9a-zA-Z\.\-\:\;\%\#\ ]){0,}\])","(\[CODED((\_){0,1})([0-9a-zA-Z\.\-\:\;\%\#\ ]){0,}\])","(\[PRCODE((\_){0,1})([0-9a-zA-Z\.\-\:\;\%\#\ ]){0,}\])","(\[SEARCHOK\])","(\[LIST((\_){0,1})([0-9]{2})\])","(\[PAGE\])");
$replace=array($Dir,$review_title,$codeA_select,$codeB_select,$codeC_select,$codeD_select,$prcode_select,$searchok,$review_list,$review_page);
$reviewbody = preg_replace($pattern,$replace,$reviewbody);

echo $reviewbody;
?>


	<? include ($Dir."lib/bottom.php") ?>
	<div id="create_openwin" style="display:none"></div>
</BODY>
</HTML>