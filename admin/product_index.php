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
			<? include ("menu_product.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ��ǰ���� &gt; <span class="2depth_select">��ǰ���� ����</span></td>
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
							<td background="images/main_titlebg.gif"><img src="images/product_maintitle.gif" border="0"></td>							
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
				$shop_main_title[] = "product_mainstitle1.gif";
				$shop_main_title[] = "product_mainstitle2.gif";
				$shop_main_title[] = "product_mainstitle3.gif";
				$shop_main_title[] = "product_mainstitle4.gif";
				$shop_main_title[] = "product_mainstitle5.gif";
				$shop_main_title[] = "product_mainstitle6.gif";
				$shop_main_title[] = "product_mainstitle7.gif";

				$shop_main_stext[0][] = "product_mains0text01.gif";
				$shop_main_stext[0][] = "product_mains0text02.gif";
				$shop_main_stext[0][] = "product_mains0text12.gif";
				$shop_main_stext[0][] = "product_mains0text03.gif";
				$shop_main_stext[0][] = "product_mains0text04.gif";
				$shop_main_stext[0][] = "product_mains0text05.gif";
				$shop_main_stext[0][] = "product_mains0text06.gif";
				$shop_main_stext[0][] = "product_mains0text07.gif";
				$shop_main_stext[0][] = "product_mains0text08.gif";
				$shop_main_stext[0][] = "product_mains0text09.gif";
				$shop_main_stext[0][] = "product_mains0text10.gif";
				$shop_main_stext[0][] = "product_mains0text11.gif";
				$shop_main_stext[0][] = "product_mains0text15.gif";
				$shop_main_stext[0][] = "product_mains0text13.gif";
				$shop_main_stext[0][] = "product_mains0text14.gif";

				$shop_main_stext[1][] = "product_mains1text01.gif";
				$shop_main_stext[1][] = "product_mains1text02.gif";
				
				$shop_main_stext[2][] = "product_mains2text01.gif";
				$shop_main_stext[2][] = "product_mains2text02.gif";
				
				$shop_main_stext[3][] = "product_mains3text01.gif";
				$shop_main_stext[3][] = "product_mains3text02.gif";
				$shop_main_stext[3][] = "product_mains3text03.gif";
				$shop_main_stext[3][] = "product_mains3text04.gif";
				$shop_main_stext[3][] = "product_mains3text05.gif";
				$shop_main_stext[3][] = "product_mains3text06.gif";
				$shop_main_stext[3][] = "product_mains3text07.gif";

				$shop_main_stext[4][] = "product_mains4text01.gif";
				$shop_main_stext[4][] = "product_mains4text02.gif";
				$shop_main_stext[4][] = "product_mains4text03.gif";
				$shop_main_stext[4][] = "product_mains4text04.gif";
				$shop_main_stext[4][] = "product_mains4text05.gif";
				$shop_main_stext[4][] = "product_mains4text06.gif";

				$shop_main_stext[5][] = "product_mains5text01.gif";

				$shop_main_stext[6][] = "product_mains6text01.gif";

				$shop_main_slink[0][] = "product_code.php";
				$shop_main_slink[0][] = "product_register.php";
				$shop_main_slink[0][] = "product_assemble.php";
				$shop_main_slink[0][] = "product_mainlist.php";
				$shop_main_slink[0][] = "product_codelist.php";
				$shop_main_slink[0][] = "product_sort.php";
				$shop_main_slink[0][] = "product_copy.php";
				$shop_main_slink[0][] = "product_theme.php";
				$shop_main_slink[0][] = "product_detaillist.php";
				$shop_main_slink[0][] = "product_deliinfo.php";
				$shop_main_slink[0][] = "product_brand.php";
				$shop_main_slink[0][] = "product_business.php";
				$shop_main_slink[0][] = "product2_register.php";
				$shop_main_slink[0][] = "product_latestup.php";
				$shop_main_slink[0][] = "product_latestsell.php";

				$shop_main_slink[1][] = "product_imgmulticonfig.php";
				$shop_main_slink[1][] = "product_imgmultiset.php";

				$shop_main_slink[2][] = "product_collectionconfig.php";
				$shop_main_slink[2][] = "product_collectionlist.php";
				
				$shop_main_slink[3][] = "product_allupdate.php";
				$shop_main_slink[3][] = "product_reserve.php";
				$shop_main_slink[3][] = "product_price.php";
				$shop_main_slink[3][] = "product_allsoldout.php";
				$shop_main_slink[3][] = "product_allquantity.php";
				$shop_main_slink[3][] = "product_excelupload.php";
				$shop_main_slink[3][] = "product_exceldownload.php";

				$shop_main_slink[4][] = "product_giftlist.php";
				$shop_main_slink[4][] = "product_estimate.php";
				$shop_main_slink[4][] = "product_review.php";
				$shop_main_slink[4][] = "product_wishlist.php";
				$shop_main_slink[4][] = "product_keywordsearch.php";
				$shop_main_slink[4][] = "product_detailfilter.php";

				$shop_main_slink[5][] = "product_option.php";

				$shop_main_slink[6][] = "product_package.php";

				$shop_main_sinfo[0][] = "ī�װ� �߰�, ����, ������ �����ϸ� ī�װ��� ���ø��� ������ �� �ֽ��ϴ�.";
				$shop_main_sinfo[0][] = "��ǰ ���/����/������ ������ �� �ֽ��ϴ�.";
				$shop_main_sinfo[0][] = "�ڵ�/���� ��ǰ���� ��ϵ� ��ǰ�� ���� ��ǰ ���/����/���� �� �� �ֽ��ϴ�.";
				$shop_main_sinfo[0][] = "���θ� ������������ �Ż�ǰ, �α��ǰ, ��õ��ǰ, Ư����ǰ�� ������ ��ǰ�� ����� �� �ֽ��ϴ�.";
				$shop_main_sinfo[0][] = "���θ� ī�װ��������� �Ż�ǰ, �α��ǰ, ��õ��ǰ�� ������ ��ǰ�� ����� �� �ֽ��ϴ�.";
				$shop_main_sinfo[0][] = "������ ī�װ��� ��ϵ� ��ǰ�� ���� ������ ������ �� �ֽ��ϴ�.";
				$shop_main_sinfo[0][] = "��ǰ�� ī�װ����� �ٸ� ī�װ��� �̵�/���� �Ҽ� ������ ���� ������ �����մϴ�.";
				$shop_main_sinfo[0][] = "����ī�װ��� ��ǰ�� ���/������ �� �ֽ��ϴ�.";
				$shop_main_sinfo[0][] = "��ǰ ������������ ����Ǵ� ����ǰ��  ���׸� ������ ������ �� �ֽ��ϴ�.";
				$shop_main_sinfo[0][] = "���/��ȯ/ȯ������ ���õ� ������ ��ǰ��ȭ�� �ϴܿ� ���������� ������ �� �ֵ��� �����Ͻ� �� �ֽ��ϴ�.";
				$shop_main_sinfo[0][] = "�귣�� �߰�, ����, ������ �����ϸ� �귣�� ���� �������� ��� ������ �� �� �ֽ��ϴ�.";
				$shop_main_sinfo[0][] = "��ǰ �ŷ�ó�� ���/����/������ ������ �� �ֽ��ϴ�.";
				$shop_main_sinfo[0][] = "��ǰ�� ���/����/������ ������ �� �ֽ��ϴ�.";
				$shop_main_sinfo[0][] = "�ֱ� ��ϵ� ��ǰ����Ʈ�Դϴ�.";
				$shop_main_sinfo[0][] = "�ֱ� �Ǹŵ� ��ǰ����Ʈ�Դϴ�.";
				
				$shop_main_sinfo[1][] = "���θ� ��ǰ�� �����̹����� ���÷��� ��ġ�� ������ �� �ֽ��ϴ�.";
				$shop_main_sinfo[1][] = "��ǰ��Ͻ� ��/��/�� �̹��� �� 10������ �̹����� �������� �� ������ �� �ֽ��ϴ�.";

				$shop_main_sinfo[2][] = "��ǰ ������������ ���û�ǰ�� �������� �� ������ǰ��, ������ġ�� ������ �� �ֽ��ϴ�.";
				$shop_main_sinfo[2][] = "��ǰ ���������� ������ ���û�ǰ ���/������ �� �ֽ��ϴ�.";
				
				$shop_main_sinfo[3][] = "��ϵ� ��ǰ�� ������ ������ ������,���� ���� �ϰ� ������ �� �ֽ��ϴ�.";
				$shop_main_sinfo[3][] = "���θ��� ��ϵ� ��� ��ǰ�� �������� �ϰ� ������ �� �ֽ��ϴ�.";
				$shop_main_sinfo[3][] = "���θ��� ��ϵ� ��� ��ǰ�� ������ �ϰ� ������ �� �ֽ��ϴ�.";
				$shop_main_sinfo[3][] = "ǰ���� ��ǰ�� ��ü������ ����/��� �� ������ �Ͻ� �� �ֽ��ϴ�.";
				$shop_main_sinfo[3][] = "��� ��ǰ�� ��� Ȯ���� �� �ֽ��ϴ�.";
				$shop_main_sinfo[3][] = "�ټ� ��ǰ������ �������Ϸ� �ۼ��Ͽ� �ϰ� ����Ͻ� �� �ֽ��ϴ�.";
				$shop_main_sinfo[3][] = "��ǰ���� Excel(.csv) �������� �ٿ�ε��� �� �ֽ��ϴ�.";

				$shop_main_sinfo[4][] = "��ǰ �ֹ��� ���ݴ뺰�� ������ ������ ���� ����ǰ�� �����մϴ�.";
				$shop_main_sinfo[4][] = "���θ� ī�װ��� ������ ������ �� �ֽ��ϴ�.";
				$shop_main_sinfo[4][] = "���θ� ��ü ��ǰ���� ���並 ������ �� �Դϴ�.";
				$shop_main_sinfo[4][] = "Wishlist�� ������ ��ǰ�� Ȯ���Ͻ� �� �ֽ��ϴ�.";
				$shop_main_sinfo[4][] = "���θ��� ��� ��ǰ�� ��ǰ�� �� Ű����� �˻� �Ͻ� �� �ֽ��ϴ�";
				$shop_main_sinfo[4][] = "��ǰ���������� ��ǰ�󼼳����� �ܾ� ���͸��� ���� ����� �ִ� ����Դϴ�.";
				
				$shop_main_sinfo[5][] = "��ǰ�� ���/������ ������ �ɼǱ׷��� ����� �� �ֽ��ϴ�.";

				$shop_main_sinfo[6][] = "��ǰ�� ���/������ ������ ��Ű���� ����� �� �ֽ��ϴ�.";

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
					echo "	<td style=\"padding-left:21px\" valign=\"top\" class=\"product_fontcolor\">".$shop_main_sinfo[$i][$j]."</td>\n";
					echo "	<td style=\"padding-left:21px\" valign=\"top\" class=\"product_fontcolor\">".$shop_main_sinfo[$i][$k]."</td>\n";
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