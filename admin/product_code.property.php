<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/admin_more.php");
INCLUDE ("access.php");

####################### 페이지 접근권한 check ###############
$PageCode = "pr-1";
$MenuCode = "nomenu";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$shop_more_info = getShopMoreInfo();

$maxfilesize="512000";
$imagepath=$Dir.DataDir."shopimages/product/";

$mode=$_POST["mode"];
$mode_result=$_POST["mode_result"];

$code=$_POST["code"];
$parentcode=$_POST["parentcode"];

$up_code_name=$_POST["up_code_name"];
$up_type1=$_POST["up_type1"];
$up_type2=$_POST["up_type2"];
$up_group_code=$_POST["up_group_code"];
$up_sort=$_POST["up_sort"];

//디자인 타입 묻지 않고 기본값으로 들어갈수 있도록
$up_list_type = ($_POST["up_list_type"] == "")? 'AL001':$_POST["up_list_type"];
$up_detail_type = ($_POST["up_detail_type"] == "")? 'AD001':$_POST["up_detail_type"];
/*$up_list_type= isset($_POST["up_list_type"])? $_POST["up_list_type"]:'AL001';
$up_detail_type=isset($_POST["up_detail_type"])? $_POST["up_detail_type"]:'AD001';*/
$up_special=$_POST["up_special"];
$up_islist=$_POST["up_islist"];
$up_code_hide=$_POST["up_code_hide"];

$up_special_1_cols=(int)$_POST["up_special_1_cols"];
$up_special_1_rows=(int)$_POST["up_special_1_rows"];
$up_special_2_cols=(int)$_POST["up_special_2_cols"];
$up_special_2_rows=(int)$_POST["up_special_2_rows"];
$up_special_3_cols=(int)$_POST["up_special_3_cols"];
$up_special_3_rows=(int)$_POST["up_special_3_rows"];

$up_special_1_type=$_POST["up_special_1_type"];
$up_special_2_type=$_POST["up_special_2_type"];
$up_special_3_type=$_POST["up_special_3_type"];

$is_gcode=$_POST["is_gcode"];
$is_sort=$_POST["is_sort"];
$is_design=$_POST["is_design"];
$is_special=$_POST["is_special"];
$up_type=$_POST["up_type"];
//소셜쇼핑 스킨고정
if($up_type1 =="S" || $up_type =="S"){
	$up_list_type	= "SL001";
	$up_detail_type	= "SD001";
}


$dsameparent=$_POST["dsameparent"];


// 렌탈 관련 추가 변수
$_POST["useseason"] =($_POST["useseason"] == '1')?'1':'0';
if(!in_array($_POST['pricetype'],array('period','time','day','checkout'))) $_POST['pricetype'] = 'time';
//if($_POST['commission']) $_POST['pricetype'] = 'time';

//접근 가능회원 복수로 선택되게 수정
$arr_group_code=$_POST["arr_group_code"];
if($up_group_code=="ALL"){
	$up_group_code = "";
	for($i=0;$i<sizeof($arr_group_code);$i++){
		if($i!=sizeof($arr_group_code)-1){
			$up_group_code .= $arr_group_code[$i].",";
		}else{
			$up_group_code .= $arr_group_code[$i];
		}
	}
}else{
	$up_group_code = $up_group_code;
}

//카테고리별 사은품적용불가, 쿠폰적용불가, 교환 및 환불불가 체크  2012-04-13 추가 적립금 사용불가
/*
$up_isgift = (!empty($_POST["up_isgift"])) ? $_POST["up_isgift"] : "N";
$up_iscoupon = (!empty($_POST["up_iscoupon"])) ? $_POST["up_iscoupon"] : "N";
$up_isrefund = (!empty($_POST["up_isrefund"])) ? $_POST["up_isrefund"] : "N";
$up_isreserve = (!empty($_POST["up_isreserve"])) ? $_POST["up_isreserve"] : "N";
*/
$up_isCheck = array('Y','N');
$up_isgift = (in_array($_POST["up_isgift"],$up_isCheck))?$_POST["up_isgift"]:"";
$up_iscoupon = (in_array($_POST["up_iscoupon"],$up_isCheck))?$_POST["up_iscoupon"]:"";
$up_isrefund = (in_array($_POST["up_isrefund"],$up_isCheck))?$_POST["up_isrefund"]:"";
$up_isreserve = (in_array($_POST["up_isreserve"],$up_isCheck))?$_POST["up_isreserve"]:"";




if(_isInt($_POST['reseller_reserve']) && $_POST['reseller_reserve'] < 100 && $_POST['reseller_reserve'] > 0){
	$reseller_reserve = $_POST['reseller_reserve']/100;
}else{
	$reseller_reserve = 0;
}

if ($mode=="insert" && strlen($up_code_name)>0) {
	if(strlen($parentcode)==12) {	//하위카테고리 추가
		$in_codeA=substr($parentcode,0,3);
		$in_codeB=substr($parentcode,3,3);
		$in_codeC=substr($parentcode,6,3);
		$in_codeD=substr($parentcode,9,3);

		$sql = "SELECT * FROM tblproductcode WHERE codeA='".$in_codeA."' AND codeB='".$in_codeB."' ";
		$sql.= "AND codeC='".$in_codeC."' AND codeD='".$in_codeD."' ";
		$result=mysql_query($sql,get_db_conn());
		$pobj = $row=mysql_fetch_object($result);
		mysql_free_result($result);
		if($row) {
			if(ereg("X",$row->type)) {
				echo "<script>parent.HiddenFrame.alert('상위카테고리 선택이 잘못되었습니다.');location.replace('".$_SERVER[PHP_SELF]."');</script>";
				exit;
			}
		} else {
			echo "<script>parent.HiddenFrame.alert('상위카테고리 선택이 잘못되었습니다.');location.replace('".$_SERVER[PHP_SELF]."');</script>";
			exit;
		}
		$type=$row->type;
		if(!ereg("M",$type)) $type.="M";

		$sql = "SELECT MAX(codeB) as maxcodeB, MAX(codeC) as maxcodeC, MAX(codeD) as maxcodeD ";
		$sql.= "FROM tblproductcode WHERE codeA='".$in_codeA."' ";
		if($in_codeB!="000") {
			$sql.= "AND codeB='".$in_codeB."' ";
		}
		if($in_codeC!="000") {
			$sql.= "AND codeC='".$in_codeC."' ";
		}
		$result = mysql_query($sql,get_db_conn());
		$row = mysql_fetch_object($result);
		mysql_free_result($result);

		if($in_codeB=="000" && $in_codeC=="000" && $in_codeD=="000") {
			$in_codeB=(int)$row->maxcodeB+1;
			$in_codeB="000".$in_codeB;
			$in_codeB=substr($in_codeB,-3);
		} else if($in_codeC=="000" && $in_codeD=="000") {
			$in_codeC=(int)$row->maxcodeC+1;
			$in_codeC="000".$in_codeC;
			$in_codeC=substr($in_codeC,-3);
		} else if($in_codeD=="000") {
			$in_codeD=(int)$row->maxcodeD+1;
			$in_codeD="000".$in_codeD;
			$in_codeD=substr($in_codeD,-3);
		}
		if (strlen($up_type2)==0 || $up_type2=="1" || $in_codeD!="000") {
			$type.="X";
		}

	} else {	//최상위 카테고리 신규추가
		$sql = "SELECT MAX(codeA) as maxcode FROM tblproductcode WHERE type IN ('L','T','LX','TX','S','SX') ";
		$result = mysql_query($sql,get_db_conn());
		$row = mysql_fetch_object($result);
		mysql_free_result($result);
		$maxcode=(int)$row->maxcode+1;
		$maxcode="000".$maxcode;
		$maxcode=substr($maxcode,-3);
		$type=$up_type1;
		if ($up_type2=="1") {	//중카테고리 없음
			$type.="X";
		}
		$in_codeA=$maxcode;
		$in_codeB="000";
		$in_codeC="000";
		$in_codeD="000";
	}
	if ($up_code_hide=="NO") {
		$up_group_code = "NO";
	}
	if(strlen($up_islist)==0) $up_islist="N";
	$in_special="";
	if(strlen($old_special)>0 && strlen($up_special)>0) {
		$arr_sp=explode(",",$old_special);
		for($i=0;$i<count($arr_sp);$i++) {
			if(eregi($arr_sp[$i],$up_special)) {
				$in_special.=$arr_sp[$i].",";
			}
		}
		$in_special=substr($in_special,0,-1);
	} else $in_special=$up_special;

	$in_special_cnt="";
	if(eregi("1",$in_special)) {
		if($up_special_1_cols<=0) $up_special_1_cols=5;
		if($up_special_1_rows<=0) $up_special_1_rows=1;
		if(strlen($up_special_1_type)==0) $up_special_1_type="I";
		$in_special_cnt.="1:".$up_special_1_cols."X".$up_special_1_rows."X".$up_special_1_type.",";
	}
	if(eregi("2",$in_special)) {
		if($up_special_2_cols<=0) $up_special_2_cols=5;
		if($up_special_2_rows<=0) $up_special_2_rows=1;
		if(strlen($up_special_2_type)==0) $up_special_2_type="I";
		$in_special_cnt.="2:".$up_special_2_cols."X".$up_special_2_rows."X".$up_special_2_type.",";
	}
	if(eregi("3",$in_special)) {
		if($up_special_3_cols<=0) $up_special_3_cols=5;
		if($up_special_3_rows<=0) $up_special_3_rows=1;
		if(strlen($up_special_3_type)==0) $up_special_3_type="I";
		$in_special_cnt.="3:".$up_special_3_cols."X".$up_special_3_rows."X".$up_special_3_type.",";
	}
	if(strlen($in_special_cnt)>0) $in_special_cnt=substr($in_special_cnt,0,-1);
	$booking_confirm=$_POST["booking_confirm"]=="now"?$_POST["booking_confirm"]:$_POST["booking_confirm_time"];


	$sql = "INSERT tblproductcode SET ";
	$sql.= "codeA		= '".$in_codeA."', ";
	$sql.= "codeB		= '".$in_codeB."', ";
	$sql.= "codeC		= '".$in_codeC."', ";
	$sql.= "codeD		= '".$in_codeD."', ";
	$sql.= "type		= '".$type."', ";
	$sql.= "code_name	= '".$up_code_name."', ";

	if($dsameparent == '1'){
		$up_list_type = $pobj->list_type;
		$up_detail_type= $pobj->detail_type;
	}else{
		$dsameparent = "";
	}
	$sql.= "list_type	= '".$up_list_type."', ";
	$sql.= "detail_type	= '".$up_detail_type."', ";
	$sql.= "dsameparent	= '".$dsameparent."', ";


	$sql.= "sort		= '".$up_sort."', ";
	$sql.= "group_code	= '".$up_group_code."', ";
	$sql.= "special		= '".$in_special."', ";
	$sql.= "special_cnt	= '".$in_special_cnt."', ";
	$sql.= "islist		= '".$up_islist."', ";
	$sql.= "isgift		= '".$up_isgift."', ";
	$sql.= "iscoupon	= '".$up_iscoupon."', ";
	$sql.= "isrefund	= '".$up_isrefund."', ";
	
	$sql.= "reseller_reserve	= '".$reseller_reserve."', ";
	
	$sql.= "booking_confirm	= '".$booking_confirm."', ";	
	$sql.= "cancel_cont		= '".$cancel_cont."', ";
	$sql.= "discount_card	= '".$discount_card."', ";

	// 배송수단 선택
	$deli_type = $_POST['deli_type'];
	if (is_array($deli_type)) {
		$deli_type = implode(',', $deli_type);
	}

	$sql.= "deli_type		= '".$deli_type."', ";
	
	$sql.= "syncNaverEp	= '".(($_POST['syncNaverEp'] == '0')?'0':'1')."', ";	

	$sql.= "isreserve	= '".$up_isreserve."' ";
	$insert = mysql_query($sql,get_db_conn());

	// 렌탈 관련 추가 필드 등록
	if($insert){
		if(_array($_REQUEST['discount'])){################################그룹별 적립률######################################				
			foreach($_REQUEST['discount'] as $gdiscount_code=>$discountval){				
				if(_empty($discountval)) $discountval = 0;
				if($discountval > 0){
					if($_REQUEST['discount_type'][$gdiscount_code] != '100'){
						$discountval = intval($discountval);
					}else if($_REQUEST['discount_type'][$gdiscount_code] == '100' && intval($discountval) < 100){
						$discountval = floatval($discountval/100);
					}
				}			
				$sql = "insert into tblmemberreserve (group_code,productcode,discountYN,reserve,over_reserve) values ('".$gdiscount_code."','".$in_codeA.$in_codeB.$in_codeC.$in_codeD."','Y','".$discountval."','N') ON DUPLICATE KEY UPDATE discountYN = values(discountYN),reserve = values(reserve),over_reserve = values(over_reserve)";
				mysql_query($sql,get_db_conn());				
			}
		}

		if(_array($_REQUEST['discount2'])){##############################그룹별 추천인적립률####################################				
			foreach($_REQUEST['discount2'] as $gdiscount_code=>$discountval){				
				if(_empty($discountval)) $discountval = 0;
				if($discountval > 0){
					if($_REQUEST['discount_type2'][$gdiscount_code] != '100'){
						$discountval = intval($discountval);
					}else if($_REQUEST['discount_type'][$gdiscount_code] == '100' && intval($discountval) < 100){
						$discountval = floatval($discountval/100);
					}
				}			
				$sql = "insert into tblreseller_reserve (group_code,productcode,discountYN,reserve,over_reserve) values ('".$gdiscount_code."','".$in_codeA.$in_codeB.$in_codeC.$in_codeD."','Y','".$discountval."','N') ON DUPLICATE KEY UPDATE discountYN = values(discountYN),reserve = values(reserve),over_reserve = values(over_reserve)";
				mysql_query($sql,get_db_conn());				
			}
		}
		


		$sql = "insert into code_rent set code='".$in_codeA.$in_codeB.$in_codeC.$in_codeD."',pricetype='".$_POST['pricetype']."',rent_stime = '".$_POST['rent_stime']."',rent_etime = '".$_POST['rent_etime']."',base_period='".$_POST['base_period']."',ownership='".$_POST['ownership']."',useseason='".$_POST['useseason']."',commission_self='".$_POST['commission_self']."',commission_main='".$_POST['commission_main']."',base_time = '".$_POST['base_time']."', base_price = '".$_POST['base_price']."', timeover_price = '".$_POST['timeover_price']."', halfday = '".$_POST['halfday']."', halfday_percent = '".$_POST['halfday_percent']."', oneday_ex = '".$_POST['oneday_ex']."', time_percent = '".$_POST['time_percent']."', checkin_time = '".$_POST['checkin_time']."', checkout_time = '".$_POST['checkout_time']."' ";

		mysql_query($sql,get_db_conn());
		
		if(_array($_POST['longrent_sday']) && _array($_POST['longrent_percent'])){
			for($i=0;$i<count($_POST['longrent_sday']);$i++){
				if(_isInt($_POST['longrent_sday'][$i]) && _isInt($_POST['longrent_percent'][$i])){
					$sql = "insert into rent_longrent set code='".$in_codeA.$in_codeB.$in_codeC.$in_codeD."',sday='".$_POST['longrent_sday'][$i]."',eday='".$_POST['longrent_eday'][$i]."',percent='".$_POST['longrent_percent'][$i]."'";
					mysql_query($sql,get_db_conn());
				}
			}
		}

		if(_array($_POST['refundday']) && _array($_POST['refundpercent'])){
			for($i=0;$i<count($_POST['refundday']);$i++){
				if($_POST['refundpercent'][$i]>=0){
					$sql = "insert into rent_refund set code='".$in_codeA.$in_codeB.$in_codeC.$in_codeD."',day='".$_POST['refundday'][$i]."',percent='".$_POST['refundpercent'][$i]."'";
					mysql_query($sql,get_db_conn());
				}
			}
		}
		
		if(_array($_POST['discrangeday']) && _array($_POST['discrangepercent'])){
			for($i=0;$i<count($_POST['discrangeday']);$i++){
				if(_isInt($_POST['discrangeday'][$i]) && _isInt($_POST['discrangepercent'][$i])){
					$sql = "insert into rent_longdiscount  set code='".$in_codeA.$in_codeB.$in_codeC.$in_codeD."',day='".$_POST['discrangeday'][$i]."',percent='".$_POST['discrangepercent'][$i]."'";
					mysql_query($sql,get_db_conn());
				}
			}
		}
	}


	//////////////////////////////////////////////////
	//배너 관련 추가
	if ($in_codeA!="000" && $in_codeB=="000" && $in_codeC=="000" && $in_codeD=="000") {
		$sql = "SELECT * FROM product_code_banner WHERE code='".$in_codeA."'";

		$result = mysql_query($sql,get_db_conn());
		$b_row = mysql_fetch_object($result);

		mysql_free_result($result);
	}

	$up_banner_file = $_FILES["up_banner_file"];
	$file_size = $up_banner_file[size];

	$up_banner_url = $_POST["up_banner_url"];
	$up_move_type = $_POST["up_move_type"];

	if($file_size > $maxfilesize) {
		echo "<script>alert(\"상품이미지의 총 용량이 ".ceil($file_size/1024)."Kbyte로 500K가 넘습니다.\\n\\n한번에 올릴 수 있는 최대 용량은 500K입니다.\\n\\n"."이미지가 gif가 아니면 이미지 포맷을 바꾸어 올리시면 용량이 줄어듭니다.\");history.go(-1);</script>\n";
		exit;
	}

	$filename = $up_banner_file[name];
	$file = $up_banner_file[tmp_name];

	if (strlen($filename)>0 && file_exists($file)) {
		$image_name = $in_codeA;

		$ext = strtolower(substr($filename,strlen($filename)-3,3));
		if ($ext=="gif" || $ext=="jpg") {
			$image = $image_name.".".$ext;
			move_uploaded_file($file,$imagepath.$image);
			chmod($imagepath.$image,0664);
		} else {
			$image="";
		}
	} else {
		$image = $b_row->banner_file;
	}

	$sql2 = "insert product_code_banner set ";
	$sql2 .= "code = '".$in_codeA."', ";
	$sql2 .= "banner_file = '".$image."', ";
	$sql2 .= "banner_url = '".$up_banner_url."', ";
	$sql2 .= "move_type = '".$up_move_type."' ";
	mysql_query($sql2,get_db_conn());
	//배너 관련 추가

	//검색키워드등록 start
	$kg_idx = $_POST["kg_idx"];
	$code = $in_codeA.$in_codeB.$in_codeC.$in_codeD;
	
	for($i=0;$i<sizeof($kg_idx);$i++){
		
		$useyn = $_POST[$kg_idx[$i]."_useyn"];
		$keyword = $_POST[$kg_idx[$i]."_kw"];

		for($j=0;$j<sizeof($keyword);$j++){
			$ksql = "INSERT tblkeyword SET ";
			$ksql.= "kg_idx  	= '".$kg_idx[$i]."', ";
			$ksql.= "code	= '".$code."', ";
			$ksql.= "keyword	= '".$keyword[$j]."', ";
			$ksql.= "use_yn	= '".$useyn."' ";
			mysql_query($ksql,get_db_conn());
		}
	}
	//검색키워드등록 end

	if ($insert) {
		$log_content = "## 카테고리입력 ## - 코드 ".$in_codeA.$in_codeB.$in_codeC.$in_codeD." - 코드명 : ".$up_code_name."";
		ShopManagerLog($_ShopInfo->getId(),$connect_ip,$log_content);

		$onload="<script>parent.NewCodeResult('".$in_codeA.$in_codeB.$in_codeC.$in_codeD."','".$type."','".$up_code_name."','".$up_list_type."','".$up_detail_type."','".$up_sort."','".$up_group_code."');parent.HiddenFrame.alert('상품카테고리 등록이 완료되었습니다.');</script>";
	} else {
		$onload="<script>parent.HiddenFrame.alert('상품카테고리 등록중 오류가 발생하였습니다.');</script>";
	}
} else if($mode=="modify" && strlen($code)==12) {
	$codeA=substr($code,0,3);
	$codeB=substr($code,3,3);
	$codeC=substr($code,6,3);
	$codeD=substr($code,9,3);

	$sql = "SELECT * FROM tblproductcode c left join code_rent r on (concat(c.codeA,c.codeB,c.codeC,c.codeD) = r.code) WHERE c.codeA='".$codeA."' AND c.codeB='".$codeB."' AND c.codeC='".$codeC."' AND c.codeD='".$codeD."' ";
	$result = mysql_query($sql,get_db_conn());
	$row = mysql_fetch_object($result);

	mysql_free_result($result);
	if(!$row) {
		echo "<script>parent.HiddenFrame.alert('해당 상품카테고리 정보가 존재하지 않습니다.');parent.location.reload();</script>";
		exit;
	}
	
	$codeinfo = $row; // 후단에서 정보 사용을 위해서
	$codeinfo->code = $codeinfo->codeA.$codeinfo->codeB.$codeinfo->codeC.$codeinfo->codeD;
	
	$type=$row->type;
	$osyncNaverEp = $row->syncNaverEp;

	// 부모 카테고리 호출 관련
	$parentInfo = array();
	if($codeB != '000'){
		$pwhere = array();
		for($i=0;$i<3;$i++){
			$key = 	'code'.chr(65+$i);
			$key2 = 'code'.chr(65+$i+1);
			if(${$key2} == '000') array_push($pwhere,$key."='000'");
			else array_push($pwhere,$key."='".${$key}."'");
		}
		array_push($pwhere,$key2."='000'");
		$psql = "select * from tblproductcode where ".implode(' and ',$pwhere)." limit 1";
		if(false !== $pres = mysql_query($psql,get_db_conn())){
			$parentInfo = mysql_fetch_assoc($pres);
		}
	}

	//배너 관련 추가 jdy
	if ($codeA!="000" && $codeB=="000" && $codeC=="000" && $codeD=="000") {
		$sql = "SELECT * FROM product_code_banner WHERE code='".$codeA."'";

		$result = mysql_query($sql,get_db_conn());
		$b_row = mysql_fetch_object($result);

		mysql_free_result($result);
	}
	//배너 관련 추가 jdy


	if ($mode_result=="result" && $up_code_name) {	//수정내역 업데이트


		//배너 관련 추가 jdy
		$up_banner_file = $_FILES["up_banner_file"];
		$file_size = $up_banner_file[size];

		$up_banner_url = $_POST["up_banner_url"];
		$up_move_type = $_POST["up_move_type"];

		if($file_size > $maxfilesize) {
			echo "<script>alert(\"상품이미지의 총 용량이 ".ceil($file_size/1024)."Kbyte로 500K가 넘습니다.\\n\\n한번에 올릴 수 있는 최대 용량은 500K입니다.\\n\\n"."이미지가 gif가 아니면 이미지 포맷을 바꾸어 올리시면 용량이 줄어듭니다.\");history.go(-1);</script>\n";
			exit;
		}

		$filename = $up_banner_file[name];
		$file = $up_banner_file[tmp_name];

		if (strlen($filename)>0 && file_exists($file)) {
			$image_name = $codeA;

			$ext = strtolower(substr($filename,strlen($filename)-3,3));
			if ($ext=="gif" || $ext=="jpg") {
				$image = $image_name.".".$ext;
				move_uploaded_file($file,$imagepath.$image);
				chmod($imagepath.$image,0664);
			} else {
				$image="";
			}
		} else {
			$image = $b_row->banner_file;
		}
		//배너 관련 추가 jdy


		if ($up_code_hide=="NO") {
			$up_group_code = "NO";
		}
		if(strlen($up_islist)==0) $up_islist="N";
		$in_special="";
		if(strlen($old_special)>0 && strlen($up_special)>0) {
			$arr_sp=explode(",",$old_special);
			for($i=0;$i<count($arr_sp);$i++) {
				if(eregi($arr_sp[$i],$up_special)) {
					$in_special.=$arr_sp[$i].",";
				}
			}
			$in_special=substr($in_special,0,-1);
		} else $in_special=$up_special;

		$in_special_cnt="";
		if(eregi("1",$in_special)) {
			if($up_special_1_cols<=0) $up_special_1_cols=5;
			if($up_special_1_rows<=0) $up_special_1_rows=1;
			if(strlen($up_special_1_type)==0) $up_special_1_type="I";
			$in_special_cnt.="1:".$up_special_1_cols."X".$up_special_1_rows."X".$up_special_1_type.",";
		}
		if(eregi("2",$in_special)) {
			if($up_special_2_cols<=0) $up_special_2_cols=5;
			if($up_special_2_rows<=0) $up_special_2_rows=1;
			if(strlen($up_special_2_type)==0) $up_special_2_type="I";
			$in_special_cnt.="2:".$up_special_2_cols."X".$up_special_2_rows."X".$up_special_2_type.",";
		}
		if(eregi("3",$in_special)) {
			if($up_special_3_cols<=0) $up_special_3_cols=5;
			if($up_special_3_rows<=0) $up_special_3_rows=1;
			if(strlen($up_special_3_type)==0) $up_special_3_type="I";
			$in_special_cnt.="3:".$up_special_3_cols."X".$up_special_3_rows."X".$up_special_3_type.",";
		}
		if(strlen($in_special_cnt)>0) $in_special_cnt=substr($in_special_cnt,0,-1);

		$up_code_name = ereg_replace(";","",$up_code_name);

		$booking_confirm=$_POST["booking_confirm"]=="now"?$_POST["booking_confirm"]:$_POST["booking_confirm_time"];

		$sql = "UPDATE tblproductcode SET ";
		$sql.= "code_name		= '".$up_code_name."', ";

		if($dsameparent == '1'){
			$up_list_type 	= $parentInfo['list_type'];
			$up_detail_type = $parentInfo['detail_type'];
		}else{
			$dsameparent = "";
		}

		$sql.= "list_type		= '".$up_list_type."', ";
		$sql.= "detail_type		= '".$up_detail_type."', ";
		$sql.= "dsameparent		= '".$dsameparent."', ";


		$sql.= "group_code		= '".$up_group_code."', ";
		$sql.= "sort			= '".$up_sort."', ";
		$sql.= "special			= '".$in_special."', ";
		$sql.= "special_cnt		= '".$in_special_cnt."', ";
		$sql.= "islist			= '".$up_islist."', ";
		$sql.= "isgift			= '".$up_isgift."', ";
		$sql.= "iscoupon		= '".$up_iscoupon."', ";
		$sql.= "isrefund		= '".$up_isrefund."', ";
		
		$sql.= "reseller_reserve	= '".$reseller_reserve."', ";	
		
		$sql.= "booking_confirm	= '".$booking_confirm."', ";	
		$sql.= "cancel_cont		= '".$cancel_cont."', ";
		$sql.= "discount_card	= '".$discount_card."', ";

		// 배송수단 선택
		$deli_type = $_POST['deli_type'];
		if (is_array($deli_type)) {
			$deli_type = implode(',', $deli_type);
		}

		$sql.= "deli_type		= '".$deli_type."', ";
		


		$sql.= "syncNaverEp	= '".(($_POST['syncNaverEp'] == '0')?'0':'1')."', ";
	//	$sql.= "haveseason	= '".$haveseason."', ";

		$sql.= "isreserve	= '".$up_isreserve."' ";
		$sql.= "WHERE codeA = '".$codeA."' AND codeB = '".$codeB."' ";
		$sql.= "AND codeC = '".$codeC."' AND codeD = '".$codeD."' ";
		$update = mysql_query($sql,get_db_conn());

		if($update){

			//검색키워드등록 start
			$kg_idx = $_POST["kg_idx"];
			
			$del_ksql = "DELETE FROM tblkeyword WHERE code='".$code."' AND productcode=''";
			mysql_query($del_ksql,get_db_conn());

			for($i=0;$i<sizeof($kg_idx);$i++){
				
				$useyn = $_POST[$kg_idx[$i]."_useyn"];
				$keyword = $_POST[$kg_idx[$i]."_kw"];

				for($j=0;$j<sizeof($keyword);$j++){
					$ksql = "INSERT tblkeyword SET ";
					$ksql.= "kg_idx  	= '".$kg_idx[$i]."', ";
					$ksql.= "code	= '".$code."', ";
					$ksql.= "keyword	= '".$keyword[$j]."', ";
					$ksql.= "use_yn	= '".$useyn."' ";
					mysql_query($ksql,get_db_conn());
				}
			}
			//검색키워드등록 end

			// 하위 적용
			$codewhere = '';
			for($si=0;$si<4;$si++){
				$key = 'code'.chr(65+$si);
				if(${$key} == '000') break;
				else $codewhere.=${$key};
			}
			if(false !== $tmpkey= array_search('reseller_reserve',$_POST['setsubsame'])){				
				unset($_POST['setsubsame'][$tmpkey]);				
				$sql = "update tblproductcode set reseller_reserve='".$reseller_reserve."' where concat(codeA,codeB,codeC,codeD) like '".$codewhere."%' and concat(codeA,codeB,codeC,codeD) not like '".$codewhere."000%'";
				@mysql_query($sql,get_db_conn());
			}
			
			$subcategorys = array();
			if(false !== $tmpkey= array_search('gdiscount',$_POST['setsubsame'])){
				unset($_POST['setsubsame'][$tmpkey]);
				$subwhere = array();
				for($si=0;$si<4;$si++){
					$key = 'code'.chr(65+$si);
//						if(${$key} == '000') break;
//						else $codewhere.=${$key};
					if(${$key} != '000') array_push($subwhere,$key."='".${$key}."'");
					else{
						array_push($subwhere,$key."!='000'");
						break;
					}
				}										
		
				if(_array($subwhere)){						
					$subsql = "select concat(codeA,codeB,codeC,codeD) as code from tblproductcode where ".implode(' and ',$subwhere);			
					if(false !== $subres = mysql_query($subsql,get_db_conn())){
						if(mysql_num_rows($subres)){
							while($subrow = mysql_fetch_assoc($subres)) array_push($subcategorys,$subrow['code']);
						}
					}
				}
			}
			if(_array($_REQUEST['discount'])){##############################그룹별 적립률####################################				
				foreach($_REQUEST['discount'] as $gdiscount_code=>$discountval){				
					if(_empty($discountval)) $discountval = 0;
					if($discountval > 0){
						if($_REQUEST['discount_type'][$gdiscount_code] != '100'){
							$discountval = intval($discountval);
						}else if($_REQUEST['discount_type'][$gdiscount_code] == '100' && intval($discountval) < 100){
							$discountval = floatval($discountval/100);
						}
					}
					
					$chksql = "select count(*) from tblmemberreserve where group_code='".$gdiscount_code."' and productcode='".$codeA.$codeB.$codeC.$codeD."'";
					$chkres = mysql_query($chksql,get_db_conn());
					if(mysql_result($chkres,0,0) > 0){
						$sql = "update tblmemberreserve set reserve = '".$discountval."' where group_code='".$gdiscount_code."' and productcode='".$codeA.$codeB.$codeC.$codeD."'";	
					}else{
						$sql = "insert into tblmemberreserve set group_code,productcode,discountYN,reserve,over_reserve) values ('".$gdiscount_code."','".$codeA.$codeB.$codeC.$codeD."','Y','".$discountval."','N') ON DUPLICATE KEY UPDATE group_code = values(group_code),productcode = values(productcode),discountYN = values(discountYN),reserve = values(".$discountval."),over_reserve = values(over_reserve)";	
					}
					mysql_query($sql,get_db_conn());
					
					if(_array($subcategorys)){ // 하위 카테고리 처리 일 경우
						foreach($subcategorys as $subcategory){
							$sql = "delete from tblmemberreserve where group_code='".$gdiscount_code."' and productcode='".$subcategory."'";
							mysql_query($sql,get_db_conn());

							$sql = "insert into tblmemberreserve (group_code,productcode,discountYN,reserve,over_reserve) values ('".$gdiscount_code."','".$subcategory."','Y','".$discountval."','N')";						
							mysql_query($sql,get_db_conn());
						}
					}
				}
			}

			if(_array($_REQUEST['discount2'])){##############################그룹별 추천인적립률################################				
				foreach($_REQUEST['discount2'] as $gdiscount_code=>$discountval){				
					if(_empty($discountval)) $discountval = 0;
					if($discountval > 0){
						if($_REQUEST['discount_type2'][$gdiscount_code] != '100'){
							$discountval = intval($discountval);
						}else if($_REQUEST['discount_type2'][$gdiscount_code] == '100' && intval($discountval) < 100){
							$discountval = floatval($discountval/100);
						}
					}
					
					$chksql = "select count(*) from tblreseller_reserve where group_code='".$gdiscount_code."' and productcode='".$codeA.$codeB.$codeC.$codeD."'";
					$chkres = mysql_query($chksql,get_db_conn());
					if(mysql_result($chkres,0,0) > 0){
						$sql = "update tblreseller_reserve set reserve='".$discountval."' where group_code='".$gdiscount_code."' and productcode='".$codeA.$codeB.$codeC.$codeD."'";
					}else{
						$sql = "insert into tblreseller_reserve (group_code,productcode,discountYN,reserve,over_reserve) values ('".$gdiscount_code."','".$codeA.$codeB.$codeC.$codeD."','Y','".$discountval."','N')";
					}
					mysql_query($sql,get_db_conn());
					
					if(_array($subcategorys)){ // 하위 카테고리 처리 일 경우
						foreach($subcategorys as $subcategory){
							$sql = "delete from tblreseller_reserve where group_code='".$gdiscount_code."' and productcode='".$subcategory."'";
							mysql_query($sql,get_db_conn());

							$sql = "insert into tblreseller_reserve (group_code,productcode,discountYN,reserve,over_reserve) values ('".$gdiscount_code."','".$subcategory."','Y','".$discountval."','N')";						
							mysql_query($sql,get_db_conn());
						}
					}
				}
			}

			// 렌탈 관련 추가 처리
			$codewhere = '';
			for($si=0;$si<4;$si++){
				$key = 'code'.chr(65+$si);
				if(${$key} == '000') break;
				else $codewhere.=${$key};
			}
			$chksql = "select count(*) from code_rent where code='".$codeA.$codeB.$codeC.$codeD."'";
			if(false !== $chkres = mysql_query($chksql,get_db_conn())){
				if(mysql_result($chkres,0,0) > 0){
					$sql = "update code_rent set pricetype='".$_POST['pricetype']."',rent_stime = '".$_POST['rent_stime']."', rent_etime = '".$_POST['rent_etime']."',base_period='".$_POST['base_period']."',ownership='".$_POST['ownership']."',useseason='".$_POST['useseason']."',commission_self='".$_POST['commission_self']."',commission_main='".$_POST['commission_main']."',base_time = '".$_POST['base_time']."', base_price = '".$_POST['base_price']."', timeover_price = '".$_POST['timeover_price']."', halfday = '".$_POST['halfday']."', halfday_percent = '".$_POST['halfday_percent']."', oneday_ex = '".$_POST['oneday_ex']."', time_percent = '".$_POST['time_percent']."', checkin_time = '".$_POST['checkin_time']."', checkout_time = '".$_POST['checkout_time']."' where code='".$codeA.$codeB.$codeC.$codeD."'";		
				}else{
					$sql = "insert into code_rent set code='".$codeA.$codeB.$codeC.$codeD."',pricetype='".$_POST['pricetype']."',rent_stime = '".$_POST['rent_stime']."',rent_etime = '".$_POST['rent_etime']."',base_period='".$_POST['base_period']."',ownership='".$_POST['ownership']."',useseason='".$_POST['useseason']."',commission_self='".$_POST['commission_self']."',commission_main='".$_POST['commission_main']."',base_time = '".$_POST['base_time']."', base_price = '".$_POST['base_price']."', timeover_price = '".$_POST['timeover_price']."', halfday = '".$_POST['halfday']."', halfday_percent = '".$_POST['halfday_percent']."', oneday_ex = '".$_POST['oneday_ex']."', time_percent = '".$_POST['time_percent']."', checkin_time = '".$_POST['checkin_time']."', checkout_time = '".$_POST['checkout_time']."' ";		
				}
				mysql_query($sql,get_db_conn());
				
				$upsubsame = array();
				if(false !== $tmpkey= array_search('commission',$_POST['setsubsame'])){
					array_push($upsubsame,"commission_self='".$_POST['commission_self']."'");
					array_push($upsubsame,"commission_main='".$_POST['commission_main']."'");
					unset($_POST['setsubsame'][$tmpkey]);
				}
				if(false !== $tmpkey= array_search('useseason',$_POST['setsubsame'])){
					array_push($upsubsame,"useseason='".$_POST['useseason']."'");
					unset($_POST['setsubsame'][$tmpkey]);
				}
				if(false !== $tmpkey= array_search('pricetype',$_POST['setsubsame'])){
					array_push($upsubsame,"pricetype='".$_POST['pricetype']."'");
					array_push($upsubsame,"base_period='".$_POST['base_period']."'");
					array_push($upsubsame,"ownership='".$_POST['ownership']."'");
					array_push($upsubsame,"base_time='".$_POST['base_time']."'");
					array_push($upsubsame,"base_price='".$_POST['base_price']."'");
					array_push($upsubsame,"timeover_price='".$_POST['timeover_price']."'");
					array_push($upsubsame,"halfday='".$_POST['halfday']."'");
					array_push($upsubsame,"halfday_percent='".$_POST['halfday_percent']."'");
					array_push($upsubsame,"oneday_ex='".$_POST['oneday_ex']."'");
					array_push($upsubsame,"time_percent='".$_POST['time_percent']."'");
					array_push($upsubsame,"checkin_time='".$_POST['checkin_time']."'");
					array_push($upsubsame,"checkout_time='".$_POST['checkout_time']."'");
					unset($_POST['setsubsame'][$tmpkey]);
				}
				
				if(_array($upsubsame)){
					$sql = "update code_rent set ".implode(',',$upsubsame)." where code like '".$codewhere."%' and code not like '".$codewhere."000%'";
					mysql_query($sql,get_db_conn());
				}	
			}
			
			if($_POST['useseason'] != '1'){
				$sql = "delete from season_range  where code like '".$codewhere."%'";			
				mysql_query($sql,get_db_conn());				
			}						
			
			/*
			if(false !== array_search('season',$_POST['setsubsame'])){
				$sql = "update code_rent set useseason='".$_POST['useseason']."' where code like '".$codewhere."%'";
				mysql_query($sql,get_db_conn());
			}
			*/

			// 장기대여
			$dsql = "delete from rent_longrent where code like '".$codewhere."%'";
			mysql_query($dsql,get_db_conn());
			
			if(_array($_POST['longrent_sday']) && _array($_POST['longrent_percent'])){		
				for($i=0;$i<count($_POST['longrent_sday']);$i++){				
					if(_isInt($_POST['longrent_sday'][$i]) && _isInt($_POST['longrent_percent'][$i])){
						$sql = "insert into rent_longrent set code='".$codeA.$codeB.$codeC.$codeD."',sday='".$_POST['longrent_sday'][$i]."',eday='".$_POST['longrent_eday'][$i]."',percent='".$_POST['longrent_percent'][$i]."'";
						mysql_query($sql,get_db_conn());
					}
				}
			}

			// 환불 / 장기할인 처리
			$dsql = "delete from rent_refund where code like '".$codewhere."%'";		
			mysql_query($dsql,get_db_conn());
			
			if(_array($_POST['refundday']) && _array($_POST['refundpercent'])){		
				for($i=0;$i<count($_POST['refundday']);$i++){				
					if($_POST['refundpercent'][$i]>=0){
						$sql = "insert into rent_refund set code='".$codeA.$codeB.$codeC.$codeD."',day='".$_POST['refundday'][$i]."',percent='".$_POST['refundpercent'][$i]."'";
						mysql_query($sql,get_db_conn());
					}
				}
			}
			
			$dsql = "delete from rent_longdiscount where code like '".$codewhere."%'";
			mysql_query($dsql,get_db_conn());
			if(_array($_POST['discrangeday']) && _array($_POST['discrangepercent'])){
				for($i=0;$i<count($_POST['discrangeday']);$i++){
					if(_isInt($_POST['discrangeday'][$i]) && _isInt($_POST['discrangepercent'][$i])){
						$sql = "insert into rent_longdiscount  set code='".$codeA.$codeB.$codeC.$codeD."',day='".$_POST['discrangeday'][$i]."',percent='".$_POST['discrangepercent'][$i]."'";
						mysql_query($sql,get_db_conn());
					}
				}
			}
			// 환불 / 장기할인 처리 끝
	
			// 하위 적용
			$subcodes = array();
			if(_array($_POST['setsubsame'])){
				$sql = "select concat(codeA,codeB,codeC,codeD) as code from tblproductcode where concat(codeA,codeB,codeC,codeD) like '".$codewhere."%' and concat(codeA,codeB,codeC,codeD) not like '".$codewhere."000%'";
			
				if(false !== $res = mysql_query($sql,get_db_conn())){					
					for($i=0;$i<mysql_num_rows($res);$i++) array_push($subcodes,mysql_result($res,$i));					
				}	
			}
			if(_array($subcodes)){
				if(false !== array_search('season',$_POST['setsubsame'])){
					$sql = "delete from season_range  where code like '".$codewhere."%' and code not like '".$codewhere."000%'";				
					mysql_query($sql,get_db_conn()) or die(mysql_error());				
				}
				
				if(false !== array_search('longrent',$_POST['setsubsame'])){
					$sql = "delete from rent_longrent  where code like '".$codewhere."%' and code not like '".$codewhere."000%'";
					mysql_query($sql,get_db_conn()) or die(mysql_error());
				}

				if(false !== array_search('refund',$_POST['setsubsame'])){
					$sql = "delete from rent_refund  where code like '".$codewhere."%' and code not like '".$codewhere."000%'";						
					mysql_query($sql,get_db_conn()) or die(mysql_error());
				}
				
				if(false !== array_search('longdiscount',$_POST['setsubsame'])){
					$sql = "delete from rent_longdiscount  where code like '".$codewhere."%' and code not like '".$codewhere."000%'";				
					mysql_query($sql,get_db_conn()) or die(mysql_error());
				}
			
				foreach($subcodes as $targetcode){					
					if(false !== array_search('season',$_POST['setsubsame'])){					
						if(false !== $chkres = mysql_query("select count(*) from code_rent where code='".$targetcode."'",get_db_conn())){
							if(mysql_result($chkres,0,0) >0){
								$sql = "update code_rent s ,code_rent p set s.useseason=p.useseason where s.code = '".$targetcode."' and p.code like '".$codewhere."000%'";								
							}else{
								$sql = "insert into code_rent (code,pricetype,commission_self,commission_main,useseason) select '".$targetcode."',pricetype,commission_self,commission_main,useseason from code_rent where code like '".$codewhere."000%'";
							}
						
							mysql_query($sql,get_db_conn());		
						}
						
						
						$sql = "insert into season_range (code,type,start,end) select '".$targetcode."',type,start,end from season_range  where code like '".$codewhere."000%'";
						mysql_query($sql,get_db_conn());
					}
					
					if(false !== array_search('longrent',$_POST['setsubsame'])){
						$sql = "insert into rent_longrent (code,sday,eday,percent) select '".$targetcode."',sday,eday,percent from rent_longrent  where code like '".$codewhere."000%'";
						mysql_query($sql,get_db_conn());
					}

					if(false !== array_search('refund',$_POST['setsubsame'])){
						$sql = "insert into rent_refund (code,day,percent) select '".$targetcode."',day,percent from rent_refund  where code like '".$codewhere."000%'";
						mysql_query($sql,get_db_conn());
					}
					
					if(false !== array_search('longdiscount',$_POST['setsubsame'])){
						$sql = "insert into rent_longdiscount (code,day,percent) select '".$targetcode."',day,percent from rent_longdiscount  where code like '".$codewhere."000%'";
						mysql_query($sql,get_db_conn());
					}
				}				
			}				
		
			if(($is_gcode==1 || $is_sort==1 || $is_design==1 || $is_special==1) && !ereg("X",$type)) {
				$sql = "UPDATE tblproductcode SET ";
				if($is_gcode==1) $sql.= "group_code = '".$up_group_code."',";
				if($is_sort==1) $sql.= "sort = '".$up_sort."',";

				if($is_design==1) {
					$sql.= "list_type = '".$up_list_type."',";
					$sql.= "detail_type = '".$up_detail_type."',";
				}

				if($is_special==1) {
					$sql.= "special		= '".$in_special."',";
					$sql.= "special_cnt	= '".$in_special_cnt."',";
					$sql.= "islist		= '".$up_islist."',";
				}
				$sql = substr($sql,0,-1);
				$sql.= " WHERE codeA='".$codeA."' ";
				if($codeB!="000") {
					$sql.= "AND codeB='".$codeB."' ";
					if($codeC!="000") {
						$sql.= "AND codeC='".$codeC."' ";
					}
				}
				mysql_query($sql,get_db_conn());
			}

			// 디자인과 별도로 하위 카테고리중 부모 디자인 변경 따르는 부분에 대한 처리
			if($codeA != '000'){
				$cwhere = array();
				$cdep = false;
				for($i=0;$i<3;$i++){
					$key = 	'code'.chr(65+$i);
					if(${$key} == '000'){
						array_push($cwhere,$key."!='000'");
						break;
					}else array_push($cwhere,$key."='".${$key}."'");
				}
				array_push($cwhere,"dsameparent='1'");
				$csql = "UPDATE  tblproductcode  SET list_type = '".$up_list_type."',detail_type = '".$up_detail_type."' where ".implode(' and ',$cwhere);
				@mysql_query($csql,get_db_conn());
			}


			if ($codeA!="000" && $codeB=="000" && $codeC=="000" && $codeD=="000") {

				if ($b_row->code) {
					$sql = "update product_code_banner set ";
					$sql .= "banner_file = '".$image."', ";
					$sql .= "banner_url = '".$up_banner_url."', ";
					$sql .= "move_type = '".$up_move_type."' ";

					$sql .= " where code = '".$codeA."' ";
				}else{
					$sql = "insert product_code_banner set ";
					$sql .= "code = '".$codeA."', ";
					$sql .= "banner_file = '".$image."', ";
					$sql .= "banner_url = '".$up_banner_url."', ";
					$sql .= "move_type = '".$up_move_type."' ";
				}

				mysql_query($sql,get_db_conn());
			}
			$onload="<script>parent.ModifyCodeResult('".$codeA.$codeB.$codeC.$codeD."','".$type."','".$up_code_name."','".$up_list_type."','".$up_detail_type."','".$up_sort."','".$up_group_code."','".$is_gcode."','".$is_sort."','".$is_design."');parent.HiddenFrame.alert('상품카테고리 정보 수정이 완료되었습니다.');</script>";
		} else {
			$onload="<script>parent.HiddenFrame.alert('상품카테고리 정보 수정중 오류가 발생하였습니다.');</script>";
		}





		//// 카테고리 옵션 변경에 따른 하위 상품 및 카테고리 설정 변경
		$swhere = array();
		$likecode = '';

		for($i=0;$i<4;$i++){
			$key = 'code'.chr(65+$i);
			if(${$key} != '000'){
				array_push($swhere, $key."='".${$key}."'");
				$likecode .= ${$key};
			}else if(${$key} == '000'){
				array_push($swhere, $key."!='000'");
				break;
			}
		}
		$setCheckP = $setCheckC = array();

		if(!empty($up_iscoupon)){
			array_push($setCheckP,"etcapply_coupon='".(($up_iscoupon=='Y')?'N':'Y')."'");
			array_push($setCheckC,"iscoupon='".$up_iscoupon."'");
		}


		if(!empty($up_isreserve)){
			array_push($setCheckP,"etcapply_reserve='".(($up_isreserve=='Y')?'N':'Y')."'");
			array_push($setCheckC,"isreserve='".$up_isreserve."'");
		}

		if(!empty($up_isgift)){
			array_push($setCheckP,"etcapply_gift='".(($up_isgift=='Y')?'N':'Y')."'");
			array_push($setCheckC,"isgift='".$up_isgift."'");
		}


		if(!empty($up_isrefund)){
			array_push($setCheckP,"etcapply_return='".(($up_isrefund=='Y')?'N':'Y')."'");
			array_push($setCheckC,"isrefund='".$up_isrefund."'");
		}

		// 네이버 지식 쇼핑 하위 카테고리 update
		if($osyncNaverEp != $_POST['syncNaverEp']){
			array_push($setCheckP,"syncNaverEp='".(($_POST['syncNaverEp']=='0')?'0':'1')."'");
			array_push($setCheckC,"syncNaverEp='".(($_POST['syncNaverEp']=='0')?'0':'1')."'");
		}
		/*
		$up_isgift = (in_array($_POST["up_isgift"],$up_isCheck))?$_POST["up_isgift"]:"";
$up_iscoupon = (in_array($_POST["up_iscoupon"],$up_isCheck))?$_POST["up_iscoupon"]:"";
$up_isrefund = (in_array($_POST["up_isrefund"],$up_isCheck))?$_POST["up_isrefund"]:"";
$up_isreserve = (in_array($_POST["up_isreserve"],$up_isCheck))?$_POST["up_isreserve"]:"";
*/
		if(count($setCheckC) > 0){
			$sql = "update tblproductcode set ".implode(',',$setCheckC)." where ".implode(' and ',$swhere);
			mysql_query($sql,get_db_conn());
		}


		if(count($setCheckP) > 0){
			$sql = "update tblproduct set ".implode(',',$setCheckP)." where productcode like '".$likecode."%'";
			//echo $sql;
			mysql_query($sql,get_db_conn());
		}


		$sql = "SELECT * FROM tblproductcode WHERE codeA='".$codeA."' AND codeB='".$codeB."' ";
		$sql.= "AND codeC='".$codeC."' AND codeD='".$codeD."' ";
		$result = mysql_query($sql,get_db_conn());
		$row = mysql_fetch_object($result);
		mysql_free_result($result);

		//배너 관련 추가 jdy
		if ($codeA!="000" && $codeB=="000" && $codeC=="000" && $codeD=="000") {
			$sql = "SELECT * FROM product_code_banner WHERE code='".$codeA."'";

			$result = mysql_query($sql,get_db_conn());
			$b_row = mysql_fetch_object($result);

			mysql_free_result($result);
		}
		//배너 관련 추가 jdy

	}
	$type=$row->type;
	$code_name=$row->code_name;
	$list_type=$row->list_type;
	$detail_type=$row->detail_type;
	$group_code=$row->group_code;
	$sort=$row->sort;
	$special=$row->special;
	$special_cnt=$row->special_cnt;
	$islist=$row->islist;
	//카테고리옵션
	$isgift=$row->isgift;
	$iscoupon=$row->iscoupon;
	$isrefund=$row->isrefund;
	$isreserve=$row->isreserve;

	$syncNaverEp=$row->syncNaverEp;

	$dsameparent=$row->dsameparent;

	$booking_confirm=$row->booking_confirm;
	$cancel_cont=$row->cancel_cont;
	$discount_card=$row->discount_card;
	$deli_type=$row->deli_type;

	
	$arr_special=explode(",",$special);
	$old_special=$special;
	unset($special);
	for($i=0;$i<count($arr_special);$i++) {
		$special[$arr_special[$i]]="Y";
	}

	if(strlen($old_special)==0) {
		$old_special="1,2,3";
	} else {
		if(!eregi("1",$old_special)) {
			$old_special.=",1";
		}
		if(!eregi("2",$old_special)) {
			$old_special.=",2";
		}
		if(!eregi("3",$old_special)) {
			$old_special.=",3";
		}
	}

	$arrspecialcnt=explode(",",$special_cnt);
	for ($i=0;$i<count($arrspecialcnt);$i++) {
		if (substr($arrspecialcnt[$i],0,2)=="1:") {
			$tmpsp1=substr($arrspecialcnt[$i],2);
		} else if (substr($arrspecialcnt[$i],0,2)=="2:") {
			$tmpsp2=substr($arrspecialcnt[$i],2);
		} else if (substr($arrspecialcnt[$i],0,2)=="3:") {
			$tmpsp3=substr($arrspecialcnt[$i],2);
		}
	}
	if(strlen($tmpsp1)>0) {
		$special_1=explode("X",$tmpsp1);
		$special_1_cols=(int)$special_1[0];
		$special_1_rows=(int)$special_1[1];
		$special_1_type=$special_1[2];
	}
	if(strlen($tmpsp2)>0) {
		$special_2=explode("X",$tmpsp2);
		$special_2_cols=(int)$special_2[0];
		$special_2_rows=(int)$special_2[1];
		$special_2_type=$special_2[2];
	}
	if(strlen($tmpsp3)>0) {
		$special_3=explode("X",$tmpsp3);
		$special_3_cols=(int)$special_3[0];
		$special_3_rows=(int)$special_3[1];
		$special_3_type=$special_3[2];
	}

	if($special_1_cols<=0) $special_1_cols=5;
	if($special_1_rows<=0) $special_1_rows=1;
	if(strlen($special_1_type)==0) $special_1_type="I";
	if($special_2_cols<=0) $special_2_cols=5;
	if($special_2_rows<=0) $special_2_rows=1;
	if(strlen($special_2_type)==0) $special_2_type="I";
	if($special_3_cols<=0) $special_3_cols=5;
	if($special_3_rows<=0) $special_3_rows=1;
	if(strlen($special_3_type)==0) $special_3_type="I";

	$type1=substr($type,0,1);
	if (ereg("X",$type)) {
		$type2="1";	//하위카테고리 없음
	} else {
		$type2="0";	//하위카테고리 있음
	}

	$gong="N";
	if (substr($row->list_type,0,1)=="B") {
		$gong="Y";
	}

	$code_loc = "";
	$sql = "SELECT code_name,type FROM tblproductcode WHERE codeA='".substr($code,0,3)."' ";
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
	//echo $sql; exit;
	$result=mysql_query($sql,get_db_conn());
	$i=0;
	while($row=mysql_fetch_object($result)) {
		if($i>0) $code_loc.= " > ";
		$code_loc.= $row->code_name;
		$i++;
	}
	mysql_free_result($result);

	/* 배너 관련 추가jdy */
	$up_banner_file = $b_row->banner_file;
	$up_banner_url = $b_row->banner_url;
	$up_move_type = $b_row->move_type;
	$banner_img = "";

	if (!empty($up_banner_file) && file_exists($imagepath.$up_banner_file)) {
		$banner_img = "<img src=\"".$imagepath.$up_banner_file."\" width=\"200\" />";
	}

	/* 배너 관련 추가jdy */

} else if ($mode=='banner_del') {

	$codeA=substr($code,0,3);
	$codeB=substr($code,3,3);
	$codeC=substr($code,6,3);
	$codeD=substr($code,9,3);

	//배너이미지 삭제
	$sql = "SELECT * FROM product_code_banner WHERE code='".$codeA."'";

	$result = mysql_query($sql,get_db_conn());
	$b_row = mysql_fetch_object($result);

	mysql_free_result($result);

	$up_banner_file = $b_row->banner_file;

	if (!empty($up_banner_file) && file_exists($imagepath.$up_banner_file)) {
		unlink($imagepath.$up_banner_file);

		$sql = "update product_code_banner set banner_file='' WHERE code='".$codeA."'";
		mysql_query($sql,get_db_conn());
	}

	echo "
		<form name=form1 action=product_code.property.php method=post>
		<input type=hidden name=mode value=modify>
		<input type=hidden name=code value=".$code.">
		</form>

		<script>alert('배너가 삭제 되었습니다.');document.form1.submit();</script>
	";
	exit();

} else {
	$mode="insert";
	$islist="Y";
	//카테고리옵션
	$isgift="Y";
	$iscoupon="Y";
	$isrefund="Y";
	$isreserve="Y";
	if(strlen($old_special)==0) $old_special="1,2,3";
	$special_cnt=4;

	$special_1_type="I";
	$special_1_cols=5;
	$special_1_rows=1;
	$special_2_type="I";
	$special_2_cols=5;
	$special_2_rows=1;
	$special_3_cols=5;
	$special_3_type="I";
	$special_3_rows=1;

	$syncNaverEp= '1';
	
	
//	$row->useseason	= pick($row->useseason,$shop_more_info['useseason']);

}

if(strlen($code)==0 && strlen($parentcode)==0) {
	$code_loc = "최상위 카테고리";
} else if(strlen($parentcode)==12) {
	if(substr($parentcode,9,3)!="000") {
		echo "<script>parent.HiddenFrame.alert('상위카테고리 선택이 잘못되었습니다.');location.replace('".$_SERVER[PHP_SELF]."');</script>";
		exit;
	} else {
		$sql = "SELECT type,syncNaverEp FROM tblproductcode ";
		$sql.= "WHERE codeA='".substr($parentcode,0,3)."' ";
		$sql.= "AND codeB='".substr($parentcode,3,3)."' ";
		$sql.= "AND codeC='".substr($parentcode,6,3)."' ";
		$sql.= "AND codeD='".substr($parentcode,9,3)."' ";
		$result=mysql_query($sql,get_db_conn());
		if($row=mysql_fetch_object($result)) {
			if(ereg("X",$row->type)) {
				echo "<script>parent.HiddenFrame.alert('상위카테고리 선택이 잘못되었습니다.');location.replace('".$_SERVER[PHP_SELF]."');</script>";
				exit;
			}
		} else {
			echo "<script>parent.HiddenFrame.alert('상위카테고리 선택이 잘못되었습니다.');location.replace('".$_SERVER[PHP_SELF]."');</script>";
			exit;
		}

		$syncNaverEp= $row->syncNaverEp; // 네이버 지식 쇼핑 연동 관련



		mysql_free_result($result);
	}
	$code_loc = "";
	$sql = "SELECT code_name,type FROM tblproductcode WHERE codeA='".substr($parentcode,0,3)."' ";
	if(substr($parentcode,3,3)!="000") {
		$sql.= "AND (codeB='".substr($parentcode,3,3)."' OR codeB='000') ";
		if(substr($parentcode,6,3)!="000") {
			$sql.= "AND (codeC='".substr($parentcode,6,3)."' OR codeC='000') ";
		} else {
			$sql.= "AND codeC='000' ";
		}
	} else {
		$sql.= "AND codeB='000' AND codeC='000' ";
	}
	$sql.= "AND codeD='000' ";
	$sql.= "ORDER BY codeA,codeB,codeC,codeD ASC ";
	//echo $sql; exit;
	$result=mysql_query($sql,get_db_conn());
	$i=0;
	while($row=mysql_fetch_object($result)) {
		if($i>0) $code_loc.= " >> ";
		$code_loc.= $row->code_name;
		$type1=substr($row->type,0,1);
		$i++;
	}
	mysql_free_result($result);

	if(substr($parentcode,6,3)!="000") {
		$type2=1;
	}


	$dsameparent = '1';
	
}

if(_empty($codeinfo->useseason) && !_empty($parentcode)){
	if(false === $pres = mysql_query("select * from code_rent where code='".$parentcode."' limit 1",get_db_conn())){
		if(mysql_num_rows($pres)) $codeinfo = mysql_fetch_object($pres);
	}
	
}
?>
<? INCLUDE "header.php";
echo '<!-- '.time().' -->';
?>
<script type="text/javascript" src="lib.js.php"></script>
<script>var LH = new LH_create();</script>
<script for=window event=onload>LH.exec();</script>
<script>//LH.add("parent_resizeIframe('PropertyFrame')");</script>
<script type="text/javascript" src="/upload/js/jquery-1.7.1.min.js"></script>
<script type="text/javascript">var $j= jQuery.noConflict();</script>
<SCRIPT LANGUAGE="JavaScript">
<!--
function DesignList(idx) {
	document.form1.gong[idx].checked=true;
	checkDesignSame(null);

	if(document.form1.gong[0].checked==true) gong="N";
	else gong="Y";
	up_list_type=document.form1.up_list_type.value;
	window.open("design_productlist.php?code="+up_list_type+"&gong="+gong,"design","height=450,width=380,scrollbars=yes");
}

function DesignDetail(idx) {
	document.form1.gong[idx].checked=true;
	checkDesignSame(null);

	if(document.form1.gong[0].checked==true) gong="N";
	else gong="Y";
	up_detail_type=document.form1.up_detail_type.value;
	window.open("design_productdetail.php?code="+up_detail_type+"&gong="+gong,"design2","height=450,width=380,scrollbars=yes");
}

function ChangeSequence() {
	txt=document.form1.fcode.options[document.form1.fcode.selectedIndex].text;
	if((num=txt.indexOf("(가상대카테고리)"))>0) document.form1.selectedfcodename.value=txt.substr(0,num);
	else document.form1.selectedfcodename.value = txt;
}

function GroupCheck(checked){
	if (checked==true) {
		alert('카테고리를 숨길경우 메인에 표시된 상품은 그대로 표시됩니다.\n확인후 메인상품의 경우는 직접 메인에서 삭제를 해주셔야 합니다.');
		document.form1.up_group_code.disabled=true;
	} else {
		document.form1.up_group_code.disabled=false;
	}
}

function Save() {
	mode = document.form1.mode.value;
	if (document.form1.up_code_name.value.length==0) {
		document.form1.up_code_name.focus();
		alert("카테고리명을 입력하세요.");
		return;
	}
	if (CheckLength(document.form1.up_code_name)>100) {
		alert('총 입력가능한 길이가 한글 50자까지입니다. 다시한번 확인하시기 바랍니다.');
		document.form1.up_code_name.focus();
		return;
	}
	if (mode=="insert") {
		if(typeof(document.form1.up_type1)=="object") {
			if (document.form1.up_type1[0].checked==false && document.form1.up_type1[1].checked==false && document.form1.up_type1[2].checked==false) {
				alert("카테고리 타입을 선택하세요.");
				return;
			}
		}
		if(typeof(document.form1.up_type2)=="object") {
			if (document.form1.up_type2[0].checked==false && document.form1.up_type2[1].checked==false) {
				alert("하위카테고리 유무를 선택하세요.");
				return;
			}
		}
	}
<?if ($type1!="S") {?>
	social_chk = false;
	//소셜스킨선택제외
	/*
	if(typeof(document.form1.up_type1)=="object" && document.form1.up_type1[2].checked==true){
		social_chk = true;
    }
	*/
	if((typeof(document.form1.up_type1)=="object" && document.form1.up_type1[2].checked==true) || (document.form1.dsameparent && document.form1.dsameparent.checked)){
		social_chk = true;
    }

	/*if(!social_chk ){
		if (document.form1.up_list_type.value.length==0) {
			alert("상품진열 디자인을 선택하세요.");
			if(document.form1.gong[0].checked==true) DesignList(0);
			else DesignList(1);
			return;
		} else {
			list_type=document.form1.up_list_type.value.substring(0,1);
			if(document.form1.gong[0].checked==true) {
				if(list_type!="A") {
					alert("상품진열 디자인을 선택하세요.");
					DesignList(0);
					return;
				}
			} else {
				if(list_type!="B") {
					alert("상품진열 디자인을 선택하세요.");
					DesignList(1);
					return;
				}
			}
		}
		if (document.form1.up_detail_type.value.length==0) {
			alert("상품상세 디자인을 선택하세요.");
			if(document.form1.gong[0].checked==true) DesignDetail(0);
			else DesignDetail(1);
			return;
		} else {
			detail_type=document.form1.up_detail_type.value.substring(0,1);
			if(document.form1.gong[0].checked==true) {
				if(detail_type!="A") {
					alert("상품상세 디자인을 선택하세요.");
					DesignDetail(0);
					return;
				}
			} else {
				if(detail_type!="B") {
					alert("상품상세 디자인을 선택하세요.");
					DesignDetail(1);
					return;
				}
			}
		}
	}*/
<? }?>

	/*
	if (document.form1.up_sort.selectedIndex<=0) {
		alert("상품 정렬 방법을 선택하세요.");
		return;
	}
	*/
	up_special="";
	for(i=0;i<document.form1.tmp_special.length;i++) {
		if(document.form1.tmp_special[i].checked==true) {
			up_special+=","+document.form1.tmp_special[i].value;
		}
	}
	if(up_special.length>0) {
		up_special=up_special.substring(1,up_special.length);
	}
	document.form1.up_special.value=up_special;
	document.form1.submit();
}

function DesignMsg(type){
	if (type==0 && confirm("일반쇼핑몰타입으로 상품이 진열되는 방식입니다!\n상품진열선택과 상품상세선택을 셋팅해 주세요!")) {
		document.form1.gong[0].checked=true;
	} else if(type==0) {
		document.form1.gong[1].checked=true;
	} else if (type==1 && confirm("공동구매타입으로 상품이 진열되는 방식입니다!\n공구상품진열선택과 공구상품상세선택을 셋팅해 주세요!")) {
		document.form1.gong[1].checked=true;
	} else if(type==1) {
		document.form1.gong[0].checked=true;
	}
	checkDesignSame(null);

}

function CodeDelete() {
	submit=true;
	con = "삭제하시겠습니까?\n하위카테고리 및 상품이 모두 지워집니다.";
	con2= "카테고리삭제는 하위카테고리 및 상품이 삭제되오니 신중히 하시기 바랍니다.\n\n최종확인을 합니다."
	if (confirm(con)) {
		if (!confirm(con2)) submit=false;
	} else submit=false;
	if (submit) {
		parent.CodeDelete2(document.form1.code.value);
	}
}

var clickgbn=false;
function ChildCodeClick() {
//	WinObj=eval("document.all.child_layer");
	WinObj=document.getElementById("child_layer");
	if(clickgbn==false) {
		WinObj.style.visibility = "visible";
		clickgbn=true;
	} else if (clickgbn==true) {
		WinObj.style.visibility = "hidden";
		clickgbn=false;
	}
}

function chGroupCode(val) {
	WinObj=eval("document.all.gcode_layer");

	if(val=="ALL") {
		WinObj.style.display = "";
	} else{
		WinObj.style.display = "none";
	}
}

function checkDesignSame(el){
	if(document.form1.dsameparent){
		if(el == document.form1.dsameparent){
			if(document.form1.dsameparent.checked){
				document.form1.gong[0].checked = false;
				document.form1.gong[1].checked = false;
			}
		}else{
			if(el == document.form1.dsameparent){
				document.form1.gong[0].checked = false;
				document.form1.gong[1].checked = false;
			}else{
				document.form1.dsameparent.checked = false;
			}
		}
	}
}

function deleteBannerImg() {

	if(confirm("배너 이미지를 삭제하시겠습니까?")) {
		document.form1.mode.value="banner_del";
		document.form1.submit();
	}
}

/*검색키워드*/
function addKwGroup(){
	$("#kwgroup").val("");
	$(".div_kw").hide();
	$(".div_kw2").show();
}

function addKwCancel(){
	$(".div_kw").show();
	$(".div_kw2").hide();
}

function addKwSend(val){
	var data = "";
	data = 'mode=kwgroup_insert&kwgroup='+$("#kwgroup").val();

	jQuery.ajax({
		url: "./keyword_ajax_process.php",
		type: "POST",
		data: data,
		contentType: "application/x-www-form-urlencoded;charset=euc-kr",
		success: function(res) {
			$("#kw_group").append(res);
			$(".div_kw").show();
			$(".div_kw2").hide();
		},
		error: function(result) {
			console.log(result);
		},
		timeout: 30000
	});
}

function addKwText(idx){
	var addDiv = "#"+idx+"addDiv";
	var addDiv2 = "#"+idx+"addDiv2";
	$(addDiv).hide();
	$(addDiv2).show();
}

function cancelKwText(idx){
	var addDiv = "#"+idx+"addDiv";
	var addDiv2 = "#"+idx+"addDiv2";
	$(addDiv).show();
	$(addDiv2).hide();
}

function addKwSelect(idx,val){
	
	for(i=0;i<document.getElementsByName("kg_idx[]").length;i++){
		if(document.getElementsByName("kg_idx[]")[i].value==idx){
			alert("이미 등록된 분류입니다.");return;
		}
	}

	var htmlView = "";
	htmlView += "<li>";
	htmlView += "<input type=\"hidden\" name=\"kg_idx[]\" value=\""+idx+"\"></span>";
	htmlView += "<span><input type=\"checkbox\" name=\""+idx+"_useyn\" value=\"Y\" checked></span>";
	htmlView += "<span style=\"padding:5px;font-weight:bold\">"+val+"</span>";
	htmlView += "<span></span>";
	htmlView += "<span id=\""+idx+"addDiv\">";
	htmlView += "<button type=\"button\" onclick=\"addKwText('"+idx+"')\">추가</button>";
	htmlView += "</span>";
	htmlView += "<span id=\""+idx+"addDiv2\" style=\"display:none\">";
	htmlView += "<input type=\"text\" id=\""+idx+"_kw_text\" name=\""+idx+"_kw_text\" placeholder=\"키워드를 입력하세요.\">";
	htmlView += "<button type=\"button\" onclick=\"insertKwText('"+idx+"')\">확인</button>";
	htmlView += "<button type=\"button\" onclick=\"cancelKwText('"+idx+"')\">취소</button>";
	htmlView += "</span>";
	htmlView += "</li>";

	$(".kw_view ul").append(htmlView);
	parent.autoResize('PropertyFrame');
}

function insertKwText(idx){
	var valInput = "#"+idx+"_kw_text";
	var addDiv = "#"+idx+"addDiv";
	var addDiv2 = "#"+idx+"addDiv2";

	var htmlView = "";
	htmlView += "<span id=\"\">";
	htmlView += $(valInput).val();
	htmlView += "<input type=\"hidden\" name=\""+idx+"_kw[]\" value=\""+$(valInput).val()+"\">";
	htmlView += "<button type=\"button\" onclick=\"delKwText(this)\" style=\"margin:2px;\">X</button> ";
	htmlView += "</span>";

	$(addDiv).before(htmlView);
	$(valInput).val('');
	
	$(addDiv).show();
	$(addDiv2).hide();
}

function delKwText(el){
	$ptr=$(el).parent();
	$ptr.remove();
}

function modifyKwGroup() {
	window.open("keyword_modify_pop.php","keyword","height=450,width=380,scrollbars=yes");
}
/*검색키워드*/
//-->
</SCRIPT>

<table cellpadding="0" cellspacing="0" width="100%" height="100%" bgcolor="#ffffff">
	<tr>
		<td width="100%" bgcolor="#FFFFFF"><IMG SRC="images/product_cate_function_title.gif" ALT="" /><a href="javascript:document.location.reload();">.</a></td>
	</tr>
	<tr>
		<td width="100%" height="100%" valign="top" style="BORDER:#FF8730 2px solid;padding-left:5px;padding-right:5px;">
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post onsubmit="return false" enctype="multipart/form-data">	
			<input type=hidden name=mode value="<?=$mode?>">
			<input type=hidden name=code value="<?=$code?>">
			<input type=hidden name=parentcode value="<?=$parentcode?>">
			<input type=hidden name=mode_result value="result">
			<input type=hidden name=up_list_type value="<?=$list_type?>">
			<input type=hidden name=up_detail_type value="<?=$detail_type?>">
			<input type=hidden name=old_special value="<?=$old_special?>">
			<input type=hidden name=up_special>
			<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
			<col width=141></col>		
			<col width=""></col>		
				<tr>
					<td colspan="2" height="10"></td>
				</tr>
				<TR>
					<TD colspan="2" style="height:1px" background="images/table_con_line.gif"></TD>
				</TR>
				<? if($mode=="modify"){?>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0"><b>카테고리 코드</b></TD>
					<TD class="td_con1" style="font-weight:bold"><?=$code?></TD>
				</TR>
				<TR>
					<TD colspan="2" style="height:1px" background="images/table_con_line.gif"></TD>
				</TR>
				<? }?>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0"><b>카테고리명</b></TD>
					<TD class="td_con1"><input type=text name=up_code_name size=38 maxlength=100 value="<?=htmlspecialchars($code_name)?>" class="input_selected" style="width:100%"></TD>
				</TR>
				<TR>
					<TD colspan="2" style="height:1px" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">카테고리위치</TD>
					<TD class="td_con1"><?=$code_loc?></TD>
				</TR>
				<TR>
					<TD colspan="2" style="height:1px" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">카테고리타입</TD>
					<TD class="td_con1">
						<?
			if ($mode=="modify" || (strlen($parentcode)==12 && strlen($type1)>0)) {
				if ($type1=="L") echo "기본 카테고리";
				else if ($type1=="T") echo "가상 카테고리";
				else if ($type1=="S") echo "소셜 카테고리<input type=\"hidden\" name=\"up_type\" value=\"S\">";
			} else { ?>
						<input type=radio id="idx_type1_1" name=up_type1 value="L"  style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;">
						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_type1_1>기본 카테고리</label>
						<input type=radio id="idx_type1_2"  name=up_type1 value="T" style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;">
						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_type1_2>가상 카테고리</label>
						<input type=radio id="idx_type1_3" name=up_type1 value="S"  style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;">
						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'\" for=idx_type1_3>소셜 카테고리</label>
						<?	}
		?>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" style="height:1px" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"> <img src="images/icon_point2.gif" width="8" height="11" border="0">하위카테고리유무 </TD>
					<TD class="td_con1">
						<?
				if ($mode=="modify" || (strlen($parentcode)==12 && $type2==1)) {
					if ($type2=="0") echo "하위카테고리 있음";
					else echo "하위카테고리 없음";
				} else { ?>
						<input type=radio id="idx_type2_1" name=up_type2 value="0" checked style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;" >
						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" onclick="javascript:alert('카테고리 등록시 한번 설정한 하위카테고리유무는 변경이 불가능 하므로 신중히 선택해 주세요.');" for=idx_type2_1>하위카테고리 있음</label>
						<input id="idx_type2_2" type=radio name=up_type2 value="1" style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;">
						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" onclick="javascript:alert('카테고리 등록시 한번 설정한 하위카테고리유무는 변경이 불가능 하므로 신중히 선택해 주세요.');" for=idx_type2_2>하위카테고리 없음</label>
						<?		} ?>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" style="height:1px" background="images/table_con_line.gif"></TD>
				</TR>
		<?	if($mode!="modify" || $type1 == 'L'){ ?>

		<!--
				<TR>
					<TD class="table_cell"> <img src="images/icon_point2.gif" width="8" height="11" border="0" />회원등급별 할인</TD>
					<TD class="td_con1">
					<?
					$groupdiscount = getGroupDiscounts(pick($code,$parentcode));							
					foreach($groupdiscount as $gdiscount){ 
						$discount = ($gdiscount['discount'] < 1)?$gdiscount['discount']*100:$gdiscount['discount'];
					?>
						<span style="margin-right:10px;"><span style="font-weight:bold;"><?=$gdiscount['group_name']?></span>&nbsp;
						<input name="discount[<?=$gdiscount['group_code']?>]" id="discount<?=$gdiscount['group_code']?>" size="10" type="text" class="input" value="<?=$discount?>" onkeyup="javascript:checkGroupReserveVal('<?=$gdiscount['group_code']?>')" style="width:30px; text-align:right; padding-right:5px;"><input name="discount_type[<?=$gdiscount['group_code']?>]" type="hidden" value="100" />%</span>
<?						}
						?><br />					
						<input type="checkbox"  name="setsubsame[]" value="gdiscount" />
						체크시 하부카테고리 일괄 적용<span style="color:red; display:block">*변경 사항의 적용을 위해서는 반드시 하단의 "카테고리 수정하기" 버튼으로 저장 하셔야 합니다.</span> </TD>		
					</TD>
				</TR>
				<TR>
					<TD colspan="2" style="height:1px" background="images/table_con_line.gif"></TD>
				</TR>
-->

				<tr>
					<TD class="table_cell">회원등급별적립</TD>					
					<TD class="td_con1"> 
					<?
					$groupdiscount = getGroupReserves(pick($code,$parentcode));					
					$rgroupdiscount = getReqGroupReserves($code);
					
					foreach($groupdiscount as $gdiscount){ 
						$discount = ($gdiscount['reserve'] < 1)?$gdiscount['reserve']*100:$gdiscount['reserve'];
					?>
						<span style="margin-right:10px;"><span style="font-weight:bold;"><?=$gdiscount['group_name']?></span>&nbsp;
						<input name="discount[<?=$gdiscount['group_code']?>]" id="discount<?=$gdiscount['group_code']?>" size="10" type="text" class="input" value="<?=$discount?>" onkeyup="javascript:checkGroupReserveVal('<?=$gdiscount['group_code']?>')" style="width:30px; text-align:right; padding-right:5px;"><input name="discount_type[<?=$gdiscount['group_code']?>]" type="hidden" value="100" />%</span>
					<?						
					}
					?><br />					
						<input type="checkbox"  name="setsubsame[]" value="gdiscount" />
						체크시 하부카테고리 일괄 적용<span style="color:red; display:block">*변경 사항의 적용을 위해서는 반드시 하단의 "카테고리 수정하기" 버튼으로 저장 하셔야 합니다.</span> </TD>
					</td>
				</tr>
				<TR>
					<TD colspan="2" style="height:1px" background="images/table_con_line.gif"></TD>
				</TR>

				<TR>
					<TD class="table_cell">추천인등급별 적립</TD>					
					<TD class="td_con1"> 
					<?
					$groupdiscount2 = getGroupReseller_Reserves(pick($code,$parentcode));					
					$rgroupdiscount2 = getReqGroupReseller_Reserves($code);
					
					foreach($groupdiscount2 as $gdiscount2){
						$discount2 = ($gdiscount2['reserve'] < 1)?$gdiscount2['reserve']*100:$gdiscount2['reserve'];
					?>
						<span style="margin-right:10px;"><span style="font-weight:bold;"><?=$gdiscount2['group_name']?></span>&nbsp;
						<input name="discount2[<?=$gdiscount2['group_code']?>]" id="discount2<?=$gdiscount2['group_code']?>" size="10" type="text" class="input" value="<?=$discount2?>" onkeyup="javascript:checkGroupReserveVal('<?=$gdiscount2['group_code']?>')" style="width:30px; text-align:right; padding-right:5px;"><input name="discount_type2[<?=$gdiscount2['group_code']?>]" type="hidden" value="100" />%</span>
					<?						
					}
					?><br />					
						<input type="checkbox"  name="setsubsame[]" value="gdiscount2" />
						체크시 하부카테고리 일괄 적용<span style="color:red; display:block">*변경 사항의 적용을 위해서는 반드시 하단의 "카테고리 수정하기" 버튼으로 저장 하셔야 합니다.</span> </TD>
					</td>
						
					</td>
				</tr>
				<TR>
					<TD colspan="2" style="height:1px" background="images/table_con_line.gif"></TD>
				</TR>


				
				<TR>
					<TD class="table_cell">
						<style type="text/css">
						 .infoListTbl{border:1px solid #CDDDE0;}
						 .infoListTbl th{ font-weight:bold; background:#efefef; border-right:1px solid #CDDDE0; border-bottom:1px solid #CDDDE0; font-size:11px;}
						 .infoListTbl td{  background:#fff; border-right:1px solid #CDDDE0; border-bottom:1px solid #CDDDE0;}
						 .infoListTbl .norbl{border-right:0px;}
						 .infoListTbl .nobbl{border-bottom:0px;}
						 </style>
						<img src="images/icon_point2.gif" width="8" height="11" border="0">대여수수료 설정 </TD>
					<TD class="td_con1"> 셀프
						<input type="text" name="commission_self" value="<?=pick($codeinfo->commission_self,$shop_more_info['commi_self'])?>" size="4" style="width:40px" />
						% / 위탁
						<input type="text" name="commission_main" value="<?=pick($codeinfo->commission_main,$shop_more_info['commi_main'])?>" size="4" style="width:40px" />
						%
						(기본 설정 -  셀프 : <span style="color:#0C0">
						<?=$shop_more_info['commi_self']?>
						</span>% / 위탁 : <span style="color:#0C0">
						<?=$shop_more_info['commi_main']?>
						</span>%) <br />
						<input type="checkbox" name="setsubsame[]" value="commission" />
						체크시 하부카테고리 일괄 적용 </TD>
				</TR>
				<TR>
					<TD colspan="2" style="height:1px" background="images/table_con_line.gif"></TD>
				</TR>

				<tr>
					<TD class="table_cell"> <img src="images/icon_point2.gif" width="8" height="11" border="0">대여가능시간</TD>
					<TD class="td_con1">
						시작: <input type="text" name="rent_stime" id="rent_stime" size="3" maxlength="2" value="<?=$codeinfo->rent_stime?>">시 ~
						종료: <input type="text" name="rent_etime" id="rent_etime" size="3" maxlength="2" value="<?=$codeinfo->rent_etime?>">시 
						*24시간 대여인 경우 시작과 종료시간을 같게 설정하세요.
					</TD>
				</tr>
				<TR>
					<TD colspan="2" style="height:1px" background="images/table_con_line.gif"></TD>
				</TR>

				<script language="javascript" type="text/javascript">
				function chPriceType(){ 
					var idx = $j("#pricetype > option:selected").val(); 
					if(idx=="day"){
						$j('#day_div').show();
						$j('#time_div').hide();
						$j('#checkout_div').hide();
						$j('#period_div').hide();
						$j('#long_div').hide();
					}else if(idx=="time"){								
						$j('#day_div').hide();
						$j('#time_div').show();
						$j('#checkout_div').hide();
						$j('#period_div').hide();
						$j('#long_div').hide();
					}else if(idx=="checkout"){
						$j('#day_div').hide();
						$j('#time_div').hide();
						$j('#checkout_div').show();
						$j('#period_div').hide();
						$j('#long_div').hide();
						$j('#checkin_time').val($j('#rent_stime').val());
						$j('#checkout_time').val($j('#rent_etime').val());
					}else if(idx=="period"){
						$j('#day_div').hide();
						$j('#time_div').hide();
						$j('#checkout_div').hide();
						$j('#period_div').show();
						$j('#long_div').hide();
					}else if(idx=="long"){
						$j('#day_div').hide();
						$j('#time_div').hide();
						$j('#checkout_div').hide();
						$j('#period_div').hide();
						$j('#long_div').show();
					}
				}

				function halfdayCheck(val){			
					if(val=="Y"){
						html = '<div>당일 12시간 요금: 24시간 요금의 <input type="text" name="halfday_percent" size="3" maxlength="4">%</div>';
						$j('#price1').html(html);
					}else{
						html = '';
						$j('#price1').html(html);
					}
				}

				function onedayexCheck(val){			
					if(val=="time"){
						html = '<div>추가 1시간 요금: 24시간 요금의 <input type="text" name="time_percent" size="3" maxlength="4">%</div>';
						$j('#price2').html(html);
					}else if(val=="half"){
						html = '<div>추가 12시간 요금: 24시간 요금의 <input type="text" name="time_percent" size="3" maxlength="4">%</div>';
						$j('#price2').html(html);
					}else{
						html = '';
						$j('#price2').html(html);
					}
				}
				</script>
				<TR>
					<TD class="table_cell"> <img src="images/icon_point2.gif" width="8" height="11" border="0">과금방식 </TD>
					<TD class="td_con1">
						<select name="pricetype" id="pricetype" onchange="javascript:chPriceType()">
							<option value="day" <? if($codeinfo->pricetype == 'day') echo ' selected="selected"'; ?> >24시간제</option>
							<option value="time" <? if($codeinfo->pricetype == 'time') echo ' selected="selected"'; ?>>1시간제</option>
							<option value="checkout" <? if($codeinfo->pricetype == 'checkout') echo ' selected="selected"'; ?>>일일제(숙박제)</option>
							<option value="period" <? if($codeinfo->pricetype == 'period') echo ' selected="selected"'; ?> >단기기간제</option>
							<option value="long" <? if($codeinfo->pricetype == 'long') echo ' selected="selected"'; ?> >장기기간제(약정)</option>
						</select>
						<input type="checkbox"  name="setsubsame[]" value="pricetype" /> 체크시 하부카테고리 일괄 적용 
					</TD>
				</TR>

				<tr>
					<TD class="table_cell"> </TD>
					<TD class="td_con1">
						<? if($codeinfo->pricetype == 'day') $display = ""; else $display = "none"; ?>
						<table id="day_div" class="infoListTbl" cellpadding="0" cellspacing="0" style="display:<?=$display?>;width:100%;margin-top:7px;padding:7px 7px 7px 7px;">
							<tr>
								<th style="width:150px;">당일 12시간 대여허용</th>
								<td style="padding:5px;">
									<input type=radio name=halfday value="Y" <?if($codeinfo->halfday=="Y")echo"checked";?> onclick="halfdayCheck('Y')">예
									<input type=radio name=halfday value="N" <?if($codeinfo->halfday=="N")echo"checked";?> onclick="halfdayCheck('N')">아니오
								</td>
								<td id="price1" style="width:300px">
									<?
									if($codeinfo->halfday=="Y"){
										echo '<div>당일 12시간 요금: 24시간 요금의 <input type="text" name="halfday_percent" size="3" maxlength="4" value="'.$codeinfo->halfday_percent.'">%</div>';
									}
									?>
								</td>
							</tr>
							<tr>
								<th>1일 초과시 과금기준</th>
								<td style="padding:5px;">
									<input type=radio name=oneday_ex value="day" <?if($codeinfo->oneday_ex=="day")echo"checked";?> onclick="onedayexCheck('day')">1일 단위
									<input type=radio name=oneday_ex value="half" <?if($codeinfo->oneday_ex=="half")echo"checked";?> onclick="onedayexCheck('half')">12시간 단위
									<input type=radio name=oneday_ex value="time" <?if($codeinfo->oneday_ex=="time")echo"checked";?> onclick="onedayexCheck('time')">1시간 단위
								</td>
								<td id="price2">
									<?
									if($codeinfo->oneday_ex=="time"){
										echo '<div>추가 1시간 요금: 24시간 요금의 <input type="text" name="time_percent" size="3" maxlength="4" value="'.$codeinfo->time_percent.'">%</div>';
									}else if($codeinfo->oneday_ex=="half"){
										echo '<div>추가 12시간 요금: 24시간 요금의 <input type="text" name="time_percent" size="3" maxlength="4" value="'.$codeinfo->time_percent.'">%</div>';
									}
									?>
								</td>
							</tr>
						</table>
						<? if($codeinfo->pricetype == 'time') $display = ""; else $display = "none"; ?>
						<table id="time_div" class="infoListTbl" cellpadding="0" cellspacing="0" style="display:<?=$display?>;width:100%;margin-top:7px;padding:7px 7px 7px 7px;">
							<tr>
								<th style="width:100px;">기본요금</th>
								<td class="norbl" style="padding:5px;">
									<select name="base_time">
										<? for($i=1;$i<=36;$i++){?>
										<option value="<?=$i?>" <? if($codeinfo->base_time == $i) echo ' selected="selected"'; ?> ><?=$i?>시간</option>
										<? } ?>
									</select>
									<input type="text" name="base_price" size="15" value="<?=$codeinfo->base_price?>">원
								</td>
							</tr>
							<tr>
								<th>추가시간당</th>
								<td>
									<input type="text" name="timeover_price" size="15" value="<?=$codeinfo->timeover_price?>">원
								</td>
							</tr>
						</table>
						<? if($codeinfo->pricetype == 'checkout') $display = ""; else $display = "none"; ?>
						<table id="checkout_div" class="infoListTbl" cellpadding="0" cellspacing="0" style="display:<?=$display?>;width:100%;margin-top:7px;padding:7px 7px 7px 7px;">
							<tr>
								<th style="width:100px;">체크인 시간</th>
								<td class="norbl" style="padding:5px;">
									<input type="text" name="checkin_time" size="3" maxlength="2" value="<?=$codeinfo->checkin_time?>">시
								</td>
								<th style="width:100px;">체크아웃 시간</th>
								<td>
									<input type="text" name="checkout_time" size="3" maxlength="2" value="<?=$codeinfo->checkout_time?>">시
								</td>
							</tr>
						</table>
						<? if($codeinfo->pricetype == 'period') $display = ""; else $display = "none"; ?>
						<table id="period_div" class="infoListTbl" cellpadding="0" cellspacing="0" style="display:<?=$display?>;width:100%;margin-top:7px;padding:7px 7px 7px 7px;">
							<tr>
								<th style="width:100px;">기본대여일</th>
								<td class="norbl" style="padding:5px;">
									<input type="text" name="base_period" size="5" value="<?=$codeinfo->base_period?>" onkeyup="javascript:$j('#addLongrent_sday').val(parseInt($j('input[name=base_period]').val())+1);">일 까지
									&nbsp;&nbsp;*3일은 2박 3일입니다.
								</td>
							</tr>
						</table>
						<? if($codeinfo->pricetype == 'long') $display = ""; else $display = "none"; ?>
						<table id="long_div" class="infoListTbl" cellpadding="0" cellspacing="0" style="display:<?=$display?>;width:100%;margin-top:7px;padding:7px 7px 7px 7px;">
							<tr>
								<th style="width:100px;">만기 후 소유권</th>
								<td class="norbl" style="padding:5px;">
									<input type=radio name="ownership " value="mv" <?if($codeinfo->ownership=="mv")echo"checked";?>>이전 
									<input type=radio name="ownership" value="re" <?if($codeinfo->ownership=="re")echo"checked";?>>반납
								</td>
							</tr>
						</table>

					</td>
				</tr>

		<TR>
			<TD colspan="2" style="height:1px" background="images/table_con_line.gif"></TD>
		</TR>
				<TR>
					<TD class="table_cell"> <img src="images/icon_point2.gif" width="8" height="11" border="0">장기대여설정</TD>
					<TD class="td_con1">
						<? 
						$longrentinfo = rentLongrentCharge(pick($codeinfo->code,$parentcode));				
						?>
						<style type="text/css">
						#longrentDiv div{float:left;width:30%;min-width:250px;margin-right:3px;padding:5px;background:#f4f4f4}
						#longrentDiv div img{cursor:pointer}
						</style>
						<script language="javascript" type="text/javascript">
						$j(function(){
							$j(document).on('click','#longrentDiv>div>img',function(e){
								rmvLongrentCharge(this);
							});

							<?if(_array($longrentinfo)){?>
								$j('#addLongrent_sday').val($j('input[name=last_eday]').val());
							<?}?>
						});
						function rmvLongrentCharge(el){
							$j(el).parent().remove();
							if($j('#addLongrent_sday').val()>$j(el).parent().find('input[name^=longrent_sday]').val()){
								$j('#addLongrent_sday').val($j(el).parent().find('input[name^=longrent_sday]').val());
							}
						}
						function addLongrentCharge(){
							var sd = parseInt($j('#addLongrent_sday').val());
							var ed = parseInt($j('#addLongrent_eday').val());
							var p = parseInt($j('#addLongrentPercent').val());
							if(isNaN(sd) || sd < 1){
								alert('날짜를 올바르게 입력하세요.');
								$j('#addLongrent_sday').focus();
							}else if(isNaN(ed) || ed < 1){
								alert('날짜를 올바르게 입력하세요.');
								$j('#addLongrent_eday').focus();
							}else if(isNaN(p) || p < 1){
								alert('추가과금율를 올바르게 입력하세요.');
								$j('#addLongrentPercent').focus();
							}else{
								var dupvalel = null;
								$j('#longrentDiv>div').each(function(idx,el){
									if($j(el).find('input[name^=longrent_sday]').val() == String(sd)){
										dupvalel = $j(el);
										return false;
									}
								});
								if(dupvalel){
									alert('중복된 일자가 있습니다. 먼저 중복 항목을 삭제후 추가 하시기 바랍니다.');
								}else{
									html = '<div><input type="hidden" name="longrent_sday[]" value="'+sd+'"><input type="hidden" name="longrent_eday[]" value="'+ed+'"><input type="hidden" name="longrent_percent[]" value="'+p+'"><span style="float:left">'+sd+'~'+ed+' 일까지 '+p+'% 추가</span><img src="../admin/images/btn_del.gif" alt="삭제" align="right" /></div>';
									$j('#longrentDiv').append(html);
									$j('#addLongrent_sday').val(ed+1);
									$j('#addLongrent_eday').val('');
									$j('#addLongrentPercent').val('');
								}
							}
							
						}
						</script>
						<table cellpadding="0" cellspacing="0" class="infoListTbl" style="margin-top:7px; border-bottom:0px;">
							<tr>
								<th style="width:100px;">기간</th>
								<td class="norbl" style="padding:5px;">
									<input type="text" name="addLongrent_sday" id="addLongrent_sday" value="" style="width:30px;" />~
									<input type="text" name="addLongrent_eday" id="addLongrent_eday" value="" style="width:30px;" />
									일까지
								</td>
								<th style="width:100px;">추가과금</th>
								<td style="padding:5px;">
									<input type="text" name="addLongrentPercent" id="addLongrentPercent" value="" style="width:30px;" />
									% 
								</td>
								<td>
									<input type="button" name="addLongrentBtn" value="추가" onclick="javascript:addLongrentCharge()" />
								</td>
							</tr>
						</table>
						<div style="padding:3px 0px; clear:both" id="longrentDiv">
						<? if(_array($longrentinfo)){
							foreach($longrentinfo as $k=>$v){ ?>
							<div>
								<input type="hidden" name="longrent_sday[]" value="<?=$v['sday']?>">
								<input type="hidden" name="longrent_eday[]" value="<?=$v['eday']?>">
								<input type="hidden" name="longrent_percent[]" value="<?=$v['percent']?>">
								<span style="float:left">
								<?=$v['sday']."~".$v['eday']?>
								일까지
								<?=$v['percent']?>
								% 추가</span><img src="../admin/images/btn_del.gif" alt="삭제" align="right" /></div>
							<?	}
						}?>
						<input type="hidden" name="last_eday" value="<?=$v['eday']+1?>">
						</div>
						<div style="clear:both">
							<input type="checkbox"  name="setsubsame[]" value="longrent" />
							체크시 하부카테고리 일괄 적용 </div>
						<span style="color:red; display:block">*변경 사항의 적용을 위해서는 반드시 하단의 "카테고리 수정하기" 버튼으로 저장 하셔야 합니다.</span>

					</div>
					</td>
				</tr>
				
				<TR>
					<TD colspan="2" style="height:1px" background="images/table_con_line.gif"></TD>
				</TR>

				<tr>
					<TD class="table_cell"> <img src="images/icon_point2.gif" width="8" height="11" border="0">예약 확정 방식</td>
					<TD class="td_con1">
					<input type=radio name=booking_confirm value="now" <?if($booking_confirm=="now")echo"checked";?>>결제와 동시  
					<input type=radio name=booking_confirm value="select" <?if($booking_confirm!="now")echo"checked";?>>
					<select name="booking_confirm_time">
						<option value="">선택</option>
						<option value="00:10" <?if($booking_confirm=="00:10")echo"selected";?>>10분</option>
						<option value="00:20" <?if($booking_confirm=="00:20")echo"selected";?>>20분</option>
						<option value="00:30" <?if($booking_confirm=="00:30")echo"selected";?>>30분</option>
						<? for($i=1;$i<=24;$i++){?>
						<option value="<?=sprintf('%02d',$i)?>:00" <?if($booking_confirm==sprintf('%02d',$i).":00")echo"selected";?>><?=$i?>시간</option>
						<? } ?>
					</select>
					이내 확인 알림
					</td>
				</tr>

				<TR>
					<TD colspan="2" style="height:1px" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell"> <img src="images/icon_point2.gif" width="8" height="11" border="0">중도해지시 해약 비용</td>
					<TD class="td_con1">
						<textarea name="cancel_cont" style="width:80%;height:120px"><?=$cancel_cont?></textarea>
					</td>
				</tr>
				<TR>
					<TD colspan="2" style="height:1px" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell"> <img src="images/icon_point2.gif" width="8" height="11" border="0">제휴카드 할인</td>
					<TD class="td_con1">
						<textarea name="discount_card" style="width:80%;height:50px"><?=$discount_card?></textarea>
					</td>
				</tr>
				<TR>
					<TD colspan="2" style="height:1px" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell"> <img src="images/icon_point2.gif" width="8" height="11" border="0">배송수단선택</td>
					<TD class="td_con1">
						<?php
							$deli_type_checked = array(5);
							if ($deli_type) {
								$deli_type = explode(',', $deli_type);

								if (in_array('택배', $deli_type)) { $deli_type_checked[0] = "checked='checked'"; }
								if (in_array('퀵서비스', $deli_type)) { $deli_type_checked[1] = "checked='checked'"; }
								if (in_array('방문수령', $deli_type)) { $deli_type_checked[2] = "checked='checked'"; }
								if (in_array('용달', $deli_type)) { $deli_type_checked[3] = "checked='checked'"; }
								if (in_array('장소예약', $deli_type)) { $deli_type_checked[4] = "checked='checked'"; }
							} else {
								$deli_type_checked[0] = "checked='checked'";
							}
						?>
						<input type="checkbox" name="deli_type[]" id="deli_parsel" value="택배" <?=$deli_type_checked[0]?> /><label for="deli_parsel">택배</label> <input type="checkbox" name="deli_type[]" id="deli_quick" value="퀵서비스" <?=$deli_type_checked[1]?> /><label for="deli_quick">퀵서비스</label> <input type="checkbox" name="deli_type[]" id="deli_visit" value="방문수령" <?=$deli_type_checked[2]?> /><label for="deli_visit">방문수령</label> 
						<input type="checkbox" name="deli_type[]" id="deli_car" value="용달" <?=$deli_type_checked[3]?> /><label for="deli_car">용달</label> 
						<input type="checkbox" name="deli_type[]" id="deli_place" value="장소예약" <?=$deli_type_checked[4]?> /><label for="deli_place">장소예약</label>
					</td>
				</tr>

		<TR>
			<TD colspan="2" style="height:1px" background="images/table_con_line.gif"></TD>
		</TR>
				<TR>
					<TD class="table_cell"> <img src="images/icon_point2.gif" width="8" height="11" border="0">환불 설정 </TD>
					<TD class="td_con1">
						<style type="text/css">
						#refundDiv div{ width:33%; margin-right:3px;; float:left; padding:5px; background:#f4f4f4}
						#refundDiv div img{cursor:pointer}
						</style>
						<script language="javascript" type="text/javascript">
						$j(function(){
							$j(document).on('click','#refundDiv>div>img',function(e){
								rmvRefundCommi(this);
							});
						});
						function rmvRefundCommi(el){
							$j(el).parent().remove();
						}
						function addRefundCommi(){
							var d = parseInt($j('#addRefundDay').val());
							var p = parseInt($j('#addRefundPercent').val());			
							if(isNaN(d) || d < 1){
								alert('취소일을 올바르게 입력하세요.');
								$j('#addRefundDay').focus();
							}else if(isNaN(p) || p < 1|| p>100){
								alert('수수료를 올바르게 입력하세요.');
								$j('#addRefundPercent').focus();
							}else{
								var dupvalel = null;
								$j('#refundDiv>div').each(function(idx,el){
									if($j(el).find('input[name^=refundday]').val() == String(d)){
										dupvalel = $j(el);
										return false;
									}
								});
								if(dupvalel){
									alert('중복된 일자가 있습니다. 먼저 중복 항목을 삭제후 추가 하시기 바랍니다.');				
								}else{
									html = '<div><input type="hidden" name="refundday[]" value="'+d+'"><input type="hidden" name="refundpercent[]" value="'+p+'"><span style="float:left">'+d+' 일전 '+p+'%</span><img src="images/btn_del.gif" alt="삭제" align="right" /></div>';
									$j('#refundDiv').append(html);
									$j('#addRefundDay').val('');
									$j('#addRefundPercent').val('');
								}
							}
							
						}

						function addRefundCommi2(){
							var d = parseInt($j('#addRefundDay2').val());
							var p = parseInt($j('#addRefundPercent2').val());
							if(isNaN(p) || p < 1|| p>100){
								alert('수수료를 올바르게 입력하세요.');
								$j('#addRefundPercent2').focus();
							}else{
								var dupvalel = null;
								$j('#refundDiv>div').each(function(idx,el){
									if($j(el).find('input[name^=refundday]').val() == String(d)){
										dupvalel = $j(el);
										return false;
									}
								});
								if(dupvalel){
									alert('중복된 일자가 있습니다. 먼저 중복 항목을 삭제후 추가 하시기 바랍니다.');
								}else{
									html = '<div><input type="hidden" name="refundday[]" value="'+d+'"><input type="hidden" name="refundpercent[]" value="'+p+'"><span style="float:left">당일환불(배송 전) '+p+'%</span><img src="../admin/images/btn_del.gif" alt="삭제" align="right" /></div>';
									$j('#refundDiv').prepend(html);
									$j('#addRefundDay2').val('-1');
									$j('#addRefundPercent2').val('');
								}
							}
							
						}

						function addRefundCommi3(){
							var d = parseInt($j('#addRefundDay3').val());
							var p = parseInt($j('#addRefundPercent3').val());
							if(isNaN(p) || p < 1|| p>100){
								alert('수수료를 올바르게 입력하세요.');
								$j('#addRefundPercent3').focus();
							}else{
								var dupvalel = null;
								$j('#refundDiv>div').each(function(idx,el){
									if($j(el).find('input[name^=refundday]').val() == String(d)){
										dupvalel = $j(el);
										return false;
									}
								});
								if(dupvalel){
									alert('중복된 일자가 있습니다. 먼저 중복 항목을 삭제후 추가 하시기 바랍니다.');
								}else{
									html = '<div><input type="hidden" name="refundday[]" value="'+d+'"><input type="hidden" name="refundpercent[]" value="'+p+'"><span style="float:left">당일환불(배송 후) '+p+'%</span><img src="../admin/images/btn_del.gif" alt="삭제" align="right" /></div>';
									$j('#refundDiv').prepend(html);
									$j('#addRefundDay3').val('0');
									$j('#addRefundPercent3').val('');
								}
							}
							
						}
						</script>
						<table cellpadding="0" cellspacing="0" class="infoListTbl" style="margin-top:7px; border-bottom:0px;">
							<tr>
								<th style="width:100px;">취소일</th>
								<td class="norbl" style="padding:5px;">
									<input type="text" name="addRefundDay" id="addRefundDay" value="" style="width:30px;" />
									일전</td>
								<th style="width:100px;">수수료</th>
								<td style="padding:5px;">
									<input type="text" name="addRefundPercent" id="addRefundPercent" value="" style="width:30px;" />
									% </td>
								<td>
									<input type="button" name="addRefundBtn" value="추가" onclick="javascript:addRefundCommi()" />
								</td>
							</tr>
							<tr>
								<th colspan="2" style="background:#f9f9f9">당일환불(배송 전)</th>
								<th style="background:#f9f9f9">수수료</th>
								<td style="padding:5px;">
									<input type="hidden" name="addRefundDay2" id="addRefundDay2" value="-1" />
									<input type="text" name="addRefundPercent2" id="addRefundPercent2" value="" style="width:50px;" />
									% </td>
								<td>
									<input type="button" name="addRefundBtn" value="추가" onclick="javascript:addRefundCommi2()" />
								</td>
							</tr>
							<tr>
								<th colspan="2" style="background:#f9f9f9">당일환불(배송 후)</th>
								<th style="background:#f9f9f9">수수료</th>
								<td style="padding:5px;">
									<input type="hidden" name="addRefundDay3" id="addRefundDay3" value="0" />
									<input type="text" name="addRefundPercent3" id="addRefundPercent3" value="" style="width:50px;" />
									% </td>
								<td>
									<input type="button" name="addRefundBtn" value="추가" onclick="javascript:addRefundCommi3()" />
								</td>
							</tr>
						</table>
						<? 
						$refundinfo = rentRefundCommission(pick($codeinfo->code,$parentcode));				
						?>
						<div style="width:100%; padding:3px 0px; clear:both" id="refundDiv">
							<? if(_array($refundinfo)){
							foreach($refundinfo as $rday=>$rpercent){ ?>
							<div>
								<input type="hidden" name="refundday[]" value="<?=$rday?>">
								<input type="hidden" name="refundpercent[]" value="<?=$rpercent?>">
								<span style="float:left">
								<?
								if($rday==-1){
									echo "당일환불(배송전)";
								}else if($rday==0){
									echo "당일환불(배송후)";
								}else{
									echo $rday."일전";
								}
								?>
								<?=$rpercent?>
								%</span><img src="images/btn_del.gif" alt="삭제" align="right" /></div>
							<?	}
						}?>
						</div>
						<div style="clear:both">
							<input type="checkbox"  name="setsubsame[]" value="refund" />
							체크시 하부카테고리 일괄 적용 </div>
						<span style="color:red; display:block">*변경 사항의 적용을 위해서는 반드시 하단의 "카테고리 수정하기" 버튼으로 저장 하셔야 합니다.</span>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" style="height:1px" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"> <img src="images/icon_point2.gif" width="8" height="11" border="0">장기할인 설정 </TD>
					<TD class="td_con1">
						<style type="text/css">
						#rangeDiscountDiv div{ width:33%; margin-right:3px;; float:left; padding:5px; background:#f4f4f4}
						#rangeDiscountDiv div img{cursor:pointer}
						</style>
						<script language="javascript" type="text/javascript">
						$j(function(){
							$j(document).on('click','#rangeDiscountDiv>div>img',function(e){
								rmvRangDiscount(this);
							});
						});
						function rmvRangDiscount(el){
							$j(el).parent().remove();
						}
						function addRangeDiscount(){
							var d = parseInt($j('#addRangeDiscountDay').val());
							var p = parseInt($j('#addRangeDiscountPercent').val());			
							if(isNaN(d) || d < 1){
								alert('연장 기간 올바르게 입력하세요.');
								$j('#addRangeDiscountDay').focus();
							}else if(isNaN(p) || p < 1|| p>100){
								alert('할인율을 올바르게 입력하세요.');
								$j('#addRangeDiscountPercent').focus();
							}else{
								var dupvalel = null;
								$j('#rangeDiscountDiv>div').each(function(idx,el){
									if($j(el).find('input[name^=discrangeday]').val() == String(d)){
										dupvalel = $j(el);
										return false;
									}
								});
								if(dupvalel){
									alert('중복된 일자가 있습니다. 먼저 중복 항목을 삭제후 추가 하시기 바랍니다.');	
								}else if(d<2){
									alert('장기할인은 2일 이상가능합니다.');
								}else{
									html = '<div><input type="hidden" name="discrangeday[]" value="'+d+'"><input type="hidden" name="discrangepercent[]" value="'+p+'"><span style="float:left">'+d+' 일이상 '+p+'% 할인</span><img src="images/btn_del.gif" alt="삭제" align="right" /></div>';
									$j('#rangeDiscountDiv').append(html);
									$j('#addRangeDiscountDay').val('');
									$j('#addRangeDiscountPercent').val('');
								}
							}
							
						}
						</script>
						<table cellpadding="0" cellspacing="0" class="infoListTbl" style="margin-top:7px; border-bottom:0px;">
							<tr>
								<th style="width:100px;">기간</th>
								<td class="norbl" style="padding:5px;">
									<input type="text" name="addRangeDiscountDay" id="addRangeDiscountDay" value="" style="width:30px;" />
									일이상</td>
								<th style="width:100px;">할인율</th>
								<td style="padding:5px;">
									<input type="text" name="addRangeDiscountPercent" id="addRangeDiscountPercent" value="" style="width:30px;" />
									% </td>
								<td>
									<input type="button" name="addRangeDiscountBtn" value="추가" onclick="javascript:addRangeDiscount()" />
							</tr>
						</table>
						<? 
						$ldiscinfo = rentLongDiscount(pick($codeinfo->code,$parentcode));
						 ?>
						<div style="width:100%; padding:3px 0px; clear:both" id="rangeDiscountDiv">
							<? if(_array($ldiscinfo)){
							foreach($ldiscinfo as $dday=>$dpercent){ ?>
							<div>
								<input type="hidden" name="discrangeday[]" value="<?=$dday?>">
								<input type="hidden" name="discrangepercent[]" value="<?=$dpercent?>">
								<span style="float:left">
								<?=$dday?>
								일이상
								<?=$dpercent?>
								%할인</span><img src="images/btn_del.gif" alt="삭제" align="right" /></div>
							<?	}
						}?>
						</div>
						<div style="clear:both">
							<input type="checkbox"  name="setsubsame[]" value="longdiscount" />
							체크시 하부카테고리 일괄 적용 </div>
						<span style="color:red; display:block">*변경 사항의 적용을 위해서는 반드시 하단의 "카테고리 수정하기" 버튼으로 저장 하셔야 합니다.</span>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" style="height:1px" background="images/table_con_line.gif"></TD>
				</TR>
<!--
				<TR>
					<TD class="table_cell"> <img src="images/icon_point2.gif" width="8" height="11" border="0" />추천인 적립</TD>
					<TD class="td_con1">
						적립률<input type="text" value="<?=$codeinfo->reseller_reserve*100?>" name="reseller_reserve" style="margin-left:10px; width:30px;" />%<br />					
						<input type="checkbox"  name="setsubsame[]" value="reseller_reserve" />
						체크시 하부카테고리 일괄 적용<span style="color:red; display:block">*변경 사항의 적용을 위해서는 반드시 하단의 "카테고리 수정하기" 버튼으로 저장 하셔야 합니다.</span> </TD>		
					</TD>
				</TR>
				<TR>
					<TD colspan="2" style="height:1px" background="images/table_con_line.gif"></TD>
				</TR>
-->				
				
						
				<TR>
					<TD class="table_cell"> <img src="images/icon_point2.gif" width="8" height="11" border="0">성수기사용 </TD>
					<TD class="td_con1">
					<? //  echo _pr($codeinfo); ?>
						<input type="radio" name="useseason" onclick="javascript:toggleSeasonList();" id="notUseSeason" value="0" <? if($codeinfo->useseason != '1') echo ' checked="checked"'; ?> />
						<label for="notUseSeason">사용안함</label>
						<input type="radio" style="margin-left:10px;" name="useseason" onclick="javascript:toggleSeasonList();" id="UseSeason" value="1" <? if($codeinfo->useseason == '1') echo ' checked="checked"'; ?> />
						<label for="UseSeason">성수기/비성수기사용</label>
						<div id="seasonDiv" style="border:1px solid #efefefe"> 
							<script language="javascript" type="text/javascript">
							function toggleSeasonList(){
								var f = document.form1;
								var listdisp = false;
								for(i=0;i<f.useseason.length;i++){
									if(f.useseason[i].checked){
										if(f.useseason[i].value == '1') listdisp = true;
										break;
									}
								}
								document.getElementById('seasonListTbl').style.display = (listdisp)?'block':'none';
							}
							</script>
							<? if($mode!="modify"){ ?>
							<div id="seasonListTbl" style="font-weight:bold; padding:10px 0px;"> 기간 설정 등은 카테고리 등록후(카테고리 고유 번호생성후) 편집 기능을 통해 등록 가능합니다.</div>
							<? }else{ ?>
							<table cellpadding="0" cellspacing="0" width="100%" id="seasonListTbl" class="infoListTbl" style="margin-top:7px;">
								
										</tr>
								
								
									<th style="width:120px;">성수기/준성수기</th>
									<td class="norbl" style="padding:5px;">
										<input type="button" value="성수기/준성수기 관리" style="width:200px;" onclick="window.open('product_seasonpop.php?code=<?=$code?>', 'busySeasonPop', 'width=800,height=600' );">
									</td>
								</tr>
								<tr>
									<th class="nobbl">공휴일/주말</th>
									<td style="padding:5px;" class="norbl nobbl">
										<input type="button" value="공휴일/주말 관리" style="width:200px;"  onclick="window.open('product_holiday.php?code=<?=$code?>', 'holidayPop', 'width=800,height=600' );">
										(* 공통휴일은 예약/렌탈관리 > 휴일관리를 이용) </td>
								</tr>
							</table>
							<!-- 전체 설정 끝 -->
							<? } ?>
						</div>
						<input type="checkbox"  name="setsubsame[]" value="season" />
						체크시 하부카테고리 일괄 적용<span style="color:red; display:block">*변경 사항의 적용을 위해서는 반드시 하단의 "카테고리 수정하기" 버튼으로 저장 하셔야 합니다.</span> </TD>		
						
				</TR>		
				<TR>
					<TD colspan="2" style="height:1px" background="images/table_con_line.gif"></TD>
				</TR>
				
				
				
				
				
				
		<? } ?>
		<? //메인페이지 배너 관려 jdy ( remake:x2chi ) ?>
		<? // if ($mode=="modify" && $codeA !='000' && $codeB=='000' && $codeC=='000' && $codeD=='000') { ?>
		<? if ($mode=="insert" && !$parentcode || $mode=="modify" && $codeA !='000' && $codeB=='000' && $codeC=='000' && $codeD=='000') { ?>
		<TR>
			<TD class="table_cell"> <img src="images/icon_point2.gif" width="8" height="11" border="0">메뉴 레이어 배너 </TD>
			<TD class="td_con1" style="padding:0px;">
				<table cellpadding="0" cellspacing="0" width="100%" height="100%" bgcolor="#ffffff">
					<tr>
						<td class="table_cell">배너 이미지</td>
						<td class="td_con1">
							<?
								if ($banner_img) {
									echo $banner_img;
									echo "&nbsp;&nbsp;<img src=images/btn_del.gif style=\"cursor:pointer;\" alt=\"삭제\" onclick=\"deleteBannerImg()\">";
								}else{
									echo "등록된 배너가 없습니다.";
								}
							?>
						</td>
					</tr>
					<TR>
						<TD colspan="2" style="height:1px" background="images/table_con_line.gif"></TD>
					</TR>
					<tr>
						<td class="table_cell"><strong>파일 첨부</strong></td>
						<td class="td_con1">
							<input type=file name="up_banner_file" style="WIDTH: 400px" class="input">
							<span class="font_orange">(권장이미지 : 200X85)</span><br />
						</td>
					</tr>
					<TR>
						<TD colspan="2" style="height:1px" background="images/table_con_line.gif"></TD>
					</TR>
					<tr>
						<td class="table_cell"><strong>링크 URL</strong></td>
						<td class="td_con1">http://
							<input type=text name=up_banner_url maxlength=100 value="<?=$up_banner_url?>" class="input" style="width:80%;">
						</td>
					</tr>
					<TR>
						<TD colspan="2" style="height:1px" background="images/table_con_line.gif"></TD>
					</TR>
					<tr>
						<td class="table_cell"><strong>링크 방법</strong></td>
						<td class="td_con1">
							<input type=radio id="up_move_type1" name=up_move_type value="0" <? if(($up_move_type=="0" || strlen($up_move_type)==0)) echo " checked"?>>
							<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=up_move_type1>같은창으로 열기</label>
							<input type=radio id="up_move_type2" name=up_move_type value="1" <? if($up_move_type=="1") echo " checked"?>>
							<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=up_move_type2>새창으로 열기</label>
						</td>
					</tr>
					<TR>
						<TD colspan="2" style="height:1px" background="images/table_con_line.gif"></TD>
					</TR>
				</table>
			</TD>
		</TR>
		<TR>
			<TD colspan="2" style="height:1px" background="images/table_con_line.gif"></TD>
		</TR>
		<? } ?>
		<? //메인페이지 배너 관련 jdy ?>
		<? // 지식 쇼핑 연동 설정 관련 추가
	if(false !== $naverep = checkNaverEp()){
		if(!_empty($naverep['shopping'])){ ?>
		<TR>
			<TD class="table_cell"> <img src="images/icon_point2.gif" width="8" height="11" border="0">네이버 지식쇼핑 연동 </TD>
			<TD class="td_con1">
				<input type="checkbox" name="syncNaverEp" value="0" <?=(($syncNaverEp=='0')?'checked':'')?> />
				네이버 지식 쇼핑 연동시 해당 카테고리를 제외 합니다.<br />
				<span style="color:red">기존 하위 카테고리및 상품 전체의 상태를 변경 하게 됩니다.</span> </TD>
		</TR>
		<TR>
			<TD colspan="2" style="height:1px" background="images/table_con_line.gif"></TD>
		</TR>
		<? }
	}
	?>
		<TR>
			<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">접근가능 회원등급</TD>
			<TD class="td_con1">
				<select name=up_group_code style="width:100%" <?if($group_code=="NO") echo "disabled";?> class="select" onChange="javascript:chGroupCode(this.options[this.selectedIndex].value)">
					<?
		$gcode_array = array("","ALL");
		$gname_array = array("모든사람 접근가능","쇼핑몰 회원만 접근가능");
		$num=2;
		if($group_code!="") $group_code1 = "ALL";
		else $group_code1 = "";
		for($i=0;$i<$num;$i++){
			echo "<option value=\"".$gcode_array[$i]."\"";
			if($group_code1==$gcode_array[$i]) echo " selected";
			echo ">".$gname_array[$i]."</option>\n";
		}
?>
				</select>
				<span id=gcode_layer <?=($group_code)?"":"style=\"display:none;\"";?>>
				<?
		//접근 가능회원 복수로 선택되게 수정
		$gcode_array = array();
		$gname_array = array();
		$sql = "SELECT group_code,group_name FROM tblmembergroup ";
		$result = mysql_query($sql,get_db_conn());
		$num=0;
		while($row = mysql_fetch_object($result)){
			$gcode_array[$num]=$row->group_code;
			$gname_array[$num++]=$row->group_name;
		}
		mysql_free_result($result);
		for($i=0;$i<$num;$i++){
			echo "<input type=\"checkbox\" name=\"arr_group_code[]\" id=\"up_group_code2\" value=\"".$gcode_array[$i]."\"";
			if(strpos($group_code,$gcode_array[$i])!==false) echo " checked";
			if( $group_code1 == "" ) echo " checked";
			echo ">".$gname_array[$i]."\n";
		}
?>
				</span> </TD>
		</TR>
		<TR>
			<TD colspan="2" style="height:1px" background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">상품정렬</TD>
			<TD class="td_con1">
				<select name=up_sort style="width:100%" class="select">
					<option <? if ($sort=="date" OR $sort=="" ) echo "selected "; ?> value="date">상품 등록/수정날짜 순서</option>
					<option <? if ($sort=="date2") echo "selected "; ?> value="date2">상품 등록/수정날짜 순서 + 품절상품 뒤로</option>
					<option <? if ($sort=="productname") echo "selected "; ?> value="productname">상품명 가나다 순서</option>
					<option <? if ($sort=="production") echo "selected "; ?> value="production">제조사 가나다 순서</option>
					<option <? if ($sort=="price") echo "selected "; ?> value="price">상품 판매가격 순서</option>
				</select>
			</TD>
		</tr>
		<TR>
			<TD colspan="2" style="height:1px" background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">카테고리 상품진열</TD>
			<TD class="td_con1">
				<input type=checkbox id="idx_special1" name=tmp_special value="1" <?if($special["1"]=="Y")echo"checked";?> style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none">
				<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_special1>신규상품</label>
				- <FONT COLOR="red">진열타입 선택 :</FONT>
				<select name=up_special_1_type class="select">
					<option value="I" <?if($special_1_type=="I")echo"selected";?>>이미지 A형</option>
					<option value="D" <?if($special_1_type=="D")echo"selected";?>>이미지 B형</option>
					<option value="L" <?if($special_1_type=="L")echo"selected";?>>리스트형</option>
				</select>
				<br>
				<img width=0 height=2><br>
				<img width=87 height=0><FONT COLOR="red">라인별 상품수 :</FONT>
				<select name=up_special_1_cols class="select">
					<option value="1" <?if($special_1_cols==1)echo"selected";?>>1</option>
					<option value="2" <?if($special_1_cols==2)echo"selected";?>>2</option>
					<option value="3" <?if($special_1_cols==3)echo"selected";?>>3</option>
					<option value="4" <?if($special_1_cols==4)echo"selected";?>>4</option>
					<option value="5" <?if($special_1_cols==5)echo"selected";?>>5</option>
					<option value="6" <?if($special_1_cols==6)echo"selected";?>>6</option>
					<option value="7" <?if($special_1_cols==7)echo"selected";?>>7</option>
					<option value="8" <?if($special_1_cols==8)echo"selected";?>>8</option>
				</select>
				&nbsp; <FONT COLOR="red">줄수 :</FONT>
				<select name=up_special_1_rows class="select">
					<option value="1" <?if($special_1_rows==1)echo"selected";?>>1</option>
					<option value="2" <?if($special_1_rows==2)echo"selected";?>>2</option>
					<option value="3" <?if($special_1_rows==3)echo"selected";?>>3</option>
					<option value="4" <?if($special_1_rows==4)echo"selected";?>>4</option>
					<option value="5" <?if($special_1_rows==5)echo"selected";?>>5</option>
				</select>
				<br>
				<img width=0 height=7><br>
				<input type=checkbox id="idx_special0" name=tmp_special value="2" <?if($special["2"]=="Y")echo"checked";?> style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none">
				<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_special0>인기상품</label>
				- <FONT COLOR="red">진열타입 선택 :</FONT>
				<select name=up_special_2_type class="select">
					<option value="I" <?if($special_2_type=="I")echo"selected";?>>이미지 A형</option>
					<option value="D" <?if($special_2_type=="D")echo"selected";?>>이미지 B형</option>
					<option value="L" <?if($special_2_type=="L")echo"selected";?>>리스트형</option>
				</select>
				<br>
				<img width=0 height=2><br>
				<img width=87 height=0><FONT COLOR="red">라인별 상품수 :</FONT>
				<select name=up_special_2_cols class="select">
					<option value="1" <?if($special_2_cols==1)echo"selected";?>>1</option>
					<option value="2" <?if($special_2_cols==2)echo"selected";?>>2</option>
					<option value="3" <?if($special_2_cols==3)echo"selected";?>>3</option>
					<option value="4" <?if($special_2_cols==4)echo"selected";?>>4</option>
					<option value="5" <?if($special_2_cols==5)echo"selected";?>>5</option>
					<option value="6" <?if($special_2_cols==6)echo"selected";?>>6</option>
					<option value="7" <?if($special_2_cols==7)echo"selected";?>>7</option>
					<option value="8" <?if($special_2_cols==8)echo"selected";?>>8</option>
				</select>
				&nbsp; <FONT COLOR="red">줄수 :</FONT>
				<select name=up_special_2_rows class="select">
					<option value="1" <?if($special_2_rows==1)echo"selected";?>>1</option>
					<option value="2" <?if($special_2_rows==2)echo"selected";?>>2</option>
					<option value="3" <?if($special_2_rows==3)echo"selected";?>>3</option>
					<option value="4" <?if($special_2_rows==4)echo"selected";?>>4</option>
					<option value="5" <?if($special_2_rows==5)echo"selected";?>>5</option>
				</select>
				<br>
				<img width=0 height=2><br>
				<input type=checkbox id="idx_special2" name=tmp_special value="3" <?if($special["3"]=="Y")echo"checked";?> style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none">
				<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_special2>추천상품</label>
				- <FONT COLOR="red">진열타입 선택 :</FONT>
				<select name=up_special_3_type class="select">
					<option value="I" <?if($special_3_type=="I")echo"selected";?>>이미지 A형</option>
					<option value="D" <?if($special_3_type=="D")echo"selected";?>>이미지 B형</option>
					<option value="L" <?if($special_3_type=="L")echo"selected";?>>리스트형</option>
				</select>
				<br>
				<img width=0 height=2><br>
				<img width=87 height=0><FONT COLOR="red">라인별 상품수 :</FONT>
				<select name=up_special_3_cols class="select">
					<option value="1" <?if($special_3_cols==1)echo"selected";?>>1</option>
					<option value="2" <?if($special_3_cols==2)echo"selected";?>>2</option>
					<option value="3" <?if($special_3_cols==3)echo"selected";?>>3</option>
					<option value="4" <?if($special_3_cols==4)echo"selected";?>>4</option>
					<option value="5" <?if($special_3_cols==5)echo"selected";?>>5</option>
					<option value="6" <?if($special_3_cols==6)echo"selected";?>>6</option>
					<option value="7" <?if($special_3_cols==7)echo"selected";?>>7</option>
					<option value="8" <?if($special_3_cols==8)echo"selected";?>>8</option>
				</select>
				&nbsp; <FONT COLOR="red">줄수 :</FONT>
				<select name=up_special_3_rows class="select">
					<option value="1" <?if($special_3_rows==1)echo"selected";?>>1</option>
					<option value="2" <?if($special_3_rows==2)echo"selected";?>>2</option>
					<option value="3" <?if($special_3_rows==3)echo"selected";?>>3</option>
					<option value="4" <?if($special_3_rows==4)echo"selected";?>>4</option>
					<option value="5" <?if($special_3_rows==5)echo"selected";?>>5</option>
				</select>
				<br>
				<input type=checkbox id="idx_islist" name=up_islist value="Y" <?if($islist=="Y")echo"checked";?> style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none">
				<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_islist>카테고리상품목록</label>
				<br>
				<span class="font_orange" style="letter-spacing:-0.5pt;FONT-SIZE:11px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;* 진열수 : <a href="shop_mainproduct.php" target="_parent"><span class="font_blue" style="letter-spacing:-0.5pt;FONT-SIZE:11px;">상점관리 > 쇼핑몰 환경 설정 > 상품 진열수/화면설정</span></a>.</span> </TD>
		</tr>
		<TR>
			<TD colspan="2" style="height:1px" background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">카테고리 숨김여부</TD>
			<TD class="td_con1">
				<input type=checkbox id="idx_code_hide1" name=up_code_hide value="NO" <? if($group_code=="NO") echo "checked";?> onclick="GroupCheck(this.checked)">
				<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_code_hide1>이 상품카테고리(카테고리) 숨기기</label>
			</TD>
		</tr>
		<TR>
			<TD colspan="2" style="height:1px" background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">카테고리 옵션</TD>
			<TD class="td_con1"> 사은품
				<select name="up_isgift" style="margin-right:10px;">
					<option value="">---</option>
					<option value="N" <? if($isgift=="N") echo "selected";?>>적용불가</option>
					<option value="Y" <? if($isgift=="Y") echo "selected";?>>적용가능</option>
				</select>
				쿠폰
				<select name="up_iscoupon" style="margin-right:10px;">
					<option value="">---</option>
					<option value="N" <? if($iscoupon=="N") echo "selected";?>>적용불가</option>
					<option value="Y" <? if($iscoupon=="Y") echo "selected";?>>적용가능</option>
				</select>
				교환 및 환불
				<select name="up_isrefund" style="margin-right:10px;">
					<option value="">---</option>
					<option value="N" <? if($isrefund=="N") echo "selected";?>>불가</option>
					<option value="Y" <? if($isrefund=="Y") echo "selected";?>>가능</option>
				</select>
				적립금
				<select name="up_isreserve" style="margin-right:10px;">
					<option value="">---</option>
					<option value="N" <? if($isreserve=="N") echo "selected";?>>사용불가</option>
					<option value="Y" <? if($isreserve=="Y") echo "selected";?>>사용가능</option>
				</select>
				<? /*
			사은품 <select name="up_isgift" style="margin-right:10px;"><option value="">---</option><option value="Y">적용불가</option><option value="N" >적용가능</option></select>
			쿠폰 <select name="up_iscoupon" style="margin-right:10px;"><option value="">---</option><option value="Y">적용불가</option><option value="N">적용가능</option></select>
			교환 및 환불 <select name="up_isrefund" style="margin-right:10px;"><option value="">---</option><option value="Y">불가</option><option value="N" >가능</option></select>
			적립금 <select name="up_isreserve" style="margin-right:10px;"><option value="">---</option><option value="Y">사용불가</option><option value="N">사용가능</option></select>
			*/ ?>
			</TD>
		</tr>
		<TR>
			<TD colspan="2" style="height:1px" background="images/table_con_line.gif"></TD>
		</TR>

		<tr>
			<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">검색 키워드 관리</TD>
			<TD class="td_con1">
				
				<div class="div_kw">
					<select name="kw_group" id="kw_group" onchange="javascript:addKwSelect(this.value,this.options[this.selectedIndex].text)">
						<option value="">키워드분류를 선택하세요</option>
						<?
						$kwsql = "SELECT * FROM tblkwgroup ";
						$kwres = mysql_query($kwsql,get_db_conn());
						while ($kwrow = mysql_fetch_object($kwres)) {
							echo "<option value=\"".$kwrow->kg_idx."\">".$kwrow->kwgroup."</option>";
						}
						?>
					</select>
					<button type="button" onclick="addKwGroup()">추가</button> 
					<button type="button" onclick="modifyKwGroup()" style="margin-left:5px">관리</button>
				</div>
				<div class="div_kw2" style="display:none">
					<input type="text" name="kwgroup" id="kwgroup">
					<button type="button" onclick="addKwSend()">확인</button>
					<button type="button" onclick="addKwCancel()">취소</button>
				</div>

				<div class="kw_view">
					<ul>
						<li>
							<span>사용</span>
							<span>분류</span>
							<span>검색키워드</span>
						</li>
						<?
						if($mode=="modify"){
							$kw_code=$code;
						}else{
							$kw_code=$parentcode;
						}

						$ksql = "SELECT kw.kg_idx,kwgroup,use_yn ";
						$ksql.= "FROM tblkeyword kw LEFT JOIN tblkwgroup kg ON kw.kg_idx=kg.kg_idx ";
						$ksql.= "WHERE code='".$kw_code."' AND productcode='' GROUP BY kw.kg_idx";
						$kres = mysql_query($ksql,get_db_conn());

						while($krow = mysql_fetch_object($kres)){
							echo "<li>";
							echo "<input type=\"hidden\" name=\"kg_idx[]\" value=\"".$krow->kg_idx."\"></span>";
							echo "<span><input type=\"checkbox\" name=\"".$krow->kg_idx."_useyn\" value=\"Y\" ";
							if ($krow->use_yn=="Y") echo "checked"; else echo "";
							echo ">";
							echo "</span>";
							echo "<span style=\"padding:5px;font-weight:bold\">".$krow->kwgroup."</span>";
							
							$ksql2 = "SELECT keyword FROM tblkeyword ";
							$ksql2.= "WHERE code='".$kw_code."' AND productcode='' AND kg_idx='".$krow->kg_idx."' ORDER BY kw_idx";
							$kres2 = mysql_query($ksql2,get_db_conn());

							while($krow2 = mysql_fetch_object($kres2)){
								echo "<span>";
								echo $krow2->keyword;
								echo "<input type=\"hidden\" name=\"".$krow->kg_idx."_kw[]\" value=\"".$krow2->keyword."\">";
								echo "<button type=\"button\" onclick=\"delKwText(this)\" style=\"margin:2px;\">x</button> ";
								echo "</span>";
							}

							echo "<span id=\"".$krow->kg_idx."addDiv\">";
							echo "<button type=\"button\" onclick=\"addKwText('".$krow->kg_idx."')\">추가</button>";
							echo "</span>";
							echo "<span id=\"".$krow->kg_idx."addDiv2\" style=\"display:none\">";
							echo "<input type=\"text\" id=\"".$krow->kg_idx."_kw_text\" name=\"".$krow->kg_idx."_kw_text\" placeholder=\"키워드를 입력하세요.\">";
							echo "<button type=\"button\" onclick=\"insertKwText('".$krow->kg_idx."')\">확인</button>";
							echo "<button type=\"button\" onclick=\"cancelKwText('".$krow->kg_idx."')\">취소</button>";
							echo "</span>";
							echo "</li>";
						}
						?>
					</ul>
				</div>
			</TD>
		</tr>

		<?
if ($type1!="S"){
?>
		<tr>
			<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">상품진열 템플릿 선택</TD>
			<TD class="td_con1">
				<?
		if(strlen(str_replace('0','',substr(trim($parentcode),0,3))) >= 1 || strlen(str_replace('0','',substr(trim($code),0,6))) >=2){ ?>
				<div style="padding:3px; background:#efefef">
					<input type="checkbox" name="dsameparent" value="1" <? if($dsameparent == 1){ ?> checked="checked" <? } ?> onclick="checkDesignSame(this)" />
					부모 디자인과 동일
					<?=$dsameparent?>
				</div>
				<? } ?>
				<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="397">
							<input type=radio id="idx_gong1" name=gong value="N" <? if(($gong=="N" || strlen($gong)==0) && $dsameparent != '1') echo " checked"?> onclick="DesignMsg(0)">
							<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_gong1>상품 진열 및 상품상세 디자인(일반형)</label>
						</td>
					</tr>
					<tr>
						<td width="100%" style="padding-left:13pt;">
							<table cellpadding="0" cellspacing="0" width="97%">
								<col width=50%>
								
										</col>
								
								<col width=50%>
								
										</col>
								
								<tr>
									<td align=center><a href="javascript:DesignList(0);"><img src="images/product_displaylist1.gif" width="158" height="16" border="0"></a></td>
									<td align=center><a href="javascript:DesignDetail(0);"><img src="images/product_displaydetail1.gif" width="158" height="16" border="0"></a></td>
								</tr>
								<?if($gong == "N" && $list_type!="" && $detail_type!="") {?>
								<tr>
									<td align=center valign="top" style="padding-top:3pt;"><a href="javascript:DesignList(0);"><img src="images/product/<?=$list_type?>.gif" width="150" height="160" border="0" style="border-width:1pt; border-color:rgb(222,222,222); border-style:solid;"></a></td>
									<td align=center valign="top" style="padding-top:3pt;"><a href="javascript:DesignDetail(0);"><img src="images/product/<?=$detail_type?>.gif" width="150" height="160" border="0" style="border-width:1pt; border-color:rgb(222,222,222); border-style:solid;"></a></td>
								</tr>
								<? } else { ?>
								<tr>
									<td align=center valign="top" style="padding-top:3pt;"><a href="javascript:DesignList(0);"><img src="images/ex1.gif" width="150" height="160" border="0" style="border-width:1pt; border-color:rgb(222,222,222); border-style:solid;"></a></td>
									<td align=center valign="top" style="padding-top:3pt;"><a href="javascript:DesignDetail(0);"><img src="images/ex2.gif" width="150" height="160" border="0" style="border-width:1pt; border-color:rgb(222,222,222); border-style:solid;"></a></td>
								</tr>
								<? } ?>
							</table>
						</td>
					</tr>
					<tr>
						<td height=10></td>
					</tr>
					<tr>
						<td width="100%">
							<input type=radio id="idx_gong2" name=gong value="Y" <? if($gong=="Y" && $dsameparent != '1') echo " checked"?> onclick="DesignMsg(1)">
							<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_gong2>가격고정형 공동구매 디자인(공구형)</label>
						</td>
					</tr>
					<tr>
						<td width="100%" style="padding-left:13pt;">
							<table cellpadding="0" cellspacing="0" width="97%">
								<col width=50%>
								
										</col>
								
								<col width=50%>
								
										</col>
								
								<tr>
									<td align=center><a href="javascript:DesignList(1);"><img src="images/product_displaylist2.gif" width="158" height="16" border="0"></a></td>
									<td align=center><a href="javascript:DesignDetail(1);"><img src="images/product_displaydetail2.gif" width="158" height="16" border="0"></a></td>
								</tr>
								<?if($gong == "Y" && $list_type!="" && $detail_type!="") {?>
								<tr>
									<td align=center valign="top" style="padding-top:3pt;"><a href="javascript:DesignList(1);"><img src="images/product/<?=$list_type?>.gif" width="150" height="160" border="0" style="border-width:1pt; border-color:rgb(222,222,222); border-style:solid;"></a></td>
									<td align=center valign="top" style="padding-top:3pt;"><a href="javascript:DesignDetail(1);"><img src="images/product/<?=$detail_type?>.gif" width="150" height="160" border="0" style="border-width:1pt; border-color:rgb(222,222,222); border-style:solid;"></a></td>
								</tr>
								<? } else { ?>
								<tr>
									<td align=center valign="top" style="padding-top:3pt;"><a href="javascript:DesignList(1);"><img src="images/ex3.gif" width="150" height="160" border="0" style="border-width:1pt; border-color:rgb(222,222,222); border-style:solid;"></a></td>
									<td align=center valign="top" style="padding-top:3pt;"><a href="javascript:DesignDetail(1);"><img src="images/ex4.gif" width="150" height="160" border="0" style="border-width:1pt; border-color:rgb(222,222,222); border-style:solid;"></a></td>
								</tr>
								<? } ?>
							</table>
						</td>
					</tr>
				</table>
			</TD>
		</tr>
		<? }?>
		<? if($mode=="modify"){?>
		<tr>
			<TD align="center" colspan="2">
				<div id=child_layer style="position:absolute;z-index:100;left:0;bottom:45;width:270px;visibility:hidden;">
					<table border=0 cellspacing=1 cellpadding=0 width=270 bgcolor=#000000>
						<tr>
							<td bgcolor=#FFFFFF>
								<table border=0 cellpadding=3 width=100%>
									<col width=50%>
									
											</col>
									
									<col width=50%>
									
											</col>
									
									<tr>
										<td valign="top">
											<input type=checkbox id="idx_isgcode" name="is_gcode" value="1" style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none">
											<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_isgcode>접근가능 회원등급</label>
											<br>
											<input type=checkbox id="idx_issort" name="is_sort" value="1" style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none">
											<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_issort>상품정렬</label>
										</td>
										<td valign="top">
											<input type=checkbox id="idx_isdesign" name="is_design" value="1" style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none">
											<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_isdesign>상품진열 디자인</label>
											<br>
											<input type=checkbox id="idx_isspecial" name="is_special" value="1" style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none">
											<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_isspecial>카테고리 진열상품</label>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</div>
			</TD>
		</tr>
		<?}?>
		<TR>
			<TD colspan="2" style="height:1px" background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<td colspan="2" height="10"></td>
		</tr>
		<?if($mode=="insert"){?>
		<TR>
			<TD colspan="2" align="center"><a href="javascript:Save()"><img src="images/botteon_add.gif" width="137" height="38" border="0" hspace="0"></a></TD>
		</TR>
		<?}else if($mode=="modify"){?>
		<TR>
			<TD colspan="2" align="center">
			
				<? if(false && !ereg("X",$type)){?>
				<a href="javascript:ChildCodeClick();"><img src="images/botteon_downallapply.gif" width="118" height="38" border="0" hspace="0"></a>&nbsp;
				<? }?>
				<a href="javascript:parent.NewCode();"><img src="images/botteon_newadd.gif" width="118" height="38" border="0" hspace="0"></a>&nbsp; <a href="javascript:Save();"><img src="images/botteon_catemodify.gif" width="118" height="38" border="0" hspace="0"></a>&nbsp; <a href="javascript:CodeDelete();"><img src="images/botteon_catedelete.gif" width="118" height="38" border="0" hspace="0"></a>&nbsp; </TD>
		</TR>
		<? } ?>
		<tr>
			<td colspan="2" height="10"></td>
		</tr>
	</TABLE>
	
			</td>
	
	
			</tr>
	
</table>
</form>
<form name=form2 action="<?=$_SERVER[PHP_SELF]?>" method=post>
	<input type=hidden name=mode>
	<input type=hidden name=code>
</form>
<div style="height:200px;"></div>
<?=$onload?>
<script type="text/javascript">
<!--
	toggleSeasonList();
	parent.autoResize('PropertyFrame');
	
//-->
</script>
</body></html>