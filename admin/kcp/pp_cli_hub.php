<?
    /* ============================================================================== */
    /* =   PAGE : ��� ��û PAGE                                                    = */
    /* = -------------------------------------------------------------------------- = */
    /* =   Copyright (c)  2008   KCP Inc.   All Rights Reserverd.                   = */
    /* ============================================================================== */
?>
<?
    /* ============================================================================== */
    /* = ���̺귯�� �� ����Ʈ ���� include                                          = */
    /* = -------------------------------------------------------------------------- = */
    require "./pp_cli_hub_lib.php";
    
	if($_POST['mod_type'] == 'RN07') $oinfo['paymethod'] = 'C';
	else $oinfo['paymethod'] = 'V';
	
	include "./config.php";
	
    /* ============================================================================== */
	
	
    /* ============================================================================== */
    /* =   01. ��� ��û ���� ����                                                  = */
    /* = -------------------------------------------------------------------------- = */
    $req_tx           = $_POST[ "req_tx"     ];                    // ��û ����
    $cust_ip          = getenv( "REMOTE_ADDR" );                   // ��û IP
    $mod_type         = $_POST[ "mod_type"   ];                    // ���� ��û ����
    /* = -------------------------------------------------------------------------- = */
    $res_cd           = "";                                        // ����ڵ�
    $res_msg          = "";                                        // ����޽���
    $tno              = "";                                        // �ŷ���ȣ
    /* = -------------------------------------------------------------------------- = */
    $tx_cd            = "";                                        // Ʈ����� �ڵ�
    /* = -------------------------------------------------------------------------- = */
    $amount           = "";                                        // �� �ŷ��ݾ�
    $mod_mny          = "";                                        // ��ҿ�û�ݾ�
    $rem_mny          = "";                                        // ��Ұ����ܾ�
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
    /* =   03-1. ��� ��û                                                          = */
    /* = -------------------------------------------------------------------------- = */
        if ( $req_tx == "mod" )
        {
            $tx_cd = "00200000";

            $c_PayPlus->mf_set_modx_data( "tno",      $_POST[ "tno" ]      ); // KCP ���ŷ� �ŷ���ȣ
            $c_PayPlus->mf_set_modx_data( "mod_type", $mod_type            ); // ���ŷ� ���� ��û ����
            $c_PayPlus->mf_set_modx_data( "mod_ip",   $cust_ip             ); // ���� ��û�� IP
            $c_PayPlus->mf_set_modx_data( "mod_desc", $_POST[ "mod_desc" ] ); // ���� ����

            if ( $mod_type == "RN07" || $mod_type == "STPA" ) // �κ������ ���
            {
                $c_PayPlus->mf_set_modx_data( "mod_mny", $_POST[ "mod_mny" ] ); // ��ҿ�û�ݾ�
                $c_PayPlus->mf_set_modx_data( "rem_mny", $_POST[ "rem_mny" ] ); // ��Ұ����ܾ�
            }
        }

    /* ============================================================================== */
    /* =   03-2. ����                                                               = */
    /* ------------------------------------------------------------------------------ */
        if ( strlen($tx_cd) > 0 )
        {
            $c_PayPlus->mf_do_tx( "",                $g_conf_home_dir, $g_conf_site_cd,
                                  $g_conf_site_key,  $tx_cd,           "",
                                  $g_conf_pa_url,    $g_conf_pa_port,  "payplus_cli_slib",
                                  "",                $cust_ip,         "3",
                                  "",                0 );
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
    /* =   04. ��� ��� ó��                                                       = */
    /* = -------------------------------------------------------------------------- = */
        if ( $req_tx == "mod" )
        {
            if ( $res_cd == "0000" )
            {
                $tno = $c_PayPlus->mf_get_res_data( "tno" );  // KCP �ŷ� ���� ��ȣ

    /* = -------------------------------------------------------------------------- = */
    /* =   04-1. �κ���� ��� ó��                                                 = */
    /* = -------------------------------------------------------------------------- = */
                if ( $mod_type == "RN07" ) // �κ������ ���
                {
                    $amount  = $c_PayPlus->mf_get_res_data( "amount"       ); // �� �ŷ��ݾ�
                    $mod_mny = $c_PayPlus->mf_get_res_data( "panc_mod_mny" ); // ��ҿ�û�� �ݾ�
                    $rem_mny = $c_PayPlus->mf_get_res_data( "panc_rem_mny" ); // ��ҿ�û�� �ܾ�
					
				//	_pr($_
					$errorstr = '';
					$opt1_name = 0;
					
					$sql = "select ifnull(max(opt1_name),0) from tblorderproduct where ordercode='".$_POST['ordercode']."' and tempkey='".$_POST['tempkey']."' and productcode='99999999995X'";
					$res = mysql_query($sql,get_db_conn());
					if($res){
						$opt1_name = mysql_result($res,0,0);						
					}
					
					$opt1_name += 1;
					
					$sql = "insert into tblorderproduct set ordercode='".$_POST['ordercode']."',tempkey='".$_POST['tempkey']."',productcode='99999999995X',productname='�����κ����' ,opt1_name='".$opt1_name."',opt2_name='',addcode='',quantity=1,price=".(-1*($mod_mny)).",date='".date('Ymd')."',selfcode='',etcapply_gift='',productbisiness='',order_prmsg='".$_POST[ "mod_desc" ]."<br>��ҿ�û�� �ܾ�:".$rem_mny."',assemble_info=''";					
					if(false === mysql_query($sql,get_db_conn())) $errorstr = mysql_error().'<br>['.$sql.']<br>';
					
					$sql = "update tblorderinfo set price = price - ".$mod_mny." where ordercode ='".$_POST['ordercode']."'";									
					if(false === mysql_query($sql,get_db_conn())) $errorstr .= mysql_error().'<br>['.$sql.']<br>';
					/*
					if(!empty($errorstr)){
						echo $errorstr;						
					}
					*/
                }
            } // End of [res_cd = "0000"]

    /* = -------------------------------------------------------------------------- = */
    /* =   04-2. ��� ���� ��� ó��                                                = */
    /* = -------------------------------------------------------------------------- = */
            else
            {
            }
        } // End of Process


    /* ============================================================================== */
    /* =   05. �� ���� �� ��������� ȣ��                                           = */
    /* ============================================================================== */
?>

    <html>
    <head>
    <script>
        function goResult()
        {
			<? if(empty($errorstr)){ ?>
            document.pay_info.submit();
			<? } ?>
        }
    </script>
    </head>
    <body onLoad="goResult()">
    <form name="pay_info" method="post" action="./result.php">
        <input type="hidden" name="req_tx"            value="<?=$req_tx?>">            <!-- ��û ���� -->
        <input type="hidden" name="mod_type"          value="<?=$mod_type?>">          <!-- ���� ��û ���� -->

        <input type="hidden" name="res_cd"            value="<?=$res_cd?>">            <!-- ��� �ڵ� -->
        <input type="hidden" name="res_msg"           value="<?=$res_msg?>">           <!-- ��� �޼��� -->
        <input type="hidden" name="tno"               value="<?=$tno?>">               <!-- KCP �ŷ���ȣ -->

        <input type="hidden" name="amount"            value="<?=$amount?>">            <!-- �� �ŷ��ݾ� -->
        <input type="hidden" name="mod_mny"           value="<?=$mod_mny?>">           <!-- ��ҿ�û�� �ݾ� -->
        <input type="hidden" name="rem_mny"           value="<?=$rem_mny?>">           <!-- ��ҿ�û�� �ܾ� -->
		<? if(!empty($errorstr)){ ?>
			<span style="color:red">���� ���� ���� DB �ݿ��� ������ �߻��߽��ϴ�. �Ʒ��� �ڵ带 jhj@objet.co.kr �� �߻��� �ֽñ� �ٶ��ϴ�.<br><? echo $errorstr; ?></span>
			KCP ���� ��� Ȯ���� ���� �Ʒ� "��� Ȯ��"�� Ŭ���� �ֽñ� �ٶ��ϴ�.		
		<input type="submit" value="��� Ȯ��">
		<? } ?>
    </form>
    </body>
    </html>
