<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "or-2";
$MenuCode = "order";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$regdate = $_shopdata->regdate;

$type=$_POST["type"];
$date_year=$_POST["date_year"];
$date_month=$_POST["date_month"];
$loc=$_POST["loc"];
$sex=$_POST["sex"];
$member=$_POST["member"];
$paymethod=$_POST["paymethod"];

if(strlen($date_year)==0) $date_year=date("Y");
if(strlen($date_month)==0) $date_month=date("m");
?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">

	function CheckForm() {
		/*
		 if(!IsNumeric(document.form1.age1.value) || !IsNumeric(document.form1.age2.value)) {
		 alert("���� �Է��� ���ڸ� �Է��ϼž� �մϴ�.");
		 return;
		 }
		 age1=0;
		 age2=0;
		 if(document.form1.age1.value.length>0 && document.form1.age2.value.length>0) {
		 age1=document.form1.age1.value;
		 age2=document.form1.age2.value;
		 if(age1==0 || age2==0 || age1>age2) {
		 age1=0;
		 age2=0;
		 }
		 }
		 if((age1>0 || document.form1.sex.value!="ALL") && document.form1.member.value!="Y") {
		 document.form1.member.options[1].selected=true;
		 }
		 document.form1.age1.value=age1;
		 document.form1.age2.value=age2;
		 */
		document.form1.submit();
	}


	function allsaleProduct(month,day) {

		if( month ) document.form1.month.value=month;
		if( day ) document.form1.day.value=day;

		window.open('','productView','width=800,height=800,scrollbars=yes');
		document.form1.method='GET';
		document.form1.target='productView';
		document.form1.action='order_allsale.product.php';
		document.form1.submit();

		document.form1.method='POST';
		document.form1.month.value='all';
		document.form1.day.value='all';
		document.form1.target='';
		document.form1.action='';
	}
</script>
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
			<? include ("menu_order.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" width="16" height="12" border="0" valign=absmiddle>������ġ : �ֹ�/���� &gt; ��ٱ��� �� ���� �м� &gt; <span class="2depth_select">��ü��ǰ ����м�</span></td>
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
					<TD><IMG SRC="images/order_allsale_title.gif" ALT=""></TD>
					</tr><tr>
					<TD width="100%" background="images/title_bg.gif" height=21></TD>
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
					<TD background="images/distribute_04.gif"><IMG SRC="images/distribute_04.gif" ></TD>
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
					<TD width="100%" class="notice_blue"><p>��ü ��ǰ�� ���������� Ȯ���Ͻ� �� �ֽ��ϴ�.</p></TD>
					<TD background="images/distribute_07.gif"><IMG SRC="images/distribute_07.gif" ></TD>
				</TR>
					<TR>
						<TD background="images/distribute_04.gif"><IMG SRC="images/distribute_04.gif" ></TD>
						<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
						<TD width="100%" class="notice_blue"><p>��ۿϷ��ǰ���� ��ǰ�����ֹ�����̹Ƿ� �ֹ������Ǽ��� �ٸ��ϴ�.</p></TD>
						<TD background="images/distribute_07.gif"><IMG SRC="images/distribute_07.gif" ></TD>
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
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/order_basket_stitle1.gif" WIDTH="134" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height="3"></td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/distribute_01.gif" WIDTH=7 HEIGHT=7 ALT=""></TD>
					<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
					<TD><IMG SRC="images/distribute_03.gif" WIDTH=8 HEIGHT=7 ALT=""></TD>
				</TR>
				<TR>
					<TD><IMG SRC="images/distribute_08.gif" WIDTH=7 HEIGHT=8 ALT=""></TD>
					<TD COLSPAN=2 background="images/distribute_09.gif"></TD>
					<TD><IMG SRC="images/distribute_10.gif" WIDTH=8 HEIGHT=8 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height="3"></td>
			</tr>
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=type value="search">
			<input type=hidden name=month value="all">
			<input type=hidden name=day value="all">
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
							<TD class="table_cell" width="138"><img src="images/icon_point2.gif" width="8" height="11" border="0">�Ⱓ ����</TD>
							<TD class="td_con1" width="191" colspan="3"><select name=date_year class="select" style="width:70px;">
<?
			for($i=substr($regdate,0,4);$i<=date("Y");$i++) {
				echo "<option value=\"".$i."\" ";
				if($i==$date_year) echo "selected";
				echo ">".$i."</option>\n";
			}
?>
							</select>�� <select name=date_month class="select" style="width:70px;">
								<option value="ALL" <?if($date_month=="ALL")echo"selected";?>>��ü</option>
								<?
									for($i=1;$i<=12;$i++) {
										//$ii=substr("0".$i,-2);
										echo "<option value=\"".sprintf('%02d',$i)."\" ";
										if($i==$date_month) echo "selected";
										echo ">".sprintf('%02d',$i)."</option>\n";
									}
								?>
							</select>��</TD>
						</TR>
						<TR>
							<TD colspan="4" width="760" background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></TD>
						</TR>
						<TR>
							<TD class="table_cell" width="138"><img src="images/icon_point2.gif" width="8" height="11" border="0">������</TD>
							<TD class="td_con1" width="191"><select name=loc class="select" style="width:70px;">
								<option value="ALL" <?if($loc=="ALL")echo"selected";?>>��ü</option>
<?
			$loclist=array("����","�λ�","�뱸","��õ","����","����","���","����","���","�泲","���","�泲","���","����","����","����","��Ÿ");
			for($i=0;$i<count($loclist);$i++) {
				echo "<option value=\"".$loclist[$i]."\" ";
				if($loc==$loclist[$i]) echo "selected";
				echo ">".$loclist[$i]."</option>\n";
			}
?>
							</select></TD>
							<TD class="table_cell1" width="126"><img src="images/icon_point2.gif" width="8" height="11" border="0">����</TD>
							<TD class="td_con1" width="256"><select name=sex class="select" style="width:70px;">
								<option value="ALL" <?if($sex=="ALL")echo"selected";?>>��ü</option>
								<option value="M" <?if($sex=="M")echo"selected";?>>����</option>
								<option value="F" <?if($sex=="F")echo"selected";?>>����</option>
							</select></TD>
						</TR>
						<TR>
							<TD colspan="4" width="760" background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></TD>
						</TR>
						<TR>
							<TD class="table_cell" width="138"><img src="images/icon_point2.gif" width="8" height="11" border="0">ȸ������</TD>
							<TD class="td_con1" width="191"><select name=member class="select" style="width:70px;">
								<option value="ALL" <?if($member=="ALL")echo"selected";?>>��ü</option>
								<option value="Y" <?if($member=="Y")echo"selected";?>>ȸ��</option>
								<option value="N" <?if($member=="N")echo"selected";?>>��ȸ��</option>
								</select></TD>
							<TD class="table_cell1" width="126"><img src="images/icon_point2.gif" width="8" height="11" border="0">�������</TD>
							<TD class="td_con1" width="256"><select name=paymethod style="width:95%" class="select">
								<option value="ALL" <?if($paymethod=="ALL")echo"selected";?>>��ü</option>
								<option value="B" <?if($paymethod=="B")echo"selected";?>>������</option>
								<option value="V" <?if($paymethod=="V")echo"selected";?>>�ǽð�������ü</option>
								<option value="O" <?if($paymethod=="O")echo"selected";?>>�������</option>
								<option value="C" <?if($paymethod=="C")echo"selected";?>>�ſ�ī��</option>
								<!--option value="P" <?if($paymethod=="P")echo"selected";?>>�Ÿź�ȣ �ſ�ī��</option-->
								<option value="M" <?if($paymethod=="M")echo"selected";?>>�޴���</option>
								<option value="Q" <?if($paymethod=="Q")echo"selected";?>>�Ÿź�ȣ �������</option>
								</select></TD>
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
				<td align="center" height=10></td>
			</tr>
			<tr>
				<td align="center"><p><a href="javascript:CheckForm();"><img src="images/botteon_search.gif" width="113" height="38" border="0"></a></p></td>
			</tr>
			<tr>
				<td height="20"></td>
			</tr>
			<tr>
				<td align="center">
					<?
							if($type=="search") {

								if($date_month=="ALL") {
									include "order_allsale.year.php";
								} else {
									include "order_allsale.month.php";
								}
							}
					?>
				</td>
			</tr>
			</form>
			<tr>
				<td height=20></td>
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
					<TD background="images/manual_left1.gif"><IMG SRC="images/manual_left1.gif" WIDTH=15 HEIGHT="5" ALT=""></TD>
					<TD COLSPAN=3 width="100%" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;" class="menual_bg">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><span class="font_dotline">��ü��ǰ ����м�</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top" style="letter-spacing:-0.5pt;"><p>- �ֹ�����Ʈ�� ��ϵǾ� �ִ� �ֹ����� �������� ����Ǹ� ���/�ݼ�/��ó���� ���еǾ� ��µ˴ϴ�.</p></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top" style="letter-spacing:-0.5pt;"><p>- �Ⱓ��/������/����/ȸ����/��������� �˻��� �����մϴ�.</p></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top" style="letter-spacing:-0.5pt;"><p>- ������ �˻��� ��� ��������� ������ �������� �մϴ�.</p></td>
					</tr>
					</table>
					</TD>
					<TD background="images/manual_right1.gif"><IMG SRC="images/manual_right1.gif" WIDTH=18 HEIGHT="2" ALT=""></TD>
				</TR>
				<TR>
					<TD><IMG SRC="images/manual_left2.gif" WIDTH=15 HEIGHT=8 ALT=""></TD>
					<TD COLSPAN=3 background="images/manual_down.gif"><IMG SRC="images/manual_down.gif" WIDTH="4" HEIGHT=8 ALT=""></TD>
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