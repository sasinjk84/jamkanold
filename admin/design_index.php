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
			<? include ("menu_design.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �����ΰ��� &gt; <span class="2depth_select">�����ΰ��� ����</span></td>
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



			<table cellpadding="0" cellspacing="0" width="100%">
			<tr><td height="8"></td></tr>
			<tr>
				<td>
					<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
						<tr>
							<td background="images/main_titlebg.gif"><img src="images/design_maintitle.gif" border="0"></td>							
						</tr>
					</table>
				</td>
			</tr>
			<tr><td height="20"></td></tr>
			<tr>
				<td valign="top">
				<table cellpadding="0" cellspacing="0" width="100%">
				<col width="50%"></col>
				<col width="50%"></col>
<?
	$shop_main_title[] = "design_mainstitle1.gif";
	$shop_main_title[] = "design_mainstitle2.gif";
	$shop_main_title[] = "design_mainstitle4.gif";
	$shop_main_title[] = "design_mainstitle3.gif";
	$shop_main_title[] = "design_mainstitle5.gif";
	$shop_main_title[] = "design_mainstitle6.gif";
	$shop_main_title[] = "design_mainstitle7.gif";

	$shop_main_stext[0][] = "design_mains0text01.gif";
	$shop_main_stext[0][] = "design_mains0text02.gif";

	$shop_main_stext[1][] = "design_mains1text01.gif";
	$shop_main_stext[1][] = "design_mains1text02.gif";
	$shop_main_stext[1][] = "design_mains1text03.gif";
	$shop_main_stext[1][] = "design_mains1text04.gif";
	$shop_main_stext[1][] = "design_mains1text05.gif";
	
	$shop_main_stext[2][] = "design_mains3text01.gif";
	$shop_main_stext[2][] = "design_mains3text02.gif";
	$shop_main_stext[2][] = "design_mains3text03.gif";
	$shop_main_stext[2][] = "design_mains3text04.gif";
	$shop_main_stext[2][] = "design_mains3text05.gif";
	$shop_main_stext[2][] = "design_mains3text06.gif";
	$shop_main_stext[2][] = "design_mains3text07.gif";
	$shop_main_stext[2][] = "design_mains4text01.gif";
	$shop_main_stext[2][] = "design_mains4text02.gif";

	$shop_main_stext[3][] = "design_mains2text20.gif";
	$shop_main_stext[3][] = "design_mains2text19.gif";
	$shop_main_stext[3][] = "design_mains2text01.gif";
	$shop_main_stext[3][] = "design_mains2text02.gif";
	$shop_main_stext[3][] = "design_mains2text03.gif";
	$shop_main_stext[3][] = "design_mains2text04.gif";
	$shop_main_stext[3][] = "design_mains2text05.gif";
	$shop_main_stext[3][] = "design_mains2text06.gif";
	$shop_main_stext[3][] = "design_mains2text07.gif";
	$shop_main_stext[3][] = "design_mains2text08.gif";
	$shop_main_stext[3][] = "design_mains2text09.gif";
	$shop_main_stext[3][] = "design_mains2text10.gif";
	$shop_main_stext[3][] = "design_mains2text11.gif";
	$shop_main_stext[3][] = "design_mains2text12.gif";
	$shop_main_stext[3][] = "design_mains2text13.gif";
	if(getVenderUsed()==true) { $shop_main_stext[3][] = "design_mains2text21.gif"; }
	$shop_main_stext[3][] = "design_mains2text14.gif";
	$shop_main_stext[3][] = "design_mains2text15.gif";
	$shop_main_stext[3][] = "design_mains2text16.gif";
	$shop_main_stext[3][] = "design_mains2text17.gif";
	$shop_main_stext[3][] = "design_mains2text18.gif";
	$shop_main_stext[3][] = "design_mains2text22.gif";
	$shop_main_stext[3][] = "design_mains2text23.gif";

	$shop_main_stext[4][] = "design_mains4text36.gif";
	$shop_main_stext[4][] = "design_mains4text31.gif";
	$shop_main_stext[4][] = "design_mains4text30.gif";
	$shop_main_stext[4][] = "design_mains4text03.gif";
	$shop_main_stext[4][] = "design_mains4text04.gif";
	$shop_main_stext[4][] = "design_mains4text05.gif";
	$shop_main_stext[4][] = "design_mains4text06.gif";
	$shop_main_stext[4][] = "design_mains4text07.gif";
	$shop_main_stext[4][] = "design_mains4text08.gif";
	$shop_main_stext[4][] = "design_mains4text09.gif";
	$shop_main_stext[4][] = "design_mains4text10.gif";
	$shop_main_stext[4][] = "design_mains4text11.gif";
	$shop_main_stext[4][] = "design_mains4text12.gif";
	$shop_main_stext[4][] = "design_mains4text13.gif";
	$shop_main_stext[4][] = "design_mains4text14.gif";
	$shop_main_stext[4][] = "design_mains4text15.gif";
	$shop_main_stext[4][] = "design_mains4text16.gif";
	$shop_main_stext[4][] = "design_mains4text17.gif";
	$shop_main_stext[4][] = "design_mains4text18.gif";
	$shop_main_stext[4][] = "design_mains4text19.gif";
	$shop_main_stext[4][] = "design_mains4text20.gif";
	$shop_main_stext[4][] = "design_mains4text21.gif";
	$shop_main_stext[4][] = "design_mains4text22.gif";
	$shop_main_stext[4][] = "design_mains4text23.gif";
	$shop_main_stext[4][] = "design_mains4text24.gif";
	$shop_main_stext[4][] = "design_mains4text25.gif";
	if(getVenderUsed()==true) { $shop_main_stext[4][] = "design_mains4text33.gif"; }
	$shop_main_stext[4][] = "design_mains4text26.gif";
	$shop_main_stext[4][] = "design_mains4text27.gif";
	$shop_main_stext[4][] = "design_mains4text28.gif";
	$shop_main_stext[4][] = "design_mains4text29.gif";
	$shop_main_stext[4][] = "design_mains4text32.gif";
	$shop_main_stext[4][] = "design_mains4text34.gif";
	$shop_main_stext[4][] = "design_mains4text35.gif";

	$shop_main_stext[5][] = "design_mains5text01.gif";
	$shop_main_stext[5][] = "design_mains5text02.gif";

	$shop_main_stext[6][] = "design_mains6text01.gif";
	$shop_main_stext[6][] = "design_mains6text02.gif";
	$shop_main_stext[6][] = "design_mains6text03.gif";

	$shop_main_slink[0][] = "design_webftp.php";
	$shop_main_slink[0][] = "design_option.php";

	$shop_main_slink[1][] = "design_adultintro.php";
	$shop_main_slink[1][] = "design_main.php";
	$shop_main_slink[1][] = "design_bottom.php";
	$shop_main_slink[1][] = "design_plist.php";
	$shop_main_slink[1][] = "design_pdetail.php";
	
	$shop_main_slink[2][] = "design_eachintropage.php";
	$shop_main_slink[2][] = "design_eachtitleimage.php";
	$shop_main_slink[2][] = "design_eachtopmenu.php";
	$shop_main_slink[2][] = "design_eachleftmenu.php";
	$shop_main_slink[2][] = "design_eachmain.php";
	$shop_main_slink[2][] = "design_eachbottom.php";
	$shop_main_slink[2][] = "design_eachloginform.php";
	$shop_main_slink[2][] = "design_eachplist.php";
	$shop_main_slink[2][] = "design_eachpdetail.php";

	$shop_main_slink[3][] = "design_tag.php";
	$shop_main_slink[3][] = "design_section.php";
	$shop_main_slink[3][] = "design_search.php";
	$shop_main_slink[3][] = "design_useinfo.php";
	$shop_main_slink[3][] = "design_memberjoin.php";
	$shop_main_slink[3][] = "design_usermodify.php";
	$shop_main_slink[3][] = "design_login.php";
	$shop_main_slink[3][] = "design_basket.php";
	$shop_main_slink[3][] = "design_order.php";
	$shop_main_slink[3][] = "design_mypage.php";
	$shop_main_slink[3][] = "design_orderlist.php";
	$shop_main_slink[3][] = "design_wishlist.php";
	$shop_main_slink[3][] = "design_mycoupon.php";
	$shop_main_slink[3][] = "design_myreserve.php";
	$shop_main_slink[3][] = "design_mypersonal.php";
	if(getVenderUsed()==true) { $shop_main_slink[3][] = "design_mycustsect.php"; }
	$shop_main_slink[3][] = "design_sendmail.php";
	$shop_main_slink[3][] = "design_popupnotice.php";
	$shop_main_slink[3][] = "design_popupinfo.php";
	$shop_main_slink[3][] = "design_formmail.php";
	$shop_main_slink[3][] = "design_cardtopimg.php";
	$shop_main_slink[3][] = "design_blist.php";
	$shop_main_slink[3][] = "design_bmap.php";

	$shop_main_slink[4][] = "design_eachbottomtools.php";
	$shop_main_slink[4][] = "design_eachtag.php";
	$shop_main_slink[4][] = "design_eachsection.php";
	$shop_main_slink[4][] = "design_eachsearch.php";
	$shop_main_slink[4][] = "design_eachbasket.php";
	$shop_main_slink[4][] = "design_eachprimageview.php";
	$shop_main_slink[4][] = "design_eachpopupnotice.php";
	$shop_main_slink[4][] = "design_eachpopupinfo.php";
	$shop_main_slink[4][] = "design_eachsendmail.php";
	$shop_main_slink[4][] = "design_eachformmail.php";
	$shop_main_slink[4][] = "design_eachboardtop.php";
	$shop_main_slink[4][] = "design_eachuseinfo.php";
	$shop_main_slink[4][] = "design_eachagreement.php";
	$shop_main_slink[4][] = "design_eachjoinagree.php";
	$shop_main_slink[4][] = "design_eachmemberjoin.php";
	$shop_main_slink[4][] = "design_eachusermodify.php";
	$shop_main_slink[4][] = "design_eachiddup.php";
	$shop_main_slink[4][] = "design_eachfindpwd.php";
	$shop_main_slink[4][] = "design_eachlogin.php";
	$shop_main_slink[4][] = "design_eachmemberout.php";
	$shop_main_slink[4][] = "design_eachmypage.php";
	$shop_main_slink[4][] = "design_eachorderlist.php";
	$shop_main_slink[4][] = "design_eachwishlist.php";
	$shop_main_slink[4][] = "design_eachmycoupon.php";
	$shop_main_slink[4][] = "design_eachmyreserve.php";
	$shop_main_slink[4][] = "design_eachmypersonal.php";
	if(getVenderUsed()==true) { $shop_main_slink[4][] = "design_eachmycustsect.php"; }
	$shop_main_slink[4][] = "design_eachsurveylist.php";
	$shop_main_slink[4][] = "design_eachsurveyview.php";
	$shop_main_slink[4][] = "design_eachreviewpopup.php";
	$shop_main_slink[4][] = "design_eachreviewall.php";
	$shop_main_slink[4][] = "design_eachrssinfo.php";
	$shop_main_slink[4][] = "design_eachblist.php";
	$shop_main_slink[4][] = "design_eachbmap.php";

	$shop_main_slink[5][] = "design_newpage.php";
	$shop_main_slink[5][] = "design_community.php";

	$shop_main_slink[6][] = "design_easytop.php";
	$shop_main_slink[6][] = "design_easyleft.php";
	$shop_main_slink[6][] = "design_easycss.php";

	$shop_main_sinfo[0][] = "���θ��� ���� ���ϵ��� ���󿡼� ���� �����Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[0][] = "���� ����, ���, ���� ������, ���� Ÿ��Ʋ�� ���������� ������ �� �� �ֽ��ϴ�.";
	
	$shop_main_sinfo[1][] = "���� ���ε����� �� ���ʸ޴�, ���� Ÿ��Ʋ�� �����Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[1][] = "���θ� ����ȭ�� �������� �����Ͽ� ����Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[1][] = "���θ� �ϴ� ȭ�� �������� �����Ͽ� ����Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[1][] = "���θ� ī�װ� ȭ�� �������� �����Ͽ� ����Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[1][] = "���θ� ��ǰ ��ȭ�� �������� �����Ͽ� ����Ͻ� �� �ֽ��ϴ�.";
	
	$shop_main_sinfo[2][] = "��Ʈ�� �������� �����Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[2][] = "���θ��� ���� Ÿ��Ʋ �̹��� �� ���������� ������ Ÿ��Ʋ ������ �����Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[2][] = "��ܸ޴��� ��ü������(default), �Ǵ� ī�װ���, �޴��� �����Ӱ� �������� �����մϴ�.";
	$shop_main_sinfo[2][] = "���ʸ޴��� ��ü������(default), �Ǵ� ī�װ���, �޴��� �����Ӱ� �������� �����մϴ�.";
	$shop_main_sinfo[2][] = "���θ� ���κ���(�����߾�+�����޴��� ��� ����)�� �����Ӱ� �������� �����մϴ�.";
	$shop_main_sinfo[2][] = "�ϴܸ޴� �������� �����Ӱ� �����Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[2][] = "�α��� ȭ�� �������� �����Ӱ� ������ �Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[2][] = "��ǰī�װ� ȭ�� �������� �����Ӱ� ������ �Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[2][] = "��ǰ�� ȭ�� �������� �����Ӱ� ������ �Ͻ� �� �ֽ��ϴ�.";

	$shop_main_sinfo[3][] = "�α��±� �� �±װ˻� ȭ�� �������� �����Ͽ� ����Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[3][] = "���� ���Ǻ� ȭ�� �������� �����Ͽ� ����Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[3][] = "��ǰ�˻� ���ȭ�� �������� �����Ͽ� ����Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[3][] = "���θ� �̿�ȳ� ȭ�� �������� �����Ͽ� ����Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[3][] = "���θ� ȸ������ ȭ�� �������� �����Ͽ� ����Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[3][] = "���θ� ȸ���������� ȭ�� �������� �����Ͽ� ����Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[3][] = "���θ� �α��� �� ��й�ȣ �н� ȭ�� �������� �����Ͽ� ����Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[3][] = "���θ� ��ٱ��� ȭ�� �������� �����Ͽ� ����Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[3][] = "��ǰ �ֹ��� ȭ�� �������� �����Ͽ� ����Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[3][] = "���θ� ���������� ȭ�� �������� �����Ͽ� ����Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[3][] = "���θ� ���������� �ֹ�����Ʈ ȭ�� �������� �����Ͽ� ����Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[3][] = "���θ� WishList ȭ�� �������� �����Ͽ� ����Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[3][] = "���θ� ���������� ���� ȭ�� �������� �����Ͽ� ����Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[3][] = "���θ� ���������� ������ ȭ�� �������� �����Ͽ� ����Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[3][] = "���θ� ���������� 1:1������ ȭ�� �������� �����Ͽ� ����Ͻ� �� �ֽ��ϴ�.";
	if(getVenderUsed()==true) { $shop_main_sinfo[3][] = "���θ� ���������� �ܰ���� ȭ�� �������� �����Ͽ� ����Ͻ� �� �ֽ��ϴ�."; }
	$shop_main_sinfo[3][] = "���θ� ���� ���� ȭ�� �������� �����Ͽ� ����Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[3][] = "���θ� �������� �˾�â �������� �����Ͽ� ����Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[3][] = "���θ� ������(����) �˾�â �������� �����Ͽ� ����Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[3][] = "���θ� ������ �������� �����Ͽ� ����Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[3][] = "ī�����â�� ����̹����� ���θ��� �°� ����/�����Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[3][] = "��ǰ �귣�庰 ȭ�� �������� �����Ͽ� ����Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[3][] = "�귣��� ȭ�� �������� �����Ͽ� ����Ͻ� �� �ֽ��ϴ�.";
	
	$shop_main_sinfo[4][] = "�ϴ� ���θ޴� ȭ�� �������� �����Ӱ� ������ �Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[4][] = "�α��±� �� �±װ˻� ȭ�� �������� �����Ӱ� ������ �Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[4][] = "���� ���Ǻ� ȭ�� �������� �����Ӱ� ������ �Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[4][] = "��ǰ�˻� ���ȭ�� �������� �����Ӱ� ������ �Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[4][] = "��ٱ��� ȭ�� �������� �����Ӱ� ������ �Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[4][] = "��ǰ�̹��� Ȯ��â �������� �����Ӱ� ������ �Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[4][] = "�������� �˾�â �������� �����Ӱ� �����Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[4][] = "���������� �˾�â �������� �����Ӱ� �����Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[4][] = "���� ȭ�� �������� �����Ӱ� ������ �Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[4][] = "������ �˾� �������� �������� �����Ӱ� ������ �Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[4][] = "�Խ��� ��� ȭ�� �������� �����Ӱ� ������ �Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[4][] = "���θ� �̿�ȳ� ȭ�� �������� �����Ӱ� ������ �Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[4][] = "���θ� �̿��� ȭ�� �������� �����Ӱ� ������ �Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[4][] = "���θ� ȸ������ ��� ȭ�� �������� �����Ӱ� ������ �Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[4][] = "���θ� ȸ������ ȭ�� �������� �����Ӱ� ������ �Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[4][] = "ȸ���������� ȭ�� �������� �����Ӱ� ������ �Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[4][] = "ȸ��ID �ߺ�üũ ȭ�� �������� �����Ӱ� ������ �Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[4][] = "�н����� �н�ȭ�� �������� �����Ӱ� ������ �Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[4][] = "�α��� ȭ�� �������� �����Ӱ� ������ �Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[4][] = "ȸ��Ż�� ȭ�� �������� �����Ӱ� ������ �Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[4][] = "���������� ȭ�� �������� �����Ӱ� ������ �Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[4][] = "���������� �ֹ�����Ʈ ȭ�� �������� �����Ӱ� ������ �Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[4][] = "WishList ȭ�� �������� �����Ӱ� ������ �Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[4][] = "���������� ���� ȭ�� �������� �����Ӱ� ������ �Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[4][] = "���������� ������ ȭ�� �������� �����Ӱ� ������ �Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[4][] = "���������� 1:1������ ȭ�� �������� �����Ӱ� ������ �Ͻ� �� �ֽ��ϴ�.";
	if(getVenderUsed()==true) { $shop_main_sinfo[4][] = "���������� �ܰ���� ȭ�� �������� �����Ӱ� ������ �Ͻ� �� �ֽ��ϴ�."; }
	$shop_main_sinfo[4][] = "��ǥ����Ʈ ȭ�� �������� �����Ӱ� ������ �Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[4][] = "��ǥ��� ȭ�� �������� �����Ӱ� ������ �Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[4][] = "�� ��ǰ�� ���信 ���� �󼼺��� �������� �������� �����Ӱ� ������ �Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[4][] = "������� ȭ�� �������� �����Ӱ� ������ �Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[4][] = "RSS ȭ�� �������� �����Ӱ� ������ �Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[4][] = "��ǰ �귣�庰 ȭ�� �������� �����Ӱ� ������ �Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[4][] = "�귣��� ȭ�� �������� �����Ӱ� ������ �Ͻ� �� �ֽ��ϴ�.";
	
	$shop_main_sinfo[5][] = "���� �Ϲ��������� ��� �� �����Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[5][] = "Ŀ�´�Ƽ �������� ��� �� �����Ͻ� �� �ֽ��ϴ�.";

	$shop_main_sinfo[6][] = "���θ� ��� �������� ���� �����Ͻ� �̹��������� �̿��Ͽ�, �����ϰ� �������� �Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[6][] = "���θ� ���ʸ޴� �������� ���� �����Ͻ� �̹��������� �̿��Ͽ�, �����ϰ� �������� �Ͻ� �� �ֽ��ϴ�.";
	$shop_main_sinfo[6][] = "����������, ��ǰī�װ�, �˻�ȭ�鿡�� �������� �ؽ�Ʈ���� �Ӽ��� �����ϰ� �����Ͻ� �� �ֽ��ϴ�.";

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
		echo "	<td style=\"padding-left:21px\" valign=\"top\" class=\"design_fontcolor\">".$shop_main_sinfo[$i][$j]."</td>\n";
		echo "	<td style=\"padding-left:21px\" valign=\"top\" class=\"design_fontcolor\">".$shop_main_sinfo[$i][$k]."</td>\n";
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
			<tr>
				<td height="30"></td>
			</tr>
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