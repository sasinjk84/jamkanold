<?	
// 최근 본 상품
####################### 추천상품 ######################
$recentviewitems = '';
$viewproduct=$_COOKIE["ViewProduct"];
$viewproduct = explode(',',$viewproduct);
for($v = count($viewproduct)-1;$v >=0;$v--){
	if(!preg_match('/^[0-9]{18}$/',$viewproduct[$v])) unset($viewproduct[$v]);		
}

if(_array($viewproduct)){		
	$sql = productQuery ();
	$sql.= "WHERE a.productcode IN ('".implode("','",$viewproduct)."') AND a.display='Y' ";
	$sql.= "AND (a.group_check='N' OR b.group_code='".$_ShopInfo->getMemgroup()."') ";
	$sql.=" and  (a.rental != '2' || rp.istrust != '-1') "; // 렌탈 위탁 대기 상품 제외
	$sql.= "ORDER BY FIELD(a.productcode,'".implode("','",$viewproduct)."') ";
	$sql.= "LIMIT 5";

	$result=mysql_query($sql,get_db_conn());
	$recentCnt =  mysql_num_rows($result);
	$i=0;
		
	$tmptxt = file_get_contents($Dir.'newUI/mainResent.html');
	$contTemp= array();
	$pos = strlen($tmptxt);
	if(false !== $pos = strpos($tmptxt,'<!-- items -->')){			
		if(false === $epos = strpos($tmptxt,'<!-- /items -->')) $epos = strlen($tmptxt);			
		$contTemp['items'] = substr($tmptxt,$pos+strlen('<!-- items -->'),$epos-$pos-strlen('<!-- items -->'));
	}
	
	
	$contTemp['head'] = substr($tmptxt,0,$pos);
	$contTemp['bott'] = substr($tmptxt,$epos);
	
	$contTemp['cont'] = '';
	
	$contTemp = str_replace('__ID__','mainResent',$contTemp);
	$i=0;
	while(!_empty($contTemp['items']) && $row=mysql_fetch_assoc($result)){
		$i++;
		$itemtxt = $contTemp['items'];
		$row['listfinal'] = ($i%6==0)?'endItem':'';
		$row = solvResultforNewUi($row);
		foreach($row as $k=>$v){
			$itemtxt = str_replace('product.'.$k,$v,$itemtxt);
		}
		$contTemp['cont'] .= $itemtxt;				
	}
	$recentviewitems = $contTemp['head'].$contTemp['cont'].$contTemp['bott'];
}

if($recentCnt>0){
	array_push($pattern,'(\[MAIN_RECENT\])');
	array_push($replace,'<div class="mainResent" style="display:none">'.$recentviewitems.'</div>');
}else{
	array_push($pattern,'(\[MAIN_RECENT\])');
	array_push($replace,'');
}



// 위시 리스트
$mywish = '';
if(!_empty($_ShopInfo->getMemid())){
	$sql = "select p.* from tblproduct p left join rent_product rp on rp.pridx=p.pridx  left join tblwishlist w on p.productcode=w.productcode where p.display='Y' and (p.rental != '2' or rp.istrust!='-1') and w.id='".$_ShopInfo->getMemid()."' order by w.date desc LIMIT 5";
	$result=mysql_query($sql,get_db_conn());
	$i=0;
		
	$tmptxt = file_get_contents($Dir.'newUI/mainResent.html');
	$contTemp= array();
	$pos = strlen($tmptxt);
	if(false !== $pos = strpos($tmptxt,'<!-- items -->')){			
		if(false === $epos = strpos($tmptxt,'<!-- /items -->')) $epos = strlen($tmptxt);			
		$contTemp['items'] = substr($tmptxt,$pos+strlen('<!-- items -->'),$epos-$pos-strlen('<!-- items -->'));
	}
	
	
	$contTemp['head'] = substr($tmptxt,0,$pos);
	$contTemp['bott'] = substr($tmptxt,$epos);
	
	$contTemp['cont'] = '';
	
	$contTemp = str_replace('__ID__','mainResent',$contTemp);
	$i=0;
	while(!_empty($contTemp['items']) && $row=mysql_fetch_assoc($result)){
		$i++;
		$itemtxt = $contTemp['items'];
		
		$row['listfinal'] = ($i%6==0)?'endItem':'';
		$row = solvResultforNewUi($row);		
		foreach($row as $k=>$v){
			$itemtxt = str_replace('product.'.$k,$v,$itemtxt);
		}
		$contTemp['cont'] .= $itemtxt;				
	}
	$mywish = $contTemp['head'].$contTemp['cont'].$contTemp['bott'];
}

if($wishCnt>0){
	array_push($pattern,'(\[MAIN_WISH\])');
	array_push($replace,'<div class="mainResent" style="display:none">'.$mywish.'</div>');
}else{
	array_push($pattern,'(\[MAIN_WISH\])');
	array_push($replace,'');
}



// newarrval 추가 UI 관련 
$subcategoryitems = array();
if(preg_match_all('/\[SUBCATEGORY_([0-9]{3,})_LOOPST\](.+)\[SUBCATEGORY_\\1_LOOPED\]/sU',$main_body,$subcategoryitems)) array_shift($subcategoryitems);
if(count($subcategoryitems)){
	foreach($subcategoryitems[0] as $idx=>$scode){
		$stitems = getCategoryItems($scode,true);
		$subcategoryitems[2][$idx] = array();
		$rkeys = array();
		foreach($stitems['items'] as $stitem){
			$rvals = array();
			if(!_array($rkeys)){
				foreach($stitem as $rkey=>$rval){
					array_push($rkeys,'{.'.$rkey.'}');		
					array_push($rvals,$rval);		
				}
			}else{
				$rvals = array_values($stitem);
			}
			$txt = $subcategoryitems[1][$idx];

			array_push($subcategoryitems[2][$idx],str_replace($rkeys,$rvals,$txt));
		}
		$subcategoryitems[2][$idx] = implode("\r\n",$subcategoryitems[2][$idx]);
	}
	
	foreach($subcategoryitems[0] as $idx=>$scode){
		$ststr = '[SUBCATEGORY_'.$scode.'_LOOPST]';
		$edstr = '[SUBCATEGORY_'.$scode.'_LOOPED]';
		
		if(false !== $pos = strpos($main_body,$ststr)){
			if(false !== $epos = strpos($main_body,$edstr)){
				$main_body=substr_replace($main_body,$subcategoryitems[2][$idx],$pos,$epos-$pos+strlen($edstr));
			}
		}		
	}
}

$subcategoryinfo = array();
if(preg_match_all('/\[SUBCATEGORY_ITEM_([0-9]{3,})_LOOPST\](.+)\[SUBCATEGORY_ITEM_\\1_LOOPED\]/sU',$main_body,$subcategoryitems)) array_shift($subcategoryitems);
if(count($subcategoryitems)){
	foreach($subcategoryitems[0] as $idx=>$scode){				
		$subcategoryitems[2][$idx] = array();
		$rkeys = array();
		if(!isset($subcategoryinfo[$scode])){
			$tmp= getCategoryItems($scode,false);	
			$subcategoryinfo[$scode] = $tmp['items'][0];
		}
		if(isset($subcategoryinfo[$scode])){
			$txt = $subcategoryitems[1][$idx];
			$rvals = array();
			if(!_array($rkeys)){
				foreach($stitem as $rkey=>$rval){
					array_push($rkeys,'{.'.$rkey.'}');		
					array_push($rvals,$rval);		
				}
			}else{
				$rvals = array_values($stitem);
			}				
			$txt = str_replace($rkeys,$rvals,$txt);
			
			
			if(preg_match_all('/(\[UI([a-zA-Z0-9]+)_([0-9]*)\])/sU',$txt,$mat)){
				$tmptxt = '';				
				
				if(file_exists($Dir.'newUI/'.$mat[2][0].'.html') && is_file($Dir.'newUI/'.$mat[2][0].'.html')){					
					$tmptxt = file_get_contents($Dir.'newUI/'.$mat[2][0].'.html');					
					$inhtml= array();
					$conts= array();
					$pos = strlen($tmptxt);
					if(false !== $pos = strpos($tmptxt,'<!-- items -->')){
						if(false === $epos = strpos($tmptxt,'<!-- /items -->')) $epos = strlen($tmptxt);			
						$conts['items'] = substr($tmptxt,$pos+strlen('<!-- items -->'),$epos-$pos-strlen('<!-- items -->'));
					}			
				
					$conts['head'] = substr($tmptxt,0,$pos);
					$conts['bott'] = substr($tmptxt,$epos);
					
					$tmp= getCategoryItems($scode,true);	
					foreach($tmp['items'] as $sidx=>$subitem){
						$conts['cont'] = '';
						
						$cont_temp = str_replace('__ID__','subCItems'.$idx.'_'.$sidx,$conts);
						$i=0;
						
					/*	$sql = "select p.* from tblproduct p left join tblwishlist w on p.productcode=w.productcode where p.display='Y' and w.id='".$_ShopInfo->getMemid()."' order by w.date desc LIMIT 5";		
		$result=mysql_query($sql,get_db_conn());*/				
						$result = _getSpecialProducts($subitem['linkcode'],0,$mat[3][0],'new_desc','resource');								
						while(!_empty($conts['items']) && $row=mysql_fetch_assoc($result)){
							$i++;
							$itemtxt = $conts['items'];					
							$row = solvResultforNewUi($row);
	
							if(_isInt($mat[3][0])) $row['listfinal'] = ($i%intval($mat[3][0])==0)?'endItem':'';
							foreach($row as $k=>$v){
								$itemtxt = str_replace('product.'.$k,$v,$itemtxt);
							}
							$cont_temp['cont'] .= $itemtxt;						
						}
						array_push($inhtml,$cont_temp['head'].$cont_temp['cont'].$cont_temp['bott']);	
					}
					
				}								
				$txt = str_replace($mat[1][0],implode("\r\n",$inhtml),$txt);
			}
			$subcategoryitems[2][$idx] = $txt;
		}
	}
	
	foreach($subcategoryitems[0] as $idx=>$scode){
		$ststr = '[SUBCATEGORY_ITEM_'.$scode.'_LOOPST]';
		$edstr = '[SUBCATEGORY_ITEM_'.$scode.'_LOOPED]';
		
		if(false !== $pos = strpos($main_body,$ststr)){
			if(false !== $epos = strpos($main_body,$edstr)){
				$main_body=substr_replace($main_body,$subcategoryitems[2][$idx],$pos,$epos-$pos+strlen($edstr));
			}
		}		
	}
}

//best seller
$subcategoryinfo = array();
if(preg_match_all('/\[SUBCATEGORYBEST_ITEM_([0-9]{3,})_LOOPST\](.+)\[SUBCATEGORYBEST_ITEM_\\1_LOOPED\]/sU',$main_body,$subcategoryitems)) array_shift($subcategoryitems);
if(count($subcategoryitems)){
	foreach($subcategoryitems[0] as $idx=>$scode){				
		$subcategoryitems[2][$idx] = array();
		$rkeys = array();
		if(!isset($subcategoryinfo[$scode])){
			$tmp= getCategoryItems($scode,false);	
			$subcategoryinfo[$scode] = $tmp['items'][0];
		}
		if(isset($subcategoryinfo[$scode])){
			$txt = $subcategoryitems[1][$idx];
			$rvals = array();
			if(!_array($rkeys)){
				foreach($stitem as $rkey=>$rval){
					array_push($rkeys,'{.'.$rkey.'}');		
					array_push($rvals,$rval);		
				}
			}else{
				$rvals = array_values($stitem);
			}				
			$txt = str_replace($rkeys,$rvals,$txt);
			
			
			if(preg_match_all('/(\[UI([a-zA-Z0-9]+)_([0-9]*)\])/sU',$txt,$mat)){
				$tmptxt = '';				
				
				if(file_exists($Dir.'newUI/'.$mat[2][0].'.html') && is_file($Dir.'newUI/'.$mat[2][0].'.html')){					
					$tmptxt = file_get_contents($Dir.'newUI/'.$mat[2][0].'.html');					
					$inhtml= array();
					$conts= array();
					$pos = strlen($tmptxt);
					if(false !== $pos = strpos($tmptxt,'<!-- items -->')){
						if(false === $epos = strpos($tmptxt,'<!-- /items -->')) $epos = strlen($tmptxt);			
						$conts['items'] = substr($tmptxt,$pos+strlen('<!-- items -->'),$epos-$pos-strlen('<!-- items -->'));
					}			
				
					$conts['head'] = substr($tmptxt,0,$pos);
					$conts['bott'] = substr($tmptxt,$epos);
					
					$tmp= getCategoryItems($scode,true);	
					foreach($tmp['items'] as $sidx=>$subitem){
						$conts['cont'] = '';
						
						$cont_temp = str_replace('__ID__','bestSeller'.$idx.'_'.$sidx,$conts);
						$i=0;
						
					/*	$sql = "select p.* from tblproduct p left join tblwishlist w on p.productcode=w.productcode where p.display='Y' and w.id='".$_ShopInfo->getMemid()."' order by w.date desc LIMIT 5";		
		$result=mysql_query($sql,get_db_conn());*/				
						$result = _getSpecialProducts($subitem['linkcode'],0,$mat[3][0],'best_desc','resource');								
						while(!_empty($conts['items']) && $row=mysql_fetch_assoc($result)){
							$i++;
							$itemtxt = $conts['items'];					
							$row = solvResultforNewUi($row);
	
							if(_isInt($mat[3][0])) $row['listfinal'] = ($i%intval($mat[3][0])==0)?'endItem':'';
							foreach($row as $k=>$v){
								$itemtxt = str_replace('product.'.$k,$v,$itemtxt);
							}
							$cont_temp['cont'] .= $itemtxt;						
						}
						array_push($inhtml,$cont_temp['head'].$cont_temp['cont'].$cont_temp['bott']);	
					}
					
				}								
				$txt = str_replace($mat[1][0],implode("\r\n",$inhtml),$txt);
			}
			$subcategoryitems[2][$idx] = $txt;
		}
	}
	
	foreach($subcategoryitems[0] as $idx=>$scode){
		$ststr = '[SUBCATEGORYBEST_ITEM_'.$scode.'_LOOPST]';
		$edstr = '[SUBCATEGORYBEST_ITEM_'.$scode.'_LOOPED]';
		
		if(false !== $pos = strpos($main_body,$ststr)){
			if(false !== $epos = strpos($main_body,$edstr)){
				$main_body=substr_replace($main_body,$subcategoryitems[2][$idx],$pos,$epos-$pos+strlen($edstr));
			}
		}		
	}
}


// 기타
$subcategoryinfo = array();
if(preg_match('/\[SUBCATEGORY_ITEM_(![0-9]{3,})_LOOPST\](.+)\[SUBCATEGORY_ITEM_\\1_LOOPED\]/sU',$main_body,$subcategoryitems)) array_shift($subcategoryitems);
if(count($subcategoryitems)){
	$scode = substr($subcategoryitems[0],1);
	$conthtml = $subcategoryitems[1];
	$conts= array();

	if(preg_match('/(\[UI([a-zA-Z0-9]+)_([0-9]*)\])/sU',$conthtml,$mat)){
		$reppattern = $mat[1];
		$filename = $mat[2];
		$perlist = $mat[3];
		if(file_exists($Dir.'newUI/'.$filename.'.html') && is_file($Dir.'newUI/'.$filename.'.html')){
			$innerpub = file_get_contents($Dir.'newUI/'.$filename.'.html');	
			
			$pos = strlen($innerpub);
			if(false !== $pos = strpos($innerpub,'<!-- items -->')){
				if(false === $epos = strpos($innerpub,'<!-- /items -->')) $epos = strlen($innerpub);
				$conts['items'] = substr($innerpub,$pos+strlen('<!-- items -->'),$epos-$pos-strlen('<!-- items -->'));
			}
			$conts['head'] = substr($innerpub,0,$pos);
			$conts['bott'] = substr($innerpub,$epos);			
		}
	}
	
	$pubhtml = array();
	if(!_empty($conthtml)){			
		$sql = "select * from tblproductcode where codeA!='".$scode."' and codeB ='000' and type like 'L%' order by sequence desc";	
			
		if(false !== $res = mysql_query($sql,get_db_conn())){
			if(mysql_num_rows($res)){
				$id = 'OtherSubCategory';
				$osci = 0;
				while($crow = mysql_fetch_assoc($res)){					
					$rkeys = $rvals = array();
					foreach($crow as $rkey=>$rval){
						array_push($rkeys,'{.'.$rkey.'}');		
						array_push($rvals,$rval);		
					}
				
					$pubhtml[$crow['codeA']] = str_replace($rkeys,$rvals,$conthtml);	
				}
				
				
				foreach($pubhtml as $tscode=>$pubtemp){
					$cont_temp = str_replace('__ID__',$id.'_'.$osci++,$conts);				
					$result = _getSpecialProducts($tscode,0,$perlist,'new_desc','resource');
					//$result = _getSpecialProducts('003',0,$perlist,'new','resource');
					if(mysql_num_rows($result)){
						while(!_empty($conts['items']) && $row=mysql_fetch_assoc($result)){					
							$itemtxt = $conts['items'];	
							$row = solvResultforNewUi($row);	
							if(_isInt($perlist)) $row['listfinal'] = ($i%intval($perlist)==0)?'endItem':'';
							foreach($row as $k=>$v){
								$itemtxt = str_replace('product.'.$k,$v,$itemtxt);
							}
							$cont_temp['cont'] .= $itemtxt;						
						}
						$pubhtml[$tscode] = str_replace($reppattern,$cont_temp['head'].$cont_temp['cont'].$cont_temp['bott'],$pubtemp);				
					}else{
						$pubhtml[$tscode] = '';
					}
				}
			}
		}
	}

	$ststr = '[SUBCATEGORY_ITEM_!'.$scode.'_LOOPST]';
	$edstr = '[SUBCATEGORY_ITEM_!'.$scode.'_LOOPED]';
	if(false !== $pos = strpos($main_body,$ststr)){
		if(false !== $epos = strpos($main_body,$edstr)){			
			$main_body=substr_replace($main_body,implode("\r\n",$pubhtml),$pos,$epos-$pos+strlen($edstr));
		}
	}
}
?>