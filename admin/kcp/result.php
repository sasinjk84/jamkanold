<?
    /* ============================================================================== */
    /* =   PAGE : ��� ǥ�� PAGE                                                    = */
    /* = -------------------------------------------------------------------------- = */
    /* =   Copyright (c)  2008   KCP Inc.   All Rights Reserverd.                   = */
    /* ============================================================================== */
?>
<?
    /* ============================================================================== */
    /* =   ��û ���                                                                = */
    /* = -------------------------------------------------------------------------- = */
    $req_tx           = $_POST[ "req_tx"     ];                    // ��û ����
    $mod_type         = $_POST[ "mod_type"   ];                    // ���� ��û ����
    /* = -------------------------------------------------------------------------- = */
    $res_cd           = $_POST[ "res_cd"     ];                    // ����ڵ�
    $res_msg          = $_POST[ "res_msg"    ];                    // ����޽���
    $tno              = $_POST[ "tno"        ];                    // �ŷ���ȣ
    /* = -------------------------------------------------------------------------- = */
    $amount           = $_POST[ "amount"     ];                    // �� �ŷ��ݾ�
    $mod_mny          = $_POST[ "mod_mny"    ];                    // ��ҿ�û�� �ݾ�
    $rem_mny          = $_POST[ "rem_mny"    ];                    // ��ҿ�û�� �ܾ�
    /* ============================================================================== */

    $req_tx_name = "";

    if( $mod_type == "STSC" )
    {
        $req_tx_name = "���";
    }
    else if( $mod_type == "RN07" )
    {
        $req_tx_name = "�κ����";
    }
?>
    <html>
    <head>
    <link href="css/sample.css" rel="stylesheet" type="text/css">
    <script language="javascript">
        <!-- �ſ�ī�� ������ ���� ��ũ��Ʈ -->
        function receiptView(tno)
        {
            receiptWin = "http://admin.kcp.co.kr/Modules/Sale/Card/ADSA_CARD_BILL_Receipt.jsp?c_trade_no=" + tno;
            window.open(receiptWin , "" , "width=420, height=670");
        }
    </script>
    </head>
    <body>
    <center>
    <table border='0' cellpadding='0' cellspacing='1' width='500' align='center'>
        <tr>
            <td align="left" height="25"><img src="./img/KcpLogo.jpg" border="0" width="65" height="50"></td>
            <td align='right' class="txt_main">KCP Online Payment System [MOD HUB PHP Version]</td>
        </tr>
        <tr>
            <td bgcolor="CFCFCF" height='3' colspan='2'></td>
        </tr>
        <tr>
            <td colspan="2">
                <br>
                <table width="90%" align="center">
                    <tr>
                        <td bgcolor="CFCFCF" height='2'></td>
                    </tr>
                    <tr>
                        <td align="center">��� ������(<?=$req_tx_name?>)</td>
                    </tr>
                    <tr>
                        <td bgcolor="CFCFCF" height='2'></td>
                    </tr>
                </table>
<?
    if ( $req_tx == "mod" )                     // �ŷ� ���� : ���
    {
?>
                <table width="85%" align="center" border='0' cellpadding='0' cellspacing='1'>
                    <tr>
                        <td>����ڵ�</td>
                        <td><?=$res_cd?></td>
                    </tr>
                    <tr><td colspan="2"><IMG SRC="./img/dot_line.gif" width="100%"></td></tr>
                    <tr>
                        <td>��� �޼���</td>
                        <td><?=$res_msg?></td>
                    </tr>
<?
        if ( $res_cd == "0000" )                    // ���� ���
        {
            if ( $mod_type == "RN07" )              // �κ����
            {
?>
                    <tr><td colspan="2"><IMG SRC="./img/dot_line.gif" width="100%"></td></tr>
                    <tr>
                        <td>�� �ŷ��ݾ�</td>
                        <td><?=$amount?></td>
                    </tr>
                    <tr><td colspan="2"><IMG SRC="./img/dot_line.gif" width="100%"></td></tr>
                    <tr>
                        <td>��ҿ�û�� �ݾ�</td>
                        <td><?=$mod_mny?></td>
                    </tr>
                    <tr><td colspan="2"><IMG SRC="./img/dot_line.gif" width="100%"></td></tr>
                    <tr>
                        <td>��Ұ��� �ܾ�</td>
                        <td><?=$rem_mny?></td>
                    </tr>
<?
            }
?>
                    <tr><td colspan="2"><IMG SRC="./img/dot_line.gif" width="100%"></td></tr>
                    <tr>
                        <td>�ſ�ī�� ������</td>
                        <td><input type="button" name="receiptView" value="������ Ȯ��" class="box" onClick="javascript:receiptView('<?=$tno?>')"></td>
                    </tr>
<?
        }
    }
?>
                </table>
                <table width="90%" align="center">
                    <tr>
                        <td bgcolor="CFCFCF" height='2'></td>
                    </tr>
                    <tr>
                        <td height='2'>&nbsp;</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td bgcolor="CFCFCF" height='3' colspan='2'></td>
        </tr>
    </table>
	<div style="text-align:center"><button onClick="javascript:cwin()">Ȯ��</button>
    </center>
	<script language="javascript" type="text/javascript">
	function cwin(){
		opener.document.location.reload();
		window.close();
	}
	</script>
    </body>
    </html>
