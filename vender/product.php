<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");
include ("access.php");
include_once($Dir."lib/ext/product_func.php");
include_once($Dir."lib/admin_more.php");

if(substr($_venderdata->grant_product,0,1)!="Y") {
	echo "<html></head><body onload=\"alert('��ǰ ��� ������ �����ϴ�.\\n\\n���θ��� �����Ͻñ� �ٶ��ϴ�.');history.go(-1)\"></body></html>";exit;
}

if($_venderdata->product_max!=0) {
	$sql = "SELECT prdt_allcnt FROM tblvenderstorecount WHERE vender='".$_VenderInfo->getVidx()."' ";
	$result=mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
	mysql_free_result($result);
	$prdt_allcnt=$row->prdt_allcnt;

	if($_venderdata->product_max<=$prdt_allcnt) {
		echo "<html></head><body onload=\"alert('�ش� �̴ϼ����� ����� �� �ִ� ��ǰ������ ".$_venderdata->product_max."�� �Դϴ�.\\n\\n�ٸ���ǰ�� ���� �� ����Ͻðų� ���θ��� �����Ͻñ� �ٶ��ϴ�. ');history.go(-1)\"></body></html>";exit;
	}
}
include "header.php"; ?>
<script type="text/javascript" src="lib.js.php"></script>
<script type="text/javascript" src="calendar.js.php"></script>
<script type="text/javascript" src="PrdtRegist.js.php"></script>
<script language="javascript" type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
<script> var $j = jQuery.noConflict();</script>