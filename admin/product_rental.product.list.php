<?php
/**
 * Created by PhpStorm.
 * User: x2chi-objet
 * Date: 2014-10-15
 * Time: ���� 5:24
 */

$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/class/pages.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "pr-1";
$MenuCode = "product";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

extract($_GET);
extract($_POST);


// ���� ���� ����Ʈ
$venderList = venderList("vender,id,com_name");

// �뿩 ����� ���� ����Ʈ
$value = array("display"=>1, "type"=>"A");
$localList = rentLocalList( $value );


// �˻��� �ʱ�ȭ
$bookingStartDate = ( strlen($bookingStartDate) > 0 ? $bookingStartDate : date("Ymd") );
$bookingEndDate = ( strlen($bookingEndDate) > 0 ? $bookingEndDate : date("Ymd") );
?>

<? INCLUDE "header.php"; ?>
	<script type="text/javascript" src="lib.js.php"></script>
	<script type="text/javascript" src="codeinit.js.php"></script>
	<script type="text/javascript" src="/js/jquery-1.10.2.min.js"></script>
	<script language="javascript" type="text/javascript" src="/js/jquery-ui-1.10.4.custom.min.js"></script>
<link type="text/css" rel="stylesheet" href="/css/ui-lightness/jquery-ui-1.10.4.custom.min.css" />

<!-- 	<script	type="text/javascript" src="<?=$Dir?>js/miniCalendar.js"></script> -->
	<script type="text/javascript">
		<!--
		// ���콺 ����ٴϴ� ���̾�
		jQuery(document).ready(function(){
			$(document).mousemove(function(e){
				var leftP = ( ( $(document).width() - 300 ) < e.pageX ) ? $(document).width()-330 : e.pageX ;
				$('#viewInfo').css("left",leftP-20);
				$('#viewInfo').css("top",e.pageY+15);
			});
		})

		// ���̾� ����ä�� ���̱�
		function viewInfo( idx ) {
			$('#viewInfo').css("display","block");
			$('#viewInfo').html($('#bookingInfo_'+idx).html());
		}

		// ���̾� ������� ������
		function offInfo( idx ) {
			$('#viewInfo').css("display","none");
			$('#viewInfo').html("");
		}

		// �˻� ��¥ ����
		function setSearchDate ( sDate, eDate ) {
			srchForm.bookingStartDate.value = sDate;
			srchForm.bookingEndDate.value = eDate;
		}

		// �˻�
		function searchSub () {
			srchForm.method = "POST";
			srchForm.submit();
		}
		-->
	</script>

<table cellpadding="0" cellspacing="0" width="100%" valign="top" background="images/con_bg.gif">
	<colgroup>
		<col width=198>
		<col width=10>
		<col>
	</colgroup>
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
								<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ��ǰ &gt; ����/��Ż ���� &gt; <span class="2depth_select">����/��Ż �˻�</span></td>
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
						<table cellpadding="0" cellspacing="0" width="100%">
							<tr><td height="8"></td></tr>
							<tr>
								<td>
									<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
										<TR>
											<TD><IMG SRC="images/product_rentalsearch_title.gif" ALT="����/��Ż �˻�"></TD>
										</tr>
										<tr>
											<TD width="100%" background="images/title_bg.gif" height="21"></TD>
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
											<TD width="100%" class="notice_blue">��ü ��Ż��ǰ ����� �˻�, �Ǵ� Ȯ���ϰ� ������ �� �ֽ��ϴ�.</TD>
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
									<h6 style="margin:0px;padding:0px;"><img src="images/product_rentalsearch_stitle1.gif" alt="" /></h6>
									<p class="notice_blue" style="margin:7px 24px;">
										1) �˻� ������ �� ������ �����ؼ� �˻��� ���� �ֽ��ϴ�.<br />
										2) ��Ż(�뿩) ��ҵ���� ��Ż���/����� ���� �޴����� ��ϰ����մϴ�.
									</p>

									<form name="srchForm" style="margin:0px;padding:0px;">
									<input type="hidden" name="act" value="search" />
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
										<colgroup>
											<col width="139">
											<col>
										</colgrup>
										<tr><td background="images/table_top_line.gif" colspan="2"></td></tr>
										<tr>
											<td class="table_cell"><img width="8" height="11" src="images/icon_point2.gif" border="0"/>�˻��Ⱓ ����</th>
											<td class="td_con1">
											<script language="javascript" type="text/javascript">
											$(function(){												
												$("#bookingStartDate" ).datepicker({
												  dateFormat:'yy-mm-dd',
												  buttonImage: "/images/mini_cal_calen.gif",
												  buttonImageOnly: true,
												  buttonText: "�����",				  
												  onSelect: function( selectedDate ) {
													$("#bookingEndDate" ).datepicker( "option", "minDate", selectedDate );
													fixrenttime();
												  }
												});
											
												$("#bookingEndDate" ).datepicker({												  
												  dateFormat:'yy-mm-dd',
												  buttonImage: "/images/mini_cal_calen.gif",
												  buttonImageOnly: true,
												  buttonText: "�ݳ���",
												  onSelect: function( selectedDate ) {
													$( "#bookingStartDate" ).datepicker( "option", "maxDate", selectedDate );
													fixrenttime();
												  }
												});	
												});
											</script> 												
												<input type="text" name="bookingStartDate" id="bookingStartDate" value="<?=$bookingStartDate?>" style="width:80px;" readonly>
												~
												<input type="text" name="bookingEndDate" id="bookingEndDate" value="<?=$bookingEndDate?>" style="width:80px;" readonly>
												<? /*
												<span id="bookingStartDateCal" style="position:absolute;display:none;border:1px solid #d9d9d9;padding:3px;background-color: #FFFFFF;"></span>
												<input type="text" name="bookingStartDate" id="bookingStartDate" value="<?=$bookingStartDate?>" style="width:80px;" readonly>
												<img src="/images/mini_cal_calen.gif" style="cursor:pointer;" onclick="bookingStartDateCal.style.display=(bookingStartDateCal.style.display=='none' ? 'block' : 'none' );" align="absmiddle">
												~
												<span id="bookingEndDateCal" style="position:absolute;display:none;border:1px solid #d9d9d9;padding:3px;background-color: #FFFFFF;"></span>
												<input type="text" name="bookingEndDate" id="bookingEndDate" value="<?=$bookingEndDate?>" style="width:80px;" readonly>
												<img src="/images/mini_cal_calen.gif" style="cursor:pointer;" onclick="bookingEndDateCal.style.display=(bookingEndDateCal.style.display=='none' ? 'block' : 'none' );" align="absmiddle">

												<script>
													show_cal('<?=$bookingStartDate?>','bookingStartDateCal','bookingStartDate');
													show_cal('<?=$bookingEndDate?>','bookingEndDateCal','bookingEndDate');
												</script>
*/ ?>
												&nbsp;&nbsp;<input type="radio" name="searchDateSelect" id="idx_sort0" onclick="javascript:setSearchDate('<?=date("Ymd")?>','<?=date("Ymd")?>');" checked><label style="cursor: hand;" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_sort0">����</label>
												&nbsp;<input type="radio" name="searchDateSelect" id="idx_sort1" onclick="javascript:setSearchDate('<?=date("Ym")."01"?>','<?=date("Ym").date("t")?>');"><label style="cursor: hand;" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_sort1">�̹���</label>
												&nbsp;<input type="radio" name="searchDateSelect" id="idx_sort2" onclick="javascript:setSearchDate('<?=date("Y")."0101"?>','<?=date("Y"."1231")?>');"><label style="cursor: hand;" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_sort2">����</label>

												<!--
												[<a href="javascript:setSearchDate('<?=date("Ymd")?>','<?=date("Ymd")?>');">����</a>]
												[<a href="javascript:setSearchDate('<?=date("Ym")."01"?>','<?=date("Ym").date("t")?>');">�̹���</a>]
												[<a href="javascript:setSearchDate('<?=date("Y")."0101"?>','<?=date("Y"."1231")?>');">����</a>]
												-->
											</td>
										</tr>
										<tr><td background="images/table_con_line.gif" colspan="2"></td></tr>
										<tr>
											<td class="table_cell"><img width="8" height="11" src="images/icon_point2.gif" border="0"/>��Ż(�뿩) ��Һ�</th>
											<td class="td_con1">
												<select name="srchLocal" style="width:150px;">
													<option value="">��ü</option>
													<?
														foreach ($localList as $v ) {
															$sel = ($srchLocal == $v['location'] ? "selected":"" );
															echo "<option value='".$v['location']."' ".$sel.">".$v['title']."</option>";
														}
													?>
												</select>
											</td>
										</tr>
										<tr><td background="images/table_con_line.gif" colspan="2"></td></tr>
										<tr>
											<td class="table_cell"><img width="8" height="11" src="images/icon_point2.gif" border="0"/>��Ż(�뿩) ���º�</th>
											<td class="td_con1">
												<select name="srchStatus" style="width:150px;">
													<option value="">��ü</option>
													<?
														foreach (rentProduct::$bookingStatus as $k => $v ) {
															$sel = ($srchStatus == $k ? "selected":"" );
															if( $k != "NN" ) echo "<option value='".$k."' ".$sel.">".$v."</option>";
														}
													?>
												</select>
											</td>
										</tr>
										<tr><td background="images/table_con_line.gif" colspan="2"></td></tr>
										<tr>
											<td class="table_cell"><img width="8" height="11" src="images/icon_point2.gif" border="0"/>�뿩�� ��</th>
											<td class="td_con1">
												<input type="text" name="srchTxt" value="<?=$srchTxt?>" style="width:300px;" class="input" /> <a href="javascript:searchSub();"><img src="images/icon_search.gif" align="absmiddle" border="0" alt="" /></a>
											</td>
										</tr>
										<tr><td background="images/table_top_line.gif" colspan="2"></td></tr>
										<tr><td height="40"></td></tr>
									</table>
									</form>


									<h6 style="margin:0px;padding-bottom:7px;"><img src="images/product_rentalsearch_stitle2.gif" alt="" /></h6>
									<table border="0" cellspacing="0" cellpadding="0" width="100%" class="tableBase">
										<tr>
											<th class="firstTh">��ǰ��</th>
											<th>�ɼ�</th>
											<th>�뿩 �Ⱓ</th>
											<th>�뿩�ڸ�</th>
											<th>����ó</th>
											<th>�ݾ�</th>
											<th>����</th>
											<th>�����</th>
										</tr>
										<?
								
										$param = array();
										
										if(!_empty($bookingStartDate)) $param['start'] = $bookingStartDate;
										if(!_empty($bookingEndDate)) $param['end'] = $bookingEndDate;
										if(!_empty($_REQUEST['srchLocal'])) $param['location'] = $_REQUEST['srchLocal'];
										if(!_empty($_REQUEST['srchStatus'])) $param['status'] = $_REQUEST['srchStatus'];
										if(!_empty($_REQUEST['srchTxt'])) $param['receiver_name'] = $_REQUEST['srchTxt'];
										
										$param['orderby'] = 'desc';

										$bookingProducts = rentProduct::searchOrder($param);
										if(!_empty($bookingProducts['err'])){
									?>
										<tr>
											<td colspan="8" style="text-align:center">	<?=$bookingProducts['err']?></td>
										</tr>
									<?	}else if(count($bookingProducts) < 1){ ?>
										<tr>
											<td colspan="8" style="text-align:center"> ��ϵ� ������� �����ϴ�.</td>
										</tr>
									<?	}else{										
											foreach ( $bookingProducts as $k=>$v ){// �ɼ�
											// ȸ�����̵�
											$memId = ( strlen($v['id']) > 0 ? " (".$v['id'].")" : "" );

										?>
										<tr align="center">
											<td class="firstTd"><a href="javascript:bookingSchedulePop('<?=$v['pridx']?>');"><?=$v['productname']?></a></td>
											<td><?=$v['optionName']?></td>
											<td><?=substr($v['start'],0,-3)?>~<?=substr($v['end'],0,-3)?></td>
											<td><?=$v['receiver_name'].$memId?></td>
											<td><?=$v['receiver_tel1']?><br><?=$v['receiver_email']?></td>
											<td><?=number_format($v['price'])?>��</td>
											<td><?=rentProduct::_bookingStatus($v['status'])?></td>
											<td><?=$v['regDate']?></td>
										</tr>
										<?
											}
									}
										?>
									</table>


								</td>
							</tr>


							<tr><td height="50"></td></tr>
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
														<td width="20" align="right"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
														<td ><span class="font_dotline">���ȳ�</span></td>
													</tr>
													<tr>
														<td width="20" align="right">&nbsp;</td>
														<td  class="space_top">
															- �˻��Ⱓ ���ÿ��� �޷� ������ Ŭ���� ���ڸ� ������ �� �ֽ��ϴ�.<br />
															- �˻�������� ��ǰ�� Ŭ���� �ش� ��ǰ�� ���� �� ��Ż ��Ȳ�� Ȯ���Ͻ� �� �ֽ��ϴ�.
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



<?
include "copyright.php";
?>