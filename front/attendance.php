<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");
include_once($Dir."lib/ext/func.php");
include_once($Dir."lib/class/pages.php");
include_once($Dir."lib/class/attendance.php");


$attendance = new attendance();
$attendancelist = $attendance->_getList('ables');
$saidx = '';
if($attendancelist['total'] ==1) $saidx = $attendancelist['items'][0]['aidx'];
else if(_isInt($_REQUEST['aidx'])) $saidx =$_REQUEST['aidx'];

if(!_empty($saidx) && false === $attendance->_set($attendancelist['items'][0]['aidx'])){
	_alert($attendance->_msg(),'/main/main.php');
	exit;
}
?>
<HTML>
<HEAD>
	<TITLE>�⼮ üũ �̺�Ʈ</TITLE>
	<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">

	<META name="description" content="<?=(strlen($_data->shopdescription)>0?$_data->shopdescription:$_data->shoptitle)?>">
	<META name="keywords" content="<?=$_data->shopkeyword?>">
	<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
	<script type="text/javascript" src="<?=$Dir?>lib/drag.js.php"></script>
	<script language="javascript" type="text/javascript" src="/js/jquery-1.10.2.min.js"></script>
	<script language="javascript" type="text/javascript">
	var $j = jQuery.noConflict();
	</script>
	<? include($Dir."lib/style.php")?>
</HEAD>
<body<?=(substr($_data->layoutdata["MOUSEKEY"],0,1)=="Y"?" oncontextmenu=\"return false;\"":"")?><?=(substr($_data->layoutdata["MOUSEKEY"],1,1)=="Y"?" ondragstart=\"return false;\" onselectstart=\"return false;\"":"")?> leftmargin="0" marginwidth="0" topmargin="0" marginheight="0"><?=(substr($_data->layoutdata["MOUSEKEY"],2,1)=="Y"?"<meta http-equiv=\"ImageToolbar\" content=\"No\">":"")?>

<? include ($Dir.MainDir.$_data->menu_type.".php"); ?>

<table border=0 cellpadding=0 cellspacing=0 width=100%>
	<tr>
		<td>

			<div class="memberbenefit">
				<h2>MUST HAVE! ��������</h2>
				<div><img src="/images/003/benefit_top.jpg" alt="" /></div>
				<div class="benefitmenu">
					<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td><a href="/front/newpage.php?code=1">ȸ������</a></td>
							<td><a href="/front/newpage.php?code=2">��ǰ������</a></td>
							<td><a href="/front/couponlist.php">��������</a></td>
							<td><a href="/front/productgift.php">�����̿��</a></td>
							<td class="nowon"><a href="/front/attendance.php">�⼮üũ</a></td>
							<td><a href="/front/member_urlhongbo.php">ȫ������������</a></td>
							<td><a href="/board/board.php?board=storytalk">���丮��</a></td>
						</tr>
					</table>
				</div>

				<div class="attendance">
					<h4>�⼮üũ</h4>
					<p>
						- �����⼮ ������ �� ������ �����⼮ �߰�����,����,����ǰ �� �پ��� ������ ����������.<br />
						- �⼮���� ��ϵ� �ش糯¥�� ������ Ȯ���Ͻð� �α��� �� �⼮üũ�ϼ���.<br />
						- ���� �⼮�ϸ� �Ϸ� 50�� ���� �ִ� 1,000�� ��������, �����⼮�� �߰����� �� ����ǰ ���� ������ �־����ϴ�.
					</p>
				</div>
			</div>

		</td>
	</tr>
	<tr><td height="20"></td></tr>
	<tr>
		<td style="border:1px solid #ecefed; padding:30px 40px;">
<?
		include $Dir.'templet/attendance/calendar.php';
?>
		</td>
	</tr>
	<tr><td height="40"></td></tr>
</table>

<? include ($Dir."lib/bottom.php") ?>
</BODY>
</HTML>