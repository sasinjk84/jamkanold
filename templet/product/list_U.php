<?

// ���� ī�װ��� �ڵ� ����
$substrststr = '[SUBCATEGORY_START]';
$substredstr = '[SUBCATEGORY_END]';
$subcatemat = '{{__catestr_'.time().'}}';
if((false !== $spos = strpos($body,$substrststr)) && (false !== $sepos = strpos($body,$substredstr))){
	$subcatestr = substr($body,$spos+strlen($substrststr),$sepos-$spos-strlen($substrststr));
	$body = substr_replace($body,$subcatemat,$spos,$sepos-$spos+strlen($substredstr));
}



$codename=$_cdata->code_name;

if(!_empty($_cdata->codeC) && $_cdata->codeC != '000'){
	$tmp = getCategoryItems(substr($code,0,9),true);
	if(is_array($tmp) && count($tmp) > 0 && count($tmp['items']) > 0){
		$sql = "SELECT code_name FROM tblproductcode WHERE codeA='".$_cdata->codeA."' AND codeB='".$_cdata->codeB."' AND codeC='".$_cdata->codeC."' AND codeD='000' limit 1 ";
	}else{
		$sql = "SELECT code_name FROM tblproductcode WHERE codeA='".$_cdata->codeA."' AND codeB='".$_cdata->codeB."' AND codeC='000' AND codeD='000' limit 1 ";
	}
}else if(!_empty($_cdata->codeB) && $_cdata->codeB != '000'){
	$sql = "SELECT code_name FROM tblproductcode WHERE codeA='".$_cdata->codeA."' AND codeB='".$_cdata->codeB."' AND codeC='000' AND codeD='000' limit 1 ";
}else{
	$sql = "SELECT code_name FROM tblproductcode WHERE codeA='".$_cdata->codeA."' AND codeB='000' AND codeC='000' AND codeD='000' limit 1 ";
}
if(false !== $cres = mysql_query($sql,get_db_conn())){
	if(mysql_num_rows($cres)){
		$codename = mysql_result($cres,0,0);
	}
}

$clipcopy="\"javascript:ClipCopy('http://".getenv("HTTP_HOST")."/?".getenv("QUERY_STRING")."')\"";

$codenavi="";
if($num=strpos($body,"[CODENAVI")) {
	$s_tmp=explode("_",substr($body,$num+9,13));
	$codenavi=getCodeLoc($code,$s_tmp[0],$s_tmp[1]);
}


//��õ��ǰ ����
$hotitem_type="";
if($num=strpos($body,"[HOTITEM")) {
	$hotitem_type=substr($body,$num+8,1);
	if($hotitem_type=="1") {	//�̹���A��
		$hotitem_type="";
		$match=array();
		$default_hotitem1=array("5","1","N","N","N","N","0","N","N","N","N");
		if (preg_match("/\[HOTITEM1([0-9LNY_]{2,14})\]/",$body,$match)) {
			$match_array=explode("_",$match[1]);
			for ($i=0;$i<strlen($match_array[0]);$i++) {
				$default_hotitem1[$i]=$match_array[0][$i];
			}
			$hotitem_type="1";
		}

		$hotitem1_cols=(int)$default_hotitem1[0];
		$hotitem1_rows=(int)$default_hotitem1[1];
		$hotitem1_rowline=$default_hotitem1[2];		// ��ǰ���ζ��ο���
		$hotitem1_colline=$default_hotitem1[3];		// ��ǰ���ζ��ο���
		$hotitem1_price=$default_hotitem1[4];		// �Һ��ڰ� ǥ�ÿ���
		$hotitem1_reserve=$default_hotitem1[5];		// ������ ǥ�ÿ���
		$hotitem1_tag=(int)$default_hotitem1[6];	// �±� ǥ�ð���(0-9) 0�� ��� ǥ�þ���
		$hotitem1_production=$default_hotitem1[7];	// ������ ǥ�ÿ���
		$hotitem1_madein=$default_hotitem1[8];	// ������ ǥ�ÿ���
		$hotitem1_model=$default_hotitem1[9];	// �𵨸� ǥ�ÿ���
		$hotitem1_brand=$default_hotitem1[10];	// �귣�� ǥ�ÿ���
		if($hotitem1_cols==0 || $hotitem1_cols==9) $hotitem1_cols=5;
		if($hotitem1_rows==0 || $hotitem1_rows==9) $hotitem1_rows=1;
		$hotitem1_gan=(($match_array[1]+0)>99)?"99":($match_array[1]+0);
		if($hotitem1_gan==0) $hotitem1_gan=5;

		$hotitem1_colnum=$hotitem1_cols*2-1;
		$hotitem1_product_num=$hotitem1_cols*$hotitem1_rows;
		if($hotitem1_cols==6)		$hotitem1_imgsize=$_data->primg_minisize-5;
		else if($hotitem1_cols==7)	$hotitem1_imgsize=$_data->primg_minisize-10;
		else if($hotitem1_cols==8)	$hotitem1_imgsize=$_data->primg_minisize-20;
		else						$hotitem1_imgsize=$_data->primg_minisize;

		$hotitem_product_num=$hotitem1_product_num;
	} else if($hotitem_type=="2") {	//�̹���B��
		$hotitem_type="";
		$match=array();
		$default_hotitem2=array("2","1","N","N","N","N","0","N","N","N","N");
		if (preg_match("/\[HOTITEM2([0-9LNY_]{2,14})\]/",$body,$match)) {
			$match_array=explode("_",$match[1]);
			for ($i=0;$i<strlen($match_array[0]);$i++) {
				$default_hotitem2[$i]=$match_array[0][$i];
			}
			$hotitem_type="2";
		}

		$hotitem2_cols=(int)$default_hotitem2[0];
		$hotitem2_rows=(int)$default_hotitem2[1];
		$hotitem2_rowline=$default_hotitem2[2];		// ��ǰ���ζ��ο���
		$hotitem2_colline=$default_hotitem2[3];		// ��ǰ���ζ��ο���
		$hotitem2_price=$default_hotitem2[4];		// �Һ��ڰ� ǥ�ÿ���
		$hotitem2_reserve=$default_hotitem2[5];		// ������ ǥ�ÿ���
		$hotitem2_tag=(int)$default_hotitem2[6];	// �±� ǥ�ð���(0-9) 0�� ��� ǥ�þ���
		$hotitem2_production=$default_hotitem2[7];	// ������ ǥ�ÿ���
		$hotitem2_madein=$default_hotitem2[8];	// ������ ǥ�ÿ���
		$hotitem2_model=$default_hotitem2[9];	// �𵨸� ǥ�ÿ���
		$hotitem2_brand=$default_hotitem2[10];	// �귣�� ǥ�ÿ���
		if($hotitem2_cols==0 || $hotitem2_cols==9) $hotitem2_cols=5;
		if($hotitem2_rows==0 || $hotitem2_rows==9) $hotitem2_rows=1;
		$hotitem2_gan=(($match_array[1]+0)>99)?"99":($match_array[1]+0);
		if($hotitem2_gan==0) $hotitem2_gan=5;

		$hotitem2_colnum=$hotitem2_cols*2-1;
		$hotitem2_product_num=$hotitem2_cols*$hotitem2_rows;
		if($hotitem2_cols==6)		$hotitem2_imgsize=$_data->primg_minisize-5;
		else if($hotitem2_cols==7)	$hotitem2_imgsize=$_data->primg_minisize-10;
		else if($hotitem2_cols==8)	$hotitem2_imgsize=$_data->primg_minisize-20;
		else						$hotitem2_imgsize=$_data->primg_minisize;

		$hotitem_product_num=$hotitem2_product_num;
	} else if($hotitem_type=="3") {	//����Ʈ��
		$hotitem_type="";
		$match=array();
		$default_hotitem3=array("5","N","Y","Y","0","N","N","N");
		if (preg_match("/\[HOTITEM3([0-9NY]{2,9})\]/",$body,$match)) {
			$ii=0;
			for ($i=0;$i<strlen($match[1]);$i++) {
				if($i==0) {
					$default_hotitem3[$ii]=$match[1][$i++].$match[1][$i];
				} else {
					$default_hotitem3[$ii]=$match[1][$i];
				}
				$ii++;
			}
			$hotitem_type="3";
		}

		$hotitem3_product_num=(int)$default_hotitem3[0];
		$hotitem3_production=$default_hotitem3[1];	// ������ ǥ�ÿ���
		$hotitem3_price=$default_hotitem3[2];		// �Һ��ڰ� ǥ�ÿ���
		$hotitem3_reserve=$default_hotitem3[3];		// ������ ǥ�ÿ���
		$hotitem3_tag=(int)$default_hotitem3[4];	// �±� ǥ�ð���(0-9) 0�� ��� ǥ�þ���
		$hotitem3_madein=$default_hotitem3[5];	// ������ ǥ�ÿ���
		$hotitem3_model=$default_hotitem3[6];	// �𵨸� ǥ�ÿ���
		$hotitem3_brand=$default_hotitem3[7];	// �귣�� ǥ�ÿ���
		if($hotitem3_product_num<0 || $hotitem3_product_num>20) $hotitem3_product_num=5;

		$hotitem_product_num=$hotitem3_product_num;
	}
}

//�űԻ�ǰ ����
$newitem_type="";
if($num=strpos($body,"[NEWITEM")) {
	$newitem_type=substr($body,$num+8,1);
	if($newitem_type=="1") {	//�̹���A��
		$newitem_type="";
		$match=array();
		$default_newitem1=array("5","1","N","N","N","N","0","N","N","N","N");
		if (preg_match("/\[NEWITEM1([0-9LNY_]{2,14})\]/",$body,$match)) {
			$match_array=explode("_",$match[1]);
			for ($i=0;$i<strlen($match_array[0]);$i++) {
				$default_newitem1[$i]=$match_array[0][$i];
			}
			$newitem_type="1";
		}

		$newitem1_cols=(int)$default_newitem1[0];
		$newitem1_rows=(int)$default_newitem1[1];
		$newitem1_rowline=$default_newitem1[2];		// ��ǰ���ζ��ο���
		$newitem1_colline=$default_newitem1[3];		// ��ǰ���ζ��ο���
		$newitem1_price=$default_newitem1[4];		// �Һ��ڰ� ǥ�ÿ���
		$newitem1_reserve=$default_newitem1[5];		// ������ ǥ�ÿ���
		$newitem1_tag=(int)$default_newitem1[6];	// �±� ǥ�ð���(0-9) 0�� ��� ǥ�þ���
		$newitem1_production=$default_newitem1[7];	// ������ ǥ�ÿ���
		$newitem1_madein=$default_newitem1[8];	// ������ ǥ�ÿ���
		$newitem1_model=$default_newitem1[9];	// �𵨸� ǥ�ÿ���
		$newitem1_brand=$default_newitem1[10];	// �귣�� ǥ�ÿ���

		if($newitem1_cols==0 || $newitem1_cols==9) $newitem1_cols=5;
		if($newitem1_rows==0 || $newitem1_rows==9) $newitem1_rows=1;
		$newitem1_gan=(($match_array[1]+0)>99)?"99":($match_array[1]+0);
		if($newitem1_gan==0) $newitem1_gan=5;

		$newitem1_colnum=$newitem1_cols*2-1;
		$newitem1_product_num=$newitem1_cols*$newitem1_rows;
		if($newitem1_cols==6)		$newitem1_imgsize=$_data->primg_minisize-5;
		else if($newitem1_cols==7)	$newitem1_imgsize=$_data->primg_minisize-10;
		else if($newitem1_cols==8)	$newitem1_imgsize=$_data->primg_minisize-20;
		else						$newitem1_imgsize=$_data->primg_minisize;

		$newitem_product_num=$newitem1_product_num;
	} else if($newitem_type=="2") {	//�̹���B��
		$newitem_type="";
		$match=array();
		$default_newitem2=array("2","1","N","N","N","N","0","N","N","N","N");
		if (preg_match("/\[NEWITEM2([0-9LNY_]{2,14})\]/",$body,$match)) {
			$match_array=explode("_",$match[1]);
			for ($i=0;$i<strlen($match_array[0]);$i++) {
				$default_newitem2[$i]=$match_array[0][$i];
			}
			$newitem_type="2";
		}

		$newitem2_cols=(int)$default_newitem2[0];
		$newitem2_rows=(int)$default_newitem2[1];
		$newitem2_rowline=$default_newitem2[2];		// ��ǰ���ζ��ο���
		$newitem2_colline=$default_newitem2[3];		// ��ǰ���ζ��ο���
		$newitem2_price=$default_newitem2[4];		// �Һ��ڰ� ǥ�ÿ���
		$newitem2_reserve=$default_newitem2[5];		// ������ ǥ�ÿ���
		$newitem2_tag=(int)$default_newitem2[6];	// �±� ǥ�ð���(0-9) 0�� ��� ǥ�þ���
		$newitem2_production=$default_newitem2[7];	// ������ ǥ�ÿ���
		$newitem2_madein=$default_newitem2[8];	// ������ ǥ�ÿ���
		$newitem2_model=$default_newitem2[9];	// �𵨸� ǥ�ÿ���
		$newitem2_brand=$default_newitem2[10];	// �귣�� ǥ�ÿ���
		if($newitem2_cols==0 || $newitem2_cols==9) $newitem2_cols=5;
		if($newitem2_rows==0 || $newitem2_rows==9) $newitem2_rows=1;
		$newitem2_gan=(($match_array[1]+0)>99)?"99":($match_array[1]+0);
		if($newitem2_gan==0) $newitem2_gan=5;

		$newitem2_colnum=$newitem2_cols*2-1;
		$newitem2_product_num=$newitem2_cols*$newitem2_rows;
		if($newitem2_cols==6)		$newitem2_imgsize=$_data->primg_minisize-5;
		else if($newitem2_cols==7)	$newitem2_imgsize=$_data->primg_minisize-10;
		else if($newitem2_cols==8)	$newitem2_imgsize=$_data->primg_minisize-20;
		else						$newitem2_imgsize=$_data->primg_minisize;

		$newitem_product_num=$newitem2_product_num;
	} else if($newitem_type=="3") {	//����Ʈ��
		$newitem_type="";
		$match=array();
		$default_newitem3=array("5","N","Y","Y","0","N","N","N");
		if (preg_match("/\[NEWITEM3([0-9NY]{2,9})\]/",$body,$match)) {
			$ii=0;
			for ($i=0;$i<strlen($match[1]);$i++) {
				if($i==0) {
					$default_newitem3[$ii]=$match[1][$i++].$match[1][$i];
				} else {
					$default_newitem3[$ii]=$match[1][$i];
				}
				$ii++;
			}
			$newitem_type="3";
		}

		$newitem3_product_num=(int)$default_newitem3[0];
		$newitem3_production=$default_newitem3[1];	// ������ ǥ�ÿ���
		$newitem3_price=$default_newitem3[2];		// �Һ��ڰ� ǥ�ÿ���
		$newitem3_reserve=$default_newitem3[3];		// ������ ǥ�ÿ���
		$newitem3_tag=(int)$default_newitem3[4];	// �±� ǥ�ð���(0-9) 0�� ��� ǥ�þ���
		$newitem3_madein=$default_newitem3[5];	// ������ ǥ�ÿ���
		$newitem3_model=$default_newitem3[6];	// �𵨸� ǥ�ÿ���
		$newitem3_brand=$default_newitem3[7];	// �귣�� ǥ�ÿ���
		if($newitem3_product_num<0 || $newitem3_product_num>20) $newitem3_product_num=5;

		$newitem_product_num=$newitem3_product_num;
	}
}

//�α��ǰ ����
$bestitem_type="";
if($num=strpos($body,"[BESTITEM")) {
	$bestitem_type=substr($body,$num+9,1);
	if($bestitem_type=="1") {	//�̹���A��
		$bestitem_type="";
		$match=array();
		$default_bestitem1=array("5","1","N","N","N","N","0","N","N","N","N");
		if (preg_match("/\[BESTITEM1([0-9LNY_]{2,14})\]/",$body,$match)) {
			$match_array=explode("_",$match[1]);
			for ($i=0;$i<strlen($match_array[0]);$i++) {
				$default_bestitem1[$i]=$match_array[0][$i];
			}
			$bestitem_type="1";
		}

		$bestitem1_cols=(int)$default_bestitem1[0];
		$bestitem1_rows=(int)$default_bestitem1[1];
		$bestitem1_rowline=$default_bestitem1[2];		// ��ǰ���ζ��ο���
		$bestitem1_colline=$default_bestitem1[3];		// ��ǰ���ζ��ο���
		$bestitem1_price=$default_bestitem1[4];			// �Һ��ڰ� ǥ�ÿ���
		$bestitem1_reserve=$default_bestitem1[5];		// ������ ǥ�ÿ���
		$bestitem1_tag=(int)$default_bestitem1[6];		// �±� ǥ�ð���(0-9) 0�� ��� ǥ�þ���
		$bestitem1_production=$default_bestitem1[7];	// ������ ǥ�ÿ���
		$bestitem1_madein=$default_bestitem1[8];	// ������ ǥ�ÿ���
		$bestitem1_model=$default_bestitem1[9];	// �𵨸� ǥ�ÿ���
		$bestitem1_brand=$default_bestitem1[10];	// �귣�� ǥ�ÿ���
		if($bestitem1_cols==0 || $bestitem1_cols==9) $bestitem1_cols=5;
		if($bestitem1_rows==0 || $bestitem1_rows==9) $bestitem1_rows=1;
		$bestitem1_gan=(($match_array[1]+0)>99)?"99":($match_array[1]+0);
		if($bestitem1_gan==0) $bestitem1_gan=5;

		$bestitem1_colnum=$bestitem1_cols*2-1;
		$bestitem1_product_num=$bestitem1_cols*$bestitem1_rows;
		if($bestitem1_cols==6)		$bestitem1_imgsize=$_data->primg_minisize-5;
		else if($bestitem1_cols==7)	$bestitem1_imgsize=$_data->primg_minisize-10;
		else if($bestitem1_cols==8)	$bestitem1_imgsize=$_data->primg_minisize-20;
		else						$bestitem1_imgsize=$_data->primg_minisize;

		$bestitem_product_num=$bestitem1_product_num;
	} else if($bestitem_type=="2") {	//�̹���B��
		$bestitem_type="";
		$match=array();
		$default_bestitem2=array("2","1","N","N","N","N","0","N","N","N","N");
		if (preg_match("/\[BESTITEM2([0-9LNY_]{2,14})\]/",$body,$match)) {
			$match_array=explode("_",$match[1]);
			for ($i=0;$i<strlen($match_array[0]);$i++) {
				$default_bestitem2[$i]=$match_array[0][$i];
			}
			$bestitem_type="2";
		}

		$bestitem2_cols=(int)$default_bestitem2[0];
		$bestitem2_rows=(int)$default_bestitem2[1];
		$bestitem2_rowline=$default_bestitem2[2];		// ��ǰ���ζ��ο���
		$bestitem2_colline=$default_bestitem2[3];		// ��ǰ���ζ��ο���
		$bestitem2_price=$default_bestitem2[4];			// �Һ��ڰ� ǥ�ÿ���
		$bestitem2_reserve=$default_bestitem2[5];		// ������ ǥ�ÿ���
		$bestitem2_tag=(int)$default_bestitem2[6];		// �±� ǥ�ð���(0-9) 0�� ��� ǥ�þ���
		$bestitem2_production=$default_bestitem2[7];	// ������ ǥ�ÿ���
		$bestitem2_madein=$default_bestitem2[8];	// ������ ǥ�ÿ���
		$bestitem2_model=$default_bestitem2[9];	// �𵨸� ǥ�ÿ���
		$bestitem2_brand=$default_bestitem2[10];	// �귣�� ǥ�ÿ���
		if($bestitem2_cols==0 || $bestitem2_cols==9) $bestitem2_cols=5;
		if($bestitem2_rows==0 || $bestitem2_rows==9) $bestitem2_rows=1;
		$bestitem2_gan=(($match_array[1]+0)>99)?"99":($match_array[1]+0);
		if($bestitem2_gan==0) $bestitem2_gan=5;

		$bestitem2_colnum=$bestitem2_cols*2-1;
		$bestitem2_product_num=$bestitem2_cols*$bestitem2_rows;
		if($bestitem2_cols==6)		$bestitem2_imgsize=$_data->primg_minisize-5;
		else if($bestitem2_cols==7)	$bestitem2_imgsize=$_data->primg_minisize-10;
		else if($bestitem2_cols==8)	$bestitem2_imgsize=$_data->primg_minisize-20;
		else						$bestitem2_imgsize=$_data->primg_minisize;

		$bestitem_product_num=$bestitem2_product_num;
	} else if($bestitem_type=="3") {	//����Ʈ��
		$bestitem_type="";
		$match=array();
		$default_bestitem3=array("5","N","Y","Y","0","N","N","N");
		if (preg_match("/\[BESTITEM3([0-9NY]{2,9})\]/",$body,$match)) {
			$ii=0;
			for ($i=0;$i<strlen($match[1]);$i++) {
				if($i==0) {
					$default_bestitem3[$ii]=$match[1][$i++].$match[1][$i];
				} else {
					$default_bestitem3[$ii]=$match[1][$i];
				}
				$ii++;
			}
			$bestitem_type="3";
		}

		$bestitem3_product_num=(int)$default_bestitem3[0];
		$bestitem3_production=$default_bestitem3[1];	// ������ ǥ�ÿ���
		$bestitem3_price=$default_bestitem3[2];			// �Һ��ڰ� ǥ�ÿ���
		$bestitem3_reserve=$default_bestitem3[3];		// ������ ǥ�ÿ���
		$bestitem3_tag=(int)$default_bestitem3[4];		// �±� ǥ�ð���(0-9) 0�� ��� ǥ�þ���
		$bestitem3_madein=$default_bestitem3[5];	// ������ ǥ�ÿ���
		$bestitem3_model=$default_bestitem3[6];	// �𵨸� ǥ�ÿ���
		$bestitem3_brand=$default_bestitem3[7];	// �귣�� ǥ�ÿ���
		if($bestitem3_product_num<0 || $bestitem3_product_num>20) $bestitem3_product_num=5;

		$bestitem_product_num=$bestitem3_product_num;
	}
}

//��ǰ����Ʈ (����Ʈ,�̹���,������)
$prlist_type="";
if($num=strpos($body,"[PRLIST")) {
	$prlist_type=substr($body,$num+7,1);
	if($prlist_type=="1") {	//�̹���A��
		$prlist_type="";
		$match=array();
		$default_prlist1=array("5","2","N","Y","N","N","0","N","N","N","N");
		if (preg_match("/\[PRLIST1([0-9LNY_]{2,14})\]/",$body,$match)) {
			$match_array=explode("_",$match[1]);
			for ($i=0;$i<strlen($match_array[0]);$i++) {
				$default_prlist1[$i]=$match_array[0][$i];
			}
			$prlist_type="1";
		}

		$prlist1_cols=(int)$default_prlist1[0];
		$prlist1_rows=(int)$default_prlist1[1];
		$prlist1_rowline=$default_prlist1[2];		// ��ǰ���ζ��ο���
		$prlist1_colline=$default_prlist1[3];		// ��ǰ���ζ��ο���
		$prlist1_price=$default_prlist1[4];			// �Һ��ڰ� ǥ�ÿ���
		$prlist1_reserve=$default_prlist1[5];		// ������ ǥ�ÿ���
		$prlist1_tag=(int)$default_prlist1[6];		// �±� ǥ�ð���(0-9) 0�� ��� ǥ�þ���
		$prlist1_production=$default_prlist1[7];	// ������ ǥ�ÿ���
		$prlist1_madein=$default_prlist1[8];	// ������ ǥ�ÿ���
		$prlist1_model=$default_prlist1[9];	// �𵨸� ǥ�ÿ���
		$prlist1_brand=$default_prlist1[10];	// �귣�� ǥ�ÿ���
		if($prlist1_cols==0 || $prlist1_cols==9) $prlist1_cols=5;
		if($prlist1_rows==0 || $prlist1_rows==9) $prlist1_rows=2;
		$prlist1_gan=(($match_array[1]+0)>99)?"99":($match_array[1]+0);
		if($prlist1_gan==0) $prlist1_gan=5;

		$prlist1_colnum=$prlist1_cols*2-1;
		$prlist1_product_num=$prlist1_cols*$prlist1_rows;
		if($prlist1_cols==6)		$prlist1_imgsize=$_data->primg_minisize-5;
		else if($prlist1_cols==7)	$prlist1_imgsize=$_data->primg_minisize-10;
		else if($prlist1_cols==8)	$prlist1_imgsize=$_data->primg_minisize-20;
		else						$prlist1_imgsize=$_data->primg_minisize;

		if($_REQUEST["listnum"]){
			$listnum=(int)$_REQUEST["listnum"];
			if($listnum<=0) $listnum=$_data->prlist_num;

			//����Ʈ ����
			$setup[list_num] = $listnum;
		}else{
			//����Ʈ ����
			$setup[list_num]=$prlist1_product_num;
		}

	} else if($prlist_type=="2") {	//�̹���B��
		$prlist_type="";
		$match=array();
		$default_prlist2=array("2","5","N","Y","N","N","0","N","N","N","N");
		if (preg_match("/\[PRLIST2([0-9LNY_]{2,14})\]/",$body,$match)) {
			$match_array=explode("_",$match[1]);
			for ($i=0;$i<strlen($match_array[0]);$i++) {
				$default_prlist2[$i]=$match_array[0][$i];
			}
			$prlist_type="2";
		}

		$prlist2_cols=(int)$default_prlist2[0];
		$prlist2_rows=(int)$default_prlist2[1];
		$prlist2_rowline=$default_prlist2[2];		// ��ǰ���ζ��ο���
		$prlist2_colline=$default_prlist2[3];		// ��ǰ���ζ��ο���
		$prlist2_price=$default_prlist2[4];			// �Һ��ڰ� ǥ�ÿ���
		$prlist2_reserve=$default_prlist2[5];		// ������ ǥ�ÿ���
		$prlist2_tag=(int)$default_prlist2[6];		// �±� ǥ�ð���(0-9) 0�� ��� ǥ�þ���
		$prlist2_production=$default_prlist2[7];	// ������ ǥ�ÿ���
		$prlist2_madein=$default_prlist2[8];	// ������ ǥ�ÿ���
		$prlist2_model=$default_prlist2[9];	// �𵨸� ǥ�ÿ���
		$prlist2_brand=$default_prlist2[10];	// �귣�� ǥ�ÿ���
		if($prlist2_cols==0 || $prlist2_cols==9) $prlist2_cols=5;
		if($prlist2_rows==0 || $prlist2_rows==9) $prlist2_rows=2;
		$prlist2_gan=(($match_array[1]+0)>99)?"99":($match_array[1]+0);
		if($prlist2_gan==0) $prlist2_gan=5;

		$prlist2_colnum=$prlist2_cols*2-1;
		$prlist2_product_num=$prlist2_cols*$prlist2_rows;
		if($prlist2_cols==6)		$prlist2_imgsize=$_data->primg_minisize-5;
		else if($prlist2_cols==7)	$prlist2_imgsize=$_data->primg_minisize-10;
		else if($prlist2_cols==8)	$prlist2_imgsize=$_data->primg_minisize-20;
		else						$prlist2_imgsize=$_data->primg_minisize;

		if($_REQUEST["listnum"]){
			$listnum=(int)$_REQUEST["listnum"];
			if($listnum<=0) $listnum=$_data->prlist_num;

			//����Ʈ ����
			$setup[list_num] = $listnum;
		}else{
			//����Ʈ ����
			$setup[list_num]=$prlist2_product_num;
		}

	} else if($prlist_type=="3") {	//����Ʈ��
		$prlist_type="";
		$match=array();
		$default_prlist3=array("15","Y","N","Y","Y","0","N","N","N");
		if (preg_match("/\[PRLIST3([0-9NY]{2,10})\]/",$body,$match)) {
			$ii=0;
			for ($i=0;$i<strlen($match[1]);$i++) {
				if($i==0) {
					$default_prlist3[$ii]=$match[1][$i++].$match[1][$i];
				} else {
					$default_prlist3[$ii]=$match[1][$i];
				}
				$ii++;
			}
			$prlist_type="3";
		}

		$prlist3_product_num=(int)$default_prlist3[0];
		$prlist3_image_yn=$default_prlist3[1];
		$prlist3_production=$default_prlist3[2];	// ������ ǥ�ÿ���
		$prlist3_price=$default_prlist3[3];			// �Һ��ڰ� ǥ�ÿ���
		$prlist3_reserve=$default_prlist3[4];		// ������ ǥ�ÿ���
		$prlist3_tag=(int)$default_prlist3[5];		// �±� ǥ�ð���(0-9) 0�� ��� ǥ�þ���
		$prlist3_madein=$default_prlist3[6];	// ������ ǥ�ÿ���
		$prlist3_model=$default_prlist3[7];	// �𵨸� ǥ�ÿ���
		$prlist3_brand=$default_prlist3[8];	// �귣�� ǥ�ÿ���
		if($prlist3_product_num<10 || $prlist3_product_num>50) $prlist3_product_num=15;

		$setup[list_num]=$prlist3_product_num;
	} else if($prlist_type=="4") {	//����������
		$prlist_type="";
		if (preg_match("/\[PRLIST4([1-8]{2}(\_){0,1}[0-9]{0,2})\]/",$body,$match)) {
			$prlist_type="4";
			$match_array=explode("_",$match[1]);
			$prlist4_cols=(int)$match_array[0][0];
			$prlist4_rows=(int)$match_array[0][1];

			if($prlist4_cols==0 || $prlist4_cols==9) $prlist4_cols=3;
			if($prlist4_rows==0 || $prlist4_rows==9) $prlist4_rows=3;
			$prlist4_colnum=$prlist4_cols*2-1;
			$prlist4_product_num=$prlist4_cols*$prlist4_rows;
			$prlist4_gan=(($match_array[1]+0)>99)?"99":($match_array[1]+0);
			if($prlist4_gan==0) $prlist4_gan=5;
		}
		$setup[list_num]=$prlist4_product_num;
	}
}



include("productlist_text.php");

$pattern=array(
	"(\[CODENAME\])",
	"(\[CODENAVI([0-9a-fA-F_]{0,13})\])",
	"(\[CLIPCOPY\])",
	"(\[CODEEVENT\])",
	"(\[CODEGROUP\])",
	"(\[HOTITEM1([0-9LNY_]{2,14})\])",
	"(\[HOTITEM2([0-9LNY_]{2,14})\])",
	"(\[HOTITEM3([0-9NY]{2,9})\])",
	"(\[NEWITEM1([0-9LNY_]{2,14})\])",
	"(\[NEWITEM2([0-9LNY_]{2,14})\])",
	"(\[NEWITEM3([0-9NY]{2,9})\])",
	"(\[BESTITEM1([0-9LNY_]{2,14})\])",
	"(\[BESTITEM2([0-9LNY_]{2,14})\])",
	"(\[BESTITEM3([0-9NY]{2,9})\])",
	"(\[SKWLIST\])",
	"(\[PRLIST1([0-9LNY_]{2,14})\])",
	"(\[PRLIST2([0-9LNY_]{2,14})\])",
	"(\[PRLIST3([0-9NY]{2,10})\])",
	"(\[PRLIST4([1-8]{2}(\_){0,1}[0-9]{0,2})\])",
	"(\[TOTAL\])",
	"(\[SORTPRODUCTUP\])",
	"(\[SORTPRODUCTDN\])",
	"(\[SORTNAMEUP\])",
	"(\[SORTNAMEDN\])",
	"(\[SORTPRICEUP\])",
	"(\[SORTPRICEDN\])",
	"(\[SORTRESERVEUP\])",
	"(\[SORTRESERVEDN\])",
	"(\[SORTNEW\])",
	"(\[SORTBEST\])",
	"(\[SORTREVIEW\])",
	"(\[ONNEW\])",
	"(\[ONBEST\])",
	"(\[ONPRICEUP\])",
	"(\[ONPRICEDN\])",
	"(\[ONRESERVEDN\])",
	"(\[ONREVIEW\])",
	"(\[LISTSELECT\])",
	"(\[PAGE\])",
	"(\[BESTITEM_TAB\])"
);

$replace=array($codename,$codenavi,$clipcopy,$codeevent,$codegroup,$hotitem1,$hotitem2,$hotitem3,$newitem1,$newitem2,$newitem3,$bestitem1,$bestitem2,$bestitem3,$skwlist,$prlist1,$prlist2,$prlist3,$prlist4,$t_count,"javascript:ChangeSort('production')","javascript:ChangeSort('production_desc')","javascript:ChangeSort('name')","javascript:ChangeSort('name_desc')","javascript:ChangeSort('price')","javascript:ChangeSort('price_desc')","javascript:ChangeSort('reserve')","javascript:ChangeSort('reserve_desc')","javascript:ChangeSort('new_desc')","javascript:ChangeSort('best_desc')","javascript:ChangeSort('review_desc')",$_new,$_best_desc,$_price,$_price_desc,$_reserve_desc,$_review_desc,$listselect,$list_page,"<li><a href='#'><img src='/data/design/img/sub/tab_prsection2.gif' asrc='/data/design/img/sub/tab_prsection2_on.gif' border='0' alt='' /></a></li>");


/*
// 3�� ī�װ�
if(!_empty($_cdata->codeB) && $_cdata->codeB != '000'){
	$tmp = getCategoryItems(substr($code,0,6),true);
	
	//$category3rd = '<div style="height:6px;background:url(\'/data/design/img/sub/top_boxline2.gif\') no-repeat;font-size:0px;"></div><table border="0" cellpadding="0" cellspacing="0" width="98%">';
	$category3rd = '<div class="sub_3rd">';
	$category3rd.='<table border="0" cellpadding="0" cellspacing="0">';
	$loop = ceil(count($tmp['items'])/6);

	for($jj=0;$jj < $loop;$jj++){
		$category3rd.='<tr>';
		for($jjj=0;$jjj < 6;$jjj++){
			$cstr = ($jjj == 5)?' class="lastTd"':'';
			if(isset($tmp['items'][$jj*6+$jjj])){
				$itm = 	$tmp['items'][$jj*6+$jjj];
				$cstr .= ($itm['codeC'] == substr($code,6,3))?' style="color:#444;font-weight:bold" ':'';
				$category3rd.='<td><a href="'.$Dir.'front/productlist.php?code='.$itm['linkcode'].'" '.$cstr.'>'.$itm['code_name'].'</a></td>';
			}else{
				$category3rd.='<td '.$cstr.'>&nbsp;</td>';
			}
		}
		$category3rd.='</tr>';
	}
	//$category3rd.='</table><div style="height:6px;background:url(\'/data/design/img/sub/bot_boxline2.gif\') no-repeat;font-size:0px;"></div>';
	$category3rd.='</table>';
	$category3rd.='</div>';
}

// 4�� ī�װ�
if(!_empty($_cdata->codeC) && $_cdata->codeC != '000'){
	$tmp = getCategoryItems(substr($code,0,9),true);

	//$category4th='<div style="height:6px;background:url(\'/data/design/img/sub/top_boxline2.gif\') no-repeat;font-size:0px;"></div><table border="0" cellpadding="0" cellspacing="0" width="98%">';
	$category4th='<div class="sub_4th">';
	$category4th.='<ul>';
	$loop = ceil(count($tmp['items'])/6);

	for($jj=0;$jj < $loop;$jj++){
		//$category4th.='<tr>';
		for($jjj=0;$jjj < 6;$jjj++){
			//$cstr=($jjj == 5)?' class="lastTd"':'';
			$cstr="";
			if(isset($tmp['items'][$jj*6+$jjj])){
				$itm=	$tmp['items'][$jj*6+$jjj];
				$cstr.=($itm['codeD']==substr($code,9,3))?' style="color:#444;font-weight:bold;"':'';
				$category4th.='<li><a href="'.$Dir.'front/productlist.php?code='.$itm['linkcode'].'" '.$cstr.'>'.$itm['code_name'].'</a></li>';
			}else{
				//$category4th.='<td '.$cstr.'>&nbsp;</td>';
			}
		}
		//$category4th.='</tr>';
	}
	//$category4th.='</table><div style="height:6px;background:url(\'/data/design/img/sub/bot_boxline2.gif\') no-repeat;font-size:0px;"></div>';
	$category4th.='</ul>';
	$category4th.='</div>';
}
*/

array_push($pattern,'(\[CATEGORY_3rd\])','(\[CATEGORY_4th\])');
array_push($replace,$category3rd,$category4th);


array_push($pattern,'(\[TSEARCH_FORM\])');
array_push($replace,$TSEARCH_FORM);
			
		

$body=preg_replace($pattern,$replace,$body);





// 4�� ī�װ�
if(!_empty($_cdata->codeC) && $_cdata->codeC != '000'){
	$tmp = getCategoryItems(substr($code,0,9),true);
	
	if(count($tmp['items'])>0 && _array($tmp) && (false !== $spos = strpos($subcatestr,'[SUBCATEGORY_LOOP]')) && (false !== $sepos = strpos($subcatestr,'[SUBCATEGORY_/LOOP]'))){
		$cateloopstr = substr($subcatestr,$spos+strlen('[SUBCATEGORY_LOOP]'),$sepos-$spos-strlen('[SUBCATEGORY_LOOP]'));
		$catelinkstr = array();
		$catelinkstr[0] = substr($subcatestr,0,$spos);
		$catelinkstr[1] = '';
		$catelinkstr[2] = substr($subcatestr,$sepos+strlen('[SUBCATEGORY_/LOOP]'));
				
		
		if(substr($code,9,3) == "000") $cactivate = ' class="select" ';
		if(sizeof($tmp['items'])>0){
			$catelinkstr[1] .= str_replace(array('category.url','cateogry.name','category.activate'),array($Dir.'front/productlist.php?code='.substr($code,0,9),"��ü",$cactivate),$cateloopstr);
		}

		foreach($tmp['items'] as $cval){
			$cactivate = '';
			if($cval['codeD'] == substr($code,9,3)) $cactivate = ' class="select" ';
			
			$catelinkstr[1] .= str_replace(array('category.url','cateogry.name','category.activate'),array($Dir.'front/productlist.php?code='.$cval['linkcode'],$cval['code_name'],$cactivate),$cateloopstr);
		}

		$catelinkstr[1] .= str_replace(array('category.url','cateogry.name','category.activate'),array($Dir.'front/productlist.php?code='.substr($code,0,6),"<img src='/data/design/img/sub/icon_category_back.gif' align='absmiddle' />",""),$cateloopstr);

		$body = str_replace($subcatemat,implode("\r\n",$catelinkstr),$body);
	}else{
		$tmp = getCategoryItems(substr($code,0,6),true);

		if(_array($tmp) && (false !== $spos = strpos($subcatestr,'[SUBCATEGORY_LOOP]')) && (false !== $sepos = strpos($subcatestr,'[SUBCATEGORY_/LOOP]'))){
			$cateloopstr = substr($subcatestr,$spos+strlen('[SUBCATEGORY_LOOP]'),$sepos-$spos-strlen('[SUBCATEGORY_LOOP]'));
			$catelinkstr = array();
			$catelinkstr[0] = substr($subcatestr,0,$spos);
			$catelinkstr[1] = '';
			$catelinkstr[2] = substr($subcatestr,$sepos+strlen('[SUBCATEGORY_/LOOP]'));
					
			if(substr($code,6,3) == "000") $cactivate = ' class="select" ';
			if(sizeof($tmp['items'])>0){
				$catelinkstr[1] .= str_replace(array('category.url','cateogry.name','category.activate'),array($Dir.'front/productlist.php?code='.substr($code,0,6),"��ü",$cactivate),$cateloopstr);
			}

			foreach($tmp['items'] as $cval){
				$cactivate = '';
				if($cval['codeC'] == substr($code,6,3)) $cactivate = ' class="select" ';
				
				$catelinkstr[1] .= str_replace(array('category.url','cateogry.name','category.activate'),array($Dir.'front/productlist.php?code='.$cval['linkcode'],$cval['code_name'],$cactivate),$cateloopstr);
			}
			$catelinkstr[1] .= str_replace(array('category.url','cateogry.name','category.activate'),array($Dir.'front/productlist.php?code='.substr($code,0,3),"<img src='/data/design/img/sub/icon_category_back.gif' align='absmiddle' />",""),$cateloopstr);

			$body = str_replace($subcatemat,implode("\r\n",$catelinkstr),$body);
		}
	}

// 3�� ī�װ�
}else if(!_empty($_cdata->codeB) && $_cdata->codeB != '000'){
	$tmp = getCategoryItems(substr($code,0,6),true);

	if(_array($tmp) && (false !== $spos = strpos($subcatestr,'[SUBCATEGORY_LOOP]')) && (false !== $sepos = strpos($subcatestr,'[SUBCATEGORY_/LOOP]'))){
		$cateloopstr = substr($subcatestr,$spos+strlen('[SUBCATEGORY_LOOP]'),$sepos-$spos-strlen('[SUBCATEGORY_LOOP]'));
		$catelinkstr = array();
		$catelinkstr[0] = substr($subcatestr,0,$spos);
		$catelinkstr[1] = '';
		$catelinkstr[2] = substr($subcatestr,$sepos+strlen('[SUBCATEGORY_/LOOP]'));
				
		if(substr($code,6,3) == "000") $cactivate = ' class="select" ';
		if(sizeof($tmp['items'])>0){
			$catelinkstr[1] .= str_replace(array('category.url','cateogry.name','category.activate'),array($Dir.'front/productlist.php?code='.substr($code,0,6),"��ü",$cactivate),$cateloopstr);
		}
		
		foreach($tmp['items'] as $cval){
			$cactivate = '';
			if($cval['codeC'] == substr($code,6,3)) $cactivate = ' class="select" ';
			
			$catelinkstr[1] .= str_replace(array('category.url','cateogry.name','category.activate'),array($Dir.'front/productlist.php?code='.$cval['linkcode'],$cval['code_name'],$cactivate),$cateloopstr);
		}

		$catelinkstr[1] .= str_replace(array('category.url','cateogry.name','category.activate'),array($Dir.'front/productlist.php?code='.substr($code,0,3),"<img src='/data/design/img/sub/icon_category_back.gif' align='absmiddle' />",""),$cateloopstr);

		$body = str_replace($subcatemat,implode("\r\n",$catelinkstr),$body);
	}

}else{
	// 2�� ī�װ�
	$tmp = getCategoryItems(substr($code,0,3),true);

	//$subcatemat = '{{__catestr_'.time().'}}';
	if(_array($tmp) && (false !== $spos = strpos($subcatestr,'[SUBCATEGORY_LOOP]')) && (false !== $sepos = strpos($subcatestr,'[SUBCATEGORY_/LOOP]'))){
		$cateloopstr = substr($subcatestr,$spos+strlen('[SUBCATEGORY_LOOP]'),$sepos-$spos-strlen('[SUBCATEGORY_LOOP]'));
		$catelinkstr = array();
		$catelinkstr[0] = substr($subcatestr,0,$spos);
		$catelinkstr[1] = '';
		$catelinkstr[2] = substr($subcatestr,$sepos+strlen('[SUBCATEGORY_/LOOP]'));
			
		if(substr($code,3,3) == "000") $cactivate = ' class="select" ';
		$catelinkstr[1] .= str_replace(array('category.url','cateogry.name','category.activate'),array($Dir.'front/productlist.php?code='.substr($code,0,3),"��ü",$cactivate),$cateloopstr);

		foreach($tmp['items'] as $cval){
			$cactivate = '';
			if($cval['codeB'] == substr($code,3,3)) $cactivate = ' class="select" ';
			
			$catelinkstr[1] .= str_replace(array('category.url','cateogry.name','category.activate'),array($Dir.'front/productlist.php?code='.$cval['linkcode'],$cval['code_name'],$cactivate),$cateloopstr);
		}

		$body = str_replace($subcatemat,implode("\r\n",$catelinkstr),$body);
	}
}


echo $body;

?>