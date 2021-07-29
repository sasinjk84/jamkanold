<table border="0" cellspacing="0" cellpadding="0" width="100%" class="tableBase">
	<tr>
		<th class="firstTh">예약코드</th>
		<th>상품명</th>
		<th>옵션</th>
		<th>대여 기간</th>
		<th>대여자명</th>
		<th>연락처</th>
		<th>금액</th>
		<th>상태</th>
		<th>등록일</th>
	</tr>
	<?
	foreach ( $bookingProductList as $v ) {
		//_pr($v);

		// 옵션
		$optionName = "";
		foreach($v['rentOpt'] as $ov) {
			$optionName .= $ov['optionName']." : ".$ov['orderCnt']."개<br>";
		}

		// 상태 변경
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