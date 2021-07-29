<?php
/**
 * Created by PhpStorm.
 * User: x2chi-objet
 * Date: 2014-10-30
 * Time: ���� 10:30
 */

$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/ext/func.php");

if(!preg_match('/^[0-9]{12,18}$/',$_REQUEST['code'])){
	_alert('��� ī�װ��� ���� ���� �ʾҽ��ϴ�.',0);
	exit;
}
$_REQUEST['code'] = substr($_REQUEST['code'],0,12);

//extract($_REQUEST);

$pridx = $_GET['pridx']? $_GET['pridx'] : 0;
$vender = $_GET['vender'];



if( $_GET['vdate'] ) {
	$chan_Y =substr($_GET['vdate'],0,4);
	$chan_M =substr($_GET['vdate'],4,2);
	$chan_D =substr($_GET['vdate'],6,2);
	$vdate = $_GET['vdate'];
} else {
	$chan_Y=date("Y");
	$chan_M=date("m");
	$chan_D=date("d");
	$vdate = $chan_Y.$chan_M.$chan_D;
}

$chkDate = $chan_Y."�� ".($chan_M?$chan_M."�� ":"").($chan_D?$chan_D."��":"");



$t=mktime(0,0,0,$chan_M,1,$chan_Y);
$week=date("w",$t);
$lastday=date("t",$t);
$day=1;


// �ָ� ��� ���� (��� ��,�Ͽ���)
$seasonSet = seasonSet();

?>


<html>
	<head>
		<title>���� ���� �޷º���</title>
		<link type="text/css" rel="stylesheet" href="/css/common.css" >
		<style>
			h2{background:url('/data/design/img/sub/tit_pop_bg.gif') repeat-x;}



			.tableBase{border-top:1px solid #b9b9b9;font-size:12px;}
			.tableBase th{padding:8px 0px;border-bottom:1px solid #ededed;border-left:1px solid #ededed;background:#f8f8f8;}
			.tableBase .firstTh{border-left:none;background:#f8f8f8;}
			.tableBase td{padding:8px;border-bottom:1px solid #ededed;border-left:1px solid #ededed;}
			.tableBase .firstTd{border-left:none;}
		</style>

		<script language="JavaScript">
			<!--
			// Ķ���� ��/�� �ٲ�
			function dateChg ( date ) {
				location.href="?code=<?=$_REQUEST['code']?>&vender=<?=$vender?>&pridx=<?=$pridx?>&vdate="+date;
			}
			-->
		</script>
	</head>

	<body topmargin="0" leftmargin="0" rightmargin="0">
		<h2>
			<div style="float:left;"><img src="/data/design/img/sub/tit_seasonchart.gif" alt="��������޷º���" /></div>
			<div style="float:right;margin-top:14px;margin-right:14px;"><a href="javascript:self.close();"><img src="/data/design/img/sub/btn_pop_close.gif" border="0" alt="" /></a><!--<input type="button" value="�ݱ�" onclick="self.close();">--></div>
			<div style="clear:both;"></div>
		</h2>

		<!-- �޷� ���� -->
		<!-- ��� �޷� -->
		<table cellpadding="0" cellspacing="0" width="50%" align="center" style="margin:15px auto;">
			<tr>
				<td width="150" style="text-align:center;">
					<select onChange="dateChg( this.value);">
						<?
						for($Yi=2012;$Yi<=date("Y")+2;$Yi++){
							$sel = "";
							if( $Yi.$chan_M==$chan_Y.$chan_M ) $sel = "selected";
							echo "<option value=".$Yi.$chan_M." ".$sel.">".$Yi."</option>";
						}
						?>
				</select>
				��
				</td>
				<td id='calendarMonth'>
					<table cellpadding="0" cellspacing="0" border="0">
						<?
						for($Mi=1;$Mi<=12;$Mi++){
							$sel = ($Mi==$chan_M)?"height:24px;line-height:24px;background:#ff3300;color:#ffffff;font-weight:700;":"";
							echo "<td width=\"24\" onclick=\"dateChg('".$chan_Y.str_pad($Mi, 2, "0", STR_PAD_LEFT)."');\" align='center' style=\"".$sel."cursor:pointer;\">".str_pad($Mi, 2, "0", STR_PAD_LEFT)."</td>";
						}
						?>
					</table>
				</td>
			</tr>
		</table>
		<!-- ��� �޷� �� -->

		<!-- �޷� ���̺� ���� -->
		<?
		if( $chan_M > 0 ) {
			$chan_M=str_pad($chan_M, 2, "0", STR_PAD_LEFT);

			$hdays = vender_rentHolidayMonth($chan_Y.$chan_M,$vender,$pridx);
			if(!$hdays){
				$hdays = rentHolidayMonth($chan_Y.$chan_M,$_REQUEST['code']);
			}


			$season = vender_rentBusySeasonRange($vender,$pridx,$chan_Y.$chan_M);
			if(!$season){
				$season = rentBusySeasonRange($_REQUEST['code'],$chan_Y.$chan_M);
			}
		?>
			<table width="98%" cellpadding="0" cellspacing="0" align="center" class="tableBase">
				<Tr height="25" bgcolor="#F3F3F3" ALIGN="CENTER">
					<Td width='15%' bgcolor='F3F3F3' style='font-family:verdana; color:#FF000A;'>SUN</td>
					<Td width='14%' style='font-family:verdana; color:#000000;'>MON</td>
					<Td width='14%' style='font-family:verdana; color:#000000;'>TUE</td>
					<Td width='14%' style='font-family:verdana; color:#000000;'>WED</td>
					<Td width='14%' style='font-family:verdana; color:#000000;'>THU</td>
					<Td width='14%' style='font-family:verdana; color:#000000;'>FRI</td>
					<Td width='15%' bgcolor='F3F3F3' style='font-family:verdana; color:#000000;'>SAT</td>
				</tr>
				<?
				$statrstamp = strtotime($chan_Y.'-'.$chan_M.'-01');
				$loop = ceil((date('t',$statrstamp)+ date('w',$statrstamp))/7);
				for($i=1; $i<=$loop; $i++){
				
					for($j=0; $j<=6; $j++){
						$Cday=str_pad($day, 2, "0", STR_PAD_LEFT);
						$DATE_KEY = $chan_Y.$chan_M.$Cday;

						//üũ����Ʈ (��,��, ���� ������)
						if( $DATE_KEY==$vdate ) {
							$today_print_content="#F6F6C2";
						} else {
							$today_print_content="#FFFFFF";
						}
						if($week==$j || $day>1){
							if($day <= $lastday){
								echo "<td height=75 valign='middle' align='center' bgcolor='".$today_print_content."'>";
								echo "<DIV style='width:100%;height:100%;'>";
								echo "<p style=\"margin:0px;padding:0px;text-align:left;\">";
								if($j==0) echo "<span style='color:#FF356D;'>";
								if($j==6) echo "<span style='color:#5635FF;'>";
								echo $day;
								echo "</p>";

								/** ����� ���� ����
								 * ���� : �����������ָ������ ���� > �ָ�������� > ��������
								 */
								$dayPriceChk = false;

								// �ָ������ ����
							//	$holiday = rentHolidayInfo($DATE_KEY,$_REQUEST['code']);
								$holiday = vender_rentHolidayInfo($vender,$pridx);
								if(!$holiday){
									$holiday = rentHolidayInfo2($_REQUEST['code']);
								}
/*
								//�����,�Ͽ��� ������ �ָ���� ����
								if( ( $j==6 AND $seasonSet['sat'] ) OR ( $j==0 AND $seasonSet['sun'] ) AND ( $dayPriceChk == false ) ) {
									echo "<div>�ָ��������</div>";
									$dayPriceChk = true;
								}
*/
								//�ָ���� ������ ���� ����(��,��,��)
								if( ( $j==6 AND $holiday['days']['sat']=="ok" ) OR ( $j==0 AND $holiday['days']['sun']=="ok" ) OR ( $j==5 AND $holiday['days']['fri']=="ok" ) AND ( $dayPriceChk == false ) ) {
									echo "<div>�ָ��������</div>";
									$dayPriceChk = true;
								}

								if ( (isset($hdays['days'][$Cday])) AND ( $dayPriceChk == false ) ) {
									echo "<div>[ ".$hdays['days'][$Cday]." ] �ָ��������</div>";
									$dayPriceChk = true;
								}

								/*
								// ������
								$busySeason = rentBusySeasonInfo("busy",$DATE_KEY);
								if( ( $busySeason['idx'] > 0 ) AND ( $dayPriceChk == false ) ) {
									echo "<div>������������</div>";
									$dayPriceChk = true;
								}

								// �ؼ�����
								$busySeason = rentBusySeasonInfo("semi",$DATE_KEY);
								if( ( $busySeason['idx'] > 0 ) AND ( $dayPriceChk == false ) ) {
									echo "<div>�ؼ�����������</div>";
									$dayPriceChk = true;
								}
								*/
								
								if($dayPriceChk == false){
								
									//echo "d=".$Cday."/".in_array($Cday,$season['busy']);
									if(in_array($Cday,$season['busy'])) echo "<div>������������</div>";
									else if(in_array($Cday,$season['semi'])) echo "<div>�ؼ�����������</div>";									
									$dayPriceChk = true;
								}
								echo "</DIV>";
								$day++;
							}else{
								echo "<Td valign='top' align='center'>";
							}
						}else{
							echo "<Td valign='top' align='center'>";
						}
						echo "</td>\n";
					}
					echo"</tr>\n";
				}
				?>
			</table>
		<?
		}
		?>
		<!-- �޷� ���̺� �� -->
		<!-- �޷� �� -->
	</body>
</html>