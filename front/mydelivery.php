<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

if(strlen($_ShopInfo->getMemid())==0) {
	exit;
}
?>
<!DOCTYPE HTML>
<HTML>
<HEAD>
	<title>���� ����� �˻�</title>
	<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">
	<META http-equiv="X-UA-Compatible" content="IE=Edge" />

	<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
	<link rel="stylesheet" type="text/css" href="<?=$Dir?>css/common.css">
	<link rel="stylesheet" type="text/css" href="<?=$Dir?>css/basket.css">

	<SCRIPT LANGUAGE="JavaScript">
		<!--
		window.resizeTo(500,400);

		function choice_addr(name,tel11,tel12,tel13,tel21,tel22,tel23,email,post,addr1,addr2) {
			if(post.length != 5){
				alert('�ش� �ּ����� �����ȣ�� ���ŵ��� �ʾ� ����� �� �����ϴ�.');
				return;
			}
			opener.document.form1.receiver_name.value=name;
			opener.document.form1.receiver_tel11.value=tel11;
			opener.document.form1.receiver_tel12.value=tel12;
			opener.document.form1.receiver_tel13.value=tel13;
			opener.document.form1.receiver_tel21.value=tel21;
			opener.document.form1.receiver_tel22.value=tel22;
			opener.document.form1.receiver_tel23.value=tel23;
			//opener.document.form1.email.value=email;

			opener.document.form1.rpost1.value=post;
			opener.document.form1.raddr1.value=addr1;
			opener.document.form1.raddr2.value=addr2;
			window.close();
		}
		//-->
	</SCRIPT>

	<style>
		html,body{margin:0;padding:0;}
		div,table,tr,td,ul,li,p,span,h1{color:#848484;font-size:13px;font-family:'Nanum Gothic',����;}
		a{color:#848484;font-size:13px;font-family:'Nanum Gothic',����;}
		h1{padding:10px 0px;background:#242424;color:#fff;font-size:20px;font-weight:bold;}
	</style>
</HEAD>

<body topmargin="0" leftmargin="0" rightmargin="0" marginheight="0" marginwidth="0" oncontextmenu="return false;">
	<h1>����� ����</h1>
	<ul style="width:92%;margin:20px auto;">
		<li style="padding-bottom:4px;">- �ּҸ� �����Ͻø� �ֹ����� �ٷ� ������ ��ϵ˴ϴ�.</li>
		<li>- �ּ����� ����/������ [���������� > ���������]���� �����մϴ�.</li>
	</ul>
	<table cellpadding="0" cellspacing="0" width="92%" class="itemListTbl" style="margin:0 auto;">
		<colgroup>
			<col width="80" />
			<col width="" />
			<col width="40" />
		</colgroup>
		<tr>
			<th>�����ȣ</th>
			<th>����� �ּ�</th>
			<th>����</th>
		</tr>
		<?
			$sql="SELECT * FROM tblorderreceiver WHERE member_id='".$_ShopInfo->getMemid()."' ORDER BY idx DESC ";
			$result=mysql_query($sql,get_db_conn());
			$cnt=0;
			while($row=mysql_fetch_object($result)) {
				$name=$row->receiver_name;
				$email=$row->receiver_email;
				$post=$row->receiver_post;

				$receiver_tel_temp=explode("-",$row->receiver_tel1);
				$tel11=$receiver_tel_temp[0];
				$tel12=$receiver_tel_temp[1];
				$tel13=$receiver_tel_temp[2];

				$receiver_tel2_temp=explode("-",$row->receiver_tel2);
				$tel21=$receiver_tel2_temp[0];
				$tel22=$receiver_tel2_temp[1];
				$tel23=$receiver_tel2_temp[2];

				$receiver_addr_temp=explode("=",$row->receiver_addr);
				$receiver_addr1=$receiver_addr_temp[0];
				$receiver_addr2=$receiver_addr_temp[1];

				echo "<tr>\n";
				echo "	<td class=\"tdstyle2a\"><A HREF=\"javascript:choice_addr('".$name."','".$tel11."','".$tel12."','".$tel13."','".$tel21."','".$tel22."','".$tel23."','".$email."','".$post."','".$receiver_addr1."','".$receiver_addr2."')\">".$post."</A></td>\n";
				echo "	<td class=\"tdstyle2a\" style=\"text-align:left;\"><A HREF=\"javascript:choice_addr('".$name."','".$tel11."','".$tel12."','".$tel13."','".$tel21."','".$tel22."','".$tel23."','".$email."','".$post."','".$receiver_addr1."','".$receiver_addr2."')\">".$receiver_addr1." ".$receiver_addr2."</a></td>\n";
				echo "	<td class=\"tdstyle2a\"><a href=\"javascript:choice_addr('".$name."','".$tel11."','".$tel12."','".$tel13."','".$tel21."','".$tel22."','".$tel23."','".$email."','".$post."','".$receiver_addr1."','".$receiver_addr2."');\"><span class=\"btn_m_gray\" style='width:100%;box-sizing:border-box;'>����</span></a></td>\n";
				echo "</tr>\n";

				$cnt++;
			}
			mysql_free_result($result);

			if($cnt==0){
				echo "<tr>\n";
				echo "	<td height=\"30\" colspan=\"3\" align=\"center\">��ϵ� ������� �����ϴ�.</td>\n";
				echo "</tr>\n";
			}
		?>
	</table>

	<div style="padding:10px 0px;text-align:center;"><a href="javascript:window.close();"><span class="btn_s_line2">â�ݱ�</span></a></div>
</body>
</html>