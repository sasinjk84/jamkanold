
<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
	<tr>
		<td style="padding:2px;background:#f2f2f2;">
			<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td width="100%" style="padding:20px 40px;background:#ffffff;">
						<table cellpadding="0" cellspacing="0" width="100%" border="0">
							<tr>
								<!--<td valign="top" width="80"><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin3_text1.gif" border="0" /></td>-->
								<td>

									<table cellpadding="0" cellspacing="4" width="100%" class="searchInputBox">
										<colgroup>
											<col width="100" />
											<col width="" />
										</colgroup>
										<tr>
											<th>��ǰŸ��</th>
											<td>
												<select name="rental" style="width:150px;font-size:12px;">
													<option value="">��ü</option>
													<option value="2" <?=($_REQUEST['rental']=='2')?'selected':''?>>��Ż��ǰ</option>
													<option value="1"  <?=($_REQUEST['rental']=='1')?'selected':''?>>�ǸŻ�ǰ</option>
												</select>
											</td>
										</tr>
										<?
										$vsql = "select vender,com_name from tblvenderinfo where disabled ='0' order by com_name";
										$venderlists = array();										
										if((false !==$vres = mysql_query($vsql,get_db_conn())) && mysql_num_rows($vres)){
											while($vrow = mysql_fetch_assoc($vres)){
												$venderlists[$vrow['vender']] = $vrow['com_name'];
											}
										}
										if(_array($venderlists)){											
										?>
										<tr>
											<th>�뿩��</th>
											<td>
												<select name="vender" style="width:150px;font-size:12px;">
												<option value="">��ü</option>
												<option value="0" <?=($_REQUEST['vender'] == '0')?'selected':''?>>��񺻻�</option>
												<? 
												foreach($venderlists as $vender=>$vname){
													$sel = ($vender == $_REQUEST['vender'])?'selected':'';
													?>
													<option value="<?=$vender?>"><?=$vname?></option>
												<?
												} ?>
												</select>
											</td>
										</tr>
										<? } ?>
										<? /*
										<tr>
											<th>�� ������</th>
											<td><input type="text" name="" value="" size="23" class="input" /></td>
										</tr>
										*/ ?>
										<tr>
											<th>��ǰ�з�</th>
											<td>
												<select name="codeA" style="width:150px;font-size:12px;" onchange="SearchChangeCate(this,1)">
													<option value="">- 1�� ī�װ� ���� -</option>
												</select>
												<select name="codeB" style="width:150px;font-size:12px;" onchange="SearchChangeCate(this,2)">
													<option value="">- 2�� ī�װ� ���� -</option>
												</select>
												<select name="codeC" style="width:150px;font-size:12px;" onchange="SearchChangeCate(this,3)">
													<option value="">- 3�� ī�װ� ���� -</option>
												</select>
												<select name="codeD" style="width:150px;font-size:12px;">
													<option value="">- 4�� ī�װ� ���� -</option>
												</select>
											</td>
										</tr>
										<tr>
											<th>��ǰ����</th>
											<td>
												<input type=text name=minprice value="<?=$minprice?>" size="23" onkeyup="strnumkeyup(this)" class="input" style="BACKGROUND-COLOR:#F7F7F7;"> <span style="position:relative;top:2px;">�� ~</span> <input type=text name=maxprice value="<?=$maxprice?>" size="23" onkeyup="strnumkeyup(this)" class="input" style="BACKGROUND-COLOR:#F7F7F7;"> <span style="position:relative;top:2px;">��</span>
											</td>
										</tr>
										<tr>
											<th>�뿩�Ⱓ</th>
											<td>
												<div class="searchCalendal"><input type="text" name="bookingStartDate" id="bookingSearchStartDate" value="<?=pick($_REQUEST['bookingStartDate'],date("Ymd"))?>" style="width:70px;text-align:center; float:right" class="datePickInput" readonly /></div>								
												<div class="searchCalendal">&nbsp;~&nbsp;</div>
												<div class="searchCalendal"><input type="text" name="bookingEndDate" id="bookingSearchEndDate" class="datePickInput" value="<?=pick($_REQUEST['bookingEndDate'],date("Ymd"))?>" style="width:70px;text-align:center; float:right" readonly /></div>
											</td>
										</tr>
										<tr>
											<th>�˻���</th>
											<td>
												<select name="s_check" style="width:90px;">
													<option value="all" <?if($s_check=="all")echo"selected";?>>���հ˻�</option>
													<option value="keyword" <?if($s_check=="keyword")echo"selected";?>>��ǰ��/Ű����</option>
													<option value="code" <?if($s_check=="code")echo"selected";?>>��ǰ�ڵ�</option>
													<option value="selfcode" <?if($s_check=="selfcode")echo"selected";?>>�����ڵ�</option>
													<option value="production" <?if($s_check=="production")echo"selected";?>>������</option>
													<option value="model" <?if($s_check=="model")echo"selected";?>>�𵨸�</option>
													<option value="content" <?if($s_check=="content")echo"selected";?>>�󼼼���</option>
													<option value="prmsg" <?if($s_check=="prmsg")echo"selected";?>>ȫ������</option>
												</select>
												<input type="text" name="search" value="<?=$search?>" style="WIDTH: 260px; BACKGROUND-COLOR:#F7F7F7;" class="input" />
											</td>
										</tr>

										<?
											// ����� �˻� 1 ��� IF
											$subSrchIf1S = "<!-- ";
											$subSrchIf1E = " -->";

											// ����� �˻� 2 ��� IF
											$subSrchIf2S = "<!-- ";
											$subSrchIf2E = " -->";

											// ����� �˻� 1
											if( strlen( $search ) > 0 ) {
												$subSrchIf1S = "";
												$subSrchIf1E = "";

												// ����� �˻� 2
												if( strlen( $search1 ) > 0 ) {
													$subSrchIf2S = "";
													$subSrchIf2E = "";
												}
											}
										?>

										<?=$subSrchIf1S?>
										<tr>
											<th>����� �˻�1</th>
											<td><input type=text name=search1 value="<?=$search1?>" style="WIDTH: 277px;BACKGROUND-COLOR:#F7F7F7;" class="input" /></td>
										</tr>
										<?=$subSrchIf1E?>

										<?=$subSrchIf2S?>
										<tr>
											<th>����� �˻�2</th>
											<td><input type=text name=search2 value="<?=$search2?>" style="WIDTH: 277px;BACKGROUND-COLOR:#F7F7F7;" class="input" /></td>
										</tr>
										<?=$subSrchIf2E?>
									</table>

								</td>
								<td align="right"><a href="javascript:CheckForm();"><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin3_icon4.gif" border="0"></a></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<script>SearchCodeInit("<?=$codeA?>","<?=$codeB?>","<?=$codeC?>","<?=$codeD?>");</script>
		</td>
	</tr>
	<tr><td height="20"></td></tr>
	<tr>
		<td height="35">
			<?
				$_new="";
				$_best_desc="";
				$_price="";
				$_price_desc="";

				switch(trim($sort)){
					case "best_desc":
						$_best_desc="class=\"sortOn\"";
					break;

					case "price":
						$_price="class=\"sortOn\"";
					break;

					case "price_desc":
						$_price_desc="class=\"sortOn\"";
					break;

					case "reserve_desc":
						$_reserve_desc="class=\"sortOn\"";
					break;

					case "new_desc":
					default:
						$_new="class=\"sortOn\"";
					break;

					case "review_desc":
						$_review_desc="class=\"sortOn\"";
					break;
				}
			?>
			<ul class="prSortType">
				
				<li><a href="javascript:ChangeSort('best_desc');" <?=$_best_desc?>>��ŷ��</a></li>
				<li><a href="javascript:ChangeSort('price');" <?=$_price?>>�������ݼ�</a></li>
				<li><a href="javascript:ChangeSort('price_desc');" <?=$_price_desc?>>�������ݼ�</a></li>
				<li><a href="javascript:ChangeSort('new_desc');" <?=$_new?>>����ϼ�</a></li>
				<li class="last"><a href="javascript:ChangeSort('review_desc');" <?=$_reserve_desc?>>�����</a></li>
			</ul>
			<!--
			<IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin3_text10.gif" border="0">
			<a href="javascript:ChangeSort('new');"><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin3_nerotop<?if($sort=="new")echo"_on";?>.gif" border="0"></a>
			<a href="javascript:ChangeSort('new_desc');"><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin3_nerodow<?if($sort=="new_desc")echo"_on";?>.gif" border="0"></a>
			<img src="../images/common/space_line.gif" width="8" height="1" border="0"><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin3_text11.gif" border="0">
			<a href="javascript:ChangeSort('best');"><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin3_nerotop<?if($sort=="best")echo"_on";?>.gif" border="0"></a>
			<a href="javascript:ChangeSort('best_desc');"><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin3_nerodow<?if($sort=="best_desc")echo"_on";?>.gif" border="0"></a>
			<img src="../images/common/space_line.gif" width="8" height="1" border="0"><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin3_text01.gif" border="0">
			<a href="javascript:ChangeSort('production');"><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin3_nerotop<?if($sort=="production")echo"_on";?>.gif" border="0"></a>
			<a href="javascript:ChangeSort('production_desc');"><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin3_nerodow<?if($sort=="production_desc")echo"_on";?>.gif" border="0"></a>
			<img src="../images/common/space_line.gif" width="8" height="1" border="0"><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin3_text02.gif" border="0">
			<a href="javascript:ChangeSort('name');"><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin3_nerotop<?if($sort=="name")echo"_on";?>.gif" border="0"></a>
			<a href="javascript:ChangeSort('name_desc');"><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin3_nerodow<?if($sort=="name_desc")echo"_on";?>.gif" border="0"></a>
			<img src="../images/common/space_line.gif" width="8" height="1" border="0"><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin3_text03.gif" border="0">
			<a href="javascript:ChangeSort('price');"><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin3_nerotop<?if($sort=="price")echo"_on";?>.gif" border="0"></a>
			<a href="javascript:ChangeSort('price_desc');"><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin3_nerodow<?if($sort=="price_desc")echo"_on";?>.gif" border="0"></a>
			<img src="../images/common/space_line.gif" width="8" height="1" border="0"><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin3_text04.gif" border="0">
			<a href="javascript:ChangeSort('reserve');"><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin3_nerotop<?if($sort=="reserve")echo"_on";?>.gif" border="0"></a>
			<a href="javascript:ChangeSort('reserve_desc');"><IMG SRC="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin3_nerodow<?if($sort=="reserve_desc")echo"_on";?>.gif" border="0"></a>
			-->
			<div style="float:right;">
				<select name="listnum" onchange="ChangeListnum(this.value)" class="select">
					<option value="20"<?if($listnum==20)echo" selected";?> >20���� ����
					<option value="40"<?if($listnum==40)echo" selected";?>>40���� ����
					<option value="60"<?if($listnum==60)echo" selected";?>>60���� ����
					<option value="100"<?if($listnum==100)echo" selected";?>>100���� ����
				</select>
			</div>
			<p style="float:right;margin:8px 8px 0px 0px;">�� <b><?=$t_count?></b>���� ��ǰ�� �ֽ��ϴ�.</p>
		</td>
	</tr>
	<tr><td height="5"></td></tr>
	<tr>
		<td height="1" background="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin3_line.gif"></td>
	</tr>
	<tr><td height="20"></td></tr>
	<tr>
		<td>
<?
		$tag_0_count = 2; //��ü��ǰ �±� ��� ����
		//��ȣ, ����, ��ǰ��, ������, ����



		$tmp_sort=explode("_",$sort);

		/*
		if($tmp_sort[0]=="reserve") {
			$addsortsql=",IF(a.reservetype='N',a.reserve*1,a.reserve*a.sellprice*0.01) AS reservesort ";
		}
		$sql = "SELECT a.productcode, a.productname, a.quantity, a.reserve, a.reservetype, a.production, ".( (isSeller()=="Y") ? "if(a.productdisprice>0,a.productdisprice,a.sellprice) as sellprice, if(a.productdisprice>0,1,0)":"a.sellprice, 0" )." as isdiscountprice, ";
		$sql.= "a.tinyimage, a.date, a.etctype, a.option_price, a.consumerprice, a.tag, a.selfcode ";
		$sql.= $addsortsql;
		$sql.= "FROM tblproduct AS a ";
		$sql.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
		*/
		$sql = productQuery();
		$sql.= $qry." ";
		$sql.= "AND (a.group_check='N' OR b.group_code='".$_ShopInfo->getMemgroup()."') ";
		if($tmp_sort[0]=="production") $sql.= "ORDER BY a.production ".$tmp_sort[1]." ";
		else if($tmp_sort[0]=="name") $sql.= "ORDER BY a.productname ".$tmp_sort[1]." ";
		else if($tmp_sort[0]=="price") $sql.= "ORDER BY a.sellprice ".$tmp_sort[1]." ";
		else if($tmp_sort[0]=="reserve") $sql.= "ORDER BY reservesort ".$tmp_sort[1]." ";
		else if($tmp_sort[0]=="new") $sql.= "ORDER BY a.regdate ".$tmp_sort[1]." ";
		else if($tmp_sort[0]=="best") $sql.= "ORDER BY a.sellcount ".$tmp_sort[1]." ";
		else $sql.= "ORDER BY a.productname ";
		$sql.= "LIMIT " . ($setup[list_num] * ($gotopage - 1)) . ", " . $setup[list_num];

		$result=mysql_query($sql,get_db_conn());

		$i=0;
		
		
		$innerpub = file_get_contents($Dir.'newUI/prlist_search.html');	
			
		$pos = strlen($innerpub);
		if(false !== $pos = strpos($innerpub,'<!-- items -->')){
			if(false === $epos = strpos($innerpub,'<!-- /items -->')) $epos = strlen($innerpub);			
			$conts['items'] = substr($innerpub,$pos+strlen('<!-- items -->'),$epos-$pos-strlen('<!-- items -->'));
		}
		$conts['head'] = substr($innerpub,0,$pos);
		$conts['bott'] = substr($innerpub,$epos);			
		$conts['cont'] = '';
		$conts = str_replace('__ID__','ProductList',$conts);				
		
		if(mysql_num_rows($result)){
			$i=0;
			while($row=mysql_fetch_assoc($result)) {
				$itemtxt = $conts['items'];	
				$row = solvResultforNewUi($row);						
				$row['listfinal'] = (++$i%5==0)?'endItem':'';					
				foreach($row as $k=>$v){
					$itemtxt = str_replace('product.'.$k,$v,$itemtxt);
				}
				$conts['cont'] .= $itemtxt;		
				if($row['listfinal'] == 'endItem') $conts['cont'] .= '<div style="clear:both; height:10px; font-size:0px; border:0px; padding:0px; margin:0px;"></div>';
			}
		}
		$prlist1 = $conts['head'].$conts['cont'].$conts['bott'];
		echo $prlist1;
		mysql_free_result($result);
		
?>
	
		</td>
	</tr>
	<tr>
		<td height="20"></td>
	</tr>
	<tr>
		<td height="1" background="<?=$Dir?>images/common/search/<?=$_data->design_search?>/design_search_skin3_line.gif"></td>
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
	<tr><td height="20"></td></tr>
</table>