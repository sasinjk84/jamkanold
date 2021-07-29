<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

//로그인,로그아웃,회원탈퇴 총괄
if(substr(getenv("SCRIPT_NAME"),-17)=="/login_sso_process.php"){
	header("HTTP/1.0 404 Not Found");
	exit;
}

/*
$type=$_REQUEST["type"];	//login,logout,exit
$id=$_POST["id"];
$passwd=$_POST["passwd"];

$ssltype=$_POST["ssltype"];
$sessid=$_POST["sessid"];
$nexturl=$_POST["nexturl"];
*/

$sql = "SELECT * FROM tblmember WHERE member_out ='N' AND email='".$email."' AND loginType='tvcf'";
//if($key) $sql.= "AND key='".$key."' ";
$result = mysql_query($sql,get_db_conn());
if($row=mysql_fetch_object($result)) {
	$memid=$row->id;
	$memname=$row->name;
	$mememail=$row->email;
	$memgroup=$row->group_code;
	$memreserve=$row->reserve;

	if($row->member_out=="Y") {	//탈퇴한 회원
		echo "<html><head><title></title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=euc-kr\"></head><body onload=\"alert('아이디 또는 비밀번호가 틀리거나 탈퇴한 회원입니다.');history.go(".$history.")\"></body></html>";exit;
	}
	if ($_data->member_baro=="Y" && $row->confirm_yn=="N") { //관리자인증기능여부 및 회원인증 검사
		echo "<html><head><title></title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=euc-kr\"></head><body onload=\"alert('쇼핑몰 운영자 인증 후 로그인이 가능합니다.\\n\\n전화로 문의바랍니다.\\n\\n".$_data->info_tel."');history.go(".$history.")\"></body></html>";exit;
	}
	if($_data->coupon_ok=="Y") {
		$date = date("YmdHis");
		$sql2 = "SELECT coupon_code FROM tblcouponissue WHERE id='".$memid."'";
		$result2 = mysql_query($sql2,get_db_conn());
		while($row2=mysql_fetch_object($result2)){
		  $coupon_code.="'".$row2->coupon_code."',";
		}
		$coupon_code = substr($coupon_code,0,-1);
		mysql_free_result($result2);

		$sql2 = "SELECT coupon_code, date_start, date_end, vender FROM tblcouponinfo ";
		$sql2.= "WHERE display='Y' AND issue_type='N' ";
		$sql2.= "AND (member='ALL' OR (member='".$memgroup."' AND member!='')) ";
		$sql2.= "AND (date_end>'".substr($date,0,10)."' OR date_end='') ";
		if(strlen($coupon_code)>0) $sql2.="AND coupon_code NOT IN (".$coupon_code.") ";

		$sql="INSERT INTO tblcouponissue (coupon_code,id,date_start,date_end,date) VALUES ";
		$couponcnt ="";
		$count=0;
		$result2 = mysql_query($sql2,get_db_conn());
		while($row2=mysql_fetch_object($result2)) {
			if($row2->date_start>0) {
				$date_start=$row2->date_start;
				$date_end=$row2->date_end;
			} else {
				$date_start = substr($date,0,10);
				$date_end = date("Ymd",mktime(0,0,0,substr($date,4,2),substr($date,6,2)+abs($row2->date_start),substr($date,0,4)))."23";
			}
			if($row2->vender>0) {	//입점업체에서 발급한 쿠폰의 경우에는 단골매장으로 등록한 회원에게만 지급
				$sql3 = "SELECT COUNT(*) as vr_cnt FROM tblregiststore ";
				$sql3.= "WHERE id='".$memid."' AND vender='".$row2->vender."' ";
				$result3=mysql_query($sql3,get_db_conn());
				$row3=mysql_fetch_object($result3);
				$vr_cnt=$row3->vr_cnt;
				mysql_free_result($result3);
				if($vr_cnt<=0) {
					continue;
				}
			}
			$sql.=" ('".$row2->coupon_code."','".$memid."','".$date_start."','".$date_end."','".$date."'),";
			$couponcnt="'".$row2->coupon_code."',";
			$count++;
		}
		mysql_free_result($result2);
		if($count>0) {
			$sql = substr($sql,0,-1);
			mysql_query($sql,get_db_conn());
			if(!mysql_errno()) {
				$couponcnt = substr($couponcnt,0,-1);
				$sql = "UPDATE tblcouponinfo SET issue_no=issue_no+1 ";
				$sql.= "WHERE coupon_code IN (".$couponcnt.")";
				mysql_query($sql,get_db_conn());
				$msg = "회원 가입시 쿠폰이 발급되었습니다.";
			}
		}
	}
	
	

	mysql_free_result($result);
	
//	echo "<html><head><title></title><meta http-equiv=\"Content-Type\" content=\"text/html; charset=euc-kr\"></head><body onload=\"alert('이미 회원가입이 되어 있는 아이디입니다. 자동 로그인됩니다.')\"></body></html>";

	$authidkey = md5(uniqid(""));
	$_ShopInfo->setMemid($memid);
	$_ShopInfo->setAuthidkey($authidkey);
	$_ShopInfo->setMemgroup($memgroup);
	$_ShopInfo->setMemname($memname);
	$_ShopInfo->setMemreserve($memreserve);
	$_ShopInfo->setMememail($mememail);
	$_ShopInfo->setToken($key);
	$_ShopInfo->Save();

	$sql = "UPDATE tblmember SET ";
	$sql.= "authidkey		= '".$authidkey."', ";
	if($passwd_type=="hash" || $passwd_type=="password" || $passwd_type=="old_password") {
		$sql.= "passwd		= '".md5($passwd)."', ";
	}
	$sql.= "ip				= '".getenv("REMOTE_ADDR")."', ";
	$sql.= "logindate		= '".date("YmdHis")."', ";
	$sql.= "logincnt		= logincnt+1 ";
	$sql.= "WHERE id='".$_ShopInfo->getMemid()."'";
	mysql_query($sql,get_db_conn());

	$loginday = date("Ymd");
	$sql = "SELECT id_list FROM tblshopcountday ";
	$sql.= "WHERE date='".$loginday."'";
	$result = mysql_query($sql,get_db_conn());
	if($row3 = mysql_fetch_object($result)){
		if(!strpos(" ".$row3->id_list,"".$_ShopInfo->getMemid()."")){
			$id_list=$row3->id_list.$_ShopInfo->getMemid()."";
			$sql = "UPDATE tblshopcountday SET id_list='".$id_list."',login_cnt=login_cnt+1 ";
			$sql.= "WHERE date='".$loginday."'";
			mysql_query($sql,get_db_conn());
		}
	} else {
		$id_list="".$_ShopInfo->getMemid()."";
		$sql = "INSERT INTO tblshopcountday (date,count,login_cnt,id_list) VALUES ('".$loginday."',1,1,'".$id_list."')";
		mysql_query($sql,get_db_conn());
	}

	// 홍보URL 로그인
	snsPromoteAccessLogin( $_ShopInfo->authkey, $_ShopInfo->getMemid() );


	//echo "ddd=".$key."/".$_ShopInfo->getToken()."/".$_ShopInfo->getMemid();exit;

	if(!_empty($_REQUEST['reurl'])){					
		Header("Location:".urldecode($reurl));
	} else {
		Header("Location:".$Dir.MainDir."main.php");
	}
	exit;

} else {
	
	$sql="select * from tblmember where member_out='N' AND email='".$email."' ";
	$result=mysql_query($sql,get_db_conn());
	$cnt=mysql_num_rows($result);
	if($cnt > 0){
		echo "<script>alert('\"".$email."\"은 이미 가입되어 있습니다.\\n가입된 아이디로 로그인해 주세요.');location.href='/front/login.php';</script>";
		exit;
	}

	if($gubun=="1"){
		$gubun = "일반";
	}else if($gubun=="2"){
		$gubun = "학생";
	}else if($gubun=="3"){
		$gubun = "전문가";
	}

	//아트디렉터
	if($geekjong=="1024") {
		$memgroup = "RP16";
	}
	//학생
	else if($geekjong=="131072"){
		$memgroup = "RP03";
	}
	//스타일리스트
	else if($geekjong=="8192") {
		$memgroup = "RP17";
	}
	//감독
	else if($geekjong=="64" || $geekjong=="128"){
		$memgroup = "RP15";
	}
	//일반
	else {
		$memgroup = "SP01";
	}
	
	$onload =  "ssoGetUserInfo();";


	/*
	$ip = getenv("REMOTE_ADDR");
	
	if($mememail){
		$sql = "INSERT tblmember SET ";
		$sql.= "id				= '".$id."', ";
		$sql.= "name			= '".$name."', ";
		$sql.= "email			= '".$email."', ";
		$sql.= "mobile			= '".$home_tel."', ";
		$sql.= "birth			= '".$birth."', ";
		$sql.= "gender			= '".$gender."', ";
		$sql.= "logindate		= '".date("YmdHis")."', ";
		$sql.= "gubun			= '".$gubun."', ";
		$sql.= "sosok			= '".$sosok."', ";
		$sql.= "jikjong			= '".$upjong."', ";
		$sql.= "jikgun			= '".$geekjong."', ";
		$sql.= "group_code		= '".$memgroup."', ";
		$sql.= "loginType		= 'tvcf', ";
		$sql.= "logincnt		= 0, ";
		$sql.= "joinip			= '".$ip."', ";
		$sql.= "ip				= '".$ip."', ";
		$sql.= "date			= '".date("YmdHis")."', ";
		$sql.= "devices			= 'P' ";
		mysql_query($sql,get_db_conn());
	}
}

mysql_free_result($result);

$authidkey = md5(uniqid(""));
$_ShopInfo->setMemid($memid);
$_ShopInfo->setAuthidkey($authidkey);
$_ShopInfo->setMemgroup($memgroup);
$_ShopInfo->setMemname($memname);
$_ShopInfo->setMemreserve($memreserve);
$_ShopInfo->setMememail($mememail);
$_ShopInfo->setToken($key);
$_ShopInfo->Save();

$sql = "UPDATE tblmember SET ";
$sql.= "authidkey		= '".$authidkey."', ";
if($passwd_type=="hash" || $passwd_type=="password" || $passwd_type=="old_password") {
	$sql.= "passwd		= '".md5($passwd)."', ";
}
$sql.= "ip				= '".getenv("REMOTE_ADDR")."', ";
$sql.= "logindate		= '".date("YmdHis")."', ";
$sql.= "logincnt		= logincnt+1 ";
$sql.= "WHERE id='".$_ShopInfo->getMemid()."'";
mysql_query($sql,get_db_conn());

$loginday = date("Ymd");
$sql = "SELECT id_list FROM tblshopcountday ";
$sql.= "WHERE date='".$loginday."'";
$result = mysql_query($sql,get_db_conn());
if($row3 = mysql_fetch_object($result)){
	if(!strpos(" ".$row3->id_list,"".$_ShopInfo->getMemid()."")){
		$id_list=$row3->id_list.$_ShopInfo->getMemid()."";
		$sql = "UPDATE tblshopcountday SET id_list='".$id_list."',login_cnt=login_cnt+1 ";
		$sql.= "WHERE date='".$loginday."'";
		mysql_query($sql,get_db_conn());
	}
} else {
	$id_list="".$_ShopInfo->getMemid()."";
	$sql = "INSERT INTO tblshopcountday (date,count,login_cnt,id_list) VALUES ('".$loginday."',1,1,'".$id_list."')";
	mysql_query($sql,get_db_conn());
}

// 홍보URL 로그인
snsPromoteAccessLogin( $_ShopInfo->authkey, $_ShopInfo->getMemid() );


//echo "ddd=".$key."/".$_ShopInfo->getToken()."/".$_ShopInfo->getMemid();exit;

if(!_empty($_REQUEST['reurl'])){					
	Header("Location:".urldecode($reurl));
} else {
	Header("Location:".$Dir.MainDir."main.php");
}
exit;
*/

?><!DOCTYPE HTML>
<HEAD>
<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">
<META http-equiv="X-UA-Compatible" content="IE=Edge" />
<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
</head>
<body>
<form name="ssoLoginForm" method="post" action="/front/member_join.php">
<input type="hidden" name="loginType" value="tvcf">
<input type="hidden" name="id" value="<?=$id?>">
<input type="hidden" name="name" value="<?=$name?>">
<input type="hidden" name="email" value="<?=$email?>">
<input type="hidden" name="home_tel" value="<?=$home_tel?>">
<input type="hidden" name="birth" value="<?=$birth?>">
<input type="hidden" name="gender" value="<?=$gender?>">
<input type="hidden" name="gubun" value="<?=$gubun?>">
<input type="hidden" name="geekjong" value="<?=$geekjong?>">
<input type="hidden" name="sosok" value="<?=$sosok?>">
<input type="hidden" name="upjong" value="<?=$upjong?>">
</form>
<script language="javascript">
<!--
function ssoGetUserInfo(){
	document.ssoLoginForm.submit();
}

<?=$onload?>

//-->
</script>
<!--
<button onclick="javascript:ssoLogin()">로그인</button>

<button onclick="javascript:ssoGetUserInfo()">회원정보</button>
-->
</body>

</html>
<?
}
?>