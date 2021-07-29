<?
$goodname=titleCut(27,$goodname);
if($pg_type=="A") {
	if (file_exists($Dir.DataDir."shopimages/etc/cardimg_kcp.gif"))
		$sitelogo = "http://".$_ShopInfo->getShopurl().DataDir."shopimages/etc/cardimg_kcp.gif";
	else if (file_exists($Dir.DataDir."shopimages/etc/cardimg_kcp.jpg"))
		$sitelogo = "http://".$_ShopInfo->getShopurl().DataDir."shopimages/etc/cardimg_kcp.jpg";
	else $sitelogo = "";

	$sitecd=$pgid_info["ID"];
	$sitekey=$pgid_info["KEY"];
	if (preg_match("/^(Q|P)$/", $paymethod)) $escrow="Y";
	else $escrow="N";
	$pgurl=$Dir."paygate/".$pg_type."/charge.php?sitecd=".$sitecd."&sitekey=".urlencode($sitekey)."&escrow=".$escrow."&paymethod=".$paymethod."&goodname=".urlencode($goodname)."&price=".$last_price."&ordercode=".urlencode($ordercode)."&buyername=".urlencode($sender_name)."&buyermail=".urlencode($sender_email)."&buyertel1=".urlencode($sender_tel)."&buyertel2=".urlencode($sender_tel)."";
	if($escrow=="Y") {
		$pgurl.="&rpost=".$rpost."&raddr1=".urlencode($raddr1)."&raddr2=".urlencode($raddr2)."";
	}
	$pgurl.="&quotafree=".$card_splittype."&quotamonth=".$card_splitmonth."&quotaprice=".$card_splitprice."&sitelogo=".urlencode($sitelogo);
} else if($pg_type=="B") {
	if (file_exists($Dir.DataDir."shopimages/etc/cardimg_dacom.gif"))
		$sitelogo = "http://".$_ShopInfo->getShopurl().DataDir."shopimages/etc/cardimg_dacom.gif";
	else if (file_exists($Dir.DataDir."shopimages/etc/cardimg_dacom.jpg"))
		$sitelogo = "http://".$_ShopInfo->getShopurl().DataDir."shopimages/etc/cardimg_dacom.jpg";
	else $sitelogo = "";

	$mid=$pgid_info["ID"];
	$mertkey=$pgid_info["KEY"];
	if (preg_match("/^(Q|P)$/", $paymethod)) $escrow="Y";
	else $escrow="";

	$pgurl=$Dir."paygate/".$pg_type."/charge.php?memid=".$_ShopInfo->getMemid()."&shopname=".urlencode($_data->shopname)."&companynum=".$_data->companynum."&mid=".$mid."&mertkey=".$mertkey."&escrow=".$escrow."&paymethod=".$paymethod."&goodname=".urlencode($goodname)."&price=".$last_price."&ordercode=".urlencode($ordercode)."&pid=".encrypt_md5($sender_resno)."&buyername=".urlencode($sender_name)."&buyermail=".urlencode($sender_email)."&buyertel=".urlencode($sender_tel)."&receiver=".urlencode($receiver_name)."&receivertel=".urlencode($receiver_tel1)."";

	$pgurl.="&rpost=".$rpost."&raddr1=".urlencode($raddr1)."&raddr2=".urlencode($raddr2)."";

	$pgurl.="&quotafree=".$card_splittype."&quotamonth=".$card_splitmonth."&quotaprice=".$card_splitprice."&sitelogo=".urlencode($sitelogo);
} else if($pg_type=="C") {
	if (file_exists($Dir.DataDir."shopimages/etc/cardimg_allthegate.gif"))
		$sitelogo = "http://".$_ShopInfo->getShopurl().DataDir."shopimages/etc/cardimg_allthegate.gif";
	else if (file_exists($Dir.DataDir."shopimages/etc/cardimg_allthegate.jpg"))
		$sitelogo = "http://".$_ShopInfo->getShopurl().DataDir."shopimages/etc/cardimg_allthegate.jpg";
	else $sitelogo = "";

	$storeid=$pgid_info["ID"];
	$hp_id=$pgid_info["HP_ID"];
	$hp_pwd=$pgid_info["HP_PWD"];
	$hp_unittype=$pgid_info["HP_UNITType"];
	$hp_subid=$pgid_info["HP_SUBID"];
	$prodcode=$pgid_info["ProdCode"];

	if (preg_match("/^(Q|P)$/", $paymethod)) $escrow="Y";
	else $escrow="";

	$pgurl=$Dir."paygate/".$pg_type."/charge.php?storeid=".$storeid."&storenm=".urlencode(titleCut(47,$_data->shopname))."&ordno=".urlencode($ordercode)."&prodnm=".urlencode($goodname)."&amt=".$last_price."&userid=".$_ShopInfo->getMemid()."&useremail=".urlencode($sender_email)."&ordnm=".urlencode($sender_name)."&ordphone=".urlencode($sender_tel)."&rcpnm=".urlencode($receiver_name)."&rcpphone=".urlencode($receiver_tel1)."&escrow=".$escrow."&paymethod=".$paymethod."&hp_id=".$hp_id."&hp_pwd=".encrypt_md5($hp_pwd)."&hp_unittype=".$hp_unittype."&hp_subid=".$hp_subid."&prodcode=".$prodcode;

	//."&companynum=".$_data->companynum."&pid=".encrypt_md5($sender_resno)."";

	$pgurl.="&rpost=".$rpost."&raddr1=".urlencode($raddr1)."&raddr2=".urlencode($raddr2)."";

	$pgurl.="&quotafree=".$card_splittype."&quotamonth=".$card_splitmonth."&quotaprice=".$card_splitprice."&sitelogo=".urlencode($sitelogo);
} else if($pg_type=="D") {
	if (file_exists($Dir.DataDir."shopimages/etc/cardimg_inipay.gif"))
		$sitelogo = "http://".$_ShopInfo->getShopurl().DataDir."shopimages/etc/cardimg_inipay.gif";
	else if (file_exists($Dir.DataDir."shopimages/etc/cardimg_inipay.jpg"))
		$sitelogo = "http://".$_ShopInfo->getShopurl().DataDir."shopimages/etc/cardimg_inipay.jpg";
	else $sitelogo = "";

	if (file_exists($Dir.DataDir."shopimages/etc/cardimgleft_inipay.gif"))
		$sitelogoleft = "http://".$_ShopInfo->getShopurl().DataDir."shopimages/etc/cardimgleft_inipay.gif";
	else if (file_exists($Dir.DataDir."shopimages/etc/cardimgleft_inipay.jpg"))
		$sitelogoleft = "http://".$_ShopInfo->getShopurl().DataDir."shopimages/etc/cardimgleft_inipay.jpg";
	else $sitelogoleft = "";

	if (preg_match("/^(Q|P)$/", $paymethod)) {
		$escrow="Y";
		$sitecd=$pgid_info["EID"];
		$pgurl=$Dir."paygate/".$pg_type."/escrow/charge.php";
	} else {
		$escrow="N";
		$sitecd=$pgid_info["ID"];
		$hpunittype=$pgid_info["HP_UNITType"];
		$pgurl=$Dir."paygate/".$pg_type."/charge.php";
	}
	$pgurl.="?sitecd=".$sitecd."&escrow=".$escrow."&paymethod=".$paymethod."&goodname=".urlencode($goodname)."&price=".$last_price."&ordercode=".urlencode($ordercode)."&hpunittype=".$hpunittype."&buyername=".urlencode($sender_name)."&buyermail=".urlencode($sender_email)."&buyertel1=".urlencode($sender_tel)."&buyertel2=".urlencode($sender_tel)."";
	$pgurl.="&receivername=".urlencode($receiver_name)."&receivertel=".urlencode($receiver_tel11.$receiver_tel12.$receiver_tel13)."&rpost=".$rpost."&raddr1=".urlencode($raddr1)."&raddr2=".urlencode($raddr2)."";
	$pgurl.="&quotafree=".$card_splittype."&quotamonth=".$card_splitmonth."&quotaprice=".$card_splitprice."&sitelogo=".urlencode($sitelogo)."&sitelogoleft=".urlencode($sitelogoleft);





} else if($pg_type=="E") {



	// 나이스 페이 시작 ======================================================

	$sitecd=$pgid_info["EID"];
	$mid=$pgid_info["ID"];
	$mertkey=$pgid_info["KEY"];
	$mertkey = rawurlencode($mertkey);

	//$escrow = ( preg_match("/^(Q|P|O|V)$/", $paymethod) ) ? "Y" : "N" ;


	$pgurl=$Dir."paygate/".$pg_type."/charge.php?";

	$pgurl.="sitecd=".$sitecd;
	$pgurl.="&mid=".$mid;
	$pgurl.="&mertkey=".$mertkey;

	$pgurl.="&paymethod=".$paymethod;
	$pgurl.="&escrow=".$escrow;

	$pgurl.="&goodname=".urlencode($goodname);
	$pgurl.="&price=".$last_price;
	$pgurl.="&ordercode=".urlencode($ordercode);

	$pgurl.="&buyername=".urlencode($sender_name);
	$pgurl.="&buyermail=".urlencode($sender_email);
	$pgurl.="&buyertel1=".urlencode($sender_tel);
	$pgurl.="&buyertel2=".urlencode($sender_tel);

	$pgurl.="&raddr1=".urlencode($raddr1);
	$pgurl.="&raddr2=".urlencode($raddr2);

	$pgurl.="&memid=".$_ShopInfo->getMemid();

	$pgurl.="&goodsCl=1"; // 휴대폰결제 상품구분: 1:실물 , 0:컨텐츠

	// 과세/비과세
	$pgurl.="&SupplyAmt=".$SupplyAmt; //공급가액
	$pgurl.="&GoodsVat=".$vat; //부가가치세 (10%)
	$pgurl.="&TaxFreeAmt=".$tax_free; //면세액


	// 나이스 페이 끝 ======================================================






} else if($pg_type=="F") {

	$sitecd=$pgid_info["EID"];
	$mid=$pgid_info["ID"];
	//$mertkey=$pgid_info["KEY"];
	$mertkey = $mid;
	if( $paymethod == "C" ) $mertkey .= "0002";
	if( $paymethod == "M" ) $mertkey .= "0001";
	if( $paymethod == "O" ) $mertkey .= "0004";
	if( $paymethod == "V" ) $mertkey .= "0003";
	$mertkey = rawurlencode($mertkey);
	$escrow = ( preg_match("/^(Q|P)$/", $paymethod) ) ? "Y" : "N" ;

	$pgurl=$Dir."paygate/".$pg_type."/charge_".$paymethod.".php?";

	$pgurl.="sitecd=".$sitecd;
	$pgurl.="&mid=".$mid;
	$pgurl.="&mertkey=".$mertkey;

	$pgurl.="&paymethod=".$paymethod;
	$pgurl.="&escrow=".$escrow;

	$pgurl.="&goodname=".urlencode($goodname);
	$pgurl.="&price=".$last_price;
	$pgurl.="&ordercode=".urlencode($ordercode);

	$pgurl.="&buyername=".urlencode($sender_name);
	$pgurl.="&buyermail=".urlencode($sender_email);
	$pgurl.="&buyertel1=".urlencode($sender_tel);
	$pgurl.="&buyertel2=".urlencode($sender_tel);

	$pgurl.="&raddr1=".urlencode($raddr1);
	$pgurl.="&raddr2=".urlencode($raddr2);

	$pgurl.="&memid=".$_ShopInfo->getMemid();

	$pgurl.="&goodsCl=1"; // 휴대폰결제 상품구분: 1:실물 , 0:컨텐츠


} else if($pg_type=="G"){
	$pgurl="";
	$pgurl=$Dir."paygate/".$pg_type."/charge.php?";
	$pgurl.="paymethod=".$paymethod;
	$pgurl.="&escrow=".$escrow;
	$pgurl.="&goodname=".urlencode($goodname);
	$pgurl.="&price=".$last_price;
	$pgurl.="&ordercode=".urlencode($ordercode);
	$pgurl.="&buyername=".urlencode($sender_name);
	$pgurl.="&buyermail=".urlencode($sender_email);
	$pgurl.="&buyertel1=".urlencode($sender_tel);
	$pgurl.="&buyertel2=".urlencode($sender_tel);
	$pgurl.="&raddr1=".urlencode($raddr1);
	$pgurl.="&raddr2=".urlencode($raddr2);
	$pgurl.="&memid=".$_ShopInfo->getMemid();
	$pgurl.="&goodsCl=1"; // 휴대폰결제 상품구분: 1:실물 , 0:컨텐츠
	// 과세/비과세
	$pgurl.="&SupplyAmt=".$SupplyAmt; //공급가액
	$pgurl.="&GoodsVat=".$vat; //부가가치세 (10%)
	$pgurl.="&TaxFreeAmt=".$tax_free; //면세액

}


?>