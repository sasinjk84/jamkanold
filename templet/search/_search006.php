<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
<tr>
	<td style="padding-left:5px;padding-right:5px;">
	<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
	<tr>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td colspan="3" height="6" background="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin4_t1.gif"></td>
		</tr>
		<tr>
			<td width="6" background="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin4_t2.gif"></td>
			<td background="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin4_tbg.gif" style="padding:15pt;">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin4_text1.gif" border="0"></td>
				<td width="30" nowrap></td>
				<td width="100%">
				<table cellpadding="2" cellspacing="0" width="100%">
				<tr>
					<td><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin4_icon1.gif" border="0"></td>
					<td width="100%"><select name=codeA style="width:183px" onchange="SearchChangeCate(this,1)" style="font-size:11px;">
					<option value="">--- 1�� ī�װ� ���� ---</option>
					</select>
					<select name=codeB style="width:183px" onchange="SearchChangeCate(this,2)" style="font-size:11px;">
					<option value="">--- 2�� ī�װ� ���� ---</option>
					</select></td>
				</tr>
				<TR>
					<TD></td>
					<td><select name=codeC style="width:183px;" onchange="SearchChangeCate(this,3)" style="font-size:11px;">
					<option value="">--- 3�� ī�װ� ���� ---</option>
					</select>
					<select name=codeD style="width:183px" style="font-size:11px;">
					<option value="">--- 4�� ī�װ� ���� ---</option>
					</select></td>
				</tr>
				<tr>
					<td><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin4_icon2.gif" border="0"></td>
					<td width="100%"><input type=text name=minprice value="<?=$minprice?>" style="WIDTH: 175px" onkeyup="strnumkeyup(this)" class="input" style="BACKGROUND-COLOR:#F7F7F7;"> <b><span style="font-size:13pt;">~</span></b> <input type=text name=maxprice value="<?=$maxprice?>" style="WIDTH: 175px" onkeyup="strnumkeyup(this)" class="input" style="BACKGROUND-COLOR:#F7F7F7;"></td>
				</tr>
				<tr>
					<td><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin4_icon3.gif" border="0"></td>
					<td width="100%"><select name=s_check style="width:90px;" style="font-size:11px;">
					<option value="all" <?if($s_check=="all")echo"selected";?>>���հ˻�</option>
					<option value="keyword" <?if($s_check=="keyword")echo"selected";?>>��ǰ��/Ű����</option>
					<option value="code" <?if($s_check=="code")echo"selected";?>>��ǰ�ڵ�</option>
					<option value="selfcode" <?if($s_check=="selfcode")echo"selected";?>>�����ڵ�</option>
					<option value="production" <?if($s_check=="production")echo"selected";?>>������</option>
					<option value="model" <?if($s_check=="model")echo"selected";?>>�𵨸�</option>
					<option value="content" <?if($s_check=="content")echo"selected";?>>�󼼼���</option>
					</select> <input type=text name=search value="<?=$search?>" style="WIDTH: 277px;" class="input" style="BACKGROUND-COLOR:#F7F7F7;"></td>
				</tr>
				</table>
				</td>
				<td align="right"><a href="javascript:CheckForm();"><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin4_icon4.gif" border="0"></a></td>
			</tr>
			</table>
			</td>
			<td width="6" background="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin4_t5.gif"></td>
		</tr>
		<tr>
			<td colspan="3" height="6" background="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin4_t3.gif"></td>
		</tr>
		</table>
		<script>SearchCodeInit("<?=$codeA?>","<?=$codeB?>","<?=$codeC?>","<?=$codeD?>");</script>
		</td>
	</tr>
	<tr>
		<td height="20"></td>
	</tr>
	<tr>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin4_sticon.gif" border="0"></td>
			<td width="100%" background="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin4_stibg.gif">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td style="color:#ffffff;font-size:11px;">�� ��ϻ�ǰ : <b><?=$t_count?>��</b></td>
				<td align="right"><select name=listnum onchange="ChangeListnum(this.value)" style="font-size:11px;">
				<option value="20"<?if($listnum==20)echo" selected";?> style="color:#444444;">20���� ����
				<option value="40"<?if($listnum==40)echo" selected";?> style="color:#444444;">40���� ����
				<option value="60"<?if($listnum==60)echo" selected";?> style="color:#444444;">60���� ����
				<option value="100"<?if($listnum==100)echo" selected";?> style="color:#444444;">100���� ����
				</select></td>
			</tr>
			</table>
			</td>
			<td><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin4_stimg.gif" border="0"></td>
		</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td height="28" style="padding-left:10px;"><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin3_text10.gif" border="0"><a href="javascript:ChangeSort('new');"><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin3_nerotop<?if($sort=="new")echo"_on";?>.gif" border="0"></a><a href="javascript:ChangeSort('new_desc');"><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin3_nerodow<?if($sort=="new_desc")echo"_on";?>.gif" border="0"></a><img src="../images/common/space_line.gif" width="8" height="1" border="0"><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin3_text11.gif" border="0"><a href="javascript:ChangeSort('best');"><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin3_nerotop<?if($sort=="best")echo"_on";?>.gif" border="0"></a><a href="javascript:ChangeSort('best_desc');"><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin3_nerodow<?if($sort=="best_desc")echo"_on";?>.gif" border="0"></a><img src="../images/common/space_line.gif" width="8" height="1" border="0"><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin4_text01.gif" border="0"><a href="javascript:ChangeSort('production');"><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin4_nerotop<?if($sort=="production")echo"_on";?>.gif" border="0"></a><a href="javascript:ChangeSort('production_desc');"><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin4_nerodow<?if($sort=="production_desc")echo"_on";?>.gif" border="0"></a><img src="../images/common/space_line.gif" width="8" height="1" border="0"><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin4_text02.gif" border="0"><a href="javascript:ChangeSort('name');"><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin4_nerotop<?if($sort=="name")echo"_on";?>.gif" border="0"></a><a href="javascript:ChangeSort('name_desc');"><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin4_nerodow<?if($sort=="name_desc")echo"_on";?>.gif" border="0"></a><img src="../images/common/space_line.gif" width="8" height="1" border="0"><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin4_text03.gif" border="0"><a href="javascript:ChangeSort('price');"><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin4_nerotop<?if($sort=="price")echo"_on";?>.gif" border="0"></a><a href="javascript:ChangeSort('price_desc');"><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin4_nerodow<?if($sort=="price_desc")echo"_on";?>.gif" border="0"></a><img src="../images/common/space_line.gif" width="8" height="1" border="0"><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin4_text04.gif" border="0"><a href="javascript:ChangeSort('reserve');"><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin4_nerotop<?if($sort=="reserve")echo"_on";?>.gif" border="0"></a><a href="javascript:ChangeSort('reserve_desc');"><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin4_nerodow<?if($sort=="reserve_desc")echo"_on";?>.gif" border="0"></a></td>
	</tr>
	<tr>
		<td height="1" background="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin4_line.gif"></td>
	</tr>
	<tr>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%">
		<col width="15%"></col>
		<col width="0"></col>
		<col width="50%"></col>
		<col width="12%"></col>
		<col width="12%"></col>
		<col width="11%"></col>
		<tr align="center" height="30" bgcolor="#F8F8F8">
			<td colspan="2"><font color="#000000"><b>��ǰ����</b></font></td>
			<td><font color="#000000"><b>��ǰ��</b></font></td>
			<td><font color="#000000"><b>���߰���</b></font></td>
			<td><font color="#000000"><b>�ǸŰ���</b></font></td>
			<td><font color="#000000"><b>������</b></font></td>
		</tr>
		<tr><td colspan=6 bgcolor=EDEDED height=1></td></tr>
		<tr><td colspan=6  height=5></td></tr>
<?
		$tag_0_count = 5; //��ü��ǰ �±� ��� ����
		//��ȣ, ����, ��ǰ��, ������, ����
		$tmp_sort=explode("_",$sort);
		if($tmp_sort[0]=="reserve") {
			$addsortsql=",IF(a.reservetype='N',a.reserve*1,a.reserve*a.sellprice*0.01) AS reservesort ";
		}
		$sql = "SELECT a.productcode, a.productname, a.sellprice, a.quantity, a.reserve, a.reservetype, a.production, ";
		$sql.= "a.tinyimage, a.date, a.etctype, a.option_price, a.consumerprice, a.tag, a.selfcode ";
		$sql.= $addsortsql;
		$sql.= "FROM tblproduct AS a ";
		$sql.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
		$sql.= $qry." ";
		$sql.= "AND (a.group_check='N' OR b.group_code='".$_ShopInfo->getMemgroup()."') ";
		if($tmp_sort[0]=="production") $sql.= "ORDER BY a.production ".$tmp_sort[1]." ";
		else if($tmp_sort[0]=="name") $sql.= "ORDER BY a.productname ".$tmp_sort[1]." ";
		else if($tmp_sort[0]=="price") $sql.= "ORDER BY a.sellprice ".$tmp_sort[1]." ";
		else if($tmp_sort[0]=="reserve") $sql.= "ORDER BY reservesort ".$tmp_sort[1]." ";
else if($tmp_sort[0]=="new") $sql.= "ORDER BY a.regdate ".$tmp_sort[1]." ";
else if($tmp_sort[0]=="best") $sql.= "ORDER BY a.sellcount ".$tmp_sort[1]." ";		else $sql.= "ORDER BY a.productname ";
		$sql.= "LIMIT " . ($setup[list_num] * ($gotopage - 1)) . ", " . $setup[list_num];
		$result=mysql_query($sql,get_db_conn());
		$i=0;
		while($row=mysql_fetch_object($result)) {
			$number = ($t_count-($setup[list_num] * ($gotopage-1))-$i);
			

			echo "<tr align=\"center\" id=\"A".$row->productcode."\" onmouseover=\"quickfun_show(this,'A".$row->productcode."','','row')\" onmouseout=\"quickfun_show(this,'A".$row->productcode."','none')\">\n";
			echo "	<td style=\"padding-top:1px;padding-bottom:1px;\">";
			if (strlen($row->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$row->tinyimage)==true) {
				echo "<A HREF=\"".$Dir.FrontDir."productdetail.php?productcode=".$row->productcode."\" onmouseover=\"window.status='��ǰ����ȸ';return true;\" onmouseout=\"window.status='';return true;\"><img src=\"".$Dir.DataDir."shopimages/product/".urlencode($row->tinyimage)."\" border=\"0\" ";
				$width = getimagesize($Dir.DataDir."shopimages/product/".$row->tinyimage);
				if($_data->ETCTYPE["IMGSERO"]=="Y") {
					if ($width[1]>$width[0] && $width[1]>$_data->primg_minisize2) echo "height=\"".$_data->primg_minisize2."\" ";
					else if (($width[1]>=$width[0] && $width[0]>=$_data->primg_minisize) || $width[0]>=$_data->primg_minisize) echo "width=\"".$_data->primg_minisize."\" ";
				} else {
					if ($width[0]>=$width[1] && $width[0]>=$_data->primg_minisize) echo "width=\"".$_data->primg_minisize."\" ";
					else if ($width[1]>=$_data->primg_minisize) echo "height=\"".$_data->primg_minisize."\" ";
				}
			} else {
				echo "<img src=\"".$Dir."images/no_img.gif\" border=\"0\" align=\"center\"";
			}
			echo "	></A></td>";
			echo "	<td style=\"position:relative;\">".($_data->ETCTYPE["QUICKTOOLS"]!="Y"?"<script>quickfun_write('".$Dir."','A','".$row->productcode."','".($row->quantity=="0"?"":"1")."','row')</script>":"")."</td>";
			echo "	<td style=\"padding-left:5px;padding-right:5px;word-break:break-all;\" align=\"left\"><A HREF=\"".$Dir.FrontDir."productdetail.php?productcode=".$row->productcode."\" onmouseover=\"window.status='��ǰ����ȸ';return true;\" onmouseout=\"window.status='';return true;\"><FONT class=\"prname\">".viewproductname($row->productname,$row->etctype,$row->selfcode)."</FONT>".(strlen($row->prmsg)?'<br><span class="prmsgArea">'.$row->prmsg.'</span>':'')."</A>";
			if($_data->ETCTYPE["TAGTYPE"]=="Y") {
				$taglist=explode(",",$row->tag);
				$jj=0;
				for($ii=0;$ii<$tag_0_count;$ii++) {
					$taglist[$ii]=ereg_replace("(<|>)","",$taglist[$ii]);
					if(strlen($taglist[$ii])>0) {
						if($jj==0) {
							echo "<br><br><img src=\"".$Dir."images/common/tag_icon.gif\" border=\"0\" align=\"absmiddle\" style=\"margin-right:2px;\"><a href=\"".$Dir.FrontDir."tag.php?tagname=".urlencode($taglist[$ii])."\" onmouseover=\"window.status='".$taglist[$ii]."';return true;\" onmouseout=\"window.status='';return true;\"><FONT class=\"prtag\">".$taglist[$ii]."</font></a>";
						}
						else {
							echo "<FONT class=\"prtag\">,</font>&nbsp;<a href=\"".$Dir.FrontDir."tag.php?tagname=".urlencode($taglist[$ii])."\" onmouseover=\"window.status='".$taglist[$ii]."';return true;\" onmouseout=\"window.status='';return true;\"><FONT class=\"prtag\">".$taglist[$ii]."</font></a>";
						}
						$jj++;
					}
				}
			}
			echo "	</td>\n";
			echo "	<TD style=\"word-break:break-all;\" class=\"prconsumerprice\"><img src=\"".$Dir."images/common/won_icon2.gif\" border=\"0\" style=\"margin-right:2px;\"><strike>".number_format($row->consumerprice)."</strike>��</td>\n";
			echo "	<TD style=\"word-break:break-all;\" class=\"prprice\">";
			if($dicker=dickerview($row->etctype,number_format($row->sellprice)."��",1)) {
				echo $dicker;
			} else if(strlen($_data->proption_price)==0) {
				echo "<img src=\"".$Dir."images/common/won_icon.gif\" border=\"0\" style=\"margin-right:2px;\">".number_format($row->sellprice)."��";
				if (strlen($row->option_price)!=0) echo "(�⺻��)";
			} else {
				if (strlen($row->option_price)==0) echo "<img src=\"".$Dir."images/common/won_icon.gif\" border=\"0\" style=\"margin-right:2px;\">".number_format($row->sellprice)."��";
				else echo "<img src=\"".$Dir."images/common/won_icon.gif\" border=\"0\" style=\"margin-right:2px;\">".ereg_replace("\[PRICE\]",number_format($row->sellprice),$_data->proption_price);
			}
			if ($row->quantity=="0") echo soldout();
			echo "	</td>\n";
			echo "	<TD style=\"word-break:break-all;\" class=\"prreserve\" align=\"center\"><img src=\"".$Dir."images/common/reserve_icon.gif\" border=\"0\" style=\"margin-right:2px;\">".number_format(getReserveConversion($row->reserve,$row->reservetype,$row->sellprice,"Y"))."��</td>\n";
			echo "</tr>\n";
			echo "<tr><td height=\"5\"></td></tr><tr>\n";
			echo "	<td height=\"1\" background=\"".$Dir."images/common/search/".$_data->design_search."/design_search_skin4_line3.gif\" colspan=\"6\"></td>";
			echo "</tr><tr><td height=\"5\"></td></tr>\n";

			$i++;
		}
		mysql_free_result($result);

		if($i == 0) {
			echo "<tr><td height=\"5\"></td></tr><tr>\n";
			echo "	<td height=\"1\" background=\"".$Dir."images/common/search/".$_data->design_search."/design_search_skin4_line3.gif\" colspan=\"6\"></td>";
			echo "</tr><tr><td height=\"5\"></td></tr>\n";
		}
?>
		</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td height="1" background="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin4_line.gif"></td>
	</tr>
	<tr>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td height="28" style="padding-left:10px;"><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin4_text01.gif" border="0"><a href="javascript:ChangeSort('production');"><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin4_nerotop<?if($sort=="production")echo"_on";?>.gif" border="0"></a><a href="javascript:ChangeSort('production_desc');"><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin4_nerodow<?if($sort=="production_desc")echo"_on";?>.gif" border="0"></a><img src="../images/common/space_line.gif" width="8" height="1" border="0"><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin4_text02.gif" border="0"><a href="javascript:ChangeSort('name');"><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin4_nerotop<?if($sort=="name")echo"_on";?>.gif" border="0"></a><a href="javascript:ChangeSort('name_desc');"><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin4_nerodow<?if($sort=="name_desc")echo"_on";?>.gif" border="0"></a><img src="../images/common/space_line.gif" width="8" height="1" border="0"><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin4_text03.gif" border="0"><a href="javascript:ChangeSort('price');"><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin4_nerotop<?if($sort=="price")echo"_on";?>.gif" border="0"></a><a href="javascript:ChangeSort('price_desc');"><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin4_nerodow<?if($sort=="price_desc")echo"_on";?>.gif" border="0"></a><img src="../images/common/space_line.gif" width="8" height="1" border="0"><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin4_text04.gif" border="0"><a href="javascript:ChangeSort('reserve');"><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin4_nerotop<?if($sort=="reserve")echo"_on";?>.gif" border="0"></a><a href="javascript:ChangeSort('reserve_desc');"><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin4_nerodow<?if($sort=="reserve_desc")echo"_on";?>.gif" border="0"></a></td>
			<td align="right" style="padding-right:10px;"><select name=listnum onchange="ChangeListnum(this.value)" style="color:#444444;font-size:11px;">
			<option value="20"<?if($listnum==20)echo" selected";?>>20���� ����
			<option value="40"<?if($listnum==40)echo" selected";?>>40���� ����
			<option value="60"<?if($listnum==60)echo" selected";?>>60���� ����
			<option value="100"<?if($listnum==100)echo" selected";?>>100���� ����
			</select></td>
		</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td height="1" background="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin4_line.gif"></td>
	</tr>
	<tr>
		<td height="20"></td>
	</tr>
	<tr>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td width="100%" style="font-size:11px;" align="center">
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
			<?=$a_div_prev_page.$a_prev_page.$print_page.$a_next_page.$a_div_next_page?>
			</td>
		</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td height="20"></td>
	</tr>
	</table>
	</td>
</tr>
</table>