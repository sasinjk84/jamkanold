<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

header("Cache-Control: no-cache, must-revalidate");
header("Content-Type: text/xml; charset=EUC-KR");

$mode=$_GET["mode"];
$tagname=$_GET["tagname"];
$sort=$_GET["sort"];
$block=$_GET["block"];
$gotopage=$_GET["gotopage"];
$productcode=$_GET["productcode"];

$selfcodefont_start = "<font class=\"prselfcode\">"; //�����ڵ� ��Ʈ ����
$selfcodefont_end = "</font>"; //�����ڵ� ��Ʈ ��

############# ��ǰ�������������� �±׵�� ############
if($mode=="prtagreg") {
	if($_data->ETCTYPE["TAGTYPE"]=="N") {
		echo "NO|���� �������� �̻�� �� �Դϴ�.";
		exit;
	}

	if(strlen($_ShopInfo->getMemid())==0) {
		echo "NO|�α��� �� TAG����� �����մϴ�.";
		exit;
	}

	if(strlen($productcode)!=18 || strlen($tagname)<2) {
		echo "NO|TAG��� ������ �߸��Ǿ����ϴ�.";
		exit;
	}

	$id="<".$_ShopInfo->getMemid().">,";
	$tag="<".$tagname.">,";
	$sql = "SELECT tagname,ids FROM tbltagproduct WHERE productcode='".$productcode."' AND tagname='".$tagname."' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		if(ereg($id,$row->ids)) {
			echo "NO|������ �̹� ����Ͻ� �±� �Դϴ�.";
			exit;
		} else {
			$sql = "UPDATE tbltagproduct SET cnt=cnt+1, ids=CONCAT('".$id."',ids) ";
			$sql.= "WHERE productcode='".$productcode."' AND tagname='".$tagname."' ";
			mysql_query($sql,get_db_conn());
			echo "OK|0|".$tagname;
			exit;
		}
	} else {
		$sql = "INSERT INTO tbltagproduct (productcode,tagname,cnt,ids) VALUES ('".$productcode."','".$tagname."','1','".$id."') ";
		mysql_query($sql,get_db_conn());

		$sql = "UPDATE tblproduct SET tag=CONCAT('".$tag."',tag), tagcount=tagcount+1 ";
		$sql.= "WHERE productcode='".$productcode."' ";
		mysql_query($sql,get_db_conn());

		echo "OK|1|".$tagname;
		exit;
	}
	mysql_free_result($result);

#��ǰ�������������� �±׵�� �Ϸ�� �±׵���Ÿ ��û
} else if($mode=="prtagget") {
	if($_data->ETCTYPE["TAGTYPE"]=="N") {
		echo "NO|���� �������� �̻�� �� �Դϴ�.";
		exit;
	}

	if(strlen($productcode)!=18) {
		echo "NO|TAG��� ������ �߸��Ǿ����ϴ�.";
		exit;
	}
	$sql = "SELECT tag FROM tblproduct WHERE productcode='".$productcode."' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		echo "OK|";
		$taglist=explode(",",$row->tag);
		$jj=0;
		for($i=0;$i<count($taglist);$i++) {
			$taglist[$i]=ereg_replace("(<|>)","",$taglist[$i]);
			if(strlen($taglist[$i])>0) {
				if($jj>0) echo ",&nbsp;&nbsp;";
				echo "<a href=\"".$Dir.FrontDir."tag.php?tagname=".urlencode($taglist[$i])."\" onmouseover=\"window.status='".$taglist[$i]."';return true;\" onmouseout=\"window.status='';return true;\">".$taglist[$i]."</a>";
				$jj++;
			}
		}
	} else {
		echo "NO|�±����� �ε��� ������ �߻��Ͽ����ϴ�.";
	}
	mysql_free_result($result);

###################### �±װ˻� #######################
} else if(($mode=="taglink" || $mode=="search") && strlen($tagname)>0) {

	//����Ʈ ����
	$setup[page_num] = 10;
	$setup[list_num] = 20;

	if ($block != "") {
		$nowblock = $block;
		$curpage  = $block * $setup[page_num] + $gotopage;
	} else {
		$nowblock = 0;
	}

	if (($gotopage == "") || ($gotopage == 0)) {
		$gotopage = 1;
	}

	$sql = "SELECT codeA, codeB, codeC, codeD FROM tblproductcode ";
	if(strlen($_ShopInfo->getMemid())==0) {
		$sql.= "WHERE group_code!='' ";
	} else {
		$sql.= "WHERE group_code!='".$_ShopInfo->getMemgroup()."' AND group_code!='ALL' AND group_code!='' ";
	}
	$result=mysql_query($sql,get_db_conn());
	$not_qry="";
	while($row=mysql_fetch_object($result)) {
		$tmpcode=$row->codeA;
		if($row->codeB!="000") $tmpcode.=$row->codeB;
		if($row->codeC!="000") $tmpcode.=$row->codeC;
		if($row->codeD!="000") $tmpcode.=$row->codeD;
		$not_qry.= "AND a.productcode NOT LIKE '".$tmpcode."%' ";
	}
	mysql_free_result($result);

	$qry = "WHERE a.display!='N' ";
	$qry.= "AND a.tag LIKE '%<".$tagname.">%' ";
	if(strlen($not_qry)>0) $qry.= $not_qry;

	$sql = "SELECT COUNT(*) as t_count ";
	$sql.= "FROM tblproduct AS a ";
	$sql.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
	$sql.= $qry;
	$sql.= "AND (a.group_check='N' OR b.group_code='".$_ShopInfo->getMemgroup()."') ";
	$result=mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
	$t_count = (int)$row->t_count;
	mysql_free_result($result);
	$pagecount = (($t_count - 1) / $setup[list_num]) + 1;

	$leftmenu="Y";
	if($_data->design_tagsearch=="U") {
		$sql="SELECT body,leftmenu FROM ".$designnewpageTables." WHERE type='tagsearch'";
		$result=mysql_query($sql,get_db_conn());
		if($row=mysql_fetch_object($result)) {
			$body=$row->body;
			$body=str_replace("[DIR]",$Dir,$body);
			$leftmenu=$row->leftmenu;
			$newdesign="Y";
		}
		mysql_free_result($result);
	}

	echo "<table border=0 cellpadding=0 cellspacing=0 width=100% style=\"table-layout:fixed\">\n";
	if ($leftmenu!="N") {
		echo "<tr>\n";
		if ($_data->title_type=="Y" && file_exists($Dir.DataDir."design/tagsearch_title.gif")) {
			echo "<td><img src=\"".$Dir.DataDir."design/tagsearch_title.gif\" border=0 alt=\"�����±װ˻�\"></td>";
		} else {
			echo "<td>\n";
			echo "<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>\n";
			echo "<TR>\n";
			echo "	<TD><IMG SRC=".$Dir."images/".$_data->icon_type."/tagsearch_title_head.gif ALT=></TD>\n";
			echo "	<TD width=100% valign=top background=".$Dir."images/".$_data->icon_type."/tagsearch_title_bg.gif></TD>\n";
			echo "	<TD width=40><IMG SRC=".$Dir."images/".$_data->icon_type."/tagsearch_title_tail.gif ALT=></TD>\n";
			echo "</TR>\n";
			echo "</TABLE>\n";
			echo "</td>\n";
		}
		echo "</tr>\n";
	}
	echo "<tr>\n";
	echo "	<td align=center>\n";
	include ($Dir.TempletDir."tagsearch/tagsearch".$_data->design_tagsearch.".php");
	echo "	</td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	if(/*$mode=="search" &&*/ $t_count>0) {	//�±װ˻��� ��쿡�� DB������Ʈ
		$sql = "INSERT tbltagsearchall SET ";
		$sql.= "tagname		= '".$tagname."', ";
		$sql.= "cnt			= '1' ";
		mysql_query($sql,get_db_conn());
		if(mysql_errno()==1062) {
			$sql = "UPDATE tbltagsearchall SET cnt=cnt+1 WHERE tagname='".$tagname."' ";
			mysql_query($sql,get_db_conn());
		}
		$sql = "INSERT tbltagsearch SET ";
		$sql.= "date		= '".date("Ymd")."', ";
		$sql.= "tagname		= '".$tagname."', ";
		$sql.= "cnt			= '1' ";
		mysql_query($sql,get_db_conn());
		if(mysql_errno()==1062) {
			$sql = "UPDATE tbltagsearch SET cnt=cnt+1 WHERE date='".date("Ymd")."' AND tagname='".$tagname."' ";
			mysql_query($sql,get_db_conn());
		}
	}

######################## ��ٱ��� ��� ##########################
} else if($mode=="basketin") {
	if(strlen($productcode)!=18) {
		echo "NO|��ǰ������ �߸��Ǿ����ϴ�.";
		exit;
	}
	//��ٱ��� ����Ű Ȯ��
	if(strlen($_ShopInfo->getTempkey())==0 || $_ShopInfo->getTempkey()=="deleted") {
		$_ShopInfo->setTempkey($_data->ETCTYPE["BASKETTIME"]);
	}

	$quantity=1;
	$sql = "SELECT productname,quantity,display,option1,option2,option_quantity,etctype,group_check FROM tblproduct ";
	$sql.= "WHERE productcode='".$productcode."' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		if($row->display!="Y") {
			$errmsg="�ش��ǰ�� �ǸŰ� ���� �ʴ� ��ǰ�Դϴ�.\n";
		}
		if($row->group_check!="N") {
			if(strlen($_ShopInfo->getMemid())>0) {
				$sqlgc = "SELECT COUNT(productcode) AS groupcheck_count FROM tblproductgroupcode ";
				$sqlgc.= "WHERE productcode='".$productcode."' ";
				$sqlgc.= "AND group_code='".$_ShopInfo->getMemgroup()."' ";
				$resultgc=mysql_query($sqlgc,get_db_conn());
				if($rowgc=@mysql_fetch_object($resultgc)) {
					if($rowgc->groupcheck_count<1) {
						$errmsg="�ش� ��ǰ�� ���� ��� ���� ��ǰ�Դϴ�.\n";
					}
					@mysql_free_result($resultgc);
				} else {
					$errmsg="�ش� ��ǰ�� ���� ��� ���� ��ǰ�Դϴ�.\n";
				}
			} else {
				$errmsg="�ش� ��ǰ�� ȸ�� ���� ��ǰ�Դϴ�.\n";
			}
		}
		if(strlen($errmsg)==0) {
			$miniq=1;
			$maxq="?";
			if(strlen($row->etctype)>0) {
				$etctemp = explode("",$row->etctype);
				for($i=0;$i<count($etctemp);$i++) {
					if(substr($etctemp[$i],0,6)=="MINIQ=")     $miniq=substr($etctemp[$i],6);
					if(substr($etctemp[$i],0,5)=="MAXQ=")      $maxq=substr($etctemp[$i],5);
				}
			}

			if(strlen(dickerview($row->etctype,0,1))>0) {
				$errmsg="�ش��ǰ�� �ǸŰ� ���� �ʽ��ϴ�. �ٸ� ��ǰ�� �ֹ��� �ּ���.\n";
			}
		}
		if(strlen($errmsg)==0) {
			if ($miniq!=1 && $miniq>1 && $quantity<$miniq) $quantity=$miniq;

			if(empty($option1) && strlen($row->option1)>0)  $option1=1;
			if(empty($option2) && strlen($row->option2)>0)  $option2=1;
			if(strlen($row->quantity)>0) {
				if ($quantity>$row->quantity) {
					if ($row->quantity>0)
						$errmsg.="�ش��ǰ�� ��� ".($_data->ETCTYPE["STOCK"]=="N"?"�����մϴ�.":"���� ".$row->quantity." �� �Դϴ�.")."\n";
					else
						$errmsg.= "[".ereg_replace("'","",$row->productname)."]��ǰ�� �ٸ� ���� �ֹ����� ǰ���Ǿ����ϴ�.\n";
				}
			}
			if(strlen($row->option_quantity)>0) {
				$optioncnt = explode(",",substr($row->option_quantity,1));
				if($option2==0) $tmoption2=1;
				else $tmoption2=$option2;
				$optionvalue=$optioncnt[(($tmoption2-1)*10)+($option1-1)];
				if($optionvalue<=0 && $optionvalue!="")
					$errmsg.="�ش��ǰ�� �ɼ��� �ٸ� ���� �ֹ����� ǰ���Ǿ����ϴ�.\n";
				else if($optionvalue<$quantity && $optionvalue!="")
					$errmsg.="�ش��ǰ�� ���õ� �ɼ��� ��� ".($_data->ETCTYPE["STOCK"]=="N"?"�����մϴ�.":"$optionvalue �� �Դϴ�.")."\n";
			}
		}
	} else {
		$errmsg="�ش��ǰ�� �������� �ʽ��ϴ�.\n";
	}
	mysql_free_result($result);

	if(strlen($errmsg)>0) {
		echo "NO|".$errmsg; exit;
	}

	// �̹� ��ٱ��Ͽ� ��� ��ǰ���� �˻��Ͽ� ������ ī��Ʈ�� ����.
	if (empty($option1))  $option1=0;
	if (empty($option2))  $option2=0;
	if (empty($opts))  $opts="0";

	$sql = "SELECT * FROM tblbasket WHERE tempkey='".$_ShopInfo->getTempkey()."' AND productcode='".$productcode."' ";
	$sql.= "AND opt1_idx='".$option1."' AND opt2_idx='".$option2."' AND optidxs='".$opts."' ";
	$result = mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
	mysql_free_result($result);

	if($row) {

	} else {
		$vdate = date("YmdHis");
		$sql = "SELECT COUNT(*) as cnt FROM tblbasket WHERE tempkey='".$_ShopInfo->getTempkey()."' ";
		$result = mysql_query($sql,get_db_conn());
		$row = mysql_fetch_object($result);
		mysql_free_result($result);
		if($row->cnt>=200) {
			echo "<script>alert('��ٱ��Ͽ��� �� 200�������� ������ �ֽ��ϴ�.');</script>";
			break;
		} else {
			$sql = "INSERT tblbasket SET ";
			$sql.= "tempkey		= '".$_ShopInfo->getTempkey()."', ";
			$sql.= "productcode	= '".$productcode."', ";
			$sql.= "opt1_idx	= '".$option1."', ";
			$sql.= "opt2_idx	= '".$option2."', ";
			$sql.= "optidxs		= '".$opts."', ";
			$sql.= "quantity	= '".$quantity."', ";
			$sql.= "date		= '".$vdate."' ";
			mysql_query($sql,get_db_conn());
		}
	}
	echo "OK|��ٱ��Ͽ� ��ϵǾ����ϴ�."; exit;
######################### ���ø���Ʈ ��� ########################
} else if($mode=="wishin") {
	$opts="0";
	$option1=0;
	$option2=0;

	if(strlen($_ShopInfo->getMemid())>0) {
		$codeA=substr($productcode,0,3);
		$codeB=substr($productcode,3,3);
		$codeC=substr($productcode,6,3);
		$codeD=substr($productcode,9,3);

		$sql = "SELECT * FROM tblproductcode WHERE codeA='".$codeA."' AND codeB='".$codeB."' AND codeC='".$codeC."' AND codeD='".$codeD."' ";
		$result=mysql_query($sql,get_db_conn());
		if($row=mysql_fetch_object($result)) {
			if($row->group_code=="NO") {	//���� �з�
				echo "NO|�ǸŰ� ����� ��ǰ�Դϴ�"; exit;
			} else if(strlen($row->group_code)>0 && $row->group_code!="ALL" && $row->group_code!=$_ShopInfo->getMemgroup()) {	//�׷�ȸ���� ����
				echo "NO|�ش� �з��� ���� ������ �����ϴ�."; exit;
			}
		} else {
			echo "NO|�ش� �з��� �������� �ʽ��ϴ�."; exit;
		}
		mysql_free_result($result);

		$sql = "SELECT productname,quantity,display,option1,option2,option_quantity,etctype,group_check FROM tblproduct ";
		$sql.= "WHERE productcode='".$productcode."' ";
		$result=mysql_query($sql,get_db_conn());
		if($row=mysql_fetch_object($result)) {
			if($row->display!="Y") {
				$errmsg="�ش� ��ǰ�� �ǸŰ� ���� �ʴ� ��ǰ�Դϴ�.\n";
			}
			if($row->group_check!="N") {
				if(strlen($_ShopInfo->getMemid())>0) {
					$sqlgc = "SELECT COUNT(productcode) AS groupcheck_count FROM tblproductgroupcode ";
					$sqlgc.= "WHERE productcode='".$productcode."' ";
					$sqlgc.= "AND group_code='".$_ShopInfo->getMemgroup()."' ";
					$resultgc=mysql_query($sqlgc,get_db_conn());
					if($rowgc=@mysql_fetch_object($resultgc)) {
						if($rowgc->groupcheck_count<1) {
							$errmsg="�ش� ��ǰ�� ���� ��� ���� ��ǰ�Դϴ�.\n";
						}
						@mysql_free_result($resultgc);
					} else {
						$errmsg="�ش� ��ǰ�� ���� ��� ���� ��ǰ�Դϴ�.\n";
					}
				} else {
					$errmsg="�ش� ��ǰ�� ȸ�� ���� ��ǰ�Դϴ�.\n";
				}
			}
			if(strlen($errmsg)==0) {
				if(strlen(dickerview($row->etctype,0,1))>0) {
					$errmsg="�ش� ��ǰ�� �ǸŰ� ���� �ʽ��ϴ�.\n";
				}
			}
			if(empty($option1) && strlen($row->option1)>0)  $option1=1;
			if(empty($option2) && strlen($row->option2)>0)  $option2=1;
		} else {
			$errmsg="�ش� ��ǰ�� �������� �ʽ��ϴ�.\n";
		}
		mysql_free_result($result);

		if(strlen($errmsg)>0) {
			echo "NO|".$errmsg; exit;
		}

		$sql = "SELECT COUNT(*) as totcnt FROM tblwishlist WHERE id='".$_ShopInfo->getMemid()."' ";
		$result2=mysql_query($sql,get_db_conn());
		$row2=mysql_fetch_object($result2);
		$totcnt=$row2->totcnt;
		mysql_free_result($result2);
		$maxcnt=100;
		if($totcnt>=$maxcnt) {
			$sql = "SELECT b.productcode ";
			$sql.= "FROM tblwishlist a, tblproduct b ";
			$sql.= "LEFT OUTER JOIN tblproductgroupcode c ON b.productcode=c.productcode ";
			$sql.= "WHERE a.id='".$_ShopInfo->getMemid()."' AND a.productcode=b.productcode ";
			$sql.= "AND b.display='Y' ";
			$sql.= "AND (b.group_check='N' OR c.group_code='".$_ShopInfo->getMemgroup()."') ";
			$sql.= "GROUP BY b.productcode ";
			$result2=mysql_query($sql,get_db_conn());
			$i=0;
			$wishprcode="";
			while($row2=mysql_fetch_object($result2)) {
				$wishprcode.="'".$row2->productcode."',";
				$i++;
			}
			mysql_free_result($result2);
			$totcnt=$i;
			$wishprcode=substr($wishprcode,0,-1);
			if(strlen($wishprcode)>0) {
				$sql = "DELETE FROM tblwishlist WHERE id='".$_ShopInfo->getMemid()."' AND productcode NOT IN (".$wishprcode.") ";
				mysql_query($sql,get_db_conn());
			}
		}
		if($totcnt<$maxcnt) {
			$sql = "SELECT COUNT(*) as cnt FROM tblwishlist WHERE id='".$_ShopInfo->getMemid()."' AND productcode='".$productcode."' ";
			$sql.= "AND opt1_idx='".$option1."' AND opt2_idx='".$option2."' AND optidxs='".$opts."' ";
			$result2=mysql_query($sql,get_db_conn());
			$row2=mysql_fetch_object($result2);
			$cnt=$row2->cnt;
			mysql_free_result($result2);
			if($cnt<=0) {
				$sql = "INSERT tblwishlist SET ";
				$sql.= "id			= '".$_ShopInfo->getMemid()."', ";
				$sql.= "productcode	= '".$productcode."', ";
				$sql.= "opt1_idx	= '".$option1."', ";
				$sql.= "opt2_idx	= '".$option2."', ";
				$sql.= "optidxs		= '".$opts."', ";
				$sql.= "date		= '".date("YmdHis")."' ";
				mysql_query($sql,get_db_conn());
			} else {
				$sql = "UPDATE tblwishlist SET date='".date("YmdHis")."' ";
				$sql.= "WHERE id='".$_ShopInfo->getMemid()."' ";
				$sql.= "AND productcode='".$productcode."' ";
				$sql.= "AND opt1_idx='".$option1."' AND opt2_idx='".$option2."' AND optidxs='".$opts."' ";
				mysql_query($sql,get_db_conn());
			}
			echo "OK|��ϵǾ����ϴ�."; exit;
		} else {
			echo "NO|WishList���� ".$maxcnt."�� ������ ����� �����մϴ�.\n\nWishList���� �ٸ� ��ǰ�� �����Ͻ� �� ����Ͻñ� �ٶ��ϴ�."; exit;
		}
	} else {
		echo "NO|�α��� �� �̿��� �����մϴ�.";
		exit;
	}

######################### �α��±� (���� ����) ####################
} else {
	if($sort!="name" && $sort!="best") $sort="name";
	$pretime=mktime(0,0,0,(int)date("m")-1,1,date("Y"));
	$predate=date("Ym",$pretime);

	$temptime=mktime(0,0,0,(int)date("m"),(int)date("d")-1,date("Y"));

	$sql = "SELECT tagname, SUM(cnt) as cnt FROM tbltagsearch WHERE (date LIKE '".$predate."%' OR date LIKE '".date("Ym",$temptime)."%') ";
	$sql.= "GROUP BY tagname ORDER BY cnt DESC LIMIT 100 ";

	unset($var);
	$success=false;
	$filename="tbltagsearch".date("Ymd").".cache";
	if(file_exists($Dir.DataDir."cache/sql/".$filename)==true) {
		if($fp=@fopen($Dir.DataDir."cache/sql/".$filename, "r")) {
			$szdata=fread($fp, filesize($Dir.DataDir."cache/sql/".$filename));
			fclose($fp);
			$var=unserialize($szdata);
			$success=true;
		}
	}
	if(!$success) {
		proc_matchfiledel($Dir.DataDir."cache/sql/tbltagsearch*");

		$result=mysql_query($sql,get_db_conn());
		if($err=mysql_error())
			trigger_error($err, E_USER_ERROR);
		while($row=mysql_fetch_object($result)){
			$var[]=$row;
		}
		mysql_free_result($result);
		$ret = WriteCache($var, $filename);
	}
	$count=count($var);

	$rank1=ceil(0.1*$count);
	$rank2=ceil(0.2*$count);
	$rank3=ceil(0.3*$count);
	$rank4=ceil(0.4*$count);

	unset($tagkey);
	unset($taglist);
	$i=0;
	while(@list($key,$row)=@each($var)) {
		$i++;

		if($i<=$rank1) {
			$rank=1;
		} else if($i<=($rank1+$rank2)) {
			$rank=2;
		} else if($i<=($rank1+$rank2+$rank3)) {
			$rank=3;
		} else {
			$rank=4;
		}
/*
		if($i>=1 && $i<=$rank1)					$rank=1;
		else if($i>=($rank1+1) && $i<=$rank2)	$rank=2;
		else if($i>=($rank2+1) && $i<=$rank3)	$rank=3;
		else if($i>=($rank3+1))	$rank=4;
*/

		$tagkey[$row->tagname]["tagname"]=$row->tagname;
		$tagkey[$row->tagname]["cnt"]=$row->cnt;
		$tagkey[$row->tagname]["rank"]=$rank;

		$taglist[$row->tagname]=$rank."-".$i;
	}

	if($sort=="name") {
		@ksort ($taglist);
	} else if($sort=="best") {
		@asort ($taglist);
	}
	@reset ($taglist);

	//����Ⱓ
	$start_date=(date("Y",$pretime)==date("Y")?date("m��d��",$pretime):date("Y��m��d��",$pretime));
	$end_date=(date("Y",$temptime)==date("Y")?date("m��d��",$temptime):date("Y��m��d��",$temptime));

	$leftmenu="Y";
	if($_data->design_tag=="U") {
		$sql="SELECT body,leftmenu FROM ".$designnewpageTables." WHERE type='tag'";
		$result=mysql_query($sql,get_db_conn());
		if($row=mysql_fetch_object($result)) {
			$body=$row->body;
			$body=str_replace("[DIR]",$Dir,$body);
			$leftmenu=$row->leftmenu;
			$newdesign="Y";
		}
		mysql_free_result($result);
	}

	echo "<table border=0 cellpadding=0 cellspacing=0 width=100% style=\"table-layout:fixed\">\n";
	if ($leftmenu!="N") {
		echo "<tr>\n";
		if ($_data->title_type=="Y" && file_exists($Dir.DataDir."design/tag_title.gif")) {
			echo "<td><img src=\"".$Dir.DataDir."design/tag_title.gif\" border=0 alt=\"�ֱ��α��±�\"></td>";
		} else {
			echo "<td>\n";
			echo "<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>\n";
			echo "<TR>\n";
			echo "	<TD><IMG SRC=".$Dir."images/".$_data->icon_type."/tag_title_head.gif ALT=></TD>\n";
			echo "	<TD width=100% valign=top background=".$Dir."images/".$_data->icon_type."/tag_title_bg.gif></TD>\n";
			echo "	<TD width=40><IMG SRC=".$Dir."images/".$_data->icon_type."/tag_title_tail.gif ALT=></TD>\n";
			echo "</TR>\n";
			echo "</TABLE>\n";
			echo "</td>\n";
		}
		echo "</tr>\n";
	}
	echo "<tr>\n";
	echo "	<td align=center>\n";
	include ($Dir.TempletDir."tag/tag".$_data->design_tag.".php");
	echo "	</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
}
?>
