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

//����Ʈ ����
$setup[page_num] = 10;
$setup[list_num] = 15;

$block=$_REQUEST["block"];
$gotopage=$_REQUEST["gotopage"];

if ($block != "") {
	$nowblock = $block;
	$curpage  = $block * $setup[page_num] + $gotopage;
} else {
	$nowblock = 0;
}

if (($gotopage == "") || ($gotopage == 0)) {
	$gotopage = 1;
}

$mode=$_POST["mode"];
$auction_seq=$_POST["auction_seq"];
$auction_date=$_POST["auction_date"];

$id=$_POST["id"];
$date=$_POST["date"];

$imagepath=$Dir.DataDir."shopimages/auction/";

if($mode=="delete" && strlen($auction_seq)>0 && strlen($auction_date)>0) {
	$sql = "SELECT product_image FROM tblauctioninfo ";
	$sql.= "WHERE auction_seq='".$auction_seq."' AND start_date='".$auction_date."' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		if(strlen($row->product_image)>0 && file_exists($imagepath.$row->product_image)) {
			unlink($imagepath.$row->product_image);
		}
	}
	mysql_free_result($result);
	$sql = "DELETE FROM tblauctioninfo ";
	$sql.= "WHERE auction_seq='".$auction_seq."' AND start_date='".$auction_date."' ";
	mysql_query($sql,get_db_conn());

	$sql = "DELETE FROM tblauctionresult ";
	$sql.= "WHERE auction_seq='".$auction_seq."' AND start_date='".$auction_date."' ";
	mysql_query($sql,get_db_conn());
	$onload="<script>alert(\"�ش� ��Ÿ� �����Ͽ����ϴ�.\");</script>";
} else if($mode=="lastdel" && strlen($auction_seq)>0 && strlen($auction_date)>0 && strlen($id)>0 && strlen($date)>0) {
	$sql = "DELETE FROM tblauctionresult ";
	$sql.= "WHERE auction_seq='".$auction_seq."' AND start_date='".$auction_date."' ";
	$sql.= "AND id='".$id."' AND date='".$date."' ";
	mysql_query($sql,get_db_conn());

	$sql = "SELECT start_price, bid_cnt FROM tblauctioninfo ";
	$sql.= "WHERE auction_seq='".$auction_seq."' AND start_date='".$auction_date."' ";
	$result=mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
	mysql_free_result($result);
	$last_price=(int)$row->start_price;
	$bid_cnt=(int)$row->bid_cnt;

	if($bid_cnt>0) {
		$sql = "SELECT price FROM tblauctionresult ";
		$sql.= "WHERE auction_seq='".$auction_seq."' AND start_date='".$auction_date."' ";
		$sql.= "ORDER BY date DESC LIMIT 1 ";
		$result=mysql_query($sql,get_db_conn());
		if($row=mysql_fetch_object($result)) {
			$last_price=$row->price;
		}
		mysql_free_result($result);
	}
	if($bid_cnt>0) $bid_cnt--;
	$sql = "UPDATE tblauctioninfo SET ";
	$sql.= "last_price	= '".$last_price."', ";
	$sql.= "bid_cnt		= '".$bid_cnt."' ";
	$sql.= "WHERE auction_seq='".$auction_seq."' AND start_date='".$auction_date."' ";
	mysql_query($sql,get_db_conn());
	$onload="<script>alert(\"���������� ������ �Ϸ�Ǿ����ϴ�.\");<script>";
}

?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function CheckForm() {

}

function AuctionModify(auction_seq,auction_date) {
	document.modifyform.auction_seq.value=auction_seq;
	document.modifyform.auction_date.value=auction_date;
	document.modifyform.submit();
}

function AuctionDelete(auction_seq,auction_date) {
	if(!confirm("�ش� ��Ÿ� ���� �����Ͻðڽ��ϱ�?")) return;
	document.form1.mode.value="delete";
	document.form1.auction_seq.value=auction_seq;
	document.form1.auction_date.value=auction_date;
	document.form1.submit();
}

function LastDelete(auction_seq,auction_date,id,date) {
	if(!confirm("���������� ������ �Ͻðڽ��ϱ�?")) return;
	document.lastform.auction_seq.value=auction_seq;
	document.lastform.auction_date.value=auction_date;
	document.lastform.id.value=id;
	document.lastform.date.value=date;
	document.lastform.submit();
}

function MemberView(id){
	document.memberform.search.value=id;
	document.memberform.submit();
}

function GoPage(block,gotopage) {
	document.form1.block.value = block;
	document.form1.gotopage.value = gotopage;
	document.form1.submit();
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
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ����/��� &gt; ���θ� ��� ���� &gt; <span class="2depth_select">��� ��� ����</span></td>
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
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=mode>
			<input type=hidden name=auction_seq>
			<input type=hidden name=auction_date>
			<input type=hidden name=block value="<?=$block?>">
			<input type=hidden name=gotopage value="<?=$gotopage?>">
			<tr><td height="8"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD>
						<IMG SRC="images/gong_auctionlist_title.gif" border="0">
					</TD>
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
					<TD width="100%" class="notice_blue">��ϵ� ��Ÿ� ������ �� �ֽ��ϴ�.</TD>
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
					<TD><IMG SRC="images/gong_auctionlist_stitle1.gif" WIDTH="187" HEIGHT=31 ALT=""></TD>
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
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<col width=95></col>
				<col width=></col>
				<col width=80></col>
				<col width=70></col>
				<col width=45></col>
				<col width=45></col>
				<col width=55></col>
				<TR>
					<TD colspan=7 background="images/table_top_line.gif"></TD>
				</TR>
				<TR align=center>
					<TD class="table_cell">��� ������</TD>
					<TD class="table_cell1">��� ��ǰ��</TD>
					<TD class="table_cell1">����������</TD>
					<TD class="table_cell1">����������</TD>
					<TD class="table_cell1">������</TD>
					<TD class="table_cell1">��ȸ</TD>
					<TD class="table_cell1">����</TD>
				</TR>
				<TR>
					<TD colspan="7" background="images/table_con_line.gif"></TD>
				</TR>
<?
				$colspan=7;
				$sql = "SELECT COUNT(*) as t_count FROM tblauctioninfo "; 
				$result = mysql_query($sql,get_db_conn());
				$row = mysql_fetch_object($result);
				mysql_free_result($result);
				$t_count = $row->t_count;
				$pagecount = (($t_count - 1) / $setup[list_num]) + 1;

				$sql = "SELECT a.auction_seq,a.start_date,a.end_date,a.auction_name,a.last_price,a.bid_cnt,a.access, ";
				$sql.= "b.id,b.date FROM tblauctioninfo a LEFT JOIN tblauctionresult b ";
				$sql.= "ON a.auction_seq=b.auction_seq AND a.start_date=b.start_date AND a.last_price=b.price ";
				$sql.= "ORDER BY a.end_date DESC ";
				$sql.= "LIMIT " . ($setup[list_num] * ($gotopage - 1)) . ", " . $setup[list_num];
				$result = mysql_query($sql,get_db_conn());
				$cnt=0;
				while($row=mysql_fetch_object($result)) {
					$number = ($t_count-($setup[list_num] * ($gotopage-1))-$cnt);
					$end_date=substr($row->end_date,0,4)."/".substr($row->end_date,4,2)."/".substr($row->end_date,6,2)."(".substr($row->end_date,8,2).":".substr($row->end_date,10,2).")";
					echo "<TR>\n";
					echo "	<TD align=center class=\"td_con2\">";
					if($row->end_date<date("YmdHis")) {
						echo "<img src=\"images/gong_auctionlist_endicon.gif\" width=\"55\" height=\"15\" border=\"0\">";
					} else {
						echo "<NOBR>".$end_date."</NOBR>";
					}
					echo "	</TD>\n";
					if(strlen($row->id)==0) {	//������ ����
						echo "	<TD class=\"td_con1\"><A HREF=\"javascript:AuctionModify('".$row->auction_seq."','".$row->start_date."');\">".$row->auction_name."</A>&nbsp;</TD>\n";
						echo "	<TD align=center class=\"td_con1\">������ ����</TD>\n";
					} else {	//������ ����
						echo "	<TD class=\"td_con1\">".$row->auction_name."</TD>\n";
						echo "	<TD align=center class=\"td_con1\"><A HREF=\"javascript:MemberView('".$row->id."');\"><b>".$row->id."</b></A>";
						if($row->end_date>date("YmdHis")) {
							echo "<BR><A HREF=\"javascript:LastDelete('".$row->auction_seq."','".$row->start_date."','".$row->id."','".$row->date."')\"><img src=\"images/icon_del1.gif\" boder=\"0\"></A>";
						}
						echo "	</TD>\n";
					}
					echo "	<TD align=center class=\"td_con1\"><b><span class=\"font_orange\">".number_format($row->last_price)."��</span></b></TD>\n";
					echo "	<TD align=center class=\"td_con1\">".(int)$row->bid_cnt."</TD>\n";
					echo "	<TD align=center class=\"td_con3\">".(int)$row->access."</TD>\n";
					echo "	<TD align=center class=\"td_con2\"><a href=\"javascript:AuctionDelete('".$row->auction_seq."','".$row->start_date."')\"><img src=\"images/btn_del.gif\" width=\"50\" height=\"22\" border=\"0\"></TD>\n";
					echo "</TR>\n";
					echo "<TR>\n";
					echo "	<TD colspan=".$colspan." background=\"images/table_con_line.gif\"></TD>\n";
					echo "</TR>\n";
					$cnt++;
				}
				mysql_free_result($result);
				if ($cnt==0) {
					echo "<tr><td class=td_con2 colspan=".$colspan." align=center>�˻��� ������ �������� �ʽ��ϴ�.</td></tr>";
				}
?>
				<TR>
					<TD colspan=7 background="images/table_top_line.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td align=center class="font_size">
<?
					$total_block = intval($pagecount / $setup[page_num]);

					if (($pagecount % $setup[page_num]) > 0) {
						$total_block = $total_block + 1;
					}

					$total_block = $total_block - 1;

					if (ceil($t_count/$setup[list_num]) > 0) {
						// ����	x�� ����ϴ� �κ�-����
						$a_first_block = "";
						if ($nowblock > 0) {
							$a_first_block .= "<a href='javascript:GoPage(0,1);' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='ù ������';return true\"><IMG src=\"images/icon_first.gif\" border=0 align=\"absmiddle\"></a>&nbsp;&nbsp;";

							$prev_page_exists = true;
						}

						$a_prev_page = "";
						if ($nowblock > 0) {
							$a_prev_page .= "<a href='javascript:GoPage(".($nowblock-1).",".($setup[page_num]*($block-1)+$setup[page_num]).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup[page_num]." ������';return true\">[prev]</a>&nbsp;&nbsp;";

							$a_prev_page = $a_first_block.$a_prev_page;
						}

						// �Ϲ� �������� ������ ǥ�úκ�-����

						if (intval($total_block) <> intval($nowblock)) {
							$print_page = "";
							for ($gopage = 1; $gopage <= $setup[page_num]; $gopage++) {
								if ((intval($nowblock*$setup[page_num]) + $gopage) == intval($gotopage)) {
									$print_page .= "<span class=font_orange2><B>[".(intval($nowblock*$setup[page_num]) + $gopage)."]</B></span> ";
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
									$print_page .= "<span class=font_orange2><B>[".(intval($nowblock*$setup[page_num]) + $gopage)."]</B></span> ";
								} else {
									$print_page .= "<a href='javascript:GoPage(".$nowblock.",".(intval($nowblock*$setup[page_num]) + $gopage).");' onMouseOver=\"window.status='������ : ".(intval($nowblock*$setup[page_num]) + $gopage)."';return true\">[".(intval($nowblock*$setup[page_num]) + $gopage)."]</a> ";
								}
							}
						}		// ������ �������� ǥ�úκ�-��


						$a_last_block = "";
						if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
							$last_block = ceil($t_count/($setup[list_num]*$setup[page_num])) - 1;
							$last_gotopage = ceil($t_count/$setup[list_num]);

							$a_last_block .= "&nbsp;&nbsp;<a href='javascript:GoPage(".$last_block.",".$last_gotopage.");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='������ ������';return true\"><IMG src=\"images/icon_last.gif\" border=0 align=\"absmiddle\" width=\"17\" height=\"14\"></a>";

							$next_page_exists = true;
						}

						// ���� 10�� ó���κ�...

						$a_next_page = "";
						if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
							$a_next_page .= "&nbsp;&nbsp;<a href='javascript:GoPage(".($nowblock+1).",".($setup[page_num]*($nowblock+1)+1).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup[page_num]." ������';return true\">[next]</a>";

							$a_next_page = $a_next_page.$a_last_block;
						}
					} else {
						$print_page = "<B>[1]</B>";
					}
?>
					<?=$a_div_prev_page.$a_prev_page.$print_page.$a_next_page.$a_div_next_page?>
					</td>
				</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
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
						<td class="space_top" style="letter-spacing:-0.5pt;">- ���������ڿ� ������������ ��µǸ�, ���� ������ Ŭ���� �ش� ȸ�� ������ ��µ˴ϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- ��Ÿ����� <b>����������</b>�� ��� ��ǰ�� ������ �� �ְ� ���� ó���ϼž� �մϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- ��ϵ� ��� ��ǰ �� �����ڰ� ���� ��ǰ�� ��ǰ�� Ŭ���� ������ �����մϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- ��� ��� ������ ������ ��Ŵ� ���� ó���� �ֽø� �˴ϴ�.</td>
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
			</form>

			<form name=lastform action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=mode value="lastdel">
			<input type=hidden name=auction_seq>
			<input type=hidden name=auction_date>
			<input type=hidden name=id>
			<input type=hidden name=date>
			<input type=hidden name=block value="<?=$block?>">
			<input type=hidden name=gotopage value="<?=$gotopage?>">
			</form>

			<form name=modifyform action="gong_auctionreg.php" method=post>
			<input type=hidden name=auction_seq>
			<input type=hidden name=auction_date>
			</form>
			<form name=memberform action="member_list.php" method=post>
			<input type=hidden name=search>
			</form>
			</table>
			</td>
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

<?=$onload?>

<? INCLUDE "copyright.php"; ?>