<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
	<td>
	<table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
	<tr>
		<td height="40">
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td style="padding-right:5px;"><?=$codenavi?></td>
					<td align="right" style="padding-right:3px; background-repeat:no-repeat; background-position:right;"><A HREF="javascript:ClipCopy('http://<?=$_ShopInfo->getShopurl()?>?<?=getenv("QUERY_STRING")?>')"><img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/btn_addr_copy.gif" border="0"></A></td>
				</tr>
			</table>
		</td>
	</tr>
	<?if($_pdata->vender>0){?>
	<tr>
		<td style="padding-top:5px;padding-bottom:5px;">
		<table border="0" cellpadding="0" cellspacing="0" width="100%" height="30" style="table-layout:fixed">
		<col width="15"></col>
		<col></col>
		<col width="165"></col>
		<tr>
			<td background="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/bgvenderinfo_left.gif">&nbsp;</td>
			<td background="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/bgvenderinfo_center.gif">
			<img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/icoStore.gif" border="0" align="absmiddle">
			<A HREF="javascript:GoMinishop('<?=$Dir.(MinishopType=="ON"?"minishop/":"minishop.php?storeid=").$_vdata->id?>')" style="text-decoration:none;"><FONT style="color:#A4A4A4;"><U><?=$_vdata->brand_name?></U></FONT></A>
			<img width="20" height="0">
			<FONT style="color:#8C8C8C;">��ü ��ǰ�� : <font style="color:#000000;font-size:8pt;"><B><?=$_vdata->prdt_cnt?></B></font>��</font>
			</td>
			<td background="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/bgvenderinfo_right.gif" align="right" style="padding-right:5;">
			<a href="javascript:custRegistMinishop();"><img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/btsConnectshop.gif" border="0" align="absmiddle" alt="�ܰ������"></a>
			</td>
		</tr>
		</table>
		</td>
	</tr>
	<?}?>
	<tr>
		<td height="20"></td>
	</tr>
	<tr>
		<td style="padding-left:5px;padding-right:5px;">
		<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
		<form name=form1 method=post action="<?=$Dir.FrontDir?>basket.php">
		<tr>
			<td>

				<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0" style="border:1px solid #efefef;">
					<tr>
						<td rowspan="2" width="380" height="100%" style="border-right:1px solid #eeeeee;">
							<table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">

<?
				if($multi_img=="Y") {
					echo "<tr><td width=\"100%\" align=\"center\"><iframe src=\"".$Dir.FrontDir."primage_multiframe.php?productcode=".$productcode."&thumbtype=".$thumbtype."\" frameborder=0 width=300 height=".$multi_height."></iframe></td></tr>\n";
				} else {
					echo "<tr><td align=\"center\">";
					if(strlen($_pdata->maximage)>0 && file_exists($Dir.DataDir."shopimages/product/".$_pdata->maximage)) {
						$imgsize=GetImageSize($Dir.DataDir."shopimages/product/".$_pdata->maximage);
						if(($imgsize[1]>550 || $imgsize[0]>750) && $multi_img!="I") $imagetype=1;
						else $imagetype=0;
					}
					if($_pdata->img_type==1) {
						if(strlen($_pdata->minimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$_pdata->minimage)) {
							$width=GetImageSize($Dir.DataDir."shopimages/product/".$_pdata->minimage);
							if($width[0]>=300) $width[0]=300;
							else if (strlen($width[0])==0) $width[0]=300;
							echo "<div style=\"width:{$width[0]}px; height:{$width[1]}px;background:url(".$Dir.DataDir."shopimages/product/".urlencode($_pdata->minimage).");text-align:center;padding-top:80px;\" ><b><font color=white size=6>".number_format($_pdata->consumerprice)."��</b></div>";
						}
						else {
							echo "<img src=\"".$Dir."images/no_img.gif\" border=\"0\" align=\"center\" >";
						}
					}
					else {

						if(strlen($_pdata->minimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$_pdata->minimage)) {
							$width=GetImageSize($Dir.DataDir."shopimages/product/".$_pdata->minimage);
							if($width[0]>=300) $width[0]=300;
							else if (strlen($width[0])==0) $width[0]=300;
							if(substr($_pdata->productcode,0,3)!='999') { echo "<a href=\"javascript:primage_view('".$_pdata->maximage."','".$imagetype."')\">"; }
							echo "<img src=\"".$Dir.DataDir."shopimages/product/".$_pdata->minimage."\" border=\"0\" width=\"".$width[0]."\"></a></td>\n";
						} else {
							echo "<img src=\"".$Dir."images/no_img.gif\" border=\"0\"></td>\n";
						}
					}

					echo "</tr>\n";
					echo "<tr><td height=\"10\"></td></tr><tr><td align=\"center\">";
					//��ǰ������
					if(substr($_pdata->productcode,0,3)!='999') {
						if($multi_img=="I") {
							echo "<a href=\"javascript:primage_view('".$_pdata->maximage."','".$imagetype."')\"><img src=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_btnbig.gif\" border=\"0\" align=\"absmiddle\"></a></td>\n";
						} else if(strlen($_pdata->maximage)>0) {
							echo "<a href=\"javascript:primage_view('".$_pdata->maximage."','".$imagetype."')\"><img src=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_btnbig.gif\" border=\"0\" align=\"absmiddle\"></a></td>\n";
						}
					}
					echo "</tr><tr><td height=\"5\"></td></tr>\n";
				}
?>
					<tr>
						<td>
							<? INCLUDE ($Dir.TempletDir."product/sns_btn.php"); ?>
						</td>
					</tr>
				</table>
				</td>
				<td></td>
				<td valign="top" style="padding:20px;">
					<table cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td><font color="#333333" style="font-size:15px;"><b><?=viewproductname($_pdata->productname,$_pdata->etctype,"")?></b></font></td>
						</tr>
<?
if(eregi("S",$_cdata->type)) {
	$nowTime = time();
	$leftTime = 0;
	if($_pdata->sell_startdate<= $nowTime && $nowTime <= $_pdata->sell_enddate){
		if($_pdata->quantity != null && $_pdata->quantity == 0){
			$sellstateBtn = "SOLD OUT";
			$strLeftTime = "000000";
			$odrChk = false;
		}else {
			$leftTime = $_pdata->sell_enddate - $nowTime;
			$left_d = intval($leftTime / (24*60*60));
			$mod_d	= $leftTime % (24*60*60);
			$left_H = $mod_d / (60*60);
			$mod_H	= $mod_d % (60*60);
			$left_i = $mod_H / 60;
			$mod_i	= $mod_H % 60;
			$left_s = $mod_i;
			$strLeftTime = sprintf("%02d%02d%02d" ,$left_H,$left_i,$left_s);
			$strLeftDay = ($left_d>0)? "<td width=\"60\" align=\"center\"><span style=\"font-family:Verdana;font-weight:bold; font-size:20px; color:#000;\" id=\"timeleft0\">".$left_d."��</span></td>":"";

			//$sellstateBtn = "<a href=\"javascript:CheckForm('ordernow4','".$opti."')\" onMouseOver=\"window.status='�ٷα���';return true;\"\"><img src=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_btn01.gif\" border=0></a>";
			$sellstateBtn = "<a href=\"javascript:CheckForm('ordernow4','".$opti."')\" onMouseOver=\"window.status='�ٷα���';return true;\"\"><img src=\"/data/design/img/detail/btn_baro.gif\" border=0></a>";
		}
	}else{
		$odrChk = false;
		if($_pdata->quantity == 0)
			$sellstateBtn = "<img src=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_btn02.gif\" border=0>";
		else
			$sellstateBtn = "<img src=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_btn03.gif\" border=0>";

		$strLeftTime = "000000";
	}
	if($_pdata->discount_state == "Y"){
		$discountRate = sprintf("<font color=\"#FE6700\">%d���̻� �����ϸ� ������ %s</font> "
									,$_pdata->complete_quantity
									,100-intval($_pdata->sellprice/$_pdata->consumerprice*100)."%");
	}
	//������
	$reserveconv_org=getReserveConversion($_pdata->reserve,$_pdata->reservetype,$_pdata->sellprice,"Y");
	//snsȫ���� ��� ������
	if($_data->recom_ok == "Y" && $_data->sns_ok == "Y" && $_pdata->sns_state == "Y" && $sell_memid !=""){
		$reserveconv = getReserveConversionSNS($reserveconv,$_pdata->sns_reserve2,$_pdata->sns_reserve2_type,$_pdata->sellprice,"Y");
	}
	//�ɼ� ���
	if(strlen($_pdata->option1)>0){
		$temp = $_pdata->option1;
		$arOption1 = explode(",",$temp);
		$count=count($arOption1);

		//���
		$arOptCnt = explode(",",substr($_pdata->option_quantity,1));

		//����
		if(strlen($_pdata->option_price)>0)
			$arOptPrice = explode(",",$_pdata->option_price);

		//�ɼ�2
		if(strlen($_pdata->option2)>0)
			$arOption2 = explode(",",$_pdata->option2);

		$prOption = " <select name=\"option\" onchange=\"change_price(this.value)\">\n";
		$prOption .="<option value=\"\">�ɼ��� �����ϼ���</option>\n";
		$prOption .="<option value=\"\">-----------------</option>\n";
		for($i=1;$i<$count;$i++)
		{
			$optPrice = ($arOptPrice)? " (".$arOptPrice[$i-1]."��)":"";
			if(sizeof($arOption2))
			{
				for($k=1;$k<sizeof($arOption2);$k++)
				{
					$posCnt = 10*($k-1)+$i;
					if($_pdata->stock_state == "Y"){
						$optCnt	= " - ����:".(($arOptCnt[$posCnt-1]>0)? $arOptCnt[$posCnt-1]."��":(($arOptCnt[$posCnt-1]=="0")? "ǰ��":"������"));
					}
					$prOption .= "<option value=\"".$i."_".$k."\">";
					$prOption .= sprintf("%s_%s%s%s"
									, $arOption1[$i]
									, $arOption2[$k]
									, $optCnt
									, $optPrice
								 );
					$prOption .= "</option>\n";
				}
			}else{
				if($_pdata->stock_state == "Y" && sizeof($arOptCnt) > 0){
					$optCnt	= " - ����:".(($arOptCnt[$i-1]>0)? $arOptCnt[$i-1]."��":(($arOptCnt[$i-1]=="0")? "ǰ��":"������"));
				}
				$prOption .= "<option value=\"".$i."_0\">";
				$prOption .= sprintf("%s%s%s"
								, $arOption1[$i]
								, $optCnt
								, $optPrice
							 );
				$prOption .= "</option>\n";
			}

		}
		$prOption .= "</select>\n";
		$prOption .= "<input type=\"hidden\" name=\"option1\" value=\"\">\n";
		$prOption .= "<input type=\"hidden\" name=\"option2\" value=\"\">\n";
	}


?>
				<tr>
					<td>
						<table cellpadding="0" cellspacing="0" width=100%>
						<tr>
							<td><img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/gongguing_info_time.gif" border="0"></td><?=$strLeftDay?>
							<td width="28" background="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/gongguing_info_timebg.gif" align="center"><span style="font-family:Verdana;font-weight:bold; font-size:22px; color:#ffffff;" id="timeleft1"><?=substr($strLeftTime,0,1)?></span></td>
							<td width="28" background="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/gongguing_info_timebg.gif" align="center"><span style="font-family:Verdana;font-weight:bold; font-size:22px; color:#ffffff;" id="timeleft2"><?=substr($strLeftTime,1,1)?></span></td>
							<td width="12" align="center"><img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/gongguing_info_timep.gif" width="12" height="30" border="0"></td>
							<td width="28" background="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/gongguing_info_timebg.gif" align="center"><span style="font-family:Verdana;font-weight:bold; font-size:22px; color:#ffffff;" id="timeleft3"><?=substr($strLeftTime,2,1)?></span></td>
							<td width="28" background="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/gongguing_info_timebg.gif" align="center"><span style="font-family:Verdana;font-weight:bold; font-size:22px; color:#ffffff;" id="timeleft4"><?=substr($strLeftTime,3,1)?></span></td>
							<td width="12" align="center"><img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/gongguing_info_timep.gif" width="12" height="30" border="0"></td>
							<td width="28" background="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/gongguing_info_timebg.gif" align="center"><span style="font-family:Verdana;font-weight:bold; font-size:22px; color:#ffffff;" id="timeleft5"><?=substr($strLeftTime,4,1)?></span></td>
							<td width="28" background="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/gongguing_info_timebg.gif" align="center"><span style="font-family:Verdana;font-weight:bold; font-size:22px; color:#ffffff;" id="timeleft6"><?=substr($strLeftTime,5,1)?></span></td>
						</tr>
						<tr>
							<td colspan=9 height=30></td>
						</tr>
						<tr>
							<td colspan=9>

								<table cellpadding="0" cellspacing="0" width=100%>
									<tr>
										<td height=25><font color="#6D6D6D" style="font-size:11px;letter-spacing:-0.5pt;word-break:break-all;">���󰡰�</font></td>
										<td align=right><strike><?=number_format($_pdata->consumerprice)?>��</strike></td>
									</tr>
									<tr>
										<td height=25><font color="#6D6D6D" style="font-size:11px;letter-spacing:-0.5pt;word-break:break-all;">���ΰ�</font></td>
										<td align=right><?=$discountRate?><b><span id="idx_price"><?=number_format($_pdata->sellprice)?></span>��</b></td>
									</tr><?if($reserveconv){?>
									<tr>
										<td height=25><font color="#6D6D6D" style="font-size:11px;letter-spacing:-0.5pt;word-break:break-all;">������</font></td>
										<td align=right><?=$reserveconv?>��</td>
									</tr><?}?>
									<tr>
										<td height=25><font color="#6D6D6D" style="font-size:11px;letter-spacing:-0.5pt;word-break:break-all;">����</font></td>
										<td align=right>
											<table cellpadding="1" cellspacing="0" width="60">
											<tr>
												<td width="33"><input type=text name="quantity" value="<?=($miniq>1?$miniq:"1")?>" size="4" style="font-size:11px;BORDER:#DFDFDF 1px solid;HEIGHT:18px;BACKGROUND-COLOR:#F7F7F7;padding-top:2pt;padding-bottom:1pt;" onkeyup="strnumkeyup(this)"></td>
												<td width="33" style="padding-left:4px;padding-right:4px;">
												<table cellpadding="0" cellspacing="0" width="100%">
												<tr>
													<td width="5" height="7" valign="top" style="padding-bottom:1px;"><a href="javascript:change_quantity('up')"><img src="<?=$Dir?>/images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_neroup.gif" border="0"></a></td>
												</tr>
												<tr>
													<td width="5" height="7" valign="bottom" style="padding-top:1px;"><a href="javascript:change_quantity('dn')"><img src="<?=$Dir?>/images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_nerodown.gif" border="0"></a></td>
												</tr>
												</table>
												</td>
												<td width="33">��</td>
											</tr>
											</table>
										</td>
									</tr>
									<?if(strlen($_pdata->option1)>0){?>
									<tr>
										<td height=25><font color="#6D6D6D" style="font-size:11px;letter-spacing:-0.5pt;word-break:break-all;"><?=$arOption1[0]?></font></td>
										<td align=right><?=$prOption?></td>
									</tr>
									<?}?>
								</table>

							</td>
						</tr>
						<tr>
							<td colspan=9 height=30></td>
						</tr>
						</table>
					</td>
				</tr>
<script type="text/javascript">
<!--
	var miniq=<?=($miniq>1?$miniq:1)?>;
<?
//�ɼǺ� ���
if(sizeof($arOption2))
	$maxnum =(sizeof($arOption2)-1)*10;
else
	$maxnum = $count-1;
if($arOptCnt>0) {
	echo "num = new Array(";

	for($i=0;$i<$maxnum;$i++) {
		if ($i!=0) echo ",";
		if(strlen($arOptCnt[$i])==0) echo "100000";
		else echo $arOptCnt[$i];
	}
	echo ");\n";
}
?>
	function change_price(selVal) {
		price = new Array(<?if($arOptPrice) echo "'".number_format($_pdata->sellprice)."'";
		for($i=0;$i<sizeof($arOptPrice);$i++) { echo ",'".number_format($arOptPrice[$i])."'"; } ?>);
		if(selVal != ""){
			var arselOpt = selVal.split("_");
			arselOpt[1] = (arselOpt[1] > 0)? arselOpt[1] :1;
			seq = parseInt(10*(arselOpt[1]-1)) + parseInt(arselOpt[0]);
			if(num[seq-1] == 0){
				alert("�ش� ��ǰ�� �ɼ��� ǰ���Ǿ����ϴ�. �ٸ� ��ǰ�� �����ϼ���.");
				document.form1.option.focus();
				return;
			}
			//document.form1.price.value = price[temp];
			if(price.length>0)
				document.getElementById("idx_price").innerHTML = price[arselOpt[0]];

		}
	}
<?
if($leftTime >0)
{
?>
	var leftTime= <?=$leftTime?>;
	var CountText ='';
	function showCountdown(){
		if(leftTime>0){
			day = Math.floor(leftTime / (3600 * 24));
			mod = leftTime % (24 * 3600);

			hour = Math.floor(mod / 3600);
			mod = mod % 3600;

			min = Math.floor(mod / 60);
			sec = mod % 60;

			if(day >0){
				document.getElementById("timeleft0").innerText = day+"��";
			}
			document.getElementById("timeleft1").innerText = Math.floor(hour / 10);
			document.getElementById("timeleft2").innerText = Math.floor(hour % 10);
			document.getElementById("timeleft3").innerText = Math.floor(min / 10);
			document.getElementById("timeleft4").innerText = Math.floor(min % 10);
			document.getElementById("timeleft5").innerText = Math.floor(sec / 10);
			document.getElementById("timeleft6").innerText = Math.floor(sec % 10);
			if (leftTime == 0){
				//document.getElementById("buyImg_"+k).src =""; //���������̹���
			}
			leftTime = leftTime-1;
		}
		setTimeout("showCountdown()", 1000);
	}
	showCountdown();
<?
}
?>
//-->
</script>
<?
}else{
?>

				<tr>
					<td height="5"></td>
				</tr>
				<tr>
					<td bgcolor="#E8E8E8" HEIGHT="1"></td>
				</tr>
				<tr>
					<td height="6"></td>
				</tr>
				<tr>
					<td width="100%">
					<table cellpadding="0" cellspacing="0" width="100%" border="0">
					<col width="14" align="center"></col>
					<col width="64"></col>
					<col width="13"></col>
					<col width=></col>
<?
				$prproductname ="<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
				$prproductname.="<td>��ǰ��</td>\n";
				$prproductname.="<td></td>";
				$prproductname.="<td>".$_pdata->productname."</td>\n";

				if(strlen($_pdata->production)>0) {
					$prproduction ="<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
					$prproduction.="<td>����ȸ��</td>\n";
					$prproduction.="<td></td>";
					$prproduction.="<td>".$_pdata->production."</td>\n";
				}
				if(strlen($_pdata->madein)>0) {
					$prmadein ="<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
					$prmadein.="<td>������</td>\n";
					$prmadein.="<td></td>";
					$prmadein.="<td>".$_pdata->madein."</td>\n";
				}
				if(strlen($_pdata->model)>0) {
					$prmodel ="<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
					$prmodel.="<td>�𵨸�</td>\n";
					$prmodel.="<td></td>";
					$prmodel.="<td>".$_pdata->model."</td>\n";
				}
				if(strlen($_pdata->brand)>0) {
					$prbrand ="<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
					$prbrand.="<td>�귣��</td>\n";
					$prbrand.="<td></td>";
					if($_data->ETCTYPE["BRANDPRO"]=="Y") {
						$prbrand.="<td><a href=\"".$Dir.FrontDir."productblist.php?brandcode=".$_pdata->brandcode."\">".$_pdata->brand."</a></td>\n";
					} else {
						$prbrand.="<td>".$_pdata->brand."</td>\n";
					}
				}
				if(strlen($_pdata->userspec)>0) {
					$specarray= explode("=",$_pdata->userspec);
					for($i=0; $i<count($specarray); $i++) {
						$specarray_exp = explode("", $specarray[$i]);
						if(strlen($specarray_exp[0])>0 || strlen($specarray_exp[1])>0) {
							${"pruserspec".$i} ="<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
							${"pruserspec".$i}.="<td>".$specarray_exp[0]."</td>\n";
							${"pruserspec".$i}.="<td></td>";
							${"pruserspec".$i}.="<td>".$specarray_exp[1]."</td>\n";
						} else {
							${"pruserspec".$i} = "";
						}
					}
				}
				if(strlen($_pdata->selfcode)>0) {
					$prselfcode ="<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
					$prselfcode.="<td>�����ڵ�</td>\n";
					$prselfcode.="<td></td>";
					$prselfcode.="<td>".$_pdata->selfcode."</td>\n";
				}
				if(strlen($_pdata->opendate)>0) {
					$propendate ="<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
					$propendate.="<td>�����</td>\n";
					$propendate.="<td></td>";
					$propendate.="<td>".@substr($_pdata->opendate,0,4).(@substr($_pdata->opendate,4,2)?"-".@substr($_pdata->opendate,4,2):"").(@substr($_pdata->opendate,6,2)?"-".@substr($_pdata->opendate,6,2):"")."</td>\n";
				}
				if($_pdata->consumerprice>0) {
					$prconsumerprice ="<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
					$prconsumerprice.="<td>���߰���</td>\n";
					$prconsumerprice.="<td></td>";
					$prconsumerprice.="<td><IMG SRC=\"".$Dir."images/common/won_icon2.gif\" border=\"0\" align=absmiddle><strike>".number_format($_pdata->consumerprice)."</strike>��</td>\n";
				}
				$SellpriceValue=0;
				if(strlen($dicker=dickerview($_pdata->etctype,number_format($_pdata->sellprice),1))>0) {
					$prsellprice ="<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
					$prsellprice.="<td>�ǸŰ���</td>\n";
					$prsellprice.="<td></td>";
					$prsellprice.="<td>".$dicker."</td>\n";
					$prdollarprice="";
					$priceindex=0;
				} else if(strlen($optcode)==0 && strlen($_pdata->option_price)>0) {
					$option_price = $_pdata->option_price;
					$pricetok=explode(",",$option_price);
					$priceindex = count($pricetok);
					for($tmp=0;$tmp<=$priceindex;$tmp++) {
						$pricetokdo[$tmp]=number_format($pricetok[$tmp]/$ardollar[1],2);
						$pricetok[$tmp]=number_format($pricetok[$tmp]);
					}
					$prsellprice ="<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
					$prsellprice.="<td>�ǸŰ���</td>\n";
					$prsellprice.="<td></td>";
					$prsellprice.="<td><img src=\"".$Dir."images/common/won_icon.gif\" border=\"0\" align=\"absmiddle\"><b><FONT color=\"#F02800\" id=\"idx_price\">".number_format($_pdata->sellprice)."��</FONT></b></td>\n";
					$prsellprice.="<input type=hidden name=price value=\"".number_format($_pdata->sellprice)."\">\n";

					$prdollarprice ="<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
					$prdollarprice.="<td>�ؿ�ȭ��</td>\n";
					$prdollarprice.="<td></td>";
					$prdollarprice.="<td><FONT id=\"idx_dollarprice\">".$ardollar[0]." ".number_format($_pdata->sellprice/$ardollar[1],2)." ".$ardollar[2]."</FONT></td>\n";
					$prdollarprice.="<input type=hidden name=dollarprice value=\"".number_format($_pdata->sellprice/$ardollar[1],2)."\">\n";
					$SellpriceValue=str_replace(",","",$pricetok[0]);
				} else if(strlen($optcode)>0) {
					$prsellprice ="<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
					$prsellprice.="<td>�ǸŰ���</td>\n";
					$prsellprice.="<td></td>";
					$prsellprice.="<td><img src=\"".$Dir."images/common/won_icon.gif\" border=\"0\" align=\"absmiddle\"><b><FONT color=\"#F02800\" id=\"idx_price\">".number_format($_pdata->sellprice)."��</FONT></b></td>\n";
					$prsellprice.="<input type=hidden name=price value=\"".number_format($_pdata->sellprice)."\">\n";

					$prdollarprice ="<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
					$prdollarprice.="<td>�ؿ�ȭ��</td>\n";
					$prdollarprice.="<td></td>";
					$prdollarprice.="<td><FONT id=\"idx_dollarprice\">".$ardollar[0]." ".number_format($_pdata->sellprice/$ardollar[1],2)." ".$ardollar[2]."</FONT></td>\n";
					$prdollarprice.="<input type=hidden name=dollarprice value=\"".number_format($_pdata->sellprice/$ardollar[1],2)."\">\n";
					$SellpriceValue=$_pdata->sellprice;
				} else if(strlen($_pdata->option_price)==0) {
					if($_pdata->assembleuse=="Y") {
						$prsellprice ="<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
						$prsellprice.="<td>�ǸŰ���</td>\n";
						$prsellprice.="<td></td>";
						$prsellprice.="<td><img src=\"".$Dir."images/common/won_icon.gif\" border=\"0\" align=\"absmiddle\"><b><FONT color=\"#F02800\" id=\"idx_price\">".number_format(($miniq>1?$miniq*$_pdata->sellprice:$_pdata->sellprice))."��</FONT></b></td>\n";
						$prsellprice.="<input type=hidden name=price value=\"".number_format(($miniq>1?$miniq*$_pdata->sellprice:$_pdata->sellprice))."\">\n";

						$prdollarprice ="<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
						$prdollarprice.="<td>�ؿ�ȭ��</td>\n";
						$prdollarprice.="<td></td>";
						$prdollarprice.="<td><FONT id=\"idx_dollarprice\">".$ardollar[0]." ".number_format(($miniq>1?$miniq*$_pdata->sellprice:$_pdata->sellprice)/$ardollar[1],2)." ".$ardollar[2]."</FONT></td>\n";
						$prdollarprice.="<input type=hidden name=dollarprice value=\"".number_format(($miniq>1?$miniq*$_pdata->sellprice:$_pdata->sellprice)/$ardollar[1],2)."\">\n";
						$SellpriceValue=($miniq>1?$miniq*$_pdata->sellprice:$_pdata->sellprice);
					} else {
						$prsellprice ="<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
						$prsellprice.="<td>�ǸŰ���</td>\n";
						$prsellprice.="<td></td>";
						$prsellprice.="<td><img src=\"".$Dir."images/common/won_icon.gif\" border=\"0\" align=\"absmiddle\"><b><FONT color=\"#F02800\" id=\"idx_price\">".number_format($_pdata->sellprice)."��</FONT></b></td>\n";
						$prsellprice.="<input type=hidden name=price value=\"".number_format($_pdata->sellprice)."\">\n";

						$prdollarprice ="<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
						$prdollarprice.="<td>�ؿ�ȭ��</td>\n";
						$prdollarprice.="<td></td>";
						$prdollarprice.="<td><FONT id=\"idx_dollarprice\">".$ardollar[0]." ".number_format($_pdata->sellprice/$ardollar[1],2)." ".$ardollar[2]."</FONT></td>\n";
						$prdollarprice.="<input type=hidden name=dollarprice value=\"".number_format($_pdata->sellprice/$ardollar[1],2)."\">\n";
						$SellpriceValue=$_pdata->sellprice;
					}
					$priceindex=0;
				}
				$reserveconv=getReserveConversion($_pdata->reserve,$_pdata->reservetype,$_pdata->sellprice,"Y");
				//snsȫ���� ��� ������
				if($_data->sns_ok == "Y" && $_pdata->sns_state == "Y" && $sell_memid !=""){
					$reserveconv = getReserveConversionSNS($reserveconv,$_pdata->sns_reserve2,$_pdata->sns_reserve2_type,$_pdata->sellprice,"Y");
				}
				if($reserveconv>0) {
					$prreserve ="<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
					$prreserve.="<td>������</td>\n";
					$prreserve.="<td></td>";
					$prreserve.="<td><IMG SRC=\"".$Dir."images/common/reserve_icon1.gif\" border=\"0\" align=absmiddle>";
					if($sell_memid !=""){
						$prreserve.="<span style=\"color:#CC0000\">(snsȫ��)</span> ";
					}
					$prreserve.="<b><FONT id=\"idx_reserve\">".number_format($reserveconv)."��</font></b></td>\n";
				}
				if(strlen($_pdata->addcode)>0) {
					$praddcode ="<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
					$praddcode.="<td>Ư�̻���</td>\n";
					$praddcode.="<td></td>";
					$praddcode.="<td>".$_pdata->addcode."</td>\n";
				}

				$prquantity ="<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
				$prquantity.="<td>���ż���</td>\n";
				$prquantity.="<td></td>";
				$prquantity.="<td>\n";
				$prquantity.="<table cellpadding=\"1\" cellspacing=\"0\" width=\"60\">\n";
				$prquantity.="<tr>\n";
				$prquantity.="	<td width=\"33\"><input type=text name=\"quantity\" value=\"".($miniq>1?$miniq:"1")."\" size=\"4\" style=\"font-size:11px;BORDER:#DFDFDF 1px solid;HEIGHT:18px;BACKGROUND-COLOR:#F7F7F7;padding-top:2pt;padding-bottom:1pt;\"".($_pdata->assembleuse=="Y"?" readonly":" onkeyup=\"strnumkeyup(this)\"");
				if(substr($productcode,0,3)=='999') $prquantity.=" readonly";
				$prquantity.="></td>\n";
				$prquantity.="	<td width=\"33\" style=\"padding-left:4px;padding-right:4px;\">\n";
				if(substr($productcode,0,3)!='999') {
					$prquantity.="	<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
					$prquantity.="	<tr>\n";
					$prquantity.="		<td width=\"5\" height=\"7\" valign=\"top\" style=\"padding-bottom:1px;\"><a href=\"javascript:change_quantity('up')\"><img src=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_neroup.gif\" border=\"0\"></a></td>\n";
					$prquantity.="	</tr>\n";
					$prquantity.="	<tr>\n";
					$prquantity.="		<td width=\"5\" height=\"7\" valign=\"bottom\" style=\"padding-top:1px;\"><a href=\"javascript:change_quantity('dn')\"><img src=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_nerodown.gif\" border=\"0\"></a></td>\n";
					$prquantity.="	</tr>\n";
					$prquantity.="	</table>\n";
				}
				$prquantity.="	</td>\n";
				$prquantity.="	<td width=\"33\">EA</td>\n";
				$prquantity.="</tr>\n";
				$prquantity.="</table>\n";
				$prquantity.="</td>\n";

				// ��Ű�� ���� ���
				$arrpackage_title=array();
				$arrpackage_list=array();
				$arrpackage_price=array();
				$arrpackage_pricevalue=array();
				if((int)$_pdata->package_num>0) {
					$sql = "SELECT * FROM tblproductpackage WHERE num='".(int)$_pdata->package_num."' ";
					$result = mysql_query($sql,get_db_conn());
					$package_count=0;
					if($row = @mysql_fetch_object($result)) {
						mysql_free_result($result);
						if(strlen($row->package_title)>0) {
							$arrpackage_title = explode("",$row->package_title);
							$arrpackage_list = explode("",$row->package_list);
							$arrpackage_price = explode("",$row->package_price);

							$package_listrep = str_replace("","",$row->package_list);

							if(strlen($package_listrep)>0) {
								$sql = "SELECT pridx,productcode,productname,sellprice,tinyimage,quantity,etctype FROM tblproduct ";
								$sql.= "WHERE pridx IN ('".str_replace(",","','",$package_listrep)."') ";
								$sql.= "AND assembleuse!='Y' ";
								$sql.= "AND display='Y' ";
								$result2 = mysql_query($sql,get_db_conn());
								while($row2 = @mysql_fetch_object($result2)) {
									$arrpackage_proinfo[productcode][$row2->pridx] = $row2->productcode;
									$arrpackage_proinfo[productname][$row2->pridx] = $row2->productname;
									$arrpackage_proinfo[sellprice][$row2->pridx] = $row2->sellprice;
									$arrpackage_proinfo[tinyimage][$row2->pridx] = $row2->tinyimage;
									$arrpackage_proinfo[quantity][$row2->pridx] = $row2->quantity;
									$arrpackage_proinfo[etctype][$row2->pridx] = $row2->etctype;
								}
								@mysql_free_result($result2);
							}

							for($t=1; $t<count($arrpackage_list); $t++) {
								$arrpackage_pricevalue[0]=0;
								$arrpackage_pricevalue[$t]=0;
								if(strlen($arrpackage_list[$t])>0) {
									$arrpackage_list_exp = explode(",",$arrpackage_list[$t]);
									$sumsellprice=0;
									for($tt=0; $tt<count($arrpackage_list_exp); $tt++) {
										$sumsellprice += (int)$arrpackage_proinfo[sellprice][$arrpackage_list_exp[$tt]];
									}

									if((int)$sumsellprice>0) {
										$arrpackage_pricevalue[$t]=(int)$sumsellprice;
										if(strlen($arrpackage_price[$t])>0) {
											$arrpackage_price_exp = explode(",",$arrpackage_price[$t]);
											if(strlen($arrpackage_price_exp[0])>0 && $arrpackage_price_exp[0]>0) {
												$sumsellpricecal=0;
												if($arrpackage_price_exp[1]=="Y") {
													$sumsellpricecal = ((int)$sumsellprice*$arrpackage_price_exp[0])/100;
												} else {
													$sumsellpricecal = $arrpackage_price_exp[0];
												}
												if($sumsellpricecal>0) {
													if($arrpackage_price_exp[2]=="Y") {
														$sumsellpricecal = $sumsellprice-$sumsellpricecal;
													} else {
														$sumsellpricecal = $sumsellprice+$sumsellpricecal;
													}
													if($sumsellpricecal>0) {
														if($arrpackage_price_exp[4]=="F") {
															$sumsellpricecal = floor($sumsellpricecal/($arrpackage_price_exp[3]*10))*($arrpackage_price_exp[3]*10);
														} else if($arrpackage_price_exp[4]=="R") {
															$sumsellpricecal = round($sumsellpricecal/($arrpackage_price_exp[3]*10))*($arrpackage_price_exp[3]*10);
														} else {
															$sumsellpricecal = ceil($sumsellpricecal/($arrpackage_price_exp[3]*10))*($arrpackage_price_exp[3]*10);
														}
														$arrpackage_pricevalue[$t]=$sumsellpricecal;
													}
												}
											}
										}
									}
								}
								$propackage_option.= "<option value=\"".$t."\" style=\"color:#ffffff;\">".$arrpackage_title[$t]."</option>\n";
								$package_count++;
							}
						}
					}

					if($package_count>0) {
						$prpackage ="<tr height=\"22\">";
						$prpackage.="	<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
						$prpackage.="	<td>��Ű������</td>\n";
						$prpackage.="	<td></td>";
						$prpackage.="	<td>\n";
						$prpackage.="	<select name=\"package_idx\" size=\"1\" style=\"font-size:11px;background-color:#404040;letter-spacing:-0.5pt;\" ";
						if($_data->proption_size>0) $prpackage.="style=\"width : ".$_data->proption_size."px;\" ";
						$prpackage.=")\" onchange=\"packagecal()\">\n";
						$prpackage.=	"<option value=\"\" style=\"color:#ffffff;\">��Ű���� �����ϼ���</option>\n";
						$prpackage.=	"<option value=\"\" style=\"color:#ffffff;\">-------------------\n";
						$prpackage.=	$propackage_option;
						$prpackage.="	</select>\n";
						$prpackage.="	</td>\n";
						$prpackage.="</tr>\n";
						$prpackage.="<input type=hidden name=\"package_type\" value=\"".$row->package_type."\">\n";
					}
				}

				$proption1="";
				if(strlen($_pdata->option1)>0) {
					$temp = $_pdata->option1;
					$tok = explode(",",$temp);
					$count=count($tok);
					$proption1.="<table cellpadding=\"0\" cellspacing=\"0\">\n";
					$proption1.="<tr>\n";
					$proption1.="	<td align=\"right\">$tok[0]&nbsp;:&nbsp;</td>\n";
					$proption1.="	<td>";
					if ($priceindex!=0) {
						$proption1.="<select name=\"option1\" size=\"1\" style=\"font-size:11px;background-color:#404040;letter-spacing:-0.5pt;\" ";
						if($_data->proption_size>0) $proption1.="style=\"width : ".$_data->proption_size."px\" ";
						$proption1.="onchange=\"change_price(1,document.form1.option1.selectedIndex-1,";
						if(strlen($_pdata->option2)>0) $proption1.="document.form1.option2.selectedIndex-1";
						else $proption1.="''";
						$proption1.=")\">\n";
					} else {
						$proption1.="<select name=\"option1\" size=\"1\" style=\"font-size:11px;background-color:#404040;letter-spacing:-0.5pt;\" ";
						if($_data->proption_size>0) $proption1.="style=\"width : ".$_data->proption_size."px\" ";
						$proption1.="onchange=\"change_price(0,document.form1.option1.selectedIndex-1,";
						if(strlen($_pdata->option2)>0) $proption1.="document.form1.option2.selectedIndex-1";
						else $proption1.="''";
						$proption1.=")\">\n";
					}

					$optioncnt = explode(",",substr($_pdata->option_quantity,1));
					$proption1.="<option value=\"\" style=\"color:#ffffff;\">�ɼ��� �����ϼ���\n";
					$proption1.="<option value=\"\" style=\"color:#ffffff;\">-----------------\n";
					for($i=1;$i<$count;$i++) {
						if(strlen($tok[$i])>0) $proption1.="<option value=\"$i\" style=\"color:#ffffff;\">$tok[$i]\n";
						if(strlen($_pdata->option2)==0 && $optioncnt[$i-1]=="0") $proption1.=" (ǰ��)";
					}
					$proption1.="</select>";
				} else {
					//$proption1.="<input type=hidden name=option1>";
				}

				$proption2="";
				if(strlen($_pdata->option2)>0) {
					$temp = $_pdata->option2;
					$tok = explode(",",$temp);
					$count2=count($tok);
					if(strlen($_pdata->option1)<=0) {
						$proption2.="<table cellpadding=\"0\" cellspacing=\"0\">\n";
					}
					$proption2.="<tr>\n";
					$proption2.="	<td align=\"right\">$tok[0]&nbsp;:&nbsp;</td>\n";
					$proption2.="	<td>";
					$proption2.="<select name=\"option2\" size=\"1\" style=\"font-size:11px;background-color:#404040;letter-spacing:-0.5pt;\" ";
					if($_data->proption_size>0) $proption2.="style=\"width : ".$_data->proption_size."px\" ";
					$proption2.="onchange=\"change_price(0,";
					if(strlen($_pdata->option1)>0) $proption2.="document.form1.option1.selectedIndex-1";
					else $proption2.="''";
					$proption2.=",document.form1.option2.selectedIndex-1)\">\n";
					$proption2.="<option value=\"\" style=\"color:#ffffff;\">�ɼ��� �����ϼ���\n";
					$proption2.="<option value=\"\" style=\"color:#ffffff;\">-----------------\n";
					for($i=1;$i<$count2;$i++) if(strlen($tok[$i])>0) $proption2.="<option value=\"$i\" style=\"color:#ffffff;\">$tok[$i]\n";
					$proption2.="</select>";
					$proption2.="	</td>\n";
					$proption2.="</tr>\n";
					$proption2.="</table>\n";
				} else {
					//$proption2.="<input type=hidden name=option2>";
					if(strlen($_pdata->option1)>0) {
					$proption1.="	</td>\n";
					$proption1.="</tr>\n";
					$proption1.="</table>\n";
					}
				}

				if(strlen($optcode)>0) {
					$sql = "SELECT * FROM tblproductoption WHERE option_code='".$optcode."' ";
					$result = mysql_query($sql,get_db_conn());
					if($row = mysql_fetch_object($result)) {
						$optionadd = array (&$row->option_value01,&$row->option_value02,&$row->option_value03,&$row->option_value04,&$row->option_value05,&$row->option_value06,&$row->option_value07,&$row->option_value08,&$row->option_value09,&$row->option_value10);
						$opti=0;
						$option_choice = $row->option_choice;
						$exoption_choice = explode("",$option_choice);
						$proption3.="<TABLE cellSpacing=\"0\" cellPadding=\"0\" border=\"0\">\n";
						while(strlen($optionadd[$opti])>0) {
							$proption3.="[OPT]";
							$proption3.="<select name=\"mulopt\" style=\"font-size:11px;background-color:#404040;letter-spacing:-0.5pt;\" onchange=\"chopprice('$opti')\"";
							if($_data->proption_size>0) $proption3.=" style=\"width : ".$_data->proption_size."px\"";
							$proption3.=">";
							$opval = str_replace('"','',explode("",$optionadd[$opti]));
							$proption3.="<option value=\"0,0\" style=\"color:#ffffff;\">--- ".$opval[0].($exoption_choice[$opti]==1?"(�ʼ�)":"(����)")." ---";
							$opcnt=count($opval);
							for($j=1;$j<$opcnt;$j++) {
								$exop = str_replace('"','',explode(",",$opval[$j]));
								$proption3.="<option value=\"".$opval[$j]."\" style=\"color:#ffffff;\">";
								if($exop[1]>0) $proption3.=$exop[0]."(+".$exop[1]."��)";
								else if($exop[1]==0) $proption3.=$exop[0];
								else $proption3.=$exop[0]."(".$exop[1]."��)";
							}
							$proption3.="</select><input type=hidden name=\"opttype\" value=\"0\"><input type=hidden name=\"optselect\" value=\"".$exoption_choice[$opti]."\">[OPTEND]";
							$opti++;
						}
						$proption3.="<input type=hidden name=\"mulopt\"><input type=hidden name=\"opttype\"><input type=hidden name=\"optselect\">";
						$proption3.="</TABLE>\n";
					}
					mysql_free_result($result);
				}

				for($i=0;$i<$prcnt;$i++) {
					if(substr($arexcel[$i],0,1)=="O") {	//����
						echo "<tr><td colspan=\"4\" height=\"5\" bgcolor=\"#FFFFFF\"></td></tr>\n";
					} else if ($arexcel[$i]=="7") {	//�ɼ�
						if(strlen($proption1)>0 || strlen($proption2)>0 || strlen($proption3)>0) {
							$proption ="<tr height=\"22\">";
							$proption.="	<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
							$proption.="	<td>��ǰ�ɼ�</td>\n";
							$proption.="	<td></td>";
							$proption.="	<td>\n";
							//$proption.="	<TABLE cellSpacing=\"0\" cellPadding=\"0\" border=\"0\">\n";
							if(strlen($proption1)>0) {
								$proption.=$proption1;
							}
							if(strlen($proption2)>0) {
								$proption.=$proption2;
							}
							if(strlen($proption3)>0) {
								$pattern=array("[OPT]","[OPTEND]");
								$replace=array("<tr><td>","</td></tr>");
								$proption.=str_replace($pattern,$replace,$proption3);
							}
							//$proption.="	</table>\n";
							$proption.="	</td>\n";
							$proption.="</tr>\n";

							echo $arproduct[$arexcel[$i]];
						} else {
							$proption ="<input type=hidden name=\"option1\">\n";
							$proption.="<input type=hidden name=\"option2\">\n";
						}
					} else if(strlen($arproduct[$arexcel[$i]])>0) {	//
						echo "<tr height=\"22\">".$arproduct[$arexcel[$i]]."</tr>\n";
						//echo "<tr><td height=1 bgcolor=#FFFFFF></td></tr>\n";
						if($arexcel[$i]=="9") $dollarok="Y";
					}
				}
				// ���Ұ�üũ ����
				$useableStr = '';
				foreach($_pdata->checkAbles as $chkidx=>$etcchk){
					switch($chkidx){
						case 'coupon': $etcname= '��������'; break;
						case 'reserve': $etcname= '������'; break;
						case 'gift': $etcname= '���Ż���ǰ'; break;
						case 'return': $etcname= '��ȯ��ȯ��'; break;
					}
					$useableStr .="<tr height=\"22\">";
					$useableStr.="	<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
					$useableStr.="	<td>".$etcname."</td>\n";
					$useableStr.="	<td></td>";
					$useableStr.="	<td>".(($etcchk == 'Y')?'<span style="color:blue">���밡��</span>':'<span style="color:red">���밡��</span>').'</td>';
				}
				echo $useableStr;
				//#���Ұ�üũ ����
?>
					</table>
					</td>
				</tr>
<script language="JavaScript">
var miniq=<?=($miniq>1?$miniq:1)?>;
var ardollar=new Array(3);
ardollar[0]="<?=$ardollar[0]?>";
ardollar[1]="<?=$ardollar[1]?>";
ardollar[2]="<?=$ardollar[2]?>";
<?
if(strlen($optcode)==0) {
	$maxnum=($count2-1)*10;
	if($optioncnt>0) {
		echo "num = new Array(";
		for($i=0;$i<$maxnum;$i++) {
			if ($i!=0) echo ",";
			if(strlen($optioncnt[$i])==0) echo "100000";
			else echo $optioncnt[$i];
		}
		echo ");\n";
	}
?>

function change_price(temp,temp2,temp3) {
<?=(strlen($dicker)>0)?"return;\n":"";?>
	if(temp3=="") temp3=1;
	price = new Array(<?if($priceindex>0) echo "'".number_format($_pdata->sellprice)."','".number_format($_pdata->sellprice)."',"; for($i=0;$i<$priceindex;$i++) { if ($i!=0) { echo ",";} echo "'".$pricetok[$i]."'"; } ?>);
	doprice = new Array(<?if($priceindex>0) echo "'".number_format($_pdata->sellprice/$ardollar[1],2)."','".number_format($_pdata->sellprice/$ardollar[1],2)."',"; for($i=0;$i<$priceindex;$i++) { if ($i!=0) { echo ",";} echo "'".$pricetokdo[$i]."'"; } ?>);
	if(temp==1) {
		if (document.form1.option1.selectedIndex><? echo $priceindex+2 ?>)
			temp = <?=$priceindex?>;
		else temp = document.form1.option1.selectedIndex;
		document.form1.price.value = price[temp];
		document.all["idx_price"].innerHTML = document.form1.price.value+"��";
<?if($_pdata->reservetype=="Y" && $_pdata->reserve>0) { ?>
		if(document.getElementById("idx_reserve")) {
			var reserveInnerValue="0";
			if(document.form1.price.value.length>0) {
				var ReservePer=<?=$_pdata->reserve?>;
				var ReservePriceValue=Number(document.form1.price.value.replace(/,/gi,""));
				if(ReservePriceValue>0) {
					reserveInnerValue = Math.round(ReservePer*ReservePriceValue*0.01)+"";
					var result = "";
					for(var i=0; i<reserveInnerValue.length; i++) {
						var tmp = reserveInnerValue.length-(i+1);
						if(i%3==0 && i!=0) result = "," + result;
						result = reserveInnerValue.charAt(tmp) + result;
					}
					reserveInnerValue = result;
				}
			}
			document.getElementById("idx_reserve").innerHTML = reserveInnerValue+"��";
		}
<? } ?>
		if(typeof(document.form1.dollarprice)=="object") {
			document.form1.dollarprice.value = doprice[temp];
			document.all["idx_dollarprice"].innerHTML=ardollar[0]+" "+document.form1.dollarprice.value+" "+ardollar[2];
		}
	}
	packagecal(); //��Ű�� ��ǰ ����
	if(temp2>0 && temp3>0) {
		if(num[(temp3-1)*10+(temp2-1)]==0){
			alert('�ش� ��ǰ�� �ɼ��� ǰ���Ǿ����ϴ�. �ٸ� ��ǰ�� �����ϼ���');
			if(document.form1.option1.type!="hidden") document.form1.option1.focus();
			return;
		}
	} else {
		if(temp2<=0 && document.form1.option1.type!="hidden") document.form1.option1.focus();
		else document.form1.option2.focus();
		return;
	}
}

<? } else if(strlen($optcode)>0) { ?>

function chopprice(temp){
<?=(strlen($dicker)>0)?"return;\n":"";?>
	ind = document.form1.mulopt[temp];
	price = ind.options[ind.selectedIndex].value;
	originalprice = document.form1.price.value.replace(/,/g, "");
	document.form1.price.value=Number(originalprice)-Number(document.form1.opttype[temp].value);
	if(price.indexOf(",")>0) {
		optprice = price.substring(price.indexOf(",")+1);
	} else {
		optprice=0;
	}
	document.form1.price.value=Number(document.form1.price.value)+Number(optprice);
	if(typeof(document.form1.dollarprice)=="object") {
		document.form1.dollarprice.value=(Math.round(((Number(document.form1.price.value))/ardollar[1])*100)/100);
		document.all["idx_dollarprice"].innerHTML=ardollar[0]+" "+document.form1.dollarprice.value+" "+ardollar[2];
	}
	document.form1.opttype[temp].value=optprice;
	var num_str = document.form1.price.value.toString()
	var result = ''

	for(var i=0; i<num_str.length; i++) {
		var tmp = num_str.length-(i+1)
		if(i%3==0 && i!=0) result = ',' + result
		result = num_str.charAt(tmp) + result
	}
	document.form1.price.value = result;
	document.all["idx_price"].innerHTML=document.form1.price.value+"��";
	packagecal(); //��Ű�� ��ǰ ����
}

<?}?>
<? if($_pdata->assembleuse=="Y") { ?>
function setTotalPrice(tmp) {
<?=(strlen($dicker)>0)?"return;\n":"";?>
	var i=true;
	var j=1;
	var totalprice=0;
	while(i) {
		if(document.getElementById("acassemble"+j)) {
			if(document.getElementById("acassemble"+j).value) {
				arracassemble = document.getElementById("acassemble"+j).value.split("|");
				if(arracassemble[2].length) {
					totalprice += arracassemble[2]*1;
				}
			}
		} else {
			i=false;
		}
		j++;
	}
	totalprice = totalprice*tmp;
	var num_str = totalprice.toString();
	var result = '';
	for(var i=0; i<num_str.length; i++) {
		var tmp = num_str.length-(i+1);
		if(i%3==0 && i!=0) result = ',' + result;
		result = num_str.charAt(tmp) + result;
	}
	if(typeof(document.form1.price)=="object") { document.form1.price.value=totalprice; }
	if(typeof(document.form1.dollarprice)=="object") {
		document.form1.dollarprice.value=(Math.round(((Number(document.form1.price.value))/ardollar[1])*100)/100);
		document.all["idx_dollarprice"].innerHTML=ardollar[0]+" "+document.form1.dollarprice.value+" "+ardollar[2];
	}
	if(document.getElementById("idx_assembleprice")) { document.getElementById("idx_assembleprice").value = result; }
	if(document.getElementById("idx_price")) { document.getElementById("idx_price").innerHTML = result+"��"; }
	if(document.getElementById("idx_price_graph")) { document.getElementById("idx_price_graph").innerHTML = result+"��"; }
	<?if($_pdata->reservetype=="Y" && $_pdata->reserve>0) { ?>
		if(document.getElementById("idx_reserve")) {
			var reserveInnerValue="0";
			if(document.form1.price.value.length>0) {
				var ReservePer=<?=$_pdata->reserve?>;
				var ReservePriceValue=Number(document.form1.price.value.replace(/,/gi,""));
				if(ReservePriceValue>0) {
					reserveInnerValue = Math.round(ReservePer*ReservePriceValue*0.01)+"";
					var result = "";
					for(var i=0; i<reserveInnerValue.length; i++) {
						var tmp = reserveInnerValue.length-(i+1);
						if(i%3==0 && i!=0) result = "," + result;
						result = reserveInnerValue.charAt(tmp) + result;
					}
					reserveInnerValue = result;
				}
			}
			document.getElementById("idx_reserve").innerHTML = reserveInnerValue+"��";
		}
	<? } ?>
}
<? } ?>

function packagecal() {
<?=(count($arrpackage_pricevalue)==0?"return;\n":"")?>
	pakageprice = new Array(<? for($i=0;$i<count($arrpackage_pricevalue);$i++) { if ($i!=0) { echo ",";} echo "'".$arrpackage_pricevalue[$i]."'"; }?>);
	var result = "";
	var intgetValue = document.form1.price.value.replace(/,/g, "");
	var temppricevalue = "0";
	for(var j=1; j<pakageprice.length; j++) {
		if(document.getElementById("idx_price"+j)) {
			temppricevalue = (Number(intgetValue)+Number(pakageprice[j])).toString();
			result="";
			for(var i=0; i<temppricevalue.length; i++) {
				var tmp = temppricevalue.length-(i+1);
				if(i%3==0 && i!=0) result = "," + result;
				result = temppricevalue.charAt(tmp) + result;
			}
			document.getElementById("idx_price"+j).innerHTML=result+"��";
		}
	}

	if(typeof(document.form1.package_idx)=="object") {
		var packagePriceValue = Number(intgetValue)+Number(pakageprice[Number(document.form1.package_idx.value)]);

		if(packagePriceValue>0) {
			result = "";
			packagePriceValue = packagePriceValue.toString();
			for(var i=0; i<packagePriceValue.length; i++) {
				var tmp = packagePriceValue.length-(i+1);
				if(i%3==0 && i!=0) result = "," + result;
				result = packagePriceValue.charAt(tmp) + result;
			}
			returnValue = result;
		} else {
			returnValue = "0";
		}
		if(document.getElementById("idx_price")) {
			document.getElementById("idx_price").innerHTML=returnValue+"��";
		}
		if(document.getElementById("idx_price_graph")) {
			document.getElementById("idx_price_graph").innerHTML=returnValue+"��";
		}
		if(typeof(document.form1.dollarprice)=="object") {
			document.form1.dollarprice.value=Math.round((packagePriceValue/ardollar[1])*100)/100;
			if(document.getElementById("idx_price_graph")) {
				document.getElementById("idx_price_graph").innerHTML=ardollar[0]+" "+document.form1.dollarprice.value+" "+ardollar[2];
			}
		}
	}
}
</script>
<?}?>
				<tr>
					<td height="10"></td>
				</tr>


				<tr>
					<td style="border-top:1px solid #eeeeee; padding:20px;">
			<?
			if(eregi("S",$_cdata->type)) {
				echo $sellstateBtn;
			}else{
				if(substr($productcode,0,3)=='999') {
					if(strlen($_pdata->quantity)>0 && $_pdata->quantity<=0)
						echo "<FONT style=\"color:#F02800;\"><b>ǰ ��</b></FONT>";
					else {
						echo "<a href=\"javascript:CheckForm('ordernow2','".$opti."')\" onMouseOver=\"window.status='�����ϱ�';return true;\"><img src=../images/design/happycopon_btn02.gif></a>\n";
						echo "<a href=\"javascript:CheckForm('ordernow3','".$opti."')\" onMouseOver=\"window.status='���α���';return true;\"><img src=../images/design/happycopon_btn01.gif></a>\n";
					}
				}
				else if(strlen($dicker)==0) {
					if(strlen($_pdata->quantity)>0 && $_pdata->quantity<=0)
						echo "<FONT style=\"color:#F02800;\"><b>ǰ ��</b></FONT>";
					else {
						echo "<a href=\"javascript:CheckForm('ordernow','".$opti."')\" onMouseOver=\"window.status='�ٷα���';return true;\"><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_btn01.gif\" border=0 align=middle></a>\n";
						echo "<a href=\"javascript:CheckForm('','".$opti."')\" onMouseOver=\"window.status='��ٱ��ϴ��';return true;\"><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_btn02.gif\" hspace=\"3\" border=\"0\" align=middle></a>\n";
					}
					if (strlen($_ShopInfo->getMemid())>0 && $_ShopInfo->getMemid()!="deleted") {
						echo "<a href=\"javascript:CheckForm('wishlist','".$opti."')\"><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_btn03.gif\" border=0 align=absmiddle></a>\n";
					} else {
						echo "<a href=\"javascript:check_login();\"><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_btn03.gif\" border=0 align=absmiddle></a>\n";
					}
				}
}
?>
					</td>
				</tr>
				</table>
				</td>
			</tr>
			</table>
			</td>
		</tr>

		<input type=hidden name=code value="<?=$code?>">
		<input type=hidden name=productcode value="<?=$productcode?>">
		<input type=hidden name=ordertype>
		<input type=hidden name=opts>
		<input type=hidden name=sell_memid value="<?=$sell_memid?>">
		<?=($brandcode>0?"<input type=hidden name=brandcode value=\"".$brandcode."\">\n":"")?>
		<?if($detailimg_eventloc=="1"){?>
		<!--
		<tr>
			<td height="20"></td>
		</tr>
		<tr>
			<td><?//=$detailimg_body?></td>
		</tr>
		-->
		<?}?>

<?
	if($package_count>0) { //��Ű�� ��ǰ ���
?>
		<!-- ��Ű�� ��ǰ ��� ���� //-->
		<tr>
			<td height="20"></td>
		</tr>
		<tr>
			<td>
			<table cellpadding="0" cellspacing="0" width="100%" height="100">
			<tr>
				<td><IMG SRC="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/skin_tag_t01.gif" border="0"></td>
				<td width="100%" background="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/skin_tag_t02.gif"></td>
				<td><IMG SRC="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/skin_tag_t03.gif" border="0"></td>
			</tr>
			<tr>
				<td height="100%" background="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/skin_tag_t08.gif"></td>
				<td width="100%" bgcolor="#F8F8F8" valign="top" style="padding:3px;">
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td bgcolor="#FFFFFF" style="border:1px #EDEDED solid;">
					<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<col width="130"></col>
					<col width=""></col>
<?
		$packagecoll=5;
		for($j=1; $j<count($arrpackage_title); $j++) {
			$arrpackage_list_exp = explode(",", $arrpackage_list[$j]);
?>
					<tr>
						<td align="center" bgcolor="#F8F8F8" style="padding:5px;border-right:1px #EDEDED solid;border-bottom:1px #EDEDED solid;">
						<table border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td align="center"><b><?=$arrpackage_title[$j]?></b></td>
						</tr>
						<tr>
							<td align="center" style="padding:3px;"><?=(strlen($dicker)>0?$dicker:"<img src=\"".$Dir."images/common/won_icon.gif\" border=\"0\" align=\"absmiddle\"><b><FONT color=\"#F02800\" id=\"idx_price".$j."\">".number_format($SellpriceValue+$arrpackage_pricevalue[$j])."��</font></b>")?></td>
						</tr>
						</table>
						</td>
						<td style="border-bottom:1px #EDEDED solid;">
						<table border="0" cellpadding="0" cellspacing="0" width=100%>
						<tr>
							<td width=100% style="padding:5">
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td width="<?=ceil(100/$packagecoll)?>%" valign="top" align="center" style="padding:5px;">
								<table border="0" cellpadding="0" cellspacing="0" width="90">
								<tr>
									<td align="center" valign=middle style="border:1px #EAEAEA solid;padding:10px;" bgcolor="#EDEDED">
<?
					if (strlen($_pdata->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$_pdata->tinyimage)==true) {
						echo "<img src=\"".$Dir.DataDir."shopimages/product/".urlencode($_pdata->tinyimage)."\" border=\"0\" ";
						$width = getimagesize($Dir.DataDir."shopimages/product/".$_pdata->tinyimage);
						if($width[0]>$width[1]) echo "width=\"70\"> ";
						else echo "height=\"70\">";
					} else {
						echo "<img src=\"".$Dir."images/no_img.gif\" width=\"70\" border=\"0\">";
					}
?></td>
								</tr>
								<tr>
									<td height="3"></td>
								</tr>
								<tr>
									<td align="center" style="word-break:break-all;padding:10px;padding-top:0px;color:#BEBEBE;"><b>�⺻��ǰ</b></td>
								</tr>
								</table>
								</td>
<?
			for($ttt=1; $ttt<count($arrpackage_list_exp); $ttt++) {
				if(strlen($arrpackage_proinfo[productcode][$arrpackage_list_exp[$ttt]])>0) {
?>
								<?=($ttt%$packagecoll==0?"</tr><tr>":"")?>
								<td width="<?=ceil(100/$packagecoll)?>%" valign="top" align="center" style="padding:5px;">
								<table border="0" cellpadding="0" cellspacing="0" width="90">
								<tr>
									<td valign="top">
									<table border="0" cellpadding="0" cellspacing="0" id="P<?=$arrpackage_proinfo[productcode][$arrpackage_list_exp[$ttt]]?>" onmouseover="quickfun_show(this,'P<?=$arrpackage_proinfo[productcode][$arrpackage_list_exp[$ttt]]?>','')" onmouseout="quickfun_show(this,'P<?=$arrpackage_proinfo[productcode][$arrpackage_list_exp[$ttt]]?>','none')">
									<tr>
										<td align="center" valign=middle style="border:1px #EAEAEA solid;padding:10px;" bgcolor="#EDEDED"><A HREF="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$arrpackage_proinfo[productcode][$arrpackage_list_exp[$ttt]]?>" onmouseover="window.status='��ǰ����ȸ';return true;" onmouseout="window.status='';return true;">

<?
					if (strlen($arrpackage_proinfo[tinyimage][$arrpackage_list_exp[$ttt]])>0 && file_exists($Dir.DataDir."shopimages/product/".$arrpackage_proinfo[tinyimage][$arrpackage_list_exp[$ttt]])==true) {
						echo "<img src=\"".$Dir.DataDir."shopimages/product/".urlencode($arrpackage_proinfo[tinyimage][$arrpackage_list_exp[$ttt]])."\" border=\"0\" ";
						$width = getimagesize($Dir.DataDir."shopimages/product/".$arrpackage_proinfo[tinyimage][$arrpackage_list_exp[$ttt]]);
						if($width[0]>$width[1]) echo "width=\"70\"> ";
						else echo "height=\"70\">";
					} else {
						echo "<img src=\"".$Dir."images/no_img.gif\" width=\"70\" border=\"0\" align=\"center\">";
					}
?></A></td>
									</tr>
									<tr>
										<td height="3" style="position:relative;"><?=($_data->ETCTYPE["QUICKTOOLS"]!="Y"?"<script>quickfun_write('".$Dir."','P','".$arrpackage_proinfo[productcode][$arrpackage_list_exp[$ttt]]."','".($arrpackage_proinfo[quantity][$arrpackage_list_exp[$ttt]]=="0"?"":"1")."')</script>":"")?></td></tr>
									</tr>
									<tr>
										<td align="center" style="word-break:break-all;padding:10px;padding-top:0px;"><A HREF="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$arrpackage_proinfo[productcode][$arrpackage_list_exp[$ttt]]?>" onmouseover="window.status='��ǰ����ȸ';return true;" onmouseout="window.status='';return true;"><FONT class="prname"><?=viewproductname($arrpackage_proinfo[productname][$arrpackage_list_exp[$ttt]],$arrpackage_proinfo[etctype][$arrpackage_list_exp[$ttt]],"")?></FONT></A></td>
									</tr>
									</table>
									</td>
								</tr>
								</table>
								</td>
<?
				}
			}

			if($ttt<$packagecoll) {
				$empty_count = $packagecoll-$ttt;
				for($ttt=0; $ttt<$empty_count; $ttt++) {
?>
								<td width="<?=ceil(100/$packagecoll)?>%"></td>
<?
				}
			}
?>
							</tr>
							</table>
							</td>
						</tr>
						</table>
						</td>
					</tr>

<?
		}
?>
					</table>
					</td>
				</tr>
				</table>
				</td>
				<td background="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/skin_tag_t04.gif"></td>
			</tr>
			<tr>
				<td><IMG SRC="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/skin_tag_t07.gif" border="0"></td>
				<td width="100%" background="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/skin_tag_t06.gif"></td>
				<td><IMG SRC="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/skin_tag_t05.gif" border="0"></td>
			</tr>
			</table>
			</td>
		</tr>
		<!-- ��Ű�� ��ǰ ��� �� //-->
<?
	} //��Ű�� ��ǰ ��� ��
?>
<?
	if($_pdata->assembleuse=="Y" && count($_adata)>0) {
?>
		<tr>
			<td height="20"></td>
		</tr>
		<tr>
			<td>
			<table cellpadding="0" cellspacing="0" width="100%" height="100">
			<tr>
				<td><IMG SRC="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/skin_tag_t01.gif" border="0"></td>
				<td width="100%" background="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/skin_tag_t02.gif"></td>
				<td><IMG SRC="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/skin_tag_t03.gif" border="0"></td>
			</tr>
			<tr>
				<td height="100%" background="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/skin_tag_t08.gif"></td>
				<td width="100%" bgcolor="#F8F8F8" valign="top">
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td>
<?
		$assemble_type_exp = explode("",$_adata->assemble_type);
		$assemble_title_exp = explode("",$_adata->assemble_title);
		$assemble_pridx_exp = explode("",$_adata->assemble_pridx);
		$assemble_list_exp = explode("",$_adata->assemble_list);

		if(count($assemble_type_exp)>0) {
?>
					<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<input type=hidden name=assemble_type value="<?=implode("|",$assemble_type_exp)?>">
					<input type=hidden name=assemble_list value="">
					<input type=hidden name=assembleuse value="Y">
					<col width="60"></col>
					<col width=""></col>
<?
			for($j=1; $j<count($assemble_type_exp); $j++) {
				$assemble_list_pexp = explode(",",$assemble_list_exp[$j]);

?>
					<tr>
						<td valign="bottom" style="padding:5px;"><?
					if(strlen($assemble_pridx_exp[$j])>0 && (strlen($_acdata[$assemble_pridx_exp[$j]]->quantity)==0 || $_acdata[$assemble_pridx_exp[$j]]->quantity>=$miniq)) {
						if(strlen($_acdata[$assemble_pridx_exp[$j]]->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$_acdata[$assemble_pridx_exp[$j]]->tinyimage)) {
							echo "<a href=\"javascript:assemble_proinfo('".$j."');\"><img src=\"".$Dir.DataDir."shopimages/product/".$_acdata[$assemble_pridx_exp[$j]]->tinyimage."\" border=\"0\" id=\"acimage".$j."\" width=\"50\" height=\"40\"></a>";
						} else {
							echo "<a href=\"javascript:assemble_proinfo('".$j."');\"><img src=\"".$Dir."images/acimage.gif\" border=\"0\" id=\"acimage".$j."\" width=\"50\" height=\"40\"></a>";
						}
						$assemble_state = "M";
					} else {
						echo "<a href=\"javascript:assemble_proinfo('".$j."');\"><img src=\"".$Dir."images/acimage.gif\" border=\"0\" id=\"acimage".$j."\" width=\"50\" height=\"40\"></a>";
						$assemble_state = "A";
					}
						?></td>
						<td valign="bottom" style="padding:5px;">
						<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td colspan="2"><span style="font-size:12px;"><b><?=$assemble_title_exp[$j]?></b></font></td>
						</tr>
						<tr>
							<td width="100%"><select name="acassembleselect[]" id="acassemble<?=$j?>" onchange="setAssenbleChange(this,'<?=$j?>');" onclick="setCurrentSelect(this.selectedIndex);" style="font-size:12px;letter-spacing:-0.5pt;width:100%;">
							<option value=""><?=($assemble_type_exp[$j]=="Y"?"&nbsp;&nbsp;&nbsp;��������������������������������&nbsp;[�ʼ��׸�] ������ �ּ���&nbsp;����������������������������������&nbsp;&nbsp;":"&nbsp;&nbsp;&nbsp;��������������������������������������&nbsp;������ �ּ���&nbsp;&nbsp;�������������������������������������� ")?></option>
<?
					for($k=1; $k<count($assemble_list_pexp); $k++) {
						if(strlen($_acdata[$assemble_list_pexp[$k]]->pridx)>0 && (strlen($_acdata[$assemble_list_pexp[$k]]->quantity)==0 || $_acdata[$assemble_list_pexp[$k]]->quantity>0)) {
							if($_acdata[$assemble_list_pexp[$k]]->pridx==$_acdata[$assemble_pridx_exp[$j]]->pridx) {
								echo "<option value=\"".$_acdata[$assemble_list_pexp[$k]]->productcode."|".$_acdata[$assemble_list_pexp[$k]]->quantity."|".$_acdata[$assemble_list_pexp[$k]]->sellprice."|G|".htmlspecialchars($_acdata[$assemble_list_pexp[$k]]->tinyimage)."\" selected style=\"color:#FF00FF;\">".$_acdata[$assemble_list_pexp[$k]]->productname." / �⺻����</option>\n";
							} else {
								$minus_price = 0;
								$minus_price = $_acdata[$assemble_list_pexp[$k]]->sellprice - $_acdata[$assemble_pridx_exp[$j]]->sellprice;
								if($minus_price>0) {
									echo "<option value=\"".$_acdata[$assemble_list_pexp[$k]]->productcode."|".$_acdata[$assemble_list_pexp[$k]]->quantity."|".$_acdata[$assemble_list_pexp[$k]]->sellprice."|".$assemble_state."|".htmlspecialchars($_acdata[$assemble_list_pexp[$k]]->tinyimage)."\" style=\"color:#FF4C00;\">".$_acdata[$assemble_list_pexp[$k]]->productname.($minus_price>0?" / +".number_format($minus_price):" / ".number_format($minus_price))."</option>\n";
								} else if($minus_price>0) {
									echo "<option value=\"".$_acdata[$assemble_list_pexp[$k]]->productcode."|".$_acdata[$assemble_list_pexp[$k]]->quantity."|".$_acdata[$assemble_list_pexp[$k]]->sellprice."|".$assemble_state."|".htmlspecialchars($_acdata[$assemble_list_pexp[$k]]->tinyimage)."\" style=\"color:#FF00FF;\">".$_acdata[$assemble_list_pexp[$k]]->productname.($minus_price>0?" / +".number_format($minus_price):" / ".number_format($minus_price))."</option>\n";
								} else {
									echo "<option value=\"".$_acdata[$assemble_list_pexp[$k]]->productcode."|".$_acdata[$assemble_list_pexp[$k]]->quantity."|".$_acdata[$assemble_list_pexp[$k]]->sellprice."|".$assemble_state."|".htmlspecialchars($_acdata[$assemble_list_pexp[$k]]->tinyimage)."\" style=\"color:#003399;\">".$_acdata[$assemble_list_pexp[$k]]->productname.($minus_price>0?" / +".number_format($minus_price):" / ".number_format($minus_price))."</option>\n";
								}
							}
						}
					}
?>
							</select></td>
						</tr>
						</table>
						</td>
					</tr>
<?
			}
?>
					</table>
					</td>
				</tr>
				<tr>
					<td style="padding-top:20px;padding-left:5px;padding-right:5px;padding-bottom:10px;"><TABLE cellSpacing=0 cellPadding=0 width="100%" border=0><tr><td height="1" bgcolor="#DADADA"></td></tr></table></td>
				</tr>
				<tr>
					<td style="padding:5px;">
					<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td align="center" bgcolor="#FFFFFF" style="padding:10px;border:1px #DADADA solid;">
						<table border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td>
							<table border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td><span style="font-size:16px;color:#000000;line-height:18px;"><b>���ż���&nbsp;:&nbsp;</b></span></td>
								<td>
								<table cellpadding="0" cellspacing="0">
								<tr>
									<td><input type=text name="assemblequantity" value="<?=($miniq>1?$miniq:"1")?>" size="4" style="height:24px;text-align:center;font-weight:bold;font-size:14px;BORDER:#DFDFDF 1px solid;BACKGROUND-COLOR:#FFFFFF;padding-top:4pt;padding-bottom:1pt;" readonly></td>
									<td style="padding-left:4px;padding-right:4px;">
									<table cellpadding="0" cellspacing="0">
									<tr>
										<td valign="top" style="padding-bottom:1px;"><a href="javascript:change_quantity('up')"><img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_neroup2.gif" border="0"></a></td>
									</tr>
									<tr>
										<td valign="bottom" style="padding-top:1px;"><a href="javascript:change_quantity('dn')"><img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_nerodown2.gif" border="0"></a></td>
									</tr>
									</table>
									</td>
								</tr>
								</table>
								</td>
							</tr>
							</table>
							</td>
							<?if(strlen($dicker)==0) { ?>
							<td style="padding-left:20px;">
							<table border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td><span style="font-size:16px;color:#000000;line-height:18px;"><b>�հ�ݾ�&nbsp;:&nbsp;</b></span></td>
								<td>
								<table cellpadding="0" cellspacing="0">
								<tr>
									<td><input type=text name="assembleprice" id="idx_assembleprice" value="<?=number_format($miniq>1?$miniq*$_pdata->sellprice:$_pdata->sellprice)?>" size="12" style="height:24px;text-align:right;font-weight:bold;font-size:14px;BORDER:#DFDFDF 1px solid;BACKGROUND-COLOR:#FFFFFF;padding-top:4pt;padding-bottom:1pt;padding-right:2pt;" readonly></td>
									<td style="padding-left:20px;"><a href="javascript:CheckForm('','')" onMouseOver="window.status='��ٱ��ϴ��';return true;"><IMG SRC="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_btn02.gif" hspace="3" border="0" align=middle></a></td>
								</tr>
								</table>
								</td>
							</tr>
							</table>
							</td>
							<? } ?>
						</tr>
						</table>
						</td>
					</tr>
					</table>
					</td>
				</tr>
				</table>
<?
		}
?>
				</td>
				<td background="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/skin_tag_t04.gif"></td>
			</tr>
			<tr>
				<td><IMG SRC="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/skin_tag_t07.gif" border="0"></td>
				<td width="100%" background="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/skin_tag_t06.gif"></td>
				<td><IMG SRC="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/skin_tag_t05.gif" border="0"></td>
			</tr>
			</table>
			</td>
		</tr>
<?
	}
?>
		</form>
		<?if($detailimg_eventloc=="2"){?>
		<tr>
			<td height="20"></td>
		</tr>
		<tr>
			<td><?=$detailimg_body?></td>
		</tr>
		<?}?>

		<!--
		<?//if($_data->ETCTYPE["TAGTYPE"]!="N") {?>
		<tr>
			<td height="20"></td>
		</tr>
		<tr>
			<td>
			<table cellpadding="0" cellspacing="0" width="100%" height="100">
			<tr>
				<td><IMG SRC="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/skin_tag_t01.gif" border="0"></td>
				<td width="100%" background="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/skin_tag_t02.gif"></td>
				<td><IMG SRC="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/skin_tag_t03.gif" border="0"></td>
			</tr>
			<tr>
				<td height="100%" background="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/skin_tag_t08.gif"></td>
				<td width="100%" bgcolor="#F8F8F8" valign="top">
				<!-- �±װ��� --//>

				<style type="text/css">
				<!--
				.tagtitle	{position:relative; width:100%; margin:2px 0 7px; height:15px;}
				.tagtitle li {padding:0px;}
				.taglist	{
					position:absolute; left:30px; width:90%; height:50px; overflow:hidden; line-height:20px; background:#ffffff;
					border:1px solid #E8E8E8;padding:0px 0px 0px 0px;
				}
				.taglist_on	{
					position:absolute; left:30px; width:90%; height:100px; overflow:auto; overflow-x:hidden; line-height:20px; background:#ffffff;
					border:1px solid #E8E8E8;padding:0px 0px 0px 0px;
				}
				.tag_more	{position:absolute; right:10px; top:0;}
				.tag_more	img	{margin:3px 0}
				.taginput	{background:#FAFAFA; padding:2px 0 2px 30px; }

				.prtaglistclass	{padding:5px 0px 0px 10px; }
				--//>
				</style>

				<SCRIPT LANGUAGE="JavaScript">
				<!--
				function tagView()	{
					obj_T = document.getElementById('tag');
					obj_B = document.getElementById('tag_btn');

					if (obj_T.className == "taglist")	{
						obj_T.className = "taglist_on";
						obj_B.src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/btn_tagmoreclose.gif";
						obj_B.alt="�ݱ�";
					} else	{
						obj_T.className = "taglist";
						obj_B.src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/btn_tagmore.gif";
						obj_B.alt="������";
					}
				}
				//--//>
				</SCRIPT>
				<div class="taginput">
					<img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/shoppingtag_text.gif" alt="����ǰ�� �±׸� �־��ּ���" align=absmiddle>
					<input type="text" name="searchtagname" maxlength="50" style="background-color:white; border:#D5D5D5 1px solid; width:190px; height:18px;" autocomplete="off" onkeyup="check_tagvalidate(event, this);">
					<a href="javascript:void(0)" onclick="tagCheck('<?=$productcode?>')" onmouseover="window.status='�±״ޱ�';return true;" onmouseout="window.status='';return true;"><img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/btn_tagreg.gif" border=0 align=absmiddle alt="�±׳ֱ�"></a>
					<span style="font-size:8pt;">* �ѹ��� �ϳ��� �±׸� �־��ּ��� </span>
				</div>
				<ul class="tagtitle">
					<li>
					<div class="taglist" id="tag">
						<div id="tagtitlediv">
						<img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/title_shoppingtag.gif" align=absmiddle alt="�����±�">
						</div>
						<div id="prtaglist" class="prtaglistclass">
<?
								$arrtaglist=explode(",",$_pdata->tag);
								$jj=0;
								for($i=0;$i<count($arrtaglist);$i++) {
									$arrtaglist[$i]=ereg_replace("(<|>)","",$arrtaglist[$i]);
									if(strlen($arrtaglist[$i])>0) {
										if($jj>0) echo ",&nbsp;&nbsp;";
										echo "<a href=\"".$Dir.FrontDir."tag.php?tagname=".urlencode($arrtaglist[$i])."\" onmouseover=\"window.status='".$arrtaglist[$i]."';return true;\" onmouseout=\"window.status='';return true;\">".$arrtaglist[$i]."</a>";
										$jj++;
									}
								}
?>
						</div>
						<div class="tag_more"><a href="javascript:tagView()" onmouseover="window.status='�±״�����';return true;" onmouseout="window.status='';return true;"><img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/btn_tagmore.gif" border=0 alt="������" id="tag_btn"></a></div>
					</div>
					</li>
				</ul>
				</td>
				<!-- �±װ��� --//>
				<td background="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/skin_tag_t04.gif"></td>
			</tr>
			<tr>
				<td><IMG SRC="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/skin_tag_t07.gif" border="0"></td>
				<td width="100%" background="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/skin_tag_t06.gif"></td>
				<td><IMG SRC="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/skin_tag_t05.gif" border="0"></td>
			</tr>
			</table>
			</td>
		</tr>
		<?//}?>
		-->

		<?if($_data->coupon_ok=="Y" && strlen($coupon_body)>0) {?>
		<tr>
			<td height="40"></td>
		</tr>
		<tr>
			<td>
				<h4 style="padding-bottom:10px; color:#444444; letter-spacing:-1px;">���� �ٿ�ε�</h4>
				<?=$coupon_body?>
			</td>
		</tr>
		<?}?>

		<tr>
			<td>
			<a name="1"></a>
			<table border="0" cellpadding="0" cellspacing="0" width="100%" class="detailTabLink">
				<tr>
					<td class="prdetailTabOn"><span><a href="#1">��ǰ������</a></span></td>
					<td class="prdetailTabOff3"><a href="#2">���û�ǰ</a></td>
					<td class="prdetailTabOff" ><a href="#3">����ǰ��<!-- ([REVIEW_TOTAL])--></a></td>
					<td class="prdetailTabOff"><a href="#4">��ǰQ&A<!-- ([QNA_COUNT])--></a></td>
					<td class="prdetailTabOff"><a href="#6">SNS�� �ҹ�����<!--([PROD_SNS_CNT])--></a></td>
					<td class="prdetailTabOff2"><a href="#5">���/��ȯ/ȯ��</a></td>
				</tr>
			</table>
			<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
			<tr>
				<td valign="top">
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td style="padding:30px 0px;">
<?
					if(strlen($detail_filter)>0) {
						$_pdata->content = preg_replace($filterpattern,$filterreplace,$_pdata->content);
					}

					if (strpos($_pdata->content,"table>")!=false || strpos($_pdata->content,"TABLE>")!=false)
						echo "<pre>".$_pdata->content."</pre>";
					else if(strpos($_pdata->content,"</")!=false)
						echo ereg_replace("\n","<br>",$_pdata->content);
					else if(strpos($_pdata->content,"img")!=false || strpos($_pdata->content,"IMG")!=false)
						echo ereg_replace("\n","<br>",$_pdata->content);
					else
						echo ereg_replace(" ","&nbsp;",ereg_replace("\n","<br>",$_pdata->content));
?>
					</td>
				</tr>


<?
	//���û�ǰ (������ ���)
	if($_data->coll_loc=="1") {
		echo "<tr>\n";
		echo "	<td height=\"20\"></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "	<td>\n";
		echo "
						<a name=\"2\"></a>
						<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" class=\"detailTabLink\">
							<tr>
								<td class=\"prdetailTabOff\"><a href=\"#1\">��ǰ������</a></td>
								<td class=\"prdetailTabOn\"><a href=\"#2\">���û�ǰ</a></td>
								<td class=\"prdetailTabOff3\"><a href=\"#3\">����ǰ��</a></td>
								<td class=\"prdetailTabOff\"><a href=\"#4\">��ǰQ&A</a></td>
								<td class=\"prdetailTabOff\"><a href=\"#6\">SNS�� �ҹ�����</a></td>
								<td class=\"prdetailTabOff2\"><a href=\"#5\">���/��ȯ/ȯ��</a></td>
							</tr>
						</table>
		";
		echo "	</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "	<td>".$collection_body."</td>\n";
		echo "</tr>\n";
	}
?>

				<tr>
					<td height="20"></td>
				</tr>
				<tr>
					<td>
						<?
							// ��ǰ�������
							$ditems = _getProductDetails($_pdata->pridx);
							if(_array($ditems) && count($ditems) > 0){
						?>
						<table border="0" cellpadding="0" cellspacing="0" class="productInfoGosi">
							<caption>���ڻ�ŷ��Һ��ں�ȣ�� �����Ģ�� ���� ��ǰ�������� ���</caption>
							<?
								foreach($ditems as $ditem){
							?>
							<tr>
								<th><?=$ditem['dtitle']?></th>
								<td><?=nl2br($ditem['dcontent'])?></td>
							</tr>
							<?
								}// end foreach
							?>
						</table>
						<?
							} // end if
						?>
					</td>
				</tr>
				</table>
				</td>
<?
//���û�ǰ (������ ����)
if($_data->coll_loc=="3") {
	echo "	<td width=\"3\" nowrap></td>\n";
	echo "	<td width=\"165\" valign=\"top\">\n";
	echo "	<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
	echo "	<tr>\n";
	echo "		<td height=\"5\"></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td>".$collection_body."</td>\n";
	echo "	</tr>\n";
	echo "	</table>\n";
	echo "	</td>\n";
}
?>
			</tr>
			</table>
			</td>
		</tr>
		<?if($detailimg_eventloc=="3"){?>
		<tr>
			<td height="20"></td>
		</tr>
		<tr>
			<td><?=$detailimg_body?></td>
		</tr>
		<?}?>

		<?
			//���û�ǰ (������ �ϴ�)
			if($_data->coll_loc=="2") {
				echo "<tr>\n";
				echo "	<td height=\"20\"></td>\n";
				echo "</tr>\n";
				echo "<tr>\n";
				echo "	<td><a name=\"2\"></a>\n";
				echo "	<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
				echo "	<tr>\n";
				echo "		<td><a href=\"#1\"><img src=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_detailtitle1r.gif\" border=\"0\"></td>\n";
				echo "		<td><a href=\"#2\"><img src=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_detailtitle2.gif\" border=\"0\"></a></td>\n";
				echo "		<td><a href=\"#3\"><img src=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_detailtitle3r.gif\" border=\"0\"></a></td>\n";
				echo "		<td><a href=\"#4\"><img src=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_detailtitle4r.gif\" border=\"0\"></a></td>\n";
				echo "		<td><a href=\"#5\"><img src=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_detailtitle5r.gif\" border=\"0\"></a></td>\n";
				if($_data->sns_ok == "Y" && $_pdata->sns_state == "Y"){
					echo "		<td><a href=\"#6\"><img src=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_detailtitle6r.gif\" border=\"0\"></a></td>\n";
				}
				if($_data->sns_ok == "Y" && $_pdata->gonggu_product == "Y"){
					echo "		<td><a href=\"#7\"><img src=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_detailtitle7r.gif\" border=\"0\"></a></td>\n";
				}
				echo "		<td width=\"100%\" background=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_detailtitlebg.gif\"></td>\n";
				echo "	</tr>\n";
				echo "	</table>\n";
				echo "	</td>\n";
				echo "</tr>\n";
				echo "<tr>\n";
				echo "	<td>".$collection_body."</td>\n";
				echo "</tr>\n";
			}
		?>

		<?if($_data->review_type!="N") {?>
		<tr>
			<td height="20"></td>
		</tr>
		<tr>
			<td valign="top">
			<a name="review"></a>
			<table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
			<tr>
				<td width="100%">
					<a name="3"></a>
					<table border="0" cellpadding="0" cellspacing="0" width="100%" class="detailTabLink">
						<tr>
							<td class="prdetailTabOff"><a href="#1">��ǰ������</a></td>
							<td class="prdetailTabOff"><a href="#2">���û�ǰ</a></td>
							<td class="prdetailTabOn" ><a href="#3">����ǰ��<!-- ([REVIEW_TOTAL])--></a></td>
							<td class="prdetailTabOff3"><a href="#4">��ǰQ&A<!--  ([QNA_COUNT])--></a></td>
							<td class="prdetailTabOff"><a href="#6">SNS�� �ҹ�����<!-- ([PROD_SNS_CNT])--></a></td>
							<td class="prdetailTabOff2"><a href="#5">���/��ȯ/ȯ��</a></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td height="15"></td>
			</tr>
			<tr>
				<td>
					<? INCLUDE ($Dir.FrontDir."prreview.php"); ?>
				</td>
			</tr>
			</table>
			</td>
		</tr>

		<?}?>

		<!-- ��ǰQ/A -->
		<?if(strlen($qnasetup->board)>0){?>
		<tr><td height="20"></td></tr>
		<tr>
			<td valign="top">
			<table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
			<tr>
				<td>
					<a name="4"></a>
					<table border="0" cellpadding="0" cellspacing="0" width="100%" class="detailTabLink">
						<tr>
							<td class="prdetailTabOff"><a href="#1">��ǰ������</a></td>
							<td class="prdetailTabOff"><a href="#2">���û�ǰ</a></td>
							<td class="prdetailTabOff"><a href="#3">����ǰ��<!-- ([REVIEW_TOTAL])--></a></td>
							<td class="prdetailTabOn"><a href="#4">��ǰQ&A<!--  ([QNA_COUNT])--></a></td>
							<td class="prdetailTabOff3"><a href="#6">SNS�� �ҹ�����<!--  ([PROD_SNS_CNT])--></a></td>
							<td class="prdetailTabOff2"><a href="#5">���/��ȯ/ȯ��</a></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td height="15"></td>
			</tr>
			<tr>
				<td>
					<? INCLUDE ($Dir.FrontDir."prqna.php"); ?>
				</td>
			</tr>
			</table>
			</td>
		</tr>
		<?}?>
<? if($_data->sns_ok == "Y" && $_pdata->sns_state == "Y"){?>
		<tr><td height="20"></td></tr>
		<tr>
			<td valign="top">
				<table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
					<tr>
						<td>
							<a name="6"></a>
							<table border="0" cellpadding="0" cellspacing="0" width="100%" class="detailTabLink">
								<tr>
									<td class="prdetailTabOff"><a href="#1">��ǰ������</a></td>
									<td class="prdetailTabOff"><a href="#2">���û�ǰ</a></td>
									<td class="prdetailTabOff" ><a href="#3">����ǰ��<!-- ([REVIEW_TOTAL])--></a></td>
									<td class="prdetailTabOff"><a href="#4">��ǰ Q&A<!--  ([QNA_COUNT])--></a></td>
									<td class="prdetailTabOn"><a href="#6">SNS�� �ҹ�����<!-- ([PROD_SNS_CNT])--></a></td>
									<td class="prdetailTabOff2"><a href="#5">���/��ȯ/ȯ��</a></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td style="padding:5,5,0,5">
						<?INCLUDE ($Dir.TempletDir."product/sns_product_cmt.php"); echo $sProductCmt;?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
<?
	}
	//if($_data->sns_ok == "Y" && $_pdata->gonggu_product == "Y"){
?>
	<!--
		<tr><td height="20"></td></tr>
		<tr>
			<td valign="top"><a name="7"></a>
			<table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
			<tr>
				<td>
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td><a href="#1"><img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_detailtitle1ra.gif" border="0"></td>
					<td><a href="#2"><img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_detailtitle2r.gif" border="0"></a></td>
					<td><a href="#3"><img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_detailtitle3r.gif" border="0"></a></td>
					<td><a href="#4"><img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_detailtitle4r.gif" border="0"></a></td>
					<td><a href="#5"><img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_detailtitle5r.gif" border="0"></a></td>
					<? if($_data->sns_ok == "Y" && $_pdata->sns_state == "Y"){?><td><a href="#6"><img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_detailtitle6r.gif" border="0"></a></td><?}?>
					<td><a href="#7"><img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_detailtitle7.gif" border="0"></a></td>
					<td width="100%" background="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_detailtitlebg.gif"></td>
					<td width="100%" background="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_detailtitlebg.gif"></td>
				</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td style="padding:5,5,0,5">
				<?INCLUDE ($Dir.TempletDir."product/sns_gonggu_cmt.php"); echo $sGongguCmt;?>
				</td>
			</tr>
			</table>
			</td>
		</tr>
	-->
<?//}

	//���/��ȯ/ȯ������
	if(strlen($deli_info)>0) {
		echo "<tr>\n";
		echo "	<td valign=\"top\">";
		echo "
						<a name=\"5\"></a>
						<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" class=\"detailTabLink\">
							<tr>
								<td class=\"prdetailTabOff\"><a href=\"#1\">��ǰ������</a></td>
								<td class=\"prdetailTabOff\"><a href=\"#2\">���û�ǰ</a></td>
								<td class=\"prdetailTabOff\"><a href=\"#3\">����ǰ��</a></td>
								<td class=\"prdetailTabOff\"><a href=\"#4\">��ǰ Q&A</a></td>
								<td class=\"prdetailTabOff\"><a href=\"#6\">SNS�� �ҹ�����</a></td>
								<td class=\"prdetailTabOn\"><a href=\"#5\">���/��ȯ/ȯ��</a></td>
							</tr>
						</table>
		";
		echo "	</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td>".$deli_info."</td>\n";
		echo "	</tr>\n";
		echo "	</table>\n";
		echo "	</td>\n";
		echo "</tr>\n";
	}
?>
		<tr><td height="20"></td></tr>
		</table>
		</td>
	</tr>
	</table>
	</td>
</tr>
</table>