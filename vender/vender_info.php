<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");
INCLUDE ("access.php");
include_once($Dir."lib/admin_more.php");

$mode=$_POST["mode"];

// ���� ���� ��ȸ jdy
$shop_more_info = getShopMoreInfo();
$account_rule = $shop_more_info['account_rule'];
// ���� ���� ��ȸ jdy

/* ������ ���� �߰� jdy */
$sql = "SELECT * FROM vender_more_info ";
$sql.= "WHERE vender='".$_VenderInfo->getVidx()."'";

$result=mysql_query($sql,get_db_conn());
$_vmdata=mysql_fetch_object($result);
mysql_free_result($result);
/* ������ ���� �߰� jdy */


/* ���ݹ�� �߰� gura */
$sql = "SELECT * FROM vender_rent ";
$sql.= "WHERE vender='".$_VenderInfo->getVidx()."' and pridx=0";

$result=mysql_query($sql,get_db_conn());
$_ptdata=mysql_fetch_object($result);
mysql_free_result($result);
/* ���ݹ�� �߰� gura */

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
		echo "<html></head><body onload=\"alert('��ǥ�� ������ ��Ȯ�� �Է��ϼ���.')\"></body></html>";exit;
	} else if(strlen($up_com_post)==0 || strlen($up_com_addr)==0) {
		echo "<html></head><body onload=\"alert('����� �ּҸ� ��Ȯ�� �Է��ϼ���.')\"></body></html>";exit;
	} else if(strlen($up_com_biz)==0) {
		echo "<html></head><body onload=\"alert('����� ���¸� ��Ȯ�� �Է��ϼ���.')\"></body></html>";exit;
	} else if(strlen($up_com_item)==0) {
		echo "<html></head><body onload=\"alert('����� ������ ��Ȯ�� �Է��ϼ���.')\"></body></html>";exit;
	} else if(strlen($up_com_tel)==0) {
		echo "<html></head><body onload=\"alert('ȸ�� ��ǥ��ȭ�� ��Ȯ�� �Է��ϼ���.')\"></body></html>";exit;
	} else if(strlen($up_p_name)==0) {
		echo "<html></head><body onload=\"alert('����ڸ��� ��Ȯ�� �Է��ϼ���.')\"></body></html>";exit;
	} else if(strlen($up_p_mobile)==0) {
		echo "<html></head><body onload=\"alert('����� �޴���ȭ�� ��Ȯ�� �Է��ϼ���.')\"></body></html>";exit;
	} else if(strlen($up_p_email)==0) {
		echo "<html></head><body onload=\"alert('����� �̸����� ��Ȯ�� �Է��ϼ���.')\"></body></html>";exit;
	}
	


	/**gura**/
	//���ݹ��
	if($useseason!="1"){//������ü ���������� �ƴѰ�� ����
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

	//���뿩����
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

	//ȯ��
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
	
	//�������
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

	
	// ��ۼ��� ����
	$deli_type = $_POST['deli_type'];
	if (is_array($deli_type)) {
		$deli_type = implode(',', $deli_type);
	}

	$sql.= "deli_type		= '".$deli_type."' ";


	// ��ǥ�̹��� ���
	if( $_FILES['com_image']['error'] == 0 AND $_FILES['com_image']['size'] > 0 AND eregi("image",$_FILES['com_image']['type']) AND $_POST['com_image_del'] != "OK" ) {
		$exte = explode(".",$_FILES['com_image']['name']);
		$exte = $exte[ count($exte)-1 ];
		$com_image_name = "comImgae_".$_VenderInfo->getVidx().".".$exte;
		move_uploaded_file($_FILES['com_image']['tmp_name'],$com_image_url.$com_image_name);
		$sql.= ", com_image = '".$com_image_name."' ";
	}

	//��ǥ�̹��� ����
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
			if(_array($_REQUEST['discount'])){##############################�׷캰 ������####################################				
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
			if(_array($_REQUEST['discount2'])){##############################�׷캰 ��õ��������################################				
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

		echo "<html></head><body onload=\"alert('��û�Ͻ� �۾��� �����Ͽ����ϴ�.');parent.location.reload()\"></body></html>";exit;
	} else {
		echo "<html></head><body onload=\"alert('��û�Ͻ� �۾��� ������ �߻��Ͽ����ϴ�.')\"></body></html>";exit;
	}
}
/* ���� ������ ���� jdy */
else if ($mode=="com"){

	$rq_commission_type = $_POST["rq_commission_type"];
	$rq_rate = $_POST["rq_rate"];
	$rq_name = $_POST["rq_name"];

	//�Ǹ� ������� ��� ������ ���濡���� ����� ���� ex) ��ü-> ����

	if ($rq_commission_type!=($_vmdata->commission_type)) {

		if ($_vmdata->commission_type == "1") {
			$up_history = "���������� -> ��ü������ ".$rq_rate."%�� �����û [����]";
		}else{
			$up_history = "��ü������ ".$_venderdata->rate."% -> ����������� �����û [����]";
		}
	}else{

		if ($_vmdata->commission_type != '') {
			if ($rq_commission_type != "1") {

				if ($rq_rate !=$_venderdata->rate) {
					$up_history = "��ü������ ".$_venderdata->rate."% -> ".$rq_rate."% �� �����û [����]";
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
		echo "<html></head><body onload=\"alert('��û�Ͻ� �۾��� �����Ͽ����ϴ�.');parent.location.reload()\"></body></html>";exit;
	} else {
		echo "<html></head><body onload=\"alert('��û�Ͻ� �۾��� ������ �߻��Ͽ����ϴ�.')\"></body></html>";exit;
	}

}
/* ���� ������ ���� jdy */

$com_tel=explode("-",$_venderdata->com_tel);
$com_fax=explode("-",$_venderdata->com_fax);
$com_p_mobile=explode("-",$_venderdata->p_mobile);
$bank_account=explode("=",$_venderdata->bank_account);


//���뿩
$longrentinfo = venderLongrentCharge($_VenderInfo->getVidx());		

//ȯ��
$refundinfo = venderRefundCommission($_VenderInfo->getVidx());		

//�������
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
		alert("��ȣ(ȸ���)�� �Է��ϼ���.");
		return;
	}
	if(CheckLength(form.up_com_name)>30) {
		form.up_com_name.focus();
		alert("��ȣ(ȸ���)�� �ѱ�15�� ����30�� ���� �Է� �����մϴ�");
		return;
	}
	if (!form.up_com_num.value) {
		form.up_com_num.focus();
		alert("����ڵ�Ϲ�ȣ�� �Է��ϼ���.");
		return;
	}
/*
	var bizno;
	var bb;
	bizno = form.up_com_num.value;
	bizno = bizno.replace("-","");
	bb = chkBizNo(bizno);
	if (!bb) {
		alert("�������� ���� ����ڵ�Ϲ�ȣ �Դϴ�.\n����ڵ�Ϲ�ȣ�� �ٽ� �Է��ϼ���.");
		form.up_com_num.value = "";
		form.up_com_num.focus();
		return;
	}
*/
	if (!form.up_com_owner.value) {
		form.up_com_owner.focus();
		alert("��ǥ�� ������ �Է��ϼ���.");
		return;
	}
	if(CheckLength(form.up_com_owner)>12) {
		form.up_com_owner.focus();
		alert("��ǥ�� ������ �ѱ� 6���ڱ��� �����մϴ�");
		return;
	}
	if (!form.up_com_post1.value) {
		form.up_com_post1.focus();
		alert("�����ȣ�� �Է��ϼ���.");
		return;
	}
	if (!form.up_com_addr.value) {
		form.up_com_addr.focus();
		alert("����� �ּҸ� �Է��ϼ���.");
		return;
	}
	if(CheckLength(form.up_com_biz)>30) {
		form.up_com_biz.focus();
		alert("����� ���´� �ѱ� 15�ڱ��� �Է� �����մϴ�");
		return;
	}
	if(CheckLength(form.up_com_item)>30) {
		form.up_com_item.focus();
		alert("����� ������ �ѱ� 15�ڱ��� �Է� �����մϴ�");
		return;
	}
	if(form.up_com_tel1.value.length==0 || form.up_com_tel2.value.length==0 || form.up_com_tel3.value.length==0) {
		form.up_com_tel1.focus();
		alert("ȸ�� ��ǥ��ȭ�� �Է��ϼ���.");
		return;
	}
	if(!isNumber(form.up_com_tel1.value) || !isNumber(form.up_com_tel2.value) || !isNumber(form.up_com_tel3.value)) {
		form.up_com_tel1.focus();
		alert("��ȭ��ȣ�� ���ڸ� �Է��ϼ���.");
		return;
	}
	if(form.up_com_fax1.value.length>0 && form.up_com_fax2.value.length>0 && form.up_com_fax3.value.length>0) {
		if(!isNumber(form.up_com_fax1.value) || !isNumber(form.up_com_fax2.value) || !isNumber(form.up_com_fax3.value)) {
			form.up_com_fax1.focus();
			alert("�ѽ���ȣ�� ���ڸ� �Է��ϼ���.");
			return;
		}
	}
	if(form.up_p_name.value.length==0) {
		form.up_p_name.focus();
		alert("����� �̸��� �Է��ϼ���.");
		return;
	}
	if(form.up_p_mobile1.value.length==0 || form.up_p_mobile2.value.length==0 || form.up_p_mobile3.value.length==0) {
		form.up_com_tel1.focus();
		alert("����� �޴���ȭ�� �Է��ϼ���.");
		return;
	}
	if(!isNumber(form.up_p_mobile1.value) || !isNumber(form.up_p_mobile2.value) || !isNumber(form.up_p_mobile3.value)) {
		form.up_com_tel1.focus();
		alert("����� �޴���ȭ ��ȣ�� ���ڸ� �Է��ϼ���.");
		return;
	}
	if(form.up_p_email.value.length==0) {
		form.up_p_email.focus();
		alert("����� �̸����� �Է��ϼ���.");
		return;
	}
	if(!IsMailCheck(form.up_p_email.value)) {
		form.up_p_email.focus();
		alert("����� �̸����� ��Ȯ�� �Է��ϼ���.");
		return;
	}
	if(form.up_bank1.value.length==0 || form.up_bank2.value.length==0 || form.up_bank3.value.length==0) {
		alert("������� ���������� ��Ȯ�� �Է��ϼ���.");
		form.up_bank1.focus();
		return;
	}
	if(form.up_passwd.value.length>0 || form.up_passwd2.value.length>0) {
		if(form.up_passwd.value!=form.up_passwd.value) {
			alert("�����Ͻ� ��й�ȣ�� ��ġ���� �ʽ��ϴ�.");
			form.up_passwd2.focus();
			return;
		} else if(form.up_passwd.value.length<4) {
			alert("��й�ȣ�� ����, ���ڸ� ȥ���Ͽ� 4~12�� �̳��� �Է��ϼ���.");
			form.up_passwd.focus();
			return;
		}
	}


	if(confirm("�����Ͻ� ������ �����Ͻðڽ��ϱ�?")) {
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
			alert("�����Ḧ �Է����ּ���.");
			form.rq_rate.focus();
			return;
		}
	}

	if(form.rq_name.value.length==0) {
		alert("��û�ڸ� �Է����ּ���.");
		form.rq_name.focus();
		return;
	}


	if(confirm("������ ������ ��û�Ͻðڽ��ϱ�??")) {
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
					html = '<div>���� 12�ð� ���: 24�ð� ����� <input type="text" name="halfday_percent" size="3" maxlength="4" value="'+data.halfday_percent+'">%</div>';
					$j('#price1').html(html);
				}

				if(data.oneday_ex=="time"){
					html = '<div>�߰� 1�ð� ���: 24�ð� ����� <input type="text" name="time_percent" size="3" maxlength="4" value="'+data.time_percent+'">%</div>';
					$j('#price2').html(html);
				}else if(data.oneday_ex=="half"){
					html = '<div>�߰� 12�ð� ���: 24�ð� ����� <input type="text" name="time_percent" size="3" maxlength="4" value="'+data.time_percent+'">%</div>';
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
											<td class="notice_gray" height="20"><img src="images/icon_dot02.gif" border=0 hspace="4">������ ������ ���� �� ��Ÿ ���� ���� �Է��մϴ�.</td>
										</tr>
										<tr>
											<td class="notice_gray" height="20"><img src="images/icon_dot02.gif" border=0 hspace="4">�Է��� ������ ���� ����Ʈ ������ü ������ �Էµ˴ϴ�.</td>
										</tr>
										<tr>
											<td class="notice_gray" height="20"><img src="images/icon_dot02.gif" border=0 hspace="4">������ �������� ��ǰ ó������[���/����/����/����]�� ���� �����ڰ� ���� �� ���� �մϴ�.</td>
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

			<!-- ó���� ���� ��ġ ���� -->
			<tr><td height=40></td></tr>
			<tr>
				<td >
				
				<form name=form1 method=post action="<?=$_SERVER[PHP_SELF]?>" enctype="multipart/form-data">
				<input type=hidden name=mode>
				<table border=0 cellpadding=0 cellspacing=0 width=100%>

				<tr>
					<td><img src="images/venter_info_stitle01.gif" alt="������ü �⺻����" align="absmiddle"><font style="color:#2A97A7">('*'ǥ�ô� �ʼ��Է��Դϴ�)</font></td>
				</tr>
				<tr><td height=5></td></tr>
				<tr><td height=2 bgcolor=#808080></td></tr>
				</table>
				<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
				<col width=140></col>
				<col width=></col>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B><font color=red>*</font> ��ȣ(ȸ���)</B></td>
					<td style=padding:10>
					<input type="text" class=input  name=up_com_name value="<?=$_venderdata->com_name?>" size=20 maxlength=30 disabled>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B><font color=red>*</font> ����ڵ�Ϲ�ȣ</B></td>
					<td style=padding:10>
					<input type="text" class=input  name=up_com_num value="<?=$_venderdata->com_num?>" size=20 maxlength=20 onkeyup="strnumkeyup(this)" disabled>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>

				<!--
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B><font color=red>*</font> ������ �������</B></td>
					<td style=padding:10>
						<input type="radio" name="com_nametech" value="1" />�����&nbsp;&nbsp;
						<input type="radio" name="com_nametech" value="0" checked />������
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				-->

				<tr>
					<td bgcolor="#F5F5F5" background="images/line01.gif" style="background-repeat:repeat-y; background-position:right; padding:9px;"><B><font color=red>*</font> ��ǥ�̹���</B></td>
					<td style="padding:7px 10px;">

						<div style="margin:5px;">
							<div style="float:left; margin:0px; padding:0px; font-size:0px;"><img src="<?=$com_image_url.$_venderdata->com_image?>" width="120" onerror="this.src='/images/no_img.gif';" style="border:1px solid #dddddd;" /></div>
							<div style="float:left; margin-top:5px; margin-left:10px;">
								<div>
									<span style="font-size:11px; color:#666666; line-height:15px; letter-spacing:-1px;">
										<strong>������� : </strong><input type="checkbox" name="com_nametech" value="1" <?=($_venderdata->com_nametech?"checked":"");?>><br /><br /><br />

										�� <b>������ �̹�����??</b>
										<div style="margin:5px 0px;"><img src="images/vender_nametek_sample.gif" style="border:1px solid #e5e5e5;" alt="" /></div>
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

						<!--
						<strong>������� : </strong><input type="checkbox" name="com_nametech" value="1" <?=($_venderdata->com_nametech?"checked":"");?>><br /><br />
						<img src="<?=$com_image_url.$_venderdata->com_image?>" onerror="this.src='/images/no_img.gif';">
						<input type="file" name="com_image" id="com_image">
						<input type="checkbox" name="com_image_del" id="com_image_del" value="OK" onclick="com_image.style.display=( this.checked ?'none':'inline')"><label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="com_image_del">����</label>
						<input type="hidden" name="com_image_del_file" value="<?=$com_image_url.$_venderdata->com_image?>">
						<br /> �̹��� ������ 100px * 100px ����.
						-->

					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>


				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B><font color=red>*</font> ��ǥ�� ����</B></td>
					<td style=padding:10>
					<input class=input type="text" name=up_com_owner value="<?=$_venderdata->com_owner?>" size=20 maxlength="12">
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td rowspan=2 bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B><font color=red>*</font> ����� �ּ�</B></td>
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
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B><font color=red>*</font> ����� ����</B></td>
					<td style=padding:10>
					<input type="text" class=input  name=up_com_biz value="<?=$_venderdata->com_biz?>" size=30 maxlength=30>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B><font color=red>*</font> ����� ����</B></td>
					<td style=padding:10>
					<input type=text class=input  name=up_com_item value="<?=$_venderdata->com_item?>" size=30 maxlength=30>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B><font color=red>*</font> ȸ�� ��ǥ��ȭ</B></td>
					<td style=padding:10>
					<input type=text class=input  name=up_com_tel1 value="<?=$com_tel[0]?>" size=4 maxlength=4 style="width:40" onkeyup="strnumkeyup(this)">-<input type=text class=input  name=up_com_tel2 value="<?=$com_tel[1]?>" size=4 maxlength=4 style="width:40" onkeyup="strnumkeyup(this)">-<input type=text class=input  name=up_com_tel3 value="<?=$com_tel[2]?>" size=4 maxlength=4 style="width:40" onkeyup="strnumkeyup(this)">
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>



				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B> ����ǸŽŰ�</B></td>
					<td style=padding:10>
					<input type=text class=input  name=ec_num value="<?=$_venderdata->ec_num?>" size=30 maxlength=30>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>


				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B> ����ڱ���</B></td>
					<td style=padding:10>
					<input type=text class=input  name=com_type value="<?=$_venderdata->com_type?>" size=30 maxlength=30>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>




				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>ȸ�� �ѽ���ȣ</B></td>
					<td style=padding:10>
					<input type=text class=input  name=up_com_fax1 value="<?=$com_fax[0]?>" size=4 maxlength=4 style="width:40" onkeyup="strnumkeyup(this)">-<input type=text class=input  name=up_com_fax2 value="<?=$com_fax[1]?>" size=4 maxlength=4 style="width:40" onkeyup="strnumkeyup(this)">-<input type=text class=input  name=up_com_fax3 value="<?=$com_fax[2]?>" size=4 maxlength=4 style="width:40" onkeyup="strnumkeyup(this)">
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>ȸ�� Ȩ������</B></td>
					<td style=padding:10>
					http://<input type=text class=input  name=up_com_homepage value="<?=$_venderdata->com_homepage?>" size=30 maxlength=50>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				</table>






				<table border=0 cellpadding=0 cellspacing=0 width=100%>
				<tr><td height=40></td></tr>
				<tr>
					<td><img src="images/venter_info_stitle02.gif" alt="��ü ����� ����" align="absmiddle"> <font style="color:#2A97A7">('*'ǥ�ô� �ʼ��Է��Դϴ�)</font></td>
				</tr>
				<tr><td height=5></td></tr>
				<tr><td height=2 bgcolor=#808080></td></tr>
				</table>
				<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
				<col width=140></col>
				<col width=></col>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B><font color=red>*</font> ����� �̸�</B></td>
					<td style=padding:10>
					<input type=text class=input  name=up_p_name value="<?=$_venderdata->p_name?>" size=20 maxlength=20> &nbsp; <span class="notice_blue">* ���� ����� �̸��� ��Ȯ�� �Է��ϼ���.</font>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B><font color=red>*</font> ����� �޴���ȭ</B></td>
					<td style=padding:10>
					<input type=text class=input  name=up_p_mobile1 value="<?=$com_p_mobile[0]?>" size=4 maxlength=3 style="width:40" onkeyup="strnumkeyup(this)">-<input type=text class=input  name=up_p_mobile2 value="<?=$com_p_mobile[1]?>" size=4 maxlength=4 style="width:40" onkeyup="strnumkeyup(this)">-<input type=text class=input  name=up_p_mobile3 value="<?=$com_p_mobile[2]?>" size=4 maxlength=4 style="width:40" onkeyup="strnumkeyup(this)"></td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B><font color=red>*</font> ����� �̸���</B></td>
					<td style=padding:10>
					<input type=text class=input  name=up_p_email value="<?=$_venderdata->p_email?>" size=30 maxlength=50> &nbsp; <span class="notice_blue">* �ֹ�Ȯ�ν� ����� �̸��Ϸ� �뺸�˴ϴ�.</font>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>����� �μ���</B></td>
					<td style=padding:10>
					<input type=text class=input  name=up_p_buseo value="<?=$_venderdata->p_buseo?>" size=20 maxlength=20>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>����� ����</B></td>
					<td style=padding:10>
					<input type=text class=input  name=up_p_level value="<?=$_venderdata->p_level?>" size=20 maxlength=20>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				</table>

				<table border=0 cellpadding=0 cellspacing=0 width=100%>
				<tr><td height=40></td></tr>
				<tr>
					<td><img src="images/venter_info_stitle04.gif" alt="�̴ϼ� �ID ����" align="absmiddle"></td>
				</tr>
				<tr><td height=5></td></tr>
				<tr><td height=2 bgcolor=#808080></td></tr>
				</table>
				<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
				<col width=140></col>
				<col width=></col>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>� ID</td>
					<td style=padding:10>
					<B><?=$_venderdata->id?></B>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>��й�ȣ ����</B></td>
					<td style=padding:10>
					<input class=input  type=password name=up_passwd size=15> &nbsp; <span class="notice_blue">* ����, ���ڸ� ȥ���Ͽ� ���(4�� ~ 12��)</font>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>��й�ȣ Ȯ��</B></td>
					<td style=padding:10>
					<input class=input type=password name=up_passwd2 size=15> &nbsp; <span class="notice_blue">* ��й�ȣ�� ���������� ���� �Ͻ� ���� �����մϴ�.</font>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>��� ���� ����</B></td>
					<td style=padding:10>
					<input type=radio name=up_session value="N" id="idx_sessionN"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_sessionN">�α��� ���� ����</label><img width=5 height=0><input type=radio name=up_session value="Y" id="idx_sessionY"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_sessionY">�α��� ���� ����</label><br>
					<span class="notice_blue">* �α��� ���� ������ �ڽ��� ������ ��� ��ڵ��� ��α��� �� �̿��� �����մϴ�.</font>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				</table>



				<table border=0 cellpadding=0 cellspacing=0 width=100%>
				<tr><td height=40></td></tr>
				<tr>
					<td><img src="images/venter_info_stitle03.gif" alt="��ü ��������" align="absmiddle"> <font style="color:#2A97A7">('*'ǥ�ô� �ʼ��Է��Դϴ�)</font></td>
				</tr>
				<tr><td height=5></td></tr>
				<tr><td height=2 bgcolor=#808080></td></tr>
				</table>

				<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed" id="rentOptTable">
				<col width=140></col>
				<col width=></col>

				<? if ($account_rule!="1")  { ?>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>������ �����</td>
					<td style=padding:10>
					<B><? if ($_vmdata->commission_type=="1") { ?>��ǰ�� ������ ����<? }else{ ?>��ü��ǰ ���� ������ ����<? } ?></B>
					&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" style="color:#ffffff;background-color:#000000;border:0;width:80px;height:25px;cursor:pointer" onclick="commissionDivView();">�����û</button>
					<div id="commission_div" style="position:absolute;width:450px;border:2px solid #acacac;background-color:#ffffff;z-index:999;padding:5px;display:none;">
						<div style="width:100%;text-align:right"><span style="border:1px solid gray;color:#ffffff;background-color:#000000;padding:2px 4px;cursor:pointer" onclick="commissionDivView('N');" >X</span></div>
						<div style="width:100%;margin-top:5px;">
							<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
								<col width=100 />
								<col width= />
							<tr><td height=2 colspan="2" bgcolor=#808080></td></tr>
							<tr>
								<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>�����</td>
								<td style=padding:10>
								<input type=radio name=rq_commission_type id=rq_commission_type0 value="1" onclick="selCommission('0');"> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=rq_commission_type0>��ǰ���� ������</label>
								&nbsp;&nbsp;
								<input type=radio name=rq_commission_type id=rq_commission_type1 value="0" onclick="selCommission('1');" checked> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=rq_commission_type1>��ü��ǰ ���� ������</label>
								</td>
								<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
								<tr id="commission_all" >
									<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>��ü ������</td>
									<td style=padding:10>
										<input type=text name=rq_rate value="" size=3 maxlength=3 onkeyup="strnumkeyup(this)" class=input>%
									</td>
								</tr>
								<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
								<tr id="commission_all" >
									<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>��û�� �̸�</td>
									<td style=padding:10>
										<input type=text name=rq_name value="" size=10 class=input>
									</td>
								</tr>
								<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
								<tr><td></td>
									<td style="padding-top:10px;text-align:right;"><span style="border:1px solid gray;color:#ffffff;background-color:#000000;padding:2px 4px;cursor:pointer" onclick="commissionRequest()">��û</span></td>
								</tr>
							</tr>
							</table>
						</div>
					</div>
					<? if ($_vmdata->commission_status=="1" || $_vmdata->commission_status=="2") {

							if ($_vmdata->rq_commission_type=="1") {
								$cm_value = "��ǰ�� ������";
							}else{
								$cm_value = "��ü��ǰ ���� ������ ".$_vmdata->rq_rate."%";
							}

							$cm_status = "";

							if ($_vmdata->commission_status=="1") {
								$cm_status = "��û ��";
							}else if ($_vmdata->commission_status=="2") {
								$cm_status = "��û �ź�";
							}

						?>
					<br/><br/>
					<span class="notice_blue"><?= $cm_value ?>�� <?= $cm_status ?></span>
					<? } ?>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<? if (!$_vmdata->commission_type) {?>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>��ü ������</td>
					<td style=padding:10>
					<B><?=(int)$_venderdata->rate?> %</B>
					&nbsp;&nbsp;&nbsp;&nbsp; <span class="notice_blue">* ����ǰ�� ���� ����˴ϴ�.</font>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<? } ?>

				<? } ?>

				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>��ǰ ó�� ����</td>
					<td style=padding:10>
					<input type=checkbox name=chk_prdt1 value="Y" <?if(substr($_venderdata->grant_product,0,1)=="Y")echo"checked";?> disabled>���
					<img width=5 height=0>
					<input type=checkbox name=chk_prdt2 value="Y" <?if(substr($_venderdata->grant_product,1,1)=="Y")echo"checked";?> disabled>����
					<img width=5 height=0>
					<input type=checkbox name=chk_prdt3 value="Y" <?if(substr($_venderdata->grant_product,2,1)=="Y")echo"checked";?> disabled>����
					<img width=5 height=0>
					<input type=checkbox name=chk_prdt4 value="Y" <?if(substr($_venderdata->grant_product,3,1)=="Y")echo"checked";?> disabled>���/������, ������ ����
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>���� ��ǰ�� ����</td>
					<td style=padding:10>
					<B><?=($_venderdata->product_max==0?"������ ��� ����":$_venderdata->product_max."�� ���� ��ǰ��� ����")?></B>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>�Ǹ� ������</td>
					<td style=padding:10>
					<B><?=(int)$_venderdata->rate?> %</B>
					&nbsp;&nbsp;&nbsp;&nbsp; <span class="notice_blue">* ���θ� ���翡�� �޴� ��ǰ�Ǹ� �������Դϴ�.</font>
					</td>
				</tr>
				
				<? /*�߰� gura */?>
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
								$j('#longrentHelpDiv').append("<br>��ǰ��� �� ���� ���������� �뿩��ǰ�� �ش��ϴ� ī�װ��� ���뿩 ������ ���� �����θ����簡 ����� ���� �����ϴ�.");
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
								$j('#priceHelpDiv').append("<br>��ǰ��� �� ���� ���������� �뿩��ǰ�� �ش��ϴ� ī�װ��� ���ݹ�� ������ ���� �����θ����簡 ����� ���� �����ϴ�.");
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
								$j('#refundHelpDiv').append("<br>��ǰ��� �� ���� ���������� �뿩��ǰ�� �ش��ϴ� ī�װ��� ȯ�� ������ ���� �����θ����簡 ����� ���� �����ϴ�.");
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
								$j('#longdiscHelpDiv').append("<br>��ǰ��� �� ���� ���������� �뿩��ǰ�� �ش��ϴ� ī�װ��� ������� ������ ���� �����θ����簡 ����� ���� �����ϴ�.");
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
								$j('#reserveHelpDiv').append("<br>��ǰ��� �� ���� ���������� �뿩��ǰ�� �ش��ϴ� ī�װ��� ȸ����޺� ���� ������ ���� �����θ����簡 ����� ���� �����ϴ�.");
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
								$j('#reseller_reserveHelpDiv').append("<br>��ǰ��� �� ���� ���������� �뿩��ǰ�� �ش��ϴ� ī�װ��� ��õ�� ���� ������ ���� �����θ����簡 ����� ���� �����ϴ�.");
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
								$j('#seasonHelpDiv').append("<br>��ǰ��� �� ���� ���������� �뿩��ǰ�� �ش��ϴ� ī�װ��� ������� ������ ���� �����θ����簡 ����� ���� �����ϴ�.");
							},'json');
					});
					
					$j('#rentOptTable').on('mouseout','.seasonHelp',function(){
						$j('#seasonHelpDiv').css('display','none');
					});
	
				});
				</script>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>���� �Ӽ�</td>
					<td style=padding:10>
						<select name="category">
							<option value="">�����ϼ���</option>
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
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>�뿩���ɽð�</td>
					<td style=padding:10>
						����: <input type="text" name="rent_stime" id="rent_stime" size="3" maxlength="2" value="<?=$_ptdata->rent_stime?>">�� ~
						����: <input type="text" name="rent_etime" id="rent_etime" size="3" maxlength="2" value="<?=$_ptdata->rent_etime?>">�� 
						*24�ð� �뿩�� ��� ���۰� ����ð��� ���� �����ϼ���.
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>���ݹ�� ����</td>
					<td style=padding:10>
					<input type=radio name=pricetype value="0" <?if($_venderdata->pricetype=="0")echo"checked";?> onclick="pricetypeDivView('N')">���� ��å�� ���� <input type="button" class="priceHelp" style="width:30px;" value="?" />
					<input type=radio name=pricetype value="1" <?if($_venderdata->pricetype=="1")echo"checked";?> onclick="pricetypeDivView()">������ü ���� ����

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
								html = '<div>���� 12�ð� ���: 24�ð� ����� <input type="text" name="halfday_percent" size="3" maxlength="4">%</div>';
								$j('#price1').html(html);
							}else{
								html = '';
								$j('#price1').html(html);
							}
							
						}

						function onedayexCheck(val){			
							if(val=="time"){
								html = '<div>�߰� 1�ð� ���: 24�ð� ����� <input type="text" name="time_percent" size="3" maxlength="4">%</div>';
								$j('#price2').html(html);
							}else if(val=="half"){
								html = '<div>�߰� 12�ð� ���: 24�ð� ����� <input type="text" name="time_percent" size="3" maxlength="4">%</div>';
								$j('#price2').html(html);
							}else{
								html = '';
								$j('#price2').html(html);
							}
							
						}
						</script>
						<table cellpadding="0" cellspacing="0" style="width:100%;margin-top:7px;padding:7px 7px 7px 7px; border-bottom:0px;border:2px solid #acacac;background-color:#ffffff">
							<tr>
								<th style="width:100px;">���ݹ��</th>
								<td class="norbl" style="padding:5px;">
									<select name="vender_rent" id="vender_rent" style="width:120px" onchange="javascript:chPriceType()">
										<option value="">�����ϼ���</option>
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
									<table id="day_div" cellpadding="0" cellspacing="0" style="display:<?=$display?>;width:100%;margin-top:7px;padding:7px 7px 7px 7px;">
										<tr>
											<th style="width:150px;">���� 12�ð� �뿩���</th>
											<td class="norbl" style="padding:5px;">
												<input type=radio name=halfday value="Y" <?if($_ptdata->halfday=="Y")echo"checked";?> onclick="halfdayCheck('Y')">��
												<input type=radio name=halfday value="N" <?if($_ptdata->halfday=="N")echo"checked";?> onclick="halfdayCheck('N')">�ƴϿ�
											</td>
											<td id="price1">
												<?
												if($_ptdata->halfday=="Y"){
													echo '<div>���� 12�ð� ���: 24�ð� ����� <input type="text" name="halfday_percent" size="3" maxlength="4" value="'.$_ptdata->halfday_percent.'">%</div>';
												}
												?>
											</td>
										</tr>
										<tr>
											<th>1�� �ʰ��� ���ݱ���</th>
											<td class="norbl" style="padding:5px;">
												<input type=radio name=oneday_ex value="day" <?if($_ptdata->oneday_ex=="day")echo"checked";?> onclick="onedayexCheck('day')">1�� ����
												<input type=radio name=oneday_ex value="half" <?if($_ptdata->oneday_ex=="half")echo"checked";?> onclick="onedayexCheck('half')">12�ð� ����
												<input type=radio name=oneday_ex value="time" <?if($_ptdata->oneday_ex=="time")echo"checked";?> onclick="onedayexCheck('time')">1�ð� ����
											</td>
											<td id="price2">
												<?
												if($_ptdata->oneday_ex=="time"){
													echo '<div>�߰� 1�ð� ���: 24�ð� ����� <input type="text" name="time_percent" size="3" maxlength="4" value="'.$_ptdata->time_percent.'">%</div>';
												}else if($_ptdata->oneday_ex=="half"){
													echo '<div>�߰� 12�ð� ���: 24�ð� ����� <input type="text" name="time_percent" size="3" maxlength="4" value="'.$_ptdata->time_percent.'">%</div>';
												}
												?>
											</td>
										</tr>
									</table>
									<? if($_ptdata->pricetype == 'time') $display = ""; else $display = "none"; ?>
									<table id="time_div" cellpadding="0" cellspacing="0" style="display:<?=$display?>;width:100%;margin-top:7px;padding:7px 7px 7px 7px;">
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
									<table id="checkout_div" cellpadding="0" cellspacing="0" style="display:<?=$display?>;width:100%;margin-top:7px;padding:7px 7px 7px 7px;">
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
												<input type="text" name="base_period" size="5" value="<?=$_ptdata->base_period?>" onkeyup="javascript:$j('#addLongrent_sday').val(parseInt($j('input[name=base_period]').val())+1);">�� ����
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
					<td style=padding:10>
					<input type=radio name=longrent value="0" <?if($_venderdata->longrent=="0")echo"checked";?> onclick="longrentDivView('N')">���� ��å�� ���� <input type="button" class="longrentHelp" style="width:30px;" value="?" /> 
					<input type=radio name=longrent value="1" <?if($_venderdata->longrent=="1")echo"checked";?> onclick="longrentDivView()">������ü ���� ����

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
								alert('��¥�� �ùٸ��� �Է��ϼ���.');
								$j('#addLongrent_sday').focus();
							}else if(isNaN(ed) || ed < 1){
								alert('��¥�� �ùٸ��� �Է��ϼ���.');
								$j('#addLongrent_eday').focus();
							}else if(isNaN(p) || p < 1){
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
						<table cellpadding="0" cellspacing="0" style="margin-top:7px;padding:7px 7px 7px 7px; border-bottom:0px;border:2px solid #acacac;background-color:#ffffff">
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
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>ȯ�� ��å</td>
					<td style=padding:10>
					<input type=radio name=refund value="0" <?if($_venderdata->refund=="0")echo"checked";?> onclick="refundDivView('N')">���� ��å�� ���� <input type="button" class="refundHelp" style="width:30px;" value="?" /> 
					<input type=radio name=refund value="1" <?if($_venderdata->refund=="1")echo"checked";?> onclick="refundDivView()">������ü ���� ����

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

						function addRefundCommi2(){
							var d = parseInt($j('#addRefundDay2').val());
							var p = parseInt($j('#addRefundPercent2').val());
							if(isNaN(p) || p < 1|| p>100){
								alert('�����Ḧ �ùٸ��� �Է��ϼ���.');
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
									alert('�ߺ��� ���ڰ� �ֽ��ϴ�. ���� �ߺ� �׸��� ������ �߰� �Ͻñ� �ٶ��ϴ�.');
								}else{
									html = '<div><input type="hidden" name="refundday[]" value="'+d+'"><input type="hidden" name="refundpercent[]" value="'+p+'"><span style="float:left">����ȯ��(��� ��) '+p+'%</span><img src="../admin/images/btn_del.gif" alt="����" align="right" /></div>';
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
								alert('�����Ḧ �ùٸ��� �Է��ϼ���.');
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
									alert('�ߺ��� ���ڰ� �ֽ��ϴ�. ���� �ߺ� �׸��� ������ �߰� �Ͻñ� �ٶ��ϴ�.');
								}else{
									html = '<div><input type="text" name="refundday[]" value="'+d+'"><input type="hidden" name="refundpercent[]" value="'+p+'"><span style="float:left">����ȯ��(��� ��) '+p+'%</span><img src="../admin/images/btn_del.gif" alt="����" align="right" /></div>';
									$j('#refundDiv').prepend(html);
									$j('#addRefundDay3').val('0');
									$j('#addRefundPercent3').val('');
								}
							}
							
						}
						</script>
						<table cellpadding="0" cellspacing="0" style="margin-top:7px;padding:7px 7px 7px 7px; border-bottom:0px;border:2px solid #acacac;background-color:#ffffff">
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
								</td>
							</tr>
							<tr>
								<th colspan="2">����ȯ��(��� ��)</th>
								<th style="width:100px;">������</th>
								<td style="padding:5px;">
									<input type="hidden" name="addRefundDay2" id="addRefundDay2" value="-1" />
									<input type="text" name="addRefundPercent2" id="addRefundPercent2" value="" style="width:30px;" />
									% </td>
								<td>
									<input type="button" name="addRefundBtn" value="�߰�" onclick="javascript:addRefundCommi2()" />
								</td>
							</tr>
							<tr>
								<th colspan="2">����ȯ��(��� ��)</th>
								<th style="width:100px;">������</th>
								<td style="padding:5px;">
									<input type="hidden" name="addRefundDay3" id="addRefundDay3" value="0" />
									<input type="text" name="addRefundPercent3" id="addRefundPercent3" value="" style="width:30px;" />
									% </td>
								<td>
									<input type="button" name="addRefundBtn" value="�߰�" onclick="javascript:addRefundCommi3()" />
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
									echo "����ȯ��(�����)";
								}else if($rday==0){
									echo "����ȯ��(�����)";
								}else{
									echo $rday."����";
								}
								?>
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
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>���� Ȯ�� ���</td>
					<td style=padding:10>
					<input type=radio name=booking_confirm value="now" <?if($_venderdata->booking_confirm=="now")echo"checked";?>>������ ����  
					<input type=radio name=booking_confirm value="select" <?if($_venderdata->booking_confirm!="now")echo"checked";?>>
					<select name="booking_confirm_time">
						<option value="">����</option>
						<option value="00:10" <?if($_venderdata->booking_confirm=="00:10")echo"selected";?>>10��</option>
						<option value="00:20" <?if($_venderdata->booking_confirm=="00:20")echo"selected";?>>20��</option>
						<option value="00:30" <?if($_venderdata->booking_confirm=="00:30")echo"selected";?>>30��</option>
						<? for($i=1;$i<=24;$i++){?>
						<option value="<?=sprintf('%02d',$i)?>:00" <?if($_venderdata->booking_confirm==sprintf('%02d',$i).":00")echo"selected";?>><?=$i?>�ð�</option>
						<? } ?>
					</select>
					�̳� Ȯ�� �˸�
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>ȸ����޺� ����</td>
					<td style=padding:10>
					<input type=radio name=reserve value="0" <?if($_venderdata->reserve=="0")echo"checked";?>>���� ��å�� ���� 
					<input type="button" class="reserveHelp" style="width:30px;" value="?" />
					<input type=radio name=reserve value="1" <?if($_venderdata->reserve=="1")echo"checked";?> onclick="reserveDivView()">������ü ���� ����

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
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>��õ�� ����</td>
					<td style=padding:10>
					<input type=radio name=reseller_reserve value="0" <?if($_venderdata->reseller_reserve=="0")echo"checked";?>>���� ��å�� ���� 
					<input type="button" class="reseller_reserveHelp" style="width:30px;" value="?" />
					<input type=radio name=reseller_reserve value="1" <?if($_venderdata->reseller_reserve=="1")echo"checked";?> onclick="reseller_reserveDivView()">������ü ���� ����

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
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>������� ����</td>
					<td style=padding:10>
					<input type=radio name=longdiscount value="0" <?if($_venderdata->longdiscount=="0")echo"checked";?> onclick="discountDivView('N')">���� ��å�� ���� <input type="button" class="longdiscHelp" style="width:30px;" value="?" />
					<input type=radio name=longdiscount value="1" <?if($_venderdata->longdiscount=="1")echo"checked";?> onclick="discountDivView()">������ü ���� ����
					
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
						<table cellpadding="0" cellspacing="0" style="margin-top:7px;padding:7px 7px 7px 7px; border-bottom:0px;border:2px solid #acacac;background-color:#ffffff">
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
						
						<div style="padding:3px 0px; clear:both" id="rangeDiscountDiv">
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
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>������ ����</td>
					<td style=padding:10>
					<input type=radio name=useseason value="2" <?if($_venderdata->season=="2")echo"checked";?> onclick="seasonDivView('N')">���� ��å�� ���� <input type="button" class="seasonHelp" style="width:30px;" value="?" />
					<input type=radio name=useseason value="0" <?if($_venderdata->season=="0")echo"checked";?> onclick="seasonDivView('N')">������ 
					<input type=radio name=useseason value="1" <?if($_venderdata->season=="1")echo"checked";?> onclick="seasonDivView()">������ü ���� ������/�񼺼��� ���
					
					<div id="seasonHelpDiv" style="width:250px; padding:10px; height:60px; position:absolute; background:#efefef; border:1px solid #FF0; display:none">
					
					</div>
					<? if($_venderdata->season=="1"){ $display=""; }else{ $display="none"; } ?>
					<div id="season_div" style="position:;width:600px;margin-top:5px;z-index:999;padding:5px;display:<?=$display?>;">
							
						<div id="seasonDiv" style="border:1px solid #efefefe"> 
							<table cellpadding="0" cellspacing="0" width="100%" id="seasonListTbl" style="margin-top:7px;padding:7px 7px 7px 7px; border-bottom:0px;border:2px solid #acacac;background-color:#ffffff">
								</tr>
									<th style="width:120px;">������/�ؼ�����</th>
									<td class="norbl" style="padding:5px;">
										<input type="button" value="������/�ؼ����� ����" style="width:200px;" onclick="window.open('vender_seasonpop.php?vender=<?=$_VenderInfo->getVidx()?>', 'busySeasonPop', 'width=800,height=600' );">
									</td>
								</tr>
								<tr>
									<th class="nobbl">������/�ָ�</th>
									<td style="padding:5px;" class="norbl nobbl">
										<input type="button" value="������/�ָ� ����" style="width:200px;"  onclick="window.open('vender_holiday.php?vender=<?=$_VenderInfo->getVidx()?>', 'holidayPop', 'width=800,height=600' );">
									</td>
								</tr>
							</table>
						</div>

					</div>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>�ߵ������� <br>�ؾ� ���</b></td>
					<td style="padding:10">
						<textarea name="cancel_cont" style="width:80%;height:120px"><?=$_venderdata->cancel_cont?></textarea>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>����ī�� ����</b></td>
					<td style="padding:10">
						<textarea name="discount_card" style="width:80%;height:50px"><?=$_venderdata->discount_card?></textarea>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>��ۼ��ܼ���</b></td>
					<td style=padding:10>
						<?php
							$deli_type_checked = array(5);
							if ($_venderdata->deli_type) {
								$deli_type = explode(',', $_venderdata->deli_type);

								if (in_array('�ù�', $deli_type)) { $deli_type_checked[0] = "checked='checked'"; }
								if (in_array('������', $deli_type)) { $deli_type_checked[1] = "checked='checked'"; }
								if (in_array('�湮����', $deli_type)) { $deli_type_checked[2] = "checked='checked'"; }
								if (in_array('���', $deli_type)) { $deli_type_checked[3] = "checked='checked'"; }
								if (in_array('��ҿ���', $deli_type)) { $deli_type_checked[4] = "checked='checked'"; }
							} else {
								$deli_type_checked[0] = "checked='checked'";
							}
						?>
						<input type="checkbox" name="deli_type[]" id="deli_parsel" value="�ù�" <?=$deli_type_checked[0]?> /><label for="deli_parsel">�ù�</label> <input type="checkbox" name="deli_type[]" id="deli_quick" value="������" <?=$deli_type_checked[1]?> /><label for="deli_quick">������</label> <input type="checkbox" name="deli_type[]" id="deli_visit" value="�湮����" <?=$deli_type_checked[2]?> /><label for="deli_visit">�湮����</label> 
						<input type="checkbox" name="deli_type[]" id="deli_car" value="���" <?=$deli_type_checked[3]?> /><label for="deli_car">���</label> 
						<input type="checkbox" name="deli_type[]" id="deli_place" value="��ҿ���" <?=$deli_type_checked[4]?> /><label for="deli_place">��ҿ���</label>
					</td>
				</tr>
				<? /*�߰� gura */?>

				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B><font color=red>*</font> ���� ��������</td>
					<td style=padding:10>
					���� <input type=text class=input  name=up_bank1 value="<?=$bank_account[0]?>" size=10>
					<img width=5 height=0>
					���¹�ȣ <input type=text class=input  name=up_bank2 value="<?=$bank_account[1]?>" size=20>
					<img width=5 height=0>
					������ <input type=text class=input  name=up_bank3 value="<?=$bank_account[2]?>" size=15>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>������</td>
					<td style=padding:10>
					<?
					switch($_vmdata->adjust_lastday) {
						case 0 : $account_date = $_venderdata->account_date;
							break;
						case 1 : $account_date = "�ſ� ��������";
							break;
						case 2 : $account_date = "�ſ� 15�ϰ� ��������";
							break;
					}
					?>
					<B><?=$account_date?></B>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
			<? /*�߰� jdy */?>
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>�����</td>
					<td style=padding:10>
					<B><?=(strlen($_vmdata->close_date)>0?"�����Ϸ� ���� ".$_vmdata->close_date." ��������":"")?></B>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
			<? /*�߰� jdy */?>

				
				<tr>
					<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>��������å����<br/>�����丮</td>
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
			<!-- ó���� ���� ��ġ �� -->

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
