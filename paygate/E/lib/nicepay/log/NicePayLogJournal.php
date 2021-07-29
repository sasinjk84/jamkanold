<?php
require_once dirname(__FILE__).'/../core/Constants.php';
require_once dirname(__FILE__).'/NiceLog.php';

/**
 * 
 * @author kblee
 *
 */
class NicePayLogJournal{
	
	/**
	 * 
	 * @var $instance
	 */
	private static $instance;
	
	/** The event log path. */
	private $logPath;
	
	
	/** The event logger. */
	private $eventLogger;

	private $appLogger;
	
	/**
	 * Create a MnBankLogJournal instance.
	 */
	private function NicePayLogJournal(){
		
	}
	
	/**
	 * get a Single MnBankLogJournal instance.
	 */
	public static function getInstance(){
		if(!isset(NicePayLogJournal::$instance)){
			NicePayLogJournal::$instance = new NicePayLogJournal();
		}
		return NicePayLogJournal::$instance;
	}
	
	/**
	 * 
	 * @param  $eventLogPath
	 */
	public function setLogDirectoryPath($logPath){
		$this->logPath = $logPath;
	}
	
	/**
	 * 
	 */
	public function configureNicePayLog4PHP(){
		if(!isset($this->appLogger) || !isset($this->eventLogger)){
			try {
				
				
				$this->appLogger = new NICELog("DEBUG","application");
				if($this->appLogger->StartLog($this->logPath)){
					
					
				}else{
					echo "�α� ��� ���� ����";
				}

				$this->eventLogger = new NICELog("INFO","event");
				if($this->eventLogger->StartLog($this->logPath)){
					
					
				}else{
					echo "�α� ��� ���� ����";
				}
				
			} catch (Exception $e) {
				echo "Exception  : Log Configuration Error";
			}
			
		}
		
	}
	
	/**
	 * Write journal.
	 * 
	 * @param dto the dto
	 */
	public function writeEventLog($dto){

		$serviceMode  = $dto->getParameter(SERVICE_MODE);
		$logString = "";
		
		//StringBuffer logBuffer = new StringBuffer(); 
		$resultCode = $dto->getParameter("ResultCode");
		$reqDate = date("Ymd");
		$reqTime = date("His");
		
		if(PAY_SERVICE_CODE == $serviceMode){ //��������
			/**
			 *  ����
	         *  P|������������|������û��|������û�ð�|��������|������ǰ��|�����ݾ�|USER_ID|�����ڵ�|����޽���
			 */
			$logString.="P|";
			if( ("3001" == $resultCode) || ("4000" == $resultCode) || ("4001" == $resultCode) || ("A000" == $resultCode)){ //��������
				$logString.="TE|";
			}else{ //��������
				$logString.="TF|";
			}
			$logString.=$reqDate."|";
			$logString.=$reqTime."|";
			$logString.= ($dto->getParameter(PAY_METHOD)==null?"":trim($dto->getParameter(PAY_METHOD)))."|";
			$logString.= ($dto->getParameter(GOODS_NAME)==null?"":trim($dto->getParameter(GOODS_NAME)))."|";
			$logString.= ($dto->getParameter(GOODS_AMT)==null?"0":trim($dto->getParameter(GOODS_AMT)))."|";
			$logString.= ($dto->getParameter(MALL_USER_ID)==null?"":trim($dto->getParameter(MALL_USER_ID)))."|";
			$logString.=trim($resultCode)."|";
			$logString.=($dto->getParameter("ResultMsg")==null?"":trim($dto->getParameter("ResultMsg")));
		}else if(CANCEL_SERVICE_CODE == $serviceMode){                                                          // �������
			/**
			 *  ���
	         *  C|��Ҽ�������|��ҿ�û��|��ҿ�û�ð�|��������|��ұݾ�|USER_ID|�����ڵ�|����޽���
			 */
			$logString.="C|";
			if(("2001" == $resultCode) || ("2005" == $resultCode)){ //��Ҽ���
				$logString.="TE|";
			}else{ //��������
				$logString.="TF|";
			}
			$logString.=$reqDate."|";
			$logString.=$reqTime."|";
			
			$logString.= ($dto->getParameter(PAY_METHOD)==null?"":trim($dto->getParameter(PAY_METHOD)))."|";
			$logString.= ($dto->getParameter(GOODS_NAME)==null?"":trim($dto->getParameter(GOODS_NAME)))."|";
			$logString.= ($dto->getParameter(GOODS_AMT)==null?"0":trim($dto->getParameter(GOODS_AMT)))."|";
			$logString.= ($dto->getParameter(MALL_USER_ID)==null?"":trim($dto->getParameter(MALL_USER_ID)))."|";
			$logString.=trim($resultCode)."|";
			$logString.=($dto->getParameter("ResultMsg")==null?"":trim($dto->getParameter("ResultMsg")));
		}
		if(isset($logString) && strlen($logString) > 0){	
			$this->eventLogger->WriteLog($logString);
			$this->eventLogger->CloseLog("");
		}
	}

	public function writeAppLog($string){
		$this->appLogger->WriteLog($string);
	}

	public function errorAppLog($string){
		$this->appLogger->WriteLog($string);
	}

	public function warnAppLog($string){
		$this->appLogger->WriteLog($string);
	}
	
	
}
?>
