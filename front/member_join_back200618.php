<?
/* ����Ȯ�� ���� */
/************************************************************************************/
/* - ����� ��ȣȭ�� ���� IV ���� Random�ϰ� ������.(�ݵ�� �ʿ���!!)						*/
/* - input�ڽ� reqNum�� value����  echo $CurTime.$RandNo  ���·� ����					*/
/************************************************************************************/
$CurTime = date(YmdHis);  //���� �ð� ���ϱ�

//6�ڸ� ������ ����
$RandNo = rand(100000, 999999);

$srvid = "SRNN001";
$srvNo = "001001";
$reqNum = $CurTime.$RandNo;
$certDate = $CurTime;
$certGb = "H";
$addVar = "";
$retUrl = "32http://beta.jamkan.com/Siren24_v2/pcc_V3_popup_seed_v2.php";

$exVar = "0000000000000000"; // Ȯ���ӽ� �ʵ��Դϴ�. �������� ������..

//02. ��ȣȭ �Ķ���� ����
$reqInfo = $srvid . "^" . $srvNo . "^" . $reqNum . "^" . $certDate . "^" . $certGb . "^" . $addVar . "^" . $exVar;

$key = "3ECA075F0D94C1E583DC5A0968FD6F97";

syslog(LOG_NOTICE, $key);
//03. ����Ȯ�� ��û���� 1����ȣȭ
//2014.02.07 KISA �ǰ����
//�� ���� ��, �ҹ� �õ� ������ ���Ͽ� �Ʒ� ���Ͽ� �ش��ϴ� ���ڿ��� ���	
if(preg_match('~[^0-9a-zA-Z+/=^]~', $reqInfo, $matches)){
	echo "�Է� �� Ȯ���� �ʿ��մϴ�.(req)"; exit;
}

//��ȣȭ��� ��ġ�� ������ SciSecuX ������ �ִ� ������ ��θ� �������ּ���.
$enc_reqInfo = exec("/home/rental/public_html/Siren24_v2/SciSecuX SEED 1 2 $reqInfo $key");	//(ex: /home/name1/php_v2/SciSecuX)

//04. ��û���� ������������ ����
$hmac_str = exec("/home/rental/public_html/Siren24_v2/SciSecuX HMAC 1 2 $enc_reqInfo $key");

//05. ��û���� 2����ȣȭ
//������ ���� ��Ģ : "��û���� 1�� ��ȣȭ^������������^�Ϻ�ȭ Ȯ�� ����"
$enc_reqInfo = $enc_reqInfo. "^" .$hmac_str. "^" ."0000000000000000";
$enc_reqInfo = exec("/home/rental/public_html/Siren24_v2/SciSecuX SEED 1 2 $enc_reqInfo $key");

$enc_reqInfo = $enc_reqInfo. "^" .$srvid. "^" ."00000000";
$enc_reqInfo = exec("/home/rental/public_html/Siren24_v2/SciSecuX SEED 1 1 $enc_reqInfo $key");
/* ����Ȯ�� ���� */



$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/ext/func.php");
include_once($Dir."lib/shopdata.php");

//SNS �α���
@include_once($Dir."lib/sns_init.php");


//����Ȯ�� ��� ����
_pr($_POST);



if(strlen($_ShopInfo->getMemid())>0) {
	header("Location:mypage_usermodify.php");
	exit;
}

// ���̵� �� ���� üũ ����.
$idChk = 0;
$mailChk = 0;
$loginType = ($_GET['loginType']) ? $_GET['loginType'] : $_POST['loginType'];
$type = $_POST["type"];

// SNS API ���� ����
if ($loginType == "naver") {
	// ���̹� �α��� API ���� ���� ���� ���.
	$result = $naver->getUserProfile();
	$result = json_decode($result);
	$info = $result->response;

	//_pr($info);
}


// 160314 sns �α������� ȸ�������ϴ� ���
if(!_empty($loginType)){
	if($loginType == "naver"){
		$id = $naver->getSocialId().$info->id;
		$name=trim(iconv("UTF-8","EUC-KR",$info->name));
		$email=trim($info->email);
		if($info->gender=='M'){
			$gender=1;
			$genderChecked1="checked";
		}else{
			$gender=2;
			$genderChecked2="checked";
		}
	}
}else{
	$id=trim($_POST["id"]);
	$name=trim($_POST["name"]);
	$email=trim($_POST["email"]);
	$gender=trim($_POST["gender"]);
}

$ip = getenv("REMOTE_ADDR");

$reserve_join=(int)$_data->reserve_join;
$recom_ok=$_data->recom_ok;
$recom_url_ok=$_data->recom_url_ok;
$armemreserve=explode("", $_data->recom_memreserve_type);
$recom_memreserve=(int)$_data->recom_memreserve;
$recom_addreserve=(int)$_data->recom_addreserve;
$recom_limit=$_data->recom_limit;
if(strlen($recom_limit)==0) $recom_limit=9999999;
$group_code=$_data->group_code;
$member_addform=$_data->member_addform;

unset($adultauthid);
unset($adultauthpw);
if(strlen($_data->adultauth)>0) {
	$tempadult=explode("=",$_data->adultauth);
	if($tempadult[0]=="Y") {
		$adultauthid=$tempadult[1];
		$adultauthpw=$tempadult[2];
	}
}

$extconf = array();
if(false !== $eres = mysql_query("select * from extra_conf where type='memconf'",get_db_conn())){
	if(mysql_num_rows($eres)){
		while($erow = mysql_fetch_assoc($eres)){
			$extconf[$erow['name']] = $erow['value'];
		}
	}
}

unset($straddform);
unset($scriptform);
unset($stretc);
if(strlen($member_addform)>0) {
	$straddform.="<tr>";
	$straddform.="	<td height=\"10\" colspan=\"4\"></td>";
	$straddform.="</tr>";
	$straddform.="<tr height=\"23\" bgcolor=\"#585858\">\n";
	$straddform.="	<td colspan=4 align=center style=\"font-size:11px;\"><font color=\"FFFFFF\" ><b>�߰������� �Է��ϼ���.</b></font></td>\n";
	$straddform.="</tr>\n";
	$straddform.="<tr>";
	$straddform.="	<td height=\"5\" colspan=\"4\"></td>";
	$straddform.="</tr>";

	$fieldarray=explode("=",$member_addform);
	$num=sizeof($fieldarray)/3;
	for($i=0;$i<$num;$i++) {
		if (substr($fieldarray[$i*3],-1,1)=="^") {
			$fieldarray[$i*3]="<font color=\"#F02800\"><b>��</b></font><font color=\"#000000\"><b>".substr($fieldarray[$i*3],0,strlen($fieldarray[$i*3])-1)."</b></font>";
			$field_check[$i]="OK";
		} else {
			$fieldarray[$i*3]="<font color=\"#000000\"><b>".$fieldarray[$i*3]."</b></font>";
		}

		$stretc.="<tr>\n";
		$stretc.="	<td align=\"left\"  style=\"padding-left:14px\">".$fieldarray[$i*3]."</td>\n";

		$etcfield[$i]="<input type=text name=\"etc[".$i."]\" value=\"".$etc[$i]."\" size=\"".$fieldarray[$i*3+1]."\" maxlength=\"".$fieldarray[$i*3+2]."\" id=\"etc_".$i."\" class=\"input\" style=\"BACKGROUND-COLOR:#F7F7F7;\">";

		$stretc.="	<td colspan=\"3\">".$etcfield[$i]."</td>\n";
		$stretc.="</tr>\n";
		$stretc.="<tr>\n";
		$stretc.="	<td height=\"10\" colspan=\"4\" background=\"".$Dir."images/common/mbjoin/memberjoin_p_skin_line.gif\"></td>";
		$stretc.="</tr>\n";

		if ($field_check[$i]=="OK") {
			$scriptform.="try {\n";
			$scriptform.="	if (document.getElementById('etc_".$i."').value==0) {\n";
			$scriptform.="		alert('�ʼ��Է»����� �Է��ϼ���.');\n";
			$scriptform.="		document.getElementById('etc_".$i."').focus();\n";
			$scriptform.="		return;\n";
			$scriptform.="	}\n";
			$scriptform.="} catch (e) {}\n";
		}
	}
	$straddform.=$stretc;
}

if($type=="insert") {
	$history="-1";
	$sslchecktype="";
	if($_POST["ssltype"]=="ssl" && strlen($_POST["sessid"])==64) {
		$sslchecktype="ssl";
		$history="-2";
	}
	if($sslchecktype=="ssl") {
		$secure_data=getSecureKeyData($_POST["sessid"]);
		if(!is_array($secure_data)) {
			echo "<html><head><title></title></head><body onload=\"alert('�������� ������ �߸��Ǿ����ϴ�.');history.go(".$history.");\"></body></html>";exit;
		}
		foreach($secure_data as $key=>$val) {
			${$key}=$val;
		}
	} else {
		$passwd1=$_POST["passwd1"];
		$passwd2=$_POST["passwd2"];
		$resno1=trim($_POST["resno1"]);
		$resno2=trim($_POST["resno2"]);
		$news_mail_yn=$_POST["news_mail_yn"];
		$news_sms_yn=$_POST["news_sms_yn"];
		$home_tel=trim($_POST["home_tel"]);
		$home_post1=trim($_POST["home_post1"]);
		//$home_post2=trim($_POST["home_post2"]);
		$home_addr1=trim($_POST["home_addr1"]);
		$home_addr2=trim($_POST["home_addr2"]);
		$mobile=trim($_POST["mobile"]);
		$office_post1=trim($_POST["office_post1"]);
		//$office_post2=trim($_POST["office_post2"]);
		$office_addr1=trim($_POST["office_addr1"]);
		$office_addr2=trim($_POST["office_addr2"]);
		$rec_id=trim($_POST["rec_id"]);
		$etc=$_POST["etc"];

		$birth=trim($_POST["birth"]);
		$mcode=trim($_POST["mcode"]);

		$vDiscrNo=trim($_POST["vDiscrNo"]);
		$uniqNo=trim($_POST["uniqNo"]);
		$scitype=trim($_POST["scitype"]);
	}


	// 160314 sns �α������� ȸ�������ϴ� ���
	if (!_empty($loginType)) {
		if ($loginType == "naver") {
			$id = $naver->getSocialId().$info->id;
		}
	}

	$onload="";

	$resno=$resno1.$resno2;

	for($i=0;$i<10;$i++) {
		if(strpos($etc[$i],"=")) {
			$onload="�߰������� �Է��� �� ���� ���ڰ� ���ԵǾ����ϴ�.";
			break;
		}
		if($i!=0) {
			$etcdata=$etcdata."=";
		}
		$etcdata=$etcdata.$etc[$i];
	}

	if($recom_ok=="Y" && strlen($rec_id)>0) {
		$sql = "SELECT COUNT(*) as cnt FROM tblmember WHERE id='".trim($rec_id)."' AND member_out!='Y' ";
		$rec_result = mysql_query($sql,get_db_conn());
		$rec_row = mysql_fetch_object($rec_result);
		$rec_num = $rec_row->cnt;
		mysql_free_result($rec_result);

		$rec_cnt=0;
		$sql = "SELECT rec_cnt FROM tblrecommendmanager WHERE rec_id='".trim($rec_id)."'";
		$rec_result = mysql_query($sql,get_db_conn());
		if($rec_row = mysql_fetch_object($rec_result)) {
			$rec_cnt = (int)$rec_row->rec_cnt;
		}
		mysql_free_result($rec_result);
	}
	//echo $name;exit;

	//if(_empty($loginType)){ //sns �α����� �ƴҰ��
		if(strlen($onload)>0) {
			if(_empty($loginType)){ //sns �α����� �ƴҰ��
				//���â ������ ��������
				if($_data->resno_type!="N" && strlen(trim($resno))!=13) {
					$onload="�ֹε�Ϲ�ȣ �Է��� �߸��Ǿ����ϴ�.";
				} else if($_data->resno_type!="N" && !chkResNo($resno)) {
					$onload="�߸��� �ֹε�Ϲ�ȣ �Դϴ�.\\n\\nȮ�� �� �ٽ� �Է��Ͻñ� �ٶ��ϴ�.";
				} else if($_data->resno_type!="N" && getAgeResno($resno)<14) {
					$onload="�� 14�� �̸��� �Ƶ��� ȸ�����Խ� �����븮���� ���ǰ� �־�� �մϴ�!\\n\\n ��� ���θ��� �����ֽñ� �ٶ��ϴ�.";
				} else if($_data->resno_type!="N" && $_data->adult_type=="Y" && getAgeResno($resno)<19) {
					$onload="�� ���θ��� ���θ� �̿밡���ϹǷ� ȸ�������� �Ͻ� �� �����ϴ�.";
				} else if(strlen(trim($id))==0) {
					$onload="���̵� �Է��� �߸��Ǿ����ϴ�.";
				} else if(!IsAlphaNumeric($id)) {
					$onload="���̵�� ����,���ڸ� �����Ͽ� 4~12�� �̳��� �Է��ϼž� �մϴ�.";
				} else if(!eregi("(^[0-9a-zA-Z]{4,12}$)",$id)) {
					$onload="���̵�� ����,���ڸ� �����Ͽ� 4~12�� �̳��� �Է��ϼž� �մϴ�.";
				} else if(strlen(trim($name))==0) {
					$onload="�̸� �Է��� �߸��Ǿ����ϴ�.";
				} else if(strlen(trim($email))==0) {
					$onload="�̸����� �Է��ϼ���.";
				} else if(!ismail($email)) {
					$onload="�̸��� �Է��� �߸��Ǿ����ϴ�.";
				} else if(strlen(trim($home_tel))==0) {
					$onload="����ȭ�� �Է��ϼ���.";
				} else if(strlen(trim($mobile))==0) {
					$onload="�޴���ȭ�� �Է��ϼ���.";
				} else if($rec_num==0 && strlen($rec_id)!=0) {
					$onload="��õ�� ID �Է��� �߸��Ǿ����ϴ�.";
				}
			}
		}

		//} else {
			if ($_data->resno_type!="N" && strlen($adultauthid)>0 && strlen($name)>0 && strlen($resno1)>0 && strlen($resno2)>0) {
				include($Dir."lib/name_check.php");
				$onload=getNameCheck($name, $resno1, $resno2, $adultauthid, $adultauthpw);
			}
			if(!$onload) {
				//if (_empty($loginType)) {
					if($_data->resno_type!="N") {
						$rsql = "SELECT id FROM tblmember WHERE resno='".$resno."'";
						$result2 = mysql_query($rsql,get_db_conn());
						$num = mysql_num_rows($result2);
						mysql_free_result($result2);
						if ($num>0) {
							$onload="�ֹι�ȣ�� �ߺ��Ǿ����ϴ�.";
						}
					}
					if(!$onload) {
						$sql = "SELECT id FROM tblmember WHERE id='".$id."' ";
						$result=mysql_query($sql,get_db_conn());
						if($row=mysql_fetch_object($result)) {
							$onload="ID�� �ߺ��Ǿ����ϴ�.\\n\\n�ٸ� ���̵� ����Ͻñ� �ٶ��ϴ�.";
						}
						mysql_free_result($result);
					}
					if(!$onload) {
						$sql = "SELECT id FROM tblmemberout WHERE id='".$id."' ";
						$result=mysql_query($sql,get_db_conn());
						if($row=mysql_fetch_object($result)) {
							$onload="ID�� �ߺ��Ǿ����ϴ�.\\n\\n�ٸ� ���̵� ����Ͻñ� �ٶ��ϴ�.";
						}
						mysql_free_result($result);
					}
					if(!$onload) {
						$sql = "SELECT email FROM tblmember WHERE email='".$email."' ";
						$result=mysql_query($sql,get_db_conn());
						if($row=mysql_fetch_object($result)) {
							$onload="�̸����� �ߺ��Ǿ����ϴ�.\\n\\n�ٸ� �̸����� ����Ͻñ� �ٶ��ϴ�.";
						}
						mysql_free_result($result);
					}
				//}

				//$gender = '';
				//$birth = '';

				if(!$onload) {

					if(in_array($gender,array('1','2'))){
						//$gender=$gender;
					}else if(!_empty($resno2)){
						$gender=substr($resno2,0,1);
					}

					if(!_empty($birth)){
						//$birth = $birth;
					}else if(!_empty($resno1)){
						$birth = (intval(substr($resno1,0,2)) < 60)?'20':'19'.substr($resno1,0,6);
					}

					$birth = preg_replace('/[^0-9]/','',$birth);

					if($extconf['reqgender'] == 'Y' && _empty($gender)){
						$onload = '������ �ʼ� �Է°� �Դϴ�.';
					}

					if(!$onload && $extconf['reqbirth'] == 'Y' && _empty($birth)){
						$onload = '������ �ʼ� �Է°� �Դϴ�.';
					}
				}

				if(!$onload) {
					//insert
					$date=date("YmdHis");

					//$home_post=$home_post1.$home_post2;
					$home_post=$home_post1;
					//$office_post=$office_post1.$office_post2;
					$office_post=$office_post1;

					if($news_mail_yn=="Y" && $news_sms_yn=="Y") {
						$news_yn="Y";
					} else if($news_mail_yn=="Y") {
						$news_yn="M";
					} else if($news_sms_yn=="Y") {
						$news_yn="S";
					} else {
						$news_yn="N";
					}
					if($_data->member_baro=="Y") $confirm_yn="N";
					else $confirm_yn="Y";

					//$home_addr=$home_addr1."=".$home_addr2;
					$home_addr=mysql_escape_string($home_addr1)."=".mysql_escape_string($home_addr2);
					$office_addr="";
					//if(strlen($office_post)==6) $office_addr=mysql_escape_string($office_addr1)."=".mysql_escape_string($office_addr2);
					$office_addr=mysql_escape_string($office_addr1)."=".mysql_escape_string($office_addr2);

					/* ��õ�� �Է� */
					$url_cnt = 1;
					while($url_cnt > 0){
						$tmpurlid = rand(10000,99999);
						$sql = "SELECT count(1) cnt FROM tblmember WHERE url_id='".$tmpurlid."'";
						$url_result = mysql_query($sql,get_db_conn());
						if($url_row = mysql_fetch_object($url_result)) {
							$url_cnt = (int)$url_row->cnt;
						}
						mysql_free_result($url_result);
					}
					$url_id = $tmpurlid;
					setcookie("my_url_id", $url_id, 0, "/".RootPath, getCookieDomain());
					setcookie("my_id", $id, 0, "/".RootPath, getCookieDomain());
					setcookie("my_name", $name, 0, "/".RootPath, getCookieDomain());
					setcookie("my_email", $email, 0, "/".RootPath, getCookieDomain());

					$sql = "INSERT tblmember SET ";
					$sql.= "id			= '".$id."', ";

					if (_empty($loginType)) {
					$sql.= "passwd		= '".md5($passwd1)."', ";
					}

					$sql.= "name		= '".$name."', ";
					$sql.= "resno		= '".$resno."', ";
					$sql.= "email		= '".$email."', ";
					$sql.= "mobile		= '".$mobile."', ";
					$sql.= "news_yn		= '".$news_yn."', ";
					$sql.= "gender		= '".$gender."', ";
					$sql.= "birth		= '".$birth."', "; //
					// ��������
					if(!empty($vDiscrNo)) $sql.= "vDiscrNo			= '".$vDiscrNo."', ";
					$sql.= "uniqNo			= '".$uniqNo."', ";

					$sql.= "home_post	= '".$home_post."', ";
					$sql.= "home_addr	= '".$home_addr."', ";
					$sql.= "home_tel	= '".$home_tel."', ";
					$sql.= "office_post	= '".$office_post."', ";
					$sql.= "office_addr	= '".$office_addr."', ";
					$sql.= "office_tel	= '".$office_tel."', ";
					$sql.= "reserve		= '".$reserve_join."', ";
					$sql.= "joinip		= '".$ip."', ";
					$sql.= "ip			= '".$ip."', ";
					$sql.= "date		= '".$date."', ";
					$sql.= "confirm_yn	= '".$confirm_yn."', ";
					if($recom_ok=="Y" && $rec_num!=0 && $rec_cnt<$recom_limit && strlen($rec_id)>0) {
						$sql.= "rec_id	= '".$rec_id."', ";
					}
					if(strlen($group_code)>0) {
						$sql.= "group_code='".$group_code."', ";
					}
					$sql.= "etcdata		= '".$etcdata."', ";
					$sql.= "loginType	= '".$loginType."', ";
					$sql.= "url_id		= '".$url_id."', ";
					//$sql.= "bizno		= '".$bizno."', ";
					//$sql.= "biz_gubun	= '".$biz_gubun."', ";
					$sql.= "devices		= 'P' ";

					//echo $sql;
					//exit;

					$insert=mysql_query($sql,get_db_conn());
					if (mysql_errno()==0) {
						if ($reserve_join>0) {
							$sql = "INSERT tblreserve SET ";
							$sql.= "id			= '".$id."', ";
							$sql.= "reserve		= ".$reserve_join.", ";
							$sql.= "reserve_yn	= 'Y', ";
							$sql.= "content		= '�������� �������Դϴ�. �����մϴ�.', ";
							$sql.= "orderdata	= '', ";
							$sql.= "date		= '".date("YmdHis",time()-1)."' ";
							$insert = mysql_query($sql,get_db_conn());
						}

						// ��õ�� ������
						if($recom_ok=="Y" && $rec_num!=0 && $rec_cnt<$recom_limit && strlen($rec_id)>0) {
							$arr = array();
							$arr['recomMem'] = $rec_id; // ��õ�� ���̵�
							$arr['newMeme'] = $id; // ��õ ���� ȸ�� ���̵�
							recommandJoin( $arr );
						}

						//�����߻� (ȸ�����Խ� �߱޵Ǵ� ����)
						if($_data->coupon_ok=="Y") {
							$date = date("YmdHis");
							$sql = "SELECT coupon_code, date_start, date_end FROM tblcouponinfo ";
							$sql.= "WHERE display='Y' AND issue_type='M' ";
							$sql.= "AND (date_end>'".substr($date,0,10)."' OR date_end='')";
							$result = mysql_query($sql,get_db_conn());

							$sql="INSERT INTO tblcouponissue (coupon_code,id,date_start,date_end,date) VALUES ";
							$couponcnt ="";
							$count=0;

							while($row = mysql_fetch_object($result)) {
								if($row->date_start>0) {
									$date_start=$row->date_start;
									$date_end=$row->date_end;
								} else {
									$date_start = substr($date,0,10);
									$date_end = date("Ymd",mktime(0,0,0,substr($date,4,2),substr($date,6,2)+abs($row->date_start),substr($date,0,4)))."23";
								}
								$sql.=" ('".$row->coupon_code."','".$id."','".$date_start."','".$date_end."','".$date."'),";
								$couponcnt="'".$row->coupon_code."',";
								$count++;
							}
							mysql_free_result($result);
							if($count>0) {
								$sql = substr($sql,0,-1);
								mysql_query($sql,get_db_conn());
								if(!mysql_errno()) {
									$couponcnt = substr($couponcnt,0,-1);
									$sql = "UPDATE tblcouponinfo SET issue_no=issue_no+1 ";
									$sql.= "WHERE coupon_code IN (".$couponcnt.")";
									mysql_query($sql,get_db_conn());
									$msg = "ȸ�� ���Խ� ������ �߱޵Ǿ����ϴ�.";
								}
							}
						}

						//���Ը��� �߼� ó��
						if(strlen($email)>0) {
							SendJoinMail($_data->shopname, $_data->shopurl, $_data->design_mail, $_data->join_msg, $_data->info_email, $email, $name);
						}

						//���� SMS �߼� ó��
						$sql = "SELECT * FROM tblsmsinfo WHERE (mem_join='Y' OR admin_join='Y') ";
						$result= mysql_query($sql,get_db_conn());
						if($row=mysql_fetch_object($result)) {
							$sms_id=$row->id;
							$sms_authkey=$row->authkey;

							$admin_join=$row->admin_join;
							$mem_join=$row->mem_join;
							$msg_mem_join=$row->msg_mem_join;

							$pattern=array("(\[ID\])","(\[NAME\])");
							$replace=array($id,$name);
							$msg_mem_join=preg_replace($pattern,$replace,$msg_mem_join);
							$msg_mem_join=AddSlashes($msg_mem_join);
							$smsmessage=$name."���� ".$id."�� ȸ�������ϼ̽��ϴ�.";
							$adminphone=$row->admin_tel;
							if(strlen($row->subadmin1_tel)>8) $adminphone.=",".$row->subadmin1_tel;
							if(strlen($row->subadmin2_tel)>8) $adminphone.=",".$row->subadmin2_tel;
							if(strlen($row->subadmin3_tel)>8) $adminphone.=",".$row->subadmin3_tel;

							$fromtel=$row->return_tel;
							mysql_free_result($result);

							$mobile=str_replace(" ","",$mobile);
							$mobile=str_replace("-","",$mobile);
							$adminphone=str_replace(" ","",$adminphone);
							$adminphone=str_replace("-","",$adminphone);

							$etcmessage="ȸ������ ���ϸ޼���(ȸ��)";
							$date=0;
							if($mem_join=="Y") {
								$temp=SendSMS($sms_id, $sms_authkey, $mobile, "", $fromtel, $date, $msg_mem_join, $etcmessage);
							}

							if($row->sleep_time1!=$row->sleep_time2) {
								$date="0";
								$time = date("Hi");
								if($row->sleep_time2<"12" && $time<=substr("0".$row->sleep_time2,-2)."59") $time+=2400;
								if($row->sleep_time2<"12" && $row->sleep_time1>$row->sleep_time2) $row->sleep_time2+=24;

								if($time<substr("0".$row->sleep_time1,-2)."00" || $time>=substr("0".$row->sleep_time2,-2)."59") {
									if($time<substr("0".$row->sleep_time1,-2)."00") $day = date("d");
									else $day=date("d")+1;
									$date = date("Y-m-d H:i:s",mktime($row->sleep_time1,0,0,date("m"),$day,date("Y")));
								}
							}
							$etcmessage="ȸ������ ���ϸ޼���(������)";
							if($admin_join=="Y") {
								$temp=SendSMS($sms_id, $sms_authkey, $adminphone, "", $fromtel, $date, $smsmessage, $etcmessage);
							}
						}

						if($recom_url_ok =="Y"){
							$URL = $Dir.FrontDir."member_urlhongbo.php";
						}else{
							$URL = $Dir.MainDir."main.php";
						}
						echo "<html><head><title></title></head><body><script>location.replace('".$URL."');</script></body></html>";
						exit;
					} else {
						$onload="ID�� �ߺ��Ǿ��ų� ȸ����� �� ������ �߻��Ͽ����ϴ�.";
					}
				}
			}
		//}

		if(strlen($onload)>0) {
			echo "<html><head><title></title></head><body onload=\"alert('".$onload."');history.go(".$history.")\"></body></html>";exit;
		}
	//}

}else if($type=="biz_insert") {
	$history="-2";
	$id=trim($_POST["id"]);
	$biz_gubun=$_POST["biz_gubun"];
	$passwd1=$_POST["passwd1"];
	$passwd2=$_POST["passwd2"];
	$name=trim($_POST["name"]);
	$bizno1=trim($_POST["bizno1"]);
	$bizno2=trim($_POST["bizno2"]);
	$bizno3=trim($_POST["bizno3"]);
	$email=trim($_POST["email"]);
	$mobile=trim($_POST["mobile"]);
	$bizno = $bizno1."-".$bizno2."-".$bizno3;
	
	$bizpath=$Dir.DataDir."shopimages/bizcheck/";

	if(is_dir($bizpath)==false) {
		mkdir($bizpath);
		chmod($bizpath,0755);
	}
	

	$onload="";
	
	
	$rsql = "SELECT id FROM tblmember WHERE bizno='".$bizno."'";
	$result2 = mysql_query($rsql,get_db_conn());
	$num = mysql_num_rows($result2);
	mysql_free_result($result2);
	if ($num>0) {
		$onload="����ڵ�Ϲ�ȣ�� �ߺ��Ǿ����ϴ�.";
	}

	$sql = "SELECT id FROM tblmember WHERE id='".$id."' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$onload="ID�� �ߺ��Ǿ����ϴ�.\\n\\n�ٸ� ���̵� ����Ͻñ� �ٶ��ϴ�.";
	}
	mysql_free_result($result);

	$sql = "SELECT id FROM tblmemberout WHERE id='".$id."' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$onload="ID�� �ߺ��Ǿ����ϴ�.\\n\\n�ٸ� ���̵� ����Ͻñ� �ٶ��ϴ�.";
	}
	mysql_free_result($result);

	$sql = "SELECT email FROM tblmember WHERE email='".$email."' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$onload="�̸����� �ߺ��Ǿ����ϴ�.\\n\\n�ٸ� �̸����� ����Ͻñ� �ٶ��ϴ�.";
	}
	mysql_free_result($result);

	//insert
	$date=date("YmdHis");

	//$home_post=$home_post1.$home_post2;
	$home_post=$home_post1;
	//$office_post=$office_post1.$office_post2;
	$office_post=$office_post1;

	if($news_mail_yn=="Y" && $news_sms_yn=="Y") {
		$news_yn="Y";
	} else if($news_mail_yn=="Y") {
		$news_yn="M";
	} else if($news_sms_yn=="Y") {
		$news_yn="S";
	} else {
		$news_yn="N";
	}
	if($_data->member_baro=="Y") $confirm_yn="N";
	else $confirm_yn="Y";

	//$home_addr=$home_addr1."=".$home_addr2;
	$home_addr=mysql_escape_string($home_addr1)."=".mysql_escape_string($home_addr2);
	$office_addr="";
	//if(strlen($office_post)==6) $office_addr=mysql_escape_string($office_addr1)."=".mysql_escape_string($office_addr2);
	$office_addr=mysql_escape_string($office_addr1)."=".mysql_escape_string($office_addr2);

	/* ��õ�� �Է� */
	$url_cnt = 1;
	while($url_cnt > 0){
		$tmpurlid = rand(10000,99999);
		$sql = "SELECT count(1) cnt FROM tblmember WHERE url_id='".$tmpurlid."'";
		$url_result = mysql_query($sql,get_db_conn());
		if($url_row = mysql_fetch_object($url_result)) {
			$url_cnt = (int)$url_row->cnt;
		}
		mysql_free_result($url_result);
	}

	if(!$onload) {

			$bizcheck = $_FILES["bizcheck"];
			if($bizcheck[size]>0){
				$bizcheckname=!_empty($bizcheck[name])?trim($bizcheck[name]):"";
				if(strlen($bizcheckname)>0){
					
					$ext = strtolower(substr($bizcheck[name],strlen($bizcheck[name])-3,3));
					$bizcheckname=date(His)."_".$bizno.".".$ext;
					move_uploaded_file($bizcheck[tmp_name],$bizpath.$bizcheckname);
					chmod($bizpath.$bizcheckname,777);

					if(is_file($src)){
						@unlink($src);
					}
				}
			}

			$url_id = $tmpurlid;
			setcookie("my_url_id", $url_id, 0, "/".RootPath, getCookieDomain());
			setcookie("my_id", $id, 0, "/".RootPath, getCookieDomain());
			setcookie("my_name", $name, 0, "/".RootPath, getCookieDomain());
			setcookie("my_email", $email, 0, "/".RootPath, getCookieDomain());

			$sql = "INSERT tblmember SET ";
			$sql.= "id			= '".$id."', ";
			$sql.= "passwd		= '".md5($passwd1)."', ";
			$sql.= "name		= '".$name."', ";
			$sql.= "bizno		= '".$bizno."', ";
			$sql.= "email		= '".$email."', ";
			$sql.= "mobile		= '".$mobile."', ";
			$sql.= "joinip		= '".$ip."', ";
			$sql.= "ip			= '".$ip."', ";
			$sql.= "date		= '".$date."', ";
			$sql.= "confirm_yn	= '".$confirm_yn."', ";
			if(strlen($group_code)>0) {
				$sql.= "group_code='".$group_code."', ";
			}
			$sql.= "etcdata		= '".$etcdata."', ";
			$sql.= "url_id		= '".$url_id."', ";
			$sql.= "biz_gubun	= '".$biz_gubun."', ";
			$sql.= "gubun		= '���', ";
			$sql.= "bizcheck	= '".$bizcheckname."', ";
			$sql.= "devices		= 'P' ";

			//exit($sql);

			$insert=mysql_query($sql,get_db_conn());
			if (mysql_errno()==0) {
				if ($reserve_join>0) {
					$sql = "INSERT tblreserve SET ";
					$sql.= "id			= '".$id."', ";
					$sql.= "reserve		= ".$reserve_join.", ";
					$sql.= "reserve_yn	= 'Y', ";
					$sql.= "content		= '�������� �������Դϴ�. �����մϴ�.', ";
					$sql.= "orderdata	= '', ";
					$sql.= "date		= '".date("YmdHis",time()-1)."' ";
					$insert = mysql_query($sql,get_db_conn());
				}

				//�����߻� (ȸ�����Խ� �߱޵Ǵ� ����)
				if($_data->coupon_ok=="Y") {
					$date = date("YmdHis");
					$sql = "SELECT coupon_code, date_start, date_end FROM tblcouponinfo ";
					$sql.= "WHERE display='Y' AND issue_type='M' ";
					$sql.= "AND (date_end>'".substr($date,0,10)."' OR date_end='')";
					$result = mysql_query($sql,get_db_conn());

					$sql="INSERT INTO tblcouponissue (coupon_code,id,date_start,date_end,date) VALUES ";
					$couponcnt ="";
					$count=0;

					while($row = mysql_fetch_object($result)) {
						if($row->date_start>0) {
							$date_start=$row->date_start;
							$date_end=$row->date_end;
						} else {
							$date_start = substr($date,0,10);
							$date_end = date("Ymd",mktime(0,0,0,substr($date,4,2),substr($date,6,2)+abs($row->date_start),substr($date,0,4)))."23";
						}
						$sql.=" ('".$row->coupon_code."','".$id."','".$date_start."','".$date_end."','".$date."'),";
						$couponcnt="'".$row->coupon_code."',";
						$count++;
					}
					mysql_free_result($result);
					if($count>0) {
						$sql = substr($sql,0,-1);
						mysql_query($sql,get_db_conn());
						if(!mysql_errno()) {
							$couponcnt = substr($couponcnt,0,-1);
							$sql = "UPDATE tblcouponinfo SET issue_no=issue_no+1 ";
							$sql.= "WHERE coupon_code IN (".$couponcnt.")";
							mysql_query($sql,get_db_conn());
							$msg = "ȸ�� ���Խ� ������ �߱޵Ǿ����ϴ�.";
						}
					}
				}

				//���Ը��� �߼� ó��
				if(strlen($email)>0) {
					SendJoinMail($_data->shopname, $_data->shopurl, $_data->design_mail, $_data->join_msg, $_data->info_email, $email, $name);
				}

				//���� SMS �߼� ó��
				$sql = "SELECT * FROM tblsmsinfo WHERE (mem_join='Y' OR admin_join='Y') ";
				$result= mysql_query($sql,get_db_conn());
				if($row=mysql_fetch_object($result)) {
					$sms_id=$row->id;
					$sms_authkey=$row->authkey;

					$admin_join=$row->admin_join;
					$mem_join=$row->mem_join;
					$msg_mem_join=$row->msg_mem_join;

					$pattern=array("(\[ID\])","(\[NAME\])");
					$replace=array($id,$name);
					$msg_mem_join=preg_replace($pattern,$replace,$msg_mem_join);
					$msg_mem_join=AddSlashes($msg_mem_join);
					$smsmessage=$name."���� ".$id."�� ȸ�������ϼ̽��ϴ�.";
					$adminphone=$row->admin_tel;
					if(strlen($row->subadmin1_tel)>8) $adminphone.=",".$row->subadmin1_tel;
					if(strlen($row->subadmin2_tel)>8) $adminphone.=",".$row->subadmin2_tel;
					if(strlen($row->subadmin3_tel)>8) $adminphone.=",".$row->subadmin3_tel;

					$fromtel=$row->return_tel;
					mysql_free_result($result);

					$mobile=str_replace(" ","",$mobile);
					$mobile=str_replace("-","",$mobile);
					$adminphone=str_replace(" ","",$adminphone);
					$adminphone=str_replace("-","",$adminphone);

					$etcmessage="ȸ������ ���ϸ޼���(ȸ��)";
					$date=0;
					if($mem_join=="Y") {
						$temp=SendSMS($sms_id, $sms_authkey, $mobile, "", $fromtel, $date, $msg_mem_join, $etcmessage);
					}

					if($row->sleep_time1!=$row->sleep_time2) {
						$date="0";
						$time = date("Hi");
						if($row->sleep_time2<"12" && $time<=substr("0".$row->sleep_time2,-2)."59") $time+=2400;
						if($row->sleep_time2<"12" && $row->sleep_time1>$row->sleep_time2) $row->sleep_time2+=24;

						if($time<substr("0".$row->sleep_time1,-2)."00" || $time>=substr("0".$row->sleep_time2,-2)."59") {
							if($time<substr("0".$row->sleep_time1,-2)."00") $day = date("d");
							else $day=date("d")+1;
							$date = date("Y-m-d H:i:s",mktime($row->sleep_time1,0,0,date("m"),$day,date("Y")));
						}
					}
					$etcmessage="ȸ������ ���ϸ޼���(������)";
					if($admin_join=="Y") {
						$temp=SendSMS($sms_id, $sms_authkey, $adminphone, "", $fromtel, $date, $smsmessage, $etcmessage);
					}
				}

				if($recom_url_ok =="Y"){
					$URL = $Dir.FrontDir."member_urlhongbo.php";
				}else{
					$URL = $Dir.MainDir."main.php";
				}
				echo "<html><head><title></title></head><body><script>alert('ȸ�����ԵǾ����ϴ�. �α����� �̿����ּ���.');location.replace('".$URL."');</script></body></html>";
				exit;
			}
		//} else {
		//	$onload="ID�� �ߺ��Ǿ��ų� ȸ����� �� ������ �߻��Ͽ����ϴ�.";
	}

	if(strlen($onload)>0) {
		echo "<html><head><title></title></head><body onload=\"alert('".$onload."');history.go(".$history.")\"></body></html>";exit;
	}

}

if(strlen($news_mail_yn)==0) $news_mail_yn="Y";
if(strlen($news_sms_yn)==0) $news_sms_yn="Y";
?>

<HTML>
<HEAD>
<TITLE><?=$_data->shoptitle?> - ȸ������</TITLE>
<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">
<META http-equiv="X-UA-Compatible" content="IE=Edge" />

<META name="description" content="<?=(strlen($_data->shopdescription)>0?$_data->shopdescription:$_data->shoptitle)?>">
<META name="keywords" content="<?=$_data->shopkeyword?>">
<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
<?include($Dir."lib/style.php")?>
<SCRIPT LANGUAGE="JavaScript">
<!--
function chkCtyNo(obj) {
	if (obj.length == 14) {
		var calStr1 = "2345670892345", biVal = 0, tmpCal, restCal;

		for (i=0; i <= 12; i++) {
			if (obj.substring(i,i+1) == "-")
				tmpCal = 1
			else
				biVal = biVal + (parseFloat(obj.substring(i,i+1)) * parseFloat(calStr1.substring(i,i+1)));
		}

		restCal = 11 - (biVal % 11);

		if (restCal == 11) {
			restCal = 1;
		}

		if (restCal == 10) {
			restCal = 0;
		}

		if (restCal == parseFloat(obj.substring(13,14))) {
			return true;
		} else {
			return false;
		}
	}
}

function strnumkeyup2(field) {
	if (!isNumber(field.value)) {
		alert("���ڸ� �Է��ϼ���.");
		field.value=strLenCnt(field.value,field.value.length - 1);
		field.focus();
		return;
	}
	if (field.name == "resno1") {
		if (field.value.length == 6) {
			form1.resno2.focus();
		}
	}
}

function AdultCheck(resno1,resno2) {
	gbn=resno2.substring(0,1);
	date=new Date();
	if(gbn=="3" || gbn=="4") {
		year="20"+resno1.substring(0,2);
	} else {
		year="19"+resno1.substring(0,2);
	}
	age=parseInt(date.getYear())-parseInt(year);
}


function CheckForm() {
	form=document.form1;
	resno1=form.resno1;
	resno2=form.resno2;
	gendercheck = "<?=$extconf['reqgender']?>";
	birthcheck = "<?=$extconf['reqbirth']?>";
	gendercount=0;
	for(var i=0;i<form.gender.length;i++){
	   if(form.gender[i].checked==true){
		   gendercount++;
	   }
	}

	<? if (!$loginType) { /* SNS���� �Ѿ� ���� �ʾ��� �� */ ?>
	if(form.id.value.length==0) {
		alert("���̵� �Է��ϼ���."); form.id.focus(); return;
	}
	if(form.id.value.length<4 || form.id.value.length>12) {
		alert("���̵�� 4�� �̻� 12�� ���Ϸ� �Է��ϼž� �մϴ�."); form.id.focus(); return;
	}
	if (CheckFormData(form.id.value)==false) {
   		alert("ID�� ����, ���ڸ� �����Ͽ� 4~12�� �̳��� ����� �����մϴ�."); form.id.focus(); return;
   	}
	if(form.passwd1.value.length==0) {
		alert("��й�ȣ�� �Է��ϼ���."); form.passwd1.focus(); return;
	}
	if(form.passwd1.value!=form.passwd2.value) {
		alert("��й�ȣ�� ��ġ���� �ʽ��ϴ�."); form.passwd2.focus(); return;
	}
	if(form.name.value.length==0) {
		alert("������ �̸��� �Է��ϼ���."); form.name.focus(); return;
	}
	if(form.name.value.length>10) {
		alert("�̸��� �ѱ� 5��, ���� 10�� �̳��� �Է��ϼž� �մϴ�."); form.name.focus(); return;
	}

	<?if($_data->resno_type!="N"){?>
	if (resno1.value.length==0) {
		alert("�ֹε�Ϲ�ȣ�� �Է��ϼ���.");
		resno1.focus();
		return;
	}
	if (resno2.value.length==0) {
		alert("�ֹε�Ϲ�ȣ�� �Է��ϼ���.");
		resno2.focus();
		return;
	}

	var bb;
	bb = chkCtyNo(resno1.value+"-"+resno2.value);

	if (!bb) {
		alert("�߸��� �ֹε�Ϲ�ȣ �Դϴ�.\n\n�ٽ� �Է��ϼ���.");
		resno1.focus();
		return;
	}
	if(AdultCheck(resno1.value,resno2.value)<14) {
		alert("�� 14�� �̸��� �Ƶ��� ȸ�����Խ�\n �����븮���� ���ǰ� �־�� �մϴ�!\n\n ��� ���θ��� �����ֽñ� �ٶ��ϴ�.");
		return;
	}

	<?if($_data->adult_type=="Y"){?>
		if(AdultCheck(resno1.value,resno2.value)<19) {
			alert("�� ���θ��� ���θ� �̿밡���ϹǷ� ȸ�������� �Ͻ� �� �����ϴ�.");
			return;
		}
	<?}?>
	<?}?>

	if(form.email.value.length==0) {
		alert("�̸����� �Է��ϼ���."); form.email.focus(); return;
	}
	if(!IsMailCheck(form.email.value)) {
		alert("�̸��� ������ �����ʽ��ϴ�.\n\nȮ���Ͻ� �� �ٽ� �Է��ϼ���."); form.email.focus(); return;
	}
	if(form.mobile.value.length==0) {
		alert("�޴���ȭ�� �Է��ϼ���."); form.mobile.focus(); return;
	}

	if(form.idChk.value=="0") {
		alert("���̵� �ߺ� üũ�� �ϼž� �մϴ�!");
		idcheck();
		return;
	}

	/*
	if(form.mailChk.value=="0") {
		alert("�̸��� �ߺ� üũ�� �ϼž� �մϴ�!");
		mailcheck();
		return;
	}
	*/
	<? } /* SNS���� �Ѿ� ���� �ʾ��� �� */ ?>

	if(gendercheck == "Y" && gendercount <= 0){
		alert("������ �����ϼ���");form.gender.value.focus();return;
	}
	if(birthcheck == "Y" && form.birth.value==""){
		alert("��������� �Է��ϼ���");form.birth.value.focus();return;
	}
	if(form.home_tel.value.length==0) {
		alert("����ȭ��ȣ�� �Է��ϼ���."); form.home_tel.focus(); return;
	}
	if(form.home_post1.value.length==0 || form.home_addr1.value.length==0) {
		alert("�ּҸ� �Է��ϼ���.");
		return;
	}
	if(form.home_addr2.value.length==0) {
		alert("���ּҸ� �Է��ϼ���."); form.home_addr2.focus(); return;
	}

	<?=$scriptform?>

	form.type.value="insert";

<?if($_data->ssl_type=="Y" && strlen($_data->ssl_domain)>0 && strlen($_data->ssl_port)>0 && $_data->ssl_pagelist["MJOIN"]=="Y") {?>
		form.action='https://<?=$_data->ssl_domain?><?=($_data->ssl_port!="443"?":".$_data->ssl_port:"")?>/<?=RootPath.SecureDir?>member_join.php';
<?}?>
	if(confirm("ȸ�������� �ϰڽ��ϱ�?"))
		form.submit();
	else
		return;
}

function f_addr_search(form,post,addr,gbn) {
	window.open("<?=$Dir.FrontDir?>addr_search.php?form="+form+"&post="+post+"&addr="+addr+"&gbn="+gbn,"f_post","resizable=no,scrollbars=yes,x=100,y=200,width=430,height=350");
}

function idcheck() {
	form1.idChk.value="0";
	window.open("<?=$Dir.FrontDir?>iddup.php?id="+document.form1.id.value,"","height=150,width=200");
}

function mailcheck() {
	if(!IsMailCheck(form1.email.value)) {
		alert("�̸��� ������ �����ʽ��ϴ�.\n\nȮ���Ͻ� �� �ٽ� �Է��ϼ���.");
		form1.email.focus();
		return;
	}
	form1.mailChk.value="0";
	window.open("<?=$Dir.FrontDir?>mailcheck.php?email="+document.form1.email.value,"","height=150,width=200");
}
//-->
</SCRIPT>


<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script type="text/javascript">
	<!--
	function addr_search_for_daumapi(post,addr1,addr2) {
		new daum.Postcode({
			oncomplete: function(data) {
				// �˾����� �˻���� �׸��� Ŭ�������� ������ �ڵ带 �ۼ��ϴ� �κ�.

			// �� �ּ��� ���� ��Ģ�� ���� �ּҸ� �����Ѵ�.
			// �������� ������ ���� ���� ��쿣 ����('')���� �����Ƿ�, �̸� �����Ͽ� �б� �Ѵ�.
				var fullAddr = '';// ���� �ּ� ����
				var extraAddr = '';  // ������ �ּ� ����

			// ����ڰ� ������ �ּ� Ÿ�Կ� ���� �ش� �ּ� ���� �����´�.
				if (data.userSelectedType === 'R') {// ����ڰ� ���θ� �ּҸ� �������� ���
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
				document.getElementById(post).value = data.zonecode;  //5�ڸ� �������ȣ ���
				document.getElementById(addr1).value = fullAddr;

			// Ŀ���� ���ּ� �ʵ�� �̵��Ѵ�.
				if (addr2 != "") {
					document.getElementById(addr2).focus();
				}
			}
		}).open();
	}
	//-->
</script>
</HEAD>

<body<?=(substr($_data->layoutdata["MOUSEKEY"],0,1)=="Y"?" oncontextmenu=\"return false;\"":"")?><?=(substr($_data->layoutdata["MOUSEKEY"],1,1)=="Y"?" ondragstart=\"return false;\" onselectstart=\"return false;\"":"")?> leftmargin="0" marginwidth="0" topmargin="0" marginheight="0"><?=(substr($_data->layoutdata["MOUSEKEY"],2,1)=="Y"?"<meta http-equiv=\"ImageToolbar\" content=\"No\">":"")?>

<? include ($Dir.MainDir.$_data->menu_type.".php") ?>

<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
<input type=hidden name=type value="">
<input type=hidden name="idChk" value="0">
<input type=hidden name="mailChk" value="0">

<? if(strlen($loginType)>0){ ?>
<input type="hidden" name="loginType" value="<?=$loginType?>" />
<? } ?>

<?if($_data->ssl_type=="Y" && strlen($_data->ssl_domain)>0 && strlen($_data->ssl_port)>0 && $_data->ssl_pagelist["MJOIN"]=="Y") {?>
<input type=hidden name=shopurl value="<?=getenv("HTTP_HOST")?>">
<?}?>


<div class="currentTitle">
	<div class="titleimage">ȸ������</div>
</div>


	<table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
$leftmenu="Y";
if($_data->design_mbjoin=="U") {
	$sql="SELECT body,leftmenu FROM ".$designnewpageTables." WHERE type='mbjoin'";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$body=$row->body;
		$body=str_replace("[DIR]",$Dir,$body);
		$leftmenu=$row->leftmenu;
		$newdesign="Y";
	}
	mysql_free_result($result);
}

if ($leftmenu!="N") {
	echo "<tr>\n";
	if ($_data->title_type=="Y" && file_exists($Dir.DataDir."design/memberjoin_title.gif")) {
		echo "<td><img src=\"".$Dir.DataDir."design/memberjoin_title.gif\" border=\"0\" ></td>\n";
	} else {
		echo "<td>\n";
		/*
		echo "<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>\n";
		echo "<TR>\n";
		echo "	<TD><IMG SRC=".$Dir."images/".$_data->icon_type."/memberjoin_title_head.gif ALT=></TD>\n";
		echo "	<TD width=100% valign=top background=".$Dir."images/".$_data->icon_type."/memberjoin_title_bg.gif></TD>\n";
		echo "	<TD width=40><IMG SRC=".$Dir."images/".$_data->icon_type."/memberjoin_title_tail.gif ALT=></TD>\n";
		echo "</TR>\n";
		echo "</TABLE>\n";
		*/
		echo "</td>\n";
	}
	echo "</tr>\n";
}

echo "<tr>\n";
echo "	<td>\n";
include ($Dir.TempletDir."mbjoin/mbjoin".$_data->design_mbjoin.".php");
echo "	</td>\n";
echo "</tr>\n";
?>

	</table>


</form>
<?=$onload?>


<link href="/css/jquery-ui/jquery-ui.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/js/jquery-ui.min.js"></script>

<script>
	//������� �޷� ó��
	$j(function() {
		$j("#birth_day").datepicker({
			dateFormat: 'yy-mm-dd',
			prevText: '���� ��',
			nextText: '���� ��',
			monthNames: ['1��','2��','3��','4��','5��','6��','7��','8��','9��','10��','11��','12��'],
			monthNamesShort: ['1��','2��','3��','4��','5��','6��','7��','8��','9��','10��','11��','12��'],
			dayNames: ['��','��','ȭ','��','��','��','��'],
			dayNamesShort: ['��','��','ȭ','��','��','��','��'],
			dayNamesMin: ['��','��','ȭ','��','��','��','��'],
			showMonthAfterYear: true,
			changeMonth: true,
			changeYear: true,
			yearSuffix: '��'
		});
	});
</script>

<script>
	var ck_path = "../";
</script>
<script src="/js/ajax_form.js"></script>

<script language=javascript>  
<!--
    var CBA_window; 

    function openPCCWindow(){ 
        var CBA_window = window.open('', 'PCCWindow', 'width=430, height=560, resizable=1, scrollbars=no, status=0, titlebar=0, toolbar=0, left=300, top=200' );

        if(CBA_window == null){ 
			 alert(" �� ������ XP SP2 �Ǵ� ���ͳ� �ͽ��÷η� 7 ������� ��쿡�� \n    ȭ�� ��ܿ� �ִ� �˾� ���� �˸����� Ŭ���Ͽ� �˾��� ����� �ֽñ� �ٶ��ϴ�. \n\n�� MSN,����,���� �˾� ���� ���ٰ� ��ġ�� ��� �˾������ ���ֽñ� �ٶ��ϴ�.");
        }

        document.reqCBAForm.action = 'https://pcc.siren24.com/pcc_V3/jsp/pcc_V3_j10_v2.jsp';
        document.reqCBAForm.target = 'PCCWindow';

		return true;
    }	

//-->
</script>

<!-- ����Ȯ�μ��� ��û form --------------------------->
<form name="reqCBAForm" method="post" action = "" onsubmit="return openPCCWindow()">
    <input type="hidden" name="reqInfo"     value = "<? echo "$enc_reqInfo" ?>">
    <input type="hidden" name="retUrl"      value = "<? echo "$retUrl" ?>">
    <input type="hidden" name="verSion"		value = "2"><!--��� ��������-->
    <input type="submit" value="����Ȯ�μ��� ��û" >
</form>

<!--End ����Ȯ�μ��� ��û form ----------------------->

</BODY>
</HTML>


<? include ($Dir."lib/bottom.php") ?>