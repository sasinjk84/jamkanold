
<SCRIPT LANGUAGE="JavaScript">
<!--
function chk_writeForm(form) {
	if (typeof(form.tmp_is_secret) == "object") {
		form.up_is_secret.value = form.tmp_is_secret.options[form.tmp_is_secret.selectedIndex].value;
	}

	if (!form.up_name.value) {
		alert('�ۼ����� �Է��Ͻʽÿ�.');
		form.up_name.focus();
		return false;
	}

	<? if (strlen($member[id])==0) { ?>
	if (!form.up_passwd.value) {
		alert('��й�ȣ�� �Է��Ͻʽÿ�.');
		form.up_passwd.focus();
		return false;
	}
	<? } ?>

	if (!form.up_subject.value) {
		alert('������ �Է��Ͻʽÿ�.');
		form.up_subject.focus();
		return false;
	}

	if (!form.up_memo.value) {
		alert('������ �Է��Ͻʽÿ�.');
		form.up_memo.focus();
		return false;
	}

	form.mode.value = "up_result";
	reWriteName(form);
	form.submit();
}

function putSubject(subject) {
	document.writeForm.up_subject.value = subject;
}

function FileUp() {
	fileupwin = window.open("","fileupwin","width=50,height=50,toolbars=no,menubar=no,scrollbars=no,status=no");
	while (!fileupwin);
	document.fileform.action = "<?=$Dir.BoardDir?>ProcessBoardFileUpload.php"
	document.fileform.target = "fileupwin";
	document.fileform.submit();
	fileupwin.focus();
}
// -->
</SCRIPT>

<SCRIPT LANGUAGE="JavaScript" src="chk_form.js.php"></SCRIPT>
<? if($setup['use_html'] !="N"){ ?>
<script type="text/javascript" src="/gmeditor/js/jquery.js"></script>
<script type="text/javascript" src="/gmeditor/js/jquery.event.drag-2.0.min.js"></script>
<script type="text/javascript" src="/gmeditor/js/jquery.resizable.js"></script>
<script type="text/javascript" src="/gmeditor/js/ajax_upload.3.6.js"></script>
<script type="text/javascript" src="/gmeditor/js/ej.h2xhtml.js"></script>
<script type="text/javascript" src="/gmeditor/editor.js"></script>
<style type="text/css">
  @import url("/gmeditor/common.css");
</style>
<script language="javascript" type="text/javascript">
$(document).ready(function() {
	ejEditor();
});
</script>
<? } ?>
<TABLE cellSpacing="0" cellPadding="0" width="<?=$setup[board_width]?>" border="0">
<tr>
	<td style="padding-left:5px;padding-right:5px;">
	<table cellSpacing="0" cellPadding="0" width="100%" bgcolor="<?=$view_left_header_color?>">
	<form name=fileform method=post>
	<input type=hidden name=board value="<?=$board?>">
	<input type=hidden name=max_filesize value="<?=$setup[max_filesize]?>">
	<input type=hidden name=btype value="<?=$setup[btype]?>">
	</form>

	<form name=writeForm method='post' action='<?= $_SERVER[PHP_SELF]?>' enctype='multipart/form-data'>
	<input type=hidden name=mode value=''>
	<? if($setup['use_html'] !="N"){ ?>
	<input type="hidden" name="up_html" value="1" />
	<? } ?>
	<input type=hidden name=pagetype value='write'>
	<input type=hidden name=exec value='<?=$_REQUEST["exec"]?>'>
	<input type=hidden name=num value=<?=$num?>>
	<input type=hidden name=board value=<?=$board?>>
	<input type=hidden name=s_check value=<?=$s_check?>>
	<input type=hidden name=search value=<?=$search?>>
	<input type=hidden name=block value=<?=$block?>>
	<input type=hidden name=gotopage value=<?=$gotopage?>>
	<input type=hidden name=pos value="<?=$thisBoard[pos]?>">
	<input type=hidden name=up_is_secret value="<?=$thisBoard[is_secret]?>">
	<col width="15%" style="padding-top:5px;padding-bottom:5px;background-color:#F8F8F8;letter-spacing:-0.5pt;"></col>
	<col width="35%" style="padding-left:3pt;padding-right:3pt;background-color:#FFFFFF;"></col>
	<col width="15%" style="background-color:#F8F8F8;letter-spacing:-0.5pt;"></col>
	<col width="35%" style="padding-left:3pt;padding-right:3pt;background-color:#FFFFFF;"></col>
	<TR>
		<TD height="2" colspan="4" bgcolor="<?=$setup[title_color]?>"></TD>
	</TR>
	<?= $hide_secret_start ?>
	<tr>
		<TD align="center"><font color="#333333">��ݱ��</font></TD>
		<TD colspan="3" style="border-left:<?=$list_divider?> 1px solid;"><?= writeSecret($exec,$thisBoard[is_secret],$thisBoard[pos]) ?></TD>
	</tr>
	<TR>
		<TD height="1" colspan="4" bgcolor="<?=$list_divider?>"></TD>
	</TR>
	<?= $hide_secret_end ?>

	<? if (strlen($member[name])>0) { ?>
	<TR>
		<Th align="center"><font color="#333333">�ۼ���</font></Th>
		<TD style="border-left:<?=$list_divider?> 1px solid;"><input type=text name="up_name" value="<?=$thisBoard[name]?>" size="13" maxlength="20" style="BACKGROUND-COLOR:#F7F7F7;width:160px" class="input" colspan="3"></TD>
	</TR>
	<? } else{ ?>
	<TR>
		<Th align="center"><font color="#333333">�ۼ���</font></Th>
		<TD style="border-left:<?=$list_divider?> 1px solid;"><input type=text name="up_name" value="<?=$thisBoard[name]?>" size="13" maxlength="20" style="BACKGROUND-COLOR:#F7F7F7;width:160px" class="input"></TD>
		<Th align="center" style="border-left:<?=$list_divider?> 1px solid;"><font color="#333333">��й�ȣ</font></Th>
		<TD style="border-left:<?=$list_divider?> 1px solid;"><input type=password name="up_passwd" value="<?=$thisBoard[passwd]?>" size="13" maxlength="20" style="BACKGROUND-COLOR:#F7F7F7;width:160px" class="input"></TD>
	</TR>
	<? } ?>


	<TR>
		<TD height="1" colspan="4" bgcolor="<?=$list_divider?>"></TD>
	</TR>
	<TR>
		<TD align="center"><font color="#333333">�̸���</font></TD>
		<TD colspan="3" style="border-left:<?=$list_divider?> 1px solid;"><input type=text name="up_email" value="<?=$thisBoard[email]?>" size="49" maxlength="60" style="BACKGROUND-COLOR:#F7F7F7;width:240px" class="input"> <font color="#FF4C00" style="font-size:11px;letter-spacing:-0.5pt;">* �亯�� ������ E-mail�� �Է��ϼ���.</font></TD>
	</TR>
	<TR>
		<TD height="1" colspan="4" bgcolor="<?=$list_divider?>"></TD>
	</TR>
	<?= $hide_replysms_start ?>
	<TR>
		<TD align="center"><font color="#333333">�޴���</font></TD>
		<TD colspan="3" style="border-left:<?=$list_divider?> 1px solid;"><input type=text name="up_cel1" value="<?=$thisBoard[cel][0]?>" size="5" maxlength="4" style="BACKGROUND-COLOR:#F7F7F7;" class="input">-<input type=text name="up_cel2" value="<?=$thisBoard[cel][1]?>" size="5" maxlength="4" style="BACKGROUND-COLOR:#F7F7F7;" class="input">-<input type=text name="up_cel3" value="<?=$thisBoard[cel][2]?>" size="5" maxlength="4" style="BACKGROUND-COLOR:#F7F7F7;" class="input"> <font color="#FF4C00" style="font-size:11px;letter-spacing:-0.5pt;">* �亯��Ͻ� �˸� ������ �޴�����ȣ�� �Է��ϼ���.</font></TD>
	</TR>
	<TR>
		<TD height="1" colspan="4" bgcolor="<?=$list_divider?>"></TD>
	</TR>
	<?= $hide_replysms_end ?>



	<?=$subCategoryList_start?>
	<tr>
		<td align="center">���Ӹ�</td>
		<td colspan="3" style="border-left:<?=$list_divider?> 1px solid;"><?=$subCategoryList?></td>
	</tr>
	<TR>
		<TD height="1" colspan="4" bgcolor="<?=$list_divider?>"></TD>
	</TR>
	<?=$subCategoryList_end?>



	<TR>
		<TD align="center"><font color="#333333">������</font></TD>
		<TD colspan="3" style="border-left:<?=$list_divider?> 1px solid;"><input type=text name="up_subject" value="<?=$thisBoard[title]?>" size="70" maxlength="200" class="input" style="BACKGROUND-COLOR:#F7F7F7;width:100%"></TD>
	</TR>
	<TR>
		<TD height="1" colspan="4" bgcolor="<?=$list_divider?>"></TD>
	</TR>



	<?
		if( $setup[linkboard] ) {
	?>
	<TR>
		<Th align="center"><font color="#333333">URL</font></Th>
		<TD colspan="3" style="border-left:<?=$list_divider?> 1px solid;"><input type=text name="up_url" value="<?=$thisBoard[url]?>" size="70" maxlength="200" class="input" style="BACKGROUND-COLOR:#F7F7F7; width:100%"></TD>
	</TR>
	<TR>
		<TD height="1" colspan="4" bgcolor="<?=$list_divider?>"></TD>
	</TR>
	<?
		}
	?>



	<TR>
		<TD align="center"><font color="#333333">�۳���</font></TD>
		<TD colspan="3" style="border-left:<?=$list_divider?> 1px solid;">
		<table cellpadding="0" cellspacing="0" width="100%">
		<? /*
		<?=$hide_html_start?>
		<tr>
			<td style="padding-top:2px;padding-bottom:2px;"><B>HTML����</B> <input type=checkbox name="up_html" value="1" <?=$thisBoard[use_html]?> style="border:none;"></td>
		</tr>
		<?=$hide_html_end?> */ ?>
		<tr>
			<td style="padding-top:2px;padding-bottom:2px;"><textarea name="up_memo" lang="ej-editor3"  style="width:590;height:280px;border:1 solid <?=$list_divider?>;PADDING:5px;line-height:17px;font-size:9pt;color:333333;" wrap="<?=$setup[wrap]?>"><?=$thisBoard[content]?></textarea></td>
		</tr>
		</table>
		</TD>
	</TR>
	<script>putSubject("<?=addslashes($thisBoard[title])?>");</script>
	<TR>
		<TD height="1" colspan="4" bgcolor="<?=$list_divider?>"></TD>
	</TR>
	<TR>
		<TD align="center"><font color="#333333">÷������</font></TD>
		<TD colspan="3" style="padding-top:3px;border-left:<?=$list_divider?> 1px solid;"><input type=text name="up_filename" size="30" onfocus="this.blur();" style="BACKGROUND-COLOR:#F7F7F7;" class="input"> <INPUT type=button value="����÷��" style="BORDER:#cccccc 1px solid;CURSOR:hand;font-size:9pt;color:#000000;height:19px;background-color:#ECE9D8" onclick="FileUp();"> <font color="#FF4C00" style="font-size:11px;letter-spacing:-0.5pt;">* �ִ� <b><?=($setup[max_filesize]/1024)?>KB</b>���� ���ε� �����մϴ�.</font>
		<? if ($thisBoard[filename]) { ?>
		<br><font color="#FF4C00" style="font-size:11px;letter-spacing:-0.5pt;">* <?=$thisBoard[filename]?></font>
		<? } ?>
		</td>
	</TR>
	<TR>
		<TD height="1" colspan="4" bgcolor="<?=$list_divider?>"></TD>
	</TR>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	field = "";
	for(i=0;i<document.writeForm.elements.length;i++) {
		if(document.writeForm.elements[i].name.length>0) {
			field += "<input type=hidden name=ins4eField["+document.writeForm.elements[i].name+"]>\n";
		}
	}
	document.write(field);
	//-->
	</SCRIPT>
	</form>
	</TABLE>
	<table cellSpacing="0" cellPadding="0"><tr><td height="10"></td></tr></table>
	<div align="center">
		<img src="<?=$imgdir?>/butt-ok.gif" border="0" style="cursor:hand;" onclick="chk_writeForm(document.writeForm);"> &nbsp;&nbsp;
		<IMG SRC="<?=$imgdir?>/butt-cancel.gif" border="0" style="CURSOR:hand" onClick="history.go(-1);">
	</div>
	</td>
</tr>
</table>