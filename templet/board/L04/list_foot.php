	<TR>
		<TD colspan="<?=$table_colcnt?>">
		<TABLE border="0" cellpadding="3" cellspacing="0" width="100%">
			<tr><td height="20"></td></tr>
			<TR>
				<TD align="right"><?=$hide_write_start?><A HREF="board.php?pagetype=write&board=<?=$board?>&exec=write" class="btn_line">�۾���</A><?=$hide_write_end?></TD>
			</TR>
			<tr><td height="10"></td></tr>
			<TR>
				<TD align="center">


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

					// ����
					function boardSort ( t ) {
						var v = ( sort.value == "" || sort.value == t+"_asc" ) ? "desc":"asc";
						location.href="?board=<?=$board?>&sort="+t+"_"+v;
					}
					</script>
					<input type="hidden" name="sort" id="sort" value="<?=$sort?>">
				</TD>
			</TR>
			<TR>
				<TD align="center" width="100%">
					<div class="pageingarea" style="text-align:center;width:100%; margin-bottom:20px;"><?=$pobj->_result('fulltext')?></div>					
				</TD>
			</TR>
			<tr>
				<td>
					<table cellpadding="0" cellspacing="0" width="<?=$setup[board_width]?>" style="table-layout:fixed">
						<tr>
							<td height="26" align="center">
								<table cellpadding="0" cellspacing="0">
									<form method=get name=frm action=<?=$PHP_SELF?> onSubmit="return schecked()">
									<input type="hidden" name="pagetype" value="list">
									<input type="hidden" name="board" value="<?=$board?>">
									<tr>
										<td>
											<input type=radio class="radio" name="s_check" value="c" <?=$check_c?> style="border:none">����+����
											<input type=radio name="s_check"  class="radio"  value="n" <?=$check_n?> style="border:none">�ۼ���
											<?=$subCategoryList_start?>
											<?=$subCategoryList?>
											<?=$subCategoryList_end?>
										</td>
										<td style="padding-left:5px;padding-right:5px;"><input type=text name="search" value="<?=$search?>" size="12" class="input"></td>
										<td><INPUT type="submit" style="cursor:pointer;" border="0" align="absMiddle" style="border:none" value="�˻�" class="btn_gray"></td>
									</tr>
									</FORM>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</TABLE>
		</TD>
	</TR>
	</TABLE>
	</TD>
</TR>
</TABLE>