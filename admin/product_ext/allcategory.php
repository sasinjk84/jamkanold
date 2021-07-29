<?
$sql = "select c.codeA,c.codeB,c.codeC,c.codeD,c.code_name,c.reseller_reserve,r.* from tblproductcode c left join code_rent as r on r.code = concat(c.codeA,codeB,codeC,codeD) where c.type like 'L%' and c.codeA >'002' and c.codeA < '899' order by codeA,codeB,codeC,codeD,c.sequence desc";

if(false === $res = mysql_query($sql,get_db_conn())) exit(mysql_error());

$categorys = array();
while($row = mysql_fetch_assoc($res)){	
	$depth = 0;
	$code = '';
	for($i=0;$i<4;$i++){		
		$code .= $row['code'.chr(65+$i)];
		if($depth <1 && $row['code'.chr(65+$i)] == '000'){
			$depth = $i;
		}
	}
	$categorys[$code] = $row;		
	$categorys[$code]['parents'] = array();
	for($i=1;$i<$depth;$i++){
		$pcode = str_pad(substr($code,0,$i*3),12,'0');
		array_push($categorys[$code]['parents'],$pcode);
	}
}

?>
<script language="javascript" type="text/javascript" src="/js/jquery-1.10.2.js"></script>
<style type="text/css">
.tblStyle_List{}
.tblStyle_List{ border-top:1px solid #ccc; border-left:1px solid #ccc; font-size:12px}
.tblStyle_List caption{ text-align:left; background:#333; color:white; font-weight:bold; padding:5px 0px 5px 5px;}
.tblStyle_List thead th{ font-weight:normal; background:#dfdfdf; border-right:1px solid #ccc; border-bottom:1px solid #ccc;height:28px;}
.tblStyle_List thead td{ font-weight:normal; background:#fff; border-right:1px solid #ccc; border-bottom:1px solid #ccc; padding:0px 5px;}
.tblStyle_List tbody th{ font-weight:normal; background:#dfdfdf; border-right:1px solid #ccc; border-bottom:1px solid #ccc;height:28px;}
.tblStyle_List tbody td{ font-weight:normal; background:#fff; border-right:1px solid #ccc; border-bottom:1px solid #ccc; padding:5px; height:28px; line-height:140%; font-size:13px}

</style>
<table border="0" cellpadding="0" cellspacing="0" class="tblStyle_List">
	<thead>
		<tr>
			<th>카테고리명</th>
			<th style="width:100px;">수수료</th>
			<th style="width:100px;">회원등급별<br />할인율</th>
			<th>가격구분</th>
			<th style="width:120px;">환불</th>
			<th style="width:120px;">장기할인</th>
			<th style="width:70px;">추천인적립</th>
			<th style="width:120px;">성수기</th>
		</tr>
	</thead>
	<tbody>
	<?	foreach($categorys as $code=>$val){ 
			$name = '';
			$style = '';
			if(_array($val['parents'])){
				foreach($val['parents'] as $pcode){
					$name .= $categorys[$pcode]['code_name'].' > ';
				}
			}else{
				$style = ' background:#efefef;';
			}
			$name .= $val['code_name'];
			
			$refunds = array();
			$rsql = "select * from rent_refund where code = '".$code."' order by day asc";
			if(false !== $rres = mysql_query($rsql,get_db_conn())){
				if(mysql_num_rows($rres)){
					while($rrow = mysql_fetch_assoc($rres)){
						$refunds[] = $rrow['day'].' 일전 '.$rrow['percent'].'%';
					}
				}
			}			
			$groupdiscount = getGroupDiscounts($code);			
			$gdiscountarr = array();
			if(_array($groupdiscount)){
				foreach($groupdiscount as $discount){					
					array_push($gdiscountarr,$discount['group_name'].' : '.($discount['discount']*100).'%');
				}
			}

			
			$longdiscount = array();
			$rsql = "select * from rent_longdiscount where code = '".$code."' order by day asc";
			if(false !== $rres = mysql_query($rsql,get_db_conn())){
				if(mysql_num_rows($rres)){
					while($rrow = mysql_fetch_assoc($rres)){
						$longdiscount[] = $rrow['day'].' 일 '.$rrow['percent'].'% 할인';
					}
				}
			}
	?>	
	<tr>
		<td style="<?=$style?>"><?=$name?></td>
		<td style="<?=$style?>">셀프 : <? echo (_isInt($val['commission_self']))?$val['commission_self']:'<span style="color:#0c0">15</span>'; ?>%<br>			
			위탁 : <? echo (_isInt($val['commission_main']))?$val['commission_main']:'<span style="color:#0c0">12</span>'; ?>%
			<div style="text-align:center"><input type="button" value="설정"  code="<?=$code?>" class="commiBtn" /></div>
		</td>
		<td style="<?=$style?>">
		<? echo implode('<br>',$gdiscountarr); ?>
		<div style="text-align:center"><input type="button" value="설정" style="" code="<?=$code?>" class="gdiscountBtn" /></div>
		</td>
		<td style="<?=$style?>">
			<select name="pricetype[]" code="<?=$code?>" orv="<?=$val['pricetype']?>">
				<option value="">--가격 구분--</option>
				<option value="day" <? if($val['pricetype'] == 'day'){?> selected <? } ?>>일일대여(24시간)</option>
				<option value="time" <? if($val['pricetype'] == 'time'){?> selected <? } ?>>시간제 대여</option>				
				<option value="checkout" <? if($val['pricetype'] == 'checkout'){?> selected <? } ?>>체크인/아웃(PM 2~AM 11)</option>
			</select>
			<div style="text-align:center"><input type="button" value="저장" style=" display:none;" code="<?=$code?>" class="priceBtn" /></div>
		</td>
		<td style="<?=$style?>">
			<? if(_array($refunds)){ 
					echo implode('<br>',$refunds);
			}else{ ?>
			-----
			<? } ?>
			<br>
			<div style="text-align:center"><input type="button" value="설정" style="" code="<?=$code?>" class="refundBtn" /></div>
		</td>
		<td style="<?=$style?>">
		<? if(_array($longdiscount)){ 
					echo implode('<br>',$longdiscount);
			}else{ ?>
			&nbsp;
			<? } ?>
			<br>
			<div style="text-align:center"><input type="button" value="설정" code="<?=$code?>" class="discountBtn" /></div>
		</td>
		<td style="<?=$style?>text-align:center">
			<input type="tel" name="reseller_reserve<?=$code?>" orv="<?=$val['reseller_reserve']*100?>" value="<?=$val['reseller_reserve']*100?>" style="text-align:right; width:30px;" />%
			<div style="text-align:center"><input type="button" value="저장" style=" display:none;" code="<?=$code?>" class="reserveBtn" /></div>
		</td>
		<td style="<?=$style?> text-align:left; padding-left:5px;">
			<input type="radio" name="useseason_<?=$code?>" value="0" <? if($val['useseason']!='1'){?> checked <? } ?> />성수기사용안함<br /><input type="radio" name="useseason_<?=$code?>" <? if($val['useseason']=='1'){?> checked="checked" <? } ?>   value="1" />성수기사용
			
			<div id="seasonDiv<?=$code?>" class="seasonDiv" code="<?=$code?>" style="border:1px solid #efefefe; margin-top:10px; margin-bottom:10px; display:none">
				<input type="button" value="성수기/준성수기 관리" style="width:100%;" onclick="window.open('product_seasonpop.php?code=<?=$code?>', 'busySeasonPop', 'width=800,height=600' );">				
			</div>
		</td>
	</tr>
	<? } ?>
	</tbody>
</table>
<script language="javascript" type="text/javascript">
$(function(){
	$('input[name^=useseason]:checked').each(function(idx,el){
		if($(el).val() == '1'){
			$(el).parent().find('.seasonDiv').css('display','block');
		}
	});
	
	
	$('input[name^=useseason]').on('click',function(e){
	//	e.preventDefault();	
		divel = $(this).parent().find('.seasonDiv');
		$this = $(this);
		if(divel){
			code = $(divel).attr('code');
			$.post('/admin/product_ext/ajax.php',{'act':'setuseseason','code':code,'useseason':$this.val()},
				function(data){
					if(data.err != 'ok') alert(data.err);
					else{						
						if($this.val() == '1'){
							$(divel).css('display','block');
						}else{
							$(divel).css('display','none');
						}
					}
				},'json');
		}
	});
	
	$('select[name^=pricetype]').on('change',function(e){
		$this = $(this);
		$orv = $this.attr('orv');
		$code = $this.attr('code');
		if($orv != $this.val() && $this.val()!= ''){
			$this.parent().find('.priceBtn').css('display','block');
		}else{
			$this.parent().find('.priceBtn').css('display','none');
		}
	});
		
	
	$('.priceBtn').on('click',function(e){
		e.preventDefault();
		$this = $(this);
		$sel = $(this).parent().parent().find('select[name^=pricetype]');
		$orv = $($sel).attr('orv');	
		
		$code = $this.attr('code');
		if($orv != $sel.val()){
			$.post('/admin/product_ext/ajax.php',{'act':'setpricetype','code':$code,'pricetype':$sel.val()}
				,function(data){
					if(data.err != 'ok'){
						alert(data.err);
					}else{
						$sel.attr('orv',$sel.val());
						$this.css('display','none');
					}
				},'json');
		}
	});
	
	$('.commiBtn').on('click',function(e){
		e.preventDefault();
		code = $(this).attr('code');		
		window.open('product_ext/pop_commi.php?code='+code,'modifyPercentPop', 'width=200,height=200');
	});
	
	$('.discountBtn').on('click',function(e){
		e.preventDefault();
		code = $(this).attr('code');		
		window.open('product_ext/pop_rentpercent.php?type=discount&code='+code,'modifyPercentPop', 'width=400,height=400');
	});
	
	$('.refundBtn').on('click',function(e){
		e.preventDefault();
		code = $(this).attr('code');		
		window.open('product_ext/pop_rentpercent.php?type=refund&code='+code,'modifyPercentPop', 'width=400,height=400');
	});
	
	
	$('.gdiscountBtn').on('click',function(e){
		e.preventDefault();
		code = $(this).attr('code');		
		window.open('product_ext/pop_gdiscount.php?code='+code,'modifyGdiscountPop', 'width=200,height=300');
	});
	
	$('input[name^=reseller_reserve]').on('change',function(e){
		$this = $(this);
		$orv = $this.attr('orv');
		$code = $this.attr('code');
		if($orv != $this.val() && $this.val()!= ''){
			$this.parent().find('.reserveBtn').css('display','block');
		}else{
			$this.parent().find('.reserveBtn').css('display','none');
		}
	});
	
	$('.reserveBtn').on('click',function(e){
		e.preventDefault();
		$this = $(this);
		$sel = $(this).parent().parent().find('input[name^=reseller_reserve]');
		$orv = $($sel).attr('orv');
		$code = $this.attr('code');
		if($orv != $sel.val()){
			$.post('/admin/product_ext/ajax.php',{'act':'setreseller_reserve','code':$code,'reseller_reserve':$sel.val()}
				,function(data){
					if(data.err != 'ok'){
						alert(data.err);
					}else{
						$sel.attr('orv',$sel.val());
						$this.css('display','none');
						alert('수정완료');
					}
				},'json');
		}
	});
	
	
	
});
</script>

		