<?
if(substr(getenv("SCRIPT_NAME"),-9)=="/init.php") {
	header("HTTP/1.0 404 Not Found");
	exit;
}
$install_state = false;
header("Content-Type: text/html; charset=euc-kr");
ini_set ( "display_errors",0 );

define("DirPath", $Dir);
define("RootPath", "");

define("AdminDir", "admin/");
define("MainDir", "main/");
define("AdultDir", "adult/");
define("AuctionDir", "auction/");
define("BoardDir", "board/");
define("FrontDir", "front/");
define("GongguDir", "gonggu/");
define("PartnerDir", "partner/");
define("RssDir", "rss/");
define("TempletDir", "templet/");
define("SecureDir", "ssl/");
define("VenderDir", "vender/");
define("CashcgiDir", "cash.cgi/");
define("AuthkeyDir", "authkey/");

define("DataDir", "data/");

define("TodaySaleDir", "todayshop/");

define("MinishopType", "OFF");

#��ȣ/��ȣȭ Ű�Դϴ�. (�ش� ���θ����� �� �����Ͻñ� �ٶ��ϴ�.)
define("enckey", "password");

#�ý��� ������ ����
define("AdminMail", "");

$base_url  = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
$base_url .= "://" . $_SERVER['HTTP_HOST'];
//$base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);

// �⺻ ����Ʈ url
define("BaseUrl",		$base_url);


// ��Ƽ �̹��� ���� ( �߰��� tblmuliimages ���̺��� �ʵ尡 �߰� �Ǿ�� ��.)
define("MultiImgCnt", 30);
// ���� ���� ���� ��ǰ ������
$wholeSaleIconSet = "<img src='/images/common/wholeSaleIcon.gif' style=\"position:relative; top:0.2em;\" alt='' />";

//  ���� ��ǥ �̹��� ���
$com_image_url = $Dir."data/shopimages/vender/";

?>