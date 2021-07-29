<?php
/**
 * Created by PhpStorm.
 * User: x2chi-objet
 * Date: 2014-10-28
 * Time: 오후 1:39
 */
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/class/pages.php");
INCLUDE ("access.php");

extract($_REQUEST);
?>


<hr />
등록된 리스트로 노출
<hr />
<?
// 대여 출고지 정보 리스트
$value = array("display"=>1); // 노출 만 표시
$localList = rentLocalList( $value );
?>
<!-- 리스트 --->
<table border="1">
	<tr>
		<td>지역코드</td>
		<td>타입</td>
		<td>소유입점사</td>
		<td>지명</td>
		<td>지도</td>
		<td>주소</td>
		<td>연동</td>
	</tr>
	<tr>
		<td colspan="6">출고지 정보 없음</td>
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
			<td><?=($v['vender']>0 ? $venderList[$v['vender']]['com_name'] : "본사"); ?></td>
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
재고수량<input type="text" value="0">
<input type="button" value="등록" onclick="">
</div>