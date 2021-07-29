	<table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-bottom:8px; table-layout:fixed;">
		<tr>
			<td>
				전체 <font class="TD_TIT4_B"><B><?= $t_count ?></B></font>건 조회<span style="color:#f2f2f2;padding:0px 8px;">|</span>현재 <B><?=$gotopage?></B>/<B><?=ceil($t_count/$setup[list_num])?></B> 페이지
				<?=$setCategoryList_start?><span style="color:#f2f2f2;padding:0px 8px;">|</span><?=$setCategoryList?>
				<?=$setCategoryList_end?>
			</td>
			<td style="padding-left:5px;"><?=$strAdminLogin?></td>
		</tr>
	</table>

	<table cellpadding="0" cellspacing="0" width="100%" border="0" style="table-layout:fixed" class="boardTable">
	<col width="40"></col>
	<col></col>
	<col width="30"></col>
	<col width="80"></col>
	<?=$hide_hit_start?>
	<col width="50"></col>
	<?=$hide_hit_end?>
	<col width="50"></col>
	<?=$hide_date_start?>
	<col width="110"></col>
	<?=$hide_date_end?>
	<tr>
		<th>NO</th>
		<th>글제목</th>
		<th>파일</th>
		<th>글쓴이</th>
		<?=$hide_hit_start?>
		<th><a href="javascript:boardSort('access');">조회수<?=$access_sortIcon?></a></th>
		<?=$hide_hit_end?>
		<th><a href="javascript:boardSort('vote');">추천수<?=$vote_sortIcon?></a></th>
		<?=$hide_date_start?>
		<th>작성일</th>
		<?=$hide_date_end?>
	</tr>
