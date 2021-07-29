<?
session_start();
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

//extract($_GET);
$datet = $_GET['datet'];
$vdate = $_GET['vdate'];


if(empty($vdate)) {
	$vdate = date('Ymd');
}

// 벤더 정보 리스트
$venderList = venderList("vender,id,com_name");

// 대여 출고지 정보 리스트
$value = array("display"=>1); //, "type"=>"B"
$localList = rentLocalList( $value );
//_pr($localList);

// 달력타입
if( strlen($datet) > 0 ) {
	$_SESSION['datet'] = $datet;
}
if( empty($_SESSION['datet']) ) $_SESSION['datet'] = $datet = "M";


function infoareaHtml($info,$setid){
	$str = '<div id="'.$setid.'" style="display:none;">
				<table width="100%" cellpadding="0" cellspacing="0" border="0" class="tableBaseSe" style="background:#ffffff">';
	$str .= '		<tr>
						<th>예약자</th>
						<td>'.$info['sender_name'].'</td>
					</tr>';
	$str .= '		<tr>
						<th>전화</th>
						<td>'.$info['sender_tel'].'</td>
					</tr>';
	$info['start'] = substr($info['start'],0,-3);
	$info['end'] = substr($info['end'],0,-3);
	
	$str .= '		<tr>
						<th>기간</th>
						<td>'.$info['start'].' ~ '.$info['end'].'</td>
					</tr>';
	$str .= '		<tr>
						<th>수량</th>
						<td>'.$info['quantity'].'</td>
					</tr>';
	$str .= '		<tr>
						<th>총금액</th>
						<td>'.number_format($info['price']).'원</td>
					</tr>';
	$str .= '		<tr>
						<th>진행상태</th>
						<td>'.rentProduct::_bookingStatus($info['status']).'</td>
					</tr>
				</table>
			</div>';
	return $str;
	
}
?>

<? INCLUDE "header.php"; ?>
<script type="text/javascript" src="lib.js.php"></script>
<script type="text/javascript" src="codeinit.js.php"></script>
<script type="text/javascript" src="/js/jquery-1.10.2.min.js"></script>
<script language="javascript" type="text/javascript" src="/js/jquery-ui-1.10.4.custom.min.js"></script>
<link type="text/css" rel="stylesheet" href="/css/ui-lightness/jquery-ui-1.10.4.custom.min.css" />
<script language="javascript" type="text/javascript">
	$(function() {
		$("#datepicker").datepicker({dateFormat:'yymmdd',onSelect :function(txt,inst){ if(document.setDateForm) document.setDateForm.submit();} });
		
		$('.scheduledItem').on('mouseover',function(){
			divid = $(this).attr('layerid');
			viewInfo(divid);
		});
		
		$('.scheduledItem').on('mouseout',function(){
			offInfo();
		});
	  });
</script> 
<script type="text/javascript">
	<!--
	// 마우스 따라다니는 레이어
	jQuery(document).ready(function(){
		$(document).mousemove(function(e){
			var leftP = ( ( $(document).width() - 600 ) < e.pageX ) ? $(document).width()-630 : e.pageX ;
		   $('#viewInfo').css("left",leftP-13);
		   $('#viewInfo').css("top",e.pageY+15);
		});
	})

	// 레이어 내용채워 보이기
	function viewInfo( idx ) {	
		$('#viewInfo').html($('#'+idx).html());
		$('#viewInfo').css("display","block");		
	}

	// 레이어 내용비우고 가리기
	function offInfo(){
		$('#viewInfo').css("display","none");
		$('#viewInfo').html("");
	}


	function changeList(val){
		if(document.setDateForm && document.setDateForm.vdate){
			document.setDateForm.vdate.value = val;
			document.setDateForm.submit();
		}
	}
	
	function changeListType(val){
		if(document.setDateForm && document.setDateForm.datet){
			document.setDateForm.datet.value = val;
			document.setDateForm.submit();
		}
	}

	function changeStatus(ordercode, bskidx) {
		window.open( "product_rental.change.status.php?ord="+ordercode+"&bskidx="+bskidx, "changeStatus" , "width=620, height=500, menubar=no, status=no, scrollbars=no" );
	}
	-->
</script>
<style type="text/css">
	.resevST_BR,.resevST_NN,{ background:#efefef;}
	.resevST_BO{ background:#6F9}
	.resevST_BI{ background:#6F9}
	.resevST_BC,.resevST_NC,.resevST_OT,.resevST_RP{ background:#f00}

	.tableBaseSe{font-size:12px;}
	.tableBaseSe caption{padding:8px;background:#ededed;border-bottom:1px solid #d9d9d9;}
	.tableBaseSe th{padding:8px 10px;border-right:1px solid #ededed;border-bottom:1px solid #ededed;background:#f5f5f5;text-align:left;}
	.tableBaseSe .lastTh{border-right:none;}
	.tableBaseSe td{padding:8px 10px;border-right:1px solid #ededed;border-bottom:1px solid #ededed;text-align:left;}
	.tableBaseSe .lastTd{border-right:none;}
</style>

<div id="viewInfo" style="display:none;position:absolute;top:100px;left:100px;width:400px;padding:0px;background:#ffffff;border:5px solid #dddddd;z-index:999;"></div>

<table cellpadding="0" cellspacing="0" width="100%" background="images/con_bg.gif">
	<tr>
		<td width="198" valign="top" background="images/leftmenu_bg.gif">
			<? include ("menu_product.php"); ?>
		</td>
		<td width="10"></td>
		<td valign="top">
			<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td height="28" colspan="3" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">현재위치 : 상품 &gt; 예약/렌탈 관리 &gt; <span class="2depth_select">예약/렌탈 현황</span></td>
				</tr>
				<tr>
					<td width="16"><img src="images/con_t_01.gif" width="16" height="16" border="0" /></td>
					<td background="images/con_t_01_bg.gif"></td>
					<td width="16"><img src="images/con_t_02.gif" width="16" height="16" border="0" /></td>
				</tr>
				<tr>
					<td width="16" background="images/con_t_04_bg1.gif"></td>
					<td bgcolor="#ffffff" style="padding:10px">
						<? include "product_rental.booking.".$_SESSION['datet'].".php"; ?>
					</td>
					<td width="16" background="images/con_t_02_bg.gif"></td>
				</tr>
				<tr>
					<td width="16"><img src="images/con_t_04.gif" width="16" height="16" border="0" /></td>
					<td background="images/con_t_04_bg.gif"></td>
					<td width="16"><img src="images/con_t_03.gif" width="16" height="16" border="0" /></td>
				</tr>
				<tr><td height="20"></td></tr>
			</table>
		</td>
	</tr>
</table>

<? INCLUDE "copyright.php"; ?>