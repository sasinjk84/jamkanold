<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

$gong_seq=$_POST["gong_seq"];

Header("Content-Disposition: attachment; filename=gong_list.xls");
Header("Content-Type: application/octet-stream");
Header("Pragma: no-cache");
Header("Expires: 0");

$sql = "SELECT gong_name FROM tblgonginfo WHERE gong_seq='".$gong_seq."' ";
$result=mysql_query($sql,get_db_conn());
$row=mysql_fetch_object($result);
mysql_free_result($result);
if($row) {
	$gong_name=$row->gong_name;
} else {
	exit;
}

echo "<center><FONT SIZE=\"4\" COLOR=\"red\"><B>���ݺ����� �������� ������ ��� (".$gong_name.")</B></FONT>";
echo "<table>";
echo "<tr bgcolor=\"#A8F0F2\">";
echo "	<td align=center><B>���̵�</B></td>";
echo "	<td align=center><B>�̸�</B></td>";
echo "	<td align=center><B>������</B></td>";
echo "	<td align=center><B>�̸���</B></td>";
echo "	<td align=center><B>��ȭ��ȣ</B></td>";
echo "	<td align=center><B>�ּ�</B></td>";
echo "	<td align=center><B>����</B></td>";
echo "	<td align=center><B>ó����Ȳ</B></td>";
echo "	<td align=center><B>�޸�</B></td>";
echo "</tr>";

$sql = "SELECT a.gong_name, b.id, b.name, b.date, b.email, b.tel, b.address, b.process_gbn, b.buy_cnt, b.memo ";
$sql.= "FROM tblgonginfo a, tblgongresult b WHERE a.gong_seq='".$gong_seq."' AND a.gong_seq=b.gong_seq ";
$result=mysql_query($sql,get_db_conn());
while($row=mysql_fetch_object($result)) {
	$date=substr($row->date,0,4)."/".substr($row->date,4,2)."/".substr($row->date,6,2)." (".substr($row->date,8,2).":".substr($row->date,10,2).")";
	echo "<tr>";
	echo "	<td align=center>".$row->id."</td>";
	echo "	<td align=center>".$row->name."</td>";
	echo "	<td align=center>".$date."</td>";
	echo "	<td align=center>".$row->email."</td>";
	echo "	<td align=center>".$row->tel."</td>";
	echo "	<td align=center>".$row->address."</td>";
	echo "	<td align=center>".$row->buy_cnt."</td>";
	echo "	<td align=center>";
	if($row->process_gbn=="I") echo "�Ա�Ȯ����";
	else if($row->process_gbn=="B") echo "�ԱݿϷ�";
	else if($row->process_gbn=="E") echo "��ۿϷ�";
	echo "	</td>";
	echo "	<td align=center>".$row->memo."</td>";
	echo "</tr>";
}
mysql_free_result($result);

echo "</table>";
echo "</center>";
?>