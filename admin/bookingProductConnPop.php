<?php
/**
 * Created by PhpStorm.
 * User: x2chi-objet
 * Date: 2014-10-28
 * Time: ���� 1:39
 */
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/class/pages.php");
INCLUDE ("access.php");

extract($_REQUEST);
?>


<hr />
��ϵ� ����Ʈ�� ����
<hr />
<?
// �뿩 ����� ���� ����Ʈ
$value = array("display"=>1); // ���� �� ǥ��
$localList = rentLocalList( $value );
?>
<!-- ����Ʈ --->
<table border="1">
	<tr>
		<td>�����ڵ�</td>
		<td>Ÿ��</td>
		<td>����������</td>
		<td>����</td>
		<td>����</td>
		<td>�ּ�</td>
		<td>����</td>
	</tr>
	<tr>
		<td colspan="6">����� ���� ����</td>
		<td>
			<input type="radio" value="0" name="location" <?=$localSel[0]?>>
		</td>
	</tr>
	<?
	foreach ( $localList as $k=>$v ) {
		?>
		<tr>
			<td><?=$v['location']?></td>
			<td><?=$rentLocationType[$v['type']]?></td>
			<td><?=($v['vender']>0 ? $venderList[$v['vender']]['com_name'] : "����"); ?></td>
			<td><?=$v['title']?></td>
			<td><?=$v['ypos']?>*<?=$v['xpos']?></td>
			<td>(<?=$v['zip']?>)<?=$v['address']?></td>
			<td>
				<input type="radio" value="<?=$v['location']?>" name="location" <?=$localSel[$v['location']]?>>
			</td>
		</tr>
	<?
	}
	?>
</table>
������<input type="text" value="0">
<input type="button" value="���" onclick="">
</div>