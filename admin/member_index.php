<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

INCLUDE ("access.php");
?>

<? INCLUDE ("header.php"); ?>


<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
<tr>
	<td valign="top">
	<table cellpadding="0" cellspacing="0" width=100% style="table-layout:fixed">
	<tr>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed"  background="images/con_bg.gif">
		<col width=198></col>
		<col width=10></col>
		<col width=></col>
		<tr>
			<td valign="top"  background="images/leftmenu_bg.gif">
			<? include ("menu_member.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ȸ������ &gt; <span class="2depth_select">ȸ������ ����</span></td>
			</tr>
			</table>
		</td>
	</tr>   
	<tr>
        <td width="16"><img src="images/con_t_01.gif" width="16" height="16" border="0"></td>
        <td background="images/con_t_01_bg.gif"></td>
        <td width="16"><img src="images/con_t_02.gif" width="16" height="16" border="0"></td>
    </tr>
    <tr>
        <td width="16" background="images/con_t_04_bg1.gif"></td>
        <td bgcolor="#ffffff" style="padding:10px">









			<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
			<tr><td height="8"></td></tr>
			<tr>
				<td>
					<table cellpadding="0" cellspacing="0" width="99%" style="table-layout:fixed">
						<tr>
							<td background="images/main_titlebg.gif"><img src="images/member_maintitle.gif" border="0"></td>							
						</tr>
					</table>
				</td>
			</tr>
			<tr><td height="20"></td></tr>
			<tr>
				<td>
				<table cellpadding="0" cellspacing="0" width="99%" style="table-layout:fixed">
				<col width="50%"></col>
				<col width="50%"></col>
<?
				$shop_main_title[] = "member_mainstitle1.gif";
				$shop_main_title[] = "member_mainstitle2.gif";
				$shop_main_title[] = "member_mainstitle3.gif";

				$shop_main_stext[0][] = "member_mains0text01.gif";
				$shop_main_stext[0][] = "member_mains0text02.gif";
				$shop_main_stext[0][] = "member_mains0text03.gif";

				$shop_main_stext[1][] = "member_mains1text01.gif";
				$shop_main_stext[1][] = "member_mains1text02.gif";
				$shop_main_stext[1][] = "member_mains1text03.gif";
				
				$shop_main_stext[2][] = "member_mains2text01.gif";
				$shop_main_stext[2][] = "member_mains2text02.gif";
				$shop_main_stext[2][] = "member_mains2text03.gif";
				$shop_main_stext[2][] = "member_mains2text04.gif";
				$shop_main_stext[2][] = "member_mains2text05.gif";

				$shop_main_slink[0][] = "member_list.php";
				$shop_main_slink[0][] = "member_outlist.php";
				$shop_main_slink[0][] = "member_excelupload.php";

				$shop_main_slink[1][] = "member_groupnew.php";
				$shop_main_slink[1][] = "member_groupmemreg.php";
				$shop_main_slink[1][] = "member_groupmemberview.php";
				
				$shop_main_slink[2][] = "member_mailsend.php";
				$shop_main_slink[2][] = "member_mailallsend.php";
				$shop_main_slink[2][] = "member_mailallsendinfo.php";
				$shop_main_slink[2][] = "javascript:parent.topframe.GoMenu(7,'market_smssinglesend.php');";
				$shop_main_slink[2][] = "javascript:parent.topframe.GoMenu(7,'market_smsgroupsend.php');";

				$shop_main_sinfo[0][] = "ȸ���� �˻��ϰų� ȸ�� �󼼳����� ��ȸ/����/Ż��/��ȣ����/��Ÿ ó���� �� �� �ֽ��ϴ�.";
				$shop_main_sinfo[0][] = "���θ����� ��ϵ� ȸ�� ��ȸ �� Ż������� �� �ִ� ������ �Դϴ�.";
				$shop_main_sinfo[0][] = "�ټ��� ȸ�������� �������Ϸ� ����� �ϰ� ����Ͻ� �� �ֽ��ϴ�.";
				
				$shop_main_sinfo[1][] = "ȸ����� �űԵ��/����/������ �Ͻ� �� ������ ��޺� ���Ѽ����� �����մϴ�.";
				$shop_main_sinfo[1][] = "ȸ���˻��� ���� ȸ��Ư���� �´� ������� ���� ������ �� �ֽ��ϴ�.";
				$shop_main_sinfo[1][] = "��޺� ��ϵ� ȸ�������� ��ȸ/������ �����մϴ�.";

				$shop_main_sinfo[2][] = "���θ� ȸ���� Ư��ȸ�� �Ѹ��� ������ �߼��� �� �ֽ��ϴ�.";
				$shop_main_sinfo[2][] = "���θ� ��üȸ�� �Ǵ� ��޺� ȸ������ ������ �߼��� �� �ֽ��ϴ�.";
				$shop_main_sinfo[2][] = "���θ� ��üȸ�� �Ǵ� ���ȸ������ �߼��� ���� �� �߼ۿ��θ� Ȯ���� �� �ֽ��ϴ�.";
				$shop_main_sinfo[2][] = "Ư�� ������ SMS�� �߼��� �� �ֽ��ϴ�.";
				$shop_main_sinfo[2][] = "��üȸ��/���ȸ��/����ȸ������ ��ü SMS �߼��� �� �� �ֽ��ϴ�.";

				for($i=0; $i<count($shop_main_title); $i++) {
					echo "<tr>\n";
					echo "	<td colspan=\"3\" background=\"images/mainstitle_bg.gif\"><img src=\"images/".$shop_main_title[$i]."\" border=\"0\"></td>\n";
					echo "</tr>\n";
					
					$shop_main_stext_round = @round(count($shop_main_stext[$i])/2);
					$k = $shop_main_stext_round;
					for($j=0; $j<$shop_main_stext_round; $j++) {
					echo "<tr>\n";
					echo "	<td style=\"padding-left:15px\"><a href=\"".$shop_main_slink[$i][$j]."\"><img src=\"images/".$shop_main_stext[$i][$j]."\" border=\"0\"><img src=\"images/cmn_main_go.gif\" border=\"0\"></a></td>\n";
						if($shop_main_stext[$i][$k]) {
						echo "	<td style=\"padding-left:15px\"><a href=\"".$shop_main_slink[$i][$k]."\"><img src=\"images/".$shop_main_stext[$i][$k]."\" border=\"0\"><img src=\"images/cmn_main_go.gif\" border=\"0\"></a></td>\n";
						} else {
						echo "	<td style=\"padding-left:15px\"></td>\n";
						}
					echo "</tr>\n";
					echo "<tr>\n";
					echo "	<td style=\"padding-left:21px\" valign=\"top\" class=\"member_fontcolor\">".$shop_main_sinfo[$i][$j]."</td>\n";
					echo "	<td style=\"padding-left:21px\" valign=\"top\" class=\"member_fontcolor\">".$shop_main_sinfo[$i][$k]."</td>\n";
					echo "</tr>\n";
						$k++;
					}

					echo "<tr>\n";
					echo "	<td height=\"20\" colspan=\"3\"></td>\n";
					echo "</tr>\n";
				}
?>
				</table>
				</td>
			</tr>
			<tr><td height="30"></td></tr>
			</table>

</td>
        <td width="16" background="images/con_t_02_bg.gif"></td>
    </tr>
    <tr>
        <td width="16"><img src="images/con_t_04.gif" width="16" height="16" border="0"></td>
        <td background="images/con_t_04_bg.gif"></td>
        <td width="16"><img src="images/con_t_03.gif" width="16" height="16" border="0"></td>
    </tr>
    <tr><td height="20"></td></tr>
</table>


			</td>
		</tr>
		</table>
		</td>
	</tr>
	</table>
	</td>
</tr>
</table>

<? INCLUDE ("copyright.php"); ?>