<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

if(strlen($_ShopInfo->getShopurl())==0) {
	exit;
}
if(strlen($_COOKIE[ViewProduct])==0 || $_COOKIE[ViewProduct]=="deleted") {
	//exit;
}

//�ֱ� �� ��ǰ ��� select
$sql = "SELECT * FROM ".$designnewpageTables." WHERE type='r_banner' ";
$result=mysql_query($sql,get_db_conn());
if(!$row=mysql_fetch_object($result)) {
	exit;
}
mysql_free_result($result);

$tmp=explode("",$row->filename);
$design=$tmp[0];	//������
$prdt_type=$tmp[1];	//��ǰŸ��(AA:�̹���,AB:�̹���+��ǰ��)
$content=$row->body;			//���������� ����
$content=str_replace("[DIR]",$Dir,$content);
$prdt_cnt=(int)$row->code;			//��ǰ���� (1~9)
if($prdt_cnt == 0) $prdt_cnt=1;
unset($tmp);

header("Pragma: public");
header("Expires: 0");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: pre-check=0, post-check=0, max-age=0", false);

//$_COOKIE[ViewProduct]	: �ֱ� �� ��ǰ ��� ��Ű (","�� ����)

ob_start("buffer_process");

$_prdt_list=substr($_COOKIE[ViewProduct],1,-1);	//(,��ǰ�ڵ�1,��ǰ�ڵ�2,��ǰ�ڵ�3,) ��������
$prdt_list=explode(",",$_prdt_list);
$prdt_no=count($prdt_list);
if(strlen($prdt_no)==0) {
	$prdt_no=0;
}

$tmp_product="";
for($i=0;$i<$prdt_no;$i++){
	$tmp_product.="'".$prdt_list[$i]."',";
}

unset($productall);
$tmp_product=substr($tmp_product,0,-1);
$sql = "SELECT productcode,productname,tinyimage,quantity,social_chk FROM tblproduct ";
$sql.= "WHERE productcode IN (".$tmp_product.") ";
$sql.= "ORDER BY FIELD(productcode,".$tmp_product.") ";
$result=mysql_query($sql,get_db_conn());
$jj=0;
while($row=mysql_fetch_object($result)) {
	$productall[$jj]["code"]=$row->productcode;
	$productall[$jj]["name"]=$row->productname;
	$productall[$jj]["image"]=$row->tinyimage;
	$productall[$jj]["quantity"]=$row->quantity;
	$productall[$jj]["social_chk"]=$row->social_chk;
	$jj++;
}

mysql_free_result($result);
$prdt_body = "";
if($design=="U") {	//����������
	unset($viewprdt);
	for($i=0;$i<count($productall);$i++) {
		$strlist1 = "<table border=0 cellpadding=0 cellspacing=0>";
		$strlist1.= "<col width=100%></col><col widht=1></col>";
		$strlist1.= "<tr id=\"V".$productall[$i]["code"]."\" ";
		if(substr($productall[$i]["code"],0,3)!='999' && $productall[$i]["social_chk"] !="Y") {
			$strlist1.= "onmouseover=\"quickfun_show(this,'V".$productall[$i]["code"]."','','row')\" onmouseout=\"quickfun_show(this,'V".$productall[$i]["code"]."','none')\"";
		}
		$strlist1.= ">";
		$strlist1.= "<td align=center style=\"padding-top:5\">";
		$strlist1.= "	<a href=\"".$Dir.FrontDir."productdetail.php?productcode=".$productall[$i]["code"]."\" onMouseOver=\"window.status='�ֱ� �� ��ǰ';return true;\">";
		if (strlen($productall[$i]["image"])>0 && file_exists($Dir.DataDir."shopimages/product/".$productall[$i]["image"])) {
			$strlist1.= "<img src=\"".$Dir.DataDir."shopimages/product/".$productall[$i]["image"]."\" border=1 style='border-color=#999999' width=60 height=60>";
		} else {
			$strlist1.= "<img src=\"".$Dir."images/common/noimage.gif\" border=0 width=60 height=60>";
		}
		$strlist1.= "</a></td>";
		$strlist1.= "	<td style=\"position:relative;\">";
		$strlist2 = ($_data->ETCTYPE["QUICKTOOLS"]!="Y"?"quickfun_return('".$Dir."','V','".$productall[$i]["code"]."','".($productall[$i]["quantity"]=="0"?"":"1")."','row')":"\"\"");
		$strlist3 = "</td>";
		$strlist3.= "</tr>";
		$strlist3.= "</table>";

		if($prdt_cnt > $i) {
			$strlist_content[$i] = $strlist1."<script>".str_replace("quickfun_return", "quickfun_write", $strlist2)."</script>".$strlist3;
		}

		$prdt_script.= "var objnplist=new ObjNPList();\n";
		$prdt_script.= "objnplist.num=\"".$i."\";\n";
		$prdt_script.= "objnplist.html1=\"".addslashes($strlist1)."\";\n";
		$prdt_script.= "objnplist.html2=".$strlist2.";\n";
		$prdt_script.= "objnplist.html3=\"".addslashes($strlist3)."\";\n";
		$prdt_script.= "recent_list[".$i."]=objnplist;\n";
		$prdt_script.= "objnplist=null;\n";
	}

	$viewprdt.= "<table border=0 cellpadding=0 cellspacing=0>\n";
	for($j=0; $j<$prdt_cnt; $j++) {
		$viewprdt.= "<tr>";
		$viewprdt.= "	<td id=\"recent_idx".$j."\">".$strlist_content[$j]."</td>\n";
		$viewprdt.= "</tr>";
	}
	$viewprdt.= "	</table>\n";

	$BTN_UP = "javascript:updown_click('up');";
	$BTN_DN = "javascript:updown_click('down');";

	$pattern=array("(\[DIR\])","(\[NEW\])","(\[UP\])","(\[DOWN\])");
	$replace=array($Dir,$viewprdt,$BTN_UP,$BTN_DN);

	$prdt_body=preg_replace($pattern,$replace,$content);
} else {	//���ø� ������
	for($i=0;$i<count($productall);$i++) {
		$strlist1 = "<table border=0 cellpadding=0 cellspacing=0 width=100% style=\"table-layout:fixed\">";
		$strlist1.= "<col width=100%></col><col widht=1></col>";
		$strlist1.= "<tr id=\"V".$productall[$i]["code"]."\" ";
		if(substr($productall[$i]["code"],0,3)!='999' && $productall[$i]["social_chk"] !="Y") {
			$strlist1.= "onmouseover=\"quickfun_show(this,'V".$productall[$i]["code"]."','','row')\" onmouseout=\"quickfun_show(this,'V".$productall[$i]["code"]."','none')\"";
		}
		$strlist1.= "><td align=center style=\"padding-top:2\">";
		$strlist1.= "<a href=\"".$Dir.FrontDir."productdetail.php?productcode=".$productall[$i]["code"]."\" onMouseOver=\"window.status='�ֱ� �� ��ǰ';return true;\">";
		if (strlen($productall[$i]["image"])>0 && file_exists($Dir.DataDir."shopimages/product/".$productall[$i]["image"])) {
			$strlist1.= "<img src=\"".$Dir.DataDir."shopimages/product/".$productall[$i]["image"]."\" border=1 style='border-color=#D1D1D1' width=60 height=60>";
		} else {
			$strlist1.= "<img src=\"".$Dir."images/common/noimage.gif\" border=0 width=60 height=60>";
		}
		$strlist1.= "</a></td>";
		$strlist1.= "<td style=\"position:relative;padding-top:2;\">";
		$strlist2= ($_data->ETCTYPE["QUICKTOOLS"]!="Y"?"quickfun_return('".$Dir."','V','".$productall[$i]["code"]."','".($productall[$i]["quantity"]=="0"?"":"1")."','row')":"\"\"");
		$strlist3= "</td>";
		$strlist3.= "</tr>";
		$strlist3.= "</table>";

		if($prdt_cnt > $i) {
			$strlist_content[$i] = $strlist1."<script>".str_replace("quickfun_return", "quickfun_write", $strlist2)."</script>".$strlist3;
		}

		$prdt_script.= "var objnplist=new ObjNPList();\n";
		$prdt_script.= "objnplist.num=\"".$i."\";\n";
		$prdt_script.= "objnplist.html1=\"".addslashes($strlist1)."\";\n";
		$prdt_script.= "objnplist.html2=".$strlist2.";\n";
		$prdt_script.= "objnplist.html3=\"".addslashes($strlist3)."\";\n";
		$prdt_script.= "recent_list[".$i."]=objnplist;\n";
		$prdt_script.= "objnplist=null;\n";
	}

	$prdt_body.= "<table border=0 cellpadding=0 cellspacing=0 width=80 style=\"table-layout:fixed\">\n";
	$prdt_body.= "<tr>\n";
	$prdt_body.= "	<td><img src=".$Dir."images/common/newproductview/newproductview".$design."_top.gif style=\"cursor:hand;\" onclick=\"RightNewprdtClose();\"></td>\n";
	$prdt_body.= "</tr>\n";
	$prdt_body.= "<tr>\n";
	$prdt_body.= "	<td align=center background=".$Dir."images/common/newproductview/newproductview".$design."_bg.gif>\n";
	$prdt_body.= "	<table border=0 cellpadding=0 cellspacing=0 width=80 style=\"table-layout:fixed\">\n";
	$prdt_body.= "	<tr><td height=3></td></tr>\n";
	$prdt_body.= "	<tr>\n";
	$prdt_body.= "		<td align=center><a href=\"javascript:updown_click('up');\"><img src=\"".$Dir."images/common/newproductview/btn_plus".$design.".gif\" border=\"0\"></a></td>\n";
	$prdt_body.= "	</tr>\n";
	$prdt_body.= "	<tr><td height=3></td></tr>\n";
	for($j=0; $j<$prdt_cnt; $j++) {
		$prdt_body.= "	<tr>\n";
		$prdt_body.= "		<td id=\"recent_idx".$j."\" >".$strlist_content[$j]."</td>\n";
		$prdt_body.= "	</tr>\n";
	}
	$prdt_body.= "	<tr><td height=3></td></tr>\n";
	$prdt_body.= "	<tr>\n";
	$prdt_body.= "		<td align=center><a href=\"javascript:updown_click('down');\"><img src=\"".$Dir."images/common/newproductview/btn_minus".$design.".gif\" border=\"0\"></a></td>\n";
	$prdt_body.= "	</tr>\n";
	$prdt_body.= "	<tr><td height=3></td></tr>\n";
	$prdt_body.= "	</table>\n";
	$prdt_body.= "	</td>\n";
	$prdt_body.= "</tr>\n";
	$prdt_body.= "<tr>\n";
	$prdt_body.= "	<td><img src=".$Dir."images/common/newproductview/newproductview".$design."_bottom.gif></td>\n";
	$prdt_body.= "</tr>\n";
	$prdt_body.= "</table>\n";
}
echo $prdt_body;
ob_end_flush();
?>
var recent_list = new Array();
var recentnum=0;
var prdt_cnt = <?=$prdt_cnt?>;

function updown_click(updownValue)
{
	if(prdt_cnt < recent_list.length) {
		if(updownValue == "up") {
			if(recentnum>0 && recent_list[recentnum-1]) {
				recentnum=recentnum-1;
			}
		} else {
			if(recent_list[recentnum+1] && recent_list[recentnum+prdt_cnt]) {
				recentnum = recentnum+1;
			}
		}

		var j=recentnum;

		for(var i=0; i<prdt_cnt; i++)
		{
			if(recent_list[j] && document.all["recent_idx"+i]) {
				document.all["recent_idx"+i].innerHTML=recent_list[j].html1+recent_list[j].html2+recent_list[j].html3;
			} else if(document.all["recent_idx"+i]) {
				document.all["recent_idx"+i].innerHTML="";
			}
			j++;
		}
	}
}

function ObjNPList() {
	var argv = ObjNPList.arguments;
	var argc = ObjNPList.arguments.length;

	//Property ����
	this.classname		= "ObjNPList";
	this.debug			= false;
	this.num			= new String((argc > 0) ? argv[0] : "0");
	this.html1			= new String((argc > 1) ? argv[1] : "");
	this.html2			= new String((argc > 2) ? argv[1] : "");
	this.html3			= new String((argc > 3) ? argv[1] : "");
}
<?
echo $prdt_script;
?>