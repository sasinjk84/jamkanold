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
			<? include ("menu_market.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ���������� &gt; <span class="2depth_select">���������� ����</span></td>
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
							<td background="images/main_titlebg.gif"><img src="images/market_maintitle.gif" border="0"></td>							
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
				$shop_main_title[] = "market_mainstitle1.gif";
				$shop_main_title[] = "market_mainstitle2.gif";
				$shop_main_title[] = "market_mainstitle3.gif";
				$shop_main_title[] = "market_mainstitle4.gif";

				$shop_main_stext[0][] = "market_mains0text01.gif";
				$shop_main_stext[0][] = "market_mains0text02.gif";
				$shop_main_stext[0][] = "market_mains0text03.gif";
				$shop_main_stext[0][] = "market_mains0text04.gif";
				$shop_main_stext[0][] = "market_mains0text05.gif";
				$shop_main_stext[0][] = "market_mains0text06.gif";
				$shop_main_stext[0][] = "market_mains0text07.gif";

				$shop_main_stext[1][] = "market_mains1text01.gif";
				$shop_main_stext[1][] = "market_mains1text02.gif";
				$shop_main_stext[1][] = "market_mains1text03.gif";
				$shop_main_stext[1][] = "market_mains1text04.gif";
				$shop_main_stext[1][] = "market_mains1text07.gif";
				$shop_main_stext[1][] = "market_mains1text05.gif";
				$shop_main_stext[1][] = "market_mains1text06.gif";
				
				$shop_main_stext[2][] = "market_mains2text01.gif";
				$shop_main_stext[2][] = "market_mains2text02.gif";
				$shop_main_stext[2][] = "market_mains2text03.gif";

				$shop_main_stext[3][] = "market_mains3text01.gif";
				$shop_main_stext[3][] = "market_mains3text02.gif";
				$shop_main_stext[3][] = "market_mains3text03.gif";
				$shop_main_stext[3][] = "market_mains3text04.gif";
				$shop_main_stext[3][] = "market_mains3text05.gif";
				$shop_main_stext[3][] = "market_mains3text06.gif";

				$shop_main_slink[0][] = "market_notice.php";
				$shop_main_slink[0][] = "market_contentinfo.php";
				$shop_main_slink[0][] = "market_survey.php";
				$shop_main_slink[0][] = "market_partner.php";
				$shop_main_slink[0][] = "market_affiliatebanner.php";
				$shop_main_slink[0][] = "market_enginepage.php";
				$shop_main_slink[0][] = "market_cash_reserve.php";
				
				$shop_main_slink[1][] = "market_eventpopup.php";
				$shop_main_slink[1][] = "market_quickmenu.php";
				$shop_main_slink[1][] = "market_newproductview.php";
				$shop_main_slink[1][] = "market_eventcode.php";
				$shop_main_slink[1][] = "market_eventbrand.php";
				$shop_main_slink[1][] = "market_eventprdetail.php";
				$shop_main_slink[1][] = "product_giftlist.php";
				
				$shop_main_slink[2][] = "market_couponnew.php";
				$shop_main_slink[2][] = "market_couponsupply.php";
				$shop_main_slink[2][] = "market_couponlist.php";

				$shop_main_slink[3][] = "market_smsconfig.php";
				$shop_main_slink[3][] = "market_smssendlist.php";
				$shop_main_slink[3][] = "market_smssinglesend.php";
				$shop_main_slink[3][] = "market_smsgroupsend.php";
				$shop_main_slink[3][] = "market_smsaddressbook.php";
				$shop_main_slink[3][] = "market_smsfill.php";

				$shop_main_sinfo[0][] = "���������� ���/����/���� �Ͻ� �� �ֽ��ϴ�.";
				$shop_main_sinfo[0][] = "����(information)�� ���/����/���� �Ͻ� �� �ֽ��ϴ�.";
				$shop_main_sinfo[0][] = "�¶�����ǥ�� ���/����/���� �Ͻ� �� �ֽ��ϴ�.";
				$shop_main_sinfo[0][] = "���޻� ���� �� ���޹�ʸ� ���� ������,�ֹ���踦 Ȯ���Ͻ� �� �ֽ��ϴ�.";
				$shop_main_sinfo[0][] = "�α��� ������ ������ ��ϵ� ��ʸ� �����Ͻ� �� �ֽ��ϴ�.";
				$shop_main_sinfo[0][] = "���ݺ� ���� ��ü�� ������ ��ǰ ���� �������� �����մϴ�.";
				$shop_main_sinfo[0][] = "������ ������ȯ ó�����¸� �����Ͻ� �� �ֽ��ϴ�.";
				
				$shop_main_sinfo[1][] = "�̺�Ʈ, ��ް����� ���������� �˾�â�� ���� ������ �̺�Ʈ ������ �˸� �� �ֽ��ϴ�.";
				$shop_main_sinfo[1][] = "���θ� ��ü���������� �׻� ����ٴϴ� ������ Quick�޴��� ������ �� �ֽ��ϴ�. ���� ���� �� �̺�Ʈ ȫ���� ���� Ȱ���ϼ���.";
				$shop_main_sinfo[1][] = "������ ��ǰ�� ���θ� ������ ����������� ����ٴϸ鼭 �����ִ� ��� �Դϴ�.";
				$shop_main_sinfo[1][] = "�� ī�װ��� ������ ��ܿ� �̹��� �Ǵ� Html ������ ���� �̺�Ʈ�� ���� �Ͻ� �� �ֽ��ϴ�.";
				$shop_main_sinfo[1][] = "�� �귣�庰 ������ ��ܿ� �̹��� �Ǵ� Html ������ ���� �̺�Ʈ�� ���� �Ͻ� �� �ֽ��ϴ�.";
				$shop_main_sinfo[1][] = "��ǰ �������� ��������  �������� �̺�Ʈ�� ǥ���� �� �ֽ��ϴ�.";
				$shop_main_sinfo[1][] = "��ǰ �ֹ��� ���ݴ뺰�� ������ ������ ���� ����ǰ�� �����մϴ�.";

				$shop_main_sinfo[2][] = "ȸ���鿡�� �����Ӱ� �������� ���񽺸� ������ �� �ֽ��ϴ�.";
				$shop_main_sinfo[2][] = "������ ������ (��ü ȸ�� �߱�, ȸ�� ��޺� �߱�, ȸ�� ���� �߱�) �����ؼ� �߱� �� �� �ֽ��ϴ�.";
				$shop_main_sinfo[2][] = "���� �������� ���������� ������ Ȯ���� �� �ִ� �޴� �Դϴ�.";

				$shop_main_sinfo[3][] = "SMS ���ڼ��� �⺻ȯ���  �����޴��� ������ �� �ֽ��ϴ�.";
				$shop_main_sinfo[3][] = "SMS ���ڼ��� �߼ۿ� ���� �󼼳����� ������ �� �ֽ��ϴ�.";
				$shop_main_sinfo[3][] = "������� �Ǵ� Ư�� ������ SMS�� �߼��� �� �ֽ��ϴ�.";
				$shop_main_sinfo[3][] = "��üȸ��/���ȸ��/����ȸ������ ��ü SMS �߼��� �� �� �ֽ��ϴ�.";
				$shop_main_sinfo[3][] = "�޴��� ��ȣ�� SMS �ּҷ��� ����� ȸ�������� �� �� �ֽ��ϴ�.";
				$shop_main_sinfo[3][] = "SMS �߼۽� �ʿ��� ���Ḧ �����մϴ�.";

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
					echo "	<td style=\"padding-left:21px\" valign=\"top\" class=\"market_fontcolor\">".$shop_main_sinfo[$i][$j]."</td>\n";
					echo "	<td style=\"padding-left:21px\" valign=\"top\" class=\"market_fontcolor\">".$shop_main_sinfo[$i][$k]."</td>\n";
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