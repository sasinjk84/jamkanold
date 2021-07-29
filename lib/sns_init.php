<?php
	$is_mobile = false;
	include_once($_SERVER['DOCUMENT_ROOT']."/lib/class/class.naverOAuth.php");
	$naver = new Naver(array(
		"CLIENT_ID"				=> 'pkd2DB6JR8kPNCxETRT5',
		"CLIENT_SECRET"		=> 'I4m4WbcmPk',
		"RETURN_URL"			=> BaseUrl."/sns/naver.php",
		"AUTO_CLOSE"			=> false,
		"SHOW_LOGOUT"		=> false,
		"SOCIAL_ID_PREFIX"	=> "social_N_",
		"IS_MOBILE"				=> $is_mobile
	));
?>