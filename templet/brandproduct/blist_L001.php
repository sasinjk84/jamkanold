<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td>
			<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td height="45">
						<table border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td style="padding-right:5px;"><?=$codenavi?></td>
								<td><A HREF="javascript:ClipCopy('http://<?=$_ShopInfo->getShopurl()?>?<?=getenv("QUERY_STRING")?>')"><img src="<?=$Dir?>images/common/brandproduct/<?=$_bdata->list_type?>/btn_addr_copy.gif" border="0"></A></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td height="10"></td></tr>
<?
if($_bdata->title_type=="image") {
	if(file_exists($Dir.DataDir."shopimages/etc/BRD".$brandcode.".gif")) {
		echo "<tr>\n";
		echo "	<td align=center><img src=\"".$Dir.DataDir."shopimages/etc/BRD".$brandcode.".gif\" border=0 align=absmiddle></td>\n";
		echo "</tr>\n";
	}
} else if($_bdata->title_type=="html") {
	if(strlen($_bdata->title_body)>0) {
		echo "<tr>\n";
		echo "	<td align=center>";
		if (strpos(strtolower($_bdata->title_body),"<table")!=false)
			echo $_bdata->title_body;
		else
			echo ereg_replace("\n","<br>",$_bdata->title_body);
		echo "	</td>\n";
		echo "</tr>\n";
	}
}
?>
<?if($_data->ETCTYPE["CODEYES"]!="N" && strlen($brand_qryA)>0) {?>
<?
	$iscode=false;
	if(strlen($likecode)==0) {			//0���з� (0���� ���� ��� 1��,2���з��� �����ش�) - 2���� �ִ��� �˻�
		//0���� �����з��� ��쿣 �ƹ��͵� �������� �ʴ´�.
		$sql = "SELECT COUNT(*) as cnt FROM tblproductcode ";
		$sql.= "WHERE codeB!='000' AND group_code!='NO' ";
		$sql.= $brand_qryA;
		$result=mysql_query($sql,get_db_conn());
		$row=mysql_fetch_object($result);
		$cnt=$row->cnt;
		$iscode=true;
		mysql_free_result($result);

		$sql = "SELECT codeA,codeB,codeC,codeD,code_name,type FROM tblproductcode ";
		$sql.= "WHERE codeB='000' AND codeC='000' AND codeD='000' AND group_code!='NO' ";
		$sql.= "AND (type='L' || type='T' || type='LX' || type='TX') ";
		$sql.= $brand_qryA;
		$sql.= "ORDER BY sequence DESC ";
		$result=mysql_query($sql,get_db_conn());
		$category_list ="<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";

		if($cnt>0) {
			$i=0;
			while($row=mysql_fetch_object($result)) {
				//if($i>0) $category_list.="<tr><td height=1 colspan=2 bgcolor=FFFFFF></td></tr>\n";
				$category_list.="<tr>";
				$category_list.="	<td width=\"25%\" bgcolor=\"#EEEEEE\" style=\"padding:10px;\"><img src=\"".$Dir."images/common/brandproduct/".$_bdata->list_type."/plist_skin_iconaa.gif\" border=\"0\" align=\"absmiddle\" hspace=\"5\"><a href=\"".$Dir.FrontDir."productblist.php?".$brand_link."code=".$row->codeA.$row->codeB.$row->codeC.$row->codeD."\"><FONT class=upcodename>".$row->code_name."</font></a></td>\n";
				$category_list.="	<td width=\"75%\" style=\"padding:10px;\" class=subcodename>";
				if(!eregi("X",$row->type)) {
					$sql = "SELECT codeA,codeB,codeC,codeD,code_name,type FROM tblproductcode ";
					$sql.= "WHERE codeA='".$row->codeA."' ";
					$sql.= "AND codeB IN ('".implode("','",$blistcodeB[$row->codeA])."') ";
					$sql.= "AND codeC='000' AND codeD='000' AND group_code!='NO' ";
					$sql.= "AND (type='LM' || type='TM' || type='LMX' || type='TMX') ";
					$sql.= "ORDER BY sequence DESC ";
					$result2=mysql_query($sql,get_db_conn());
					$j=0;
					while($row2=mysql_fetch_object($result2)) {
						if($j>0) $category_list.=" | ";
						$category_list.="<a href=\"".$Dir.FrontDir."productblist.php?".$brand_link."code=".$row2->codeA.$row2->codeB.$row2->codeC.$row2->codeD."\"><FONT class=subcodename>".$row2->code_name."</font></a>";
						$j++;
					}
					mysql_free_result($result2);
				}

				$category_list.="	</td>\n";
				$category_list.="</tr>\n";
				$i++;
			}
		} else {
			$category_list.="<tr>";
			$category_list.="	<td style=\"padding:10px;\" class=subcodename>";
			$i=0;
			while($row=mysql_fetch_object($result)) {
				if($i>0) $category_list.=" | ";
				$category_list.="<a href=\"".$Dir.FrontDir."productblist.php?".$brand_link."code=".$row->codeA.$row->codeB.$row->codeC.$row->codeD."\"><FONT class=subcodename>".$row->code_name."</FONT></a>";
				$i++;
			}
			$category_list.="	</td>\n";
			$category_list.="</tr>\n";
		}
		$category_list.="</table>\n";
		mysql_free_result($result);
	} else if(strlen($likecode)==3) {			//1���з� (1���� ���� ��� 2��,3���з��� �����ش�) - 3���� �ִ��� �˻�
		//1���� �����з��� ��쿣 �ƹ��͵� �������� �ʴ´�.
		if($_cdata->type!="LX" && $_cdata->type!="TX") {	//�����з��� ���� ��쿡��
			$sql = "SELECT COUNT(*) as cnt FROM tblproductcode ";
			$sql.= "WHERE codeA='".$codeA."' ";
			$sql.= "AND codeB IN ('".implode("','",$blistcodeB[$codeA])."') ";
			$sql.= "AND codeC!='000' AND group_code!='NO' ";
			$result=mysql_query($sql,get_db_conn());
			$row=mysql_fetch_object($result);
			$cnt=$row->cnt;
			$iscode=true;
			mysql_free_result($result);

			$sql = "SELECT codeA,codeB,codeC,codeD,code_name,type FROM tblproductcode ";
			$sql.= "WHERE codeA='".$codeA."' ";
			$sql.= "AND codeB IN ('".implode("','",$blistcodeB[$codeA])."') ";
			$sql.= "AND codeC='000' AND codeD='000' AND group_code!='NO' ";
			$sql.= "AND (type='LM' || type='TM' || type='LMX' || type='TMX') ";
			$sql.= "ORDER BY sequence DESC ";
			$result=mysql_query($sql,get_db_conn());
			$category_list ="<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";

			if($cnt>0) {
				$i=0;
				while($row=mysql_fetch_object($result)) {
					//if($i>0) $category_list.="<tr><td height=1 colspan=2 bgcolor=FFFFFF></td></tr>\n";
					$category_list.="<tr>";
					$category_list.="	<td width=\"25%\" bgcolor=\"#EEEEEE\" style=\"padding:10px;\"><img src=\"".$Dir."images/common/brandproduct/".$_bdata->list_type."/plist_skin_iconaa.gif\" border=\"0\" align=\"absmiddle\" hspace=\"5\"><a href=\"".$Dir.FrontDir."productblist.php?".$brand_link."code=".$row->codeA.$row->codeB.$row->codeC.$row->codeD."\"><FONT class=upcodename>".$row->code_name."</font></a></td>\n";
					$category_list.="	<td width=\"75%\" style=\"padding:10px;\" class=subcodename>";
					if(!eregi("X",$row->type)) {
						$sql = "SELECT codeA,codeB,codeC,codeD,code_name,type FROM tblproductcode ";
						$sql.= "WHERE codeA='".$row->codeA."' AND codeB='".$row->codeB."' ";
						$sql.= "AND codeC IN ('".implode("','",$blistcodeC[$row->codeA.$row->codeB])."') ";
						$sql.= "AND codeD='000' AND group_code!='NO' ";
						$sql.= "AND (type='LM' || type='TM' || type='LMX' || type='TMX') ";
						$sql.= "ORDER BY sequence DESC ";
						$result2=mysql_query($sql,get_db_conn());
						$j=0;
						while($row2=mysql_fetch_object($result2)) {
							if($j>0) $category_list.=" | ";
							$category_list.="<a href=\"".$Dir.FrontDir."productblist.php?".$brand_link."code=".$row2->codeA.$row2->codeB.$row2->codeC.$row2->codeD."\"><FONT class=subcodename>".$row2->code_name."</font></a>";
							$j++;
						}
						mysql_free_result($result2);
					}

					$category_list.="	</td>\n";
					$category_list.="</tr>\n";
					$i++;
				}
			} else {
				$category_list.="<tr>";
				$category_list.="	<td style=\"padding:10px;\" class=subcodename>";
				$i=0;
				while($row=mysql_fetch_object($result)) {
					if($i>0) $category_list.=" | ";
					$category_list.="<a href=\"".$Dir.FrontDir."productblist.php?".$brand_link."code=".$row->codeA.$row->codeB.$row->codeC.$row->codeD."\"><FONT class=subcodename>".$row->code_name."</FONT></a>";
					$i++;
				}
				$category_list.="	</td>\n";
				$category_list.="</tr>\n";
			}
			$category_list.="</table>\n";
			mysql_free_result($result);
		} else {
			$iscode=true;
			$category_list ="<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
			$category_list.="<tr>";
			$category_list.="	<td style=\"padding:10px;\" class=subcodename>";
			$sql = "SELECT codeA,codeB,codeC,codeD,code_name,type FROM tblproductcode ";
			$sql.= "WHERE codeB='000' AND codeC='000' AND codeD='000' AND group_code!='NO' ";
			$sql.= "AND (type='L' || type='T' || type='LX' || type='TX') ";
			$sql.= $brand_qryA;
			$sql.= "ORDER BY sequence DESC ";
			$result=mysql_query($sql,get_db_conn());
			$i=0;
			while($row=mysql_fetch_object($result)) {
				if($i>0) $category_list.=" | ";
				$category_list.="<a href=\"".$Dir.FrontDir."productblist.php?".$brand_link."code=".$row->codeA.$row->codeB.$row->codeC.$row->codeD."\"><FONT class=subcodename>";
				if($code==$row->codeA.$row->codeB.$row->codeC.$row->codeD) {
					$category_list.="<B>".$row->code_name."</B>";
				} else {
					$category_list.="".$row->code_name."";
				}
				$category_list.="</FONT></a>";
				$i++;
			}
			$category_list.="	</td>\n";
			$category_list.="</tr>\n";
			$category_list.="</table>\n";
			mysql_free_result($result);
		}
	} else if(strlen($likecode)==6) {	//2���з� (2���� ���� ��� 3��,4���з��� �����ش�) - 4���� �ִ��� �˻�
		//2���� �����з��� ��쿣 1���� ���� 2���� �����ش�
		if($_cdata->type!="LMX" && $_cdata->type!="TMX") {	//�����з��� ���� ��쿡��
			$sql = "SELECT COUNT(*) as cnt FROM tblproductcode ";
			$sql.= "WHERE codeA='".$codeA."' AND codeB='".$codeB."' AND codeD!='000' AND group_code!='NO' ";
			$sql.= "AND codeC IN ('".implode("','",$blistcodeC[$codeA.$codeB])."') ";
			$result=mysql_query($sql,get_db_conn());
			$row=mysql_fetch_object($result);
			$cnt=$row->cnt;
			$iscode=true;
			mysql_free_result($result);

			$sql = "SELECT codeA,codeB,codeC,codeD,code_name,type FROM tblproductcode ";
			$sql.= "WHERE codeA='".$codeA."' AND codeB='".$codeB."' ";
			$sql.= "AND codeC IN ('".implode("','",$blistcodeC[$codeA.$codeB])."') ";
			$sql.= "AND codeD='000' AND group_code!='NO' ";
			$sql.= "AND (type='LM' || type='TM' || type='LMX' || type='TMX') ";
			$sql.= "ORDER BY sequence DESC ";

			$result=mysql_query($sql,get_db_conn());
			$category_list="<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
			if($cnt>0) {
				$i=0;
				while($row=mysql_fetch_object($result)) {
					$category_list.="<tr>";
					$category_list.="	<td width=\"25%\" bgcolor=\"#EEEEEE\" style=\"padding:10px;\"><img src=\"".$Dir."images/common/brandproduct/".$_bdata->list_type."/plist_skin_iconaa.gif\" border=\"0\" align=\"absmiddle\" hspace=\"5\"><a href=\"".$Dir.FrontDir."productblist.php?".$brand_link."code=".$row->codeA.$row->codeB.$row->codeC.$row->codeD."\"><FONT class=upcodename>".$row->code_name."</FONT></a></td>\n";
					$category_list.="	<td width=\"75%\" style=\"padding:10px;\" class=subcodename>";
					if(!eregi("X",$row->type)) {
						$sql = "SELECT codeA,codeB,codeC,codeD,code_name,type FROM tblproductcode ";
						$sql.= "WHERE codeA='".$row->codeA."' AND codeB='".$row->codeB."' AND codeC='".$row->codeC."' ";
						$sql.= "AND codeD IN ('".implode("','",$blistcodeD[$row->codeA.$row->codeB.$row->codeC])."') ";
						$sql.= "AND group_code!='NO' ";
						$sql.= "AND (type='LM' || type='TM' || type='LMX' || type='TMX') ";
						$sql.= "ORDER BY sequence DESC ";
						$result2=mysql_query($sql,get_db_conn());
						$j=0;
						while($row2=mysql_fetch_object($result2)) {
							if($j>0) $category_list.=" | ";
							$category_list.="<a href=\"".$Dir.FrontDir."productblist.php?".$brand_link."code=".$row2->codeA.$row2->codeB.$row2->codeC.$row2->codeD."\"><FONT class=subcodename>".$row2->code_name."</FONT></a>";
							$j++;
						}
						mysql_free_result($result2);
					}

					$category_list.="	</td>\n";
					$category_list.="</tr>\n";
					$i++;
				}
			} else {
				$category_list.="<tr>";
				$category_list.="	<td style=\"padding:10px;\" class=subcodename>";
				$i=0;
				while($row=mysql_fetch_object($result)) {
					if($i>0) $category_list.=" | ";
					$category_list.="<a href=\"".$Dir.FrontDir."productblist.php?".$brand_link."code=".$row->codeA.$row->codeB.$row->codeC.$row->codeD."\"><FONT class=subcodename>".$row->code_name."</FONT></a>";
					$i++;
				}
				$category_list.="	</td>\n";
				$category_list.="</tr>\n";
			}
			$category_list.="</table>\n";
			mysql_free_result($result);
		} else {
			$iscode=true;
			$category_list ="<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
			$category_list.="<tr>";
			$category_list.="	<td style=\"padding:10px;\" class=subcodename>";
			$sql = "SELECT codeA,codeB,codeC,codeD,code_name,type FROM tblproductcode ";
			$sql.= "WHERE codeA='".$codeA."' ";
			$sql.= "AND codeB IN ('".implode("','",$blistcodeB[$codeA])."') ";
			$sql.= "AND codeC='000' AND codeD='000' AND group_code!='NO' ";
			$sql.= "AND (type='LM' || type='TM' || type='LMX' || type='TMX') ";
			$sql.= "ORDER BY sequence DESC ";
			$result=mysql_query($sql,get_db_conn());
			$i=0;
			while($row=mysql_fetch_object($result)) {
				if($i>0) $category_list.=" | ";
				$category_list.="<a href=\"".$Dir.FrontDir."productblist.php?".$brand_link."code=".$row->codeA.$row->codeB.$row->codeC.$row->codeD."\"><FONT class=subcodename>";
				if($code==$row->codeA.$row->codeB.$row->codeC.$row->codeD) {
					$category_list.="<B>".$row->code_name."</B>";
				} else {
					$category_list.="".$row->code_name."";
				}
				$category_list.="</FONT></a>";
				$i++;
			}
			$category_list.="	</td>\n";
			$category_list.="</tr>\n";
			$category_list.="</table>\n";
			mysql_free_result($result);
		}
	} else if(strlen($likecode)==9) {	//3���з� (2���� ���� ��� 3��, 4���з��� �����ش�) - 4���� �ִ��� �˻�
		//3���� �����з��� ��쿣 2���� ���� 3���� �����ش�
		if($_cdata->type!="LMX" && $_cdata->type!="TMX") {	//�����з��� ���� ��쿡��
			$sql = "SELECT COUNT(*) as cnt FROM tblproductcode WHERE codeA='".$codeA."' AND codeB='".$codeB."' ";
			$sql.= "AND codeC IN ('".implode("','",$blistcodeC[$codeA.$codeB])."') ";
			$sql.= "AND codeD!='000' AND group_code!='NO' ";
			$result=mysql_query($sql,get_db_conn());
			$row=mysql_fetch_object($result);
			$cnt=$row->cnt;
			$iscode=true;
			mysql_free_result($result);

			$sql = "SELECT codeA,codeB,codeC,codeD,code_name,type FROM tblproductcode ";
			$sql.= "WHERE codeA='".$codeA."' AND codeB='".$codeB."' ";
			$sql.= "AND codeC IN ('".implode("','",$blistcodeC[$codeA.$codeB])."') ";
			$sql.= "AND codeD='000' AND group_code!='NO' ";
			$sql.= "AND (type='LM' || type='TM' || type='LMX' || type='TMX') ";
			$sql.= "ORDER BY sequence DESC ";
			$result=mysql_query($sql,get_db_conn());
			$category_list="<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
			if($cnt>0) {
				$i=0;
				while($row=mysql_fetch_object($result)) {
					$category_list.="<tr>";
					$category_list.="	<td width=\"25%\" bgcolor=\"#EEEEEE\" style=\"padding:10px;\"><img src=\"".$Dir."images/common/brandproduct/".$_bdata->list_type."/plist_skin_iconaa.gif\" border=\"0\" align=\"absmiddle\" hspace=\"5\"><a href=\"".$Dir.FrontDir."productblist.php?".$brand_link."code=".$row->codeA.$row->codeB.$row->codeC.$row->codeD."\"><FONT class=upcodename>".$row->code_name."</FONT></a></td>\n";
					$category_list.="	<td width=\"75%\" style=\"padding:10px;\" class=subcodename>";
					if(!eregi("X",$row->type)) {
						$sql = "SELECT codeA,codeB,codeC,codeD,code_name,type FROM tblproductcode ";
						$sql.= "WHERE codeA='".$row->codeA."' AND codeB='".$row->codeB."' AND codeC='".$row->codeC."' ";
						$sql.= "AND codeD IN ('".implode("','",$blistcodeD[$row->codeA.$row->codeB.$row->codeC])."') ";
						$sql.= "AND group_code!='NO' ";
						$sql.= "AND (type='LM' || type='TM' || type='LMX' || type='TMX') ";
						$sql.= "ORDER BY sequence DESC ";
						$result2=mysql_query($sql,get_db_conn());
						$j=0;
						while($row2=mysql_fetch_object($result2)) {
							if($j>0) $category_list.=" | ";
							$category_list.="<a href=\"".$Dir.FrontDir."productblist.php?".$brand_link."code=".$row2->codeA.$row2->codeB.$row2->codeC.$row2->codeD."\"><FONT class=subcodename>".$row2->code_name."</FONT></a>";
							$j++;
						}
						mysql_free_result($result2);
					}

					$category_list.="	</td>\n";
					$category_list.="</tr>\n";
					$i++;
				}
			} else {
				$category_list.="<tr>";
				$category_list.="	<td style=\"padding:10px;\" class=subcodename>";
				$i=0;
				while($row=mysql_fetch_object($result)) {
					if($i>0) $category_list.=" | ";
					$category_list.="<a href=\"".$Dir.FrontDir."productblist.php?".$brand_link."code=".$row->codeA.$row->codeB.$row->codeC.$row->codeD."\"><FONT class=subcodename>".$row->code_name."</FONT></a>";
					$i++;
				}
				$category_list.="	</td>\n";
				$category_list.="</tr>\n";
			}
			$category_list.="</table>\n";
			mysql_free_result($result);
		} else {
			$iscode=true;
			$category_list ="<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
			$category_list.="<tr>";
			$category_list.="	<td style=\"padding:10px;\" class=subcodename>";
			$sql = "SELECT codeA,codeB,codeC,codeD,code_name,type FROM tblproductcode ";
			$sql.= "WHERE codeA='".$codeA."' AND codeB='".$codeB."' ";
			$sql.= "AND codeC IN ('".implode("','",$blistcodeC[$codeA.$codeB])."') ";
			$sql.= "AND codeD='000' AND group_code!='NO' ";
			$sql.= "AND (type='LM' || type='TM' || type='LMX' || type='TMX') ";
			$sql.= "ORDER BY sequence DESC ";
			$result=mysql_query($sql,get_db_conn());
			$i=0;
			while($row=mysql_fetch_object($result)) {
				if($i>0) $category_list.=" | ";
				$category_list.="<a href=\"".$Dir.FrontDir."productblist.php?".$brand_link."code=".$row->codeA.$row->codeB.$row->codeC.$row->codeD."\"><FONT class=subcodename>";
				if($code==$row->codeA.$row->codeB.$row->codeC.$row->codeD) {
					$category_list.="<B>".$row->code_name."</B>";
				} else {
					$category_list.="".$row->code_name."";
				}
				$category_list.="</FONT></a>";
				$i++;
			}
			$category_list.="	</td>\n";
			$category_list.="</tr>\n";
			$category_list.="</table>\n";
			mysql_free_result($result);
		}
	} else if(strlen($likecode)==12) {	//4���з� (3���� ���� ��� 4���з��� �����ش�)
		$iscode=true;
		$category_list ="<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
		$category_list.="<tr>";
		$category_list.="	<td style=\"padding:10px;\" class=subcodename>";
		$sql = "SELECT codeA,codeB,codeC,codeD,code_name,type FROM tblproductcode ";
		$sql.= "WHERE codeA='".$codeA."' AND codeB='".$codeB."' AND codeC='".$codeC."' ";
		$sql.= "AND codeD IN ('".implode("','",$blistcodeD[$codeA.$codeB.$codeC])."') ";
		$sql.= "AND group_code!='NO' ";
		$sql.= "AND (type='LM' || type='TM' || type='LMX' || type='TMX') ";
		$sql.= "ORDER BY sequence DESC ";

		$result=mysql_query($sql,get_db_conn());
		$i=0;
		while($row=mysql_fetch_object($result)) {
			if($i>0) $category_list.=" | ";
			$category_list.="<a href=\"".$Dir.FrontDir."productblist.php?".$brand_link."code=".$row->codeA.$row->codeB.$row->codeC.$row->codeD."\"><FONT class=subcodename>";
			if($code==$row->codeA.$row->codeB.$row->codeC.$row->codeD) {
				$category_list.="<B>".$row->code_name."</B>";
			} else {
				$category_list.="".$row->code_name."";
			}
			$category_list.="</FONT></a>";
			$i++;
		}
		$category_list.="	</td>\n";
		$category_list.="</tr>\n";
		$category_list.="</table>\n";
		mysql_free_result($result);
	}
?>
	<?if($iscode==true){?>

	<tr>
		<td width="100%">
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td style="padding-left:10px;padding-bottom:5px;padding-top:5px;line-height:24px;color:#000000;font-size:15px;" class=choicecodename><b><?=($_cdata->code_name?$_cdata->code_name:$_bdata->brandname)?></b></td>
		</tr>
		<tr>
			<td bgcolor="#F2F2F2" style="padding:1px;"><?=$category_list?></td>
		</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>


	<?}?>
<?}?>

<!-- ��ǰ��� ���� -->
<?
$sql = "SELECT COUNT(*) as t_count FROM tblproduct AS a ";
$sql.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
$sql.= $qry." ";
$sql.= "AND (a.group_check='N' OR b.group_code='".$_ShopInfo->getMemgroup()."') ";
if(strlen($not_qry)>0) {
	$sql.= $not_qry." ";
}
$result=mysql_query($sql,get_db_conn());
$row=mysql_fetch_object($result);
$t_count = (int)$row->t_count;
mysql_free_result($result);
$pagecount = (($t_count - 1) / $setup[list_num]) + 1;

?>
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td>
			<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td><IMG SRC="<?=$Dir?>images/common/brandproduct/<?=$_bdata->list_type?>/plist_skin_sticon.gif" border="0"></td>
					<td width="100%" background="<?=$Dir?>images/common/brandproduct/<?=$_bdata->list_type?>/plist_skin_stibg.gif" style="color:#ffffff;font-size:11px;"><B>�귣�� : <?=$_bdata->brandname?></B> �� ��ϻ�ǰ : <b><?=$t_count?>��</b></td>
					<td><IMG SRC="<?=$Dir?>images/common/brandproduct/<?=$_bdata->list_type?>/plist_skin_stimg.gif" border="0"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="30">
		<?
			$_date="";
			$_sellcount_desc="";
			$_price="";
			$_price_desc="";

			switch(trim($sort)){
				case "sellcount_desc":
					$_sellcount_desc="class=\"sortOn\"";
				break;

				case "price":
					$_price="class=\"sortOn\"";
				break;

				case "price_desc":
					$_price_desc="class=\"sortOn\"";
				break;

				case "reserve_desc":
					$_reserve_desc="class=\"sortOn\"";
				break;

				case "date":
				default:
					$_date="class=\"sortOn\"";
				break;
			}
		?>
			<ul class="prSortType">
				<li><a href="javascript:ChangeSort('new_desc');" <?=$_date?>>�űԵ�ϼ�</a></li>
				<li><a href="javascript:ChangeSort('best_desc');" <?=$_sellcount_desc?>>�α��ǰ��</a></li>
				<li><a href="javascript:ChangeSort('price');" <?=$_price?>>�������ݼ�</a></li>
				<li><a href="javascript:ChangeSort('price_desc');" <?=$_price_desc?>>�������ݼ�</a></li>
				<li class="last"><a href="javascript:ChangeSort('reserve_desc');" <?=$_reserve_desc?>>�����ݼ�</a></li>
			</ul>
			<div style="float:right; text-align:right;">
			<?
				if($listnum == 8) $sel8 = "selected";
				if($listnum == 16) $sel16 = "selected";
				if($listnum == 32) $sel32 = "selected";

				$listselect = "
					<select name=\"listnum2\" onchange=\"ChangeNum(this)\">
						<option value='8' ".$sel8.">8</option>
						<option value='16' ".$sel16.">16</option>
						<option value='32' ".$sel32.">32</option>
					</select>
				";
				echo $listselect."���� ����";
			?>
			</div>
		</td>
	</tr>
	<!--
	<tr>
		<td height="28" style="padding-left:10px;"><IMG SRC="<?=$Dir?>images/common/brandproduct/<?=$_bdata->list_type?>/plist_skin_text01.gif" border="0"><a href="javascript:ChangeSort('production');"><IMG SRC="<?=$Dir?>images/common/brandproduct/<?=$_bdata->list_type?>/plist_skin_nerotop<?if($sort=="production")echo"_on";?>.gif" border="0"></a><a href="javascript:ChangeSort('production_desc');"><IMG SRC="<?=$Dir?>images/common/brandproduct/<?=$_bdata->list_type?>/plist_skin_nerodow<?if($sort=="production_desc")echo"_on";?>.gif" border="0"></a><img src="../images/common/space_line.gif" width="8" height="1" border="0"><IMG SRC="<?=$Dir?>images/common/brandproduct/<?=$_bdata->list_type?>/plist_skin_text02.gif" border="0"><a href="javascript:ChangeSort('name');"><IMG SRC="<?=$Dir?>images/common/brandproduct/<?=$_bdata->list_type?>/plist_skin_nerotop<?if($sort=="name")echo"_on";?>.gif" border="0"></a><a href="javascript:ChangeSort('name_desc');"><IMG SRC="<?=$Dir?>images/common/brandproduct/<?=$_bdata->list_type?>/plist_skin_nerodow<?if($sort=="name_desc")echo"_on";?>.gif" border="0"></a><img src="../images/common/space_line.gif" width="8" height="1" border="0"><IMG SRC="<?=$Dir?>images/common/brandproduct/<?=$_bdata->list_type?>/plist_skin_text03.gif" border="0"><a href="javascript:ChangeSort('price');"><IMG SRC="<?=$Dir?>images/common/brandproduct/<?=$_bdata->list_type?>/plist_skin_nerotop<?if($sort=="price")echo"_on";?>.gif" border="0"></a><a href="javascript:ChangeSort('price_desc');"><IMG SRC="<?=$Dir?>images/common/brandproduct/<?=$_bdata->list_type?>/plist_skin_nerodow<?if($sort=="price_desc")echo"_on";?>.gif" border="0"></a><img src="../images/common/space_line.gif" width="8" height="1" border="0"><IMG SRC="<?=$Dir?>images/common/brandproduct/<?=$_bdata->list_type?>/plist_skin_text04.gif" border="0"><a href="javascript:ChangeSort('reserve');"><IMG SRC="<?=$Dir?>images/common/brandproduct/<?=$_bdata->list_type?>/plist_skin_nerotop<?if($sort=="reserve")echo"_on";?>.gif" border="0"></a><a href="javascript:ChangeSort('reserve_desc');"><IMG SRC="<?=$Dir?>images/common/brandproduct/<?=$_bdata->list_type?>/plist_skin_nerodow<?if($sort=="reserve_desc")echo"_on";?>.gif" border="0"></a></td>
	</tr>
	-->
	<tr>
		<td height="1" background="<?=$Dir?>images/common/brandproduct/<?=$_bdata->list_type?>/plist_skin_line3.gif"></td>
	</tr>
	<tr>
		<td height="20"></td>
	</tr>
	<tr>
		<td>
			<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td>
						<table cellpadding="2" cellspacing="0" width="100%">
							<tr>
<?
		//��ȣ, ����, ��ǰ��, ������, ����
		$tmp_sort=explode("_",$sort);
		if($tmp_sort[0]=="reserve") {
			$addsortsql=",IF(a.reservetype='N',a.reserve*1,a.reserve*a.sellprice*0.01) AS reservesort ";
		}

		$sql = "SELECT a.productcode, a.productname, a.quantity, ".( (isSeller()=="Y") ? "if(a.productdisprice>0,a.productdisprice,a.sellprice) as sellprice, if(a.productdisprice>0,1,0)":"a.sellprice, 0" )." as isdiscountprice, ";
		$sql.= "a.tinyimage, a.date, a.etctype, a.reserve, a.reservetype, a.option_price, a.consumerprice, a.tag, a.selfcode,a.prmsg, a.discountRate, a.vender, a.sellcount, IF(d.discountYN='Y',if(d.discount>1,d.discount,d.discount*a.sellprice),0) AS discountprices ";
		$sql.= $addsortsql;
		$sql.= "FROM tblproduct AS a ";
		$sql.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
		$sql.= " left join tblmemberdiscount d on (d.productcode = a.productcode and d.group_code='".$_ShopInfo->getMemgroup()."') ";
		$sql.= $qry." ";
		$sql.= "AND (a.group_check='N' OR b.group_code='".$_ShopInfo->getMemgroup()."') ";
		if(strlen($not_qry)>0) {
			$sql.= $not_qry." ";
		}
		if($tmp_sort[0]=="production") $sql.= "ORDER BY a.production ".$tmp_sort[1]." ";
		else if($tmp_sort[0]=="name") $sql.= "ORDER BY a.productname ".$tmp_sort[1]." ";
		else if($tmp_sort[0]=="price") $sql.= "ORDER BY a.sellprice ".$tmp_sort[1]." ";
		else if($tmp_sort[0]=="reserve") $sql.= "ORDER BY reservesort ".$tmp_sort[1]." ";
		else if($tmp_sort[0]=="new") $sql.= "ORDER BY a.regdate ".$tmp_sort[1]." ";
		else if($tmp_sort[0]=="best") $sql.= "ORDER BY a.sellcount ".$tmp_sort[1]." ";
		else $sql.= "ORDER BY a.productname ";
		$sql.= "LIMIT " . ($setup[list_num] * ($gotopage - 1)) . ", " . $setup[list_num];
		$result=mysql_query($sql,get_db_conn());

		$i=0;
		while($row=mysql_fetch_object($result)) {

			// ���� ���� ���� ��ǰ ������
			$wholeSaleIcon = ( $row->isdiscountprice == 1 ) ? $wholeSaleIconSet:"";

			// ������ ǥ��
			$discountRate = ( $row->discountRate > 0 ) ? "<strong>".$row->discountRate."</strong>%��" : "";

			$memberpriceValue = $row->sellprice;
			$strikeStart = $strikeEnd = '';
			$memberprice = 0;
			if($row->discountprices>0 AND isSeller() != 'Y' ){
				$memberprice = number_format($row->sellprice - $row->discountprices);
				$strikeStart = "<strike>";
				$strikeEnd = "</strike>";
				$memberpriceValue = ($row->sellprice - $row->discountprices);
			}

			$number = ($t_count-($setup[list_num] * ($gotopage-1))-$i);
			$tableSize = $_data->primg_minisize + 12;

			if ($i!=0 && $i%4==0) {
				echo "</tr><tr><td colspan=\"9\" height=\"10\"></td></tr>\n";
			}
			if ($i!=0 && $i%4!=0) {
				echo "<td width=\"10\" nowrap></td>";
			}
			echo "<td align=\"center\" valign=\"top\">\n";
			echo "<TABLE cellSpacing=\"0\" cellPadding=\"0\" width=\"".$tableSize."\" border=\"0\" id=\"G".$row->productcode."\" onmouseover=\"quickfun_show(this,'G".$row->productcode."','')\" onmouseout=\"quickfun_show(this,'G".$row->productcode."','none')\" class=\"prInfoBox\">\n";
			echo "<TR>\n";
			echo "	<TD align=\"center\" height=\"120\" style=\"padding:5px;\">";
			echo "<A HREF=\"".$Dir.FrontDir."productdetail.php?".$brand_link."productcode=".$row->productcode.$add_query."&sort=".$sort."\" onmouseover=\"window.status='��ǰ����ȸ';return true;\" onmouseout=\"window.status='';return true;\">";
			if (strlen($row->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$row->tinyimage)==true) {
				echo "<img src=\"".$Dir.DataDir."shopimages/product/".urlencode($row->tinyimage)."\" border=\"0\" ";
				$width = getimagesize($Dir.DataDir."shopimages/product/".$row->tinyimage);
				if($_data->ETCTYPE["IMGSERO"]=="Y") {
					if ($width[1]>$width[0] && $width[1]>$_data->primg_minisize2) echo "height=\"".$_data->primg_minisize2."\" ";
					else if (($width[1]>=$width[0] && $width[0]>=$_data->primg_minisize) || $width[0]>=$_data->primg_minisize) echo "width=\"".$_data->primg_minisize."\" ";
				} else {
					if ($width[0]>=$width[1] && $width[0]>=$_data->primg_minisize) echo "width=\"".$_data->primg_minisize."\" ";
					else if ($width[1]>=$_data->primg_minisize) echo "height=\"".$_data->primg_minisize."\" ";
				}
			} else {
				echo "<img src=\"".$Dir."images/no_img.gif\" border=\"0\" align=\"center\"";
			}
			echo "	></A></td>";
			echo "</tr>\n";

			echo "<tr><td height=\"3\" style=\"position:relative;\">".($_data->ETCTYPE["QUICKTOOLS"]!="Y"?"<script>quickfun_write('".$Dir."','G','".$row->productcode."','".($row->quantity=="0"?"":"1")."')</script>":"")."</td></tr>\n";

			echo "<tr>";
			echo "	<TD style=\"padding:5px 7px; word-break:break-all;\"><A HREF=\"".$Dir.FrontDir."productdetail.php?".$brand_link."productcode=".$row->productcode.$add_query."&sort=".$sort."\" onmouseover=\"window.status='��ǰ����ȸ';return true;\" onmouseout=\"window.status='';return true;\"><FONT class=\"prname\">".viewproductname($row->productname,$row->etctype,$row->selfcode)."</FONT>".(strlen($row->prmsg)?'<br /><span class="prmsgArea">'.$row->prmsg.'</span>':'')."</A></td>\n";
			echo "</tr>\n";

			//���߰� + �ǸŰ� + ������ + ȸ�����ΰ�
			echo "<tr>";
			echo "	<td style=\"padding:0px 7px 7px 7px; word-break:break-all;\">
							<table border=0 cellpadding=0 cellspacing=0 width=100%>
								<tr>
									<td>
			";
			if($row->consumerprice!=0) {
				echo "	<span class=\"prconsumerprice\" style=\"padding-right:2px;\"><strike>".number_format($row->consumerprice)."</strike>��</span>\n";
				//echo "	<td align=\"center\" style=\"word-break:break-all;\" class=\"prconsumerprice\"><img src=\"".$Dir."images/common/won_icon2.gif\" border=\"0\" style=\"margin-right:2px;\"><strike>".number_format($row->consumerprice)."</strike>��</td>\n";
			}

			// ȸ�� ���ΰ��� ���� �� ���� class ����
			if($memberprice > 0){
				$mainprpriceClass = "";
			}else{
				$mainprpriceClass = "mainprprice";
			}

			echo "<span style=\"white-space:nowrap;\">";
			echo $strikeStart;
			if($dicker=dickerview($row->etctype,number_format($row->sellprice)."��",1)) {
				echo $dicker;
			} else if(strlen($_data->proption_price)==0) {
				echo "<strong class=\"".$mainprpriceClass."\">".$wholeSaleIcon.number_format($row->sellprice)."</strong><strong>��</strong>";
				//echo "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 style=\"margin-right:2px;\">".$wholeSaleIcon.number_format($row->sellprice)."��";
				//if (strlen($row->option_price)!=0) echo "(�⺻��)";
			} else {
				//echo "<img src=\"".$Dir."images/common/won_icon.gif\" border=\"0\" style=\"margin-right:2px;\">";
				if (strlen($row->option_price)==0) echo number_format($row->sellprice)."��";
				else echo ereg_replace("\[PRICE\]",number_format($row->sellprice),$_data->proption_price);
			}
			echo $strikeEnd;
			echo "
							</span>
						</td>
			";
			if($row->discountRate > 0){
				echo "<td align=\"right\" valign=\"bottom\" class=\"discount\">".$discountRate."</td>";
			}
			echo "
					</tr>
				</table>
			";

			if ($row->quantity=="0") echo soldout();

			//ȸ�����ΰ� ����
			if( $memberprice > 0 ) {
				echo "	<div><span class=\"mainprprice\">".dickerview($row->etctype,$memberprice."��")."</span> <img src=\"".$Dir."images/common/memsale_icon.gif\" align=\"absmiddle\" alt=\"\" /></div>\n";
				//echo "	<td align=center valign=top style=\"word-break:break-all;\" class=\"mainprprice\"><img src=\"".$Dir."images/common/memsale_icon.gif\" align=\"absmiddle\" alt=\"\" />".dickerview($row->etctype,$memberprice."��")."</td>\n";
			}

			$reserveconv=getReserveConversion($row->reserve,$row->reservetype,$memberpriceValue,"Y");
			if($reserveconv>0) {
				echo "	<div style=\"margin-top:5px;\"><img src=\"".$Dir."images/common/reserve_icon.gif\" border=\"0\" style=\"margin-right:2px;\" align=\"absmiddle\" alt=\"\" /><span class=\"prreserve\">".number_format($reserveconv)."</span>��</div>\n";
				//echo "	<td align=\"center\" style=\"word-break:break-all;\" class=\"prreserve\"><img src=\"".$Dir."images/common/reserve_icon.gif\" border=\"0\" style=\"margin-right:2px;\" align=\"absmiddle\" alt=\"\" />".number_format($reserveconv)."��</td>\n";
			}
			echo "	</td>\n";
			echo "</tr>\n";

			if($_data->ETCTYPE["TAGTYPE"]=="Y") {
				$taglist=explode(",",$row->tag);
				$jj=0;
				for($ii=0;$ii<$plist0_tag_0_count;$ii++) {
					$taglist[$ii]=ereg_replace("(<|>)","",$taglist[$ii]);
					if(strlen($taglist[$ii])>0) {
						if($jj==0) {
							echo "<tr>\n";
							echo "	<td align=\"center\" style=\"word-break:break-all;\">\n";
							echo "	<img src=\"".$Dir."images/common/tag_icon.gif\" border=\"0\" align=\"absmiddle\" style=\"margin-right:2px;\"><a href=\"".$Dir.FrontDir."tag.php?tagname=".urlencode($taglist[$ii])."\" onmouseover=\"window.status='".$taglist[$ii]."';return true;\" onmouseout=\"window.status='';return true;\"><FONT class=\"prtag\">".$taglist[$ii]."</font></a>";
						}
						else {
							echo "<FONT class=\"prtag\">,</font>&nbsp;<a href=\"".$Dir.FrontDir."tag.php?tagname=".urlencode($taglist[$ii])."\" onmouseover=\"window.status='".$taglist[$ii]."';return true;\" onmouseout=\"window.status='';return true;\"><FONT class=\"prtag\">".$taglist[$ii]."</font></a>";
						}
						$jj++;
					}
				}
				if($jj!=0) {
					echo "	</td>\n";
					echo "</tr>\n";
				}
			}

			// ������ ������
			if( $row->vender > 0 ) {
				$classList = array();
				$classResult=mysql_query("SELECT * FROM `tblVenderClassType` ",get_db_conn());
				while($classRow=mysql_fetch_object($classResult)) {
					$classList[$classRow->idx] = $classRow->name;
				}
				$v_info = mysql_fetch_assoc ( mysql_query( "SELECT * FROM `tblvenderinfo` WHERE `vender`=".$row->vender." LIMIT 1;" ,get_db_conn()) );

				$venderNameTag = "<div style=\"float:left; width:60px;\"><img src=\"".$com_image_url.$v_info['com_image']."\" onerror=\"this.src='/images/no_img.gif';\" width=\"48\" style=\"border:1px solid #dddddd;\" /></div>";
				$venderNameTag .= "<div style=\"float:left; width:65%; font-size:11px;\">";
				$venderNameTag .= "	<span class=\"name\">".$v_info['com_name']."</span> <span class=\"owner\">(".$v_info['com_owner'].")</span><br />";
				$venderNameTag .= "	<a href=\"javascript:GoMinishop('/minishop.php?storeid=".$v_info['id']."')\"><img src=\"/images/common/icon_vender_go.gif\" border=\"0\" alt=\"��ü��ǰ����\" /></a>";
				$venderNameTag .= "</div>";

				// ������ ���
				echo "
					<tr>
						<td class=\"nameTagBox\">".$venderNameTag."</td>
					</tr>
				";
			}

			echo "</table>\n";
			echo "</td>";

			$i++;
		}
		if($i>0 && $i<4) {
			for($k=0; $k<(4-$i); $k++) {
				echo "<td width=\"10\" nowrap></td>\n<td width=\"".$tableSize."\"></td>\n";
			}
		}
		mysql_free_result($result);
?>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="20"></td>
	</tr>
	<tr>
		<td height="1" background="<?=$Dir?>images/common/brandproduct/<?=$_bdata->list_type?>/plist_skin_line3.gif"></td>
	</tr>
	<!--
	<tr>
		<td height="28" style="padding-left:10px;"><IMG SRC="<?=$Dir?>images/common/brandproduct/<?=$_bdata->list_type?>/plist_skin_text01.gif" border="0"><a href="javascript:ChangeSort('production');"><IMG SRC="<?=$Dir?>images/common/brandproduct/<?=$_bdata->list_type?>/plist_skin_nerotop<?if($sort=="production")echo"_on";?>.gif" border="0"></a><a href="javascript:ChangeSort('production_desc');"><IMG SRC="<?=$Dir?>images/common/brandproduct/<?=$_bdata->list_type?>/plist_skin_nerodow<?if($sort=="production_desc")echo"_on";?>.gif" border="0"></a><img src="../images/common/space_line.gif" width="8" height="1" border="0"><IMG SRC="<?=$Dir?>images/common/brandproduct/<?=$_bdata->list_type?>/plist_skin_text02.gif" border="0"><a href="javascript:ChangeSort('name');"><IMG SRC="<?=$Dir?>images/common/brandproduct/<?=$_bdata->list_type?>/plist_skin_nerotop<?if($sort=="name")echo"_on";?>.gif" border="0"></a><a href="javascript:ChangeSort('name_desc');"><IMG SRC="<?=$Dir?>images/common/brandproduct/<?=$_bdata->list_type?>/plist_skin_nerodow<?if($sort=="name_desc")echo"_on";?>.gif" border="0"></a><img src="../images/common/space_line.gif" width="8" height="1" border="0"><IMG SRC="<?=$Dir?>images/common/brandproduct/<?=$_bdata->list_type?>/plist_skin_text03.gif" border="0"><a href="javascript:ChangeSort('price');"><IMG SRC="<?=$Dir?>images/common/brandproduct/<?=$_bdata->list_type?>/plist_skin_nerotop<?if($sort=="price")echo"_on";?>.gif" border="0"></a><a href="javascript:ChangeSort('price_desc');"><IMG SRC="<?=$Dir?>images/common/brandproduct/<?=$_bdata->list_type?>/plist_skin_nerodow<?if($sort=="price_desc")echo"_on";?>.gif" border="0"></a><img src="../images/common/space_line.gif" width="8" height="1" border="0"><IMG SRC="<?=$Dir?>images/common/brandproduct/<?=$_bdata->list_type?>/plist_skin_text04.gif" border="0"><a href="javascript:ChangeSort('reserve');"><IMG SRC="<?=$Dir?>images/common/brandproduct/<?=$_bdata->list_type?>/plist_skin_nerotop<?if($sort=="reserve")echo"_on";?>.gif" border="0"></a><a href="javascript:ChangeSort('reserve_desc');"><IMG SRC="<?=$Dir?>images/common/brandproduct/<?=$_bdata->list_type?>/plist_skin_nerodow<?if($sort=="reserve_desc")echo"_on";?>.gif" border="0"></a></td>
	</tr>
	<tr>
		<td height="1" background="<?=$Dir?>images/common/brandproduct/<?=$_bdata->list_type?>/plist_skin_line3.gif"></td>
	</tr>
	-->
	<tr>
		<td height="20"></td>
	</tr>
<?
		$total_block = intval($pagecount / $setup[page_num]);

		if (($pagecount % $setup[page_num]) > 0) {
			$total_block = $total_block + 1;
		}

		$total_block = $total_block - 1;

		if (ceil($t_count/$setup[list_num]) > 0) {
			// ����	x�� ����ϴ� �κ�-����
			$a_first_block = "";
			if ($nowblock > 0) {
				$a_first_block .= "<a href='javascript:GoPage(0,1);' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='ù ������';return true\"><FONT class=\"prlist\">[1...]</FONT></a>&nbsp;&nbsp;";

				$prev_page_exists = true;
			}

			$a_prev_page = "";
			if ($nowblock > 0) {
				$a_prev_page .= "<a href='javascript:GoPage(".($nowblock-1).",".($setup[page_num]*($block-1)+$setup[page_num]).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup[page_num]." ������';return true\"><FONT class=\"prlist\">[prev]</FONT></a>&nbsp;&nbsp;";

				$a_prev_page = $a_first_block.$a_prev_page;
			}

			// �Ϲ� �������� ������ ǥ�úκ�-����

			if (intval($total_block) <> intval($nowblock)) {
				$print_page = "";
				for ($gopage = 1; $gopage <= $setup[page_num]; $gopage++) {
					if ((intval($nowblock*$setup[page_num]) + $gopage) == intval($gotopage)) {
						$print_page .= "<FONT class=\"choiceprlist\">".(intval($nowblock*$setup[page_num]) + $gopage)."</font> ";
					} else {
						$print_page .= "<a href='javascript:GoPage(".$nowblock.",".(intval($nowblock*$setup[page_num]) + $gopage).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='������ : ".(intval($nowblock*$setup[page_num]) + $gopage)."';return true\"><FONT class=\"prlist\">[".(intval($nowblock*$setup[page_num]) + $gopage)."]</FONT></a> ";
					}
				}
			} else {
				if (($pagecount % $setup[page_num]) == 0) {
					$lastpage = $setup[page_num];
				} else {
					$lastpage = $pagecount % $setup[page_num];
				}

				for ($gopage = 1; $gopage <= $lastpage; $gopage++) {
					if (intval($nowblock*$setup[page_num]) + $gopage == intval($gotopage)) {
						$print_page .= "<FONT class=\"choiceprlist\">".(intval($nowblock*$setup[page_num]) + $gopage)."</FONT> ";
					} else {
						$print_page .= "<a href='javascript:GoPage(".$nowblock.",".(intval($nowblock*$setup[page_num]) + $gopage).");' onMouseOver=\"window.status='������ : ".(intval($nowblock*$setup[page_num]) + $gopage)."';return true\"><FONT class=\"prlist\">[".(intval($nowblock*$setup[page_num]) + $gopage)."]</FONT></a> ";
					}
				}
			}		// ������ �������� ǥ�úκ�-��


			$a_last_block = "";
			if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
				$last_block = ceil($t_count/($setup[list_num]*$setup[page_num])) - 1;
				$last_gotopage = ceil($t_count/$setup[list_num]);

				$a_last_block .= "&nbsp;&nbsp;<a href='javascript:GoPage(".$last_block.",".$last_gotopage.");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='������ ������';return true\"><FONT class=\"prlist\">[...".$last_gotopage."]</FONT></a>";

				$next_page_exists = true;
			}

			// ���� 10�� ó���κ�...

			$a_next_page = "";
			if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
				$a_next_page .= "&nbsp;&nbsp;<a href='javascript:GoPage(".($nowblock+1).",".($setup[page_num]*($nowblock+1)+1).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup[page_num]." ������';return true\"><FONT class=\"prlist\">[next]</FONT></a>";

				$a_next_page = $a_next_page.$a_last_block;
			}
		} else {
			$print_page = "<FONT class=\"prlist\">1</FONT>";
		}
?>
	<tr>
		<td style="font-size:11px;" align="center"><?=$a_div_prev_page.$a_prev_page.$print_page.$a_next_page.$a_div_next_page?></td>
	</tr>
	<tr>
		<td height="20"></td>
	</tr>
	</table>
	</td>
</tr>
</table>