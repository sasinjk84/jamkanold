<?
	$Dir="../";
	include_once($Dir."lib/init.php");
	include_once($Dir."lib/lib.php");
	include ("access.php");

	####################### ������ ���ٱ��� check ###############
	$PageCode = "mo-1";
	$MenuCode = "mobile";
	if (!$_usersession->isAllowedTask($PageCode)) {
		include ("AccessDeny.inc.php");
		exit;
	}

	#########################################################

	/* �ڹٽ�ũ��Ʈ ���� ����*/
	//�̹��� ù��
	$this_b_m = date('Y-m')."-01";
	//�̹��� ��������
	$this_e_d = date('t',mktime(0,0,1,date('m'),1,date('Y')));
	//�̹��� ��������¥
	$this_e_m = date('Y-m')."-".$this_e_d;

	$today = time();
	$week = date("w");


	//15����
	$b_15day = strftime("%Y-%m-%d", strtotime("-15 day"));
	$e_15day = date("Y-m-d");

	$b_30day = strftime("%Y-%m-%d", strtotime("-30 day"));
	$e_30day = date("Y-m-d");

	$b_60day = strftime("%Y-%m-%d", strtotime("-60 day"));
	$e_60day = date("Y-m-d");


	//�̹���
	$week_first = $today - ($week * 86400);
	$week_last = $week_first + (6 * 86400);

	$b_this_week_day = date("Y-m-d",$week_first);
	$e_this_week_day = date("Y-m-d",$week_last);

	/* �ڹٽ�ũ��Ʈ �������� �� ��*/
?>
<? include "header.php"; ?>
<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
	function ACodeSendIt(f,obj) {
		if(obj.ctype=="X") {
			f.code.value = obj.value+"000000000";
		} else {
			f.code.value = obj.value;
		}

	burl = "mobile_product_category.php?depth=2&code=" + obj.value;
	curl = "mobile_product_category.php?depth=3";
	durl = "mobile_product_category.php?depth=4";
	BCodeCtgr.location.href = burl;
	CCodeCtgr.location.href = curl;
	DCodeCtgr.location.href = durl;
	}
	function checkForm()
		{
		var f=	document.form1;
		f.target = "ifrm";

		f.submit();
	}


	function setPeriod(str) {
		var f =	document.form1;

		if(str=="today") {
			f.regdate_begin.value = "<?=date('Y-m-d')?>";
			f.regdate_end.value = "<?=date('Y-m-d')?>";
		} else if(str=="15day"){
			f.regdate_begin.value = "<?=$b_15day?>";
			f.regdate_end.value = "<?=$e_15day?>";
		}else if(str=="week"){
			f.regdate_begin.value = "<?=$b_this_week_day?>";
			f.regdate_end.value = "<?=$e_this_week_day?>";
		}else if(str=="month"){
			f.regdate_begin.value = "<?=$b_30day?>";
			f.regdate_end.value = "<?=$e_30day?>";
		}else if(str=="2month"){
			f.regdate_begin.value = "<?=$b_60day?>";
			f.regdate_end.value = "<?=$e_60day?>";
		}else if(str=="all"){
			f.regdate_begin.value = "";
			f.regdate_end.value = "<?=date('Y-m-d')?>";
		}
	}
</script>
<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0">
	<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td height="8"></td>
		</tr>
		<tr>
			<td>
				<table width="100%" border=0 cellpadding=0 cellspacing=0>
					<tr>
						<td><img src="images/mobile_product_search_popt.gif" border="0"></td>
						<td width="100%" background="images/member_mailallsend_imgbg.gif"></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td height="20"></td>
		</tr>
		<tr>
			<td>
				<table width="95%" border=0 cellpadding=0 cellspacing=0 align="center">
					<tr>
						<td><img src="images/mobile_product_search_stitl.gif" border="0"></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td height=10></td>
		</tr>
		<tr>
			<td>
			<?
				$code=$_REQUEST["code"];

				$codeA=substr($code,0,3);
				$codeB=substr($code,3,3);
				$codeC=substr($code,6,3);
				$codeD=substr($code,9,3);
				if(strlen($codeA)!=3) $codeA="";
				if(strlen($codeB)!=3) $codeB="";
				if(strlen($codeC)!=3) $codeC="";
				if(strlen($codeD)!=3) $codeD="";
				$code=$codeA.$codeB.$codeC.$codeD;
				if($codeA=="000") {
					$code=$codeA=$codeB=$codeC=$codeD="";
				}
			?>
				<form name=form1 action="mobile_product_search_result.php" method=get>
					<input type="hidden" name="code" value="<?=$code?>">
					<input type="hidden" name="page_mode" value="<?=$page_mode?>">
					<input type="hidden" name="pm_idx" value="<?=$pm_idx?>">
					<table width="95%" border=0 cellpadding=0 cellspacing=0 align="center">
						<tr>
							<td colspan="2" background="images/table_con_line.gif"></td>
						</tr>
						<tr>
							<td class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">ī�װ�</td>
							<td class="td_con1">
								<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
									<tr>
										<td height=28 valign=middle>
											<?
											// ī�װ� ���� ������ ����� �� ������ �����Ǿ��ִ��� ����
												$result = mysql_query("select use_same_product_code from tblmobileconfig");
												$row = mysql_fetch_array($result);
												if($row[use_same_product_code]=="Y"){ // ���θ� ������ ����
													$sql = "SELECT codeA,codeB,codeC,codeD,code_name,type FROM tblproductcode ";
													$sql.= "WHERE codeB='000' AND codeC='000' ";
													$sql.= "AND codeD='000' AND type LIKE 'L%' ORDER BY sequence DESC ";
												} else { // ����� �� ����
													$sql = "SELECT codeA,codeB,codeC,codeD,code_name,type FROM tblproductcode ";
													$sql.= "WHERE codeB='000' AND codeC='000' ";
													$sql.= "AND codeD='000' AND type LIKE 'L%' and mobile_display = 'Y' ORDER BY sequence DESC ";
												}
												$result=mysql_query($sql,get_db_conn());
											?>
											<select class="select"  name="code1" style="width:143px;height:21px;" onchange="ACodeSendIt(document.form1,this.options[this.selectedIndex])">
												<option value="">---- �� �� �� ----</option>
													<?
														while($row=mysql_fetch_object($result)) {
															$ctype=substr($row->type,-1);
															if($ctype!="X") {
																$ctype="";
															}

															if($row->codeA==$codeA) {
																$str_sel = "selected";
															}else {
																$str_sel = "";
															}
															echo "<option value=\"".$row->codeA."\" ctype='".$ctype."' $str_sel>".$row->code_name."";
															if($ctype=="X") echo " (���Ϻз�)";
															echo "</option>\n";
														}
														mysql_free_result($result);


													?>
											</select>
										</td>
										<td height=28 valign=middle>
											<iframe name="BCodeCtgr" src="mobile_product_category.php?depth=2&code=<?=$code?>" width="220" height="21" scrolling=no frameborder=no></iframe>
										</td>
										<td height=28 valign=middle>
											<iframe name="CCodeCtgr" src="mobile_product_category.php?depth=3&code=<?=$code?>" width="220" height="21" scrolling=no frameborder=no></iframe>
										</td>
										<td height=28 valign=middle>
											<iframe name="DCodeCtgr" src="mobile_product_category.php?depth=4&code=<?=$code?>" width="220" height="21" scrolling=no frameborder=no></iframe>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td colspan="2" background="images/table_con_line.gif"></td>
						</tr>
						<tr>
							<td class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">�˻���</td>
							<td class="td_con1">
								<select class="select"  name="search_field">
									<option name="productname" value="productname">��ǰ��</option>
									<option name="productcode" value="productcode">��ǰ�ڵ�</option>
									<option name="keyword" value="keyword">Ű����</option>
								</select>
								<input type="text" name="search_word" class="input">
							</td>
						</tr>
						<tr>
							<td colspan="2" background="images/table_con_line.gif"></td>
						</tr>
						<tr>
							<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">��ǰ����</td>
							<td class="td_con1">
								<table width="100%" cellpadding="0" cellspacing="0" border=0>
									<tr>
										<td width="300">
											<input type="text" class="input" name="sellprice_min" size="10">~<input type="text" class="input" name="sellprice_max" size="10">
										</td>
										<td class="table_cell">
											<img src="images/icon_point2.gif" width="8" height="11" border="0">�귣��
										</td>
										<td>
											<select class="select"  name="brand">
												<option value="">�귣��</option>
												<?
													$result_brand = mysql_query("select bridx,brandname from tblproductbrand order by bridx ASC");
													while($row_brand = mysql_fetch_array($result_brand))
														{
												?>
															<option value="<?=$row_brand[bridx]?>"><?=$row_brand[brandname]?></option><?
														}
												?>
											</select>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td colspan="2" background="images/table_con_line.gif"></td>
						</tr>
						<tr>
							<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">��ǰ�����</td>
							<td class="td_con1">
								<input type="text" class="input" name="regdate_begin" size="10"> ~ <input type="text"  name="regdate_end" class="input" size="10">
								<input type="button" value="����"  onClick="setPeriod('today')">
								<input type="button" value="15��"  onClick="setPeriod('15day')">
								<input type="button" value="�Ѵ�"  onClick="setPeriod('month')">
								<input type="button" value="�δ�"  onClick="setPeriod('2month')">
								<input type="button" value="��ü"  onClick="setPeriod('all')">
							</td>
						</tr>
						<tr>
							<td colspan="2" background="images/table_con_line.gif"></td>
						</tr>
						<tr>
							<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">��ǰ��¿���</td>
							<td class="td_con1">
								<input type="radio" name="display" value="" checked /> ��ü
								<input type="radio" name="display" value="Y" /> ��»�ǰ
								<input type="radio" name="display" value="N" /> ����»�ǰ
							</td>
						</tr>
						<tr>
							<td colspan="2" background="images/table_con_line.gif"></td>
						</tr>
						<tr>
							<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�������¿���</td>
							<td class="td_con1">
								<input type="radio" name="mobile_display" value="" checked /> ��ü
								<input type="radio" name="mobile_display" value="Y" /> ��»�ǰ
								<input type="radio" name="mobile_display" value="N" /> ����»�ǰ
							</td>
						</tr>
						<tr>
							<td colspan="2" background="images/table_con_line.gif"></td>
						</tr>
					</table>
				</form>
			</td>
		</tr>
		<tr>
			<td height="15"></td>
		</tr>
		<tr>
			<td>
				<div style="text-align:center">
					<a href="javascript:checkForm()"><img src="images/botteon_search.gif" width="113" height="38" border="0"></a>
				</div>
			</td>
		</tr>
		<tr>
			<td align="center">
				<iframe name="ifrm" src="mobile_product_search_result.php?page_mode=<?=$page_mode?>&pm_idx=<?=$pm_idx?>&code=<?=$code?>" width=95% height=600 frameborder=0 align=top scrolling="no" marginheight="0" marginwidth="0"></iframe>
			</td>
		</tr>
		<tr>
			<td height="15"></td>
		</tr>
	</table>
</body>
</html>
