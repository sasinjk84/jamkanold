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
		<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed" background="images/con_bg.gif">
		<col width=198></col>
		<col width=10></col>
		<col width=></col>
		<tr>
			<td valign="top" background="images/leftmenu_bg.gif">
			<? include ("menu_shop.php"); ?>
			</td>

			<td></td>
			<td valign="top">





<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �������� &gt; <span class="2depth_select">�������� ����</span></td>
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
			<tr>
				<td>
					<table cellpadding="0" cellspacing="0" width="99%" style="table-layout:fixed">
						<tr>
							<td background="images/main_titlebg.gif"><img src="images/shop_main_title.gif" border="0"></td>							
						</tr>
					</table>
				</td>
			</tr>
			<tr><td height="20"></td></tr>
			<tr>
				<td valign="top">
				<table cellpadding="0" cellspacing="0" width="99%" style="table-layout:fixed">
				<col width="50%"></col>
				<col width="50%"></col>
<?
				$shop_main_title[] = "shop_main_stitle1.gif";
				$shop_main_title[] = "shop_main_stitle2.gif";
				$shop_main_title[] = "shop_main_stitle3.gif";
				$shop_main_title[] = "shop_main_stitle4.gif";

				$shop_main_stext[0][] = "shop_main_s0text01.gif";
				$shop_main_stext[0][] = "shop_main_s0text02.gif";
				$shop_main_stext[0][] = "shop_main_s0text03.gif";
				$shop_main_stext[0][] = "shop_main_s0text04.gif";
				$shop_main_stext[0][] = "shop_main_s0text05.gif";
				$shop_main_stext[0][] = "shop_main_s0text06.gif";

				$shop_main_stext[1][] = "shop_main_s1text01.gif";
				$shop_main_stext[1][] = "shop_main_s1text09.gif";
				$shop_main_stext[1][] = "shop_main_s1text02.gif";
				$shop_main_stext[1][] = "shop_main_s1text03.gif";
				$shop_main_stext[1][] = "shop_main_s1text10.gif";
				$shop_main_stext[1][] = "shop_main_s1text04.gif";
				$shop_main_stext[1][] = "shop_main_s1text05.gif";
				$shop_main_stext[1][] = "shop_main_s1text06.gif";
				$shop_main_stext[1][] = "shop_main_s1text07.gif";
				$shop_main_stext[1][] = "shop_main_s1text08.gif";
				
				$shop_main_stext[2][] = "shop_main_s2text12.gif";
				$shop_main_stext[2][] = "shop_main_s2text11.gif";
				$shop_main_stext[2][] = "shop_main_s2text01.gif";
				$shop_main_stext[2][] = "shop_main_s2text02.gif";
				$shop_main_stext[2][] = "shop_main_s2text03.gif";
				$shop_main_stext[2][] = "shop_main_s2text04.gif";
				$shop_main_stext[2][] = "shop_main_s2text05.gif";
				$shop_main_stext[2][] = "shop_main_s2text06.gif";
				$shop_main_stext[2][] = "shop_main_s2text07.gif";
				$shop_main_stext[2][] = "shop_main_s2text08.gif";
				$shop_main_stext[2][] = "shop_main_s2text09.gif";
				$shop_main_stext[2][] = "shop_main_s2text10.gif";
				$shop_main_stext[2][] = "shop_main_s2text13.gif";
				
				$shop_main_stext[3][] = "shop_main_s3text02.gif";
				$shop_main_stext[3][] = "shop_main_s3text01.gif";
				$shop_main_stext[3][] = "shop_main_s3text03.gif";
				$shop_main_stext[3][] = "shop_main_s3text04.gif";

				$shop_main_slink[0][] = "shop_basicinfo.php";
				$shop_main_slink[0][] = "shop_keyword.php";
				$shop_main_slink[0][] = "shop_mainintro.php";
				$shop_main_slink[0][] = "shop_companyintro.php";
				$shop_main_slink[0][] = "shop_agreement.php";
				$shop_main_slink[0][] = "shop_privercyinfo.php";

				$shop_main_slink[1][] = "shop_openmethod.php";
				$shop_main_slink[1][] = "shop_layout.php";
				$shop_main_slink[1][] = "shop_displaytype.php";
				$shop_main_slink[1][] = "shop_mainproduct.php";
				$shop_main_slink[1][] = "shop_productshow.php";
				$shop_main_slink[1][] = "shop_mainleftinform.php";
				$shop_main_slink[1][] = "shop_logobanner.php";
				$shop_main_slink[1][] = "shop_orderform.php";
				$shop_main_slink[1][] = "shop_ssl.php";
				$shop_main_slink[1][] = "shop_bizsiren.php";
				
				$shop_main_slink[2][] = "shop_tag.php";
				$shop_main_slink[2][] = "shop_search.php";
				$shop_main_slink[2][] = "shop_reserve.php";
				$shop_main_slink[2][] = "shop_recommand.php";
				$shop_main_slink[2][] = "shop_member.php";
				$shop_main_slink[2][] = "shop_deli.php";
				$shop_main_slink[2][] = "shop_payment.php";
				$shop_main_slink[2][] = "shop_escrow.php";
				$shop_main_slink[2][] = "shop_return.php";
				$shop_main_slink[2][] = "shop_basket.php";
				$shop_main_slink[2][] = "shop_review.php";
				$shop_main_slink[2][] = "shop_estimate.php";
				$shop_main_slink[2][] = "shop_snsinfo.php";
				
				$shop_main_slink[3][] = "shop_rolelist.php";
				$shop_main_slink[3][] = "shop_adminlist.php";
				$shop_main_slink[3][] = "shop_iplist.php";
				$shop_main_slink[3][] = "shop_changeadminpasswd.php";

				$shop_main_sinfo[0][] = "����ڵ������, �������� ��� �⺻���� ������ �����մϴ�.";
				$shop_main_sinfo[0][] = "���θ� ��ܿ� ǥ�õǴ� Ÿ��Ʋ�ٿ� �˻��� ��Ÿ�ױ׸� �����մϴ�.";
				$shop_main_sinfo[0][] = "���θ� ���� �߾��� Ÿ��Ʋ �̹��� ������ ������ �����մϴ�.";
				$shop_main_sinfo[0][] = "ȸ��Ұ��� ����, �൵� �����մϴ�.";
				$shop_main_sinfo[0][] = "���θ� �̿����� �����մϴ�.";
				$shop_main_sinfo[0][] = "����������޹�ħ, ����å���� ������ �����մϴ�.";

				$shop_main_sinfo[1][] = "Ư�� ���θ��� ���� ȸ��/��ȸ��, ���θ����� ���θ� ������ �� �ֽ��ϴ�.";
				$shop_main_sinfo[1][] = "���θ� ��ü������ ���� �� ������� ����� ������ �� �ֽ��ϴ�.";
				$shop_main_sinfo[1][] = "������, ������ ���ļ���, ��ǰ���Է��� �������� ����� �ϰ� ������ �� �ֽ��ϴ�.";
				$shop_main_sinfo[1][] = "���θ��� ���� ��ǰ �� ī�װ� ��ǰ�� �������� ���� ��ǰ ���� Ÿ���� ������ �� �ֽ��ϴ�.";
				$shop_main_sinfo[1][] = "���θ��� ��ǰ ���� ���� ������ �� �� �ֽ��ϴ�.";
				$shop_main_sinfo[1][] = "���θ� ���� ���� �ϴ� ������ ������ �˸��� ���¹�ȣ, �ʼ� �������� ���� ����� �� �ֽ��ϴ�.";
				$shop_main_sinfo[1][] = "���θ��� �ΰ� �� ��ʸ� ���/�����Ͻ� �� �ֽ��ϴ�.";
				$shop_main_sinfo[1][] = "ȸ������ �� �ֹ��� ���θ� ����� �޼����� ����� �� �ֽ��ϴ�.";
				$shop_main_sinfo[1][] = "������ ������ ������ ���� SSL(���ȼ���) ��� ������ �� �� �ֽ��ϴ�.";
				$shop_main_sinfo[1][] = "�Ǹ����� ������ ����� ��� ���θ��� �������� �� ����Ȯ�� ó���� �����մϴ�.";
				
				$shop_main_sinfo[2][] = "��ǰ�� �±�(Tag) ���� ����� �����Ͻ� �� �ֽ��ϴ�.";
				$shop_main_sinfo[2][] = "��ǰ�˻��� �α�˻��� ���� ����� �����Ͻ� �� �ֽ��ϴ�.";
				$shop_main_sinfo[2][] = "�����ڿ� ���� ������/���� ���� ���ǰ� ��밡�� ����, �⺻ ���޺����� ������ �� �ֽ��ϴ�.";
				$shop_main_sinfo[2][] = "��ȸ�� ������ ��õ�� ��õ�ο��� ���� ������ �ο��� �� �ֽ��ϴ�.";
				$shop_main_sinfo[2][] = "�⺻ ȸ������ �Է��� + �߰� �Է��� , �ֹι�ȣ ��� �� Ż������ �� �� �ֽ��ϴ�.";
				$shop_main_sinfo[2][] = "��ǰ ��۰��� ������ ���θ� ���ݿ� �°� �����Ͻ� �� �ֽ��ϴ�.";
				$shop_main_sinfo[2][] = "����/�ſ�ī�� ������ ������, �ּұ��ž�, �������Һ�, �ſ�ī�� ��������� ������ �� �ֽ��ϴ�.";
				$shop_main_sinfo[2][] = "���θ��� ������� ��ġ��(����ũ��)�� ���� ������ �Ͻ� �� �ֽ��ϴ�.";
				$shop_main_sinfo[2][] = "��ȯ/��ǰ/ȯ�ҿ� ���� ������ �Ͻ� �� �ֽ��ϴ�.";
				$shop_main_sinfo[2][] = "��ٱ��Ͽ� ���õ� ����� ������ �� �ֽ��ϴ�.";
				$shop_main_sinfo[2][] = "��ǰ����ı��� ��뿩��, �Խù��, �ۼ������� ������ �� �ֽ��ϴ�.";
				$shop_main_sinfo[2][] = "���θ��� ��ǰ������ ����� ������ �� �ֽ��ϴ�.";
				$shop_main_sinfo[2][] = "SNS�� ���� ��ǰ ȫ���� ���� ������ �ο��� �� �ֽ��ϴ�.";
				
				$shop_main_sinfo[3][] = "���������� �޴��� ���ٱ��� �׷��� �����մϴ�.";
				$shop_main_sinfo[3][] = "���/�ο�� ���� ���� �� ����, �ο�� �� �޴� ������/�������� ���� ������ �� �ֽ��ϴ�.";
				$shop_main_sinfo[3][] = "������������ ������ �� �ִ� ���/�ο�� IP�� �����մϴ�.";
				$shop_main_sinfo[3][] = "���/�ο�ں� ������ �н����带 ������ �� �ֽ��ϴ�.";

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
						echo "	<td style=\"padding-left:21px\" valign=\"top\" class=\"shop_fontcolor\">".$shop_main_sinfo[$i][$j]."</td>\n";
						echo "	<td style=\"padding-left:21px\" valign=\"top\" class=\"shop_fontcolor\">".$shop_main_sinfo[$i][$k]."</td>\n";
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