<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "pr-1";
$MenuCode = "product";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script type="text/javascript" src="codeinit.js.php"></script>
<script language="JavaScript">
var code="<?=$code?>";
var allopen=false;
var movecode=false;

function CodeProcessFun(_code) {
	if(_code=="out" || _code.length==0 || _code=="000000000000") {
		document.all["code_top"].style.background="#dddddd";
		selcode="";
		seltype="";
		if(_code!="out") {
			BodyInit('');
		} else {
			_code="";
		}
	} else {
		document.all["code_top"].style.background="#ffffff";
		BodyInit(_code);
	}
	SetButton();
}

function ViewProperty() {
	if(selcode.length==0 || selcode=="000000000000") {
		return;
	}
	document.form1.code.value=selcode;
	document.form1.parentcode.value="";
	document.form1.mode.value="modify";
	document.form1.action="product_code.property.php";
	document.form1.target="PropertyFrame";
	document.form1.submit();
}

function NewCode() {
	if((selcode.length==0 || (selcode.length==12 && selcode.substring(9,12)=="000")) && (seltype.indexOf("X")==-1)) {
		document.form1.parentcode.value=selcode;
	} else {
		document.form1.parentcode.value="";
	}
	document.form1.code.value="";
	document.form1.action="product_code.property.php";
	document.form1.target="PropertyFrame";
	document.form1.submit();
}

function NewCodeResult(_code,type,code_name,list_type,detail_type,sort,group_code) {
	codeA=_code.substring(0,3);
	codeB=_code.substring(3,6);
	codeC=_code.substring(6,9);
	codeD=_code.substring(9,12);
	if((type=="L" || type=="T" || type=="LX" || type=="TX" || type=="S" || type=="SX") && codeB=="000") {	//1��ī�װ� �߰��Ϸ�
		var calist = new CodeAList();
		calist=AddObj(calist,_code,type,code_name,list_type,detail_type,all_list.length,sort,group_code);
		calist.display="show";
		all_list[all_list.length] = calist;
		calist=null;
		BodyInit(''); SetButton();
		return;
	} else {
		for(i=0;i<all_list.length;i++) {
			if(codeA==all_list[i].codeA) {
				if((type=="LM" || type=="TM" || type=="LMX" || type=="TMX" || type=="SM" || type=="SMX") && codeC=="000") {	//2��
					var cblist = new CodeBList();
					cblist=AddObj(cblist,_code,type,code_name,list_type,detail_type,all_list[i].ArrCodeB.length,sort,group_code);
					if(all_list[i].open=="open") {
						cblist.display="show";
					}
					all_list[i].ArrCodeB[all_list[i].ArrCodeB.length] = cblist;
					cblist=null;
					BodyInit(''); SetButton();
					return;
				} else {
					for(ii=0;ii<all_list[i].ArrCodeB.length;ii++) {
						if(codeB==all_list[i].ArrCodeB[ii].codeB) {

							if((type=="LM" || type=="TM" || type=="LMX" || type=="TMX" || type=="SM" || type=="SMX") && codeD=="000") {
								var cclist = new CodeCList();
								cclist=AddObj(cclist,_code,type,code_name,list_type,detail_type,all_list[i].ArrCodeB[ii].ArrCodeC.length,sort,group_code);
								if(all_list[i].ArrCodeB[ii].open=="open") {
									cclist.display="show";
								}
								all_list[i].ArrCodeB[ii].ArrCodeC[all_list[i].ArrCodeB[ii].ArrCodeC.length] = cclist;
								cclist=null;
								BodyInit(''); SetButton();
								return;
							} else if((type=="LM" || type=="TM" || type=="LMX" || type=="TMX" || type=="SM" || type=="SMX") && codeD!="000") {
								for(iii=0;iii<all_list[i].ArrCodeB[ii].ArrCodeC.length;iii++) {
									if(codeC==all_list[i].ArrCodeB[ii].ArrCodeC[iii].codeC) {
										var cdlist = new CodeDList();
										cdlist=AddObj(cdlist,_code,type,code_name,list_type,detail_type,all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD.length,sort,group_code);

										if(all_list[i].ArrCodeB[ii].ArrCodeC[iii].open=="open") {
											cdlist.display="show";
										}
										all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD.length] = cdlist;
										cdlist=null;
										BodyInit(''); SetButton();
										return;
									}
								}
							}
						}
					}
				}
			}
		}
	}
}

function AddObj(obj,_code,type,code_name,list_type,detail_type,sequence,sort,group_code) {
	obj.code=_code;
	obj.codeA=_code.substring(0,3);
	obj.codeB=_code.substring(3,6);
	obj.codeC=_code.substring(6,9);
	obj.codeD=_code.substring(9,12);
	obj.type=type;
	obj.code_name=code_name;
	obj.list_type=list_type;
	obj.detail_type=detail_type;
	obj.sequence=sequence;
	obj.sort=sort;
	obj.group_code=group_code;
	obj.selected=false;
	return obj;
}

function ModifyCodeResult(_code,type,code_name,list_type,detail_type,sort,group_code,is_gcode,is_sort,is_design) {
	codeA=_code.substring(0,3);
	codeB=_code.substring(3,6);
	codeC=_code.substring(6,9);
	codeD=_code.substring(9,12);
	for(i=0;i<all_list.length;i++) {
		if(codeA!="000" && codeB=="000" && codeC=="000" && codeD=="000") {
			if(all_list[i].code==_code) {
				all_list[i].code_name=code_name;
				all_list[i].list_type=list_type;
				all_list[i].detail_type=detail_type;
				all_list[i].sort=sort;
				all_list[i].group_code=group_code;
				if(is_gcode==1 || is_sort==1 || is_design==1) {
					for(ii=0;ii<all_list[i].ArrCodeB.length;ii++) {
						if(is_gcode==1) all_list[i].ArrCodeB[ii].group_code=group_code;
						if(is_sort==1) all_list[i].ArrCodeB[ii].sort=sort;
						if(is_design==1) {
							all_list[i].ArrCodeB[ii].list_type=list_type;
							all_list[i].ArrCodeB[ii].detail_type=detail_type;
						}
						for(iii=0;iii<all_list[i].ArrCodeB[ii].ArrCodeC.length;iii++) {
							if(is_gcode==1) all_list[i].ArrCodeB[ii].ArrCodeC[iii].group_code=group_code;
							if(is_sort==1) all_list[i].ArrCodeB[ii].ArrCodeC[iii].sort=sort;
							if(is_design==1) {
								all_list[i].ArrCodeB[ii].ArrCodeC[iii].list_type=list_type;
								all_list[i].ArrCodeB[ii].ArrCodeC[iii].detail_type=detail_type;
							}
							for(iiii=0;iiii<all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD.length;iiii++) {
								if(is_gcode==1) all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].group_code=group_code;
								if(is_sort==1) all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].sort=sort;
								if(is_design==1) {
									all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].list_type=list_type;
									all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].detail_type=detail_type;
								}
							}
						}
					}
				}
				BodyInit(''); SetButton();ViewProperty();
				return;
			}
		} else {
			for(ii=0;ii<all_list[i].ArrCodeB.length;ii++) {
				if(codeA!="000" && codeB!="000" && codeC=="000" && codeD=="000") {
					if(all_list[i].ArrCodeB[ii].code==_code) {
						all_list[i].ArrCodeB[ii].code_name=code_name;
						all_list[i].ArrCodeB[ii].list_type=list_type;
						all_list[i].ArrCodeB[ii].detail_type=detail_type;
						all_list[i].ArrCodeB[ii].sort=sort;
						all_list[i].ArrCodeB[ii].group_code=group_code;
						if(is_gcode==1 || is_sort==1 || is_design==1) {
							for(iii=0;iii<all_list[i].ArrCodeB[ii].ArrCodeC.length;iii++) {
								if(is_gcode==1) all_list[i].ArrCodeB[ii].ArrCodeC[iii].group_code=group_code;
								if(is_sort==1) all_list[i].ArrCodeB[ii].ArrCodeC[iii].sort=sort;
								if(is_design==1) {
									all_list[i].ArrCodeB[ii].ArrCodeC[iii].list_type=list_type;
									all_list[i].ArrCodeB[ii].ArrCodeC[iii].detail_type=detail_type;
								}
								for(iiii=0;iiii<all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD.length;iiii++) {
									if(is_gcode==1) all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].group_code=group_code;
									if(is_sort==1) all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].sort=sort;
									if(is_design==1) {
										all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].list_type=list_type;
										all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].detail_type=detail_type;
									}
								}
							}
						}
						BodyInit(''); SetButton();ViewProperty();
						return;
					}
				} else {
					for(iii=0;iii<all_list[i].ArrCodeB[ii].ArrCodeC.length;iii++) {
						if(codeA!="000" && codeB!="000" && codeC!="000" && codeD=="000") {
							if(all_list[i].ArrCodeB[ii].ArrCodeC[iii].code==_code) {
								all_list[i].ArrCodeB[ii].ArrCodeC[iii].code_name=code_name;
								all_list[i].ArrCodeB[ii].ArrCodeC[iii].list_type=list_type;
								all_list[i].ArrCodeB[ii].ArrCodeC[iii].detail_type=detail_type;
								all_list[i].ArrCodeB[ii].ArrCodeC[iii].sort=sort;
								all_list[i].ArrCodeB[ii].ArrCodeC[iii].group_code=group_code;
								if(is_gcode==1 || is_sort==1 || is_design==1) {
									for(iiii=0;iiii<all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD.length;iiii++) {
										if(is_gcode==1) all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].group_code=group_code;
										if(is_sort==1) all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].sort=sort;
										if(is_design==1) {
											all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].list_type=list_type;
											all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].detail_type=detail_type;
										}
									}
								}
								BodyInit(''); SetButton();ViewProperty();
								return;
							}
						} else {
							for(iiii=0;iiii<all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD.length;iiii++) {
								if(codeA!="000" && codeB!="000" && codeC!="000" && codeD!="000") {
									if(all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].code==_code) {
										all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].code_name=code_name;
										all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].list_type=list_type;
										all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].detail_type=detail_type;
										all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].sort=sort;
										all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].group_code=group_code;
										BodyInit(''); SetButton();ViewProperty();
										return;
									}
								}
							}
						}
					}
				}
			}
		}
	}
}

function AllOpen() {
	display="show";
	open1="open";
	if(allopen) {
		display="none";
		open1="close";
		allopen=false;
	} else {
		allopen=true;
	}
	for(i=0;i<all_list.length;i++) {
		if(display=="none" && all_list[i].codeA==selcode.substring(0,3)) {
			all_list[i].selected=true;
			selcode=all_list[i].codeA+all_list[i].codeB+all_list[i].codeC+all_list[i].codeD;
			seltype=all_list[i].type;
		}
		all_list[i].display=display;
		all_list[i].open=open1;
		for(ii=0;ii<all_list[i].ArrCodeB.length;ii++) {
			if(display=="none") {
				all_list[i].ArrCodeB[ii].selected=false;
			}
			all_list[i].ArrCodeB[ii].display=display;
			all_list[i].ArrCodeB[ii].open=open1;
			for(iii=0;iii<all_list[i].ArrCodeB[ii].ArrCodeC.length;iii++) {
				if(display=="none") {
					all_list[i].ArrCodeB[ii].ArrCodeC[iii].selected=false;
				}
				all_list[i].ArrCodeB[ii].ArrCodeC[iii].display=display;
				all_list[i].ArrCodeB[ii].ArrCodeC[iii].open=open1;
				for(iiii=0;iiii<all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD.length;iiii++) {
					if(display=="none") {
						all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].selected=false;
					}
					all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].display=display;
					all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].open=open1;
				}
			}
		}
	}
	BodyInit('');
}

function SetButton() {
	document.all["btn_property"].disabled=false;
	document.all["btn_property"].className="btn";
	document.all["btn_codeadd"].disabled=false;
	document.all["btn_codeadd"].className="btn";
	document.all["btn_codedel"].disabled=false;
	document.all["btn_codedel"].className="btn";
	document.all["btn_moveup"].disabled=false;
	document.all["btn_moveup"].className="btn";
	document.all["btn_movedown"].disabled=false;
	document.all["btn_movedown"].className="btn";
	if(selcode.length==0 || selcode=="000000000000") {
		document.all["btn_property"].disabled=true;
		document.all["btn_property"].className="btnNA";
		document.all["btn_moveup"].disabled=true;
		document.all["btn_moveup"].className="btnNA";
		document.all["btn_movedown"].disabled=true;
		document.all["btn_movedown"].className="btnNA";
	}
	if((seltype.indexOf("X")!=-1)) {
		document.all["btn_codeadd"].disabled=true;
		document.all["btn_codeadd"].className="btnNA";
	} else {
		document.all["btn_codedel"].disabled=true;
		document.all["btn_codedel"].className="btnNA";
	}
	if(movecode==false) {
		document.all["btn_movesave"].disabled=true;
		document.all["btn_movesave"].className="btnNA";
		document.all["btn_movecancel"].disabled=true;
		document.all["btn_movecancel"].className="btnNA";
	} else {
		document.all["btn_movesave"].disabled=false;
		document.all["btn_movesave"].className="btn";
		document.all["btn_movecancel"].disabled=false;
		document.all["btn_movecancel"].className="btn";
	}
}

function CodeMove(tmp) {
	//## up / down ##//
	if(selcode.length==0 || selcode=="000000000000" || seltype.length==0) {
		alert("�̵��� ī�װ� ������ �ȵǾ����ϴ�.");
		return;
	}
	_codeA=selcode.substring(0,3);
	_codeB=selcode.substring(3,6);
	_codeC=selcode.substring(6,9);
	_codeD=selcode.substring(9,12);

	//ī�װ��̵� ó��
	tmp_array=new Array();
	for(i=0;i<all_list.length;i++) {
		if(_codeB=="000" && _codeC=="000" && _codeD=="000" && (seltype=="L" || seltype=="T" || seltype=="LX" || seltype=="TX" || seltype=="S" || seltype=="SX")) {	//1�� ��ī�װ�
			if(all_list[i].code==selcode) {
				if(tmp=="up") {
					if(i==0) {
						alert("���� ī�װ��� \"�ֻ���\" �Դϴ�. �� �̻� �̵��� �� �����ϴ�.");
						return;
					}
				} else if(tmp=="down") {
					if(i==(all_list.length-1)) {
						alert("���� ī�װ��� \"������\" �Դϴ�. �� �̻� �̵��� �� �����ϴ�.");
						return;
					}
				}
				for(j=0;j<all_list.length;j++) {
					if(tmp=="up") {
						if(j<(i-1) || j>i) {
							tmp_array[j]=all_list[j];
						} else if (j==(i-1)) {
							tmp_array[j]=all_list[j+1];
						} else if (j==i) {
							tmp_array[j]=all_list[j-1];
						}
					} else if(tmp=="down") {
						if(j<i || j>(i+1)) {
							tmp_array[j]=all_list[j];
						} else if(j==(i+1)) {
							tmp_array[j]=all_list[j-1];
						} else if(j==i) {
							tmp_array[j]=all_list[j+1];
						}
					}
				}
				all_list = new Array();
				all_list = tmp_array;
				movecode=true;
				BodyInit('');
				SetButton();
				return;
			}
		} else {
			if(all_list[i].codeA==_codeA) {
				for(ii=0;ii<all_list[i].ArrCodeB.length;ii++) {
					if(_codeA!="000" && _codeB!="000" && _codeC=="000" && _codeD=="000") {	//2��ī�װ�
						if(all_list[i].ArrCodeB[ii].code==selcode) {
							if(tmp=="up") {
								if(ii==0) {
									alert("���� ī�װ��� \"�ֻ���\" �Դϴ�. �� �̻� �̵��� �� �����ϴ�.");
									return;
								}
							} else if(tmp=="down") {
								if(ii==(all_list[i].ArrCodeB.length-1)) {
									alert("���� ī�װ��� \"������\" �Դϴ�. �� �̻� �̵��� �� �����ϴ�.");
									return;
								}
							}
							for(j=0;j<all_list[i].ArrCodeB.length;j++) {
								if(tmp=="up") {
									if(j<(ii-1) || j>ii) {
										tmp_array[j]=all_list[i].ArrCodeB[j];
									} else if (j==(ii-1)) {
										tmp_array[j]=all_list[i].ArrCodeB[j+1];
									} else if (j==ii) {
										tmp_array[j]=all_list[i].ArrCodeB[j-1];
									}
								} else if(tmp=="down") {
									if(j<ii || j>(ii+1)) {
										tmp_array[j]=all_list[i].ArrCodeB[j];
									} else if(j==(ii+1)) {
										tmp_array[j]=all_list[i].ArrCodeB[j-1];
									} else if(j==ii) {
										tmp_array[j]=all_list[i].ArrCodeB[j+1];
									}
								}
							}
							all_list[i].ArrCodeB = new Array();
							all_list[i].ArrCodeB = tmp_array;
							movecode=true;
							BodyInit('');
							SetButton();
							return;
						}
					} else {	//3,4�� ī�װ�
						if(all_list[i].ArrCodeB[ii].codeA==_codeA && all_list[i].ArrCodeB[ii].codeB==_codeB) {
							for(iii=0;iii<all_list[i].ArrCodeB[ii].ArrCodeC.length;iii++) {
								if(_codeA!="000" && _codeB!="000" && _codeC!="000" && _codeD=="000") {	//3��ī�װ�
									if(all_list[i].ArrCodeB[ii].ArrCodeC[iii].code==selcode) {
										if(tmp=="up") {
											if(iii==0) {
												alert("���� ī�װ��� \"�ֻ���\" �Դϴ�. �� �̻� �̵��� �� �����ϴ�.");
												return;
											}
										} else if(tmp=="down") {
											if(iii==(all_list[i].ArrCodeB[ii].ArrCodeC.length-1)) {
												alert("���� ī�װ��� \"������\" �Դϴ�. �� �̻� �̵��� �� �����ϴ�.");
												return;
											}
										}
										for(j=0;j<all_list[i].ArrCodeB[ii].ArrCodeC.length;j++) {
											if(tmp=="up") {
												if(j<(iii-1) || j>iii) {
													tmp_array[j]=all_list[i].ArrCodeB[ii].ArrCodeC[j];
												} else if (j==(iii-1)) {
													tmp_array[j]=all_list[i].ArrCodeB[ii].ArrCodeC[j+1];
												} else if (j==iii) {
													tmp_array[j]=all_list[i].ArrCodeB[ii].ArrCodeC[j-1];
												}
											} else if(tmp=="down") {
												if(j<iii || j>(iii+1)) {
													tmp_array[j]=all_list[i].ArrCodeB[ii].ArrCodeC[j];
												} else if(j==(iii+1)) {
													tmp_array[j]=all_list[i].ArrCodeB[ii].ArrCodeC[j-1];
												} else if(j==iii) {
													tmp_array[j]=all_list[i].ArrCodeB[ii].ArrCodeC[j+1];
												}
											}
										}
										all_list[i].ArrCodeB[ii].ArrCodeC = new Array();
										all_list[i].ArrCodeB[ii].ArrCodeC = tmp_array;
										movecode=true;
										BodyInit('');
										SetButton();
										return;
									}
								} else {	//4��ī�װ�
									if(all_list[i].ArrCodeB[ii].ArrCodeC[iii].codeA==_codeA && all_list[i].ArrCodeB[ii].ArrCodeC[iii].codeB==_codeB && all_list[i].ArrCodeB[ii].ArrCodeC[iii].codeC==_codeC) {
										for(iiii=0;iiii<all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD.length;iiii++) {
											if(all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].code==selcode) {
												if(tmp=="up") {
													if(iiii==0) {
														alert("���� ī�װ��� \"�ֻ���\" �Դϴ�. �� �̻� �̵��� �� �����ϴ�.");
														return;
													}
												} else if(tmp=="down") {
													if(iiii==(all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD.length-1)) {
														alert("���� ī�װ��� \"������\" �Դϴ�. �� �̻� �̵��� �� �����ϴ�.");
														return;
													}
												}
												for(j=0;j<all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD.length;j++) {
													if(tmp=="up") {
														if(j<(iiii-1) || j>iiii) {
															tmp_array[j]=all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[j];
														} else if (j==(iiii-1)) {
															tmp_array[j]=all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[j+1];
														} else if (j==iiii) {
															tmp_array[j]=all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[j-1];
														}
													} else if(tmp=="down") {
														if(j<iiii || j>(iiii+1)) {
															tmp_array[j]=all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[j];
														} else if(j==(iiii+1)) {
															tmp_array[j]=all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[j-1];
														} else if(j==iiii) {
															tmp_array[j]=all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[j+1];
														}
													}
												}
												all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD = new Array();
												all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD = tmp_array;
												movecode=true;
												BodyInit('');
												SetButton();
												return;
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}
}

//ī�װ����� DB�� ����
function MoveSave() {
	if(confirm("������ ī�װ������� ���� �Ͻðڽ��ϱ�?")) {
		codes="";
		for(i=0;i<all_list.length;i++) {
			codes+="!"+all_list[i].code;
			for(ii=0;ii<all_list[i].ArrCodeB.length;ii++) {
				codes+="@"+all_list[i].ArrCodeB[ii].code;
				for(iii=0;iii<all_list[i].ArrCodeB[ii].ArrCodeC.length;iii++) {
					codes+="#"+all_list[i].ArrCodeB[ii].ArrCodeC[iii].code;
					for(iiii=0;iiii<all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD.length;iiii++) {
						codes+="$"+all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].code;
					}
				}
			}
		}
		document.form1.codes.value=codes.substring(1);
		document.form1.mode.value="movesave";
		document.form1.action="product_code.process.php";
		document.form1.target="HiddenFrame";
		document.form1.submit();
	}
}
//�̵��� ī�װ� �ǵ�����
function MoveCancel() {
	arrall=new Array();
	for(i=0;i<all_list.length;i++) {
		arr_b=new Array();
		for(ii=0;ii<all_list[i].ArrCodeB.length;ii++) {
			arr_c=new Array();
			for(iii=0;iii<all_list[i].ArrCodeB[ii].ArrCodeC.length;iii++) {
				arr_d=new Array();
				for(iiii=0;iiii<all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD.length;iiii++) {
					arr_d[all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].sequence]=all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii];
				}
				all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD=new Array();
				all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD=arr_d;

				arr_c[all_list[i].ArrCodeB[ii].ArrCodeC[iii].sequence]=all_list[i].ArrCodeB[ii].ArrCodeC[iii];
			}
			all_list[i].ArrCodeB[ii].ArrCodeC=new Array();
			all_list[i].ArrCodeB[ii].ArrCodeC=arr_c;

			arr_b[all_list[i].ArrCodeB[ii].sequence]=all_list[i].ArrCodeB[ii];
		}
		all_list[i].ArrCodeB=new Array();
		all_list[i].ArrCodeB=arr_b;
		arrall[all_list[i].sequence]=all_list[i];
	}
	all_list=new Array();
	all_list=arrall;
	movecode=false;
	BodyInit('');
	SetButton();
	alert("�̵��� ī�װ��� �ʱⰪ���� �ǵ��Ƚ��ϴ�.");
	return;
}

function CodeDelete() {
	if(selcode.length==12 && selcode!="000000000000" && (seltype.indexOf("X")!=-1)) {
		if(confirm("���õ� ī�װ��� ���� �Ͻðڽ��ϱ�?")) {
			document.form1.code.value=selcode;
			document.form1.mode.value="delete";
			document.form1.action="product_code.process.php";
			document.form1.target="HiddenFrame";
			document.form1.submit();
		}
	}
}

function CodeDelete2(_code) {
	if(selcode.length==12 && selcode!="000000000000") {
		document.form1.code.value=_code;
		document.form1.mode.value="delete";
		document.form1.action="product_code.process.php";
		document.form1.target="HiddenFrame";
		document.form1.submit();
	}
}

function CodeMoveResult() {
	movecode=false;
	SetButton();
}

function CodeDeleteResult(_code) {
	//document.location.reload();

	codeA=_code.substring(0,3);
	codeB=_code.substring(3,6);
	codeC=_code.substring(6,9);
	codeD=_code.substring(9,12);
	arrall=new Array();
	j=0;
	for(i=0;i<all_list.length;i++) {
		if(codeA!="000" && codeB=="000" && codeC=="000" && codeD=="000") {	//��ī�װ��� �����Ͽ���.
			if(all_list[i].code!=_code) {
				all_list[i].sequence=j;
				arrall[j]=all_list[i];
				j++;
			} else {
				if(selcode==_code) {
					selcode="";
					seltype="";
				}
			}
		} else {
			all_list[i].sequence=j;
			arrall[j]=all_list[i];
			arr_b=new Array();
			jj=0;
			for(ii=0;ii<all_list[i].ArrCodeB.length;ii++) {
				if(codeA!="000" && codeB!="000" && codeC=="000" && codeD=="000") {
					if(all_list[i].ArrCodeB[ii].code!=_code) {
						all_list[i].ArrCodeB[ii].sequence=jj;
						arr_b[jj]=all_list[i].ArrCodeB[ii];
						jj++;
					} else {
						if(selcode==_code) {
							arrall[j].selected=true;
							arrall[j].open="open";
							selcode=all_list[i].code;
							seltype=all_list[i].type;
						}
					}
				} else {
					all_list[i].ArrCodeB[ii].sequence=jj;
					arr_b[jj]=all_list[i].ArrCodeB[ii];
					arr_c=new Array();
					jjj=0;
					for(iii=0;iii<all_list[i].ArrCodeB[ii].ArrCodeC.length;iii++) {
						if(codeA!="000" && codeB!="000" && codeC!="000" && codeD=="000") {
							if(all_list[i].ArrCodeB[ii].ArrCodeC[iii].code!=_code) {
								all_list[i].ArrCodeB[ii].ArrCodeC[iii].sequence=jjj;
								arr_c[jjj]=all_list[i].ArrCodeB[ii].ArrCodeC[iii];
								jjj++;
							} else {
								if(selcode==_code) {
									arr_b[jj].selected=true;
									arr_b[jj].open="open";
									selcode=all_list[i].ArrCodeB[ii].code;
									seltype=all_list[i].ArrCodeB[ii].type;
								}
							}
						} else {
							all_list[i].ArrCodeB[ii].ArrCodeC[iii].sequence=jjj;
							arr_c[jjj]=all_list[i].ArrCodeB[ii].ArrCodeC[iii];
							arr_d=new Array();
							jjjj=0;
							for(iiii=0;iiii<all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD.length;iiii++) {
								if(codeA!="000" && codeB!="000" && codeC!="000" && codeD!="000") {
									if(all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].code!=_code) {
										all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].sequence=jjjj;
										arr_d[jjjj]=all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii];
										jjjj++;
									} else {
										if(selcode==_code) {
											arr_c[jjj].selected=true;
											arr_c[jjj].open="open";
											selcode=all_list[i].ArrCodeB[ii].ArrCodeC[iii].code;
											seltype=all_list[i].ArrCodeB[ii].ArrCodeC[iii].type;
										}
									}
								} else {
									all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].sequence=jjjj;
									arr_d[jjjj]=all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii];
									jjjj++;
								}
							}
							arrall[j].ArrCodeB[jj].ArrCodeC[jjj].ArrCodeD=new Array();
							arrall[j].ArrCodeB[jj].ArrCodeC[jjj].ArrCodeD=arr_d;

							jjj++;
						}
					}
					arrall[j].ArrCodeB[jj].ArrCodeC=new Array();
					arrall[j].ArrCodeB[jj].ArrCodeC=arr_c;

					jj++;
				}
			}
			arrall[j].ArrCodeB=new Array();
			arrall[j].ArrCodeB=arr_b;

			j++;
		}
	}
	all_list=new Array();
	all_list=arrall;

	document.form1.code.value="";
	document.form1.parentcode.value="";
	document.form1.action="product_code.property.php";
	document.form1.target="PropertyFrame";
	document.form1.submit();

	BodyInit('');
	SetButton();
}

var divLeft=0;
var defaultLeft=0;
var timeOffset=0;
var setTObj;
var divName="";
var zValue=0;

function divMove()
{
	divLeft+=timeOffset;

	if(divLeft >= defaultLeft)
	{
		divLeft=defaultLeft;
		divName.style.left=divLeft;
		divName.style.zIndex = zValue;
		clearTimeout(setTObj);
		setTObj="";
	}
	else
	{
		timeOffset+=20;
		divName.style.left=divLeft;
		setTObj=setTimeout('divMove();',5);
	}
}

function divAction(arg1,arg2)
{
	if(zValue != arg2 && !setTObj)
	{
		defaultLeft = arg1.offsetLeft;
		divLeft = defaultLeft;
		zValue = arg2;
		divName = arg1;
		timeOffset = -70;
		divMove();
	}
}
</script>


<STYLE type=text/css>
	#menuBar {}
	#contentDiv {WIDTH: 220;HEIGHT: 320;}
</STYLE>
<table cellpadding="0" cellspacing="0" width="100%">
<tr>
	<td valign="top">
	<table cellpadding="0" cellspacing="0" width=100%>
	<tr>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%"  background="images/con_bg.gif">
		<col width=198></col>
		<col width=10></col>
		<col width=></col>
		<tr>
			<td valign="top"  background="images/leftmenu_bg.gif">
			<? include ("menu_product.php"); ?>
			</td>

			<td></td>
			<td valign="top">


<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ��ǰ���� &gt;ī�װ�/��ǰ���� &gt; <span class="2depth_select">ī�װ� ����</span></td>
			</tr>
			</table>
		</td>
	</tr>
	<tr>
        <td width="16"><img src="images/con_t_01.gif" width="16" height="16" border="0"></td>
        <td background="images/con_t_01_bg.gif"></td>
        <td width="16"><img src="images/con_t_02.gif" width="16" height="16" border="0"></td>
    </tr>
    <tr>
        <td width="16" background="images/con_t_04_bg1.gif"></td>
        <td bgcolor="#ffffff" style="padding:10px">



			<table cellpadding="0" cellspacing="0" width="100%">
			<tr><td height="8"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/product_code_title.gif" WIDTH="208" HEIGHT=32 ALT=""></TD>
				</tr>
				<tr>
					<TD width="100%" background="images/title_bg.gif" height=21></TD>
				</TR>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height="3"></td></tr>
			<tr>
				<td style="padding-bottom:3pt;">
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/distribute_01.gif"></TD>
					<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
					<TD><IMG SRC="images/distribute_03.gif"></TD>
				</TR>
				<TR>
					<TD background="images/distribute_04.gif"></TD>
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
					<TD width="100%" class="notice_blue">ī�װ� �߰�, ����, ������ �����ϸ� ī�װ��� ���ø��� ������ �� �ֽ��ϴ�.</TD>
					<TD background="images/distribute_07.gif"></TD>
				</TR>
				<TR>
					<TD><IMG SRC="images/distribute_08.gif"></TD>
					<TD COLSPAN=2 background="images/distribute_09.gif"></TD>
					<TD><IMG SRC="images/distribute_10.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height="20"></td></tr>
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=mode value="<?=$mode?>">
			<input type=hidden name=code>
			<input type=hidden name=codes>
			<input type=hidden name=parentcode>
			<tr>
				<td>
				<table cellpadding="0" cellspacing="0" width="100%" height="910">
				<tr>
					<td valign="top">
					<DIV onmouseover="document.getElementById('cateidx').style.zIndex=2;" id="cateidx" style="position:absolute;z-index:0;width:242px;bgcolor:#FFFFFF;">
					<table cellpadding="0" cellspacing="0" width="100%" height="870">
					<tr>
						<td width="100%" height="100%" valign="top" background="images/category_boxbg.gif">
						<table cellpadding="0" cellspacing="0" width="100%" height="100%">
						<tr>
							<td bgcolor="#FFFFFF"><IMG SRC="images/product_totoacategory_title.gif" WIDTH=85 HEIGHT=24 ALT=""></td>
						</tr>
						<tr>
							<td><IMG SRC="images/category_box1.gif" border="0"></td>
						</tr>
						<tr>
							<td bgcolor="#0F8FCB" style="padding-top:4pt; padding-bottom:6pt;">
							<table align="center" cellpadding="0" cellspacing="0" width="230">
							<tr>
								<td width="24"><button title="��ü Ʈ��Ȯ��" id="btn_treeall" class="btn" onmouseover="if(this.className=='btn'){this.className='btnOver'}" onmouseout="if(this.className=='btnOver'){this.className='btn'}" unselectable="on" onclick="AllOpen();"><IMG SRC="images/category_btn1.gif" WIDTH=22 HEIGHT=23 border="0"></button></td>
								<td width="24"><button title="���õ� ī�װ��Ӽ� ����" id="btn_property" class="btn" onmouseover="if(this.className=='btn'){this.className='btnOver'}" onmouseout="if(this.className=='btnOver'){this.className='btn'}" unselectable="on" onclick="ViewProperty();"><IMG SRC="images/category_btn2.gif" WIDTH=22 HEIGHT=23 border="0"></button></td>
								<td width="24"><button title="���õ� ī�װ��� ����ī�װ� �߰�" id="btn_codeadd" class="btn" onmouseover="if(this.className=='btn'){this.className='btnOver'}" onmouseout="if(this.className=='btnOver'){this.className='btn'}" unselectable="on" onclick="NewCode();"><IMG SRC="images/category_btn3.gif" WIDTH=22 HEIGHT=23 border="0"></button></td>
								<td width="24"><button title="���õ� ī�װ� ����" id="btn_codedel" class="btn" onmouseover="if(this.className=='btn'){this.className='btnOver'}" onmouseout="if(this.className=='btnOver'){this.className='btn'}" unselectable="on" onclick="CodeDelete();"><IMG SRC="images/category_btn4.gif" WIDTH=22 HEIGHT=23 border="0"></button></td>
								<td width="24"><button title="���õ� ī�װ� ���� �̵�" id="btn_moveup" class="btn" onmouseover="if(this.className=='btn'){this.className='btnOver'}" onmouseout="if(this.className=='btnOver'){this.className='btn'}" unselectable="on" onclick="CodeMove('up');"><IMG SRC="images/category_btn5.gif" WIDTH=22 HEIGHT=23 border="0"></button></td>
								<td width="24"><button title="���õ� ī�װ� �Ʒ��� �̵�" id="btn_movedown" class="btn" onmouseover="if(this.className=='btn'){this.className='btnOver'}" onmouseout="if(this.className=='btnOver'){this.className='btn'}" unselectable="on" onclick="CodeMove('down');"><IMG SRC="images/category_btn6.gif" WIDTH=22 HEIGHT=23 border="0"></button></td>
								<td width="24"><button title="�̵��� ī�װ� ����" id="btn_movesave" class="btn" onmouseover="if(this.className=='btn'){this.className='btnOver'}" onmouseout="if(this.className=='btnOver'){this.className='btn'}" unselectable="on" onclick="MoveSave();"><IMG SRC="images/category_btn7.gif" WIDTH=22 HEIGHT=23 border="0"></button></td>
								<td width="24"><button title="�̵��� ī�װ� �ǵ�����" id="btn_movecancel" class="btn" onmouseover="if(this.className=='btn'){this.className='btnOver'}" onmouseout="if(this.className=='btnOver'){this.className='btn'}" unselectable="on" onclick="MoveCancel();"><IMG SRC="images/category_btn8.gif" WIDTH=23 HEIGHT=23 border="0"></button></td>
							</tr>
							</table>
							</td>
						</tr>
						<tr>
							<td><IMG SRC="images/category_box2.gif" border="0"></td>
						</tr>
						<tr>
							<td width="100%" height="100%" align=center valign=top style="padding-left:5px;padding-right:5px;">
								<DIV class=MsgrScroller id=contentDiv style="width:99%;height:100%;OVERFLOW-x: auto; OVERFLOW-y: auto;" oncontextmenu="return false" style="overflow-x:hidden;overflow-y:hidden;" ondragstart="return false" onselectstart="return false" oncontextmenu="return false">
									<DIV id=bodyList>
										<table border=0 cellpadding=0 cellspacing=0 width="100%" height="100%" bgcolor=FFFFFF>
											<tr>
												<td height=18><IMG SRC="images/directory_root.gif" border=0 align=absmiddle> <span id="code_top" style="cursor:default;" onmouseover="this.className='link_over'" onmouseout="this.className='link_out'" onclick="ChangeSelect('out');">�ֻ��� ī�װ�</span></td>
											</tr>
											<tr>
												<!-- ��ǰī�װ� ��� -->
												<td id="code_list" nowrap valign=top></td>
												<!-- ��ǰī�װ� ��� �� -->
											</tr>
										</table>
									</DIV>
								</DIV>
							</td>
						</tr>
						</table>
						</td>
					</tr>
					<tr>
						<td><IMG SRC="images/category_boxdown.gif" border="0"></td>
					</tr>
					</table>
					</div>
					</td>
					<td style="padding-left:84px;"></td>
					<td width="100%" valign="top" height="100%" onmouseover="document.getElementById('cateidx').style.zIndex=0;">
						<DIV style="position:relative;z-index:1;height:100%;margin-left:170px;bgcolor:#FFFFFF;">
							<IFRAME name="PropertyFrame" id="PropertyFrame" src="product_code.property.php" width=100% height=600 frameborder=0 align=TOP scrolling="no" marginheight="0" marginwidth="0"></IFRAME>
						</div>
					</td>
				</tr>
				<IFRAME name="HiddenFrame" src="<?=$Dir?>blank.php" width=0 height=0 frameborder=0 align=TOP scrolling="no" marginheight="0" marginwidth="0"></IFRAME>
				</table>
				</td>
			</tr>
			</form>
			<tr><td height=20></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/manual_top1.gif" WIDTH=15 HEIGHT=45 ALT="" onLoad="autoResize('PropertyFrame');"></TD>
					<TD><IMG SRC="images/manual_title.gif" WIDTH=113 HEIGHT=45 ALT=""></TD>
					<TD width="100%" background="images/manual_bg.gif"></TD>
					<TD background="images/manual_bg.gif"></TD>
					<TD><IMG SRC="images/manual_top2.gif" WIDTH=18 HEIGHT=45 ALT=""></TD>
				</TR>
				<TR>
					<TD background="images/manual_left1.gif"></TD>
					<TD COLSPAN=3 width="100%" valign="top" bgcolor="#FFFFFF" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;" class="menual_bg">
					<table cellpadding="0" cellspacing="0" width="100%">
					<col width=20></col>
					<col width=></col>
					<tr>
						<td align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><span class="font_dotline">ī�װ������� ���ǻ���</span></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- ī�װ����� �ִ� �ѱ� 50��, ���� 100�� �̳��� ���ѵǾ� ������, Ư�����ڴ� �ﰡ�� �ּ���.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- �ֻ���ī�װ� ������ ����ī�װ� ������ Ȯ�� �� ����� �ּ���.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- "����ī�װ� ����" ���ý� �ش� ī�װ����� �ٷ� ��ǰ�� ����� �� �ֽ��ϴ�.</td>
					</tr>
					<tr>
						<td colspan="2" height="20"></td>
					</tr>
					<tr>
						<td align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><span class="font_dotline">��ǰ����</span></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- [��ǰ ���/������¥ ����] : ���ļ����� �ֱ� ������ ��ǰ�� ���� ��µǸ� �����ȵ� ��ǰ�� ��ϼ����� ��µ˴ϴ�.<br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<a href="javascript:parent.topframe.GoMenu(4,'product_sort.php');"><span class="font_blue">��ǰ���� > ī�װ�/��ǰ���� > ��ǰ �������� ����</span></a> ���� ���� ���� ������ �����մϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- [��ǰ ���/������¥ ���� + ǰ����ǰ �ڷ�] : ����ִ� ��ǰ�� ǰ���� ��ǰ ������ ���еǾ� ��µǸ�<br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						���ļ����� �ֱ� ������ ��ǰ�� ���� ��µǸ� �����ȵ� ��ǰ�� ��ϼ����� ��µ˴ϴ�.<br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<a href="javascript:parent.topframe.GoMenu(4,'product_sort.php');"><span class="font_blue">��ǰ���� > ī�װ�/��ǰ���� > ��ǰ �������� ����</span></a> ���� ���� ���� ������ �����մϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- [��ǰ�� ������ ����] : ���ļ����� ��ǰ �̸������� ��µ˴ϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- [������ ������ ����] : ���ļ����� ������ �̸������� ��µ˴ϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- [��ǰ �ǸŰ��� ����] : ���ļ����� ��ǰ �ǸŰ��� ���� ������ ��µ˴ϴ�.</td>
					</tr>
					<tr>
						<td colspan="2" height="20"></td>
					</tr>
					<tr>
						<td align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><span class="font_dotline">����ī�װ� ����</span></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- ī�װ� Ÿ���� �Ϲݰ� �������� ���еǸ� "����ī�װ�"�� �Ϲݻ�ǰ �߿��� �����Ͽ� ��ǰ�� �����մϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- ����ī�װ��� ��ǰ�� ������ ��ǰ�̹Ƿ� ������ �Ϲݻ�ǰ�� ���� �Ǵ� ������ �Բ� �����˴ϴ�.</td>
					</tr>
					<tr>
						<td colspan="2" height="20"></td>
					</tr>
					<tr>
						<td align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><span class="font_dotline">��ǰ���� Ÿ�Լ���</span></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- ī�װ� ��ǰ�������� �α�/�Ż�ǰ/��õ��ǰ �������ο� ����Ÿ��, �������� ������ �� �ֽ��ϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- ���κ��� ��ǰ������ <a href="javascript:parent.topframe.GoMenu(1,'shop_mainproduct.php');"><span class="font_blue">�������� > ���θ� ȯ�� ���� > ��ǰ ������/ȭ�鼳��</a></span> ���� ������ �� �ֽ��ϴ�.</td>
					</tr>
					</table>
					</TD>
					<TD background="images/manual_right1.gif"></TD>
				</TR>
				<TR>
					<TD><IMG SRC="images/manual_left2.gif" WIDTH=15 HEIGHT=8 ALT=""></TD>
					<TD COLSPAN=3 background="images/manual_down.gif"></TD>
					<TD><IMG SRC="images/manual_right2.gif" WIDTH=18 HEIGHT=8 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height="50"></td></tr>
			</table>

		</td>
        <td width="16" background="images/con_t_02_bg.gif"></td>
    </tr>
    <tr>
        <td width="16"><img src="images/con_t_04.gif" width="16" height="16" border="0"></td>
        <td background="images/con_t_04_bg.gif"></td>
        <td width="16"><img src="images/con_t_03.gif" width="16" height="16" border="0"></td>
    </tr>
    <tr><td height="20"></td></tr>
</table>

			</td>
		</tr>
		</table>
		</td>
	</tr>
	</table>
	</td>
</tr>
</table>

<?
$sql = "SELECT * FROM tblproductcode ORDER BY sequence DESC ";
include ("codeinit.php");
?>

<?=$onload?>
<? INCLUDE "copyright.php"; ?>