<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

if(strlen($_ShopInfo->getMemid())==0) {
	Header("Location:".$Dir.FrontDir."login.php?chUrl=".getUrl());
	exit;
}

if($_data->reserve_maxuse<0) {
	echo "<html><head><title></title></head><body onload=\"alert('�� ���θ������� ������ ����� �������� �ʽ��ϴ�.');location.href='".$Dir.FrontDir."mypage.php'\"></body></html>";exit;
}

if($_data->cr_ok!='Y') {
	echo "<html><head><title></title></head><body onload=\"alert('������ ��ȯ ����� �������� �ʽ��ϴ�.');location.href='".$Dir.FrontDir."mypage.php'\"></body></html>";exit;
}

$maxreserve=$_data->reserve_maxuse;

$reserve=0;
$sql = "SELECT id,name,reserve FROM tblmember WHERE id='".$_ShopInfo->getMemid()."' ";
$result=mysql_query($sql,get_db_conn());
if($row=mysql_fetch_object($result)) {
	$coderow = $row;
	$id=$row->id;
	$name=$row->name;
	$reserve=$row->reserve;
} else {
	echo "<html><head><title></title></head><body onload=\"alert('ȸ�������� �������� �ʽ��ϴ�.');location.href='".$_SERVER[PHP_SELF]."?type=logout'\"></body></html>";exit;
}
mysql_free_result($result);

if($_POST['type']=='add') {
	$bank = $_POST['bank'];
	$cr_price = $_POST['cr_price'];

	if(!$bank || !$cr_price) {
		echo "<html><head><title></title></head><body onload=\"alert('������ ����� �Ѿ���� ���߽��ϴ�.');window.history.back();\"></body></html>";exit;
	}

	if($reserve<$cr_price) {
		echo "<html><head><title></title></head><body onload=\"alert('��ȯ��û �ݾ��� �߸� �Ǿ����ϴ�.');window.history.back();\"></body></html>";exit;
	}

	switch($_data->cr_limit){
		case 1 :
			$sdate1 = strtotime(date("Y-m-d")." 00:00");
			$sdate2 = strtotime(date("Y-m-d")." 23:59:59");

			$sql = "SELECT count(*) as cnt FROM tblcrinfo WHERE signdate >= {$sdate1} && signdate <= {$sdate2}";
			$result=mysql_query($sql,get_db_conn());
			$cnts=mysql_fetch_array($result);
			echo $sql;
			if($cnts['cnt']>0) {
				echo "<html><head><title></title></head><body onload=\"alert('��ȯ��û�� ��1ȸ�� ���� �մϴ�.');window.history.back();\"></body></html>";exit;
			}
		break;
		case 2 :
			$sdate1 = time()-(date('w')-1)*86400;
			$sdate2 = $sdate1 + (86400*6);
			$sdate1 = strtotime(date("Y-m-d",$sdate1)." 00:00");
			$sdate2 = strtotime(date("Y-m-d",$sdate2)." 23:59:59");

			$sql = "SELECT count(*) as cnt FROM tblcrinfo WHERE signdate >= {$sdate1} && signdate <= {$sdate2}";
			$result=mysql_query($sql,get_db_conn());
			$cnts=mysql_fetch_array($result);
			if($cnts['cnt']>0) {
				echo "<html><head><title></title></head><body onload=\"alert('��ȯ��û�� ��1ȸ�� ���� �մϴ�.');window.history.back();\"></body></html>";exit;
			}
		break;
		case 3 :
			$sdate1 = strtotime(date("Y-m")."-01 00:00");
			$sdate2 = strtotime(date("Y-m-t")." 23:59:59");

			$sql = "SELECT count(*) as cnt FROM tblcrinfo WHERE signdate >= {$sdate1} && signdate <= {$sdate2}";
			$result=mysql_query($sql,get_db_conn());
			$cnts=mysql_fetch_array($result);
			if($cnts['cnt']>0) {
				echo "<html><head><title></title></head><body onload=\"alert('��ȯ��û�� ��1ȸ�� ���� �մϴ�.');window.history.back();\"></body></html>";exit;
			}
		break;
	}

	$tmps = explode('|',$bank);
	if($tmps[0] && $tmps[1] && $tmps[2]) {
		$row = array();
		$row['name'] = $tmps[0];
		$row['bank_name'] = $tmps[1];
		$row['bank_num'] = $tmps[2];
	}
	else {
		$sql = "SELECT * FROM tblbankinfo WHERE id='{$id}' && uid='{$bank}'";
		$result=mysql_query($sql,get_db_conn());
		if(!$row=mysql_fetch_array($result)) {
			echo "<html><head><title></title></head><body onload=\"alert('���������� �߸� �Ǿ����ϴ�.');window.history.back();\"></body></html>";exit;
		}
		mysql_free_result($result);
	}

	if($bank && $cr_price) {
		$signdate = time();
		$sql = "INSERT INTO tblcrinfo SET id='{$id}', name='{$row['name']}', bank_name='{$row['bank_name']}', bank_num='{$row['bank_num']}', price='{$cr_price}', signdate='{$signdate}'";
		mysql_query($sql,get_db_conn());

		$sql = "INSERT tblreserve SET ";
		$sql.= "id			= '".$_ShopInfo->getMemid()."', ";
		$sql.= "reserve		= -{$cr_price}, ";
		$sql.= "reserve_yn	= 'N', ";
		$sql.= "content		= '������ȯ ������ ���', ";
		$sql.= "date		= '".date("YmdHis")."' ";
		mysql_query($sql,get_db_conn());

		$sql = "UPDATE tblmember SET reserve=if(reserve<".abs($cr_price).",0,reserve-".abs($cr_price).") WHERE id='".$id."' ";
		mysql_query($sql,get_db_conn());

		echo "<html><head><title></title></head><body onload=\"alert('��ȯ��û�� �Ϸ� �Ǿ����ϴ�.');window.location.href='".$Dir.FrontDir."mypage_cash01.php';\"></body></html>";exit;
	}

}

$cr_limit_arr = array("","�� 1ȸ","�� 1ȸ","�� 1ȸ");
if($_data->cr_edate) {
	$cr_date = $_data->cr_sdate."~".$_data->cr_edate;
}
else $cr_date = $_data->cr_sdate;

?>

<HTML>
<HEAD>
<TITLE><?=$_data->shoptitle?> - ������ ����</TITLE>
<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">

<META name="description" content="<?=(strlen($_data->shopdescription)>0?$_data->shopdescription:$_data->shoptitle)?>">
<META name="keywords" content="<?=$_data->shopkeyword?>">
<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
<script type="text/javascript" src="<?=$Dir?>lib/jquery-1.4.2.min.js"></script>
<?include($Dir."lib/style.php")?>
<SCRIPT type="text/JavaScript">
<!--

function checkForm(){
	f = document.cashForm;
	if(!f.cr_price.value) {
		alert('��ȯ�ݾ��� �Է� �Ͻñ� �ٶ��ϴ�');
		return false;
	}
	if (!isNumber(f.cr_price.value)) {
		alert("��ȯ�ݾ��� ���ڸ� �Է°��� �մϴ�.");
		return false;
	}
	if(f.cr_price.value<f.maxPrice.value) {
		alert("��������ȯ��û�� <?=number_format($_data->cr_maxprice)?>���̻� �����մϴ�");
		return false;
	}
	tmps = f.cr_price.value.length - (f.unit.value.length-1);
	f.cr_price.value = f.cr_price.value.substr(0,tmps) + f.unit.value.substr(1);

	if(!f.bank.value) {
		alert('�Աݰ��¸� ���� �Ͻñ� �ٶ��ϴ�');
		return false;
	}
	if(f.agree.checked==false) {
		alert('����� ���� �Ͻñ� �ٶ��ϴ�');
		return false;
	}

	alert("������ȯ��û�ݾ� �ּҴ����� <?=number_format($_data->cr_unit)?>�������Դϴ�. <?=number_format($_data->cr_unit)?>�������� �ڵ���ûó���˴ϴ١�. ��û��������ȯ��û������ Ȯ�����ּ���.");

	f.submit();
}
//-->
</SCRIPT>
</HEAD>
<body<?=(substr($_data->layoutdata["MOUSEKEY"],0,1)=="Y"?" oncontextmenu=\"return false;\"":"")?><?=(substr($_data->layoutdata["MOUSEKEY"],1,1)=="Y"?" ondragstart=\"return false;\" onselectstart=\"return false;\"":"")?> leftmargin="0" marginwidth="0" topmargin="0" marginheight="0"><?=(substr($_data->layoutdata["MOUSEKEY"],2,1)=="Y"?"<meta http-equiv=\"ImageToolbar\" content=\"No\">":"")?>

<? include ($Dir.MainDir.$_data->menu_type.".php") ?>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td valign="top">
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
$leftmenu="Y";
if($_data->design_myreserve=="U") {
	$sql="SELECT body,leftmenu FROM ".$designnewpageTables." WHERE type='myreserve'";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$body=$row->body;
		$body=str_replace("[DIR]",$Dir,$body);
		$leftmenu=$row->leftmenu;
		$newdesign="Y";
	}
	mysql_free_result($result);
}

if ($leftmenu!="N") {
	echo "<tr>\n";
	if ($_data->title_type=="Y" && file_exists($Dir.DataDir."design/myreserve_title.gif")) {
		echo "<td><img src=\"".$Dir.DataDir."design/myreserve_title.gif\" border=\"0\" alt=\"������ ����\"></td>\n";
	} else {
		echo "<td>\n";
		echo "<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>\n";
		echo "<TR>\n";
		echo "	<TD><IMG SRC=".$Dir."images/".$_data->icon_type."/myreserve_title_head.gif ALT=></TD>\n";
		echo "	<TD width=100% valign=top background=".$Dir."images/".$_data->icon_type."/myreserve_title_bg.gif></TD>\n";
		echo "	<TD width=40><IMG SRC=".$Dir."images/".$_data->icon_type."/myreserve_title_tail.gif ALT=></TD>\n";
		echo "</TR>\n";
		echo "</TABLE>\n";
		echo "</td>\n";
	}
	echo "</tr>\n";
}
if($_data->design_mypage =="001")
	$designMypage = "3";
else if($_data->design_mypage =="002")
	$designMypage = "2";
else if($_data->design_mypage =="003")
	$designMypage = "1";
else
	$designMypage = "3";
?>
	<tr>
		<td style="padding:5px;padding-top:0px;">
			<table align="center" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER="0" CELLPADDING="0" CELLSPACING="0">
				<TR>
					<TD><A HREF="<?=$Dir.FrontDir?>mypage.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin<?=$designMypage?>_menu1.gif" BORDER="0"></A></TD>
					<TD><A HREF="<?=$Dir.FrontDir?>mypage_orderlist.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin<?=$designMypage?>_menu2.gif" BORDER="0"></A></TD>
					<TD><A HREF="<?=$Dir.FrontDir?>mypage_personal.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin<?=$designMypage?>_menu3.gif" BORDER="0"></A></TD>
					<TD><A HREF="<?=$Dir.FrontDir?>wishlist.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin<?=$designMypage?>_menu4.gif" BORDER="0"></A></TD>
					<TD><A HREF="<?=$Dir.FrontDir?>mypage_reserve.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin<?=$designMypage?>_menu5r.gif" BORDER="0"></A></TD>
					<TD><A HREF="<?=$Dir.FrontDir?>mypage_coupon.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin<?=$designMypage?>_menu6.gif" BORDER="0"></A></TD>
					<?if($_data->recom_url_ok == "Y" || $_data->sns_ok == "Y"){?><TD><A HREF="<?=$Dir.FrontDir?>mypage_promote.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin<?=$designMypage?>_menu10.gif" BORDER="0"></A></TD><?}?>
					<TD><A HREF="<?=$Dir.FrontDir?>mypage_gonggu.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin<?=$designMypage?>_menu11.gif" BORDER="0"></A></TD>
					<? if(getVenderUsed()==true) { ?><TD><A HREF="<?=$Dir.FrontDir?>mypage_custsect.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin<?=$designMypage?>_menu9.gif" BORDER="0"></A></TD><? } ?>
					<TD><A HREF="<?=$Dir.FrontDir?>mypage_usermodify.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin<?=$designMypage?>_menu7.gif" BORDER="0"></A></TD>
					<TD><A HREF="<?=$Dir.FrontDir?>mypage_memberout.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin<?=$designMypage?>_menu8.gif" BORDER="0"></A></TD>
					<TD width="100%" background="<?=$Dir?>images/common/mypersonal_skin<?=$designMypage?>_menubg.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height="20"></td>
			</tr>
			<tr>
				<td>
					<form name="cashForm" method="post" action="">
					<input type="hidden" name="bank" >
					<input type="hidden" name="type" value="add">
					<input type="hidden" name="maxPrice" value="<?=$_data->cr_maxprice;?>">
					<input type="hidden" name="unit" value="<?=$_data->cr_unit?>">
					<TABLE cellSpacing=0 cellPadding=0 width="100%">
					<TR>
						<TD><IMG SRC="../images/design/promote3_cash_tap01r.gif" WIDTH=107 HEIGHT=31 ALT="" border="0"><a href="../front/mypage_cash02.php"><IMG SRC="../images/design/promote3_cash_tap02.gif" WIDTH=117 HEIGHT=31 ALT="" border="0"></a></TD>
					</TR>
					<TR>
						<TD><img src="../images/design/con_line01.gif" width="100%" height="2" border="0"></TD>
					</TR>
					<TR>
						<TD height="15"></TD>
					</TR>
					<TR>
						<TD class="table_td"><IMG vspace=3 align=absMiddle src="../images/design/icon_star.gif">������ȯ ��û �� ���� ���µ���� �ʼ��Դϴ�.<img src="../images/design/promote3_cash_btn01.gif" align="absmiddle" width="64" height="26" border="0" style="cursor:pointer" id="accountReg"><br><IMG vspace=3 align=absMiddle src="../images/design/icon_star.gif"><?=number_format($_data->cr_maxprice)?>�� �̻� ����ݾ��� ���� ��� ������ȯ�� �����ϸ� ����ݾ� ������ ȸ���� ���� ���·� ��� ��û �Ͻ� �� �ֽ��ϴ�.<br><IMG vspace=3 align=absMiddle src="../images/design/icon_star.gif">������ȯ ��û���� ��ȯ ��û �� ������ ���� <?=$cr_date?>�� �̳� �Ա� ó���˴ϴ�. <br><IMG vspace=3 align=absMiddle src="../images/design/icon_star.gif">��ȯ��û�� <?=$cr_limit_arr[$_data->cr_limit]?> <?=number_format($_data->cr_unit)?>�� �����θ� �����մϴ�.</TD>
					</TR>
					<TR>
						<TD height="20"></TD>
					</TR>
					<TR>
						<TD>

							<TABLE cellSpacing=0 cellPadding=0 width="100%">
							<TR>
								<TD bgColor="#e4e4e4" height=1 colSpan="2"></TD>
							</TR>
							<TR height=34>
								<TD class="mypage_text1" bgcolor="#F8F8F8" width="160" align="center">��ȯ������ ������</TD>
								<TD class="mypage_text1s" align="left"><?=number_format($reserve)?></TD>
							</TR>
							<TR>
								<TD bgColor="#e4e4e4" height=1 colSpan="2"></TD>
							</TR>
							<TR height=34>
								<TD class="mypage_text1" bgcolor="#F8F8F8" width="160" align="center">��ȯ�ݾ�</TD>
								<TD class="mypage_text1s">
									<input type="text" name="cr_price" value="<?=$_data->cr_maxprice;?>">��
								</TD>
							</tr>
							<TR>
								<TD bgColor="#e4e4e4" height=1 colSpan="2"></TD>
							</TR>
							<TR height=34>
								<TD class="mypage_text1" bgcolor="#F8F8F8" width="160" align="center">�Աݰ���</TD>
								<TD class="mypage_text1s"><IMG SRC="../images/design/promote3_cash_btn04.gif" WIDTH=58 HEIGHT=19 ALT="" border="0" style="cursor:pointer" id="accountSel"><IMG SRC="../images/design/promote3_cash_btn05.gif" WIDTH=73 HEIGHT=19 ALT="" border="0" style="cursor:pointer" id="accountlatest"></a></TD>
							</tr>
							<TR>
								<TD bgColor="#e4e4e4" height=1 colSpan="2"></TD>
							</TR>
							<TR height=34>
								<TD class="mypage_text1" bgcolor="#F8F8F8" width="160" align="center">���</TD>
								<TD class="mypage_text1s">�������� ��ȯ�� �������� �ٽ� �����Ͻ� �� �����ϴ�.�����Ͻʴϱ�? &nbsp;&nbsp; <input type="checkbox" name="agree" value='1'>����</TD>
							</tr>
							<TR>
								<TD bgColor="#e4e4e4" height=1 colSpan="2"></TD>
							</TR>
							</TABLE>
						</TD>
					</TR>
					<TR>
						<TD height="20"></TD>
					</TR>
					<TR>
						<TD align="center"><a href="#" onclick="checkForm();"><IMG SRC="../images/design/promote3_cash_btn02.gif" WIDTH=101 HEIGHT=67 ALT=""></a></TD>
					</TR>
					</TABLE>
					</form>
				</td>
			</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="60"></td>
	</tr>
	</table>
</td>
</tr>
</table>


<div id="dvPopup" style="display:none; width:350px; border:0px ; background-color:#FFFFFF;margin:auto">
</div>
<SCRIPT type="text/javascript">
<!--
var viewportScroll = $j(window).scrollTop();
var cssLeft = document.body.clientWidth/2 - 150;
var cssTop = document.body.clientHeight/2 - 100 + viewportScroll;

function showPopDiv(){
	$j("#dvPopup").css({'position':'absolute','top':cssTop+'px','left':cssLeft+'px','z-index':'1000'}).show();
}
function hidePopDiv(){
	$j("#dvPopup").html("");
	$j("#dvPopup").hide();
}
$j("#accountReg").click(function () {
	showPopDiv();
	$j("#dvPopup").load("./mypage_bank.html");
});
$j("#accountSel").click(function () {
	showPopDiv();
	$j("#dvPopup").load("./mypage_bank_sec.html",{'gbn':"1"});
});
$j("#accountlatest").click(function () {
	showPopDiv();
	$j("#dvPopup").load("./mypage_bank_sec.html",{'gbn':"2"});
});
//-->
</SCRIPT>

<? include ($Dir."lib/bottom.php") ?>

</BODY>
</HTML>
