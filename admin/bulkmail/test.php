<Script language="JavaScript"> 
<!-- 
function form_submit() 
{ 
    document.form.action="http://plus040.lemonplus.kr/servlet/servletAPI.ThunderAPIServlet"; // �߼�API URL : �����Ǵ� �����. 
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
				<input type="text" name="title" value="[$name]�� �ȳ��ϼ���." style="width:400">
				
			</td>
		</tr>
		<tr>
			<td>mailContents</td>
			<td width="500">
				<input type="text" name="mailContents" value="[$name]�� �ȳ��ϼ���. ��뷮 ���� �߼� �׽�Ʈ �Դϴ�." style="width:400">
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
				<input type="text" name="senderName" value="������"  style="width:400">
				
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
				<input type="text" name="email" value="guraguna@naver.com��guraguna@gmail.com��"  style="width:400">				
				(������ : &aelig;) </td>
		</tr>
		<tr>
			<td>name</td>
			<td width="500">
				<input type="text" name="name" value="����������"  style="width:400">				
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
	<td width="500"><input type="text" name="etc1" value="��Ÿ1����Ÿ1��"  style="width:400"> </td>
</tr>
<tr>
	<td>etc2</td>
	<td width="500"><input type="text" name="etc2" value="��Ÿ2����Ÿ2��"  style="width:400"> </td>
</tr>
<tr>
	<td>etc3</td>
	<td width="500"><input type="text" name="etc3" value="��Ÿ3����Ÿ3��"  style="width:400"> </td>
</tr>
<tr>
	<td>etc4</td>
	<td width="500"><input type="text" name="etc4" value="��Ÿ4����Ÿ4��"  style="width:400"> </td>
</tr>
<tr>
	<td>etc5</td>
	<td width="500"><input type="text" name="etc5" value="��Ÿ5����Ÿ5��"  style="width:400"> </td>
</tr>
<tr>
	<td>etc6</td>
	<td width="500"><input type="text" name="etc6" value="��Ÿ6����Ÿ6��"  style="width:400"> </td>
</tr>
<tr>
	<td>etc7</td>
	<td width="500"><input type="text" name="etc7" value="��Ÿ7����Ÿ7��"  style="width:400"> </td>
</tr>
<tr>
	<td>etc8</td>
	<td width="500"><input type="text" name="etc8" value="��Ÿ8����Ÿ8��"  style="width:400"> </td>
</tr>
<tr>
	<td>etc9</td>
	<td width="500"><input type="text" name="etc9" value="��Ÿ9����Ÿ9��"  style="width:400"> </td>
</tr> -->
	</table>
</form>
<input type="button" onClick="form_submit()" value="�߼�">
