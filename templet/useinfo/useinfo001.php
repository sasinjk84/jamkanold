<table cellpadding="0" cellspacing="0" width="100%">
<tr>
	<td style="padding:5px 0px;">
		<table cellpadding="0" cellspacing="6" width="100%" bgcolor="#F7F6F6" style="BORDER: #E5E3E3 1px solid;">
			<tr>
				<td bgcolor="#FFFFFF" style="padding:15px;BORDER: #E5E3E3 1px solid;">
					<table cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td></td>
							<td width="100%"> - <?=$_data->shopname?>�� �湮�� �ּż� �����մϴ�.
							<br> - <?=$_data->shopname?> ���ͳ� ���θ��� ȸ������ �ǽ��ϰ� �ֽ��ϴ�.
							<br> - ó������ ���� ���� <a href="<?=$Dir.FrontDir?>member_agree.php"><b><font color="#000000">ȸ������</font></b></a>�� �Ͻ� �� �̿��Ͻñ� �ٶ��ϴ�.
							<? if ($_data->member_buygrant=="U") { ?>
							<br> - ȸ�������� ���Ͻô��� <b>��ȸ���� �ڰ�</b>���� ��ǰ�� �����Ͻ� �� �ֽ��ϴ�.
							<?}?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</td>
</tr>
<tr>
	<td height="30"></td>
</tr>
<tr>
	<td>
	<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td><IMG SRC="<?=$Dir?>images/common/useinfo/<?=$_data->design_useinfo?>/design_useinfo_skin2_icon.gif" border="0" hspace="5"><font color="#000000"><b>��ǰ�ֹ��ȳ�</b></font></td>
		</tr>
		<tr>
			<td background="<?=$Dir?>images/common/useinfo/<?=$_data->design_useinfo?>/design_useinfo_skin2_line.gif">&nbsp;</td>
		</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td style="padding-left:20px;">
		��&nbsp;�� �ڳʸ� Ŭ���ϼż� ���ϴ�.<br>
		��&nbsp;"�ٷΰ���"�޴� �Ǵ� ��ǰ�����̳� ��ǰ���� Ŭ���ϼ���.<br>
		��&nbsp;"��ٱ��� ���"�� Ŭ���ϼ���.<br>
		��&nbsp;"��ٱ��Ͽ� �־����ϴ�" �޽����� ��µǸ� �ֹ���ǰ�� Ȯ�� �� "�ֹ���ư"�� Ŭ���ϼ���.<br>
		��&nbsp;"�ֹ���" �ۼ��������� ��µǸ� �ֹ����� �ۼ� �� "�ֹ�"�� Ŭ���ϸ� �ֹ��� �Ϸ�˴ϴ�.</td>
	</tr>
	<tr>
		<td height="30"></td>
	</tr>
	<tr>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td><IMG SRC="<?=$Dir?>images/common/useinfo/<?=$_data->design_useinfo?>/design_useinfo_skin2_icon.gif" border="0" hspace="5"><font color="#000000"><b>���θ� ����ó</b></font></td>
		</tr>
		<tr>
			<td background="<?=$Dir?>images/common/useinfo/<?=$_data->design_useinfo?>/design_useinfo_skin2_line.gif">&nbsp;</td>
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
		<td height="30"></td>
	</tr>
	<tr>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td><IMG SRC="<?=$Dir?>images/common/useinfo/<?=$_data->design_useinfo?>/design_useinfo_skin2_icon.gif" border="0" hspace="5"><font color="#000000"><b>��۾ȳ�</b></font></td>
		</tr>
		<tr>
			<td background="<?=$Dir?>images/common/useinfo/<?=$_data->design_useinfo?>/design_useinfo_skin2_line.gif">&nbsp;</td>
		</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td style="padding-left:20px;">��۹���� <font color="#FF4C00"><b>
<?
			if ($_data->deli_type=="T") echo "�ù�";
			else if ($_data->deli_type=="P") echo "�������";
			else if ($_data->deli_type=="I") echo "�Ϲݵ��";
			else if ($_data->deli_type=="X") echo "�ù� + �������";
			else if ($_data->deli_type=="S") echo "�ù� + �Ϲݵ��";
			else if ($_data->deli_type=="M") echo "�������";
?>
			</b></font> �Դϴ�.
			<br>�� �ֹ��Ͻ� ���κ��� <?=$_data->deli_setperiod?> ~ <?=$_data->deli_setperiod+3?>�� �ȿ� ���� �� �ֽ��ϴ�.
			<?  if ($_data->payment_type=="Y" || $_data->payment_type=="N") { $payment_mothod_C="��";?>
			<br>�� �¶��� �Աݽ� �Ա� Ȯ�� �� <?=$_data->deli_setperiod?> ~ <?=$_data->deli_setperiod+3?>��
			<?  } else { $payment_mothod_C="��"; }?>
			<?  if ($_data->payment_type=="Y" || $_data->payment_type=="C") { ?>
			<br><?=$payment_mothod_C?> �ſ�ī�� ������ �ֹ� �� <?=$_data->deli_setperiod?> ~ <?=$_data->deli_setperiod+3?>��
			<?  } ?></td>
	</tr>
	<tr>
		<td height="30"></td>
	</tr>
	<tr>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%">
		<col width="150"></col>
		<col></col>
		<tr>
			<td><IMG SRC="<?=$Dir?>images/common/useinfo/<?=$_data->design_useinfo?>/design_useinfo_skin2_icon.gif" border="0" hspace="5"><font color="#000000"><b>��ȯ/��ǰ/ȯ�� �ȳ�</b></font></td>
		</tr>
		<tr>
			<td background="<?=$Dir?>images/common/useinfo/<?=$_data->design_useinfo?>/design_useinfo_skin2_line.gif">&nbsp;</td>
		</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td style="padding-left:20px;">�� ���� ���ɿ� ���� ��ȯ �� ��ǰ�� ��쿡�� ��ۺ�� <font color="#FF4C00"><b><?=($_data->return2_type=="1"?"�Ǹ���":"�Һ���")?></b></font>�δ��Դϴ�.
		<br>�� ��ǰ�� �̻� ���� ��ȯ �� ��ǰ�� ��쿡�� ��ۺ�� <font color="#FF4C00"><b><?=($_data->return1_type=="1"?"�Ǹ���":"�Һ���")?></b></font>�δ��Դϴ�.
		<br>�� ���� : <?=$_data->info_tel?></td>
	</tr>
	<tr>
		<td height="30"></td>
	</tr>
	<tr>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td><IMG SRC="<?=$Dir?>images/common/useinfo/<?=$_data->design_useinfo?>/design_useinfo_skin2_icon.gif" border="0" hspace="5"><font color="#000000"><b>�������� ��ȣ ��å </b></font><A HREF="javascript:privercy();"><font color="#000000"><b>(�������� ��ȣ��å)</b></font></a></td>
		</tr>
		<tr>
			<td background="<?=$Dir?>images/common/useinfo/<?=$_data->design_useinfo?>/design_useinfo_skin2_line.gif">&nbsp;</td>
		</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td style="padding-left:20px;">�� ��� : <font color="#FF4C00"><b><?=$_data->privercyname?></b></font>
		<br>�� ��ȭ : <font color="#FF4C00"><b><?=$_data->info_tel?></b></font>
		<br>�� ���� : <a href="mailto:<?=$_data->privercyemail?>"><?=$_data->privercyemail?></a></td>
	</tr>
	</table>
	</td>
</tr>
<tr>
	<td height="50"></td>
</tr>
</table>