<?php

$doc = '.';

include $doc . '/lib/nusoap.php';
include $doc . '/lib/XML.class.php';
include $doc . '/lib/Hiworks_Bill.class.php';

/*
ȸ�� ������ �������ּ���
*/
$cfg = array();

$cfg['license_no'] = '';    //  �߱޹����� ��ȣ�� �Է����ּ���
$cfg['license_id'] = '';    //  ���̿��� ���̵� �Է����ּ���
$cfg['domain'] = '';    //  ���̿��� �������� �Է����ּ���
$cfg['partner_id'] = '';    //  ���̿��� ȸ�� �ڵ带 �Է����ּ���


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
$document_status['W'] = '�̹߼�';
$document_status['T'] = '�̿���';
$document_status['R'] = '����';
$document_status['S'] = '����';
$document_status['B'] = '�ݷ�';
$document_status['C'] = '������ҿ�û';
$document_status['A'] = '�����ּҿϷ�';

?>