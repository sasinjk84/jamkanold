<?php
	include 'cfg.php';//�⺻ include����   
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="EUC-KR" xml:lang="EUC-KR" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<title>���� ���ݰ�꼭</title>
<style>
table {font-size:12px;}
input,textarea {font-size:12px; font-family: "gulim";border:solid 1px #D7D7D7;}
input.right {text-align:right;}

.hidden_line {border:dotted 1px #D7D7D7;}
.show_line {border:solid 1px #D7D7D7;}
.background {background-color:#e7e7e7;text-align:right;}
</style>
<script>
    function chkLogin(f)
    {
        if (!f.domain.value) {
            alert('���̿��� ���� �׷��ּҸ� �Է��� �ּ���');
            f.domain.focus();
            return false;
        }

        if (!f.license_id.value) {
            alert('����� ���̵� �Է��� �ּ���');
            f.license_id.focus();
            return false;
        }

        if (!f.license_no.value) {
            alert('���ݰ�꼭 ���������� �Է��� �ּ���');
            f.license_no.focus();
            return false;
        }

        return true;
    }

    function init()
    {
        document.getElementById('domain').focus();
    }

</script>
</head>
<body onload="init();">
<form method="post" name="frmLogin" action="http://billapi.hiworks.co.kr/auto_login.php" onsubmit="return chkLogin(this)">
<table width="400" cellpadding="0" cellspacing="1" bgcolor="#c8c8c8">
<tr bgcolor="#ffffff" height="30">
<td width="40%" align="center">���̿��� ���� �׷��ּ�</td>
<td>&nbsp;<input type="text" name="domain" id="domain" value="<?=$cfg['domain']?>"></td>
</tr>
<tr bgcolor="#ffffff" height="30">
<td align="center">����� ���̵�</td>
<td>&nbsp;<input type="text" name="license_id" value="<?=$cfg['license_id']?>"></td>
</tr>
<tr bgcolor="#ffffff" height="30">
<td align="center">���ݰ�꼭 ��������</td>
<td>&nbsp;<input type="text" name="license_no" value="<?=$cfg['license_no']?>"></td>
</tr>
<tr bgcolor="#ffffff" height="30">
<td align="center">�α��� ���</td>
<td>&nbsp;<input type="radio" name="pType" id="pType_BILL" value="BILL" checked="checked"><label for="pType_BILL">���ݰ�꼭</label>
<input type="radio" name="pType" id="pType_SMS" value="SMS"><label for="pType_SMS">��������</label>
</td>
</tr>

<tr bgcolor="#ffffff" height="30">
<td colspan="2" align="center"><input type="submit" value="�α���" style="width:100px"></td>
</tr>
</table>
</form>
</body>
</html>