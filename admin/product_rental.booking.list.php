<table border="0" cellspacing="0" cellpadding="0" width="100%" class="tableBase">
	<tr>
		<th class="firstTh">�����ڵ�</th>
		<th>��ǰ��</th>
		<th>�ɼ�</th>
		<th>�뿩 �Ⱓ</th>
		<th>�뿩�ڸ�</th>
		<th>����ó</th>
		<th>�ݾ�</th>
		<th>����</th>
		<th>�����</th>
	</tr>
	<?
	foreach ( $bookingProductList as $v ) {
		//_pr($v);

		// �ɼ�
		$optionName = "";
		foreach($v['rentOpt'] as $ov) {
			$optionName .= $ov['optionName']." : ".$ov['orderCnt']."��<br>";
		}

		// ���� ����
		$bookingStatusSel = "<div id='loading_".$v['idx']."' style=\"display:none;\"></div>";
		$bookingStatusSel .= "<div id='select_".$v['idx']."' style=\"display:block;\">";
		$bookingStatusSel .= "<select name='bookingStatusSel_".$v['idx']."' id='bookingStatusSel_".$v['idx']."' onchange=\"bookingStatusChange(".$v['idx'].", this.value);\">";
		foreach ($bookingStatus as $sk=>$sv) {
			$sel = ( $sk == $v['status'] ? "selected" : "" );
			$bookingStatusSel .= "<option value='".$sk."' ".$sel.">".$sv."</option>";
		}
		$bookingStatusSel .= "</select>";
		$bookingStatusSel .= "</div>";

		echo "
								<tr align=\"center\">
									<td class=\"firstTd\">".$v['idx']."</td>
									<td>".$v['productname']."</td>
									<td>".$optionName."</td>
									<td>".$v['bookingStartDate']." ~ ".$v['bookingEndDate']."</td>
									<td>".$v['receiver_name']."</td>
									<td>".$v['receiver_tel1']."<br>".$v['receiver_tel2']."</td>
									<td>".number_format($v['orderPrice'])."</td>
									<td>".$bookingStatusSel."</td>
									<td>".$v['regDate']."</td>
								</tr>
							";
	}
	?>
</table>