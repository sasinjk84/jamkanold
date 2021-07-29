<?php

    require 'cfg.php';

    /*
    ���ݰ�꼭/��꼭 ���� �����Դϴ�.
    */

    //  hiworks bill ��ü ����
    $HB = new Hiworks_Bill( $cfg['domain'], $cfg['license_id'], $cfg['license_no'], $cfg['partner_id'] );


    //  Type �Է�
    $HB->set_type( HB_DOCUMENTTYPE_TAX , HB_TAXTYPE_TAX, HB_SENDTYPE_SEND );

    //  �⺻���� �Է�
    $HB->set_basic_info( 'ȫ�浿', 'billapi@hiworks.co.kr', '010-111-1111', '�޸�', '111-111', '222-222' );

    $HB->set_document_info(date('Y-m-d'), '300000', '30000', HB_PTYPE_RECEIPT, '', '', '', '', '');

    //  ������ ������ ���޹޴��� ����
    $HB->set_company_info( '214-86-39239', '�����', '�����', '����', '���񽺾�', '�ַ��', HB_COMPANYPREFIX_SUPPLIER );
    $HB->set_company_info( '214-86-39239', '��ü��', '�����', '����', '���񽺾�', '��꼭', HB_COMPANYPREFIX_CONSUMER );

    //  ��������Է�
    $HB->set_work_info( '10', '30', '������', 'EA', '1', '100000', '100000', '10000', '', '110000' );
    $HB->set_work_info( '10', '30', '���ϵ�', 'EA', '1', '200000', '200000', '20000', '', '220000' );

    $rs = $HB->send_document( HB_SOAPSERVER_URL );

    if (!$rs) {
        $HB->showError();
        exit;
    }

    $HB->view('Result :', $HB->get_document_id());
    unset($HB, $rs);
?>