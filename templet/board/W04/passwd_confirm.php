<div align=center>
<table align="center" cellpadding="0" cellspacing="0" width="350">
<tr>
	<td height="40" colspan="3" style="padding:5px;" valign="bottom"><?=$error_meaage?></td>
</tr>
<tr>
	<td height="10" colspan="3"></td>
</tr>
<tr>
	<td><IMG SRC="<?=$imgdir?>/message_title.gif" border="0"></td>
	<td background="<?=$imgdir?>/message_title1bg.gif"><IMG SRC="<?=$imgdir?>/message_title1.gif" border="0"></td>
	<td><IMG SRC="<?=$imgdir?>/message_title2.gif" border="0"></td>
</tr>
<tr>
	<td background="<?=$imgdir?>/message_bg.gif"><IMG SRC="<?=$imgdir?>/message_bg.gif" border="0"></td>
	<td width="100%">
	<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="1" bgcolor="<?=$view_divider?>"></td>
	</tr>
	<tr>
		<td align="center" bgcolor="<?=$view_left_header_color?>" style="padding:5px;padding-top:10px;padding-bottom:10px;"><?=$info_msg?></td>
	</tr>
	<tr>
		<td height="1" bgcolor="<?=$view_divider?>"></td>
	</tr>
	<?
		if( $member[id]!="" AND $boardUserid == $member[id] ) {
	?>
	<tr>
		<td align="center" height="40">��� ���� �Ͻðڽ��ϱ�?</td>
	</tr>
	<?
		} else{
	?>
	<tr>
		<td align="center" height="40"><?=$admin_hide_start?>��й�ȣ : <INPUT type=password name="up_passwd" value="" size="20" maxLength="20" class="input" style="width:160px;"><?=$admin_hide_end?></td>
	</tr>
	<?
		}
	?>
	</table>
	</td>
	<td background="<?=$imgdir?>/message_bg1.gif"><IMG SRC="<?=$imgdir?>/message_bg1.gif" border="0"></td>
</tr>
<tr>
	<td><IMG SRC="<?=$imgdir?>/message_down.gif" border="0"></td>
	<td background="<?=$imgdir?>/message_down1.gif"><IMG SRC="<?=$imgdir?>/message_down1.gif" border="0"></td>
	<td><IMG SRC="<?=$imgdir?>/message_down2.gif" border="0"></td>
</tr>
<tr>
	<td colspan="3" align="center" style="padding-top:5px;"><input type=image src="<?=$imgdir?>/board_btn01.gif" border="0" style="border:none;width:52;height:24"><img src="<?=$imgdir?>/board_btn02.gif" border="0" hspace="5" style="CURSOR:hand" onClick="history.go(-1);"></td>
</tr>
</table>