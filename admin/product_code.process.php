<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "pr-1";
$MenuCode = "nomenu";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$mode=$_POST["mode"];
$code=$_POST["code"];
$codes=$_POST["codes"];

if($mode=="movesave" && strlen($codes)>0) {	//�̵��� �з� ���� ����
	$tok1=explode("!",$codes);
	for($i=0;$i<count($tok1);$i++) {
		if(strlen(trim($tok1[$i]))>=12) {
			$code = substr($tok1[$i],0,12);
			$sequence=9999-$i;
			$sql = "UPDATE tblproductcode SET sequence='".$sequence."' ";
			$sql.= "WHERE codeA='".substr($code,0,3)."' ";
			$sql.= "AND codeB='".substr($code,3,3)."' AND codeC='".substr($code,6,3)."' ";
			$sql.= "AND codeD='".substr($code,9,3)."' ";
			mysql_query($sql,get_db_conn());

			if(strcmp("@",substr($tok1[$i],13))) {
				$tok2=explode("@",substr($tok1[$i],13));
				for($ii=0;$ii<count($tok2);$ii++) {
					if(strlen(trim($tok2[$ii]))>=12) {
						$code=substr($tok2[$ii],0,12);
						$sequence=9999-$ii;
						$sql = "UPDATE tblproductcode SET sequence='".$sequence."' ";
						$sql.= "WHERE codeA='".substr($code,0,3)."' ";
						$sql.= "AND codeB='".substr($code,3,3)."' AND codeC='".substr($code,6,3)."' ";
						$sql.= "AND codeD='".substr($code,9,3)."' ";
						mysql_query($sql,get_db_conn());

						if(strcmp("#",substr($tok2[$ii],13))) {
							$tok3=explode("#",substr($tok2[$ii],13));
							for($iii=0;$iii<count($tok3);$iii++) {
								if(strlen(trim($tok3[$iii]))>=12) {
									$code=substr($tok3[$iii],0,12);
									$sequence=9999-$iii;
									$sql = "UPDATE tblproductcode SET sequence='".$sequence."' ";
									$sql.= "WHERE codeA='".substr($code,0,3)."' ";
									$sql.= "AND codeB='".substr($code,3,3)."' AND codeC='".substr($code,6,3)."' ";
									$sql.= "AND codeD='".substr($code,9,3)."' ";
									mysql_query($sql,get_db_conn());
									
									if(strcmp("$",substr($tok3[$iii],13))) {
										$tok4=explode("$",$tok3[$iii]);
										for($iiii=1;$iiii<count($tok4);$iiii++) {
											$code=$tok4[$iiii];
											$sequence=9999-$iiii;
											$sql = "UPDATE tblproductcode SET sequence='".$sequence."' ";
											$sql.= "WHERE codeA='".substr($code,0,3)."' ";
											$sql.= "AND codeB='".substr($code,3,3)."' AND codeC='".substr($code,6,3)."' ";
											$sql.= "AND codeD='".substr($code,9,3)."' ";
											mysql_query($sql,get_db_conn());
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}
	$onload="<script>parent.CodeMoveResult();alert('�з����� ���� ������ �Ϸ�Ǿ����ϴ�.');</script>";
} else if($mode=="delete" && strlen($code)==12) {	//�з� ����
	$codeA=substr($code,0,3);
	$codeB=substr($code,3,3);
	$codeC=substr($code,6,3);
	$codeD=substr($code,9,3);
	$sql = "SELECT * FROM tblproductcode WHERE codeA='".$codeA."' AND codeB='".$codeB."' ";
	$sql.= "AND codeC='".$codeC."' AND codeD='".$codeD."' ";
	$result = mysql_query($sql,get_db_conn());
	if ($row=mysql_fetch_object($result)) {
		$sql = "DELETE FROM tblproductcode WHERE codeA = '".$codeA."' ";
		$likecode=$codeA;
		if($codeB!="000") {
			$sql.= "AND codeB='".$codeB."' ";
			$likecode.=$codeB;
			if($codeC!="000") {
				$sql.= "AND codeC='".$codeC."' ";
				$likecode.=$codeC;
				if($codeD!="000") {
					$sql.= "AND codeD='".$codeD."' ";
					$likecode.=$codeD;
				}
			}
		}
		mysql_query($sql,get_db_conn());

		$arrvender=array();
		$arrvenderid=array();
		$arrpridx=array();
		$arrassembleuse=array();
		$arrassembleproduct=array();
		$arrproductcode=array();
		$sql = "SELECT vender,pridx,assembleuse,assembleproduct,productcode FROM tblproduct ";
		$sql.= "WHERE productcode LIKE '".$likecode."%' ";
		$p_result=mysql_query($sql,get_db_conn());
		while($p_row=mysql_fetch_object($p_result)) {
			if($p_row->vender>0 && strlen($arrvenderid[$p_row->vender])==0) {
				$arrvender[]=$p_row->vender;
				$arrvenderid[$p_row->vender]=$p_row->vender;
			}
			$arrpridx[]=$p_row->pridx;
			$arrassembleuse[]=$p_row->assembleuse;
			$arrassembleproduct[]=$p_row->assembleproduct;
			$arrproductcode[]=$p_row->productcode;
		}
		mysql_free_result($p_result);
		// ��Ż ���� ����
		$sql = "DELETE  rent_seasonPrice FROM  rent_seasonPrice s left join tblproduct p on p.pridx=s.pridx WHERE p.productcode LIKE '".$likecode."%' ";
		@mysql_query($sql,get_db_conn());
		$sql = "DELETE  rent_product_option  FROM  rent_product_option  s left join tblproduct p on p.pridx=s.pridx WHERE p.productcode LIKE '".$likecode."%' ";
		@mysql_query($sql,get_db_conn());
		
		
		$sql = "DELETE FROM tblproduct WHERE productcode LIKE '".$likecode."%' ";
		mysql_query($sql,get_db_conn());
		$sql = "DELETE FROM tblproducttheme WHERE (productcode LIKE '".$likecode."%' OR code LIKE '".$likecode."%') ";
		mysql_query($sql,get_db_conn());
		$sql = "DELETE FROM tblproductreview WHERE productcode LIKE '".$likecode."%' ";
		mysql_query($sql,get_db_conn());
		$sql = "DELETE FROM tblwishlist WHERE productcode LIKE '".$likecode."%'";
		mysql_query($sql,get_db_conn());
		$sql = "DELETE FROM tblspecialcode WHERE code LIKE '".$likecode."%'";
		mysql_query($sql,get_db_conn());
		$sql = "DELETE FROM tblcollection WHERE productcode LIKE '".$likecode."%'";
		mysql_query($sql,get_db_conn());
		$sql = "DELETE FROM tbltagproduct WHERE productcode LIKE '".$likecode."%'";
		mysql_query($sql,get_db_conn());
		$sql = "DELETE FROM tblproductgroupcode WHERE productcode LIKE '".$likecode."%' ";
		mysql_query($sql,get_db_conn());
		
		// ��Ż ���� ����
		$sql = "DELETE FROM code_rent WHERE code LIKE '".$likecode."%' ";
		mysql_query($sql,get_db_conn());		
		$sql = "DELETE FROM season_range WHERE code LIKE '".$likecode."%' ";
		mysql_query($sql,get_db_conn());
		$sql = "DELETE FROM rent_product WHERE code LIKE '".$likecode."%' ";
		mysql_query($sql,get_db_conn());
		$sql = "DELETE FROM rent_refund WHERE code LIKE '".$likecode."%' ";
		mysql_query($sql,get_db_conn());
		$sql = "DELETE FROM rent_longdiscount WHERE code LIKE '".$likecode."%' ";
		mysql_query($sql,get_db_conn());

		//��޺� ��������
		$sql = "DELETE FROM tblmemberreserve WHERE productcode LIKE '".$likecode."%' ";
		mysql_query($sql,get_db_conn());
		//��޺� ��õ����������
		$sql = "DELETE FROM tblreseller_reserve WHERE productcode LIKE '".$likecode."%' ";
		mysql_query($sql,get_db_conn());
		
		//�˻�Ű���� ����
		$sql = "DELETE FROM tblkeyword WHERE code LIKE '".$likecode."%' ";
		mysql_query($sql,get_db_conn());

		for($vz=0; $vz<count($arrpridx); $vz++) { // �ڵ�/���� �⺻������ǰ�� ���� ó��		
			if($arrassembleuse[$vz]=="Y") {
				$sql = "SELECT assemble_pridx FROM tblassembleproduct ";
				$sql.= "WHERE productcode = '".$arrproductcode[$vz]."' ";
				$result = mysql_query($sql,get_db_conn());
				if($row = @mysql_fetch_object($result)) {
					$sql = "DELETE FROM tblassembleproduct WHERE productcode = '".$arrproductcode[$vz]."' ";
					mysql_query($sql,get_db_conn());
					
					if(strlen(str_replace("","",$row->assemble_pridx))>0) {
						$sql = "UPDATE tblproduct SET ";
						$sql.= "assembleproduct = REPLACE(assembleproduct,',".$arrproductcode[$vz]."','') ";
						$sql.= "WHERE pridx IN ('".str_replace("","','",$row->assemble_pridx)."') ";
						$sql.= "AND assembleuse != 'Y' ";
						mysql_query($sql,get_db_conn());
					}
				}
				mysql_free_result($result);
			} else {
				if(strlen($arrassembleproduct[$vz])>0) {
					$sql = "SELECT productcode, assemble_pridx FROM tblassembleproduct ";
					$sql.= "WHERE productcode IN ('".str_replace(",","','",$arrassembleproduct[$vz])."') ";
					$result = mysql_query($sql,get_db_conn());
					while($row = @mysql_fetch_object($result)) {
						$sql = "SELECT SUM(sellprice) as sumprice FROM tblproduct ";
						$sql.= "WHERE pridx IN ('".str_replace("","','",$row->assemble_pridx)."') ";
						$sql.= "AND display ='Y' ";
						$sql.= "AND assembleuse!='Y' ";
						$result2 = mysql_query($sql,get_db_conn());
						if($row2 = @mysql_fetch_object($result2)) {
							$sql = "UPDATE tblproduct SET sellprice='".$row2->sumprice."' ";
							$sql.= "WHERE productcode = '".$row->productcode."' ";
							$sql.= "AND assembleuse='Y' ";
							mysql_query($sql,get_db_conn());
						}
						mysql_free_result($result2);
					}
				}

				$sql = "UPDATE tblassembleproduct SET ";
				$sql.= "assemble_pridx=REPLACE(assemble_pridx,'".$arrpridx[$vz]."',''), ";
				$sql.= "assemble_list=REPLACE(assemble_list,',".$arrpridx[$vz]."','') ";
				mysql_query($sql,get_db_conn());
			}
		}

		for($yy=0;$yy<count($arrvender);$yy++) {
			//�̴ϼ� �׸��ڵ忡 ��ϵ� ��ǰ ����
			setVenderThemeDeleteLike($likecode, $arrvender[$yy]);

			//�̴ϼ� ��ǰ�� ������Ʈ (������ ��ǰ��)
			$sql = "SELECT COUNT(*) as prdt_allcnt, COUNT(IF(display='Y',1,NULL)) as prdt_cnt FROM tblproduct ";
			$sql.= "WHERE vender='".$arrvender[$yy]."' ";
			$result=mysql_query($sql,get_db_conn());
			$row=mysql_fetch_object($result);
			$prdt_allcnt=(int)$row->prdt_allcnt;
			$prdt_cnt=(int)$row->prdt_cnt;
			mysql_free_result($result);

			setVenderCountUpdate($prdt_allcnt, $prdt_cnt, $arrvender[$yy]);

			$tmpcodeA=substr($likecode,0,3);
			$sql = "SELECT COUNT(*) as cnt FROM tblproduct ";
			$sql.= "WHERE productcode LIKE '".$tmpcodeA."%' AND vender='".$arrvender[$yy]."' ";
			$result=mysql_query($sql,get_db_conn());
			$row=mysql_fetch_object($result);
			$prcnt=$row->cnt;
			mysql_free_result($result);

			if($prcnt==0) {
				setVenderDesignDeleteNor($tmpcodeA, $arrvender[$yy]);

				$imagename=$Dir.DataDir."shopimages/vender/".$arrvender[$yy]."_CODE10_".$tmpcodeA.".gif";
				@unlink($imagename);
			}
		}

		$delshopimage = $Dir.DataDir."shopimages/product/".$likecode."*";
		proc_matchfiledel($delshopimage);

		$delshopimage = $Dir.DataDir."shopimages/etc/CODE".$likecode."*";
		proc_matchfiledel($delshopimage);

		$log_content = "## �з� ���� ## - �ڵ� : ".$code." - �ڵ� : ".ereg_replace("'","''",$row->code_name)."";
		ShopManagerLog($_ShopInfo->getId(),$connect_ip,$log_content);

		delProductMultiImg("codedel",$code,"");
	}
	mysql_free_result($result);
	$onload="<script>parent.CodeDeleteResult('".$code."');alert('�����Ͻ� �з��� �����Ͽ����ϴ�.');</script>";
}

echo $onload;
?>