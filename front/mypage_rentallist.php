<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");
include_once($Dir."lib/ext/base_func.php");
include_once($Dir."lib/ext/product_func.php");
include_once($Dir."lib/ext/order_func.php");
include_once($Dir."lib/class/pages.php");

if(strlen($_ShopInfo->getMemid())==0) {
	Header("Location:".$Dir.FrontDir."login.php?chUrl=".getUrl());
	exit;
}

$sql = "SELECT * FROM tblmember WHERE id='".$_ShopInfo->getMemid()."' ";
$result=mysql_query($sql,get_db_conn());
if($row=mysql_fetch_object($result)) {
	if($row->member_out=="Y") {
		$_ShopInfo->SetMemNULL();
		$_ShopInfo->Save();
		echo "<html><head><title></title></head><body onload=\"alert('ȸ�� ���̵� �������� �ʽ��ϴ�.');location.href='".$Dir.FrontDir."login.php';\"></body></html>";exit;
	}

	if($row->authidkey!=$_ShopInfo->getAuthidkey()) {
		$_ShopInfo->SetMemNULL();
		$_ShopInfo->Save();
		echo "<html><head><title></title></head><body onload=\"alert('ó������ �ٽ� �����Ͻñ� �ٶ��ϴ�.');location.href='".$Dir.FrontDir."login.php';\"></body></html>";exit;
	}
}
mysql_free_result($result);

//����Ʈ ����
$setup[page_num] = 10;
$setup[list_num] = 10;
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
?>

<HTML>
	<HEAD>
		<TITLE><?=$_data->shoptitle?> - ��Ż �� �ݳ�</TITLE>
		<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">
		<META http-equiv="X-UA-Compatible" content="IE=Edge" />

		<META name="description" content="<?=(strlen($_data->shopdescription)>0?$_data->shopdescription:$_data->shoptitle)?>">
		<META name="keywords" content="<?=$_data->shopkeyword?>">
		<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
		<?include($Dir."lib/style.php")?>
	</HEAD>

	<body<?=(substr($_data->layoutdata["MOUSEKEY"],0,1)=="Y"?" oncontextmenu=\"return false;\"":"")?><?=(substr($_data->layoutdata["MOUSEKEY"],1,1)=="Y"?" ondragstart=\"return false;\" onselectstart=\"return false;\"":"")?> leftmargin="0" marginwidth="0" topmargin="0" marginheight="0"><?=(substr($_data->layoutdata["MOUSEKEY"],2,1)=="Y"?"<meta http-equiv=\"ImageToolbar\" content=\"No\">":"")?>

		<?
			include ($Dir.MainDir.$_data->menu_type.".php");
			include_once("./mypage_groupinfo.php");

		$param = array('id'=>$_ShopInfo->getMemid());
		switch($_REQUEST['s']){
			case 'RENT':
				$tit = '-��Ż��';
				$param['status']=array('BI');
				$param['end'] = date('Y-m-d H:i:s');
				break;
			case 'RENT_NC':
				$tit = '-��Ż ���� �ӹ�';
				$param['status'] = array('BNC');
				break;
			case 'RENT_END':
				$tit = '-��Ż����';
				$param['status'] = array('BE');
				break;
			case 'RETURN_READY':
				$tit = '-�ݳ����';
				$param['status'] = array('CR');
				break;
			case 'RETURN_OVER':
				$tit = '-�ݳ���ü';
				$param['status'] = array('OT');
				break;
			case 'RETURN_END':
				$tit = '-�ݳ��Ϸ�';
				$param['status'] = array('CE');
				break;
			default:
				$tit = '';
				$param['status']=array('BI','BE','CR','CE','NR','OT');
				break;
		}

		$rentCount = rentProduct::getCount($_ShopInfo->getMemid());
		?>
		<!-- ����������-�ֹ����� ��� �޴� -->
		<div class="currentTitle">
			<div class="titleimage">��Ż �� �ݳ� <?=$tit?></div>
		</div>
		<!-- ����������-�ֹ����� ��� �޴� -->

		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<?
				$leftmenu="Y";
				if($_data->design_orderlist=="U") {
					$sql="SELECT body,leftmenu FROM ".$designnewpageTables." WHERE type='orderlist'";
					$result=mysql_query($sql,get_db_conn());
					if($row=mysql_fetch_object($result)) {
						$body=$row->body;
						$body=str_replace("[DIR]",$Dir,$body);
						$leftmenu=$row->leftmenu;
						$newdesign="Y";
					}
					mysql_free_result($result);
				}

				if ($leftmenu!="N") {
					echo "<tr>\n";
					if ($_data->title_type=="Y" && file_exists($Dir.DataDir."design/orderlist_title.gif")) {
						echo "<td><img src=\"".$Dir.DataDir."design/orderlist_title.gif\" border=\"0\" alt=\"�ֹ�����\"></td>\n";
					} else {
						echo "<td></td>\n";
					}
					echo "</tr>\n";
				}
			?>
			<tr>
				<td align="center" style="text-align:left;">
					<!-- START -->
					<div class="orderStateWrap">
						<!-- �ֹ���Ȳ -->
						<table cellpadding="0" cellspacing="0" border="0" width="55%" class="myOrderTbl" style="margin:0 auto;">
							<tr>
								<td>
									<p>��Ż��</p>
									<a href="<?=$_SERVER['PHP_SELF']?>?s=RENT"><strong><?=number_format($rentCount['rental'])?></strong></a>
								</td>
								<td>
									<p>�ݳ� �ӹ� ��Ż</p>
									<a href="<?=$_SERVER['PHP_SELF']?>?s=RENT_NC"><strong><?=number_format($rentCount['rental_close_near'])?></strong></a>
								</td>
								<td>
									<p>��Ż����</p>
									<a href="<?=$_SERVER['PHP_SELF']?>?s=RENT_END"><strong><?=number_format($rentCount['rental_end'])?></strong></a>
								</td>

								<td style="border-left:1px solid #e5e5e5;">
									<p>�ݳ����</p>
									<a href="<?=$_SERVER['PHP_SELF']?>?s=RETURN_READY"><strong><?=number_format($rentCount['collecting'])?></strong></a>
								</td>
								<td>
									<p>�ݳ���ü</p>
									<a href="<?=$_SERVER['PHP_SELF']?>?s=RETURN_OVER"><strong><?=number_format($rentCount['rental_overtime'])?></strong></a>
								</td>
								<td>
									<p>�ݳ��Ϸ�</p>
									<a href="<?=$_SERVER['PHP_SELF']?>?s=RETURN_END"><strong><?=number_format($rentCount['rental_comp'])?></strong></a>
								</td>
							</tr>
						</table>
						</div>
						<p style="padding:40px 0px 10px;">�ݳ� ���� �Ǵ� �ļ� ���� ����� ��� ���� �ð����� �Ǹ��ڿ��� �����ٶ��ϴ�. ���� �����ڰ� ��Ż�� ��� ������ �� �� �ֽ��ϴ�.</p>

						<table cellpadding="0" cellspacing="0" width="100%" border="0" class="orderlistTbl">
							<col width="180"></col>
							<col></col>
							<col width="120"></col>
							<col width="120"></col>
							<col width="120"></col>
							<col width="120"></col>
							<tr>
								<th>�ֹ�����/������ȣ</th>
								<th>��ǰ����</th>
								<th>�Ⱓ</th>
								<th>�ֹ��ݾ�(����)</th>
								<th>�Ǹ���</th>
								<th>�������</th>
							</tr>								
							<?
							$info = rentProduct::searchOrder($param);
							
							if(!_array($info)){
							?>
							<tr>
								<td align="center" colspan="6" style="padding:30px;">��ϵ� ������ �����ϴ�.</td>
							</tr>
							<?
							}else{
								foreach ( $info as $k => $v ) {
									$pinfo = rentProduct::read($v['pridx']);
									if(!_array($pinfo)) continue;
									$optionName = "";
									foreach($v['opt'] as $ov) {
										$optionName .= $ov['optionName']." : ".$ov['orderCnt']."��<br>";
									}
									
									$rangetext = '';
									$timetext = '';
									if($pinfo['solvprice']['codeinfo']['pricetype'] == 'time') $rangetext = date('Y-m-d H',strtotime($v['start'])).'<br>'.date('Y-m-d H',strtotime($v['end'])+1);
									else $rangetext = date('Y-m-d',strtotime($v['start'])).'<br>'.date('Y-m-d',strtotime($v['end'])+1);

									$datediff = datediff_rent($v['end'],$v['start']);
									if($datediff['day'] > 0) $timetext =$datediff['day'].'�� ';
									if($datediff['hour'] > 0) $timetext .=$datediff['hour'].'�ð�';
									if(!_empty($timetext)) $rangetext .= '<br>'.$timetext;

									//  �����ð�
									$edatediff = datediff_rent($v['end'],date('Y-m-d H:i:s'));		
									$timetext = '';						
									if($edatediff['day'] > 0) $timetext =$edatediff['day'].'�� ';
									if($edatediff['hour'] > 0) $timetext .=$edatediff['hour'].'�ð�';
							?>								
							
							<tr>
								<td align="center"><?=$v['regDate']?><br/>(<?=$v['ordercode']?>)</td>
								<td align="left"><?=$v['productname']?><br/><?=$optionName?></td>
								<td align="center"><?=$rangetext?></td>
								<td align="center"><?=number_format($v['price'])?>��<br/>(<?=number_format($v['quantity'])?>��)</td>
								<td align="center"><?=$v['com_name'].'<br/>'.$v['com_tel']?></td>
								<td align="center"><?=rentProduct::_bookingStatus($v['status'])?><br><?=$timetext?></td>
							</tr>

							<?	} 
							}?>
						</table>

					<!-- END -->
				</td>
			</tr>
		</table>
		<? include ($Dir."lib/bottom.php") ?>
		<?=$onload?>
	</BODY>
</HTML>