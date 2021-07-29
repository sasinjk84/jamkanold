<?php
require_once dirname(__FILE__).'/BodyMessageValidator.php';
/**
 * 
 * @author kblee
 *
 */
class VBankBodyMessageValidator implements BodyMessageValidator{
	
	/**
	 * 
	 */
	public function VBankBodyMessageValidator(){
		
	}
	
	/**
	 * 
	 * @param $mdto
	 */
	public function validate($mdto){
		// ��������Աݸ�����
		if($mdto->getParameter(VBANK_EXPIRE_DATE) == null || $mdto->getParameter(VBANK_EXPIRE_DATE) == ""){
			if(LogMode::isAppLogable()){
				$logJournal = NicePayLogJournal::getInstance();
				$logJournal->errorAppLog("��������Աݸ����� �̼��� �����Դϴ�.");
			}
			throw new ServiceException("V701","��������Աݸ����� �̼��� �����Դϴ�.");
		}
	}	
		
}

?>
