<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "sh-2";
$MenuCode = "shop";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$type=$_POST["type"];
$proption_price=$_POST["proption_price"];
$primg_minisize=$_POST["primg_minisize"];
$soldview=$_POST["soldview"];
$codeyes=$_POST["codeyes"];
$quicktools=$_POST["quicktools"];
$bottomtools=$_POST["bottomtools"];

$selfcodev=$_POST["selfcodev"];
$selfcodel=$_POST["selfcodel"];
$selfcoden=$_POST["selfcoden"];

$selfcodefront=$_POST["selfcodefront"];
$selfcodeback=$_POST["selfcodeback"];

$pester_state=$_POST["pester_state"];

$primg_minisize2=250;

$imagepath = $Dir.DataDir."shopimages/etc/";

if ($type=="del" && file_exists($imagepath."soldout.gif")) {
	$img_url=$imagepath."soldout.gif";
	unlink($img_url);
	$onload = "<script> alert('��ǰ ǰ�� �̹��� ������ �Ϸ�Ǿ����ϴ�.'); </script>";
}

if ($type=="icondel" && file_exists($imagepath."priceicon.gif")) {
	$img_url = $imagepath."priceicon.gif";
	unlink($img_url);
	$sqld="SELECT etctype FROM tblshopinfo ";
	$resultd=mysql_query($sqld,get_db_conn());
	$rowd=mysql_fetch_object($resultd);
	$rowd->etctype=str_replace("MEMIMG=Y","",$rowd->etctype);
	mysql_query("UPDATE tblshopinfo SET etctype = '".$rowd->etctype."' ",get_db_conn());
	DeleteCache("tblshopinfo.cache");
	$onload = "<script> alert('��ǰ���� ������ ������ �Ϸ�Ǿ����ϴ�.'); </script>";
}

if ($type=="up") {
	if (strlen($image_name)>0 && strtolower(substr($image_name,strlen($image_name)-3,3))=="gif" && $image_size<=153600) {
		$image_name="soldout.gif";
		move_uploaded_file($image,"$imagepath$image_name");
		chmod("$imagepath$image_name",0664);
	} else {
		if (strlen($image_name)>0) $msg="�ø��� �̹����� 150KB ������ gif���ϸ� �˴ϴ�.";
	}

	$etctype=$etcvalue;
	if (strlen($codeyes)>0){
		$etctype.= "CODEYES=".$codeyes."";
	}
	if ($soldview=="Y") {
		$etctype.="MAINSOLD=Y";
	}
	if ($imgsero=="Y") {
		$etctype.="IMGSERO=Y";
	}

	if($bfont=="Y" || strlen($fontcolor)>0){
		$etctype.="SELL=".$bfont.",".$fontcolor."";
	}
	if((strlen($prevdollar)>0 || strlen($nextdollar)>0) && $dollarprice>0){
		$etctype.="DOLLAR=".$prevdollar.",".$dollarprice.",".$nextdollar."";
	}
	if($memprice=="Y"){
		$etctype.="MEM=Y";

		if (strlen($pricefile_name)>0 && strtolower(substr($pricefile_name,strlen($pricefile_name)-3,3))=="gif" && $pricefile_size <= 153600) {
			move_uploaded_file($pricefile,$imagepath."priceicon.gif");
			chmod($imagepath."priceicon.gif",0664);
			$etctype.="MEMIMG=Y";
		} else if (strlen($priceicon)>0) {
			$etctype.="MEMIMG=".$priceicon."";
		} else if (file_exists($imagepath."priceicon.gif")) {
			$etctype.="MEMIMG=Y";
		}
	}
	
	if ($quicktools=="Y") {
		$etctype.="QUICKTOOLS=Y";
	}

	if ($bottomtools=="Y") {
		$etctype.="BOTTOMTOOLS=Y";
	}

	if ($selfcodev=="Y") {
		if($selfcodel=="Y") {
			if($selfcoden=="Y") {
				$etctype.="SELFCODEVIEW=Y";
			} else {
				$etctype.="SELFCODEVIEW=Z";
			}
		} else {
			if($selfcoden=="Y") {
				$etctype.="SELFCODEVIEW=N";
			} else {
				$etctype.="SELFCODEVIEW=M";
			}
		}
	}
	
	if(strlen($selfcodefront)>0) {
		$etctype.="SELFCODEF=".$selfcodefront."";
	}

	if(strlen($selfcodeback)>0) {
		$etctype.="SELFCODEB=".$selfcodeback."";
	}

	$sql = "UPDATE tblshopinfo SET ";
	$sql.= "proption_price		= '".$proption_price."', ";
	$sql.= "primg_minisize		= '".$primg_minisize."', ";
	$sql.= "proption_size		= '".$proption_size."', ";
	$sql.= "pester_state		= '".$pester_state."', ";
	$sql.= "etctype				= '".$etctype."' ";
	$result = mysql_query($sql,get_db_conn());
	DeleteCache("tblshopinfo.cache");
	$onload="<script> alert('���� ������ �Ϸ�Ǿ����ϴ�. $msg'); </script>";
}

$sql = "SELECT * FROM tblshopinfo ";
$result = mysql_query($sql,get_db_conn());
if($row=mysql_fetch_object($result)) {
	$proption_price = $row->proption_price;
	$primg_minisize = $row->primg_minisize;
	$proption_size = $row->proption_size;
	$pester_state = $row->pester_state;
	if($proption_size<=0) $checkopt="F";
	else $checkopt="S";

	if (strlen($row->etctype)>0) {
		$etctemp = explode("",$row->etctype);
		$cnt = count($etctemp);
		$etcvalue="";
		for ($i=0;$i<$cnt;$i++) {
			if (substr($etctemp[$i],0,8)=="CODEYES=") $codeyes=substr($etctemp[$i],8);
			else if (substr($etctemp[$i],0,5)=="SELL=") $sellprice=substr($etctemp[$i],5);
			else if (substr($etctemp[$i],0,4)=="MEM=") $memprice=substr($etctemp[$i],4);
			else if (substr($etctemp[$i],0,7)=="DOLLAR=") $ardollar=substr($etctemp[$i],7);
			else if (strpos($etctemp[$i],"MEMIMG=")!==false) $memimg=substr($etctemp[$i],7);
			else if (strpos($etctemp[$i],"SELFCODEVIEW=")!==false) $selfcode=substr($etctemp[$i],13);
			else if (strpos($etctemp[$i],"SELFCODEF=")!==false) $selfcodefront=substr($etctemp[$i],10);
			else if (strpos($etctemp[$i],"SELFCODEB=")!==false) $selfcodeback=substr($etctemp[$i],10);
			else if (strpos($etctemp[$i],"MAINSOLD=Y")!==false) $soldview="Y";
			else if (strpos($etctemp[$i],"IMGSERO=Y")!==false) $imgsero="Y";
			else if (strpos($etctemp[$i],"QUICKTOOLS=Y")!==false) $quicktools="Y";
			else if (strpos($etctemp[$i],"BOTTOMTOOLS=Y")!==false) $bottomtools="Y";
			else if(strlen($etctemp[$i])>0) $etcvalue.=$etctemp[$i]."";
		}
	}
	$soldview=($soldview!="Y"?"N":"Y");
	$quicktools=($quicktools!="Y"?"N":"Y");
	$bottomtools=($bottomtools!="Y"?"N":"Y");

	if(strlen($selfcode)>0 && ($selfcode=="Y" || $selfcode=="Z" || $selfcode=="N" || $selfcode=="M")) { 
		$selfcodev="Y";
		if($selfcode=="Y" || $selfcode=="Z") {
			$selfcodel="Y";
		} else {
			$selfcodel="N";
		}

		if($selfcode=="Y" || $selfcode=="N") {
			$selfcoden="Y";
		} else {
			$selfcoden="N";
		}
	} else {
		$selfcodev="N";
		$selfcodel="N";
		$selfcoden="N";
	}
}
mysql_free_result($result);

$selltype=explode(",",$sellprice);
if(strlen($selltype[0])>0) $bfont="Y";
if(strlen($selltype[1])>0) $fontcolor=$selltype[1];

?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script>
function CheckForm(type) {
	if(type=="icondel"){
		if (!confirm("��ϵ� ��ǰ �������� �����Ͻðڽ��ϱ�?")) {
			return;
		}
	}
	form1.type.value=type;
	form1.submit();
}

function selcolor(){
	fontcolor = document.form1.fontcolor.value.substring(1);
	var newcolor = showModalDialog("color.php?color="+fontcolor, "oldcolor", "resizable: no; help: no; status: no; scroll: no;");
	if(newcolor){
		document.form1.fontcolor.value='#'+newcolor;
	}
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
			<? include ("menu_shop.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �������� &gt; ���θ� ȯ�� ���� &gt; <span class="2depth_select">��ǰ ���� ��Ÿ ����</span></td>
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
					<TD><IMG SRC="images/shop_productshow_title.gif" border="0"></TD>
				</TR>
				<TR>
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
					<TD width="100%" class="notice_blue">���θ��� ��ǰ ���� ���� ������ �� �� �ֽ��ϴ�.</TD>
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
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post enctype="multipart/form-data">
			<input type=hidden name=type>
			<input type=hidden name=etcvalue value="<?=$etcvalue?>">
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/shop_saleout_stitle2.gif"  ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
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
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/distribute_01.gif"></TD>
					<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
					<TD><IMG SRC="images/distribute_03.gif"></TD>
				</TR>
				<TR>
					<TD background="images/distribute_04.gif"></TD>
					<TD class="notice_blue" valign="top"><IMG SRC="images/distribute_img.gif" ></TD>
					<TD width="100%" class="notice_blue">1) ����/ī�װ��� ��ǰ����Ʈ �̹��� ����� ���� �� �� �ֽ��ϴ�.<br>2) ���簢���� �̹����� ����� ��� ���λ���� ���� ���λ������ �ڵ������� ����˴ϴ�.</TD>
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
				<td height=3></td>
			</tr>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="150"><img src="images/icon_point2.gif" width="8" height="11" border="0">��ǰ �̹��� �ּ� ������</TD>
					<TD class="td_con1" >
					<input type="text" name="primg_minisize" style="width:60px;" value="<?=$primg_minisize?>">
					<!-- <select name=primg_minisize class="select">
<?
		for ($i=80;$i<=200;$i+=10) {
			echo "<option value=\"$i\"";
			if ($i==$primg_minisize) echo " selected";
			echo ">$i";
		}
		if($primg_minisize>200) {
			echo "<option value=\"$primg_minisize\" selected>$primg_minisize";
		}
?>
					</select> -->�ȼ� <input type=checkbox id="idx_imgsero1" name=imgsero value="Y" <?=$imgsero=="Y"?checked:""?>> <label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_imgsero1>���λ����� <b><?=$primg_minisize2?>�ȼ�</b>���� ���</label></TD>
				</TR>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height="25"></td></tr>
			<tr>
				<td><IMG SRC="images/shop_saleout_s1text10.gif" border="0"></td>
			</tr>
			<tr>
				<td height="5"></td>
			</tr>
			<tr>
				<td>

					<table cellpadding="0" cellspacing="0" width="100%">
						<TR>
							<TD bgcolor="#B9B9B9" height="1"></TD>
						</TR>
						<tr>
							<td>
								<table cellpadding="0" cellspacing="0" width="100%">
								<col width="150"></col>
								<col width=""></col>
									<TR>
										<TD class="table_cell"><b><img src="images/icon_point2.gif" border="0"></b>�ϴ� ���θ޴� ��� ����</TD>
										<TD class="td_con1"><input type=radio name="bottomtools" value="N" id="idx_bottomtools2" <?=($bottomtools=="N")?"checked":""?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_bottomtools2">�ϴ� ���θ޴� �����<font color="#000000">(����)</font></label>
						<input type=radio name="bottomtools" value="Y" id="idx_bottomtools1" <?=($bottomtools=="Y")?"checked":""?>><label style="cursor:hand;" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_bottomtools1">�ϴ� ���θ޴� ��¾���</label>
										</TD>
									</TR>
								</table>
							</td>
						</tr>
						<TR>
							<TD bgcolor="#B9B9B9" height="1"></TD>
						</TR>
					</table>


				</td>
			</tr>
			<tr><td height="3"></td></tr>
			<tr>
				<td style="padding-bottom:3pt;">
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/distribute_01.gif"></TD>
					<TD width="100%" background="images/distribute_02.gif"></TD>
					<TD><IMG SRC="images/distribute_03.gif"></TD>
				</TR>
				<TR>
					<TD background="images/distribute_04.gif"></TD>
					<td WIDTH="100%">
					<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
					<tr>
						<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" border="0"></TD>
						<TD width="100%" class="notice_blue">1. �ϴ� ���θ޴��� ��� ���θ� ������ �� �ֽ��ϴ� .<br>2. "<b>�ϴ� ���θ޴� �����(����)</b>" ������ ��� ���´� �Ʒ��� �����ϴ�.</td>
					</tr>
					<tr>
						<td class="notice_blue"></td>
						<td width="100%" class="notice_blue">
						<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>
						<tr>
							<td height="10" colspan=3></td>
						</tr>
						<tr>
							<td><img src="images/bottom_tools_col.gif" border="0" style="border:1px #C4C4C4 solid;"></td>
						</tr>
						</table>
						</td>
					</tr>
					</table>
					</TD>
					<TD background="images/distribute_07.gif"></TD>
				</TR>
				<TR>
					<TD><IMG SRC="images/distribute_08.gif"></TD>
					<TD background="images/distribute_09.gif"></TD>
					<TD><IMG SRC="images/distribute_10.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height="30"></td>
			</tr>
			<tr>
				<td><IMG SRC="images/shop_saleout_s1text09.gif" border="0"></td>
			</tr>
			<tr>
				<td height="5"></td>
			</tr>
			<tr>
				<td>
					<table cellpadding="0" cellspacing="0" width="100%">
						<TR>
							<TD bgcolor="#B9B9B9" height="1"></TD>
						</TR>
						<tr>
							<td>
								<table cellpadding="0" cellspacing="0" width="100%">
								<col width="150"></col>
								<col width=""></col>
									<TR>
										<TD class="table_cell"><b><img src="images/icon_point2.gif" border="0"></b>��ǰ ������ ��� ����</TD>
										<TD class="td_con1"><input type=radio name="quicktools" value="N" id="idx_quicktools2" <?=($quicktools=="N")?"checked":""?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_quicktools2">������ �����<font color="#000000">(����)</font></label>
											<input type=radio name="quicktools" value="Y" id="idx_quicktools1" <?=($quicktools=="Y")?"checked":""?>><label style="cursor:hand;" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_quicktools1">������ ��¾���</label>
										</TD>
									</TR>
								</table>
							</td>
						</tr>
						<TR>
							<TD bgcolor="#B9B9B9" height="1"></TD>
						</TR>
					</table>


				</td>
			</tr>
			<tr><td height="10"></td></tr>
			<tr>
				<td style="padding-bottom:3pt;">
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/distribute_01.gif"></TD>
					<TD width="100%" background="images/distribute_02.gif"></TD>
					<TD><IMG SRC="images/distribute_03.gif"></TD>
				</TR>
				<TR>
					<TD background="images/distribute_04.gif"></TD>
					<td WIDTH="100%">
					<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
					<tr>
						<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" border="0"></TD>
						<TD width="100%" class="notice_blue">1. ��ǰ���� ��� ��½ÿ� ������ ��� ���θ� �����մϴ�.<br>2. "<b>������ �����(����)</b>" ������ ��� ���´� �Ʒ��� �����ϴ�.</td>
					</tr>
					<tr>
						<td class="notice_blue"></td>
						<td width="100%" class="notice_blue">
						<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>
						<tr>
							<td height="5" colspan=3></td>
						</tr>
						<tr align="center">
							<td><b>�̹���A�� Ÿ��</b></td>
							<td style="padding-left:20px;"></td>
							<td><b>�̹���B��, ����Ʈ�� Ÿ��</b></td>
						</tr>
						<tr>
							<td height="5" colspan=3></td>
						</tr>
						<tr>
							<td><img src="images/quick_tools_col.gif" border="0"></td>
							<td style="padding-left:20px;"></td>
							<td valign=top><img src="images/quick_tools_row.gif" border="0"></td>
						</tr>
						</table>
						</td>
					</tr>
					</table>
					</TD>
					<TD background="images/distribute_07.gif"></TD>
				</TR>
				<TR>
					<TD><IMG SRC="images/distribute_08.gif"></TD>
					<TD background="images/distribute_09.gif"></TD>
					<TD><IMG SRC="images/distribute_10.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height="30"></td>
			</tr>
			<tr>
				<td>
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td>
					<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
					<TR>
						<TD><IMG SRC="images/shop_saleout_stitle3.gif"  ALT=""></TD>
						<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
						<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
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
						<TD width="100%" class="notice_blue">1) ���ΰ� ī�װ� ��ǰ����Ʈ�� ǰ��ǥ�� �����Դϴ�. �̹����� ���� ��쿡�� <font color="red">(ǰ��)</font>�� ǥ�� �˴ϴ�.<br>2) ��ǰ����Ʈ�� ��ǰ�� ���� ǰ��ǥ�� ����</TD>
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
				<tr><td height=3></td></tr>
				<tr>
					<td>
					<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
					<TR>
						<TD colspan=2 background="images/table_top_line.gif"></TD>
					</TR>
					<TR>
						<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">ǰ�� ǥ�� ����</TD>
						<TD class="td_con1" ><input type=radio id="idx_soldview1" name=soldview value="Y" <?=($soldview=="Y")?"checked":""?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_soldview1>ǰ�� ǥ����(O)</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=radio id="idx_soldview2" name="soldview" value="N" <?=($soldview=="N")?"checked":""?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_soldview2>ǰ�� ǥ�� ����(X)</label></TD>
					</TR>
					<TR>
						<TD colspan="2"  background="images/table_con_line.gif"></TD>
					</TR>
					<TR>
						<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">ǰ�� ������ ���ε�</TD>
						<TD class="td_con1" ><input type=file name=image <? if (file_exists($imagepath."soldout.gif")) { ?> style="WIDTH: 300px" class="input"> <a href="javascript:CheckForm('del');"><img src="images/icon_del1.gif" width="37" height="14" border="0" align=absmiddle></a> <img src="<?=$imagepath?>soldout.gif" border=0 align=absmiddle><?}else {?> style="WIDTH: 100%" class="input"><?}?><br><span class="font_orange">* ��� ���� �̹����� 150KB ������ GIF(gif)���ϸ� �����մϴ�.</span></TD>
					</TR>
					<TR>
						<TD colspan=2 background="images/table_top_line.gif"></TD>
					</TR>
					</TABLE>
					</td>
				</tr>
				<!--
				<tr>
					<td height="30">&nbsp;</td>
				</tr>
				<tr>
					<td>
					<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
					<TR>
						<TD><IMG SRC="images/shop_mainproduct_stitle5.gif" WIDTH="192" HEIGHT=31 ALT=""></TD>
						<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
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
					<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
					<TR>
						<TD><IMG SRC="images/distribute_01.gif"></TD>
						<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
						<TD><IMG SRC="images/distribute_03.gif"></TD>
					</TR>
					<TR>
						<TD background="images/distribute_04.gif"></TD>
						<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
						<TD width="100%" class="notice_blue">1) ���λ�ǰ�� �ǸŰ��� ������ ������ �� �ֽ��ϴ�.<br>
						2) <a href="javascript:parent.topframe.GoMenu(2,'design_easycss.php');"><span class="font_blue">�����ΰ��� > Easy ������ ���� > Easy �ؽ�Ʈ �Ӽ� ����</font></a> ���� ����, ��ǰ����Ʈ, ��ǰ�� ���� ��Ʈ ��Ÿ���� �߰��� ������ �� �ֽ��ϴ�.<br>
						3) Easy �������� ����� ��� Easy������ ���������� ����˴ϴ�.
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
				<tr>
					<td height=3></td>
				</tr>
				<tr>
					<td>
					<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
					<TR>
						<TD colspan=2 background="images/table_top_line.gif"></TD>
					</TR>
					<TR>
						<TD width="146" class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">��ǰ���ݻ��� ����</TD>
						<TD class="td_con1" >���� : <input type=text name=fontcolor size=10 maxlength=7 value="<?=$fontcolor?>" class="input"> <a href="JavaScript:selcolor()"><img src="images/btn_color.gif" width="111" height="16" border="0" hspace="1"></a>  &nbsp;&nbsp;&nbsp;&nbsp;�β� : <input type=checkbox id="idx_bfont1" name=bfont value="Y" <?=($bfont=="Y"?"checked":"")?>> <label style='cursor:hand; TEXT-DECORATION: none' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_bfont1>����</label></TD>
					</TR>
					<TR>
						<TD colspan=2 background="images/table_top_line.gif"></TD>
					</TR>
					</TABLE>
					</td>
				</tr>
				-->
				<tr><td height="25"></td></tr>
				<tr>
					<td>
					<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
					<TR>
						<TD><IMG SRC="images/shop_selfcode_stitle1.gif" border="0"></TD>
						<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
						<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
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
						<TD width="100%" class="notice_blue">��ǰ ���/������ �Է��� �����ڵ��� ��� �� ��ġ�� ������ �� �ֽ��ϴ�.</TD>
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
				<tr><td height=3></td></tr>
				<tr>
					<td>
					<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
					<TR>
						<TD colspan=2 background="images/table_top_line.gif"></TD>
					</TR>
					<TR>
						<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">�����ڵ� ��� ����</TD>
						<TD class="td_con1" ><input type=radio id="idx_selfcodev1" name=selfcodev value="Y" <?=($selfcodev=="Y")?"checked":""?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_selfcodev1>�����ڵ� �����(O)</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=radio id="idx_selfcodev2" name="selfcodev" value="N" <?=($selfcodev!="Y")?"checked":""?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_selfcodev2>�����ڵ� ��� ����(X)</label><br>
						<span class="font_orange">* �����ڵ� ����� ���� ��ǰ ��Ͽ��� ��µ˴ϴ�.</span></TD>
					</TR>
					<TR>
						<TD colspan="2"  background="images/table_con_line.gif"></TD>
					</TR>
					<TR>
						<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">�����ڵ� ��� ��ġ</TD>
						<TD class="td_con1" ><input type=radio id="idx_selfcodel1" name=selfcodel value="Y" <?=($selfcodel=="Y")?"checked":""?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_selfcodel1>��ǰ�� ��</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=radio id="idx_selfcodel2" name="selfcodel" value="N" <?=($selfcodel!="Y")?"checked":""?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_selfcodel2>��ǰ�� ��</label>&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=checkbox id="idx_selfcoden1" name=selfcoden value="Y" <?=$selfcoden=="Y"?checked:""?>> <label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_selfcoden1>�ٹٲ�</label>
						</TD>
					</TR>
					<TR>
						<TD colspan="2"  background="images/table_con_line.gif"></TD>
					</TR>
					<TR>
						<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">�����ڵ� ���� ����</TD>
						<TD class="td_con1" >�����ڵ� �� ���� : <input type=text name=selfcodefront value="<?=$selfcodefront?>" size=10 maxlength=12 onKeyDown="chkFieldMaxLen(12)" class="input">&nbsp;&nbsp;<span class="font_orange">* Html�� ��� �Ұ��̸� �Է� �ִ�� 12Byte(����12��, �ѱ�6��)</span><br>
						�����ڵ� �� ���� : <input type=text name=selfcodeback value="<?=$selfcodeback?>" size=10 maxlength=8 onKeyDown="chkFieldMaxLen(8)" class="input">&nbsp;&nbsp;<span class="font_orange">* Html�� ��� �Ұ��̸� �Է� �ִ�� 8Byte(����8��, �ѱ�4��)</span><br>
						<span class="font_orange">* �����ڵ� ���� ���ڴ� �����ڵ� ��½� �߰��� �յڿ� ���ڸ� ����� ���� ��� ����ϼ���.</span></TD>
					</TR>
					<TR>
						<TD colspan=2 background="images/table_top_line.gif"></TD>
					</TR>
					</TABLE>
					</td>
				</tr>
				<tr><td height="25"></td></tr>
				<tr>
					<td>
					<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
					<TR>
						<TD><IMG SRC="images/shop_saleout_stitle5.gif"  ALT=""></TD>
						<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
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
						<TD width="100%" class="notice_blue">��ǰ ���������� ��ȭǥ�ø� �Ͻ÷��� ȯ�� �� ��ȣ�� �����Ͻð� [��ǰ ������ �������]���� �߰��Ͻø� �˴ϴ�.</TD>
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
					<td height=3></td>
				</tr>
<?
		$ardollar=explode(",",$ardollar);
		$dollar1=$ardollar[0];
		$dollarprice=$ardollar[1];
		$dollar2=$ardollar[2];
?>
				<tr>
					<td>
					<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
					<TR>
						<TD colspan=2 background="images/table_top_line.gif"></TD>
					</TR>
					<TR>
						<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">ȯ�� �⺻��</TD>
						<TD class="td_con1" ><input type=text name=dollarprice value="<?=$dollarprice?>" size=10 maxlength=5 class="input"> ��</TD>
					</TR>
					<TR>
						<TD colspan="2"  background="images/table_con_line.gif"></TD>
					</TR>
					<TR>
						<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">��ȭ ��ȣ</TD>
						<TD class="td_con1" >
<?
			$prevdollar=array("","$","��","��","EUR","USD","US $","Can$");
			$nextdollar=array("","�޷�","����","��","����","EUR","USD","Can$");
			$dollarcnt = count($prevdollar);
			echo "<select name=prevdollar class=\"select\">";
			for($i=0;$i<$dollarcnt;$i++){
				echo "<option value=\"".$prevdollar[$i]."\"";
				if($dollar1 ==$prevdollar[$i]) echo " selected";
				if($i==0) $prevdollar[$i]="ǥ������ ����";
				echo ">".$prevdollar[$i];
			}
			echo "</select> <!--1,234.56--> ";
			echo "<select name=nextdollar class=\"select\">";
			for($i=0;$i<$dollarcnt;$i++){
				echo "<option value=\"".$nextdollar[$i]."\"";
				if($dollar2 ==$nextdollar[$i]) echo " selected";
				if($i==0) $nextdollar[$i]="ǥ������ ����";
				echo ">".$nextdollar[$i];
			}
			echo "</select>";
?>
						</TD>
					</TR>
					<TR>
						<TD background="images/table_top_line.gif" colspan=2></TD>
					</TR>
					</TABLE>
					</td>
				</tr>
				<tr><td height="25"></td></tr>
				<tr>
					<td>
					<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
					<TR>
						<TD><IMG SRC="images/shop_saleout_stitle9.gif"  ALT="������ ����"></TD>
						<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
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
						<TD width="100%" class="notice_blue">��ǰ ���Ž� ������ ��� ��뿩�θ� �����մϴ�.</TD>
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
					<td height=3></td>
				</tr>
				<tr>
					<td>
					<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
					<TR>
						<TD colspan=2 background="images/table_top_line.gif"></TD>
					</TR>
					<TR>
						<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">������ ��� ��뿩��</TD>
						<TD class="td_con1" ><input type=radio id="idx_pester_state1" name=pester_state value="N" <?=($pester_state!="Y"?"checked":"")?>> <label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_pester_state1>������ ������</label> &nbsp;&nbsp;&nbsp;&nbsp;<input type=radio id="idx_pester_state2" name=pester_state value="Y" <?=($pester_state=="Y"?"checked":"")?>> <label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_pester_state2>������ ���</label></TD>
					</TR>
					<TR>
						<TD background="images/table_top_line.gif" colspan=2></TD>
					</TR>
					</TABLE>
					</td>
				</tr>
				<tr><td height="25"></td></tr>
				<tr>
					<td>
					<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
					<TR>
						<TD><IMG SRC="images/shop_saleout_stitle6.gif"  ALT=""></TD>
						<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
						<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
					</TR>
					</TABLE>
					</td>
				</tr>
				<tr>
					<td style="padding-top:3px; padding-bottom:3px;">
					<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
					<TR>
						<TD><IMG SRC="images/distribute_01.gif"></TD>
						<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
						<TD><IMG SRC="images/distribute_03.gif"></TD>
					</TR>
					<TR>
						<TD background="images/distribute_04.gif"></TD>
						<TD class="notice_blue" valign="top"><IMG SRC="images/distribute_img.gif" ></TD>
						<TD width="100%" class="notice_blue">1) ȸ�����Ը� ���� ����ÿ� ȸ������ ���� �Ǵ� ���������� ǥ�� �� �� �ֽ��ϴ�.<br>2) ��ϵ� �̹����� ������ �̹����� Ŭ���Ͻø� ������ �˴ϴ�.</TD>
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
					<td>
					<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
					<TR>
						<TD colspan=2 background="images/table_top_line.gif"></TD>
					</TR>
					<TR>
						<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">��ǰ���� ���⿩��<br></TD>
						<TD class="td_con1" ><input type=radio id="idx_memprice1" name=memprice value="N" <?=($memprice!="Y"?"checked":"")?>> <label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_memprice1>ȸ��/��ȸ�� ��� ����</label> &nbsp;&nbsp;&nbsp;&nbsp;<input type=radio id="idx_memprice2" name=memprice value="Y" <?=($memprice=="Y"?"checked":"")?>> <label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_memprice2>ȸ�����Ը� ��ǰ ���� ����</label>(������ �̻��� <span class="font_blue">ȸ������</span> ���� ǥ��)</TD>
					</TR>
					<TR>
						<TD colspan="2"  background="images/table_con_line.gif"></TD>
					</TR>
					<TR>
						<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">�⺻ ������ ���</TD>
						<TD class="td_con1" >
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
						<TR height=5>
							<TD colSpan=7></TD>
						</TR>
						<TR>
<?
			for ($i=1 ; $i <= 9 ; $i++) { 
				if ($i%5==1) echo "<tr>";
?>
				<td>&nbsp;<input type=radio id="idx_priceicon<?=$i?>" name=priceicon value="<?=$i?>" <? if ($memimg=="$i") echo "checked"?>> <img src="<?=$Dir?>images/common/priceicon<?=$i?>.gif" align=absmiddle></td>
<?
				if ($i%5==0) echo "</tr>\n";
			}
?>
							<td>&nbsp;<input type=radio id="idx_priceiconN" name=priceicon value="N" <? if ($memimg=="N") echo "checked"?>> <label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_priceiconN>ǥ�þ���</label></td>
						</TR>
						</TABLE>
						</TD>
					</TR>
					<TR>
						<TD colspan="2"  background="images/table_con_line.gif"></TD>
					</TR>													
					<TR>
						<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">���� ����� �����ܻ��</TD>
						<TD class="td_con1" ><input type=file name=pricefile size=30 class="input">
<?
			if (file_exists($imagepath."priceicon.gif")) 
			echo "&nbsp;&nbsp;��ϵ� ������ : <a href=\"javascript:CheckForm('icondel');\"><img src='".$imagepath."priceicon.gif' border=0 align=absmiddle></a>\n";
?>
						<br><span class="font_orange">* ��� ���� �̹����� 150KB ������ GIF(gif)���ϸ� �����մϴ�.</span>
						</TD>
					</TR>
					<TR>
						<TD colspan=2 background="images/table_top_line.gif"></TD>
					</TR>
					</TABLE>
					</td>
				</tr>
				<tr><td height="30"></td></tr>
				<tr>
					<td>
					<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
					<TR>
						<TD><IMG SRC="images/shop_saleout_stitle4.gif" ALT=""></TD>
						<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
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
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td  bgcolor="#EDEDED" style="padding:4pt;">
						<table cellpadding="0" cellspacing="0" width="100%" bgcolor="white">
						<tr>
							<td >
							<TABLE cellSpacing=0 cellPadding=0  border=0 width=100%>
							<TR>
								<TD  height="35" background="images/blueline_bg.gif"><p align="center"><input type=radio id="idx_codeyes1" name=codeyes value="Y" class="radio" <? if ($codeyes!="N") echo "checked"; ?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_codeyes1><b><font color="#333333">�׻� ����ٴ�</span></b></label></TD>
								<TD  height="35" background="images/blueline_bg.gif"><p align="center"><input type=radio id="idx_codeyes2" name=codeyes value="N" class="radio" <? if ($codeyes=="N") echo "checked"; ?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_codeyes2><b><font color="#333333">ǥ������ ����</span></b></label></TD>
							</TR>
							<TR>
								<TD colspan="2"  background="images/table_con_line.gif"></TD>
							</TR>
							<TR>
								<TD  style="padding-top:10pt; padding-bottom:10pt;"><p align="center"><img src="images/shop_saleout_img1.gif" border="0" class="imgline"></TD>
								<TD  class="td_con1"><p align="center"><img src="images/shop_saleout_img2.gif" border="0" class="imgline"></TD>
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
					<td height="25">&nbsp;</td>
				</tr>
				<tr>
					<td>
					<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
					<TR>
						<TD><IMG SRC="images/shop_saleout_stitle7.gif"  ALT=""></TD>
						<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
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
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td bgcolor="#EDEDED" style="padding:4pt;">
						<table cellpadding="0" cellspacing="0" width="100%" bgcolor="white">
						<tr>
							<td >
							<TABLE cellSpacing=0 cellPadding=0  border=0 width="100%">
							<TR>
								<TD  height="35" background="images/blueline_bg.gif"><p align="center"><input type=radio id="idx_checkopt1" name=checkopt value="F" <?if($checkopt=="F") echo "checked"?> onClick="document.form1.proption_size.disabled=true;document.form1.proption_size.style.background='#EFEFEF';document.form1.proption_size.value='';"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_checkopt1><font color="#333333"><b>�ڵ����� �����մϴ�.</label></b></span></TD>
								<TD  height="35" background="images/blueline_bg.gif"><p align="center"><input type=radio id="idx_checkopt2" name=checkopt value="S" <?if($checkopt=="S") echo "checked"?> onClick="document.form1.proption_size.disabled=false;document.form1.proption_size.style.background='white';document.form1.proption_size.value='<?=$proption_size?>';"><input name=proption_size size=3 maxlength=3 value="<?if($proption_size==0) echo ""; else echo $proption_size;?>" class="input_selected"> <label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_checkopt2><font color="#333333"><b>�ȼ��� ���� ��ŵ�ϴ�. (���� 230�ȼ�)</b></span></label></TD>
							</TR>
							<TR>
								<TD colspan="2"  background="images/table_con_line.gif"></TD>
							</TR>
							<TR>
								<TD  style="padding-top:10pt; padding-bottom:10pt;"><p align="center"><IMG src="images/mainproduct_img8_1.gif" align=absMiddle></TD>
								<TD  class="td_con1"><p align="center"><IMG src="images/mainproduct_img8_2.gif" align=absMiddle></TD>
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
					<td height="25"></td>
				</tr>
				<tr>
					<td>
					<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
					<TR>
						<TD><IMG SRC="images/shop_saleout_stitle8.gif"  ALT=""></TD>
						<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
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
					<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
					<TR>
						<TD><IMG SRC="images/distribute_01.gif"></TD>
						<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
						<TD><IMG SRC="images/distribute_03.gif"></TD>
					</TR>
					<TR>
						<TD background="images/distribute_04.gif"></TD>
						<TD class="notice_blue" valign="top"><IMG SRC="images/distribute_img.gif" ></TD>
						<TD width="100%" class="notice_blue">1) ��ǰ ������ �ɼǺ��� Ʋ����� ������ �޼����� ������ �� �ֽ��ϴ�.<br>2) ��ǰ ������ ǥ���ϰ� ������� <B>[PRICE]</B>�� �Է��Ͻñ� �ٶ��ϴ�.<br>3) �Է��� �ȵǾ��� ��� �⺻���� ���õ˴ϴ�.</TD>
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
					<td height=3></td>
				</tr>
				<tr>
					<td>
					<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
					<TR>
						<TD colspan=2 background="images/table_top_line.gif"></TD>
					</TR>
					<TR>
						<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">��ü ǥ�� ���� �Է�</TD>
						<TD class="td_con1" ><input type=text name=proption_price size=50 maxlength=50 value="<?=$proption_price?>" onKeyDown="chkFieldMaxLen('100')" class="input" style=width:100%></TD>
					</TR>
					<TR>
						<TD colspan=2 background="images/table_top_line.gif"></TD>
					</TR>
					</TABLE>
					</td>
				</tr>
				<tr>
					<td height=3></td>
				</tr>
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
						<TD class="notice_blue" valign="top"><IMG SRC="images/distribute_img.gif" ></TD>
						<TD width="100%" class="notice_blue">
						<table cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td width="676"><span class="font_blue">1)��ǰ���� ��ºκ��� ������ �� �ֽ��ϴ�.<br></span></td>
						</tr>
						<tr>
							<td width="676"><span class="font_blue">2)</span><span class="font_orange" style="letter-spacing:-0.5pt;"><b>��ǰ���� ǥ�� + ���� ���� = [PRICE]�Է� + �����Է�</b><br></span></td>
						</tr>
						<tr>
							<td width="676">&nbsp;</td>
						</tr>
						<tr>
							<td width="676"><b><span class="font_blue" style="letter-spacing:-0.5pt;">[��ǰ �ɼǰ� ǥ�� ���� �Է¿���]&nbsp;</span></b></td>
						</tr>
						<tr>
							<td width="676">
							<table cellpadding="0" cellspacing="0" width="99%">
							<tr>
								<td width="179"><span class="font_blue">�� ���Է�(�⺻-���ݸ�ǥ��)</span></td>
								<td width="150"><INPUT class="input" style=width:100% readonly></td>
								<td width="32"><p align="center"><span class="font_blue">��</span></td>
								<td width="325"><span class="font_blue">�ǸŰ��� 89,000��</span></td>
							</tr>
							<tr>
								<td width="179"><span class="font_blue">�� [PRICE]��+�����Է�<br></span></td>
								<td width="150"><INPUT class="input" style=width:100% value="[PRICE]�� ������ ���" readonly></td>
								<td width="32"><p align="center"><span class="font_blue">��</span></td>
								<td width="325"><span class="font_blue">�ǸŰ��� 89,000 ������ ���</span></td>
							</tr>
							<tr>
								<td width="179"><span class="font_blue">�� �����Է¸� �Է�(���ݹ�ǥ��)</span></td>
								<td width="150"><INPUT class="input" style=width:100% value="������ ���" readonly></td>
								<td width="32"><p align="center"><span class="font_blue">��</span></td>
								<td width="325"><span class="font_blue">�ǸŰ��� ������ ���</span></td>
							</tr>
							</table>
							</td>
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
				<tr>
					<td height=10></td>
				</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td align="center"><a href="javascript:CheckForm('up');"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a></td>
			</tr>
			</form>
			<tr>
				<td height=20></td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/manual_top1.gif" WIDTH=15 HEIGHT=45 ALT=""></TD>
					<TD><IMG SRC="images/manual_title.gif" WIDTH=113 HEIGHT=45 ALT=""></TD>
					<TD width="100%" background="images/manual_bg.gif"></TD>
					<TD background="images/manual_bg.gif"></TD>
					<TD><IMG SRC="images/manual_top2.gif" WIDTH=18 HEIGHT=45 ALT=""></TD>
				</TR>
				<TR>
					<TD background="images/manual_left1.gif"></TD>
					<TD COLSPAN=3 width="100%" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;" class="menual_bg">
					<table cellpadding="0" cellspacing="0" width="99%">
					<tr>
						<td width="163"><img src="images/shop_mainproduct_img5.gif" border="0"></td>
						<td  valign="top">
						<table cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
							<td width="100%"><b>��ġ���� ����<br></b></td>
						</tr>
						<tr>
							<td width="20" align="right" height="38">&nbsp;</td>
							<td width="100%" class="space_top" height="38">- <a href="javascript:parent.topframe.GoMenu(2,'design_eachmain.php');"><span class="font_blue">�����ΰ��� > ���������� - ���� �� ���ϴ� > ���κ��� �ٹ̱�</span></a> ���� ���� ������ ���� �� ��ġ��<br><b>&nbsp;&nbsp;</b>������ �� �ֽ��ϴ�.</td>
						</tr>
						
						</table>
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