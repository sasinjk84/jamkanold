<?php

#tbldesignnewpage
$backuppage = array(
	'topmenu'		=> array(
							'name'		=> 'type_code',				//����ǰ��ҷ����޴�
							'subject'	=> ''
						),
	'leftmenu'		=> array(
							'name'		=> 'type_code',				//��ܸ޴���
							'subject'	=> ''
						),
	'mainpage'		=> array(
							'name'		=> 'type',				//���κ����ٹ̱�
							'subject'	=> '����������'				
						),
	'bottom'		=> array(
							'name'		=> 'type_code',				//�ϴ�ȭ��ٹ̱�
							'subject'	=> '���θ� �ϴ�'				
						),
	'loginform'		=> array(
							'name'		=> 'type',				//�α��ε����ΰ���
							'subject'	=> '�α�����'				
						),
	'logoutform'	=> array(
							'name'		=> 'type',				//�α׾ƿ������ΰ���
							'subject'	=> '�α׾ƿ���'				
						),
	'prlist'		=> array(
							'name'		=> 'type_code_leftmenu',	//���ī�װ�
							'subject'	=> '��ǰ ī�װ�',
							'info'		=> 'ulist_type'				//tblproductcode all : Y , where codeA+B+C+D
						),
	'prdetail'		=> array(
							'name'		=> 'type_code_leftmenu',				//���ī�װ���
							'subject'	=> '��ǰ�� ȭ��',
							'info'		=> 'udetail_type'			//tblproductcode all : Y , where codeA+B+C+D
						),
	'bttoolsetc'	=> array(
							'name'		=> 'type',				//�⺻���μ���
							'subject'	=> '�⺻���μ���'				
						),
	'bttools'		=> array(
							'name'		=> 'type',				//�÷�bar������
							'subject'	=> '�⺻����ȭ��'				
						),
	'bttoolstdy'	=> array(
						'name'		=> 'type',				//�ֱ� �� ��ǰ����
						'subject'	=> '�ֱ� �� ��ǰ ����'				
					),
	'bttoolswlt'	=> array(
						'name'		=> 'type',				//wishlist ����
						'subject'	=> 'wishlist ����'
					),
	'bttoolsbkt'	=> array(
						'name'		=> 'type',				//��ٱ��� ����
						'subject'	=> '��ٱ��� ����'
					),
	'bttoolsmbr'	=> array(
							'name'		=> 'type',				//ȸ������ ����
							'subject'	=> 'ȸ������ ����'
						),
	'tag'			=> array(
							'name'		=> 'type_leftmenu',				//�α��±�
							'subject'	=> '�α��±� ȭ��',
							'info'		=> 'design_tag'					//tblshopinfo ���뿩�ο� ���� Y : 'U' / N : DesignDeNum
						),
	'tagsearch'		=> array(
							'name'		=> 'type_leftmenu',				//�±װ˻�
							'subject'	=> '�±װ˻� ȭ��',
							'info'		=> 'design_tagsearch'			//tblshopinfo ���뿩�ο� ���� Y : 'U' / N : DesignDeNum
						),
	'prnew'			=> array(
							'name'		=> 'type_leftmenu',				//�Ż�ǰ
							'subject'	=> '���� �Ż�ǰ ȭ��',
							'info'		=> 'design_prnew'				//tblshopinfo ���뿩�ο� ���� Y : 'U' / N : DesignDeNum
						),
	'prbest'		=> array(
							'name'		=> 'type_leftmenu',				//�α��ǰ
							'subject'	=> '���� �α��ǰ ȭ��',
							'info'		=> 'design_prbest'				//tblshopinfo ���뿩�ο� ���� Y : 'U' / N : DesignDeNum
						),
	'prhot'			=> array(
							'name'		=> 'type_leftmenu',				//��õ��ǰ
							'subject'	=> '���� ��õ��ǰ ȭ��',
							'info'		=> 'design_prhot'				//tblshopinfo ���뿩�ο� ���� Y : 'U' / N : DesignDeNum
						),
	'prspecial'		=> array(
							'name'		=> 'type_leftmenu',				//Ư����ǰ
							'subject'	=> '���� Ư����ǰ ȭ��',
							'info'		=> 'design_prspecial'			//tblshopinfo ���뿩�ο� ���� Y : 'U' / N : DesignDeNum
						),
	'search'		=> array(
							'name'		=> 'type_leftmenu',				//��ǰ�˻�
							'subject'	=> '��ǰ�˻� ���ȭ��',
							'info'		=> 'design_search'				//tblshopinfo ���뿩�ο� ���� Y : 'U' / N : DesignDeNum
						),
	'basket'		=> array(
							'name'		=> 'type_leftmenu',				//��ٱ���
							'subject'	=> '��ٱ��� ȭ��',
							'info'		=> 'design_basket'				//tblshopinfo ���뿩�ο� ���� Y : 'U' / N : DesignDeNum

						),
	'primgview'		=> array(
							'name'		=> 'type_leftmenu_filename',			//��ǰ�̹��� Ȯ��â
							'subject'	=> '��ǰ�̹��� Ȯ��â ������'
						),
	'noticelist'		=> array(
							'name'		=> 'type_filename',			//�������� �˾����
							'subject'	=> '�������� �˾� ���â'
						),
	'noticeview'		=> array(
							'name'		=> 'type_filename',			//�������� �˾��������� 
							'subject'	=> '�������� �˾� �� ������'
						),
	'infolist'		=> array(
							'name'		=> 'type_filename',			//�����˾� ���
							'subject'	=> '���� �˾� ���â'
						),
	'infoview'		=> array(
							'name'		=> 'type_filename',			//�����˾� ��������
							'subject'	=> '���� �˾� �� ������'
						),
	'joinmail'		=> array(
							'name'		=> 'type',			//�ű� ȸ�����Ը���
							'subject'	=> 'Y'	//�����Ͱ� �ҷ��ͼ� ����
						),
	'ordermail'		=> array(
							'name'		=> 'type',			//�ֹ���û����
							'subject'	=> 'Y'	//�����Ͱ� �ҷ��ͼ� ����
						),
	'delimail'		=> array(
							'name'		=> 'type',			//�ֹ��߼۸���
							'subject'	=> 'Y'	//�����Ͱ� �ҷ��ͼ� ����
						),
	'bankmail'		=> array(
							'name'		=> 'type',			//�ֹ��Աݸ���
							'subject'	=> 'Y'	//�����Ͱ� �ҷ��ͼ� ����
						),
	'passmail'		=> array(
							'name'		=> 'type',			//���̵�/�н��������
							'subject'	=> 'Y'	//�����Ͱ� �ҷ��ͼ� ����
						),
	'authmail'		=> array(
							'name'		=> 'type',			//ȸ����������
							'subject'	=> 'Y'	//�����Ͱ� �ҷ��ͼ� ����
						),
	'email'		=> array(
							'name'		=> 'type_code_filename',			//������ȭ��
							'subject'	=> '������ ȭ�� ������'
						),
	'board'		=> array(
							'name'		=> 'type_code_leftmenu_filename',			//ȸ����������
							'subject'	=> '�Խ��� ���ȭ�� ������'
						),
	'joinagree'		=> array(
							'name'		=> 'type_leftmenu',			//ȸ�����Ծ��
							'subject'	=> ''
						),
	'mbjoin'		=> array(
							'name'		=> 'type_leftmenu',			//ȸ�������Է���
							'subject'	=> 'ȸ������ �Է��� ������',
							'info'		=> 'design_mbjoin'			//tblshopinfo ���뿩�ο� ���� Y : 'U' / N : DesignDeNum
						),
	'mbmodify'		=> array(
							'name'		=> 'type_leftmenu',			//ȸ������ȭ��
							'subject'	=> 'ȸ���������� ȭ�� ������',
							'info'		=> 'design_mbmodify'		//tblshopinfo ���뿩�ο� ���� Y : 'U' / N : DesignDeNum
						),
	'iddup'		=> array(
							'name'		=> 'type',			//ȸ��IDüũ
							'subject'	=> 'ȸ��ID �ߺ�üũ ȭ�� ������'
						),
	'findpwd'		=> array(
							'name'		=> 'type_leftmenu',			//�н�����н�ȭ��
							'subject'	=> '�н����� �н�ȭ�� ������'
						),
	'login'		=> array(
							'name'		=> 'type_leftmenu',			//�α���ȭ��
							'subject'	=> '�α��� ȭ�� ������'
						),
	'memberout'		=> array(
							'name'		=> 'type_leftmenu',			//ȸ��Ż��ȭ��
							'subject'	=> 'ȸ��Ż�� ȭ�� ������'
						),
	'mypage'		=> array(
							'name'		=> 'type_leftmenu',			//����������
							'subject'	=> 'MyPage ȭ��',
							'info'		=> 'design_mypage'			//tblshopinfo ���뿩�ο� ���� Y : 'U' / N : DesignDeNum

						),
	'orderlist'		=> array(
							'name'		=> 'type_leftmenu',			//�ֹ�����Ʈ
							'subject'	=> '�ֹ�����Ʈ ȭ��',
							'info'		=> 'design_orderlist'		//tblshopinfo ���뿩�ο� ���� Y : 'U' / N : DesignDeNum
						),
	'wishlist'		=> array(
							'name'		=> 'type_leftmenu',			//wishlist
							'subject'	=> 'WishList ȭ��',
							'info'		=> 'design_wishlist'		//tblshopinfo ���뿩�ο� ���� Y : 'U' / N : DesignDeNum
						),
	'mycoupon'		=> array(
							'name'		=> 'type_leftmenu',			//����ȭ��
							'subject'	=> '���������� ���� ȭ��',
							'info'		=> 'design_mycoupon'		//tblshopinfo ���뿩�ο� ���� Y : 'U' / N : DesignDeNum
						),
	'myreserve'		=> array(
							'name'		=> 'type_leftmenu',			//������ȭ��
							'subject'	=> '���������� ������ ȭ��',
							'info'		=> 'design_myreserve'		//tblshopinfo ���뿩�ο� ���� Y : 'U' / N : DesignDeNum
						),
	'mypersonal'		=> array(
							'name'		=> 'type_leftmenu',			//1:1������
							'subject'	=> '���������� 1:1������ ȭ��',
							'info'		=> 'design_mypersonal'		//tblshopinfo ���뿩�ο� ���� Y : 'U' / N : DesignDeNum
						),
	'mycustsect'		=> array(
							'name'		=> 'type_leftmenu',			//�ܰ����
							'subject'	=> '���������� �ܰ���� ȭ��',
							'info'		=> 'design_mycustsect'		//tblshopinfo ���뿩�ο� ���� Y : 'U' / N : DesignDeNum
						),
	'surveylist'		=> array(
							'name'		=> 'type',			//��ǥ����Ʈ
							'subject'	=> '��ǥ����Ʈ ȭ�� ������'
						),
	'surveyview'		=> array(
							'name'		=> 'type',			//��ǥ���
							'subject'	=> '��ǥ��� ȭ�� ������'
						),
	'reviewopen'		=> array(
							'name'		=> 'type_filename',			//��ǰ����
							'subject'	=> '��ǰ���� �˾�ȭ�� ������'
						),
	'reviewall'			=> array(
							'name'		=> 'type',			//������� ȭ�� ������
							'subject'	=> '������� ȭ�� ������'
						),
	'rssinfo'			=> array(
							'name'		=> 'type',			//RSS
							'subject'	=> 'RSS ������'
						),
	'brlist'			=> array(
							'name'		=> 'type_code_leftmenu',	//��ǰ �귣��
							'subject'	=> '��ǰ �귣��',
							'info'		=> 'u_type'					//tblproductbrand all : Y , where bridx
						),
	'bmap'				=> array(
							'name'		=> 'type_leftmenu',			//�귣��� ȭ��
							'subject'	=> '�귣��� ȭ��',
							'info'		=> 'design_bmap'			//tblshopinfo ���뿩�ο� ���� Y : 'U' / N : DesignDeNum
						),
	'newpage'			=> array(
							'name'		=> 'type_leftmenu_code_filename',			//�����߰� �Ϲ�������
							'subject'	=> 'Y'	//�����Ͱ� �ҷ��ͼ� ����
						),
	'community'			=> array(
							'name'		=> 'type_leftmenu_code_filename',			//�����߰� Ŀ�´�Ƽ
							'subject'	=> 'Y'	//�����Ͱ� �ҷ��ͼ� ����
						),
	'rbanner'			=> array(
							'name'		=> 'type_leftmenu_code_filename',			//�ֱٺ���ǰ����
							'subject'	=> 'Y'	//�����Ͱ� �ҷ��ͼ� ����
						),

//���� tbldesign => 2011-09-16 top_height�� ����
	'topmenuall'		=> array(
							'name'		=> 'designtype_height',			
							'subject'	=> ''	
						),

//2011-09-16 �߰�����
	'adultintro'		=> array(
							'name'		=> 'type_leftmenu',			//���θ���Ʈ��
							'subject'	=> ''	
						),
	'adultlogin'		=> array(
							'name'		=> 'type_leftmenu',			//���θ��α���
							'subject'	=> ''	
						),
	'agreement'		=> array(
							'name'		=> 'type_leftmenu',			//�̿���
							'subject'	=> ''	
						),
	'useinfo'		=> array(
							'name'		=> 'type_leftmenu',			//�̿�ȳ�
							'subject'	=> '�̿�ȳ�'	
						),
//2011-11-07 �и��Ǿ� �ִ� quickmenu�� newpage�� �̵���Ŵ by.jyh 
	'quickmenu'			=> array(
							'name'		=> 'type_leftmenu_code_filename',			//�ֱٺ���ǰ����
							'subject'	=> 'Y'	//�����Ͱ� �ҷ��ͼ� ����
						)


);
