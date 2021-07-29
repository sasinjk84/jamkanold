<?php
// 상품정보 동기화 goods.php 용파일
	$istest = true;
	$shopId = '';

	$certificationKey = ''; // 가맹정 인증키
	$buyButtonImageKey = ''; // 버튼 인증키
	
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
	
	// 포트번호가 있을경우 필터링한다.
	preg_match("/([a-z0-9-.]*)\.([a-z]{2,3})/", $_SERVER[HTTP_HOST], $matches);
	$domain = 'http://' . $matches[0];

	// 상품상세정보 페이지 절대경로
	$goodsUrl = $domain . '/front/productdetail.php';

	// 장바구니 페이지 절대경로
	$cartUrl = $domain . '/front/basket.php';

	// 상품 원본 이미지 디렉토리 절대경로
	$imageUrl = $domain . '/data/shopimages/product/';
	
	// 상품 썸네일 이미지 디렉토리 절대경로
	$thumbImageUrl = $domain . '/data/shopimages/product/';
	
	
	///
	$f=@file("../data/config.php");
	if($f){		
		for($i=1;$i<=4;$i++) $f[$i]=trim(str_replace("\n","",$f[$i]));
		$dbconn = @mysql_connect($f[1],$f[2],$f[3]) or exit("DB 접속 에러가 발생하였습니다.");
		$status = @mysql_select_db($f[4],$dbconn) or exit("DB Select 에러가 발생하였습니다.");

		if (!$status) {
		   exit("DB Select 에러가 발생하였습니다.");
		}
	}
?>