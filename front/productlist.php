<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
//include_once($Dir."lib/cache_product.php");
include_once($Dir."lib/shopdata.php");
include_once($Dir."lib/ext/product_func.php");
include_once($Dir."lib/ext/member_func.php");

$rcode=$_REQUEST["code"];

if(strlen($rcode)==0) {
	Header("Location:".$Dir.MainDir."main.php");
	exit;
}

$selfcodefont_start = "<font class=\"prselfcode\">"; //�����ڵ� ��Ʈ ����
$selfcodefont_end = "</font>"; //�����ڵ� ��Ʈ ��

$code = '';
$likecode='';
for($i=0;$i<4;$i++){
	$tcode = substr($rcode,$i*3,3);
	if(strlen($tcode) != 3 || $tcode == '000'){
		$tcode = '000';
	}else{
		$likecode.=$tcode;
	}
	${'code'.chr(65+$i)} = $tcode;
	$code.=$tcode;
}


/*
function getCodeLoc($code,$color1="9E9E9E",$color2="9E9E9E") {
	global $_ShopInfo, $Dir;
	$code_loc = "<A HREF=\"".$Dir.MainDir."main.php\"><FONT COLOR=\"".$color1."\">Ȩ</FONT></A> <FONT COLOR=\"".$color1."\">></FONT> ";
	$sql = "SELECT codeA,codeB,codeC,codeD,code_name,type FROM tblproductcode ";
	$sql.= "WHERE codeA='".substr($code,0,3)."' ";
	if(substr($code,3,3)!="000") {
		$sql.= "AND (codeB='".substr($code,3,3)."' OR codeB='000') ";
		if(substr($code,6,3)!="000") {
			$sql.= "AND (codeC='".substr($code,6,3)."' OR codeC='000') ";
			if(substr($code,9,3)!="000") {
				$sql.= "AND (codeD='".substr($code,9,3)."' OR codeD='000') ";
			} else {
				$sql.= "AND codeD='000' ";
			}
		} else {
			$sql.= "AND codeC='000' ";
		}
	} else {
		$sql.= "AND codeB='000' AND codeC='000' ";
	}
	$sql.= "ORDER BY codeA,codeB,codeC,codeD ASC ";
	$result=mysql_query($sql,get_db_conn());
	$i=0;
	while($row=mysql_fetch_object($result)) {
		$tmpcode=$row->codeA.$row->codeB.$row->codeC.$row->codeD;
		if($i>0) $code_loc.= " <FONT COLOR=\"".$color1."\">></FONT> ";
		if($code==$tmpcode) {
			$code_loc.="<A HREF=\"".$Dir.FrontDir."productlist.php?code=".$tmpcode."\"><FONT COLOR=\"".$color2."\"><B>".$row->code_name."</B></FONT></A>";
		} else {
			$code_loc.="<A HREF=\"".$Dir.FrontDir."productlist.php?code=".$tmpcode."\"><FONT COLOR=\"".$color1."\">".$row->code_name."</FONT></A>";
		}
		$code_loc.= $_tmp;
		$i++;
	}
	mysql_free_result($result);
	return $code_loc;
}
*/



function getCodeLoc($code,$color1="9E9E9E",$color2="9E9E9E") {
	global $_ShopInfo, $Dir,$code;
	$naviitem = array();
	array_push($naviitem,"<A HREF=\"".$Dir.MainDir."main.php\"><span style=\"color:".$color1.";\">Ȩ</span></A>&nbsp;");

	for($i=0;$i<4;$i++){
		$tmp = array();

		$getsub = ($GLOBALS['code'.chr(65+$i)] == '000');
		$tmp = getCategoryItems(substr($code,0,$i*3),true);
		if(is_array($tmp) && count($tmp) > 0 && count($tmp['items']) > 0){
			$str = '&nbsp;<select name="code'.chr(65+$i).'"  id="code'.chr(65+$i).'" onChange="javascript:chgNaviCode('.$i.')">';
			if($tmp['depth'] != $i){
				exit('System Error');
			}
			$sel = '';
			//if($getsub)  $str .= '<option value="">��ü</option>';
			$str .= '<option value="">��ü</option>';
			foreach($tmp['items'] as $item){
				if($sel != 'ok'){
					for($j=0;$j<=$i;$j++){
						if($j >0 && $sel != 'selected') break;
						if($item['code'.chr(65+$j)] == $GLOBALS['code'.chr(65+$j)]) $sel = 'selected';
						else $sel = '';
					}
				}

				if($sel == 'selected'){
					$str .= '<option value="'.$item['code'.chr(65+$i)].'" selected>'.$item['code_name'].'</option>';
					$sel = 'ok';
				}else{
					$str .= '<option value="'.$item['code'.chr(65+$i)].'" >'.$item['code_name'].'</option>';
				}
			}
			$str .= '</select>';
			array_push($naviitem,$str);
		}
		if($getsub) break;
	}
	return implode('&nbsp;<span style=\'color:'.$color1.';\'>&gt;</span>',$naviitem);
}


//�˻�Ű����
$search_keyword = $_REQUEST["search_keyword"];

$_cdata="";
$sql = "SELECT * FROM tblproductcode WHERE codeA='".$codeA."' AND codeB='".$codeB."' ";
$sql.= "AND codeC='".$codeC."' AND codeD='".$codeD."' ";
$result=mysql_query($sql,get_db_conn());
if($row=mysql_fetch_object($result)) {
	//���ٰ��ɱ��ѱ׷� üũ
	if($row->group_code=="NO") {
		echo "<html></head><body onload=\"location.href='".$Dir.MainDir."main.php'\"></body></html>";exit;
	}
	if(strlen($_ShopInfo->getMemid())==0) {
		if(strlen($row->group_code)>0) {
			echo "<html></head><body onload=\"location.href='".$Dir.FrontDir."login.php?chUrl=".getUrl()."'\"></body></html>";exit;
		}
	} else {
		//if($row->group_code!="ALL" && strlen($row->group_code)>0 && $row->group_code!=$_ShopInfo->getMemgroup()) {
		if(strlen($row->group_code)>0 && strpos($row->group_code,$_ShopInfo->getMemgroup())===false) {	//�׷�ȸ���� ����
			echo "<html></head><body onload=\"alert('�ش� ī�װ� ���ٱ����� �����ϴ�.');location.href='".$Dir.MainDir."main.php'\"></body></html>";exit;
		}
	}
	$_cdata=$row;

	// �̸�����
	if( @!preg_match( 'U', $_cdata->list_type ) AND $preview===true ) {
		$_cdata->list_type = $_cdata->list_type."U";
	}

} else {
	echo "<html></head><body onload=\"location.href='".$Dir.MainDir."main.php'\"></body></html>";exit;
}
mysql_free_result($result);


$sort=$_REQUEST["sort"];
$listnum=(int)$_REQUEST["listnum"];
//_pr($_data);
//if($listnum<=0) $listnum=$_data->prlist_num;
if($listnum<=48) $listnum=48;


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

$sql = "SELECT codeA, codeB, codeC, codeD FROM tblproductcode ";
if(strlen($_ShopInfo->getMemid())==0) {
	$sql.= "WHERE group_code!='' ";
} else {
	//$sql.= "WHERE group_code!='".$_ShopInfo->getMemgroup()."' AND group_code!='ALL' AND group_code!='' ";
	$sql.= "WHERE group_code NOT LIKE '%".$_ShopInfo->getMemgroup()."%' AND group_code!='' ";
}
$result=mysql_query($sql,get_db_conn());
$not_qry="";
while($row=mysql_fetch_object($result)) {
	$tmpcode=$row->codeA;
	if($row->codeB!="000") $tmpcode.=$row->codeB;
	if($row->codeC!="000") $tmpcode.=$row->codeC;
	if($row->codeD!="000") $tmpcode.=$row->codeD;
	$not_qry.= "AND a.productcode NOT LIKE '".$tmpcode."%' ";
}
mysql_free_result($result);


$qry = "WHERE 1=1 ";
if(eregi("T",$_cdata->type)) {	//����з�
	$sql = "SELECT productcode FROM tblproducttheme WHERE code LIKE '".$likecode."%' ";
	if(strlen($_cdata->sort)==0 || $_cdata->sort=="date" || $_cdata->sort=="date2") {
		$sql.= "ORDER BY date DESC ";
	}
	$result=mysql_query($sql,get_db_conn());
	$t_prcode="";
	while($row=mysql_fetch_object($result)) {
		$t_prcode.=$row->productcode.",";
		$i++;
	}
	mysql_free_result($result);

	//�߰� ī�װ��� �ִ��� üũ
	$sql = "SELECT productcode FROM tblcategorycode WHERE categorycode LIKE '".$likecode."%' ";
	$result=mysql_query($sql,get_db_conn());
	while($row=mysql_fetch_object($result)) {
		$t_prcode.=$row->productcode.",";
		$i++;
	}
	mysql_free_result($result);
	//# �߰� ī�װ��� �ִ��� üũ

	$t_prcode=substr($t_prcode,0,-1);
	$t_prcode=ereg_replace(',','\',\'',$t_prcode);
	$qry.= "AND a.productcode IN ('".$t_prcode."') ";

	$add_query="&code=".$code;
} else {	//�Ϲݺз�
	//$qry.= "AND a.productcode LIKE '".$likecode."%' ";

	//�߰� ī�װ��� �ִ��� üũ
	/*
	$sql = "SELECT productcode FROM tblcategorycode WHERE categorycode LIKE '".$likecode."%' ";

	$result=mysql_query($sql,get_db_conn());
	$prcode="";
	while($row=mysql_fetch_object($result)) {
		$prcode.=$row->productcode.",";
		$i++;
	}
	mysql_free_result($result);
	$prcode=substr($prcode,0,-1);
	$prcode=ereg_replace(',','\',\'',$prcode);
	$qry.= "AND a.productcode IN ('".$prcode."') ";
	$add_query="&code=".$code;*/
	$qry.= "AND cc.categorycode LIKE '".$likecode."%' ";
//	echo $qry;
	$add_query="&code=".$code;
}
$qry.="AND a.display='Y' ";

if(!isset($_REQUEST['getrental']) || $_REQUEST['getrental'] != '-1' )  $_REQUEST['getrental'] = '1';
if(!isset($_REQUEST['getproduct']) || $_REQUEST['getproduct'] != '-1')  $_REQUEST['getproduct'] = '1';

if($_REQUEST['getrental'] == '-1'){
	$qry.="AND a.rental!='2' ";
}
if($_REQUEST['getproduct'] == '-1'){
	$qry.="AND a.rental='2' ";
}

//echo $qry;

//������ġ
$codenavi=getCodeLoc($code);


?>
<!DOCTYPE HTML>

<HTML>
<HEAD>
<!--<TITLE><?=$_data->shopname." [".$_cdata->code_name."]"?></TITLE>-->
<TITLE><?=$_data->shoptitle?></TITLE>
<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">
<META http-equiv="X-UA-Compatible" content="IE=Edge" />

<META name="description" content="<?=(strlen($_data->shopdescription)>0?$_data->shopdescription:$_data->shoptitle)?>">
<META name="keywords" content="<?=$_data->shopkeyword?>">

<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
<script type="text/javascript" src="<?=$Dir?>lib/drag.js.php"></script>

<?include($Dir."lib/style.php")?>
<SCRIPT LANGUAGE="JavaScript">
<!--
function ClipCopy(url) {
	var tmp;
	tmp = window.clipboardData.setData('Text', url);
	if(tmp) {
		alert('�ּҰ� ����Ǿ����ϴ�.');
	}
}

function ChangeSort(val) {
	document.form2.block.value="";
	document.form2.gotopage.value="";
	document.form2.sort.value=val;
	document.form2.submit();
}

function ChangeListnum(val) {
	document.form2.block.value="";
	document.form2.gotopage.value="";
	document.form2.listnum.value=val;
	document.form2.submit();
}

function GoPage(block,gotopage) {
	document.form2.block.value=block;
	document.form2.gotopage.value=gotopage;
	document.form2.submit();
}

function ChangeNum(obj) {
	document.form2.listnum.value=obj.value;
	document.form2.submit();
}

function chgNaviCode(dp){
	var code = '';
	dp = parseInt(dp);
	if(dp > 4) dp = 4
	for(i=0;i<=dp;i++){
		var el = document.getElementById('code'+String.fromCharCode(65+i));
		if(el){
			code += el.options[el.selectedIndex].value;
		}else{
			break;
		}
	}
	document.codeNaviForm.code.value = code;
	document.codeNaviForm.submit();
}
//-->
</SCRIPT>
</HEAD>

<body<?=(substr($_data->layoutdata["MOUSEKEY"],0,1)=="Y"?" oncontextmenu=\"return false;\"":"")?><?=(substr($_data->layoutdata["MOUSEKEY"],1,1)=="Y"?" ondragstart=\"return false;\" onselectstart=\"return false;\"":"")?> leftmargin="0" marginwidth="0" topmargin="0" marginheight="0"><?=(substr($_data->layoutdata["MOUSEKEY"],2,1)=="Y"?"<meta http-equiv=\"ImageToolbar\" content=\"No\">":"")?>

<? 
include ($Dir.MainDir.$_data->menu_type.".php");
?>

<table border=0 cellpadding=0 cellspacing=0 width=100%>
<tr>
	<td>
<?
	if(false && strlen($_cdata->list_type)==5) {
		echo $Dir.TempletDir."product/list_".$_cdata->list_type.".php";
	} else if (true || strlen($_cdata->list_type)==6 && substr($_cdata->list_type,5,6)=="U") {
		//leftmenu : ���뿩��
		$tmp = categorySubTab($code);
		$_ndata = NULL;
		do{
			$chkcode = '';
			for($i=0;$i<4;$i++) $chkcode .= ($i < $tmp['depth'])?$tmp['code'.chr(65+$i)]:'000';
			if($tmp['depth'] == 0){
				$sql = "SELECT leftmenu,body,code FROM ".$designnewpageTables." WHERE type='prlist' AND (code='".$chkcode."' OR code='ALL') AND leftmenu='Y' ORDER BY code ASC LIMIT 1 ";
				$result=mysql_query($sql,get_db_conn());
			}else{
				//$sql = "SELECT leftmenu,body,code FROM ".$designnewpageTables." WHERE type='prlist' AND (code='".$chkcode."' OR code='ALL') AND leftmenu='Y' ORDER BY code ASC LIMIT 1 ";
				$sql = "SELECT leftmenu,body,code FROM ".$designnewpageTables." WHERE type='prlist' AND (code='".$chkcode."') AND leftmenu='Y' ORDER BY code ASC LIMIT 1 ";
				$result=mysql_query($sql,get_db_conn());
			}
		
			if(mysql_num_rows($result)){
				$_ndata=mysql_fetch_object($result);
			}else{
				if($tmp['depth'] == 0) break;
				$csql = "select dsameparent from tblproductcode where codeA='".$tmp['codeA']."' and codeB='".$tmp['codeB']."' and codeC='".$tmp['codeC']."' and codeD='".$tmp['codeD']."' limit 1";				
				$cresult = mysql_query($csql);
				if($cresult && mysql_num_rows($cresult) && mysql_result($cresult,0,0) == '1'){
					$tmp['depth'] -= 1;
					$tmp['code'.chr(65+$tmp['depth'])] = '000';
					continue;
				}
				$tmp['depth'] = 0;
			}
		}while(empty($_ndata) && $tmp['depth'] >= 0);
		mysql_free_result($result);
		if($_ndata) {
			$body=$_ndata->body;
			$body=str_replace("[DIR]",$Dir,$body);
			include($Dir.TempletDir."product/list_U.php");
		} else {
			include($Dir.TempletDir."product/list_".substr($_cdata->list_type,0,5).".php");
		}
	}
?>
	</td>
</tr>
</table>
<form name=form2 method=get action="<?=$_SERVER[PHP_SELF]?>">
<input type=hidden name=code value="<?=$rcode?>">
<input type=hidden name=listnum value="<?=$listnum?>">
<input type=hidden name=sort value="<?=$sort?>">
<input type=hidden name=block value="<?=$block?>">
<input type=hidden name=gotopage value="<?=$gotopage?>">
<input type="hidden" name="getrental" value="<?=$_REQUEST['getrantal']?>" />
<input type="hidden" name="getproduct" value="<?=$_REQUEST['getproduct']?>" />
<input type="hidden" name="search_keyword" value="<?=$search_keyword?>" />
<input type="hidden" name="search_price_s" value="<?=$search_price_s?>" />
<input type="hidden" name="search_price_e" value="<?=$search_price_e?>" />
</form>

<form name="codeNaviForm" id="codeNaviForm" action="<?=$_SERVER['PHP_SELF']?>">
<input type="hidden" name="code" value="">
</form>
<script language="javascript" type="text/javascript">
$j(function(){
	if($j('#ptypeRental')){
		<? if($_REQUEST['getrental'] != '-1'){ ?>
		$j('#ptypeRental').attr('checked',true)
		<? } ?>
	
	}
	
	if($j('#ptypeSell')){
		<? if($_REQUEST['getproduct'] != '-1'){ ?>
		$j('#ptypeSell').attr('checked',true)
		<? } ?>
	}
	
	$j('#ptypeRental,#ptypeSell').on('click',function(){selProducttype(); });

});

//�˻�Ű����
$j('.keyword input[type=checkbox]').click(function() {
	var kidx = '';
	$j('.keyword input[type=checkbox]:checked').each(function (i) {
		if(kidx != '') { kidx += ','; }
		if(this.checked){ kidx += $j(this).val(); }
	});
	$j('form[name="form2"] input[name=search_keyword]').val(kidx);
	$j('form[name="form2"]').submit();
});

$j('.btn_search_price').click(function() {
	var sprice = $j(".keyword input[name=search_price_s]").val();
	var eprice = $j(".keyword input[name=search_price_e]").val();
	$j('form[name="form2"] input[name=search_price_s]').val(sprice);
	$j('form[name="form2"] input[name=search_price_e]').val(eprice);
	$j('form[name="form2"]').submit();
});

$j(document).on("click", "#icon_more", function(){
	var thistitle = $j(this).attr("title");
	if($j("."+thistitle).css('display')=="none"){
		$j("."+thistitle).show();
	}else{
		$j("."+thistitle).hide();
	}
});
//�˻�Ű����

function selProducttype(){
	if($j('#ptypeRental') && !$j('#ptypeRental').prop('checked')){
		document.form2.getrental.value = '-1';
	}else{
		document.form2.getrental.value = '1';
	}
	if($j('#ptypeSell') && !$j('#ptypeSell').prop('checked')){
		document.form2.getproduct.value = '-1';
	}else{
		document.form2.getproduct.value = '1';
	}
	document.form2.submit();
}
</script>
<script src="/js/product_option.js"></script>


<? include ($Dir."lib/bottom.php") ?>

<div id="create_openwin" class="viewPopup" style="display:none"></div>

<div id="wishlist" class="wishPopup" style="display:none;"></div>
<div id="basketlist" class="basketPopup" style="display:none;"></div>	

</BODY>
</HTML>

<? if($HTML_CACHE_EVENT=="OK") ob_end_flush(); ?>