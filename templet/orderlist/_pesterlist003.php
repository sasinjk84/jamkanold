<table cellpadding="0" cellspacing="0" width="100%">
<tr>
	<td style="padding:5px;padding-top:0px;">
	<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td valign="bottom">
		<TABLE WIDTH="100%" BORDER="0" CELLPADDING="0" CELLSPACING="0">
		<TR>
			<TD><A HREF="<?=$Dir.FrontDir?>mypage.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin1_menu1.gif" BORDER="0"></A></TD>
			<TD><A HREF="<?=$Dir.FrontDir?>mypage_orderlist.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin1_menu2r.gif" BORDER="0"></A></TD>
			<TD><A HREF="<?=$Dir.FrontDir?>mypage_personal.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin1_menu3.gif" BORDER="0"></A></TD>
			<TD><A HREF="<?=$Dir.FrontDir?>wishlist.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin1_menu4.gif" BORDER="0"></A></TD>
			<TD><A HREF="<?=$Dir.FrontDir?>mypage_reserve.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin1_menu5.gif" BORDER="0"></A></TD>
			<TD><A HREF="<?=$Dir.FrontDir?>mypage_coupon.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin1_menu6.gif" BORDER="0"></A></TD>
			<?if($_data->recom_url_ok == "Y" || $_data->sns_ok == "Y"){?><TD><A HREF="<?=$Dir.FrontDir?>mypage_promote.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin1_menu10.gif" BORDER="0"></A></TD><?}?>
			<TD><A HREF="<?=$Dir.FrontDir?>mypage_gonggu.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin1_menu11.gif" BORDER="0"></A></TD>
			<? if(getVenderUsed()==true) { ?><TD><A HREF="<?=$Dir.FrontDir?>mypage_custsect.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin1_menu9.gif" BORDER="0"></A></TD><? } ?>
			<TD><A HREF="<?=$Dir.FrontDir?>mypage_usermodify.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin1_menu7.gif" BORDER="0"></A></TD>
			<TD><A HREF="<?=$Dir.FrontDir?>mypage_memberout.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin1_menu8.gif" BORDER="0"></A></TD>
			<TD width="100%" background="<?=$Dir?>images/common/mypersonal_skin1_menubg.gif"></TD>
		</TR>
		</TABLE>
		</td>
	</tr>
	<tr>
		<td height="20"></td>
	</tr>
	<tr>
		<td height="20"><span style="font-weight:bold;color:#000;">�� ������ ����</span></td>
	</tr>
	<tr>
		<td style="padding:10px;padding-right:0px;font-size:11px;letter-spacing:-0.5pt;line-height:15px;">* ���� �ֱ� <font color="#F02800" style="font-size:11px;letter-spacing:-0.5pt;"><b>6���� �ڷ���� ����</b></font>�Ǹ�, <font color="#000000" style="font-size:11px;letter-spacing:-0.5pt;"><b>6���� ���� �ڷ�� ���ڸ� �����ؼ� ��ȸ</b></font>�Ͻñ� �ٶ��ϴ�.<br>
		&nbsp;&nbsp;&nbsp;(���ں��� ��ȸ�� �ִ� ���� 3�� ������ ������ ��ȸ�� �����մϴ�)<br>
		*&nbsp;�� ���� ��ȸ ������ �Ⱓ�� 6������ ���� ���ý� ��ȸ �Ⱓ�� 6���� �̳��� �����ϼž� �մϴ�.</td>
	</tr>
	<tr>
		<td>
		<table cellpadding="0" cellspacing="8" width="100%" bgcolor="#E8E8E8">
		<tr>
			<td background="<?=$Dir?>images/common/orderlist/<?=$_data->design_orderlist?>/design_search_skin1_tbg.gif" style="padding:20px;">
			<table width="100%" cellpadding="0" cellspacing="0">
			<tr>
				<td height="26"><img src="<?=$Dir?>images/common/orderlist/<?=$_data->design_orderlist?>/orderlist_skin1_text01.gif" border="0" align="absmiddle"></td>
				<td><A HREF="javascript:GoSearch('TODAY')"><img src="<?=$Dir?>images/common/orderlist/<?=$_data->design_orderlist?>/orderlist_skin1_btn01.gif" border="0" align="absmiddle"></A>
				<A HREF="javascript:GoSearch('15DAY')"><img src="<?=$Dir?>images/common/orderlist/<?=$_data->design_orderlist?>/orderlist_skin1_btn02.gif" border="0" align="absmiddle"></A>
				<A HREF="javascript:GoSearch('1MONTH')"><img src="<?=$Dir?>images/common/orderlist/<?=$_data->design_orderlist?>/orderlist_skin1_btn03.gif" border="0" hspace="2" align="absmiddle"></A>
				<A HREF="javascript:GoSearch('3MONTH')"><img src="<?=$Dir?>images/common/orderlist/<?=$_data->design_orderlist?>/orderlist_skin1_btn04.gif" border="0" align="absmiddle"></A>
				<A HREF="javascript:GoSearch('6MONTH')"><img src="<?=$Dir?>images/common/orderlist/<?=$_data->design_orderlist?>/orderlist_skin1_btn05.gif" border="0" hspace="2" align="absmiddle"></A></td>
			</tr>
			<tr>
				<td><img src="<?=$Dir?>images/common/orderlist/<?=$_data->design_orderlist?>/orderlist_skin1_text02.gif" border="0" align="absmiddle"></td>
				<td><SELECT onchange="ChangeDate('s')" name="s_year" align="absmiddle" style="font-size:11px;">
				<?
				for($i=date("Y");$i>=(date("Y")-2);$i--) {
					echo "<option value=\"".$i."\"";
					if($s_year==$i) echo " selected";
					echo " style=\"color:#444444;\">".$i."</option>\n";
				}
				?>
				</SELECT> <SELECT onchange="ChangeDate('s')" name="s_month" style="font-size:11px;">
				<?
				for($i=1;$i<=12;$i++) {
					echo "<option value=\"".$i."\"";
					if($s_month==$i) echo " selected";
					echo " style=\"color:#444444;\">".$i."</option>\n";
				}
				?>
				</SELECT> <SELECT name="s_day" style="font-size:11px;">
				<?
				for($i=1;$i<=get_totaldays($s_year,$s_month);$i++) {
					echo "<option value=\"".$i."\"";
					if($s_day==$i) echo " selected";
					echo " style=\"color:#444444;\">".$i."</option>\n";
				}
				?>
				</SELECT><b> ~ </b> <SELECT onchange="ChangeDate('e')" name="e_year" style="font-size:11px;">
				<?
				for($i=date("Y");$i>=(date("Y")-2);$i--) {
					echo "<option value=\"".$i."\"";
					if($e_year==$i) echo " selected";
					echo " style=\"color:#444444;\">".$i."</option>\n";
				}
				?>
				</SELECT> <SELECT onchange="ChangeDate('e')" name="e_month" style="font-size:11px;">
				<?
				for($i=1;$i<=12;$i++) {
					echo "<option value=\"".$i."\"";
					if($e_month==$i) echo " selected";
					echo " style=\"color:#444444;\">".$i."</option>\n";
				}
				?>
				</SELECT> <SELECT name="e_day" style="font-size:11px;">
				<?
				for($i=1;$i<=get_totaldays($e_year,$e_month);$i++) {
					echo "<option value=\"".$i."\"";
					if($e_day==$i) echo " selected";
					echo " style=\"color:#444444;\">".$i."</option>\n";
				}
				?>
				</SELECT><a href="javascript:CheckForm();"><img src="<?=$Dir?>images/common/orderlist/<?=$_data->design_orderlist?>/orderlist_skin1_btn06.gif" border="0" hspace="5" align="absmiddle"></a> </td>
			</tr>
			</table>
			</td>
		</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td height="20"></td>
	</tr>
	<tr>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%" border="0" bgcolor="#E7E7E7" style="table-layout:fixed">
		<col width="100"></col>
		<col></col>
		<col width="200"></col>
		<col width="200"></col>
		<tr height="30" align="center" bgcolor="#F8F8F8" style="letter-spacing:-0.5pt;">
			<td><font color="#333333"><b>������ ����</b></font></td>
			<td><font color="#333333"><b>�ֹ� ��ǰ��</b></font></td>
			<td><font color="#333333"><b>������ ���</b></font></td>
			<td><font color="#333333"><b>������</b></font></td>
		</tr>
		<tr>
			<td height="1" colspan="4" bgcolor="#DDDDDD"></td>
		</tr>
<?
		$s_curtime=mktime(0,0,0,$s_month,$s_day,$s_year);
		$e_curtime=mktime(23,59,59,$e_month,$e_day,$e_year);

		$sql = "SELECT COUNT(*) as t_count FROM tblpesterinfo WHERE id='".$_ShopInfo->getMemid()."' ";
		$sql.= "AND regdate >= '".$s_curtime."' AND regdate <= '".$e_curtime."' ";
		$result=mysql_query($sql,get_db_conn());
		$row=mysql_fetch_object($result);
		$t_count = (int)$row->t_count;
		mysql_free_result($result);
		$pagecount = (($t_count - 1) / $setup[list_num]) + 1;

		$sql = "SELECT tempkey, pester_name,pester_tel, state, ordercode, regdate ";
		$sql.= "FROM tblpesterinfo WHERE id='".$_ShopInfo->getMemid()."' ";
		$sql.= "AND regdate >= '".$s_curtime."' AND regdate <= '".$e_curtime."' ";
		$sql.= "ORDER BY regdate DESC ";
		$sql.= "LIMIT " . ($setup[list_num] * ($gotopage - 1)) . ", " . $setup[list_num];
		$result=mysql_query($sql,get_db_conn());
		$cnt=0;
		while($row=mysql_fetch_object($result)) {

			echo "<tr bgcolor=\"#FFFFFF\" onmouseover=\"this.style.background='#FEFBD1';\" onmouseout=\"this.style.background='#FFFFFF';\">\n";
			echo "	<td align=\"center\" style=\"font-size:8pt;padding:3;\">".date("Y-m-d", $row->regdate)."</td>\n";
			echo "	<td>\n";
			echo "	<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"table-layout:fixed\">\n";
			echo "	<col></col>\n";
			$sql = "SELECT productname FROM tblbasket4 a, tblproduct b WHERE a.productcode=b.productcode AND tempkey='".$row->tempkey."' ";
			$result2=mysql_query($sql,get_db_conn());
			$jj=0;
			while($row2=mysql_fetch_object($result2)) {
				if($jj>0) echo "<tr><td height=\"1\" ></tr>";
				echo "<tr>\n";
				echo "	<td style=\"font-size:8pt;padding:3;line-height:11pt;\"><A HREF=\"javascript:PesterDetailPop('".$row->tempkey."')\" onmouseover=\"window.status='�ֹ�������ȸ';return true;\" onmouseout=\"window.status='';return true;\">".$row2->productname."</a></td>\n";
				echo "</tr>\n";
				$jj++;
			}
			mysql_free_result($result2);
			echo "	</table>\n";
			echo "	</td>\n";
			echo "	<td align=\"center\" style=\"font-size:8pt;\">".$row->pester_name."(".$row->pester_tel.")</td>\n";
			echo "	<td align=\"center\">";
			if($row->state == "0"){
				echo "������ ���� ���";
			}else if($row->state == "1"){
				echo "������ ���� <A HREF=\"javascript:OrderDetailPop('".$row->ordercode."')\" onmouseover=\"window.status='�ֹ�������ȸ';return true;\" onmouseout=\"window.status='';return true;\"><img src=\"".$Dir."images/common/mypage_detailview.gif\" border=\"0\" align=\"absmiddle\"></A>\n";
			}else if($row->state == "0"){
				echo "������ ���� <A HREF=\"javascript:OrderDetailPop('".$row->ordercode."')\" onmouseover=\"window.status='�ֹ�������ȸ';return true;\" onmouseout=\"window.status='';return true;\"><img src=\"".$Dir."images/common/mypage_detailview.gif\" border=\"0\" align=\"absmiddle\"></A>\n";
			}else{
				echo "������ ��û�� ��ǰ�� ǰ�� �� �Ǹ�����";
			}
			echo "	</td>\n";
			echo "</tr>\n";
			echo "<tr><td colspan=\"4\" height=\"1\" bgcolor=\"#F5F5F5\"></td></tr>\n";
			$cnt++;
		}
		mysql_free_result($result);
		if($cnt ==0){
			echo "<tr><td colspan=\"4\" height=25 align=center>������ �� ������ �����ϴ�.</td></tr>";
			echo "<tr><td colspan=\"4\" height=\"1\" bgcolor=\"#F5F5F5\"></td></tr>\n";
		}
?>
		</table>
		</td>
	</tr>
	<tr>
		<td height="20"></td>
	</tr>
	<tr>
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
				$a_first_block .= "<a href='javascript:GoPage(0,1);' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='ù ������';return true\"><FONT class=\"prlist\">[1...]</FONT></a>&nbsp;&nbsp;";

				$prev_page_exists = true;
			}

			$a_prev_page = "";
			if ($nowblock > 0) {
				$a_prev_page .= "<a href='javascript:GoPage(".($nowblock-1).",".($setup[page_num]*($block-1)+$setup[page_num]).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup[page_num]." ������';return true\"><FONT class=\"prlist\">[prev]</FONT></a>&nbsp;&nbsp;";

				$a_prev_page = $a_first_block.$a_prev_page;
			}

			// �Ϲ� �������� ������ ǥ�úκ�-����

			if (intval($total_block) <> intval($nowblock)) {
				$print_page = "";
				for ($gopage = 1; $gopage <= $setup[page_num]; $gopage++) {
					if ((intval($nowblock*$setup[page_num]) + $gopage) == intval($gotopage)) {
						$print_page .= "<FONT class=\"choiceprlist\">".(intval($nowblock*$setup[page_num]) + $gopage)."</font> ";
					} else {
						$print_page .= "<a href='javascript:GoPage(".$nowblock.",".(intval($nowblock*$setup[page_num]) + $gopage).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='������ : ".(intval($nowblock*$setup[page_num]) + $gopage)."';return true\"><FONT class=\"prlist\">[".(intval($nowblock*$setup[page_num]) + $gopage)."]</FONT></a> ";
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
						$print_page .= "<FONT class=\"choiceprlist\">".(intval($nowblock*$setup[page_num]) + $gopage)."</FONT> ";
					} else {
						$print_page .= "<a href='javascript:GoPage(".$nowblock.",".(intval($nowblock*$setup[page_num]) + $gopage).");' onMouseOver=\"window.status='������ : ".(intval($nowblock*$setup[page_num]) + $gopage)."';return true\"><FONT class=\"prlist\">[".(intval($nowblock*$setup[page_num]) + $gopage)."]</FONT></a> ";
					}
				}
			}		// ������ �������� ǥ�úκ�-��


			$a_last_block = "";
			if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
				$last_block = ceil($t_count/($setup[list_num]*$setup[page_num])) - 1;
				$last_gotopage = ceil($t_count/$setup[list_num]);

				$a_last_block .= "&nbsp;&nbsp;<a href='javascript:GoPage(".$last_block.",".$last_gotopage.");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='������ ������';return true\"><FONT class=\"prlist\">[...".$last_gotopage."]</FONT></a>";

				$next_page_exists = true;
			}

			// ���� 10�� ó���κ�...

			$a_next_page = "";
			if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
				$a_next_page .= "&nbsp;&nbsp;<a href='javascript:GoPage(".($nowblock+1).",".($setup[page_num]*($nowblock+1)+1).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup[page_num]." ������';return true\"><FONT class=\"prlist\">[next]</FONT></a>";

				$a_next_page = $a_next_page.$a_last_block;
			}
		} else {
			$print_page = "<FONT class=\"prlist\">1</FONT>";
		}
?>
		<td align="center"><?=$a_div_prev_page.$a_prev_page.$print_page.$a_next_page.$a_div_next_page?></td>
	</tr>
	<tr>
		<td height="20"></td>
	</tr>
	</table>
	</td>
</tr>
</table>