<?
	$Dir="../";
	include_once($Dir."lib/init.php");
	include_once($Dir."lib/lib.php");

	//옵션 클래스 2016-09-26 Seul
	include_once($Dir."lib/class/option.php");
	$optClass = new Option;

	require_once ('./config.php');

	if(!$dbconn) exit("Failed connecting to MySQL...   ");

	$queryString = $_SERVER[QUERY_STRING];
	$vars = array();
	
	foreach (explode('&', $queryString) as $pair) {
		list ($key, $value) = explode('=', $pair);
		$key = urldecode($key);
		$value = urldecode($value);
		$vars[$key][] = $value;
	}
	
	$itemIds = $vars[ITEM_ID];
	if (count($itemIds) < 1) {
		exit('ITEM_ID 는 필수입니다.');
	}

	header('Content-Type: application/xml;charset=utf-8');
	echo ('<?xml version="1.0" encoding="utf-8"?>'."\n\n");
?>
<response>

<?
	foreach ($vars[ITEM_ID] as $itemId) {
		$rows = mysql_fetch_array(mysql_query("SELECT * FROM tblproduct WHERE productcode = '" . $itemId . "'"));

		if ($rows[productcode]) {
			$id = $rows[productcode];
			$name = $rows[productname];
			$url = $goodsUrl . '?productcode=' . $id ;
			$description = $rows[content];
			$image = $imageUrl . $rows[maximage];
			$thumbImage = $thumbImageUrl . $rows[tinyimage];
			$price = $rows[sellprice];
			$quantity = $rows[quantity] ? $rows[quantity] : 10;
			
			$optionName = array();
			$optionValue = array();
		
			if (ereg("^(\[OPTG)([0-9]{4})(\])$", $rows[option1])) {
				$optcode = substr($rows[option1], 5, 4);
	
				if (strlen($optcode) > 0) {
					$result = mysql_query("SELECT * FROM tblproductoption WHERE option_code='" . $optcode . "'");
					while ($data = mysql_fetch_array($result)) {
						for ($i = 1; $i <= 10; $i++) {
							if ($data[option_value . sprintf('%02d', $i)]) $arrAddOption[] = $data[option_value . sprintf('%02d', $i)];
						}
					
					}
					mysql_free_result($result);

					foreach ($arrAddOption as $key => $value) {
						$arrOption = explode('', $value);
						$arrOption[0];
						$optionName[] = $arrOption[0];
						array_shift($arrOption);
						$optionValue[] = $arrOption;
					}

				}

			} else {
				if ($rows[option1]) {
					$arrOption1 = explode(',', $rows[option1]);
					$arrOption1[0];
					$optionName[] = $arrOption1[0];
					array_shift($arrOption1);
					$optionValue[] = $arrOption1;
				}
				if ($rows[option2]) {
					$arrOption2 = explode(',', $rows[option2]);
					$optionName[] = $arrOption2[0];
					array_shift($arrOption2);
					$optionValue[] = $arrOption2;
				}
				
			}

			$categorySequence = array('first', 'second', 'third', 'fourth');
			$category = mysql_fetch_array(mysql_query("SELECT code_name FROM tblproductcode WHERE codeA = '" . substr($id, 0, 3) . "'"));
			$subCategory = mysql_fetch_array(mysql_query("SELECT code_name FROM tblproductcode WHERE codeA = '" . substr($id, 0, 3) . "' AND codeB = '" . substr($id, 3, 3) . "' AND codeC='" . substr($id, 6, 3) . "' AND codeD='" . substr($id, 9, 3) . "'"));
			$categoryId = array(substr($id, 0, 3), substr($id, 0, 12));
			$categoryName = array($category[code_name], $subCategory[code_name]);
?>
	<item id="<?=$id?>">
		<name><![CDATA[<?=$name?>]]></name>
		<url><![CDATA[<?=$url?>]]></url>
		<description><![CDATA[<?=$description?>]]></description>
		<image><![CDATA[<?=$image?>]]></image>
		<thumb><![CDATA[<?=$thumbImage?>]]></thumb>
		<price><?=$price?></price>
		<quantity><?=$quantity?></quantity>
		<options>
<?	
	//옵션 사용여부 2016-10-04 Seul
	$optClass->setOptUse($id);

	if($optClass->optUse) {
		$optClass->setOptType($id);
		$optClass->setOptAttInfo($id);
			
		if($optClass->optType==1 && $optClass->optNormalType==0) { //조합형(일반형)옵션 중 일체형 옵션
			$optClass->setOptValInfo($id, -1);
			$optClass->setOptComInfo("allInOne", $id, $optClass->optVal, $optClass->optValIdx);

			echo('<option name="' .implode(",", $optClass->optAtt). '">');
			for($i=0, $end=count($optClass->optComText); $i<$end; $i++) {
				echo('<select><![CDATA[' .$optClass->optComText[$i]. ']]></select>');
			}
			echo('</option>');
		} else {
			for($i=0, $att_end=count($optClass->optAtt); $i<$att_end; $i++) {
				$optClass->setOptValInfo($id, $optClass->optAttIdx[$i]);

				echo('<option name="' .$optClass->optAtt[$i]. '">');
				for($j=0, $val_end=count($optClass->optVal); $j<$val_end; $j++) {
					echo('<select><![CDATA[' .$optClass->optVal[$j]. ']]></select>');
				}
				echo('</option>');
			}
		}
	}
?>
		</options>
		<category>
<? 
	if (is_array($categoryId) && count($categoryId) > 0) { 
		foreach ($categoryId as $key => $value) {
?>
			<<?=$categorySequence[$key]?> id="<?=$value?>"><![CDATA[<?=$categoryName[$key]?>]]></<?=$categorySequence[$key]?>>
<? 
		}	

	} 
?>
		</category>
	</item>
<?
		}

	} 
?>	
</response>