<?
if(substr(getenv("SCRIPT_NAME"),-15)=="/name_check.php"){
	header("HTTP/1.0 404 Not Found");
	exit;
}

function name_check($name, $jumin1, $jumin2, $id, $pw) {
	global $Dir, $history;
	if(strlen($history)==0) $history="-1";
	$name_result=0;
	$file_jumin = $Dir.DataDir."cache/name/CACHE_$name"."_".md5("$jumin1$jumin2");
	$file_denyY = $Dir.DataDir."cache/name/CACHE_Y_".getenv("REMOTE_ADDR");
	$file_denyN = $Dir.DataDir."cache/name/CACHE_N_".getenv("REMOTE_ADDR");

	if(strpos(" ".$file_jumin,"..")==true) $file_jumin=str_replace("..","",$file_jumin);
	if(strpos(" ".$file_denyY,"..")==true) $file_denyY=str_replace("..","",$file_denyY);
	if(strpos(" ".$file_denyN,"..")==true) $file_denyN=str_replace("..","",$file_denyN);

	include($Dir."lib/cache_namecheck.php");

	if ($name_result==119) {
		echo "<script>alert('[".getenv("REMOTE_ADDR")."] IP���� �ٷ� �Ǹ����� �õ��� �ֽ��ϴ�.\\n�Ǹ����� ���� ������ ��ȣ�ϱ����� �αױ���ϰ�, �Ǹ������� �ź��մϴ�.');history.go(".$history.");</script>";
		exit;
	}

	if ($name_result!=1) {
		$host=getenv("HTTP_HOST");
		$port=80;
		$path="/".RootPath.FrontDir."getnamecheck.php";
		$name = ereg_replace(" ","",$name);

		$query="id=".$id."&pw=".$pw."&name=".$name."&jumin1=".$jumin1."&jumin2=".$jumin2;
		$fp = @fsockopen($host, $port, &$errno, &$errstr, 5);
		if(!$fp) {
			$strLine = " result value=5";
			@flush();
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
			flush();
			while($currentHeader = fgets($fp,4096)) {
				if($currentHeader == "\r\n") {
					break;
				}
			}

			$strLine = "";
			while(!feof($fp)) {
				$strLine .= fgets($fp, 4096);
			}
		}

		$name_result = substr($strLine,strpos($strLine,"result value=")+13,1);
		if ($name_result==1 && strlen($file_jumin)>0) {
			$fp = @fopen("$file_jumin","w");
			@fputs($fp,"OK");
			@fclose($fp);
		}

		if($name_result==1 || $name_result==2 || $name_result==4) {
			if(file_exists("$file_denyY")==true) {
				$fp = @fopen("$file_denyY","r");
				$temp = @fgets($fp,1024);
				if (strlen($temp)==0) $temp=0;
				@fclose($fp);

				$filetime = filemtime($file_denyY);
				$timegan=(time()-$filetime);
				$temp ++;
			} else {
				$temp=1;
			}
			$fp = @fopen("$file_denyY","w");@fputs($fp,$temp);@fclose($fp);
		}
	}
	
	if($name_result!=1) {
?>
		<html>
		<head>
			<title>�Ǹ�����</title>
			<style>
			td {font-family:Tahoma;color:666666;font-size:9pt;}
			tr {font-family:Tahoma;color:666666;font-size:9pt;}
			BODY,TD,SELECT,DIV,form,TEXTAREA,center,option,pre,blockquote {font-family:Tahoma;color:000000;font-size:9pt;}

			A:link{color:#5A595A;text-decoration:none;}
			A:visited{color:#5A595A;text-decoration:none;}
			A:active{color:#5A595A;text-decoration:none;}
			A:hover{color:#5A595A;text-decoration:underline;}
			</style>
		</head>
		<body topmargin="0" leftmargin="0" rightmargin="0" marginheight="0" marginwidth="0" background="<?=$Dir.AdultDir?>images/errobg.gif">
		<table cellpadding="0" cellspacing="0" width="100%" height="100%">
		<tr>
			<td width="100%" height="100%" align="center">
			<table cellpadding="0" cellspacing="0">
			<tr>
				<td><IMG SRC="<?=$Dir.AdultDir?>images/erro_top.gif" border="0"></td>
			</tr>
			<tr>
				<td background="<?=$Dir.AdultDir?>images/erro_07bg.gif">
				<table align="center" cellpadding="0" cellspacing="0">
				<tr>
					<td><img src="<?=$Dir.AdultDir?>images/erro_01.gif" border="0"><br></td>
				</tr>
				<tr>
					<td>
					<table cellpadding="0" cellspacing="0">
					<tr>
						<td style="padding-left:30px;line-height:110%;">
<?
						if($name_result==2) echo "<b><span style=\"font-size:9pt; letter-spacing:-0.5pt;\"><font color=\"#FF4518\">�Է��Ͻ� ����� �ֹι�ȣ�� ��ġ���� �ʽ��ϴ�.</font><br>�ٽ��ѹ� ��Ȯ�� �Է��Ͽ� �ֽñ� �ٶ��ϴ�.</span></b>";
						else if($name_result==3) echo "<b><span style=\"font-size:9pt; letter-spacing:-0.5pt;\"><font color=\"#FF4518\">�Է��Ͻ� ����� �ֹι�ȣ�� ����ſ��򰡿��� �����ϴ� �Ǹ�DB�� �����Ͱ� �����ϴ�.</font><br>�ٽ��ѹ� ��Ȯ�� �Է��Ͽ� �ֽñ� �ٶ��ϴ�.</span></b>";
						else if($name_result==4) echo "<b><span style=\"font-size:9pt; letter-spacing:-0.5pt;\"><font color=\"#FF4518\">�ֹι�ȣ �����Դϴ�.</font><br>�ٽ��ѹ� ��Ȯ�� �Է��Ͽ� �ֽñ� �ٶ��ϴ�.</span></b>";
						else if($name_result==5) echo "<b><span style=\"font-size:9pt; letter-spacing:-0.5pt;\"><font color=\"#FF4518\">���� ����ſ��� �����ͺ��̽��� ������ ��Ȱ���� ���մϴ�.</font><br>��� �� �ٽ� �����ϼ���. (����ſ������� ���� : 02-512-1300)</span></b>";
						else if($name_result==7) echo "<b><span style=\"font-size:9pt; letter-spacing:-0.5pt;\"><font color=\"#FF4518\">������ ���ǵ�������� ��û�ϼ̽��ϴ�.</font><br><a href=http://www.siren24.com target=_blank><u>www.siren24.com</u></a>���� �Ͻ������ϼž� �մϴ�.</span></b>";
						else if($name_result!=1) echo "<b><span style=\"font-size:9pt; letter-spacing:-0.5pt;\"><font color=\"#FF4518\">���� ����ſ��� �����ͺ��̽��� ������ ��Ȱ���� ���մϴ�.</font><br>��� �� �ٽ� �����ϼ���. (���� : 02-512-1300)</span></b>";
?>
						</td>
					</tr>
					<tr>
						<td height="5"></td>
					</tr>
					<tr>
						<td style="padding-left:30px;">
<?
						if($name_result==2) echo "<font color=\"#FF4518\"><b>���� ����ġ</b></span>";
						else if($name_result==3) echo "<font color=\"#FF4518\"><b>DB ������</b></span>";
						else if($name_result==4) echo "<font color=\"#FF4518\"><b>�ֹι�ȣ ����</b></span>";
						else if($name_result==5) echo "<font color=\"#FF4518\"><b>DB ���� ����</b></span>";
						else if($name_result==7) echo "<font color=\"#FF4518\"><b>���ǵ������</b></span>";
						else if($name_result!=1) echo "<font color=\"#FF4518\"><b>DB ���� ����</b></span>";
?>
						<a href="JavaScript:history.go(<?=$history?>)"><IMG SRC="<?=$Dir.AdultDir?>images/erro_04.gif" border="0" hspace="5" align="absmiddle"></a></td>
					</tr>
					</table>
					</td>
				</tr>
				<tr>
					<td style="line-height:110%;"><img src="<?=$Dir.AdultDir?>images/erro_line.gif" border="0" vspace="10"></td>
				</tr>
				<tr>
					<td>
					<table cellpadding="0" cellspacing="0">
					<tr>
						<td style="padding-left:30px;line-height:110%;"><span style="font-size:8pt; letter-spacing:-0.5pt;"><font color="#666666" face="����"><b>�������� 
						����</b> : ����ſ�������(��)<br>TEL 02)512-1300 
						FAX 02)3449-1678 (��ð� : 09:30~17:30)<br>������ 
						�ź���(�ֹε���� ��)�� �����Ͽ� ����ó�� 
						�����Ͽ� �ѽ��� �����ֽʽÿ�.<br>24�ð� 
						�̳��� ���� ó���� �� �帮�ڽ��ϴ�.</font></span></td>
					</tr>
					</table>
					</td>
				</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td><IMG SRC="<?=$Dir.AdultDir?>images/erro_down.gif" border="0"></td>
			</tr>
			</table>
			</td>
		</tr>
		</table>
		</body>
		</html>
<?
		exit;
	}
}

function getNameCheck($name, $jumin1, $jumin2, $id, $pw) {
	global $Dir;
	$name_result=0;
	$file_jumin = $Dir.DataDir."cache/name/CACHE_$name"."_".md5("$jumin1$jumin2");
	$file_denyY = $Dir.DataDir."cache/name/CACHE_Y_".getenv("REMOTE_ADDR");
	$file_denyN = $Dir.DataDir."cache/name/CACHE_N_".getenv("REMOTE_ADDR");

	if(strpos(" ".$file_jumin,"..")==true) $file_jumin=str_replace("..","",$file_jumin);
	if(strpos(" ".$file_denyY,"..")==true) $file_denyY=str_replace("..","",$file_denyY);
	if(strpos(" ".$file_denyN,"..")==true) $file_denyN=str_replace("..","",$file_denyN);

	include($Dir."lib/cache_namecheck.php");

	if ($name_result==119) {
		return "[".getenv("REMOTE_ADDR")."] IP���� �ٷ� �Ǹ����� �õ��� �ֽ��ϴ�.\\n\\n�Ǹ����� ���� ������ ��ȣ�ϱ����� �αױ���ϰ�, �Ǹ������� �ź��մϴ�.";
		exit;
	}

	if ($name_result!=1) {
		$host=getenv("HTTP_HOST");
		$port=80;
		$path="/".RootPath.FrontDir."getnamecheck.php";
		$name = ereg_replace(" ","",$name);

		$query="id=".$id."&pw=".$pw."&name=".$name."&jumin1=".$jumin1."&jumin2=".$jumin2;
		$fp = fsockopen($host, $port, &$errno, &$errstr, 5);
		if(!$fp) {
			$strLine = " result value=5";
			flush();
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
			flush();
			while($currentHeader = fgets($fp,4096)) {
				if($currentHeader == "\r\n") {
					break;
				}
			}

			$strLine = "";
			while(!feof($fp)) {
				$strLine .= fgets($fp, 4096);
			}
		}

		$name_result = substr($strLine,strpos($strLine,"result value=")+13,1);
		if($name_result==1 && strlen($file_jumin)>0) {
			$fp = @fopen("$file_jumin","w");
			@fputs($fp,"OK");
			@fclose($fp);
		}

		if ($name_result==1 || $name_result==2 || $name_result==4) {
			if(file_exists("$file_denyY")==true) {
				$fp = @fopen("$file_denyY","r");
				$temp = @fgets($fp,1024);
				if (strlen($temp)==0) $temp=0;
				@fclose($fp);

				$filetime = filemtime($file_denyY);
				$timegan=(time()-$filetime);
				$temp ++;
			} else {
				$temp=1;
			}
			$fp = @fopen("$file_denyY","w");@fputs($fp,$temp);@fclose($fp);
		}
	}

	if($name_result!=1) {
		if($name_result==2) return "ȸ������ �Է��Ͻ� ����� �ֹι�ȣ�� ��ġ���� �ʽ��ϴ�.\\n\\n�ٽ��ѹ� ��Ȯ�� �Է��Ͽ� �ֽñ� �ٶ��ϴ�.";
		else if($name_result==3) return "�Է��Ͻ� ����� �ֹι�ȣ�� ����ſ��򰡿��� �����ϴ� �Ǹ�DB�� �����Ͱ� �����ϴ�.\\n\\n�ٽ��ѹ� ��Ȯ�� �Է��Ͽ� �ֽñ� �ٶ��ϴ�.";
		else if($name_result==4) return "�ֹι�ȣ �����Դϴ�. �ٽ��ѹ� ��Ȯ�� �Է��Ͽ� �ֽñ� �ٶ��ϴ�.";
		else if($name_result==5) return "����ſ��� �����ͺ��̽��� ������ �ȵ˴ϴ�.��� �� �ٽ� �����ϼ���.\\n\\n����ſ������� ���� : 02)512-1300";
		else if($name_result==7) return "������ ���ǵ�������� ��û�ϼ̽��ϴ�.\\n\\www.siren24.com���� �Ͻ������ϼž� �մϴ�.";
		else if($name_result!=1) return "����ſ��� �����ͺ��̽��� ������ �ȵ˴ϴ�. ��� �� �ٽ� �����ϼ���.\\n\\n����ſ������� ���� : 02)512-1300";
	} else {
		return "";
	}
}
?>