<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");

$sellvidx=$_REQUEST["sellvidx"];

$_MiniLib=new _MiniLib($sellvidx);
$_MiniLib->_MiniInit();

if(!$_MiniLib->isVender) {
	Header("Location:".$Dir.MainDir."main.php");
	exit;
}
$_minidata=$_MiniLib->getMiniData();

$code=$_REQUEST["code"];
$code2=$_REQUEST["code2"];
$tgbn="10";


if($code){
	$code2 = $code;
}

if($code2){
	$code2 = str_pad($code2, 12, "0", STR_PAD_RIGHT);
	$code = $code2;
}



if(strlen($code)==0) {
	$code="000000";
}
$codeA=substr($code,0,3);
$codeB=substr($code,3,3);
$codeC=substr($code,6,3);
$codeD=substr($code,9,3);
if(strlen($codeA)!=3) $codeA="000";
if(strlen($codeB)!=3) $codeB="000";

if($codeA!="000") $likecode.=$codeA;
if($codeB!="000") $likecode.=$codeB;
if($codeC!="000") $likecode.=$codeC;
if($codeD!="000") $likecode.=$codeD;


$code2A=substr($code2,0,3);
$code2B=substr($code2,3,3);
$code2C=substr($code2,6,3);
$code2D=substr($code2,9,3);
if(strlen($code2A)!=3) $code2A="000";
if(strlen($code2B)!=3) $code2B="000";
if(strlen($code2C)!=3) $code2C="000";
if(strlen($code2D)!=3) $code2D="000";


$_MiniLib->getCode($tgbn,$code);
$_MiniLib->getThemecode();

$sort=$_REQUEST["sort"];
$listnum=(int)$_REQUEST["listnum"];
$pageid=$_REQUEST["pageid"];
if(!preg_match("/^(I|D|L)$/",$pageid)) $pageid=$_minidata->prlist_display;

if($listnum<= 20) $listnum=20;

//리스트 세팅
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

$iscode=true;
if(substr($code,0,3)=="000") {
	$thiscodename="미니샵";
	$thiscodecnt=$_MiniLib->codecnt["000"];
} else {
	if(strlen($_MiniLib->codename[$code])==0) $iscode=false;

	$thiscodename=$_MiniLib->codename[$code];
	//echo $code;

	$codeBsel=substr($code,3,3);
	$codeCsel=substr($code,6,3);
	$codeDsel=substr($code,9,3);

	if($codeBsel == "000"){
		$ni = 1;
		$top_urllink = "&nbsp;&nbsp;<a href=\"javascript:GoPrSection('')\">▲</a>";
	}else if($codeCsel == "000"){
		$ni = 2;
		$top_urllink = "&nbsp;&nbsp;<a href=\"javascript:GoPrSection('".substr($code,0,3)."000000000')\">▲</a>";
	}else if($codeDsel == "000"){
		$ni = 3;
		$top_urllink = "&nbsp;&nbsp;<a href=\"javascript:GoPrSection('".substr($code,0,6)."000000')\">▲</a>";
	}else{
		$ni = 4;
		$top_urllink = "&nbsp;&nbsp;<a href=\"javascript:GoPrSection('".substr($code,0,9)."000')\">▲</a>";
	}
	$thiscodecnt=$_MiniLib->codecnt[substr($code,0,3*$ni)];
}

if($iscode==false) {
	//수정
	Header("Location:".$Dir.FrontDir."minishop.php?sellvidx=".$sellvidx);
	exit;
}


function getCodeLoc($code2,$color1="9E9E9E",$color2="9E9E9E") {
	global $_ShopInfo, $Dir,$code2, $sellvidx;
	$naviitem = array();
	array_push($naviitem,"<A HREF=\"".$Dir."front/minishop.php?sellvidx=".$sellvidx."\"><span style=\"color:".$color1.";\">홈</span></A>&nbsp;");
		
	for($i=0;$i<4;$i++){
		$tmp = array();
		
		$getsub = ($GLOBALS['code2'.chr(65+$i)] == '000');
		$tmp = getCategoryItems_vender(substr($code2,0,$i*3),true, $sellvidx);
		if(is_array($tmp) && count($tmp) > 0 && count($tmp['items']) > 0){
			$str = '&nbsp;<select name="code2'.chr(65+$i).'"  id="code2'.chr(65+$i).'" onChange="javascript:chgNaviCode('.$i.')">';
			if($tmp['depth'] != $i){
				exit('System Error');
			}
			$str .= '<option value="">전체</option>';
			$sel = "";
			foreach($tmp['items'] as $item){
				if($sel != 'ok'){
					for($j=0;$j<=$i;$j++){
						if($j >0 && $sel != 'selected') break;
					
						if($item['code'.chr(65+$j)] == $GLOBALS['code2'.chr(65+$j)]) $sel = 'selected';
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


$strlocation="<A HREF=\"http://".$_ShopInfo->getShopurl()."\">홈</A> > <A HREF=\"http://".$_ShopInfo->getShopurl().FrontDir."minishop.php?sellvidx=".$_minidata->vender."\"><B>".$_minidata->brand_name."</B></A>";

$tag_0_count = "3";
$tag_1_count = "3";
$tag_2_count = "5";


//현재위치
$codenavi=getCodeLoc($code2);
?>

<HTML>
<HEAD>
<TITLE><?=$_data->shoptitle?></TITLE>
<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">

<META name="description" content="<?=(strlen($_data->shopdescription)>0?$_data->shopdescription:$_data->shoptitle)?>">
<META name="keywords" content="<?=$_data->shopkeyword?>">
<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
<script type="text/javascript" src="<?=$Dir?>lib/DropDown.js.php"></script>
<script type="text/javascript" src="<?=$Dir?>lib/minishop.js.php"></script>
<? include($Dir."lib/style.php")?>
<SCRIPT LANGUAGE="JavaScript">
<!--


function GoPrSection(code) {
	document.prfrm.code.value=code;
	document.prfrm.block.value="";
	document.prfrm.gotopage.value="";
	document.prfrm.submit();
}

function ChangeSort(val) {
	document.prfrm.block.value="";
	document.prfrm.gotopage.value="";
	document.prfrm.sort.value=val;
	document.prfrm.submit();
}

function ChangeListnum(val) {
	document.prfrm.block.value="";
	document.prfrm.gotopage.value="";
	document.prfrm.listnum.value=val;
	document.prfrm.submit();
}

function ChangeDisplayType(pageid) {
	document.prfrm.pageid.value=pageid;
	
	if(pageid=="I") {
		document.all["btn_displayI"].src="/images/minishop/ico_img_on.gif";
		//document.all["btn_displayD"].style.fontWeight="";
		document.all["btn_displayL"].src="/images/minishop/ico_list.gif";

		document.all["layer_displayI"].style.display="block";
		//document.all["layer_displayD"].style.display="none";
		document.all["layer_displayL"].style.display="none";
	/*
	} else if(pageid=="D") {
		document.all["btn_displayI"].style.fontWeight="";
		//document.all["btn_displayD"].style.fontWeight="bold";
		document.all["btn_displayL"].style.fontWeight="";

		document.all["layer_displayI"].style.display="none";
		//document.all["layer_displayD"].style.display="block";
		document.all["layer_displayL"].style.display="none";
	*/
	} else if(pageid=="L") {
		document.all["btn_displayI"].src="/images/minishop/ico_img.gif";
		//document.all["btn_displayD"].style.fontWeight="";
		document.all["btn_displayL"].src="/images/minishop/ico_list_on.gif";

		document.all["layer_displayI"].style.display="none";
		//document.all["layer_displayD"].style.display="none";
		document.all["layer_displayL"].style.display="block";
	}
}

function GoPage(block,gotopage) {
	document.prfrm.block.value=block;
	document.prfrm.gotopage.value=gotopage;
	document.prfrm.submit();
}

function chgNaviCode(dp){
	var code = '';
	dp = parseInt(dp);
	if(dp > 4) dp = 4
	for(i=0;i<=dp;i++){
		var el = document.getElementById('code2'+String.fromCharCode(65+i));
		if(el){
			code += el.options[el.selectedIndex].value;
		}else{
			break;
		}
	}
	document.codeNaviForm.code2.value = code;
	document.codeNaviForm.submit();
}

//-->
</SCRIPT>
</HEAD>

<body<?=(substr($_data->layoutdata["MOUSEKEY"],0,1)=="Y"?" oncontextmenu=\"return false;\"":"")?><?=(substr($_data->layoutdata["MOUSEKEY"],1,1)=="Y"?" ondragstart=\"return false;\" onselectstart=\"return false;\"":"")?> leftmargin="0" marginwidth="0" topmargin="0" marginheight="0"><?=(substr($_data->layoutdata["MOUSEKEY"],2,1)=="Y"?"<meta http-equiv=\"ImageToolbar\" content=\"No\">":"")?>


<? include ($Dir."lib/menu_minishop_v2.php") ?>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
	<td align="center">

	<!-- 메인화면 상단 자유디자인 -->
<?
	if($_minidata->main_toptype=="image") {
		if(file_exists($Dir.DataDir."shopimages/vender/MAIN_".$_minidata->vender.".gif")) {
			echo "<table width=100% border=0 cellpadding=0 cellspacing=0>\n";
			echo "<tr>\n";
			echo "	<td align=center><img src=\"".$Dir.DataDir."shopimages/vender/MAIN_".$_minidata->vender.".gif\" border=0 align=absmiddle></td>\n";
			echo "</tr>\n";
			echo "<tr><td height=5></td></tr>\n";
			echo "</table>\n";
		}
	} else if($_minidata->main_toptype=="html") {
		if(strlen($_minidata->main_topdesign)>0) {
			echo "<table width=100% border=0 cellpadding=0 cellspacing=0>\n";
			echo "<tr>\n";
			echo "	<td align=center>";
			if (strpos(strtolower($_minidata->main_topdesign),"<table")!=false)
				echo $_minidata->main_topdesign;
			else
				echo ereg_replace("\n","<br>",$_minidata->main_topdesign);
			echo "	</td>\n";
			echo "</tr>\n";
			echo "<tr><td height=5></td></tr>\n";
			echo "</table>\n";
		}
	}
?>

	<!-- HOT 추천상품 -->
<?
	if($_minidata->hot_used=="1") {
		unset($hot_disptype);
		unset($hot_dispcnt);
		unset($hot_prcode);
		unset($specialprlist);
		$sql = "SELECT disptype, dispcnt FROM tblvendersectdisplist WHERE seq='".$_minidata->hot_dispseq."' ";

		
		$result=mysql_query($sql,get_db_conn());
		if($row=mysql_fetch_object($result)) {
			$hot_disptype=$row->disptype;
			$hot_dispcnt=$row->dispcnt;
		}
		mysql_free_result($result);
		if(strlen($hot_disptype)>0 && $hot_dispcnt>0) {
			$sql = "SELECT a.* FROM tblproduct AS a ";
			$sql.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
			$sql.= "LEFT JOIN rent_product rp on a.pridx=rp.pridx ";
			$sql.= "WHERE 1=1 ";
			if($_minidata->hot_linktype=="2") {
				$sql2 = "SELECT special_list FROM tblvenderspecialmain WHERE vender='".$_minidata->vender."' AND special='3' ";
				$result2=mysql_query($sql2,get_db_conn());
				if($row2=mysql_fetch_object($result2)) {
					$hot_prcode=ereg_replace(',','\',\'',$row2->special_list);
				}
				mysql_free_result($result2);
				if(strlen($hot_prcode)>0) {
					$sql.= "AND a.productcode IN ('".$hot_prcode."') ";
				} else {
					$isnot_hotspecial=true;
				}
			}
			//$sql.= "AND a.vender='".$_minidata->vender."' AND a.display='Y' ";

			$sql.= "AND (a.vender='".$_minidata->vender."' and (rp.trust_vender is NULL or rp.trust_vender='0')) ";
			$sql.= "OR (rp.trust_vender='".$_minidata->vender."' AND rp.trust_vender<>a.vender AND rp.trust_approve='Y') ";
			$sql.= "AND a.display='Y' "; 
			//$sql.= "WHERE (a.vender='".$_minidata->vender."' and rp.trust_vender='".$_minidata->vender."') AND a.display='Y' ";


			$sql.= "AND (a.group_check='N' OR b.group_code='".$_ShopInfo->getMemgroup()."') ";
			if($_minidata->hot_linktype=="1" || $isnot_hotspecial==true) {
				$sql.= "ORDER BY a.sellcount DESC ";
			} else if($_minidata->hot_linktype=="2") {
				$sql.= "ORDER BY FIELD(a.productcode,'".$hot_prcode."') ";
			}
			$sql.= "LIMIT ".$hot_dispcnt." ";
		
			$result=mysql_query($sql,get_db_conn());

			/*
			$yy=1;
			while($row=mysql_fetch_object($result)) {
				$specialprlist[$yy]=$row;
				$yy++;
			}
//			mysql_free_result($result);
		}
		if(count($specialprlist)>0) { */
			echo "<table width=100% border=0 cellspacing=0 cellpadding=0>\n";
			echo "<tr>\n";
			echo "	<td background=\"".$Dir."images/minishop/title_hot_bg.gif\" style=\"padding-left:10\" height=\"25\"><img src=\"".$Dir."images/minishop/title_hot.gif\" border=0></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td height=10></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td valign=top>\n";
			
			
			
			//include ($Dir.TempletDir."minisect/".$hot_disptype.".php");
			
			$innerpub = file_get_contents($Dir.'newUI/prlist_category.html');	
			
			$pos = strlen($innerpub);
			if(false !== $pos = strpos($innerpub,'<!-- items -->')){
				if(false === $epos = strpos($innerpub,'<!-- /items -->')) $epos = strlen($innerpub);			
				$conts['items'] = substr($innerpub,$pos+strlen('<!-- items -->'),$epos-$pos-strlen('<!-- items -->'));
			}
			$conts['head'] = substr($innerpub,0,$pos);
			$conts['bott'] = substr($innerpub,$epos);			
			$conts['cont'] = '';
			$conts = str_replace('__ID__','TOP_HOT',$conts);	
			$conts = str_replace('__WIDTH__','225px',$conts);
			$conts = str_replace('__HEIGHT__','225px',$conts);
			if(mysql_num_rows($result)){
				$i=0;
				while($row=mysql_fetch_assoc($result)) {		
				
					$itemtxt = $conts['items'];	
					$row = solvResultforNewUi($row);	
					$row['listfinal'] = (++$i%5==0)?'endItem':'';					
					foreach($row as $k=>$v){
						$itemtxt = str_replace('product.'.$k,$v,$itemtxt);
					}
					$conts['cont'] .= $itemtxt;		
				}
			}
			echo $conts['head'].$conts['cont'].$conts['bott'];
			
			
			echo "	</td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td height=15></td>\n";
			echo "</tr>\n";
			echo "</table>\n";
		}
	}
?>

	<!-- NEW 신상품 -->
<?
	if($_minidata->new_used=="1") {
		unset($new_disptype);
		unset($new_dispcnt);
		unset($specialprlist);
		$sql = "SELECT disptype, dispcnt FROM tblvendersectdisplist WHERE seq='".$_minidata->new_dispseq."' ";
		$result=mysql_query($sql,get_db_conn());
		if($row=mysql_fetch_object($result)) {
			$new_disptype=$row->disptype;
			$new_dispcnt=$row->dispcnt;
		}
		mysql_free_result($result);
		if(strlen($new_disptype)>0 && $new_dispcnt>0) {
			$sql = "SELECT a.* FROM tblproduct AS a ";
			$sql.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
			$sql.= "LEFT JOIN rent_product rp on a.pridx=rp.pridx ";
			$sql.= "WHERE (a.vender='".$_minidata->vender."' and (rp.trust_vender is NULL or rp.trust_vender='0')) ";
			$sql.= "or (rp.trust_vender='".$_minidata->vender."' AND rp.trust_vender<>a.vender AND rp.trust_approve='Y') ";
			$sql.= "AND a.display='Y' ";
			//$sql.= "WHERE (a.vender='".$_minidata->vender."' and rp.trust_vender='".$_minidata->vender."') AND a.display='Y' ";
			$sql.= "AND (a.group_check='N' OR b.group_code='".$_ShopInfo->getMemgroup()."') ";
			$sql.= "ORDER BY a.regdate DESC ";
			$sql.= "LIMIT ".$new_dispcnt." ";
			

			$result=mysql_query($sql,get_db_conn());
			/*
			$yy=1;
			while($row=mysql_fetch_object($result)) {
				$specialprlist[$yy]=$row;
				$yy++;
			}
			mysql_free_result($result);
		}
		if(count($specialprlist)>0) { */
			echo "<table width=100% border=0 cellspacing=0 cellpadding=0>\n";
			echo "<tr>\n";
			echo "	<td height=10></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td  background=\"".$Dir."images/minishop/title_new_bg.gif\"  style=\"padding-left:10\" height=\"25\"><img src=\"".$Dir."images/minishop/title_new.gif\" border=0></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td height=10></td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td valign=top>\n";
		//	include ($Dir.TempletDir."minisect/".$new_disptype.".php");
		$innerpub = file_get_contents($Dir.'newUI/prlist_category.html');	
			
			$pos = strlen($innerpub);
			if(false !== $pos = strpos($innerpub,'<!-- items -->')){
				if(false === $epos = strpos($innerpub,'<!-- /items -->')) $epos = strlen($innerpub);			
				$conts['items'] = substr($innerpub,$pos+strlen('<!-- items -->'),$epos-$pos-strlen('<!-- items -->'));
			}
			$conts['head'] = substr($innerpub,0,$pos);
			$conts['bott'] = substr($innerpub,$epos);			
			$conts['cont'] = '';
			$conts = str_replace('__ID__','TOP_NEW',$conts);
			$conts = str_replace('__WIDTH__','225px',$conts);
			$conts = str_replace('__HEIGHT__','225px',$conts);
			
			if(mysql_num_rows($result)){
				$i=0;
				while($row=mysql_fetch_assoc($result)) {
					$itemtxt = $conts['items'];	
					$row = solvResultforNewUi($row);	
					$row['listfinal'] = (++$i%4==0)?'endItem':'';					
					foreach($row as $k=>$v){
						$itemtxt = str_replace('product.'.$k,$v,$itemtxt);
					}
					$conts['cont'] .= $itemtxt;		
				}
			}
			echo $conts['head'].$conts['cont'].$conts['bott'];
			
			
			echo "	</td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td height=15></td>\n";
			echo "</tr>\n";
			echo "</table>\n";
		}
	}
	?>

	<table border="0" cellpadding="0" cellspacing="0" style="float: left;">
	<tr>
		<td style="padding-right:5px;"><?=$codenavi?></td>
	</tr>
	</table>

	<?if($_minidata->code_distype!="NY"){?>
	<table border=0 cellpadding=0 cellspacing=0 width=100%>
		<tr height=37>
			<td style="padding-left:5px"><span style="color:#000000;font-weight:bold;font-size:15px;"><?=$thiscodename?>(<?=(int)$thiscodecnt?>) <?=$top_urllink?></span></td>
		</tr>
		<tr>
			<td>
				<div style="margin-bottom:20px;background:url('/data/design/img/sub/bg_boxline2.gif') repeat-y;">
					<div style="height:6px;background:url('/data/design/img/sub/top_boxline2.gif') no-repeat;font-size:0px;"></div>
					<!-- 3차 카테고리 목록 START -->
					<table border="0" cellpadding="0" cellspacing="0" width="98%" align="center" style="margin:10px auto;">
						<tr>
							<td>
									<?
										if(substr($code,0,3)=="000"){
											if(substr($code,0,3)=="000") echo "<b>";
											echo "<a href=\"javascript:GoPrSection('')\">전체[".(int)$_MiniLib->codecnt["000"]."]</b></a>";
											for($i=0;$i<count($prdataA);$i++) {
												$tmpcode=$prdataA[$i]->codeA.$prdataA[$i]->codeB.$prdataA[$i]->codeC.$prdataA[$i]->codeD;
												echo "<span style=\"padding:0px 10px;color:#cccccc;font-size:11px;\">|</span>";
												if($code==$tmpcode) echo "<b>";
												echo "<a href=\"javascript:GoPrSection('".$tmpcode."')\">".$_MiniLib->codename[$tmpcode]."[".(int)$_MiniLib->codecnt[substr($tmpcode,0,3)]."]</A></b>";
											}
										}else{
												$codeBsel=substr($code,3,3);
												$codeCsel=substr($code,6,3);
												$codeDsel=substr($code,9,3);

												if($codeBsel == "000"){
													$ni = 1;
													$ni2 = 2;
												}else if($codeCsel == "000"){
													$ni = 2;
													$ni2 = 3;
												}else if($codeDsel == "000"){
													$ni = 3;
													$ni2 = 4;
												}else{
													$ni = 3;
													$ni2 = 5;
												}
												
												$a_line = 0;
												if(substr($code,3*$ni,3)=="000"){
													echo "<b>";
													echo "<a href=\"javascript:GoPrSection('".substr($code,0,3*$ni)."')\">".$_MiniLib->codename[$code]."[".(int)$_MiniLib->codecnt[substr($code,0,3*$ni)]."]</b></a>";
													$a_line = 1;
												}

												unset($strprdata);
												for($j=0;$j<count($prdataB[substr($code,0,3*$ni)]);$j++) {

													$tmpcode=$prdataB[substr($code,0,3*$ni)][$j]->codeA.$prdataB[substr($code,0,3*$ni)][$j]->codeB.$prdataB[substr($code,0,3*$ni)][$j]->codeC.$prdataB[substr($code,0,3*$ni)][$j]->codeD;
													
													if($a_line == "1"){
														$strprdata.= "<span style=\"padding:0px 10px;color:#cccccc;font-size:11px;\">|</span>";
													}else{
														if($j != '0'){
															$strprdata.= "<span style=\"padding:0px 10px;color:#cccccc;font-size:11px;\">|</span>";
														}
													}
													if($tgbn!="10" || $code!=$tmpcode) {
														if($prdataB[substr($code,0,3*$ni)][$j]->codeC=='000'){ //2차 카테고리
															$strprdata.="<A HREF=\"javascript:GoPrSection('".$tmpcode."')\">".$prdataB[substr($code,0,3*$ni)][$j]->code_name."[".(int)$_MiniLib->codecnt[substr($tmpcode,0,3*$ni2)]."]</A>";
														}else{ //4차 카테고리
															$strprdata.="<A HREF=\"javascript:GoPrSection('".$tmpcode."')\">".$prdataB[substr($code,0,3*$ni)][$j]->code_name."[".(int)$_MiniLib->codecnt[substr($tmpcode,0,3*$ni2)]."]</A>";
														}
													} else {
														$strprdata.="<span style=\"text-decoration:underline\"><b>".$prdataB[substr($code,0,3*$ni)][$j]->code_name."[".(int)$_MiniLib->codecnt[substr($tmpcode,0,3*$ni2)]."]</b></span>";
													}
												}
											echo $strprdata;
										}
									?>
							</td>
						</tr>
					</table>
					<!-- 3차 카테고리 목록 END -->
					<div style="height:6px;background:url('/data/design/img/sub/bot_boxline2.gif') no-repeat;font-size:0px;"></div>
				</div>
			</td>
		</tr>
	</table>
	<?}?>

	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<colgroup>
			<col width="400">
			<col width="">
			<col width="58">
		</colgroup>
	<tr>
		<td style="padding:4px;">
			<?
				$sortHit=""; $sortLowPrice=""; $sortHiPrice=""; $sortNew="";
				if($sort == 'ord_qty' || !$sort){
					$sortHit = 'On';
				}else if($sort == 'lprice'){
					$sortLowPrice = 'On';
				}else if($sort == 'hprice'){
					$sortHiPrice = 'On';
				}else if($sort == 'cdate'){
					$sortNew = 'On';
				}
			?>
			<style>
				.sort{background:none;}
				.sortOn{padding-left:12px;background:url('/images/minishop/ico_sort_on.gif') no-repeat;background-position:0% 4px;}
				.sortLine{padding:0px 10px;color:#cccccc;font-size:10px;}
			</style>

			<a href="javascript:ChangeSort('ord_qty');"><span class="sort<?=$sortHit?>">인기순</span><!--<img src="<?=$Dir?>images/minishop/btn_sort_popular.gif" border=0 alt="인기상품순" align="absmiddle">--></a><span class="sortLine">|</span><a href="javascript:ChangeSort('lprice');"><span class="sort<?=$sortLowPrice?>">낮은 가격순</span><!--<img src="<?=$Dir?>images/minishop/btn_sort_low.gif" border=0 alt="낮은가격순" align="absmiddle">--></a><span class="sortLine">|</span><a href="javascript:ChangeSort('hprice');"><span class="sort<?=$sortHiPrice?>">높은 가격순</span><!--<img src="<?=$Dir?>images/minishop/btn_sort_high.gif" border=0 alt="높은가격순" align="absmiddle">--></a><span class="sortLine">|</span><a href="javascript:ChangeSort('cdate');"><span class="sort<?=$sortNew?>">등록일순<!--<img src="<?=$Dir?>images/minishop/btn_sort_new.gif" border=0 alt="신상품" align="absmiddle">--></span></a>
		</td>
		<td align="right">
			<select name="listnum" onChange="ChangeListnum(this.value)" class="select">
			<? for($i=1;$i<6;$i++){ 
					$tnum = 5 * (4*$i);
					$tsel = $listnum == $tnum?'selected':'';
			?>
				
				<option value="<?=$tnum?>" <?=$tsel?>><?=$tnum?>개씩 보기</option>
			<?	} ?>
			</select>
		</td>
		<? /*
		<td align="right"><!-- <A HREF="javascript:ChangeDisplayType('I')"><img id="btn_displayI" src="<?=$Dir?>images/minishop/ico_img.gif" border="0" /></A><img src="<?=$Dir?>images/minishop/ico_double.gif" border=0 hspace=2><span id="btn_displayD"><A HREF="javascript:ChangeDisplayType('D')">이미지더블형</A></span>&nbsp; <A HREF="javascript:ChangeDisplayType('L')"><img id="btn_displayL" src="<?=$Dir?>images/minishop/ico_list.gif" border="0" /></A>--></td> */?>
	</tr>
	<table>

	<table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:4px 0px 10px 0px;">
		<tr><td height="0" bgcolor="#ECECEC"></td></tr>
	</table>
<?
	$qry = "WHERE 1=1 ";
	if(strlen($likecode)>0) {
		$qry.= "AND a.productcode LIKE '".$likecode."%' ";
	}

// 	(a.vender='15' and rp.trust_vender is NULL) or (rp.trust_vender='15' and rp.trust_vender<>a.vender)

	$qry.= "AND a.display='Y' ";
	$qry.= "AND a.vender='".$_minidata->vender."' and ((rp.trust_vender is NULL or rp.trust_vender='0')) ";
	$qry.= "OR (rp.trust_vender='".$_minidata->vender."' AND rp.trust_vender<>a.vender AND rp.trust_approve='Y') ";
	$qry.= "AND (a.group_check='N' OR b.group_code='".$_ShopInfo->getMemgroup()."') ";

	$t_count = (int)$thiscodecnt;
	$pagecount = (($t_count - 1) / $setup[list_num]) + 1;

	$sql = productQuery ();
	$sql.= $qry." ";
	if($sort=="ord_qty") $sql.= "ORDER BY a.sellcount DESC ";
	else if($sort=="lprice") $sql.= "ORDER BY a.sellprice ASC ";
	else if($sort=="hprice") $sql.= "ORDER BY a.sellprice DESC ";
	else if($sort=="cdate") $sql.= "ORDER BY a.regdate DESC ";
	else $sql.= "ORDER BY a.sellcount DESC ";
	$sql.= "LIMIT " . ($setup[list_num] * ($gotopage - 1)) . ", " . $setup[list_num];

	$result=mysql_query($sql,get_db_conn());
	
	$innerpub = file_get_contents($Dir.'newUI/prlist_category.html');	
			
	$pos = strlen($innerpub);
	if(false !== $pos = strpos($innerpub,'<!-- items -->')){
		if(false === $epos = strpos($innerpub,'<!-- /items -->')) $epos = strlen($innerpub);			
		$conts['items'] = substr($innerpub,$pos+strlen('<!-- items -->'),$epos-$pos-strlen('<!-- items -->'));
	}
	$conts['head'] = substr($innerpub,0,$pos);
	$conts['bott'] = substr($innerpub,$epos);			
	$conts['cont'] = '';
	$conts = str_replace('__ID__','PRITEMS',$conts);
	$conts = str_replace('__WIDTH__','225px',$conts);
	$conts = str_replace('__HEIGHT__','225px',$conts);
	
	if(mysql_num_rows($result)){
		$i=0;
		while($row=mysql_fetch_assoc($result)) {
			$itemtxt = $conts['items'];	
			$row = solvResultforNewUi($row);	
			$row['listfinal'] = (++$i%5==0)?'endItem':'';					
			foreach($row as $k=>$v){
				$itemtxt = str_replace('product.'.$k,$v,$itemtxt);
			}
			if(!_empty($row['listfinal'])) $itemtxt .= '<div style="clear:both; height:10px;"></div>';
			$conts['cont'] .= $itemtxt;		
		}
	}
	echo $conts['head'].$conts['cont'].$conts['bott'];
	

	if($i>0) {		
		$total_block = intval($pagecount / $setup[page_num]);
		if (($pagecount % $setup[page_num]) > 0) {
			$total_block = $total_block + 1;
		}
		$total_block = $total_block - 1;
		if (ceil($t_count/$setup[list_num]) > 0) {
			// 이전	x개 출력하는 부분-시작
			$a_first_block = "";
			if ($nowblock > 0) {
				$a_first_block .= "<a href='javascript:GoPage(0,1);' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='첫 페이지';return true\"><img src=".$Dir."images/minishop/btn_miniprev_end.gif border=0 align=absmiddle></a> ";
				$prev_page_exists = true;
			}
			$a_prev_page = "";
			if ($nowblock > 0) {
				$a_prev_page .= "<a href='javascript:GoPage(".($nowblock-1).",".($setup[page_num]*($block-1)+$setup[page_num]).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='이전 ".$setup[page_num]." 페이지';return true\"><img src=".$Dir."images/minishop/btn_miniprev.gif border=0 align=absmiddle></a> ";

				$a_prev_page = $a_first_block.$a_prev_page;
			}
			if (intval($total_block) <> intval($nowblock)) {
				$print_page = "";
				for ($gopage = 1; $gopage <= $setup[page_num]; $gopage++) {
					if ((intval($nowblock*$setup[page_num]) + $gopage) == intval($gotopage)) {
						$print_page .= "<FONT color=red><B>".(intval($nowblock*$setup[page_num]) + $gopage)."</B></font> ";
					} else {
						$print_page .= "<a href='javascript:GoPage(".$nowblock.",".(intval($nowblock*$setup[page_num]) + $gopage).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='페이지 : ".(intval($nowblock*$setup[page_num]) + $gopage)."';return true\">[".(intval($nowblock*$setup[page_num]) + $gopage)."]</a> ";
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
						$print_page .= "<FONT color=red><B>".(intval($nowblock*$setup[page_num]) + $gopage)."</B></FONT> ";
					} else {
						$print_page .= "<a href='javascript:GoPage(".$nowblock.",".(intval($nowblock*$setup[page_num]) + $gopage).");' onMouseOver=\"window.status='페이지 : ".(intval($nowblock*$setup[page_num]) + $gopage)."';return true\">[".(intval($nowblock*$setup[page_num]) + $gopage)."]</a> ";
					}
				}
			}
			$a_last_block = "";
			if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
				$last_block = ceil($t_count/($setup[list_num]*$setup[page_num])) - 1;
				$last_gotopage = ceil($t_count/$setup[list_num]);
				$a_last_block .= " <a href='javascript:GoPage(".$last_block.",".$last_gotopage.");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='마지막 페이지';return true\"><img src=".$Dir."images/minishop/btn_mininext_end.gif border=0 align=absmiddle></a>";
				$next_page_exists = true;
			}
			$a_next_page = "";
			if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
				$a_next_page .= " <a href='javascript:GoPage(".($nowblock+1).",".($setup[page_num]*($nowblock+1)+1).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='다음 ".$setup[page_num]." 페이지';return true\"><img src=".$Dir."images/minishop/btn_mininext.gif border=0 align=absmiddle></a>";
				$a_next_page = $a_next_page.$a_last_block;
			}
		} else {
			$print_page = "<B>1</B>";
		}
		$pageing=$a_div_prev_page.$a_prev_page.$print_page.$a_next_page.$a_div_next_page;

		echo "<table border=0 cellpadding=0 cellspacing=0 width=100%>\n";
		echo "<tr><td height=15></td></tr>\n";
		echo "<tr><td height=1 bgcolor=#ECECEC></td></tr>\n";
		echo "<tr><td height=10></td></tr>\n";
		echo "<tr><td align=center>".$pageing."</td></tr>\n";
		echo "</table>\n";
	}
?>
	</td>
</tr>

<form name=prfrm method=get action="<?=$_SERVER[PHP_SELF]?>">
<input type=hidden name=sellvidx value="<?=$_minidata->vender?>">
<input type=hidden name=code value="<?=$code?>">
<input type=hidden name=tgbn value="">
<input type=hidden name=pageid value="<?=$pageid?>">
<input type=hidden name=listnum value="<?=$listnum?>">
<input type=hidden name=sort value="<?=$sort?>">
<input type=hidden name=block value="<?=$block?>">
<input type=hidden name=gotopage value="<?=$gotopage?>">
</form>

<form name="codeNaviForm" method="get" id="codeNaviForm" action="<?=$_SERVER['PHP_SELF']?>">
<input type="hidden" name="code2" value="">
<input type="hidden" name="sellvidx" value="<?=$sellvidx?>">
</form>

</table>
<? /*
<script>ChangeDisplayType('<?=$pageid?>')</script>
*/ ?>
<link type="text/css" rel="stylesheet" href="/css/jamkan.css" >
<? include ($Dir."lib/bottom.php") ?>

<div id="create_openwin" class="viewPopup" style="display:none"></div>

<div id="wishlist" class="wishPopup" style="display:none;"></div>
<div id="basketlist" class="basketPopup" style="display:none;"></div>	

</BODY>
</HTML>