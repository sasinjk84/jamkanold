<?
	$Dir="../";
	include_once($Dir."lib/init.php");
	include_once($Dir."lib/lib.php");
	include_once($Dir."lib/class/pages.php");
	INCLUDE ("access.php");

	$curpage=(empty($_GET['page']))?1:trim($_GET['page']);
	$listnum = 20; // ����¡ ����Ʈ��

	//_pr($_POST);

	extract($_GET, EXTR_SKIP);

	if( $type == "search" ) {

		$srchDate = $date_year.( $date_month=="ALL" ? ($month!="all" ? str_pad($month, 2, "0", STR_PAD_LEFT) : "" ) : $date_month ).($day!="all"?str_pad($day, 2, "0", STR_PAD_LEFT):"");
		$prtDate = $date_year."�� ".( $date_month=="ALL" ? ($month!="all" ? str_pad($month, 2, "0", STR_PAD_LEFT)."�� " : "" ) : $date_month."�� " ).($day!="all"?str_pad($day, 2, "0", STR_PAD_LEFT)."��":"");

		$SQL = "
			SELECT
				P.vender, P.productcode, P.productname, COUNT(P.productcode) as orderCnt, SUM(P.quantity) as productSum
			FROM
				tblorderproduct as P
				LEFT OUTER JOIN tblorderinfo as O ON P.ordercode = O.ordercode
				LEFT OUTER JOIN tblmember as M ON O.id = M.id
			WHERE
				P.ordercode LIKE '".$srchDate."%'
			AND
				P.productcode < 900000000000000000
			AND
				P.deli_gbn = 'Y'
		";
		// �ɼǺ� ó�� ���� ------------------------------
			// �������
			if( $paymethod != "ALL" ) {
				$SQL .= " AND O.paymethod LIKE '".$paymethod."%' ";
			}
			// ������
			if( $loc != "ALL" ) {
				if($loc=="��Ÿ") {
					$SQL .= " AND O.loc is NULL ";
				} else {
					$SQL .= " AND O.loc = '".$loc."' ";
				}
			}
			// ����
			if( $sex != "ALL" ) {
				$SQL .= " AND M.gender = '".($sex=="M"?"1":"2")."' ";
			}
			// ȸ������
			if( $member != "ALL" ) {
				if($member=="Y") {
					$SQL .= "AND MID(P.ordercode,21,1)!='X' ";
				} else if($member=="N") {
					$SQL .= "AND MID(P.ordercode,21,1)='X' ";
				}
			}
		// �ɼǺ� ó�� ��------------------------------

		$SQL .= "
			GROUP BY P.productcode
			ORDER BY productSum DESC
		";

		$pageSQL = $SQL;

		$SQL .= "
			LIMIT ". ($listnum * ($curpage - 1)) .", ".$listnum."
		";

		//echo $SQL;

		$result = mysql_query($SQL,get_db_conn());

	} else {
		echo "�߸��� ���� �Դϴ�.";
	}

	// ����¡ ����
	$pageResult = mysql_query($pageSQL,get_db_conn());
	$totallistrowcount = mysql_num_rows($pageResult);
	$pageparam = array('page'=>1,'total_page'=>1,'links'=>'','pageblocks'=>10,
	                   'style_first'=>'',
	                   'style_prev'=>'<span class="page_prev"></span>',
	                   'style_page'=>'<span class="page_current">%u</span>', // ���� ������
	                   'style_next'=>'<span class="page_next" ></span>',
	                   'style_end'=>'',
	                   'style_pages'=>'<span class="page_basic">%u</span>', // �Ϲ� ������
	                   'style_page_sep'=>'');
?>

<link rel="stylesheet" href="style.css">
<style type="text/css">
	.listTbl{ width:96%; margin:0 auto; border-top:1px solid #ccc;  border-left:1px solid #ccc;}
	.listTbl th{ font-size:12px; border-right:1px solid #ccc;  border-bottom:1px solid #ccc; background:#efefef; padding:3px 0px}
	.listTbl td{border-right:1px solid #ccc;  border-bottom:1px solid #ccc; background:#fff; padding:5px 0px;}
</style>

<div style="text-align:center; overflow:hidden;">
	<div style="width:96%; margin:10 auto; text-align:left; font-weight:bold;"><?=$prtDate?></div>

	<table border=0 width="100%" cellpadding="0" cellspacing="0" class="listTbl" align="center">
		<tr>
			<th>��ǰ��</th>
			<th style="width:80px;">�ֹ�����</th>
			<th style="width:80px;">�Ǹż���</th>
		</tr>
		<? if ( $totallistrowcount == 0 ) { ?>
		<tr>
			<td colspan="3" style="text-align:center; height:50px;">�Ǹ� ��ǰ�� �����ϴ�.</td>
		</tr>
		<? }else{
			$odd = false;
			while($row = mysql_fetch_assoc($result) ){ 
		?>
		<tr>
			<td style="padding-left:5px;<?=$odd?'background:#fafafa':''?>"><a href='/front/productdetail.php?productcode=<?=$row['productcode']?>' target='_blank'><?=$row['productname']?></a></td>
			<td style="text-align:center;<?=$odd?'background:#fafafa':''?>"><?=$row['orderCnt']?></td>
			<td style="text-align:center;<?=$odd?'background:#fafafa':''?>"><?=$row['productSum']?></td>
		</tr>
		<?
			$odd = !$odd;
			}
		}
		?>
	</table>

	<div id="page_wrap" style="text-align:center; margin-top:10px;">
		<?
			$opt = "";
			foreach ( $_GET as $k => $v ) {
				$opt .= ($k!="page")?"&".$k."=".$v:"";
			}
			$pageLink = $_SERVER['PHP_SELF']."?page=%u".$opt; // ��ũ
			$pagePerBlock = ceil($totallistrowcount/$listnum);
			$paging = new pages($pageparam);
			$paging->_init(array('page'=>$curpage,'total_page'=>$pagePerBlock,'links'=>$pageLink,'pageblocks'=>10))->_solv();
			echo $paging->_result('fulltext');
		?>
	</div>
</div>