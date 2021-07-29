<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");
INCLUDE ("access.php");
include_once($Dir."lib/admin_more.php");

$mode=$_POST["mode"];

// 정산 기준 조회 jdy
$shop_more_info = getShopMoreInfo();
$account_rule = $shop_more_info['account_rule'];
// 정산 기준 조회 jdy

/* 수수료 관련 추가 jdy */
$sql = "SELECT * FROM vender_more_info ";
$sql.= "WHERE vender='".$_VenderInfo->getVidx()."'";

$result=mysql_query($sql,get_db_conn());
$_vmdata=mysql_fetch_object($result);
mysql_free_result($result);
/* 수수료 관련 추가 jdy */


/* 과금방식 추가 gura */
$sql = "SELECT * FROM vender_rent ";
$sql.= "WHERE vender='".$_VenderInfo->getVidx()."' and pridx=0";

$result=mysql_query($sql,get_db_conn());
$_ptdata=mysql_fetch_object($result);
mysql_free_result($result);
/* 과금방식 추가 gura */

if($mode=="update") {
	$up_com_owner=$_POST["up_com_owner"];
	$up_com_post1=$_POST["up_com_post1"];
	$up_com_post2=$_POST["up_com_post2"];
	$up_com_addr=$_POST["up_com_addr"];
	$up_com_biz=$_POST["up_com_biz"];
	$up_com_item=$_POST["up_com_item"];
	$up_com_tel1=$_POST["up_com_tel1"];
	$up_com_tel2=$_POST["up_com_tel2"];
	$up_com_tel3=$_POST["up_com_tel3"];
	$up_com_fax1=$_POST["up_com_fax1"];
	$up_com_fax2=$_POST["up_com_fax2"];
	$up_com_fax3=$_POST["up_com_fax3"];
	$up_com_homepage=strtolower($_POST["up_com_homepage"]);

	$up_p_name=$_POST["up_p_name"];
	$up_p_mobile1=$_POST["up_p_mobile1"];
	$up_p_mobile2=$_POST["up_p_mobile2"];
	$up_p_mobile3=$_POST["up_p_mobile3"];
	$up_p_email=$_POST["up_p_email"];
	$up_p_buseo=$_POST["up_p_buseo"];
	$up_p_level=$_POST["up_p_level"];

	$up_passwd=$_POST["up_passwd"];

	$up_bank1=$_POST["up_bank1"];
	$up_bank2=$_POST["up_bank2"];
	$up_bank3=$_POST["up_bank3"];

	$up_session=$_POST["up_session"];

	$com_type=$_POST["com_type"];
	$ec_num=$_POST["ec_num"];
	$com_nametech=$_POST["com_nametech"];

	$price=$_POST["price"];
	$refund=$_POST["refund"];
	$longdiscount=$_POST["longdiscount"];
	$season=$_POST["season"];

	$reserve=$_POST["reserve"];
	$reseller_reserve=$_POST["reseller_reserve"];
	$booking_confirm=$_POST["booking_confirm"]=="now"?$_POST["booking_confirm"]:$_POST["booking_confirm_time"];

	$up_com_post="";
	$up_com_post=$up_com_post1.$up_com_post2;
	/*if(strlen($up_com_post1)==3 && strlen($up_com_post2)==3) {
		$up_com_post=$up_com_post1.$up_com_post2;
	}*/

	$up_com_tel="";
	$up_com_fax="";
	$up_p_mobile="";
	if(strlen($up_com_tel1)>0 && strlen($up_com_tel2)>0 && strlen($up_com_tel3)>0) {
		if(IsNumeric($up_com_tel1) && IsNumeric($up_com_tel2) && IsNumeric($up_com_tel3)) {
			$up_com_tel=$up_com_tel1."-".$up_com_tel2."-".$up_com_tel3;
		}
	}
	if(strlen($up_com_fax1)>0 && strlen($up_com_fax2)>0 && strlen($up_com_fax3)>0) {
		if(IsNumeric($up_com_fax1) && IsNumeric($up_com_fax2) && IsNumeric($up_com_fax3)) {
			$up_com_fax=$up_com_fax1."-".$up_com_fax2."-".$up_com_fax3;
		}
	}
	if(strlen($up_p_mobile1)>0 && strlen($up_p_mobile2)>0 && strlen($up_p_mobile3)>0) {
		if(IsNumeric($up_p_mobile1) && IsNumeric($up_p_mobile2) && IsNumeric($up_p_mobile3)) {
			$up_p_mobile=$up_p_mobile1."-".$up_p_mobile2."-".$up_p_mobile3;
		}
	}
	if(!ismail($up_p_email)) {
		$up_p_email="";
	}
	$up_com_homepage=str_replace("http://","",$up_com_homepage);

	$bank_account="";
	if(strlen($up_bank1)>0 && strlen($up_bank2)>0 && strlen($up_bank3)>0) {
		$bank_account=$up_bank1."=".$up_bank2."=".$up_bank3;
	}

	if(strlen($up_com_owner)==0) {
		echo "<html></head><body onload=\"alert('대표자 성명을 정확히 입력하세요.')\"></body></html>";exit;
	} else if(strlen($up_com_post)==0 || strlen($up_com_addr)==0) {
		echo "<html></head><body onload=\"alert('사업장 주소를 정확히 입력하세요.')\"></body></html>";exit;
	} else if(strlen($up_com_biz)==0) {
		echo "<html></head><body onload=\"alert('사업자 업태를 정확히 입력하세요.')\"></body></html>";exit;
	} else if(strlen($up_com_item)==0) {
		echo "<html></head><body onload=\"alert('사업자 종목을 정확히 입력하세요.')\"></body></html>";exit;
	} else if(strlen($up_com_tel)==0) {
		echo "<html></head><body onload=\"alert('회사 대표전화를 정확히 입력하세요.')\"></body></html>";exit;
	} else if(strlen($up_p_name)==0) {
		echo "<html></head><body onload=\"alert('담당자명을 정확히 입력하세요.')\"></body></html>";exit;
	} else if(strlen($up_p_mobile)==0) {
		echo "<html></head><body onload=\"alert('담당자 휴대전화를 정확히 입력하세요.')\"></body></html>";exit;
	} else if(strlen($up_p_email)==0) {
		echo "<html></head><body onload=\"alert('담당자 이메일을 정확히 입력하세요.')\"></body></html>";exit;
	}
	


	/**gura**/
	//과금방식
	if($useseason!="1"){//입점업체 고유설정이 아닌경우 삭제
		$sql = "DELETE FROM vender_season_range ";
		$sql.= "WHERE vender='".$_VenderInfo->getVidx()."' and pridx=0";
		mysql_query($sql,get_db_conn());

		$sql = "DELETE FROM vender_holiday_list ";
		$sql.= "WHERE vender='".$_VenderInfo->getVidx()."' and pridx=0";
		mysql_query($sql,get_db_conn());
	}

	//if($pricetype=="1"){
		$sql = "SELECT * FROM vender_rent ";
		$sql.= "WHERE vender='".$_VenderInfo->getVidx()."' and pridx=0";

		$result=mysql_query($sql,get_db_conn());
		$_ptdata=mysql_fetch_object($result);
		mysql_free_result($result);

		if($_ptdata->vender){
			$sql2 = "UPDATE vender_rent SET ";
			$sql2.= "rent_stime			= '".$rent_stime."', ";
			$sql2.= "rent_etime			= '".$rent_etime."', ";
			$sql2.= "pricetype			= '".$vender_rent."', ";
			$sql2.= "useseason			= '".$useseason."', ";
			$sql2.= "base_period		= '".$base_period."', ";
			$sql2.= "ownership			= '".$ownership."', ";
			$sql2.= "base_time			= '".$base_time."', ";
			$sql2.= "base_price			= '".$base_price."', ";
			$sql2.= "timeover_price		= '".$timeover_price."', ";
			$sql2.= "halfday			= '".$halfday."', ";
			$sql2.= "halfday_percent	= '".$halfday_percent."', ";
			$sql2.= "oneday_ex			= '".$oneday_ex."', ";
			$sql2.= "time_percent		= '".$time_percent."', ";
			$sql2.= "checkin_time		= '".$checkin_time."', ";
			$sql2.= "checkout_time		= '".$checkout_time."' ";
			$sql2.= "where vender='".$_VenderInfo->getVidx()."' ";
			$sql2.= "and pridx='0'";
			mysql_query($sql2,get_db_conn());

		}else{
			$sql2 = "insert vender_rent SET ";
			$sql2.= "vender	= '".$_VenderInfo->getVidx()."', ";
			$sql2.= "rent_stime			= '".$rent_stime."', ";
			$sql2.= "rent_etime			= '".$rent_etime."', ";
			$sql2.= "pricetype			= '".$vender_rent."', ";
			$sql2.= "useseason			= '".$useseason."', ";
			$sql2.= "base_period		= '".$base_period."', ";
			$sql2.= "ownership			= '".$ownership."', ";
			$sql2.= "base_time			= '".$base_time."', ";
			$sql2.= "base_price			= '".$base_price."', ";
			$sql2.= "timeover_price		= '".$timeover_price."', ";
			$sql2.= "halfday			= '".$halfday."', ";
			$sql2.= "halfday_percent	= '".$halfday_percent."', ";
			$sql2.= "oneday_ex			= '".$oneday_ex."', ";
			$sql2.= "time_percent		= '".$time_percent."', ";
			$sql2.= "checkin_time		= '".$checkin_time."', ";
			$sql2.= "checkout_time		= '".$checkout_time."' ";
			mysql_query($sql2,get_db_conn());
		}
	//}

	//장기대여설정
	$dsql = "delete from vender_longrent where vender=".$_VenderInfo->getVidx(). " and pridx=0";
	mysql_query($dsql,get_db_conn());
	if($_POST['longrent']=="1" && _array($_POST['longrent_sday']) && _array($_POST['longrent_percent'])){
		for($i=0;$i<count($_POST['longrent_sday']);$i++){
			if(_isInt($_POST['longrent_sday'][$i]) && _isInt($_POST['longrent_percent'][$i])){
				$sql2 = "insert into vender_longrent set vender='".$_VenderInfo->getVidx()."',sday='".$_POST['longrent_sday'][$i]."',eday='".$_POST['longrent_eday'][$i]."',percent='".$_POST['longrent_percent'][$i]."'";
				mysql_query($sql2,get_db_conn());
			}
		}
	}

	//환불
	$dsql = "delete from vender_refund where vender=".$_VenderInfo->getVidx(). " and pridx=0";
	mysql_query($dsql,get_db_conn());
	if($_POST['refund']=="1" && _array($_POST['refundday']) && _array($_POST['refundpercent'])){
		for($i=0;$i<count($_POST['refundday']);$i++){
			if(_isInt($_POST['refundpercent'][$i])){
				$sql2 = "insert into vender_refund set vender='".$_VenderInfo->getVidx()."',day='".$_POST['refundday'][$i]."',percent='".$_POST['refundpercent'][$i]."'";
				mysql_query($sql2,get_db_conn());
			}
		}
	}
	
	//장기할인
	$dsql = "delete from vender_longdiscount where vender=".$_VenderInfo->getVidx(). " and pridx=0";
	mysql_query($dsql,get_db_conn());
	if($_POST['longdiscount']=="1" && _array($_POST['discrangeday']) && _array($_POST['discrangepercent'])){
		for($i=0;$i<count($_POST['discrangeday']);$i++){
			if(_isInt($_POST['discrangeday'][$i]) && _isInt($_POST['discrangepercent'][$i])){
				$sql2 = "insert into vender_longdiscount  set vender=".$_VenderInfo->getVidx().",day='".$_POST['discrangeday'][$i]."',percent='".$_POST['discrangepercent'][$i]."'";
				mysql_query($sql2,get_db_conn());
			}
		}
	}
	//gura
	

	$sql = "UPDATE tblvenderinfo SET ";
	if(strlen($up_passwd)>=4) {
		$sql.= "passwd		= '".md5($up_passwd)."', ";
	}
	$sql.= "com_owner		= '".$up_com_owner."', ";
	$sql.= "com_post		= '".$up_com_post."', ";
	$sql.= "com_addr		= '".$up_com_addr."', ";
	$sql.= "com_biz			= '".$up_com_biz."', ";
	$sql.= "com_item		= '".$up_com_item."', ";
	$sql.= "com_tel			= '".$up_com_tel."', ";
	$sql.= "com_fax			= '".$up_com_fax."', ";
	$sql.= "com_homepage	= '".$up_com_homepage."', ";
	$sql.= "p_name			= '".$up_p_name."', ";
	$sql.= "p_mobile		= '".$up_p_mobile."', ";
	$sql.= "p_email			= '".$up_p_email."', ";
	$sql.= "p_buseo			= '".$up_p_buseo."', ";
	$sql.= "p_level			= '".$up_p_level."', ";
	$sql.= "bank_account	= '".$bank_account."', ";
	$sql.= "com_type		= '".$com_type."', ";
	$sql.= "ec_num			= '".$ec_num."', ";
	
	//gura
	$sql.= "pricetype		= '".$pricetype."', ";
	$sql.= "longrent		= '".$longrent."', ";
	$sql.= "refund			= '".$refund."', ";
	$sql.= "longdiscount	= '".$longdiscount."', ";
	$sql.= "season			= '".$useseason."', ";
	$sql.= "category		= '".$category."', ";
	$sql.= "reserve			= '".$reserve."', ";
	$sql.= "reseller_reserve = '".$reseller_reserve."', ";
	$sql.= "cancel_cont		= '".$cancel_cont."', ";
	$sql.= "discount_card	= '".$discount_card."', ";
	$sql.= "booking_confirm	= '".$booking_confirm."', ";

	
	// 배송수단 선택
	$deli_type = $_POST['deli_type'];
	if (is_array($deli_type)) {
		$deli_type = implode(',', $deli_type);
	}

	$sql.= "deli_type		= '".$deli_type."' ";


	// 대표이미지 등록
	if( $_FILES['com_image']['error'] == 0 AND $_FILES['com_image']['size'] > 0 AND eregi("image",$_FILES['com_image']['type']) AND $_POST['com_image_del'] != "OK" ) {
		$exte = explode(".",$_FILES['com_image']['name']);
		$exte = $exte[ count($exte)-1 ];
		$com_image_name = "comImgae_".$_VenderInfo->getVidx().".".$exte;
		move_uploaded_file($_FILES['com_image']['tmp_name'],$com_image_url.$com_image_name);
		$sql.= ", com_image = '".$com_image_name."' ";
	}

	//대표이미지 삭제
	if( $_POST['com_image_del'] == "OK" AND strlen($_POST['com_image_del_file']) > 0 ) {
		unlink($_POST['com_image_del_file']);
		$sql.= ", com_image = '' ";
	}

	$sql.= "WHERE vender='".$_VenderInfo->getVidx()."' ";
	if(mysql_query($sql,get_db_conn())) {
		
		if($up_session == "Y") {
			$sql = "DELETE FROM tblvendersession WHERE authkey != '".$_VenderInfo->getAuthkey()."' AND vender = '".$_VenderInfo->getVidx()."' ";
			mysql_query($sql,get_db_conn());
		}

		
		if($reserve=="0"){
			$sql = "delete from tblmemberreserve where vender='".$_VenderInfo->getVidx()."'";
			mysql_query($sql,get_db_conn());
		}else{
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

					$sql = "insert into tblmemberreserve (vender,group_code,productcode,discountYN,reserve,over_reserve) values ('".$_VenderInfo->getVidx()."','".$gdiscount_code."','','Y','".$discountval."','N') ON DUPLICATE KEY UPDATE discountYN = values(discountYN),reserve = values(reserve),over_reserve = values(over_reserve)";
					mysql_query($sql,get_db_conn());
				}
			}
		}
		
		if($reseller_reserve=="0"){
			$sql = "delete from tblreseller_reserve where vender='".$_VenderInfo->getVidx()."'";
			mysql_query($sql,get_db_conn());
		}else{
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

					$sql = "insert into tblreseller_reserve (vender,group_code,productcode,discountYN,reserve,over_reserve) values ('".$_VenderInfo->getVidx()."','".$gdiscount_code."','','Y','".$discountval."','N') ON DUPLICATE KEY UPDATE discountYN = values(discountYN),reserve = values(reserve),over_reserve = values(over_reserve)";

					mysql_query($sql,get_db_conn());
				}
			}
		}

		echo "<html></head><body onload=\"alert('요청하신 작업이 성공하였습니다.');parent.location.reload()\"></body></html>";exit;
	} else {
		echo "<html></head><body onload=\"alert('요청하신 작업중 오류가 발생하였습니다.')\"></body></html>";exit;
	}
}
/* 개별 수수료 관련 jdy */
else if ($mode=="com"){

	$rq_commission_type = $_POST["rq_commission_type"];
	$rq_rate = $_POST["rq_rate"];
	$rq_name = $_POST["rq_name"];

	//판매 수수료로 운영시 수수료 변경에대한 기록을 남김 ex) 전체-> 개별

	if ($rq_commission_type!=($_vmdata->commission_type)) {

		if ($_vmdata->commission_type == "1") {
			$up_history = "개별수수료 -> 전체수수료 ".$rq_rate."%로 변경요청 [입점]";
		}else{
			$up_history = "전체수수료 ".$_venderdata->rate."% -> 개별수수료로 변경요청 [입점]";
		}
	}else{

		if ($_vmdata->commission_type != '') {
			if ($rq_commission_type != "1") {

				if ($rq_rate !=$_venderdata->rate) {
					$up_history = "전체수수료 ".$_venderdata->rate."% -> ".$rq_rate."% 로 변경요청 [입점]";
					$updateChk = "1";
				}

			}
		}
	}

	$sql = "UPDATE vender_more_info SET ";
	$sql.= "rq_commission_type	= '".$rq_commission_type."', ";
	$sql.= "rq_rate	= '".$rq_rate."', ";
	$sql.= "commission_status = '1' ";
	$sql.= "WHERE vender='".$_VenderInfo->getVidx()."' ";

	$err =0;
	if (!mysql_query($sql,get_db_conn())) {
		$err++;
	}

	if ($up_history !="") {
		$sql = "insert commission_history set ";
		$sql.= "vender	= '".$_VenderInfo->getVidx()."', ";
		$sql.= "memo	= '".$up_history."', ";
		$sql.= "`type`	= '1', ";
		$sql.= "rq_name	= '".$rq_name."', ";
		$sql.= "reg_date	= now() ";
	}

	if (!mysql_query($sql,get_db_conn())) {
		$err++;
	}

	if($err==0) {
		echo "<html></head><body onload=\"alert('요청하신 작업이 성공하였습니다.');parent.location.reload()\"></body></html>";exit;
	} else {
		echo "<html></head><body onload=\"alert('요청하신 작업중 오류가 발생하였습니다.')\"></body></html>";exit;
	}

}
/* 개별 수수료 관련 jdy */

$com_tel=explode("-",$_venderdata->com_tel);
$com_fax=explode("-",$_venderdata->com_fax);
$com_p_mobile=explode("-",$_venderdata->p_mobile);
$bank_account=explode("=",$_venderdata->bank_account);


//장기대여
$longrentinfo = venderLongrentCharge($_VenderInfo->getVidx());		

//환불
$refundinfo = venderRefundCommission($_VenderInfo->getVidx());		

//장기할인
$ldiscinfo = venderLongDiscount($_VenderInfo->getVidx());

$_ptdata->checkin_time = $_ptdata->checkin_time? $_ptdata->checkin_time : "9";
$_ptdata->checkout_time = $_ptdata->checkout_time? $_ptdata->checkout_time : "21";
?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>

<script type="text/javascript" src="/upload/js/jquery-1.7.1.min.js"></script>
<script type="text/javascript">var $j= jQuery.noConflict();</script>

<script language="JavaScript">
function formSubmit() {
	var form = document.form1;
	if (!form.up_com_name.value) {
		form.up_com_name.focus();
		alert("상호(회사명)을 입력하세요.");
		return;
	}
	if(CheckLength(form.up_com_name)>30) {
		form.up_com_name.focus();
		alert("상호(회사명)은 한글15자 영문30자 까지 입력 가능합니다");
		return;
	}
	if (!form.up_com_num.value) {
		form.up_com_num.focus();
		alert("사업자등록번호를 입력하세요.");
		return;
	}
/*
	var bizno;
	var bb;
	bizno = form.up_com_num.value;
	bizno = bizno.replace("-","");
	bb = chkBizNo(bizno);
	if (!bb) {
		alert("인증되지 않은 사업자등록번호 입니다.\n사업자등록번호를 다시 입력하세요.");
		form.up_com_num.value = "";
		form.up_com_num.focus();
		return;
	}
*/
	if (!form.up_com_owner.value) {
		form.up_com_owner.focus();
		alert("대표자 성명을 입력하세요.");
		return;
	}
	if(CheckLength(form.up_com_owner)>12) {
		form.up_com_owner.focus();
		alert("대표자 성명은 한글 6글자까지 가능합니다");
		return;
	}
	if (!form.up_com_post1.value) {
		form.up_com_post1.focus();
		alert("우편번호를 입력하세요.");
		return;
	}
	if (!form.up_com_addr.value) {
		form.up_com_addr.focus();
		alert("사업장 주소를 입력하세요.");
		return;
	}
	if(CheckLength(form.up_com_biz)>30) {
		form.up_com_biz.focus();
		alert("사업자 업태는 한글 15자까지 입력 가능합니다");
		return;
	}
	if(CheckLength(form.up_com_item)>30) {
		form.up_com_item.focus();
		alert("사업자 종목은 한글 15자까지 입력 가능합니다");
		return;
	}
	if(form.up_com_tel1.value.length==0 || form.up_com_tel2.value.length==0 || form.up_com_tel3.value.length==0) {
		form.up_com_tel1.focus();
		alert("회사 대표전화를 입력하세요.");
		return;
	}
	if(!isNumber(form.up_com_tel1.value) || !isNumber(form.up_com_tel2.value) || !isNumber(form.up_com_tel3.value)) {
		form.up_com_tel1.focus();
		alert("전화번호는 숫자만 입력하세요.");
		return;
	}
	if(form.up_com_fax1.value.length>0 && form.up_com_fax2.value.length>0 && form.up_com_fax3.value.length>0) {
		if(!isNumber(form.up_com_fax1.value) || !isNumber(form.up_com_fax2.value) || !isNumber(form.up_com_fax3.value)) {
			form.up_com_fax1.focus();
			alert("팩스번호는 숫자만 입력하세요.");
			return;
		}
	}
	if(form.up_p_name.value.length==0) {
		form.up_p_name.focus();
		alert("담당자 이름을 입력하세요.");
		return;
	}
	if(form.up_p_mobile1.value.length==0 || form.up_p_mobile2.value.length==0 || form.up_p_mobile3.value.length==0) {
		form.up_com_tel1.focus();
		alert("담당자 휴대전화를 입력하세요.");
		return;
	}
	if(!isNumber(form.up_p_mobile1.value) || !isNumber(form.up_p_mobile2.value) || !isNumber(form.up_p_mobile3.value)) {
		form.up_com_tel1.focus();
		alert("담당자 휴대전화 번호는 숫자만 입력하세요.");
		return;
	}
	if(form.up_p_email.value.length==0) {
		form.up_p_email.focus();
		alert("담당자 이메일을 입력하세요.");
		return;
	}
	if(!IsMailCheck(form.up_p_email.value)) {
		form.up_p_email.focus();
		alert("담당자 이메일을 정확히 입력하세요.");
		return;
	}
	if(form.up_bank1.value.length==0 || form.up_bank2.value.length==0 || form.up_bank3.value.length==0) {
		alert("정산받을 계좌정보를 정확히 입력하세요.");
		form.up_bank1.focus();
		return;
	}
	if(form.up_passwd.value.length>0 || form.up_passwd2.value.length>0) {
		if(form.up_passwd.value!=form.up_passwd.value) {
			alert("변경하실 비밀번호가 일치하지 않습니다.");
			form.up_passwd2.focus();
			return;
		} else if(form.up_passwd.value.length<4) {
			alert("비밀번호는 영문, 숫자를 혼합하여 4~12자 이내로 입력하세요.");
			form.up_passwd.focus();
			return;
		}
	}


	if(confirm("변경하신 내용을 저장하시겠습니까?")) {
		form.mode.value="update";
		form.target="processFrame";
		form.submit();
	}
}

function Save() {
	document.form1.mode.value="update";
		document.form1.target="processFrame";
	document.form1.submit();
}
function f_addr_search(form,post,addr,gbn) {
	window.open("<?=$Dir.FrontDir?>addr_search.php?form="+form+"&post="+post+"&addr="+addr+"&gbn="+gbn,"f_post","resizable=yes,scrollbars=yes,x=100,y=200,width=370,height=250");
}

function commissionDivView(v) {

	cm_div = document.getElementById('commission_div');

	if (v=='N') {
		cm_div.style.display="none";
	}else {
		if (cm_div.style.display=="none") {
			cm_div.style.display="";
		}else{
			cm_div.style.display="none";
		}
	}
}

function selCommission(num) {

	c_td = document.getElementById("commission_all")

	if (num==1) {
		c_td.style.display="inline"
	}else{
		c_td.style.display="none"
	}

}

function commissionRequest() {

	var form = document.form1;

	if(form.rq_commission_type[1].checked) {

		if(form.rq_rate.value.length==0) {
			alert("수수료를 입력해주세요.");
			form.rq_rate.focus();
			return;
		}
	}

	if(form.rq_name.value.length==0) {
		alert("요청자를 입력해주세요.");
		form.rq_name.focus();
		return;
	}


	if(confirm("수수료 변경을 요청하시겠습니까??")) {
		form.mode.value="com";
		form.target="processFrame";
		form.submit();
	}
}


function pricetypeDivView(v) {
	var cm_div = document.getElementById('pricetype_div');
	var val = $j('select[name=category]').val();

	if (v=='N') {
		cm_div.style.display="none";
	}else {
		if (cm_div.style.display=="none") {
			cm_div.style.display="";
			
			<?if(!$_ptdata->vender){?>
			$j.post('./new/getOrginfo.php',{'act':'price','code':val},
			function(data){
				$j('#vender_rent').val(data.item_main);
				$j('input[name=halfday][value='+data.halfday+']').attr("checked", true);
				$j('input[name=halfday_percent]').val(data.halfday_percent);
				$j('input[name=oneday_ex][value='+data.oneday_ex+']').attr("checked", true);
				$j('input[name=time_percent]').val(data.time_percent);
				$j('select[name=base_time]').val(data.base_time);
				$j('input[name=base_price]').val(data.base_price);
				$j('input[name=timeover_price]').val(data.timeover_price);

				if(data.item_main=="day"){
					$j('#day_div').show();
					$j('#time_div').hide();
					$j('#checkout_div').hide();
					$j('#period_div').hide();
					$j('#long_div').hide();
				}else if(data.item_main=="time"){
					$j('#day_div').hide();
					$j('#time_div').show();
					$j('#checkout_div').hide();
					$j('#period_div').hide();
					$j('#long_div').hide();
				}else if(data.item_main=="checkout"){
					$j('#day_div').hide();
					$j('#time_div').hide();
					$j('#checkout_div').show();
					$j('#period_div').hide();
					$j('#long_div').hide();
					$j('#checkin_time').val($j('#rent_stime').val());
					$j('#checkout_time').val($j('#rent_etime').val());
				}else if(data.item_main=="period"){
					$j('#day_div').hide();
					$j('#time_div').hide();
					$j('#checkout_div').hide();
					$j('#period_div').show();
					$j('#long_div').hide();
				}else if(data.item_main=="long"){
					$j('#day_div').hide();
					$j('#time_div').hide();
					$j('#checkout_div').hide();
					$j('#period_div').hide();
					$j('#long_div').show();
				}

				if(data.halfday=="Y"){
					html = '<div>당일 12시간 요금: 24시간 요금의 <input type="text" name="halfday_percent" size="3" maxlength="4" value="'+data.halfday_percent+'">%</div>';
					$j('#price1').html(html);
				}

				if(data.oneday_ex=="time"){
					html = '<div>추가 1시간 요금: 24시간 요금의 <input type="text" name="time_percent" size="3" maxlength="4" value="'+data.time_percent+'">%</div>';
					$j('#price2').html(html);
				}else if(data.oneday_ex=="half"){
					html = '<div>추가 12시간 요금: 24시간 요금의 <input type="text" name="time_percent" size="3" maxlength="4" value="'+data.time_percent+'">%</div>';
					$j('#price2').html(html);
				}

			},'json');
			<?}?>
		}else{
			cm_div.style.display="none";
		}
	}
}

function discountDivView(v) {
	var cm_div = document.getElementById('discount_div');
	var val = $j('select[name=category]').val();

	if (v=='N') {
		cm_div.style.display="none";
	}else {
		if (cm_div.style.display=="none") {
			cm_div.style.display="";
			
			<? if(count($ldiscinfo)==0){?>
			$j.post('./new/getOrginfo.php',{'act':'longdiscount','code':val},
			function(data){
				$j('#rangeDiscountDiv').html(data.items);
			},'json');
			<? } ?>

		}else{
			cm_div.style.display="none";
		}
	}
}

function seasonDivView(v) {
	var cm_div = document.getElementById('season_div');
	var val = $j('select[name=category]').val();

	if (v=='N') {
		cm_div.style.display="none";
	}else {
		if (cm_div.style.display=="none") {
			cm_div.style.display="";
		}else{
			cm_div.style.display="none";
		}
	}
}

function longrentDivView(v) {
	var cm_div = document.getElementById('longrent_div');
	var val = $j('select[name=category]').val();

	if (v=='N') {
		cm_div.style.display="none";
	}else {
		if (cm_div.style.display=="none") {
			cm_div.style.display="";
			$j('#addLongrent_sday').val(parseInt($j('input[name=base_period]').val())+1);

			<? if(count($refundinfo)==0){?>
			$j.post('./new/getOrginfo.php',{'act':'longrent','code':val},
			function(data){
				$j('#longrentDiv').html(data.items);
			},'json');
			<? } ?>
		}else{
			cm_div.style.display="none";
		}
	}
}

function refundDivView(v) {
	var cm_div = document.getElementById('refund_div');
	var val = $j('select[name=category]').val();

	if (v=='N') {
		cm_div.style.display="none";
	}else {
		if (cm_div.style.display=="none") {
			cm_div.style.display="";

			<? if(count($refundinfo)==0){?>
			$j.post('./new/getOrginfo.php',{'act':'refund','code':val},
			function(data){
				$j('#refundDiv').html(data.items);
			},'json');
			<? } ?>
		}else{
			cm_div.style.display="none";
		}
	}
}

function reserveDivView(v) {
	var cm_div = document.getElementById('reserve_div');

	if (v=='N') {
		cm_div.style.display="none";
	}else {
		if (cm_div.style.display=="none") {
			cm_div.style.display="";
		}else{
			cm_div.style.display="none";
		}
	}
}

function reseller_reserveDivView(v) {
	var cm_div = document.getElementById('reseller_reserve_div');

	if (v=='N') {
		cm_div.style.display="none";
	}else {
		if (cm_div.style.display=="none") {
			cm_div.style.display="";
		}else{
			cm_div.style.display="none";
		}
	}
}
</script>

<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
<col width=190></col>
<col width=20></col>
<col width=></col>
<col width=5></col>
<tr>
	<td width=190 valign=top nowrap background="images/minishop_leftbg.gif"><? include ("menu.php"); ?></td>
	<td width=20 nowrap></td>
	<td valign=top style="padding-top:20px">

	<table width="100%"  border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>
		<table width="100%"  border="0" cellpadding="0" cellspacing="0" >
		<tr>
			<td>
				<table border=0 cellpadding=0 cellspacing=0 width=100% >
				<tr>
					<td><img src="images/venter_info_title.gif"></td>
				</tr>
				<tr>
					<td height=5 background="images/minishop_titlebg.gif">
				</tr>
				</table>
			</td>
		</tr>
		<tr><td height=10></td></tr>
		<tr>
			<td>
				<table border=0 cellpadding=0 cellspacing=0 width=100% >
				<tr>
					<td colspan=3 >


						<table cellpadding="10" cellspacing="1" width="100%" bgcolor="#EFEFF2">
							<tr>
								<td  bgcolor="#F5F5F9" style="padding:20px">
									<table border=0 cellpadding=0 cellspacing=0 width=100%>
										<tr>
											<td class="notice_gray" height="20"><img src="images/icon_dot02.gif" border=0 hspace="4">입점사 관리자 정보 및 기타 설정 값을 입력합니다.</td>
										</tr>
										<tr>
											<td class="notice_gray" height="20"><img src="images/icon_dot02.gif" border=0 hspace="4">입력한 정보는 본사 사이트 입점업체 정보에 입력됩니다.</td>
										</tr>
										<tr>
											<td class="notice_gray" height="20"><img src="images/icon_dot02.gif" border=0 hspace="4">입점사 관리자의 상품 처리권한[등록/수정/삭제/인증]은 본사 관리자가 승인 후 가능 합니다.</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>



					</td>
				</tr>
				</table>
				</td>
			</tr>

			<!-- 처리할 본문 위치 시작 -->
			<tr><td height=40></td></tr>
			<tr>
				<td >
				
				<form name=form1 method=post action="<?=$_SERVER[PHP_SELF]?>" enctype="multipart/form-data">
				<input type=hidden name=mode>
				<table border=0 cellpadding=0 cellspacing=0 width=100%>

				<tr>
					<td><img src="images/venter_info_stitle01.gif" alt="입점업체 기본정보" align="absmiddle"><font style="color:#2A97A7">('*'표시는 필수입력입니다)</font></td>
				</tr>
				<tr><td height=5></td></tr>
				<tr><td height=2 bgcolor=#808080></td></tr>
				</table>
				<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
				<col width=140></col>
				<col width=></col>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B><font color=red>*</font> 상호(회사명)</B></td>
					<td style=padding:10>
					<input type="text" class=input  name=up_com_name value="<?=$_venderdata->com_name?>" size=20 maxlength=30 disabled>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B><font color=red>*</font> 사업자등록번호</B></td>
					<td style=padding:10>
					<input type="text" class=input  name=up_com_num value="<?=$_venderdata->com_num?>" size=20 maxlength=20 onkeyup="strnumkeyup(this)" disabled>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>

				<!--
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B><font color=red>*</font> 네임텍 사용유무</B></td>
					<td style=padding:10>
						<input type="radio" name="com_nametech" value="1" />사용함&nbsp;&nbsp;
						<input type="radio" name="com_nametech" value="0" checked />사용안함
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				-->

				<tr>
					<td bgcolor="#F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y; background-position:right; padding:9px;"><B><font color=red>*</font> 대표이미지</B></td>
					<td style="padding:7px 10px;">

						<div style="margin:5px;">
							<div style="float:left; margin:0px; padding:0px; font-size:0px;"><img src="<?=$com_image_url.$_venderdata->com_image?>" width="120" onerror="this.src='/images/no_img.gif';" style="border:1px solid #dddddd;" /></div>
							<div style="float:left; margin-top:5px; margin-left:10px;">
								<div>
									<span style="font-size:11px; color:#666666; line-height:15px; letter-spacing:-1px;">
										<strong>사용유무 : </strong><input type="checkbox" name="com_nametech" value="1" <?=($_venderdata->com_nametech?"checked":"");?>><br /><br /><br />

										※ <b>네임텍 이미지는??</b>
										<div style="margin:5px 0px;"><img src="images/vender_nametek_sample.gif" style="border:1px solid #e5e5e5;" alt="" /></div>
										- 네임텍 이미지는 상품 목록 및 상세 페이지에서 입점사 정보 출력시 사용되는 이미지 입니다.<br />
										- 이미지 사이즈는 가로*세로 100px * 100px 을 권장드립니다.
									</span>
								</div>
								<div style="margin-top:10px;">
									<input type="file" name="com_image" id="com_image">
									<input type="checkbox" name="com_image_del" id="com_image_del" value="OK" onclick="com_image.style.display=( this.checked ?'none':'inline')"><label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="com_image_del">삭제</label>
									<input type="hidden" name="com_image_del_file" value="<?=$com_image_url.$_vdata->com_image?>">
								</div>
							</div>
						</div>

						<!--
						<strong>사용유무 : </strong><input type="checkbox" name="com_nametech" value="1" <?=($_venderdata->com_nametech?"checked":"");?>><br /><br />
						<img src="<?=$com_image_url.$_venderdata->com_image?>" onerror="this.src='/images/no_img.gif';">
						<input type="file" name="com_image" id="com_image">
						<input type="checkbox" name="com_image_del" id="com_image_del" value="OK" onclick="com_image.style.display=( this.checked ?'none':'inline')"><label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="com_image_del">삭제</label>
						<input type="hidden" name="com_image_del_file" value="<?=$com_image_url.$_venderdata->com_image?>">
						<br /> 이미지 사이즈 100px * 100px 권장.
						-->

					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>


				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B><font color=red>*</font> 대표자 성명</B></td>
					<td style=padding:10>
					<input class=input type="text" name=up_com_owner value="<?=$_venderdata->com_owner?>" size=20 maxlength="12">
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td rowspan=2 bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B><font color=red>*</font> 사업장 주소</B></td>
					<td style=padding:10>
					<input class=input type="text" type=text name="up_com_post1" id="up_com_post1" value="<?=$_venderdata->com_post?>" size="5" maxlength="5" readonly> <img src="images/btn_findpostno.gif" border=0 align=absmiddle style="cursor:hand" onClick="addr_search_for_daumapi('up_com_post1','up_com_addr','');">
					</td>
				</tr>
				<tr>
					<td style=padding:10>
					<input type=text class=input  name="up_com_addr" id="up_com_addr" value="<?=$_venderdata->com_addr?>" size=50 maxlength=150>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B><font color=red>*</font> 사업자 업태</B></td>
					<td style=padding:10>
					<input type="text" class=input  name=up_com_biz value="<?=$_venderdata->com_biz?>" size=30 maxlength=30>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B><font color=red>*</font> 사업자 종목</B></td>
					<td style=padding:10>
					<input type=text class=input  name=up_com_item value="<?=$_venderdata->com_item?>" size=30 maxlength=30>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B><font color=red>*</font> 회사 대표전화</B></td>
					<td style=padding:10>
					<input type=text class=input  name=up_com_tel1 value="<?=$com_tel[0]?>" size=4 maxlength=4 style="width:40" onkeyup="strnumkeyup(this)">-<input type=text class=input  name=up_com_tel2 value="<?=$com_tel[1]?>" size=4 maxlength=4 style="width:40" onkeyup="strnumkeyup(this)">-<input type=text class=input  name=up_com_tel3 value="<?=$com_tel[2]?>" size=4 maxlength=4 style="width:40" onkeyup="strnumkeyup(this)">
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>



				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B> 통신판매신고</B></td>
					<td style=padding:10>
					<input type=text class=input  name=ec_num value="<?=$_venderdata->ec_num?>" size=30 maxlength=30>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>


				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B> 사업자구분</B></td>
					<td style=padding:10>
					<input type=text class=input  name=com_type value="<?=$_venderdata->com_type?>" size=30 maxlength=30>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>




				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>회사 팩스번호</B></td>
					<td style=padding:10>
					<input type=text class=input  name=up_com_fax1 value="<?=$com_fax[0]?>" size=4 maxlength=4 style="width:40" onkeyup="strnumkeyup(this)">-<input type=text class=input  name=up_com_fax2 value="<?=$com_fax[1]?>" size=4 maxlength=4 style="width:40" onkeyup="strnumkeyup(this)">-<input type=text class=input  name=up_com_fax3 value="<?=$com_fax[2]?>" size=4 maxlength=4 style="width:40" onkeyup="strnumkeyup(this)">
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>회사 홈페이지</B></td>
					<td style=padding:10>
					http://<input type=text class=input  name=up_com_homepage value="<?=$_venderdata->com_homepage?>" size=30 maxlength=50>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				</table>






				<table border=0 cellpadding=0 cellspacing=0 width=100%>
				<tr><td height=40></td></tr>
				<tr>
					<td><img src="images/venter_info_stitle02.gif" alt="업체 담당자 정보" align="absmiddle"> <font style="color:#2A97A7">('*'표시는 필수입력입니다)</font></td>
				</tr>
				<tr><td height=5></td></tr>
				<tr><td height=2 bgcolor=#808080></td></tr>
				</table>
				<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
				<col width=140></col>
				<col width=></col>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B><font color=red>*</font> 담당자 이름</B></td>
					<td style=padding:10>
					<input type=text class=input  name=up_p_name value="<?=$_venderdata->p_name?>" size=20 maxlength=20> &nbsp; <span class="notice_blue">* 입점 담당자 이름을 정확히 입력하세요.</font>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B><font color=red>*</font> 담당자 휴대전화</B></td>
					<td style=padding:10>
					<input type=text class=input  name=up_p_mobile1 value="<?=$com_p_mobile[0]?>" size=4 maxlength=3 style="width:40" onkeyup="strnumkeyup(this)">-<input type=text class=input  name=up_p_mobile2 value="<?=$com_p_mobile[1]?>" size=4 maxlength=4 style="width:40" onkeyup="strnumkeyup(this)">-<input type=text class=input  name=up_p_mobile3 value="<?=$com_p_mobile[2]?>" size=4 maxlength=4 style="width:40" onkeyup="strnumkeyup(this)"></td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B><font color=red>*</font> 담당자 이메일</B></td>
					<td style=padding:10>
					<input type=text class=input  name=up_p_email value="<?=$_venderdata->p_email?>" size=30 maxlength=50> &nbsp; <span class="notice_blue">* 주문확인시 담당자 이메일로 통보됩니다.</font>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>담당자 부서명</B></td>
					<td style=padding:10>
					<input type=text class=input  name=up_p_buseo value="<?=$_venderdata->p_buseo?>" size=20 maxlength=20>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>담당자 직위</B></td>
					<td style=padding:10>
					<input type=text class=input  name=up_p_level value="<?=$_venderdata->p_level?>" size=20 maxlength=20>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				</table>

				<table border=0 cellpadding=0 cellspacing=0 width=100%>
				<tr><td height=40></td></tr>
				<tr>
					<td><img src="images/venter_info_stitle04.gif" alt="미니샵 운영ID 관리" align="absmiddle"></td>
				</tr>
				<tr><td height=5></td></tr>
				<tr><td height=2 bgcolor=#808080></td></tr>
				</table>
				<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
				<col width=140></col>
				<col width=></col>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>운영 ID</td>
					<td style=padding:10>
					<B><?=$_venderdata->id?></B>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>비밀번호 변경</B></td>
					<td style=padding:10>
					<input class=input  type=password name=up_passwd size=15> &nbsp; <span class="notice_blue">* 영문, 숫자를 혼용하여 사용(4자 ~ 12자)</font>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>비밀번호 확인</B></td>
					<td style=padding:10>
					<input class=input type=password name=up_passwd2 size=15> &nbsp; <span class="notice_blue">* 비밀번호는 정기적으로 변경 하실 것을 권장합니다.</font>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>운영자 세션 삭제</B></td>
					<td style=padding:10>
					<input type=radio name=up_session value="N" id="idx_sessionN"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_sessionN">로그인 세션 유지</label><img width=5 height=0><input type=radio name=up_session value="Y" id="idx_sessionY"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_sessionY">로그인 세션 삭제</label><br>
					<span class="notice_blue">* 로그인 세션 삭제시 자신을 제외한 모든 운영자들은 재로그인 후 이용이 가능합니다.</font>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				</table>



				<table border=0 cellpadding=0 cellspacing=0 width=100%>
				<tr><td height=40></td></tr>
				<tr>
					<td><img src="images/venter_info_stitle03.gif" alt="업체 관리정보" align="absmiddle"> <font style="color:#2A97A7">('*'표시는 필수입력입니다)</font></td>
				</tr>
				<tr><td height=5></td></tr>
				<tr><td height=2 bgcolor=#808080></td></tr>
				</table>

				<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed" id="rentOptTable">
				<col width=140></col>
				<col width=></col>

				<? if ($account_rule!="1")  { ?>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>수수료 운영형태</td>
					<td style=padding:10>
					<B><? if ($_vmdata->commission_type=="1") { ?>상품별 수수료 적용<? }else{ ?>전체상품 동일 수수료 적용<? } ?></B>
					&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" style="color:#ffffff;background-color:#000000;border:0;width:80px;height:25px;cursor:pointer" onclick="commissionDivView();">변경요청</button>
					<div id="commission_div" style="position:absolute;width:450px;border:2px solid #acacac;background-color:#ffffff;z-index:999;padding:5px;display:none;">
						<div style="width:100%;text-align:right"><span style="border:1px solid gray;color:#ffffff;background-color:#000000;padding:2px 4px;cursor:pointer" onclick="commissionDivView('N');" >X</span></div>
						<div style="width:100%;margin-top:5px;">
							<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
								<col width=100 />
								<col width= />
							<tr><td height=2 colspan="2" bgcolor=#808080></td></tr>
							<tr>
								<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>운영형태</td>
								<td style=padding:10>
								<input type=radio name=rq_commission_type id=rq_commission_type0 value="1" onclick="selCommission('0');"> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=rq_commission_type0>상품개별 수수료</label>
								&nbsp;&nbsp;
								<input type=radio name=rq_commission_type id=rq_commission_type1 value="0" onclick="selCommission('1');" checked> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=rq_commission_type1>전체상품 동일 수수료</label>
								</td>
								<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
								<tr id="commission_all" >
									<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>전체 수수료</td>
									<td style=padding:10>
										<input type=text name=rq_rate value="" size=3 maxlength=3 onkeyup="strnumkeyup(this)" class=input>%
									</td>
								</tr>
								<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
								<tr id="commission_all" >
									<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>요청자 이름</td>
									<td style=padding:10>
										<input type=text name=rq_name value="" size=10 class=input>
									</td>
								</tr>
								<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
								<tr><td></td>
									<td style="padding-top:10px;text-align:right;"><span style="border:1px solid gray;color:#ffffff;background-color:#000000;padding:2px 4px;cursor:pointer" onclick="commissionRequest()">요청</span></td>
								</tr>
							</tr>
							</table>
						</div>
					</div>
					<? if ($_vmdata->commission_status=="1" || $_vmdata->commission_status=="2") {

							if ($_vmdata->rq_commission_type=="1") {
								$cm_value = "상품별 수수료";
							}else{
								$cm_value = "전체상품 동일 수수료 ".$_vmdata->rq_rate."%";
							}

							$cm_status = "";

							if ($_vmdata->commission_status=="1") {
								$cm_status = "요청 중";
							}else if ($_vmdata->commission_status=="2") {
								$cm_status = "요청 거부";
							}

						?>
					<br/><br/>
					<span class="notice_blue"><?= $cm_value ?>로 <?= $cm_status ?></span>
					<? } ?>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<? if (!$_vmdata->commission_type) {?>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>전체 수수료</td>
					<td style=padding:10>
					<B><?=(int)$_venderdata->rate?> %</B>
					&nbsp;&nbsp;&nbsp;&nbsp; <span class="notice_blue">* 모든상품에 동일 적용됩니다.</font>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<? } ?>

				<? } ?>

				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>상품 처리 권한</td>
					<td style=padding:10>
					<input type=checkbox name=chk_prdt1 value="Y" <?if(substr($_venderdata->grant_product,0,1)=="Y")echo"checked";?> disabled>등록
					<img width=5 height=0>
					<input type=checkbox name=chk_prdt2 value="Y" <?if(substr($_venderdata->grant_product,1,1)=="Y")echo"checked";?> disabled>수정
					<img width=5 height=0>
					<input type=checkbox name=chk_prdt3 value="Y" <?if(substr($_venderdata->grant_product,2,1)=="Y")echo"checked";?> disabled>삭제
					<img width=5 height=0>
					<input type=checkbox name=chk_prdt4 value="Y" <?if(substr($_venderdata->grant_product,3,1)=="Y")echo"checked";?> disabled>등록/수정시, 관리자 인증
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>입점 상품수 제한</td>
					<td style=padding:10>
					<B><?=($_venderdata->product_max==0?"무제한 등록 가능":$_venderdata->product_max."개 까지 상품등록 가능")?></B>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>판매 수수료</td>
					<td style=padding:10>
					<B><?=(int)$_venderdata->rate?> %</B>
					&nbsp;&nbsp;&nbsp;&nbsp; <span class="notice_blue">* 쇼핑몰 본사에서 받는 상품판매 수수료입니다.</font>
					</td>
				</tr>
				
				<? /*추가 gura */?>
				<script language="javascript" type="text/javascript">
				$j(function(){
					var val = $j('select[name=category]').val();
					
					$j('#rentOptTable').on('mouseover','.longrentHelp',function(){
						var pos = $j(this).position();									
						$j('#longrentHelpDiv').css({'display':'','left':pos.left+30,'top':pos.top-20});

						$j.post('./new/getOrginfo.php',{'act':'longrent','code':val},
							function(data){
								$j('#longrentHelpDiv').html("");
								$j('#longrentHelpDiv').html(data.items);
								$j('#longrentHelpDiv').append("<br>상품등록 및 관리 페이지에서 대여상품이 해당하는 카테고리별 장기대여 설정에 대해 잠깐쇼핑몰본사가 등록한 값을 따릅니다.");
							},'json');
					});
					
					$j('#rentOptTable').on('mouseout','.longrentHelp',function(){
						$j('#longrentHelpDiv').css('display','none');
					});

					$j('#rentOptTable').on('mouseover','.priceHelp',function(){
						var pos = $j(this).position();									
						$j('#priceHelpDiv').css({'display':'','left':pos.left+30,'top':pos.top-20});

						$j.post('./new/getOrginfo.php',{'act':'price','code':val},
							function(data){
								$j('#priceHelpDiv').html("");
								$j('#priceHelpDiv').html(data.items);
								$j('#priceHelpDiv').append("<br>상품등록 및 관리 페이지에서 대여상품이 해당하는 카테고리별 과금방식 설정에 대해 잠깐쇼핑몰본사가 등록한 값을 따릅니다.");
							},'json');
					});
					
					$j('#rentOptTable').on('mouseout','.priceHelp',function(){
						$j('#priceHelpDiv').css('display','none');
					});

					$j('#rentOptTable').on('mouseover','.refundHelp',function(){
						var pos = $j(this).position();									
						$j('#refundHelpDiv').css({'display':'','left':pos.left+30,'top':pos.top-20});

						$j.post('./new/getOrginfo.php',{'act':'refund','code':val},
							function(data){
								$j('#refundHelpDiv').html("");
								$j('#refundHelpDiv').html(data.items);
								$j('#refundHelpDiv').append("<br>상품등록 및 관리 페이지에서 대여상품이 해당하는 카테고리별 환불 설정에 대해 잠깐쇼핑몰본사가 등록한 값을 따릅니다.");
							},'json');
					});
					
					$j('#rentOptTable').on('mouseout','.refundHelp',function(){
						$j('#refundHelpDiv').css('display','none');
					});

					$j('#rentOptTable').on('mouseover','.longdiscHelp',function(){
						var pos = $j(this).position();									
						$j('#longdiscHelpDiv').css({'display':'','left':pos.left+30,'top':pos.top-20});

						$j.post('./new/getOrginfo.php',{'act':'longdiscount','code':val},
							function(data){
								$j('#longdiscHelpDiv').html("");
								$j('#longdiscHelpDiv').html(data.items);
								$j('#longdiscHelpDiv').append("<br>상품등록 및 관리 페이지에서 대여상품이 해당하는 카테고리별 장기할인 설정에 대해 잠깐쇼핑몰본사가 등록한 값을 따릅니다.");
							},'json');
					});
					
					$j('#rentOptTable').on('mouseout','.longdiscHelp',function(){
						$j('#longdiscHelpDiv').css('display','none');
					});

					$j('#rentOptTable').on('mouseover','.reserveHelp',function(){
						var pos = $j(this).position();									
						$j('#reserveHelpDiv').css({'display':'','left':pos.left+30,'top':pos.top-20});

						$j.post('./new/getOrginfo.php',{'act':'reserve','code':val},
							function(data){
								$j('#reserveHelpDiv').html("");
								$j('#reserveHelpDiv').html(data.items);
								$j('#reserveHelpDiv').append("<br>상품등록 및 관리 페이지에서 대여상품이 해당하는 카테고리별 회원등급별 적립 설정에 대해 잠깐쇼핑몰본사가 등록한 값을 따릅니다.");
							},'json');
					});

					$j('#rentOptTable').on('mouseout','.reserveHelp',function(){
						$j('#reserveHelpDiv').css('display','none');
					});

					$j('#rentOptTable').on('mouseover','.reseller_reserveHelp',function(){
						var pos = $j(this).position();									
						$j('#reseller_reserveHelpDiv').css({'display':'','left':pos.left+30,'top':pos.top-20});

						$j.post('./new/getOrginfo.php',{'act':'reseller_reserve','code':val},
							function(data){
								$j('#reseller_reserveHelpDiv').html("");
								$j('#reseller_reserveHelpDiv').html(data.items);
								$j('#reseller_reserveHelpDiv').append("<br>상품등록 및 관리 페이지에서 대여상품이 해당하는 카테고리별 추천인 적립 설정에 대해 잠깐쇼핑몰본사가 등록한 값을 따릅니다.");
							},'json');
					});

					$j('#rentOptTable').on('mouseout','.reseller_reserveHelp',function(){
						$j('#reseller_reserveHelpDiv').css('display','none');
					});

					$j('#rentOptTable').on('mouseover','.seasonHelp',function(){
						var pos = $j(this).position();									
						$j('#seasonHelpDiv').css({'display':'','left':pos.left+30,'top':pos.top-20});

						$j.post('./new/getOrginfo.php',{'act':'season','code':val},
							function(data){
								$j('#seasonHelpDiv').html("");
								$j('#seasonHelpDiv').html(data.items);
								$j('#seasonHelpDiv').append("<br>상품등록 및 관리 페이지에서 대여상품이 해당하는 카테고리별 장기할인 설정에 대해 잠깐쇼핑몰본사가 등록한 값을 따릅니다.");
							},'json');
					});
					
					$j('#rentOptTable').on('mouseout','.seasonHelp',function(){
						$j('#seasonHelpDiv').css('display','none');
					});
	
				});
				</script>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>점포 속성</td>
					<td style=padding:10>
						<select name="category">
							<option value="">선택하세요</option>
					<?
					$tmp = getCategoryItems();
					foreach($tmp['items'] as $item){
						if($item['codeA']==$_venderdata->category) $selected = "selected";
						else $selected = "";
						echo "<option value='".$item['codeA']."' ".$selected.">".$item['code_name']."</option>";
					}
					
					?>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>대여가능시간</td>
					<td style=padding:10>
						시작: <input type="text" name="rent_stime" id="rent_stime" size="3" maxlength="2" value="<?=$_ptdata->rent_stime?>">시 ~
						종료: <input type="text" name="rent_etime" id="rent_etime" size="3" maxlength="2" value="<?=$_ptdata->rent_etime?>">시 
						*24시간 대여인 경우 시작과 종료시간을 같게 설정하세요.
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>과금방식 설정</td>
					<td style=padding:10>
					<input type=radio name=pricetype value="0" <?if($_venderdata->pricetype=="0")echo"checked";?> onclick="pricetypeDivView('N')">본사 정책에 따름 <input type="button" class="priceHelp" style="width:30px;" value="?" />
					<input type=radio name=pricetype value="1" <?if($_venderdata->pricetype=="1")echo"checked";?> onclick="pricetypeDivView()">입점업체 고유 설정

					<div id="priceHelpDiv" style="width:250px; padding:10px; height:150px; position:absolute; background:#efefef; border:1px solid #FF0; display:none"></div>

					<? if($_venderdata->pricetype=="1"){ $display=""; }else{ $display="none"; } ?>
					<div id="pricetype_div" style="width:90%;margin-top:5px;z-index:999;padding:5px;display:<?=$display?>;">
						<script language="javascript" type="text/javascript">
						function chPriceType(){ 
							var idx = $j("#vender_rent > option:selected").val(); 
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
						<table cellpadding="0" cellspacing="0" style="width:100%;margin-top:7px;padding:7px 7px 7px 7px; border-bottom:0px;border:2px solid #acacac;background-color:#ffffff">
							<tr>
								<th style="width:100px;">과금방식</th>
								<td class="norbl" style="padding:5px;">
									<select name="vender_rent" id="vender_rent" style="width:120px" onchange="javascript:chPriceType()">
										<option value="">선택하세요</option>
										<option value="day" <? if($_ptdata->pricetype == 'day') echo ' selected="selected"'; ?> >24시간제</option>
										<option value="time" <? if($_ptdata->pricetype == 'time') echo ' selected="selected"'; ?>>1시간제</option>
										<option value="checkout" <? if($_ptdata->pricetype == 'checkout') echo ' selected="selected"'; ?>>일일제(숙박제)</option>
										<option value="period" <? if($_ptdata->pricetype == 'period') echo ' selected="selected"'; ?> >단기기간제</option>
										<option value="long" <? if($_ptdata->pricetype == 'long') echo ' selected="selected"'; ?> >장기기간제(약정)</option>
									</select>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<? if($_ptdata->pricetype == 'day') $display = ""; else $display = "none"; ?>
									<table id="day_div" cellpadding="0" cellspacing="0" style="display:<?=$display?>;width:100%;margin-top:7px;padding:7px 7px 7px 7px;">
										<tr>
											<th style="width:150px;">당일 12시간 대여허용</th>
											<td class="norbl" style="padding:5px;">
												<input type=radio name=halfday value="Y" <?if($_ptdata->halfday=="Y")echo"checked";?> onclick="halfdayCheck('Y')">예
												<input type=radio name=halfday value="N" <?if($_ptdata->halfday=="N")echo"checked";?> onclick="halfdayCheck('N')">아니오
											</td>
											<td id="price1">
												<?
												if($_ptdata->halfday=="Y"){
													echo '<div>당일 12시간 요금: 24시간 요금의 <input type="text" name="halfday_percent" size="3" maxlength="4" value="'.$_ptdata->halfday_percent.'">%</div>';
												}
												?>
											</td>
										</tr>
										<tr>
											<th>1일 초과시 과금기준</th>
											<td class="norbl" style="padding:5px;">
												<input type=radio name=oneday_ex value="day" <?if($_ptdata->oneday_ex=="day")echo"checked";?> onclick="onedayexCheck('day')">1일 단위
												<input type=radio name=oneday_ex value="half" <?if($_ptdata->oneday_ex=="half")echo"checked";?> onclick="onedayexCheck('half')">12시간 단위
												<input type=radio name=oneday_ex value="time" <?if($_ptdata->oneday_ex=="time")echo"checked";?> onclick="onedayexCheck('time')">1시간 단위
											</td>
											<td id="price2">
												<?
												if($_ptdata->oneday_ex=="time"){
													echo '<div>추가 1시간 요금: 24시간 요금의 <input type="text" name="time_percent" size="3" maxlength="4" value="'.$_ptdata->time_percent.'">%</div>';
												}else if($_ptdata->oneday_ex=="half"){
													echo '<div>추가 12시간 요금: 24시간 요금의 <input type="text" name="time_percent" size="3" maxlength="4" value="'.$_ptdata->time_percent.'">%</div>';
												}
												?>
											</td>
										</tr>
									</table>
									<? if($_ptdata->pricetype == 'time') $display = ""; else $display = "none"; ?>
									<table id="time_div" cellpadding="0" cellspacing="0" style="display:<?=$display?>;width:100%;margin-top:7px;padding:7px 7px 7px 7px;">
										<tr>
											<th style="width:100px;">기본요금</th>
											<td class="norbl" style="padding:5px;">
												<select name="base_time">
													<? for($i=1;$i<=36;$i++){?>
													<option value="<?=$i?>" <? if($_ptdata->base_time == $i) echo ' selected="selected"'; ?> ><?=$i?>시간</option>
													<? } ?>
												</select>
												<input type="text" name="base_price" size="15" value="<?=$_ptdata->base_price?>">원
											</td>
										</tr>
										<tr>
											<th>추가시간당</th>
											<td>
												<input type="text" name="timeover_price" size="15" value="<?=$_ptdata->timeover_price?>">원
											</td>
										</tr>
									</table>
									<? if($_ptdata->pricetype == 'checkout') $display = ""; else $display = "none"; ?>
									<table id="checkout_div" cellpadding="0" cellspacing="0" style="display:<?=$display?>;width:100%;margin-top:7px;padding:7px 7px 7px 7px;">
										<tr>
											<th style="width:100px;">체크인 시간</th>
											<td class="norbl" style="padding:5px;">
												<input type="text" name="checkin_time" id="checkin_time" size="3" maxlength="2" value="<?=$_ptdata->checkin_time?>">시
											</td>
											<th style="width:100px;">체크아웃 시간</th>
											<td>
												<input type="text" name="checkout_time" id="checkout_time" size="3" maxlength="2" value="<?=$_ptdata->checkout_time?>">시
											</td>
										</tr>
									</table>
									<? if($_ptdata->pricetype == 'period') $display = ""; else $display = "none"; ?>
									<table id="period_div" cellpadding="0" cellspacing="0" style="display:<?=$display?>;width:100%;margin-top:7px;padding:7px 7px 7px 7px;">
										<tr>
											<th style="width:100px;">기본대여일</th>
											<td class="norbl" style="padding:5px;">
												<input type="text" name="base_period" size="5" value="<?=$_ptdata->base_period?>" onkeyup="javascript:$j('#addLongrent_sday').val(parseInt($j('input[name=base_period]').val())+1);">일 까지
												&nbsp;&nbsp;*3일은 2박 3일입니다.
											</td>
										</tr>
									</table>
									<? if($_ptdata->pricetype == 'long') $display = ""; else $display = "none"; ?>
									<table id="long_div" cellpadding="0" cellspacing="0" style="display:<?=$display?>;width:100%;margin-top:7px;padding:7px 7px 7px 7px;">
										<tr>
											<th style="width:100px;">만기 후 소유권</th>
											<td class="norbl" style="padding:5px;">
												<input type=radio name="ownership" value="mv" <?if($_ptdata->ownership=="mv")echo"checked";?>>이전 
												<input type=radio name="ownership" value="re" <?if($_ptdata->ownership=="re")echo"checked";?>>반납
											</td>
										</tr>
									</table>

								</td>
							</tr>
						</table>
					</div>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>장기대여설정</td>
					<td style=padding:10>
					<input type=radio name=longrent value="0" <?if($_venderdata->longrent=="0")echo"checked";?> onclick="longrentDivView('N')">본사 정책에 따름 <input type="button" class="longrentHelp" style="width:30px;" value="?" /> 
					<input type=radio name=longrent value="1" <?if($_venderdata->longrent=="1")echo"checked";?> onclick="longrentDivView()">입점업체 고유 설정

					<div id="longrentHelpDiv" style="width:250px; padding:10px; height:60px; position:absolute; background:#efefef; border:1px solid #FF0; display:none"></div>

					<? if($_venderdata->longrent=="1"){ $display=""; }else{ $display="none"; } ?>
					<div id="longrent_div" style="margin-top:5px;z-index:999;padding:5px;display:<?=$display?>;">
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
						<table cellpadding="0" cellspacing="0" style="margin-top:7px;padding:7px 7px 7px 7px; border-bottom:0px;border:2px solid #acacac;background-color:#ffffff">
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

					</div>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>환불 정책</td>
					<td style=padding:10>
					<input type=radio name=refund value="0" <?if($_venderdata->refund=="0")echo"checked";?> onclick="refundDivView('N')">본사 정책에 따름 <input type="button" class="refundHelp" style="width:30px;" value="?" /> 
					<input type=radio name=refund value="1" <?if($_venderdata->refund=="1")echo"checked";?> onclick="refundDivView()">입점업체 고유 설정

					<div id="refundHelpDiv" style="width:250px; padding:10px; height:60px; position:absolute; background:#efefef; border:1px solid #FF0; display:none"></div>

					<? if($_venderdata->refund=="1"){ $display=""; }else{ $display="none"; } ?>
					<div id="refund_div" style="margin-top:5px;z-index:999;padding:5px;display:<?=$display?>;">
						<style type="text/css">
						#refundDiv div{float:left;width:30%;min-width:250px;margin-right:3px;padding:5px;background:#f4f4f4}
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
									html = '<div><input type="hidden" name="refundday[]" value="'+d+'"><input type="hidden" name="refundpercent[]" value="'+p+'"><span style="float:left">'+d+' 일전 '+p+'%</span><img src="../admin/images/btn_del.gif" alt="삭제" align="right" /></div>';
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
									html = '<div><input type="text" name="refundday[]" value="'+d+'"><input type="hidden" name="refundpercent[]" value="'+p+'"><span style="float:left">당일환불(배송 후) '+p+'%</span><img src="../admin/images/btn_del.gif" alt="삭제" align="right" /></div>';
									$j('#refundDiv').prepend(html);
									$j('#addRefundDay3').val('0');
									$j('#addRefundPercent3').val('');
								}
							}
							
						}
						</script>
						<table cellpadding="0" cellspacing="0" style="margin-top:7px;padding:7px 7px 7px 7px; border-bottom:0px;border:2px solid #acacac;background-color:#ffffff">
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
								<th colspan="2">당일환불(배송 전)</th>
								<th style="width:100px;">수수료</th>
								<td style="padding:5px;">
									<input type="hidden" name="addRefundDay2" id="addRefundDay2" value="-1" />
									<input type="text" name="addRefundPercent2" id="addRefundPercent2" value="" style="width:30px;" />
									% </td>
								<td>
									<input type="button" name="addRefundBtn" value="추가" onclick="javascript:addRefundCommi2()" />
								</td>
							</tr>
							<tr>
								<th colspan="2">당일환불(배송 후)</th>
								<th style="width:100px;">수수료</th>
								<td style="padding:5px;">
									<input type="hidden" name="addRefundDay3" id="addRefundDay3" value="0" />
									<input type="text" name="addRefundPercent3" id="addRefundPercent3" value="" style="width:30px;" />
									% </td>
								<td>
									<input type="button" name="addRefundBtn" value="추가" onclick="javascript:addRefundCommi3()" />
								</td>
							</tr>
						</table>
						<div style="padding:3px 0px; clear:both" id="refundDiv">
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
								%</span><img src="../admin/images/btn_del.gif" alt="삭제" align="right" /></div>
							<?	}
						}?>
						</div>

					</div>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>예약 확정 방식</td>
					<td style=padding:10>
					<input type=radio name=booking_confirm value="now" <?if($_venderdata->booking_confirm=="now")echo"checked";?>>결제와 동시  
					<input type=radio name=booking_confirm value="select" <?if($_venderdata->booking_confirm!="now")echo"checked";?>>
					<select name="booking_confirm_time">
						<option value="">선택</option>
						<option value="00:10" <?if($_venderdata->booking_confirm=="00:10")echo"selected";?>>10분</option>
						<option value="00:20" <?if($_venderdata->booking_confirm=="00:20")echo"selected";?>>20분</option>
						<option value="00:30" <?if($_venderdata->booking_confirm=="00:30")echo"selected";?>>30분</option>
						<? for($i=1;$i<=24;$i++){?>
						<option value="<?=sprintf('%02d',$i)?>:00" <?if($_venderdata->booking_confirm==sprintf('%02d',$i).":00")echo"selected";?>><?=$i?>시간</option>
						<? } ?>
					</select>
					이내 확인 알림
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>회원등급별 적립</td>
					<td style=padding:10>
					<input type=radio name=reserve value="0" <?if($_venderdata->reserve=="0")echo"checked";?>>본사 정책에 따름 
					<input type="button" class="reserveHelp" style="width:30px;" value="?" />
					<input type=radio name=reserve value="1" <?if($_venderdata->reserve=="1")echo"checked";?> onclick="reserveDivView()">입점업체 고유 설정

					<div id="reserveHelpDiv" style="width:250px; padding:10px; height:60px; position:absolute; background:#efefef; border:1px solid #FF0; display:none"></div>

					<? if($_venderdata->reserve=="1"){ $display=""; }else{ $display="none"; } ?>
					<div id="reserve_div" style="width:100%;margin-top:5px;z-index:999;padding:5px;display:<?=$display?>;">	
						<?
						$groupdiscount = getGroupReserves("",$_VenderInfo->getVidx());					
						
						foreach($groupdiscount as $gdiscount){ 
							$discount = ($gdiscount['reserve'] < 1)?$gdiscount['reserve']*100:$gdiscount['reserve'];
						?>
							<span style="margin-right:10px;"><span style="font-weight:bold;"><?=$gdiscount['group_name']?></span>&nbsp;
							<input name="discount[<?=$gdiscount['group_code']?>]" id="discount<?=$gdiscount['group_code']?>" size="10" type="text" class="input" value="<?=$discount?>" onkeyup="javascript:checkGroupReserveVal('<?=$gdiscount['group_code']?>')" style="width:30px; text-align:right; padding-right:5px;"><input name="discount_type[<?=$gdiscount['group_code']?>]" type="hidden" value="100" />%</span>
						<?						
						}
						?>
					</div>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>추천인 적립</td>
					<td style=padding:10>
					<input type=radio name=reseller_reserve value="0" <?if($_venderdata->reseller_reserve=="0")echo"checked";?>>본사 정책에 따름 
					<input type="button" class="reseller_reserveHelp" style="width:30px;" value="?" />
					<input type=radio name=reseller_reserve value="1" <?if($_venderdata->reseller_reserve=="1")echo"checked";?> onclick="reseller_reserveDivView()">입점업체 고유 설정

					<div id="reseller_reserveHelpDiv" style="width:250px; padding:10px; height:60px; position:absolute; background:#efefef; border:1px solid #FF0; display:none"></div>

					<? if($_venderdata->reseller_reserve=="1"){ $display=""; }else{ $display="none"; } ?>
					<div id="reseller_reserve_div" style="width:100%;margin-top:5px;z-index:999;padding:5px;display:<?=$display?>;">	
						<?
						$groupdiscount2 = getGroupReseller_Reserves("",$_VenderInfo->getVidx());
						
						foreach($groupdiscount2 as $gdiscount2){
							$discount2 = ($gdiscount2['reserve'] < 1)?$gdiscount2['reserve']*100:$gdiscount2['reserve'];
						?>
							<span style="margin-right:10px;"><span style="font-weight:bold;"><?=$gdiscount2['group_name']?></span>&nbsp;
							<input name="discount2[<?=$gdiscount2['group_code']?>]" id="discount2<?=$gdiscount2['group_code']?>" size="10" type="text" class="input" value="<?=$discount2?>" onkeyup="javascript:checkGroupReserveVal('<?=$gdiscount2['group_code']?>')" style="width:30px; text-align:right; padding-right:5px;"><input name="discount_type2[<?=$gdiscount2['group_code']?>]" type="hidden" value="100" />%</span>
						<?						
						}
						?>
					</div>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>장기할인 설정</td>
					<td style=padding:10>
					<input type=radio name=longdiscount value="0" <?if($_venderdata->longdiscount=="0")echo"checked";?> onclick="discountDivView('N')">본사 정책에 따름 <input type="button" class="longdiscHelp" style="width:30px;" value="?" />
					<input type=radio name=longdiscount value="1" <?if($_venderdata->longdiscount=="1")echo"checked";?> onclick="discountDivView()">입점업체 고유 설정
					
					<div id="longdiscHelpDiv" style="width:250px; padding:10px; height:60px; position:absolute; background:#efefef; border:1px solid #FF0; display:none">
					
					</div>

					<? if($_venderdata->longdiscount=="1"){ $display=""; }else{ $display="none"; } ?>
					<div id="discount_div" style="margin-top:5px;z-index:999;padding:5px;display:<?=$display?>;">
						<style type="text/css">
						#rangeDiscountDiv div{ width:30%; margin-right:3px;; float:left; padding:5px; background:#f4f4f4}
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
								}else{
									html = '<div><input type="hidden" name="discrangeday[]" value="'+d+'"><input type="hidden" name="discrangepercent[]" value="'+p+'"><span style="float:left">'+d+' 일이상 '+p+'% 할인</span><img src="../admin/images/btn_del.gif" alt="삭제" align="right" /></div>';
									$j('#rangeDiscountDiv').append(html);
									$j('#addRangeDiscountDay').val('');
									$j('#addRangeDiscountPercent').val('');
								}
							}
							
						}
						</script>
						<table cellpadding="0" cellspacing="0" style="margin-top:7px;padding:7px 7px 7px 7px; border-bottom:0px;border:2px solid #acacac;background-color:#ffffff">
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
						
						<div style="padding:3px 0px; clear:both" id="rangeDiscountDiv">
							<? if(_array($ldiscinfo)){
							foreach($ldiscinfo as $dday=>$dpercent){ ?>
							<div>
								<input type="hidden" name="discrangeday[]" value="<?=$dday?>">
								<input type="hidden" name="discrangepercent[]" value="<?=$dpercent?>">
								<span style="float:left">
								<?=$dday?>
								일이상
								<?=$dpercent?>
								%할인</span><img src="../admin/images/btn_del.gif" alt="삭제" align="right" /></div>
							<?	}
						}?>
						</div>

					</div>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>성수기 설정</td>
					<td style=padding:10>
					<input type=radio name=useseason value="2" <?if($_venderdata->season=="2")echo"checked";?> onclick="seasonDivView('N')">본사 정책에 따름 <input type="button" class="seasonHelp" style="width:30px;" value="?" />
					<input type=radio name=useseason value="0" <?if($_venderdata->season=="0")echo"checked";?> onclick="seasonDivView('N')">사용안함 
					<input type=radio name=useseason value="1" <?if($_venderdata->season=="1")echo"checked";?> onclick="seasonDivView()">입점업체 고유 성수기/비성수기 사용
					
					<div id="seasonHelpDiv" style="width:250px; padding:10px; height:60px; position:absolute; background:#efefef; border:1px solid #FF0; display:none">
					
					</div>
					<? if($_venderdata->season=="1"){ $display=""; }else{ $display="none"; } ?>
					<div id="season_div" style="position:;width:600px;margin-top:5px;z-index:999;padding:5px;display:<?=$display?>;">
							
						<div id="seasonDiv" style="border:1px solid #efefefe"> 
							<table cellpadding="0" cellspacing="0" width="100%" id="seasonListTbl" style="margin-top:7px;padding:7px 7px 7px 7px; border-bottom:0px;border:2px solid #acacac;background-color:#ffffff">
								</tr>
									<th style="width:120px;">성수기/준성수기</th>
									<td class="norbl" style="padding:5px;">
										<input type="button" value="성수기/준성수기 관리" style="width:200px;" onclick="window.open('vender_seasonpop.php?vender=<?=$_VenderInfo->getVidx()?>', 'busySeasonPop', 'width=800,height=600' );">
									</td>
								</tr>
								<tr>
									<th class="nobbl">공휴일/주말</th>
									<td style="padding:5px;" class="norbl nobbl">
										<input type="button" value="공휴일/주말 관리" style="width:200px;"  onclick="window.open('vender_holiday.php?vender=<?=$_VenderInfo->getVidx()?>', 'holidayPop', 'width=800,height=600' );">
									</td>
								</tr>
							</table>
						</div>

					</div>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>중도해지시 <br>해약 비용</b></td>
					<td style="padding:10">
						<textarea name="cancel_cont" style="width:80%;height:120px"><?=$_venderdata->cancel_cont?></textarea>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>제휴카드 할인</b></td>
					<td style="padding:10">
						<textarea name="discount_card" style="width:80%;height:50px"><?=$_venderdata->discount_card?></textarea>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>배송수단선택</b></td>
					<td style=padding:10>
						<?php
							$deli_type_checked = array(5);
							if ($_venderdata->deli_type) {
								$deli_type = explode(',', $_venderdata->deli_type);

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
				<? /*추가 gura */?>

				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B><font color=red>*</font> 정산 계좌정보</td>
					<td style=padding:10>
					은행 <input type=text class=input  name=up_bank1 value="<?=$bank_account[0]?>" size=10>
					<img width=5 height=0>
					계좌번호 <input type=text class=input  name=up_bank2 value="<?=$bank_account[1]?>" size=20>
					<img width=5 height=0>
					예금주 <input type=text class=input  name=up_bank3 value="<?=$bank_account[2]?>" size=15>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>정산일</td>
					<td style=padding:10>
					<?
					switch($_vmdata->adjust_lastday) {
						case 0 : $account_date = $_venderdata->account_date;
							break;
						case 1 : $account_date = "매월 마지막일";
							break;
						case 2 : $account_date = "매월 15일과 마지막일";
							break;
					}
					?>
					<B><?=$account_date?></B>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
			<? /*추가 jdy */?>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>결산일</td>
					<td style=padding:10>
					<B><?=(strlen($_vmdata->close_date)>0?"정산일로 부터 ".$_vmdata->close_date." 일전까지":"")?></B>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
			<? /*추가 jdy */?>

				
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>수수료정책변경<br/>히스토리</td>
					<td style=padding:10>
					<?
						getVenderCommissionHistory($_VenderInfo->getVidx(), 0);
					?>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				</table>


				<table border=0 cellpadding=0 cellspacing=0 width=100%>
				<tr><td height=20></td></tr>
				<tr>
					<td align=center>
					<A HREF="javascript:formSubmit()"><img src="images/btn_save01.gif" border=0></A>
					</td>
				</tr>


				</table>

				</form>

				</td>
			</tr>
			<!-- 처리할 본문 위치 끝 -->

			</table>
			</td>
		</tr>
		</table>
		</td>
	</tr>
	</table>
	</td>
</tr>
</table>
<iframe name="processFrame" src="about:blank" width="0" height="0" scrolling=no frameborder=no></iframe>

<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script type="text/javascript">
function addr_search_for_daumapi(post,addr1,addr2) {
	new daum.Postcode({
		oncomplete: function(data) {
			// 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

			// 각 주소의 노출 규칙에 따라 주소를 조합한다.
			// 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
			var fullAddr = ''; // 최종 주소 변수
			var extraAddr = ''; // 조합형 주소 변수

			// 사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
			if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
				fullAddr = data.roadAddress;

			} else { // 사용자가 지번 주소를 선택했을 경우(J)
				fullAddr = data.jibunAddress;
			}

			// 사용자가 선택한 주소가 도로명 타입일때 조합한다.
			if(data.userSelectedType === 'R'){
				//법정동명이 있을 경우 추가한다.
				if(data.bname !== ''){
					extraAddr += data.bname;
				}
				// 건물명이 있을 경우 추가한다.
				if(data.buildingName !== ''){
					extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
				}
				// 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
				fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
			}

			// 우편번호와 주소 정보를 해당 필드에 넣는다.
			document.getElementById(post).value = data.zonecode; //5자리 새우편번호 사용
			document.getElementById(addr1).value = fullAddr;

			// 커서를 상세주소 필드로 이동한다.
			if (addr2 != "") {
				document.getElementById(addr2).focus();
			}
		}
	}).open();
}
</script>

<?=$onload?>

<? INCLUDE "copyright.php"; ?>
