<?
	$Dir="../";
	include_once($Dir."lib/init.php");
	include_once($Dir."lib/lib.php");
	include_once($Dir."lib/shopdata.php");
	include_once($Dir."m/inc/function.php");

	$mode = !_empty($_POST['mode'])?trim($_POST['mode']):"";  //���
	$categorycode = !_empty($_POST['code'])?trim($_POST['code']):""; // ī�װ��ڵ�
	$productcode = !_empty($_POST['productcode'])?trim($_POST['productcode']):""; // ��ǰ�ڵ�
	$sort = !_empty($_POST['sort'])?trim($_POST['sort']):""; // ����
	$quality = !_empty($_POST['quality'])?trim($_POST['quality']):"";//ǰ��
	$price = !_empty($_POST['price'])?trim($_POST['price']):"";//����
	$delitime = !_empty($_POST['delitime'])?trim($_POST['delitime']):"";//��۽ð�
	$recommend = !_empty($_POST['recommend'])?trim($_POST['recommend']):"";//��õ
	$writer = !_empty($_POST['rname'])?trim($_POST['rname']):"";//�ۼ���
	$contents= !_empty($_POST['rcontent'])?trim($_POST['rcontent']):"";//����
	
	$avermark = floor(((int) $quality+ (int) $price + (int) $delitime+ (int) $recommend) /4);

	if($_data->review_type =="N" || $_data->ETCTYPE["REVIEW"]=="N") {
		echo '<script>alert("����ı� ��� ������ ���� �ʾ� ����� �� �����ϴ�.");location.replace("/front/productdetail.php?productcode='.$productcode.'");</script>';exit;
	}
	if(strlen($_ShopInfo->getMemid())==0 && $_data->review_memtype=="Y"){
		echo '<script>alert("ȸ������ ����Դϴ�.");location.replace("/front/productdetail.php?productcode='.$productcode.'");</script>';exit;
	}

	if(strlen($mode) <= 0 || $mode != "write"){
		echo '<script>alert("�������� ����� �ƴϹǷ� ������ �� �����ϴ�.");location.replace("/front/productdetail.php?productcode='.$productcode.'");</script>';exit;
	}else if(strlen($code) <= 0 || strlen($code)>12){
		echo '<script>alert("�������� ����� �ƴϹǷ� ������ �� �����ϴ�2.");location.replace("/front/productdetail.php?productcode='.$productcode.'");</script>';exit;
	}else if(strlen($productcode) <= 0 || strlen($productcode)>18){
		echo '<script>alert("�������� ����� �ƴϹǷ� ������ �� �����ϴ�3.");location.replace("/front/productdetail.php?productcode='.$productcode.'");</script>';exit;
	}else if(strlen($quality) <= 0 || ($quality < 1 || $quality > 5)){
		echo '<script>alert("ǰ�� ������ ������ �ּ���.");location.replace("/front/productdetail.php?productcode='.$productcode.'");</script>';exit;
	}else if(strlen($price) <= 0 || ($price < 1 || $price > 5)){
		echo '<script>alert("���� ������ ������ �ּ���.");location.replace("/front/productdetail.php?productcode='.$productcode.'");</script>';exit;
	}else if(strlen($delitime) <= 0 || ($delitime < 1 || $delitime > 5)){
		echo '<script>alert("��� ������ ������ �ּ���.");location.replace("/front/productdetail.php?productcode='.$productcode.'");</script>';exit;
	}else if(strlen($recommend) <= 0 || ($recommend < 1 || $recommend > 5)){
		echo '<script>alert("��õ ���� ������ �ּ���.");location.replace("/front/productdetail.php?productcode='.$productcode.'");</script>';exit;
	}else if(strlen($writer) <= 0){
		echo '<script>alert("�ۼ��ڸ� �Է��� �ּ���.");location.replace("/front/productdetail.php?productcode='.$productcode.'");</script>';exit;
	}else if(strlen($contents) <= 0){
		echo '<script>alert("������ �Է��� �ּ���.");location.replace("/front/productdetail.php?productcode='.$productcode.'");</script>';exit;
	}
	
	
	$allowfile = array('image/pjpeg','image/jpeg','image/JPG','image/X-PNG','image/PNG','image/png','image/x-png','image/gif');
	$saveattechfile = $Dir."data/shopimages/productreview/";
/*
	$getmaxfilesize = _uploadMaxFileSize();
	
	
	$maxfilesize = $getmaxfilesize['maxsize'];//����������
	$maxfilesize_unit = $getmaxfilesize['unit'];//�����û�����
*/

	$maxfilesize = 3000000;//����������
	$maxfilesize_unit = "3M";

	$filename="";
	$queryattechname="";
	$attechfilename = !_empty($_FILES['attech']['name'])?trim($_FILES['attech']['name']):"";
	if(strlen($attechfilename)>0){
		$attechfiletype = !_empty($_FILES['attech']['type'])?trim($_FILES['attech']['type']):"";
		$attechfilesize = !_empty($_FILES['attech']['size'])?trim($_FILES['attech']['size']):"";
		$attechtempfilename = !_empty($_FILES['attech']['tmp_name'])?trim($_FILES['attech']['tmp_name']):"";

		if(!in_array($attechfiletype,$allowfile)){
			echo '<script>alert("÷�� ������ ������ �ƴմϴ�.\n÷�ΰ����� ������ jpg, gif, png�Դϴ�.");location.replace("/front/productdetail.php?productcode='.$productcode.'");</script>';exit;
		}else{
			if($attechfilesize >$maxfilesize){
				echo '<script>alert("÷�� ������ ���� �뷮�� �ʰ� �Ǿ����ϴ�.\n�ִ� ÷�ΰ����� ���Ͽ뷮�� '.$maxfilesize_unit.'�Դϴ�.");location.replace("/front/productdetail.php?productcode='.$productcode.'");</script>';exit;
			}else{
				$filename = date("YmdHis").$attechfilename;

				if(move_uploaded_file($attechtempfilename,$saveattechfile.$filename)){
					$queryattechname = $filename;
				}
			}
		}
	}
	
	$reviewwriteSQL ="INSERT tblproductreview SET ";
	$reviewwriteSQL.= "productcode	= '".$productcode."'";
	$reviewwriteSQL.= ", id			= '".$_ShopInfo->getMemid()."'";
	$reviewwriteSQL.= ", name		= '".$writer."'";
	$reviewwriteSQL.= ", marks		= '".$avermark."'";
	$reviewwriteSQL.= ", date		= '".date("YmdHis")."'";
	$reviewwriteSQL.= ", content		= '".$contents."'";
	$reviewwriteSQL.= ", device		= 'P'";
	$reviewwriteSQL.= ", quality		= '".$quality."'";
	$reviewwriteSQL.= ", price		= '".$price."'";
	$reviewwriteSQL.= ", delitime		= '".$delitime."'";
	$reviewwriteSQL.= ", recommend = '".$recommend."' ";
	if(strlen($queryattechname)>0){
		$reviewwriteSQL.= ", img = '".$queryattechname."' ";
	}
	
	$returnmsg="";

	if(false !== mysql_query($reviewwriteSQL,get_db_conn())){
		if($_data->review_type=="A") {
			$returnmsg="������ ������ ��ϵ˴ϴ�.";
		}else{
			$returnmsg="��ǰ���� ��ϵǾ����ϴ�.";
		}

		productReviewAverage ($productcode, $avermark);

	}else{
		$returnmsg="��ǰ���� ��ϵ��� �ʾҽ��ϴ�.";
	}

	echo '<script>alert("'.$returnmsg.'");location.replace("/front/productdetail.php?productcode='.$productcode.'#3");</script>';
	exit;
?>