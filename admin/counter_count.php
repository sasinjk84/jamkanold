<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/class/pages.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "st-1";
$MenuCode = "counter";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$postip=$_GET["ip"];
$postmsg=$_GET["msg"];
$mode=$_GET["mode"];
$page=$_GET["page"];

// ���� �߰�
if( $mode == "notAuthIP_Insert" AND strlen($postip) > 8 ) {
	$ipInsertSQL = "INSERT `tblConnIP_block` SET `IP` = '".$postip."', `msg` = '".$postmsg."' ; ";
	mysql_query( $ipInsertSQL, get_db_conn() );
	//header("location:/admin/counter_NotAuthIP.php");
}

?>


<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>

<script type="text/javascript">
<!--
	// ���� ���
	function notAuthIP( f ) {
		if( confirm('���� ���� �Ͻðڽ��ϱ�?') ) {
			if( f.msg.value.length == 0 ) {
				alert('�޸� �Է��ϼ���!');
				f.msg.focus();
				return false;
			}

			f.methode="GET";
			f.submit();
		}
	}

	function MemberInfo(id) {
		window.open("about:blank","infopop","width=567,height=600,scrollbars=yes");
		document.form3.target="infopop";
		document.form3.id.value=id;
		document.form3.action="member_infopop.php";
		document.form3.submit();
	}
//-->
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
			<? include ("menu_counter.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ���м� &gt; ���� ���� &gt; <span class="2depth_select">���� IP ����</span></td>
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
						<TR><TD><IMG SRC="images/counter_connIP_title.gif"ALT="���� IP ����"></TD></tr>
						<tr><TD width="100%" background="images/title_bg.gif" height="21"></TD></TR>
					</TABLE>
				</td>
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
					<TD background="images/distribute_04.gif"></TD>
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
					<TD width="100%" class="notice_blue"><p>���θ��� ������ ��� �湮���� IP����� Ȯ�� �� ���� ������ �� �ֽ��ϴ�.</p></TD>
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
			<tr><td height="40"></td></tr>

			<tr><TD><IMG SRC="images/shop_connIplist_stitle1.gif" ALT="����IP ���" /></TD></tr>
			<tr><td height="6"></td></tr>
			<tr>
				<td align=center>

				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td>

						<table border=0 cellpadding=0 cellspacing=0 width="100%">
							<col width="150"></col>
							<col width="80"></col>
							<col width=""></col>
							<TR><TD colspan=4 background="images/table_top_line.gif" alt="" /></TD></TR>
							<TR>
								<TD class="table_cell" align="center">IP Address</TD>
								<TD class="table_cell1" align="center">����Ƚ��</TD>
								<TD class="table_cell1" align="center">ȸ��ID</TD>
								<TD class="table_cell1" align="center">���� (���ܸ޸�)</TD>
							</TR>
							<?
								$list_item=50;
								$list_page=10;

								if($page=='') $page = 1; //������ �ʱ�ȭ
								$offset = $list_item*($page-1); //�������� ���۰� ���

								$ipListSQL = "
									SELECT
										S.*,
										B.`idx` as block,
										B.`msg`
									FROM
										`tblConnIP_statistics` as S
										LEFT OUTER JOIN `tblConnIP_block` as B ON S.`IP` = B.`IP`
								";
								$result=mysql_query($ipListSQL) or die (mysql_error());
								$total_no=mysql_num_rows($result); // ���ù� �� ����

								$ipListSQL.=" ORDER BY S.`count` DESC LIMIT ".$offset.", ".$list_item." "; // ������ ��� ����
								$total_page=ceil($total_no/$list_item); // ��ü �Խù� ������ ����
								if($total_page==0) $total_page=1; // ��ü �Խù� ������ ���� �ʱ�ȭ


								$linkstr = "?page=%u";
								$pageSet = array('page'=>$page,'total_page'=>$total_page,'links'=>$linkstr,'pageblocks'=>$list_page,'style_pages'=>'%u', // �Ϲ� ������
									'style_page_sep'=>'&nbsp;.&nbsp;');
								$Opage = new pages($pageSet);
								$Opage->_solv();

								$ipListResult = mysql_query( $ipListSQL , get_db_conn() );
								while ( $ipListRow = mysql_fetch_assoc ( $ipListResult ) ) {
							?>
							<tr><td colspan=4 height=1 bgcolor=#EDEDED></td></tr>
							<TR>
								<TD class="td_con2" align=center><FONT color=#666666 title="<?=$ipListRow[IP]?>"><?=long2ip($ipListRow[IP])?></FONT></TD>
								<TD class="td_con1" align=center><FONT color=#666666><?=number_format($ipListRow['count'])?> ȸ</FONT></TD>
								<TD class="td_con1" align=center>
									<table style="width:90%">
									<?
										$ipMemSQL = "SELECT *, count(*) as cnt FROM `tblConnIP_memid` WHERE `IP` = '".$ipListRow[IP]."' GROUP BY `memid` ORDER BY cnt DESC; ";
										$ipMemResult = mysql_query( $ipMemSQL , get_db_conn() );
										while($ipMemRow = mysql_fetch_assoc( $ipMemResult )){
											echo "
												<tr>
													<td align=\"center\">
														<a href=\"javascript:MemberInfo('".$ipMemRow['memid']."');\">
															".$ipMemRow['memid']."
														</a>
													</td>
													<td align=\"right\">
														".number_format($ipMemRow['cnt'])." ȸ
													</td>
												</tr>
												<TR><TD colspan=2 background=\"images/table_top_line.gif\" /></TD></TR>
											";

										}
									?>
									</table>
								</TD>
								<form name="notAuthIP<?=$ipListRow[idx]?>form">
								<TD class="td_con1" align=center>
									<?
										if( $ipListRow[block] > 0 ) {
											echo "���ܵ� (".$ipListRow[msg].")";
										} else {
									?>
									<ul style="list-style:none; margin:0px; padding:0px;">
										<li style="float:left;"><input type="text" name="msg" size="60" class="input"></li>
										<li style="float:left; padding-left:5px;"><a href="#" onclick="notAuthIP(notAuthIP<?=$ipListRow[idx]?>form);"><img src="images/btn_block.gif" alt="����" /></a></li>
									</ul>
									<?
										}
									?>
								</TD>
								<input type="hidden" name="mode" value="notAuthIP_Insert">
								<input type="hidden" name="ip" value="<?=$ipListRow[IP]?>">
								<input type="hidden" name="page" value="<?=$page?>">
								</form>
							</TR>
							<?
								}
							?>
							<TR><TD colspan=4 background="images/table_top_line.gif" alt="" /></TD></TR>
							<TR><TD colspan=4 height="30" /></TD></TR>
							<TR>
								<TD colspan=4 align=center>
									<?=$Opage->_result('fulltext')?>
								</TD>
							</TR>
						</table>
					</td>
				</tr>
				</table>

				</td>
			</tr>
			<tr><td height="30"></td></tr>
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
					<TD COLSPAN=3 width="100%" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;" class="menual_bg" >
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><FONT color=#3d3d3d>���θ� ���� ���� IP ����.</FONT></td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><FONT color=#3d3d3d>��ȸ�� / ȸ���α��ν� ������ ���� �ߺ� ī��Ʈ �˴ϴ�.</FONT></td>
					</tr>
					<tr><td colspan=2 height=5></td></tr>
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
			<tr><td height="30"></td></tr>
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


<form name=form3 method=post>
	<input type=hidden name=id>
</form>

<?=$onload?>
<? INCLUDE "copyright.php"; ?>
