<?
	if(substr(getenv("SCRIPT_NAME"),-21)=="/productbmap_text.php"){
		header("HTTP/1.0 404 Not Found");
		exit;
	}
	$alphabet_title = array("A"=>"<span id=\"engtitleidx\">A</span><br><br>",
	"B"=>"<span id=\"engtitleidx\">B</span><br><br>",
	"C"=>"<span id=\"engtitleidx\">C</span><br><br>",
	"D"=>"<span id=\"engtitleidx\">D</span><br><br>",
	"E"=>"<span id=\"engtitleidx\">E</span><br><br>",
	"F"=>"<span id=\"engtitleidx\">F</span><br><br>",
	"G"=>"<span id=\"engtitleidx\">G</span><br><br>",
	"H"=>"<span id=\"engtitleidx\">H</span><br><br>",
	"I"=>"<span id=\"engtitleidx\">I</span><br><br>",
	"J"=>"<span id=\"engtitleidx\">J</span><br><br>",
	"K"=>"<span id=\"engtitleidx\">K</span><br><br>",
	"L"=>"<span id=\"engtitleidx\">L</span><br><br>",
	"M"=>"<span id=\"engtitleidx\">M</span><br><br>",
	"N"=>"<span id=\"engtitleidx\">N</span><br><br>",
	"O"=>"<span id=\"engtitleidx\">O</span><br><br>",
	"P"=>"<span id=\"engtitleidx\">P</span><br><br>",
	"Q"=>"<span id=\"engtitleidx\">Q</span><br><br>",
	"R"=>"<span id=\"engtitleidx\">R</span><br><br>",
	"S"=>"<span id=\"engtitleidx\">S</span><br><br>",
	"T"=>"<span id=\"engtitleidx\">T</span><br><br>",
	"U"=>"<span id=\"engtitleidx\">U</span><br><br>",
	"V"=>"<span id=\"engtitleidx\">V</span><br><br>",
	"W"=>"<span id=\"engtitleidx\">W</span><br><br>",
	"X"=>"<span id=\"engtitleidx\">X</span><br><br>",
	"Y"=>"<span id=\"engtitleidx\">Y</span><br><br>",
	"Z"=>"<span id=\"engtitleidx\">Z</span><br><br>",
	"ETC"=>"<span id=\"engtitleidx\">ETC</span><br><br>");
	$hangul_title = array("丑"=>"<span id=\"kortitleidx\">丑</span><br><br>",
	"中"=>"<span id=\"kortitleidx\">中</span><br><br>",
	"之"=>"<span id=\"kortitleidx\">之</span><br><br>",
	"予"=>"<span id=\"kortitleidx\">予</span><br><br>",
	"仃"=>"<span id=\"kortitleidx\">仃</span><br><br>",
	"仆"=>"<span id=\"kortitleidx\">仆</span><br><br>",
	"今"=>"<span id=\"kortitleidx\">今</span><br><br>",
	"仄"=>"<span id=\"kortitleidx\">仄</span><br><br>",
	"元"=>"<span id=\"kortitleidx\">元</span><br><br>",
	"內"=>"<span id=\"kortitleidx\">內</span><br><br>",
	"六"=>"<span id=\"kortitleidx\">六</span><br><br>",
	"兮"=>"<span id=\"kortitleidx\">兮</span><br><br>",
	"公"=>"<span id=\"kortitleidx\">公</span><br><br>",
	"冗"=>"<span id=\"kortitleidx\">冗</span><br><br>",
	"晦顫"=>"<span id=\"kortitleidx\">晦顫</span><br><br>");

	$alphabet_search = array("<a href=\"".$_SERVER[PHP_SELF]."\"><span id=\"engbaridx\">TOTAL</span></a>",
	"<a href=\"".$_SERVER[PHP_SELF]."?searchValue=A\"><span id=\"engbaridx\">A</span></a>",
	"<a href=\"".$_SERVER[PHP_SELF]."?searchValue=B\"><span id=\"engbaridx\">B</span></a>",
	"<a href=\"".$_SERVER[PHP_SELF]."?searchValue=C\"><span id=\"engbaridx\">C</span></a>",
	"<a href=\"".$_SERVER[PHP_SELF]."?searchValue=D\"><span id=\"engbaridx\">D</span></a>",
	"<a href=\"".$_SERVER[PHP_SELF]."?searchValue=E\"><span id=\"engbaridx\">E</span></a>",
	"<a href=\"".$_SERVER[PHP_SELF]."?searchValue=F\"><span id=\"engbaridx\">F</span></a>",
	"<a href=\"".$_SERVER[PHP_SELF]."?searchValue=G\"><span id=\"engbaridx\">G</span></a>",
	"<a href=\"".$_SERVER[PHP_SELF]."?searchValue=H\"><span id=\"engbaridx\">H</span></a>",
	"<a href=\"".$_SERVER[PHP_SELF]."?searchValue=I\"><span id=\"engbaridx\">I</span></a>",
	"<a href=\"".$_SERVER[PHP_SELF]."?searchValue=J\"><span id=\"engbaridx\">J</span></a>",
	"<a href=\"".$_SERVER[PHP_SELF]."?searchValue=K\"><span id=\"engbaridx\">K</span></a>",
	"<a href=\"".$_SERVER[PHP_SELF]."?searchValue=L\"><span id=\"engbaridx\">L</span></a>",
	"<a href=\"".$_SERVER[PHP_SELF]."?searchValue=M\"><span id=\"engbaridx\">M</span></a>",
	"<a href=\"".$_SERVER[PHP_SELF]."?searchValue=N\"><span id=\"engbaridx\">N</span></a>",
	"<a href=\"".$_SERVER[PHP_SELF]."?searchValue=O\"><span id=\"engbaridx\">O</span></a>",
	"<a href=\"".$_SERVER[PHP_SELF]."?searchValue=P\"><span id=\"engbaridx\">P</span></a>",
	"<a href=\"".$_SERVER[PHP_SELF]."?searchValue=Q\"><span id=\"engbaridx\">Q</span></a>",
	"<a href=\"".$_SERVER[PHP_SELF]."?searchValue=R\"><span id=\"engbaridx\">R</span></a>",
	"<a href=\"".$_SERVER[PHP_SELF]."?searchValue=S\"><span id=\"engbaridx\">S</span></a>",
	"<a href=\"".$_SERVER[PHP_SELF]."?searchValue=T\"><span id=\"engbaridx\">T</span></a>",
	"<a href=\"".$_SERVER[PHP_SELF]."?searchValue=U\"><span id=\"engbaridx\">U</span></a>",
	"<a href=\"".$_SERVER[PHP_SELF]."?searchValue=V\"><span id=\"engbaridx\">V</span></a>",
	"<a href=\"".$_SERVER[PHP_SELF]."?searchValue=W\"><span id=\"engbaridx\">W</span></a>",
	"<a href=\"".$_SERVER[PHP_SELF]."?searchValue=X\"><span id=\"engbaridx\">X</span></a>",
	"<a href=\"".$_SERVER[PHP_SELF]."?searchValue=Y\"><span id=\"engbaridx\">Y</span></a>",
	"<a href=\"".$_SERVER[PHP_SELF]."?searchValue=Z\"><span id=\"engbaridx\">Z</span></a>",
	"<a href=\"".$_SERVER[PHP_SELF]."?searchValue=ETC\"><span id=\"engbaridx\">ETC</span></a>");
	$hangul_search = array("<a href=\"".$_SERVER[PHP_SELF]."\"><span id=\"korbaridx\">瞪羹</span></a>",
	"<a href=\"".$_SERVER[PHP_SELF]."?searchValue=丑\"><span id=\"korbaridx\">丑</span></a>",
	"<a href=\"".$_SERVER[PHP_SELF]."?searchValue=中\"><span id=\"korbaridx\">中</span></a>",
	"<a href=\"".$_SERVER[PHP_SELF]."?searchValue=之\"><span id=\"korbaridx\">之</span></a>",
	"<a href=\"".$_SERVER[PHP_SELF]."?searchValue=予\"><span id=\"korbaridx\">予</span></a>",
	"<a href=\"".$_SERVER[PHP_SELF]."?searchValue=仃\"><span id=\"korbaridx\">仃</span></a>",
	"<a href=\"".$_SERVER[PHP_SELF]."?searchValue=仆\"><span id=\"korbaridx\">仆</span></a>",
	"<a href=\"".$_SERVER[PHP_SELF]."?searchValue=今\"><span id=\"korbaridx\">今</span></a>",
	"<a href=\"".$_SERVER[PHP_SELF]."?searchValue=仄\"><span id=\"korbaridx\">仄</span></a>",
	"<a href=\"".$_SERVER[PHP_SELF]."?searchValue=元\"><span id=\"korbaridx\">元</span></a>",
	"<a href=\"".$_SERVER[PHP_SELF]."?searchValue=內\"><span id=\"korbaridx\">內</span></a>",
	"<a href=\"".$_SERVER[PHP_SELF]."?searchValue=六\"><span id=\"korbaridx\">六</span></a>",
	"<a href=\"".$_SERVER[PHP_SELF]."?searchValue=兮\"><span id=\"korbaridx\">兮</span></a>",
	"<a href=\"".$_SERVER[PHP_SELF]."?searchValue=公\"><span id=\"korbaridx\">公</span></a>",
	"<a href=\"".$_SERVER[PHP_SELF]."?searchValue=冗\"><span id=\"korbaridx\">冗</span></a>",
	"<a href=\"".$_SERVER[PHP_SELF]."?searchValue=晦顫\"><span id=\"korbaridx\">晦顫</span></a>");
	
	$alphabet_list = array();
	$hangul_list = array();
	$other_list = array();

	$sql = "SELECT bridx, brandname FROM tblproductbrand ";
	$sql.= "ORDER BY brandname ";
	get_db_cache($sql, $resval, "tblproductbrand.cache");
	
	$al_i=0;
	$han_j=0;
	while(list($key, $row)=each($resval)) {
		if(eregi("^[[:alpha:]]", $row->brandname, $returnvalue)) {
			$alphabet_list[strtoupper($returnvalue[0])][] = "<A HREF=\"".$Dir.FrontDir."productblist.php?brandcode=".$row->bridx."\"><span id=\"engbrandidx\">".$row->brandname."</span></a>\n";
			$al_i++;
		} else if(($row->brandname>="丑"&&$row->brandname<"凶") || ($row->brandname>="陛"&&$row->brandname<"氶")) {
			if(($row->brandname>="丑"&&$row->brandname<"中") || ($row->brandname>="陛"&&$row->brandname<"釭")) {
				$hangul_list["丑"][] = "<A HREF=\"".$Dir.FrontDir."productblist.php?brandcode=".$row->bridx."\"><span id=\"korbrandidx\">".$row->brandname."</span></a>\n";
			} else if(($row->brandname>="中"&&$row->brandname<"之") || ($row->brandname>="釭"&&$row->brandname<"棻")) {
				$hangul_list["中"][] = "<A HREF=\"".$Dir.FrontDir."productblist.php?brandcode=".$row->bridx."\"><span id=\"korbrandidx\">".$row->brandname."</span></a>\n";
			} else if(($row->brandname>="之"&&$row->brandname<"予") || ($row->brandname>="棻"&&$row->brandname<"塭")) {
				$hangul_list["之"][] = "<A HREF=\"".$Dir.FrontDir."productblist.php?brandcode=".$row->bridx."\"><span id=\"korbrandidx\">".$row->brandname."</span></a>\n";
			} else if(($row->brandname>="予"&&$row->brandname<"仃") || ($row->brandname>="塭"&&$row->brandname<"夥")) {
				$hangul_list["予"][] = "<A HREF=\"".$Dir.FrontDir."productblist.php?brandcode=".$row->bridx."\"><span id=\"korbrandidx\">".$row->brandname."</span></a>\n";
			} else if(($row->brandname>="仃"&&$row->brandname<"仆") || ($row->brandname>="葆"&&$row->brandname<"夥")) {
				$hangul_list["仃"][] = "<A HREF=\"".$Dir.FrontDir."productblist.php?brandcode=".$row->bridx."\"><span id=\"korbrandidx\">".$row->brandname."</span></a>\n";
			} else if(($row->brandname>="仆"&&$row->brandname<"今") || ($row->brandname>="夥"&&$row->brandname<"餌")) {
				$hangul_list["仆"][] = "<A HREF=\"".$Dir.FrontDir."productblist.php?brandcode=".$row->bridx."\"><span id=\"korbrandidx\">".$row->brandname."</span></a>\n";
			} else if(($row->brandname>="今"&&$row->brandname<"仄") || ($row->brandname>="餌"&&$row->brandname<"嬴")) {
				$hangul_list["今"][] = "<A HREF=\"".$Dir.FrontDir."productblist.php?brandcode=".$row->bridx."\"><span id=\"korbrandidx\">".$row->brandname."</span></a>\n";
			} else if(($row->brandname>="仄"&&$row->brandname<"元") || ($row->brandname>="嬴"&&$row->brandname<"濠")) {
				$hangul_list["仄"][] = "<A HREF=\"".$Dir.FrontDir."productblist.php?brandcode=".$row->bridx."\"><span id=\"korbrandidx\">".$row->brandname."</span></a>\n";
			} else if(($row->brandname>="元"&&$row->brandname<"內") || ($row->brandname>="濠"&&$row->brandname<"離")) {
				$hangul_list["元"][] = "<A HREF=\"".$Dir.FrontDir."productblist.php?brandcode=".$row->bridx."\"><span id=\"korbrandidx\">".$row->brandname."</span></a>\n";
			} else if(($row->brandname>="內"&&$row->brandname<"六") || ($row->brandname>="離"&&$row->brandname<"蘋")) {
				$hangul_list["內"][] = "<A HREF=\"".$Dir.FrontDir."productblist.php?brandcode=".$row->bridx."\"><span id=\"korbrandidx\">".$row->brandname."</span></a>\n";
			} else if(($row->brandname>="六"&&$row->brandname<"兮") || ($row->brandname>="蘋"&&$row->brandname<"顫")) {
				$hangul_list["六"][] = "<A HREF=\"".$Dir.FrontDir."productblist.php?brandcode=".$row->bridx."\"><span id=\"korbrandidx\">".$row->brandname."</span></a>\n";
			} else if(($row->brandname>="兮"&&$row->brandname<"公") || ($row->brandname>="顫"&&$row->brandname<"だ")) {
				$hangul_list["兮"][] = "<A HREF=\"".$Dir.FrontDir."productblist.php?brandcode=".$row->bridx."\"><span id=\"korbrandidx\">".$row->brandname."</span></a>\n";
			} else if(($row->brandname>="公"&&$row->brandname<"冗") || ($row->brandname>="だ"&&$row->brandname<"ж")) {
				$hangul_list["公"][] = "<A HREF=\"".$Dir.FrontDir."productblist.php?brandcode=".$row->bridx."\"><span id=\"korbrandidx\">".$row->brandname."</span></a>\n";
			} else {
				$hangul_list["冗"][] = "<A HREF=\"".$Dir.FrontDir."productblist.php?brandcode=".$row->bridx."\"><span id=\"korbrandidx\">".$row->brandname."</span></a>\n";
			}
			$han_j++;
		} else {
			$other_list[] = "<A HREF=\"".$Dir.FrontDir."productblist.php?brandcode=".$row->bridx."\"><span id=\"engbrandidx\">".$row->brandname."</span></a>\n";
			$al_i++;
			$han_j++;
		}
	}

	$engsearchbar.= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
	$engsearchbar.= "<tr align=\"center\">\n";
	for($i=0; $i<count($alphabet_search); $i++) {
		$engsearchbar.= "	<td width=\"3%\">".$alphabet_search[$i]."</td>\n";
	}
	$engsearchbar.= "</tr>\n";
	$engsearchbar.= "</table>\n";

	$korsearchbar.= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
	$korsearchbar.= "<tr align=\"center\">\n";
	for($i=0; $i<count($hangul_search); $i++) {
		$korsearchbar.= "	<td width=\"6%\">".$hangul_search[$i]."</td>\n";
	}
	$korsearchbar.= "</tr>\n";
	$korsearchbar.= "</table>\n";
	
	if(($al_i+$han_j)>0) {
		$alphabet_list["ETC"] = $other_list; // 晦顫 頂辨 艙僥/и旋 賅舒 轎溘
		$hangul_list["晦顫"] = $other_list; // 晦顫 頂辨 艙僥/и旋 賅舒 轎溘

		$colval = "4";	// td 偎熱
		$tdWidth = 100/$colval;

		if($al_i>0) {
			$brandalphabet = "";
			$brandalphabet.= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
			$brandalphabet.= "<tr align=\"center\" valign=\"top\">\n";
			$brandalphabet.= "	<td width=\"".$tdWidth."%\" style=\"padding:10px;word-break:break-all;\">\n";

			$colal = ceil($al_i/$colval);

			$i=0;
			while(list($key1, $val1)=each($alphabet_list)) {
				if(count($alphabet_list[$key1])>0) {
					sort($alphabet_list[$key1]);
					$k=0;
					while(list($key2, $val2)=each($alphabet_list[$key1])) {
						if($colal==$i) {
							$brandalphabet.= "	</td>\n<td width=\"".$tdWidth."%\" style=\"padding:10px;word-break:break-all;\">";
							$i=0;
						}
						if($k==0) {
							$brandalphabet.= $alphabet_title[$key1];
						}
						$brandalphabet.= $val2."<br>";
						$i++;
						$k++;
					}
					$brandalphabet .= "<br><br>";
				}
			}

			if($al_i<$colval) {
				for($i=0; $i<$colval-$al_i; $i++) {
					$brandalphabet.= "	</td>\n<td width=\"".$tdWidth."%\" style=\"padding:10px;word-break:break-all;\">";
				}
			}

			$brandalphabet.= "		</td>\n";
			$brandalphabet.= "	</tr>\n";
			$brandalphabet.= "	</table>\n";
		}

		if($han_j>0) {
			$brandhangul = "";
			$brandhangul.= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
			$brandhangul.= "<tr align=\"center\" valign=\"top\">\n";
			$brandhangul.= "	<td width=\"".$tdWidth."%\" style=\"padding:10px;word-break:break-all;\">\n";

			$colhan = ceil($han_j/$colval);

			$i=0;
			while(list($key1, $val1)=each($hangul_list)) {
				if(count($hangul_list[$key1])>0) {
					$k=0;
					while(list($key2, $val2)=each($hangul_list[$key1])) {
						if($colhan==$i) {
							$brandhangul.= "	</td>\n<td width=\"".$tdWidth."%\" style=\"padding:10px;word-break:break-all;\">";
							$i=0;
						}
						if($k==0) {
							$brandhangul.= $hangul_title[$key1];
						}
						$brandmap_list[$key1].= $val2."<br>";
						$brandhangul.= $val2."<br>";
						$i++;
						$k++;
					}
					$brandhangul.= "<br><br>";
				}
			}
			
			if($han_j<$colval) {
				for($i=0; $i<$colval-$han_j; $i++) {
					$brandhangul.= "	</td>\n<td width=\"".$tdWidth."%\" style=\"padding:10px;word-break:break-all;\">";
				}
			}
			$brandhangul.= "	</td>\n";
			$brandhangul.= "</tr>\n";
			$brandhangul.= "</table>\n";
		}

		if(strlen($searchValue)>0) {
			if($searchValue == "ETC") {
				$searchType=1;
			} else if(eregi("^[[:alpha:]]", $searchValue, $returnvalue)) {
				$searchValue = strtoupper($returnvalue[0]);
				$searchType=1;
			} else if($searchValue>="丑"&&$searchValue<"凶") {
			} else {
				$searchValue = "晦顫";
			}

			if($searchType>0) {
				$searchbar = $engsearchbar;
			} else {
				$searchbar = $korsearchbar;
			}
			
			$searchresult = "";
			$searchresult.= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
			$searchresult.= "<col width=\"100\"></col>\n";
			$searchresult.= "<col></col>\n";
			$searchresult.= "<tr>\n";
			$searchresult.= "	<td align=\"center\" valign=\"top\" style=\"padding:10px;\">".($searchType>0?$alphabet_title[$searchValue]:$hangul_title[$searchValue])."</td>\n";
			$searchresult.= "	<td>\n";
			$searchresult.= "	<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
			$searchresult.= "	<tr align=\"center\" valign=\"top\">\n";

			if($searchType>0) {
				$searchresult.= "		<td width=\"".$tdWidth."%\" style=\"padding:10px;word-break:break-all;\" id=\"engbrandidx\">\n";
				$result_count = count($alphabet_list[$searchValue]);
				if($result_count>0) {
					$colalsearch = ceil($result_count/$colval);
					sort($alphabet_list[$searchValue]);
					$i=0;
					for($j=0; $j<$result_count; $j++) {
						if($colalsearch==$i) {
							$searchresult.= "</td>\n<td width=\"".$tdWidth."%\" style=\"padding:10px;word-break:break-all;\" id=\"engbrandidx\">";
							$i=0;
						}
						$brandsearch_list[$searchValue].= $alphabet_list[$searchValue][$j]."<br>";
						$searchresult.= $alphabet_list[$searchValue][$j]."<br>";
						$i++;
					}
				}
			} else {
				$searchresult.= "		<td width=\"".$tdWidth."%\" style=\"padding:10px;word-break:break-all;\" id=\"korbrandidx\">\n";
				$result_count = count($hangul_list[$searchValue]);
				if($result_count>0) {
					$colhansearch = ceil($result_count/$colval);
					$i=0;
					for($j=0; $j<$result_count; $j++) {
						if($colhansearch==$i) {
							$searchresult.= "</td>\n<td width=\"".$tdWidth."%\" style=\"padding:10px;word-break:break-all;\" id=\"korbrandidx\">";
							$i=0;
						}
						$brandsearch_list[$searchValue].= $hangul_list[$searchValue][$j]."<br>";
						$searchresult.= $hangul_list[$searchValue][$j]."<br>";
						$i++;
					}
				}
			}

			if($result_count<$colval) {
				for($i=0; $i<$colval-$result_count; $i++) {
					$searchresult.= "	</td>\n<td width=\"".$tdWidth."%\" style=\"padding:10px;word-break:break-all;\">";
				}
			}

			$searchresult.= "		</td>\n";
			$searchresult.= "	</tr>\n";
			$searchresult.= "	</table>\n";
			$searchresult.= "	</td>\n";
			$searchresult.= "</tr>\n";
			$searchresult.= "</table>\n";
		}
	}
?>