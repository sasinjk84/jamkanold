	<!-- 목록 부분 시작 -->
	<tr>
		<TD nowrap  class="tdCon" ><img src="<?=$imgdir?>/icon_notice.gif" border="0"></TD>
		<TD nowrap  class="tdCon"  colspan="<?=(strlen($hide_date_start)>0?$table_colcnt-1:$table_colcnt-2)?>" align="left" style="word-break:break-all;padding:15px 0px"><a href="board.php?pagetype=view&board=<?=$board?>&view=1&num=<?=$nRow[num]?>&block=<?=$nowblock?>&gotopage=<?=$gotopage?>&search=<?=$search?>&s_check=<?=$s_check?>"><?=$nRow[title]?></A></TD>
		<?=$hide_date_start?>
		<TD nowrap   class="tdCon" ><?=$nRow[writetime]?></TD>
		<?=$hide_date_end?>
	</TR>