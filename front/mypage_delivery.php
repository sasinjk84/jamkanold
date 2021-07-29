<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");
include_once($Dir."lib/venderlib.php");

$exOk=0;
$_VenderInfo = new _VenderInfo($_COOKIE[_vinfo]);
if($_ShopInfo->getMemid()==$_VenderInfo->getId()){
	$Vender = 1;
	$exOk=1;
}

$sql = "SELECT * FROM tblmember WHERE id='".$_ShopInfo->getMemid()."' ";
$result=mysql_query($sql,get_db_conn());
if($row=mysql_fetch_object($result)) {
	$_mdata=$row;
	if($row->member_out=="Y") {
		$_ShopInfo->SetMemNULL();
		$_ShopInfo->Save();
		echo "<html><head><title></title></head><body onload=\"alert('회원 아이디가 존재하지 않습니다.');location.href='".$Dir.FrontDir."login.php';\"></body></html>";exit;
	}

	if($row->authidkey!=$_ShopInfo->getAuthidkey()) {
		$_ShopInfo->SetMemNULL();
		$_ShopInfo->Save();
		echo "<html><head><title></title></head><body onload=\"alert('처음부터 다시 시작하시기 바랍니다.');location.href='".$Dir.FrontDir."login.php';\"></body></html>";exit;
	}
		if($row->wholesaletype=="Y") $exOk=1;

}
mysql_free_result($result);

if(strlen($_ShopInfo->getMemid())==0) {
	Header("Location:".$Dir.FrontDir."login.php?chUrl=".getUrl());
	exit;
}

$sql = "SELECT * FROM tblmember WHERE id='".$_ShopInfo->getMemid()."' ";
$result=mysql_query($sql,get_db_conn());
if($row=mysql_fetch_object($result)) {
	$coderow=$row;
	if($row->member_out=="Y") {
		$_ShopInfo->SetMemNULL();
		$_ShopInfo->Save();
		echo "<html><head><title></title></head><body onload=\"alert('회원 아이디가 존재하지 않습니다.');location.href='".$Dir.FrontDir."login.php';\"></body></html>";exit;
	}

	if($row->authidkey!=$_ShopInfo->getAuthidkey()) {
		$_ShopInfo->SetMemNULL();
		$_ShopInfo->Save();
		echo "<html><head><title></title></head><body onload=\"alert('처음부터 다시 시작하시기 바랍니다.');location.href='".$Dir.FrontDir."login.php';\"></body></html>";exit;
	}
	$url_id = $row->url_id;
}

$leftmenu="Y";
$sql="SELECT body,leftmenu FROM ".$designnewpageTables." WHERE type='memberout'";
$result=mysql_query($sql,get_db_conn());
if($row=mysql_fetch_object($result)) {
	$body=$row->body;
	$body=str_replace("[DIR]",$Dir,$body);
	$leftmenu=$row->leftmenu;
	$newdesign="Y";
}
mysql_free_result($result);

$type=$_POST['type'];

$idx=$_POST['idx'];
$receiver_name=$_POST['receiver_name'];
$receiver_tel1=$_POST['receiver_tel11']."-".$_POST['receiver_tel12']."-".$_POST['receiver_tel13'];
$receiver_tel2=$_POST['receiver_tel21']."-".$_POST['receiver_tel22']."-".$_POST['receiver_tel23'];
$receiver_email=$_POST['receiver_email'];
$receiver_post=$_POST['rpost1'];
$receiver_addr1=$_POST['raddr1'];
$receiver_addr2=$_POST['raddr2'];
$receiver_addr=mysql_escape_string($receiver_addr1)."=".mysql_escape_string($receiver_addr2);

if($type=='delete'){
	$sql="DELETE FROM tblorderreceiver WHERE idx=".$idx." ";
	mysql_query($sql,get_db_conn());
	$onload="<script>alert('배송지 삭제가 완료되었습니다.');</script>";

}

$msql="SELECT * FROM tblorderreceiver WHERE member_id='".$_ShopInfo->getMemid()."' ORDER BY idx DESC ";
$mresult=mysql_query($msql,get_db_conn());
$mnums=mysql_num_rows($mresult);

include_once($Dir."lib/header.php");
?>

<body<?=(substr($_data->layoutdata["MOUSEKEY"],0,1)=="Y"?" oncontextmenu=\"return false;\"":"")?><?=(substr($_data->layoutdata["MOUSEKEY"],1,1)=="Y"?" ondragstart=\"return false;\" onselectstart=\"return false;\"":"")?> leftmargin="0" marginwidth="0" topmargin="0" marginheight="0"><?=(substr($_data->layoutdata["MOUSEKEY"],2,1)=="Y"?"<meta http-equiv=\"ImageToolbar\" content=\"No\">":"")?>

<? 
	include ($Dir.MainDir.$_data->menu_type.".php");
	include_once("./mypage_groupinfo.php"); //회원등급 출력용
?>

<script language="javascript">
	<!--
	function FormType(type,idx){
		if(type=='insert'){
			window.open("mypage_delivery_insert.php","addAddr","width=400,height=550,top=100,left=100");

		}else if(type=='modify'){
			window.open("mypage_delivery_modify.php?idx="+idx, "addAddr", "width=400,height=550,top=100,left=100");
		}
	}

	function DeleteForm(idx){
		if(!confirm("배송지를 삭제하시겠습니까?")){
			return;
		}
		document.form1.type.value='delete';
		document.form1.idx.value=idx;
		document.form1.submit();
	}
	//-->
</script>

<style>
	#insert_delivery_pop{display:none;-webkit-transition: all 0.3s ease;-moz-transition: all 0.3s ease;-ms-transition: all 0.3s ease;-o-transition: all 0.3s ease;transition: all 0.3s ease;}
	.wrap_insert_delivery{position:fixed;top:0px;left:0px;display:table;width:100%;height:100%;background:rgba(0,0,0,0.8);z-index:999;}
	#cell_insert_delivery{display:table-cell;border:1px solid #ddd;box-sizing:border-box;vertical-align:middle;-webkit-transition: all 0.3s ease;-moz-transition: all 0.3s ease;-ms-transition: all 0.3s ease;-o-transition: all 0.3s ease;transition: all 0.3s ease;}
</style>

<!-- 마이페이지-홍보관리 상단 메뉴 -->
<h1 class="subpageTitle">배송지 관리</h1>

<!--
<div class="mypagemembergroup">
	<div class="groupinfotext">안녕하세요? <strong class="st1"><?=$_ShopInfo->getMemname()?></strong>님.<? if($groupname){?> 회원님의 등급은 <strong class="st2"><?=$groupname?></strong>입니다.<?}?></div>
</div>
<table border="0" cellpadding="0" cellspacing="1" class="mypagetmenu">
	<tr>
		<td class="leftline"><a href="/front/mypage.php">마이페이지</a></td>
		<td class="nowMyPage"><a href="/front/mypage_delivery.php">배송지관리</a></td>
		<td class="leftline"><a href="/front/mypage_orderlist.php">주문배송조회</a></td>
		<? if($_data->personal_ok == "Y"){ ?><td class="leftline"><a href="/front/mypage_personal.php">1:1 문의</a></td><? } ?>
		<td class="leftline"><a href="/front/mypage_reserve.php">적립금</a></td>
		<?if(Excelbuy=="ON" && $exOk==1){?><td><a href="../B2B/ex_order.php">엑셀주문</a></td><?}?>
		<td class="leftline"><a href="/front/wishlist.php">위시리스트</a></td>
		<td class="leftline"><a href="/front/mypage_coupon.php">쿠폰내역</a></td>
		<? if($_data->recom_url_ok == "Y" || $_data->sns_ok == "Y"){ ?><td class="leftline"><a href="/front/mypage_promote.php">홍보관리</a></td><? } ?>
		<? if(getVenderUsed()==true) { ?><td><a href="/front/mypage_custsect.php">단골매장</a></td><? } ?>
		<td><a href="/front/mypage_usermodify.php">회원정보</a></td>
        <? if($_data->memberout_type!="N"){ ?><td><a href="/front/mypage_memberout.php">회원탈퇴</a></td><? } ?>
	</tr>
</table>
-- 마이페이지-홍보관리 상단 메뉴 -->


<div style="position:relative;margin:25px 0px 15px 0px;">
	<h4 style="line-height:20px;font-weight:normal;">상품 주문시 등록하는 배송지 정보를 미리 등록 및 관리할 수 있습니다.</h4>
	<a href="#" onclick="FormType('insert','')" id="insert_delivery" style="position:absolute;top:0px;right:0px;display:inline-block;*display:inline;*zoom:1;"><span class="btn_m_line3">배송지 등록</span></a>
</div>

<table border="0" cellpadding="0" cellspacing="0" width="100%" class="itemListTbl">
	<colgroup>
		<col width="70" />
		<col width="120" />
		<col width="" />
		<col width="120" />
		<col width="120" />
		<col width="100" />
	</colgroup>
	<tr style="height: 50px;
    background: #f9f9f9;
    /* border-right: 1px solid #f2f2f2; */
    border-bottom: 1px solid #f2f2f2;
    color: #848484;
    text-align: center;
    font-weight: 400;
    font-size: 14px;">
		<th>번호</th>
		<th>우편번호</th>
		<th>배송지 주소</th>
		<th>휴대폰번호</th>
		<th>수신자명</th>
		<th>관리</th>
	</tr>
	<?
		$i=0;
		while($mrow=mysql_fetch_object($mresult)){
			$receiver_addr=stripslashes($mrow->receiver_addr);
			$receiver_addr_temp=explode("=",$receiver_addr);
			$receiver_addr1= $receiver_addr_temp[0];
			$receiver_addr2= stripslashes($receiver_addr_temp[1]);
	?>
	<tr>
		<td class="tdstyle2a"  style="text-align: center;border-bottom: 1px solid #f2f2f2;padding: 10px 0px;"><?=($mnums-$i)?></td>
		<td class="tdstyle2a"  style="text-align: center;border-bottom: 1px solid #f2f2f2;padding: 10px 0px;"><?=$mrow->receiver_post?></td>
		<td class="tdstyle2a"  style="text-align: left;border-bottom: 1px solid #f2f2f2;padding: 10px 0px;"><?=$receiver_addr1." ".$receiver_addr2?></td>
		<td class="tdstyle2a"  style="text-align: center;border-bottom: 1px solid #f2f2f2;padding: 10px 0px;"><?=$mrow->receiver_tel2?></td>
		<td class="tdstyle2a"  style="text-align: center;border-bottom: 1px solid #f2f2f2;padding: 10px 0px;"><?=$mrow->receiver_name?></td>
		<td class="tdstyle2a"  style="text-align: center;border-bottom: 1px solid #f2f2f2;padding: 10px 0px;">
			<a href="javascript:FormType('modify','<?=$mrow->idx?>')" class="modify_delivery"><span class="btn_s_line2">수정</span></a>
			<a href="javascript:DeleteForm('<?=$mrow->idx?>')"><span class="btn_s_line2">삭제</span></a>
		</td>
	</tr>
	<?
			$i++;
		}

		if($mnums<1){
			echo "<tr><td colspan='6' align='center' style='padding:20px 0px;border-bottom:1px solid #eee;'>등록된 배송지가 없습니다.</td></tr>";
		}
	?>
</table>

<?=$onload?>

<? include ($Dir."lib/bottom.php"); ?>
