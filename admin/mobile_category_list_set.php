<?
	$Dir="../";
	include_once($Dir."lib/init.php");
	include_once($Dir."lib/lib.php");
	include ("access.php");

	####################### ������ ���ٱ��� check ###############
	$PageCode = "mo-1";
	$MenuCode = "mobile";
	if (!$_usersession->isAllowedTask($PageCode)) {

		include ("AccessDeny.inc.php");
		exit;
	}
	#########################################################

	$mode = isset($_GET['mode'])? trim($_GET['mode']):"";
	$codeA = isset($_GET['codeA'])? trim($_GET['codeA']):"";
	$codeB = isset($_GET['codeB'])? trim($_GET['codeB']):"";
	$codeC = isset($_GET['codeC'])? trim($_GET['codeC']):"";
	$codeC = isset($_GET['codeD'])? trim($_GET['codeD']):"";

	$categorycode = $codeA.$codeB.$codeC.$codeD;

	if(strlen($categorycode) == 12){
		if($mode=="show") {
			$prupdateSQL = "UPDATE tblproduct SET mobile_display = 'Y' WHERE display = 'Y' AND productcode LIKE '".$categorycode."%' ";
			if(mysql_query($prupdateSQL, get_db_conn())){
				echo '<script>alert("���� ������ ��ǰ �������� ����ϼ��� ��ǰ�� ���� ���� �Ǿ����ϴ�.");</script>';
			}else{
				echo '<script>alert("������ ó������ �ʾҽ��ϴ�.\n����� �ٽ� �õ��� �ֽñ� �ٶ��ϴ�.");</script>';
			}
		} else if($mode=="hidden") {
			$prupdateSQL = "UPDATE tblproduct SET mobile_display = 'N' WHERE productcode LIKE '".$categorycode."%' ";
			if(mysql_query($prupdateSQL, get_db_conn())){
				echo '<script>alert("���� ������ ��ǰ �������� ����ϼ��� ��ǰ�� ���� ���� �Ǿ����ϴ�.");</script>';
			}else{
				echo '<script>alert("������ ó������ �ʾҽ��ϴ�.\n����� �ٽ� �õ��� �ֽñ� �ٶ��ϴ�.");</script>';
			}
		}
	}
	

	$allmode = isset($_POST['allmode'])? trim($_POST['allmode']):"";
	
	if(strlen($allmode) > 0){
		$allmsg="";
		switch($allmode){
			case "view":
				$allupdateSQL = "UPDATE tblproduct SET mobile_display = 'Y' WHERE display = 'Y' ";
				$allmsg = "���̱�";
			break;
			case "hidden":
				$allupdateSQL = "UPDATE tblproduct SET mobile_display = 'N' WHERE mobile_display = 'Y' ";
				$allmsg = "�����";
			break;

		}

		if(mysql_query($allupdateSQL, get_db_conn())){
			echo '<script>alert("��ϵ� ��� ��ǰ�� ����ϼ��� '.$allmsg.' ���� �Ǿ����ϴ�.");</script>';
		}else{
			echo '<script>alert("������ ó������ �ʾҽ��ϴ�.\n����� �ٽ� �õ��� �ֽñ� �ٶ��ϴ�.");</script>';
		}
	}

	$result = mysql_query("select use_same_product_code from tblmobileconfig");
	$row = mysql_fetch_array($result);
?>

<? include "header.php"; ?>
<script type="text/javascript" src="lib.js.php"></script>
<script type="text/javascript" src="codeinit.js.php"></script>
<script>
	function setShow(s1,s2,s3,s4) {
		location.href="<?=$_SERVER[PHP_SELF]?>?mode=show&codeA="+s1+"&codeB="+s2+"&codeC="+s3+"&codeD="+s4;
	}

	function setHidden(s1,s2,s3,s4){
		location.href="<?=$_SERVER[PHP_SELF]?>?mode=hidden&codeA="+s1+"&codeB="+s2+"&codeC="+s3+"&codeD="+s4;
	}
	function changePrView(mode){
		var _form = document.form3;
		
		_form.allmode.value= mode;
		_form.submit();
	}
</script>
<style type=text/css>
	#menuBar {}
	#contentDiv {width: 220;height: 320;}
</style>

<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
	<tr>
		<td valign="top">
			<table cellpadding="0" cellspacing="0" width=100% style="table-layout:fixed">
				<tr>
					<td>
						<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed"  background="images/con_bg.gif">
							<col width=198></col>
							<col width=10></col>
							<col width=></col>
							<tr>
								<td valign="top"  background="images/leftmenu_bg.gif">
									<? include ("menu_mobile.php"); ?>
								</td>
								<td></td>
								<td valign="top">
									<table cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td height="29" colspan="3">
												<table cellpadding="0" cellspacing="0" width="100%">
													<tr>
														<td height="28" class="link" align="left" background="images/con_link_bg.gif">
															<img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ����ϼ� &gt; <span class="2depth_select">ī�װ��� ��ǰ���� ����</span>
														</td>
													</tr>
												</table>
											</td>
										</tr>
										<tr>
											<td width="16"><img src="images/con_t_01.gif" width="16" height="16" border="0"></td>
											<td background="images/con_t_01_bg.gif"></td>
											<td width="16"><img src="images/con_t_02.gif" width="16" height="16" border="0"></td>
										</tr>
										<tr>
											<td width="16" background="images/con_t_04_bg1.gif"></td>
											<td bgcolor="#ffffff" style="padding:10px">
												<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
													<tr>
														<td height="8"></td>
													</tr>
													<tr>
														<td>
															<table width="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
																<tr>
																	<td><img src="images/mobile_category_list_set.gif" alt="ī�װ��� ��ǰ ����"></td>
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
														<td style="padding-bottom:3pt;">
															<table width="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
																<tr>
																	<td><img src="images/distribute_01.gif"></td>
																	<td COLSPAN=2 background="images/distribute_02.gif"></td>
																	<td><img src="images/distribute_03.gif"></td>
																</tr>
																<tr>
																	<td background="images/distribute_04.gif"></td>
																	<td class="notice_blue"><img src="images/distribute_img.gif" ></td>
																	<td width="100%" class="notice_blue">����� ����Ʈ�� �����Ǵ� ��ǰ�� ī�װ� ���� �����Ͻ� �� �ֽ��ϴ�.</td>
																	<td background="images/distribute_07.gif"></td>
																</tr>
																<tr>
																	<td><img src="images/distribute_08.gif"></td>
																	<td COLSPAN=2 background="images/distribute_09.gif"></td>
																	<td><img src="images/distribute_10.gif"></td>
																</tr>
															</table>
														</td>
													</tr>
													<tr>
														<td height="20"></td>
													</tr>
													<tr>
														<td>
															<table cellpadding="0" cellspacing="0" border="0" width="100%" >
																<tr>
																	<td valign="top">
																		<form name=form3 action="<?=$_SERVER['PHP_SELF']?>" method="post" style="padding-top:10px">
																			<table cellSpacing=0 cellPadding=0 width="100%" border=0>
																				<tr>
																					<td background="images/table_top_line.gif" colspan=2></td>
																				</tr>
																				<tr>
																					<td class="table_cell" width="170">
																						<img src="images/icon_point2.gif" width="8" height="11" border="0">����ϼ� ��ǰ���� ����<?=$row[use_same_product_code]?>
																					</td>
																					<td class="td_con1" >
																						<table  border="0" cellpadding="0" cellspacing="0">
																							<tr>
																								<td class="td_con1b">
																									<a href="javascript:changePrView('view');">��� ���̱�</a>
																									<a href="javascript:changePrView('hidden');">��� �����</a>
																								</td>
																							</tr>
																						</table>
																					</td>
																				</tr>
																				<tr>
																					<td background="images/table_top_line.gif" colspan=2></td>
																				</tr>
																			</table>
																			<input type="hidden" name="allmode" value="">
																		</form>
																		
																	</td>
																</tr>
																<?if($row['use_same_product_code'] == "N" ){?>
																<tr>
																	<td>
																		<table cellpadding="0" cellspacing="0" width="100%" border="0" >
																			<tr>
																				<td width="100%" height="100%" valign="top" background="images/category_boxbg.gif">
																					<table cellpadding="0" cellspacing="0" border=0 width="100%" height="100%">
																						<tr>
																							<td width="100%"  align=center valign=top height="100%" bgcolor=FFFFFF>
																								<table cellSpacing=0 cellPadding=0 width="100%" border=0>
																									<!-- <tr>
																										<td background="images/table_top_line.gif"  colspan="4"></td>
																									</tr> -->
																									<tr>
																										<td class="table_cell" align="center">ī�װ�</td>
																										<td class="table_cell1" align="center" >������ ī�װ� ���� ����</td>
																										<td class="table_cell1" align="center">����� ī�װ� ���� ����</td>
																										<td class="table_cell1" align="center">��ǰ ���� ����</td>
																									</tr>
																									<tr>
																										<td colspan="4"  background="images/table_con_line.gif"></td>
																									</tr>
																										<?
																											$query = "SELECT * FROM tblproductcode WHERE substr(type,1,1) != 'X' AND substr(type,1,1) != 'S' ORDER BY codeA ASC ";
																											$result = mysql_query($query);
																												while($row = mysql_fetch_array($result)){
																													$_tab = 0;
																													if($row[codeB]!="000") {	$_tab++; }
																													if($row[codeB]!="000") {	$_tab++; }
																													if($row[codeC]!="000") {	$_tab++; }
																													if($row[codeD]!="000") {	$_tab++; }
																													$_tab = $_tab * 8;
																													
																													$folder_image="";
																													$categorytype = trim(substr($row[type],0,1));
																													$ctype=substr($row[type],-1);
																													switch($categorytype){
																														case "L":
																															$folder_image = "directory_folder1.gif";
																															if($ctype=="X") {
																																$folder_image = "directory_folder3.gif";
																															}
																														break;
																														case "T":
																															$folder_image = "directory_folder2T.gif";
																															if($ctype=="X") {
																																$folder_image = "directory_folder3T.gif";
																															}
																														break;
																													}

																										?>
																									<tr>
																										<td colspan="4"  background="images/table_con_line.gif"></td>
																									</tr>
																									<tr align="center" height="33">
																										<td style="text-align:left;padding-left:<?=$_tab?>px">
																											<img src="../admin/images/<?=$folder_image?>">
																											<?=$row[code_name]?>
																										</td>
																										<td>
																											<?=$row[group_code]?>
																											<? if($row[group_code]=="N") { echo "�ƴϿ�"; }  else { echo "���̱�";}	;?>
																										</td>
																										<td>
																											<?
																													$query_m = "SELECT * FROM tblproductcode where codeA = $row[codeA] and codeB = $row[codeB] and codeC = $row[codeC] and codeD = $row[codeD]";
																													$result_m = mysql_query($query_m);
																													if(mysql_num_rows($result_m)){
																														$row_m = mysql_fetch_array($result_m);
																														echo $row_m[mobile_display];
																													}else {
																													echo "-";
																													}

																											?>
																										</td>
																										<td>
																											<input type="button" value="���̱�" onClick="setShow('<?=$row[codeA]?>','<?=$row[codeB]?>','<?=$row[codeC]?>','<?=$row[codeD]?>')">
																											<input type="button" value="�����" onClick="setHidden('<?=$row[codeA]?>','<?=$row[codeB]?>','<?=$row[codeC]?>','<?=$row[codeD]?>')">
																										</td>
																									</tr>
																											<?
																												}
																											?>
																									<tr>
																										<td colspan="4"  background="images/table_con_line.gif"></td>
																									</tr>
																								</table>
																							</td>
																						</tr>
																					</table>
																				</td>
																			</tr>
																		</table>
																	</td>
																</tr>
																<?}?>
															</table>
														</td>
													</tr>
													<tr>
														<td height=20></td>
													</tr>
													<tr>
														<td>
															<table width="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
																<tr>
																	<td><img src="images/manual_top1.gif" width=15 height=45 alt=""></td>
																	<td><img src="images/manual_title.gif" width=113 height=45 alt=""></td>
																	<td width="100%" background="images/manual_bg.gif"></td>
																	<td background="images/manual_bg.gif"></td>
																	<td><img src="images/manual_top2.gif" width=18 height=45 alt=""></td>
																</tr>
																<tr>
																	<td background="images/manual_left1.gif"></td>
																	<td COLSPAN=3 width="100%" valign="top" class="menual_bg" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;">
																		<table cellpadding="0" cellspacing="0" width="100%">
																			<col width=20></col>
																			<col width=></col>
																			<tr>
																				<td align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
																				<td><span class="font_dotline">ī�װ� ���� ����(������)</span></td>
																			</tr>
																			<tr>
																				<td align="right">&nbsp;</td>
																				<td class="space_top" style="letter-spacing:-0.5pt;">
																				- "�Ҽ� �� ��������" ī�װ��� ��� ����ϼ����� �������� �ʽ��ϴ�.
																				- "ī�װ� ������ ��Ŭ������ ���� �Ͻ� �� �ֽ��ϴ�.<br/>
																				
																				
																				</td>
																			</tr>
																		</table>
																	</td>
																	<td background="images/manual_right1.gif"></td>
																</tr>
																<tr>
																	<td><img src="images/manual_left2.gif" width=15 height=8 alt=""></td>
																	<td COLSPAN=3 background="images/manual_down.gif"></td>
																	<td><img src="images/manual_right2.gif" width=18 height=8 alt=""></td>
																</tr>
															</table>
														</td>
													</tr>
													<tr>
														<td height="50"></td>
													</tr>
												</table>
											</td>
											<td width="16" background="images/con_t_02_bg.gif"></td>
										</tr>
										<tr>
											<td width="16"><img src="images/con_t_04.gif" width="16" height="16" border="0"></td>
											<td background="images/con_t_04_bg.gif"></td>
											<td width="16"><img src="images/con_t_03.gif" width="16" height="16" border="0"></td>
										</tr>
										<tr>
											<td height="20"></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<iframe name="ifrm_ctrl" id="ifrm_ctrl" style="display:none;"></iframe>
<?=$onload?>
<? include "copyright.php"; ?>