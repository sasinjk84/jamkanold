<?php
/*
**************************************************************************************
* Maker : ������(m4700q@nate.com)														 *
* Create Date : 2013.2.04															 *
* Update Date : 2013.2.06															 *
* Create Purpose : ���������� backup �� ����ó���� ���� Ŭ����								 *
* Program Version : 2.0																 *
* Program Name : getmall backup Ŭ����											 	 *
* Update ���� :	�������� FTP ���ε� �� ���� ��� �߰�								 		 *
**************************************************************************************
*/ 
class backup {
	private $_error = 0;
	private $_dbl_no = null;
	private $_dbl_ftpnm_ftp = null;
	private $_path = null;
	private $_backpath = null;
	private $_backuppage = array();
	private $_maxcnt = 10;		//�ӽ÷�100��
	private $db = null;
	private $_ShopInfo = null;
	private $_zipfile;
	private $_dbl_ftp = array();
	private $_errorInfo = array();
	private $_addDir = null;
	private $_arr_dir_list = array();	//����ҵ��丮�����迭
	private $_arr_dir_size = 0;			//����ҵ��丮������
	
	public function __construct() {
		global $Dir;
		global $backuppage;
		global $_ShopInfo;
		include_once("MySQL.php");
		$this->db = new MySQL();

		$this->_path	 = $Dir.DataDir."htm/";
		$this->_backpath = $Dir.DataDir."design_backup/";
		$this->_backuppage	= $backuppage;
		$this->_ShopInfo	= $_ShopInfo;
	}

	//create�� zip���Ͽ� ���� ��ų ������ ����
	public function setAddDir($arg) {
		if($arg || is_array($arg)) {
			$this->_addDir = $arg;
		} else {
			return false;
		}
	}

	public function design_backup($data) {
		if(!$data || !is_array($data)) return false;

		//���list�� ����
		$sql = array(
			'dbl_type'				=> $data['dbl_type'],
			'dbl_subject'			=> $data['dbl_subject'],
			'id'			        => $this->_ShopInfo->getId()
		);
		if($this->db->insert('tbldesign_backup_list', $sql)) $this->_dbl_no = @mysql_insert_id();
		else $this->setErrorInfo('[Error] Not Insert : tbldesign_backup_list');

		if($this->_dbl_no) {
			$this->_dbl_ftpnm_ftp	= $this->_ShopInfo->getId()."_".$this->_dbl_no."_".date("YmdHis").".zip";
			if(!$this->db->update('tbldesign_backup_list', array('dbl_ftpnm_ftp'=> $this->_dbl_ftpnm_ftp),'dbl_no='. $this->_dbl_no)) $this->setErrorInfo('[Error] Not Update : tbldesign_backup_list');
			//�������� �ߺ����������� pk �ٿ��� �ٽ� update

			if($this->_setInsert()) {
				$this->_setHtmWrite();	//htm���ϻ���
				$zipfile = $this->_pclzip('../data/design_backup/'.$this->_dbl_ftpnm_ftp);

				$zipdir = array(
							'../data/shopimages/etc/',
							'../data/htm/'
						);
				$zipdir = array_merge($zipdir,$this->getdir_list('../data/design/'));
				$create = $zipfile->create($zipdir,PCLZIP_OPT_REMOVE_PATH,"../data/"); 
				//$zipfile->delete(PCLZIP_OPT_BY_NAME,'design/skin/');	//design/skin/������ ������ ����
				//�������������� ���� �ƴϸ� �׳� false return
				if($this->_addDir) {
					//$zipfile->delete(PCLZIP_OPT_BY_NAME,$this->_addDir);
					$zipfile->add($this->_addDir,PCLZIP_OPT_REMOVE_PATH,"../data/");
				} 
				
				if(empty($create)) $this->setErrorInfo('[Error] pclzip �������');
				LIB_removeAllData('../data/htm/');		//htm�� ������ .htm���� ����
			} else {
				$this->setErrorInfo('[Error] function _setInsert ����');
			}
		} else {
			$this->setErrorInfo('[Error] _dbl_no �̻���');
		}
		if($this->_error==0) return true;
		else return false;
	}


	//db����
	public function design_revert($no) {
		global $Dir;
		if(!$no) return false;

		//tgz ���� �����
		$dbl_ftpnm_ftp =$this->db->one("tbldesign_backup_list", "dbl_ftpnm_ftp", "dbl_no=".$no);
		if(!$dbl_ftpnm_ftp) {
			$this->setErrorInfo("[Error] Not tgz file");
			return false;
		}

		$zipfile = $this->_pclzip($this->_backpath.$dbl_ftpnm_ftp);
		$this->_deleteFile();
		$extract = $zipfile->extract(PCLZIP_OPT_PATH, $Dir.DataDir); 
		if($extract) {
			LIB_removeAllData($this->_path);	//htm�� ������ .htm���� ����
		}

		//��������������
		//if(!$this->db->delete("tbldesignnewpage")) $this->setErrorInfo('[Error] tbldesignnewpage Not Delete');
		$this->db->delete("tbldesignnewpage"); 
		$this->db->insertLine("tbldesignnewpage","(type, code, subject, filename, body, leftmenu) SELECT dbp_type, dbp_code, dbp_subject, dbp_filename, dbp_body, dbp_leftmenu FROM tbldesign_backup_page WHERE dbl_no =". $no);
		//$this->setErrorInfo('[Error] tbldesignnewpage Not Insert');


		//shopinfo ����		 
		$this->db->update("tblshopinfo a join tbldesign_backup_template b on b.dbl_no = ".$no,
		"a.frame_type			=  b.dbt_frame_type,
		 a.top_type				=  b.dbt_top_type, 
		 a.main_type			=  b.dbt_main_type,       
		 a.title_type			=  b.dbt_title_type,          
		 a.css					=  b.dbt_css,
		 a.quick_type			=  b.dbt_quick_type");		 

		 //���ø�2 tbldesign
		$this->db->update("tbldesign a JOIN tbldesign_backup_template b ON b.dbl_no = ".$no,
		"a.top_height	=	b.dbt_top_height"	);

		 //���� 
		$this->db->delete("tblquickmenu"); 
		$this->db->insertLine("tblquickmenu","(num, used, reg_date, design, x_size, y_size, x_to, y_to, scroll_auto, title, content) SELECT dbq_num, dbq_used, dbq_reg_date, dbq_design, dbq_x_size, dbq_y_size, dbq_x_to, dbq_y_to, dbq_scroll_auto, dbq_title, dbq_content FROM tbldesign_backup_quick WHERE dbl_no =". $no);

		$this->deletecache();
		if(!$this->setHistory("revert","������ ������ �Ϸ� �Ǿ����ϴ�.",array("dbl_no"=>$no,"dbh_mode"=>"server"))) $this->setErrorInfo('[Error] history insert(revert) ����');

		if($this->_error==0) return true;
		else return false;

	}

	private function _setInsert() {
		//if(!$this->db->insertLine("tbldesign_backup_page","(dbp_type, dbp_code, dbp_subject, dbp_filename, dbp_body, dbp_leftmenu, dbl_no ) SELECT type, code, subject, filename, body, leftmenu, '".$this->_dbl_no."' FROM tbldesignnewpage;")) $this->setErrorInfo('[Error] tbldesign_backup_page Not insert');
		//���������ε� ����
		$this->db->insertLine("tbldesign_backup_page","(dbp_type, dbp_code, dbp_subject, dbp_filename, dbp_body, dbp_leftmenu, dbl_no ) SELECT type, code, subject, filename, body, leftmenu, '".$this->_dbl_no."' FROM tbldesignnewpage;");
		
		$this->db->insertLine("tbldesign_backup_template",
			"( 
					dbt_frame_type,   
					dbt_top_type,
					dbt_main_type,
					dbt_title_type,
					dbt_css,
					dbt_quick_type,
					dbt_top_height, 
					dbl_no
				)
				SELECT 
					a.frame_type,   
					a.top_type,
					a.main_type,
					a.title_type,
					a.css,
					a.quick_type,
					b.top_height, 
					'".$this->_dbl_no."'
				FROM
					tblshopinfo a,
					tbldesign b ");

		//quick ����������
		$this->db->insertLine("tbldesign_backup_quick",
			"( 
					dbq_num,
					dbq_used,
					dbq_reg_date,   
					dbq_design,
					dbq_x_size,
					dbq_y_size,
					dbq_x_to,
					dbq_y_to,
					dbq_scroll_auto,
					dbq_title,
					dbq_content,
					dbl_no
				)
				SELECT 
					num,        
					used,       
					reg_date,   
					design,     
					x_size,     
					y_size,     
					x_to,       
					y_to,       
					scroll_auto,
					title,
					content,
					'".$this->_dbl_no."'
				FROM
					tblquickmenu ");	
		

		if($this->_addDir) { 
			unset($content_intro); 
			$content_intro = '(��Ų��������) ';
		}
		if(!$this->setHistory('backup',$content_intro.'������ ����� �Ϸ� �Ǿ����ϴ�.')) $this->setErrorInfo('[Error] history insert(backup) ����');
		

		if($this->_error == 0) return true;
		else return false;
	}

	private function _setHtmWrite() {

		$sql = $this->db->select("tbldesign_backup_page", array(
			"field"	=> "*",
			"where"	=> "dbl_no =".$this->_dbl_no
		)); 

		if($sql) {
			foreach($sql as $v) {
				unset($arrfilename);
				$dbp_body = $v[dbp_body];

				if(strstr($v[dbp_filename],"")) $arrfilename =  explode('',$v[dbp_filename]);
				if(is_array($arrfilename)) $filename = $arrfilename[0] . '_' . $arrfilename[1];
				else $filename = $v[dbp_filename];
				
				$name = $this->_chkType($this->_backuppage[$v[dbp_type]]['name']);
				$name = str_replace('{$v[dbp_type]}',$v[dbp_type],$name);
				$name = str_replace('{$v[dbp_leftmenu]}',$v[dbp_leftmenu],$name);
				$name = str_replace('{$v[dbp_code]}',$v[dbp_code],$name);
				$name = str_replace('{$filename}',$filename,$name);
				if(substr($name,0,1)=="_") $name = substr($name,1,strlen($name));
				if($this->_backuppage[$v[dbp_type]]['subject'] == 'Y') {
					$dbp_body =  $v[dbp_subject].'
######subject ���� ��������########
' . $dbp_body ;
				}

				$name .= '.htm'; 
				$fp = fopen($this->_path.$name,"w");
				fwrite($fp,$dbp_body);
				fclose($fp);
			}
		}

		$result = $this->db->row("tbldesign_backup_template", array(
			//"field"	=> "dbt_top_height, dbt_body_top,dbt_body_left, dbt_useinfo, dbt_agreement",
			"field"	=> "dbt_top_height",
			"where"	=> "dbl_no =".$this->_dbl_no
		)); 

		$arrtemplate = array();

		$arrtemplate[0]['name']		=	'topmenuall_' . $result[dbt_top_height] . '.htm';
		$arrtemplate[0]['content']	=	'';
		foreach($arrtemplate as $k => $v) {
			$fp = fopen($this->_path.$v['name'],"w");
			fwrite($fp,$v['content']);
			fclose($fp);
		}

	}
	
	private function _chkType($type) {
		if(!$type) return false;
		$arrtype = explode("_",$type);

		if(count($arrtype) > 1) {
			foreach($arrtype as $vv){
				if($vv =='type') $name .= '_{$v[dbp_type]}';
				elseif($vv =='leftmenu') $name .= '_{$v[dbp_leftmenu]}';
				elseif($vv =='code') $name .= '_{$v[dbp_code]}';
				elseif($vv =='filename') $name .= '_{$filename}';
			}
		} else {
			$name = '{$v[dbp_type]}';
		}
		return $name;
	}

	public function getMaxchk() {
		$cnt = $this->db->num("tbldesign_backup_list", "dbl_no","dbl_type='backup' and dbl_use='y' ");

		if($cnt >= $this->_maxcnt) return false;
		else return true;
	}

	public function setHistory($type,$content,$data=null) {
		if(is_array($data)) {
			if($data['dbl_no']) $no = $data['dbl_no'];
			if($data['dbh_mode']) $dbh_mode = $data['dbh_mode'];
		} else {
			$no = $this->_dbl_no;
		}

		$sql = array(
			"dbh_type"		=> $type,
			"dbh_mode"		=> $dbh_mode,
			"dbh_content"	=> $content,
			"dbl_no"		=> $no,
			"id"			=> $this->_ShopInfo->getId()
		);

		if($this->db->insert('tbldesign_backup_history', $sql)) return true;
		else return false;
	}

	public function design_delete($no) {
		global $Dir;
		$dbl_ftpnm_ftp = $this->db->one("tbldesign_backup_list", "dbl_ftpnm_ftp", "dbl_no=".$no);
	
		$this->db->update("tbldesign_backup_list", "dbl_use='n', dbl_del_data='".date("Y-m-d H:i:s")."'", "dbl_no=".$no);
		$this->db->delete("tbldesign_backup_page","dbl_no=".$no);
		$this->db->delete("tbldesign_backup_template","dbl_no=".$no);

		//optimize
		$this->db->optimize("tbldesign_backup_page");

		if(!@unlink($Dir.DataDir."design_backup/".$dbl_ftpnm_ftp)) $this->setErrorInfo('[Error] '.$Dir.DataDir."backup/".$dbl_ftpnm_ftp.' unlink ����');
		if(!$this->setHistory("delete","��������� ���� �Ǿ����ϴ�.",array("dbl_no"=>$no))) $this->setErrorInfo('[Error] history insert(delete) ����');

		if($this->_error == 0) return true;
		else return false;
	}

	//file revert ftp����
	public function setFileTgz($file) {
		return $this->_dbl_ftp = $file;
	}

	public function getFileTgz($type=null, $gubun=null) { // $gubun : 'ftp' �� ��� ����ڰ� ftp�� ���� ���ε� �� ���������� �����ؼ� ������ �� �̸� �������� ���� ����.
		if(!$type) $type = 'name';
		$ftp = $this->_dbl_ftp;
		$return = $gubun != 'ftp' ? $ftp[$type] : $ftp;
		return $return;
	}

	//ftp�� ����
	public function file_revert($type=null) { // $type : 'ftp' �� ��� ����ڰ� ftp�� ���� ���ε� �� ���������� �����ؼ� �����ϴ� ���.
		global $Dir;

		$revertDir	= $Dir.DataDir."revert/"; 
		$htmDir		= $this->_path;
		$fileName	= $type != 'ftp' ? $this->getFileTgz() : $this->getFileTgz(null, 'ftp');
		$fileDir	= $revertDir. $fileName;


		if($type != 'ftp'){ // web���� ���� �� �뷮 üũ
			if($this->getFileTgz('size')> 8000000) return 'Notsize';
			if(is_dir($revertDir) && preg_match('/\\.zip$/i',$fileDir)) move_uploaded_file($this->getFileTgz('tmp_name'), $fileDir);
			else return false;			
		}

		$zipfile = $this->_pclzip($fileDir);
		if(!$this->pclList()) return 'Notzip';
		$this->_deleteFile();
		$extract = $zipfile->extract(PCLZIP_OPT_PATH, $Dir.DataDir); 


		//���������� ���������� �����Ǿ����� Ȯ��.
		if($extract) {
			$i = 0; 
			// data/htm/ ������ .htm ������ �迭�� ��´�
			if (file_exists($htmDir)==true) {
				$d = @opendir($htmDir); 
				while ( false !== ( $file = readdir($d))) { 
					if (filetype($htmDir.$file) == 'file') {
						$ext = substr($file,strrpos($file,'.'));
						if($ext=='.htm') {
							$ReadFolder[$i] = $file; 
							$i++;
						}	
					}
				}
				closedir($d);

				//html ������ �迭�� ��´�.
				foreach($ReadFolder as $k => $v) {
					//$path = $Dir.DataDir."htm/".$v;
					$path = $htmDir.$v;
					unset($content);
					if(file_exists($path)==true){
						$fp = @fopen($path,"r");
						if($fp) {
							while (!feof($fp)) {$content.= fgets($fp, 1024);}
						}
						$htmFile[$v] = $content;
						fclose($fp);
					}
				}
				
				$this->db->delete("tbldesignnewpage");
				//htm������ DB�� insert
				foreach($htmFile as $k => $v) {
					$arrInsert	= array();
					$k		= str_replace(".htm","",$k);
					$arrkey	= explode("_",$k);
					$backconfigName			= $this->_backuppage[$arrkey[0]]['name'];
					$arrInsert['subject']	= $this->_backuppage[$arrkey[0]]['subject'];
					$info					= $this->_backuppage[$arrkey[0]]['info'];
					$arrName = explode("_",$backconfigName);

					$j = 0;
					for($i=0;$i<count($arrName);$i++) {
						$key = $arrName[$i];
						if(!$key) $key = 'designtype';	//tbldesignnewpage DB�� �ƴ� �����ϰ��
						if($arrName[$i]=='code') $infocode = $arrkey[$j];

						//filename üũ
						if(($arrName[$i]=='filename' && $arrkey[$j+1]) && $arrkey[0]!='board' ) {
							$value = $arrkey[$j].''.$arrkey[$j+1];
							$j++;
						} else {
							$value = $arrkey[$j];
						}
						$arrInsert[$key] = $value;
						$j++;
					}

					//����������
					if($arrInsert['subject']=='Y') {
						$arrBody = explode("\n", $v);
						$arrInsert['subject'] = trim($arrBody[0]);
						unset($arrBody[0],$arrBody[1]);
						$v = implode("\n",$arrBody);
					}
					//tbldesign update control
					if($arrInsert['designtype']) {
						switch($arrInsert['designtype']) {
							case 'topmenuall' : 
								$designArray['top_height'] = $arrInsert['height'];
								//$designArray['body_top'] = $v;
							break;
						}
					//tbldesignnewpage insert
					} else {
						$arrInsert['body'] = getReplacePhp($v);
						$arrInsert['body'] = str_replace(array("<?"), array( "&lt?;"), addslashes(stripslashes($arrInsert['body'])));

						$arrInsert['subject'] = getReplacePhp($arrInsert['subject']);
						$arrInsert['subject'] = str_replace(array("<?"), array( "&lt?;"), addslashes(stripslashes($arrInsert['subject'])));

						if(!$this->db->insertOnlystring('tbldesignnewpage',$arrInsert)) $this->setErrorInfo('[Error] tbldesignnewpage Not Insert ����');
						//body�� ������ǥ �Ⱥٴ� ������ �߻��ؼ� ����
					}

				}

				//tblshopinfo update
				if($arrShopinfo) $this->db->update('tblshopinfo', $arrShopinfo);

				//tbldesign update
				if($designArray) $this->db->update('tbldesign', $designArray);
				LIB_removeAllData($htmDir);	//htm�� ������ .htm���� ����
				//LIB_removeAfter_Dir($htmDir);	//htm�� ������ .htm���� ����
				@unlink($fileDir);
				$this->deletecache();
				$this->setHistory('revert','������ ������ �Ϸ� �Ǿ����ϴ�.',array('dbh_mode'=>'uplode'));
			}
		} else {
			$this->setErrorInfo('[Error] ������������');
		}

		if($this->_error==0) return 'OK';
		else return false;
	}

	//pclzip class 
	private function _pclzip($zip) {
		include ("pclzip.lib.php");
		return $this->_zipfile = new PclZip($zip);
	}

	//zip���� üũ
	public function pclList() {
		$zipfile = $this->_zipfile;

		if (($list = $zipfile->listContent()) == 0) {
			$this->setErrorInfo($zipfile->errorInfo(true));
			return false;
		}

		if (file_exists($zipfile->zipname)==true) {
			for ($i=0; $i<sizeof($list); $i++) {
				//���丮��Ģ
				if(!preg_match("/^(shopimages|design|htm)\//",$list[$i]['filename'])) {
					$this->setErrorInfo('[Error] '.$list[$i]['filename'].' �ùٸ� ���丮������ �ƴմϴ�.');
				}

				//Ȯ����üũ
				if(strstr($list[$i]['filename'],".")) {
					if(!preg_match('/\\.(gif|jpg|jpeg|bmp|png|db|htm|zip|rar|css|swf)$/i',$list[$i]['filename'])) {	//�÷��� �߰�
						$this->setErrorInfo('[Error] '.$list[$i]['filename'].' �ùٸ� Ȯ���ڰ� �ƴմϴ�.');
					}
				} 
			}
		}

		if($this->_error>0) { 
			@unlink($zipfile->zipname);
			return false;
		} 
		return true;

		
	}

	public function setErrorInfo($string) {
		$this->_errorInfo[] = $string;
		$this->_error +=1;
	}

	public function getErrorInfo() {
		$errorInfo = $this->_errorInfo;
		if(count($errorInfo)<0) return 'NoError';
		foreach($errorInfo as $k) {
			$errorStr .= $k.'<br/>'; 
		}
		return $errorStr;
	}

	//ĳ������ ����
	public function deletecache() {
		DeleteCache("tblshopinfo.cache");
		delete_cache_file("main");
		delete_cache_file("product");
		delete_cache_file("productb");
	}
	
	//zip���� �������� �� ��������
	private function _deleteFile() {
		global $Dir;
		@unlink($Dir.DataDir."design/intro.htm");	//��Ʈ������ 
	}

	//data/design/ ����� ���� skin ���������ϰ� �迭�� ��Ƽ� ����ϱ�(�뷮�����ذ�)
	public function setdir_list($URL) { 
		if(is_dir($URL)) {
			if($dh = @opendir($URL)) { 
				while(( $file = @readdir( $dh )) !== false ) { 
					if( $file == '.' || $file == ".." || substr($URL.$file,0,19)=="../data/design/skin" )	continue;	//data/design/skin/ ���丮�� ���ܽ�Ų��.
					if($file=="Thumbs.db") continue;
					else {
						$this->_arr_dir_list[] = $URL.$file;
					}
				} 
				closedir($dh); 
			} 
		} 
	}

	//������� ��Ÿ�����Լ�
	public function getdir_list($URL) {
		$this->setdir_list($URL);
		return $this->_arr_dir_list;
	}

	//���丮������ ���ϴ� �Լ�
	public function setdir_size($URL) { 
		if(is_dir($URL)) {
			if($dh = @opendir($URL)) { 
				while(( $file = @readdir( $dh )) !== false ) { 
					if( $file == '.' || $file == "..")	continue;
					if( @filetype( $URL.$file ) == "dir")	$this->setdir_size($URL.$file.'/');	//����Լ�
					if($file=="Thumbs.db") continue;
					else {
						if( @filetype( $URL.$file ) != "file" ) $file = $file;
						$this->_arr_dir_size += filesize($URL."/".$file);
					}
				} 
				closedir($dh); 
			} 	
		}
	}
	
	//������� ������ϴ� �Լ�
	public function getdir_size() {
		return $this->_arr_dir_size;
	}


}
