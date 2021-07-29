<?
if(substr(getenv("SCRIPT_NAME"),-14)=="/venderlib.php") {
	header("HTTP/1.0 404 Not Found");
	exit;
}

class _VenderInfo {
	var $vidx			= "";
	var $id				= "";
	var $authkey		= "";

	var $venderdata		= "";

	function _VenderInfo($_vinfo) {
		if ($_vinfo) {
			$savedata=unserialize(decrypt_md5($_vinfo));
			$this->vidx				= $savedata["vidx"];
			$this->id				= $savedata["id"];
			$this->authkey			= $savedata["authkey"];
		}
	}

	function Save() {
		$savedata["vidx"]			= $this->getVidx();
		$savedata["id"]				= $this->getId();
		$savedata["authkey"]		= $this->getAuthkey();

		$_vinfo = encrypt_md5(serialize($savedata));
		setcookie("_vinfo", $_vinfo, 0, "/".RootPath.VenderDir);
	}

	function setVidx($vidx)				{$this->vidx = $vidx;}
	function setId($id)					{$this->id = $id;}
	function setAuthkey($authkey)		{$this->authkey = $authkey;}

	function getVidx()					{return $this->vidx;}
	function getId()					{return $this->id;}
	function getAuthkey()				{return $this->authkey;}

	function getVenderdata()			{return $this->venderdata;}

	function VenderAccessCheck() {
		$sql = "SELECT a.*, b.*, c.date as sessiondate ";
		$sql.= "FROM tblvenderinfo a, tblvenderstore b, tblvendersession c ";
		$sql.= "WHERE a.vender='".$this->getVidx()."' AND a.id='".$this->getId()."' AND a.vender=b.vender ";
		$sql.= "AND a.id=b.id AND a.vender=c.vender AND c.authkey='".$this->getAuthkey()."' AND a.delflag='N' ";
		$result=mysql_query($sql,get_db_conn());
		if($row=mysql_fetch_object($result)) {
			$sessiondate=$row->sessiondate;
			$sessiontime=mktime((int)substr($sessiondate,8,2),(int)substr($sessiondate,10,2),0,(int)substr($sessiondate,4,2),(int)substr($sessiondate,6,2)+1,(int)substr($sessiondate,0,4));

			if($sessiontime<time()) {
				echo "<script>\n";
				echo "	alert(\"세션 시간이 만료되었습니다.\\n\\n다시 로그인 하시기 바랍니다.\");\n";
				echo "	if (opener) {\n";
				echo "		opener.parent.location.href=\"logout.php\";\n";
				echo "		window.close();\n";
				echo "	} else {\n";
				echo "		parent.location.href=\"logout.php\";\n";
				echo "	}\n";
				echo "</script>\n";
				exit;
			}
			$this->venderdata=$row;
		} else {
			echo "<script>\n";
			echo "	alert(\"정상적인 경로로 다시 접속하시기 바랍니다..\");\n";
			echo "	if (opener) {\n";
			echo "		opener.parent.location.href=\"logout.php\";\n";
			echo "		window.close();\n";
			echo "	} else {\n";
			echo "		parent.location.href=\"logout.php\";\n";
			echo "	}\n";
			echo "</script>\n";
			exit;
		}
		mysql_free_result($result);
	}

	function ShopVenderLog($vidx,$ip,$content,$date="") {
		if (strlen($date)!=14) {
			$date=date("YmdHis");
		}
		$sql = "INSERT tblvenderlog SET ";
		$sql.= "vender		= '".$vidx."', ";
		$sql.= "date		= '".$date."', ";
		$sql.= "ip			= '".$ip."', ";
		$sql.= "content		= '".$content."' ";
		//mysql_query($sql,get_db_conn());
	}
}

class _MiniLib {
	var $vender		= "";
	var $MiniData	= "";
	var $isVender	= false;

	var $prdataA	= array();
	var $prdataB	= array();
	var $prdataC	= array();
	var $prdataD	= array();
	var $codecnt	= array();
	var $codename	= array();
	var $themeprdataA	= array();
	var $themeprdataB	= array();
	var $themecodecnt	= array();
	var $themecodename	= array();

	var $code_locname	= "";

	var $sch_codeA=array();
	var $sch_codeB=array();
	var $sch_codeC=array();
	var $sch_codeD=array();
	var $sch_prcnt=0;

	function _MiniLib($vender) {
		$this->vender=$vender;
	}

	function _MiniInit() {
		if(strlen($this->vender)>0) {
			if($this->getMinishop($row)) {
				$this->isVender=true;
				$this->MiniData=$row;
				if($this->MiniData->shop_width<=0) $this->MiniData->shop_width=900;
				if(strlen($this->MiniData->code_distype)==0) $this->MiniData->code_distype="YY";

				$arrskin=explode(",",$row->skin);
				$top_imgseq=(int)$arrskin[0];
				$top_colorseq=(int)$arrskin[1];
				$menu_colorseq=(int)$arrskin[2];

				$title_backimg=$this->getTitleskin($top_imgseq);
				$title_color=$this->getMenucolor($top_colorseq);
				if($top_imgseq==0) {
					$this->MiniData->top_backimg=DirPath.DataDir."shopimages/vender/top_".$this->vender.".gif";
				} else {
					$this->MiniData->top_backimg=DirPath."images/minishop/title_skin/".$title_color->color."_".$title_backimg.".gif";
				}
				$this->MiniData->top_fontcolor=$title_color->fontcolor;

				$menu_color=$this->getMenucolor($menu_colorseq);
				$this->MiniData->color=$menu_color->color;
				$this->MiniData->leftcolor=$menu_color->leftcolor;
				$this->MiniData->fontcolor=$menu_color->fontcolor;

				if(file_exists(DirPath.DataDir."shopimages/vender/logo_".$this->vender.".gif")) {
					$this->MiniData->logo=DirPath.DataDir."shopimages/vender/logo_".$this->vender.".gif";
				} else {
					$this->MiniData->logo=DirPath."images/minishop/logo.gif";
				}

				$this->setCustinfo();
			}
		}
	}

	function getMinishop(&$row) {
		$sql = "SELECT * FROM tblvenderstore a, tblvenderstorecount b ";
		$sql.= "WHERE a.vender='".$this->vender."' AND a.vender=b.vender ";
		$result=mysql_query($sql,get_db_conn());
		if($row=mysql_fetch_object($result)) {
			$res=true;
		} else {
			$res=false;
		}
		mysql_free_result($result);
		return $res;
	}

	function getTitleskin($seq) {
		$sql = "SELECT backimg FROM tblvendertitleskin WHERE seq='".$seq."' ";
		$result=mysql_query($sql,get_db_conn());
		$row=mysql_fetch_object($result);
		mysql_free_result($result);
		return $row->backimg;
	}

	function getMenucolor($seq) {
		$sql = "SELECT * FROM tblvenderboxgroupcolor WHERE seq='".$seq."' ";
		$result=mysql_query($sql,get_db_conn());
		$row=mysql_fetch_object($result);
		mysql_free_result($result);
		return $row;
	}

	function getCode($tgbn="",$code="") {
		GLOBAL $_ShopInfo;
		if(substr($this->MiniData->code_distype,0,1)!="Y") return;
		
		if($code != '000000'){
			$codeAsel=substr($code,0,3);
			$codeBsel=substr($code,3,3);
			$codeCsel=substr($code,6,3);
			$codeDsel=substr($code,9,3);

			if($codeBsel == "000"){
				$addcode = $codeAsel;
				$ni = 1;
			}elseif($codeCsel == "000"){
				$addcode = $codeAsel.$codeBsel;
				$ni = 2;
			}elseif($codeDsel == "000"){
				$addcode = $codeAsel.$codeBsel.$codeCsel;
				$ni = 3;
			}else{
				$addcode = $codeAsel.$codeBsel.$codeCsel;
				$ni = 3;
			}

			$sql = "SELECT SUBSTRING(a.productcode,1,12) as prcode, COUNT(*) as prcnt ";
			$sql.= "FROM tblproduct AS a ";
			$sql.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
			$sql.= "LEFT OUTER JOIN rent_product rp ON a.pridx=rp.pridx ";
			$sql.= "WHERE a.productcode like '".$addcode."%' and (a.vender='".$this->vender."' and (rp.trust_vender is NULL or rp.trust_vender='0')) ";
			$sql.= "OR (rp.trust_vender='".$this->vender."' AND rp.trust_vender<>a.vender AND rp.trust_approve='Y') ";
			$sql.= "AND a.display='Y' ";
			$sql.= "AND (a.group_check='N' OR b.group_code='".$_ShopInfo->getMemgroup()."') ";
			$sql.= "GROUP BY prcode ";
			$result=mysql_query($sql,get_db_conn());
			$codecnt=array();
			unset($codes);
			$ii=0;
			while($row=mysql_fetch_object($result)) {
				$codes["A"][$ii]=substr($row->prcode,0,3);
				$codes["B"][$ii]=substr($row->prcode,3,3);
				$codes["C"][$ii]=substr($row->prcode,6,3);
				$codes["D"][$ii]=substr($row->prcode,9,3);
				
				$codecnt[substr($row->prcode,0,3*$ni)]+=$row->prcnt;
				$codecnt[substr($row->prcode,0,3*($ni+1))]+=$row->prcnt;


				$codecnt["000"]+=$row->prcnt;
				$ii++;
			}
			mysql_free_result($result);
			$this->codecnt=$codecnt;

			$prdataA=array();
			$prdataB=array();
			$codename=array();


				$sql = "SELECT codeA,codeB,codeC,codeD,code_name FROM tblproductcode ";
				$sql.= "WHERE ( ";
				
				if($codeBsel == "000"){
					$sql.= " codeA='".$codeAsel."' and codeC ='000' and codeD ='000' ";
					$sql .= " and codeB in ('".implode("','",$codes["B"])."') ";
				}elseif($codeCsel == "000"){
					$sql.= " codeA='".$codeAsel."' and codeB='".$codeBsel."' and codeD ='000' ";
					$sql .= " and codeC in ('".implode("','",$codes["C"])."') ";
				}elseif($codeDsel == "000"){
					$sql.= " codeA='".$codeAsel."' and codeB='".$codeBsel."' and codeC='".$codeCsel."' ";
					$sql .= " and codeD in ('".implode("','",$codes["D"])."') ";
				}else{
					$sql.= " codeA='".$codeAsel."' and codeB='".$codeBsel."' and codeC='".$codeCsel."' ";
					$sql .= " and codeD in ('".implode("','",$codes["D"])."') ";
				}
				$sql.= ") ";
				
				$sql.= "AND group_code!='NO' AND (type LIKE 'L%') ";
				$sql.= "ORDER BY codeA,codeB,codeC,codeD ASC ";
				$result=mysql_query($sql,get_db_conn());
				while($row=mysql_fetch_object($result)) {

					$prdataA[]=$row;

					if($ni == "1"){
						$prdataB[$row->codeA][]=$row;
					}else if($ni == "2"){
						$prdataB[$row->codeA.$row->codeB][]=$row;
					}else if($ni == "3"){
						$prdataB[$row->codeA.$row->codeB.$row->codeC][]=$row;
					}else{
						$prdataB[$row->codeA.$row->codeB.$row->codeC][]=$row;
					}
				}

				mysql_free_result($result);
				$this->prdataA=$prdataA;
				$this->prdataB=$prdataB;


				

			$sql = "SELECT codeA,codeB,codeC,codeD,code_name FROM tblproductcode ";
			$sql.= "WHERE ( codeA='".$codeAsel."' ";
			$sql.= ") ";
	
				$sql.= "AND group_code!='NO' AND (type LIKE 'L%') ";
				$sql.= "ORDER BY codeA,codeB,codeC,codeD ASC ";
				$result=mysql_query($sql,get_db_conn());
				while($row=mysql_fetch_object($result)) {
					$tmpcode3=$row->codeA.$row->codeB.$row->codeC.$row->codeD;
					$codename[$tmpcode3]=$row->code_name;
				}
				mysql_free_result($result);
				$this->codename=$codename;
			//}
		}else{
				$sql = "SELECT SUBSTRING(a.productcode,1,12) as prcode, COUNT(*) as prcnt ";
				$sql.= "FROM tblproduct AS a ";
				$sql.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
				$sql.= "LEFT OUTER JOIN rent_product rp ON a.pridx=rp.pridx ";
				$sql.= "WHERE (a.vender='".$this->vender."' and (rp.trust_vender is NULL or rp.trust_vender='0')) ";
				$sql.= "OR (rp.trust_vender='".$this->vender."' AND rp.trust_vender<>a.vender AND rp.trust_approve='Y') ";
				$sql.= "AND a.display='Y' ";
				$sql.= "AND (a.group_check='N' OR b.group_code='".$_ShopInfo->getMemgroup()."') ";
				$sql.= "GROUP BY prcode ";
				$result=mysql_query($sql,get_db_conn());
				$codecnt=array();
				unset($codes);
				$ii=0;
				while($row=mysql_fetch_object($result)) {
					$codes[$ii]["A"]=substr($row->prcode,0,3);
					$codes[$ii]["B"]=substr($row->prcode,3,3);
					$codecnt[substr($row->prcode,0,3)]+=$row->prcnt;
					$codecnt[substr($row->prcode,0,6)]+=$row->prcnt;
					$codecnt[$row->prcode]+=$row->prcnt;
					$codecnt["000"]+=$row->prcnt;
					$ii++;
				}
				mysql_free_result($result);
				$this->codecnt=$codecnt;

				$prdataA=array();
				$prdataB=array();
				$prdataC=array();
				$prdataD=array();
				$codename=array();

				if(count($codes)>0) {
					$sql = "SELECT codeA,codeB,codeC,codeD,code_name FROM tblproductcode ";
					$sql.= "WHERE (";
					for($i=0;$i<count($codes);$i++) {
						if($i>0) $sql.= " OR ";
						$sql.= "(codeA='".$codes[$i]["A"]."' AND (codeB='000' OR codeB='".$codes[$i]["B"]."')) ";
					}
					$sql.= ") ";
					$a = substr($code,0,3);
					$b = substr($code,3,3);
					$c = substr($code,6,3);
					$d = substr($code,9,3);


					
					if($a!="000") {
						$sql.= "AND codeA='".$a."' ";
						if($b!="000"){
							$sql.="AND codeB='".$b."' ";
								if($c!="000"){
									$sql.="AND codeC='".$c."' ";
										if($d!="000"){
											$sql.="AND codeD='".$d."' ";
										}else{
											$sql.= "  ";
										}
								}else{
									$sql.= " AND codeD='000' ";
								}
						}else{
							$sql.= " AND codeC='000' AND codeD='000' ";
						}
					}else{
						$sql.= "AND codeC='000' AND codeD='000' ";
					}



					$sql.= "AND group_code!='NO' AND (type LIKE 'L%') ";
					$sql.= "ORDER BY codeA,codeB,codeC,codeD ASC ";
					$result=mysql_query($sql,get_db_conn());
					while($row=mysql_fetch_object($result)) {
						$tmpcode3=$row->codeA.$row->codeB.$row->codeC.$row->codeD;
						$codename[$tmpcode3]=$row->code_name;
						
						if($a!="000" && $b!="000" && $row->codeC=="000" && $row->codeB!="000" && $row->codeA!="000") {
							$prdataA[]=$row;
							if($tgbn=="10") {
								if(substr($code,0,6)==$row->codeA.$row->codeB) {
									$this->code_locname=$row->code_name;
								}
							}
						}else{
							if($row->codeB=="000") {
								$prdataA[]=$row;
								if($tgbn=="10") {
									if(substr($code,0,3)==$row->codeA) {
										$this->code_locname=$row->code_name;
									}
								}
							} else {
								$prdataB[$row->codeA][]=$row;
							}
						}

						
					}
					mysql_free_result($result);
					$this->prdataA=$prdataA;
					$this->prdataB=$prdataB;
					$this->codename=$codename;
				}
		}
	}

	function getThemecode($tgbn="",$code="") {
		GLOBAL $_ShopInfo;
		if(substr($this->MiniData->code_distype,-1)!="Y") return;

		$sql = "SELECT a.themecode, COUNT(*) as prcnt ";
		$sql.= "FROM tblvenderthemeproduct a, tblproduct b ";
		$sql.= "LEFT OUTER JOIN tblproductgroupcode c ON b.productcode=c.productcode ";
		$sql.= "LEFT OUTER JOIN rent_product rp ON b.pridx=rp.pridx ";
		$sql.= "WHERE (a.vender='".$this->vender."' and (rp.trust_vender is NULL or rp.trust_vender='0')) ";
		$sql.= "OR (rp.trust_vender='".$this->vender."' AND rp.trust_vender<>a.vender AND rp.trust_approve='Y') ";
		//$sql.= "WHERE a.vender='".$this->vender."' ";
		$sql.= "AND a.vender=b.vender AND a.productcode=b.productcode ";
		$sql.= "AND b.display='Y' ";
		$sql.= "AND (b.group_check='N' OR c.group_code='".$_ShopInfo->getMemgroup()."') ";
		$sql.= "GROUP BY a.themecode ";
		$result=mysql_query($sql,get_db_conn());
		
		unset($codecnt);
		unset($codes);
		$ii=0;
		while($row=mysql_fetch_object($result)) {
			$codes[$ii]["A"]=substr($row->themecode,0,3);
			$codes[$ii]["B"]=substr($row->themecode,3,3);
			$codecnt[substr($row->themecode,0,3)]+=$row->prcnt;
			$codecnt[$row->themecode]+=$row->prcnt;
			$ii++;
		}
		mysql_free_result($result);
		$this->themecodecnt=$codecnt;

		$themeprdataA=array();
		$themeprdataB=array();
		$themecodename=array();
		if(count($codes)>0) {
			$sql = "SELECT codeA,codeB,code_name FROM tblvenderthemecode ";
			$sql.= "WHERE vender='".$this->vender."' AND ( ";
			for($i=0;$i<count($codes);$i++) {
				if($i>0) $sql.= " OR ";
				$sql.= "(codeA='".$codes[$i]["A"]."' AND (codeB='000' OR codeB='".$codes[$i]["B"]."')) ";
			}
			$sql.= ") ";
			$sql.= "ORDER BY sequence DESC ";
			$result=mysql_query($sql,get_db_conn());
			while($row=mysql_fetch_object($result)) {
				$tmpcode3=$row->codeA.$row->codeB;
				$themecodename[$tmpcode3]=$row->code_name;
				if($row->codeB=="000") {
					$themeprdataA[]=$row;
					if($tgbn=="20") {
						if(substr($code,0,3)==$row->codeA) {
							$this->code_locname=$row->code_name;
						}
					}
				} else {
					$themeprdataB[$row->codeA][]=$row;
				}
			}
			mysql_free_result($result);
			$this->themeprdataA=$themeprdataA;
			$this->themeprdataB=$themeprdataB;
			$this->themecodename=$themecodename;
		}
	}

	function getSearchcode($likecode,$qry) {
		GLOBAL $_ShopInfo;
		$sql = "SELECT SUBSTRING(a.productcode,1,12) as prcode, COUNT(*) as prcnt ";
		$sql.= "FROM tblproduct AS a ";
		$sql.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
		$sql.= $qry." ";
		$sql.= "AND (a.group_check='N' OR b.group_code='".$_ShopInfo->getMemgroup()."') ";
		$sql.= "GROUP BY prcode ";
		$result=mysql_query($sql,get_db_conn());
		$code_prcnt=0;
		$codeA=array();
		$codeB=array();
		$codeC=array();
		$codeD=array();
		while($row=mysql_fetch_object($result)) {
			$c1=substr($row->prcode,0,3);
			$c2=substr($row->prcode,3,3);
			$c3=substr($row->prcode,6,3);
			$c4=substr($row->prcode,9,3);
			$code_prcnt+=$row->prcnt;
			$codeA[$c1]["cnt"]+=$row->prcnt;
			if($c2!="000") {
				$codeB[$c1][$c2]["cnt"]+=$row->prcnt;
				if($c3!="000") {
					$codeC[$c1][$c2][$c3]["cnt"]+=$row->prcnt;
					if($c4!="000") {
						$codeD[$c1][$c2][$c3][$c4]["cnt"]+=$row->prcnt;
					}
				}
			}
		}
		mysql_free_result($result);

		if($code_prcnt>0) {
			$sql = "SELECT codeA, codeB, codeC, codeD, code_name FROM tblproductcode ";
			$sql.= "WHERE codeA IN (";
			$i=0;
			while(list($key,$val)=each($codeA)) {
				if($i>0) $sql.= ",";
				$sql.= "'".$key."'";
				$i++;
			}
			$sql.= ") ";
			$result=mysql_query($sql,get_db_conn());
			while($row=mysql_fetch_object($result)) {
				$c1=$row->codeA;
				$c2=$row->codeB;
				$c3=$row->codeC;
				$c4=$row->codeD;
				if($c2=="000" && $c3=="000" && $c4=="000") {
					$codeA[$c1]["name"]=$row->code_name;
				} else if($c3=="000" && $c4=="000") {
					if(is_array($codeB[$c1][$c2])==true) {
						$codeB[$c1][$c2]["name"]=$row->code_name;
					}
				} else if($c4=="000") {
					if(is_array($codeC[$c1][$c2][$c3])==true) {
						$codeC[$c1][$c2][$c3]["name"]=$row->code_name;
					}
				} else {
					if(is_array($codeD[$c1][$c2][$c3][$c4])==true) {
						$codeD[$c1][$c2][$c3][$c4]["name"]=$row->code_name;
					}
				}
			}
			mysql_free_result($result);
		}
		$this->sch_codeA=$codeA;
		$this->sch_codeB=$codeB;
		$this->sch_codeC=$codeC;
		$this->sch_codeD=$codeD;
		$this->sch_prcnt=$code_prcnt;
	}

	function setCustinfo() {
		$cust_data=array();
		$temp=explode("=",$this->MiniData->cust_info);
		for ($i=0;$i<count($temp);$i++) {
			if (substr($temp[$i],0,4)=="TEL=")			$cust_data["TEL"]=substr($temp[$i],4);
			else if (substr($temp[$i],0,4)=="FAX=")		$cust_data["FAX"]=substr($temp[$i],4);
			else if (substr($temp[$i],0,6)=="EMAIL=")	$cust_data["EMAIL"]=substr($temp[$i],6);
			else if (substr($temp[$i],0,6)=="TIME1=")	$cust_data["TIME1"]=substr($temp[$i],6);
			else if (substr($temp[$i],0,6)=="TIME2=")	$cust_data["TIME2"]=substr($temp[$i],6);
			else if (substr($temp[$i],0,6)=="TIME3=")	$cust_data["TIME3"]=substr($temp[$i],6);
		}
		if($cust_data["TIME1"]=="0") $cust_data["TIME1"]="휴무";
		if($cust_data["TIME2"]=="0") $cust_data["TIME2"]="휴무";
		if($cust_data["TIME3"]=="0") $cust_data["TIME3"]="휴무";
		$this->MiniData->custdata=$cust_data;
	}

	function getMiniData()	{return $this->MiniData;}
}
?>