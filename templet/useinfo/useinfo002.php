<table cellpadding="0" cellspacing="0" width="96%">
<tr>
	<td>&nbsp;- <?=$_data->shopname?>�� �湮�� �ּż� �����մϴ�.
	<br>&nbsp;- <?=$_data->shopname?> ���ͳ� ���θ��� ȸ������ �ǽ��ϰ� �ֽ��ϴ�.
	<br>&nbsp;- ó������ ���� ���� <a href="<?=$Dir.FrontDir?>member_agree.php"><b><font color="#333333">ȸ������</font></b></a>�� �Ͻ� �� �̿��Ͻñ� �ٶ��ϴ�.
	<? if ($_data->member_buygrant=="U") { ?>
	<br>&nbsp;- ȸ�������� ���Ͻô��� <b>��ȸ���� �ڰ�</b>���� ��ǰ�� �����Ͻ� �� �ֽ��ϴ�.
	<?}?></td>
</tr>
<tr>
	<td height="20"></td>
</tr>
<tr>
	<td>
	<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td><img src="<?=$Dir?>images/common/useinfo/<?=$_data->design_useinfo?>/design_useinfo_skin1_sticon.gif" border="0"></td>
			<td width="100%" background="<?=$Dir?>images/common/useinfo/<?=$_data->design_useinfo?>/design_useinfo_skin1_stbg.gif"><font color="#333333"><b>��ǰ�ֹ��ȳ�</b></font></td>
			<td><img src="<?=$Dir?>images/common/useinfo/<?=$_data->design_useinfo?>/design_useinfo_skin1_stend.gif" border="0"></td>
		</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td style="padding-left:20px;">��&nbsp;�� �ڳʸ� Ŭ���ϼż� ���ϴ�.
		<br>��&nbsp;"�ٷΰ���"�޴� �Ǵ� ��ǰ�����̳� ��ǰ���� Ŭ���ϼ���.
		<br>��&nbsp;"��ٱ��� ���"�� Ŭ���ϼ���.
		<br>��&nbsp;"��ٱ��Ͽ� �־����ϴ�" �޽����� ��µǸ� �ֹ���ǰ�� Ȯ�� �� "�ֹ���ư"�� Ŭ���ϼ���.
		<br>��&nbsp;�ֹ���ư�� ������, "�ֹ���" �ۼ��������� ��µǸ� �ֹ����� �ۼ� �� "�ֹ�"�� Ŭ���ϸ� �ֹ��� �Ϸ�˴ϴ�.</td>
	</tr>
	<tr>
		<td height="20"></td>
	</tr>
	<tr>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td><img src="<?=$Dir?>images/common/useinfo/<?=$_data->design_useinfo?>/design_useinfo_skin1_sticon.gif" border="0"></td>
			<td width="100%" background="<?=$Dir?>images/common/useinfo/<?=$_data->design_useinfo?>/design_useinfo_skin1_stbg.gif"><font color="#333333"><b>���θ� ����ó</b></font></td>
			<td><img src="<?=$Dir?>images/common/useinfo/<?=$_data->design_useinfo?>/design_useinfo_skin1_stend.gif" border="0"></td>
		</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td style="padding-left:20px;">��&nbsp;��ȭ : <?=$_data->info_tel?><br>
		�� �ּ� : <?=$_data->info_addr?></td>
	</tr>
	<tr>
		<td height="20"></td>
	</tr>
	<tr>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td><img src="<?=$Dir?>images/common/useinfo/<?=$_data->design_useinfo?>/design_useinfo_skin1_sticon.gif" border="0"></td>
			<td width="100%" background="<?=$Dir?>images/common/useinfo/<?=$_data->design_useinfo?>/design_useinfo_skin1_stbg.gif"><font color="#333333"><b>��۾ȳ�</b></font></td>
			<td><img src="<?=$Dir?>images/common/useinfo/<?=$_data->design_useinfo?>/design_useinfo_skin1_stend.gif" border="0"></td>
		</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td style="padding-left:20px;">��۹���� <font color="#AA3344"><b>
<?
		if ($_data->deli_type=="T") echo "�ù�";
		else if ($_data->deli_type=="P") echo "�������";
		else if ($_data->deli_type=="I") echo "�Ϲݵ��";
		else if ($_data->deli_type=="X") echo "�ù� + �������";
		else if ($_data->deli_type=="S") echo "�ù� + �Ϲݵ��";
		else if ($_data->deli_type=="M") echo "�������";
?>
		</b></font> �Դϴ�.<br>
		<br>�� �ֹ��Ͻ� ���κ��� <?=$_data->deli_setperiod?> ~ <?=$_data->deli_setperiod+3?>�� �ȿ� ���� �� �ֽ��ϴ�.
		<?  if ($_data->payment_type=="Y" || $_data->payment_type=="N") { $payment_mothod_C="��";?>
		<br>�� �¶��� �Աݽ� �Ա� Ȯ�� �� <?=$_data->deli_setperiod?> ~ <?=$_data->deli_setperiod+3?>��
		<?  } else { $payment_mothod_C="��"; }?>
		<?  if ($_data->payment_type=="Y" || $_data->payment_type=="C") { ?>
		<br><?=$payment_mothod_C?> �ſ�ī�� ������ �ֹ� �� <?=$_data->deli_setperiod?> ~ <?=$_data->deli_setperiod+3?>��
		<?  } ?>
		</td>
	</tr>
	<tr>
		<td height="20"></td>
	</tr>
	<tr>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td><img src="<?=$Dir?>images/common/useinfo/<?=$_data->design_useinfo?>/design_useinfo_skin1_sticon.gif" border="0"></td>
			<td width="100%" background="<?=$Dir?>images/common/useinfo/<?=$_data->design_useinfo?>/design_useinfo_skin1_stbg.gif"><font color="#333333"><b>��ȯ/��ǰ/ȯ�� �ȳ�</b></font></td>
			<td><img src="<?=$Dir?>images/common/useinfo/<?=$_data->design_useinfo?>/design_useinfo_skin1_stend.gif" border="0"></td>
		</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td style="padding-left:20px;">�� ���� ���ɿ� ���� ��ȯ �� ��ǰ�� ��쿡�� ��ۺ�� <font color="#AA3344"><b><?=($_data->return2_type=="1"?"�Ǹ���":"�Һ���")?></b></font>�δ��Դϴ�.
			<br>�� ��ǰ�� �̻� ���� ��ȯ �� ��ǰ�� ��쿡�� ��ۺ�� <font color="#AA3344"><b><?=($_data->return1_type=="1"?"�Ǹ���":"�Һ���")?></b></font>�δ��Դϴ�.
			<br>�� ���� : <?=$_data->info_tel?></td>
	</tr>
	<tr>
		<td height="20"></td>
	</tr>
	<tr>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td><img src="<?=$Dir?>images/common/useinfo/<?=$_data->design_useinfo?>/design_useinfo_skin1_sticon.gif" border="0"></td>
			<td width="100%" background="<?=$Dir?>images/common/useinfo/<?=$_data->design_useinfo?>/design_useinfo_skin1_stbg.gif"><font color="#333333"><b>�������� ��ȣ ��å </font><A HREF="javascript:privercy();"><font color="#333333">(�������� ��ȣ��å ����)</b></font></a></td>
			<td><img src="<?=$Dir?>images/common/useinfo/<?=$_data->design_useinfo?>/design_useinfo_skin1_stend.gif" border="0"></td>
		</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td style="padding-left:20px;">�� ��� : <font color="#333333"><b><?=$_data->privercyname?></b></font>
			<br>�� ��ȭ : <font color="#333333"><b><?=$_data->info_tel?></b></font>
			<br>�� ���� : <a href="mailto:<?=$_data->privercyemail?>"><?=$_data->privercyemail?></a></td>
	</tr>
	</table>
	</td>
</tr>
<tr>
	<td height="20"></td>
</tr>
</table>