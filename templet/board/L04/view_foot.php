		</TD>
	</TR>
	<TR><TD height="10"></TD></TR>
	<!--<TR><TD height="1" bgcolor="#EDEDED"></TD></TR>-->
	<!-- ��ư ���� ��� -->
	<TR height="50">
		<TD>
			<table border="0" cellspacing="0" width="100%" STYLE="TABLE-LAYOUT:FIXED">
				<tr>
					<td>
					<?=$voteButton?>
					<?
						//2014/07/04�� ���� ������ �����ڸ� ��� �� ��� ��ư ���� ���� ����  �����ϱ�,�����ϱ�
						if($setup['grant_write'] != "A"){
					?>
					<?=$reply_start?><A HREF="board.php?pagetype=write&exec=reply&board=<?=$board?>&num=<?=$num?>&s_check=<?=$s_check?>&search=<?=$search?>&block=<?=$nowblock?>&gotopage=<?=$gotopage?>" class="btn_line">�亯�ޱ�</A><?=$reply_end?>

					<?= $hide_delete_start ?>
					<A HREF="board.php?pagetype=passwd_confirm&exec=modify&board=<?=$board?>&num=<?=$num?>&s_check=<?=$s_check?>&search=<?=$search?>&block=<?=$block?>&gotopage=<?=$gotopage?>"  class="btn_line">�����ϱ�</A>

					<A HREF="board.php?pagetype=passwd_confirm&exec=delete&board=<?=$board?>&num=<?=$num?>&s_check=<?=$s_check?>&search=<?=$search?>&block=<?=$block?>&gotopage=<?=$gotopage?>" class="btn_line">�����ϱ�</A>
					<?= $hide_delete_end ?>
					<?}?>
					<?=$hide_write_start?><A HREF="board.php?pagetype=write&exec=write&board=<?=$board?>" class="btn_line">�۾���<?=$hide_write_end?>
					</td>
					<TD align=right><A HREF="board.php?pagetype=list&board=<?=$board?>&s_check=<?=$s_check?>&search=<?=$search?>&block=<?=$nowblock?>&gotopage=<?=$gotopage?>" class="btn_line">�������</A></td>
				</TR>
			</table>
		</td>
	</tr>

	<?if($tr_str1) { ?>
	<?=$hide_reply_start?>
<!--
	<tr>
		<td><img src="<?=$imgdir?>/board_article_reply.gif" border="0"></td>
	</tr>
-->
	<TR>
		<TD>
			<table border="0" cellpadding="0" cellspacing="0" width="100%" STYLE="TABLE-LAYOUT:FIXED;margin:10px 0px;border:1px solid #ededed">
				<tr>
					<td width="100%">
					<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<col width=></col>
						<col width="100"></col>
						<col width="100"></col>
						<?= $tr_str1 ?>
					</table>
					</td>
				</tr>
			</table>
		</TD>
	</TR>
	<?=$hide_reply_end?>
	<? } ?>
	<TR>
		<TD>
		<?=$hide_prev_start?>
		<TABLE border="0" cellspacing="0" cellpadding="0" width="100%" style="table-layout:fixed;border-top:0px solid #ededed;padding-top:10px;">
			<col width="80"></col>
			<col width=></col>
			<col width="100"></col>
			<TR height="24" ALIGN="CENTER" onMouseOver="this.style.backgroundColor='<?=$list_mouse_over_color?>'" onMouseOut="this.style.backgroundColor=''" style="CURSOR:hand;" onClick="location='board.php?pagetype=view&view=1&board=<?=$board?>&num=<?=$p_row[num]?>&block=<?=$nowblock?>&gotopage=<?=$gotopage?>&search=<?=$search?>&s_check=<?=$s_check?>';">
				<TD>������</td>
				<td align="left"><?=$prevTitle?></td>
				<TD><?=$prevName?></td>
			</TR>
		</TABLE>
		<?=$hide_prev_end?>
		<? if($hide_prev_start) { ?>
		<TABLE border="0" cellpadding="0" cellspacing="0" style="table-layout:fixed">
			<tr><td height="10"></td></tr>
			<TR><TD height="1" bgcolor="#EDEDED"></td></TR>
		</TABLE>
		<? } else if($hide_next_start) { ?>
		<TABLE border="0" cellpadding="0" cellspacing="0" style="table-layout:fixed">
			<tr><TD height="1" bgcolor="#EDEDED"></td></tr>
		</TABLE>
		<? } ?>
		<?=$hide_next_start?>
		<TABLE border="0" cellspacing="0" cellpadding="0" width="100%" style="table-layout:fixed;border-bottom:1px solid #ededed;padding-bottom:10px;">
			<col width="80"></col>
			<col width=></col>
			<col width="100"></col>
			<TR height="24" ALIGN="CENTER" onMouseOver="this.style.backgroundColor='<?=$list_mouse_over_color?>'" onMouseOut="this.style.backgroundColor=''" style="CURSOR:hand;" onClick="location='board.php?pagetype=view&view=1&board=<?=$board?>&num=<?=$n_row[num]?>&block=<?=$nowblock?>&gotopage=<?=$gotopage?>&search=<?=$search?>&s_check=<?=$s_check?>';">
				<TD>������</td>
				<td align="left"><?=$nextTitle?></td>
				<TD><?=$nextName?></td>
			</TR>
		</TABLE>
		<?=$hide_next_end?>
		</TD>
	</TR>
	</TABLE>
	</td>
</tr>
</table>
<BR><BR>