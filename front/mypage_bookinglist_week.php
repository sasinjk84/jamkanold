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
		?>

		<!-- ����������-�ֹ����� ��� �޴� -->
		<div class="currentTitle">
			<div class="titleimage">����/��Ż ����</div>
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
					<div style="margin-top:20px;">
						<style>
							.tableBase{width:100%;border-top:2px solid #444444;}
							.tableBase th{height:30px;text-align:center;background:#f8f8f8;border-right:1px solid #e2e2e2;border-bottom:1px solid #dddddd;}
							.tableBase .lastTh{border-right:none;}
							.tableBase td{height:30px;text-align:center;border-right:1px solid #e2e2e2;border-bottom:1px solid #dddddd;}
							.tableBase .lastTd{border-right:none;}
							.calendal td{width:10%;}
							.calendal .sunday{background:#ea2f36;color:#ffffff;}
							.calendal .saturday{background:#ff9501;color:#ffffff;}
							.calendal .holiday{background:#a3df05;color:#ffffff;}
							.calendal .checkday1{background:#02bae0;color:#ffffff;}
							.calendal .checkday2{background:#c0c0c0;color:#ffffff;}
						</style>
						<div style="height:6px;background:url('/data/design/img/sub/top_boxline2.gif') no-repeat;font-size:0px;"></div>
						<div style="position:relative;padding:20px;background:url('/data/design/img/sub/bg_boxline2.gif') repeat-y;">
							<div style="margin:10px 0px;overflow:hidden;">

								<div style="float:left;">
									<div style="float:left;margin-right:10px;"><a href="#"><img src="/data/design/img/sub/btn_refresh.gif" alt="" /></a></div>
									<div style="float:left;"><a href="mypage_bookinglist.php"><img src="/data/design/img/sub/btn_month.gif" alt="" /></a></div>
									<div style="float:left;"><a href="mypage_bookinglist_week.php"><img src="/data/design/img/sub/btn_week.gif" alt="" /></a></div>
									<div style="float:left;"><a href="mypage_bookinglist_day.php"><img src="/data/design/img/sub/btn_day.gif" alt="" /></a></div>
									<div style="float:left;margin-top:6px;margin-left:20px;">
										<span><img src="/data/design/img/sub/icon_red.gif" align="absmiddle" alt="" /> �Ͽ���</span>
										<span style="padding:0px 10px;"><img src="/data/design/img/sub/icon_orange.gif" align="absmiddle" alt="" /> �����</span>
										<span><img src="/data/design/img/sub/icon_green.gif" align="absmiddle" alt="" /> ������</span>
									</div>
								</div>

								<div style="position:absolute;top:25px;left:50%;width:250px;margin-left:-100px;text-align:center;overflow:hidden;">
									<div style="float:left;">
										<a href="#"><img src="/data/design/img/sub/btn_prev.gif" border="0" alt="" /></a> 
										<span style="padding:0px 10px;font-size:30px;font-weight:700;line-height:30px;">2015.01</span> 
										<a href="#"><img src="/data/design/img/sub/btn_next.gif" border="0" alt="" /></a>
									</div>
									<div style="float:left;margin-top:4px;margin-left:20px;"><a href="#"><img src="/data/design/img/sub/btn_today.gif" border="0" alt="" /></a></div>
								</div>

								<div style="float:right;margin-top:6px;">
									<span><img src="/data/design/img/sub/icon_blue.gif" align="absmiddle" alt="" /> �ܿ����� 00��</span>
									<span style="padding-left:10px;"><img src="/data/design/img/sub/icon_gray.gif" align="absmiddle" alt="" /> �ܿ����� 0</span>
								</div>

								<div style="clear:both;"></div>
							</div>

							<table cellpadding="0" cellspacing="0" width="100%" border="0" class="tableBase calendal" style="margin-bottom:40px;">
								<tr>
									<th colspan="2">��ǰ/��¥</font></th>
									<th class="sunday">4(��)</font></th>
									<th>5(��)</th>
									<th>6(ȭ)</th>
									<th>7(��)</th>
									<th>8(��)</th>
									<th>9(��)</th>
									<th class="lastTh saturday">10(��)</th>
								</tr>
								<tr>
									<td style="width:100px;">ī�װ���</td>
									<td style="width:100px;">��ǰ��</td>
									<td></td>
									<td></td>
									<td class="checkday2">0</td>
									<td class="checkday2">0</td>
									<td></td>
									<td></td>
									<td class="lastTd"></td>
								</tr>
								<tr>
									<td style="width:100px;">ī�װ���</td>
									<td style="width:100px;">��ǰ��</td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td class="checkday1"></td>
									<td class="checkday1">2</td>
									<td class="lastTd"></td>
								</tr>
								<tr>
									<td style="width:100px;">ī�װ���</td>
									<td style="width:100px;">��ǰ��</td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td class="lastTd"></td>
								</tr>
							</table>

							<div style="margin-bottom:10px;color:#444444;font-size:17px;font-weight:700;">���� / ��Ż �� ����</div>
							<div style="margin-bottom:5px;padding-top:5px;overflow:hidden;">
								<div style="float:left;">
									<form name="" action="">
									����/��Ż ����
									<select name="" style="position:relative;top:-2px;padding:2px;font-size:11px;">
										<option name="" value="">������</option>
										<option name="" value="">����Ȯ��</option>
										<option name="" value="">�������</option>
									</select>

									<span style="padding-left:20px;">������</span> <input type="" name="" size="10" class="input"  style="position:relative;top:-2px;" />
									</form>
								</div>
								<div style="float:right;">
									<select name="" style="position:relative;top:-2px;padding:2px;font-size:11px;">
										<option name="" value="">��ü</option>
										<option name="" value="">���̵�</option>
										<option name="" value="">������</option>
									</select>
									<input type="" name="" size="20" class="input" style="position:relative;top:-2px;height:21px;" /> <a href="#"><img src="/data/design/img/sub/btn_search.gif" align="absmiddle" /></a>
								</div>
							</div>
							<table border="0" cellpadding="0" cellspacing="0" width="100%" class="tableBase">
								<colgroup>
									<col width="60">
									<col width="">
									<col width="100">
									<col width="100">
									<col width="140">
									<col width="100">
									<col width="100">
									<col width="120">
								</colgroup>
								<tr>
									<th>����</th>
									<th>�Ⱓ</th>
									<th>���̵�</th>
									<th>������</th>
									<th>��ȭ��ȣ</th>
									<th>�ѱݾ�</th>
									<th>�������</th>
									<th class="lastTh">�����</th>
								</tr>
								<tr>
									<td>3</td>
									<td>2015.01.01 ~ 2015.01.10</td>
									<td>guest</td>
									<td>ȫ�浿</td>
									<td>010-1234-5678</td>
									<td>18,000��</td>
									<td>������</td>
									<td class="lastTd">2014.12.01</td>
								</tr>
								<tr>
									<td>2</td>
									<td>2015.01.10 ~ 2015.01.17</td>
									<td>guest</td>
									<td>��浿</td>
									<td>010-1234-5678</td>
									<td>240,000��</td>
									<td>�������</td>
									<td class="lastTd">2015.01.01</td>
								</tr>
								<tr>
									<td>1</td>
									<td>2015.01.10 ~ 2015.01.25</td>
									<td>guest</td>
									<td>�ڱ浿</td>
									<td>010-1234-5678</td>
									<td>1,200,000��</td>
									<td>����Ȯ��</td>
									<td class="lastTd">2015.01.04</td>
								</tr>
							</table>

							<div class="pageingarea" style="text-align:center;width:100%; margin:20px 0px;">
								<a href="#"><img src="/images/common/btn_page_start.gif" border="0" alt="ó������" class="blockPageBtn" /></a>
								<a href="#"><img src="/images/common/btn_page_prev.gif" border="0" alt="���� 10 ������" class="blockPageBtn" /></a>
								<span class="currpageitem">1</span>
								<a href="#"><img src="/images/common/btn_page_next.gif" border="0" alt="���� 10 ������" class="blockPageBtn" /></a>
								<a href="#"><img src="/images/common/btn_page_end.gif" border="0" alt="������" class="blockPageBtn"  /></a>
							</div>

						</div>
						<div style="height:6px;background:url('/data/design/img/sub/bot_boxline2.gif') no-repeat;font-size:0px;"></div>
					</div>
					<!-- END -->
				</td>
			</tr>
		</table>
		<? include ($Dir."lib/bottom.php") ?>
		<?=$onload?>
	</BODY>
</HTML>