<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include ("access.php");
####################### ������ ���ٱ��� check ###############
	$PageCode = "pr-1";
	$MenuCode = "product";
	if (!$_usersession->isAllowedTask($PageCode)) {
		INCLUDE ("AccessDeny.inc.php");
		exit;
	}
#########################################################

	$prcode=$_POST["prcode"];
	if(strlen($prcode)==18) {
		$code=substr($prcode,0,12);
		$codeA=substr($code,0,3);
		$codeB=substr($code,3,3);
		$codeC=substr($code,6,3);
		$codeD=substr($code,9,3);
	}

	// ���� ���� ����Ʈ
	$venderList = venderList("vender,id,com_name");

	// �뿩 ����� ���� ����Ʈ
	//$value = array("vender"=>1);
	$localList = rentLocalList( $value );
	//_pr($localList);

	$type = 'A';
	$display = 0;
	extract($_POST);
	// ���� ����
	if( $saveType == "insert" ) {
		$SQL = "
			INSERT `rent_location` SET
			`vender` = '".$vender."',
			`type` = '".$type."',
			`title` = '".$local."',
			`display` = '".$display."',
			`xpos` = '".$xpos."',
			`ypos` = '".$ypos."',
			`address` = '".$addr."',
			`zip` = '".($zip1."-".$zip2)."'
		";
		mysql_query($SQL,get_db_conn());
		echo "<script> location.href = 'product_rental.local.php'; </script>";
	}
	//��������
	if( $saveType == "delete" ) {
		$SQL = "DELETE FROM `rent_location` WHERE `location` = '".$localList[$localKey][location]."' ";
		mysql_query($SQL,get_db_conn());
		echo "<script> location.href = 'product_rental.local.php".(isset($_REQUEST['ispop'])?'?ispop':'')."'; </script>";
	}

	//�������� ����
	if( $saveType == "update" ) {
		$SQL = "
			UPDATE `rent_location` SET
			`vender` = '".$vender."',
			`type` = '".$type."',
			`title` = '".$local."',
			`display` = '".$display."',
			`xpos` = '".$xpos."',
			`ypos` = '".$ypos."',
			`address` = '".$addr."',
			`zip` = '".($zip1."-".$zip2)."'
			WHERE
			`location` = '".$localList[$localKey][location]."'
		";
		mysql_query($SQL,get_db_conn());
		echo "<script> location.href = 'product_rental.local.php".(isset($_REQUEST['ispop'])?'?ispop':'')."'; </script>";
	}

	//���� ����
	if( $saveType == "updateView" ) {
		$venderSel = $localList[$localKey][vender];
        $type =  $localList[$localKey][type];
		$local = $localList[$localKey][title];
        $display =  $localList[$localKey][display];
		$ypos = $localList[$localKey][ypos];
		$xpos = $localList[$localKey][xpos];
		$addr = $localList[$localKey][address];
		$zip = explode("-",$localList[$localKey][zip]);
		$zip1 = $zip[0];
		$zip2 = $zip[1];
	}

$ispop = isset($_REQUEST['ispop']); //false;
?>

<? include "header.php"; ?>
	<script type="text/javascript">
		<!--
			// �뿩 ����� ���
			function localInputview(op) {
				btnInput.style.display='none';
				rentLocalInput.reset();
				rentLocalInputTable.style.display = op;
				if ( op == "none" ) {
					btnInput.style.display='block';
					
				}else{
					rentLocalInput.saveType.value = "insert" ;
					//rentLocalInput.btnValue.src= "images/botteon_save.gif" ;
				}			
			}

			// ����
			function localUpdate ( k ) {
				rentLocalInput.saveType.value='updateView';
				rentLocalInput.localKey.value = k;
				rentLocalInput.method = "POST";
				rentLocalInput.submit();
			}

			// ����
			function localSave(f) {
				f.method = "POST";
				f.submit();
			}

			// �ٸ��̸����� ����
			function localSaveAs ( f ){
				f.saveType.value = "insert";
				f.localKey.value = "";
				localSave(f);
			}

			//����
			function localDelete ( k ) {
				if ( confirm("���� �����Ͻðڽ��ϱ�?") ) {
					rentLocalInput.saveType.value = "delete" ;
					rentLocalInput.localKey.value = k;
					rentLocalInput.method = "POST";
					rentLocalInput.submit();
				}
			}
		-->
	</script>
	<script type="text/javascript" src="lib.js.php"></script>
<?
	if(!$ispop){ ?>
	<script type="text/javascript" src="codeinit.js.php"></script>
	<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
		<td valign="top">
			<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
				<td>

					<table cellpadding="0" cellspacing="0" width="100%"  background="images/con_bg.gif">
						<col width="198">
						<col width="10">
						<col>
						<tr>
						<td valign="top"  background="images/leftmenu_bg.gif">
							<? include ("menu_product.php"); ?>
						</td>
						<td></td>
						<td valign="top">
							<table cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td height="29" colspan="3">
										<table cellpadding="0" cellspacing="0" width="100%">
											<tr>
												<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ��ǰ���� &gt;����/�뿩 ���� &gt; <span class="2depth_select">�뿩 ����� ����</span></td>
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
<? } ?>		
									<table cellpadding="0" cellspacing="0" width="100%">
										<tr><td height="8"></td></tr>
										<tr>
											<td>
												<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
													<TR>
														<TD><IMG SRC="images/product_rental_title.gif" ALT="�뿩 ����� ����"></TD>
													</tr>
													<tr>
														<TD width="100%" background="images/title_bg.gif" height=21></TD>
													</TR>
												</TABLE>
											</td>
										</tr>
										<tr><td height="3"></td></tr>
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
														<TD width="100%" class="notice_blue">�뿩��ǰ�� ����� �� ��ҷ�Ż ������ ������ �� �ֽ��ϴ�.</TD>
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
										<tr><td height="15"></td></tr>
										<tr>
											<td>
												<!-- ��� -->
												<form name="rentLocalInput" id="rentLocalForm">
													<? if($ispop){ ?>
														<input type="hidden" name="ispop" />
													<? } ?>
													<table border="0" cellspacing="0" cellpadding="0" width="100%" style="display: none;" id="rentLocalInputTable" class="tableBaseTh">
														<caption style="display:block;padding-bottom:6px;text-align:left;"><img src="images/product_rentalrelease_stitle2.gif" alt="" /></caption>														
														<tr>
															<th style="width:160px;">���⿩��</th>
															<td>
																<?
																  $displaySel[$display] = "checked";
																?>
																<input type="radio" name="display" value='0' <?=$displaySel[0]?>>�������
																<input type="radio" name="display" value='1' <?=$displaySel[1]?>>����
																</select>
															</td>
															<th style="width:160px;">Ÿ��</th>
															<td>
																<?
																  if(_empty($type)) $type = 'A';
																  $typeSel[$type] = "checked";																
																?>
																<input type="radio" name="type" value='A' <?=$typeSel['A']?>>�����
																<input type="radio" name="type" value='B' <?=$typeSel['B']?>>��� ��Ż
																</select>
															</td>
														</tr>
														<tr>
															<th>���� ������</th>
															<td colspan="3">
																<select name="vender" style="width:170px;">
																	<option value='0'>����</option>
																	<?
																		foreach($venderList as $k => $v ){
																			$sel = ($k==$venderSel ? "selected" : "" );
																			echo "<option value='".$k."' ".$sel.">".$v['com_name']."</option>";
																		}
																	?>
																</select>
															</td>
														</tr>
														<tr>
															<th>��Ī</th>
															<td colspan="3"><input type="text" name="local" value="<?=$local?>" class="input" style="width:170px;" /></td>
														</tr>
														<? /*
														<tr>
															<th><img src="images/icon_point2.gif" border="0" alt="" />����</th>
															<td>
																Y : <input type="text" name="ypos" value="<?=$ypos?>" class="input" />
																X : <input type="text" name="xpos" value="<?=$xpos?>" class="input" />
															</td>
														</tr>
														*/ ?>
														<tr>
															<th>�ּ�</th>
															<td colspan="3">
																<input type="text" name="zip1" value="<?=$zip1?>" size="4" class="input" readonly /> - <input type="text" name="zip2" value="<?=$zip2?>" size="4" class="input" readonly /> <a href="#"><img src="images/icon_addr.gif" height="20" align="absmiddle" border="0" /></a><br />
																<input type="text" name="addr" value="<?=$addr?>" size="80" class="input" />
															</td>
														</tr>
														<tr>
															<td class="lastTd" colspan="4" style="padding-top:15px; text-align:right">
																<input type="image" src="images/botteon_save.gif" onclick="localSave(this.form);" id="btnValue" />

																<?
																	if( $saveType == "updateView" ) {
																?>
																<!--<input type="button" onclick="localSaveAs(this.form);" value="�ٸ��̸���������">-->
																<input type="image" src="images/mobile_product_list_btn.gif" onclick="localSaveAs(this.form);" />
																<?
																	}
																?>
																<!--<input type="button" onclick="localInputview('none');" value="�ݱ�" />-->
																<img src="images/btn_cancle.gif" alt="���" onclick="localInputview('none');" />
															</td>
														</tr>
													</table>
													<input type="hidden" name="saveType" value="">
													<input type="hidden" name="localKey" value="">													
												</form>
												<div style="text-align:right;" id="btnInput"><img src="images/botteon_insert.gif" onclick="localInputview('block');"  /></div>
												<!-- <button onclick="localInputview('block');" id="btnInput">����ϱ�</button>-->
												
												
												
												<!-- ����Ʈ --->
												<h6 style="margin:0px;padding-bottom:6px;"><img src="images/product_rentalrelease_stitle1.gif" alt="" /></h6>
												<table border="0" cellspacing="0" cellpadding="0" width="100%" class="tableBase">
													<colgroup>
														<col width="60"></col>
														<col width="60"></col>
														<col width="60"></col>
														<col width="100"></col>
													<!-- <col width="100"></col> -->														
														<col width="100"></col>
														<col width=""></col>
														<col width="120"></col>
													</colgroup>
													<tr>
														<th class="firstTh">�����ڵ�</th>
														<th>����</th>
														<th>Ÿ��</td>
														<th>���� ������</th>
														<th>����</th>
													<!--	<th>����</th> -->
														<th>�ּ�</th>
														<th>����</th>
													</tr>
													<?
														foreach ( $localList as $k=>$v ) {
													?>
													<tr align="center">
														<td class="firstTd"><?=$v['location']?></td>
														<td><?=($v['display']?"����":"�������")?></td>
														<td><?=$rentLocationType[$v['type']]?></td>
														<td><?=($v['vender']>0 ? $venderList[$v['vender']]['com_name'] : "����"); ?></td>
														<td><?=$v['title']?></td>
														<!-- <td><?=$v['ypos']?> * <?=$v['xpos']?></td> -->
														<td align="left" style="padding-left:10px;">(<?=$v['zip']?>) <?=$v['address']?></td>
														<td>
															<a href="javascript:localUpdate('<?=$k?>');"><img src="images/btn_edit.gif" border="0" border="����" /></a>
															<a href="javascript:localDelete('<?=$k?>');"><img src="images/btn_del.gif" border="0" alt="����" /></a>
															<!--
															<input type="button" onclick="localDelete('<?=$k?>');" value="����">
															<input type="button" onclick="localUpdate('<?=$k?>');" value="����">
															-->
														</td>
													</tr>
													<?
														}
													?>
													<tr><td height="20"></td></tr>
												</table>

											</td>
										</tr>
										<tr><td height="20"></td></tr>
										<tr>
											<td>
												<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
													<TR>
														<TD><IMG SRC="images/manual_top1.gif" WIDTH=15 HEIGHT=45 ALT=""></TD>
														<TD><IMG SRC="images/manual_title.gif" WIDTH=113 HEIGHT=45 ALT=""></TD>
														<TD width="100%" background="images/manual_bg.gif"></TD>
														<TD background="images/manual_bg.gif"></TD>
														<TD><IMG SRC="images/manual_top2.gif" WIDTH=18 HEIGHT=45 ALT=""></TD>
													</TR>
													<TR>
														<TD background="images/manual_left1.gif"><IMG SRC="images/manual_left1.gif" WIDTH=15 HEIGHT="5" ALT=""></TD>
														<TD COLSPAN=3 width="100%" valign="top" bgcolor="#FFFFFF" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;"  class="menual_bg">
															<table cellpadding="0" cellspacing="0" width="100%">
																<tr>
																	<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
																	<td ><span class="font_dotline">����</span></td>
																</tr>
																<tr>
																	<td width="20" align="right">&nbsp;</td>
																	<td  class="space_top">
																		- ��Ż��� �� ����� �߰��� �ϴ��� [����ϱ�] �޴��� ���ؼ� �߰��� �����մϴ�.<br />
																		- ��ϵ� ���� ������ ������ [����] �޴��� ���ؼ� ���������մϴ�.<br />
																		- ��Ż��� �� ������� �������� �ʴ��� ���⿩�θ� [�������] ������ �ش� �׸��� ��µ��� �ʽ��ϴ�.
																	</td>
																</tr>
																<tr>
																	<td colspan="2" height="20"></td>
																</tr>
															</table>
														</TD>
														<TD background="images/manual_right1.gif"><IMG SRC="images/manual_right1.gif" WIDTH=18 HEIGHT="2" ALT=""></TD>
													</TR>
													<TR>
														<TD><IMG SRC="images/manual_left2.gif" WIDTH=15 HEIGHT=8 ALT=""></TD>
														<TD COLSPAN=3 background="images/manual_down.gif"><IMG SRC="images/manual_down.gif" WIDTH="4" HEIGHT=8 ALT=""></TD>
														<TD><IMG SRC="images/manual_right2.gif" WIDTH=18 HEIGHT=8 ALT=""></TD>
													</TR>
												</TABLE>
											</td>
										</tr>
										<tr>
											<td height="50"></td>
										</tr>
									</table>
<?	if(!$ispop){ ?>
								</td>
								<td width="16" background="images/con_t_02_bg.gif"></td>
								</tr>
								<tr>
									<td width="16"><img src="images/con_t_04.gif" width="16" height="16" border="0"></td>
									<td background="images/con_t_04_bg.gif"></td>
									<td width="16"><img src="images/con_t_03.gif" width="16" height="16" border="0"></td>
								</tr>
								<tr><td height="20"></td></tr>
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
<? } ?>

<? if( $saveType == "updateView" ) { ?>
	<script>
		btnInput.style.display='none';
		rentLocalInputTable.style.display = 'block';
		//rentLocalInput.btnValue.src= 'images/botteon_save.gif' ;
		rentLocalInput.saveType.value= 'update' ;
		rentLocalInput.localKey.value= '<?=$localKey?>' ;
	</script>
<?	} 
	if(!$ispop)	include "copyright.php";
?>
