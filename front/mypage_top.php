<div class="orderStateWrap">
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="myOrderTbl">
	<tr>
		
		<td>
			<p>주문현황</p>
			<strong><?=$ordercounts['ordercount']?></strong>
		</td>
		<td>
			<p>예약확정</p>
			<strong><?=$rentCount['booking']?></strong>
		</td>
		<td>
			<p>배송중</p>
			<strong><?=$ordercounts['delicomplatecount']?></strong>
		</td>
		<td>
			<p>렌탈중</p>
			<strong><?=$rentCount['rental']?></strong>
		</td>
		<td>
			<p>렌탈종료</p>
			<strong><?=$rentCount['rental_end']?></strong>
		</td>
		<td>
			<p>반납완료</p>
			<strong><?=$rentCount['rental_comp']?></strong>
		</td>

		<td style="border-left:1px solid #e5e5e5;">
			<p style="color:#ff0000">취소</p>
			<strong style="color:#ff0000"><?=$rentCount['booking_cancle']?></strong>
		</td>
		<td style="border:0px;">
			<p>반품/환불</p>
			<strong><?=$ordercounts['repaymentcount']?></strong>
		</td>

<!--
		<td style="border-left:1px solid #e5e5e5;">
			<p>주문/결제현황</p>
			<strong><?=$ordercounts['ordercount']?>/<?=$ordercounts['unpay']?></strong>
		</td>
		<td>
			<p>예약확정</p>
			<strong><?=$rentCount['booking']?></strong>
		</td>
		<td>
			<p>취소</p>
			<strong><?=$rentCount['booking_cancle']?></strong>
		</td>
		<td>
			<p>배송준비</p>
			<strong><?=$ordercounts['delireadycount']?></strong>
		</td>
		<td>
			<p>렌탈중</p>
			<strong><?=$ordercounts['delicomplatecount']?></strong>
		</td>

		<td>
			<p>주문</p>
			<a href="javascript:goOrderType('R');"><strong><?=$refund?></strong></a>
		</td>
		<td style="border:0px;">
			<p>환불완료</p>
			<a href="javascript:goOrderType('R');"><strong><?=$repayment?></strong></a>
		</td>

		<td >
			<p>취소</p>
			<strong><?=$ordercounts['ordercancel']?></strong>
		</td>
		<td>
			<p>반품</p>
			<a href="javascript:goOrderType('R');"><strong><?=$refund?></strong></a>
		</td>
		<td style="border:0px;">
			<p>교환</p>
			<a href="javascript:goOrderType('R');"><strong><?=$repayment?></strong></a>
		</td>

		<td style="border-left:1px solid #e5e5e5;">
			<p>렌탈중</p>
			<strong><?=$rentCount['rental']?></strong>
		</td>
		<td>
			<p>렌탈종료</p>
			<strong><?=$rentCount['rental_end']?></strong>
		</td>
		<td style="border:0px;">
			<p>반품/환불</p>
			<strong><?=$rentCount['rental_comp']?></strong>
		</td>
-->
	</tr>
</table>
</div>