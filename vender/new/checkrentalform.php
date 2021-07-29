<?
$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/ext/func.php");
include_once($Dir."lib/ext/rent.php");
include_once($Dir."lib/ext/product_func.php");
include_once($Dir."lib/venderlib.php");
include_once("../access.php");
/*
_pr($_venderdata);
_pr($_VenderInfo);*/
ob_start();
if($_REQUEST['act'] == 'locallist'){
	// 대여 출고지 정보 리스트	
	$value = array("display"=>1,'vender'=>($_venderdata->vender)); // 노출 만 표시
	$localList = rentLocalList( $value );
	if(!isset($localList[$rentProduct['location']])) $rentProduct['location']= 0;
	?>
	<select name="location" style="float:left">
			<option value="0">--소재지 선택--</option>
		<?
			// 대여 출고지 정보 리스트	
			$value = array("display"=>1,'vender'=>($_venderdata->vender)); // 노출 만 표시
			$localList = rentLocalList( $value );
			if(!isset($localList[$rentProduct['location']])) $rentProduct['location']= 0;
			
			foreach ( $localList as $k=>$v ) { 
				$sel = ($rentProduct['location'] == $v['location'])?'checked="checked"':'';
			?>
			<option value="<?=$v['location']?>" <?=$sel?>><?=$v['title']?></option>
		<? } ?>
		</select>
<?
	$ret['cont'] = ob_get_clean();
}else if($_REQUEST['act'] == 'groupDiscount'){
	$code = str_pad($_REQUEST['code'],12,'0');
	$groupdiscount = getGroupDiscounts($code);					
	$ret = array('err'=>'ok','items'=>array());
	
	$sql = "select reseller_reserve from tblproductcode where concat(codeA,codeB,codeC,codeD) ='".$code."' limit 1";
	if(false !== $rrres = mysql_query($sql,get_db_conn())){
				if(mysql_num_rows($rrres)) $ret['reseller_reserve'] = (mysql_result($rrres,0,0)*100).'%';
	}
	
	if(_array($groupdiscount)){
		foreach($groupdiscount as $gdiscount){
			array_push($ret['items'],array('group_code'=>$gdiscount['group_code'],'txt'=>($gdiscount['discount'] <1)?($gdiscount['discount']*100).'%':$gdiscount['discount']));
		}
	}
}else{
	$code = str_pad($_REQUEST['code'],12,'0');
	$categoryRentInfo = categoryRentInfo($code);					

	if(_array($categoryRentInfo)){	
		$goodsTypeSel[1] = "checked";
		$commi = rentCommitionByCategory($code,$_venderdata->vender);
	
?>


<tr class="rentalItemArea1">
	<th><font color="FF4800">*</font>상품구분 및<br /> 관리 형태</th>
	<td colspan="3">		
		<div style="margin-bottom:5px;">
			<input type="radio" name="istrust" value="1" <?=($rentProduct['istrust']!='-1' && $rentProduct['istrust']!='0')?'checked':''?>  onclick="javascript:toggleTrust()"  />셀프관리 (수수료  <?=number_format($commi['self'])?>%)
			<input type="radio" name="istrust" value="0" style="margin-left:8px;" <?=($rentProduct['istrust']=='0')?'checked':''?>  onclick="javascript:toggleTrust()" />위탁관리 (위탁 수수료 <input type="text" name="maincommi" id="maincommi" style="width:60px;text-align:right;" value="<?=number_format($commi['main'])?>">%)
			<input type="hidden" name="orgcommi" id="orgcommi" value="<?=$commi['main']?>">
			<select name="trust_vender" style="width:130px" id=" trust_sel" onchange="javascript:chn_mainCommi()">
				<option value="">위탁업체 선택하기</option>
				<?
				$sql = "SELECT * FROM tbltrustagree WHERE (take_vender='".$_VenderInfo->getVidx()."' OR give_vender='".$_VenderInfo->getVidx()."') AND approve='Y'";
				$result=mysql_query($sql,get_db_conn());
				while($row=mysql_fetch_object($result)) {

					if($_VenderInfo->getVidx()==$row->take_vender){//해당업체가 받은업체인 경우
						$trust_vender = $row->give_vender;
						$trust_vender_val = $row->give_vender."::take";
						$trust_approve_default = "Y";
						$trust_gubun = "(받은위탁) ";
					}else{
						$trust_vender = $row->take_vender;
						$trust_vender_val = $row->take_vender."::give";
						$trust_approve_default = "N";
						$trust_gubun = "(보낸위탁) ";
					}

					$sql = "SELECT * FROM tblvenderinfo WHERE vender='".$trust_vender."' ";
					$tRes=mysql_query($sql,get_db_conn());
					$tData=mysql_fetch_object($tRes);
				?>
				<option value="<?=$trust_vender_val?>"><?=$trust_gubun.$tData->com_name?></option>
				<?
				}
				?>
			</select>
			<input type="hidden" name="trust_approve" value="<?=$trust_approve_default?>">
			<!--
			<? if($rentProduct['istrust']=='0'){ ?>
			<input type="radio" name="istrust" value="0" style="margin-left:8px;" <?=($rentProduct['istrust']=='0')?'checked':''?>  onclick="javascript:toggleTrust()" />위탁관리 (수수료  <?=number_format($commi['main'])?>%)
			<? }else{ ?>
			<input type="radio" name="istrust" value="-1" style="margin-left:8px;" <?=($rentProduct['istrust']=='-1')?'checked':''?> onclick="javascript:toggleTrust()" />위탁관리 요청 (수수료  <?=number_format($commi['main'])?>%)
			<? } ?>
			-->
			<br><span class="notice_blue">* 총관리자가 설정한 카테고리별 수수료 설정과 감면수수료가 반영되어 적용됩니다.</font>
		</div>
		<div id="goodsTypeLocalDiv">	
			<table border="0" width="100%" cellpadding="0" cellspacing="0"  class="tableBaseSe" style="border-top:1px solid #ededed;">
				<tr>
					<th style="width:80px;"><img width="8" height="11" src="images/icon_point2.gif" border="0"/>상품 타입</th>
					<td style="width:60px;text-align:left;padding-left:10px;">
						<input type=radio id="itemType1" name="itemType" value="product" checked="checked"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=itemType1>상품</label><br>
						<input type=radio id="itemType2" name="itemType" value="location" ><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=itemType2>장소</label>
					</td>
					<? if(!_empty($categoryRentInfo['pricetype'])){ ?>
					<th style="width:80px;"><img width="8" height="11" src="images/icon_point2.gif" border="0"/>가격구분</th>
					<td style="text-align:left;padding:0px 0px;">
						<script language="javascript" type="text/javascript">
						/*
						$j(function(){
							$j('#price1').on('keyup','.autoSolv',function(e){
								var ptr = $j(this).parent();
								var ptr2 = $j('#rentOptTable').parent().parent();
								del = $j(ptr).find('input[name^=halfday_percent]');
								cel = $j(ptr2).find('input[name^=nomalPrice]');
								sel = $j(ptr).find('input[name^=halfday_per_price]');

								autoPrice(cel,del,sel);
							});
							$j('#price2').on('keyup','.autoSolv',function(e){
								var ptr = $j(this).parent();
								var ptr2 = $j('#rentOptTable').parent().parent();
								del = $j(ptr).find('input[name^=time_percent]');
								cel = $j(ptr2).find('input[name^=nomalPrice]');
								sel = $j(ptr).find('input[name^=time_per_price]');

								autoPrice(cel,del,sel);
							});

							$j('#price1').on('keyup','.autoPerSolv',function(e){
								var ptr = $j(this).parent();
								var ptr2 = $j('#rentOptTable').parent().parent();
								del = $j(ptr).find('input[name^=halfday_percent]');
								cel = $j(ptr2).find('input[name^=nomalPrice]');
								sel = $j(ptr).find('input[name^=halfday_per_price]');

								autoPercent(cel,del,sel);
							});
							$j('#price2').on('keyup','.autoPerSolv',function(e){
								var ptr = $j(this).parent();
								var ptr2 = $j('#rentOptTable').parent().parent();
								del = $j(ptr).find('input[name^=time_percent]');
								cel = $j(ptr2).find('input[name^=nomalPrice]');
								sel = $j(ptr).find('input[name^=time_per_price]');

								autoPercent(cel,del,sel);
							});
						});
						*/
						
						function autoPrice(cel,del,sel){
							if(cel && del && sel){
								var customerp = parseInt($j(cel).val());
								var discountp = parseInt($j(del).val());
								var sellp = 0;
								if(!isNaN(customerp) && customerp >= 0){
									sellp = customerp;
									if(!isNaN(discountp) && discountp > 0 && discountp <=100){
										$j(del).val(discountp);
										sellp = parseInt(Math.floor(customerp*discountp/100)/100)*100;
									}else{
										$j(del).val('0');
									}
								}
								$j(sel).val(sellp);
							}else{
							}
						}

						function autoPercent(cel,del,sel){
							if(cel && del && sel){
								var customerp = parseInt($j(cel).val());
								var sellprice = parseInt($j(sel).val());
								var discper=0;
								if(!isNaN(customerp) && customerp >= 0){
									discper = $j(del).val();
									if(!isNaN(sellprice) && sellprice > 0){
										$j(sel).val(sellprice);
										discper = parseInt(Math.round(100*sellprice/customerp));
									}else{
										$j(sel).val('0');
									}
								}
								$j(del).val(discper);
							}else{
							}
						}

						function autoPercent_basic(cel,del,sel){
							if(cel && del && sel){
								var customerp = parseInt($j(cel).val());
								var sellprice = parseInt($j(sel).val());
								var discper=0;
								if(!isNaN(customerp) && customerp >= 0){
									discper = $j(del).val();
									if(!isNaN(sellprice) && sellprice > 0){
										$j(sel).val(sellprice);
										discper = 100-parseInt(Math.round(100*sellprice/customerp));
									}else{
										$j(sel).val('0');
									}
								}
								$j(del).val(discper);
							}else{
							}
						}
						
						function chPriceType(){ 
							var idx = $j("#pricetype > option:selected").val(); 
							if(idx=="day"){
								$j('#day_div').show();
								$j('#time_div').hide();
								$j('#checkout_div').hide();
								$j('#period_div').hide();
								$j('#long_div').hide();
								$j('#rent_time').show();

								$j('#rentOptTable').show();
								$j('#rentOptTable2').hide();
								$j('.rentalItemArea5').show();
								$j('.rentalItemArea7').hide();
								$j('.rentalItemArea8').hide();
								$j('.rentalItemArea9').hide();
							}else if(idx=="time"){								
								$j('#day_div').hide();
								$j('#time_div').show();
								$j('#checkout_div').hide();
								$j('#period_div').hide();
								$j('#long_div').hide();
								$j('#rent_time').show();

								$j('#rentOptTable').show();
								$j('#rentOptTable2').hide();
								$j('.rentalItemArea5').show();
								$j('.rentalItemArea7').hide();
								$j('.rentalItemArea8').hide();
								$j('.rentalItemArea9').hide();
							}else if(idx=="checkout"){
								$j('#day_div').hide();
								$j('#time_div').hide();
								$j('#checkout_div').show();
								$j('#period_div').hide();
								$j('#long_div').hide();
								$j('#rent_time').hide();
								$j('#checkin_time').val($j('#rent_stime').val());
								$j('#checkout_time').val($j('#rent_etime').val());

								$j('#rentOptTable').show();
								$j('#rentOptTable2').hide();
								$j('.rentalItemArea5').show();
								$j('.rentalItemArea7').hide();
								$j('.rentalItemArea8').hide();
								$j('.rentalItemArea9').hide();
							}else if(idx=="period"){//단기기간
								$j('#day_div').hide();
								$j('#time_div').hide();
								$j('#checkout_div').hide();
								$j('#period_div').show();
								$j('#long_div').hide();
								$j('#rent_time').show();

								$j('#rentOptTable').show();
								$j('#rentOptTable2').hide();
								$j('.rentalItemArea5').hide();
								$j('.rentalItemArea7').show();
								$j('.rentalItemArea8').hide();
								$j('.rentalItemArea9').hide();
							}else if(idx=="long"){//장기기간
								$j('#day_div').hide();
								$j('#time_div').hide();
								$j('#checkout_div').hide();
								$j('#period_div').hide();
								$j('#long_div').show();
								$j('#rent_time').show();
								
								$j('#rentOptTable').hide();
								$j('#rentOptTable2').show();
								$j('.rentalItemArea5').show();
								$j('.rentalItemArea7').show();
								$j('.rentalItemArea8').show();
								$j('.rentalItemArea9').show();
							}
							$j('.rentalItemArea10').show();
						}
/*
						function halfdayCheck(val){			
							if(val=="Y"){
								html = '<div>당일 12시간 요금: <br>24시간 요금의 ';
								html += '<input type="text" name="halfday_percent" class="autoSolv" size="3" maxlength="2">%';
								html += '<input type="text" name="halfday_per_price" class="autoSolv" size="5">원</div>';
								$j('#price1').html(html);
							}else{
								html = '';
								$j('#price1').html(html);
							}
							
						}

						function onedayexCheck(val){			
							if(val=="time"){
								html = '<div>추가 1시간 요금: <br>';
								html+= '24시간 요금의 <input type="text" name="time_percent" class="autoSolv" size="3" maxlength="2">%';
								html+= '<input type="text" name="time_per_price" class="autoPerSolv" size="5">원';
								html+='</div>';
								$j('#price2').html(html);
							}else if(val=="half"){
								html = '<div>추가 12시간 요금: <br>';
								html+= '24시간 요금의 <input type="text" name="time_percent" class="autoSolv" size="3" maxlength="2">%';
								html+= '<input type="text" name="time_per_price" class="autoPerSolv" size="5">원';
								html+='</div>';
								$j('#price2').html(html);
							}else{
								html = '';
								$j('#price2').html(html);
							}
						}
						*/
						</script>
						<?
						//gura
						$pridx = $pridx? $pridx : 0;
						$sql = "SELECT * FROM vender_rent ";
						$sql.= "WHERE vender='".$_venderdata->vender."' and pridx='".$pridx."'";
						$result=mysql_query($sql,get_db_conn());
						$_ptdata=mysql_fetch_object($result);
						mysql_free_result($result);

						//신규등록시 입점업체고유방식선택시 or 상품수정시
						if(($_venderdata->pricetype=="1" && $pridx==0) || ($_ptdata->pricetype!="" && $pridx!=0)){
							$categoryRentInfo['rent_stime'] = $_ptdata->rent_stime;
							$categoryRentInfo['rent_etime'] = $_ptdata->rent_etime;
							$categoryRentInfo['pricetype'] = $_ptdata->pricetype;
							$categoryRentInfo['halfday'] = $_ptdata->halfday;
							$categoryRentInfo['halfday_percent'] = $_ptdata->halfday_percent;
							$categoryRentInfo['oneday_ex'] = $_ptdata->oneday_ex;
							$categoryRentInfo['time_percent'] = $_ptdata->time_percent;
							$categoryRentInfo['base_period'] = $_ptdata->base_period;
							$categoryRentInfo['ownership'] = $_ptdata->ownership? $_ptdata->ownership : "mv";
							$categoryRentInfo['base_time'] = $_ptdata->base_time;
							$categoryRentInfo['base_price'] = $_ptdata->base_price;
							$categoryRentInfo['timeover_price'] = $_ptdata->timeover_price;
							$categoryRentInfo['checkin_time'] = $_ptdata->checkin_time;
							$categoryRentInfo['checkout_time'] = $_ptdata->checkout_time;
						}
						?>
						<select name="pricetype" id="pricetype" onchange="javascript:chPriceType()" style="width:120px;margin:5px;">
							<option value="day" <? if($categoryRentInfo['pricetype'] == 'day') echo ' selected="selected"'; ?> >24시간제</option>
							<option value="time" <? if($categoryRentInfo['pricetype'] == 'time') echo ' selected="selected"'; ?>>1시간제</option>
							<option value="checkout" <? if($categoryRentInfo['pricetype'] == 'checkout') echo ' selected="selected"'; ?>>일일제(숙박제)</option>
							<option value="period" <? if($categoryRentInfo['pricetype'] == 'period') echo ' selected="selected"'; ?> >단기기간제</option>
							<option value="long" <? if($categoryRentInfo['pricetype'] == 'long') echo ' selected="selected"'; ?> >장기기간제(약정)</option>
						</select>&nbsp;&nbsp;
						<span id="rent_time" style="display:<?=($categoryRentInfo['pricetype'] == 'checkout')? "none":"display"; ?>">
							시작: <input type="text" name="rent_stime" id="rent_stime" size="3" maxlength="2" value="<?=$categoryRentInfo['rent_stime']?>">시 ~
							종료: <input type="text" name="rent_etime" id="rent_etime" size="3" maxlength="2" value="<?=$categoryRentInfo['rent_etime']?>">시 
						</span>
						
						<? if($categoryRentInfo['pricetype'] == 'day') $display = ""; else $display = "none"; ?>
						<table id="day_div" cellpadding="0" cellspacing="0" style="display:<?=$display?>;width:100%;padding:0px;">
							<tr>
								<th style="width:120px;">당일 12시간 대여허용</th>
								<td style="width:85px;text-align:left">
									<input type=radio name=halfday value="Y" <?if($categoryRentInfo['halfday']=="Y")echo"checked";?>>예<br>
									<input type=radio name=halfday value="N" <?if($categoryRentInfo['halfday']=="N")echo"checked";?>>아니오
								</td>
								<!--td id="price1">
									<?
									/*
									if($categoryRentInfo['halfday']=="Y"){
										echo '<div>당일 12시간 요금: <br>';
										echo '24시간 요금의 <input type="text" name="halfday_percent" class="autoSolv" size="3" maxlength="2" value="'.$categoryRentInfo['halfday_percent'].'">%';
										echo '<input type="text" name="halfday_per_price" size="5" class="autoPerSolv" value="'.$categoryRentInfo['halfday_per_price'].'">원</div>';
									}
									*/
									?>
								</td-->
							</tr>
							<tr>
								<th>1일 초과시 과금기준</th>
								<td style="width:85px;text-align:left">
									<input type=radio name=oneday_ex value="day" <?if($categoryRentInfo['oneday_ex']=="day")echo"checked";?>>1일 단위<br>
									<input type=radio name=oneday_ex value="half" <?if($categoryRentInfo['oneday_ex']=="half")echo"checked";?>>12시간 단위<br>
									<input type=radio name=oneday_ex value="time" <?if($categoryRentInfo['oneday_ex']=="time")echo"checked";?>>1시간 단위
								</td>
								<!--td id="price2">
									<?
									/*
									if($categoryRentInfo['oneday_ex']=="time"){
										echo '<div>추가 1시간 요금: <br>';
										echo '24시간 요금의 <input type="text" name="time_percent" class="autoSolv" size="3" maxlength="2" value="'.$categoryRentInfo['time_percent'].'">%';
										echo '<input type="text" name="time_per_price" class="autoPerSolv" size="5" value="'.$categoryRentInfo['time_per_price'].'">원</div>';
									}else if($categoryRentInfo['oneday_ex']=="half"){
										echo '<div>추가 12시간 요금: <br>';
										echo '24시간 요금의 <input type="text" name="time_percent" class="autoSolv" size="3" maxlength="2" value="'.$categoryRentInfo['time_percent'].'">%';
										echo '<input type="text" name="time_per_price" class="autoPerSolv" size="5" value="'.$categoryRentInfo['time_per_price'].'">원</div>';
									}
									*/
									?>
								</td-->
							</tr>
						</table>
						<? if($categoryRentInfo['pricetype'] == 'time') $display = ""; else $display = "none"; ?>
						<table id="time_div" cellpadding="0" cellspacing="0" style="display:<?=$display?>;width:100%;padding:0px;">
							<tr>
								<th style="width:80px;">기본요금</th>
								<td class="norbl" style="padding-right:5px">
									최소시간 <select name="base_time" onchange="javascript:changePrice2();" style="width:60px">
										<? for($i=1;$i<=36;$i++){?>
										<option value="<?=$i?>" <? if($categoryRentInfo['base_time'] == $i) echo ' selected="selected"'; ?> ><?=$i?>시간</option>
										<? } ?>
									</select> <!--<input type="text" name="base_price" size="10" value="<?=$categoryRentInfo['base_price']?>" onkeyup="javascript:changePrice();">원-->
								</td>
							</tr>
							<!--tr>
								<th>초과 1시간당</th>
								<td style="text-align:right;padding-right:5px">
									<input type="text" name="timeover_price" size="10" value="<?=$categoryRentInfo['timeover_price']?>" readonly>원
								</td>
							</tr-->
						</table>
						<? if($categoryRentInfo['pricetype'] == 'checkout') $display = ""; else $display = "none"; ?>
						<table id="checkout_div" cellpadding="0" cellspacing="0" style="display:<?=$display?>;width:100%;padding:0px;">
							<tr>
								<th style="width:80px;">체크인</th>
								<td class="norbl" style="padding:5px;">
									<select name="checkin_time" style="width:50px">
										<? for($i=0;$i<=23;$i++){ ?>
										<option value="<?=sprintf('%02d',$i)?>" <? if($categoryRentInfo['checkin_time']==sprintf('%02d',$i)){echo "selected";}?>><?=sprintf('%02d',$i)?>시</option>
										<? } ?>
									</select>
								</td>
								<th style="width:80px;">체크아웃</th>
								<td class="norbl">
									<select name="checkout_time" style="width:50px">
										<? for($i=0;$i<=23;$i++){ ?>
										<option value="<?=sprintf('%02d',$i)?>" <? if($categoryRentInfo['checkout_time']==sprintf('%02d',$i)){echo "selected";}?>><?=sprintf('%02d',$i)?>시</option>
										<? } ?>
									</select>
								</td>
							</tr>
						</table>
						<? if($categoryRentInfo['pricetype'] == 'period') $display = ""; else $display = "none"; ?>
						<table id="period_div" cellpadding="0" cellspacing="0" style="display:<?=$display?>;width:100%;padding:0px;">
							<tr>
								<th style="width:100px;">기본대여일</th>
								<td class="norbl" style="padding:5px;">
									<input type="text" name="base_period" size="5" value="<?=$categoryRentInfo['base_period']?>" onkeyup="javascript:$j('#addLongrent_sday').val(parseInt($j('input[name=base_period]').val())+1);$j('#bp_text').text('*'+$j('input[name=base_period]').val()+'일은 '+(parseInt($j('input[name=base_period]').val())-1)+'박 '+$j('input[name=base_period]').val()+'일 입니다.')">일 
									&nbsp;&nbsp;<span id="bp_text">*3일은 2박 3일입니다.</span>
								</td>
							</tr>
						</table>
						<? if($categoryRentInfo['pricetype'] == 'long') $display = ""; else $display = "none"; ?>
						<table id="long_div" cellpadding="0" cellspacing="0" style="display:<?=$display?>;width:100%;padding:0px;">
							<tr>
								<th style="width:100px;">만기 후 소유권</th>
								<td class="norbl" style="padding:5px;">
									<input type=radio name="ownership" value="mv" <?if($categoryRentInfo['ownership']=="mv")echo"checked";?>>이전 
									<input type=radio name="ownership" value="re" <?if($categoryRentInfo['ownership']=="re")echo"checked";?>>반납
								</td>
							</tr>
						</table>

					</td>
					<? } ?>									
					<th style="width:80px;"><img width="8" height="11" src="images/icon_point2.gif" border="0"/>성수기사용</th>
					<td style="text-align:left;padding:0px 10px;">
						<?// echo ($categoryRentInfo['useseason'] == '1')?'성수기 사용':'사용안함';?>
						<?
						//gura
						if($pridx!=0){
							$categoryRentInfo['useseason'] = $_ptdata->useseason;
						}else if($_venderdata->season!="2" && $pridx==0){//입점업체고유방식선택시
							$categoryRentInfo['useseason'] = $_venderdata->season;
						}else{
							$categoryRentInfo['useseason'] = $categoryRentInfo['useseason'];
						}
						?>
						<input type=radio name=useseason value="0" <?if($categoryRentInfo['useseason']!="1")echo"checked";?> onclick="toggleSeasonList()">사용안함<br>
						<input type=radio name=useseason value="1" <?if($categoryRentInfo['useseason']=="1")echo"checked";?> onclick="toggleSeasonList()">(비)성수기 사용
						<script language="javascript" type="text/javascript">
						function toggleSeasonList(){
							var f = document.form1;
							var listdisp = false;
							for(i=0;i<f.useseason.length;i++){
								if(f.useseason[i].checked){
									if(f.useseason[i].value == '1') listdisp = true;
									break;
								}
							}
							document.getElementById('seasonListTbl').style.display = (listdisp)?'block':'none';
							if(listdisp){
								$j('.seasonList').show();
								$j('.itemSepTD').attr('rowspan',6);
							}else{
								$j('.seasonList').hide();
								$j('.itemSepTD').attr('rowspan',1);
							}
							toggleOptType();
						}
						</script>
						<? if($categoryRentInfo['useseason'] == '1') $display = ""; else $display = "none"; ?>
						<div id="seasonListTbl" style="font-weight:bold; padding:10px 0px;display:<?=$display?>"> 성수기/비성수기 설정은 상품 신규 등록 후 내 상품관리 에서 수정이 가능합니다.</div>
					</td>																
				</tr>
			</table>
		</div>
	</td>
</tr>
<tr class="rentalItemArea2">
	<Th><font color="FF4800">*</font>소재지</th>
	<td colspan="3">
	<?
	// 대여 출고지 정보 리스트	
	$value = array("display"=>1,'vender'=>($_venderdata->vender)); // 노출 만 표시
	$localList = rentLocalList( $value );
	if(!isset($localList[$rentProduct['location']])) $rentProduct['location']= 0;
	?>
	<div id="localListDiv">	
		<select name="location" style="float:left;width:120px">
			<option value="0">--소재지 선택--</option>
		<?
			// 대여 출고지 정보 리스트	
			$value = array("display"=>1,'vender'=>($_venderdata->vender)); // 노출 만 표시
			$localList = rentLocalList( $value );
			if(!isset($localList[$rentProduct['location']])) $rentProduct['location']= 0;
			
			foreach ( $localList as $k=>$v ) { 
				$sel = ($rentProduct['location'] == $v['location'])?'checked="checked"':'';
			?>
			<option value="<?=$v['location']?>" <?=$sel?>><?=$v['title']?></option>
		<? } ?>
		</select>			
	</div>
	<input type="button" value="출고지 관리" onclick="javascript:openLocalWin();" style="margin-left:5px;" />

		
	</td>
</tr>
<!--
<tr class="rentalItemArea3">
	<th>노출아이디선택</th>
	<td>
		<input type="radio" name="rentdispId" value="self" <?=($rentProduct['rentdispId'] != 'main')?'checked':''?> />본인
		<input type="radio" name="rentdispId" value="main" <?=($rentProduct['rentdispId'] == 'main')?'checked':''?> />잠깐본점
	</td>
	<th>미니홈노출</th>
	<td colspan="3">
		<input type="radio" name="rentdispminihome" value="self" <?=($rentProduct['rentdispminihome'] != 'main')?'checked':''?> />예
		<input type="radio" name="rentdispminihome" value="main" <?=($rentProduct['rentdispminihome'] == 'main')?'checked':''?> />아니오
	</td>
</tr>-->
<tr class="rentalItemArea3">
	<th> 당일예약가능여부</th>
	<td colspan="3">
		<input type="radio" name="today_reserve" id="itemReserve1" value="Y" />
		<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=itemReserve1>가능</label>
		<input type="radio" name="today_reserve" id="itemReserve2" value="N" checked />
		<label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=itemReserve2>불가능</label>
	</TD>
</TR>
<tr class="rentalItemArea4">
	<th><font color="FF4800">*</font><span class="font_orange" style="font-weight:bold">대여가격</span></th>
	<td colspan="3">
		<style type="text/css">
		#rentOptTable{border-top:1px solid #b9b9b9;font-size:12px;}
		#rentOptTable th{padding:8px 2px;border-bottom:1px solid #ededed;border-left:1px solid #ededed;background:#f8f8f8; background-image:none; text-align:center}
		#rentOptTable .firstTh{border-left:none;background:#f8f8f8;}
		#rentOptTable td{padding:8px 2px;border-bottom:1px solid #ededed;border-left:1px solid #ededed;}
		#rentOptTable .firstTd{padding-left:10px;border-left:none;}
		</style>
		<div id="rentPriceArea">
			<script language="javascript" type="text/javascript">
			function chn_mainCommi() {
				var trust_vender = $j('select[name=trust_vender]').val();
				var val = $j('select[name=code1]').val();

				$j.post('./new/productCommi.php',{'trust_vender':trust_vender,'code':val,'vender':<?=$_VenderInfo->getVidx()?>},
				function(data){
					if(data.maincommi && data.maincommi>0){
						$j('#maincommi').val(data.maincommi);
					}else{
						$j('#maincommi').val($j('#orgcommi').val());
					}
				},'json');
			}

			var syncmopttype = null;
			var syncownership = null;
			function toggleOptType(){
				<? if(!_isInt($rentProduct['pridx'])){ ?>
				var mopttype = $j('input:radio[name=multiOpt]:checked').val();
				<? }else{ ?>
				var mopttype = '<?=($rentProduct['multiOpt']=='1')?'1':'0'?>';
				<? } ?>
				var ownership = $j('input:radio[name=ownership]:checked').val();
				var pricetype = $j('select[name=pricetype]').val();
				var halfday = $j('input:radio[name=halfday]:checked').val();
				var oneday_ex = $j('input:radio[name=oneday_ex]:checked').val();
				
				/*
				1시간제인 경우 옵션별 초과요금설정
				*/
				if(pricetype=="time"){
					$j('#rentOptTable').find('.optTime').css('display','');
				}else{
					$j('#rentOptTable').find('.optTime').css('display','none');
				}
				
				/*
				24시간제인 경우 옵션별 12시간요금설정
				*/
				if(pricetype=="day"){
					if(halfday=="Y"){
						$j('#rentOptTable').find('.optDay').css('display','');
					}else{
						$j('#rentOptTable').find('.optDay').css('display','none');
					}
					if(oneday_ex=="half"){
						$j('#rentOptTable').find('.optDay2').css('display','');
						$j('#rentOptTable').find('.optDay3').css('display','none');
					}else if(oneday_ex=="time"){
						$j('#rentOptTable').find('.optDay2').css('display','none');
						$j('#rentOptTable').find('.optDay3').css('display','');
					}else{
						$j('#rentOptTable').find('.optDay2').css('display','none');
						$j('#rentOptTable').find('.optDay3').css('display','none');
					}
					
				}else{
					$j('#rentOptTable').find('.optDay').css('display','none');
					$j('#rentOptTable').find('.optDay2').css('display','none');
					$j('#rentOptTable').find('.optDay3').css('display','none');
				}

				if(mopttype == '1'){
					$j('#rentOptTable').find('.optMulti').css('display','');
					$j('#rentOptTable').find('caption').css('display','');
					
					$j('#rentOptTable2').find('.optMulti').css('display','');
					$j('#rentOptTable2').find('caption').css('display','');
					if(ownership=="mv"){
						$j('#rentOptTable2').find('.optMoney').css('display','none');
					}else{
						$j('#rentOptTable2').find('.optMoney').css('display','');
					}
				}else{
					$j('#rentOptTable').find('.optMulti').css('display','none');
					$j('#rentOptTable').find('caption').css('display','none');
					
					$j('#rentOptTable2').find('.optMulti').css('display','none');
					$j('#rentOptTable2').find('caption').css('display','none');
				
					if(ownership=="mv"){
						$j('#rentOptTable2').find('.optMoney').css('display','none');
					}else{
						$j('#rentOptTable2').find('.optMoney').css('display','');
					}
					//$j('#rentOptTable>tbody').find('tr:gt(0)').remove();
				}
				syncmopttype = mopttype;
				syncownership = ownership;
				if(pricetype!="long"){
					if($j('#rentOptTable>tbody').find('tr').length < 1){
						rentOptInsert(true);
						$j('#rentOptTable2>tbody').find('tr').remove();
					}
				}else{
					if($j('#rentOptTable2>tbody').find('tr').length < 1){
						rentOptInsert2(true);
						$j('#rentOptTable>tbody').find('tr').remove();
					}
				}

			}
			function delOptitem(el){							
				var cnt = 	$('#rentOptTable>tbody').find('tr.itemSepRow').length;
				if(cnt < 2){
					alert('1개 이상의 옵션 항목이 필요 합니다.');
				}else{
					var pel = $j(el).parent().parent();
					$j(pel).nextUntil('.itemSepRow','tr').remove();
					$j(pel).remove();
				}
			}
			
			function rentOptInsert(chktoggle){
				bel = $j('#rentPriceArea').find('textarea[name=optformatcode]');
				$j('#rentOptTable>tbody').append($j(bel).val());
				toggleSeasonList();
				if(!chktoggle) toggleOptType();
				else{
					if(syncmopttype != '1'){
						$j('#rentOptTable').find('.optMulti').css('display','none');
						$j('#rentOptTable').find('caption').css('display','none');
					}
				}
			}
			
			function delOptitem2(el){							
				var cnt = 	$('#rentOptTable2>tbody').find('tr.itemSepRow').length;
				if(cnt < 2){
					alert('1개 이상의 옵션 항목이 필요 합니다.');
				}else{
					var pel = $j(el).parent().parent();
					$j(pel).nextUntil('.itemSepRow','tr').remove();
					$j(pel).remove();
				}
			}
			function rentOptInsert2(chktoggle){
				bel = $j('#rentPriceArea').find('textarea[name=optformatcode2]');
				$j('#rentOptTable2>tbody').append($j(bel).val());
				if(!chktoggle) toggleOptType();
				else{
					if(syncmopttype != '1'){
						$j('#rentOptTable2').find('.optMulti').css('display','none');
						$j('#rentOptTable2').find('caption').css('display','none');
					}
					if(syncownership=="mv"){
						$j('#rentOptTable2').find('.optMoney').css('display','none');
					}else{
						$j('#rentOptTable2').find('.optMoney').css('display','');
					}
				}
			}
			
			function autoSolv(cel,del,sel){
				if(cel && del && sel){
					var customerp = parseInt($j(cel).val());
					var discountp = parseInt($j(del).val());
					var sellp = 0;						
					if(!isNaN(customerp) && customerp >= 0){
						sellp = customerp;
						if(!isNaN(discountp) && discountp > 0 && discountp <100){
							$j(del).val(discountp);
							sellp = parseInt(Math.round(customerp*(100-discountp)/10)/100)*10;
						}else{
							$j(del).val('0');
						}
					}
					$j(sel).val(sellp);
				}else{
				}
			}

			function autoSolv2(mon,opy,sel,ins){
				if(mon && (opy || sel) && ins){
					var month = parseInt($j(mon).val());
					var optpay = $j(opy).val();
					var customerp = parseInt($j(sel).val());
					var intsp = parseInt($j(ins).val());

					if(optpay=="분납"){
						if(!isNaN(customerp) && customerp > 0){
							intsp = parseInt(customerp/month);
							$j(ins).val(intsp);
						}	
					}
				}
			}

			function autoSolv3(mon,opy,sel,ins){
				if(mon && (opy || sel) && ins){
					var month = parseInt($j(mon).val());
					var optpay = $j(opy).val();
					var customerp = parseInt($j(sel).val());
					var intsp = parseInt($j(ins).val());

					if(optpay=="분납"){
						if(!isNaN(intsp) && intsp > 0){
							customerp = parseInt(intsp*month);
							$j(sel).val(customerp);
						}	
					}
				}
			}
			
			$j(function(){
				$j('input:radio[name=ownership]').on('click',toggleOptType);
				$j('input:radio[name=multiOpt]').on('click',toggleOptType);
				$j('#pricetype').on('change',toggleOptType);
				$j('input:radio[name=halfday]').on('click',toggleOptType);
				$j('input:radio[name=oneday_ex]').on('click',toggleOptType);

				$j('#rentOptTable').on('keyup','.autoSolv',function(e){
					var ptr = $j(this).parent().parent();
					//var v   = $j(this).data("calc");
					cel = $j(ptr).find('input[name^=custPrice]');
					del = $j(ptr).find('input[name^=priceDiscP]');
					sel = $j(ptr).find('input[name^=nomalPrice]');

					$('input[name=base_price]').val(cel.val());
					autoSolv(cel,del,sel);
				});
				
				//초과 1시간당
				$j('#rentOptTable').on('keyup','.autoSolv_time',function(e){
					var ptr = $j(this).parent().parent();
					cel4 = $j(ptr).find('input[name^=nomalPrice]');
					del4 = $j(ptr).find('input[name^=productTimeover_percent]');
					sel4 = $j(ptr).find('input[name^=productTimeover_price]');
					autoPrice(cel4,del4,sel4);
				});
				
				//당일12시간요금
				$j('#rentOptTable').on('keyup','.autoSolv',function(e){
					var ptr = $j(this).parent().parent();
					var ptr2 = $j('#price1').parent();
					del2 = $j(ptr2).find('input[name^=halfday_percent]');
					cel2 = $j(ptr).find('input[name^=nomalPrice]');
					sel2 = $j(ptr2).find('input[name^=halfday_per_price]');
					autoPrice(cel2,del2,sel2);
				});
				
				//추가12시간요금
				$j('#rentOptTable').on('keyup','.autoSolv',function(e){
					var ptr = $j(this).parent().parent();
					var ptr3 = $j('#price2').parent();
					del3 = $j(ptr3).find('input[name^=time_percent]');
					sel3 = $j(ptr3).find('input[name^=time_per_price]');
					autoPrice(cel2,del3,sel3);
				});

				//추가1시간요금
				$j('#rentOptTable').on('keyup','.autoSolv_half',function(e){
					var ptr = $j(this).parent().parent();
					cel5 = $j(ptr).find('input[name^=nomalPrice]');
					del5 = $j(ptr).find('input[name^=productHalfday_percent]');
					sel5 = $j(ptr).find('input[name^=productHalfday_price]');
					autoPrice(cel5,del5,sel5);
				});
				
				$j('#rentOptTable').on('keyup','.autoSolv_halftime',function(e){
					var ptr = $j(this).parent().parent();
					cel6 = $j(ptr).find('input[name^=nomalPrice]');
					del6 = $j(ptr).find('input[name^=productOverHalfTime_percent]');
					sel6 = $j(ptr).find('input[name^=productOverHalfTime_price]');
					autoPrice(cel6,del6,sel6);
				});
				
				$j('#rentOptTable').on('keyup','.autoSolv_onetime',function(e){
					var ptr = $j(this).parent().parent();
					cel7 = $j(ptr).find('input[name^=nomalPrice]');
					del7 = $j(ptr).find('input[name^=productOverOneTime_percent]');
					sel7 = $j(ptr).find('input[name^=productOverOneTime_price]');
					autoPrice(cel7,del7,sel7);
				});

				$j('#rentOptTable').on('keyup','.autoPerSolv',function(e){
					var ptr = $j(this).parent().parent();
					cel0 = $j(ptr).find('input[name^=custPrice]');
					del0 = $j(ptr).find('input[name^=priceDiscP]');
					sel0 = $j(ptr).find('input[name^=nomalPrice]');
					autoPercent_basic(cel0,del0,sel0);
				});
				
				$j('#rentOptTable').on('keyup','.autoPerSolv',function(e){
					var ptr = $j(this).parent().parent();
					del = $j(ptr).find('input[name^=productTimeover_percent]');
					cel = $j(ptr).find('input[name^=nomalPrice]');
					sel = $j(ptr).find('input[name^=productTimeover_price]');
					autoPercent(cel,del,sel);
				});
				
				$j('#rentOptTable').on('keyup','.autoPerSolv',function(e){
					var ptr = $j(this).parent().parent();
					cel5 = $j(ptr).find('input[name^=nomalPrice]');
					del5 = $j(ptr).find('input[name^=productHalfday_percent]');
					sel5 = $j(ptr).find('input[name^=productHalfday_price]');
					autoPercent(cel5,del5,sel5);
				});
				
				$j('#rentOptTable').on('keyup','.autoPerSolv',function(e){
					var ptr = $j(this).parent().parent();
					cel6 = $j(ptr).find('input[name^=nomalPrice]');
					del6 = $j(ptr).find('input[name^=productOverHalfTime_percent]');
					sel6 = $j(ptr).find('input[name^=productOverHalfTime_price]');
					autoPercent(cel6,del6,sel6);
				});
				
				$j('#rentOptTable').on('keyup','.autoPerSolv',function(e){
					var ptr = $j(this).parent().parent();
					cel7 = $j(ptr).find('input[name^=nomalPrice]');
					del7 = $j(ptr).find('input[name^=productOverOneTime_percent]');
					sel7 = $j(ptr).find('input[name^=productOverOneTime_price]');
					autoPercent(cel7,del7,sel7);
				});

				$j('#rentOptTable2').on('keyup','.autoSolv2',function(e){
					var ptr = $j(this).parent().parent();
					mon = $j(ptr).find('input[name^=optionName]');
					opy = $j(ptr).find('select[name^=optionPay]');
					sel = $j(ptr).find('input[name^=nomalPrice]');
					ins = $j(ptr).find('input[name^=installmentPay]');

					autoSolv2(mon,opy,sel,ins);
					$j(ptr).find("#installmentMonth").text(mon.val());
				});
				$j('#rentOptTable2').on('keyup','.autoSolv3',function(e){
					var ptr = $j(this).parent().parent().parent();
					mon = $j(ptr).find('input[name^=optionName]');
					opy = $j(ptr).find('select[name^=optionPay]');
					sel = $j(ptr).find('input[name^=nomalPrice]');
					ins = $j(ptr).find('input[name^=installmentPay]');

					autoSolv3(mon,opy,sel,ins);
					$j(ptr).find("#installmentMonth").text(mon.val());
				});
				toggleOptType();
				chPriceType();
				
				$j('#rentOptTable').on('mouseover','.priceHelp',function(){
					var pos = $j(this).position();									
					var pricev = parseInt($j(this).parent().find('input[name^=nomalPrice]').val());
					if(!isNaN(pricev)){									
						if($j('#priceHelpDiv').find('#priceHelp24')) $j('#priceHelpDiv').find('#priceHelp24').text(pricev+'원');
						if($j('#priceHelpDiv').find('#priceHelp12')) $j('#priceHelpDiv').find('#priceHelp12').text(Math.round(pricev*0.7)+'원');
						if($j('#priceHelpDiv').find('#priceHelp1')) $j('#priceHelpDiv').find('#priceHelp1').text(Math.round(pricev/20)+'원');
						$j('#priceHelpDiv').css({'display':'','left':pos.left+30,'top':pos.top-20});
					}
				});
				
				$j('#rentOptTable').on('mouseout','.priceHelp',function(){
					$j('#priceHelpDiv').css('display','none');
				});


				$j('#rentOptTable2').on('change','.optpay',function(){
					if($j(this).val()=="분납"){
						$j(this).parent().parent().find("#instDiv").show();
					}else{
						$j(this).parent().parent().find("#instDiv").hide();
					}
				});

			});
			</script>
			<? if(!_isInt($_data->pridx)){ ?>
			<input type="radio" name="multiOpt" id="multiOpt1" value="0" checked="checked" /> <label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=multiOpt1>단일상품</label> 
			<input type="radio" name="multiOpt" id="multiOpt2" value="1" /><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=multiOpt2>복합상품</label><br />
			<? }else{ ?>
			<input type="hidden" name="multiOpt" value="<?=$rentProduct['multiOpt']?>" />						
			<? } ?>
			
			<div id="priceHelpDiv" style="width:200px; padding:10px; height:60px; position:absolute; background:#efefef; border:1px solid #FF0; display:none">
			24시간 : <span id="priceHelp24"></span><br />
			12시간 : <span id="priceHelp12"></span><br />
			<? if($categoryRentInfo['pricetype'] == 'time'){ ?> 추가 1시간 : <span id="priceHelp1"></span><? } ?>
			</div>
			<table border="0" cellpadding="0" cellspacing="0" id="rentOptTable">
				<caption style="padding:0px;"><input type="button" value="항목 추가" onclick="javascript:rentOptInsert()" style="width:100%" /></caption>
				<thead>
					<tr>
						<th class="firstTh optMulti">옵션명</th>
						<th width=80>등급</th>
						<th>&nbsp;</th>
						<th>정상가-할인율</th>
						<th>= 할인가(할증)</th>
						<th class="optTime">초과 1시간당</th>
						<th class="optDay">당일12시간요금<br>(24시간요금의)</th>
						<th class="optDay2">추가12시간요금<br>(24시간요금의)</th>
						<th class="optDay3">추가1시간요금<br>(24시간요금의)</th>
						<th width=80>재고량</th>
						<th width=80 class="optMulti">비고</th>
					</tr>	
				</thead>
				<tbody>
				
				</tbody>
			</table>
			<textarea name="optformatcode" style="display:none">
				<tr class="itemSepRow">
					<td class="firstTd optMulti itemSepTD" align="center"  rowspan="<?=($categoryRentInfo['useseason'] == '1')?6:1?>"><input type="text" name="optionName[]" style="width:100;" class="input" /></td>
					<td width=80 align="center" class="itemSepTD" rowspan="<?=($categoryRentInfo['useseason'] == '1')?6:1?>">
						<select name="optionGrade[]">
							<?
							foreach (rentProduct::_status() as $k=>$v) {
								echo "<option value='".$k."'>".$v."</option>";
							}
							?>
						</select>
					</td>				
					<td align="center">일반가(비수기*평일)</td>
					<td>
						<input type="text" name="custPrice[]" value="0" style="width:80px" class="input autoSolv" />원 - <input type="text" name="priceDiscP[]" value="0" style="width:30px" class="input autoSolv" />%
					</td>
					<td>
						<input type="text" name="nomalPrice[]" style="width:80px;" value="0" class="input autoPerSolv" />원
						<input type="hidden" name="roptidx[]" value="" />
					</td>
					<td align="center" class="optTime itemSepTD" rowspan="<?=($categoryRentInfo['useseason'] == '1')?6:1?>">
						<input type="text" name="productTimeover_percent[]" size="3" class="input autoSolv_time">% 
						<input type="text" name="productTimeover_price[]" size="10" class="input autoPerSolv">원
					</td>
					<td align="center" class="optDay itemSepTD" rowspan="<?=($categoryRentInfo['useseason'] == '1')?6:1?>">
						<input type="text" name="productHalfday_percent[]" size="3" class="input autoSolv_half" value="<?=$categoryRentInfo['halfday_percent']?>">% 
						<input type="text" name="productHalfday_price[]" size="10" class="input autoPerSolv">원
					</td>
					<td align="center" class="optDay2 itemSepTD" rowspan="<?=($categoryRentInfo['useseason'] == '1')?6:1?>">
						<input type="text" name="productOverHalfTime_percent[]" size="3" class="input autoSolv_halftime" value="<?=$categoryRentInfo['time_percent']?>">% 
						<input type="text" name="productOverHalfTime_price[]" size="10" class="input autoPerSolv">원
					</td>
					<td align="center" class="optDay3 itemSepTD" rowspan="<?=($categoryRentInfo['useseason'] == '1')?6:1?>">
						<input type="text" name="productOverOneTime_percent[]" size="3" class="input autoSolv_onetime" value="<?=$categoryRentInfo['time_percent']?>">% 
						<input type="text" name="productOverOneTime_price[]" size="10" class="input autoPerSolv">원
					</td>
					<td align="center" class="itemSepTD" rowspan="<?=($categoryRentInfo['useseason'] == '1')?6:1?>">
						<input type="text" name="productCount[]" value="0" style="width:40px;text-align:center;" class="input" />개
					</td>
					<td align="center" class="optMulti itemSepTD" rowspan="<?=($categoryRentInfo['useseason'] == '1')?6:1?>">
						<input type="button" value="삭제" onclick="javascript:delOptitem(this)" />
						<!-- <a href="javascript:rentOptInsert('insert');"><img src="images/btn_badd2.gif" /></a> --->
					</td>
				</tr>								
				<?// if($categoryRentInfo['useseason'] == '1'){ ?>
				<tr class="seasonList" style="display:<?=$display?>">
					<td align="center">성수기*평일 할증</td>			
					<td>&nbsp;</td>
					<td><input type="text" name="busySeason[]" value="0" style="width:30px" class="input" />%</td>
				</tr>
				<tr class="seasonList" style="display:<?=$display?>">
					<td align="center">성수기*주말공휴일 할증</td>			
					<td>&nbsp;</td>
					<td><input type="text" name="busyHolidaySeason[]" value="0" style="width:30px" class="input" />%</td>
				</tr>
				<tr class="seasonList" style="display:<?=$display?>">
					<td align="center">준성수기*평일 할증</td>			
					<td>&nbsp;</td>
					<td><input type="text" name="semiBusySeason[]" value="0" style="width:30px" class="input" />%</td>
				</tr>
				<tr class="seasonList" style="display:<?=$display?>">
					<td align="center">준성수기*주말공휴일 할증</td>			
					<td>&nbsp;</td>
					<td><input type="text" name="semiBusyholidaySeason[]" value="0" style="width:30px" class="input" />%</td>
				</tr>
				<tr class="seasonList" style="display:<?=$display?>">
					<td align="center">비수기*주말공휴일 할증</td>			
					<td>&nbsp;</td>
					<td><input type="text" name="holidaySeason[]" value="0" style="width:30px" class="input" />%</td>
				</tr>
				<? //} ?>
			</textarea>

			<table border="0" cellpadding="0" cellspacing="0" id="rentOptTable2" style="display:none">
				<caption style="padding:0px;"><input type="button" value="항목 추가" onclick="javascript:rentOptInsert2()" style="width:100%" /></caption>							
				<thead>
					<tr>
						<th class="firstTh">약정기간</th>
						<th>분납/일시불</th>
						<th>가격</th>
						<th class="optMoney">보증금</th>
						<th>선납금</th>
						<th>재고량</th>
						<th class="optMulti">비고</th>
					</tr>	
				</thead>
				<tbody>
				
				</tbody>
			</table>
			<textarea name="optformatcode2" style="display:none">
				<tr class="itemSepRow">
					<td class="firstTd" align="center"><input type="text" name="optionName[]" style="width:40%;" class="input autoSolv2" />개월</td>
					<td align="center">
						<select name="optionPay[]" class="optpay">
							<option value="일시납">일시납</option>
							<option value="분납">분납</option>
						</select>
					</td>
					<td>
						<input type="text" name="nomalPrice[]" value="0" style="width:80px" class="input autoSolv2" />원<br>
						<span id="instDiv" style="display:none">
						(<input type="text" name="installmentPay[]" value="0" style="width:50px" class="input autoSolv3" />*<span id="installmentMonth"></span>개월)</span>
					</td>
					<td class="optMoney"><input type="text" name="deposit[]" style="width:80px;" value="0" class="input" />원</td>
					<td><input type="text" name="prepay[]" style="width:80px;" value="0" class="input" />원</td>
					<td align="center"><input type="text" name="productCount[]" value="0" style="width:60px;text-align:center;" class="input" />개</td>
					<td align="center" class="optMulti">
						<input type="button" value="삭제" onclick="javascript:delOptitem2(this)" />
					</td>
				</tr>
			</textarea>
		</div>
	</td>
</tr>

<? 
if($_venderdata->longrent=="1"){//입점업체 고유설정 사용 선택시
	$longrentinfo = venderLongrentCharge($_VenderInfo->getVidx(),0);		
}else{//본사정책에 따름 선택시
	$longrentinfo = rentLongrentCharge(pick($code,$parentcode));		
}
?>

<tr class="rentalItemArea7">
	<th>장기대여 설정</th>
	<td colspan="3">
		<style type="text/css">
		#longrentDiv div{ width:30%; margin-right:3px;; float:left; padding:5px; background:#f4f4f4}
		#longrentDiv div img{cursor:pointer}
		</style>
		<script language="javascript" type="text/javascript">
		$j(function(){
			$j(document).on('click','#longrentDiv>div>img',function(e){
				rmvLongrentCharge(this);
			});

			<?if(_array($longrentinfo)){?>
				$j('#addLongrent_sday').val($j('input[name=last_eday]').val());
			<?}?>

			$j('#longRentTbl').on('keyup','.autoSolv',function(e){
				var ptr = $j(this).parent();
				var ptr2 = $j('#rentOptTable').parent().parent();
				del = $j(ptr).find('input[name^=addLongrentPercent]');
				cel = $j(ptr2).find('input[name^=nomalPrice]');
				sel = $j(ptr).find('input[name^=addLongrentPrice]');
				var d = $j('#addLongrent_sday').val();

				var customerp = parseInt($j(cel).val()*d);
				var discountp = parseInt($j(del).val());
				var sellp = 0;
				if(!isNaN(customerp) && customerp >= 0){
					sellp = customerp;
					if(!isNaN(discountp) && discountp > 0){
						$j(del).val(discountp);
						sellp = parseInt(customerp + Math.floor(customerp*discountp/100));
					}else{
						$j(del).val('0');
					}
				}
				$j(sel).val(sellp);
			});
			$j('#longRentTbl').on('keyup','.autoPerSolv',function(e){
				var ptr = $j(this).parent();
				var ptr2 = $j('#rentOptTable').parent().parent();
				del = $j(ptr).find('input[name^=addLongrentPercent]');
				cel = $j(ptr2).find('input[name^=nomalPrice]');
				sel = $j(ptr).find('input[name^=addLongrentPrice]');
				var d = $j('#addLongrent_sday').val();

				var customerp = parseInt($j(cel).val()*d);
				var sellprice = parseInt($j(sel).val());
				var discper=0;
				if(!isNaN(customerp) && customerp >= 0){
					discper = $j(del).val();
					if(!isNaN(sellprice) && sellprice > 0){
						$j(sel).val(sellprice);
						discper = parseInt(Math.round(100*sellprice/customerp)-100);
					}else{
						$j(sel).val('0');
					}
				}
				$j(del).val(discper);
			});

		});
		function rmvLongrentCharge(el){
			$j(el).parent().remove();
			if($j('#addLongrent_sday').val()>$j(el).parent().find('input[name^=longrent_sday]').val()){
				$j('#addLongrent_sday').val($j(el).parent().find('input[name^=longrent_sday]').val());
			}
		}
		function addLongrentCharge(){
			var sd = parseInt($j('#addLongrent_sday').val());
			var ed = parseInt($j('#addLongrent_eday').val());
			var p = parseInt($j('#addLongrentPercent').val());
			var addprice = $j('#addLongrentPrice').val();
			if(isNaN(sd) || sd < 1){
				alert('날짜를 올바르게 입력하세요.');
				$j('#addLongrent_sday').focus();
			}else if(isNaN(ed) || ed < 1){
				alert('날짜를 올바르게 입력하세요.');
				$j('#addLongrent_eday').focus();
			}else if(isNaN(p) || p < 1){
				alert('추가과금율를 올바르게 입력하세요.');
				$j('#addLongrentPercent').focus();
			}else{
				var dupvalel = null;
				$j('#longrentDiv>div').each(function(idx,el){
					if($j(el).find('input[name^=longrent_sday]').val() == String(sd)){
						dupvalel = $j(el);
						return false;
					}
				});
				if(dupvalel){
					alert('중복된 일자가 있습니다. 먼저 중복 항목을 삭제후 추가 하시기 바랍니다.');
				}else{
					html = '<div><input type="hidden" name="longrent_sday[]" value="'+sd+'"><input type="hidden" name="longrent_eday[]" value="'+ed+'"><input type="hidden" name="longrent_percent[]" value="'+p+'"><span style="float:left">'+sd+'~'+ed+' 일까지 '+p+'% '+$j('#addLongrentPrice').val()+'원 추가 ('+number_format(addprice)+'원)</span><img src="../admin/images/btn_del.gif" alt="삭제" align="right" /></div>';
					$j('#longrentDiv').append(html);
					$j('#addLongrent_sday').val(ed+1);
					$j('#addLongrent_eday').val('');
					$j('#addLongrentPercent').val('');
					$j('#addLongrentPrice').val('');
				}
			}
			
		}
		</script>
		<table cellpadding="0" cellspacing="0" style="margin-top:7px;padding:7px 7px 7px 7px; border-bottom:0px;border:2px solid #acacac;background-color:#ffffff" id="longRentTbl">
			<tr>
				<th style="width:100px;">기간</th>
				<td class="norbl" style="padding:5px;">
					<input type="text" name="addLongrent_sday" id="addLongrent_sday" value="" style="width:30px;" />~
					<input type="text" name="addLongrent_eday" id="addLongrent_eday" value="" style="width:30px;" />
					일까지
				</td>
				<th style="width:100px;">추가과금</th>
				<td style="padding:5px;">
					<input type="text" name="addLongrentPercent" id="addLongrentPercent" class="autoSolv" value="" style="width:30px;" />% 
					<input type="text" name="addLongrentPrice" id="addLongrentPrice" class="autoPerSolv" value="" style="width:60px;" />원
				</td>
				<td>
					<input type="button" name="addLongrentBtn" value="추가" onclick="javascript:addLongrentCharge()" />
				</td>
			</tr>
		</table>
		<div style="padding:3px 0px; clear:both" id="longrentDiv">
			<? 
			if(_array($longrentinfo)){
				foreach($longrentinfo as $k=>$v){ 
					$dayPrice = $cprice*$v['sday'];
					$disPrice = number_format($dayPrice + ($dayPrice * $v['percent']/100));
			?>
			<script>
				$j('#rentOptTable').on('keyup','.autoSolv',function(e){
					var ptr = $j(this).parent().parent();
					sel = $j(ptr).find('input[name^=nomalPrice]');
					$j('#longrent_price<?=$k?>').html(sel.val()*<?=$v['percent']?>*0.01+"원");
				});
			</script>
			<div>
				<input type="hidden" name="longrent_sday[]" value="<?=$v['sday']?>">
				<input type="hidden" name="longrent_eday[]" value="<?=$v['eday']?>">
				<input type="hidden" name="longrent_percent[]" value="<?=$v['percent']?>">
				<span style="float:left">
				<?=$v['sday']."~".$v['eday']?>
				일까지
				<?=$v['percent']?>% <span id="longrent_price<?=$k?>"></span> 추가 (<?=$disPrice?>원)
				</span>
				<img src="../admin/images/btn_del.gif" alt="삭제" align="right" />
			</div>
			<?	}
		}?>
		<input type="hidden" name="last_eday" value="<?=$v['eday']+1?>">
		</div>
		
	</td>
</tr>
<tr class="rentalItemArea5">
	<th>장기할인 설정</th>
	<td colspan="3">
		※ 장기할인서비스를 이용하지 않을려면 기간과 할인율에 0을 입력하시면 됩니다.<br>
		<style type="text/css">
		#rangeDiscountDiv div{ width:30%; margin-right:3px;; float:left; padding:5px; background:#f4f4f4}
		#rangeDiscountDiv div img{cursor:pointer}
		</style>
		<script language="javascript" type="text/javascript">
		function number_format(num){
			var num_str = num.toString();
			var result = "";

			for(var i=0; i<num_str.length; i++){
				var tmp = num_str.length - (i+1);
				if(((i%3)==0) && (i!=0))    result = ',' + result;
				result = num_str.charAt(tmp) + result;
			}
			return result;
		}

		$j(function(){
			$j(document).on('click','#rangeDiscountDiv>div>img',function(e){
				rmvRangDiscount(this);
			});

			$j('#longDiscountTbl').on('keyup','.autoSolv',function(e){
				var ptr = $j(this).parent();
				var ptr2 = $j('#rentOptTable').parent().parent();
				del = $j(ptr).find('input[name^=addRangeDiscountPercent]');
				cel = $j(ptr2).find('input[name^=nomalPrice]');
				sel = $j(ptr).find('input[name^=addRangeDiscountPrice]');
				var d = $j('#addRangeDiscountDay').val();

				var customerp = parseInt($j(cel).val()*d);
				var discountp = parseInt($j(del).val());
				var sellp = 0;
				if(!isNaN(customerp) && customerp >= 0){
					sellp = customerp;
					if(!isNaN(discountp) && discountp > 0 && discountp <100){
						$j(del).val(discountp);
						sellp = parseInt(customerp - Math.floor(customerp*discountp/100));
					}else{
						$j(del).val('0');
					}
				}
				$j(sel).val(sellp);
			});
			$j('#longDiscountTbl').on('keyup','.autoPerSolv',function(e){
				var ptr = $j(this).parent();
				var ptr2 = $j('#rentOptTable').parent().parent();
				del = $j(ptr).find('input[name^=addRangeDiscountPercent]');
				cel = $j(ptr2).find('input[name^=nomalPrice]');
				sel = $j(ptr).find('input[name^=addRangeDiscountPrice]');
				var d = $j('#addRangeDiscountDay').val();

				var customerp = parseInt($j(cel).val()*d);
				var sellprice = parseInt($j(sel).val());
				var discper=0;
				if(!isNaN(customerp) && customerp >= 0){
					discper = $j(del).val();
					if(!isNaN(sellprice) && sellprice > 0){
						$j(sel).val(sellprice);
						discper = parseInt(Math.round(100*sellprice/customerp));
					}else{
						$j(sel).val('0');
					}
				}
				$j(del).val(discper);
			});

		});
		function rmvRangDiscount(el){
			$j(el).parent().remove();
		}
		function addRangeDiscount(){
			var d = $j('#addRangeDiscountDay').val();//parseInt($j('#addRangeDiscountDay').val());
			var p = parseInt($j('#addRangeDiscountPercent').val());	
			var cel = parseInt($j('input[name^=custPrice]').val());

			if(isNaN(d) || d < 0){
				alert('기간을 올바르게 입력하세요.');
				$j('#addRangeDiscountDay').focus();
			}else if(isNaN(p) || p < 0|| p>100){
				alert('할인율을 올바르게 입력하세요.');
				$j('#addRangeDiscountPercent').focus();
			}else{
				var dupvalel = null;
				$j('#rangeDiscountDiv>div').each(function(idx,el){
					if($j(el).find('input[name^=discrangeday]').val() == String(d)){
						dupvalel = $j(el);
						return false;
					}
				});
				if(dupvalel){
					alert('중복된 일자가 있습니다. 먼저 중복 항목을 삭제후 추가 하시기 바랍니다.');
				}else if(d<1){
					alert('장기할인은 1일 이상가능합니다.');
				}else{
					var dayPrice = parseInt(cel*d);
					var disPrice = number_format(dayPrice - parseInt(dayPrice*p/100));
				
					html = '<div><input type="hidden" name="discrangeday[]" value="'+d+'"><input type="hidden" name="discrangepercent[]" value="'+p+'"><span style="float:left">'+d+' 일이상 '+p+'% 할인 ('+disPrice+'원)</span><img src="../admin/images/btn_del.gif" alt="삭제" align="right" /></div>';
					$j('#rangeDiscountDiv').append(html);
					$j('#addRangeDiscountDay').val('');
					$j('#addRangeDiscountPercent').val('');
					$j('#addRangeDiscountPrice').val('');
				}
			}
			
		}
		</script>
		<table cellpadding="0" cellspacing="0" style="margin-top:7px;padding:7px 7px 7px 7px; border-bottom:0px;border:2px solid #acacac;background-color:#ffffff" id="longDiscountTbl">
			<tr>
				<th style="width:100px;">기간</th>
				<td class="norbl" style="padding:5px;">
					<input type="text" name="addRangeDiscountDay" id="addRangeDiscountDay" value="" style="width:30px;" />
					일이상</td>
				<th style="width:100px;">할인율</th>
				<td style="padding:5px;">
					<input type="text" name="addRangeDiscountPercent" id="addRangeDiscountPercent" class="autoSolv" value="" style="width:30px;" />% 
					<input type="text" name="addRangeDiscountPrice" id="addRangeDiscountPrice" class="autoPerSolv" value="" style="width:60px;" />원
					<span id="disprice"></span>
				</td>
				<td>
					<input type="button" name="addRangeDiscountBtn" value="추가" onclick="javascript:addRangeDiscount()" />
				</td>
			</tr>
		</table>
		<? 
		if($_venderdata->longdiscount=="1"){//입점업체 고유설정 사용 선택시
			$ldiscinfo = venderLongDiscount($_VenderInfo->getVidx(),0);
		}else{//본사정책에 따름 선택시
			$ldiscinfo = rentLongDiscount(pick($code,$parentcode));
		}
		?>
		<div style="width:100%; padding:3px 0px; clear:both" id="rangeDiscountDiv">
			<? if(_array($ldiscinfo)){
			foreach($ldiscinfo as $dday=>$dpercent){ ?>
			<div>
				<input type="hidden" name="discrangeday[]" value="<?=$dday?>">
				<input type="hidden" name="discrangepercent[]" value="<?=$dpercent?>">
				<span style="float:left">
				<?=$dday?>
				일이상
				<?=$dpercent?>
				%할인</span><img src="../admin/images/btn_del.gif" alt="삭제" align="right" /></div>
			<?	}
		}?>
		</div>
	</td>
</tr>

<tr class="rentalItemArea6">
	<th>환불 설정</th>
	<td colspan="3">
		※ 환불설정을 이용하지 않을려면 취소일과 수수료에 0을 입력하시면 됩니다.<br>
		<style type="text/css">
		#refundDiv div{ width:30%; margin-right:3px;; float:left; padding:5px; background:#f4f4f4}
		#refundDiv div img{cursor:pointer}
		</style>
		<script language="javascript" type="text/javascript">
		$j(function(){
			$j(document).on('click','#refundDiv>div>img',function(e){
				rmvRefundCommi(this);

			});
		});
		function rmvRefundCommi(el){
			$j(el).parent().remove();
		}
		function addRefundCommi(){
			var d = parseInt($j('#addRefundDay').val());
			var p = parseInt($j('#addRefundPercent').val());			
			if(isNaN(d) || d < 0){
				alert('취소일을 올바르게 입력하세요.');
				$j('#addRefundDay').focus();
			}else if(isNaN(p) || p < 0|| p>100){
				alert('수수료를 올바르게 입력하세요.');
				$j('#addRefundPercent').focus();
			}else{
				var dupvalel = null;
				$j('#refundDiv>div').each(function(idx,el){
					if($j(el).find('input[name^=refundday]').val() == String(d)){
						dupvalel = $j(el);
						return false;
					}
				});
				if(dupvalel){
					alert('중복된 일자가 있습니다. 먼저 중복 항목을 삭제후 추가 하시기 바랍니다.');				
				}else{
					html = '<div><input type="hidden" name="refundday[]" value="'+d+'"><input type="hidden" name="refundpercent[]" value="'+p+'"><span style="float:left">'+d+' 일전 '+p+'%</span><img src="../admin/images/btn_del.gif" alt="삭제" align="right" /></div>';
					$j('#refundDiv').append(html);
					$j('#addRefundDay').val('');
					$j('#addRefundPercent').val('');
				}
			}
			
		}

		function addRefundCommi2(){
			var d = parseInt($j('#addRefundDay2').val());
			var p = parseInt($j('#addRefundPercent2').val());
			if(isNaN(p) || p < 1|| p>100){
				alert('수수료를 올바르게 입력하세요.');
				$j('#addRefundPercent2').focus();
			}else{
				var dupvalel = null;
				$j('#refundDiv>div').each(function(idx,el){
					if($j(el).find('input[name^=refundday]').val() == String(d)){
						dupvalel = $j(el);
						return false;
					}
				});
				if(dupvalel){
					alert('중복된 일자가 있습니다. 먼저 중복 항목을 삭제후 추가 하시기 바랍니다.');
				}else{
					html = '<div><input type="hidden" name="refundday[]" value="'+d+'"><input type="hidden" name="refundpercent[]" value="'+p+'"><span style="float:left">당일환불(배송 전) '+p+'%</span><img src="../admin/images/btn_del.gif" alt="삭제" align="right" /></div>';
					$j('#refundDiv').prepend(html);
					$j('#addRefundDay2').val('-1');
					$j('#addRefundPercent2').val('');
				}
			}
			
		}

		function addRefundCommi3(){
			var d = parseInt($j('#addRefundDay3').val());
			var p = parseInt($j('#addRefundPercent3').val());
			if(isNaN(p) || p < 1|| p>100){
				alert('수수료를 올바르게 입력하세요.');
				$j('#addRefundPercent3').focus();
			}else{
				var dupvalel = null;
				$j('#refundDiv>div').each(function(idx,el){
					if($j(el).find('input[name^=refundday]').val() == String(d)){
						dupvalel = $j(el);
						return false;
					}
				});
				if(dupvalel){
					alert('중복된 일자가 있습니다. 먼저 중복 항목을 삭제후 추가 하시기 바랍니다.');
				}else{
					html = '<div><input type="hidden" name="refundday[]" value="'+d+'"><input type="hidden" name="refundpercent[]" value="'+p+'"><span style="float:left">당일환불(배송 후) '+p+'%</span><img src="../admin/images/btn_del.gif" alt="삭제" align="right" /></div>';
					$j('#refundDiv').prepend(html);
					$j('#addRefundDay3').val('0');
					$j('#addRefundPercent3').val('');
				}
			}
			
		}
		</script>
		<table cellpadding="0" cellspacing="0" style="margin-top:7px;padding:7px 7px 7px 7px; border-bottom:0px;border:2px solid #acacac;background-color:#ffffff">
			<tr>
				<th style="width:100px;background:#f9f9f9">취소일</th>
				<td class="norbl" style="padding:5px;">
					<input type="text" name="addRefundDay" id="addRefundDay" value="" style="width:30px;" />
					일전</td>
				<th style="width:100px;background:#f9f9f9">수수료</th>
				<td style="padding:5px;">
					<input type="text" name="addRefundPercent" id="addRefundPercent" value="" style="width:30px;" />
					% </td>
				<td>
					<input type="button" name="addRefundBtn" value="추가" onclick="javascript:addRefundCommi()" />
			</tr>
			<tr>
				<th colspan="2" style="background:#f9f9f9">당일환불(배송 전)</th>
				<th style="background:#f9f9f9">수수료</th>
				<td style="padding:5px;">
					<input type="hidden" name="addRefundDay2" id="addRefundDay2" value="-1" />
					<input type="text" name="addRefundPercent2" id="addRefundPercent2" value="" style="width:50px;" />
					% </td>
				<td>
					<input type="button" name="addRefundBtn" value="추가" onclick="javascript:addRefundCommi2()" />
				</td>
			</tr>
			<tr>
				<th colspan="2" style="background:#f9f9f9">당일환불(배송 후)</th>
				<th style="background:#f9f9f9">수수료</th>
				<td style="padding:5px;">
					<input type="hidden" name="addRefundDay3" id="addRefundDay3" value="0" />
					<input type="text" name="addRefundPercent3" id="addRefundPercent3" value="" style="width:50px;" />
					% </td>
				<td>
					<input type="button" name="addRefundBtn" value="추가" onclick="javascript:addRefundCommi3()" />
				</td>
			</tr>
		</table>

		<div style="width:100%; padding:3px 0px; clear:both" id="refundDiv">
		<? 
		if($_venderdata->refund=="1"){//입점업체 고유설정 사용 선택시
			$refundinfo = venderRefundCommission($_VenderInfo->getVidx(),0);		
		}else{//본사정책에 따름 선택시
			$refundinfo = rentRefundCommission(pick($code,$parentcode));		
		}
		?>
		
			<? if(_array($refundinfo)){
			foreach($refundinfo as $rday=>$rpercent){ ?>
			<div>
				<input type="hidden" name="refundday[]" value="<?=$rday?>">
				<input type="hidden" name="refundpercent[]" value="<?=$rpercent?>">
				<span style="float:left">
				<?
				if($rday==-1){
					echo "당일환불(배송전)";
				}else if($rday==0){
					echo "당일환불(배송후)";
				}else{
					echo $rday."일전";
				}
				?>
				<?=$rpercent?>
				%</span><img src="../admin/images/btn_del.gif" alt="삭제" align="right" /></div>
			<?	}
		}?>
		</div>
	</td>
</tr>

<tr class="rentalItemArea10" style="display:none">
	<th>예약 확정 방식</th>
	<td colspan="3">
		<input type=radio name=booking_confirm value="now" <?if($_venderdata->booking_confirm=="now")echo"checked";?>>결제와 동시  
		<input type=radio name=booking_confirm value="select" <?if($_venderdata->booking_confirm!="now")echo"checked";?>>
		<select name="booking_confirm_time">
			<option value="">선택</option>
			<option value="00:10" <?if($_venderdata->booking_confirm=="00:10")echo"selected";?>>10분</option>
			<option value="00:20" <?if($_venderdata->booking_confirm=="00:20")echo"selected";?>>20분</option>
			<option value="00:30" <?if($_venderdata->booking_confirm=="00:30")echo"selected";?>>30분</option>
			<? for($i=1;$i<=24;$i++){?>
			<option value="<?=sprintf('%02d',$i)?>:00" <?if($_venderdata->booking_confirm==sprintf('%02d',$i).":00")echo"selected";?>><?=$i?>시간</option>
			<? } ?>
		</select>
		이내 확인 알림
	</td>
</tr>
<tr class="rentalItemArea8" style="display:none">
	<th>중도해지시 해약 비용</th>
	<td colspan="3">
		<textarea name="cancel_cont" style="width:80%;height:120px"><?=$_venderdata->cancel_cont?></textarea>
	</td>
</tr>
<tr class="rentalItemArea9" style="display:none">
	<th>제휴카드 할인</th>
	<td colspan="3">
		<textarea name="discount_card" style="width:80%;height:50px"><?=$_venderdata->discount_card?></textarea>
	</td>
</tr>

<?
		$ret['cont'] = ob_get_clean();
		$ret['checkbox'] = '<input type=radio id="goodsType1" name="goodsType" value="1" onclick="toggleGoodsType(\'1\');"><label style=\'cursor:hand;\' for=goodsType1>판매상품</label> &nbsp;	<input type=radio id="goodsType2" name="goodsType" value="2" checked="checked"  onclick="toggleGoodsType(\'2\'); "><label style="cursor:hand;" for=goodsType2>대여상품</label><a href="javascript:document.location.reload()">.</a>';	
	}else{
		$ret['cont'] = '<input type=radio id="goodsType1" name="goodsType" value="1" checked="checked" onclick="toggleGoodsType(\'1\');"><label style=\'cursor:hand;\' for=goodsType1>판매상품</label> ';
		$ret['checkbox'] = '';
	}
}
// php  5.2.0 이상은 추가
$phpVer = str_replace(".","",phpversion());
if( $phpVer >= 520 ) array_walk($ret,'_encode');

echo json_encode($ret);
exit;

?>