/**
 * Created by x2chi-objet on 2014-10-02.
 */
/************************************************************
 ��� ��
 *************************************************************
 <input type="text" name="birthM" id="birthM" style="width:80px;" readonly>
 <img src="/img/calen.gif" style="cursor:pointer;" onclick="(calStr1.style.display=='none')?calStr1.style.display='':calStr1.style.display='none';" align="absmiddle">
 <span id="calStr1" style="position:absolute;display:none;border:1px solid #d9d9d9;padding:3px;background-color: #FFFFFF;"></span>
 <script>
 //�Լ�ȣ��
 show_cal('19850101','calStr1','birthM');
 //��¥�� �����ϰ� ������ -> show_cal('20040808','calStr1','birthM');
 //calStr�� �ش� �޷��� �÷��� �ϴ� div���̾�(�� ID)
 //birthM ��¥�� �Էµ� ��
 // ''���Է�...
 // Http://www.x2chi.com
 // x2chi@x2chi.com
 </script>

 /////
 <input type="text" name="endD" id="endD" value="<?=date("Ymd")?>" style="width:80px;" readonly>
 <img src="/images/mini_cal_calen.gif" style="cursor:pointer;" onclick="(endDC.style.display=='none') ? endDC.style.display='' : endDC.style.display='none';" align="absmiddle">
 <span id="endDC" style="position:absolute;display:none;border:1px solid #d9d9d9;padding:3px;background-color: #FFFFFF;"></span>
 <script>
 show_cal('<?=date("Ymd")?>','endDC','birthM');
 </script>

 ************************************************************/



//�ڹ��Լ� ���̺귯���� �߰������� �ϴ� �Լ���..
function lpad(str,fill,leng)
{
	var n = leng - str.length;
	var out ="";
	for (i =0; i < n; i++)  out = out + fill;
	out=out+str;
	return out;
}



//parseInt������ '08'�� 8������ �ν��ؼ� 0�̳� NaN�� �����ش�
function toInt(str)
{
	i=0;
	while(1)
	{
		if(str.substr(0,1)=='0') {
			str=str.substr(1,str.length)
		} else {
			break;
		}
		i++;
	}
	return parseInt(str);
}




//����Ͽ� �ش��ϴ� �迭 ��������(�̴� �������̵忡�� �������� �������������);
//var anniversary = new Array();

function show_cal(selectDate,calDivObj,targetObj, dayLimit ) //selectDate�̽��� �Ǵ� ��¥, calDivObj�޷��� �Ѹ� DIV�±� ���̵�, targetObj ���� �Էµ� ��
{
	if(dayLimit==undefined) var dayLimit = 'unLimit';

	//���������� ����
	var selectDate = ''+selectDate; //��������1 - �̽��� �Ǵ� ��¥ ����
	today = new Date();
	var toDate = today.getFullYear() + lpad(''+(today.getMonth()+1),'0',2) + lpad(''+today.getDate(),'0',2); // ���ó�¥ ����
	if (selectDate == '') selectDate=toDate;

	var preMonDate;
	var nextMonDate;
	preMonDate= selectDate.substr(0,4)+lpad(''+(toInt(selectDate.substr(4,2))-1),'0',2)+selectDate.substr(6,2);
	nextMonDate= selectDate.substr(0,4)+lpad(''+(toInt(selectDate.substr(4,2))+1),'0',2)+selectDate.substr(6,2);
	if(selectDate.substr(4,2)=='01') preMonDate= (toInt(selectDate.substr(0,4))-1) + '12' + selectDate.substr(6,2);
	if(selectDate.substr(4,2)=='12') nextMonDate= (toInt(selectDate.substr(0,4))+1) + '01' + selectDate.substr(6,2);

	var firstDay = getFirstDay(selectDate.substr(0,4), selectDate.substr(4,2)); // ù��° ������ ���ڰ�
	var lastDay = getLastDay(selectDate.substr(0,4), selectDate.substr(4,2)); // ������ ������ ���ڰ�
	var daysOfMonth = getDaysOfMonth(selectDate.substr(0,4), selectDate.substr(4,2)); // 28, 29, 30, 31 �� �ϳ�


	var calString;//�޷� HTML�� �����ϱ� ���� ������.
	calString="<table border='0' cellspacing='0' cellpadding='0' style='font-size:11px'>";
	// ��� ��Ʈ��
	calString+="<tr style='color=#0000C6'><td colspan='7' align=center>";

	calString+="<span style=cursor:pointer; OnClick=show_cal('"+ (parseInt(selectDate.substr(0,4))-1)+ selectDate.substr(4,4) +"','"+ calDivObj +"','"+targetObj+"');><img src='/images/mini_cal_pre.gif' border=0 align=absmiddle></span>";
	calString+="&nbsp;";
	calString+="<span OnClick=show_cal('"+ toDate +"','"+ calDivObj +"','"+targetObj+"');><font size='2'><b>"+selectDate.substr(0,4)+"</b></font></span>";
	calString+="&nbsp;";
	calString+="<span style=cursor:pointer; OnClick=show_cal('"+ (parseInt(selectDate.substr(0,4))+1)+ selectDate.substr(4,4) +"','"+ calDivObj +"','"+targetObj+"');><img src='/images/mini_cal_next.gif' border=0 align=absmiddle></span>";
	calString+="&nbsp;";
	calString+="<span style=cursor:pointer; OnClick=show_cal('"+ preMonDate +"','"+ calDivObj +"','"+targetObj+"');><img src='/images/mini_cal_pre.gif' border=0 align=absmiddle></span>";
	calString+="&nbsp;";
	calString+="<span OnClick=show_cal('"+ toDate +"','"+ calDivObj +"','"+targetObj+"');><font size='2' color='#FF6600'><b>"+selectDate.substr(4,2)+"</b></font></span>";
	calString+="&nbsp;";
	calString+="<span style=cursor:pointer; OnClick=show_cal('"+ nextMonDate +"','"+ calDivObj +"','"+targetObj+"');><img src='/images/mini_cal_next.gif' border=0 align=absmiddle></span>";
	calString+="&nbsp;";
	calString+="<span style=cursor:pointer; OnClick="+calDivObj+".style.display='none';><img src='/images/mini_cal_exit.gif' align=absmiddle></span>";

	calString+="</td></tr>";

	// ����
	calString+="<tr height='5'><td colspan='7'></td></tr>";
	calString+="<tr height=19>";
	calString+="<td width='19' align=center style='color=#C60000'>S</td>";
	calString+="<td width='19' align=center>M</td>";
	calString+="<td width='19' align=center>T</td>";
	calString+="<td width='19' align=center>W</td>";
	calString+="<td width='19' align=center>T</td>";
	calString+="<td width='19' align=center>F</td>";
	calString+="<td width='19' align=center style='color=#0000C6'>S</td>";
	calString+="</tr>";

	// �޷� textfield ���
	for (var i=0; i < Math.ceil( (firstDay+daysOfMonth)/7 ); i++) {
		calString+="<tr valign='middle' height='19'>";
		for (var j=1; j <= 7; j++) {
			colNum=i*7+j; //�޷��� �� ĭ�� Į���� ��ȣ�� ����

			if (colNum>firstDay && colNum<firstDay+daysOfMonth+1) //�޷¿� ��¥�� ���;� �Ǵ� ����
			{
				thisDay=colNum-firstDay; //�̳��� ��¥(����)

				//������ ������ �������� ����
				if(colNum%7==1) {tdColor="C60000";}
				else if(colNum%7==0) {tdColor="0000C6";}
				else {tdColor="333333";}

				//������� ���
				/*
				 for(k=0;k<anniversary.length;k++)
				 {
				 if(thisDay==anniversary[k])
				 {
				 thisDay="<a href='http://www.x2chi.com'><b>"+thisDay+"</b></a>";
				 break;
				 }
				 }
				 */

				// ��¥ ���
				if(thisDay<10) thisDay="0"+thisDay;
				chkDate=selectDate.substr(0,4)+selectDate.substr(4,2)+thisDay;

				calString+="<td align=center onClick=miniCal_chk("+chkDate+","+calDivObj+",'"+targetObj+"','"+dayLimit+"'); style=color=#"+tdColor+";cursor:pointer; onMouseOver=this.style.background='#e4e4e4'; onMouseOut=this.style.background='#ffffff';>"+thisDay+"</td>";
			}
			else
			{
				calString+="<td></td>";
			}
		}
		calString+="</tr>";
	}
	calString+="</table>";

	//����� ��Ʈ�������� DIV���̾ �ø���..
	//document.getElementById(calDivObj).innerHTML=calString;
}




/////////////////////////��¥ ���õ� ���� �Լ��� ����////////////////////////////
function getDaysOfMonth(year, month) {
	var DOMonth = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31]; // ��� ���� �ϼ�
	var lDOMonth = [31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31]; // ���� ���� �ϼ�

	if ((year % 4) == 0) {
		if ((year % 100) == 0 && (year % 400) != 0)
			return DOMonth[toInt(month)-1];

		return lDOMonth[toInt(month)-1];
	} else
		return DOMonth[toInt(month)-1];
}

// ù��° ���� ���ϱ�
function getFirstDay(year, month) {
	var tmpDate = new Date();
	tmpDate.setDate(1);
	tmpDate.setMonth(toInt(month)-1);
	tmpDate.setFullYear(year);
	return tmpDate.getDay();
}


// ������ ���� ���ϱ�
function getLastDay(year, month) {
	var tmpDate = new Date();
	tmpDate.setDate( getDaysOfMonth(year,month) );
	tmpDate.setMonth(toInt(month)-1);
	tmpDate.setFullYear(year);
	return tmpDate.getDay();
}
/////////////////////////��¥ ���õ� ���� �Լ��� ��////////////////////////////



// ���� �Է��� DIV �ݱ�
function miniCal_chk(chkDate,calDivObj,targetObj, dayLimit) {
	chkDate = String(chkDate);	
	today = new Date();
	
	if($j('#'+targetObj).data('dayLimit')) alert($j(targetObj).data('dayLimit'));
	var result = "";
	switch ( dayLimit ) {
		case "todayNext" : // ���� ���� ��¥�� ���� ���� �ɼ�
			if( Number(today.getFullYear()) <= Number(chkDate.substring(0,4)) && Number(today.getMonth()+1) <= Number(chkDate.substring(4,6)) && Number(today.getDate()) < Number(chkDate.substring(6,8)) ) {
				result = "todayNext OK";
			} else {
				result = "����("+today.getFullYear()+"/"+(today.getMonth()+1)+"/"+today.getDate()+") ������ ��¥�� �����ϼ���!";
			}
			break;
		case "unLimit" : // ������ ���ð���
			result = "unLimit OK";
			break;
		default :
			result = "OK";
			break;
	}

	if ( result == "todayNext OK" || result == "unLimit OK" || result == "OK" ) {
		document.getElementById( targetObj ).value=chkDate;
		
		calDivObj.style.display='none';
		if( result == "todayNext OK" ) {
			if( typeof form1 != 'undefined' ) priceCalc(form1);
			if( typeof quickfun_form1 != 'undefined' ) priceCalc(quickfun_form1);
		}
	} else {
		alert(result);
	}
}





