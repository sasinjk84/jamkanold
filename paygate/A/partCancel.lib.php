<?
    /* ============================================================================== */
    /* =   PAGE : ���� ���� ȯ�� ���� PAGE                                          = */
    /* = -------------------------------------------------------------------------- = */
    /* =   Copyright (c)  2008   KCP Inc.   All Rights Reserverd.                   = */
    /* ============================================================================== */


    /* ============================================================================== */
    /* =   01. KCP ���� ���� ���� ����                                              = */
    /* = -------------------------------------------------------------------------- = */
    $g_conf_home_dir = $Dir."paygate/A/payplus_new"; // �� ���θ� ��� ��ġ ���� ���. bin������
    $g_conf_log_dir  = $Dir."paygate/A/payplus_new/log";           // log ������ �Է�

    $g_conf_pa_url   = "paygw.kcp.co.kr";							// real url : paygw.kcp.co.kr , test url : testpaygw.kcp.co.kr
    $g_conf_pa_port  = "8090";
    /* ============================================================================== */


	/* ============================================================================== */
	/* =   02. ���� ��û ���� ����                                                  = */
	/* = -------------------------------------------------------------------------- = */
	$site_cd        = $_POST[  "site_cd"         ];             // ����Ʈ �ڵ�
	$site_key       = $_POST[  "site_key"        ];             // ����Ʈ Ű
	$req_tx         = $_POST[  "req_tx"          ];             // ��û ����
	$cust_ip        = getenv(  "REMOTE_ADDR"     );             // ��û IP
	$ordr_idxx      = $_POST[  "ordr_idxx"       ];             // ���θ� �ֹ���ȣ
	$good_name      = $_POST[  "good_name"       ];             // ��ǰ��
	/* = -------------------------------------------------------------------------- = */
	$good_mny       = $_POST[  "good_mny"        ];             // ���� �ѱݾ�
	$tran_cd        = $_POST[  "tran_cd"         ];             // ó�� ����
	/* = -------------------------------------------------------------------------- = */
	$res_cd         = "";                                       // �����ڵ�
	$res_msg        = "";                                       // ����޽���
	$tno            = $_POST[  "tno"             ];             // KCP �ŷ� ���� ��ȣ
	/* = -------------------------------------------------------------------------- = */
	$buyr_name      = $_POST[  "buyr_name"       ];             // �ֹ��ڸ�
	$buyr_tel1      = $_POST[  "buyr_tel1"       ];             // �ֹ��� ��ȭ��ȣ
	$buyr_tel2      = $_POST[  "buyr_tel2"       ];             // �ֹ��� �ڵ��� ��ȣ
	$buyr_mail      = $_POST[  "buyr_mail"       ];             // �ֹ��� E-mail �ּ�
	/* = -------------------------------------------------------------------------- = */
	$bank_name      = "";                                       // �����
	$bank_issu      = $_POST[  "bank_issu"       ];             // ������ü ���񽺻�
	/* = -------------------------------------------------------------------------- = */
	$mod_type       = $_POST[  "mod_type"        ];             // ����TYPE VALUE ������ҽ� �ʿ�
	$mod_desc       = $_POST[  "mod_desc"        ];             // �������
	/* = -------------------------------------------------------------------------- = */
	$use_pay_method = $_POST[  "use_pay_method"  ];             // ���� ���
	$bSucc          = "";                                       // ��ü DB ó�� ���� ����
	$acnt_yn        = $_POST[  "acnt_yn"         ];             // ���º���� ������ü, ������� ����
	/* = -------------------------------------------------------------------------- = */
	$card_cd        = "";                                       // �ſ�ī�� �ڵ�
	$card_name      = "";                                       // �ſ�ī�� ��
	$app_time       = "";                                       // ���νð� (��� ���� ���� ����)
	$app_no         = "";                                       // �ſ�ī�� ���ι�ȣ
	$noinf          = "";                                       // �ſ�ī�� ������ ����
	$quota          = "";                                       // �ſ�ī�� �Һΰ���
	$bankname       = "";                                       // �����
	$depositor      = "";                                       // �Ա� ���� ������ ����
	$account        = "";                                       // �Ա� ���� ��ȣ
	/* = -------------------------------------------------------------------------- = */
	$escw_used      = $_POST[  "escw_used"       ];             // ����ũ�� ��� ����
	$pay_mod        = $_POST[  "pay_mod"         ];             // ����ũ�� ����ó�� ���
	$deli_term      = $_POST[  "deli_term"       ];             // ��� �ҿ���
	$bask_cntx      = $_POST[  "bask_cntx"       ];             // ��ٱ��� ��ǰ ����
	$good_info      = $_POST[  "good_info"       ];             // ��ٱ��� ��ǰ �� ����
	$rcvr_name      = $_POST[  "rcvr_name"       ];             // ������ �̸�
	$rcvr_tel1      = $_POST[  "rcvr_tel1"       ];             // ������ ��ȭ��ȣ
	$rcvr_tel2      = $_POST[  "rcvr_tel2"       ];             // ������ �޴�����ȣ
	$rcvr_mail      = $_POST[  "rcvr_mail"       ];             // ������ E-Mail
	$rcvr_zipx      = $_POST[  "rcvr_zipx"       ];             // ������ �����ȣ
	$rcvr_add1      = $_POST[  "rcvr_add1"       ];             // ������ �ּ�
	$rcvr_add2      = $_POST[  "rcvr_add2"       ];             // ������ ���ּ�
	/* ============================================================================== */

/* ====================================================================== */
/* =   ���� ���� CLASS                                                  = */
/* ====================================================================== */
class   C_PAYPLUS_CLI
{
    var   $m_payx_data;
    var   $m_ordr_data;
    var   $m_rcvr_data;
    var   $m_escw_data;
    var   $m_modx_data;
    var   $m_encx_data;

    /* -------------------------------------------------------------------- */
    /* -   ó�� ��� ��                                                   - */
    /* -------------------------------------------------------------------- */
    var   $m_res_data;
    var   $m_res_cd;
    var   $m_res_msg;

    /* -------------------------------------------------------------------- */
    /* -   ������                                                         - */
    /* -------------------------------------------------------------------- */
    function  C_PAYPLUS_CLI()
    {
        $this->m_payx_data="payx_data=";
        $this->m_payx_common="";
        $this->m_payx_card="";
        $this->m_ordr_data="";
        $this->m_rcvr_data="";
        $this->m_escw_data="";
        $this->m_modx_data="";
        $this->m_encx_data="";
    }

    function  mf_init( $mode )
    {
      if ( $mode == "1" )
      {
        if ( !extension_loaded( 'pp_cli_dl_php' ) )
        {
          dl( "pp_cli_dl_php.so" );
        }
      }
    }

    function  mf_clear()
    {
        $this->m_payx_data="payx_data=";
        $this->m_payx_common="";
        $this->m_payx_card="";
        $this->m_ordr_data="";
        $this->m_rcvr_data="";
        $this->m_escw_data="";
        $this->m_modx_data="";
        $this->m_encx_data="";
    }

    function  mf_gen_trace_no( $site_cd, $ip, $mode )
    {
      if ( $mode == "1" )
      {
        $trace_no = lfPP_CLI_DL__gen_trace_no( $site_cd, $ip );
      }
      else
      {
        $trace_no = "";
      }

      return  $trace_no;
    }

    function  mf_set_data_us( $name, $val )
    {
        $data = "";

        if ( $name != "" && $val != "" )
        {
            $data = $name . '=' . $val . chr( 31 );
        }

        return  $data;
    }

    function  mf_add_payx_data( $pay_type, $payx_data )
    {
        $this->m_payx_data .= ( $pay_type . '=' . $payx_data . chr( 30 ) );
    }

    function  mf_set_ordr_data( $name, $val )
    {
        if ( $val != "" )
        {
            $this->m_ordr_data .= ( $name . '=' . $val . chr( 31 ) );
        }
    }

    function  mf_set_rcvr_data( $name, $val )
    {
        if ( $val != "" )
        {
            $this->m_rcvr_data .= ( $name . '=' . $val . chr( 31 ) );
        }
    }

    function  mf_set_escw_data( $name, $val )
    {
        if ( $val != "" )
        {
            $this->m_escw_data .= ( $name . '=' . $val . chr( 29 ) );
        }
    }

    function  mf_set_modx_data( $name, $val )
    {
        if ( $val != "" )
        {
            $this->m_modx_data .= ( $name . '=' . $val . chr( 31 ) );
        }
    }

    /* -------------------------------------------------------------------- */
    /* -   FUNC  :  ���� ó�� �Լ�                                        - */
    /* -------------------------------------------------------------------- */
    function  mf_do_tx( $trace_no,  $home_dir, $site_cd,
                        $site_key,  $tx_cd,    $pub_key_str,
                        $pa_url,    $pa_port,  $user_agent,
                        $ordr_idxx, $cust_ip,
                        $log_level, $opt, $mode )
    {
        $payx_data = $this->m_payx_data;

        $ordr_data = $this->mf_get_data( "ordr_data", $this->m_ordr_data );
        $rcvr_data = $this->mf_get_data( "rcvr_data", $this->m_rcvr_data );
        $escw_data = $this->mf_get_data( "escw_data", $this->m_escw_data );
        $modx_data = $this->mf_get_data( "mod_data",  $this->m_modx_data );

        if ( $mode == "1" )
        {
          $res_data = lfPP_CLI_DL__do_tx_2( $trace_no, $home_dir, $site_cd,
                                            $site_key, $tx_cd,    $pub_key_str,
                                            $pa_url,   $pa_port,  $user_agent,
                                            $ordr_idxx,
                                            $payx_data, $ordr_data,
                                            $rcvr_data, $escw_data,
                                            $modx_data,
                                            $this->m_encx_data, $this->m_encx_info,
                                            $log_level, $opt );

        }
        else
        {
          $res_data = $this->mf_exec( $home_dir . "/bin/pp_cli",
                                      "-h",
                                      "home="      . $home_dir          . "," .
                                      "site_cd="   . $site_cd           . "," .
                                      "site_key="  . $site_key          . "," .
                                      "tx_cd="     . $tx_cd             . "," .
                                      "pa_url="    . $pa_url            . "," .
                                      "pa_port="   . $pa_port           . "," .
                                      "ordr_idxx=" . $ordr_idxx         . "," .
                                      "payx_data=" . $payx_data         . "," .
                                      "ordr_data=" . $ordr_data         . "," .
                                      "rcvr_data=" . $rcvr_data         . "," .
                                      "escw_data=" . $escw_data         . "," .
                                      "modx_data=" . $modx_data         . "," .
                                      "enc_data="  . $this->m_encx_data . "," .
                                      "enc_info="  . $this->m_encx_info . "," .
                                      "trace_no="  . $trace_no          . "," .
                                      "cust_ip="   . $cust_ip           . "," .
                                      "log_level=" . $log_level         . "," .
                                      "opt="       . $opt               . "" );

          if ( $res_data == "" )
          {
              $res_data = "res_cd=9502" . chr( 31 ) . "res_msg=���� ��� ȣ�� ����";
          }
        }

      parse_str( str_replace( chr( 31 ), "&", $res_data ), $this->m_res_data );

      $this->m_res_cd  = $this->m_res_data[ "res_cd"  ];
      $this->m_res_msg = $this->m_res_data[ "res_msg" ];
    }

    /* -------------------------------------------------------------------- */
    /* -   FUNC  :  ó�� ��� ���� �����ϴ� �Լ�                           - */
    /* -------------------------------------------------------------------- */
    function  mf_get_res_data( $name )
    {
        return  $this->m_res_data[ $name ];
    }

    function  mf_get_payx_data()
    {
        $my_data = "";

        if ( $this->m_payx_common != "" || $this->m_payx_card != "" )
        {
            $my_data  = "payx_data=";
        }

        if ( $this->m_payx_common != "" )
        {
            $my_data .= "common=" . $this->m_payx_common . chr( 30 );
        }

        if ( $this->m_payx_card != "" )
        {
            $my_data .= ( "card=" . $this->m_payx_card   . chr( 30 ) );
        }

        return  $my_data;
    }

    function  mf_get_data( $data_name, $data )
    {
        $my_data = "";

        if ( $data != "" )
        {
            $my_data = $data_name . "=" . $data;
        }
        else
        {
            $my_data = "";
        }

        return  $my_data;
    }

    function  mf_exec()
    {
      $arg = func_get_args();

      if ( is_array( $arg[0] ) )  $arg = $arg[0];

      $exec_cmd = array_shift( $arg );

      while ( list(,$i) = each($arg) )
      {
        $exec_cmd .= " " . escapeshellarg( $i );
      }

      $rt = exec( $exec_cmd );

      return  $rt;
    }
}
?>
