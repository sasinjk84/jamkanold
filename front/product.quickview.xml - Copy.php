<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

header("Cache-Control: no-cache, must-revalidate");
header("Content-Type: text/xml; charset=EUC-KR");

$imagepath=$Dir.DataDir."shopimages/multi/";

$productcode=$_GET["productcode"];
$errmsg="";
if(strlen($productcode)==18) {
	$codeA=substr($productcode,0,3);
	$codeB=substr($productcode,3,3);
	$codeC=substr($productcode,6,3);
	$codeD=substr($productcode,9,3);

	$sql = "SELECT * FROM tblproductcode WHERE codeA='".$codeA."' AND codeB='".$codeB."' AND codeC='".$codeC."' AND codeD='".$codeD."' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$_cdata=$row;
		if($row->group_code=="NO") {	//���� �з�
			$errmsg="�ǸŰ� ����� ��ǰ�Դϴ�.";
		} else if($row->group_code=="ALL" && strlen($_ShopInfo->getMemid())==0) {	//ȸ���� ���ٰ���
			$errmsg="ȸ������ ��ǰ�Դϴ�.\\n\\n�α��� �� �̿��Ͻñ� �ٶ��ϴ�.";
		} else if(strlen($row->group_code)>0 && $row->group_code!="ALL" && $row->group_code!=$_ShopInfo->getMemgroup()) {	//�׷�ȸ���� ����
			$errmsg="�ش� �з��� ���� ������ �����ϴ�.";
		}


		$sql = productQuery ();
		//�Ҽ�
		if(eregi("S",$_cdata->type)) {
			$sql = "SELECT a.*, c.* ";
			$sql.= "FROM tblproduct AS a ";
			$sql.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
			$sql.= "LEFT OUTER JOIN tblproduct_social c ON a.productcode=c.pcode ";
		}
		$sql.= "WHERE a.productcode='".$productcode."' AND a.display='Y' ";
		$sql.= "AND (a.group_check='N' OR b.group_code LIKE '%".$_ShopInfo->getMemgroup()."%') ";
		$result=mysql_query($sql,get_db_conn());
		if($row=mysql_fetch_object($result)) {
			$_pdata=$row;
		} else {
			$errmsg="�ش� ��ǰ ������ �������� �ʽ��ϴ�.";
		}
		mysql_free_result($result);

	} else {
		$errmsg="�ش� �з��� �������� �ʽ��ϴ�.";
	}
} else {
	$errmsg="�ش� ��ǰ�� �������� �ʽ��ϴ�.";
}


#####################��ǰ�� ȸ�������� ���� ����#######################################
// ���� ���� ���� ��ǰ ������
$wholeSaleIcon = ( $_pdata->isdiscountprice == 1 ) ? $wholeSaleIconSet:"";

$memberpriceValue = $_pdata->sellprice;
$strikeStart = $strikeEnd = $memberprice = '';
if($_pdata->discountprices>0){
	$memberprice = number_format($_pdata->sellprice - $_pdata->discountprices);
	$strikeStart = "<strike>";
	$strikeEnd = "</strike> �� ".$memberprice;
	$memberpriceValue = ($_pdata->sellprice - $_pdata->discountprices);
}
#####################��ǰ�� ȸ�������� ���� �� #######################################


if(strlen($errmsg)>0) {
	echo "<script>alert('".$errmsg."'); $('create_openwin').setStyle('display','none');</script>"; exit;
}

$multi_img="N";
$maxsize=220;
$sql = "SELECT * FROM tblmultiimages WHERE productcode='".$productcode."' ";
$result=mysql_query($sql,get_db_conn());
if($row=mysql_fetch_object($result)) {
	$multi_img="Y";
	//$multi_imgs=array(&$row->primg01,&$row->primg02,&$row->primg03,&$row->primg04,&$row->primg05,&$row->primg06,&$row->primg07,&$row->primg08,&$row->primg09,&$row->primg10);
	$multi_imgs = array ();
	for( $i=1;$i<=MultiImgCnt;$i++ ){
		$k = str_pad($i,2,'0',STR_PAD_LEFT);
		array_push( $multi_imgs, &$row->{"primg".$k} );
	}

	$tmpsize=explode("",$row->size);

	$y=0;
	for($i=0;$i<MultiImgCnt;$i++) {
		if(strlen($multi_imgs[$i])>0) {
			$yesimage[$y]=$multi_imgs[$i];
			if(strlen($tmpsize[$i])==0) {
				$size=getimagesize($imagepath.$multi_imgs[$i]);
				$xsize[$y]=$size[0];
				$ysize[$y]=$size[1];
			} else {
				$tmp=explode("X",$tmpsize[$i]);
				$xsize[$y]=$tmp[0];
				$ysize[$y]=$tmp[1];
			}
			$y++;
		}
	}

	$makesize=$maxsize;
	for($i=0;$i<$y;$i++){
		if($xsize[$i]>$makesize || $ysize[$i]>$makesize) {
			if($xsize[$i]>=$ysize[$i]) {
				$tempxsize=$makesize;
				$tempysize=($ysize[$i]*$makesize)/$xsize[$i];
			} else {
				$tempxsize=($xsize[$i]*$makesize)/$ysize[$i];
				$tempysize=$makesize;
			}
			$xsize[$i]=$tempxsize;
			$ysize[$i]=$tempysize;
		}
	}
	mysql_free_result($result);
}

$sql = "SELECT COUNT(*) as t_count, SUM(marks) as totmarks FROM tblproductreview ";
$sql.= "WHERE productcode='".$productcode."' ";
if($_data->review_type=="A") $sql.= "AND display='Y' ";
$result=mysql_query($sql,get_db_conn());
$row=mysql_fetch_object($result);
$review_tcount = (int)$row->t_count;
$review_totmarks = (int)$row->totmarks;
$review_marks=@ceil($review_totmarks/$review_tcount);
mysql_free_result($result);

$review_aver=@(int)(($review_totmarks/$review_tcount)*20);

?>

<table border=0 cellpadding=0 cellspacing=0 width=100%>
<tr>
	<td>
	<table border=0 cellpadding=0 cellspacing=0 width=100%>
	<tr>
		<td id=layerbox-top style="cursor:move; float:left;">
		<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
		<col width=10></col>
		<col width=></col>
		<col width=10></col>
		<tr>
			<td style="width:10px;height:25px;background: url(/<?=RootPath?>images/common/layeropenbg_top_left.gif) no-repeat 0 0;"></td>
			<td style="height:25px;background: url(/<?=RootPath?>images/common/layeropenbg_top_center.gif)">
			<table border=0 cellpadding=0 cellspacing=0 width=100%>
			<col width=></col>
			<col width=50></col>
			<tr>
				<td style="padding:5,0,0,0; font-size:11px;color:#FEEACB;"><B>���� Quick View</B></td>
				<td align=right style="padding-top:2;"><a style="cursor:hand" onclick="PrdtQuickCls.openwinClose()"><FONT style="font-size:11px;color:#FEEACB;">close</FONT> <img src="/<?=RootPath?>images/common/layeropen_btn_close.gif" border=0 align=absmiddle></a></td>
			</tr>
			</table>
			</td>
			<td style="width:10px;height:25px;background: url(/<?=RootPath?>images/common/layeropenbg_top_right.gif) no-repeat 0 0;"></td>
		</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td id=layerbox-content>
		<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
		<col width=10></col>
		<col width=></col>
		<col width=10></col>
		<tr>
			<td style="width:10px;background: url(/<?=RootPath?>images/common/layeropenbg_middle_left.gif) repeat-y;"></td>
			<td style="background: url(/<?=RootPath?>images/common/layeropenbg_middle_center.gif);">
			<div style="margin: 15px 0 0 3px;">
			<!-- ���� ���� -->
			<table border=0 cellpadding=0 cellspacing=0 width=100%>
			<tr>
				<td style="word-break:break-all;">
				<?if(strlen($_pdata->addcode)>0) echo "<font style=\"color:#FF7900;font-size:14px\"><B>[".$_pdata->addcode."]</B></font> ";
				echo "		<FONT style=\"color:#000000;font-size:14px\"><B>".viewproductname($_pdata->productname,$_pdata->etctype,$_pdata->selfcode)."</B></FONT>";?>
				</td>
			</tr>
			<tr><td height=5></td></tr>
			<tr><td height=1 bgcolor=#fafafa></td></tr>
			<tr><td height=1 bgcolor=#efefef></td></tr>
			<tr><td height=1 bgcolor=#fafafa></td></tr>
			<tr><td height=10></td></tr>
			<tr>
				<td>
				<table border=0 cellpadding=0 cellspacing=0 width=100%>
				<col width=230></col>
				<col width=10></col>
				<col width=></col>
				<tr>
					<td>
					<table border=0 cellpadding=0 cellspacing=0 width=100%>
					<tr>
						<td style="padding:5,5,5,5; border:1px #efefef solid" align=center valign=middle><?
						if($multi_img=="Y") {
							echo "<img src=\"/".RootPath."images/common/trans.gif\" border=0 name=quickprimg>";
						} else {
							if(strlen($_pdata->minimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$_pdata->minimage)) {
								$width=GetImageSize($Dir.DataDir."shopimages/product/".$_pdata->minimage);
								if($width[0]>=220) $width[0]=220;
								else if (strlen($width[0])==0) $width[0]=220;
								echo "<img src=\"/".RootPath.DataDir."shopimages/product/".$_pdata->minimage."\" border=0 width=".$width[0].">";
							} else {
								echo "<img src=\"/".RootPath."images/no_img.gif\" border=0>";
							}
						}
						?></td>
					</tr>
<?
					if($multi_img=="Y") {
						echo "<tr><td height=5></td></tr>\n";
						echo "<tr>\n";
						echo "	<td>\n";
						echo "	<table border=0 cellpadding=0 cellspacing=0 width=100%>\n";
						echo "	<tr>\n";
						echo "		<td align=center>\n";
						echo "		<table border=0 cellpadding=0 cellspacing=1 bgcolor=#DADADA>\n";
						for($i=0;$i<$y;$i++) {
							if($i==0) echo "<tr height=46 bgcolor=#FFFFFF>\n";
							if($i>0 && $i%5==0) echo "</tr><tr height=46 bgcolor=#FFFFFF>\n";
							echo "<td width=46 align=center>";
							echo "<a href=\"javascript:PrdtQuickCls.quickprimg_preview('".$imagepath.$yesimage[$i]."','".$xsize[$i]."','".$ysize[$i]."')\">";
							echo "<img src=".$imagepath."s".$yesimage[$i]." border=0";
							if($xsize[$i]>$ysize[$i]) echo " width=41";
							else echo " height=41";
							echo "></a></td>";
						}
						if($i%5!=0) {
							//if($i>5) {
								for($j=($i%5);$j<5;$j++) {
									echo "<td width=46 align=center bgcolor=#ffffff></td>";
								}
							//}
							echo "</tr>\n";
						}
						echo "		</table>\n";
						echo "		</td>\n";
						echo "	</tr>\n";
						echo "	</table>\n";
						echo "	</td>\n";
						echo "</tr>\n";

						echo "<script>PrdtQuickCls.quickprimg_preview('".$imagepath.$yesimage[0]."','".$xsize[0]."','".$ysize[0]."');</script>";
					}
?>
					</table>
					</td>
					<td>&nbsp;</td>
					<td valign=top style="padding-top:5">
					<!-- ��ǰ �󼼳��� ��� ���� -->
					<table border=0 cellpadding=0 cellspacing=0 width=100%>
					<tr>
						<td>
						<table border=0 cellpadding=0 cellspacing=0 width=100%>
						<col width=70></col>
						<col width=></col>
<?
						if($_pdata->consumerprice>0) {	//�Һ��ڰ�
							echo "<tr>\n";
							echo "	<td style=\"font-family:Tahoma\">�Һ��ڰ�</td>\n";
							echo "	<td style=\"font-family:'verdana','arial'\"><img src=\"/".RootPath."images/common/won_icon2.gif\" border=0 align=absmiddle><s>".number_format($_pdata->consumerprice)."��</s>";
							echo "	</td>\n";
							echo "</tr>\n";
						}
						echo "	<tr height=23>\n";
						echo "		<td style=\"font-family:Tahoma\">�ǸŰ���</td>\n";
						echo "		<td style=\"color:#FD5810;font-size:14px;font-weight:bold;font-family:'verdana','arial'\">";

						echo $strikeStart;

						if($dicker=dickerview($_pdata->etctype,number_format($memberpriceValue)."��",1)) {
							echo $dicker;
						} else if(strlen($_data->proption_price)==0) {
							echo "<img src=\"/".RootPath."images/common/won_icon.gif\" border=0 align=absmiddle>".$wholeSaleIcon.number_format($memberpriceValue)."��";
							if (strlen($_pdata->option_price)!=0) echo " (�⺻��)";
						} else {
							echo "<img src=\"/".RootPath."images/common/won_icon.gif\" border=0 align=absmiddle>";
							if (strlen($_pdata->optionprice)==0) echo number_format($memberpriceValue)."��";
							else echo ereg_replace("\[PRICE\]",number_format($memberpriceValue),$_data->proption_price);
						}

						echo $strikeEnd;

						if ($_pdata->quantity=="0") echo soldout();
						echo "		</td>\n";
						echo "	</tr>\n";


						$reserveconv=getReserveConversion($_pdata->reserve,$_pdata->reservetype,$memberpriceValue,"Y");
						if($reserveconv>0) {	//������
							echo "<tr>\n";
							echo "	<td style=\"font-family:Tahoma\">������</td>\n";
							echo "	<td style=\"font-family:'verdana','arial'\"><img src=\"/".RootPath."images/common/reserve_icon.gif\" border=0 align=absmiddle>".number_format($reserveconv)."��";
							echo "	</td>\n";
							echo "</tr>\n";
						}
						if($_data->ETCTYPE["TAGTYPE"]!="N") {
							if(strlen($_pdata->tag)>0) {
								echo "<tr>\n";
								echo "	<td colspan=2 style=\"padding-top:5px;\">\n";
								echo "	<img src=\"/".RootPath."images/common/layeropen_tagicn.gif\" border=0>&nbsp;\n";
								echo "	<div style=\"position:absolute;height:40px;width:250px;overflow:auto; overflow-x:hidden;line-height:17px; background:#ffffff;border:2px solid #FF5400;padding:2,2,2,2\">\n";
								$arrtaglist=explode(",",$_pdata->tag);
								$jj=0;
								for($i=0;$i<count($arrtaglist);$i++) {
									$arrtaglist[$i]=ereg_replace("(<|>)","",$arrtaglist[$i]);
									if(strlen($arrtaglist[$i])>0) {
										if($jj>0) echo ",&nbsp;&nbsp;";
										echo "<a href=\"/".RootPath.FrontDir."tag.php?tagname=".urlencode($arrtaglist[$i])."\" onmouseover=\"window.status='".$arrtaglist[$i]."';return true;\" onmouseout=\"window.status='';return true;\">".$arrtaglist[$i]."</a>";
										$jj++;
									}
								}
								echo "	</div>\n";
								echo "	</td>\n";
								echo "</tr>\n";
								echo "<tr><td colspan=2 height=20></td></tr>\n";
							}
						}
?>
						</table>
						</td>
					</tr>
					<tr><td height=10></td></tr>
					<tr><td height=1 bgcolor=#fafafa></td></tr>
					<tr><td height=1 bgcolor=#efefef></td></tr>
					<tr><td height=1 bgcolor=#fafafa></td></tr>
					<tr><td height=10></td></tr>
					<tr>
						<td>
						<table border=0 cellpadding=0 cellspacing=0 width=100%>
						<col width=70></col>
						<col width=></col>
						<tr>
							<td style="font-family:Tahoma;color:#FD5810;font-weight:bold">��ǰ��</td>
							<td style="padding-bottom:5">
<?
							if($review_tcount==0) {
								echo "<center><FONT style=\"color:#cccccc\"><B>��ϵ� ��ǰ����<br>�����ϴ�.</B></FONT></center>";
							} else {
								for($i=0;$i<$review_marks;$i++) echo "<FONT color=#FD5810>��</FONT>";
								for($i=$review_marks;$i<5;$i++) echo "<FONT color=#CACACA>��</FONT>";
								echo "<img width=30 height=0> <B><FONT style=\"color:#cccccc\">����</FONT> <FONT style=\"font-family:'verdana','arial';font-size:17px;color:#FD5810\">".$review_aver."</FONT><FONT style=\"color:#cccccc;font-size:15px\">��</FONT></B>";
							}
?>
							</td>
						</tr>
						</table>
						</td>
					</tr>
					<tr><td height=10></td></tr>
					<tr><td height=1 bgcolor=#fafafa></td></tr>
					<tr><td height=1 bgcolor=#efefef></td></tr>
					<tr><td height=1 bgcolor=#fafafa></td></tr>
					<tr><td height=5></td></tr>
					<tr>
						<td>
						<table border=0 cellpadding=0 cellspacing=0 width=100%>
<?
						$sql = "SELECT * FROM tblproductreview WHERE productcode='".$productcode."' ";
						if($_data->review_type=="A") $sql.= "AND display='Y' ";
						$sql.= "ORDER BY num DESC LIMIT 5 ";
						$result=mysql_query($sql,get_db_conn());
						$cnt=0;
						while($row=mysql_fetch_object($result)) {
							$content=explode("=",$row->content);
							echo "<tr height=18 onMouseOver=\"PrdtQuickCls.QuickReviewMouseOver($cnt)\" onMouseOut=\"PrdtQuickCls.QuickReviewMouseOut($cnt);\">";
							echo "	<td>";
							echo "	<font color=#FD5810>[";
							for($i=0;$i<$row->marks;$i++) {
								echo "<FONT color=#FD5810>��</FONT>";
							}
							for($i=$row->marks;$i<5;$i++) {
								echo "<FONT color=#CACACA>��</FONT>";
							}
							echo "]</font> ";
							echo titleCut(30,strip_tags($content[0]));

							echo "	<br><div id=quickreview".$cnt." style=\"position:absolute; z-index:100; visibility:hidden;\">\n";
							echo "	<table width=280 border=0 cellspacing=0 cellpadding=5 bgcolor=#FFFF80>\n";
							echo "	<tr>\n";
							echo "		<td style=\"padding-left:10;padding-right:10;line-height:15pt\">".nl2br(strip_tags($content[0]))."</td>\n";
							echo "	</tr>";
							echo "	</table>\n";
							echo "	</div>\n";

							echo "	</td>\n";
							echo "</tr>\n";
							$cnt++;
						}
						mysql_free_result($result);
?>
						</table>
						</td>
					</tr>
					</table>
					<!-- ��ǰ �󼼳��� ��� ��   -->
					</td>
				</tr>
				</table>
				</td>
			</tr>
			<tr><td height=5></td></tr>
			<tr><td height=1 bgcolor=#fafafa></td></tr>
			<tr><td height=1 bgcolor=#efefef></td></tr>
			<tr><td height=1 bgcolor=#fafafa></td></tr>
			<tr><td height=15></td></tr>
			<tr>
				<td align=center><A HREF="/<?=RootPath.FrontDir?>productdetail.php?productcode=<?=$productcode?>" onmouseover="window.status='��ǰ����ȸ';return true;" onmouseout="window.status='';return true;"><img src="/<?=RootPath?>images/common/quick_btn_prdetail.gif" border=0></A></td>
			</tr>
			</table>
			<!-- ����   �� -->
			</div>
			</td>
			<td style="width:10px;background: url(/<?=RootPath?>images/common/layeropenbg_middle_right.gif) repeat-y;"></td>
		</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td id=layerbox-bottom>
		<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
			<colgroup>
				<col width=10></col>
				<col width=></col>
				<col width=10></col>
			</colgroup>
		<tr>
			<td style="width:10px;height:18px;background: url(/<?=RootPath?>images/common/layeropenbg_bottom_left.gif) no-repeat 0 0;"></td>
			<td style="height:18px;background: url(/<?=RootPath?>images/common/layeropenbg_bottom_center.gif)" align=right></td>
			<td style="width:10px;height:18px;background: url(/<?=RootPath?>images/common/layeropenbg_bottom_right.gif) no-repeat 0 0;"></td>
		</tr>
		</table>
		</td>
	</tr>
	</table>
	</td>
</tr>
</table>

<script>
Drag.init($("layerbox-top"),$("create_openwin"));
</script>
