<?
if(substr(getenv("SCRIPT_NAME"),-11)=="/delete.php"){
	header("HTTP/1.0 404 Not Found");
	exit;
}

include ("head.php");

if($setup[use_article_care]=="Y" && $member[admin]!="SU") {
	$errmsg="�ش� �Խ����� �Խñ� ��ȣ ����� ������̹Ƿ� ������ �Ұ����մϴ�.\\n\\n���θ� ��ڿ��� �����Ͻñ� �ٶ��ϴ�.";
	echo "<html><head><title></title></head><body onload=\"alert('".$errmsg."');history.go(-1);\"></body></html>";exit;
}

$qry  = "SELECT * FROM tblboard WHERE board='".$board."' AND num='".$num."' ";
$del_result = mysql_query($qry,get_db_conn());
$del_ok = mysql_num_rows($del_result);

if ((!$del_ok) || ($del_ok == -1)) {
	$errmsg="������ ���� �����ϴ�.\\n\\n�ٽ� Ȯ�� �Ͻʽÿ�.";
	echo "<html><head><title></title></head><body onload=\"alert('".$errmsg."');location.replace('board.php?pagetype=list&board=$board&s_check=$s_check&search=$search&block=$block&gotopage=$gotopage');\"></body></html>";exit;
} else {
	$del_row = mysql_fetch_array($del_result);

	if ($mode == "delete") {
		if($member[admin]!="SU") {
			if (strlen($_POST["up_passwd"])==0) {
				$errmsg="�߸��� ��η� �����ϼ̽��ϴ�.(1)";
				echo "<html><head><title></title></head><body onload=\"alert('".$errmsg."');location.replace('board.php?pagetype=list&board=$board&s_check=$s_check&search=$search&block=$block&gotopage=$gotopage');\"></body></html>";exit;
			}

			if ((crypt($del_row[passwd],"passwd") != $_POST["up_passwd"]) && crypt($setup[passwd],"passwd") != $_POST["up_passwd"]) {
				$errmsg="��й�ȣ�� ��ġ���� �ʽ��ϴ�.\\n\\n�ٽ� Ȯ�� �Ͻʽÿ�.";
				echo "<html><head><title></title></head><body onload=\"alert('".$errmsg."');location.replace('board.php?pagetype=view&board=$board&num=$num&s_check=$s_check&search=$search&block=$block&gotopage=$gotopage');\"></body></html>";exit;
			}
		}

		/** ������ ���� ���� ó�� �߰� �κ� */
		if(preg_match_all('/\/data\/editor\/([a-zA-Z0-9\.]+)/',$del_row['content'],$edtimg)){
			foreach($edtimg[1] as $timg){
				@unlink($_SERVER['DOCUMENT_ROOT'].'/data/editor/'.$timg);
			}
		}
		/** #������ ���� ���� ó�� �߰� �κ� */

		if ($del_row[pos] <> 0) {
			// �Խù��� ��������
			$sql  = "DELETE FROM tblboard WHERE board='".$board."' AND num=".$num." ";
			$isUpdate = true;
		} else {
			$sql2  = "SELECT COUNT(*) FROM tblboard WHERE board='".$board."' AND thread=".$del_row[thread]." ";
			$result2 = mysql_query($sql2,get_db_conn());
			$deleteTotal = mysql_result($result2,0,0);
			mysql_free_result($result2);

			if ($deleteTotal == 1) {
				$sql  = "DELETE FROM tblboard WHERE board='".$board."' AND num = ".$num." ";
				$isUpdate = true;
			} else {
				$delMsg = "������ �Ǵ� �ۼ��ڿ� ���� �����Ǿ����ϴ�.";
				$sql  = "UPDATE tblboard SET ";
				$sql .= "prev_no = 0, ";
				$sql .= "next_no = 0, ";
				$sql .= "use_html = '0', ";
				$sql .= "title = '".$delMsg."', ";
				$sql .= "filename = '', ";
				$sql .= "total_comment = 0, ";
				$sql .= "content = '".$delMsg."', ";
				$sql .= "notice = '0', ";
				$sql .= "deleted = '1' ";
				$sql .= "WHERE board='".$board."' AND num=".$num." ";
			}
		}
		$delete = mysql_query($sql,get_db_conn());

		if($delete) {
			if($del_row[prev_no]) mysql_query("UPDATE tblboard SET next_no='".$del_row[next_no]."' WHERE board='".$board."' AND next_no='".$del_row[num]."'",get_db_conn());
			if($del_row[next_no]) mysql_query("UPDATE tblboard SET prev_no='".$del_row[prev_no]."' WHERE board='".$board."' AND prev_no='".$del_row[num]."'",get_db_conn());

			// ===== �������̺��� �Խñۼ� update =====
			unset($in_max_qry);
			unset($in_total_qry);
			if ($del_row[pos] == 0) {
				if ($del_row[prev_no] == 0) {
					$in_max_qry = "max_num = '$del_row[next_no]' ";
				}
			}
			if ($isUpdate) {
				$in_total_qry = "total_article = total_article - 1 ";
			}

			$sql3 = "UPDATE tblboardadmin SET ";
			if ($in_max_qry) $sql3.= $in_max_qry;
			if ($in_max_qry && $in_total_qry) $sql3.= ",".$in_total_qry;
			else if (!$in_max_qry && $in_total_qry) $sql3.= $in_total_qry;
			$sql3.= "WHERE board='".$board."' ";

			if ($in_max_qry || $in_total_qry) $update = mysql_query($sql3,get_db_conn());

			if ($setup[use_comment]=="Y" && $del_row[total_comment] > 0) {
				@mysql_query("DELETE FROM tblboardcomment WHERE board='".$board."' AND parent = '".$del_row[num]."'",get_db_conn());
			}

			if($del_row[filename]) {
				$filedel=ProcessBoardFileDel($board,$del_row[filename]);
			}

			echo("<meta http-equiv='Refresh' content='0; URL=board.php?pagetype=list&board=$board&block=$block&gotopage=$gotopage&search=$search&s_check=$s_check'>");
			exit;
		} else {
			$errmsg="�ۻ��� �� ������ �߻��߽��ϴ�.";
			echo "<html><head><title></title></head><body onload=\"alert('".$errmsg."');location.replace('board.php?pagetype=list&board=$board&s_check=$s_check&search=$search&block=$block&gotopage=$gotopage');\"></body></html>";exit;
		}

	} else {
		if ($member[admin]!="SU") {
			if (strlen($_POST["up_passwd"])==0 AND $boardUserid != $member[id]) {
				$errmsg="�߸��� ��η� �����ϼ̽��ϴ�.(2)";
				echo "<html><head><title></title></head><body onload=\"alert('".$errmsg."');location.replace('board.php?pagetype=list&board=$board&s_check=$s_check&search=$search&block=$block&gotopage=$gotopage');\"></body></html>";exit;
			}

			if(strlen($del_row[passwd])==16) {
				$sql9 = "SELECT PASSWORD('".$_POST["up_passwd"]."') AS new_passwd";
				$result9 = mysql_query($sql9,get_db_conn());
				$row9=@mysql_fetch_object($result9);
				$new_passwd = $row9->new_passwd;
				@mysql_free_result($result);
			}

			if ($del_row[passwd]!=$_POST["up_passwd"] && $setup[passwd]!=$_POST["up_passwd"]) {
				if(strlen($del_row[passwd])!=16 || (strlen($del_row[passwd])==16 && $del_row[passwd]!=$new_passwd)) {
					$errmsg="��й�ȣ�� ��ġ���� �ʽ��ϴ�.\\n\\n�ٽ� Ȯ�� �Ͻʽÿ�.";
					echo "<html><head><title></title></head><body onload=\"alert('".$errmsg."');location.replace('board.php?pagetype=view&board=$board&num=$num&s_check=$s_check&search=$search&block=$block&gotopage=$gotopage');\"></body></html>";exit;
				}
			}

			if(strlen($del_row[passwd])==16 && $del_row[passwd]==$new_passwd) {
				@mysql_query("UPDATE tblboard SET passwd='".$_POST["up_passwd"]."' WHERE board='".$del_row[board]."' AND num='".$del_row[num]."' ",get_db_conn());
				$del_row[passwd]=$_POST["up_passwd"];
			}
		}

		$thisBoard[name] = stripslashes($del_row[name]);
		$thisBoard[email] = $del_row[email];
		$thisBoard[title] = stripslashes($del_row[title]);

		include ("top.php");

		include ($dir."/delete.php");

		include ("bottom.php");
	}
}
?>
