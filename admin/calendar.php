<?
$year=$_GET["year"];
$month=$_GET["month"];
$day=$_GET["day"];
if(strlen($year)!=4) $year=date("Y");
if(strlen($month)!=2) $month=date("m");
if(strlen($day)!=2) $day=date("d");
?>

<HTML>
<HEAD>
<TITLE></TITLE>
</HEAD>
<BODY>
<SCRIPT LANGUAGE="JavaScript">
<!--
function doOver() {																// ���콺�� Į�������� ������
	var el = window.event.srcElement;
	cal_Day = el.title;

	if (cal_Day.length > 7) {													// ���� ���� ������.
		el.style.borderTopColor = el.style.borderLeftColor = "buttonhighlight";
		el.style.borderRightColor = el.style.borderBottomColor = "buttonshadow";
	}
}

function doClick() {															// ���ڸ� �����Ͽ��� ���
	cal_Day = window.event.srcElement.title;
	window.event.srcElement.style.borderColor = "red";							// �׵θ� ���� ����������
	if (cal_Day.length > 7) {													// ���� ����������
		window.returnValue=cal_Day;
		window.close();
	}
}

function doOut() {
	var el = window.event.fromElement;
	cal_Day = el.title;

	if (cal_Day.length > 7) {
		el.style.borderColor = "white";
	}
}

function day2(d) {																// 2�ڸ� ���ڷ� ����
	var str = new String();
	
	if (parseInt(d) < 10) {
		str = "0" + parseInt(d);
	} else {
		str = "" + parseInt(d);
	}
	return str;
}

function Show_cal(sYear, sMonth, sDay) {
	var Months_day = new Array(0,31,28,31,30,31,30,31,31,30,31,30,31)
	var Weekday_name = new Array("��", "��", "ȭ", "��", "��", "��", "��");
	var intThisYear = new Number(), intThisMonth = new Number(), intThisDay = new Number();
	datToday = new Date();													// ���� ���� ����
	
	intThisYear = parseInt(sYear);
	intThisMonth = parseInt(sMonth);
	intThisDay = parseInt(sDay);
	
	if (intThisYear == 0) intThisYear = datToday.getFullYear();				// ���� ���� ���
	if (intThisMonth == 0) intThisMonth = parseInt(datToday.getMonth())+1;	// �� ���� ������ ���� -1 �� ���� �ŵ��� ����.
	if (intThisDay == 0) intThisDay = datToday.getDate();
	
	switch(intThisMonth) {
		case 1:
				intPrevYear = intThisYear -1;
				intPrevMonth = 12;
				intNextYear = intThisYear;
				intNextMonth = 2;
				break;
		case 12:
				intPrevYear = intThisYear;
				intPrevMonth = 11;
				intNextYear = intThisYear + 1;
				intNextMonth = 1;
				break;
		default:
				intPrevYear = intThisYear;
				intPrevMonth = parseInt(intThisMonth) - 1;
				intNextYear = intThisYear;
				intNextMonth = parseInt(intThisMonth) + 1;
				break;
	}

	NowThisYear = datToday.getFullYear();										// ���� ��
	NowThisMonth = datToday.getMonth()+1;										// ���� ��
	NowThisDay = datToday.getDate();											// ���� ��
	
	datFirstDay = new Date(intThisYear, intThisMonth-1, 1);						// ���� ���� 1�Ϸ� ���� ��ü ����(���� 0���� 11������ ����(1������ 12��))
	intFirstWeekday = datFirstDay.getDay();										// ���� �� 1���� ������ ���� (0:�Ͽ���, 1:������)
	
	intSecondWeekday = intFirstWeekday;
	intThirdWeekday = intFirstWeekday;
	
	datThisDay = new Date(intThisYear, intThisMonth, intThisDay);				// �Ѿ�� ���� ���� ����
	intThisWeekday = datThisDay.getDay();										// �Ѿ�� ������ �� ����

	varThisWeekday = Weekday_name[intThisWeekday];								// ���� ���� ����
	
	intPrintDay = 1																// ���� ���� ����
	secondPrintDay = 1
	thirdPrintDay = 1
	
	Stop_Flag = 0
	
	if ((intThisYear % 4)==0) {													// 4�⸶�� 1���̸� (��γ����� ��������)
		if ((intThisYear % 100) == 0) {
			if ((intThisYear % 400) == 0) {
				Months_day[2] = 29;
			}
		} else {
			Months_day[2] = 29;
		}
	}
	intLastDay = Months_day[intThisMonth];										// ������ ���� ����
	Stop_flag = 0
	
	Cal_HTML = "<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0 ONMOUSEOVER=doOver(); ONMOUSEOUT=doOut(); STYLE='font-size:8pt;font-family:Tahoma;'>"
			+ "<tr><td colspan=7 height=3></td></tr>"
			+ "<TR ALIGN=CENTER><TD COLSPAN=7 nowrap=nowrap ALIGN=CENTER><SPAN TITLE='������' STYLE=cursor:hand; onClick='Show_cal("+intPrevYear+","+intPrevMonth+",1);'><FONT COLOR=Navy>��</FONT></SPAN> "
			+ "<B STYLE=color:red>"+get_Yearinfo(intThisYear,intThisMonth,intThisDay)+"�� "+get_Monthinfo(intThisYear,intThisMonth,intThisDay)+"��</B>"
			+ " <SPAN TITLE='������' STYLE=cursor:hand; onClick='Show_cal("+intNextYear+","+intNextMonth+",1);'><FONT COLOR=Navy>��</FONT></SPAN></TD></TR>"
			+ "<tr><td colspan=7 height=3></td></tr>"
			+ "<TR height=19 ALIGN=CENTER BGCOLOR=#FDE4E1 STYLE='color:Navy;font-weight:bold;'><TD>��</TD><TD>��</TD><TD>ȭ</TD><TD>��</TD><TD>��</TD><TD>��</TD><TD>��</TD></TR>";
			
	for (intLoopWeek=1; intLoopWeek < 7; intLoopWeek++) {						// �ִ��� ���� ����, �ִ� 6��
		Cal_HTML += "<TR ALIGN=RIGHT BGCOLOR=WHITE>"
		for (intLoopDay=1; intLoopDay <= 7; intLoopDay++) {						// ���ϴ��� ���� ����, �Ͽ��� ����
			if (intThirdWeekday > 0) {											// ù�� �������� 1���� ũ��
				Cal_HTML += "<TD onClick=doClick(); style=\"line-height:11pt;\">";
				intThirdWeekday--;
			} else {
				if (thirdPrintDay > intLastDay) {								// �Է� ��¦ �������� ũ�ٸ�
					Cal_HTML += "<TD onClick=doClick(); style=\"line-height:11pt;\">";
				} else {														// �Է³�¥�� ������� �ش� �Ǹ�
					Cal_HTML += "<TD onClick=doClick(); title="+intThisYear+"-"+day2(intThisMonth).toString()+"-"+day2(thirdPrintDay).toString()+" STYLE=\"cursor:Hand;border:1px solid white;line-height:11pt;";
					if (intThisYear == NowThisYear && intThisMonth==NowThisMonth && thirdPrintDay==intThisDay) {
						Cal_HTML += "background-color:cyan;";
					}
					
					switch(intLoopDay) {
						case 1:													// �Ͽ����̸� ���� ������
							Cal_HTML += "color:red;"
							break;
						case 7:
							Cal_HTML += "color:blue;"
							break;
						default:
							Cal_HTML += "color:black;"
							break;
					}
					
					Cal_HTML += "\">"+thirdPrintDay;
					
				}
				thirdPrintDay++;
				
				if (thirdPrintDay > intLastDay) {								// ���� ��¥ ���� ���� ������ ũ�� ������ Ż��
					Stop_Flag = 1;
				}
			}
			Cal_HTML += "</TD>";
		}
		Cal_HTML += "</TR>";
		if (Stop_Flag==1) break;
	}
	Cal_HTML += "</TABLE>";

	document.body.innerHTML=Cal_HTML;
}

function get_Yearinfo(year,month,day) {											// �� ������ �޺� �ڽ��� ǥ��
	var min = 2001;
	//var max = year;
	datToday = new Date();
	max = datToday.getFullYear();

	var i = new Number();
	var str = new String();
	
	str = "<SELECT onChange='Show_cal(this.value,"+month+","+day+");' ONMOUSEOVER=doOver(); style='font-size:8pt'>";
	for (i=min; i<=(max+1); i++) {
		if (i == parseInt(year)) {
			str += "<OPTION VALUE="+i+" selected ONMOUSEOVER=doOver();>"+i+"</OPTION>";
		} else {
			str += "<OPTION VALUE="+i+" ONMOUSEOVER=doOver();>"+i+"</OPTION>";
		}
	}
	str += "</SELECT>";
	return str;
}


function get_Monthinfo(year,month,day) {										// �� ������ �޺� �ڽ��� ǥ��
	var i = new Number();
	var str = new String();
	
	str = "<SELECT onChange='Show_cal("+year+",this.value,"+day+");' ONMOUSEOVER=doOver(); style='font-size:8pt'>";
	for (i=1; i<=12; i++) {
		if (i == parseInt(month)) {
			str += "<OPTION VALUE="+i+" selected ONMOUSEOVER=doOver();>"+i+"</OPTION>";
		} else {
			str += "<OPTION VALUE="+i+" ONMOUSEOVER=doOver();>"+i+"</OPTION>";
		}
	}
	str += "</SELECT>";
	return str;
}
Show_cal('<?=$year?>','<?=$month?>','<?=$day?>');
//-->
</SCRIPT>
</BODY>
</HTML>
