<?php
/**
 * Created by PhpStorm.
 * User: x2chi-objet
 * Date: 2014-10-07
 * Time: ���� 11:57
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
$value = array("display"=>1, "type"=>"B");
$localList = rentLocalList( $value );


// �˻��� �ʱ�ȭ
$bookingStartDate = ( strlen($bookingStartDate) > 0 ? $bookingStartDate : date("Ymd") );
$bookingEndDate = ( strlen($bookingEndDate) > 0 ? $bookingEndDate : date("Ymd") );
?>

<? INCLUDE "header.php"; ?>
<script type="text/javascript" src="lib.js.php"></script>
<script type="text/javascript" src="codeinit.js.php"></script>
<script type="text/javascript" src="/js/jquery-1.10.2.min.js"></script>
<script	type="text/javascript" src="<?=$Dir?>js/miniCalendar.js"></script>
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
<table cellpadding="0" cellspacing="0" width="100%">
<tr>
<td valign="top">
<table cellpadding="0" cellspacing="0" width=100%>
<tr>
<td>

<table cellpadding="0" cellspacing="0" width="100%"  background="images/con_bg.gif">
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
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ��ǰ���� &gt;����/�뿩 ����&gt; <span class="2depth_select">���� ����</span></td>
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
							<TD><IMG SRC="images/product_rental_title.gif" ALT="���� ����"></TD>
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
							<TD width="100%" class="notice_blue">���� ���¸� Ȯ���ϰ� �����մϴ�.</TD>
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
				<td>


					<form name="srchForm">
						<br><strong>���� ����</strong>
						<hr>
						[<a href="javascript:document.location.reload();">���ΰ�ħ</a>]
						<hr>

						<span id="bookingStartDateCal" style="position:absolute;display:none;border:1px solid #d9d9d9;padding:3px;background-color: #FFFFFF;"></span>
						<input type="text" name="bookingStartDate" id="bookingStartDate" value="<?=$bookingStartDate?>" style="width:80px;" readonly>
						<img src="/images/mini_cal_calen.gif" style="cursor:pointer;" onclick="bookingStartDateCal.style.display=(bookingStartDateCal.style.display=='none' ? 'block' : 'none' );" align="absmiddle">

						<span id="bookingEndDateCal" style="position:absolute;display:none;border:1px solid #d9d9d9;padding:3px;background-color: #FFFFFF;"></span>
						<input type="text" name="bookingEndDate" id="bookingEndDate" value="<?=$bookingEndDate?>" style="width:80px;" readonly>
						<img src="/images/mini_cal_calen.gif" style="cursor:pointer;" onclick="bookingEndDateCal.style.display=(bookingEndDateCal.style.display=='none' ? 'block' : 'none' );" align="absmiddle">

						<script>
							show_cal('<?=$bookingStartDate?>','bookingStartDateCal','bookingStartDate');
							show_cal('<?=$bookingEndDate?>','bookingEndDateCal','bookingEndDate');
						</script>

						[<a href="javascript:setSearchDate('<?=date("Ymd")?>','<?=date("Ymd")?>');">����</a>]
						[<a href="javascript:setSearchDate('<?=date("Ym")."01"?>','<?=date("Ym").date("t")?>');">�̹���</a>]
						[<a href="javascript:setSearchDate('<?=date("Y")."0101"?>','<?=date("Y"."1231")?>');">����</a>]

						<br />

						��� :
						<select name="srchLocal">
							<option value="">��ü</option>
							<?
							foreach ($localList as $v ) {
								$sel = ($srchLocal == $v['location'] ? "selected":"" );
								echo "<option value='".$v['location']."' ".$sel.">".$v['title']."</option>";
							}
							?>
						</select>


						���� :
						<select name="srchStatus">
							<option value="">��ü</option>
							<?
								foreach ($bookingStatus as $k => $v ) {
									$sel = ($srchStatus == $k ? "selected":"" );
									if( $k != "NN" ) echo "<option value='".$k."' ".$sel.">".$v."</option>";
								}
							?>
						</select>

						�˻� :
						<input type="text" name="srchTxt" value="<?=$srchTxt?>"/>
						<input type="button" value="�˻�" onclick="searchSub();">

					</form>


					<table border="1" cellspacing="0" cellpadding="5" width="100%">
						<tr align="center">
							<td>��Ī</td>
							<td>�뿩 �Ⱓ</td>
							<td>�뿩�ڸ�</td>
							<td>����ó</td>
							<td>�ݾ�</td>
							<td>����</td>
							<td>�����</td>
						</tr>
						<?
						$sqlWhere = array();
						array_push($sqlWhere, " ( ( s.`bookingStartDate` >= ".$bookingStartDate." AND s.`bookingStartDate` <= ".$bookingEndDate." ) OR ( s.`bookingEndDate` >= ".$bookingStartDate." AND s.`bookingEndDate` <= ".$bookingEndDate." ) ) ");


						// ����� �˻�
						if( $srchLocal > 0 ){
							array_push($sqlWhere, " s.`location` = ".$srchLocal." ");
						}

						// Ű���� �˻�
						if ( strlen($srchTxt) > 0 ) {
							array_push($sqlWhere, " ( o.`id` LIKE '%".$srchTxt."%' OR o.`sender_name` LIKE '%".$srchTxt."%' OR o.`sender_tel` LIKE '%".$srchTxt."%' ) ");
						}

						$sqlWhere = " WHERE `type` = 'B' ".implode(" AND ", $sqlWhere);

						$sqlJoin = "INNER JOIN `tblorderinfo` as o ON o.`ordercode` = s.`ordercode` ";
						$prtJoin = ", o.`id`, o.`price`, o.`sender_name`, o.`sender_tel` ";

						$schdSQL = "SELECT s.*".$prtJoin." FROM `rent_product_schedule` as s ".$sqlJoin.$sqlWhere;

						$schdRES=mysql_query($schdSQL,get_db_conn());

						// ����¡����
						$total = mysql_result($schdRES,0,0);
						$page = _isInt($_REQUEST['page'])?intval($_REQUEST['page']):1;
						$perpage = _isInt($_REQUEST['perpage'])?intval($_REQUEST['perpage']):10;
						$total_page = max(1,@ceil($total/$perpage));
						$page = min($page,$total_page);
						$schdSQL .= " limit ".($page-1)*$perpage.','.$perpage;

						$schdRES=mysql_query($schdSQL,get_db_conn());
						while ( $schdROW =mysql_fetch_assoc($schdRES) ) {
							echo "
								<tr align=\"center\">
									<td>".$localList[$schdROW['location']]['title']."</td>
									<td>".$schdROW['bookingStartDate']." ~ ".$schdROW['bookingEndDate']."</td>
									<td>".$schdROW['sender_name']."</td>
									<td>".$schdROW['sender_tel']."</td>
									<td>".number_format($schdROW['price'])."</td>
									<td>".$bookingStatus[$schdROW['status']]."</td>
									<td>".$schdROW['regDate']."</td>
								</tr>
							";
						}
						?>
						<tr align=\"center\">
							<td colspan="7" align="center">
								<?
								$pages = new pages(array('total_page'=>$total_page,'page'=>$page,'pageblocks'=>10,'links'=>"/admin/product_rental.booking.list.php?page=%u"));
								echo $pages->_solv()->_result('fulltext');
								?>
							</td>
						</tr>
					</table>


				</td>
			</tr>


			<tr>
				<td height="20"></td>
			</tr>
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
										<td  class="space_top">- ������</td>
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
</td>
</tr>
</table>
</td>
</tr>
</table>


<?
INCLUDE "copyright.php";
?>
