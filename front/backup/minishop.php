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
$tgbn="10";
if(strlen($code)==0) {
	$code="000000";
}
$codeA=substr($code,0,3);
$codeB=substr($code,3,3);
if(strlen($codeA)!=3) $codeA="000";
if(strlen($codeB)!=3) $codeB="000";
$code=$codeA.$codeB;
if($codeA!="000") $likecode.=$codeA;
if($codeB!="000") $likecode.=$codeB;


$_MiniLib->getCode($tgbn,$code);
$_MiniLib->getThemecode();

$sort=$_REQUEST["sort"];
$listnum=(int)$_REQUEST["listnum"];
$pageid=$_REQUEST["pageid"];
if(!preg_match("/^(I|D|L)$/",$pageid)) $pageid=$_minidata->prlist_display;

if($listnum<=0) $listnum=$_minidata->prlist_num;

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

$iscode=true;
if(substr($code,0,3)=="000") {
	$thiscodename="�̴ϼ�";
	$thiscodecnt=$_MiniLib->codecnt["000"];
} else {
	if(strlen($_MiniLib->codename[$code])==0) $iscode=false;

	$thiscodename=$_MiniLib->codename[$code];
	$thiscodecnt=$_MiniLib->codecnt[substr($code,0,3)];
}

if($iscode==false) {
	Header("Location:".$Dir.FrontDir."minishop.php?sellvidx=".$sellvidx);
	exit;
}

$strlocation="<A HREF=\"http://".$_ShopInfo->getShopurl()."\">Ȩ</A> > <A HREF=\"http://".$_ShopInfo->getShopurl().FrontDir."minishop.php?sellvidx=".$_minidata->vender."\"><B>".$_minidata->brand_name."</B></A>";

$tag_0_count = "3";
$tag_1_count = "3";
$tag_2_count = "5";
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
<?include($Dir."lib/style.php")?>
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
//-->
</SCRIPT>
</HEAD>

<body<?=(substr($_data->layoutdata["MOUSEKEY"],0,1)=="Y"?" oncontextmenu=\"return false;\"":"")?><?=(substr($_data->layoutdata["MOUSEKEY"],1,1)=="Y"?" ondragstart=\"return false;\" onselectstart=\"return false;\"":"")?> leftmargin="0" marginwidth="0" topmargin="0" marginheight="0"><?=(substr($_data->layoutdata["MOUSEKEY"],2,1)=="Y"?"<meta http-equiv=\"ImageToolbar\" content=\"No\">":"")?>

<? include ($Dir."lib/menu_minishop.php") ?>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
	<td align="center">

	<!-- ����ȭ�� ��� ���������� -->
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

	<!-- HOT ��õ��ǰ -->
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
			$sql.= "AND a.vender='".$_minidata->vender."' AND a.display='Y' ";
			$sql.= "AND (a.group_check='N' OR b.group_code='".$_ShopInfo->getMemgroup()."') ";
			if($_minidata->hot_linktype=="1" || $isnot_hotspecial==true) {
				$sql.= "ORDER BY a.sellcount DESC ";
			} else if($_minidata->hot_linktype=="2") {
				$sql.= "ORDER BY FIELD(a.productcode,'".$hot_prcode."') ";
			}
			$sql.= "LIMIT ".$hot_dispcnt." ";
			$result=mysql_query($sql,get_db_conn());
			$yy=1;
			while($row=mysql_fetch_object($result)) {
				$specialprlist[$yy]=$row;
				$yy++;
			}
			mysql_free_result($result);
		}
		if(count($specialprlist)>0) {
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
			$conts = str_replace('__ID__','TOP_NEW',$conts);				
			
			if(mysql_num_rows($result)){
				$i=0;
				while($row=mysql_fetch_assoc($result)) {
					$itemtxt = $conts['items'];	
					$row = solvResultforNewUi($row);	
					$row['listfinal'] = (++$i%$newitem1_cols==0)?'endItem':'';					
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

	<!-- NEW �Ż�ǰ -->
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
			$sql = "SELECT a.productcode,a.productname,a.sellprice,a.quantity,a.consumerprice,a.reserve,a.reservetype,a.production, ";
			$sql.= "a.option_price, a.tag, a.minimage, a.tinyimage, a.etctype, a.option_price, a.selfcode, a.prmsg ";
			$sql.= "FROM tblproduct AS a ";
			$sql.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
			$sql.= "WHERE a.vender='".$_minidata->vender."' AND a.display='Y' ";
			$sql.= "AND (a.group_check='N' OR b.group_code='".$_ShopInfo->getMemgroup()."') ";
			$sql.= "ORDER BY a.regdate DESC ";
			$sql.= "LIMIT ".$new_dispcnt." ";
			$result=mysql_query($sql,get_db_conn());
			$yy=1;
			while($row=mysql_fetch_object($result)) {
				$specialprlist[$yy]=$row;
				$yy++;
			}
			mysql_free_result($result);
		}
		if(count($specialprlist)>0) {
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
			include ($Dir.TempletDir."minisect/".$new_disptype.".php");
			echo "	</td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "	<td height=15></td>\n";
			echo "</tr>\n";
			echo "</table>\n";
		}
	}
	?>

	<?if($_minidata->code_distype!="NY"){?>
	<table border=0 cellpadding=0 cellspacing=0 width=100%>
		<tr height=37>
			<td style="padding-left:5px"><span style="color:#000000;font-weight:bold;font-size:15px;"><?=$thiscodename?>(<?=(int)$thiscodecnt?>)</span></td>
		</tr>
		<tr>
			<td>
				<div style="margin-bottom:20px;background:url('/data/design/img/sub/bg_boxline2.gif') repeat-y;">
					<div style="height:6px;background:url('/data/design/img/sub/top_boxline2.gif') no-repeat;font-size:0px;"></div>
					<!-- 3�� ī�װ� ��� START -->
					<table border="0" cellpadding="0" cellspacing="0" width="98%" align="center" style="margin:10px auto;">
						<tr>
							<td>
	<?
		if(substr($code,0,3)=="000") echo "<b>";
		echo "<a href=\"javascript:GoPrSection('')\">��ü[".(int)$_MiniLib->codecnt["000"]."]</b></a>";
		for($i=0;$i<count($prdataA);$i++) {
			$tmpcode=$prdataA[$i]->codeA.$prdataA[$i]->codeB;
			echo "<span style=\"padding:0px 10px;color:#cccccc;font-size:11px;\">|</span>";
			if($code==$tmpcode) echo "<b>";
			echo "<a href=\"javascript:GoPrSection('".$tmpcode."')\">".$_MiniLib->codename[$tmpcode]."[".(int)$_MiniLib->codecnt[substr($tmpcode,0,3)]."]</A></b>";
		}
	?>
							</td>
						</tr>
					</table>
					<!-- 3�� ī�װ� ��� END -->
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

			<a href="javascript:ChangeSort('ord_qty');"><span class="sort<?=$sortHit?>">�α��</span><!--<img src="<?=$Dir?>images/minishop/btn_sort_popular.gif" border=0 alt="�α��ǰ��" align="absmiddle">--></a><span class="sortLine">|</span><a href="javascript:ChangeSort('lprice');"><span class="sort<?=$sortLowPrice?>">���� ���ݼ�</span><!--<img src="<?=$Dir?>images/minishop/btn_sort_low.gif" border=0 alt="�������ݼ�" align="absmiddle">--></a><span class="sortLine">|</span><a href="javascript:ChangeSort('hprice');"><span class="sort<?=$sortHiPrice?>">���� ���ݼ�</span><!--<img src="<?=$Dir?>images/minishop/btn_sort_high.gif" border=0 alt="�������ݼ�" align="absmiddle">--></a><span class="sortLine">|</span><a href="javascript:ChangeSort('cdate');"><span class="sort<?=$sortNew?>">����ϼ�<!--<img src="<?=$Dir?>images/minishop/btn_sort_new.gif" border=0 alt="�Ż�ǰ" align="absmiddle">--></span></a>
		</td>
		<td align="right">
			<select name="listnum" onChange="ChangeListnum(this.value)" style="height:26px;color:#999999;font-size:11px;font-family:����;">
				<option value="12"<?if($listnum==12)echo" selected";?>>12���� ����</option>
				<option value="24"<?if($listnum==24)echo" selected";?>>24���� ����</option>
				<option value="36"<?if($listnum==36)echo" selected";?>>36���� ����</option>
				<option value="48"<?if($listnum==48)echo" selected";?>>48���� ����</option>
				<option value="60"<?if($listnum==60)echo" selected";?>>60���� ����</option>
			</select>
		</td>
		<td align="right"><A HREF="javascript:ChangeDisplayType('I')"><img id="btn_displayI" src="<?=$Dir?>images/minishop/ico_img.gif" border="0" /></A><!--<img src="<?=$Dir?>images/minishop/ico_double.gif" border=0 hspace=2><span id="btn_displayD"><A HREF="javascript:ChangeDisplayType('D')">�̹���������</A></span>&nbsp; --><A HREF="javascript:ChangeDisplayType('L')"><img id="btn_displayL" src="<?=$Dir?>images/minishop/ico_list.gif" border="0" /></A></td>
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
	$qry.="AND a.display='Y' AND a.vender='".$_minidata->vender."' ";
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
	$i=0;
	$str_displayI="<table width=100% border=0 cellpadding=0 cellspacing=0 id=layer_displayI style=\"display:none;table-layout:fixed\">\n";
	$str_displayI.="<col width=20></col><col width=23%></col><col width=40></col><col width=23%></col><col width=40></col><col width=23%></col><col width=40></col><col width=23%></col><col width=20></col>\n";
	$str_displayD="<table width=100% border=0 cellpadding=0 cellspacing=0 id=layer_displayD style=\"display:none;table-layout:fixed\">\n";
	$str_displayD.="<col width=20></col><col width=48%></col><col width=40></col><col width=48%></col><col width=20></col>\n";
	$str_displayL="<table width=100% border=0 cellpadding=0 cellspacing=0 id=layer_displayL style=\"display:none;table-layout:fixed\">\n";
	$str_displayL.="<col width=10></col><col width=90></col><col width=1></col><col width=></col><col width=90></col><col width=120></col><col width=70></col><col width=10></col>\n";

	while($row=mysql_fetch_object($result)) {

		// ���� ���� ���� ��ǰ ������
		$wholeSaleIcon = ( $row->isdiscountprice == 1 ) ? $wholeSaleIconSet:"";

		$memberpriceValue = $row->sellprice;
		$strikeStart = $strikeEnd = $memberprice = '';
		if($row->discountprices>0){
			$memberprice = number_format($row->sellprice - $row->discountprices);
			$strikeStart = "<strike>";
			$strikeEnd = "</strike>";
			$memberpriceValue = ($row->sellprice - $row->discountprices);
		}


		$number = ($t_count-($setup[list_num] * ($gotopage-1))-$i);
		$reserveconv=getReserveConversion($row->reserve,$row->reservetype,$memberpriceValue,"Y");

		/*############### ū�̹����� ���� ##################*/
		if ($i>0 && $i%4==0) {
			$str_displayI.="<tr><td colspan=9 height=1><table border=0 cellpadding=0 cellspacing=0 height=1 style=\"table-layout:fixed\"><tr><td height=1 style=\"border:1 dotted #DDDDDD\"><img width=1 height=0></td></tr></table></td></tr>\n";
			$str_displayI.="<tr><td colspan=9 height=10></td></tr><tr>\n";
		}
		if ($i%4==0) {
			$str_displayI.="<td height=100% align=center nowrap>&nbsp;</td>";
		}
		$str_displayI.="<td valign=top>\n";
		$str_displayI.= "<table border=0 cellpadding=0 cellspacing=0 width=100% id=\"GI".$row->productcode."\" onmouseover=\"quickfun_show(this,'GI".$row->productcode."','')\" onmouseout=\"quickfun_show(this,'GI".$row->productcode."','none')\">\n";
		$str_displayI.= "<tr height=100>\n";
		$str_displayI.= "	<td align=center>";
		if (strlen($row->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$row->tinyimage)==true) {
			$str_displayI.= "<A HREF=\"javascript:GoItem('".$row->productcode."')\"><img src=\"".$Dir.DataDir."shopimages/product/".urlencode($row->tinyimage)."\" border=0 ";
			$width = getimagesize($Dir.DataDir."shopimages/product/".$row->tinyimage);
			if ($width[0]>=$width[1] && $width[0]>=130) $str_displayI.= "width=130 ";
			else if ($width[1]>=130) $str_displayI.= "height=130 ";
		} else {
			$str_displayI.= "<img src=\"".$Dir."images/no_img.gif\" border=0 align=center";
		}
		$str_displayI.= "	></A></td>\n";
		$str_displayI.= "</tr>\n";
		$str_displayI.= "<tr><td height=\"3\" style=\"position:relative;\">".($_data->ETCTYPE["QUICKTOOLS"]!="Y"?"<script>quickfun_write('".$Dir."','GI','".$row->productcode."','".($row->quantity=="0"?"":"1")."')</script>":"")."</td></tr>\n";
		$str_displayI.= "<tr>";
		$str_displayI.= "	<td valign=top style=\"word-break:break-all;\"><A HREF=\"javascript:GoItem('".$row->productcode."')\">".viewproductname($row->productname,$row->etctype,$row->selfcode)."</A><br><A HREF=\"".$Dir."?productcode=".$row->productcode."\" onmouseover=\"window.status='��ǰ����ȸ';return true;\" onmouseout=\"window.status='';return true;\" target=\"_blank\"><img src=\"".$Dir."images/minishop/icon_newpopup.gif\" border=0 align=absmiddle>".(strlen($row->prmsg)?'<br><span class="prmsgArea">'.$row->prmsg.'</span>':'')."</td>\n";
		$str_displayI.= "</tr>\n";
		$str_displayI.= "<tr><td height=5></td></tr>\n";
		if($reserveconv>0) {	//������
			$str_displayI.="<tr>\n";
			$str_displayI.="	<td valign=top style=\"word-break:break-all;\" class=verdana2><img src=\"".$Dir."images/common/reserve_icon.gif\" border=0 align=absmiddle>".number_format($reserveconv)."��";
			$str_displayI.="	</td>\n";
			$str_displayI.="</tr>\n";
		}
		if($row->consumerprice>0) {	//�Һ��ڰ�
			$str_displayI.="<tr>\n";
			$str_displayI.="	<td valign=top style=\"word-break:break-all;\" class=verdana2 style=\"color:#A7A7A7\"><img src=\"".$Dir."images/common/won_icon2.gif\" border=0 align=absmiddle><strike>".number_format($row->consumerprice)."</strike>��";
			$str_displayI.="	</td>\n";
			$str_displayI.="</tr>\n";
		}
		$str_displayI.= "<tr>\n";
		$str_displayI.= "	<td valign=top style=\"word-break:break-all;\" class=verdana2 style=\"font-weight:bold;color:#FF3243 !important\">";

		$str_displayI.= $strikeStart;

		if($dicker=dickerview($row->etctype,$wholeSaleIcon.number_format($row->sellprice)."��",1)) {
			$str_displayI.= $dicker;
		} else if(strlen($_data->proption_price)==0) {
			$str_displayI.= "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle>".$wholeSaleIcon.number_format($row->sellprice)."��";
			if (strlen($row->option_price)!=0) $str_displayI.= "(�⺻��)";
		} else {
			$str_displayI.= "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle>";
			if (strlen($row->option_price)==0) $str_displayI.= $wholeSaleIcon.number_format($row->sellprice)."��";
			else $str_displayI.= ereg_replace("\[PRICE\]",number_format($row->sellprice),$_data->proption_price);
		}

		$str_displayI.= $strikeEnd;

		if ($row->quantity=="0") $str_displayI.= soldout();
		$str_displayI.= "	</td>\n";
		$str_displayI.= "</tr>\n";

		//ȸ�����ΰ� ����
		if( $memberprice > 0 ) {
			$str_displayI.="<tr>\n";
			$str_displayI.="	<td valign=top style=\"word-break:break-all;\" class=verdana2 style=\"font-weight:bold;color:#FF3243 !important\">".dickerview($row->etctype,$memberprice."��");
			$str_displayI.="	</td>\n";
			$str_displayI.="</tr>\n";
		}


		//�±װ���
		if($_data->ETCTYPE["TAGTYPE"]=="Y") {
			if(strlen($row->tag)>0) {
				$arrtaglist=explode(",",$row->tag);
				$jj=0;
				for($ii=0;$ii<$tag_0_count;$ii++) {
					$arrtaglist[$ii]=ereg_replace("(<|>)","",$arrtaglist[$ii]);
					if(strlen($arrtaglist[$ii])>0) {
						if($jj==0) {
							$str_displayI.= "<tr>\n";
							$str_displayI.= "	<td align=\"left\" style=\"word-break:break-all;\" class=verdana2 style=\"padding-top:2px;font-family:����; font-size:8pt; font-weight:normal; color:FF6633;\">\n";
							$str_displayI.= "	<img src=\"".$Dir."images/common/tag_icon.gif\" border=\"0\" align=\"absmiddle\" style=\"margin-right:2px;\"><a href=\"".$Dir.FrontDir."tag.php?tagname=".urlencode($arrtaglist[$ii])."\" onmouseover=\"window.status='".$arrtaglist[$ii]."';return true;\" onmouseout=\"window.status='';return true;\"><FONT style=\"font-family:����; font-size:8pt; font-weight:normal; color:FF6633;\">".$arrtaglist[$ii]."</font></a>";
						}
						else {
							$str_displayI.= "<img width=2 height=0>+<img width=2 height=0><a href=\"".$Dir.FrontDir."tag.php?tagname=".urlencode($arrtaglist[$ii])."\" onmouseover=\"window.status='".$arrtaglist[$ii]."';return true;\" onmouseout=\"window.status='';return true;\"><FONT style=\"font-family:����; font-size:8pt; font-weight:normal; color:FF6633;\">".$arrtaglist[$ii]."</font></a>";
						}
						$jj++;
					}
				}
				if($jj!=0) {
					$str_displayI.= "	</td>\n";
					$str_displayI.= "</tr>\n";
				}
			}
		}

		$str_displayI.= "</table>\n";
		$str_displayI.= "</td>\n";
		$str_displayI.="<td height=100% align=center nowrap>&nbsp;</td>";

		if (($i+1)%4==0) {
			$str_displayI.="</tr><tr><td colspan=9 height=10></td></tr>\n";
		}
		/*#################### ū�̹����� �� ##################*/

		/*#################### �̹��������� ���� ##################*/
		if($i==0) $str_displayD.="<tr>\n";
		if ($i>0 && $i%2==0) {
				$str_displayD.="<tr><td colspan=5 height=1><table border=0 cellpadding=0 cellspacing=0 height=1 style=\"table-layout:fixed\"><tr><td height=1 style=\"border:1 dotted #DDDDDD\"><img width=1 height=0></td></tr></table></td></tr>\n";
				$str_displayD.="<tr><td colspan=5 height=10></td></tr><tr>\n";
		}
		if ($i%2==0) {
			$str_displayD.="<td height=100% align=center nowrap>&nbsp;</td>";
		}
		$str_displayD.="<td align=center>\n";
		$str_displayD.= "<table border=0 cellpadding=0 cellspacing=0 width=100% id=\"GD".$row->productcode."\" onmouseover=\"quickfun_show(this,'GD".$row->productcode."','','row')\" onmouseout=\"quickfun_show(this,'GD".$row->productcode."','none')\">\n";
		$str_displayD.= "<col width=\"100\"></col>\n";
		$str_displayD.= "<col width=\"0\"></col>\n";
		$str_displayD.= "<col width=\"100%\"></col>\n";
		$str_displayD.= "<tr height=100>\n";
		$str_displayD.= "	<td align=center>";
		if (strlen($row->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$row->tinyimage)==true) {
			$str_displayD.= "<A HREF=\"javascript:GoItem('".$row->productcode."')\"><img src=\"".$Dir.DataDir."shopimages/product/".urlencode($row->tinyimage)."\" border=0 ";
			$width = getimagesize($Dir.DataDir."shopimages/product/".$row->tinyimage);
			if($_data->ETCTYPE["IMGSERO"]=="Y") {
				if ($width[1]>$width[0] && $width[1]>$_data->primg_minisize2) $str_displayD.= "height=".$_data->primg_minisize2." ";
				else if (($width[1]>=$width[0] && $width[0]>=$_data->primg_minisize) || $width[0]>=$_data->primg_minisize) $str_displayD.= "width=".$_data->primg_minisize." ";
			} else {
				if ($width[0]>=$width[1] && $width[0]>=$_data->primg_minisize) $str_displayD.= "width=".$_data->primg_minisize." ";
				else if ($width[1]>=$_data->primg_minisize) $str_displayD.= "height=".$_data->primg_minisize." ";
			}
		} else {
			$str_displayD.= "<img src=\"".$Dir."images/no_img.gif\" border=0 align=center";
		}
		$str_displayD.= "	></A></td>\n";
		$str_displayD.= "	<td style=\"position:relative;\">".($_data->ETCTYPE["QUICKTOOLS"]!="Y"?"<script>quickfun_write('".$Dir."','GD','".$row->productcode."','".($row->quantity=="0"?"":"1")."','row')</script>":"")."</td>";
		$str_displayD.= "	<td valign=middle style=\"padding-left:5\">\n";
		$str_displayD.= "	<table border=0 cellpadding=0 cellspacing=0 width=100%>\n";
		$str_displayD.= "	<tr>";
		$str_displayD.= "		<td align=left valign=top style=\"word-break:break-all;\"><A HREF=\"javascript:GoItem('".$row->productcode."')\">".viewproductname($row->productname,$row->etctype,$row->selfcode)."</A><br><A HREF=\"".$Dir."?productcode=".$row->productcode."\" onmouseover=\"window.status='��ǰ����ȸ';return true;\" onmouseout=\"window.status='';return true;\" target=\"_blank\"><img src=\"".$Dir."images/minishop/icon_newpopup.gif\" border=0 align=absmiddle>".(strlen($row->prmsg)?'<br><span class="prmsgArea">'.$row->prmsg.'</span>':'')."</td>\n";
		$str_displayD.= "	</tr>\n";
		//�±װ���
		if($_data->ETCTYPE["TAGTYPE"]=="Y") {
			if(strlen($row->tag)>0) {
				$str_displayD.="<tr><td height=5></td></tr>\n";
				$str_displayD.="<tr>\n";
				$str_displayD.="	<td align=left style=\"word-break:break-all;\" style=\"font-family:����; font-size:8pt; font-weight:normal; color:FF6633;\"><img src=\"".$Dir."images/common/tag_icon.gif\" border=0 align=absmiddle><img width=2 height=0>";
				$arrtaglist=explode(",",$row->tag);
				$jj=0;
				for($ii=0;$ii<$tag_1_count;$ii++) {
					$arrtaglist[$ii]=ereg_replace("(<|>)","",$arrtaglist[$ii]);
					if(strlen($arrtaglist[$ii])>0) {
						if($jj<4) {
							if($jj>0) $str_displayD.="<img width=2 height=0>+<img width=2 height=0>";
						} else {
							if($jj>0) $str_displayD.="<img width=2 height=0>+<img width=2 height=0>";
							break;
						}
						$str_displayD.="<a href=\"".$Dir.FrontDir."tag.php?tagname=".urlencode($arrtaglist[$ii])."\" onmouseover=\"window.status='".$arrtaglist[$ii]."';return true;\" onmouseout=\"window.status='';return true;\"><FONT style=\"font-family:����; font-size:8pt; font-weight:normal; color:FF6633;\">".$arrtaglist[$ii]."</FONT></a>";
						$jj++;
					}
				}
				$str_displayD.="	</td>\n";
				$str_displayD.="</tr>\n";
			}
		}
		$str_displayD.= "<tr><td height=5></td></tr>\n";
		if($reserveconv>0) {	//������
			$str_displayD.="<tr>\n";
			$str_displayD.="	<td align=left valign=top style=\"word-break:break-all;\" class=verdana2><img src=\"".$Dir."images/common/reserve_icon.gif\" border=0 align=absmiddle>".number_format($reserveconv)."��";
			$str_displayD.="	</td>\n";
			$str_displayD.="</tr>\n";
		}
		if($row->consumerprice>0) {	//�Һ��ڰ�
			$str_displayD.="<tr>\n";
			$str_displayD.="	<td align=left valign=top style=\"word-break:break-all;\" class=verdana2 style=\"color:#A7A7A7\"><img src=\"".$Dir."images/common/won_icon2.gif\" border=0 align=absmiddle><strike>".number_format($row->consumerprice)."</strike>��";
			$str_displayD.="	</td>\n";
			$str_displayD.="</tr>\n";
		}
		$str_displayD.= "<tr>\n";
		$str_displayD.= "	<td align=left valign=top style=\"word-break:break-all;\" class=verdana2 style=\"font-weight:bold;color:#FF3243 !important\">";

		$str_displayD.= $strikeStart;

		if($dicker=dickerview($row->etctype,$wholeSaleIcon.number_format($row->sellprice)."��",1)) {
			$str_displayD.= $dicker;
		} else if(strlen($_data->proption_price)==0) {
			$str_displayD.= "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle>".$wholeSaleIcon.number_format($row->sellprice)."��";
			if (strlen($row->option_price)!=0) $str_displayD.= "(�⺻��)";
		} else {
			$str_displayD.= "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle>";
			if (strlen($row->option_price)==0) $str_displayD.= $wholeSaleIcon.number_format($row->sellprice)."��";
			else $str_displayD.= ereg_replace("\[PRICE\]",number_format($row->sellprice),$_data->proption_price);
		}

		$str_displayD.= $strikeEnd;

		//ȸ�����ΰ� ����
		if( $memberprice > 0 ) {
			$str_displayD.= "<br />".dickerview($row->etctype,$memberprice."��");
		}

		if ($row->quantity=="0") $str_displayD.= soldout();
		$str_displayD.= "	</td>\n";
		$str_displayD.= "</tr>\n";
		$str_displayD.= "	</table>\n";
		$str_displayD.= "	</td>\n";
		$str_displayD.= "</tr>\n";
		$str_displayD.= "</table>\n";
		$str_displayD.= "</td>\n";
		$str_displayD.="<td height=100% align=center nowrap>&nbsp;</td>";

		if (($i+1)%2==0) {
			$str_displayD.="</tr><tr><td colspan=5 height=10></td></tr>\n";
		}
		/*#################### �̹��������� �� ##################*/

		/*#################### ����Ʈ�� ���� ##################*/
		if($i>0) {
			$str_displayL.="<tr><td colspan=8 height=5></td></tr>\n";
			$str_displayL.="<tr><td colspan=8 height=1><table border=0 cellpadding=0 cellspacing=0 height=1 style=\"table-layout:fixed\"><tr><td height=1 style=\"border:1 dotted #DDDDDD\"><img width=1 height=0></td></tr></table></td></tr>\n";
			$str_displayL.="<tr><td colspan=8 height=5></td></tr>\n";
		}
		$str_displayL.= "<tr id=\"GL".$row->productcode."\" onmouseover=\"quickfun_show(this,'GL".$row->productcode."','','row')\" onmouseout=\"quickfun_show(this,'GL".$row->productcode."','none')\">\n";
		$str_displayL.= "	<td></td>\n";
		$str_displayL.= "	<td align=center>";
		if (strlen($row->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$row->tinyimage)==true) {
			$str_displayL.= "<A HREF=\"javascript:GoItem('".$row->productcode."')\"><img src=\"".$Dir.DataDir."shopimages/product/".urlencode($row->tinyimage)."\" border=0 ";
			$width = getimagesize($Dir.DataDir."shopimages/product/".$row->tinyimage);
			if ($width[0]>=$width[1] && $width[0]>=90) $str_displayL.= "width=90 ";
			else if ($width[1]>=90) $str_displayL.= "height=90 ";
		} else {
			$str_displayL.= "<img src=\"".$Dir."images/no_img.gif\" height=90 border=0 align=center";
		}
		$str_displayL.= "	></A></td>\n";
		$str_displayL.= "	<td style=\"position:relative;\">".($_data->ETCTYPE["QUICKTOOLS"]!="Y"?"<script>quickfun_write('".$Dir."','GL','".$row->productcode."','".($row->quantity=="0"?"":"1")."','row')</script>":"")."</td>";
		$str_displayL.= "	<td style=\"padding-left:10\" style=\"word-break:break-all;\"><A HREF=\"javascript:GoItem('".$row->productcode."')\">".viewproductname($row->productname,$row->etctype,$row->selfcode)."</A>";
		if ($row->quantity=="0") $str_displayL.= soldout();
		$str_displayL.= " <br><A HREF=\"".$Dir."?productcode=".$row->productcode."\" onmouseover=\"window.status='��ǰ����ȸ';return true;\" onmouseout=\"window.status='';return true;\" target=\"_blank\"><img src=\"".$Dir."images/minishop/icon_newpopup.gif\" border=0 align=absmiddle>".(strlen($row->prmsg)?'<br><span class="prmsgArea">'.$row->prmsg.'</span>':'')."";
		//�±װ���
		if($_data->ETCTYPE["TAGTYPE"]=="Y") {
			if(strlen($row->tag)>0) {
				$str_displayL.="<br><img width=0 height=5><br><img src=\"".$Dir."images/common/tag_icon.gif\" border=0 align=absmiddle><img width=2 height=0>";
				$arrtaglist=explode(",",$row->tag);
				$jj=0;
				for($ii=0;$ii<$tag_2_count;$ii++) {
					$arrtaglist[$ii]=ereg_replace("(<|>)","",$arrtaglist[$ii]);
					if(strlen($arrtaglist[$ii])>0) {
						if($jj<5) {
							if($jj>0) $str_displayL.="<img width=2 height=0><FONT style=\"font-family:����; font-size:8pt; font-weight:normal; color:FF6633;\">+</FONT><img width=2 height=0>";
						} else {
							if($jj>0) $str_displayL.="<img width=2 height=0><FONT style=\"font-family:����; font-size:8pt; font-weight:normal; color:FF6633;\">+</FONT><img width=2 height=0>";
							break;
						}
						$str_displayL.="<a href=\"".$Dir.FrontDir."tag.php?tagname=".urlencode($arrtaglist[$ii])."\" onmouseover=\"window.status='".$arrtaglist[$ii]."';return true;\" onmouseout=\"window.status='';return true;\"><FONT style=\"font-family:����; font-size:8pt; font-weight:normal; color:FF6633;\">".$arrtaglist[$ii]."</FONT></a>";
						$jj++;
					}
				}
			}
		}
		$str_displayL.= "	</td>\n";
		$str_displayL.= "	<td align=center style=\"word-break:break-all;\" class=verdana2 style=\"color:#A7A7A7\"><img src=\"".$Dir."images/common/won_icon2.gif\" border=0 align=absmiddle><strike>".number_format($row->consumerprice)."</strike>��</td>\n";
		$str_displayL.= "	<td align=center style=\"word-break:break-all;\" class=verdana2 style=\"font-weight:bold;color:#FF3243 !important\">";

		$str_displayL.= $strikeStart;

		if($dicker=dickerview($row->etctype,$wholeSaleIcon.number_format($row->sellprice)."��",1)) {
			$str_displayL.= $dicker;
		} else if(strlen($_data->proption_price)==0) {
			$str_displayL.= "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle>".$wholeSaleIcon.number_format($row->sellprice)."��";
			if (strlen($row->option_price)!=0) $str_displayL.= "(�⺻��)";
		} else {
			$str_displayL.="<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle>";
			if (strlen($row->option_price)==0) $str_displayL.= $wholeSaleIcon.number_format($row->sellprice)."��";
			else $str_displayL.= ereg_replace("\[PRICE\]",number_format($row->sellprice),$_data->proption_price);
		}

		$str_displayL.= $strikeEnd;

		//ȸ�����ΰ� ����
		if( $memberprice > 0 ) {
			$str_displayL.="<br />��<br />".dickerview($row->etctype,$memberprice."��");
		}

		$str_displayL.= "	</td>\n";
		$str_displayL.= "	<td align=center style=\"word-break:break-all;\" class=verdana2><img src=\"".$Dir."images/common/reserve_icon.gif\" border=0 align=absmiddle>".number_format($reserveconv)."��</td>\n";
		$str_displayL.= "	<td></td>\n";
		$str_displayL.= "</tr>\n";
		/*#################### ����Ʈ�� �� ##################*/

		$i++;
	}
	mysql_free_result($result);
	$str_displayI.="</tr></table>\n";
	$str_displayD.="</tr></table>\n";
	$str_displayL.="</table>\n";

	echo $str_displayI;
	echo $str_displayD;
	echo $str_displayL;

	if($i>0) {
		$total_block = intval($pagecount / $setup[page_num]);
		if (($pagecount % $setup[page_num]) > 0) {
			$total_block = $total_block + 1;
		}
		$total_block = $total_block - 1;
		if (ceil($t_count/$setup[list_num]) > 0) {
			// ����	x�� ����ϴ� �κ�-����
			$a_first_block = "";
			if ($nowblock > 0) {
				$a_first_block .= "<a href='javascript:GoPage(0,1);' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='ù ������';return true\"><img src=".$Dir."images/minishop/btn_miniprev_end.gif border=0 align=absmiddle></a> ";
				$prev_page_exists = true;
			}
			$a_prev_page = "";
			if ($nowblock > 0) {
				$a_prev_page .= "<a href='javascript:GoPage(".($nowblock-1).",".($setup[page_num]*($block-1)+$setup[page_num]).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup[page_num]." ������';return true\"><img src=".$Dir."images/minishop/btn_miniprev.gif border=0 align=absmiddle></a> ";

				$a_prev_page = $a_first_block.$a_prev_page;
			}
			if (intval($total_block) <> intval($nowblock)) {
				$print_page = "";
				for ($gopage = 1; $gopage <= $setup[page_num]; $gopage++) {
					if ((intval($nowblock*$setup[page_num]) + $gopage) == intval($gotopage)) {
						$print_page .= "<FONT color=red><B>".(intval($nowblock*$setup[page_num]) + $gopage)."</B></font> ";
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
						$print_page .= "<FONT color=red><B>".(intval($nowblock*$setup[page_num]) + $gopage)."</B></FONT> ";
					} else {
						$print_page .= "<a href='javascript:GoPage(".$nowblock.",".(intval($nowblock*$setup[page_num]) + $gopage).");' onMouseOver=\"window.status='������ : ".(intval($nowblock*$setup[page_num]) + $gopage)."';return true\">[".(intval($nowblock*$setup[page_num]) + $gopage)."]</a> ";
					}
				}
			}
			$a_last_block = "";
			if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
				$last_block = ceil($t_count/($setup[list_num]*$setup[page_num])) - 1;
				$last_gotopage = ceil($t_count/$setup[list_num]);
				$a_last_block .= " <a href='javascript:GoPage(".$last_block.",".$last_gotopage.");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='������ ������';return true\"><img src=".$Dir."images/minishop/btn_mininext_end.gif border=0 align=absmiddle></a>";
				$next_page_exists = true;
			}
			$a_next_page = "";
			if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
				$a_next_page .= " <a href='javascript:GoPage(".($nowblock+1).",".($setup[page_num]*($nowblock+1)+1).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup[page_num]." ������';return true\"><img src=".$Dir."images/minishop/btn_mininext.gif border=0 align=absmiddle></a>";
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

</table>

<script>ChangeDisplayType('<?=$pageid?>')</script>

<? include ($Dir."lib/bottom.php") ?>

<div id="create_openwin" style="display:none"></div>

</BODY>
</HTML>