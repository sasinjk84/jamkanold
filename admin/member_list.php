<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "me-1";
$MenuCode = "member";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$setup[page_num] = 10;
$setup[list_num] = 20;

$type=$_POST["type"];
$id=$_POST["id"];
$ids=$_POST["ids"];
$email=$_POST["email"];
$mem_name =$_POST["mem_name"];

$devices=( empty($_POST["devices"]) ? "All" : $_POST["devices"] );

if ($type=="confirm_ok") {
	$sql = "UPDATE tblmember SET confirm_yn='Y' WHERE id='".$id."' ";
	mysql_query($sql,get_db_conn());
	if (strlen($email)>0) {
		SendAuthMail($_shopdata->shopname, $shopurl, $_shopdata->design_mail, $_shopdata->info_email, $email, $id, $mem_name);
	}

	$sql2= "SELECT * FROM tblsmsinfo WHERE mem_auth = 'Y' ";
	$result2 = mysql_query($sql2,get_db_conn());
	if($row2=mysql_fetch_object($result2)){
		$sql3 = "SELECT mobile, name FROM tblmember WHERE id='".$id."'";
		$result3 = mysql_query($sql3,get_db_conn());
		$row3 = mysql_fetch_object($result3);
		mysql_free_result($result3);
		$sms_id=$row2->id;
		$sms_authkey=$row2->authkey;

		$row3->mobile=ereg_replace(",","",$row3->mobile);
		$row3->mobile=ereg_replace("-","",$row3->mobile);
		$toname= $row3->name;
		$totel= $row3->mobile;

		$msg=$row2->msg_mem_auth;
		$patten=array("(\[NAME\])");
		$replace=array($toname);
		$msg=preg_replace($patten,$replace,$msg);
		$msg=AddSlashes($msg);
		$fromtel=$row2->return_tel;
		$etcmsg="ȸ������ �ȳ��޼���";
		$date=0;
		$res=SendSMS($sms_id, $sms_authkey, $totel, "", $fromtel, $date, $msg, $etcmsg);
	}
} else if ($type=="confirm_cancel") {
	$sql = "UPDATE tblmember SET confirm_yn = 'N' WHERE id = '".$id."' ";
	mysql_query($sql,get_db_conn());
	/*echo "<script>history.go(-1);</script>"; exit;*/
} else if ($type=="member_out" && strlen($id)>0) {	//���� ȸ������
	$idval=substr($id,0,-3);
	$arr_id=explode("|=|",$idval);
	for($i=0;$i<count($arr_id);$i++) {
		$outid=$arr_id[$i];
		$sql = "SELECT COUNT(*) as cnt FROM tblorderinfo WHERE id='".$outid."'";
		$result= mysql_query($sql,get_db_conn());
		$row = mysql_fetch_object($result);
		mysql_free_result($result);
		if ($row->cnt==0) {
			$sql = "DELETE FROM tblmember WHERE id = '".$outid."'";
		} else {
			$sql = "UPDATE tblmember SET ";
			$sql.= "passwd			= '', ";
			$sql.= "resno			= '', ";
			$sql.= "email			= '', ";
			$sql.= "news_yn			= 'N', ";
			$sql.= "age				= '', ";
			$sql.= "gender			= '', ";
			$sql.= "job				= '', ";
			$sql.= "birth			= '', ";
			$sql.= "home_post		= '', ";
			$sql.= "home_addr		= '', ";
			$sql.= "home_tel		= '', ";
			$sql.= "mobile			= '', ";
			$sql.= "office_post		= '', ";
			$sql.= "office_addr		= '', ";
			$sql.= "office_tel		= '', ";
			$sql.= "memo			= '', ";
			$sql.= "reserve			= 0, ";
			$sql.= "joinip			= '', ";
			$sql.= "ip				= '', ";
			$sql.= "authidkey		= '', ";
			$sql.= "group_code		= '', ";
			$sql.= "member_out		= 'Y', ";
			$sql.= "etcdata			= '' ";
			$sql.= "WHERE id = '".$outid."'";
		}
		mysql_query($sql,get_db_conn());
		$sql = "DELETE FROM tblreserve WHERE id='".$outid."'";
		mysql_query($sql,get_db_conn());
		$sql = "DELETE FROM tblcouponissue WHERE id='".$outid."'";
		mysql_query($sql,get_db_conn());
		$sql = "DELETE FROM tblmemo WHERE id='".$outid."'";
		mysql_query($sql,get_db_conn());
		$sql = "DELETE FROM tblrecommendmanager WHERE rec_id='".$outid."'";
		mysql_query($sql,get_db_conn());
		$sql = "DELETE FROM tblrecomendlist WHERE id='".$outid."'";
		mysql_query($sql,get_db_conn());
		$sql = "DELETE FROM tblpersonal WHERE id='".$outid."'";
		mysql_query($sql,get_db_conn());
	}
	//�α� insert
	$log_content = "## ȸ������ : ID:".str_replace("|=|",",",$idval)." ##";
	ShopManagerLog($_ShopInfo->getId(),$connect_ip,$log_content);

	$onload="<script>alert('�����Ͻ� ȸ�� ".count($arr_id)."���� Ż��ó�� �Ͽ����ϴ�.\\n\\n���ų����� �ִ� ��쿡�� ȸ�� �⺻������ �����˴ϴ�.');</script>";
}

$regdate = $_shopdata->regdate;
$CurrentTime = time();
//$period[0] = substr($regdate,0,4)."-".substr($regdate,4,2)."-".substr($regdate,6,2);
$period[0] = "2001-01-01";
$period[1] = date("Y-m-d",$CurrentTime);
$period[2] = date("Y-m-d",$CurrentTime-(60*60*24*7));
$period[3] = date("Y-m",$CurrentTime)."-01";
$period[4] = date("Y",$CurrentTime)."-01-01";

$sort=(int)$_POST["sort"];
$scheck=(int)$_POST["scheck"];
$group_code=$_POST["group_code"];
$search_start=$_POST["search_start"];
$search_end=$_POST["search_end"];
$vperiod=(int)$_POST["vperiod"];
$search=$_POST["search"];
$search_start=$search_start?$search_start:$period[0];
$search_end=$search_end?$search_end:date("Y-m-d",$CurrentTime);

$block=$_REQUEST["block"];
$gotopage=$_REQUEST["gotopage"];

${"check_sort".$sort} = "checked";
${"check_scheck".$scheck} = "checked";
${"check_vperiod".$vperiod} = "checked";

if ($scheck == "6") {
	$sort_disabled = "disabled";
}

$display_group_code="none";
if($scheck==7) $display_group_code="";
$display_todaylogin="";
if($scheck==8) $display_todaylogin="none";

$ArrSort = array("date","name","id","age","reserve");
$ArrScheck = array("id","name","email","resno","home_tel","mobile","rec_id","group_code","logindate");

if ($block != "") {
	$nowblock = $block;
	$curpage  = $block * $setup[page_num] + $gotopage;
} else {
	$nowblock = 0;
}

if (($gotopage == "") || ($gotopage == 0)) {
	$gotopage = 1;
}

?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script type="text/javascript" src="calendar.js.php"></script>
<SCRIPT LANGUAGE="JavaScript">
<!--
function member_baro(type,id,email,mem_name) {
	var msg = "";
	if (type=="ok") msg="["+id+"] ���� ���� �Ͻðڽ��ϱ�?";
	else if (type=="cancel") msg="["+id+"] ���� ������ ����Ͻðڽ��ϱ�?";
	if (confirm(msg)) {
		document.form1.type.value="confirm_"+type;
		document.form1.id.value=id;
		document.form1.email.value=email;
		document.form1.mem_name.value=mem_name;
		document.form1.submit();
	}
}

function check() {
	if (document.form1.search.value.length==0) {
		tmsg="";
		if(document.form1.scheck[8].checked==false) tmsg="�˻��Ⱓ ������ ";
		if(document.form1.scheck[7].checked==true && document.form1.group_code.value.length==0) {
			alert("��ȸ�Ͻ� ȸ�� ����� �����ϼ���.");
			document.form1.group_code.focus();
			return;
		}
		if (confirm(tmsg+"��ü ��ȸ�Ͻðڽ��ϱ�?")) {
			document.form1.submit();
		}
	} else {
		document.form1.submit();
	}
}

function searchcheck() {
	key=event.keyCode;
	if (key==13) { check(); }
}

function OnChangePeriod(val) {
	var pForm = document.form1;
	var period = new Array(7);
	period[0] = "<?=$period[0]?>";
	period[1] = "<?=$period[1]?>";
	period[2] = "<?=$period[2]?>";
	period[3] = "<?=$period[3]?>";
	period[4] = "<?=$period[4]?>";

	pForm.search_start.value = period[val];
	pForm.search_end.value = period[1];
}
function OnChangeSearchType(val) {
	if (val == 6) {
		for(var i=0;i<document.form1.sort.length;i++) {
			document.form1.sort[i].disabled = true;
		}
	} else {
		for(var i=0;i<document.form1.sort.length;i++) {
			document.form1.sort[i].disabled = false;
		}
	}

	if (val == 7) {
		document.all.div_todaylogin.style.display = "";
		document.all.div_todaylogin2.style.display = "";
		document.all.div_group_code.style.display = "";
		document.all.div_group_code2.style.display = "";
	} else {
		document.all.div_group_code.style.display = "none";
		document.all.div_group_code2.style.display = "none";
		if (val == 8) {
			document.all.div_todaylogin.style.display = "none";
			document.all.div_todaylogin2.style.display = "none";
		} else {
			document.all.div_todaylogin.style.display = "";
			document.all.div_todaylogin2.style.display = "";
		}
	}
}

function CheckAll(){
	chkval=document.form1.allcheck.checked;
	cnt=document.form1.tot.value;
	for(i=1;i<=cnt;i++){
		document.form1.ids_chk[i].checked=chkval;
	}
}

function check_del() {
	document.form1.id.value="";
	for(i=1;i<document.form1.ids_chk.length;i++) {
		if(document.form1.ids_chk[i].checked==true) {
			document.form1.id.value+=document.form1.ids_chk[i].value+"|=|";
		}
	}
	if(document.form1.id.value.length==0) {
		alert("�����Ͻ� ȸ�����̵� �����ϴ�.");
		return;
	}
	if(confirm("�����Ͻ� ȸ�����̵� Ż��ó�� �Ͻðڽ��ϱ�?")) {
		document.form1.type.value="member_out";
		document.form1.submit();
	}
}

function MemberInfo(id) {
	window.open("about:blank","infopop","width=567,height=600,scrollbars=yes");
	document.form3.target="infopop";
	document.form3.id.value=id;
	document.form3.action="member_infopop.php";
	document.form3.submit();
}

function LostPass(id) {
	window.open("about:blank","lostpasspop","width=350,height=200,scrollbars=no");
	document.form3.target="lostpasspop";
	document.form3.id.value=id;
	document.form3.action="member_lostpasspop.php";
	document.form3.submit();
}

function ReserveInOut(id){
	window.open("about:blank","reserve_set","width=245,height=140,scrollbars=no");
	document.reserveform.target="reserve_set";
	document.reserveform.id.value=id;
	document.reserveform.type.value="reserve";
	document.reserveform.submit();
}


function ReserveInfo(id) {
	window.open("about:blank","reserve_info","width=500,height=400,scrollbars=yes");
	document.form2.id.value=id;
	document.form2.submit();
}

function OrderInfo(id) {
	window.open("about:blank","orderinfo","width=414,height=320,scrollbars=yes");
	document.form3.target="orderinfo";
	document.form3.id.value=id;
	document.form3.action="orderinfopop.php";
	document.form3.submit();
}

function CouponInfo(id) {
	window.open("about:blank","couponinfo","width=600,height=400,scrollbars=yes");
	document.form3.target="couponinfo";
	document.form3.id.value=id;
	document.form3.action="coupon_listpop.php";
	document.form3.submit();
}

function MemberMail(mail,news_yn){
	if(news_yn!="Y" && news_yn!="M" && !confirm("�ش� ȸ���� ���ϼ����� �ź��Ͽ����ϴ�.\n\n������ �߼��Ͻ÷��� Ȯ�� ��ư�� Ŭ���Ͻñ� �ٶ��ϴ�.")) {
		return;
	}
	document.mailform.rmail.value=mail;
	document.mailform.submit();
}

function MemberSMS(news_yn,tel1,tel2) {
	if(news_yn!="Y" && news_yn!="S") {
		if(!confirm("SMS���Űź� ȸ���Դϴ�.\n\nSMS�� �߼��Ͻ÷��� \"Ȯ��\"�� �����ּ���.")) {
			return;
		}
	}
	number=tel1+"|"+tel2;
	document.smsform.number.value=number;
	window.open("about:blank","sendsmspop","width=220,height=350,scrollbars=no");
	document.smsform.submit();
}

function MemberMemo2(id) {
	window.open("about:blank","memopop","width=350,height=350,scrollbars=no");
	document.form3.target="memopop";
	document.form3.id.value=id;
	document.form3.action="member_memopop.php";
	document.form3.submit();
}
function MemberEtcView(id) {
	window.open("about:blank","etcpop","width=350,height=350,scrollbars=no");
	document.form3.target="etcpop";
	document.form3.id.value=id;
	document.form3.action="member_etcpop.php";
	document.form3.submit();
}

function excel_download() {
	if(confirm("�˻��� ��� ȸ�������� �ٿ�ε� �Ͻðڽ��ϱ�?")) {
		document.excelform.submit();
	}
}

function GoPage(block,gotopage) {
	document.idxform.block.value = block;
	document.idxform.gotopage.value = gotopage;
	document.idxform.submit();
}

//-->
</SCRIPT>
<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
<tr>
	<td valign="top">
	<table cellpadding="0" cellspacing="0" width=100% style="table-layout:fixed">
	<tr>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed"  background="images/con_bg.gif">
		<?
			if( $popup != "OK" ) {
				echo "
					<col width=198></col>
					<col width=10></col>
					<col width=></col>
				";
			}
		?>
		<tr>
			<?
				if( $popup != "OK" ) {
					echo "<td valign=\"top\" background=\"images/leftmenu_bg.gif\">";
					include ("menu_member.php");
					echo "</td><td></td>";
				}
			?>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ȸ������ &gt; ȸ���������� &gt; <span class="2depth_select">ȸ����������</span></td>
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
					<TD><IMG SRC="images/member_list_title.gif"  ALT=""></TD>
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
					<TD background="images/distribute_04.gif"></TD>
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
					<TD width="100%" class="notice_blue">ȸ���� �˻��ϰų� ȸ�� �󼼳����� ��ȸ/����/Ż��/��ȣ����/��Ÿ ó���� �� �� �ֽ��ϴ�.</TD>
					<TD background="images/distribute_07.gif"></TD>
				</TR>
				<TR>
					<TD><IMG SRC="images/distribute_08.gif"></TD>
					<TD COLSPAN=2 background="images/distribute_09.gif"></TD>
					<TD><IMG SRC="images/distribute_10.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=20></td></tr>
			<?
				if( $popup != "OK" ) {
			?>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/member_list_stitle1.gif" WIDTH="192" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=3></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/distribute_01.gif"></TD>
					<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
					<TD><IMG SRC="images/distribute_03.gif"></TD>
				</TR>
				<TR>
					<TD background="images/distribute_04.gif"></TD>
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
					<TD width="100%" class="notice_blue">1) ȸ������ �˻��� �ݵ�� �˻������� �����ϼ���.<br>2) �˻��� �Է½� ���� �Ǵ� Ư������(- / ~)�� ���������� �˻����� �ʽ��ϴ�.</span></b></TD>
					<TD background="images/distribute_07.gif"></TD>
				</TR>
				<TR>
					<TD><IMG SRC="images/distribute_08.gif"></TD>
					<TD COLSPAN=2 background="images/distribute_09.gif"></TD>
					<TD><IMG SRC="images/distribute_10.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<?
				}
			?>



			<tr><td height=3></td></tr>
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=type>
			<input type=hidden name=id>
			<input type=hidden name=email>
			<input type=hidden name="mem_name">


			<?
				if( $popup != "OK" ) {
			?>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<col width=139></col>
				<col width=></col>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">���Ĺ�� ����</TD>
					<TD class="td_con1">
					<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
					<TR>
						<TD width="30%"><input style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;" type=radio name=sort value="0" id=idx_sort0 <?=$check_sort0?> <?=$sort_disabled?>> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_sort0>����� �������� ����</label></TD>
						<TD width="30%"><input  style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;" type=radio name=sort value="1" id=idx_sort1 <?=$check_sort1?> <?=$sort_disabled?>> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_sort1>ȸ���� �������� ����</label></TD>
						<TD width="40%"><input  style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;" type=radio name=sort value="2" id=idx_sort2 <?=$check_sort2?> <?=$sort_disabled?>> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_sort2>���̵� �������� ����</label></TD>
					</TR>
					<TR>
						<TD width="30%"><input  style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;" type=radio name=sort value="3" id=idx_sort3 <?=$check_sort3?> <?=$sort_disabled?>> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_sort3>���̼� �������� ����</label></TD>
						<TD width="30%"><input  style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;" type=radio name=sort value="4" id=idx_sort4 <?=$check_sort4?> <?=$sort_disabled?>> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_sort4>������ �������� ����</label></TD>
						<TD width="40%">&nbsp;</TD>
					</TR>
					</TABLE>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�˻����� ����</TD>
					<TD class="td_con1">
					<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
					<TR>
						<TD width="30%"><input  style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;" type=radio name=scheck value="0" id=idx_scheck0 onClick="OnChangeSearchType(this.value);" <?=$check_scheck0?>> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_scheck0>ȸ�� ���̵�� �˻�</label></TD>
						<TD width="30%"><input  style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;" type=radio name=scheck value="1" id=idx_scheck1 onClick="OnChangeSearchType(this.value);" <?=$check_scheck1?>> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_scheck1>ȸ����� �˻�</label></TD>
						<TD width="40%"><input  style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;" type=radio name=scheck value="2" id=idx_scheck2 onClick="OnChangeSearchType(this.value);" <?=$check_scheck2?>> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_scheck2>�̸��Ϸ� �˻�</label></TD>
					</TR>
					<TR>
						<TD width="30%"><input  style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;" type=radio name=scheck value="3" id=idx_scheck3 onClick="OnChangeSearchType(this.value);" <?=$check_scheck3?>> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_scheck3>�ֹε�Ϲ�ȣ(����ڹ�ȣ)�� �˻�</label></TD>
						<TD width="30%"><input  style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;" type=radio name=scheck value="4" id=idx_scheck4 onClick="OnChangeSearchType(this.value);" <?=$check_scheck4?>> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_scheck4>��ȭ��ȣ�� �˻�</label></TD>
						<TD width="40%"><input  style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;" type=radio name=scheck value="5" id=idx_scheck5 onClick="OnChangeSearchType(this.value);" <?=$check_scheck5?>> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_scheck5>�ڵ��� ��ȣ�� �˻�</label></TD>
					</TR>
					<TR>
						<TD width="30%"><input  style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;" type=radio name=scheck value="6" id=idx_scheck6 onClick="OnChangeSearchType(this.value);" <?=$check_scheck6?>> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_scheck6>��õ�� �˻�</label></TD>
						<TD width="30%"><input  style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;" type=radio name=scheck value="7" id=idx_scheck7 onClick="OnChangeSearchType(this.value);" <?=$check_scheck7?>> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_scheck7>���ȸ�� �˻�</label></TD>
				<?
						$sql = "SELECT login_cnt FROM tblshopcountday WHERE date = '".date("Ymd")."'";
						$result = mysql_query($sql,get_db_conn());
						if ($row = mysql_fetch_object($result)) {
							$todaylogin = $row->login_cnt;
						} else {
							$todaylogin = 0;
						}
						mysql_free_result($result);
				?>
						<TD width="40%"><input  style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;" type=radio name=scheck value="8" id=idx_scheck8 onClick="OnChangeSearchType(this.value);" <?=$check_scheck8?>> <label style='cursor:hand; TEXT-DECORATION: none;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_scheck8>���� �α��� ȸ�� �˻� <B><!-- (�� <?=$todaylogin?>��) --></B></label></TD>
					</TR>
					</TABLE>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<? if($_shopdata->wholesalemember == 'Y'){ // ����ȸ�� ����?>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">ȸ�� Ÿ��</TD>
					<TD class="td_con1">
						<input type="radio" name="wholesale" value="A" <? if($_REQUEST['wholesale']=='' OR $_REQUEST['wholesale'] == 'A') echo 'checked'; ?> />��üȸ��
						<input type="radio" name="wholesale" value="D" <? if($_REQUEST['wholesale'] == 'D') echo 'checked'; ?> />�Ϲ�ȸ��
						<input type="radio" name="wholesale" value="Y" <? if($_REQUEST['wholesale'] == 'Y') echo 'checked'; ?> />����ȸ��
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<? } ?>
				<TR id="div_group_code" style="display:<?=$display_group_code?>;">
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">ȸ����� ����</TD>
					<TD class="td_con1"><select name=group_code style="width:300px" class="input_selected">
					<option value="">ȸ�� ����� �����ϼ���.</option>
				<?
					$sql = "SELECT group_code,group_name FROM tblmembergroup ";
					$result = mysql_query($sql,get_db_conn());
					$count = 0;
					while ($row=mysql_fetch_object($result)) {
						echo "<option value='".$row->group_code."'";
						$group_name[$row->group_code]=$row->group_name;
						if($group_code==$row->group_code){
							//$subject=$row->subject;
							echo " selected";
						}
						echo ">".$row->group_name."</option>";
					}
					mysql_free_result($result);
				?>
					</select>
					</TD>
				</TR>
				<TR id="div_group_code2" style="display:<?=$display_group_code?>;">
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>



				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">���� ��� ��</TD>
					<TD class="td_con1">
						<input type=radio id="idx_device0" name=devices value="All" <?if($devices=="All")echo"checked";?>>
						<label style='cursor:hand;' onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for=idx_device0>��ü</label>
						&nbsp;&nbsp;&nbsp;
						<input type=radio id="idx_device2" name=devices value="P" <?if($devices=="P")echo"checked";?>>
						<label style='cursor:hand;' onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for=idx_device2>PC��</label>
						&nbsp;&nbsp;&nbsp;
						<input type=radio id="idx_device1" name=devices value="M" <?if($devices=="M")echo"checked";?>>
						<label style='cursor:hand;' onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for=idx_device1>�����</label>
						<!-- &nbsp;&nbsp;&nbsp;
						<input type=radio id="idx_device2" name=devices value="A" <?if(devices=="A")echo"checked";?>>
						<label style='cursor:hand;' onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for=idx_device2>App</label> -->
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>



				<TR id="div_todaylogin" style="display:<?=$display_todaylogin?>;">
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�˻��Ⱓ ����</TD>
					<TD class="td_con1"><input type=text name=search_start value="<?=$search_start?>" size=10 onfocus="this.blur();" OnClick="Calendar(this)" class="input_selected"> ~ <input type=text name=search_end value="<?=$search_end?>" size=10 onfocus="this.blur();" OnClick="Calendar(this)" class="input_selected">
					<input type=radio id=idx_vperiod0 name=vperiod value="0" checked style="BORDER-RIGHT: 0px; BORDER-TOP: 0px; BORDER-LEFT: 0px; BORDER-BOTTOM: 0px;" onclick="OnChangePeriod(this.value)" <?=$check_vperiod0?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_vperiod0>��ü</label>
					<input type=radio id=idx_vperiod1 name=vperiod value="1" style="BORDER-RIGHT: 0px; BORDER-TOP: 0px; BORDER-LEFT: 0px; BORDER-BOTTOM: 0px;" onclick="OnChangePeriod(this.value)" <?=$check_vperiod1?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_vperiod1>����</label>
					<input type=radio id=idx_vperiod2 name=vperiod value="2" style="BORDER-RIGHT: 0px; BORDER-TOP: 0px; BORDER-LEFT: 0px; BORDER-BOTTOM: 0px;" onclick="OnChangePeriod(this.value)" <?=$check_vperiod2?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_vperiod2>1����</label>
					<input type=radio id=idx_vperiod3 name=vperiod value="3" style="BORDER-RIGHT: 0px; BORDER-TOP: 0px; BORDER-LEFT: 0px; BORDER-BOTTOM: 0px;" onclick="OnChangePeriod(this.value)" <?=$check_vperiod3?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_vperiod3>�̴�</label>
					<input type=radio id=idx_vperiod4 name=vperiod value="4" style="BORDER-RIGHT: 0px; BORDER-TOP: 0px; BORDER-LEFT: 0px; BORDER-BOTTOM: 0px;" onclick="OnChangePeriod(this.value)" <?=$check_vperiod4?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_vperiod4>����</label></TD>
				</TR>
				<TR id="div_todaylogin2" style="display:<?=$display_todaylogin?>;">
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�˻��� �Է�</TD>
					<TD class="td_con1"><input name=search size=40 value="<?=$search?>" onKeyDown="searchcheck()" class="input"> <a href="javascript:check();"><img src="images/btn_search3.gif" width="77" height="25" border="0" align=absmiddle></a></TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height="30">&nbsp;</td>
			</tr>
			<?
				}
			?>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/member_list_stitle2.gif" WIDTH="192" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=3></td></tr>
<?
			$date_start = str_replace("-","",$search_start)."000000";
			$date_end = str_replace("-","",$search_end)."235959";
			if ($scheck=="6") {			//��õ�� �˻�
				$searchsql = "AND a.date >= '".$date_start."' AND a.date <= '".$date_end."' ";
				$searchsql.= "AND b.member_out = 'N' ";
				if($search) {
					$searchsql.= "AND a.rec_id LIKE '".$search."%' ";
				}
				$sql0 = "SELECT b.id FROM tblrecomendlist a, tblmember b ";
				$sql0.= "WHERE a.rec_id=b.id ".$searchsql;
				$sql0.= "GROUP BY a.rec_id ";
				$sql = "SELECT COUNT(a.rec_id) as rec_cnt, b.*,g.group_name FROM tblrecomendlist a, tblmember b left join tblmembergroup g on g.group_code = b.group_code ";
				$sql.= "WHERE a.rec_id=b.id ".$searchsql;
				$sql.= "GROUP BY a.rec_id ORDER BY rec_cnt DESC ";
				$sql.= "LIMIT " . ($setup[list_num] * ($gotopage - 1)) . ", " . $setup[list_num];
				$result = mysql_query($sql0,get_db_conn());
				$t_count = mysql_num_rows($result);
				mysql_free_result($result);
			} else if ($scheck=="7") {	//���ȸ�� �˻�
				$searchsql = "AND date >= '".$date_start."' AND date <= '".$date_end."' ";
				if ($group_code) {
					$searchsql.= "AND group_code = '".$group_code."' ";	//�ش� ���ȸ��
				} else {
					$searchsql.= "AND group_code != '' ";				//��� ���ȸ��
				}
				if ($search) {
					$searchsql.= "AND id LIKE '".$search."%' ";
				}
				$sql0 = "SELECT COUNT(*) as cnt FROM tblmember WHERE 1=1 ";
				$sql0.= $searchsql;
				$sql = "SELECT * FROM tblmember WHERE 1=1 ";
				$sql.= $searchsql." ";
			} else if ($scheck=="8") {	//���� �α��� ȸ�� �˻�
				if ($search) {
					$searchsql = "AND id LIKE '".$search."%' ";
				}
				$sql0 = "SELECT COUNT(*) as cnt FROM tblmember ";
				$sql0.= "WHERE logindate >= '".date("Ymd")."000000' ".$searchsql;
				$sql = "SELECT * FROM tblmember WHERE logindate >= '".date("Ymd")."000000' ".$searchsql." ";
			} else {
				$searchsql = "AND date >= '".$date_start."' AND date <= '".$date_end."' ";
				if ($search) {
					//$searchsql.= "AND ".$ArrScheck[$scheck]." LIKE '".$search."%' ";
					if($ArrScheck[$scheck]=="resno"){
						$searchsql.= "AND (".$ArrScheck[$scheck]." LIKE '".$search."%' OR bizno LIKE '%".$search."%') ";
					}else{
						$searchsql.= "AND ".$ArrScheck[$scheck]." LIKE '".$search."%' ";
					}
				}
				$sql0 = "SELECT COUNT(*) as cnt FROM tblmember WHERE 1=1 ".$searchsql;
				$sql = "SELECT * FROM tblmember WHERE 1=1 ".$searchsql." ";
			}

			// ����ȸ�� ���� �߰�
			if($_shopdata->wholesalemember == 'Y'){
				if($_REQUEST['wholesale'] =='Y'){
					$sql0 .= " and wholesaletype in ('Y','R') ";
					$sql .= " and wholesaletype in ('Y','R') ";

				}

				if($_REQUEST['wholesale'] =='D'){
					$sql0 .= " and (isnull(wholesaletype) || length(wholesaletype) < 1 || ( wholesaletype != 'Y' and wholesaletype !='R')) ";
					$sql .= " and  (isnull(wholesaletype) || length(wholesaletype) < 1 || ( wholesaletype != 'Y' and wholesaletype !='R')) ";
				}
			}



			// ���Ա�� �˻�
			if( $devices != "All" ) {
				$sql.= "AND devices = '".$devices."' ";
			}


			if ($scheck!="6") {
				$result = mysql_query($sql0,get_db_conn());
				$row = mysql_fetch_object($result);
				$t_count = $row->cnt;
				unset($row);
				mysql_free_result($result);
				switch ($sort) {
					case "0":	//�����
						$sql.= "ORDER BY date DESC ";
						break;
					case "1":	//ȸ����
						$sql.= "ORDER BY name ASC ";
						break;
					case "2":	//���̵�
						$sql.= "ORDER BY id ASC ";
						break;
					case "3":	//���̼�
						$sql.= "ORDER BY resno ASC ";
						break;
					case "4":	//������
						$sql.= "ORDER BY reserve DESC ";
						break;
					default :	//�����
						$sql.= "ORDER BY date DESC ";
						break;
				}
				$sql.= "LIMIT " . ($setup[list_num] * ($gotopage - 1)) . ", " . $setup[list_num];
			}
			#echo $sql; exit;
			$pagecount = (($t_count - 1) / $setup[list_num]) + 1;
?>
			<tr>
				<td style="padding-bottom:3pt;">
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/distribute_01.gif"></TD>
					<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
					<TD><IMG SRC="images/distribute_03.gif"></TD>
				</TR>
				<TR>
					<TD background="images/distribute_04.gif"></TD>
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
					<TD width="100%" class="notice_blue">
					<table border=0 cellpadding=0 cellspacing=0 width=100%>
					<tr>
						<td class="notice_blue"><p>��ü <span class="font_orange"><B><?=$t_count?></B></span>�� ��ȸ, ���� <span class="font_orange"><B><?=$gotopage?>/<?=ceil($t_count/$setup[list_num])?></B></span> ������</p></td>
						<td align=right><a href="javascript:excel_download()"><img src="images/btn_excel1.gif" border="0"></a></td>
					</tr>
					</table>
					</TD>
					<TD background="images/distribute_07.gif"></TD>
				</TR>
				<TR>
					<TD><IMG SRC="images/distribute_08.gif"></TD>
					<TD COLSPAN=2 background="images/distribute_09.gif"></TD>
					<TD><IMG SRC="images/distribute_10.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height="3"></td></tr>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<?$_shopdata->member_baro=="Y"?$member_list_colspan="15":$member_list_colspan="14";?>
				<TR>
					<TD background="images/table_top_line.gif" colspan="<?=$member_list_colspan?>" height=1></TD>
				</TR>
				<TR align=center>
					<TD class="table_cell"><input type=checkbox name=allcheck onclick="CheckAll()" style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;"></TD>
					<TD class="table_cell1">��ȣ</TD>
					<TD class="table_cell1">���̵�</TD>
					<TD class="table_cell1">����</TD>
					<TD class="table_cell1">���</TD>
					<TD class="table_cell1">������</TD>
					<TD class="table_cell1">����</TD>
					<TD class="table_cell1">�޸�</TD>
					<TD class="table_cell1">�ּ�<br /><br />��ȭ</TD>

					<? if (strpos($_ShopInfo->getId(), "getmall") !== false) {?>
					<TD class="table_cell1">�����ݰ���</TD>
					<TD class="table_cell1">����</TD>
					<?}?>

					<TD class="table_cell1">����</TD>
					<TD class="table_cell1">��õ��</TD>
					<?if ($_shopdata->member_baro=="Y") {?>
					<TD class="table_cell1">����</TD>
					<? }?>
					<TD class="table_cell1">���Ա��/���</TD>
				</TR>
				<input type=hidden name=ids_chk>
				<TR>
					<TD colspan="<?=$member_list_colspan?>" background="images/table_con_line.gif"></TD>
				</TR>
<?
				$cnt=0;
				$result = mysql_query($sql,get_db_conn());
				$group_names = array();
				while($row=mysql_fetch_object($result)) {
					if ($scheck!="6") {
						$sql = "SELECT COUNT(*) as recom_cnt FROM tblrecomendlist a, tblmember b WHERE a.rec_id=b.id AND a.rec_id='".$row->id."'";
						$result2 = mysql_query($sql,get_db_conn());
						$row2 = mysql_fetch_object($result2);
						$recom_cnt = $row2->recom_cnt;
						mysql_free_result($result2);
					}
					$groupname = '';
					if(strlen(trim($row->group_code))){
						if(isset($group_names[$row->group_code])) $groupname = $group_names[$row->group_code];
						else{
							$gsql = "select group_name from tblmembergroup where group_code = '".$row->group_code."' limit 1";
							if(false !== $gres =mysql_query($gsql,get_db_conn())){
								$groupname = mysql_result($gres,0,0);
								$group_names[$row->group_code] = $groupname;
							}
							mysql_free_result($gres);
						}
					}

					$number = ($t_count-($setup[list_num] * ($gotopage-1))-$cnt);
					$reg_date=substr($row->date,0,4)."/".substr($row->date,4,2)."/".substr($row->date,6,2)." (".substr($row->date,8,2).":".substr($row->date,10,2).")";
					$haddress="�����ȣ : ".substr($row->home_post,0,3)."-".substr($row->home_post,3,3)." ".str_replace("="," ",$row->home_addr);
					$oaddress="�����ȣ : ".substr($row->office_post,0,3)."-".substr($row->office_post,3,3)." ".str_replace("="," ",$row->office_addr);

					$tcnt = number_format($row->total_cnt);

					$sql2 = "select cnt from tblcheckcount WHERE id='{$row->id}'";
					$result2 = mysql_query($sql2,get_db_conn());
					$row2 = mysql_fetch_array($result2);
					mysql_free_result($result2);
					$dcnt = number_format($row2['cnt']);

					// �ֹ� �õ���
					$cntSql = "select count(*) as cnt from tblorderinfo WHERE id='{$row->id}' AND `deli_gbn` != 'Y' ";
					$cntResult = mysql_query($cntSql,get_db_conn());
					$cntRow = mysql_fetch_array($cntResult);
					mysql_free_result($cntResult);
					$cntAll = number_format($cntRow['cnt']);

					// �ֹ��Ϸ� ��
					$cntSql = "select count(*) as cnt from tblorderinfo WHERE id='{$row->id}' AND `deli_gbn` = 'Y' ";
					$cntResult = mysql_query($cntSql,get_db_conn());
					$cntRow = mysql_fetch_array($cntResult);
					mysql_free_result($cntResult);
					$cntOdr = number_format($cntRow['cnt']);


					$ordertpay = 0;
					$sqlo = "select sum(price) from tblorderinfo where id='".$row->id."' and pay_admin_proc != 'C' and deli_gbn = 'Y' group by id";

					$reso = mysql_query($sqlo,get_db_conn());

					$reso_num_rows = mysql_num_rows($reso);
					if($reso_num_rows > 0){
						if($reso){
							$ordertpay = mysql_result($reso,0,0);
							$ordertpay = number_format($ordertpay);
						}
					}

					echo "<tr>\n";
					echo "	<TD class=\"td_con2\"><p align=\"center\"><input type=checkbox name=ids_chk value=\"".$row->id."\" style=\"BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;\"></td>\n";
					echo "	<TD class=\"td_con1\" align=center>";
					if($row->member_out!="Y") {
						echo "	<b><span class=\"font_orange\"><A HREF=\"javascript:MemberEtcView('".$row->id."')\">".$number."</A></span></b>";
					} else echo $number;
					echo "	</td>\n";
					echo "	<TD align=center class=\"td_con1\">";

					if($row->member_out!="Y") {
						//echo "<b><span class=\"font_orange\"><A HREF=\"javascript:MemberInfo('".$row->id."')\">".$row->id."</A></span></b>";
						echo "<b><span class=\"font_orange\"><A HREF=\"javascript:MemberInfo('".$row->id."')\">".$row->id."</A></span></b>";

					} else {
						echo $row->id;
					}

					if(strlen(trim($groupname))) echo '<br>['.$groupname.']';
					if($row->wholesaletype == 'Y') echo "<br><span style='color:blue'>[����ȸ��]</span>";
					else if($row->wholesaletype == 'R') echo "<br><span style='color:gray'>[����ȸ����û]</span>";

					echo "	</td>\n";
					echo "	<TD align=center class=\"td_con1\">".$row->name."</td>\n";
					echo "	<TD align=center class=\"td_con1\">";
					if($row->member_out!="Y") {
						echo "	<a href=\"javascript:LostPass('".$row->id."');\"><img src=\"images/btn_edit4.gif\" width=\"35\" height=\"29\" border=\"0\"></a>";
					} else {
						//echo "	<button class=button2 disabled>����</button>";
					}
					echo "	</td>\n";
					echo "	<TD align=center class=\"td_con1\" title='".$reg_date."'>".substr($reg_date,0,10)."</td>\n";
					echo "	<TD align=center class=\"td_con1\">";
					echo "	<a href=\"javascript:MemberMail('".$row->email."','".$row->news_yn."');\"><img src=\"images/btn_send.gif\" width=\"35\" height=\"29\" border=\"0\"></a>";
					echo "	</td>\n";
					echo "	<TD align=center class=\"td_con1\">";
					echo "	<a href=\"javascript:MemberMemo2('".$row->id."');\">".(strlen($row->memo)>0?"<img src=\"images/btn_memo.gif\" width=\"35\" height=\"29\" border=\"0\">":"<img src=\"images/btn_memor.gif\" width=\"35\" height=\"29\" border=\"0\">")."</a>";
					echo "	</td>\n";
					echo "	<TD class=\"td_con1\">\n";
					echo "	<table cellpadding=\"0\" cellspacing=\"0\" align=\"center\">\n";
					echo "	<tr>\n";
					echo "		<td>\n";
					echo "		<A HREF=\"javascript:alert('".$haddress."')\"><IMG src=\"images/addr_home.gif\" align=absMiddle border=0 width=\"19\" height=\"19\"></A>";
					echo "		</td>\n";
					echo "		<td style=\"padding-left:1pt;\">\n";
					echo "		<A HREF=\"javascript:alert('".$oaddress."')\"><IMG src=\"images/addr_office.gif\" align=absMiddle border=0 width=\"19\" height=\"19\"></A>";
					echo "		</td>\n";
					echo "	</tr>\n";
					echo "	<tr style='padding-top:10px;'>\n";
					echo "		<td>\n";
					if(strlen($row->home_tel)>0 || strlen($row->mobile)>0) {
						//sendsms.php
						$mem_tel ="\\n������ȭ : ".$row->home_tel."";
						$mem_tel.="\\n\\n�޴���ȭ : ".$row->mobile."\\n";
					echo "		<A HREF=\"javascript:alert('".$mem_tel."')\"><IMG src=\"images/member_tel.gif\" align=absMiddle border=0 width=\"19\" height=\"19\"></A>";
					echo "		</td>\n";
					echo "		<td style=\"padding-left:1pt;\">\n";
					echo "		<A HREF=\"javascript:MemberSMS('".$row->news_yn."','".$row->home_tel."','".$row->mobile."')\"><IMG src=\"images/member_mobile.gif\" align=absMiddle border=0 width=\"19\" height=\"19\"></A>";

						//news_yn : Y/S
					} else {
						echo "- -";
					}
					echo "		</td>\n";
					echo "	</tr>\n";
					echo "	</table>\n";
					echo "	</TD>\n";


					if (strpos($_ShopInfo->getId(), "getmall") !== false) {
						echo "	<TD class=\"td_con1\">\n";
						echo "	<table cellpadding=\"0\" cellspacing=\"0\" align=\"center\">\n";
						echo "  <tr><td colspan=2 height=30 align=center>".number_format($row->reserve)."</td></tr>";
						echo "	<tr>\n";
						echo "		<td>\n";
						echo "		<A HREF=\"javascript:ReserveInOut('".$row->id."');\"><img src=\"images/btn_pm.gif\" width=\"35\" height=\"29\" border=\"0\"></A>";
						echo "		</td>\n";
						echo "		<td style=\"padding-left:1pt;\">\n";
						echo "		<A HREF=\"javascript:ReserveInfo('".$row->id."');\"><img src=\"images/btn_detail.gif\" width=\"35\" height=\"29\" border=\"0\"></A>";
						echo "		</td>\n";
						echo "	</tr>\n";
						echo "	</table>\n";
						echo "	</TD>\n";
						echo "	<TD class=\"td_con1\">\n";
						echo "	<table cellpadding=\"0\" cellspacing=\"0\" align=\"center\">\n";
						echo "	<tr>\n";
						echo "		<td style=\"padding-left:1pt;\">\n";
						echo "		<A HREF=\"javascript:CouponInfo('".$row->id."');\"><img src=\"images/btn_coupon.gif\" width=\"35\" height=\"29\" border=\"0\"></A>";
						echo "		</td>\n";
						echo "	</tr>\n";
						echo "	</table>\n";
						echo "	</TD>\n";
					}

					echo "	<TD class=\"td_con1\">\n";
					echo " <span title='�ֹ���� �� �ֹ� ������'>�ֹ��õ���: ".$cntAll."��</span><br />";
					echo " <A HREF=\"javascript:OrderInfo('".$row->id."');\" title='��ۿϷ��'>�ֹ���: ".$cntOdr."��</a><br />";

					echo " <span title='�ֹ���(��ۿϷ�) �����ݾ�'>�������űݾ�: {$ordertpay}��</span><br />";
					//echo " �ѹ湮: {$tcnt}ȸ<br />";

					echo "	</TD>\n";
					if ($scheck!="6") {
						echo "	<TD align=center class=\"td_con1\">".($row->rec_id?$row->rec_id:"&nbsp;")."<br>(".$recom_cnt.")</td>\n";
					} else {
						echo "	<TD align=right class=\"td_con1\" style=\"padding-right:1pt;\">".$row->rec_cnt."��</td>\n";
					}
					if ($_shopdata->member_baro=="Y") {
						if ($row->confirm_yn == "Y") {
							echo "<TD align=center class=\"td_con1\"><a href=\"javascript:member_baro('cancel','".$row->id."','".$row->email."','".$row->name."');\"><b><span class=\"font_orange\">OK</span></b></td>\n";
						} else {
							echo "<TD align=center class=\"td_con1\"><a href=\"javascript:member_baro('ok','".$row->id."','".$row->email."','".$row->name."');\"><img src=\"images/btn_ok2.gif\" width=\"35\" height=\"29\" border=\"0\"></td>\n";
						}
					}
					$devicesView = ($row->devices == "M" ? "�����" : ($row->devices == "P" ? "PC��" : "-" ) );
					$loginGubun = ($row->loginType == "" ? "������" : ($row->loginType == "tvcf" ? "TVFC" : "-" ) );
					echo "<TD align=center class=\"td_con1\"><b>".$devicesView."</b><br>".$loginGubun."</td>\n";
					echo "</tr>\n";
					echo "<tr>\n";
					echo "	<TD colspan=\"".$member_list_colspan."\" width=\"762\" background=\"images/table_con_line.gif\"><img src=\"images/table_con_line.gif\" width=\"4\" height=\"1\" border=\"0\"></TD>";
					echo "</tr>\n";
					$cnt++;
				}
				mysql_free_result($result);
?>
				<TR>
					<TD background="images/table_top_line.gif" colspan="<?=$member_list_colspan?>" height="1"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=6></td></tr>
			<tr>
				<td><p align="left"><a href="javascript:check_del()"><img src="images/icon_tal.gif" border="0"></a></p><p align="center">
<?
				$total_block = intval($pagecount / $setup[page_num]);

				if (($pagecount % $setup[page_num]) > 0) {
					$total_block = $total_block + 1;
				}

				$total_block = $total_block - 1;

				if (ceil($t_count/$setup[list_num]) > 0) {
					// ����	x�� ����ϴ� �κ�-����
					$a_first_block = "";
					if ($nowblock > 0) {
						$a_first_block .= "<a href='javascript:GoPage(0,1);' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='ù ������';return true\"><IMG src=\"images/icon_first.gif\" border=0 align=\"absmiddle\"></a>&nbsp;&nbsp;";

						$prev_page_exists = true;
					}

					$a_prev_page = "";
					if ($nowblock > 0) {
						$a_prev_page .= "<a href='javascript:GoPage(".($nowblock-1).",".($setup[page_num]*($block-1)+$setup[page_num]).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup[page_num]." ������';return true\">[prev]</a>&nbsp;&nbsp;";

						$a_prev_page = $a_first_block.$a_prev_page;
					}

					// �Ϲ� �������� ������ ǥ�úκ�-����

					if (intval($total_block) <> intval($nowblock)) {
						$print_page = "";
						for ($gopage = 1; $gopage <= $setup[page_num]; $gopage++) {
							if ((intval($nowblock*$setup[page_num]) + $gopage) == intval($gotopage)) {
								$print_page .= "<span class=font_orange2><B>[".(intval($nowblock*$setup[page_num]) + $gopage)."]</B></span> ";
							} else {
								$print_page .= "<a href='javascript:GoPage(".$nowblock.",".(intval($nowblock*$setup[page_num]) + $gopage).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='������ : ".(intval($nowblock*$setup[page_num]) + $gopage)."';return true\">[".(intval($nowblock*$setup[page_num]) + $gopage)."]</a> ";
							}
						}
					} else {
						if (($pagecount % $setup[page_num]) == 0) {
							$lastpage = $setup[page_num];
						} else {
							$lastpage = $pagecount % $setup[page_num];
						}

						for ($gopage = 1; $gopage <= $lastpage; $gopage++) {
							if (intval($nowblock*$setup[page_num]) + $gopage == intval($gotopage)) {
								$print_page .= "<span class=font_orange2><B>[".(intval($nowblock*$setup[page_num]) + $gopage)."]</B></span> ";
							} else {
								$print_page .= "<a href='javascript:GoPage(".$nowblock.",".(intval($nowblock*$setup[page_num]) + $gopage).");' onMouseOver=\"window.status='������ : ".(intval($nowblock*$setup[page_num]) + $gopage)."';return true\">[".(intval($nowblock*$setup[page_num]) + $gopage)."]</a> ";
							}
						}
					}		// ������ �������� ǥ�úκ�-��


					$a_last_block = "";
					if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
						$last_block = ceil($t_count/($setup[list_num]*$setup[page_num])) - 1;
						$last_gotopage = ceil($t_count/$setup[list_num]);

						$a_last_block .= "&nbsp;&nbsp;<a href='javascript:GoPage(".$last_block.",".$last_gotopage.");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='������ ������';return true\"><IMG src=\"images/icon_last.gif\" border=0 align=\"absmiddle\" width=\"17\" height=\"14\"></a>";

						$next_page_exists = true;
					}

					// ���� 10�� ó���κ�...

					$a_next_page = "";
					if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
						$a_next_page .= "&nbsp;&nbsp;<a href='javascript:GoPage(".($nowblock+1).",".($setup[page_num]*($nowblock+1)+1).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup[page_num]." ������';return true\">[next]</a>";

						$a_next_page = $a_next_page.$a_last_block;
					}
				} else {
					$print_page = "<B>[1]</B>";
				}
?>
				<!-- ������ ��� ---------------------->
				<?=$a_div_prev_page?>
				<?=$a_prev_page?>
				<?=$print_page?>
				<?=$a_next_page?>
				<?=$a_div_next_page?>
				<!-- ������ ��� �� -->
				</p>
				</td>
			</tr>
			<tr>
				<td height="30">&nbsp;</td>
			</tr>
			<input type=hidden name=tot value="<?=$cnt?>">
			</form>
			<form name=form2 action="member_reservelist.php" method=post target=reserve_info>
			<input type=hidden name=id>
			<input type=hidden name=type>
			</form>
			<form name=form3 method=post>
			<input type=hidden name=id>
			</form>
			<form name=reserveform action="reserve_money.php" method=post>
			<input type=hidden name=type>
			<input type=hidden name=id>
			</form>
			<form name=mailform action="member_mailsend.php" method=post>
			<input type=hidden name=rmail>
			</form>
			<form name=smsform action="sendsms.php" method=post target="sendsmspop">
			<input type=hidden name=number>
			</form>

			<form name=idxform action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=block value="">
			<input type=hidden name=gotopage value="">
			<input type=hidden name=sort value="<?=$sort?>">
			<input type=hidden name=scheck value="<?=$scheck?>">
			<input type=hidden name=group_code value="<?=$group_code?>">
			<input type=hidden name=vperiod value="<?=$vperiod?>">
			<input type=hidden name=search_start value="<?=$search_start?>">
			<input type=hidden name=search_end value="<?=$search_end?>">
			<input type=hidden name=search value="<?=$search?>">
			<input type=hidden name=devices value="<?=$devices?>">
			</form>

			<form name=excelform action="member_excel.php" method=post>
			<input type=hidden name=sort value="<?=$sort?>">
			<input type=hidden name=scheck value="<?=$scheck?>">
			<input type=hidden name=group_code value="<?=$group_code?>">
			<input type=hidden name=search_start value="<?=$search_start?>">
			<input type=hidden name=search_end value="<?=$search_end?>">
			<input type=hidden name=search value="<?=$search?>">
			</form>

			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/manual_top1.gif" WIDTH=15 height="45" ALT=""></TD>
					<TD><IMG SRC="images/manual_title.gif" WIDTH=113 height="45" ALT=""></TD>
					<TD width="100%" background="images/manual_bg.gif" height="35"></TD>
					<TD background="images/manual_bg.gif"></TD>
					<td background="images/manual_bg.gif"><IMG SRC="images/manual_top2.gif" WIDTH=18 height="45" ALT=""></td>
				</TR>
				<TR>
					<TD background="images/manual_left1.gif"></TD>
					<TD COLSPAN=3 width="100%" valign="top" class=menual_bg style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;"  class="menual_bg">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><span class="font_dotline">�˻��� ȸ����� Ŭ���� ����</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- <b>��ȣ</b> : <span style="letter-spacing:-0.5pt;">ȸ������ ���� �� ȸ���߰� �Է��� ������ Ȯ���� �� �ֽ��ϴ�.</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- <b>���̵�</b> : <span style="letter-spacing:-0.5pt;">�ֹι�ȣ, �̸���, �ּ� �� ȸ���⺻������ Ȯ���� �� �ֽ��ϴ�.</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- <b>���</b> : <span style="letter-spacing:-0.5pt;">��ڶ�� �Ͽ��� ȸ���� ��й�ȣ ��ü�� �������� ���ϸ� ��� �ӽú�й�ȣ�� �߱ް����մϴ�.</span><br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="letter-spacing:-0.5pt;">(�ӽú�й�ȣ�� ȸ�����Խ� ����� �̸��Ϸ� �߼۵˴ϴ�. �̸��� ���ſ��θ� Ȯ���ϼ���.)</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- <b>����</b> : <span style="letter-spacing:-0.5pt;">ȸ������ ������ �߼��� �� �ֽ��ϴ�.</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- <b>�޸�</b> : <span style="letter-spacing:-0.5pt;">ȸ���� ���� �޸� �� �� �ֽ��ϴ�.(���̵� �Է��� �޸𰡴�)</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- <b>�ּ�</b> : <span style="letter-spacing:-0.5pt;">���ּ� �Ǵ� ȸ���ּҸ� Ȯ�� �� �� �ֽ��ϴ�.</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- <b>��ȭ</b> : <span style="letter-spacing:-0.5pt;">������ȭ�� �޴���ȭ ��ȣ�� Ȯ�� �Ҽ� ������ SMS �߼۵� �����մϴ�.(SMS �߼��� SMS�Ӵϸ� ���� �� �̿��� �����մϴ�.)</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- <b>������</b> : <span style="letter-spacing:-0.5pt;">��� ���Ƿ� �������� ������ �� ������ ���� ������ ������ Ȯ���� �� �ֽ��ϴ�.</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- <b>����</b> : <span style="letter-spacing:-0.5pt;">���ų��� �� ���� ���������� Ȯ�� �Ҽ� �ֽ��ϴ�.(���ų��� ������ ���ó�� �Ϸ�� �ֹ��Ǹ� ��µ˴ϴ�.)</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- <b>����</b> : <span style="letter-spacing:-0.5pt;">������ ���� �������� ������ ������ ���ŷ� �����ÿ��� ��µ˴ϴ�.</span><br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:parent.topframe.GoMenu(1,'shop_openmethod.php');"><span class="font_blue" style="letter-spacing:-0.5pt;">�������� > ���θ� ȯ�� ���� > ������ ���� ����</a></span></td>
					</tr>
					</table>
					</TD>
					<TD background="images/manual_right1.gif"></TD>
				</TR>
				<TR>
					<TD><IMG SRC="images/manual_left2.gif" WIDTH=15 HEIGHT=8 ALT=""></TD>
					<TD COLSPAN=3 background="images/manual_down.gif"></TD>
					<TD><IMG SRC="images/manual_right2.gif" WIDTH=18 HEIGHT=8 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height="50"></td></tr>
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
<?=$onload?>

<? INCLUDE "copyright.php"; ?>