<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");
include_once($Dir."lib/admin_more.php");
include_once($Dir."lib/ext/func.php");
INCLUDE ("access.php");

$date = date('Ymd');

$ordercode = $_POST[ordercode];
$type = $_POST[type];
$goods = $_POST[goods];
$status = $_POST[status];
$bank = $_POST[bank];
$account_name = $_POST[account_name];
$account_num = $_POST[account_num];
$reserve = $_POST[reserve];
$re_reserve_max = $_POST[re_reserve_max];
$re_price_max = $_POST[re_price_max];

// ���� ����
$dcPriceSend = ( empty($_POST[dcPriceSend]) ? 0 : $_POST[dcPriceSend] );
// ȯ�Ҿ�
$re_price = ( empty($_POST[re_price]) ? 0 : $_POST[re_price] );
// �����
$refund_free = ( empty($_POST[refund_free]) ? 0 : $_POST[refund_free] );
// �������� �ݾ� ( ���ξ׿��� �鼼������, ���� ���� ���� )
$price_tax = $re_price - $refund_free; // - $dcPriceSend;
//�ΰ��� ( �������� �ݾ��� ���� )
$refund_vat = floor($price_tax / 11);
//���ް� ( �����ݾ� : �������� �ݾ׿��� ��������  )
$refund_tax = $price_tax-$refund_vat;



//exit(_pr($_POST));

if ($type=="order") {

	$status	= $_POST['status'];
	$goods = $_POST['goods'];

	$goodsOrg = $goods;
	$goods = explode("|",$goods);

	if ($status=='RC') {

		// ��� �ݾ�
		$re_price = $_POST['re_price'];

		//����ݾ�
		$now_price = 0;
		$sql = "SELECT * FROM tblorderinfo WHERE ordercode='".$ordercode."'";
		$result=mysql_query($sql,get_db_conn());
		$_ord=mysql_fetch_object($result);
		$now_price = $_ord->price;
		mysql_free_result($result);



		//������� ȯ�ұ�
		$sum_re = 0;
		$sql = "SELECT sum(price) as sum_re FROM tblorderproduct WHERE ordercode='".$ordercode."' and productcode='99999999995X' ";
		$result=mysql_query($sql,get_db_conn());
		$row=mysql_fetch_object($result);
		$sum_re = $row->sum_re;
		mysql_free_result($result);




		if ($re_price > $balancePrice ) {
			?>
			<script type="text/javascript">
			<!--
				alert("���� �ܾ׺��� ���� ȯ���� ���� �����ϴ�.");
				history.back();
		   //-->
			</script>
			<?
			exit();
		}


		//�ӽ����̺��� ���� ���� Ȯ��. uid �浹 �ȳ���.
		$re_count = 0;
		$sql = "SELECT count(*) as cnt FROM tblorderproducttemp WHERE ordercode='".$ordercode."' and productcode='99999999995X' ";
		$result=mysql_query($sql,get_db_conn());
		$row=mysql_fetch_object($result);
		$re_count = ($row->cnt) + 1;
		mysql_free_result($result);








		//ī������� �κ���� ����� ����Ȯ��
		$c_status='C';
		if (preg_match("/^(C){1}/",$_ord->paymethod) && $_ord->pay_flag=="0000") {
			$c_status='A';

			if ($refund_free) {

				if ($refund_free > $re_price) {
					?>
					<script type="text/javascript">
					<!--
						alert("��ҿ�û ������� ��ü �ݾ׺��� Ů�ϴ�.");
						history.back();
					//-->
					</script>
					<?
					exit();
				}

				$sql = "select * from card_orderinfo_tax where ordercode='".$ordercode."'";
				$result=mysql_query($sql,get_db_conn());
				$row=mysql_fetch_object($result);
				$free_mny = $row->free_mny;
				mysql_free_result($result);


				//��� �Ϸ�Ȱ�
				$sql = "select sum(cancel_free) as cancel_free from card_part_cancel_log  where ordercode='".$ordercode."'";
				$result=mysql_query($sql,get_db_conn());
				$row=mysql_fetch_object($result);
				$cancel_free = $row->cancel_free;
				mysql_free_result($result);


				//��� ��û���ΰ�
				$sql = "select sum(free_mny) as want_cancel_free from card_part_cancel_tax_free  where ordercode='".$ordercode."' and status=0";
				$result=mysql_query($sql,get_db_conn());
				$row=mysql_fetch_object($result);
				$want_cancel_free = $row->want_cancel_free;
				mysql_free_result($result);


				$free_mny = $free_mny - $cancel_free - $want_cancel_free;

				if ($refund_free > $free_mny) {
					?>
					<script type="text/javascript">
					<!--
						alert("��ҿ�û ������� ��Ұ��� ����� �ݾ׺��� Ů�ϴ�.");
						history.back();
				   //-->
					</script>
					<?
					exit();
				}

			}
		}





		// ī�� ���� ����
		if (preg_match("/^(C){1}/",$_ord->paymethod)) {

			$remain_price = 0; // �����ݾ�
			$remain_tax = 0; // ���ް�
			$remain_vat = 0; // �ΰ���
			$remain_free = 0; // �����

			$sql = "SELECT * FROM card_orderinfo_tax WHERE ordercode='".$ordercode."' ";
			$result=mysql_query($sql,get_db_conn());
			if($row=mysql_fetch_object($result)) {
				$remain_price = $row->amount;
				$remain_tax = $row->tax_mny;
				$remain_vat = $row->vat_mny;
				$remain_free = $row->free_mny;
			}else{
				echo "<script>alert('".get_message("���� �ڷᰡ �ջ�Ǿ����ϴ�.")."');history.go(-1);</script>\n";
				exit;
			}

			$sql = "SELECT * FROM card_part_cancel_log WHERE ordercode='".$ordercode."' order by reg_date desc limit 0, 1";
			$result=mysql_query($sql,get_db_conn());
			if($row=mysql_fetch_object($result)) {

				$remain_price = $row->remain_price;
				$remain_tax = $row->remain_tax;
				$remain_vat = $row->remain_vat;
				$remain_free = $row->remain_free;

			}

			if ($refund_tax>$remain_tax) {
				?>
				<script type="text/javascript">
				<!--
					alert("PG�翡 ��� ��û �� �� �ִ� �����ݾ׺��� ū �׼��� �Է��ϼ̽��ϴ�. \n����� �κ��� �����Ͽ� �ٽ� �Է����ֽʽÿ�.\n��� ���� �ݾ� : <?= $remain_price ?>\n��� ���� ����� : <?= $remain_free ?>");
					history.back();
			   //-->
				</script>
				<?
				exit();
			}

			if ($refund_free>$remain_free) {
				?>
				<script type="text/javascript">
				<!--
					alert("PG�翡 ��� ��û �� �� �ִ� ��������� ū �׼��� �Է��ϼ̽��ϴ�. �ٽ� �õ����ּ���.\n��� ���� �ݾ� : <?= $remain_price ?>\n��� ���� ����� : <?= $remain_free ?>");
					history.back();
			   //-->
				</script>
				<?
				exit();
			}

		}


		if ($reserve>0) {
			//������ ��ȸ
			$now_reserve = 0;
			$sql = "SELECT * FROM tblorderinfo WHERE ordercode='".$ordercode."'";
			$result=mysql_query($sql,get_db_conn());
			$_ord=mysql_fetch_object($result);

			$now_reserve = $_ord->reserve;
			mysql_free_result($result);

			//��ҳ��� ��ȸ
			$sql = "select sum(cancel_reserve) as cancel_reserve from part_cancel_reserve where ordercode='".$ordercode."'";
			$result=mysql_query($sql,get_db_conn());
			$row=mysql_fetch_object($result);

			$cancel_reserve = $row->cancel_reserve;
			mysql_free_result($result);

			$now_reserve = $now_reserve-$cancel_reserve;

			if ($now_reserve<$reserve) {
				?>
				<script type="text/javascript">
				<!--
					alert("ȯ�� ������ �׼����� ū�׼��� �Է��ϼ̽��ϴ�.");
					history.back();
			   //-->
				</script>
				<?
				exit();
			}
		}

	}

	//�κ� ��� ��ǰ��
	$cancel_product_name = "";

	$p_count = 0;
	$p_name = array();
	$b_count = 0;
	$b_name = array();

	$cou_chk = 0;

	for($i=0;$i<count($goods);$i++) {

		//�κ� ��� ��ǰ�� �����
		$sql = "select productcode, productname from tblorderproduct where uid='".$goods[$i]."'";
		$result=mysql_query($sql,get_db_conn());
		$data=mysql_fetch_array($result);

		$productcode = $data['productcode'];
		$productname = $data['productname'];

		mysql_free_result($result);

		if (substr($productcode)!="COU") {

			//���� ������ ����
			redeliveryOrderAdjustDetailByProduct($ordercode, $goods[$i]);

			if	($productcode=="99999999990X") {
				$b_name[$b_count]=$productname;

				$b_count++;
			}else{
				$p_name[$p_count]=$productname;
				$p_count++;
			}


			$sql = "UPDATE tblorderproduct SET status = '".$status."' WHERE uid='".$goods[$i]."'";
			mysql_query($sql,get_db_conn());

			if ($status=='' || $status=='RA') {
			$sql = "delete from part_cancel_want where uid='".$goods[$i]."'";
			mysql_query($sql,get_db_conn());
			}

		}else{
			$cou_chk++;
		}
	}

	if (count($goods)==$cou_chk) {
		?>
		<script type="text/javascript">
		<!--
			alert("������ ȯ��ó�� �� �� �����ϴ�.");
			history.back();
	   //-->
		</script>
		<?
		exit();
	}

	if ($p_count>0) {
		$cancel_product_name = "�κ� ��� : ".$p_name[0];
		if ($p_count>1) {
			$p_count--;
			$cancel_product_name .= " �� ".$p_count."��";
		}

		if ($b_count>0) {
			$cancel_product_name .= ", ��۷� ".$b_count."��";
		}

	}else{
		$cancel_product_name = "�κ� ��� : ".$b_name[0];
		if ($b_count>1) {
			$b_count--;
			$cancel_product_name .= " �� ".$b_count."��";
		}
	}

	if ($status=='RC') {

		//�ӽ����̺��� ���� ����. uid �浹 �ȳ���.
		$sql = "insert into tblorderproducttemp(vender, ordercode, tempkey, productcode, productname, quantity, price, deli_gbn, status, `date`, opt1_name) values (0, '".$ordercode."', '".$_ord->tempkey."', '99999999995X', '".$cancel_product_name."', 1, '".$re_price."', 'N', '".$c_status."','".$date."', '".$re_count."')";
		mysql_query($sql,get_db_conn());

		$cancel_uid = mysql_insert_id();

		$sql = "insert into tblorderproduct ";
		$sql .= " select * from tblorderproducttemp where uid='".$cancel_uid."'";
		mysql_query($sql,get_db_conn());



		if (preg_match("/^(C){1}/",$_ord->paymethod)) {

			$sql = "insert into card_part_cancel_tax_free(ordercode, cancel_uid, amount, tax_mny, vat_mny, free_mny, status, reg_date) ";
			$sql .= " values ('".$ordercode."', '".$cancel_uid."', '".$re_price."', '".$refund_tax."', '".$refund_vat."', '".$refund_free."', 0, now())";
			mysql_query($sql,get_db_conn());
		}

		//������

		if ($reserve>0) {
			$sql = "UPDATE tblmember SET reserve=reserve+".abs($reserve)." ";
			$sql.= "WHERE id='".$_ord->id."' ";
			mysql_query($sql,get_db_conn());

			$sql = "INSERT tblreserve SET ";
			$sql.= "id			= '".$_ord->id."', ";
			$sql.= "reserve		= '".$reserve."', ";
			$sql.= "reserve_yn	= 'Y', ";
			$sql.= "content		= '�ֹ� �κ� ��Ұǿ� ���� ������ ȯ��', ";
			$sql.= "orderdata	= '".$ordercode."=".$_ord->price."', ";
			$sql.= "date		= '".date("YmdHis")."' ";
			mysql_query($sql,get_db_conn());

			$log_content="## ȸ�� ������ ȯ�� ## - �ֹ���ȣ : ".$ordercode." - ������ ".$reserve;
			ShopManagerLog($_ShopInfo->getId(),$connect_ip,$log_content);

			$remain_reserve = $now_reserve - $reserve;

			$sql = "insert into part_cancel_reserve(ordercode, cancel_reserve, org_reserve, remain_reserve, memo,reg_date) values ('".$ordercode."', '".$reserve."', '".$now_reserve."', '".$remain_reserve."', '".$cancel_product_name."',now()) ";
			mysql_query($sql,get_db_conn());
		}



		// ȯ���� �ܾ��� 0-�̸� ��ü ��� ó��
		if( ($balancePrice - $re_price) <= 0 ) {
			$sql = "UPDATE tblorderinfo SET deli_gbn = 'C', bank_date = '".date("Ymd")."X' WHERE ordercode = '".$ordercode."' LIMIT 1 ; ";
			mysql_query($sql,get_db_conn());
		}

		/* ���̽� ���� �ϰ��
		echo "<form name=niceform method=post action=\"".$Dir."paygate/E/cancel.php\">\n";
		echo "<input type=hidden name=ordercode value=\"".$ordercode."\">\n";
		echo "<input type=hidden name=TID value=\"".$_ord->pay_auth_no."\">\n";
		echo "<input type=hidden name=CancelAmt value=\"".$re_price."\">\n";
		echo "<input type=hidden name=CancelMsg value=\"������ �κ����\">\n";
		echo "<input type=hidden name=PartialCancelCode value=\"1\">\n"; // ��ü ��� : 0 , �κ���� : 1

		// ����/�����
		echo "<input type=hidden name=SupplyAmt value=\"".$refund_tax."\">\n"; //���ް�
		echo "<input type=hidden name=GoodsVat value=\"".$refund_vat."\">\n"; //�ΰ���
		echo "<input type=hidden name=ServiceAmt value=\"0\">\n"; //�����
		echo "<input type=hidden name=TaxFreeAmt value=\"".$refund_free."\">\n"; //�鼼 �ݾ�

		echo "<input type=hidden name=cancel_uid value=\"".$cancel_uid."\">\n"; // �κ� ��� ��� �ڵ�
		echo "<input type=hidden name=goodsOrg value=\"".$goodsOrg."\">\n"; // �κ� ��� ��ǰ ���

		echo "</form>\n";
		echo "<script> document.niceform.submit(); </script>";
		exit;
		*/

	}

}else if ($type=="bank") {




	$sql = "SELECT count(*) as cnt FROM order_refund_account WHERE ordercode='".$ordercode."'";
	$result=mysql_query($sql,get_db_conn());
	if (mysql_num_rows($result) && mysql_result($result,0,0) > 0){
		$sql = "update order_refund_account set bank='".$bank."', account_name='".$account_name."', account_num='".$account_num."' WHERE ordercode='".$ordercode."'";
	}else{
		$sql = "insert into order_refund_account(ordercode, bank, account_name, account_num, reg_date)";
		$sql .= " values ('".$ordercode."', '".$bank."', '".$account_name."', '".$account_num."', now())";
	}

	mysql_query($sql,get_db_conn());


}
?>
<form name="f" action="order_detail.php" method="post">
<input type="hidden" name="ordercode" value="<?=$ordercode?>">
</form>
<script>
document.f.submit();
</script>