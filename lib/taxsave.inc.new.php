<?php
include_once $Dir."lib/taxsave.class.php";
/* ============================================================================== */
/* =   PAGE : ���/���� ó�� PAGE                                               = */
/* = -------------------------------------------------------------------------- = */
/* =   ������ ������ �߻��ϴ� ��� �Ʒ��� �ּҷ� �����ϼż� Ȯ���Ͻñ� �ٶ��ϴ�.= */
/* =   ���� �ּ� : http://testpay.kcp.co.kr/pgsample/FAQ/search_error.jsp       = */
/* = -------------------------------------------------------------------------- = */
/* =   Copyright (c)  2007   KCP Inc.   All Rights Reserved.                    = */
/* ============================================================================== */

/* ============================================================================== */
/* = ���̺귯�� �� ����Ʈ ���� include                                          = */
/* = -------------------------------------------------------------------------- = */
/* ============================================================================== */

/* ============================================================================== */
/* =   01. KCP ���� ���� ���� ����                                              = */
/* = -------------------------------------------------------------------------- = */
$g_conf_home_dir  = $Dir."paygate/A/payplus/"; // �� ���θ� ��� ��ġ ���� ��� bin������
$g_conf_log_level = "3";

$g_conf_pa_url    = "paygw.kcp.co.kr"; // �� �׽�Ʈ: testpaygw.kcp.co.kr, ����: paygw.kcp.co.kr
$g_conf_pa_port   = "8080";                // �� �׽�Ʈ: 8090,                ����: 8080
$g_conf_tx_mode   = 0;

$g_conf_user_type = "PGNW";  // ���� �Ұ�
$g_conf_site_id   = $tax_scd; // ���� �ݿ��� KCP�� �߱޵� site_cd ��� ex) T0000
/* ============================================================================== */



$sql = "SELECT * FROM tbltaxsavelist WHERE ordercode='".$ordercode."' ";
$result=mysql_query($sql,get_db_conn());
$row=mysql_fetch_object($result);
/* ============================================================================== */
/* =   01. ��û ���� ����                                                       = */
/* = -------------------------------------------------------------------------- = */
$req_tx     = "pay";//$_POST[ "req_tx"     ];                             // ��û ����
$trad_time  = $row->tsdtime;//$_POST[ "trad_time"  ];                             // ���ŷ� �ð�
/* = -------------------------------------------------------------------------- = */
$ordr_idxx  = $row->ordercode;//$_POST[ "ordr_idxx"  ];                             // �ֹ� ��ȣ
$buyr_name  = $row->name;//$_POST[ "buyr_name"  ];                             // �ֹ��� �̸�
$buyr_tel1  = str_replace("-","",$row->tel);//$_POST[ "buyr_tel1"  ];                             // �ֹ��� ��ȭ��ȣ
$buyr_mail  = $row->email;//$_POST[ "buyr_mail"  ];                             // �ֹ��� E-Mail
$good_name  = str_replace(array(",","-"," "),"",$row->productname);//$_POST[ "good_name"  ];                             // ��ǰ ����
$comment    = "";//$_POST[ "comment"    ];                             // ���
/* = -------------------------------------------------------------------------- = */

$corp_type     = "0";//$_POST[ "corp_type"      ];                      // ����� ����
$corp_tax_type = "TG01";//$_POST[ "corp_tax_type"  ];                      // ����/�鼼 ����
$corp_tax_no   = $tax_cnum;//$_POST[ "corp_tax_no"    ];                      // ���� ����� ��ȣ
$corp_nm       = $tax_cname;//$_POST[ "corp_nm"        ];                      // ��ȣ
$corp_owner_nm = $tax_cowner;//$_POST[ "corp_owner_nm"  ];                      // ��ǥ�ڸ�
$corp_addr     = $tax_caddr;//$_POST[ "corp_addr"      ];                      // ����� �ּ�
$corp_telno    = str_replace("-","",$tax_ctel);//$_POST[ "corp_telno"     ];                      // ����� ��ǥ ����ó
/* = -------------------------------------------------------------------------- = */
$tr_code    = $row->tr_code;//$_POST[ "tr_code"    ];                             // ����뵵
$id_info    = $row->id_info;//$_POST[ "id_info"    ];                             // �ź�Ȯ�� ID
$amt_tot    = $row->amt1;//$_POST[ "amt_tot"    ];                             // �ŷ��ݾ� �� ��
$amt_sup    = $row->amt2;//$_POST[ "amt_sup"    ];                             // ���ް���
$amt_svc    = $row->amt3;//$_POST[ "amt_svc"    ];                             // �����
$amt_tax    = $row->amt4;//$_POST[ "amt_tax"    ];                             // �ΰ���ġ��
/* = -------------------------------------------------------------------------- = */
$mod_type   = $_POST[ "mod_type"   ];                             // ���� Ÿ��
$mod_value  = $_POST[ "mod_value"  ];                             // ���� ��û �ŷ���ȣ
$mod_gubn   = $_POST[ "mod_gubn"   ];                             // ���� ��û �ŷ���ȣ ����
$mod_mny    = $_POST[ "mod_mny"    ];                             // ���� ��û �ݾ�
$rem_mny    = $_POST[ "rem_mny"    ];                             // ����ó�� ���� �ݾ�
/* = -------------------------------------------------------------------------- = */
$cust_ip    = getenv( "REMOTE_ADDR" );                            // ��û IP
/* ============================================================================== */


/* ============================================================================== */
/* =   02. �ν��Ͻ� ���� �� �ʱ�ȭ                                              = */
/* = -------------------------------------------------------------------------- = */
$c_PayPlus  = new C_PAYPLUS_CLI;
$c_PayPlus->mf_clear();
/* ============================================================================== */


/* ============================================================================== */
/* =   03. ó�� ��û ���� ����, ����                                            = */
/* = -------------------------------------------------------------------------- = */

/* = -------------------------------------------------------------------------- = */
/* =   03-1. ���� ��û                                                          = */
/* = -------------------------------------------------------------------------- = */
// ��ü ȯ�� ����
if ( $req_tx == "pay" )
{
	$tx_cd = "07010000"; // ���ݿ����� ��� ��û

	// ���ݿ����� ����
	$rcpt_data_set .= $c_PayPlus->mf_set_data_us( "user_type",      $g_conf_user_type );
	$rcpt_data_set .= $c_PayPlus->mf_set_data_us( "trad_time",      $trad_time        );
	$rcpt_data_set .= $c_PayPlus->mf_set_data_us( "tr_code",        $tr_code          );
	$rcpt_data_set .= $c_PayPlus->mf_set_data_us( "id_info",        $id_info          );
	$rcpt_data_set .= $c_PayPlus->mf_set_data_us( "amt_tot",        $amt_tot          );
	$rcpt_data_set .= $c_PayPlus->mf_set_data_us( "amt_sup",        $amt_sup          );
	$rcpt_data_set .= $c_PayPlus->mf_set_data_us( "amt_svc",        $amt_svc          );
	$rcpt_data_set .= $c_PayPlus->mf_set_data_us( "amt_tax",        $amt_tax          );
	$rcpt_data_set .= $c_PayPlus->mf_set_data_us( "pay_type",       "PAXX"            ); // �� ���� ���� ����(PABK - ������ü, PAVC - �������, PAXX - ��Ÿ)
	//$rcpt_data_set .= $c_PayPlus->mf_set_data_us( "pay_trade_no",   $pay_trade_no ); // ���� �ŷ���ȣ(PABK, PAVC�� ��� �ʼ�)
	//$rcpt_data_set .= $c_PayPlus->mf_set_data_us( "pay_tx_id",      $pay_tx_id    ); // ������� �Ա��뺸 TX_ID(PAVC�� ��� �ʼ�)
	// �ֹ� ����
	$c_PayPlus->mf_set_ordr_data( "ordr_idxx",  $ordr_idxx );
	$c_PayPlus->mf_set_ordr_data( "good_name",  $good_name );
	$c_PayPlus->mf_set_ordr_data( "buyr_name",  $buyr_name );
	$c_PayPlus->mf_set_ordr_data( "buyr_tel1",  $buyr_tel1 );
	$c_PayPlus->mf_set_ordr_data( "buyr_mail",  $buyr_mail );
	$c_PayPlus->mf_set_ordr_data( "comment",    $comment   );

	// ������ ����
	$corp_data_set .= $c_PayPlus->mf_set_data_us( "corp_type",       $corp_type     );
	//$corp_data_set .= $c_PayPlus->mf_set_data_us( "corp_type",       0     );

	if ( $corp_type == "1" ) // �������� ��� �ǸŻ��� DATA ���� ����
	{
		$corp_data_set .= $c_PayPlus->mf_set_data_us( "corp_tax_type",   $corp_tax_type );
		$corp_data_set .= $c_PayPlus->mf_set_data_us( "corp_tax_no",     $corp_tax_no   );
		$corp_data_set .= $c_PayPlus->mf_set_data_us( "corp_sell_tax_no",$corp_tax_no   );
		$corp_data_set .= $c_PayPlus->mf_set_data_us( "corp_nm",         $corp_nm       );
		$corp_data_set .= $c_PayPlus->mf_set_data_us( "corp_owner_nm",   $corp_owner_nm );
		$corp_data_set .= $c_PayPlus->mf_set_data_us( "corp_addr",       $corp_addr     );
		$corp_data_set .= $c_PayPlus->mf_set_data_us( "corp_telno",      $corp_telno    );
	}

	$c_PayPlus->mf_set_ordr_data( "rcpt_data", $rcpt_data_set );
	$c_PayPlus->mf_set_ordr_data( "corp_data", $corp_data_set );
}

/* = -------------------------------------------------------------------------- = */
/* =   03-2. ��� ��û                                                          = */
/* = -------------------------------------------------------------------------- = */
else if ( $req_tx == "mod" )
{
	if ( $mod_type == "STSQ" )
	{
		$tx_cd = "07030000"; // ��ȸ ��û
	}
	else
	{
		$tx_cd = "07020000"; // ��� ��û
	}

	$c_PayPlus->mf_set_modx_data( "mod_type",   $mod_type   );      // ���ŷ� ���� ��û ����
	$c_PayPlus->mf_set_modx_data( "mod_value",  $mod_value  );
	$c_PayPlus->mf_set_modx_data( "mod_gubn",   $mod_gubn   );
	$c_PayPlus->mf_set_modx_data( "trad_time",  $trad_time  );

	if ( $mod_type == "STPC" ) // �κ����
	{
		$c_PayPlus->mf_set_modx_data( "mod_mny",  $mod_mny  );
		$c_PayPlus->mf_set_modx_data( "rem_mny",  $rem_mny  );
	}
}
/* ============================================================================== */

/* ============================================================================== */
/* =   03-3. ����                                                               = */
/* ------------------------------------------------------------------------------ */
if ( strlen($tx_cd) > 0 )
{
	$c_PayPlus->mf_do_tx( "",                $g_conf_home_dir, $g_conf_site_id,
						  "",                $tx_cd,           "",
						  $g_conf_pa_url,    $g_conf_pa_port,  "payplus_cli_slib",
						  $ordr_idxx,        $cust_ip,         $g_conf_log_level,
						  "",                $g_conf_tx_mode );
}
else
{
	$c_PayPlus->m_res_cd  = "9562";
	$c_PayPlus->m_res_msg = "���� ����";
}
$res_cd  = $c_PayPlus->m_res_cd;                      // ��� �ڵ�
$res_msg = $c_PayPlus->m_res_msg;                     // ��� �޽���
/* ============================================================================== */


/* ============================================================================== */
/* =   04. ���� ��� ó��                                                       = */
/* = -------------------------------------------------------------------------- = */
if ( $req_tx == "pay" )
{
	if ( $res_cd == "0000" )
	{
		$cash_no    = $c_PayPlus->mf_get_res_data( "cash_no"    );       // ���ݿ����� �ŷ���ȣ
		$receipt_no = $c_PayPlus->mf_get_res_data( "receipt_no" );       // ���ݿ����� ���ι�ȣ
		$app_time   = $c_PayPlus->mf_get_res_data( "app_time"   );       // ���νð�(YYYYMMDDhhmmss)
		$reg_stat   = $c_PayPlus->mf_get_res_data( "reg_stat"   );       // ��� ���� �ڵ�
		$reg_desc   = $c_PayPlus->mf_get_res_data( "reg_desc"   );       // ��� ���� ����

/* = -------------------------------------------------------------------------- = */
/* =   04-1. ���� ����� ��ü ��ü������ DB ó�� �۾��Ͻô� �κ��Դϴ�.         = */
/* = -------------------------------------------------------------------------- = */
/* =         ���� ����� DB �۾� �ϴ� �������� ���������� ���ε� �ǿ� ����      = */
/* =         DB �۾��� �����Ͽ� DB update �� �Ϸ���� ���� ���, �ڵ�����       = */
/* =         ���� ��� ��û�� �ϴ� ���μ����� �����Ǿ� �ֽ��ϴ�.                = */
/* =         DB �۾��� ���� �� ���, bSucc ��� ����(String)�� ���� "false"     = */
/* =         �� ������ �ֽñ� �ٶ��ϴ�. (DB �۾� ������ ��쿡�� "false" �̿��� = */
/* =         ���� �����Ͻø� �˴ϴ�.)                                           = */
/* = -------------------------------------------------------------------------- = */
		$sql = "UPDATE tbltaxsavelist SET ";
		$sql.= "tsdtime		= '".$trad_time."', ";
		$sql.= "type		= 'Y', ";
		$sql.= "authno		= '".$cash_no."', ";
		$sql.= "mtrsno		= '".$receipt_no."', ";
		$sql.= "oktime		= '".$app_time."', ";
		$sql.= "error_msg	= '".$reg_desc."' ";
		$sql.= "WHERE ordercode='".$ordr_idxx."' ";
		mysql_query($sql,get_db_conn());
		$msg="���ݿ����� �߱��� ���������� ó���Ǿ����ϴ�.";

		$bSucc = "";             // DB �۾� ������ ��� "false" �� ����
		$msg="$res_cd $res_msg .";
/* = -------------------------------------------------------------------------- = */
/* =   04-2. DB �۾� ������ ��� �ڵ� ���� ���                                 = */
/* = -------------------------------------------------------------------------- = */
		if ( $bSucc == "false" )
		{
			$c_PayPlus->mf_clear();

			$tx_cd = "07020000"; // ��� ��û

			$c_PayPlus->mf_set_modx_data( "mod_type",  "STSC"     );                    // ���ŷ� ���� ��û ����
			$c_PayPlus->mf_set_modx_data( "mod_value", $cash_no   );
			$c_PayPlus->mf_set_modx_data( "mod_gubn",  "MG01"     );
			$c_PayPlus->mf_set_modx_data( "trad_time", $trad_time );

			$c_PayPlus->mf_do_tx( "",                $g_conf_home_dir, $g_conf_site_id,
								  "",                $tx_cd,           "",
								  $g_conf_pa_url,    $g_conf_pa_port,  "payplus_cli_slib",
								  $ordr_idxx,        $cust_ip,         $g_conf_log_level,
								  "",                $g_conf_tx_mode );

			$res_cd  = $c_PayPlus->m_res_cd;
			$res_msg = $c_PayPlus->m_res_msg;
		}

	}    // End of [res_cd = "0000"]

/* = -------------------------------------------------------------------------- = */
/* =   04-3. ��� ���и� ��ü ��ü������ DB ó�� �۾��Ͻô� �κ��Դϴ�.         = */
/* = -------------------------------------------------------------------------- = */
	else
	{
		$msg="$res_cd $res_msg .";

	}
}
/* ============================================================================== */


/* ============================================================================== */
/* =   05. ���� ��� ó��                                                       = */
/* = -------------------------------------------------------------------------- = */
else if ( $req_tx == "mod" )
{
	if ( $res_cd == "0000" )
	{
		$cash_no    = $c_PayPlus->mf_get_res_data( "cash_no"    );       // ���ݿ����� �ŷ���ȣ
		$receipt_no = $c_PayPlus->mf_get_res_data( "receipt_no" );       // ���ݿ����� ���ι�ȣ
		$app_time   = $c_PayPlus->mf_get_res_data( "app_time"   );       // ���νð�(YYYYMMDDhhmmss)
		$reg_stat   = $c_PayPlus->mf_get_res_data( "reg_stat"   );       // ��� ���� �ڵ�
		$reg_desc   = $c_PayPlus->mf_get_res_data( "reg_desc"   );       // ��� ���� ����
	}

/* = -------------------------------------------------------------------------- = */
/* =   05-1. ���� ���и� ��ü ��ü������ DB ó�� �۾��Ͻô� �κ��Դϴ�.         = */
/* = -------------------------------------------------------------------------- = */
	else
	{
	}
}
/* ============================================================================== */


/* ============================================================================== */
/* =   06. �ν��Ͻ� CleanUp                                                     = */
/* = -------------------------------------------------------------------------- = */
$c_PayPlus->mf_clear();
/* ============================================================================== */

/* ============================================================================== */
/* =   07. �� ���� �� ��������� ȣ��                                           = */
/* ============================================================================== */
?>