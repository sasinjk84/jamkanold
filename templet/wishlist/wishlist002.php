<table cellpadding="0" cellspacing="0" width="100%">
<tr>
	<td style="padding:5px;padding-top:0px;">
	<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td>
		<TABLE WIDTH="100%" BORDER="0" CELLPADDING="0" CELLSPACING="0">
		<TR>
			<TD><A HREF="<?=$Dir.FrontDir?>mypage.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin2_menu1.gif" BORDER="0"></A></TD>
			<TD><A HREF="<?=$Dir.FrontDir?>mypage_orderlist.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin2_menu2.gif" BORDER="0"></A></TD>
			<TD><A HREF="<?=$Dir.FrontDir?>mypage_personal.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin2_menu3.gif" BORDER="0"></A></TD>
			<TD><A HREF="<?=$Dir.FrontDir?>wishlist.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin2_menu4r.gif" BORDER="0"></A></TD>
			<TD><A HREF="<?=$Dir.FrontDir?>mypage_reserve.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin2_menu5.gif" BORDER="0"></A></TD>
			<TD><A HREF="<?=$Dir.FrontDir?>mypage_coupon.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin2_menu6.gif" BORDER="0"></A></TD>
			<?if($_data->recom_url_ok == "Y" || $_data->sns_ok == "Y"){?><TD><A HREF="<?=$Dir.FrontDir?>mypage_promote.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin2_menu10.gif" BORDER="0"></A></TD><?}?>
			<TD><A HREF="<?=$Dir.FrontDir?>mypage_gonggu.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin2_menu11.gif" BORDER="0"></A></TD>
			<? if(getVenderUsed()==true) { ?><TD><A HREF="<?=$Dir.FrontDir?>mypage_custsect.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin2_menu9.gif" BORDER="0"></A></TD><? } ?>
			<TD><A HREF="<?=$Dir.FrontDir?>mypage_usermodify.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin2_menu7.gif" BORDER="0"></A></TD>
			<TD><A HREF="<?=$Dir.FrontDir?>mypage_memberout.php"><IMG SRC="<?=$Dir?>images/common/mypersonal_skin2_menu8.gif" BORDER="0"></A></TD>
			<TD width="100%" background="<?=$Dir?>images/common/mypersonal_skin2_menubg.gif"></TD>
		</TR>
		</TABLE>
		</td>
	</tr>
	<tr>
		<td height="20"></td>
	</tr>
	<tr>
		<td style="font-size:11px;letter-spacing:-0.5pt;line-height:15px;">&nbsp;* WishList�� ���� ������ ���ݰ� ���� �ֹ����� ������ ��ǰ <font color="#FF4C00" style="font-size:11px;letter-spacing:-0.5pt;"><b>Ȯ���� �ϴ� ������ ���� ���� ������ �޶����Ƿ� ���� ����</b></font>�� ���� �� �ֽ��ϴ�.<br>
		&nbsp;* "WishList"�� ��ǰ�� �����Ͻø� ���Ͻô� �ñ⿡ �ֹ��� �Ͻ� �� �ֽ��ϴ�.<br>
		&nbsp;* ���ſ켱������ �Բ� �޸� ������ �νø� ���ϰ� ���� �Ͻ� �� �ֽ��ϴ�. (���ſ켱������ �� 3���� �⺻������ ����˴ϴ�.)</td>
	</tr>
	<tr>
		<td height="20"></td>
	</tr>
<?
	$qry = "WHERE a.id='".$_ShopInfo->getMemid()."' ";
	$qry.= "AND a.productcode=b.productcode AND b.display='Y' ";
	$qry.= "AND (b.group_check='N' OR c.group_code='".$_ShopInfo->getMemgroup()."') ";

	$sql = "SELECT COUNT(*) as t_count ";
	$sql.= "FROM tblwishlist a, tblproduct b ";
	$sql.= "LEFT OUTER JOIN tblproductgroupcode c ON b.productcode=c.productcode ";
	$sql.= $qry;
	$result=mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
	$t_count = (int)$row->t_count;
	mysql_free_result($result);
	$pagecount = (($t_count - 1) / $setup[list_num]) + 1;
?>
	<tr>
		<td>
		<table cellpadding="0" cellspacing="6" width="100%" bgcolor="#E8E8E8">
		<tr>
			<td width="100%" bgcolor=#ffffff style="padding:25px;">
			<table cellpadding="0" cellspacing="0" align=center>
			<tr>
				<td><img src="<?=$Dir?>images/common/wishlist/<?=$_data->design_wishlist?>/wishlist_skin2_t_text1.gif" border="0"></td>
				<td valign="bottom"><font color="#FF4C00" style="font-size:30px;line-height:28px;letter-spacing:-0.5pt;"><b><?=$t_count?>��</b></font></td>
				<td><img src="<?=$Dir?>images/common/wishlist/<?=$_data->design_wishlist?>/wishlist_skin2_t_text2.gif" border="0"></td>
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
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td height="1" bgcolor="#666666"></td>
		</tr>
		<tr>
			<td bgcolor="#F8F8F8"style="padding:5px;">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td><A HREF="javascript:CheckBoxAll()"><img src="<?=$Dir?>images/common/wishlist/<?=$_data->design_wishlist?>/wishlist_skin2_btn01.gif" border="0"></A><A HREF="javascript:GoDelete()"><img src="<?=$Dir?>images/common/wishlist/<?=$_data->design_wishlist?>/wishlist_skin2_btn02.gif" border="0" hspace="2"></A></td>
				<td align="right" style="font-size:11px;letter-spacing:-0.5pt;line-height:15px;"><b>���Ĺ��</b> <SELECT onchange=ChangeSort(this.value) name="sort" style="font-size:11px;background-color:#404040;letter-spacing:-0.5pt;">
				<option value="date_desc"<?if($sort=="date_desc")echo" selected";?> style="color:#ffffff;">�ֱٵ�ϼ�</option>
				<option value="marks_desc"<?if($sort=="marks_desc")echo" selected";?> style="color:#ffffff;">���ſ켱������</option>
				<option value="price_desc"<?if($sort=="price_desc")echo" selected";?> style="color:#ffffff;">�������ݼ�</option>
				<option value="price"<?if($sort=="price")echo" selected";?> style="color:#ffffff;">�������ݼ�</option>
				<option value="name"<?if($sort=="name")echo" selected";?> style="color:#ffffff;">��ǰ���</option>
				</SELECT> <SELECT onchange=ChangeListnum(this.value) name="listnum" style="font-size:11px;background-color:#404040;letter-spacing:-0.5pt;">
				<option value="10"<?if($listnum==10)echo" selected";?> style="color:#ffffff;">10���� ����</option>
				<option value="20"<?if($listnum==20)echo" selected";?> style="color:#ffffff;">20���� ����</option>
				<option value="30"<?if($listnum==30)echo" selected";?> style="color:#ffffff;">30���� ����</option>
				<option value="40"<?if($listnum==40)echo" selected";?> style="color:#ffffff;">40���� ����</option>
				<option value="50"<?if($listnum==50)echo" selected";?> style="color:#ffffff;">50���� ����</option>
				</SELECT></td>
			</tr>
			</table>
			</td>
		</tr>
		<tr>
			<td height="1" bgcolor="#DDDDDD"></td>
		</tr>
<?
		$tmp_sort=explode("_",$sort);
		$sql = "SELECT a.opt1_idx,a.opt2_idx,a.optidxs,b.productcode,b.productname,b.sellprice,b.sellprice as realprice, ";
		$sql.= "b.reserve,b.reservetype,b.addcode,b.tinyimage,b.option_price,b.option_quantity,b.option1,b.option2, ";
		$sql.= "b.etctype,a.wish_idx,a.marks,a.memo,b.selfcode,b.assembleuse,b.package_num FROM tblwishlist a, tblproduct b ";
		$sql.= "LEFT OUTER JOIN tblproductgroupcode c ON b.productcode=c.productcode ";
		$sql.= $qry." ";
		if($tmp_sort[0]=="date") $sql.= "ORDER BY a.date ".$tmp_sort[1]." ";
		else if($tmp_sort[0]=="marks") $sql.= "ORDER BY a.marks ".$tmp_sort[1]." ";
		else if($tmp_sort[0]=="price") $sql.= "ORDER BY b.sellprice ".$tmp_sort[1]." ";
		else if($tmp_sort[0]=="name") $sql.= "ORDER BY b.productname ".$tmp_sort[1]." ";
		else $sql.= "ORDER BY a.date DESC ";
		$sql.= "LIMIT " . ($setup[list_num] * ($gotopage - 1)) . ", " . $setup[list_num];
		$result=mysql_query($sql,get_db_conn());
		$cnt=0;
		while($row=mysql_fetch_object($result)) {
			$row->quantity=1;

			if(ereg("^(\[OPTG)([0-9]{4})(\])$",$row->option1)) {
				$optioncode = substr($row->option1,5,4);
				$row->option1="";
				$row->option_price="";
				if($row->optidxs!="") {
					$tempoptcode = substr($row->optidxs,0,-1);
					$exoptcode = explode(",",$tempoptcode);

					$sqlopt = "SELECT * FROM tblproductoption WHERE option_code='".$optioncode."' ";
					$resultopt = mysql_query($sqlopt,get_db_conn());
					if($rowopt = mysql_fetch_object($resultopt)){
						$optionadd = array (&$rowopt->option_value01,&$rowopt->option_value02,&$rowopt->option_value03,&$rowopt->option_value04,&$rowopt->option_value05,&$rowopt->option_value06,&$rowopt->option_value07,&$rowopt->option_value08,&$rowopt->option_value09,&$rowopt->option_value10);
						$opti=0;
						$optvalue="";
						$option_choice = $rowopt->option_choice;
						$exoption_choice = explode("",$option_choice);
						while(strlen($optionadd[$opti])>0){
							if($exoption_choice[$opti]==1 && $exoptcode[$opti]==0){
								$delsql = "DELETE FROM tblbasket WHERE tempkey='".$_ShopInfo->getTempkey()."' ";
								$delsql.= "AND productcode='".$row->productcode."' ";
								$delsql.= "AND opt1_idx='".$row->opt1_idx."' AND opt2_idx='".$row->opt2_idx."' ";
								$delsql.= "AND optidxs='".$row->optidxs."' ";
								mysql_query($delsql,get_db_conn());
							}
							if($exoptcode[$opti]>0){
								$opval = str_replace('"','',explode("",$optionadd[$opti]));
								$optvalue.= ", ".$opval[0]." : ";
								$exop = str_replace('"','',explode(",",$opval[$exoptcode[$opti]]));
								if ($exop[1]>0) $optvalue.=$exop[0]."(<font color=\"#FF3C00\">+".$exop[1]."��</font>)";
								else if($exop[1]==0) $optvalue.=$exop[0];
								else $optvalue.=$exop[0]."(<font color=\"#FF3C00\">".$exop[1]."��</font>)";
								$row->realprice+=($row->quantity*$exop[1]);
							}
							$opti++;
						}
						$optvalue = substr($optvalue,1);
					}
				}
			} else {
				$optvalue="";
			}

			if (strlen($row->option_price)==0) {
				$price = $row->realprice;
				$tempreserve = getReserveConversion($row->reserve,$row->reservetype,$row->sellprice,"N");
				$sellprice=$row->sellprice;
			} else if (strlen($row->opt1_idx)>0) {
				$option_price = $row->option_price;
				$pricetok=explode(",",$option_price);
				$priceindex = count($pricetok);
				$price = $pricetok[$row->opt1_idx-1]*$row->quantity;
				$tempreserve = getReserveConversion($row->reserve,$row->reservetype,$pricetok[$row->opt1_idx-1],"N");
				$sellprice=$pricetok[$row->opt1_idx-1];
			}

			$bankonly_html = ""; $setquota_html = "";
			if (strlen($row->etctype)>0) {
				$etctemp = explode("",$row->etctype);
				for ($i=0;$i<count($etctemp);$i++) {
					switch ($etctemp[$i]) {
						case "BANKONLY": $bankonly = "Y";
							$bankonly_html = " <img src=\"".$Dir."images/common/bankonly.gif\" border=\"0\"> ";
							break;
						case "SETQUOTA":
							if ($_data->card_splittype=="O" && $price>=$_data->card_splitprice) {
								$setquotacnt++;
								$setquota_html = " <img src=\"".$Dir."images/common/setquota.gif\" border=\"0\">";
								$setquota_html.= "</b><font color=\"#000000\" size=\"1\">(";
								$setquota_html.="3~";
								$setquota_html.= $_data->card_splitmonth.")</font>";
							}
							break;
					}
				}
			}

			$number = ($t_count-($setup[list_num] * ($gotopage-1))-$cnt);
			if($cnt>0) {
				echo "<tr>\n";
				echo "	<td height=\"1\" bgcolor=\"#DDDDDD\"></td>\n";
				echo "</tr>\n";
			}
			echo "<tr>\n";
			echo "	<td style=\"padding-bottom:3px;padding-top:3px;\">\n";
			echo "	<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
			echo "	<tr>\n";
			echo "		<td rowspan=\"3\" width=\"60\" align=\"center\" nowrap>\n";							
			echo "<A HREF=\"".$Dir.FrontDir."productdetail.php?productcode=".$row->productcode."\" onmouseover=\"window.status='��ǰ����ȸ';return true;\" onmouseout=\"window.status='';return true;\">\n";
							
			if(strlen($row->tinyimage)!=0 && file_exists($Dir.DataDir."shopimages/product/".$row->tinyimage)){
				$file_size=getImageSize($Dir.DataDir."shopimages/product/".$row->tinyimage);
				echo "<img src=\"".$Dir.DataDir."shopimages/product/".$row->tinyimage."\"";
				if($file_size[0]>=$file_size[1]) echo " width=\"60\"";
				else echo " height=60";
				echo " border=0>";
			} else {
			echo "<img src=\"".$Dir."images/no_img.gif\" width=\"60\" border=\"0\">";
			}

			echo "</A></td>\n";
			echo "		<td width=\"100%\" style=\"padding-left:5px;padding-right:5px;word-break:break-all;\"><input type=checkbox name=sels[] value=\"".$row->wish_idx."\" style=\"BORDER:none;\"><A HREF=\"".$Dir.FrontDir."productdetail.php?productcode=".$row->productcode."\" onmouseover=\"window.status='��ǰ����ȸ';return true;\" onmouseout=\"window.status='';return true;\"><font color=\"#000000\"><b>".viewproductname($row->productname,$row->etctype,$row->selfcode)."</b></font></A> ".$bankonly_html.$setquota_html."</td>\n";
			echo "		<td align=\"center\"><A HREF=\"javascript:CheckForm('',".$row->wish_idx.")\"><img src=\"".$Dir."images/common/wishlist/".$_data->design_wishlist."/wishlist_skin2_btn03.gif\" border=\"0\"></A></td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td style=\"padding-left:5px;padding-right:5px;\">\n";
			echo "		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
			echo "		<tr>\n";
			echo "			<td width=\"70%\">\n";
			if (strlen($row->option1)>0 || strlen($row->option2)>0 || strlen($optvalue)>0) {
				echo "<img src=\"".$Dir."images/common/icn_option.gif\" border=\"0\" align=absmiddle hspace=\"1\">\n";
				if (strlen($row->option1)>0 && $row->opt1_idx>0) {
					$temp = $row->option1;
					$tok = explode(",",$temp);
					$count=count($tok);
					echo $tok[0]." : ".$tok[$row->opt1_idx]."\n";
				} 
				if (strlen($row->option2)>0 && $row->opt2_idx>0) {
					$temp = $row->option2;
					$tok = explode(",",$temp);
					$count=count($tok);
					echo ",&nbsp; ".$tok[0]." : ".$tok[$row->opt2_idx]."\n";
				}
				if(strlen($optvalue)>0) {
					echo $optvalue."\n";
				} 
			}
			echo "			</td>\n";
			echo "			<td width=\"15%\" align=\"right\"><img src=\"".$Dir."images/common/won_icon.gif\" border=\"0\" align=absmiddle> <font color=\"#F02800\"><b>".number_format($price)."��</b></font></td>\n";
			echo "			<td width=\"15%\" align=\"right\"><img src=\"".$Dir."images/common/reserve_icon1.gif\" border=\"0\" align=absmiddle> ".number_format($tempreserve)."��</td>\n";
			echo "		</tr>\n";
			echo "		</table>\n";
			echo "		</td>\n";
			echo "		<td align=\"center\"><A HREF=\"javascript:CheckForm('ordernow',".$row->wish_idx.")\"><img src=\"".$Dir."images/common/wishlist/".$_data->design_wishlist."/wishlist_skin2_btn04.gif\" border=\"0\"></A></td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td style=\"padding-left:5px;padding-right:5px;\">\n";
			echo "		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
			echo "		<tr>\n";
			echo "			<td>\n";
			echo "			<SELECT name=up_marks_".$row->wish_idx." style=\"letter-spacing:-0.5pt;font-size:11px;font-family:'����,����';\">\n";

			$marks_0=$marks_1=$marks_2=$marks_3=$marks_4=$marks_5="";
			${"marks_".$row->marks}="selected";

			echo "			<OPTION value=0 ".$marks_0." style=\"color:#000000;\">���ſ켱����</OPTION>\n";
			echo "			<OPTION value=1 ".$marks_1." style=\"color:#000000;\">��</OPTION>\n";
			echo "			<OPTION value=2 ".$marks_2." style=\"color:#000000;\">�ڡ�</OPTION>\n";
			echo "			<OPTION value=3 ".$marks_3." style=\"color:#000000;\">�ڡڡ�</OPTION>\n";
			echo "			<OPTION value=4 ".$marks_4." style=\"color:#000000;\">�ڡڡڡ�</OPTION>\n";
			echo "			<OPTION value=5 ".$marks_5." style=\"color:#000000;\">�ڡڡڡڡڡ�</OPTION></SELECT>\n";
			echo "			</td>\n";
			echo "			<td width=\"100%\" style=\"padding-left:2px;\"><INPUT type=text name=\"up_memo_".$row->wish_idx."\" value=\"".$row->memo."\" size=\"52\" maxLength=\"100\" style=\"width:100%;height:19px;border-width:1px; border-color:#dddddd; border-style:solid;\"></td>\n";
			echo "		</tr>\n";
			echo "		</table>\n";
			echo "		</td>\n";
			echo "		<td align=\"center\"><A HREF=\"javascript:SaveMemo(".$row->wish_idx.")\"><img src=\"".$Dir."images/common/wishlist/".$_data->design_wishlist."/wishlist_skin2_btn05.gif\" border=\"0\"></A></td>\n";
			echo "	</tr>\n";
			echo "	</table>\n";
			echo "	</td>\n";
			echo "</tr>\n";

			$miniq = 1; 
			if (strlen($row->etctype)>0) {
				$etctemp = explode("",$row->etctype);
				for ($i=0;$i<count($etctemp);$i++) {
					if (substr($etctemp[$i],0,6)=="MINIQ=") $miniq=substr($etctemp[$i],6);
				}
			}
			echo "<input type=hidden name=productcode_".$row->wish_idx." value=\"".$row->productcode."\">\n";
			echo "<input type=hidden name=option1_".$row->wish_idx." value=\"".$row->opt1_idx."\">\n";
			echo "<input type=hidden name=option2_".$row->wish_idx." value=\"".$row->opt2_idx."\">\n";
			echo "<input type=hidden name=opts_".$row->wish_idx." value=\"".$row->optidxs."\">\n";
			echo "<input type=hidden name=quantity_".$row->wish_idx." value=\"".$miniq."\">\n";
			echo "<input type=hidden name=assembleuse_".$row->wish_idx." value=\"".$row->assembleuse."\">\n";
			echo "<input type=hidden name=packagenum_".$row->wish_idx." value=\"".((int)$row->package_num?$row->package_num:"")."\">\n";
			$cnt++;
		}
		mysql_free_result($result);

		if($cnt==0) {
			echo "<tr><td height=\"30\" colspan=\"6\" align=\"center\">�ش系���� �����ϴ�.</td></tr>\n";
		}
?>
		<tr>
			<td height="3" background="<?=$Dir?>images/common/wishlist/<?=$_data->design_wishlist?>/wishlist_skin2_line.gif"></td>
		</tr>
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
			}

			$a_last_block = "";
			if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
				$last_block = ceil($t_count/($setup[list_num]*$setup[page_num])) - 1;
				$last_gotopage = ceil($t_count/$setup[list_num]);

				$a_last_block .= "&nbsp;&nbsp;<a href='javascript:GoPage(".$last_block.",".$last_gotopage.");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='������ ������';return true\"><FONT class=\"prlist\">[...".$last_gotopage."]</FONT></a>";

				$next_page_exists = true;
			}

			$a_next_page = "";
			if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
				$a_next_page .= "&nbsp;&nbsp;<a href='javascript:GoPage(".($nowblock+1).",".($setup[page_num]*($nowblock+1)+1).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup[page_num]." ������';return true\"><FONT class=\"prlist\">[next]</FONT></a>";

				$a_next_page = $a_next_page.$a_last_block;
			}
		} else {
			$print_page = "<FONT class=\"prlist\">1</FONT>";
		}
?>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td align="center"><?=$a_div_prev_page.$a_prev_page.$print_page.$a_next_page.$a_div_next_page?></td>
		</tr>
		</table>
		</td>
	</tr>
	</table>
	</td>
</tr>
</table>