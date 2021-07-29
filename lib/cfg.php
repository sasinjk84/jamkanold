<?php
$Dir="../";
include_once($Dir."lib/nusoap.php");
include_once($Dir."lib/XML.class.php");
include_once($Dir."lib/Hiworks_Bill.class.php");


/* **************************************** */
/* define ����                                */
/* **************************************** */
define( 'HB_DOCUMENTTYPE_TAX' , 'A' );    // ���ݰ�꼭
define( 'HB_DOCUMENTTYPE_BILL' , 'B' );   // ��꼭
define( 'HB_DOCUMENTTYPE_DETAIL' , 'D' ); // �ŷ�����

define( 'HB_TAXTYPE_TAX', 'A' );		// ����
define( 'HB_TAXTYPE_NOTAX', 'B' );	// ����
define( 'HB_TAXTYPE_MANUAL', 'D' );	// ����

define( 'HB_SENDTYPE_SEND', 'S' );	// ����
define( 'HB_SENDTYPE_RECV', 'R' );	// ����

define( 'HB_PTYPE_RECEIPT', 'R' );	// ����
define( 'HB_PTYPE_CALL', 'C' );		// û��

define( 'HB_COMPANYPREFIX_SUPPLIER', 's' );	// ����ó ���ξ�
define( 'HB_COMPANYPREFIX_CONSUMER', 'r' );	// ����ó ���ξ�

define( 'HB_SOAPSERVER_URL', 'http://billapi.hiworks.co.kr/server.php?wsdl' );	// SOAP Server URL

/* **************************************** */
/* Ÿ�� ����                                */
/* **************************************** */
$document_status = array();
$document_status['W'] = '���ο�û��';
$document_status['T'] = '���ο�û';
$document_status['R'] = '���ο�û';
$document_status['S'] = '����';
$document_status['B'] = '�ݷ�';
$document_status['C'] = '������ҿ�û';
$document_status['A'] = '�����ּҿϷ�';
$document_status['E'] = '����';

define( 'TAXRATE' , '10' );    // ����

class Shop_Billinfo
{
	var $license_no = "";
	var $license_id = "";
	var $domain = "";
	var $partner_id = "";

	var $r_name = "";
	var $r_number = "";
	var $r_tnumber = "";
	var $r_master = "";
	var $r_address = "";
	var $r_condition = "";
	var $r_item = "";
	var $c_name ="";
	var $c_email ="";
	var $c_cell ="";

	var $s_name = "";
	var $s_number = "";
	var $s_tnumber = "";
	var $s_master = "";
	var $s_address = "";
	var $s_condition = "";
	var $s_item = "";

	var $taxrate = TAXRATE;

	var $supplyprice = "";		// supplyprice : ���ް���
	var $tax = "";				// tax : ����

	var $guest_name ="";
	var $reserve = "";
	var $deli_price = "";
	var $dc_price = "";
	var $count = 0;
	var $productname = null;
	var $quantity = null;
	var $taxsum = null;
	var $taxsumquantity = null;
	var $taxsumsale = null;


	function Shop_Billinfo()
	{
		$sql = "SELECT * FROM tblshopbillinfo WHERE bill_state ='Y' limit 1";
		$result=@mysql_query($sql,get_db_conn());
		if($row=@mysql_fetch_object($result)) {
			$this->license_no = $row->license_no;
			$this->license_id = $row->license_id;
			$this->domain = $row->domain;
			$this->partner_id = $row->partner_id;
		}else{
			error_msg("���ڼ��ݰ�꼭 ���� ������ �����ϴ�.","");
		}
	}

	function baseinfo($mem_id)
	{
		$sql = "SELECT * FROM tblmemcompany WHERE memid ='".$mem_id."' ";
		$result = mysql_query($sql,get_db_conn());
		if ($row=mysql_fetch_object($result)) {
			$this->r_name = $row->companyname;
			$this->r_number = $row->companynum;
			$this->r_tnumber = $row->companytnum;
			$this->r_master = $row->companyowner;
			$this->r_address = str_replace("||","",$row->companyaddr);
			$this->r_condition = $row->companybiz;
			$this->r_item = $row->companyitem;

			$this->c_name = $row->c_name;
			$this->c_email = $row->c_email;
			$this->c_cell = $row->c_cell;
		}else{
			error_msg("���޹޴��� ������ �������� �ʽ��ϴ�.","");
		}
		mysql_free_result($result);

		$sql = "SELECT * FROM tblshopinfo ";
		$result = mysql_query($sql,get_db_conn());
		if ($row=mysql_fetch_object($result)) {
			$this->s_name = $row->companyname;
			$this->s_number = $row->companynum;
			$this->s_master = $row->companyowner;
			$this->s_address = str_replace("||","",$row->companyaddr);
			$this->s_condition = $row->companybiz;
			$this->s_item = $row->companyitem;
		}else{
			error_msg("������ ������ �������� �ʽ��ϴ�.","");
		}
		mysql_free_result($result);

	}

	function order_info($ordercode)
	{
		$sql = "SELECT * FROM tblorderinfo WHERE ordercode='".$ordercode."' "; //�߼ۿϷ� :  AND deli_gbn='Y'
		$result = mysql_query($sql,get_db_conn());
		if ($row=mysql_fetch_object($result)) {
			$this->guest_name=$row->sender_name;
			$totalprice=$row->price;
			$reserve=$row->reserve;
			$deli_price=$row->deli_price;
			$dc_price=$row->dc_price;
			$paymethod=$row->paymethod;

			//$this->supplyprice = round($totalprice/(1+$this->taxrate/100));
			//$this->tax = $totalprice-$this->supplyprice;
			//$totalsumprice = $totalprice;

			$this->product_info($ordercode);
			//��Ÿ ����
			$count = $this->count;
			if($deli_price>0) {
				$this->productname[$count]="��۷�";
				$this->quantity[$count]=1;
				$this->taxsum[$count]=round($deli_price/(1+$this->taxrate/100));  //�ܰ�
				$this->taxsumquantity[$count]=$this->taxsum[$count]; //���ް���
				$this->taxsumsale[$count]=$deli_price-$this->taxsumquantity[$count]; //����

				$this->supplyprice = $this->supplyprice + $this->taxsumquantity[$count];
				$this->tax = $this->tax + $this->taxsumsale[$count];
				$count++;
			}
			if($reserve>0) {
				$this->productname[$count]="������";
				$this->quantity[$count]=1;
				$this->taxsum[$count]=-round($reserve/(1+$this->taxrate/100));  //�ܰ�
				$this->taxsumquantity[$count]=$this->taxsum[$count]; //���ް���
				$this->taxsumsale[$count]=-$reserve-$this->taxsumquantity[$count]; //����

				$this->supplyprice = $this->supplyprice + $this->taxsumquantity[$count];
				$this->tax = $this->tax + $this->taxsumsale[$count];
				$count++;
			}
			if($dc_price<0) {
				$this->productname[$count]="���ȸ�� ����";
				$this->quantity[$count]=1;
				$this->taxsum[$count]=round($dc_price/(1+$this->taxrate/100));  //�ܰ�
				$this->taxsumquantity[$count]=$this->taxsum[$count]; //���ް���
				$this->taxsumsale[$count]=$dc_price-$this->taxsumquantity[$count]; //����

				$this->supplyprice = $this->supplyprice + $this->taxsumquantity[$count];
				$this->tax = $this->tax + $this->taxsumsale[$count];
				$count++;
			}
			$this->count = $count;
		}
		mysql_free_result($result);
	}

	function product_info($ordercode){
		$sql="SELECT * FROM tblorderproduct WHERE ordercode='".$ordercode."' ";
		$result=mysql_query($sql,get_db_conn());
		$count=0;
		unset($etcdata);
		while($row=mysql_fetch_object($result)){
			if($row->productcode!="99999999997X") {
				if(ereg("^(COU)([0-9]{8})(X)$",$row->productcode)) {				#����
					if($row->price!=0 && $row->price!=NULL) {
						$etcdata[]=$row;
						continue;
					}
				} else if(ereg("^(9999999999)([0-9]{1})(X|R)$",$row->productcode)) {
					if($row->productcode=="99999999990X") {
						continue;
					} else {
						$etcdata[]=$row;
						continue;
					}
				}
				$this->productname[$count]=strip_tags($row->productname);
				$this->quantity[$count]=$row->quantity;

				$this->taxsum[$count]=round($row->price/(1+$this->taxrate/100));  //�ܰ�
				$this->taxsumquantity[$count]=round($row->price*$row->quantity/(1+$this->taxrate/100)); //���ް���
				$this->taxsumsale[$count]=$row->price*$row->quantity-$this->taxsumquantity[$count]; //����

				$this->supplyprice = $this->supplyprice + $this->taxsumquantity[$count];
				$this->tax = $this->tax + $this->taxsumsale[$count];
				$count++;
			}
		}
		for($k=0;$k<count($etcdata);$k++){
			$this->productname[$count]=strip_tags($etcdata[$k]->productname);
			$this->quantity[$count]=1;
			$this->taxsum[$count]=round($etcdata[$k]->price/(1+$this->taxrate/100));  //�ܰ�
			$this->taxsumquantity[$count]=$this->taxsum[$count]; //���ް���
			$this->taxsumsale[$count]=$etcdata[$k]->price-$this->taxsumquantity[$count]; //����
			$this->supplyprice = $this->supplyprice + $this->taxsumquantity[$count];
			$this->tax = $this->tax + $this->taxsumsale[$count];
			$count++;
		}
		$this->count =$count;
	}

	function set_taxrate ($rate){
		$this->taxrate = $rate;
	}

	function get_serial($ordercode)
	{
		$sql = "SELECT ifnull(max(b_idx),0)+1 as maxidx FROM tblorderbill ";
		$result=@mysql_query($sql,get_db_conn());
		if($row=@mysql_fetch_object($result)) {
			$maxidx = $row->maxidx;
		}
		return substr($ordercode, 0, 8)."-".sprintf("%08d", $maxidx);

	}

}
?>