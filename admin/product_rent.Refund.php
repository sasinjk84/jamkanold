<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### 페이지 접근권한 check ###############
$PageCode = "pr-1";
$MenuCode = "product";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

extract($_GET);
extract($_POST);
//_pr($_POST);

// DB
if( strlen($dayMsg) > 0 AND strlen($feesMsg) > 0 ) {
	if( $del == "delete" AND strlen ($idx) > 0 ) {
		$saveSQL = "DELETE FROM rent_refund";
		$saveSQLwhere = " WHERE idx = " . $idx;
	} else {
		// 저장
		$sortMax = mysql_fetch_assoc( mysql_query( "select max(sort) as sort from rent_refund  " ,get_db_conn() ) );
		$saveSQLdata = "`dayMsg` = '" . $dayMsg . "', `feesMsg` = '" . $feesMsg . "', `sort` = ".($sortMax['sort'] + 1);
		if (strlen ($idx) > 0) {
			// 수정
			$saveSQL = "UPDATE rent_refund SET";
			$saveSQLwhere = " WHERE idx = " . $idx;
		} else {
			//등록
			$saveSQL = "INSERT rent_refund SET";
			$saveSQLwhere = "";
		}
	}
	$SaveSQL = $saveSQL.$saveSQLdata.$saveSQLwhere;
	mysql_query( $SaveSQL ,get_db_conn());
	header('Location: http://'.$_SERVER['SERVER_NAME'].'/admin/product_rent.Refund.php');
	exit;
}
?>

<? INCLUDE "header.php"; ?>
<script type="text/javascript" src="lib.js.php"></script>
<script type="text/javascript" src="codeinit.js.php"></script>
<script type="text/javascript" src="/js/jquery-1.10.2.min.js"></script>
<script type="text/javascript">
	// 저장
	function refundCHK( f ){
		if ( f.dayMsg.value.length == 0 ) {
			alert("취소일내용을 입력하세요!");
			f.dayMsg.focus();
			return;
		}
		if ( f.feesMsg.value.length == 0 ) {
			alert("취소수수료내용을 입력하세요!");
			f.feesMsg.focus();
			return;
		}
		f.method = "POST";
		f.submit()
	}
	//삭제
	function refundDEL ( f) {
		if( confirm("정말 삭제 하시겠습니까?")){
			f.del.value = "delete";
			refundCHK( f );
		}
	}
</script>

<table cellpadding="0" cellspacing="0" width="100%">
<tr>
<td valign="top">
<table cellpadding="0" cellspacing="0" width=100%>
<tr>
<td>

<table cellpadding="0" cellspacing="0" width="100%"  background="images/con_bg.gif">
<tr>
<td valign="top"  background="images/leftmenu_bg.gif" width=198>
	<? include ("menu_product.php"); ?>
</td>

<td width=10></td>
<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
<tr>
	<td height="29" colspan="3">
		<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">현재위치 : 상품 &gt; 예약/렌탈 관리 &gt; <span class="2depth_select">환불 정책</span></td>
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
							<TD><IMG SRC="images/product_refund_title.gif" ALT="환불 정책"></TD>
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
							<TD width="100%" class="notice_blue">환불 정책을 등록/수정/삭제하실 수 있습니다.</TD>
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
					<h6 style="margin:0px;padding-bottom:5px;"><img src="images/product_refund_stitle1.gif" alt="" /></h6>
					<table border="0" cellpadding="0" cellspacing="0" width="100%" class="tableBase" style="margin-bottom:40px;">
						<colgroup>
							<col width="50">
							<col width="">
							<col width="">
							<col width="140">
						</colgroup>
						<tr>
							<th class="firstTh">순서</th>
							<th>취소일</th>
							<th>취소 수수료</th>
							<th>관리</th>
						</tr>
						<?
						$SQL = "SELECT * FROM rent_refund ORDER BY sort ASC";
						$RES=mysql_query($SQL,get_db_conn());
						$i = 1;
						while ( $ROW =mysql_fetch_assoc($RES) ) {
						?>
							<form name="refundForm<?=$ROW['idx']?>">
								<tr align="center">
									<td class="firstTd"><?=$i?></td>
									<td align="left" style="padding-left:7px;"><input type="text" name="dayMsg" value="<?=$ROW['dayMsg']?>" style="width:300px;" class="input" /></td>
									<td align="left" style="padding-left:7px;"><input type="text" name="feesMsg" value="<?=$ROW['feesMsg']?>" style="width:300px;" class="input" /></td>
									<td>
										<!-- <input type="button" value="수정" onclick="refundCHK(refundForm<?=$ROW['idx']?>);">
										<input type="button" value="삭제" onclick="refundDEL(refundForm<?=$ROW['idx']?>);">
										<input type="button" value="위로" onclick="refundSort("up",refundForm<?=$ROW['idx']?>);">
										<!-- <input type="button" value="아래로" onclick="refundSort("dn",refundForm<?=$ROW['idx']?>);"> -->
										<input type="image" src="images/btn_edit.gif" onclick="refundCHK(refundForm<?=$ROW['idx']?>);" /></a>
										<input type="image" src="images/btn_del.gif" onclick="refundDEL(refundForm<?=$ROW['idx']?>);" /></a>
									</td>
								</tr>
								<input type="hidden" name="del" value="" />
								<input type="hidden" name="idx" value="<?=$ROW['idx']?>" />
							</form>
						<?
							$i++;
						}
						?>
					</table>

					<form name="refundForm">
					<h6 style="margin:0px;padding-bottom:5px;"><img src="images/product_refund_stitle2.gif" alt="" /></h6>
					<table cellpadding="0" cellspacing="0" width="100%" class="tableBase">
						<tr align="center">
							<th class="firstTh" style="text-align:left;padding-left:10px;"><img src="images/icon_point2.gif" border="0" alt="" />취소일</th>
							<td align="left" style="padding-left:7px;"><input type="text" name="dayMsg" style="width:300px;" class="input" /> &nbsp;<span class="font_orange">(입력 예: 1일 전, 12시간 전)</span></td>
						</tr>
						<tr>
							<th class="firstTh" style="text-align:left;padding-left:10px;"><img src="images/icon_point2.gif" border="0" alt="" />취소 수수료</th>
							<td align="left" style="padding-left:7px;"><input type="text" name="feesMsg" style="width:300px;" class="input" /></td>
						</tr>
					</table>
					<div style="margin-top:20px;text-align:center;"><input type="image" src="images/botteon_save.gif" onclick="refundCHK(refundForm);" /></div>
					</form>
				</td>
			</tr>
			<tr><td height="40"></td></tr>
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
										<td  class="space_top">- 설명내용</td>
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
