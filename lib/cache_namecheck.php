<?
if(substr(getenv("SCRIPT_NAME"),-20)=="/cache_namecheck.php"){
	header("HTTP/1.0 404 Not Found");
	exit;
}

$second = 900;		// 15�а� ��IP���� �Ǹ�������
$maxvisit = 6;		// 6���̻��Ұ��
$no_second = 600;	// 10�а� �Ǹ������� ����

if (file_exists($file_jumin)==true) {
	$filetime = @filemtime($file_jumin);
	$timegan=(time()-$filetime);
	if ($timegan>$second) {
		@unlink($file_jumin);
		$name_result=0;
	} else {
		$name_result=1;
		return;
	}
}


// $file_denyN �� �����ϸ� ������..
if (file_exists($file_denyN)==true) {
	$filetime = @filemtime($file_denyN);
	$timegan=(time()-$filetime);
	// ���ϻ������� no_seond�ʰ� ������ �ٽ� ����
	if ($timegan>=$no_second) {
		@unlink($file_denyN);
	} else {
		$name_result=119;
		return;
	}
}

if (file_exists($file_denyY)==false) {
	$fp = @fopen("$file_denyY","w");@fputs($fp,"1");@fclose($fp);
} else {
	$fp = @fopen("$file_denyY","r");
	$temp = @fgets($fp,1024);
	if (strlen($temp)==0) $temp=0;
	@fclose($fp);

	$filetime = @filemtime($file_denyY);
	$timegan=(time()-$filetime);
	if ($timegan>$second) {
		@unlink($file_denyY); // ���ϻ�������  x �� ������ �ٽ� ����
	} else {
		if ($temp>=$maxvisit) { // x �� �ȿ� �湮�ڰ� x ���̻��̸�
			$fp = @fopen("$file_denyN","w");@fputs($fp,$temp);@fclose($fp);
			unlink($file_denyY);
			$name_result=119;
			return;
		}
	}
}
?>