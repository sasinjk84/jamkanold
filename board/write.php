<?
if(substr(getenv("SCRIPT_NAME"),-9)=="/write.php"){
	header("HTTP/1.0 404 Not Found");
	exit;
}

include ("head.php");

$exec=$_REQUEST["exec"];

if(strlen($pridx)>0) {
	$prqnaboard=getEtcfield($_data->etcfield,"PRQNA");
	if($prqnaboard!=$board) $pridx="";
}

if(strlen($mode)>0 && ($mode != "reWrite")) {
	//�������� ���͸�
	if (strlen($setup[filter])>0) {
		if (isFilter($setup[filter],$up_memo,$findFilter)) {
			echo "
				<script>
					window.alert('��� ���ѵ� �ҷ��ܾ ����ϼ̽��ϴ�.\\n\\n�ٽ� Ȯ�� �Ͻʽÿ�.');
				</script>
			";
			reWriteForm();
			exit;
		}
	}

	//������ ��Ī���� �̸� ���͸�
	if(strlen($setup[admin_name])>0) {
		if(($_POST["up_name"]==$setup[admin_name]) && ($setup[passwd]!=$_POST["up_passwd"])) {
			echo "
				<script>
					window.alert('�Խ��� ������ ��Ī �۾��� ������ ���Ͽ� [$_POST[up_name]] �̸����� ����� �Ұ����մϴ�.\\n\\n�ش� �̸����� �Խù� ����� �ϽǷ��� �Խ��� ������ ��й�ȣ�� �Է��ϼ���.');
				</script>
			";
			reWriteForm();
			exit;
		}
	}
}


if($setup[use_html]=="N") {
	$hide_html_start="<!--";
	$hide_html_end="-->";
}

if($exec == "reply") {
	$hide_replysms_start="<!--";
	$hide_replysms_end="-->";
}

if ($exec == "write") {	//�۾���
	//�۾��� ���� üũ
	if($member[grant_write]!="Y") {
		if($setup[grant_write]=="A") {	// ������
			$errmsg="�Խ��� ������ �α��� �� �۾��Ⱑ �����մϴ�.";
			echo "<html><head><title></title></head><body onload=\"alert('".$errmsg."');history.go(-1);\"></body></html>";exit;
		} else if($setup[grant_write]=="Y") {
			if(strlen($setup[group_code])==4) {
				$errmsg="Ư�� ȸ���׷츸 �۾��Ⱑ �����մϴ�.";
				echo "<html><head><title></title></head><body onload=\"alert('".$errmsg."');history.go(-1);\"></body></html>";exit;
			} else {
				$errmsg="ȸ�� �α��� �� �۾��Ⱑ �����մϴ�.";
				echo "<html><head><title></title></head><body onload=\"alert('".$errmsg."');history.go(-1);\"></body></html>";exit;
			}
		}
	}

	if(($_POST[mode]=="up_result") && ($_POST[ins4e][mode]=="up_result") && ($_POST[up_subject]!="") && ($_POST[ins4e][up_subject]!="")) {
		if(!eregi($_SERVER[HTTP_HOST],$_SERVER[HTTP_REFERER])) {
			$errmsg="�߸��� ��η� �����ϼ̽��ϴ�.(1)";
			echo "<html><head><title></title></head><body onload=\"alert('".$errmsg."');history.go(-1);\"></body></html>";exit;
		}

		$thread = $setup[thread_no] - 1;
		if ($thread<=0) {
			$que2 = "SELECT MIN(thread) FROM tblboard ";
			$result = mysql_query($que2,get_db_conn());
			$row = mysql_fetch_array($result);
			if ($row[0]<=0) {
				$thread = 999999999;
			} else {
				$thread = $row[0] - 1;
			}
			mysql_free_result($result);
		}

		//�ش� ���θ� ��� �Խ��� thread�� �����ϰ� ������Ʈ (���յǾ� ������ �� ����thread���� �����ϱ� ���Ͽ�)
		@mysql_query("UPDATE tblboardadmin SET thread_no='".$thread."' ",get_db_conn());

		if($setup[use_html]=="N") $up_html="";

		//���Ͽ� ����
		$send_email = $up_email;
		$send_name = $up_name;
		$send_subject = $up_subject;
		$send_memo = stripslashes($up_memo);
		$send_filename= $up_filename;

		if (!$up_html) {
			$send_memo = nl2br(stripslashes($up_memo));
		}
		$send_date = date("Y-m-d H:i:s");


		/** ������ ���� ���� ó�� �߰� �κ� */
		if(preg_match_all('/\/data\/editor_temp\/([a-zA-Z0-9\.]+)/',$up_memo,$edimg)){
			foreach($edimg[1] as $timg){
				@rename($_SERVER['DOCUMENT_ROOT'].'/data/editor_temp/'.$timg,$_SERVER['DOCUMENT_ROOT'].'/data/editor/'.$timg);
			}
			$up_memo = str_replace('/data/editor_temp/','/data/editor/',$up_memo);
			$send_memo = str_replace('/data/editor_temp/','/data/editor/',$send_memo);
		}
		/** #������ ���� ���� ó�� �߰� �κ� */

		$up_name = addslashes($up_name);
		$up_subject = str_replace("<!","&lt;!",$up_subject);
		$up_subject = addslashes($up_subject);
		$up_memo = str_replace("<!","&lt;!",$up_memo);
		$up_memo = addslashes($up_memo);
		$up_userid = $_ShopInfo->getMemid();
		$up_usercel = $up_cel1."-".$up_cel2."-".$up_cel3;

		if (!$up_is_secret) $up_is_secret = 0;

		$next_no = $setup[max_num];

		if (!$next_no) {
			$que3 = "SELECT MAX(num) FROM tblboard WHERE board='".$board."' AND pos=0 AND deleted!='1'";
			$result3 = mysql_query($que3,get_db_conn());
			$row3 = mysql_fetch_array($result3);
			@mysql_free_result($result3);
			$next_no = $row3[0];

			if (!$next_no) $next_no = 0;
		}

		if(ProcessBoardFileIn($board,$up_filename)!="SUCCESS") {
			$up_filename="";
		}

		if(strlen($up_url)>0) {
			$up_memo = $up_subject;
		}

		$sql  = "INSERT tblboard SET ";
		$sql .= "board				= '".$board."', ";
		$sql .= "subCategory		= '".$subCategory."', ";
		$sql .= "num				= '', ";
		$sql .= "thread				= '".$thread."', ";
		$sql .= "pos				= '0', ";
		$sql .= "depth				= '0', ";
		$sql .= "prev_no			= '0', ";
		if(strlen($pridx)>0) {
			$sql.= "pridx			= '".$pridx."', ";
		}
		$sql .= "next_no			= '".$next_no."', ";
		$sql .= "name				= '".$up_name."', ";
		$sql .= "passwd				= '".$up_passwd."', ";
		$sql .= "email				= '".$up_email."', ";
		$sql .= "userid				= '".$up_userid."', ";
		$sql .= "usercel			= '".$up_usercel."', ";
		$sql .= "is_secret			= '".$up_is_secret."', ";
		$sql .= "use_html			= '".$up_html."', ";
		$sql .= "title				= '".$up_subject."', ";
		$sql .= "filename			= '".$up_filename."', ";
		$sql .= "writetime			= '".time()."', ";
		$sql .= "ip					= '".getenv("REMOTE_ADDR")."', ";
		$sql .= "access				= '0', ";
		$sql .= "total_comment		= '0', ";
		$sql .= "content			= '".$up_memo."', ";
		$sql .= "notice				= '0', ";
		$sql .= "deleted			= '0' ";
		if(strlen($up_url)>0) {
			$sql.= ", url			= '".$up_url."' ";
		}
		
		$insert = mysql_query($sql,get_db_conn());


		if($insert) {
			$qry = "SELECT LAST_INSERT_ID() ";
			$res = mysql_fetch_row(mysql_query($qry,get_db_conn()));
			$thisNum = $res[0];

			if ($next_no) {
				$qry9 = "SELECT thread FROM tblboard WHERE board='$board' AND num='$next_no' ";
				$res9 = mysql_query($qry9,get_db_conn());
				$next_thread = mysql_fetch_row($res9);
				@mysql_free_result($res9);
				mysql_query("UPDATE tblboard SET prev_no='".$thisNum."' WHERE board='".$board."' AND thread = '".$next_thread[0]."'",get_db_conn());

				mysql_query("UPDATE tblboard SET prev_no='$thisNum' WHERE board='$board' AND num = '$next_no'",get_db_conn());
			}

			// ===== �������̺��� �Խñۼ� update =====
			$sql3 = "UPDATE tblboardadmin SET total_article=total_article+1, max_num='$thisNum' ";
			$sql3.= "WHERE board='$board' ";
			$update = mysql_query($sql3,get_db_conn());

			if (($setup[use_admin_mail]=="Y") && $setup[admin_mail]) {
				INCLUDE "SendForm.inc.php";

				$title = $send_subject;
				$message = GetHeader() . GetContent($send_name, $send_email, $send_subject, $send_memo,$send_date,$send_filename,$setup[board_name]) . GetFooter();

				$tmp_admin_mail_list = split(",",$setup[admin_mail]);

				sendMailForm($send_name,$send_email,$message,$bodytext,$mailheaders);

				for($jj=0;$jj<count($tmp_admin_mail_list);$jj++) {
					if (ismail($tmp_admin_mail_list[$jj])) {
						mail($tmp_admin_mail_list[$jj], $title, $bodytext, $mailheaders);
					}
				}
			}

			//�Խ��� �۵�� SMS�߼�
			$sqlsms = "SELECT * FROM tblsmsinfo ";
			$resultsms= mysql_query($sqlsms,get_db_conn());
			if($rowsms=mysql_fetch_object($resultsms)){
				function getStringCut($strValue,$lenValue)
				{
					preg_match('/^([\x00-\x7e]|.{2})*/', substr($strValue,0,$lenValue), $retrunValue);
					return $retrunValue[0];
				}

				$sms_id=$rowsms->id;
				$sms_authkey=$rowsms->authkey;

				if ( $rowsms->admin_board =="Y" ) {

					$totellist = ( strlen($setup[admin_sms]) > 0 ) ? $setup[admin_sms] : $rowsms->admin_tel;

					$fromtel=$rowsms->return_tel;
					$smsboardname=str_replace("\\n"," ",str_replace("\\r","",strip_tags($setup[board_name])));
					$smsboardsubject=str_replace("\\n"," ",str_replace("\\r","",strip_tags(str_replace("&lt;!","<!",stripslashes($up_subject)))));

					$smsmsg="]�űԱ��� ".getStringCut($smsboardsubject,20)."���� ��ϵǾ����ϴ�.";
					$smsmsg=getStringCut($setup[board_name],80-strlen($smsmsg)).$smsmsg;

					$etcmsg="�Խ��� �۵�� �޼���(������)";
					if($rowsms->sleep_time1!=$rowsms->sleep_time2){
						$date="0";
						$time = date("Hi");
						if($rowsms->sleep_time2<"12" && $time<=substr("0".$rowsms->sleep_time2,-2)."59") $time+=2400;
						if($rowsms->sleep_time2<"12" && $rowsms->sleep_time1>$rowsms->sleep_time2) $rowsms->sleep_time2+=24;

						if($time<substr("0".$rowsms->sleep_time1,-2)."00" || $time>=substr("0".$rowsms->sleep_time2,-2)."59"){
							if($time<substr("0".$rowsms->sleep_time1,-2)."00") $day = date("d");
							else $day=date("d")+1;
							$date = date("Y-m-d H:i:s",mktime($rowsms->sleep_time1,0,0,date("m"),$day,date("Y")));
						}
					}
					$temp=SendSMS($sms_id, $sms_authkey, $totellist, "", $fromtel, $date, $smsmsg, $etcmsg);
					mysql_free_result($resultsms);
				}
			}
			echo("<meta http-equiv='Refresh' content='0; URL=board.php?pagetype=list&board=$board'>");
			exit;
		} else {
			echo "
				<script>
				window.alert('�۾��� �Է��� ������ �߻��Ͽ����ϴ�.');
				</script>
			";
			reWriteForm();
			exit;
		}
	} else {
		if ($mode == "reWrite") {
			$thisBoard=$_REQUEST["thisBoard"];
			$thisBoard[content]  = stripslashes(urldecode($thisBoard[content]));
			$thisBoard[title]  = stripslashes(urldecode($thisBoard[title]));
			$thisBoard[name]  = stripslashes(urldecode($thisBoard[name]));
		} else if (!$_REQUEST["mode"]) {
			$thisBoard[name] = $member[name];
			$thisBoard[email] = $member[email];
			$thisBoard[pridx] = $pridx;
			$thisBoard[cel] = explode("-",$row2[usercel]);
		}

		include ("top.php");

		if(strlen($pridx)>0) {
			$sql = "SELECT a.productcode,a.productname,a.etctype,a.sellprice,a.quantity,a.tinyimage ";
			$sql.= "FROM tblproduct AS a ";
			$sql.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
			$sql.= "WHERE pridx='".$pridx."' ";
			$sql.= "AND (a.group_check='N' OR b.group_code='".$_ShopInfo->getMemgroup()."') ";
			$result=mysql_query($sql,get_db_conn());
			if($_pdata=mysql_fetch_object($result)) {
				INCLUDE "prqna_top.php";
			} else {
				$pridx="";
			}
			mysql_free_result($result);
		}

		//include ($dir."/write.php");

		include ($dir."/write.php");

		include ("bottom.php");
	}
} else if ($exec == "modify") {	//����
	//�Խñ� ���� ���� üũ
	if($setup[use_article_care]=="Y" && $member[admin]!="SU") {
		$errmsg="�ش� �Խ����� �Խñ� ��ȣ ����� ������̹Ƿ� ������ �Ұ����մϴ�.\\n\\n���θ� ��ڿ��� �����Ͻñ� �ٶ��ϴ�.";
		echo "<html><head><title></title></head><body onload=\"alert('".$errmsg."');history.go(-1);\"></body></html>";exit;
	}

	$sql2 = "SELECT * FROM tblboard WHERE board='$board' AND num = $num ";
	$result2 = mysql_query($sql2,get_db_conn());
	$edit_ok = mysql_num_rows($result2);

	if ((!$edit_ok) || ($edit_ok == -1)) {
		$errmsg="������ �Խñ��� �����ϴ�.";
		echo "<html><head><title></title></head><body onload=\"alert('".$errmsg."');history.go(-1);\"></body></html>";exit;
	}

	$row2 = mysql_fetch_array($result2);

	if(($_POST[mode]=="up_result") && ($_POST[ins4e][mode]=="up_result") && ($_POST[up_subject]!="") && ($_POST[ins4e][up_subject]!="")) {
		if($member[admin]!="SU") {
			if (strlen($_POST["up_passwd"])==0 AND $boardUserid != $member[id]) {
				$errmsg="�߸��� ��η� �����ϼ̽��ϴ�.(2)";
				echo "<html><head><title></title></head><body onload=\"alert('".$errmsg."');location.replace('board.php?pagetype=list&board=$board&s_check=$s_check&search=$search&block=$block&gotopage=$gotopage');\"></body></html>";exit;
			}

			if (($row2[passwd]!=$_POST["up_passwd"]) && ($setup[passwd]!=$_POST["up_passwd"])) {
				$errmsg="��й�ȣ�� ��ġ���� �ʽ��ϴ�.\\n\\n�ٽ� Ȯ�� �Ͻʽÿ�.";
				echo "<html><head><title></title></head><body onload=\"alert('".$errmsg."');history.go(-1);\"></body></html>";exit;
			}
		}

		/** ������ ���� ���� ó�� �߰� �κ� */
		if(preg_match_all('/\/data\/editor\/([a-zA-Z0-9\.]+)/',$row2['content'],$edtimg)){
			if(!preg_match_all('/\/data\/editor\/([a-zA-Z0-9\.]+)/',$up_memo,$edimg)) $edimg[1] = array();
			foreach($edtimg[1] as $cimg){
				if(!in_array($cimg,$edimg[1])) @unlink($_SERVER['DOCUMENT_ROOT'].'/data/editor/'.$cimg);
			}
		}

		if(preg_match_all('/\/data\/editor_temp\/([a-zA-Z0-9\.]+)/',$up_memo,$edimg)){
			foreach($edimg[1] as $timg){
				@rename($_SERVER['DOCUMENT_ROOT'].'/data/editor_temp/'.$timg,$_SERVER['DOCUMENT_ROOT'].'/data/editor/'.$timg);
			}
			$up_memo = str_replace('/data/editor_temp/','/data/editor/',$up_memo);
		}
		/** #������ ���� ���� ó�� �߰� �κ� */


		$up_name = addslashes($up_name);
		$up_subject = str_replace("<!","&lt;!",$up_subject);
		$up_subject = addslashes($up_subject);
		$up_memo = str_replace("<!","&lt;!",$up_memo);
		$up_memo = addslashes($up_memo);
		$up_userid = $_ShopInfo->getMemid();
		$up_usercel = $up_cel1."-".$up_cel2."-".$up_cel3;

		if (!$up_is_secret) $up_is_secret = 0;

		if($setup[use_html]=="N") $up_html="";

		$sql  = "UPDATE tblboard SET ";
		$sql .= "name			= '".$up_name."', ";
		$sql .= "email			= '".$up_email."', ";
		$sql .= "is_secret		= '".$up_is_secret."', ";
		$sql .= "use_html		= '".$up_html."', ";
		$sql .= "title			= '".$up_subject."', ";
		$sql .= "subCategory		= '".$subCategory."', ";
		if ($up_filename) {
			if(ProcessBoardFileModify($board,$up_filename,$row2[filename])=="SUCCESS") {
				$sql .= "filename	= '".$up_filename."', ";
			}
		}
		$sql .= "userid				= '".$up_userid."', ";
		$sql .= "usercel			= '".$up_usercel."', ";
		$sql .= "ip				= '".getenv("REMOTE_ADDR")."', ";
		$sql .= "content		= '".$up_memo."' ";
		if(strlen($up_url)>0) {
			$sql.= ", url			= '".$up_url."' ";
		}
		$sql .= "WHERE board='".$board."' AND num = $num ";
		$insert = mysql_query($sql,get_db_conn());

		if($insert) {
			echo("<meta http-equiv='Refresh' content='0; URL=board.php?pagetype=view&board=$board&num=$num&s_check=$s_check&search=$search&block=$block&gotopage=$gotopage'>");
			exit;
		} else {
			echo "
				<script>
				window.alert('�ۼ��� �� ������ �߻��߽��ϴ�.');
				</script>
			";
			reWriteForm();
			exit;
		}
	} else {
		if($member[admin]!="SU") {
			if (strlen($_POST["up_passwd"])==0 AND $boardUserid != $member[id]) {
				$errmsg="�߸��� ��η� �����ϼ̽��ϴ�.(3)";
				echo "<html><head><title></title></head><body onload=\"alert('".$errmsg."');location.replace('board.php?pagetype=list&board=$board&s_check=$s_check&search=$search&block=$block&gotopage=$gotopage');\"></body></html>";exit;
			}

			if(strlen($row2[passwd])==16) {
				$sql9 = "SELECT PASSWORD('".$_POST["up_passwd"]."') AS new_passwd";
				$result9 = mysql_query($sql9,get_db_conn());
				$row9=@mysql_fetch_object($result9);
				$new_passwd = $row9->new_passwd;
				@mysql_free_result($result);
			}

			if ($row2[passwd]!=$_POST["up_passwd"] && $setup[passwd]!=$_POST["up_passwd"]) {
				if(strlen($row2[passwd])!=16 || (strlen($row2[passwd])==16 && $row2[passwd]!=$new_passwd)) {
					$errmsg="��й�ȣ�� ��ġ���� �ʽ��ϴ�.\\n\\n�ٽ� Ȯ�� �Ͻʽÿ�.";
					echo "<html><head><title></title></head><body onload=\"alert('".$errmsg."');history.go(-1);\"></body></html>";exit;
				}
			}

			if(strlen($row2[passwd])==16 && $row2[passwd]==$new_passwd) {
				@mysql_query("UPDATE tblboard SET passwd='".$_POST["up_passwd"]."' WHERE board='".$row2[board]."' AND num='".$row2[num]."' ",get_db_conn());
				$row2[passwd]=$_POST["up_passwd"];
			}
		}

		if ($row2[filename]) {
			$thisBoard[filename] = "���������� ����Ϸ��� ����÷�� ���� ������.";
		}

		if ($mode == "reWrite") {
			$thisBoard[content]  = stripslashes(urldecode($thisBoard[content]));
			$thisBoard[title]  = stripslashes(urldecode($thisBoard[title]));
			$thisBoard[name]  = stripslashes(urldecode($thisBoard[name]));
		} else if (!$mode) {
			$thisBoard[pos] = $row2[pos];
			$thisBoard[is_secret] = $row2[is_secret];
			$thisBoard[name] = stripslashes($row2[name]);
			$thisBoard[passwd] = $row2[passwd];
			$thisBoard[email] = $row2[email];
			$thisBoard[cel] = explode("-",$row2[usercel]);
			$thisBoard[title] = stripslashes($row2[title]);
			$thisBoard[content] = stripslashes($row2[content]);
			$thisBoard[url] = $row2[url];

			if ($row2[use_html] == "1") $thisBoard[use_html] = "checked";
		}

		include ("top.php");

		if(strlen($row2[pridx])>0 && $row2[pridx]>0) {
			$sql = "SELECT a.productcode,a.productname,a.etctype,a.sellprice,a.quantity,a.tinyimage ";
			$sql.= "FROM tblproduct AS a ";
			$sql.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
			$sql.= "WHERE pridx='".$row2[pridx]."' ";
			$sql.= "AND (a.group_check='N' OR b.group_code='".$_ShopInfo->getMemgroup()."') ";
			$result=mysql_query($sql,get_db_conn());
			if($_pdata=mysql_fetch_object($result)) {
				INCLUDE "prqna_top.php";
			} else {
				$pridx="";
			}
			mysql_free_result($result);
		}


		include ($dir."/write.php");

		include ("bottom.php");
	}
} else if ($exec == "reply") {	//�亯
	//�������� �ٹ��� �Խ����� �亯���Ⱑ �ȵȴ�.
	if($setup[btype]!="L") {
		$errmsg="�� �Խ����� �亯���� ����� �������� �ʽ��ϴ�.";
		echo "<html><head><title></title></head><body onload=\"alert('".$errmsg."');history.go(-1);\"></body></html>";exit;
	}

	//�亯 ���� üũ
	if($member[grant_reply]!="Y") {
		if($setup[grant_reply]=="Y") {
			$errmsg="�Խ��� ȸ�� �α��� �� �亯���Ⱑ �����մϴ�.";
			echo "<html><head><title></title></head><body onload=\"alert('".$errmsg."');history.go(-1);\"></body></html>";exit;
		} else if($setup[grant_reply]=="A") {
			$errmsg="�Խ��� ������ �α��� �� �亯���Ⱑ �����մϴ�.";
			echo "<html><head><title></title></head><body onload=\"alert('".$errmsg."');history.go(-1);\"></body></html>";exit;
		}
	}

	$sql2 = "SELECT * FROM tblboard WHERE board='$board' AND num = $num ";
	$result2 = mysql_query($sql2,get_db_conn());
	$re_ok = mysql_num_rows($result2);

	if ((!$re_ok) || ($re_ok == -1)) {
		$errmsg="�亯�� �Խñ��� �����ϴ�.";
		echo "<html><head><title></title></head><body onload=\"alert('".$errmsg."');history.go(-1);\"></body></html>";exit;
	}

	$row2 = mysql_fetch_array($result2);

	if(($_POST[mode] == "up_result") && ($_POST[ins4e][mode] == "up_result") && ($_POST[up_subject] != "") && ($_POST[ins4e][up_subject] != "")) {
		// ======== thread, pos, depth ���� ========
		$sql = "UPDATE tblboard SET pos = pos+1 WHERE board='$board' AND thread=$row2[thread] AND pos>$row2[pos] ";
		$update = mysql_query($sql,get_db_conn());

		if($setup[use_html]=="N") $up_html="";

		//���Ͽ� ����
		$send_email = $up_email;
		$send_name = $up_name;
		$send_subject = $up_subject;
		$send_memo = stripslashes($up_memo);
		$send_filename= $up_filename;
		if (!$up_html) {
			$send_memo = nl2br(stripslashes($up_memo));
		}
		$send_date = date("Y-m-d H:i:s");

		$up_name = addslashes($up_name);
		$up_subject = str_replace("<!","&lt;!",$up_subject);
		$up_subject = addslashes($up_subject);
		$up_memo = str_replace("<!","&lt;!",$up_memo);
		$up_memo = addslashes($up_memo);
		$up_userid = $_ShopInfo->getMemid();
		$up_usercel = $up_cel1."-".$up_cel2."-".$up_cel3;

		if (!$up_is_secret) $up_is_secret = 0;

		if(ProcessBoardFileIn($board,$up_filename)!="SUCCESS") {
			$up_filename="";
		}

		$sql  = "INSERT tblboard SET ";
		$sql .= "board				= '".$board."', ";
		$sql .= "subCategory		= '".$subCategory."', ";
		$sql .= "num				= '', ";
		$sql .= "thread				= '".$row2[thread]."', ";
		$sql .= "pos				= $row2[pos]+1, ";
		$sql .= "depth				= $row2[depth]+1, ";
		$sql .= "prev_no			= '".$row2[prev_no]."', ";
		$sql .= "next_no			= '".$row2[next_no]."', ";
		$sql .= "pridx				= '".$row2[pridx]."', ";
		$sql .= "name				= '".$up_name."', ";
		$sql .= "passwd				= '".$up_passwd."', ";
		$sql .= "email				= '".$up_email."', ";
		$sql .= "userid				= '".$up_userid."', ";
		$sql .= "usercel			= '".$up_usercel."', ";
		$sql .= "is_secret			= '".$up_is_secret."', ";
		$sql .= "use_html			= '".$up_html."', ";
		$sql .= "title				= '".$up_subject."', ";
		$sql .= "filename			= '".$up_filename."', ";
		$sql .= "writetime			= '".time()."', ";
		$sql .= "ip					= '".getenv("REMOTE_ADDR")."', ";
		$sql .= "access				= '0', ";
		$sql .= "total_comment		= '0', ";
		$sql .= "content			= '".$up_memo."', ";
		$sql .= "notice				= '0', ";
		$sql .= "deleted			= '0' ";
		
		$insert = mysql_query($sql,get_db_conn());

		if($insert) {
			$qry = "SELECT LAST_INSERT_ID() ";
			$res = mysql_fetch_row(mysql_query($qry,get_db_conn()));
			$thisNum = $res[0];

			// ===== �������̺��� �Խñۼ� update =====
			$sql3 = "UPDATE tblboardadmin SET total_article=total_article+1 WHERE board='".$board."' ";
			$update = mysql_query($sql3,get_db_conn());

			if (strlen($row2[email])>0) {
				INCLUDE "SendForm.inc.php";

				$title = $send_subject;
				$message = GetHeader() . GetContent($send_name, $send_email, $send_subject, $send_memo,$send_date,$send_filename,$setup[board_name], "re", $row2[name]) . GetFooter();

				$tmp_admin_mail_list = split(",",$setup[admin_mail]);

				sendMailForm($send_name,$send_email,$message,$bodytext,$mailheaders);

				if (ismail($row2[email])) {
					mail($row2[email], $title, $bodytext, $mailheaders);
				}
			}

			//�Խ��� �۵�� SMS�߼�
			$sqlsms = "SELECT * FROM tblsmsinfo ";
			$resultsms= mysql_query($sqlsms,get_db_conn());
			if($rowsms=@mysql_fetch_object($resultsms)){
				function getStringCut($strValue,$lenValue)
				{
					preg_match('/^([\x00-\x7e]|.{2})*/', substr($strValue,0,$lenValue), $retrunValue);
					return $retrunValue[0];
				}

				$sms_id=$rowsms->id;
				$sms_authkey=$rowsms->authkey;
				$fromtel=$rowsms->return_tel;
			}

			if($rowsms->admin_board == "Y"){

				//�Խ��� �۵�� SMS�߼�
				if ( ($setup[use_admin_sms]=="Y") ) {

					$totellist = ( strlen($setup[admin_sms]) > 0 ) ? $setup[admin_sms] : $rowsms->admin_tel;

					$smsboardname=str_replace("\\n"," ",str_replace("\\r","",strip_tags($setup[board_name])));
					$smsboardsubject=str_replace("\\n"," ",str_replace("\\r","",strip_tags(str_replace("&lt;!","<!",stripslashes($up_subject)))));

					$smsmsg="]�űԱ��� ".getStringCut($smsboardsubject,20)."���� ��ϵǾ����ϴ�.";
					$smsmsg=getStringCut($setup[board_name],80-strlen($smsmsg)).$smsmsg;

					$etcmsg="�Խ��� �۵�� �޼���(������)";
					if($rowsms->sleep_time1!=$rowsms->sleep_time2){
						$date="0";
						$time = date("Hi");
						if($rowsms->sleep_time2<"12" && $time<=substr("0".$rowsms->sleep_time2,-2)."59") $time+=2400;
						if($rowsms->sleep_time2<"12" && $rowsms->sleep_time1>$rowsms->sleep_time2) $rowsms->sleep_time2+=24;

						if($time<substr("0".$rowsms->sleep_time1,-2)."00" || $time>=substr("0".$rowsms->sleep_time2,-2)."59"){
							if($time<substr("0".$rowsms->sleep_time1,-2)."00") $day = date("d");
							else $day=date("d")+1;
							$date = date("Y-m-d H:i:s",mktime($rowsms->sleep_time1,0,0,date("m"),$day,date("Y")));
						}
					}
					$temp=SendSMS($sms_id, $sms_authkey, $totellist, "", $fromtel, $date, $smsmsg, $etcmsg);
					mysql_free_result($resultsms);
				}
			}
			//�亯�� SMS �߼�
			if($setup[reply_sms] == "Y" && strlen(str_replace("-","",$row2[usercel]))>0){

				$smsboardname=str_replace("\\n"," ",str_replace("\\r","",strip_tags($setup[board_name])));
				$smsboardsubject=str_replace("\\n"," ",str_replace("\\r","",strip_tags(str_replace("&lt;!","<!",stripslashes($up_subject)))));

				$smsmsg="[".$_data->shopname."] ".$setup[board_name]."�� �ø��� �ۿ� �亯�� ��ϵǾ����ϴ�.";

				$etcmsg="�Խ��� �亯��� �޼���";
				if($rowsms->sleep_time1!=$rowsms->sleep_time2){
					$date="0";
					$time = date("Hi");
					if($rowsms->sleep_time2<"12" && $time<=substr("0".$rowsms->sleep_time2,-2)."59") $time+=2400;
					if($rowsms->sleep_time2<"12" && $rowsms->sleep_time1>$rowsms->sleep_time2) $rowsms->sleep_time2+=24;

					if($time<substr("0".$rowsms->sleep_time1,-2)."00" || $time>=substr("0".$rowsms->sleep_time2,-2)."59"){
						if($time<substr("0".$rowsms->sleep_time1,-2)."00") $day = date("d");
						else $day=date("d")+1;
						$date = date("Y-m-d H:i:s",mktime($rowsms->sleep_time1,0,0,date("m"),$day,date("Y")));
					}
				}
				$temp=SendSMS($sms_id, $sms_authkey, $row2[usercel], "", $fromtel, $date, $smsmsg, $etcmsg);
				mysql_free_result($resultsms);
			}
			echo("<meta http-equiv='Refresh' content='0; URL=board.php?pagetype=list&board=$board&s_check=$s_check&search=$search&block=$block&gotopage=$gotopage'>");
			exit;
		} else {
			echo "
				<script>
				window.alert('�۴亯 �� ������ �߻��߽��ϴ�.');
				</script>
			";
			reWriteForm();
			exit;
		}
	} else {

		if ($mode == "reWrite") {
			$thisBoard[content]  = stripslashes(urldecode($thisBoard[content]));
			if ($setup[use_related] == "1") {
				$thisBoard[content] = str_replace("cid:",$attachFileUrl,$thisBoard[content]);
			}
			$thisBoard[title]  = stripslashes(urldecode($thisBoard[title]));
			$thisBoard[summary]  = stripslashes(urldecode($thisBoard[summary]));
			$thisBoard[name]  = stripslashes(urldecode($thisBoard[name]));
		} else if (!$mode) {
			$thisBoard[pos] = $row2[pos];
			$thisBoard[is_secret] = $row2[is_secret];
			$thisBoard[use_anonymouse] = $row2[use_anonymouse];
			$thisBoard[sitelink1] = $row2[sitelink1];
			$thisBoard[sitelink2] = $row2[sitelink2];
			$thisBoard[name] = $member[name];
			$thisBoard[email] = $member[email];
			$thisBoard[category] = $row2[category];

			$thisBoard[title] = stripslashes($row2[title]);
			$thisBoard[summary] = stripslashes($row2[summary]);
			$thisBoard[content] = stripslashes($row2[content]);

			$thisBoard[title]    = "[�亯]" . $thisBoard[title];

			if ($setup[use_related] == "1") {
				$thisBoard[content] = str_replace("cid:",$attachFileUrl,$thisBoard[content]);
				if ($wtype == "special" && $row2[use_html] != "1") {
					$thisBoard[content] = nl2br($thisBoard[content]);
				}

				if ($wtype == "special" || $row2[use_related] == "1") {
					$tmp_content = $thisBoard[content];
					$thisBoard[content] = "<br><br><blockquote type=cite style=\"border-left: #000000 solid 2px; margin-left: 5px; padding-left: 5px\">";
					$thisBoard[content] .= "<div style=\"font: 10pt arial\">-------- \n\n\n'".stripslashes($row2[name])."'���� ���ű� --------\n";
					$thisBoard[content] .= "<div><br></div>\n";
					$thisBoard[content] .= $tmp_content."</blockquote>";
				} else {
					$thisBoard[content]  = "\n\n\n'".stripslashes($row2[name])."'���� ���ű�\n";
					$thisBoard[content] .= "------------------------------------\n";
					$thisBoard[content] .= ">" . str_replace(chr(10), chr(10).">", $row2[content]) . "\n";
					$thisBoard[content] .= "------------------------------------\n";
				}
			} else {
				$thisBoard[content]  = "\n\n\n'".stripslashes($row2[name])."'���� ���ű�\n";
				$thisBoard[content] .= "------------------------------------\n";
				$thisBoard[content] .= ">" . str_replace(chr(10), chr(10).">", $row2[content]) . "\n";
				$thisBoard[content] .= "------------------------------------\n";
			}
		}

		if($setup[use_html]!="N") $thisBoard['content'] = '';
		include ("top.php");

		if(strlen($row2[pridx])>0 && $row2[pridx]>0) {
			$sql = "SELECT a.productcode,a.productname,a.etctype,a.sellprice,a.quantity,a.tinyimage ";
			$sql.= "FROM tblproduct AS a ";
			$sql.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
			$sql.= "WHERE pridx='".$row2[pridx]."' ";
			$sql.= "AND (a.group_check='N' OR b.group_code='".$_ShopInfo->getMemgroup()."') ";
			$result=mysql_query($sql,get_db_conn());
			if($_pdata=mysql_fetch_object($result)) {
				INCLUDE "prqna_top.php";
			} else {
				$pridx="";
			}
			mysql_free_result($result);
		}

		include ($dir."/write.php");
		include ("bottom.php");
	}
} else {
	$errmsg="�߸��� �������Դϴ�.";
	echo "<html><head><title></title></head><body onload=\"alert('".$errmsg."');history.go(-1);\"></body></html>";exit;
}


?>