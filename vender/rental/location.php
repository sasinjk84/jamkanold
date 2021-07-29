<?
$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");
include ("../access.php");
include_once($Dir."lib/ext/product_func.php");
include_once($Dir."lib/admin_more.php");

$_REQUEST['address'] = $_REQUEST['address1']." ".$_REQUEST['address2'];

// 지역 저장
if(!_empty($_REQUEST['act'])){
	switch($_REQUEST['act']){
		case 'update':
			if(!_isInt($_REQUEST['location'])){
				_alert('정보를 찾을수 없습니다.','-1');
				exit;
			}
			$sql = "select * from rent_location where location = '".$_REQUEST['location']."' limit 1";
			if(false === $res = mysql_query($sql,get_db_conn())){
				_alert('DB 접속 오류 ','-1');
				exit;
			}
			if(mysql_num_rows($res) <1){
				_alert('정보를 찾을 수 없습니다.','-1');
				exit;
			}
			$oinfo = mysql_fetch_assoc($res);
			if($oinfo['vender'] != $_VenderInfo->getVidx()){
				_alert('수정 권한이 없습니다.','-1');
			}
		case 'add':
			$sql = (!_isInt($oinfo['location']))?' INSERT INTO `rent_location` SET ':'UPDATE `rent_location` SET ';
			$sql.= "`type` = '".($_REQUEST['type']=='A'?'A':'B')."',`title` = '"._escape($_REQUEST['title'],false)."',`display` = '".($_REQUEST['display'] == '0'?'0':'1')."',`xpos` = '".$_REQUEST['xpos']."',`ypos` = '".$_REQUEST['ypos']."',`address` = '"._escape($_REQUEST['address'],false)."',`zip` = '".$_REQUEST['zip1']."'";
			$sql .= (!_isInt($oinfo['location']))?",vender='".$_VenderInfo->getVidx()."'":" where location='".$oinfo['location']."'";
			if(false === mysql_query($sql,get_db_conn())){
				_alert('DB 접속 오류 ','-1');
				exit;
			}
			break;
		case 'delete':
			$sql = "DELETE FROM `rent_location` WHERE `location` = '".$_REQUEST['location']."' and vender='".$_VenderInfo->getVidx()."' ";
			mysql_query($sql,get_db_conn());
			break;
		default:
			_alert('잘못된 명령 입니다.','-1');
			exit;
	}
	?>
	<script language="javascript" type="text/javascript">
	opener.loadLocalList();
	</script>
	<?
	_alert('',$_SERVER['PHP_SELF']);
	exit;
}
// 대여 출고지 정보 리스트
$localList = rentLocalList(array('vender'=>$_VenderInfo->getVidx()));
?><!doctype html>
<html lang="ko">
	<head>
		<META http-equiv="X-UA-Compatible" content="IE=Edge" />
		<title>출고지/지역관리</title>

		<script language="javascript" type="text/javascript" src="/js/jquery-1.10.2.min.js"></script>
		<script language="javascript" type="text/javascript" src="/js/jquery-ui-1.10.4.custom.min.js"></script>
		<link href="/css/ui-lightness/jquery-ui-1.10.4.custom.min.css" rel="stylesheet">

		<style type="text/css">
			html,body{margin:0;padding:0}
			.wrap{width:94%;margin:20px auto;}
			.tableBase{border-top:1px solid #b9b9b9;font-size:12px;}
			.tableBase th{padding:8px 0px;border-bottom:1px solid #ededed;border-left:1px solid #ededed;background:#f8f8f8;}
			.tableBase .firstTh{border-left:none;background:#f8f8f8;}
			.tableBase td{padding:8px 0px;border-bottom:1px solid #ededed;border-left:1px solid #ededed;}
			.tableBase .firstTd{border-left:none;}

			.tableBaseSe{font-size:12px;}
			.tableBaseSe caption{padding:8px;background:#ededed;border-bottom:1px solid #d9d9d9;}
			.tableBaseSe th{padding:8px 0px;border-right:1px solid #ededed;border-bottom:1px solid #ededed;background:#f5f5f5;text-align:center;}
			.tableBaseSe .lastTh{border-right:none;}
			.tableBaseSe td{padding:8px 0px;border-right:1px solid #ededed;border-bottom:1px solid #ededed;text-align:center;}
			.tableBaseSe .lastTd{border-right:none;}

			.tableBaseTh{font-size:12px;border-top:1px solid #d9d9d9;}
			.tableBaseTh caption{display:none;}
			.tableBaseTh th{padding:5px 12px;background:#f8f8f8;border-right:1px solid #ededed;border-bottom:1px solid #ededed;text-align:left;}
			.tableBaseTh .lastTh{border-bottom:none;}
			.tableBaseTh td{padding:5px 8px;border-bottom:1px solid #ededed;}
			.tableBaseTh .lastTd{border-bottom:none;}

			.tableBaseNoBorder th{border:0px;padding:1px 0px;}
			.tableBaseNoBorder td{border:0px;padding:1px 0px;}

			.baseBtn{display:block;width:100px;padding:7px 0px 3px 0px;background:#ffffff;border:1px solid #cccccc;border-radius:4px;color:#666666;font-size:11px;font-family:돋움;letter-spacing:-1px;font-weight:normal;text-decoration:none;text-align:center}
			.baseBtn:hover{background:#444444;color:#ffffff;border:none}
		</style>

		<script language="javascript" type="text/javascript">
			function checkLength(el,tit,min,max){
				val = $.trim($(el).val());
				if(val.length < min){
					alert(tit+'의 값은 '+min+' 자 이상 입력하셔야 합니다.');
					$(el).focus();
					return false;
				}else if(max && val.length > max){
					alert(tit+'의 값은 '+max+' 자 이하로 입력하셔야 합니다.');
					$(el).focus();
					return false;
				}
				return true;
			}

			$(function(){
				$("#inputDiv").dialog({
					autoOpen: false,
					height: 330,
					width: 450,
					modal: true,
					resizable:false,
					draggable:false,
				buttons: {
					"저장": function() {
					  var bValid = true;
					  var f = document.rentLocalInput;
					  bValid = bValid && checkLength($(f).find('input[name=title]'),"지역명", 3, 30 );
					  bValid = bValid && checkLength( $(f).find('input[name=address1]'), "주소",5 );
					  if(bValid){
						  f.submit();
					  }
					},
					'취소': function(){
						document.rentLocalInput.reset();
					  $( this ).dialog( "close" );
					}
				  },
				  close: function(){
				//   alert('t');
				  }
				});

				$('.editBtn').on('click',function(event){
					event.preventDefault();
					openEdit($(this));
				});
			});

			function openzip() {
				window.open("/front/addr_search.php?form=rentLocalInput&post=zip&addr=address&gbn=2","f_post","resizable=yes,scrollbars=yes,x=100,y=200,width=370,height=250");
			}

			function openEdit(el){
				//var local = parseInt(local);
				var f = document.rentLocalInput;
				if(el){
					/*
					for(p in el){
						alert(p+'-'+el[p]);
					}*/
					var location = parseInt($(el).parent().parent().find('td:eq(0)').attr('lidx'));
					var display = $(el).parent().parent().find('td:eq(0)').attr('ldisp');
					if(!isNaN(location) && location >0){ // 에디트			
						f.act.value = 'update';
						f.location.value = location;			
						var type=  $(el).parent().parent().find('td:eq(1)').attr('ltype');
						var title=  $(el).parent().parent().find('td:eq(2)').find('.titleTxt').html();
						var zip1 =  $(el).parent().parent().find('td:eq(2)').attr('zip1');
						var zip2 =  $(el).parent().parent().find('td:eq(2)').attr('zip2');
						var address =  $(el).parent().parent().find('td:eq(2)').find('span.addressTxt').html();
						
						$(f).find("input[name=display][value="+display+"]").attr('checked',true);
						$(f).find("input[name=type][value="+type+"]").attr('checked',true);
						$(f).find("input[name=title]").val(title);
						$(f).find("input[name=zip1]").val(zip1+""+zip2);
						//$(f).find("input[name=zip2]").val(zip2);
						$(f).find("input[name=address1]").val(address);
					}
				}else{
					f.act.value = 'add';
					$(f).find("input[name=display][value=1]").attr('checked',true);
					$(f).find("input[name=type][value=A]").attr('checked',true);
				}
				$('#inputDiv').dialog('open');
			}

			function localDelete(location){
				var f = document.rentLocalInput;
				if(confirm('삭제 하시겠습니까?')){
					f.location.value = location;
					f.act.value = 'delete';
					f.submit();
				}
			}
		</script>
	</head>
	<body>

		<div class="wrap">
			<h6 style="margin:0px;padding-bottom:14px;overflow:hidden">
				<img src="/admin/images/product_rentalrelease_stitle1.gif" style="float:left" alt="" />
				<a href="#" onClick="javascript:openEdit()" class="baseBtn" style="float:right;margin-top:4px;" />출고지 등록하기</a>
			</h6>

			<table border="0" cellspacing="0" cellpadding="0" width="100%" class="tableBase">
				<colgroup>
					<col width="70" />
					<col width="50" />
					<col width="" />
					<col width="70" />
				</colgroup>
				<tr>
					<th class="firstTh">노출</th>
					<th>타입</td>
					<th>[지역명]<br>주소</th>
					<th>관리</th>
				</tr>
				<?
					if(_array($localList)){	
						foreach ( $localList as $k=>$v ) {
							$expzip = explode('-',$v['zip']);
				?>
				<tr>
					<td class="firstTd" style="text-align:center" lidx="<?=$v['location']?>" ldisp="<?=$v['display']?>"><?=($v['display']?"노출":"노출안함")?></td>
					<td style="text-align:center" ltype="<?=$v['type']?>"><?=rentProduct::locationType($v['type'])?></td>
					<td style="padding-left:10px;" zip1="<?=$expzip[0]?>" zip2="<?=$expzip[1]?>"><p style="margin-bottom:4px;">[<strong class="titleTxt"><?=$v['title']?></strong>]</p>
					(<?=str_replace('-','',$v['zip'])?>) <span class="addressTxt"><?=$v['address']?></span></td>
					<td style="text-align:center">
						<img src="/admin/images/btn_edit.gif" border="0" border="수정" class="editBtn" style="cursor:pointer" />
						<a href="javascript:localDelete('<?=$k?>');"><img src="/admin/images/btn_del.gif" border="0" alt="삭제" /></a>
					</td>
				</tr>
				<?
						}
					}else{ ?>	
				<tr>
					<td colspan="4" style="text-align:center; padding:15px 0px;"> 등록된 출고지 정보가 없습니다.</td>
				</tr>
				<?	}	?>
			</table>

			<div style="text-align:center; padding-top:15px;">
				<a href="#" onClick="javascript:window.close();" class="baseBtn" style="margin:0 auto">창닫기</a>

			<div id="inputDiv" style="display:none;background:#ffffff;margin:0px;overflow:hidden" title="출고지/지역관리">
				<form name="rentLocalInput" style="margin:0px; padding:0px;" action="<?=$_SERVER['PHP_SELF']?>" method="post">
					<input type="hidden" name="act" value="" />
					<input type="hidden" name="location" value="" />

					<table border="0" cellspacing="0" cellpadding="0" width="100%" id="rentLocalInputTable" class="tableBaseTh">
						<tr>
							<th>노출여부</th>
							<td>
								<input type="radio" name="display" value='0' />노출안함
								<input type="radio" name="display" value='1' />노출
							</td>
						</tr>
						<tr>
							<th>타입</th>
							<td>				
								<input type="radio" name="type" value='A' />출고지
								<input type="radio" name="type" value='B' />장소 렌탈
							</td>
						</tr>
						<tr>
							<th>지역명</th>
							<td><input type="text" name="title" id="title" class="input" style="width:170px;" /></td>
						</tr>
						<tr>
							<th>주소</th>
							<td>
								<!--
								<input type="text" name="zip1" value="" size="4" class="input" readonly />
								-
								<input type="text" name="zip2" value="" size="4" style="margin-right:5px" class="input" readonly />
								<a href="javascript:openzip()"><img src="/admin/images/icon_addr.gif" height="20" align="absmiddle" border="0" /></a><br />
								<input type="text" name="address" value="" style="width:90%; margin-top:5px;" class="input" />
								-->

								<div style="overflow:hidden">
									<INPUT type="text" name="zip1" id="zip1" size="10" value="" readOnly class="input" /> 
									<A href="javascript:addr_search_for_daumapi('zip1','address1','address2');"><img src="/admin/images/icon_addr.gif" height="20" align="absmiddle" border="0" /></a>
								</div>
								<div style="margin:3px 0px;overflow:hidden"><INPUT type="text" name="address1" id="address1" value="" maxLength="100" style="WIDTH:96%" class="input" /></div>
								<!--<div style="overflow:hidden"><INPUT type="text" name="address2" id="address2" maxLength="100" style="WIDTH:96%" class="input" /></div>-->

							</td>
						</tr>
					</table>
				</form>
			</div>
		</div>

		<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
		<script type="text/javascript">
			<!--
			function addr_search_for_daumapi(post,addr1,addr2) {
				new daum.Postcode({
					oncomplete: function(data) {
						// 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

						// 각 주소의 노출 규칙에 따라 주소를 조합한다.
						// 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
						var fullAddr = ''; // 최종 주소 변수
						var extraAddr = ''; // 조합형 주소 변수

						// 사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
						if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
							fullAddr = data.roadAddress;

						} else { // 사용자가 지번 주소를 선택했을 경우(J)
							fullAddr = data.jibunAddress;
						}
	
						// 사용자가 선택한 주소가 도로명 타입일때 조합한다.
						if(data.userSelectedType === 'R'){
							//법정동명이 있을 경우 추가한다.
							if(data.bname !== ''){
								extraAddr += data.bname;
							}
							// 건물명이 있을 경우 추가한다.
							if(data.buildingName !== ''){
								extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
							}
							// 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
							fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
						}
						
						var title = '';
						if(data.sido=="서울"){
							title = data.sigungu+" "+data.roadname;
						}else{
							title = data.sigungu;
						}

						// 우편번호와 주소 정보를 해당 필드에 넣는다.
						document.getElementById(post).value = data.zonecode; //5자리 새우편번호 사용
						document.getElementById(addr1).value = fullAddr;
						document.getElementById("title").value = title;

						// 커서를 상세주소 필드로 이동한다.
						if (addr2 != "") {
						//	document.getElementById(addr2).focus();
						}
					}
				}).open();
			}
			//-->
		</script>
	</body>
</html>