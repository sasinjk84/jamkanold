<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");


$basketItems = getBasketByArray();

//_pr($basketItems);

echo $chkprice = $_REQUEST['chkprice'];
echo "<br>";

					if( strlen($_ShopInfo->getMemgroup()) > 0 ) {

						// �׷� ȸ�� ��ۺ� ���� ��å ( Ÿ�� - 1:�⺻, 2:����, 3:�ݾ��̻� )
						$groupDeli = memberGroupDelivery ( $_ShopInfo->getMemgroup() );
						//// Ÿ�� "1" �⺻ ��� ��å �н�~
						//// Ÿ�� "2" ����
						if( $groupDeli['type'] == "2" ) {
							$basketItems['deli_price'] =0;
						}
						//// Ÿ�� "3"�ݾ��̻� ����
						if( $groupDeli['type'] == "3" AND $chkprice >= $groupDeli['money'] ) {
							$basketItems['deli_price'] = 0;
						}
					}
					echo "<hr>";
		echo "�� ��ۺ� : ".$basketItems['deli_price']."<br>";
?>