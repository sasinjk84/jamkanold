<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="8"></td>
	</tr>
	<tr>
		<td>
			<table width="100%" border=0 cellpadding=0 cellspacing=0>
				<tr>
					<td><IMG SRC="images/market_bulkmail_title.gif"  ALT="��뷮���� �߼۰���"></td>
				</tr>
				<tr>
					<td width="100%" background="images/title_bg.gif" height="21"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="3"></td>
	</tr>
	<tr>
		<td style="padding-bottom:3pt;"> </td>
	</tr>
	<tr>
		<td>
			<?
			if(!empty($_REQUEST['gidx']) && intval($_REQUEST['gidx']) >0){
				$group = $bulkmail->_getGroup($_REQUEST['gidx']);
			}else{
				$group = array();
			}
			?>
			<form name="groupForm" method="post" action="">
				<input type="hidden" name="act" value="group" />
				<input type="hidden" name="md" value="" />
				<input type="hidden" name="gidx" value="<?=$group['gidx']?>" />
				<table border="0" cellpadding="0" cellspacing="0" class="formTbl" style="margin-bottom:10px; width:450px">
					<tr>
						<th style="width:120px;">�׷��</th>
						<td>
							<input type="text" name="gname" value="<?=$group['gname']?>" />
						</td>
					</tr>
					<tr>
						<th>�׷켳��</th>
						<td>
							<input type="text" name="memo" value="<?=$group['memo']?>" />
						</td>
					</tr>
				</table>
				<table border="0" cellpadding="0" cellspacing="0" class="formTbl" style="margin-bottom:10px; width:450px">
					<tr>
						<th style="width:120px;">ȸ�����</th>
						<td>
							<input type="hidden" name="member_group_name" value="" />
							<select name="member_group">
								<option value="">��üȸ��</option>
								<?
								$gquery = "select * from tblmembergroup";
								$gres= mysql_query($gquery,get_db_conn());
								$member_groups = array();
								if($gres){
									while($trow = mysql_fetch_assoc($gres)){										
										$sel = (!empty($group['member_group']) && $group['member_group'] == $trow['group_code'])?'selected':'';
										?>
								<option value="<?=$trow['group_code']?>" <?=$sel?>><?=$trow['group_name']?></option>								
										<?											
									}
								}
								
								?>
							</select>
						</td>
					</tr>
					<tr>
						<th>
							<select name="skey">
								<option value="">����</option>
								<option value="id" <?=(($group['skey'] == 'id')?'selected':'')?>>ID</option>
								<option value="name" <?=(($group['skey'] == 'name')?'selected':'')?>>�̸�</option>
								<option value="email" <?=(($group['skey'] == 'email')?'selected':'')?>>E-mail</option>
							</select>
						</th>
						<td>
							<input type="text" name="sval" value="<?=$group['sval']?>" />
						</td>
					</tr>
					<tr>
						<th>����������</th>
						<td>
							<input type="text" name="reserve[]" style="width:100px;" value="<?=$group['reserve'][0]?>" />
							~
							<input type="text" name="reserve[]" value="<?=$group['reserve'][1]?>" style="width:100px;" />
						</td>
					</tr>
					<tr>
						<th>����</th>
						<td>
							<input type="text" name="age[]" style="width:100px;" value="<?=$group['age'][0]?>" />
							~
							<input type="text" name="age[]" value="<?=$group['age'][1]?>" style="width:100px;" />
						</td>
					</tr>
					<tr>
						<th>����</th>
						<td>
							<input type="radio" name="gender" value="" <?=(empty($group['gender'])?'checked':'')?> />
							���ȸ��
							<input type="radio" name="gender" value="1" <?=(($group['gender'] == '1')?'checked':'')?> />
							��
							<input type="radio" name="gender" value="2" <?=(($group['gender'] == '2')?'checked':'')?> />
							��</td>
					</tr>
					<tr>
						<th>������</th>
						<td>
							<input type="text" name="joindate[]" value="<?=$group['joindate'][0]?>" OnClick="Calendar(this)" style="width:120px" />
							~
							<input type="text" name="joindate[]" value="<?=$group['joindate'][1]?>" OnClick="Calendar(this)" style="width:120px" />
						</td>
					</tr>
					<tr>
						<th>E-mail ����</th>
						<td>
							<input type="radio" name="news_yn" value=""  <?=(empty($group['news_yn'])?'checked':'')?> />
							���ȸ��
							<input type="radio" name="news_yn" value="Y" <?=(($group['news_yn'] == 'Y')?'checked':'')?> />
							�������ȸ��
							<input type="radio" name="news_yn" value="N" <?=(($group['news_yn'] == 'N')?'checked':'')?> />
							���ž���ȸ��</td>
					</tr>
					<tr>
						<th>��ȥ����</th>
						<td>
							<input type="radio" name="married_yn" value="" <?=(empty($group['married_yn'])?'checked':'')?>/>
							���ȸ��
							<input type="radio" name="married_yn" value="Y" <?=(($group['married_yn'] == 'Y')?'checked':'')?>/>
							��ȥ
							<input type="radio" name="married_yn" value="N" <?=(($group['married_yn'] == 'N')?'checked':'')?>/>
							��ȥ</td>
					</tr>
					<tr>
						<th>��ȭ</th>
						<td>
							<input type="text" name="home_tel" value="<?=$group['home_tel']?>" />
						</td>
					</tr>
					<tr>
						<th>�޴���ȭ</th>
						<td>
							<input type="text" name="mobile" value="<?=$group['mobile']?>" />
						</td>
					</tr>
				</table>
				<div style="width:100%;margin-top:5px; text-align:center">
					<input type="button" value="���" style="margin-right:5px;" onclick="javascript:checkForm()" />
					<input type="button" value="�˻�" style="margin-right:5px;" onclick="javascript:searchList()" />
					<input type="button" value="���" onclick="javascript:goList();" />
				</div>
			</form>
			<script language="javascript" type="text/javascript">
			function checkForm(){
				document.groupForm.action = '/admin/bulkmail/process.php';
				document.groupForm.submit();
			}
			
			function searchList(){
				document.groupForm.action = '/admin/bulkmail.php';
				document.groupForm.act.value = 'search';
				document.groupForm.submit();
			}
			
			function goList(){
				history.back();
			}
			</script>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>
			<table width="100%" border=0 cellpadding=0 cellspacing=0>
				<tr>
					<td><IMG SRC="images/manual_top1.gif" width=15 height="45" ALT=""></td>
					<td><IMG SRC="images/manual_title.gif" width=113 height="45" ALT=""></td>
					<td width="100%" background="images/manual_bg.gif" height="35"></td>
					<td background="images/manual_bg.gif"></td>
					<td background="images/manual_bg.gif"><IMG SRC="images/manual_top2.gif" width=18 height="45" ALT=""></td>
				</tr>
				<tr>
					<td background="images/manual_left1.gif"></td>
					<td COLSPAN=3 width="100%" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;" class="menual_bg"> </td>
					<td background="images/manual_right1.gif"></td>
				</tr>
				<tr>
					<td><IMG SRC="images/manual_left2.gif" width=15 HEIGHT=8 ALT=""></td>
					<td COLSPAN=3 background="images/manual_down.gif"></td>
					<td><IMG SRC="images/manual_right2.gif" width=18 HEIGHT=8 ALT=""></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="50"></td>
	</tr>
</table>
