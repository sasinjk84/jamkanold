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
			<? include ("menu_community.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : Ŀ�´�Ƽ &gt; <span class="2depth_select">Ŀ�´�Ƽ ����</span></td>
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
							<td background="images/main_titlebg.gif"><img src="images/community_maintitle.gif" border="0"></td>							
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
				$shop_main_title[] = "community_mainstitle1.gif";

				$shop_main_stext[0][] = "community_mains0text01.gif";
				$shop_main_stext[0][] = "community_mains0text02.gif";
				$shop_main_stext[0][] = "community_mains0text03.gif";
				$shop_main_stext[0][] = "community_mains0text04.gif";
				$shop_main_stext[0][] = "community_mains0text05.gif";
				$shop_main_stext[0][] = "community_mains0text06.gif";

				$shop_main_slink[0][] = "community_list.php";
				$shop_main_slink[0][] = "community_register.php";
				$shop_main_slink[0][] = "community_article.php";
				$shop_main_slink[0][] = "community_notice.php";
				$shop_main_slink[0][] = "community_personal.php";
				$shop_main_slink[0][] = "community_schedule_year.php";

				$shop_main_sinfo[0][] = "��ϵ� �Խ����� ���/������ ���� �� ����ó���� �� �� �ֽ��ϴ�.";
				$shop_main_sinfo[0][] = "Ŀ�´�Ƽ ���ݿ� �´� �پ��� �Խ����� ������ �� �ֽ��ϴ�.";
				$shop_main_sinfo[0][] = "��ϵ� �Խ����� ��� �Խù��� ������ �� �ֽ��ϴ�.";
				$shop_main_sinfo[0][] = "�Խ��� ��ܿ� ��� ���������� ������ �� �ֽ��ϴ�.";
				$shop_main_sinfo[0][] = "1:1 ������ �Խ��� ���� �� ���ǿ� ���� �亯 ������ �Ͻ� �� �ֽ��ϴ�.";
				$shop_main_sinfo[0][] = "���θ��� �ֿ� ������ �����Ͻ� �� �ֽ��ϴ�.";

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
					echo "	<td style=\"padding-left:21px\" valign=\"top\" class=\"community_fontcolor\">".$shop_main_sinfo[$i][$j]."</td>\n";
					echo "	<td style=\"padding-left:21px\" valign=\"top\" class=\"community_fontcolor\">".$shop_main_sinfo[$i][$k]."</td>\n";
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