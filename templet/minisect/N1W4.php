<!-- N1W4 -->
<table width=100% border=0 cellspacing=0 cellpadding=0 style="table-layout:fixed">
<tr align=center valign=top>
	<td width=50%>
<?
	########## 1 start (280*280) ##########
	$kk=1;
	if(isset($specialprlist[$kk])) {
		echo "<table width=100% border=0 cellspacing=0 cellpadding=0 align=center id=\"M".$specialprlist[$kk]->productcode."\" onmouseover=\"quickfun_show(this,'M".$specialprlist[$kk]->productcode."','')\" onmouseout=\"quickfun_show(this,'M".$specialprlist[$kk]->productcode."','none')\">\n";
		echo "<tr>\n";
		echo "	<td height=290 align=center valign=top style=padding:5,10><A HREF=\"javascript:GoItem('".$specialprlist[$kk]->productcode."')\">";
		if (strlen($specialprlist[$kk]->minimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$specialprlist[$kk]->minimage)==true) {
			echo "<img src=\"".$Dir.DataDir."shopimages/product/".urlencode($specialprlist[$kk]->minimage)."\" border=0 ";
			$width = getimagesize($Dir.DataDir."shopimages/product/".$specialprlist[$kk]->minimage);
			if ($width[0]>=$width[1] && $width[0]>=280) echo "width=280 ";
			else if ($width[1]>=280) echo "height=280 ";
		} else {
			echo "<img src=\"".$Dir."images/no_img.gif\" border=0 align=center";
		}
		echo "	></a></td>\n";
		echo "</tr>\n";
		echo "<tr><td height=\"3\" style=\"position:relative;\">".($_data->ETCTYPE["QUICKTOOLS"]!="Y"?"<script>quickfun_write('".$Dir."','M','".$specialprlist[$kk]->productcode."','".($specialprlist[$kk]->quantity=="0"?"":"1")."')</script>":"")."</td></tr>\n";
		echo "<tr>\n";
		echo "	<td align=center valign=top style=padding:0,10>\n";
		echo "	<table width=280 border=0 cellspacing=0 cellpadding=0>\n";
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
		if($specialprlist[$kk]->consumerprice>0) {	//�Һ��ڰ�
			echo "	<tr>\n";
			echo "		<td height=17 class=verdana2 style=\"color:#A7A7A7\"><img src=\"".$Dir."images/common/won_icon2.gif\" border=0 align=absmiddle><strike>".number_format($specialprlist[$kk]->consumerprice)."</strike>��";
			echo "		</td>\n";
			echo "	</tr>\n";
		}
		echo "	<tr>\n";
		echo "		<td height=17 class=verdana2 style=\"font-weight:bold;color:#FF3243 !important\">";
		if($dicker=dickerview($specialprlist[$kk]->etctype,number_format($specialprlist[$kk]->sellprice)."��",1)) {
			echo $dicker;
		} else if(strlen($_data->proption_price)==0) {
			echo "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle>".number_format($specialprlist[$kk]->sellprice)."��";
			if (strlen($specialprlist[$kk]->option_price)!=0) echo "(�⺻��)";
		} else {
			echo "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle>";
			if (strlen($specialprlist[$kk]->option_price)==0) echo number_format($specialprlist[$kk]->sellprice)."��";
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
	<td width=1 bgcolor=ECECEC nowrap></td>
	<td width=50% height=100% valign=middle>
	<table width=100% height=100% border=0 cellspacing=5 cellpadding=0>
	<tr>
		<td height=25% valign=middle>
<?
		########## 2 start (90*90) ##########
		if(isset($specialprlist[$kk])) {
			echo "<table width=100% border=0 cellspacing=0 cellpadding=0 align=center id=\"M".$specialprlist[$kk]->productcode."\" onmouseover=\"quickfun_show(this,'M".$specialprlist[$kk]->productcode."','','row')\" onmouseout=\"quickfun_show(this,'M".$specialprlist[$kk]->productcode."','none')\">\n";
			echo "<col width=\"90\"></col>\n";
			echo "<col width=\"0\"></col>\n";
			echo "<col width=\"\"></col>\n";
			echo "<tr>\n";
			echo "	<td valign=top style=padding:5,10><A HREF=\"javascript:GoItem('".$specialprlist[$kk]->productcode."')\">";
			if (strlen($specialprlist[$kk]->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$specialprlist[$kk]->tinyimage)==true) {
				echo "<img src=\"".$Dir.DataDir."shopimages/product/".urlencode($specialprlist[$kk]->tinyimage)."\" border=0 ";
				$width = getimagesize($Dir.DataDir."shopimages/product/".$specialprlist[$kk]->tinyimage);
				if ($width[0]>=$width[1] && $width[0]>=90) echo "width=90 ";
				else if ($width[1]>=90) echo "height=90 ";
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
			echo "		<td height=13> </td>\n";
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
			if($specialprlist[$kk]->consumerprice>0) {	//�Һ��ڰ�
				echo "	<tr>\n";
				echo "		<td height=17 class=verdana2 style=\"color:#A7A7A7\"><img src=\"".$Dir."images/common/won_icon2.gif\" border=0 align=absmiddle><strike>".number_format($specialprlist[$kk]->consumerprice)."</strike>��";
				echo "		</td>\n";
				echo "	</tr>\n";
			}
			echo "	<tr>\n";
			echo "		<td height=17 class=verdana2 style=\"font-weight:bold;color:#FF3243 !important\">";
			if($dicker=dickerview($specialprlist[$kk]->etctype,number_format($specialprlist[$kk]->sellprice)."��",1)) {
				echo $dicker;
			} else if(strlen($_data->proption_price)==0) {
				echo "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle>".number_format($specialprlist[$kk]->sellprice)."��";
				if (strlen($specialprlist[$kk]->option_price)!=0) echo "(�⺻��)";
			} else {
				echo "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle>";
				if (strlen($specialprlist[$kk]->option_price)==0) echo number_format($specialprlist[$kk]->sellprice)."��";
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
	</tr>
	<tr><td height=1 bgcolor=ECECEC nowrap></td></tr>
	<tr>
		<td height=25% valign=middle>
<?
		########## 3 start (90*90) ##########
		if(isset($specialprlist[$kk])) {
			echo "<table width=100% border=0 cellspacing=0 cellpadding=0 align=center id=\"M".$specialprlist[$kk]->productcode."\" onmouseover=\"quickfun_show(this,'M".$specialprlist[$kk]->productcode."','','row')\" onmouseout=\"quickfun_show(this,'M".$specialprlist[$kk]->productcode."','none')\">\n";
			echo "<col width=\"90\"></col>\n";
			echo "<col width=\"0\"></col>\n";
			echo "<col width=\"\"></col>\n";
			echo "<tr>\n";
			echo "	<td valign=top style=padding:5,10><A HREF=\"javascript:GoItem('".$specialprlist[$kk]->productcode."')\">";
			if (strlen($specialprlist[$kk]->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$specialprlist[$kk]->tinyimage)==true) {
				echo "<img src=\"".$Dir.DataDir."shopimages/product/".urlencode($specialprlist[$kk]->tinyimage)."\" border=0 ";
				$width = getimagesize($Dir.DataDir."shopimages/product/".$specialprlist[$kk]->tinyimage);
				if ($width[0]>=$width[1] && $width[0]>=90) echo "width=90 ";
				else if ($width[1]>=90) echo "height=90 ";
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
			echo "		<td height=13> </td>\n";
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
			if($specialprlist[$kk]->consumerprice>0) {	//�Һ��ڰ�
				echo "	<tr>\n";
				echo "		<td height=17 class=verdana2 style=\"color:#A7A7A7\"><img src=\"".$Dir."images/common/won_icon2.gif\" border=0 align=absmiddle><strike>".number_format($specialprlist[$kk]->consumerprice)."</strike>��";
				echo "		</td>\n";
				echo "	</tr>\n";
			}
			echo "	<tr>\n";
			echo "		<td height=17 class=verdana2 style=\"font-weight:bold;color:#FF3243 !important\">";
			if($dicker=dickerview($specialprlist[$kk]->etctype,number_format($specialprlist[$kk]->sellprice)."��",1)) {
				echo $dicker;
			} else if(strlen($_data->proption_price)==0) {
				echo "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle>".number_format($specialprlist[$kk]->sellprice)."��";
				if (strlen($specialprlist[$kk]->option_price)!=0) echo "(�⺻��)";
			} else {
				echo "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle>";
				if (strlen($specialprlist[$kk]->option_price)==0) echo number_format($specialprlist[$kk]->sellprice)."��";
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
	</tr>
	<tr><td height=1 bgcolor=ECECEC nowrap></td></tr>
	<tr>
		<td height=25% valign=middle>
<?
		########## 4 start (90*90) ##########
		if(isset($specialprlist[$kk])) {
			echo "<table width=100% border=0 cellspacing=0 cellpadding=0 align=center id=\"M".$specialprlist[$kk]->productcode."\" onmouseover=\"quickfun_show(this,'M".$specialprlist[$kk]->productcode."','','row')\" onmouseout=\"quickfun_show(this,'M".$specialprlist[$kk]->productcode."','none')\">\n";
			echo "<col width=\"90\"></col>\n";
			echo "<col width=\"0\"></col>\n";
			echo "<col width=\"\"></col>\n";
			echo "<tr>\n";
			echo "	<td valign=top style=padding:5,10><A HREF=\"javascript:GoItem('".$specialprlist[$kk]->productcode."')\">";
			if (strlen($specialprlist[$kk]->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$specialprlist[$kk]->tinyimage)==true) {
				echo "<img src=\"".$Dir.DataDir."shopimages/product/".urlencode($specialprlist[$kk]->tinyimage)."\" border=0 ";
				$width = getimagesize($Dir.DataDir."shopimages/product/".$specialprlist[$kk]->tinyimage);
				if ($width[0]>=$width[1] && $width[0]>=90) echo "width=90 ";
				else if ($width[1]>=90) echo "height=90 ";
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
			echo "		<td height=13> </td>\n";
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
			if($specialprlist[$kk]->consumerprice>0) {	//�Һ��ڰ�
				echo "	<tr>\n";
				echo "		<td height=17 class=verdana2 style=\"color:#A7A7A7\"><img src=\"".$Dir."images/common/won_icon2.gif\" border=0 align=absmiddle><strike>".number_format($specialprlist[$kk]->consumerprice)."</strike>��";
				echo "		</td>\n";
				echo "	</tr>\n";
			}
			echo "	<tr>\n";
			echo "		<td height=17 class=verdana2 style=\"font-weight:bold;color:#FF3243 !important\">";
			if($dicker=dickerview($specialprlist[$kk]->etctype,number_format($specialprlist[$kk]->sellprice)."��",1)) {
				echo $dicker;
			} else if(strlen($_data->proption_price)==0) {
				echo "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle>".number_format($specialprlist[$kk]->sellprice)."��";
				if (strlen($specialprlist[$kk]->option_price)!=0) echo "(�⺻��)";
			} else {
				echo "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle>";
				if (strlen($specialprlist[$kk]->option_price)==0) echo number_format($specialprlist[$kk]->sellprice)."��";
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
	<tr><td height=1 bgcolor=ECECEC nowrap></td></tr>
	<tr>
		<td height=25% valign=middle>
<?
		########## 5 start (90*90) ##########
		if(isset($specialprlist[$kk])) {
			echo "<table width=100% border=0 cellspacing=0 cellpadding=0 align=center id=\"M".$specialprlist[$kk]->productcode."\" onmouseover=\"quickfun_show(this,'M".$specialprlist[$kk]->productcode."','','row')\" onmouseout=\"quickfun_show(this,'M".$specialprlist[$kk]->productcode."','none')\">\n";
			echo "<col width=\"90\"></col>\n";
			echo "<col width=\"0\"></col>\n";
			echo "<col width=\"\"></col>\n";
			echo "<tr>\n";
			echo "	<td valign=top style=padding:5,10><A HREF=\"javascript:GoItem('".$specialprlist[$kk]->productcode."')\">";
			if (strlen($specialprlist[$kk]->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$specialprlist[$kk]->tinyimage)==true) {
				echo "<img src=\"".$Dir.DataDir."shopimages/product/".urlencode($specialprlist[$kk]->tinyimage)."\" border=0 ";
				$width = getimagesize($Dir.DataDir."shopimages/product/".$specialprlist[$kk]->tinyimage);
				if ($width[0]>=$width[1] && $width[0]>=90) echo "width=90 ";
				else if ($width[1]>=90) echo "height=90 ";
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
			echo "		<td height=13> </td>\n";
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
			if($specialprlist[$kk]->consumerprice>0) {	//�Һ��ڰ�
				echo "	<tr>\n";
				echo "		<td height=17 class=verdana2 style=\"color:#A7A7A7\"><img src=\"".$Dir."images/common/won_icon2.gif\" border=0 align=absmiddle><strike>".number_format($specialprlist[$kk]->consumerprice)."</strike>��";
				echo "		</td>\n";
				echo "	</tr>\n";
			}
			echo "	<tr>\n";
			echo "		<td height=17 class=verdana2 style=\"font-weight:bold;color:#FF3243 !important\">";
			if($dicker=dickerview($specialprlist[$kk]->etctype,number_format($specialprlist[$kk]->sellprice)."��",1)) {
				echo $dicker;
			} else if(strlen($_data->proption_price)==0) {
				echo "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle>".number_format($specialprlist[$kk]->sellprice)."��";
				if (strlen($specialprlist[$kk]->option_price)!=0) echo "(�⺻��)";
			} else {
				echo "<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle>";
				if (strlen($specialprlist[$kk]->option_price)==0) echo number_format($specialprlist[$kk]->sellprice)."��";
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
	</table>
	</td>
</tr>
</table>
