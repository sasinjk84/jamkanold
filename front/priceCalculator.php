<?php
/**
 * Created by PhpStorm.
 * User: x2chi-objet
 * Date: 2014-11-18
 * Time: 오후 3:09
 */
header("Content-Type: text/html; charset=EUC-KR");

$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

header("Content-Type: text/html; charset=EUC-KR");

$rentSellPrice = rentSellPrice($_REQUEST['pridx'], $_REQUEST['opt'], $_REQUEST['sdate'], $_REQUEST['edate'], $_REQUEST['vender']);

if(!_empty($rentSellPrice['err']) && $rentSellPrice['err'] != 'ok'){
	echo $rentSellPrice['err'];
}else{

	if($rentSellPrice['timegap'] == 1){
		$rangestr = $rentSellPrice['diff']['day'].'일'.$rentSellPrice['diff']['hour'].'시간';
		$daypatt = "Y-m-d일 H시";
	}else{
		$rangestr = $rentSellPrice['diff']['day'].'일';
		$daypatt = "Y-m-d일";
	}
	
	echo $rangestr,' ('.date($daypatt,$rentSellPrice['range'][0])." ~ ".date($daypatt,$rentSellPrice['range'][1]+1).")<br />";
	echo "<strong style=\"font-size:20px;\">".number_format($rentSellPrice['totalprice']+$rentSellPrice['discprice'])."<span style=\"font-size:13px;\">원</span></strong>";
	
	if(!_empty($rentSellPrice['discountmsg'])){
		echo $rentSellPrice['discountmsg'];
	}
}
?>