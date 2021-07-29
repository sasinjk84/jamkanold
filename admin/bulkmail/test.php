<Script language="JavaScript"> 
<!-- 
function form_submit() 
{ 
    document.form.action="http://plus040.lemonplus.kr/servlet/servletAPI.ThunderAPIServlet"; // 발송API URL : 아이피는 변경됨. 
    document.form.submit(); 
} 
//--> 
</Script>

<form action="" name="form" >
<input type="hidden" name="campaignType" value="1">

	<table width="700" border="1">
		<tr>
			<td>title</td>
			<td width="500">
				<input type="text" name="title" value="[$name]님 안녕하세요." style="width:400">
				
			</td>
		</tr>
		<tr>
			<td>mailContents</td>
			<td width="500">
				<input type="text" name="mailContents" value="[$name]님 안녕하세요. 대용량 메일 발송 테스트 입니다." style="width:400">
			</td>
		</tr>
		<tr>
			<td>senderEmail</td>
			<td width="500">
				<input type="text" name="senderEmail" value="madmirr@gmail.com"  style="width:400">
				
			</td>
		</tr>
		<tr>
			<td>senderName</td>
			<td width="500">
				<input type="text" name="senderName" value="전형준"  style="width:400">
				
			</td>
		</tr>
		<tr>
			<td>receiverName</td>
			<td width="500">
				<input type="text" name="receiverName" value="[$name]"  style="width:400">
				
			</td>
		</tr>
		<tr>
			<td>email</td>
			<td width="500">
				<input type="text" name="email" value="guraguna@naver.comæguraguna@gmail.comæ"  style="width:400">				
				(구분자 : &aelig;) </td>
		</tr>
		<tr>
			<td>name</td>
			<td width="500">
				<input type="text" name="name" value="곽용욱æ곽용욱æ"  style="width:400">				
			</td>
		</tr>
		<tr>
			<td>userid</td>
			<td width="500">
				<input type="text" name="userid" value="test"  style="width:400">				
			</td>
		</tr>
		<!-- 
<tr>
	<td>etc1</td>
	<td width="500"><input type="text" name="etc1" value="기타1æ기타1æ"  style="width:400"> </td>
</tr>
<tr>
	<td>etc2</td>
	<td width="500"><input type="text" name="etc2" value="기타2æ기타2æ"  style="width:400"> </td>
</tr>
<tr>
	<td>etc3</td>
	<td width="500"><input type="text" name="etc3" value="기타3æ기타3æ"  style="width:400"> </td>
</tr>
<tr>
	<td>etc4</td>
	<td width="500"><input type="text" name="etc4" value="기타4æ기타4æ"  style="width:400"> </td>
</tr>
<tr>
	<td>etc5</td>
	<td width="500"><input type="text" name="etc5" value="기타5æ기타5æ"  style="width:400"> </td>
</tr>
<tr>
	<td>etc6</td>
	<td width="500"><input type="text" name="etc6" value="기타6æ기타6æ"  style="width:400"> </td>
</tr>
<tr>
	<td>etc7</td>
	<td width="500"><input type="text" name="etc7" value="기타7æ기타7æ"  style="width:400"> </td>
</tr>
<tr>
	<td>etc8</td>
	<td width="500"><input type="text" name="etc8" value="기타8æ기타8æ"  style="width:400"> </td>
</tr>
<tr>
	<td>etc9</td>
	<td width="500"><input type="text" name="etc9" value="기타9æ기타9æ"  style="width:400"> </td>
</tr> -->
	</table>
</form>
<input type="button" onClick="form_submit()" value="발송">
