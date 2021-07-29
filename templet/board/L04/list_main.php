	<!-- 목록 부분 시작 -->
	<TR align="center" onMouseOver="this.style.backgroundColor='<?=$list_mouse_over_color?>';" onMouseOut="this.style.backgroundColor='';">
		<TD nowrap   class="tdCon" ><?=$number?></TD>
		<TD nowrap  class="tdCon"  align="left" style="word-break:break-all;padding-left:5px;padding-right:5px;"><?=$secret_img?> <?=$subject?> &nbsp;&nbsp;<?=$prview_img?></TD>
		<TD nowrap  class="tdCon" ><?=$file_icon?></TD>
		<TD nowrap  class="tdCon" ><?=$str_name?></TD>
		<?=$hide_hit_start?>
		<TD nowrap  class="tdCon" ><?=$hit?></TD>
		<?=$hide_hit_end?>
		<TD nowrap  class="tdCon" ><?=$vote?></TD>
		<?=$hide_date_start?>
		<TD nowrap  class="tdCon" ><?=$reg_date?></TD>
		<?=$hide_date_end?>
	</TR>
	<!-- 목록 부분 끝 -->