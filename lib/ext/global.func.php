<?

$includeFiles = array (
	"lib/ext/product_func.php",		// ��ǰ
	"lib/ext/order_func.php",			// �ֹ�
	"lib/ext/order_func.rent.php",			// ��Ż
	"lib/ext/member_func.php",		// ȸ��
	"lib/ext/coupon_func.php",		// ����
	"lib/ext/promote.php"				// ȫ��
);

// �ű� �Լ�
if(strlen(trim($Dir))){
	foreach ( $includeFiles as $f ){ include_once($Dir.$f); }
}else{
	foreach ( $includeFiles as $f ){ include_once($_SERVER['DOCUMENT_ROOT']."/".$f); }
}



function editorImsgeUrlSolv($content,$original=''){
	/** ������ ���� ���� ó�� �߰� �κ� */
	if(!_empty($original) && preg_match_all('/\/data\/editor\/([a-zA-Z0-9\.]+)/',$original,$edtimg)){
		if(!preg_match_all('/\/data\/editor\/([a-zA-Z0-9\.]+)/',$content,$edimg)) $edimg[1] =array();
		foreach($edtimg[1] as $cimg){
			if(!in_array($cimg,$edimg[1])) @unlink($_SERVER['DOCUMENT_ROOT'].'/data/editor/'.$cimg);
		}
	}
	/** #������ ���� ���� ó�� �߰� �κ� */

	/** ������ ���� ���� ó�� �߰� �κ� */
	if(preg_match_all('/\/data\/editor_temp\/([a-zA-Z0-9\.]+)/',$content,$edimg)){
		foreach($edimg[1] as $timg){
			@rename($_SERVER['DOCUMENT_ROOT'].'/data/editor_temp/'.$timg,$_SERVER['DOCUMENT_ROOT'].'/data/editor/'.$timg);
		}
		$content = str_replace('/data/editor_temp/','/data/editor/',$content);
	}
	/** #������ ���� ���� ó�� �߰� �κ� */
	return $content;
}






// 2013-10-11 IP���� x2chi
// (IP,{block,test,print})
//test:���ٰ��ɰ˻� (default)
//block:����
//print:���
function ipAuth ( $ip , $test = "test" ) {

	$ipOrg = $ip;
	$ip = ip2long($ip);

	$ipSQL = "SELECT * FROM `tblConnIP_block` WHERE `IP` = '".$ip."' ";
	$ipResult = mysql_query( $ipSQL , get_db_conn() );
	$auth = ( @mysql_num_rows ( $ipResult ) > 0 )?"block":"access";

	$returnArray = array("ip"=>$ipOrg, "auth"=>$auth);

	if( $auth == "block" AND $test == "block" ) {
		exit(
			"
				<center>
					<img src='/images/blockingImages.jpg' alt='���� �Ͻ� IP [ ".$ipOrg." ] �ش� ����Ʈ���� ���ܵ� IP�Դϴ�.'>
				</center>
			"
		);
	}
	if( $test == "print" ) {
		_pr($returnArray);
		//exit;
	}
	return $returnArray;

}
ipAuth($_SERVER['REMOTE_ADDR'], "block");


// ���� ������ �α�
function connIP ( $ip ) {
	@session_start();
	global $_ShopInfo;

	if( substr($ip,0,4) != "127." ) {

		$ip = ip2long($ip);
		$id = $_ShopInfo->getMemid();
		$key = $ip."_".$id; //date("Ymd")."_".

		if( $_SESSION['connIP'] != $key ) {

			$serverInfo = "";
			foreach ( $_SERVER as $k => $v ){
				$serverInfo .= "|".$k."=".$v;
			}
			$ipInSQL = "INSERT `tblConnIP_list` SET `IP` = '".$ip."' , `conn`=NOW(), `serverInfo` = '".$serverInfo."' ; ";
			@mysql_query( $ipInSQL , get_db_conn() );
			if(strlen($id)>0){
				$ipMemInSQL = "INSERT `tblConnIP_memid` SET `IP` = '".$ip."' , `memid`='".$id."', `conn`=NOW() ; ";
				@mysql_query( $ipMemInSQL , get_db_conn() );
			}
			$ipStatisticsSQL = "SELECT * FROM `tblConnIP_statistics` WHERE `IP` = '".$ip."' ";
			$ipStatisticsResult = mysql_query( $ipStatisticsSQL , get_db_conn() );
			if ( @mysql_num_rows ( $ipStatisticsResult ) == 0 ) {
				$ipStatisticsInputSQL = "INSERT `tblConnIP_statistics` SET `IP` = '".$ip."' ;";
			} else {
				$ipStatisticsInputSQL = "UPDATE `tblConnIP_statistics` SET `count`=`count`+1 WHERE `IP` = '".$ip."' LIMIT 1; ";
			}
			@mysql_query( $ipStatisticsInputSQL , get_db_conn() );

			$_SESSION['connIP'] = $key;
		}
	}
}
//connIP ( $_SERVER['REMOTE_ADDR'] );




// ����ȸ�� ��� ����
function isWholesale () {
	$ROW = mysql_fetch_assoc ( mysql_query( "SELECT `wholesalemember` FROM `tblshopinfo` LIMIT 1 ;" ) );
	return $ROW['wholesalemember'];
}



// ���̺� ���� !! ���� ������ �� ����
function truncateTables ( $tableName ) {
	$sql = "TRUNCATE TABLE `".$tableName."`";
	return mysql_query( $sql, get_db_conn() );
}





// SMS key
function smsCountValue () {
	$sql = "SELECT id, authkey, return_tel FROM tblsmsinfo ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)){
		$return_tel = explode("-",$row->return_tel);
		$sms_id=$row->id;
		$sms_authkey=$row->authkey;
	}
	mysql_free_result($result);

	$smscountdata=getSmscount($sms_id, $sms_authkey);

	$smscount = explode("|",$smscountdata);

	return $smscount[1];
}


// ������ �������� ���� ===============================================================================================================



	// ������ > ���� ������ ���/����, �̸����� ���� ���� Ȯ��
	function isAdminDesingCHK ( $tables, $type, $code='', $field='' ) {
		$sql = "SELECT * FROM ".$tables." WHERE ";
		$sql .= "type='".$type."' ";
		if( strlen($code) > 0 AND $code != "codeChange" ) $sql .= " AND code='".$code."' ";
		if( strlen($field) > 0 ) $sql .= " AND orgField='".$field."' ";
		$sql .= "LIMIT 1 ;";

		$result=mysql_query( $sql,get_db_conn());
		$row = mysql_fetch_assoc ($result);
		$return = (mysql_num_rows($result) > 0) ? true : false;
		mysql_free_result($result);

		return array( "row"=>$row, "check"=>$return );
	}




	// ������ > ���� ������ ��� / ����
	/* adminDesingBackup (
			$mode, => ��� / ���� ���� (store:restore)
			$type,  => ���� Ű tbldesignnewpage �� type
			$body,  => ���� tbldesignnewpage �� body
			$subject='',  => ���� tbldesignnewpage �� subject
			$code='',  => �߰���� tbldesignnewpage �� code
			$filename='',  => �߰���� tbldesignnewpage �� filename
			$leftmenu='',  => �޴���� tbldesignnewpage �� leftmenu
			$table = 'tbldesignnewpage', => �ٸ� ���̺� ������ �ҽ��ϰ�� ���̺�� ( �⺻:tbldesignnewpage )
			$field = '' => �ٸ� ���̺� ������ �ҽ��ϰ�� �ʵ��
		)
	*/
	function adminDesingBackup ( $mode, $type, $body, $subject='', $code='', $filename='', $leftmenu='', $table = 'tbldesignnewpage', $field = '' ) {

		$returnError = "ERROR";

		// ���
		if($mode=="store" && strlen(trim($body))>0){

			$returnMSG = $subject."��������";

			$returnError = "store";

			$temp = isAdminDesingCHK("tbldesignnewpage_temp",$type, $code, $field );
			if( $temp['check'] === true ) {

				$sql = "UPDATE tbldesignnewpage_temp SET ";
				if( strlen($subject) > 0 ) $sql.= "subject = '".$subject."', ";
				if( strlen($filename) > 0 ) $sql.= "filename = '".$filename."', ";
				if( strlen($leftmenu) > 0 ) $sql.= "leftmenu = '".$leftmenu."', ";
				$sql.= "body = '".$body."', ";
				$sql.= "orgTable = '".$table."' ";
				$sql.= "WHERE type = '".$type."' ";
				if( strlen($code) > 0 ) $sql.= " AND code = '".$code."' ";
				if( strlen($field) > 0 ) $sql.= " AND orgField = '".$field."' ";

				$returnError .= " update";
				$returnMSG = "";

			} else {

				$sql = "INSERT tbldesignnewpage_temp SET ";
				$sql.= "type = '".$type."', ";
				if( strlen($subject) > 0 ) $sql.= "subject = '".$subject."', ";
				if( strlen($code) > 0 ) $sql.= "code = '".$code."', ";
				if( strlen($filename) > 0 ) $sql.= "filename = '".$filename."', ";
				if( strlen($leftmenu) > 0 ) $sql.= "leftmenu = '".$leftmenu."', ";
				if( strlen($field) > 0 ) $sql.= "orgField = '".$field."', ";
				$sql.= "body = '".$body."', ";
				$sql.= "orgTable = '".$table."' ";

				$returnError .= " insert";

			}

			if ( $result = mysql_query($sql,get_db_conn()) ) {
				$returnError .= " OK";
				$returnMSG .= " ���";
			} else {
				$returnError .= " ERROR";
				$returnMSG .= " ��� ����";
			}
			@mysql_free_result($result);

			$returnMSG .= " �Ǿ����ϴ�.";
		}

		// ����
		if($mode=="restore"){

			$returnMSG = $subject."��������";

			$returnError = "restore";

			if( $type == "bottom") $code='codeChange'; // bottom �� code�� ���� ��

			$temp = isAdminDesingCHK("tbldesignnewpage_temp",$type, $code, $field );
			if( $temp['check'] === true ) {
				$row = $temp['row'];
				if( $row['orgTable'] == "tbldesignnewpage" ) {
					$org = isAdminDesingCHK ("tbldesignnewpage", $type, $row['code'] );
					if( $org['check'] === true ) {
						$sql = "UPDATE tbldesignnewpage SET ";
						if( strlen($row['subject']) > 0 ) $sql.= "subject = '".$row['subject']."', ";
						if( strlen($row['filename']) > 0 ) $sql.= "filename = '".$row['filename']."', ";
						if( strlen($row['leftmenu']) > 0 ) $sql.= "leftmenu = '".$row['leftmenu']."', ";
						if( strlen($row['code']) > 0 AND $code == "codeChange" ) $sql.= "code = '".$row['code']."', ";
						$sql.= "body = '".$row['body']."' ";
						$sql.= "WHERE type = '".$row['type']."' ";
						if( strlen($row['code']) > 0 AND $code != "codeChange" ) $sql.= " AND code = '".$row['code']."' ";

						$returnError .= " update";
					} else {
						$sql = "INSERT tbldesignnewpage SET ";
						if( strlen($row['type']) > 0 ) $sql.= " type = '".$row['type']."', ";
						if( strlen($row['code']) > 0 ) $sql.= " code = '".$row['code']."', ";
						if( strlen($row['subject']) > 0 ) $sql.= "subject = '".$row['subject']."', ";
						if( strlen($row['filename']) > 0 ) $sql.= "filename = '".$row['filename']."', ";
						if( strlen($row['leftmenu']) > 0 ) $sql.= "leftmenu = '".$row['leftmenu']."', ";
						$sql.= "body = '".$row['body']."' ";

						$returnError .= " insert";
					}
				} else {
					$sql = "UPDATE ".$row['orgTable']." SET ";
					$sql.= "`".$row['orgField']."` = '".$row['body']."' ";

					$returnError .= " ".$row['orgTable']." update";
				}
			} else {
				$returnMSG .= " ����� ������ ����";
			}

			if ( $result = mysql_query($sql,get_db_conn()) ) {
				$returnError .= " OK";
				$returnMSG .= " ����";
			} else {
				$returnError .= " ERROR";
				$returnMSG .= " ���� ����";
			}
			@mysql_free_result($result);

			$returnMSG .= " �Ǿ����ϴ�.";

		}

		//echo $sql;
		$returnMSG .= "(".$returnError.")";
		$returnMSG .= "\\n";
		return $returnMSG;

	}






	// �̸����� ����
	function adminPreview ( $type, $body, $subject='', $code='', $filename='', $leftmenu='' ) {

		$returnError = "ERROR";

		if( strlen(trim($body)) > 0 ) {

			$returnMSG = $subject."�̸�����";

			$returnError = "preview";

			// �̸����� ���̺� ����
			//truncateTables('tbldesignnewpage_prev');

			$temp = isAdminDesingCHK("tbldesignnewpage_prev",$type, $code );

			if( $temp['check'] === true ) {

				$sql = "UPDATE tbldesignnewpage_prev SET ";
				if( strlen($subject) > 0 ) $sql.= "subject = '".$subject."', ";
				if( strlen($filename) > 0 ) $sql.= "filename = '".$filename."', ";
				if( strlen($leftmenu) > 0 ) $sql.= "leftmenu = '".$leftmenu."', ";
				$sql.= "body = '".$body."' ";
				$sql.= "WHERE type = '".$type."' ";
				if( strlen($code) > 0 ) $sql.= " AND code = '".$code."' ";

				$returnError .= " update";
				$returnMSG = "";

			} else {

				$sql = "INSERT tbldesignnewpage_prev SET ";
				$sql.= "type = '".$type."', ";
				if( strlen($subject) > 0 ) $sql.= "subject = '".$subject."', ";
				if( strlen($code) > 0 ) $sql.= "code = '".$code."', ";
				if( strlen($filename) > 0 ) $sql.= "filename = '".$filename."', ";
				if( strlen($leftmenu) > 0 ) $sql.= "leftmenu = '".$leftmenu."', ";
				$sql.= "body = '".$body."' ";

				$returnError .= " insert";

			}

			if ( $result = mysql_query($sql,get_db_conn()) ) {
				$returnError .= " OK";
				$returnMSG .= " ���� ����";
			} else {
				$returnError .= " ERROR";
				$returnMSG .= " ���� ����";
			}
			@mysql_free_result($result);

		}

		//echo $sql;
		$returnMSG .= "(".$returnError.")";
		$returnMSG .= "\\n";
		return $returnMSG;

	}

	function _getDeviceInfo(){
		$_devicelist = '/(iPod|iPhone|Android|BlackBerry|SymbianOS|SCH-M\d+|Opera Mini|Windows CE|Nokia|SonyEricsson|webOS|PalmOS)/';
		if ( $_GET['pc'] == "ON" ){
			$_deviceinfo = "P";
		}else{
			if(preg_match($_devicelist, $_SERVER['HTTP_USER_AGENT'])) {
				$_deviceinfo = "M";
			} else {
				$_deviceinfo = "P";
			}
		}
		return $_deviceinfo;
	}



// ������ �������� �� ================================================================================================================
/* jyh �Լ� ���� ���� */
function prt($data=null, $size=0) {
	//if (!isdev()) return;
	ob_start();
	print_r($data);
	$content_text = ob_get_contents();
	ob_end_clean();
	$content_list = explode("\n", $content_text);
	$rows = count($content_list);
	$cols = 0;
	foreach ($content_list as $line) {
		if (($col = mb_strlen($line)) > $cols) {
			$cols = $col;
			if ($cols > 128) $rows++;
		}
	}
	$rows += 2;
	$cols += 2;
	if (!headers_sent()) {
		header('Content-type: text/html; charset=euc-kr');
	}
	echo '<textarea cols="' . $cols . '" rows="' . $rows . '"';
	echo ' style="display:block; margin:0; padding:5px; border:1px solid #999;';
	if ($size) echo ' width:' . $size . 'px;';
	echo ' font-family:Hwsr_9pt, Gulimche; font-size:9pt;">';
	echo $content_text;
	echo '</textarea>';
}


//�����ǹ��� �Լ�
function getInjection($str) {
	if(!$str) return false;
	return addslashes(stripslashes($str));
}

//�����ǹ��� �Լ�2
function getInjection2($str) {
	if(!$str) return false;
	//$str = stripslashes($str);
	//$str = mysql_real_escape_string($str);

	$str = htmlspecialchars(stripslashes($str));
	$str = str_ireplace("script", "blocked", $str);
	$str = mysql_escape_string($str);


	return $str;
}

//�����ΰ��� ���� php�ҽ��� ����
function getReplacePhp($str) {
	if(!$str) return false;

	$str = str_replace("<?","&lt?",$str);
	$str = str_replace("?>","?&gt",$str);
	return $str;
}

//���ϻ����Լ�
function LIB_removeAllData( $URL ) {
	if(is_dir($URL)) {
		if( $dh = @opendir($URL)) {
			while( ( $file = @readdir( $dh ) ) !== false ) {
				if( $file == '.' || $file == ".." )	continue;

				if( @filetype( $URL.$file ) == "dir" )	LIB_removeAllData( $URL.$file.'/' );
				else	@unlink( $URL.$file );                    // ���� ����
			}
			//@rmdir( $URL );        // ���� ����
			closedir( $dh );
		}
	}
}

//�ֻ�����������ϰ� �۹̼�
function LIB_removeAfter_Dir($URL) {
	if(is_dir($URL)) {
		LIB_removeAllData($URL);
		@mkdir($URL);
		@chmod($URL,0707);
	}
}

function isdev() {
		//return isget($_SERVER, 'SERVER_ADDR') == '127.0.0.1';
		return true;
}

//�������� Ÿ��Ʋ �̹��� ��Ʈ��
function page_top_title() {
	global $_data, $config;
	$args = func_get_args();

	if (is_array($args) && empty($args)) return false;
	//prt($args);

	$Dir		= $args[0];
	$leftmenu	= $args[1];	//Ÿ��Ʋ �̹��� �������� ����
	$page_type  = $args[2];	//������ ���а�

	//$date_img	= $Dir.DataDir."design/".$config['page_top_title'][$page_type]['data'];	//����ڰ� ����� Ÿ��Ʋ �̹���
	//$alt		= $config['page_top_title'][$page_type]['alt'];							//alt
	//$def_img	= $Dir."images/".$_data->icon_use_type."/".$config['page_top_title'][$page_type]['def'];	//default Ÿ��Ʋ �̹���

	$date_img	= $Dir.DataDir."design/".$page_type."_title.gif";	//����ڰ� ����� Ÿ��Ʋ �̹���
	$alt		= $config['page_top_title'][$page_type]['alt'];							//alt
	$def_img	= $Dir.DataDir."design/skin/".$_data->icon_use_type."/etc/".$page_type."_title_head.gif";	//default Ÿ��Ʋ �̹���


	if ($leftmenu!="N") {
	//if ($leftmenu=="N") {
		if ($_data->title_type=="Y" && file_exists($date_img)) {
			$text  = "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
			$text .= "<tr>\n";
			//$text .= "<td><img src=\"".$date_img."\" border=\"0\" alt=\"".$alt."\"></td>\n";
			$text .= "<td><img src=\"".$date_img."\" border=\"0\" ></td>\n";
			$text .= "</tr>\n";
			$text .= "</table>\n";
		} elseif(file_exists($def_img)) {	//������ �����Ұ�쿡�� ��Ÿ���� ���� by.jyh
			$text  = "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
			$text .= "<tr>\n";
			$text .= "<td><img src=\"".$def_img."\" border=\"0\" alt=\"".$alt."\"></td>\n";
			$text .= "</tr>\n";
			$text .= "</table>\n";
		}
	}
	return $text;
}

/* jyh �Լ� ���� �� */



function checkNaverEp(){
	//$engine_num = "naver"; //���������� ��ȣ
	$filename = DirPath.DataDir."shopimages/etc/naver.db";
	$checkValue = false;
	if(file_exists($filename)==true){
		if($fp=@fopen($filename, "r")){
			$szdata=fread($fp, filesize($filename));
			fclose($fp);
			$checkValue=unserialize($szdata);
		}
	}
	return $checkValue;
}

function _basketCount($table="tblbasket_normal",$tempkey=null){
	$tablename = trim($table);
	$basketkey = trim($tempkey);
	$rowcount= 0;

	if(strlen($basketkey)>0){
		$dsql = "delete r.* from rent_basket_temp r left join ".$tablename." as b on (b.basketidx=r.basketidx and b.ordertype = r.ordertype)  left join tblproduct p on p.productcode = b.productcode where p.productcode is null and b.tempkey = '".$basketkey."' and memid=''";
		@mysql_query($dsql,get_db_conn());
		$dsql = "delete b.* from ".$tablename." as b left join tblproduct p on p.productcode = b.productcode where p.productcode is null and b.tempkey = '".$basketkey."' and memid=''";	
		@mysql_query($dsql,get_db_conn());
		$countSQL = "SELECT COUNT(tempkey) AS rowcount FROM ".$tablename." WHERE tempkey = '".$basketkey."' and memid=''";
//		echo $countSQL;
		$countRes = @mysql_query($countSQL,get_db_conn());
		$rowcount = @mysql_result($countRes,0,0);
		mysql_free_result($countRes);
	}
	return $rowcount;
}


function _basketCount2($table="tblbasket_normal",$memid){
	$tablename = trim($table);
	$memid = trim($memid);
	$rowcount= 0;

	if(strlen($memid)>0){
		$countSQL = "SELECT COUNT(memid) AS rowcount FROM ".$tablename." a inner join tblproduct b on a.productcode=b.productcode WHERE a.memid = '".$memid."' ";
		$countRes = @mysql_query($countSQL,get_db_conn());
		$rowcount = @mysql_result($countRes,0,0);
		mysql_free_result($countRes);
	}
	return $rowcount;
}



/***********************
/* ������ ��� ����
/*
************************/
function nameTechUse( $vender ){

	$use = false;

	$shopRow = mysql_fetch_assoc (mysql_query("SELECT `nametech_use` FROM `shop_more_info` ",get_db_conn()));
	if( $shopRow[nametech_use] == 1 ) {
		$use = true;
		if( strlen( $vender ) > 0 ) {
			$venderRow = mysql_fetch_assoc (mysql_query("SELECT `com_nametech` FROM  `tblvenderinfo` WHERE `vender` =".$vender,get_db_conn()));
			if( $venderRow[com_nametech] == 1 ) {
				$use = true;
			} else{
				$use = false;
			}
		} else{
			$use = false;
		}
	}

	return $use;
}








// �����ǰ ������ �߰�
// �����ǰ�� ��� ��ǰ�� ������ ����Ʈ �� �տ� �����ǰ �������� �߰� ��
function reservationEtcType($reservation,$etctype){
	if( strlen($reservation) > 0 AND $reservation != '0000-00-00' AND strtotime($reservation) > strtotime(date("Ymd")) ) {
		if( eregi("ICON=", $etctype) ) {
			if( !eregi("ICON=29", $etctype) ) {
				$etctype = str_replace("ICON=","ICON=29",$etctype);
			}
		} else{
			$etctype = "ICON=29".$etctype;
		}
	}
	return $etctype;
}




// ��������Ʈ
	function venderList ( $fields = "*" ) {
		$SQL = "SELECT ".$fields." FROM `tblvenderinfo` ORDER BY vender ASC";
		$RES = mysql_query($SQL,get_db_conn());
		$return = array();
		while ( $ROW =  mysql_fetch_assoc($RES) ) { $return[$ROW['vender']] = $ROW; }
		return $return;
	}


function calcSetBankinfo(){
	$bankinfoArray = array(
		"001"=>"�ѱ�����",
		"002"=>"�������",
		"003"=>"�������",
		"004"=>"��������",
		"005"=>"��ȯ����",
		"007"=>"�����߾�ȸ",
		"008"=>"����������",
		"011"=>"�����߾�ȸ",
		"012"=>"����ȸ������",
		"020"=>"�츮����",
		"023"=>"SC��������",
		"027"=>"�ѱ���Ƽ����",
		"031"=>"�뱸����",
		"032"=>"�λ�����",
		"034"=>"��������",
		"035"=>"��������",
		"037"=>"��������",
		"039"=>"�泲����",
		"045"=>"�������ݰ���ȸ",
		"048"=>"�����߾�ȸ",
		"050"=>"��ȣ��������",
		"052"=>"��ǽ��ĸ�����",
		"054"=>"HSBC����",
		"055"=>"����ġ����",
		"056"=>"���̺񿣾Ϸ�����",
		"057"=>"�����Ǹ�ü�̽�����",
		"058"=>"����ȣ���۷���Ʈ����",
		"059"=>"�̾���õ���UFJ����",
		"060"=>"BOA",
		"071"=>"������ź� ��ü��",
		"076"=>"�ſ뺸�����",
		"077"=>"����ſ뺸�����",
		"081"=>"�ϳ�����",
		"088"=>"��������",
		"093"=>"�ѱ����ñ�������",
		"094"=>"���ﺸ������",
		"095"=>"����û",
		"099"=>"����������",
		"209"=>"�������ձ�������",
		"218"=>"��������",
		"230"=>"�̷���������",
		"238"=>"�������",
		"240"=>"�Ｚ����",
		"243"=>"�ѱ���������",
		"247"=>"�츮��������",
		"261"=>"��������",
		"262"=>"������������",
		"263"=>"����ġ������������",
		"264"=>"Ű������",
		"265"=>"��Ʈ���̵�����",
		"266"=>"������������",
		"267"=>"�������",
		"268"=>"�ַθ���������",
		"269"=>"��ȭ����",
		"270"=>"�ϳ���������",
		"278"=>"�¸�׽�������",
		"279"=>"��������",
		"280"=>"������������",
		"287"=>"�޸�������",
		"289"=>"������ġ��������",
		"290"=>"�α�����"
	);
	return $bankinfoArray;
}

?>
