<?php

    require 'cfg.php';

    /*
    ���ݰ�꼭/��꼭 ����üũ �����Դϴ�.
    */

    $document_id = '';

    //  hiworks bill ��ü ����
    $HB = new Hiworks_Bill( $cfg['domain'], $cfg['license_id'], $cfg['license_no'], $cfg['partner_id'] );
    $HB->set_document_id($document_id);


    $documet_result_array = $HB->check_document( HB_SOAPSERVER_URL );

    if (!$documet_result_array) {
        $HB->showError();
        exit;
    }

    $HB->view('Result :', $documet_result_array);
    unset($HB, $rs);

?>