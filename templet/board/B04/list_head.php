<script language=javascript>
function schecked(){
	if (frm.search.value == ''){
		alert('�˻�� �Է����ּ���.');
		frm.search.focus();
		return false;
	} 
	else {
		frm.submit();
	}
}

function comment_view(idx) {
	if(document.all["comment_layer"+idx].style.display=="none") {
		document.all["comment_layer"+idx].style.display="";
		document.commform.num.value=idx;
		document.commform.target="list_comment"+idx;
		document.commform.submit();
	} else {
		document.all["comment_layer"+idx].style.display="none";
	}
}

</script>
<table cellpadding="0" cellspacing="0" width="<?=$setup[board_width]?>" style="table-layout:fixed">
<tr>
	<td style="padding-left:5px;padding-right:5px;">
	<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%" border="0">
		<tr>
			<td height="26">
			<table cellpadding="0" cellspacing="0">
			<form method=get name=frm action=<?=$PHP_SELF?> onSubmit="return schecked()">
			<input type="hidden" name="pagetype" value="list">
			<input type="hidden" name="board" value="<?=$board?>">
			<tr>
				<td style="font-size:11px;letter-spacing:-0.5pt;"><input type=radio name="s_check" value="c" <?=$check_c?> style="border:none">����+����<input type=radio name="s_check" value="n" <?=$check_n?> style="border:none">�ۼ���</td>
				<td style="padding-left:5px;padding-right:5px;"><input type=text name="search" value="<?=$search?>" size="12" class="input"></td>
				<td><INPUT type="image" src="<?=$imgdir?>/butt-go.gif" border="0" align="absMiddle" style="border:none"></td>
			</tr>
			</FORM>
			</table>
			</td>
			<td align="right">
			<table cellpadding="0" cellspacing="0" border="0">
			<tr align="right">
				<td style="font-size:11px;letter-spacing:-0.5pt;"><img src="<?=$imgdir?>/board_icon_8a.gif" border="0">��ü <font class="TD_TIT4_B"><B><?= $t_count ?></B></font>�� ��ȸ&nbsp;&nbsp;<img src="<?=$imgdir?>/board_icon_8a.gif" border="0">���� <B><?=$gotopage?></B>/<B><?=ceil($t_count/$setup[list_num])?></B> ������</td>
				<td style="padding-left:5px;"><?=$strAdminLogin?></td>
			</tr>
			</table>
			</td>
		</tr>
		</table>
		</td>
	</tr>
	</table>
	<table cellpadding="0" cellspacing="0" width="100%" border="0" style="table-layout:fixed">
	<col width="40"></col>
	<col></col>
	<col width="30"></col>
	<col width="80"></col>
	<?=$hide_hit_start?>
	<col width="40"></col>
	<?=$hide_hit_end?>
	<?=$hide_date_start?>
	<col width="110"></col>
	<?=$hide_date_end?>
	<TR>
		<TD height="2" colspan="<?=$table_colcnt?>" bgcolor="<?=$setup[title_color]?>"></TD>
	</TR>