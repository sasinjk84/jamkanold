<table cellpadding="0" cellspacing="0" width="100%">
<tr>
	<td>
				<div class="orderStateWrap" style="text-align:center;padding:26px 0px;"><span style="font-size:20px;color:#777777;padding:0px 10px 0px 0px">��밡���� ������</span><span style="color:#FF6600;font-weight:bold;font-size:30px;"><?=number_format($reserve)?>��</div>
	<table cellpadding="0" cellspacing="0" width="100%">
	<!--
	<tr>
		<td>
		<TABLE WIDTH="100%" BORDER="0" CELLPADDING="0" CELLSPACING="0">
		<TR>
			<TD><A HREF="<?=$Dir.FrontDir?>mypage.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin3_menu1.gif" BORDER="0"></A></TD>
			<TD><A HREF="<?=$Dir.FrontDir?>mypage_orderlist.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin3_menu2.gif" BORDER="0"></A></TD>
			<TD><A HREF="<?=$Dir.FrontDir?>mypage_personal.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin3_menu3.gif" BORDER="0"></A></TD>
			<TD><A HREF="<?=$Dir.FrontDir?>wishlist.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin3_menu4.gif" BORDER="0"></A></TD>
			<TD><A HREF="<?=$Dir.FrontDir?>mypage_reserve.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin3_menu5r.gif" BORDER="0"></A></TD>
			<TD><A HREF="<?=$Dir.FrontDir?>mypage_coupon.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin3_menu6.gif" BORDER="0"></A></TD>
			<?if($_data->recom_url_ok == "Y" || $_data->sns_ok == "Y"){?><TD><A HREF="<?=$Dir.FrontDir?>mypage_promote.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin3_menu10.gif" BORDER="0"></A></TD><?}?>
			<TD><A HREF="<?=$Dir.FrontDir?>mypage_gonggu.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin3_menu11.gif" BORDER="0"></A></TD>
			<? if(getVenderUsed()==true) { ?><TD><A HREF="<?=$Dir.FrontDir?>mypage_custsect.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin3_menu9.gif" BORDER="0"></A></TD><? } ?>
			<TD><A HREF="<?=$Dir.FrontDir?>mypage_usermodify.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin3_menu7.gif" BORDER="0"></A></TD>
			<TD><A HREF="<?=$Dir.FrontDir?>mypage_memberout.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin3_menu8.gif" BORDER="0"></A></TD>
			<TD width="100%" background="<?=$Dir?>images/common/mypersonal_skin3_menubg.gif"></TD>
		</TR>
		</TABLE>
		</td>
	</tr>
	-->
	<tr>
		<td style="padding:10px 0px;line-height:22px;">
		* ���Բ��� ��밡���� �������� <b><?=number_format($reserve)?>��</b> �Դϴ�.<br>
		* ������ �ݾ��� <b><?=number_format($maxreserve)?>�� �̻� ����</b>�Ǿ�����, ����Ͻ� �� �ֽ��ϴ�.������ ������ ��뿩�θ� Ȯ���ϴ� �ȳ����� ���ɴϴ�. <br>
		* ������ ������ <b>�ֱ� 6�������� ����</b>�ǹǷ� ���� ���� �ٶ��ϴ�.<br>
		* �ֹ��Ϸ� �� �ο��� ���� ������ �ش� ������ Ŭ���Ͻø� �󼼳����� Ȯ���Ͻ� �� �ֽ��ϴ�.(��, �����Ͻ� �ֹ������� ��ȸ�� �Ұ����մϴ�. )
		<? if($_data->cr_ok=='Y') { ?>
		<br>* ������ �������� <b><?=number_format($_data->cr_unit)?>��</b> ������ �������� ��ȯ �Ͻ� �� �ֽ��ϴ�.
		   <a href='<?=$Dir.FrontDir?>mypage_cash01.php'><IMG SRC="../images/design/promote3_btn01.gif" border="0" align="absmiddle"></a>
		<? } ?>	
		</td>
	</tr>
	<tr><td height=25></td></tr>
	<tr>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%" border="0" class="orderlistTbl">
		<col width="140"></col>
		<col></col>
		<col width="100"></col>
		<col width="100"></col>
		<tr>
			<th>�߻�����</th>
			<th>�߻�����</th>
			<th>�����ݾ�</th>
			<th>������</th>
		</tr>
<?
		$sql = "SELECT COUNT(*) as t_count FROM tblreserve ";
		$sql.= "WHERE id='".$_ShopInfo->getMemid()."' ";
		$sql.= "AND date >= '".$s_curdate."' AND date <= '".$e_curdate."' ";
		$result = mysql_query($sql,get_db_conn());
		$row = mysql_fetch_object($result);
		$t_count = $row->t_count;
		mysql_free_result($result);
		$pagecount = (($t_count - 1) / $setup[list_num]) + 1;

		$sql = "SELECT * FROM tblreserve WHERE id='".$_ShopInfo->getMemid()."' ";
		$sql.= "AND date >= '".$s_curdate."' AND date <= '".$e_curdate."' ";
		$sql.= "ORDER BY date DESC LIMIT " . ($setup[list_num] * ($gotopage - 1)) . ", " . $setup[list_num];
		$result=mysql_query($sql,get_db_conn());
		$cnt=0;
		while($row=mysql_fetch_object($result)) {
			$number = ($t_count-($setup[list_num] * ($gotopage-1))-$i);
			$date=substr($row->date,0,4)."/".substr($row->date,4,2)."/".substr($row->date,6,2);

			if($cnt>0) {

			}

			$ordercode="";
			$orderprice="";
			$orderdata=$row->orderdata;
			if(strlen($orderdata)>0) {
				$tmpstr=explode("=",$orderdata);
				$ordercode=$tmpstr[0];
				$orderprice=$tmpstr[1];
			}

			echo "<tr>\n";
			echo "	<td class=\"centerCell\">".$date."</td>\n";
			echo "	<td align=\"left\"><nobr><a";
			if(strlen($ordercode)>0) echo " style=\"cursor:hand;\" onclick=\"OrderDetailPop('".$ordercode."')\">";
			echo "".$row->content."</a></td>\n";
			echo "	<td class=\"centerCell\">";
			if(strlen($orderprice)>0 && $orderprice>0) {
				echo "<font color=\"#F02800\"><b>".number_format($orderprice)."��";
			} else {
				echo "-";
			}
			echo "</td>\n";
			echo "	<td class=\"centerCell\">".number_format($row->reserve)."��</td>\n";
			echo "</tr>\n";
			$cnt++;
		}
		mysql_free_result($result);
		if ($cnt==0) {
			echo "<tr><td colspan=\"4\" align=\"center\" style=\"padding:30px;\">�ش系���� �����ϴ�.</td></tr>";
		}
?>
		</table>
		</td>
	</tr>
	<tr>
		<td height="30"></td>
	</tr>
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
	<tr>
		<td align="center"><?=$a_div_prev_page.$a_prev_page.$print_page.$a_next_page.$a_div_next_page?></td>
	</tr>
	</table>
	</td>
</tr>
</table>