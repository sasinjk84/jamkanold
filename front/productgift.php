<?
	$Dir="../";
	include_once($Dir."lib/init.php");
	include_once($Dir."lib/lib.php");
	include_once($Dir."lib/shopdata.php");

	$prsection_type=$_data->design_prspecial;

	$sort=$_REQUEST["sort"];
	$listnum=(int)$_REQUEST["listnum"];

	if($listnum<=0) $listnum=20;

	//����Ʈ ����
	$setup[page_num] = 10;
	$setup[list_num] = $listnum;

	$block=$_REQUEST["block"];
	$gotopage=$_REQUEST["gotopage"];

	if ($block != "") {
		$nowblock = $block;
		$curpage  = $block * $setup[page_num] + $gotopage;
	} else {
		$nowblock = 0;
	}

	if (($gotopage == "") || ($gotopage == 0)) {
		$gotopage = 1;
	}

	$t_count=0;
	$sql = "SELECT COUNT(*) as t_count ";
	$sql.= "FROM tblproduct AS a ";
	$sql.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
	$sql.= "WHERE substring(a.productcode,1,3)='999' AND a.display='Y' ";
	$result=mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
	$t_count = (int)$row->t_count;
	mysql_free_result($result);
	$pagecount = (($t_count - 1) / $setup[list_num]) + 1;
?>

<HTML>
<HEAD>
<TITLE><?=$_data->shoptitle?> - �����̿�Ǳ���</TITLE>
<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">

<META name="description" content="<?=(strlen($_data->shopdescription)>0?$_data->shopdescription:$_data->shoptitle)?>">
<META name="keywords" content="<?=$_data->shopkeyword?>">
<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
<?include($Dir."lib/style.php")?>
</HEAD>
<body<?=(substr($_data->layoutdata["MOUSEKEY"],0,1)=="Y"?" oncontextmenu=\"return false;\"":"")?><?=(substr($_data->layoutdata["MOUSEKEY"],1,1)=="Y"?" ondragstart=\"return false;\" onselectstart=\"return false;\"":"")?> leftmargin="0" marginwidth="0" topmargin="0" marginheight="0"><?=(substr($_data->layoutdata["MOUSEKEY"],2,1)=="Y"?"<meta http-equiv=\"ImageToolbar\" content=\"No\">":"")?>

<? include ($Dir.MainDir.$_data->menu_type.".php") ?>

<table border="0" cellpadding="0" cellspacing="0" width="100%">


<tr>
<td valign="top">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
if ($leftmenu!="N") {
	echo "<tr>\n";
	if ($_data->title_type=="Y" && file_exists($Dir.DataDir."design/productgift_title.gif")) {
		echo "<td  background=../images/004/productnew_title_bg.gif><img src=\"".$Dir.DataDir."design/productgift_title.gif\" border=\"0\" alt=\"�����̿�Ǳ���\"></td>\n";
	} else {
		echo "<td background=../images/004/productnew_title_bg.gif>\n";
		/*
		echo "<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>\n";
		echo "<TR>\n";
		echo "	<TD><IMG SRC=../images/design/productgift_title.gif ALT=></TD>\n";
		echo "	<TD></TD>\n";
		echo "</TR>\n";
		echo "</TABLE>\n";
		*/
		echo "</td>\n";
	}
	echo "</tr>\n";
}

?>
<tr>
	<td>
		<table align="center" cellpadding="0" cellspacing="0" width="100%" align=center>
			<tr>
				<td>
					<div class="memberbenefit">
						<h2>MUST HAVE! ��������</h2>
						<div><img src="/images/003/benefit_top.jpg" alt="" /></div>
						<div class="benefitmenu">
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td><a href="/front/newpage.php?code=1">ȸ������</a></td>
									<td><a href="/front/newpage.php?code=2">��ǰ������</a></td>
									<td><a href="/front/couponlist.php">��������</a></td>
									<td class="nowon"><a href="/front/productgift.php">�����̿��</a></td>
									<td><a href="/front/attendance.php">�⼮üũ</a></td>
									<td><a href="/front/member_urlhongbo.php">ȫ������������</a></td>
									<td><a href="/board/board.php?board=storytalk">���丮��</a></td>
								</tr>
							</table>
						</div>

						<div class="productgift">
							<h4>�����̿��</h4>
							<p>
								- �����̿���� ���ݰ� ���� ����� �� �ִ� ���������� ��ġ�˴ϴ�.<br />
								<strong>- �����̿���� �����Ͻø� �����ݾ׿� �߰��������� �帳�ϴ�.</strong>&nbsp;&nbsp;��) 100,000�� �����̿�� ���Ž� 102,000�� ��ġ�� ����<br />
								- ���������� ���θ��� �̿��Ͻô� �����̶�� �� ���ϰ� �˶��ϰ� ������ �� �ִ� ������ ������ġ���� �̿��� ������.<br />
								<strong>- �����̿���� �����ϱⰡ �����մϴ�.</strong> �����ϱ�� �޴� ���� �޴����� �̸��Ϸ� �̿�� ������ȣ�� �ڵ��߼۵˴ϴ�.<br />
								<font color="#ff0000">- �����̿���� ������ ������ �����ϸ�, ��������, ������ ���, ����ǰ����, ��ȯ �� ȯ���� �Ұ��մϴ�.</font>
							</p>
						</div>
					</div>
				</td>
			</tr>
			<tr><td height="20"></td></tr>
			<tr>
				<td align="center" style="border:1px solid #ecefed; padding:30px 40px;">
						<table cellpadding="0" cellspacing="0" width="100%" border="0">
							<tr>
<?
			$print_page="";
			$a_first_block="";
			$a_prev_page="";
			$a_next_page="";

			if($t_count<=0) {
				echo "<td align=\"center\" height=\"30\">��ϵ� ��ǰ�� �����ϴ�.</td>";
			} else {
				$tag_0_count = 2; //��ü��ǰ �±� ��� ����
				//��ȣ, ����, ��ǰ��, ������, ����
				$tmp_sort=explode("_",$sort);
				if($tmp_sort[0]=="reserve") {
					$addsortsql=",IF(a.reservetype='N',a.reserve*1,a.reserve*a.sellprice*0.01) AS reservesort ";
				}
				$sql = "SELECT a.productcode, a.productname, a.sellprice, a.quantity, a.reserve, a.reservetype, a.production, ";
				$sql.= "a.tinyimage, a.minimage, a.etctype, a.option_price, a.consumerprice, a.tag, a.selfcode, a.img_type ";
				$sql.= $addsortsql;
				$sql.= "FROM tblproduct AS a ";
				$sql.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
				$sql.= "WHERE substring(a.productcode,1,3)='999' AND a.display='Y' ";
				$sql.= "AND (a.group_check='N' OR b.group_code='".$_ShopInfo->getMemgroup()."') ";
				if($tmp_sort[0]=="production") $sql.= "ORDER BY a.production ".$tmp_sort[1]." ";
				else if($tmp_sort[0]=="name") $sql.= "ORDER BY a.productname ".$tmp_sort[1]." ";
				else if($tmp_sort[0]=="price") $sql.= "ORDER BY a.sellprice ".$tmp_sort[1]." ";
				else if($tmp_sort[0]=="reserve") $sql.= "ORDER BY reservesort ".$tmp_sort[1]." ";
				else $sql.= "ORDER BY FIELD(a.productcode,'".$sp_prcode."') ";
				$sql.= "LIMIT " . ($setup["list_num"] * ($gotopage - 1)) . ", " . $setup["list_num"];

				$result=mysql_query($sql,get_db_conn());
				$i=0;
				while($row=mysql_fetch_object($result)) {
					$number = ($t_count-($setup["list_num"] * ($gotopage-1))-$i);
					if($i>0 && $i%3==0) echo "</tr><tr><td valign=\"top\" colspan=\"5\" height=\"25\"></td></tr>\n";
					if ($i!=0 && $i%3!=0) {
						echo "<td valign=\"top\" align=\"left\" width=\"3%\">&nbsp;</td>";
					}
					echo "<td align=\"center\" valign=\"top\" width=\"30%\">\n";
					echo "<TABLE cellSpacing=\"0\" cellPadding=\"0\" width=\"100%\" border=\"0\" id=\"A".$row->productcode."\">\n";
					echo "<TR height=\"100\">\n";
					//echo "	<TD align=\"center\">";
					echo "<TD>";
					//echo "<A HREF=\"".$Dir.FrontDir."productdetail.php?productcode=".$row->productcode."\" onmouseover=\"window.status='��ǰ����ȸ';return true;\" onmouseout=\"window.status='';return true;\">";
					echo "<div onclick=\"pageLink('".$row->productcode."');\" style=\"cursor:pointer\">";;
					if($row->img_type==1) {

						if (strlen($row->minimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$row->minimage)==true) {
							$width = getimagesize($Dir.DataDir."shopimages/product/".$row->minimage);
							echo "
								<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"width:{$width[0]}px; height:{$width[1]}px; background:url(".$Dir.DataDir."shopimages/product/".urlencode($row->minimage)."); text-align:center;\">
									<tr>
										<td style=\"padding-top:8px; color:#999999;\"><b><font style=\"font-family:'verdana','����'; font-size:30px; line-height:30px; letter-spacing:-1px;\">".number_format($row->consumerprice)."</font>��</b></td>
									</tr>
								</table>
							";
						}
						else {
							//echo "<img src=\"".$Dir."images/no_img.gif\" border=\"0\" align=\"center\" >";
							echo "
								<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"222\">
									<tr>
										<td background=\"/images/design/giftcard_bg.gif\" height=\"105\" align=\"center\" style=\"padding-top:8px;\"><b><font style=\"font-family:'verdana','����'; color:#aaaaaa; font-size:30px; line-height:30px; letter-spacing:-2px;\">".number_format($row->consumerprice)."</font><font color=#bbbbbb>��</font></b></td>
									</tr>
								</table>
							";
						}
					}
					else {
						$maximgsize =222;
						if (strlen($row->minimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$row->minimage)==true) {
							echo "<img src=\"".$Dir.DataDir."shopimages/product/".urlencode($row->minimage)."\" border=\"0\" ";
							$width = getimagesize($Dir.DataDir."shopimages/product/".$row->minimage);
							if($_data->ETCTYPE["IMGSERO"]=="Y") {
								if ($width[1]>$width[0] && $width[1]>$_data->primg_minisize2) echo "height=\"".$_data->primg_minisize2."\" ";
								else if (($width[1]>=$width[0] && $width[0]>=$_data->primg_minisize) || $width[0]>=$_data->primg_minisize) echo "width=\"".$_data->primg_minisize."\" >";
							} else {
								if ($width[0]>=$width[1] && $width[0]>=$maximgsize) echo "width=\"".$maximgsize."\" >";
								else if ($width[1]>=$_data->primg_minisize) echo "height=\"".$_data->primg_minisize."\" > ";
							}
						} else {
							//echo "<img src=\"".$Dir."images/no_img.gif\" border=\"0\" align=\"center\" >";

							echo "
								<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"222\">
									<tr>
										<td background=\"/images/design/giftcard_bg.gif\" height=\"105\" align=\"center\" style=\"padding-top:10px;\"><b><font style=\"font-family:'verdana','����'; color:#aaaaaa; font-size:32px; line-height:30px; letter-spacing:-2px;\">".number_format($row->consumerprice)."</font><font color=\"#bbbbbb\">��</font></b></td>
									</tr>
								</table>
							";
						}
					}
					//echo "	</A></td>";
					echo '</div>';
					echo "</td>";
					echo "</tr>\n";
					echo "<tr><td height=\"6\"></td></tr>\n";
					echo "<tr>";
					echo "	<td align=\"left\" class=\"table_tda\"><A HREF=\"".$Dir.FrontDir."productdetail.php?productcode=".$row->productcode."\" onmouseover=\"window.status='��ǰ����ȸ';return true;\" onmouseout=\"window.status='';return true;\">".viewproductname($row->productname,$row->etctype,$row->selfcode)."</A></td>\n";
					echo "</tr>\n";
					echo "<tr><td height=\"6\"></td></tr>\n";
					/*if($row->consumerprice!=0) {
						echo "<tr>\n";
						echo "	<td align=\"left\" class=\"table_tda\"><img src=\"".$Dir."images/common/won_icon2.gif\" border=\"0\" style=\"margin-right:2px;\"><strike>".number_format($row->consumerprice)."</strike>��</td>\n";
						echo "</tr>\n";
					}*/
					echo "<tr>\n";
					echo "	<td align=\"left\" class=\"table_tda\"><b>";
					if($dicker=dickerview($row->etctype,number_format($row->sellprice)."��",1)) {
						echo $dicker;
					} else if(strlen($_data->proption_price)==0) {
						echo "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 style=\"margin-right:2px;\">".number_format($row->sellprice)."��";
						if (strlen($row->option_price)!=0) echo "(�⺻��)";
					} else {
						echo "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 style=\"margin-right:2px;\">";
						if (strlen($row->option_price)==0) echo number_format($row->sellprice)."��";
						else echo ereg_replace("\[PRICE\]",number_format($row->sellprice),$_data->proption_price);
					}
					if ($row->quantity=="0") echo soldout();
					echo "	</b></td>\n";
					echo "</tr>\n";
					$reserveconv=getReserveConversion($row->reserve,$row->reservetype,$row->sellprice,"Y");
					if($reserveconv>0) {
						echo "<tr>\n";
						echo "	<td align=\"center\" style=\"word-break:break-all;\" class=\"prreserve\"><img src=\"".$Dir."images/common/reserve_icon.gif\" border=\"0\" style=\"margin-right:2px;\">".number_format($reserveconv)."��</td>\n";
						echo "</tr>\n";
					}
					if($_data->ETCTYPE["TAGTYPE"]=="Y") {
						$taglist=explode(",",$row->tag);
						$jj=0;
						for($ii=0;$ii<$tag_0_count;$ii++) {
							$taglist[$ii]=ereg_replace("(<|>)","",$taglist[$ii]);
							if(strlen($taglist[$ii])>0) {
								if($jj==0) {
									echo "<tr>\n";
									echo "	<td align=\"center\" style=\"word-break:break-all;\">\n";
									echo "	<img src=\"".$Dir."images/common/tag_icon.gif\" border=\"0\" align=\"absmiddle\" style=\"margin-right:2px;\"><a href=\"".$Dir.FrontDir."tag.php?tagname=".urlencode($taglist[$ii])."\" onmouseover=\"window.status='".$taglist[$ii]."';return true;\" onmouseout=\"window.status='';return true;\"><FONT class=\"prtag\">".$taglist[$ii]."</font></a>";
								}
								else {
									echo "<FONT class=\"prtag\">,</font>&nbsp;<a href=\"".$Dir.FrontDir."tag.php?tagname=".urlencode($taglist[$ii])."\" onmouseover=\"window.status='".$taglist[$ii]."';return true;\" onmouseout=\"window.status='';return true;\"><FONT class=\"prtag\">".$taglist[$ii]."</font></a>";
								}
								$jj++;
							}
						}
						if($jj!=0) {
							echo "	</td>\n";
							echo "</tr>\n";
						}
					}
					echo "</table>\n";
					
					
					
					echo "<div style=\"margin-top:12px; text-align:left;\">";
					$code = !_empty($row->productcode)?substr($row->productcode,0,12):"";
					echo "<img src=\"/images/design/happycopon_btn02.gif\" style=\"cursor:pointer\" onClick=\"orderNow('ordernow2','".$row->sellprice."','".$code."','".$row->productcode."')\" /> ";
					echo "<img src=\"/images/design/happycopon_btn01.gif\" style=\"cursor:pointer\" onClick=\"orderNow('ordernow3','".$row->sellprice."','".$code."','".$row->productcode."')\" />";
					echo "</div>";
					echo "</td>";

					$i++;
				}
				if($i>0 && $i%3>0) {
					for($k=0; $k<(3-$i%3); $k++) {
						echo "<td width=\"3%\"></td>\n<td width=\"30%\"></td>\n";
					}
				}
				mysql_free_result($result);

				$total_block = intval($pagecount / $setup["page_num"]);

				if (($pagecount % $setup["page_num"]) > 0) {
					$total_block = $total_block + 1;
				}

				$total_block = $total_block - 1;

				if (ceil($t_count/$setup["list_num"]) > 0) {
					// ����	x�� ����ϴ� �κ�-����
					$a_first_block = "";
					if ($nowblock > 0) {
						$a_first_block .= "<a href='javascript:GoPage(0,1);' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='ù ������';return true\"><img src=\"../images/design/btn_first.gif\" height=\"22\" border=\"0\" align=\"absmiddle\"></a>&nbsp;&nbsp;";

						$prev_page_exists = true;
					}

					if ($nowblock > 0) {
						$a_prev_page .= "<a href='javascript:GoPage(".($nowblock-1).",".($setup["page_num"]*($block-1)+$setup["page_num"]).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup["page_num"]." ������';return true\"><img src=\"../images/design/btn_pre.gif\" height=\"22\" border=\"0\" align=\"absmiddle\"></a>&nbsp;&nbsp;";

						$a_prev_page = $a_first_block.$a_prev_page;
					}

					// �Ϲ� �������� ������ ǥ�úκ�-����

					if (intval($total_block) <> intval($nowblock)) {
						$print_page = "";
						for ($gopage = 1; $gopage <= $setup["page_num"]; $gopage++) {
							if ((intval($nowblock*$setup["page_num"]) + $gopage) == intval($gotopage)) {
								$print_page .= "<b><font color=\"#FF511B\">".(intval($nowblock*$setup["page_num"]) + $gopage)."</font></b> ";
							} else {
								$print_page .= "<a href='javascript:GoPage(".$nowblock.",".(intval($nowblock*$setup["page_num"]) + $gopage).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='������ : ".(intval($nowblock*$setup["page_num"]) + $gopage)."';return true\"><FONT class=\"table01_con2\">[".(intval($nowblock*$setup["page_num"]) + $gopage)."]</FONT></a> ";
							}
						}
					} else {
						if (($pagecount % $setup["page_num"]) == 0) {
							$lastpage = $setup["page_num"];
						} else {
							$lastpage = $pagecount % $setup["page_num"];
						}

						for ($gopage = 1; $gopage <= $lastpage; $gopage++) {
							if (intval($nowblock*$setup["page_num"]) + $gopage == intval($gotopage)) {
								$print_page .= "<b><font color=\"#FF511B\">".(intval($nowblock*$setup["page_num"]) + $gopage)."</font></b> ";
							} else {
								$print_page .= "<a href='javascript:GoPage(".$nowblock.",".(intval($nowblock*$setup["page_num"]) + $gopage).");' onMouseOver=\"window.status='������ : ".(intval($nowblock*$setup["page_num"]) + $gopage)."';return true\"><FONT class=\"table01_con2\">[".(intval($nowblock*$setup["page_num"]) + $gopage)."]</FONT></a> ";
							}
						}
					}		// ������ �������� ǥ�úκ�-��


					$a_last_block = "";
					if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
						$last_block = ceil($t_count/($setup["list_num"]*$setup["page_num"])) - 1;
						$last_gotopage = ceil($t_count/$setup["list_num"]);

						$a_last_block .= "&nbsp;&nbsp;<a href='javascript:GoPage(".$last_block.",".$last_gotopage.");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='������ ������';return true\"><img src=\"../images/design/btn_end.gif\" height=\"22\" border=\"0\" align=\"absmiddle\"></a>";

						$next_page_exists = true;
					}

					// ���� 10�� ó���κ�...

					if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
						$a_next_page .= "&nbsp;&nbsp;<a href='javascript:GoPage(".($nowblock+1).",".($setup["page_num"]*($nowblock+1)+1).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup["page_num"]." ������';return true\"><img src=\"../images/design/btn_next.gif\" height=\"22\" border=\"0\" align=\"absmiddle\"></a>";

						$a_next_page = $a_next_page.$a_last_block;
					}
				} else {
					$print_page = "<FONT class=\"table01_con2\">1</FONT>";
				}
			}
?>
									</tr>
								</table>
							</td>
						</tr>
						<tr><td height="20"></td></tr>
						<tr>
							<td align="center"><?=$a_prev_page.$print_page.$a_next_page?></td>
						</tr>
						<!--
						<tr>
							<td><img src="../images/design/con_line02.gif" width="100%" height="1" border="0"></td>
						</tr>
						-->
						<!--<tr>
							<td><img src="../images/design/con_line02.gif" width="100%" height="1" border="0"></td>
						</tr>
						<tr>
							<td height="100"></td>
						</tr>-->
					</table>
				</td>
			</tr>
			<tr><td height="20"></td></tr>
		</table>
	</td>
</table>
<form name=form1 method=post action="<?=$Dir.FrontDir?>basket2.php">
	<input type="hidden" name="price" value=""/>
	<input type="hidden" name="quantity" value="1"/>
	<input type="hidden" name="code" value="">
	<input type="hidden" name="productcode" value="">
	<input type="hidden" name="ordertype" value="">
</form>
<? include ($Dir."lib/bottom.php") ?>
<script>
	function pageLink(prnum){
		location.href="<?=$Dir.FrontDir?>productdetail.php?productcode="+prnum;
		return;
	}

	function orderNow(type,price,code,prcode){
		document.form1.ordertype.value=type;
		document.form1.code.value=code;
		document.form1.productcode.value=prcode;
		document.form1.price.value=price;
		document.form1.action = "<?=$Dir.FrontDir?>basket2.php";
		document.form1.submit();
	}
</script>


</BODY>
</HTML>