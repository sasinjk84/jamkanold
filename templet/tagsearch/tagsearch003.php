<TABLE cellSpacing=0 cellPadding=0 width="95%" align="center">
<TR>
	<TD align="right"><A HREF="javascript:void(0)" onclick="tagCls.tagList()" onmouseover="window.status='�ֱ��α��±�';return true;" onmouseout="window.status='';return true;"><img src="<?=$Dir?>images/common/tagsearch/<?=$_data->design_tagsearch?>/tag_icon.gif" width="89" height="18" border="0" vspace="4"></a></TD>
</TR>
<TR>
	<TD>




		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td><img src="<?=$Dir?>images/common/tag/<?=$_data->design_tag?>/tag_img03_head.gif"  border="0"></td>
			<td background="<?=$Dir?>images/common/tag/<?=$_data->design_tag?>/tag_img03_bg.gif" align="center">
			<table align="center" cellpadding="0" cellspacing="0" width="400">
				<tr>
					<td><img src="<?=$Dir?>images/common/tagsearch/<?=$_data->design_tagsearch?>/tag_text02.gif" border="0"></td>
					<td width="100%" align="center"><INPUT type=text name=searchtagname value="<?=$tagname?>" class=input style="background-color:rgb(254,255,252); width:300px;" size="42" maxlength=50 onkeydown="CheckKeyTagSearch()" onkeyup="check_tagvalidate(event, this);"></td>
					<td style="padding-left:5"><A HREF="javascript:void(0)" onclick="tagCls.searchProc()" onmouseover="window.status='�±װ˻�';return true;" onmouseout="window.status='';return true;"><img src="<?=$Dir?>images/common/tagsearch/<?=$_data->design_tagsearch?>/tag_btn03.gif" width="73" height="23" border="0"></a></td>
				</tr>
			</table>
			</td>
			<td><img src="<?=$Dir?>images/common/tag/<?=$_data->design_tag?>/tag_img03_tail.gif"  border="0"></td>
		</tr>
		</table>




	</TD>
</TR>
<TR>
	<TD>&nbsp;</TD>
</TR>
<TR>
	<TD align="center">
	<?if($t_count>0){?>
	<FONT style="font-size:16px;color:#000000"><B>"<FONT COLOR="#FF5400"><?=$tagname?></FONT>"(��)�� �±װ� ��ϵǾ� �ִ� ��ǰ�Դϴ�.</B></FONT>
	<?}else{?>
	<FONT style="font-size:16px;color:#000000"><B>"<FONT COLOR="#FF5400"><?=$tagname?></FONT>"(��)�� �±װ� ��ϵ� ��ǰ�� <FONT COLOR="#FF5400">�����ϴ�.</FONT></B></FONT>
	<?}?>
	</TD>
</TR>
<tr><td height=20></td></tr>

<?if($t_count<=0){?>

<TR>
	<TD>
	<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
	<TR>
		<TD><IMG SRC="<?=$Dir?>images/common/tagsearch/<?=$_data->design_tagsearch?>/tag_t01_head.gif" WIDTH="14" HEIGHT=8 ALT=""></TD>
		<TD width="100%" background="<?=$Dir?>images/common/tagsearch/<?=$_data->design_tagsearch?>/tag_t01_bg.gif"></TD>
		<TD><img src="<?=$Dir?>images/common/tagsearch/<?=$_data->design_tagsearch?>/tag_t01_tail.gif" width="14" height="8" border="0"></TD>
	</TR>
	<TR>
		<TD height="31" background="<?=$Dir?>images/common/tagsearch/<?=$_data->design_tagsearch?>/tag_t01bg_head.gif"><img src="<?=$Dir?>images/common/tagsearch/<?=$_data->design_tagsearch?>/tag_t01bg_head.gif" width="14" height="31" border="0"></TD>
		<TD height="31" style="padding-top:6pt; padding-right:15pt; padding-bottom:3pt; padding-left:15pt;" width="100%">
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td width="5%" align="center"><img src="<?=$Dir?>images/common/tagsearch/<?=$_data->design_tagsearch?>/tag_icon1.gif" width="55" height="55" border="0"></td>
			<td width="95%" style="line-height:120%;"><span style="font-size:8pt; letter-spacing:-0.5pt;"><font face="����">- �±׾ <b>�ٸ���</b> �ԷµǾ� �ִ��� Ȯ���� ������.<br>- ���� �Ϲ������±׾� �� <b>�ٸ� �±׾�</b>�� �˻��� ������.<br>- <b>���� ����</b> �˻��� ������.<br>- �±׾ Ư����ȣ�� ���ԵǾ� �ִٸ� <b>Ư����ȣ�� ����</b> �˻��� ������.</font></span></td>
		</tr>
		</table>
		</TD>
		<TD height="31" background="<?=$Dir?>images/common/tagsearch/<?=$_data->design_tagsearch?>/tag_t01bg_tail.gif"><img src="<?=$Dir?>images/common/tagsearch/<?=$_data->design_tagsearch?>/tag_t01bg_tail.gif" width="14" height="31" border="0"></TD>
	</TR>
	<TR>
		<TD><IMG SRC="<?=$Dir?>images/common/tagsearch/<?=$_data->design_tagsearch?>/tag_t02_head.gif" WIDTH="14" HEIGHT=16 ALT=""></TD>
		<TD width="100%" background="<?=$Dir?>images/common/tagsearch/<?=$_data->design_tagsearch?>/tag_t02_bg.gif"></TD>
		<TD><img src="<?=$Dir?>images/common/tagsearch/<?=$_data->design_tagsearch?>/tag_t02_tail.gif" width="14" height="16" border="0"></TD>
	</TR>
	</TABLE>
	</TD>
</TR>

<?}else{?>

<TR>
	<TD>
	<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td colspan="3">
		<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
		<TR>
			<TD colspan=3 width="100%" height=26 bgcolor="#ffffff"><font color="000000"><span style="font-size:8pt;">*�� <b><?=$t_count?>��</b>�� ��ǰ�� �����մϴ�.</span></font></TD>
		</TR>
		<TR>
			<TD colspan=3 bgcolor=#dddddd height=2></TD>
		</TR>
		</TABLE>
		</td>
	</tr>
	<tr>

		<td style="padding:5pt;" width="100%" valign="top">
		<table cellpadding="0" cellspacing="0" width="100%">
<?
		$sql = "SELECT a.productcode, a.productname, a.sellprice, a.consumerprice, a.quantity, a.reserve, a.reservetype, a.production, ";
		$sql.= "a.addcode,a.tinyimage,a.date,a.etctype,a.option1,a.option2,a.option_price,a.tag,a.tagcount,a.selfcode ";
		$sql.= "FROM tblproduct AS a ";
		$sql.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
		$sql.= $qry." ";
		$sql.= "AND (a.group_check='N' OR b.group_code='".$_ShopInfo->getMemgroup()."') ";
		$sql.= "ORDER BY a.tagcount DESC ";
		$sql.= "LIMIT " . ($setup[list_num] * ($gotopage - 1)) . ", " . $setup[list_num];
		$result=mysql_query($sql,get_db_conn());
		$i=0;
		while($row=mysql_fetch_object($result)) {
			$number = ($t_count-($setup[list_num] * ($gotopage-1))-$i);

			if($i>0) {
				echo "<tr>\n";
				echo "	<td width=651 colspan=2 height=1 background=\"".$Dir."images/common/tagsearch/".$_data->design_tagsearch."/dotline.gif\"></td>\n";
				echo "</tr>\n";
			}

			echo "<tr>\n";
			echo "	<td width=15% height=120 align=center>";
			if (strlen($row->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$row->tinyimage)==true) {
				echo "<A HREF=\"".$Dir.FrontDir."productdetail.php?productcode=".$row->productcode."\" onmouseover=\"window.status='��ǰ����ȸ';return true;\" onmouseout=\"window.status='';return true;\"><img src=\"".$Dir.DataDir."shopimages/product/".urlencode($row->tinyimage)."\" border=0 ";
				$width = getimagesize($Dir.DataDir."shopimages/product/".$row->tinyimage);
				if ($width[1]>90) echo "height=90 ";
			} else {
				echo "<img src=\"".$Dir."images/no_img.gif\" border=0 align=center";
			}
			echo "	></A></td>\n";
			echo "	<td width=85%>\n";
			echo "	<table cellpadding=0 cellspacing=0 width=100%>\n";
			echo "	<tr>\n";
			echo "		<td height=47>\n";
			echo "		<table cellpadding=0 cellspacing=0>\n";
			echo "		<tr>\n";
			echo "			<td>";
			echo "			<A HREF=\"".$Dir.FrontDir."productdetail.php?productcode=".$row->productcode."\" onmouseover=\"window.status='��ǰ����ȸ';return true;\" onmouseout=\"window.status='';return true;\">";
			if(strlen($row->addcode)>0) echo "<font class=praddcode>[".$row->addcode."]</font><br>";
			echo "		<FONT class=\"prname\">".viewproductname($row->productname,$row->etctype,$row->selfcode)."</FONT>";
			echo "			</a>\n";
			echo "			</td>\n";
			echo "		</tr>\n";
			echo "		</table>\n";
			echo "		</td>\n";
			echo "		<td height=47 width=140>\n";
			echo "		<table cellpadding=0 cellspacing=0 width=100%>\n";
			echo "		<tr>\n";
			echo "			<td class=\"prconsumerprice\"><img src=\"".$Dir."images/common/won_icon2.gif\" border=0 align=absmiddle> ".number_format($row->consumerprice)."��<br></td>\n";
			echo "		</tr>\n";
			echo "		<tr>\n";
			echo "			<td class=prprice>";
			if($dicker=dickerview($row->etctype,number_format($row->sellprice)."��",1)) {
				echo $dicker;
			} else if(strlen($_data->proption_price)==0) {
				echo "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle> ".number_format($row->sellprice)."��";
				if (strlen($row->option_price)!=0) echo "(�⺻��)";
			} else {
				echo "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle> ";
				if (strlen($row->option_price)==0) echo number_format($row->sellprice)."��";
				else echo ereg_replace("\[PRICE\]",number_format($row->sellprice),$_data->proption_price);
			}
			if ($row->quantity=="0") echo soldout();
			echo "			<br></td>\n";
			echo "		</tr>\n";
			echo "		<tr>\n";
			echo "			<td class=prreserve><img src=\"".$Dir."images/common/reserve_icon.gif\" border=0 align=absmiddle> ".number_format(getReserveConversion($row->reserve,$row->reservetype,$row->sellprice,"Y"))."��</td>\n";
			echo "		</tr>\n";
			echo "		</table>\n";
			echo "		</td>\n";
			echo "		<td height=47 width=180>\n";
			echo "		<table cellpadding=0 cellspacing=0 width=100%>\n";
			echo "		<tr>\n";
			echo "			<td width=39 align=center><A HREF=\"javascript:void(0)\" onclick=\"PrdtQuickCls.quickView('".$row->productcode."')\" onmouseover=\"window.status='�̸�����';return true;\" onmouseout=\"window.status='';return true;\"><img src=\"".$Dir."images/common/tagsearch/".$_data->design_tagsearch."/tag_listquic.gif\" width=42 height=30 border=0></a></td>\n";
			
			$wish_script="PrdtQuickCls.quickFun('".$row->productcode."','1')";

			if($row->quantity=="0")
				$basket_script="alert('��� �����ϴ�.');";
			else
				$basket_script="PrdtQuickCls.quickFun('".$row->productcode."','2')";

			echo "			<td width=39 align=center><A HREF=\"javascript:void(0)\" onclick=\"".$wish_script."\" onmouseover=\"window.status='���ø���Ʈ';return true;\" onmouseout=\"window.status='';return true;\"><img src=\"".$Dir."images/common/tagsearch/".$_data->design_tagsearch."/tag_listzim.gif\" width=43 height=30 border=0 hspace=2></a></td>\n";
			echo "			<td width=39 align=center><A HREF=\"javascript:void(0)\" onclick=\"".$basket_script."\" onmouseover=\"window.status='��ٱ���';return true;\" onmouseout=\"window.status='';return true;\"><img src=\"".$Dir."images/common/tagsearch/".$_data->design_tagsearch."/tag_listba.gif\" width=52 height=30 border=0></a></td>\n";
			echo "		</tr>\n";
			echo "		</table>\n";
			echo "		</td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td height=23 colspan=3 style=\"padding-top:4pt;\"><b><font color=\"#AF8C63\"><span style=\"font-size:8pt;\"><img src=\"".$Dir."images/common/tagsearch/".$_data->design_tagsearch."/tag_ticon.gif\" width=31 height=15 border=0 align=absmiddle></span></font><span style=\"font-size:8pt;\"><font color=\"#FE7316\">����±׼�:".$row->tagcount."��</font></span></b><span style=\"font-size:8pt;color:#FE7316\">";

			$arrtag=explode(",",$row->tag);
			$jj=0;
			for($ii=0;$ii<count($arrtag);$ii++) {
				$temptag=ereg_replace("<|>","",$arrtag[$ii]);
				if(strlen($temptag)>0) {
					$jj++;
					if($jj>1) echo ", ";
					echo " <A HREF=\"javascript:void(0)\" onclick=\"tagCls.tagSearch('".$temptag."')\" onmouseover=\"window.status='".$temptag."';return true;\" onmouseout=\"window.status='';return true;\" style=\"color:#FE7316\">".$temptag."</A>";
					if($jj>=5) break;
				}
			}

			echo "		</span></td>\n";
			echo "	</tr>\n";
			echo "	</table>\n";
			echo "	</td>\n";
			echo "</tr>\n";

			$i++;
		}
		mysql_free_result($result);

?>
		</table>
		</td>
	</tr>
		<TR>
			<TD colspan=3 bgcolor=#dddddd height=2></TD>
		</TR>
	</table>
	</TD>
</TR>
<TR>
	<TD>&nbsp;</TD>
</TR>
<TR>
	<TD>
	<table cellpadding=0 cellspacing=0 width=100%>
	<tr>
		<td width=100% class="new_font_size" align="center"><?=$a_div_prev_page.$a_prev_page.$print_page.$a_next_page.$a_div_next_page?></td>
	</tr>
	</table>
	</TD>
</TR>

<?}?>

</TABLE>
