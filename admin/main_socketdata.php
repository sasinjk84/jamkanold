<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

/*
################# SMS �ܿ� #################
$sql = "SELECT id, authkey FROM tblsmsinfo ";
$result=mysql_query($sql,get_db_conn());
$row=mysql_fetch_object($result);
mysql_free_result($result);
$sms_id=$row->id;
$sms_authkey=$row->authkey;

$sms_count="";
if(strlen($sms_id)==0 || strlen($sms_authkey)==0) {
	$sms_count="<A style=\"cursor:hand\" onclick=\"alert(\'SMS ȸ������ �� ���� �� SMS �⺻ȯ�� �������� SMS ���̵� �� ����Ű�� �Է��Ͻñ� �ٶ��ϴ�.\');\"><font color=red><U>�̼���!!</U></font></A>";
} else {
	$smscountdata=getSmscount($sms_id, $sms_authkey);
	if(substr($smscountdata,0,2)=="OK") {
		$sms_count="<font class=\"font_orange4\"><b>".substr($smscountdata,3)."</b></font> Point";
	} else if(substr($smscountdata,0,2)=="NO") {
		$sms_count="<A style=\"cursor:hand\" onclick=\"alert(\'SMS ȸ�� ���̵� �������� �ʽ��ϴ�. SMS �⺻ȯ�� �������� SMS ���̵� �� ����Ű�� ��Ȯ�� �Է��Ͻñ� �ٶ��ϴ�.\');\"><font class=\"font_orange4\"><B>��������!!</B></font></A>";
	} else if(substr($smscountdata,0,2)=="AK") {
		$sms_count="<A style=\"cursor:hand\" onclick=\"alert(\'SMS ȸ�� ����Ű�� ��ġ���� �ʽ��ϴ�. SMS �⺻ȯ�� �������� ����Ű�� ��Ȯ�� �Է��Ͻñ� �ٶ��ϴ�.\');\"><font class=\"font_orange4\"><B>��������!!</B></font></A>";
	} else {
		$sms_count="<A style=\"cursor:hand\" onclick=\"alert(\'SMS ������ ����� �Ұ����մϴ�. ��� �� �̿��Ͻñ� �ٶ��ϴ�.\');\"><font class=\"font_orange4\"><B>��ſ���!!</B></font></A>";
	}
}
*/

$str_notice="";
$server_error=false;
$noticedata=array();
$resdata=getAdminMainNotice();
if(substr($resdata,0,2)=="OK") {
	$noticedata=unserialize(substr($resdata,3));
} else {
	$server_error=true;
}
for($i=0;$i<count($noticedata);$i++) {
	$row=$noticedata[$i];
	$row->subject=str_replace("'","`",$row->subject);
	$row->subject=str_replace("\"","`",$row->subject);
	$str_notice.="<tr>";
	$str_notice.="	<td width=8><img src=images/main_center_point.gif border=0></td>";
	$str_notice.="	<td><font color=gray>[".substr($row->date,4,2)."/".substr($row->date,6,2)."]</font> <A HREF=\"javascript:shop_noticeview(\'view\',\'".$row->date."\')\">".titleCut(23,$row->subject)."</A></td>";
	$str_notice.="</tr>";
}
if($i==0) {
	if($server_error==true) {
		$str_notice.="<tr><td align=center><font class=font_orange>�������� ������ ����� �Ұ����մϴ�.</font></td></tr>";
	} else {
		$str_notice.="<tr><td align=center>��ϵ� ���������� �����ϴ�.</td></tr>";
	}
}
$str_notice="<table cellpadding=0 cellspacing=0 width=100% border=0>".$str_notice."</table>";

?>

<script>
/*
try {
	parent.document.all["idx_sms"].innerHTML='<?=$sms_count?>';
} catch(e) {}
*/
try {
	parent.document.all["idx_notice"].innerHTML='<?=$str_notice?>';
} catch(e) {}
</script>