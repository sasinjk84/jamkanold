<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");
include_once($Dir."lib/admin_more.php");
include_once($Dir.'service/subMallIche/bankcode.php');

####################### ������ ���ٱ��� check ###############
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
	echo "<script>alert('�ش� ��ü ������ �������� �ʽ��ϴ�.');history.go(-1);</script>";
	exit;
}
mysql_free_result($result);

$com_tel=explode("-",$_vdata->com_tel);
$com_fax=explode("-",$_vdata->com_fax);
$p_mobile=explode("-",$_vdata->p_mobile);
$bank_account=explode("=",$_vdata->bank_account);

$_vdata->checkin_time = $_vdata->checkin_time? $_vdata->checkin_time : "9";
$_vdata->checkout_time = $_vdata->checkout_time? $_vdata->checkout_time : "21";


/* ������ ���� �߰� jdy */
$sql = "SELECT * FROM vender_more_info ";
$sql.= "WHERE vender='".$vender."'";
$result=mysql_query($sql,get_db_conn());

$_vmdata=mysql_fetch_object($result);

mysql_free_result($result);
/* ������ ���� �߰� jdy */

/* ���ݹ�� �߰� gura */
$sql = "SELECT * FROM vender_rent ";
$sql.= "WHERE vender='".$vender."' and pridx=0";

$result=mysql_query($sql,get_db_conn());
$_ptdata=mysql_fetch_object($result);
mysql_free_result($result);
/* ���ݹ�� �߰� gura */



// ���� ���� ��ȸ jdy
$shop_more_info = getShopMoreInfo();
$account_rule = $shop_more_info['account_rule'];

$reserve_use = $shop_more_info['reserve_use'];
$coupon_use = $shop_more_info['coupon_use'];
// ���� ���� ��ȸ jdy


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


	/* ������ ���� �߰� jdy */
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
	//�Ǹ� ������� ��� ������ ���濡���� ����� ���� ex) ��ü-> ����

		//$_vmdata ���� ������ �������� ����

		if ($_vmdata->vender) {
			if ($up_commission_type!=($_vmdata->commission_type)) {

				if ($_vmdata->commission_type == "1") {
					$up_history = "���������� -> ��ü������ ".$up_rate."%�� ���� [�����]";
				}else{

					$up_history = "��ü������ ".$_vdata->rate."% -> ����������� ���� [�����]";
					$up_rate = 0;
				}
				$updateChk = "1";
			}else{

				if ($_vmdata->commission_type != '') {
					if ($up_commission_type != "1") {

						if ($up_rate !=$_vdata->rate) {
							$up_history = "��ü������ ".$_vdata->rate."% -> ".$up_rate."% �� ���� [�����]";
							$updateChk = "1";
						}

					}else{
						$up_rate = 0;
					}
				}
			}
		}
	}else{
	//���ް��� ��� ��� ������ ���� ���ް� ���.

		$up_commission_type = 1;
		$up_rate = 0;
	}
	/* ������ ���� �߰� jdy */

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
		$error="ȸ����� �Է��ϼ���.";
	} /*else if(strlen($up_com_num)==0) {
		$error="����ڵ�Ϲ�ȣ�� �Է��ϼ���.";
	} */else if(strlen($up_brand_name)==0) {
		$error="�̴ϼ����� �Է��ϼ���.";
	} /*else if(chkBizNo($up_com_num)==false) {
		$error="����ڵ�Ϲ�ȣ�� ��Ȯ�� �Է��ϼ���.";
	} */else if(strlen($up_com_tel)==0) {
		$error="ȸ�� ��ǥ��ȭ�� ��Ȯ�� �Է��ϼ���.";
	} else if(strlen($up_p_name)==0) {
		$error="����� �̸��� �Է��ϼ���.";
	} else if(strlen($up_p_mobile)==0) {
		$error="����� �޴���ȭ�� ��Ȯ�� �Է��ϼ���.";
	} else if(strlen($up_p_email)==0) {
		$error="����� �̸����� �Է��ϼ���.";
	} else if(ismail($up_p_email)==false) {
		$error="����� �̸����� ��Ȯ�� �Է��ϼ���.";
	} else if(strlen($up_close_date)==0) {
		/* �߰� jdy */
		$error="������� �Է����ּ���.";
		/* �߰� jdy */
	}

	if(strlen($error)==0) {
		$sql = "SELECT brand_name FROM tblvenderstore WHERE vender!='".$vender."' AND brand_name='".$up_brand_name."' ";
		$result=mysql_query($sql,get_db_conn());
		if($row=mysql_fetch_object($result)) {
			$error="�̴ϼ����� �ߺ��Ǿ����ϴ�.";
		}
		mysql_free_result($result);

		if(strlen($error)==0) {

			
			/**gura**/
			//���ݹ��
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
			
			//���뿩����
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

			//ȯ��
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
			
			//�������
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

			if($useseason!="1"){//������ü ���������� �ƴѰ�� ����
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
			
			// ������ �߰�
			if(_isInt($_REQUEST['starmark'])) $sql.= ",starmark	= '".$_REQUEST['starmark']."' ";

			// ������ �̹��� ���
			if( $_FILES['com_image']['error'] == 0 AND $_FILES['com_image']['size'] > 0 AND eregi("image",$_FILES['com_image']['type']) AND $_POST['com_image_del'] != "OK" ) {
				$exte = explode(".",$_FILES['com_image']['name']);
				$exte = $exte[ count($exte)-1 ];
				$com_image_name = "comImgae_".date("YmdHis").".".$exte;
				move_uploaded_file($_FILES['com_image']['tmp_name'],$com_image_url.$com_image_name);
				$sql.= ", com_image = '".$com_image_name."' ";
			}

			//������ �̹��� ����
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


				/* ������ ���� �߰� jdy */
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
						$close_history = "����� ".$_vmdata->close_date."�Ͽ��� ".$up_close_date."�Ϸ� ����";
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
					$ad_his = "������ ".$_vdata->account_date."�Ͽ��� ".$up_account_date."�Ϸ� ����";
					$ad_his_chk++;
				}

				if ($_vmdata->adjust_lastday != $up_adjust_lastday) {

					$ad_his = "";
					switch($_vmdata->adjust_lastday) {
						case 0 : $ad_his = "�������� ������������ ";
							break;
						case 1 : $ad_his = "�������� �ſ��������Ͽ��� ";
							break;
						case 2 : $ad_his = "�������� 15�ϰ� �ſ��������Ͽ��� ";
							break;
					}

					switch($up_adjust_lastday) {
						case 0 : $ad_his .= "������������ ���� ( ".$up_account_date." ) ";
							break;
						case 1 : $ad_his .= "�ſ��������Ϸ� ����";
							break;
						case 2 : $ad_his .= "15�ϰ� �ſ��������Ϸ� ����";
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

				/* ������ ���� �߰� jdy */
				
				
				// �׷� ���� ó��
				if(!_isInt($_POST['vgidx'])) mysql_query("delete from vender_group_link where vender='".$vender."'",get_db_conn());
				else mysql_query("insert into vender_group_link (vender,vgidx) values ('".$vender."','".$_POST['vgidx']."') on duplicate key update vgidx='".$_POST['vgidx']."'",get_db_conn());
				$log_content = "## ������ü ���� ���� ## - ��üID : ".$_vdata->id;
				ShopManagerLog($_ShopInfo->getId(),$connect_ip,$log_content);


				echo "<html></head><body onload=\"alert('��ü���� ������ �Ϸ�Ǿ����ϴ�.');parent.document.form3.submit();\"></body></html>";exit;
			} else {
				$error="������ü ����� ������ �߻��Ͽ����ϴ�.";
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

	/* ������ ���� �߰� jdy */
	mysql_query("DELETE FROM vender_more_info WHERE vender='".$vender."'",get_db_conn());
	/* ������ ���� �߰� jdy */
	
	// �׷� ����
	mysql_query("delete from vender_group_link where vender='".$vender."'",get_db_conn());


	mysql_query("optimize table tblvenderstorevisit");
	mysql_query("optimize table tblvenderlog");
	mysql_query("optimize table tblregiststore");

	//�̹��� ���� ����
	$storeimagepath=$Dir.DataDir."shopimages/vender/";
	proc_matchfiledel($storeimagepath."MAIN_".$vender.".*");
	proc_matchfiledel($storeimagepath."logo_".$vender.".*");
	proc_matchfiledel($storeimagepath.$vender."*");
	proc_matchfiledel($storeimagepath."aboutdeliinfo_".$vender."*");

	if($delete_gbn=="Y") {			//��ü ��ǰ ���� ����
		$sql = "SELECT productcode FROM tblproduct WHERE vender='".$vender."' ";
		$result=mysql_query($sql,get_db_conn());
		while($row=mysql_fetch_object($result)) {
			$prcode=$row->productcode;
			#�±װ��� �����
			$sql = "DELETE FROM tbltagproduct WHERE productcode = '".$prcode."'";
			mysql_query($sql,get_db_conn());

			#���� �����
			$sql = "DELETE FROM tblproductreview WHERE productcode = '".$prcode."'";
			mysql_query($sql,get_db_conn());

			#���ø���Ʈ �����
			$sql = "DELETE FROM tblwishlist WHERE productcode = '".$prcode."'";
			mysql_query($sql,get_db_conn());

			#���û�ǰ �����
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

		$log_content = "## ������ü ���� ## - ��üID : ".$_vdata->id." , [��ü��ǰ ����]";
		ShopManagerLog($_ShopInfo->getId(),$connect_ip,$log_content);
	} else if($delete_gbn=="N") {	//��ü ��ǰ ���θ� ���� ��ǰ���� ����
		$sql = "UPDATE tblproduct SET vender=0 ";
		$sql.= "WHERE vender='".$vender."' ";
		mysql_query($sql,get_db_conn());

		$log_content = "## ������ü ���� ## - ��üID : ".$_vdata->id." , [��ü��ǰ �����ǰ���� ����]";
		ShopManagerLog($_ShopInfo->getId(),$connect_ip,$log_content);
	}

	echo "<html></head><body onload=\"alert('�ش� ������ü ������ ������ �����Ǿ����ϴ�.');parent.document.form3.submit();\"></body></html>";exit;
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
		alert("��ü ���ο��θ� �����ϼ���.");
		form.up_disabled[0].focus();
		return;
	}
	if(form.up_passwd.value.length>0) {
		if(form.up_passwd.value!=form.up_passwd2.value) {
			alert("������ ��й�ȣ�� ��ġ���� �ʽ��ϴ�."); form.up_passwd2.focus(); return;
		}
	}
	if(form.up_com_name.value.length==0) {
		alert("ȸ����� �Է��ϼ���."); form.up_com_name.focus(); return;
	}
	/*
	if(form.up_com_num.value.length==0) {
		alert("����ڵ�Ϲ�ȣ�� �Է��ϼ���."); form.up_com_num.focus(); return;
	}
	if(chkBizNo(form.up_com_num.value)==false) {
		alert("����ڵ�Ϲ�ȣ�� �߸��Ǿ����ϴ�."); form.up_com_num.focus(); return;
	}*/
	if(form.up_com_tel1.value.length==0 || form.up_com_tel2.value.length==0 || form.up_com_tel3.value.length==0) {
		alert("ȸ�� ��ǥ��ȭ�� ��Ȯ�� �Է��ϼ���."); form.up_com_tel1.focus(); return;
	}
	if(form.up_p_name.value.length==0) {
		alert("����� �̸��� �Է��ϼ���."); form.up_p_name.focus(); return;
	}
	if(form.up_p_mobile1.value.length==0 || form.up_p_mobile2.value.length==0 || form.up_p_mobile3.value.length==0) {
		alert("����� �޴���ȭ�� ��Ȯ�� �Է��ϼ���."); form.up_p_mobile1.focus(); return;
	}
	if(form.up_p_email.value.length==0) {
		alert("����� �̸����� �Է��ϼ���."); form.up_p_email.focus(); return;
	}
	if(IsMailCheck(form.up_p_email.value)==false) {
		alert("����� �̸����� ��Ȯ�� �Է��ϼ���."); form.up_p_email.focus(); return;
	}

	if(form.up_account_date.value=='29' || form.up_account_date.value=='30' || form.up_account_date.value=='31') {
		alert("�����Ϸ� ����� �� ���� ��¥ �Դϴ�."); form.up_account_date.focus(); return;
	}

	all_rate = document.getElementById("up_all_rate");
	up_c_type0 = document.getElementById("up_commission_type0");

	if (up_c_type0.checked) {
		if (typeof all_rate !="undefined") {

			if (all_rate.value != '' && Number(all_rate.value)>0) {

				if(!confirm("���� �����Ḧ "+all_rate.value+"% �� �����մϴ�. ����Ͻðڽ��ϱ�?")) {
					return;
				}
			}
		}
	}

	if(confirm("������ü ������ �����Ͻðڽ��ϱ�?")) {
		document.form1.type.value="update";
		document.form1.target="processFrame";
		document.form1.submit();
	}
}

function GoReturn() {
	document.form3.submit();
}

function CheckDelete() {
	if(confirm("�ش� ��ü�� ���� �����Ͻðڽ��ϱ�?")) {
		if(confirm("�ش� ��ü�� ��ǰ�� ���� �����Ͻðڽ��ϱ�?\n\n��ü ��ǰ�� ���� ������ ��� [Ȯ��]\n\n��ü ��ǰ�� ���θ� ���� ��ǰ���� �����Ͻ÷��� [���] ��ư�� ��������.")) {
			if(confirm("���� �ش� ��ü�� ��ǰ�� ��� �����Ͻðڽ��ϱ�?")) {
				document.form1.delete_gbn.value="Y";
				document.form1.type.value="delete";
				document.form1.target="processFrame";
				document.form1.submit();
			}
		} else {
			if(confirm("���� �ش� ��ü ���� �� ��ü ��ǰ�� ���θ� ���� ��ǰ���� �����Ͻðڽ��ϱ�?")) {
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
		alert("�̴ϼ����� �Է��ϼ���.");
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
		alert("�ʱ� ������ǰ ������� 0%�� �����Ǵ� �ݵ�� �Ʒ� ���������� �ϰ����������� ������ ���� �� �� ��ǰ�� �����Ḧ �����Ͻñ� �ٶ��ϴ�.\n��������ῡ ���� ����ݿ��� �Ѱ����ڰ� �ش��ǰ�ֹ��ܰ踦 ��ۿϷ�� ó���� ������ ����˴ϴ�.");
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
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �������� &gt; ������ü ���� &gt; <span class="2depth_select">������ü �űԵ��</span></td>
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
					<TD width="100%" class="notice_blue">������ü�� ������ ����/���� �Ͻ� �� �ֽ��ϴ�.</TD>
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
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;<a href="javascript:document.location.reload()">[���ΰ�ħ]</a></TD>
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
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">��ü ����</TD>
					<TD class="td_con1">
					<input type=radio name=up_disabled id=up_disabled0 value="0" <?if($_vdata->disabled=="0")echo"checked";?>> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=up_disabled0>����</label>
					<img width=20 height=0>
					<input type=radio name=up_disabled id=up_disabled1 value="1" <?if($_vdata->disabled=="1" || strlen($_vdata->disabled)==0)echo"checked";?>> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=up_disabled1>����</label>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">��ü ID</TD>
					<TD class="td_con1"><B><?=$_vdata->id?></B></TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">�н����� ����</TD>
					<TD class="td_con1">
					<input type=password name=up_passwd value="" size=20 maxlength=12 class=input>
					&nbsp;&nbsp;
					<FONT class=font_orange>* ����, ���ڸ� ȥ���Ͽ� ���(4�� ~ 12��)</font>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">�н����� Ȯ��</TD>
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
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">�Ǹ��ڸ�����</TD>
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
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">������ �̹���</TD>
					<TD class="td_con1">

						<div style="margin:5px;">
							<div style="float:left; margin:0px; padding:0px; font-size:0px;"><img src="<?=$com_image_url.$_vdata->com_image?>" width="120" onerror="this.src='/images/no_img.gif';" style="border:1px solid #dddddd;" /></div>
							<div style="float:left; margin-top:5px; margin-left:10px;">
								<div>
									<span style="font-size:11px; color:#666666; line-height:15px; letter-spacing:-1px;">
										<strong>������� : </strong><input type="checkbox" name="com_nametech" value="1" <?=($_vdata->com_nametech?"checked":"");?>><br /><br /><br />
										�� <b>������ �̹�����??</b><br />
										<img src="images/vender_nametek_sample.gif" style="border:1px solid #e5e5e5;" hspace="8" vspace="4" alt="" /><br />
										- ������ �̹����� ��ǰ ��� �� �� ���������� ������ ���� ��½� ���Ǵ� �̹��� �Դϴ�.<br />
										- �̹��� ������� ����*���� 100px * 100px �� ����帳�ϴ�.
									</span>
								</div>
								<div style="margin-top:10px;">
									<input type="file" name="com_image" id="com_image">
									<input type="checkbox" name="com_image_del" id="com_image_del" value="OK" onclick="com_image.style.display=( this.checked ?'none':'inline')"><label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="com_image_del">����</label>
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
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">��ȣ (ȸ���)</TD>
					<TD class="td_con1">
					<input type=text name=up_com_name value="<?=$_vdata->com_name?>" size=20 maxlength=30 class=input>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">����ڵ�Ϲ�ȣ</TD>
					<TD class="td_con1">
					<input type=text name=up_com_num value="<?=$_vdata->com_num?>" size=20 maxlength=20 onkeyup="strnumkeyup(this)" class=input>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">�̴ϼ���</TD>
					<TD class="td_con1">
					<input type=text name=up_brand_name value="<?=$_vdata->brand_name?>" size=20 maxlength=30 class=input>
					<A class=board_list hideFocus style="selector-dummy: true" onfocus=this.blur(); href="javascript:branddup();"><IMG src="images/duple_check_img.gif" border=0 align="absmiddle"></A>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point5.gif" width="8" height="11" border="0">��ǥ�� ����</TD>
					<TD class="td_con1">
					<input name=up_com_owner value="<?=$_vdata->com_owner?>" size=20 maxlength="12" class=input>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point5.gif" width="8" height="11" border="0">ȸ�� �ּ�</TD>
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
					<TD class="table_cell" width="139"><img src="images/icon_point5.gif" width="8" height="11" border="0">����� ����</TD>
					<TD class="td_con1">
					<input type="text" name=up_com_biz value="<?=$_vdata->com_biz?>" size=30 maxlength=30 class=input>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point5.gif" width="8" height="11" border="0">����� ����</TD>
					<TD class="td_con1">
					<input type=text name=up_com_item value="<?=$_vdata->com_item?>" size=30 maxlength=30 class=input>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>



				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point5.gif" width="8" height="11" border="0">����ǸŽŰ�</TD>
					<TD class="td_con1">
					<input type=text name=ec_num value="<?=$_vdata->ec_num?>" size=20 maxlength=20 class=input>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>



				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point5.gif" width="8" height="11" border="0">����ڱ���</TD>
					<TD class="td_con1">
					<input type=text name=com_type value="<?=$_vdata->com_type?>" size=20 maxlength=20 class=input>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>



				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">ȸ�� ��ǥ��ȭ</TD>
					<TD class="td_con1">
					<input type=text name=up_com_tel1 value="<?=$com_tel[0]?>" size=4 maxlength=4 style="width:40" onkeyup="strnumkeyup(this)" class=input>-<input type=text name=up_com_tel2 value="<?=$com_tel[1]?>" size=4 maxlength=4 style="width:40" onkeyup="strnumkeyup(this)" class=input>-<input type=text name=up_com_tel3 value="<?=$com_tel[2]?>" size=4 maxlength=4 style="width:40" onkeyup="strnumkeyup(this)" class=input>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point5.gif" width="8" height="11" border="0">ȸ�� �ѽ���ȣ</TD>
					<TD class="td_con1">
					<input type=text name=up_com_fax1 value="<?=$com_fax[0]?>" size=4 maxlength=4 style="width:40" onkeyup="strnumkeyup(this)" class=input>-<input type=text name=up_com_fax2 value="<?=$com_fax[1]?>" size=4 maxlength=4 style="width:40" onkeyup="strnumkeyup(this)" class=input>-<input type=text name=up_com_fax3 value="<?=$com_fax[2]?>" size=4 maxlength=4 style="width:40" onkeyup="strnumkeyup(this)" class=input>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point5.gif" width="8" height="11" border="0">ȸ�� Ȩ������</TD>
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
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">����� �̸�</TD>
					<TD class="td_con1">
					<input type=text name=up_p_name value="<?=$_vdata->p_name?>" size=20 maxlength=20 class=input> &nbsp; <FONT class=font_orange>* ���� ����� �̸��� ��Ȯ�� �Է��ϼ���.</font>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">����� �޴���ȭ</TD>
					<TD class="td_con1">
					<input type=text name=up_p_mobile1 value="<?=$p_mobile[0]?>" size=4 maxlength=3 style="width:40" onkeyup="strnumkeyup(this)" class=input>-<input type=text name=up_p_mobile2 value="<?=$p_mobile[1]?>" size=4 maxlength=4 style="width:40" onkeyup="strnumkeyup(this)" class=input>-<input type=text name=up_p_mobile3 value="<?=$p_mobile[2]?>" size=4 maxlength=4 style="width:40" onkeyup="strnumkeyup(this)" class=input></TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">����� �̸���</TD>
					<TD class="td_con1">
					<input type=text name=up_p_email value="<?=$_vdata->p_email?>" size=30 maxlength=50 class=input> &nbsp; <FONT class=font_orange>* �ֹ�Ȯ�ν� ����� �̸��Ϸ� �뺸�˴ϴ�.</font>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point5.gif" width="8" height="11" border="0">����� �μ���</TD>
					<TD class="td_con1">
					<input type=text name=up_p_buseo value="<?=$_vdata->p_buseo?>" size=20 maxlength=20 class=input>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point5.gif" width="8" height="11" border="0">����� ����</TD>
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
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">�׷�</TD>
					<TD class="td_con1">
					<select name="vgidx" id="vgidx" onchange="javascript:changeGroup();" vgidx="<?=$vgroup['vgidx']?>">
						<option value="">-- �׷� --</option>
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

							html = '���� ������ (���� : '+vgcommi_self+'%  /  ��Ź : '+vgcommi_main+'% )';
							html2 = '������ (���� : <span style="color:#0C0;">'+commi_self+'</span>- '+vgcommi_self+'='+totalcommi_self+'%  /  ��Ź : <span style="color:#0C0;">'+commi_main+'</span>- '+vgcommi_main+'='+totalcommi_main+'%)';
						}else{
							html = '';
							html2 = '������ (���� : <span style="color:#0C0;">'+commi_self+'</span>%  /  ��Ź : <span style="color:#0C0;">'+commi_main+'</span>%)';
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
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�뿩������</TD>
					<TD class="td_con1" id="txtCommiArea">
					
					</TD>
				</tr>
				<TR>
					<TD colspan=2 background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<? if ($account_rule !="1") { ?>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">�ǸŻ�ǰ ������ Ÿ��</TD>
					<TD class="td_con1">
						<input type=radio name=up_commission_type id=up_commission_type0 value="1" <?if($_vmdata->commission_type=="1") echo"checked";?> onclick="selCommission('0');"> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=up_commission_type0>��ǰ���� ������</label>
						<img width=20 height=0>
						<input type=radio name=up_commission_type id=up_commission_type1 value="0" <?if($_vmdata->commission_type=="0" || strlen($_vmdata->commission_type)==0) echo "checked";?>  onclick="selCommission('1');"> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=up_commission_type1>��ü��ǰ ���� ������</label>
						<br><span class="font_blue">&nbsp;* ��������� ���� �Ǹż������ �ΰ��� ������ ����� ó�� �� ����.<br>
						&nbsp;&nbsp;(�������� ��� ����, ���̰���, �鼼, ���λ���� �� ���������� ���������ϹǷ� �Ǹż������� �ΰ����� ������ ����� ����)</span>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
			</TABLE>

			<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<? if ($account_rule !='1') { ?>
				<TR id="commission_tr" <? if ($_vmdata->commission_type!="1") { ?> style="display:none" <? } ?>>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">������ǰ �Ǹ� ������ �ϰ�����</TD>
					<TD class="td_con1">
						<input type=text name=up_all_rate id="up_all_rate" value="" size=3 maxlength=3 onkeyup="strnumkeyup(this)" class=input>%
						&nbsp;&nbsp;&nbsp;&nbsp; <FONT class=font_orange>* 0���� ū��ġ�� �Է��� ��� ��ϵǾ� �ִ� ��ǰ ��ο� �ϰ������� �����Ḧ �����ų�� �ֽ��ϴ�.</font>
					</TD>
				</TR>
				<? } ?>

				<TR id="commission_all" <? if ($_vmdata->commission_type=="1") { ?> style="display:none" <? } ?>>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">��ü��ǰ �Ǹ� ������</TD>
					<TD class="td_con1">
						<input type=text name=up_rate value="<?=$_vdata->rate?>" size=3 maxlength=3 onkeyup="strnumkeyup(this)" class=input>%
						&nbsp;&nbsp;&nbsp;&nbsp; <FONT class=font_orange>* ����ǰ�� ���� ����˴ϴ�.</font>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<? }else{ ?>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">������ �����</TD>
					<TD class="td_con1">
						<input type=hidden name=up_commission_type value="1" />
						<input type=hidden name=up_rate value="0" />
						��ǰ���� ���ް�
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<? } ?>
			</TABLE>

			<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">��ǰ ó�� ����</TD>
					<TD class="td_con1">
					<input type=checkbox name=chk_prdt1 id=idx_chk_prdt1 value="Y" <?if(substr($_vdata->grant_product,0,1)=="Y")echo"checked";?>><label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_chk_prdt1>���</label>
					<img width=20 height=0>
					<input type=checkbox name=chk_prdt2 id=idx_chk_prdt2 value="Y" <?if(substr($_vdata->grant_product,1,1)=="Y")echo"checked";?>><label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_chk_prdt2>����</label>
					<img width=20 height=0>
					<input type=checkbox name=chk_prdt3 id=idx_chk_prdt3 value="Y" <?if(substr($_vdata->grant_product,2,1)=="Y")echo"checked";?>><label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_chk_prdt3>����</label>
					<img width=50 height=0>
					<input type=checkbox name=chk_prdt4 id=idx_chk_prdt4 value="Y" <?if(substr($_vdata->grant_product,3,1)=="Y")echo"checked";?>><label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_chk_prdt4>���/������, ������ ����</label>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">���� ��ǰ�� ����(<?=$_vdata->product_max?>)</TD>
					<TD class="td_con1">
						<input type=text name=up_product_max value="<?=$_vdata->product_max?>" size=3 onkeyup="strnumkeyup(this)" class=input>
						�� ���� ��ǰ��� ����
						<br/>
						<FONT class=font_orange>* ���������� ������ �ÿ��� <span class="font_blue">0</span>�� �Է����ּ���.</font>
						<!--
						<select name=up_product_max class="select">
						<option value="0" <?if($_vdata->product_max==0)echo"selected";?>>������</option>
						<option value="50" <?if($_vdata->product_max==50)echo"selected";?>>50</option>
						<option value="100" <?if($_vdata->product_max==100)echo"selected";?>>100</option>
						<option value="150" <?if($_vdata->product_max==150)echo"selected";?>>150</option>
						<option value="200" <?if($_vdata->product_max==200)echo"selected";?>>200</option>
						<option value="250" <?if($_vdata->product_max==250)echo"selected";?>>250</option>
						<option value="300" <?if($_vdata->product_max==300)echo"selected";?>>300</option>
						</select> �� ���� ��ǰ��� ����
						-->
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<!--
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">�Ǹ� ������</TD>
					<TD class="td_con1">
						<input type=text name=up_rate value="<?=$_vdata->rate?>" size=3 maxlength=3 onkeyup="strnumkeyup(this)" class=input>%
						&nbsp;&nbsp;&nbsp;&nbsp; <FONT class=font_orange>* ���θ� ���翡�� �޴� ��ǰ�Ǹ� �����Ḧ �Է��ϼ���.</font>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				-->


				<? /*�߰� gura */?>
				<tr>
					<td class="table_cell"><B>���� �Ӽ�</td>
					<td class="td_con1">
						<select name="category">
							<option value="">�����ϼ���</option>
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
					<td class="table_cell"><B>�뿩���ɽð�</td>
					<td class="td_con1">
						����: <input type="text" name="rent_stime" id="rent_stime" size="3" maxlength="2" value="<?=$_ptdata->checkin_time?>">�� ~
						����: <input type="text" name="rent_etime" id="rent_etime" size="3" maxlength="2" value="<?=$_ptdata->checkout_time?>">�� 
						*24�ð� �뿩�� ��� ���۰� ����ð��� ���� �����ϼ���.
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td class="table_cell"><B>���ݹ�� ����</td>
					<td class="td_con1">
					<input type=radio name=pricetype value="0" <?if($_vdata->pricetype=="0")echo"checked";?> onclick="pricetypeDivView('N')">���� ��å�� ����&nbsp;&nbsp;
					<input type=radio name=pricetype value="1" <?if($_vdata->pricetype=="1")echo"checked";?> onclick="pricetypeDivView()">������ü ���� ����
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
								html = '<div>���� 12�ð� ���: 24�ð� ����� <input type="text" name="halfday_percent" size="3" maxlength="2">%</div>';
								$j('#price1').html(html);
							}else{
								html = '';
								$j('#price1').html(html);
							}
							
						}

						function onedayexCheck(val){			
							if(val=="time"){
								html = '<div>�߰� 1�ð� ���: 24�ð� ����� <input type="text" name="time_percent" size="3" maxlength="2">%</div>';
								$j('#price2').html(html);
							}else if(val=="half"){
								html = '<div>�߰� 12�ð� ���: 24�ð� ����� <input type="text" name="time_percent" size="3" maxlength="2">%</div>';
								$j('#price2').html(html);
							}else{
								html = '';
								$j('#price2').html(html);
							}
							
						}
						</script>
						<table cellpadding="0" cellspacing="0" class="infoListTbl" style="width:100%;margin-top:7px;padding:7px 7px 7px 7px; border-bottom:0px;border:2px solid #acacac;background-color:#ffffff">
							<tr>
								<th style="width:100px;">���ݹ��</th>
								<td class="norbl" style="padding:5px;">
									<select name="vender_rent" id="vender_rent" onchange="javascript:chPriceType()">
										<option value="day" <? if($_ptdata->pricetype == 'day') echo ' selected="selected"'; ?> >24�ð���</option>
										<option value="time" <? if($_ptdata->pricetype == 'time') echo ' selected="selected"'; ?>>1�ð���</option>
										<option value="checkout" <? if($_ptdata->pricetype == 'checkout') echo ' selected="selected"'; ?>>������(������)</option>
										<option value="period" <? if($_ptdata->pricetype == 'period') echo ' selected="selected"'; ?> >�ܱ�Ⱓ��</option>
										<option value="long" <? if($_ptdata->pricetype == 'long') echo ' selected="selected"'; ?> >���Ⱓ��(����)</option>
									</select>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<? if($_ptdata->pricetype == 'day') $display = ""; else $display = "none"; ?>
									<table id="day_div" class="infoListTbl" cellpadding="0" cellspacing="0" style="display:<?=$display?>;width:100%;margin-top:7px;padding:7px 7px 7px 7px;">
										<tr>
											<th style="width:150px;">���� 12�ð� �뿩���</th>
											<td style="padding:5px;">
												<input type=radio name=halfday value="Y" <?if($_ptdata->halfday=="Y")echo"checked";?> onclick="halfdayCheck('Y')">��
												<input type=radio name=halfday value="N" <?if($_ptdata->halfday=="N")echo"checked";?> onclick="halfdayCheck('N')">�ƴϿ�
											</td>
											<td id="price1" style="width:300px">
												<?
												if($_ptdata->halfday=="Y"){
													echo '<div>���� 12�ð� ���: 24�ð� ����� <input type="text" name="halfday_percent" size="3" maxlength="2" value="'.$_ptdata->halfday_percent.'">%</div>';
												}
												?>
											</td>
										</tr>
										<tr>
											<th>1�� �ʰ��� ���ݱ���</th>
											<td style="padding:5px;">
												<input type=radio name=oneday_ex value="day" <?if($_ptdata->oneday_ex=="day")echo"checked";?> onclick="onedayexCheck('day')">1�� ����
												<input type=radio name=oneday_ex value="half" <?if($_ptdata->oneday_ex=="half")echo"checked";?> onclick="onedayexCheck('half')">12�ð� ����
												<input type=radio name=oneday_ex value="time" <?if($_ptdata->oneday_ex=="time")echo"checked";?> onclick="onedayexCheck('time')">1�ð� ����
											</td>
											<td id="price2" style="width:300px">
												<?
												if($_ptdata->oneday_ex=="time"){
													echo '<div>�߰� 12�ð� ���: 24�ð� ����� <input type="text" name="time_percent" size="3" maxlength="2" value="'.$_ptdata->time_percent.'">%</div>';
												}else if($_ptdata->oneday_ex=="half"){
													echo '<div>�߰� 12�ð� ���: 24�ð� ����� <input type="text" name="time_percent" size="3" maxlength="2" value="'.$_ptdata->time_percent.'">%</div>';
												}
												?>
											</td>
										</tr>
									</table>
									<? if($_ptdata->pricetype == 'time') $display = ""; else $display = "none"; ?>
									<table id="time_div" class="infoListTbl" cellpadding="0" cellspacing="0" style="display:<?=$display?>;width:100%;margin-top:7px;padding:7px 7px 7px 7px;">
										<tr>
											<th style="width:100px;">�⺻���</th>
											<td class="norbl" style="padding:5px;">
												<select name="base_time">
													<? for($i=1;$i<=36;$i++){?>
													<option value="<?=$i?>" <? if($_ptdata->base_time == $i) echo ' selected="selected"'; ?> ><?=$i?>�ð�</option>
													<? } ?>
												</select>
												<input type="text" name="base_price" size="15" value="<?=$_ptdata->base_price?>">��
											</td>
										</tr>
										<tr>
											<th>�߰��ð���</th>
											<td>
												<input type="text" name="timeover_price" size="15" value="<?=$_ptdata->timeover_price?>">��
											</td>
										</tr>
									</table>
									<? if($_ptdata->pricetype == 'checkout') $display = ""; else $display = "none"; ?>
									<table id="checkout_div" class="infoListTbl" cellpadding="0" cellspacing="0" style="display:<?=$display?>;width:100%;margin-top:7px;padding:7px 7px 7px 7px;">
										<tr>
											<th style="width:100px;">üũ�� �ð�</th>
											<td class="norbl" style="padding:5px;">
												<input type="text" name="checkin_time" id="checkin_time" size="3" maxlength="2" value="<?=$_ptdata->checkin_time?>">��
											</td>
											<th style="width:100px;">üũ�ƿ� �ð�</th>
											<td>
												<input type="text" name="checkout_time" id="checkout_time" size="3" maxlength="2" value="<?=$_ptdata->checkout_time?>">��
											</td>
										</tr>
									</table>
									<? if($_ptdata->pricetype == 'period') $display = ""; else $display = "none"; ?>
									<table id="period_div" cellpadding="0" cellspacing="0" style="display:<?=$display?>;width:100%;margin-top:7px;padding:7px 7px 7px 7px;">
										<tr>
											<th style="width:100px;">�⺻�뿩��</th>
											<td class="norbl" style="padding:5px;">
												<input type="text" name="base_period" size="5" value="<?=$_ptdata->base_period?>"  onkeyup="javascript:$j('#addLongrent_sday').val(parseInt($j('input[name=base_period]').val())+1);">�� ����
												&nbsp;&nbsp;*3���� 2�� 3���Դϴ�.
											</td>
										</tr>
									</table>
									<? if($_ptdata->pricetype == 'long') $display = ""; else $display = "none"; ?>
									<table id="long_div" cellpadding="0" cellspacing="0" style="display:<?=$display?>;width:100%;margin-top:7px;padding:7px 7px 7px 7px;">
										<tr>
											<th style="width:100px;">���� �� ������</th>
											<td class="norbl" style="padding:5px;">
												<input type=radio name="ownership" value="mv" <?if($_ptdata->ownership=="mv")echo"checked";?>>���� 
												<input type=radio name="ownership" value="re" <?if($_ptdata->ownership=="re")echo"checked";?>>�ݳ�
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
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>���뿩����</td>
					<td style=padding:7,10>
					<input type=radio name=longrent value="0" <?if($_vdata->longrent=="0")echo"checked";?> onclick="longrentDivView('N')">���� ��å�� ���� 
					<input type=radio name=longrent value="1" <?if($_vdata->longrent=="1")echo"checked";?> onclick="longrentDivView()">������ü ���� ����

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
								alert('��¥�� �ùٸ��� �Է��ϼ���.');
								$j('#addLongrent_sday').focus();
							}else if(isNaN(ed) || ed < 1){
								alert('��¥�� �ùٸ��� �Է��ϼ���.');
								$j('#addLongrent_eday').focus();
							}else if(isNaN(p) || p < 1|| p>100){
								alert('�߰��������� �ùٸ��� �Է��ϼ���.');
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
									alert('�ߺ��� ���ڰ� �ֽ��ϴ�. ���� �ߺ� �׸��� ������ �߰� �Ͻñ� �ٶ��ϴ�.');
								}else{
									html = '<div><input type="hidden" name="longrent_sday[]" value="'+sd+'"><input type="hidden" name="longrent_eday[]" value="'+ed+'"><input type="hidden" name="longrent_percent[]" value="'+p+'"><span style="float:left">'+sd+'~'+ed+' �ϱ��� '+p+'% �߰�</span><img src="../admin/images/btn_del.gif" alt="����" align="right" /></div>';
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
								<th style="width:100px;">�Ⱓ</th>
								<td class="norbl" style="padding:5px;">
									<input type="text" name="addLongrent_sday" id="addLongrent_sday" value="" style="width:30px;" />~
									<input type="text" name="addLongrent_eday" id="addLongrent_eday" value="" style="width:30px;" />
									�ϱ���
								</td>
								<th style="width:100px;">�߰�����</th>
								<td style="padding:5px;">
									<input type="text" name="addLongrentPercent" id="addLongrentPercent" value="" style="width:30px;" />
									% 
								</td>
								<td>
									<input type="button" name="addLongrentBtn" value="�߰�" onclick="javascript:addLongrentCharge()" />
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
								�ϱ���
								<?=$v['percent']?>
								% �߰�</span><img src="../admin/images/btn_del.gif" alt="����" align="right" /></div>
							<?	}
						}?>
						<input type="hidden" name="last_eday" value="<?=$v['eday']+1?>">
						</div>

					</div>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td class="table_cell"><B>ȯ�� ��å</td>
					<td class="td_con1">
						<input type=radio name=refund value="0" <?if($_vdata->refund=="0")echo"checked";?> onclick="refundDivView('N')">���� ��å�� ����&nbsp;&nbsp;
						<input type=radio name=refund value="1" <?if($_vdata->refund=="1")echo"checked";?> onclick="refundDivView()">������ü ���� ����

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
									alert('������� �ùٸ��� �Է��ϼ���.');
									$j('#addRefundDay').focus();
								}else if(isNaN(p) || p < 1|| p>100){
									alert('�����Ḧ �ùٸ��� �Է��ϼ���.');
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
										alert('�ߺ��� ���ڰ� �ֽ��ϴ�. ���� �ߺ� �׸��� ������ �߰� �Ͻñ� �ٶ��ϴ�.');				
									}else{
										html = '<div><input type="hidden" name="refundday[]" value="'+d+'"><input type="hidden" name="refundpercent[]" value="'+p+'"><span style="float:left">'+d+' ���� '+p+'%</span><img src="../admin/images/btn_del.gif" alt="����" align="right" /></div>';
										$j('#refundDiv').append(html);
										$j('#addRefundDay').val('');
										$j('#addRefundPercent').val('');
									}
								}
								
							}
							</script>

							<table cellpadding="0" cellspacing="0" class="infoListTbl" style="margin-top:7px;padding:7px 7px 7px 7px; border-bottom:0px;border:2px solid #acacac;background-color:#ffffff">
								<tr>
									<th style="width:100px;">�����</th>
									<td class="norbl" style="padding:5px;">
										<input type="text" name="addRefundDay" id="addRefundDay" value="" style="width:30px;" />
										����</td>
									<th style="width:100px;">������</th>
									<td style="padding:5px;">
										<input type="text" name="addRefundPercent" id="addRefundPercent" value="" style="width:30px;" />
										% </td>
									<td>
										<input type="button" name="addRefundBtn" value="�߰�" onclick="javascript:addRefundCommi()" />
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
									����
									<?=$rpercent?>
									%</span><img src="../admin/images/btn_del.gif" alt="����" align="right" /></div>
								<?	}
							}?>
							</div>

						</div>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td class="table_cell"><B>������� ����</td>
					<td class="td_con1">
						<input type=radio name=longdiscount value="0" <?if($_vdata->longdiscount=="0")echo"checked";?> onclick="discountDivView('N')">���� ��å�� ����&nbsp;&nbsp;
						<input type=radio name=longdiscount value="1" <?if($_vdata->longdiscount=="1")echo"checked";?> onclick="discountDivView()">������ü ���� ����

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
									alert('���� �Ⱓ �ùٸ��� �Է��ϼ���.');
									$j('#addRangeDiscountDay').focus();
								}else if(isNaN(p) || p < 1|| p>100){
									alert('�������� �ùٸ��� �Է��ϼ���.');
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
										alert('�ߺ��� ���ڰ� �ֽ��ϴ�. ���� �ߺ� �׸��� ������ �߰� �Ͻñ� �ٶ��ϴ�.');				
									}else{
										html = '<div><input type="hidden" name="discrangeday[]" value="'+d+'"><input type="hidden" name="discrangepercent[]" value="'+p+'"><span style="float:left">'+d+' ���̻� '+p+'% ����</span><img src="../admin/images/btn_del.gif" alt="����" align="right" /></div>';
										$j('#rangeDiscountDiv').append(html);
										$j('#addRangeDiscountDay').val('');
										$j('#addRangeDiscountPercent').val('');
									}
								}
								
							}
							</script>
							<table cellpadding="0" cellspacing="0" class="infoListTbl" style="margin-top:7px;padding:7px 7px 7px 7px; border-bottom:0px;border:2px solid #acacac;background-color:#ffffff">
								<tr>
									<th style="width:100px;">�Ⱓ</th>
									<td class="norbl" style="padding:5px;">
										<input type="text" name="addRangeDiscountDay" id="addRangeDiscountDay" value="" style="width:30px;" />
										���̻�</td>
									<th style="width:100px;">������</th>
									<td style="padding:5px;">
										<input type="text" name="addRangeDiscountPercent" id="addRangeDiscountPercent" value="" style="width:30px;" />
										% </td>
									<td>
										<input type="button" name="addRangeDiscountBtn" value="�߰�" onclick="javascript:addRangeDiscount()" />
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
									���̻�
									<?=$dpercent?>
									%����</span><img src="../admin/images/btn_del.gif" alt="����" align="right" /></div>
								<?	}
							}?>
							</div>

						</div>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td class="table_cell"><B>������ ����</td>
					<td class="td_con1">
						<input type=radio name=useseason value="2" <?if($_vdata->season=="2")echo"checked";?> onclick="seasonDivView('N')">���� ��å�� ����&nbsp;&nbsp;
						<input type=radio name=useseason value="0" <?if($_vdata->season=="0")echo"checked";?> onclick="seasonDivView('N')">������&nbsp;&nbsp;
						<input type=radio name=useseason value="1" <?if($_vdata->season=="1")echo"checked";?> onclick="seasonDivView()">������ü ���� ������/�񼺼��� ���

						<? if($_vdata->season=="1"){ $display=""; }else{ $display="none"; } ?>
						<div id="season_div" style="position:;width:600px;margin-top:5px;z-index:999;padding:5px;display:<?=$display?>;">
								
							<div id="seasonDiv" style="border:1px solid #efefefe"> 
								<table cellpadding="0" cellspacing="0" width="100%" id="seasonListTbl" class="infoListTbl" style="margin-top:7px;padding:7px 7px 7px 7px; border-bottom:0px;border:2px solid #acacac;background-color:#ffffff">
									</tr>
										<th style="width:120px;">������/�ؼ�����</th>
										<td class="norbl" style="padding:5px;">
											<input type="button" value="������/�ؼ����� ����" style="width:200px;" onclick="window.open('../vender/vender_seasonpop.php?vender=<?=$vender?>', 'busySeasonPop', 'width=800,height=600' );">
										</td>
									</tr>
									<tr>
										<th class="nobbl">������/�ָ�</th>
										<td style="padding:5px;" class="norbl nobbl">
											<input type="button" value="������/�ָ� ����" style="width:200px;"  onclick="window.open('../vender/vender_holiday.php?vender=<?=$vender?>', 'holidayPop', 'width=800,height=600' );">
										</td>
									</tr>
								</table>
							</div>

						</div>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<? /*�߰� gura */?>


				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point5.gif" width="8" height="11" border="0">���� ��������</TD>
					<TD class="td_con1">
						���� 
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
						���¹�ȣ <input type=text name=up_bank2 value="<?=$bank_account[1]?>" size=20 class=input>
						<img width=20 height=0>
						������ <input type=text name=up_bank3 value="<?=$bank_account[2]?>" size=15 class=input>
						<BR />
						<span style="color:#ffffff; background-color:#ff4400;padding:4px 5px 2px 5px;cursor:pointer; font-size:11px; letter-spacing:-1px; font-weight:bold;" onclick="subMallIche(<?=$vender?>);">���� ���޴��� ���/����</span> (���޴��� ��Ͻ� ���������� ���氡���ϸ�, ���޴��࿡ ��ϵ� ���������� �� ������ ���� �� �� �ֽ��ϴ�!)
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point5.gif" width="8" height="11" border="0">������(�ſ�)</TD>
					<TD class="td_con1">

						<input type="radio" name="adjust_lastday" id="adjust_lastday_0" value="0" <? if ($_vmdata->adjust_lastday==0) {?>checked="checked"<? } ?> onclick="adjustChecked('0')"> <label for="adjust_lastday_0">��������</label>&nbsp;&nbsp;
						<input type="radio" name="adjust_lastday" id="adjust_lastday_1" value="1" <? if ($_vmdata->adjust_lastday==1) {?>checked="checked"<? } ?> onclick="adjustChecked('1')"> <label for="adjust_lastday_0">�ſ���������</label>&nbsp;&nbsp;
						<input type="radio" name="adjust_lastday" id="adjust_lastday_2" value="2" <? if ($_vmdata->adjust_lastday==2) {?>checked="checked"<? } ?> onclick="adjustChecked('2')"> <label for="adjust_lastday_0">15�ϰ� �ſ���������</label>

						<div id="adjust_div" <? if ($_vmdata->adjust_lastday>0) {?>style="display:none;"<?}?>>
						<input type=text name=up_account_date value="<?=$_vdata->account_date?>" size=75 class=input>��
						&nbsp;&nbsp;&nbsp;&nbsp;
						<span style="color:#ffffff;background-color:#000000;padding:4px 5px 2px 5px;cursor:pointer; font-size:11px; letter-spacing:-1px; font-weight:bold;" onclick="setAccountDate(0)">����</span>&nbsp;
						<span style="color:#ffffff;background-color:#000000;padding:4px 5px 2px 5px;cursor:pointer; font-size:11px; letter-spacing:-1px; font-weight:bold;" onclick="setAccountDate(2)">¦������</span>&nbsp;
						<span style="color:#ffffff;background-color:#000000;padding:4px 5px 2px 5px;cursor:pointer; font-size:11px; letter-spacing:-1px; font-weight:bold;" onclick="setAccountDate(1)">Ȧ������</span>
						<br/>
						<FONT class=font_orange>* �������Խ� 10,20,30 �� ���� ����, ���� ���Խ� 2���޷� ���� 29,30,31�� ����Ҽ� ����</font>
						</div>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
			<? /* �߰� jdy */?>
				<TR>
					<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">�����</TD>
					<TD class="td_con1">������ ����
						<input type=text name=up_close_date value="<?=$_vmdata->close_date?>" size=10 class=input onkeyup="strnumkeyup(this)" >�� ������ ���
						&nbsp;&nbsp; <FONT class=font_orange>* (�����Ϻ��� 1������������ �ֹ��� ������ ��� 7�� �Է�. �ݵ�� 1���� ū���� �Է�, <b>������ �ϴ� �Ŵ��� ����</b>)</font>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>

				<? if ($reserve_use || $coupon_use) {?>
				<input type="hidden" name="up_reserve_use" id="up_reserve_use0" value="0" />
				<!--
				<tr>
					<td class="table_cell"><img src="images/icon_point5.gif" border="0">���� ��� ����</td>
					<td class="td_con1">

						<? if ($reserve_use) {?>
						<b>������ : </b>
						<input type=radio name=up_reserve_use id=up_reserve_use1 value="1" <?if($_vmdata->reserve_use=="1")echo"checked";?>> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=up_reserve_use1>���</label>
						<img width=20 height=0>
						<input type=radio name=up_reserve_use id=up_reserve_use0 value="0" <?if($_vmdata->reserve_use=="0" || strlen($_vmdata->reserve_use)==0)echo"checked";?>> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=up_reserve_use0>��� ����</label>
						<br/>
						<? } ?>

						<!-- <? if ($coupon_use) {?>
						<b>���� : </b>
						<input type=radio name=up_coupon_use id=up_coupon_use1 value="1" <?if($_vmdata->coupon_use=="1")echo"checked";?>> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=up_coupon_use1>���</label>
						<img width=20 height=0>
						<input type=radio name=up_coupon_use id=up_coupon_use0 value="0" <?if($_vmdata->coupon_use=="0" || strlen($_vmdata->coupon_use)==0)echo"checked";?>> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=up_coupon_use0>��� ����</label>
						<br/>
						<? } ?> --//>
						<span class="font_blue">
						* ���Ұ� üũ �� ������� ������ ����� �� ������ �ش�޴��� ������ ������忡 ������� �ʽ��ϴ�.
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

			<? /* �߰� jdy */?>

				<TR>
					<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">��Ÿ����</TD>
					<TD class="td_con1">
						<textarea name="up_etc" cols="80" rows="5" ><?= $_vmdata->etc ?></textarea>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">�����ڸ޸�</TD>
					<TD class="td_con1">
						<textarea name="up_admin_memo" cols="80" rows="5" ><?= $_vmdata->admin_memo ?></textarea>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">��������å����<br/>�����丮</TD>
					<TD class="td_con1">
					<iframe src="vender_ch_pop.php?vender=<?=$vender ?>&type=if" width="780" height="100" frameborder=0 framespacing=0 marginheight=0 marginwidth=0 scrolling=no vspace=0 onload="autoResize(this)" ></iframe>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif" style="height:1px"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point5.gif" width="8" height="11" border="0">��Ź������</TD>
					<TD class="td_con1">
						<?
						$sql = "SELECT * FROM tbltrustmanage WHERE vender='".$vender."'";
						$result=mysql_query($sql,get_db_conn());
						$data=mysql_fetch_object($result);

						if($data->approve=="Y"){
						?>
							<p>��Ź���� ��ü����� �Ϸ�Ǿ����ϴ�. [<a href="javascript:trustView('<?=$vender?>')">��������</a>]</p>
						<?
						}else if($data->approve=="N"){
						?>
							<p>��Ź���� ��ü��� ��û�� �߽��ϴ�. [<a href="javascript:trustView('<?=$vender?>')">��������</a>]</p>
						<?
						}else if($data->approve=="R"){
						?>
							<p>��Ź���� ��ü��� ��û�� �����Ǿ����ϴ�. [<a href="javascript:trustView('<?=$vender?>')">��������</a>]</p>
						<?
						}else if($data->approve=="C"){
						?>
							<p>��Ź���� ��ü��� ��ҵǾ����ϴ�. [<a href="javascript:trustView('<?=$vender?>')">��������</a>]</p>
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
						<td class="menual_con"><img src="images/icon_8.gif" border="0" align="absmiddle"><span class="font_dotline">������� �������</span></td>
					</tr>
					<tr>
						<td  class="space_top"><span style="padding-left:13px">- ����ݾ� : ������ ��������Ⱓ ������ ������ü ��ۿϷ��ǰ�� �� ���⿡�� �Ǹż�����, ���������� ������, ���������� ���� ��۷Ḧ ���� �ݾ��� ������ �� �����ݾ�<br/>
						<span style="padding-left:13px">- ��������� : �ŷ��� ���� �� ����ݾ��� �����Ǵ� �Ⱓ<br/>
						<span style="padding-left:13px">- ����� : ����������� ������ ��¥(������)<br/>
						<span style="padding-left:13px">- ������ : ����������� ����ݾ��� ������ü���� ����(�Ա�)�ϴ� ��¥<br/>
						<span style="padding-left:13px">- ������ȸ�� : ����ݾ��� ��ȸ�ϴ� ��¥
						</td>
					</tr>
					<tr><td height="20"></td></tr>

					<tr>
						<td class="menual_con"><img src="images/icon_8.gif" border="0" align="absmiddle"><span class="font_dotline">������� ��) </span></td>
					</tr>
					<tr>
						<td  class="space_top">
						<span style="padding-left:13px">* A��ü�� �������� �ſ�10�� 1ȸ �̰�, ������� �����Ϸ� ���� 5�� ������ ���<br/>
						<span style="padding-left:13px">- ��������� : ������ 6�� ~ �̹���5��<br/>
						<span style="padding-left:13px">- ����� : �Ŵ� 5��
						</td>
					</tr>
					<tr><td height="20"></td></tr>
					<tr>
						<td class="menual_con"><img src="images/icon_8.gif" border="0" align="absmiddle"><span class="font_dotline">�������� ������� ��) </span></td>
					</tr>
					<tr>
						<td  class="space_top">
						<span style="padding-left:13px">* B��ü�� �������� �ſ� 5��, 10��, 15��, 20��, 25��, 30��  6ȸ �̰�, ������� �����Ϸ� ���� 5�� ������ ���<br/>
						<span style="padding-left:13px">- ��������� : ������ 26��~������ ����(5�� ����), �̹��� 1��~�̹��� 5��(10�� ����), 6��~10��(15�� ����), 11��~15��(20�� ����), 16��~20��(25�� ����), 21��~ 25��(30�� ����)<br/>
						<span style="padding-left:13px">- ����� : �Ŵ� ����, 5��, 10��, 15��, 20��, 25��
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
			// �˾����� �˻���� �׸��� Ŭ�������� ������ �ڵ带 �ۼ��ϴ� �κ�.

			// �� �ּ��� ���� ��Ģ�� ���� �ּҸ� �����Ѵ�.
			// �������� ������ ���� ���� ��쿣 ����('')���� �����Ƿ�, �̸� �����Ͽ� �б� �Ѵ�.
			var fullAddr = ''; // ���� �ּ� ����
			var extraAddr = ''; // ������ �ּ� ����

			// ����ڰ� ������ �ּ� Ÿ�Կ� ���� �ش� �ּ� ���� �����´�.
			if (data.userSelectedType === 'R') { // ����ڰ� ���θ� �ּҸ� �������� ���
				fullAddr = data.roadAddress;

			} else { // ����ڰ� ���� �ּҸ� �������� ���(J)
				fullAddr = data.jibunAddress;
			}

			// ����ڰ� ������ �ּҰ� ���θ� Ÿ���϶� �����Ѵ�.
			if(data.userSelectedType === 'R'){
				//���������� ���� ��� �߰��Ѵ�.
				if(data.bname !== ''){
					extraAddr += data.bname;
				}
				// �ǹ����� ���� ��� �߰��Ѵ�.
				if(data.buildingName !== ''){
					extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
				}
				// �������ּ��� ������ ���� ���ʿ� ��ȣ�� �߰��Ͽ� ���� �ּҸ� �����.
				fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
			}

			// �����ȣ�� �ּ� ������ �ش� �ʵ忡 �ִ´�.
			document.getElementById(post).value = data.zonecode; //5�ڸ� �������ȣ ���
			document.getElementById(addr1).value = fullAddr;

			// Ŀ���� ���ּ� �ʵ�� �̵��Ѵ�.
			if (addr2 != "") {
				document.getElementById(addr2).focus();
			}
		}
	}).open();
}
</script>



<?=$onload?>
<? INCLUDE "copyright.php"; ?>