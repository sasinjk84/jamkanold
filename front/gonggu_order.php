<?
	$Dir="../";
	include_once($Dir."lib/init.php");
	include_once($Dir."lib/lib.php");
	include_once($Dir."lib/shopdata.php");
	$sleftMn = "NO";

	$email ="";$mobile ="";
	if(strlen($_ShopInfo->getMemid())>0) {
		$sql = "SELECT * FROM tblmember WHERE id='".$_ShopInfo->getMemid()."' ";
		$result = mysql_query($sql);
		if($row = mysql_fetch_object($result)) {
			$email = $row->email;
			if (strlen($row->mobile)>0) $mobile = $row->mobile;
		}
	}
?>

<HTML>
	<HEAD>
		<TITLE><?=$_data->shoptitle?> �����μ� > �������� > �������Ž�û</TITLE>
		<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">
		<META http-equiv="X-UA-Compatible" content="IE=Edge" />

		<META name="description" content="<?=(strlen($_data->shopdescription)>0?$_data->shopdescription:$_data->shoptitle)?>">
		<META name="keywords" content="<?=$_data->shopkeyword?>">
		<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
		<script type="text/javascript" src="<?=$Dir?>lib/jquery-1.4.2.min.js"></script>
		<?include($Dir."lib/style.php")?>
	</HEAD>

	<body<?=(substr($_data->layoutdata["MOUSEKEY"],0,1)=="Y"?" oncontextmenu=\"return false;\"":"")?><?=(substr($_data->layoutdata["MOUSEKEY"],1,1)=="Y"?" ondragstart=\"return false;\" onselectstart=\"return false;\"":"")?> leftmargin="0" marginwidth="0" topmargin="0" marginheight="0"><?=(substr($_data->layoutdata["MOUSEKEY"],2,1)=="Y"?"<meta http-equiv=\"ImageToolbar\" content=\"No\">":"")?>

	<? include ($Dir.MainDir.$_data->menu_type.".php") ?>

		<!-- �������� ������ ��� �޴� -->
		<div class="currentTitle">
			<div class="titleimage">��������</div>
			<!--<div class="current"><img src="/data/design/img/sub/icon_home.gif" border="0" alt="" /> Ȩ &gt; <SPAN class="nowCurrent">�α���</span></div>-->
		</div>
		<!-- �������� ������ ��� �޴� -->

		<div style="clear:both;height:6px;background:url('/data/design/img/main/top_boxline.gif') no-repeat;font-size:0px;"></div>
		<div style="padding:20px 30px;background:#ffffff url('/data/design/img/main/bg_boxline.gif') repeat-y;overflow:hidden;">
			<table align="center" cellpadding="0" cellspacing="0" width="100%" class="table_td">
				<tr>
					<td>
						<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>
							<TR>
								<TD><a href="../front/gonggu_main.php"><IMG SRC="../images/design/gonggu_tap01.gif"  ALT="" border="0"></a></TD>
								<TD><a href="../front/gonggu_end.php"><IMG SRC="../images/design/gonggu_tap02.gif" ALT="" border="0"></a></TD>
								<TD><a href="../front/gonggu_order.php"><IMG SRC="../images/design/gonggu_tap03r.gif"  ALT="" border="0"></a></TD>
								<TD><a href="../front/gonggu_guide.php"><IMG SRC="../images/design/gonggu_tap04.gif"  ALT="" border="0"></a></TD>
								<TD width="100%" background="../images/design/gonggu_tap_bg.gif"></TD>
							</TR>
						</TABLE>
					</td>
				</tr>
				<tr><td height="30"></td></tr>
				<tr>
					<td style="border:1px solid #dddddd;">
						<table cellpadding="0" cellspacing="0" width="100%">
							<!--
							<tr>
								<td width="4"><IMG SRC="../images/design/gonggu_order_t01.gif" WIDTH=4 HEIGHT=4 ALT=""></td>
								<td background="../images/design/gonggu_order_tbg01.gif"></td>
								<td width="4"><img src="../images/design/gonggu_order_t02.gif" width="4" height="4" border="0"></td>
								<td background="../images/design/gonggu_order_tbg01.gif" width="280"></td>
								<td width="4" align="right"><IMG SRC="../images/design/gonggu_order_t03.gif" WIDTH="4" HEIGHT="4" ALT=""></td>
							</tr>
							-->
							<tr>
								<!--<td width="4" background="../images/design/gonggu_order_tbg02.gif"></td>-->
								<td style="padding:15px;" background="../images/design/gonggu_order_tbg.jpg" valign="top">
									<table cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td><IMG SRC="../images/design/gonggu_order_title01.gif" WIDTH=169 HEIGHT=20 ALT=""></td>
										</tr>
										<tr><td height=10></td></tr>
										<tr>
											<td class="gongguBest"></td>
										</tr>
									</table>
								</td>
								<!--<td width="4" background="../images/design/gonggu_order_tbg04.gif"></td>-->
								<td style="padding:15px;" width="280" background="../images/design/gonggu_order_tbg.jpg" valign="top" align="center"><a href="../front/gonggu_guide.php"><IMG SRC="../images/design/gonggu_order_btn01.gif"  ALT="" border="0"></a></td>
								<!--<td width="4" background="../images/design/gonggu_order_tbg06.gif"></td>-->
							</tr>
							<!--
							<tr>
								<td width="4"><img src="../images/design/gonggu_order_t06.gif" width="4" height="4" border="0"></td>
								<td background="../images/design/gonggu_order_tbg03.gif"></td>
								<td background="../images/design/gonggu_end_tbg4.gif" width="4"><img src="../images/design/gonggu_order_t05.gif" width="4" height="4" border="0"></td>
								<td background="../images/design/gonggu_order_tbg05.gif" width="280"></td>
								<td width="4" align="right"><IMG SRC="../images/design/gonggu_order_t04.gif" WIDTH=4 HEIGHT=4 ALT=""></td>
							</tr>
							-->
						</table>
					</td>
				</tr>
				<tr><td height="20"></td></tr>
				<tr>
					<td>
						<table cellpadding="0" cellspacing="0" width="100%" bgcolor="#F4F4F4">
							<tr>
								<td style="padding:20px;" align="center">
									<table cellpadding="0" cellspacing="0" width="100%" bgcolor="#F4F4F4">
										<tr>
											<td width="80" align="center" valign="top"><IMG SRC="../images/design/gonggu_order_btn04.gif" WIDTH=80 HEIGHT=80 ALT="" id="prdtSchBtn" style="cursor:pointer"></td>
											<td align="center" valign="top" width="100%"><textarea name="gonggu_cmt" id="gonggu_cmt" onChange="CheckStrLen('100',this);" onKeyUp="CheckStrLen('100',this);" rows="5" cols="50" style="width:97%" class="textarea_gonggu"></textarea></td>
											<td width="80" align="center" valign="top"><a href="#gonggu_cmt"><IMG SRC="../images/design/gonggu_order_btn05.gif" WIDTH=80 HEIGHT=80 ALT="" hspace=5 onclick="snsGongguReg();return false;"></a></td>
										</tr>
										<tr>
											<td align="center" colspan="3" height="15"></td>
										</tr>
										<tr>
											<td align="center" colspan="3">
												<table cellpadding="0" cellspacing="0" width="100%" bgcolor="#F4F4F4">
<?
if($_data->sns_ok == "Y"){
	if(TWITTER_ID !="TWITTER_ID")
		echo "<input type=\"hidden\" name=\"tLoginBtnChk\" id=\"tLoginBtnChk\">";
	if(FACEBOOK_ID !="FACEBOOK_ID")
		echo "<input type=\"hidden\" name=\"fLoginBtnChk\" id=\"fLoginBtnChk\">";
?>
													<tr>
														<td align="left">
														<? if(TWITTER_ID !="TWITTER_ID"){?>
														<a href="javascript:changeSnsInfo('t');"><IMG SRC="../images/design/icon_twitter_off.gif" WIDTH="25" HEIGHT="25" ALT="" border="0" id="tLoginBtn4"></a>
														<?}?>
														<? if(FACEBOOK_ID!="FACEBOOK_ID"){?>
														<a href="javascript:changeSnsInfo('f');"><IMG SRC="../images/design/icon_facebook_off.gif" WIDTH="25" HEIGHT="25" ALT="" hspace="4" border="0" id="fLoginBtn4"></a>
														<?}?>
														<? if(ME2DAY_ID!="ME2DAY_ID"){?>
														<a href="javascript:changeSnsInfo('m');"><IMG SRC="../images/design/icon_me2day_off.gif" WIDTH="25" HEIGHT="25" ALT="" border="0"id="mLoginBtn4"></a>
														<?}?>
														<A HREF="#commen" onclick="CopyUrl2();return false;"><IMG SRC="../images/design/gonggu_order_btn06.gif" WIDTH=28 HEIGHT=24 ALT="" hspace="4"></a>
														<IMG SRC="../images/design/gonggu_order_text01.gif" WIDTH=248 HEIGHT=24 ALT=""></td>
													</tr>
<? } ?>
													<tr>
														<td height="35"></td>
													</tr>
													<tr>
														<td  class="table_td" align="left">- �������Ŵ� 1ȸ�� <b><font color="#FF855F">�ּ� 30�� �̻� �ִ� 100�� ������ ��ǰ���� ���� ����</font></b>�ϸ�, �Ǹ� �����<b><font color="#FF855F"> ���� 30~50%</font></b>�� ���ε� �������� ��ǰ�� �����Ͻ� �� �ֽ��ϴ�.<br>- Ư����ǰ,��ȹ��ǰ�� �������� ������ �Ұ����մϴ�.<br>- ������ ����� ���� ��ǰ���� ����ڰ� ���� ��쿡�� ������ �����մϴ�.<br>- ��û �� ����� ��ǰ�� �������Ű� ����Ǹ� ����Ͻ� �̸��ϰ� SMS�� �˶��� �߼۵˴ϴ�.</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td height="20"></td></tr>
				<tr>
					<td height="20">*�� <span id="gongCmtTot">0</span>�� ��û</td>
				</tr>
				<tr>
					<td><img src="../images/design/con_line01.gif" width="100%" height="2" border="0"></td>
				</tr>
				<tr><td height="20"></td></tr>
				<tr>
					<td id="snsGongguList"></td>
				</tr>
				<tr><td height="100"></td></tr>
			</table>
		</div>
		<div style="height:6px;background:url('/data/design/img/main/bot_boxline.gif') no-repeat;font-size:0px;"></div>

		<script type="text/javascript" src="<?=$Dir?>lib/sns.js"></script>
		<script type="text/javascript">
			<!--
			var pcode = "";
			var productName = "";
			var memId = "<?=$_ShopInfo->getMemid() ?>";
			var fbPicture ="";
			var preShowID ="";
			var snsCmt = "";
			var snsLink = "";
			var snsType = "";
			var gRegFrm = "list";

			$j(document).ready( function () {
				if(memId != ""){
					snsImg();
					snsInfo();
				}
				showSnsComment();
				showGongguCmt();
			});
			//-->
		</script>

		<? include ($Dir.FrontDir."snsGongguToCmt.php") ?>
		<div id="gongPrdtSearch" style="postion:absolute;display:none;background:#fff;"></div>
		<? include ($Dir."lib/bottom.php") ?>

	</BODY>
</HTML>