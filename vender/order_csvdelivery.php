<?
session_start();
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");
INCLUDE ("access.php");

$imagepath=$Dir.DataDir;

$mode=$_POST["mode"];
$deli_company=$_POST["deli_company"];
$upfile=$_FILES["upfile"];

if($mode=="upload" && strlen($upfile[name])>0 && $upfile[size]>0) {
	$ext = strtolower(substr($upfile[name],strlen($upfile[name])-3,3));
	if($ext=="csv") {
		$filename="excelupfile.txt";
		copy($upfile[tmp_name],$imagepath.$filename);
		chmod($imagepath.$filename,0664);
		$onload="<script>alert(\"�������� ����� �Ϸ�Ǿ����ϴ�.\\n\\n������� ���ó���� �Ͻñ� �ٶ��ϴ�.\");</script>";
	} else {
		$onload="<script>alert(\"���������� �߸��Ǿ� ���ε尡 �����Ͽ����ϴ�.\\n\\n��� ������ ������ ����(CSV) ���ϸ� ��� �����մϴ�.\");</script>";
	}
}

?>

<? INCLUDE "header.php"; ?>
<style>
	form{border:0px; padding:0px; margin:0px;}
</style>
<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function CheckForm(form) {
	if(form.deli_company.value.length==0) {
		alert("�ù��ü�� �����ϼ���.");
		form.deli_company.focus();
		return;
	}
	if(form.upfile.value.length==0) {
		alert("����� ����(CSV) ������ �����ϼ���.");
		form.upfile.focus();
		return;
	}
	form.mode.value="upload";
	form.submit();
}

function OrderDetailView(ordercode) {
	document.detailform.ordercode.value = ordercode;
	window.open("","orderdetail","scrollbars=yes,width=700,height=600");
	document.detailform.submit();
}

</script>
<table border=0 cellpadding=0 cellspacing=0 width=100% height="100%" style="table-layout:fixed">
<col width=190></col>
<col width=20></col>
<col width=></col>
<col width=20></col>
<tr>
	<td width=190 valign=top nowrap background="images/minishop_leftbg.gif"><? include ("menu.php"); ?></td>
	<td width=20 nowrap></td>
	<td valign=top style="padding-top:20px">

	<table width="100%"  border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>
		<table width="100%"  border="0" cellpadding="0" cellspacing="0" >
		<tr>
			<td>
				<table border=0 cellpadding=0 cellspacing=0 width=100% >
				<tr>
					<td><img src="images/order_csvdelivery_title.gif" alt=""></td>
				</tr>
				<tr>
					<td height=5 background="images/minishop_titlebg.gif">
				</tr>
				</table>
			</td>
		</tr>
		<tr><td height=10></td></tr>
		<tr>
			<td>
				<table border=0 cellpadding=0 cellspacing=0 width=100% >
				<tr>
					<td colspan=3 >
					<!--  -->
						<table cellpadding="10" cellspacing="1" width="100%" bgcolor="#EFEFF2">
							<tr>
								<td  bgcolor="#F5F5F9" style="padding:20px">
									<table border=0 cellpadding=0 cellspacing=0 width=100%>
										<tr>
											<td class="notice_gray" height="20"><img src="images/icon_dot02.gif" border=0 hspace="4">�ټ� �ֹ����� ��������� �������Ϸ� ����� �ֹ�����Ʈ�� �ϰ� �ݿ��ϴ� ����Դϴ�.</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td height="40">
					</td>
				</tr>
				<tr>
					<td>
						<img src="images/order_csvdelivery_stitle1.gif" border="0"/>
						<table border=0 cellpadding=0 cellspacing=0 width=100%>
							<tr>
								<td valign=top bgcolor=D4D4D4 style=padding:1>
									<table border=0 cellpadding=0 cellspacing=0 width=100%>
										<tr>
											<td valign=top bgcolor=F0F0F0 style=padding:10>
												<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
													<tr>
														<td>
															<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post enctype="multipart/form-data">
																<input type=hidden name=mode>
																<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
																	<TR>
																		<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">�ù��ü ����</TD>
																		<TD class="td_con1" >
																		<select name="deli_company" class="select" style="width:130px">
																			<option value="">�ù��ü ����</option>
																			<?
																						$sql = "SELECT code, company_name FROM tbldelicompany ";
																						$result=mysql_query($sql,get_db_conn());
																						while($row=mysql_fetch_object($result)) {
																							echo "<option value=\"".$row->code."\">".$row->company_name."</option>\n";
																						}
																						mysql_free_result($result);
																			?>
																			</select>
																		</td>
																	</TR>
																	<TR>
																		<TD colspan="2"  background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></TD>
																	</TR>
																	<TR>
																		<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">�������� ���</TD>
																		<TD class="td_con1" ><input type=file name=upfile style="width:60%" class="input"> <span class="font_orange">������(CSV) ���ϸ� ��� �����մϴ�.</span></TD>
																	</TR>
																</TABLE>
															</form>
														</td>
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
				</td>
			</tr>
			<tr>
				<td height="10">
				</td>
			</tr>
			<tr>
				<td align="center"><p><a href="javascript:CheckForm(document.form1);"><img src="images/btn_fileup.gif" width="113" height="38" border="0"></a></p></td>
			</tr>

			<!-- ó���� ���� ��ġ ���� -->
			<tr><td height=40></td></tr>
			<?if($mode=="upload"){?>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/order_csvdelivery_stitle.gif" border="0"></TD>
					<TD width="100%"></TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height=3></td>
			</tr>
			<tr>
				<td>
				<table border=0 cellpadding=0 cellspacing=0 width=100%>
				<col width=50></col>
				<col width=140></col>
				<col width=140></col>
				<col width=140></col>
				<col width=></col>
				<TR>
					<TD height="1" colspan="5" bgcolor="#B9B9B9"></TD>
				</TR>
				<TR align="center">
					<TD class="table_cell">��ȣ</TD>
					<TD class="table_cell1">�ֹ�����</TD>
					<TD class="table_cell1">�ֹ���</TD>
					<TD class="table_cell1">�����ȣ</TD>
					<TD class="table_cell1">����</TD>
				</TR>
				<TR>
					<TD height="1" colspan="5" bgcolor="#EDEDED"></TD>
				</TR>
<?
			$filepath=$imagepath.$filename;
			$fp=@fopen($filepath, "r");
			$i=1;
			while(!feof($fp)) {
				$buffer=fgets($fp,4096);
				if(strlen($buffer)>3) {
					$field=explode(",",$buffer);
					$date=substr($field[0],0,4)."/".substr($field[0],4,2)."/".substr($field[0],6,2)." (".substr($field[0],8,2).":".substr($field[0],10,2).")";
					echo "<tr align=center>\n";
					echo "	<td class=td_con2>".$i."</td>\n";
					echo "	<td class=td_con1>".$date."</td>\n";
					echo "	<td class=td_con1>".$field[1]."</td>\n";
					echo "	<td class=td_con1>".$field[2]."</td>\n";
					echo "	<td class=td_con1><iframe src=\"order_csvdelivery.process.php?type=init&ordercode=".trim($field[0])."&deli_com=".$deli_company."&deli_name=".urlencode($deli_name)."&deli_num=".$field[2]."\" style='width=100%;height=28px;font-size=15px;border:0 solid #FFFFFF;' scrolling='no' frameborder='NO'></iframe></td>\n";
					echo "</tr>\n";
					echo "<tr><TD height=\"1\" colspan=\"5\" bgcolor=\"#EDEDED\"></TD></tr>\n";
					$i++;
				}
			}
?>
				<TR>
					<TD height="1" colspan="5" bgcolor="#B9B9B9"></TD>
				</TR>
				</table>
				</td>
			</tr>
		<?}?>
			<!-- ó���� ���� ��ġ �� -->
			
			<tr>
				<td height=20></td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="../admin/images/manual_top1.gif" WIDTH=15 height="45" ALT=""></TD>
					<TD background="../admin/images/manual_bg.gif"><IMG SRC="../admin/images/manual_title.gif" WIDTH=113 height="45" ALT=""></TD>
					<TD width="100%" background="../admin/images/manual_bg.gif" height="35"></TD>
					<TD background="../admin/images/manual_bg.gif"></TD>
					<td background="../admin/images/manual_bg.gif"><IMG SRC="../admin/images/manual_top2.gif" WIDTH=18 height="45" ALT=""></td>
				</TR>
				<TR>
					<TD background="../admin/images/manual_left1.gif"><IMG SRC="../admin/images/manual_left1.gif" WIDTH=15 HEIGHT="5" ALT=""></TD>
					<TD COLSPAN=3 width="100%" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;" class="menual_bg">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="20" align="right" valign="top"><img src="../admin/images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><span class="font_dotline">�ֹ�����Ʈ �ϰ���� ����</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top" style="letter-spacing:-0.5pt;"><p>- �ֹ� ó���� �� �ֹ� ���� ���ó���� ���� �ʰ�, �ټ��� �ֹ��� ����� �� ��������� ��������(CSV)�� �ۼ��Ͽ� �ϰ� �����ϴ� ����Դϴ�.</p></td>
					</tr>
					<tr>
						<td colspan="2" height="20"></td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><img src="../admin/images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><span class="font_dotline">�ֹ�����Ʈ �ϰ���� ���</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top" style="letter-spacing:-0.5pt;"><p>- �� �Ʒ��� ������ ����� �ϰ���� ������ ��������(Ȯ���� CSV)�� �ۼ��մϴ�.<br>
						<b>&nbsp;&nbsp;</b><span class="font_orange">------------ �ϰ���� ����(CSV) ���� ------------</span><br>
						<b>&nbsp;&nbsp;</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;<span class="font_blue">�ֹ���ȣ,�ֹ���,�����ȣ</span><br>
						<b>&nbsp;&nbsp;</b><span class="font_orange">--------------------------------------------------</span><br>
						<b>&nbsp;&nbsp;</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;20070307154752877166,ȫ�浿,11223344<br>
						<b>&nbsp;&nbsp;</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;20070307160323501849,ȫ�浿,55667788<br>
						<b>&nbsp;&nbsp;</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;20070307160929925273,ȫ�浿,99001122<br>
						<b>&nbsp;&nbsp;</b><span class="font_orange">--------------------------------------------------</span>
						</p></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top" style="letter-spacing:-0.5pt;"><p>- �� ���θ����� �̿��ϴ� �ù��ü ������, �ۼ��� ������� ��������(csv)�� ���ε��մϴ�.</p></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top" style="letter-spacing:-0.5pt;"><p>- �� ������ ����ϸ� ��ϵ� ������ [������� ����Ʈ]�� ��µ˴ϴ�.</p></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top" style="letter-spacing:-0.5pt;"><p>- �� [���ó���ϱ�] ��ư�� �̿��Ͽ� �����մϴ�.</p></td>
					</tr>
					</table>
					</TD>
					<TD background="../admin/images/manual_right1.gif"><IMG SRC="../admin/images/manual_right1.gif" WIDTH=18 HEIGHT="2" ALT=""></TD>
				</TR>
				<TR>
					<TD><IMG SRC="../admin/images/manual_left2.gif" WIDTH=15 HEIGHT=8 ALT=""></TD>
					<TD COLSPAN=3 background="../admin/images/manual_down.gif"><IMG SRC="../admin/images/manual_down.gif" WIDTH="4" HEIGHT=8 ALT=""></TD>
					<TD><IMG SRC="../admin/images/manual_right2.gif" WIDTH=18 HEIGHT=8 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height="50"></td>
			</tr>
			</table>
			

			<!-- <a href="javascript:OrderDeliCodeUpdate();"><img src="images/btn_orderDeliCodeUpload.gif" border="0" alt="�߼۴���ֹ� �ϰ���� ����(�˾�)"></a> -->
			</td>
		</tr>
		</table>
		</td>
	</tr>
	</table>

	</td>
</tr>

<form name=pageForm method=post action="<?=$_SERVER[PHP_SELF]?>">
<input type=hidden name=search_start value="<?=$search_start?>">
<input type=hidden name=search_end value="<?=$search_end?>">
<input type=hidden name=s_check value="<?=$s_check?>">
<input type=hidden name=search value="<?=$search?>">
<input type=hidden name=paystate value="<?=$paystate?>">
<input type=hidden name=deli_gbn value="<?=$deli_gbn?>">
<input type=hidden name=orderby value="<?=$orderby?>">
<input type=hidden name=block>
<input type=hidden name=gotopage>
</form>

<form name=checkexcelform action="order_excel.php" method=post>
<input type=hidden name=ordercodes>
</form>

</table>
<!-- �����ȣ �ϰ� ó���� ���� �� �߰� ���� -->
<form id="orderDeliForm" name=OrderDeliCodeUpdatePopForm>
	<input type=hidden name=ordercode>
</form>
<!-- �����ȣ �ϰ� ó���� ���� �� �߰� �� -->
<iframe name="processFrame" src="about:blank" width="0" height="0" scrolling=no frameborder=no></iframe>

<?=$onload?>

<? INCLUDE "copyright.php"; ?>