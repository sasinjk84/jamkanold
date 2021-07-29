<?php
	require 'cfg.php';

	/*
	���ݰ�꼭/��꼭 ���� �����Դϴ�.
	*/

	//  hiworks bill ��ü ����
	$HB = new Hiworks_Bill_V2( $cfg['domain'], $cfg['license_id'], $cfg['license_no'], $cfg['partner_id'] );

	// �⺻���� �Է�
	$HB->set_basic_info('d_type', HB_DOCUMENTTYPE_DETAIL); // d_type : ���ݰ�꼭(HB_DOCUMENTTYPE_TAX), ��꼭(HB_DOCUMENTTYPE_BILL), �ŷ�����(HB_DOCUMENTTYPE_DETAIL)
	$HB->set_basic_info('kind', HB_TAXTYPE_TAX);        // kind : ����(HB_TAXTYPE_TAX), ����(HB_TAXTYPE_NOTAX), ����(HB_TAXTYPE_MANUAL)
	$HB->set_basic_info('sendtype', HB_SENDTYPE_SEND);  // sendtype : ����(HB_SENDTYPE_SEND), ����(HB_SENDTYPE_RECV)

	$HB->set_basic_info('detail_together_tax', '1'); // �ŷ����� �߼� �� ���ݰ�꼭 ���� �߼� ����(�ŷ������� �߼��� ���� �ּ�ó���ϼ���.)

	$HB->set_basic_info('c_name', '�޴´����');             // c_name : ����ڸ�
	$HB->set_basic_info('c_email', 'billapi@hiworks.co.kr'); // c_email : �̸����ּ�
	$HB->set_basic_info('c_cell', '010-000-0000');           // c_cell : �޴���
	$HB->set_basic_info('c_phone', '02-000-0000');           // c_phone : �Ϲ���ȭ

	$HB->set_basic_info('c_name2', '�޴´����');             // c_name2 : ����ڸ�2
	$HB->set_basic_info('c_email2', 'billapi@hiworks.co.kr'); // c_email2 : �̸����ּ�2
	$HB->set_basic_info('c_cell2', '010-000-0000');           // c_cell2 : �޴���2
	$HB->set_basic_info('c_phone2', '02-000-0000');           // c_phone2 : �Ϲ���ȭ2

	$HB->set_basic_info('sc_name', '�����´����');           // sc_name : ����ڸ�
	$HB->set_basic_info('sc_email', 'billapi@hiworks.co.kr'); // sc_email : �̸����ּ�
	$HB->set_basic_info('sc_cell', '010-000-0000');           // sc_cell : �޴���
	$HB->set_basic_info('sc_phone', '02-000-0000');           // sc_phone : �Ϲ���ȭ

	$HB->set_basic_info('memo', '�޸�');                     // memo : �޸�
	$HB->set_basic_info('book_no', '111-111');               // book_no : å��ȣ X�� Xȣ
	$HB->set_basic_info('serial', '222-222');                // serial : �Ϸù�ȣ

	$HB->set_document_info('issue_date', date('Y-m-d')); // issue_date : �ۼ���
	$HB->set_document_info('supplyprice', '500000');     // supplyprice : ���ް���
	$HB->set_document_info('tax', '50000');              // tax : ����
	$HB->set_document_info('p_type', HB_PTYPE_RECEIPT);  // ptype : ����(HB_PTYPE_RECEIPT), û��(HB_PTYPE_CALL)
	$HB->set_document_info('remark', '');                // remark : ���
	$HB->set_document_info('money', '');                 // money : ����
	$HB->set_document_info('moneycheck', '');            // moneycheck : ��ǥ
	$HB->set_document_info('bill', '');                  // bill : ����
	$HB->set_document_info('uncollect', '');             // uncollect : �ܻ�̼���

	// ������ ����
	$HB->set_company_info('s_number', '111-11-11111'); // s_number : ��Ϲ�ȣ
	$HB->set_company_info('s_tnumber', '1111');        // s_tnumber : ��������ȣ
	$HB->set_company_info('s_name', '��ȣ');           // s_name : ��ȣ(���θ�)
	$HB->set_company_info('s_master', '��ǥ�ڼ���');   // s_master : ����(��ǥ��)
	$HB->set_company_info('s_address', '�ּ�');        // s_address : �ּ�
	$HB->set_company_info('s_condition', '����');      // s_condition : ����
	$HB->set_company_info('s_item', '����');           // s_item : ����

	// ���޹޴��� ����
	$HB->set_company_info('r_number', '222-22-22222'); // r_number : ��Ϲ�ȣ
	$HB->set_company_info('r_tnumber', '2222');        // r_tnumber : ��������ȣ
	$HB->set_company_info('r_name', '��ȣ');           // r_name : ��ȣ(���θ�)
	$HB->set_company_info('r_master', '��ǥ�ڼ���');   // r_master : ����(��ǥ��)
	$HB->set_company_info('r_address', '�ּ�');        // r_address : �ּ�
	$HB->set_company_info('r_condition', '����');      // r_condition : ����
	$HB->set_company_info('r_item', '����');           // r_item : ����

	// ��������Է�
	// ���ݰ�꼭, ��꼭�� �ִ� 4��, �ŷ������� �ִ� 20������ �Է°�����
	$HB->set_work_info( '10', '30', '������ euc-kr', 'EA', '1', '100000', '100000', '10000', '', '110000' );
	$HB->set_work_info( '10', '30', '���ϵ�', 'EA', '1', '200000', '200000', '20000', '', '220000' );
	$HB->set_work_info( '10', '30', '���ϵ�', 'EA', '1', '200000', '200000', '20000', '', '220000' );

	$rs = $HB->send_document( HB_SOAPSERVER_URL );

	if (!$rs) {
		$HB->showError();
		exit;
	}

	$HB->view('Result :', $HB->get_document_id());
	unset($HB, $rs);
?>