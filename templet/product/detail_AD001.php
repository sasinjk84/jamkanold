<? 
/*
$pinfo = rentProduct::read($_pdata->pridx); 
_pr($pinfo);*/

if($_pdata->vender > 0){
	$sql ="SELECT v.*,s.brand_name,g.vgname,g.vgicon FROM tblvenderinfo v left join tblvenderstore s on s.vender = v.vender left join vender_group_link vg on vg.vender=v.vender left join vender_group g on g.vgidx=vg.vgidx WHERE v.vender=".$_pdata->vender." LIMIT 1";
	$result=mysql_query($sql,get_db_conn());
	$venderinfo = mysql_fetch_assoc($result);
}
?>

<div style="overflow:hidden;">
	<div style="float:left;margin: 15px 0px 0px;overflow: hidden;border-bottom: 0px solid #eceff1;">
		<table border="0" cellpadding="0" cellspacing="0" style="float:left">
			<tr>
				<td style="padding-right:5px;"><?=$codenavi?></td>
				<td align="right" style="padding-right:3px; background-repeat:no-repeat; background-position:right;"><A HREF="javascript:ClipCopy('http://<?=$_ShopInfo->getShopurl()?>?<?=getenv("QUERY_STRING")?>')"><img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/btn_addr_copy.gif" border="0" /></A></td>
			</tr>
		</table>
		<form name="subtopSearch" action="/front/productsearch.php" method="get">
		<?	for($k=0;$k<4;$k++){ 			?>
		<input type="hidden" name="code<?=chr(65+$k)?>" value="<?=${'code'.chr(65+$k)}?>"  style=""/>
		<? 	} ?>
		<table border="0" cellpadding="0" cellspacing="0" style="float:right">
			<tr>
				<!-- <td><input type="text" name="bookingStartDate" class="datePickInput" value="<?=date("Ymd")?>" style="width:70px; height:24px;" readonly> ~ </td>
				<td><input type="text" name="bookingEndDate"  value="<?=date("Ymd")?>" style="width:70px; height:24px; border:1px solid #cfcff" class="datePickInput" readonly></td>									
				<td style="padding-left:10px;">
					<input type="text" name="search" style="height:24px; width:200px;" />
				</td>
				<td><img src="/data/design/img/top/search_bt2.gif" style="margin-left:3px; cursor:pointer" onclick="javascript:_Search(document.subtopSearch);" /></td>	 -->				
			</tr>
		</table>
		</form>
	</div>
	<p style="float:right;margin:15px 0px;"><?=(!_empty($_pdata->productnumber))? "��ǰ��ȣ : ".$_pdata->productnumber: ""; ?></p>
</div>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td>

			<div class="prdetailnameWrap">
				<span class="prdetailname"><?=$rentalIcon?><?=viewproductname($_pdata->productname,$_pdata->etctype,"")?></span>
				
				<? if(strlen($_pdata->prmsg)>0){ ?>
					<p><?=$_pdata->prmsg?></p>
				<? } ?>
			</div>
	
		</td>
	</tr>
	<tr>
		<td>

			<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed;">
				<form name="form1" method="post" action="<?=$Dir.FrontDir?>basket.php">
				<input type="hidden" name="vender" id="vender" value="<?=$_pdata->vender?>">
				<input type="hidden" name="selFolder" id="selFolder">
				<input type="hidden" name="pre_bookingStartDate" id="pre_bookingStartDate">
				<input type="hidden" name="pre_bookingEndDate" id="pre_bookingEndDate">
				<input type="hidden" name="pre_startTime" id="pre_startTime">
				<input type="hidden" name="pre_endTime" id="pre_endTime">
				<tr>
					<td>
						<div class="basketContents">
							<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
								<colgroup>
									<col>
									<col width="0">
									<col width="596">
								</colgroup>
								<tr>
									<td valign="top" height="100%">
										<table cellpadding="0" cellspacing="0" width="100%" height="100%" border="0">
											<?
												$sql = "select * from product_multicontents where pridx='".$_pdata->pridx."'";
												$multiconts = array();
												if(false !== $cres = mysql_query($sql,get_db_conn())){
					//								if(mysql_result($cres,0,0) >0) $multi_img = 'Y';
													$imagepath=$Dir.DataDir."shopimages/multi/";
													if(mysql_num_rows($cres)){
														while($row = mysql_fetch_assoc($cres)){
															if($row['type'] == 'img'){
																if(file_exists($imagepath.'/'.$row['cont'])) $row['content'] = '<img src="'.$imagepath.'/thumb_'.$row['cont'].'" >';
																else continue;
															}else{
																$row['content'] = '������';
															}
															array_push($multiconts,$row);
														}
													}
												}
												if(_array($multiconts)) {
													//echo "<tr><td width=\"100%\" height=\"100%\" align=\"center\"><iframe src=\"".$Dir.FrontDir."primage_multiframe.php?productcode=".$productcode."&thumbtype=".$thumbtype."\" frameborder=0 width=100% height=".$multi_height."></iframe></td></tr>\n"; ?>
												<tr>
													<td align="center" style="padding:10px;border-right:1px solid #eceff1; width:600px;height:600px; overflow:hidden" id="multicontdisparea">
													</td>
												</tr>
												<tr>
													<td align="center" style="border-right:1px solid #eceff1;"> 
													<?
													foreach($multiconts as $mulitem){ ?>
													<div class="multiThumb" midx="<?=$mulitem['midx']?>">
													<?=$mulitem['content']?>
													</div>
													<? } ?>
													<script language="javascript" type="text/javascript">
													var selmel = null;
													function getMultiCont(el){
														$j('#multicontdisparea>div').css('display','none');				
														if(selmel){
															if(selmel == el) return;
															$j(selmel).removeClass('active');
														}
														$j(el).addClass('active');
														selmel = el;	
														
														midx = $j(el).attr('midx');
														
														if($j('#mcontDiv_'+midx).length>0){
															//$j('#mcontDiv_'+midx).css('display','block');
															$j('#mcontDiv_'+midx).fadeIn(200);
														}else{
															$j('#multicontdisparea').append('<div id="mcontDiv_'+midx+'" style="text-align:center;">�ε�</div>');
															$j.get('/ajaxback/multicontent.php',{'act':'get','pridx':'<?=$_pdata->pridx?>','midx':midx},
																function(data){
																	if(data.err == 'ok'){
																		$j('#mcontDiv_'+midx).html('');
																		$j('#multicontdisparea').find('#mcontDiv_'+midx).html(data.content);
																		$j('#mcontDiv_'+midx).fadeOut(0);
																		$j('#mcontDiv_'+midx).fadeIn(200);
																	}
																}
															,'json');
														}
													}
													$j(function(){
														/*
														$j('.multiThumb').hover(function() {
															getMultiCont(this);
														}, function() {
															getMultiCont($j('.multiThumb:eq(0)'));
														});
														*/
														$j('.multiThumb').on('click',function(){
															getMultiCont(this);
														});

														getMultiCont($j('.multiThumb:eq(0)'));

													});

													</script>
													</td>
												</tr>
											<?	} else {
													echo "<tr><td align=\"center\" style=\"padding:10px;border-right:1px solid #eceff1;\" class=detailImg>";
													if(strlen($_pdata->maximage)>0 && file_exists($Dir.DataDir."shopimages/product/".$_pdata->maximage)) {
														$imgsize=GetImageSize($Dir.DataDir."shopimages/product/".$_pdata->maximage);
														if(($imgsize[1]>470 || $imgsize[0]>920) && $multi_img!="I") $imagetype=1;
														else $imagetype=0;
													}
													
													if($_pdata->img_type==1) {														
														if(strlen($_pdata->minimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$_pdata->minimage)) {
															$width=GetImageSize($Dir.DataDir."shopimages/product/".$_pdata->minimage);
															if($width[0]>=550) $width[0]=550;
															else if (strlen($width[0])==0) $width[0]=550;
															echo "<div style=\"width:{$width[0]}px; height:{$width[1]}px;background:url(".$Dir.DataDir."shopimages/product/".urlencode($_pdata->minimage).");text-align:center;padding-top:110px;\" ><b><font style=\"font-family:'verdana','����';color:#361468;font-size:30px;letter-spacing:-1px;\">".number_format($_pdata->consumerprice)."��</b></div>";
														}
														else {
															echo "<img src=\"".$Dir."images/no_img.gif\" border=\"0\" align=\"center\" >";
														}
													} else {														
													//	if(strlen($_pdata->minimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$_pdata->minimage)) {
														if(strlen($_pdata->maximage)>0 && file_exists($Dir.DataDir."shopimages/product/".$_pdata->maximage)) {
															$width=GetImageSize($Dir.DataDir."shopimages/product/".$_pdata->maximage);
															
															$ratiow = ($width[0] > 920)?(real)(920 / $width[0]):1;
															$ratioh = ($width[1] > 470)?(real)(470 / $width[1]):1;
															$ratio = ($ratiow > $ratioh)?$ratioh:$ratiow;
															$width[0] = (int)($ratio*$width[0]);	
														
													//		if(substr($_pdata->productcode,0,3)!='999') { echo "<a href=\"javascript:primage_view('".$_pdata->maximage."','".$imagetype."')\">"; }
															if(substr($_pdata->productcode,0,3)!='999') { echo "<a href=\"javascript:quickZoom('".$_pdata->productcode."')\">"; }
															echo "<img src=\"".$Dir.DataDir."shopimages/product/".$_pdata->maximage."\" border=\"0\" width=\"".$width[0]."\"></a></td>\n";
														} else {
															echo "<img src=\"".$Dir."images/no_img.gif\" border=\"0\"></td>\n";
														}
													}

													echo "</tr>\n";
												}
											?>

										<!--<tr><td height="10"></td></tr>-->
										<!-- SNS ��ư ��� -->
										<?// INCLUDE ($Dir.TempletDir."product/sns_btn.php"); ?>
									</table>
									<!-- QR �ڵ� -->
									<!--<img src="http://<?//=$_ShopInfo->getShopurl()?>pqrcode.php?productcode=<?//=$productcode?>" />-->

								</td>
								<td></td>
								<td valign="top" style="position:relative;">












<script>
	$j(document).ready(function(){
		var jbOffset=$j('#detail_view_point').offset();
		$j(window).scroll(function(){
			if($j(document).scrollTop() > jbOffset.top){
			//if($j(document).scrollTop() > 1500){
				$j('.wrapDetailBox').addClass('fixed');
			}else{
				$j('.wrapDetailBox').removeClass('fixed');
			}
		});
	});

	function dispinfo(){
		if($j('#card_discount').css('display')=="none"){
			$j('#card_discount').show();
		}else{
			$j('#card_discount').hide();
		}
	}

	function deliChange(val){
		if(val=="�ù�"){
			$j('#deli_info1').show();
			$j('#deli_info2').hide();
			$j('#deli_info3').hide();
			$j('#deli_info4').hide();
			$j('#deli_info5').hide();
		}else if(val=="������"){
			$j('#deli_info1').hide();
			$j('#deli_info2').show();
			$j('#deli_info3').hide();
			$j('#deli_info4').hide();
			$j('#deli_info5').hide();
		}else if(val=="�湮����"){
			$j('#deli_info1').hide();
			$j('#deli_info2').hide();
			$j('#deli_info3').show();
			$j('#deli_info4').hide();
			$j('#deli_info5').hide();
		}else if(val=="���"){
			$j('#deli_info1').hide();
			$j('#deli_info2').hide();
			$j('#deli_info3').hide();
			$j('#deli_info4').show();
			$j('#deli_info5').hide();
		}else if(val=="��ҿ���"){
			$j('#deli_info1').hide();
			$j('#deli_info2').hide();
			$j('#deli_info3').hide();
			$j('#deli_info4').hide();
			$j('#deli_info5').show();
		}
	}
</script>

<style>
	.tdSize{float:left;width:49%;}
	.tdSize2{float:right;width:49%;}
	.prDetailTab, .prDetailContents{width:1060px;overflow:hidden;}

	/* �ϴܰ��� ����� �� ���� */
	.wrapDetailBox.fixed{position:fixed;top:0px;right:0%;width:380px;height:100%;border-left:1px solid #ddd;background:#fff;overflow:hidden;z-index:999;}
	.wrapDetailBox.fixed .hiddenPart{display:none;}
	.wrapDetailBox.fixed .tdSize, .wrapDetailBox.fixed .tdSize2{width:100%;}
	.wrapDetailBox.fixed .detailBtn a{display:inline-block;float:left;width:48%;margin-right:1%;margin-bottom:4px;box-sizing:border-box;letter-spacing:-1px;}
</style>

<style type="text/css">
	.reviewmeter{overflow:hidden;background-color:#f3f3f3;background:#f2f2f2;background:-webkit-linear-gradient(top,#eee,#f6f6f6);background:linear-gradient(to bottom,#eee,#f6f6f6);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#eeeeee', endColorstr='#f6f6f6', GradientType=0)}
	.reviewmeter .reviewbar{border-radius:1px;width:0;float:left;font-size:0;height:100%;background-color:#ffce00;background:#ffba00;background:-webkit-linear-gradient(top,#ffce00,#ffa700);background:linear-gradient(to bottom,#ffce00,#ffa700);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffce00', endColorstr='#ffa700', GradientType=0);-webkit-transition:width .5s ease;transition:width .5s ease}
</style>

<div class="wrapDetailBox">

									<div style="position:absolute;top:40px;right:40px;" class="hiddenPart">
									<!--��ǰ�� �� ����-->
									<!--
										<span class="prdetailname"><?=$rentalIcon?><?=viewproductname($_pdata->productname,$_pdata->etctype,"")?></span>

										<? if(strlen($_pdata->prmsg)>0){ ?>
											<p style="margin:10px 0px;color:#999999;font-size:11px"><?=$_pdata->prmsg?></p>
										<? } ?>
										-->
										<!--�̴ϼ� 2018.10.28
										<? if($_pdata->vender > 0){ ?>
										<div style="margin:30px 0px;">
											<div class="prdetailmsg" style="display:inline">by<a href="javascript:GoMinishop('../minishop.php?storeid=<?=$_vdata->id?>')" style="margin-left:5px;"><span style="color:#e21f36; font-size:20px; font-weight:bold;"><?=$_vdata->brand_name?></span> <a href="javascript:GoMinishop('../minishop.php?storeid=<?=$_vdata->id?>')" style="font-size:12px;padding:2px 10px;margin-left:5px;border:1px solid #e21f36;color:#e21f36;">�̴ϼ��ٷΰ��� ></a></div>
										</div>
										<? } ?>
										-->
										<div style="position:relative">
											<?
												// ���� ���� ( ���� ���� )
												$prAvg = productReviewAverage($productcode);
												$prAvgMark = "";
												for($i=1;$i<=5;$i++){
													$addclass = ($i <= $prAvg['average'])?'active':'';
													$prAvgMark .= '<div class="starmark '.$addclass.'">��</div>';
												}
												/*
												for( $i = 0 ; $i < $prAvg['average'] ; $i++ ) {
												$prAvgMark .= "<img src=\"/images/003/star_point1.gif\" alt=\"\" />";
												}
												for( $i = $prAvg['average']; $i < 5 ; $i++ ) {
												$prAvgMark .= "<img src=\"/images/003/star_point2.gif\" alt=\"\" />";
												}*/
												//$prAvgMark .= "(".$prAvg['count'].")";
												//echo $prAvgMark;
											?>

											<script language="javascript" type="text/javascript">
												function toggleReview(){
													/*
													if($j('#reviewInfo').css('display') == 'none'){
														$j('#reviewInfo').css('display','');
													}else{
														$j('#reviewInfo').css('display','none');
													}*/
													clearTimeout(rintval);
													if(overReview){
														$j('#reviewInfo').css('display','');
													}else{
														$j('#reviewInfo').css('display','none');
													}
												}
												var overReview = false;
												var rintval =null;
												$j(function(){
													$j('#reviewInfo').mouseenter(function(){ overReview = true; toggleReview();	});
													$j('#reviewInfo').mouseleave(function(){ overReview = false; toggleReview();	});
													$j('.toggleReviewArea').mouseover(function(){ overReview = true; toggleReview();	});
													$j('.toggleReviewArea').mouseout(function(){ overReview = false; rintval = setTimeout(toggleReview,500);	});
													
												});
											</script>
											
											<div style="float:right;width:110px; padding-top:10px;overflow:hidden;" >
												<? if($counttotal>0){?>
												<span style="float:right;font-size:12px;"><?=$startotalcount?>(<?=$counttotal?>)</span><span style="float:right;"><?=$prAvgMark?></span>
												<!-- ��� �������� �Ʒ� ȭ��ǥ:���콺������ ��ǰ�򳪿�<div style="width:15px; margin-right:5px; cursor:pointer; float:right"  class="toggleReviewArea"><img src="/upload/img/icon/downarrow.gif" style="margin-top:2px;" /></div>-->
												<? } ?>
											</div>

<!--
											<div><a href="#3" style="font-weight:600;">��ǰQ&A(<?=$qnacount?>)</a><span style="padding:10px;color:#bbbbbb;font-size:11px;">|</span><a href="#2" style="font-weight:600;">����ǰ��(<?=$prAvg['count']?>)</a> </div>
											-->
											<div id="reviewInfo" style="position:absolute; display:none; background:#fff; padding:10px 5px;width:586px; border:1px solid #DDD; right:0px;top:0px;;">
											<?
												$sql = "select marks,count(num) as cnt from tblproductreview where productcode='".$productcode."' group by marks";

												$avg = array();
												$totalreviews = 0;
												$hotreview = array();
												if(false !== $rres = mysql_query($sql,get_db_conn())){
													if(mysql_num_rows($rres)){
														while($tmp = mysql_fetch_assoc($rres)){
															$avg[$tmp['marks']] = $tmp['cnt'];
															$totalreviews +=$tmp['cnt'];
														}
													}
												}
												
												if($totalreviews < 1){
											?>
												<span style="text-align:center; display:block; width:100%; padding:10px 0px;">��ϵ� ���䰡 �����ϴ�.</span>
											<?
												}else{
													$sql = "select * from tblproductreview where productcode='".$productcode."' order by date desc limit 5";
													if(false !== $rres = mysql_query($sql,get_db_conn())){
														if(mysql_num_rows($rres)){
															while($tmp = mysql_fetch_assoc($rres)){
																array_push($hotreview,$tmp);
															}
														}
													}
												?>

												<table border="0" cellpadding="0" cellspacing="0" style="width:200px; float:left; margin-right:20px; margin-left:10px;">
													<tr>
														<td colspan="3" style="height:5px;"></td>
													</tr>
													<?
														for($i=5;$i>=1;$i--){
															if(isset($avg[$i])){
																$rpercent = round($avg[$i]/$totalreviews*100);
																$cnt = $avg[$i];
															}else{
																$rpercent = 0;
																$cnt = 0;
															}
													?>
													<tr>
														<td style="width:40px;"><?=$i?> star</td>
														<td style="width:120px; height:20px;" class="reviewmeter"><div style="width:<?=$rpercent?>%; " class="reviewbar"></div></td>
														<td style="text-align:right; width:30px;"><?=number_format($cnt)?></td>
													</td>
													<tr>
														<td colspan="3" style="height:5px;"></td>
													</tr>
													<? } ?>
													<tr>
														<td colspan="3" style=" text-align:center"><a href="#2">See all <?=number_format($totalreviews)?> reviews</a></td>
													</tr>
												</table>

												<table border="0" cellpadding="0" cellspacing="0" style="">
												<? foreach($hotreview as $reviewitem){ ?>
													<tr>
														<td><span style=" color:#000; font-size:14px; font-weight:bold"><?=strCut($reviewitem['content'], 30)?></span></td>
													</tr>
													<tr>
														<td><?=$reviewitem['name']?></td>
													</tr>
													<tr>
														<td height="10"></td>
													</tr>
												<? } ?>
												</table>
											<? } ?>
											</div>
										</div>
									</div>
									<!--��ǰ�� �� ��-->

									<table cellpadding="0" cellspacing="0" width="100%" border="0">
										<tr>
											<td style="padding:0px 30px;">
												<? if($reserveprices>0){ ?>
													<style>
														.prinfoTable td{padding:5px 0px;}
														/*.prinfoTable .tdpadding{padding-left:15px;}*/
														.consumerprice{padding-right:24px;color:#aaaaaa;font-size:17px;background:url('<?=$Dir?>data/design/img/detail/icon_arrow.gif') no-repeat;background-position:95% 6px;}
														#idx_price{color:#888888;}
													</style>
												<? }else{ ?>
													<style>
														.prinfoTable td{padding:5px 0px;}
														/*.prinfoTable .tdpadding{padding-left:15px;}*/
														.consumerprice{padding-right:24px;color:#aaaaaa;font-size:17px;background:url('<?=$Dir?>data/design/img/detail/icon_arrow.gif') no-repeat;background-position:95% 6px;}
														#idx_price{color:#ea2f36;font-size:22px;font-weight:700;line-height:120%;}
													</style>
												<? } ?>
												<table cellpadding="0" cellspacing="0" width="100%" border="0" class="prinfoTable">
													<colgroup>
														<col width="120">
														<col width="0" style="font-size:0px;">
														<col width="">
													</colgroup>
													<?
														$prproductname.="<td class=\"tdpadding\">��ǰ��</td>\n";
														$prproductname.="<td></td>";
														$prproductname.="<td>".$_pdata->productname.$_pdata->goodsStatus."</td>\n";

														if(_empty($_ShopInfo->getMemid())){
															/*
															$reurl=trim(urlencode($_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']));
															$prmemprice.="<td><span style=\"color:#ff3b4f;\">ȸ�� ���㰡</span></td>\n";
															$prmemprice.="<td></td>";
															$prmemprice.="<td><a href=\"/front/login.php?reurl=".$reurl."\"><span style=\"color:#ff3b4f;\">�α���</span></a> <img src=\"/data/design/img/detail/icon_lock.gif\" align=\"absmiddle\" /></td>\n";
															*/
															
														}else{
															
														}

														if(strlen($_pdata->production)>0) {
															$prproduction.="<td class=\"tdpadding\">����ȸ��</td>\n";
															$prproduction.="<td></td>";
															$prproduction.="<td>".$_pdata->production."</td>\n";
														}
														if(strlen($_pdata->madein)>0) {
															$prmadein.="<td class=\"tdpadding\">������</td>\n";
															$prmadein.="<td></td>";
															$prmadein.="<td>".$_pdata->madein."</td>\n";
														}
														if(strlen($_pdata->model)>0) {
															$prmodel.="<td class=\"tdpadding\">�𵨸�</td>\n";
															$prmodel.="<td></td>";
															$prmodel.="<td>".$_pdata->model."</td>\n";
														}
														if(strlen($_pdata->brand)>0) {
															$prbrand.="<td class=\"tdpadding\">�귣��</td>\n";
															$prbrand.="<td></td>";
															if($_data->ETCTYPE["BRANDPRO"]=="Y") {
																$prbrand.="<td><a href=\"".$Dir.FrontDir."productblist.php?brandcode=".$_pdata->brandcode."\">".$_pdata->brand."</a></td>\n";
															} else {
																$prbrand.="<td>".$_pdata->brand."</td>\n";
															}
														}

														//����� ���� ����
														if(strlen($_pdata->userspec)>0) {
															$specarray= explode("=",$_pdata->userspec);
															for($i=0; $i<count($specarray); $i++) {
																$specarray_exp = explode("", $specarray[$i]);
																if(strlen($specarray_exp[0])>0 || strlen($specarray_exp[1])>0) {
																	${"pruserspec".$i}.="<td class=\"tdpadding\">".$specarray_exp[0]."</td>\n";
																	${"pruserspec".$i}.="<td></td>";
																	${"pruserspec".$i}.="<td>".$specarray_exp[1]."</td>\n";
																} else {
																	${"pruserspec".$i} = "";
																}
															}
														}

														if(strlen($_pdata->selfcode)>0) {
															$prselfcode.="<td class=\"tdpadding\">�����ڵ�</td>\n";
															$prselfcode.="<td></td>";
															$prselfcode.="<td>".$_pdata->selfcode."</td>\n";
														}
														if(strlen($_pdata->opendate)>0) {
															$propendate.="<td class=\"tdpadding\">�����</td>\n";
															$propendate.="<td></td>";
															$propendate.="<td>".@substr($_pdata->opendate,0,4).(@substr($_pdata->opendate,4,2)?"-".@substr($_pdata->opendate,4,2):"").(@substr($_pdata->opendate,6,2)?"-".@substr($_pdata->opendate,6,2):"")."</td>\n";
														}
														/*
														if($_pdata->consumerprice>0) {
															$prconsumerprice.="<td>���߰���</td>\n";
															$prconsumerprice.="<td></td>";
															$prconsumerprice.="<td><IMG SRC=\"".$Dir."images/common/won_icon2.gif\" border=\"0\" align=absmiddle><strike>".number_format($_pdata->consumerprice)."��</strike></td>\n";
														}
														*/
														$SellpriceValue=0;

														$prsellprice.="
															<tr class='hiddenPart'>
																<td colspan=3>
																	<table border=0 cellpadding=0 cellspacing=0 style=\"width:100%;background:#f9f9f9;padding:15px 8px;border-top:1px solid #f2f2f2;border-bottom:1px solid #f2f2f2;\">
																		<colgroup>
																			<col width=115 />
																			<col width= />
																			<col width= />
																		</colgroup>
																		<tr>
														";

														if($prentinfo){
															
															//$prentinfo['codeinfo'] = categoryRentInfo($_pdata->productcode);
															$prentinfo['codeinfo'] = venderRentInfo($_pdata->vender,$_pdata->pridx,$_pdata->productcode);

															if(!isset($prentinfo['codeinfo'])){
																$prentinfo['codeinfo'] = categoryRentInfo($_pdata->productcode);
															}

															//$pricetitle = '�뿩��';
															
															if($prentinfo['multiOpt'] == '0'){
																$oinfo = array_shift($prentinfo['options']);
															}
															if($prentinfo['codeinfo']['pricetype']=="long"){
																if($prentinfo['multiOpt'] == '0'){
																	if($oinfo['optionPay']=="�г�" ){
																		$pricetitle = '�г�';
																	}else{
																		$pricetitle = '�Ͻó�';
																	}
																}else{
																	foreach($prentinfo['options'] as $oinfo){
																		if($oinfo['optionPay']=="�г�" ){
																			$pricetitle = '�г�';break;
																		}else{
																			$pricetitle = '�Ͻó�';break;
																		}
																	}
																}
																//$pricetitle = '��';
															}else{
																$pricetitle = '';
															}

															if($prentinfo['codeinfo']['pricetype']=="checkout"){
																if($prentinfo['codeinfo']['checkin_time']<$prentinfo['codeinfo']['checkout_time']){
																	$rent_period = "1��";
																}else{
																	$rent_period = "1��";
																}
															}

															if($prentinfo['codeinfo']['checkin_time']>12){
																$checkin_time = $prentinfo['codeinfo']['checkin_time']-12;
																$checkin_time = "���� ".$checkin_time;
															}else{
																$checkin_time = "���� ".$prentinfo['codeinfo']['checkin_time'];
															}

															if($prentinfo['codeinfo']['checkout_time']>12){
																$checkout_time = $prentinfo['codeinfo']['checkout_time']-12;
																$checkout_time = "���� ".$checkout_time;
															}else{
																$checkout_time = "���� ".$prentinfo['codeinfo']['checkout_time'];
															}

															switch($prentinfo['codeinfo']['pricetype']){
																case 'day': $pricetitle .= '24�ð�'; break;
																case 'time': $pricetitle .= $prentinfo['codeinfo']['base_time'].'�ð�'; break;
																case 'checkout': $pricetitle .= $rent_period; break;
																case 'period': $pricetitle .= ($prentinfo['codeinfo']['base_period']>1)? ($prentinfo['codeinfo']['base_period']-1).'�� '.$prentinfo['codeinfo']['base_period'].'��':$prentinfo['codeinfo']['base_period'].'��'; break;
																case 'long': $pricetitle .= ''; break;
															}
														}else{
															$pricetitle = '�ǸŰ�';
														}

														$prsellprice.="<td>".$pricetitle."</td>\n";

														if($_pdata->consumerprice != $_pdata->sellprice){
															if($prentinfo['codeinfo']['pricetype']!="long"){
																$prsellprice.="<td><strike><font id=\"normal_price\">".number_format($_pdata->consumerprice)."��</font></strike>";
																$sellPer = round(100-(($_pdata->sellprice/$_pdata->consumerprice)*100),2);
																$prsellprice.="<span style=\"color:#FD3C4D;font-weight:bold;\">".$sellPer."%</span>";
																$prsellprice.="</td>";
																$prsellprice.="</tr><tr><td></td>";
															}
														}

														if(strlen($dicker=dickerview($_pdata->etctype,number_format($_pdata->sellprice),1))>0) {
															
															//$prsellprice.="<td>".$pricetitle."</td>\n";
															$prsellprice.="<td>\n";
															//���߰���
															if($_pdata->consumerprice>0) {
																$prsellprice.="<span class=\"consumerprice\"><strike>".number_format($_pdata->consumerprice)."��</strike></span>";
															}
															$prsellprice.=$dicker;
															$prsellprice.="</td>\n";
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
															//$prsellprice.="<td>".$pricetitle."</td>\n";
															//$prsellprice.="<td><img src=\"".$Dir."images/common/won_icon.gif\" border=\"0\" align=\"absmiddle\"><b><FONT color=\"#F02800\" id=\"idx_price\">".number_format($_pdata->sellprice)."��</FONT></b></td>\n";
															$prsellprice.="<td>";
															//���߰���
															if($_pdata->consumerprice>0) {
																$prsellprice.="<span class=\"consumerprice\"><strike>".number_format($_pdata->consumerprice)."��</strike></span>";
															}
															$prsellprice.="<img src=\"".$Dir."images/common/won_icon.gif\" border=\"0\" align=\"absmiddle\">".$strikeStart."<FONT id=\"idx_price\">".number_format($_pdata->sellprice)."��</FONT>".$strikeEnd.$mempricestr."";
															$prsellprice.="</td>\n";

															$prsellprice.="<input type=hidden name=price value=\"".number_format($_pdata->sellprice)."\">\n";

															$prdollarprice ="<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
															$prdollarprice.="<td>�ؿ�ȭ��</td>\n";
															$prdollarprice.="<td></td>";
															$prdollarprice.="<td><FONT id=\"idx_dollarprice\">".$ardollar[0]." ".number_format($_pdata->sellprice/$ardollar[1],2)." ".$ardollar[2]."</FONT></td>\n";
															$prdollarprice.="<input type=hidden name=dollarprice value=\"".number_format($_pdata->sellprice/$ardollar[1],2)."\">\n";
															$SellpriceValue=str_replace(",","",$pricetok[0]);
														} else if(strlen($optcode)>0) {
															//$prsellprice.="<td>".$pricetitle."</td>\n";
															//$prsellprice.="<td><img src=\"".$Dir."images/common/won_icon.gif\" border=\"0\" align=\"absmiddle\"><b><FONT color=\"#F02800\" id=\"idx_price\">".number_format($_pdata->sellprice)."��</FONT></b></td>\n";
															$prsellprice.="<td>";
															//���߰���
															if($_pdata->consumerprice>0) {
																$prsellprice.="<span class=\"consumerprice\"><strike>".number_format($_pdata->consumerprice)."��</strike></span>";
															}
															$prsellprice.="<img src=\"".$Dir."images/common/won_icon.gif\" border=\"0\" align=\"absmiddle\">".$strikeStart."<FONT id=\"idx_price\">".number_format($_pdata->sellprice)."��</FONT>".$strikeEnd.$mempricestr."";
															$prsellprice.="</td>\n";
															$prsellprice.="<input type=hidden name=price value=\"".number_format($_pdata->sellprice)."\">\n";

															$prdollarprice ="<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
															$prdollarprice.="<td>�ؿ�ȭ��</td>\n";
															$prdollarprice.="<td></td>";
															$prdollarprice.="<td><FONT id=\"idx_dollarprice\">".$ardollar[0]." ".number_format($_pdata->sellprice/$ardollar[1],2)." ".$ardollar[2]."</FONT></td>\n";
															$prdollarprice.="<input type=hidden name=dollarprice value=\"".number_format($_pdata->sellprice/$ardollar[1],2)."\">\n";
															$SellpriceValue=$_pdata->sellprice;
														} else if(strlen($_pdata->option_price)==0) {
															if($_pdata->assembleuse=="Y") {
																//$prsellprice.="<td>".$pricetitle."</td>\n";
																//$prsellprice.="<td><img src=\"".$Dir."images/common/won_icon.gif\" border=\"0\" align=\"absmiddle\"><b><FONT color=\"#F02800\" id=\"idx_price\">".number_format(($miniq>1?$miniq*$_pdata->sellprice:$_pdata->sellprice))."��</FONT></b></td>\n";
																$prsellprice.="<td>";
																//���߰���
																if($_pdata->consumerprice>0) {
																	$prsellprice.="<span class=\"consumerprice\"><strike>".number_format($_pdata->consumerprice)."��</strike></span>";
																}
																$prsellprice.="<img src=\"".$Dir."images/common/won_icon.gif\" border=\"0\" align=\"absmiddle\">".$strikeStart."<FONT id=\"idx_price\">".number_format(($miniq>1?$miniq*$_pdata->sellprice:$_pdata->sellprice))."��</FONT>".$strikeEnd.number_format(($miniq>1?$miniq*$memberprice:$memberprice))."��";
																$prsellprice.="</td>\n";

																$prsellprice.="<input type=hidden name=price value=\"".number_format(($miniq>1?$miniq*$_pdata->sellprice:$_pdata->sellprice))."\">\n";

																$prdollarprice.="<td>�ؿ�ȭ��</td>\n";
																$prdollarprice.="<td></td>";
																$prdollarprice.="<td><FONT id=\"idx_dollarprice\">".$ardollar[0]." ".number_format(($miniq>1?$miniq*$_pdata->sellprice:$_pdata->sellprice)/$ardollar[1],2)." ".$ardollar[2]."</FONT></td>\n";
																$prdollarprice.="<input type=hidden name=dollarprice value=\"".number_format(($miniq>1?$miniq*$_pdata->sellprice:$_pdata->sellprice)/$ardollar[1],2)."\">\n";
																$SellpriceValue=($miniq>1?$miniq*$_pdata->sellprice:$_pdata->sellprice);
															} else {
																//$prsellprice.="<td>".$pricetitle."</td>\n";
																//$prsellprice.="<td><img src=\"".$Dir."images/common/won_icon.gif\" border=\"0\" align=\"absmiddle\"><b><FONT color=\"#F02800\" id=\"idx_price\">".number_format($_pdata->sellprice)."��</FONT></b></td>\n";
																$prsellprice.="<td>";
															/*	
															//���߰���
															if($_pdata->consumerprice>0) {
																$prsellprice.="<span class=\"consumerprice\"><strike>".number_format($_pdata->consumerprice)."��</strike></span>";
															}*/
															
																//$prsellprice.=(($_pdata->consumerprice != $_pdata->sellprice) ? "<span style='padding:0px 10px;font-size:25px;line-height:20px;'>��</span>" : "").$strikeStart."<FONT id=\"idx_price\">".number_format($_pdata->sellprice)."��</FONT>".$strikeEnd.$mempricestr."";

																
																if($prentinfo['codeinfo']['pricetype']=="long"){
																	if($prentinfo['multiOpt'] == '0'){											
																		if($oinfo['optionPay']=="�г�" ){
																			$prsellprice.="<FONT id=\"idx_price\">".number_format($oinfo['nomalPrice']/$oinfo['optionName'])."��</font>".$mempricestr."";
																		}else{
																			$prsellprice.="<FONT id=\"idx_price\">".number_format($oinfo['nomalPrice'])."��</font>".$mempricestr."";
																		}
																	}else{
																		foreach($prentinfo['options'] as $oinfo){
																			if($oinfo['optionPay']=="�г�" ){
																				$prsellprice.="<FONT id=\"idx_price\">".number_format($oinfo['nomalPrice']/$oinfo['optionName'])."��</font>".$mempricestr."";
																				break;
																			}else{
																				$prsellprice.="<FONT id=\"idx_price\">".number_format($oinfo['nomalPrice'])."��</font>".$mempricestr."";
																				break;
																			}
																		}
																	}
																}else{
																	$prsellprice.="<FONT id=\"idx_price\">".number_format($_pdata->sellprice)."��</FONT>".$mempricestr."";
																}
																$prsellprice.="</td>\n";
																$prsellprice.="<input type=hidden name=price value=\"".number_format($_pdata->sellprice)."\">\n";

																$prdollarprice.="<td>�ؿ�ȭ��</td>\n";
																$prdollarprice.="<td></td>";
																$prdollarprice.="<td><FONT id=\"idx_dollarprice\">".$ardollar[0]." ".number_format($_pdata->sellprice/$ardollar[1],2)." ".$ardollar[2]."</FONT></td>\n";
																$prdollarprice.="<input type=hidden name=dollarprice value=\"".number_format($_pdata->sellprice/$ardollar[1],2)."\">\n";
																$SellpriceValue=$_pdata->sellprice;
															}
															$priceindex=0;
														}

														$prsellprice.="
																		</tr>
																	</table>
																</td>
															</tr>
														";

														// ���Ű� ���� �߰�
														if(isSeller() == 'Y' AND $_pdata->productdisprice > 0 ){
															$prsellprice .="</tr><tr><td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
															$prsellprice.="<td class=\"tdpadding\">���Ű���</td>\n";
															$prsellprice.="<td></td>";
															$prsellprice.="<td><img src=\"".$Dir."images/common/won_icon.gif\" border=\"0\" align=\"absmiddle\"><b><FONT id=\"idx_wsprice\">".number_format($_pdata->productdisprice)."��</FONT></b></td>\n";
														}
														// #���Ű� ���� �߰�
														
														if($prentinfo['codeinfo']['pricetype'] == 'long'){
															$prsellprice .="</tr><tr>";
															$prsellprice.="<td class=\"tdpadding\">������</td>\n";
															$prsellprice.="<td></td>";
															$prsellprice.="<td>";
																if($prentinfo['codeinfo']['ownership'] =="mv"){
																	$prsellprice.= "������";
																}else{
																	$prsellprice.= "������ �ݳ���";
																}
															$prsellprice.="</td>\n";
															$prsellprice.="</tr><tr>";
															$prsellprice.="<td class=\"tdpadding\">����ī�� ����</td>\n";
															$prsellprice.="<td></td>";
															$prsellprice.="<td><a href=\"javascript:dispinfo()\"><span style=\"border:1px solid #cccccc;padding:2px 4px;font-size:11px;\">������������</span></a></td>\n";
															$prsellprice.="</tr>";
															$prsellprice.="<tr id=\"card_discount\" style=\"display:none\">";
															$prsellprice.="<td class=\"tdpadding\"></td>\n";
															$prsellprice.="<td></td>";
															$prsellprice.="<td style=\"padding-bottom:15px;\">".$prentinfo['codeinfo']['discount_card']."</td>\n";
															$prsellprice.="</tr><tr>";
														}

														//����ǰ ���� �߰�
														if(!_empty($giftprice) && intval($giftprice) > 0){
															$prgift.="<td class=\"tdpadding\">����ǰ</td>\n";
															$prgift.="<td></td>";
															$prgift.="<td><div style=position:relative;>��å���� <a href=# onmouseover=\"showGift.style.visibility='visible'\" onmouseout=\"showGift.style.visibility='hidden'\">[?]</a></div>\n";
															$prgift.="<div id=\"showGift\" style=\"width:260px; margin:0px; padding:10px; position:absolute; background:#ffffff; color:#666; font-size:11px; border:1 solid #ccc; visible; z-index:100; visibility:hidden;\">��ǰ �ǸŰ����� �ƴ� ������������ ���� �� ���Ű����� <b>".number_format($giftprice)."��</b> �̻� ����Ǹ�, �������� ������ ��쳪 ������ǰ�� ���� ����ǰ�� ���� �Ұ����� �� �ֽ��ϴ�.</div></td>";
														}

if($prentinfo['codeinfo']['pricetype']=="checkout"){
	$totalTxt = "���ڱⰣ/�Ѿ�";
	$startTxt = "üũ��/üũ�ƿ�";
}else{
	$totalTxt = "��Ż�Ⱓ/�Ѿ�";
	$startTxt = "�Ⱓ";
//	$startTxt = "�뿩/�ݳ�";2018.10.30
}


if($_pdata->today_reserve=="Y"){
	$startD = date("Y-m-d");
	
	if($prentinfo['codeinfo']['pricetype']=="time"){
		$endD = date("Y-m-d");
	}else if($prentinfo['codeinfo']['pricetype']=="period"){
		$endD = date("Y-m-d",strtotime('+'.($prentinfo['codeinfo']['base_period']-1).' day'));
	}else{
		$endD = date("Y-m-d",strtotime('+1 day'));
	}

}else{
	$startD = date("Y-m-d",strtotime('+1 day'));
	if($prentinfo['codeinfo']['pricetype']=="time"){
		$endD = date("Y-m-d",strtotime('+1 day'));
	}else if($prentinfo['codeinfo']['pricetype']=="period"){
		$endD = date("Y-m-d",strtotime('+'.($prentinfo['codeinfo']['base_period']-1).' day'));
	}else{
		$endD = date("Y-m-d",strtotime('+2 day'));
	}
}

$wk = date("w",strtotime($startD));
switch($wk){
	case "0" : $week = "��";break;
	case "1" : $week = "��";break;
	case "2" : $week = "ȭ";break;
	case "3" : $week = "��";break;
	case "4" : $week = "��";break;
	case "5" : $week = "��";break;
	case "6" : $week = "��";break;
}
//$startD_view = date("m/d",strtotime($startD))."(".$week.")";
$startD_view = "�뿩��";
$wk = date("w",strtotime($endD));
switch($wk){
	case "0" : $week = "��";break;
	case "1" : $week = "��";break;
	case "2" : $week = "ȭ";break;
	case "3" : $week = "��";break;
	case "4" : $week = "��";break;
	case "5" : $week = "��";break;
	case "6" : $week = "��";break;
}
//$endD_view = date("m/d",strtotime($endD))."(".$week.")";
$endD_view = "�ݳ���";

/*
														$rental .= "<td></td>";
														$rental .= "<td></td>";
														$rental .= "<td><img src=\"/data/design/img/detail/btn_rentalchart.gif\" style=\"cursor:pointer;\" onclick=\"bookingSchedulePop(".$_pdata->pridx.");\" border=\"0\" alt=\"��Ż��Ȳ����\" />\n";
														// ���� ���� ���� üũ													
														if($prentinfo['codeinfo']['useseason'] == '1'){
															$rental .= "<img src=\"/data/design/img/detail/btn_seasonchart.gif\" style=\"cursor:pointer;\" onclick=\"bookingPriceCalendalPop('".substr($_pdata->productcode,0,12)."','".$_pdata->vender."','".$_pdata->pridx."');\" border=\"0\" alt=\"��������޷º���\" />";
														}		
														$rental .= "</td></tr><tr>\n";
*/
													if($prentinfo && $prentinfo['codeinfo']['pricetype'] != 'long'){ //���뿩�ɼ��� ��� �Ⱓ����
														//$rental .= "<tr><td colspan=\"3\" style=\"padding-top:15px;\"><td></tr>\n";
														//$rental .= "<tr><td colspan=\"3\" style=\"border-top:1px solid #eceff1;padding-top:15px;\"><td></tr>\n";
														$rental .= "<td class=\"tdpadding\" valign='top'>".$startTxt."</td>\n";
														$rental .= "<td></td>";
														$rental .= "<td>";
/*
														$rental .= "<img src=\"/data/design/img/detail/btn_rentalchart.gif\" style=\"cursor:pointer;margin-bottom:3px\" onclick=\"bookingSchedulePop(".$_pdata->pridx.");\" border=\"0\" alt=\"��Ż��Ȳ����\" />\n";
														// ���� ���� ���� üũ													
														if($prentinfo['codeinfo']['useseason'] == '1'){
															$rental .= "<img src=\"/data/design/img/detail/btn_seasonchart.gif\" style=\"cursor:pointer;margin-bottom:3px\" onclick=\"bookingPriceCalendalPop('".substr($_pdata->productcode,0,12)."','".$_pdata->vender."','".$_pdata->pridx."');\" border=\"0\" alt=\"��������޷º���\" />";
														}
														*/

/*�Ⱓ ����*/											$rental .= '<div style="overflow:hidden;">';
														$rental .= '<div class="tdSize">';
														$rental .= '<p style="font-style:italic;font-size:11px;color:#bbbbbb;display:none;">�뿩</p>';
														$rental .= '<div style="border:1px solid #333333;">';
														$rental .= '<input type="text" name="p_bookingSDate" id="p_bookingSDate" value="'.$startD_view.'" class="input1" onChange="priceCalc2(this.form)" style="color:#ff0000">';
														$rental .= '<input type="hidden" name="p_bookingStartDate" id="p_bookingStartDate" value="" class="input">';
														
														//$rental .= '<input type="hidden" name="pricetype" id="pricetype" value="'.$prentinfo['codeinfo']['pricetype'].'">';
														
														if($prentinfo['codeinfo']['pricetype'] != 'period'){//�ܱ�Ⱓ������
															$rental .= '<select name="startTime" id="startTime" onChange="disableCheck(this)" class="select1">';
															$rental .= '<option value="">�ð�</option>';
															if($prentinfo['codeinfo']['pricetype'] == 'checkout'){//������
																if($prentinfo['codeinfo']['checkout_time']==0 || $prentinfo['codeinfo']['checkin_time']>$prentinfo['codeinfo']['checkout_time']){
																	$end_time = 23;
																}else{
																	$end_time = $prentinfo['codeinfo']['checkout_time'];
																}
																for($i=$prentinfo['codeinfo']['checkin_time'];$i<=$end_time;$i++){
																	$prentinfo['codeinfo']['checkin_time']=$prentinfo['codeinfo']['checkin_time']?$prentinfo['codeinfo']['checkin_time']:date("H")+1;
																	$sel = $i==$prentinfo['codeinfo']['checkin_time']?'selected':'';

																	$rental .= '<option value="'.sprintf('%02d',$i).'" '.$sel.'>'.sprintf('%02d',$i).'��</option>';
																}
															}else{
																for($i=0;$i<=23;$i++){
																	//$prentinfo['codeinfo']['checkin_time']=$prentinfo['codeinfo']['checkin_time']?$prentinfo['codeinfo']['checkin_time']:date("H")+1;
																	//$sel = $i==$prentinfo['codeinfo']['rent_stime']?'selected':'';

																	if($prentinfo['codeinfo']['rent_stime']!="0" && $prentinfo['codeinfo']['rent_etime']!="0" && ($i<$prentinfo['codeinfo']['rent_stime'] || $i>$prentinfo['codeinfo']['rent_etime'])){
																		$optionStyle=" class='disabled'";
																	}else{
																		$optionStyle="";
																	}
																	$rental .= '<option value="'.sprintf('%02d',$i).'" '.$sel.' '.$optionStyle.'>'.sprintf('%02d',$i).'��</option>';
																}
															}
															$rental .= '</select>';
														}
														/*
														if($prentinfo['codeinfo']['pricetype'] == 'time'){

															$rental .= '<select name="startTime" id="startTime"  onChange="priceCalc2(this.form)" class="select1">';
															for($i=0;$i<=23;$i++){
																$rental .= '<option value="'.sprintf('%02d',$i).'">'.sprintf('%02d',$i).'��</option>';
															}
															$rental .= '</select>';
															//$rental .= '&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="startTime" value="00" style="border:0px; width:25px;">�� 00��';
														//}else if($prentinfo['codeinfo']['pricetype'] == 'day') $rental .= '&nbsp;00�� ~ ';
														}else if($prentinfo['codeinfo']['pricetype'] == 'day'){
															$rental .= '<select name="startTime" id="startTime"  onChange="priceCalc2(this.form)" class="select1">';
															for($i=0;$i<=23;$i++){
																$rental .= '<option value="'.sprintf('%02d',$i).'" '.$sel.'>'.sprintf('%02d',$i).'��</option>';
															}
															$rental .= '</select>';
														}
														else if($prentinfo['codeinfo']['pricetype'] == 'checkout'){

															$rental .= '&nbsp;'.$checkin_time.'��';
															$rental .= '<input type="hidden" name="startTime" id="startTime" value="'.$prentinfo['codeinfo']['checkin_time'].'" class="select1">';
														}
														*/


														$rental .= '</div>';
														$rental .= '</div>';
														$rental .= '<div class="tdSize2">';
														$rental .= '<p style="font-style:italic;font-size:11px;color:#bbbbbb;display:none;">�ݳ�</p>';
														$rental .= '<div style="border:1px solid #333333;">';
														$rental .= '<input type="text" name="p_bookingEDate" id="p_bookingEDate" value="'.$endD_view.'" class="input1" onChange="priceCalc2(this.form)" readonly style="color:#ff0000">';
														$rental .= '<input type="hidden" name="p_bookingEndDate" id="p_bookingEndDate" value="" class="input">';
														if($prentinfo['codeinfo']['pricetype'] != 'period'){//�ܱ�Ⱓ������
															$rental .= '<select name="endTime" id="endTime"  onChange="disableCheck(this)" class="select1">';
															$rental .= '<option value="">�ð�</option>';
															if($prentinfo['codeinfo']['pricetype'] == 'checkout'){//������
																if($prentinfo['codeinfo']['checkout_time']==0){
																	$end_time = 23;
																}else{
																	$end_time = $prentinfo['codeinfo']['checkout_time'];
																}
																for($i=0;$i<=$end_time;$i++){
																	if($prentinfo['codeinfo']['checkout_time']==0 && $prentinfo['codeinfo']['pricetype']=="time"){
																		$sel = $i==($prentinfo['codeinfo']['checkin_time']+$prentinfo['codeinfo']['base_time'])?'selected':'';
																	}else{
																		$sel = $i==$prentinfo['codeinfo']['checkout_time']?'selected':'';
																	}
																	
																	$rental .= '<option value="'.sprintf('%02d',$i).'" '.$sel.'>'.sprintf('%02d',$i).'��</option>';
																}
															}else{
																
																for($i=0;$i<=23;$i++){
																	if($prentinfo['codeinfo']['rent_stime']==0 && $prentinfo['codeinfo']['pricetype']=="time"){
																		//$sel = $i==$prentinfo['codeinfo']['rent_etime']?'selected':'';
																	}else{
																		//$sel = $i==$prentinfo['codeinfo']['rent_etime']?'selected':'';
																	}
																	if($prentinfo['codeinfo']['rent_stime']!="0" && $prentinfo['codeinfo']['rent_etime']!="0" && ($i<$prentinfo['codeinfo']['rent_stime'] || $i>$prentinfo['codeinfo']['rent_etime'])){
																		$optionStyle=" class='disabled'";
																	}else{
																		$optionStyle="";
																	}
																	$rental .= '<option value="'.sprintf('%02d',$i).'" '.$sel.' '.$optionStyle.'>'.sprintf('%02d',$i).'��</option>';
																}
															}
															$rental .= '</select>';
														}
/*
														if($prentinfo['codeinfo']['pricetype'] == 'time'){
															$rental .= '<select name="endTime" id="endTime"  onChange="priceCalc2(this.form)" class="select1">';
															for($i=0;$i<=23;$i++){
																$sel = $i==$prentinfo['codeinfo']['base_time']?'selected':'';
																$rental .= '<option value="'.sprintf('%02d',$i).'" '.$sel.'>'.sprintf('%02d',$i).'��</option>';
															}
															$rental .= '</select>';
														}else if($prentinfo['codeinfo']['pricetype'] == 'day'){
															$rental .= '<select name="endTime" id="endTime"  onChange="priceCalc2(this.form)" class="select1">';
															for($i=0;$i<=23;$i++){
																$rental .= '<option value="'.sprintf('%02d',$i).'" '.$sel.'>'.sprintf('%02d',$i).'��</option>';
															}
															$rental .= '</select>';
														}
														else if($prentinfo['codeinfo']['pricetype'] == 'checkout'){ 
															$rental .= '&nbsp;'.$checkout_time.'��';
															$rental .= '<input type="hidden" name="endTime" id="endTime" value="'.$prentinfo['codeinfo']['checkout_time'].'">';
														}
*/
														$rental .= "</div>\n";
														$rental .= "</div>\n";
/*�Ⱓ ��*/												$rental .= "</div>\n";

														$rental .= "<div id=\"periodPrint\"  style=\"color:#568EF5;\"></div>";
														
														$rental .= "</td></tr><tr>\n";
														
																									
													}else{
														//$rental .= '<input type="hidden" name="pricetype" id="pricetype" value="'.$prentinfo['codeinfo']['pricetype'].'">';
													}


														if (strlen($_pdata->deli_type)>0 && $prentinfo['codeinfo']['pricetype']!="checkout") {
															$deli_type = explode(',', $_pdata->deli_type);
															$prdelitype.="<td class=\"tdpadding\">���</td>\n";
															$prdelitype.="<td></td>\n";
															$prdelitype.="<td>";
															$prdelitype.="<input type=\"hidden\" name=\"delitype_count\" value=\"".count($deli_type)."\">";
															if(count($deli_type)==1){
																$prdelitype.=$deli_type[0];
																$prdelitype.="<input type=\"hidden\" name=\"ord_deli_type\" value=\"".$deli_type[0]."\">";
															}else{
																$prdelitype.="<select name=\"ord_deli_type\" id=\"deli_type\" style=\"border:1px solid #333333;height:35px;width:100%\" onchange=\"deliChange(this.value)\">\n";
																$prdelitype.="<option value=\"selec\">�����ϼ���</option>";
																for($i=0,$end=count($deli_type);$i<$end;$i++) {
																	if($deli_type[$i]=="�ù�"){
																		$prdelitype.="<option value=\"".$deli_type[$i]."\">".$deli_type[$i]."-".$delipriceTxt." &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$venderinfo['deli_info1']."</option>";
																	}else{
																		$prdelitype.="<option value=\"".$deli_type[$i]."\">".$deli_type[$i]."</option>";
																	}
																}
																$prdelitype.="</select>\n";
															}
															$prdelitype.="<div id=\"deli_info1\" style=\"display:none;cursor:pointer;margin-top:4px;font-size:9pt\" onclick=\"javascript:deliPopup1()\">".$deliselectTxt." ?</div>\n";
															$prdelitype.="<div id=\"deli_info2\" style=\"display:none;cursor:pointer;margin-top:4px;font-size:9pt\" onclick=\"javascript:deliPopup2()\">������ �̿��� <img src='/data/design/img/detail/icon_question.png' width='17' align='absmiddle' alt='' /></div>\n";
															$prdelitype.="<div id=\"deli_info3\" style=\"display:none;cursor:pointer;margin-top:4px;font-size:9pt\" onclick=\"javascript:deliPopup3()\">�湮���� �̿��� <img src='/data/design/img/detail/icon_question.png' width='17' align='absmiddle' alt='' /></div>\n";
															$prdelitype.="<div id=\"deli_info4\" style=\"display:none;cursor:pointer;margin-top:4px;font-size:9pt\" onclick=\"javascript:deliPopup4()\">��� �̿��� <img src='/data/design/img/detail/icon_question.png' width='17' align='absmiddle' alt='' /></div>\n";
															$prdelitype.="<div id=\"deli_info5\" style=\"display:none;cursor:pointer;margin-top:4px;font-size:9pt\" onclick=\"javascript:deliPopup5()\">��ҿ��� �̿��� <img src='/data/design/img/detail/icon_question.png' width='17' align='absmiddle' alt='' /></div>\n";
															$prdelitype.="</td>\n";
														}

														//��ۺ� ���� �߰�
														if(substr($_pdata->productcode,0,3)!='999' && $prentinfo['codeinfo']['pricetype']!="checkout") {
															$prtrans.="<td>��۹��</td>\n";
															$prtrans.="<td></td>";
															$prtrans.="<td>".$delipriceTxt." / ".$deliRangeStr."</td>\n";
														}


														$_pdata->sellprice = ( $memberprice > 0 ) ? $memberprice : $_pdata->sellprice;
														//$_pdata->reserve = $mem_reserve;
														//$_pdata->reservetype = "Y";
														$reserveconv = $_pdata->reserve*100;
														$reserveprice=getReserveConversion($_pdata->reserve,$_pdata->reservetype,$_pdata->sellprice,"Y");
														//snsȫ���� ��� ������
														if($_data->sns_ok == "Y" && $_pdata->sns_state == "Y" && $sell_memid !=""){
															$reserveconv = getReserveConversionSNS($reserveconv,$_pdata->sns_reserve2,$_pdata->sns_reserve2_type,$_pdata->sellprice,"Y");
														}

														$categoryAuth = categoryAuth ( $productcode );
														if($reserveconv>0 AND $categoryAuth['reserve'] == "Y") {
/*
															$prreserve.="<td style=\"color:#ff0000\">����</td>\n";
															$prreserve.="<td></td>";
															$prreserve.="<td>";
															//$prreserve.="<td><IMG SRC=\"".$Dir."images/common/reserve_icon1.gif\" border=\"0\" align=absmiddle>&nbsp;";
															if($sell_memid !=""){
																$prreserve.="<span style=\"color:#CC0000\">(snsȫ��)</span> ";
															}
															//$reserveprice = intval($_pdata->sellprice*$reserveconv*0.01);
															$prreserve.="<FONT id=\"idx_reserve\" style=\"color:#ff0000\">".number_format($reserveprice)."�� (".$reserveconv." %,ȸ���������)</font></td>\n";
																*/
															$prreserve.="<input type=hidden name='reserveconv' id='reserveconv' value='".$reserveconv."'>";
														
														}else{
															$prreserve = '';
														}

														if(strlen($_pdata->addcode)>0) {
															$praddcode.="<td class=\"tdpadding\">Ư�̻���</td>\n";
															$praddcode.="<td></td>";
															$praddcode.="<td>".$_pdata->addcode."</td>\n";
														}

														if($_pdata->rental==1) {
															$prquantity .= "<td class=\"tdpadding\">���ż���</td>\n";
															$prquantity .= "<td></td>";
															$prquantity .= "<td>\n";
															$prquantity .= "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
															$prquantity .= "<tr>\n";
															$prquantity .= "	<td><input type=text name=\"quantity\" value=\"" . ($miniq > 1 ? $miniq : "1") . "\" size=\"4\" class=\"input\" style=\"height:25px;line-height:25px;text-align:center;\"" . (($_pdata->assembleuse == "Y" || substr($productcode, 0, 3) == '999') ? " readonly" : " onkeyup=\"strnumkeyup(this)\"");
															if (substr($productcode, 0, 3) == '999') $prquantity .= " readonly";
															$prquantity .= "></td>\n";
															$prquantity .= "	<td>\n";
															if (substr($productcode, 0, 3) != '999') {
																$prquantity .= "	<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
																$prquantity .= "	<tr>\n";
																//$prquantity.="		<td width=\"5\" height=\"7\" valign=\"top\" style=\"padding-bottom:1px;\"><a href=\"javascript:change_quantity('up')\"><img src=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_neroup.gif\" border=\"0\"></a></td>\n";
																$prquantity .= "		<td valign=\"top\" style=\"padding:0px;font-size:0px;line-height:0%;\"><a href=\"javascript:change_quantity('up')\"><img src=\"" . $Dir . "data/design/img/detail/pdetail_skin_neroup.gif\" border=\"0\" /></a></td>\n";
																$prquantity .= "	</tr>\n";
																$prquantity .= "	<tr>\n";
																//$prquantity.="		<td width=\"5\" height=\"7\" valign=\"bottom\" style=\"padding-top:1px;\"><a href=\"javascript:change_quantity('dn')\"><img src=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_nerodown.gif\" border=\"0\"></a></td>\n";
																$prquantity .= "		<td valign=\"bottom\" style=\"padding:0px;font-size:0px;line-height:0%;\"><a href=\"javascript:change_quantity('dn')\"><img src=\"" . $Dir . "data/design/img/detail/pdetail_skin_nerodown.gif\" border=\"0\" /></a></td>\n";
																$prquantity .= "	</tr>\n";
																$prquantity .= "	</table>\n";
															}
															$prquantity .= "	</td>\n";
															//$prquantity.="	<td width=\"33\">EA</td>\n";
															$prquantity .= "</tr>\n";
															$prquantity .= "</table>\n";
															$prquantity .= "</td>\n";
														}

													if($prentinfo){ // ��Ż��ǰ �ɼ�
														echo '<input type="hidden" name="pricetype" id="pricetype" value="'.$prentinfo['codeinfo']['pricetype'].'">';
														if(_array($prentinfo['locationinfo'])){
															$rentalloc .= "<td>".($prentinfo['itemType'] == "location" ? "������" : "�����" )."</td>\n";
															$rentalloc .= "<td></td>";
															$rentalloc .= "<td>";
															$rentalloc .= $prentinfo['locationinfo']['title']."(".$prentinfo['locationinfo']['address'].")";
															$rentalloc .= "</td></tr>";
														}

														if($prentinfo['multiOpt'] == '0'){
															//$oinfo = array_shift($prentinfo['options']);

															if($prentinfo['codeinfo']['pricetype']=="checkout"){
																$rentalcount .= "<td class=\"tdpadding\">���Ǽ�</td>\n";
															}else{
																$rentalstatus .= "<td  class=\"tdpadding\">����</td>\n";
																$rentalstatus .= "<td></td>";
																$rentalstatus .= "<td>";
																
																if($prentinfo['codeinfo']['pricetype']=="long"){
																	if($prentinfo['codeinfo']['ownership']=="re"){
																		$deposit = "������ ".number_format($oinfo['deposit'])."��, ";
																	}
																	if($oinfo['optionPay']=="�Ͻó�"){
																		$rentalstatus .= "�Ͻú� ".number_format($oinfo['nomalPrice'])."��, ".$deposit.$oinfo['optionName']."���� + ������ : ".number_format($oinfo['prepay'])."��";
																	}else{
																		$rentalstatus .= "�� ".number_format($oinfo['nomalPrice']/$oinfo['optionName'])."��, ".$deposit.$oinfo['optionName']."���� + ������ : ".number_format($oinfo['prepay'])."��";
																	}
																	
																}else{
																	$rentalstatus .= rentProduct::_status($oinfo['grade']);
																}

																$rentalstatus .= "</td></tr>";
																
																if($prentinfo['codeinfo']['pricetype']!="long"){
																	$rentalstatus .= "<tr><td></td><td></td><td>";
																	$rentalstatus .= "<img src=\"/data/design/img/detail/btn_rentalchart.gif\" style=\"cursor:pointer;\" onclick=\"bookingSchedulePop(".$_pdata->pridx.");\" border=\"0\" alt=\"��Ż��Ȳ����\" />\n";
																	// ���� ���� ���� üũ													
																	if($prentinfo['codeinfo']['useseason'] == '1'){
																		$rentalstatus .= "<img src=\"/data/design/img/detail/btn_seasonchart.gif\" style=\"cursor:pointer;\" onclick=\"bookingPriceCalendalPop('".substr($_pdata->productcode,0,12)."','".$_pdata->vender."','".$_pdata->pridx."');\" border=\"0\" alt=\"��������޷º���\" />";
																	}
																	$rentalstatus .= "</td></tr>";
																}

																$rentalcount .= "<td class=\"tdpadding\" valign='top'><span style='display:inline-block;*display:inline;*zoom:1;margin:5px 0px;line-height:29px;'>����</span></td>\n";
															}
															$rentalcount .= "<td></td>";
															//$rental .= "<td><input type='text' value='1' style='width:30px;' class=\"input rentOptionSelect\" name='rentOptions' idxcode=\"".$oinfo['idx']."\" onchange=\"priceCalc2(this.form)\">��<input type='hidden' value='' name='rentOptionList'></td></tr>";

															$rentalcount .= "<td>";
															$rentalcount .= "<div style=\"float:left;overflow:hidden;border:1px solid #333333;width:106px;margin:5px 0px;box-sizing:border-box;\">\n";
															$rentalcount .= "<p style=\"float:left;\"><a href=\"javascript:change_quantity2('dn','".$oinfo['idx']."')\"><img src=\"".$Dir."data/design/img/detail/pdetail_skin_nerodown.gif\" border=\"0\" /></a></p>\n";
															$rentalcount .= "<p style=\"float:left;\"><input type=text value=\"1\" size=\"4\" class=\"input2 rentOptionSelect\" name='rentOptions' idxcode=\"".$oinfo['idx']."\" style=\"height:27px;text-align:center;\" readonly>\n";
															$rentalcount .= "<input type=hidden name=\"o_price_idx[]\" id=\"hidden_oprice_".$oinfo['idx']."\" value=\"".$oinfo['nomalPrice']."\">";
															$rentalcount .= "<input type='hidden' value='' name='rentOptionList'></p>\n";
															$rentalcount .= "<p style=\"float:left;\"><a href=\"javascript:change_quantity2('up','".$oinfo['idx']."')\"><img src=\"".$Dir."data/design/img/detail/pdetail_skin_neroup.gif\" border=\"0\" /></a></p>\n";
															$rentalcount .= "</div>";
															$rentalcount .= "<div style=\"float:left;margin:5px 0px;margin-left:10px;line-height:29px;\" id=\"productCnt_".$oinfo['idx']."\">".$oinfo['productCount']."�� ����</div>";
															$rentalcount .= "<input type=hidden name=\"restCnt_".$oinfo['idx']."\"  id=\"restCnt_".$oinfo['idx']."\" value=\"".$oinfo['productCount']."\">";

															$rentalcount .= "<div style=\"float:right;margin:5px 0px;line-height:29px;\">";

															if($prentinfo['codeinfo']['pricetype']=="long"){
																if($oinfo['optionPay']=="�Ͻó�"){
																	$total_optpay = number_format($oinfo['nomalPrice']+$oinfo['prepay']);
																}else{
																	$total_optpay = number_format($oinfo['nomalPrice']/$oinfo['optionName']+$oinfo['prepay']);
																}
															}else{	
																$total_optpay = number_format($oinfo['nomalPrice']);
															}

															$rentalcount .= "<span id=\"option_price_".$oinfo['idx']."\">".$total_optpay."</span>��";
															$rentalcount .= "</div>";
															$rentalcount .= "</td></tr>";
															


/*
															$proption1 = "<td></td>";
															$proption1 .= "<td></td>";
															$proption1 .= "<td align=\"right\">";
															$proption1 .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"optionTable hiddenPart\">\n";
															$proption1 .= "
																			<colgroup>
																				<col width=\"80\">
																				<col width=\"80\">
																				<col width=\"\">
																			</colgroup>
															";
															$pricetype = $prentinfo['codeinfo']['pricetype'] == 'time'?'�ð���':'�Ϸ�';
															$proption1 .= "
																			<tr>
																				<th>�ɼǸ�</th>
																				<th>����</th>
																				<th>����(".$pricetype.")</th>
																			</tr>
															";
															//if(_array($prentinfo['options'])){
															//	foreach($prentinfo['options'] as $oinfo){
																	if(!_empty($_pdata->discount)){
																		$oinfo['nomalPrice'] = tempSolvDiscount($oinfo['nomalPrice'],$_pdata->discount);
																		$oinfo['halfPrice'] = tempSolvDiscount($oinfo['halfPrice'],$_pdata->discount);
																		$oinfo['busySeason'] = tempSolvDiscount($oinfo['busySeason'],$_pdata->discount);
																		$oinfo['semiBusySeason'] = tempSolvDiscount($oinfo['semiBusySeason'],$_pdata->discount);
																		$oinfo['holidaySeason'] = tempSolvDiscount($oinfo['holidaySeason'],$_pdata->discount);

																		$oinfo['busyHoliSeason'] = tempSolvDiscount($oinfo['busyHolidaySeason'],$_pdata->discount);
																		$oinfo['semiBusyHoliSeason'] = tempSolvDiscount($oinfo['semiBusyHolidaySeason'],$_pdata->discount);
																	}
																	
																	
																	$proption1 .= "<tr align='center'>\n";
																	$proption1 .= "
																				<td>".$oinfo['optionName']."</td>
																				<td>".rentProduct::_status($oinfo['grade'])."</td>
																				<td>
																					<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" >";
																	
																	if($prentinfo['codeinfo']['pricetype'] == 'day'){
																		if($prentinfo['codeinfo']['halfday'] == 'Y'){
																		$proption1 .= "<tr>
																							<td>�� 12�ð�</td>
																							<td align=\"right\">".number_format($oinfo['nomalPrice']*($prentinfo['codeinfo']['halfday_percent']/100))."��</td>
																						</tr>";
																		}
																		$proption1 .= "<tr>
																							<td>�� 24�ð�</td>
																							<td align=\"right\">".number_format($oinfo['nomalPrice'])."��</td>
																						</tr>";	
																	}else if($prentinfo['codeinfo']['pricetype'] == 'time'){
																		$proption1 .= "<tr>
																						<td>".(($prentinfo['codeinfo']['useseason'] == '1')?'�� �Ϲݰ�':'�� �ּҴ뿩�ð�('.$prentinfo['codeinfo']['base_time'].'�ð�)')."</td>
																						<td align=\"right\">".number_format($oinfo['nomalPrice'])."��</td>
																					</tr>";
																		$proption1 .= "<tr>
																						<td>�� �߰� 1�ð���</td>
																						<td align=\"right\">".number_format($prentinfo['codeinfo']['timeover_price']-$prentinfo['codeinfo']['timeover_price']*$oinfo['priceDiscP']/100)."��</td>
																					</tr>";
																	}else{
																		$proption1 .= "<tr>
																							<td>".(($prentinfo['codeinfo']['useseason'] == '1')?'�� �Ϲݰ�':'')."</td>
																							<td align=\"right\">".number_format($oinfo['nomalPrice'])."��</td>
																						</tr>";																				
																		
																	}
																	
																	$holidayPrice = "";
																	if($prentinfo['codeinfo']['useseason'] == '1'){	
																
																		$holidayPrice = $oinfo['nomalPrice'] + round($oinfo['nomalPrice']*$oinfo['holidaySeason']/100);
																																
																		$proption1 .= "
																			<tr>
																				<td>�� ������*����</td>
																				<td align=\"right\"> ".number_format($oinfo['nomalPrice']+round($oinfo['nomalPrice']*$oinfo['busySeason']/100))."��</td>
																			</tr>
																			<tr>
																				<td>�� ������*�ָ�</td>
																				<td align=\"right\"> ".number_format($oinfo['nomalPrice']+round($oinfo['nomalPrice']*$oinfo['busyHolidaySeason']/100))."��</td>
																			</tr>
																			<tr>
																				<td>�� �ؼ�����*����</td>
																				<td align=\"right\">".number_format($oinfo['nomalPrice']+round($oinfo['nomalPrice']*$oinfo['semiBusySeason']/100))."��</td>
																			</tr>
																			<tr>
																				<td>�� �ؼ�����*�ָ�</td>
																				<td align=\"right\">".number_format($oinfo['nomalPrice']+round($oinfo['nomalPrice']*$oinfo['semiBusyHolidaySeason']/100))."��</td>
																			</tr>
																			<tr>
																				<td>�� �����*�ָ���</td>
																				<td align=\"right\">".number_format($holidayPrice)."��</td>
																			</tr>";
																	}
																					
																	$proption1 .= "</table>
																				</td>
																	";
																	$proption1 .= "</tr>\n";
																//}
															//}
															$proption1 .= "</table></td>\n";

															*/
															
														}else{																													
															$proption1 = "";
/*
															$proption1 .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"1\" width=\"100%\" class=\"optionTable\">\n";
															$proption1 .= "
																			<colgroup>
																				<col width=\"80\">
																				<col>
																				<col width=\"\">
																			</colgroup>
															";
*/
															$pricetype = $prentinfo['codeinfo']['pricetype'] == 'time'?'�ð���':'�Ϸ�';

/*
															$proption1 .= "
																			<tr align=\"center\">
																				<th bgcolor=\"#f5f5f5\">�ɼǸ�</th>
																				<th bgcolor=\"#f5f5f5\">����</th>
																				<th bgcolor=\"#f5f5f5\">����(".$pricetype.")</th>
																				<th bgcolor=\"#f5f5f5\">����</th>
																			</tr>
															";

*/
															/*���޷�*/
															if($prentinfo['codeinfo']['pricetype']!="long"){
																$proption1 .= "<tr>";
																$proption1 .= "<td></td>";
																$proption1 .= "<td></td>";
																$proption1 .= "<td><img src=\"/data/design/img/detail/btn_rentalchart.gif\" style=\"cursor:pointer;\" onclick=\"bookingSchedulePop(".$_pdata->pridx.");\" border=\"0\" alt=\"��Ż��Ȳ����\" />\n";
																// ���� ���� ���� üũ													
																if($prentinfo['codeinfo']['useseason'] == '1'){
																	$proption1 .= "<img src=\"/data/design/img/detail/btn_seasonchart.gif\" style=\"cursor:pointer;\" onclick=\"bookingPriceCalendalPop('".substr($_pdata->productcode,0,12)."','".$_pdata->vender."','".$_pdata->pridx."');\" border=\"0\" alt=\"��������޷º���\" />";
																}		
																$proption1 .= "</td></tr>\n";
															}
															/*���޷�*/

															$proption1 .= "<tr>";
															$proption1.="<td class=\"tdpadding\">�ɼ�</td>\n";
															$proption1.="<td></td>";
															$proption1.="<td>\n";

															$proption1 .= "<select name=\"rent_option\"  style=\"width:100%;border:1px solid #333333;height:35px;color:#ff0000;\" size=\"1\" ";
															if ($_data->proption_size > 0) $proption1 .= "style=\"width : " . $_data->proption_size . "px\" ";
															$proption1 .= "onchange=\"change_option(this.value)\">\n";
															$proption1 .= "<option value=\"\">�ɼ��� �����ϼ���\n";
															$proption1 .= "<option value=\"\" style=\"color:#000000\">-----------------\n";

															if(_array($prentinfo['options'])){
																foreach($prentinfo['options'] as $oinfo){
																	if(!_empty($_pdata->discount)){
																		$oinfo['nomalPrice'] = tempSolvDiscount($oinfo['nomalPrice'],$_pdata->discount);
																		$oinfo['halfPrice'] = tempSolvDiscount($oinfo['halfPrice'],$_pdata->discount);
																		$oinfo['busySeason'] = tempSolvDiscount($oinfo['busySeason'],$_pdata->discount);
																		$oinfo['semiBusySeason'] = tempSolvDiscount($oinfo['semiBusySeason'],$_pdata->discount);
																		$oinfo['holidaySeason'] = tempSolvDiscount($oinfo['holidaySeason'],$_pdata->discount);

																		$oinfo['busyHoliSeason'] = tempSolvDiscount($oinfo['busyHoliSeason'],$_pdata->discount);
																		$oinfo['semiBusyHoliSeason'] = tempSolvDiscount($oinfo['semiBusyHoliSeason'],$_pdata->discount);
																	}
																	
																	if($prentinfo['codeinfo']['pricetype']=="long"){
																		if($prentinfo['codeinfo']['ownership']=="re"){
																			$deposit = "������ ".number_format($oinfo['deposit'])."��, ";
																		}
																		
																		if($oinfo['optionPay']=="�Ͻó�"){
																			$proption1 .= "<option value=\"".$oinfo['idx']."\" style=\"color:#000000\">�Ͻú� ".number_format($oinfo['nomalPrice'])."��, ".$deposit.$oinfo['optionName']."���� + ������ : ".number_format($oinfo['prepay'])."��";
																		}else{
																			$proption1 .= "<option value=\"".$oinfo['idx']."\" style=\"color:#000000\">�� ".number_format($oinfo['nomalPrice']/$oinfo['optionName'])."��, ".$deposit.$oinfo['optionName']."���� + ������ : ".number_format($oinfo['prepay'])."��";
																		}
																	}else{
																		$proption1 .= "<option value=\"".$oinfo['idx']."\" style=\"color:#000000\">" . $oinfo['optionName'] ." | ". rentProduct::_status($oinfo['grade']) ." (".number_format($oinfo['nomalPrice'])."��)";
																	}
																	$proption1 .= "</option> \n";

/*
																	$proption1 .= "<tr align='center'>\n";
																	$proption1 .= "
																				<td>".$oinfo['optionName']."</td>
																				<td>".rentProduct::_status($oinfo['grade'])."</td>
																				<td style=\"padding:0px 10px;\">
																					<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" >";
*/

/*
																	if($prentinfo['codeinfo']['pricetype'] == 'day' && !_empty($oinfo['halfPrice'])){
																		if($prentinfo['codeinfo']['halfday'] == 'Y'){
																		$proption1 .= "<tr>
																							<td>�� 12�ð�</td>
																							<td align=\"right\">".number_format($oinfo['halfPrice'])."��</td>
																						</tr>";
																		}
																		$proption1 .= "<tr>
																							<td>�� 24�ð�</td>
																							<td align=\"right\">".number_format($oinfo['nomalPrice'])."��</td>
																						</tr>";
																	}else if($prentinfo['codeinfo']['pricetype'] == 'time'){
																		$proption1 .= "<tr>
																						<td>".(($prentinfo['codeinfo']['useseason'] == '1')?'�� �Ϲݰ�':'')."</td>
																						<td align=\"right\">".number_format($oinfo['nomalPrice'])."��</td>
																					</tr>";
																		$proption1 .= "<tr>
																						<td>�� �߰� 1�ð���</td>
																						<td align=\"right\">".number_format($prentinfo['codeinfo']['timeover_price']-$prentinfo['codeinfo']['timeover_price']*$oinfo['priceDiscP']/100)."��</td>
																					</tr>";
																	}else{
																		$proption1 .= "<tr>
																						<td>".(($prentinfo['codeinfo']['useseason'] == '1')?'�� �Ϲݰ�':'')."</td>
																						<td align=\"right\">".number_format($oinfo['nomalPrice'])."��</td>
																					</tr>";
																	}
																	
																	$holidayPrice = "";
																	if($prentinfo['codeinfo']['useseason'] == '1'){	
																
																		$holidayPrice = $oinfo['nomalPrice'] + round($oinfo['nomalPrice']*$oinfo['holidaySeason']/100);
																																
																		$proption1 .= "
																			<tr>
																				<td>�� ������*����</td>
																				<td align=\"right\"> ".number_format($oinfo['nomalPrice']+round($oinfo['nomalPrice']*$oinfo['busySeason']/100))."��</td>
																			</tr>
																			<tr>
																				<td>�� ������*�ָ�</td>
																				<td align=\"right\"> ".number_format($oinfo['nomalPrice']+round($oinfo['nomalPrice']*$oinfo['busyHoliSeason']/100))."��</td>
																			</tr>
																			<tr>
																				<td>�� �ؼ�����*����</td>
																				<td align=\"right\">".number_format($oinfo['nomalPrice']+round($oinfo['nomalPrice']*$oinfo['semiBusySeason']/100))."��</td>
																			</tr>
																			<tr>
																				<td>�� �ؼ�����*�ָ�</td>
																				<td align=\"right\">".number_format($oinfo['nomalPrice']+round($oinfo['nomalPrice']*$oinfo['semiBusyHoliSeason']/100))."��</td>
																			</tr>
																			<tr>
																				<td>�� �����*�ָ���</td>
																				<td align=\"right\">".number_format($holidayPrice)."��</td>
																			</tr>";
																	}
																					
																	$proption1 .= "</table>
																				</td>
																	";
																	$proption1 .= "
																		<td width='40'>
																			<input type='text' value='0' style='width:30px;' class=\"input rentOptionSelect\" name='rentOptions' idxcode=\"".$oinfo['idx']."\" onchange=\"priceCalc2(this.form)\">��
																		</td>
																	";
																	$proption1 .= "</tr>\n";

*/
																	//�ɼ��� �ִ� ��� ����üũ(ǰ������)
																	if($oinfo['productCount']>0){
																		$_pdata->quantity = $oinfo['productCount'];
																	}
																}
															}
															$proption1 .= "</select>\n";
															$proption1.="</td></tr>\n";
															$proption1 .= "<input type='hidden' value='' name='rentOptionList'>";
															
														}
													}else{

														$proption1 = "";
														if (strlen($_pdata->option1) > 0) {
															$temp = $_pdata->option1;
															$tok = explode(",", $temp);
															$count = count($tok);
															//$proption1 .= "<table cellpadding=\"0\" cellspacing=\"0\">\n";
															//$proption1 .= "<tr>\n";
															$proption1 .= "	<td>$tok[0]&nbsp;&nbsp;</td>\n";
															$proption1 .= "	<td></td><td>";
															if ($priceindex != 0) {
																$proption1 .= "<select name=\"option1\" size=\"1\" style=\"font-size:11px;color:#ffffff;background-color:#404040;letter-spacing:-0.5pt;\" ";
																if ($_data->proption_size > 0) $proption1 .= "style=\"width : " . $_data->proption_size . "px\" ";
																$proption1 .= "onchange=\"change_price(1,document.form1.option1.selectedIndex-1,";
																if (strlen($_pdata->option2) > 0) $proption1 .= "document.form1.option2.selectedIndex-1";
																else $proption1 .= "''";
																$proption1 .= ")\">\n";
															} else {
																$proption1 .= "<select name=\"option1\" size=\"1\" style=\"font-size:11px;color:#ffffff;background-color:#404040;letter-spacing:-0.5pt;\" ";
																if ($_data->proption_size > 0) $proption1 .= "style=\"width : " . $_data->proption_size . "px\" ";
																$proption1 .= "onchange=\"change_price(0,document.form1.option1.selectedIndex-1,";
																if (strlen($_pdata->option2) > 0) $proption1 .= "document.form1.option2.selectedIndex-1";
																else $proption1 .= "''";
																$proption1 .= ")\">\n";
															}

															$optioncnt = explode(",", substr($_pdata->option_quantity, 1));
															$proption1 .= "<option value=\"\" style=\"color:#ffffff;\">�ɼ��� �����ϼ���\n";
															$proption1 .= "<option value=\"\" style=\"color:#ffffff;\">-----------------\n";
															$option_price = $_pdata->option_price;
															for ($i = 1; $i < $count; $i++) {
																$pricetokTemp = 0;
																if (!empty($option_price)) {
																	$pricetok = explode(",", $option_price);
																	if ($pricetok[$i - 1] > 0) {
																		$pricetokTemp = ($pricetok[$i - 1] - $discountprices) - $_pdata->sellprice;
																		$pricetokTempFlag = ($pricetokTemp > 0) ? "+" : "";
																	}
																}
																$priceView = ($pricetokTemp == 0) ? "" : " (" . $pricetokTempFlag . number_format($pricetokTemp) . "��)";
																if (strlen($tok[$i]) > 0) $proption1 .= "<option value=\"" . $i . "\" style=\"color:#ffffff;\">" . $tok[$i] . $priceView . "\n";
																if (strlen($_pdata->option2) == 0 && $optioncnt[$i - 1] == "0") $proption1 .= " (ǰ��)";
															}
															$proption1 .= "</select>";
														} else {
															//$proption1.="<input type=hidden name=option1>";
														}

														$proption2 = "";
														if (strlen($_pdata->option2) > 0) {
															$temp = $_pdata->option2;
															$tok = explode(",", $temp);
															$count2 = count($tok);
															if (strlen($_pdata->option1) <= 0) {
																//$proption2 .= "<table cellpadding=\"0\" cellspacing=\"0\">\n";
															}
															//$proption2 .= "<tr>\n";
															$proption2 .= "	<td>$tok[0]&nbsp;&nbsp;</td>\n";
															$proption2 .= "	<td></td><td>";
															$proption2 .= "<select name=\"option2\" size=\"1\" style=\"font-size:11px;color:#ffffff;background-color:#404040;letter-spacing:-0.5pt;\" ";
															if ($_data->proption_size > 0) $proption2 .= "style=\"width : " . $_data->proption_size . "px\" ";
															$proption2 .= "onchange=\"change_price(0,";
															if (strlen($_pdata->option1) > 0) $proption2 .= "document.form1.option1.selectedIndex-1";
															else $proption2 .= "''";
															$proption2 .= ",document.form1.option2.selectedIndex-1)\">\n";
															$proption2 .= "<option value=\"\" style=\"color:#ffffff;\">�ɼ��� �����ϼ���\n";
															$proption2 .= "<option value=\"\" style=\"color:#ffffff;\">-----------------\n";
															for ($i = 1; $i < $count2; $i++) if (strlen($tok[$i]) > 0) $proption2 .= "<option value=\"$i\" style=\"color:#ffffff;\">$tok[$i]\n";
															$proption2 .= "</select>";
															$proption2 .= "	</td>\n";
															//$proption2 .= "</tr>\n";
															//$proption2 .= "</table>\n";
														} else {
															//$proption2.="<input type=hidden name=option2>";
															if (strlen($_pdata->option1) > 0) {
																$proption1 .= "	</td>\n";
																//$proption1 .= "</tr>\n";
																//$proption1 .= "</table>\n";
															}
														}

														if (strlen($optcode) > 0) {
															$sql = "SELECT * FROM tblproductoption WHERE option_code='" . $optcode . "' ";
															$result = mysql_query($sql, get_db_conn());
															if ($row = mysql_fetch_object($result)) {
																$optionadd = array(&$row->option_value01, &$row->option_value02, &$row->option_value03, &$row->option_value04, &$row->option_value05, &$row->option_value06, &$row->option_value07, &$row->option_value08, &$row->option_value09, &$row->option_value10);
																$opti = 0;
																$option_choice = $row->option_choice;
																$exoption_choice = explode("", $option_choice);
																$proption3 .= "<TABLE cellSpacing=\"0\" cellPadding=\"0\" border=\"0\">\n";
																while (strlen($optionadd[$opti]) > 0) {
																	$proption3 .= "[OPT]";
																	$proption3 .= "<select name=\"mulopt\" style=\"font-size:11px;background-color:#404040;letter-spacing:-0.5pt;\" onchange=\"chopprice('$opti')\"";
																	if ($_data->proption_size > 0) $proption3 .= " style=\"width : " . $_data->proption_size . "px\"";
																	$proption3 .= ">";
																	$opval = str_replace('"', '', explode("", $optionadd[$opti]));
																	$proption3 .= "<option value=\"0,0\" style=\"color:#ffffff;\">--- " . $opval[0] . ($exoption_choice[$opti] == 1 ? "(�ʼ�)" : "(����)") . " ---";
																	$opcnt = count($opval);
																	for ($j = 1; $j < $opcnt; $j++) {
																		$exop = str_replace('"', '', explode(",", $opval[$j]));
																		$proption3 .= "<option value=\"" . $opval[$j] . "\" style=\"color:#ffffff;\">";
																		if ($exop[1] > 0) $proption3 .= $exop[0] . "(+" . $exop[1] . "��)";
																		else if ($exop[1] == 0) $proption3 .= $exop[0];
																		else $proption3 .= $exop[0] . "(" . $exop[1] . "��)";
																	}
																	$proption3 .= "</select><input type=hidden name=\"opttype\" value=\"0\"><input type=hidden name=\"optselect\" value=\"" . $exoption_choice[$opti] . "\">[OPTEND]";
																	$opti++;
																}
																$proption3 .= "<input type=hidden name=\"mulopt\"><input type=hidden name=\"opttype\"><input type=hidden name=\"optselect\">";
																$proption3 .= "</TABLE>\n";
															}
															mysql_free_result($result);
														}
													}
								
											$proption1 .= "<tr>";
											$proption1 .= "		<td></td>";
											$proption1 .= "			<td></td>";
											$proption1 .= "			<td id=\"optionView\"></td>";
											$proption1 .= "		</tr>";

														// ���Ұ�üũ ����
														$useableStr = '';
														/*
														foreach($_pdata->checkAbles as $chkidx=>$etcchk){
															switch($chkidx){
																case 'coupon': $etcname= '��������'; break;
																case 'reserve': $etcname= '������'; break;
																case 'gift': $etcname= '���Ż���ǰ'; break;
																case 'return': $etcname= '��ǰ/��ȯ'; break;
																default:continue;
															}

															$useableStr .="<tr height=\"22\">";
															$useableStr.="	<td><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\"></td>\n";
															$useableStr.="	<td>".$etcname."</td>\n";
															$useableStr.="	<td></td>";
															$useableStr.="	<td>".(($etcchk == 'Y')?'<span style="color:blue">���밡��</span>':'<span style="color:red">����Ұ�</span>').'</td>';
														}
														*/
														//$useableStr.="<tr height=\"22\">";


														if($_pdata->checkAbles['return'] != 'Y'){ // ��ȯ ��ǰ ����
															$useableStr.="	<td class=\"tdpadding\">��ǰ/��ȯ</td>\n";
															$useableStr.="	<td></td>";
															$useableStr.="	<td><span style='color:red'>�Ұ�</span></td>";
														}

														//echo $useableStr;
														//#���Ұ�üũ ����

														/// ���� ��ü �˾� ��ũ(��ǰ���� �����ϰ� ����ϱ� J.Bum)
														/*if(substr($_pdata->productcode,0,3)!='999') {
															$couponpoplink = '';
															if(_array($couponItems)){
																$couponpoplink.="	<td>����</td>\n";
																$couponpoplink.="	<td></td>";
																$couponpoplink.='	<td><a href="javascript:ableCouponPOP(\''.$_pdata->productcode.'\')"><u>���밡�� ��ü����<u></a></td>';
															}
														}*/

													for($i=0;$i<$prcnt;$i++) {
														if(substr($arexcel[$i],0,1)=="O") {	//����
															echo "<tr><td colspan=\"4\" height=\"5\" bgcolor=\"#FFFFFF\"></td></tr>\n";
														} else if ($arexcel[$i]=="7") {	//�ɼ�
															if(strlen($proption1)>0 || strlen($proption2)>0 || strlen($proption3)>0) {
																$proption ="<tr>";
																//$proption.="	<td><!--<IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_point.gif\" border=\"0\">--></td>\n";
																//$proption.="	<td>��ǰ�ɼ�</td>\n";
																//$proption.="	<td></td>";
																//$proption.="	<td colspan=\"3\" style=\"padding:10px 0px;\">\n";
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
														} else if(strlen(trim($arproduct[$arexcel[$i]]))>0) {
															echo "<tr height=\"22\">".$arproduct[$arexcel[$i]]."</tr>\n";
															//echo "<tr><td height=1 bgcolor=#FFFFFF></td></tr>\n";
															if($arexcel[$i]=="9") $dollarok="Y";
														}
													}
												?>


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
														price = new Array(
															<?
																if($priceindex>0) {
																	echo "'".number_format($_pdata->sellprice)."','".number_format($_pdata->sellprice)."',";
																	for($i=0;$i<$priceindex;$i++) {
																		if ($i>0) {
																			echo ",";
																		}
																		echo "'".number_format($pricetok[$i])."'";
																	}
																}
															?>
														);
														doprice = new Array(
															<?
																if($priceindex>0) {
																	echo "'".number_format($_pdata->sellprice/$ardollar[1],2)."','".number_format($_pdata->sellprice/$ardollar[1],2)."',";
																	for($i=0;$i<$priceindex;$i++) {
																		if ($i!=0) {
																			echo ",";
																		}
																		echo "'".$pricetokdo[$i]."'";
																	}
																}
															?>
														);

														if(temp==1) {
															if (document.form1.option1.selectedIndex><? echo $priceindex+2 ?>)
																temp = <?=$priceindex?>;
															else temp = document.form1.option1.selectedIndex;
															document.form1.price.value = price[temp];
															var priceValue = document.form1.price.value.replace(/,/gi,"");
															document.all["idx_price"].innerHTML = number_format( priceValue ) + "��";
															if (document.all["memberprice"]) {
																var discountprices = parseInt(<?=$discountprices?>);
																priceValue = priceValue - discountprices;
																document.all["memberprice"].innerHTML = number_format(priceValue);
															}
													<?if($_pdata->reservetype=="Y" && $_pdata->reserve>0) { ?>
															if(document.getElementById("idx_reserve")) {
																var reserveInnerValue="0";
																if(priceValue>0) {
																	var ReservePer=<?=$_pdata->reserve?>;
																	var ReservePriceValue=Number(priceValue);
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
														//packagecal(); //��Ű�� ��ǰ ����
														if(temp2>0 && temp3>0) {
															if(num[(temp3-1)*10+(temp2-1)]==0){
																alert('�ش� ��ǰ�� �ɼ��� ǰ���Ǿ����ϴ�. �ٸ� ��ǰ�� �����ϼ���.');
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
													
													function option_delete(oidx){
														$j("#hidden_oidx_"+oidx).remove();
														$j("#tbl_"+oidx).remove();
														//$j("#option_"+oidx).remove();
														priceCalc2(document.form1);
													}

													function change_option(oidx){
														for(i=0;i<document.getElementsByName("o_idx[]").length;i++){
															if(document.getElementsByName("o_idx[]")[i].value==oidx){
																alert("�̹� ������ �ɼ��Դϴ�.");return;
															}
														}

														var htmlView = "";
														htmlView += "<table border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"background:#f9f9f9;border:1px solid #f2f2f2;padding:10px;\" id=\"tbl_"+oidx+"\">";

														<?
														foreach($prentinfo['options'] as $oinfo){	
														?>
															if(oidx==<?=$oinfo['idx']?>){
																htmlView += "<tr id=\"option_"+oidx+"\">";
																htmlView += "<td>";

																htmlView += "<div style=\"overflow:hidden;\">";
																htmlView += "	<div style=\"float:left\">";
																htmlView += "		<div>";
																htmlView += "			<input type=hidden name=\"o_idx[]\" id=\"hidden_oidx_"+oidx+"\" value=\""+oidx+"\">";

																<?
																if($prentinfo['codeinfo']['pricetype']=="long"){
																	if($oinfo['optionPay']=="�Ͻó�"){
																		$total_optpay = number_format($oinfo['nomalPrice']+$oinfo['prepay']);
																?>
																		htmlView += "<input type=hidden name=\"o_price_idx[]\"		id=\"hidden_oprice_"+oidx+"\" value=\"<?=$oinfo['nomalPrice']+$oinfo['prepay']?>\">";
																		htmlView += "�Ͻú� <?=number_format($oinfo['nomalPrice'])?>��,<?=$oinfo['optionName']?>���� ";
																<?		
																	}else{
																		$total_optpay = number_format($oinfo['nomalPrice']/$oinfo['optionName']+$oinfo['prepay']);
																?>
																		htmlView += "<input type=hidden name=\"o_price_idx[]\" id=\"hidden_oprice_"+oidx+"\" value=\"<?=$oinfo['prepay']?>\">";
																		htmlView += "�� <?=number_format($oinfo['nomalPrice']/$oinfo['optionName'])?>��,<?=$oinfo['optionName']?>���� ";
																<?
																	}
																}else{	
																	$total_optpay = number_format($oinfo['nomalPrice']);
																?>
																	htmlView += "<input type=hidden name=\"o_price_idx[]\" id=\"hidden_oprice_"+oidx+"\" value=\"<?=$oinfo['nomalPrice']?>\">";
																	htmlView += "<?=$oinfo['optionName']?> | <?=rentProduct::_status($oinfo['grade'])?>";
																
																<? } ?>

																//htmlView += "<input type='text' value='1' style='width:30px;' class=\"input rentOptionSelect\" name='rentOptions' idxcode=\"<?=$oinfo['idx']?>\" onchange=\"priceCalc2(document.form1)\">��";

																htmlView += "		</div>";
																//htmlView += "<td width=\"60\" align=\"right\">";
																//htmlView += "<input type=text value=\"1\" size=\"4\" class=\"input rentOptionSelect\" name='rentOptions' idxcode=\"<?=$oinfo['idx']?>\" style=\"height:25px;line-height:25px;text-align:center;\" readonly></td>\n";

															<? if (substr($productcode, 0, 3) != '999') { ?>
																htmlView += "		<div style=\"float:left;overflow:hidden;border:1px solid #333333;width:106px;margin:5px 0px;\">\n";
																htmlView += "			</p>\n";
																htmlView += "			<p style=\"float:left;\"><a href=\"javascript:change_quantity2('dn','<?=$oinfo['idx']?>')\"><img src=\"<?=$Dir?>data/design/img/detail/pdetail_skin_nerodown.gif\" border=\"0\" /></a></p>\n";
																htmlView += "			<p style=\"float:left;\"><input type=text value=\"1\" size=\"4\" class=\"input2 rentOptionSelect\" name='rentOptions' idxcode=\"<?=$oinfo['idx']?>\" style=\"height:25px;line-height:25px;text-align:center;\" readonly>\n";
																htmlView += "			<p style=\"float:left;\"><a href=\"javascript:change_quantity2('up','<?=$oinfo['idx']?>')\"><img src=\"<?=$Dir?>data/design/img/detail/pdetail_skin_neroup.gif\" border=\"0\" /></a></p>\n";
																htmlView += "		</div>\n";
															<? } ?>
																htmlView += "		<div style=\"float:left;margin:5px 5px;\" id=\"productCnt_"+oidx+"\"><?=$oinfo['productCount']?>�� ����</div>";
																htmlView += "		<input type=hidden name=\"restCnt_"+oidx+"\"  id=\"restCnt_"+oidx+"\" value=\"<?=$oinfo['productCount']?>\">";
																htmlView += "	</div>\n";
																htmlView += "	<div style=\"float:right;margin-top:20px;\">";
																htmlView += "		<span id=\"option_price_"+oidx+"\"><?=$total_optpay?></span>��";
																htmlView += "		<a href=\"javascript:option_delete('<?=$oinfo['idx']?>')\" style=\"padding:0px 5px\"><img src=\"/data/design/img/detail/icon_close.gif\"></a>";
																htmlView += "	</div>";
																htmlView += "</div>";

																htmlView += "</td>";
																htmlView += "</tr>";
															}
														<?
														}	
														?>
														htmlView += "</table>";

														$j("#optionView").append(htmlView);
														if(document.getElementsByName("o_idx[]").length>0){
															priceCalc2(document.form1);
														}
													}
												</script>

												

												<? if($_pdata->rental==2){ ?>
												<input type="hidden" name="pridx" id="pridx" value="<?=$_pdata->pridx?>">
												<tr>
													<td colspan="3" id="priceCalcPrint"></td>
												</tr>
												<!--
												<tr>
													<td><?=$totalTxt?></td>
													<td></td>
													<td>
														<input type="hidden" name="pridx" id="pridx" value="<?=$_pdata->pridx?>">
														<span id="priceCalcPrint" style="color:#ec2f36;font-family:tahoma,����;"></span>
													</td>
												</tr>
												-->
												<? } ?>
												</table>
											</td>
										</tr>
										<tr>
											<td>
												<div style="margin-left:30px;padding:40px 0px;text-align:left;overflow:hidden;" class="detailBtn">
												<?
													if(substr($productcode,0,3)=='999') {
														if($_pdata->rental != 2 && strlen($_pdata->quantity)>0 && $_pdata->quantity<=0)
															echo "<FONT style=\"color:#F02800;\"><b>ǰ ��</b></FONT>";
														else {
															echo "<a href=\"javascript:CheckForm('ordernow2','".$opti."')\" onMouseOver=\"window.status='�����ϱ�';return true;\" style=\"display:inline-block;background:#828f9a;color:#ffffff;line-height:70px;font-size:19px;text-align:center;height:70px;padding:0px 10px;\">�����ϱ�</a>\n";
															echo "<a href=\"javascript:CheckForm('ordernow3','".$opti."')\" onMouseOver=\"window.status='���α���';return true;\" style=\"display:inline-block;background:#828f9a;color:#ffffff;line-height:70px;font-size:19px;text-align:center;height:70px;padding:0px 23px;\">���α���</a>\n";
														}
													}
													else if(strlen($dicker)==0) {
														if($_pdata->rental != 2 && strlen($_pdata->quantity)>0 && $_pdata->quantity<=0)
															echo "<FONT style=\"color:#F02800;\"><b>ǰ ��</b></FONT>";
														else {

															if( $_pdata->rental == 2 ) {
																echo "<a href=\"javascript:checkRequest()\" onMouseOver=\"window.status='��Ż';return true;\" style=\"display:inline-block;border-radius:4px;background:#ea2f36;color:#ffffff;line-height:70px;font-size:19px;text-align:center;height:70px;padding:0px 15px;\">���� ��Ż</a>\n";
															} else {
																echo "<a href=\"javascript:CheckForm('ordernow','".$opti."')\" onMouseOver=\"window.status='�ٷα���';return true;\" style=\"display:inline-block;border-radius:4px;background:#ea2f36;color:#ffffff;line-height:70px;font-size:19px;text-align:center;height:70px;padding:0px 15px;\">�ٷα���</a>\n";	
															}
															echo "<a href=\"javascript:CheckForm('recommandnow','".$opti."')\" onMouseOver=\"window.status='Ÿȸ������ ��õ';return true;\" style=\"display:inline-block;border-radius:4px;background:#828f9a;color:#ffffff;line-height:70px;font-size:19px;text-align:center;height:70px;padding:0px  15px;\">Ÿȸ������ ��õ</a>\n";

															if($prentinfo['codeinfo']['pricetype']!="long"){
																//echo "<a href=\"javascript:basketPopup('".$opti."')\" onMouseOver=\"window.status='��ٱ���';return true;\" style=\"display:inline-block;border-radius:4px;background:#828f9a;color:#ffffff;line-height:70px;font-size:19px;text-align:center;height:70px;padding:0px  15px;\">��ٱ���</a>\n";
															}

														}
														
														if($prentinfo['codeinfo']['pricetype']!="long"){
															//echo "<a href=\"javascript:CheckForm('prebasket','');\" style=\"display:inline-block;border-radius:4px;background:#828f9a;color:#ffffff;line-height:70px;font-size:19px;text-align:center;height:70px;padding:0px 15px;\">�켱���</a>\n";
															echo "<a href=\"javascript:basketPopup('".$opti."');\" style=\"display:inline-block;border-radius:4px;background:#828f9a;color:#ffffff;line-height:70px;font-size:19px;text-align:center;height:70px;padding:0px 15px;\">�켱���</a>\n";
														}

														if (strlen($_ShopInfo->getMemid())>0 && $_ShopInfo->getMemid()!="deleted") {
															//echo "<a href=\"javascript:CheckForm('wishlist','".$opti."')\"><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_btn03.gif\" border=0 align=middle></a>\n";
															//echo "<a href=\"javascript:CheckForm('wishlist','".$opti."')\"><IMG SRC=\"".$Dir."data/design/img/detail/btn_wishlist.gif\" border=\"0\" align=\"absmiddle\" /></a>\n";
															echo "<a href=\"javascript:wishPopup('".$opti."')\" style=\"display:inline-block;border-radius:4px;background:#828f9a;color:#ffffff;line-height:70px;font-size:19px;text-align:center;height:70px;padding:0px 15px;\">���ϱ�</a>\n";
														} else {
															//echo "<a href=\"javascript:check_login();\"><IMG SRC=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_btn03.gif\" border=0 align=absmiddle></a>\n";
															echo "<a href=\"javascript:check_login();\" style=\"display:inline-block;border-radius:4px;background:#828f9a;color:#ffffff;line-height:70px;font-size:19px;text-align:center;height:70px;padding:0px 15px;\">���ϱ�</a>\n";
														}
													}

													//������+�����ϱ� ��ư
													if($odrChk &&($_pdata->present_state == "Y" || $_pdata->pester_state == "Y")) {
														if($_pdata->pester_state == "Y"){
													?>
														<!--<a href="javascript:CheckForm('pester','<?=$opti?>')"><img src="<?=$Dir?>images/design/productdetail_pester.gif" border="0" align="absmiddle" /></a>-->
														<a href="javascript:CheckForm('pester','<?=$opti?>')"><img src="<?=$Dir?>data/design/img/detail/btn_pester.gif" border="0" align="absmiddle" /></a>
													<?
														}
														//if($_pdata->present_state == "Y"){
													?>
														<!--<a href="javascript:CheckForm('present','<?=$opti?>')"><img src="<?=$Dir?>images/design/productdetail_present.gif" border="0" align="absmiddle" /></a>-->
													<? //} ?>
													<? } ?>
													
															<br>
															<?
															if($_pdata->booking_confirm){//��ǰ�� ������ �ִ°��
																$total_sql="select count(*) as totalcnt from tblorderinfo o left join tblorderproduct op on o.ordercode=op.ordercode where op.productcode='".$_pdata->productcode."'";
																$total_res=mysql_query($total_sql,get_db_conn());
																$total_row=mysql_fetch_object($total_res);

																if($_pdata->booking_confirm=="now"){
																	echo "���� ��� ���� Ȯ�� �����";
																}else{
																	echo "���� �� ";
																	$arrconfirmTime = explode(":",$_pdata->booking_confirm);
																	if($arrconfirmTime[0]=="00"){
																		echo $arrconfirmTime[1]."��";

																		$confirm_sql="select count(*) as cnt from tblorderinfo o left join tblorderproduct op on o.ordercode=op.ordercode where op.productcode='".$_pdata->productcode."' and timestampdiff(minute,bank_date,prd_status_date)<=".$arrconfirmTime[1];
																	}else{
																		echo $arrconfirmTime[0]."�ð�";

																		$confirm_sql="select count(*) as cnt from tblorderinfo o left join tblorderproduct op on o.ordercode=op.ordercode where op.productcode='".$_pdata->productcode."' and timestampdiff(hour,bank_date,prd_status_date)<=".$arrconfirmTime[0];
																	}

																	$confirm_res=mysql_query($confirm_sql,get_db_conn());
																	$confirm_row=mysql_fetch_object($confirm_res);
																	
																	if($total_row->totalcnt>0){
																		$bookingper = round(($confirm_row->cnt/$total_row->totalcnt) * 100,1);
																	}else{
																		$bookingper = 99;
																	}

																	echo "�� �˸�, ".$bookingper."%���� ����";
																}

															}else if($venderinfo['booking_confirm']){//������ ������ �ִ°��
																$total_sql="select count(*) as totalcnt from tblorderinfo o left join tblorderproduct op on o.ordercode=op.ordercode where op.vender='".$_pdata->vender."'";
																$total_res=mysql_query($total_sql,get_db_conn());
																$total_row=mysql_fetch_object($total_res);
																
																if($venderinfo['booking_confirm']=="now"){
																	echo "���� ��� ���� Ȯ�� �����";
																}else{
																	echo "���� �� ";
																	$arrconfirmTime = explode(":",$venderinfo['booking_confirm']);
																	if($arrconfirmTime[0]=="00"){
																		echo $arrconfirmTime[1]."��";

																		$confirm_sql="select count(*) as cnt from tblorderinfo o left join tblorderproduct op on o.ordercode=op.ordercode where op.vender='".$_pdata->vender."' and timestampdiff(minute,bank_date,prd_status_date)<=".$arrconfirmTime[1];
																	}else{
																		echo $arrconfirmTime[0]."�ð�";

																		$confirm_sql="select count(*) as cnt from tblorderinfo o left join tblorderproduct op on o.ordercode=op.ordercode where op.vender='".$_pdata->vender."' and timestampdiff(hour,bank_date,prd_status_date)<=".$arrconfirmTime[0];
																	}

																	$confirm_res=mysql_query($confirm_sql,get_db_conn());
																	$confirm_row=mysql_fetch_object($confirm_res);
																	
																	if($total_row->totalcnt>0){
																		$bookingper = round(($confirm_row->cnt/$total_row->totalcnt) * 100,1);
																	}else{
																		$bookingper = 99;
																	}

																	echo "�� �˸�, ".$bookingper."%���� ����";
																}
															}
															?>
															
															</div>
														</td>
													</tr>
												</table>
</div>












											</td>
										</tr>

<!-- �� üũ�ƿ� --
<tr>
	<td>
		<table width="100%">
		   <tr>
			  <td style="padding-top:25px;text-align:right;">
				 <div style="border-top:2px solid #000000;">
					<div style="margin-top:-2px;text-align:right;"><?//=$checkoutObj->btn($_ShopInfo->getTempkey())?></div>
				 </div> 	
			  <td>
		   </tr>
		</table>
	</td>
</tr>
-->

										<input type=hidden name=code value="<?=$code?>">
										<input type=hidden name=productcode value="<?=$productcode?>">
										<input type=hidden name=ordertype>
										<input type=hidden name=opts>
										<input type=hidden name=sell_memid value="<?=$sell_memid?>">
										<?=($brandcode>0?"<input type=hidden name=brandcode value=\"".$brandcode."\">\n":"")?>


										<!-- ��ǰ�� ���� �̺�Ʈ ����(��ǰ ���� �ٷ� �Ʒ�) -->
										<?if($detailimg_eventloc=="1"){?>
										<tr><td height="20"></td></tr>
										<tr>
											<td><?=$detailimg_body?></td>
										</tr>
										<?}?>
									</table>
								</td>
							</tr>
						</table>
					</div>
				</td>
			</tr>

			<!-- ������ ���� �� ������ ����/��ǰ ��� j.bum -->
			<? if($_pdata->vender>0){ ?>
			<tr><td height="100"></td></tr>
			<tr>
				<td>
<!--�������-->
			<?
				if($_pdata->vender){
					$revcnt = 0;					
					$sql = "select count(*) from tblregiststore where vender='".$_pdata->vender."'";
					if(false !== $res = mysql_query($sql,get_db_conn())){
						$revcnt = mysql_result($res,0,0);
					}											
				
					if(!_empty($venderinfo['vgicon']) && is_file($Dir.'data/shopimages/vender/'.$venderinfo['vgicon'])){
						$vgicon = '&nbsp;<img src="'.$Dir.'data/shopimages/vender/'.$venderinfo['vgicon'].'" style="height:20px" />';
					}
					
			?>
					<table border="0" cellpadding="0" cellspacing="0" width="100%" style="border:1px solid #ededed;">
					<colgroup>
						<col width="220">
						<col width="*">
					</colgroup>
						<tr>
							<td align="center" valign="middle">
								<!--�Ǹ����� �ٸ� ��ǰ ����-->
								<!-- ������ ���� START -->
								<?// $v_info = mysql_fetch_assoc ( mysql_query( "SELECT * FROM `tblvenderinfo` WHERE `vender`=".$_pdata->vender." LIMIT 1;" ,get_db_conn()) ); ?>
								<div style="margin:15px auto; text-align:left;">
									<ul style="list-style:none; margin:0px; padding:0px;">
										<!--
										<li style="height:80px; text-align:center; overflow-y:hidden; border:1px solid #dddddd; font-size:0px;">
											<img src="/data/shopimages/vender/<?=$v_info[com_image]?>" onerror="this.src='/images/003/logo.gif';" width="150" border="0" alt="" />
										</li>
										-->
										<li style="text-align:center;">
											<A HREF="javascript:GoMinishop('<?=$Dir.(MinishopType=="ON"?"minishop/":"minishop.php?storeid=").$_vdata->id?>')" style="text-decoration:none;"><span style="color:#333333; font-weight:bold;font-size:22px;"><?=$_vdata->brand_name?></span><br><?=$v_info[com_owner]?></A>
										</li>
										<li style="text-align:center;"><?=$venderinfo['vgname']?><?=$vgicon?></li>
										<li style="padding:5px 0px;text-align:center;"><span style="font-size:12px; letter-spacing:-1px;">��ǰ�� <?=$_vdata->prdt_cnt?>��</span></li>
										<li style="text-align:center;padding:25px 0px 15px;">
											<a href="javascript:custRegistMinishop();" class="btn_sline1"><img src="/upload/img/icon/icon_favorit.gif" style="vertical-align:middle;"><span style="padding-left:4px;">����</span></a>
											<A HREF="javascript:GoMinishop('<?=$Dir.(MinishopType=="ON"?"minishop/":"minishop.php?storeid=").$_vdata->id?>')"  class="btn_sline1"><img src="/upload/img/icon/icon_store.gif"  style="padding-bottom:5px;vertical-align:middle;"><span style="padding-left:4px;">�����</span></a></li>
										<li style="text-align:center;"><? for($i=1;$i<=5;$i++){ $addclass = ($venderinfo['starmark']>= $i)?' active':''; ?><div class="starmark <?=$addclass?>">��</div><? } ?></li>
									</ul>
								</div>
								<!-- ������ ���� END -->
							</td>
							<td valign="top" style="padding:30px 0px;">
								<!-- ������ ��ǰ ��� START -->
									<?=$venderproduct?>
								<!-- ������ ��ǰ ��� END -->
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<?}?>


	<?
	//��ǰ�� �ϴ��������
	if(substr($productcode,0,3)!='999') {
		
	?>
	
	
			</form>

			<tr><td height="20" id="detail_view_point"></td></tr>

			<!-- ��ǰ�� ���� �̺�Ʈ ����(��ǰ ������ �ٷ� ��) -->
			<?if($detailimg_eventloc=="2"){?>
			<tr><td height="20"></td></tr>
			<tr><td><?=$detailimg_body?></td></tr>
			<?}?>

			<? /////////////////////////////////////////////

				//���û�ǰ (������ ���)
				if($_data->coll_loc=="1") {
					echo "<tr>\n";
					echo "	<td height=\"20\"></td>\n";
					echo "</tr>\n";
					echo "<tr>\n";
					echo "	<td><a name=\"2\"></a>\n";
					echo "	<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" class=\"prDetailTab\">\n";
					echo "	<tr>\n";
					echo "		<td><a href=\"#1\">��ǰ������</td>\n";
					echo "		<td><a href=\"#2\">���û�ǰ</a></td>\n";
					echo "		<td><a href=\"#3\">���/AS/ȯ�Ҿȳ�</a></td>\n";
					echo "		<td><a href=\"#4\">����ı�</a></td>\n";
					echo "		<td><a href=\"#5\">��ǰQ&A</a></td>\n";
//					if($_data->sns_ok == "Y" && $_pdata->sns_state == "Y"){
//						echo "		<td><a href=\"#6\">SNS �ҹ�����</a></td>\n";
//					}
//					if($_data->sns_ok == "Y" && $_pdata->gonggu_product == "Y"){
//						echo "		<td><a href=\"#7\">�������Ž�û(".$product_Gonggu_Count.")</a></td>\n";
//					}
					//echo "		<td width=\"100%\" background=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_detailtitlebg.gif\"></td>\n";
					echo "	</tr>\n";
					echo "	</table>\n";
					echo "	</td>\n";
					echo "</tr>\n";
					echo "<tr>\n";
					echo "	<td>".$collection_body."</td>\n";
					echo "</tr>\n";
				}
			?>

			<tr><td height="100"></td></tr>
			<tr>
				<td>
					<a name="1"></a>
							<table cellpadding="0" cellspacing="0" width="100%" class="prDetailTab">
								<tr>
									<td class="prDetailTabOn"><a href="#1">��ǰ������</a></td>
									<td class="prDetailTabOff2"><a href="#2">���û�ǰ</a></td>
<!--									<? if($_data->coll_loc != '0' && strlen($collection_list) > 0) { ?><td class="prDetailTabOff2"><a href="#2">���û�ǰ</a></td><? } ?>-->
									<td class="prDetailTabOff2"><a href="#3">���/AS/ȯ�Ҿȳ�</a></td>
									<td class="prDetailTabOff2"><a href="#4">����ı� (<?=$counttotal?>)</a></td>
									<td class="prDetailTabOff2"><a href="#5">��ǰQ&A (<?=$qnacount?>)</a></td>
									<!--<td class="prDetailTabOff2"><a href="#6">SNS �ҹ����� (<?=$product_SNS_Count?>)</a></td>-->
									<? if($_data->sns_ok == "Y" && $_pdata->gonggu_product == "Y"){?><td class="prDetailTabOff"><a href="#7">�������Ž�û(<?=$product_Gonggu_Count?>)</a></td><?}?>
								</tr>
							</table>

					<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed;">
						<tr>
							<td valign="top">
								<div class="productDetailWrap">
									<table cellpadding="0" cellspacing="0" width="100%" border="0" class="prDetailContents">
										<tr>
											<td>

												<div style="border:10px solid #f9f9f9;">
												<table border="0" cellpadding="0" cellspacing="0" width="100%">
													<tr>
														<td>
															<?
																if($prentinfo['codeinfo']['pricetype'] == 'long'){
																	echo "<div class=\"prDetailContents\"><p style=\"background:#f9f9f9;line-height:30px;text-align:center;font-weight:bold;color:#333333\">�ߵ������� �ؾ���</p><br><div style=\"padding:0px 15px;line-height:23px;\">".nl2br($prentinfo['codeinfo']['cancel_cont'])."</div></div>";
																}

																if(strlen($detail_filter)>0) {
																	$_pdata->content = preg_replace($filterpattern,$filterreplace,$_pdata->content);
																}

																if (strpos($_pdata->content,"table>")!=false || strpos($_pdata->content,"TABLE>")!=false){
																	echo "<pre>".$_pdata->content."</pre>";
																}else if(strpos($_pdata->content,"</")!=false){
																	echo ereg_replace("\n","<br>",$_pdata->content);
																}else if(strpos($_pdata->content,"img")!=false || strpos($_pdata->content,"IMG")!=false){
																	echo ereg_replace("\n","<br>",$_pdata->content);
																}else{
																	echo ereg_replace(" ","&nbsp;",ereg_replace("\n","<br>",$_pdata->content));
																}
															?>
														</td>
													</tr>
												</table>
												</div>


											</td>
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


										<tr><td height="100"></td></tr>
										<tr>
											<td>
												<table border="0" cellpadding="0" cellspacing="0" width="100%" style="border:1px solid #ededed;padding:10px;" class="prDetailContents">
													<colgroup>
														<col width="200">
														<col>
														<col width="30%">
													</colgroup>
													<tr>
														<td align="center">
														<?
														//��ǥ�̹���
														if(!_empty($venderinfo['com_image'])){
															$comImg = $com_image_url.$venderinfo['com_image'];

															$size = getimagesize($com_image_url.$venderinfo['com_image']);
															$width = $size[0]; $height = $size[1];


															$ratiow = ($size[0] > 185)?(real)((80*100)/$size[1]):1;
															$ratioh = ($size[1] > 80)?(real)((165*100)/$size[0]):1;

															if($ratiow < $ratioh){
																$width = ceil(($size[0]*$ratiow)/100);
																$height = 80;
															}else{
																$width = 165;
																$height = ceil(($size[1]*$ratioh)/100);
															}

														}else{
															$comImg = "/images/minishop/logo.gif";
															$width = "165"; $height = "80";
														}
														?><img src="<?=$comImg?>" width="<?=$width?>" height="<?=$height?>" alt="" /></td>
														<td>
															<table border="0" cellpadding="0" cellspacing="0">
																<tr>
																	<td><A HREF="javascript:GoMinishop('<?=$Dir.(MinishopType=="ON"?"minishop/":"minishop.php?storeid=").$_vdata->id?>')"><?=$venderinfo['brand_name']?></A></td>
																	<td><A HREF="javascript:GoMinishop('<?=$Dir.(MinishopType=="ON"?"minishop/":"minishop.php?storeid=").$_vdata->id?>')" style="margin-left:5px;"><img src="/upload/img/icon/btn_vender_gostore.gif" border="0" alt="�����ٷΰ���" /></a></td>
																</tr>
															</table>
														</td>
														<td>
															<table border="0" cellpadding="0" cellspacing="0">
																<tr>
																	<td style="width:100px;">�������</td>
																	<td><?=$venderinfo['vgname']?></td>
																	<td><?=$vgicon?></td>
																</tr>
																<tr>
																	<td colspan="3" style="height:10px;"></td>
																</tr>
																<tr>
																	<td style="width:100px;">���ɵ�ϼ�</td>
																	<td colspan="2"><?=number_format($revcnt)?> ���ɵ��</td>
																</tr>
																<tr>
																	<td colspan="3" style="height:10px;"></td>
																</tr>
																<tr>
																	<td style="width:100px;">�ǸŸ�����</td>
																	<td colspan="3">
																	<? for($i=1;$i<=5;$i++){ $addclass = ($venderinfo['starmark']>= $i)?' active':''; ?><div class="starmark <?=$addclass?>">��</div><? } ?></td>
																</tr>
															</table>
														<!--
															<a href="javascript:custRegistMinishop();"><img src="/images/common/btn_vender_addstor.gif" border="0" alt="�ܰ������" /></a>															-->
														</td>
													</tr>
												</table>
												<table border="0" cellpadding="0" cellspacing="0" width="100%" class="venderInfoTable prDetailContents" style="border:1px solid #ededed;padding:10px;border-top:0px;">
													<colgroup>
														<col width="30%">
														<col width="40%">
														<col width="30%">
													</colgroup>
													<tr>
														<td><span style="font-weight:600;">�� ��ȣ/��ǥ��&nbsp;&nbsp;:&nbsp;&nbsp;</span> <?=$venderinfo['com_name']?> / <?=$venderinfo['com_owner']?></td>
														<td><span style="font-weight:600;">�� ����������&nbsp;&nbsp;:&nbsp;&nbsp;</span> (<?=substr($venderinfo['com_post'],0,3)?>-<?=substr($venderinfo['com_post'],3,3)?>) <?=$venderinfo['com_addr']." ".$venderinfo['com_biz']?></td>
														<td></td>
													</tr>
													<tr>
														<td><span style="font-weight:600;">�� ����ڵ�Ϲ�ȣ&nbsp;&nbsp;:&nbsp;&nbsp;</span> <?=substr($venderinfo['com_num'],0,3)?>-<?=substr($venderinfo['com_num'],3,2)?>-<?=substr($venderinfo['com_num'],5,5)?></td>
														<td><span style="font-weight:600;">�� ����Ǹž��Ű��ȣ&nbsp;&nbsp;:&nbsp;&nbsp;</span> <?=$venderinfo['ec_num']?></td>
														<td><span style="font-weight:600;">�� ����ó&nbsp;&nbsp;:&nbsp;&nbsp;</span> <?=$venderinfo['com_tel']?> / <?=$venderinfo['p_email']?></td>
													</tr>
												</table>
											</td>
										</tr>
										<? }?>
									</table>
								</div>

							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>

							<?
								//���û�ǰ (������ ����)
								if($_data->coll_loc=="3") {
									echo "	<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
									echo "	<tr>\n";
									echo "		<td>".$collection_body."</td>\n";
									echo "	</tr>\n";
									echo "	</table>\n";
								}
							?>

				</td>
			</tr>
			<!-- ������ ������ ��� -->
			<?
				if( nameTechUse($_pdata->vender) ) {
				$v_info = mysql_fetch_assoc ( mysql_query( "SELECT * FROM `tblvenderinfo` WHERE `vender`=".$_pdata->vender." LIMIT 1;" ,get_db_conn()) );
			?>
			<tr>
				<td>
					<table border="0" cellpadding="0" cellspacing="0" width="100%" style="border:1px solid #ededed; margin-top:20px;" class="prDetailContents">
						<tr>
							<td style="width:130px; text-align:center; background:#f9f9f9;"><img src="<?=$com_image_url.$v_info['com_image']?>" width="100" /></td>
							<td style="padding:10px 15px;">
								<div style="height:30px; border-bottom:1px solid #ededed;">
									<div style="float:left; height:27px; line-height:27px;">
										<?=$v_info['com_name']?>&nbsp;&nbsp;<span style="color:#dddddd;">|</span>&nbsp;&nbsp;��ǥ : <?=$v_info['com_owner']?>
										<? if( $v_info['class'] > 0 ){ ?>
										&nbsp;&nbsp;<span style="color:#dddddd;">|</span>&nbsp;&nbsp;��� : <?=$classList[$v_info['class']]?>
										<? } ?>
									</div>
									<div style="float:right; margin:0px; padding:0px;">
										<a href="javascript:GoMinishop('../minishop.php?storeid=<?=$v_info['id']?>')"><img src="/images/common/btn_vender_allpr.gif" border="0" alt="��ü��ǰ����" /></a>
										<a href="javascript:custRegistMinishop();"><img src="/images/common/btn_vender_addstor.gif" border="0" alt="�ܰ������" /></a>
									</div>
								</div>

								<div>
									<table border="0" cellpadding="0" cellspacing="0" width="100%" class="venderInfoTbl">
										<caption>�Ǹ��� ����</caption>
										<tr>
											<th>����ڹ�ȣ</th>
											<td>: <?=$v_info['com_num']?></td>
											<th>����Ǹž��Ű��ȣ</th>
											<td>: <?=$v_info['ec_num']?></td>
										</tr>
										<tr>
											<th>����ó</th>
											<td>: <?=$v_info['com_tel']?></td>
											<th>E-mail</th>
											<td>: <?=$v_info['p_email']?></td>
										</tr>
										<tr>
											<th>����������</th>
											<td>: <?=$v_info['com_addr']?></td>
											<th>����ڱ���</th>
											<td>: <?=$v_info['com_type']?></td>
										</tr>
									</table>
								</div>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<?
				}
			?>

			<!-- ��ǰ�� ���� �̺�Ʈ ����(��ǰ ������ �ٷ� �Ʒ�) -->
			<? if($detailimg_eventloc=="3"){ ?>
			<tr><td height="100"></td></tr>
			<tr>
				<td><?=$detailimg_body?></td>
			</tr>
			<? } ?>

			<?
				//���û�ǰ (������ �ϴ�)
				if($_data->coll_loc=="2") {
					echo "<tr><td height=\"100\"></td></tr>\n";
					echo "<tr>\n";
					echo "	<td><a name=\"2\"></a>\n";
					echo "	<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" class=\"prDetailTab\">\n";
					echo "	<tr>\n";
					echo "		<td class=\"prDetailTabOff2\"><a href=\"#1\">��ǰ������<!--<img src=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_detailtitle1r.gif\" border=\"0\">--></td>\n";
					echo "	<td class=\"prDetailTabOn\"><a href=\"#2\">���û�ǰ<!--<img src=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_detailtitle2.gif\" border=\"0\">--></a></td>\n";
//					if($_data->coll_loc != '0' && strlen($collection_list) > 0) {
//						echo "	<td class=\"prDetailTabOn\"><a href=\"#2\">���û�ǰ<!--<img src=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_detailtitle2.gif\" border=\"0\">--></a></td>\n";
//					}

					echo "		<td class=\"prDetailTabOff2\"><a href=\"#3\">���/AS/ȯ�Ҿȳ�<!--<img src=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_detailtitle3r.gif\" border=\"0\">--></a></td>\n";
					echo "		<td class=\"prDetailTabOff2\"><a href=\"#4\">����ı� (".$counttotal.")<!--<img src=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_detailtitle4r.gif\" border=\"0\">--></a></td>\n";
					echo "		<td class=\"prDetailTabOff2\"><a href=\"#5\">��ǰQ&A (".$qnacount.")<!--<img src=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_detailtitle5r.gif\" border=\"0\">--></a></td>\n";
//					if($_data->sns_ok == "Y" && $_pdata->sns_state == "Y"){
//						echo "		<td class=\"prDetailTabOff2\"><a href=\"#6\">SNS �ҹ����� (".$product_SNS_Count.")<!--<img src=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_detailtitle6r.gif\" border=\"0\">--></a></td>\n";
//					}
	//				if($_data->sns_ok == "Y" && $_pdata->gonggu_product == "Y"){
	//					echo "		<td class=\"prDetailTabOff2\"><a href=\"#7\">�������Ž�û(".$product_Gonggu_Count.")<!--<img src=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_detailtitle7r.gif\" border=\"0\">--></a></td>\n";
	//				}
					//echo "		<td width=\"100%\" background=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_detailtitlebg.gif\"></td>\n";
					//echo "		<td class=\"prDetailTabNull\">&nbsp;</td>\n";
					echo "	</tr>\n";
					echo "	</table>\n";
					echo "	</td>\n";
					echo "</tr>\n";
					echo "<tr><td height=\"10\"></td></tr>\n";
					echo "<tr>\n";
					echo "	<td>".$collection_body."</td>\n";
					echo "</tr>\n";
				}
			?>

			<!-- �����ı� -->
			<?if($_data->review_type!="N") {?>
			<tr><td height="100"></td></tr>
			<tr>
				<td valign="top">
					<a name="4"></a>
						<table cellpadding="0" cellspacing="0" width="100%" class="prDetailTab">
							<tr>
								<td class="prDetailTabOff2"><a href="#1">��ǰ������</a></td>
								<td class="prDetailTabOff2"><a href="#2">���û�ǰ</a></td>
<!--								<? if($_data->coll_loc != '0' && strlen($collection_list) > 0) { ?><td class="prDetailTabOff2"><a href="#2">���û�ǰ</a></td><? } ?>-->
								<td class="prDetailTabOff2"><a href="#3">���/AS/ȯ�Ҿȳ�</a></td>
								<td class="prDetailTabOn"><a href="#4">����ı� (<?=$counttotal?>)</a></td>
								<td class="prDetailTabOff2"><a href="#5">��ǰQ&A (<?=$qnacount?>)</a></td>
<!--								<td class="prDetailTabOff2"><a href="#6">SNS �ҹ����� (<?=$product_SNS_Count?>)</a></td>
								<? if($_data->sns_ok == "Y" && $_pdata->gonggu_product == "Y"){?><td class="prDetailTabOff"><a href="#7">�������Ž�û(<?=$product_Gonggu_Count?>)</a></td><?}?>-->
							</tr>
						</table>
				</td>
			</tr>

			<tr>
				<td style="padding:30px 0px 10px 0px;;">
					<table border="0" cellpadding="0" cellspacing="0" width="100%" class="reviewPoint prDetailContents">
						<tr>
							<th>�����ۼ��� �������� �帳�ϴ�.<br>ù��° 500��, ���� ���� 500��, ����Ʈ 1,000�� �߰� ����</th>
							<td>
								<h4><span><?=$avertotalscore?></span>&nbsp;/&nbsp;���</h4>
								<div><?=$reviewstarcount?></div>
							</td>
							<td>
								<h4><span><?=$averquality?></span>&nbsp;/&nbsp;ǰ��</h4>
								<div><?=$qualitystarcount?></div>
							</td>
							<td>
								<h4><span><?=$averprice?></span>&nbsp;/&nbsp;����</h4>
								<div><?=$pricestarcount?></div>
							</td>
							<td>
								<h4><span><?=$averdelitime?></span>&nbsp;/&nbsp;���</h4>
								<div><?=$delitimestarcount?></div>
							</td>
							<td>
								<h4><span><?=$averrecommend?></span>&nbsp;/&nbsp;��õ</h4>
								<div><?=$recommendstarcount?></div>
							</td>
						</tr>
					</table>
				</td>
			</tr>
<!--
			<tr>
				<td style="padding:35px 45px 0px 30px;">
					<div style="float:left;"><img src="/data/design/img/detail/review_text.gif" alt="" /></div>
					<div style="float:right;"><img src="/data/design/img/detail/review_image.gif" alt="" /></div>
				</td>
			</tr>
-->
			<tr>
				<td>
					<div style="padding:20px 0px;" class="prDetailContents"><? INCLUDE ($Dir.FrontDir."prreview.php"); ?></div>
				</td>
			</tr>
			<?}?>

			<!-- ��ǰQ/A -->
			<? if(strlen($qnasetup->board)>0){ ?>
			<tr><td height="100"></td></tr>
			<tr>
				<td valign="top">
					<a name="5"></a>
						<table cellpadding="0" cellspacing="0" width="100%" class="prDetailTab">
							<tr>
								<td class="prDetailTabOff2"><a href="#1">��ǰ������</a></td>
								<td class="prDetailTabOff2"><a href="#2">���û�ǰ</a></td>
<!--								<? if($_data->coll_loc != '0' && strlen($collection_list) > 0) { ?><td class="prDetailTabOff2"><a href="#2">���û�ǰ</a></td><? } ?>-->
								<td class="prDetailTabOff2"><a href="#3">���/AS/ȯ�Ҿȳ�</a></td>
								<td class="prDetailTabOff2"><a href="#4">����ı� (<?=$counttotal?>)</a></td>
								<td class="prDetailTabOn"><a href="#5">��ǰQ&A (<?=$qnacount?>)</a></td>
<!--								<td class="prDetailTabOff2"><a href="#6">SNS �ҹ����� (<?=$product_SNS_Count?>)</a></td>
								<? if($_data->sns_ok == "Y" && $_pdata->gonggu_product == "Y"){?><td class="prDetailTabOff"><a href="#7">�������Ž�û(<?=$product_Gonggu_Count?>)</a></td><?}?>-->
							</tr>
						</table>
				</td>
			</tr>
			<tr>
				<td>
					<div style="padding:20px 0px;" class="prDetailContents"><? INCLUDE ($Dir.FrontDir."prqna.php"); ?></div>
				</td>
			</tr>
			<?}?>

			<!-- ���/��ȯ/ȯ������ -->
			<tr><td height="100"></td></tr>
			<tr>
				<td valign="top">
					<a name="3"></a>
						<table cellpadding="0" cellspacing="0" width="100%" class="prDetailTab">
							<tr>
								<td class="prDetailTabOff2"><a href="#1">��ǰ������</a></td>
								<td class="prDetailTabOff2"><a href="#2">���û�ǰ</a></td>
<!--								<? if($_data->coll_loc != '0' && strlen($collection_list) > 0) { ?><td class="prDetailTabOff2"><a href="#2">���û�ǰ</a></td><? } ?>-->
								<td class="prDetailTabOn"><a href="#3">���/AS/ȯ�Ҿȳ�</a></td>
								<td class="prDetailTabOff2"><a href="#4">����ı� (<?=$counttotal?>)</a></td>
								<td class="prDetailTabOff2"><a href="#5">��ǰQ&A (<?=$qnacount?>)</a></td>
<!--								<td class="prDetailTabOff2"><a href="#6">SNS �ҹ����� (<?=$product_SNS_Count?>)</a></td>
								<? if($_data->sns_ok == "Y" && $_pdata->gonggu_product == "Y"){?><td class="prDetailTabOff"><a href="#7">�������Ž�û(<?=$product_Gonggu_Count?>)</a></td><?}?>-->
							</tr>
						</table>
						<? if(strlen($deli_info)>0) { 
						echo "<div class=\"prDetailContents\">".$deli_info."</div>";						
						?>
				</td>
			</tr>
			<? } ?>

			<!-- SNS �ҹ����� 
			<? if($_data->sns_ok == "Y" && $_pdata->sns_state == "Y"){?>
			<tr><td height="100"></td></tr>
			<tr>
				<td valign="top"><a name="6"></a>
					<table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
						<tr>
							<td>
								<table cellpadding="0" cellspacing="0" width="100%" class="prDetailTab">
									<tr>
										<td class="prDetailTabOff2"><a href="#1">��ǰ������</a></td>
										<td class="prDetailTabOff2"><a href="#2">���û�ǰ</a></td>
//										<? if($_data->coll_loc != '0' && strlen($collection_list) > 0) { ?><td class="prDetailTabOff2"><a href="#2">���û�ǰ</a></td><? } ?>                 //
										<td class="prDetailTabOff2"><a href="#3">���/AS/ȯ�Ҿȳ�</a></td>
										<td class="prDetailTabOff2"><a href="#4">����ı� (<?=$counttotal?>)</a></td>
										<td class="prDetailTabOff2"><a href="#5">��ǰQ&A (<?=$qnacount?>)</a></td>
//									<td class="prDetailTabOn"><a href="#6">SNS �ҹ����� (<?=$product_SNS_Count?>)</a></td>                   //
										<? if($_data->sns_ok == "Y" && $_pdata->gonggu_product == "Y"){?><td class="prDetailTabOff"><a href="#7">�������Ž�û(<?=$product_Gonggu_Count?>)</a></td><?}?>
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
			<?}?>-->

			<!-- �������� -->
			<? if($_data->sns_ok == "Y" && $_pdata->gonggu_product == "Y"){ ?>
			<tr><td height="100"></td></tr>
			<tr>
				<td valign="top"><a name="7"></a>
					<table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
						<tr>
							<td>
								<table cellpadding="0" cellspacing="0" width="100%" class="prDetailTab">
									<tr>
										<td class="prDetailTabOff2"><a href="#1">��ǰ������</a></td>
										<td class="prDetailTabOff2"><a href="#2">���û�ǰ</a></td>
<!--										<? if($_data->coll_loc != '0' && strlen($collection_list) > 0) { ?><td class="prDetailTabOff2"><a href="#2">���û�ǰ</a></td><? } ?>-->
										<td class="prDetailTabOff2"><a href="#3">���/AS/ȯ�Ҿȳ�</a></td>
										<td class="prDetailTabOff2"><a href="#4">����ı� (<?=$counttotal?>)</a></td>
										<td class="prDetailTabOff2"><a href="#5">��ǰQ&A (<?=$qnacount?>)</a></td>
<!--										<? if($_data->sns_ok == "Y" && $_pdata->sns_state == "Y"){?><td class="prDetailTabOff2"><a href="#6">SNS �ҹ����� (<?=$product_SNS_Count?>)</a></td><?}?>
										<td class="prDetailTabOn"><a href="#7">�������Ž�û(<?=$product_Gonggu_Count?>)</a></td>-->
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
			<?
				}
			}
			?>
			<tr><td height="20"></td></tr>
			</table>
		</td>
	</tr>
</table>

<script language="javascript" type="text/javascript">

$j(function(){
    $j( "#checkRequest" ).dialog({
	  autoOpen:false,
      modal: true,
      width: 500,
      height: 140,
      buttons: {
      
      }
    });
});
</script>

<div id="checkRequest" title="���෻Ż" style="display:none;">
	<div style="line-height:150%" id="checkReserv">
		�Ϻ� ��ǰ ��Ż�� �Ұ����� ���, ���ڷ� �ȳ��Ǹ� �ݾ��� ȯ�ҵ˴ϴ�.
		<!--
		��ǰ���� �� �뿩��ü��Ȳ�� ���� ������ �Ұ����� ���� �ֽ��ϴ�.<br />
		�̶�, ����Ұ� �� �����ڷ� �ȳ��Ǹ� �ݾ��� ȯ��ó�� �˴ϴ�.<br />
		����, üũ �� �������� �� 12�ð� �̳� �Ա����� ���� ��� �ڵ��ֹ� ��ҵ˴ϴ�.<br/>
		�̿� �����Ͻø� Ȯ�ι�ư�� �����ֽʽÿ�.-->
		<div style="text-align:center; margin-top:15px;">
			<img src="/upload/img/btn_confirm.gif" alt="����" onclick="javascript:CheckForm('ordernow','<?=$opti?>')" style="cursor:pointer" />
			<img src="/upload/img/btn_cancel.gif" alt="���" onclick="javascript:$j('#checkRequest').dialog('close');" style="cursor:pointer" />
		</div>
	</div>
</div>