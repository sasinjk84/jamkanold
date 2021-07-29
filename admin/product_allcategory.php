<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");


include_once($Dir."lib/admin_more.php");
include_once($Dir."lib/ext/product_func.php");
include_once($Dir."lib/class/thumbnail.php");

####################### 페이지 접근권한 check ###############
$PageCode = "pr-1";
$MenuCode = "product";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################
include "header.php"; ?>
<STYLE type=text/css>
#menuBar {
}
#contentDiv {
	WIDTH: 200;
	HEIGHT: 320;
}
</STYLE>
<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td valign="top">	
			<table cellpadding="0" cellspacing="0" width=100%>		
				<tr>			
					<td>		
						<table cellpadding="0" cellspacing="0" width="100%"  background="images/con_bg.gif">
							<tr>
								<td valign="top"  background="images/leftmenu_bg.gif" style="width:198px;">
									<? include ("menu_product.php"); ?>
								</td>
								<td style="width:10px;"></td>				
								<td valign="top">
									<table cellpadding="0" cellspacing="0" width="100%">					
										<tr>
											<td height="29" colspan="3">
												<table cellpadding="0" cellspacing="0" width="100%">
													<tr>
														<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">현재위치 : 상품관리 &gt;카테고리/상품관리 &gt; <span class="2depth_select">상품 등록/수정/삭제</span></td>
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
																	<TD><IMG SRC="images/product_allcategory.gif" ALT="카테고리 간편관리"></TD>
																</tr>
																<tr>
																	<TD width="100%" background="images/title_bg.gif" height=21></TD>
																</TR>
															</TABLE>
														</td>
													</tr>
													<tr>
														<td height="3"></td>
													</tr>
													<tr>
														<td>
														<? include_once './product_ext/allcategory.php'; ?>
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
<? include "copyright.php"; ?>
