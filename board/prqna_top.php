<table cellpadding="0" cellspacing="0" width="<?=$setup[board_width]?>">
<tr>
	<td style="padding-left:5px;padding-right:5px;">
	<table cellpadding="0" cellspacing="8" width="100%" bgcolor="#E8E8E8">
	<tr>
		<td background="<?=$Dir.BoardDir?>images/board_qna_tbg.gif" bgcolor="#FFFFFF" style="padding:8px;">
		<table cellpadding="0" cellspacing="0" width="100%" align="center" style="table-layout:fixed">
		<col width="70"></col>
		<col width="15"></col>
		<col></col>
		<col width="135"></col>
		<tr>
			<td>
<?
			echo "<A HREF=\"".$Dir.FrontDir."productdetail.php?productcode=".$_pdata->productcode."\" onmouseover=\"window.status='��ǰ����ȸ';return true;\" onmouseout=\"window.status='';return true;\">";
			if (strlen($_pdata->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$_pdata->tinyimage)==true) {
				echo "<img src=\"".$Dir.DataDir."shopimages/product/".urlencode($_pdata->tinyimage)."\" border=\"0\" width=\"70\">";
			} else {
				echo "<img src=\"".$Dir."images/no_img.gif\" border=\"0\" align=\"center\" width=\"70\">";
			}
			echo "</A></td>";
?>
			<td></td>
			<td>
			<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
			<col width="60">
			<col width="10">
			<tr>
				<td>��ǰ��</td>
				<td align="center">:</td>
				<td><A HREF="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$_pdata->productcode?>" onmouseover="window.status='��ǰ����ȸ';return true;" onmouseout="window.status='';return true;"><FONT class="prname"><?=viewproductname($_pdata->productname,$_pdata->etctype)?></FONT></A></td>
			</tr>
			<tr>
				<td>��ǰ����</td>
				<td align="center">:</td>
				<td><font class="prprice">
<?
			if($dicker=dickerview($_pdata->etctype,number_format($_pdata->sellprice)."��",1)) {
				echo $dicker;
			} else if(strlen($_data->proption_price)==0) {
				echo "<img src=\"".$Dir."images/common/won_icon.gif\" border=\"0\" align=\"absmiddle\">".number_format($_pdata->sellprice)."��";
				if (strlen($_pdata->option_price)!=0) echo "(�⺻��)";
			} else {
				if (strlen($_pdata->option_price)==0) echo number_format($_pdata->sellprice)."��";
				else echo ereg_replace("\[PRICE\]",number_format($_pdata->sellprice),$_data->proption_price);
			}
			if ($_pdata->quantity=="0") echo soldout();
?>
				</font></td>
			</tr>
			</table>
			</td>
			<td align="right"><A HREF="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$_pdata->productcode?>" onmouseover="window.status='��ǰ����ȸ';return true;" onmouseout="window.status='';return true;"><IMG SRC="<?=$Dir.BoardDir?>images/board_qna_btn03.gif" border="0"></td>
		</tr>
		</table>
		</td>
	</tr>
	</table>
	</td>
</tr>
<tr>
	<td height="10"></td>
</tr>
</table>