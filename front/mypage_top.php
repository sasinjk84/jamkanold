<div class="orderStateWrap">
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="myOrderTbl">
	<tr>
		
		<td>
			<p>�ֹ���Ȳ</p>
			<strong><?=$ordercounts['ordercount']?></strong>
		</td>
		<td>
			<p>����Ȯ��</p>
			<strong><?=$rentCount['booking']?></strong>
		</td>
		<td>
			<p>�����</p>
			<strong><?=$ordercounts['delicomplatecount']?></strong>
		</td>
		<td>
			<p>��Ż��</p>
			<strong><?=$rentCount['rental']?></strong>
		</td>
		<td>
			<p>��Ż����</p>
			<strong><?=$rentCount['rental_end']?></strong>
		</td>
		<td>
			<p>�ݳ��Ϸ�</p>
			<strong><?=$rentCount['rental_comp']?></strong>
		</td>

		<td style="border-left:1px solid #e5e5e5;">
			<p style="color:#ff0000">���</p>
			<strong style="color:#ff0000"><?=$rentCount['booking_cancle']?></strong>
		</td>
		<td style="border:0px;">
			<p>��ǰ/ȯ��</p>
			<strong><?=$ordercounts['repaymentcount']?></strong>
		</td>

<!--
		<td style="border-left:1px solid #e5e5e5;">
			<p>�ֹ�/������Ȳ</p>
			<strong><?=$ordercounts['ordercount']?>/<?=$ordercounts['unpay']?></strong>
		</td>
		<td>
			<p>����Ȯ��</p>
			<strong><?=$rentCount['booking']?></strong>
		</td>
		<td>
			<p>���</p>
			<strong><?=$rentCount['booking_cancle']?></strong>
		</td>
		<td>
			<p>����غ�</p>
			<strong><?=$ordercounts['delireadycount']?></strong>
		</td>
		<td>
			<p>��Ż��</p>
			<strong><?=$ordercounts['delicomplatecount']?></strong>
		</td>

		<td>
			<p>�ֹ�</p>
			<a href="javascript:goOrderType('R');"><strong><?=$refund?></strong></a>
		</td>
		<td style="border:0px;">
			<p>ȯ�ҿϷ�</p>
			<a href="javascript:goOrderType('R');"><strong><?=$repayment?></strong></a>
		</td>

		<td >
			<p>���</p>
			<strong><?=$ordercounts['ordercancel']?></strong>
		</td>
		<td>
			<p>��ǰ</p>
			<a href="javascript:goOrderType('R');"><strong><?=$refund?></strong></a>
		</td>
		<td style="border:0px;">
			<p>��ȯ</p>
			<a href="javascript:goOrderType('R');"><strong><?=$repayment?></strong></a>
		</td>

		<td style="border-left:1px solid #e5e5e5;">
			<p>��Ż��</p>
			<strong><?=$rentCount['rental']?></strong>
		</td>
		<td>
			<p>��Ż����</p>
			<strong><?=$rentCount['rental_end']?></strong>
		</td>
		<td style="border:0px;">
			<p>��ǰ/ȯ��</p>
			<strong><?=$rentCount['rental_comp']?></strong>
		</td>
-->
	</tr>
</table>
</div>