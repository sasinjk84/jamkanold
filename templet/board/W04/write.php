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

	.w04write{width:100%;border-top:2px solid #000000;}
	.w04write th{width:15%;padding:5px;background-color:#F8F8F8;border-bottom:1px solid #DFDFDF;letter-spacing:-0.5pt;color:#333333;}
	.w04write td{width:35%;padding:5px;background-color:#FFFFFF;border-bottom:1px solid #DFDFDF;border-left:1px solid #DFDFDF;}
</style>

<script language="javascript" type="text/javascript">
$(document).ready(function() {
	ejEditor();
});
</script>
<? } ?>

<TABLE cellSpacing="0" cellPadding="0" width="<?=$setup[board_width]?>" border="0">
<tr>
	<td>
	<table cellSpacing="0" cellPadding="0" width="100%" bgcolor="<?=$view_left_header_color?>" class="w04write">
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
	<?= $hide_secret_start ?>
	<tr>
		<Th>��ݱ��</Th>
		<TD colspan="3"><?= writeSecret($exec,$thisBoard[is_secret],$thisBoard[pos]) ?></TD>
	</tr>
	<?= $hide_secret_end ?>

	<? if (strlen($member[name])>0) { ?>
	<TR>
		<Th>�ۼ���</Th>
		<TD colspan="3"><input type=text name="up_name" value="<?=$thisBoard[name]?>" size="13" maxlength="20" style="BACKGROUND-COLOR:#F7F7F7;width:160px" class="input" /></TD>
	</TR>
	<? } else{ ?>
	<TR>
		<Th>�ۼ���</Th>
		<TD><input type=text name="up_name" value="<?=$thisBoard[name]?>" size="13" maxlength="20" style="BACKGROUND-COLOR:#F7F7F7;width:160px" class="input" /></TD>
		<Th>��й�ȣ</Th>
		<TD><input type=password name="up_passwd" value="<?=$thisBoard[passwd]?>" size="13" maxlength="20" style="BACKGROUND-COLOR:#F7F7F7;width:160px" class="input" /></TD>
	</TR>
	<? } ?>

	<TR>
		<Th>�̸���</Th>
		<TD colspan="3"><input type=text name="up_email" value="<?=$thisBoard[email]?>" size="49" maxlength="60" style="BACKGROUND-COLOR:#F7F7F7;width:240px" class="input" /> <font color="#FF4C00" style="font-size:11px;letter-spacing:-0.5pt;">* �亯�� ������ E-mail�� �Է��ϼ���.</font></TD>
	</TR>
	<?= $hide_replysms_start ?>
	<TR>
		<Th align="center"><font color="#333333">�޴���</font></Th>
		<TD colspan="3"><input type=text name="up_cel1" value="<?=$thisBoard[cel][0]?>" size="5" maxlength="4" style="BACKGROUND-COLOR:#F7F7F7;" class="input" />-<input type=text name="up_cel2" value="<?=$thisBoard[cel][1]?>" size="5" maxlength="4" style="BACKGROUND-COLOR:#F7F7F7;" class="input" />-<input type=text name="up_cel3" value="<?=$thisBoard[cel][2]?>" size="5" maxlength="4" style="BACKGROUND-COLOR:#F7F7F7;" class="input" /> <font color="#FF4C00" style="font-size:11px;letter-spacing:-0.5pt;">* �亯��Ͻ� �˸� ������ �޴�����ȣ�� �Է��ϼ���.</font></TD>
	</TR>
	<?= $hide_replysms_end ?>

	<?=$subCategoryList_start?>
	<tr>
		<th>���Ӹ�</th>
		<td colspan="3"><?=$subCategoryList?></td>
	</tr>
	<?=$subCategoryList_end?>

	<TR>
		<Th>������</Th>
		<TD colspan="3"><input type=text name="up_subject" value="<?=$thisBoard[title]?>" size="70" maxlength="200" class="input" style="BACKGROUND-COLOR:#F7F7F7;width:100%;" /></TD>
	</TR>
	<TR>
		<Th>�۳���</Th>
		<TD colspan="3"><textarea name="up_memo" lang="ej-editor3"  style="width:590;height:280px;border:1 solid <?=$list_divider?>;PADDING:5px;line-height:17px;font-size:9pt;color:333333;" wrap="<?=$setup[wrap]?>"><?=$thisBoard[content]?></textarea></TD>
	</TR>
	<script>putSubject("<?=addslashes($thisBoard[title])?>");</script>
	<TR>
		<Th>÷������</Th>
		<TD colspan="3"><input type=text name="up_filename" size="30" onfocus="this.blur();" style="BACKGROUND-COLOR:#F7F7F7;" class="input"> <INPUT type=button value="����÷��" style="BORDER:#cccccc 1px solid;CURSOR:hand;font-size:9pt;color:#000000;height:19px;background-color:#ECE9D8" onclick="FileUp();" /> <font color="#FF4C00" style="font-size:11px;letter-spacing:-0.5pt;">* �ִ� <b><?=($setup[max_filesize]/1024)?>KB</b>���� ���ε� �����մϴ�.</font>
		<? if ($thisBoard[filename]) { ?>
		<br><font color="#FF4C00" style="font-size:11px;letter-spacing:-0.5pt;">* <?=$thisBoard[filename]?></font>
		<? } ?>
		</td>
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

	<div style="margin:20px 0px; text-align:center;">
		<img src="<?=$imgdir?>/butt-ok.gif" border="0" style="cursor:hand;" onclick="chk_writeForm(document.writeForm);"> &nbsp;&nbsp;
		<IMG SRC="<?=$imgdir?>/butt-cancel.gif" border="0" style="CURSOR:hand" onClick="history.go(-1);">
	</div>
	</td>
</tr>
</table>