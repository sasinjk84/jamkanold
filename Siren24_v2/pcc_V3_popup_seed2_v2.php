<?
	header ("Cache-Control : no-cache");
	header ("Cache-Control : post-check=0 pre-check=0");
	header ("Pragma:no-cache");

	//$enc_retInfo =  $_REQUEST["retInfo"];
	//$param = "?retInfo=$enc_retInfo";

	$iv = "0000000000000000";

	// �Ķ���ͷ� ���� ��û���
	$enc_retInfo = $_REQUEST["retInfo"];

	//��ȣȭ Ű ����
	$key = "3ECA075F0D94C1E583DC5A0968FD6F97";

	//2014.02.07 KISA �ǰ���� : �� ���� ��, �ҹ� �õ� ������ ���Ͽ� �Ʒ� ���Ͽ� �ش��ϴ� ���ڿ��� ���
	if( preg_match('~[^0-9a-zA-Z+/=^]~', $iv, $matches) || 
		preg_match('~[^0-9a-zA-Z+/=^]~', $enc_retInfo, $matches)){
			echo "�Է� �� Ȯ���� �ʿ��մϴ�.(res-1)"; exit;
	}

	/*
?>

	[����Ȯ�� ��� ���� Sample-PHP] <br><br>
	[��ȣȭ �ϱ��� ���Ű�] <br><br>

	retInfo : <? echo $enc_retInfo ?> <br />

<?
	*/

	//02. 1�� ��ȣȭ 
	//��ȣȭ��� ��ġ�� ������ SciSecuX ������ �ִ� ������ ��θ� �������ּ���.
	$dec_retInfo = exec("/home/rental/public_html/Siren24_v2/SciSecuX SEED 2 2 $iv $enc_retInfo $key"); //(ex: /home/name1/php_v2/SciSecuX)

	/*
	PHP ������ ���� ������ ����ϴ� split �Լ��� �۵��� �� �Ҷ��� �ֽ��ϴ�.
	�׷��� explode �Լ��� ���� �� \\^ ���ø���ſ� ^�� ������ �����ֽø� ����ó���� �����մϴ�.
	*/
	$totInfo = split("\\^", $dec_retInfo);
	$encPara  = $totInfo[0];			//����Ȯ��1����ȣȭ��
	$encMsg   = $totInfo[1];		//��ȣȭ�� ���� �Ķ������ ������������

	//03. HMAC Ȯ��
	$hmac_str = exec("/home/rental/public_html/Siren24_v2/SciSecuX HMAC 1 2 $encPara $key");

	if($hmac_str != $encMsg){
?>
		<script language="javascript">
			alert("���������� �����Դϴ�!!");
		</script>
		<a href="http://beta.jamkan.com/Siren24_v2/pcc_V3_input_seed_v2.php">[Back]</a>
<?
		exit;
	}

	//04. 2�� ��ȣȭ
	$decPara = exec("/home/rental/public_html/Siren24_v2/SciSecuX SEED 2 2 $iv $encPara $key");

	/*
	PHP ������ ���� ������ ����ϴ� split �Լ��� �۵��� �� �Ҷ��� �ֽ��ϴ�.
	�׷��� explode �Լ��� ���� �� \\^ ���ø���ſ� ^�� ������ �����ֽø� ����ó���� �����մϴ�.
	*/
	//05. ������ ����
	$split_dec_retInfo = split("\\^", $decPara);

	//print_r($split_dec_retInfo);

	$name		= $split_dec_retInfo[0];		//����
	$birYMD		= $split_dec_retInfo[1];		//�������
	$sex			= $split_dec_retInfo[2];		//����
	$fgnGbn		= $split_dec_retInfo[3];		//���ܱ��� ���а�
	$di				= $split_dec_retInfo[4];		//DI
	$ci1			= $split_dec_retInfo[5];		//CI1
	$ci2			= $split_dec_retInfo[6];		//CI2	
	$civersion	= $split_dec_retInfo[7];		//CI Version
	$reqNum		= "0000000000000000";		//$split_dec_retInfo[8];		//��û��ȣ
	$result		= $split_dec_retInfo[9];		//����Ȯ�� ��� (Y/N)
	$certGb		= $split_dec_retInfo[10];		//��������
	$cellNo		= $split_dec_retInfo[11];		//�ڵ��� ��ȣ
	$cellCorp		= $split_dec_retInfo[12];		//�̵���Ż�
	$certDate	= $split_dec_retInfo[13];		//�����ð�
	$addVar		= $split_dec_retInfo[14];	//�߰� �Ķ����

	//���� �ʵ�
	$ext1		= $split_dec_retInfo[15];
	$ext2		= $split_dec_retInfo[16];
	$ext3		= $split_dec_retInfo[17];
	$ext4		= $split_dec_retInfo[18];
	$ext5		= $split_dec_retInfo[19];

	//$name=iconv("UTF-8","EUC-KR",$name);
?>

<script>
	window_name=opener.window.name;
	opener.sirenResult('<? echo $name ?>','<? echo $cellNo ?>','<? echo $sex ?>','<? echo $birYMD ?>','<? echo $result ?>');
	self.close();
</script>

<? /*
<html>
<head>
<script language="JavaScript">
function end(){
	window.opener.location.href = 'http://beta.jamkan.com/Siren24_v2/pcc_V3_result_seed_v2.php' + '<?=$param?>';
	self.close();
}
</script>

</head>
<body onload="javascript:end()">
</body>
</html>
*/ ?>