<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "me-2";
$MenuCode = "member";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$regdate=$_shopdata->joindate;
if(strlen($regdate)==0) $regdate="20070401010101";

//����Ʈ ����
$setup[page_num] = 10;
$setup[list_num] = 20;

$block=$_REQUEST["block"];
$gotopage=$_REQUEST["gotopage"];
$sort=$_POST["sort"];

if ($block != "") {
	$nowblock = $block;
	$curpage  = $block * $setup[page_num] + $gotopage;
} else {
	$nowblock = 0;
}

if (($gotopage == "") || ($gotopage == 0)) {
	$gotopage = 1;
}


$type=$_POST["type"];
$mode=$_POST["mode"];
$ids=$_POST["ids"];
$group_code=$_POST["group_code"];
$searchtype=$_POST["searchtype"];
$sex=$_POST["sex"];
$age=$_POST["age"];
$agemin=$_POST["agemin"];
$agemax=$_POST["agemax"];
$reserve=$_POST["reserve"];
$reservemin=$_POST["reservemin"];
$reservemax=$_POST["reservemax"];
$memregdate=$_POST["memregdate"];
$memregyear1=$_POST["memregyear1"];
$memregmonth1=$_POST["memregmonth1"];
$memregday1=$_POST["memregday1"];
$memregyear2=$_POST["memregyear2"];
$memregmonth2=$_POST["memregmonth2"];
$memregday2=$_POST["memregday2"];
$birth=$_POST["birth"];
$birthmonth=$_POST["birthmonth"];
$birthday=$_POST["birthday"];
$addr=$_POST["addr"];
$seladdr=$_POST["seladdr"];
$groupmember=$_POST["groupmember"];
$buydate=$_POST["buydate"];
$buyyear1=$_POST["buyyear1"];
$buymonth1=$_POST["buymonth1"];
$buyday1=$_POST["buyday1"];
$buyyear2=$_POST["buyyear2"];
$buymonth2=$_POST["buymonth2"];
$buyday2=$_POST["buyday2"];
$price=$_POST["price"];
$pricemin=$_POST["pricemin"];
$pricemax=$_POST["pricemax"];
$ordercnt=$_POST["ordercnt"];
$ordercntmin=$_POST["ordercntmin"];
$ordercntmax=$_POST["ordercntmax"];
$search=$_POST["search"];

$today=date("Ymd");
if(strlen($memregmonth1)>0){
	if (strlen($memregmonth1) != 2) $memregmonth1 = "0".$memregmonth1;
	if (strlen($memregday1) != 2) $memregday1 = "0".$memregday1;
	if (strlen($memregmonth2) != 2) $memregmonth2 = "0".$memregmonth2;
	if (strlen($memregday2) != 2) $memregday2 = "0".$memregday2;
}

// �����ں�
$memregdateDisabled = "disabled";
if($memregdate!="ALL") {
	$termday = (mktime(0,0,0,$memregmonth2,$memregday2,$memregyear2)-mktime(0,0,0,$memregmonth1,$memregday1,$memregyear1))/86400;
	if( strlen($_POST["type"]) > 0 ) {
		$memregdate = "SELDATE";
		$memregdateDisabled = "";
	}
} else {
	$termday=0;
}

if ($termday>92) {
	echo "<script>alert('�������ں� ��ȸ�� 3������ �ʰ��� �� �����ϴ�.\\n��¥�� �������Ͻ� �� �ٽ� �õ��ϼ���.');history.go(-1);</script>";
	exit;
}


// ������Ϻ�
$birthDisabled = "disabled";
if( $birth != "ALL" AND strlen($_POST["type"]) > 0 ) {
	$birthDisabled = "";
	$birth ='SELDATE';
}



// ������
$addrDisabled = "disabled";
if( $addr != "ALL" AND strlen($_POST["type"]) > 0 ) {
	$addrDisabled = "";
	$addr ='SELDATE';
}

// ȸ������
$gubunDisabled = "disabled";
if( $gubun_all != "ALL" AND strlen($_POST["type"]) > 0 ) {
	$gubunDisabled = "";
	$gubun ='SELDATE';
}

// ����
$jobtypeDisabled = "disabled";
if( $jikjong_all != "ALL" AND strlen($_POST["type"]) > 0 ) {
	$jobtypeDisabled = "";
	$job_type ='SELDATE';
}

// ����
$jobgroupDisabled = "disabled";
if( $jikgun_all != "ALL" AND strlen($_POST["type"]) > 0 ) {
	$jobgroupDisabled = "";
	$job_group ='SELDATE';
}


// ���ų��� - ���ų�¥��
if( $buydate != "ALL" AND strlen($_POST["type"]) > 0 ) {
	$buydate ='SELDATE';
}

// ���ų��� - ���űݾ׺�
if( $price != "ALL" AND strlen($_POST["type"]) > 0 ) {
	$price ='SELDATE';
}

// ���ų��� - ���ŰǼ���
if( $ordercnt != "ALL" AND strlen($_POST["type"]) > 0 ) {
	$ordercnt ='SELDATE';
}



if($mode=="insert") {
	$ids=substr($ids,0,-1);
	$inid=eregi_replace("\|","'",$ids);
	$sql = "UPDATE tblmember SET group_code = '".$group_code."' WHERE id IN (".$inid.") ";
	mysql_query($sql,get_db_conn());
	$onload="<script>alert('�ش� ȸ���� ����� ���� �Ǿ����ϴ�.');</script>";
}
$max=10;
$len=21;

if (empty($searchtype)) $searchtype="M";
if (empty($page)) $page="1";
?>
<? INCLUDE "header.php"; ?>
<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">

function CheckForm() {
	/*
	if(document.form1.group_code.value.length==0){
		alert('ȸ������� ���� �����ϼž� �˻��� �����մϴ�.');
		document.form1.group_code.focus();
		return;
	}*/
	if(document.form1.searchtype[0].checked==true){
		if(document.form1.sex[0].checked==false && document.form1.sex[1].checked==false && document.form1.sex[2].checked==false){
			alert('������ �����ϼ���. ');
			document.form1.sex[0].focus();
			return;
		}
		if(document.form1.age.checked==false && document.form1.agemin.value.length==0 && document.form1.agemax.value.length==0){
			alert('���̸� �����ϼ���. ');
			document.form1.age.focus();
			return;
		}
		if((document.form1.agemin.value.length!=0 && isNaN(document.form1.agemin.value)) || (document.form1.agemax.value.length!=0 && isNaN(document.form1.agemax.value))){
			alert('���̴� ���ڸ� �Է� ���� �մϴ�.');
			document.form1.agemin.focus();
			return;
		}
		if(document.form1.reserve.checked==false && document.form1.reservemin.value.length==0 && document.form1.reservemax.value.length==0){
			alert('������ ������ �ϼ���. ');
			document.form1.reserve.focus();
			return;
		}
		if((document.form1.reservemin.value.length!=0 && isNaN(document.form1.reservemin.value)) || (document.form1.reservemax.value.length!=0 && isNaN(document.form1.reservemax.value))){
			alert('�������� ���ڸ� �Է� �����մϴ�.');
			document.form1.reservemin.focus();
			return;
		}
	}else if(document.form1.searchtype[1].checked==true){
		if(document.form1.price.checked==false && document.form1.pricemin.value.length==0 && document.form1.pricemax.value.length==0){
			alert('���űݾ��� �����ϼ���. ');
			document.form1.price.focus();
			return;
		}
		if((document.form1.pricemin.value.length!=0 && isNaN(document.form1.pricemin.value)) || (document.form1.pricemax.value.length!=0 && isNaN(document.form1.pricemax.value))){
			alert('���űݾ��� ���ڸ� �Է� �����մϴ�.');
			document.form1.pricemin.focus();
			return;
		}
		if(document.form1.ordercnt.checked==false && document.form1.ordercntmin.value.length==0 && document.form1.ordercntmax.value.length==0){
			alert('���ŰǼ��� �����ϼ���. ');
			document.form1.ordercnt.focus();
			return;
		}
		if((document.form1.ordercntmin.value.length!=0 && isNaN(document.form1.ordercntmin.value)) || (document.form1.ordercntmax.value.length!=0 && isNaN(document.form1.ordercntmax.value))){
			alert('���ŰǼ��� ���ڸ� �Է� �����մϴ�.');
			document.form1.ordercntmin.focus();
			return;
		}
	}else if(document.form1.searchtype[2].checked==true){
		if(document.form1.search.value.length<2){
			alert('Ư��ȸ�� �˻���� 2�� �̻� �Է��ϼž� �մϴ�. ');
			document.form1.search.focus();
			return;
		}
	}
	document.form1.type.value="search";
	document.form1.mode.value="";
	document.form1.block.value=document.form3.block.value;
	document.form1.gotopage.value=document.form3.gotopage.value;
	document.form1.submit();
}

function ChangeSex(no){
	for(i=0;i<3;i++){
		if(no==i) document.form1.sex[i].checked=true;
		else document.form1.sex[i].checked=false;
	}
}

function ChangeGroup(no){
	for(i=0;i<3;i++){
		if(no==i) document.form1.groupmember[i].checked=true;
		else document.form1.groupmember[i].checked=false;
	}
}

function ChangeCheck(no){
	if(no==1) document.form1.age.checked=false;
	else if(no==2) document.form1.reserve.checked=false;
	else if(no==3) document.form1.price.checked=false;
	else if(no==4) document.form1.ordercnt.checked=false;
	else if(no==5) document.form1.buydate.checked=false;
	else if(no==6) document.form1.sosok.checked=false;
}

var shop="layer1";
var ArrLayer = new Array ("layer1","layer2","layer3");
function ViewLayer(gbn){
	if(document.all){
		for(i=0;i<ArrLayer.length;i++) {
			if (ArrLayer[i] == gbn)
				document.all[ArrLayer[i]].style.display="";
			else
				document.all[ArrLayer[i]].style.display="none";
		}
	} else if(document.getElementById){
		for(i=0;i<2;i++) {
			if (ArrLayer[i] == gbn)
				document.getElementByld[ArrLayer[i]].style.display="";
			else
				document.getElementByld[ArrLayer[i]].style.display="none";
		}
	} else if(document.layers){
		for(i=0;i<2;i++) {
			if (ArrLayer[i] == gbn)
				document.layers[ArrLayer[i]].display="";
			else
				document.layers[ArrLayer[i]].display="none";
		}
	}
	shop=gbn;
}

function GoSort(sort){
	document.form1.type.value="search";
	document.form1.mode.value="";
	document.form1.sort.value=sort;
	document.form1.submit();
}

function GoPage(block,gotopage) {
	document.form1.type.value="search";
	document.form1.mode.value="";
	document.form1.block.value = block;
	document.form1.gotopage.value = gotopage;
	document.form1.submit();
}

function GoGroupCode( code ){
	<?
	// �׷� ����
	echo "var groupInfo = new Array();\n";
	echo "groupInfoArray = new Array();\n";
	$grouptitle = array();
	$Gsql = "SELECT * FROM tblmembergroup ";
	$Gresult = mysql_query($Gsql,get_db_conn());
	$i=0;

	echo "groupInfo[".$i."] = new Array(2);\n";
	echo "groupInfo[".$i."][0] = '';\n";
	echo "groupInfo[".$i."][1] = '';\n";
	echo "groupInfo[".$i."][2] = '';\n";
	echo "groupInfo[".$i."][3] = '0';\n";
	$i++;

	while($Grow=mysql_fetch_object($Gresult)) {

		$Msql = "SELECT COUNT(*) as cnt FROM tblmember WHERE group_code = '".$Grow->group_code."'";
		$Mresult=mysql_query($Msql,get_db_conn());
		$Mrow=mysql_fetch_object($Mresult);

		$grouptitle[$Grow->group_code]['code']=$Grow->group_code;
		$grouptitle[$Grow->group_code]['name']=$Grow->group_name;

		echo "groupInfo[".$i."] = new Array(2);\n";
		echo "groupInfo[".$i."][0] = '".$Grow->group_code."';\n";
		echo "groupInfo[".$i."][1] = '".$Grow->group_name."';\n";
		echo "groupInfo[".$i."][2] = '".$Grow->group_description."';\n";
		echo "groupInfo[".$i."][3] = '".$Mrow->cnt."';\n";

		echo "groupInfoArray[".$i."] = '".$Grow->group_code."';\n";

		$i++;

	}
	mysql_free_result($Gresult);
	?>
	var key = '0';
	var temp = groupInfoArray.indexOf(code);
	if( temp > 0 ) key = temp;
	groupInfoCNT.innerHTML = groupInfo[key][3];
	groupInfoMemo.innerHTML = groupInfo[key][2];
}

function CheckAll(cnt){
	chkval=document.form1.allcheck.checked;
	for(i=1;i<=cnt;i++){
		document.form1.chkid[i].checked=chkval;
	}
}

function InsertGroup(cnt){
	chkval=false;

	if(document.form1.group_code.value.length==0){
		alert('ȸ������� ���� �����ϼž� �˻��� �����մϴ�.');
		document.form1.group_code.focus();
		return;
	}

	document.form1.ids.value="";

	for(i=1;i<=cnt;i++){
		if(document.form1.chkid[i].checked==true){
			chkval=true;
			document.form1.ids.value+="|"+document.form1.chkid[i].value+"|,";
		}
	}
	if(chkval==false){
		alert('������ ȸ���� �����ϼ���');
		document.form1.chkid[1].focus();
		return;
	}
	document.form1.type.value="search";
	document.form1.mode.value="insert";
	document.form1.submit();
}

function ReserveInfo(id) {
	window.open("about:blank","reserve_info","height=400,width=400,scrollbars=yes");
	document.form2.id.value=id;
	document.form2.submit();
}

function UserMemoView(obj,type) {
	try {
		obj.style.visibility = type;
	} catch (e) {}
}

</script>


<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed"  background="images/con_bg.gif">
	<col width=198></col>
	<col width=10></col>
	<col width=></col>
	<tr>
		<td valign="top"  background="images/leftmenu_bg.gif">
			<? include ("menu_member.php"); ?>
		</td>
		<td></td>
		<td valign="top">
			<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td height="29" colspan="3">
						<table cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ȸ������ &gt; ȸ����޼��� &gt; <span class="2depth_select">��޺� ȸ������/����</span></td>
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
							<tr>
								<td height="8"></td>
							</tr>
							<tr>
								<td>
									<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
										<TR>
											<TD><IMG SRC="images/member_groupmemreg_title.gif" ALT=""></TD>
										</tr>
										<tr>
											<TD width="100%" background="images/title_bg.gif" height="21"></TD>
										</TR>
									</TABLE>
								</td>
							</tr>
							<tr>
								<td height="3"></td>
							</tr>
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
											<TD width="100%" class="notice_blue">ȸ���˻��� ���� ȸ��Ư���� �´� ������� ���� ������ �� �ֽ��ϴ�.</TD>
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
							<tr>
								<td height="20"></td>
							</tr>
							<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
							<input type=hidden name=type>
							<input type=hidden name=mode>
							<input type=hidden name=block>
							<input type=hidden name=gotopage>
							<input type=hidden name=sort value="<?=$sort?>">
							<input type=hidden name=ids>
							<tr>
								<td height="10"></td>
							</tr>
							<tr>
								<td>
									<table cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td width="100%" bgcolor="#ededed" style="padding:4pt;">
												<table cellpadding="0" cellspacing="0" width="100%" bgcolor="white">
													<tr>
														<td width="100%">
															<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
																<TR>
																	<TD colspan="4" background="images/table_con_line.gif"></TD>
																</TR>
																<TR>
																	<TD height="35" align=center background="images/blueline_bg.gif"><b><font color="#555555">ȸ���˻��ϱ�</font></b></TD>
																	<td></td>
																	<td></td>
																	<td></td>
																</TR>
																<TR>
																	<TD>
																		<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
																			<col width=138></col>
																			<col width=></col>
																			<col width=108></col>
																			<col width=206></col>
																			<TR>
																				<TD colspan="2" background="images/table_con_line.gif"></TD>
																			</TR>
																			<TR>
																				<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�˻�����</TD>
																				<TD class="td_con1">
																					<input type=radio id="idx_searchtype1" name=searchtype value="M" style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;" <?if($searchtype=="M") echo "checked";?> onclick="ViewLayer('layer1')">
																					<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_searchtype1>ȸ�� �Ӽ�</label>
																					&nbsp;&nbsp;
																					<input type=radio id="idx_searchtype2" name=searchtype value="O" style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;" <?if($searchtype=="O") echo "checked";?> onclick="ViewLayer('layer2')">
																					<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_searchtype2>���� ����</label>
																					&nbsp;&nbsp;
																					<input type=radio id="idx_searchtype3" name=searchtype value="U" style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;" <?if($searchtype=="U") echo "checked";?> onclick="ViewLayer('layer3')">
																					<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_searchtype3>Ư�� ȸ��</label>
																				</TD>
																			</TR>
																			<TR>
																				<TD colspan="4" background="images/table_con_line.gif"></TD>
																			</TR>
																		</table>
																		<div id=layer1 style="margin-left:0;display:hide; display:<?=($searchtype=="M"?"block":"none")?> ;border-style:solid; border-width:0; border-color:black;background:#FFFFFF;padding:0;">
																			<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
																				<col width=138></col>
																				<col width=></col>
																				<col width=108></col>
																				<col width=206></col>
																				<TR>
																					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">���ɺ�</TD>
																					<TD class="td_con1">
																						<input type=checkbox id="idx_age1" name=age value="ALL" style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;" <? if(empty($agemin) && empty($agemax)) echo "checked"?>>
																						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_age1>��ü</label>
																						<input type=text name=agemin value="<?=$agemin?>" size=3 maxlength=3 onfocus="ChangeCheck(1)" class="input">
																						������
																						<input type=text name=agemax value="<?=$agemax?>" size=3 maxlength=3 onfocus="ChangeCheck(1)" class="input">
																						������</TD>
																				</TR>
																				<TR>
																					<TD colspan="4" background="images/table_con_line.gif"></TD>
																				</TR>
																				<TR>
																					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">����</TD>
																					<TD class="td_con1">
																						<input type=checkbox id="idx_sex1" name=sex value="ALL" style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;" onclick="ChangeSex(0)" <? if($sex !="M" && $sex !='F' ) echo "checked"?>>
																						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_sex1>��ü</label>
																						<input type=checkbox id="idx_sex2" name=sex value="M" style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;" onclick="ChangeSex(1)" <?if($sex=="M") echo "checked"?>>
																						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_sex2>����</label>
																						<input type=checkbox id="idx_sex3" name=sex value="F" style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;" onclick="ChangeSex(2)" <?if($sex=="F") echo "checked"?>>
																						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_sex3>����</label>
																					</TD>
																				</TR>
																				<TR>
																					<TD colspan="4" background="images/table_con_line.gif"></TD>
																				</TR>
																				<TR>
																					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�����ݾ׺�</TD>
																					<TD class="td_con1">
																						<input type=checkbox id="idx_reserve1" name=reserve value="ALL" style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;" <? if(empty($reservemin) && empty($reservemax)) echo "checked"?>>
																						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_reserve1>��ü</label>
																						<input type=text name=reservemin value="<?=$reservemin?>" size=8 maxlength=8 onfocus="ChangeCheck(2)" class="input">
																						������
																						<input type=text name=reservemax value="<?=$reservemax?>" size=8 maxlength=8 onfocus="ChangeCheck(2)" class="input">
																						������</TD>
																				</TR>
																				<TR>
																					<TD colspan="4" background="images/table_con_line.gif"></TD>
																				</TR>
																				<TR>
																					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�������ں�</TD>
																					<TD class="td_con1">
																						<input type=checkbox id="idx_memregdate1" name=memregdate value="ALL" style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;" <?if($memregdate=="ALL" OR $memregdate == '') echo "checked"?> onclick="idx_memregdateSel.disabled=this.checked;">
																						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_memregdate1>��ü</label>
																						<span id="idx_memregdateSel" <?=$memregdateDisabled?>>
																						<?
	if (empty($memregdate1)) $memregdate1 = $today;
	if (empty($memregdate2)) $memregdate2 = $today;
	// �ʱⰪ ���� :���ó�¥
	if(empty($memregyear1))	$memregyear1 = substr($memregdate1,0,4);
	if(empty($memregmonth1))	$memregmonth1 = substr($memregdate1,4,2);
	if(empty($memregday1))		$memregday1 = substr($memregdate1,6,2);

	if(empty($memregyear2))	$memregyear2 = substr($memregdate2,0,4);
	if(empty($memregmonth2))	$memregmonth2 = substr($memregdate2,4,2);
	if(empty($memregday2))		$memregday2 = substr($memregdate2,6,2);

	echo "<select size=1 name=memregyear1 class=\"select\">\n";
	for ($i = substr($regdate,0,4);$i <=substr($today,0,4) ; $i++) {
		if($i == $memregyear1)  echo "<option selected value=\"$i\">$i</option>\n";
		else echo "<option value=\"$i\">$i</option>\n";
	}
	echo "</select>��";
	echo "<select size=1 name=memregmonth1 class=\"select\">\n";
	for ($i = 1;$i <= 12; $i++) {
		if($i == $memregmonth1)  echo "<option selected value=\"$i\">$i</option>\n";
		else echo "<option value=\"$i\">$i</option>\n";
	}
	echo "</select>��";
	echo "<select size=1 name=memregday1 class=\"select\">\n";
	for ($i = 1;$i <= 31; $i++) {
		if ($i == $memregday1)  echo "<option selected value=\"$i\">$i</option>\n";
		else echo "<option value=\"$i\">$i</option>\n";
	}
	echo "</select>�� ~ ";

	echo "<select size=1 name=memregyear2 class=\"select\">\n";
	for ($i = substr($regdate,0,4);$i <= substr($today,0,4); $i++) {
		if ($i == $memregyear2)  echo "<option selected value=\"$i\">$i</option>\n";
		else echo "<option value=\"$i\">$i</option>\n";
	}
	echo "</select>��";
	echo "<select size=1 name=memregmonth2 class=\"select\">\n";
	for ($i = 1;$i <= 12; $i++) {
		if ($i == $memregmonth2)  echo "<option selected value=\"$i\">$i</option>\n";
		else echo "<option value=\"$i\">$i</option>\n";
	}
	echo "</select>��";
	echo "<select size=1 name=memregday2 class=\"select\">\n";

	for ($i = 1;$i <= 31; $i++) {
		if($i == $memregday2)   echo "<option selected value=\"$i\">$i</option>\n";
		else  echo "<option value=\"$i\">$i</option>\n";
	}
	echo "</select>��";
?>
																						</span>
																					</TD>
																				</TR>
																				<TR>
																					<TD colspan="4" background="images/table_con_line.gif"></TD>
																				</TR>
																				<TR>
																					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">������Ϻ�</TD>
																					<TD class="td_con1">
																						<input type=checkbox id="idx_birth1" name=birth value="ALL" style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;" <?if($birth=="ALL" OR $birth=="") echo "checked"?> onclick="idx_birthSel.disabled=this.checked;">
																						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_birth1>��ü</label>
																						<span id="idx_birthSel" <?=$birthDisabled?>>
																						<?
	if(strlen($birthmonth)==0) $birthmonth = date("m");
	echo "<select size=1 name=birthmonth class=\"select\">\n";
	for ($i = 1;$i <= 12; $i++) {
		if ($i<10) $i2 = "0$i";
		else $i2=$i;
		if($i2 == $birthmonth)  echo "<option selected value=\"$i2\">$i</option>\n";
		else echo "<option value=\"$i2\">$i</option>\n";
	}
	echo "</select>��";
	if(strlen($birthday)==0) $birthday = date("d");
	echo "<select size=1 name=birthday class=\"select\">\n";
	echo "<option value=\"ALL\"";
	if($birthday=="ALL") echo " selected";
	echo ">��ü";
	for ($i = 1;$i <= 31; $i++) {
		if ($i<10) $i2 = "0$i";
		else $i2=$i;
		if ($i2 == $birthday)  echo "<option selected value=\"$i2\">$i</option>\n";
		else echo "<option value=\"$i2\">$i</option>\n";
	}
	echo "</select>��";
?>
																						</span>
																					</TD>
																				</TR>
																				<TR>
																					<TD colspan="4" background="images/table_con_line.gif"></TD>
																				</TR>
																				<TR>
																					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">������</TD>
																					<TD class="td_con1">
																						<input type=checkbox id="idx_addr1" name=addr value="ALL" style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;" <?if($addr=="ALL" OR $addr=="") echo "checked"?> onclick="idx_addrSel.disabled=this.checked;">
																						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_addr1>��ü</label>
																						<span id="idx_addrSel" <?=$addrDisabled?>>
																						<select name=seladdr class="select">
																							<?
	$area= array ("����","��õ","�λ�","����","����","�뱸","���","���","����","���","�泲","���","�泲","����","����","����");
	$arnum = count($area);
	for($i=0;$i<$arnum;$i++){
		echo "<option value=\"".$area[$i]."\"";
		if($seladdr==$area[$i]) echo " selected";
		echo ">".$area[$i];
	}
?>
																						</select>
																						<span class="font_orange">*�ش� �˻��� ���ּҸ� �˻��մϴ�.</span>
																						</span>
																					</TD>
																				</TR>

																				<TR>
																					<TD colspan="4" background="images/table_con_line.gif"></TD>
																				</TR>
																				<TR>
																					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">ȸ������</TD>
																					<TD class="td_con1">
																						<input type=checkbox id="idx_gubun1" name=gubun_all value="ALL" style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;" <?if($gubun=="ALL" OR $gubun=="") echo "checked"?> onclick="idx_gubunSel.disabled=this.checked;">
																						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_gubun1>��ü</label>
																						<span id="idx_gubunSel" <?=$gubunDisabled?>>
																						<select name=selgubun class="select">
<?
	$gubun= array ("�Ϲ�","������","����","�л�","���");
	$arnum = count($gubun);
	for($i=0;$i<$arnum;$i++){
		echo "<option value=\"".$gubun[$i]."\"";
		if($selgubun==$gubun[$i]) echo " selected";
		echo ">".$gubun[$i];
	}
?>
																						</select>
																						</span>
																					</TD>
																				</TR>

																				<TR>
																					<TD colspan="4" background="images/table_con_line.gif"></TD>
																				</TR>
																				<TR>
																					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�Ҽ�</TD>
																					<TD class="td_con1">
																						<input type=checkbox id="idx_sosok1" name=sosok_all value="ALL" style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;" <?if(empty($selsosok)) echo "checked"?> onclick="idx_sosokSel.disabled=this.checked;">
																						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_sosok1>��ü</label>
																						<input type=text id="idx_sosokSel" name=selsosok value="<?=$selsosok?>" onfocus="ChangeCheck(6)" size=20 class="input">
																					</TD>
																				</TR>

																				<TR>
																					<TD colspan="4" background="images/table_con_line.gif"></TD>
																				</TR>
																				<TR>
																					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">����</TD>
																					<TD class="td_con1">
																						<input type=checkbox id="idx_jobtype1" name=jikjong_all value="ALL" style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;" <?if($job_type=="ALL" OR $job_type=="") echo "checked"?> onclick="idx_jobtypeSel.disabled=this.checked;">
																						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_jobtype1>��ü</label>
																						<span id="idx_jobtypeSel" <?=$jobtypeDisabled?>>
																						<select name=seljob_type class="select">
																							<?
	$jikjonglist= array ("1","2","4","8","16","32","64","128","256","512","1024","2048","4096","8192","16384","32768","65536","131072","131072","262144","524288","1048576","2097152","4194304","8388608","16777216","33554432","67108864","134217728");
	$job_type= array ("��������","���δ���","����Ʈ���δ���","����/CM Song","�Կ�","����","�̼�/��Ʈ","��Ʈ����","����ũ��/�ڵ�","��ȹ��/ī��","�ʸ�/����/NTC","������ô�ü","������","����","�ؿ��ڵ�","��ü��","�����Կ�","��������","������ȸ��","�μ�","SP(����)","������/����ġ","�̺�Ʈ","�𵨿�������","�����а�","��Ÿ","���丮����","�����̼�/����");
	$arnum = count($job_type);
	for($i=0;$i<$arnum;$i++){
		echo "<option value=\"".$jikjonglist[$i]."\"";
		if($seljob_type==$jikjonglist[$i]) echo " selected";
		echo ">".$job_type[$i];
	}
?>
																						</select>
																						</span>
																					</TD>
																				</TR>

																				<TR>
																					<TD colspan="4" background="images/table_con_line.gif"></TD>
																				</TR>
																				<TR>
																					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">����</TD>
																					<TD class="td_con1">
																						<input type=checkbox id="idx_jobgroup1" name=jikgun_all value="ALL" style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;" <?if($job_group=="ALL" OR $job_group=="") echo "checked"?> onclick="idx_jobgroupSel.disabled=this.checked;">
																						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_jobgroup1>��ü</label>
																						<span id="idx_jobgroupSel" <?=$jobgroupDisabled?>>
																						<select name=seljob_group class="select">
<?
	$jikgunlist=array("1","2","4","8","16","32","64","128","256","512","1024","2048","4096","8192","16384","32768","65536","131072","262144","524288","1048576");
	$job_group= array ("CD","PD","CW","�����̳�","AE","������","����","������","����","�����繫","��Ʈ����","�÷���","TD","��Ÿ�ϸ���Ʈ","Į�󸮽�Ʈ","����ȫ��","����","�л�","��Ÿ","����","ĳ���õ���");
	$arnum = count($job_group);
	for($i=0;$i<$arnum;$i++){
		echo "<option value=\"".$jikgunlist[$i]."\"";
		if($seljob_group==$jikgunlist[$i]) echo " selected";
		echo ">".$job_group[$i];
	}
?>
																						</select>
																						</span>
																					</TD>
																				</TR>


																				<TR>
																					<TD colspan="4" background="images/table_con_line.gif"></TD>
																				</TR>
																				<tr>
																					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�⺯�� ȸ��</TD>
																					<TD class="td_con1" colspan="3">
																						<input type=checkbox id="idx_groupmember1" name=groupmember value="NO" style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;" <? if($groupmember !="YES" && $groupmember !="ONE") echo "checked"?> onclick="ChangeGroup(0)">
																						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_groupmember1>��ü</label>
																						&nbsp;&nbsp;
																						<input type=checkbox id="idx_groupmember2" name=groupmember value="YES" style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;" <?if($groupmember=="YES") echo "checked"?> onclick="ChangeGroup(1)">
																						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_groupmember2>��ü �⺯�� ȸ�� ����</label>
																						&nbsp;&nbsp;
																						<input type=checkbox id="idx_groupmember3" name=groupmember value="ONE" style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;" <?if($groupmember=="ONE") echo "checked"?> onclick="ChangeGroup(2)">
																						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_groupmember3>���� ��� ȸ�� ����</label>
																					</TD>
																				</tr>
																			</table>
																		</div>
																		<div id=layer2 style="margin-left:0;display:hide; display:<?=($searchtype=="O"?"block":"none")?> ;border-style:solid; border-width:0; border-color:black;background:#FFFFFF;padding:0;">
																			<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
																				<col width=138>

																						</col>

																				<col width=>

																						</col>

																				<col width=108>

																						</col>

																				<col width=206>

																						</col>

																				<tr>
																					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">���ų�¥��</TD>
																					<TD class="td_con1" colspan="3">
																						<input type=checkbox id="idx_buydate1" name=buydate value="ALL" style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;" <?if($buydate=="ALL" OR $buydate=="") echo "checked"?>>
																						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_buydate1>��ü</label>
																						<select name=buyyear1 onchange="ChangeCheck(5)" class="select">
																							<?
	if(strlen($buyyear1)==0) $temp = date("Y");
	else $temp=$buyyear1;
	for ($i=substr($regdate,0,4);$i<=date("Y");$i++) {
		if ($i==$temp)
			echo "<option value=\"$i\" selected>$i\n";
		else
			echo "<option value=\"$i\">$i\n";
	}
?>
																						</select>
																						��
																						<select name=buymonth1 onchange="ChangeCheck(5)" class="select">
																							<?
	if(strlen($buymonth1)==0) $curmonth = date("m");
	else $curmonth=$buymonth1;
	for ($i=1;$i<=12;$i++) {
		if ($i<10) $i2 = "0$i";
		else $i2 = $i;
		if ($i2 == $curmonth) echo "<option value=\"$i2\" selected>$i2\n";
		else echo "<option value=\"$i2\">$i2\n";
	}
?>
																						</select>
																						��
																						<select name=buyday1 onchange="ChangeCheck(5)" class="select">
																							<?
	if(strlen($buyday1)==0) $curday = date("d");
	else $curday=$buyday1;
	for ($i=1;$i<=31;$i++) {
		if ($i<10) $i2 = "0$i";
		else $i2 = $i;
		if ($i2 == $curday) echo "<option value=\"$i2\" selected>$i2\n";
		else echo "<option value=\"$i2\">$i2\n";
	}
?>
																						</select>
																						�� ~
																						<select name=buyyear2 onchange="ChangeCheck(5)" class="select">
																							<?
	if(strlen($buyyear2)==0) $temp = date("Y");
	else $temp=$buyyear2;
	for ($i=substr($regdate,0,4);$i<=date("Y");$i++) {
		if ($i==$temp)
			echo "<option value=\"$i\" selected>$i\n";
		else
			echo "<option value=\"$i\">$i\n";
	}
?>
																						</select>
																						��
																						<select name=buymonth2 onchange="ChangeCheck(5)" class="select">
																							<?
	if(strlen($buymonth2)==0) $curmonth = date("m");
	else $curmonth=$buymonth2;
	for ($i=1;$i<=12;$i++) {
		if ($i<10) $i2 = "0$i";
		else $i2 = $i;
		if ($i2 == $curmonth) echo "<option value=\"$i2\" selected>$i2\n";
		else echo "<option value=\"$i2\">$i2\n";
	}
?>
																						</select>
																						��
																						<select name=buyday2 onchange="ChangeCheck(5)" class="select">
																							<?
	if(strlen($buyday2)==0) $curday = date("d");
	else $curday=$buyday2;
	for ($i=1;$i<=31;$i++) {
		if ($i<10) $i2 = "0$i";
		else $i2 = $i;
		if ($i2 == $curday) echo "<option value=\"$i2\" selected>$i2\n";
		else echo "<option value=\"$i2\">$i2\n";
	}
?>
																						</select>
																						�� </TD>
																				</tr>
																				<TR>
																					<TD colspan="4" background="images/table_con_line.gif"></TD>
																				</TR>
																				<tr>
																					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">���űݾ׺�</TD>
																					<TD class="td_con1" colspan="3">
																						<input type=checkbox id="idx_price1" name=price value="ALL" style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;" <?if($price=="ALL" OR $price=="") echo "checked"?>>
																						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_price1>��ü</label>
																						<input type=text name=pricemin value="<?=$pricemin?>" size=8 maxlength=8 onclick="ChangeCheck(3)" class="input">
																						�� ����
																						<input type=text name=pricemax value="<?=$pricemax?>" size=8 maxlength=8 onclick="ChangeCheck(3)" class="input">
																						�� ����</TD>
																				</tr>
																				<TR>
																					<TD colspan="4" background="images/table_con_line.gif"></TD>
																				</TR>
																				<TR>
																					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">���ŰǼ���</TD>
																					<TD class="td_con1" colspan="3">
																						<input type=checkbox id="idx_ordercnt1" name=ordercnt value="ALL" style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;" <?if($ordercnt=="ALL" OR $ordercnt=="") echo "checked"?>>
																						<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_ordercnt1>��ü</label>
																						<input type=text name=ordercntmin value="<?=$ordercntmin?>" size=8 maxlength=8 onclick="ChangeCheck(4)" class="input">
																						�� ���� &nbsp;
																						<input type=text name=ordercntmax value="<?=$ordercntmax?>" size=8 maxlength=8 onclick="ChangeCheck(4)" class="input">
																						�� ����</TD>
																				</TR>
																			</table>
																		</div>
																		<div id=layer3 style="margin-left:0;display:hide; display:<?=($searchtype=="U"?"block":"none")?> ;border-style:solid; border-width:0; border-color:black;background:#FFFFFF;padding:0;">
																			<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
																				<col width=138>

																						</col>

																				<col width=>

																						</col>

																				<col width=108>

																						</col>

																				<col width=206>

																						</col>

																				<TR>
																					<TD class="table_cell" style="padding-bottom:10pt;"><img src="images/icon_point2.gif" width="8" height="11" border="0">Ư��ȸ�� �˻�</TD>
																					<TD class="td_con1" colspan="3" style="padding-bottom:10pt;">
																						<input type=text name=search value="<?=$search?>" class="input">
																						&nbsp;<span class="font_orange">*Ư��ȸ���� �̸�, ���̵�, ����ó, �̸����� �˻��մϴ�!</span></TD>
																				</TR>
																			</TABLE>
																		</div>
																	</TD>
																	<td></td>
																	<td></td>
																	<td></td>
																</TR>
															</TABLE>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td height=5></td>
							</tr>
							<tr>
								<td>
									<p align="center"><a href="javascript:CheckForm();"><img src="images/botteon_search1.gif" width="145" height="38" border="0" vspace="3"></a>
								</td>
							</tr>
							<tr>
								<td height="20"></td>
							</tr>
							<tr>
								<td>
									<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
										<TR>
											<TD><IMG SRC="images/member_groupmem_stitle1.gif" WIDTH="192" HEIGHT=31 ALT=""></TD>
											<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
											<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
										</TR>
									</TABLE>
								</td>
							</tr>
							<tr>
								<td>
									<p align="right">&nbsp;<FONT color=red><B>���Ĺ�� :</B></FONT> ������ <A href="javascript:GoSort('reserve_desc');"><B>��</B></A> <A href="javascript:GoSort('reserve_asc');"><B>��</B></A> &nbsp;|&nbsp; �̸� <A href="javascript:GoSort('name_asc');"><B>��</B></A> <A href="javascript:GoSort('name_desc');"><B>��</B></A>
								</td>
							</tr>
							<tr>
								<td>
									<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
										<TR>
											<TD background="images/table_top_line.gif" colspan="12" height=1></TD>
										</TR>
										<input type=hidden name=chkid>
										<TR align=center>
											<TD class="table_cell">����</TD>
											<TD class="table_cell1">���̵�</TD>
											<TD class="table_cell1">�޸�</TD>
											<TD class="table_cell1">��޸�</TD>
											<TD class="table_cell1">����</TD>
											<TD class="table_cell1">ȸ������</TD>
											<TD class="table_cell1">����</TD>
											<TD class="table_cell1">����</TD>
											<TD class="table_cell1">����</TD>
											<TD class="table_cell1">���űݾ�</TD>
											<TD class="table_cell1">���ŰǼ�</TD>
											<TD class="table_cell1">������</TD>
										</TR>
										<TR>
											<TD background="images/table_con_line.gif" colspan="12" height=1></TD>
										</TR>
<?
if ($type=="search") {
	if ($searchtype=="M" || $searchtype=="U") {
	$qry.= "WHERE member_out = 'N' ";
	if ($searchtype=="M" && $groupmember=="YES")
	$qry.= "AND group_code = '' ";
	else if ($searchtype=="M" && $groupmember=="ONE")
	$qry.= "AND (group_code = '' OR group_code != '".$group_code."') ";
	if ($searchtype=="U") {
	$qry.= "
		AND
			(
				name LIKE '%".$search."%'
				OR
				id LIKE '%".$search."%'
				OR
				mobile LIKE '%".$search."%'
				OR
				home_tel LIKE '%".$search."%'
				OR
				email LIKE '%".$search."%'
			)
	";
	} else if ($searchtype=="M") {
	//if($sex!="ALL" && $sex=="M") $qry.= "AND  MID(resno,7,1)%2=1 ";
	//else if($sex!="ALL" && $sex=="F") $qry.= "AND  MID(resno,7,1)%2=0 ";
	if($sex!="ALL" && $sex=="M") $qry.= "AND gender='1' ";
	else if($sex!="ALL" && $sex=="F") $qry.= "AND gender='2' ";
	if ($age!="ALL"){
		$start_year = (int)date("Y") - (int)$agemax +1;
		$end_year = (int)date("Y") - (int)$agemin +1;
		$s_year = substr((string)$start_year,2,2);
		$e_year = substr((string)$end_year,2,2);
		if ($start_year < 2000 && $end_year < 2000) {
			$qry.= "AND (LEFT(resno,2) BETWEEN '".$s_year."' AND '".$e_year."') ";
			$qry.= "AND MID(resno,7,1) < '3' ";
		} else if ($start_year < 2000 && $end_year > 1999) {
			$qry.= "AND (((LEFT(resno,2) BETWEEN '".$s_year."' AND '99') ";
			$qry.= "AND MID(resno,7,1) < '3') OR ((LEFT(resno,2) BETWEEN '00' AND '".$e_year."') ";
			$qry.= "AND MID(resno,7,1) > '2')) ";
		} else if ($start_year > 1999 && $end_year > 1999) {
			$qry.= "AND (LEFT(resno,2) BETWEEN '".$s_year."' AND '".$e_year."') ";
			$qry.= "AND MID(resno,7,1) > '2') ";
		}
	}
	if($birth!="ALL"){
		if($birthday!="ALL") $qry.= "AND MID(resno,3,4) = '".$birthmonth.$birthday."' ";
		else $qry.= "AND MID(resno,3,2) = '".$birthmonth."' ";
	}
	if($memregdate!="ALL"){
		$memregdate1 = substr($memregyear1,0,4).substr($memregmonth1,0,2).substr($memregday1,0,2)."000000";
		$memregdate2 = substr($memregyear2,0,4).substr($memregmonth2,0,2).substr($memregday2,0,2)."999999";
		$qry.= "AND date >= '".$memregdate1."' AND date <= '".$memregdate2."' ";
	}
	if($reserve!="ALL"){
		if(strlen($reservemin)!=0) $reserveminvalue=$reservemin;
		else $reserveminvalue=0;
		if(strlen($reservemax)!=0) $reservemaxvalue=$reservemax;
		else $reservemaxvalue=10000000000;
		$qry.= "AND reserve >= '".$reserveminvalue."' AND reserve <= '".$reservemaxvalue."' ";
	}
	if($addr!="ALL"){
		$qry.= "AND home_addr LIKE '".$seladdr."%' ";
	}

	if($gubun_all!="ALL"){
		$qry.= "AND gubun='".$selgubun."' ";
	}
	if($sosok_all!="ALL"){
		$qry.= "AND sosok LIKE '".$selsosok."%' ";
	}
	if($jikjong_all!="ALL"){
		$qry.= "AND jikjong='".$seljob_type."' ";
	}
	if($jikgun_all!="ALL"){
		$qry.= "AND jikgun='".$seljob_group."' ";
	}
}

$sql = "SELECT COUNT(*) as t_count FROM tblmember ";
$sql.= $qry;
$result = mysql_query($sql,get_db_conn());
$row = mysql_fetch_object($result);
$t_count = $row->t_count;
mysql_free_result($result);
$pagecount = (($t_count - 1) / $setup[list_num]) + 1;

$sql = "SELECT id, reserve, name, MID(resno,1,2) as age, MID(resno,7,1) as sex, ";
$sql.= "group_code, MID(home_addr,1,4) as addr, memo, gender,gubun, jikgun,jikjong FROM tblmember ";
$sql.= $qry." ";
if($sort=="reserve_desc") $sql.= "ORDER BY reserve DESC ";
else if($sort=="reserve_asc") $sql.= "ORDER BY reserve ";
else if($sort=="name_asc") $sql.= "ORDER BY name ";
else $sql.= "ORDER BY name DESC ";
$sql.= "LIMIT " . ($setup[list_num] * ($gotopage - 1)) . ", " . $setup[list_num];

$result=mysql_query($sql,get_db_conn());
$count=0;
while($row=mysql_fetch_object($result)){
$row->id=strtolower($row->id);
$arcount[$count]=$row->id;
$arreserve[$row->id]=$row->reserve;
$arname[$row->id]=$row->name;
$arsex[$row->id]=$row->gender;
$argubun[$row->id]=$row->gubun;
$arjob_group[$row->id]=$row->job_group;
$arage[$row->id]=$row->age;
if(!$row->group_code) $artrue[$row->id]="Y";
else $artrue[$row->id]="N";
$groupcode[$row->id]=$row->group_code;
$address[$row->id]=$row->addr;
$memo[$row->id]=$row->memo;
$mulid.="'$row->id',";
$count++;
}

$maxcnt=$count;
if($maxcnt>20) $count--;
$mulid=substr($mulid,0,-1);
mysql_free_result($result);
if($count!=0){
$sql = "SELECT COUNT(price) as cnt, SUM(price) as totalprice, id FROM tblorderinfo ";
$sql.= "WHERE deli_gbn = 'Y' AND id IN (".$mulid.") GROUP BY id";
$result=mysql_query($sql,get_db_conn());
while($row=mysql_fetch_object($result)){
	$row->id=strtolower($row->id);
	$arprice[$row->id]=$row->totalprice;
	$arcnt[$row->id]=$row->cnt;
}
mysql_free_result($result);
}
} else if ($searchtype=="O") {

$sql = "SELECT COUNT(price) as cnt, SUM(price) as totalprice, id FROM tblorderinfo ";
$sql.= "WHERE 1=1 ";
if($buydate!="ALL") {
$sql.= "AND (ordercode > '".$buyyear1.$buymonth1.$buyday1."000000' ";
$sql.= "AND ordercode < '".$buyyear2.$buymonth2.$buyday2."999999') ";
}
$sql.= "AND deli_gbn = 'Y' AND (MID(ordercode,15,2) != 'X' && MID(ordercode,15,1) != '|') ";
$sql.= "GROUP BY id ";
if($price!="ALL" || $ordercnt!="ALL") $sql.= "HAVING ";
if($price!="ALL"){
if(strlen($pricemin)!=0) $priceminvalue=$pricemin;
else $priceminvalue=0;
if(strlen($pricemax)!=0) $pricemaxvalue=$pricemax;
else $pricemaxvalue=10000000000;
$sql.= "totalprice >= '".$priceminvalue."' AND totalprice <= '".$pricemaxvalue."' ";
}
if($price!="ALL" && $ordercnt!="ALL") $sql.= "AND ";
if($ordercnt!="ALL"){
if(strlen($ordercntmin)!=0) $ordercntminvalue=$ordercntmin;
else $ordercntminvalue=0;
if(strlen($ordercntmax)!=0) $ordercntmaxvalue=$ordercntmax;
else $ordercntmaxvalue=10000000000;
$sql.= "cnt >= '".$ordercntminvalue."' AND cnt <= '".$ordercntmaxvalue."' ";
}
$result = mysql_query($sql,get_db_conn());
$t_count = mysql_num_rows($result);
mysql_free_result($result);
$pagecount = (($t_count - 1) / $setup[list_num]) + 1;
if($sort=="topcnt") $sql.= "ORDER BY cnt DESC ";
else if($sort=="bottomcnt") $sql.= "ORDER BY cnt ";
else if($sort=="bottomprice") $sql.= "ORDER BY totalprice ";
else $sql.= "ORDER BY totalprice DESC ";
$sql.= "LIMIT " . ($setup[list_num] * ($gotopage - 1)) . ", " . $setup[list_num];
$result=mysql_query($sql,get_db_conn());
while($row=mysql_fetch_object($result)){
$row->id=strtolower($row->id);
$arcount[$count]=$row->id;
$arprice[$row->id]=$row->totalprice;
$arcnt[$row->id]=$row->cnt;
$mulid.="'$row->id',";
$count++;
}				$maxcnt=$count;
if($maxcnt>20) $count--;
$mulid=substr($mulid,0,-1);
mysql_free_result($result);
if ($count!=0) {
$sql = "SELECT id, MID(resno,1,2) as age, MID(resno,7,1) as sex, reserve, name, ";
$sql.= "group_code, MID(home_addr,1,4) as addr, memo, gender,gubun, jikgun FROM tblmember ";
$sql.= "WHERE id IN (".$mulid.") ";

$result=mysql_query($sql,get_db_conn());
while($row=mysql_fetch_object($result)){
	$row->id=strtolower($row->id);
	if(!$row->group_code) $artrue[$row->id]="Y";
	else $artrue[$row->id]="N";
	$arreserve[$row->id]=$row->reserve;
	$arname[$row->id]=$row->name;
	$arsex[$row->id]=$row->gender;
	$argubun[$row->id]=$row->gubun;
	$arjob_group[$row->id]=$row->job_group;
	$arage[$row->id]=$row->age;
	$groupcode[$row->id]=$row->group_code;
	$address[$row->id]=$row->addr;
	$memo[$row->id]=$row->memo;
}
mysql_free_result($result);
}
}
$lineage=100+date("y");
$totalcheck=0;
// ȸ�� ����Ʈ ���
for($i=0;$i<$count;$i++) {
$_gender="";
$_groupname="";
switch($arsex[$arcount[$i]]){
case "1":
	$_gender="����";
break;
case "2":
	$_gender="����";
break;
default:
	$_gender="-";
break;
}

if(($searchtype!="O" && $groupcode[$arcount[$i]]=="") || ($searchtype=="O" && $artrue[$arcount[$i]]=="Y")) {
$_groupname = $arname[$arcount[$i]];
} else if($artrue[$arcount[$i]]=="N" || $groupcode[$arcount[$i]]!="") {
//echo "<font color=#AA0000 title='�̸� : ".$arname[$arcount[$i]]."\n�ش� ��޸� : ".$grouptitle[$groupcode[$arcount[$i]]]['name']."'><u>".titleCut(15,$grouptitle[$groupcode[$arcount[$i]]]['name'])."</font>\n";
$_groupname = (titleCut(15,$grouptitle[$groupcode[$arcount[$i]]]['name']) != "")?titleCut(15,$grouptitle[$groupcode[$arcount[$i]]]['name']):"-";
} else {
$_groupname = "���� ȸ��";
}
$bgcolor="#FFFFFF";
if($searchtype!="O" && strlen($arprice[$arcount[$i]])>0) {
$bgcolor="#FEFAAB";
}
echo "<tr>\n";
echo "	<TD align=center class=\"td_con2\">";
if($artrue[$arcount[$i]]!=""){
$totalcheck++;
echo "<input type=checkbox name=chkid value=\"".$arcount[$i]."\" style=\"BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none\">";
}
echo "	</td>\n";
echo "	<TD align=center class=\"td_con1\"><span class=\"font_orange\"><b>".$arcount[$i]."</b></span></TD>\n";
echo "	<TD align=center class=\"td_con1\"><NOBR>";
if (strlen(trim($memo[$arcount[$i]]))>0) {
echo "<img src=\"images/btn_memo.gif\" width=\"35\" height=\"29\" border=\"0\" onMouseOver=\"UserMemoView(divmemo_".$i.",'visible')\" onMouseOut=\"UserMemoView(divmemo_".$i.",'hidden')\">";
} else {
echo "<img src=\"images/btn_memor.gif\" width=\"35\" height=\"29\" border=\"0\">";
}
echo "	<div id=\"divmemo_".$i."\" style=\"position:absolute; z-index:5; width:250; filter:revealTrans(duration=0.3); visibility:hidden;\">\n";
echo "	<table border=0 cellspacing=0 cellpadding=1 bgcolor=#7F7F65>\n";
echo "	<tr>\n";
echo "		<td><font color=#ffffff>&nbsp;".$memo[$arcount[$i]]."&nbsp;</td>\n";
echo "	</tr>\n";
echo "	</table>\n";
echo "	</div>\n";
echo "	</td>\n";
echo "	<TD align=center class=\"td_con1\">";
echo $_groupname;
echo "	</td>\n";
echo "	<TD align=center class=\"td_con1\">".($address[$arcount[$i]])."&nbsp;</td>\n";
echo "	<TD align=center class=\"td_con1\">".$argubun[$arcount[$i]]."&nbsp;</td>\n";
echo "	<TD align=center class=\"td_con1\">".$arjob_group[$arcount[$i]]."&nbsp;</td>\n";
echo "	<TD align=center class=\"td_con1\">";
echo $_gender; 
echo "</td>\n";
echo "	<TD align=center class=\"td_con1\">".(strlen($arage[$arcount[$i]])==0?"&nbsp;":$lineage-$arage[$arcount[$i]])."</td>\n";
echo "	<TD align=right class=\"td_con1\"><span class=\"font_orange\"><b>".number_format($arprice[$arcount[$i]])."��</b></span></td>\n";
echo "	<TD align=right class=\"td_con1\">".number_format($arcnt[$arcount[$i]])."��</td>\n";
echo "	<TD align=right class=\"td_con1\">".number_format($arreserve[$arcount[$i]])."��&nbsp;<a href=\"javascript:ReserveInfo('".$arcount[$i]."');\"><img src=\"images/btn_detail.gif\" width=\"35\" height=\"29\" border=\"0\" align=absMiddle></a></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "	<TD background=\"images/table_con_line.gif\" colspan=\"12\" height=1></TD>\n";
echo "</tr>\n";
}
}
if($count==0) {
echo "<tr><td class=\"td_con2\" colspan=12 align=center>�˻��� ȸ���� �����ϴ�.</td></tr>\n";
}
?>
										<TR>
											<TD background="images/table_top_line.gif" colspan="12" height=1></TD>
										</TR>
									</TABLE>
								</td>
							</tr>
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
echo "<tr>\n";
echo "	<td height=\"24\">\n";
echo "	<input type=checkbox name=allcheck value=\"".$totalcheck."\" style=\"BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;\" onclick=\"CheckAll('".$totalcheck."')\">&nbsp;<font color=#0054A6>page ��ü ȸ�� ����</font>";
echo "	</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "	<td height=\"10\"></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "	<td width=\"100%\" class=\"font_size\" align=center>\n";
echo "		".$a_div_prev_page.$a_prev_page.$print_page.$a_next_page.$a_div_next_page;
echo "	</td>\n";
echo "</tr>\n";
?>
							<tr><td height="20"></td></tr>
							<tr>
								<td>
									<table cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td width="100%" bgcolor="#ededed" style="padding:4pt;">
												<table cellpadding="0" cellspacing="0" width="100%" bgcolor="white">
													<tr>
														<td width="100%">
															<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
																<TR>
																	<TD colspan="4" background="images/table_con_line.gif"></TD>
																</TR>
																<TR>
																	<TD height="35" align=center background="images/blueline_bg.gif"><b><font color="#555555">���� ��� ����</font></b></TD>
																	<td></td>
																	<td></td>
																	<td></td>
																</TR>
																<TR>
																	<TD>
																		<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
																			<col width=138>

																					</col>

																			<col width=>

																					</col>

																			<col width=108>

																					</col>

																			<col width=206>

																					</col>

																			<TR>
																				<TD colspan="4" background="images/table_con_line.gif"></TD>
																			</TR>
																			<TR>
																				<TD class="table_cell" style="padding-top:10pt;"><img src="images/icon_point2.gif" width="8" height="11" border="0">ȸ����� ����</TD>
																				<TD class="td_con1" style="padding-top:10pt;">
																					<select name=group_code onchange="GoGroupCode( this.value );" style="width:90%" class="select">
																						<option value="">�ش� ����� �����ϼ���.
																						<?
																							foreach($grouptitle as $gcode=>$gname){
																								$sel = ($group_code==$gcode) ? " selected" : "" ;
																								echo "<option value=\"".$gname['code']."\">".$gname['name']."</option>\n";
																							}
																						?>
																					</select>
																				</TD>
																				<TD class="table_cell1" style="padding-top:10pt;"><img src="images/icon_point2.gif" width="8" height="11" border="0">��� ȸ����</TD>
																				<TD class="td_con1" style="padding-top:10pt;">
																					<span id="groupInfoCNT">0</span>�� </TD>
																			</TR>
																			<TR>
																				<TD colspan="4" background="images/table_con_line.gif"></TD>
																			</TR>
																			<TR>
																				<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">ȸ����� ����</TD>
																				<TD class="td_con1" colspan="3">&nbsp;
																					<span id="groupInfoMemo"></span>
																				</TD>
																			</TR>
																		</TABLE>
																	</TD>
																	<td></td>
																	<td></td>
																	<td></td>
																</TR>
															</TABLE>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td>
									<p align="center"><a href="javascript:InsertGroup('<?=$totalcheck?>');"><img src="images/botteon_register.gif" border="0" vspace="3"></a>
								</td>
							</tr>
							</form>
							<form name=form2 action="member_reservelist.php" method=post target=reserve_info>
								<input type=hidden name=id>
								<input type=hidden name=type>
							</form>
							<form name=form3 method=post>
								<input type=hidden name=block value="<?=$block?>">
								<input type=hidden name=gotopage value="<?=$gotopage?>">
							</form>
							<tr>
								<td>&nbsp;</td>
							</tr>
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
											<TD COLSPAN=3 width="100%" valign="top" class=menual_bg style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;">
												<table cellpadding="0" cellspacing="0" width="100%">
													<tr>
														<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
														<td >
															<p><span class="font_dotline">ȸ����� ����/�˻� ���</span>
														</td>
													</tr>
													<tr>
														<td width="20" align="right">&nbsp;</td>
														<td  class="space_top">
															<p>- �̵��� ����� ���� �� �˻����غ��� �˻����ǵ��� �����ؾ߸� �˻��� �����մϴ�.
														</td>
													</tr>
													<tr>
														<td width="20" align="right">&nbsp;</td>
														<td  class="space_top">
															<p>- �˻��� �� �ش� ȸ������ ����� �̵��� �� �ֽ��ϴ�.
														</td>
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
				<tr>
					<td height="20"></td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<?=$onload?>
<? INCLUDE "copyright.php"; ?>