<table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
<?if (preg_match("/^(B){1}/", $_ord->paymethod) || (preg_match("/^(V|O|Q|C|P|M){1}/", $_ord->paymethod) && strcmp($_ord->pay_flag,"0000")==0)) {?>
<tr>
	<td align="center"><img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/orderend_img.gif" border="0"></td>
</tr>
<tr>
	<td align="center" height=20></td>
</tr>
<?}?>
<tr><td height="40"></td></tr>
<tr>
	<td style="padding-left:10px;padding-right:10px;">
	<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
	<tr>
		<td style="padding-right:10px;">
		<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0" height="100%">
		<TR>
			<TD><IMG SRC="<?=$Dir?>images/common/order/<?=$_data->design_order?>/design_orderf_leftimg01.gif" border="0"></TD>
		</TR>
		<TR>
			<TD height="100%" background="<?=$Dir?>images/common/order/<?=$_data->design_order?>/design_orderf_leftimgbg.gif"></TD>
		</TR>
		<TR>
			<TD><IMG SRC="<?=$Dir?>images/common/order/<?=$_data->design_order?>/design_orderf_leftimgdown.gif" border="0"></TD>
		</TR>
		</TABLE>
		</td>
		<td width="100%">
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td>
			<table cellpadding="0" cellspacing="0" width="100%">
			<col></col>
			<col width="30"></col>
			<col width="60"></col>
			<col width="80"></col>
			<tr>
				<td height="2" colspan="4" bgcolor="#000000"></td>
			</tr>
			<tr height="30" align="center" bgcolor="#F8F8F8" style="letter-spacing:-0.5pt;">
				<td><font color="#333333"><b>��ǰ��</b></font></td>
				<td><font color="#333333"><b>����</b></font></td>
				<td><font color="#333333"><b>������</b></font></td>
				<td><font color="#333333"><b>�ֹ��ݾ�</b></font></td>
			</tr>
			<tr>
				<td height="1" colspan="4" bgcolor="#DDDDDD"></td>
			</tr>
<?
	$sql = "SELECT productcode,productname,price,reserve,opt1_name,opt2_name,tempkey,addcode,quantity,order_prmsg,selfcode,package_idx,assemble_idx,assemble_info ";
	$sql.= "FROM tblorderproduct WHERE ordercode='".$ordercode."' ORDER BY productcode ASC ";
	$result=mysql_query($sql,get_db_conn());
	$sumprice=0;
	$sumreserve=0;
	$totprice=0;
	$totreserve=0;
	$totquantity=0;
	$cnt=0;
	unset($etcdata);
	unset($prdata);
	while($row=mysql_fetch_object($result)) {
		$optvalue="";
		if(ereg("^(\[OPTG)([0-9]{3})(\])$",$row->opt1_name)) {
			$optioncode=$row->opt1_name;
			$row->opt1_name="";
			$sql = "SELECT opt_name FROM tblorderoption WHERE ordercode='".$ordercode."' AND productcode='".$row->productcode."' ";
			$sql.= "AND opt_idx='".$optioncode."' ";
			$result2=mysql_query($sql,get_db_conn());
			if($row2=mysql_fetch_object($result2)) {
				$optvalue=$row2->opt_name;
			}
			mysql_free_result($result2);
		}

		$isnot=false;
		if (substr($row->productcode,0,3)!="999" && substr($row->productcode,0,3)!="COU") {
			$no++;
			$sumreserve=$row->reserve*$row->quantity;
			$totreserve+=$sumreserve;
			$totquantity+=$row->quantity;
			$isnot=true;
		}
		if(ereg("^(COU)([0-9]{8})(X)$",$row->productcode)) {				#����
			$etcdata[]=$row;
			continue;
		} else if(ereg("^(9999999999)([0-9]{1})(X)$",$row->productcode)) {
			#99999999999X : ���ݰ����� �����ݾ׿��� �߰�����/����
			#99999999998X : ����ũ�� ������ ������
			#99999999997X : �ΰ���(VAT)
			#99999999990X : ��ǰ��ۺ�
			$etcdata[]=$row;
			continue;
		} else {															#��¥��ǰ
			$prdata[]=$row;
		}

		$sumprice=$row->price*$row->quantity;
		$totprice+=$sumprice;
		echo "<tr ".($sumprice<0?"height=\"22\" bgcolor=\"#F2FAFF\"":"height=\"26\"").">\n";
		echo "	<td ".($sumprice<0?"align=\"right\" style=\"word-break:break-all;\"":"style=\"padding-left:5px;word-break:break-all;\"").">";
		echo ($sumprice<0?"<font color=\"#0000FF\"><b>".viewselfcode($row->productname,$row->selfcode)."</B></font><b>&nbsp:&nbsp;</b>":"<font color=\"#000000\"><B>".viewselfcode($row->productname,$row->selfcode)."</B></font>");
		echo "	</td>\n";
		echo "	<td align=\"center\"><font color=\"#000000\">".($isnot==true?$row->quantity:"&nbsp;")."</font></td>\n";
		echo "	<td align=\"right\" style=\"padding-right:5px\"><font color=\"#000000\">".($isnot==true?number_format($sumreserve)."��":"&nbsp;")."</font></td>\n";
		echo "	<td align=\"right\" style=\"padding-right:5px\"><font color=\"#FF3C00\"><b>".number_format($sumprice)."��</b></font></td>\n";
		echo "</tr>\n";
		if(strlen($row->opt1_name)>0 || strlen($row->opt2_name)>0 || strlen($optvalue)>0 || strlen(str_replace("","",str_replace(":","",str_replace("=","",$row->assemble_info))))>0) {
			if(strlen($row->opt1_name)>0 || strlen($row->opt2_name)>0 || strlen($optvalue)>0) {
				echo "<tr>\n";
				echo "	<td colspan=\"4\" style=\"padding:5px;padding-top:0px;word-break:break-all;\">";
				if(strlen($row->addcode)>0) echo "Ư¡ : ".$row->addcode."&nbsp;&nbsp;";
				if(strlen($row->opt1_name)>0) echo " ".$row->opt1_name." ";
				if(strlen($row->opt2_name)>0) echo ", ".$row->opt2_name." ";
				if(strlen($optvalue)>0) echo $optvalue;
				echo "	</td>\n";
				echo "</tr>\n";
				$row->addcode="";
			}
			if(strlen(str_replace("","",str_replace(":","",str_replace("=","",$row->assemble_info))))>0) {
				$assemble_infoall_exp = explode("=",$row->assemble_info);

				if($row->package_idx>0 && strlen(str_replace("","",str_replace(":","",$assemble_infoall_exp[0])))>0) {
					echo "<tr>\n";
					echo "	<td colspan=\"4\" style=\"padding:5px;padding-top:0px;word-break:break-all;\">";
					if(strlen($row->addcode)>0) echo "Ư¡ : ".$row->addcode."&nbsp;&nbsp;";
					$package_info_exp = explode(":", $assemble_infoall_exp[0]);
					if(strlen($package_info_exp[3])>0) echo "��Ű������ : <a href=\"javascript:setPackageShow('packageidx".$cnt."');\">".$package_info_exp[3]."(<font color=#FF3C00>+".number_format($package_info_exp[2])."��</font>)</a>";
					$productname_package_list_exp = explode("",$package_info_exp[1]);
					echo "	<table border=0 width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\n";
					echo "	<tr id=\"packageidx".$cnt."\" style=\"display:none;\">\n";
					if(count($productname_package_list_exp)>0 && strlen($productname_package_list_exp[0])>0) {
						echo "		<td width=\"50\" valign=\"top\" style=\"padding-left:12px;\" nowrap><font color=\"#FF7100\" style=\"line-height:10px;\">��<br>����<b>��</b></font></td>\n";
						echo "		<td width=\"100%\">\n";
						echo "		<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left:1px #DDDDDD solid;border-top:1px #DDDDDD solid;border-right:1px #DDDDDD solid;\">\n";
						
						for($i=0; $i<count($productname_package_list_exp); $i++) {
							echo "		<tr>\n";
							echo "			<td bgcolor=\"#FFFFFF\" style=\"border-bottom:1px #DDDDDD solid;\">\n";
							echo "			<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
							echo "			<col width=\"\"></col>\n";
							echo "			<col width=\"120\"></col>\n";
							echo "			<tr>\n";
							echo "				<td style=\"padding:4px;word-break:break-all;\"><font color=\"#000000\">".$productname_package_list_exp[$i]."</font>&nbsp;</td>\n";
							echo "				<td align=\"center\" style=\"padding:4px;border-left:1px #DDDDDD solid;\">�� ��ǰ 1���� ����1��</td>\n";
							echo "			</tr>\n";
							echo "			</table>\n";
							echo "			</td>\n";
							echo "		</tr>\n";
						}
						echo "		</table>\n";
						echo "		</td>\n";
					} else {
						echo "		<td width=\"50\" valign=\"top\" style=\"padding-left:12px;\" nowrap><font color=\"#FF7100\" style=\"line-height:10px;\">��<br>����<b>��</b></font></td>\n";
						echo "		<td width=\"100%\">\n";
						echo "		<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left:1px #DDDDDD solid;border-top:1px #DDDDDD solid;border-right:1px #DDDDDD solid;\">\n";
						echo "		<tr>\n";
						echo "			<td bgcolor=\"#FFFFFF\" style=\"border-bottom:1px #DDDDDD solid;padding:4px;word-break:break-all;\"><font color=\"#000000\">������ǰ�� �������� �ʴ� ��Ű��</font></td>\n";
						echo "		</tr>\n";
						echo "		</table>\n";
						echo "		</td>\n";
					}
					echo "	</tr>\n";
					echo "	</table>\n";
					echo "	</td>\n";
					echo "</tr>\n";
					$row->addcode="";
				}
				if($row->assemble_idx>0 && strlen(str_replace("","",str_replace(":","",$assemble_infoall_exp[1])))>0) { 
					echo "<tr>\n";
					echo "	<td colspan=\"4\" style=\"padding:5px;padding-top:0px;padding-left:5px;\"><nobr>";
					if(strlen($row->addcode)>0) echo "Ư¡ : ".$row->addcode."<br>";
					if($row->assemble_idx>0 && strlen(str_replace("","",str_replace(":","",$assemble_infoall_exp[1])))>0) {
						echo "<table border=0 width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\n";
						echo "<tr>\n";
						echo "	<td width=\"50\" valign=\"top\" style=\"padding-left:5px;\" nowrap><font color=\"#FF7100\" style=\"line-height:10px;\">��<br>����<b>��</b></font></td>\n";
						echo "	<td width=\"100%\">\n";
						echo "	<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left:1px #DDDDDD solid;border-top:1px #DDDDDD solid;border-right:1px #DDDDDD solid;\">\n";
						
						$assemble_info_exp = explode(":", $assemble_infoall_exp[1]);

						if(count($assemble_info_exp)>2) {
							$assemble_productname_exp = explode("", $assemble_info_exp[1]);
							$assemble_sellprice_exp = explode("", $assemble_info_exp[2]);

							for($k=0; $k<count($assemble_productname_exp); $k++) {
								echo "	<tr>\n";
								echo "		<td bgcolor=\"#FFFFFF\" style=\"border-bottom:1px #DDDDDD solid;\">\n";
								echo "		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
								echo "		<col width=\"\"></col>\n";
								echo "		<col width=\"80\"></col>\n";
								echo "		<col width=\"120\"></col>\n";
								echo "		<tr>\n";
								echo "			<td style=\"padding:4px;word-break:break-all;\"><font color=\"#000000\">".$assemble_productname_exp[$k]."</font>&nbsp;</td>\n";
								echo "			<td align=\"right\" style=\"padding:4px;border-left:1px #DDDDDD solid;border-right:1px #DDDDDD solid;\"><font color=\"#000000\">".number_format((int)$assemble_sellprice_exp[$k])."��</font></td>\n";
								echo "			<td align=\"center\" style=\"padding:4px;\">�� ��ǰ 1���� ����1��</td>\n";
								echo "		</tr>\n";
								echo "		</table>\n";
								echo "		</td>\n";
								echo "	</tr>\n";
							}
						}
						echo "	</table>\n";
						echo "	</td>\n";
						echo "</tr>\n";
						echo "</table>\n";
					}
					echo "	</td>\n";
					echo "</tr>\n";
					$row->addcode="";
				}
			}
		} else if(strlen($row->addcode)>0) {
			echo "<tr>\n";
			echo "	<td colspan=\"4\" style=\"padding-left:5px;word-break:break-all;\">";
			if(strlen($row->addcode)>0) echo "Ư¡ : ".$row->addcode;
			echo "	</td>\n";
			echo "</tr>\n";
		}
		if($sumprice>=0) {
			echo "<tr><td colspan=\"4\" height=\"1\" bgcolor=\"#DDDDDD\"></td></tr>\n";
		}
	}
	mysql_free_result($result);
?>
			<tr height="26" bgcolor="#FBFBFB">
				<td colspan="4" align="right" style="padding-right:5px;"><font color="#000000"><B>�հ�&nbsp:&nbsp;</b></font><font color="#FF3C00"><b><?=number_format($totprice)?>��</b></font></td>
			</tr>
			<tr><td colspan="4" height="1" bgcolor="#DDDDDD"></td></tr>
			</table>
			</td>
		</tr>
		<tr>
			<td height="10"></td>
		</tr>
		<TR>
			<TD><IMG src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/orderend_stext04.gif" border="0" vspace="3"></TD>
		</TR>
		<tr>
			<td height="2"></td>
		</tr>
		<tr>
			<td>
			<table cellpadding="0" cellspacing="0" width="100%">
			<col width="90"></col>
			<col width="190"></col>
			<col width="60"></col>
			<col width="40"></col>
			<col></col>
			<tr>
				<td height="2" colspan="5" bgcolor="#000000"></td>
			</tr>
			<tr height="30" align="center" bgcolor="#F8F8F8" style="letter-spacing:-0.5pt;">
				<td><font color="#333333"><b>�׸�</b></font></td>
				<td><font color="#333333"><b>����</b></font></td>
				<td><font color="#333333"><b>�ݾ�</b></font></td>
				<td><font color="#333333"><b>������</b></font></td>
				<td><font color="#333333"><b>�ش��ǰ��</b></font></td>
			</tr>
<?
	$etcprice=0;
	$etcreserve=0;
	for($i=0;$i<count($etcdata);$i++) {
		echo "<tr><td colspan=\"5\" height=\"1\" bgcolor=\"#DDDDDD\"></td></tr>\n";
		if(ereg("^(COU)([0-9]{8})(X)$",$etcdata[$i]->productcode)) {				#����
			$etcprice+=$etcdata[$i]->price;
			$etcreserve+=$etcdata[$i]->reserve;
			echo "<tr height=\"25\">\n";
			echo "	<td align=\"center\"><b>���� ���</b></td>\n";
			echo "	<td style=\"padding-left:5px;word-break:break-all;\">".$etcdata[$i]->productname."</td>\n";
			echo "	<td align=\"right\" style=\"padding-right:5px;\"><font color=\"#000000\">".($etcdata[$i]->price!=0?number_format($etcdata[$i]->price)."��":"&nbsp;")."</font></td>\n";
			echo "	<td align=\"right\" style=\"padding-right:5px;\"><font color=\"#FF3C00\"><b>".($etcdata[$i]->reserve!=0?number_format($etcdata[$i]->reserve)."��":"&nbsp;")."</b></font></td>\n";
			echo "	<td style=\"padding-right:5px;word-break:break-all;\" align=\"right\"><font color=\"#000000\"><B>".$etcdata[$i]->order_prmsg."</b></font></td>\n";
			echo "</tr>\n";
		} else if(ereg("^(9999999999)([0-9]{1})(X)$",$etcdata[$i]->productcode)) {
			#99999999999X : ���ݰ����� �����ݾ׿��� �߰�����/����
			#99999999998X : ����ũ�� ������ ������
			#99999999997X : �ΰ���(VAT)
			#99999999990X : ��ǰ��ۺ�
			if($etcdata[$i]->productcode=="99999999999X") {
				$etcprice+=$etcdata[$i]->price;
				$etcreserve+=$etcdata[$i]->reserve;
				echo "<tr height=\"25\">\n";
				echo "	<td align=\"center\"><b>���� ����</b></td>\n";
				echo "	<td style=\"padding-left:5px;word-break:break-all;\">".$etcdata[$i]->productname."</td>\n";
				echo "	<td align=\"right\" style=\"padding-right:5px;\"><font color=\"#000000\">".($etcdata[$i]->price!=0?number_format($etcdata[$i]->price)."��":"&nbsp;")."</font></td>\n";
				echo "	<td align=\"right\" style=\"padding-right:5px;\"><font color=\"#FF3C00\"><b>".($etcdata[$i]->reserve!=0?number_format($etcdata[$i]->reserve)."��":"&nbsp;")."</b></font></td>\n";
				echo "	<td style=\"padding-right:5px;word-break:break-all;\" align=\"right\"><font color=\"#000000\"><B>�ֹ��� ��ü����</b></font></td>\n";
				echo "</tr>\n";
			} else if($etcdata[$i]->productcode=="99999999998X") {
				$etcprice+=$etcdata[$i]->price;
				$etcreserve+=$etcdata[$i]->reserve;
				echo "<tr height=\"25\">\n";
				echo "	<td align=\"center\"><b>���� ������</b></td>\n";
				echo "	<td style=\"padding-left:5px;word-break:break-all;\">".$etcdata[$i]->productname."</td>\n";
				echo "	<td align=\"right\" style=\"padding-right:5px;\"><font color=\"#000000\">".($etcdata[$i]->price!=0?number_format($etcdata[$i]->price)."��":"&nbsp;")."</font></td>\n";
				echo "	<td align=\"right\" style=\"padding-right:5px;\"><font color=\"#FF3C00\"><b>".($etcdata[$i]->reserve!=0?number_format($etcdata[$i]->reserve)."��":"&nbsp;")."</b></font></td>\n";
				echo "	<td style=\"padding-right:5px;word-break:break-all;\" align=\"right\"><font color=\"#000000\"><B>�ֹ��� ��ü����</b></font></td>\n";
				echo "</tr>\n";
			} else if($etcdata[$i]->productcode=="99999999990X") {
				echo "<tr height=\"25\">\n";
				echo "	<td align=\"center\"><b>��۷�</b></td>\n";
				echo "	<td style=\"padding-left:5px;word-break:break-all;\">".$etcdata[$i]->productname."</td>\n";
				echo "	<td align=\"right\" style=\"padding-right:5px;\"><font color=\"#000000\">".($etcdata[$i]->price!=0?number_format($etcdata[$i]->price)."��":"&nbsp;")."</font></td>\n";
				echo "	<td align=\"right\" style=\"padding-right:5px;\"><font color=\"#FF3C00\"><b>".($etcdata[$i]->reserve!=0?number_format($etcdata[$i]->reserve)."��":"&nbsp;")."</b></font></td>\n";
				echo "	<td style=\"padding-right:5px;word-break:break-all;\" align=\"right\"><font color=\"#000000\"><B>".$etcdata[$i]->order_prmsg."</b></font></td>\n";
				echo "</tr>\n";
			} else if($etcdata[$i]->productcode=="99999999997X") {
				echo "<tr height=\"25\">\n";
				echo "	<td align=\"center\"><b>�ΰ���(VAT)</b></td>\n";
				echo "	<td style=\"padding-left:5px;word-break:break-all;\">".$etcdata[$i]->productname."</td>\n";
				echo "	<td align=\"right\" style=\"padding-right:5px;\"><font color=\"#000000\">".($etcdata[$i]->price!=0?number_format($etcdata[$i]->price)."��":"&nbsp;")."</font></td>\n";
				echo "	<td align=\"right\" style=\"padding-right:5px;\"></td>\n";
				echo "	<td style=\"padding-right:5px;word-break:break-all;\" align=\"right\"><font color=\"#000000\"><B>�ֹ��� ��ü ����</b></font></td>\n";
				echo "</tr>\n";
			}
		}
	}

	$dc_price=(int)$_ord->dc_price;
	$salemoney=0;
	$salereserve=0;
	if($dc_price<>0) {
		if($dc_price>0) $salereserve=$dc_price;
		else $salemoney=-$dc_price;
		if(strlen($_ord->ordercode)==20 && substr($_ord->ordercode,-1)!="X") {
			$sql = "SELECT b.group_name FROM tblmember a, tblmembergroup b ";
			$sql.= "WHERE a.id='".$_ord->id."' AND b.group_code=a.group_code AND MID(b.group_code,1,1)!='M' ";
			$result=mysql_query($sql,get_db_conn());
			if($row=mysql_fetch_object($result)) {
				$group_name=$row->group_name;
			}
			mysql_free_result($result);
		}
		echo "<tr><td colspan=\"5\" height=\"1\" bgcolor=\"#DDDDDD\"></td></tr>\n";
		echo "<tr height=\"25\">\n";
		echo "	<td align=\"center\"><b>�׷�����/����</b></td>\n";
		echo "	<td style=\"padding-left:5px;word-break:break-all;\">�׷�ȸ�� ����/���� : ".$group_name."</td>\n";
		echo "	<td align=\"right\" style=\"padding-right:5px;\"><font color=\"#000000\">".($salemoney>0?"-".number_format($salemoney)."��":"&nbsp;")."</font></td>\n";
		echo "	<td align=\"right\" style=\"padding-right:5px;\"><font color=\"#FF3C00\"><b>".($salereserve>0?"+ ".number_format($salereserve)."��":"&nbsp;")."</b></font></td>\n";
		echo "	<td style=\"padding-right:5px;word-break:break-all;\" align=\"right\"><font color=\"#000000\"><B>�ֹ��� ��ü ����</b></font></td>\n";
		echo "</tr>\n";
	}

	if($_ord->reserve>0) {
		echo "<tr><td colspan=\"5\" height=\"1\" bgcolor=\"#DDDDDD\"></td></tr>\n";
		echo "<tr height=\"25\">\n";
		echo "	<td align=\"center\"><b>������ ���</b></td>\n";
		echo "	<td style=\"padding-left:5px;word-break:break-all;\">������ ������ ".number_format($_ord->reserve)."�� ���</td>\n";
		echo "	<td align=\"right\" style=\"padding-right:5px;\"><font color=\"#000000\">-".number_format($_ord->reserve)."��</font></td>\n";
		echo "	<td align=\"right\" style=\"padding-right:5px;\"><font color=\"#FF3C00\"><b>&nbsp;</b></font></td>\n";
		echo "	<td style=\"padding-right:5px;word-break:break-all;\" align=\"right\"><font color=\"#000000\"><B>�ֹ��� ��ü ����</b></font></td>\n";
		echo "</tr>\n";
	}
	$totprice+=$_ord->deli_price-$salemoney-$_ord->reserve+$etcprice;
	$totreserve+=$salereserve+$etcreserve;

	echo "<tr><td colspan=\"5\" height=\"1\" bgcolor=\"#DDDDDD\"></td></tr>\n";
?>
			<tr height="26" bgcolor="#FBFBFB">
				<td colspan="5" align="right" style="padding-right:5px;"><font color="#000000"><B>�հ�&nbsp:&nbsp;</b></font><font color="#FF3C00"><b><?=number_format($_ord->deli_price-$salemoney-$_ord->reserve+$etcprice)?>��</b></font></td>
			</tr>
			</table>
			</td>
		</tr>
		<tr>
			<td>
			<table cellpadding="0" cellspacing="0" width="100%">
			<col></col>
			<col width="100"></col>
			<tr>
				<td colspan="2" height="2" bgcolor="#DDDDDD"></td>
			</tr>
			<tr height="30" bgcolor="#FBFBFB">
				<td align="right" style="padding-right:5px;"><font color="#000000"><B>�� �����ݾ�&nbsp:&nbsp;</b></font></td>
				<td align="right" style="padding-right:5px;"><font color="#FF3C00" style="font-size:12pt;"><b><?=number_format($_ord->price)?>��</b></font></td>
			</tr>

			<?if($totreserve>0 && substr($_ord->ordercode,-1)!="X") {?>

			<tr><td colspan="2" height="1" bgcolor="#DDDDDD"></td></tr>
			<tr height="30" bgcolor="#F2FAFF">
				<td align="right" style="padding-right:5px;"><font color="#000000"><B>�����ݾ�&nbsp:&nbsp;</b></font></td>
				<td align="right" style="padding-right:5px;"><font color="#0054A6">����� <b><?=number_format($totreserve)?>��</b></font></td>
			</tr>
			
			<?}?>

			<tr>
				<td colspan="2" height="2" bgcolor="#DDDDDD"></td>
			</tr>
			</table>
			</td>
		</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td height="35" colspan="2"></td>
	</tr>
	<tr>
		<td style="padding-right:10px;">
		<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0" height="100%">
		<TR>
			<TD><IMG SRC="<?=$Dir?>images/common/order/<?=$_data->design_order?>/design_orderf_leftimg02.gif" border="0"></TD>
		</TR>
		<TR>
			<TD height="100%" background="<?=$Dir?>images/common/order/<?=$_data->design_order?>/design_orderf_leftimgbg.gif"></TD>
		</TR>
		<TR>
			<TD><IMG SRC="<?=$Dir?>images/common/order/<?=$_data->design_order?>/design_orderf_leftimgdown.gif" border="0"></TD>
		</TR>
		</TABLE>
		</td>
		<td valign="top" height="100%">
		<table cellpadding="0" cellspacing="0" width="100%"  height="100%">
		<TR>
			<TD>
			<table cellpadding="0" cellspacing="0" width="100%" height="100%">
			<tr>
				<td bgcolor="#E9E9E9" height="3"></td>
			</tr>
			<tr>
				<td style="padding:10px;" height="100%">
<?
	if(preg_match("/^(V|C|P|M){1}/", $_ord->paymethod)) {
		$arpm=array("V"=>"�ǽð�������ü","C"=>"�ſ�ī��","P"=>"�Ÿź�ȣ - �ſ�ī��", "M"=>"�ڵ���");
		echo $arpm[substr($_ord->paymethod,0,1)];

		if ($_ord->pay_flag=="0000") {
			if(preg_match("/^(C|P){1}/", $_ord->paymethod)) {
				echo "&nbsp;�������� - ���ι�ȣ : ".$_ord->pay_auth_no." ";
			} else {
				echo "&nbsp;������ <font color=blue>����ó��</font> �Ǿ����ϴ�.";
			}
		} else if(strlen($_ord->pay_flag)>0)
			echo "&nbsp;�ŷ���� : <font color=red><b><u>".$_ord->pay_data."</u></b></font>\n";
		else
			echo "&nbsp;\n<font color=red>(���ҽ���)</font>";

		if (preg_match("/^(C|P|M){1}/", $_ord->paymethod) && $_data->card_payfee>0) echo "<br>&nbsp\n".$arpm[substr($_ord->paymethod,0,1)]." ������ ���� ���ΰ� ������ �ȵ˴ϴ�.";

	} else if (preg_match("/^(B|O|Q){1}/", $_ord->paymethod)) {
		if(preg_match("/^(B){1}/", $_ord->paymethod)) echo "������ �Ա� : <font color=#0054A6>".$_ord->pay_data."</font> <br>\n(�Ա�Ȯ���� ����� �˴ϴ�.)";
		else {
			if($_ord->pay_flag=="0000") $msg = "&nbsp\n(�Ա�Ȯ���� ����� �˴ϴ�.)";
			if(preg_match("/^(O){1}/", $_ord->paymethod)) echo "������� : <font color=#0054A6>".$_ord->pay_data."</font> <br>".$msg;
			else if(preg_match("/^(Q){1}/", $_ord->paymethod)) echo "�Ÿź�ȣ - ������� : <font color=#0054A6>".$_ord->pay_data."</font> <br>".$msg;
		}
	}
?>
				</td>
			</tr>
			<tr>
				<td bgcolor="#E9E9E9" height="3"></td>
			</tr>
			</table>
			</TD>
		</TR>
		</table>
		</td>
	</tr>
	<tr>
		<td height="35" colspan="2"></td>
	</tr>
	<tr>
		<td style="padding-right:10px;">
		<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0" height="100%">
		<TR>
			<TD><IMG SRC="<?=$Dir?>images/common/order/<?=$_data->design_order?>/design_orderf_leftimg03.gif" border="0"></TD>
		</TR>
		<TR>
			<TD height="100%" background="<?=$Dir?>images/common/order/<?=$_data->design_order?>/design_orderf_leftimgbg.gif"></TD>
		</TR>
		<TR>
			<TD><IMG SRC="<?=$Dir?>images/common/order/<?=$_data->design_order?>/design_orderf_leftimgdown.gif" border="0"></TD>
		</TR>
		</TABLE>
		</td>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%">
		<TR>
			<TD valign="top">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td bgcolor="#E9E9E9" height="3"></td>
			</tr>
			<tr>
				<td style="padding-top:15px;padding-bottom:15px"  valign="top">
				<table cellpadding="0" cellspacing="0" width="100%">
				<col width="100"></col>
				<col></col>
				<tr>
					<td><img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_skin_point.gif" border="0"><font color="#000000"><b>�̸�</b></font></td>
					<td><b><?=$_ord->sender_name?></b></td>
				</tr>
				<tr>
					<td HEIGHT="10" colspan="2" background="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_skin_line.gif"></td>
				</tr>
				<tr>
					<td><img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_skin_point.gif" border="0"><font color="#000000"><b>��ȭ��ȣ</b></font></td>
					<td><?=$_ord->sender_tel?></td>
				</tr>
				<tr>
					<td HEIGHT="10" colspan="2" background="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_skin_line.gif"></td>
				</tr>
				<tr>
					<td><img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_skin_point.gif" border="0"><font color="#000000"><b>�̸���</b></font></td>
					<td><?=$_ord->sender_email?></td>
				</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td bgcolor="#E9E9E9" height="3"></td>
			</tr>
			</table>
			</TD>
		</TR>
		</table>
		</td>
	</tr>
	<tr>
		<td height="35" colspan="2"></td>
	</tr>
	<tr>
		<td>
		<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0" height="100%">
		<TR>
			<TD><IMG SRC="<?=$Dir?>images/common/order/<?=$_data->design_order?>/design_orderf_leftimg04.gif" border="0"></TD>
		</TR>
		<TR>
			<TD height="100%" background="<?=$Dir?>images/common/order/<?=$_data->design_order?>/design_orderf_leftimgbg.gif"></TD>
		</TR>
		<TR>
			<TD><IMG SRC="<?=$Dir?>images/common/order/<?=$_data->design_order?>/design_orderf_leftimgdown.gif" border="0"></TD>
		</TR>
		</TABLE>
		</td>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%">
		<TR>
			<TD>
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td bgcolor="#E9E9E9" height="3"></td>
			</tr>
			<tr>
				<td style="padding-top:15px;padding-bottom:15px">
				<table cellpadding="0" cellspacing="0" width="100%">
				<col width="100"></col>
				<col></col>
				<tr>
					<td><img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_skin_point.gif" border="0"><font color="#000000"><b>�̸�</b></font></td>
					<td><b><?=$_ord->receiver_name?></b></td>
				</tr>
				<tr>
					<td HEIGHT="10" colspan="2" background="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_skin_line.gif"></td>
				</tr>
				<tr>
					<td><img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_skin_point.gif" border="0"><font color="#000000"><b>��ȭ��ȣ</b></font></td>
					<td><?=$_ord->receiver_tel1?></td>
				</tr>
				<tr>
					<td HEIGHT="10" colspan="2" background="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_skin_line.gif"></td>
				</tr>
				<tr>
					<td><img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_skin_point.gif" border="0"><font color="#000000"><b>�����ȭ</b></font></td>
					<td><?=$_ord->receiver_tel2?></td>
				</tr>
				<tr>
					<td HEIGHT="10" colspan="2" background="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_skin_line.gif"></td>
				</tr>
				<tr>
					<td><img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_skin_point.gif" border="0"><font color="#000000"><b>�ּ�</b></font></td>
					<td><?=$_ord->receiver_addr?></td>
				</tr>
				<tr>
					<td HEIGHT="10" colspan="2" background="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_skin_line.gif"></td>
				</tr>
				<tr>
					<td><img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_skin_point.gif" border="0"><font color="#000000"><b>�ֹ���û����</b></font></td>
					<td valign="top"><?=ereg_replace("\r\n","<br>",$_ord->order_msg)?></td>
				</tr>
<?
	for($i=0;$i<count($prdata);$i++) {
		if(strlen($prdata[$i]->order_prmsg)>0) {
			echo "<tr><td HEIGHT=\"10\" colspan=\"2\" background=\"".$Dir."images/common/order/".$_data->design_order."/order_skin_line.gif\"></td></tr>";
			echo "<tr>\n";
			echo "	<td><img src=\"".$Dir."images/common/order/".$_data->design_order."/order_skin_point.gif\" border=\"0\"><font color=\"#000000\"><b>�ֹ��޼���</b></font></td>\n";
			echo "	<td style=\"word-break:break-all;\">\n";
			echo "	<FONT COLOR=\"#000000\"><B>��ǰ�� :</B></FONT> ".$prdata[$i]->productname."<BR>\n";
			echo "<textarea style=\"width:95%;height:40;overflow-x:hidden;overflow-y:auto;\" readonly>".$prdata[$i]->order_prmsg."</textarea>\n";
			echo "	</td>\n";
			echo "</tr>\n";
		}
	}
?>
				</table>
			</tr>
			<tr>
				<td bgcolor="#E9E9E9" height="3"></td>
			</tr>
			</table>
			</TD>
		</TR>
		</table>
		</td>
	</tr>
	<tr>
		<td height="35" colspan="2"></td>
	</tr>
<?if($_ord->order_type == "p"){?>
	<tr>
		<td></td>
		<td>
			<table border="0" cellpadding="0" cellspacing="6" width="100%" bgcolor="#E9E9E9" style="table-layout:fixed">
			<tr>
				<td bgcolor=#ffffff style="padding:15px;">
				<table border="0" cellpadding="0" cellspacing="0">
				<tr><td><b>���� �޴� ģ������ ������ ���۵Ǿ����ϴ�.</b></td>
				</tr>
				<tr>
					<td HEIGHT="10" background="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_skin_line.gif"></td>
				</tr>
				<tr>
					<td><?=$_ord->receiver_email?></td>
				</tr>
				<tr>
					<td HEIGHT="10" background="<?=$Dir?>images/common/order/<?=$_data->design_order?>/order_skin_line.gif"></td>
				</tr>
				<tr>
					<td><textarea name="in_message" rows="4" cols="70" style="border-width:1px; border-color:#CCCCCC; border-style:solid;width:500px" readonly><?=$_ord->receiver_message?></textarea></td>
				</tr>
				</table>
				</td>
			</tr>
			</table>
		</td>
	</tr>
	<TR>
		<td height="20" colspan="2"></td>
	</TR>
<?}?>
	<tr>
		<td></td>
		<td>
		<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0" width="100%" height="100%">
		<tr>
			<td>
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td bgcolor="#E9E9E9" height="3"></td>
			</tr>
			<tr>
				<td style="padding:10px;">
					<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td style="width:50%">
<?
	if(preg_match("/^(B){1}/", $_ord->paymethod) || (preg_match("/^(V|O|Q|C|P|M){1}/", $_ord->paymethod) && strcmp($_ord->pay_flag,"0000")==0)) {
		if(strlen($_ord->ordercode)==20 && substr($_ord->ordercode,-1)!="X") {
			echo "<font color=\"#FF6600\"><b>".$_ord->sender_name."���� �ֹ��� �Ϸ�Ǿ����ϴ�.</b></font><br><br>\n";
			if ($totreserve>0) echo "������ ��ǰ ���Կ� ���� ������ <font color=\"#FF6600\"><b>".number_format($totreserve)."��</b></font>�� ��۰� �Բ� �ٷ� �����˴ϴ�.<br>\n";
		} else {
			echo "�ֹ��� �Ϸ�Ǿ����ϴ�.<br>\n";
			echo "������ �ֹ�Ȯ�� ��ȣ�� <font color=0000a0><b>".substr($_ord->id,1,6)."</b></font>�Դϴ�.<br>\n";
		}
	} else if (preg_match("/^(V|O|Q|C|P|M)$/", $_ord->paymethod) && strcmp($_ord->pay_flag,"0000")!=0 ) {
		echo "<font color=red size=3><b>�ֹ��� ���еǾ����ϴ�.</b></font><br>\n";
	}

	if(preg_match("/^(B){1}/", $_ord->paymethod) || (preg_match("/^(O|Q){1}/", $_ord->paymethod) && $_ord->pay_flag=="0000")) {
		echo "�Աݹ���� �������Ա��� ��� ���¹�ȣ�� �޸��ϼ���.<br>���� �Ա�Ȯ�� �� �ٷ� �����帳�ϴ�.<br><br>\n";
	} else if(preg_match("/^(C|P|M){1}/", $_ord->paymethod) && strcmp($_ord->pay_flag,"0000")==0) {
		echo "���� Ȯ�� �� �ٷ� �����帳�ϴ�.<br><br>\n";
	}

	if ((preg_match("/^(B){1}/", $_ord->paymethod) || (preg_match("/^(V|O|Q|C|P|M){1}/", $_ord->paymethod) && strcmp($_ord->pay_flag,"0000")==0)) && strlen($_data->orderend_msg)>0) {
		echo ereg_replace("\n","<br>",$_data->orderend_msg);
		echo "<br>\n";
	}
?>
						</td>
						<td style="width:50%; text-align:right">
						<img src="/lib/barcode.php?str=<?=$_ord->ordercode?>" >
						</td>
						</tr>
						</table>
				</td>
			</tr>
			<tr>
				<td bgcolor="#E9E9E9" height="3"></td>
			</tr>
			</table>
			</td>
		</tr>
		<TR>
			<TD height="30"></TD>
		</TR>
		<TR>
			<TD align="center"><a href="javascript:OrderDetailPrint('<?=$_ord->ordercode?>')"><img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/btn_orderend1.gif" border="0"></a><a href="<?=$Dir.MainDir?>main.php"><img src="<?=$Dir?>images/common/order/<?=$_data->design_order?>/btn_orderend2.gif" border="0"></a></TD>
		</TR>
		<tr>
			<td height="20"></td>
		</tr>
		</table>
		</td>
	</tr>
	</table>
	</td>
</tr>
</table>