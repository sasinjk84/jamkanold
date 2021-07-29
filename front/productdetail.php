<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

include_once($Dir."lib/ext/product_func.php");
include_once($Dir."lib/ext/member_func.php");
include_once($Dir."lib/ext/coupon_func.php");

include_once($Dir."lib/class/rentproduct.php");

@include_once($Dir."_NaverCheckout/naverCheckout.class.php"); //네이버 체크아웃 연동
$checkoutObj = new naverCheckout();
$checkoutObj->checkDetailRedirect();

//네이버 체크아웃 연동 2016-01-07 Seul (모바일쪽 상품 상세화면 테스트 화면으로 변경)
if(eregi("PSP|Symbian|Nokia|LGT|mobile|Mobile|Mini|iphone|SAMSUNG|Windows Phone|Android|Galaxy", $_SERVER['HTTP_USER_AGENT']) AND $_SESSION[chk_BW]!="none") {
	echo("<script>location.replace('".$Dir."m/productdetail_tab01.php?productcode=".$_REQUEST['productcode']."');</script>");
}

//sns 홍보를 통해 접근
$sid = $_REQUEST["sid"];
$sql = "SELECT id,pcode FROM tblsnsproduct WHERE code='".$sid."'";
$result=mysql_query($sql,get_db_conn());
if($row=mysql_fetch_object($result)) {
	$sell_memid = ($_ShopInfo->getMemid() != $row->id)? $row->id:"";
}
mysql_free_result($result);

$mode=$_REQUEST["mode"];
$coupon_code=$_REQUEST["coupon_code"];


$productcode=$_REQUEST["productcode"];

$prentinfo = rentProduct::read($productcode,$prentinfo);

if(!_array($prentinfo)) $prentinfo = false;


$tblcategorycodeResult = mysql_query("SELECT * FROM `tblcategorycode` WHERE `productcode` = '".$productcode."' AND `categorycode` = '".$_REQUEST["code"]."' ",get_db_conn());
if( mysql_num_rows( $tblcategorycodeResult ) ){
	$rcode=$_REQUEST["code"];
}

//가상카테고리 검증 시작
$virtype = false;
if(strlen($rcode) > 0){
	$vcodeA = substr($rcode,0,3);
	$vcodeB = (substr($rcode,3,3))? substr($rcode,3,3):'000';
	$vcodeC = (substr($rcode,6,3))? substr($rcode,6,3):'000';
	$vcodeD = (substr($rcode,9,3))? substr($rcode,9,3):'000';
}


$virCateSql = "SELECT type FROM tblproductcode WHERE codeA = '".$vcodeA."' AND codeB = '".$vcodeB."' AND codeC = '".$vcodeC."' AND codeD = '".$vcodeD."' ";
$virCateResult = mysql_query($virCateSql,get_db_conn());
$virCateRows = mysql_num_rows($virCateResult);
$virCateRow = mysql_fetch_object($virCateResult);

if($virCateRows>0 && (substr($virCateRow->type,0,1) == 'T')){
	$virtype= true;

}
//가상카테고리 검증 끝

if(strlen($rcode)==0 || $virtype) {
	$rcode=substr($productcode,0,12);
}

$code = '';
$likecode='';
for($i=0;$i<4;$i++){
	$tcode = substr($rcode,$i*3,3);
	if(strlen($tcode) != 3){
		$tcode = '000';
	}else{
		$likecode.=$tcode;
	}
	${'code'.chr(65+$i)} = $tcode;
	$code.=$tcode;
}

$sort=$_REQUEST["sort"];
$brandcode=$_REQUEST["brandcode"];

$selfcodefont_start = "<font class=\"prselfcode\">"; //진열코드 폰트 시작
$selfcodefont_end = "</font>"; //진열코드 폰트 끝

/* 상품평 관련 사용자 이름을 위해서 처리 */
$userloginname = 'Guest';
if(strlen($_ShopInfo->getMemid())>0) {
	$sql = "select name from tblmember WHERE id='".$_ShopInfo->getMemid()."' limit 1";
	if(false !== $res = mysql_query($sql,get_db_conn())){
		if(mysql_num_rows($res)) $userloginname = mysql_result($res,0,0);
		mysql_free_result($res);
	}
}

/*
function getBCodeLoc($brandcode,$code="",$color1="9E9E9E",$color2="9E9E9E") {
	global $_ShopInfo, $Dir;
	$sql = "SELECT brandname FROM tblproductbrand ";
	$sql.= "WHERE bridx='".$brandcode."' ";
	$result=mysql_query($sql,get_db_conn());
	$brow=mysql_fetch_object($result);

	if(strlen($code)>0) {
		$code_loc = "<A HREF=\"".$Dir.MainDir."main.php\"><FONT COLOR=\"".$color1."\">홈</FONT></A>&nbsp;&nbsp;<FONT COLOR=\"".$color1."\">></FONT>&nbsp;&nbsp;<A HREF=\"".$Dir.FrontDir."productblist.php?brandcode=".$brandcode."\"><FONT COLOR=\"".$color1."\">브랜드 : ".$brow->brandname."</FONT></A>";
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
			$code_loc.= " <FONT COLOR=\"".$color1."\">></FONT> ";
			if($code==$tmpcode) {
				$code_loc.="<A HREF=\"".$Dir.FrontDir."productblist.php?brandcode=".$brandcode."&code=".$tmpcode."\"><FONT COLOR=\"".$color2."\"><B>".$row->code_name."</B></FONT></A>";
			} else {
				$code_loc.="<A HREF=\"".$Dir.FrontDir."productblist.php?brandcode=".$brandcode."&code=".$tmpcode."\"><FONT COLOR=\"".$color1."\">".$row->code_name."</FONT></A>";
			}
			$code_loc.= $_tmp;
			$i++;
		}
		mysql_free_result($result);
	} else {
		$code_loc = "<A HREF=\"".$Dir.MainDir."main.php\"><FONT COLOR=\"".$color1."\">홈</FONT></A>&nbsp;<FONT COLOR=\"".$color1."\">></FONT>&nbsp;<A HREF=\"".$Dir.FrontDir."productblist.php?brandcode=".$brandcode."\"><FONT COLOR=\"".$color1."\"><B>브랜드 : ".$brow->brandname."</FONT></B></A>";
	}
	return $code_loc;
}
/*
function getCodeLoc($code,$color1="9E9E9E",$color2="9E9E9E") {
	global $_ShopInfo, $Dir;
	$code_loc = "<A HREF=\"".$Dir.MainDir."main.php\"><FONT COLOR=\"".$color1."\">홈</FONT></A> <FONT COLOR=\"".$color1."\">></FONT> ";
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
		$slink_list = (eregi("S",$row->type))? "gonggu_main.php":((substr($code,0,3)!="999")? "productlist.php":"productgift.php");
		if($code==$tmpcode) {
			$code_loc.="<A HREF=\"".$Dir.FrontDir.$slink_list."?code=".$tmpcode."\"><FONT COLOR=\"".$color2."\"><B>".$row->code_name."</B></FONT></A>";
		} else {
			$code_loc.="<A HREF=\"".$Dir.FrontDir.$slink_list."?code=".$tmpcode."\"><FONT COLOR=\"".$color1."\">".$row->code_name."</FONT></A>";
		}
		$code_loc.= $_tmp;
		$i++;
	}
	mysql_free_result($result);
	return $code_loc;
}*/


function getBCodeLoc($brandcode,$color1="9E9E9E",$color2="9E9E9E") {
	global $_ShopInfo, $Dir,$code;
	$naviitem = array();

	array_push($naviitem,"<A HREF=\"".$Dir.MainDir."main.php\"><span style=\"color:".$color1.";\">홈</span></A>&nbsp;");
	//<FONT COLOR=\"".$color1."\">></FONT>
	$sql = "SELECT brandname FROM tblproductbrand WHERE bridx='".$brandcode."' ";
	if(false === $result=mysql_query($sql,get_db_conn())) return '';
	if(mysql_num_rows($result) < 1)  return '';
	array_push($naviitem,"&nbsp;<A HREF=\"".$Dir.FrontDir."productblist.php?brandcode=".$brandcode."\"><span style=\"color:".$color1.";\">".mysql_result($result,0,0)."</span></A>&nbsp;");


	for($i=0;$i<4;$i++){
		$tmp = array();

		$getsub = ($GLOBALS['code'.chr(65+$i)] == '000' || empty($GLOBALS['code'.chr(65+$i)]));
		$tmp = getCategoryItems(substr($code,0,$i*3),true);

		if(is_array($tmp) && count($tmp) > 0 && count($tmp['items']) > 0){
			$str = '&nbsp;<select name="code'.chr(65+$i).'"  id="code'.chr(65+$i).'" onChange="javascript:chgNaviCode('.$i.')">';
			if($tmp['depth'] != $i){
				exit('System Error');
			}
			$sel = '';
			if($getsub)  $str .= '<option value="">-----------------</option>';
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
	return implode('&nbsp;<FONT COLOR="'.$color1.'">></FONT>',$naviitem);
}

function getCodeLoc($code,$color1="9E9E9E",$color2="9E9E9E") {
	global $_ShopInfo, $Dir,$code;
	$naviitem = array();
	array_push($naviitem,"<A HREF=\"".$Dir.MainDir."main.php\"><span style=\"color:".$color1.";\">홈</span></A>&nbsp;");
	//<FONT COLOR=\"".$color1."\">></FONT>

	for($i=0;$i<4;$i++){
		$tmp = array();

		$getsub = ($GLOBALS['code'.chr(65+$i)] == '000');
		$tmp = getCategoryItems(substr($code,0,$i*3),true);
		if(is_array($tmp) && count($tmp) > 0 && count($tmp['items']) > 0){
			$str = '&nbsp;<select  style="font-size: 13px;
    height: 30px;
    line-height: 30px;
    border: 1px solid #dddddd;
    color: #555555;" name="code'.chr(65+$i).'"  id="code'.chr(65+$i).'" onChange="javascript:chgNaviCode('.$i.')">';
			if($tmp['depth'] != $i){
				exit('System Error');
			}
			$sel = '';
			if($getsub)  $str .= '<option value="">-----------------</option>';
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
	return implode('&nbsp;<FONT COLOR="'.$color1.'">></FONT>',$naviitem);
}



$_cdata="";
$_pdata="";
if(strlen($productcode)==18) {
	$sql = "SELECT * FROM tblproductcode WHERE codeA='".$codeA."' AND codeB='".$codeB."' AND codeC='".$codeC."' AND codeD='".$codeD."' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$_cdata=$row;

		// 미리보기
		if( @!preg_match( 'U', $_cdata->detail_type ) AND $preview===true ) {
			$_cdata->detail_type = $_cdata->detail_type."U";
		}

		if($row->group_code=="NO") {	//숨김 분류
			echo "<html></head><body onload=\"alert('판매가 종료된 상품입니다.');location.href='".$Dir.MainDir."main.php';\"></body></html>";exit;
			//} else if($row->group_code=="ALL" && strlen($_ShopInfo->getMemid())==0) {	//회원만 접근가능
		} else if(strlen($row->group_code)>0 && strlen($_ShopInfo->getMemid())==0) {	//회원만 접근가능
			Header("Location:".$Dir.FrontDir."login.php?chUrl=".getUrl());
			exit;
			//} else if(strlen($row->group_code)>0 && $row->group_code!="ALL" && $row->group_code!=$_ShopInfo->getMemgroup()) {	//그룹회원만 접근
		} else if(strlen($row->group_code)>0 && strpos($row->group_code,$_ShopInfo->getMemgroup())===false) {	//그룹회원만 접근
			echo "<html></head><body onload=\"alert('해당 분류의 접근 권한이 없습니다.');history.go(-1);\"></body></html>";exit;
		}

		//Wishlist 담기
		if($mode=="wishlist") {
			if(strlen($_ShopInfo->getMemid())==0) {	//비회원
				echo "<html></head><body onload=\"alert('로그인을 하셔야 본 서비스를 이용하실 수 있습니다.');location.href='".$Dir.FrontDir."login.php?chUrl=".getUrl()."';\"></body></html>";exit;
			}
			$sql = "SELECT COUNT(*) as totcnt FROM tblwishlist WHERE id='".$_ShopInfo->getMemid()."' ";
			$result2=mysql_query($sql,get_db_conn());
			$row2=mysql_fetch_object($result2);
			$totcnt=$row2->totcnt;
			mysql_free_result($result2);
			$maxcnt=20;
			if($totcnt>=$maxcnt) {
				$sql = "SELECT b.productcode ";
				$sql.= "FROM tblwishlist a, tblproduct b ";
				$sql.= "LEFT OUTER JOIN tblproductgroupcode c ON b.productcode=c.productcode ";
				$sql.= "WHERE a.id='".$_ShopInfo->getMemid()."' AND a.productcode=b.productcode ";
				$sql.= "AND b.display='Y' ";
				$sql.= "AND (b.group_check='N' OR c.group_code LIKE '%".$_ShopInfo->getMemgroup()."%') ";
				$sql.= "GROUP BY b.productcode ";

				$result2=mysql_query($sql,get_db_conn());
				$i=0;
				$wishprcode="";
				while($row2=mysql_fetch_object($result2)) {
					$wishprcode.="'".$row2->productcode."',";
					$i++;
				}
				mysql_free_result($result2);
				$totcnt=$i;
				$wishprcode=substr($wishprcode,0,-1);
				if(strlen($wishprcode)>0) {
					$sql = "DELETE FROM tblwishlist WHERE id='".$_ShopInfo->getMemid()."' AND productcode NOT IN (".$wishprcode.") ";
					mysql_query($sql,get_db_conn());
				}
			}
			if($totcnt<$maxcnt) {
				$sql = "SELECT COUNT(*) as cnt FROM tblwishlist WHERE id='".$_ShopInfo->getMemid()."' AND productcode='".$productcode."' ";
				$result2=mysql_query($sql,get_db_conn());
				$row2=mysql_fetch_object($result2);
				$cnt=$row2->cnt;
				mysql_free_result($result2);
				if($cnt>0) {
					echo "<html></head><body onload=\"alert('WishList에 이미 등록된 상품입니다.');history.go(-1);\"></body></html>";exit;
				} else {
					$sql = "INSERT tblwishlist SET ";
					$sql.= "id			= '".$_ShopInfo->getMemid()."', ";
					$sql.= "productcode	= '".$productcode."', ";
					$sql.= "date		= '".date("YmdHis")."' ";
					mysql_query($sql,get_db_conn());
					echo "<html></head><body onload=\"alert('WishList에 해당 상품을 등록하였습니다.');history.go(-1);\"></body></html>";exit;
				}
			} else {
				echo "<html></head><body onload=\"alert('WishList에는 ".$maxcnt."개 까지만 등록이 가능합니다.\\n\\nWishList에서 다른 상품을 삭제하신 후 등록하시기 바랍니다.');history.go(-1);\"></body></html>";exit;
			}
		}
	} else {
		echo "<html></head><body onload=\"alert('해당 분류가 존재하지 않습니다.');location.href='".$Dir.MainDir."main.php';\"></body></html>";exit;
	}
	mysql_free_result($result);
/*
	$sql = "SELECT a.* ";
	$sql.= "FROM tblproduct AS a ";
	*/
	$sql = "SELECT a.*,d.reserve,d.over_reserve ";
	$sql.= "FROM tblproduct AS a ";
	
	$sql .= " left join tblmemberreserve d on (d.productcode=a.productcode and d.group_code='".$_ShopInfo->getMemgroup()."' AND d.discountYN='Y') ";
	$sql.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
	//소셜
	if(eregi("S",$_cdata->type)) {
		$sql = "SELECT a.*, c.* ";
		$sql.= "FROM tblproduct AS a ";
		$sql.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
		$sql.= "LEFT OUTER JOIN tblproduct_social c ON a.productcode=c.pcode ";
	}
	$sql.= "WHERE a.productcode='".$productcode."' AND a.display='Y' ";
	$sql.= "AND (a.group_check='N' OR b.group_code LIKE '%".$_ShopInfo->getMemgroup()."%') ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$_pdata=$row;

		//위탁상품인경우 위탁받은 업체정보 노출
		$sql = "SELECT trust_vender,istrust FROM rent_product ";
		$sql.= "WHERE pridx='".$_pdata->pridx."' ";
		$presult=mysql_query($sql,get_db_conn());
		$prow=mysql_fetch_object($presult);
		if($prow->istrust=="0") $_pdata->vender = $prow->trust_vender;

		$_pdata->brandcode = $_pdata->brand;
		$_pdata->brand = $brow->brandname;

		$sql = "SELECT * FROM tblproductbrand ";
		$sql.= "WHERE bridx='".$_pdata->brand."' ";
		$bresult=mysql_query($sql,get_db_conn());
		$brow=mysql_fetch_object($bresult);
		$_pdata->brandcode = $_pdata->brand;
		$_pdata->brand = $brow->brandname;

		mysql_free_result($result);

		if($_pdata->assembleuse=="Y") {
			$sql = "SELECT * FROM tblassembleproduct ";
			$sql.= "WHERE productcode='".$productcode."' ";
			$result=mysql_query($sql,get_db_conn());
			if($row=@mysql_fetch_object($result)) {
				$_adata=$row;
				mysql_free_result($result);
				$assemble_list_pridx = str_replace("","",$_adata->assemble_list);

				if(strlen($assemble_list_pridx)>0) {
					$sql = "SELECT pridx,productcode,productname,sellprice,quantity,tinyimage FROM tblproduct ";
					$sql.= "WHERE pridx IN ('".str_replace(",","','",$assemble_list_pridx)."') ";
					$sql.= "AND assembleuse!='Y' ";
					$sql.= "AND display='Y' ";
					$result=mysql_query($sql,get_db_conn());
					while($row=@mysql_fetch_object($result)) {
						$_acdata[$row->pridx] = $row;
					}
					mysql_free_result($result);
				}
			}
		}
		$_pdata->checkAbles = _getEtcImg($_pdata->productcode,'val'); // 사용 불가 항목 관련 내용 추가
	} else {
		echo "<html></head><body onload=\"alert('해당 상품 정보가 존재하지 않습니다.');history.go(-1);\"></body></html>";exit;
	}
} else {
	echo "<html></head><body onload=\"alert('해당 상품 정보가 존재하지 않습니다.');location.href='".$Dir.MainDir."main.php'\"></body></html>";exit;
}

// 렌탈 아이콘 상품 타입 Rent/Sale
$rentalIcon = rentalIcon($row->rental);

if($mode=="coupon" && strlen($coupon_code)==8 && strlen($productcode)==18) {	//쿠폰 발급
	if(strlen($_ShopInfo->getMemid())==0) {	//비회원
		echo "<html></head><body onload=\"alert('로그인 후 쿠폰 다운로드가 가능합니다.');location.href='".$Dir.FrontDir."login.php?chUrl=".getUrl()."';\"></body></html>";exit;
	} else {
		$sql = "SELECT * FROM tblcouponinfo ";
		if($_pdata->vender>0) {
			$sql.= "WHERE (vender='0' OR vender='".$_pdata->vender."') ";
		} else {
			$sql.= "WHERE vender='0' ";
		}
		$sql.= "AND coupon_code='".$coupon_code."' ";
		$sql.= "AND display='Y' AND issue_type='Y' AND detail_auto='Y' ";
		$sql.= "AND (date_end>".date("YmdH")." OR date_end='') ";
		//$sql.= "AND ((use_con_type2='Y' AND productcode IN ('ALL','".substr($code,0,3)."000000000','".substr($code,0,6)."000000','".substr($code,0,9)."000','".$code."','".$productcode."')) OR (use_con_type2='N' AND productcode NOT IN ('".substr($code,0,3)."000000000','".substr($code,0,6)."000000','".substr($code,0,9)."000','".$code."','".$productcode."'))) ";
		$sql .= " and coupon_code='".$coupon_code."'";
		$result=mysql_query($sql,get_db_conn());


		if($row=mysql_fetch_object($result)) {
			if($row->issue_tot_no>0 && $row->issue_tot_no<$row->issue_no+1) {
				$onload="<script>alert(\"모든 쿠폰이 발급되었습니다.\");</script>";
				//} else {
			}else if(checkCouponUasble($row->productcode,$_pdata->productcode,$row->use_con_type2)){
				$date=date("YmdHis");
				if($row->date_start>0) {
					$date_start=$row->date_start;
					$date_end=$row->date_end;
				} else {
					$date_start = substr($date,0,10);
					$date_end = date("Ymd",mktime(0,0,0,substr($date,4,2),substr($date,6,2)+abs($row->date_start),substr($date,0,4)))."23";
				}
				$sql = "INSERT tblcouponissue SET ";
				$sql.= "coupon_code	= '".$coupon_code."', ";
				$sql.= "id			= '".$_ShopInfo->getMemid()."', ";
				$sql.= "date_start	= '".$date_start."', ";
				$sql.= "date_end	= '".$date_end."', ";
				$sql.= "date		= '".$date."' ";
				mysql_query($sql,get_db_conn());
				if(!mysql_errno()) {
					$sql = "UPDATE tblcouponinfo SET issue_no = issue_no+1 ";
					$sql.= "WHERE coupon_code = '".$coupon_code."'";
					mysql_query($sql,get_db_conn());

					$onload="<script>alert(\"해당 쿠폰 발급이 완료되었습니다.\\n\\n상품 주문시 해당 쿠폰을 사용하실 수 있습니다.\");</script>";
				} else {
					if($row->repeat_id=="Y") {	//동일인 재발급이 가능하다면,,,,
						$sql = "UPDATE tblcouponissue SET ";
						if($row->date_start<=0) {
							$sql.= "date_start	= '".$date_start."', ";
							$sql.= "date_end	= '".$date_end."', ";
						}
						$sql.= "used		= 'N' ";
						$sql.= "WHERE coupon_code='".$coupon_code."' ";
						$sql.= "AND id='".$_ShopInfo->getMemid()."' ";
						mysql_query($sql,get_db_conn());
						$onload="<script>alert(\"해당 쿠폰 발급이 완료되었습니다.\\n\\n상품 주문시 해당 쿠폰을 사용하실 수 있습니다.\");</script>";
					} else {
						$onload="<script>alert(\"이미 쿠폰을 발급받으셨습니다.\\n\\n해당 쿠폰은 재발급이 불가능합니다.\");</script>";
					}
				}
			}else {
				$onload="<script>alert(\"해당 쿠폰은 사용 가능한 쿠폰이 아닙니다.\");</script>";
			}
		} else {
			$onload="<script>alert(\"해당 쿠폰은 사용 가능한 쿠폰이 아닙니다.\");</script>";
		}
		mysql_free_result($result);
	}
}

$ref=$_REQUEST["ref"];
if (strlen($ref)==0) {
	$ref=strtolower(ereg_replace("http://","",getenv("HTTP_REFERER")));
	if(strpos($ref,"/") != false) $ref=substr($ref,0,strpos($ref,"/"));
}

if(strlen($ref)>0 && strlen($_ShopInfo->getRefurl())==0) {
	$sql2="SELECT * FROM tblpartner WHERE url LIKE '%".$ref."%' ";
	$result2 = mysql_query($sql2,get_db_conn());
	if ($row2=mysql_fetch_object($result2)) {
		mysql_query("UPDATE tblpartner SET hit_cnt = hit_cnt+1 WHERE url = '".$row2->url."'",get_db_conn());
		$_ShopInfo->setRefurl($row2->id);
		$_ShopInfo->Save();
	}
	mysql_free_result($result2);
}

if(strlen($productcode)==18) {
	$viewproduct=$_COOKIE["ViewProduct"];
	if(strrpos(" ".$viewproduct,",".$productcode.",")==0) {
		if(strlen($viewproduct)==0) {
			$viewproduct=",".$productcode.",";
		} else {
			$viewproduct=",".$productcode.$viewproduct;
		}
	} else {
		$viewproduct=str_replace(",".$productcode.",",",",$viewproduct);
		$viewproduct=",".$productcode.$viewproduct;
	}
	$viewproduct=substr($viewproduct,0,571);
	@setcookie("ViewProduct",$viewproduct,0,"/".RootPath);
}


//상품 상세 공통 이벤트 관리
//if(strlen($_cdata->detail_type)==5) {	//개별디자인이 아닐 경우
$sql = "SELECT * FROM ".$designnewpageTables." WHERE type='detailimg' ";
$result=mysql_query($sql,get_db_conn());
if($row=mysql_fetch_object($result)) {
	$row->body=str_replace("[DIR]",$Dir,$row->body);
	$design_type=$row->code;
	$detailimg_eventloc=$row->leftmenu;
	$detailimg_body="<table border=0 cellpadding=0 cellspacing=0>\n";
	if($design_type=="1") {	//이미지 타입
		$detailimg_body.="<tr><td align=center><img src=\"".$Dir.DataDir."shopimages/etc/".$row->filename."\" border=0></td></tr>\n";
	} else if($design_type=="2") {	//html 타입
		$detailimg_body.="<tr><td align=center>".$row->body."</td></tr>\n";
	}
	$detailimg_body.="</table>\n";
}
mysql_free_result($result);
//}

//추천관련상품

/* coll_loc => 0:사용안함, 1:상세화면 상단 위치, 2:상세화면 하단 위치, 3:상세화면 오른쪽 위치 */
if($_data->coll_loc>0) {
/*
	$sql = "SELECT collection_list FROM tblcollection ";
	$sql.= "WHERE (productcode='".substr($code,0,3)."000000000' ";
	$sql.= "OR productcode='".substr($code,0,6)."000000' OR productcode='".substr($code,0,9)."000' ";
	$sql.= "OR productcode='".substr($code,0,12)."' OR productcode='".$productcode."') ";
	$sql.= "ORDER BY productcode DESC LIMIT 1 ";
	$result=mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
	$collection_list=$row->collection_list;
	mysql_free_result($result);
*/
	$collection_list="";
	$sql = "SELECT ordercode FROM tblorderproduct ";
	$sql.= "WHERE productcode='".$productcode."'";
	
	$result=mysql_query($sql,get_db_conn());
	while($row=mysql_fetch_object($result)){
		$collection_list.=$row->ordercode.",";
		
	}
	$collection_list=substr($collection_list,0,-1);
	mysql_free_result($result);

	if(strlen($collection_list)>0) {
		/*
		$collection=ereg_replace(",","','",$collection_list);
		$sql = "SELECT a.productcode,a.productname,a.sellprice,a.tinyimage,a.etctype,a.reserve,a.reservetype,a.consumerprice,a.option_price,a.tag,a.quantity,a.selfcode ";
		$sql.= "FROM tblproduct AS a ";
		$sql.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
		$sql.= "WHERE a.productcode IN ('".$collection."') ";
		$sql.= "AND a.display='Y' AND a.productcode!='".$productcode."' ";
		$sql.= "AND (a.group_check='N' OR b.group_code='".$_ShopInfo->getMemgroup()."') ";
		$sql.= "ORDER BY FIELD(a.productcode,'".$collection."') LIMIT ".$_data->coll_num;
		*/
		$collection=ereg_replace(",","','",$collection_list);
		$sql = "SELECT productcode FROM tblorderproduct ";
		$sql.= "WHERE ordercode in ('".$collection."') AND (productcode<>'".$productcode."' AND productcode<>'99999999990X')";
		$result=mysql_query($sql,get_db_conn());
		while($row=mysql_fetch_object($result)){
			$prd_list.=$row->productcode.",";
		}
		$prd_list=substr($prd_list,0,-1);
		$prd_list=ereg_replace(",","','",$prd_list);

		$sql = "SELECT a.productcode,a.productname,a.sellprice,a.tinyimage,a.etctype,a.reserve,a.reservetype,a.consumerprice,a.option_price,a.tag,a.quantity,a.selfcode ";
		$sql.= "FROM tblproduct AS a ";
		$sql.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
		$sql.= "WHERE a.productcode IN ('".$prd_list."') ";
		$sql.= "AND a.display='Y' AND a.productcode!='".$productcode."' ";
		$sql.= "AND (a.group_check='N' OR b.group_code='".$_ShopInfo->getMemgroup()."') ";
		$sql.= "ORDER BY FIELD(a.productcode,'".$prd_list."') LIMIT ".$_data->coll_num;
		$result=mysql_query($sql,get_db_conn());
		$collcnt=mysql_num_rows($result);
		if($collcnt<$_data->coll_num) $collcnt=$_data->coll_num;

		$collection_body="<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" ";

		if($_data->coll_loc=="3") {
			$collection_body.="width=\"100%\" style=\"table-layout:fixed\">\n";
			$collection_body.="<tr>\n";
			$collection_body.="	<td style=\"padding:5;border:#dddddd solid 1\">\n";
			$collection_body.="	<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"table-layout:fixed\">\n";
		} else {
			$collection_body.="width=100%>";
			$collection_body.="<tr>\n";
			$collection_body.="	<td width=100% style=\"padding:5\">\n";
			$collection_body.="	<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"table-layout:fixed\">\n";
			$collection_body.="	<tr>\n";
		}
		$tag_detail_count=2;
		$i=0;
		while($row=mysql_fetch_object($result)) {
			if($_data->coll_loc=="3") {
				if($i>0) {
					$collection_body.="<tr><td height=\"3\"></td></tr>\n";
					$collection_body.="<tr>\n";
					$collection_body.="	<td align=\"center\">";
					$collection_body.="	<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"90%\" style=\"table-layout:fixed\"><tr><td height=\"1\" bgcolor=\"#dddddd\"></td></tr></table>\n";
					$collection_body.="	</td>\n";
					$collection_body.="</tr>\n";
					$collection_body.="<tr><td height=\"5\"></td></tr>\n";
				} else {
					$collection_body.="<tr><td height=\"3\"></td></tr>\n";
				}
				$collection_body.="<tr>\n";
				$collection_body.="	<td align=center valign=\"top\">\n";
				$collection_body.="	<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"table-layout:fixed\" id=\"R".$row->productcode."\" onmouseover=\"quickfun_show(this,'R".$row->productcode."','','row')\" onmouseout=\"quickfun_show(this,'R".$row->productcode."','none')\">\n";
				$collection_body.="<colgroup><col width=75><col width=1><col></colgroup>\n";
			} else {
				if($i>0) $collection_body.="<td width=\"5\" nowrap></td>\n";
				$collection_body.="	<td width=\"".ceil(100/$collcnt)."%\" valign=\"top\">";
				$collection_body.="	<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"table-layout:fixed\" id=\"R".$row->productcode."\" onmouseover=\"quickfun_show(this,'R".$row->productcode."','')\" onmouseout=\"quickfun_show(this,'R".$row->productcode."','none')\">\n";
			}

			$collection_body.="	<tr>\n";
			$collection_body.="		<td align=\"center\" valign=middle>\n";
			$collection_body.= "	<A HREF=\"".$Dir.FrontDir."productdetail.php?productcode=".$row->productcode."\" onmouseover=\"window.status='상품상세조회';return true;\" onmouseout=\"window.status='';return true;\">";
			if (strlen($row->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$row->tinyimage)==true) {
				$collection_body.= "<img src=\"".$Dir.DataDir."shopimages/product/".urlencode($row->tinyimage)."\" border=0 ";
				$width = getimagesize($Dir.DataDir."shopimages/product/".$row->tinyimage);
				if($width[0]>$width[1]) $collection_body.="width=120";
				else $collection_body.="height=120";
			} else {
				$collection_body.= "<img src=\"".$Dir."images/no_img.gif\" width=\"70\" border=\"0\" align=\"center\"";
			}
			$collection_body.= "		></A></td>";
			//$collection_body.="		\n";

			if($_data->coll_loc!="3") {
				$collection_body.="	</tr>\n";
				$collection_body.="	<tr><td height=\"5\"></td></tr>\n";
				$collection_body.= "<tr><td height=\"3\" style=\"position:relative;\">".($_data->ETCTYPE["QUICKTOOLS"]!="Y"?"<script>quickfun_write('".$Dir."','R','".$row->productcode."','".($row->quantity=="0"?"":"1")."')</script>":"")."</td></tr>\n";
				$collection_body.="	<tr>";
			} else {
				$collection_body.="	<td style=\"position:relative;\">".($_data->ETCTYPE["QUICKTOOLS"]!="Y"?"<script>quickfun_write('".$Dir."','R','".$row->productcode."','".($row->quantity=="0"?"":"1")."','row')</script>":"")."</td>";
			}

			$collection_body.="		<td ".($_data->coll_loc!="3"?"align=\"center\"":"")." valign=middle style=\"word-break:break-all;\">";
			$collection_body.="		<A HREF=\"".$Dir.FrontDir."productdetail.php?productcode=".$row->productcode."\" onmouseover=\"window.status='상품상세조회';return true;\" onmouseout=\"window.status='';return true;\"><FONT class=\"prname\">".viewproductname($row->productname,$row->etctype,$row->selfcode)."</FONT></A>";

			if($row->consumerprice!=0) {
				if($_data->coll_loc!="3") {
					$collection_body.="		</td>\n";
					$collection_body.="	</tr>\n";
					$collection_body.="	<tr>\n";
					$collection_body.="		<td align=\"center\" style=\"word-break:break-all;\" class=\"prconsumerprice\">";
				} else {
					$collection_body.="		<BR>";
				}

				$collection_body.= "<img src=\"".$Dir."images/common/won_icon2.gif\" border=\"0\" style=\"margin-right:2px;\"><strike>".number_format($row->consumerprice)."</strike>원";
			}

			if($_data->coll_loc!="3") {
				$collection_body.="		</td>\n";
				$collection_body.="	</tr>\n";
				$collection_body.="	<tr>\n";
				$collection_body.="		<td align=\"center\">";
			} else {
				$collection_body.="		<BR>";
			}
			$collection_body.="		<FONT class=\"prprice\">";
			if($dicker=dickerview($row->etctype,number_format($row->sellprice)."원",1)) {
				$collection_body.= $dicker;
			} else if(strlen($_data->proption_price)==0) {
				$collection_body.= "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 style=\"margin-right:2px;\">".number_format($row->sellprice)."원";
				if (strlen($row->option_price)!=0) $collection_body.="(기본가)";
			} else {
				$collection_body.="<img src=\"".$Dir."images/common/won_icon.gif\" border=0 style=\"margin-right:2px;\">";
				if (strlen($row->option_price)==0) $collection_body.= number_format($row->sellprice)."원";
				else $collection_body.= ereg_replace("\[PRICE\]",number_format($row->sellprice),$_data->proption_price);
			}
			if ($row->quantity=="0") $collection_body.= soldout();

			if($row->reserve!=0) {
				if($_data->coll_loc!="3") {
					$collection_body.="		</font></td>\n";
					$collection_body.="	</tr>\n";
					$collection_body.="	<tr>\n";
					$collection_body.="		<td align=\"center\" style=\"word-break:break-all;\" class=\"prreserve\">";
				} else {
					$collection_body.="		<BR>";
				}
				$reserveconv=getReserveConversion($row->reserve,$row->reservetype,$row->sellprice,"Y");
				$collection_body.= "<img src=\"".$Dir."images/common/reserve_icon.gif\" border=\"0\" style=\"margin-right:2px;\">".number_format($reserveconv)."원";
			}

			$taglist=explode(",",$row->tag);
			$jj=0;
			for($ii=0;$ii<$tag_detail_count;$ii++) {
				$taglist[$ii]=ereg_replace("(<|>)","",$taglist[$ii]);
				if(strlen($taglist[$ii])>0) {
					if($jj==0) {
						if($_data->coll_loc!="3") {
							$collection_body.="		</font></td>\n";
							$collection_body.="	</tr>\n";
							$collection_body.="	<tr>\n";
							$collection_body.="		<td align=\"center\" style=\"word-break:break-all;\">";
						} else {
							$collection_body.="		<BR>";
						}
						$collection_body.= "<img src=\"".$Dir."images/common/tag_icon.gif\" border=\"0\" align=\"absmiddle\" style=\"margin-right:2px;\"><a href=\"".$Dir.FrontDir."tag.php?tagname=".urlencode($taglist[$ii])."\" onmouseover=\"window.status='".$taglist[$ii]."';return true;\" onmouseout=\"window.status='';return true;\"><FONT class=\"prtag\">".$taglist[$ii]."</font></a>";
					}
					else {
						$collection_body.= "<FONT class=\"prtag\">,</font>&nbsp;<a href=\"".$Dir.FrontDir."tag.php?tagname=".urlencode($taglist[$ii])."\" onmouseover=\"window.status='".$taglist[$ii]."';return true;\" onmouseout=\"window.status='';return true;\"><FONT class=\"prtag\">".$taglist[$ii]."</font></a>";
					}
					$jj++;
				}
			}


			$collection_body.="		</font></td>\n";


			$collection_body.="	</tr>\n";
			$collection_body.="	</table>\n";
			$collection_body.="	</td>\n";
			if($_data->coll_loc=="3") {
				$collection_body.="</tr>\n";
			}

			$i++;
		}
		mysql_free_result($result);
		if($_data->coll_loc!="3") {
			if($i!=$collcnt) {
				for($j=$i;$j<$collcnt;$j++) {
					$collection_body.="<td width=\"".ceil(100/$collcnt)."%\" align=\"center\"></td>";
				}
			}
			$collection_body.="	</tr>\n";
		}
		$collection_body.="	</table>\n";
		$collection_body.="	</td>\n";
		$collection_body.="</tr>\n";
		$collection_body.="</table>\n";
	}else{
		$collection_body.="<div style=\"text-align:center; padding:20px 0px; \">관련상품이 없습니다.</div>";
	}
}

//쿠폰을 사용할 경우
/*
//if($_data->coupon_ok=="Y") {
if($_data->coupon_ok=="Y" && $_pdata->checkAbles['coupon'] != 'N'){ // 쿠폰 사용 불가 설정일 경우 상품 쿠폰 정보를 출력하지 않음
	$sql = "SELECT * FROM tblcouponinfo ";
	if($_pdata->vender>0) {
		$sql.= "WHERE (vender='0' OR vender='".$_pdata->vender."') ";
	} else {
		$sql.= "WHERE vender='0' ";
	}
	$sql.= "AND display='Y' AND issue_type='Y' AND detail_auto='Y' ";
	$sql.= "AND (date_end>".date("YmdH")." OR date_end='') ";
	$sql.= "AND ((use_con_type2='Y' AND productcode IN ('ALL','".substr($code,0,3)."000000000','".substr($code,0,6)."000000','".substr($code,0,9)."000','".$code."','".$productcode."')) OR (use_con_type2='N' AND productcode NOT IN ('".substr($code,0,3)."000000000','".substr($code,0,6)."000000','".substr($code,0,9)."000','".$code."','".$productcode."'))) ";
	$result=mysql_query($sql,get_db_conn());
	$i=0;
	while($row=mysql_fetch_object($result)) {
		if($row->date_start>0) {
			$date2 = substr($row->date_start,4,2)."/".substr($row->date_start,6,2)." ~ ".substr($row->date_end,4,2)."/".substr($row->date_end,6,2);
		} else {
			$date2 = date("m/d")." ~ ".date("m/d",mktime(0,0,0,date("m"),date("d")+abs($row->date_start),date("Y")));
		}

		if($i==0) {
			$coupon_body="<table border=0 cellpadding=0 cellspacing=0>\n";
			$couponbody1=$coupon_body;
			$couponbody2=$coupon_body;
		}
		$tmpcouponbody="<tr><td height=\"16\"><font style=\"font-size:8pt;\">* ".$row->description."</font></td></tr>\n";
		$coupon_body.=$tmpcouponbody;
		$couponbody1.=$tmpcouponbody;
		$tmpcouponbody="";
		$tmpcouponbody.="<tr><td>";
		if(file_exists($Dir.DataDir."shopimages/etc/COUPON".$row->coupon_code.".gif")) {
			$tmpcouponbody.="<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"352\" style=\"table-layout:fixed;cursor:hand;\">\n";
			$tmpcouponbody.="<tr>\n";
			$tmpcouponbody.="	<td onclick=\"issue_coupon('".$row->coupon_code."')\"><a href=\"javascript:issue_coupon('".$row->coupon_code."')\"><img src=\"".$Dir.DataDir."shopimages/etc/COUPON".$row->coupon_code.".gif\" border=0></a></td>\n";
			$tmpcouponbody.="</tr>\n";
			$tmpcouponbody.="<tr><td align=\"right\"><A HREF=\"javascript:issue_coupon('".$row->coupon_code."')\"><IMG SRC=\"".$Dir."images/common/coupon_download.gif\" border=\"0\"></A></td></tr>\n";
			$tmpcouponbody.="</table>\n";
		} else {
			$tmpcouponbody.="<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"352\" style=\"table-layout:fixed;\">\n";
			$tmpcouponbody.="<col width=\"5\"></col>\n";
			$tmpcouponbody.="<col width=></col>\n";
			$tmpcouponbody.="<col width=\"5\"></col>\n";
			$tmpcouponbody.="<tr style=\"cursor:hand;\" onclick=\"issue_coupon('".$row->coupon_code."')\">\n";
			$tmpcouponbody.="	<td colspan=\"3\"><IMG SRC=\"".$Dir."images/common/coupon_table01.gif\" border=\"0\"></td>\n";
			$tmpcouponbody.="</tr>\n";
			$tmpcouponbody.="<tr style=\"cursor:hand;\" onclick=\"issue_coupon('".$row->coupon_code."')\">\n";
			$tmpcouponbody.="	<td background=\"".$Dir."images/common/coupon_table02.gif\"><IMG SRC=\"".$Dir."images/common/coupon_table02.gif\" border=\"0\"></td>\n";
			$tmpcouponbody.="	<td width=\"100%\" style=\"padding:3pt;\" background=\"".$Dir."images/common/coupon_bg.gif\">\n";
			$tmpcouponbody.="	<table align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n";
			$tmpcouponbody.="	<tr>\n";
			$tmpcouponbody.="		<td style=\"padding-bottom:4pt;\"><IMG SRC=\"".$Dir."images/common/coupon_title".$row->sale_type.".gif\" border\"0\"></td>\n";
			$tmpcouponbody.="	</tr>\n";
			$tmpcouponbody.="	<tr>\n";
			$tmpcouponbody.="		<td>\n";
			$tmpcouponbody.="		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
			$tmpcouponbody.="		<tr>\n";
			$tmpcouponbody.="			<td><font color=\"#585858\" style=\"font-size:11px;letter-spacing:-0.5pt;\">유효기간 : ".$date2."</font>\n";
			if($row->bank_only=="Y") $tmpcouponbody.=" <font color=\"0000FF\">(현금결제만 가능)</font>";
			$tmpcouponbody.="			</td>\n";
			$tmpcouponbody.="		</tr>\n";
			$tmpcouponbody.="		</table>\n";
			$tmpcouponbody.="		</td>\n";
			$tmpcouponbody.="	</tr>\n";
			$tmpcouponbody.="	<tr>\n";
			$tmpcouponbody.="		<td>\n";
			$tmpcouponbody.="		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
			$tmpcouponbody.="		<tr>\n";
			$tmpcouponbody.="			<td width=\"100%\" align=\"right\"><font color=#FF5000 style=\"font-family:sans-serif;font-size:48px;line-height:45px\"><b><font color=\"#FF6600\" face=\"강산체\">".number_format($row->sale_money)."</font></b></td>\n";
			$tmpcouponbody.="			<td><IMG SRC=\"".$Dir."images/common/coupon_text".$row->sale_type.".gif\" border=\"0\"></td>\n";
			$tmpcouponbody.="		</tr>\n";
			$tmpcouponbody.="		</table>\n";
			$tmpcouponbody.="		</td>\n";
			$tmpcouponbody.="	</tr>\n";
			$tmpcouponbody.="	</table>\n";
			$tmpcouponbody.="	</td>\n";
			$tmpcouponbody.="	<td background=\"".$Dir."images/common/coupon_table04.gif\"><IMG SRC=\"".$Dir."images/common/coupon_table04.gif\" border=\"0\"></td>\n";
			$tmpcouponbody.="</tr>\n";
			$tmpcouponbody.="<tr style=\"cursor:hand;\" onclick=\"issue_coupon('".$row->coupon_code."')\">\n";
			$tmpcouponbody.="	<td colspan=\"3\"><IMG SRC=\"".$Dir."images/common/coupon_table03.gif\" border=\"0\"></td>\n";
			$tmpcouponbody.="</tr>\n";
			$tmpcouponbody.="<tr><td align=\"right\" colspan=\"3\"><A HREF=\"javascript:issue_coupon('".$row->coupon_code."')\"><IMG SRC=\"".$Dir."images/common/coupon_download.gif\" border=\"0\"></A></td></tr>\n";
			$tmpcouponbody.="</table>\n";
		}
		$tmpcouponbody.="</td></tr>\n";
		$coupon_body.=$tmpcouponbody;
		$couponbody1.=$tmpcouponbody;
		$couponbody2.=$tmpcouponbody;
		$tmpcouponbody="<tr><td height=\"10\"></td></tr>\n";
		$coupon_body.=$tmpcouponbody;
		$couponbody1.=$tmpcouponbody;
		$couponbody2.=$tmpcouponbody;
		$i++;
	}
	mysql_free_result($result);
	if($i>0) {
		$coupon_body.="</table>\n";
		$couponbody1.="</table>\n";
		$couponbody2.="</table>\n";
	}
}

*/

//쿠폰을 사용할 경우
$couponItems = array();
//if($_data->coupon_ok=="Y"){
if($_data->coupon_ok=="Y" && $_pdata->checkAbles['coupon'] != 'N'){ // 쿠폰 사용 불가 설정일 경우 상품 쿠폰 정보를 출력하지 않음

	$couponItems = ableCouponOnProduct($_pdata->productcode,$_pdata->vender);
	$mycouponItems = getMyCouponList($_pdata->productcode);

	$coupon_body = '';
	if(_array($couponItems)){
		$coupon_body= '<table cellpadding="0" cellspacing="0" border="0" width="100%"><tr>';
		$cperline = 5;
		$loop = ceil(count($couponItems)/$cperline)*$cperline;

		for($i=0;$i < count($couponItems);$i++){
			$row = $couponItems[$i];
			$date2 = ($row->date_start>0)?substr($row->date_start,0,42)."/".substr($row->date_start,4,2)."/".substr($row->date_start,6,2)." ~ ".substr($row->date_end,0,4)."/".substr($row->date_end,4,2)."/".substr($row->date_end,6,2):date("Y/m/d")." ~ ".date("Y/m/d",mktime(0,0,0,date("m"),date("d")+abs($row->date_start),date("Y")));
			if($i > 0 && $i%$cperline == 0) $coupon_body .= '</tr><tr>';
			$coupon_body .= '<td>';
			$coupon_body .= '	<div style="border:3px solid #ddd; width:180px; min-height:55px; _height:55px;">';

			//$coupon_name=titleCut(50,$row->coupon_name)." - ".number_format($row->sale_money).($row->sale_type<=2?"%":"원").($row->sale_type%2==0?"할인":"적립")."쿠폰";
			$coupon_name = addslashes($row->coupon_name);
			$coupon_desc = number_format($row->sale_money).($row->sale_type<=2?"%":"원").($row->sale_type%2==0?"할인":"적립")."쿠폰";

			if(file_exists($Dir.DataDir."shopimages/etc/COUPON".$row->coupon_code.".gif")) {
				$coupon_body .= '		<div style="text-align:center">';
				$coupon_body .= '			<img src="'.$Dir.DataDir.'shopimages/etc/COUPON'.$row->coupon_code.'.gif\" border=0>';
				$coupon_body .= '		</div>';
			}else{
				$coupon_body .= '		<ul style="list-style:none; margin:0px; padding:0px;">';
				$coupon_body .= '			<li style="width:100%; color:#bbb; font-family:verdana; font-size:10px; font-weight:bold; padding-left:5px;">COUPON</li>';
				//$coupon_body .= '			<li style="float:right; width:100%; color:#ff3300; text-align:center; line-height:30px; font-weight:bold;"><span style="font-family:verdana; font-size:20px; letter-spacing:-0.1em;">10</span>% 할인</li>';
				$coupon_body .= '			<li style="float:right; width:100%; color:#ff3300; text-align:center; line-height:30px; font-weight:bold;"><span style="font-family:verdana; font-size:20px; letter-spacing:-0.1em;">'.number_format($row->sale_money).'</span>'.($row->sale_type<=2?"%":"원").' '.($row->sale_type%2==0?"할인":"적립").'</li>';
				$coupon_body .= '		</ul>';
			}
			$coupon_body .= '	</div>';

			$coupon_body .= '	<div style="width:180px; height:30px; margin-top:5px; text-align:center;"><a href="javascript:return false;" onMouseOver="showInfo'.$i.'.style.visibility=\'visible\';" onMouseOut="showInfo'.$i.'.style.visibility=\'hidden\';">쿠폰정보</a> | <a href="javascript:issue_coupon(\''.$row->coupon_code.'\')"><span style="font-weight:bold;">다운로드</span></a></div>';
			$coupon_body .= '	<div id="showInfo'.$i.'" style="width:210px; margin:0px; margin-top:-12px; padding:10px; position:absolute; background:#ffffff; color:#666; font-size:11px; border:1 solid #ccc; visible; z-index:100; visibility:hidden;">';
			$coupon_body .= '		<span style="color:#444; font-size:12px; font-weight:bold;">쿠폰명 : '.$coupon_name.'</span><br />';
			$coupon_body .= $row->description.'<br />';
			$coupon_body .= '		사용기간 : '.$date2.'<br />';
			if($row->bank_only=="Y") $coupon_body.=" <font color=\"0000FF\">(현금결제만 가능)</font><br />";

			$productList = usableProductOnCoupon($row->productcode);
			if($row->use_con_type2=="N") $coupon_body .= '		적용대상 : '.'['.$productList.'] 제외';
			else $coupon_body .= '		적용대상 : '.$productList.'';
			$coupon_body .= '	</div>';
		}

		for(;$i<$loop;$i++){
			$coupon_body .= '<td width="20%"></td>';
		}
		$coupon_body .= '</tr></table>';
	}

	if(_array($mycouponItems)){
		$coupon_body .= '<table cellpadding="0" cellspacing="0" border="0" width="100%"><tr>';
		$cperline = 5;
		$loop = ceil(count($mycouponItems)/$cperline)*$cperline;

		for($i=0;$i < count($mycouponItems);$i++){
			$row = $mycouponItems[$i];
			$date2 = ($row['date_start']>0)?substr($row['date_start'],0,42)."/".substr($row['date_start'],4,2)."/".substr($row['date_start'],6,2)." ~ ".substr($row['date_end'],0,4)."/".substr($row['date_end'],4,2)."/".substr($row['date_end'],6,2):date("Y/m/d")." ~ ".date("Y/m/d",mktime(0,0,0,date("m"),date("d")+abs($row['date_start']),date("Y")));
			if($i > 0 && $i%$cperline == 0) $coupon_body .= '</tr><tr>';
			$coupon_body .= '<td>';
			$coupon_body .= '	<div style="border:3px solid #ddd; width:150px; min-height:55px; _height:55px;">';

			//$coupon_name=titleCut(50,$row['coupon_name'])." - ".number_format($row['sale_money']).($row['sale_type']<=2?"%":"원").($row['sale_type']%2==0?"할인":"적립")."쿠폰";
			$coupon_name = addslashes($row['coupon_name']);
			$coupon_desc = number_format($row['sale_money']).($row['sale_type']<=2?"%":"원").($row['sale_type']%2==0?"할인":"적립")."쿠폰";

			if(file_exists($Dir.DataDir."shopimages/etc/COUPON".$row['coupon_code'].".gif")) {
				$coupon_body .= '		<div style="text-align:center">';
				$coupon_body .= '			<img src="'.$Dir.DataDir.'shopimages/etc/COUPON'.$row['coupon_code'].'.gif\" border=0>';
				$coupon_body .= '		</div>';
			}else{
				$coupon_body .= '		<ul style="list-style:none; margin:0px; padding:0px;">';
				$coupon_body .= '			<li style="width:100%; color:#bbb; font-family:verdana; font-size:10px; font-weight:bold; padding-left:5px;">COUPON</li>';
				//$coupon_body .= '			<li style="float:right; width:100%; color:#ff3300; text-align:center; line-height:30px; font-weight:bold;"><span style="font-family:verdana; font-size:20px; letter-spacing:-0.1em;">10</span>% 할인</li>';
				$coupon_body .= '			<li style="float:right; width:100%; color:#ff3300; text-align:center; line-height:30px; font-weight:bold;"><span style="font-family:verdana; font-size:20px; letter-spacing:-0.1em;">'.number_format($row['sale_money']).'</span>'.($row['sale_type']<=2?"%":"원").' '.($row['sale_type']%2==0?"할인":"적립").'</li>';
				$coupon_body .= '		</ul>';
			}
			$coupon_body .= '	</div>';

			$coupon_body .= '	<div style="width:150px; height:30px; margin-top:5px; text-align:center;"><a href="javascript:return false;" onMouseOver="myShowInfo'.$i.'.style.visibility=\'visible\';" onMouseOut="myShowInfo'.$i.'.style.visibility=\'hidden\';">쿠폰정보</a> | <span style="font-weight:bold;">보유중</span></div>';
			$coupon_body .= '	<div id="myShowInfo'.$i.'" style="width:210px; margin:0px; margin-top:-12px; padding:10px; position:absolute; background:#ffffff; color:#666; font-size:11px; border:1 solid #ccc; visible; z-index:100; visibility:hidden;">';
			$coupon_body .= '		<span style="color:#444; font-size:12px; font-weight:bold;">쿠폰명 : '.$coupon_name.'</span><br />';
			$coupon_body .= $row['description'].'<br />';
			$coupon_body .= '		사용기간 : '.$date2.'<br />';
			if($row['bank_only']=="Y") $coupon_body.=" <font color=\"0000FF\">(현금결제만 가능)</font><br />";

			$productList = usableProductOnCoupon($row['productcode']);
			if($row['use_con_type2']=="N") $coupon_body .= '		적용대상 : '.'['.$productList.'] 제외';
			else $coupon_body .= '		적용대상 : '.$productList.'';
			$coupon_body .= '	</div>';
		}

		for(;$i<$loop;$i++){
			$coupon_body .= '<td></td>';
		}
		$coupon_body .= '</tr></table>';
	}

	if(_empty($coupon_body)){
		$coupon_body = '<div style="font-size:11px; color:#666666;height:30px; line-height:30px;"> * 이 상품에 적용 가능한 쿠폰이 없습니다.</div>';
	}
}else if($_data->coupon_ok == 'N'){ // 쿠폰 사용안함 설정일 경우 쿠폰 목록 미출력 처리
	$coupon_body = '';
}else{
	$coupon_body = '<div style="font-size:11px; color:#666666;height:30px; line-height:30px;">* 이 상품은 <b>할인쿠폰이 적용불가한</b> 상품입니다.</div>';
}



//상품단어 필터링
if(strlen($_data->filter)>0) {
	$arr_filter=explode("#",$_data->filter);
	$detail_filter=$arr_filter[0];
	$filters=explode("=",$detail_filter);
	$filtercnt=count($filters)/2;

	for($i=0;$i<$filtercnt;$i++){
		$filterpattern[$i]="/".str_replace("\0","\\0",preg_quote($filters[$i*2]))."/";
		$filterreplace[$i]=$filters[$i*2+1];
		if(strlen($filterreplace[$i])==0) $filterreplace[$i]="***";
	}

	$review_filter_array=explode("REVIEWROW",$arr_filter[1]);
	$review_filter=$review_filter_array[0];
}

//상품다중이미지 확인
/*
$multi_img="N";
$sql2 ="SELECT * FROM tblmultiimages WHERE productcode='".$productcode."' ";
$result2=mysql_query($sql2,get_db_conn());
if($row2=mysql_fetch_object($result2)) {
	if($_data->multi_distype=="0") {
		$multi_img="I";
	} else if($_data->multi_distype=="1") {
		$multi_img="Y";
		//$multi_imgs=array(&$row2->primg01,&$row2->primg02,&$row2->primg03,&$row2->primg04,&$row2->primg05,&$row2->primg06,&$row2->primg07,&$row2->primg08,&$row2->primg09,&$row2->primg10);
		$multi_imgs = array ();
		for( $i=1;$i<=MultiImgCnt;$i++ ){
			$k = str_pad($i,2,'0',STR_PAD_LEFT);
			array_push( $multi_imgs, &$row2->{"primg".$k} );
		}

		$thumbcnt=0;
		for($j=0;$j<MultiImgCnt;$j++) {
			if(strlen($multi_imgs[$j])>0) {
				$thumbcnt++;
			}
		}
		$multi_height=430;
		$thumbtype=1;
		if($thumbcnt>5) {
			$multi_height=550;
			$thumbtype=2;
		}
	}
}
mysql_free_result($result2);
*/

#####################상품별 회원할인율 적용 시작#######################################
if(_empty($_ShopInfo->getMemid())){
	$reurl=trim(urlencode($_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']));
	$mempricestr="<tr><td style=\"color:#568EF5;\">회원적립/할인</td>";
	$mempricestr.="<td colspan=3><span style=\"color:#568EF5;\"><img src=\"/data/design/img/detail/icon_lock1.gif\" align=\"absmiddle\" />[<a href=\"/front/login.php?reurl=".$reurl."\"  style=\"color:#568EF5;\">로그인</a>]</span></td></tr>\n";
	$mempricestr.="<input type=hidden name=\"ismember\" id=\"ismember\" value=\"N\">";
	$mempricestr.="<input type=hidden name=\"reurl\" id=\"reurl\" value=\"".$reurl."\">";
}else{
	$mem_reserve = getProductReserve($productcode);
	//$discountprices = getProductDiscount($productcode);
	//echo $mem_reserve."/".$discountprices;
	if($discountprices>0){
		$memberprice = $_pdata->sellprice - $discountprices;

		$disc_per = ($discountprices/$_pdata->sellprice)*100;
		$mempricestr = "<tr><td style=\"color:#568EF5;\">회원적립/할인</td>";
		$mempricestr .= "<td colspan=3><span id='memberprice' style=\"font-weight:normal;color:#568EF5;\">".number_format($memberprice)."원</span> <span style=\"color:#568EF5;font-size:11px;\">(".$disc_per."%,회원등급할인)</span></td></tr>";
		$mempricestr .= "<input type=\"hidden\" name=\"disc_per\" id=\"disc_per\" value=\"".$disc_per."\">";
		$mempricestr .="<input type=hidden name=\"reurl\" id=\"reurl\" value=\"".$reurl."\">";
		$strikeStart = "<strike>";
		$strikeEnd = "</strike>";
	}else if($mem_reserve>0){
		$reserve_per = round($mem_reserve*100);
		$reserveprice=getReserveConversion($mem_reserve,"Y",$_pdata->sellprice,"Y");
		$mempricestr = "<tr><td style=\"color:#568EF5;\">회원적립/할인</td>";
		$mempricestr .= "<td colspan=3><span id='memberprice' style=\"font-weight:normal;color:#568EF5;\">".number_format($reserveprice)."원</span> <span style=\"color:#568EF5;font-size:11px;\">(".$reserve_per."%,회원등급적립)</span></td></tr>";
		$mempricestr .="<input type=hidden name=\"reserveconv\" id=\"reserveconv\" value=\"".$reserve_per."\">";
		$strikeStart = "<strike>";
		$strikeEnd = "</strike>";
	}else{
		$memberprice = '';
		$mempricestr = '';
	}

}
#####################상품별 회원할인율 적용 끝 #######################################

//상품 상세정보 노출정보
$_data->exposed_list = "";
if(strlen($_data->exposed_list)==0) {
	//$_data->exposed_list=",3,4,2,23,0,17,1,10,5,27,28,22,24,29,25,26,20,21,7,19,6,";
	$_data->exposed_list=",3,2,23,0,17,1,10,5,27,28,7,24,29,25,26,20,21,19,6,";
}
$arexcel = explode(",",substr($_data->exposed_list,1,-1));
$prcnt = count($arexcel);
//$arproduct=array(&$prproduction,&$prmadein,&$prconsumerprice,&$prsellprice,&$prreserve,&$praddcode,&$prquantity,&$proption,&$prproductname,&$prdollarprice,&$prmodel,&$propendate,&$pruserspec0,&$pruserspec1,&$pruserspec2,&$pruserspec3,&$pruserspec4,&$prbrand,&$prselfcode,&$prpackage);

$arproduct=array(&$prproduction,&$prmadein,&$prconsumerprice,&$prsellprice,&$prreserve,&$praddcode,&$prquantity,&$proption,&$prproductname,&$prdollarprice,&$prmodel,&$propendate,&$pruserspec0,&$pruserspec1,&$pruserspec2,&$pruserspec3,&$pruserspec4,&$prbrand,&$prselfcode,&$prpackage,&$useableStr,&$prgift,&$prtrans,&$couponpoplink,&$rental,&$prmemprice,&$prdelitype,&$rentalloc,&$rentalstatus,&$rentalcount);
$ardollar=explode(",",$_data->ETCTYPE["DOLLAR"]);

if(strlen($ardollar[1])==0 || $ardollar[1]<=0) $ardollar[1]=1;

if(ereg("^(\[OPTG)([0-9]{4})(\])$",$_pdata->option1)){
	$optcode = substr($_pdata->option1,5,4);
	$_pdata->option1="";
	$_pdata->option_price="";
}

$miniq = 1;
if (strlen($_pdata->etctype)>0) {
	$etctemp = explode("",$_pdata->etctype);
	for ($i=0;$i<count($etctemp);$i++) {
		if (substr($etctemp[$i],0,6)=="MINIQ=")			$miniq=substr($etctemp[$i],6);
		if (substr($etctemp[$i],0,11)=="DELIINFONO=")	$deliinfono=substr($etctemp[$i],11);
		if (substr($etctemp[$i],0,5)=="MAXQ=")      $maxq=substr($etctemp[$i],5);
	}
}

//입점업체 정보 관련
if($_pdata->vender>0) {
	$sql = "SELECT a.vender, a.id, a.brand_name, a.deli_info, b.prdt_cnt ";
	$sql.= "FROM tblvenderstore a, tblvenderstorecount b ";
	$sql.= "WHERE a.vender='".$_pdata->vender."' AND a.vender=b.vender ";
	$result=mysql_query($sql,get_db_conn());
	if(!$_vdata=mysql_fetch_object($result)) {
		$_pdata->vender=0;
	}
	mysql_free_result($result);
}
//_pr($_data);
//exit;
//deli_setperiod

$delipriceTxt = '';
$deliRangeStr = ((intval($_data->deli_setperiod) > 0)?$_data->deli_setperiod+2:3).'일 이내 배송가능(토,일 공휴일 제외)';
if(($_pdata->deli=="Y" || $_pdata->deli=="N") && $_pdata->deli_price>0) {
	$delipriceTxt = number_format($_pdata->deli_price).'원';
	//$delipriceTxt = '[개별유료배송] '.number_format($_pdata->deli_price).'원';
	if($_pdata->deli=="Y") $delipriceTxt .= '(수량대비증가)';
} else if($_pdata->deli=="F" || $_pdata->deli=="G") {
	if($_pdata->deli=="F") {
		$delipriceTxt = '[개별무료배송]';
	} else {
		$delipriceTxt = '[개별착불배송]';
	}
}else{
	$_vdinfo = false;
	if($_pdata->vender >0){
		$sql = "select * from tblvenderinfo where vender = '".$_pdata->vender."' limit 1";
		if(false !== $result = mysql_query($sql,get_db_conn())){
			if(mysql_num_rows($result)){
				$_vdinfo = mysql_fetch_assoc($result);
			}
			mysql_free_result($result);
		}
	}
	if($_vdinfo && $_vdinfo['deli_super'] != 'S'){
		if($_vdinfo['deli_type'] == 'F'){
			$delipriceTxt = '[입점사무료배송]';
		}else if($_vdinfo['deli_type'] == 'Y'){
			$delipriceTxt = '[입점사착불]';
		}else{
			if( $_vdinfo['deli_price'] > 0 ) {
				//$delipriceTxt = '[입점사유료배송] '.number_format($_vdinfo['deli_price']).'원';
				$delipriceTxt = number_format($_vdinfo['deli_price']).'원';
			} else{
				$delipriceTxt = '배송비 무료';
			}
		}
	}else{
		if($_data->deli_type == 'F'){
			$delipriceTxt = '[무료배송]';
		}else if($_data->deli_type == 'Y'){
			$delipriceTxt = '[착불]';
		}else{
			if( $_data->deli_basefee > 0 ) {
				$delipriceTxt = number_format($_data->deli_basefee).'원';
				//$delipriceTxt = '[유료배송] '.number_format($_data->deli_basefee).'원';
			} else{
				$delipriceTxt = '배송비 무료';
			}
		}
	}

	$deliselectTxt = number_format($_vdinfo['deli_mini']).'원 이상 무료배송';
}

//입점업체 인기상품 출력
if( $_pdata->vender > 0 ) {
	$venderproductSQL = "SELECT productcode, tinyimage, maximage,sellprice, consumerprice, reserve, reservetype, productname, rental FROM tblproduct ";
	$venderproductSQL .= "WHERE vender ='".$_pdata->vender."' ";
	$venderproductSQL .= "AND productcode !='".$productcode."' ";
	$venderproductSQL .= "AND display='Y' ";
//	$venderproductSQL .= "AND (maximage != '' || maximage is null) ";
	$venderproductSQL .= "ORDER BY sellcount DESC LIMIT 0, 6";
	$venderproduct = "";
	$venderproduct .= "<ul style=\"clear:both; margin:0px 5px;\">\n";

	if(false !== $venderproductRes = mysql_query($venderproductSQL, get_db_conn())){
		$venderproductNum = mysql_num_rows($venderproductRes);

		if($venderproductNum <= 0){
			$venderproduct .= "	<li>등록된 상품이 없습니다.</li>\n";
		}else{
			while($venderproductRow = mysql_fetch_assoc($venderproductRes)){
				$reserveconv=getReserveConversion($venderproductRow['reserve'],$venderproductRow['reservetype'],$venderproductRow['sellprice'],"Y");
				$src = $Dir."data/shopimages/product/".$venderproductRow['maximage'];
				if(strlen($venderproductRow['maximage'])>0){
					$size = '150';		//상품이미지 가로 사이즈
				}
				
				// 렌탈 아이콘
				$rentalIcon = rentalIcon($venderproductRow['rental']);

				$venderproduct .= "	<li style=\"float:left; width:170px; padding:0px 15px 0px 15px;\">\n";
				$venderproduct .= "		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" border=\"0\">\n";
				$venderproduct .= "			<tr>\n";
				$venderproduct .= "				<td>\n";
				$venderproduct .= "					<a href=\"/front/productdetail.php?productcode=".$venderproductRow['productcode']."\" style=\"border:0px;\"><div style=background:url(\"".$src."\");width:170px;height:170px;background-position:center;background-size:100%;background-repeat:no-repeat;></div></a>";
				$venderproduct .= "				</td>\n";
				$venderproduct .= "			</tr>\n";
				$venderproduct .= "			<tr><td height=\"5\"></td></tr>\n";
				$venderproduct .= "			<tr>\n";
				$venderproduct .= "				<td align=\"center\"><a href=\"/front/productdetail.php?productcode=".$venderproductRow['productcode']."\"><span class=\"prname\">".$rentalIcon.titleCut(40,$venderproductRow['productname'])."</span></a></td>\n";
				$venderproduct .= "			</tr>\n";
				/*
				if(strlen($venderproductRow['consumerprice'])>0){
					$venderproduct .= "		<tr>\n";
					$venderproduct .= "			<td align=\"center\"><span style=\"text-decoration:line-through\">".number_format($venderproductRow['consumerprice'])."원</span></td>\n";
					$venderproduct .= "		</tr>\n";
				}
				*/
				$venderproduct .= "			<tr>\n";
				$venderproduct .= "				<td align=\"center\" class=\"prprice\">".number_format($venderproductRow['sellprice'])."원</td>\n";
				$venderproduct .= "			</tr>\n";

				if($reserveconv > 0){
					$venderproduct .= "			<tr>\n";
					$venderproduct .= "				<td align=\"center\" class=\"prreserve\"><img src=\"".$Dir."images/common/reserve_icon.gif\" border=\"0\" align=\"absmiddle\" /> ".number_format($reserveconv)."원</td>\n";
					$venderproduct .= "			</tr>\n";
				}

				$venderproduct .= "		</table>\n";
				$venderproduct .= "	</li>\n";
			}
		}
	}else{
		$venderproduct .= "	<li>\n";
		$venderproduct .= "	DB 와 연결중에 오류가 발생하였습니다.\n다시 시도해 주시기 바랍니다.\n";
		$venderproduct .= "	</li>\n";
	}
	$venderproduct .= "</ul>\n";
}


//배송/교환/환불정보 노출
$deli_info="";
if($deliinfono!="Y") {	//개별상품별 배송/교환/환불정보 노출일 경우
	$deli_info_data="";
	if($_pdata->vender>0 && strlen($_vdata->deli_info)>0) {		//입점업체 상품이면서 배송/교환/환불정보가 있을경우 입점업체 배송/교환/환불정보 누출
		$deli_info_data=$_vdata->deli_info;
		$aboutdeliinfofile=$Dir.DataDir."shopimages/vender/aboutdeliinfo_".$_vdata->vender.".gif";
	} else {
		$deli_info_data=$_data->deli_info;
		$aboutdeliinfofile=$Dir.DataDir."shopimages/etc/aboutdeliinfo.gif";
	}
	if(strlen($deli_info_data)>0) {
		$tempdeli_info=explode("=",$deli_info_data);
		if($tempdeli_info[0]=="Y") {
			if($tempdeli_info[1]=="TEXT") {			//텍스트형
				$allowedTags = "<h1><b><i><a><ul><li><pre><hr><blockquote><u><img><br><font>";

				if(strlen($tempdeli_info[2])>0 || strlen($tempdeli_info[3])>0) {
					$deli_info = "<table border=0 cellpadding=0 cellspacing=0 width=100%>\n";
					$deli_info.= "<tr>\n";
					$deli_info.= "	<td>\n";
					$deli_info.= "	<table border=0 cellpadding=0 cellspacing=0 width=100%>\n";
					if(strlen($tempdeli_info[2])>0) {	//배송정보 텍스트
						$deli_info.= "	<tr>\n";
						//$deli_info.= "		<td><img src=\"".$Dir."images/common/detaildeliinfo_img1.gif\" border=0></td>\n";
						$deli_info.= "		<td><p style=\"font-weight:bold;font-size:15px;padding:10px 0px;color:#333333\">배송안내</p></td>\n";
						$deli_info.= "	</tr>\n";
						$deli_info.= "	<tr>\n";
						$deli_info.= "		<td>\n";
						$deli_info.= "		".nl2br(strip_tags($tempdeli_info[2],$allowedTags))."\n";
						$deli_info.= "		</td>\n";
						$deli_info.= "	</tr>\n";
						$deli_info.= "	<tr><td height=10></td></tr>\n";
					}
					if(strlen($tempdeli_info[3])>0) {	//교환/환불정보 텍스트
						$deli_info.= "	<tr>\n";
						$deli_info.= "		<td><p style=\"font-weight:bold;font-size:15px;padding:10px 0px;margin-top:30px;color:#333333\">교환/반품/환불 안내
</p></td>\n";
						$deli_info.= "	</tr>\n";
						$deli_info.= "	<tr>\n";
						$deli_info.= "		<td>\n";
						$deli_info.= "		".nl2br(strip_tags($tempdeli_info[3],$allowedTags))."\n";
						$deli_info.= "		</td>\n";
						$deli_info.= "	</tr>\n";
						$deli_info.= "	<tr><td height=15></td></tr>\n";
					}
					$deli_info.= "	</table>\n";
					$deli_info.= "	</td>\n";
					$deli_info.= "</tr>\n";
					$deli_info.= "</table>\n";
				}
			} else if($tempdeli_info[1]=="IMAGE") {	//이미지형
				if(file_exists($aboutdeliinfofile)) {
					$deli_info = "<table border=0 cellpadding=0 cellspacing=0 width=100%>\n";
					$deli_info.= "<tr>\n";
					$deli_info.= "	<td><img src=\"".$aboutdeliinfofile."\" align=absmiddle border=0></td>\n";
					$deli_info.= "</tr>\n";
					$deli_info.= "</table>\n";
				}
			} else if($tempdeli_info[1]=="HTML") {	//HTML로 입력
				if(strlen($tempdeli_info[2])>0) {
					$deli_info = "<table border=0 cellpadding=0 cellspacing=0 width=100%>\n";
					$deli_info.= "<tr><td>".$tempdeli_info[2]."</td></tr>\n";
					$deli_info.= "</table>\n";
				}
			}
		}
	}
}

//리뷰관련 환경 설정
$reviewlist=$_data->ETCTYPE["REVIEWLIST"];
$reviewdate=$_data->ETCTYPE["REVIEWDATE"];
if(strlen($reviewlist)==0) $reviewlist="N";

if($mode=="review_write") {
	function ReviewFilter($filter,$memo,&$findFilter) {
		$use_filter = split(",",$filter);
		$isFilter = false;
		for($i=0;$i<count($use_filter);$i++) {
			if (eregi($use_filter[$i],$memo)) {
				$findFilter = $use_filter[$i];
				$isFilter = true;
				break;
			}
		}
		return $isFilter;
	}

	$rname=$_POST["rname"];
	$rcontent=$_POST["rcontent"];
	$rmarks=$_POST["rmarks"];
	if((strlen($_ShopInfo->getMemid())==0) && $_data->review_memtype=="Y") {
		echo "<html></head><body onload=\"alert('로그인을 하셔야 사용후기 등록이 가능합니다.');location.href='".$Dir.FrontDir."login.php?chUrl=".getUrl()."'\"></body></html>";exit;
	}
	if(strlen($review_filter)>0) {	//사용후기 내용 필터링
		if(ReviewFilter($review_filter,$rcontent,$findFilter)) {
			echo "<html></head><body onload=\"alert('사용하실 수 없는 단어를 입력하셨습니다.(".$findFilter.")\\n\\n다시 입력하시기 바랍니다.');history.go(-1);\"></body></html>";exit;
		}
	}
	/** 첨부 이미지 추가 */
	$up_imd = '';

	if(is_array($_FILES['img']) && is_uploaded_file($_FILES['img']['tmp_name'])){
		if($_FILES['img']['error'] > 0){
			echo "<html></head><body onload=\"alert('파일 업로드중 오류가 발생했습니다.');history.go(-1);\"></body></html>";
			exit;
		}

		$save_dir=$Dir.DataDir."shopimages/productreview/";
		$numresult = mysql_query("select ifnull(max(num),1) as num from tblproductreview",get_db_conn());

		if($numresult){
			$file_name =  $productcode.(intval(mysql_result($numresult,0,0))+1);
		}

		$size=getimageSize($_FILES['img']['tmp_name']);
		$width=$size[0];
		$height=$size[1];
		$imgtype=$size[2];
		$_w = 650;
		$ratio = ($_w > 0 && $width > $_w)?(real)($_w / $width):1;

		if($imgtype==1)      $file_ext ='gif';
		else if($imgtype==2) $file_ext ='jpg';
		else if($imgtype==3) $file_ext ='png';
		else{
			echo "<html></head><body onload=\"alert('올바른 형태의 이미지 파일이 아닙니다.');history.go(-1);\"></body></html>";
			exit;
		}

		$index = 0;
		$up_name = $file_name.".".$file_ext;
		while(file_exists($save_dir."/".$up_name)){
			$up_name = $file_name."_".$index.".".$file_ext;
			$index++;
		}

		if(!move_uploaded_file($_FILES['img']['tmp_name'],$save_dir."/".$up_name)){
			echo "<html></head><body onload=\"alert('파일 저장 실패.');history.go(-1);\"></body></html>";
			exit;
		}
		if($ratio < 1){
			$source = $target = $save_dir."/".$up_name;
			$new_width = (int)($ratio*$width);
			$new_height = (int)($ratio*$height);

			$dest_img = imagecreatetruecolor($new_width,$new_height);
			$white = imagecolorallocate($dest_img,255,255,255);
			imagefill($dest_img,0,0,$white);

			if($file_ext == 'gif'){ //이미지 타입에 따라서 이미지 로드
				$src_img = imagecreatefromgif($source);
				imagecopyresampled($dest_img,$src_img,0,0,0,0,$new_width,$new_height,$width,$height);
				imagedestroy($src_img);
				imagegif($dest_img,$target);
			}else if($file_ext == 'jpg'){
				$src_img = @imagecreatefromjpeg($source);
				imagecopyresampled($dest_img,$src_img,0,0,0,0,$new_width,$new_height,$width,$height);
				imagedestroy($src_img);
				imagejpeg($dest_img,$target,75);
			}else if($file_ext == 'png'){
				$src_img = imagecreatefrompng($source);
				imagecopyresampled($dest_img,$src_img,0,0,0,0,$new_width,$new_height,$width,$height);
				imagedestroy($src_img);
				imagepng($dest_img,$target);
			}
			imagedestroy($dest_img);
		}
	}



	$sql = "INSERT tblproductreview SET ";
	$sql.= "productcode	= '".$productcode."', ";
	$sql.= "id			= '".$_ShopInfo->getMemid()."', ";
	$sql.= "name		= '".$rname."', ";
	$sql.= "marks		= '".$rmarks."', ";
	$sql.= "date		= '".date("YmdHis")."', ";
	$sql.= "content		= '".$rcontent."', ";
	$sql.= "img		= '".$up_name."' ";
	mysql_query($sql,get_db_conn());

	if($_data->review_type=="A") $msg="관리자 인증후 등록됩니다.";
	else $msg="등록되었습니다.";
	$rqry="productcode=".$productcode;
	if(strlen($code)>0) $rqry.="&code=".$code;
	if(strlen($sort)>0) $rqry.="&sort=".$sort;
	if(strlen($brandcode)>0) $rqry.="&brandcode=".$brandcode;
	echo "<html></head><body onload=\"alert('".$msg."');location='".$_SERVER["PHP_SELF"]."?".$rqry."'\"></body></html>";exit;
}

//이전/다음 상품 관련
$qry = "WHERE 1=1 ";
if(eregi("T",$_cdata->type)) {	//가상분류
	$sql = "SELECT productcode FROM tblproducttheme WHERE code LIKE '".$likecode."%' ";
	$result=mysql_query($sql,get_db_conn());
	$t_prcode="";
	while($row=mysql_fetch_object($result)) {
		$t_prcode.=$row->productcode.",";
		$i++;
	}
	mysql_free_result($result);
	$t_prcode=substr($t_prcode,0,-1);
	$t_prcode=ereg_replace(',','\',\'',$t_prcode);
	$qry.= "AND a.productcode IN ('".$t_prcode."') ";

	$add_query="&code=".$code;
} else {	//일반분류
	$qry.= "AND a.productcode LIKE '".$likecode."%' ";
}
$qry.= "AND a.display='Y' ";

$tmp_sort=explode("_",$sort);
if($brandcode>0) {
	$qry.="AND a.brand='".$brandcode."' ";
	$add_query.="&brandcode=".$brandcode;
	$brand_link = "brandcode=".$brandcode."&";

	$sql ="SELECT SUBSTRING(a.productcode, 1, 3) AS code FROM tblproduct AS a ";
	$sql.="LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
	$sql.="WHERE a.display='Y' AND a.brand='".$brandcode."' ";
	$sql.="AND (a.group_check='N' OR b.group_code LIKE '%".$_ShopInfo->getMemgroup()."%') ";
	$sql.="GROUP BY code ";
	$result=mysql_query($sql,get_db_conn());
	$brand_qry = "";
	$leftcode = array();
	while($row=mysql_fetch_object($result)) {
		$leftcode[] = $row->code;
	}
	if(count($leftcode)>0) {
		$brand_qry = "AND codeA IN ('".implode("','",$leftcode)."') ";
	}

	if($tmp_sort[0]=="reserve") {
		$addsortsql=",IF(a.reservetype='N',a.reserve*1,a.reserve*a.sellprice*0.01) AS reservesort ";
	}
	$sql = "SELECT a.productcode, a.productname, a.sellprice, a.quantity, a.reserve, a.reservetype, a.production, ";
	$sql.= "a.tinyimage, a.date, a.etctype, a.option_price ";
	$sql.= $addsortsql;
	$sql.= "FROM tblproduct AS a ";
	$sql.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
	$sql.= $qry." ";
	$sql.= "AND (a.group_check='N' OR b.group_code LIKE '%".$_ShopInfo->getMemgroup()."%') ";
	if($tmp_sort[0]=="production") $sql.= "ORDER BY a.production ".$tmp_sort[1]." ";
	else if($tmp_sort[0]=="name") $sql.= "ORDER BY a.productname ".$tmp_sort[1]." ";
	else if($tmp_sort[0]=="price") $sql.= "ORDER BY a.sellprice ".$tmp_sort[1]." ";
	else if($tmp_sort[0]=="reserve") $sql.= "ORDER BY reservesort ".$tmp_sort[1]." ";
	else $sql.= "ORDER BY a.productname ";
} else {
	if($tmp_sort[0]=="reserve") {
		$addsortsql=",IF(a.reservetype='N',a.reserve*1,a.reserve*a.sellprice*0.01) AS reservesort ";
	}
	$sql = "SELECT a.productcode, a.productname, a.sellprice, a.quantity, a.reserve, a.reservetype, a.production, ";
	if($_cdata->sort=="date2") $sql.="IF(a.quantity<=0,'11111111111111',a.date) as date, ";
	$sql.= "a.tinyimage, a.etctype, a.option_price ";
	$sql.= $addsortsql;
	$sql.= "FROM tblproduct AS a ";
	$sql.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
	$sql.= $qry." ";
	$sql.= "AND (a.group_check='N' OR b.group_code LIKE '%".$_ShopInfo->getMemgroup()."%') ";
	if($tmp_sort[0]=="production") $sql.= "ORDER BY a.production ".$tmp_sort[1]." ";
	else if($tmp_sort[0]=="name") $sql.= "ORDER BY a.productname ".$tmp_sort[1]." ";
	else if($tmp_sort[0]=="sellprice") $sql.= "ORDER BY a.sellprice ".$tmp_sort[1]." ";
	else if($tmp_sort[0]=="reserve") $sql.= "ORDER BY reservesort ".$tmp_sort[1]." ";
	else {
		if(strlen($_cdata->sort)==0 || $_cdata->sort=="date" || $_cdata->sort=="date2") {
			$sql.= "ORDER BY date DESC ";
		} else if($_cdata->sort=="productname") {
			$sql.= "ORDER BY a.productname ";
		} else if($_cdata->sort=="production") {
			$sql.= "ORDER BY a.production ";
		} else if($_cdata->sort=="price") {
			$sql.= "ORDER BY a.sellprice ";
		}
	}
}
$result=mysql_query($sql,get_db_conn());
unset($arr_productcode);
$isprcode=false;
while($row=mysql_fetch_object($result)) {
	if($productcode==$row->productcode) {
		$isprcode=true;
	} else {
		if($isprcode==false) {
			$arr_productcode["prev"]=$row->productcode;
		} else {
			$arr_productcode["next"]=$row->productcode;
			break;
		}
	}
}
mysql_free_result($result);


//현재위치
$codenavi=($brandcode>0?getBCodeLoc($brandcode,$code):getCodeLoc($code));

//상품QNA 게시판 존재여부 확인 및 설정정보 확인
$prqnaboard=getEtcfield($_data->etcfield,"PRQNA");
if(strlen($prqnaboard)>0) {
	$sql = "SELECT * FROM tblboardadmin WHERE board='".$prqnaboard."' ";
	$result=mysql_query($sql,get_db_conn());
	$qnasetup=mysql_fetch_object($result);
	mysql_free_result($result);
	if($qnasetup->use_hidden=="Y") unset($qnasetup);
}

//페이스북 이미지
if(strlen($_pdata->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$_pdata->tinyimage)) {
	$fbThumb = "http://".$_ShopInfo->getShopurl().DataDir."shopimages/product/".$_pdata->tinyimage;
}else{
	$fbThumb = "http://".$_ShopInfo->getShopurl()."images/no_img/no_img.gif";
}

//sns 설정
$arSnsType = explode("", $_data->sns_reserve_type);
$odrChk = true;

// 사은품 관련 최소구매 금액
$tmpgift = explode('|',$_data->gift_type);

if(($tmpgift[0] == 'M' && !_empty($_ShopInfo->getMemid())) || $tmpgift[0] == 'C'){
	if(false !== $gres = mysql_query("select min(gift_startprice) from tblgiftinfo",get_db_conn())){
		$giftprice = mysql_result($gres,0,0);
	}
}

#상품후기 카운트
$reviewcountSQL = "SELECT COUNT(productcode) FROM tblproductreview WHERE productcode = '".$_pdata->productcode."' ";
$reviewcount=0;
if(false !== $reviewcountRes = mysql_query($reviewcountSQL,get_db_conn())){
	$reviewcount = mysql_result($reviewcountRes,0,0);
	@mysql_free_result($reviewcountRes);
}
#상품후기 카운트 끝
#상품QNA 카운트
$qnacountSQL = "SELECT COUNt(num) FROM tblboard WHERE board = '".$prqnaboard."' AND pridx = '".$_pdata->pridx."' AND pos ='0' ";
$qnacount=0;
if(false !== $qnacountRes = mysql_query($qnacountSQL,get_db_conn())){
	$qnacount= mysql_result($qnacountRes,0,0);
	@mysql_free_result($qnacountRes);
}
#상품 QNA 카운트 끝

$rsort = !_empty($_GET['sort'])?trim($_GET['sort']):"";
$rblock = !_empty($_GET['block'])?trim($_GET['block']):"";
$rgotopage = !_empty($_GET['gotopage'])?trim($_GET['gotopage']):"";
$rqnablock = !_empty($_GET['qnablock'])?trim($_GET['qnablock']):"";
$rqnagotopage = !_empty($_GET['qnagotopage'])?trim($_GET['qnagotopage']):"";
$rbrandcode = !_empty($_GET['brandcode'])?trim($_GET['brandcode']):"";
$rselectreview = !_empty($_GET['review'])?trim($_GET['review']):"";

$reviewlink = $_SERVER['PHP_SELF']."?productcode=".$productcode."&sort=".$rsort."&block=".$rblock."&gotopage=".$rgotopage."&qnablock=".$rqnablock."&qnagotopage=".$rqnagotopage."&brandcode=".$rbrandcode;

$reviewcounterSQL = "SELECT ";
$reviewcounterSQL .= "COUNT(num) AS total ";
$reviewcounterSQL .= ",SUM(IF(img IS NULL OR img ='',1,0)) AS basic ";
$reviewcounterSQL .= ",SUM(IF(img IS NOT NULL AND img !='',1,0)) AS photo ";
$reviewcounterSQL .= ",SUM(IF(best = 'Y',1,0)) AS best ";
$reviewcounterSQL .= ",SUM(quality) AS quailty ";
$reviewcounterSQL .= ",SUM(price) AS price ";
$reviewcounterSQL .= ",SUM(delitime) AS delitime ";
$reviewcounterSQL .= ",SUM(recommend) AS recommend ";
$reviewcounterSQL .= "FROM tblproductreview ";
$reviewcounterSQL .= "WHERE productcode = '".$productcode."' ";

if(false !== $reviewcountRes = mysql_query($reviewcounterSQL,get_db_conn())){
	$reviewcountRow = mysql_fetch_assoc($reviewcountRes);
	mysql_free_result($reviewcountRes);
}

$averqulity=$averprice=$averdelitime=$averrecommend=$avertotalscore=$startotalcount ="0";

$counttotal = ($reviewcountRow['total'])?trim($reviewcountRow['total']):"0";
$countbasic = ($reviewcountRow['basic'])?trim($reviewcountRow['basic']):"0";
$countphoto = ($reviewcountRow['photo'])?trim($reviewcountRow['photo']):"0";
$countbest = ($reviewcountRow['best'])?trim($reviewcountRow['best']):"0";
$sumquailty = ($reviewcountRow['quailty'])?trim($reviewcountRow['quailty']):"0";
$sumprice = ($reviewcountRow['price'])?trim($reviewcountRow['price']):"0";
$sumdelitime = ($reviewcountRow['delitime'])?trim($reviewcountRow['delitime']):"0";
$sumrecommend = ($reviewcountRow['recommend'])?trim($reviewcountRow['recommend']):"0";

$averquality = floor(($sumquailty * 20)/$counttotal);
$averprice = floor(($sumprice * 20)/$counttotal);
$averdelitime = floor(($sumdelitime * 20)/$counttotal);
$averrecommend = floor(($sumrecommend * 20)/$counttotal);

$countaverqulity = floor($sumquailty/$counttotal);
$countaverprice = floor($sumprice/$counttotal);
$countaverdelitime = floor($sumdelitime/$counttotal);
$countaverrecommend = floor($sumrecommend/$counttotal);

$avertotalscore = floor(($averquality+$averprice+$averdelitime+$averrecommend) /4);
$startotalcount = floor($avertotalscore / 20);

#사용후기 토탈 별점수
$reviewstarcount="";
for($i=1;$i<=5;$i++){
	if($i <= $startotalcount){
		$reviewstarcount.='<img src="/images/003/star_point1.gif" alt="" />';
	}else{
		$reviewstarcount.='<img src="/images/003/star_point2.gif" alt="" />';
	}
}

#사용후기 품질 별점수
$qualitystarcount="";
for($i=1;$i<=5;$i++){

	if($i <= $countaverqulity){
		$qualitystarcount.='<img src="/images/003/star_point1.gif" alt="" />';
	}else{
		$qualitystarcount.='<img src="/images/003/star_point2.gif" alt="" />';
	}
}

#사용후기 가격 별점수
$pricestarcount="";
for($i=1;$i<=5;$i++){
	if($i <= $countaverprice){
		$pricestarcount.='<img src="/images/003/star_point1.gif" alt="" />';
	}else{
		$pricestarcount.='<img src="/images/003/star_point2.gif" alt="" />';
	}
}

#사용후기 배송 별점수
$delitimestarcount="";
for($i=1;$i<=5;$i++){
	if($i <= $countaverdelitime){
		$delitimestarcount.='<img src="/images/003/star_point1.gif" alt="" />';
	}else{
		$delitimestarcount.='<img src="/images/003/star_point2.gif" alt="" />';
	}
}

#사용후기 추천 별점수
$recommendstarcount="";
for($i=1;$i<=5;$i++){
	if($i <= $countaverrecommend){
		$recommendstarcount.='<img src="/images/003/star_point1.gif" alt="" />';
	}else{
		$recommendstarcount.='<img src="/images/003/star_point2.gif" alt="" />';
	}
}

#상품평 타입 선택 탭
$sphotoreview=$sbestreview=$sbasicreview=$sallreview=" tabOff";
switch($rselectreview){
	case "photo":
		$sphotoreview = " tabOn";
		break;
	case "best":
		$sbestreview = " tabOn";
		break;
	case "basic":
		$sbasicreview = " tabOn";
		break;
	case "all":
	default:
		$sallreview = " tabOn";
		break;
}

// 상품 SNS 카운터
$product_SNS_Count = 0;
$product_SNS_Count_SQL = "SELECT count(`seq`) FROM `tblsnscomment` WHERE `pcode` = '".$productcode."' ; ";
if( false !== $p_sns_cnt = mysql_query( $product_SNS_Count_SQL, get_db_conn() ) ) {
	$product_SNS_Count = mysql_result($p_sns_cnt,0,0);
}


// 상품 공구 카운터
$product_Gonggu_Count = 0;
$product_Gonggu_Count_SQL = "SELECT count(`seq`) FROM `tblsnsGongguCmt` WHERE `pcode` = '".$productcode."' ; ";
if( false !== $p_Gonggu_cnt = mysql_query( $product_Gonggu_Count_SQL, get_db_conn() ) ) {
	$product_Gonggu_Count = mysql_result($p_Gonggu_cnt,0,0);
}


// 상품 공구 사용유무 체크
if($_pdata->gonggu_product == "Y"){
	$product_Gonggu_used_start = '';
	$product_Gonggu_used_end = '';
}else{
	$product_Gonggu_used_start = '<!--';
	$product_Gonggu_used_end = '-->';
}


?>

<HTML>
<HEAD>
<!--<TITLE><?=$_data->shopname." [".$_pdata->productname."]"?></TITLE>-->
<TITLE><?=$_data->shoptitle?></TITLE>
<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">
<META http-equiv="X-UA-Compatible" content="IE=Edge" />

<META name="description" content="<?=(strlen($_data->shopdescription)>0?$_data->shopdescription:$_data->shoptitle)?>">
<META name="keywords" content="<?=$_data->shopkeyword?>">
<link type="text/css" rel="stylesheet" href="/css/common.css" >

<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
<? include($Dir."lib/style.php")?>
<SCRIPT LANGUAGE="JavaScript">
<!--
function ClipCopy(url) {
	/*
	var tmp;
	tmp = window.clipboardData.setData('Text', url);
	if(tmp) {
		alert('주소가 복사되었습니다.');
	}*/	
	if (window.clipboardData) { // Internet Explorer
       tmp = window.clipboardData.setData("Text", url);
	   if(tmp) {
			alert('주소가 복사되었습니다.');
	   }
    } else {  
       temp = prompt("이 글의 트랙백 주소입니다. Ctrl+C를 눌러 클립보드로 복사하세요", url);
    }
}

<?if($_pdata->vender>0){?>
function custRegistMinishop() {
	if(document.custregminiform.memberlogin.value!="Y") {
		alert("로그인 후 이용이 가능합니다.");
		return;
	}
	owin=window.open("about:blank","miniregpop","width=100,height=100,scrollbars=no");
	owin.focus();
	document.custregminiform.target="miniregpop";
	document.custregminiform.action="minishop.regist.pop.php";
	document.custregminiform.submit();
}
<?}?>


function ableCouponPOP(productcode){
	var pcwin=window.open("/newfront/ablecoupons.php?productcode="+productcode,"CouponPop","width=617,height=450,scrollbars=yes");
}

function primage_view(img,type) {
	if (img.length==0) {
		alert("확대보기 이미지가 없습니다.");
		return;
	}
	var tmp = "toolbar=no,menubar=no,resizable=no,status=no";
	if(type=="1") {
		tmp+=",scrollbars=yes";
		sc="yes";
	} else {
		sc="";
	}
	url = "<?=$Dir.FrontDir?>primage_view.php?scroll="+sc+"&image="+img;

	window.open(url,"primage_view",tmp);
}

function change_quantity(gbn) {
	tmp=document.form1.quantity.value;
	if(gbn=="up") {
		tmp++;
	} else if(gbn=="dn") {
		if(tmp>1) tmp--;
	}
	if(document.form1.quantity.value!=tmp) {
		<? if($_pdata->assembleuse=="Y") { ?>
		if(getQuantityCheck(tmp)) {
			if(document.form1.assemblequantity) {
				document.form1.assemblequantity.value=tmp;
			}
			document.form1.quantity.value=tmp;
			setTotalPrice(tmp);
		} else {
			alert('구성상품 중 '+tmp+'보다 재고량이 부족한 상품있어서 변경을 불가합니다.');
			return;
		}
		<? } else { ?>
		document.form1.quantity.value=tmp;
		<? } ?>
	}
}


function change_quantity2(gbn,oidx) {

	$j('.rentOptionSelect').each(function(idx,el){
		var qty = $j(el).val();	

		if(gbn=="up") {
			qty++;
		} else if(gbn=="dn") {
			if(qty>1) qty--;
		}

		if($j(el).attr('idxcode')==oidx){
			if($j(el).val()!=qty) {
			<? if($_pdata->assembleuse=="Y") { ?>
				if(getQuantityCheck(qty)) {
					if(document.form1.assemblequantity) {
						document.form1.assemblequantity.value=qty;
					}
					$j(el).val(qty);
					setTotalPrice(qty);
				} else {
					alert('구성상품 중 '+qty+'보다 재고량이 부족한 상품있어서 변경을 불가합니다.');
					return;
				}
			<? } else { ?>
				if($j("#restCnt_"+oidx).val()<qty){
					alert('재고량이 부족해 변경을 불가합니다.');
					return;
				}else{
					$j(el).val(qty);
					$j("#option_price_"+oidx).text(number_format($j("#hidden_oprice_"+oidx).val()*qty));
				}
			<? } ?>
			}
		}
	});

	priceCalc2(document.form1);

}

function check_login() {
	if(confirm("로그인이 필요한 서비스입니다. 로그인을 하시겠습니까?")) {
		document.location.href="<?=$Dir.FrontDir?>login.php?chUrl=<?=getUrl()?>";
	}
}
<?if($_data->coupon_ok=="Y") {?>
function issue_coupon(coupon_code){
	document.couponform.mode.value="coupon";
	document.couponform.coupon_code.value=coupon_code;
	document.couponform.submit();
}
<?}?>

function checkRequest(){
	var f = document.form1;
	
	
	<?
	if($prentinfo['codeinfo']['pricetype']!="long") {//장기기간제 제외
	?>
		if(document.form1.p_bookingStartDate.value==""){
			alert("대여일을 먼저 선택 해주세요.");
			return;
		}
		<? if($prentinfo['codeinfo']['pricetype']!="period"){?>
		if(document.form1.startTime.value==""){
			alert("대여시간을 선택 해주세요.");
			return;
		}
		<? } ?>
		if(document.form1.p_bookingEndDate.value==""){
			alert("반납일을 먼저 선택 해주세요.");
			return;
		}
		<? if($prentinfo['codeinfo']['pricetype']!="period"){?>
		if(document.form1.endTime.value==""){
			alert("반납시간을 선택 해주세요.");
			return;
		}
		<? } ?>

		var now = new Date();
		var nowDay = now.getFullYear()+"-"+("0"+(now.getMonth()+1)).slice(-2)+"-"+("0"+now.getDate()).slice(-2);
		var nowTime = now.getHours();

		if($j('#p_bookingStartDate').val()==nowDay && $j('#startTime').val()<=nowTime){
			alert("현재시간보다 빠른 시간은 선택할 수 없습니다.");
			return;
		}

		if($j('#p_bookingStartDate').val()==$j('#p_bookingEndDate').val() && $j('#endTime').val()!="" && $j('#startTime').val()>=$j('#endTime').val()){
			alert("대여일과 반납일이 같은 경우 반납시간이 대여시간보다 빠를 수는 없습니다.");
			return;
		}
	<? 
	} //end 장기기간제 제외
	?>

	<?if(strlen($_ShopInfo->getMemid())==0){?>
		if(confirm("회원전용 기능입니다. 로그인 하시겠습니까?")){
			location.href="<?=$Dir.FrontDir?>login.php?chUrl=<?=getUrl()?>";
		}
		return;
	<? } ?>

	<? if($prentinfo['codeinfo']['pricetype']!="checkout"){?>
	if(document.form1.delitype_count.value>1){
		if(document.form1.ord_deli_type.options[document.form1.ord_deli_type.options.selectedIndex].value == "selec"){
			alert("배송수단을 선택해주세요.");
			document.form1.ord_deli_type.focus();
			return;
		}
	}
	<?}?>
	if( f.rentOptionList.value.length < 4 ) {
		alert("주문수량을 입력하세요.");
		return;
	} else {
		$j( "#checkRequest" ).dialog('open');
	}
	priceCalc(f);

}

function CheckForm(gbn,temp2) {

	if(gbn!="wishlist") {
		<?
		if( $_pdata->rental == 2 ) {
		?>
			var f = document.form1;
			priceCalc(f);
			if( f.rentOptionList.value.length < 4 ) {
				alert("옵션을 선택하세요.");
				return;
			}

			if(miniq>1 && f.rentOptions.value<miniq) {
				alert("해당 상품의 구매수량은 "+miniq+"개 이상 주문이 가능합니다.");
				f.rentOptions.focus();
				return;
			}

			if(maxq!="?" && maxq>0 && f.rentOptions.value>maxq) {
				alert("해당 상품의 구매수량는 "+maxq+"개 이하 주문이 가능합니다.");
				f.rentOptions.focus();
				return;
			}
		<?
		}else{
		?>
		if(document.form1.quantity.value.length==0 || document.form1.quantity.value==0) {
			alert("주문수량을 입력하세요.");
			document.form1.quantity.focus();
			return;
		}
		if(!IsNumeric(document.form1.quantity.value)) {
			alert("주문수량은 숫자만 입력하세요.");
			document.form1.quantity.focus();
			return;
		}
		if(miniq>1 && document.form1.quantity.value<miniq) {
			alert("해당 상품의 구매수량은 "+miniq+"개 이상 주문이 가능합니다.");
			document.form1.quantity.focus();
			return;
		}
		if(maxq!="?" && maxq>0 && document.form1.quantity.value>maxq) {
			alert("해당 상품의 구매수량는 "+maxq+"개 이하 주문이 가능합니다.");
			document.form1.quantity.focus();
			return;
		}
		<?
		}
		?>
	} else if(gbn=="ordernow2" || gbn=="ordernow3") {
		document.form1.action = "<?=$Dir.FrontDir?>basket2.php";
	}


	
	<? if($prentinfo['codeinfo']['pricetype']!="checkout"){?>
	if(document.form1.delitype_count.value>1 && gbn!="wishlist" && gbn!="prebasket"){
		if(document.form1.ord_deli_type.options[document.form1.ord_deli_type.options.selectedIndex].value == "selec"){
			alert("배송수단을 선택해주세요.");
			document.form1.ord_deli_type.focus();
			return;
		}
	}
	<? } ?>
	

	var now = new Date();
	var nowDay = now.getFullYear()+"-"+("0"+(now.getMonth()+1)).slice(-2)+"-"+("0"+now.getDate()).slice(-2);
	var nowTime = now.getHours();

	if(gbn!="wishlist" && gbn!="prebasket") {
		<?
		if( $_pdata->rental == 2 && $prentinfo['codeinfo']['pricetype']!="long") {//대여상품인경우
		?>
			if(document.form1.p_bookingStartDate.value==""){
				alert("대여일을 먼저 선택 해주세요.");
				return;
			}
			if(document.form1.p_bookingEndDate.value==""){
				alert("반납일을 먼저 선택 해주세요.");
				return;
			}

			if($j('#p_bookingStartDate').val()==nowDay && $j('#startTime').val()<=nowTime){
				alert("현재시간보다 빠른 시간은 선택할 수 없습니다.");
				return;
			}

			if($j('#p_bookingStartDate').val()==$j('#p_bookingEndDate').val() && $j('#startTime').val()>=$j('#endTime').val()){
				alert("대여일과 반납일이 같은 경우 반납시간이 대여시간보다 빠를 수는 없습니다.");
				return;
			}
		<? } ?>
	}

	if(gbn=="prebasket"){
		nowTime = nowTime + 1;
		if(nowTime>=24){
			now = new Date(now.getFullYear(),now.getMonth(),now.getDate()+1);
			nowDay = now.getFullYear()+"-"+("0"+(now.getMonth()+1)).slice(-2)+"-"+("0"+now.getDate()).slice(-2);
			nowTime = 0;
		}
		var endDay = now.getFullYear()+"-"+("0"+(now.getMonth()+1)).slice(-2)+"-"+("0"+now.getDate()).slice(-2);
		var endTime = now.getHours();
		var endDate;


		<?
		if($prentinfo['codeinfo']['pricetype']=="day"){//24시간제	
		?>
			endDate = new Date(now.getFullYear(),now.getMonth(),now.getDate()+1);
			endDay = endDate.getFullYear()+"-"+("0"+(endDate.getMonth()+1)).slice(-2)+"-"+("0"+endDate.getDate()).slice(-2);

			nowTime = <?=$prentinfo['codeinfo']['rent_stime']?>;
			endTime = <?=$prentinfo['codeinfo']['rent_etime']?>;
		<?
		}else if($prentinfo['codeinfo']['pricetype']=="time"){//시간제
		?>
			//endTime = nowTime + <?=$prentinfo['codeinfo']['base_time']?>;
		
			//현재시간이 영업마감시간을 지난 경우 다음날로 시작
			if(nowTime >= <?=$prentinfo['codeinfo']['rent_etime']?>){ 
				now = new Date(now.getFullYear(),now.getMonth(),now.getDate()+1);
				nowDay = now.getFullYear()+"-"+("0"+(now.getMonth()+1)).slice(-2)+"-"+("0"+now.getDate()).slice(-2);

				endDay = now.getFullYear()+"-"+("0"+(now.getMonth()+1)).slice(-2)+"-"+("0"+now.getDate()).slice(-2);
			
			}else if(now.getHours() > <?=$prentinfo['codeinfo']['rent_stime']?>){
				//현재시간이 영업시작시간보다 지난경우 현재시간보다 2시간뒤 예약
				nowTime = now.getHours()+1;
			}else{
				nowTime = <?=$prentinfo['codeinfo']['rent_stime']?>;
			}


			
			
			
			endTime = nowTime + <?=$prentinfo['codeinfo']['base_time']?>;
			if(endTime>24){
				endTime = ("0"+(endTime-24)).slice(-2);	
				endDate = new Date(now.getFullYear(),now.getMonth(),now.getDate()+1);
				endDay = endDate.getFullYear()+"-"+("0"+(endDate.getMonth()+1)).slice(-2)+"-"+("0"+endDate.getDate()).slice(-2);
			}

		<?
		}else if($prentinfo['codeinfo']['pricetype']=="checkout"){//숙박제
			if($prentinfo['codeinfo']['checkin_time']<$prentinfo['codeinfo']['checkout_time']){//1일
		?>
				endDate = nowDate;
				endDay = nowDay;
		<?
			}else{	//1박
		?>
				endDate = new Date(now.getFullYear(),now.getMonth(),now.getDate()+1);
				endDay = endDate.getFullYear()+"-"+("0"+(endDate.getMonth()+1)).slice(-2)+"-"+("0"+endDate.getDate()).slice(-2);
		<?
			}	
		?>
			nowTime = <?=$prentinfo['codeinfo']['checkin_time']?>;
			endTime = <?=$prentinfo['codeinfo']['checkout_time']?>;
		<?
		}else if($prentinfo['codeinfo']['pricetype']=="period"){//기간제
		?>
			endDate = new Date(now.getFullYear(),now.getMonth(),now.getDate()+<?=$prentinfo['codeinfo']['base_period']?>);
			endDay = endDate.getFullYear()+"-"+("0"+(endDate.getMonth()+1)).slice(-2)+"-"+("0"+endDate.getDate()).slice(-2);
		<?
		}
		?>

		document.form1.pre_bookingStartDate.value=nowDay;
		document.form1.pre_bookingEndDate.value=endDay;
		document.form1.pre_startTime.value=nowTime;
		document.form1.pre_endTime.value=endTime;
		//gbn = "";
		//return;
	}else{
		document.form1.pre_bookingStartDate.value="";
		document.form1.pre_bookingEndDate.value="";
		document.form1.pre_startTime.value="";
		document.form1.pre_endTime.value="";
	}

	document.form1.ordertype.value=gbn;
	


	if(temp2!="") {
		document.form1.opts.value="";
		try {
			for(i=0;i<temp2;i++) {
				if(document.form1.optselect[i].value==1 && document.form1.mulopt[i].selectedIndex==0) {
					alert('필수선택 항목입니다. 옵션을 반드시 선택하세요');
					document.form1.mulopt[i].focus();
					return;
				}
				document.form1.opts.value+=document.form1.mulopt[i].selectedIndex+",";
			}
		} catch (e) {}
	}
	<?
	if(eregi("S",$_cdata->type)) {
	?>
	if(typeof(document.form1.option)!="undefined" && document.form1.option.selectedIndex<2) {
		alert('해당 상품의 옵션을 선택하세요.');
		document.form1.option.focus();
		return;
	}
	if(typeof(document.form1.option)!="undefined" && document.form1.option.selectedIndex>=2) {
		arselOpt=document.form1.option.value.split("_");
		arselOpt[1] = (arselOpt[1] > 0)? arselOpt[1] :1;
		seq = parseInt(10*(arselOpt[1]-1)) + parseInt(arselOpt[0]);
		if(num[seq-1]==0) {
			alert('해당 상품의 옵션은 품절되었습니다. 다른 옵션을 선택하세요');
			document.form1.option.focus();
			return;
		}
		document.form1.option1.value = arselOpt[0];
		document.form1.option2.value = arselOpt[1];
	}
	<?
	}else{
	?>
	if(typeof(document.form1.option1)!="undefined" && document.form1.option1.selectedIndex<2) {
		alert('해당 상품의 옵션을 선택하세요.');
		document.form1.option1.focus();
		return;
	}
	if(typeof(document.form1.option2)!="undefined" && document.form1.option2.selectedIndex<2) {
		alert('해당 상품의 옵션을 선택하세요.');
		document.form1.option2.focus();
		return;
	}
	if(typeof(document.form1.option1)!="undefined" && document.form1.option1.selectedIndex>=2) {
		temp2=document.form1.option1.selectedIndex-1;
		if(typeof(document.form1.option2)=="undefined") temp3=1;
		else temp3=document.form1.option2.selectedIndex-1;
		if(num[(temp3-1)*10+(temp2-1)]==0) {
			alert('해당 상품의 옵션은 품절되었습니다. 다른 옵션을 선택하세요');
			document.form1.option1.focus();
			return;
		}
	}
	<?
	}
	?>
	if(typeof(document.form1.package_type)!="undefined" && typeof(document.form1.packagenum)!="undefined" && document.form1.package_type.value=="Y" && document.form1.packagenum.selectedIndex<2) {
		alert('해당 상품의 패키지를 선택하세요.');
		document.form1.packagenum.focus();
		return;
	}
	if(gbn!="wishlist") {
		<? if($_pdata->assembleuse=="Y") { ?>
		if(typeof(document.form1.assemble_type)=="undefined") {
			alert('현재 구성상품이 미등록된 상품입니다. 구매가 불가능합니다.');
			return;
		} else {
			if(document.form1.assemble_type.value.length>0) {
				arracassembletype = document.form1.assemble_type.value.split("|");
				document.form1.assemble_list.value="";

				for(var i=1; i<=arracassembletype.length; i++) {
					if(arracassembletype[i]=="Y") {
						if(document.getElementById("acassemble"+i).options.length<2) {
							alert('필수 구성상품의 상품이 없어서 구매가 불가능합니다.');
							document.getElementById("acassemble"+i).focus();
							return;
						} else if(document.getElementById("acassemble"+i).value.length==0) {
							alert('필수 구성상품을 선택해 주세요.');
							document.getElementById("acassemble"+i).focus();
							return;
						}
					}

					if(document.getElementById("acassemble"+i)) {
						if(document.getElementById("acassemble"+i).value.length>0) {
							arracassemblelist = document.getElementById("acassemble"+i).value.split("|");
							document.form1.assemble_list.value += "|"+arracassemblelist[0];
						} else {
							document.form1.assemble_list.value += "|";
						}
					}
				}
			} else {
				alert('현재 구성상품이 미등록된 상품입니다. 구매가 불가능합니다.');
				return;
			}
		}
		<? } ?>
		
		//장바구니 선택폴더에 담기
		var selFolder = "";
		for(i=0;i<document.getElementsByName("selfd[]").length;i++){
			if(document.getElementsByName("selfd[]")[i].checked){
				selFolder = document.getElementsByName("selfd[]")[i].value;
			}
		}
		document.form1.selFolder.value = selFolder;
		document.form1.submit();
	} else {
		document.wishform.opts.value=document.form1.opts.value;
		//document.wishform.deli_type.value=document.form1.ord_deli_type.options[document.form1.ord_deli_type.options.selectedIndex].value;
		if(typeof(document.form1.option1)!="undefined") document.wishform.option1.value=document.form1.option1.value;
		if(typeof(document.form1.option2)!="undefined") document.wishform.option2.value=document.form1.option2.value;
		
		//위시리스트 선택폴더에 담기
		var selCate = "";
		for(i=0;i<document.getElementsByName("sel[]").length;i++){
			if(document.getElementsByName("sel[]")[i].checked){
				selCate += document.getElementsByName("sel[]")[i].value +",";
			}
		}
		document.wishform.selCate.value = selCate;
		//위시리스트 선택폴더에 담기

		//window.open("about:blank","confirmwishlist","width=500,height=250,scrollbars=no");
		//document.wishform.submit();

		data = 'productcode=<?=$productcode?>&opts='+document.wishform.opts.value+'&option1='+document.wishform.option1.value+'&option2='+document.wishform.option2.value+'&selCate='+selCate;
		jQuery.ajax({
			url: "/front/confirm_wishlist.php",
			type: "POST",
			data: data,
			success: function(res) {
				$j('#confirmResult').html("찜하기가 되었습니다. <A style=\"color:#0000ff\" HREF=\"/front/wishlist.php\">찜목록 <b>바로가기</b></a>");
			},
			error: function(result) {
				console.log(result);
			},
			timeout: 30000
		});


	}
}

function view_review(cnt) {

	var review_list = document.getElementsByClassName('reviewspan');

	if(review_list.length>=0 && review_list[cnt].style.display == "none"){

		for(i=0;i<review_list.length;i++) {
			if(cnt==i) {
				if(review_list[i].style.display=="none") {
					review_list[i].style.display="";
				} else {
					review_list[i].style.display="none";
				}
			} else {
				review_list[i].style.display="none";
			}
		}
	} else {

		review_list[cnt].style.display = ( review_list[cnt].style.display == "none" ) ? "" : "none";
	}
}

function review_open(prcode,num) {
	window.open("<?=$Dir.FrontDir?>review_popup.php?prcode="+prcode+"&num="+num,"","width=450,height=400,scrollbars=yes");
}

/*function review_write() {
 if(typeof(document.all["reviewwrite"])=="object") {
 if(document.all["reviewwrite"].style.display=="none") {
 document.all["reviewwrite"].style.display="";
 } else {
 document.all["reviewwrite"].style.display="none";
 }
 }
 }*/

function review_write() {
	if(typeof(document.all["reviewwrite"])=="object") {
		if(document.all["reviewwrite"].style.display=="none") {
			document.all["reviewwrite"].style.display="";
		} else {
			document.all["reviewwrite"].style.display="none";
		}
	}
}

function write_review(){
	var userid = "<?=$_ShopInfo->getMemid()?>";
	var membergrant = "<?=$_data->review_memtype?>"; //회원 전용일경우
	var reviewgrant = "<?=$_data->review_type?>";
	var reviewetcgrant = "<?=$_data->ETCTYPE['REVIEW']?>";
	var _form = document.reviewWriteForm;
	if(reviewgrant == "N" || reviewetcgrant == "N"){
		alert("사용후기 설정이 되지 않아 사용 할 수 없습니다.");
		return;
	}else if(userid =="" && membergrant == "Y"){
		if(confirm("회원전용 기능입니다. 로그인 하시겠습니까?")){
			location.href="<?=$Dir.FrontDir?>login.php?chUrl=<?=getUrl()?>";
		}
		return;
	}else{

		if(_form.rname.value==""){
			alert("작성자를 입력해 주세요.");
			_form.rname.focus();
			return;
		}else if(_form.rname.rcontents){
			_form.rcontents.focus();
			return;
		}else{
			if(confirm("상품평을 등록 하시겠습니까?")){
				_form.mode.value="write";
				_form.submit();
			}

			return;
		}
	}
}

function CheckReview() {
	if(document.reviewform.rname.value.length==0) {
		alert("작성자 이름을 입력하세요.");
		document.reviewform.rname.focus();
		return;
	}
	if(document.reviewform.rcontent.value.length==0) {
		alert("상품평 내용을 입력하세요.");
		document.reviewform.rcontent.focus();
		return;
	}
	document.reviewform.mode.value="review_write";
	document.reviewform.submit();
}

var view_qnano="";
function view_qnacontent(idx) {
	if (idx=="W") {	//쓰기권한 없음
		alert("상품Q&A 게시판 문의 권한이 없습니다.");
	} else if(idx=="N") {	//일기권한 없음
		alert("해당 Q&A게시판 게시글을 보실 수 없습니다.");
	} else if(idx=="S") {	//잠금기능 설정된 글
		if(view_qnano.length>0 && view_qnano!=idx) {
			document.all["qnacontent"+view_qnano].style.display="none";
		}
		alert("해당 문의 글은 잠금기능이 설정된 게시글로\n\n직접 게시판에 가셔서 확인하셔야 합니다.");
	} else if(idx=="D") {
		if(view_qnano.length>0 && view_qnano!=idx) {
			document.all["qnacontent"+view_qnano].style.display="none";
		}
		alert("작성자가 삭제한 게시글입니다.");
	} else {
		try {
			if(document.all["qnacontent"+idx].style.display=="none") {
				view_qnano=idx;
				document.all["qnacontent"+idx].style.display="";
			} else {
				document.all["qnacontent"+idx].style.display="none";
			}
		} catch (e) {
			alert("오류로 인하여 게시내용을 보실 수 없습니다.");
		}
	}
}

function GoPage(gbn,block,gotopage) {
	document.idxform.action=document.idxform.action+"?#"+gbn;
	if(gbn=="review") {
		document.idxform.block.value=block;
		document.idxform.gotopage.value=gotopage;
	} else if(gbn=="prqna") {
		document.idxform.qnablock.value=block;
		document.idxform.qnagotopage.value=gotopage;
	}
	document.idxform.submit();
}

/* ################ 태그관련 ################## */
var IE = false ;
if (window.navigator.appName.indexOf("Explorer") !=-1) {
	IE = true;
}
//tag 금칙 문자 (%, &, +, <, >, ?, /, \, ', ", =,  \n)
var restrictedTagChars = /[\x25\x26\x2b\x3c\x3e\x3f\x2f\x5c\x27\x22\x3d\x2c\x20]|(\x5c\x6e)/g;
function check_tagvalidate(aEvent, input) {
	var keynum;
	if(typeof aEvent=="undefined") aEvent=window.event;
	if(IE) {
		keynum = aEvent.keyCode;
	} else {
		keynum = aEvent.which;
	}
	//  %, &, +, -, ., /, <, >, ?, \n, \ |
	var ret = input.value;
	if(ret.match(restrictedTagChars) != null ) {
		ret = ret.replace(restrictedTagChars, "");
		input.value=ret;
	}
}

function tagCheck(productcode) {
	<?if(strlen($_ShopInfo->getMemid())>0){?>
	var obj = document.all;
	if(obj.searchtagname.value.length < 2 ){
		alert("태그를(2자 이상) 입력해 주세요!");
		obj.searchtagname.focus();
		return;
	}
	goProc("prtagreg",productcode);
	return;
	<?}else{?>
	alert("로그인 후 작성해 주세요!");
	return;
	<?}?>
}

function goProc(mode,productcode){
	var obj = document.all;
	if(mode=="prtagreg") {
		succFun=myFunction;
		var tag=obj.searchtagname.value;
		var path="<?=$Dir.FrontDir?>tag.xml.php?mode="+mode+"&productcode="+productcode+"&tagname="+tag;
		obj.searchtagname.value="처리중 입니다!";
	} else {
		succFun=prTaglist;
		var path="<?=$Dir.FrontDir?>tag.xml.php?mode="+mode+"&productcode="+productcode;
	}
	var myajax = new Ajax(path,
		{
			onComplete: function(text) {
				succFun(text,productcode);
			}
		}
	).request();
}

function myFunction(request,productcode){
	var msgtmp = request;
	var splitString = msgtmp.split("|");

	//다시 초기화
	var obj = document.all;
	obj.searchtagname.value="";
	if(splitString[0]=="OK") {
		var tag = splitString[2];
		if(splitString[1]=="0") {

		} else if(splitString[1]=="1") {
			goProc("prtagget",productcode);
		}
	} else if(splitString[0]=="NO") {
		alert(splitString[1]);
	}
}

function prTaglist(request) {
	var msgtmp = request;
	var splitString = msgtmp.split("|");
	if(splitString[0]=="OK") {
		document.all["prtaglist"].innerHTML=splitString[1];
	} else if(splitString[0]=="NO") {
		alert(splitString[1]);
	}
}

<? if($_pdata->assembleuse=="Y") { ?>
var currentSelectIndex = "";
function setCurrentSelect(thisSelectIndex) {
	currentSelectIndex = thisSelectIndex;
}

function setAssenbleChange(thisObj,idxValue) {
	if(thisObj.value.length>0) {
		thisValueSplit = thisObj.value.split('|');
		if(thisValueSplit[1].length>0) {
			if(Number(thisValueSplit[1])==0) {
				alert('현재 상품은 품절 상품입니다.');
			} else {
				if(Number(document.form1.quantity.value)>0) {
					if(Number(thisValueSplit[1]) < Number(document.form1.quantity.value)) {
						alert('구성 상품의 재고량이 부족합니다.');
					} else {
						setTotalPrice(document.form1.quantity.value);
						if(thisValueSplit.length>3 && thisValueSplit[4].length>0 && document.getElementById("acimage"+idxValue)) {
							document.getElementById("acimage"+idxValue).src="<?=$Dir.DataDir."shopimages/product/"?>"+thisValueSplit[4];
						} else {
							document.getElementById("acimage"+idxValue).src="<?=$Dir."images/acimage.gif"?>";
						}
						return;
					}
				} else {
					alert('본 상품 수량을 입력해 주세요.');
				}
			}
		} else {
			setTotalPrice(document.form1.quantity.value);
			if(thisValueSplit.length>3 && thisValueSplit[4].length>0 && document.getElementById("acimage"+idxValue)) {
				document.getElementById("acimage"+idxValue).src="<?=$Dir.DataDir."shopimages/product/"?>"+thisValueSplit[4];
			} else {
				document.getElementById("acimage"+idxValue).src="<?=$Dir."images/acimage.gif"?>";
			}
			return;
		}

		thisObj.options[currentSelectIndex].selected = true;
	} else {
		setTotalPrice(document.form1.quantity.value);
		document.getElementById("acimage"+idxValue).src="<?=$Dir."images/acimage.gif"?>";
		return;
	}
}

function getQuantityCheck(tmp) {
	var i=true;
	var j=1;
	while(i) {
		if(document.getElementById("acassemble"+j)) {
			if(document.getElementById("acassemble"+j).value) {
				arracassemble = document.getElementById("acassemble"+j).value.split("|");
				if(arracassemble[1].length>0 && Number(tmp) > Number(arracassemble[1])) {
					return false;
				}
			}
		} else {
			i=false;
		}
		j++;
	}
	return true;
}

function assemble_proinfo(idxValue) { // 조립상품 개별 상품 정보보기
	if(document.getElementById("acassemble"+idxValue)) {
		if(document.getElementById("acassemble"+idxValue).value.length>0) {
			thisValueSplit = document.getElementById("acassemble"+idxValue).value.split('|');
			if(thisValueSplit[0].length>0) {
				product_info_pop("assemble_proinfo.php?op=<?=$productcode?>&np="+thisValueSplit[0],"assemble_proinfo_"+thisValueSplit[0],700,700,"yes");
			} else {
				alert("해당 상품정보가 존재하지 않습니다.");
			}
		}
	}
}

function product_info_pop(url,win_name,w,h,use_scroll) {
	var x = (screen.width - w) / 2;
	var y = (screen.height - h) / 2;
	if (use_scroll==null) use_scroll = "no";
	var use_option = "";
	use_option = use_option + "toolbar=no, channelmode=no, location=no, directories=no, resizable=no, menubar=no";
	use_option = use_option + ", scrollbars=" + use_scroll + ", left=" + x + ", top=" + y + ", width=" + w + ", height=" + h;

	var win = window.open(url,win_name,use_option);
	return win;
}
<? } ?>

var productUrl = "http://<?=$_data->shopurl?>?prdt=<?=$productcode?>";
var productName = "<?=strip_tags($_pdata->productname)?>";
function goFaceBook()
{
	var href = "http://www.facebook.com/sharer/sharer.php?u=" + encodeURIComponent(productUrl) + "&t=" + encodeURIComponent(productName);
	var a = window.open(href, 'Facebook', '');
	if (a) {
		a.focus();
	}
}

function goTwitter()
{
	var href = "http://twitter.com/share?text=" + encodeURIComponent(productName) + " " + encodeURIComponent(productUrl);
	var a = window.open(href, 'Twitter', '');
	if (a) {
		a.focus();
	}
}


function snsSendCheck(type){
	<?if($arSnsType[0] != "N"){?>
	if(confirm("적립금을 받으려면 로그인이 필요합니다. 로그인하시겠습니까?")){
		document.location.href="<?=$Dir.FrontDir?>login.php?chUrl=<?=getUrl()?>";
	}else{
		<?}?>
		if(type =="t")
			goTwitter();
		else if(type =="f")
			goFaceBook();
		else if(type =="m")
			goMe2Day();
		<?if($arSnsType[0] != "N") {?>
	}
	<?}?>
}


//카테고리 뷰
function qrCodeView(obj,type){
	var obj;
	var div = eval("document.all." + obj);

	if(type == 'open'){
		div.style.display = "block";
	}else if (type == 'over'){
		div.style.display = "block";
	}else if (type == 'out'){
		div.style.display = "none";
	}
}


function wishPopup(opti){
	$j('#wishlist').bPopup({
		closeClass:'closeBtn',
		content:'ajax', //'ajax', 'iframe' or 'image'
        contentContainer:'.wishPopup'
        //loadUrl:'/front/wishPopup.php?opti='+opti
	});
}

function basketPopup(opti){
	var f = document.form1;
	/*
	<?
	if($prentinfo['codeinfo']['pricetype']!="long") {//장기기간제 제외
	?>
		if(document.form1.p_bookingStartDate.value==""){
			alert("대여일을 먼저 선택 해주세요.");
			return;
		}
		<? if($prentinfo['codeinfo']['pricetype']!="period"){?>
		if(document.form1.startTime.value==""){
			alert("대여시간을 선택 해주세요.");
			return;
		}
		<? } ?>
		if(document.form1.p_bookingEndDate.value==""){
			alert("반납일을 선택 해주세요.");
			return;
		}
		<? if($prentinfo['codeinfo']['pricetype']!="period"){?>
		if(document.form1.endTime.value==""){
			alert("반납시간을 선택 해주세요.");
			return;
		}
		<? } ?>

		var now = new Date();
		var nowDay = now.getFullYear()+"-"+("0"+(now.getMonth()+1)).slice(-2)+"-"+("0"+now.getDate()).slice(-2);
		var nowTime = now.getHours();

		if($j('#p_bookingStartDate').val()==nowDay && $j('#startTime').val()<=nowTime){
			alert("현재시간보다 빠른 시간은 선택할 수 없습니다.");
			return;
		}

		if($j('#p_bookingStartDate').val()==$j('#p_bookingEndDate').val() && $j('#endTime').val()!="" && $j('#startTime').val()>=$j('#endTime').val()){
			alert("대여일과 반납일이 같은 경우 반납시간이 대여시간보다 빠를 수는 없습니다.");
			return;
		}
	<? 
	} //end 장기기간제
	?>
*/
	$j('#basketlist').bPopup({
		closeClass:'closeBtn',
		content:'ajax', //'ajax', 'iframe' or 'image'
        contentContainer:'.basketPopup'
        //loadUrl:'/front/wishPopup.php?opti='+opti
	});
}

function deliPopup1(){
	$j('#delilist1').bPopup({
		closeClass:'closeBtn',
		content:'ajax', //'ajax', 'iframe' or 'image'
        contentContainer:'.deliPopup1'
	});
}
function deliPopup2(){
	$j('#delilist2').bPopup({
		closeClass:'closeBtn',
		content:'ajax', //'ajax', 'iframe' or 'image'
        contentContainer:'.deliPopup2'
	});
}
function deliPopup3(){
	$j('#delilist3').bPopup({
		closeClass:'closeBtn',
		content:'ajax', //'ajax', 'iframe' or 'image'
        contentContainer:'.deliPopup3'
	});
}
function deliPopup4(){
	$j('#delilist4').bPopup({
		closeClass:'closeBtn',
		content:'ajax', //'ajax', 'iframe' or 'image'
        contentContainer:'.deliPopup4'
	});
}
function deliPopup5(){
	$j('#delilist5').bPopup({
		closeClass:'closeBtn',
		content:'ajax', //'ajax', 'iframe' or 'image'
        contentContainer:'.deliPopup5'
	});
}

function disableCheck(obj) { 
	if(obj.id=="endTime" && (document.form1.p_bookingStartDate.value=="" || document.form1.startTime.value=="") && document.form1.endTime.value!=""){
		alert("대여일 및 대여시간을 먼저 선택 해주세요.");
		document.form1.endTime.value = "";
		return;
	}
	if (obj[obj.selectedIndex].className=='disabled') { 
		alert("선택하신 시간은 영업시간이 아니기 때문에 방문이 불가능합니다.\n "); 
		for (var i=0; obj[i].className=="disabled"; i++); 
		obj.selectedIndex = i; 
		return; 
	} 
	priceCalc2(obj);
}
//-->
</SCRIPT>

<style type="text/css"> 
<!-- 
option.disabled { color: lightgrey; } 
--> 
</style> 

<? @include_once $Dir.'_NaverCheckout/callback.js.php'; ?>
<?
//시중가격이 없을 때 할인율 표시 숨기기
if($_pdata->discountRate == 0) {
	?>
	<style>
		.discountrate{display:none;}
	</style>
<? } ?>

</HEAD>

<body<?=(substr($_data->layoutdata["MOUSEKEY"],0,1)=="Y"?" oncontextmenu=\"return false;\"":"")?><?=(substr($_data->layoutdata["MOUSEKEY"],1,1)=="Y"?" ondragstart=\"return false;\" onselectstart=\"return false;\"":"")?> leftmargin="0" marginwidth="0" topmargin="0" marginheight="0"><?=(substr($_data->layoutdata["MOUSEKEY"],2,1)=="Y"?"<meta http-equiv=\"ImageToolbar\" content=\"No\">":"")?>

<?
include ($Dir.MainDir.$_data->menu_type.".php");
?>
<script type="text/javascript" src="/js/rental.js"></script>
<table border=0 cellpadding=0 cellspacing=0 width=100%>
	<tr>
		<td>
			<?
			if(strlen($_cdata->detail_type)==5) {
				//echo($Dir.TempletDir."product/detail_".$_cdata->detail_type.".php");
				include($Dir.TempletDir."product/detail_".$_cdata->detail_type.".php");
			} else if (strlen($_cdata->detail_type)==6 && substr($_cdata->detail_type,5,6)=="U") {
				$tmp = categorySubTab($code);
				$_ndata = NULL;

				do{
					$chkcode = '';
					for($i=0;$i<4;$i++) $chkcode .= ($i < $tmp['depth'])?$tmp['code'.chr(65+$i)]:'000';

					$sql = "SELECT leftmenu,body,code FROM ".$designnewpageTables." WHERE type='prdetail' AND (code='".$chkcode."' OR code='ALL') AND leftmenu='Y' ORDER BY code ASC LIMIT 1 ";
					$result=mysql_query($sql,get_db_conn());

					if(mysql_num_rows($result)){
						$_ndata=mysql_fetch_object($result);
						mysql_free_result($result);
					}else{
						$csql = "select dsameparent from tblproductcode where codeA='".$tmp['codeA']."' and codeB='".$tmp['codeB']."' and codeC='".$tmp['codeC']."' and codeD='".$tmp['codeD']."' limit 1";
						$cresult = mysql_query($csql);
						if($cresult && mysql_num_rows($cresult) && mysql_result($cresult,0,0) == '1'){
							$tmp['depth'] -= 1;
							$tmp['code'.chr(65+$tmp['depth'])] = '000';
							continue;
						}
						mysql_free_result($cresult);
						$tmp['depth'] = 0;
					}
				}while(empty($_ndata) && $tmp['depth'] > 0);

				if($_ndata) {
					$body=$_ndata->body;
					$body=str_replace("[DIR]",$Dir,$body);

					/************************************************************* S . 2012-04-13  NaverCheckout *************************************************************/
					$available = strlen($_pdata->quantity) > 0 && $_pdata->quantity <= 0 ? 'N' : 'Y';

					// 체크아웃으로 결제가 불가능한 상품
					$notCheckout = array('023', '008');
					foreach ($notCheckout as $value) {
						if (substr($_pdata->productcode, 0, 3) == $value) {
							$available = 'N';
							break;
						}
					}
					/************************************************************* E . 2012-04-13  NaverCheckout *************************************************************/
					include($Dir.TempletDir."product/detail_U.php");

				} else {

					include($Dir.TempletDir."product/detail_".substr($_cdata->detail_type,0,5).".php");
				}
			}
			?>
		</td>
	</tr>
	<form name=couponform method=get action="<?=$_SERVER[PHP_SELF]?>">
		<input type=hidden name=mode value="">
		<input type=hidden name=coupon_code value="">
		<input type=hidden name=productcode value="<?=$productcode?>">
		<?=($brandcode>0?"<input type=hidden name=brandcode value=\"".$brandcode."\">\n":"")?>
	</form>
	<form name=idxform method=get action="<?=$_SERVER[PHP_SELF]?>">
		<input type=hidden name=productcode value="<?=$productcode?>">
		<input type=hidden name=sort value="<?=$sort?>">
		<input type=hidden name=block value="<?=$block?>">
		<input type=hidden name=gotopage value="<?=$gotopage?>">
		<input type=hidden name=qnablock value="<?=$qnablock?>">
		<input type=hidden name=qnagotopage value="<?=$qnagotopage?>">
		<input type=hidden name=review value="<?=$rselectreview?>">
		<?=($brandcode>0?"<input type=hidden name=brandcode value=\"".$brandcode."\">\n":"")?>
	</form>
	<form name=wishform method=post action="<?=$Dir.FrontDir?>confirm_wishlist.php" target="confirmwishlist">
		<input type=hidden name=productcode value="<?=$productcode?>">
		<input type=hidden name=opts>
		<input type=hidden name=option1>
		<input type=hidden name=option2>
		<input type=hidden name=deli_type>
		<input type=hidden name=selCate>
	</form>

	<?if($_pdata->vender>0){?>
		<form name=custregminiform method=post>
			<input type=hidden name=sellvidx value="<?=$_vdata->vender?>">
			<input type=hidden name=memberlogin value="<?=(strlen($_ShopInfo->getMemid())>0?"Y":"N")?>">
		</form>
	<?}?>
</table>
<?if($_data->sns_ok == "Y" && ($_pdata->sns_state == "Y" || $_pdata->gonggu_product == "Y")){?>
	<script type="text/javascript" src="<?=$Dir?>lib/sns.js"></script>
	<script type="text/javascript">
		<!--
		var pcode = "<?=$productcode ?>";
		var memId = "<?=$_ShopInfo->getMemid() ?>";
		var fbPicture ="<?=$fbThumb?>";
		var preShowID ="";
		var snsCmt = "";
		var snsLink = "";
		var snsType = "";
		var gRegFrm = "";

		$j(document).ready( function () {
			if(memId != ""){
				snsImg();
				snsInfo();
			}
			showSnsComment();
			showGongguCmt();
		});
		//-->
	</script>
	<? include ($Dir.FrontDir."snsGongguToCmt.php") ?>
<?}?>
<div id="create_openwin" style="display:none"></div>
<? include ($Dir."lib/bottom.php") ?>

<?=$onload?>
<script language="JavaScript">
<!--
function _orderNaverCheckout() {
	if(document.form1.quantity.value.length==0 || document.form1.quantity.value==0) {
		alert("주문수량을 입력하세요.");
		document.form1.quantity.focus();
		return;
	}
	if(!IsNumeric(document.form1.quantity.value)) {
		alert("주문수량은 숫자만 입력하세요.");
		document.form1.quantity.focus();
		return;
	}
	if(miniq>1 && document.form1.quantity.value<=1) {
		alert("해당 상품의 구매수량은 "+miniq+"개 이상 주문이 가능합니다.");
		document.form1.quantity.focus();
		return;
	}

	if("<?=$opti?>" != "") {
		document.form1.opts.value="";
		try {
			for(i=0;i<"<?=$opti?>";i++) {
				if(document.form1.optselect[i].value==1 && document.form1.mulopt[i].selectedIndex==0) {
					alert('필수선택 항목입니다. 옵션을 반드시 선택하세요');
					document.form1.mulopt[i].focus();
					return;
				}
				document.form1.opts.value+=document.form1.mulopt[i].selectedIndex+",";
			}
		} catch (e) {}
	}

	if(typeof(document.form1.option1)!="undefined" && document.form1.option1.selectedIndex<2) {
		alert('해당 상품의 옵션을 선택하세요.');
		document.form1.option1.focus();
		return;
	}
	if(typeof(document.form1.option2)!="undefined" && document.form1.option2.selectedIndex<2) {
		alert('해당 상품의 옵션을 선택하세요.');
		document.form1.option2.focus();
		return;
	}
	if(typeof(document.form1.option1)!="undefined" && document.form1.option1.selectedIndex>=2) {
		temp2=document.form1.option1.selectedIndex-1;
		if(typeof(document.form1.option2)=="undefined") temp3=1;
		else temp3=document.form1.option2.selectedIndex-1;
		if(num[(temp3-1)*20+(temp2-1)]==0) {
			alert('해당 상품의 옵션은 품절되었습니다. 다른 옵션을 선택하세요');
			document.form1.option1.focus();
			return;
		}
	}
	if(typeof(document.form1.package_type)!="undefined" && typeof(document.form1.packagenum)!="undefined" && document.form1.package_type.value=="Y" && document.form1.packagenum.selectedIndex<2) {
		alert('해당 상품의 패키지를 선택하세요.');
		document.form1.packagenum.focus();
		return;
	}

	<? if($_pdata->assembleuse=="Y") { ?>
	if(typeof(document.form1.assemble_type)=="undefined") {
		alert('현재 구성상품이 미등록된 상품입니다. 구매가 불가능합니다.');
		return;
	} else {
		if(document.form1.assemble_type.value.length>0) {
			arracassembletype = document.form1.assemble_type.value.split("|");
			document.form1.assemble_list.value="";

			for(var i=1; i<=arracassembletype.length; i++) {
				if(arracassembletype[i]=="Y") {
					if(document.getElementById("acassemble"+i).options.length<2) {
						alert('필수 구성상품의 상품이 없어서 구매가 불가능합니다.');
						document.getElementById("acassemble"+i).focus();
						return;
					} else if(document.getElementById("acassemble"+i).value.length==0) {
						alert('필수 구성상품을 선택해 주세요.');
						document.getElementById("acassemble"+i).focus();
						return;
					}
				}

				if(document.getElementById("acassemble"+i)) {
					if(document.getElementById("acassemble"+i).value.length>0) {
						arracassemblelist = document.getElementById("acassemble"+i).value.split("|");
						document.form1.assemble_list.value += "|"+arracassemblelist[0];
					} else {
						document.form1.assemble_list.value += "|";
					}
				}
			}
		} else {
			alert('현재 구성상품이 미등록된 상품입니다. 구매가 불가능합니다.');
			return;
		}
	}
	<? } ?>

	var param = "";
	param += "?goodsId=<?=$_pdata->productcode?>";
	param += "&goodsName=<?=$_pdata->productname?>";
	param += "&goodsPrice=<?=$_pdata->sellprice?>";
	param += "&goodsCount=" + document.getElementById("quantity").value;
	param += "&isTransMoney=1";
	param += "&goodsTransType=0";
	param += "&limitGoodsTransMoney=<?=$_data->deli_miniprice?>";
	param += "&goodsTransMoney=<?=$_data->deli_basefee?>";

	var goodsOption = "";
	<?
		if (strlen($optcode) > 0) {
			foreach ($optionadd as $key => $value) {
				if ($value) $newOptionadd[] = $value;
			}

			$i = 0;
			foreach ($optionadd as $key => $value) {
				if ($value) {
					$arrOption = explode('', $value);
	?>
	goodsOption += "<?=$arrOption[0]?>:" + document.form1.mulopt[<?=$key?>].value;
	<? if ($i < count($newOptionadd) - 1) { ?>
	goodsOption += "/";
	<? } ?>
	<?
					$i++;
				}

			}

		} else {
	?>
	if (document.getElementById("option1").innerText != '') {
		<? $optionName1 = explode(',', $_pdata->option1);	?>
		goodsOption += "<?=$optionName1[0]?>:" + document.getElementById("option1").value;
	}
	if (document.getElementById("option2").innerText != '') {
		<? $optionName2 = explode(',', $_pdata->option2);	?>
		goodsOption += "/";
		goodsOption += "<?=$optionName2[0]?>:" + document.getElementById("option2").value;
	}
	<?
		}
	?>

	param += "&goodsOption=" + encodeURIComponent(goodsOption);

	location.href = "/_NaverCheckout/order.php" + param;

}

function _wishlistNaverCheckout() {
	var isGoodsImage = 1;
	var isGoodsThumbImage = 1;
	var goodsImage = "<?=$_pdata->maximage?>";
	var goodsThumbImage = "<?=$_pdata->tinyimage?>";

	if (!goodsImage) {
		isGoodsImage = 0;
		goodsImage = "";
	}
	if (!goodsThumbImage) {
		isGoodsThumbImage = 0;
		goodsThumbImage = "";
	}

	var param = "";
	param += "?goodsId=<?=$_pdata->productcode?>";
	param += "&goodsName=<?=$_pdata->productname?>";
	param += "&goodsPrice=<?=$_pdata->sellprice?>";
	param += "&isGoodsImage=" + isGoodsImage;
	param += "&goodsImage=" + goodsImage;
	param += "&isGoodsThumbImage=" + isGoodsThumbImage;
	param += "&goodsThumbImage=" + goodsThumbImage;

	//alert(param);

	window.open("/_NaverCheckout/wishlist.php" + param, "_wishlistNaverCheckout", "width=397, height=304, scrollbars=yes");
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

function reviewSelect(type){
	var link ="<?=$reviewlink?>";
	location.href = link+"&review="+type+"#3";
	return;
}

//-->
</script>
<form name="codeNaviForm" id="codeNaviForm" action="/front/productlist.php">
	<input type="hidden" name="code" value="">
</form>

<?
if($_pdata->today_reserve=="Y"){
	$minDate = 0;
}else{
	$minDate = 1;
}
?>
<script language="javascript" type="text/javascript">
$j(function(){
	/*
	var now = new Date();
	var nowday = now.getDate();
	var nowmonth = parseInt(now.getMonth())+1;
	var nowyear = now.getFullYear();
	var today = nowyear+"-"+nowmonth+"-"+nowday;

	if($j("#p_bookingStartDate").val()=="" || $j("#p_bookingStartDate").val()==now.getDate()){
		if($j("#startTime").val()<=now.getDate()){
		}
	}
	*/
	if($j("#p_bookingSDate")){
		$j("#p_bookingSDate").datepicker({
			showOn: "both",
			dateFormat:'mm/dd(DD)',
			dayNames: ['일','월','화','수','목','금','토'],
			buttonImage: "/images/mini_cal_calen.gif",
			minDate: <?=$minDate?>,
			buttonImageOnly: true,
			buttonText: "대여",
			altField: "#p_bookingStartDate",
			altFormat: "yy-mm-dd",
			onClose: function( selectedDate ) {
			}
			,onSelect:function(selectedDate,picker){
				//$j("#p_bookingEDate").datepicker( "option", "minDate", selectedDate );
				if($j("#p_bookingEDate").val()<$j("#p_bookingSDate").val()){
					//alert("반납일이 대여일이전일 수 없습니다.");
					$j("#p_bookingSDate").val("대여일");
				}
				$j("#p_bookingSDate").css("color","#000000");
				if(fixrenttime()) priceCalc2(document.form1);
			 }
		});

		$j("#p_bookingEDate").datepicker({
			showOn: "both",
			dateFormat:'mm/dd(DD)',
			dayNames: ['일','월','화','수','목','금','토'],
			buttonImage: "/images/mini_cal_calen.gif",
			minDate: <?=$minDate?>,
			buttonImageOnly: true,
			buttonText: "반납",
			altField: "#p_bookingEndDate",
			altFormat: "yy-mm-dd",
			onClose: function( selectedDate ) {
			}
			,onSelect:function(selectedDate,picker){
				//$j("#p_bookingSDate").datepicker( "option", "maxDate", selectedDate );
				if($j("#p_bookingSDate").val()=="대여일"){
					alert("대여일 먼저 선택하세요.");$j("#p_bookingEDate").val("반납일");
				}
				if($j("#p_bookingEDate").val()<$j("#p_bookingSDate").val()){
					alert("반납일이 대여일이전일 수 없습니다.");$j("#p_bookingEDate").val("반납일");
				}
				$j("#p_bookingEDate").css("color","#000000");
				if(fixrenttime()) priceCalc2(document.form1);
			 }
		});
/*
		$j("#p_bookingStartDate" ).datepicker({
		  showOn: "both",
		  dateFormat:'yy-mm-dd',
		  buttonImage: "/images/mini_cal_calen.gif",
		  minDate: <?=$minDate?>,
		  buttonImageOnly: true,
		  buttonText: "출고일",
		  onClose: function( selectedDate ) {
		  }
		  ,onSelect:function(selectedDate,picker){		
			  	$j("#p_bookingEndDate" ).datepicker( "option", "minDate", selectedDate );
				if(fixrenttime()) priceCalc2(document.form1);			
		  }
		});
		
		$j("#p_bookingEndDate" ).datepicker({
		  showOn: "both",
		  dateFormat:'yy-mm-dd',
		  buttonImage: "/images/mini_cal_calen.gif",
		  minDate: 1,
		  buttonImageOnly: true,
		  buttonText: "반납일",
		  onClose: function( selectedDate ){
		  }
		  ,onSelect:function(selectedDate,picker){
			  $j( "#p_bookingStartDate" ).datepicker( "option", "maxDate", selectedDate );
			  if(fixrenttime()) priceCalc2(document.form1);			
		  }
		});
*/
		//priceCalc2(document.form1);
	}

	if($j("#p_bookingSDate").val()==""){
		$j('#startTime').val("");
	}
	if($j("#p_bookingEDate").val()==""){
		$j('#endTime').val("");
	}
	
	<? if($prentinfo['codeinfo']['pricetype']=="long"){?>
	priceCalc2(document.form1);
	<?}?>
});




function fixrenttime(){
	
	<? if($prentinfo['codeinfo']['pricetype'] =='time'){ ?>
	var st = $j('#p_bookingSDate').datepicker('getDate');
	var ed = $j('#p_bookingEDate').datepicker('getDate');	
	diff = (ed.getTime() - st.getTime()) / (60 * 60 * 1000);
	
	if($j("#p_bookingSDate").val()=="대여일" || $j("#p_bookingEDate").val()=="반납일"){
		return false;
	}

	if($j("#p_bookingEDate").val() == $j("#p_bookingSDate").val()){
		//if($j('#endTime').val() <24){
			//alert('예약 시간은 최소 24 시간 입니다.');
			
		//	$j('#endTime').find('option[value=23]').attr('selected',true);
		//	return false;
		//}
	}
	<? } ?>
	return true;
}

<? // if($prentinfo['codeinfo']['pricetype'] =='time'){ ?>
	
</script>
<?
// 카테고리 리스트
$wishCateList = wishCateList();

?>
<div id="wishlist" class="wishPopup" style="display:none;">

<script language="javascript" type="text/javascript">
function wishCateModifyOpen(title,idx){
	var cateSetDiv2 = document.getElementById('cateSetDiv2');
	var okDiv = document.getElementById('okDiv');
	cateSetDiv2.style.display = ( cateSetDiv2.style.display == 'none' ) ? 'block' : 'none';
	okDiv.style.display = ( okDiv.style.display == 'none' ) ? 'block' : 'none';
	document.wishForm.cateTitle2.value = title;
	document.wishForm.delCateIdx.value = idx;			
}

function wishCateViewOnOff2 ( t,t2 ) {
	t.style.display = ( t.style.display == 'none' ) ? 'block' : 'none';
	t2.style.display = ( t2.style.display == 'none' ) ? 'block' : 'none';
}

function wishManage ( mode, idx ) {
	if(mode=="cateDelete"){
		if( confirm('폴더 삭제시 폴더 내에 저장된 상품들도 함께 삭제됩니다\r\n폴더를 삭제하시겠습니까?') ) {

			data = 'mode='+mode+'&delCateIdx='+idx;

			jQuery.ajax({
				url: "/front/wishPopup.php",
				type: "POST",
				data: data,
				success: function(res) {
					$j('#wishResult').html(res);
				},
				error: function(result) {
					console.log(result);
				},
				timeout: 30000
			});
		}
	}else if(mode=="cateModify"){

		data = 'mode='+mode+'&delCateIdx='+document.wishForm.delCateIdx.value+'&cateTitle='+$j("#cateTitle2").val();

		jQuery.ajax({
			url: "/front/wishPopup.php",
			type: "POST",
			data: data,
			success: function(res) {
				wishCateModifyOpen(cateSetDiv2,okDiv);
				$j('#wishResult').html(res);
			},
			error: function(result) {
				console.log(result);
			},
			timeout: 30000
		});


	}else if(mode=="cateInsert"){
		data = 'mode='+mode+'&cateTitle='+$j("#cateTitle").val();

		jQuery.ajax({
			url: "/front/wishPopup.php",
			type: "POST",
			data: data,
			success: function(res) {
				wishCateViewOnOff2(cateSetDiv,okDiv);
				$j('#wishResult').html(res);
			},
			error: function(result) {
				console.log(result);
			},
			timeout: 30000
		});

	}
	
}


</script>
<div class="searchPw popwin">
	<div class="spw_wrap">
		<p class="tit">찜하기</p>
		<p class="desc">프로젝트나 주제별로 편리하게 보관할 수 있습니다.</p>
		<div class="spwform">
			<form id="wishForm" name="wishForm" method="post">
			<input type="hidden" name="mode">
			<input type="hidden" name="delCateIdx" value=''>
				<fieldset>
					<legend>찜하기</legend>
					<ul id="wishResult">
						<li style="overflow:hidden;">
							<input type="checkbox" class="checkbox" name="sel[]" value="0" checked>기본폴더
							<span style="float:right;"><img src="/data/design/img/detail/icon_lock1.gif"></span></li>
						<?
						foreach ( $wishCateList as $k=>$v ) {					
							echo "<li style=\"overflow:hidden;\"><input type=\"checkbox\" class=\"checkbox\" name=\"sel[]\" value=\"".$k."\">".$v;
							echo "<p style=\"float:right;\"><input type='image' value='수정'src=\"/data/design/img/detail/icon_edit.gif\"  onclick=\"wishCateModifyOpen('".$v."','".$k."');return false;\"> ";
							echo "<input type='image' src=\"/data/design/img/detail/icon_close.gif\" value='삭제' onclick=\"wishManage('cateDelete', '".$k."');return false;\" style=\"margin-left:5px;\"></p>";
							echo "</li>";
						}
						?>
					</ul>
					<p id="cateSetDiv2" style="display:none">
						<input type="text" name="cateTitle2" id="cateTitle2" style="width:100px" />
						<input type="button" value="수정" onclick="wishManage('cateModify','');"> 
						<input type="button" value="취소" onclick="javascript:wishCateViewOnOff2(cateSetDiv2,okDiv);"> 
					</p>
					<p style="border-top:1px solid #ededed;padding:20px 0px;margin-top:15px;"><a href="javascript:wishCateViewOnOff2(cateSetDiv,okDiv);"><span style="font-weight:bold;font-size:15px;color:#ea2f36;">+ 새폴더 추가</span></a></p>
					<p id="cateSetDiv" style="display:none">
						<input type="text" name="cateTitle" id="cateTitle" style="width:65%;border:1px solid #333333;height:35px;padding-left:10px;" placeholder="프로젝트 주제명 입력" />
						<input type="button" value="만들기" onclick="wishManage('cateInsert','');" class="btn_gray1"> 
						<input type="button" value="취소" onclick="javascript:wishCateViewOnOff2(cateSetDiv,okDiv);" class="btn_line"> 
					</p>
					<p id="okDiv" style="width:130px;margin:0px auto;">
						<input type="button" value="취소" class="btn_line btn_close closeBtn"> 
						<input type="button"  value="확인" onclick="javascript:CheckForm('wishlist','<?=$opti?>')" class="btn_line btn_login">
					</p>
					<p id="confirmResult" style="width:100%;margin:5px auto;text-align:center;color:#0000ff">
					</p>
				</fieldset>
			</form>
		</div>
	</div>
</div>	


<div id="basketlist" class="basketPopup" style="display:none;">

<script language="javascript" type="text/javascript">
function basketFolderModifyOpen(title,idx){
	var basketfdSetDiv2 = document.getElementById('basketfdSetDiv2');
	var okbasketDiv = document.getElementById('okbasketDiv');
	basketfdSetDiv2.style.display = ( basketfdSetDiv2.style.display == 'none' ) ? 'block' : 'none';
	okbasketDiv.style.display = ( okbasketDiv.style.display == 'none' ) ? 'block' : 'none';
	document.basketForm.folderName2.value = title;
	document.basketForm.delCateIdx.value = idx;			
}

function basketFolderViewOnOff2 ( t,t2 ) {
	t.style.display = ( t.style.display == 'none' ) ? 'block' : 'none';
	t2.style.display = ( t2.style.display == 'none' ) ? 'block' : 'none';
}

function basketManage ( mode, idx ) {
	if(mode=="cateDelete"){
		if( confirm('폴더를 삭제하시겠습니까?') ) {

			data = 'mode='+mode+'&delCateIdx='+idx;

			jQuery.ajax({
				url: "/front/basketPopup.php",
				type: "POST",
				data: data,
				success: function(res) {
					$j('#basketResult').html(res);
				},
				error: function(result) {
					console.log(result);
				},
				timeout: 30000
			});
		}
	}else if(mode=="cateModify"){

		data = 'mode='+mode+'&delCateIdx='+document.basketForm.delCateIdx.value+'&folderName='+$j("#folderName2").val();

		jQuery.ajax({
			url: "/front/basketPopup.php",
			type: "POST",
			data: data,
			success: function(res) {
				if(res=="err") alert("동일한 그룹명이 존재합니다.");
				else{
					basketFolderModifyOpen(basketfdSetDiv2,okbasketDiv);
					$j('#basketResult').html(res);
				}
			},
			error: function(result) {
				console.log(result);
			},
			timeout: 30000
		});


	}else if(mode=="cateInsert"){
		data = 'mode='+mode+'&folderName='+$j("#folderName").val();

		jQuery.ajax({
			url: "/front/basketPopup.php",
			type: "POST",
			data: data,
			success: function(res) {
				if(res=="err") alert("동일한 그룹명이 존재합니다.");
				else{
					$j('#basketResult').html(res);
					basketFolderViewOnOff2(basketfdSetDiv,okbasketDiv);
				}
			},
			error: function(result) {
				console.log(result);
			},
			timeout: 30000
		});

	}
	
}


</script>
<div class="searchPw popwin">
	<div class="spw_wrap">
		<p class="tit">우선담기</p>
		<p class="desc">프로젝트나 주제별로 편리하게 보관할 수 있습니다.</p>
		<div class="spwform">
			<form id="basketForm" name="basketForm" method="post">
			<input type="hidden" name="mode">
			<input type="hidden" name="delCateIdx" value=''>
				<fieldset>
					<legend>우선담기</legend>
					<ul id="basketResult">
						<li style="overflow:hidden;">
							<input type="radio" class="checkbox" name="selfd[]" value="0" checked>기본폴더
							<span style="float:right;"><img src="/data/design/img/detail/icon_lock1.gif"></span></li>
						<?
						$folders = array();
						if(false !== $fres = mysql_query("select * from basket_folder where id='".$_ShopInfo->getMemid()."' order by bfidx desc",get_db_conn())){		
							while($frow = mysql_fetch_assoc($fres)){			
								if(!_empty($frow['name'])) $folders[$frow['bfidx']] = $frow['name'];
							}
						}
						foreach ( $folders as $k=>$v ) {					
							echo "<li style=\"overflow:hidden;\"><input type=\"radio\" class=\"checkbox\" name=\"selfd[]\" value=\"".$k."\">".$v;
							echo "<p style=\"float:right;\"><input type='image' value='수정'src=\"/data/design/img/detail/icon_edit.gif\"  onclick=\"basketFolderModifyOpen('".$v."','".$k."');return false;\"> ";
							echo "<input type='image' src=\"/data/design/img/detail/icon_close.gif\" value='삭제' onclick=\"basketManage('cateDelete', '".$k."');return false;\" style=\"margin-left:5px;\"></p>";
							echo "</li>";
						}
						?>
					</ul>
					<p id="basketfdSetDiv2" style="display:none">
						<input type="text" name="folderName2" id="folderName2" style="width:100px" />
						<input type="button" value="수정" onclick="basketManage('cateModify','');"> 
						<input type="button" value="취소" onclick="javascript:basketFolderViewOnOff2(basketfdSetDiv2,okbasketDiv);"> 
					</p>
					<p style="border-top:1px solid #ededed;padding:20px 0px;margin-top:15px;"><a href="javascript:basketFolderViewOnOff2(basketfdSetDiv,okbasketDiv);"><span style="font-weight:bold;font-size:15px;color:#ea2f36;">+ 새폴더 추가</span></a></p>
					<p id="basketfdSetDiv" style="display:none">
						<input type="text" name="folderName" id="folderName" style="width:65%;border:1px solid #333333;height:35px;padding-left:10px;" placeholder="프로젝트 주제명 입력" />
						<input type="button" value="만들기" onclick="basketManage('cateInsert','');" class="btn_gray1"> 
						<input type="button" value="취소" onclick="javascript:basketFolderViewOnOff2(basketfdSetDiv,okbasketDiv);" class="btn_line"> 
					</p>
					<p id="okbasketDiv" style="width:130px;margin:0px auto;">
						<input type="button" value="취소" class="btn_line btn_close closeBtn"> 
						<input type="button"  value="확인" onclick="javascript:CheckForm('prebasket','')" class="btn_line btn_login">
					</p>
				</fieldset>
			</form>
		</div>
	</div>
</div>	










<div id="delilist1" class="deliPopup1" style="display:none;">
	<div class="searchPw popwin">
		<div class="spw_wrap">
			<p class="tit">구매조건에 따라 배송비 차등부과</p>
			<div class="spwform">
				<table border=0 cellpadding=4 cellspacing=1 width=100%>
				<?
				$array_deli=explode("|",$_vdinfo['deli_area']);
				for($i=0;$i<sizeof($array_deli);$i++){
					if($array_deli[$i*2]){
				?>
					<tr>
						<td style="padding:5px;background-color:#efefef;height:30px;width:150px"><?=$array_deli[$i*2]?></td>
						<td style="padding:5px;"><?=number_format($array_deli[$i*2+1])?>원</td>
					</tr>
				<?
					}
				}
				?>
				</table>
			</div>
			<p id="okbasketDiv" style="text-align:center;margin:0px auto;">
				<input type="button" value="확인" class="btn_line btn_close closeBtn"> 
			</p>
		</div>
	</div>	
</div>

<div id="delilist2" class="deliPopup2" style="display:none;">
	<div class="searchPw popwin">
		<div class="spw_wrap">
			<p class="tit">퀵서비스 이용방법</p>
			<div class="spwform">
				<?=$_vdinfo['deli_info2']?>
			</div>
			<p id="okbasketDiv" style="text-align:center;margin:0px auto;">
				<input type="button" value="확인" class="btn_line btn_close closeBtn"> 
			</p>
		</div>
	</div>	
</div>

<div id="delilist3" class="deliPopup3" style="display:none;">
	<div class="searchPw popwin">
		<div class="spw_wrap">
			<p class="tit">방문수령 이용방법</p>
			<div class="spwform">
				<?=$_vdinfo['deli_info3']?>
			</div>
			<p id="okbasketDiv" style="text-align:center;margin:0px auto;">
				<input type="button" value="확인" class="btn_line btn_close closeBtn"> 
			</p>
		</div>
	</div>	
</div>

<div id="delilist4" class="deliPopup4" style="display:none;">
	<div class="searchPw popwin">
		<div class="spw_wrap">
			<p class="tit">용달 이용방법</p>
			<div class="spwform">
				<?=$_vdinfo['deli_info4']?>
			</div>
			<p id="okbasketDiv" style="text-align:center;margin:0px auto;">
				<input type="button" value="확인" class="btn_line btn_close closeBtn"> 
			</p>
		</div>
	</div>	
</div>

<div id="delilist5" class="deliPopup5" style="display:none;">
	<div class="searchPw popwin">
		<div class="spw_wrap">
			<p class="tit">장소예약 이용방법</p>
			<div class="spwform">
				<?=$_vdinfo['deli_info5']?>
			</div>
			<p id="okbasketDiv" style="text-align:center;margin:0px auto;">
				<input type="button" value="확인" class="btn_line btn_close closeBtn"> 
			</p>
		</div>
	</div>	
</div>


</BODY>
</HTML>