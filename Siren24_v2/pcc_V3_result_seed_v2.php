<?
/**************************************************************************************************************************
* Program Name  : ����Ȯ�� ��� Sample Page
* File Name     : pcc_V3_result_seed_v2
* Comment       : 
* History       : Version 1.0
**************************************************************************************************************************/
?>
<?
	/************************************************************************************/
	
 	/************************************************************************************/

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

    [����Ȯ�� ��� ���� Sample-PHP] <br> <br>
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
?>
	<script>
		window_name=opener.window.name;
		opener.sirenResult('<? echo $name ?>','<? echo $cellNo ?>','<? echo $sex ?>','<? echo $birYMD ?>','<? echo $result ?>');
		self.close();
	</script>

	<!--
	<form name="reqCBAre" method="post" action="/front/member_join.php" >
		<input type="hidden" name="req_name" value="<? echo "$name" ?>">
		<input type="hidden" name="req_sex" value="<? echo "$sex" ?>">
		<input type="hidden" name="req_birYMD" value="<? echo "$birYMD" ?>">
		<input type="hidden" name="req_di" value="<? echo "$di" ?>">
		<input type="hidden" name="req_result" value="<? echo "$result" ?>">
		<input type="hidden" name="req_cellNo" value="<? echo "$cellNo" ?>">
		<input type="hidden" name="addVar" value="<? echo "$addVar" ?>">
	</form>
	<script>document.reqCBAre.submit();</script>
	-->

<?
	/*
?>
<html>
    <head>
        <title>SCI������ ����Ȯ�μ���  �׽�Ʈ</title>
        <meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
		<meta name="robots" content="noindex,nofollow" />
        <style>
            <!--
            body,p,ol,ul,td
            {
                font-family: ����;
                font-size: 12px;
            }

            a:link { size:9px;color:#000000;text-decoration: none; line-height: 12px}
            a:visited { size:9px;color:#555555;text-decoration: none; line-height: 12px}
            a:hover { color:#ff9900;text-decoration: none; line-height: 12px}

            .style1 {
                color: #6b902a;
                font-weight: bold;
            }
            .style2 {
                color: #666666
            }
            .style3 {
                color: #3b5d00;
                font-weight: bold;
            }
            -->
        </style>
    </head>
	<body>
            [��ȣȭ �� ���Ű�] <br>
            <br>
            <table cellpadding="1" cellspacing="1" border="1">
				<tr>
					<td align="center" colspan="2">14���̻� �� �ſ������� ���</td>
				</tr>
				<tr>
                    <td align="left">����</td>
                    <td align="left"><? echo $name ?></td>
                </tr>
				<tr>
                    <td align="left">����</td>
                    <td align="left"><? echo $sex ?></td>
                </tr>
				<tr>
                    <td align="left">�������</td>
                    <td align="left"><? echo $birYMD ?></td>
                </tr>
				<tr>
                    <td align="left">���ܱ��� ���а�(1:������, 2:�ܱ���)</td>
                    <td align="left"><? echo $fgnGbn ?></td>
                </tr>				
				<tr>
                    <td align="left">�ߺ�����������</td>
                    <td align="left"><? echo $di ?></td>
                </tr>
				<tr>
                    <td align="left">��������1</td>
                    <td align="left"><? echo $ci1 ?></td>
                </tr>
				<tr>
                    <td align="left">��������2</td>
                    <td align="left"><? echo $ci2 ?></td>
                </tr>
				<tr>
                    <td align="left">������������</td>
                    <td align="left"><? echo $civersion ?></td>
                </tr>
                <tr>
                    <td align="left">��û��ȣ</td>
                    <td align="left"><? echo $reqNum ?></td>
                </tr>
				<tr>
                    <td align="left">������������</td>
                    <td align="left"><? echo $result ?></td>
                </tr>
				<tr>
                    <td align="left">��������</td>
                    <td align="left"><? echo $certGb ?></td>
                </tr>
				<tr>
                    <td align="left">�ڵ�����ȣ</td>
                    <td align="left"><? echo $cellNo ?></td>
                </tr>
				<tr>
                    <td align="left">�̵���Ż�</td>
                    <td align="left"><? echo $cellCorp ?></td>
                </tr>
                <tr>
                    <td align="left">��û�ð�</td>
                    <td align="left"><? echo $certDate ?></td>
                </tr>				
				<tr>
                    <td align="left">�߰��Ķ����</td>
                    <td align="left"><? echo $addVar ?>&nbsp;</td>
                </tr>
				
            </table>              
            <br>
            <br>
            <a href="http://beta.jamkan.com/Siren24_v2/pcc_V3_input_seed_v2.php">[Back]</a>
</body>
</html>
*/ ?>