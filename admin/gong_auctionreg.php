<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "go-2";
$MenuCode = "gong";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$imagepath=$Dir.DataDir."shopimages/auction/";

$mode=$_POST["mode"];
$auction_seq=$_POST["auction_seq"];
$auction_date=$_POST["auction_date"];

$CurrentTime = time();
$start_date1=$_POST["start_date1"];
$start_date2=$_POST["start_date2"];
$start_date3=$_POST["start_date3"];

$end_date1=$_POST["end_date1"];
$end_date2=$_POST["end_date2"];
$end_date3=$_POST["end_date3"];

$start_date1=$start_date1?$start_date1:date("Y-m-d",$CurrentTime);
$start_date2=$start_date2?$start_date2:date("H",$CurrentTime);
$start_date3=$start_date3?$start_date3:date("i",$CurrentTime);

$end_date1=$end_date1?$end_date1:date("Y-m-d",($CurrentTime+(60*60*24)));
$end_date2=$end_date2?$end_date2:date("H",$CurrentTime);
$end_date3=$end_date3?$end_date3:date("i",$CurrentTime);

$start_date=str_replace("-","",$start_date1).$start_date2.$start_date3."00";
$end_date=str_replace("-","",$end_date1).$end_date2.$end_date3."59";

$auction_name=$_POST["auction_name"];
$start_price=(int)$_POST["start_price"];
$sel_mini_unit=(int)$_POST["sel_mini_unit"];
$mini_unit=(int)$_POST["mini_unit"];
$deli_area=$_POST["deli_area"];
$used_period=$_POST["used_period"];
$content=$_POST["content"];
$product_image=$_FILES["product_image"];

if($sel_mini_unit==0) {
	$mini_unit=0;
}

if(strlen($auction_seq)>0 && strlen($auction_date)>0) {
	$sql = "SELECT * FROM tblauctioninfo ";
	$sql.= "WHERE auction_seq='".$auction_seq."' AND start_date='".$auction_date."' ";
	$result=mysql_query($sql,get_db_conn());
	$data=mysql_fetch_object($result);
	mysql_free_result($result);
	if(!$data) {
		$onload="<script>alert(\"�ش� ��Ż�ǰ ������ �������� �ʽ��ϴ�.\");</script>";
		$mode="";
	} else {
		if($mode!="modify") {
			$start_date1=substr($data->start_date,0,4)."-".substr($data->start_date,4,2)."-".substr($data->start_date,6,2);
			$start_date2=substr($data->start_date,8,2);
			$start_date3=substr($data->start_date,10,2);
			$end_date1=substr($data->end_date,0,4)."-".substr($data->end_date,4,2)."-".substr($data->end_date,6,2);
			$end_date2=substr($data->end_date,8,2);
			$end_date3=substr($data->end_date,10,2);
		}
	}
}

if($mode=="insert" || $mode=="modify") {
	if($start_price<100 || substr($start_price,-2)!="00") {
		$onload="<script>alert(\"��� ���۰��� �Է��� �߸��Ǿ����ϴ�.\");</script>";
	}
	if($mini_unit>0 && strlen($onload)==0) {
		if($mini_unit<100 || substr($mini_unit,-2)!="00") {
			$onload="<script>alert(\"���� �ּҴ��� ���� �Է��� �߸��Ǿ����ϴ�.\");</script>";
		}
	}
	if(strlen($onload)==0) {
		if(strlen($product_image[name])>0 && file_exists($product_image[tmp_name]) && (
               strtolower(substr($product_image[name],strlen($product_image[name])-3,3))=="gif" ||
               strtolower(substr($product_image[name],strlen($product_image[name])-3,3))=="jpg")) {
			$imagename=date("YmdHis",$CurrentTime).".".substr($product_image[name],-3);
			$filesize = $product_image[size];
		} else {
			$imagename=$data->product_image;
		}
		if($filesize<102400) {
			if($end_date>date("YmdHis")) {
				if($mode=="insert") {
					$sql = "SELECT MAX(auction_seq)+1 as seq FROM tblauctioninfo ";
					$sql.= "WHERE start_date LIKE '".substr($start_date,0,8)."%' ";
					$result=mysql_query($sql,get_db_conn());
					$row=mysql_fetch_object($result);
					mysql_free_result($result);
					if($row->seq>0) {
						$in_auction_seq=$row->seq;
					} else {
						$in_auction_seq=1;
					}
					$sql = "INSERT tblauctioninfo SET ";
					$sql.= "auction_seq		= '".$in_auction_seq."', ";
					$sql.= "start_date		= '".$start_date."', ";
					$sql.= "end_date		= '".$end_date."', ";
					$sql.= "auction_name	= '".$auction_name."', ";
					$sql.= "start_price		= '".$start_price."', ";
					$sql.= "last_price		= '".$start_price."', ";
					$sql.= "mini_unit		= '".$mini_unit."', ";
					$sql.= "deli_area		= '".$deli_area."', ";
					$sql.= "used_period		= '".$used_period."', ";
					$sql.= "product_image	= '".$imagename."', ";
					$sql.= "content			= '".$content."' ";
					mysql_query($sql,get_db_conn());
					$onload="<script>alert(\"��Ż�ǰ ����� �Ϸ�Ǿ����ϴ�.\");</script>";
					$start_date1=date("Y-m-d",$CurrentTime);
					$start_date2=date("H",$CurrentTime);
					$start_date3=date("i",$CurrentTime);
					$end_date1=date("Y-m-d",($CurrentTime+(60*60*24)));
					$end_date2=date("H",$CurrentTime);
					$end_date3=date("i",$CurrentTime);
				} else if($mode=="modify") {
					if($start_date==$auction_date) {
						$in_auction_seq=$auction_seq;
					} else {
						$sql = "SELECT MAX(auction_seq)+1 as seq FROM tblauctioninfo ";
						$sql.= "WHERE start_date LIKE '".substr($start_date,0,8)."%' ";
						$result=mysql_query($sql,get_db_conn());
						$row=mysql_fetch_object($result);
						mysql_free_result($result);
						if($row->seq>0) {
							$in_auction_seq=$row->seq;
						} else {
							$in_auction_seq=1;
						}
					}
					$sql = "UPDATE tblauctioninfo SET ";
					$sql.= "auction_seq		= '".$in_auction_seq."', ";
					$sql.= "start_date		= '".$start_date."', ";
					$sql.= "end_date		= '".$end_date."', ";
					$sql.= "auction_name	= '".$auction_name."', ";
					$sql.= "start_price		= '".$start_price."', ";
					$sql.= "mini_unit		= '".$mini_unit."', ";
					$sql.= "deli_area		= '".$deli_area."', ";
					$sql.= "used_period		= '".$used_period."', ";
					$sql.= "product_image	= '".$imagename."', ";
					$sql.= "content			= '".$content."' ";
					$sql.= "WHERE auction_seq	= '".$auction_seq."' AND start_date='".$auction_date."' ";
					mysql_query($sql,get_db_conn());
					if(strlen($data->product_image)>0 && file_exists($product_image[tmp_name]) && file_exists($imagepath.$data->product_image)) {
						unlink($imagepath.$data->product_image);
					}
					$auction_seq=$in_auction_seq;
					$auction_date=$start_date;
					$data->auction_name=$auction_name;
					$data->start_price=$start_price;
					$data->mini_unit=$mini_unit;
					$data->deli_area=$deli_area;
					$data->used_period=$used_period;
					$data->content=$content;
					$data->product_image = $imagename;

					$onload="<script>alert(\"��Ż�ǰ ������ �Ϸ�Ǿ����ϴ�.\");</script>";
				}
				if(file_exists($product_image[tmp_name]) && (
               strtolower(substr($imagename,strlen($imagename)-3,3))=="gif" ||
               strtolower(substr($imagename,strlen($imagename)-3,3))=="jpg")) {
					move_uploaded_file($product_image[tmp_name],$imagepath.$imagename);
					chmod($imagepath.$imagename,0666);
				}
			} else {
				$onload="<script>alert(\"��� ������ ������ �߸��Ǿ����ϴ�.\\n\\n��� �������� ����ð����� Ŀ���մϴ�.\");</script>";
				$data->auction_name=$auction_name;
				$data->start_price=$start_price;
				$data->mini_unit=$mini_unit;
				$data->deli_area=$deli_area;
				$data->used_period=$used_period;
				$data->content=$content;
			}
		} else {
			$onload="<script>alert(\"��Ż�ǰ ���� ���� �뷮�� 150KB���Ϸ� ����� �����մϴ�.\");</script>";
			$data->auction_name=$auction_name;
			$data->start_price=$start_price;
			$data->mini_unit=$mini_unit;
			$data->deli_area=$deli_area;
			$data->used_period=$used_period;
			$data->content=$content;
		}
	} else {
		$data->auction_name=$auction_name;
		$data->start_price=$start_price;
		$data->mini_unit=$mini_unit;
		$data->deli_area=$deli_area;
		$data->used_period=$used_period;
		$data->content=$content;
	}
}

$mode="insert";
if(strlen($data->auction_seq)>0) $mode="modify";

?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script type="text/javascript" src="calendar.js.php"></script>
<script language="JavaScript">
function CheckForm(form) {
	if(form.auction_name.value.length==0) {
		alert("��Ż�ǰ ������ �Է��ϼ���.");
		form.auction_name.focus();
		return;
	}
	if(form.start_price.value.length==0) {
		alert("��� ���۰����� �Է��ϼ���.");
		form.start_price.focus();
		return;
	}
	if(!IsNumeric(form.start_price.value)) {
		alert("��� ���۰����� ���ڸ� �Է� �����մϴ�.");
		form.start_price.focus();
		return;
	}
	if(form.start_price.value<100) {
		alert("��� ���۰����� 100�� �̻��̾�� �մϴ�.");
		form.start_price.focus();
		return;
	}
	if(form.start_price.value.substring(form.start_price.value.length-2,form.start_price.value.length)!="00") {
		alert("��� ���۰����� 100�� ������ �Է��ϼ���.\n\n��) 100,500,1100,1800");
		form.start_price.focus();
		return;
	}
	if(form.sel_mini_unit[1].checked==true) {
		if(form.mini_unit.value.length==0) {
			alert("���� �ּҴ��� ������ �Է��ϼ���.");
			form.mini_unit.focus();
			return;
		}
		if(!IsNumeric(form.mini_unit.value)) {
			alert("���� �ּҴ��� ������ ���ڸ� �Է� �����մϴ�.");
			form.mini_unit.focus();
			return;
		}
		if(form.mini_unit.value<100) {
			alert("���� �ּҴ��� ������ 100�� �̻��̾�� �մϴ�.");
			form.mini_unit.focus();
			return;
		}
		if(form.mini_unit.value.substring(form.mini_unit.value.length-2,form.mini_unit.value.length)!="00") {
			alert("���� �ּҴ��� ������ 100�� ������ �Է��ϼ���.\n\n��) 100,500,1000,1500");
			form.mini_unit.focus();
			return;
		}
	}
	if(form.content.value.length==0) {
		alert("��� �󼼳����� �Է��ϼ���.");
		form.content.focus();
		return;
	}
	document.form1.submit();
}

function chk_mini_unit(gbn) {
	if (gbn=="0") {
		document.form1.mini_unit.value="";
		document.form1.mini_unit.disabled=true;
	} else if (gbn=="1") {
		document.form1.mini_unit.disabled=false;
		document.form1.mini_unit.focus();
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
			<? include ("menu_gong.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ����/��� &gt; ���θ� ��� ���� &gt; <span class="2depth_select">��Ż�ǰ ���/����</span></td>
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
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post enctype="multipart/form-data">
			<input type=hidden name=mode value="<?=$mode?>">
			<input type=hidden name=auction_seq value="<?=$auction_seq?>">
			<input type=hidden name=auction_date value="<?=$auction_date?>">
			<tr><td height="8"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/gong_auctionreg_title.gif"  ALT=""></TD>
					</tr><tr>
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
					<TD width="100%" class="notice_blue">��� ��ǰ�� ��� �� ������ �Ͻ� �� �ֽ��ϴ�. ������ ��� ��Ż�ǰ �������� ��ǰ���� Ŭ���Ͻø� ������ �����մϴ�.</TD>
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
					<TD><IMG SRC="images/gong_auctionreg_stitle1.gif" WIDTH="187" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=3></td></tr>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<col width=140></col>
				<col width=></col>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">��� ��ǰ��</TD>
					<TD class="td_con1"><INPUT class=input style=width:100% maxLength=100 size=70 name=auction_name value="<?=$data->auction_name?>"></TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">��� ������</TD>
					<TD class="td_con1">
					<INPUT class="input_selected" style="TEXT-ALIGN: center" onfocus=this.blur(); onclick=Calendar(this) size=15 value="<?=$start_date1?>" name=start_date1> 
					<SELECT name=start_date2 class="select">
<?
					for($i=0;$i<=23;$i++) {
						$val=substr("0".$i,-2);
						if($i<=5) {
							echo "<option value=\"".$val."\"";
							if($val==$start_date2) {
								echo "selected";
							}
							echo " >���� ".$i."��</option>";
						} else if($i<=11) {
							echo "<option value=\"".$val."\"";
							if($val==$start_date2) {
								echo "selected";
							}
							echo " >���� ".$i."��</option>";
						} else {
							echo "<option value=\"".$val."\"";
							if($val==$start_date2) {
								echo "selected";
							}
							echo " >���� ".$i."��</option>";
						}
					}
?>
					</SELECT>
					 
					<SELECT name=start_date3 class="select">
<?
					for($i=0;$i<=59;$i++) {
						$val=substr("0".$i,-2);
						echo "<option value=\"".$val."\"";
						if($val==$start_date3) {
							echo "selected";
						}
						echo " >".$val."��</option>";
					}
?>
					</SELECT>
					
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">��� ������</TD>
					<TD class="td_con1">
					<INPUT class="input_selected" style="TEXT-ALIGN: center" onfocus=this.blur(); onclick=Calendar(this) size=15 value="<?=$end_date1?>" name=end_date1> 
					<SELECT name=end_date2 class="select">
<?
					for($i=0;$i<=23;$i++) {
						$val=substr("0".$i,-2);
						if($i<=5) {
							echo "<option value=\"".$val."\"";
							if($val==$end_date2) {
								echo "selected";
							}
							echo " >���� ".$i."��</option>";
						} else if($i<=11) {
							echo "<option value=\"".$val."\"";
							if($val==$end_date2) {
								echo "selected";
							}
							echo " >���� ".$i."��</option>";
						} else {
							echo "<option value=\"".$val."\"";
							if($val==$end_date2) {
								echo "selected";
							}
							echo " >���� ".$i."��</option>";
						}
					}
?>
					</SELECT>
					 
					<SELECT name=end_date3 class="select">
<?
					for($i=0;$i<=59;$i++) {
						$val=substr("0".$i,-2);
						echo "<option value=\"".$val."\"";
						if($val==$end_date3) {
							echo "selected";
						}
						echo " >".$val."��</option>";
					}
?>
					</SELECT>
					
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">��� ���۰���</TD>
					<TD class="td_con1"><INPUT class=input onkeyup=strnumkeyup(this) style="TEXT-ALIGN: right" maxLength=10 name=start_price value="<?=$data->start_price?>"> ��  &nbsp;<FONT class=font_orange>ex) 20000 (�޸��� �Է����� ������)</FONT></TD>
				</tr>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">���� �ּҴ��� ����</TD>
					<TD class="td_con1">
					<INPUT class=input id=idx_mini_unit0 onclick="chk_mini_unit('0')" type=radio value=0 name=sel_mini_unit <?if($data->mini_unit==0) echo " checked";?>><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_mini_unit0>�ڵ�</LABEL> &nbsp;
					<INPUT class=input id=idx_mini_unit1 onclick="chk_mini_unit('1')" type=radio value=1 name=sel_mini_unit <?if($data->mini_unit>0) echo " checked";?>><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_mini_unit1>�����Է�</LABEL>
					<INPUT class="input" style="TEXT-ALIGN: right" maxLength=5 size=5 name=mini_unit <?if($data->mini_unit>0)echo"value=".$data->mini_unit."";else echo"disabled";?>> �� &nbsp; <FONT class=font_orange>ex) 1000 (�޸��� �Է����� ������)</FONT></TD>
				</tr>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">��� �������� ����</TD>
					<TD class="td_con1">
					
					<SELECT name=deli_area size="1" class="select">
<?
					$arealist=array("����","����","������","��⵵","������","���","����","��û��","���ֵ�");
					for($i=0;$i<count($arealist);$i++) {
						if($data->deli_area==$arealist[$i]) {
							echo "<option value=\"".$arealist[$i]."\" selected>".$arealist[$i]."</option>\n";
						} else {
							echo "<option value=\"".$arealist[$i]."\">".$arealist[$i]."</option>\n";
						}
					}
?>
					</SELECT>
					<FONT class=font_orange>����� ���������� �����ϼ���.</FONT>
					</TD>
				</tr>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">���Ⱓ</TD>
					<TD class="td_con1"><INPUT class=input maxLength=30 size=30 name=used_period value="<?=$data->used_period?>">  <FONT class=font_orange>����Ż�ǰ�� ���Ⱓ�� �Է��ϼ���.</FONT></TD>
				</tr>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">��Ż�ǰ ����</TD>
					<TD class="td_con1">
					<INPUT class=input style=width:65% type=file size=70 name=product_image>&nbsp;&nbsp;&nbsp;&nbsp;<span class="font_orange" style="font-size:11px;letter-spacing:-0.5pt;">* �̹����� 150KB ������ GIF, JPG�� ����</span>
<?
					if(strlen($data->product_image)>0) {
						if(file_exists($imagepath.$data->product_image)==true) {
							echo "<br><img src=\"".$imagepath.$data->product_image."\" border=0 width=200 height=100 style=\"border:#e1e1e1 solid 1px\">";
						} else {
							echo "<br>��ϵ� ��Ż�ǰ ������ �����ϴ�.";
						}
					}
?>
					</TD>
				</tr>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">��� �󼼳���<br>(��ǰ����,���,��ǰ��)</TD>
					<TD class="td_con1"><TEXTAREA style="WIDTH: 100%; HEIGHT: 150px" name=content wrap=off class="textarea"><?=$data->content?></TEXTAREA></TD>
				</tr>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=10></td></tr>
			<tr>
				<td align="center"><a href="javascript:CheckForm(document.form1);"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a></td>
			</tr>
			<tr><td height=20></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/manual_top1.gif" WIDTH=15 height="45" ALT=""></TD>
					<TD><IMG SRC="images/manual_title.gif" WIDTH=113 height="45" ALT=""></TD>
					<TD width="100%" background="images/manual_bg.gif" height="35"></TD>
					<TD background="images/manual_bg.gif">&nbsp;</TD>
					<td background="images/manual_bg.gif"><IMG SRC="images/manual_top2.gif" WIDTH=18 height="45" ALT=""></td>
				</TR>
				<TR>
					<TD background="images/manual_left1.gif"></TD>
					<TD COLSPAN=3 width="100%" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;" class="menual_bg">
					<table cellpadding="0" cellspacing="0" width="100%">
					<col width=20></col>
					<col width=></col>
					<tr>
						<td align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><span class="font_dotline">��Ż�ǰ ���/����</span></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- ��� ���۰����� 100�� �̻���� �����մϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- ���� �ּҴ��� ���� �����Է��� 100�� �̻���� �����մϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- ���� �ּҴ����� �ڵ����� ���ý� �Ʒ��� ���� ���� �˴ϴ�.<br>
						<b>&nbsp;&nbsp;</b>&nbsp;&nbsp;&nbsp;&nbsp;5,000�� <span class="font_blue">�̸�</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;100��<br>
						<b>&nbsp;&nbsp;</b><b>&nbsp;&nbsp;</b>50,000�� <span class="font_blue">�̸�</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;500��<br>
						<b>&nbsp;&nbsp;</b>&nbsp;100,000�� <span class="font_blue">�̸�</span><b>&nbsp;&nbsp;</b>1,000��<br>
						<b>&nbsp;&nbsp;</b>&nbsp;100,000�� <span class="font_orange">�̻�</span><b>&nbsp;&nbsp;</b>2,000��<br>
						</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- ��� �������� ������ ��������� �������� ������ ���� ������, ��ǰ ���� ����������� ǥ��˴ϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- ���� �� ���� �Է½� �޸�(,)�� �����ϰ� �Է��� �ּ���.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- ��ϵ� ��� ��ǰ �� �����ڰ� �ִ� ��� ��ǰ�� ������ �� �����ϴ�.</td>
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
			</form>
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