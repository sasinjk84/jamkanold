<?php

    require 'cfg.php';

    /*
    ���ݰ�꼭/��꼭 ���� �����Դϴ�.
    */

    //  hiworks bill ��ü ����
    $HB = new Hiworks_Bill( $cfg['domain'], $cfg['license_id'], $cfg['license_no'], $cfg['partner_id'] );
    $HB->set_delete_id('');

    $documet_result_array = $HB->delete_document( HB_SOAPSERVER_URL );

    if (!$documet_result_array) {
        $HB->showError();
        exit;
    }

    $HB->view('Result :', $documet_result_array);
    unset($HB, $rs);

?>