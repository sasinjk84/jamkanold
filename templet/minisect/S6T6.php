<!-- S6T6 -->
<table width=100% border=0 cellspacing=0 cellpadding=0 style="table-layout:fixed">
<tr align=center valign=top>
	<td width=25%>
<?
	########## 1 start (130*130) ##########
	$kk=1;
	if(isset($specialprlist[$kk])) {
		echo "<table width=100% border=0 cellspacing=0 cellpadding=0 align=center id=\"M".$specialprlist[$kk]->productcode."\" onmouseover=\"quickfun_show(this,'M".$specialprlist[$kk]->productcode."','')\" onmouseout=\"quickfun_show(this,'M".$specialprlist[$kk]->productcode."','none')\">\n";
		echo "<tr>\n";
		echo "	<td height=140 align=center valign=top style=padding:5,10><A HREF=\"javascript:GoItem('".$specialprlist[$kk]->productcode."')\">";
		if (strlen($specialprlist[$kk]->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$specialprlist[$kk]->tinyimage)==true) {
			echo "<img src=\"".$Dir.DataDir."shopimages/product/".urlencode($specialprlist[$kk]->tinyimage)."\" border=0 ";
			$width = getimagesize($Dir.DataDir."shopimages/product/".$specialprlist[$kk]->tinyimage);
			if ($width[0]>=$width[1] && $width[0]>=130) echo "width=130 ";
			else if ($width[1]>=130) echo "height=130 ";
		} else {
			echo "<img src=\"".$Dir."images/no_img.gif\" border=0 align=center";
		}
		echo "	></a></td>\n";
		echo "</tr>\n";
		echo "<tr><td height=\"3\" style=\"position:relative;\">".($_data->ETCTYPE["QUICKTOOLS"]!="Y"?"<script>quickfun_write('".$Dir."','M','".$specialprlist[$kk]->productcode."','".($specialprlist[$kk]->quantity=="0"?"":"1")."')</script>":"")."</td></tr>\n";
		echo "<tr>\n";
		echo "	<td align=center valign=top style=padding:0,10>\n";
		echo "	<table width=130 border=0 cellspacing=0 cellpadding=0>\n";
		echo "	<tr>\n";
		echo "		<td height=7> </td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td height=2></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td valign=top style=\"word-break:break-all;\"><A HREF=\"javascript:GoItem('".$specialprlist[$kk]->productcode."')\">".viewproductname($specialprlist[$kk]->productname,$specialprlist[$kk]->etctype,$specialprlist[$kk]->selfcode)."</a></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td height=4></td>\n";
		echo "	</tr>\n";
		if($specialprlist[$kk]->consumerprice>0) {	//소비자가
			echo "	<tr>\n";
			echo "		<td height=17 class=verdana2 style=\"color:#A7A7A7\"><img src=\"".$Dir."images/common/won_icon2.gif\" border=0 align=absmiddle><strike>".number_format($specialprlist[$kk]->consumerprice)."</strike>원";
			echo "		</td>\n";
			echo "	</tr>\n";
		}
		echo "	<tr>\n";
		echo "		<td height=17 class=verdana2 style=\"font-weight:bold;color:#FF3243 !important\">";
		if($dicker=dickerview($specialprlist[$kk]->etctype,number_format($specialprlist[$kk]->sellprice)."원",1)) {
			echo $dicker;
		} else if(strlen($_data->proption_price)==0) {
			echo "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle>".number_format($specialprlist[$kk]->sellprice)."원";
			if (strlen($specialprlist[$kk]->option_price)!=0) echo "(기본가)";
		} else {
			echo "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle>";
			if (strlen($specialprlist[$kk]->option_price)==0) echo number_format($specialprlist[$kk]->sellprice)."원";
			else echo ereg_replace("\[PRICE\]",number_format($specialprlist[$kk]->sellprice),$_data->proption_price);
		}
		if ($specialprlist[$kk]->quantity=="0") echo soldout();
		echo "		</td>\n";
		echo "	</tr>\n";
		if($specialprlist[$kk]->consumerprice<=0) {
			echo "	<tr>\n";
			echo "		<td height=17></td>\n";
			echo "	</tr>\n";
		}
		echo "	<tr>\n";
		echo "		<td height=4></td>\n";
		echo "	</tr>\n";
		echo "	</table>\n";
		echo "	</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
	} else {
		echo "&nbsp;";
	}
	$kk++;
	########## 1 end ##########
?>
	</td>
	<td width=1 bgcolor=ffffff nowrap></td>
	<td width=25%>
<?
	########## 2 start (130*130) ##########
	if(isset($specialprlist[$kk])) {
		echo "<table width=100% border=0 cellspacing=0 cellpadding=0 align=center id=\"M".$specialprlist[$kk]->productcode."\" onmouseover=\"quickfun_show(this,'M".$specialprlist[$kk]->productcode."','')\" onmouseout=\"quickfun_show(this,'M".$specialprlist[$kk]->productcode."','none')\">\n";
		echo "<tr>\n";
		echo "	<td height=140 align=center valign=top style=padding:5,10><A HREF=\"javascript:GoItem('".$specialprlist[$kk]->productcode."')\">";
		if (strlen($specialprlist[$kk]->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$specialprlist[$kk]->tinyimage)==true) {
			echo "<img src=\"".$Dir.DataDir."shopimages/product/".urlencode($specialprlist[$kk]->tinyimage)."\" border=0 ";
			$width = getimagesize($Dir.DataDir."shopimages/product/".$specialprlist[$kk]->tinyimage);
			if ($width[0]>=$width[1] && $width[0]>=130) echo "width=130 ";
			else if ($width[1]>=130) echo "height=130 ";
		} else {
			echo "<img src=\"".$Dir."images/no_img.gif\" border=0 align=center";
		}
		echo "	></a></td>\n";
		echo "</tr>\n";
		echo "<tr><td height=\"3\" style=\"position:relative;\">".($_data->ETCTYPE["QUICKTOOLS"]!="Y"?"<script>quickfun_write('".$Dir."','M','".$specialprlist[$kk]->productcode."','".($specialprlist[$kk]->quantity=="0"?"":"1")."')</script>":"")."</td></tr>\n";
		echo "<tr>\n";
		echo "	<td align=center valign=top style=padding:0,10>\n";
		echo "	<table width=130 border=0 cellspacing=0 cellpadding=0>\n";
		echo "	<tr>\n";
		echo "		<td height=7> </td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td height=2></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td valign=top style=\"word-break:break-all;\"><A HREF=\"javascript:GoItem('".$specialprlist[$kk]->productcode."')\">".viewproductname($specialprlist[$kk]->productname,$specialprlist[$kk]->etctype,$specialprlist[$kk]->selfcode)."</a></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td height=4></td>\n";
		echo "	</tr>\n";
		if($specialprlist[$kk]->consumerprice>0) {	//소비자가
			echo "	<tr>\n";
			echo "		<td height=17 class=verdana2 style=\"color:#A7A7A7\"><img src=\"".$Dir."images/common/won_icon2.gif\" border=0 align=absmiddle><strike>".number_format($specialprlist[$kk]->consumerprice)."</strike>원";
			echo "		</td>\n";
			echo "	</tr>\n";
		}
		echo "	<tr>\n";
		echo "		<td height=17 class=verdana2 style=\"font-weight:bold;color:#FF3243 !important\">";
		if($dicker=dickerview($specialprlist[$kk]->etctype,number_format($specialprlist[$kk]->sellprice)."원",1)) {
			echo $dicker;
		} else if(strlen($_data->proption_price)==0) {
			echo "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle>".number_format($specialprlist[$kk]->sellprice)."원";
			if (strlen($specialprlist[$kk]->option_price)!=0) echo "(기본가)";
		} else {
			echo "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle>";
			if (strlen($specialprlist[$kk]->option_price)==0) echo number_format($specialprlist[$kk]->sellprice)."원";
			else echo ereg_replace("\[PRICE\]",number_format($specialprlist[$kk]->sellprice),$_data->proption_price);
		}
		if ($specialprlist[$kk]->quantity=="0") echo soldout();
		echo "		</td>\n";
		echo "	</tr>\n";
		if($specialprlist[$kk]->consumerprice<=0) {
			echo "	<tr>\n";
			echo "		<td height=17></td>\n";
			echo "	</tr>\n";
		}
		echo "	<tr>\n";
		echo "		<td height=4></td>\n";
		echo "	</tr>\n";
		echo "	</table>\n";
		echo "	</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
	} else {
		echo "&nbsp;";
	}
	$kk++;
	########## 2 end ##########
?>
	</td>
	<td width=1 bgcolor=ffffff nowrap></td>
	<td width=25%>
<?
	########## 3 start (130*130) ##########
	if(isset($specialprlist[$kk])) {
		echo "<table width=100% border=0 cellspacing=0 cellpadding=0 align=center id=\"M".$specialprlist[$kk]->productcode."\" onmouseover=\"quickfun_show(this,'M".$specialprlist[$kk]->productcode."','')\" onmouseout=\"quickfun_show(this,'M".$specialprlist[$kk]->productcode."','none')\">\n";
		echo "<tr>\n";
		echo "	<td height=140 align=center valign=top style=padding:5,10><A HREF=\"javascript:GoItem('".$specialprlist[$kk]->productcode."')\">";
		if (strlen($specialprlist[$kk]->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$specialprlist[$kk]->tinyimage)==true) {
			echo "<img src=\"".$Dir.DataDir."shopimages/product/".urlencode($specialprlist[$kk]->tinyimage)."\" border=0 ";
			$width = getimagesize($Dir.DataDir."shopimages/product/".$specialprlist[$kk]->tinyimage);
			if ($width[0]>=$width[1] && $width[0]>=130) echo "width=130 ";
			else if ($width[1]>=130) echo "height=130 ";
		} else {
			echo "<img src=\"".$Dir."images/no_img.gif\" border=0 align=center";
		}
		echo "	></a></td>\n";
		echo "</tr>\n";
		echo "<tr><td height=\"3\" style=\"position:relative;\">".($_data->ETCTYPE["QUICKTOOLS"]!="Y"?"<script>quickfun_write('".$Dir."','M','".$specialprlist[$kk]->productcode."','".($specialprlist[$kk]->quantity=="0"?"":"1")."')</script>":"")."</td></tr>\n";
		echo "<tr>\n";
		echo "	<td align=center valign=top style=padding:0,10>\n";
		echo "	<table width=130 border=0 cellspacing=0 cellpadding=0>\n";
		echo "	<tr>\n";
		echo "		<td height=7> </td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td height=2></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td valign=top style=\"word-break:break-all;\"><A HREF=\"javascript:GoItem('".$specialprlist[$kk]->productcode."')\">".viewproductname($specialprlist[$kk]->productname,$specialprlist[$kk]->etctype,$specialprlist[$kk]->selfcode)."</a></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td height=4></td>\n";
		echo "	</tr>\n";
		if($specialprlist[$kk]->consumerprice>0) {	//소비자가
			echo "	<tr>\n";
			echo "		<td height=17 class=verdana2 style=\"color:#A7A7A7\"><img src=\"".$Dir."images/common/won_icon2.gif\" border=0 align=absmiddle><strike>".number_format($specialprlist[$kk]->consumerprice)."</strike>원";
			echo "		</td>\n";
			echo "	</tr>\n";
		}
		echo "	<tr>\n";
		echo "		<td height=17 class=verdana2 style=\"font-weight:bold;color:#FF3243 !important\">";
		if($dicker=dickerview($specialprlist[$kk]->etctype,number_format($specialprlist[$kk]->sellprice)."원",1)) {
			echo $dicker;
		} else if(strlen($_data->proption_price)==0) {
			echo "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle>".number_format($specialprlist[$kk]->sellprice)."원";
			if (strlen($specialprlist[$kk]->option_price)!=0) echo "(기본가)";
		} else {
			echo "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle>";
			if (strlen($specialprlist[$kk]->option_price)==0) echo number_format($specialprlist[$kk]->sellprice)."원";
			else echo ereg_replace("\[PRICE\]",number_format($specialprlist[$kk]->sellprice),$_data->proption_price);
		}
		if ($specialprlist[$kk]->quantity=="0") echo soldout();
		echo "		</td>\n";
		echo "	</tr>\n";
		if($specialprlist[$kk]->consumerprice<=0) {
			echo "	<tr>\n";
			echo "		<td height=17></td>\n";
			echo "	</tr>\n";
		}
		echo "	<tr>\n";
		echo "		<td height=4></td>\n";
		echo "	</tr>\n";
		echo "	</table>\n";
		echo "	</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
	} else {
		echo "&nbsp;";
	}
	$kk++;
	########## 3 end ##########
?>
	</td>
	<td width=1 bgcolor=ECECEC nowrap></td>
	<td width=25%>
	<table border=0 cellspacing=5 cellpadding=0>
	<tr>
		<td height=34% valign=middle>
<?
		########## 4 start (60*60) ##########
		if(isset($specialprlist[$kk])) {
			echo "<table width=100% border=0 cellspacing=0 cellpadding=0 align=center id=\"M".$specialprlist[$kk]->productcode."\" onmouseover=\"quickfun_show(this,'M".$specialprlist[$kk]->productcode."','','row')\" onmouseout=\"quickfun_show(this,'M".$specialprlist[$kk]->productcode."','none')\">\n";
			echo "<col width=\"60\"></col>\n";
			echo "<col width=\"0\"></col>\n";
			echo "<col width=\"\"></col>\n";
			echo "<tr>\n";
			echo "	<td valign=top style=padding:5,10><A HREF=\"javascript:GoItem('".$specialprlist[$kk]->productcode."')\">";
			if (strlen($specialprlist[$kk]->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$specialprlist[$kk]->tinyimage)==true) {
				echo "<img src=\"".$Dir.DataDir."shopimages/product/".urlencode($specialprlist[$kk]->tinyimage)."\" border=0 ";
				$width = getimagesize($Dir.DataDir."shopimages/product/".$specialprlist[$kk]->tinyimage);
				if ($width[0]>=$width[1] && $width[0]>=60) echo "width=60 ";
				else if ($width[1]>=60) echo "height=60 ";
			} else {
				echo "<img src=\"".$Dir."images/no_img.gif\" border=0 align=center";
			}
			echo "	></a></td>\n";
			echo "	<td style=\"position:relative;\">".($_data->ETCTYPE["QUICKTOOLS"]!="Y"?"<script>quickfun_write('".$Dir."','M','".$specialprlist[$kk]->productcode."','".($specialprlist[$kk]->quantity=="0"?"":"1")."','row')</script>":"")."</td>";
			echo "	<td valign=top>\n";
			echo "	<table border=0 cellspacing=0 cellpadding=0 width=100%>\n";
			echo "	<tr>\n";
			echo "		<td height=5></td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td valign=top style=\"word-break:break-all;\" style='padding-right:5px'><A HREF=\"javascript:GoItem('".$specialprlist[$kk]->productcode."')\">".viewselfcode($specialprlist[$kk]->productname,$specialprlist[$kk]->selfcode)."</a></td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td height=4></td>\n";
			echo "	</tr>\n";
			if($specialprlist[$kk]->consumerprice>0) {	//소비자가
				echo "	<tr>\n";
				echo "		<td class=verdana2 style=\"color:#A7A7A7\"><img src=\"".$Dir."images/common/won_icon2.gif\" border=0 align=absmiddle><strike>".number_format($specialprlist[$kk]->consumerprice)."</strike>원";
				echo "		</td>\n";
				echo "	</tr>\n";
			}
			echo "	<tr>\n";
			echo "		<td class=verdana2 style=\"font-weight:bold;color:#FF3243 !important\">";
			if($dicker=dickerview($specialprlist[$kk]->etctype,number_format($specialprlist[$kk]->sellprice)."원",1)) {
				echo $dicker;
			} else if(strlen($_data->proption_price)==0) {
				echo "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle>".number_format($specialprlist[$kk]->sellprice)."원";
				if (strlen($specialprlist[$kk]->option_price)!=0) echo "(기본가)";
			} else {
				echo "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle>";
				if (strlen($specialprlist[$kk]->option_price)==0) echo number_format($specialprlist[$kk]->sellprice)."원";
				else echo ereg_replace("\[PRICE\]",number_format($specialprlist[$kk]->sellprice),$_data->proption_price);
			}
			if ($specialprlist[$kk]->quantity=="0") echo soldout();
			echo "		</td>\n";
			echo "	</tr>\n";
			echo "	</table>\n";
			echo "	</td>\n";
			echo "</tr>\n";
			echo "</table>\n";
		} else {
			echo "&nbsp;";
		}
		$kk++;
		########## 4 end ##########
?>
		</td>
	</tr>
	<tr>
		<td height=1 bgcolor=ECECEC nowrap></td>
	</tr>
	<tr>
		<td height=34% valign=middle>
<?
		########## 5 start (60*60) ##########
		if(isset($specialprlist[$kk])) {
			echo "<table width=100% border=0 cellspacing=0 cellpadding=0 align=center id=\"M".$specialprlist[$kk]->productcode."\" onmouseover=\"quickfun_show(this,'M".$specialprlist[$kk]->productcode."','','row')\" onmouseout=\"quickfun_show(this,'M".$specialprlist[$kk]->productcode."','none')\">\n";
			echo "<col width=\"60\"></col>\n";
			echo "<col width=\"0\"></col>\n";
			echo "<col width=\"\"></col>\n";
			echo "<tr>\n";
			echo "	<td valign=top style=padding:5,10><A HREF=\"javascript:GoItem('".$specialprlist[$kk]->productcode."')\">";
			if (strlen($specialprlist[$kk]->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$specialprlist[$kk]->tinyimage)==true) {
				echo "<img src=\"".$Dir.DataDir."shopimages/product/".urlencode($specialprlist[$kk]->tinyimage)."\" border=0 ";
				$width = getimagesize($Dir.DataDir."shopimages/product/".$specialprlist[$kk]->tinyimage);
				if ($width[0]>=$width[1] && $width[0]>=60) echo "width=60 ";
				else if ($width[1]>=60) echo "height=60 ";
			} else {
				echo "<img src=\"".$Dir."images/no_img.gif\" border=0 align=center";
			}
			echo "	></a></td>\n";
			echo "	<td style=\"position:relative;\">".($_data->ETCTYPE["QUICKTOOLS"]!="Y"?"<script>quickfun_write('".$Dir."','M','".$specialprlist[$kk]->productcode."','".($specialprlist[$kk]->quantity=="0"?"":"1")."','row')</script>":"")."</td>";
			echo "	<td valign=top>\n";
			echo "	<table border=0 cellspacing=0 cellpadding=0 width=100%>\n";
			echo "	<tr>\n";
			echo "		<td height=5></td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td valign=top style=\"word-break:break-all;\" style='padding-right:5px'><A HREF=\"javascript:GoItem('".$specialprlist[$kk]->productcode."')\">".viewselfcode($specialprlist[$kk]->productname,$specialprlist[$kk]->selfcode)."</a></td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td height=4></td>\n";
			echo "	</tr>\n";
			if($specialprlist[$kk]->consumerprice>0) {	//소비자가
				echo "	<tr>\n";
				echo "		<td class=verdana2 style=\"color:#A7A7A7\"><img src=\"".$Dir."images/common/won_icon2.gif\" border=0 align=absmiddle><strike>".number_format($specialprlist[$kk]->consumerprice)."</strike>원";
				echo "		</td>\n";
				echo "	</tr>\n";
			}
			echo "	<tr>\n";
			echo "		<td class=verdana2 style=\"font-weight:bold;color:#FF3243 !important\">";
			if($dicker=dickerview($specialprlist[$kk]->etctype,number_format($specialprlist[$kk]->sellprice)."원",1)) {
				echo $dicker;
			} else if(strlen($_data->proption_price)==0) {
				echo "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle>".number_format($specialprlist[$kk]->sellprice)."원";
				if (strlen($specialprlist[$kk]->option_price)!=0) echo "(기본가)";
			} else {
				echo "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle>";
				if (strlen($specialprlist[$kk]->option_price)==0) echo number_format($specialprlist[$kk]->sellprice)."원";
				else echo ereg_replace("\[PRICE\]",number_format($specialprlist[$kk]->sellprice),$_data->proption_price);
			}
			if ($specialprlist[$kk]->quantity=="0") echo soldout();
			echo "		</td>\n";
			echo "	</tr>\n";
			echo "	</table>\n";
			echo "	</td>\n";
			echo "</tr>\n";
			echo "</table>\n";
		} else {
			echo "&nbsp;";
		}
		$kk++;
		########## 5 end ##########
?>
		</td>
	</tr>
	<tr>
		<td height=1 bgcolor=ECECEC nowrap></td>
	</tr>
	<tr>
		<td height=34% valign=middle>
<?
		########## 6 start (60*60) ##########
		if(isset($specialprlist[$kk])) {
			echo "<table width=100% border=0 cellspacing=0 cellpadding=0 align=center id=\"M".$specialprlist[$kk]->productcode."\" onmouseover=\"quickfun_show(this,'M".$specialprlist[$kk]->productcode."','','row')\" onmouseout=\"quickfun_show(this,'M".$specialprlist[$kk]->productcode."','none')\">\n";
			echo "<col width=\"60\"></col>\n";
			echo "<col width=\"0\"></col>\n";
			echo "<col width=\"\"></col>\n";
			echo "<tr>\n";
			echo "	<td valign=top style=padding:5,10><A HREF=\"javascript:GoItem('".$specialprlist[$kk]->productcode."')\">";
			if (strlen($specialprlist[$kk]->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$specialprlist[$kk]->tinyimage)==true) {
				echo "<img src=\"".$Dir.DataDir."shopimages/product/".urlencode($specialprlist[$kk]->tinyimage)."\" border=0 ";
				$width = getimagesize($Dir.DataDir."shopimages/product/".$specialprlist[$kk]->tinyimage);
				if ($width[0]>=$width[1] && $width[0]>=60) echo "width=60 ";
				else if ($width[1]>=60) echo "height=60 ";
			} else {
				echo "<img src=\"".$Dir."images/no_img.gif\" border=0 align=center";
			}
			echo "	></a></td>\n";
			echo "	<td style=\"position:relative;\">".($_data->ETCTYPE["QUICKTOOLS"]!="Y"?"<script>quickfun_write('".$Dir."','M','".$specialprlist[$kk]->productcode."','".($specialprlist[$kk]->quantity=="0"?"":"1")."','row')</script>":"")."</td>";
			echo "	<td valign=top>\n";
			echo "	<table border=0 cellspacing=0 cellpadding=0 width=100%>\n";
			echo "	<tr>\n";
			echo "		<td height=5></td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td valign=top style=\"word-break:break-all;\" style='padding-right:5px'><A HREF=\"javascript:GoItem('".$specialprlist[$kk]->productcode."')\">".viewselfcode($specialprlist[$kk]->productname,$specialprlist[$kk]->selfcode)."</a></td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td height=4></td>\n";
			echo "	</tr>\n";
			if($specialprlist[$kk]->consumerprice>0) {	//소비자가
				echo "	<tr>\n";
				echo "		<td class=verdana2 style=\"color:#A7A7A7\"><img src=\"".$Dir."images/common/won_icon2.gif\" border=0 align=absmiddle><strike>".number_format($specialprlist[$kk]->consumerprice)."</strike>원";
				echo "		</td>\n";
				echo "	</tr>\n";
			}
			echo "	<tr>\n";
			echo "		<td class=verdana2 style=\"font-weight:bold;color:#FF3243 !important\">";
			if($dicker=dickerview($specialprlist[$kk]->etctype,number_format($specialprlist[$kk]->sellprice)."원",1)) {
				echo $dicker;
			} else if(strlen($_data->proption_price)==0) {
				echo "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle>".number_format($specialprlist[$kk]->sellprice)."원";
				if (strlen($specialprlist[$kk]->option_price)!=0) echo "(기본가)";
			} else {
				echo "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle>";
				if (strlen($specialprlist[$kk]->option_price)==0) echo number_format($specialprlist[$kk]->sellprice)."원";
				else echo ereg_replace("\[PRICE\]",number_format($specialprlist[$kk]->sellprice),$_data->proption_price);
			}
			if ($specialprlist[$kk]->quantity=="0") echo soldout();
			echo "		</td>\n";
			echo "	</tr>\n";
			echo "	</table>\n";
			echo "	</td>\n";
			echo "</tr>\n";
			echo "</table>\n";
		} else {
			echo "&nbsp;";
		}
		$kk++;
		########## 6 end ##########
?>
		</td>
	</tr>
	</table>
	</td>
</tr>
</table>

<table align=center width=98% border=0 cellspacing=0 cellpadding=0>
<tr><td height=5></td></tr>
<tr><td height=1 bgcolor=ECECEC nowrap></td></tr>
<tr><td height=5></td></tr>
</table>

<table width=100% border=0 cellspacing=0 cellpadding=0 style="table-layout:fixed">
<tr align=center valign=top>
	<td width=25%>
<?
	########## 7 start (130*130) ##########
	if(isset($specialprlist[$kk])) {
		echo "<table width=100% border=0 cellspacing=0 cellpadding=0 align=center id=\"M".$specialprlist[$kk]->productcode."\" onmouseover=\"quickfun_show(this,'M".$specialprlist[$kk]->productcode."','')\" onmouseout=\"quickfun_show(this,'M".$specialprlist[$kk]->productcode."','none')\">\n";
		echo "<tr>\n";
		echo "	<td height=140 align=center valign=top style=padding:5,10><A HREF=\"javascript:GoItem('".$specialprlist[$kk]->productcode."')\">";
		if (strlen($specialprlist[$kk]->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$specialprlist[$kk]->tinyimage)==true) {
			echo "<img src=\"".$Dir.DataDir."shopimages/product/".urlencode($specialprlist[$kk]->tinyimage)."\" border=0 ";
			$width = getimagesize($Dir.DataDir."shopimages/product/".$specialprlist[$kk]->tinyimage);
			if ($width[0]>=$width[1] && $width[0]>=130) echo "width=130 ";
			else if ($width[1]>=130) echo "height=130 ";
		} else {
			echo "<img src=\"".$Dir."images/no_img.gif\" border=0 align=center";
		}
		echo "	></a></td>\n";
		echo "</tr>\n";
		echo "<tr><td height=\"3\" style=\"position:relative;\">".($_data->ETCTYPE["QUICKTOOLS"]!="Y"?"<script>quickfun_write('".$Dir."','M','".$specialprlist[$kk]->productcode."','".($specialprlist[$kk]->quantity=="0"?"":"1")."')</script>":"")."</td></tr>\n";
		echo "<tr>\n";
		echo "	<td align=center valign=top style=padding:0,10>\n";
		echo "	<table width=130 border=0 cellspacing=0 cellpadding=0>\n";
		echo "	<tr>\n";
		echo "		<td height=7> </td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td height=2></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td valign=top style=\"word-break:break-all;\"><A HREF=\"javascript:GoItem('".$specialprlist[$kk]->productcode."')\">".viewproductname($specialprlist[$kk]->productname,$specialprlist[$kk]->etctype,$specialprlist[$kk]->selfcode)."</a></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td height=4></td>\n";
		echo "	</tr>\n";
		if($specialprlist[$kk]->consumerprice>0) {	//소비자가
			echo "	<tr>\n";
			echo "		<td height=17 class=verdana2 style=\"color:#A7A7A7\"><img src=\"".$Dir."images/common/won_icon2.gif\" border=0 align=absmiddle><strike>".number_format($specialprlist[$kk]->consumerprice)."</strike>원";
			echo "		</td>\n";
			echo "	</tr>\n";
		}
		echo "	<tr>\n";
		echo "		<td height=17 class=verdana2 style=\"font-weight:bold;color:#FF3243 !important\">";
		if($dicker=dickerview($specialprlist[$kk]->etctype,number_format($specialprlist[$kk]->sellprice)."원",1)) {
			echo $dicker;
		} else if(strlen($_data->proption_price)==0) {
			echo "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle>".number_format($specialprlist[$kk]->sellprice)."원";
			if (strlen($specialprlist[$kk]->option_price)!=0) echo "(기본가)";
		} else {
			echo "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle>";
			if (strlen($specialprlist[$kk]->option_price)==0) echo number_format($specialprlist[$kk]->sellprice)."원";
			else echo ereg_replace("\[PRICE\]",number_format($specialprlist[$kk]->sellprice),$_data->proption_price);
		}
		if ($specialprlist[$kk]->quantity=="0") echo soldout();
		echo "		</td>\n";
		echo "	</tr>\n";
		if($specialprlist[$kk]->consumerprice<=0) {
			echo "	<tr>\n";
			echo "		<td height=17></td>\n";
			echo "	</tr>\n";
		}
		echo "	<tr>\n";
		echo "		<td height=4></td>\n";
		echo "	</tr>\n";
		echo "	</table>\n";
		echo "	</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
	} else {
		echo "&nbsp;";
	}
	$kk++;
	########## 7 end ##########
?>
	</td>
	<td width=1 bgcolor=ffffff nowrap></td>
	<td width=25%>
<?
	########## 8 start (130*130) ##########
	if(isset($specialprlist[$kk])) {
		echo "<table width=100% border=0 cellspacing=0 cellpadding=0 align=center id=\"M".$specialprlist[$kk]->productcode."\" onmouseover=\"quickfun_show(this,'M".$specialprlist[$kk]->productcode."','')\" onmouseout=\"quickfun_show(this,'M".$specialprlist[$kk]->productcode."','none')\">\n";
		echo "<tr>\n";
		echo "	<td height=140 align=center valign=top style=padding:5,10><A HREF=\"javascript:GoItem('".$specialprlist[$kk]->productcode."')\">";
		if (strlen($specialprlist[$kk]->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$specialprlist[$kk]->tinyimage)==true) {
			echo "<img src=\"".$Dir.DataDir."shopimages/product/".urlencode($specialprlist[$kk]->tinyimage)."\" border=0 ";
			$width = getimagesize($Dir.DataDir."shopimages/product/".$specialprlist[$kk]->tinyimage);
			if ($width[0]>=$width[1] && $width[0]>=130) echo "width=130 ";
			else if ($width[1]>=130) echo "height=130 ";
		} else {
			echo "<img src=\"".$Dir."images/no_img.gif\" border=0 align=center";
		}
		echo "	></a></td>\n";
		echo "</tr>\n";
		echo "<tr><td height=\"3\" style=\"position:relative;\">".($_data->ETCTYPE["QUICKTOOLS"]!="Y"?"<script>quickfun_write('".$Dir."','M','".$specialprlist[$kk]->productcode."','".($specialprlist[$kk]->quantity=="0"?"":"1")."')</script>":"")."</td></tr>\n";
		echo "<tr>\n";
		echo "	<td align=center valign=top style=padding:0,10>\n";
		echo "	<table width=130 border=0 cellspacing=0 cellpadding=0>\n";
		echo "	<tr>\n";
		echo "		<td height=7> </td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td height=2></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td valign=top style=\"word-break:break-all;\"><A HREF=\"javascript:GoItem('".$specialprlist[$kk]->productcode."')\">".viewproductname($specialprlist[$kk]->productname,$specialprlist[$kk]->etctype,$specialprlist[$kk]->selfcode)."</a></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td height=4></td>\n";
		echo "	</tr>\n";
		if($specialprlist[$kk]->consumerprice>0) {	//소비자가
			echo "	<tr>\n";
			echo "		<td height=17 class=verdana2 style=\"color:#A7A7A7\"><img src=\"".$Dir."images/common/won_icon2.gif\" border=0 align=absmiddle><strike>".number_format($specialprlist[$kk]->consumerprice)."</strike>원";
			echo "		</td>\n";
			echo "	</tr>\n";
		}
		echo "	<tr>\n";
		echo "		<td height=17 class=verdana2 style=\"font-weight:bold;color:#FF3243 !important\">";
		if($dicker=dickerview($specialprlist[$kk]->etctype,number_format($specialprlist[$kk]->sellprice)."원",1)) {
			echo $dicker;
		} else if(strlen($_data->proption_price)==0) {
			echo "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle>".number_format($specialprlist[$kk]->sellprice)."원";
			if (strlen($specialprlist[$kk]->option_price)!=0) echo "(기본가)";
		} else {
			echo "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle>";
			if (strlen($specialprlist[$kk]->option_price)==0) echo number_format($specialprlist[$kk]->sellprice)."원";
			else echo ereg_replace("\[PRICE\]",number_format($specialprlist[$kk]->sellprice),$_data->proption_price);
		}
		if ($specialprlist[$kk]->quantity=="0") echo soldout();
		echo "		</td>\n";
		echo "	</tr>\n";
		if($specialprlist[$kk]->consumerprice<=0) {
			echo "	<tr>\n";
			echo "		<td height=17></td>\n";
			echo "	</tr>\n";
		}
		echo "	<tr>\n";
		echo "		<td height=4></td>\n";
		echo "	</tr>\n";
		echo "	</table>\n";
		echo "	</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
	} else {
		echo "&nbsp;";
	}
	$kk++;
	########## 8 end ##########
?>
	</td>
	<td width=1 bgcolor=ffffff nowrap></td>
	<td width=25%>
<?
	########## 9 start (130*130) ##########
	if(isset($specialprlist[$kk])) {
		echo "<table width=100% border=0 cellspacing=0 cellpadding=0 align=center id=\"M".$specialprlist[$kk]->productcode."\" onmouseover=\"quickfun_show(this,'M".$specialprlist[$kk]->productcode."','')\" onmouseout=\"quickfun_show(this,'M".$specialprlist[$kk]->productcode."','none')\">\n";
		echo "<tr>\n";
		echo "	<td height=140 align=center valign=top style=padding:5,10><A HREF=\"javascript:GoItem('".$specialprlist[$kk]->productcode."')\">";
		if (strlen($specialprlist[$kk]->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$specialprlist[$kk]->tinyimage)==true) {
			echo "<img src=\"".$Dir.DataDir."shopimages/product/".urlencode($specialprlist[$kk]->tinyimage)."\" border=0 ";
			$width = getimagesize($Dir.DataDir."shopimages/product/".$specialprlist[$kk]->tinyimage);
			if ($width[0]>=$width[1] && $width[0]>=130) echo "width=130 ";
			else if ($width[1]>=130) echo "height=130 ";
		} else {
			echo "<img src=\"".$Dir."images/no_img.gif\" border=0 align=center";
		}
		echo "	></a></td>\n";
		echo "</tr>\n";
		echo "<tr><td height=\"3\" style=\"position:relative;\">".($_data->ETCTYPE["QUICKTOOLS"]!="Y"?"<script>quickfun_write('".$Dir."','M','".$specialprlist[$kk]->productcode."','".($specialprlist[$kk]->quantity=="0"?"":"1")."')</script>":"")."</td></tr>\n";
		echo "<tr>\n";
		echo "	<td align=center valign=top style=padding:0,10>\n";
		echo "	<table width=130 border=0 cellspacing=0 cellpadding=0>\n";
		echo "	<tr>\n";
		echo "		<td height=7> </td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td height=2></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td valign=top style=\"word-break:break-all;\"><A HREF=\"javascript:GoItem('".$specialprlist[$kk]->productcode."')\">".viewproductname($specialprlist[$kk]->productname,$specialprlist[$kk]->etctype,$specialprlist[$kk]->selfcode)."</a></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td height=4></td>\n";
		echo "	</tr>\n";
		if($specialprlist[$kk]->consumerprice>0) {	//소비자가
			echo "	<tr>\n";
			echo "		<td height=17 class=verdana2 style=\"color:#A7A7A7\"><img src=\"".$Dir."images/common/won_icon2.gif\" border=0 align=absmiddle><strike>".number_format($specialprlist[$kk]->consumerprice)."</strike>원";
			echo "		</td>\n";
			echo "	</tr>\n";
		}
		echo "	<tr>\n";
		echo "		<td height=17 class=verdana2 style=\"font-weight:bold;color:#FF3243 !important\">";
		if($dicker=dickerview($specialprlist[$kk]->etctype,number_format($specialprlist[$kk]->sellprice)."원",1)) {
			echo $dicker;
		} else if(strlen($_data->proption_price)==0) {
			echo "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle>".number_format($specialprlist[$kk]->sellprice)."원";
			if (strlen($specialprlist[$kk]->option_price)!=0) echo "(기본가)";
		} else {
			echo "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle>";
			if (strlen($specialprlist[$kk]->option_price)==0) echo number_format($specialprlist[$kk]->sellprice)."원";
			else echo ereg_replace("\[PRICE\]",number_format($specialprlist[$kk]->sellprice),$_data->proption_price);
		}
		if ($specialprlist[$kk]->quantity=="0") echo soldout();
		echo "		</td>\n";
		echo "	</tr>\n";
		if($specialprlist[$kk]->consumerprice<=0) {
			echo "	<tr>\n";
			echo "		<td height=17></td>\n";
			echo "	</tr>\n";
		}
		echo "	<tr>\n";
		echo "		<td height=4></td>\n";
		echo "	</tr>\n";
		echo "	</table>\n";
		echo "	</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
	} else {
		echo "&nbsp;";
	}
	$kk++;
	########## 9 end ##########
?>
	</td>
	<td width=1 bgcolor=ECECEC nowrap></td>
	<td width=25%>
	<table border=0 cellspacing=5 cellpadding=0>
	<tr>
		<td height=34% valign=middle>
<?
		########## 10 start (60*60) ##########
		if(isset($specialprlist[$kk])) {
			echo "<table width=100% border=0 cellspacing=0 cellpadding=0 align=center id=\"M".$specialprlist[$kk]->productcode."\" onmouseover=\"quickfun_show(this,'M".$specialprlist[$kk]->productcode."','','row')\" onmouseout=\"quickfun_show(this,'M".$specialprlist[$kk]->productcode."','none')\">\n";
			echo "<col width=\"60\"></col>\n";
			echo "<col width=\"0\"></col>\n";
			echo "<col width=\"\"></col>\n";
			echo "<tr>\n";
			echo "	<td valign=top style=padding:5,10><A HREF=\"javascript:GoItem('".$specialprlist[$kk]->productcode."')\">";
			if (strlen($specialprlist[$kk]->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$specialprlist[$kk]->tinyimage)==true) {
				echo "<img src=\"".$Dir.DataDir."shopimages/product/".urlencode($specialprlist[$kk]->tinyimage)."\" border=0 ";
				$width = getimagesize($Dir.DataDir."shopimages/product/".$specialprlist[$kk]->tinyimage);
				if ($width[0]>=$width[1] && $width[0]>=60) echo "width=60 ";
				else if ($width[1]>=60) echo "height=60 ";
			} else {
				echo "<img src=\"".$Dir."images/no_img.gif\" border=0 align=center";
			}
			echo "	></a></td>\n";
			echo "	<td style=\"position:relative;\">".($_data->ETCTYPE["QUICKTOOLS"]!="Y"?"<script>quickfun_write('".$Dir."','M','".$specialprlist[$kk]->productcode."','".($specialprlist[$kk]->quantity=="0"?"":"1")."','row')</script>":"")."</td>";
			echo "	<td valign=top>\n";
			echo "	<table border=0 cellspacing=0 cellpadding=0 width=100%>\n";
			echo "	<tr>\n";
			echo "		<td height=5></td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td valign=top style=\"word-break:break-all;\" style='padding-right:5px'><A HREF=\"javascript:GoItem('".$specialprlist[$kk]->productcode."')\">".viewselfcode($specialprlist[$kk]->productname,$specialprlist[$kk]->selfcode)."</a></td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td height=4></td>\n";
			echo "	</tr>\n";
			if($specialprlist[$kk]->consumerprice>0) {	//소비자가
				echo "	<tr>\n";
				echo "		<td class=verdana2 style=\"color:#A7A7A7\"><img src=\"".$Dir."images/common/won_icon2.gif\" border=0 align=absmiddle><strike>".number_format($specialprlist[$kk]->consumerprice)."</strike>원";
				echo "		</td>\n";
				echo "	</tr>\n";
			}
			echo "	<tr>\n";
			echo "		<td class=verdana2 style=\"font-weight:bold;color:#FF3243 !important\">";
			if($dicker=dickerview($specialprlist[$kk]->etctype,number_format($specialprlist[$kk]->sellprice)."원",1)) {
				echo $dicker;
			} else if(strlen($_data->proption_price)==0) {
				echo "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle>".number_format($specialprlist[$kk]->sellprice)."원";
				if (strlen($specialprlist[$kk]->option_price)!=0) echo "(기본가)";
			} else {
				echo "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle>";
				if (strlen($specialprlist[$kk]->option_price)==0) echo number_format($specialprlist[$kk]->sellprice)."원";
				else echo ereg_replace("\[PRICE\]",number_format($specialprlist[$kk]->sellprice),$_data->proption_price);
			}
			if ($specialprlist[$kk]->quantity=="0") echo soldout();
			echo "		</td>\n";
			echo "	</tr>\n";
			echo "	</table>\n";
			echo "	</td>\n";
			echo "</tr>\n";
			echo "</table>\n";
		} else {
			echo "&nbsp;";
		}
		$kk++;
		########## 10 end ##########
?>
		</td>
	</tr>
	<tr>
		<td height=1 bgcolor=ECECEC nowrap></td>
	</tr>
	<tr>
		<td height=34% valign=middle>
<?
		########## 11 start (60*60) ##########
		if(isset($specialprlist[$kk])) {
			echo "<table width=100% border=0 cellspacing=0 cellpadding=0 align=center id=\"M".$specialprlist[$kk]->productcode."\" onmouseover=\"quickfun_show(this,'M".$specialprlist[$kk]->productcode."','','row')\" onmouseout=\"quickfun_show(this,'M".$specialprlist[$kk]->productcode."','none')\">\n";
			echo "<col width=\"60\"></col>\n";
			echo "<col width=\"0\"></col>\n";
			echo "<col width=\"\"></col>\n";
			echo "<tr>\n";
			echo "	<td valign=top style=padding:5,10><A HREF=\"javascript:GoItem('".$specialprlist[$kk]->productcode."')\">";
			if (strlen($specialprlist[$kk]->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$specialprlist[$kk]->tinyimage)==true) {
				echo "<img src=\"".$Dir.DataDir."shopimages/product/".urlencode($specialprlist[$kk]->tinyimage)."\" border=0 ";
				$width = getimagesize($Dir.DataDir."shopimages/product/".$specialprlist[$kk]->tinyimage);
				if ($width[0]>=$width[1] && $width[0]>=60) echo "width=60 ";
				else if ($width[1]>=60) echo "height=60 ";
			} else {
				echo "<img src=\"".$Dir."images/no_img.gif\" border=0 align=center";
			}
			echo "	></a></td>\n";
			echo "	<td style=\"position:relative;\">".($_data->ETCTYPE["QUICKTOOLS"]!="Y"?"<script>quickfun_write('".$Dir."','M','".$specialprlist[$kk]->productcode."','".($specialprlist[$kk]->quantity=="0"?"":"1")."','row')</script>":"")."</td>";
			echo "	<td valign=top>\n";
			echo "	<table border=0 cellspacing=0 cellpadding=0 width=100%>\n";
			echo "	<tr>\n";
			echo "		<td height=5></td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td valign=top style=\"word-break:break-all;\" style='padding-right:5px'><A HREF=\"javascript:GoItem('".$specialprlist[$kk]->productcode."')\">".viewselfcode($specialprlist[$kk]->productname,$specialprlist[$kk]->selfcode)."</a></td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td height=4></td>\n";
			echo "	</tr>\n";
			if($specialprlist[$kk]->consumerprice>0) {	//소비자가
				echo "	<tr>\n";
				echo "		<td class=verdana2 style=\"color:#A7A7A7\"><img src=\"".$Dir."images/common/won_icon2.gif\" border=0 align=absmiddle><strike>".number_format($specialprlist[$kk]->consumerprice)."</strike>원";
				echo "		</td>\n";
				echo "	</tr>\n";
			}
			echo "	<tr>\n";
			echo "		<td class=verdana2 style=\"font-weight:bold;color:#FF3243 !important\">";
			if($dicker=dickerview($specialprlist[$kk]->etctype,number_format($specialprlist[$kk]->sellprice)."원",1)) {
				echo $dicker;
			} else if(strlen($_data->proption_price)==0) {
				echo "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle>".number_format($specialprlist[$kk]->sellprice)."원";
				if (strlen($specialprlist[$kk]->option_price)!=0) echo "(기본가)";
			} else {
				echo "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle>";
				if (strlen($specialprlist[$kk]->option_price)==0) echo number_format($specialprlist[$kk]->sellprice)."원";
				else echo ereg_replace("\[PRICE\]",number_format($specialprlist[$kk]->sellprice),$_data->proption_price);
			}
			if ($specialprlist[$kk]->quantity=="0") echo soldout();
			echo "		</td>\n";
			echo "	</tr>\n";
			echo "	</table>\n";
			echo "	</td>\n";
			echo "</tr>\n";
			echo "</table>\n";
		} else {
			echo "&nbsp;";
		}
		$kk++;
		########## 11 end ##########
?>
		</td>
	</tr>
	<tr>
		<td height=1 bgcolor=ECECEC nowrap></td>
	</tr>
	<tr>
		<td height=34% valign=middle>
<?
		########## 12 start (60*60) ##########
		if(isset($specialprlist[$kk])) {
			echo "<table width=100% border=0 cellspacing=0 cellpadding=0 align=center id=\"M".$specialprlist[$kk]->productcode."\" onmouseover=\"quickfun_show(this,'M".$specialprlist[$kk]->productcode."','','row')\" onmouseout=\"quickfun_show(this,'M".$specialprlist[$kk]->productcode."','none')\">\n";
			echo "<col width=\"60\"></col>\n";
			echo "<col width=\"0\"></col>\n";
			echo "<col width=\"\"></col>\n";
			echo "<tr>\n";
			echo "	<td valign=top style=padding:5,10><A HREF=\"javascript:GoItem('".$specialprlist[$kk]->productcode."')\">";
			if (strlen($specialprlist[$kk]->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$specialprlist[$kk]->tinyimage)==true) {
				echo "<img src=\"".$Dir.DataDir."shopimages/product/".urlencode($specialprlist[$kk]->tinyimage)."\" border=0 ";
				$width = getimagesize($Dir.DataDir."shopimages/product/".$specialprlist[$kk]->tinyimage);
				if ($width[0]>=$width[1] && $width[0]>=60) echo "width=60 ";
				else if ($width[1]>=60) echo "height=60 ";
			} else {
				echo "<img src=\"".$Dir."images/no_img.gif\" border=0 align=center";
			}
			echo "	></a></td>\n";
			echo "	<td style=\"position:relative;\">".($_data->ETCTYPE["QUICKTOOLS"]!="Y"?"<script>quickfun_write('".$Dir."','M','".$specialprlist[$kk]->productcode."','".($specialprlist[$kk]->quantity=="0"?"":"1")."','row')</script>":"")."</td>";
			echo "	<td valign=top>\n";
			echo "	<table border=0 cellspacing=0 cellpadding=0 width=100%>\n";
			echo "	<tr>\n";
			echo "		<td height=5></td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td valign=top style=\"word-break:break-all;\" style='padding-right:5px'><A HREF=\"javascript:GoItem('".$specialprlist[$kk]->productcode."')\">".viewselfcode($specialprlist[$kk]->productname,$specialprlist[$kk]->selfcode)."</a></td>\n";
			echo "	</tr>\n";
			echo "	<tr>\n";
			echo "		<td height=4></td>\n";
			echo "	</tr>\n";
			if($specialprlist[$kk]->consumerprice>0) {	//소비자가
				echo "	<tr>\n";
				echo "		<td class=verdana2 style=\"color:#A7A7A7\"><img src=\"".$Dir."images/common/won_icon2.gif\" border=0 align=absmiddle><strike>".number_format($specialprlist[$kk]->consumerprice)."</strike>원";
				echo "		</td>\n";
				echo "	</tr>\n";
			}
			echo "	<tr>\n";
			echo "		<td class=verdana2 style=\"font-weight:bold;color:#FF3243 !important\">";
			if($dicker=dickerview($specialprlist[$kk]->etctype,number_format($specialprlist[$kk]->sellprice)."원",1)) {
				echo $dicker;
			} else if(strlen($_data->proption_price)==0) {
				echo "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle>".number_format($specialprlist[$kk]->sellprice)."원";
				if (strlen($specialprlist[$kk]->option_price)!=0) echo "(기본가)";
			} else {
				echo "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle>";
				if (strlen($specialprlist[$kk]->option_price)==0) echo number_format($specialprlist[$kk]->sellprice)."원";
				else echo ereg_replace("\[PRICE\]",number_format($specialprlist[$kk]->sellprice),$_data->proption_price);
			}
			if ($specialprlist[$kk]->quantity=="0") echo soldout();
			echo "		</td>\n";
			echo "	</tr>\n";
			echo "	</table>\n";
			echo "	</td>\n";
			echo "</tr>\n";
			echo "</table>\n";
		} else {
			echo "&nbsp;";
		}
		$kk++;
		########## 12 end ##########
?>
		</td>
	</tr>
	</table>
	</td>
</tr>
</table>