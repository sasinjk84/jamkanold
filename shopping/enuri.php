<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
@include_once($Dir."lib/venderlib.php");
include_once($Dir."lib/shopdata.php");

$engine_num = "enuri"; //���������� ��ȣ
function ReadEngine($file) {
	$filename = DirPath.DataDir."shopimages/etc/".$file;

	if(file_exists($filename)==true) {
		if($fp=@fopen($filename, "r")) {
			$szdata=fread($fp, filesize($filename));
			fclose($fp);
			$engine=unserialize($szdata);
		}
	}
	return $engine;
}
$engineval = ReadEngine("engineinfo.db");

if($engineval[$engine_num] == "checked") {
	if($code>0) {
		if((int)$page==0)
			$page=1;
	
		$sql = "SELECT COUNT(*) AS allcount FROM tblproduct ";
		$sql.= "WHERE display='Y' ";
		$sql.= "AND (quantity IS NULL OR quantity > 0) ";
		$sql.= "AND productcode LIKE '".(substr($code,3,6)=="000"?substr($code,0,3):substr($code,0,6))."%' ";
		$tresult=mysql_query($sql,get_db_conn());
		$rowcnt=mysql_fetch_object($tresult);
		$total = $rowcnt->allcount;
		mysql_free_result($tresult);

		$list_num = 1000;
		$limit_start = ($page-1)*$list_num;
		$limit_end = $list_num;

		$sql = "SELECT productcode, productname, sellprice, quantity, production, ";
		$sql.= "deli, deli_price, vender, tinyimage, minimage, maximage, madein, reserve, reservetype, regdate, opendate FROM tblproduct ";
		$sql.= "WHERE display='Y' ";
		$sql.= "AND (quantity IS NULL OR quantity > 0) ";
		$sql.= "AND productcode LIKE '".(substr($code,3,6)=="000"?substr($code,0,3):substr($code,0,6))."%' ";
		$sql.= "LIMIT ".$limit_start.", ".$limit_end."";
		$presult=mysql_query($sql,get_db_conn());
	
		if($total>0)
		{
			echo "
<HTML>
<HEAD>
<TITLE>������ ���������� ī�װ�</TITLE>
</HEAD>
<BODY topmargin='30'>
<table border=\"0\" cellspacing=\"1\" cellpadding=\"10\" bgcolor=\"white\" width=\"600\" align='center'>
<tr><td align=center>��<b>������ ���������� ��ǰ</b></td></tr>
</table>
<center>��ǰ�� : ".$total." ��</center>
<table border=\"0\" cellspacing=\"1\" cellpadding=\"1\" bgcolor=\"black\" width=\"600\" align='center'>
	<tr align=\"center\" bgcolor=\"EDEDED\">
		<td width=\"25\" height=\"24\" align=\"center\">��ȣ</td>
		<td width=\"180\" height=\"24\" align=\"center\">��ǰ��</td>
		<td width=\"40\" height=\"24\" align=\"center\">����</td>
		<td width=\"35\" height=\"24\" align=\"center\">���<br>����</td>
		<td width=\"50\" height=\"24\" align=\"center\">���</td>
		<td width=\"90\" height=\"24\" align=\"center\">����ǰ�̹���</td>
		<td width=\"30\" height=\"24\" align=\"center\">����<br>���� <br></td>
		<td width=\"30\" height=\"24\" align=\"center\">��꼭</td>
		<td width=\"50\" height=\"24\" align=\"center\">������</td>
		<td width=\"30\" height=\"24\" align=\"center\">��ǰ�ڵ�</td>
		<td width=\"50\" height=\"24\" align=\"center\">������<br>�Һ�</td>
	</tr>\n";
		
			if(getVenderUsed()==true) {
				$sql = "SELECT vender, deli_price, deli_mini FROM tblvenderinfo ";
				$result=mysql_query($sql,get_db_conn());
				while($row=mysql_fetch_object($result)) {
						$vender_deli_price[$row->vender] = $row->deli_price;
						$vender_deli_mini[$row->vender] = $row->deli_mini;
				}
				mysql_free_result($result);
			}
			
			$i=1;
			while($row=mysql_fetch_object($presult)) {
				$itemcode 		= $row->productcode;
				$modelname		= strip_tags($row->productname);
				$modelname		= preg_replace("(\\t|\\n|\\r|\^|\||%|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��)", "", $modelname);
				$price			= $row->sellprice;
				$productcom		= strip_tags($row->production);
				$productcom		= preg_replace("(\\t|\\n|\\r|\^|\||%|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��)", "", $productcom);
				$url			= "http://".$shopurl."?productcode=".$row->productcode;
				$coupon			= "";
				$GoodsImg_Url	= "http://".$shopurl.DataDir."shopimages/product/".$row->tinyimage;
				$GoodsImg_Url1	= "http://".$shopurl.DataDir."shopimages/product/".$row->minimage;
				$GoodsImg_Url2	= "http://".$shopurl.DataDir."shopimages/product/".$row->maximage;
				$madein			= strip_tags($row->madein);
				$madein			= preg_replace("(\\t|\\n|\\r|\^|\||%|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��|��)", "", $madein);
				$reserve		= getReserveConversion($row->reserve,$row->reservetype,$row->sellprice,"Y");
				$regdate		= str_replace("-", "", substr($row->regdate,0,10));
				$opendate		= $row->opendate;
				
				$BesongBi		= "����";
				if($row->deli=="N" && $row->deli_price == "0") {
					if($row->vender>0) {
						if($vender_deli_price[$row->vender]>0) {
							if($vender_deli_mini[$row->vender]>0) {
								$BesongBi	= $vender_deli_mini[$row->vender]."���̻󹫷�";
							} else {
								$BesongBi	= "��ۺ� ".$vender_deli_price[$row->vender]."��";
							}
						} else if($vender_deli_price[$row->vender]=="-9") {
							$BesongBi	= "����";
						}
					} else {
						if($_data->deli_basefee>0) {
							if($_data->deli_miniprice>0) {
								$BesongBi	= $_data->deli_miniprice."���̻󹫷�";
							} else {
								$BesongBi	= "��ۺ� ".$_data->deli_basefee."��";
							}
						} else if($_data->deli_basefee == "-9") {
							$BesongBi	= "����";
						}
					}
				} else if($row->deli_price>0) {
					$BesongBi	= "��ۺ� ".$row->deli_price."��";
				} else if($row->deli=="G" && $row->deli_price == "0") {
					$BesongBi	= "����";
				}
				echo "\t<tr align=\"center\" bgcolor=\"#FFFFFF\">
		<td>".$i."</td>
        <td style=\"word-break:break-all;\"><a href='".$url."'>".trim($modelname)."</a></td>
        <td>".trim($price)."</td>
        <td>��� ����</td>
        <td>".$BesongBi."</td>
        <td style=\"word-break:break-all;\">".trim($GoodsImg_Url2)."</td>
        <td></td>
        <td></td>
        <td>".trim($productcom)."</td>
        <td>".trim($itemcode)."</td>
		<td></td>
	</tr>";
				$i++;
			}
			mysql_free_result($presult);
			echo "
</table>
<table border=\"0\" cellspacing=\"1\" cellpadding=\"10\" bgcolor=\"white\" width=\"95%\" align='center'>
	<tr>
		<td align='center'>";
			$paging_count = ceil($total/$list_num);
			for($i=1; $i<=$paging_count; $i++) {
				echo " <a href='http://".$shopurl."shopping/enuri.php?code=".$code."&page=".$i."'>".$i."</a>";
			}
			echo "</td>
	</tr>
</table>";
		}
	} else {
		$sql = "SELECT codeA,codeB,codeC,codeD,code_name FROM tblproductcode ";
		$sql.= "WHERE type LIKE 'L%' ";
		$sql.= "AND codeC = '000' AND codeD = '000' ";
		$sql.= "ORDER BY codeA, codeB";
		$result=mysql_query($sql,get_db_conn());
		
		while($row=mysql_fetch_object($result)) {
			if($row->codeB == "000")
				$codeval1[$row->codeA]=$row->code_name;
			else
				$codeval2[$row->codeA][$row->codeB]=$row->code_name;
		}
		echo "
<HTML>
<HEAD>
<TITLE>������ ���������� ī�װ�</TITLE>
</HEAD>
<BODY topmargin='30'>
<table border=\"0\" cellspacing=\"1\" cellpadding=\"10\" bgcolor=\"white\" width=\"90%\" align='center'>
<tr><td align=\"center\">�� <b>������ ���������� ī�װ�</b></td></tr>
</table>
<table border=\"0\" cellspacing=\"1\" cellpadding=\"5\" bgcolor=\"black\" width=\"91%\" align='center'>
	<tr bgcolor=\"#ededed\">
		<th width=60 align=center>��з�</th>
		<th>�ߺз�</th>
	</tr>\n";
		
		while (list ($key, $val) = @each ($codeval1)) {
			echo "\t<tr bgcolor='white'>
		<td align=center><a href='http://".$shopurl."shopping/enuri.php?code=".$key."000000000'>".$val."</a></td>
		<td>";
			$i=0;
			while (list ($key1, $val1) = @each($codeval2[$key])) {
				if($i==0) {
					echo "<a href='http://".$shopurl."shopping/enuri.php?code=".$key.$key1."000000'>".$val1."</a>";
				} else {
					echo " | <a href='http://".$shopurl."shopping/enuri.php?code=".$key.$key1."000000'>".$val1."</a>";
				}
				$i++;
			}
			if($i==0)
				echo "�ߺз�����";
			echo "</td>
	</tr>";
		}
		mysql_free_result($result);

		echo "
	</table>
	</BODY>
	</HTML>\n";
	}
} else {
	echo "<html><head><title></title></head><body onload=\"alert('���� �������� �̻�� ������ �Դϴ�.');window.close();\"></body></html>";exit;
}
?>