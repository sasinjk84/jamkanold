<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "de-7";
$MenuCode = "design";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$imagepath=$Dir.DataDir."shopimages/etc/";

$type=$_POST["type"];
if($type=="update") {
	$menu_list=substr($_POST["menu_list"],1);
	$top_xsize=(int)$_POST["top_xsize"];
	$top_ysize=(int)$_POST["top_ysize"];
	$menu_align=$_POST["menu_align"];
	$logo_loc=$_POST["logo_loc"];

	$isbackground=$_POST["isbackground"];
	if($isbackground!="Y") $isbackground="N";

	$link1=$_POST["menulink16"];
	$link2=$_POST["menulink17"];
	$link3=$_POST["menulink18"];
	$link4=$_POST["menulink19"];
	$link5=$_POST["menulink20"];

	$okdesign=$_POST["okdesign"];

	if(strlen($menu_list)==0) $menu_list="1,2,3,4,5,6";
	if($top_xsize==0) $top_xsize=900;
	if($top_ysize==0) $top_ysize=120;
	if($menu_align!="L" && $menu_align!="C" && $menu_align!="R") $menu_align="L";
	if($logo_loc!="T" && $logo_loc!="Y") $logo_loc="T";

	$shopimg=array(1=>&$_FILES["menuimg1"],2=>&$_FILES["menuimg2"],3=>&$_FILES["menuimg3"],4=>&$_FILES["menuimg4"],5=>&$_FILES["menuimg5"],6=>&$_FILES["menuimg6"],7=>&$_FILES["menuimg7"],8=>&$_FILES["menuimg8"],9=>&$_FILES["menuimg9"],10=>&$_FILES["menuimg10"],11=>&$_FILES["menuimg11"],12=>&$_FILES["menuimg12"],13=>&$_FILES["menuimg13"],14=>&$_FILES["menuimg14"],15=>&$_FILES["menuimg15"],16=>&$_FILES["menuimg16"],17=>&$_FILES["menuimg17"],18=>&$_FILES["menuimg18"],19=>&$_FILES["menuimg19"],20=>&$_FILES["menuimg20"],21=>&$_FILES["background"],22=>&$_FILES["logoimg"]);

	$vshopimg=array(1=>&$_POST["vmenuimg1"],2=>&$_POST["vmenuimg2"],3=>&$_POST["vmenuimg3"],4=>&$_POST["vmenuimg4"],5=>&$_POST["vmenuimg5"],6=>&$_POST["vmenuimg6"],7=>&$_POST["vmenuimg7"],8=>&$_POST["vmenuimg8"],9=>&$_POST["vmenuimg9"],10=>&$_POST["vmenuimg10"],11=>&$_POST["vmenuimg11"],12=>&$_POST["vmenuimg12"],13=>&$_POST["vmenuimg13"],14=>&$_POST["vmenuimg14"],15=>&$_POST["vmenuimg15"],16=>&$_POST["vmenuimg16"],17=>&$_POST["vmenuimg17"],18=>&$_POST["vmenuimg18"],19=>&$_POST["vmenuimg19"],20=>&$_POST["vmenuimg20"],21=>&$isbackground,22=>&$_POST["islogoimg"]);

	$display=array(1=>&$_POST["display1"],2=>&$_POST["display2"],3=>&$_POST["display3"],4=>&$_POST["display4"],5=>&$_POST["display5"],6=>&$_POST["display6"],7=>&$_POST["display7"],8=>&$_POST["display8"],9=>&$_POST["display9"],10=>&$_POST["display10"],11=>&$_POST["display11"],12=>&$_POST["display12"],13=>&$_POST["display13"],14=>&$_POST["display14"],15=>&$_POST["display15"],16=>&$_POST["display16"],17=>&$_POST["display17"],18=>&$_POST["display18"],19=>&$_POST["display19"],20=>&$_POST["display20"],21=>&$display21,22=>&$display22);

	if(strlen($shopimg[21][name])>0 && file_exists($shopimg[21][tmp_name])) {
		$display21="21";
		$isbackground="Y";
	}
	if(strlen($shopimg[22][name])>0 && file_exists($shopimg[22][tmp_name])) {
		$display22="22";
	}


	$iserror=false;
	for($i=1;$i<=count($shopimg);$i++) {
		if($display[$i]==$i) {
			if(strlen($shopimg[$i][name])>0 && file_exists($shopimg[$i][tmp_name])) {
				$ext = strtolower(substr($shopimg[$i][name],strlen($shopimg[$i][name])-3,3));
				if($ext=="gif") {
					$shopimg[$i][name]="easytopmenu".$i.".gif";
					if($i==21) {
						$shopimg[$i][name]="easytopbg.gif";
					} else if($i==22) {
						$shopimg[$i][name]="logo.gif";
					}
					move_uploaded_file($shopimg[$i][tmp_name],$imagepath.$shopimg[$i][name]);
					chmod($imagepath.$shopimg[$i][name],0664);
				} else {
					$iserror=true;
				}
			} else if(strlen($vshopimg[$i])==0) {
				$iserror=true;
			}
		}
	}

	if($iserror) {
		echo "<html></head><body onload=\"alert('�̹��� ����� �߸��Ǿ����ϴ�.');location.href='".$_SERVER[PHP_SELF]."'\"></body></html>";exit;
	}

	$sql = "SELECT COUNT(*) as cnt FROM tbldesign ";
	$result=mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
	$cnt=(int)$row->cnt;
	mysql_free_result($result);
	$qry="";
	if($cnt==0) {
		$sql = "INSERT tbldesign SET ";
	} else {
		$sql = "UPDATE tbldesign SET ";
	}
	$sql.= "top_set		= 'Y', ";
	$sql.= "top_xsize	= '".$top_xsize."', ";
	$sql.= "top_ysize	= '".$top_ysize."', ";
	$sql.= "menu_align	= '".$menu_align."', ";
	$sql.= "background	= '".$isbackground."', ";
	$sql.= "logo_loc	= '".$logo_loc."', ";
	$sql.= "menu_list	= '".$menu_list."', ";
	$sql.= "link1		= '".$link1."', ";
	$sql.= "link2		= '".$link2."', ";
	$sql.= "link3		= '".$link3."', ";
	$sql.= "link4		= '".$link4."', ";
	$sql.= "link5		= '".$link5."' ";
	$sql.= $qry;
	mysql_query($sql,get_db_conn());
	DeleteCache("tbldesign.cache");

	if($okdesign=="Y") {
		$sql = "UPDATE tblshopinfo SET top_type='tope' ";
		mysql_query($sql,get_db_conn());
		DeleteCache("tblshopinfo.cache");

		$_shopdata->top_type="tope";
	}
	$onload = "<script> alert('���� ������ �Ϸ�Ǿ����ϴ�.'); </script>";
}

$sql = "SELECT * FROM tbldesign ";
$result=mysql_query($sql,get_db_conn());
$row=mysql_fetch_object($result);
if($row->top_set=="Y") {
	$top_xsize=$row->top_xsize;
	$top_ysize=$row->top_ysize;
	$menu_align=$row->menu_align;
	$background=$row->background;
	$logo_loc=$row->logo_loc;
	$menu_list=$row->menu_list;
	$link1=$row->link1;
	$link2=$row->link2;
	$link3=$row->link3;
	$link4=$row->link4;
	$link5=$row->link5;
} else {
	$menu_align="L";
	$logo_loc="T";
	$menu_list="1,2,3,4,5,6";
}
mysql_free_result($result);
?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function CheckForm() {
	if(document.form1.top_xsize.value.length==0) {
		alert("��ܸ޴� ���� ����� �Է��ϼ���.");
		document.form1.top_xsize.focus();
		return;
	}
	if(!IsNumeric(document.form1.top_xsize.value)) {
		alert("��ܸ޴� ���� ������� ���ڸ� �Է� �����մϴ�.");
		document.form1.top_xsize.focus();
		return;
	}
	if(document.form1.top_ysize.value.length==0) {
		alert("��ܸ޴� ���� ����� �Է��ϼ���.");
		document.form1.top_ysize.focus();
		return;
	}
	if(!IsNumeric(document.form1.top_ysize.value)) {
		alert("��ܸ޴� ���� ������� ���ڸ� �Է� �����մϴ�.");
		document.form1.top_ysize.focus();
		return;
	}
	if(document.form1.logoimg.value.length==0 && document.form1.islogoimg.value.length==0) {
		alert("���θ� �ΰ� ����ϼ���.");
		return;
	}
	document.form1.menu_list.value="";
	for(i=1;i<=20;i++) {
		if(document.form1["display"+i].checked==true) {
			if(document.form1["menuimg"+i].value.length==0 && document.form1["vmenuimg"+i].value.length==0) {
				alert("�ش� �޴��� �̹����� ����ϼž� �մϴ�.");
				document.form1["menuimg"+i].focus();
				return;
				break;
			}
			if(document.form1["menulink"+i].value.length==0) {
				alert("�ش� �޴��� ��ũ�� �Է��ϼ���.");
				document.form1["menulink"+i].focus();
				return;
				break;
			}
			document.form1.menu_list.value+=","+document.form1["display"+i].value;
		}
	}
	if(document.form1.menu_list.value.length==0) {
		alert("�޴��� �ϳ� �̻� �����ϼž� �մϴ�.");
		return;
	}
	document.form1.type.value="update";
	document.form1.submit();
}

function menu_sort() {
	window.open("design_easytoppopup.php","easytop","height=100,width=100,toolbar=no,menubar=no,scrollbars=no,status=no");
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
			<? include ("menu_design.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �����ΰ��� &gt; Easy ������ ���� &gt; <span class="2depth_select">Easy ��� �޴� ����</span></td>
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
					<TD><IMG SRC="images/design_easytop_title.gif" ALT=""></TD>
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
					<TD width="100%" class="notice_blue"><p>���θ� ��� �������� ���� �����Ͻ� �̹��������� �̿��Ͽ�, �����ϰ� �������� �Ͻ� �� �ֽ��ϴ�.</p></TD>
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
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/distribute_01.gif" WIDTH=7 HEIGHT=7 ALT=""></TD>
					<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
					<TD><IMG SRC="images/distribute_03.gif" WIDTH=8 HEIGHT=7 ALT=""></TD>
				</TR>
				<TR>
					<TD background="images/distribute_04.gif"></TD>
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif"></TD>
					<TD width="100%" class="notice_blue">


					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="145" align=center style=padding-right:10px><img src="images/design_easytop_img1.gif" width="159" height="110" border="0"></td>
						<td  class="notice_blue">1) HTML�� ���� �̹����� ����Ͽ� ��ܸ޴��� �������� ���� �����մϴ�.<br>2) �⺻�޴��� ��ũ���� �Ұ�, �߰� �޴��� ��ũ�� ���/���� �����մϴ�.<br>3) Easy ������ ���� : ���� ���ø� ���� �Ǵ� ���������� �����ϸ� �˴ϴ�.</td>

					</tr>
					</table>


					</TD>
					<TD background="images/distribute_07.gif"></TD>
				</TR>
				<TR>
					<TD><IMG SRC="images/distribute_08.gif" WIDTH=7 HEIGHT=8 ALT=""></TD>
					<TD COLSPAN=2 background="images/distribute_09.gif"></TD>
					<TD><IMG SRC="images/distribute_10.gif" WIDTH=8 HEIGHT=8 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td>&nbsp;</td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/design_easytop_stitle1.gif" WIDTH="210" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=3></td></tr>
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post enctype="multipart/form-data">
			<input type=hidden name=type>
			<input type=hidden name=menu_list>
			<tr>
				<td style="PADDING-RIGHT: 10px; PADDING-LEFT: 10px; PADDING-BOTTOM: 10px; PADDING-TOP: 10px" bgcolor="#EBEBEB"><iframe name=contents src="/<?=RootPath.MainDir?>tope.php" frameborder=no scrolling=auto style="width:100%; height: 100%;"></iframe></td>
			</tr>
			<tr><td height="30"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/design_easytop_stitle2.gif" WIDTH="210" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
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
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">��� ������ ����</TD>
					<TD class="td_con1"><img src="images/icon_width.gif" width="28" height="14" border="0"> <input type=text name=top_xsize value="<?=$top_xsize?>" size=10 maxlength=3 onkeyup="strnumkeyup(this)" class="input">�ȼ� &nbsp;&nbsp;<img src="images/icon_height.gif" width="28" height="14" border="0"> <input type=text name=top_ysize value="<?=$top_ysize?>" size=10 maxlength=3 onkeyup="strnumkeyup(this)" class="input">�ȼ�</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">��ܸ޴� ���� ��ġ</TD>
					<TD class="td_con1" ><input type=radio name=menu_align value="L" <?if($menu_align=="L")echo"checked";?> style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;">���� &nbsp; <input type=radio name=menu_align value="C" <?if($menu_align=="C")echo"checked";?> style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;">��� &nbsp; <input type=radio name=menu_align value="R" <?if($menu_align=="R")echo"checked";?> style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;">������</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD width="148" class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">��� ����̹���</TD>
					<TD  class="td_con1"><p>&nbsp;<input type=file name=background size=45 style=width:100% class="input"><br><span class="font_orange">* �̵�Ͻ� ��� ���, ����̹��� ����� ���� ��� �ݺ��Ǵ� ���� �߻��˴ϴ�.</span></p><input type=hidden name=isbackground<?if(file_exists($imagepath."easytopbg.gif"))echo" value=Y";?>></TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD width="148" class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">���θ� �ΰ� �̹��� ����</TD>
					<TD  class="td_con1">
					<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
					<TR>
						<TD style="PADDING-BOTTOM: 5px" width="60">�ΰ��� :<input type=hidden name=islogoimg<?if(file_exists($imagepath."logo.gif"))echo" value=Y";?>></TD>
						<TD style="PADDING-BOTTOM: 5px" ><input type=file name=logoimg size=45 class="input" style=width:100%></TD>
					</TR>
					<TR>
						<TD style="PADDING-TOP: 5px" width="60">������ġ : </TD>
						<TD style="PADDING-TOP: 5px" ><input type=radio name=logo_loc value="T" <?if($logo_loc=="T")echo"checked";?> style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;"><img src="images/design_easytop_logoimg1.gif" border=0 align=absmiddle>&nbsp;<input type=radio name=logo_loc value="Y" <?if($logo_loc=="Y")echo"checked";?> style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;"><img src="images/design_easytop_logoimg2.gif" border=0 align=absmiddle></TD>
					</TR>
					</TABLE>
					</TD>
				</TR>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height="5"></td></tr>
			<tr>
				<td align=center>
				<TABLE BORDER=0 CELLPADDING=4 CELLSPACING=0>
				<TR>
					<TD><img src="images/design_easytop_imga21.gif" border="0"></TD>
					<TD><img src="images/design_easytop_imga22.gif" border="0"></TD>
					<TD><img src="images/design_easytop_imga23.gif" border="0"></TD>
				</TR>
				</TABLE>
				
				

			</tr>
			<tr><td height="30"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/design_easytop_stitle3.gif" WIDTH="210" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=3></td></tr>
			<tr>
				<td align="center">
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD colspan="2"><A HREF="javascript:menu_sort()"><img src="images/btn_array.gif" width="140" height="26" border="0" vspace="5"></a></TD>
				</TR>
				<TR>
					<TD colspan=5 background="images/table_top_line.gif"></TD>
				</TR>
				<TR align=center>
					<TD class="table_cell">No</TD>
					<TD class="table_cell1">��� �̹�����</TD>
					<TD class="table_cell1">�ش� �̹��� ���(GIF ���� - 150K ����)</TD>
					<TD class="table_cell1">��ũ �ּ� (URL)</TD>
					<TD class="table_cell1">���⿩��</TD>
				</TR>
				<TR>
					<TD colspan="5" background="images/table_con_line.gif"></TD>
				</TR>
<?
			$menu_all=",1 ,2 ,3 ,4 ,5 ,6 ,7 ,8 ,9 ,10 ,11 ,12 ,13 ,14 ,15 ,16 ,17 ,18 ,19 ,20 ";
			$menu_all_name=array(1=>"����������",2=>"ȸ��Ұ�",3=>"�̿�ȳ�",4=>"ȸ������/����",5=>"��ٱ���",6=>"�ֹ���ȸ",7=>"�α���",8=>"�α׾ƿ�",9=>"ȸ��Ż��",10=>"����������",11=>"������",12=>"�űԻ�ǰ",13=>"�α��ǰ",14=>"��õ��ǰ",15=>"Ư����ǰ",16=>"�߰��̹���1",17=>"�߰��̹���2",18=>"�߰��̹���3",19=>"�߰��̹���4",20=>"�߰��̹���5");
			$menu_all_url=array(1=>"[HOME]",2=>"[COMPANY]",3=>"[USEINFO]",4=>"[MEMBER]",5=>"[BASKET]",6=>"[ORDER]",7=>"[LOGIN]",8=>"[LOGOUT]",9=>"[MEMBEROUT]",10=>"[MYPAGE]",11=>"[EMAIL]",12=>"[PRODUCTNEW]",13=>"[PRODUCTBEST]",14=>"[PRODUCTHOT]",15=>"[PRODUCTSPECIAL]",16=>&$link1,17=>&$link2,18=>&$link3,19=>&$link4,20=>&$link5);

			$arr_menu_list=explode(",",$menu_list);
			$j=0;
			for($i=0;$i<count($arr_menu_list);$i++) {
				$j++;
				$menu_all=str_replace(",".$arr_menu_list[$i]." ","",$menu_all);
				echo "<tr>\n";
				echo "	<TD class=\"td_con2\" align=center>".$j."</td>\n";
				echo "	<TD class=\"td_con1\">".$menu_all_name[$arr_menu_list[$i]]."</td>\n";
				echo "	<TD class=\"td_con1\"><input type=file name=\"menuimg".$arr_menu_list[$i]."\" size=30 style=\"width:99%\" class=\"input\"></td>\n";
				echo "	<TD class=\"td_con1\"><input type=text name=\"menulink".$arr_menu_list[$i]."\" value=\"".$menu_all_url[$arr_menu_list[$i]]."\" size=40";
				if($arr_menu_list[$i]<=15) echo " readonly style=\"BACKGROUND: #f4f4f4; COLOR: #555555\"";
				echo " style=\"width:99%\" class=\"input\"></td>\n";
				echo "	<TD class=\"td_con1\" align=center><input type=checkbox name=\"display".$j."\" value=\"".$arr_menu_list[$i]."\" checked style=\"BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none\"></td>\n";
				echo "</tr>\n";
				echo "<input type=hidden name=vmenuimg".$arr_menu_list[$i]."";
				if(file_exists($imagepath."easytopmenu".$arr_menu_list[$i].".gif")) echo " value=\"easytopmenu".$arr_menu_list[$i].".gif\"";
				echo ">\n";
				echo "<tr>\n";
				echo "	<TD colspan=\"5\" background=\"images/table_con_line.gif\"></TD>\n";
				echo "</tr>\n";
			}
			$menu_all=str_replace(" ","",$menu_all);
			$menu_all=substr($menu_all,1);
			$arr_menu_all=explode(",",$menu_all);
			for($i=0;$i<count($arr_menu_all);$i++) {
				$j++;
				echo "<tr>\n";
				echo "	<TD class=\"td_con2\" align=center>".$j."</td>\n";
				echo "	<TD class=\"td_con1\">".$menu_all_name[$arr_menu_all[$i]]."</td>\n";
				echo "	<TD class=\"td_con1\"><input type=file name=\"menuimg".$arr_menu_all[$i]."\" size=30 style=\"width:99%\" class=\"input\"></td>\n";
				echo "	<TD class=\"td_con1\"><input type=text name=\"menulink".$arr_menu_all[$i]."\" value=\"".$menu_all_url[$arr_menu_all[$i]]."\" size=40";
				if($arr_menu_all[$i]<=15) echo " readonly style=\"BACKGROUND: #f4f4f4; COLOR: #555555\"";
				echo " style=\"width:99%\" class=\"input\"></td>\n";
				echo "	<TD class=\"td_con1\" align=center><input type=checkbox name=\"display".$j."\" value=\"".$arr_menu_all[$i]."\" style=\"BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none\"></td>\n";
				echo "</tr>\n";
				echo "<input type=hidden name=vmenuimg".$arr_menu_all[$i]."";
				if(file_exists($imagepath."easytopmenu".$arr_menu_all[$i].".gif")) echo " value=\"easytopmenu".$arr_menu_list[$i].".gif\"";
				echo ">\n";
				echo "<tr>\n";
				echo "	<TD colspan=\"5\" background=\"images/table_con_line.gif\"></TD>\n";
				echo "</tr>\n";
			}
?>
				<TR>
					<TD colspan=5 background="images/table_top_line.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td align="center" height="25">
				<table cellpadding="0" cellspacing="0" width="100%" style="padding-top:4pt; padding-bottom:4pt;">
				<tr>
					<td style="letter-spacing:-0.5pt;">&nbsp;<?if($_shopdata->top_type!="tope") {?><input type=checkbox name=okdesign value="Y" style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none;"> <span class="font_orange"><b>Easy �������� ���θ��� ��ٷ� �ݿ��մϴ�.(���ø�, ���������� ��� ������)</b> �̹� �������� ���� üũ�ڽ� �������� �ʽ��ϴ�.<br>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;* ������� �� ������ ���� - [�����ϱ�] Ŭ���ϸ� ���θ��� ��ٷ� �ݿ��˴ϴ�.<br>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;* �̻���� �� ������ ���� - üũ�ڽ��� üũ���� ���� ���¿��� [�����ϱ�] Ŭ���ϸ� ���常 �˴ϴ�.</span><?}?></TD>
				</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td align="center"><hr size="1" color="#F3F3F3"></td>
			</tr>
			<tr>
				<td align="center"><a href="javascript:CheckForm('update');"><img src="images/botteon_save.gif" width="113" height="38" border="0" vspace="7"></a></td>
			</tr>
			</form>
			<tr><td height=20></td></tr>
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
					<TD COLSPAN=3 width="100%" valign="top"  style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;" class="menual_bg">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td width="100%"><span class="font_dotline">Easy ������ ���� ���</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td width="100%" class="space_top" style="letter-spacing:-0.5pt;">- ���ø����� ���� : �������ø� ����(Easy ������, ������������ ��� �����ǰ� ���õ� ���ø��� ������� ����)</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td width="100%" class="space_top" style="letter-spacing:-0.5pt;">- �������������� ���� : <a href="javascript:parent.topframe.GoMenu(2,'design_option.php');"><span class="font_blue">�����ΰ��� > ��FTP ��  �������� ���� > ���������� ���뼱��</span></a> �޴�����<br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>&nbsp;&nbsp;&nbsp;</b>[���+���� ���� ����][��ܸ� ����] ���� - ���������� �������� ����˴ϴ�.<br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[�������] ���� - ������� ���ø����� ����˴ϴ�.
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