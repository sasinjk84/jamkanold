<?
	$Dir="../";
	include_once($Dir."lib/init.php");
	include_once($Dir."lib/lib.php");
	include_once($Dir."lib/shopdata.php");

	if(strlen($_ShopInfo->getMemid())>0) {
		header("Location:mypage_usermodify.php");
		if( $preview===false ) exit;
	}

	$leftmenu="Y";
	$sql="SELECT body,leftmenu FROM ".$designnewpageTables." WHERE type='joinagree'";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$body=$row->body;
		$body=str_replace("[DIR]",$Dir,$body);
		$leftmenu=$row->leftmenu;
		$newdesign="Y";
	}
	mysql_free_result($result);
?>

<HTML>
<HEAD>
<TITLE><?=$_data->shoptitle?> - ȸ������</TITLE>
<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">
<META http-equiv="X-UA-Compatible" content="IE=Edge" />
<META name="description" content="<?=(strlen($_data->shopdescription)>0?$_data->shopdescription:$_data->shoptitle)?>">
<META name="keywords" content="<?=$_data->shopkeyword?>">
<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
<script type="text/javascript" src="/js/jquery-1.8.3.min.js"></script>
<script language="javascript" type="text/javascript">
$j = jQuery.noConflict();
</script>
<?include($Dir."lib/style.php")?>
<SCRIPT LANGUAGE="JavaScript">
<!--
//�Ϲ�ȸ�� üũ
function CheckForm() {
	if(!document.form1.agree || document.form1.agree.checked==false) {
		alert("ȸ������� �����ϼž� ȸ�������� �����մϴ�.");
		if(document.form1.agree) {
			document.form1.agree.focus();
		}
		return;
	} else if(!document.form1.agreep || document.form1.agreep.checked==false) {
		alert("���κ�ȣ��޹�ħ�� �����ϼž� ȸ�������� �����մϴ�.");
		if(document.form1.agreep) {
			document.form1.agreep.focus();
		}
		return;
	} else if(confirm("���� ȸ������ �����Ͻý��ϱ�?")) {
		document.form1.submit();
	} else {
		return;
	}
}

//����ȸ�� üũ
function CheckForm2() {
	if(!document.form2.agree || document.form2.agree.checked==false) {
		alert("ȸ������� �����ϼž� ȸ�������� �����մϴ�.");
		if(document.form2.agree) {
			document.form2.agree.focus();
		}
		return;
	} else if(!document.form2.agreep || document.form2.agreep.checked==false) {
		alert("���κ�ȣ��޹�ħ�� �����ϼž� ȸ�������� �����մϴ�.");
		if(document.form2.agreep) {
			document.form2.agreep.focus();
		}
		return;
	} else if(confirm("����ȸ������ �����Ͻðڽ��ϱ�?")) {
		document.form2.submit();
	} else {
		return;
	}
}

//�� ó��
function TabMenu(index) {
	for (i=1; i<=2; i++)
		if (index == i) {
			thisMenu = eval("member" + index + ".style");
			thisMenu.display = "";
		} else {
			otherMenu = eval("member" + i + ".style");
			otherMenu.display = "none";
		}
}
//-->
</SCRIPT>
</HEAD>

<body<?=(substr($_data->layoutdata["MOUSEKEY"],0,1)=="Y"?" oncontextmenu=\"return false;\"":"")?><?=(substr($_data->layoutdata["MOUSEKEY"],1,1)=="Y"?" ondragstart=\"return false;\" onselectstart=\"return false;\"":"")?> leftmargin="0" marginwidth="0" topmargin="0" marginheight="0"><?=(substr($_data->layoutdata["MOUSEKEY"],2,1)=="Y"?"<meta http-equiv=\"ImageToolbar\" content=\"No\">":"")?>

<? include ($Dir.MainDir.$_data->menu_type.".php") ?>

<!-- ȸ������ ��� ��� �޴� -->
<div class="currentTitle">
	<div class="titleimage">ȸ������</div>
	<!--<div class="current"><img src="/data/design/img/sub/icon_home.gif" border="0" alt="" /> Ȩ &gt; ���������� &gt; <SPAN class="nowCurrent">ȸ������</span></div>-->
</div>
<!-- ȸ������ ��� ��� �޴� -->

<div style="overflow:hidden;width:70%;margin:0px auto;">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
if ($leftmenu!="N") {
	echo "<tr>\n";
	if ($_data->title_type=="Y" && file_exists($Dir.DataDir."design/memberjoin_title.gif")) {
		echo "<td><img src=\"".$Dir.DataDir."design/memberjoin_title.gif\" border=\"0\" alt=\"ȸ������\"></td>\n";
	} else {
		echo "<td>\n";
		/*
		echo "<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>\n";
		echo "<TR>\n";
		echo "	<TD><IMG SRC=".$Dir."images/".$_data->icon_type."/memberjoin_title_head.gif ALT=></TD>\n";
		echo "	<TD width=100% valign=top background=".$Dir."images/".$_data->icon_type."/memberjoin_title_bg.gif></TD>\n";
		echo "	<TD width=40><IMG SRC=".$Dir."images/".$_data->icon_type."/memberjoin_title_tail.gif ALT=></TD>\n";
		echo "</TR>\n";
		echo "</TABLE>\n";
		*/
		echo "</td>\n";
	}
	echo "</tr>\n";
}

$sql="SELECT agreement,agreement2,privercy FROM tbldesign ";
$result=mysql_query($sql,get_db_conn());
$row=mysql_fetch_object($result);
$agreement=$row->agreement;
$agreement2=$row->agreement2;
$privercy_exp=@explode("=", $row->privercy);
$privercy=$privercy_exp[1];
mysql_free_result($result);

//�Ϲ�ȸ�� ���
if(strlen($agreement)==0) {
	$buffer="";
	$fp=fopen($Dir.AdminDir."agreement.txt","r");
	if($fp) {
		while (!feof($fp)) {
			$buffer.= fgets($fp, 1024);
		}
	}
	fclose($fp);
	$agreement=$buffer;
}

//����ȸ�� ���
if(strlen($agreement2)==0) {
	$buffer2="";
	$fp=fopen($Dir.AdminDir."agreement2.txt","r");
	if($fp) {
		while (!feof($fp)) {
			$buffer2.= fgets($fp, 1024);
		}
	}
	fclose($fp);
	$agreement2=$buffer2;
}

$pattern=array("(\[SHOP\])","(\[COMPANY\])");
$replace=array($_data->shopname, $_data->companyname);
$agreement = preg_replace($pattern,$replace,$agreement);
$agreement2 = preg_replace($pattern,$replace,$agreement2);

if(strlen($privercy)==0) {
	$buffer="";
	$fp=fopen($Dir.AdminDir."privercy2.txt","r");
	if($fp) {
		while (!feof($fp)) {
			$buffer.= fgets($fp, 1024);
		}
	}
	fclose($fp);
	$privercy=$buffer;
}

$pattern=array("(\[SHOP\])","(\[NAME\])","(\[EMAIL\])","(\[TEL\])");
$replace=array($_data->shopname,$_data->privercyname,"<a href=\"mailto:".$_data->privercyemail."\">".$_data->privercyemail."</a>",$_data->info_tel);
$privercy = preg_replace($pattern,$replace,$privercy);

$wholesalemember = mysql_result(mysql_query("select wholesalemember from tblshopinfo limit 1",get_db_conn()),0,0);

if($newdesign=="Y") {	//����������
	$pattern=array("(\[CONTRACT\])","(\[PRIVERCY\])","(\[CHECK\])","(\[CHECKP\])","(\[OK\])","(\[REJECT\])");

	$replace=array($agreement,$privercy,"<input type=checkbox  class=checkbox id=\"idx_agree\" name=agree style=\"border:none;\"> <label style='cursor:hand;' onmouseover=\"style.textDecoration='underline'\" onmouseout=\"style.textDecoration='none'\" for=idx_agree>","<input type=checkbox class=checkbox id=\"idx_agreep\" name=agreep style=\"border:none;\"> <label style='cursor:hand;' onmouseover=\"style.textDecoration='underline'\" onmouseout=\"style.textDecoration='none'\" for=idx_agreep>","javascript:CheckForm()","javascript:history.go(-1)");
	$body=preg_replace($pattern,$replace,$body);
	echo "<tr>\n";
	echo "	<td align=center>".$body."</td>";
	echo "</tr>\n";
	echo "<tr>\n";
} else {

if($wholesalemember == 'N' && $memtype == "C"){
	echo "<script language=javascript>alert('����ȸ�� ��å�� �����ڿ� ���� ������ �ʽ��ϴ�.');history.back(-1);</script>";
}
?>
	<tr>
		<td>
			<table align="center" cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
				<tr>
					<td>
						<table cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td style="text-align:center;color:#bbbbbb;">
										ȸ�������� �Ͻø� ���θ����� ��ϴ� ���� �̺�Ʈ�� �����Ͻ� �� �ֽ��ϴ�.<br />
										���� ���� ���θ����� ������ ��õ��ǰ �� �̺�Ʈ ���� �� �پ��� ���� ������ ���Ϸ� ������ �� �ֽ��ϴ�.
								</td>
							</tr>
							<tr>
								<td>
									<!-- �Ϲ�ȸ�� ȸ������ ���/�������� ��޹�ħ START -->
									<? if($memtype == "C"){ ?>
									<div id="member1" style="display:none;">
									<? }else{ ?>
									<div id="member1" style="display:;">
									<? } ?>
										<table border="0" cellpadding="0" cellspacing="0" width="100%">
											<form name="form1" action="member_join.php" method="post">
											<? if($wholesalemember == 'Y'){ ?>
											<tr>
												<td>
													<table border="0" cellpadding="0" cellspacing="0" width="100%">
														<tr>
															<td><img src="/images/common/tab_member_on.gif" border="0"></a></td>
															<td onClick="TabMenu(2)" style="consor:hand;"><img src="/images/common/tab_company_off.gif"></td>
															<td width="100%" background="/images/common/tab_bg.gif"></td>
														</tr>
													</table>
												</td>
											</tr>
											<? } ?>
											<tr>
												<td>

												<div style="margin-bottom:50px;margin-top:50px;">
													<P STYLE="font-size:20px;font-weight:bold;color:#333333;margin:20px 0px;border-left:2px solid #333333;padding-left:7px;line-height:15px;">ȸ�����</P>
													<DIV style="padding:5px;overflow-y:auto;height:170px;border:1px solid #dddddd;margin:10px 0px;"><?=$agreement?></DIV>
													<p style="text-align:right;"><INPUT id="idx_agree" type="checkbox" class="checkbox" name="agree" style="position:relative;top:2px;border:none;"> <label style='cursor:hand;' onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for=idx_agree>���� ȸ������� �����մϴ�.</LABEL></p>
												</div>

												<div style="margin-bottom:50px;">
													<P STYLE="font-size:20px;font-weight:bold;color:#333333;margin:20px 0px;border-left:2px solid #333333;padding-left:7px;line-height:15px;">�������� ��޹�ħ</P>
													<DIV style="padding:5px;overflow-y:auto;height:170px;border:1px solid #dddddd;margin:10px 0px;"><?=$privercy?></DIV>
													<p style="text-align:right;"><INPUT id="idx_agreep" type="checkbox" class="checkbox" name="agreep" style="position:relative;top:2px;border:none;"> <label style='cursor:hand;' onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for=idx_agreep>���� ����������޹�ħ�� �����մϴ�.</LABEL></p>
												</div>


												</td>
											</tr>
											<tr>
												<td align="center"><A HREF="javascript:CheckForm()" class="btn_grayB"><span>ȸ������</span></a><!--<A HREF="javascript:history.go(-1);"><img src="<?=$Dir?>images/btn_mback.gif" border="0" hspace="5"></a>--></td>
											</tr>
											</form>
										</table>
									</div>
									<!-- �Ϲ�ȸ�� ȸ������ ���/�������� ��޹�ħ END -->

									<? if($wholesalemember == 'Y'){ ?>
									<!-- ����ȸ�� ȸ������ ���/�������� ��޹�ħ START -->
									<? if($memtype == "C"){ ?>
									<div id="member2" style="display:;">
									<? }else{ ?>
									<div id="member2" style="display:none;">
									<? } ?>
										<table border="0" cellpadding="0" cellspacing="0" width="100%">
											<form name="form2" action="member_join2.php" method="post">
											<tr>
												<td>
													<table border="0" cellpadding="0" cellspacing="0" width="100%">
														<tr>
															<td onClick="TabMenu(1)" style="consor:hand;"><img src="/images/common/tab_member_off.gif" border="0"></a></td>
															<td><img src="/images/common/tab_company_on.gif"></td>
															<td width="100%" background="/images/common/tab_bg.gif" align="right"><font style="color:#FF6600; font-size:11px; letter-spacing:-0.04em;">* ����ȸ���� <b>���� �� �������� ����</b>�� �ʿ�� �մϴ�.</font></td>
														</tr>
													</table>
												</td>
											</tr>
											<tr><td height="20"></td></tr>
											<tr>
												<td><IMG src="<?=$Dir?>images/join_yak_03.gif" border=0></td>
											</tr>
											<tr>
												<td align="center">
													<table border="0" cellpadding="0" cellspacing="0">
														<tr>
															<td background="<?=$Dir?>images/join_yak_t01.gif"><img src="<?=$Dir?>images/join_yak_t01_left.gif" border="0"></td>
															<td background="<?=$Dir?>images/join_yak_t01.gif"></td>
															<td align="right" background="<?=$Dir?>images/join_yak_t01.gif"><img src="<?=$Dir?>images/join_yak_t01_right.gif" border="0"></td>
														</tr>
														<tr>
															<td background="<?=$Dir?>images/join_yak_t02.gif"></td>
															<td>
																<TABLE width="100%" cellSpacing="0" cellPadding="0" border="0" style="TABLE-LAYOUT: fixed">
																	<TR>
																		<TD style="BORDER-RIGHT: #dfdfdf 1px solid; BORDER-TOP: #dfdfdf 1px solid; BORDER-LEFT: #dfdfdf 1px solid; BORDER-BOTTOM: #dfdfdf 1px solid" bgColor="#ffffff"><DIV style="PADDING:5px;OVERFLOW-Y:auto;OVERFLOW-X:auto;HEIGHT:250px"><?=$agreement2?></DIV></TD>
																	</TR>
																</TABLE>
															</td>
															<td background="<?=$Dir?>images/join_yak_t04.gif"></td>
														</tr>
														<tr>
															<td background="<?=$Dir?>images/join_yak_t03.gif"><img src="<?=$Dir?>images/join_yak_t03_left.gif" border="0"></td>
															<td background="<?=$Dir?>images/join_yak_t03.gif"></td>
															<td align="right" background="<?=$Dir?>images/join_yak_t03.gif"><img src="<?=$Dir?>images/join_yak_t03_right.gif" border="0"></td>
														</tr>
													</table>
												</td>
											</tr>
											<tr><td height="10"></td></tr>
											<tr>
												<td align="center"><INPUT id="idx_agree2" type="checkbox" class="checkbox" name="agree" style="border:none;"> <label style='cursor:hand;' onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for="idx_agree2">���� ȸ������� �����մϴ�.</LABEL></td>
											</tr>
											<tr><td height="20"></td></tr>
											<tr><td><IMG src="<?=$Dir?>images/join_yak_02.gif" border="0"></td></tr>
											<tr>
												<td align="center">
													<table cellpadding="0" cellspacing="0">
														<tr>
															<td background="<?=$Dir?>images/join_yak_t01.gif"><img src="<?=$Dir?>images/join_yak_t01_left.gif" border="0"></td>
															<td background="<?=$Dir?>images/join_yak_t01.gif"></td>
															<td align="right" background="<?=$Dir?>images/join_yak_t01.gif"><img src="<?=$Dir?>images/join_yak_t01_right.gif" border="0"></td>
														</tr>
														<tr>
															<td background="<?=$Dir?>images/join_yak_t02.gif"></td>
															<td>
																<TABLE width="100%" cellSpacing="0" cellPadding="0" border="0" style="TABLE-LAYOUT: fixed">
																	<TR>
																		<TD style="BORDER-RIGHT: #dfdfdf 1px solid; BORDER-TOP: #dfdfdf 1px solid; BORDER-LEFT: #dfdfdf 1px solid; BORDER-BOTTOM: #dfdfdf 1px solid" bgColor="#ffffff"><DIV style="PADDING-RIGHT: 10px; OVERFLOW-Y: auto; PADDING-LEFT: 10px; OVERFLOW-X: auto; PADDING-BOTTOM: 10px; PADDING-TOP: 10px; HEIGHT: 250px"><?=$privercy?></DIV></TD>
																	</TR>
																</TABLE>
															</td>
															<td background="<?=$Dir?>images/join_yak_t04.gif"></td>
														</tr>
														<tr>
															<td background="<?=$Dir?>images/join_yak_t03.gif"><img src="<?=$Dir?>images/join_yak_t03_left.gif" border="0"></td>
															<td background="<?=$Dir?>images/join_yak_t03.gif"></td>
															<td align="right" background="<?=$Dir?>images/join_yak_t03.gif"><img src="<?=$Dir?>images/join_yak_t03_right.gif" border="0"></td>
														</tr>
													</table>
												</td>
											</tr>
											<tr><td height="10"></td></tr>
											<tr>
												<td align="center"><INPUT id="idx_agreep2" type="checkbox" class="checkbox" name="agreep" style="border:none;"> <label style='cursor:hand;' onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for="idx_agreep2">���� ����������޹�ħ�� �����մϴ�.</LABEL></td>
											</tr>
											<tr><td height="20"></td></tr>
											<tr>
												<td align="center"><A HREF="javascript:CheckForm2()"><img src="<?=$Dir?>images/btn_mjoin.gif" border="0"></a><A HREF="javascript:history.go(-1);"><img src="<?=$Dir?>images/btn_mback.gif" border="0" hspace="5"></a></td>
											</tr>
											</form>
										</table>
									</div>
									<? } ?>
									<!-- �Ϲ�ȸ�� ȸ������ ���/�������� ��޹�ħ END -->

								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td height="20"></td></tr>
<?
}
?>
</table>
</div>

<? include ($Dir."lib/bottom.php") ?>

</BODY>
</HTML>