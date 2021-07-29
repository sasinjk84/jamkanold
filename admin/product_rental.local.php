<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include ("access.php");
####################### 페이지 접근권한 check ###############
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

	// 벤더 정보 리스트
	$venderList = venderList("vender,id,com_name");

	// 대여 출고지 정보 리스트
	//$value = array("vender"=>1);
	$localList = rentLocalList( $value );
	//_pr($localList);

	$type = 'A';
	$display = 0;
	extract($_POST);
	// 지역 저장
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
	//지역삭제
	if( $saveType == "delete" ) {
		$SQL = "DELETE FROM `rent_location` WHERE `location` = '".$localList[$localKey][location]."' ";
		mysql_query($SQL,get_db_conn());
		echo "<script> location.href = 'product_rental.local.php".(isset($_REQUEST['ispop'])?'?ispop':'')."'; </script>";
	}

	//지역수정 저장
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

	//지역 수정
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
			// 대여 출고지 등록
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

			// 수정
			function localUpdate ( k ) {
				rentLocalInput.saveType.value='updateView';
				rentLocalInput.localKey.value = k;
				rentLocalInput.method = "POST";
				rentLocalInput.submit();
			}

			// 저장
			function localSave(f) {
				f.method = "POST";
				f.submit();
			}

			// 다른이름으로 저장
			function localSaveAs ( f ){
				f.saveType.value = "insert";
				f.localKey.value = "";
				localSave(f);
			}

			//삭제
			function localDelete ( k ) {
				if ( confirm("정말 삭제하시겠습니까?") ) {
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
												<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">현재위치 : 상품관리 &gt;예약/대여 관리 &gt; <span class="2depth_select">대여 출고지 관리</span></td>
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
														<TD><IMG SRC="images/product_rental_title.gif" ALT="대여 출고지 관리"></TD>
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
														<TD width="100%" class="notice_blue">대여상품의 출고지 및 장소렌탈 정보를 관리할 수 있습니다.</TD>
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
												<!-- 등록 -->
												<form name="rentLocalInput" id="rentLocalForm">
													<? if($ispop){ ?>
														<input type="hidden" name="ispop" />
													<? } ?>
													<table border="0" cellspacing="0" cellpadding="0" width="100%" style="display: none;" id="rentLocalInputTable" class="tableBaseTh">
														<caption style="display:block;padding-bottom:6px;text-align:left;"><img src="images/product_rentalrelease_stitle2.gif" alt="" /></caption>														
														<tr>
															<th style="width:160px;">노출여부</th>
															<td>
																<?
																  $displaySel[$display] = "checked";
																?>
																<input type="radio" name="display" value='0' <?=$displaySel[0]?>>노출안함
																<input type="radio" name="display" value='1' <?=$displaySel[1]?>>노출
																</select>
															</td>
															<th style="width:160px;">타입</th>
															<td>
																<?
																  if(_empty($type)) $type = 'A';
																  $typeSel[$type] = "checked";																
																?>
																<input type="radio" name="type" value='A' <?=$typeSel['A']?>>출고지
																<input type="radio" name="type" value='B' <?=$typeSel['B']?>>장소 렌탈
																</select>
															</td>
														</tr>
														<tr>
															<th>소유 입점사</th>
															<td colspan="3">
																<select name="vender" style="width:170px;">
																	<option value='0'>본사</option>
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
															<th>명칭</th>
															<td colspan="3"><input type="text" name="local" value="<?=$local?>" class="input" style="width:170px;" /></td>
														</tr>
														<? /*
														<tr>
															<th><img src="images/icon_point2.gif" border="0" alt="" />지도</th>
															<td>
																Y : <input type="text" name="ypos" value="<?=$ypos?>" class="input" />
																X : <input type="text" name="xpos" value="<?=$xpos?>" class="input" />
															</td>
														</tr>
														*/ ?>
														<tr>
															<th>주소</th>
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
																<!--<input type="button" onclick="localSaveAs(this.form);" value="다른이름으로저장">-->
																<input type="image" src="images/mobile_product_list_btn.gif" onclick="localSaveAs(this.form);" />
																<?
																	}
																?>
																<!--<input type="button" onclick="localInputview('none');" value="닫기" />-->
																<img src="images/btn_cancle.gif" alt="취소" onclick="localInputview('none');" />
															</td>
														</tr>
													</table>
													<input type="hidden" name="saveType" value="">
													<input type="hidden" name="localKey" value="">													
												</form>
												<div style="text-align:right;" id="btnInput"><img src="images/botteon_insert.gif" onclick="localInputview('block');"  /></div>
												<!-- <button onclick="localInputview('block');" id="btnInput">등록하기</button>-->
												
												
												
												<!-- 리스트 --->
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
														<th class="firstTh">지역코드</th>
														<th>노출</th>
														<th>타입</td>
														<th>소유 입점사</th>
														<th>지명</th>
													<!--	<th>지도</th> -->
														<th>주소</th>
														<th>관리</th>
													</tr>
													<?
														foreach ( $localList as $k=>$v ) {
													?>
													<tr align="center">
														<td class="firstTd"><?=$v['location']?></td>
														<td><?=($v['display']?"노출":"노출안함")?></td>
														<td><?=$rentLocationType[$v['type']]?></td>
														<td><?=($v['vender']>0 ? $venderList[$v['vender']]['com_name'] : "본사"); ?></td>
														<td><?=$v['title']?></td>
														<!-- <td><?=$v['ypos']?> * <?=$v['xpos']?></td> -->
														<td align="left" style="padding-left:10px;">(<?=$v['zip']?>) <?=$v['address']?></td>
														<td>
															<a href="javascript:localUpdate('<?=$k?>');"><img src="images/btn_edit.gif" border="0" border="수정" /></a>
															<a href="javascript:localDelete('<?=$k?>');"><img src="images/btn_del.gif" border="0" alt="삭제" /></a>
															<!--
															<input type="button" onclick="localDelete('<?=$k?>');" value="삭제">
															<input type="button" onclick="localUpdate('<?=$k?>');" value="수정">
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
																	<td ><span class="font_dotline">설명</span></td>
																</tr>
																<tr>
																	<td width="20" align="right">&nbsp;</td>
																	<td  class="space_top">
																		- 렌탈장소 및 출고지 추가시 하단의 [등록하기] 메뉴를 통해서 추가가 가능합니다.<br />
																		- 등록된 내용 수정은 우측의 [수정] 메뉴를 통해서 수정가능합니다.<br />
																		- 렌탈장소 및 출고지를 삭제하지 않더라도 노출여부를 [노출안함] 설정시 해당 항목은 출력되지 않습니다.
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
