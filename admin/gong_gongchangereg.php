<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "go-3";
$MenuCode = "gong";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$imagepath=$Dir.DataDir."shopimages/gonggu/";

$mode=$_POST["mode"];
$gong_seq=$_POST["gong_seq"];

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

$gong_name=$_POST["gong_name"];
$production=$_POST["production"];
$specialadd=$_POST["specialadd"];
$receipt_end=$_POST["receipt_end"];
$origin_price=$_POST["origin_price"];
$start_price=$_POST["start_price"];
$quantity=$_POST["quantity"];
$down_price=$_POST["down_price"];
$mini_price=$_POST["mini_price"];
$sel_count=$_POST["sel_count"];
$count=$_POST["count"];
$sel_deli_money=$_POST["sel_deli_money"];
$deli_money=$_POST["deli_money"];
$content=$_POST["content"];
$image1=$_FILES["image1"];
$image2=$_FILES["image2"];
$image3=$_FILES["image3"];

if($sel_count==0) $count=1;
if(strlen($count)==0 || $count==0) $count=1;

if(strlen($sel_deli_money)==0) {
	$deli_money="NULL";
} else if(strlen($sel_deli_money)>0 && $sel_deli_money==0) {
	$deli_money="0";
}

if(strlen($gong_seq)>0) {
	$sql = "SELECT * FROM tblgonginfo WHERE gong_seq='".$gong_seq."' ";
	$result=mysql_query($sql,get_db_conn());
	$data=mysql_fetch_object($result);
	mysql_free_result($result);
	if(!$data) {
		$onload="<script>alert(\"�ش� �������� ������ �������� �ʽ��ϴ�.\");</script>";
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
		$onload="<script>alert(\"�������� ���۰��� �Է��� �߸��Ǿ����ϴ�.\");</script>";
	}
	if(!$onload) {
		if($end_date<date("YmdHis")) {
			$onload="<script>alert(\"�������� ������ ������ �߸��Ǿ����ϴ�.\\n\\n�������� �������� ����ð����� Ŀ���մϴ�.\");</script>";
		}
	}
	if(!$onload) {
		$filesize=$image1[size]+$image2[size]+$image3[size];
		if($filesize>307200) {
			$onload="<script>alert(\"�������� �� �̹��� �뷮�� 300KB���Ϸ� ����� �����մϴ�\");</script>";
		}
	}
	if(!$onload) {
		$files=array(&$image1,&$image2,&$image3);
		$oldfiles=array(&$data->image1,&$data->image2,&$data->image3);
		unset($in_image);
		for($i=0;$i<3;$i++) {
			if($mode=="modify") {
				if(strlen($oldfiles[$i])>0 && file_exists($imagepath.$oldfiles[$i]) && strlen($files[$i][name])>0) {
					unlink($imagepath.$oldfiles[$i]);
				}
			}
			if(strlen($files[$i][name])>0 && file_exists($files[$i][tmp_name])) {
				$ext = strtolower(substr($files[$i][name],strlen($files[$i][name])-3,3));
				if($ext=="gif" || $ext=="jpg") {
					$in_image[$i] = ($i+1)."_".date("YmdHis",$CurrentTime).".".$ext;
					if($i==2) {
						$size=getimageSize($files[$i][tmp_name]);
						$imgwidth=$size[0];
						$imgheight=$size[1];
						$imgtype=$size[2];
						$maxsize=120;
						$makesize=100;
						if($imgwidth>$maxsize || $imgheight>$maxsize) {
							if($imgtype==1)			$img = ImageCreateFromGif($files[$i][tmp_name]);
							else if($imgtype==2)	$img = ImageCreateFromJpeg($files[$i][tmp_name]);
							else if($imgtype==3)	$img = ImageCreateFromPng($files[$i][tmp_name]);
							if($imgwidth>=$imgheight) {
								$small_width=$makesize; 
								$small_height=($imgheight*$makesize)/$imgwidth;
							} else if($imgwidth<$imgheight) {
								$small_width=($imgwidth*$makesize)/$imgheight; $small_height=$makesize;
							}

							if($imgtype==1) {
								$img2=ImageCreate($small_width,$small_height); // GIF�ϰ��
								ImageCopyResized($img2,$img,0,0,0,0,$small_width,$small_height,$imgwidth,$imgheight);
								imageGIF($img2,$files[$i][tmp_name]);
							} else if($imgtype==2) {
								$img2=ImageCreateTrueColor($small_width,$small_height); // JPG�ϰ��
								imagecopyresampled($img2,$img,0,0,0,0,$small_width,$small_height,$imgwidth,$imgheight);
								imageJPEG($img2,$files[$i][tmp_name],90);
							} else {
								$im2=ImageCreateTrueColor($small_width,$small_height); // PNG�ϰ��
								imagecopyresampled($img2,$img,0,0,0,0,$small_width,$small_height,$imgwidth,$imgheight);
								imagePNG($img2,$files[$i][tmp_name]);
							}
							ImageDestroy($img);
							ImageDestroy($img2);
						}
					}
					move_uploaded_file($files[$i][tmp_name],$imagepath.$in_image[$i]);
					chmod($imagepath.$in_image[$i],0666);
				} else {
					$in_image[$i]=$oldfiles[$i];
				}
			} else {
				$in_image[$i]=$oldfiles[$i];
			}
		}
		unset($qry);
		unset($qry2);
		if($mode=="insert" && strlen($gong_name)>0) {
			$sql = "SELECT MAX(gong_seq)+1 as seq FROM tblgonginfo ";
			$result=mysql_query($sql,get_db_conn());
			$row=mysql_fetch_object($result);
			mysql_free_result($result);
			if($row->seq>0) {
				$in_gong_seq=$row->seq;
			} else {
				$in_gong_seq=1;
			}
			$qry = "INSERT tblgonginfo SET ";
			$qry.= "gong_seq	= '".$in_gong_seq."', ";

			$msg="�������� ����� �Ϸ�Ǿ����ϴ�.";
		} else if($mode=="modify" && strlen($gong_seq)>0) {
			$qry = "UPDATE tblgonginfo SET ";
			$qry2 = "WHERE gong_seq='".$gong_seq."' ";
			$msg="�������� ������ �Ϸ�Ǿ����ϴ�.";
			$data->image1=$in_image[0];
			$data->image2=$in_image[1];
			$data->image3=$in_image[2];
		}
		$data->gong_name=$gong_name;
		$data->production=$production;
		$data->specialadd=$specialadd;
		$data->receipt_end=$receipt_end;
		$data->origin_price=$origin_price;
		$data->start_price=$start_price;
		$data->quantity=$quantity;
		$data->down_price=$down_price;
		$data->mini_price=$mini_price;
		$data->count=$count;
		$data->deli_money=($deli_money=="NULL"?"":$deli_money);
		$data->content=$content;
		$sql = $qry." ";
		$sql.= "start_date		= '".$start_date."', ";
		$sql.= "end_date		= '".$end_date."', ";
		$sql.= "gong_name		= '".$gong_name."', ";
		$sql.= "production		= '".$production."', ";
		$sql.= "specialadd		= '".$specialadd."', ";
		$sql.= "receipt_end		= '".$receipt_end."', ";
		$sql.= "origin_price	= '".$origin_price."', ";
		$sql.= "start_price		= '".$start_price."', ";
		$sql.= "quantity		= '".$quantity."', ";
		$sql.= "down_price		= '".$down_price."', ";
		$sql.= "mini_price		= '".$mini_price."', ";
		$sql.= "count			= '".$count."', ";
		$sql.= "deli_money		= ".$deli_money.", ";
		$sql.= "image1			= '".$in_image[0]."', ";
		$sql.= "image2			= '".$in_image[1]."', ";
		$sql.= "image3			= '".$in_image[2]."', ";
		$sql.= "content			= '".$content."' ";
		$sql.= $qry2;
		$update=mysql_query($sql,get_db_conn());
		if($update) {
			$start_date1=date("Y-m-d",$CurrentTime);
			$start_date2=date("H",$CurrentTime);
			$start_date3=date("i",$CurrentTime);
			$end_date1=date("Y-m-d",($CurrentTime+(60*60*24)));
			$end_date2=date("H",$CurrentTime);
			$end_date3=date("i",$CurrentTime);

			$onload="<script>alert(\"".$msg."\");</script>";
		} else {
			$onload="<script>alert(\"�������� ����� ������ �߻��Ͽ����ϴ�.\");</script>";
			for($i=0;$i<3;$i++) {
				if(strlen($in_image[$i])>0 && file_exists($imagepath.$in_image[$i])) {
					unlink($imagepath.$in_image[$i]);
				}
			}
		}
	} else {
		$data->gong_name=$gong_name;
		$data->production=$production;
		$data->specialadd=$specialadd;
		$data->receipt_end=$receipt_end;
		$data->origin_price=$origin_price;
		$data->start_price=$start_price;
		$data->quantity=$quantity;
		$data->down_price=$down_price;
		$data->mini_price=$mini_price;
		$data->count=$count;
		$data->deli_money=$deli_money;
		$data->content=$content;
	}
}

$mode="insert";
if(strlen($data->gong_seq)>0) $mode="modify";

if(strlen($data->count)==0) $data->count=1;

/*
if(strlen($deli_money)==0 || $deli_money=="NULL") {
	$data->deli_money="";
} else if(strlen($deli_money)>0 && $deli_money==0) {
	$data->deli_money=0;
} else {
	$data->deli_money=$deli_money;
}
*/
?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script type="text/javascript" src="calendar.js.php"></script>
<script language="JavaScript">
function CheckForm(form) {
	if(form.gong_name.value.length==0) {
		alert("�������� ��ǰ ������ �Է��ϼ���.");
		form.gong_name.focus();
		return;
	}
	if(!IsNumeric(form.receipt_end.value)) {
		alert("�Ա� �������� ���ڸ� �Է� �����մϴ�.");
		form.receipt_end.focus();
		return;
	}
	if(form.start_price.value.length==0) {
		alert("�������� ���۰����� �Է��ϼ���.");
		form.start_price.focus();
		return;
	}
	if(!IsNumeric(form.start_price.value)) {
		alert("�������� ���۰����� ���ڸ� �Է� �����մϴ�.");
		form.start_price.focus();
		return;
	}
	if(form.start_price.value<100) {
		alert("�������� ���۰����� 100�� �̻��̾�� �մϴ�.");
		form.start_price.focus();
		return;
	}
	if(form.start_price.value.substring(form.start_price.value.length-2,form.start_price.value.length)!="00") {
		alert("�������� ���۰����� 100�� ������ �Է��ϼ���.\n\n��) 9100,10500,1100,11800");
		form.start_price.focus();
		return;
	}
	if(form.quantity.value.length==0) {
		alert("�������� ���������� �Է��ϼ���.");
		form.quantity.focus();
		return;
	}
	if(!IsNumeric(form.quantity.value)) {
		alert("�������� ���������� ���ڸ� �Է� �����մϴ�.");
		form.quantity.focus();
		return;
	}
	if(form.down_price.value.length==0) {
		alert("�������� ������ ���Ұ����� �Է��ϼ���.");
		form.down_price.focus();
		return;
	}
	if(!IsNumeric(form.down_price.value)) {
		alert("�������� ������ ���Ұ����� ���ڸ� �Է� �����մϴ�.");
		form.down_price.focus();
		return;
	}
	if(form.down_price.value<=0) {
		alert("�������� ������ ���Ұ����� 0�� �̻��̾�� �մϴ�.");
		form.down_price.focus();
		return;
	}
	if(!IsNumeric(form.mini_price.value)) {
		alert("�������� ���Ѱ����� ���ڸ� �Է� �����մϴ�.");
		form.mini_price.focus();
		return;
	}
	if(form.mini_price.value.length>0 && form.mini_price.value<=0) {
		alert("�������� ���Ѱ����� 0�� �̻��̾�� �մϴ�.");
		form.mini_price.focus();
		return;
	}
	//������������ ó��
	if(form.sel_count[0].checked==true) {
		form.count.value=1;
	} else if(form.count.value==0 || form.count.value.length==0) {
		alert("���Ҵ����� �Է��ϼ���.");
		form.count.focus();
		return;
	} else if(!IsNumeric(form.count.value)) {
		alert("���Ҵ����� ���ڸ� �Է� �����մϴ�.");
		form.count.focus();
		return;
	} else if(form.count.value<2) {
		alert("���Ҵ����� 2�̻� �Է��ϼž� �մϴ�.");
		form.count.focus();
		return;
	}

	if(form.sel_deli_money[2].checked==true) {
		if(form.deli_money.value.length==0) {
			alert("��۷Ḧ �Է��ϼ���.");
			form.deli_money.focus();
			return;
		}
		if(!IsNumeric(form.deli_money.value)) {
			alert("��۷�� ���ڸ� �Է� �����մϴ�.");
			form.deli_money.focus();
			return;
		}
		if(form.deli_money.value<=0) {
			alert("��۷�� 0�� �̻��̾�� �մϴ�.");
			form.deli_money.focus();
			return;
		}
	}
	if(form.content.value.length==0) {
		alert("�������� �󼼳����� �Է��ϼ���.");
		form.content.focus();
		return;
	}
	temp=form.down_price.value*(form.quantity.value/form.count.value);
	temp=form.start_price.value-temp;
	if(temp<0) temp=0;
	if(form.mini_price.value.length==0 || temp>form.mini_price.value) {
		if(!confirm("�������� ���Ѱ����� �ڵ����� ����Ͻðڽ��ϱ�?\n\n�ڵ� ���� ���Ѱ��� : "+temp+"��")) {
			form.mini_price.focus();
			return;
		} else {
			document.form1.mini_price.value=temp;
		}
	}
	if(form.receipt_end.value.length==0) {
		if(!confirm("�Ա� ������ ������ �⺻ 3�Ϸ� �����Ͻðڽ��ϱ�?")) {
			form.receipt_end.focus();
			return;
		} else {
			form.receipt_end.value.value=3;
		}
	}

	document.form1.submit();
}

function chk_deli_money(disabled,color) {
	document.form1.deli_money.disabled=disabled;
	document.form1.deli_money.style.background=color;
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
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ����/��� &gt; �������Ű��� &gt; <span class="2depth_select">���ݺ����� ���� ���/����</span></td>
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
			<input type=hidden name=gong_seq value="<?=$gong_seq?>">
			<tr><td height="8"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/gong_gongchangereg_title.gif" ALT=""></TD>
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
					<TD width="100%" class="notice_blue">�������� ��ǰ�� ���/�����Ͻ� �� �ֽ��ϴ�.(��ϵ� �������� ��ǰ ������ ��ϰ��� �������� ��ǰ���� Ŭ���Ͻø� ���� �����մϴ�.)</TD>
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
					<TD><IMG SRC="images/gong_gongchangereg_stitle1.gif" WIDTH="232" HEIGHT=31 ALT=""></TD>
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
				<col width=170></col>
				<col width=></col>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">��ǰ��</TD>
					<TD class="td_con1"><INPUT class=input maxLength=100 size=70 name=gong_name value="<?=$data->gong_name?>"></TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">������<br></TD>
					<TD class="td_con1"><INPUT class=input maxLength=20 size=30 name=production value="<?=$data->production?>"></TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">Ư��ǥ��</TD>
					<TD class="td_con1"><INPUT class=input maxLength=100 size=50 name=specialadd value="<?=$data->specialadd?>">  <FONT class=font_orange>ex) ���� BLUE, ������ XL</FONT></TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�������� ������</TD>
					<TD class="td_con1">
					<INPUT class="input_selected" style="text-align:center;" onfocus=this.blur(); onclick=Calendar(this) size=15 value="<?=$start_date1?>" name=start_date1> 
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
				</tr>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�������� ������</TD>
					<TD class="td_con1">
					<INPUT class="input_selected" style="text-align:center;" onfocus=this.blur(); onclick=Calendar(this) size=15 value="<?=$end_date1?>" name=end_date1> 
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
				</tr>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�Ա� ������</TD>
					<TD class="td_con1">�������� ���� �� <INPUT class=input onkeyup=strnumkeyup(this) style="TEXT-ALIGN: right" maxLength="10" size="10" name=receipt_end value="<?=($data->receipt_end!=0?$data->receipt_end:"")?>"> �� ���� �Ա�  <FONT class=font_orange>�����Է½� �⺻ 3�Ϸ� ����</FONT></TD>
				</tr>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">���߰���</TD>
					<TD class="td_con1"><INPUT class=input onkeyup=strnumkeyup(this) style="TEXT-ALIGN: right" maxLength=10 size=10 name=origin_price value="<?=$data->origin_price?>"> ��  <FONT class=font_orange>ex) 60000</FONT></TD>
				</tr>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">���۰���</TD>
					<TD class="td_con1"><INPUT class=input onkeyup=strnumkeyup(this) style="TEXT-ALIGN: right" maxLength=10 size=10 name=start_price value="<?=$data->start_price?>"> ��  <FONT class=font_orange>ex) 30000</FONT></TD>
				</tr>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">��������</TD>
					<TD class="td_con1"><INPUT class=input onkeyup=strnumkeyup(this) style="TEXT-ALIGN: right" maxLength="10" size="10" name=quantity value="<?=$data->quantity?>"> �� <FONT class=font_orange>ex) 100</FONT></TD>
				</tr>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">���� ������ ���Ұ���</TD>
					<TD class="td_con1"><INPUT class=input onkeyup=strnumkeyup(this) style="TEXT-ALIGN: right" maxLength="10" size=10 name=down_price value="<?=$data->down_price?>"> ��  <FONT class=font_orange>ex) 1000</FONT></TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">��������</TD>
					<TD class="td_con1"><INPUT class=input onkeyup=strnumkeyup(this) style="TEXT-ALIGN: right" maxLength=10 size=10 name=mini_price value="<?=($data->mini_price!=0?$data->mini_price:"")?>"> ��&nbsp;&nbsp;<span class="font_ornage">* ���Է½� �ڵ� ���Ǿ� �Էµ˴ϴ�.</span></TD>
				</tr>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�������� ����</TD>
					<TD class="td_con1">
					<INPUT class=input id=idx_count0 onclick="this.form.count.disabled=true;this.form.count.style.background='#f4f4f4'" type=radio value=0 name=sel_count <?if($data->count==1)echo"checked";?>><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_count0>�����̵��</LABEL><br>
					<INPUT class=input id=idx_count1 onclick="this.form.count.disabled=false;this.form.count.style.background='#ffffff'" type=radio value=1 name=sel_count <?if($data->count>1)echo"checked";?>><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand; TEXT-DECORATION: none" onmouseout="style.textDecoration='none'" for=idx_count1>��ܽ� =&gt; ���Ҵ���</LABEL> : <INPUT class=input onkeyup=strnumkeyup(this) style="TEXT-ALIGN: right;<?if($data->count==1)echo"BACKGROUND:#f4f4f4";?>" maxLength="10" size="10" name=count <?if($data->count>1)echo"value=\"".$data->count."\""; else echo"disabled";?>> ��&nbsp;&nbsp;&nbsp;&nbsp;<FONT class=font_orange><span class="font_orange">* ���Ҵ����� �Է��� ���� ������ ������ �����մϴ�.</span></FONT>
					</TD>
				</tr>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">��۷�</TD>
					<TD class="td_con1">
						
						<INPUT class=input id=idx_deli_money0 type=radio value="" name=sel_deli_money <?if(strlen($data->deli_money)==0) echo " checked";?> onclick="chk_deli_money('disabled','#f4f4f4')"><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_deli_money0>����</LABEL> &nbsp;&nbsp;
						<INPUT class=input id=idx_deli_money1 type=radio value=0 name=sel_deli_money <?if(strlen($data->deli_money)>0 && $data->deli_money==0) echo " checked";?> onclick="chk_deli_money('disabled','#f4f4f4')"><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_deli_money1>����</LABEL> &nbsp;&nbsp;
						<INPUT class=input id=idx_deli_money2 type=radio value=1 name=sel_deli_money <?if($data->deli_money>0) echo " checked";?> onclick="chk_deli_money('','#ffffff')"><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand; TEXT-DECORATION: none" onmouseout="style.textDecoration='none'" for=idx_deli_money2>��۷�</LABEL> : 
						<INPUT class=input maxLength="10" size="10" name=deli_money <?if($data->deli_money>0)echo"value=".$data->deli_money."";else echo"disabled";?> onkeyup=strnumkeyup(this) style="TEXT-ALIGN: right;<?if($data->deli_money<=0)echo"BACKGROUND:#f4f4f4;";?>"> ��
					</TD>
				</tr>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�����̹���</TD>
					<TD class="td_con1">
					<INPUT class=input style=width:80% type=file size=70 name=image1>&nbsp;&nbsp;&nbsp;&nbsp;<span class="font_orange" style="font-size:11px;letter-spacing:-0.5pt;">* GIF, JPG�� ����</span>
<?
					if(strlen($data->image1)>0) {
						if(file_exists($imagepath.$data->image1)==true) {
							echo "<br><img src=\"".$imagepath.$data->image1."\" border=0 width=200 height=100 style=\"border:#e1e1e1 solid 1px\">";
						} else {
							echo "<br>��ϵ� �̹����� �����ϴ�.";
						}
					}
?>
					</TD>
				</tr>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�߰��̹���</TD>
					<TD class="td_con1">
					<INPUT class=input style=width:80% type=file size=70 name=image2>&nbsp;&nbsp;&nbsp;&nbsp;<span class="font_orange" style="font-size:11px;letter-spacing:-0.5pt;">* GIF, JPG�� ����</span>
<?
					if(strlen($data->image2)>0) {
						if(file_exists($imagepath.$data->image2)==true) {
							echo "<br><img src=\"".$imagepath.$data->image2."\" border=0 width=150 height=80 style=\"border:#e1e1e1 solid 1px\">";
						} else {
							echo "<br>��ϵ� �̹����� �����ϴ�.";
						}
					}
?>
					</TD>
				</tr>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�����̹���</TD>
					<TD class="td_con1">
					<INPUT class=input style=width:80% type=file size=70 name=image3>&nbsp;&nbsp;&nbsp;&nbsp;<span class="font_orange" style="font-size:11px;letter-spacing:-0.5pt;">* GIF, JPG�� ����</span>
<?
					if(strlen($data->image3)>0) {
						if(file_exists($imagepath.$data->image3)==true) {
							echo "<br><img src=\"".$imagepath.$data->image3."\" border=0 width=100 height=60 style=\"border:#e1e1e1 solid 1px\">";
						} else {
							echo "<br>��ϵ� �̹����� �����ϴ�.";
						}
					}
?>
					</TD>
				</tr>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�󼼳���<br>&nbsp;&nbsp;<span style="font-size:11px;letter-spacing:-0.5pt;">(��ǰ����, ���/��ȯ/ȯ�� ��)</span></TD>
					<TD class="td_con1"><TEXTAREA style="WIDTH: 100%; HEIGHT: 150px" name=content wrap=off class="textarea"><?=$data->content?></TEXTAREA></TD>
				</tr>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
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
						<td><span class="font_dotline">��� ��� ����</span></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- �������� ���۰����� 100�� �̻���� �����մϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- �������� ��ǰ ������ <b>���۰���</b>���� �����ؼ� �������� �����ڰ� �ö����� <b>������ ���Ұ���</b> ������ ������ �������ϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- �����������Ŀ��� <b>�����̵��</b>�� ������ 1�� ������, <b>��ܽ�</b>�� ������ �ο��� ������ ������ �������ϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- �̹���(�����̹���+�߰��̹���+�����̹���) ���ε� �뷮�� �ִ� 300KB ���ϸ� �����մϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- ���� �� ���� �Է½� �޸�(,)�� �����ϰ� �Է��� �ּ���.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- ��ϵ� �������� ��ǰ �� �����ڰ� �ִ� �������� ��ǰ�� ������ �� �����ϴ�.</td>
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