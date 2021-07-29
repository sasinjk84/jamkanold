<?php
require_once dirname(__FILE__).'/WebParamGather.php';

/**
 * 
 * @author kblee
 *
 */
class CashReceiptWebParamGather implements WebParamGather{
	
	/**
	 * Default Constructor
	 */
	public function CashReceiptWebParamGather(){
		
	}
	
	/**
	 * 
	 * @param $request
	 */
	public function gather($request) {
		$webParam = new WebMessageDTO();
		// �ֹ� ��ȣ,�޴��� ��ȣ �ĺ� ��
		$webParam->setParameter(RECEIPT_TYPE_NO,$request["ReceiptTypeNo"]);
		// �ҵ���� ����
		$webParam->setParameter(RECEIPT_TYPE,$request["ReceiptType"]);
		// �����
		$webParam->setParameter(RECEIT_SERVICE_AMT,$request["ReceiptServiceAmt"]);
		//�ΰ���ġ��
		$webParam->setParameter(RECEIT_VAT,$request["ReceiptVAT"]);
		
		//�ΰ���ġ��
		$webParam->setParameter(RECEIT_SUPPLY_AMT,$request["ReceiptSupplyAmt"]);

		//���� ������ ��û �ݾ�
		$webParam->setParameter(RECEIPT_AMT,$request["ReceiptAmt"]);
				
		//���� ������ ����� ����ڹ�ȣ
		$webParam->setParameter(RECEIT_SUB_NUM,$request["ReceiptSubNum"]);
		
		return $webParam;
	}
	
}
?>
