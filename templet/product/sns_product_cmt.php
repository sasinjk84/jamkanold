<?
	$arSnsType = explode("", $_data->sns_reserve_type);

	$sAddReserve = "������ SNS ä�η� ��õ�� URL�� ���� ��ǰ�� �Ǹŵ� ���, <b><font color=\"#FE8854\">";
	$sAddReserve2 = "��õ URL�� ���� ������ �����Դ� <b><font color=\"#FE8854\">";

	if($arSnsType[0] == "A"){

		if($arSnsType[1] == "N"){
			$sAddReserve .= "��õ������ ".$_data->sns_recomreserve."��</font></b>��";
		}else{
			$sAddReserve .= "�Ǹűݾ��� ".$_data->sns_recomreserve."%</font></b>��";
		}
		if($_data->sns_memreserve >0){
			if($arSnsType[2] == "N"){
				$sAddReserve2 .= $_data->sns_memreserve."��</font></b>��";
			}else{
				$sAddReserve2 .= "���űݾ��� ".$_data->sns_memreserve."%</font></b>��";
			}
		}else{
			$sAddReserve2 ="";
		}
	}else if($arSnsType[0] == "B"){
		if($_pdata->sns_reserve1_type == "N"){
			$sAddReserve .= "��õ������ ".$_pdata->sns_reserve1."��</font></b>��";
		}else{
			$sAddReserve .= "�Ǹűݾ��� ".$_pdata->sns_reserve1."%</font></b>��";
		}
		if($_pdata->sns_reserve2 >0){
			if($_pdata->sns_reserve2_type == "N"){
				$sAddReserve2 .= $_pdata->sns_reserve2."��</font></b>��";
			}else{
				$sAddReserve2 .= "���űݾ��� ".$_pdata->sns_reserve2."%</font></b>��";
			}
		}else{
			$sAddReserve2 ="";
		}
	}
	$sAddReserve .= " ���Բ� ���������� �����ص帳�ϴ�.";
	if($sAddReserve2) $sAddReserve2 .= " ���������� ������ �帳�ϴ�.";

	$sProductCmt = "
		<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">
			<tr><td height=\"16\"></td></tr>
			<tr>
				<td class=\"table_td\">".$sAddReserve."<br>".$sAddReserve2."</td>
			</tr>
			<tr><td height=20></td></tr>
			<tr>
				<td>
					<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">
						<tr>
							<td class=\"table_td\">* �� <b><span id=\"snsCmtTot\">0</span></b>�� ����</td>
							<td class=\"table_td\" align=\"right\"><!--<a href=\"../front/sns_guide.php\"><IMG SRC=\"../images/design/detail_btn_sns.gif\" WIDTH=140 HEIGHT=27 ALT=\"\" vspace=\"3\" border=\"0\"></a>--></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" bgcolor=\"#F4F4F4\">
						<tr>
							<td style=\"padding:20px;\" align=\"center\">
								<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" bgcolor=\"#F4F4F4\">
									<tr>
										<!--td width=\"48\" align=\"center\" valign=\"top\"><IMG SRC=\"../images/design/sns_default.jpg\" WIDTH=\"48\" HEIGHT=\"48\" ALT=\"\" class=\"img\" id=\"snsThumb\" ></td-->
										<td></td>
										<td align=\"center\" valign=\"top\" width=\"100%\"><textarea name=\"comment\" id=\"comment\" onChange=\"CheckStrLen('100',this);\" onKeyUp=\"CheckStrLen('100',this);\" rows=\"5\" cols=\"50\" style=\"width:98%\" class=\"textarea_gonggu\"></textarea></td>
										<td width=\"80\" align=\"center\" valign=\"top\"><a href=\"#comment\"><IMG SRC=\"../images/design/detail_btn_snsch.gif\" WIDTH=80 HEIGHT=80 id=\"detail_btn_snsch\" ALT=\"\" onclick=\"snsReg(); this.src='/images/loader.gif'; return false;\" ></a></td>
									</tr>
									<tr>
										<td align=\"center\" valign=\"top\" colspan=\"3\" height=10></td>
									</tr>
									<tr>
										<td align=\"center\" valign=\"top\"></td>
										<td align=\"center\" valign=\"top\" colspan=\"2\">
											<table cellpadding=\"0\" cellspacing=\"0\" width=\"98%\" align=\"center\">
												<tr>
													<td width=\"145\" valign=\"top\" align=\"left\">
													".((TWITTER_ID !="TWITTER_ID")? "
													<a href=\"javascript:changeSnsInfo('t');\"><IMG SRC=\"../images/design/icon_twitter_off.gif\" WIDTH=\"25\" HEIGHT=\"25\" ALT=\"\" border=\"0\" id=\"tLoginBtn3\"></a>":"").
													((FACEBOOK_ID !="FACEBOOK_ID")? "
													<a href=\"javascript:changeSnsInfo('f');\"><IMG SRC=\"../images/design/icon_facebook_off.gif\" WIDTH=\"25\" HEIGHT=\"25\" ALT=\"\" hspace=\"4\" border=\"0\" id=\"fLoginBtn3\"></a>":"").
													"<A HREF=\"#commen\" onclick=\"CopyUrl();return false;\"><IMG SRC=\"../images/design/gonggu_order_btn06.gif\" WIDTH=28 HEIGHT=24 ALT=\"\" hspace=\"2\"></a></td>
													<td valign=\"top\"><IMG SRC=\"../images/design/detail_text_sns02.gif\" WIDTH=408 HEIGHT=67 ALT=\"\"></td>
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
			<tr><td height=20></td></tr>
			<tr>
				<td><IMG SRC=\"../images/design/detail_text_sns01.gif\" WIDTH=165 HEIGHT=33 ALT=\"\"></td>
			</tr>
			<tr>
				<td><img src=\"../images/design/con_line01.gif\" width=\"100%\" height=\"1\" border=\"0\"></td>
			</tr>
			<tr><td height=20></td></tr>
			<tr>
				<td id=\"snsBoardList\"></td>
			</tr>
		</table>
	";
?>