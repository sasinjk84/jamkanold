<?
	$Dir="../";
	include_once($Dir."lib/init.php");
	include_once($Dir."lib/lib.php");
	include ("access.php");

	####################### ������ ���ٱ��� check ###############
	$PageCode = "mo-1";
	$MenuCode = "mobile";
	if (!$_usersession->isAllowedTask($PageCode)) {
		include ("AccessDeny.inc.php");
		exit;
	}
	#########################################################

	$result = mysql_query("select * from tblmobileconfig");
	$row = mysql_fetch_array($result);
?>

<? include "header.php"; ?>
<style type=text/css>
	#menuBar {}
	#contentdiv {width: 200px;height: 315px;}
</style>

<script type="text/javascript" src="lib.js.php"></script>
<script type="text/javascript" src="codeinit.js.php"></script>
<script language="JavaScript">
	var code="<?=$code?>";
	function CodeProcessFun(_code) {
	
		if(_code=="out" || _code.length==0 || _code=="000000000000") {
		
			document.all["code_top"].style.background="#dddddd";
			selcode="";
			seltype="";

			if(_code!="out") {
				BodyInit('');
			} else {
				_code="";
			}
		} else {
			document.all["code_top"].style.background="#ffffff";
			BodyInit(_code);
		}

		if(selcode.length==12 && selcode!="000000000000") {
			document.form2.mode.value="";
			document.form2.code.value=selcode;
			document.form2.target="ListFrame";
			document.form2.action="mobile_product_selected_list.php";
			document.form2.submit();
			document.form2.target="ifrm_ctrl";

			if(document.ifrm_ctrl.form1){
				document.form2.Scrolltype.value = document.ifrm_ctrl.form1.Scrolltype.value;
			}
			document.form2.submit();
		} else {
			document.form2.mode.value="";
			document.form2.code.value="";
			document.form2.target="ListFrame";
			document.form2.action="mobile_product_selected_list.php";
			document.form2.submit();
			document.form2.target="ifrm_ctrl";

			if(document.ifrm_ctrl.form1){
				document.form2.Scrolltype.value = document.ifrm_ctrl.form1.Scrolltype.value;
			}
			document.form2.submit();
		}
	}

	var allopen=false;

	function AllOpen() {
		display="show";
		open1="open";

		if(allopen) {
			display="none";
			open1="close";
			allopen=false;
		} else {
			allopen=true;
		}
		for(i=0;i<all_list.length;i++) {
			if(display=="none" && all_list[i].codeA==selcode.substring(0,3)) {
				all_list[i].selected=true;
				selcode=all_list[i].codeA+all_list[i].codeB+all_list[i].codeC+all_list[i].codeD;
				seltype=all_list[i].type;
			}
			all_list[i].display=display;
			all_list[i].open=open1;
			for(ii=0;ii<all_list[i].ArrCodeB.length;ii++) {

				if(display=="none") {
					all_list[i].ArrCodeB[ii].selected=false;
				}
				all_list[i].ArrCodeB[ii].display=display;
				all_list[i].ArrCodeB[ii].open=open1;

				for(iii=0;iii<all_list[i].ArrCodeB[ii].ArrCodeC.length;iii++) {
					if(display=="none") {
						all_list[i].ArrCodeB[ii].ArrCodeC[iii].selected=false;
					}

					all_list[i].ArrCodeB[ii].ArrCodeC[iii].display=display;
					all_list[i].ArrCodeB[ii].ArrCodeC[iii].open=open1;

					for(iiii=0;iiii<all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD.length;iiii++) {
						if(display=="none") {
							all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].selected=false;
						}
						all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].display=display;
						all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].open=open1;
					}
				}
			}
		}
		BodyInit('');
	}

	var divLeft=0;
	var defaultLeft=0;
	var timeOffset=0;
	var setTObj;
	var divName="";
	var zValue=0;

	function divMove(){
		divLeft+=timeOffset;

		if(divLeft >= defaultLeft){
			divLeft=defaultLeft;
			divName.style.left=divLeft;
			divName.style.zIndex = zValue;
			clearTimeout(setTObj);
			setTObj="";
		}else{
			timeOffset+=20;
			divName.style.left=divLeft;
			setTObj=setTimeout('divMove();',5);
		}
	}

	function divAction(arg1,arg2){
		if(zValue != arg2 && !setTObj){
			defaultLeft = arg1.offsetLeft;
			divLeft = defaultLeft;
			zValue = arg2;
			divName = arg1;
			timeOffset = -70;
			divMove();
		}
	}


</script>
<form name=form2 action="" method=get>
	<input type=hidden name=mode>
	<input type=hidden name=code>
	<input type=hidden name=Scrolltype>
</form>
<iframe name="ifrm_ctrl" width=0 height=0 frameborder=0 align=top scrolling="no" marginheight="0" marginwidth="0"></iframe>
<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
	<tr>
		<td valign="top">
			<table cellpadding="0" cellspacing="0" width=100% style="table-layout:fixed">
				<tr>
					<td>
						<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed"  background="images/con_bg.gif">
							<col width=198></col>
							<col width=10></col>
							<col width=></col>
							<tr>
								<td valign="top"  background="images/leftmenu_bg.gif">
									<? include ("menu_mobile.php"); ?>
								</td>
								<td></td>
								<td valign="top">
									<table cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td height="29" colspan="3">
												<table cellpadding="0" cellspacing="0" width="100%">
													<tr>
														<td height="28" class="link" align="left" background="images/con_link_bg.gif">
															<img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ����ϼ� &gt;<span class="2depth_select">����� ��ǰ����</span>
														</td>
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
															<table width="100%" border=0 cellpadding=0 cellspacing=0>
																<tr>
																	<td><img src="images/mobile_product_list_title.gif" border="0"></td>
																</tr>
																<tr>
																	<td width="100%" background="images/title_bg.gif" height="21"></td>
																</tr>
															</table>
														</td>
													</tr>
													<tr>
														<td height="3"></td>
													</tr>
													<tr>
														<td style="padding-bottom:3pt;">
															<table width="100%" border=0 cellpadding=0 cellspacing=0>
																<tr>
																	<td><img src="images/distribute_01.gif"></td>
																	<td COLSPAN=2 background="images/distribute_02.gif"></td>
																	<td><img src="images/distribute_03.gif"></td>
																</tr>
																<tr>
																	<td background="images/distribute_04.gif"></td>
																	<td class="notice_blue"><img src="images/distribute_img.gif" ></td>
																	<td width="100%" class="notice_blue">����ϼ��θ��� ��ǰ�� ���� �ϽǼ� �ֽ��ϴ�.</td>
																	<td background="images/distribute_07.gif"></td>
																</tr>
																	<tr>
																	<td><img src="images/distribute_08.gif"></td>
																	<td COLSPAN=2 background="images/distribute_09.gif"></td>
																	<td><img src="images/distribute_10.gif"></td>
																</tr>
															</table>
														</td>
													</tr>
													<tr>
														<td>
															<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=get>
																<table cellpadding="0" cellspacing="0" width="100%" height="551" id="main_process" style="display:none">
																	<tr>
																		<td valign="top">
																			<div onmouseover="document.getElementById('cateidx').style.zIndex=2;" id="cateidx" style="position:absolute;z-index:0;width:242px;bgcolor:#FFFFFF;">
																				<table cellpadding="0" cellspacing="0" width="100%" height="511">
																					<tr>
																						<td width="232" height="100%" valign="top" background="images/category_boxbg.gif">
																							<table cellpadding="0" cellspacing="0" width="242" height="100%">
																								<tr>
																									<td bgcolor="#FFFFFF"><img src="images/product_totoacategory_title.gif" border="0"></td>
																								</tr>
																								<tr>
																									<td><img src="images/category_box1.gif" border="0"></td>
																								</tr>
																								<tr>
																									<td bgcolor="#0F8FCB" style="padding:2;padding-left:4">
																										<button title="��ü Ʈ��Ȯ��" id="btn_treeall" class="btn" onmouseover="if(this.className=='btn'){this.className='btnOver'}" onmouseout="if(this.className=='btnOver'){this.className='btn'}" unselectable="on" onclick="AllOpen();">
																											<img src="images/category_btn1.gif" width=22 height=23 border="0">
																										</button>
																									</td>
																								</tr>
																								<tr>
																									<td bgcolor="#0F8FCB" style="padding-top:4pt; padding-bottom:6pt;"></td>
																								</tr>
																								<tr>
																									<td><img src="images/category_box2.gif" border="0"></td>
																								</tr>
																								<tr>
																									<td width="100%" height="100%" align=center valign=top style="padding-left:5px;padding-right:5px;">
																										<div class=MsgrScroller id=contentdiv style="width:99%;height:100%;OVERFLOW-x: auto; OVERFLOW-y: auto;" oncontextmenu="return false" style="overflow-x:hidden;overflow-y:hidden;" ondragstart="return false" onselectstart="return false" oncontextmenu="return false">
																											<div id=bodyList>
																												<table border=0 cellpadding=0 cellspacing=0 width="100%" height="100%" bgcolor=FFFFFF>
																													<tr>
																														<td height=18>
																															<img src="images/directory_root.gif" border=0 align=absmiddle> <span id="code_top" style="cursor:default;" onmouseover="this.className='link_over'" onmouseout="this.className='link_out'" onclick="ChangeSelect('out');">�ֻ��� ī�װ�</span>
																														</td>
																													</tr>
																													<tr>
																														<!-- ��ǰī�װ� ��� -->
																														<td id="code_list" nowrap valign=top></td>
																														<!-- ��ǰī�װ� ��� �� -->
																													</tr>
																												</table>
																											</div>
																										</div>
																									</td>
																								</tr>
																							</table>
																						</td>
																					</tr>
																					<tr>
																						<td><img src="images/category_boxdown.gif" border="0"></td>
																					</tr>
																				</table>
																			</div>
																		</td>
																		<td style="padding-left:84px;"></td>
																		<td width="100%" valign="top" height="100%" onmouseover="document.getElementById('cateidx').style.zIndex=0;">
																			<!--<A name="linkanchor">-->
																				<div style="position:relative;z-index:1;width:100%;height:100%;bgcolor:#FFFFFF;">
																					<iframe name="ListFrame" src="mobile_product_selected_list.php" width=100% height=551 frameborder=0 align=TOP scrolling="no" marginheight="0" marginwidth="0"></iframe>
																				</div>
																		</td>
																	</tr>
																</table>
																<input type=hidden name=mode value="<?=$mode?>">
																<input type=hidden name=code>
															</form>
														</td>
													</tr>
													<tr>
														<td align="center" width="100%" valign="top" height="100%"></td>
													</tr>
													<tr>
														<td height="20"></td>
													</tr>
													<tr>
														<td height="20"></td>
													</tr>
													<tr>
														<td>
															<table width="100%" border=0 cellpadding=0 cellspacing=0>
																<tr>
																	<td><img src="images/manual_top1.gif" width=15 height=45 alt=""></td>
																	<td><img src="images/manual_title.gif" width=113 height=45 alt=""></td>
																	<td width="100%" background="images/manual_bg.gif"></td>
																	<td background="images/manual_bg.gif"></td>
																	<td><img src="images/manual_top2.gif" width=18 height=45 alt=""></td>
																</tr>
																<tr>
																	<td background="images/manual_left1.gif"></td>
																	<td COLSPAN=3 width="100%" valign="top" class="menual_bg" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;">
																		<table cellpadding="0" cellspacing="0" width="100%">
																			<col width=20></col>
																			<col width=></col>
																			<tr>
																				<td align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
																				<td><span class="font_dotline">����ϼ� ��ǰ ���� ����</span></td>
																			</tr>
																			<tr>
																				<td align="right">&nbsp;</td>
																				<td class="space_top" style="letter-spacing:-0.5pt;">
																					- "�Ҽ� �� ��������" ��ǰ ������ ����ϼ����� �������� �ʽ��ϴ�. <br/>
																					- �⺻������ ����ϼ��� ��� ��ϵ� ��ǰ�� �ܼ� ���� ������ �����մϴ�(���� ��ϺҰ�)<br/>
																					- ����ϼ��� ��� ��Ͻ� ������ �������� ������� �ʽ��ϴ� (�� : <img src="../images/common/icon01.gif"/>,<img src="../images/common/icon17.gif"/>  �� )<br/>
																					- ����ϼ��� ��� PC�������� �����Ǵ� �Ż�ǰ,�α��ǰ,��õ��ǰ,Ư����ǰ ����� �������� �ʽ��ϴ�.<br/>
																				</td>
																				
																			</tr>
																			<tr>
																				<td width="20" align="right">&nbsp;</td>
																				<td  class="space_top">&nbsp; </td>
																			</tr>
																			<tr>
																				<td align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
																				<td><span class="font_dotline">����ϼ� ������ ��ǰ ����</span></td>
																			</tr>
																			<tr>
																				<td align="right">&nbsp;</td>
																				<td class="space_top" style="letter-spacing:-0.5pt;">
																					- "��üī�װ� ��Ͽ��� ���ϴ� ī�װ��� �����Ͻø� �ش� ī�װ��� ������ ��ǰ�� Ȯ�� �Ͻ� �� �ֽ��ϴ�.<br/>
																				</td>

																			</tr>
																			<tr>
																				<td width="20" align="right">&nbsp;</td>
																				<td  class="space_top">&nbsp; </td>
																			</tr>
																			<tr>
																				<td align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
																				<td><span class="font_dotline">����ϼ� ������ ��ǰ ����</span></td>
																			</tr>
																			<tr>
																				<td align="right">&nbsp;</td>
																				<td class="space_top" style="letter-spacing:-0.5pt;">
																					- ī�װ� ���� ���� �޴����� ����ϼ� ������ ���� ������ ��� ��ǰ ���� ������ �Ұ����մϴ�.<br/>
																					- "��üī�װ� ���" ���� ������ ���ϴ� ī�װ��� ���� ���� �Ͻ� �� ���� "��ϵ� ��ǰ���" �޴����� "�߰�����ϱ�" ��ư�� Ŭ���Ͻø� ��ǰ�� ���� �Ҽ� �ִ� �˾�(���� ����� ���θ� ��ǰ���� â)�� ��Ÿ���ϴ�.<br/>
																					- "����� ���θ� ��ǰ���� â"���� �˻��� ��ǰ�� ������ ���ϴ� ��ǰ�� Ŭ���Ͻø� ���� �˴ϴ�.<br/>
																					- "����� ���θ� ��ǰ���� â"���� "[����ϼ��� �����]"�� ��ǰ�� ��� �̹� ���⼳���� ��ǰ�̹Ƿ� Ŭ�� �Ͻô��� ���ϻ�ǰ �ϳ��� �����˴ϴ�</br>
																					- "����� ���θ� ��ǰ���� â"���� �ٸ� ī�װ� ��ǰ�� �˻��Ͽ� �����Ͻ� ��� �˻��� �ش� ī�װ��� ��ǰ�� �����˴ϴ�.(�⺻������ �ش� ī�װ��� ��ϵ� ��ǰ�� ���� �����մϴ�)</br>
																				</td>
																			</tr>
																			<tr>
																				<td width="20" align="right">&nbsp;</td>
																				<td  class="space_top">&nbsp; </td>
																			</tr>
																			<tr>
																				<td align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
																				<td><span class="font_dotline">����ϼ� ������ ��ǰ ����</span></td>
																			</tr>
																			<tr>
																				<td align="right">&nbsp;</td>
																				<td class="space_top" style="letter-spacing:-0.5pt;">
																					- "��üī�װ� ���"���� ������ ���ϴ� ī�װ� ������ ���� "��ϵ� ��ǰ ���" �޴����� �ش� ��ǰ�� "����" ��ư�� Ŭ���Ͻø� �Ǹ� �ܼ��� ����� �� �������¸� ���ŵǹǷ� ��ϻ�ǰ�� ���������� �ʽ��ϴ�.
																				</td>
																				<tr>
																					<td width="20" align="right">&nbsp;</td>
																					<td  class="space_top">&nbsp; </td>
																				</tr>
																			</tr>
																			<tr>
																				<td align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
																				<td><span class="font_dotline">����ϼ� ������ ��ǰ���� ����</span></td>
																			</tr>
																			<tr>
																				<td align="right">&nbsp;</td>
																				<td class="space_top" style="letter-spacing:-0.5pt;">
																					- "��üī�װ� ���"���� ������ ���ϴ� ī�װ� ������ ���� "��ϵ� ��ǰ ���" �޴����� �ش� ��ǰ�� "��â" ��ư�� Ŭ���Ͻø� ��Ÿ���� �˾�(���� ��ǰ ���/����/���� â)���� ��ǰ ������ �����Ͻ� �� �ֽ��ϴ�.<br/>
																					- "��ǰ ���/����/���� â" ���� ��ǰ������ �����Ͻ� ��� ��ü���θ����� �ش� ��ǰ�� �����ǹǷ� ���� �Ͻñ� �ٶ��ϴ�.<br/>
																					- "��ǰ ���/����/���� â" ���� ��ǰ�� �����Ͻ� ��� ��ü���θ����� �ش� ��ǰ�� ���ŵǹǷ� ��������� ���� �Ͻñ� �ٶ��ϴ�.<br/>
																				</td>
																			</tr>
																		</table>
																	</td>
																	<td background="images/manual_right1.gif"></td>
																</tr>
																<tr>
																	<td><img src="images/manual_left2.gif" width=15 height=8 alt=""></td>
																	<td COLSPAN=3 background="images/manual_down.gif"></td>
																	<td><img src="images/manual_right2.gif" width=18 height=8 alt=""></td>
																</tr>
															</table>
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
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<iframe name="HiddenFrame" src="<?=$Dir?>blank.php" width=0 height=0 frameborder=0 align=TOP scrolling="no" marginheight="0" marginwidth="0"></iframe>
<?
if($row[use_same_product_image]=="Y"){
?>
	<script>
		document.getElementById("main_process").style.display = 'none';</script>
<?
}else{
?>
	<script>
		document.getElementById("main_process").style.display = 'block';</script>
<?
}

if($row[use_same_product_code]=="Y"){
	$sql = "SELECT * FROM tblproductcode WHERE substr(type,1,1) != 'X' AND substr(type,1,1) != 'S' ORDER BY sequence DESC";
}else{
	$sql = "SELECT * FROM tblproductcode where substr(type,1,1) != 'X' AND substr(type,1,1) != 'S' AND mobile_display = 'Y' ORDER BY sequence DESC ";
}
include ("codeinit.php");
?>
<? include "copyright.php"; ?>