<?php
require_once dirname(__FILE__).'/BodyMessageValidator.php';
/**
 * 
 * @author kblee
 *
 */
class VBankBulkBodyMessageValidator implements BodyMessageValidator{
	
	/**
	 * 
	 */
	public function VBankBulkBodyMessageValidator(){
		
	}
	
	/**
	 * 
	 * @param $mdto
	 */
	public function validate($mdto){
		// ��������Աݸ�����
		/*
		if($mdto->getParameter(VBANK_EXPIRE_DATE) == null || $mdto->getParameter(VBANK_EXPIRE_DATE) == ""){
			if(LogMode::isAppLogable()){
				$logJournal = NicePayLogJournal::getInstance();
				$logJournal->errorAppLog("��������Աݸ����� �̼��� �����Դϴ�.");
			}
			throw new ServiceException("V701","��������Աݸ����� �̼��� �����Դϴ�.");
		}*/
	}	
		
}

?>
