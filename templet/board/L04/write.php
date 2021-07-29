
<SCRIPT LANGUAGE="JavaScript">
<!--
function chk_writeForm(form) {
	if (typeof(form.tmp_is_secret) == "object") {
		form.up_is_secret.value = form.tmp_is_secret.options[form.tmp_is_secret.selectedIndex].value;
	}

	if (!form.up_name.value) {
		alert('작성자을 입력하십시오.');
		form.up_name.focus();
		return false;
	}

	<? if (strlen($member[id])==0) { ?>
	if (!form.up_passwd.value) {
		alert('비밀번호를 입력하십시오.');
		form.up_passwd.focus();
		return false;
	}
	<? } ?>

	if (!form.up_subject.value) {
		alert('제목을 입력하십시오.');
		form.up_subject.focus();
		return false;
	}

	if (!form.up_memo.value) {
		alert('내용을 입력하십시오.');
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
		<td>
			<table cellSpacing="0" cellPadding="0"  class="tblWrite">
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
				<input type=hidden name=pridx value=<?=$pridx?>>
				<input type=hidden name=pos value="<?=$thisBoard[pos]?>">
				<input type=hidden name=up_is_secret value="<?=$thisBoard[is_secret]?>">
				<col width="5%"></col>
				<col width="45%"></col>
				<col width="5%"></col>
				<col width="45%"></col>
				<?= $hide_secret_start ?>
				<TR>
					<Th align="center">잠금기능</Th>
					<TD colspan="3"><?= writeSecret($exec,$thisBoard[is_secret],$thisBoard[pos]) ?></TD>
				</TR>
				<?= $hide_secret_end ?>

				<? if (strlen($member[name])>0) { ?>
				<TR>
					<Th align="center">작성자</Th>
					<TD><input type=text name="up_name" value="<?=$thisBoard[name]?>" size="13" maxlength="20" style="width:160px" class="input" colspan="3"></TD>
				</TR>
				<? } else{ ?>
				<TR>
					<Th align="center">작성자</Th>
					<TD><input type=text name="up_name" value="<?=$thisBoard[name]?>" size="13" maxlength="20" style="width:160px" class="input"></TD>
					<Th align="center">비밀번호</Th>
					<TD><input type=password name="up_passwd" value="<?=$thisBoard[passwd]?>" size="13" maxlength="20" style="width:160px" class="input"></TD>
				</TR>
				<? } ?>

				<TR>
					<Th align="center">이메일</Th>
					<TD colspan="3"><input type=text name="up_email" value="<?=$thisBoard[email]?>" size="49" maxlength="60" style="width:240px" class="input"></TD>
				</TR>

				<?= $hide_replysms_start ?>
				<TR>
					<Th align="center">휴대폰</Th>
					<TD colspan="3"><input type=text name="up_cel1" value="<?=$thisBoard[cel][0]?>" size="5" maxlength="4" style="" class="input">-<input type=text name="up_cel2" value="<?=$thisBoard[cel][1]?>" size="5" maxlength="4" style="" class="input">-<input type=text name="up_cel3" value="<?=$thisBoard[cel][2]?>" size="5" maxlength="4" style="" class="input"></TD>
				</TR>
				<?= $hide_replysms_end ?>

				<?=$subCategoryList_start?>
				<tr>
					<th align="center">말머리</th>
					<td colspan="3"><?=$subCategoryList?></td>
				</tr>
				<?=$subCategoryList_end?>

				<TR>
					<Th align="center">글제목</Th>
					<TD colspan="3"><input type=text name="up_subject" value="<?=$thisBoard[title]?>" maxlength="200" class="input" style=" width:95%;"></TD>
				</TR>

				<? if( $setup[linkboard] ) { ?>
				<TR>
					<Th align="center">URL</Th>
					<TD colspan="3"><input type=text name="up_url" value="<?=$thisBoard[url]?>" maxlength="200" class="input" style=" width:95%;"></TD>
				</TR>
				<? } ?>

				<TR <?=( $setup[linkboard]?"style=\"display:none;\"":"")?>>
					<Th align="center">글내용</Th>
					<TD colspan="3">
						<textarea name="up_memo" lang="ej-editor3" style="width:100%;height:280px;border:1 solid #ededed;PADDING:8px;line-height:17px;font-size:9pt;color:333333;" wrap="<?=$setup[wrap]?>"><?=$thisBoard[content]?><?=( $setup[linkboard]?"******\"":"")?></textarea>
					</TD>
				</TR>
				<script>putSubject("<?=addslashes($thisBoard[title])?>");</script>
				<!--
				<TR <?=( $setup[linkboard]?"style=\"display:none;\"":"")?>>
					<TD height="1" colspan="4" bgcolor="#ededed"></TD>
				</TR>
				-->

				<TR>
					<Th align="center">첨부파일</Th>
					<TD colspan="3" style="padding-top:3px;border-left:#ededed 1px solid;"><input type=text name="up_filename" size="30" onfocus="this.blur();" style="" class="input"> <INPUT type=button value="파일첨부" style="CURSOR:pointer;vertical-align:middle;padding: 6px 13px;text-align: center;font-size: 13px;box-sizing: border-box;background: #ffffff;border: rgba(0, 0, 0, 0.1) 1px solid;color: #4f4f4f;
}" onclick="FileUp();">(최대 <?=($setup[max_filesize]/1024)?>KB까지 업로드 가능합니다.)
					<? if ($thisBoard[filename]) { ?>
					<br><font color="#FF4C00" style="font-size:11px;letter-spacing:-0.5pt;">* <?=$thisBoard[filename]?></span>
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

			<div style="margin-top:15px;text-align:center;">
				<img src="<?=$imgdir?>/butt-ok.gif" border="0" style="cursor:hand;" onclick="chk_writeForm(document.writeForm);"> &nbsp;&nbsp;
				<IMG SRC="<?=$imgdir?>/butt-cancel.gif" border="0" style="CURSOR:hand" onClick="history.go(-1);">
			</div>
		</td>
	</tr>
</table>