<?
$bankda->_authMatch();

####################### ������ ���ٱ��� check ###############
$PageCode = "or-4";
$MenuCode = "order";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("../AccessDeny.inc.php");
	exit;
}
/*
if($_POST['mode'] == "delete") {
	$d = $bankda->_delete($_POST['Bkid'],$info['user_id']);
}

if($orderby == "") {
	$orderby = "DESC";
}
$arr_status = array("N"=>"Ȯ����","T"=>"�Ա�Ȯ��(�ڵ�)","B"=>"�Ա�Ȯ��(����)","S" => "��������","F" => "����(����ġ)","A" => "�������Ա�Ȯ��");
*/
#########################################################
$CurrentTime = time();
$period[0] = date("Y-m-d",$CurrentTime);
$period[1] = date("Y-m-d",$CurrentTime-(60*60*24*7));
$period[2] = date("Y-m-d",$CurrentTime-(60*60*24*15));
$period[3] = date("Y-m-d",mktime(0,0,0,date("m")-1,date("d"),date("Y")));
$period[4] = date("Y-m-d",mktime(0,0,0,date("m")-2,date("d"),date("Y")));
$period[5] = date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")-10));//��ü

if(_empty($_REQUEST['search_start'])) $_REQUEST['search_start']=$period[0];
if(_empty($_REQUEST['search_end'])) $_REQUEST['search_end']=date("Y-m-d",$CurrentTime);

$matching_period[0] = date("Y-m-d",$CurrentTime);
$matching_period[1] = date("Y-m-d",$CurrentTime-(60*60*24*7));
$matching_period[2] = date("Y-m-d",$CurrentTime-(60*60*24*15));
$matching_period[3] = date("Y-m-d",mktime(0,0,0,date("m")-1,date("d"),date("Y")));
$matching_period[4] = date("Y-m-d",mktime(0,0,0,date("m")-2,date("d"),date("Y")));
$period[5] = date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")-10));//��ü
$search_matching_start=$search_matching_start?$search_matching_start:$matching_period[0];
$search_matching_end=$search_matching_end?$search_matching_end:date("Y-m-d",$CurrentTime);

if($_POST['mode'] == "delete") {
	$d = $bankda->_deleteList($_POST['Bkid']);	
	if(!_empty($d['err']) && $d['err'] != 'ok' ) _alert($d['err']);
	unset($_POST['mode'],$_REQUEST['mode']);
}

$listinfo = $bankda->_getList($_REQUEST);
//_pr($res);
?>

<script type="text/javascript" src="/admin/lib.js.php"></script>
<script type="text/javascript" src="/admin/calendar.js.php"></script>
<script language="JavaScript">
function OnChangePeriod(val) {
	var pForm = document.form1;
	var period = new Array(7);
	period[0] = "<?=$period[0]?>";
	period[1] = "<?=$period[1]?>";
	period[2] = "<?=$period[2]?>";
	period[3] = "<?=$period[3]?>";
	period[4] = "<?=$period[4]?>";
	period[5] = "<?=$period[5]?>";

	pForm.search_start.value = period[val];
	pForm.search_end.value = period[0];
}

function OnChangematchingPeriod(val) {
	var pForm = document.form1;
	var matching_period = new Array(7);
	matching_period[0] = "<?=$period[0]?>";
	matching_period[1] = "<?=$period[1]?>";
	matching_period[2] = "<?=$period[2]?>";
	matching_period[3] = "<?=$period[3]?>";
	matching_period[4] = "<?=$period[4]?>";
	matching_period[5] = "<?=$period[5]?>";

	pForm.search_matching_start.value = matching_period[val];
	pForm.search_matching_end.value = matching_period[0];
}

function GoPage(page,listnum) {
	document.form1.page.value = page;
	document.form1.list_num.value = listnum;
	document.form1.submit();
}


function GoOrderby(orderby) {
	document.form1.orderby.value = orderby;
	document.form1.submit();
}

function OrderDetailView(ordercode) {
	document.detailform.ordercode.value = ordercode;
	window.open("","orderdetail","scrollbars=yes,width=700,height=600");
	document.detailform.submit();
}

function CheckForm(){
	document.form1.submit();
}

function CheckDelete(){
	option = confirm("������ ������ ���� �Ͻðڽ��ϱ�?"); 
	if(option == true ){ 
		document.form1.mode.value = "delete";		
		document.form1.submit();
	}
}

</script>
<style type="text/css">
.tblStyle{font-size:11px; line-height:200%; width:100%; margin-top:15px;}
.tblStyle caption{ text-align:left}
.tblStyle thead th{ background:##f8f8f8;  color:#4b4b4b;  font-weight:bold; border-bottom:1px solid #ddd;}
.tblStyle thead td{ border-bottom:1px solid #ddd; border-left:#e3e3e3}

.tblStyle tbody th{ background:##f8f8f8;  color:#4b4b4b;  font-weight:bold; border-bottom:1px solid #ddd;}
.tblStyle tbody td{ border-bottom:1px solid #ddd; border-left:#e3e3e3}

</style>
<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
<input type=hidden name=mode>
<input type=hidden name=orderby value="<?=$orderby?>">
<input type=hidden name=list_num value="<?=$_REQUEST['list_num']?>">
<input type=hidden name=page value="<?=$_REQUEST['page']?>">
<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
	<tr>
		<td>
			<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/order_bankm_title.gif" ALT="������ �Ա�Ȯ��"></TD>
				</tr>
				<tr>
					<TD width="100%" background="images/title_bg.gif" height="21"></TD>
				</TR>
			</TABLE>
		</td>
	</tr>
	<tr>
		<td height="3"></td>
	</tr>
	<tr>
		<td style="padding-bottom:3pt;">
			<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/distribute_01.gif"></TD>
					<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
					<TD><IMG SRC="images/distribute_03.gif"></TD>
				</TR>
				<TR>
					<TD background="images/distribute_04.gif"><IMG SRC="images/distribute_04.gif" ></TD>
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
					<TD width="100%" class="notice_blue"><p>������ �Ա�Ȯ���� �Ͻ� �� �ֽ��ϴ�.</p></TD>
					<TD background="images/distribute_07.gif"><IMG SRC="images/distribute_07.gif" ></TD>
				</TR>
				<TR>
					<TD><IMG SRC="images/distribute_08.gif"></TD>
					<TD COLSPAN=2 background="images/distribute_09.gif"></TD>
					<TD><IMG SRC="images/distribute_10.gif"></TD>
				</TR>
			</TABLE>
		</td>
	</tr>
	<tr>
		<td height=20></td>
	</tr>	
	<tr>
		<td>
			<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/order_bankm_stitle1.gif" ALT="������ �Ա���ȸ"></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
			</TABLE>
		</td>
	</tr>
	<tr>
		<td height=3></td>
	</tr>
	<tr>
		<td>
			<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<col class=cellC><col class=cellL><col class=cellC><col class=cellL width=35%>
				<TR>
					<TD background="images/table_top_line.gif" width="153"><img src="images/table_top_line.gif"></TD>
					<TD colspan="3" background="images/table_top_line.gif" width="607" ></TD>
				</TR>
				<tr>
					<td class="table_cell">Ű����˻�</td>
					<td class="td_con1" >
						<select name="skey">
						<option value="" > �����ϱ� </option>
						<option value="bkjukyo" <?if($skey == "bkjukyo") echo "selected";?>> �Ա��ڸ� </option>
						<option value="bkinput" <?if($skey == "bkinput") echo "selected";?>> �Աݿ����ݾ� </option>
						<option value="ordercode" <?if($skey == "ordercode") echo "selected";?>> �ֹ���ȣ </option>
						</select>
						<input type="text" NAME="sword" value="<?=$sword?>" class="line">
					</td>
					<td class="table_cell">�������<font class=small color=444444>/</font>�����</td>
					<td class="td_con1" >
						<select name="Bkstatus">
						<option value=""> ��ü </option>
						<?
						foreach($bankda->statusArr as $stkey=>$stval){ 
							$sel = ($_REQUEST['Bkstatus'] == $stkey)?'selected':'';
						?>						
						<option value="<?=$stkey?>" <?=$sel?>><?=$stval?></option>
						<? } ?>
						</select>

						<select name="bkname">
						<option value="">������˻�</option>
						<option value="�������"  <?if($_REQUEST['bkname'] == "�������") echo "selected";?>>�������		
						<option value="��������"  <?if($_REQUEST['bkname'] == "��������") echo "selected";?>>��������		
						<option value="��ȯ����"  <?if($_REQUEST['bkname'] == "��ȯ����") echo "selected";?>>��ȯ����		
						<option value="��������"  <?if($_REQUEST['bkname'] == "��������") echo "selected";?>>��������		
						<option value="�����߾�ȸ"  <?if($_REQUEST['bkname'] == "�����߾�ȸ") echo "selected";?>>�����߾�ȸ		
						<option value="��������"  <?if($_REQUEST['bkname'] == "��������") echo "selected";?>>��������		
						<option value="�츮����"  <?if($_REQUEST['bkname'] == "�츮����") echo "selected";?>>�츮����		
						<option value="��������"  <?if($_REQUEST['bkname'] == "��������") echo "selected";?>>��������		
						<option value="��������"  <?if($_REQUEST['bkname'] == "��������") echo "selected";?>>��������		
						<option value="��������"  <?if($_REQUEST['bkname'] == "��������") echo "selected";?>>��������		
						<option value="��������"  <?if($_REQUEST['bkname'] == "��������") echo "selected";?>>��������		
						<option value="�ѹ�����"  <?if($_REQUEST['bkname'] == "�ѹ�����") echo "selected";?>>�ѹ�����		
						<option value="�뱸����"  <?if($_REQUEST['bkname'] == "�뱸����") echo "selected";?>>�뱸����		
						<option value="�λ�����"  <?if($_REQUEST['bkname'] == "�λ�����") echo "selected";?>>�λ�����		
						<option value="��������"  <?if($_REQUEST['bkname'] == "��������") echo "selected";?>>��������		
						<option value="��������"  <?if($_REQUEST['bkname'] == "��������") echo "selected";?>>��������		<option value="��������"  <?if($_REQUEST['bkname'] == "��������") echo "selected";?>>��������		<option value="�泲����"  <?if($_REQUEST['bkname'] == "�泲����") echo "selected";?>>�泲����		<option value="�������ݰ�"  <?if($_REQUEST['bkname'] == "�������ݰ�") echo "selected";?>>�������ݰ�		<option value="��ü��"  <?if($_REQUEST['bkname'] == "��ü��") echo "selected";?>>��ü��		<option value="�ϳ�����"  <?if($_REQUEST['bkname'] == "�ϳ�����") echo "selected";?>>�ϳ�����
						</select>
					</td>
				</tr>
				<TR>
					<TD colspan="4" background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></TD>
				</TR>
				<tr>
					<td class="table_cell">�Ա���</td>
					<td colspan="3" class="td_con1" >
						<input type=text name="search_start" value="<?=$_REQUEST['search_start']?>" size=13 onfocus="this.blur();" OnClick="Calendar(this)"  class="input_selected"> ~ <input type=text name=search_end value="<?=$_REQUEST['search_end']?>" size=13 onfocus="this.blur();" OnClick="Calendar(this)"  class="input_selected">
						<img src="images/btn_today01.gif" border=0 align="absmiddle" style="cursor:hand" onclick="OnChangePeriod(0)" alt="����">
						<img src="images/btn_day07.gif" border=0 align="absmiddle" style="cursor:hand" onclick="OnChangePeriod(1)" alt="7��">
						<img src="images/btn_day15.gif" border=0 align="absmiddle" style="cursor:hand" onclick="OnChangePeriod(2)" alt="15��">
						<img src="images/btn_day30.gif" border=0 align="absmiddle" style="cursor:hand" onclick="OnChangePeriod(3)" alt="1����"></a>
						<img src="images/btn_day60.gif" border=0 align="absmiddle" style="cursor:hand" onclick="OnChangePeriod(4)" alt="�δ�">
						<img src="images/btn_day.gif" border=0 align="absmiddle" style="cursor:hand" onclick="OnChangePeriod(5)" alt="��ü">
					</td>
				</tr>
				<TR>
					<TD colspan="4" background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></TD>
				</TR>				
			</table>
		</td>
	</tr>
	<tr>
		<td height=10></td>
	</tr>
	<tr>
		<td align="center"><p><a href="javascript:CheckForm();"><img src="bankda/images/botteon_search.gif" border="0"></a><!--  <a href="./bankda/bank_match.php?ad=admin"><img src="bankda/images/botteon_matching.gif" border="0"></a> --></p></td>
	</tr>

	<tr>
		<td height="50"></td>
	</tr>
	<tr>
		<td>
			<table>
				<tr>
					<td>						
						
						<tr>
							<td style="padding-bottom:3pt;">
								<table cellpadding="0" cellspacing="0" width="100%">
									<tr>
										<td >
											<img src="images/icon_8a.gif" width="13" height="13" border="0"><B>���� :
											<A HREF="javascript:GoOrderby('<?if($orderby == "DESC") echo "ASC"; else echo "DESC";?>');"><B><FONT class=font_orange>�Ա�����<?if($orderby == "DESC") echo "��"; else echo "��";?></FONT></B></A>
										</td>
										<td  align="right"><img src="images/icon_8a.gif" width="13" height="13" border="0">�� : <B><?=number_format($listinfo['total'])?></B>��, &nbsp;&nbsp;<img src="images/icon_8a.gif" width="13" height="13" border="0">���� <b><?=$listinfo['page']?>/<?=$listinfo['totalpage']?></b> ������</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td>
								<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0 style="table-layout:fixed">
									<col width=4%></col>
									<col width=5%></col>
									<col width=12%></col>
									<col width=12%></col>
									<col width=10%></col>
									<col width=11%></col>
									<col width=11%></col>
									<col width=11%></col>
									<col width=12%></col>
									<col width=11%></col>
									<input type=hidden name=chkordercode>
									<TR>
										<TD background="images/table_top_line.gif" width="761" colspan="10"></TD>
									</TR>
									<TR height=32>
										<TD class="table_cell5" align="center"><input type=checkbox id=allcheck name=allcheck onclick="selectAll();"></TD>
										<TD class="table_cell6" align="center">��ȣ</TD>
										<TD class="table_cell6" align="center">�ԱݿϷ���</TD>
										<TD class="table_cell6" align="center">���¹�ȣ</TD>
										<TD class="table_cell6" align="center">�����</TD>
										<TD class="table_cell6" align="center">�Աݱݾ�</TD>
										<TD class="table_cell6" align="center">�Ա��ڸ�</TD>
										<TD class="table_cell6" align="center">�������</TD>
										<TD class="table_cell6" align="center">���� ��Ī��</TD>
										<TD class="table_cell6" align="center">�ֹ���ȣ</TD>
									</TR>
									<TR>
										<TD colspan="10" background="images/table_con_line.gif"></TD>
									</TR>
									<?
									$linkstr = "javascript:GoPage('%u','".$_REQUEST['list_num']."')";
									$pageSet = array('page'=>$listinfo['page'],'total_page'=>$listinfo['totalpage'],'links'=>$linkstr,'pageblocks'=>$listinfo['list_num'],'style_pages'=>'%u', // �Ϲ� ������ 
										'style_page_sep'=>'&nbsp;.&nbsp;');
									
									$Opage = new pages($pageSet);
									$Opage->_solv();
									
									if($listinfo['total'] < 1){ 
									?>
									<tr>
										<td colspan="10" style="padding:10; text-align:center">�Ա� Ȯ�� ������ �����ϴ�.</td>
									</tr>
									<? }else{
											$vno = $listinfo['total'] - ($listinfo['page']-1)*$listinfo['list_num'];
											foreach($listinfo['items'] as $item) {											
												$ordercode = !_empty($item['ordercode']) ? "<a href=\"javascript:OrderDetailView('$item[ordercode]')\"><font color=blue>".$item['ordercode']."</font></a>": "-";
									?>
									<TR height=32>
										<TD class="td_con1b" align="center"><input type="checkbox" name="Bkid[]" value="<?=$item['Bkid']?>"></TD>
										<TD class="td_con1" align="center"><?=$vno--?></TD>
										<TD class="td_con1" align="center"><?=$item['Bkdate']?></TD>
										<TD class="td_con1" align="center"><?=$item['Bkacctno']?></TD>
										<TD class="td_con1" align="center"><?=$item['Bkname']?></TD>
										<TD class="td_con1" align="center"><?=number_format($item['Bkinput'])?></TD>
										<TD class="td_con1" align="center"><?=$item['Bkjukyo']?></TD>
										<TD class="td_con1" align="center"><?=$bankda->_statusTxt($item['status'])?></TD>
										<TD class="td_con1" align="center"><?=$item['matchdate']?></TD>
										<TD class="td_con1" align="center"><?=$ordercode?></TD>
									</TR>
									<!-- <tr height=28 bgcolor=#FFFFFF><td colspan=10 align=center>��ȸ�� ������ �����ϴ�.</td></tr> -->
									<TR>
										<TD background="images/table_top_line.gif" colspan="10"></TD>
									</TR>
									<?
											}
									} ?>
								</TABLE>
								<div style="text-align:center; margin-top:10px"><?=$Opage->_result('fulltext')?></div>
							</td>
						</tr>
						<tr>
							<td style="padding-top:4pt;"><a href="javascript:CheckDelete();"><img src="bankda/images/botteon_delete.gif" border="0"></a></td>
						</tr>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="40"></td>
	</tr>
	<tr>
		<td>
		<? /*
			<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/manual_top1.gif" WIDTH=15 HEIGHT=45 ALT=""></TD>
					<TD><IMG SRC="images/manual_title.gif" WIDTH=113 HEIGHT=45 ALT=""></TD>
					<TD width="100%" background="images/manual_bg.gif"></TD>
					<TD background="images/manual_bg.gif"></TD>
					<TD><IMG SRC="images/manual_top2.gif" WIDTH=18 HEIGHT=45 ALT=""></TD>
				</TR>
				<TR>
					<TD background="images/manual_left1.gif"></TD>
					<TD COLSPAN=3 width="100%" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;">
						<table cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
								<td><span class="font_dotline">10�� �ֱ�� �ڵ� �Ա�Ȯ���մϴ�.</span></td>
							</tr>
							<tr>
								<td width="20" align="right">&nbsp;</td>
								<td width="701" class="space_top">���� �Ա�Ȯ���� ��� 14�� ���� �����͸� �˻��ؼ� ��Ī�մϴ�.</td>
							</tr>
							<tr>
								<td width="20" align="right">&nbsp;</td>
								<td width="701" class="space_top"><!-- - �������� ��Ͽ� ǥ��� ����鸸 ��ϰ��� �մϴ�.--></td>
							</tr>
						</table>
					</TD>
					<TD background="images/manual_right1.gif"></TD>
				</TR>
				<TR>
					<TD><IMG SRC="images/manual_left2.gif" WIDTH=15 HEIGHT=8 ALT=""></TD>
					<TD COLSPAN=3 background="images/manual_down.gif"></TD>
					<TD><IMG SRC="images/manual_right2.gif" WIDTH=18 HEIGHT=8 ALT=""></TD>
				</TR>
			</TABLE>*/ ?>
		</td>
	</tr>
	<tr>
		<td height="50"></td>
	</tr>
</table>
</form>
<form name=detailform method="post" action="order_detail.php" target="orderdetail">
<input type="hidden" name="ordercode" value="" />
</form> 
<script type="text/javascript">
var chkbox = document.getElementsByName("Bkid[]");
function selectAll(){
	if(document.getElementById("allcheck").checked == true) {
		for(i = 0 ; i < chkbox.length ; i++)
		{ 
			chkbox[i].checked = true;
		}
	}else{
		cancelAll();
	}
}
function cancelAll(){
	for(i = 0 ; i < chkbox.length ; i++)
	{ 
		chkbox[i].checked = false;
	}
}
</script>
<?=$onload?>