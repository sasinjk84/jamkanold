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
			<? include ("menu_order.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �ֹ�/���� &gt; <span class="2depth_select">�ֹ�/���� ����</span></td>
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
							<td background="images/main_titlebg.gif"><img src="images/order_maintitle.gif" border="0"></td>							
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
				$shop_main_title[] = "order_mainstitle1.gif";
				$shop_main_title[] = "order_mainstitle2.gif";
				$shop_main_title[] = "order_mainstitle3.gif";

				$shop_main_stext[0][] = "order_mains0text01.gif";
				$shop_main_stext[0][] = "order_mains0text02.gif";
				$shop_main_stext[0][] = "order_mains0text03.gif";
				$shop_main_stext[0][] = "order_mains0text04.gif";
				$shop_main_stext[0][] = "order_mains0text05.gif";
				$shop_main_stext[0][] = "order_mains0text06.gif";
				$shop_main_stext[0][] = "order_mains0text07.gif";
				$shop_main_stext[0][] = "order_mains0text08.gif";

				$shop_main_stext[1][] = "order_mains1text01.gif";
				$shop_main_stext[1][] = "order_mains1text02.gif";
				$shop_main_stext[1][] = "order_mains1text03.gif";
				
				$shop_main_stext[2][] = "order_mains2text01.gif";
				$shop_main_stext[2][] = "order_mains2text02.gif";
				$shop_main_stext[2][] = "order_mains2text03.gif";
				$shop_main_stext[2][] = "order_mains2text04.gif";

				$shop_main_slink[0][] = "order_list.php";
				$shop_main_slink[0][] = "order_delay.php";
				$shop_main_slink[0][] = "order_delisearch.php";
				$shop_main_slink[0][] = "order_namesearch.php";
				$shop_main_slink[0][] = "order_monthsearch.php";
				$shop_main_slink[0][] = "order_tempinfo.php";
				$shop_main_slink[0][] = "order_excelinfo.php";
				$shop_main_slink[0][] = "order_csvdelivery.php";

				$shop_main_slink[1][] = "order_basket.php";
				$shop_main_slink[1][] = "order_allsale.php";
				$shop_main_slink[1][] = "order_eachsale.php";
				
				$shop_main_slink[2][] = "order_taxsaveabout.php";
				$shop_main_slink[2][] = "order_taxsaveconfig.php";
				$shop_main_slink[2][] = "order_taxsavelist.php";
				$shop_main_slink[2][] = "order_taxsaveissue.php";

				$shop_main_sinfo[0][] = "���ں� ���θ��� ��� �ֹ���Ȳ �� �ֹ������� Ȯ��/ó���Ͻ� �� �ֽ��ϴ�.";
				$shop_main_sinfo[0][] = "�̹��/���Ա� ó���� �ֹ� ������ �Ͻ� �� �ֽ��ϴ�.";
				$shop_main_sinfo[0][] = "�Ա��Ϻ�, ������ں�, �ֹ����ں� �ֹ���Ȳ �� �ֹ������� Ȯ��/ó���Ͻ� �� �ֽ��ϴ�.";
				$shop_main_sinfo[0][] = "�ֹ��� �̸� �� �ֹ����� ������ �ֹ���Ȳ �� �ֹ������� Ȯ���Ͻ� �� �ֽ��ϴ�.";
				$shop_main_sinfo[0][] = "�ش� ��ǰ�� �ֹ��� �ֹ����� Ȯ���Ͻ� �� �ֽ��ϴ�.";
				$shop_main_sinfo[0][] = "���θ������� �����õ� �ǿ� ���� ��Ȳ �� ������ �Ͻ� �� �ֽ��ϴ�.";
				$shop_main_sinfo[0][] = "�ֹ�����Ʈ�� �������Ϸ� �ٿ�ε��� ���, �ֹ�����Ʈ�� �� �׸� �� �迭������ ������ �� �ֽ��ϴ�.";
				$shop_main_sinfo[0][] = "�ټ� �ֹ����� ��������� �������Ϸ� ����� �ֹ�����Ʈ�� �ϰ� �ݿ��ϴ� ����Դϴ�.";
				
				$shop_main_sinfo[1][] = "���� ��ٱ��Ͽ� ���� ��ǰ�� Ȯ���� �� ������, �׿� ���� �м��� �����մϴ�.";
				$shop_main_sinfo[1][] = "��ü ��ǰ�� ���������� Ȯ���Ͻ� �� �ֽ��ϴ�.";
				$shop_main_sinfo[1][] = "������ǰ�� ���������� Ȯ���Ͻ� �� �ֽ��ϴ�.";

				$shop_main_sinfo[2][] = "���ݿ����� ������ ���� �Ұ��� ���ݿ����� ���񽺸� ���� ���θ��� ���� ��û ���� �ȳ��Դϴ�.";
				$shop_main_sinfo[2][] = "���ݿ����� �߱��� ���� ����������� �����Ͻ� �� �ֽ��ϴ�.";
				$shop_main_sinfo[2][] = "���ݿ����� �߱޽�û ��ȸ �� �߱޳��� Ȯ���� �����մϴ�.";
				$shop_main_sinfo[2][] = "���ݿ������� ���������� �߱޿�û�� �����մϴ�.";

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
					echo "	<td style=\"padding-left:21px\" valign=\"top\" class=\"order_fontcolor\">".$shop_main_sinfo[$i][$j]."</td>\n";
					echo "	<td style=\"padding-left:21px\" valign=\"top\" class=\"order_fontcolor\">".$shop_main_sinfo[$i][$k]."</td>\n";
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