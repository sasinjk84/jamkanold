<?php
// ��ǰ���� ����ȭ goods.php ������
	$istest = true;
	$shopId = '';

	$certificationKey = ''; // ������ ����Ű
	$buyButtonImageKey = ''; // ��ư ����Ű
	
	if($istest === true){	
		$checkoutHost = 'test-checkout.naver.com';
		$checkoutHostM = 'test-m.naver.com';
	}else{
		$checkoutHost = 'checkout.naver.com';
		$checkoutHostM = 'm.checkout.naver.com';
	}


	$_charset = 'CP949';	// euc-kr
//	$_charset = 'utf-8';	// utf-8
	
	$_wishlistRequestUrl = $_charset == 'utf-8' ? 'POST /customer/api/wishlist.nhn HTTP/1.1' : 'POST /customer/api/CP949/wishlist.nhn HTTP/1.1';
	
	$_orderRequestUrl = $_charset == 'utf-8' ? 'POST /customer/api/order.nhn HTTP/1.1' : 'POST /customer/api/CP949/order.nhn HTTP/1.1';

	$isEvent = true;
	$checkoutSloganLinkUrl = 'https://checkout.naver.com/customer';
	if ($isEvent) {
		$checkoutSloganImageName = 'txt_about1';
		$checkoutSloganLinkUrl .= '/redirect.nhn';
	
	} else {
		$checkoutSloganImageName = 'txt_1';
		$checkoutSloganLinkUrl .= '/home.nhn';
	
	}

	extract($_GET);
	
	// ��Ʈ��ȣ�� ������� ���͸��Ѵ�.
	preg_match("/([a-z0-9-.]*)\.([a-z]{2,3})/", $_SERVER[HTTP_HOST], $matches);
	$domain = 'http://' . $matches[0];

	// ��ǰ������ ������ ������
	$goodsUrl = $domain . '/front/productdetail.php';

	// ��ٱ��� ������ ������
	$cartUrl = $domain . '/front/basket.php';

	// ��ǰ ���� �̹��� ���丮 ������
	$imageUrl = $domain . '/data/shopimages/product/';
	
	// ��ǰ ����� �̹��� ���丮 ������
	$thumbImageUrl = $domain . '/data/shopimages/product/';
	
	
	///
	$f=@file("../data/config.php");
	if($f){		
		for($i=1;$i<=4;$i++) $f[$i]=trim(str_replace("\n","",$f[$i]));
		$dbconn = @mysql_connect($f[1],$f[2],$f[3]) or exit("DB ���� ������ �߻��Ͽ����ϴ�.");
		$status = @mysql_select_db($f[4],$dbconn) or exit("DB Select ������ �߻��Ͽ����ϴ�.");

		if (!$status) {
		   exit("DB Select ������ �߻��Ͽ����ϴ�.");
		}
	}
?>