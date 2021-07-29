<?
if(substr(getenv("SCRIPT_NAME"),-8)=="/lib.php") {
	header("HTTP/1.0 404 Not Found");
	exit;
}

include_once($Dir."lib/mail.php");

define("_IncomuShopVersionNo", "1.6.0");				#독립형 쇼핑몰 Version
define("_IncomuShopVersionDate", "2015/01");			#Version 업데이트 날짜
define("_IncomuUrl", "i.odv.kr");						#솔루션 배포 회사 도메인
if(strlen(getSellerdomain("DOMAIN"))>0) {
	define("_SellerUrl", getSellerdomain("DOMAIN"));	#리셀러 배포 회사 도메인
} else {
	define("_SellerUrl", _IncomuUrl);					#리셀러 배포 회사 도메인
}
define("_InfoDomain", "i.odv.kr");						#회사 도메인
define("_DEMOSHOP", "");								#데모 쇼핑몰에서만 사용

############################## 암호화 파일 체크 ##################################
function setFileZendCheck() {
	global $Dir;
	if($f=@file($Dir.AuthkeyDir.".shopaccess")) {
		$authkey=trim($f[0]);

		$ZendError=true;
		if(strlen($authkey)==64) {
			$fp=@fopen ($Dir.str_replace(RootPath,"",substr(getenv("SCRIPT_NAME"),1)),"r");
			if($fp) {
				$temp=@fgets($fp,5);

				if($temp=="Zend") { //$temp => Zend일 경우 암호화
					$ZendError=false;
				}
			}
			@fclose($fp);
		} else {
			$ZendError=false;
		}
		if($ZendError==true) {
			error_msg("쇼핑몰 파일타입이 올바르지 않습니다.","-1");
		}
	} else {
		error_msg("도메인 인증파일이 존재하지 않습니다.","-1");
	}
} setFileZendCheck();

############################## 사용 가능 도메인 체크 ##################################
function getUseShopDomain() {
	global $Dir;
	$retval="";	//X:파일이 존재하지 않음, O:인증성공, A:잘못된 인증키, D:도메인 불일치, E:사용기간 만료, F:허용IP가 아님, R:통신불가
	if($f=@file($Dir.AuthkeyDir.".shopaccess")) {
		$authkey=trim($f[0]);
		if(strlen($authkey)>0) {
			$host=_IncomuUrl;
			//$path="/incomushop/getuseshopdomain.html"; //회사명이 들어가 디렉토리명 변경
			$path="/getmallshop/getuseshopdomain.html";
			$query="&authkey=".$authkey."&domain=".(strlen(getenv("HTTP_HOST"))>0?getenv("HTTP_HOST"):getUriDomain(getenv("REQUEST_URI")))."&serverip=".getenv("SERVER_ADDR");

			$resdata=SendSocketPost($host,$path,$query);
			if(substr($resdata,0,4)=="[OK]") {
				$retval=substr($resdata,4,1);
			} else {
				$retval="R";
			}
		} else {
			$retval="A";
		}
	} else {
		$retval="X";
	}

	if(!preg_match("/^(X|O|A|D|E|F|R)$/",$retval)) {
		$retval="R";
	}

	return $retval;
}

############################## 주문시도건 복구(쿠폰,적립금,재고량) 처리 ##################################
function temporder_restore() {
	GLOBAL $Dir;
	if(file_exists($Dir.DataDir."retemp")) {	//data 폴더의 쓰레기 파일들 일정시간 지나면 삭제
		$filecreatetime=(time()-filemtime($Dir.DataDir."retemp"))/60;
		if($filecreatetime>10) {
			$sdate=date("YmdHi",mktime(date("H")-1,date("i")-5,0,date("m"),date("d"),date("Y")));
			$edate=date("YmdHi",mktime(date("H"),date("i"),0,date("m"),date("d")-7,date("Y")));

			$randkey="PROC".rand(1000000, 9999999);
			$sql = "UPDATE tblorderinfotemp SET ";
			$sql.= "pay_data	= CONCAT('".$randkey."||',pay_data) ";
			$sql.= "WHERE (ordercode>='".$edate."' AND ordercode<='".$sdate."') ";
			$sql.= "AND (del_gbn='' OR del_gbn is NULL) AND pay_data NOT LIKE 'PROC%' ";
			mysql_query($sql,get_db_conn());

			$sql = "SELECT * FROM tblorderinfotemp WHERE (ordercode>='".$edate."' AND ordercode<='".$sdate."') ";
			$sql.= "AND (del_gbn='' OR del_gbn is NULL) AND pay_data LIKE '".$randkey."%' ";
			$result=@mysql_query($sql,get_db_conn());
			while($data=@mysql_fetch_object($result)) {
				$ordercode=$data->ordercode;

				@mysql_query("UPDATE tblorderinfotemp SET del_gbn='R', pay_data=REPLACE(pay_data,'".$randkey."||','') WHERE ordercode='".$ordercode."'",get_db_conn());

				$sql = "SELECT a.productcode,a.productname,a.opt1_name,a.opt2_name,a.quantity,a.package_idx,a.assemble_idx,a.assemble_info, ";
				$sql.= "b.option_quantity,b.option1,b.option2 FROM tblorderproducttemp a, tblproduct b ";
				$sql.= "WHERE a.productcode=b.productcode AND a.ordercode='".$ordercode."' ";
				$result2=@mysql_query($sql,get_db_conn());
				while($row=@mysql_fetch_object($result2)) {
					$tmpoptq="";
					if(strlen($artmpoptq[$row->productcode])>0)
						$optq=$artmpoptq[$row->productcode];
					else
						$optq=$row->option_quantity;

					if(strlen($optq)>51 && substr($row->opt1_name,0,5)!="[OPTG") {
						$tmpoptname1=explode(" : ",$row->opt1_name);
						$tmpoptname2=explode(" : ",$row->opt2_name);
						$tmpoption1=explode(",",$row->option1);
						$tmpoption2=explode(",",$row->option2);
						$cnt=1;
						$maxoptq = count($tmpoption1);
						while($tmpoption1[$cnt]!=$tmpoptname1[1] && $cnt<$maxoptq) {
							$cnt++;
						}
						$opt_no1=$cnt;
						$cnt=1;
						$maxoptq2 = count($tmpoption2);
						while($tmpoption2[$cnt]!=$tmpoptname2[1] && $cnt<$maxoptq2) {
							$cnt++;
						}
						$opt_no2=$cnt;
						$optioncnt = explode(",",substr($optq,1));
						if($optioncnt[($opt_no2-1)*10+($opt_no1-1)]!="") $optioncnt[($opt_no2-1)*10+($opt_no1-1)]+=$row->quantity;
						for($j=0;$j<5;$j++) {
							for($i=0;$i<10;$i++) {
								$tmpoptq.=",".$optioncnt[$j*10+$i];
							}
						}
						if(strlen($tmpoptq)>0 && $tmpoptq.","!=$optq) {
							$artmpoptq[$row->productcode]=$tmpoptq;
							$tmpoptq=",option_quantity='".$tmpoptq.",'";
						} else {
							$tmpoptq="";
						}
					}
					$sql = "UPDATE tblproduct SET quantity=quantity+".$row->quantity.$tmpoptq." ";
					$sql.= "WHERE productcode='".$row->productcode."'";
					@mysql_query($sql,get_db_conn());

					if(str_replace("","",str_replace(":","",str_replace("=","",$row->assemble_info)))) {
						$assemble_infoall_exp = explode("=",$row->assemble_info);

						if($row->package_idx>0 && strlen(str_replace("","",str_replace(":","",$assemble_infoall_exp[0])))>0) {
							$package_info_exp = explode(":",$assemble_infoall_exp[0]);
							if(strlen($package_info_exp[0])>0) {
								$package_productcode_exp = explode("",$package_info_exp[0]);
								for($k=0; $k<count($package_productcode_exp); $k++) {
									$sql2 = "UPDATE tblproduct SET ";
									$sql2.= "quantity		= quantity+".$row->quantity." ";
									$sql2.= "WHERE productcode='".$package_productcode_exp[$k]."' ";
									mysql_query($sql2,get_db_conn());
								}
							}
						}

						if($row->assemble_idx>0 && strlen(str_replace("","",str_replace(":","",$assemble_infoall_exp[1])))>0) {
							$assemble_info_exp = explode(":",$assemble_infoall_exp[1]);
							if(strlen($assemble_info_exp[0])>0) {
								$assemble_productcode_exp = explode("",$assemble_info_exp[0]);
								for($k=0; $k<count($assemble_productcode_exp); $k++) {
									$sql2 = "UPDATE tblproduct SET ";
									$sql2.= "quantity		= quantity+".$row->quantity." ";
									$sql2.= "WHERE productcode='".$assemble_productcode_exp[$k]."' ";
									mysql_query($sql2,get_db_conn());
								}
							}
						}
					}
				}
				@mysql_free_result($result2);

				$sql = "SELECT productcode FROM tblorderproducttemp WHERE ordercode='".$ordercode."' AND productcode LIKE 'COU%' ";
				$result3=@mysql_query($sql,get_db_conn());
				$rowcou=@mysql_fetch_object($result3);
				@mysql_free_result($result3);
				if($rowcou) {
					$coupon_code=substr($rowcou->productcode,3,-1);
					@mysql_query("UPDATE tblcouponissue SET used='N' WHERE id='".$data->id."' AND coupon_code='".$coupon_code."'",get_db_conn());
				}
				if($data->reserve>0) {
					@mysql_query("UPDATE tblmember SET reserve=reserve+".$data->reserve." WHERE id='".$data->id."'",get_db_conn());
					$reserve_restore_sql = "INSERT tblreserve SET id = '".$data->id."', reserve = '".abs($data->reserve)."' , reserve_yn = 'Y', content='자동 복구프로세스에 의한 적립금 복구', orderdata = '".$ordercode."=".$data->price."', date='".date('YmdHis')."' ";
					@mysql_query($reserve_restore_sql,get_db_conn());
				}
			}
			@mysql_free_result($result);
			@unlink($Dir.DataDir."retemp");
		}
	} else {
		if($fp=fopen($Dir.DataDir."retemp", "w")) {
			fputs($fp, "OK");
			fclose($fp);
		}
	}
} 
if($install_state !==true){
	temporder_restore();
}

############################## 도메인만 가져오기 ##################################
function getUriDomain($url) {
	if(strlen($url)>0) {
		$temp = ereg_replace("http://", "", $url);
		$result = @explode("/", $temp);
		return $result[0];
	}
}

function getCookieDomain() {
	$domain_explode=explode(".",getenv("HTTP_HOST"));
	if($domain_explode[0]=="www")
	{
		@array_shift($domain_explode);
		return ".".@implode(".",$domain_explode);
	}
	else
		return getenv("HTTP_HOST");
}

###########################################################
function decrypt_authkey($str) {
	return decrypt_md5($str,"*ghkddnjsrl*");
}

function getAdminMainNotice() {
 $path="/_getmallAdminNotice.php";
 $query="";

 $resdata=SendSocketPost(_IncomuUrl,$path,$query);
 return $resdata;
}

function getSmshost(&$path) {
 $host = "sms.getmall.kr";
 $path = "";
 return $host;
}
###########################################################

function decrypt_md5($hex_buf,$key="") {
	if(strlen($key)==0) $key=enckey;
	$len = strlen($hex_buf);
	for ($i=0; $i<$len; $i+=2)
		$buf .= chr(hexdec(substr($hex_buf, $i, 2)));
		$key1 = pack("H*", md5($key));
		while($buf) {
			$m = substr($buf, 0, 16);
			$buf = substr($buf, 16);

			$c = "";
			for($i=0;$i<16;$i++) {
				$c .= $m{$i}^$key1{$i};
			}
			$ret_buf .= $m = $c;
			$key1 = pack("H*",md5($key.$key1.$m));
		}
		return($ret_buf);
}

function encrypt_md5($buf,$key="") {
	if(strlen($key)==0) $key=enckey;
	$key1 = pack("H*",md5($key));
	while($buf) {
		$m = substr($buf, 0, 16);
		$buf = substr($buf, 16);

		$c = "";
		for($i=0;$i<16;$i++) {
			$c .= $m{$i}^$key1{$i};
		}
		$ret_buf .= $c;
		$key1 = pack("H*",md5($key.$key1.$m));
	}
	$len = strlen($ret_buf);
	for($i=0; $i<$len; $i++)
		$hex_data .= sprintf("%02x", ord(substr($ret_buf, $i, 1)));
	return($hex_data);
}

function get_db_conn() {
	global $DB_CONN, $Dir;
	if (!$DB_CONN) {
		$f=@file($Dir.DataDir."config.php") or error_msg("config.php파일이 없습니다.<br>DB설정을 먼저 하십시요",$Dir."install.php");
		for($i=1;$i<=4;$i++) $f[$i]=trim(str_replace("\n","",$f[$i]));

		$DB_CONN = @mysql_connect($f[1],$f[2],$f[3]) or error_msg("DB 접속 에러가 발생하였습니다.");
		$status = @mysql_select_db($f[4],$DB_CONN) or error_msg("DB Select 에러가 발생하였습니다.");

		if (!$status) {
		   error_msg("DB Select 에러가 발생하였습니다.");
		}
		mysql_query("set names euckr");
	}	
	return $DB_CONN;

}

########### SQL CACHE ##################
function WriteCache(&$var, $file) {
	$filename = DirPath.DataDir."cache/sql/".$file;
	$success = false;

	if($fp=fopen($filename, "w")) {
		fputs($fp, serialize($var));
		fclose($fp);
		$success=true;
	}
	return $success;
}

function ReadCache(&$var, $file, $delmin=240) {
	$filename = DirPath.DataDir."cache/sql/".$file;
	$success = false;

	if(file_exists($filename)==true) {
		$filecreatetime=(time()-filemtime($filename))/60;
		if($filecreatetime<=$delmin) {
			if($fp=@fopen($filename, "r")) {
				$szdata=fread($fp, filesize($filename));
				fclose($fp);
				$var=unserialize($szdata);
				$success=true;
			}
		} else {
			DeleteCache($file);
		}
	}
	return $success;
}

function DeleteCache($file) {
	$filename = DirPath.DataDir."cache/sql/".$file;
	if(file_exists($filename)==true) {
		@unlink($filename);
	}
}

function get_db_cache($SQL, &$var, $file, $delmin=240, $refresh=false) {
	global $DB_CONN;
	$var=array();
	$ret=true;

	if($refresh || !ReadCache($var, $file, $delmin)) {
		if (!$DB_CONN) $DB_CONN = get_db_conn();
		$res=mysql_query($SQL, $DB_CONN);
		if($err=mysql_error())
			trigger_error($err, E_USER_ERROR);
		while($rec=mysql_fetch_object($res)){
			$var[]=$rec;
		}
		mysql_free_result($res);
		$ret = WriteCache($var, $file);
	}
	return $ret;
}

function delete_cache_file($type, $str="") {
	if($type=="main") {
		if(is_dir(getenv("DOCUMENT_ROOT")."/".RootPath.DataDir."cache/main")==true) {
			$match=getenv("DOCUMENT_ROOT")."/".RootPath.DataDir."cache/main/*_main.php_";
		}
	} else if($type=="product") {
		if(is_dir(getenv("DOCUMENT_ROOT")."/".RootPath.DataDir."cache/product")==true) {
			$match=getenv("DOCUMENT_ROOT")."/".RootPath.DataDir."cache/product/*_product*";
			if(strlen($str)>0) $match.=$str."*";
		}
	} else if($type=="productb") {
		if(is_dir(getenv("DOCUMENT_ROOT")."/".RootPath.DataDir."cache/product")==true) {
			$match=getenv("DOCUMENT_ROOT")."/".RootPath.DataDir."cache/product/*_productb*";
			if(strlen($str)>0) $match.=$str."*";
		}
	}
	if(strlen($match)>0) {
		$match=str_replace("..","",$match);
		$match=str_replace(" ","",$match);
		$matches=glob($match);
		if(is_array($matches)) {
			foreach($matches as $cachefile) {
				@unlink($cachefile);
			}
		}
	}
}

function getSellerdomain() {
	$resdata="";
	if($f=@file(DirPath.AuthkeyDir.".seller")) {
		$sellerid=trim($f[0]);
		if(strlen($sellerid)>0) {
			$resdata_decrypt=decrypt_authkey($sellerid);
			$resdata = @explode("|", $resdata_decrypt);
		}
	}

	return $resdata[1];
}


class usersession {
	var $id					= "";
	var $authkey			= "";

	var $shopdata			= "";

	var $allowanyip			= false;
	var $ipaddresses		= Array();
	var $roleidx			= 0;
	var $allowalltasks		= false;
	var $taskcodes			= Array();

	function usersession($id, $authkey) {
		$sql = "SELECT * FROM tblsecurityadmin WHERE id = '".$id."' ";
		if(_DEMOSHOP!="OK") {
			$sql.= "AND authkey='".$authkey."' ";
		}
		$result = mysql_query($sql,get_db_conn());
		$row=mysql_fetch_object($result);
		mysql_free_result($result);
		if ($row) {
			$this->id = $id;
			$this->authkey = $authkey;

			unset($resval);
			$sql = "SELECT * FROM tblshopinfo ";
			$result=mysql_query($sql,get_db_conn());
			if(!$row2=mysql_fetch_object($result)) {
				error_msg("쇼핑몰 정보 등록이 안되었습니다.<br>쇼핑몰 설정을 먼저 하십시요",DirPath."install.php");
			}

			$this->shopdata = $row2;

			$this->shopdata->escrow_id="";
			$this->shopdata->trans_id="";
			$this->shopdata->virtual_id="";
			$this->shopdata->card_id="";
			$this->shopdata->mobile_id="";
			if($f=@file(DirPath.AuthkeyDir."pg")) {
				for($i=0;$i<count($f);$i++) {
					$f[$i]=trim(str_replace("\n","",$f[$i]));
					if (substr($f[$i],0,12)=="escrow_id:::") $this->shopdata->escrow_id=decrypt_authkey(substr($f[$i],12));
					else if (substr($f[$i],0,11)=="trans_id:::") $this->shopdata->trans_id=decrypt_authkey(substr($f[$i],11));
					else if (substr($f[$i],0,13)=="virtual_id:::") $this->shopdata->virtual_id=decrypt_authkey(substr($f[$i],13));
					else if (substr($f[$i],0,10)=="card_id:::") $this->shopdata->card_id=decrypt_authkey(substr($f[$i],10));
					else if (substr($f[$i],0,12)=="mobile_id:::") $this->shopdata->mobile_id=decrypt_authkey(substr($f[$i],12));
				}
			}


			unset($ETCTYPE);
			if (strlen($row2->etctype)>0) {
				$etctemp = explode("",$row2->etctype);
				$etccnt = count($etctemp);
				for ($etci=0;$etci<$etccnt;$etci++) {
					$etctemp2 = explode("=",$etctemp[$etci]);
					$ETCTYPE[$etctemp2[0]]=$etctemp2[1];
				}
			}
			$this->shopdata->ETCTYPE=$ETCTYPE;


			//접근 가능한 IP인지 확인
			$sql = "SELECT ipidx FROM tblsecurityadminip WHERE id='".$this->id."' ORDER BY ipidx ASC LIMIT 1";
			$result = mysql_query($sql,get_db_conn());
			if ($row = mysql_fetch_object($result)) {
				if ($row->ipidx == 0)
					$this->allowanyip = true;
			}
			mysql_free_result($result);

			if (!$this->allowanyip) {
				$sql = "SELECT b.ipaddress as ipaddress1 FROM tblsecurityadminip a,tblsecurityiplist b ";
				$sql.= "WHERE a.id = '".$this->id."' AND a.ipidx = b.idx AND b.disabled = 0";
				$result = mysql_query($sql,get_db_conn());
				$j = 1;
				while($row = mysql_fetch_object($result)) {
					$this->ipaddresses[$j] = $row->ipaddress1;
					$j++;
				}
				mysql_free_result($result);
			}

			$sql = "SELECT a.idx as roleidx FROM tblsecurityrole a, tblsecurityadminrole b ";
			$sql.= "WHERE a.idx = b.roleidx AND a.disabled = 0 ";
			$sql.= "AND b.id = '".$this->id."' ";
			$result = mysql_query($sql,get_db_conn());
			$row = mysql_fetch_object($result);
			if ($row->roleidx)
				$this->roleidx = (int)$row->roleidx;

			mysql_free_result($result);

			if ($this->roleidx > 0) {
				$sql = "SELECT taskidx FROM tblsecurityroletask ";
				$sql.= "WHERE roleidx = ".$this->roleidx." ORDER BY taskidx ASC LIMIT 1";
				$result = mysql_query($sql,get_db_conn());
				if ($row = mysql_fetch_object($result)) {
					if ($row->taskidx == 0)
						$this->allowalltasks = true;
				}
				mysql_free_result($result);
			}

			if (!$this->allowalltasks && $this->roleidx > 0) {
				$sql = "SELECT b.taskcode, b.taskgroupidx, c.taskgroupcode, c.taskgroupname ";
				$sql.= "FROM tblsecurityroletask a, tblsecuritytask b, tblsecuritytaskgroup c ";
				$sql.= "WHERE a.roleidx = ".$this->roleidx." AND a.taskidx = b.idx ";
				$sql.= "AND b.taskgroupidx = c.idx ";
				$sql.= "ORDER BY b.taskgroupidx, b.taskcode ASC ";
				$result = mysql_query($sql,get_db_conn());
				while($row = mysql_fetch_object($result)) {
					$this->taskcodes[$row->taskcode] = true;
				}
				mysql_free_result($result);
			}
		} else {
			echo "<script>\n";
			echo "	alert(\"정상적인 경로로 다시 접속하시기 바랍니다.\");\n";
			echo "	if (opener) {\n";
			echo "		opener.parent.location.href=\"logout.php\";\n";
			echo "		window.close();\n";
			echo "	} else {\n";
			echo "		parent.location.href=\"logout.php\";\n";
			echo "	}\n";
			echo "</script>\n";
			exit;
		}
	}

	function isallowedip($ip) {
		if ($this->allowanyip)
			return true;
		else
			return (boolean)array_search($ip, $this->ipaddresses);
	}

	function isallowedtask($taskcode) {
		if ($this->allowalltasks)
			return true;
		if ($this->allowalltasks) {
			$taskcodess = substr($taskcode,0,2);
			return true;
		} else {
			return (boolean)$this->taskcodes[$taskcode];
		}
	}

	function getallowalltasks() {
		return (boolean)$this->allowalltasks;
	}

	function getshopdata() {
		return (object)$this->shopdata;
	}
}

class _ShopInfo {
	var $id				= "";
	var $authkey		= "";

	var $counterid		= "";
	var $counterauthkey	= "";

	var $shopurl		= "";
	var $refurl			= "";
	var $authidkey		= "";
	var $memid			= "";
	var $memgroup		= "";
	var $memname		= "";
	var $mememail		= "";
	var $memreserve		= 0;
	var $boardadmin		= "";	//array serialize data

	var $tempkey		= "";	//장바구니 인증키
	var $gifttempkey	= "";	//사은품 관련 키
	var $oldtempkey		= "";	//결제창 띄울경우 기존 장바구니 인증키
	var $okpayment		= "";	//결제시 새로고침 방지 쿠키

	var $token			= "";

	var $searchkey		= "";	//검색인증 구분키

	function _ShopInfo($_sinfo) {
		if ($_sinfo) {
			$savedata=unserialize(decrypt_md5($_sinfo));

			$this->id			= $savedata["id"];
			$this->authkey		= $savedata["authkey"];

			$this->shopurl		= $savedata["shopurl"];
			$this->refurl		= $savedata["refurl"];
			$this->authidkey	= $savedata["authidkey"];
			$this->memid		= $savedata["memid"];
			$this->memgroup		= $savedata["memgroup"];
			$this->memname		= $savedata["memname"];
			$this->memreserve	= $savedata["memreserve"];
			$this->mememail		= $savedata["mememail"];
			$this->boardadmin	= $savedata["boardadmin"];
			$this->gifttempkey	= $savedata["gifttempkey"];
			$this->oldtempkey	= $savedata["oldtempkey"];
			$this->okpayment	= $savedata["okpayment"];

			$this->token		= $savedata["token"];
		}
	}

	function Save() {
		$savedata["id"]			= $this->getId();
		$savedata["authkey"]	= $this->getAuthkey();
		$savedata["shopurl"]	= $this->getShopurl();
		$savedata["refurl"]		= $this->getRefurl();
		$savedata["authidkey"]	= $this->getAuthidkey();
		$savedata["memid"]		= $this->getMemid();
		$savedata["memgroup"]	= $this->getMemgroup();
		$savedata["memname"]	= $this->getMemname();
		$savedata["memreserve"]	= $this->getMemreserve();
		$savedata["mememail"]	= $this->getMememail();
		$savedata["boardadmin"]	= $this->getBoardadmin();
		$savedata["gifttempkey"]= $this->getGifttempkey();
		$savedata["oldtempkey"]	= $this->getOldtempkey();
		$savedata["okpayment"]	= $this->getOkpayment();

		$savedata["token"]		= $this->getToken();

		$_sinfo = encrypt_md5(serialize($savedata));
		setcookie("_sinfo", $_sinfo, 0, "/".RootPath, getCookieDomain());
	}

	function SetMemNULL() {
		$this->setAuthidkey("");
		$this->setMemid("");
		$this->setMemgroup("");
		$this->setMemname("");
		$this->setMemreserve("");
		$this->setMememail("");
		$this->setToken("");
	}

	function setId($id)					{$this->id = $id;}
	function setAuthkey($authkey)		{$this->authkey = $authkey;}
	function setShopurl($shopurl)		{$this->shopurl = $shopurl;}
	function setRefurl($refurl)			{$this->refurl = $refurl;}
	function setAuthidkey($authidkey)	{$this->authidkey = $authidkey;}
	function setMemid($memid)			{$this->memid = $memid;}
	function setMemgroup($memgroup)		{$this->memgroup = $memgroup;}
	function setMemname($memname)		{$this->memname = $memname;}
	function setMemreserve($memreserve)	{$this->memreserve = $memreserve;}
	function setMememail($mememail)		{$this->mememail = $mememail;}
	function setBoardadmin($boardadmin)	{$this->boardadmin = $boardadmin;}
	function setGifttempkey($gifttempkey){$this->gifttempkey = $gifttempkey;}
	function setOldtempkey($oldtempkey){$this->oldtempkey = $oldtempkey;}
	function setOkpayment($okpayment)	{$this->okpayment = $okpayment;}
	function setToken($token)		{$this->token = $token;}


	function getId()			{return $this->id;}
	function getAuthkey()		{return $this->authkey;}
	function getShopurl()		{return $this->shopurl;}
	function getRefurl()		{return $this->refurl;}
	function getAuthidkey()		{return $this->authidkey;}
	function getMemid()			{return $this->memid;}
	function getMemgroup()		{return $this->memgroup;}
	function getMemname()		{return $this->memname;}
	function getMemreserve()	{return $this->memreserve;}
	function getMememail()		{return $this->mememail;}
	function getBoardadmin()	{return $this->boardadmin;}
	function getGifttempkey()	{return $this->gifttempkey;}
	function getOldtempkey()	{return $this->oldtempkey;}
	function getOkpayment()		{return $this->okpayment;}
	function getToken()			{return $this->token;}

	//쇼핑몰 방문자수 확인
	function getShopCount() {
		$sql = "SELECT * FROM tblshopcount ";
		$result=mysql_query($sql,get_db_conn());
		if($row=mysql_fetch_object($result)) {
			$count=(int)$row->count;
		} else {
			$count=0;
		}
		mysql_free_result($result);
		return $count;
	}

	//LMS (mms) 사용 여부
	function useLMS() {
		$sql = "SELECT use_mms FROM tblsmsinfo ";
		$result=mysql_query($sql,get_db_conn());
		if($row=mysql_fetch_object($result)){
			$use_mms=$row->use_mms;
		}
		mysql_free_result($result);
		return $use_mms;
	}

	function getTempkey() {
		if(strlen($this->tempkey)!=32) {
			$basketauthkey=$_COOKIE["basketauthkey"];
			$this->tempkey=$basketauthkey;
		}
		return $this->tempkey;
	}

	function setTempkey($time,$isNULL="") {
		if($isNULL==true) {
			$basketauthkey="";
			setcookie("basketauthkey", $basketauthkey, 0, "/".RootPath, getCookieDomain());
		} else {
			/*			
			$basketauthkey = md5(uniqid(rand(),1));
			*/
			$basketauthkey = $this->solvTempkey();
			if($time>0 && $time!=0) {
				setcookie("basketauthkey", $basketauthkey, time()+3600*$time, "/".RootPath, getCookieDomain());
			} else {
				setcookie("basketauthkey", $basketauthkey, 0, "/".RootPath, getCookieDomain());
			}
		}
		$this->tempkey=$basketauthkey;
	}
	
	// 장바구니 보존처리 
	function solvTempkey(){
		$returnkey = NULL;		
		if(!empty($this->memid)){		
			if((false !== $res = mysql_query($sql,get_db_conn())) && mysql_num_rows($res)){
				$returnkey = mysql_result($res,0,0);
				@mysql_query("update basketauthkey set `date`=NOW() where id='".$this->memid."'",get_db_conn());
				@mysql_query("update tblbasket_normal set `date`='".date('YmdHis')."' where tempkey='".$returnkey."'",get_db_conn()); // ??간 갱신				
			}
		}
		if(empty($returnkey)){
			do{
				$rcnt = 0;
				$returnkey = md5(uniqid(rand(),1));
				$sql = "select count(*) from basketauthkey where basketauthkey='".$returnkey."'";				

				if(false !== $res = mysql_query($sql,get_db_conn())){
					$rcnt = mysql_result($res,0,0);
				}				
			}while($rcnt > 0);			

			if(!empty($this->memid)){
				$sql = "INSERT INTO basketauthkey (id,basketauthkey,date) VALUES ('".$this->memid."','".$returnkey."',NOW())  ON DUPLICATE KEY UPDATE basketauthkey=VALUES(basketauthkey), date=VALUES(date)";
				@mysql_query($sql,get_db_conn());
			}
		}	
		return $returnkey;
	}

	function getPgdata() {
		global $_data;
		$_data->escrow_id="";
		$_data->trans_id="";
		$_data->virtual_id="";
		$_data->card_id="";
		$_data->mobile_id="";
		if($f=@file(DirPath.AuthkeyDir."pg")) {
			for($i=0;$i<count($f);$i++) {
				$f[$i]=trim(str_replace("\n","",$f[$i]));
				if (substr($f[$i],0,12)=="escrow_id:::") $_data->escrow_id=decrypt_authkey(substr($f[$i],12));
				else if (substr($f[$i],0,11)=="trans_id:::") $_data->trans_id=decrypt_authkey(substr($f[$i],11));
				else if (substr($f[$i],0,13)=="virtual_id:::") $_data->virtual_id=decrypt_authkey(substr($f[$i],13));
				else if (substr($f[$i],0,10)=="card_id:::") $_data->card_id=decrypt_authkey(substr($f[$i],10));
				else if (substr($f[$i],0,12)=="mobile_id:::") $_data->mobile_id=decrypt_authkey(substr($f[$i],12));
			}
		}
	}

	function adminLogin() {
		global $shopurl,$mem_id,$mem_pw,$ssllogintype,$sessid,$history;
		$connect_ip = getenv("REMOTE_ADDR");

		if (strlen($mem_id)>0 && (strlen($mem_pw)>0 || ($ssllogintype=="ssl"))) {
			$flag	= false;
			$disabled = 0;
			$currenttime = time();
			$sql = "SELECT id, passwd, expirydate, disabled FROM tblsecurityadmin ";
			$sql.= "WHERE id='".$mem_id."' ";
			if($ssllogintype=="ssl") $sql.= "AND authkey='".$sessid."' ";
			else $sql.= "AND passwd=md5('".$mem_pw."') ";
			$result = mysql_query($sql,get_db_conn());
			if ($row = mysql_fetch_object($result)) {
				$id = $row->id;
				$passwd = $row->passwd;
				$expirydate = (int)$row->expirydate;
				$disabled = (int)$row->disabled;
				if ($expirydate == 0) {
					$flag = true;
				} else {
					if ($expirydate > time()) $flag = true;
					else $flag = false;
				}
				if ($disabled == 1) $flag = false;
				if($ssllogintype!="ssl") {
					if ($flag) {
						$flag = false;
						if (md5($mem_pw) == $passwd) $flag = true;
					}
				}
				if ($flag) {
					if(_DEMOSHOP!="OK") {
						$useshop=getUseShopDomain();
						if($useshop=="X") {
							error_msg("도메인 인증파일이 존재하지 않습니다.","http://"._IncomuUrl);
						} else if($useshop=="A") {
							error_msg("도메인 인증키가 잘못되었습니다.","http://"._IncomuUrl);
						} else if($useshop=="D") {
							error_msg("도메인 인증키가 잘못되었습니다.","http://"._IncomuUrl);
						} else if($useshop=="E") {
							error_msg("도메인 인증키가 잘못되었습니다.","http://"._IncomuUrl);
						} else if($useshop=="F") {
							error_msg("쇼핑몰을 운영할 수 있는 IP정보가 잘못되었습니다.","http://"._IncomuUrl);
						}
					}
					$authkey = md5(uniqid(""));
					$this->setShopurl($shopurl);
					$this->setId($id);
					$this->setAuthkey($authkey);
					$this->Save();
					$sql = "UPDATE tblsecurityadmin SET authkey='".$authkey."', lastlogintime='".time()."' ";
					$sql.= "WHERE id='".$id."'";
					$update = mysql_query($sql,get_db_conn());
					$log_content = "로그인 : $id";
					ShopManagerLog($id,$connect_ip,$log_content);
				} else {
					error_msg("로그인 정보가 올바르지 않습니다.<br>다시 확인하시기 바랍니다.",$history);
				}
			} else {
				error_msg("로그인 정보가 올바르지 않습니다.<br>다시 확인하시기 바랍니다.",$history);
			}
			mysql_free_result($result);
		} else {
			$id = $this->getId();
			$authkey = $this->getAuthkey();
			$sql = "SELECT * FROM tblsecurityadmin WHERE id='".$id."' AND authkey='".$authkey."' ";
			$result = mysql_query($sql,get_db_conn());
			$rows = mysql_num_rows($result);
			if ($rows <= 0) {
				$this->setId("");
				$this->setAuthkey("");
				$this->Save();
				error_msg("정상적인 경로로 다시 접속하시기 바랍니다.",$history);
			}
		}
	}

	function getSellerid() {
		$resdata="";
		if($f=@file(DirPath.AuthkeyDir.".seller")) {
			$sellerid=trim($f[0]);
			if(strlen($sellerid)>0) {
				$resdata_decrypt=decrypt_authkey($sellerid);
				$resdata = @explode("|", $resdata_decrypt);
			}
		}
		return $resdata[0];
	}
}

$_ShopInfo = new _ShopInfo($_COOKIE[_sinfo]);

class _ShopData extends _ShopInfo {
	var $shopdata		= "";

	function _ShopData($_ShopInfo) {
		global $ref;
		//$this=$_ShopInfo;

		$sql = "SELECT * FROM tblshopinfo ";
		$result=mysql_query($sql,get_db_conn());
		if($row=mysql_fetch_object($result)) {
			mysql_free_result($result);
			$this->shopdata=$row;

			$this->shopdata->onetop_type=$row->top_type;
			if ($row->frame_type=="Y") $this->shopdata->top_type="top";

			$this->shopdata->deli_basefee=$this->shopdata->deli_basefee+0;
			if($row->deli_setperiod<2) $this->shopdata->deli_setperiod=1;
			if($row->deli_basefee==-9) {
				$this->shopdata->deli_basefee=0;
				$this->shopdata->deli_after="Y";
			}
			if ($row->deli_miniprice==0) $this->shopdata->deli_miniprice=1000000000;
			else $this->shopdata->deli_miniprice = $row->deli_miniprice;
			if (strlen($row->deli_type)==0) $this->shopdata->deli_type=0;
			if (strlen($row->reserve_join)==0) $this->shopdata->reserve_join=0;

			$this->shopdata->primg_minisize2 = 270;

			$this->shopdata->countpath="<img src=\"".DirPath.FrontDir."counter.php?ref=".urlencode($ref)."\" width=0 height=0>";


			######## 쇼핑몰 레이아웃 관련(SHOPWIDTH, MAINUSED, MOUSEKEY, SHOPBGTYPE, BGCOLOR, BACKGROUND #########
			unset($layoutdata);
			if(strlen($row->layoutdata)>0) {
				$laytemp=explode("",$row->layoutdata);
				$laycnt=count($laytemp);
				for ($layi=0;$layi<$laycnt;$layi++) {
					$laytemp2=explode("=",$laytemp[$layi]);
					if(isset($laytemp2[1])) {
						$layoutdata[$laytemp2[0]]=$laytemp2[1];
					} else {
						$layoutdata[$laytemp2[0]]="";
					}
				}
			}
			$this->shopdata->layoutdata=$layoutdata;
			######################################################################################################


			unset($ETCTYPE);
			if (strlen($row->etctype)>0) {
				$etctemp = explode("",$row->etctype);
				$etccnt = count($etctemp);
				for ($etci=0;$etci<$etccnt;$etci++) {
					$etctemp2 = explode("=",$etctemp[$etci]);
					if(isset($etctemp2[1])) {
						$ETCTYPE[$etctemp2[0]]=$etctemp2[1];
					} else {
						$ETCTYPE[$etctemp2[0]]="";
					}
				}
			}
			$this->shopdata->ETCTYPE=$ETCTYPE;
			$this->shopdata->count=$this->getShopCount();
			$this->shopdata->visitor=$this->shopdata->count;

			$this->shopdata->primg_minisize2 = 270;

			unset($this->ssl_pagelist);
			if($row->ssl_type=="Y" && strlen($row->ssl_page)>0) {
				$temp=explode("|",$row->ssl_page);
				$cnt=count($temp);
				for ($i=0;$i<$cnt;$i++) {
					if (substr($temp[$i],0,6)=="ADMIN=") $this->shopdata->ssl_pagelist["ADMIN"]=substr($temp[$i],6);	#관리자 로그인페이지
					else if (substr($temp[$i],0,6)=="PLOGN=") $this->shopdata->ssl_pagelist["PLOGN"]=substr($temp[$i],6);	#파트너 로그인페이지
					else if (substr($temp[$i],0,6)=="VLOGN=") $this->shopdata->ssl_pagelist["VLOGN"]=substr($temp[$i],6);	#입점업체 로그인페이지
					else if (substr($temp[$i],0,6)=="LOGIN=") $this->shopdata->ssl_pagelist["LOGIN"]=substr($temp[$i],6);	#회원 로그인페이지
					else if (substr($temp[$i],0,6)=="MJOIN=") $this->shopdata->ssl_pagelist["MJOIN"]=substr($temp[$i],6);	#회원가입
					else if (substr($temp[$i],0,6)=="MEDIT=") $this->shopdata->ssl_pagelist["MEDIT"]=substr($temp[$i],6);	#회원정보수정
					else if (substr($temp[$i],0,6)=="MLOST=") $this->shopdata->ssl_pagelist["MLOST"]=substr($temp[$i],6);	#ID/PW찾기
					else if (substr($temp[$i],0,6)=="ORDER=") $this->shopdata->ssl_pagelist["ORDER"]=substr($temp[$i],6);	#주문페이지
					else if (substr($temp[$i],0,6)=="ADULT=") $this->shopdata->ssl_pagelist["ADULT"]=substr($temp[$i],6);	#성인인증
				}
			}

			if(strlen($row->search_info)>0) {
				$temp=explode("=",$row->search_info);
				$cnt = count($temp);
			}

			unset($this->shopdata->search_info);
			if($cnt>0) {
				$this->shopdata->search_info["autosearch"]="";
				$this->shopdata->search_info["bestkeyword"]="";
				$this->shopdata->search_info["bestauto"]="";
				$this->shopdata->search_info["keyword"]="";
				for ($i=0;$i<$cnt;$i++) {
					if (substr($temp[$i],0,11)=="AUTOSEARCH=") $this->shopdata->search_info["autosearch"]=substr($temp[$i],11);	#자동완성기능 사용여부(Y/N)
					else if (substr($temp[$i],0,12)=="BESTKEYWORD=") $this->shopdata->search_info["bestkeyword"]=substr($temp[$i],12);	#인기검색어기능 사용여부(Y/N)
					else if (substr($temp[$i],0,9)=="BESTAUTO=") $this->shopdata->search_info["bestauto"]=substr($temp[$i],9);	#인기검색어 자동추출인지 수동등록인지(Y/N)
					else if (substr($temp[$i],0,8)=="KEYWORD=") $this->shopdata->search_info["keyword"]=substr($temp[$i],8);	#인기검색어 수동등록 리스트
				}
			}
			if(strlen($this->shopdata->search_info["autosearch"])==0) $this->shopdata->search_info["autosearch"]="N";
			if(strlen($this->shopdata->search_info["bestkeyword"])==0) $this->shopdata->search_info["bestkeyword"]="Y";
			if(strlen($this->shopdata->search_info["bestauto"])==0) $this->shopdata->search_info["bestauto"]="Y";

			if(strlen($this->shopdata->ETCTYPE["SELFCODEVIEW"])>0) {
				$this->shopdata->ETCTYPE["SELFCODELOCAT"]="";
				$this->shopdata->ETCTYPE["SELFCODEBR"]="";

				if($this->shopdata->ETCTYPE["SELFCODEVIEW"]=="Y" || $this->shopdata->ETCTYPE["SELFCODEVIEW"]=="Z") {
					$this->shopdata->ETCTYPE["SELFCODELOCAT"]="Y";
				} else if($this->shopdata->ETCTYPE["SELFCODEVIEW"]=="N" || $this->shopdata->ETCTYPE["SELFCODEVIEW"]=="M") {
					$this->shopdata->ETCTYPE["SELFCODELOCAT"]="N";
				}

				if($this->shopdata->ETCTYPE["SELFCODEVIEW"]=="Y" || $this->shopdata->ETCTYPE["SELFCODEVIEW"]=="N") {
					$this->shopdata->ETCTYPE["SELFCODEBR"]="<br>";
				}

				if(strlen($this->shopdata->ETCTYPE["SELFCODEF"])>0) {
					$this->shopdata->ETCTYPE["SELFCODEF"] = str_replace(" ", "&nbsp;", @htmlspecialchars($this->shopdata->ETCTYPE["SELFCODEF"]));
				}

				if(strlen($this->shopdata->ETCTYPE["SELFCODEB"])>0) {
					$this->shopdata->ETCTYPE["SELFCODEB"] = str_replace(" ", "&nbsp;", @htmlspecialchars($this->shopdata->ETCTYPE["SELFCODEB"]));
				}
			}
		} else {
			mysql_free_result($result);

			//쇼핑몰 정보 등록이 안되었으니까 error 페이지 함수 호출
			error_msg("쇼핑몰 정보 등록이 안되었습니다.<br>쇼핑몰 설정을 먼저 하십시요",DirPath."install.php");
		}
	}
}



class _PartnerInfo {
	var $joindate		= "";
	var $partner_id		= "";
	var $partner_authkey= "";

	function _PartnerInfo($_pinfo) {
		if ($_pinfo) {
			$savedata=unserialize(decrypt_md5($_pinfo));

			$this->joindate			= $savedata["joindate"];
			$this->partner_id		= $savedata["partner_id"];
			$this->partner_authkey	= $savedata["partner_authkey"];
		}
	}

	function Save() {
		$savedata["joindate"]		= $this->getJoindate();
		$savedata["partner_id"]		= $this->getPartnerid();
		$savedata["partner_authkey"]= $this->getpartnerauthkey();

		$_pinfo = encrypt_md5(serialize($savedata));
		setcookie("_pinfo", $_pinfo, 0, "/".RootPath.PartnerDir, getCookieDomain());
	}

	function setJoindate($joindate)		{$this->joindate = $joindate;}
	function setPartnerid($partner_id)	{$this->partner_id = $partner_id;}
	function setPartnerauthkey($partner_authkey)		{$this->partner_authkey = $partner_authkey;}


	function getJoindate()		{return $this->joindate;}
	function getPartnerid()			{return $this->partner_id;}
	function getpartnerauthkey()		{return $this->partner_authkey;}
}

function DemoShopCheck($errormsg, $url="") {
	global $MenuCode;
	if(_DEMOSHOP=="OK") {
		if(getenv("REMOTE_ADDR")!=_ALLOWIP) {
			$errormsg=str_replace("<br>","\\n",$errormsg);
			$errormsg=str_replace("\"","\\\"",$errormsg);
			if($url=="window.close()") {
				echo "<html><head><title></title></head><body onload=\"alert('".$errormsg."');window.close();\"></body></html>";exit;
			} else if($url=="history.go(-1)" || $url=="history.back()") {
				echo "<html><head><title></title></head><body onload=\"alert('".$errormsg."');history.go(-1);\"></body></html>";exit;
			} else if(strlen($url)>0) {
				echo "<html><head><title></title></head><body onload=\"alert('".$errormsg."');location='".$url."'\"></body></html>";exit;
			} else {
				include("AccessDeny.inc.html");
				exit;
			}
		}
	}
}

function SetReserve($id, $reserve, $content, $orderdata="") {
	if(strlen($reserve)>0 && $reserve!=0) {
		if($reserve>0) $yn="Y";
		else if($reserve<0) $yn="N";
		$date=date("YmdHis");
		$sql = "INSERT tblreserve SET ";
		$sql.= "id			= '".$id."', ";
		$sql.= "reserve		= ".$reserve.", ";
		$sql.= "reserve_yn	= '".$yn."', ";
		$sql.= "content		= '".$content."', ";
		$sql.= "orderdata	= '".$orderdata."', ";
		$sql.= "date		= '".$date."' ";
		if(mysql_query($sql,get_db_conn())) {
			$sql = "UPDATE tblmember SET ";
			$sql.= "reserve=reserve+$reserve ";
			$sql.= "WHERE id = '".$id."' ";
			if(mysql_query($sql,get_db_conn())) {
				return true;
			}
		}
	}
}

function getReserveConversion($reserve,$reservetype,$sellprice,$reservshow) {
	global $_ShopInfo, $_data;

	$_data->ETCTYPE["MEM"]=(isset($_data->ETCTYPE["MEM"])?$_data->ETCTYPE["MEM"]:"");

	if($_data->ETCTYPE["MEM"]=="Y" && strlen($_ShopInfo->getMemid())==0 && $reservshow=="Y") {
		return 0;
	} else {
		$sellprice = (int)$sellprice;
		if($reservetype=="Y") {
			if($sellprice>0 && $reserve>0) {
				//return @round($sellprice*$reserve*0.01);
				return @round($sellprice*$reserve);
			} else {
				return 0;
			}
		} else {
			return $reserve;
		}
	}
}

function viewselfcode($productname,$selfcode) {
	GLOBAL $_data,$selfcodefont_start,$selfcodefont_end;

	if(strlen($selfcode)>0) {
		$selfcode = $selfcodefont_start.$_data->ETCTYPE["SELFCODEF"].$selfcode.$_data->ETCTYPE["SELFCODEB"].$selfcodefont_end;

		if($_data->ETCTYPE["SELFCODELOCAT"]=="Y") {
			return $selfcode.$_data->ETCTYPE["SELFCODEBR"].$productname;
		} else if($_data->ETCTYPE["SELFCODELOCAT"]=="N") {
			return $productname.$_data->ETCTYPE["SELFCODEBR"].$selfcode;
		} else {
			return $productname;
		}
	} else {
		return $productname;
	}
}

function viewproductname($productname,$icon,$selfcode,$addcode=""){
	global $Dir,$iconyes,$_ShopInfo;

	$productname = viewselfcode($productname,$selfcode);

	$oriicon=$icon;
	$icoi = strpos(" ".$icon,"ICON=");
	if($icoi>0){
		if(is_array($iconyes)==false) {
			getUsericon();
		}
		$icon = substr($icon,strpos($icon,"ICON="));
		$icon = substr($icon,5,strpos($icon,"")-5);
		$num=strlen($icon) ;
		$iconname="";
		for($i>0,$i=0;$i<$num;$i+=2){
			$temp=$icon[$i].$icon[$i+1];
			if(preg_match("/^(U)[1-6]$/",$temp) && $iconyes[$temp]=="Y") {
				$iconname.=" <img src=\"http://".$_ShopInfo->shopurl.DataDir."shopimages/etc/icon".$temp.".gif\" align=absmiddle border=0>";
			} else if(strlen($temp)>0 && !preg_match("/^(U)[1-6]$/",$temp)) {
				$iconname.=" <img src=\"http://".$_ShopInfo->shopurl."images/common/icon".$temp.".gif\" align=absmiddle border=0>";
			}
		}
		//return $productname.(strlen($addcode)>0?" - ".$addcode:"")."</a>".$iconname;
		//return $productname.(strlen($addcode)>0?" - ".$addcode:"").$iconname;
		return $iconname."<span style='color:#000;font-weight:bold;'>".$productname."</span>".(strlen($addcode)>0?" <br><span style='font-size:11px;'>".$addcode."</span>":"");
	} else {
		//return $productname.(strlen($addcode)>0?" - ".$addcode:"")."</a>";
		return "<span style='color:#000;font-weight:bold;'>".$productname."</span>".(strlen($addcode)>0?" <br><span style='font-size:11px;'>".$addcode."</span>":"");
	}
}

function getUsericon() {
	global $iconyes, $Dir;
	if(is_array($iconyes)==false) {
		$filepath=$Dir.DataDir."shopimages/etc/";
		$icon = array("U1","U2","U3","U4","U5","U6");
		$iconnum = count($icon);
		for($i=0;$i<$iconnum;$i++){
			if(file_exists($filepath."icon".$icon[$i].".gif")) {
				$iconyes[$icon[$i]]="Y";
			} else {
				$iconyes[$icon[$i]]="N";
			}
		}
	}
}

function soldout($temp=0){
	global $_ShopInfo, $Dir;
	if(file_exists($Dir.DataDir."shopimages/etc/soldout.gif")){
		return "<img src=\"".$Dir.DataDir."shopimages/etc/soldout.gif\" align=absmiddle border=0>";
	} else {
		if($temp==1) {
			return "<b><font color=red style=\"font-size:9pt\">품절</font></b>";
		} else {
			return " <font color=red style=\"font-size:9pt\">(품절)</font>";
		}
	}
}

function dickerview($etctype,$price=0,$ectype=0) {
	global $_ShopInfo, $Dir;
	global $_data;

	$_data->ETCTYPE["MEM"]=(isset($_data->ETCTYPE["MEM"])?$_data->ETCTYPE["MEM"]:"");
	$_data->ETCTYPE["MEMIMG"]=(isset($_data->ETCTYPE["MEMIMG"])?$_data->ETCTYPE["MEMIMG"]:"");
	$_data->ETCTYPE["SELL"]=(isset($_data->ETCTYPE["SELL"])?$_data->ETCTYPE["SELL"]:"");

	if($_data->ETCTYPE["MEM"]=="Y" && strlen($_ShopInfo->getMemid())==0) {
		if ($_data->ETCTYPE["MEMIMG"]=="Y" && file_exists($Dir.DataDir."shopimages/etc/priceicon.gif")) {
			return "<img src=\"".$Dir.DataDir."shopimages/etc/priceicon.gif\" border=0 align=absmiddle>";
		} else if ($_data->ETCTYPE["MEMIMG"]=="N") {
			return "<img width=1 height=0 border=0>";
		} else if (strlen($_data->ETCTYPE["MEMIMG"])>0 && $_data->ETCTYPE["MEMIMG"]!="Y") {
			return "<img src=\"".$Dir."images/common/priceicon".$_data->ETCTYPE["MEMIMG"].".gif\" border=0 align=absmiddle>";
		} else {
			return "<font color=red>회원공개</font>";
		}
	}
	$dicker_pos=strpos($etctype,"DICKER=");
	if ($dicker_pos===false) {
		if(strlen($_data->ETCTYPE["SELL"])==0 && $ectype==0) {
			//return "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle> ".$price;
			return $price;
		} else {
			if($ectype==1) return;
			$type=explode(",",$_data->ETCTYPE["SELL"]);
			if(strlen($type[0])>0) $price="<b>".$price."</b>";
			if(strlen($type[1])>0) $price="<font color=".$type[1].">".$price."</font>";
			//return "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle> ".$price;
			return $price;
		}
	} else {
		$f_dicker=substr($etctype,$dicker_pos+7);
		$dicker_pos2=strpos($f_dicker,"");
		return str_replace("$","&#036;",substr($f_dicker,0,$dicker_pos2));
	}
}

//에스크로 설정정보 읽어옴
function GetEscrowType($escrow_info) {
	$val = array();
	$list = explode("|",$escrow_info);
	for ($i=0;$i<count($list); $i++) {
		$data = explode("=",$list[$i]);
		$val[$data[0]] = $data[1];
	}
	return $val;
}

//tblshopdetail 테이블의 etcfield값 get
function getEtcfield($etcfield,$key) {
	$val="";
	$arrayetc=explode("=",$etcfield);
	$cnt=count($arrayetc);
	for($i=0;$i<$cnt;$i++) {
		if (substr($arrayetc[$i],0,strlen($key)+1)==($key."=")) {
			$val=substr($arrayetc[$i],strlen($key)+1);
			break;
		}
	}
	return $val;
}

function setEtcfield($etcfield,$key,$val) {
	$etcvalue="";
	$isfind=false;
	$arrayetc=explode("=",$etcfield);
	$cnt=count($arrayetc);
	for($i=0;$i<$cnt;$i++) {
		if (substr($arrayetc[$i],0,strlen($key)+1)==($key."=")) {
			if(strlen($val)) {
				$etcvalue.=$key."=".$val."=";
			}
			$isfind=true;
		} else {
			if(strlen($arrayetc[$i])>0) $etcvalue.=$arrayetc[$i]."=";
		}
	}
	if(!$isfind && strlen($val)>0) {
		$etcvalue=$key."=".$val."=";
	}
	$sql = "UPDATE tblshopinfo SET etcfield='".$etcvalue."' ";
	mysql_query($sql,get_db_conn());
	DeleteCache("tblshopinfo.cache");

	return $etcvalue;
}


function ShopManagerLog($id,$ip,$content,$date="") {
	if (strlen($date)!=14) {
		$date=date("YmdHis");
	}
	$sql = "INSERT tblsecurityadminlog SET ";
	$sql.= "id			= '".$id."', ";
	$sql.= "date		= '".$date."', ";
	$sql.= "ip			= '".$ip."', ";
	$sql.= "content		= '".$content."' ";
	mysql_query($sql,get_db_conn());
}

function getEncKey($str) {
	$t_total = strlen($str);
	$sum_len = ord($str{$t_total - 3}) + ord($str{$t_total - 2});
	$id1 = $sum_len % 11;
	$id2 = $sum_len % 6;
	$key = $sum_len.$id1.$id2;

	return $key;
}

function getVenderUsed() {
	global $_ShopInfo;
	$resdata=array();
	if($f=@file(DirPath.AuthkeyDir."vender")) {
		for($i=0;$i<count($f);$i++) {
			$f[$i]=trim(str_replace("\n","",$f[$i]));
			if (substr($f[$i],0,12)=="VENDERPKG:::") {
				$tempdata=explode("|",decrypt_authkey(substr($f[$i],12)));
				$resdata["OK"]=$tempdata[0];
				$resdata["COUNT"]=$tempdata[1];
				$resdata["DATE"]=$tempdata[2];
				$resdata["DOMAIN"]=$tempdata[3];
				if(substr($_ShopInfo->getShopurl(),0,4)!="www." && substr($resdata["DOMAIN"],0,4)=="www.") {
					$resdata["DOMAIN"]=substr($resdata["DOMAIN"],4);					
				}else if(substr($_ShopInfo->getShopurl(),0,4)=="www." && substr($resdata["DOMAIN"],0,4)!="www."){
					$resdata["DOMAIN"]='www.'.$resdata["DOMAIN"];	
				}
				return $resdata;
				break;
			}
		}
	}
}

function getSmscount($id,$authkey) {
	$host=getSmshost(&$path);
	$path=$path."/process/getsmscount.html";
	$enckey=getEncKey($id);
	$query="&shopid=".$id."&authkey=".$authkey."&enckey=".$enckey;

	$resdata=SendSocketPost($host,$path,$query);
	return $resdata;
}

function getSmspaylist() {
	$host=getSmshost(&$path);
	$path=$path."/process/getpaylist.html";

	$resdata=SendSocketPost($host,$path,"");
	return $resdata;
}

function getSmssendlist($id,$authkey,$query) {
	$host=getSmshost(&$path);
	$path=$path."/process/getsendlist.html";
	$enckey=getEncKey($id);
	$query="&shopid=".$id."&authkey=".$authkey."&enckey=".$enckey."&".$query;

	$resdata=SendSocketPost($host,$path,$query);
	return $resdata;
}

function getSmsfillinfo($id,$authkey,$query) {
	$host=getSmshost(&$path);
	$path=$path."/process/getfillinfo.html";
	$enckey=getEncKey($id);
	$query="&shopid=".$id."&authkey=".$authkey."&enckey=".$enckey."&".$query;

	$resdata=SendSocketPost($host,$path,$query);
	return $resdata;
}

function SendSMS( $shopid, $authkey, $totellist, $tonamelist, $fromtel, $date, $msg, $etcmsg, $use_mms="" ) {

	GLOBAL $_ShopInfo;

	if( $use_mms == "" ) $use_mms = $_ShopInfo->useLMS();

	#smsID, sms인증키, 받는사람핸드폰, 받는사람명, 보내는사람(회신전화번호), 발송일, 메세지, etc메세지(예:개별 메세지 전송)
	if(strlen($shopid)>0 && strlen($authkey)>0) {
		$host=getSmshost(&$path);
		$path=$path."/process/sendsms.php";
		$service=getenv("HTTP_HOST");

		if( $use_mms=='Y' AND strlen($msg)<81 ) $use_mms = 'N';

		$enckey=getEncKey($shopid);
		$query="&tran_id=".$shopid."&authkey=".$authkey."&enckey=".$enckey."&tran_refkey=".$service."&tran_phone=".$totellist."&tran_callback=".$fromtel."&tran_date=".$date."&name=".urlencode($tonamelist)."&tran_msg=".urlencode($msg)."&tran_etc1=".$etcmsg."&tran_etc2=".getenv("SERVER_ADDR")."&use_mms=".$use_mms."";

		$resdata=SendSocketPost($host,$path,$query);
		return $resdata;
	}

	#SMS 발송 가능 횟수는 SMS서버에서 확인 후 메세지를 리턴한다.
	#return "[SMS]문자메세지를 발송하였습니다.";
}

//mms기능
function SendSMS2($shopid, $authkey, $totellist, $tonamelist, $fromtel, $date, $msg, $etcmsg, $use_mms="") {
	#smsID, sms인증키, 받는사람핸드폰, 받는사람명, 보내는사람(회신전화번호), 발송일, 메세지, etc메세지(예:개별 메세지 전송), mms사용유무(Y)
	if(strlen($shopid)>0 && strlen($authkey)>0) {
		$host=getSmshost(&$path);
		$path=$path."/process/sendsms.php";
		$service=getenv("HTTP_HOST");

		if( $use_mms=='Y' AND strlen($msg)<81 ) $use_mms = 'N';

		$enckey=getEncKey($shopid);
		$query="&tran_id=".$shopid."&authkey=".$authkey."&enckey=".$enckey."&tran_refkey=".$service."&tran_phone=".$totellist."&tran_callback=".$fromtel."&tran_date=".$date."&name=".urlencode($tonamelist)."&tran_msg=".urlencode($msg)."&tran_etc1=".$etcmsg."&tran_etc2=".getenv("SERVER_ADDR")."&use_mms=".$use_mms."";

		$resdata=SendSocketPost($host,$path,$query);
		return $resdata;
	}

	#SMS 발송 가능 횟수는 SMS서버에서 확인 후 메세지를 리턴한다.
	#return "[SMS]문자메세지를 발송하였습니다.";
}

function getRemoteImageData($host,$path,$ext,$port=80) {
	$fp = @fsockopen($host, $port, &$errno, &$errstr, 3);
	if(!$fp) {
		@fclose($fp);
		return "ERROR : $errstr ($errno)";
	} else {
		$cmd = "GET $path HTTP/1.1\n";
		fputs($fp, $cmd);
		$cmd = "Host: $host\n";
		fputs($fp, $cmd);
		$cmd = "Content-type: image/$ext\n";
		fputs($fp, $cmd);
		$cmd = "Connection: close\n\n";
		fputs($fp, $cmd);
		while($currentHeader = fgets($fp,4096)) {
			if($currentHeader == "\r\n") {
				break;
			}
		}
		$strLine = "";
		while(!feof($fp)) {
			$strLine .= fgets($fp, 4096);
		}
		fclose($fp);
		return $strLine;
	}
}

function getSecureKeyData($key) {
	if (file_exists(DirPath.DataDir."ssl/".$key.".temp")==true) {
		if($fp=@fopen(DirPath.DataDir."ssl/".$key.".temp", "r")) {
			$secure_data=fread($fp, filesize(DirPath.DataDir."ssl/".$key.".temp"));
			fclose($fp);
			$secure_data=unserialize($secure_data);
		}
		@unlink(DirPath.DataDir."ssl/".$key.".temp");
		return $secure_data;
	}
}

function delProductMultiImg($type,$code,$productcode) {
	global $Dir;
	include ($Dir."lib/prmultiprocess.php");
}

function SendSocketPost($host,$path,$query,$port=80) {
	$fp = @fsockopen($host, $port, &$errno, &$errstr, 3);
	if(!$fp) {
		@fclose($fp);
		return "ERROR : $errstr ($errno)";
	} else {
		$cmd = "POST $path HTTP/1.0\n";
		fputs($fp, $cmd);
		$cmd = "Host: $host\n";
		fputs($fp, $cmd);
		$cmd = "Content-type: application/x-www-form-urlencoded\n";
		fputs($fp, $cmd);
		$cmd = "Content-length: " . strlen($query) . "\n";
		fputs($fp, $cmd);
		$cmd = "Connection: close\n\n";
		fputs($fp, $cmd);
		fputs($fp, $query);
		while($currentHeader = fgets($fp,4096)) {
			if($currentHeader == "\r\n") {
				break;
			}
		}
		$strLine = "";
		while(!feof($fp)) {
			$strLine .= fgets($fp, 4096);
		}
		fclose($fp);
		return $strLine;
	}
}

function SendSocketGet($host,$path,$query,$port=80) {
	$fp = @fsockopen($host, $port, &$errno, &$errstr, 3);
	if(!$fp) {
		@fclose($fp);
		return "ERROR : $errstr ($errno)";
	} else {
		$cmd = "GET $path?$query HTTP/1.0\n";
		fputs($fp, $cmd);
		$cmd = "Host: $host\n";
		fputs($fp, $cmd);
		$cmd = "Content-type: application/x-www-form-urlencoded\n";
		fputs($fp, $cmd);
		$cmd = "Connection: close\n\n";
		fputs($fp, $cmd);
		while($currentHeader = fgets($fp,4096)) {
			if($currentHeader == "\r\n") {
				break;
			}
		}
		$strLine = "";
		while(!feof($fp)) {
			$strLine .= fgets($fp, 4096);
		}
		fclose($fp);
		return $strLine;
	}
}

function ismail($strEmail) {
	if (eregi("^[^@ ]+@([a-zA-Z0-9\-]+\.)+([a-zA-Z0-9\-]{2}|net|com|gov|mil|org|edu|int)$", $strEmail)) {
		return true;
	} else {
		return false;
	}
}

function IsAlphaNumeric($data) {
	$numstr = "0123456789abcdefghijklmnopqrstuvwxyz";
	$thischar="";
	$count = 0;
	$data = strtolower($data);

	for($i=0; $i<strlen($data);$i++) {
		$thischar = substr($data,$i,1);
		if (eregi($thischar,$numstr))
			$count++;
	}
	if ($count == strlen($data))
		return true;
	else
		return false;
}

function IsNumeric($data) {
	$numstr = "0123456789";
	$thischar="";
	$count = 0;
	$data = strtolower($data);

	for($i=0; $i<strlen($data);$i++) {
		$thischar = substr($data,$i,1);
		if (eregi($thischar,$numstr))
			$count++;
	}
	if ($count == strlen($data))
		return true;
	else
		return false;
}

function getMailHeader($send_name,$send_email) {
	$mailheaders  = "From:$send_name <$send_email>\r\n";
	//$mailheaders .= "X-Mailer:SendMail\r\n";
	//$mailheaders .= "MIME-Version: 1.0\r\n";
	$mailheaders .= "Content-Type: text/html; charset=euc-kr\r\n";
	return $mailheaders;
}

function sendmail($to, $subject, $body, $header) {
	@mail($to,$subject,$body,$header);
/*
	$host="www.nicei.net";
	$path="/_mail.html";
	$fp = fsockopen($host, 80, &$errno, &$errstr, 3);
	if(!$fp) {
		$res="FAIL";
		flush();
		@fclose($fp);
	} else {
		$qry.="&to=".urlencode($to)."&subject=".urlencode($subject)."&body=".urlencode($body)."&header=".urlencode($header);
		$cmd = "POST $path HTTP/1.0\n";
		fputs($fp, $cmd);
		$cmd = "Host: $host\n";
		fputs($fp, $cmd);
		$cmd = "Content-type: application/x-www-form-urlencoded\n";
		fputs($fp, $cmd);
		$cmd = "Content-length: " . strlen($qry) . "\n";
		fputs($fp, $cmd);
		$cmd = "Connection: close\n\n";
		fputs($fp, $cmd);
		fputs($fp, $qry);
		@fclose($fp);
	}
*/
}

function getMailData($sender_name,$sender_email,$message,$file,&$bodytext,&$mailheaders) {
	$boundary = "--------" . uniqid("part");

	$mailheaders  = "From:$sender_name <$sender_email>\r\n";
	//$mailheaders .= "X-Mailer:SendMail\r\n";
	//$mailheaders .= "MIME-Version: 1.0\r\n";

	if ($file && $file["size"]>0) {	// 첨부파일 있으면...
		$mailheaders .= "Content-Type: Multipart/mixed; boundary=\"$boundary\"";
		$bodytext  = "This is a multi-part message in MIME format.\r\n";
		$bodytext .= "\r\n--$boundary\r\n";
		$bodytext .= "Content-Type: text/html; charset=euc-kr\r\n";
		$bodytext .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
		$bodytext .= $message . "\r\n\r\n";

		$filename = basename($file["name"]);
		$result = fopen($file["tmp_name"], "r");
		$file = fread($result, $file["size"]);
		fclose($result);

		if ($upfile["type"]=="") {
			$upfile["type"] = "application/octet-stream";
		}

		$bodytext .= "\r\n--$boundary\r\n";
		$bodytext .= "Content-Type: $upfile[type]; name=\"$filename\"\r\n";
		$bodytext .= "Content-Transfer-Encoding: base64\r\n";
		$bodytext .= "Content-Disposition: attachment; filename=\"$filename\"\r\n\r\n";
		$bodytext .= chunk_split(base64_encode($file))."\r\n";
		$bodytext .= "\r\n--".$boundary."--\r\n";
	} else {
		$mailheaders .= "Content-Type: text/html; charset=euc-kr\r\n";
		$bodytext .= $message . "\r\n\r\n";
	}
}

function getDirList($path) {
	global $dirlist;

	$directory = dir($path);
	while($entry = $directory->read()) {
		if ($entry != "." && $entry != "..") {
			if (is_dir($path."/".$entry)) {
				$dirlist[]=$path."/".$entry;
				getDirList($path."/".$entry);
			}
		}
	}
	$directory->close();
}

function getFileList($path) {
	unset($filelist);
	$directory = dir($path);
	while($entry = $directory->read()) {
		if ($entry != "." && $entry != "..") {
			if (!is_dir($path."/".$entry)) {
				$filelist[]=$entry;
			}
		}
	}
	$directory->close();

	return $filelist;
}

function proc_rmdir($path) {
	global $rmdirlist;
	$rmdirlist[]=$path;
	$directory = dir($path);
	while($entry = $directory->read()) {
		if ($entry != "." && $entry != "..") {
			if (is_dir($path."/".$entry)) {
				proc_rmdir($path."/".$entry);
			} else {
				@unlink($path."/".$entry);
			}
		}
	}
	$directory->close();

	for($i=0;$i<count($rmdirlist);$i++) {
		if(is_dir($rmdirlist[$i])) {
			@rmdir($rmdirlist[$i]);
		}
	}
}

function proc_matchfiledel($match) {
	if(strlen($match)>0) {
		$match=str_replace(" ","",$match);
		$matches=glob($match);
		if(is_array($matches)) {
			foreach($matches as $delfile) {
				@unlink($delfile);
			}
		}
	}
}

function titleCut($len_title,$title) {
	$trim_len=strlen(substr($title,0,$len_title));
	if (strlen($title) > $trim_len){
		for($jj=0;$jj < $trim_len;$jj++) {
			$uu=ord(substr($title, $jj, 1));
			if( $uu > 127 ){
				$jj++;
			}
		}
		$n_title=substr($title,0,$jj);
		$n_title=$n_title."...";
	} else {
		$n_title = $title;
	}
	return $n_title;
}

function unique_id() {
	$now = (string)microtime();
	$now = explode(" ", $now);
	$unique_id = $now[1].str_replace(".", "", $now[0]);
	unset($now);

	$tm = date("YmdHis",substr($unique_id,0,10)).substr($unique_id,11,5)."A";
	return $tm;
}

//번호만 추출
function check_num($str){
	$str2="";
	for($i=0;$i<strlen($str);$i++){
		if('0'<=$str[$i] && $str[$i]<='9') $str2.=$str[$i];
	}
	return $str2;
}

//전화번호 정비
function replace_tel($tel) {
	$tel2="";
	if(substr($tel,0,2)=="02") {
		$tel2="02-";
		$num=2;
	} else {
		if (strlen($tel)<=8) {
			$tel2="02-";
			$num=0;
		} else {
			$tel2=substr($tel,0,3)."-";
			$num=3;
		}
	}
	if(strlen($tel)-$num==7) $tel2.=substr($tel,$num,3)."-".substr($tel,$num+3,4);
	else $tel2.=substr($tel,$num,4)."-".substr($tel,$num+4,4);
	return $tel2;
}

//핸드폰 번호 체크
function check_mobile_head($tel){
	$tel2=check_num($tel);
	if(strlen($tel2)!=0){
		$telhead=substr($tel2,0,3);
		if($telhead=="010" || $telhead=="011" || $telhead=="016" || $telhead=="017" || $telhead=="018" || $telhead=="019") {
			return $tel2;
		}
	}
	return 0;
}


//사업자등록번호 체크 함수
function chkBizNo($val) {
	if (strlen($val) == 10) {
		$bizID = $val;
		$checkID = Array(1, 3, 7, 1, 3, 7, 1, 3, 5, 1);
		$chkSum = 0;

		for ($i=0; $i<=7; $i++) $chkSum += $checkID[$i] * substr($bizID,$i,1);

		$c2 = "0" . ($checkID[8] * substr($bizID,8,1));
		$c2 = substr($c2, strlen($c2) - 2, strlen($c2));

		$chkSum += floor(substr($c2,0,1)) + floor(substr($c2,1,1));

		$remainder = (10 - ($chkSum % 10)) % 10 ;

		if (floor(substr($bizID,9,1)) != $remainder) {
			return false;
		} else {
			return true;
		}
	} else {
		return false;
	}
}

//주민등록번호 체크 함수
function chkResNo($val) {
	if (strlen($val)==13) {
		$calStr1="234567892345";
		$biVal=0;
		$restCal="";

		for($i=0;$i<=11;$i++) {
			$biVal = $biVal + (substr($val,$i,1) * substr($calStr1,$i,1));
		}

		$restCal = 11 - ($biVal % 11);

		if ($restCal == 11) {
			$restCal = 1;
		}

		if ($restCal == 10) {
			$restCal = 0;
		}

		if ($restCal == substr($val,12,1)) {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}

//나이계산 (13자리)
function getAgeResno($resno) {
	$age=0;
	$gbn=substr($resno,6,1);
	if($gbn=="3" || $gbn=="4") {
		$year="20".substr($resno,0,2);
		$age=date("Y")-$year;
	} else if ($gbn=="1" || $gbn=="2") {
		$year="19".substr($resno,0,2);
		$age=date("Y")-$year;
	}
	return $age;
}

function getUrl() {
	$file = getenv("SCRIPT_NAME");
	$query = getenv("QUERY_STRING");
	$chUrl = $file;

	if($query) $chUrl.="?".$query;
	return urlencode($chUrl);
}

function getTitle($title) {
	$title = stripslashes($title);
	$title = str_replace("\"", "＂", $title);	// " 문자의 변환
	$title = str_replace("\'", "`", $title);	// " 문자의 변환
	$title = str_replace("'", "`", $title);	// " 문자의 변환
	//$title = str_replace("<","&lt",$title);
	//$title = str_replace(">","&gt",$title);
	return $title;
}

function getStripHide($msg) {
	$msg = str_replace("<!--","&lt;!--",$msg);
	$msg = str_replace("-->","--&gt;",$msg);
	return $msg;
}

function isNull($str) {
	$tmp=str_replace("　","",$str);
	$tmp=str_replace("\n","",$tmp);
	$tmp=strip_tags($tmp);
	$tmp=str_replace("&nbsp;","",$tmp);
	$tmp=str_replace(" ","",$tmp);
	if(eregi("[^[:space:]]",$tmp)) return 0;
	return 1;
}

function autoLink($str) {
	// http url
	$homepage_pattern = "/([^\"\=\>])(mms|http|HTTP|ftp|FTP|telnet|TELNET)\:\/\/(.[^ \n\<\"]+)/";
	$str = preg_replace($homepage_pattern,"\\1<a href=\\2://\\3 target=_blank>\\2://\\3</a>", " ".$str);

	// mail
	$email_pattern = "/([ \n]+)([a-z0-9\_\-\.]+)@([a-z0-9\_\-\.]+)/";
	$str = preg_replace($email_pattern,"\\1<a href=mailto:\\2@\\3>\\2@\\3</a>", " ".$str);

	return $str;
}

function buffer_process($buffer) {
	$data = str_replace("\r","",$buffer);
	$data = explode("\n",str_replace("'","\\'",$data));
	unset($buffer);
	for ($i=0;$i<sizeOf($data);$i++) {
		$temp.= "document.writeln('".$data[$i]."');\n";
	}
	return $temp;
}

function backup_save_sql($sql) {
	$savepatten=array("/\n/","/^M/");
	$savereplace=array("","\\r\\n");
	$savetemp = preg_replace($savepatten,$savereplace,$sql)."; /** ".date("H").":".date("i")."**/\n";
	$file = DirPath.DataDir."backup/".date("Y")."_".date("m")."_".date("d")."_"."00______";
	$fp = fopen("$file","a");
	fputs($fp,$savetemp);
	fclose($fp);
}

function getSearchBestKeyword($target,$maxkeylen,$str,$keygbn=",",$keystyle="") {
	$data="";
	$yy=0;
	if(strlen($str)>0) {
		$tempbest=explode(",",$str);
		for($i=0;$i<count($tempbest);$i++) {
			$tempbestname=$tempbest[$i];
			if(($yy+strlen($tempbest[$i]))>$maxkeylen) {
				for($jj=0;$jj < $maxkeylen;$jj++) {
					$uu=ord(substr($tempbest[$i], $jj, 1));
					if( $uu > 127 ){
						$jj++;
					}
					if(($yy+$jj)>=$maxkeylen) {
						break;
					}
				}
				$tempbestname=substr($tempbestname,0,$jj-1)."...";
				if($i>0) $data.=$keygbn;
				$data.="<A HREF=\"".DirPath.FrontDir."productsearch.php?search=".urlencode($tempbest[$i])."\" ".$target." ".$keystyle.">".$tempbestname."</A>";
				break;
			} else {
				$yy=$yy+strlen($tempbest[$i]);
				if($yy>$maxkeylen) break;
				else {
					if($i>0) $data.=$keygbn;
					$data.="<A HREF=\"".DirPath.FrontDir."productsearch.php?search=".urlencode($tempbest[$i])."\" ".$target." ".$keystyle.">".$tempbestname."</A>";
				}
			}
		}
	}
	return $data;
}

function return_vat($vatprice) {
	$vatprice = (int)$vatprice;
	if($vatprice>0) {
		return @round(($vatprice/10)/10)*10;
	} else {
		return 0;
	}
}

function setDeliLimit($totalprice,$delilimit,$msguse="N") {
	$deli_limit_exp = explode("=",$delilimit);
	for($i=0; $i<count($deli_limit_exp); $i++) {
		$deli_limit_exp2=explode("",$deli_limit_exp[$i]);
		if(strlen($deli_limit_exp2[1])>0) {
			if($deli_limit_exp2[0]<=$totalprice && $totalprice<$deli_limit_exp2[1]) {
				$delilmitprice = (int)$deli_limit_exp2[2];
				if($msguse=="Y") {
					$delilmitprice.= "".number_format($deli_limit_exp2[0])." 이상 ".number_format($deli_limit_exp2[1])." 미만";
				}
				break;
			} else {
				$delilmitprice="";
			}
		} else {
			if($deli_limit_exp2[0]<=$totalprice) {
				$delilmitprice = (int)$deli_limit_exp2[2];
				if($msguse=="Y") {
					$delilmitprice.= "".number_format($deli_limit_exp2[0])." 이상";
				}
				break;
			} else {
				$delilmitprice="";
			}
		}
	}
	return $delilmitprice;
}

function is_blank($str) {
	$temp=str_replace("　","",$str);
	$temp=str_replace("\n","",$temp);
	$temp=strip_tags($temp);
	$temp=str_replace("&nbsp;","",$temp);
	$temp=str_replace(" ","",$temp);
	if(eregi("[^[:space:]]",$temp)) return 0;
	return 1;
}

function get_message($msg) {
	$pos = strpos($msg,"\\n");
	if ($pos<1) $pos = strlen($msg);
	$line = substr("************************************************************************",0,$pos+6);
	$temp = $line."\\n\\n";
	$temp.= $msg;
	$temp.= "\\n\\n";
	$temp.= $line;

	return $temp;
}

function error_msg($msg,$url="") {
	global $Dir;

	include ($Dir."error.php");

	exit;
}

///////////////////////////////////// vender 서비스 파일 체크 시작 /////////////////////////////////////

function setUseVender() {
	GLOBAL $_ShopInfo;
	$usevender=true;
	$vauthkey=getVenderUsed();
	
	if($vauthkey["OK"]!="OK") {
		$usevender=false;
	} else if($vauthkey["DOMAIN"]!=$_ShopInfo->getShopurl()) {
		$usevender=false;
	} else if($vauthkey["DATE"]!="*" && $vauthkey["DATE"]<date("Ymd")) {
		$usevender=false;
	}
	return $usevender;
}
/*
function setVenderUsed() {
	GLOBAL $_ShopInfo;
	$vender_used="";
	$vauthkey=getVenderUsed();
	if($vauthkey["OK"]=="OK") {
		if($vauthkey["DOMAIN"]!=$_ShopInfo->getShopurl()) {
			$vender_used="<a style=\"cursor:hand\" onclick=\"alert('입점기능 인증키의 도메인정보가 잘못되어 이용하실 수 없습니다.')\"><font color=red>사용불가</font></a>";
		} else if($vauthkey["DATE"]!="*" && $vauthkey["DATE"]<date("Ymd")) {
			$vender_used="<a style=\"cursor:hand\" onclick=\"alert('입점기능 사용기간이 만료되어 이용하실 수 없습니다.')\"><font color=red>사용기간 만료</font></a>";
		} else {
			if($vauthkey["DATE"]!="*") {
				$vender_used_date=substr($vauthkey["DATE"],0,4)."/".substr($vauthkey["DATE"],4,2)."/".substr($vauthkey["DATE"],6,2);
				if($vauthkey["COUNT"]!="*") {
					$vender_used="<a style=\"cursor:hand\" onclick=\"alert('입점기능 이용 기간은 ".$vender_used_date." 까지 이용 가능하며\\n\\n입점 가능 업체수는 ".$vauthkey["COUNT"]."개 업체 입니다.')\"><font class=\"font_orange4\">".$vender_used_date."</font></a>";
				} else {
					$vender_used="<a style=\"cursor:hand\" onclick=\"alert('입점기능 이용 기간은 ".$vender_used_date." 까지 이용 가능하며\\n\\n입점업체수는 무제한 입점 가능합니다.')\"><font class=\"font_orange4\">".$vender_used_date."</font></a>";
				}
			} else {
				if($vauthkey["COUNT"]!="*") {
					$vender_used="<a style=\"cursor:hand\" onclick=\"alert('기간 제한 없이 이용 가능합니다. (입점제한 : ".$vauthkey["COUNT"]."업체)')\"><font class=\"font_orange4\">".$vauthkey["COUNT"]."개 사용가능</font></a>";
				} else {
					$vender_used="<a style=\"cursor:hand\" onclick=\"alert('입점기능 및 미니샵을 이용하시는데 아무런 제약 없이 이용 가능합니다.')\"><font class=\"font_orange4\">무제한</font></a>";
				}
			}
		}
	} else {
		$vender_used="<a style=\"cursor:hand\" onclick=\"alert('입점기능 및 미니샵은 몰인몰(E-market) 버전에서만 사용하실 수 있습니다.')\"><font color=red>사용불가</font></a>";
	}
	return $vender_used;
}2012-03-16 mija
*/
function setVenderUsed() {
	GLOBAL $_ShopInfo;
	$vender_used="";
	$vauthkey=getVenderUsed();
	if($vauthkey["OK"]=="OK") {
		if($vauthkey["DOMAIN"]!=$_ShopInfo->getShopurl()) {
			$vender_used[0]="<a style=\"cursor:hand\" onclick=\"alert('입점기능 인증키의 도메인정보가 잘못되어 이용하실 수 없습니다.')\"><font color=red>사용불가</font></a>";
		} else if($vauthkey["DATE"]!="*" && $vauthkey["DATE"]<date("Ymd")) {
			$vender_used[0]="<a style=\"cursor:hand\" onclick=\"alert('입점기능 사용기간이 만료되어 이용하실 수 없습니다.')\"><font color=red>사용기간 만료</font></a>";
		} else {
			if($vauthkey["DATE"]!="*") {
				$vender_used_date=substr($vauthkey["DATE"],0,4)."/".substr($vauthkey["DATE"],4,2)."/".substr($vauthkey["DATE"],6,2);
				if($vauthkey["COUNT"]!="*") {
					$vender_used[0]="<a style=\"cursor:hand\" onclick=\"alert('입점기능 이용 기간은 ".$vender_used_date." 까지 이용 가능하며\\n\\n입점 가능 업체수는 ".$vauthkey["COUNT"]."개 업체 입니다.')\"><font class=\"font_orange4\">".$vender_used_date."</font></a>";
				} else {
					$vender_used[0]="<a style=\"cursor:hand\" onclick=\"alert('입점기능 이용 기간은 ".$vender_used_date." 까지 이용 가능하며\\n\\n입점업체수는 무제한 입점 가능합니다.')\"><font class=\"font_orange4\">".$vender_used_date."</font></a>";
				}
			} else {
				if($vauthkey["COUNT"]!="*") {
					$vender_used[0]="<a style=\"cursor:hand\" onclick=\"alert('기간 제한 없이 이용 가능합니다. (입점제한 : ".$vauthkey["COUNT"]."업체)')\"><font class=\"font_orange4\">".$vauthkey["COUNT"]."개 사용가능</font></a>";
				} else {
					$vender_used[0]="<a style=\"cursor:hand\" onclick=\"alert('입점기능 및 미니샵을 이용하시는데 아무런 제약 없이 이용 가능합니다.')\"><font class=\"font_orange4\">무제한</font></a>";
				}
			}
		}
	$vender_used[1]="<font style=\"color:#fdfdfd;font-size:11px; letter-spacing:-2px; font-family:돋움;\">사용중</font>";

	} else {
		$vender_used[0]="<a style=\"cursor:hand\" onclick=\"alert('입점기능 및 미니샵은 몰인몰(E-market) 버전에서만 사용하실 수 있습니다.')\"><font color=red>사용불가</font></a>";
		$vender_used[1]="<a href=\"http://www.getmall.co.kr/board/board.php?board=license\" target=\"_blank\"><img src=\"images/main_icon_order.gif\"></a>";
	}
	return $vender_used;
}

$_vscriptname=substr(str_replace(RootPath,"",getenv("SCRIPT_NAME")),1);
if(strlen($_vscriptname)>0) {
	switch($_vscriptname) {
		// 관리자모드 admin 벤더 관련 일반페이지
		case AdminDir."vender_calendar.php":
		case AdminDir."vender_counsel.php":
		case AdminDir."vender_mailsend.php":
		case AdminDir."vender_notice.php":
		case AdminDir."vender_orderadjust.php":
		case AdminDir."vender_orderlist.php":
		case AdminDir."vender_prdtallsoldout.php":
		case AdminDir."vender_prdtallupdate.php":
		case AdminDir."vender_prdtlist.php":
		case AdminDir."vender_smssend.php":
		case AdminDir."vender_infomodify.php":
		case AdminDir."vender_management.php":
		case AdminDir."vender_new.php":
			$vauthkey=getVenderUsed();
			if($vauthkey["OK"]!="OK") {
				//입점기능 사용 불가능
				echo "<html><head></head><body onload=\"alert('입점기능 및 미니샵은 몰인몰(E-market) 버전에서만 사용하실 수 있습니다.');history.go(-1);\"></body></html>";exit;
			} else if($vauthkey["DOMAIN"]!=$_ShopInfo->getShopurl()) {
				//도메인 정보가 올바르지 않음
				echo "<html><head></head><body onload=\"alert('입점기능 인증키의 도메인정보가 잘못되어 이용하실 수 없습니다.');history.go(-1);\"></body></html>";exit;
			} else if($vauthkey["DATE"]!="*" && $vauthkey["DATE"]<date("Ymd")) {
				//사용기간이 만료되었습니다.
				echo "<html><head></head><body onload=\"alert('입점기능 사용기간이 만료되어 이용하실 수 없습니다.');history.go(-1);\"></body></html>";exit;
			} else if($_vscriptname!=AdminDir."vender_infomodify.php" && $_vscriptname!=AdminDir."vender_management.php" && $vauthkey["COUNT"]!="*") {
				if($_vscriptname==AdminDir."vender_new.php" && (strlen($vauthkey["COUNT"])==0 || $vauthkey["COUNT"]==0)) {
					echo "<html><head></head><body onload=\"alert('입점업체 신규등록을 하실 수 없습니다.');history.go(-1);\"></body></html>";exit;
				}
				/*
				//벤더개수 제한기능삭제함
				$vendercount_result=@mysql_query("SELECT COUNT(*) as cnt FROM tblvenderinfo ",@get_db_conn());
				$vendercount_row=@mysql_fetch_object($vendercount_result);
				@mysql_free_result($vendercount_result);
				if($_vscriptname==AdminDir."vender_new.php" && $vendercount_row->cnt>=$vauthkey["COUNT"]) {
					echo "<html><head></head><body onload=\"alert('본 쇼핑몰은 ".$vauthkey["COUNT"]."업체 까지 서비스 가능합니다.\\n\\n입점업체 정리 후 이용하시기 바랍니다.');location='vender_management.php'\"></body></html>";exit;
				} else if ($vendercount_row->cnt>$vauthkey["COUNT"]) {
					echo "<html><head></head><body onload=\"alert('본 쇼핑몰은 ".$vauthkey["COUNT"]."업체 까지 서비스 가능합니다.\\n\\n입점업체 정리 후 이용하시기 바랍니다.');location='vender_management.php'\"></body></html>";exit;
				}
				*/
			}
			break;
		// 관리자모드 admin 벤더 관련 새창페이지
		case AdminDir."vender_branddup.php":
		case AdminDir."vender_calendar.detail.php":
		case AdminDir."vender_counsel_pop.php":
		case AdminDir."vender_detailpop.php":
		case AdminDir."vender_findpop.php":
		case AdminDir."vender_iddup.php":
		case AdminDir."vender_infopop.php":
		case AdminDir."vender_orderdetail.php":
			$vauthkey=getVenderUsed();
			if($vauthkey["OK"]!="OK") {
				//입점기능 사용 불가능
				echo "<html><head></head><body onload=\"alert('입점기능 및 미니샵은 몰인몰(E-market) 버전에서만 사용하실 수 있습니다.');window.close();\"></body></html>";exit;
			} else if($vauthkey["DOMAIN"]!=$_ShopInfo->getShopurl()) {
				//도메인 정보가 올바르지 않음
				echo "<html><head></head><body onload=\"alert('입점기능 인증키의 도메인정보가 잘못되어 이용하실 수 없습니다.');window.close();\"></body></html>";exit;
			} else if($vauthkey["DATE"]!="*" && $vauthkey["DATE"]<date("Ymd")) {
				//사용기간이 만료되었습니다.
				echo "<html><head></head><body onload=\"alert('입점기능 사용기간이 만료되어 이용하실 수 없습니다.');window.close();\"></body></html>";exit;
			}
			break;
		// 사용자모드 front 벤더 관련 페이지
		case "minishop.php":
		case "minishop":
		case FrontDir."minishop.php":
		case FrontDir."minishop.notice.php":
		case FrontDir."minishop.productlist.php":
		case FrontDir."minishop.productsearch.php":
		case FrontDir."minishop.regist.pop.php":
			include_once($Dir."lib/shopdata.php");
			$vauthkey=getVenderUsed();
			if($vauthkey["OK"]!="OK" || $vauthkey["DOMAIN"]!=$_ShopInfo->getShopurl()) {
				if($_vscriptname==FrontDir."minishop.regist.pop.php") {
					echo "<html></head><body onload=\"window.close()\"></body></html>";exit;
				} else {
					header("Location:".$Dir);exit;
				}
			}
			break;
		// 사용자모드 vender 벤더 관련 페이지
		case VenderDir."login.php":
		case VenderDir."loginproc.php":
			include_once($Dir."lib/shopdata2.php");
			$vauthkey=getVenderUsed();
			if($vauthkey["OK"]!="OK") {
				//입점기능 사용 불가능
				echo "<html><head></head><body onload=\"alert('본 쇼핑몰에서는 입점기능을 사용하실 수 없습니다.\\n\\n쇼핑몰에 문의하시기 바랍니다.');location.href='".$Dir."';\"></body></html>";exit;
			} else if($vauthkey["DOMAIN"]!=$_ShopInfo->getShopurl()) {
				//도메인 정보가 올바르지 않음
				echo "<html><head></head><body onload=\"alert('본 쇼핑몰에서는 입점기능을 사용하실 수 없습니다.\\n\\n쇼핑몰에 문의하시기 바랍니다.');location.href='".$Dir."';\"></body></html>";exit;
			} else if($vauthkey["DATE"]!="*" && $vauthkey["DATE"]<date("Ymd")) {
				//사용기간이 만료되었습니다.
				echo "<html><head></head><body onload=\"alert('입점기능 사용기간이 만료되어 이용하실 수 없습니다.\\n\\n쇼핑몰에 문의하시기 바랍니다.');location.href='".$Dir."';\"></body></html>";exit;
			}
			break;
		default :
			break;
	}
}

function setVenderCountUpdate($prdt_allcnt, $prdt_cnt, $arrvender_yy) {
	$sql ="UPDATE tblvenderstorecount SET prdt_allcnt='".$prdt_allcnt."', prdt_cnt='".$prdt_cnt."' ";
	$sql.="WHERE vender='".$arrvender_yy."' ";
	@mysql_query($sql,get_db_conn());
}

function setVenderCountUpdateMin($vender, $vdisp) {
	$sql ="UPDATE tblvenderstorecount SET ";
	if($vdisp=="Y") {
		$sql.="prdt_cnt=prdt_cnt-1, ";
	}
	$sql.="prdt_allcnt=prdt_allcnt-1 ";
	$sql.="WHERE vender='".$vender."' ";
	@mysql_query($sql,get_db_conn());
}

function setVenderCountUpdateRan($vender, $display) {
	$sql ="UPDATE tblvenderstorecount SET ";
	if($display=="Y") {
		$sql.="prdt_cnt=prdt_cnt+1 ";
	} else {
		$sql.="prdt_cnt=prdt_cnt-1 ";
	}
	$sql.="WHERE vender='".$vender."' ";
	@mysql_query($sql,get_db_conn());
}

function setVenderThemeDelete($prcodelist, $arrvender_yy) {
	$sql = "DELETE FROM tblvenderthemeproduct WHERE vender='".$arrvender_yy."' ";
	$sql.= "AND productcode IN ('".$prcodelist."') ";
	@mysql_query($sql,get_db_conn());
}

function setVenderThemeDeleteNor($prcode, $vender) {
	$sql = "DELETE FROM tblvenderthemeproduct WHERE vender='".$vender."' ";
	$sql.= "AND productcode='".$prcode."' ";
	@mysql_query($sql,get_db_conn());
}

function setVenderThemeDeleteLike($likecode, $arrvender_yy) {
	$sql = "DELETE FROM tblvenderthemeproduct WHERE vender='".$arrvender_yy."' ";
	$sql.= "AND productcode LIKE '".$likecode."%' ";
	@mysql_query($sql,get_db_conn());
}

function setVenderThemeSpecialUpdate($vender, $prarr_IN_kk, $prarr_OUT_kk) {
	$sql = "UPDATE tblvenderthemeproduct SET productcode='".$prarr_IN_kk."' ";
	$sql.= "WHERE vender='".$vender."' AND productcode='".$prarr_OUT_kk."' ";
	@mysql_query($sql,get_db_conn());

	$sql = "UPDATE tblvenderspecialcode SET ";
	$sql.= "special_list = replace(special_list,'".$prarr_OUT_kk."','".$prarr_IN_kk."') ";
	$sql.= "WHERE vender='".$vender."' ";
	@mysql_query($sql,get_db_conn());

	$sql = "UPDATE tblvenderspecialmain SET ";
	$sql.= "special_list = replace(special_list,'".$prarr_OUT_kk."','".$prarr_IN_kk."') ";
	$sql.= "WHERE vender='".$vender."' ";
	@mysql_query($sql,get_db_conn());
}

function setVenderDesignDelete($str_codeA, $arrvender_yy) {
	$sql = "DELETE FROM tblvendercodedesign WHERE vender='".$arrvender_yy."' ";
	$sql.= "AND code IN ('".$str_codeA."') AND tgbn='10' ";
	@mysql_query($sql,get_db_conn());
}

function setVenderDesignDeleteNor($tmpcodeA, $vender) {
	$sql = "DELETE FROM tblvendercodedesign WHERE vender='".$vender."' ";
	$sql.= "AND code='".$tmpcodeA."' AND tgbn='10' ";
	mysql_query($sql,get_db_conn());
}

function setVenderDesignInsert($vender, $prarr_IN_kk) {
	$sql = "INSERT tblvendercodedesign SET ";
	$sql.= "vender		= '".$vender."', ";
	$sql.= "code		= '".substr($prarr_IN_kk,0,3)."', ";
	$sql.= "tgbn		= '10', ";
	$sql.= "hot_used	= '1', ";
	$sql.= "hot_dispseq	= '118' ";
	@mysql_query($sql,get_db_conn());
}
////////////////////////////////////////////////////////////////////////////////////////////////////////

//////////////////////////////// 결제창 띄울 경우 장바구니 기타사항 복구 ///////////////////////////////
function basket_restore() {
	global $_ShopInfo;
	$oldtempkey = $_ShopInfo->getOldtempkey();
	$curtempkey = $_ShopInfo->gettempkey();
	if(strlen($oldtempkey)>0 && strlen($curtempkey)>0) {
		$sql="SELECT COUNT(*) AS oldbasketcount FROM tblbasket WHERE tempkey='".$oldtempkey."' ";
		$result=mysql_query($sql,get_db_conn());
		if($row=mysql_fetch_object($result)) {
			$oldbasketcount=$row->oldbasketcount;
		}
		mysql_free_result($result);

		if($oldbasketcount>0) {
			$sql = "SELECT * FROM tblorderinfotemp WHERE tempkey='".$oldtempkey."' ";
			$result=mysql_query($sql,get_db_conn());
			$data=mysql_fetch_object($result);
			mysql_free_result($result);
			if($data && strlen($data->del_gbn)==0 && substr($data->ordercode,0,12)<=date("YmdHis")) {
				$sql = "SELECT a.productcode,a.productname,a.opt1_name,a.opt2_name,a.quantity,a.package_idx,a.assemble_idx,a.assemble_info, ";
				$sql.= "b.option_quantity,b.option1,b.option2 FROM tblorderproducttemp a, tblproduct b ";
				$sql.= "WHERE a.productcode=b.productcode AND a.ordercode='".$data->ordercode."' ";
				$result=mysql_query($sql,get_db_conn());
				$message="";
				while ($row=mysql_fetch_object($result)) {
					$tmpoptq="";
					if(strlen($artmpoptq[$row->productcode])>0)
						$optq=$artmpoptq[$row->productcode];
					else
						$optq=$row->option_quantity;

					if(strlen($optq)>51 && substr($row->opt1_name,0,5)!="[OPTG"){
						$tmpoptname1=explode(" : ",$row->opt1_name);
						$tmpoptname2=explode(" : ",$row->opt2_name);
						$tmpoption1=explode(",",$row->option1);
						$tmpoption2=explode(",",$row->option2);
						$cnt=1;
						$maxoptq = count($tmpoption1);
						while ($tmpoption1[$cnt]!=$tmpoptname1[1] && $cnt<$maxoptq) {
							$cnt++;
						}
						$opt_no1=$cnt;
						$cnt=1;
						$maxoptq2 = count($tmpoption2);
						while ($tmpoption2[$cnt]!=$tmpoptname2[1] && $cnt<$maxoptq2) {
							$cnt++;
						}
						$opt_no2=$cnt;
						$optioncnt = explode(",",substr($optq,1));
						if($optioncnt[($opt_no2-1)*10+($opt_no1-1)]!="") $optioncnt[($opt_no2-1)*10+($opt_no1-1)]+=$row->quantity;
						for($j=0;$j<5;$j++){
							for($i=0;$i<10;$i++){
								$tmpoptq.=",".$optioncnt[$j*10+$i];
							}
						}
						if(strlen($tmpoptq)>0 && $tmpoptq.","!=$optq){
							$artmpoptq[$row->productcode]=$tmpoptq;
							$tmpoptq=",option_quantity='".$tmpoptq.",'";
						}else{
							$tmpoptq="";
							$message .="[".$row->productname." - ".$row->opt1_name.$row->opt2_name."]\\n";
						}
					}
					$sql = "UPDATE tblproduct SET quantity=quantity+".$row->quantity.$tmpoptq." ";
					$sql.= "WHERE productcode='".$row->productcode."'";
					mysql_query($sql,get_db_conn());

					if(str_replace("","",str_replace(":","",str_replace("=","",$row->assemble_info)))) {
						$assemble_infoall_exp = explode("=",$row->assemble_info);

						if($row->package_idx>0 && strlen(str_replace("","",str_replace(":","",$assemble_infoall_exp[0])))>0) {
							$package_info_exp = explode(":",$assemble_infoall_exp[0]);
							if(strlen($package_info_exp[0])>0) {
								$package_productcode_exp = explode("",$package_info_exp[0]);
								for($k=0; $k<count($package_productcode_exp); $k++) {
									$sql2 = "UPDATE tblproduct SET ";
									$sql2.= "quantity		= quantity+".$row->quantity." ";
									$sql2.= "WHERE productcode='".$package_productcode_exp[$k]."' ";
									mysql_query($sql2,get_db_conn());
								}
							}
						}

						if($row->assemble_idx>0 && strlen(str_replace("","",str_replace(":","",$assemble_infoall_exp[1])))>0) {
							$assemble_info_exp = explode(":",$assemble_infoall_exp[1]);
							if(strlen($assemble_info_exp[0])>0) {
								$assemble_productcode_exp = explode("",$assemble_info_exp[0]);
								for($k=0; $k<count($assemble_productcode_exp); $k++) {
									$sql2 = "UPDATE tblproduct SET ";
									$sql2.= "quantity		= quantity+".$row->quantity." ";
									$sql2.= "WHERE productcode='".$assemble_productcode_exp[$k]."' ";
									mysql_query($sql2,get_db_conn());
								}
							}
						}
					}
				}
				mysql_free_result($result);

				$sql = "SELECT productcode FROM tblorderproducttemp ";
				$sql.= "WHERE ordercode='".$data->ordercode."' AND productcode LIKE 'COU%' ";
				$result=mysql_query($sql,get_db_conn());
				$rowcou=mysql_fetch_object($result);
				mysql_free_result($result);
				if($rowcou) {
					$coupon_code=substr($rowcou->productcode,3,-1);
					mysql_query("UPDATE tblcouponissue SET used='N' WHERE id='".$data->id."' AND coupon_code='".$coupon_code."'",get_db_conn());
				}
				if($data->reserve>0) {
					mysql_query("UPDATE tblmember SET reserve=reserve+".$data->reserve." WHERE id='".$data->id."'",get_db_conn());
				}
				mysql_query("UPDATE tblorderinfotemp SET del_gbn='R' WHERE ordercode='".$data->ordercode."'",get_db_conn());
			}
			mysql_query("UPDATE tblbasket SET tempkey='".$curtempkey."' WHERE tempkey='".$oldtempkey."'",get_db_conn()); //장바구니 복원
		}
		$_ShopInfo->setOldtempkey("");
		$_ShopInfo->Save();
	}
}
if(strlen($_vscriptname)>0) {
	switch($_vscriptname) {
		case FrontDir."basket.php":
		case FrontDir."mypage.php":
		case FrontDir."mypage_coupon.php":
		case FrontDir."mypage_reserve.php":
			basket_restore();
			break;
		default :
			break;
	}
}
////////////////////////////////////////////////////////////////////////////////////////////////////////

//// #### SNS 기능추가관련 ####/////////////////////////////////////////////////////////////////////////
//file_get_contents 함수대용
function curl($url)
{
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$g = curl_exec($ch);
	curl_close($ch);
	return $g;
}
//php 5.2 이상은 json_decode 함수 지원
if ( !function_exists('json_decode') ){
	function json_decode($json)
	{
		$comment = false;
		$out = '$x=';

		for ($i=0; $i<strlen($json); $i++)
		{
			if (!$comment)
			{
				if (($json[$i] == '{') || ($json[$i] == '['))       $out .= ' array(';
				else if (($json[$i] == '}') || ($json[$i] == ']'))   $out .= ')';
				else if ($json[$i] == ':')    $out .= '=>';
				else                         $out .= $json[$i];
			}
			else $out .= $json[$i];
			if ($json[$i] == '"' && $json[($i-1)]!="\\")    $comment = !$comment;
		}
		eval($out . ';');
		return $x;
	}
}


if(!function_exists('json_encode'))
{
    function json_encode($a=false)
    {
        // Some basic debugging to ensure we have something returned
        if (is_null($a)) return 'null';
        if ($a === false) return 'false';
        if ($a === true) return 'true';
        if (is_scalar($a))
        {
            if (is_float($a))
            {
                // Always use '.' for floats.
                return floatval(str_replace(',', '.', strval($a)));
            }
            if (is_string($a))
            {
                static $jsonReplaces = array(array('\\', '/', "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
                return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
            }
            else
                return $a;
        }
        $isList = true;
        for ($i = 0, reset($a); true; $i++) {
            if (key($a) !== $i)
            {
                $isList = false;
                break;
            }
        }
        $result = array();
        if ($isList)
        {
            foreach ($a as $v) $result[] = json_encode($v);
            return '[' . join(',', $result) . ']';
        }
        else
        {
            foreach ($a as $k => $v) $result[] = json_encode($k).':'.json_encode($v);
            return '{' . join(',', $result) . '}';
        }
    }
}

//sns 앱 키
function snsAppkey(){
	$sql = "SELECT * FROM tblshopsnsinfo WHERE state ='Y' ";
	$result=@mysql_query($sql,get_db_conn());
	while($data=@mysql_fetch_object($result)) {
		switch ($data->type){
			case "f":
				$data->icon_img	= ($data->icon_img)? "/data/shopimages/etc/".$data->icon_img:"/images/design/icon_facebook_on.gif";
				define("FACEBOOK_ID", "{$data->appid}");
				define("FACEBOOK_SECRET", "{$data->secret}");
				define("FACEBOOK_IMG", "{$data->icon_img}");
				break;
			case "t":
				$data->icon_img	= ($data->icon_img)? "/data/shopimages/etc/".$data->icon_img:"/images/design/icon_twitter_on.gif";
				define("TWITTER_ID", "{$data->appid}");
				define("TWITTER_SECRET", "{$data->secret}");
				define("TWITTER_IMG", "{$data->icon_img}");
				break;
			case "m":
				$data->icon_img	= ($data->icon_img)? "/data/shopimages/etc/".$data->icon_img:"/images/design/icon_me2day_on.gif";
				define("ME2DAY_ID", "{$data->appid}");
				define("ME2DAY_IMG", "{$data->icon_img}");
				break;
			default:
				break;
		}
	}
}
if(@file($Dir.DataDir."config.php")) {
	snsAppkey();
}

//sns 적립금관련
function getReserveConversionSNS($reserve_org,$reserve,$reservetype,$sellprice,$reservshow) {
	global $_ShopInfo, $_data;
	$arSnsType = explode("", $_data->sns_reserve_type);
	if($arSnsType[0] == "A"){
		$Rtnreserve=getReserveConversion($_data->sns_memreserve,$arSnsType[2],$sellprice,$reservshow);
	}else if($arSnsType[0] == "B"){
		$Rtnreserve=getReserveConversion($reserve,$reservetype,$sellprice,$reservshow);
	}
	$Rtnreserve = ($Rtnreserve > $reserve_org)? $Rtnreserve:$reserve_org;
	return $Rtnreserve;
}


//2012-03-14 MIJA
#	mysql query
function sql_query($sql) {
	$result = mysql_query($sql) or die("<span style='font-weight:bold; color:#f00;'>".$sql."</span><p><span style='font-weight:bold; color:#00f;'>".mysql_error()."<p>MySQL DateBase Error Num. ".mysql_errno()."</span>");
	return $result;
}

#	mysql_fetch_object
function sql_fetch_obj($tbl, $where="", $field="*") {
$myFetch	=	sql_query("select ".$field." from ".$tbl." ".$where."");
if(mysql_num_rows($myFetch) > 0)			return @mysql_fetch_object($myFetch);
else										return 0;
}

// 전역 함수
if($install_state !== true){
	@include_once($Dir."lib/ext/global.func.php");
}


?>