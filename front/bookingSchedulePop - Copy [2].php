<?php
/**
 * Created by PhpStorm.
 * User: x2chi-objet
 * Date: 2014-10-14
 * Time: 오후 3:49
 */
	$Dir="../";
	include_once($Dir."lib/init.php");
	include_once($Dir."lib/lib.php");
	if($_REQUEST['isadm'] == '1'){
		include_once("../admin/access.php");
		//	_pr($_usersession);	
	}else{		
		if(_empty($_REQUEST['opt'])) _alert('옵션이 선택 되지 않았습니다.','0');
		$temp = explode(',',$_REQUEST['opt']);
		$opts = array();
		foreach($temp as $tm){
			if(!_empty($tm) && false !== strpos($tm,'|')){
				$ttmp = explode('|',$tm);
				if(_isInt($ttmp[0]) && _isInt($ttmp[1])) $opts[$ttmp[0]] = $ttmp[1];
			}
		}
		if(!_array($opts)) _alert('옵션이 선택 되지 않았습니다.','0');
		if(_isInt($_REQUEST['selOpt'])){
			if(!isset($opts[$_REQUEST['selOpt']])) _alert('잘못된 선택 입니다.','0');
		}else{
			$selOpt = array_shift(array_keys($opts));
		}
	}
	if(_isInt($_REQUEST['selOpt'])){
		$selOpt = $_REQUEST['selOpt'];
	}

	extract($_GET);
	
	if( $pridx > 0 ){
		// 오늘 날짜
		$vdate = $_GET[vdate];
		$selT =  ( empty($vdate) ? time() :strtotime( $vdate."01") );
		
		$prv = date("Ym",strtotime("-1 month",$selT));
		$nxt = date("Ym",strtotime("+1 month",$selT));
		$selY = date("Y",$selT);
		$selM = date("m",$selT);
		$curY = date("Y");
		$curM = date("m");
		$monthDays = date("t",$selT);
		$firstw = date('w',strtotime(date('Y-m-01',$selT)));
		$looprow = ceil(($monthDays + $firstw)/7);

		$pinfo = &rentProduct::read($pridx);		
		$optidxs = array_keys($pinfo['options']);
		rentProduct::schedule($pridx,date('Ym01',$selT),date('Ymt',$selT));		
		
		$optidxs = array_keys($pinfo['options']);		
		if($_REQUEST['isadm'] == '1'){
			$opts[$selOpt] = 0;
		}else{	
			$optidxs = array_intersect(array_keys($opts),$optidxs);			
			if(!in_array($selOpt,$optidxs)) _alert('대상 옵션을 찾을 수 없습니다.','0');			
		}
		if(!_isInt($selOpt)) $selOpt = $optidxs[0];

		$schedulebyday = array();
	
		if($pinfo['codeinfo']['pricetype'] == 'time'){
			$datekey = 'Y-m-d H';
			$optrows = 24;
		}else{
			$datekey = 'Y-m-d';
			$optrows = 1;
		}
		
//		_pr($pinfo);
?>

<html>
	<head>
		<title>렌탈/예약 현황보기</title>
		<meta http-equiv="CONTENT-TYPE" content="text/html;charset=EUC-KR">
		<style>
			BODY,DIV,form,TEXTAREA,center,option,pre,blockquote,table{font-family:"굴림","돋움";color:#4B4B4B;font-size:12px;line-height:120%;}
			h2{background:url('/data/design/img/sub/tit_pop_bg.gif') repeat-x;}

			A:link    {color:#635C5A;text-decoration:none;}
			A:visited {color:#545454;text-decoration:none;}
			A:active  {color:#5A595A;text-decoration:none;}
			A:hover  {color:#545454;text-decoration:underline;}

			.tableBase{border-top:1px solid #b9b9b9;font-size:12px;}
			.tableBase th{padding:8px 0px;border-bottom:1px solid #ededed;border-left:1px solid #ededed;background:#f8f8f8;}
			.tableBase .firstTh{border-left:none;background:#f8f8f8;}
			.tableBase td{padding:8px 0px;border-bottom:1px solid #ededed;border-left:1px solid #ededed;}
			.tableBase .firstTd{border-left:none;}

			.tableBaseSe{font-size:12px;}
			.tableBaseSe caption{padding:8px;background:#ededed;border-bottom:1px solid #d9d9d9;}
			.tableBaseSe th{padding:8px 0px;border-right:1px solid #ededed;border-bottom:1px solid #ededed;background:#f5f5f5;text-align:center;}
			.tableBaseSe .lastTh{border-right:none;}
			.tableBaseSe td{padding:8px 0px;border-right:1px solid #ededed;border-bottom:1px solid #ededed;text-align:center;}
			.tableBaseSe .lastTd{border-right:none;}
			
			
			.oddline{ background:#efefef;}
			.rentfull{ background:#ff0000; text-align:center}
			.rentfull.old{ background:#B5B3B3; text-align:center}
			.rentable{ text-align:center;}
			.rentfull.today{ background:#FFC;}
			.rentable.today{ background:#FF3}
			
			.wing{ position:fixed; top:0px;}
		</style>

		<script type="text/javascript" src="/js/jquery-1.10.2.min.js"></script>
		<script type="text/javascript">
			<!--
			var $j = jQuery.noConflict();
			var $rtop = 0;
			var $wing = false;
			// 마우스 따라다니는 레이어
			jQuery(document).ready(function(){
				$j(document).mousemove(function(e){
					var leftP = ( ( $j(document).width() - 600 ) < e.pageX ) ? $j(document).width()-625 : e.pageX ;
					$j('#viewInfo').css("left",leftP-20);
					$j('#viewInfo').css("top",e.pageY+15);
				});
				
				$rtop = $j('#schedultTbl').offset().top;
			})

			// 레이어 내용채워 보이기
			function viewInfo( idx ) {
				$j('#viewInfo').css("display","block");
				$j('#viewInfo').html($j('#bookingInfo_'+idx).html());
			}

			// 레이어 내용비우고 가리기
			function offInfo( idx ) {
				$j('#viewInfo').css("display","none");
				$j('#viewInfo').html("");
			}
			
			<? if(!_empty($usersession->shopdata->id)){ ?>

			<? } ?>
			/*
			$j(window).scroll(function(event){		
				var ntop = $j('body').scrollTop();
				if($rtop <= ntop && !$wing){
					$j('#schedultTbl').find('thead:eq(0)').addClass('wing');
					$wing = true;
				}else if($wing){
					$j('#schedultTbl').find('thead:eq(0)').removeClass('wing');
					$wing = false;
				}
			});*/
			-->
		</script>
	</head>
	
	<body topmargin="0" leftmargin="0" rightmargin="0">
		<div style="text-align:center;">
			<h2>
				<div style="float:left;"><img src="/data/design/img/sub/tit_rentalchart.gif" alt="예약/렌탈 현황보기" /></div>
				<div style="float:right;margin-top:14px;margin-right:14px;"><a href="javascript:self.close();"><img src="/data/design/img/sub/btn_pop_close.gif" border="0" alt="" /></a><!--<input type="button" value="닫기" onclick="self.close();">--></div>
				<div style="clear:both;"></div>
			</h2>
			<!--<strong>예약현황</strong>-->
		<table border="0" cellpadding="0" cellspacing="0" style="width:100%">
			<tr>
				<th style="width:33%">
				<? if($prv >= date('Ym')){ ?>				
				<a href="?vdate=<?=$prv?>&pridx=<?=$pridx?>&opt=<?=$_REQUEST['opt']?>">이전달</a>
				<? } ?>
					
				</th>
				<th style="width:34%">[<a href="?vdate=<?=$curY.$curM?>&pridx=<?=$pridx?>&opt=<?=$_REQUEST['opt']?>">이번달</a>]</th>
				<th style="width:33%"><a href="?vdate=<?=$nxt?>&pridx=<?=$pridx?>&opt=<?=$_REQUEST['opt']?>">다음달</a></th>
			</tr>
			<tr><th colspan="3" style="height:5px;"></th></tr>
			<tr>
				<th style="font-weight:normal"><? if($prv >= date('Ym')){ ?><a href="?vdate=<?=$prv?>&pridx=<?=$pridx?>&opt=<?=$_REQUEST['opt']?>"><?=substr($prv,-2)?>월</a><? } ?></th>
				<th style="font-weight:13px;"><?=$selY.'년'.$selM?>월</th>
				<th style="font-weight:normal"><a href="?vdate=<?=$nxt?>&pridx=<?=$pridx?>&opt=<?=$_REQUEST['opt']?>"><?=substr($nxt,-2)?>월</a></th>
			</tr>
		</table>
		<? if(count($optidxs) > 1){ ?>
		<form name="changeTarget" method="get" action="<?=$_SERVER['PHP_SELF']?>" style="float:left; clear:both">
			<input type="hidden" name="opt" value="<?=$_REQUEST['opt']?>">
			<input type="hidden" name="pridx" value="<?=$_REQUEST['pridx']?>">
			<input type="hidden" name="vdate" value="">			
			<select name="selOpt">
				<? foreach($optidxs as $optidx){ ?>
				<option value="<?=$optidx?>"><?=rentProduct::_status($pinfo['options'][$optidx]['grade'])?><? if(!_empty($pinfo['options'][$optidx]['optionName'])) echo '('.$pinfo['options'][$optidx]['optionName'].')'; ?></option>
				<?	} ?>
			</select>
		</form>
		<? } ?>
		<div id="viewInfo" style="display:none;position:absolute;top:100px;left:100px;width:600;padding:10px;background:#ffffff;border:2px solid #999999;z-index:999;overflow:hidden;"></div>
		<style type="text/css">
		.calendarTbl{}
		.calendarTbl .sun{ color:red;}
		.calendarTbl .sat{ color:blue;}
		.calendarTbl th{ width:100px;}
		.calendarTbl tbody td { text-align:center; height:100px; position:relative}
		.calendarTbl tbody td.rentableday{ background:#80FF80}
		.calendarTbl tbody td.rentableday2{ background:#F90}
		.dinfoArea{ border:1px solid #ff0000; background:#FF9; color:blue; position:absolute; left:0px; top:10px; width:200px; display:none}
		</style>
		<script language="javascript" type="text/javascript">
		$j(function(){
			$j('.rentableday,.rentableday2').on('mouseover',function(){
				$j('.dinfoArea').css('display','none');
				$j(this).find('.dinfoArea').css('display','');
			});
		});
		</script>
		<table border="0" cellpadding="0" cellspacing="0" class="tableBase calendarTbl" style="clear:both">
			<thead>
				<tr>
					<th class="sun">일</th>
					<th>월</th>
					<th>화</th>
					<th>수</th>
					<th>목</th>
					<th>금</th>
					<th class="sat">토</th>
				</tr>
			</thead>
			<tbody>
			<?
		//	_pr($pinfo);
			for($i=0;$i<$looprow;$i++){
				echo '<tr>';
				for($j=0;$j<7;$j++){
					$day = $i*7+$j-$firstw+1;
					if($day < 1 || $day > $monthDays) $daystr = '&nbsp;';
					else $daystr = $day;
					$class = ($j > 0)?($j ==6 )?'sat':'':'sun';
					
					$fixdate = date('Y-m-'.sprintf('%02d',$day),$selT);					
					$dinfostr = '';
					if($day <= $monthDays && $fixdate > date('Y-m-d')){												
						echo $fixdate;
						$minfix = 0;
						if($optrows > 1){ // 24시간제
							$scarr = array();
							$ststr = NULL;
							$chkcnt = 0;							
							$rangeCheckcnt=0;
							for($jj=0;$jj<24;$jj++){
								$key = $fixdate.' '.sprintf('%02d',$jj);
								$disablecnt = $pinfo['optschedule'][$selOpt][$key];
								$ablecnt = $pinfo['options'][$selOpt]['productCount']-$disablecnt;
								
								if($ablecnt > 0 && $ablecnt > $opts[$selOpt]){									
									$chkcnt++;									
									if(_empty($ststr)){
										$ststr = $jj;
										$rangeCheckcnt = $ablecnt;
									}else if($rangeCheckcnt != $ablecnt){
										$scarr[sprintf('%02d',$ststr).'~'.sprintf('%02d',$jj)]= $rangeCheckcnt;
										$ststr = $jj;
										$rangeCheckcnt = $ablecnt;
									}
									if($minfix <1) $minfix = $ablecnt;
									else if($minfix > $ablecnt) $minfix = $ablecnt;									
								}else{
									if(_empty($ststr)){
										$scarr[sprintf('%02d',$ststr).'~'.sprintf('%02d',$jj)]= $rangeCheckcnt;
										$ststr = NULL;
									}
								}
							}	
							if($chkcnt ==24){
								$class.=' rentableday';
								$dinfostr = '<div class="dinfoArea">'.$minfix.'개 대여가능/'.$pinfo['options'][$selOpt]['productCount'].'</div>';
							}else if($chkcnt < 1){
								
							}else if(_array($scarr)){
								$class.=' rentableday2';
								$dinfostr = '<div class="dinfoArea">';
								foreach($scarr as $dkey=>$rangeCheckcnt){
									$dinfostr .= $dkey.'시 '.$rangeCheckcnt.'개 /'.$pinfo['options'][$selOpt]['productCount'];
								}
								$dinfostr .= '</div>';
							}
						}else{ // 1일
							$key = $fixdate;
							$disablecnt = $pinfo['optschedule'][$selOpt][$key];
							$ablecnt = $pinfo['options'][$selOpt]['productCount']-$disablecnt;
							if($ablecnt > 0 && $ablecnt > $opts[$selOpt]){
								$minfix = $ablecnt;
								$class.=' rentableday';
								$dinfostr = '<div class="dinfoArea">'.$minfix.'개 대여가능/'.$pinfo['options'][$selOpt]['productCount'].'</div>';
							}else{
								
							}
						}
					}
					echo '<td class="'.$class.'">'.$dinfostr.$daystr.'</td>';
				}
				echo '</tr>';
			}
			?>
			</tbody>
		</table>
		<table border="0" cellspacing="0" cellpadding="0" align="center" width="100%" class="tableBase" id="schedultTbl">
			<thead>
				<tr>
					<th class="firstTh" style="width:100px;">상품명</th>
					<th style="width:100px">옵션</th>
					<? if($optrows > 1){ ?><th style="width:40px;">시간</th><? } ?>
					<? // 일자
					for( $dd = 1 ; $dd <= $monthDays ; $dd++) {
						$dayOfWeek = date("w",strtotime($selY."-".$selM."-".$dd)); //요일
						// 요일 및 휴무일 컬러 휴무일>일요일>토요일 (휴무일:(일요일:토요일))
						$dayOfWeekColor = (strlen($dayOffs[0])==0?($dayOfWeek==0?"#FF8888":($dayOfWeek==6?"8888FF":"")):"#FF6600");
						
						echo "<th style=\"width:40px;\"><span style='color:".$dayOfWeekColor.";'>". str_pad($dd, 2, "0", STR_PAD_LEFT) ."</span></th>";
					}
					?>
				</tr>
			</thead>
			<tbody>
			<?
			// 상품 리스트
			$isfirst = true;
			$prefix = date('Y-m',$selT);
			foreach($pinfo['options'] as $opt){
			?>	
				<? if($isfirst){ ?><tr><td class="firstTd" align="left" rowspan="<?=count($pinfo['options'])*$optrows?>" style="width:100px;"><?=$pinfo['productname']?></td><? $isfirst= false; }else{ ?><tr><? } ?>
				<td align="left" rowspan="<?=$optrows?>" style="width:100px;"><?=$opt['optionName']?></td>
				<?				
				if($optrows > 1){ // 24시간제
					for($i=0;$i<$optrows;$i++){
						if($i >0) echo '<tr>';
						$class0 = ($i%2)?'oddline':'';
						
						echo '<td class="'.$class0.'" style="text-align:center; font-weight:bold; width:40px">'.sprintf('%02d',$i).'</td>';
						for( $dd = 1 ; $dd <= $monthDays ; $dd++) {
							$key = $prefix.'-'.sprintf('%02d',$dd).' '.sprintf('%02d',$i);
							$cnt =$pinfo['schedule'][$key][$opt['idx']];
							$class1= '';							
							if($key <= date('Y-m-d H') || $pinfo['schedule'][$key][$opt['idx']] >= $opt['productCount']){
								$class1= ' rentfull';
								if($key <= date('Y-m-d H')) $class1 .= ' old';								
							}else{
								$class1 = ' rentable';
							}
							if(!_isInt($cnt)) $cnt = '-';
							
							if(substr($key,0,10) == date('Y-m-d')) $class1.= ' today';
							
							$class = $class0.' '.$class1;
							echo '<td class="'.$class.'">'.$cnt.'</td>';
						}
						if($i <$optrows-1) echo '</tr>';
					}
				}else{ // 하루단위
					for( $dd = 1 ; $dd <= $monthDays ; $dd++) {
						$key = $prefix.'-'.sprintf('%02d',$dd);
						$cnt =$pinfo['schedule'][$key][$opt['idx']];
						if(empty($pinfo['schedule'][$key][$opt['idx']])) $cnt = '&nbsp;';
						else{
							if($pinfo['schedule'][$key][$opt['idx']] >= $opt['productCount']){
								$class.= ' rentfull';
								$cnt = 'X';
							}else{
								$class.= ' rentable';
								$cnt = $pinfo['schedule'][$key][$opt['idx']].'/'.$opt['productCount'];
							}
						}
						
						echo '<td class="'.$class.'">'.$cnt.'</td>';
					}
				}
				?>
				</tr>
		<?	}
			?>
		</tr>
		</table>

		</div>
	</body>
</html>
<? } ?>