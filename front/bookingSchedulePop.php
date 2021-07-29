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
		rentProduct::schedule($pridx,date('Ym01',$selT),date('Ymt',$selT),"cal");		
		
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
		<link type="text/css" rel="stylesheet" href="/css/common.css" >
		<style>
			h2{background:url('/data/design/img/sub/tit_pop_bg.gif') repeat-x;}

			A:link    {color:#635C5A;text-decoration:none;}
			A:visited {color:#545454;text-decoration:none;}
			A:active  {color:#5A595A;text-decoration:none;}
			A:hover  {color:#545454;text-decoration:underline;}

			.tableBase{border-top:1px solid #b9b9b9;font-size:12px;}
			.tableBase th{padding:8px 0px;border-bottom:1px solid #ededed;border-left:1px solid #ededed;background:#f8f8f8;text-align:center;}
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
		<table border="0" cellpadding="0" cellspacing="0" style="width:100%;margin:5px 0px;">
			<tr>
				<th style="font-weight:normal;text-align:center;"><? if($prv >= date('Ym')){ ?><a href="?vdate=<?=$prv?>&pridx=<?=$pridx?>&opt=<?=$_REQUEST['opt']?>"><?=substr($prv,-2)?>월</a><? } ?></th>
				<th style="font-weight:bold;font-size:17px;text-align:center;color:#568EF5;"><?=$selY.'년'.$selM?>월</th>
				<th style="font-weight:normal;text-align:center;"><a href="?vdate=<?=$nxt?>&pridx=<?=$pridx?>&opt=<?=$_REQUEST['opt']?>"><?=substr($nxt,-2)?>월</a></th>
			</tr>
			<tr>
				<th style="width:33%;text-align:center;font-size:13px;font-weight:normal;">
				<? if($prv >= date('Ym')){ ?>				
				<a href="?vdate=<?=$prv?>&pridx=<?=$pridx?>&opt=<?=$_REQUEST['opt']?>"><span style="color:#cccccc;">이전달</span></a>
				<? } ?>
					
				</th>
				<th style="width:34%;text-align:center;font-size:13px;font-weight:normal;color:#cccccc;"><a href="?vdate=<?=$curY.$curM?>&pridx=<?=$pridx?>&opt=<?=$_REQUEST['opt']?>"><span style="color:#cccccc;">이번달</span></a></th>
				<th style="width:33%;text-align:center;font-size:13px;font-weight:normal;color:#cccccc;"><a href="?vdate=<?=$nxt?>&pridx=<?=$pridx?>&opt=<?=$_REQUEST['opt']?>"><span style="color:#cccccc;">다음달</span></a></th>
			</tr>
			<tr><th colspan="3" style="height:5px;"></th></tr>
		</table>
		<? if(count($optidxs) > 1){ ?>
		<form name="changeTarget" method="get" action="<?=$_SERVER['PHP_SELF']?>" style="float:left; clear:both">
			<input type="hidden" name="opt" value="<?=$_REQUEST['opt']?>">
			<input type="hidden" name="pridx" value="<?=$_REQUEST['pridx']?>">
			<input type="hidden" name="vdate" value="">			
			<select name="selOpt" onchange="document.changeTarget.submit()">
				<? foreach($optidxs as $optidx){ ?>
				<option value="<?=$optidx?>" <?=$selOpt==$optidx? "selected":""?>><?=rentProduct::_status($pinfo['options'][$optidx]['grade'])?><? if(!_empty($pinfo['options'][$optidx]['optionName'])) echo '('.$pinfo['options'][$optidx]['optionName'].')'; ?></option>
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
		.calendarTbl tbody td.rentableday{ background:#f2f2f2;}
		.calendarTbl tbody td.rentableday2{ background:#568EF5;}
		.dinfoArea{border:1px solid #444444; background:#fff;  position:absolute; left:2px; top:60px; width:130px; display:none; z-index:999;padding:8px;border-radius:4px;text-align:center;font-size:12px;}
		</style>
		<script language="javascript" type="text/javascript">
		var doch = $j(document).innerHeight();
		var docw = $j(document).innerWidth();
		
		$j(function(){
			$j('.rentableday,.rentableday2').on('mouseover',function(){				
				$j('.dinfoArea').css('display','none');
				var dw = $j(this).find('.dinfoArea').innerWidth();
				var dh = $j(this).find('.dinfoArea').innerHeight();
				var position = $j(this).position();
				
				if(position.left + dw + 10 > docw) $j(this).find('.dinfoArea').css('left',(docw-(position.left + dw +20)));
				if(position.top + dh +60 > doch) $j(this).find('.dinfoArea').css('top',( doch-(position.top + dh +60)));
				
				$j(this).find('.dinfoArea').css('display','block');
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
					
					if($pinfo['today_reserve']=="Y"){//당일예약가능인 경우
						$today = date('Y-m-d');
					}else{
						$today = date("Y-m-d",strtotime('+1 day'));
					}
					if($day > 0 && $day <= $monthDays && $fixdate >= $today){												
						$minfix = 0;
						if($optrows > 1){ // 1시간제
							$scarr = array();
							$ststr = NULL;
							$chkcnt = 0;							
							$rangeCheckcnt=0;
							for($jj=0;$jj<25;$jj++){
								$key = $fixdate.' '.sprintf('%02d',$jj);
								$disablecnt = $pinfo['optschedule'][$selOpt][$key];
								$ablecnt = $pinfo['options'][$selOpt]['productCount']-$disablecnt;
								if($ablecnt > 0 && $ablecnt >= $opts[$selOpt]){		
									$chkcnt++;									
								}

								if(_empty($ststr)){
									$ststr = $jj;
									$rangeCheckcnt = $ablecnt;
								}else if($rangeCheckcnt != $ablecnt){
									$scarr[sprintf('%02d',$ststr).'~'.sprintf('%02d',$jj)]= $rangeCheckcnt;
									$ststr = $jj;
									$rangeCheckcnt = $ablecnt;
								}else{

									$scarr[sprintf('%02d',$ststr).'~'.sprintf('%02d',$jj)]= $rangeCheckcnt;
									$ststr = $jj;
									$rangeCheckcnt = $ablecnt;
								}
								if($minfix <1) $minfix = $ablecnt;
								else if($minfix > $ablecnt) $minfix = $ablecnt;	



/*
								if($ablecnt > 0 && $ablecnt >= $opts[$selOpt]){									
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
								//	if(_empty($ststr)){
										//$scarr[sprintf('%02d',$ststr).'~'.sprintf('%02d',$jj)]= $rangeCheckcnt;
								//		$ststr = NULL;
								//	}
								//	$ststr = $jj;
										
									if(_empty($ststr)){
										$ststr = $jj;
										$rangeCheckcnt = $ablecnt;
									}else if($rangeCheckcnt != $ablecnt){
										$scarr[sprintf('%02d',$ststr).'~'.sprintf('%02d',$jj)]= $rangeCheckcnt;
										$ststr = $jj;
										$rangeCheckcnt = $ablecnt;
									}
								}
								*/
							}	
							if($chkcnt == 25){
								$class.=' rentableday';
								//$dinfostr = '<div class="dinfoArea">'.$minfix.'개 대여가능/'.$pinfo['options'][$selOpt]['productCount'].'</div>';
								$dinfostr = '<div class="dinfoArea">'.$minfix.'개 대여가능</div>';
							}else if($chkcnt < 1){
								
							}else if(_array($scarr)){
								$class.=' rentableday2';
								$dinfostr = '<div class="dinfoArea">';
								foreach($scarr as $dkey=>$rangeCheckcnt){
									//$dinfostr .= $dkey.'시 '.$rangeCheckcnt.'개 /'.$pinfo['options'][$selOpt]['productCount']."<br>";
									$dinfostr .= $dkey.'시 '.$rangeCheckcnt.'개 대여가능<br>';
								}
								$dinfostr .= '</div>';
							}
						}else{ // 1일(24시간제)
							$key = $fixdate;
							$disablecnt = $pinfo['optschedule'][$selOpt][$key];
							$ablecnt = $pinfo['options'][$selOpt]['productCount']-$disablecnt;
							
							if($ablecnt > 0 && $ablecnt >= $opts[$selOpt]){

								$minfix = $ablecnt;
								$class.=' rentableday';
								//$dinfostr = '<div class="dinfoArea">'.$minfix.'개 대여가능/'.$pinfo['options'][$selOpt]['productCount'].'</div>';
								$dinfostr = '<div class="dinfoArea">'.$minfix.'개 대여가능</div>';
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
		</div>
	</body>
</html>
<? } ?>