<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");
INCLUDE ("access.php");

//����Ʈ ����
$setup[page_num] = 10;
$setup[list_num] = 20;

$block=$_REQUEST["block"];
$block2=$_REQUEST["block2"];
$gotopage=$_REQUEST["gotopage"];
$gotopage2=$_REQUEST["gotopage2"];
$search=$_POST["search"];

if ($block != "") {
	$nowblock = $block;
	$curpage  = $block * $setup[page_num] + $gotopage;
} else {
	$nowblock = 0;
}

if (($gotopage == "") || ($gotopage == 0)) {
	$gotopage = 1;
}

if ($block2 != "") {
	$nowblock2 = $block2;
	$curpage2  = $block2 * $setup[page_num] + $gotopage2;
} else {
	$nowblock2 = 0;
}

if (($gotopage2 == "") || ($gotopage2 == 0)) {
	$gotopage2 = 1;
}
////////////////////////

$type=$_POST["type"];
$coupon_code=$_POST["coupon_code"];
$uid=$_POST["uid"];

if($type=="stop" && strlen($coupon_code)>0) {	//�߱�����
	$sql = "UPDATE tblcouponinfo SET display='N',issue_type='D' ";
	$sql.= "WHERE coupon_code='".$coupon_code."' ";
	$sql.= "AND vender='".$_VenderInfo->getVidx()."' ";
	mysql_query($sql,get_db_conn());
	if(!mysql_errno()) {
		echo "<html></head><body onload=\"alert('�ش� ������ ���ؼ� �߱����� ó���� �Ϸ�Ǿ����ϴ�.\\n\\n���� �߱޵� ������ ��밡���մϴ�.');parent.GoInit('');\"></body></html>";exit;
	} else {
		echo "<html></head><body onload=\"alert('��û�Ͻ� �۾��� ������ �߻��Ͽ����ϴ�.');\"></body></html>";exit;
	}
	exit;
} else if($type=="delete" && strlen($coupon_code)>0) {	//��������
	$sql = "SELECT * FROM tblcouponinfo ";
	$sql.= "WHERE coupon_code='".$coupon_code."' ";
	$sql.= "AND vender='".$_VenderInfo->getVidx()."' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$sql = "DELETE FROM tblcouponinfo ";
		$sql.= "WHERE coupon_code='".$coupon_code."' ";
		$sql.= "AND vender='".$_VenderInfo->getVidx()."' ";
		mysql_query($sql,get_db_conn());

		$sql = "DELETE FROM tblcouponissue ";
		$sql.= "WHERE coupon_code='".$coupon_code."' ";
		mysql_query($sql,get_db_conn());
		if(file_exists($Dir.DataDir."shopimages/etc/COUPON".$coupon_code.".gif")) {
			unlink($Dir.DataDir."shopimages/etc/COUPON".$coupon_code.".gif");
		}
		if(!mysql_errno()) {
			echo "<html></head><body onload=\"alert('�ش� ������ ��� ������ ���� �����Ǿ����ϴ�.');parent.GoInit('');\"></body></html>";exit;
		} else {
			echo "<html></head><body onload=\"alert('��û�Ͻ� �۾��� ������ �߻��Ͽ����ϴ�.');\"></body></html>";exit;
		}
	} else {
		echo "<html></head><body onload=\"alert('�ش� ���������� �������� �ʽ��ϴ�.');\"></body></html>";exit;
	}
	mysql_free_result($result);
	exit;
} else if($type=="issueagain" && strlen($coupon_code)>0 && strlen($uid)>0) {	//ȸ������ �߱��� ���� ��߱�
	$sql = "SELECT * FROM tblcouponinfo ";
	$sql.= "WHERE coupon_code='".$coupon_code."' ";
	$sql.= "AND vender='".$_VenderInfo->getVidx()."' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$sql = "UPDATE tblcouponissue SET used='N' WHERE coupon_code = '".$coupon_code."' AND id = '".$uid."' ";
		mysql_query($sql,get_db_conn());
		if(!mysql_errno()) {
			echo "<html></head><body onload=\"alert('".$uid." ȸ���Բ� �ش� ������ ��߱� �Ǿ����ϴ�.');parent.GoInit('".$coupon_code."');\"></body></html>";exit;
		} else {
			echo "<html></head><body onload=\"alert('��û�Ͻ� �۾��� ������ �߻��Ͽ����ϴ�.');\"></body></html>";exit;
		}
	} else {
		echo "<html></head><body onload=\"alert('�ش� ���������� �������� �ʽ��ϴ�.');\"></body></html>";exit;
	}
	mysql_free_result($result);
	exit;
} else if($type=="issuedelete" && strlen($coupon_code)>0 && strlen($uid)>0) {	//ȸ������ �߱��� ���� ����
	$sql = "SELECT * FROM tblcouponinfo ";
	$sql.= "WHERE coupon_code='".$coupon_code."' ";
	$sql.= "AND vender='".$_VenderInfo->getVidx()."' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$sql = "DELETE FROM tblcouponissue WHERE coupon_code = '".$coupon_code."' AND id = '".$uid."' ";
		mysql_query($sql,get_db_conn());
		if(!mysql_errno()) {
			echo "<html></head><body onload=\"alert('".$uid." ȸ���Կ��� �߱޵� ������ �����Ǿ����ϴ�.');parent.GoInit('".$coupon_code."');\"></body></html>";exit;
		} else {
			echo "<html></head><body onload=\"alert('��û�Ͻ� �۾��� ������ �߻��Ͽ����ϴ�.');\"></body></html>";exit;
		}
	} else {
		echo "<html></head><body onload=\"alert('��û�Ͻ� �۾��� ������ �߻��Ͽ����ϴ�.');\"></body></html>";exit;
	}
	mysql_free_result($result);
	exit;
}

?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function CouponView(code) {
	window.open("about:blank","couponview","width=650,height=650,scrollbars=no");
	document.cform.coupon_code.value=code;
	document.cform.submit();
}

function CouponIssue(code){
	document.form1.coupon_code.value=code;
	document.form1.block2.value="";
	document.form1.gotopage2.value="";
	document.form1.target="";
	document.form1.submit();
}

function CouponStop(code) {
	if(confirm("���� ȸ������ �߱޵� ������ ����� �����մϴ�.\n\n�ش� ���� �߱��� �����Ͻðڽ��ϱ�?")) {
		document.form1.coupon_code.value=code;
		document.form1.type.value="stop";
		document.form1.target="processFrame";
		document.form1.submit();
	}
}

function CouponDelete(code) {
	if(confirm("���� ȸ������ �߱޵� �������� ��� �����˴ϴ�.\n\n�ش� ������ ���� �����Ͻðڽ��ϱ�?")) {
		document.form1.coupon_code.value=code;
		document.form1.type.value="delete";
		document.form1.target="processFrame";
		document.form1.submit();
	}
}

function IssueCouponAgain(code,uid) {
	if(confirm(uid+" ȸ���Կ��� ������ ��߱� �Ͻðڽ��ϱ�?")) {
		document.form1.coupon_code.value=code;
		document.form1.uid.value=uid;
		document.form1.type.value="issueagain";
		document.form1.target="processFrame";
		document.form1.submit();
	}
}

function IssueCouponDelete(code,uid) {
	if(confirm(uid+" ȸ���Կ��� �߱��� ������ �����Ͻðڽ��ϱ�?")) {
		document.form1.coupon_code.value=code;
		document.form1.uid.value=uid;
		document.form1.type.value="issuedelete";
		document.form1.target="processFrame";
		document.form1.submit();
	}
}

function GoPage(block,gotopage) {
	document.form1.type.value = "";
	document.form1.coupon_code.value = "";
	document.form1.uid.value = "";
	document.form1.block.value = block;
	document.form1.gotopage.value = gotopage;
	document.form1.block2.value="";
	document.form1.gotopage2.value="";
	document.form1.target="";
	document.form1.submit();
}

function GoPage2(block,gotopage) {
	document.form1.type.value = "";
	document.form1.uid.value = "";
	document.form1.block2.value = block;
	document.form1.gotopage2.value = gotopage;
	document.form1.target="";
	document.form1.submit();
}

function GoInit(coupon_code) {
	document.form1.type.value = "";
	document.form1.coupon_code.value = coupon_code;
	document.form1.uid.value = "";
	document.form1.target="";
	document.form1.submit();
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
					<td><img src="images/coupon_list_title.gif"></td>
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


						<table cellpadding="10" cellspacing="1" width="100%" bgcolor="#EFEFF2">
							<tr>
								<td  bgcolor="#F5F5F9" style="padding:20px">
									<table border=0 cellpadding=0 cellspacing=0 width=100%>
										<tr>
											<td class="notice_gray" height="20"><img src="images/icon_dot02.gif" border=0 hspace="4">�����ڵ带 Ŭ���Ͻø� �߱޵� ������ ���� �������� ���� �� �ֽ��ϴ�.</td>
										</tr>
										<tr>
											<td class="notice_gray" height="20"><img src="images/icon_dot02.gif" border=0 hspace="4">[��ȸ] ��ư�� Ŭ���Ͻø� �ش� ������ ���� �߱޹��� ȸ�������� ��ȸ�Ͻ� �� �ֽ��ϴ�.</td>
										</tr>
										<tr>
											<td class="notice_gray" height="20"><img src="images/icon_dot02.gif" border=0 hspace="4">[�߱�����] ��ư�� Ŭ���Ͻø� ���� �߱޵� ������ ����ϵ�, ���̻� �űԹ߱��� ���� �������� �����˴ϴ�.</td>
										</tr>
										<tr>
											<td class="notice_gray" height="20"><img src="images/icon_dot02.gif" border=0 hspace="4">[����] ��ư�� Ŭ���Ͻø� ���� �߱޵� �������� ��� �����ǰ�, �űԹ߱޵� �ȵ˴ϴ�.</td>
										</tr>
										<tr>
											<td class="notice_gray" height="20"><img src="images/icon_dot02.gif" border=0 hspace="4">��ȿ�Ⱓ�� ���� ������ ��� [����]�� ������ ���ֽñ� �ٶ��ϴ�.</td>
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

			<!-- ó���� ���� ��ġ ���� -->
			<tr><td height=40></td></tr>
			<tr>
				<td>
				


				<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
				<tr>
					<td><img src="images/coupon_list_stitle01.gif" border=0 align=absmiddle alt="�߱��� ��������"></td>
				</tr>
				<tr><td height=10></td></tr>

				<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
				<input type=hidden name=type>
				<input type=hidden name=coupon_code value="<?=$coupon_code?>">
				<input type=hidden name=uid>
				<input type=hidden name=block value="<?=$block?>">
				<input type=hidden name=gotopage value="<?=$gotopage?>">
				<input type=hidden name=block2 value="<?=$block2?>">
				<input type=hidden name=gotopage2 value="<?=$gotopage2?>">
				<tr><td height=1 bgcolor=#cccccc></td></tr>
				<tr>
					<td bgcolor=E7E7E7>
					<table width=100% border=0 cellspacing=1 cellpadding=0 style="table-layout:fixed">
					<col width=35></col>
					<col width=65></col>
					<col width=></col>
					<col width=90></col>
					<col width=110></col>
					<col width=60></col>
					<col width=70></col>
					<col width=45></col>
					<tr height=35 align=center bgcolor=F5F5F5>
						<td align=center><B>��ȣ</B></td>
						<td align=center><B>�����ڵ�</B></td>
						<td align=center><B>������</B></td>
						<td align=center><B>�ݾ�/������</B></td>
						<td align=center><B>��ȿ�Ⱓ<B></td>
						<td align=center><B>�߱޳���</B></td>
						<td align=center><B>�߱�����</B></td>
						<td align=center><B>����</B></td>
					</tr>
<?
					$sql = "SELECT COUNT(*) as t_count FROM tblcouponinfo ";
					$sql.= "WHERE vender='".$_VenderInfo->getVidx()."' ";
					$result = mysql_query($sql,get_db_conn());
					$row = mysql_fetch_object($result);
					mysql_free_result($result);
					$t_count = $row->t_count;
					$pagecount = (($t_count - 1) / $setup[list_num]) + 1;

					$sql = "SELECT * FROM tblcouponinfo WHERE vender='".$_VenderInfo->getVidx()."' ORDER BY date DESC ";
					$sql.= "LIMIT " . ($setup[list_num] * ($gotopage - 1)) . ", " . $setup[list_num];
					$result = mysql_query($sql,get_db_conn());
					$cnt=0;
					while($row=mysql_fetch_object($result)) {
						$number = ($t_count-($setup[list_num] * ($gotopage-1))-$cnt);
						$cnt++;

						if($coupon_code==$row->coupon_code) {
							$coupon_name=$row->coupon_name;
						}

						if($row->sale_type<=2) $dan="%";
						else $dan="��";
						if($row->sale_type%2==0) $sale = "����";
						else $sale = "����";
						if($row->date_start>0) {
							$date = substr($row->date_start,2,2).".".substr($row->date_start,4,2).".".substr($row->date_start,6,2)." ~ ".substr($row->date_end,2,2).".".substr($row->date_end,4,2).".".substr($row->date_end,6,2);
						} else {
							$date = abs($row->date_start)."�ϵ���";
						}

						echo "<tr height=30 bgcolor=#FFFFFF>\n";
						echo "	<td align=center style=\"font-size:8pt\">".$number."</td>\n";
						echo "	<td align=center style=\"font-size:8pt\"><A HREF=\"javascript:CouponView('".$row->coupon_code."');\"><B>".$row->coupon_code."</B></A></td>\n";
						echo "	<td style=\"padding-left:5;color:#003399\"><nobr>".$row->coupon_name."</td>\n";
						echo "	<td align=center><font color=\"".($sale=="����"?"#FF0000":"#0000FF")."\">".number_format($row->sale_money).$dan." ".$sale."</td>\n";
						echo "	<td align=center style=\"font-size:8pt\">".$date."</td>\n";
						echo "	<td align=center><A HREF=\"javascript:CouponIssue('".$row->coupon_code."')\"><B>[��ȸ]</B></A></td>\n";
						echo "	<td align=center>";
						if($row->issue_type!="D") {
							echo "<A HREF=\"javascript:CouponStop('".$row->coupon_code."')\"><font color=red><B>[�߱�����]</B></font></A>";
						} else {
							echo "&nbsp;";
						}
						echo "	</td>\n";
						echo "	<td align=center><A HREF=\"javascript:CouponDelete('".$row->coupon_code."')\"><font color=red><B>[����]</B></font></A></td>\n";
						echo "</tr>\n";
					}
					mysql_free_result($result);
					if ($cnt==0) {
						echo "<tr><td height=30 bgcolor=#FFFFFF colspan=8 align=center>�߱��� ���������� �����ϴ�.</td></tr>";
					} else {
						$total_block = intval($pagecount / $setup[page_num]);
						if (($pagecount % $setup[page_num]) > 0) {
							$total_block = $total_block + 1;
						}
						$total_block = $total_block - 1;
						if (ceil($t_count/$setup[list_num]) > 0) {
							// ����	x�� ����ϴ� �κ�-����
							$a_first_block = "";
							if ($nowblock > 0) {
								$a_first_block .= "<a href='javascript:GoPage(0,1);' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='ù ������';return true\"><img src=".$Dir."images/minishop/btn_miniprev_end.gif border=0 align=absmiddle></a> ";
								$prev_page_exists = true;
							}
							$a_prev_page = "";
							if ($nowblock > 0) {
								$a_prev_page .= "<a href='javascript:GoPage(".($nowblock-1).",".($setup[page_num]*($block-1)+$setup[page_num]).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup[page_num]." ������';return true\"><img src=".$Dir."images/minishop/btn_miniprev.gif border=0 align=absmiddle></a> ";

								$a_prev_page = $a_first_block.$a_prev_page;
							}
							if (intval($total_block) <> intval($nowblock)) {
								$print_page = "";
								for ($gopage = 1; $gopage <= $setup[page_num]; $gopage++) {
									if ((intval($nowblock*$setup[page_num]) + $gopage) == intval($gotopage)) {
										$print_page .= "<FONT color=red><B>".(intval($nowblock*$setup[page_num]) + $gopage)."</B></font> ";
									} else {
										$print_page .= "<a href='javascript:GoPage(".$nowblock.",".(intval($nowblock*$setup[page_num]) + $gopage).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='������ : ".(intval($nowblock*$setup[page_num]) + $gopage)."';return true\">[".(intval($nowblock*$setup[page_num]) + $gopage)."]</a> ";
									}
								}
							} else {
								if (($pagecount % $setup[page_num]) == 0) {
									$lastpage = $setup[page_num];
								} else {
									$lastpage = $pagecount % $setup[page_num];
								}
								for ($gopage = 1; $gopage <= $lastpage; $gopage++) {
									if (intval($nowblock*$setup[page_num]) + $gopage == intval($gotopage)) {
										$print_page .= "<FONT color=red><B>".(intval($nowblock*$setup[page_num]) + $gopage)."</B></FONT> ";
									} else {
										$print_page .= "<a href='javascript:GoPage(".$nowblock.",".(intval($nowblock*$setup[page_num]) + $gopage).");' onMouseOver=\"window.status='������ : ".(intval($nowblock*$setup[page_num]) + $gopage)."';return true\">[".(intval($nowblock*$setup[page_num]) + $gopage)."]</a> ";
									}
								}
							}
							$a_last_block = "";
							if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
								$last_block = ceil($t_count/($setup[list_num]*$setup[page_num])) - 1;
								$last_gotopage = ceil($t_count/$setup[list_num]);
								$a_last_block .= " <a href='javascript:GoPage(".$last_block.",".$last_gotopage.");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='������ ������';return true\"><img src=".$Dir."images/minishop/btn_mininext_end.gif border=0 align=absmiddle></a>";
								$next_page_exists = true;
							}
							$a_next_page = "";
							if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
								$a_next_page .= " <a href='javascript:GoPage(".($nowblock+1).",".($setup[page_num]*($nowblock+1)+1).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup[page_num]." ������';return true\"><img src=".$Dir."images/minishop/btn_mininext.gif border=0 align=absmiddle></a>";
								$a_next_page = $a_next_page.$a_last_block;
							}
						} else {
							$print_page = "<B>1</B>";
						}
						$pageing=$a_div_prev_page.$a_prev_page.$print_page.$a_next_page.$a_div_next_page;
					}
?>
					</table>
					</td>
				</tr>
				<tr><td height=10></td></tr>
				<tr>
					<td align=center>

					<?=$pageing?>

					</td>
				</tr>
				<tr><td height=10></td></tr>

				<?if(strlen($coupon_code)>0){?>

				<tr><td height=20></td></tr>
<?
				$sql = "SELECT COUNT(*) as cnt, COUNT(IF(b.used='Y',1,NULL)) as cnt2, ";
				$sql.= "COUNT(IF(b.id like '%".$search."%',1,NULL)) as cnt3 ";
				$sql.= "FROM tblcouponinfo a, tblcouponissue b WHERE a.coupon_code = '".$coupon_code."' AND a.vender='".$_VenderInfo->getVidx()."' ";
				$sql.= "AND a.coupon_code=b.coupon_code ";
				$result=mysql_query($sql,get_db_conn());
				$row = mysql_fetch_object($result);
				mysql_free_result($result);
				$totalnum=$row->cnt;
				$usenum=$row->cnt2;
				$t_count2 = $row->cnt;
				if(strlen($search)>0) $t_count2 = $row->cnt3;
				$pagecount2 = (($t_count2 - 1) / $setup[list_num]) + 1;
?>
				<tr>
					<td>
					<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
					<col width=></col>
					<col width=230></col>
					<tr>
						<td><img src="images/icon_dot03.gif" border=0 align=absmiddle> �߱޹��� ȸ������ <FONT style="font-size:8pt;color:#2A97A7">(������ : <B><?=$coupon_name?></B> , &nbsp;&nbsp;&nbsp;�߱�:<B><?=$totalnum?></B>��, ���:<B><?=$usenum?></B>��)</FONT></td>
						<td align=right>
						�����̵� �˻� : <input type=text name=search value="<?=$search?>" style="width:70">
						<img src=images/btn_inquery02.gif border=0 align=absmiddle style="cursor:hand" onclick="document.form1.type.value='';document.form1.uid.value='';document.form1.submit();">
						</td>
					</tr>
					</table>
					</td>
				</tr>
				<tr><td height=2></td></tr>
				<tr><td height=1 bgcolor=#cccccc></td></tr>
				<tr>
					<td bgcolor=E7E7E7>
					<table width=100% border=0 cellspacing=1 cellpadding=0 style="table-layout:fixed">
					<col width=60></col>
					<col width=140></col>
					<col width=140></col>
					<col width=></col>
					<col width=80></col>
					<col width=80></col>
					<tr height=35 align=center bgcolor=F5F5F5>
						<td align=center><B>��ȣ</B></td>
						<td align=center><B>���̵�</B></td>
						<td align=center><B>�߱���</B></td>
						<td align=center><B>��ȿ�Ⱓ</B></td>
						<td align=center><B>��뿩��<B></td>
						<td align=center><B>���</B></td>
					</tr>
<?
					if($t_count2>0) {
						$sql = "SELECT b.* FROM tblcouponinfo a, tblcouponissue b ";
						$sql.= "WHERE a.coupon_code='".$coupon_code."' AND a.vender='".$_VenderInfo->getVidx()."' ";
						$sql.= "AND a.coupon_code=b.coupon_code ";
						if(strlen($search)>0) $sql.= "AND b.id LIKE '%".$search."%' ";
						$sql.= "ORDER BY b.date DESC LIMIT " . ($setup[list_num] * ($gotopage2 - 1)) . ", " . $setup[list_num];
						$result = mysql_query($sql,get_db_conn());
						$cnt=0;
						while($row=mysql_fetch_object($result)) {
							$number = ($t_count2-($setup[list_num] * ($gotopage2-1))-$cnt);
							$cnt++;

							$date = substr($row->date_start,0,4).".".substr($row->date_start,4,2).".".substr($row->date_start,6,2)." ~ ".substr($row->date_end,0,4).".".substr($row->date_end,4,2).".".substr($row->date_end,6,2);
							$regdate = substr($row->date,0,4).".".substr($row->date,4,2).".".substr($row->date,6,2);
							$used="<FONT COLOR=\"red\">�̻��</FONT>";
							if($row->used=="Y") $used="<FONT COLOR=\"blue\">�����</FONT>";
							echo "<tr height=30 bgcolor=#FFFFFF>\n";
							echo "	<td align=center>".$number."</td>\n";
							echo "	<td align=center>".$row->id."</td>\n";
							echo "	<td align=center>".$regdate."</td>\n";
							echo "	<td align=center>".$date."</td>\n";
							echo "	<td align=center>".$used."</td>\n";
							if($row->used=="Y") {
								echo "	<td align=center><A HREF=\"javascript:IssueCouponAgain('".$row->coupon_code."','".$row->id."')\"><font color=blue><B>[��߱�]</B></font></A></td>\n";
							} else {
								echo "	<td align=center><A HREF=\"javascript:IssueCouponDelete('".$row->coupon_code."','".$row->id."')\"><font color=red><B>[����]</B></font></A></td>\n";
							}
							echo "</tr>\n";
						}
						mysql_free_result($result);

						$total_block2 = intval($pagecount2 / $setup[page_num]);
						if (($pagecount2 % $setup[page_num]) > 0) {
							$total_block2 = $total_block2 + 1;
						}
						$total_block2 = $total_block2 - 1;
						if (ceil($t_count2/$setup[list_num]) > 0) {
							// ����	x�� ����ϴ� �κ�-����
							$a_first_block2 = "";
							if ($nowblock2 > 0) {
								$a_first_block2 .= "<a href='javascript:GoPage2(0,1);' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='ù ������';return true\"><img src=".$Dir."images/minishop/btn_miniprev_end.gif border=0 align=absmiddle></a> ";

								$prev_page_exists2 = true;
							}
							$a_prev_page2 = "";
							if ($nowblock2 > 0) {
								$a_prev_page2 .= "<a href='javascript:GoPage(".($nowblock2-1).",".($setup[page_num]*($block2-1)+$setup[page_num]).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup[page_num]." ������';return true\"><img src=".$Dir."images/minishop/btn_miniprev.gif border=0 align=absmiddle></a> ";

								$a_prev_page2 = $a_first_block2.$a_prev_page2;
							}
							if (intval($total_block2) <> intval($nowblock2)) {
								$print_page2 = "";
								for ($gopage2 = 1; $gopage2 <= $setup[page_num]; $gopage2++) {
									if ((intval($nowblock2*$setup[page_num]) + $gopage2) == intval($gotopage2)) {
										$print_page2 .= "<FONT color=red><B>".(intval($nowblock2*$setup[page_num]) + $gopage2)."</B></font> ";
									} else {
										$print_page2 .= "<a href='javascript:GoPage2(".$nowblock2.",".(intval($nowblock2*$setup[page_num]) + $gopage2).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='������ : ".(intval($nowblock2*$setup[page_num]) + $gopage2)."';return true\">[".(intval($nowblock2*$setup[page_num]) + $gopage2)."]</a> ";
									}
								}
							} else {
								if (($pagecount2 % $setup[page_num]) == 0) {
									$lastpage2 = $setup[page_num];
								} else {
									$lastpage2 = $pagecount2 % $setup[page_num];
								}
								for ($gopage2 = 1; $gopage2 <= $lastpage2; $gopage2++) {
									if (intval($nowblock2*$setup[page_num]) + $gopage2 == intval($gotopage2)) {
										$print_page2 .= "<FONT color=red><B>".(intval($nowblock2*$setup[page_num]) + $gopage2)."</B></FONT> ";
									} else {
										$print_page2 .= "<a href='javascript:GoPage2(".$nowblock2.",".(intval($nowblock2*$setup[page_num]) + $gopage2).");' onMouseOver=\"window.status='������ : ".(intval($nowblock2*$setup[page_num]) + $gopage2)."';return true\">[".(intval($nowblock2*$setup[page_num]) + $gopage2)."]</a> ";
									}
								}
							}
							$a_last_block2 = "";
							if ((intval($total_block2) > 0) && (intval($nowblock2) < intval($total_block2))) {
								$last_block2 = ceil($t_count2/($setup[list_num]*$setup[page_num])) - 1;
								$last_gotopage2 = ceil($t_count2/$setup[list_num]);

								$a_last_block2 .= " <a href='javascript:GoPage(".$last_block2.",".$last_gotopage2.");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='������ ������';return true\"><img src=".$Dir."images/minishop/btn_mininext_end.gif border=0 align=absmiddle></a>";

								$next_page_exists2 = true;
							}
							$a_next_page2 = "";
							if ((intval($total_block2) > 0) && (intval($nowblock2) < intval($total_block2))) {
								$a_next_page2 .= " <a href='javascript:GoPage(".($nowblock2+1).",".($setup[page_num]*($nowblock2+1)+1).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup[page_num]." ������';return true\"><img src=".$Dir."images/minishop/btn_mininext.gif border=0 align=absmiddle></a>";

								$a_next_page2 = $a_next_page2.$a_last_block2;
							}
						} else {
							$print_page2 = "<B>1</B>";
						}
						$pageing2=$a_div_prev_page2.$a_prev_page2.$print_page2.$a_next_page2.$a_div_next_page2;
					} else {
						echo "<tr><td height=30 bgcolor=#FFFFFF colspan=6 align=center>��ȸ�� ������ �����ϴ�.</td></tr>";
					}
?>
					</table>
					</td>
				</tr>
				<tr><td height=10></td></tr>
				<tr>
					<td align=center>

					<?=$pageing2?>

					</td>
				</tr>
				<?}?>

				</form>
				</table>

				</td>
			</tr>
			<!-- ó���� ���� ��ġ �� -->

			</table>
			</td>
		</tr>
		</table>
		</td>
	</tr>
	</table>

	</td>
</tr>

<form name=cform action="coupon_view.php" method=post target=couponview>
<input type=hidden name=coupon_code>
</form>

</table>

<iframe name="processFrame" src="about:blank" width="0" height="0" scrolling=no frameborder=no></iframe>

<?=$onload?>

<? INCLUDE "copyright.php"; ?>