<?
session_start();
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");
include ("access.php");


// 대여 출고지 정보 리스트
$value = array("display"=>1,'vender'=>$_VenderInfo->getVidx()); //, "type"=>"B"
$localList = rentLocalList( $value );
//_pr($localList);

// 달력타입
if( strlen($datet) > 0 ) {
	$_SESSION['datet'] = $_REQUEST['datet'];
}

if( empty($_SESSION['datet']) ) $_SESSION['datet'] = "M";

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
						<td>'.$info['start'].'~'.$info['end'].'</td>
					</tr>';
	$str .= '		<tr>
						<th>수량</th>
						<td>'.$info['quantity'].'</td>
					</tr>';
	$str .= '		<tr>
						<th>총금액</th>
						<td>'.$info['price'].'</td>
					</tr>';
	$str .= '		<tr>
						<th>진행상태</th>
						<td>'.rentProduct::_bookingStatus($info['status']).'</td>
					</tr>
				</table>
			</div>';
	return $str;
	
}

include "header.php"; ?>
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
	-->
</script>

<style type="text/css">
#orderDeliForm{border:0px;padding:0px;margin:0px;}
.resevST_BR,.resevST_NN,{ background:#efefef;}
.resevST_BO{ background:#6F9}
.resevST_BI{ background:#6F9}
.resevST_BC,.resevST_NC,.resevST_OT,.resevST_RP{ background:#f00}
</style>
<div id="viewInfo" style="display:none;position:absolute;top:100px;left:100px;width:400px;height:0;padding:0px;background:#ffffff;border:2px solid #999999;z-index:999;"></div>
<table border=0 cellpadding=0 cellspacing=0 width=100% height="100%" style="table-layout:fixed">
	<col width=190></col>
	<col width=20></col>
	<col width=></col>
	<col width=20></col>
	<tr>
		<td width=190 valign=top nowrap background="images/minishop_leftbg.gif"><? include ("menu.php"); ?></td>
		<td width=20 nowrap></td>
		<td valign=top style="padding-top:20px">			
			<?			
			include './rental/orderlist.php';
			?>
			<!-- 처리할 본문 위치 끝 -->		
		</td>
	</tr>
</table>
<? INCLUDE "copyright.php"; ?>