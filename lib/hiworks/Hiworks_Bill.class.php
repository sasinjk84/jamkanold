<?php
class Hiworks_Bill
{
	var $client = null;
	var $document_id = null;
	var $delete_id = null;
	var $error = array();

	var $builtin_array = array();	// ���� ����
	var $basic_array = array();		// ������, �������, ȸ�系�� ���� ��ȣ ����
	var $document_array = array();	// ���� ����

	var $supply_array = array();	// ����,���� ����� ����
	var $work_array = array();		// �׸� ����

	var $check_document_array = array();    // document_id üũ�� ����

	var $send_param = array(); // GetParam ���� ������ ���� �迭

	var $sum_price = 0;
	var $sum_tax = 0;

	/*
	* domain : ������
	* license_id : ���̿��� ������/�׷���̵�
	* license_no : ���̿��� ������ȣ
	*/
	function Hiworks_Bill( $domain, $license_id, $license_no, $partner_id )
	{
		if (!$this->is($domain)||!$this->is($license_id)||!$this->is($license_no)||!$this->is($partner_id)) {
			die('Not Found!!');
		}

		$this->builtin_array['domain'] = $domain;
		$this->builtin_array['license_id'] = $license_id;
		$this->builtin_array['license_no'] = $license_no;
		$this->builtin_array['partner_id'] = $partner_id;

	}   // end of Hiworks_Bill : construction

	/*
	* type : ���ݰ�꼭(A), ��꼭(B)
	* kind : ����(A), ����(B), ����(D)
	* sendtype : ����(S), ����(R)
	*/
	function set_type( $type='A', $kind='A', $sendtype='S' )
	{
		if(strtoupper($type) == 'D') {
			$this->_setError("�ŷ������� �����Ͻ÷��� Hiworks_Bill_V2 Ŭ������ ������ּ���.");
			$this->showError();
			exit();
			return false;
		}
		$this->basic_array['d_type'] = (in_array(strtoupper($type), array('A', 'B'))) ? $type : 'A';
		$this->basic_array['kind'] = (in_array(strtoupper($kind), array('A', 'B', 'D'))) ? $kind : 'A';
		$this->basic_array['sendtype'] = (in_array(strtoupper($sendtype), array('S', 'R'))) ? $sendtype : 'S';

		if( $this->basic_array['d_type'] == 'B' ) {
			$this->basic_array['kind'] = 'B';
		}

		return true;
	}

	/*
	* name : ����ڸ�
	* email : �̸����ּ�
	hp : �޴���
	memo : �޸�
	book_no : å��ȣ X�� Xȣ
	serial_no : �Ϸù�ȣ
	*/
	function set_basic_info( $name, $email, $hp='', $memo='', $book_no='', $serial='' )
	{

		if (!$this->is($name)||!$this->is($email)) {
			return false;
		}

		if ($book_no&&$this->is_bar_type($book_no)) {
			return false;
		}

		if ($serial&&$this->is_bar_type($serial)) {
			return false;
		}

		$this->basic_array['c_name'] = $name;
		$this->basic_array['c_email'] = $email;
		$this->basic_array['c_cell'] = $hp;
		$this->basic_array['memo'] = $memo;
		$this->basic_array['book_no'] = $book_no;
		$this->basic_array['serial'] = $serial;

		return true;
	}   // end of set_basic_info

	/*
	* number : ��Ϲ�ȣ
	* name : ��ȣ(���θ�)
	* master : ����(��ǥ��)
	* address : �ּ�
	* condition : ����
	* item : ����
	prefix : ������(s), ���޹޴���(r)
	*/
	function set_company_info( $number, $name, $master, $address='', $condition='', $item='', $prefix='s' )
	{

		if (!$this->is($number)||!$this->is($name)||!$this->is($master)) {
			return false;
		}

		$pre = (in_array(strtolower($prefix), array('s', 'r'))) ? $prefix : 's';
		$key = ($pre=='s') ? 0 : 1;
		$this->supply_array[$key][$pre.'_number'] = $number;
		$this->supply_array[$key][$pre.'_name'] = $name;
		$this->supply_array[$key][$pre.'_master'] = $master;
		$this->supply_array[$key][$pre.'_address'] = $address;
		$this->supply_array[$key][$pre.'_condition'] = $condition;
		$this->supply_array[$key][$pre.'_item'] = $item;

		return true;
	}   // end of set_supply_info

	/*
	* issue_date : �ۼ���
	* supplyprice : ���ް���
	* tax : ����
	* totalprice : �հ�ݾ�
	* ptype : ����(R), û��(C)
	* remark : ���
	* money : ����
	* moneycheck : ��ǥ
	* bill : ����
	* uncollect : �ܻ�̼���
	*/
	function set_document_info( $issue_date, $supplyprice, $tax, $ptype='R', $remark='', $money='', $moneycheck='', $bill='', $uncollect='' )
	{
		if (!$this->is($issue_date)||!$this->is($supplyprice)||!$this->is($tax)) {
			return false;
		}

		if ($this->is_bar_type($issue_date)) {
			return false;
		}

		$this->document_array['issue_date'] = $issue_date;
		$this->document_array['supplyprice'] = $this->cleaner($supplyprice);
		$this->document_array['tax'] = $this->cleaner($tax);
		//$this->document_array['total'] = $this->cleaner($totalprice);
		$this->document_array['p_type'] = (in_array(strtoupper($ptype), array('R', 'C'))) ? $ptype : 'R';
		$this->document_array['remark'] = $remark;
		$this->document_array['money'] = $money;
		$this->document_array['moneycheck'] = $moneycheck;
		$this->document_array['bill'] = $bill;
		$this->document_array['uncollect'] = $uncollect;

		return true;
	}   // end of set_extra_info


	/*
	* mm : ��
	* dd : ��
	* subject : ǰ��
	* form : �԰�
	* count : ����
	* oneprice : �ܰ�
	price : ���ް���
	tax_row : ����
	etc : ���
	sum : �հ�
	*/
	function set_work_info( $mm, $dd, $subject, $form, $count, $oneprice, $price=0, $tax_row=0, $etc='', $sum=0 )
	{
		if (!$this->is($count)||!$this->is($oneprice)) {
			return false;
		}

		$count = $this->cleaner($count);
		$oneprice = $this->cleaner($oneprice);
		$price = $this->cleaner($price);
		$tax_row = $this->cleaner($tax_row);
		$sum = $this->cleaner($sum);

		$this->sum_price += $price;
		$this->sum_tax += $tax_row;

		$c = count($this->work_array);
		$this->work_array[$c]['mm'] = $mm;
		$this->work_array[$c]['dd'] = $dd;
		$this->work_array[$c]['subject'] = $subject;
		$this->work_array[$c]['form'] = $form;
		$this->work_array[$c]['count'] = $count;
		$this->work_array[$c]['oneprice'] = $oneprice;
		$this->work_array[$c]['price'] = $price;
		$this->work_array[$c]['tax_row'] = $tax_row;
		$this->work_array[$c]['etc'] = $etc;
		$this->work_array[$c]['sum'] = $sum;

		return true;
	}   // end of set_work_info

	/*
	* id : document_id
	*/
	function set_document_id($id)
	{
		if (!$this->is($id)) {
			return false;
		}

		$id = $this->cleaner($id);

		$c = count($this->check_document_array);
		$this->check_document_array[$c]['id'] = $id;

		return true;
	}

	function set_delete_id($id)
	{
		if (!$this->is($id)) {
			return false;
		}

		$this->delete_id = $this->cleaner($id);

		return true;
	}

	function get_delete_id()
	{
		return $this->delete_id;
	}

	function _merge_delete_array()
	{
		$array = array();
		$array = array_merge($array, $this->builtin_array);
		$send_array = array();
		$send_array['delete_id'] = $this->get_delete_id();
		$send_array = array_merge($send_array, $array);

		return $send_array;
	}

	function _merge_document_array()
	{
		$array = array();
		$array = array_merge($array, $this->builtin_array);
		$send_array = array();
		$send_array['document_id_array'] = $this->check_document_array;
		$send_array = array_merge($send_array, $array);

		return $send_array;
	}

	/*
	�Է¹��� �迭���� �����Ѵ�.
	*/
	function _merge_array()
	{
		$array = array();
		$array = array_merge($array, $this->builtin_array);
		$array = array_merge($array, $this->basic_array );
		$array = array_merge($array, $this->document_array);

		$send_array = array();
		$send_array['service_info_array'] = $this->supply_array;
		$send_array['service_account_array'] = $this->work_array;
		$send_array = array_merge($send_array, $array);

		return $send_array;
	}

	/*
	�迭�� ���� �������� ����üũ
	*/
	function _check_send_array($array)
	{

		if (($this->sum_price) != $array['supplyprice']) {
			$this->_setError("Error Account : supplyprice[".$this->sum_price.'  '.$array['supplyprice'].']');
			return false;
		}

		if ($this->sum_tax != $array['tax']) {
			$this->_setError("Error Account : tax");
			return false;
		}

		return true;
	}

	function _setError($error)
	{
		$this->error = $error;
	}

	function _getError() {
		return $this->error;
	}

	function _set_document_id($id)
	{
		$this->document_id = $id;
	}

	function get_document_id()
	{
		return $this->document_id;
	}

	function showError() {

		$line = $this->_getError();
		if(strpos($line, '|') !== false) {
			list($code, $msg) = explode('|', $line);
			echo 'Error Code : '.$code;
			echo '<br />Error Msg : '.$msg;
		} else {
			$this->view('Error :', $line);
		}

	}

	/*
	soap ������ �����Ѵ�.
	*/
	function send_document($serverpath)
	{
		echo $serverpath;
		exit;
		if (!$serverpath) {
			$this->_setError('serverpath not found!');
			return false;
		}
		$send_array = $this->_merge_array();
		if (!$this->_check_send_array( $send_array ))  {
			return false;
		}

		//  soap client ��ü�����
		$this->client = new nusoap_client($serverpath, true);
		$this->client->decode_utf8 = false;

		//  soap ���� üũ
		if ($this->client->getError()) {
			$this->_setError($this->client->getError());
			return false;
		}

		//  proxy ����
		$proxy = $this->client->getProxy();
		//  ������ LaunchOut �޼ҵ带 �����ϰ� ���ϰ��� �����޴´�.

		$result = $proxy->LaunchOut( $send_array );
		list($code, $msg) = explode('|', $result);

		if ($code=='0000') {
			$this->_set_document_id($msg);
			return $code;
		} else {
			$this->_setError($result);
			return false;
		}
	}

	function delete_document($serverpath)
	{
		if (!$serverpath) {
			$this->_setError('serverpath not found!');
			return false;
		}

		$send_array = $this->_merge_delete_array();

		if (!$send_array) {
			$this->_setError('delete_id not found!');
			return false;
		}

		//  soap client ��ü�����
		$this->client = new nusoap_client($serverpath, true);
		$this->client->decode_utf8 = false;

		//  soap ���� üũ
		if ($this->client->getError()) {
			$this->_setError($this->client->getError());
			return false;
		}

		//  proxy ����
		$proxy = $this->client->getProxy();

		//  ������ LaunchOut �޼ҵ带 �����ϰ� ���ϰ��� �����޴´�.
		$result = $proxy->DeleteDocumentId( $send_array );

		return $result;

	}

	function check_document($serverpath)
	{
		if (!$serverpath) {
			$this->_setError('serverpath not found!');
			return false;
		}
		$send_array = $this->_merge_document_array();

		//  soap client ��ü�����
		$this->client = new nusoap_client($serverpath, true);
		$this->client->decode_utf8 = false;

		//  soap ���� üũ
		if ($this->client->getError()) {
			$this->_setError($this->client->getError());
			return false;
		}

		//  proxy ����
		$proxy = $this->client->getProxy();

		//  ������ LaunchOut �޼ҵ带 �����ϰ� ���ϰ��� �����޴´�.
		$result = $proxy->CheckDocumentId( $send_array );

		return $result;
	}

	function is($x) {
		return (!empty($x)||isset($x)) ? true : false;
	}

	function is_bar_type($x)
	{
		$y = explode('-', $x);
		if(($yLen = count($y)) > 0) return false;
		for($i=0; $i<$yLen; $i++) {
			if(preg_match('[^0-9]+', $y[$i])) return false;
		}
		return true;
	}

	function cleaner($x) {
		return str_replace(',', '', $x);
	}

	function view($x, $y)
	{
		echo $x.'<pre>';
		if (is_array($y)) {
			print_r($y);
		} else {
			echo htmlspecialchars($y);
		}
		echo '</pre>';
	}
}   // end of class : Hiworks_Bill

class Hiworks_Bill_V2 {
	var $client = null;
	var $document_id = null;
	var $delete_id = null;
	var $error = array();

	var $builtin_array = array();	// ���� ����
	var $basic_array = array();		// ������, �������, ȸ�系�� ���� ��ȣ ����
	var $document_array = array();	// ���� ����

	var $supply_array = array();	// ����,���� ����� ����
	var $work_array = array();		// �׸� ����

	var $check_document_array = array();    // document_id üũ�� ����

	var $sum_price = 0;
	var $sum_tax = 0;

	var $dup_check = true;

	/*
	* domain : ������
	* license_id : ���̿��� ������/�׷���̵�
	* license_no : ���̿��� ������ȣ
	*/
	function Hiworks_Bill_V2( $domain, $license_id, $license_no, $partner_id )
	{
		if (!$this->is($domain)||!$this->is($license_id)||!$this->is($license_no)||!$this->is($partner_id)) {
			die('Not Found!!');
		}

		$this->builtin_array['domain'] = $domain;
		$this->builtin_array['license_id'] = $license_id;
		$this->builtin_array['license_no'] = $license_no;
		$this->builtin_array['partner_id'] = $partner_id;

	} // end of Hiworks_Bill : construction

	/*
	* id : document_id
	*/
	function set_document_id($id)
	{
		if (!$this->is($id)) {
			return false;
		}

		$id = $this->cleaner($id);

		$c = count($this->check_document_array);
		$this->check_document_array[$c]['id'] = $id;

		return true;
	}

	function set_delete_id($id)
	{
		if (!$this->is($id)) {
			return false;
		}

		$this->delete_id = $this->cleaner($id);

		return true;
	}

	function get_delete_id()
	{
		return $this->delete_id;
	}

	function _merge_delete_array()
	{
		$array = array();
		$array = array_merge($array, $this->builtin_array);
		$send_array = array();
		$send_array['delete_id'] = $this->get_delete_id();
		$send_array = array_merge($send_array, $array);

		return $send_array;
	}

	function _merge_document_array()
	{
		$array = array();
		$array = array_merge($array, $this->builtin_array);
		$send_array = array();
		$send_array['document_id_array'] = $this->check_document_array;
		$send_array = array_merge($send_array, $array);

		return $send_array;
	}

	function set_basic_info($name, $value) {
		if($this->get_dup_check() && isset($this->basic_array[$name])) {
			return false;
		}
		$this->basic_array[$name] = $value;
	}

	function set_company_info($name, $value) {
		if(strpos($name, 's_') === 0) {
			$this->supply_array[0][$name] = $value;
		} else if(strpos($name, 'r_') === 0) {
			$this->supply_array[1][$name] = $value;
		}
	}

	function set_document_info($name, $value) {
		$this->document_array[$name] = $value;
	}

	/*
	* mm : ��
	* dd : ��
	* subject : ǰ��
	* form : �԰�
	* count : ����
	* oneprice : �ܰ�
	price : ���ް���
	tax_row : ����
	etc : ���
	sum : �հ�
	*/
	function set_work_info( $mm, $dd, $subject, $form, $count, $oneprice, $price=0, $tax_row=0, $etc='', $sum=0 )
	{
		if (!$this->is($count)||!$this->is($oneprice)) {
			return false;
		}

		$count = $this->cleaner($count);
		$oneprice = $this->cleaner($oneprice);
		$price = $this->cleaner($price);
		$tax_row = $this->cleaner($tax_row);
		$sum = $this->cleaner($sum);

		$this->sum_price += $price;
		$this->sum_tax = $tax_row;

		$c = count($this->work_array);
		$this->work_array[$c]['mm'] = $mm;
		$this->work_array[$c]['dd'] = $dd;
		$this->work_array[$c]['subject'] = $subject;
		$this->work_array[$c]['form'] = $form;
		$this->work_array[$c]['count'] = $count;
		$this->work_array[$c]['oneprice'] = $oneprice;
		$this->work_array[$c]['price'] = $price;
		$this->work_array[$c]['tax_row'] = $tax_row;
		$this->work_array[$c]['etc'] = $etc;
		$this->work_array[$c]['sum'] = $sum;

		return true;
	}   // end of set_work_info

	/*
	�Է¹��� �迭���� �����Ѵ�.
	*/
	function _merge_array()
	{
		$array = array();
		//$array = array_merge($array, $this->builtin_array);

		$this->basic_array['d_type'] = (in_array(strtoupper($this->basic_array['d_type']), array('A', 'B', 'D'))) ? $this->basic_array['d_type'] : 'A';
		$this->basic_array['kind'] = (in_array(strtoupper($this->basic_array['kind']), array('A', 'B', 'D'))) ? $this->basic_array['kind'] : 'A';
		$this->basic_array['sendtype'] = (in_array(strtoupper($this->basic_array['sendtype']), array('S', 'R'))) ? $this->basic_array['sendtype'] : 'S';
		if( $this->basic_array['d_type'] == 'B' ) {
			$this->basic_array['kind'] = 'B';
		}

		if( $this->basic_array['d_type'] == 'B' ) {
			$this->basic_array['kind'] = 'B';
		}

		if (!$this->is($this->basic_array['c_name']) || !$this->is($this->basic_array['c_email'])) {
			return false;
		}
		if ($this->basic_array['book_no'] && $this->is_bar_type($this->basic_array['book_no'])) {
			return false;
		}
		if ($this->basic_array['serial'] && $this->is_bar_type($this->basic_array['serial'])) {
			return false;
		}

		if (
			!$this->is($this->supply_array[0]['s_number']) || !$this->is($this->supply_array[0]['s_name']) || !$this->is($this->supply_array[0]['s_master'])
			||
			!$this->is($this->supply_array[1]['r_number']) || !$this->is($this->supply_array[1]['r_name']) || !$this->is($this->supply_array[1]['r_master'])
		) {
            return false;
        }

		if(!$this->is($this->document_array['issue_date']) || !$this->is($this->document_array['supplyprice']) || !$this->is($this->document_array['tax'])) {
			return false;
		}
		if ($this->is_bar_type($this->document_array['issue_date'])) {
			return false;
		}

		$this->document_array['supplyprice'] = $this->cleaner($this->document_array['supplyprice']);
		$this->document_array['tax'] = $this->cleaner($this->document_array['tax']);
		$this->document_array['p_type'] = (in_array(strtoupper($this->document_array['p_type']), array('R', 'C'))) ? $this->document_array['p_type'] : 'R';

		foreach($this->work_array as $key => $value) {
			$this->work_array[$key]['count'] = $this->cleaner($value['count']);
			$this->work_array[$key]['oneprice'] = $this->cleaner($value['oneprice']);
			$this->work_array[$key]['price'] = $this->cleaner($value['price']);
			$this->work_array[$key]['tax_row'] = $this->cleaner($value['tax_row']);
			$this->work_array[$key]['sum'] = $this->cleaner($value['sum']);

			$this->sum_price += $this->work_array[$c]['price'];
			$this->sum_tax += $this->work_array[$c]['tax_row'];
		}

		$array = array_merge($array, $this->basic_array );
		$array = array_merge($array, $this->document_array);

		$send_array = array();
		$send_array['service_info_array'] = $this->supply_array;
		$send_array['service_account_array'] = $this->work_array;
		$send_array = array_merge($send_array, $array);

		return $send_array;
	}

	/*
	�迭�� ���� �������� ����üũ
	*/
	function _check_send_array($array)
	{
		
		if (($this->sum_price) != $array['supplyprice']) {
			$this->_setError("Error Account : supplyprice ");
			return false;
		}

		if ($this->sum_tax != $array['tax']) {

			$this->_setError("Error Account : tax");
			return false;
		}

		return true;
	}

	function _setError($error)
	{
		$this->error = $error;
	}

	function _getError() {
		return $this->error;
	}

	function _set_document_id($id)
	{
		$this->document_id = $id;
	}

	function get_document_id()
	{
		return $this->document_id;
	}

	// �̹� �����Ͱ� �ִµ� �ٽ� �Է� �� ���� �߻�
	function set_dup_check() {
		$this->dup_check = true;
	}

	// �̹� �����Ͱ� �ִµ� �ٽ� �Է� �� ���� �߻� ����
	function unset_dup_check() {
		$this->dup_check = false;
	}

	function get_dup_check() {
		return $this->dup_check;
	}

	function showError() {
		$line = $this->_getError();
		if(strpos($line, '|') !== false) {
			list($code, $msg) = explode('|', $line);
			echo 'Error Code : '.$code;
			echo '<br />Error Msg : '.$msg;
		} else {
			$this->view('Error :', $this->_getError());
		}
	}

	/*
	soap ������ �����Ѵ�.
	*/
	function send_document($serverpath) {	
		if (!$serverpath) {
			$this->_setError('serverpath not found!');
			return false;
		}
		$send_array = $this->_merge_array();

		if (!$this->_check_send_array( $send_array ))  {
			return false;
		}

		$xml_array = array('HiworksBillData' => $send_array);

		$xml_str = array_to_xml($xml_array);
		$this->builtin_array['data'] = base64_encode(urlencode($xml_str));

		//  soap client ��ü�����
		$this->client = new nusoap_client($serverpath, true);
		$this->client->decode_utf8 = false;

		//  soap ���� üũ
		if ($this->client->getError()) {
			$this->_setError($this->client->getError());
			return false;
		}

		//  proxy ����
		$proxy = $this->client->getProxy();
		//  ������ LaunchOut �޼ҵ带 �����ϰ� ���ϰ��� �����޴´�.

		$result = $proxy->LaunchOutV2( $this->builtin_array );
		list($code, $msg) = explode('|', $result);

		if ($code=='0000') {
			$this->_set_document_id($msg);
			return $code;
		} else {
			$this->_setError($result);
			return false;
		}
	}

	function delete_document($serverpath)
	{
		if (!$serverpath) {
			$this->_setError('serverpath not found!');
			return false;
		}

		$send_array = $this->_merge_delete_array();

		if (!$send_array) {
			$this->_setError('delete_id not found!');
			return false;
		}

		//  soap client ��ü�����
		$this->client = new nusoap_client($serverpath, true);
		$this->client->decode_utf8 = false;

		//  soap ���� üũ
		if ($this->client->getError()) {
			$this->_setError($this->client->getError());
			return false;
		}

		//  proxy ����
		$proxy = $this->client->getProxy();

		//  ������ LaunchOut �޼ҵ带 �����ϰ� ���ϰ��� �����޴´�.
		$result = $proxy->DeleteDocumentId( $send_array );

		return $result;

	}

	function check_document($serverpath)
	{
		if (!$serverpath) {
			$this->_setError('serverpath not found!');
			return false;
		}
		$send_array = $this->_merge_document_array();

		//  soap client ��ü�����
		$this->client = new nusoap_client($serverpath, true);
		$this->client->decode_utf8 = false;

		//  soap ���� üũ
		if ($this->client->getError()) {
			$this->_setError($this->client->getError());
			return false;
		}

		//  proxy ����
		$proxy = $this->client->getProxy();

		//  ������ LaunchOut �޼ҵ带 �����ϰ� ���ϰ��� �����޴´�.
		$result = $proxy->CheckDocumentId( $send_array );

		return $result;
	}

	function set_param($name, $value) {
		if(!isset($name{0})) {
			$this->_setError("name �׸��� ����ֽ��ϴ�.");
			$this->showError();
			exit();
		}

		if(!isset($value{0})) {
			$this->_setError("���� �׸��� ����ֽ��ϴ�.");
			$this->showError();
			exit();
		}

		$this->send_param[] = array($name, $value);
	}

	function _merge_param() {
		if(count($this->send_param) < 1) {
			$this->_setError("������ ������ �����ϴ�.");
			$this->showError();
			exit();
		}

		$return_array = array();
		foreach($this->send_param as $value) {
			$return_array[$value[0]] = $value[1];
		}

		return $return_array;
	}

	function send_param($serverpath) {
		if (!$serverpath) {
			$this->_setError('serverpath not found!');
			return false;
		}
		$send_array = $this->_merge_param();

		$xml_array = array('HiworksBillData' => $send_array);

		$xml_str = array_to_xml($xml_array);
		$this->builtin_array['data'] = base64_encode(urlencode($xml_str));

		//  soap client ��ü�����
		$this->client = new nusoap_client($serverpath, true);
		$this->client->decode_utf8 = false;

		//  soap ���� üũ
		if ($this->client->getError()) {
			$this->_setError($this->client->getError());
			return false;
		}

		//  proxy ����
		$proxy = $this->client->getProxy();

		//  ������ GetParam �޼ҵ带 �����ϰ� ���ϰ��� �����޴´�.
		$result = xml_to_array($proxy->GetParam($this->builtin_array));

		return $result['HiworksBillData'];
	}

	function is($x) {
		return (!empty($x)||isset($x)) ? true : false;
	}

	function is_bar_type($x)
	{
		$y = explode('-', $x);
		if(($yLen = count($y)) > 0) return false;
		for($i=0; $i<$yLen; $i++) {
			echo $y[$i]."\n";
			if(preg_match('[^0-9]+', $y[$i])) return false;
		}
		return true;
	}

	function cleaner($x) {
		return str_replace(',', '', $x);
	}

	function view($x, $y)
	{
		echo $x.'<pre>';
		if (is_array($y)) {
			print_r($y);
		} else {
			echo htmlspecialchars($y);
		}
		echo '</pre>';
	}
}   // end of class : Hiworks_Bill

?>