<?php
/**
 * Created by PhpStorm.
 * User: x2chi-objet
 * Date: 2014-11-13
 * Time: ���� 5:29
 */
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/class/pages.php");
INCLUDE ("access.php");

extract($_REQUEST);
?>
<!-- ��Ż ��ǰ �ɼ� -->
<?
//$goodsStatus = ( empty($goodsStatus) ? $goodStatusArray[0] : $goodsStatus );
?>
<script language="javascript">
	function addRow(tableid) {
		var table = document.getElementById(tableid);
		var rowlen = table.rows.length;
		//var row = table.insertRow();		// IE�� Chrome ������ �޸���.
		var row = table.insertRow(rowlen-1);	// HTML������ ���� ǥ�� ����
		row.insertCell(0).innerHTML = "<input type='text' name='optionName'>";
		row.insertCell(1).innerHTML = "<select name='optionGrade'><? foreach ($goodStatusArray as $k=>$v) { echo "<option value='".$k."'>".$v."</option>"; } ?></select>";
		row.insertCell(2).innerHTML = "<input type='text' name='optionPrice'>";
		row.insertCell(3).innerHTML = "<input type='text' name='optionCount'>";
	}
</script>
<table border="1" id='table1'>
	<tr id='row1'>
		<td align='center'>�ɼǸ�</td>
		<td align='center'>���</td>
		<td align='center'>����</td>
		<td align='center'>���</td>
	</tr>
	<tr id='row2'>
		<td align='center' colspan='4'>
			<input type='button' value='�ɼ��߰�' onClick='addRow("table1")' />
		</td>
	</tr>
</table>