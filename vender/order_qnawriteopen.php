<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");
INCLUDE ("access.php");

include ($Dir.BoardDir."file.inc.php");

//��ǰQNA �Խ��� ���翩�� Ȯ�� �� �������� Ȯ��
$prqnaboard=getEtcfield($_venderdata->etcfield,"PRQNA");
if(strlen($prqnaboard)>0) {
	$sql = "SELECT * FROM tblboardadmin WHERE board='".$prqnaboard."' ";
	$result=mysql_query($sql,get_db_conn());
	$qnasetup=mysql_fetch_object($result);
	mysql_free_result($result);

	$qnasetup->btype=substr($qnasetup->board_skin,0,1);
	$qnasetup->max_filesize=$qnasetup->max_filesize*(1024*100);
	if($qnasetup->use_hidden=="Y") unset($qnasetup);
}

if(strlen($qnasetup->board)<=0) {
	echo "<html></head><body onload=\"alert('���θ� Q&A�Խ��� ������ �ȵǾ����ϴ�.\\n\\n���θ��� �����Ͻñ� �ٶ��ϴ�.');window.close();\"></body></html>";exit;
}

function writeSecret($exec,$is_secret,$pos) {
	global $qnasetup;

	if ($exec == "reply") $disabled = "disabled";
	if ($exec == "modify" && $pos != "0") $disabled = "disabled";

	if($qnasetup->use_lock=="A") {
		echo "<select name=tmp_is_secret disabled>
			<option value=\"0\">������</option>
			<option value=\"1\" selected>��ݻ��</option>
			</select> &nbsp; <FONT COLOR=\"red\">�ڵ���ݱ��</FONT>
		";
	} else if($qnasetup->use_lock=="Y") {
		${"select".$is_secret} = "selected";
		echo "<select name=tmp_is_secret $disabled>
			<option value=\"0\" $select0>������</option>
			<option value=\"1\" $select1>��ݻ��</option>
			</select>
		";
	}
}



$exec=$_POST["exec"];
$num=$_POST["num"];
$board=$qnasetup->board;

if($exec=="modify" || $exec=="reply") {
	$qry = "WHERE a.board='".$qnasetup->board."' ";
	$qry.= "AND a.pridx=b.pridx AND b.vender='".$_VenderInfo->getVidx()."' ";

	$sql = "SELECT a.*, b.productcode,b.productname,b.tinyimage,b.sellprice,b.selfcode ";
	$sql.= "FROM tblboard a, tblproduct b ".$qry." ";
	$sql.= "AND a.num='".$num."' ";
	$result=mysql_query($sql,get_db_conn());
	if(!$qnadata=mysql_fetch_object($result)) {
		if($exec=="modify") $errmsg="������ �Խñ��� �������� �ʽ��ϴ�.";
		else if($exec=="reply") $errmsg="�亯�� �Խñ��� �������� �ʽ��ϴ�.";
		echo "<html></head><body onload=\"alert('".$errmsg."');window.close();\"></body></html>";exit;
	}
	mysql_free_result($result);
}

if($exec=="modify") {
	if($qnasetup->use_article_care=="Y") {
		$errmsg="��ǰQ&A �Խ����� �Խñ� ��ȣ ����� ������̹Ƿ� ������ �Ұ����մϴ�.\\n\\n���θ� ��ڿ��� �����Ͻñ� �ٶ��ϴ�.";
		echo "<html><head><title></title></head><body onload=\"alert('".$errmsg."');window.close();\"></body></html>";exit;
	}

	if(($_POST[mode]=="up_result") && strlen($_POST[up_subject])>0) {
		if (strlen($_POST["up_passwd"])==0) {
			$errmsg="�߸��� ��η� �����ϼ̽��ϴ�.";
			echo "<html><head><title></title></head><body onload=\"alert('".$errmsg."');window.close();\"></body></html>";exit;
		}

		if ($qnadata->passwd!=$_POST["up_passwd"]) {
			$errmsg="��й�ȣ�� ��ġ���� �ʽ��ϴ�.\\n\\n�ٽ� Ȯ�� �Ͻʽÿ�.";
			echo "<html><head><title></title></head><body onload=\"alert('".$errmsg."');history.go(-1);\"></body></html>";exit;
		}

		$up_name = addslashes($up_name);
		$up_subject = str_replace("<!","&lt;!",$up_subject);
		$up_subject = addslashes($up_subject);
		$up_memo = str_replace("<!","&lt;!",$up_memo);
		$up_memo = addslashes($up_memo);

		if (!$up_is_secret) $up_is_secret = 0;

		if($setup[use_html]=="N") $up_html="";

		$sql  = "UPDATE tblboard SET ";
		$sql .= "name			= '".$up_name."', ";
		$sql .= "email			= '".$up_email."', ";
		$sql .= "is_secret		= '".$up_is_secret."', ";
		$sql .= "use_html		= '".$up_html."', ";
		$sql .= "title			= '".$up_subject."', ";
		if ($up_filename) {
			if(ProcessBoardFileModify($board,$up_filename,$qnadata->filename)=="SUCCESS") {
				$sql .= "filename	= '".$up_filename."', ";
			}
		}
		$sql .= "ip				= '".getenv("REMOTE_ADDR")."', ";
		$sql .= "content		= '".$up_memo."' ";
		$sql .= "WHERE board='".$board."' AND num = $num ";
		$insert = mysql_query($sql,get_db_conn());

		if($insert) {
			echo "<html><head><title></title></head><body onload=\"opener.viewArticle(".$num.");window.close();\"></body></html>";exit;
		} else {
			echo "<html><head><title></title></head><body onload=\"alert('�� ������ ������ �߻��Ͽ����ϴ�.');history.go(-1);\"></body></html>";exit;
		}
	} else {
		if (strlen($_POST["up_passwd"])==0) {
			$errmsg="�߸��� ��η� �����ϼ̽��ϴ�.";
			echo "<html><head><title></title></head><body onload=\"alert('".$errmsg."');window.close();\"></body></html>";exit;
		}

		if ($qnadata->passwd!=$_POST["up_passwd"]) {
			$errmsg="��й�ȣ�� ��ġ���� �ʽ��ϴ�.\\n\\n�ٽ� Ȯ�� �Ͻʽÿ�.";
			echo "<html><head><title></title></head><body onload=\"alert('".$errmsg."');history.go(-1);\"></body></html>";exit;
		}

		if (strlen($qnadata->filename)>0) {
			$thisBoard[filename] = "���������� ����Ϸ��� ����÷�� ���� ������.";
		}

		$thisBoard[pos] = $qnadata->pos;
		$thisBoard[is_secret] = $qnadata->is_secret;
		$thisBoard[name] = stripslashes($qnadata->name);
		$thisBoard[passwd] = $qnadata->passwd;
		$thisBoard[email] = $qnadata->email;
		$thisBoard[title] = stripslashes($qnadata->title);
		$thisBoard[content] = stripslashes($qnadata->content);

		if ($qnadata->use_html == "1") $thisBoard[use_html] = "checked";

		//������
		include ("order_qnawriteopen.inc.php");
	}
} else if($exec=="reply") {
	if($qnasetup->btype!="L") {
		$errmsg="�� �Խ����� �亯���� ����� �������� �ʽ��ϴ�.\\n\\n���θ��� �����Ͻñ� �ٶ��ϴ�.";
		echo "<html><head><title></title></head><body onload=\"alert('".$errmsg."');window.close();\"></body></html>";exit;
	}

	if($_POST[mode] == "up_result" && strlen($_POST[up_subject])>0) {		
		// ======== thread, pos, depth ���� ========
		$sql = "UPDATE tblboard SET pos = pos+1 WHERE board='".$board."' AND thread='".$qnadata->thread."' ";
		$sql.= "AND pos>".$qnadata->pos." ";
		$update = mysql_query($sql,get_db_conn());

		if($qnasetup->use_html=="N") $up_html="";

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

		if (!$up_is_secret) $up_is_secret = 0;

		if(ProcessBoardFileIn($board,$up_filename)!="SUCCESS") {
			$up_filename="";
		}

		$sql  = "INSERT tblboard SET ";
		$sql .= "board				= '".$board."', ";
		$sql .= "num				= '', ";
		$sql .= "thread				= '".$qnadata->thread."', ";
		$sql .= "pos				= '".($qnadata->pos+1)."', ";
		$sql .= "depth				= '".($qnadata->depth+1)."', ";
		$sql .= "prev_no			= '".$qnadata->prev_no."', ";
		$sql .= "next_no			= '".$qnadata->next_no."', ";
		$sql .= "pridx				= '".$qnadata->pridx."', ";
		$sql .= "name				= '".$up_name."', ";
		$sql .= "passwd				= '".$up_passwd."', ";
		$sql .= "email				= '".$up_email."', ";
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
				INCLUDE ($Dir.BoardDir."SendForm.inc.php");

				$title = $send_subject;
				$message = GetHeader() . GetContent($send_name, $send_email, $send_subject, $send_memo,$send_date,$send_filename,$setup[board_name]) . GetFooter();

				$tmp_admin_mail_list = split(",",$qnasetup->admin_mail);

				sendMailForm($send_name,$send_email,$message,$bodytext,$mailheaders);

				if (ismail($qnadata->email)) {
					mail($qnadata->email, $title, $bodytext, $mailheaders);
				}
			}

			echo "<html><head><title></title></head><body onload=\"opener.listArticle();window.close();\"></body></html>";exit;
		} else {
			echo "<html><head><title></title></head><body onload=\"alert('�亯�� ������ �߻��Ͽ����ϴ�.');history.go(-1);\"></body></html>";exit;
		}
	} else {
		$thisBoard[pos] = $qnadata->pos;
		$thisBoard[is_secret] = $qnadata->is_secret;
		$thisBoard[use_anonymouse] = $qnadata->use_anonymouse;
		$thisBoard[sitelink1] = $qnadata->sitelink1;
		$thisBoard[sitelink2] = $qnadata->sitelink2;
		$thisBoard[name] = "";
		$thisBoard[email] = "";
		$thisBoard[category] = $qnadata->category;

		$thisBoard[title] = stripslashes($qnadata->title);
		$thisBoard[summary] = stripslashes($qnadata->summary);

		$thisBoard[content] = stripslashes($qnadata->content);

		$thisBoard[title]    = "[�亯]" . $thisBoard[title];

		$thisBoard[content]  = "\n\n\n'".stripslashes($qnadata->name)."'���� ���ű�\n";
		$thisBoard[content] .= "------------------------------------\n";
		$thisBoard[content] .= ">" . str_replace(chr(10), chr(10).">", $qnadata->content) . "\n";
		$thisBoard[content] .= "------------------------------------\n";
		
		//������
		include ("order_qnawriteopen.inc.php");
	}
} else {
	echo "<html><head><title></title></head><body onload=\"alert('�߸��� ��η� �����ϼ̽��ϴ�.');window.close();\"></body></html>";exit;
}