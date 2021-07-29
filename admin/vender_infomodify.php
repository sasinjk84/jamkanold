<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");
include_once($Dir."lib/admin_more.php");
include_once($Dir.'service/subMallIche/bankcode.php');

####################### 페이지 접근권한 check ###############
$PageCode = "vd-1";
$MenuCode = "vender";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$vender=$_POST["vender"];


$sql = "SELECT a.*, b.brand_name FROM tblvenderinfo a, tblvenderstore b ";
$sql.= "WHERE a.vender='".$vender."' AND a.delflag='N' AND a.vender=b.vender ";
$result=mysql_query($sql,get_db_conn());
if(!$_vdata=mysql_fetch_object($result)) {
	echo "<script>alert('해당 업체 정보가 존재하지 않습니다.');history.go(-1);</script>";
	exit;
}
mysql_free_result($result);

$com_tel=explode("-",$_vdata->com_tel);
$com_fax=explode("-",$_vdata->com_fax);
$p_mobile=explode("-",$_vdata->p_mobile);
$bank_account=explode("=",$_vdata->bank_account);

$_vdata->checkin_time = $_vdata->checkin_time? $_vdata->checkin_time : "9";
$_vdata->checkout_time = $_vdata->checkout_time? $_vdata->checkout_time : "21";


/* 수수료 관련 추가 jdy */
$sql = "SELECT * FROM vender_more_info ";
$sql.= "WHERE vender='".$vender."'";
$result=mysql_query($sql,get_db_conn());

$_vmdata=mysql_fetch_object($result);

mysql_free_result($result);
/* 수수료 관련 추가 jdy */

/* 과금방식 추가 gura */
$sql = "SELECT * FROM vender_rent ";
$sql.= "WHERE vender='".$vender."' and pridx=0";

$result=mysql_query($sql,get_db_conn());
$_ptdata=mysql_fetch_object($result);
mysql_free_result($result);
/* 과금방식 추가 gura */



// 정산 기준 조회 jdy
$shop_more_info = getShopMoreInfo();
$account_rule = $shop_more_info['account_rule'];

$reserve_use = $shop_more_info['reserve_use'];
$coupon_use = $shop_more_info['coupon_use'];
// 정산 기준 조회 jdy


$type=$_POST["type"];
if($type=="update") {

	$up_disabled=$_POST["up_disabled"];
	$up_passwd=$_POST["up_passwd"];
	$up_com_name=$_POST["up_com_name"];
	$up_com_num=$_POST["up_com_num"];
	$up_brand_name=$_POST["up_brand_name"];
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

	$chk_prdt1=$_POST["chk_prdt1"];
	$chk_prdt2=$_POST["chk_prdt2"];
	$chk_prdt3=$_POST["chk_prdt3"];
	$chk_prdt4=$_POST["chk_prdt4"];
	$up_product_max=$_POST["up_product_max"];
	$up_rate=$_POST["up_rate"];
	$up_bank1=$_POST["up_bank1"];
	$up_bank2=$_POST["up_bank2"];
	$up_bank3=$_POST["up_bank3"];
	$up_account_date=$_POST["up_account_date"];

	$com_type=$_POST["com_type"];
	$ec_num=$_POST["ec_num"];
	$com_nametech=$_POST["com_nametech"];

	$price=$_POST["price"];
	$refund=$_POST["refund"];
	$longdiscount=$_POST["longdiscount"];
	$season=$_POST["season"];


	/* 수수료 관련 추가 jdy */
	$up_commission_type = $_POST["up_commission_type"];
	$up_etc = $_POST["up_etc"];
	$up_admin_memo = $_POST["up_admin_memo"];

	$up_close_date = $_POST["up_close_date"];


	$up_reserve_use=$_POST["up_reserve_use"];
	$up_coupon_use=$_POST["up_coupon_use"];

	$up_history = "";
	$updateChk = "";

	$up_adjust_lastday=$_POST["adjust_lastday"];

	$up_all_rate = $_POST["up_all_rate"];

	if ($account_rule != "1") {
	//판매 수수료로 운영시 수수료 변경에대한 기록을 남김 ex) 전체-> 개별

		//$_vmdata 값이 없을떈 수행하지 않음

		if ($_vmdata->vender) {
			if ($up_commission_type!=($_vmdata->commission_type)) {

				if ($_vmdata->commission_type == "1") {
					$up_history = "개별수수료 -> 전체수수료 ".$up_rate."%로 변경 [운영본사]";
				}else{

					$up_history = "전체수수료 ".$_vdata->rate."% -> 개별수수료로 변경 [운영본사]";
					$up_rate = 0;
				}
				$updateChk = "1";
			}else{

				if ($_vmdata->commission_type != '') {
					if ($up_commission_type != "1") {

						if ($up_rate !=$_vdata->rate) {
							$up_history = "전체수수료 ".$_vdata->rate."% -> ".$up_rate."% 로 변경 [운영본사]";
							$updateChk = "1";
						}

					}else{
						$up_rate = 0;
					}
				}
			}
		}
	}else{
	//공급가로 운영할 경우 무조건 개별 공급가 사용.

		$up_commission_type = 1;
		$up_rate = 0;
	}
	/* 수수료 관련 추가 jdy */

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

	if($chk_prdt1!="Y") $chk_prdt1="N";
	if($chk_prdt2!="Y") $chk_prdt2="N";
	if($chk_prdt3!="Y") $chk_prdt3="N";
	if($chk_prdt4!="Y") $chk_prdt4="N";
	$grant_product=$chk_prdt1.$chk_prdt2.$chk_prdt3.$chk_prdt4;

	$bank_account="";
	if(strlen($up_bank1)>0 && strlen($up_bank2)>0 && strlen($up_bank3)>0) {
		$bank_account=$up_bank1."=".$up_bank2."=".$up_bank3;
	}

	$error="";
	if(strlen($up_com_name)==0) {
		$error="회사명을 입력하세요.";
	} /*else if(strlen($up_com_num)==0) {
		$error="사업자등록번호를 입력하세요.";
	} */else if(strlen($up_brand_name)==0) {
		$error="미니샵명을 입력하세요.";
	} /*else if(chkBizNo($up_com_num)==false) {
		$error="사업자등록번호를 정확히 입력하세요.";
	} */else if(strlen($up_com_tel)==0) {
		$error="회사 대표전화를 정확히 입력하세요.";
	} else if(strlen($up_p_name)==0) {
		$error="담당자 이름을 입력하세요.";
	} else if(strlen($up_p_mobile)==0) {
		$error="담당자 휴대전화를 정확히 입력하세요.";
	} else if(strlen($up_p_email)==0) {
		$error="담당자 이메일을 입력하세요.";
	} else if(ismail($up_p_email)==false) {
		$error="담당자 이메일을 정확히 입력하세요.";
	} else if(strlen($up_close_date)==0) {
		/* 추가 jdy */
		$error="결산일을 입력해주세요.";
		/* 추가 jdy */
	}

	if(strlen($error)==0) {
		$sql = "SELECT brand_name FROM tblvenderstore WHERE vender!='".$vender."' AND brand_name='".$up_brand_name."' ";
		$result=mysql_query($sql,get_db_conn());
		if($row=mysql_fetch_object($result)) {
			$error="미니샵명이 중복되었습니다.";
		}
		mysql_free_result($result);

		if(strlen($error)==0) {

			
			/**gura**/
			//과금방식
			//if($pricetype=="1"){
				$sql = "SELECT * FROM vender_rent ";
				$sql.= "WHERE vender='".$vender."' and pridx=0";

				$result=mysql_query($sql,get_db_conn());
				$_ptdata=mysql_fetch_object($result);
				mysql_free_result($result);
				
				if($vender_rent=="checkout"){
					$checkin_time = $checkin_time;
					$checkout_time = $checkout_time;
				}else{
					$checkin_time = $rent_stime;
					$checkout_time = $rent_etime;
				}

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
					$sql2.= "where vender='".$vender."' ";
					$sql2.= "and pridx='0'";
					mysql_query($sql2,get_db_conn());

				}else{
					$sql2 = "insert vender_rent SET ";
					$sql2.= "vender	= '".$vender."', ";
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
			//	}
			}
			
			//장기대여설정
			$dsql = "delete from vender_longrent where vender=".$vender. " and pridx=0";
			mysql_query($dsql,get_db_conn());
			if($_POST['longrent']=="1" && _array($_POST['longrent_sday']) && _array($_POST['longrent_percent'])){
				for($i=0;$i<count($_POST['longrent_sday']);$i++){
					if(_isInt($_POST['longrent_sday'][$i]) && _isInt($_POST['longrent_percent'][$i])){
						$sql2 = "insert into vender_longrent set vender='".$vender."',sday='".$_POST['longrent_sday'][$i]."',eday='".$_POST['longrent_eday'][$i]."',percent='".$_POST['longrent_percent'][$i]."'";
						mysql_query($sql2,get_db_conn());
					}
				}
			}

			//환불
			$dsql = "delete from vender_refund where vender=".$vender. " and pridx=0";		
			mysql_query($dsql,get_db_conn());
			
			if(_array($_POST['refundday']) && _array($_POST['refundpercent'])){
				for($i=0;$i<count($_POST['refundday']);$i++){
					if(_isInt($_POST['refundday'][$i]) && _isInt($_POST['refundpercent'][$i])){
						$sql2 = "insert into vender_refund set vender='".$vender."',day='".$_POST['refundday'][$i]."',percent='".$_POST['refundpercent'][$i]."'";
						mysql_query($sql2,get_db_conn());
					}
				}
			}
			
			//장기할인
			$dsql = "delete from vender_longdiscount where vender=".$vender. " and pridx=0";
			mysql_query($dsql,get_db_conn());
			if(_array($_POST['discrangeday']) && _array($_POST['discrangepercent'])){
				for($i=0;$i<count($_POST['discrangeday']);$i++){
					if(_isInt($_POST['discrangeday'][$i]) && _isInt($_POST['discrangepercent'][$i])){
						$sql2 = "insert into vender_longdiscount  set vender=".$vender.",day='".$_POST['discrangeday'][$i]."',percent='".$_POST['discrangepercent'][$i]."'";
						mysql_query($sql2,get_db_conn());
					}
				}
			}
			//gura

			if($useseason!="1"){//입점업체 고유설정이 아닌경우 삭제
				$sql = "DELETE FROM vender_season_range ";
				$sql.= "WHERE vender='".$vender."' and pridx=0";
				mysql_query($sql,get_db_conn());

				$sql = "DELETE FROM vender_holiday_list ";
				$sql.= "WHERE vender='".$vender."' and pridx=0";
				mysql_query($sql,get_db_conn());
			}


			$sql = "UPDATE tblvenderinfo SET ";
			if(strlen($up_passwd)>0) {
				$sql.= "passwd			= '".md5($up_passwd)."', ";
			}
			$sql.= "grant_product	= '".$grant_product."', ";
			$sql.= "product_max		= '".$up_product_max."', ";
			$sql.= "rate			= '".$up_rate."', ";
			$sql.= "bank_account	= '".$bank_account."', ";
			$sql.= "account_date	= '".$up_account_date."', ";
			$sql.= "com_name		= '".$up_com_name."', ";
			$sql.= "com_num			= '".$up_com_num."', ";
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
			$sql.= "regdate			= '".date("YmdHis")."', ";
			$sql.= "disabled			= '".$up_disabled."', ";
			$sql.= "com_type		= '".$com_type."', ";
			$sql.= "ec_num			= '".$ec_num."', ";
			$sql.= "com_nametech	= '".$com_nametech."', ";

			//gura
			$sql.= "pricetype		= '".$pricetype."', ";
			$sql.= "longrent		= '".$longrent."', ";
			$sql.= "refund			= '".$refund."', ";
			$sql.= "longdiscount	= '".$longdiscount."', ";
			$sql.= "season			= '".$useseason."', ";
			$sql.= "category		= '".$category."' ";
			
			// 만족도 추가
			if(_isInt($_REQUEST['starmark'])) $sql.= ",starmark	= '".$_REQUEST['starmark']."' ";

			// 네임텍 이미지 등록
			if( $_FILES['com_image']['error'] == 0 AND $_FILES['com_image']['size'] > 0 AND eregi("image",$_FILES['com_image']['type']) AND $_POST['com_image_del'] != "OK" ) {
				$exte = explode(".",$_FILES['com_image']['name']);
				$exte = $exte[ count($exte)-1 ];
				$com_image_name = "comImgae_".date("YmdHis").".".$exte;
				move_uploaded_file($_FILES['com_image']['tmp_name'],$com_image_url.$com_image_name);
				$sql.= ", com_image = '".$com_image_name."' ";
			}

			//네임텍 이미지 삭제
			if( $_POST['com_image_del'] == "OK" AND strlen($_POST['com_image_del_file']) > 0 ) {
				unlink($_POST['com_image_del_file']);
				$sql.= ", com_image = '' ";
			}


			$sql.= "WHERE vender='".$vender."' ";
			if(mysql_query($sql,get_db_conn())) {
				if($_vdata->brand_name!=$up_brand_name) {
					$sql = "UPDATE tblvenderstore SET ";
					$sql.= "brand_name	= '".$up_brand_name."' ";
					$sql.= "WHERE vender='".$vender."' ";
					mysql_query($sql,get_db_conn());
				}


				/* 수수료 관련 추가 jdy */
				$sql = "select * from vender_more_info WHERE vender='".$vender."' ";
				$result = mysql_query($sql,get_db_conn());
				$data_lows = mysql_num_rows($result);
				$_vmdata=mysql_fetch_object($result);
				mysql_free_result($result);

				if ($data_lows > 0) {

					$sql = "UPDATE vender_more_info SET ";
					$sql.= "commission_type	= '".$up_commission_type."', ";

					if ($updateChk=="1") {
						$sql.= "commission_status = '0', ";
					}

					$sql.= "close_date				= '".$up_close_date."', ";
					$sql.= "etc				= '".$up_etc."', ";
					$sql.= "admin_memo		= '".$up_admin_memo."', ";
					$sql.= "reserve_use		= '".$up_reserve_use."', ";
					$sql.= "coupon_use		= '".$up_coupon_use."', ";
					$sql.= "adjust_lastday		= '".$up_adjust_lastday."' ";

					$sql.= "WHERE vender='".$vender."' ";
					mysql_query($sql,get_db_conn());

					if ($_vmdata->close_date != $up_close_date ) {
						$close_history = "결산일 ".$_vmdata->close_date."일에서 ".$up_close_date."일로 변경";
					}

				}else{

					$sql = "INSERT vender_more_info SET ";
					$sql.= "vender			= '".$vender."', ";
					$sql.= "commission_type	= '".$up_commission_type."', ";
					$sql.= "rq_commission_type	= '0', ";
					$sql.= "rq_rate	= '0', ";
					$sql.= "commission_status = '0', ";
					$sql.= "etc				= '".$up_etc."', ";
					$sql.= "admin_memo		= '".$up_admin_memo."', ";
					$sql.= "reserve_use		= '".$up_reserve_use."', ";
					$sql.= "close_date				= '".$up_close_date."', ";
					$sql.= "coupon_use		= '".$up_coupon_use."', ";
					$sql.= "adjust_lastday	= '".$up_adjust_lastday."' ";
					mysql_query($sql,get_db_conn());
				}

				if ($up_history !="") {
					$sql = "insert commission_history set ";
					$sql.= "vender	= '".$vender."', ";
					$sql.= "memo	= '".$up_history."', ";
					$sql.= "`type`	= '1', ";
					$sql.= "admin_id	= '".$_usersession->id."', ";
					$sql.= "reg_date	= now() ";

					mysql_query($sql,get_db_conn());
				}

				if ($close_history !="") {
					$sql = "insert commission_history set ";
					$sql.= "vender	= '".$vender."', ";
					$sql.= "memo	= '".$close_history."', ";
					$sql.= "`type`	= '1', ";
					$sql.= "admin_id	= '".$_usersession->id."', ";
					$sql.= "reg_date	= now() ";

					mysql_query($sql,get_db_conn());
				}

				$ad_his_chk = 0;

				if ($up_adjust_lastday==0 && $_vdata->account_date != $up_account_date) {
					$ad_his = "정산일 ".$_vdata->account_date."일에서 ".$up_account_date."일로 변경";
					$ad_his_chk++;
				}

				if ($_vmdata->adjust_lastday != $up_adjust_lastday) {

					$ad_his = "";
					switch($_vmdata->adjust_lastday) {
						case 0 : $ad_his = "정산일이 직접지정에서 ";
							break;
						case 1 : $ad_his = "정산일이 매월마지막일에서 ";
							break;
						case 2 : $ad_his = "정산일이 15일과 매월마지막일에서 ";
							break;
					}

					switch($up_adjust_lastday) {
						case 0 : $ad_his .= "직접지정으로 변경 ( ".$up_account_date." ) ";
							break;
						case 1 : $ad_his .= "매월마지막일로 변경";
							break;
						case 2 : $ad_his .= "15일과 매월마지막일로 변경";
							break;
					}

					$ad_his_chk++;
				}

				if ($ad_his_chk>0) {

					$sql = "insert commission_history set ";
					$sql.= "vender	= '".$vender."', ";
					$sql.= "memo	= '".$ad_his."', ";
					$sql.= "`type`	= '1', ";
					$sql.= "admin_id	= '".$_usersession->id."', ";
					$sql.= "reg_date	= now() ";

					mysql_query($sql,get_db_conn());

				}

				if ($up_commission_type == "1") {
					if ($up_all_rate>0) {
						setProductCommissionAll($vender, $up_all_rate, $_usersession->id);
					}
				}

				/* 수수료 관련 추가 jdy */
				
				
				// 그룹 정보 처리
				if(!_isInt($_POST['vgidx'])) mysql_query("delete from vender_group_link where vender='".$vender."'",get_db_conn());
				else mysql_query("insert into vender_group_link (vender,vgidx) values ('".$vender."','".$_POST['vgidx']."') on duplicate key update vgidx='".$_POST['vgidx']."'",get_db_conn());
				$log_content = "## 입점업체 정보 수정 ## - 업체ID : ".$_vdata->id;
				ShopManagerLog($_ShopInfo->getId(),$connect_ip,$log_content);


				echo "<html></head><body onload=\"alert('업체정보 수정이 완료되었습니다.');parent.document.form3.submit();\"></body></html>";exit;
			} else {
				$error="입점업체 등록중 오류가 발생하였습니다.";
			}
		}
	}
	if(strlen($error)>0) {
		echo "<html></head><body onload=\"alert('".$error."');\"></body></html>";exit;
	}
	
	
} else if($type=="delete" && ($_POST["delete_gbn"]=="Y" || $_POST["delete_gbn"]=="N")) {
	$delete_gbn=$_POST["delete_gbn"];
	$sql = "SELECT COUNT(*) as cnt FROM tblorderproduct WHERE vender='".$vender."' ";
	$result=mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
	mysql_free_result($result);
	$cnt=$row->cnt;

	$sql="UPDATE tblshopcount SET vendercnt=vendercnt-1 ";
	mysql_query($sql,get_db_conn());

	if($cnt<=0) {
		mysql_query("DELETE FROM tblvenderinfo WHERE vender='".$vender."'",get_db_conn());
	} else {
		$sql = "UPDATE tblvenderinfo SET delflag='Y' ";
		$sql.= "WHERE vender='".$vender."' ";
		mysql_query($sql,get_db_conn());
	}
	mysql_query("DELETE FROM tblvenderstore WHERE vender='".$vender."'",get_db_conn());
	mysql_query("DELETE FROM tblvenderstorecount WHERE vender='".$vender."'",get_db_conn());
	mysql_query("DELETE FROM tblvenderstorevisit WHERE vender='".$vender."'",get_db_conn());
	mysql_query("DELETE FROM tblvendercodedesign WHERE vender='".$vender."'",get_db_conn());
	mysql_query("DELETE FROM tblregiststore WHERE vender='".$vender."'",get_db_conn());
	mysql_query("DELETE FROM tblvenderlog WHERE vender='".$vender."'",get_db_conn());
	mysql_query("DELETE FROM tblvenderthemecode vender='".$vender."'",get_db_conn());
	mysql_query("DELETE FROM tblvenderthemeproduct WHERE vender='".$vender."'",get_db_conn());
	mysql_query("DELETE FROM tblvenderspecialmain WHERE vender='".$vender."'",get_db_conn());
	mysql_query("DELETE FROM tblvenderspecialcode WHERE vender='".$vender."'",get_db_conn());
	mysql_query("DELETE FROM tblvendernotice WHERE vender='".$vender."'",get_db_conn());
	mysql_query("DELETE FROM tblvenderadminnotice WHERE vender='".$vender."'",get_db_conn());
	mysql_query("DELETE FROM tblvenderadminqna WHERE vender='".$vender."'",get_db_conn());
	mysql_query("DELETE FROM tblvenderaccount WHERE vender='".$vender."'",get_db_conn());
	mysql_query("DELETE FROM tblregiststore WHERE vender='".$vender."'",get_db_conn());

	/* 수수료 관련 추가 jdy */
	mysql_query("DELETE FROM vender_more_info WHERE vender='".$vender."'",get_db_conn());
	/* 수수료 관련 추가 jdy */
	
	// 그룹 삭제
	mysql_query("delete from vender_group_link where vender='".$vender."'",get_db_conn());


	mysql_query("optimize table tblvenderstorevisit");
	mysql_query("optimize table tblvenderlog");
	mysql_query("optimize table tblregiststore");

	//이미지 파일 삭제
	$storeimagepath=$Dir.DataDir."shopimages/vender/";
	proc_matchfiledel($storeimagepath."MAIN_".$vender.".*");
	proc_matchfiledel($storeimagepath."logo_".$vender.".*");
	proc_matchfiledel($storeimagepath.$vender."*");
	proc_matchfiledel($storeimagepath."aboutdeliinfo_".$vender."*");

	if($delete_gbn=="Y") {			//업체 상품 완전 삭제
		$sql = "SELECT productcode FROM tblproduct WHERE vender='".$vender."' ";
		$result=mysql_query($sql,get_db_conn());
		while($row=mysql_fetch_object($result)) {
			$prcode=$row->productcode;
			#태그관련 지우기
			$sql = "DELETE FROM tbltagproduct WHERE productcode = '".$prcode."'";
			mysql_query($sql,get_db_conn());

			#리뷰 지우기
			$sql = "DELETE FROM tblproductreview WHERE productcode = '".$prcode."'";
			mysql_query($sql,get_db_conn());

			#위시리스트 지우기
			$sql = "DELETE FROM tblwishlist WHERE productcode = '".$prcode."'";
			mysql_query($sql,get_db_conn());

			#관련상품 지우기
			$sql = "DELETE FROM tblcollection WHERE productcode = '".$prcode."'";
			mysql_query($sql,get_db_conn());

			$sql = "DELETE FROM tblproducttheme WHERE productcode = '".$prcode."'";
			mysql_query($sql,get_db_conn());

			$sql = "DELETE FROM tblproduct WHERE productcode = '".$prcode."'";
			mysql_query($sql,get_db_conn());

			$sql = "DELETE FROM tblproductgroupcode WHERE productcode = '".$prcode."'";
			mysql_query($sql,get_db_conn());

			$delshopimage = $Dir.DataDir."shopimages/product/".$prcode."*";
			proc_matchfiledel($delshopimage);

			delProductMultiImg("prdelete","",$prcode);
		}
		mysql_free_result($result);

		$log_content = "## 입점업체 삭제 ## - 업체ID : ".$_vdata->id." , [업체상품 삭제]";
		ShopManagerLog($_ShopInfo->getId(),$connect_ip,$log_content);
	} else if($delete_gbn=="N") {	//업체 상품 쇼핑몰 본사 상품으로 변경
		$sql = "UPDATE tblproduct SET vender=0 ";
		$sql.= "WHERE vender='".$vender."' ";
		mysql_query($sql,get_db_conn());

		$log_content = "## 입점업체 삭제 ## - 업체ID : ".$_vdata->id." , [업체상품 본사상품으로 변경]";
		ShopManagerLog($_ShopInfo->getId(),$connect_ip,$log_content);
	}

	echo "<html></head><body onload=\"alert('해당 입점업체 정보가 완전히 삭제되었습니다.');parent.document.form3.submit();\"></body></html>";exit;
}

$disabled=$_POST["disabled"];
$s_check=$_POST["s_check"];
$search=$_POST["search"];
$block=$_POST["block"];
$gotopage=$_POST["gotopage"];

?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>


<script language="javascript" type="text/javascript" src="/js/jquery-1.10.2.min.js"></script>
<script type="text/javascript">var $j= jQuery.noConflict();</script>
<script language="JavaScript">
function CheckForm() {
	form=document.form1;
	if(form.up_disabled[0].checked!=true && form.up_disabled[1].checked!=true) {
		alert("업체 승인여부를 선택하세요.");
		form.up_disabled[0].focus();
		return;
	}
	if(form.up_passwd.value.length>0) {
		if(form.up_passwd.value!=form.up_passwd2.value) {
			alert("변경할 비밀번호가 일치하지 않습니다."); form.up_passwd2.focus(); return;
		}
	}
	if(form.up_com_name.value.length==0) {
		alert("회사명을 입력하세요."); form.up_com_name.focus(); return;
	}
	/*
	if(form.up_com_num.value.length==0) {
		alert("사업자등록번호를 입력하세요."); form.up_com_num.focus(); return;
	}
	if(chkBizNo(form.up_com_num.value)==false) {
		alert("사업자등록번호가 잘못되었습니다."); form.up_com_num.focus(); return;
	}*/
	if(form.up_com_tel1.value.length==0 || form.up_com_tel2.value.length==0 || form.up_com_tel3.value.length==0) {
		alert("회사 대표전화를 정확히 입력하세요."); form.up_com_tel1.focus(); return;
	}
	if(form.up_p_name.value.length==0) {
		alert("담당자 이름을 입력하세요."); form.up_p_name.focus(); return;
	}
	if(form.up_p_mobile1.value.length==0 || form.up_p_mobile2.value.length==0 || form.up_p_mobile3.value.length==0) {
		alert("담당자 휴대전화를 정확히 입력하세요."); form.up_p_mobile1.focus(); return;
	}
	if(form.up_p_email.value.length==0) {
		alert("담당자 이메일을 입력하세요."); form.up_p_email.focus(); return;
	}
	if(IsMailCheck(form.up_p_email.value)==false) {
		alert("담당자 이메일을 정확히 입력하세요."); form.up_p_email.focus(); return;
	}

	if(form.up_account_date.value=='29' || form.up_account_date.value=='30' || form.up_account_date.value=='31') {
		alert("정산일로 사용할 수 없는 날짜 입니다."); form.up_account_date.focus(); return;
	}

	all_rate = document.getElementById("up_all_rate");
	up_c_type0 = document.getElementById("up_commission_type0");

	if (up_c_type0.checked) {
		if (typeof all_rate !="undefined") {

			if (all_rate.value != '' && Number(all_rate.value)>0) {

				if(!confirm("개별 수수료를 "+all_rate.value+"% 로 지정합니다. 계속하시겠습니까?")) {
					return;
				}
			}
		}
	}

	if(confirm("입점업체 정보를 수정하시겠습니까?")) {
		document.form1.type.value="update";
		document.form1.target="processFrame";
		document.form1.submit();
	}
}

function GoReturn() {
	document.form3.submit();
}

function CheckDelete() {
	if(confirm("해당 업체를 정말 삭제하시겠습니까?")) {
		if(confirm("해당 업체의 상품도 같이 삭제하시겠습니까?\n\n업체 상품을 같이 삭제할 경우 [확인]\n\n업체 상품을 쇼핑몰 본사 상품으로 변경하시려면 [취소] 버튼을 누르세요.")) {
			if(confirm("정말 해당 업체와 상품을 모두 삭제하시겠습니까?")) {
				document.form1.delete_gbn.value="Y";
				document.form1.type.value="delete";
				document.form1.target="processFrame";
				document.form1.submit();
			}
		} else {
			if(confirm("정말 해당 업체 삭제 후 업체 상품을 쇼핑몰 본사 상품으로 변경하시겠습니까?")) {
				document.form1.delete_gbn.value="N";
				document.form1.type.value="delete";
				document.form1.target="processFrame";
				document.form1.submit();
			}
		}
	}
}

function branddup(vender) {
	brand=document.form1.up_brand_name;
	if(brand.value.length==0) {
		alert("미니샵명을 입력하세요.");
		brand.focus();
		return;
	}
	window.open("vender_branddup.php?vender="+vender+"&brand_name="+brand.value,"","height=100,width=300,toolbar=no,menubar=no,scrollbars=no,status=no");
}

function f_addr_search(form,post,addr,gbn) {
	window.open("<?=$Dir.FrontDir?>addr_search.php?form="+form+"&post="+post+"&addr="+addr+"&gbn="+gbn,"f_post","resizable=yes,scrollbars=yes,x=100,y=200,width=370,height=250");
}

function trustView(vender) {
	window.open("vender_trustView.php?vender="+vender,"","height=600,width=724,toolbar=no,menubar=no,scrollbars=yes,status=no");
}

function selCommission(num) {

	c_td = document.getElementById("commission_all")
	c_tr = document.getElementById("commission_tr")

	if (num==1) {
		c_td.style.display="inline"
		if ( typeof c_tr != "undefined" ) {
			c_tr.style.display="none"
		}
	}else{
		c_td.style.display="none"
		if ( typeof c_tr != "undefined" ) {
			c_tr.style.display="inline"
		}
	}

	if (num==0) {
		alert("초기 개별상품 수수료는 0%로 설정되니 반드시 아래 개별수수료 일괄변경기능으로 수수료 설정 후 각 상품별 수수료를 조정하시기 바랍니다.\n변경수수료에 대한 정산반영은 총관리자가 해당상품주문단계를 배송완료로 처리한 시점에 적용됩니다.");
	}
}

function autoResize(ifr)
{

    var iframeHeight=ifr.contentWindow.document.body.scrollHeight;
    ifr.height=iframeHeight+20;

}

function setAccountDate(setType) {

	setValue = "";

	if (setType == 0) {

		for (i=0;i<31;i++) {

			if (setValue=="") {
				setValue = i+1;
			}else{

				setValue = setValue+","+(i+1);
			}
		}

	}else if (setType == 1) {

		for (i=0;i<31;i=i+2) {

			if (setValue=="") {
				setValue = i+1;
			}else{

				setValue = setValue+","+(i+1);
			}
		}

	}else if (setType == 2) {

		for (i=1;i<31;i=i+2) {

			if (setValue=="") {
				setValue = i+1;
			}else{

				setValue = setValue+","+(i+1);
			}
		}
	}

	document.form1.up_account_date.value = setValue;

}

function adjustChecked(num) {


	adjust = document.getElementById("adjust_div");
	if (num==0) {
		adjust.style.display = "";
	}else{
		adjust.style.display = "none";
	}

}


function pricetypeDivView(v) {

	cm_div = document.getElementById('pricetype_div');

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

function discountDivView(v) {

	cm_div = document.getElementById('discount_div');

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

function seasonDivView(v) {

	cm_div = document.getElementById('season_div');

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
		}else{
			cm_div.style.display="none";
		}
	}
}
function refundDivView(v) {

	cm_div = document.getElementById('refund_div');

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
<style type="text/css">
.infoListTbl{border:1px solid #CDDDE0;}
.infoListTbl th{ font-weight:bold; background:#efefef; border-right:1px solid #CDDDE0; border-bottom:1px solid #CDDDE0; font-size:11px;}
.infoListTbl td{  background:#fff; border-right:1px solid #CDDDE0; border-bottom:1px solid #CDDDE0;}
.infoListTbl .norbl{border-right:0px;}
.infoListTbl .nobbl{border-bottom:0px;}
</style>
<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
<tr>
	<td valign="top">
	<table cellpadding="0" cellspacing="0" width=100% style="table-layout:fixed">
	<tr>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed"  background="images/con_bg.gif">
		<col width=198></col>
		<col width=10></col>
		<col width=></col>
		<tr>
			<td valign="top"  background="images/leftmenu_bg.gif">
			<? include ("menu_vender.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">현재위치 : 입점관리 &gt; 입점업체 관리 &gt; <span class="2depth_select">입점업체 신규등록</span></td>
			</tr>
			</table>
		</td>
	</tr>
	<tr>
        <td width="16"><img src="images/con_t_01.gif" width="16" height="16" border="0"></td>
        <td background="images/con_t_01_bg.gif"></td>
        <td width="16"><img src="images/con_t_02.gif" width="16" height="16" border="0"></td>
    </tr>
    <tr>
        <td width="16" background="images/con_t_04_bg1.gif"></td>
        <td bgcolor="#ffffff" style="padding:10px">
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post enctype="multipart/form-data">
			<input type=hidden name=type>
			<input type=hidden name=delete_gbn>
			<input type=hidden name=vender value="<?=$vender?>">
			<table cellpadding="0" cellspacing="0" width="100%">
			
			<tr><td height="8"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/vender_new_title.gif"ALT=""></TD>
					</tr><tr>
					<TD width="100%" background="images/title_bg.gif" height="21"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height="3"></td>
			</tr>
			<tr>
				<td style="padding-bottom:3pt;">
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/distribute_01.gif"></TD>
					<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
					<TD><IMG SRC="images/distribute_03.gif"></TD>
				</TR>
				<TR>
					<TD background="images/distribute_04.gif"></TD>
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
					<TD width="100%" class="notice_blue">입점업체의 정보를 수정/삭제 하실 수 있습니다.</TD>
					<TD background="images/distribute_07.gif"></TD>
				</TR>
				<TR>
					<TD><IMG SRC="images/distribute_08.gif"></TD>
					<TD COLSPAN=2 background="images/distribute_09.gif"></TD>
					<TD><IMG SRC="images/distribute_10.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height=20></td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/vender_reg_stitle1.gif" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;<a href="javascript:document.location.reload()">[새로고침]</a></TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height=3></td>
			</tr>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif" style="height:1px"></TD>
				</TR>

				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">업체 승인</TD>
					<TD class="td_con1">
					<input type=radio name=up_disabled id=up_disabled0 value="0" <?if($_vdata->disabled=="0")echo"checked";?>> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=up_disabled0>승인</label>
					<img width=20 height=0>
					<input type=radio name=up_disabled id=up_disabled1 value="1" <?if($_vdata->disabled=="1" || strlen($_vdata->disabled)==0)echo"checked";?>> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=up_disabled1>보류</label>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">업체 ID</TD>
					<TD class="td_con1"><B><?=$_vdata->id?></B></TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">패스워드 변경</TD>
					<TD class="td_con1">
					<input type=password name=up_passwd value="" size=20 maxlength=12 class=input>
					&nbsp;&nbsp;
					<FONT class=font_orange>* 영문, 숫자를 혼용하여 사용(4자 ~ 12자)</font>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">패스워드 확인</TD>
					<TD class="td_con1">
					<input type=password name=up_passwd2 value="" size=20 maxlength=12 class=input>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif" style="height:1px"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height=20></td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/vender_reg_stitle2.gif" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height=3></td>
			</tr>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif" style="height:1px"></TD>
				</TR>				
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">판매자만족도</TD>
					<TD class="td_con1">
					<select name="starmark">
					<? for($i=0;$i<=5;$i++){ 
						$sel = ($_vdata->starmark == $i)?'selected':'';
					?>
						<option value="<?=$i?>" <?=$sel?>><?=$i?></option>
					<? } ?>
					</select>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">네임텍 이미지</TD>
					<TD class="td_con1">

						<div style="margin:5px;">
							<div style="float:left; margin:0px; padding:0px; font-size:0px;"><img src="<?=$com_image_url.$_vdata->com_image?>" width="120" onerror="this.src='/images/no_img.gif';" style="border:1px solid #dddddd;" /></div>
							<div style="float:left; margin-top:5px; margin-left:10px;">
								<div>
									<span style="font-size:11px; color:#666666; line-height:15px; letter-spacing:-1px;">
										<strong>사용유무 : </strong><input type="checkbox" name="com_nametech" value="1" <?=($_vdata->com_nametech?"checked":"");?>><br /><br /><br />
										※ <b>네임텍 이미지는??</b><br />
										<img src="images/vender_nametek_sample.gif" style="border:1px solid #e5e5e5;" hspace="8" vspace="4" alt="" /><br />
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
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">상호 (회사명)</TD>
					<TD class="td_con1">
					<input type=text name=up_com_name value="<?=$_vdata->com_name?>" size=20 maxlength=30 class=input>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">사업자등록번호</TD>
					<TD class="td_con1">
					<input type=text name=up_com_num value="<?=$_vdata->com_num?>" size=20 maxlength=20 onkeyup="strnumkeyup(this)" class=input>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">미니샵명</TD>
					<TD class="td_con1">
					<input type=text name=up_brand_name value="<?=$_vdata->brand_name?>" size=20 maxlength=30 class=input>
					<A class=board_list hideFocus style="selector-dummy: true" onfocus=this.blur(); href="javascript:branddup();"><IMG src="images/duple_check_img.gif" border=0 align="absmiddle"></A>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point5.gif" width="8" height="11" border="0">대표자 성명</TD>
					<TD class="td_con1">
					<input name=up_com_owner value="<?=$_vdata->com_owner?>" size=20 maxlength="12" class=input>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point5.gif" width="8" height="11" border="0">회사 주소</TD>
					<TD class="td_con1">
					<input type=text name="up_com_post1" id="up_com_post1" value="<?=$_vdata->com_post?>" size="5" maxlength="5" readonly class=input>
					<A class=board_list hideFocus style="selector-dummy: true" onfocus=this.blur(); href="javascript:addr_search_for_daumapi('up_com_post1','up_com_addr','');"><IMG src="images/order_no_uimg.gif" border=0 align="absmiddle"></A><br>
					<input type=text name="up_com_addr" id="up_com_addr" value="<?=$_vdata->com_addr?>" size=50 maxlength=150 class=input>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point5.gif" width="8" height="11" border="0">사업자 업태</TD>
					<TD class="td_con1">
					<input type="text" name=up_com_biz value="<?=$_vdata->com_biz?>" size=30 maxlength=30 class=input>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point5.gif" width="8" height="11" border="0">사업자 종목</TD>
					<TD class="td_con1">
					<input type=text name=up_com_item value="<?=$_vdata->com_item?>" size=30 maxlength=30 class=input>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>



				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point5.gif" width="8" height="11" border="0">통신판매신고</TD>
					<TD class="td_con1">
					<input type=text name=ec_num value="<?=$_vdata->ec_num?>" size=20 maxlength=20 class=input>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>



				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point5.gif" width="8" height="11" border="0">사업자구분</TD>
					<TD class="td_con1">
					<input type=text name=com_type value="<?=$_vdata->com_type?>" size=20 maxlength=20 class=input>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>



				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">회사 대표전화</TD>
					<TD class="td_con1">
					<input type=text name=up_com_tel1 value="<?=$com_tel[0]?>" size=4 maxlength=4 style="width:40" onkeyup="strnumkeyup(this)" class=input>-<input type=text name=up_com_tel2 value="<?=$com_tel[1]?>" size=4 maxlength=4 style="width:40" onkeyup="strnumkeyup(this)" class=input>-<input type=text name=up_com_tel3 value="<?=$com_tel[2]?>" size=4 maxlength=4 style="width:40" onkeyup="strnumkeyup(this)" class=input>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point5.gif" width="8" height="11" border="0">회사 팩스번호</TD>
					<TD class="td_con1">
					<input type=text name=up_com_fax1 value="<?=$com_fax[0]?>" size=4 maxlength=4 style="width:40" onkeyup="strnumkeyup(this)" class=input>-<input type=text name=up_com_fax2 value="<?=$com_fax[1]?>" size=4 maxlength=4 style="width:40" onkeyup="strnumkeyup(this)" class=input>-<input type=text name=up_com_fax3 value="<?=$com_fax[2]?>" size=4 maxlength=4 style="width:40" onkeyup="strnumkeyup(this)" class=input>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point5.gif" width="8" height="11" border="0">회사 홈페이지</TD>
					<TD class="td_con1">
					http://<input type=text name=up_com_homepage value="<?=$_vdata->com_homepage?>" size=30 maxlength=50 class=input>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif" style="height:1px"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height=20></td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/vender_reg_stitle3.gif" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height=3></td>
			</tr>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif" style="height:1px"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">담당자 이름</TD>
					<TD class="td_con1">
					<input type=text name=up_p_name value="<?=$_vdata->p_name?>" size=20 maxlength=20 class=input> &nbsp; <FONT class=font_orange>* 입점 담당자 이름을 정확히 입력하세요.</font>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">담당자 휴대전화</TD>
					<TD class="td_con1">
					<input type=text name=up_p_mobile1 value="<?=$p_mobile[0]?>" size=4 maxlength=3 style="width:40" onkeyup="strnumkeyup(this)" class=input>-<input type=text name=up_p_mobile2 value="<?=$p_mobile[1]?>" size=4 maxlength=4 style="width:40" onkeyup="strnumkeyup(this)" class=input>-<input type=text name=up_p_mobile3 value="<?=$p_mobile[2]?>" size=4 maxlength=4 style="width:40" onkeyup="strnumkeyup(this)" class=input></TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">담당자 이메일</TD>
					<TD class="td_con1">
					<input type=text name=up_p_email value="<?=$_vdata->p_email?>" size=30 maxlength=50 class=input> &nbsp; <FONT class=font_orange>* 주문확인시 담당자 이메일로 통보됩니다.</font>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point5.gif" width="8" height="11" border="0">담당자 부서명</TD>
					<TD class="td_con1">
					<input type=text name=up_p_buseo value="<?=$_vdata->p_buseo?>" size=20 maxlength=20 class=input>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point5.gif" width="8" height="11" border="0">담당자 직위</TD>
					<TD class="td_con1">
					<input type=text name=up_p_level value="<?=$_vdata->p_level?>" size=20 maxlength=20 class=input>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif" style="height:1px"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height=20></td>
			</tr>
			<tr>
				<td>
					<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
						<TR>
							<TD><IMG SRC="images/vender_reg_stitle4.gif" ALT=""></TD>
							<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
							<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
						</TR>
					</TABLE>
				</td>
			</tr>
			<tr>
				<td height=3></td>
			</tr>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif" style="height:1px"></TD>
				</TR>
				<?
				$vgrouplist = array();
				$sql = "select * from vender_group order by vgyearsell desc,vgcommi_self desc,vgcommi_main desc";
				if(false !== $res = mysql_query($sql,get_db_conn())){
					if(0 < $vno = mysql_num_rows($res)){		
						while($row = mysql_fetch_assoc($res)){
							$row['vno'] = $vno++;
							array_push($vgrouplist,$row);
						}
					}
				}
				
				$vgidx = '';
				if(false !== $lres = mysql_query("select vgidx from vender_group_link where vender='".$_vmdata->vender."' limit 1",get_db_conn())){
					if(mysql_num_rows($lres)) $vgidx = mysql_result($lres,0,0);
				}
				?>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">그룹</TD>
					<TD class="td_con1">
					<select name="vgidx" id="vgidx" onchange="javascript:changeGroup();" vgidx="<?=$vgroup['vgidx']?>">
						<option value="">-- 그룹 --</option>
					<?	if(_array($vgrouplist)){ 
							foreach($vgrouplist as $vgroup){ 
								$sel = ($vgidx == $vgroup['vgidx'])?'selected="selected"':''
							?>
						<option value="<?=$vgroup['vgidx']?>" commi_self="<?=$vgroup['vgcommi_self']?>"  commi_main="<?=$vgroup['vgcommi_main']?>" <?=$sel?>><?=$vgroup['vgname']?></option>
						
					<?		}
						} ?>
					</select>
					<span id="commiText" style="padding-left:10px;"></span>
					<script language="javascript" type="text/javascript">
					var commi_self = parseFloat('<?=$shop_more_info['commi_self']?>');
					var commi_main = parseFloat('<?=$shop_more_info['commi_main']?>');
					
					function changeGroup(){
						var ovgidx = $j('#vgidx').attr('vgidx');
						var sel = $j('#vgidx').find('option:selected');
						var vgcommi_self = parseFloat($j(sel).attr('commi_self'));
						var vgcommi_main = parseFloat($j(sel).attr('commi_main'));
						
						if(isNaN(vgcommi_self)) vgcommi_self = 0;
						if(isNaN(vgcommi_main)) vgcommi_main = 0;
						if(vgcommi_self > 0 || vgcommi_main > 0){
							totalcommi_main = commi_main-vgcommi_main;
							if (totalcommi_main<0) totalcommi_main = 0;
							else totalcommi_main = totalcommi_main;

							totalcommi_self = commi_self-vgcommi_self;
							if (totalcommi_self<0) totalcommi_self = 0;
							else totalcommi_self = totalcommi_self;

							html = '감면 수수료 (셀프 : '+vgcommi_self+'%  /  위탁 : '+vgcommi_main+'% )';
							html2 = '수수료 (셀프 : <span style="color:#0C0;">'+commi_self+'</span>- '+vgcommi_self+'='+totalcommi_self+'%  /  위탁 : <span style="color:#0C0;">'+commi_main+'</span>- '+vgcommi_main+'='+totalcommi_main+'%)';
						}else{
							html = '';
							html2 = '수수료 (셀프 : <span style="color:#0C0;">'+commi_self+'</span>%  /  위탁 : <span style="color:#0C0;">'+commi_main+'</span>%)';
						}
						$j('#commiText').html(html);
						$j('#txtCommiArea').html(html2);
					}
					
					$j(function(){
						changeGroup();
					});
					</script>
					</TD>
				</TR>
				<TR>
					<TD colspan=2 background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<tr>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">대여수수료</TD>
					<TD class="td_con1" id="txtCommiArea">
					
					</TD>
				</tr>
				<TR>
					<TD colspan=2 background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<? if ($account_rule !="1") { ?>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">판매상품 수수료 타입</TD>
					<TD class="td_con1">
						<input type=radio name=up_commission_type id=up_commission_type0 value="1" <?if($_vmdata->commission_type=="1") echo"checked";?> onclick="selCommission('0');"> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=up_commission_type0>상품개별 수수료</label>
						<img width=20 height=0>
						<input type=radio name=up_commission_type id=up_commission_type1 value="0" <?if($_vmdata->commission_type=="0" || strlen($_vmdata->commission_type)==0) echo "checked";?>  onclick="selCommission('1');"> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=up_commission_type1>전체상품 동일 수수료</label>
						<br><span class="font_blue">&nbsp;* 입점사와의 협정 판매수수료는 부가세 별도로 정산식 처리 시 산정.<br>
						&nbsp;&nbsp;(입점사의 경우 과세, 간이과세, 면세, 개인사업자 등 복합적으로 입점가능하므로 판매수수료의 부가세는 별도로 정산시 산정)</span>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
			</TABLE>

			<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<? if ($account_rule !='1') { ?>
				<TR id="commission_tr" <? if ($_vmdata->commission_type!="1") { ?> style="display:none" <? } ?>>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">개별상품 판매 수수료 일괄변경</TD>
					<TD class="td_con1">
						<input type=text name=up_all_rate id="up_all_rate" value="" size=3 maxlength=3 onkeyup="strnumkeyup(this)" class=input>%
						&nbsp;&nbsp;&nbsp;&nbsp; <FONT class=font_orange>* 0보다 큰수치를 입력할 경우 등록되어 있는 상품 모두에 일괄적으로 수수료를 변경시킬수 있습니다.</font>
					</TD>
				</TR>
				<? } ?>

				<TR id="commission_all" <? if ($_vmdata->commission_type=="1") { ?> style="display:none" <? } ?>>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">전체상품 판매 수수료</TD>
					<TD class="td_con1">
						<input type=text name=up_rate value="<?=$_vdata->rate?>" size=3 maxlength=3 onkeyup="strnumkeyup(this)" class=input>%
						&nbsp;&nbsp;&nbsp;&nbsp; <FONT class=font_orange>* 모든상품에 동일 적용됩니다.</font>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<? }else{ ?>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">수수료 운영형태</TD>
					<TD class="td_con1">
						<input type=hidden name=up_commission_type value="1" />
						<input type=hidden name=up_rate value="0" />
						상품개별 공급가
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<? } ?>
			</TABLE>

			<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">상품 처리 권한</TD>
					<TD class="td_con1">
					<input type=checkbox name=chk_prdt1 id=idx_chk_prdt1 value="Y" <?if(substr($_vdata->grant_product,0,1)=="Y")echo"checked";?>><label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_chk_prdt1>등록</label>
					<img width=20 height=0>
					<input type=checkbox name=chk_prdt2 id=idx_chk_prdt2 value="Y" <?if(substr($_vdata->grant_product,1,1)=="Y")echo"checked";?>><label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_chk_prdt2>수정</label>
					<img width=20 height=0>
					<input type=checkbox name=chk_prdt3 id=idx_chk_prdt3 value="Y" <?if(substr($_vdata->grant_product,2,1)=="Y")echo"checked";?>><label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_chk_prdt3>삭제</label>
					<img width=50 height=0>
					<input type=checkbox name=chk_prdt4 id=idx_chk_prdt4 value="Y" <?if(substr($_vdata->grant_product,3,1)=="Y")echo"checked";?>><label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_chk_prdt4>등록/수정시, 관리자 인증</label>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">입점 상품수 제한(<?=$_vdata->product_max?>)</TD>
					<TD class="td_con1">
						<input type=text name=up_product_max value="<?=$_vdata->product_max?>" size=3 onkeyup="strnumkeyup(this)" class=input>
						개 까지 상품등록 가능
						<br/>
						<FONT class=font_orange>* 무제한으로 설정할 시에는 <span class="font_blue">0</span>을 입력해주세요.</font>
						<!--
						<select name=up_product_max class="select">
						<option value="0" <?if($_vdata->product_max==0)echo"selected";?>>무제한</option>
						<option value="50" <?if($_vdata->product_max==50)echo"selected";?>>50</option>
						<option value="100" <?if($_vdata->product_max==100)echo"selected";?>>100</option>
						<option value="150" <?if($_vdata->product_max==150)echo"selected";?>>150</option>
						<option value="200" <?if($_vdata->product_max==200)echo"selected";?>>200</option>
						<option value="250" <?if($_vdata->product_max==250)echo"selected";?>>250</option>
						<option value="300" <?if($_vdata->product_max==300)echo"selected";?>>300</option>
						</select> 개 까지 상품등록 가능
						-->
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<!--
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">판매 수수료</TD>
					<TD class="td_con1">
						<input type=text name=up_rate value="<?=$_vdata->rate?>" size=3 maxlength=3 onkeyup="strnumkeyup(this)" class=input>%
						&nbsp;&nbsp;&nbsp;&nbsp; <FONT class=font_orange>* 쇼핑몰 본사에서 받는 상품판매 수수료를 입력하세요.</font>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				-->


				<? /*추가 gura */?>
				<tr>
					<td class="table_cell"><B>점포 속성</td>
					<td class="td_con1">
						<select name="category">
							<option value="">선택하세요</option>
					<?
					$tmp = getCategoryItems();
					foreach($tmp['items'] as $item){
						if($item['codeA']==$_vdata->category) $selected = "selected";
						else $selected = "";
						echo "<option value='".$item['codeA']."' ".$selected.">".$item['code_name']."</option>";
					}
					
					?>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td class="table_cell"><B>대여가능시간</td>
					<td class="td_con1">
						시작: <input type="text" name="rent_stime" id="rent_stime" size="3" maxlength="2" value="<?=$_ptdata->checkin_time?>">시 ~
						종료: <input type="text" name="rent_etime" id="rent_etime" size="3" maxlength="2" value="<?=$_ptdata->checkout_time?>">시 
						*24시간 대여인 경우 시작과 종료시간을 같게 설정하세요.
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td class="table_cell"><B>과금방식 설정</td>
					<td class="td_con1">
					<input type=radio name=pricetype value="0" <?if($_vdata->pricetype=="0")echo"checked";?> onclick="pricetypeDivView('N')">본사 정책에 따름&nbsp;&nbsp;
					<input type=radio name=pricetype value="1" <?if($_vdata->pricetype=="1")echo"checked";?> onclick="pricetypeDivView()">입점업체 고유 설정
					<? if($_vdata->pricetype=="1"){ $display=""; }else{ $display="none"; } ?>
					<div id="pricetype_div" style="width:700px;margin-top:5px;z-index:999;padding:5px;display:<?=$display?>;">
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
								html = '<div>당일 12시간 요금: 24시간 요금의 <input type="text" name="halfday_percent" size="3" maxlength="2">%</div>';
								$j('#price1').html(html);
							}else{
								html = '';
								$j('#price1').html(html);
							}
							
						}

						function onedayexCheck(val){			
							if(val=="time"){
								html = '<div>추가 1시간 요금: 24시간 요금의 <input type="text" name="time_percent" size="3" maxlength="2">%</div>';
								$j('#price2').html(html);
							}else if(val=="half"){
								html = '<div>추가 12시간 요금: 24시간 요금의 <input type="text" name="time_percent" size="3" maxlength="2">%</div>';
								$j('#price2').html(html);
							}else{
								html = '';
								$j('#price2').html(html);
							}
							
						}
						</script>
						<table cellpadding="0" cellspacing="0" class="infoListTbl" style="width:100%;margin-top:7px;padding:7px 7px 7px 7px; border-bottom:0px;border:2px solid #acacac;background-color:#ffffff">
							<tr>
								<th style="width:100px;">과금방식</th>
								<td class="norbl" style="padding:5px;">
									<select name="vender_rent" id="vender_rent" onchange="javascript:chPriceType()">
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
									<table id="day_div" class="infoListTbl" cellpadding="0" cellspacing="0" style="display:<?=$display?>;width:100%;margin-top:7px;padding:7px 7px 7px 7px;">
										<tr>
											<th style="width:150px;">당일 12시간 대여허용</th>
											<td style="padding:5px;">
												<input type=radio name=halfday value="Y" <?if($_ptdata->halfday=="Y")echo"checked";?> onclick="halfdayCheck('Y')">예
												<input type=radio name=halfday value="N" <?if($_ptdata->halfday=="N")echo"checked";?> onclick="halfdayCheck('N')">아니오
											</td>
											<td id="price1" style="width:300px">
												<?
												if($_ptdata->halfday=="Y"){
													echo '<div>당일 12시간 요금: 24시간 요금의 <input type="text" name="halfday_percent" size="3" maxlength="2" value="'.$_ptdata->halfday_percent.'">%</div>';
												}
												?>
											</td>
										</tr>
										<tr>
											<th>1일 초과시 과금기준</th>
											<td style="padding:5px;">
												<input type=radio name=oneday_ex value="day" <?if($_ptdata->oneday_ex=="day")echo"checked";?> onclick="onedayexCheck('day')">1일 단위
												<input type=radio name=oneday_ex value="half" <?if($_ptdata->oneday_ex=="half")echo"checked";?> onclick="onedayexCheck('half')">12시간 단위
												<input type=radio name=oneday_ex value="time" <?if($_ptdata->oneday_ex=="time")echo"checked";?> onclick="onedayexCheck('time')">1시간 단위
											</td>
											<td id="price2" style="width:300px">
												<?
												if($_ptdata->oneday_ex=="time"){
													echo '<div>추가 12시간 요금: 24시간 요금의 <input type="text" name="time_percent" size="3" maxlength="2" value="'.$_ptdata->time_percent.'">%</div>';
												}else if($_ptdata->oneday_ex=="half"){
													echo '<div>추가 12시간 요금: 24시간 요금의 <input type="text" name="time_percent" size="3" maxlength="2" value="'.$_ptdata->time_percent.'">%</div>';
												}
												?>
											</td>
										</tr>
									</table>
									<? if($_ptdata->pricetype == 'time') $display = ""; else $display = "none"; ?>
									<table id="time_div" class="infoListTbl" cellpadding="0" cellspacing="0" style="display:<?=$display?>;width:100%;margin-top:7px;padding:7px 7px 7px 7px;">
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
									<table id="checkout_div" class="infoListTbl" cellpadding="0" cellspacing="0" style="display:<?=$display?>;width:100%;margin-top:7px;padding:7px 7px 7px 7px;">
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
												<input type="text" name="base_period" size="5" value="<?=$_ptdata->base_period?>"  onkeyup="javascript:$j('#addLongrent_sday').val(parseInt($j('input[name=base_period]').val())+1);">일 까지
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
					<td style=padding:7,10>
					<input type=radio name=longrent value="0" <?if($_vdata->longrent=="0")echo"checked";?> onclick="longrentDivView('N')">본사 정책에 따름 
					<input type=radio name=longrent value="1" <?if($_vdata->longrent=="1")echo"checked";?> onclick="longrentDivView()">입점업체 고유 설정

					<div id="longrentHelpDiv" style="width:250px; padding:10px; height:60px; position:absolute; background:#efefef; border:1px solid #FF0; display:none"></div>
					<? 
					$longrentinfo = venderLongrentCharge($vender);				
					?>
					<? if($_vdata->longrent=="1"){ $display=""; }else{ $display="none"; } ?>
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
							}else if(isNaN(p) || p < 1|| p>100){
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
						<table cellpadding="0" cellspacing="0" class="infoListTbl" style="margin-top:7px;padding:7px 7px 7px 7px; border-bottom:0px;border:2px solid #acacac;background-color:#ffffff">
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
					<td class="table_cell"><B>환불 정책</td>
					<td class="td_con1">
						<input type=radio name=refund value="0" <?if($_vdata->refund=="0")echo"checked";?> onclick="refundDivView('N')">본사 정책에 따름&nbsp;&nbsp;
						<input type=radio name=refund value="1" <?if($_vdata->refund=="1")echo"checked";?> onclick="refundDivView()">입점업체 고유 설정

						<? if($_vdata->refund=="1"){ $display=""; }else{ $display="none"; } ?>
						<div id="refund_div" style="width:600px;margin-top:5px;z-index:999;padding:5px;display:<?=$display?>;">
							<style type="text/css">
							#refundDiv div{ width:30%; margin-right:3px;; float:left; padding:5px; background:#f4f4f4}
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
							</script>

							<table cellpadding="0" cellspacing="0" class="infoListTbl" style="margin-top:7px;padding:7px 7px 7px 7px; border-bottom:0px;border:2px solid #acacac;background-color:#ffffff">
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
								</tr>
							</table>
							<div style="width:100%; padding:3px 0px; clear:both" id="refundDiv">
							<? 
							$refundinfo = venderRefundCommission($vender);				
							?>
							
								<? if(_array($refundinfo)){
								foreach($refundinfo as $rday=>$rpercent){ ?>
								<div>
									<input type="hidden" name="refundday[]" value="<?=$rday?>">
									<input type="hidden" name="refundpercent[]" value="<?=$rpercent?>">
									<span style="float:left">
									<?=$rday?>
									일전
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
					<td class="table_cell"><B>장기할인 설정</td>
					<td class="td_con1">
						<input type=radio name=longdiscount value="0" <?if($_vdata->longdiscount=="0")echo"checked";?> onclick="discountDivView('N')">본사 정책에 따름&nbsp;&nbsp;
						<input type=radio name=longdiscount value="1" <?if($_vdata->longdiscount=="1")echo"checked";?> onclick="discountDivView()">입점업체 고유 설정

						<? if($_vdata->longdiscount=="1"){ $display=""; }else{ $display="none"; } ?>
						<div id="discount_div" style="position:;width:600px;margin-top:5px;z-index:999;padding:5px;display:<?=$display?>;">
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
							<table cellpadding="0" cellspacing="0" class="infoListTbl" style="margin-top:7px;padding:7px 7px 7px 7px; border-bottom:0px;border:2px solid #acacac;background-color:#ffffff">
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
							$ldiscinfo = venderLongDiscount($vender);
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
									%할인</span><img src="../admin/images/btn_del.gif" alt="삭제" align="right" /></div>
								<?	}
							}?>
							</div>

						</div>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td class="table_cell"><B>성수기 설정</td>
					<td class="td_con1">
						<input type=radio name=useseason value="2" <?if($_vdata->season=="2")echo"checked";?> onclick="seasonDivView('N')">본사 정책에 따름&nbsp;&nbsp;
						<input type=radio name=useseason value="0" <?if($_vdata->season=="0")echo"checked";?> onclick="seasonDivView('N')">사용안함&nbsp;&nbsp;
						<input type=radio name=useseason value="1" <?if($_vdata->season=="1")echo"checked";?> onclick="seasonDivView()">입점업체 고유 성수기/비성수기 사용

						<? if($_vdata->season=="1"){ $display=""; }else{ $display="none"; } ?>
						<div id="season_div" style="position:;width:600px;margin-top:5px;z-index:999;padding:5px;display:<?=$display?>;">
								
							<div id="seasonDiv" style="border:1px solid #efefefe"> 
								<table cellpadding="0" cellspacing="0" width="100%" id="seasonListTbl" class="infoListTbl" style="margin-top:7px;padding:7px 7px 7px 7px; border-bottom:0px;border:2px solid #acacac;background-color:#ffffff">
									</tr>
										<th style="width:120px;">성수기/준성수기</th>
										<td class="norbl" style="padding:5px;">
											<input type="button" value="성수기/준성수기 관리" style="width:200px;" onclick="window.open('../vender/vender_seasonpop.php?vender=<?=$vender?>', 'busySeasonPop', 'width=800,height=600' );">
										</td>
									</tr>
									<tr>
										<th class="nobbl">공휴일/주말</th>
										<td style="padding:5px;" class="norbl nobbl">
											<input type="button" value="공휴일/주말 관리" style="width:200px;"  onclick="window.open('../vender/vender_holiday.php?vender=<?=$vender?>', 'holidayPop', 'width=800,height=600' );">
										</td>
									</tr>
								</table>
							</div>

						</div>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<? /*추가 gura */?>


				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point5.gif" width="8" height="11" border="0">정산 계좌정보</TD>
					<TD class="td_con1">
						은행 
						<select name="up_bank1" class=input>
							<?
								$bankinfoArray  = calcSetBankinfo();
								foreach ( $bankinfoArray as $k => $v ){
									if( $bank_account[0] == $v ) {
										$sel = "selected";
									} else{
										$sel = "";
									}
									echo "<option value='".$v."'  ".$sel.">".$v."</option>";
								}
							?>
						</select>
						<img width=20 height=0>
						계좌번호 <input type=text name=up_bank2 value="<?=$bank_account[1]?>" size=20 class=input>
						<img width=20 height=0>
						예금주 <input type=text name=up_bank3 value="<?=$bank_account[2]?>" size=15 class=input>
						<BR />
						<span style="color:#ffffff; background-color:#ff4400;padding:4px 5px 2px 5px;cursor:pointer; font-size:11px; letter-spacing:-1px; font-weight:bold;" onclick="subMallIche(<?=$vender?>);">정산 지급대행 등록/수정</span> (지급대행 등록시 은행정보는 변경가능하며, 지급대행에 등록된 은행정보는 위 정보와 상이 할 수 있습니다!)
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point5.gif" width="8" height="11" border="0">정산일(매월)</TD>
					<TD class="td_con1">

						<input type="radio" name="adjust_lastday" id="adjust_lastday_0" value="0" <? if ($_vmdata->adjust_lastday==0) {?>checked="checked"<? } ?> onclick="adjustChecked('0')"> <label for="adjust_lastday_0">직접지정</label>&nbsp;&nbsp;
						<input type="radio" name="adjust_lastday" id="adjust_lastday_1" value="1" <? if ($_vmdata->adjust_lastday==1) {?>checked="checked"<? } ?> onclick="adjustChecked('1')"> <label for="adjust_lastday_0">매월마지막일</label>&nbsp;&nbsp;
						<input type="radio" name="adjust_lastday" id="adjust_lastday_2" value="2" <? if ($_vmdata->adjust_lastday==2) {?>checked="checked"<? } ?> onclick="adjustChecked('2')"> <label for="adjust_lastday_0">15일과 매월마지막일</label>

						<div id="adjust_div" <? if ($_vmdata->adjust_lastday>0) {?>style="display:none;"<?}?>>
						<input type=text name=up_account_date value="<?=$_vdata->account_date?>" size=75 class=input>일
						&nbsp;&nbsp;&nbsp;&nbsp;
						<span style="color:#ffffff;background-color:#000000;padding:4px 5px 2px 5px;cursor:pointer; font-size:11px; letter-spacing:-1px; font-weight:bold;" onclick="setAccountDate(0)">매일</span>&nbsp;
						<span style="color:#ffffff;background-color:#000000;padding:4px 5px 2px 5px;cursor:pointer; font-size:11px; letter-spacing:-1px; font-weight:bold;" onclick="setAccountDate(2)">짝수격일</span>&nbsp;
						<span style="color:#ffffff;background-color:#000000;padding:4px 5px 2px 5px;cursor:pointer; font-size:11px; letter-spacing:-1px; font-weight:bold;" onclick="setAccountDate(1)">홀수격일</span>
						<br/>
						<FONT class=font_orange>* 복수기입시 10,20,30 과 같이 기입, 단일 기입시 2월달로 인해 29,30,31은 사용할수 없음</font>
						</div>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
			<? /* 추가 jdy */?>
				<TR>
					<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">결산일</TD>
					<TD class="td_con1">정산일 기준
						<input type=text name=up_close_date value="<?=$_vmdata->close_date?>" size=10 class=input onkeyup="strnumkeyup(this)" >일 전까지 결산
						&nbsp;&nbsp; <FONT class=font_orange>* (정산일보다 1주일전까지의 주문을 정산할 경우 7을 입력. 반드시 1보다 큰수를 입력, <b>페이지 하단 매뉴얼 참조</b>)</font>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>

				<? if ($reserve_use || $coupon_use) {?>
				<input type="hidden" name="up_reserve_use" id="up_reserve_use0" value="0" />
				<!--
				<tr>
					<td class="table_cell"><img src="images/icon_point5.gif" border="0">혜택 사용 여부</td>
					<td class="td_con1">

						<? if ($reserve_use) {?>
						<b>적립금 : </b>
						<input type=radio name=up_reserve_use id=up_reserve_use1 value="1" <?if($_vmdata->reserve_use=="1")echo"checked";?>> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=up_reserve_use1>사용</label>
						<img width=20 height=0>
						<input type=radio name=up_reserve_use id=up_reserve_use0 value="0" <?if($_vmdata->reserve_use=="0" || strlen($_vmdata->reserve_use)==0)echo"checked";?>> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=up_reserve_use0>사용 안함</label>
						<br/>
						<? } ?>

						<!-- <? if ($coupon_use) {?>
						<b>쿠폰 : </b>
						<input type=radio name=up_coupon_use id=up_coupon_use1 value="1" <?if($_vmdata->coupon_use=="1")echo"checked";?>> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=up_coupon_use1>사용</label>
						<img width=20 height=0>
						<input type=radio name=up_coupon_use id=up_coupon_use0 value="0" <?if($_vmdata->coupon_use=="0" || strlen($_vmdata->coupon_use)==0)echo"checked";?>> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=up_coupon_use0>사용 안함</label>
						<br/>
						<? } ?> --//>
						<span class="font_blue">
						* 사용불가 체크 시 입점사는 혜택을 사용할 수 없으며 해당메뉴가 입점사 관리모드에 노출되지 않습니다.
						</span>
					</td>
				</tr>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				-->
				<? } ?>

				<? if (!$reserve_use) { ?>
					<input type=hidden name=up_reserve_use value="0" />
				<? } ?>
				<? if (!$coupon_use) { ?>
					<input type=hidden name=up_coupon_use value="0" />
				<? } ?>

			<? /* 추가 jdy */?>

				<TR>
					<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">기타사항</TD>
					<TD class="td_con1">
						<textarea name="up_etc" cols="80" rows="5" ><?= $_vmdata->etc ?></textarea>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">관리자메모</TD>
					<TD class="td_con1">
						<textarea name="up_admin_memo" cols="80" rows="5" ><?= $_vmdata->admin_memo ?></textarea>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">수수료정책변경<br/>히스토리</TD>
					<TD class="td_con1">
					<iframe src="vender_ch_pop.php?vender=<?=$vender ?>&type=if" width="780" height="100" frameborder=0 framespacing=0 marginheight=0 marginwidth=0 scrolling=no vspace=0 onload="autoResize(this)" ></iframe>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">위탁계약관리</TD>
					<TD class="td_con1">
						<?
						$sql = "SELECT * FROM tbltrustmanage WHERE vender='".$vender."'";
						$result=mysql_query($sql,get_db_conn());
						$data=mysql_fetch_object($result);

						if($data->approve=="Y"){
						?>
							<p>위탁관리 업체등록이 완료되었습니다. [<a href="javascript:trustView('<?=$vender?>')">정보보기</a>]</p>
						<?
						}else if($data->approve=="N"){
						?>
							<p>위탁관리 업체등록 신청을 했습니다. [<a href="javascript:trustView('<?=$vender?>')">정보보기</a>]</p>
						<?
						}else if($data->approve=="R"){
						?>
							<p>위탁관리 업체등록 신청이 거절되었습니다. [<a href="javascript:trustView('<?=$vender?>')">정보보기</a>]</p>
						<?
						}else if($data->approve=="C"){
						?>
							<p>위탁관리 업체등록 취소되었습니다. [<a href="javascript:trustView('<?=$vender?>')">정보보기</a>]</p>
						<?
						}
						?>

						<?
						$sql = "SELECT ta.ta_idx,ta.give_vender,ta.take_vender FROM tbltrustagree ta ";
						$sql.= "WHERE (ta.give_vender='".$vender."' OR ta.take_vender='".$vender."') ";
						$result=mysql_query($sql,get_db_conn());

						if(mysql_num_rows($result)){
						?>
						<iframe src="vender_trust.php?vender=<?=$vender?>" width="780" height="100" frameborder=0 framespacing=0 marginheight=0 marginwidth=0 scrolling=no vspace=0 onload="autoResize(this)" ></iframe>
						<?
						}
						?>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif" style="height:1px"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height=20></td>
			</tr>
			<tr>
				<td align="center">
					<a href="javascript:CheckForm();"><img src="images/btn_edit2.gif" width="113" height="38" border="0"></a>
					&nbsp;
					<a href="javascript:CheckDelete();"><img src="images/btn_infodelete.gif" width="113" height="38" border="0"></a>
				</td>
			</tr>
			
			<tr>
				<td height=20></td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/manual_top1.gif" WIDTH=15 height="45" ALT=""></TD>
					<TD><IMG SRC="images/manual_title.gif" WIDTH=113 height="45" ALT=""></TD>
					<TD width="100%" background="images/manual_bg.gif" height="35"></TD>
					<TD background="images/manual_bg.gif">&nbsp;</TD>
					<td background="images/manual_bg.gif"><IMG SRC="images/manual_top2.gif" WIDTH=18 height="45" ALT=""></td>
				</TR>
				<TR>
					<TD background="images/manual_left1.gif"></TD>
					<TD COLSPAN=3 width="100%" valign="top" class="menual_bg" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td class="menual_con"><img src="images/icon_8.gif" border="0" align="absmiddle"><span class="font_dotline">정산관련 용어정리</span></td>
					</tr>
					<tr>
						<td  class="space_top"><span style="padding-left:13px">- 정산금액 : 설정한 정산산정기간 동안의 입점업체 배송완료상품의 총 매출에서 판매수수료, 입점사지급 적립금, 쿠폰혜택을 빼고 배송료를 더한 금액을 산출한 실 결제금액<br/>
						<span style="padding-left:13px">- 정산기준일 : 거래된 매출 중 정산금액이 산정되는 기간<br/>
						<span style="padding-left:13px">- 결산일 : 정산기준일의 마지막 날짜(마감일)<br/>
						<span style="padding-left:13px">- 정산일 : 정산기준일의 정산금액을 입점업체에게 결제(입금)하는 날짜<br/>
						<span style="padding-left:13px">- 정산조회일 : 정산금액을 조회하는 날짜
						</td>
					</tr>
					<tr><td height="20"></td></tr>

					<tr>
						<td class="menual_con"><img src="images/icon_8.gif" border="0" align="absmiddle"><span class="font_dotline">정산산출 예) </span></td>
					</tr>
					<tr>
						<td  class="space_top">
						<span style="padding-left:13px">* A업체가 정산일이 매월10일 1회 이고, 결산일이 정산일로 부터 5일 이전인 경우<br/>
						<span style="padding-left:13px">- 정산기준일 : 이전달 6일 ~ 이번달5일<br/>
						<span style="padding-left:13px">- 결산일 : 매달 5일
						</td>
					</tr>
					<tr><td height="20"></td></tr>
					<tr>
						<td class="menual_con"><img src="images/icon_8.gif" border="0" align="absmiddle"><span class="font_dotline">복수정산 응용산출 예) </span></td>
					</tr>
					<tr>
						<td  class="space_top">
						<span style="padding-left:13px">* B업체가 정산일이 매월 5일, 10일, 15일, 20일, 25일, 30일  6회 이고, 결산일이 정산일로 부터 5일 이전인 경우<br/>
						<span style="padding-left:13px">- 정산기준일 : 지난달 26일~지난달 말일(5일 정산), 이번달 1일~이번달 5일(10일 정산), 6일~10일(15일 정산), 11일~15일(20일 정산), 16일~20일(25일 정산), 21일~ 25일(30일 정산)<br/>
						<span style="padding-left:13px">- 결산일 : 매달 말일, 5일, 10일, 15일, 20일, 25일
						</td>
					</tr>
					<tr><td height="20"></td></tr>
					</table>
					</TD>
					<TD background="images/manual_right1.gif"></TD>
				</TR>
				<TR>
					<TD><IMG SRC="images/manual_left2.gif" WIDTH=15 HEIGHT=8 ALT=""></TD>
					<TD COLSPAN=3 background="images/manual_down.gif"></TD>
					<TD><IMG SRC="images/manual_right2.gif" WIDTH=18 HEIGHT=8 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height="50"></td>
			</tr>			
			</table>
			</form>
			<form name="form3" method="post" action="vender_management.php">
			<input type=hidden name='vender' value="<?=$vender?>">
			<input type=hidden name='disabled' value='<?=$disabled?>'>
			<input type=hidden name='s_check' value='<?=$s_check?>'>
			<input type=hidden name='search' value='<?=$search?>'>
			<input type=hidden name='block' value='<?=$block?>'>
			<input type=hidden name='gotopage' value='<?=$gotopage?>'>
			</form>
		</td>
        <td width="16" background="images/con_t_02_bg.gif"></td>
    </tr>
    <tr>
        <td width="16"><img src="images/con_t_04.gif" width="16" height="16" border="0"></td>
        <td background="images/con_t_04_bg.gif"></td>
        <td width="16"><img src="images/con_t_03.gif" width="16" height="16" border="0"></td>
    </tr>
    <tr><td height="20"></td></tr>
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