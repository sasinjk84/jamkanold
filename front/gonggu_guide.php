<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");
$sleftMn = "NO";
unset($row);
$sql = "SELECT * FROM tbldesign ";
$result=mysql_query($sql,get_db_conn());
if($crow=mysql_fetch_object($result)) {

} else {
	$crow->introtype="C";
}
mysql_free_result($result);

?>

<HTML>
	<HEAD>
		<TITLE><?=$_data->shoptitle?> �����μ� > �������� > �������� �̿�ȳ�</TITLE>
		<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">
		<META http-equiv="X-UA-Compatible" content="IE=Edge" />

		<META name="description" content="<?=(strlen($_data->shopdescription)>0?$_data->shopdescription:$_data->shoptitle)?>">
		<META name="keywords" content="<?=$_data->shopkeyword?>">
		<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
		<?include($Dir."lib/style.php")?>
	</HEAD>

	<body<?=(substr($_data->layoutdata["MOUSEKEY"],0,1)=="Y"?" oncontextmenu=\"return false;\"":"")?><?=(substr($_data->layoutdata["MOUSEKEY"],1,1)=="Y"?" ondragstart=\"return false;\" onselectstart=\"return false;\"":"")?> leftmargin="0" marginwidth="0" topmargin="0" marginheight="0"><?=(substr($_data->layoutdata["MOUSEKEY"],2,1)=="Y"?"<meta http-equiv=\"ImageToolbar\" content=\"No\">":"")?>

	<? include ($Dir.MainDir.$_data->menu_type.".php") ?>

		<!-- �������� ������ ��� �޴� -->
		<div class="currentTitle">
			<div class="titleimage">��������</div>
			<!--<div class="current"><img src="/data/design/img/sub/icon_home.gif" border="0" alt="" /> Ȩ &gt; <SPAN class="nowCurrent">�α���</span></div>-->
		</div>
		<!-- �������� ������ ��� �޴� -->

		<div style="clear:both;height:6px;background:url('/data/design/img/main/top_boxline.gif') no-repeat;font-size:0px;"></div>
		<div style="padding:20px 30px;background:#ffffff url('/data/design/img/main/bg_boxline.gif') repeat-y;overflow:hidden;">
			<table align="center" cellpadding="0" cellspacing="0" width="100%" class="table_td">
				<tr>
					<td>
						<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>
							<TR>
								<TD><a href="../front/gonggu_main.php"><IMG SRC="../images/design/gonggu_tap01.gif" ALT="" border="0"></a></TD>
								<TD><a href="../front/gonggu_end.php"><IMG SRC="../images/design/gonggu_tap02.gif"  ALT="" border="0"></a></TD>
								<TD><a href="../front/gonggu_order.php"><IMG SRC="../images/design/gonggu_tap03.gif"  ALT="" border="0"></a></TD>
								<TD><a href="../front/gonggu_guide.php"><IMG SRC="../images/design/gonggu_tap04r.gif"  ALT="" border="0"></a></TD>
								<TD width="100%" background="../images/design/gonggu_tap_bg.gif"></TD>
							</TR>
						</TABLE>
					</td>
				</tr>
				<tr><td height="40"></td></tr>
				<tr>
					<td><IMG SRC="../images/design/gonggu_stitle01.gif" WIDTH=179 HEIGHT=50 ALT=""></td>
				</tr>
				<tr>
					<td><IMG SRC="../images/design/gonggu_sstitle01.gif" WIDTH=311 HEIGHT=25 ALT=""></td>
				</tr>
				<tr>
					<td class="table_td1">�Ʒ��� 3���� ������� ���ϴ� SNSä�ο� �α��� �ϼ���.<br>- ����������&gt;���� ����&gt;���� SNS ä�� ��� �޴����� ������ ���ϴ� SNSä�ο� �α���<br>- ��ǰ �������� �ϴ� �������� ��û �ǿ��� ���ۼ� �� �Ʒ� ����׸� 1�������� SNSä���� �����Ͽ� �α���<br>- �������� �޴� �� �������� ��û �ǿ��� ���ۼ� �� �Ʒ� ����׸� 1�� ������ SNSä���� �����Ͽ� �α���</td>
				</tr>
				<tr><td height="40"></td></tr>
				<tr>
					<td><IMG SRC="../images/design/gonggu_sstitle02.gif" WIDTH=132 HEIGHT=25 ALT=""></td>
				</tr>
				<tr>
					<td class="table_td1">- �Ʒ� ����׸� 2���� ��ǰ�˻��ϱ� ��ư�� Ŭ��<br>- ��ǰ �˻� �˾����� ���� ���ɻ�ǰ ����Ʈ���� �Ǵ� ���� �˻��Ͽ� ��ǰ�� ����<br>- �� SNSä�ο� ������ ������ �Ʒ� ����׸� 3�������� �Է� �� �������� ������ �Ϸ��ϼ���!</td>
				</tr>
				<tr>
					<td height=20></td>
				</tr>
				<tr>
					<td><IMG SRC="../images/design/gonggu_img01.gif" WIDTH=930 HEIGHT=156 ALT=""></td>
				</tr>
				<tr><td height="60"></td></tr>
				<tr>
					<td><IMG SRC="../images/design/gonggu_stitle02.gif" WIDTH=218 HEIGHT=50 ALT=""></td>
				</tr>
				<tr>
					<td><IMG SRC="../images/design/gonggu_sstitle03.gif" WIDTH=282 HEIGHT=25 ALT=""></td>
				</tr>
				<tr>
					<td class="table_td1">- ������ ������ ��ǰ �� ���� ������ ��ǰ�� ���� ������ ����Ѵٸ� �Ʒ� ����׸� 1���� �Բ� ��û�ϱ� ��ư�� Ŭ��<br>- Ŭ���� ��� ������ �Է��� �� �ְ� �ش� ��ǰ�� �������Ű� ����� �� �̸��ϰ� SMS�� �˶��� ������ ���ε� ���� �� �� �ֽ��ϴ�.<br>- ������� �Է��� ��� �Ʒ�����׸� 2�������� �ڽ��� ���۰� SNS������ �������ϴ�.</td>
				</tr>
				<tr><td height="20"></td></tr>
				<tr>
					<td>
						<table cellpadding="15" cellspacing="1" width="100%" bgcolor="#EEEEEE">
							<tr>
								<td width="922" bgcolor="#F9F9F9" class="table_td">���� �������� ��� �Բ� ��û�ϱ� ��ư��� ���������� ��ư���� �ٲ�<br>���� ���� �� ����������� ���� ���·� �������� ������� ���� ������ ��ư���� �ٲ�<br>������ ����� ��ǰ�� ��� �����Ϸ� ��ư���� �ٲ�</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td height="30"></td>
				</tr>
				<tr>
					<td><IMG SRC="../images/design/gonggu_img02.gif" WIDTH=930 HEIGHT=215 ALT=""></td>
				</tr>
				<tr><td height="60"></td></tr>
				<tr>
					<td><IMG SRC="../images/design/gonggu_stitle03.gif" WIDTH=225 HEIGHT=50 ALT=""></td>
				</tr>
				<tr>
					<td><IMG SRC="../images/design/gonggu_sstitle04.gif" WIDTH=205 HEIGHT=25 ALT=""></td>
				</tr>
				<tr>
					<td class="table_td1">�������� &gt; �������� �������� �ǿ��� �������� �������Ű� �������� ��ǰ�󼼺��� ��ư�� Ŭ���ϸ� ��ǰ�� ���� �� ������ Ȯ�� �Ͻ� �� �ֽ��ϴ�.</td>
				</tr>
				<tr><td height="30"></td></tr>
				<tr>
					<td><IMG SRC="../images/design/gonggu_sstitle05.gif" WIDTH=166 HEIGHT=25 ALT=""></td>
				</tr>
				<tr>
					<td class="table_td1">��ǰ �� ���� Ȯ�� �� �ɼǰ� ������ �����Ͽ� �������ݿ� ���ſ��� �Ͻ� �� �ֽ��ϴ�.<br>(���ſ��� �� �������� �̷�� ���� �������Ű� �������� ���� ��� �ϰ� ȯ�� ó�� �˴ϴ�.)</td>
				</tr>
				<tr><td height="30"></td></tr>
				<tr>
					<td><IMG SRC="../images/design/gonggu_sstitle06.gif" WIDTH=101 HEIGHT=25 ALT=""></td>
				</tr>
				<tr>
					<td class="table_td1">��������ڰ� �ּ��ο��� �ʰ��� ��� ������ ����Ǹ� ������ ����˴ϴ�.</td>
				</tr>
				<tr><td height="20"></td></tr>
				<tr>
					<td height="20"><IMG SRC="../images/design/gonggu_img03.gif" WIDTH=930 HEIGHT=517 ALT=""></td>
				</tr>
				<tr><td height="60"></td></tr>
				<tr>
					<td><IMG SRC="../images/design/gonggu_stitle04.gif" WIDTH=179 HEIGHT=50 ALT=""></td>
				</tr>
				<tr>
					<td><IMG SRC="../images/design/gonggu_sstitle07.gif" WIDTH=208 HEIGHT=25 ALT=""></td>
				</tr>
				<tr>
					<td class="table_td1">����������&gt;�������� �������� ���� ��û/���� ������ Ȯ���Ͻ� �� �ֽ��ϴ�.<br>- ���� ���Ȼ�ǰ ����<br>- ���� ��û��ǰ ����<br>- ���� ���� ����</td>
				</tr>
				<tr><td height="30"></td></tr>
				<tr>
					<td><IMG SRC="../images/design/gonggu_sstitle08.gif" WIDTH=184 HEIGHT=25 ALT=""></td>
				</tr>
				<tr>
					<td class="table_td1">���Բ��� ���� �ް� ��� ������ �ٸ��ַ�� ��ġ ��(���¼ҽ� Ư����) ȯ���� �Ұ����մϴ�.</td>
				</tr>
				<tr><td height="100"></td></tr>
			</table>
		</div>
		<div style="height:6px;background:url('/data/design/img/main/bot_boxline.gif') no-repeat;font-size:0px;"></div>

		<? include ($Dir."lib/bottom.php") ?>

	</BODY>
</HTML>