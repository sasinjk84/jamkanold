<?
if(!eregi(getenv("HTTP_HOST"),getenv("HTTP_REFERER"))) {
	header("HTTP/1.0 404 Not Found");
	exit;
} else {
?>
var all_list = new Array();
//var lista=new Array();
//var listb=new Array();
//var listc=new Array();
//var listd=new Array();
var listacount=0;
var listbcount=new Array();
var listccount=new Array();
var listdcount=new Array();
var listaloop=new Array();
var listbloop=new Array();
var listcloop=new Array();
var listdloop=new Array();
var selcode="";
var seltype="";
var selcode_name="";
var sel_list_type="";
var sel_detail_type="";
var CodeInit_cnt=0;

function DeleteFrontZero(str){
	val = new String(str)
	do {
		if (val.length==1) 
			break;
		if (val.substr(0,1)=='0')
			val = val.substr(1, val.length - 1);
		else
			break;
	} while (true);
	return val
}

function ToInt(val){
	val = DeleteFrontZero(val);
	return parseInt(val);
}

function CodeList() {
	var argv = CodeList.arguments;   
	var argc = CodeList.arguments.length;
	
	//Property ����
	this.classname		= "CodeList"								//classname
	this.debug			= false;									//����뿩��.
	this.code			= new String((argc > 0) ? argv[0] : "000000000000");
	this.codeA			= new String((argc > 1) ? argv[1] : "000");
	this.codeB			= new String((argc > 2) ? argv[2] : "000");
	this.codeC			= new String((argc > 3) ? argv[3] : "000");
	this.codeD			= new String((argc > 4) ? argv[4] : "000");
	this.type			= new String((argc > 5) ? argv[5] : "");
	this.code_name		= new String((argc > 6) ? argv[6] : "");
	this.list_type		= new String((argc > 7) ? argv[7] : "");
	this.detail_type	= new String((argc > 8) ? argv[8] : "");
	this.sort			= new String((argc > 9) ? argv[9] : "");
	this.group_code		= new String((argc > 10) ? argv[10] : "");
	this.selected		= new Boolean((argc > 11) ? argv[11] : false );
	this.display		= new String((argc > 12) ? argv[12] : "none");	//display��
	this.open			= new String((argc > 13) ? argv[13] : "close");	//open, close ����
}
function CodeAList() {
	var argv = CodeAList.arguments;   
	var argc = CodeAList.arguments.length;
	
	//Property ����
	this.classname		= "CodeAList"								//classname
	this.debug			= false;									//����뿩��.
	this.code			= new String((argc > 0) ? argv[0] : "000000000000");
	this.codeA			= new String((argc > 1) ? argv[1] : "000");
	this.codeB			= new String((argc > 2) ? argv[2] : "000");
	this.codeC			= new String((argc > 3) ? argv[3] : "000");
	this.codeD			= new String((argc > 4) ? argv[4] : "000");
	this.type			= new String((argc > 5) ? argv[5] : "");
	this.code_name		= new String((argc > 6) ? argv[6] : "");
	this.list_type		= new String((argc > 7) ? argv[7] : "");
	this.detail_type	= new String((argc > 8) ? argv[8] : "");
	this.sequence		= new String((argc > 9) ? argv[9] : "");	//�������� �ѹ��� (1,2,3,4,5,6......)
	this.sort			= new String((argc > 10) ? argv[10] : "");
	this.group_code		= new String((argc > 11) ? argv[11] : "");
	this.selected		= new Boolean((argc > 12) ? argv[12] : false);
	this.display		= new String((argc > 13) ? argv[13] : "none");
	this.open			= new String((argc > 14) ? argv[14] : "close");
	this.ArrCodeB		= new Array();
}
function CodeBList() {
	var argv = CodeBList.arguments;   
	var argc = CodeBList.arguments.length;
	
	//Property ����
	this.classname		= "CodeBList"								//classname
	this.debug			= false;									//����뿩��.
	this.code			= new String((argc > 0) ? argv[0] : "000000000000");
	this.codeA			= new String((argc > 1) ? argv[1] : "000");
	this.codeB			= new String((argc > 2) ? argv[2] : "000");
	this.codeC			= new String((argc > 3) ? argv[3] : "000");
	this.codeD			= new String((argc > 4) ? argv[4] : "000");
	this.type			= new String((argc > 5) ? argv[5] : "");
	this.code_name		= new String((argc > 6) ? argv[6] : "");
	this.list_type		= new String((argc > 7) ? argv[7] : "");
	this.detail_type	= new String((argc > 8) ? argv[8] : "");
	this.sequence		= new String((argc > 9) ? argv[9] : "");	//�������� �ѹ��� (1,2,3,4,5,6......)
	this.sort			= new String((argc > 10) ? argv[10] : "");
	this.group_code		= new String((argc > 11) ? argv[11] : "");
	this.selected		= new Boolean((argc > 12) ? argv[12] : false);
	this.display		= new String((argc > 13) ? argv[13] : "none");
	this.open			= new String((argc > 14) ? argv[14] : "close");
	this.ArrCodeC		= new Array();
}
function CodeCList() {
	var argv = CodeCList.arguments;   
	var argc = CodeCList.arguments.length;
	
	//Property ����
	this.classname		= "CodeCList"								//classname
	this.debug			= false;									//����뿩��.
	this.code			= new String((argc > 0) ? argv[0] : "000000000000");
	this.codeA			= new String((argc > 1) ? argv[1] : "000");
	this.codeB			= new String((argc > 2) ? argv[2] : "000");
	this.codeC			= new String((argc > 3) ? argv[3] : "000");
	this.codeD			= new String((argc > 4) ? argv[4] : "000");
	this.type			= new String((argc > 5) ? argv[5] : "");
	this.code_name		= new String((argc > 6) ? argv[6] : "");
	this.list_type		= new String((argc > 7) ? argv[7] : "");
	this.detail_type	= new String((argc > 8) ? argv[8] : "");
	this.sequence		= new String((argc > 9) ? argv[9] : "");	//�������� �ѹ��� (1,2,3,4,5,6......)
	this.sort			= new String((argc > 10) ? argv[10] : "");
	this.group_code		= new String((argc > 11) ? argv[11] : "");
	this.selected		= new Boolean((argc > 12) ? argv[12] : false);
	this.display		= new String((argc > 13) ? argv[13] : "none");
	this.open			= new String((argc > 14) ? argv[14] : "close");
	this.ArrCodeD		= new Array();
}

function CodeDList() {
	var argv = CodeDList.arguments;   
	var argc = CodeDList.arguments.length;
	
	//Property ����
	this.classname		= "CodeDList"								//classname
	this.debug			= false;									//����뿩��.
	this.code			= new String((argc > 0) ? argv[0] : "000000000000");
	this.codeA			= new String((argc > 1) ? argv[1] : "000");
	this.codeB			= new String((argc > 2) ? argv[2] : "000");
	this.codeC			= new String((argc > 3) ? argv[3] : "000");
	this.codeD			= new String((argc > 4) ? argv[4] : "000");
	this.type			= new String((argc > 5) ? argv[5] : "");
	this.code_name		= new String((argc > 6) ? argv[6] : "");
	this.list_type		= new String((argc > 7) ? argv[7] : "");
	this.detail_type	= new String((argc > 8) ? argv[8] : "");
	this.sequence		= new String((argc > 9) ? argv[9] : "");	//�������� �ѹ��� (1,2,3,4,5,6......)
	this.sort			= new String((argc > 10) ? argv[10] : "");
	this.group_code		= new String((argc > 11) ? argv[11] : "");
	this.selected		= new Boolean((argc > 12) ? argv[12] : false);
	this.display		= new String((argc > 13) ? argv[13] : "none");
	this.open			= new String((argc > 14) ? argv[14] : "close");
}

function CodeInit() {
	j=0;	
	for(i=0;i<listacount;i++) {
		listaloopobj = listaloop[i.toString()];
		if(listaloopobj.type=="L" || listaloopobj.type=="T" || listaloopobj.type=="LX" || listaloopobj.type=="TX" || listaloopobj.type=="S" || listaloopobj.type=="SX") {//��з� �̱�
			var calist=new CodeAList();
			calist.code=listaloopobj.code;
			calist.codeA=listaloopobj.codeA;
			calist.codeB=listaloopobj.codeB;
			calist.codeC=listaloopobj.codeC;
			calist.codeD=listaloopobj.codeD;
			calist.type=listaloopobj.type;
			calist.code_name=listaloopobj.code_name;
			calist.list_type=listaloopobj.list_type;
			calist.detail_type=listaloopobj.detail_type;
			calist.sequence=j;
			calist.sort=listaloopobj.sort;
			calist.group_code=listaloopobj.group_code;
			calist.selected=listaloopobj.selected;
			calist.display=listaloopobj.display;
			calist.open=listaloopobj.open;

			jj=0;
			for(ii=0;ii<listbcount[listaloopobj.codeA];ii++) {
				listbloopobj = listbloop[listaloopobj.codeA+ii.toString()];
				var cblist=new CodeBList();
				cblist.code=listbloopobj.code;
				cblist.codeA=listbloopobj.codeA;
				cblist.codeB=listbloopobj.codeB;
				cblist.codeC=listbloopobj.codeC;
				cblist.codeD=listbloopobj.codeD;
				cblist.type=listbloopobj.type;
				cblist.code_name=listbloopobj.code_name;
				cblist.list_type=listbloopobj.list_type;
				cblist.detail_type=listbloopobj.detail_type;
				cblist.sequence=jj;
				cblist.sort=listbloopobj.sort;
				cblist.group_code=listbloopobj.group_code;
				cblist.selected=listbloopobj.selected;
				cblist.display=listbloopobj.display;
				cblist.open=listbloopobj.open;

				jjj=0;
				for(iii=0;iii<listccount[listbloopobj.codeA+listbloopobj.codeB];iii++) {
					listcloopobj = listcloop[listbloopobj.codeA+listbloopobj.codeB+iii.toString()];
					var cclist=new CodeCList();
					cclist.code=listcloopobj.code;
					cclist.codeA=listcloopobj.codeA;
					cclist.codeB=listcloopobj.codeB;
					cclist.codeC=listcloopobj.codeC;
					cclist.codeD=listcloopobj.codeD;
					cclist.type=listcloopobj.type;
					cclist.code_name=listcloopobj.code_name;
					cclist.list_type=listcloopobj.list_type;
					cclist.detail_type=listcloopobj.detail_type;
					cclist.sequence=jjj;
					cclist.sort=listcloopobj.sort;
					cclist.group_code=listcloopobj.group_code;
					cclist.selected=listcloopobj.selected;
					cclist.display=listcloopobj.display;
					cclist.open=listcloopobj.open;

					jjjj=0;
					for(iiii=0;iiii<listdcount[listcloopobj.codeA+listcloopobj.codeB+listcloopobj.codeC];iiii++) {
						listdloopobj = listdloop[listcloopobj.codeA+listcloopobj.codeB+listcloopobj.codeC+iiii.toString()];
						var cdlist=new CodeDList();
						cdlist.code=listdloopobj.code;
						cdlist.codeA=listdloopobj.codeA;
						cdlist.codeB=listdloopobj.codeB;
						cdlist.codeC=listdloopobj.codeC;
						cdlist.codeD=listdloopobj.codeD;
						cdlist.type=listdloopobj.type;
						cdlist.code_name=listdloopobj.code_name;
						cdlist.list_type=listdloopobj.list_type;
						cdlist.detail_type=listdloopobj.detail_type;
						cdlist.sequence=jjjj;
						cdlist.sort=listdloopobj.sort;
						cdlist.group_code=listdloopobj.group_code;
						cdlist.selected=listdloopobj.selected;
						cdlist.display=listdloopobj.display;
						cdlist.open=listdloopobj.open;

						cclist.ArrCodeD[jjjj]=cdlist;
						cdlist=null;
						jjjj++;
					}
					cblist.ArrCodeC[jjj]=cclist;
					cclist=null;
					jjj++;
				}
				calist.ArrCodeB[jj]=cblist;
				cblist=null;
				jj++;
			}
			all_list[i] = calist;
			calist=null;
			j++;
		}
	}

	ChangeSelect(selcode);
	CodeInit_cnt++;
	//BodyInit();
	//CodeProcessFun(selcode);
}

function addCodeDiv(id,html,display) {
	var newDiv=document.createElement("div"); 
	newDiv.id=id;
	newDiv.style.display=display;
	newDiv.style.height="17";
	newDiv.innerHTML=html;
	document.getElementById('code_list').appendChild(newDiv); 
}

var fontcolorid = "";
var temphtml="";
function BodyInit(codeValue) {
	if(codeValue.length==0 || CodeInit_cnt==0) { 
		document.getElementById('code_list').innerHTML="";
		for(i=0;i<all_list.length;i++) {
			tmpcode=all_list[i].code;
			plusimg="<img width=11 height=0><img id=\"img_"+tmpcode+"\" src=\"images/directory_folder_close.gif\" align=absmiddle onclick=\"ChangeCloseOpen('"+tmpcode+"');\"> ";
			if(all_list[i].ArrCodeB.length<=0) plusimg="<img width=20 height=0>";
			else if(all_list[i].display=="show" && all_list[i].open=="open") {
				plusimg="<img width=11 height=0><img id=\"img_"+tmpcode+"\" src=\"images/directory_folder_open.gif\" align=absmiddle onclick=\"ChangeCloseOpen('"+tmpcode+"');\"> ";
				all_list[i].open="open";
			}

			strcodename=all_list[i].code_name;
			if(all_list[i].list_type.substring(0,1)=="B") {
				strcodename+="(������)";
			}
			folder_gbn="1";
			if(all_list[i].type.substring(1,2)=="X") folder_gbn="3";
			if(all_list[i].type.substring(0,1)=="T") folder_gbn+="T";
			if(all_list[i].type.substring(0,1)=="S") folder_gbn+="S";
			fontbgcolor="#FFFFFF";
			if(all_list[i].selected==true) {
				fontbgcolor="#dddddd";
				fontcolorid=tmpcode;
			}

			temphtml=plusimg+" <img src=\"images/directory_folder"+folder_gbn+".gif\" align=absmiddle> <span id=\"span_"+tmpcode+"\" style=\"cursor:default;background-color:"+fontbgcolor+"\" onmouseover=\"this.className='link_over'\" onmouseout=\"this.className='link_out'\" onclick=\"ChangeSelect('"+tmpcode+"')\">"+strcodename+"</span>";
			tempdisplay="";
			addCodeDiv("div_"+tmpcode,temphtml,tempdisplay);

			for(ii=0;ii<all_list[i].ArrCodeB.length;ii++) {
				tmpcode=all_list[i].ArrCodeB[ii].code;
				plusimg="<img width=29 height=0><img id=\"img_"+tmpcode+"\" src=\"images/directory_folder_close.gif\" align=absmiddle onclick=\"ChangeCloseOpen('"+tmpcode+"');\"> ";
				if(all_list[i].ArrCodeB[ii].ArrCodeC.length<=0) {
					plusimg="<img width=38 height=0>";
				} else if(all_list[i].ArrCodeB[ii].display=="show" && all_list[i].ArrCodeB[ii].open=="open") {
					plusimg="<img width=29 height=0><img id=\"img_"+tmpcode+"\" src=\"images/directory_folder_open.gif\" align=absmiddle onclick=\"ChangeCloseOpen('"+tmpcode+"');\"> ";
					all_list[i].ArrCodeB[ii].open="open";
				}
				strcodename=all_list[i].ArrCodeB[ii].code_name;
				if(all_list[i].ArrCodeB[ii].list_type.substring(0,1)=="B") {
					strcodename+="(������)";
				}
				folder_gbn="1";
				if(all_list[i].ArrCodeB[ii].type.substring(2,3)=="X") folder_gbn="3";
				if(all_list[i].ArrCodeB[ii].type.substring(0,1)=="T") folder_gbn+="T";
				if(all_list[i].ArrCodeB[ii].type.substring(0,1)=="S") folder_gbn+="S";

				fontbgcolor="#FFFFFF";
				if(all_list[i].ArrCodeB[ii].selected==true) {
					fontbgcolor="#dddddd";
					fontcolorid=tmpcode;
				}

				temphtml=plusimg+" <img src=\"images/directory_folder"+folder_gbn+".gif\" align=absmiddle> <span id=\"span_"+tmpcode+"\" style=\"cursor:default;background-color:"+fontbgcolor+"\" onmouseover=\"this.className='link_over'\" onmouseout=\"this.className='link_out'\" onclick=\"ChangeSelect('"+tmpcode+"')\">"+strcodename+"</span>";
				tempdisplay="none";
				if(all_list[i].ArrCodeB[ii].display!="none") tempdisplay="";
				addCodeDiv("div_"+tmpcode,temphtml,tempdisplay);

				for(iii=0;iii<all_list[i].ArrCodeB[ii].ArrCodeC.length;iii++) {
					tmpcode=all_list[i].ArrCodeB[ii].ArrCodeC[iii].code;
					plusimg="<img width=48 height=0><img id=\"img_"+tmpcode+"\" src=\"images/directory_folder_close.gif\" align=absmiddle onclick=\"ChangeCloseOpen('"+tmpcode+"');\"> ";
					if(all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD.length<=0) {
						plusimg="<img width=57 height=0>";
					} else if(all_list[i].ArrCodeB[ii].ArrCodeC[iii].display=="show" && all_list[i].ArrCodeB[ii].ArrCodeC[iii].open=="open") {
						plusimg="<img width=48 height=0><img id=\"img_"+tmpcode+"\" src=\"images/directory_folder_open.gif\" align=absmiddle onclick=\"ChangeCloseOpen('"+tmpcode+"');\"> ";
						all_list[i].ArrCodeB[ii].ArrCodeC[iii].open="open";
					}
					strcodename=all_list[i].ArrCodeB[ii].ArrCodeC[iii].code_name;
					if(all_list[i].ArrCodeB[ii].ArrCodeC[iii].list_type.substring(0,1)=="B") {
						strcodename+="(������)";
					}
					folder_gbn="1";
					if(all_list[i].ArrCodeB[ii].ArrCodeC[iii].type.substring(2,3)=="X") folder_gbn="3";
					if(all_list[i].ArrCodeB[ii].ArrCodeC[iii].type.substring(0,1)=="T") folder_gbn+="T";
					if(all_list[i].ArrCodeB[ii].ArrCodeC[iii].type.substring(0,1)=="S") folder_gbn+="S";

					fontbgcolor="#FFFFFF";
					if(all_list[i].ArrCodeB[ii].ArrCodeC[iii].selected==true) {
						fontbgcolor="#dddddd";
						fontcolorid=tmpcode;
					}

					temphtml=plusimg+" <img src=\"images/directory_folder"+folder_gbn+".gif\" align=absmiddle> <span id=\"span_"+tmpcode+"\" style=\"cursor:default;background-color:"+fontbgcolor+"\" onmouseover=\"this.className='link_over'\" onmouseout=\"this.className='link_out'\" onclick=\"ChangeSelect('"+tmpcode+"')\">"+strcodename+"</span>";
					tempdisplay="none";
					if(all_list[i].ArrCodeB[ii].ArrCodeC[iii].display!="none") tempdisplay="";
					addCodeDiv("div_"+tmpcode,temphtml,tempdisplay);
					
					if(CodeInit_cnt==0 && codeValue.substring(0,9)==tmpcode.substring(0,9)) {
						tempdisplaydefault="";
					} else {
						tempdisplaydefault="none";
					}

					for(iiii=0;iiii<all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD.length;iiii++) {
						tmpcode=all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].code;
						plusimg="<img width=76 height=0>";
						strcodename=all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].code_name;
						if(all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].list_type.substring(0,1)=="B") {
							strcodename+="(������)";
						}

						folder_gbn="3";
						if(all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].type.substring(1,1)=="T") {
							folder_gbn+="T";
						}
						if(all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].type.substring(1,1)=="S") {
							folder_gbn+="S";
						}

						fontbgcolor="#FFFFFF";
						if(all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].selected==true) {
							fontbgcolor="#dddddd";
							fontcolorid=tmpcode;
						}

						temphtml=plusimg+" <img src=\"images/directory_folder"+folder_gbn+".gif\" align=absmiddle> <span id=\"span_"+tmpcode+"\" style=\"cursor:default;background-color:"+fontbgcolor+"\" onmouseover=\"this.className='link_over'\" onmouseout=\"this.className='link_out'\" onclick=\"ChangeSelect('"+tmpcode+"')\">"+strcodename+"</span>";
						tempdisplay=tempdisplaydefault;
						if(all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].display!="none") tempdisplay="";
						addCodeDiv("div_"+tmpcode,temphtml,tempdisplay);
					}
				}
			}
		}
	}
}

function ChangeSelect(_code) {
	//if(_code.length==12) {
		ChangeCloseOpen(_code,"1");
	//} else {
	//	CodeProcessFun(_code);
	//}
}

function ChangeCloseOpen(_code,op) {
	var codeoutcheck = _code;
	if(_code=="out") {
		_code="";
	}

	codeA=_code.substring(0,3);
	codeB=_code.substring(3,6);
	codeC=_code.substring(6,9);
	codeD=_code.substring(9,12);
	
	if(fontcolorid.length>0) {
		if(document.getElementById("span_"+fontcolorid)) {
			document.getElementById("span_"+fontcolorid).style.backgroundColor="#FFFFFF";
		} else {
			fontcolorid="";
		}
	}

	if(codeD=="000") {
		if(codeC=="000") {
			if(codeB=="000") {
				if(codeA=="000") {
					fontcolorid="";
				} else {
					fontcolorid=codeA+"000000000";
				}
			} else {
				fontcolorid=codeA+codeB+"000000";
			}
		} else {
			fontcolorid=codeA+codeB+codeC+"000";
		}
	} else {
		fontcolorid=codeA+codeB+codeC+codeD;
	}
	
	if(fontcolorid.length>0) {
		if(document.getElementById("span_"+fontcolorid)) {
			document.getElementById("span_"+fontcolorid).style.backgroundColor="#DDDDDD";
		}
	}

	selcode_name="";
	for(i=0;i<all_list.length;i++) {
		all_list[i].selected=false;
		if(codeA==all_list[i].codeA) {
			if(codeB.length==3 && codeB=="000") {	//��з�
				tmpcode=all_list[i].code;
				tmpdiv = document.getElementById("div_"+tmpcode);
				gbn=all_list[i].open;
				if(gbn=="open") {
					gbn="close";
					if(op!="1") {
						all_list[i].display="none";
					}
				} else if(gbn=="close") {
					gbn="open";
					all_list[i].display="show";
					if(tmpdiv) { tmpdiv.style.display=""; }
				}
				if(op!="1" || (op=="1" && gbn=="open")) {
					all_list[i].open=gbn;
					if(typeof(document.all["img_"+_code])=="object") {
						document.all["img_"+_code].src="images/directory_folder_"+gbn+".gif";
					}
				}

				if(_code==tmpcode) {
					all_list[i].selected=true;
					selcode=tmpcode;
					seltype=all_list[i].type;
					selcode_name+=all_list[i].code_name;
					sel_list_type=all_list[i].list_type;
					sel_detail_type=all_list[i].detail_type;
				}

				for(ii=0;ii<all_list[i].ArrCodeB.length;ii++) {
					all_list[i].ArrCodeB[ii].selected=false;
					tmpcode=all_list[i].ArrCodeB[ii].code;
					tmpdiv = document.getElementById("div_"+tmpcode);
					if(gbn=="close") {
						if(op!="1") {
							all_list[i].ArrCodeB[ii].display="none";
							if(tmpdiv) { tmpdiv.style.display="none"; }
						}
					} else if(gbn=="open") {
						all_list[i].ArrCodeB[ii].display="show";
						if(tmpdiv) { tmpdiv.style.display=""; }
					}
					for(iii=0;iii<all_list[i].ArrCodeB[ii].ArrCodeC.length;iii++) {
						all_list[i].ArrCodeB[ii].ArrCodeC[iii].selected=false;
						tmpcode=all_list[i].ArrCodeB[ii].ArrCodeC[iii].code;
						tmpdiv = document.getElementById("div_"+tmpcode);
						if(all_list[i].ArrCodeB[ii].open=="open") {
							if(gbn=="close") {
								if(op!="1") {
									all_list[i].ArrCodeB[ii].ArrCodeC[iii].display="none";
									if(tmpdiv) { tmpdiv.style.display="none"; }
								}
							} else if(gbn=="open") {
								all_list[i].ArrCodeB[ii].ArrCodeC[iii].display="show";
								if(tmpdiv) { tmpdiv.style.display=""; }
							}
						} else {
							all_list[i].ArrCodeB[ii].ArrCodeC[iii].display="none";
							if(tmpdiv) { tmpdiv.style.display="none"; }
						}
						for(iiii=0;iiii<all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD.length;iiii++) {
							all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].selected=false;
							tmpcode=all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].code;
							tmpdiv = document.getElementById("div_"+tmpcode);
							if(all_list[i].ArrCodeB[ii].open=="open" && all_list[i].ArrCodeB[ii].ArrCodeC[iii].open=="open") {
								if(gbn=="close") {
									if(op!="1") {
										all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].display="none";
										if(tmpdiv) { tmpdiv.style.display="none"; }
									}
								} else if(gbn=="open") {
									all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].display="show";
									if(tmpdiv) { tmpdiv.style.display=""; }
								}
							} else {
								if(op!="1") {
									all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].display="none";
									if(tmpdiv) { tmpdiv.style.display="none"; }
								}
							}
						}
					}
				}

			} else if(codeC.length==3 && codeC=="000") {
				for(ii=0;ii<all_list[i].ArrCodeB.length;ii++) {
					all_list[i].ArrCodeB[ii].selected=false;
					if(codeA==all_list[i].ArrCodeB[ii].codeA && codeB==all_list[i].ArrCodeB[ii].codeB) {
						gbn=all_list[i].ArrCodeB[ii].open;
						if(gbn=="open") {
							gbn="close";
						} else if(gbn=="close") {
							gbn="open";
						}

						if(op!="1" || (op=="1" && gbn=="open")) {
							all_list[i].ArrCodeB[ii].open=gbn;
							if(typeof(document.all["img_"+_code])=="object") {
								document.all["img_"+_code].src="images/directory_folder_"+gbn+".gif";
							}
						}

						tmpcode=all_list[i].ArrCodeB[ii].code;
						if(_code==tmpcode) {
							all_list[i].ArrCodeB[ii].selected=true;
							selcode=tmpcode;
							seltype=all_list[i].ArrCodeB[ii].type;
							selcode_name+=all_list[i].code_name+" > "+all_list[i].ArrCodeB[ii].code_name;
							sel_list_type=all_list[i].ArrCodeB[ii].list_type;
							sel_detail_type=all_list[i].ArrCodeB[ii].detail_type;
						}
						for(iii=0;iii<all_list[i].ArrCodeB[ii].ArrCodeC.length;iii++) {
							all_list[i].ArrCodeB[ii].ArrCodeC[iii].selected=false;
							tmpcode=all_list[i].ArrCodeB[ii].ArrCodeC[iii].code;
							tmpdiv = document.getElementById("div_"+tmpcode);
							if(all_list[i].open=="open" && all_list[i].ArrCodeB[ii].open=="open") {
								if(gbn=="close") {
									if(op!="1") {
										all_list[i].ArrCodeB[ii].ArrCodeC[iii].display="none";
										if(tmpdiv) { tmpdiv.style.display="none"; }
									}
								} else if(gbn=="open") {
									all_list[i].ArrCodeB[ii].ArrCodeC[iii].display="show";
									if(tmpdiv) { tmpdiv.style.display=""; }
								}
							} else {
								if(op!="1") {
									all_list[i].ArrCodeB[ii].ArrCodeC[iii].display="none";
									if(tmpdiv) { tmpdiv.style.display="none"; }
								}
							}
							for(iiii=0;iiii<all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD.length;iiii++) {
								all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].selected=false;
								tmpcode=all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].code;
								tmpdiv = document.getElementById("div_"+tmpcode);
								if(all_list[i].open=="open" && all_list[i].ArrCodeB[ii].open=="open" && all_list[i].ArrCodeB[ii].ArrCodeC[iii].open=="open") {
									if(gbn=="close") {
										if(op!="1") {
											all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].display="none";
											if(tmpdiv) { tmpdiv.style.display="none"; }
										}
									} else if(gbn=="open") {
										all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].display="show";
										if(tmpdiv) { tmpdiv.style.display=""; }
									}
								} else {
									if(op!="1") {
										all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].display="none";
										if(tmpdiv) { tmpdiv.style.display="none"; }
									}
								}
							}
						}
					} else {
						for(iii=0;iii<all_list[i].ArrCodeB[ii].ArrCodeC.length;iii++) {
							all_list[i].ArrCodeB[ii].ArrCodeC[iii].selected=false;
							for(iiii=0;iiii<all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD.length;iiii++) {
								all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].selected=false;
							}
						}
					}
				}

			} else if(codeD.length==3 && codeD=="000") {
				for(ii=0;ii<all_list[i].ArrCodeB.length;ii++) {
					all_list[i].ArrCodeB[ii].selected=false;

					if(codeA==all_list[i].ArrCodeB[ii].codeA && codeB==all_list[i].ArrCodeB[ii].codeB) {
						for(iii=0;iii<all_list[i].ArrCodeB[ii].ArrCodeC.length;iii++) {
							all_list[i].ArrCodeB[ii].ArrCodeC[iii].selected=false;
							if(codeA==all_list[i].ArrCodeB[ii].codeA && codeB==all_list[i].ArrCodeB[ii].codeB && codeC==all_list[i].ArrCodeB[ii].ArrCodeC[iii].codeC) {
								gbn=all_list[i].ArrCodeB[ii].ArrCodeC[iii].open;
								if(gbn=="open") {
									gbn="close";
								} else if(gbn=="close") {
									gbn="open";
								}
								
								if(op!="1" || (op=="1" && gbn=="open")) {
									all_list[i].ArrCodeB[ii].ArrCodeC[iii].open=gbn;
									if(typeof(document.all["img_"+_code])=="object") {
										document.all["img_"+_code].src="images/directory_folder_"+gbn+".gif";
									}
								}

								tmpcode=all_list[i].ArrCodeB[ii].ArrCodeC[iii].code;
								if(_code==tmpcode) {
									all_list[i].ArrCodeB[ii].ArrCodeC[iii].selected=true;
									selcode=tmpcode;
									seltype=all_list[i].ArrCodeB[ii].ArrCodeC[iii].type;
									selcode_name+=all_list[i].code_name+" > "+all_list[i].ArrCodeB[ii].code_name+" > "+all_list[i].ArrCodeB[ii].ArrCodeC[iii].code_name;
									sel_list_type=all_list[i].ArrCodeB[ii].ArrCodeC[iii].list_type;
									sel_detail_type=all_list[i].ArrCodeB[ii].ArrCodeC[iii].detail_type;
								}

								for(iiii=0;iiii<all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD.length;iiii++) {
									all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].selected=false;
									tmpcode=all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].code;
									tmpdiv = document.getElementById("div_"+tmpcode);
									if(all_list[i].open=="open" && all_list[i].ArrCodeB[ii].open=="open" && all_list[i].ArrCodeB[ii].ArrCodeC[iii].open=="open") {
										if(gbn=="close") {
											if(op!="1") {
												all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].display="none";
												if(tmpdiv) { tmpdiv.style.display="none"; }
											}
										} else if(gbn=="open") {
											all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].display="show";
											if(tmpdiv) { tmpdiv.style.display=""; }
										}
									} else {
										if(op!="1") {
											all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].display="none";
											if(tmpdiv) { tmpdiv.style.display="none"; }
										}
									}
								}
							} else {
								for(iiii=0;iiii<all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD.length;iiii++) {
									all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].selected=false;
								}
							}
						}
					} else {
						for(iii=0;iii<all_list[i].ArrCodeB[ii].ArrCodeC.length;iii++) {
							all_list[i].ArrCodeB[ii].ArrCodeC[iii].selected=false;
							for(iiii=0;iiii<all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD.length;iiii++) {
								all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].selected=false;
							}
						}
					}
				}
			} else {
				for(ii=0;ii<all_list[i].ArrCodeB.length;ii++) {
					all_list[i].ArrCodeB[ii].selected=false;

					if(codeA==all_list[i].ArrCodeB[ii].codeA && codeB==all_list[i].ArrCodeB[ii].codeB) {
						for(iii=0;iii<all_list[i].ArrCodeB[ii].ArrCodeC.length;iii++) {
							all_list[i].ArrCodeB[ii].ArrCodeC[iii].selected=false;
							if(codeA==all_list[i].ArrCodeB[ii].codeA && codeB==all_list[i].ArrCodeB[ii].codeB && codeC==all_list[i].ArrCodeB[ii].ArrCodeC[iii].codeC) {
								for(iiii=0;iiii<all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD.length;iiii++) {
									all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].selected=false;
									tmpcode=all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].code;
									if(_code==tmpcode) {
										all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].selected=true;
										selcode=tmpcode;
										seltype=all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].type;
										selcode_name+=all_list[i].code_name+" > "+all_list[i].ArrCodeB[ii].code_name+" > "+all_list[i].ArrCodeB[ii].ArrCodeC[iii].code_name+" > "+all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].code_name;
										sel_list_type=all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].list_type;
										sel_detail_type=all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].detail_type;
									} else {
										all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].selected=false;
									}
								}
							} else {
								for(iiii=0;iiii<all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD.length;iiii++) {
									all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].selected=false;
								}
							}
						}
					} else {
						for(iii=0;iii<all_list[i].ArrCodeB[ii].ArrCodeC.length;iii++) {
							all_list[i].ArrCodeB[ii].ArrCodeC[iii].selected=false;
							for(iiii=0;iiii<all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD.length;iiii++) {
								all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].selected=false;
							}
						}
					}
				}
			}
		} else {
			all_list[i].selected=false;
			tmpcode=all_list[i].code;
			for(ii=0;ii<all_list[i].ArrCodeB.length;ii++) {
				tmpcode=all_list[i].ArrCodeB[ii].code;
				all_list[i].ArrCodeB[ii].selected=false;
				for(iii=0;iii<all_list[i].ArrCodeB[ii].ArrCodeC.length;iii++) {
					tmpcode=all_list[i].ArrCodeB[ii].ArrCodeC[iii].code;
					all_list[i].ArrCodeB[ii].ArrCodeC[iii].selected=false;
					for(iiii=0;iiii<all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD.length;iiii++) {
						tmpcode=all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].code;
						all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].selected=false;
					}
				}
			}
		}
	}
	
	CodeProcessFun(codeoutcheck);
}
<?
}
?>